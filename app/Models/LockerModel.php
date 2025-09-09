<?php

namespace App\Models;

use CodeIgniter\Model;

class LockerModel extends Model
{
    protected $table = 'lockers';
    protected $primaryKey = 'locker_sno';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'front_sno',
        'locker_no',
        'locker_row',
        'locker_col',
        'locker_floor',
        'use_yn'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'group_sno' => 'required|integer',
        'locker_no' => 'required|max_length[20]',
        'row_idx' => 'required|integer|greater_than_equal_to[0]',
        'col_idx' => 'required|integer|greater_than_equal_to[0]',
        'level_idx' => 'required|integer|greater_than_equal_to[0]',
        'status' => 'required|max_length[1]|in_list[A,U,D]'
    ];

    // 상태 코드에 따른 텍스트 정의
    public static $statusTexts = [
        'A' => '사용가능',
        'U' => '사용중',
        'D' => '사용불가'
    ];

    // 상태 코드에 따른 색상 정의
    public static $statusColors = [
        'A' => '#28a745', // 초록색
        'U' => '#dc3545', // 빨간색
        'D' => '#6c757d'  // 회색
    ];

    // 락커 번호 생성 (예: A-1-01)
    public function generateLockerNo($group_sno, $row, $col, $level)
    {
        $levelChar = chr(65 + $level); // A, B, C, ...
        return sprintf("%s-%d-%02d", $levelChar, $row + 1, $col + 1);
    }

    // 그룹 내 모든 락커 생성
    public function createLockersForGroup($group)
    {
        $lockers = [];
        for ($level = 0; $level < $group['levels']; $level++) {
            for ($row = 0; $row < $group['rows']; $row++) {
                for ($col = 0; $col < $group['cols']; $col++) {
                    $lockers[] = [
                        'group_sno' => $group['group_sno'],
                        'locker_no' => $this->generateLockerNo($group['group_sno'], $row, $col, $level),
                        'row_idx' => $row,
                        'col_idx' => $col,
                        'level_idx' => $level,
                        'status' => 'A' // 기본값: 사용가능
                    ];
                }
            }
        }
        return $this->insertBatch($lockers);
    }

    // 도면 관련
    public function insert_floor($data)
    {
        $builder = $this->db->table('tb_locker_floor');
        return $builder->insert($data);
    }

    public function get_floor_list()
    {
        $builder = $this->db->table('tb_locker_floor');
        $builder->where('use_yn', 'Y');
        $builder->orderBy('floor_ord', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function get_floor_info($data)
    {
        $builder = $this->db->table('tb_locker_floor');
        
        if (is_array($data)) {
            $builder->where('floor_sno', $data['floor_sno']);
            if (isset($data['comp_cd'])) {
                $builder->where('comp_cd', $data['comp_cd']);
            }
            if (isset($data['bcoff_cd'])) {
                $builder->where('bcoff_cd', $data['bcoff_cd']);
            }
        } else {
            $builder->where('floor_sno', $data);
        }
        
        return $builder->get()->getRowArray();
    }

    // 도면 삭제
    public function delete_floor($data)
    {
        $builder = $this->db->table('tb_locker_floor');
        $builder->where('floor_sno', $data['floor_sno']);
        return $builder->delete();
    }

    // 구역 관련
    public function insert_zone($data)
    {
        $builder = $this->db->table('tb_locker_zone');
        return $builder->insert($data);
    }

    public function update_zone($zone_sno, $data)
    {
        $builder = $this->db->table('tb_locker_zone');
        $builder->where('zone_sno', $zone_sno);
        return $builder->update($data);
    }

    public function get_zone_list($floor_sno)
    {
        $builder = $this->db->table('tb_locker_zone');
        $builder->where('floor_sno', $floor_sno);
        $builder->where('use_yn', 'Y');
        return $builder->get()->getResultArray();
    }

    public function get_zone_info($zone_sno)
    {
        $builder = $this->db->table('tb_locker_zone');
        $builder->where('zone_sno', $zone_sno);
        return $builder->get()->getRowArray();
    }

    // 구역 삭제
    public function delete_zone($zone_sno)
    {
        try {
            $this->db->transStart();
            
            // 구역 삭제 (연관된 락커 그룹은 외래키 CASCADE 설정으로 자동 삭제)
            $this->db->table('tb_locker_zone')
                     ->where('zone_sno', $zone_sno)
                     ->delete();
            
            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Error deleting zone: ' . $e->getMessage());
            return false;
        }
    }

    // 락커 그룹 관련
    public function insert_group($data)
    {
        $builder = $this->db->table('tb_locker_group');
        if ($builder->insert($data)) {
            // ✅ 성공 시 새로 생성된 group_sno 반환
            return $this->db->insertID();
        }
        return false;
    }

    public function update_group($group_sno, $data)
    {
        $builder = $this->db->table('tb_locker_group');
        $builder->where('group_sno', $group_sno);
        return $builder->update($data);
    }

    public function get_group_list($zone_sno)
    {
        $builder = $this->db->table('tb_locker_group');
        $builder->where('zone_sno', $zone_sno);
        $builder->where('use_yn', 'Y');
        return $builder->get()->getResultArray();
    }

    public function get_group_info($group_sno)
    {
        $builder = $this->db->table('tb_locker_group');
        $builder->where('group_sno', $group_sno);
        return $builder->get()->getRowArray();
    }

    // 정면도 관련
    public function insert_front($data)
    {
        $builder = $this->db->table('tb_locker_front');
        return $builder->insert($data);
    }

    public function update_front($front_sno, $data)
    {
        $builder = $this->db->table('tb_locker_front');
        $builder->where('front_sno', $front_sno);
        return $builder->update($data);
    }

    public function get_front_info($group_sno)
    {
        $builder = $this->db->table('tb_locker_front');
        $builder->where('group_sno', $group_sno);
        $builder->where('use_yn', 'Y');
        return $builder->get()->getRowArray();
    }

    // 개별 락커 관련
    public function insert_locker($data)
    {
        $builder = $this->db->table('tb_locker');
        return $builder->insert($data);
    }

    public function insert_locker_batch($data)
    {
        $builder = $this->db->table('tb_locker');
        return $builder->insertBatch($data);
    }

    public function update_locker($locker_sno, $data)
    {
        $builder = $this->db->table('tb_locker');
        $builder->where('locker_sno', $locker_sno);
        return $builder->update($data);
    }

    public function get_locker_list($front_sno)
    {
        $builder = $this->db->table('tb_locker');
        $builder->where('front_sno', $front_sno);
        $builder->where('use_yn', 'Y');
        $builder->orderBy('locker_floor', 'ASC');
        $builder->orderBy('locker_row', 'ASC');
        $builder->orderBy('locker_col', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function get_locker_info($locker_sno)
    {
        $builder = $this->db->table('tb_locker');
        $builder->where('locker_sno', $locker_sno);
        return $builder->get()->getRowArray();
    }

    // ============================================
    // Locker4 호환 메서드들
    // ============================================
    
    /**
     * 락커 타입 목록 조회 (Locker4 호환)
     */
    public function getLockerTypes($comp_cd = '001', $bcoff_cd = '001')
    {
        $builder = $this->db->table('lockr_types');
        $builder->where('COMP_CD', $comp_cd);
        $builder->where('BCOFF_CD', $bcoff_cd);
        
        $result = $builder->get();
        $types = $result->getResultArray();
        
        // API 형식으로 변환
        $formattedTypes = [];
        foreach ($types as $type) {
            $formattedTypes[] = [
                'LOCKR_TYPE_CD' => $type['LOCKR_TYPE_CD'],
                'LOCKR_TYPE_NM' => $type['LOCKR_TYPE_NM'],
                'WIDTH' => intval($type['WIDTH']),
                'HEIGHT' => intval($type['HEIGHT']),
                'DEPTH' => intval($type['DEPTH']),
                'COLOR' => $type['COLOR']
            ];
        }
        
        return $formattedTypes;
    }

    /**
     * 락커 구역 목록 조회 (Locker4 호환)
     */
    public function getLockerZones($comp_cd = '001', $bcoff_cd = '001')
    {
        $builder = $this->db->table('lockr_area');
        $builder->where('COMP_CD', $comp_cd);
        $builder->where('BCOFF_CD', $bcoff_cd);
        
        $result = $builder->get();
        $zones = $result->getResultArray();
        
        // API 형식으로 변환
        $formattedZones = [];
        foreach ($zones as $zone) {
            $formattedZones[] = [
                'LOCKR_KND_CD' => $zone['LOCKR_KND_CD'],
                'LOCKR_KND_NM' => $zone['LOCKR_KND_NM'],
                'X' => intval($zone['X'] ?? 0),
                'Y' => intval($zone['Y'] ?? 0),
                'WIDTH' => intval($zone['WIDTH'] ?? 800),
                'HEIGHT' => intval($zone['HEIGHT'] ?? 600),
                'COLOR' => $zone['COLOR'] ?? '#e5e7eb'
            ];
        }
        
        return $formattedZones;
    }

    /**
     * 락커 목록 조회 (Locker4 호환)
     */
    public function getLockers($comp_cd = '001', $bcoff_cd = '001', $zone_id = null)
    {
        $builder = $this->db->table('lockrs');
        $builder->where('COMP_CD', $comp_cd);
        $builder->where('BCOFF_CD', $bcoff_cd);
        
        if ($zone_id) {
            $builder->where('LOCKR_KND', $zone_id);
        }
        
        $builder->orderBy('LOCKR_CD', 'ASC');
        
        $result = $builder->get();
        return $result->getResultArray();
    }
} 