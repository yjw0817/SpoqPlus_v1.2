<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\FaceRecognitionModel;
use Exception;

/**
 * Face Recognition API Controller
 * 
 * 얼굴 인식 관련 API를 처리하는 컨트롤러
 */
class FaceApi extends ApiController
{
    protected $faceModel;
    
    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Parent 초기화
        parent::initController($request, $response, $logger);
        
        // Face Recognition Model 초기화
        $this->faceModel = new FaceRecognitionModel();
    }
    
    /**
     * 얼굴 인식 서버 상태 확인
     * GET /api/face/health
     */
    public function health_check()
    {
        try {
            $result = $this->callFaceRecognitionAPI('/api/face/health', 'GET');
            return $this->response->setJSON($result);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 얼굴 등록 API
     * POST /api/face/register
     */
    public function register()
    {
        try {
            $postVar = $this->request->getPost();
            $fileData = $this->request->getFile('face_image');
            
            // 필수 파라미터 확인
            if (!isset($postVar['mem_sno']) || empty($postVar['mem_sno'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => '회원번호(mem_sno)가 필요합니다.'
                ]);
            }
            
            // 이미지 처리
            $imageBase64 = $this->processImageData($fileData, $postVar);
            if (!$imageBase64) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => '이미지 데이터가 필요합니다.'
                ]);
            }
            
            // 세션에서 회사/지점 정보 가져오기
            $session = session();
            $param1 = $session->get('comp_cd') ?? 'DEFAULT';
            $param2 = $session->get('bcoff_cd') ?? 'DEFAULT';
            
            // Python 서버로 요청
            $requestData = [
                'member_id' => $postVar['mem_sno'],
                'image' => $imageBase64,
                'param1' => $param1,  // 회사 코드
                'param2' => $param2   // 지점 코드
            ];
            
            log_message('info', '[FaceApi] register - param1: ' . $param1 . ', param2: ' . $param2);
            
            $result = $this->callFaceRecognitionAPI('/api/face/register', 'POST', $requestData);
            
            // 성공한 경우 데이터베이스에 저장
            if ($result['success']) {
                $dbData = [
                    'mem_sno' => $postVar['mem_sno'],
                    'face_encoding' => json_encode($result['data']['face_encoding']),
                    'glasses_detected' => $result['data']['glasses_detected'] ?? false,
                    'quality_score' => $result['data']['quality_score'] ?? 0,
                    'security_level' => $result['data']['security_level'] ?? 3,
                    'liveness_score' => $result['data']['liveness_score'] ?? 0.75,
                    'notes' => 'Registered via API on ' . date('Y-m-d H:i:s')
                ];
                
                $this->faceModel->registerFace($dbData);
                
                // 로그 저장
                $this->saveFaceRecognitionLog([
                    'mem_sno' => $postVar['mem_sno'],
                    'confidence_score' => $result['data']['quality_score'] ?? 0,
                    'processing_time_ms' => $result['data']['processing_time_ms'] ?? 0,
                    'glasses_detected' => $result['data']['glasses_detected'] ?? false,
                    'match_category' => 'registration',
                    'security_checks_passed' => json_encode($result['data']['security_checks'] ?? []),
                    'success' => true,
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => (string)$this->request->getUserAgent(),
                    'session_id' => session_id()
                ]);
            }
            
            return $this->response->setJSON($result);
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 얼굴 인식 API
     * POST /api/face/recognize
     */
    public function recognize()
    {
        try {
            $postVar = $this->request->getPost();
            $fileData = $this->request->getFile('face_image');
            
            // JSON 입력 처리 추가
            $jsonInput = null;
            $contentType = $this->request->getHeaderLine('Content-Type');
            if (strpos($contentType, 'application/json') !== false) {
                $jsonRaw = $this->request->getBody();
                $jsonInput = json_decode($jsonRaw, true);
            }
            
            // 상세 디버깅 정보 수집
            $debugInfo = [
                'post_keys' => array_keys($postVar),
                'file_exists' => $fileData ? true : false,
                'file_valid' => $fileData ? $fileData->isValid() : false,
                'file_size' => $fileData ? $fileData->getSize() : 0,
                'file_error' => $fileData ? $fileData->getError() : 'no_file',
                'image_base64_exists' => isset($postVar['image_base64']),
                'image_base64_length' => isset($postVar['image_base64']) ? strlen($postVar['image_base64']) : 0,
                'content_type' => $contentType,
                'json_input_exists' => $jsonInput !== null,
                'json_image_exists' => $jsonInput ? isset($jsonInput['image_base64']) : false,
                'json_image_length' => ($jsonInput && isset($jsonInput['image_base64'])) ? strlen($jsonInput['image_base64']) : 0
            ];
            
            // 이미지 처리 - JSON과 FormData 모두 지원
            $imageBase64 = null;
            try {
                // JSON 입력 우선 처리 (image_base64 키로 수정)
                if ($jsonInput && isset($jsonInput['image_base64']) && !empty($jsonInput['image_base64'])) {
                    $debugInfo['json_processing'] = 'found_json_image_data';
                    $base64Data = $jsonInput['image_base64'];
                    
                    // data:image/jpeg;base64, 접두사 제거
                    if (strpos($base64Data, 'data:image') === 0) {
                        $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                        $debugInfo['json_prefix_removed'] = 'yes';
                    } else {
                        $debugInfo['json_prefix_removed'] = 'no_prefix_found';
                    }
                    
                    $imageBase64 = $base64Data;
                    $debugInfo['json_processing_result'] = 'success';
                    $debugInfo['json_final_length'] = strlen($imageBase64);
                }
                // FormData 처리 (기존 방식)
                else if (isset($postVar['image_base64']) && !empty($postVar['image_base64'])) {
                    $debugInfo['form_base64_processing'] = 'found_base64_data';
                    $base64Data = $postVar['image_base64'];
                    
                    // data:image/jpeg;base64, 접두사 제거
                    if (strpos($base64Data, 'data:image') === 0) {
                        $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                        $debugInfo['form_prefix_removed'] = 'yes';
                    } else {
                        $debugInfo['form_prefix_removed'] = 'no_prefix_found';
                    }
                    
                    $imageBase64 = $base64Data;
                    $debugInfo['form_processing_result'] = 'success';
                    $debugInfo['form_final_length'] = strlen($imageBase64);
                } else {
                    // 파일 업로드 처리
                    if ($fileData && $fileData->isValid()) {
                        $imageBase64 = base64_encode(file_get_contents($fileData->getTempName()));
                        $debugInfo['file_processing_result'] = 'success';
                    } else {
                        $debugInfo['no_image_source'] = 'no_json_no_form_no_file';
                    }
                }
                
                $debugInfo['final_imageBase64_status'] = $imageBase64 ? 'not_null' : 'is_null';
                $debugInfo['final_imageBase64_length'] = $imageBase64 ? strlen($imageBase64) : 0;
                
            } catch (Exception $e) {
                $debugInfo['processing_error'] = $e->getMessage();
            }
            
            // 더 정확한 검증
            if (!$imageBase64 || strlen(trim($imageBase64)) === 0) {
                $debugInfo['imageBase64_value_check'] = [
                    'is_null' => $imageBase64 === null,
                    'is_empty_string' => $imageBase64 === '',
                    'is_false' => $imageBase64 === false,
                    'strlen_raw' => $imageBase64 ? strlen($imageBase64) : 'no_value',
                    'strlen_trimmed' => $imageBase64 ? strlen(trim($imageBase64)) : 'no_value',
                    'first_50_chars' => $imageBase64 ? substr($imageBase64, 0, 50) : 'no_value',
                    'contains_only_whitespace' => $imageBase64 ? (trim($imageBase64) === '') : 'no_value'
                ];
                
                return $this->response->setJSON([
                    'success' => false,
                    'error' => '이미지가 제공되지 않았습니다.',
                    'debug' => $debugInfo,
                    'post_data_sample' => isset($postVar['image_base64']) ? substr($postVar['image_base64'], 0, 100) . '...' : 'no_base64',
                    'json_data_sample' => ($jsonInput && isset($jsonInput['image_base64'])) ? substr($jsonInput['image_base64'], 0, 100) . '...' : 'no_json_image'
                ]);
            }
            
            // 세션에서 회사/지점 정보 가져오기
            $session = session();
            $param1 = $session->get('comp_cd') ?? 'DEFAULT';
            $param2 = $session->get('bcoff_cd') ?? 'DEFAULT';
            
            // Python 서버로 요청
            $requestData = [
                'image' => $imageBase64,
                'param1' => $param1,  // 회사 코드
                'param2' => $param2   // 지점 코드
            ];
            
            log_message('info', '[FaceApi] recognize - param1: ' . $param1 . ', param2: ' . $param2);
            
            $result = $this->callFaceRecognitionAPI('/api/face/recognize', 'POST', $requestData);
            
            // 디버그 정보 추가
            $result['debug'] = $debugInfo;
            $result['image_length'] = strlen($imageBase64);
            
            // 로그 저장 - 간단한 기본 형태
            $logData = [
                'mem_sno' => ($result['success'] && isset($result['face_matching']['member']['mem_sno'])) 
                    ? $result['face_matching']['member']['mem_sno'] : null,
                'confidence_score' => $result['face_matching']['similarity_score'] ?? 0,
                'processing_time_ms' => isset($result['processing_time']) ? ($result['processing_time'] * 1000) : 0,
                'glasses_detected' => $result['glasses_detection']['has_glasses'] ?? false,
                'match_category' => ($result['face_matching']['match_found'] ?? false) ? 'match_found' : 'no_match',
                'security_checks_passed' => json_encode([
                    'basic_security' => $result['basic_security']['passed'] ?? false,
                    'liveness_check' => $result['liveness_check']['is_live'] ?? false
                ]),
                'success' => $result['success'] && ($result['face_matching']['match_found'] ?? false),
                'error_message' => $result['success'] ? null : ($result['error'] ?? 'Unknown error'),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => (string)$this->request->getUserAgent(),
                'session_id' => session_id(),
                'similarity_score' => $result['face_matching']['similarity_score'] ?? 0,
                'quality_score' => $result['confidence_score'] ?? 0
            ];
            
            // 디버깅: 로그 저장 시도
            file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - 로그 저장 시도: " . json_encode($logData) . "\n", FILE_APPEND);
            
            $this->saveFaceRecognitionLog($logData);
            
            // 디버깅: 로그 저장 완료
            file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - 로그 저장 완료\n", FILE_APPEND);
            
            return $this->response->setJSON($result);
            
        } catch (Exception $e) {
            // Python 서버 오류시에도 기본 로그 저장
            $this->saveFaceRecognitionLog([
                'mem_sno' => null,
                'confidence_score' => 0,
                'processing_time_ms' => 0,
                'glasses_detected' => false,
                'match_category' => 'server_error',
                'security_checks_passed' => json_encode(['error' => 'Python server not available']),
                'success' => false,
                'error_message' => $e->getMessage(),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => (string)$this->request->getUserAgent(),
                'session_id' => session_id(),
                'similarity_score' => 0,
                'quality_score' => 0
            ]);
            
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }
    
    /**
     * 얼굴 데이터 삭제 API
     * DELETE /api/face/delete/{mem_sno}
     */
    public function delete($mem_sno = null)
    {
        try {
            if (!$mem_sno) {
                $postVar = $this->request->getPost();
                $mem_sno = $postVar['mem_sno'] ?? null;
            }
            
            if (!$mem_sno) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => '회원번호(mem_sno)가 필요합니다.'
                ]);
            }
            
            // 데이터베이스에서 비활성화
            $this->faceModel->deactivateFace($mem_sno);
            
            // Python 서버에서도 삭제
            $result = $this->callFaceRecognitionAPI('/api/face/delete/' . $mem_sno, 'DELETE');
            
            return $this->response->setJSON($result);
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 얼굴 데이터 목록 조회 API
     * GET /api/face/list
     */
    public function list()
    {
        try {
            $getVar = $this->request->getGet();
            $filters = [
                'limit' => $getVar['limit'] ?? 100,
                'glasses_detected' => $getVar['glasses'] ?? null,
                'quality_min' => $getVar['quality_min'] ?? null
            ];
            
            $faces = $this->faceModel->getFacesWithMemberInfo($filters);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $faces
            ]);
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 얼굴 인식 통계 API
     * GET /api/face/stats
     */
    public function stats()
    {
        try {
            $getVar = $this->request->getGet();
            $filters = [
                'start_date' => $getVar['start_date'] ?? date('Y-m-d', strtotime('-30 days')),
                'end_date' => $getVar['end_date'] ?? date('Y-m-d')
            ];
            
            $stats = $this->faceModel->getFaceRecognitionStats($filters);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 이미지 데이터 처리
     * @param mixed $fileData
     * @param array $postVar
     * @return string|null
     */
    private function processImageData($fileData, $postVar)
    {
        // 강제 로깅 (파일로 직접 기록)
        file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - processImageData 시작\n", FILE_APPEND);
        file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - POST keys: " . implode(', ', array_keys($postVar)) . "\n", FILE_APPEND);
        
        // Base64 데이터 우선 처리 (가장 확실한 방법)
        if (isset($postVar['image_base64']) && !empty($postVar['image_base64'])) {
            file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - Base64 데이터 감지: " . strlen($postVar['image_base64']) . " 문자\n", FILE_APPEND);
            $base64Data = $postVar['image_base64'];
            
            // data:image/jpeg;base64, 접두사 제거
            if (strpos($base64Data, 'data:image') === 0) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - 접두사 제거 후: " . strlen($base64Data) . " 문자\n", FILE_APPEND);
            }
            
            file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - Base64 데이터 반환 성공\n", FILE_APPEND);
            return $base64Data;
        }
        
        // 파일 업로드 처리
        if ($fileData && $fileData->isValid()) {
            file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - 파일 업로드 감지\n", FILE_APPEND);
            return base64_encode(file_get_contents($fileData->getTempName()));
        }
        
        file_put_contents('debug_face_api.log', date('Y-m-d H:i:s') . " - 이미지 데이터 없음 - NULL 반환\n", FILE_APPEND);
        return null;
    }
    
    /**
     * Python 얼굴 인식 서버 API 호출
     * @param string $endpoint
     * @param string $method
     * @param array $data
     * @return array
     */
    private function callFaceRecognitionAPI($endpoint, $method = 'GET', $data = null)
    {
        $faceHost = getenv('FACE_HOST') ?: 'localhost';
        $facePort = getenv('FACE_PORT') ?: '5002';
        $baseUrl = "http://{$faceHost}:{$facePort}";
        $url = $baseUrl . $endpoint;
        
        // 디버깅: 요청 데이터 로깅
        $debugInfo = [
            'url' => $url,
            'method' => $method,
            'data_keys' => $data ? array_keys($data) : [],
            'image_length' => ($data && isset($data['image'])) ? strlen($data['image']) : 0,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - 요청: " . json_encode($debugInfo) . "\n", FILE_APPEND);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                $jsonData = json_encode($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonData)
                ]);
                
                // 디버깅: 실제 전송 데이터 샘플
                file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - JSON 크기: " . strlen($jsonData) . "\n", FILE_APPEND);
                file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - JSON 샘플: " . substr($jsonData, 0, 200) . "...\n", FILE_APPEND);
            }
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // 디버깅: 응답 로깅
        file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - 응답 코드: " . $httpCode . "\n", FILE_APPEND);
        file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - 응답 길이: " . strlen($response) . "\n", FILE_APPEND);
        file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - 응답 샘플: " . substr($response, 0, 500) . "...\n", FILE_APPEND);
        
        if ($curlError) {
            file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - CURL 오류: " . $curlError . "\n", FILE_APPEND);
            throw new Exception('CURL Error: ' . $curlError);
        }
        
        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - HTTP 오류: " . $httpCode . " - " . $response . "\n", FILE_APPEND);
            throw new Exception('HTTP Error ' . $httpCode . ': ' . ($errorData['error'] ?? 'Unknown error'));
        }
        
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - JSON 파싱 오류: " . json_last_error_msg() . "\n", FILE_APPEND);
            throw new Exception('JSON Decode Error: ' . json_last_error_msg());
        }
        
        // 디버깅: 최종 결과
        file_put_contents('debug_python_api.log', date('Y-m-d H:i:s') . " - 결과: " . json_encode(['success' => $result['success'] ?? false, 'error' => $result['error'] ?? null]) . "\n", FILE_APPEND);
        
        return $result;
    }
    
    /**
     * 얼굴 인식 로그 저장
     * @param array $logData
     */
    private function saveFaceRecognitionLog($logData)
    {
        try {
            $this->faceModel->saveFaceRecognitionLog($logData);
        } catch (Exception $e) {
            // 로그 저장 실패는 무시 (메인 기능에 영향 주지 않음)
            log_message('error', 'Face recognition log save failed: ' . $e->getMessage());
        }
    }
} 