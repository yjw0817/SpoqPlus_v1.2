<?php
/**
 * InsightFace 서비스 PHP 연동 예제
 * SPOQ Plus 시스템에서 InsightFace API를 호출하는 방법
 */

class InsightFaceClient {
    private $baseUrl;
    private $timeout;
    
    public function __construct($baseUrl = 'http://localhost:5002', $timeout = 30) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
    }
    
    /**
     * 헬스 체크
     */
    public function healthCheck() {
        return $this->makeRequest('GET', '/api/face/health');
    }
    
    /**
     * 얼굴 등록
     * 
     * @param string $memberId 회원 ID
     * @param string $imageData Base64 인코딩된 이미지 데이터
     * @return array API 응답
     */
    public function registerFace($memberId, $imageData) {
        $data = [
            'member_id' => $memberId,
            'image' => $imageData
        ];
        
        return $this->makeRequest('POST', '/api/face/register', $data);
    }
    
    /**
     * 얼굴 인식
     * 
     * @param string $imageData Base64 인코딩된 이미지 데이터
     * @return array API 응답
     */
    public function recognizeFace($imageData) {
        $data = [
            'image' => $imageData
        ];
        
        return $this->makeRequest('POST', '/api/face/recognize', $data);
    }
    
    /**
     * HTTP 요청 처리
     */
    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => 'CURL Error: ' . $error
            ];
        }
        
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'JSON Decode Error: ' . json_last_error_msg(),
                'response' => $response
            ];
        }
        
        return $decoded;
    }
}

// 사용 예제
function exampleUsage() {
    // InsightFace 클라이언트 초기화
    $client = new InsightFaceClient('http://localhost:5002');
    
    // 1. 헬스 체크
    echo "=== 헬스 체크 ===\n";
    $health = $client->healthCheck();
    print_r($health);
    
    // 2. 얼굴 등록 예제
    echo "\n=== 얼굴 등록 ===\n";
    
    // 이미지 파일을 Base64로 인코딩
    $imagePath = '/path/to/face_image.jpg';
    if (file_exists($imagePath)) {
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageDataWithPrefix = 'data:image/jpeg;base64,' . $imageData;
        
        $memberId = 'MEM001';
        $registerResult = $client->registerFace($memberId, $imageDataWithPrefix);
        
        if ($registerResult['success']) {
            echo "얼굴 등록 성공!\n";
            echo "Face ID: " . $registerResult['face_id'] . "\n";
            echo "품질 점수: " . $registerResult['quality_score'] . "\n";
            echo "안경 감지: " . ($registerResult['glasses_detected'] ? '예' : '아니오') . "\n";
        } else {
            echo "얼굴 등록 실패: " . $registerResult['error'] . "\n";
        }
    }
    
    // 3. 얼굴 인식 예제
    echo "\n=== 얼굴 인식 ===\n";
    
    // 인식할 이미지
    $testImagePath = '/path/to/test_face.jpg';
    if (file_exists($testImagePath)) {
        $testImageData = base64_encode(file_get_contents($testImagePath));
        $testImageDataWithPrefix = 'data:image/jpeg;base64,' . $testImageData;
        
        $recognizeResult = $client->recognizeFace($testImageDataWithPrefix);
        
        if ($recognizeResult['success']) {
            if ($recognizeResult['matched']) {
                echo "얼굴 인식 성공!\n";
                echo "회원 ID: " . $recognizeResult['member_id'] . "\n";
                echo "유사도: " . ($recognizeResult['similarity'] * 100) . "%\n";
                echo "신뢰도: " . $recognizeResult['confidence'] . "\n";
            } else {
                echo "등록된 얼굴을 찾을 수 없습니다.\n";
                echo "최대 유사도: " . ($recognizeResult['similarity'] * 100) . "%\n";
            }
        } else {
            echo "얼굴 인식 실패: " . $recognizeResult['error'] . "\n";
        }
    }
}

// CodeIgniter 4 컨트롤러 예제
namespace App\Controllers;

use CodeIgniter\Controller;

class FaceRecognitionController extends Controller {
    private $insightFaceClient;
    
    public function __construct() {
        $this->insightFaceClient = new InsightFaceClient(
            getenv('INSIGHTFACE_API_URL') ?: 'http://localhost:5002'
        );
    }
    
    /**
     * 얼굴 등록 API
     */
    public function register() {
        $json = $this->request->getJSON(true);
        
        if (!isset($json['member_id']) || !isset($json['image'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => '필수 파라미터가 누락되었습니다.'
            ])->setStatusCode(400);
        }
        
        // InsightFace API 호출
        $result = $this->insightFaceClient->registerFace(
            $json['member_id'],
            $json['image']
        );
        
        // 로깅
        log_message('info', 'Face registration attempt for member: ' . $json['member_id']);
        
        return $this->response->setJSON($result);
    }
    
    /**
     * 얼굴 인식 API
     */
    public function recognize() {
        $json = $this->request->getJSON(true);
        
        if (!isset($json['image'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => '이미지 데이터가 없습니다.'
            ])->setStatusCode(400);
        }
        
        // InsightFace API 호출
        $result = $this->insightFaceClient->recognizeFace($json['image']);
        
        // 인식 성공시 추가 처리
        if ($result['success'] && $result['matched']) {
            // 출입 기록 저장 등의 추가 로직
            $this->saveAccessLog($result['member_id'], $result['similarity']);
        }
        
        return $this->response->setJSON($result);
    }
    
    /**
     * 출입 기록 저장
     */
    private function saveAccessLog($memberId, $similarity) {
        $db = \Config\Database::connect();
        
        $data = [
            'mem_sno' => $memberId,
            'access_time' => date('Y-m-d H:i:s'),
            'access_type' => 'face_recognition',
            'similarity_score' => $similarity,
            'device_info' => $this->request->getUserAgent(),
            'ip_address' => $this->request->getIPAddress()
        ];
        
        $db->table('access_logs')->insert($data);
    }
}

// 마이그레이션 스크립트 예제
class MigrateToInsightFace {
    private $db;
    private $insightFaceClient;
    
    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->insightFaceClient = new InsightFaceClient('http://localhost:5002');
    }
    
    /**
     * 기존 회원 얼굴 데이터를 InsightFace로 마이그레이션
     */
    public function migrate($batchSize = 50) {
        // 기존 얼굴 데이터 조회
        $query = $this->db->query("
            SELECT mf.*, m.mem_id, m.mem_name 
            FROM member_faces mf
            JOIN members m ON mf.mem_sno = m.mem_sno
            WHERE mf.is_active = 1
            LIMIT ?
        ", [$batchSize]);
        
        $results = $query->getResultArray();
        $successCount = 0;
        $failCount = 0;
        
        foreach ($results as $face) {
            echo "Processing member: {$face['mem_id']} ({$face['mem_name']})\n";
            
            // 이미지 파일이 있는 경우
            if (!empty($face['face_image_path']) && file_exists($face['face_image_path'])) {
                $imageData = base64_encode(file_get_contents($face['face_image_path']));
                $imageDataWithPrefix = 'data:image/jpeg;base64,' . $imageData;
                
                // InsightFace로 재등록
                $result = $this->insightFaceClient->registerFace(
                    $face['mem_sno'],
                    $imageDataWithPrefix
                );
                
                if ($result['success']) {
                    $successCount++;
                    echo "✓ Success: {$face['mem_id']}\n";
                    
                    // 마이그레이션 상태 업데이트
                    $this->db->query("
                        UPDATE member_faces 
                        SET migration_status = 'completed',
                            migration_date = NOW()
                        WHERE face_id = ?
                    ", [$face['face_id']]);
                } else {
                    $failCount++;
                    echo "✗ Failed: {$face['mem_id']} - {$result['error']}\n";
                }
            } else {
                echo "⚠ No image file for: {$face['mem_id']}\n";
                $failCount++;
            }
        }
        
        echo "\n=== 마이그레이션 완료 ===\n";
        echo "성공: {$successCount}건\n";
        echo "실패: {$failCount}건\n";
        
        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => count($results)
        ];
    }
}