<?php

namespace App\Models;

use CodeIgniter\Model;

class LockerGroupModel extends Model
{
    protected $table = 'tb_locker_group';
    protected $primaryKey = 'group_sno';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'zone_sno',
        'group_nm',
        'group_rows',
        'group_cols',
        'locker_width',
        'locker_height',
        'locker_depth',
        'locker_type',
        'total_count',
        'group_coords',
        'use_yn',
        'cre_id',
        'cre_datetm',
        'mod_id',
        'mod_datetm'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'cre_datetm';
    protected $updatedField = 'mod_datetm';

    protected $validationRules = [
        'zone_sno' => 'required|integer',
        'group_nm' => 'required|max_length[100]',
        'group_rows' => 'required|integer|greater_than[0]',
        'group_cols' => 'required|integer|greater_than[0]',
        'locker_width' => 'required|integer|greater_than[0]',
        'locker_height' => 'required|integer|greater_than[0]',
        'locker_depth' => 'required|integer|greater_than[0]'
    ];

    // 락커 그룹의 전체 락커 수를 계산
    public function getTotalLockers($group_sno)
    {
        $group = $this->find($group_sno);
        if ($group) {
            return $group['group_rows'] * $group['group_cols'] * $group['levels'];
        }
        return 0;
    }

    // 락커 그룹의 전체 크기를 계산 (cm)
    public function getGroupDimensions($group_sno)
    {
        $group = $this->find($group_sno);
        if ($group) {
            return [
                'width' => $group['group_cols'] * $group['locker_width'],
                'height' => $group['group_rows'] * $group['locker_height'],
                'total_height' => $group['levels'] * $group['level_height']
            ];
        }
        return null;
    }

    // 락커 그룹과 구역 정보를 함께 조회 (comp_cd, bcoff_cd 포함)
    public function getGroupWithZoneInfo($group_sno = null)
    {
        $builder = $this->db->table($this->table . ' lg')
            ->select('lg.*, lz.comp_cd, lz.bcoff_cd, lz.zone_nm, lz.floor_sno')
            ->join('tb_locker_zone lz', 'lg.zone_sno = lz.zone_sno');
            
        if ($group_sno) {
            $builder->where('lg.group_sno', $group_sno);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
    
    // 특정 구역의 락커 그룹들 조회 (comp_cd, bcoff_cd 포함)
    public function getGroupsByZone($zone_sno)
    {
        return $this->db->table($this->table . ' lg')
            ->select('lg.*, lz.comp_cd, lz.bcoff_cd, lz.zone_nm')
            ->join('tb_locker_zone lz', 'lg.zone_sno = lz.zone_sno')
            ->where('lg.zone_sno', $zone_sno)
            ->where('lg.use_yn', 'Y')
            ->get()
            ->getResultArray();
    }
} 