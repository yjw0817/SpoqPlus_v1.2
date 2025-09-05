<?php
namespace App\Models;

use CodeIgniter\Model;

class PayModel extends Model
{
    
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
     * 결재관리 update (환불)
     * @param array $data
     */
    public function update_paymt_mgmt_cancel_tbl(array $data)
    {
        $sql = "UPDATE paymt_mgmt_tbl SET
                    PAYMT_STAT         = :paymt_stat:
                    ,REFUND_DATE        = :refund_date:
                    ,MOD_ID             = :mod_id:
                    ,MOD_DATETM         = :mod_datetm:
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND PAYMT_MGMT_SNO = :paymt_mgmt_sno:
				";
        $query = $this->db->query($sql, [
            'paymt_mgmt_sno'            => $data['paymt_mgmt_sno']
            ,'comp_cd'                  => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'paymt_stat'               => $data['paymt_stat']
            ,'refund_date'              => $data['refund_date']
            ,'mod_id'                   => $data['mod_id']
            ,'mod_datetm'               => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 결제관리 정보 가져오기 (환불)
     * @param array $data
     * @return array
     */
    public function get_paymt_mgmt_cancel_tbl(array $data)
    {
        $sql = "SELECT * FROM paymt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND PAYMT_MGMT_SNO = :paymt_mgmt_sno:
				";
        
        $query = $this->db->query($sql, [
            'paymt_mgmt_sno'            => $data['paymt_mgmt_sno']
            ,'comp_cd'                  => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 환불관리 insert
     * @param array $data
     */
    public function insert_refund_mgmt_tbl(array $data)
    {
    	$sql = "INSERT refund_mgmt_tbl SET
					BUY_EVENT_SNO		= :buy_event_sno:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD			= :bcoff_cd:
                    ,SELL_EVENT_NM			= :sell_event_nm:
					,MEM_SNO			= :mem_sno:
                    ,MEM_ID				= :mem_id:
                    ,MEM_NM				= :mem_nm:
                    ,USE_PROD		    = :use_prod:
                    ,USE_UNIT		    = :use_unit:
                    ,CLAS_CNT			= :clas_cnt:
                    ,EXR_S_DATE			= :exr_s_date:
                    ,EXR_E_DATE			= :exr_e_date:
                    ,TOTAL_EXR_DAY_CNT	= :total_exr_day_cnt:
                    ,1TM_EXR_AMT		= :1tm_exr_amt:
                    ,USE_DAY_CNT		= :use_day_cnt:
                    ,1TM_CLAS_AMT		= :1tm_clas_amt:
                    ,USE_CLAS_CNT		= :use_clas_cnt:
                    ,USE_AMT			= :use_amt:
                    ,BUY_AMT			= :buy_amt:
                    ,REFUND_AMT		    = :rerund_amt:
                    ,PNALT_AMT			= :pnalt_amt:
                    ,ETC_AMT			= :etc_amt:
                    ,CRE_ID             = :cre_id:
                    ,CRE_DATETM         = :cre_datetm:
                    ,MOD_ID             = :mod_id:
                    ,MOD_DATETM         = :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'buy_event_sno'			=> $data['buy_event_sno']
    			,'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    	        ,'sell_event_nm'		=> $data['sell_event_nm']
    			,'mem_sno'				=> $data['mem_sno']
    			,'mem_id'				=> $data['mem_id']
    			,'mem_nm'				=> $data['mem_nm']
    			,'use_prod'				=> $data['use_prod']
    			,'use_unit'				=> $data['use_unit']
    			,'clas_cnt'				=> $data['clas_cnt']
    			,'exr_s_date'			=> $data['exr_s_date']
    			,'exr_e_date'			=> $data['exr_e_date']
    			,'total_exr_day_cnt'	=> $data['total_exr_day_cnt']
    			,'1tm_exr_amt'			=> $data['1tm_exr_amt']
    			,'use_day_cnt'			=> $data['use_day_cnt']
    			,'1tm_clas_amt'			=> $data['1tm_clas_amt']
    			,'use_clas_cnt' 		=> $data['use_clas_cnt']
    			,'use_amt'				=> $data['use_amt']
    			,'buy_amt'				=> $data['buy_amt']
    			,'rerund_amt'			=> $data['rerund_amt']
    			,'pnalt_amt'			=> $data['pnalt_amt']
    			,'etc_amt'				=> $data['etc_amt']
    			,'cre_id'				=> $data['cre_id']
    			,'cre_datetm'			=> $data['cre_datetm']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 구매상품 update (환불 종료)
     * @param array $data
     */
    public function update_buy_event_cancel_tbl(array $data)
    {
    	$sql = "UPDATE buy_event_mgmt_tbl SET
                    EVENT_STAT          = :event_stat:
                    ,EVENT_STAT_RSON 	= :event_stat_rson:
                    ,EXR_E_DATE 	    = :exr_e_date:
                    ,MOD_ID             = :mod_id:
                    ,MOD_DATETM         = :mod_datetm:
                WHERE COMP_CD 			= :comp_cd:
                AND BCOFF_CD 			= :bcoff_cd:
                AND MEM_SNO 			= :mem_sno:
                AND BUY_EVENT_SNO 		= :buy_event_sno:
				";
    	$query = $this->db->query($sql, [
    			'event_stat'            => $data['event_stat']
    			,'event_stat_rson'		=> $data['event_stat_rson']
    	        ,'exr_e_date'		    => $data['exr_e_date']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    			,'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'mem_sno'				=> $data['mem_sno']
    			,'buy_event_sno'		=> $data['buy_event_sno']
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
     * 미수금 내역 insert
     * @param array $data
     */
    public function insert_recvb_hist_tbl(array $data)
    {
        $sql = "INSERT recvb_hist_tbl SET
					RECVB_HIST_SNO      = :recvb_hist_sno:
					,BUY_EVENT_SNO      = :buy_event_sno:
					,SELL_EVENT_SNO     = :sell_event_sno:
					,SEND_EVENT_SNO     = :send_event_sno:
                    ,COMP_CD		    = :comp_cd:
                    ,BCOFF_CD		    = :bcoff_cd:
                    ,1RD_CATE_CD        = :1rd_cate_cd:
                    ,2RD_CATE_CD        = :2rd_cate_cd:
                    ,MEM_SNO            = :mem_sno:
                    ,MEM_ID	            = :mem_id:
                    ,MEM_NM	            = :mem_nm:
                    ,SELL_EVENT_NM      = :sell_event_nm:
                    ,RECVB_AMT		    = :recvb_amt:
                    ,RECVB_PAYMT_AMT    = :recvb_paymt_amt:
                    ,RECVB_LEFT_AMT     = :recvb_left_amt:
                    ,RECVB_STAT		    = :recvb_stat:
                    ,BUY_DATETM		    = :buy_datetm:
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
            'recvb_hist_sno'            => $data['recvb_hist_sno']
            ,'buy_event_sno'			=> $data['buy_event_sno']
            ,'sell_event_sno'			=> $data['sell_event_sno']
            ,'send_event_sno'           => $data['send_event_sno']
            ,'comp_cd'                  => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'1rd_cate_cd'              => $data['1rd_cate_cd']
            ,'2rd_cate_cd'              => $data['2rd_cate_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'mem_id'                   => $data['mem_id']
            ,'mem_nm'                   => $data['mem_nm']
            ,'sell_event_nm'            => $data['sell_event_nm']
            ,'recvb_amt'                => $data['recvb_amt']
            ,'recvb_paymt_amt'			=> $data['recvb_paymt_amt']
            ,'recvb_left_amt'			=> $data['recvb_left_amt']
            ,'recvb_stat'               => $data['recvb_stat']
            ,'buy_datetm'               => $data['buy_datetm']
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
     * 상품일련번호를 이용하여 판매상품 정보 가져오기
     * @param array $data
     * @return array
     */
    public function get_event_use_sno(array $data)
    {
        $sql = "SELECT * FROM sell_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND SELL_EVENT_SNO = :sell_event_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'sell_event_sno'		=> $data['sell_event_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 상품일련번호를 이용하여 구매상품 정보 가져오기
     * @param array $data
     * @return array
     */
    public function get_event_buy_sno(array $data)
    {
        $sql = "SELECT * FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND BUY_EVENT_SNO = :buy_event_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'buy_event_sno'		=> $data['buy_event_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원일련번호를 이용하여 구매한 상품중에 이용중, 예약됨 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_buy_events_use_memsno(array $data)
    {
        $sql = "SELECT BUY_EVENT_SNO,1RD_CATE_CD,2RD_CATE_CD,USE_PROD,USE_UNIT,ACC_RTRCT_DV
                ,ACC_RTRCT_MTHD,EVENT_STAT,EXR_S_DATE,EXR_E_DATE,GRP_CATE_SET,LOCKR_SET 
                ,ADD_SRVC_EXR_DAY ,LOCKR_NO, LOCKR_GENDR_SET, LOCKR_KND
                FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND EVENT_STAT != '99'
				ORDER BY EXR_S_DATE ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'mem_sno'		        => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 구매상품관리 update
     * 구매시 영향받은 상품들의 시작, 종료일을 업데이트 한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_redate(array $data)
    {
    	$sql = "UPDATE buy_event_mgmt_tbl SET
					EXR_S_DATE		    = :exr_s_date:
					,EXR_E_DATE		    = :exr_e_date:
					,MOD_ID		   		= :mod_id:
					,MOD_DATETM		    = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		= :buy_event_sno: 
				";
    	$query = $this->db->query($sql, [
    			'buy_event_sno' 		=> $data['buy_event_sno']
    			,'exr_s_date'			=> $data['exr_s_date']
    			,'exr_e_date'			=> $data['exr_e_date']
    			,'mod_id'	    		=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
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
                    ,1RD_CATE_CD		        = :1rd_cate_cd:
					,2RD_CATE_CD		    = :2rd_cate_cd:
					,MEM_SNO		        = :mem_sno:
					,MEM_ID		        = :mem_id:
					,MEM_NM		        = :mem_nm:
                    ,STCHR_ID          = :stchr_id:
					,STCHR_NM          = :stchr_nm:
					,PTCHR_ID		    = :ptchr_id:
					,PTCHR_NM		    = :ptchr_nm:
					,SELL_EVENT_NM			= :sell_event_nm:
					,SELL_AMT		= :sell_amt:
					,USE_PROD			    = :use_prod:
					,USE_UNIT		    = :use_unit:
					,CLAS_CNT			    = :clas_cnt:
					,DOMCY_DAY		    = :domcy_day:
					,DOMCY_CNT		    = :domcy_cnt:
					,DOMCY_POSS_EVENT_YN		    = :domcy_poss_event_yn:
					,ACC_RTRCT_DV		    = :acc_rtrct_dv:
					,ACC_RTRCT_MTHD		    = :acc_rtrct_mthd:
					,CLAS_DV		    = :clas_dv:
					,EVENT_IMG		    = :event_img:
					,EVENT_ICON		    = :event_icon:
					,GRP_CLAS_PSNNL_CNT		    = :grp_clas_psnnl_cnt:
					,EVENT_STAT		    = :event_stat:
					,EVENT_STAT_RSON		    = :event_stat_rson:
					,EXR_S_DATE		    = :exr_s_date:
					,EXR_E_DATE		    = :exr_e_date:
					,LEFT_DOMCY_POSS_DAY		    = :left_domay_poss_day:
					,LEFT_DOMCY_POSS_CNT		    = :left_domcy_poss_cnt:
					,BUY_DATETM		    = :buy_datetm:
					,REAL_SELL_AMT		    = :real_sell_amt:
					,BUY_AMT		    = :buy_amt:
					,RECVB_AMT		    = :recbc_amt:
					,ADD_SRVC_EXR_DAY	    = :add_srvc_exr_day:
					,ADD_SRVC_CLAS_CNT		    = :add_srvc_clas_cnt:
					,ADD_DOMCY_DAY		    = :add_domcy_day:
					,ADD_DOMCY_CNT		    = :add_domcy_cnt:
					,SRVC_CLAS_LEFT_CNT		    = :srvc_clas_left_cnt:
					,SRVC_CLAS_PRGS_CNT		    = :srvc_clas_prgs_cnt:
					,1TM_CLAS_PRGS_AMT		    = :1tm_clas_prgs_amt:
					,MEM_REGUL_CLAS_LEFT_CNT		    = :mem_regul_clas_left_cnt:
					,MEM_REGUL_CLAS_PRGS_CNT		    = :mem_regul_clas_prgs_cnt:
					,ORI_EXR_S_DATE		    = :ori_exr_s_date:
					,ORI_EXR_E_DATE		    = :ori_exr_e_date:
					,TRANSM_POSS_YN		    = :transm_poss_yn:
					,REFUND_POSS_YN		    = :refund_poss_yn:
					,GRP_CATE_SET		    = :grp_cate_set:
					,LOCKR_SET		    = :lockr_set:
					,LOCKR_KND		    = :lockr_knd:
					,LOCKR_GENDR_SET		    = :lockr_gendr_set:
					,LOCKR_NO		    = :lockr_no:
					,CRE_ID		    = :cre_id:
					,CRE_DATETM		    = :cre_datetm:
					,MOD_ID		    = :mod_id:
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
	
	/**
	 * 락커 상태 update [ lockr_room ]
	 * @param array $data
	 */
	public function update_lockr_room(array $data)
	{
	    $sql = "UPDATE lockr_room SET
					BUY_EVENT_SNO		= :buy_event_sno:
					,MEM_SNO		    = :mem_sno:
					,MEM_NM		   		= :mem_nm:
					,LOCKR_USE_S_DATE	= :lockr_use_s_date:
                    ,LOCKR_USE_E_DATE	= :lockr_use_e_date:
                    ,LOCKR_STAT		    = :lockr_stat:
    			WHERE COMP_CD 		    = :comp_cd:
                    AND BCOFF_CD        = :bcoff_cd:
                    AND LOCKR_KND       = :lockr_knd:
                    AND LOCKR_GENDR_SET = :lockr_gendr_set:
                    AND LOCKR_NO        = :lockr_no:
				";
	    $query = $this->db->query($sql, [
	        'buy_event_sno' 		    => $data['buy_event_sno']
	        ,'mem_sno'			        => $data['mem_sno']
	        ,'mem_nm'			        => $data['mem_nm']
	        ,'lockr_use_s_date'	    	=> $data['lockr_use_s_date']
	        ,'lockr_use_e_date'			=> $data['lockr_use_e_date']
	        ,'lockr_stat'			    => $data['lockr_stat']
	        ,'comp_cd'			        => $data['comp_cd']
	        ,'bcoff_cd'			        => $data['bcoff_cd']
	        ,'lockr_knd'			    => $data['lockr_knd']
	        ,'lockr_gendr_set'			=> $data['lockr_gendr_set']
	        ,'lockr_no'			        => $data['lockr_no']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * 신규매출인지 재등록 매출인지 검사를 리스트
	 * @param array $data
	 * @return array
	 */
	public function list_sales_dv_rson(array $data)
	{
	    $sql = "SELECT 1RD_CATE_CD, 2RD_CATE_CD, CRE_DATETM, GRP_CATE_SET 
                FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
				";
	    
	    $query = $this->db->query($sql, [
	        'comp_cd'				=> $data['comp_cd']
	        ,'bcoff_cd'				=> $data['bcoff_cd']
	        ,'mem_sno'				=> $data['mem_sno']
	    ]);
	    array_push($data,$query);
	    return $query->getResultArray();
	}
	
	/**
	 * 가입회원 -> 현재회원
	 * where mem_sno
	 * @param array $data
	 */
	public function update_mem_join_to_now(array $data)
	{
	    $sql = "UPDATE mem_info_detl_tbl SET
					MEM_STAT		= :mem_stat:
					,REG_PLACE		= :reg_place:
					,REG_DATETM		= :reg_datetm:
    			WHERE COMP_CD 		= :comp_cd:
                    AND BCOFF_CD    = :bcoff_cd:
                    AND MEM_SNO     = :mem_sno:
				";
	    $query = $this->db->query($sql, [
	        'mem_stat'                 => $data['mem_stat']
	        ,'reg_place'               => $data['reg_place']
	        ,'reg_datetm'              => $data['reg_datetm']
	        ,'comp_cd'                 => $data['comp_cd']
	        ,'bcoff_cd'                => $data['bcoff_cd']
	        ,'mem_sno'                 => $data['mem_sno']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * 종료회원 -> 현재회원
	 * where mem_sno
	 * @param array $data
	 */
	public function update_mem_end_to_now(array $data)
	{
	    $sql = "UPDATE mem_info_detl_tbl SET
					MEM_STAT		= :mem_stat:
					,RE_REG_DATETM	= :re_reg_datetm:
    			WHERE COMP_CD 		= :comp_cd:
                    AND BCOFF_CD    = :bcoff_cd:
                    AND MEM_SNO     = :mem_sno:
				";
	    $query = $this->db->query($sql, [
	        'mem_stat'                 => $data['mem_stat']
	        ,'re_reg_datetm'           => $data['re_reg_datetm']
	        ,'comp_cd'                 => $data['comp_cd']
	        ,'bcoff_cd'                => $data['bcoff_cd']
	        ,'mem_sno'                 => $data['mem_sno']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	
	/**
	 * 이전 미수금 내역을 90 으로 업데이트 한다.
	 * where mem_sno
	 * @param array $data
	 */
	public function update_recvb_hist_tbl(array $data)
	{
	    $sql = "UPDATE recvb_hist_tbl SET
					RECVB_STAT     = :recvb_stat:
                    ,MOD_ID        = :mod_id:
					,MOD_DATETM    = :mod_datetm:
    			WHERE BUY_EVENT_SNO	= :buy_event_sno:
                    AND RECVB_STAT  = '00'
				";
	    $query = $this->db->query($sql, [
	        'recvb_stat'           => $data['recvb_stat']
	        ,'mod_id'              => $data['mod_id']
	        ,'mod_datetm'          => $data['mod_datetm']
	        ,'buy_event_sno'       => $data['buy_event_sno']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * 이전 미수금 내역 정보를 가져온다.
	 * @param array $data
	 * @return array
	 */
	public function select_recvb_hist_tbl(array $data)
	{
	    $sql = "SELECT *
                FROM recvb_hist_tbl
                WHERE BUY_EVENT_SNO	= :buy_event_sno:
                    AND RECVB_STAT  = '00'
				";
	    
	    $query = $this->db->query($sql, [
	        'buy_event_sno'			=> $data['buy_event_sno']
	    ]);
	    array_push($data,$query);
	    return $query->getResultArray();
	}
	
	/**
	 * 이전 미수금 내역을 90 또는 01 으로 업데이트 한다.
	 * where mem_sno
	 * @param array $data
	 */
	public function update_misu_buy_event(array $data)
	{
	    $sql = "UPDATE buy_event_mgmt_tbl SET
					BUY_AMT        = :buy_amt:
                    ,RECVB_AMT     = :recvb_amt:
                    ,MOD_ID        = :mod_id:
					,MOD_DATETM    = :mod_datetm:
    			WHERE BUY_EVENT_SNO	= :buy_event_sno:
				";
	    $query = $this->db->query($sql, [
	        'buy_amt'           => $data['buy_amt']
	        ,'recvb_amt'              => $data['recvb_amt']
	        ,'mod_id'              => $data['mod_id']
	        ,'mod_datetm'          => $data['mod_datetm']
	        ,'buy_event_sno'       => $data['buy_event_sno']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * 이전 매출 정보를 불러와서 매출구분사유 와 매출회원상태 를 가져온다.
	 * @param array $data
	 * @return array
	 */
	public function select_sales_mgmt_tbl(array $data)
	{
	    $sql = "SELECT SALES_DV_RSON,SALES_MEM_STAT
                FROM sales_mgmt_tbl
                WHERE RECVB_HIST_SNO	= :recvb_hist_sno:
                LIMIT 1
				";
	    
	    $query = $this->db->query($sql, [
	        'recvb_hist_sno'			=> $data['recvb_hist_sno']
	    ]);
	    array_push($data,$query);
	    return $query->getResultArray();
	}
	
	
	/**
	 * 구매상품관리 update
	 * 구매시 영향받은 상품들의 시작, 종료일을 업데이트 한다.
	 * where : BUY_EVENT_SNO
	 * @param array $data
	 */
	public function update_buy_event_mgmt_trans_end(array $data)
	{
	    $sql = "UPDATE buy_event_mgmt_tbl SET
					EVENT_STAT		    = :event_stat:
					,EVENT_STAT_RSON    = :event_stat_rson:
					,MOD_ID		   		= :mod_id:
					,MOD_DATETM		    = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		= :buy_event_sno:
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
				";
	    $query = $this->db->query($sql, [
	        'buy_event_sno' 		=> $data['buy_event_sno']
	        ,'comp_cd'			    => $data['comp_cd']
	        ,'bcoff_cd'			    => $data['bcoff_cd']
	        ,'event_stat'			=> $data['event_stat']
	        ,'event_stat_rson'		=> $data['event_stat_rson']
	        ,'mod_id'	    		=> $data['mod_id']
	        ,'mod_datetm'			=> $data['mod_datetm']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	
    /**
     * 환불상세내역 insert
     * @param array $data
     */
    public function insert_refund_detail_tbl(array $data)
    {
        $sql = "INSERT refund_detail_tbl SET
					REFUND_DETAIL_SNO	= :refund_detail_sno:
					,REFUND_MGMT_SNO	= :refund_mgmt_sno:
					,BUY_EVENT_SNO		= :buy_event_sno:
					,PAYMT_MGMT_SNO		= :paymt_mgmt_sno:
					,REFUND_MTHD		= :refund_mthd:
					,REFUND_AMT			= :refund_amt:
					,REFUND_DATE		= :refund_date:
					,REFUND_TYPE		= :refund_type:
					,PAYMT_VAN_SNO		= :paymt_van_sno:
					,REFUND_BANK_CD		= :refund_bank_cd:
					,REFUND_ACCT_NO		= :refund_acct_no:
					,REFUND_ACCT_NM		= :refund_acct_nm:
					,REFUND_STAT		= :refund_stat:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD			= :bcoff_cd:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'refund_detail_sno'		=> $data['refund_detail_sno']
            ,'refund_mgmt_sno'		=> $data['refund_mgmt_sno']
            ,'buy_event_sno'		=> $data['buy_event_sno']
            ,'paymt_mgmt_sno'		=> $data['paymt_mgmt_sno']
            ,'refund_mthd'			=> $data['refund_mthd']
            ,'refund_amt'			=> $data['refund_amt']
            ,'refund_date'			=> $data['refund_date']
            ,'refund_type'			=> $data['refund_type']
            ,'paymt_van_sno'		=> $data['paymt_van_sno']
            ,'refund_bank_cd'		=> $data['refund_bank_cd']
            ,'refund_acct_no'		=> $data['refund_acct_no']
            ,'refund_acct_nm'		=> $data['refund_acct_nm']
            ,'refund_stat'			=> $data['refund_stat']
            ,'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'cre_id'				=> $data['cre_id']
            ,'cre_datetm'			=> $data['cre_datetm']
            ,'mod_id'				=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 환불상세내역 조회
     * @param string $refund_mgmt_sno
     * @return array
     */
    public function get_refund_detail_list($refund_mgmt_sno)
    {
        $sql = "SELECT * FROM refund_detail_tbl
                WHERE REFUND_MGMT_SNO = :refund_mgmt_sno:
                ORDER BY REFUND_DETAIL_SNO";
        
        $query = $this->db->query($sql, [
            'refund_mgmt_sno' => $refund_mgmt_sno
        ]);
        
        return $query->getResultArray();
    }
    
    /**
     * 결제관리 정보 조회
     * @param string $paymt_mgmt_sno
     * @return array
     */
    public function get_paymt_mgmt_info($paymt_mgmt_sno)
    {
        $sql = "SELECT * FROM paymt_mgmt_tbl
                WHERE PAYMT_MGMT_SNO = :paymt_mgmt_sno:";
        
        $query = $this->db->query($sql, [
            'paymt_mgmt_sno' => $paymt_mgmt_sno
        ]);
        
        return $query->getResultArray();
    }
    
}