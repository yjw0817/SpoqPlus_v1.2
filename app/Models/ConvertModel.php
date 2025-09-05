<?php
namespace App\Models;

use CodeIgniter\Model;

class ConvertModel extends Model
{
    
    /**
     * buy_event 에서 수업강사 이름을 업데이트 한다.
     * @param array $data
     * @return array
     */
    public function update_buy_event_ptchr_nm(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
                PTCHR_NM = :ptchr_nm:
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND PTCHR_ID = :ptchr_id:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'ptchr_id'          => $data['ptchr_id']
            ,'ptchr_nm'       => $data['ptchr_nm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * buy_event 에서 수업강사 이름을 업데이트 한다.
     * @param array $data
     * @return array
     */
    public function update_buy_event_stchr_nm(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
                STCHR_NM = :stchr_nm:
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'          => $data['stchr_id']
            ,'stchr_nm'       => $data['stchr_nm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    public function get_teacher_list(array $data)
    {
        
        $sql = "SELECT * FROM mem_info_detl_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_DV = 'T'
                ORDER BY CRE_DATETM ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사정보를 컨버팅 하기 위해 이전 프로그램의 회원 리스트를 가져온다.
     * @param array $data
     */
    public function teacher_list(array $data)
    {
        $sql = "SELECT * FROM z_convert_teacher
                order by user_work_date ASC
				";
        
        $query = $this->db->query($sql, [
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원의 등록일자를 업데이트 한다.
     * @param array $data
     * @return array
     */
    public function update_member_reg_datetm(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
                REG_DATETM = :reg_datetm:
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'          => $data['mem_sno']
            ,'reg_datetm'       => $data['reg_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 등록일자를 업데이트 하기 위하여 최초 등록일자를 가져온다.
     * 최종 종료된 일자를 리턴한다.
     * @param array $data
     * @return array
     */
    public function get_reg_datetm(array $data)
    {
        $sql = "SELECT CRE_DATETM FROM buy_event_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                ORDER BY CRE_DATETM ASC LIMIT 1
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'          => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    public function get_member_status(array $data)
    {
        
        $sql = "SELECT * FROM mem_info_detl_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                ORDER BY CRE_DATETM ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원상태를 종료회원으로 업데이트 하고 종료날짜도 업데이트 한다.
     * @param array $data
     * @return array
     */
    public function update_member_status_end_datetm(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
                MEM_STAT = :mem_stat:
                , END_DATETM = :end_datetm:
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'          => $data['mem_sno']
            ,'mem_stat'         => $data['mem_stat']
            ,'end_datetm'       => $data['end_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 회원 상태를 현재회원 상태로 업데이트 한다.
     * @param array $data
     * @return array
     */
    public function update_member_status(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
                MEM_STAT = :mem_stat:
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'          => $data['mem_sno']
            ,'mem_stat'         => $data['mem_stat']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 종료중 상품만 있는 회원 조건
     * 최종 종료된 일자를 리턴한다.
     * @param array $data
     * @return array
     */
    public function end_status_member_date(array $data)
    {
        $sql = "SELECT CRE_DATETM FROM buy_event_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                ORDER BY CRE_DATETM DESC LIMIT 1
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'          => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 중에 이용중, 예약됨 상품이 있는지 카운트 리턴
     * @param array $data
     * @return array
     */
    public function chk_status_member(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM buy_event_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND EVENT_STAT IN ('01','00')
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'          => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 매출관리 insert
     * @param array $data
     */
    public function insert_sales_mgmt_tbl(array $data)
    {
        $sql = "INSERT sales_mgmt_tbl SET
					SALES_MGMT_SNO         = :sales_mgmt_sno:
					,PAYMT_MGMT_SNO        = :paymt_mgmt_sno:
                    ,BUY_EVENT_SNO         = :buy_event_sno:
                    ,SELL_EVENT_SNO        = :sell_event_sno:
                    ,SEND_EVENT_SNO         = :send_event_sno:
                    ,RECVB_HIST_SNO         = :recvb_hist_sno:
                    ,PAYMT_VAN_SNO          = :paymt_van_sno:
                    ,COMP_CD                = :comp_cd:
                    ,BCOFF_CD               = :bcoff_cd:
                    ,1RD_CATE_CD            = :1rd_cate_cd:
                    ,2RD_CATE_CD            = :2rd_cate_cd:
                    ,CLAS_DV                 = :clas_dv:
                    ,MEM_SNO                 = :mem_sno:
                    ,MEM_ID                 = :mem_id:
                    ,MEM_NM                 = :mem_nm:
                    ,PTHCR_ID               = :pthcr_id:
                    ,SELL_EVENT_NM          = :sell_event_nm:
                    ,PAYMT_STAT             = :paymt_stat:
                    ,PAYMT_MTHD             = :paymt_mthd:
                    ,ACC_SNO                = :acc_sno:
                    ,APPNO                  = :appno:
                    ,PAYMT_AMT              = :paymt_amt:
                    ,SALES_DV               = :sales_dv:
                    ,SALES_DV_RSON          = :sales_dv_rson:
                    ,SALES_MEM_STAT         = :sales_mem_stat:
                    ,PAYMT_CHNL             = :paymt_chnl:
                    ,PAYMT_VAN_KND          = :paymt_van_knd:
                    ,SALES_APLY_YN          = :sales_aply_yn:
                    ,GRP_CATE_SET           = :grp_cate_set:
                    ,LOCKR_SET              = :lockr_set:
                    ,LOCKR_KND              = :lockr_knd:
                    ,LOCKR_GENDR_SET        = :lockr_gendr_set:
                    ,LOCKR_NO               = :lockr_no:
                    ,CRE_ID                 = :cre_id:
                    ,CRE_DATETM             = :cre_datetm:
                    ,MOD_ID                 = :mod_id:
                    ,MOD_DATETM             = :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'sales_mgmt_sno'            => $data['sales_mgmt_sno']
            ,'paymt_mgmt_sno'			=> $data['paymt_mgmt_sno']
            ,'buy_event_sno'			=> $data['buy_event_sno']
            ,'sell_event_sno'			=> $data['sell_event_sno']
            ,'send_event_sno'			=> $data['send_event_sno']
            ,'recvb_hist_sno'			=> $data['recvb_hist_sno']
            ,'paymt_van_sno'			=> $data['paymt_van_sno']
            ,'comp_cd'                  => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'1rd_cate_cd'              => $data['1rd_cate_cd']
            ,'2rd_cate_cd'              => $data['2rd_cate_cd']
            ,'clas_dv'                  => $data['clas_dv']
            ,'mem_sno'                  => $data['mem_sno']
            ,'mem_id'                   => $data['mem_id']
            ,'mem_nm'                   => $data['mem_nm']
            ,'pthcr_id'                 => $data['pthcr_id']
            ,'sell_event_nm'			=> $data['sell_event_nm']
            ,'paymt_stat'               => $data['paymt_stat']
            ,'paymt_mthd'               => $data['paymt_mthd']
            ,'acc_sno'                  => $data['acc_sno']
            ,'appno'                    => $data['appno']
            ,'paymt_amt'                => $data['paymt_amt']
            ,'sales_dv'                 => $data['sales_dv']
            ,'sales_dv_rson'			=> $data['sales_dv_rson']
            ,'sales_mem_stat'			=> $data['sales_mem_stat']
            ,'paymt_chnl'               => $data['paymt_chnl']
            ,'paymt_van_knd'			=> $data['paymt_van_knd']
            ,'sales_aply_yn'			=> $data['sales_aply_yn']
            ,'grp_cate_set'             => $data['grp_cate_set']
            ,'lockr_set'                => $data['lockr_set']
            ,'lockr_knd'                => $data['lockr_knd']
            ,'lockr_gendr_set'			=> $data['lockr_gendr_set']
            ,'lockr_no'                 => $data['lockr_no']
            ,'cre_id'                   => $data['cre_id']
            ,'cre_datetm'               => $data['cre_datetm']
            ,'mod_id'                   => $data['mod_id']
            ,'mod_datetm'               => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 결재관리 insert
     * @param array $data
     */
    public function insert_paymt_mgmt_tbl(array $data)
    {
        $sql = "INSERT paymt_mgmt_tbl SET
					PAYMT_MGMT_SNO      = :paymt_mgmt_sno:
					,BUY_EVENT_SNO      = :buy_event_sno:
					,SELL_EVENT_SNO     = :sell_event_sno:
					,SEND_EVENT_SNO     = :send_event_sno:
                    ,RECVB_SNO          = :recvb_sno:
                    ,PAYMT_VAN_SNO      = :paymt_van_sno:
                    ,COMP_CD		    = :comp_cd:
                    ,BCOFF_CD		    = :bcoff_cd:
                    ,1RD_CATE_CD        = :1rd_cate_cd:
                    ,2RD_CATE_CD        = :2rd_cate_cd:
                    ,MEM_SNO            = :mem_sno:
                    ,MEM_ID	            = :mem_id:
                    ,MEM_NM	            = :mem_nm:
                    ,SELL_EVENT_NM      = :sell_event_nm:
                    ,PAYMT_MTHD		    = :paymt_mthd:
                    ,ACCT_NO            = :acct_no:
                    ,APPNO_SNO          = :appno_sno:
                    ,APPNO              = :appno:
                    ,PAYMT_AMT		    = :paymt_amt:
                    ,PAYMT_STAT         = :paymt_stat:
                    ,PAYMT_DATE         = :paymt_date:
                    ,REFUND_DATE        = :refund_date:
                    ,PAYMT_CHNL         = :paymt_chnl:
                    ,PAYMT_VAN_KND      = :paymt_van_knd:
                    ,GRP_CATE_SET       = :grp_cate_set:
                    ,LOCKR_SET		    = :lockr_set:
                    ,LOCKR_KND		    = :lockr_knd:
                    ,LOCKR_GENDR_SET    = :lockr_gendr_set:
                    ,LOCKR_NO		    = :lockr_no:
                    ,CRE_ID             = :cre_id:
                    ,CRE_DATETM         = :cre_datetm:
                    ,MOD_ID             = :mod_id:
                    ,MOD_DATETM         = :mod_datetm:
            
				";
        $query = $this->db->query($sql, [
            'paymt_mgmt_sno'            => $data['paymt_mgmt_sno']
            ,'buy_event_sno'			=> $data['buy_event_sno']
            ,'sell_event_sno'			=> $data['sell_event_sno']
            ,'send_event_sno'           => $data['send_event_sno']
            ,'recvb_sno'                => $data['recvb_sno']
            ,'paymt_van_sno'            => $data['paymt_van_sno']
            ,'comp_cd'                  => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'1rd_cate_cd'              => $data['1rd_cate_cd']
            ,'2rd_cate_cd'              => $data['2rd_cate_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'mem_id'                   => $data['mem_id']
            ,'mem_nm'                   => $data['mem_nm']
            ,'sell_event_nm'            => $data['sell_event_nm']
            ,'paymt_mthd'               => $data['paymt_mthd']
            ,'acct_no'                  => $data['acct_no']
            ,'appno_sno'                => $data['appno_sno']
            ,'appno'                    => $data['appno']
            ,'paymt_amt'                => $data['paymt_amt']
            ,'paymt_stat'               => $data['paymt_stat']
            ,'paymt_date'               => $data['paymt_date']
            ,'refund_date'              => $data['refund_date']
            ,'paymt_chnl'               => $data['paymt_chnl']
            ,'paymt_van_knd'            => $data['paymt_van_knd']
            ,'grp_cate_set'             => $data['grp_cate_set']
            ,'lockr_set'                => $data['lockr_set']
            ,'lockr_knd'                => $data['lockr_knd']
            ,'lockr_gendr_set'			=> $data['lockr_gendr_set']
            ,'lockr_no'                 => $data['lockr_no']
            ,'cre_id'                   => $data['cre_id']
            ,'cre_datetm'               => $data['cre_datetm']
            ,'mod_id'                   => $data['mod_id']
            ,'mod_datetm'               => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 구매상품관리 insert
     * @param array $data
     */
    public function insert_buy_event_mgmt(array $data)
    {
        $sql = "INSERT buy_event_mgmt_tbl SET
					BUY_EVENT_SNO 		= :buy_event_sno:
                    ,SELL_EVENT_SNO 	= :sell_event_sno:
                    ,SEND_EVENT_SNO 	= :send_event_sno:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD		    = :bcoff_cd:
                    ,1RD_CATE_CD		= :1rd_cate_cd:
					,2RD_CATE_CD		= :2rd_cate_cd:
					,MEM_SNO		    = :mem_sno:
					,MEM_ID		        = :mem_id:
					,MEM_NM		        = :mem_nm:
                    ,STCHR_ID           = :stchr_id:
					,STCHR_NM           = :stchr_nm:
					,PTCHR_ID		    = :ptchr_id:
					,PTCHR_NM		    = :ptchr_nm:
					,SELL_EVENT_NM		= :sell_event_nm:
					,SELL_AMT		    = :sell_amt:
					,USE_PROD			= :use_prod:
					,USE_UNIT		    = :use_unit:
					,CLAS_CNT			= :clas_cnt:
					,DOMCY_DAY		    = :domcy_day:
					,DOMCY_CNT		    = :domcy_cnt:
					,DOMCY_POSS_EVENT_YN = :domcy_poss_event_yn:
					,ACC_RTRCT_DV		= :acc_rtrct_dv:
					,ACC_RTRCT_MTHD		= :acc_rtrct_mthd:
					,CLAS_DV		    = :clas_dv:
					,EVENT_IMG		    = :event_img:
					,EVENT_ICON		    = :event_icon:
					,GRP_CLAS_PSNNL_CNT	= :grp_clas_psnnl_cnt:
					,EVENT_STAT		    = :event_stat:
					,EVENT_STAT_RSON	= :event_stat_rson:
					,EXR_S_DATE		    = :exr_s_date:
					,EXR_E_DATE		    = :exr_e_date:
					,LEFT_DOMCY_POSS_DAY = :left_domay_poss_day:
					,LEFT_DOMCY_POSS_CNT = :left_domcy_poss_cnt:
					,BUY_DATETM		    = :buy_datetm:
					,REAL_SELL_AMT		= :real_sell_amt:
					,BUY_AMT		    = :buy_amt:
					,RECVB_AMT		    = :recbc_amt:
					,ADD_SRVC_EXR_DAY	= :add_srvc_exr_day:
					,ADD_SRVC_CLAS_CNT	= :add_srvc_clas_cnt:
					,ADD_DOMCY_DAY		= :add_domcy_day:
					,ADD_DOMCY_CNT		= :add_domcy_cnt:
					,SRVC_CLAS_LEFT_CNT	= :srvc_clas_left_cnt:
					,SRVC_CLAS_PRGS_CNT	= :srvc_clas_prgs_cnt:
					,1TM_CLAS_PRGS_AMT	= :1tm_clas_prgs_amt:
					,MEM_REGUL_CLAS_LEFT_CNT = :mem_regul_clas_left_cnt:
					,MEM_REGUL_CLAS_PRGS_CNT = :mem_regul_clas_prgs_cnt:
					,ORI_EXR_S_DATE		= :ori_exr_s_date:
					,ORI_EXR_E_DATE		= :ori_exr_e_date:
					,TRANSM_POSS_YN		= :transm_poss_yn:
					,REFUND_POSS_YN		= :refund_poss_yn:
					,GRP_CATE_SET		= :grp_cate_set:
					,LOCKR_SET		    = :lockr_set:
					,LOCKR_KND		    = :lockr_knd:
					,LOCKR_GENDR_SET	= :lockr_gendr_set:
					,LOCKR_NO		    = :lockr_no:
					,CRE_ID		        = :cre_id:
					,CRE_DATETM		    = :cre_datetm:
					,MOD_ID		        = :mod_id:
					,MOD_DATETM		    = :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 	=> $data['buy_event_sno']
            ,'sell_event_sno'	=> $data['sell_event_sno']
            ,'send_event_sno'	=> $data['send_event_sno']
            ,'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'1rd_cate_cd'		    => $data['1rd_cate_cd']
            ,'2rd_cate_cd'		=> $data['2rd_cate_cd']
            ,'mem_sno'		    => $data['mem_sno']
            ,'mem_id'		    => $data['mem_id']
            ,'mem_nm'		    => $data['mem_nm']
            ,'stchr_id'		=> $data['stchr_id']
            ,'stchr_nm'		=> $data['stchr_nm']
            ,'ptchr_id'		=> $data['ptchr_id']
            ,'ptchr_nm'		=> $data['ptchr_nm']
            ,'sell_event_nm'	    => $data['sell_event_nm']
            ,'sell_amt'	    => $data['sell_amt']
            ,'use_prod'			=> $data['use_prod']
            ,'use_unit'		=> $data['use_unit']
            ,'clas_cnt'			=> $data['clas_cnt']
            ,'domcy_day'		=> $data['domcy_day']
            ,'domcy_cnt'	=> $data['domcy_cnt']
            ,'domcy_poss_event_yn'			=> $data['domcy_poss_event_yn']
            ,'acc_rtrct_dv'			=> $data['acc_rtrct_dv']
            ,'acc_rtrct_mthd'		    => $data['acc_rtrct_mthd']
            ,'clas_dv'		=> $data['clas_dv']
            ,'event_img'		    => $data['event_img']
            ,'event_icon'		=> $data['event_icon']
            ,'grp_clas_psnnl_cnt'		=> $data['grp_clas_psnnl_cnt']
            ,'event_stat'	    => $data['event_stat']
            ,'event_stat_rson'	    => $data['event_stat_rson']
            ,'exr_s_date'			=> $data['exr_s_date']
            ,'exr_e_date'		=> $data['exr_e_date']
            ,'left_domay_poss_day'			=> $data['left_domay_poss_day']
            ,'left_domcy_poss_cnt'		=> $data['left_domcy_poss_cnt']
            ,'buy_datetm'	=> $data['buy_datetm']
            ,'real_sell_amt'			=> $data['real_sell_amt']
            ,'buy_amt'			=> $data['buy_amt']
            ,'recbc_amt'		    => $data['recbc_amt']
            ,'add_srvc_exr_day'		=> $data['add_srvc_exr_day']
            ,'add_srvc_clas_cnt'		=> $data['add_srvc_clas_cnt']
            ,'add_domcy_day'		    => $data['add_domcy_day']
            ,'add_domcy_cnt'		=> $data['add_domcy_cnt']
            ,'srvc_clas_left_cnt'		=> $data['srvc_clas_left_cnt']
            ,'srvc_clas_prgs_cnt'	    => $data['srvc_clas_prgs_cnt']
            ,'1tm_clas_prgs_amt'	    => $data['1tm_clas_prgs_amt']
            ,'mem_regul_clas_left_cnt'			=> $data['mem_regul_clas_left_cnt']
            ,'mem_regul_clas_prgs_cnt'		=> $data['mem_regul_clas_prgs_cnt']
            ,'ori_exr_s_date'			=> $data['ori_exr_s_date']
            ,'ori_exr_e_date'		=> $data['ori_exr_e_date']
            ,'transm_poss_yn'	=> $data['transm_poss_yn']
            ,'refund_poss_yn'			=> $data['refund_poss_yn']
            ,'grp_cate_set'			=> $data['grp_cate_set']
            ,'lockr_set'		    => $data['lockr_set']
            ,'lockr_knd'		=> $data['lockr_knd']
            ,'lockr_gendr_set'		    => $data['lockr_gendr_set']
            ,'lockr_no'		=> $data['lockr_no']
            ,'cre_id'		=> $data['cre_id']
            ,'cre_datetm'	    => $data['cre_datetm']
            ,'mod_id'	    => $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    public function get_pr_nmae(array $data)
    {
        $sql = "SELECT pr_name FROM z_convert_product_basic
                WHERE uid = :uid:
				";
        
        $query = $this->db->query($sql, [
            'uid'			=> $data['uid']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function get_sell_event_info(array $data)
    {
        $sql = "SELECT * FROM sell_event_mgmt_tbl
                WHERE SELL_EVENT_SNO = :sell_event_sno:
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sell_event_sno'   => $data['sell_event_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function get_convert_product_member(array $data)
    {
        $sql = "SELECT * FROM z_convert_product_member
                WHERE user_id = :user_id:
                ORDER BY pr_regdate ASC
				";
        
        $query = $this->db->query($sql, [
            'user_id'			=> $data['user_id']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function get_member(array $data)
    {
        $limit_s = ($data['page'] - 1);
        
        if ($limit_s > 0 )
        {
            $limit_s = ($limit_s * 100) + 1;
        }
        
        $sql = "SELECT * FROM mem_info_detl_tbl
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd: 
                ORDER BY CRE_DATETM ASC LIMIT {$limit_s},100
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
	/**
	 * 회원정보를 컨버팅 하기전에 아이디 중복 여부에 대해서 체크를 한다.
	 * @param array $data
	 * @return array
	 */
    public function member_chk1(array $data)
    {
        $sql = "SELECT user_id, COUNT(user_id) AS counter FROM z_convert_member
                GROUP BY user_id
                HAVING counter > 1
				";
        
        $query = $this->db->query($sql, [
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원정보를 컨버팅 하기 위해 이전 프로그램의 회원 리스트를 가져온다.
     * @param array $data
     */
    public function member_list(array $data)
    {
        $sql = "SELECT * FROM z_convert_member
                order by user_first_date ASC
				";
        
        $query = $this->db->query($sql, [
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원메인정보 insert
     * @param array $data
     */
    public function insert_mem_main_info(array $data)
    {
        $sql = "INSERT mem_main_info_tbl SET
					MEM_SNO 		= :mem_sno:
					,MEM_ID			= :mem_id:
					,MEM_PWD		= :mem_pwd:
                    ,MEM_NM		    = :mem_nm:
					,QR_CD			= :qr_cd:
					,BTHDAY			= :bthday:
					,MEM_GENDR		= :mem_gendr:
					,MEM_TELNO		= :mem_telno:
                    ,MEM_TELNO_ENC  = :mem_telno_enc:
                    ,MEM_TELNO_MASK = :mem_telno_mask:
                    ,MEM_TELNO_SHORT = :mem_telno_short:
                    ,MEM_TELNO_ENC2  = :mem_telno_enc2:
					,MEM_ADDR		= :mem_addr:
					,MEM_MAIN_IMG	= :mem_main_img:
					,MEM_THUMB_IMG	= :mem_thumb_img:
					,MEM_DV			= :mem_dv:
                    ,SET_COMP_CD	= :set_comp_cd:
                    ,SET_BCOFF_CD	= :set_bcoff_cd:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                    ,CONVERT_KEY    = :convert_key:
				";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_pwd'			=> $data['mem_pwd']
            ,'mem_nm'			=> $data['mem_nm']
            ,'qr_cd'			=> $data['qr_cd']
            ,'bthday'			=> $data['bthday']
            ,'mem_gendr'		=> $data['mem_gendr']
            ,'mem_telno'		=> $data['mem_telno']
            ,'mem_telno_enc'		=> $data['mem_telno_enc']
            ,'mem_telno_mask'		=> $data['mem_telno_mask']
            ,'mem_telno_short'		=> $data['mem_telno_short']
            ,'mem_telno_enc2'		=> $data['mem_telno_enc2']
            ,'mem_addr'			=> $data['mem_addr']
            ,'mem_main_img'		=> $data['mem_main_img']
            ,'mem_thumb_img'	=> $data['mem_thumb_img']
            ,'mem_dv'			=> $data['mem_dv']
            ,'set_comp_cd'		=> $data['set_comp_cd']
            ,'set_bcoff_cd'		=> $data['set_bcoff_cd']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
            ,'convert_key'      => $data['convert_key']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 회원정보상세 insert
     * @param array $data
     */
    public function insert_mem_info_detl_tbl(array $data)
    {
        $sql = "INSERT mem_info_detl_tbl SET
					MEM_SNO 		= :mem_sno:
					,COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
					,MEM_ID			= :mem_id:
                    ,MEM_NM		    = :mem_nm:
					,MEM_STAT		= :mem_stat:
					,QR_CD			= :qr_cd:
					,BTHDAY			= :bthday:
					,MEM_GENDR		= :mem_gendr:
					,MEM_TELNO		= :mem_telno:
                    ,MEM_TELNO_ENC  = :mem_telno_enc:
                    ,MEM_TELNO_MASK = :mem_telno_mask:
                    ,MEM_TELNO_SHORT = :mem_telno_short:
                    ,MEM_TELNO_ENC2  = :mem_telno_enc2:
					,MEM_ADDR		= :mem_addr:
					,MEM_MAIN_IMG	= :mem_main_img:
					,MEM_THUMB_IMG	= :mem_thumb_img:
					,TCHR_POSN		= :tchr_posn:
					,CTRCT_TYPE		= :ctrct_type:
					,TCHR_SIMP_PWD	= :tchr_simp_pwd:
					,MEM_DV			= :mem_dv:
                    ,JON_PLACE			= :jon_place:
                    ,JON_DATETM			= :jon_datetm:
                    ,REG_PLACE			= :reg_place:
                    ,REG_DATETM			= :reg_datetm:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                    ,CONVERT_KEY    = :convert_key:
				";
        
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
            ,'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_nm'			=> $data['mem_nm']
            ,'mem_stat'			=> $data['mem_stat']
            ,'qr_cd'			=> $data['qr_cd']
            ,'bthday'			=> $data['bthday']
            ,'mem_gendr'		=> $data['mem_gendr']
            ,'mem_telno'		=> $data['mem_telno']
            ,'mem_telno_enc'		=> $data['mem_telno_enc']
            ,'mem_telno_mask'		=> $data['mem_telno_mask']
            ,'mem_telno_short'		=> $data['mem_telno_short']
            ,'mem_telno_enc2'		=> $data['mem_telno_enc2']
            ,'mem_addr'			=> $data['mem_addr']
            ,'mem_main_img'		=> $data['mem_main_img']
            ,'mem_thumb_img'	=> $data['mem_thumb_img']
            ,'tchr_posn'		=> $data['tchr_posn']
            ,'ctrct_type'		=> $data['ctrct_type']
            ,'tchr_simp_pwd'	=> $data['tchr_simp_pwd']
            ,'mem_dv'			=> $data['mem_dv']
            ,'jon_place'		=> $data['jon_place']
            ,'jon_datetm'		=> $data['jon_datetm']
            ,'reg_place'		=> $data['reg_place']
            ,'reg_datetm'		=> $data['reg_datetm']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
            ,'convert_key'      => $data['convert_key']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 회원 컨버팅 키값을 이용하여 회원 정보를 가져온다.
     * @param array $data
     */
    public function memo_list(array $data)
    {
        $sql = "SELECT * FROM z_convert_member_memo
                ORDER BY me_regdate ASC
				";
        if ($data['page'] == '1') $sql .= " LIMIT 0,10000";
        if ($data['page'] == '2') $sql .= " LIMIT 10001,10000";
        if ($data['page'] == '3') $sql .= " LIMIT 20001,10000";
        if ($data['page'] == '4') $sql .= " LIMIT 30001,10000";
        if ($data['page'] == '5') $sql .= " LIMIT 40001,10000";
        if ($data['page'] == '6') $sql .= " LIMIT 50001,10000";
        
        $query = $this->db->query($sql, [
        ]);
        array_push($data,$query);
        return $query->getResultArray();
        
    }
    
    /**
     * 회원 컨버팅 회원 아이디를 이용하여 회원 정보를 가져온다.
     * @param array $data
     */
    public function get_mem_info_user_id(array $data)
    {
        $sql = "SELECT * FROM mem_info_detl_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_DV = 'M'
				AND MEM_ID = :mem_id:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_id'		=> $data['mem_id']
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function cron_list_smgmt(array $data)
    {
        $sql = "SELECT * FROM smgmt_mgmt_tbl
				";
        
        $query = $this->db->query($sql, [
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function cron_pwd_enc_smgmt_update(array $data)
    {
        $sql = "UPDATE smgmt_mgmt_tbl SET
                    SMGMT_PWD = :smgmt_pwd:
                WHERE SMGMT_ID = :smgmt_id:
				";
        
        $query = $this->db->query($sql, [
            'smgmt_id' 	=> $data['smgmt_id']
            ,'smgmt_pwd' 	=> $data['smgmt_pwd']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    public function insert_cron(array $data)
    {
        $sql = "INSERT cron_hist SET
					CRON_KND 		    = :cron_knd:
					,CRON_CRE_DATETM    = :cron_cre_datetm:
				";
        $query = $this->db->query($sql, [
            'cron_knd' 			        => $data['cron_knd']
            ,'cron_cre_datetm' 			=> $data['cron_cre_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
}