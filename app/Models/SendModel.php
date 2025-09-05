<?php
namespace App\Models;

use CodeIgniter\Model;

class SendModel extends Model
{
    
    
    /**
     * 보내기 상품의 상태를 업데이트한다. update
     * @param array $data
     */
    public function update_send_event_mgmt(array $data)
    {
        $sql = "UPDATE send_event_mgmt_tbl SET
					COMP_CD 		      = :comp_cd:
					,BCOFF_CD	          = :bcoff_cd:
                    ,SEND_STAT            = :send_stat: 
					,MOD_ID			      = :mod_id:
					,MOD_DATETM		      = :mod_datetm:
                WHERE SEND_EVENT_MGMT_SNO = :send_event_mgmt_sno:
				";
        $query = $this->db->query($sql, [
            'send_event_mgmt_sno'  => $data['send_event_mgmt_sno']
            ,'comp_cd'			   => $data['comp_cd']
            ,'bcoff_cd'		       => $data['bcoff_cd']
            ,'send_stat'		   => $data['send_stat']
            ,'mod_id'			   => $data['mod_id']
            ,'mod_datetm'		   => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
	 * 상품 보내기 정보 가져오기
	 * @param array $data
	 * @return array
	 */
	public function get_send_event_mgmt(array $data)
	{
		$sql = "SELECT * FROM send_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
				AND SEND_EVENT_MGMT_SNO = :send_event_mgmt_sno:
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'send_event_mgmt_sno'	=> $data['send_event_mgmt_sno']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	
	/**
	 * =================================================================================
	 * send_event_manage [상품 보내기 리스트] - START
	 * =================================================================================
	 */
	
	private function list_send_event_mgmt_where(array $data)
	{
	    $add_where = "";
	    
	    // 회원명
	    if (isset($data['snm']))
	    {
	        if ($data['snm'] != '')
	        {
	            $add_where .= " AND (T.MEM_NM LIKE '%{$data['snm']}%' OR T.MEM_ID LIKE '%{$data['snm']}%') ";
	        }
	    }
	    
	    // 상품명
	    if (isset($data['senm']))
	    {
	        if ($data['senm'] != '')
	        {
	            $add_where .= " AND T.SELL_EVENT_NM LIKE '%{$data['senm']}%' ";
	        }
	    }
	    
	    // 보내기 상태
	    if (isset($data['ssstat']))
	    {
	        if ($data['ssstat'] != '')
	        {
	            $add_where .= " AND T.SEND_STAT = '{$data['ssstat']}' ";
	        }
	    }
	    
	    // 날짜검색조건
	    if (isset($data['sdcon']))
	    {
	        if ($data['sdcon'] != '')
	        {
	            if ($data['sdate'] != '' && $data['sdate'] != '')
	            {
	                $sdate = $data['sdate'] . " 00:00:00";
	                $edate = $data['edate'] . " 23:59:59";
	                
	                switch ($data['sdcon'])
	                {
	                    case "sd":
	                        $add_where .= " AND T.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
	                        break;
	                }
	            }
	        }
	    }
	    
	    return $add_where;
	}
	
	/**
	 * 상품 보내기 리스트
	 * @param array $data
	 * @return array
	 */
	public function list_send_event_mgmt_count(array $data)
	{
	    $add_where = $this->list_send_event_mgmt_where($data);
		$sql = "SELECT COUNT(*) as counter FROM send_event_mgmt_tbl T
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
		]);
		$count = $query->getResultArray();
		return $count[0]['counter'];
	}
	
    
    /**
     * 상품 보내기 리스트
     * @param array $data
     * @return array
     */
    public function list_send_event_mgmt(array $data)
    {
        $add_where = $this->list_send_event_mgmt_where($data);
        $sql = "SELECT T.SEND_EVENT_MGMT_SNO, T.SELL_EVENT_SNO, T.COMP_CD, T.BCOFF_CD,
                       T.1RD_CATE_CD, T.2RD_CATE_CD, T.MEM_SNO, T.MEM_ID, T.MEM_NM,
                       T.STCHR_ID, T.STCHR_NM, T.PTCHR_ID, T.PTCHR_NM,
                       T.SELL_EVENT_NM, T.SELL_AMT, T.USE_PROD, T.USE_UNIT,
                       T.CLAS_CNT, T.ADD_SRVC_EXR_DAY, T.ADD_SRVC_CLAS_CNT,
                       T.DOMCY_DAY, T.DOMCY_CNT, T.DOMCY_POSS_EVENT_YN,
                       T.ACC_RTRCT_DV, T.ACC_RTRCT_MTHD, T.CLAS_DV,
                       T.MEM_DISP_YN, T.SELL_S_DATE, T.SELL_E_DATE,
                       T.EVENT_IMG, T.EVENT_ICON, T.GRP_CLAS_PSNNL_CNT,
                       T.SELL_STAT, T.ORI_SELL_AMT, T.ORI_CLAS_CNT,
                       T.ORI_DOMCY_DAY, T.ORI_DOMCY_CNT, T.SEND_BUY_S_DATE,
                       T.SEND_BUY_E_DATE, T.SEND_STAT, T.GRP_CATE_SET,
                       T.LOCKR_SET, T.LOCKR_KND, T.LOCKR_GENDR_SET, T.LOCKR_NO,
                       T.CRE_ID, T.CRE_DATETM, T.MOD_ID, T.MOD_DATETM,
                       MID.MEM_TELNO, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
				FROM send_event_mgmt_tbl AS T
                INNER JOIN mem_info_detl_tbl AS MID ON T.MEM_SNO = MID.MEM_SNO
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY T.CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * =================================================================================
     * send_event_manage [상품 보내기 리스트] - END
     * =================================================================================
     */
    
    
    /**
     * 상품 보내기 insert
     * @param array $data
     */
    public function insert_send_event_mgmt(array $data)
    {
    	$sql = "INSERT send_event_mgmt_tbl SET
					SELL_EVENT_SNO 		= :sell_event_sno:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD		    = :bcoff_cd:
                    ,1RD_CATE_CD		= :1rd_cate_cd:
					,2RD_CATE_CD		= :2rd_cate_cd:
					,MEM_SNO			= :mem_sno:
					,MEM_ID		        = :mem_id:
					,MEM_NM		        = :mem_nm:
					,STCHR_ID		    = :stchr_id:
					,STCHR_NM	        = :stchr_nm:
					,PTCHR_ID	        = :ptchr_id:
					,PTCHR_NM			= :ptchr_nm:
                    ,SELL_EVENT_NM		= :sell_event_nm:
                    ,SELL_AMT			= :sell_amt:
                    ,USE_PROD			= :use_prod:
                    ,USE_UNIT			= :use_unit:
                    ,CLAS_CNT			= :clas_cnt:
                    ,ADD_SRVC_EXR_DAY	= :add_srvc_exr_day:
                    ,ADD_SRVC_CLAS_CNT	= :add_srvc_clas_cnt:
                    ,DOMCY_DAY			= :domcy_day:
                    ,DOMCY_CNT			= :domcy_cnt:
                    ,DOMCY_POSS_EVENT_YN = :domcy_poss_event_yn:
                    ,ACC_RTRCT_DV		= :acc_rtrct_dv:
                    ,ACC_RTRCT_MTHD		= :acc_rtrct_mthd:
                    ,CLAS_DV			= :clas_dv:
                    ,MEM_DISP_YN		= :mem_disp_yn:
                    ,SELL_S_DATE		= :sell_s_date:
                    ,SELL_E_DATE		= :sell_e_date:
                    ,EVENT_IMG			= :event_img:
                    ,EVENT_ICON			= :event_icon:
                    ,GRP_CLAS_PSNNL_CNT	= :grp_clas_psnnl_cnt:
                    ,SELL_STAT			= :sell_stat:
                    ,ORI_SELL_AMT		= :ori_sell_amt:
                    ,ORI_CLAS_CNT		= :ori_clas_cnt:
                    ,ORI_DOMCY_DAY		= :ori_domcy_day:
                    ,ORI_DOMCY_CNT		= :ori_domcy_cnt:
                    ,SEND_BUY_S_DATE	= :send_buy_s_date:
                    ,SEND_BUY_E_DATE	= :send_buy_e_date:
                    ,SEND_STAT			= :send_stat:
                    ,GRP_CATE_SET		= :grp_cate_set:
                    ,LOCKR_SET			= :lockr_set:
                    ,LOCKR_KND			= :lockr_knd:
                    ,LOCKR_GENDR_SET	= :lockr_gendr_set:
                    ,LOCKR_NO			= :lockr_no:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'sell_event_sno' 			=> $data['sell_event_sno']
    			,'comp_cd'			    => $data['comp_cd']
    			,'bcoff_cd'			    => $data['bcoff_cd']
    			,'1rd_cate_cd'			=> $data['1rd_cate_cd']
    			,'2rd_cate_cd'			=> $data['2rd_cate_cd']
    			,'mem_sno'			    => $data['mem_sno']
    			,'mem_id'		        => $data['mem_id']
    			,'mem_nm'		        => $data['mem_nm']
    			,'stchr_id'			    => $data['stchr_id']
    			,'stchr_nm'		        => $data['stchr_nm']
    			,'ptchr_id'	            => $data['ptchr_id']
    			,'ptchr_nm'			    => $data['ptchr_nm']
        	    ,'sell_event_nm'		=> $data['sell_event_nm']
        	    ,'sell_amt'			    => $data['sell_amt']
        	    ,'use_prod'			    => $data['use_prod']
        	    ,'use_unit'			    => $data['use_unit']
        	    ,'clas_cnt'			    => $data['clas_cnt']
        	    ,'add_srvc_exr_day'		=> $data['add_srvc_exr_day']
        	    ,'add_srvc_clas_cnt'	=> $data['add_srvc_clas_cnt']
        	    ,'domcy_day'			=> $data['domcy_day']
        	    ,'domcy_cnt'			=> $data['domcy_cnt']
        	    ,'domcy_poss_event_yn'	=> $data['domcy_poss_event_yn']
        	    ,'acc_rtrct_dv'			=> $data['acc_rtrct_dv']
        	    ,'acc_rtrct_mthd'		=> $data['acc_rtrct_mthd']
        	    ,'clas_dv'			    => $data['clas_dv']
        	    ,'mem_disp_yn'			=> $data['mem_disp_yn']
        	    ,'sell_s_date'			=> $data['sell_s_date']
        	    ,'sell_e_date'			=> $data['sell_e_date']
        	    ,'event_img'			=> $data['event_img']
        	    ,'event_icon'			=> $data['event_icon']
        	    ,'grp_clas_psnnl_cnt'	=> $data['grp_clas_psnnl_cnt']
        	    ,'sell_stat'			=> $data['sell_stat']
        	    ,'ori_sell_amt'			=> $data['ori_sell_amt']
        	    ,'ori_clas_cnt'			=> $data['ori_clas_cnt']
        	    ,'ori_domcy_day'		=> $data['ori_domcy_day']
        	    ,'ori_domcy_cnt'		=> $data['ori_domcy_cnt']
        	    ,'send_buy_s_date'		=> $data['send_buy_s_date']
        	    ,'send_buy_e_date'		=> $data['send_buy_e_date']
        	    ,'send_stat'			=> $data['send_stat']
        	    ,'grp_cate_set'			=> $data['grp_cate_set']
        	    ,'lockr_set'			=> $data['lockr_set']
        	    ,'lockr_knd'			=> $data['lockr_knd']
        	    ,'lockr_gendr_set'		=> $data['lockr_gendr_set']
        	    ,'lockr_no'			    => $data['lockr_no']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    		
    
    
    
}