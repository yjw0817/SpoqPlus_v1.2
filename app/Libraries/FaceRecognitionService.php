<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;

class FaceRecognitionService
{
    private $baseUrl;
    private $timeout;
    private $client;
    
    public function __construct()
    {
        $faceHost = getenv('FACE_HOST') ?: 'localhost';
        $facePort = getenv('FACE_PORT') ?: '5002';
        $this->baseUrl = "http://{$faceHost}:{$facePort}";
        $this->timeout = 30;
        $this->client = Services::curlrequest();
    }
    
    /**
     * 얼굴 인식 서버 상태 확인
     * @return array
     */
    public function checkHealth()
    {
        try {
            $response = $this->client->get($this->baseUrl . '/api/face/health', [
                'timeout' => 5
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            
            if ($statusCode === 200) {
                return [
                    'success' => true,
                    'status' => 'healthy',
                    'data' => json_decode($body, true)
                ];
            } else {
                return [
                    'success' => false,
                    'status' => 'unhealthy',
                    'error' => 'HTTP ' . $statusCode
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * 얼굴 등록
     * @param string $mem_sno 회원번호
     * @param string $imageBase64 Base64 인코딩된 이미지 데이터
     * @param array $options 추가 옵션
     * @return array
     */
    public function registerFace($mem_sno, $imageBase64, $options = [])
    {
        try {
            $data = [
                'member_id' => $mem_sno,
                'image' => $imageBase64
            ];
            
            // 추가 옵션이 있다면 포함
            if (!empty($options)) {
                $data = array_merge($data, $options);
            }
            
            $response = $this->client->post($this->baseUrl . '/api/face/register', [
                'timeout' => $this->timeout,
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $responseData = json_decode($body, true);
            
            if ($statusCode === 200) {
                return [
                    'success' => true,
                    'data' => $responseData,
                    'processing_time_ms' => $responseData['processing_time_ms'] ?? 0
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['error'] ?? 'Unknown error',
                    'status_code' => $statusCode
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage(),
                'exception' => true
            ];
        }
    }
    
    /**
     * 얼굴 인식
     * @param string $imageBase64 Base64 인코딩된 이미지 데이터
     * @param array $options 추가 옵션
     * @return array
     */
    public function recognizeFace($imageBase64, $options = [])
    {
        try {
            $data = [
                'image' => $imageBase64
            ];
            
            // 추가 옵션이 있다면 포함
            if (!empty($options)) {
                $data = array_merge($data, $options);
            }
            
            $response = $this->client->post($this->baseUrl . '/api/face/recognize', [
                'timeout' => $this->timeout,
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $responseData = json_decode($body, true);
            
            if ($statusCode === 200) {
                return [
                    'success' => true,
                    'data' => $responseData,
                    'processing_time_ms' => $responseData['processing_time_ms'] ?? 0
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['error'] ?? 'Unknown error',
                    'status_code' => $statusCode
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage(),
                'exception' => true
            ];
        }
    }
    
    /**
     * 얼굴 데이터 삭제
     * @param string $mem_sno 회원번호
     * @return array
     */
    public function deleteFace($mem_sno)
    {
        try {
            $response = $this->client->delete($this->baseUrl . '/api/face/delete/' . $mem_sno, [
                'timeout' => $this->timeout
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $responseData = json_decode($body, true);
            
            if ($statusCode === 200) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['error'] ?? 'Unknown error',
                    'status_code' => $statusCode
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage(),
                'exception' => true
            ];
        }
    }
    
    /**
     * 얼굴 데이터 목록 조회
     * @return array
     */
    public function listFaces()
    {
        try {
            $response = $this->client->get($this->baseUrl . '/api/face/list', [
                'timeout' => $this->timeout
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $responseData = json_decode($body, true);
            
            if ($statusCode === 200) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['error'] ?? 'Unknown error',
                    'status_code' => $statusCode
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage(),
                'exception' => true
            ];
        }
    }
    
    /**
     * 이미지 파일을 Base64로 변환
     * @param string $imagePath 이미지 파일 경로
     * @return string|false
     */
    public function imageToBase64($imagePath)
    {
        if (!file_exists($imagePath)) {
            return false;
        }
        
        $imageData = file_get_contents($imagePath);
        if ($imageData === false) {
            return false;
        }
        
        return base64_encode($imageData);
    }
    
    /**
     * Base64 이미지를 파일로 저장
     * @param string $base64Data Base64 인코딩된 이미지 데이터
     * @param string $filePath 저장할 파일 경로
     * @return bool
     */
    public function base64ToImage($base64Data, $filePath)
    {
        try {
            // data:image/jpeg;base64, 접두사 제거
            if (strpos($base64Data, 'data:image') === 0) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            }
            
            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                return false;
            }
            
            // 디렉터리가 없다면 생성
            $directory = dirname($filePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            return file_put_contents($filePath, $imageData) !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * 웹캠 이미지 데이터를 처리하여 Base64로 변환
     * @param mixed $fileData 업로드된 파일 데이터
     * @return string|false
     */
    public function processWebcamImage($fileData)
    {
        try {
            if (is_string($fileData)) {
                // 이미 Base64 문자열인 경우
                return $fileData;
            }
            
            if (is_object($fileData) && method_exists($fileData, 'getTempName')) {
                // CodeIgniter 파일 객체인 경우
                $tempPath = $fileData->getTempName();
                return $this->imageToBase64($tempPath);
            }
            
            if (is_array($fileData) && isset($fileData['tmp_name'])) {
                // 일반 PHP 업로드 배열인 경우
                return $this->imageToBase64($fileData['tmp_name']);
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * 얼굴 인식 서버 URL 설정
     * @param string $url
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = rtrim($url, '/');
    }
    
    /**
     * 타임아웃 설정
     * @param int $seconds
     */
    public function setTimeout($seconds)
    {
        $this->timeout = $seconds;
    }
    
    /**
     * 서버 연결 테스트
     * @return bool
     */
    public function testConnection()
    {
        $health = $this->checkHealth();
        return $health['success'] === true;
    }
} 