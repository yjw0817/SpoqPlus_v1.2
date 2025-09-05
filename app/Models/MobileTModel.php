<?php
namespace App\Models;

use CodeIgniter\Model;

class MobileTModel extends Model
{
    
    /**
     * 모바일 결제 결과 저장
     * @param array $data
     */
    public function insert_paymt_mobile_result(array $data)
    {
        $sql = "INSERT paymt_mobile_result_tbl SET
					P_STATUS                = :p_status:
                    ,P_AUTH_DT              = :p_auth_dt:
					,P_AUTH_NO 		        = :p_auth_no:
					,P_RMESG1		        = :p_rmesg1:
					,P_RMESG2		        = :p_rmesg2:
					,P_TID			        = :p_tid:
					,P_FN_CD1		        = :p_fn_cd1:
					,P_AMT		            = :p_amt:
					,P_TYPE		            = :p_type:
					,P_UNAME	            = :p_uname:
                    ,P_MID	                = :p_mid:
                    ,P_OID	                = :p_oid:
                    ,P_NOTI	                = :p_noti:
                    ,P_NEXT_URL	            = :p_next_url:
                    ,P_MNAME	            = :p_mname:
                    ,P_NOTEURL	            = :p_noteurl:
                    ,P_CARD_MEMBER_NUM	    = :p_card_member_num:
                    ,P_CARD_NUM	            = :p_card_num:
                    ,P_CARD_ISSUER_CODE	    = :p_card_issuer_code:
                    ,P_CARD_PURCHASE_CODE	= :p_card_purchase_code:
                    ,P_CARD_PRTC_CODE	    = :p_card_prtc_code:
                    ,P_CARD_INTEREST	    = :p_card_interest:
                    ,P_CARD_CHECKFLAG	    = :p_card_checkflag:
                    ,P_CARD_ISSUER_NAME	    = :p_card_issuer_name:
                    ,P_CARD_PURCHASE_NAME	= :p_card_purchase_name:
                    ,P_CARD_POINT	        = :p_card_point:
                    ,P_FN_NM	            = :p_fn_nm:
                    ,P_FLG_ESCROW	        = :p_flg_escrow:
                    ,P_MERCHANT_RESERVED	= :p_merchant_reserved:
                    ,P_CARD_APPLPRICE	    = :p_card_applprice:
                    ,MEM_SNO	            = :mem_sno:
					,CRE_ID			        = :cre_id:
					,CRE_DATETM		        = :cre_datetm:
				";
        $query = $this->db->query($sql, [
            'p_status'                => $data['p_status']
            ,'p_auth_dt'              => $data['p_auth_dt']
            ,'p_auth_no' 		      => $data['p_auth_no']
            ,'p_rmesg1'			      => $data['p_rmesg1']
            ,'p_rmesg2'			      => $data['p_rmesg2']
            ,'p_tid'			      => $data['p_tid']
            ,'p_fn_cd1'			      => $data['p_fn_cd1']
            ,'p_amt'		          => $data['p_amt']
            ,'p_type'		          => $data['p_type']
            ,'p_uname'	              => $data['p_uname']
            ,'p_mid'	              => $data['p_mid']
            ,'p_oid'	              => $data['p_oid']
            ,'p_noti'	              => $data['p_noti']
            ,'p_next_url'	          => $data['p_next_url']
            ,'p_mname'	              => $data['p_mname']
            ,'p_noteurl'	          => $data['p_noteurl']
            ,'p_card_member_num'	  => $data['p_card_member_num']
            ,'p_card_num'	          => $data['p_card_num']
            ,'p_card_issuer_code'	  => $data['p_card_issuer_code']
            ,'p_card_purchase_code'	  => $data['p_card_purchase_code']
            ,'p_card_prtc_code'	      => $data['p_card_prtc_code']
            ,'p_card_interest'	      => $data['p_card_interest']
            ,'p_card_checkflag'	      => $data['p_card_checkflag']
            ,'p_card_issuer_name'	  => $data['p_card_issuer_name']
            ,'p_card_purchase_name'	  => $data['p_card_purchase_name']
            ,'p_card_point'	          => $data['p_card_point']
            ,'p_fn_nm'	              => $data['p_fn_nm']
            ,'p_flg_escrow'	          => $data['p_flg_escrow']
            ,'p_merchant_reserved'	  => $data['p_merchant_reserved']
            ,'p_card_applprice'	      => $data['p_card_applprice']
            ,'mem_sno'	              => $data['mem_sno']
            ,'cre_id'			      => $data['cre_id']
            ,'cre_datetm'		      => $data['cre_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 모바일 결제 정보 가져오기
     * @param array $data
     */
    public function get_paymt_mobile_paysno(array $data)
    {
        $sql = "SELECT * FROM paymt_mobile_tbl
                WHERE PAY_MEM_SNO = :pay_mem_sno:
                AND PAY_APPNO_SNO = :pay_appno_sno:
                AND PAY_STAT = '90'
				";
        $query = $this->db->query($sql, [
            'pay_mem_sno'			=> $data['pay_mem_sno']
            ,'pay_appno_sno'			=> $data['pay_appno_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 모바일 결제 정보 가져오기
     * @param array $data
     */
    public function get_paymt_mobile(array $data)
    {
        $sql = "SELECT * FROM paymt_mobile_tbl
                WHERE PAY_COMP_CD = :pay_comp_cd:
                AND PAY_BCOFF_CD = :pay_bcoff_cd:
                AND PAY_MEM_SNO = :pay_mem_sno:
                AND PAY_MEM_ID = :pay_mem_id:
                AND PAY_APPNO_SNO = :pay_appno_sno:
                AND PAY_STAT = '90'
				";
        $query = $this->db->query($sql, [
            'pay_comp_cd' 			=> $data['pay_comp_cd']
            ,'pay_bcoff_cd'			=> $data['pay_bcoff_cd']
            ,'pay_mem_sno'			=> $data['pay_mem_sno']
            ,'pay_mem_id'			=> $data['pay_mem_id']
            ,'pay_appno_sno'			=> $data['pay_appno_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 모바일 결제 사전 거래
     * @param array $data
     */
    public function insert_paymt_mobile(array $data)
    {
        $sql = "INSERT paymt_mobile_tbl SET
					PAY_STAT            = :pay_stat:
                    ,PAY_COMP_CD        = :pay_comp_cd:
					,PAY_BCOFF_CD 		= :pay_bcoff_cd:
					,PAY_MEM_SNO		= :pay_mem_sno:
					,PAY_MEM_ID		    = :pay_mem_id:
					,PAY_MEM_NM			= :pay_mem_nm:
					,PAY_SELL_SNO		= :pay_sell_sno:
					,PAY_SEND_SNO		= :pay_send_sno:
					,PAY_APPNO_SNO		= :pay_appno_sno:
					,PAY_APPNO	        = :pay_appno:
                    ,PAY_CARD_AMT	    = :pay_card_amt:
                    ,PAY_ACCT_AMT	    = :pay_acct_amt:
                    ,PAY_ACCT_NO	    = :pay_acct_no:
                    ,PAY_CASH_AMT	    = :pay_cash_amt:
                    ,PAY_MISU_AMT	    = :pay_misu_amt:
                    ,PAY_EXR_S_DATE	    = :pay_exr_s_date:
                    ,PAY_REAL_SELL_AMT	= :pay_real_sell_amt:
                    ,PAY_LOCKR_NO	    = :pay_lockr_no:
                    ,PAY_LOCKR_GENDR_SET	= :pay_lockr_gendr_set:
                    ,PAY_ISSUE	        = :pay_issue:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'pay_stat'              => $data['pay_stat']
            ,'pay_comp_cd'          => $data['pay_comp_cd']
            ,'pay_bcoff_cd' 		=> $data['pay_bcoff_cd']
            ,'pay_mem_sno'			=> $data['pay_mem_sno']
            ,'pay_mem_id'			=> $data['pay_mem_id']
            ,'pay_mem_nm'			=> $data['pay_mem_nm']
            ,'pay_sell_sno'			=> $data['pay_sell_sno']
            ,'pay_send_sno'		    => $data['pay_send_sno']
            ,'pay_appno_sno'		=> $data['pay_appno_sno']
            ,'pay_appno'	        => $data['pay_appno']
            ,'pay_card_amt'	        => $data['pay_card_amt']
            ,'pay_acct_amt'	        => $data['pay_acct_amt']
            ,'pay_acct_no'	        => $data['pay_acct_no']
            ,'pay_cash_amt'	        => $data['pay_cash_amt']
            ,'pay_misu_amt'	        => $data['pay_misu_amt']
            ,'pay_exr_s_date'	    => $data['pay_exr_s_date']
            ,'pay_real_sell_amt'	=> $data['pay_real_sell_amt']
            ,'pay_lockr_no'	        => $data['pay_lockr_no']
            ,'pay_lockr_gendr_set'	=> $data['pay_lockr_gendr_set']
            ,'pay_issue'	        => $data['pay_issue']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }

    /**
     * 회원 모바일
     * PT첵 되지 않은 수업 출석
     * @param array $data
     */
    public function list_clas_attd_without_pt_check(array $data)
    {
        $sql = "SELECT B.*, 
                       A.CRE_DATETM AS ATTD_DATETM, 
                       A.ATTD_YN, 
                       A.AUTO_CHK, 
                       A.ATTD_MGMT_SNO, 
                       CONCAT(A.ATTD_YY, '-', A.ATTD_MM, '-', A.ATTD_DD) AS ATTD_YMD, 
                       C.CLAS_MGMT_SNO AS CLAS_MGMT_SNO
                FROM attd_mgmt_tbl AS A
                LEFT JOIN buy_event_mgmt_tbl AS B
                ON A.MEM_SNO = B.MEM_SNO AND A.COMP_CD = B.COMP_CD AND A.BCOFF_CD = B.BCOFF_CD AND A.BUY_EVENT_SNO = B.BUY_EVENT_SNO
                LEFT JOIN pt_clas_mgmt_tbl AS C ON B.BUY_EVENT_SNO = C.BUY_EVENT_SNO AND A.CLAS_MGMT_SNO = C.CLAS_MGMT_SNO
                WHERE
                A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:";
        
        // attd_ymd가 있을 때만 조건 추가
        if (isset($data['attd_ymd']) && !empty($data['attd_ymd'])) {
            $sql .= " AND A.ATTD_YMD = :attd_ymd:";
        }
        
        $sql .= " AND A.ATTD_YN = 'Y'
                AND B.EVENT_STAT = '00'
                AND  B.1RD_CATE_CD = 'PRVN'
                AND A.BUY_EVENT_SNO = :buy_event_sno:
                AND C.CLAS_MGMT_SNO IS NULL
                ORDER BY A.CRE_DATETM DESC";
        
        $params = [
            'comp_cd' 			=> $data['comp_cd'],
            'bcoff_cd'			=> $data['bcoff_cd'],
            'buy_event_sno'		=> $data['buy_event_sno']
        ];
        
        // attd_ymd가 있을 때만 파라미터 추가
        if (isset($data['attd_ymd']) && !empty($data['attd_ymd'])) {
            $params['attd_ymd'] = $data['attd_ymd'];
        }
        
        $query = $this->db->query($sql, $params);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    

/**
     * 회원 모바일
     * PT첵 된 수업출럭 리스트
     * @param array $data
     */
    public function list_clas_attd_with_pt_check(array $data)
    {
        $sql = "SELECT B.*, 
                       A.CRE_DATETM AS ATTD_DATETM, 
                       A.ATTD_YN, 
                       A.AUTO_CHK, 
                       A.ATTD_MGMT_SNO, 
                       CONCAT(A.ATTD_YY, '-', A.ATTD_MM, '-', A.ATTD_DD) AS ATTD_YMD, 
                       C.CLAS_MGMT_SNO AS CLAS_MGMT_SNO
                FROM attd_mgmt_tbl AS A
                LEFT JOIN buy_event_mgmt_tbl AS B
                ON A.MEM_SNO = B.MEM_SNO AND A.COMP_CD = B.COMP_CD AND A.BCOFF_CD = B.BCOFF_CD AND A.BUY_EVENT_SNO = B.BUY_EVENT_SNO
                LEFT JOIN pt_clas_mgmt_tbl AS C ON B.BUY_EVENT_SNO = C.BUY_EVENT_SNO AND A.CLAS_MGMT_SNO = C.CLAS_MGMT_SNO
                WHERE
                A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:";
        
        // attd_ymd가 있을 때만 조건 추가
        if (isset($data['attd_ymd']) && !empty($data['attd_ymd'])) {
            $sql .= " AND A.ATTD_YMD = :attd_ymd:";
        }
        
        $sql .= " AND A.ATTD_YN = 'Y'
                AND B.EVENT_STAT = '00'
                AND  B.1RD_CATE_CD = 'PRVN'
                AND A.BUY_EVENT_SNO = :buy_event_sno:
                AND C.CLAS_MGMT_SNO IS NOT NULL
                ORDER BY A.CRE_DATETM DESC";
        
        $params = [
            'comp_cd' 			=> $data['comp_cd'],
            'bcoff_cd'			=> $data['bcoff_cd'],
            'buy_event_sno'		=> $data['buy_event_sno']
        ];
        
        // attd_ymd가 있을 때만 파라미터 추가
        if (isset($data['attd_ymd']) && !empty($data['attd_ymd'])) {
            $params['attd_ymd'] = $data['attd_ymd'];
        }
        
        $query = $this->db->query($sql, $params);
        
        array_push($data,$query);
        return $query->getResultArray();
    }


    /**
     * 강사 모바일
     * 강사모바일 수업 출석회원 리스트
     * @param array $data
     */
    public function list_clas_attd(array $data)
    {
        $sql = "SELECT B.*, 
                       A.CRE_DATETM AS ATTD_DATETM, 
                       A.ATTD_YN, 
                       A.AUTO_CHK, 
                       A.ATTD_MGMT_SNO, 
                       CONCAT(A.ATTD_YY, '-', A.ATTD_MM, '-', A.ATTD_DD) AS ATTD_YMD, 
                       C.CLAS_MGMT_SNO AS CLAS_MGMT_SNO
                FROM attd_mgmt_tbl AS A
                LEFT JOIN buy_event_mgmt_tbl AS B
                ON A.MEM_SNO = B.MEM_SNO AND A.COMP_CD = B.COMP_CD AND A.BCOFF_CD = B.BCOFF_CD AND A.BUY_EVENT_SNO = B.BUY_EVENT_SNO
                LEFT JOIN pt_clas_mgmt_tbl AS C ON B.BUY_EVENT_SNO = C.BUY_EVENT_SNO AND A.CLAS_MGMT_SNO = C.CLAS_MGMT_SNO
                WHERE
                A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                AND A.ATTD_YMD = :attd_ymd:
                AND A.ATTD_YN = 'Y'
                AND A.AUTO_CHK = :auto_chk:
                AND B.EVENT_STAT = '00'
                AND  B.1RD_CATE_CD = 'PRVN'
                AND B.STCHR_ID = :stchr_id:
                ORDER BY A.CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'attd_ymd'			=> $data['attd_ymd']
            ,'auto_chk'			=> $data['auto_chk']
            ,'stchr_id'			=> $data['stchr_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 회원 모바일
     * 구매하기 리스트 조건 추가.
     * @param array $data
     */
    private function list_buy_event_where(array $data)
    {
        $add_where = "";
        
        if ($data['use_type'] == "H")
        {
            if ($data['use_prod'] == "0" )
            {
                $add_where = " AND USE_UNIT = 'D' ";
            } else
            {
                $add_where = " AND USE_UNIT = 'M' ";
                
                if ($data['use_prod'] < 13)
                {
                    $add_where .= " AND USE_PROD = '{$data['use_prod']}' ";
                } else
                {
                    $add_where .= " AND USE_PROD > 12 ";
                }
                
            }
        }
        
        if (isset($data['mem_disp_yn']))
        {
            $add_where .= " AND MEM_DISP_YN = '{$data['mem_disp_yn']}' ";
        }
        
        return $add_where;
    }
    
    /**
     * 회원 모바일
     * 구매하기 리스트
     * @param array $data
     */
    public function list_buy_event(array $data)
    {
        $add_where = $this->list_buy_event_where($data);
        $sql = "SELECT * FROM sell_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD = :1rd_cate_cd:
                -- AND EVENT_STEP != '1'
                AND SELL_YN = 'Y' AND GETDATE() BETWEEN IFNULL(SELL_S_DATE, '1900-01-01') AND IFNULL(SELL_E_DATE, '2100-01-01')
                {$add_where}
                ORDER BY 
                1RD_CATE_CD ASC
                ,2RD_CATE_CD ASC
                ,EVENT_REF_SNO DESC
                ,EVENT_STEP ASC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'1rd_cate_cd'		=> $data['1rd_cate_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 모바일
     * 회원 매출리스트 (환불 제외)
     * @param array $data
     */
    public function get_mem_acc(array $data)
    {
        $sql = "SELECT ACC_RTRCT_DV, ACC_RTRCT_MTHD FROM buy_event_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND EVENT_STAT != '99'
                AND ACC_RTRCT_DV != '99'
                AND (IFNULL(CLAS_DV, '') NOT IN ('21','22') AND 1RD_CATE_CD != 'PRVN')
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
     * 회원 모바일
     * 회원 매출리스트 (환불 제외)
     * @param array $data
     */
    public function sum_sales1_memsno(array $data)
    {
        $sql = "SELECT SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND SALES_DV != '90'
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
     * 회원 모바일
     * 회원 매출리스트 (환불만)
     * @param array $data
     */
    public function sum_sales2_memsno(array $data)
    {
        $sql = "SELECT SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND SALES_DV = '90'
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
     * 회원 모바일
     * 회원 매출리스트 (환불 제외)
     * @param array $data
     */
    public function list_sales1_memsno(array $data)
    {
        $sql = "SELECT B.* FROM sales_mgmt_tbl B LEFT JOIN buy_event_mgmt_tbl S ON S.BUY_EVENT_SNO = B.BUY_EVENT_SNO
                WHERE IFNULL(CASE WHEN S.EVENT_STAT = '00' OR S.EVENT_STAT = '01' THEN CASE WHEN DATE(NOW()) > DATE(EXR_E_DATE) THEN '99' ELSE CASE WHEN DATE(NOW()) > DATE(EXR_S_DATE) THEN '00' ELSE S.`EVENT_STAT` END END ELSE S.EVENT_STAT END, '00') <> '99'
                AND B.COMP_CD = :comp_cd:
                AND B.BCOFF_CD = :bcoff_cd:
                AND B.MEM_SNO = :mem_sno:
                AND B.SALES_DV != '90'
                ORDER BY B.CRE_DATETM DESC
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
     * 회원 모바일
     * 회원 매출리스트 (환불만)
     * @param array $data
     */
    public function list_sales2_memsno(array $data)
    {
        $sql = "SELECT * FROM sales_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND SALES_DV = '90'
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
     * 강사 모바일
     * 강사 매출리스트 (환불 제외)
     * @param array $data
     */
    public function sum_sales1_tmemid(array $data)
    {
        $sql = "SELECT SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND PTHCR_ID = :pthcr_id:
                AND SALES_DV != '90'
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'pthcr_id'			=> $data['pthcr_id']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 감사 모바일
     * 강사 매출리스트 (환불만)
     * @param array $data
     */
    public function sum_sales2_tmemid(array $data)
    {
        $sql = "SELECT SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND PTHCR_ID = :pthcr_id:
                AND SALES_DV = '90'
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'pthcr_id'			=> $data['pthcr_id']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일
     * 강사 매출리스트 (환불 제외)
     * @param array $data
     */
    public function list_sales1_tmemid(array $data)
    {
        $sql = "SELECT * FROM sales_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND PTHCR_ID = :pthcr_id:
                AND SALES_DV != '90'
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'pthcr_id'			=> $data['pthcr_id']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일
     * 강사 매출리스트 (환불 제외)
     * @param array $data
     */
    // public function list_sales2_tmemid(array $data)
    // {
    //     $sql = "SELECT * FROM sales_mgmt_tbl
    //             WHERE
    //             COMP_CD = :comp_cd:
    //             AND BCOFF_CD = :bcoff_cd:
    //             AND PTHCR_ID = :pthcr_id:
    //             AND SALES_DV = '90'
    //             AND CRE_DATETM BETWEEN :sdate: AND :edate:
    //             ORDER BY CRE_DATETM DESC
	// 			";
    //     $query = $this->db->query($sql, [
    //         'comp_cd' 			=> $data['comp_cd']
    //         ,'bcoff_cd'			=> $data['bcoff_cd']
    //         ,'pthcr_id'			=> $data['pthcr_id']
    //         ,'sdate'			=> $data['sdate']
    //         ,'edate'			=> $data['edate']
    //     ]);
        
    //     array_push($data,$query);
    //     return $query->getResultArray();
    // }

    /**
     * 강사 모바일
     * 강사 매출리스트 (환불 제외)
     * @param array $data
     */
    public function list_sales2_tmemid(array $data)
    {
        $sql = "SELECT S.SALES_MGMT_SNO, 
                        S.PAYMT_MGMT_SNO, 
                        S.BUY_EVENT_SNO, 
                        S.SELL_EVENT_SNO, 
                        S.SEND_EVENT_SNO, 
                        S.RECVB_HIST_SNO, 
                        S.PAYMT_VAN_SNO, 
                        S.COMP_CD, 
                        S.BCOFF_CD, 
                        CONCAT(T.M_CATE, CASE WHEN T.LOCKR_SET = '' THEN 'N' ELSE IFNULL(T.LOCKR_SET, 'N') END) AS 1RD_CATE_CD, 
                        S.SELL_EVENT_SNO AS 2RD_CATE_CD, 
                        S.CLAS_DV,
                        S.MEM_SNO,
                        S.MEM_ID,
                        S.PTHCR_ID,
                        S.SELL_EVENT_NM,
                        S.PAYMT_STAT,
                        S.PAYMT_MTHD,
                        S.ACC_SNO,
                        S.APPNO,
                        S.PAYMT_AMT,
                        S.SALES_DV,
                        S.SALES_DV_RSON,
                        S.SALES_MEM_STAT,
                        S.PAYMT_CHNL,
                        S.PAYMT_VAN_KND,
                        S.SALES_APLY_YN,
                        S.GRP_CATE_SET,
                        S.LOCKR_SET,
                        S.LOCKR_KND,
                        S.LOCKR_GENDR_SET,
                        S.LOCKR_NO,
                        S.CRE_ID,
                        S.CRE_DATETM,
                        S.MOD_ID,
                        S.MOD_DATETM
                    FROM sales_mgmt_tbl S LEFT JOIN sell_event_mgmt_tbl T ON S.SELL_EVENT_SNO = T.SELL_EVENT_SNO
                WHERE
                    S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                AND S.PTHCR_ID = :pthcr_id:
                AND S.SALES_DV = '90'
                AND S.CRE_DATETM BETWEEN :sdate: AND :edate:
                ORDER BY S.CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'pthcr_id'			=> $data['pthcr_id']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 모바일
     * 회원 휴회 리스트
     * @param array $data
     */
    public function list_domcy_memsno(array $data)
    {
        $sql = "SELECT A.*, B.SELL_EVENT_NM FROM domcy_mgmt_tbl A LEFT JOIN buy_event_mgmt_tbl B ON A.domcy_aply_buy_sno = B.buy_event_sno
                WHERE
                A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                AND A.MEM_SNO = :mem_sno:
                ORDER BY A.CRE_DATETM DESC
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
     * 회원 모바일 메인
     * 회원 추천상품 리스트
     * @param array $data
     */
    public function list_sendevent_memsno(array $data)
    {
        $sql = "SELECT S.`SEND_EVENT_MGMT_SNO`, S.`SELL_EVENT_SNO`, S.`COMP_CD`, S.`BCOFF_CD`,
                CONCAT(T.M_CATE, CASE WHEN T.LOCKR_SET = '' THEN 'N' ELSE IFNULL(T.LOCKR_SET, 'N') END) AS 1RD_CATE_CD, 
                S.SELL_EVENT_SNO AS 2RD_CATE_CD, 
                S.`MEM_SNO`, S.`MEM_ID`, S.`MEM_NM`, S.`STCHR_ID`, S.`STCHR_NM`, S.`PTCHR_ID`, S.`PTCHR_NM`, S.`SELL_EVENT_NM`, S.`SELL_AMT`, S.`USE_PROD`, S.`USE_UNIT`, S.`CLAS_CNT`, S.`ADD_SRVC_EXR_DAY`, S.`ADD_SRVC_CLAS_CNT`, S.`DOMCY_DAY`, S.`DOMCY_CNT`, S.`DOMCY_POSS_EVENT_YN`, S.`ACC_RTRCT_DV`, S.`ACC_RTRCT_MTHD`, S.`CLAS_DV`, S.`MEM_DISP_YN`, S.`SELL_S_DATE`, S.`SELL_E_DATE`, S.`EVENT_IMG`, S.`EVENT_ICON`, S.`GRP_CLAS_PSNNL_CNT`, S.`SELL_STAT`, S.`ORI_SELL_AMT`, S.`ORI_CLAS_CNT`, S.`ORI_DOMCY_DAY`, S.`ORI_DOMCY_CNT`, S.`SEND_BUY_S_DATE`, S.`SEND_BUY_E_DATE`, S.`SEND_STAT`, S.`GRP_CATE_SET`, S.`LOCKR_SET`, S.`LOCKR_KND`, S.`LOCKR_GENDR_SET`, S.`LOCKR_NO`, S.`CRE_ID`, S.`CRE_DATETM`, S.`MOD_ID`, S.`MOD_DATETM`
                FROM send_event_mgmt_tbl S LEFT JOIN sell_event_mgmt_tbl T ON S.SELL_EVENT_SNO = T.SELL_EVENT_SNO
                WHERE S.COMP_CD = :comp_cd:  AND S.SEND_BUY_E_DATE >= CURDATE()
                AND S.BCOFF_CD = :bcoff_cd: 
                AND S.MEM_SNO = :mem_sno:
                AND S.SEND_STAT = '00'
                AND T.M_CATE IS NOT NULL
                ORDER BY S.CRE_DATETM DESC
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
     * 회원 모바일 메인
     * 회원 추천상품 리스트
     * @param array $data
     */
    public function list_sendevent_memsno_01(array $data)
    {
        $sql = "SELECT S.SEND_EVENT_MGMT_SNO, 
                        S.SELL_EVENT_SNO, 
                        S.COMP_CD,
                        S.BCOFF_CD,
                        CONCAT(T.M_CATE, CASE WHEN T.LOCKR_SET = '' THEN 'N' ELSE IFNULL(T.LOCKR_SET, 'N') END) AS 1RD_CATE_CD, 
                        S.SELL_EVENT_SNO AS 2RD_CATE_CD, 
                        S.MEM_SNO,
                        S.MEM_ID,
                        S.MEM_NM,
                        S.STCHR_ID,
                        S.STCHR_NM,
                        S.PTCHR_ID,
                        S.PTCHR_NM,
                        S.SELL_EVENT_NM,
                        S.SELL_AMT,
                        S.USE_PROD,
                        S.USE_UNIT,
                        S.CLAS_CNT,
                        S.ADD_SRVC_EXR_DAY,
                        S.ADD_SRVC_CLAS_CNT,
                        S.DOMCY_DAY,
                        S.DOMCY_POSS_EVENT_YN,
                        S.ACC_RTRCT_DV,
                        S.ACC_RTRCT_MTHD,
                        S.CLAS_DV,
                        S.MEM_DISP_YN,
                        S.SELL_S_DATE,
                        S.SELL_E_DATE,
                        S.EVENT_IMG,
                        S.EVENT_ICON,
                        S.GRP_CLAS_PSNNL_CNT,
                        S.SELL_STAT,
                        S.ORI_SELL_AMT,
                        S.ORI_CLAS_CNT,
                        S.ORI_DOMCY_DAY,
                        S.ORI_DOMCY_CNT,
                        S.SEND_BUY_S_DATE,
                        S.SEND_BUY_E_DATE,
                        S.SEND_STAT,
                        S.GRP_CATE_SET,
                        S.LOCKR_SET,
                        S.LOCKR_KND,
                        S.LOCKR_GENDR_SET,
                        S.LOCKR_NO,
                        S.CRE_ID,
                        S.CRE_DATETM,
                        S.MOD_ID,
                        S.MOD_DATETM
                    FROM send_event_mgmt_tbl S LEFT JOIN sell_event_mgmt_tbl T ON S.SELL_EVENT_SNO = T.SELL_EVENT_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                AND S.MEM_SNO = :mem_sno:
                AND S.SEND_STAT = '01'
                ORDER BY S.CRE_DATETM DESC
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
     * 강사 모바일 메인
     * 수업회원출석 카운트
     * @param array $data
     */
    public function get_todayClasAttdCount(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM
                (
                    SELECT MEM_SNO FROM attd_mgmt_tbl AS A
                    WHERE 
                    A.COMP_CD = :comp_cd:
                    AND A.BCOFF_CD = :bcoff_cd:
                    AND ATTD_YMD = :attd_ymd:
                    AND MEM_SNO IN
                        (
                            SELECT MEM_SNO FROM buy_event_mgmt_tbl AS B
                            WHERE 
                            B.COMP_CD = :comp_cd:
                            AND B.BCOFF_CD = :bcoff_cd:
                            AND B.STCHR_ID = :stchr_id:
                            AND B.EVENT_STAT = '00'
                        )
                    GROUP BY MEM_SNO
                ) AS T
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'attd_ymd'			=> $data['attd_ymd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
        
        //array_push($data,$query);
        //return $query->getResultArray();
        
    }
    
    /**
     * 강사 모바일 메인
     * 오늘 수업한 수
     * @param array $data
     */
    public function get_todayClasCount(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM pt_clas_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YMD = :clas_chk_ymd:
                AND CANCEL_YN = 'N'
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'clas_chk_ymd'		=> $data['clas_chk_ymd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
        
        //array_push($data,$query);
        //return $query->getResultArray();
        
    }
    
    
    /**
     * 강사 모바일 메인
     * 수업종료 예정 상품 건수
     * @param array $data
     */
    public function get_clasEndCount(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM buy_event_mgmt_tbl 
                WHERE 
                COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd: 
                AND (IFNULL(CLAS_DV, '') IN ('21','22') OR 1RD_CATE_CD = 'PRVN')
                AND STCHR_ID = :stchr_id:
                AND EVENT_STAT = '00'
                AND (MEM_REGUL_CLAS_LEFT_CNT + SRVC_CLAS_LEFT_CNT) < 7
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
        
        //array_push($data,$query);
        //return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 메인
     * 강사 공지사항 리스트
     * @param array $data
     */
    public function list_tnotice(array $data)
    {
        $sql = "SELECT * FROM notice_tbl
                WHERE 
                COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND NOTI_DV = :noti_dv:
                AND NOTI_STAT = '00'
                AND :ndate: BETWEEN IF(IFNULL(NOTI_S_DATE,'2024-01-01') !='' , IFNULL(NOTI_S_DATE,'2024-01-01'), '2024-01-01')
                AND IF(IFNULL(NOTI_E_DATE,'3099-12-31') !='' , IFNULL(NOTI_E_DATE,'3099-12-31'), '3099-12-31')
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'ndate'			=> $data['ndate']
            ,'noti_dv'			=> $data['noti_dv']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 모바일 메인
     * 회원 공지사항 상세
     * @param array $data
     */
    public function get_mnotice(array $data)
    {
        $sql = "SELECT * FROM notice_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND NOTI_DV = '01'
                AND NOTI_STAT = '00'
                AND NOTI_SNO = :noti_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'noti_sno'			=> $data['noti_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 메인
     * 강사 공지사항 상세
     * @param array $data
     */
    public function get_tnotice(array $data)
    {
        $sql = "SELECT * FROM notice_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND NOTI_DV = '02'
                AND NOTI_STAT = '00'
                AND NOTI_SNO = :noti_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'noti_sno'			=> $data['noti_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 모바일 수업내역
     * 전체 수업남은 횟수
     * @param array $data
     * @return array
     */
    public function get_mem_clas_all_left_cnt(array $data)
    {
        $sql = "SELECT SUM(SRVC_CLAS_LEFT_CNT+MEM_REGUL_CLAS_LEFT_CNT) AS counter FROM buy_event_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND EVENT_STAT = '00'
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 회원 모바일 수업내역
     * 이번달 수업 진행
     * @param array $data
     * @return array
     */
    public function get_mem_clas_prgs_dd_cnt(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND CLAS_CHK_YY = :clas_chk_yy:
                AND CLAS_CHK_MM = :clas_chk_mm:
                AND CANCEL_YN = 'N'
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
            ,'clas_chk_yy'		=> $data['clas_chk_yy']
            ,'clas_chk_mm'		=> $data['clas_chk_mm']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 강사 모바일 수업상품관리
     * 전체 수업남은 횟수
     * @param array $data
     * @return array
     */
    public function get_clas_all_left_cnt(array $data)
    {
        $sql = "SELECT SUM(SRVC_CLAS_LEFT_CNT+MEM_REGUL_CLAS_LEFT_CNT) AS counter FROM buy_event_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND EVENT_STAT = '00'
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 강사 모바일 수업상품관리
     * 이번달 수업 진행
     * @param array $data
     * @return array
     */
    public function get_clas_prgs_dd_cnt(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YMD = :clas_chk_ymd:
                AND CANCEL_YN = 'N'
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'clas_chk_ymd'		=> $data['clas_chk_ymd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 강사 모바일 수업상품관리
     * 이번달 수업 진행
     * @param array $data
     * @return array
     */
    public function get_clas_prgs_cnt(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YY = :clas_chk_yy:
                AND CLAS_CHK_MM = :clas_chk_mm:
                AND CANCEL_YN = 'N'
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'clas_chk_yy'		=> $data['clas_chk_yy']
            ,'clas_chk_mm'		=> $data['clas_chk_mm']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 회원 모바일 수업진행내역
     * @param array $data
     */
    public function list_attd_mem_use(array $data)
    {
        $sql = "SELECT C.SELL_EVENT_NM, C.STCHR_NM, B.MEM_REGUL_CLAS_PRGS_CNT, B.MEM_REGUL_CLAS_LEFT_CNT, B.SRVC_CLAS_PRGS_CNT, B.SRVC_CLAS_LEFT_CNT, A.CRE_DATETM,
                B.CLAS_MGMT_SNO, A.BUY_EVENT_SNO, B.CANCEL_YN, C.STCHR_ID, A.ATTD_YMD
                FROM attd_mgmt_tbl A
                INNER JOIN buy_event_mgmt_tbl C
                ON A.BUY_EVENT_SNO = C.BUY_EVENT_SNO
                LEFT JOIN pt_clas_mgmt_tbl B
                ON A.CLAS_MGMT_SNO = B.CLAS_MGMT_SNO
                WHERE
                A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                AND A.MEM_SNO = :mem_sno:
                AND A.ATTD_YY = :clas_chk_yy:
                AND A.ATTD_MM = :clas_chk_mm:
                AND A.M_CATE = 'PRV'
                -- AND B.CANCEL_YN = 'N'
                ORDER BY A.CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
            ,'clas_chk_yy'		=> $data['clas_chk_yy']
            ,'clas_chk_mm'		=> $data['clas_chk_mm']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 수업진행내역 (총 진행 횟수)
     * @param array $data
     * AND CLAS_CHK_YMD = :clas_chk_ymd:
     */
    public function list_attd_tchr_use_month_cnt(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YY = :clas_chk_yy:
                AND CLAS_CHK_MM = :clas_chk_mm:
                AND CANCEL_YN = 'N'
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'clas_chk_yy'		=> $data['clas_chk_yy']
            ,'clas_chk_mm'		=> $data['clas_chk_mm']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 수업진행내역 (총 진행 금액)
     * @param array $data
     * AND CLAS_CHK_YMD = :clas_chk_ymd:
     */
    public function list_attd_tchr_use_month_cost(array $data)
    {
        $sql = "SELECT SUM(TCHR_1TM_CLAS_PRGS_AMT) as sum_cost FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YY = :clas_chk_yy:
                AND CLAS_CHK_MM = :clas_chk_mm:
                AND CANCEL_YN = 'N'
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'clas_chk_yy'		=> $data['clas_chk_yy']
            ,'clas_chk_mm'		=> $data['clas_chk_mm']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 수업진행내역
     * @param array $data
     * AND CLAS_CHK_YMD = :clas_chk_ymd:
     */
    public function list_attd_tchr_use_month(array $data)
    {
        $sql = "SELECT * FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YY = :clas_chk_yy:
                AND CLAS_CHK_MM = :clas_chk_mm:
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'clas_chk_yy'		=> $data['clas_chk_yy']
            ,'clas_chk_mm'		=> $data['clas_chk_mm']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 수업진행내역
     * @param array $data
     * AND CLAS_CHK_YMD = :clas_chk_ymd:
     */
    public function list_attd_tchr_use(array $data)
    {
        $sql = "SELECT * FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YMD = :clas_chk_ymd:
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
            ,'clas_chk_ymd'		=> $data['clas_chk_ymd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 수업상품관리
     * 수업 상품 현황 (이용중)
     * @param array $data
     */
    public function list_buy_event_tchr_use(array $data)
    {
        $sql = "SELECT * FROM buy_event_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND STCHR_ID = :stchr_id:
                AND EVENT_STAT = '00'
                AND (IFNULL(CLAS_DV, '') IN ('21','22') OR 1RD_CATE_CD = 'PRVN')
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 판매상품 현황
     * 판매상품 리스트
     * @param array $data
     */
    public function list_buy_event_pchr(array $data)
    {
        $sql = "SELECT B.*, (SELECT A.M_CATE FROM sell_event_mgmt_tbl A WHERE A.SELL_EVENT_SNO = B.SELL_EVENT_SNO) AS M_CATE 
        FROM buy_event_mgmt_tbl B
                WHERE 
                B.COMP_CD = :comp_cd:
                AND B.BCOFF_CD = :bcoff_cd:
                AND B.PTCHR_ID = :ptchr_id:
                AND B.CRE_DATETM BETWEEN :sdate: AND :edate:
                ORDER BY B.CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'ptchr_id'			=> $data['ptchr_id']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 판매상품 현황
     * 판매상품 금액
     * @param array $data
     */
    public function sum_buy_event_pchr(array $data)
    {

        /*$sql = "SELECT SUM(PAYMT_AMT) AS sum_cost FROM buy_event_mgmt_tbl
        WHERE 
        COMP_CD = :comp_cd:
        AND BCOFF_CD = :bcoff_cd:
        AND PTHCR_ID = :ptchr_id:
        AND CRE_DATETM BETWEEN :sdate: AND :edate:
        ";*/
        $sql = "SELECT SUM(REAL_SELL_AMT) AS sum_cost FROM buy_event_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND PTCHR_ID = :ptchr_id:
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'ptchr_id'			=> $data['ptchr_id']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 강사 모바일 종료예정현황
     * 이용권 (이번달)
     * @param array $data
     */
    public function end_buy_event_pchr(array $data)
    {
        $sql = "SELECT B.*, C.M_CATE
                FROM buy_event_mgmt_tbl B INNER JOIN sell_event_mgmt_tbl C ON B.SELL_EVENT_SNO = C.SELL_EVENT_SNO
                WHERE B.COMP_CD = :comp_cd:
                AND B.BCOFF_CD = :bcoff_cd:
                AND B.PTCHR_ID = :ptchr_id:
                AND (IFNULL(B.CLAS_DV, '') NOT IN ('21','22') AND B.1RD_CATE_CD != 'PRVN') 
                AND B.EXR_E_DATE < :edate: AND B.EXR_E_DATE > NOW() AND B.EXR_E_DATE IS NOT NULL  -- 회원권이 살아 있더라도 날짜가 지나면 빠짐
                AND B.EVENT_STAT = '00'
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'ptchr_id'			=> $data['ptchr_id']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 강사 모바일 종료예정현황
     * 수업상품
     * @param array $data
     */
    public function end_buy_event_schr(array $data)
    {
        $sql = "SELECT *, (SELECT M_CATE FROM sell_event_mgmt_tbl A WHERE B.SELL_EVENT_SNO = A.SELL_EVENT_SNO) AS M_CATE
                FROM buy_event_mgmt_tbl B  
                WHERE
                COMP_CD = :comp_cd:
                AND B.BCOFF_CD = :bcoff_cd:
                AND (IFNULL(B.CLAS_DV, '') IN ('21','22') OR (SELECT M_CATE FROM sell_event_mgmt_tbl A WHERE B.SELL_EVENT_SNO = A.SELL_EVENT_SNO) = 'PRV') 
                -- AND B.EXR_E_DATE > NOW() AND B.EXR_E_DATE IS NOT NULL  -- 회원권이 살아 있더라도 날짜가 지나면 빠짐 PT도 종료일이 있지만 현재는 제외
                AND B.STCHR_ID = :stchr_id:
                AND B.EVENT_STAT = '00'
                AND (B.MEM_REGUL_CLAS_LEFT_CNT + B.SRVC_CLAS_LEFT_CNT) < 7
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'stchr_id'			=> $data['stchr_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 강사 모바일 보내기 상품 현황
     * 보내기 상품 리스트 (구매안함)
     * @param array $data
     */
    public function list_send_event_tchr1(array $data)
    {
        $sql = "SELECT * FROM send_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND (CRE_ID = :cre_id: OR STCHR_ID = :cre_id: OR PTCHR_ID = :cre_id:)
                AND SEND_STAT != '01'
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'cre_id'			=> $data['cre_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 모바일 보내기 상품 현황
     * 보내기 상품 리스트 (구매함)
     * @param array $data
     */
    public function list_send_event_tchr2(array $data)
    {
        $sql = "SELECT * FROM send_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND (CRE_ID = :cre_id: OR STCHR_ID = :cre_id: OR PTCHR_ID = :cre_id:)
                AND SEND_STAT = '01'
                ORDER BY CRE_DATETM DESC
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'cre_id'			=> $data['cre_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    
    
    
	
    
}