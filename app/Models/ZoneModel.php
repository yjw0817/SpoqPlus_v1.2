<?php

namespace App\Models;

use CodeIgniter\Model;

class ZoneModel extends Model
{
    protected $table = 'tb_locker_zone';
    protected $primaryKey = 'zone_sno';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'comp_cd',
        'bcoff_cd',
        'floor_sno',
        'zone_nm',
        'zone_gendr',
        'zone_coords',
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
        'comp_cd' => 'required|max_length[10]',
        'bcoff_cd' => 'required|max_length[10]',
        'floor_sno' => 'required|integer',
        'zone_nm' => 'required|max_length[100]',
        'zone_gendr' => 'required|max_length[1]|in_list[M,F,A]',
        'zone_coords' => 'required'
    ];

    // 성별에 따른 구역 색상 정의
    public static $genderColors = [
        'M' => '#ADD8E6', // 연한 파란색
        'F' => '#FFB6C1', // 연한 분홍색
        'A' => '#90EE90'  // 연한 초록색
    ];

    // 성별 코드에 따른 텍스트 정의
    public static $genderTexts = [
        'M' => '남성 전용',
        'F' => '여성 전용',
        'A' => '혼용'
    ];
} 