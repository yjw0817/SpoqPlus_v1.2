<?php
namespace App\Models;

use CodeIgniter\Model;

class NotiModel extends Model
{
    
    /**
     * 공지 정보를 가져온다.
     * @param array $data
     */
    public function get_notice(array $data)
    {
        $sql = "SELECT * FROM notice_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
                AND NOTI_DV = :noti_dv:
                AND NOTI_SNO = :noti_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'noti_dv'			=> $data['noti_dv']
            ,'noti_sno'			=> $data['noti_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 공지사항 리스트를 가져온다.
     * @param array $data
     */
    public function list_notice_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM notice_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
                AND NOTI_DV = :noti_dv:
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'noti_dv'			=> $data['noti_dv']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
        
    }
    
    /**
     * 공지사항 리스트를 가져온다.
     * @param array $data
     */
    public function list_notice(array $data)
    {
        $sql = "SELECT * FROM notice_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
                AND NOTI_DV = :noti_dv:
                ORDER BY CRE_DATETM DESC
                limit {$data['limit_s']} , {$data['limit_e']}
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'noti_dv'			=> $data['noti_dv']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
        
    }
	
    /**
     * 메모 insert
     * @param array $data
     */
    public function insert_notice(array $data)
    {
    	$sql = "INSERT notice_tbl SET
					COMP_CD		      = :comp_cd:
					,BCOFF_CD		  = :bcoff_cd:
                    ,NOTI_DV		  = :noti_dv:
                    ,NOTI_TITLE		  = :noti_title:
                    ,NOTI_CONTS		  = :noti_conts:
                    ,NOTI_S_DATE	  = :noti_s_date:
					,NOTI_E_DATE	  = :noti_e_date:
					,NOTI_STAT	      = :noti_stat:
					,NOTI_TOP		  = :noti_top:
                    ,NOTI_EXT_URL	  = :noti_ext_url:
					,CRE_ID			  = :cre_id:
					,CRE_DATETM		  = :cre_datetm:
					,MOD_ID			  = :mod_id:
					,MOD_DATETM		  = :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'comp_cd'			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    	        ,'noti_dv'			=> $data['noti_dv']
    			,'noti_title'		=> $data['noti_title']
    			,'noti_conts'		=> $data['noti_conts']
    			,'noti_s_date'		=> $data['noti_s_date']
    			,'noti_e_date'		=> $data['noti_e_date']
    			,'noti_stat'	    => $data['noti_stat']
    			,'noti_top'		    => $data['noti_top']
    	        ,'noti_ext_url'		=> $data['noti_ext_url']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 메모 modify
     * @param array $data
     */
    public function modify_notice(array $data)
    {
        $sql = "UPDATE notice_tbl SET
					COMP_CD		      = :comp_cd:
					,BCOFF_CD		  = :bcoff_cd:
                    ,NOTI_DV		  = :noti_dv:
                    ,NOTI_TITLE		  = :noti_title:
                    ,NOTI_CONTS		  = :noti_conts:
                    ,NOTI_S_DATE	  = :noti_s_date:
					,NOTI_E_DATE	  = :noti_e_date:
					,NOTI_STAT	      = :noti_stat:
					,NOTI_TOP		  = :noti_top:
                    ,NOTI_EXT_URL	  = :noti_ext_url:
					,MOD_ID			  = :mod_id:
					,MOD_DATETM		  = :mod_datetm:
                WHERE NOTI_SNO = :noti_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'noti_dv'			=> $data['noti_dv']
            ,'noti_title'		=> $data['noti_title']
            ,'noti_conts'		=> $data['noti_conts']
            ,'noti_s_date'		=> $data['noti_s_date']
            ,'noti_e_date'		=> $data['noti_e_date']
            ,'noti_stat'	    => $data['noti_stat']
            ,'noti_top'		    => $data['noti_top']
            ,'noti_ext_url'		=> $data['noti_ext_url']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
            ,'noti_sno'		    => $data['noti_sno']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    		
    
    
}