<?php
namespace App\Models;

use CodeIgniter\Model;

class TransModel extends Model
{
    /**
     * 양수 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_transm_mgmt_for_assgn_buy_sno(array $data)
    {
        $sql = "SELECT * FROM transm_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND ASSGN_BUY_EVENT_SNO = :assgn_buy_event_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'assgn_buy_event_sno'      => $data['assgn_buy_event_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 양도 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_transm_mgmt_for_buy_sno(array $data)
    {
        $sql = "SELECT * FROM transm_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND BUY_EVENT_SNO = :buy_event_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'buy_event_sno'            => $data['buy_event_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
	
    private function list_transm_mgmt_where(array $data)
    {
        $add_where = "";
        
        // 양도자
        if (isset($data['stnm']))
        {
            if ($data['stnm'] != '')
            {
                $add_where .= " AND (A.TRANSM_NM LIKE '%{$data['stnm']}%' OR A.TRANSM_ID LIKE '%{$data['stnm']}%') ";
            }
        }
        
        // 양수자
        if (isset($data['sanm']))
        {
            if ($data['sanm'] != '')
            {
                $add_where .= " AND (A.ASSGN_NM LIKE '%{$data['sanm']}%' OR A.ASSGN_ID LIKE '%{$data['sanm']}%' ) ";
            }
        }
        
        // 상품명
        if (isset($data['senm']))
        {
            if ($data['senm'] != '')
            {
                $add_where .= " AND A.SELL_EVENT_NM LIKE '%{$data['senm']}%' ";
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
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 양도 남은금액 총합계를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_transm_mgmt_sum_cost(array $data)
    {
        $add_where = $this->list_transm_mgmt_where($data);
        $sql = "SELECT SUM(LEFT_AMT) as sum_cost FROM transm_mgmt_tbl A
                WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'                   => $data['comp_cd']
                    ,'bcoff_cd'                 => $data['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['sum_cost'];
    }
    
	/**
	 * 양도 리스트를 가져온다.
	 * @param array $data
	 * @return array
	 */
	public function list_transm_mgmt_count(array $data)
	{
	    $add_where = $this->list_transm_mgmt_where($data);
		$sql = "SELECT COUNT(*) as counter FROM transm_mgmt_tbl A
                WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'                   => $data['comp_cd']
				,'bcoff_cd'                 => $data['bcoff_cd']
		]);
		
		$count = $query->getResultArray();
		return $count[0]['counter'];
	}
	
    
    /**
     * 양도 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_transm_mgmt(array $data)
    {
        $add_where = $this->list_transm_mgmt_where($data);
        $sql = "select DISTINCT A.*, M.MEM_MAIN_IMG AS S_MEM_MAIN_IMG, M.MEM_THUMB_IMG AS S_MEM_THUMB_IMG, M.MEM_GENDR AS S_MEM_GENDR, N.MEM_MAIN_IMG AS T_MEM_MAIN_IMG, N.MEM_THUMB_IMG AS T_MEM_THUMB_IMG, N.MEM_GENDR AS T_MEM_GENDR
                from transm_mgmt_tbl A  LEFT JOIN mem_info_detl_tbl AS M ON M.MEM_SNO = A.TRANSM_SNO LEFT JOIN mem_info_detl_tbl AS N ON N.MEM_SNO = A.ASSGN_SNO
                WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY A.CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
	/**
     * 양도, 양수자의 회원 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_mem_info_detl(array $data)
    {
        $sql = "SELECT * FROM mem_info_detl_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 구매상품에서 구매상품 일련번호를 이용하여 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_buy_event(array $data)
    {
        $sql = "SELECT * FROM buy_event_mgmt_tbl
                WHERE COMP_CD       = :comp_cd:
                AND BCOFF_CD        = :bcoff_cd:
                AND BUY_EVENT_SNO   = :buy_event_sno:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'buy_event_sno'	=> $data['buy_event_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 양도관리 내역에 저장한다.
     * @param array $data
     */
    public function insert_transm_mgmt(array $data)
    {
        $sql = "INSERT transm_mgmt_tbl SET
					BUY_EVENT_SNO               = :buy_event_sno:
                    ,SELL_EVENT_SNO             = :sell_event_sno:
                    ,SEND_EVENT_SNO             = :send_event_sno:
                    ,SELL_EVENT_NM              = :sell_event_nm:
					,ASSGN_BUY_EVENT_SNO        = :assgn_buy_event_sno:
                    ,ASSGN_SELL_EVENT_NM        = :assgn_sell_event_nm:
                    ,COMP_CD                    = :comp_cd:
					,BCOFF_CD                   = :bcoff_cd:
                    ,1RD_CATE_CD		        = :1rd_cate_cd:
					,2RD_CATE_CD                = :2rd_cate_cd:
					,TRANSM_SNO                  = :transm_sno:
                    ,TRANSM_ID                  = :transm_id:
                    ,TRANSM_NM                  = :transm_nm:
					,ASSGN_SNO		            = :assgn_sno:
                    ,ASSGN_ID		            = :assgn_id:
                    ,ASSGN_NM		            = :assgn_nm:
					,TRANSM_USE_DAY		        = :transm_use_day:
                    ,TRANSM_LEFT_DAY            = :transm_left_day:
					,USE_AMT                    = :use_amt:
					,LEFT_AMT		            = :left_amt:
					,ASSGN_EXR_S_DATE		    = :assgn_exr_s_date:
					,TRANSM_ACC_RTRCT_DV		= :transm_acc_rtrct_dv:
					,TRANSM_ACC_RTRCT_MTHD		= :transm_acc_rtrct_mthd:
					,ASSGN_ACC_RTRCT_DV		    = :assgn_acc_rtrct_dv:
					,ASSGN_ACC_RTRCT_MTHD		= :assgn_acc_rtrct_mthd:
					,REAL_TRANSM_DAY			= :real_transm_day:
					,REAL_TRANSM_CNT		    = :real_transm_cnt:
					,TRANSM_CRE_DATE		    = :transm_cre_date:
					,TRANSM_STAT		        = :transm_stat:
					,CRE_ID		                = :cre_id:
					,CRE_DATETM		            = :cre_datetm:
					,MOD_ID		                = :mod_id:
					,MOD_DATETM		            = :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 	       => $data['buy_event_sno']
            ,'sell_event_sno'	       => $data['sell_event_sno']
            ,'send_event_sno'	       => $data['send_event_sno']
            ,'sell_event_nm'	       => $data['sell_event_nm']
            ,'assgn_buy_event_sno'	   => $data['assgn_buy_event_sno']
            ,'assgn_sell_event_nm'	   => $data['assgn_sell_event_nm']
            ,'comp_cd'			       => $data['comp_cd']
            ,'bcoff_cd'			       => $data['bcoff_cd']
            ,'1rd_cate_cd'		       => $data['1rd_cate_cd']
            ,'2rd_cate_cd'		       => $data['2rd_cate_cd']
            ,'transm_sno'		       => $data['transm_sno']
            ,'transm_id'		       => $data['transm_id']
            ,'transm_nm'		       => $data['transm_nm']
            ,'assgn_sno'		       => $data['assgn_sno']
            ,'assgn_id'		           => $data['assgn_id']
            ,'assgn_nm'		           => $data['assgn_nm']
            ,'transm_use_day'		   => $data['transm_use_day']
            ,'transm_left_day'		   => $data['transm_left_day']
            ,'use_amt'		           => $data['use_amt']
            ,'left_amt'		           => $data['left_amt']
            ,'assgn_exr_s_date'		   => $data['assgn_exr_s_date']
            ,'transm_acc_rtrct_dv'	   => $data['transm_acc_rtrct_dv']
            ,'transm_acc_rtrct_mthd'   => $data['transm_acc_rtrct_mthd']
            ,'assgn_acc_rtrct_dv'	   => $data['assgn_acc_rtrct_dv']
            ,'assgn_acc_rtrct_mthd'    => $data['assgn_acc_rtrct_mthd']
            ,'real_transm_day'		   => $data['real_transm_day']
            ,'real_transm_cnt'		   => $data['real_transm_cnt']
            ,'transm_cre_date'	       => $data['transm_cre_date']
            ,'transm_stat'			   => $data['transm_stat']
            ,'cre_id'		           => $data['cre_id']
            ,'cre_datetm'	           => $data['cre_datetm']
            ,'mod_id'	               => $data['mod_id']
            ,'mod_datetm'			   => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
}