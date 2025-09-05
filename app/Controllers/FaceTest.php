<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FaceTest extends Controller
{
    /**
     * 간단한 얼굴 인식 연동 테스트 컨트롤러
     */
    
    public function health()
    {
        try {
            $result = $this->callPythonAPI('/api/face/health');
            
            if ($result['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => '✅ Python 얼굴 인식 서버 연결 성공!',
                    'data' => $result['data'],
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => $result['error']
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function test_register()
    {
        try {
            // 테스트용 더미 이미지 (1x1 픽셀 PNG를 Base64로 인코딩)
            $dummy_image = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==';
            
            // 세션에서 회사/지점 정보 가져오기
            $session = session();
            $param1 = $session->get('comp_cd') ?? 'DEFAULT';
            $param2 = $session->get('bcoff_cd') ?? 'DEFAULT';
            
            $requestData = [
                'member_id' => 'test_' . time(),
                'image' => $dummy_image,
                'param1' => $param1,  // 회사 코드
                'param2' => $param2   // 지점 코드
            ];
            
            log_message('info', '[FaceTest] test_register - param1: ' . $param1 . ', param2: ' . $param2);
            
            $result = $this->callPythonAPI('/api/face/register', 'POST', $requestData);
            
            return $this->response->setJSON([
                'success' => $result['success'],
                'message' => $result['success'] ? '얼굴 등록 테스트 완료' : '얼굴 등록 실패',
                'data' => $result['data'] ?? null,
                'error' => $result['error'] ?? null
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function list_faces()
    {
        try {
            $result = $this->callPythonAPI('/api/face/list');
            
            return $this->response->setJSON([
                'success' => $result['success'],
                'message' => '등록된 얼굴 목록 조회',
                'data' => $result['data'] ?? null,
                'error' => $result['error'] ?? null
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 회원 등록용 얼굴 인식 (얼굴 검출만 수행, 매칭은 하지 않음)
     * POST /FaceTest/recognize_for_registration
     */
    public function recognize_for_registration()
    {
        try {
            log_message('info', '[FaceTest] ========== recognize_for_registration 시작 ==========');
            
            $postVar = $this->request->getPost();
            $jsonInput = $this->request->getJSON(true);
            
            // JSON과 POST 둘 다 지원
            $requestData = $jsonInput ?: $postVar;
            
            log_message('info', '[FaceTest] 요청 타입: ' . ($jsonInput ? 'JSON' : 'POST'));
            log_message('info', '[FaceTest] 요청 데이터 키: ' . implode(', ', array_keys($requestData)));
            
            if (!isset($requestData['image']) || empty($requestData['image'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'face_detected' => false,
                    'error' => '이미지 데이터가 필요합니다.'
                ]);
            }
            
            // Python 서버로 얼굴 검출 요청 (인식이 아닌 검출만)
            $pythonRequest = [
                'image' => $requestData['image'],
                'param1' => $requestData['param1'] ?? session()->get('comp_cd') ?? 'DEFAULT',
                'param2' => $requestData['param2'] ?? session()->get('bcoff_cd') ?? 'DEFAULT'
            ];
            
            log_message('info', '[FaceTest] InsightFace API 호출: /api/face/detect_for_registration');
            log_message('info', '[FaceTest] 이미지 데이터 길이: ' . strlen($requestData['image']));
            log_message('info', '[FaceTest] param1 (comp_cd): ' . $pythonRequest['param1']);
            log_message('info', '[FaceTest] param2 (bcoff_cd): ' . $pythonRequest['param2']);
            
            // detect_for_registration API 호출 (향상된 품질 검증 포함)
            $result = $this->callPythonAPI('/api/face/detect_for_registration', 'POST', $pythonRequest);
            
            log_message('info', '[FaceTest] API 호출 결과: ' . json_encode($result));
            
            // Python 응답 구조 수정: $result['data']는 Python의 전체 응답
            if (!isset($result['data'])) {
                log_message('error', '[FaceTest] API 응답에 data 키가 없음. 전체 응답: ' . json_encode($result));
                return $this->response->setJSON([
                    'success' => false,
                    'face_detected' => false,
                    'error' => 'API 응답 형식 오류'
                ]);
            }
            
            $pythonResponse = $result['data'];
            
           
            
            if ($result['success'] && $pythonResponse['face_detected']) {
                // 얼굴 검출 성공 - Python 응답에서 직접 데이터 추출
                log_message('info', '[FaceTest] ✅ 얼굴 검출 성공!');
                log_message('info', '[FaceTest] Python 응답 구조: ' . json_encode($pythonResponse));
                log_message('info', '[FaceTest] face_encoding 크기: ' . count($pythonResponse['face_encoding'] ?? []));
                
                return $this->response->setJSON([
                    'success' => true,
                    'face_detected' => true,
                    'face_data' => [
                        'face_encoding' => $pythonResponse['face_encoding'] ?? [],
                        'glasses_detected' => $pythonResponse['glasses_detected'] ?? false,
                        'glasses_confidence' => $pythonResponse['glasses_confidence'] ?? 0.0,
                        'quality_score' => $pythonResponse['quality_score'] ?? 0.85,
                        'liveness_check' => $pythonResponse['liveness_check'] ?? [],
                        'security_check' => $pythonResponse['security_check'] ?? [],
                        'processing_time_ms' => $pythonResponse['processing_time_ms'] ?? 0,
                        'embedding_dimensions' => $pythonResponse['embedding_dimensions'] ?? 0,
                        'landmark_count' => $pythonResponse['landmark_count'] ?? 0
                    ],
                    'suitable_for_registration' => $pythonResponse['suitable_for_registration'] ?? false,
                    'recommendations' => $pythonResponse['recommendations'] ?? []
                ]);
            } else {
                // 얼굴 검출 실패
                $errorMessage = $pythonResponse['error'] ?? '얼굴을 검출할 수 없습니다.';
                
                log_message('error', '[FaceTest] ❌ 얼굴 검출 실패: ' . $errorMessage);
                log_message('error', '[FaceTest] Python 응답: ' . json_encode($pythonResponse));
                
                return $this->response->setJSON([
                    'success' => false,
                    'face_detected' => false,
                    'error' => $errorMessage
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'face_detected' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 체크인용 얼굴 인식 (프록시)
     * POST /FaceTest/recognize_for_checkin
     */
    public function recognize_for_checkin()
    {
        try {
            log_message('info', '[FaceTest] ========== recognize_for_checkin 시작 ==========');
            
            $postVar = $this->request->getPost();
            $jsonInput = $this->request->getJSON(true);
            
            // JSON과 POST 둘 다 지원
            $requestData = $jsonInput ?: $postVar;
            
            if (!isset($requestData['image'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => '이미지 데이터가 없습니다.'
                ]);
            }
            
            // 세션에서 회사/지점 정보 가져오기
            $session = session();
            $comp_cd = $session->get('comp_cd') ?? 'DEFAULT';
            $bcoff_cd = $session->get('bcoff_cd') ?? 'DEFAULT';
            
            // 로그로 확인
            log_message('info', '[FaceTest] comp_cd: ' . $comp_cd . ', bcoff_cd: ' . $bcoff_cd);
            
            // InsightFace 서버로 전달할 데이터
            $apiData = [
                'image' => $requestData['image'],
                'comp_cd' => $comp_cd,      // Python에서 param1으로 자동 변환
                'bcoff_cd' => $bcoff_cd,    // Python에서 param2로 자동 변환
                'check_liveness' => $requestData['check_liveness'] ?? true,
                'check_blink' => $requestData['check_blink'] ?? false,
                'security_level' => $requestData['security_level'] ?? 3
            ];
            
            // Python API 호출 (InsightFace 서버)
            $result = $this->callPythonAPI('/api/face/recognize_for_checkin', 'POST', $apiData);
            
            if (!$result['success']) {
                log_message('error', '[FaceTest] API 호출 실패: ' . json_encode($result));
                return $this->response->setJSON([
                    'success' => false,
                    'error' => $result['error'] ?? 'InsightFace 서버 연결 실패'
                ]);
            }
            
            // Python API의 응답을 그대로 전달
            return $this->response->setJSON($result['data']);
            
        } catch (\Exception $e) {
            log_message('error', '[FaceTest] recognize_for_checkin 오류: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Python API 호출 함수
     */
    public function callPythonAPI($endpoint, $method = 'GET', $data = null)
    {
        $baseUrl = 'http://localhost:5002';  // InsightFace 서버
        $url = $baseUrl . $endpoint;
        
        log_message('info', '[FaceTest] API 호출 URL: ' . $url);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($data))
                ]);
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            log_message('error', '[FaceTest] CURL 에러: ' . $curlError);
            return [
                'success' => false,
                'error' => 'CURL Error: ' . $curlError
            ];
        }
        
        log_message('info', '[FaceTest] HTTP 응답 코드: ' . $httpCode);
        log_message('info', '[FaceTest] 원본 응답: ' . substr($response, 0, 500) . '...');
        
        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            return [
                'success' => false,
                'error' => 'HTTP Error ' . $httpCode . ': ' . ($errorData['error'] ?? 'Unknown error')
            ];
        }
        
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'JSON Decode Error: ' . json_last_error_msg()
            ];
        }
        
        return [
            'success' => true,
            'data' => $result
        ];
    }
} 