<?php

namespace App\Models;

use CodeIgniter\Model;

class FloorModel extends Model
{
    protected $table = 'tb_locker_floor';
    protected $primaryKey = 'floor_sno';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'comp_cd',
        'bcoff_cd',
        'floor_nm',
        'floor_ord',
        'floor_img',
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
        'floor_nm' => 'required|max_length[100]',
        'floor_ord' => 'required|integer',
        'floor_img' => 'required|max_length[255]'
    ];
} 