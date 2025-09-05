<?php
/**
 * SPOQ Plus 회원 정보 관리 - Face Recognition 연동
 * 
 * 회원 정보 조회 및 얼굴 등록/수정 처리
 */

require_once 'InsightFaceClient.php';

class MemberFaceController {
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
     * 회원 정보 및 얼굴 등록 상태 조회
     */
    public function getMemberInfo($memSno) {
        try {
            // 회원 기본 정보 조회
            $stmt = $this->db->prepare("
                SELECT 
                    m.mem_sno, m.mem_id, m.mem_nm, m.mem_telno,
                    m.mem_gendr, m.mem_birth, m.mem_email,
                    m.mem_photo, m.mem_status, m.reg_date,
                    m.comp_cd, m.bcoff_cd,
                    c.comp_nm, b.bcoff_nm
                FROM mem_info_detl_tbl m
                LEFT JOIN company_tbl c ON m.comp_cd = c.comp_cd
                LEFT JOIN bcoff_mgmt_tbl b ON m.bcoff_cd = b.bcoff_cd
                WHERE m.mem_sno = ?
            ");
            
            $stmt->execute([$memSno]);
            $memberInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$memberInfo) {
                return [
                    'success' => false,
                    'message' => '회원 정보를 찾을 수 없습니다.'
                ];
            }
            
            // 얼굴 등록 정보 조회
            $stmt = $this->db->prepare("
                SELECT 
                    face_id, face_encoding IS NOT NULL as has_encoding,
                    face_image_path, glasses_detected,
                    quality_score, security_level, liveness_score,
                    registered_date, last_updated, is_active,
                    notes
                FROM member_faces
                WHERE mem_sno = ?
                ORDER BY registered_date DESC
            ");
            
            $stmt->execute([$memSno]);
            $faceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // 회원권 정보 조회
            $stmt = $this->db->prepare("
                SELECT 
                    mp.product_cd, p.product_nm,
                    mp.start_date, mp.end_date,
                    mp.total_count, mp.remaining_count,
                    mp.status
                FROM member_products mp
                JOIN products p ON mp.product_cd = p.product_cd
                WHERE mp.mem_sno = ?
                    AND mp.status = 'A'
                ORDER BY mp.end_date DESC
            ");
            
            $stmt->execute([$memSno]);
            $memberships = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // 최근 체크인 기록
            $stmt = $this->db->prepare("
                SELECT 
                    checkin_time, checkin_type,
                    recognition_score
                FROM checkin_logs
                WHERE mem_sno = ?
                ORDER BY checkin_time DESC
                LIMIT 10
            ");
            
            $stmt->execute([$memSno]);
            $recentCheckins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'member' => $memberInfo,
                'face_registration' => [
                    'registered' => count($faceRecords) > 0,
                    'active_count' => count(array_filter($faceRecords, function($f) { 
                        return $f['is_active'] == 1; 
                    })),
                    'records' => $faceRecords
                ],
                'memberships' => $memberships,
                'recent_checkins' => $recentCheckins
            ];
            
        } catch (Exception $e) {
            error_log("Member Info Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '회원 정보 조회 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * 얼굴 등록
     */
    public function registerFace() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // 입력 검증
            if (empty($input['mem_sno']) || empty($input['image'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '필수 정보가 누락되었습니다.'
                ], 400);
            }
            
            // 회원 확인
            $memberInfo = $this->getMemberInfo($input['mem_sno']);
            if (!$memberInfo['success']) {
                return $this->jsonResponse($memberInfo, 404);
            }
            
            // 기존 얼굴 데이터 비활성화 옵션
            if (!empty($input['replace_existing']) && $input['replace_existing']) {
                $this->deactivateExistingFaces($input['mem_sno']);
            }
            
            // 얼굴 품질 검사 (등록 전)
            $qualityCheck = $this->insightFaceClient->detectForRegistration($input['image']);
            
            if (!$qualityCheck['success'] || !$qualityCheck['face_detected']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '얼굴을 감지할 수 없습니다.',
                    'details' => $qualityCheck
                ]);
            }
            
            if (!$qualityCheck['recommendations']['suitable_for_registration']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '이미지 품질이 등록 기준에 미달합니다.',
                    'recommendations' => $qualityCheck['recommendations']['messages'],
                    'quality_info' => [
                        'quality_score' => $qualityCheck['quality_score'],
                        'liveness_score' => $qualityCheck['liveness_score'],
                        'face_size_ratio' => $qualityCheck['face_size_ratio']
                    ]
                ]);
            }
            
            // InsightFace에 얼굴 등록
            $registrationResult = $this->insightFaceClient->registerFace(
                $input['mem_sno'],
                $input['image'],
                $input['security_level'] ?? 3,
                $input['notes'] ?? '회원 정보 페이지에서 등록'
            );
            
            if (!$registrationResult['success']) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '얼굴 등록에 실패했습니다.',
                    'error' => $registrationResult['error'] ?? '알 수 없는 오류'
                ]);
            }
            
            // 회원 사진 업데이트 (선택사항)
            if (!empty($input['update_photo']) && $input['update_photo']) {
                $this->updateMemberPhoto($input['mem_sno'], $input['image']);
            }
            
            return $this->jsonResponse([
                'success' => true,
                'message' => '얼굴이 성공적으로 등록되었습니다.',
                'face_id' => $registrationResult['face_id'],
                'quality_score' => $registrationResult['quality_score'],
                'liveness_score' => $registrationResult['liveness_score'],
                'glasses_detected' => $registrationResult['glasses_detected'],
                'security_checks' => $registrationResult['security_checks']
            ]);
            
        } catch (Exception $e) {
            error_log("Face Registration Error: " . $e->getMessage());
            return $this->jsonResponse([
                'success' => false,
                'message' => '얼굴 등록 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 얼굴 재등록
     */
    public function updateFace() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['mem_sno']) || empty($input['image'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '필수 정보가 누락되었습니다.'
                ], 400);
            }
            
            // 기존 얼굴 데이터 비활성화
            $this->deactivateExistingFaces($input['mem_sno']);
            
            // 새로운 얼굴 등록
            return $this->registerFace();
            
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => '얼굴 재등록 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 얼굴 삭제 (비활성화)
     */
    public function deleteFace() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (empty($input['mem_sno'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => '회원 번호가 필요합니다.'
                ], 400);
            }
            
            // 모든 얼굴 데이터 비활성화
            $count = $this->deactivateExistingFaces($input['mem_sno']);
            
            return $this->jsonResponse([
                'success' => true,
                'message' => '얼굴 데이터가 삭제되었습니다.',
                'deleted_count' => $count
            ]);
            
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => '얼굴 삭제 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * 기존 얼굴 데이터 비활성화
     */
    private function deactivateExistingFaces($memSno) {
        $stmt = $this->db->prepare("
            UPDATE member_faces
            SET is_active = 0,
                last_updated = NOW(),
                notes = CONCAT(IFNULL(notes, ''), ' | Deactivated on ', NOW())
            WHERE mem_sno = ? AND is_active = 1
        ");
        
        $stmt->execute([$memSno]);
        return $stmt->rowCount();
    }
    
    /**
     * 회원 사진 업데이트
     */
    private function updateMemberPhoto($memSno, $imageData) {
        try {
            // Base64 데이터에서 실제 이미지 추출
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = base64_decode($imageData);
            
            // 파일 저장 경로
            $uploadDir = '/uploads/member_photos/';
            $fileName = $memSno . '_' . time() . '.jpg';
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir . $fileName;
            
            // 디렉토리 생성
            if (!is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }
            
            // 파일 저장
            file_put_contents($filePath, $imageData);
            
            // DB 업데이트
            $stmt = $this->db->prepare("
                UPDATE mem_info_detl_tbl
                SET mem_photo = ?
                WHERE mem_sno = ?
            ");
            
            $stmt->execute([$uploadDir . $fileName, $memSno]);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Photo Update Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 얼굴 인식 통계
     */
    public function getFaceRecognitionStats($memSno) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_attempts,
                    AVG(confidence_score) as avg_confidence,
                    AVG(similarity_score) as avg_similarity,
                    SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as success_count,
                    MAX(recognition_time) as last_recognition
                FROM face_recognition_logs
                WHERE mem_sno = ?
                    AND recognition_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            
            $stmt->execute([$memSno]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'stats' => $stats
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => '통계 조회 중 오류가 발생했습니다.',
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

// 라우팅
$controller = new MemberFaceController();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'info':
        if (!empty($_GET['mem_sno'])) {
            $result = $controller->getMemberInfo($_GET['mem_sno']);
            $controller->jsonResponse($result);
        }
        break;
        
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->registerFace();
        }
        break;
        
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateFace();
        }
        break;
        
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->deleteFace();
        }
        break;
        
    case 'stats':
        if (!empty($_GET['mem_sno'])) {
            $result = $controller->getFaceRecognitionStats($_GET['mem_sno']);
            $controller->jsonResponse($result);
        }
        break;
        
    default:
        $controller->jsonResponse([
            'success' => false,
            'message' => 'Invalid action'
        ], 400);
}
?>