<?php
namespace App\Models;

use CodeIgniter\Model;

class DomcyModel extends Model
{
    
    private function list_domcy_mgmt_where(array $data)
    {
        $add_where = "";
        
        // 회원명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND A.MEM_NM LIKE '%{$data['snm']}%' ";
            }
        }
        
        // 휴회신청상태
        if (isset($data['sdstat']))
        {
            if ($data['sdstat'] != '')
            {
                $add_where .= " AND A.DOMCY_MGMT_STAT = '{$data['sdstat']}' ";
            }
        }
        
        // 날짜검색조건
        if (isset($data['sdcon']))
        {
            if ($data['sdcon'] != '')
            {
                $sdate = isset($data['sdate']) ? trim($data['sdate']) : '';
                $edate = isset($data['edate']) ? trim($data['edate']) : '';
                
                // 시작일과 종료일이 모두 있는 경우
                if ($sdate != '' && $edate != '')
                {
                    $sdate_full = $sdate . " 00:00:00";
                    $edate_full = $edate . " 23:59:59";
                    
                    switch ($data['sdcon'])
                    {
                        case "sc":
                            $add_where .= " AND A.CRE_DATETM BETWEEN '{$sdate_full}' AND '{$edate_full}' ";
                            break;
                        case "ss":
                            $add_where .= " AND A.DOMCY_S_DATE BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "se":
                            $add_where .= " AND A.DOMCY_E_DATE BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
                // 시작일만 있는 경우
                else if ($sdate != '' && $edate == '')
                {
                    $sdate_full = $sdate . " 00:00:00";
                    
                    switch ($data['sdcon'])
                    {
                        case "sc":
                            $add_where .= " AND A.CRE_DATETM >= '{$sdate_full}' ";
                            break;
                        case "ss":
                            $add_where .= " AND A.DOMCY_S_DATE >= '{$sdate}' ";
                            break;
                        case "se":
                            $add_where .= " AND A.DOMCY_E_DATE >= '{$sdate}' ";
                            break;
                    }
                }
                // 종료일만 있는 경우
                else if ($sdate == '' && $edate != '')
                {
                    $edate_full = $edate . " 23:59:59";
                    
                    switch ($data['sdcon'])
                    {
                        case "sc":
                            $add_where .= " AND A.CRE_DATETM <= '{$edate_full}' ";
                            break;
                        case "ss":
                            $add_where .= " AND A.DOMCY_S_DATE <= '{$edate}' ";
                            break;
                        case "se":
                            $add_where .= " AND A.DOMCY_E_DATE <= '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
	/**
	 * 휴회중 출석처리를 위한 휴회 목록을 가져온다.
	 * 휴회중인 리스트만 가져와야한다.
	 * @param array $data
	 */
	public function list_domcy_mgmt_count(array $data)
	{
	    $add_where = $this->list_domcy_mgmt_where($data);
		$sql = "SELECT COUNT(*) as counter FROM domcy_mgmt_tbl A
				WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'	=> $data['comp_cd']
				,'bcoff_cd'	=> $data['bcoff_cd']
		]);
		
		$count = $query->getResultArray();
		return $count[0]['counter'];
	}
	
	
    /**
     * 휴회중 출석처리를 위한 휴회 목록을 가져온다.
     * 휴회중인 리스트만 가져와야한다.
     * @param array $data
     */
    public function list_domcy_mgmt(array $data)
    {
        $add_where = $this->list_domcy_mgmt_where($data);
        $sql = "SELECT A.*, M.MEM_MAIN_IMG, M.MEM_THUMB_IMG, M.MEM_GENDR
                FROM domcy_mgmt_tbl A   LEFT JOIN mem_info_detl_tbl AS M ON M.MEM_SNO = A.MEM_SNO
				WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY A.CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'	=> $data['comp_cd']
            ,'bcoff_cd'	=> $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 휴회 신청 리스트 대해서 종료 처리 업데이트를 수행한다. (중간출석)
     * @param array $data
     * @return array
     */
    public function update_domcy_end_attd(array $data)
    {
        $sql = "UPDATE domcy_mgmt_tbl SET
				    DOMCY_MGMT_STAT		  = :domcy_mgmt_stat:
                    ,MOD_ID               = :mod_id:
                    ,MOD_DATETM           = :mod_datetm:
                    ,DOMCY_E_DATE         = :domcy_e_date:
                WHERE DOMCY_MGMT_SNO      = :domcy_mgmt_sno:
				";
        $query = $this->db->query($sql, [
            'domcy_mgmt_sno' 		    => $data['domcy_mgmt_sno']
            ,'domcy_e_date' 		    => $data['domcy_e_date']
            ,'domcy_mgmt_stat'			=> $data['domcy_mgmt_stat']
            ,'mod_id'			        => $data['mod_id']
            ,'mod_datetm'		        => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 휴회 신청 리스트 대해서 종료 처리 업데이트를 수행한다.
     * @param array $data
     * @return array
     */
    public function update_domcy_end(array $data)
    {
        $sql = "UPDATE domcy_mgmt_tbl SET
				    DOMCY_MGMT_STAT		  = :domcy_mgmt_stat:
                    ,MOD_ID               = :mod_id:
                    ,MOD_DATETM           = :mod_datetm:
                WHERE DOMCY_E_DATE        = :domcy_e_date:
				";
        $query = $this->db->query($sql, [
            'domcy_e_date' 		        => $data['domcy_e_date']
            ,'domcy_mgmt_stat'			=> $data['domcy_mgmt_stat']
            ,'mod_id'			        => $data['mod_id']
            ,'mod_datetm'		        => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 휴회 세부 상품들에 대해서 종료 처리 업데이트를 수행한다.
     * @param array $data
     * @return array
     */
    public function update_domcy_hist_end(array $data)
    {
        $sql = "UPDATE domcy_mgmt_hist_tbl SET
				    DOMCY_MGMT_STAT		  = :domcy_mgmt_stat:
                    ,MOD_ID               = :mod_id:
                    ,MOD_DATETM           = :mod_datetm:
                WHERE DOMCY_REAL_E_DATE   = :domcy_real_e_date:
				";
        $query = $this->db->query($sql, [
            'domcy_real_e_date' 		=> $data['domcy_real_e_date']
            ,'domcy_mgmt_stat'			=> $data['domcy_mgmt_stat']
            ,'mod_id'			        => $data['mod_id']
            ,'mod_datetm'		        => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 휴회중 출석처리를 위한 휴회 목록을 가져온다.
     * 휴회중인 리스트만 가져와야한다.
     * @param array $data
     */
    public function get_domcy_attd(array $data)
    {
        $sql = "SELECT * FROM domcy_mgmt_hist_tbl
				WHERE MEM_SNO = :mem_sno:
                AND DOMCY_MGMT_STAT = '01'
				";
        
        $query = $this->db->query($sql, [
            'mem_sno'	=> $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
	/**
	 * 해당날짜에 대한 휴회을 실행해야할 목록을 가져온다.
	 * @param array $data
	 */
	public function run_list_domcy(array $data)
	{
		$sql = "SELECT * FROM domcy_mgmt_tbl
				WHERE DOMCY_S_DATE = :domcy_s_date:
				";
		
		$query = $this->db->query($sql, [
				'domcy_s_date'	=> $data['domcy_s_date']
		]);
		
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	
	/**
	 * 휴회 신청을 insert 한다.
	 * @param array $data
	 * @return array
	 */
	public function insert_domcy_mgmt(array $data)
	{
		$sql = "INSERT domcy_mgmt_tbl SET
					DOMCY_APLY_BUY_SNO	= :domcy_aply_buy_sno:
					,COMP_CD		    = :comp_cd:
					,BCOFF_CD		   	= :bcoff_cd:
					,MEM_SNO			= :mem_sno:
					,MEM_ID				= :mem_id:
					,MEM_NM				= :mem_nm:
					,DOMCY_S_DATE		= :domcy_s_date:
					,DOMCY_E_DATE		= :domcy_e_date:
					,DOMCY_USE_DAY		= :domcy_use_day:
					,DOMCY_MGMT_STAT	= :domcy_mgmt_stat:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
				";
				
		$query = $this->db->query($sql, [
				'domcy_aply_buy_sno' => $data['domcy_aply_buy_sno']
				,'comp_cd'			 => $data['comp_cd']
				,'bcoff_cd'			 => $data['bcoff_cd']
				,'mem_sno'			 => $data['mem_sno']
				,'mem_id'			 => $data['mem_id']
				,'mem_nm'			 => $data['mem_nm']
				,'domcy_s_date'		 => $data['domcy_s_date']
				,'domcy_e_date'		 => $data['domcy_e_date']
				,'domcy_use_day'	 => $data['domcy_use_day']
				,'domcy_mgmt_stat'	 => $data['domcy_mgmt_stat']
				,'cre_id'			 => $data['cre_id']
				,'cre_datetm'		 => $data['cre_datetm']
				,'mod_id'			 => $data['mod_id']
				,'mod_datetm'		 => $data['mod_datetm']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function update_domcy_mgmt_hist(array $data)
	{
	    $sql = "UPDATE domcy_mgmt_hist_tbl SET
				    USE_AF_DOMCY_DAY		= :use_af_domcy_day:
					,DOMCY_REAL_USE_DAY		= :domcy_real_use_day:
					,DOMCY_REAL_E_DATE		= :domcy_real_e_date:
					,USE_AF_EXR_E_DATE		= :use_af_exr_e_date:
                    ,DOMCY_MGMT_STAT        = :domcy_mgmt_stat:
					,MOD_ID					= :mod_id:
					,MOD_DATETM				= :mod_datetm:
                WHERE DOMCY_MGMT_HIST_SNO   = :domcy_mgmt_hist_sno:
				";
	    $query = $this->db->query($sql, [
	        'domcy_mgmt_hist_sno' 		=> $data['domcy_mgmt_hist_sno']
	        ,'use_af_domcy_day'	        => $data['use_af_domcy_day']
	        ,'domcy_real_use_day' 	    => $data['domcy_real_use_day']
	        ,'domcy_real_e_date'	    => $data['domcy_real_e_date']
	        ,'use_af_exr_e_date'		=> $data['use_af_exr_e_date']
	        ,'domcy_mgmt_stat'		    => $data['domcy_mgmt_stat']
	        ,'mod_id'			        => $data['mod_id']
	        ,'mod_datetm'		        => $data['mod_datetm']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * 휴회 적용 상품을 insert 한다.
	 * @param array $data
	 * @return array
	 */
	public function insert_domcy_mgmt_hist(array $data)
	{
		$sql = "INSERT domcy_mgmt_hist_tbl SET
					DOMCY_MGMT_SNO			= :domcy_mgmt_sno:
					,BUY_EVENT_SNO		    = :buy_event_sno:
					,SELL_EVENT_SNO		   	= :sell_event_sno:
					,SEND_EVENT_SNO			= :send_event_sno:
					,COMP_CD				= :comp_cd:
					,BCOFF_CD				= :bcoff_cd:
					,1RD_CATE_CD			= :1rd_cate_cd:
					,2RD_CATE_CD			= :2rd_cate_cd:
                    ,MEM_SNO				= :mem_sno:
					,MEM_ID					= :mem_id:
                    ,MEM_NM					= :mem_nm:
                    ,SELL_EVENT_NM			= :sell_event_nm:
					,DOMCY_S_DATE			= :domcy_s_date:
					,DOMCY_E_DATE			= :domcy_e_date:
					,DOMCY_USE_DAY		    = :domcy_use_day:
					,USE_BF_DOMCY_DAY		= :use_bf_domcy_day:
					,USE_AF_DOMCY_DAY		= :use_af_domcy_day:
					,DOMCY_REAL_USE_DAY		= :domcy_real_use_day:
					,DOMCY_REAL_S_DATE		= :domcy_real_s_date:
					,DOMCY_REAL_E_DATE		= :domcy_real_e_date:
					,USE_BF_DOMCY_CNT		= :use_bf_domcy_cnt:
					,USE_BF_EXR_S_DATE		= :use_bf_exr_s_date:
					,USE_BF_EXR_E_DATE		= :use_bf_exr_e_date:
					,USE_AF_EXR_S_DATE		= :use_af_exr_s_date:
					,USE_AF_EXR_E_DATE		= :use_af_exr_e_date:
					,DOMCY_MGMT_STAT		= :domcy_mgmt_stat:
					,GRP_CATE_SET			= :grp_cate_set:
					,LOCKR_SET				= :lockr_set:
					,LOCKR_KND				= :lockr_knd:
					,LOCKR_GENDR_SET		= :lockr_gendr_set:
					,LOCKR_NO				= :lockr_no:
					,CRE_ID					= :cre_id:
					,CRE_DATETM				= :cre_datetm:
					,MOD_ID					= :mod_id:
					,MOD_DATETM				= :mod_datetm:
				";
		$query = $this->db->query($sql, [
				'domcy_mgmt_sno' 		=> $data['domcy_mgmt_sno']
				,'buy_event_sno'		=> $data['buy_event_sno']
				,'sell_event_sno'		=> $data['sell_event_sno']
				,'send_event_sno'		=> $data['send_event_sno']
				,'comp_cd'		 		=> $data['comp_cd']
				,'bcoff_cd'		 		=> $data['bcoff_cd']
				,'1rd_cate_cd'	 		=> $data['1rd_cate_cd']
				,'2rd_cate_cd'	 		=> $data['2rd_cate_cd']
		        ,'mem_sno'			 	=> $data['mem_sno']
		        ,'mem_id'			 	=> $data['mem_id']
		        ,'mem_nm'			 	=> $data['mem_nm']
				,'sell_event_nm'		=> $data['sell_event_nm']
				,'domcy_s_date'		 	=> $data['domcy_s_date']
				,'domcy_e_date' 		=> $data['domcy_e_date']
				,'domcy_use_day'	  	=> $data['domcy_use_day']
				,'use_bf_domcy_day'	  	=> $data['use_bf_domcy_day']
				,'use_af_domcy_day'	  	=> $data['use_af_domcy_day']
				,'domcy_real_use_day' 	=> $data['domcy_real_use_day']
				,'domcy_real_s_date'	=> $data['domcy_real_s_date']
				,'domcy_real_e_date'	=> $data['domcy_real_e_date']
				,'use_bf_domcy_cnt'	 	=> $data['use_bf_domcy_cnt']
				,'use_bf_exr_s_date'	=> $data['use_bf_exr_s_date']
				,'use_bf_exr_e_date'	=> $data['use_bf_exr_e_date']
				,'use_af_exr_s_date' 	=> $data['use_af_exr_s_date']
				,'use_af_exr_e_date'	=> $data['use_af_exr_e_date']
				,'domcy_mgmt_stat'		=> $data['domcy_mgmt_stat']
				,'grp_cate_set'			=> $data['grp_cate_set']
				,'lockr_set'		 	=> $data['lockr_set']
				,'lockr_knd'		 	=> $data['lockr_knd']
				,'lockr_gendr_set'	 	=> $data['lockr_gendr_set']
				,'lockr_no'	 			=> $data['lockr_no']
				,'cre_id'			 => $data['cre_id']
				,'cre_datetm'		 => $data['cre_datetm']
				,'mod_id'			 => $data['mod_id']
				,'mod_datetm'		 => $data['mod_datetm']
		]);
		
		array_push($data,$query);
		return $data;
	}

	/**
	 * 회원의 휴회 신청 날짜 중복을 체크한다.
	 * 신청하려는 날짜가 기존 신청된 휴회 기간과 겹치는지 확인
	 * @param array $data
	 * @return array
	 */
	public function check_domcy_date_overlap(array $data)
	{
		$sql = "SELECT COUNT(*) as overlap_count 
				FROM domcy_mgmt_tbl 
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_SNO = :mem_sno:
				AND DOMCY_APLY_BUY_SNO = :domcy_aply_buy_sno:
				AND DOMCY_MGMT_STAT IN ('00', '01', '02')
				AND (
					(:domcy_s_date: BETWEEN DOMCY_S_DATE AND DOMCY_E_DATE)
					OR (:domcy_e_date: BETWEEN DOMCY_S_DATE AND DOMCY_E_DATE)
					OR (DOMCY_S_DATE BETWEEN :domcy_s_date: AND :domcy_e_date:)
					OR (DOMCY_E_DATE BETWEEN :domcy_s_date: AND :domcy_e_date:)
				)";
		
		$query = $this->db->query($sql, [
			'comp_cd' => $data['comp_cd'],
			'bcoff_cd' => $data['bcoff_cd'],
			'mem_sno' => $data['mem_sno'],
			'domcy_aply_buy_sno' => $data['domcy_aply_buy_sno'],
			'domcy_s_date' => $data['domcy_s_date'],
			'domcy_e_date' => $data['domcy_e_date']
		]);
		
		return $query->getResultArray();
	}
    
}