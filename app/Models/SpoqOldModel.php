<?php
namespace App\Models;

use CodeIgniter\Model;

class SpoQOldModel extends Model
{
	
    private $custom = [
        'DSN'      => '',
        'hostname' => '114.207.244.33',
        'username' => 'dc_SpoQplus',
        'password' => 'dck110104',
        'database' => 'dc_SpoQplus',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];
    
    private $db2;
    
    public function today_SpoQ_old_attd(array $data)
    {
        
        $db2 = \Config\Database::connect($this->custom);
        
        
        $sql = "SELECT COUNT(*) AS counter FROM spo_member_attend
                WHERE user_date > :ndate:
                AND user_id_select = :user_id_select:
                AND user_type = :user_type:
				";
        
        $query = $db2->query($sql, [
            'ndate' 	        => $data['ndate']
            ,'user_id_select' 	=> $data['user_id_select']
            ,'user_type' 	    => $data['user_type']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function today_SpoQ_old_attd_list(array $data)
    {
        $db2 = \Config\Database::connect($this->custom);
        
        $sql = "SELECT * FROM spo_member_attend
                WHERE user_date > '2024-11-05 00:00:00'
                AND user_id_select = 'jamesgymjs'
                AND user_type = 'MEMBER'
                ORDER BY user_date DESC LIMIT :limit_s:
				";
        
        $query = $db2->query($sql, [
            'ndate' 	        => $data['ndate']
            ,'user_id_select' 	=> $data['user_id_select']
            ,'user_type' 	    => $data['user_type']
            ,'limit_s' 	        => $data['limit_s']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
}