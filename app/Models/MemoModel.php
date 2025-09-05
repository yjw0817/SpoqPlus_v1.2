<?php
namespace App\Models;

use CodeIgniter\Model;

class MemoModel extends Model
{
    
    /**
     * 메모 일련번호를 사용하여 메모 수정 정보를 가져온다. (전체)
     * @param array $data
     */
    public function get_memo_info(array $data)
    {
        $sql = "SELECT * FROM memo_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEMO_MGMT_SNO = :memo_mgmt_sno:
                ORDER BY PRIO_SET DESC, CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'memo_mgmt_sno'	=> $data['memo_mgmt_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 일련번로를 이용하여 메모 리스트를 가져온다. (전체)
     * @param array $data
     */
    public function list_memo_mem_sno(array $data)
    {
        $sql = "SELECT * FROM memo_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_SNO = :mem_sno:
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 일련번로를 이용하여 메모 리스트를 가져온다.
     * @param array $data
     */
    public function get_memo_mem_sno_9(array $data)
    {
        $sql = "SELECT * FROM memo_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_SNO = :mem_sno:
                ORDER BY CRE_DATETM DESC
                LIMIT 9
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
        
    }
	
    /**
     * 메모 insert
     * @param array $data
     */
    public function insert_memo_mgmt(array $data)
    {
    	$sql = "INSERT memo_mgmt_tbl SET
					COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
                    ,MEM_SNO		= :mem_sno:
                    ,MEM_ID		    = :mem_id:
                    ,MEM_NM		    = :mem_nm:
					,PRIO_SET		= :prio_set:
					,MEMO_CONTS_DV	= :memo_conts_dv:
					,MEMO_CONTS		= :memo_conts:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'comp_cd'			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    			,'mem_sno'			=> $data['mem_sno']
    			,'mem_id'			=> $data['mem_id']
    			,'mem_nm'			=> $data['mem_nm']
    			,'prio_set'		    => $data['prio_set']
    			,'memo_conts_dv'	=> $data['memo_conts_dv']
    			,'memo_conts'		=> $data['memo_conts']
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
    public function modify_memo_mgmt(array $data)
    {
        $sql = "UPDATE memo_mgmt_tbl SET
					MEMO_CONTS		= :memo_conts:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE
                    COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
                    AND MEMO_MGMT_SNO = :memo_mgmt_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'memo_mgmt_sno'	=> $data['memo_mgmt_sno']
            ,'memo_conts'		=> $data['memo_conts']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 중요메모 설정 변경
     * @param array $data
     */
    public function modify_memo_prio_set(array $data)
    {
        $sql = "UPDATE memo_mgmt_tbl SET
					PRIO_SET		= :prio_set:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE
                    COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
                    AND MEMO_MGMT_SNO = :memo_mgmt_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'memo_mgmt_sno'	=> $data['memo_mgmt_sno']
            ,'prio_set'		    => $data['prio_set']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    		
    
    
}