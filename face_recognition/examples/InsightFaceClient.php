<?php
/**
 * InsightFace API 클라이언트
 * 
 * SPOQ Plus에서 InsightFace 서비스와 통신하기 위한 PHP 클라이언트
 */

class InsightFaceClient {
    private $baseUrl;
    private $timeout;
    private $apiKey;
    
    /**
     * 생성자
     * 
     * @param string $baseUrl API 서버 URL
     * @param int $timeout 요청 타임아웃 (초)
     * @param string|null $apiKey API 인증키 (선택사항)
     */
    public function __construct($baseUrl = null, $timeout = 30, $apiKey = null) {
        if ($baseUrl === null) {
            $faceHost = getenv('FACE_HOST') ?: 'localhost';
            $facePort = getenv('FACE_PORT') ?: '5002';
            $baseUrl = "http://{$faceHost}:{$facePort}";
        }
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
        $this->apiKey = $apiKey;
    }
    
    /**
     * 헬스 체크
     * 
     * @return array
     */
    public function healthCheck() {
        return $this->makeRequest('GET', '/api/face/health');
    }
    
    /**
     * 얼굴 등록
     * 
     * @param string $memberId 회원 ID
     * @param string $imageData Base64 인코딩된 이미지 데이터
     * @param int $securityLevel 보안 레벨 (1-3)
     * @param string|null $notes 메모
     * @param string|null $param1 필터링 파라미터 1 (예: 회사 코드)
     * @param string|null $param2 필터링 파라미터 2 (예: 지점 코드)
     * @return array
     */
    public function registerFace($memberId, $imageData, $securityLevel = 3, $notes = null, $param1 = null, $param2 = null) {
        $data = [
            'member_id' => $memberId,
            'image' => $imageData,
            'security_level' => $securityLevel
        ];
        
        if ($notes !== null) {
            $data['notes'] = $notes;
        }
        
        if ($param1 !== null) {
            $data['param1'] = $param1;
        }
        
        if ($param2 !== null) {
            $data['param2'] = $param2;
        }
        
        return $this->makeRequest('POST', '/api/face/register', $data);
    }
    
    /**
     * 얼굴 인식
     * 
     * @param string $imageData Base64 인코딩된 이미지 데이터
     * @param string|null $param1 필터링 파라미터 1 (예: 회사 코드)
     * @param string|null $param2 필터링 파라미터 2 (예: 지점 코드)
     * @param string|null $compCd [레거시] 회사 코드 (param1으로 자동 변환)
     * @param string|null $bcoffCd [레거시] 지점 코드 (param2로 자동 변환)
     * @return array
     */
    public function recognizeFace($imageData, $param1 = null, $param2 = null, $compCd = null, $bcoffCd = null) {
        $data = ['image' => $imageData];
        
        // param1, param2가 없으면 레거시 파라미터 사용
        if ($param1 !== null) {
            $data['param1'] = $param1;
        } elseif ($compCd !== null) {
            $data['comp_cd'] = $compCd;
        }
        
        if ($param2 !== null) {
            $data['param2'] = $param2;
        } elseif ($bcoffCd !== null) {
            $data['bcoff_cd'] = $bcoffCd;
        }
        
        return $this->makeRequest('POST', '/api/face/recognize', $data);
    }
    
    /**
     * 체크인용 얼굴 인식 (엄격한 보안 검사)
     * 
     * @param string $imageData Base64 인코딩된 이미지 데이터
     * @param string $compCd 회사 코드
     * @param string $bcoffCd 지점 코드
     * @param int $securityLevel 보안 레벨
     * @return array
     */
    public function recognizeFaceForCheckin($imageData, $compCd, $bcoffCd, $securityLevel = 3) {
        $data = [
            'image' => $imageData,
            'comp_cd' => $compCd,
            'bcoff_cd' => $bcoffCd,
            'security_level' => $securityLevel
        ];
        
        return $this->makeRequest('POST', '/api/face/recognize_for_checkin', $data);
    }
    
    /**
     * 등록용 얼굴 검출 (품질 체크)
     * 
     * @param string $imageData Base64 인코딩된 이미지 데이터
     * @return array
     */
    public function detectForRegistration($imageData) {
        $data = ['image' => $imageData];
        
        return $this->makeRequest('POST', '/api/face/detect_for_registration', $data);
    }
    
    /**
     * 눈 깜빡임 감지 (라이브니스 검증)
     * 
     * @param array $frames Base64 인코딩된 이미지 프레임 배열
     * @return array
     */
    public function detectBlink($frames) {
        $data = ['frames' => $frames];
        
        return $this->makeRequest('POST', '/api/face/blink_detection', $data);
    }
    
    /**
     * HTTP 요청 처리
     * 
     * @param string $method HTTP 메소드
     * @param string $endpoint API 엔드포인트
     * @param array|null $data 요청 데이터
     * @return array
     */
    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        // 헤더 설정
        $headers = [
            'Accept: application/json'
        ];
        
        // API 키가 있으면 추가
        if ($this->apiKey !== null) {
            $headers[] = 'X-API-Key: ' . $this->apiKey;
        }
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            $headers[] = 'Content-Type: application/json';
            
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            
            if ($data !== null) {
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // 요청 실행
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // CURL 에러 처리
        if ($error) {
            return [
                'success' => false,
                'error' => 'Connection Error',
                'message' => $error,
                'http_code' => 0
            ];
        }
        
        // 응답 파싱
        $decoded = json_decode($response, true);
        
        // JSON 파싱 에러
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid Response',
                'message' => 'JSON decode error: ' . json_last_error_msg(),
                'http_code' => $httpCode,
                'raw_response' => $response
            ];
        }
        
        // HTTP 에러 코드 처리
        if ($httpCode >= 400) {
            if (!isset($decoded['success'])) {
                $decoded['success'] = false;
            }
            $decoded['http_code'] = $httpCode;
        }
        
        return $decoded;
    }
    
    /**
     * 이미지 파일을 Base64로 인코딩
     * 
     * @param string $filePath 이미지 파일 경로
     * @return string|false
     */
    public static function encodeImageFile($filePath) {
        if (!file_exists($filePath)) {
            return false;
        }
        
        $imageData = file_get_contents($filePath);
        if ($imageData === false) {
            return false;
        }
        
        $mimeType = mime_content_type($filePath);
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
            return false;
        }
        
        return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
    }
    
    /**
     * 이미지 URL을 Base64로 인코딩
     * 
     * @param string $imageUrl 이미지 URL
     * @return string|false
     */
    public static function encodeImageUrl($imageUrl) {
        $imageData = @file_get_contents($imageUrl);
        if ($imageData === false) {
            return false;
        }
        
        // 이미지 타입 감지
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $imageData);
        finfo_close($finfo);
        
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
            return false;
        }
        
        return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
    }
    
    /**
     * 업로드된 파일을 Base64로 인코딩
     * 
     * @param array $uploadedFile $_FILES 배열의 요소
     * @return string|array 성공시 Base64 문자열, 실패시 에러 배열
     */
    public static function encodeUploadedFile($uploadedFile) {
        // 업로드 에러 체크
        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'error' => 'Upload failed',
                'code' => $uploadedFile['error']
            ];
        }
        
        // 파일 크기 체크 (5MB)
        if ($uploadedFile['size'] > 5 * 1024 * 1024) {
            return [
                'success' => false,
                'error' => 'File too large',
                'message' => 'Maximum file size is 5MB'
            ];
        }
        
        // MIME 타입 체크
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($uploadedFile['type'], $allowedTypes)) {
            return [
                'success' => false,
                'error' => 'Invalid file type',
                'message' => 'Only JPEG and PNG images are allowed'
            ];
        }
        
        // 파일 읽기 및 인코딩
        $imageData = file_get_contents($uploadedFile['tmp_name']);
        if ($imageData === false) {
            return [
                'success' => false,
                'error' => 'Failed to read file'
            ];
        }
        
        return 'data:' . $uploadedFile['type'] . ';base64,' . base64_encode($imageData);
    }
}
?>