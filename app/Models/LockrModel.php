<?php
namespace App\Models;

use CodeIgniter\Model;

class LockrModel extends Model
{
    
    /**
     * 라커룸 시작,번호를 이용하여 이용중인지 체크 [counter]
     * @param array $data
     * @return array
     */
    public function chk_lockr_create_count(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM lockr_room
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND LOCKR_KND = :lockr_knd:
                AND LOCKR_GENDR_SET = :lockr_gendr_set:
                AND LOCKR_NO BETWEEN :lockr_s_no: AND :lockr_e_no:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'lockr_knd'				=> $data['lockr_knd']
            ,'lockr_gendr_set'				=> $data['lockr_gendr_set']
            ,'lockr_s_no'				=> $data['lockr_s_no']
            ,'lockr_e_no'				=> $data['lockr_e_no']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 라커룸 시작,번호를 이용하여 이용중인지 체크 [counter]
     * @param array $data
     * @return array
     */
    public function lockr_use_count(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM lockr_room
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND LOCKR_KND = :lockr_knd:
                AND LOCKR_GENDR_SET = :lockr_gendr_set:
                AND LOCKR_NO BETWEEN :lockr_s_no: AND :lockr_e_no:
                AND LOCKR_STAT != '00';
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'lockr_knd'				=> $data['lockr_knd']
            ,'lockr_gendr_set'				=> $data['lockr_gendr_set']
            ,'lockr_s_no'				=> $data['lockr_s_no']
            ,'lockr_e_no'				=> $data['lockr_e_no']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 라커번호를 삭제한다.
     * @param array $data
     * @return array
     */
    public function del_lockr_room(array $data)
    {
        $sql = "DELETE FROM lockr_room
				WHERE COMP_CD   = :comp_cd:
				AND BCOFF_CD    = :bcoff_cd:
				AND LOCKR_KND   = :lockr_knd:
                AND LOCKR_GENDR_SET   = :lockr_gendr_set:
                AND LOCKR_NO BETWEEN :lockr_s_no: AND :lockr_e_no:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'lockr_knd'				=> $data['lockr_knd']
            ,'lockr_gendr_set'				=> $data['lockr_gendr_set']
            ,'lockr_s_no'				=> $data['lockr_s_no']
            ,'lockr_e_no'				=> $data['lockr_e_no']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 라커번호 설정를 삭제한다.
     * @param array $data
     * @return array
     */
    public function del_lockr_room_set(array $data)
    {
        $sql = "DELETE FROM lockr_set_hist_tbl
				WHERE COMP_CD   = :comp_cd:
				AND BCOFF_CD    = :bcoff_cd:
                AND LOCKR_KND   = :lockr_knd:
                AND LOCKR_GENDR_SET   = :lockr_gendr_set:
                AND LOCKR_SET_HIST_SNO   = :lockr_set_hist_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'lockr_knd'			=> $data['lockr_knd']
            ,'lockr_gendr_set'		=> $data['lockr_gendr_set']
            ,'lockr_set_hist_sno'	=> $data['lockr_set_hist_sno']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    
    /**
     * 라커번호에 대한 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_lockr_room_no(array $data)
    {
        $sql = "SELECT * FROM lockr_room
				WHERE COMP_CD   = :comp_cd:
				AND BCOFF_CD    = :bcoff_cd:
				AND LOCKR_KND   = :lockr_knd:
                AND LOCKR_GENDR_SET   = :lockr_gendr_set:
                AND LOCKR_NO    = :lockr_no:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'lockr_knd'			=> $data['lockr_knd']
            ,'lockr_gendr_set'		=> $data['lockr_gendr_set']
            ,'lockr_no'             => $data['lockr_no']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 이미 이용중인 락커 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_lockr_room(array $data)
    {
        $sql = "SELECT * FROM lockr_room
				WHERE COMP_CD   = :comp_cd:
				AND BCOFF_CD    = :bcoff_cd:
				AND LOCKR_KND   = :lockr_knd:
                AND MEM_SNO     = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'lockr_knd'			=> $data['lockr_knd']
            ,'mem_sno'              => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
	
	/**
	 * 락커에서 각 단위별 락커 이용여부 리스트를 구한다.
	 * @param array $data
	 * @return array
	 */
	public function list_lockr_range(array $data)
	{
		$sql = "SELECT * FROM lockr_room
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND LOCKR_KND = :lockr_knd:
				AND LOCKR_GENDR_SET = :lockr_gendr_set:
				AND LOCKR_NO BETWEEN :lockr_min: AND :lockr_max:
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'lockr_knd'			=> $data['lockr_knd']
				,'lockr_gendr_set'		=> $data['lockr_gendr_set']
				,'lockr_min'			=> $data['lockr_min']
				,'lockr_max'			=> $data['lockr_max']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	/**
	 * 락커 100단위 범위의 이용가능한 락커 갯수를 구한다.
	 * @param array $data
	 * @return array
	 */
	public function count_lockr_range(array $data)
	{
		$sql = "SELECT COUNT(LOCKR_STAT) AS counter FROM lockr_room
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND LOCKR_KND = :lockr_knd:
				AND LOCKR_GENDR_SET = :lockr_gendr_set:
				AND LOCKR_STAT = '00'
				AND LOCKR_NO BETWEEN :lockr_min: AND :lockr_max:
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'lockr_knd'			=> $data['lockr_knd']
				,'lockr_gendr_set'		=> $data['lockr_gendr_set']
				,'lockr_min'			=> $data['lockr_min']
				,'lockr_max'			=> $data['lockr_max']
		]);
		
		$count = $query->getResultArray();
		return $count[0]['counter'];
	}
	
	/**
	 * 락커 셋팅 정보에서 최소값과 최고값을 구한다.
	 * @param array $data
	 * @return array
	 */
	public function select_lockr_minmax(array $data)
	{
		$sql = "SELECT MIN(LOCKR_S_NO) AS min , MAX(LOCKR_E_NO) AS max FROM lockr_set_hist_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND LOCKR_KND = :lockr_knd:
				AND LOCKR_GENDR_SET = :lockr_gendr_set:
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'lockr_knd'			=> $data['lockr_knd']
				,'lockr_gendr_set'		=> $data['lockr_gendr_set']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	
	/**
	 * 락커 셋팅 정보 select
	 * @param array $data
	 * @return array
	 */
	public function select_lockr_room(array $data)
	{
		$sql = "SELECT * FROM lockr_room
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND LOCKR_KND = :lockr_knd:
				AND MEM_SNO = :mem_sno:
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'lockr_knd'			=> $data['lockr_knd']
				,'mem_sno'				=> $data['mem_sno']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	/**
	 * 락커 셋팅 정보 select
	 * @param array $data
	 * @return array
	 */
	public function select_lockr_set_hist(array $data)
	{
		$sql = "SELECT * FROM lockr_set_hist_tbl
				WHERE COMP_CD 		= :comp_cd:
				AND BCOFF_CD 		= :bcoff_cd:
				AND LOCKR_KND 		= :lockr_knd:
				AND LOCKR_GENDR_SET = :lockr_gendr_set:
				ORDER BY LOCKR_S_NO ASC
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'lockr_knd'			=> $data['lockr_knd']
				,'lockr_gendr_set'		=> $data['lockr_gendr_set']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	/**
	 * 락커 생성 insert
	 * @param array $data
	 */
	public function insert_lockr(array $data)
	{
		$sql = "INSERT lockr_set_hist_tbl SET
					COMP_CD		    	= :comp_cd:
					,BCOFF_CD		    = :bcoff_cd:
					,LOCKR_KND		   	= :lockr_knd:
					,LOCKR_GENDR_SET	= :lockr_gendr_set:
					,LOCKR_S_NO		    = :lockr_s_no:
					,LOCKR_E_NO		    = :lockr_e_no:
					,LOCKR_DISP_FMT		= :lockr_disp_fmt:
					,SET_HIST_STAT		= :set_hist_stat:
					,CRE_ID		    	= :cre_id:
					,CRE_DATETM		    = :cre_datetm:
					,MOD_ID		    	= :mod_id:
					,MOD_DATETM		    = :mod_datetm:
				";
		$query = $this->db->query($sql, [
				'comp_cd' 			=> $data['comp_cd']
				,'bcoff_cd'			=> $data['bcoff_cd']
				,'lockr_knd'		=> $data['lockr_knd']
				,'lockr_gendr_set'	=> $data['lockr_gendr_set']
				,'lockr_s_no'		=> $data['lockr_s_no']
				,'lockr_e_no'		=> $data['lockr_e_no']
				,'lockr_disp_fmt'	=> $data['lockr_disp_fmt']
				,'set_hist_stat'	=> $data['set_hist_stat']
				,'cre_id'			=> $data['cre_id']
				,'cre_datetm'		=> $data['cre_datetm']
				,'mod_id'			=> $data['mod_id']
				,'mod_datetm'		=> $data['mod_datetm']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function insert_lockr_room(array $data)
	{
		$sql = "INSERT lockr_room SET
					COMP_CD		    	= :comp_cd:
					,BCOFF_CD		    = :bcoff_cd:
					,LOCKR_KND		   	= :lockr_knd:
					,LOCKR_GENDR_SET	= :lockr_gendr_set:
					,LOCKR_NO		    = :lockr_no:
					,BUY_EVENT_SNO		= :buy_event_sno:
					,MEM_SNO			= :mem_sno:
					,MEM_NM				= :mem_nm:
					,LOCKR_USE_S_DATE	= :lockr_use_s_date:
					,LOCKR_USE_E_DATE	= :lockr_use_e_date:
					,LOCKR_STAT			= :lockr_stat:
					,SET_MAIN_SNO		= :set_main_sno:
				";
		$query = $this->db->query($sql, [
				'comp_cd' 			=> $data['comp_cd']
				,'bcoff_cd'			=> $data['bcoff_cd']
				,'lockr_knd'		=> $data['lockr_knd']
				,'lockr_gendr_set'	=> $data['lockr_gendr_set']
				,'lockr_no'			=> $data['lockr_no']
				,'buy_event_sno'	=> $data['buy_event_sno']
				,'mem_sno'			=> $data['mem_sno']
				,'mem_nm'			=> $data['mem_nm']
				,'lockr_use_s_date'	=> $data['lockr_use_s_date']
				,'lockr_use_e_date'	=> $data['lockr_use_e_date']
				,'lockr_stat'		=> $data['lockr_stat']
				,'set_main_sno'		=> $data['set_main_sno']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	/**
	 * 라커 업데이트 ( 고장 , 고장해제 , 비우기, 이동 )
	 * @param array $data
	 */
	public function update_lockr_room_stat(array $data)
	{
	    $sql = "UPDATE lockr_room SET
					BUY_EVENT_SNO		= :buy_event_sno:
					,MEM_SNO			= :mem_sno:
					,MEM_NM				= :mem_nm:
					,LOCKR_USE_S_DATE	= :lockr_use_s_date:
					,LOCKR_USE_E_DATE	= :lockr_use_e_date:
					,LOCKR_STAT			= :lockr_stat:
                    ,LOCKR_NO			= :lockr_no:
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND LOCKR_KND = :lockr_knd:
                AND LOCKR_GENDR_SET = :lockr_gendr_set:
                AND LOCKR_NO = :lockr_no:
				";
	    $query = $this->db->query($sql, [
	        'comp_cd'			 => $data['comp_cd']
	        ,'bcoff_cd'			 => $data['bcoff_cd']
	        ,'lockr_knd'		 => $data['lockr_knd']
	        ,'lockr_gendr_set'	 => $data['lockr_gendr_set']
	        ,'lockr_no'          => $data['lockr_no']
	        ,'lockr_stat'        => $data['lockr_stat']
	        ,'buy_event_sno'     => $data['buy_event_sno']
	        ,'mem_sno'           => $data['mem_sno']
	        ,'mem_nm'            => $data['mem_nm']
	        ,'lockr_use_s_date'  => $data['lockr_use_s_date']
	        ,'lockr_use_e_date'  => $data['lockr_use_e_date']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * 번호 이동시 구매상품의 라커 번호를 업데이트 한다.
	 * @param array $data
	 */
	public function update_buy_event_lockr_no(array $data)
	{
	    $sql = "UPDATE buy_event_mgmt_tbl SET
					LOCKR_NO		= :lockr_no:
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND BUY_EVENT_SNO = :buy_event_sno:
				";
	    $query = $this->db->query($sql, [
	        'comp_cd'			 => $data['comp_cd']
	        ,'bcoff_cd'			 => $data['bcoff_cd']
	        ,'buy_event_sno'	 => $data['buy_event_sno']
	        ,'lockr_no'          => $data['lockr_no']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
    
}