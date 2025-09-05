<?php

namespace App\Models;

use CodeIgniter\Model;

class FaceRecognitionModel extends Model
{
    protected $table = 'member_faces';
    protected $primaryKey = 'face_id';
    
    protected $allowedFields = [
        'mem_sno', 'face_encoding', 'face_image_path', 'glasses_detected',
        'quality_score', 'security_level', 'liveness_score', 'is_active', 'notes'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'registered_date';
    protected $updatedField = 'last_updated';
    
    /**
     * 회원의 얼굴 데이터 조회
     * @param string $mem_sno
     * @return array|null
     */
    public function getFaceByMemSno($mem_sno)
    {
        return $this->where([
            'mem_sno' => $mem_sno,
            'is_active' => 1
        ])->first();
    }
    
    /**
     * 활성화된 모든 얼굴 데이터 조회
     * @return array
     */
    public function getAllActiveFaces()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    /**
     * 얼굴 데이터 등록
     * @param array $data
     * @return bool
     */
    public function registerFace($data)
    {
        // 🔍 입력 데이터 로깅
        log_message('info', '[MODEL] 🔍 registerFace 입력 데이터: ' . json_encode($data));
        log_message('info', '[MODEL] 🔍 입력 glasses_detected 값: ' . var_export($data['glasses_detected'] ?? 'NOT_SET', true));
        log_message('info', '[MODEL] 🔍 입력 glasses_detected 타입: ' . gettype($data['glasses_detected'] ?? null));
        
        // 기존 데이터가 있는지 확인 (is_active = 1인 것만)
        $existing = $this->where([
            'mem_sno' => $data['mem_sno'],
            'is_active' => 1
        ])->first();
        
        if ($existing) {
            // 기존 활성 데이터가 있으면 업데이트
            $updateData = [
                'face_encoding' => $data['face_encoding'],
                'glasses_detected' => $data['glasses_detected'],
                'quality_score' => $data['quality_score'],
                'security_level' => $data['security_level'] ?? 'medium',
                'liveness_score' => $data['liveness_score'] ?? 0.95,
                'notes' => $data['notes'] ?? 'Updated via API',
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            // 🔍 업데이트 데이터 로깅
            log_message('info', '[MODEL] 🔍 업데이트 데이터: ' . json_encode($updateData));
            log_message('info', '[MODEL] 🔍 업데이트 glasses_detected 값: ' . var_export($updateData['glasses_detected'], true));
            
            $updateResult = $this->update($existing['face_id'], $updateData);
            
            if ($updateResult) {
                log_message('info', "회원 {$data['mem_sno']}의 얼굴 데이터 업데이트 성공 (ID: {$existing['face_id']})");
                
                // 🔍 실제 저장된 데이터 확인
                $savedData = $this->find($existing['face_id']);
                log_message('info', '[MODEL] 🔍 저장된 데이터 확인: ' . json_encode($savedData));
                log_message('info', '[MODEL] 🔍 저장된 glasses_detected 값: ' . var_export($savedData['glasses_detected'] ?? 'NOT_FOUND', true));
                
                return $existing['face_id']; // 기존 ID 반환
            } else {
                log_message('error', "회원 {$data['mem_sno']}의 얼굴 데이터 업데이트 실패");
                return false;
            }
        } else {
            // 기존 데이터가 없으면 새로 삽입
            $insertData = [
                'mem_sno' => $data['mem_sno'],
                'face_encoding' => $data['face_encoding'],
                'glasses_detected' => $data['glasses_detected'],
                'quality_score' => $data['quality_score'],
                'security_level' => $data['security_level'] ?? 'medium',
                'liveness_score' => $data['liveness_score'] ?? 0.95,
                'is_active' => 1,
                'notes' => $data['notes'] ?? 'Registered via API'
            ];
            
            // 🔍 삽입 데이터 로깅
            log_message('info', '[MODEL] 🔍 삽입 데이터: ' . json_encode($insertData));
            log_message('info', '[MODEL] 🔍 삽입 glasses_detected 값: ' . var_export($insertData['glasses_detected'], true));
            
            $insertResult = $this->insert($insertData);
            
            if ($insertResult) {
                $newId = $this->getInsertID();
                log_message('info', "회원 {$data['mem_sno']}의 새 얼굴 데이터 등록 성공 (ID: {$newId})");
                
                // 🔍 실제 저장된 데이터 확인
                $savedData = $this->find($newId);
                log_message('info', '[MODEL] 🔍 저장된 데이터 확인: ' . json_encode($savedData));
                log_message('info', '[MODEL] 🔍 저장된 glasses_detected 값: ' . var_export($savedData['glasses_detected'] ?? 'NOT_FOUND', true));
                
                return $newId; // 새 ID 반환
            } else {
                log_message('error', "회원 {$data['mem_sno']}의 얼굴 데이터 등록 실패");
                return false;
            }
        }
    }
    
    /**
     * 얼굴 데이터 비활성화
     * @param string $mem_sno
     * @return bool
     */
    public function deactivateFace($mem_sno)
    {
        return $this->where('mem_sno', $mem_sno)->set(['is_active' => 0])->update();
    }
    
    /**
     * 얼굴 인식 로그 저장
     * @param array $logData
     * @return bool
     */
    public function saveFaceRecognitionLog($logData)
    {
        // 얼굴 등록인 경우 기존 로그를 UPDATE
        if (($logData['match_category'] ?? '') === 'registration' && !empty($logData['mem_sno'])) {
            // 새로운 쿼리 빌더 인스턴스 생성
            $selectBuilder = $this->db->table('face_recognition_logs');
            
            // 같은 회원의 기존 등록 로그가 있는지 확인
            $existingLog = $selectBuilder->where('mem_sno', $logData['mem_sno'])
                                       ->where('match_category', 'registration')
                                       ->orderBy('recognition_time', 'DESC')
                                       ->get()
                                       ->getRowArray();
            
            if ($existingLog) {
                // 기존 등록 로그 UPDATE (새로운 builder 사용)
                $updateBuilder = $this->db->table('face_recognition_logs');
                return $updateBuilder->where('log_id', $existingLog['log_id'])->update([
                    'confidence_score' => $logData['confidence_score'] ?? 0,
                    'similarity_score' => $logData['similarity_score'] ?? null,
                    'quality_score' => $logData['quality_score'] ?? null,
                    'processing_time_ms' => $logData['processing_time_ms'] ?? 0,
                    'glasses_detected' => $logData['glasses_detected'] ?? false,
                    'security_checks_passed' => $logData['security_checks_passed'] ?? null,
                    'success' => $logData['success'] ?? false,
                    'error_message' => $logData['error_message'] ?? null,
                    'ip_address' => $logData['ip_address'] ?? null,
                    'user_agent' => $logData['user_agent'] ?? null,
                    'session_id' => $logData['session_id'] ?? null,
                    'recognition_time' => date('Y-m-d H:i:s')
                ]);
            } else{
                $insertBuilder = $this->db->table('face_recognition_logs');
                return $insertBuilder->insert([
                    'mem_sno' => $logData['mem_sno'] ?? null,
                    'confidence_score' => $logData['confidence_score'] ?? 0,
                    'similarity_score' => $logData['similarity_score'] ?? null,
                    'quality_score' => $logData['quality_score'] ?? null,
                    'processing_time_ms' => $logData['processing_time_ms'] ?? 0,
                    'glasses_detected' => $logData['glasses_detected'] ?? false,
                    'match_category' => $logData['match_category'] ?? 'unknown',
                    'security_checks_passed' => $logData['security_checks_passed'] ?? null,
                    'success' => $logData['success'] ?? false,
                    'error_message' => $logData['error_message'] ?? null,
                    'ip_address' => $logData['ip_address'] ?? null,
                    'user_agent' => $logData['user_agent'] ?? null,
                    'session_id' => $logData['session_id'] ?? null
                ]);
            }
        } else
        {
            $insertBuilder = $this->db->table('face_recognition_logs');
            return $insertBuilder->insert([
                'mem_sno' => $logData['mem_sno'] ?? null,
                'confidence_score' => $logData['confidence_score'] ?? 0,
                'similarity_score' => $logData['similarity_score'] ?? null,
                'quality_score' => $logData['quality_score'] ?? null,
                'processing_time_ms' => $logData['processing_time_ms'] ?? 0,
                'glasses_detected' => $logData['glasses_detected'] ?? false,
                'match_category' => $logData['match_category'] ?? 'unknown',
                'security_checks_passed' => $logData['security_checks_passed'] ?? null,
                'success' => $logData['success'] ?? false,
                'error_message' => $logData['error_message'] ?? null,
                'ip_address' => $logData['ip_address'] ?? null,
                'user_agent' => $logData['user_agent'] ?? null,
                'session_id' => $logData['session_id'] ?? null
            ]);
        }
    }
    
    /**
     * 얼굴 인식 통계 조회
     * @param array $filters
     * @return array
     */
    public function getFaceRecognitionStats($filters = [])
    {
        $builder = $this->db->table('face_recognition_logs');
        
        // 날짜 필터 적용
        if (isset($filters['start_date'])) {
            $builder->where('recognition_time >=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $builder->where('recognition_time <=', $filters['end_date']);
        }
        
        return [
            'total_attempts' => $builder->countAllResults(false),
            'successful_attempts' => $builder->where('success', 1)->countAllResults(false),
            'failed_attempts' => $builder->where('success', 0)->countAllResults(false),
            'avg_processing_time' => $builder->selectAvg('processing_time_ms')->get()->getRowArray()['processing_time_ms']
        ];
    }
    
    /**
     * 회원 정보와 함께 얼굴 데이터 조회
     * @param array $filters
     * @return array
     */
    public function getFacesWithMemberInfo($filters = [])
    {
        $builder = $this->db->table($this->table . ' mf');
        $builder->select('mf.*, mi.mem_nm, mi.mem_gendr, mi.mem_telno_mask');
        $builder->join('mem_info_detl_tbl mi', 'mf.mem_sno = mi.mem_sno', 'left');
        $builder->where('mf.is_active', 1);
        
        // 필터 적용
        if (isset($filters['glasses_detected'])) {
            $builder->where('mf.glasses_detected', $filters['glasses_detected']);
        }
        if (isset($filters['quality_min'])) {
            $builder->where('mf.quality_score >=', $filters['quality_min']);
        }
        
        $builder->orderBy('mf.registered_date', 'DESC');
        
        if (isset($filters['limit'])) {
            $builder->limit($filters['limit']);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * 얼굴 인식 시스템 설정 조회
     * @param string $config_key
     * @return mixed
     */
    public function getFaceSystemConfig($config_key = null)
    {
        $builder = $this->db->table('face_system_config');
        
        if ($config_key) {
            $result = $builder->where('config_key', $config_key)->get()->getRowArray();
            return $result ? $result['config_value'] : null;
        } else {
            $results = $builder->get()->getResultArray();
            $config = [];
            foreach ($results as $row) {
                $config[$row['config_key']] = $row['config_value'];
            }
            return $config;
        }
    }
    
    /**
     * 얼굴 인식 시스템 설정 업데이트
     * @param string $config_key
     * @param string $config_value
     * @return bool
     */
    public function updateFaceSystemConfig($config_key, $config_value)
    {
        $builder = $this->db->table('face_system_config');
        
        $existing = $builder->where('config_key', $config_key)->get()->getRowArray();
        
        if ($existing) {
            return $builder->where('config_key', $config_key)->update([
                'config_value' => $config_value,
                'updated_date' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $builder->insert([
                'config_key' => $config_key,
                'config_value' => $config_value,
                'description' => 'Auto-generated config'
            ]);
        }
    }
    
    // ==================== 별칭 메서드 (컨트롤러 호환성) ====================
    
    /**
     * 회원 얼굴 데이터 등록 (별칭)
     * @param array $data
     * @return int|false
     */
    public function registerMemberFace($data)
    {
        try {
            $result = $this->registerFace($data);
            
            if ($result) {
                // 새로 삽입된 경우 ID 반환, 업데이트된 경우 true 반환
                return is_numeric($result) ? $result : $this->getInsertID();
            }
            
            return false;
        } catch (\Exception $e) {
            log_message('error', '얼굴 등록 실패: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 회원 얼굴 데이터 비활성화 (별칭)
     * @param string $mem_sno
     * @return bool
     */
    public function deactivateMemberFaces($mem_sno)
    {
        return $this->deactivateFace($mem_sno);
    }
    
    /**
     * 얼굴 인식 로그 저장 (별칭)
     * @param array $logData
     * @return bool
     */
    public function logRecognition($logData)
    {
        return $this->saveFaceRecognitionLog($logData);
    }
} 