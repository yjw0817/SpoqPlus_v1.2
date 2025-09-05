<?php
/**
 * SPOQ Plus 체크인 시스템 - Face Recognition 연동
 * 
 * 이 파일은 얼굴 인식을 통한 회원 체크인 처리 예제입니다.
 */

require_once 'InsightFaceClient.php';

class FaceCheckinController {
    private $insightFaceClient;
    private $db;
    
    public function __construct() {
        // InsightFace 클라이언트 초기화
        $faceHost = getenv('FACE_HOST') ?: 'localhost';
        $facePort = getenv('FACE_PORT') ?: '5002';
        $this->insightFaceClient = new InsightFaceClient(
            getenv('FACE_API_URL') ?: "http://{$faceHost}:{$facePort}"
        );
        
        // 데이터베이스 연결
        $this->db = new PDO(
            'mysql:host=localhost;dbname=spoqplus;charset=utf8mb4',
            'username',
            'password'
        );
    }
    
    /**
     * 체크인 처리
     */
    public function processCheckin() {
        try {
            // POST 데이터 받기
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['image'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '이미지 데이터가 없습니다.'
                ], 400);
            }
            
            // 지점 정보 (세션이나 설정에서 가져오기)
            $compCd = $_SESSION['comp_cd'] ?? $_SESSION['comp_cd'] ?? 'C001';
            $bcoffCd = $_SESSION['bcoff_cd'] ?? $_SESSION['bcoff_cd'] ?? 'B001';
            
            // 얼굴 인식 API 호출
            $recognitionResult = $this->insightFaceClient->recognizeFaceForCheckin(
                $input['image'],
                $compCd,      // Python에서 param1으로 자동 변환
                $bcoffCd,     // Python에서 param2로 자동 변환
                3  // 최고 보안 레벨
            );
            
            // 인식 실패
            if (!$recognitionResult['success']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '얼굴 인식에 실패했습니다.',
                    'error' => $recognitionResult['error'] ?? '알 수 없는 오류'
                ]);
            }
            
            // 매칭되지 않음
            if (!$recognitionResult['matched']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '등록된 회원을 찾을 수 없습니다.',
                    'similarity' => $recognitionResult['similarity'] ?? 0
                ]);
            }
            
            // 체크인 허용 여부 확인
            if (!$recognitionResult['checkin_allowed']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '보안 검증에 실패했습니다.',
                    'security_checks' => $recognitionResult['security_checks']
                ]);
            }
            
            // 회원 정보 조회
            $memberInfo = $this->getMemberInfo($recognitionResult['member_id']);
            
            if (!$memberInfo) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '회원 정보를 찾을 수 없습니다.'
                ]);
            }
            
            // 회원권 상태 확인
            $membershipStatus = $this->checkMembershipStatus($memberInfo['mem_sno']);
            
            if (!$membershipStatus['active']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => $membershipStatus['message'],
                    'member_info' => [
                        'name' => $memberInfo['mem_nm'],
                        'member_id' => $memberInfo['mem_id']
                    ]
                ]);
            }
            
            // 체크인 처리
            $checkinResult = $this->performCheckin($memberInfo, $recognitionResult);
            
            if ($checkinResult['success']) {
                return $this->jsonResponse([
                    'success' => true,
                    'message' => '체크인이 완료되었습니다.',
                    'member_info' => [
                        'name' => $memberInfo['mem_nm'],
                        'member_id' => $memberInfo['mem_id'],
                        'photo' => $memberInfo['mem_photo']
                    ],
                    'checkin_info' => [
                        'checkin_id' => $checkinResult['checkin_id'],
                        'checkin_time' => $checkinResult['checkin_time'],
                        'remaining_count' => $membershipStatus['remaining_count'],
                        'expiry_date' => $membershipStatus['expiry_date']
                    ],
                    'recognition_info' => [
                        'similarity' => $recognitionResult['similarity'],
                        'confidence' => $recognitionResult['confidence'],
                        'security_level' => $recognitionResult['security_level']
                    ]
                ]);
            } else {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '체크인 처리 중 오류가 발생했습니다.',
                    'error' => $checkinResult['error']
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Checkin Error: " . $e->getMessage());
            return $this->jsonResponse([
                'success' => false,
                'message' => '시스템 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 회원 정보 조회
     */
    private function getMemberInfo($memberId) {
        $stmt = $this->db->prepare("
            SELECT 
                m.mem_sno, m.mem_id, m.mem_nm, m.mem_telno,
                m.mem_gendr, m.mem_photo, m.comp_cd, m.bcoff_cd,
                m.mem_status
            FROM mem_info_detl_tbl m
            WHERE m.mem_sno = ? AND m.mem_status = 'A'
        ");
        
        $stmt->execute([$memberId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * 회원권 상태 확인
     */
    private function checkMembershipStatus($memSno) {
        $stmt = $this->db->prepare("
            SELECT 
                mp.product_cd, mp.start_date, mp.end_date,
                mp.total_count, mp.used_count, mp.remaining_count,
                mp.status, p.product_nm
            FROM member_products mp
            JOIN products p ON mp.product_cd = p.product_cd
            WHERE mp.mem_sno = ? 
                AND mp.status = 'A'
                AND mp.start_date <= CURDATE()
                AND mp.end_date >= CURDATE()
                AND mp.remaining_count > 0
            ORDER BY mp.end_date ASC
            LIMIT 1
        ");
        
        $stmt->execute([$memSno]);
        $membership = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$membership) {
            return [
                'active' => false,
                'message' => '유효한 회원권이 없습니다.'
            ];
        }
        
        return [
            'active' => true,
            'product_nm' => $membership['product_nm'],
            'remaining_count' => $membership['remaining_count'],
            'expiry_date' => $membership['end_date']
        ];
    }
    
    /**
     * 체크인 수행
     */
    private function performCheckin($memberInfo, $recognitionResult) {
        try {
            $this->db->beginTransaction();
            
            // 체크인 기록 생성
            $stmt = $this->db->prepare("
                INSERT INTO checkin_logs (
                    mem_sno, checkin_time, checkin_type,
                    recognition_score, security_level,
                    comp_cd, bcoff_cd, device_info, ip_address
                ) VALUES (?, NOW(), 'face', ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $memberInfo['mem_sno'],
                $recognitionResult['similarity'],
                $recognitionResult['security_level'],
                $memberInfo['comp_cd'],
                $memberInfo['bcoff_cd'],
                $_SERVER['HTTP_USER_AGENT'] ?? '',
                $_SERVER['REMOTE_ADDR'] ?? ''
            ]);
            
            $checkinId = $this->db->lastInsertId();
            
            // 회원권 사용 처리
            $stmt = $this->db->prepare("
                UPDATE member_products
                SET used_count = used_count + 1,
                    remaining_count = remaining_count - 1,
                    last_used_date = NOW()
                WHERE mem_sno = ? 
                    AND status = 'A'
                    AND remaining_count > 0
                ORDER BY end_date ASC
                LIMIT 1
            ");
            
            $stmt->execute([$memberInfo['mem_sno']]);
            
            // 얼굴 인식 로그는 InsightFace에서 자동 저장됨
            
            $this->db->commit();
            
            return [
                'success' => true,
                'checkin_id' => $checkinId,
                'checkin_time' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * JSON 응답 전송
     */
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 실행
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new FaceCheckinController();
    $controller->processCheckin();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>