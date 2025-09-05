<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

/**
 * PG/VAN 설정 관리 모델
 * 
 * PG(Payment Gateway - 결제대행) 및 VAN(Value Added Network - 신용카드단말기) 
 * 설정 정보를 관리하는 모델입니다.
 * 
 * 주요 기능:
 * - PG/VAN 설정 조회/수정
 * - 암호화된 민감 데이터 처리
 * - 설정 유효성 검증
 * - 설정 백업/복원
 * - 결제 제공업체 활성화/비활성화
 * 
 * @author SpoqPlus Development Team
 * @version 1.0.0
 * @since 2025-07-29
 */
class PgVanSettingsModel extends Model
{
    protected $table = 'bcoff_mgmt_tbl';
    protected $primaryKey = 'BCOFF_CD';
    protected $allowedFields = [
        'PG_SETTINGS', 'VAN_SETTINGS', 'PAYMENT_DEFAULT_SETTINGS', 
        'PG_VAN_BACKUP_SETTINGS', 'SETTINGS_UPDATE_HIST', 'MOD_ID', 'MOD_DATETM'
    ];
    
    // 암호화 서비스
    protected $encrypter;
    
    // 로그 서비스
    protected $logger;
    
    public function __construct()
    {
        parent::__construct();
        $this->encrypter = Services::encrypter();
        $this->logger = Services::logger();
    }

    /**
     * 지점별 PG 설정 조회
     * 
     * @param array $data ['comp_cd' => string, 'bcoff_cd' => string]
     * @return array PG 설정 정보
     * 
     * @example
     * $settings = $model->get_pg_settings(['comp_cd' => 'COMP001', 'bcoff_cd' => 'BCOFF001']);
     */
    public function get_pg_settings(array $data): array
    {
        try {
            $sql = "SELECT COMP_CD, BCOFF_CD, PG_SETTINGS, PAYMENT_DEFAULT_SETTINGS, 
                           MOD_ID, MOD_DATETM
                    FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $result = $query->getRowArray();
            
            if (!$result) {
                $this->logger->info("PG 설정 조회 실패 - 지점 정보 없음", $data);
                return [];
            }
            
            // JSON 데이터 디코딩 및 암호화된 데이터 복호화
            $pg_settings = $this->decode_and_decrypt_settings($result['PG_SETTINGS'] ?? '{}');
            $default_settings = json_decode($result['PAYMENT_DEFAULT_SETTINGS'] ?? '{}', true);
            
            return [
                'comp_cd' => $result['COMP_CD'],
                'bcoff_cd' => $result['BCOFF_CD'],
                'pg_settings' => $pg_settings,
                'default_settings' => $default_settings,
                'mod_id' => $result['MOD_ID'],
                'mod_datetm' => $result['MOD_DATETM']
            ];
            
        } catch (\Exception $e) {
            $this->logger->error("PG 설정 조회 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("PG 설정 조회 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    /**
     * 지점별 VAN 설정 조회
     * 
     * @param array $data ['comp_cd' => string, 'bcoff_cd' => string]
     * @return array VAN 설정 정보
     */
    public function get_van_settings(array $data): array
    {
        try {
            $sql = "SELECT COMP_CD, BCOFF_CD, VAN_SETTINGS, PAYMENT_DEFAULT_SETTINGS,
                           MOD_ID, MOD_DATETM
                    FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $result = $query->getRowArray();
            
            if (!$result) {
                $this->logger->info("VAN 설정 조회 실패 - 지점 정보 없음", $data);
                return [];
            }
            
            // JSON 데이터 디코딩 및 암호화된 데이터 복호화
            $van_settings = $this->decode_and_decrypt_settings($result['VAN_SETTINGS'] ?? '{}');
            $default_settings = json_decode($result['PAYMENT_DEFAULT_SETTINGS'] ?? '{}', true);
            
            return [
                'comp_cd' => $result['COMP_CD'],
                'bcoff_cd' => $result['BCOFF_CD'],
                'van_settings' => $van_settings,
                'default_settings' => $default_settings,
                'mod_id' => $result['MOD_ID'],
                'mod_datetm' => $result['MOD_DATETM']
            ];
            
        } catch (\Exception $e) {
            $this->logger->error("VAN 설정 조회 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("VAN 설정 조회 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    /**
     * PG/VAN 통합 결제 설정 조회
     * 
     * @param array $data ['comp_cd' => string, 'bcoff_cd' => string]
     * @return array 통합 결제 설정 정보
     */
    public function get_all_payment_settings(array $data): array
    {
        try {
            $sql = "SELECT COMP_CD, BCOFF_CD, PG_SETTINGS, VAN_SETTINGS, 
                           PAYMENT_DEFAULT_SETTINGS, MOD_ID, MOD_DATETM
                    FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $result = $query->getRowArray();
            
            if (!$result) {
                return $this->get_default_payment_config();
            }
            
            // JSON 데이터 디코딩 및 암호화된 데이터 복호화
            $pg_settings = $this->decode_and_decrypt_settings($result['PG_SETTINGS'] ?? '{}');
            $van_settings = $this->decode_and_decrypt_settings($result['VAN_SETTINGS'] ?? '{}');
            $default_settings = json_decode($result['PAYMENT_DEFAULT_SETTINGS'] ?? '{}', true);
            
            return [
                'comp_cd' => $result['COMP_CD'],
                'bcoff_cd' => $result['BCOFF_CD'],
                'pg_settings' => $pg_settings,
                'van_settings' => $van_settings,
                'default_settings' => $default_settings,
                'mod_id' => $result['MOD_ID'],
                'mod_datetm' => $result['MOD_DATETM']
            ];
            
        } catch (\Exception $e) {
            $this->logger->error("통합 결제 설정 조회 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("통합 결제 설정 조회 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    /**
     * PG 설정 업데이트
     * 
     * @param array $data 업데이트할 PG 설정 데이터
     * @return array 업데이트 결과
     */
    public function update_pg_settings(array $data): array
    {
        try {
            // 설정 유효성 검증
            $validation_result = $this->validate_pg_settings($data['pg_settings']);
            if (!$validation_result['valid']) {
                throw new \InvalidArgumentException("PG 설정 유효성 검증 실패: " . implode(', ', $validation_result['errors']));
            }
            
            // 기존 설정 백업
            $this->backup_settings($data);
            
            // 민감 데이터 암호화
            $encrypted_settings = $this->encrypt_sensitive_data($data['pg_settings']);
            
            // 설정 업데이트 이력 추가
            $update_history = $this->add_update_history('PG', $data);
            
            $sql = "UPDATE bcoff_mgmt_tbl SET 
                        PG_SETTINGS = :pg_settings:,
                        SETTINGS_UPDATE_HIST = :update_hist:,
                        MOD_ID = :mod_id:,
                        MOD_DATETM = :mod_datetm:
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'pg_settings' => json_encode($encrypted_settings, JSON_UNESCAPED_UNICODE),
                'update_hist' => json_encode($update_history, JSON_UNESCAPED_UNICODE),
                'mod_id' => $data['mod_id'],
                'mod_datetm' => $data['mod_datetm'],
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $this->logger->info("PG 설정 업데이트 완료", [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd'],
                'mod_id' => $data['mod_id']
            ]);
            
            return [
                'success' => true,
                'message' => 'PG 설정이 성공적으로 업데이트되었습니다.',
                'updated_at' => $data['mod_datetm']
            ];
            
        } catch (\Exception $e) {
            $this->logger->error("PG 설정 업데이트 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("PG 설정 업데이트 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    /**
     * VAN 설정 업데이트
     * 
     * @param array $data 업데이트할 VAN 설정 데이터
     * @return array 업데이트 결과
     */
    public function update_van_settings(array $data): array
    {
        try {
            // 설정 유효성 검증
            $validation_result = $this->validate_van_settings($data['van_settings']);
            if (!$validation_result['valid']) {
                throw new \InvalidArgumentException("VAN 설정 유효성 검증 실패: " . implode(', ', $validation_result['errors']));
            }
            
            // 기존 설정 백업
            $this->backup_settings($data);
            
            // 민감 데이터 암호화
            $encrypted_settings = $this->encrypt_sensitive_data($data['van_settings']);
            
            // 설정 업데이트 이력 추가
            $update_history = $this->add_update_history('VAN', $data);
            
            $sql = "UPDATE bcoff_mgmt_tbl SET 
                        VAN_SETTINGS = :van_settings:,
                        SETTINGS_UPDATE_HIST = :update_hist:,
                        MOD_ID = :mod_id:,
                        MOD_DATETM = :mod_datetm:
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'van_settings' => json_encode($encrypted_settings, JSON_UNESCAPED_UNICODE),
                'update_hist' => json_encode($update_history, JSON_UNESCAPED_UNICODE),
                'mod_id' => $data['mod_id'],
                'mod_datetm' => $data['mod_datetm'],
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $this->logger->info("VAN 설정 업데이트 완료", [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd'],
                'mod_id' => $data['mod_id']
            ]);
            
            return [
                'success' => true,
                'message' => 'VAN 설정이 성공적으로 업데이트되었습니다.',
                'updated_at' => $data['mod_datetm']
            ];
            
        } catch (\Exception $e) {
            $this->logger->error("VAN 설정 업데이트 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("VAN 설정 업데이트 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    /**
     * 특정 결제 제공업체 활성화/비활성화
     * 
     * @param array $data ['comp_cd', 'bcoff_cd', 'provider_type', 'provider_name', 'status', 'mod_id', 'mod_datetm']
     * @return array 업데이트 결과
     */
    public function toggle_provider_status(array $data): array
    {
        try {
            $provider_type = strtoupper($data['provider_type']); // PG 또는 VAN
            $provider_name = $data['provider_name'];
            $status = $data['status']; // 'Y' 또는 'N'
            
            // 현재 설정 조회
            $current_settings = $this->get_all_payment_settings([
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            if (empty($current_settings)) {
                throw new \InvalidArgumentException("해당 지점의 설정 정보를 찾을 수 없습니다.");
            }
            
            // 제공업체 상태 업데이트
            if ($provider_type === 'PG') {
                $settings = $current_settings['pg_settings'];
                if (!isset($settings[$provider_name])) {
                    throw new \InvalidArgumentException("PG 제공업체 '{$provider_name}'를 찾을 수 없습니다.");
                }
                $settings[$provider_name]['enabled'] = ($status === 'Y');
                $update_data = array_merge($data, ['pg_settings' => $settings]);
                return $this->update_pg_settings($update_data);
                
            } elseif ($provider_type === 'VAN') {
                $settings = $current_settings['van_settings'];
                if (!isset($settings[$provider_name])) {
                    throw new \InvalidArgumentException("VAN 제공업체 '{$provider_name}'를 찾을 수 없습니다.");
                }
                $settings[$provider_name]['enabled'] = ($status === 'Y');
                $update_data = array_merge($data, ['van_settings' => $settings]);
                return $this->update_van_settings($update_data);
                
            } else {
                throw new \InvalidArgumentException("잘못된 제공업체 타입입니다. 'PG' 또는 'VAN'이어야 합니다.");
            }
            
        } catch (\Exception $e) {
            $this->logger->error("제공업체 상태 변경 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("제공업체 상태 변경 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    /**
     * 기본 결제 설정 구성 조회
     * 
     * @return array 기본 결제 설정
     */
    public function get_default_payment_config(): array
    {
        return [
            'pg_settings' => [
                'kcp' => [
                    'enabled' => false,
                    'merchant_id' => '',
                    'merchant_key' => '',
                    'test_mode' => true,
                    'priority' => 1
                ],
                'inicis' => [
                    'enabled' => false,
                    'merchant_id' => '',
                    'merchant_key' => '',
                    'test_mode' => true,
                    'priority' => 2
                ],
                'toss' => [
                    'enabled' => false,
                    'client_key' => '',
                    'secret_key' => '',
                    'test_mode' => true,
                    'priority' => 3
                ]
            ],
            'van_settings' => [
                'ksnet' => [
                    'enabled' => false,
                    'terminal_id' => '',
                    'merchant_id' => '',
                    'encryption_key' => '',
                    'test_mode' => true,
                    'priority' => 1
                ],
                'nice' => [
                    'enabled' => false,
                    'terminal_id' => '',
                    'merchant_id' => '',
                    'encryption_key' => '',
                    'test_mode' => true,
                    'priority' => 2
                ],
                'kicc' => [
                    'enabled' => false,
                    'terminal_id' => '',
                    'merchant_id' => '',
                    'encryption_key' => '',
                    'test_mode' => true,
                    'priority' => 3
                ]
            ],
            'default_settings' => [
                'default_payment_method' => 'card',
                'auto_capture' => true,
                'currency' => 'KRW',
                'timeout' => 30,
                'retry_count' => 3,
                'notification_enabled' => true
            ]
        ];
    }

    /**
     * 암호화된 민감 데이터 저장
     * 
     * @param array $data 설정 데이터
     * @return array 저장 결과
     */
    public function save_encrypted_settings(array $data): array
    {
        try {
            // 민감 데이터 암호화
            $encrypted_data = $this->encrypt_sensitive_data($data['settings']);
            
            $sql = "UPDATE bcoff_mgmt_tbl SET 
                        {$data['settings_type']} = :settings:,
                        MOD_ID = :mod_id:,
                        MOD_DATETM = :mod_datetm:
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'settings' => json_encode($encrypted_data, JSON_UNESCAPED_UNICODE),
                'mod_id' => $data['mod_id'],
                'mod_datetm' => $data['mod_datetm'],
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            return [
                'success' => true,
                'message' => '암호화된 설정이 저장되었습니다.'
            ];
            
        } catch (\Exception $e) {
            $this->logger->error("암호화 설정 저장 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("암호화 설정 저장 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    /**
     * 설정 데이터 유효성 검증
     * 
     * @param array $settings 검증할 설정 데이터
     * @param string $type 설정 타입 ('PG' 또는 'VAN')
     * @return array 검증 결과
     */
    public function validate_settings(array $settings, string $type = 'PG'): array
    {
        if ($type === 'PG') {
            return $this->validate_pg_settings($settings);
        } elseif ($type === 'VAN') {
            return $this->validate_van_settings($settings);
        } else {
            return ['valid' => false, 'errors' => ['잘못된 설정 타입입니다.']];
        }
    }

    /**
     * 설정 백업
     * 
     * @param array $data 백업할 설정 데이터
     * @return bool 백업 성공 여부
     */
    public function backup_settings(array $data): bool
    {
        try {
            // 현재 설정 조회
            $current_settings = $this->get_all_payment_settings([
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            if (empty($current_settings)) {
                return true; // 기존 설정이 없으면 백업할 필요 없음
            }
            
            // 백업 데이터 생성
            $backup_data = [
                'backup_date' => date('Y-m-d H:i:s'),
                'backup_by' => $data['mod_id'] ?? 'system',
                'pg_settings' => $current_settings['pg_settings'] ?? [],
                'van_settings' => $current_settings['van_settings'] ?? [],
                'default_settings' => $current_settings['default_settings'] ?? []
            ];
            
            $sql = "UPDATE bcoff_mgmt_tbl SET 
                        PG_VAN_BACKUP_SETTINGS = :backup_data:
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $this->db->query($sql, [
                'backup_data' => json_encode($backup_data, JSON_UNESCAPED_UNICODE),
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $this->logger->info("설정 백업 완료", [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            $this->logger->error("설정 백업 오류: " . $e->getMessage(), $data);
            return false;
        }
    }

    /**
     * 설정 복원
     * 
     * @param array $data 복원할 지점 정보
     * @return array 복원 결과
     */
    public function restore_settings(array $data): array
    {
        try {
            $sql = "SELECT PG_VAN_BACKUP_SETTINGS 
                    FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $result = $query->getRowArray();
            
            if (!$result || empty($result['PG_VAN_BACKUP_SETTINGS'])) {
                throw new \RuntimeException("복원할 백업 데이터가 없습니다.");
            }
            
            $backup_data = json_decode($result['PG_VAN_BACKUP_SETTINGS'], true);
            
            // 백업 데이터로 설정 복원
            $restore_sql = "UPDATE bcoff_mgmt_tbl SET 
                                PG_SETTINGS = :pg_settings:,
                                VAN_SETTINGS = :van_settings:,
                                PAYMENT_DEFAULT_SETTINGS = :default_settings:,
                                MOD_ID = :mod_id:,
                                MOD_DATETM = :mod_datetm:
                            WHERE COMP_CD = :comp_cd: 
                            AND BCOFF_CD = :bcoff_cd:";
            
            $this->db->query($restore_sql, [
                'pg_settings' => json_encode($backup_data['pg_settings'] ?? [], JSON_UNESCAPED_UNICODE),
                'van_settings' => json_encode($backup_data['van_settings'] ?? [], JSON_UNESCAPED_UNICODE),
                'default_settings' => json_encode($backup_data['default_settings'] ?? [], JSON_UNESCAPED_UNICODE),
                'mod_id' => $data['mod_id'],
                'mod_datetm' => $data['mod_datetm'],
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $this->logger->info("설정 복원 완료", [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd'],
                'backup_date' => $backup_data['backup_date']
            ]);
            
            return [
                'success' => true,
                'message' => '설정이 성공적으로 복원되었습니다.',
                'backup_date' => $backup_data['backup_date']
            ];
            
        } catch (\Exception $e) {
            $this->logger->error("설정 복원 오류: " . $e->getMessage(), $data);
            throw new \RuntimeException("설정 복원 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    // ===== PRIVATE 메서드들 =====

    /**
     * JSON 설정 데이터 디코딩 및 복호화
     * 
     * @param string $json_data JSON 문자열
     * @return array 디코딩된 설정 데이터
     */
    private function decode_and_decrypt_settings(string $json_data): array
    {
        if (empty($json_data) || $json_data === '{}') {
            return [];
        }
        
        $settings = json_decode($json_data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->warning("JSON 디코딩 오류: " . json_last_error_msg());
            return [];
        }
        
        return $this->decrypt_sensitive_data($settings);
    }

    /**
     * 민감 데이터 암호화
     * 
     * @param array $settings 설정 데이터
     * @return array 암호화된 설정 데이터
     */
    private function encrypt_sensitive_data(array $settings): array
    {
        $encrypted_settings = $settings;
        $sensitive_fields = ['merchant_key', 'secret_key', 'encryption_key', 'api_key', 'private_key'];
        
        foreach ($settings as $provider => &$config) {
            if (is_array($config)) {
                foreach ($config as $key => &$value) {
                    if (in_array($key, $sensitive_fields) && !empty($value)) {
                        try {
                            $encrypted_settings[$provider][$key] = $this->encrypter->encrypt($value);
                        } catch (\Exception $e) {
                            $this->logger->warning("암호화 실패 - {$provider}.{$key}: " . $e->getMessage());
                        }
                    }
                }
            }
        }
        
        return $encrypted_settings;
    }

    /**
     * 민감 데이터 복호화
     * 
     * @param array $settings 암호화된 설정 데이터
     * @return array 복호화된 설정 데이터
     */
    private function decrypt_sensitive_data(array $settings): array
    {
        $decrypted_settings = $settings;
        $sensitive_fields = ['merchant_key', 'secret_key', 'encryption_key', 'api_key', 'private_key'];
        
        foreach ($settings as $provider => &$config) {
            if (is_array($config)) {
                foreach ($config as $key => &$value) {
                    if (in_array($key, $sensitive_fields) && !empty($value)) {
                        try {
                            $decrypted_settings[$provider][$key] = $this->encrypter->decrypt($value);
                        } catch (\Exception $e) {
                            $this->logger->warning("복호화 실패 - {$provider}.{$key}: " . $e->getMessage());
                            $decrypted_settings[$provider][$key] = '';
                        }
                    }
                }
            }
        }
        
        return $decrypted_settings;
    }

    /**
     * PG 설정 유효성 검증
     * 
     * @param array $settings PG 설정 데이터
     * @return array 검증 결과
     */
    private function validate_pg_settings(array $settings): array
    {
        $errors = [];
        $valid_providers = ['kcp', 'inicis', 'toss'];
        
        foreach ($settings as $provider => $config) {
            if (!in_array($provider, $valid_providers)) {
                $errors[] = "지원하지 않는 PG 제공업체입니다: {$provider}";
                continue;
            }
            
            if (!is_array($config)) {
                $errors[] = "{$provider} 설정이 올바르지 않습니다.";
                continue;
            }
            
            // 필수 필드 검증
            $required_fields = $this->get_required_fields_for_pg($provider);
            foreach ($required_fields as $field) {
                if (empty($config[$field])) {
                    $errors[] = "{$provider}의 {$field} 설정이 누락되었습니다.";
                }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * VAN 설정 유효성 검증
     * 
     * @param array $settings VAN 설정 데이터
     * @return array 검증 결과
     */
    private function validate_van_settings(array $settings): array
    {
        $errors = [];
        $valid_providers = ['ksnet', 'nice', 'kicc'];
        
        foreach ($settings as $provider => $config) {
            if (!in_array($provider, $valid_providers)) {
                $errors[] = "지원하지 않는 VAN 제공업체입니다: {$provider}";
                continue;
            }
            
            if (!is_array($config)) {
                $errors[] = "{$provider} 설정이 올바르지 않습니다.";
                continue;
            }
            
            // 필수 필드 검증
            $required_fields = $this->get_required_fields_for_van($provider);
            foreach ($required_fields as $field) {
                if (empty($config[$field])) {
                    $errors[] = "{$provider}의 {$field} 설정이 누락되었습니다.";
                }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * PG 제공업체별 필수 필드 반환
     * 
     * @param string $provider PG 제공업체명
     * @return array 필수 필드 목록
     */
    private function get_required_fields_for_pg(string $provider): array
    {
        $required_fields = [
            'kcp' => ['merchant_id', 'merchant_key'],
            'inicis' => ['merchant_id', 'merchant_key'],
            'toss' => ['client_key', 'secret_key']
        ];
        
        return $required_fields[$provider] ?? [];
    }

    /**
     * VAN 제공업체별 필수 필드 반환
     * 
     * @param string $provider VAN 제공업체명
     * @return array 필수 필드 목록
     */
    private function get_required_fields_for_van(string $provider): array
    {
        $required_fields = [
            'ksnet' => ['terminal_id', 'merchant_id'],
            'nice' => ['terminal_id', 'merchant_id'],
            'kicc' => ['terminal_id', 'merchant_id']
        ];
        
        return $required_fields[$provider] ?? [];
    }

    /**
     * 설정 업데이트 이력 추가
     * 
     * @param string $type 설정 타입 ('PG' 또는 'VAN')
     * @param array $data 업데이트 데이터
     * @return array 업데이트 이력
     */
    private function add_update_history(string $type, array $data): array
    {
        try {
            // 기존 이력 조회
            $sql = "SELECT SETTINGS_UPDATE_HIST FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            $result = $query->getRowArray();
            $history = json_decode($result['SETTINGS_UPDATE_HIST'] ?? '[]', true);
            
            // 새 이력 추가
            $new_entry = [
                'type' => $type,
                'updated_at' => $data['mod_datetm'],
                'updated_by' => $data['mod_id'],
                'changes' => $this->get_setting_changes($type, $data)
            ];
            
            array_unshift($history, $new_entry);
            
            // 최대 50개 이력만 보관
            if (count($history) > 50) {
                $history = array_slice($history, 0, 50);
            }
            
            return $history;
            
        } catch (\Exception $e) {
            $this->logger->warning("업데이트 이력 추가 오류: " . $e->getMessage());
            return [];
        }
    }

    /**
     * 설정 변경 내용 추출
     * 
     * @param string $type 설정 타입
     * @param array $data 변경 데이터
     * @return array 변경 내용
     */
    private function get_setting_changes(string $type, array $data): array
    {
        $changes = [];
        $settings_key = strtolower($type) . '_settings';
        
        if (isset($data[$settings_key])) {
            foreach ($data[$settings_key] as $provider => $config) {
                if (isset($config['enabled'])) {
                    $changes[] = [
                        'provider' => $provider,
                        'field' => 'enabled',
                        'new_value' => $config['enabled'] ? 'Y' : 'N'
                    ];
                }
            }
        }
        
        return $changes;
    }
}