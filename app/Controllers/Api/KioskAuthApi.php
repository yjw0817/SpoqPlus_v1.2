<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AttdModel;
use App\Models\MemModel;
use App\Models\FaceRecognitionModel;
use App\Models\ApiKeyModel;

/**
 * 키오스크 얼굴 인증 API
 * 
 * @package App\Controllers\Api
 * @version 1.0.0
 * @since 2024-01-09
 */
class KioskAuthApi extends ResourceController
{
    use ResponseTrait;
    
    protected $modelName = 'App\Models\FaceRecognitionModel';
    protected $format = 'json';
    
    private $attdModel;
    private $memModel;
    private $faceModel;
    private $apiKeyModel;
    private $currentApiKeyInfo;
    
    // API 응답 코드 정의
    const CODE_SUCCESS = 200;
    const CODE_PARTIAL_SUCCESS = 206;
    const CODE_BAD_REQUEST = 400;
    const CODE_UNAUTHORIZED = 401;
    const CODE_NOT_FOUND = 404;
    const CODE_SERVER_ERROR = 500;
    const CODE_SERVICE_UNAVAILABLE = 503;
    
    // 에러 코드 정의
    const ERROR_MISSING_PARAMS = 'E001';
    const ERROR_INVALID_IMAGE = 'E002';
    const ERROR_NO_FACE_DETECTED = 'E003';
    const ERROR_SECURITY_FAILED = 'E004';
    const ERROR_MEMBER_NOT_FOUND = 'E005';
    const ERROR_NO_ACTIVE_TICKET = 'E006';
    const ERROR_SERVER_ERROR = 'E999';
    
    public function __construct()
    {
        // 부모 생성자 호출 없이 직접 초기화
        helper(['url', 'form']);
        
        $this->attdModel = new AttdModel();
        $this->memModel = new MemModel();
        $this->faceModel = new FaceRecognitionModel();
        $this->apiKeyModel = new ApiKeyModel();
        
        // CORS 설정 (로컬 테스트용)
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
        
        // OPTIONS 요청 처리 (preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }
    
    /**
     * 초기화 메서드 오버라이드 - 로그인 체크 무시
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // 부모 초기화 호출
        parent::initController($request, $response, $logger);
        
        // API는 로그인 체크 하지 않음
        // 세션 시작하지 않음
    }
    
    /**
     * 얼굴 인증 API
     * 
     * POST /api/v1/kiosk/face-auth
     * 
     * Request Body:
     * {
     *     "image": "base64_encoded_image",
     *     "device_id": "KIOSK_001",
     *     "location": "1F_ENTRANCE",
     *     "check_liveness": true,
     *     "language": "ko"  // ko, en, ja, zh
     * }
     */
    public function faceAuth()
    {
        try {
            // API 키 검증
            $apiKeyValidation = $this->validateApiKey();
            if (!$apiKeyValidation['success']) {
                return $this->fail($apiKeyValidation['message'], self::CODE_UNAUTHORIZED);
            }
            
            // 요청 검증
            $validation = $this->validateRequest();
            if (!$validation['success']) {
                return $this->fail($validation['message'], self::CODE_BAD_REQUEST, self::ERROR_MISSING_PARAMS);
            }
            
            $data = $this->request->getJSON(true);
            $language = $data['language'] ?? 'ko';
            
            // Python 얼굴 인식 서버 호출
            $faceResult = $this->callFaceRecognitionServer($data);
            
            // 얼굴 검출 실패
            if (!$faceResult['face_detected']) {
                return $this->respond([
                    'success' => false,
                    'error_code' => self::ERROR_NO_FACE_DETECTED,
                    'message' => $this->getMessage('no_face_detected', $language),
                    'data' => null,
                    'timestamp' => date('Y-m-d H:i:s')
                ], self::CODE_NOT_FOUND);
            }
            
            // 보안 검증 실패
            if ($faceResult['security_failed']) {
                $securityMessage = $this->getSecurityFailureMessage($faceResult['security_details'], $language);
                return $this->respond([
                    'success' => false,
                    'error_code' => self::ERROR_SECURITY_FAILED,
                    'message' => $securityMessage,
                    'data' => [
                        'security_details' => $faceResult['security_details']
                    ],
                    'timestamp' => date('Y-m-d H:i:s')
                ], self::CODE_UNAUTHORIZED);
            }
            
            // 회원 매칭 실패
            if (!$faceResult['face_matching']['match_found']) {
                return $this->respond([
                    'success' => false,
                    'error_code' => self::ERROR_MEMBER_NOT_FOUND,
                    'message' => $this->getMessage('member_not_found', $language),
                    'data' => null,
                    'timestamp' => date('Y-m-d H:i:s')
                ], self::CODE_NOT_FOUND);
            }
            
            // 회원 정보 조회
            $memberSno = $faceResult['face_matching']['member']['mem_sno'];
            $memberInfo = $this->getMemberInfo($memberSno);
            
            if (!$memberInfo) {
                return $this->respond([
                    'success' => false,
                    'error_code' => self::ERROR_MEMBER_NOT_FOUND,
                    'message' => $this->getMessage('member_not_found', $language),
                    'data' => null,
                    'timestamp' => date('Y-m-d H:i:s')
                ], self::CODE_NOT_FOUND);
            }
            
            // 이용권 목록 조회
            $tickets = $this->getMemberTickets($memberSno);
            
            // 활성 이용권이 없는 경우
            if (empty($tickets)) {
                return $this->respond([
                    'success' => true,
                    'error_code' => self::ERROR_NO_ACTIVE_TICKET,
                    'message' => $this->getMessage('no_active_ticket', $language),
                    'data' => [
                        'member' => [
                            'mem_sno' => $memberInfo['MEM_SNO'],
                            'mem_nm' => $memberInfo['MEM_NM'],
                            'mem_telno_mask' => $this->maskPhoneNumber($memberInfo['MEM_TELNO'])
                        ],
                        'tickets' => [],
                        'ticket_count' => 0
                    ],
                    'timestamp' => date('Y-m-d H:i:s')
                ], self::CODE_PARTIAL_SUCCESS);
            }
            
            // 성공 응답
            return $this->respond([
                'success' => true,
                'error_code' => null,
                'message' => $this->getMessage('auth_success', $language, ['name' => $memberInfo['MEM_NM']]),
                'data' => [
                    'member' => [
                        'mem_sno' => $memberInfo['MEM_SNO'],
                        'mem_nm' => $memberInfo['MEM_NM'],
                        'mem_telno_mask' => $this->maskPhoneNumber($memberInfo['MEM_TELNO'])
                    ],
                    'tickets' => $this->formatTickets($tickets, $language),
                    'ticket_count' => count($tickets),
                    'similarity_score' => $faceResult['face_matching']['similarity_score']
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ], self::CODE_SUCCESS);
            
        } catch (\Exception $e) {
            log_message('error', '[KioskAuthApi] Error: ' . $e->getMessage());
            
            return $this->respond([
                'success' => false,
                'error_code' => self::ERROR_SERVER_ERROR,
                'message' => $this->getMessage('server_error', $language ?? 'ko'),
                'data' => null,
                'timestamp' => date('Y-m-d H:i:s')
            ], self::CODE_SERVER_ERROR);
        }
    }
    
    /**
     * 체크인 처리 API
     * 
     * POST /api/v1/kiosk/checkin
     * 
     * Request Body:
     * {
     *     "mem_sno": "12345",
     *     "ticket_id": "T123456",
     *     "device_id": "KIOSK_001",
     *     "location": "1F_ENTRANCE",
     *     "language": "ko"
     * }
     */
    public function checkin()
    {
        try {
            // API 키 검증
            $apiKeyValidation = $this->validateApiKey();
            if (!$apiKeyValidation['success']) {
                return $this->fail($apiKeyValidation['message'], self::CODE_UNAUTHORIZED);
            }
            
            $data = $this->request->getJSON(true);
            $language = $data['language'] ?? 'ko';
            
            // 필수 파라미터 검증
            if (!isset($data['mem_sno']) || !isset($data['ticket_id'])) {
                return $this->fail(
                    $this->getMessage('missing_params', $language),
                    self::CODE_BAD_REQUEST,
                    self::ERROR_MISSING_PARAMS
                );
            }
            
            // 체크인 처리
            $result = $this->processCheckin(
                $data['mem_sno'],
                $data['ticket_id'],
                $data['device_id'] ?? 'KIOSK',
                $data['location'] ?? ''
            );
            
            if ($result['success']) {
                return $this->respond([
                    'success' => true,
                    'error_code' => null,
                    'message' => $this->getMessage('checkin_success', $language, [
                        'ticket_name' => $result['ticket_name']
                    ]),
                    'data' => [
                        'checkin_id' => $result['checkin_id'],
                        'ticket_name' => $result['ticket_name'],
                        'remaining_count' => $result['remaining_count'] ?? null,
                        'expire_date' => $result['expire_date'] ?? null
                    ],
                    'timestamp' => date('Y-m-d H:i:s')
                ], self::CODE_SUCCESS);
            } else {
                return $this->respond([
                    'success' => false,
                    'error_code' => 'E007',
                    'message' => $result['message'] ?? $this->getMessage('checkin_failed', $language),
                    'data' => null,
                    'timestamp' => date('Y-m-d H:i:s')
                ], self::CODE_BAD_REQUEST);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[KioskAuthApi] Checkin Error: ' . $e->getMessage());
            
            return $this->respond([
                'success' => false,
                'error_code' => self::ERROR_SERVER_ERROR,
                'message' => $this->getMessage('server_error', $language ?? 'ko'),
                'data' => null,
                'timestamp' => date('Y-m-d H:i:s')
            ], self::CODE_SERVER_ERROR);
        }
    }
    
    /**
     * API 상태 확인
     * 
     * GET /api/v1/kiosk/status
     */
    public function status()
    {
        try {
            // API 키 검증 (상태 확인은 선택적)
            $apiKeyValidation = $this->validateApiKey();
            if (!$apiKeyValidation['success']) {
                return $this->fail($apiKeyValidation['message'], self::CODE_UNAUTHORIZED);
            }
            
            // Python 얼굴 인식 서버 상태 확인
            $faceServerStatus = $this->checkFaceRecognitionServer();
            
            // 데이터베이스 연결 확인
            $dbStatus = $this->checkDatabaseConnection();
            
            return $this->respond([
                'success' => true,
                'service' => 'Kiosk Face Authentication API',
                'version' => '1.0.0',
                'status' => 'operational',
                'components' => [
                    'api_server' => 'healthy',
                    'face_recognition_server' => $faceServerStatus ? 'healthy' : 'unhealthy',
                    'database' => $dbStatus ? 'healthy' : 'unhealthy'
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'error' => 'Failed to check status',
                'message' => $e->getMessage()
            ], self::CODE_SERVER_ERROR);
        }
    }
    
    /**
     * API 키 검증
     */
    private function validateApiKey(): array
    {
        $apiKey = $this->request->getHeaderLine('X-API-Key');
        
        // 디버깅 로그
        log_message('debug', '[API Key Validation] Received API Key: ' . $apiKey);
        
        if (empty($apiKey)) {
            log_message('debug', '[API Key Validation] No API key provided');
            return [
                'success' => false,
                'message' => 'API key is required'
            ];
        }
        
        // API 키 검증
        $keyInfo = $this->apiKeyModel->validateApiKey($apiKey);
        
        // 디버깅 로그
        log_message('debug', '[API Key Validation] Key validation result: ' . ($keyInfo ? 'VALID' : 'INVALID'));
        
        if (!$keyInfo) {
            log_message('debug', '[API Key Validation] Invalid API key: ' . $apiKey);
            return [
                'success' => false,
                'message' => 'Invalid or inactive API key'
            ];
        }
        
        // API 키 정보 저장 (이후 사용을 위해)
        $this->currentApiKeyInfo = $keyInfo;
        
        // 사용 로그 기록 (비동기 처리 권장)
        $this->apiKeyModel->logApiKeyUsage($keyInfo['id'], [
            'endpoint' => $this->request->getUri()->getPath(),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);
        
        return ['success' => true];
    }
    
    /**
     * 요청 검증
     */
    private function validateRequest(): array
    {
        $data = $this->request->getJSON(true);
        
        if (!isset($data['image']) || empty($data['image'])) {
            return [
                'success' => false,
                'message' => 'Image data is required'
            ];
        }
        
        if (!isset($data['device_id']) || empty($data['device_id'])) {
            return [
                'success' => false,
                'message' => 'Device ID is required'
            ];
        }
        
        return ['success' => true];
    }
    
    /**
     * 얼굴 인식 서버 상태 확인
     */
    private function checkFaceRecognitionServer(): bool
    {
        try {
            $faceHost = getenv('FACE_HOST') ?: 'localhost';
            $facePort = getenv('FACE_PORT') ?: '5002';
            $ch = curl_init("http://{$faceHost}:{$facePort}/api/face/health");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            return $httpCode === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * 데이터베이스 연결 확인
     */
    private function checkDatabaseConnection(): bool
    {
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT 1');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Python 얼굴 인식 서버 호출
     */
    private function callFaceRecognitionServer(array $data): array
    {
        $faceHost = getenv('FACE_HOST') ?: 'localhost';
        $facePort = getenv('FACE_PORT') ?: '5002';
        $url = "http://{$faceHost}:{$facePort}/api/face/recognize_for_checkin";
        
        // API 키의 회사/지점 정보 포함
        $postData = [
            'image' => $data['image'],
            'check_liveness' => $data['check_liveness'] ?? true,
            'check_blink' => false,
            'comp_cd' => $this->currentApiKeyInfo['comp_cd'] ?? null,
            'bcoff_cd' => $this->currentApiKeyInfo['bcoff_cd'] ?? null
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false) {
            throw new \Exception('Face recognition server is not available');
        }
        
        $result = json_decode($response, true);
        
        // 서버 오류 처리
        if ($httpCode !== 200 || !$result) {
            throw new \Exception('Face recognition server error');
        }
        
        return $result;
    }
    
    /**
     * 이용권 정보 포맷팅
     */
    private function formatTickets(array $tickets, string $language): array
    {
        $formatted = [];
        
        foreach ($tickets as $ticket) {
            $formatted[] = [
                'ticket_id' => $ticket['ticket_id'],
                'ticket_name' => $ticket['ticket_name'],
                'ticket_type' => $ticket['ticket_type'],
                'status' => $ticket['status'],
                'is_available' => $ticket['status'] === 'active',
                'remaining_info' => $this->formatRemainingInfo($ticket, $language),
                'expire_date' => $ticket['expire_date'],
                'restrictions' => $ticket['restrictions'] ?? null,
                'gx_info' => $ticket['gx_info'] ?? null
            ];
        }
        
        return $formatted;
    }
    
    /**
     * 남은 횟수/기간 정보 포맷팅
     */
    private function formatRemainingInfo(array $ticket, string $language): string
    {
        if ($ticket['ticket_type'] === 'count') {
            return $this->getMessage('remaining_count', $language, [
                'count' => $ticket['remaining_count']
            ]);
        } elseif ($ticket['ticket_type'] === 'period') {
            return $this->getMessage('remaining_days', $language, [
                'days' => $ticket['remaining_days']
            ]);
        }
        
        return $ticket['remaining_info'] ?? '';
    }
    
    /**
     * 전화번호 마스킹
     */
    private function maskPhoneNumber(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }
        
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 11) {
            return substr($phone, 0, 3) . '-****-' . substr($phone, -4);
        } elseif (strlen($phone) === 10) {
            return substr($phone, 0, 3) . '-***-' . substr($phone, -4);
        }
        
        return $phone;
    }
    
    /**
     * 보안 실패 메시지 생성
     */
    private function getSecurityFailureMessage(array $details, string $language): string
    {
        if (!$details['liveness_passed']) {
            return $this->getMessage('liveness_failed', $language);
        }
        
        if (!$details['quality_passed']) {
            return $this->getMessage('quality_failed', $language);
        }
        
        if (!$details['blink_passed']) {
            return $this->getMessage('blink_failed', $language);
        }
        
        return $this->getMessage('security_failed', $language);
    }
    
    /**
     * 다국어 메시지 반환
     */
    private function getMessage(string $key, string $language, array $params = []): string
    {
        $messages = [
            'ko' => [
                'no_face_detected' => '얼굴이 감지되지 않았습니다. 카메라를 정면으로 바라봐 주세요.',
                'liveness_failed' => '조명이 밝은 곳에서 다시 시도해 주세요.',
                'quality_failed' => '카메라를 정면으로 바라봐 주세요.',
                'blink_failed' => '눈을 깜빡여 주세요.',
                'security_failed' => '보안 검증에 실패했습니다.',
                'member_not_found' => '등록된 회원이 아닙니다. 회원번호를 입력해 주세요.',
                'no_active_ticket' => '사용 가능한 이용권이 없습니다.',
                'auth_success' => '{name}님 안녕하세요!',
                'server_error' => '시스템 오류가 발생했습니다. 잠시 후 다시 시도해 주세요.',
                'missing_params' => '필수 정보가 누락되었습니다.',
                'checkin_success' => '{ticket_name} 이용권으로 입장이 완료되었습니다.',
                'checkin_failed' => '입장 처리에 실패했습니다.',
                'remaining_count' => '잔여 {count}회',
                'remaining_days' => '잔여 {days}일'
            ],
            'en' => [
                'no_face_detected' => 'No face detected. Please look at the camera.',
                'liveness_failed' => 'Please try again in a well-lit area.',
                'quality_failed' => 'Please face the camera directly.',
                'blink_failed' => 'Please blink your eyes.',
                'security_failed' => 'Security verification failed.',
                'member_not_found' => 'Member not found. Please enter your member number.',
                'no_active_ticket' => 'No active tickets available.',
                'auth_success' => 'Welcome, {name}!',
                'server_error' => 'System error occurred. Please try again later.',
                'missing_params' => 'Required information is missing.',
                'checkin_success' => 'Check-in completed with {ticket_name}.',
                'checkin_failed' => 'Check-in failed.',
                'remaining_count' => '{count} times remaining',
                'remaining_days' => '{days} days remaining'
            ],
            'ja' => [
                'no_face_detected' => '顔が検出されませんでした。カメラを見てください。',
                'liveness_failed' => '明るい場所でもう一度お試しください。',
                'quality_failed' => 'カメラを正面から見てください。',
                'blink_failed' => '目を瞬いてください。',
                'security_failed' => 'セキュリティ検証に失敗しました。',
                'member_not_found' => '会員が見つかりません。会員番号を入力してください。',
                'no_active_ticket' => '利用可能なチケットがありません。',
                'auth_success' => '{name}様、こんにちは！',
                'server_error' => 'システムエラーが発生しました。後でもう一度お試しください。',
                'missing_params' => '必須情報が不足しています。',
                'checkin_success' => '{ticket_name}でチェックインが完了しました。',
                'checkin_failed' => 'チェックインに失敗しました。',
                'remaining_count' => '残り{count}回',
                'remaining_days' => '残り{days}日'
            ],
            'zh' => [
                'no_face_detected' => '未检测到人脸。请看着摄像头。',
                'liveness_failed' => '请在光线充足的地方重试。',
                'quality_failed' => '请正对摄像头。',
                'blink_failed' => '请眨眼。',
                'security_failed' => '安全验证失败。',
                'member_not_found' => '未找到会员。请输入会员号。',
                'no_active_ticket' => '没有可用的票券。',
                'auth_success' => '{name}，您好！',
                'server_error' => '系统错误。请稍后重试。',
                'missing_params' => '缺少必要信息。',
                'checkin_success' => '使用{ticket_name}完成入场。',
                'checkin_failed' => '入场失败。',
                'remaining_count' => '剩余{count}次',
                'remaining_days' => '剩余{days}天'
            ]
        ];
        
        $message = $messages[$language][$key] ?? $messages['ko'][$key] ?? $key;
        
        // 파라미터 치환
        foreach ($params as $param => $value) {
            $message = str_replace('{' . $param . '}', $value, $message);
        }
        
        return $message;
    }
    
    /**
     * 회원 정보 조회
     */
    private function getMemberInfo($memberSno): ?array
    {
        $db = \Config\Database::connect();
        
        // API 키의 회사/지점 정보 사용
        $compCd = $this->currentApiKeyInfo['comp_cd'] ?? null;
        $bcoffCd = $this->currentApiKeyInfo['bcoff_cd'] ?? null;
        
        // 회사/지점별로 회원 조회
        $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM, M.MEM_TELNO, M.MEM_GENDR, M.MEM_DV, 
                       M.SET_COMP_CD, M.SET_BCOFF_CD, M.USE_YN
                FROM mem_main_info_tbl M  
                WHERE M.MEM_SNO = ? 
                AND M.USE_YN = 'Y'
                AND M.SET_COMP_CD = ?
                AND M.SET_BCOFF_CD = ?";
                
        $query = $db->query($sql, [$memberSno, $compCd, $bcoffCd]);
        $result = $query->getRowArray();
        
        // 로그 기록
        if (!$result) {
            log_message('debug', "[KioskAuthApi] Member not found or not in this branch. MEM_SNO: $memberSno, COMP_CD: $compCd, BCOFF_CD: $bcoffCd");
        }
        
        return $result ?: null;
    }
    
    /**
     * 회원 이용권 목록 조회
     */
    private function getMemberTickets($memberSno): array
    {
        // AttdModel의 cur_available_membership 메서드 사용
        $initVar = [
            'comp_cd' => $this->currentApiKeyInfo['comp_cd'] ?? $_SESSION['comp_cd'] ?? 'C00001',
            'bcoff_cd' => $this->currentApiKeyInfo['bcoff_cd'] ?? $_SESSION['bcoff_cd'] ?? 'B00001',
            'mem_sno' => $memberSno
        ];
        
        $ticketList = $this->attdModel->cur_available_membership($initVar);
        
        // 데이터 정리 및 포맷팅
        if (is_array($ticketList)) {
            $formattedTickets = [];
            foreach ($ticketList as $ticket) {
                if (is_array($ticket)) {
                    $formattedTickets[] = [
                        'ticket_id' => $ticket['PTHRC_SNO'] ?? '',
                        'ticket_name' => $ticket['GDS_NM'] ?? '',
                        'ticket_type' => isset($ticket['USE_CNT']) ? 'count' : 'period',
                        'status' => 'active',
                        'remaining_count' => $ticket['USE_CNT'] ?? null,
                        'remaining_days' => $ticket['REM_DAY'] ?? null,
                        'expire_date' => $ticket['END_DATE'] ?? '',
                        'remaining_info' => $ticket['REM_INFO'] ?? ''
                    ];
                }
            }
            return $formattedTickets;
        }
        
        return [];
    }
    
    /**
     * 체크인 처리
     */
    private function processCheckin($memSno, $ticketId, $deviceId, $location): array
    {
        try {
            // 체크인 처리 로직
            $attdLib = new \App\Libraries\Attd_lib();
            
            $checkInData = [
                'comp_cd' => $this->currentApiKeyInfo['comp_cd'] ?? $_SESSION['comp_cd'] ?? 'C00001',
                'bcoff_cd' => $this->currentApiKeyInfo['bcoff_cd'] ?? $_SESSION['bcoff_cd'] ?? 'B00001',
                'mem_sno' => $memSno,
                'pthrc_sno' => $ticketId,
                'device_id' => $deviceId,
                'location' => $location,
                'attd_dt' => date('Y-m-d'),
                'attd_tm' => date('H:i:s')
            ];
            
            // 체크인 처리
            $result = $attdLib->insertAttd($checkInData);
            
            if ($result) {
                return [
                    'success' => true,
                    'checkin_id' => 'CHK' . date('YmdHis'),
                    'ticket_name' => $result['ticket_name'] ?? '',
                    'remaining_count' => $result['remaining_count'] ?? null,
                    'expire_date' => $result['expire_date'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'message' => '체크인 처리 실패'
                ];
            }
        } catch (\Exception $e) {
            log_message('error', '[KioskAuthApi] Checkin error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => '체크인 처리 중 오류 발생'
            ];
        }
    }
}