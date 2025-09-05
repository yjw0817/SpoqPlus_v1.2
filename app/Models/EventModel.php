<?php
namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    /**
     * COMP_CD를 이용하여 회사이름을 가져온다.
     * @param array $data
     * @return array
     */
    public function get_comp_name_only(array $data)
    {
        $sql = "SELECT COMP_NM FROM smgmt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * COMP_CD, BCOFF_CD 를 이용하여 지점명을 가져온다.
     * @param array $data
     * @return array
     */
    public function get_bcoff_name_only(array $data)
    {
        $sql = "SELECT BCOFF_NM FROM bcoff_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 탈퇴전에 이용중 , 예약됨 상품의 카운트를 가녀온다.
     */
    public function list_sece_buy_event_for_memsno(array $data)
    {
        $sql = "SELECT 
                B.COMP_CD
                , B.BCOFF_CD
                , COUNT(B.BCOFF_CD) AS counter 
                FROM buy_event_mgmt_tbl AS B
                WHERE B.MEM_SNO = :mem_sno:
                AND B.EVENT_STAT IN ('00','01')
                GROUP BY B.COMP_CD , B.BCOFF_CD
				";
        
        $query = $this->db->query($sql, [
            'mem_sno'				=> $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 강사 퇴사처리를 할떄 사용함.
     * 퇴사처리할때 자신의 수업강사로 이용중, 예약됨 상품에 대한 리스트를 가져옴.
     * @param array $data
     * @return array
     */
    public function list_buy_event_stchr_id_sece(array $data)
    {
        $sql = "SELECT * FROM buy_event_mgmt_tbl
                WHERE EVENT_STAT IN ('00','01')
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND (IFNULL(CLAS_DV, '') IN ('21','22') OR 1RD_CATE_CD = 'PRVN')
                AND STCHR_ID = :stchr_id:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'stchr_id'			    => $data['stchr_id']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    public function event_stat_group_count(array $data)
    {
        $sql = "SELECT EVENT_STAT , COUNT(EVENT_STAT) AS counter FROM buy_event_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                GROUP BY EVENT_STAT
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'            => $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 환불 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_refund_mgmt_for_buy_sno(array $data)
    {
        $sql = "SELECT * FROM refund_mgmt_tbl
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
    
    
    /**
     * 강사수당 설정 대분류 상품 가져오기
     * @param array $data
     */
    public function sarly_cate_1rd(array $data)
    {
        $sql = "SELECT DISTINCT 1RD_CATE_CD, CATE_NM, LOCKR_SET, USE_YN 
                -- SELECT DISTINCT * 
                FROM 1rd_event_cate_tbl
                WHERE COMP_CD = :comp_cd:
                -- AND GRP_CATE_SET = '1RD'
                AND USE_YN = 'Y'
                ORDER BY CASE WHEN 1RD_CATE_CD = 'OPTN' OR 1RD_CATE_CD = 'OPTY' THEN '1' ELSE 0 END, CATE_NM
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사수당 설정 대분류 상품 가져오기
     * @param array $data
     */
    public function sarly_cate_2rd(array $data)
    {
        $sql = "SELECT * FROM 2rd_event_cate_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND GRP_CATE_SET = '2RD'
                AND USE_YN = 'Y'
                ORDER BY CASE WHEN 1RD_CATE_CD = 'OPTN' OR 1RD_CATE_CD = 'OPTY' THEN '1' ELSE 0 END, CATE_NM
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 회원이 종료회원상태인지 체크한다.
     * 회원 일련번호를 이용한다.
     * @param array $data
     * @return array
     */
    public function end_chk_event_mem_sno(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND EVENT_STAT != '99'
				";
                
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'mem_sno'              => $data['mem_sno']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * =================================================================================
     * misu_manage [미수금관리] - START
     * =================================================================================
     */
    
    private function list_all_recvb_mgmt_where(array $data)
    {
        $add_where = "";
        
        // 구매 회원명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND (T.MEM_NM LIKE '%{$data['snm']}%' OR T.MEM_ID LIKE '%{$data['snm']}%') ";
            }
        }
        
        // 판매상품명
        if (isset($data['senm']))
        {
            if ($data['senm'] != '')
            {
                $add_where .= " AND T.SELL_EVENT_NM LIKE '%{$data['senm']}%' ";
            }
        }
        
        // 미수금처리상태
        if (isset($data['srstat']))
        {
            if ($data['srstat'] != '')
            {
                $add_where .= " AND T.RECVB_STAT = '{$data['srstat']}' ";
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
                        case "ms":
                            $add_where .= " AND T.MOD_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 미수금관리 리스트
     * @param array $data
     */
    public function list_all_recvb_mgmt_sum_cost(array $data)
    {
        $add_where = $this->list_all_recvb_mgmt_where($data);
        $sql = "SELECT SUM(T.RECVB_PAYMT_AMT) as sum_cost FROM recvb_hist_tbl T
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                ]);
                $count = $query->getResultArray();
                return $count[0]['sum_cost'];
    }
    
    /**
     * 미수금관리 리스트
     * @param array $data
     */
    public function list_all_recvb_mgmt_count(array $data)
    {
        $add_where = $this->list_all_recvb_mgmt_where($data);
    	$sql = "SELECT COUNT(*) as counter FROM recvb_hist_tbl T
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
     * 미수금관리 리스트
     * @param array $data
     */
    public function list_all_recvb_mgmt(array $data)
    {
        $add_where = $this->list_all_recvb_mgmt_where($data);
        $sql = "SELECT T.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM recvb_hist_tbl AS T
                LEFT JOIN mem_info_detl_tbl AS MID ON T.MEM_SNO = MID.MEM_SNO
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY T.MOD_DATETM DESC, T.RECVB_STAT
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
     * misu_manage [미수금관리] - END
     * =================================================================================
     */
    
    
    /**
     * =================================================================================
     * buy_eventhist_manage [구매내역관리] - START
     * =================================================================================
     */
    
    private function list_all_paymt_mgmt_where(array $data)
    {
        $add_where = "";
        
        // 구매 회원명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND T.MEM_NM LIKE '%{$data['snm']}%' ";
            }
        }
        
        // 판매상품명
        if (isset($data['senm']))
        {
            if ($data['senm'] != '')
            {
                $add_where .= " AND T.SELL_EVENT_NM LIKE '%{$data['senm']}%' ";
            }
        }
        
        // 결제상태
        if (isset($data['spstat']))
        {
            if ($data['spstat'] != '')
            {
                $add_where .= " AND T.PAYMT_STAT = '{$data['spstat']}' ";
            }
        }
        
        // 결제채널
        if (isset($data['spchnl']))
        {
            if ($data['spchnl'] != '')
            {
                $add_where .= " AND T.PAYMT_CHNL = '{$data['spchnl']}' ";
            }
        }
        
        // 결제수단
        if (isset($data['spmthd']))
        {
            if ($data['spmthd'] != '')
            {
                $add_where .= " AND T.PAYMT_MTHD = '{$data['spmthd']}' ";
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
                        case "ps":
                            $add_where .= " AND T.PAYMT_DATE BETWEEN '{$data['sdate']}' AND '{$data['edate']}' ";
                            break;
                        case "pr":
                            $add_where .= " AND T.REFUND_DATE BETWEEN '{$data['sdate']}' AND '{$data['edate']}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 구매내역관리 리스트
     * @param array $data
     */
    public function list_all_paymt_mgmt_count(array $data)
    {
        $add_where = $this->list_all_paymt_mgmt_where($data);
    	$sql = "SELECT COUNT(*) as counter FROM paymt_mgmt_tbl T
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
     * 구매내역관리 리스트
     * @param array $data
     */
    public function list_all_paymt_mgmt(array $data)
    {
        $add_where = $this->list_all_paymt_mgmt_where($data);
        $sql = "SELECT T.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM paymt_mgmt_tbl AS T
                LEFT JOIN mem_info_detl_tbl AS MID ON T.MEM_SNO = MID.MEM_SNO
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY T.MOD_DATETM DESC
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
     * buy_eventhist_manage [구매내역관리] - END
     * =================================================================================
     */
    
    
    /**
     * [환불] 구매내역관리 리스트
     * @param array $data
     */
    public function list_refund_paymt_mgmt(array $data)
    {
        $sql = "SELECT * FROM paymt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND BUY_EVENT_SNO = :buy_event_sno:
                ORDER BY PAYMT_MTHD ASC, CRE_DATETM DESC
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
     * ===========================================================================================
     * @param array $var
     * @return string
     */
    
    public function add_where_buy_event3($var)
    {
        $return_where = "";
        
        $return_where .= " AND (IFNULL(CLAS_DV, '') IN ('21','22') OR 1RD_CATE_CD = 'PRVN') ";
        
        if (isset($var["sch_ccnt"]))
        {
            if($var['sch_ccnt'] != '')
            {
                //$return_where .= " AND EXR_E_DATE BETWEEN '{$var['sch_end_sdate']}' AND '{$var['sch_end_edate']}' ";
                $return_where .= " AND (MEM_REGUL_CLAS_LEFT_CNT + SRVC_CLAS_LEFT_CNT) < '{$var['sch_ccnt']}' ";
            }
        }
        /*
        if (isset($var["acc_rtrct_dv"]))
        {
            if($var['acc_rtrct_dv'] != '')
            {
                $return_where .= " AND ACC_RTRCT_DV = '{$var['acc_rtrct_dv']}'";
            }
        }
        
        if (isset($var["acc_rtrct_mthd"]))
        {
            if($var['acc_rtrct_mthd'] != '')
            {
                $return_where .= " AND ACC_RTRCT_MTHD = '{$var['acc_rtrct_mthd']}'";
            }
        }*/
        
        if (isset($var["search_cate1"]))
        {
            if($var['search_cate1'] != '')
            {
                $return_where .= " AND 1RD_CATE_CD = '{$var['search_cate1']}' ";
            }
        }
        
        if (isset($var["search_cate2"]))
        {
            if($var['search_cate2'] != '')
            {
                $return_where .= " AND 2RD_CATE_CD = '{$var['search_cate2']}' ";
            }
        }
        
        
        return $return_where;
    }
    
    /**
     * 상품 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_all_buy_event3_count(array $data)
    {
        $add_where = $this->add_where_buy_event3($data);
        $sql = "SELECT COUNT(*) AS counter FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $_SESSION['comp_cd']
                    ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['counter'];
    }
    
    /**
     * 구매상품 관리 리스트
     * @param array $data
     */
    public function list_all_buy_event3(array $data)
    {
        $add_where = $this->add_where_buy_event3($data);
        
        $sql = "SELECT T.*, MID.MEM_TELNO, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM (SELECT * FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where} ) AS T 
                INNER JOIN mem_info_detl_tbl AS MID ON T.MEM_SNO = MID.MEM_SNO
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
    
    // ===========================================================================================
    
    
    /**
     * ===========================================================================================
     * @param array $var
     * @return string
     */
    
    public function add_where_buy_event1($var)
    {
        $return_where = "";
        if (isset($var["sch_end_sdate"]))
        {
            if($var['sch_end_sdate'] != '')
            {
                //$return_where .= " AND EXR_E_DATE BETWEEN '{$var['sch_end_sdate']}' AND '{$var['sch_end_edate']}' ";
                $return_where .= " AND EXR_E_DATE > '{$var['sch_end_sdate']}' ";
            }
        }
        
        /*
        if (isset($var["acc_rtrct_dv"]))
        {
            if($var['acc_rtrct_dv'] != '')
            {
                $return_where .= " AND ACC_RTRCT_DV = '{$var['acc_rtrct_dv']}'";
            }
        }
        if (isset($var["acc_rtrct_mthd"]))
        {
            if($var['acc_rtrct_mthd'] != '')
            {
                $return_where .= " AND ACC_RTRCT_MTHD = '{$var['acc_rtrct_mthd']}'";
            }
        }
        */
        
        if (isset($var["search_cate1"]))
        {
            if($var['search_cate1'] != '')
            {
                $return_where .= " AND 1RD_CATE_CD = '{$var['search_cate1']}' ";
            }
        }
        
        if (isset($var["search_cate2"]))
        {
            if($var['search_cate2'] != '')
            {
                $return_where .= " AND 2RD_CATE_CD = '{$var['search_cate2']}' ";
            }
        }
        
        
        return $return_where;
    }
    
    /**
     * 상품 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_all_buy_event1_count(array $data)
    {
        $add_where = $this->add_where_buy_event1($data);
        $sql = "SELECT COUNT(*) AS counter FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $_SESSION['comp_cd']
                    ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['counter'];
    }
    
    /**
     * 구매상품 관리 리스트
     * @param array $data
     */
    public function list_all_buy_event1(array $data)
    {
        $add_where = $this->add_where_buy_event1($data);
        
        $sql = "SELECT T.*, MID.MEM_TELNO, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM (SELECT * FROM buy_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where} ) AS T 
                INNER JOIN mem_info_detl_tbl AS MID ON T.MEM_SNO = MID.MEM_SNO
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
    
    // ===========================================================================================
    
    /**
     * 상품 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_all_buy_event2_count(array $data)
    {
        $add_where = $this->add_where_buy_event2($data);
        $add_where2 = $this->add_where_buy_event22($data);
        
        $sql = "SELECT COUNT(*) AS counter FROM mem_info_detl_tbl AS M
                WHERE M.MEM_SNO
                IN
                (
                SELECT T1.MEM_SNO FROM
                (
                	SELECT MEM_SNO FROM buy_event_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd: 
                    {$add_where}
                	GROUP BY MEM_SNO
                ) AS T1
                LEFT OUTER JOIN 
                (
                	SELECT MEM_SNO, COUNT(MEM_SNO) AS counter FROM buy_event_mgmt_tbl
                	WHERE 1=1 {$add_where2}
                	AND EVENT_STAT IN ('00','01')
                	GROUP BY MEM_SNO
                ) T2
                ON T1.MEM_SNO = T2.MEM_SNO
                WHERE IFNULL(counter,'') = ''
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                )
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $_SESSION['comp_cd']
                    ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['counter'];
    }
    
    
    /**
     * 구매상품 관리 리스트
     * @param array $data
     */
    public function list_all_buy_event2(array $data)
    {
        $add_where = $this->add_where_buy_event2($data);
        $add_where2 = $this->add_where_buy_event22($data);
        
        $sql = "SELECT M.* FROM mem_info_detl_tbl AS M
                WHERE M.MEM_SNO
                IN
                (
                SELECT T1.MEM_SNO FROM
                (
                	SELECT MEM_SNO FROM buy_event_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: 
                    AND BCOFF_CD = :bcoff_cd: 
                    {$add_where}
                	GROUP BY MEM_SNO
                ) AS T1
                LEFT OUTER JOIN 
                (
                	SELECT MEM_SNO, COUNT(MEM_SNO) AS counter FROM buy_event_mgmt_tbl
                	WHERE 1=1 {$add_where2}
                	AND EVENT_STAT IN ('00','01')
                	GROUP BY MEM_SNO
                ) T2
                ON T1.MEM_SNO = T2.MEM_SNO
                WHERE IFNULL(counter,'') = ''
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                )
                ORDER BY CRE_DATETM DESC
                limit {$data['limit_s']} , {$data['limit_e']}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                ]);
                array_push($data,$query);
                return $query->getResultArray();
    }
    
    public function add_where_buy_event2($var)
    {
        $return_where = "";
        if (isset($var["sch_end_sdate"]))
        {
            if($var['sch_end_sdate'] != '')
            {
                $return_where .= " AND 
                	( 
                	'{$var['sch_end_sdate']}' BETWEEN EXR_S_DATE AND EXR_E_DATE
                	OR '{$var['sch_end_sdate']}' < EXR_S_DATE
                	) ";
            }
        }
        /*
        if (isset($var["acc_rtrct_dv"]))
        {
            if($var['acc_rtrct_dv'] != '')
            {
                $return_where .= " AND ACC_RTRCT_DV = '{$var['acc_rtrct_dv']}'";
            }
        }
        
        if (isset($var["acc_rtrct_mthd"]))
        {
            if($var['acc_rtrct_mthd'] != '')
            {
                $return_where .= " AND ACC_RTRCT_MTHD = '{$var['acc_rtrct_mthd']}'";
            }
        }*/
        
        if (isset($var["search_cate1"]))
        {
            if($var['search_cate1'] != '')
            {
                $return_where .= " AND 1RD_CATE_CD = '{$var['search_cate1']}' ";
            }
        }
        
        if (isset($var["search_cate2"]))
        {
            if($var['search_cate2'] != '')
            {
                $return_where .= " AND 2RD_CATE_CD = '{$var['search_cate2']}'  ";
            }
        }
        
        
        return $return_where;
    }
    
    public function add_where_buy_event22($var)
    {
        $return_where = "";
        
        if (isset($var["search_cate11"]))
        {
            if($var['search_cate11'] != '')
            {
                $return_where .= " AND 1RD_CATE_CD = '{$var['search_cate11']}' ";
            }
        }
        
        if (isset($var["search_cate22"]))
        {
            if($var['search_cate22'] != '')
            {
                $return_where .= " AND 2RD_CATE_CD = '{$var['search_cate22']}' ";
            }
        }
        
        return $return_where;
    }

      /**
     * 상품 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_all_buy_event4_count(array $data)
    {
        $add_where = $this->add_where_buy_event4($data);
        $add_where2 = $this->add_where_buy_event44($data);
        
        $sql = "SELECT COUNT(BUY_EVENT_SNO) AS counter
                FROM _mem_unrenewed_items
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                " . $add_where . " -- Dynamically add the first WHERE clause
                " . $add_where2 . " -- Dynamically add the second WHERE clause
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $_SESSION['comp_cd']
                    ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['counter'];
    }
    
    
    /**
     * 구매상품 관리 리스트
     * @param array $data
     */
    public function list_all_buy_event4(array $data)
    {
        $add_where = $this->add_where_buy_event4($data);
        $add_where2 = $this->add_where_buy_event44($data);
        
        $sql = "SELECT V.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM _mem_unrenewed_items AS V
                LEFT JOIN mem_info_detl_tbl AS MID ON V.MEM_SNO = MID.MEM_SNO
                WHERE V.COMP_CD = :comp_cd:
                AND V.BCOFF_CD = :bcoff_cd:
                " . $add_where . " -- Dynamically add the first WHERE clause
                " . $add_where2 . " -- Dynamically add the second WHERE clause
                ORDER BY V.CRE_DATETM DESC
                limit {$data['limit_s']} , {$data['limit_e']}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                ]);
                array_push($data,$query);
                return $query->getResultArray();
    }

    public function add_where_buy_event4($var)
    {
        $return_where = "";
        if (isset($var["end_days"]))
        {
            if($var['end_days'] != '')
            {
                $return_where .= " AND 
                	( 
                	'{$var['end_days']}' >=  END_DAYS
                	) ";
            }
        }
        
        
        if (isset($var["search_cate1"]))
        {
            if($var['search_cate1'] != '')
            {
                $return_where .= " AND 1RD_CATE_CD = '{$var['search_cate1']}' ";
            }
        }
        
        if (isset($var["search_cate2"]))
        {
            if($var['search_cate2'] != '')
            {
                $return_where .= " AND 2RD_CATE_CD = '{$var['search_cate2']}' ";
            }
        }
        
        
        return $return_where;
    }
    
    public function add_where_buy_event44($var)
    {
        $return_where = "";
        if (isset($var["end_days"]))
        {
            if($var['end_days'] != '')
            {
                $return_where .= " AND 
                	( 
                	'{$var['end_days']}' >=  END_DAYS
                	) ";
            }
        }

        if (isset($var["search_cate11"]))
        {
            if($var['search_cate11'] != '')
            {
                $return_where .= " AND 1RD_CATE_CD = '{$var['search_cate11']}' ";
            }
        }
        
        if (isset($var["search_cate22"]))
        {
            if($var['search_cate22'] != '')
            {
                $return_where .= " AND 2RD_CATE_CD = '{$var['search_cate22']}' ";
            }
        }
        
        return $return_where;
    }
    
    // ===========================================================================================
    
    private function list_all_buy_event_where(array $data)
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
        
        // 판매상품명
        if (isset($data['senm']))
        {
            if ($data['senm'] != '')
            {
                $add_where .= " AND T.SELL_EVENT_NM LIKE '%{$data['senm']}%' ";
            }
        }
        
        // 상품상태
        if (isset($data['sestat']))
        {
            if ($data['sestat'] != '')
            {
                $add_where .= " AND T.EVENT_STAT = '{$data['sestat']}' ";
            }
        }
        
        // 상품상태사유
        if (isset($data['sestatr']))
        {
            if ($data['sestatr'] != '')
            {
                $add_where .= " AND T.EVENT_STAT_RSON = '{$data['sestatr']}' ";
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
                        case "exs":
                            $add_where .= " AND T.EXR_S_DATE BETWEEN '{$data['sdate']}' AND '{$data['edate']}' ";
                            break;
                        case "exe":
                            $add_where .= " AND T.EXR_E_DATE BETWEEN '{$data['sdate']}' AND '{$data['edate']}' ";
                            break;
                        case "exb":
                            $add_where .= " AND T.BUY_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    public function list_all_buy_event_sum_cost1(array $data)
    {
        $add_where = $this->list_all_buy_event_where($data);
        $sql = "SELECT SUM(T.REAL_SELL_AMT) as sum_cost FROM buy_event_mgmt_tbl T
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['sum_cost'];
    }
    
    public function list_all_buy_event_sum_cost2(array $data)
    {
        $add_where = $this->list_all_buy_event_where($data);
        $sql = "SELECT SUM(T.BUY_AMT) as sum_cost FROM buy_event_mgmt_tbl T
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['sum_cost'];
    }
    
    public function list_all_buy_event_sum_cost3(array $data)
    {
        $add_where = $this->list_all_buy_event_where($data);
        $sql = "SELECT SUM(T.RECVB_AMT) as sum_cost FROM buy_event_mgmt_tbl T
                WHERE T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['sum_cost'];
    }
    
    public function list_all_buy_event_count(array $data)
    {
        $add_where = $this->list_all_buy_event_where($data);
    	$sql = "SELECT COUNT(*) as counter FROM buy_event_mgmt_tbl T
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
    
    public function list_all_buy_event(array $data)
    {
        $add_where = $this->list_all_buy_event_where($data);
        $sql = "SELECT T.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM buy_event_mgmt_tbl AS T
                LEFT JOIN mem_info_detl_tbl AS MID ON T.MEM_SNO = MID.MEM_SNO
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
    
    
    public function list_event_where($var)
    {
        $return_where = "";
        
        $or_where = " OR ( COMP_CD = '{$_SESSION['comp_cd']}' AND BCOFF_CD = '{$_SESSION['bcoff_cd']}' AND EVENT_DEPTH = '1') ";
        
        if (isset($var["day_n"]))
        {
            if($var['day_n'] == 'N')
            {
                $return_where .= " AND EVENT_DEPTH != 3 ";
            }
        }
        
        if (isset($var["acc_dv"]))
        {
            if($var['acc_dv'] != '')
            {
                $return_where .= " AND ACC_RTRCT_DV = '{$var['acc_dv']}' ";
            }
        }
        
        if (isset($var["acc_mthd"]))
        {
            if($var['acc_mthd'] != '')
            {
                $return_where .= " AND ACC_RTRCT_MTHD = '{$var['acc_mthd']}' ";
            }
        }
        
        if (isset($var["1rd_cd"]))
        {
            if($var['1rd_cd'] != '')
            {
                $return_where .= " AND 1RD_CATE_CD = '{$var['1rd_cd']}' ";
                
                $or_where = " OR (COMP_CD = '{$_SESSION['comp_cd']}' AND BCOFF_CD = '{$_SESSION['bcoff_cd']}' AND EVENT_DEPTH = '1' AND 1RD_CATE_CD = '{$var['1rd_cd']}') ";
            }
        }
        
        if (isset($var["2rd_cd"]))
        {
            if($var['2rd_cd'] != '')
            {
                $return_where .= " AND 2RD_CATE_CD = '{$var['2rd_cd']}' ";
                
                $or_where = " OR (COMP_CD = '{$_SESSION['comp_cd']}' AND BCOFF_CD = '{$_SESSION['bcoff_cd']}' AND EVENT_DEPTH = '1' AND 2RD_CATE_CD = '{$var['2rd_cd']}') ";
            }
        }

        if (isset($var["m_cate"]))
        {
            if($var['m_cate'] != '')
            {
                $return_where .= " AND M_CATE = '{$var['m_cate']}' ";
            }
        }

        
        $return_where .= $or_where;
        
        return $return_where;
    }

    public function list_event_where2($var)
    {
        $return_where = "";
       
        
        if (isset($var["day_n"]))
        {
            if($var['day_n'] == 'N')
            {
                $return_where .= " AND EVENT_DEPTH != 3 ";
            }
        }
        
        if (isset($var["acc_dv"]))
        {
            if($var['acc_dv'] != '')
            {
                $return_where .= " AND ACC_RTRCT_DV = '{$var['acc_dv']}' ";
            }
        }
        
        if (isset($var["acc_mthd"]))
        {
            if($var['acc_mthd'] != '')
            {
                $return_where .= " AND ACC_RTRCT_MTHD = '{$var['acc_mthd']}' ";
            }
        }
        
        if (isset($var["m_cate"]))
        {
            if($var['m_cate'] != '')
            {
                $return_where .= " AND M_CATE = '{$var['m_cate']}' ";
            }
        }

        if (isset($var["sell_yn"]))
        {
            if($var['sell_yn'] != '')
            {
                $return_where .= " AND SELL_YN = '{$var['sell_yn']}' ";
            }
        }
        
        return $return_where;
    }
    
    /**
     * 상품 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_event_count(array $data)
    {
        $add_where = $this->list_event_where($data);
        $sql = "SELECT COUNT(*) AS counter FROM sell_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY 
                EVENT_REF_SNO DESC
                ,EVENT_STEP ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $_SESSION['comp_cd']
            ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }

    
    
    /**
     * 중분류 [ List Board ]
     * @param array $data
     * @return array
     */
    public function list_m_cate(array $data)
    {
        $add_where = $this->list_event_where($data);
        $sql = "SELECT * FROM sell_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY 
                1RD_CATE_CD ASC
                ,2RD_CATE_CD ASC
                ,EVENT_REF_SNO DESC
                ,EVENT_STEP ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $_SESSION['comp_cd']
            ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }

    /**
     * 상품 [ List Board ]
     * @param array $data
     * @return array
     */
    public function list_event2(array $data)
    {
        $add_where = $this->list_event_where2($data);
        $sql = "SELECT * 
                FROM (
                    SELECT 
                        B.*,
                        COALESCE(C.cnt, 0) AS ref_count,
                        COALESCE(D.cnt, 0) AS p_ref_count,
                        REGEXP_REPLACE(
                            B.SELL_EVENT_NM,
                            '[0-9]+',
                            LPAD(REGEXP_SUBSTR(B.SELL_EVENT_NM, '[0-9]+'), 4, '0')
                        ) AS padded_event_name
                    FROM 
                        sell_event_mgmt_tbl B
                    LEFT JOIN (
                        SELECT 
                            EVENT_REF_SNO,
                            COUNT(SELL_EVENT_SNO) AS cnt
                        FROM 
                            sell_event_mgmt_tbl
                        WHERE SELL_EVENT_SNO <> EVENT_REF_SNO
                          AND SELL_YN = 'Y'
                        GROUP BY 
                            EVENT_REF_SNO
                    ) C
                    ON 
                        B.SELL_EVENT_SNO = C.EVENT_REF_SNO
                    LEFT JOIN (
                        SELECT 
                            EVENT_REF_SNO,
                            COUNT(SELL_EVENT_SNO) AS cnt
                        FROM 
                            sell_event_mgmt_tbl
                        WHERE SELL_EVENT_SNO <> EVENT_REF_SNO
                        GROUP BY 
                            EVENT_REF_SNO
                    ) D
                    ON 
                        B.SELL_EVENT_SNO = D.EVENT_REF_SNO) A
                    
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY 
                padded_event_name
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }

    /**
     * 상품 [ List Board ] 대분류, 중분류가 없어도 됨
     * @param array $data
     * @return array
     */
    public function list_event3(array $data)
    {
        $add_where = $this->list_event_where($data);
        $sql = "SELECT * 
                FROM (
                    SELECT 
                        B.*,
                        COALESCE(C.cnt, 0) AS ref_count,
                        COALESCE(D.cnt, 0) AS p_ref_count,
                        REGEXP_REPLACE(
                            B.SELL_EVENT_NM,
                            '[0-9]+',
                            LPAD(REGEXP_SUBSTR(B.SELL_EVENT_NM, '[0-9]+'), 4, '0')
                        ) AS padded_event_name
                    FROM 
                        sell_event_mgmt_tbl B
                    LEFT JOIN (
                        SELECT 
                            EVENT_REF_SNO,
                            COUNT(SELL_EVENT_SNO) AS cnt
                        FROM 
                            sell_event_mgmt_tbl
                        WHERE SELL_EVENT_SNO <> EVENT_REF_SNO
                          AND SELL_YN = 'Y'
                        GROUP BY 
                            EVENT_REF_SNO
                    ) C
                    ON 
                        B.SELL_EVENT_SNO = C.EVENT_REF_SNO
                    LEFT JOIN (
                        SELECT 
                            EVENT_REF_SNO,
                            COUNT(SELL_EVENT_SNO) AS cnt
                        FROM 
                            sell_event_mgmt_tbl
                        WHERE SELL_EVENT_SNO <> EVENT_REF_SNO
                        GROUP BY 
                            EVENT_REF_SNO
                    ) D
                    ON 
                        B.SELL_EVENT_SNO = D.EVENT_REF_SNO) A
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND SELL_YN = 'Y'
                {$add_where}
                ORDER BY 
                padded_event_name
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $_SESSION['comp_cd']
            ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 상품 [ List Board ] 대분류, 중분류 값이 있어야 함.
     * @param array $data
     * @return array
     */
    public function list_event(array $data)
    {
        $add_where = $this->list_event_where($data);
        $sql = "SELECT * FROM sell_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD IS NOT NULL 
                AND 2RD_CATE_CD IS NOT NULL
                {$add_where}
                ORDER BY 
                1RD_CATE_CD ASC
                ,2RD_CATE_CD ASC
                ,EVENT_REF_SNO DESC
                ,EVENT_STEP ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $_SESSION['comp_cd']
            ,'bcoff_cd'				=> $_SESSION['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function update_sell_evnet_setting(array $data)
    {
        $sql = "UPDATE sell_event_mgmt_tbl SET
					SELL_S_DATE 		= :sell_s_date:
                    ,SELL_E_DATE        = :sell_e_date:
                    ,MEM_DISP_YN        = :mem_disp_yn:
                WHERE
					COMP_CD			     = :comp_cd:
					AND BCOFF_CD		 = :bcoff_cd:
					AND SELL_EVENT_SNO	 = :sell_event_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'sell_event_sno'		=> $data['sell_event_sno']
            ,'sell_s_date'		=> $data['sell_s_date']
            ,'sell_e_date'		=> $data['sell_e_date']
            ,'mem_disp_yn'		=> $data['mem_disp_yn']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 상품일련번호를 이용하여 정보 가져오기
     * @param array $data
     * @return array
     */
    public function get_event(array $data)
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
     * 상품 [ List Board ]
     * @param array $data
     * @return array
     */
    public function get_event_last_step(array $data)
    {
        $sql = "SELECT EVENT_STEP FROM sell_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND EVENT_REF_SNO = :sell_event_sno:
                ORDER BY EVENT_STEP DESC LIMIT 1
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
     * 판매상품 BASE insert
     * @param array $data
     */
    public function update_event_step(array $data)
    {
        $sql = "UPDATE sell_event_mgmt_tbl SET
					EVENT_STEP 		= EVENT_STEP + 1
                WHERE
					COMP_CD			= :comp_cd:
					AND BCOFF_CD			= :bcoff_cd:
					AND EVENT_REF_SNO		= :event_ref_sno:
                    AND EVENT_STEP > :event_step:
				";
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'event_ref_sno'		=> $data['event_ref_sno']
            ,'event_step'			=> $data['event_step']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    
	
	/**
	 * 판매상품 BASE insert
	 * @param array $data
	 */
	public function insert_sell_event_base(array $data)
	{
		$sql = "INSERT sell_event_mgmt_tbl SET
					SELL_EVENT_SNO 		= :sell_event_sno:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD			= :bcoff_cd:
                    ,1RD_CATE_CD		= :1rd_cate_cd:
					,2RD_CATE_CD		= :2rd_cate_cd:
					,SELL_EVENT_NM		= :sell_event_nm:
                    ,CLAS_DV            = :clas_dv:
					,EVENT_REF_SNO		= :event_ref_sno:
					,EVENT_STEP			= :event_step:
					,EVENT_DEPTH		= :event_depth:
					,EVENT_ACC_REF_SNO	= :event_acc_ref_sno:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                    ,SELL_YN		= :sell_yn:
				";
		$query = $this->db->query($sql, [
				'sell_event_sno' 		=> $data['sell_event_sno']
				,'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'1rd_cate_cd'			=> $data['1rd_cate_cd']
				,'2rd_cate_cd'			=> $data['2rd_cate_cd']
				,'sell_event_nm'		=> $data['sell_event_nm']
		        ,'clas_dv'		        => $data['clas_dv']
				,'event_ref_sno'		=> $data['event_ref_sno']
				,'event_step'			=> $data['event_step']
				,'event_depth'			=> $data['event_depth']
				,'event_acc_ref_sno'	=> $data['event_acc_ref_sno']
				,'cre_id'			=> $data['cre_id']
				,'cre_datetm'		=> $data['cre_datetm']
				,'mod_id'			=> $data['mod_id']
				,'mod_datetm'		=> $data['mod_datetm']
		        ,'sell_yn'		=> $data['sell_yn']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
    /**
     * 판매상품 insert
     * @param array $data
     */
    public function insert_sell_event(array $data)
    {
    	$sql = "INSERT sell_event_mgmt_tbl SET
					SELL_EVENT_SNO 		= :sell_event_sno:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD			= :bcoff_cd:
                    ,1RD_CATE_CD		= :1rd_cate_cd:
					,2RD_CATE_CD		= :2rd_cate_cd:
					,SELL_EVENT_NM		= :sell_event_nm:
					,SELL_AMT			= :sell_amt:
					,USE_PROD			= :use_prod:
					,USE_UNIT			= :use_unit:
					,CLAS_CNT			= :clas_cnt:
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
					,SELL_STAT			= :sell_stat:
					,GRP_CATE_SET		= :grp_cate_set:
					,LOCKR_SET			= :lockr_set:
                    ,LOCKR_KND			= :lockr_knd:
					,LOCKR_GENDR_SET	= :lockr_gendr_set:
					,EVENT_REF_SNO		= :event_ref_sno:
					,EVENT_STEP			= :event_step:
					,EVENT_DEPTH		= :event_depth:
					,EVENT_ACC_REF_SNO	= :event_acc_ref_sno:
					,GRP_CLAS_PSNNL_CNT			= :grp_clas_psnnl_cnt:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                    ,SELL_YN		= :sell_yn:
				";
    	$query = $this->db->query($sql, [
    			'sell_event_sno' 		=> $data['sell_event_sno']
    			,'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'1rd_cate_cd'			=> $data['1rd_cate_cd']
    			,'2rd_cate_cd'			=> $data['2rd_cate_cd']
    			,'sell_event_nm'		=> $data['sell_event_nm']
    			,'sell_amt'				=> $data['sell_amt']
    			,'use_prod'				=> $data['use_prod']
    			,'use_unit'				=> $data['use_unit']
    			,'clas_cnt'				=> $data['clas_cnt']
    			,'domcy_day'			=> $data['domcy_day']
    			,'domcy_cnt'			=> $data['domcy_cnt']
    			,'domcy_poss_event_yn'	=> $data['domcy_poss_event_yn']
    			,'acc_rtrct_dv'			=> $data['acc_rtrct_dv']
    			,'acc_rtrct_mthd'		=> $data['acc_rtrct_mthd']
    			,'clas_dv'				=> $data['clas_dv']
    			,'mem_disp_yn'			=> $data['mem_disp_yn']
    			,'sell_s_date'			=> $data['sell_s_date']
    			,'sell_e_date'			=> $data['sell_e_date']
    			,'event_img'			=> $data['event_img']
    			,'event_icon'			=> $data['event_icon']
    			,'sell_stat'			=> $data['sell_stat']
    			,'grp_cate_set'			=> $data['grp_cate_set']
    			,'lockr_set'			=> $data['lockr_set']
    	        ,'lockr_knd'			=> $data['lockr_knd']
    			,'lockr_gendr_set'		=> $data['lockr_gendr_set']
    			,'event_ref_sno'		=> $data['event_ref_sno']
    			,'event_step'			=> $data['event_step']
    			,'event_depth'			=> $data['event_depth']
    			,'event_acc_ref_sno'	=> $data['event_acc_ref_sno']
    			,'grp_clas_psnnl_cnt'	=> $data['grp_clas_psnnl_cnt']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	        ,'sell_yn'		=> $data['sell_yn']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }

    /**
     * 구매한 상품을 회원 아이디를 이용하여 가져온다.
     * 구매한 상품의 상태로도 검색을 해야한다.
     * 종료인경우 최근 3개월 이내 종료된 회원권만 보여주도록 제한
     * @param array $data
     * @return array
     */
    public function list_buy_event_user_id_event_stat_for_mobile(array $data)
    {
    	$sql = "SELECT S.`BUY_EVENT_SNO`, S.`SELL_EVENT_SNO`, S.`SEND_EVENT_SNO`, S.`COMP_CD`, S.`BCOFF_CD`, CONCAT(T.M_CATE, CASE WHEN T.LOCKR_SET = '' THEN 'N' ELSE IFNULL(T.LOCKR_SET, 'N') END) AS 1RD_CATE_CD, 
                    S.SELL_EVENT_SNO AS 2RD_CATE_CD, 
                    S.`MEM_SNO`, S.`MEM_ID`, S.`MEM_NM`, S.`STCHR_ID`, S.`STCHR_NM`, S.`PTCHR_ID`, S.`PTCHR_NM`, S.`SELL_EVENT_NM`, S.`SELL_AMT`, S.`USE_PROD`, S.`USE_UNIT`, S.`CLAS_CNT`, S.`DOMCY_DAY`, S.`DOMCY_CNT`, CASE WHEN S.`DOMCY_DAY` IS NULL OR S.`DOMCY_CNT` IS NULL THEN 'N' ELSE S.`DOMCY_POSS_EVENT_YN`END AS DOMCY_POSS_EVENT_YN, S.`ACC_RTRCT_DV`, S.`ACC_RTRCT_MTHD`, 
                    CASE WHEN S.CLAS_CNT > 0 THEN CASE WHEN S.SELL_EVENT_NM LIKE '%골프%' THEN 22 ELSE 21 END ELSE S.`CLAS_DV` END AS CLAS_DV, S.`EVENT_IMG`, S.`EVENT_ICON`, S.`GRP_CLAS_PSNNL_CNT`, 
                    CASE WHEN IFNULL(EXR_E_DATE, '') = '' THEN CASE WHEN IFNULL(EXR_S_DATE, '') = '' THEN '01' ELSE '00' END ELSE CASE WHEN S.EVENT_STAT = '00' OR S.EVENT_STAT = '01'  THEN CASE WHEN DATE(NOW()) > DATE(EXR_E_DATE) THEN '99' ELSE CASE WHEN DATE(NOW()) > DATE(EXR_S_DATE) THEN '00' ELSE S.`EVENT_STAT` END END ELSE S.EVENT_STAT END END AS EVENT_STAT, S.`EVENT_STAT_RSON`, S.`EXR_S_DATE`, S.`EXR_E_DATE`, S.`USE_DOMCY_DAY`, S.`LEFT_DOMCY_POSS_DAY`, S.`LEFT_DOMCY_POSS_CNT`, S.`BUY_DATETM`, S.`REAL_SELL_AMT`, S.`BUY_AMT`, S.`RECVB_AMT`, S.`ADD_SRVC_EXR_DAY`, S.`ADD_SRVC_CLAS_CNT`, S.`ADD_DOMCY_DAY`, S.`ADD_DOMCY_CNT`, S.`SRVC_CLAS_LEFT_CNT`, S.`SRVC_CLAS_PRGS_CNT`, S.`1TM_CLAS_PRGS_AMT`, S.`MEM_REGUL_CLAS_LEFT_CNT`, S.`MEM_REGUL_CLAS_PRGS_CNT`, S.`ORI_EXR_S_DATE`, S.`ORI_EXR_E_DATE`, S.`TRANSM_POSS_YN`, S.`REFUND_POSS_YN`, S.`GRP_CATE_SET`, S.`LOCKR_SET`, S.`LOCKR_KND`, S.`LOCKR_GENDR_SET`, S.`LOCKR_NO`, S.`CRE_ID`, S.`CRE_DATETM`, S.`MOD_ID`, S.`MOD_DATETM`
                FROM buy_event_mgmt_tbl S LEFT JOIN sell_event_mgmt_tbl T ON S.SELL_EVENT_SNO = T.SELL_EVENT_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                AND S.MEM_ID = :mem_id:
				AND CASE WHEN IFNULL(EXR_E_DATE, '') = '' THEN CASE WHEN IFNULL(EXR_S_DATE, '') = '' THEN '01' ELSE '00' END ELSE CASE WHEN S.EVENT_STAT = '00' OR S.EVENT_STAT = '01'  THEN CASE WHEN DATE(NOW()) > DATE(EXR_E_DATE) THEN '99' ELSE CASE WHEN DATE(NOW()) > DATE(EXR_S_DATE) THEN '00' ELSE S.`EVENT_STAT` END END ELSE S.EVENT_STAT END END = :event_stat:
                AND T.M_CATE IS NOT NULL 
                AND ( 
                    :event_stat: != '99'  -- 종료인경우 최근 3개월 이내 종료된 회원권만 보여주도록 제한
                    OR IFNULL(S.EXR_E_DATE, S.MOD_DATETM) >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)  
                )
                ORDER BY S.MOD_DATETM DESC
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'mem_id'				=> $data['mem_id']
    			,'event_stat'			=> $data['event_stat']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 구매한 상품을 회원 아이디를 이용하여 가져온다.
     * 구매한 상품의 상태로도 검색을 해야한다.
     * @param array $data
     * @return array
     */
    public function list_buy_event_user_id_event_stat(array $data)
    {
    	$sql = "SELECT S.`BUY_EVENT_SNO`, S.`SELL_EVENT_SNO`, S.`SEND_EVENT_SNO`, S.`COMP_CD`, S.`BCOFF_CD`, CONCAT(T.M_CATE, CASE WHEN T.LOCKR_SET = '' THEN 'N' ELSE IFNULL(T.LOCKR_SET, 'N') END) AS 1RD_CATE_CD, 
                    S.SELL_EVENT_SNO AS 2RD_CATE_CD, T.M_CATE,
                    S.`MEM_SNO`, S.`MEM_ID`, S.`MEM_NM`, S.`STCHR_ID`, S.`STCHR_NM`, S.`PTCHR_ID`, S.`PTCHR_NM`, S.`SELL_EVENT_NM`, S.`SELL_AMT`, S.`USE_PROD`, S.`USE_UNIT`, S.`CLAS_CNT`, S.`DOMCY_DAY`, S.`DOMCY_CNT`, CASE WHEN S.`DOMCY_DAY` IS NULL OR S.`DOMCY_CNT` IS NULL THEN 'N' ELSE S.`DOMCY_POSS_EVENT_YN`END AS DOMCY_POSS_EVENT_YN, S.`ACC_RTRCT_DV`, S.`ACC_RTRCT_MTHD`, 
                    CASE WHEN S.CLAS_CNT > 0 THEN CASE WHEN S.SELL_EVENT_NM LIKE '%골프%' THEN 22 ELSE 21 END ELSE S.`CLAS_DV` END AS CLAS_DV, S.`EVENT_IMG`, S.`EVENT_ICON`, S.`GRP_CLAS_PSNNL_CNT`, 
                    CASE WHEN IFNULL(S.EVENT_STAT, '') <> '99' AND IFNULL(EXR_E_DATE, '') = '' THEN CASE WHEN IFNULL(EXR_S_DATE, '') = '' THEN '01' ELSE '00' END ELSE CASE WHEN S.EVENT_STAT = '00' OR S.EVENT_STAT = '01'  THEN CASE WHEN DATE(NOW()) > DATE(EXR_E_DATE) THEN '99' ELSE CASE WHEN DATE(NOW()) > DATE(EXR_S_DATE) THEN '00' ELSE S.`EVENT_STAT` END END ELSE S.EVENT_STAT END END AS EVENT_STAT, S.`EVENT_STAT_RSON`, S.`EXR_S_DATE`, S.`EXR_E_DATE`, S.`USE_DOMCY_DAY`, S.`LEFT_DOMCY_POSS_DAY`, S.`LEFT_DOMCY_POSS_CNT`, S.`BUY_DATETM`, S.`REAL_SELL_AMT`, S.`BUY_AMT`, S.`RECVB_AMT`, S.`ADD_SRVC_EXR_DAY`, S.`ADD_SRVC_CLAS_CNT`, S.`ADD_DOMCY_DAY`, S.`ADD_DOMCY_CNT`, S.`SRVC_CLAS_LEFT_CNT`, S.`SRVC_CLAS_PRGS_CNT`, S.`1TM_CLAS_PRGS_AMT`, S.`MEM_REGUL_CLAS_LEFT_CNT`, S.`MEM_REGUL_CLAS_PRGS_CNT`, S.`ORI_EXR_S_DATE`, S.`ORI_EXR_E_DATE`, S.`TRANSM_POSS_YN`, S.`REFUND_POSS_YN`, S.`GRP_CATE_SET`, S.`LOCKR_SET`, S.`LOCKR_KND`, S.`LOCKR_GENDR_SET`, S.`LOCKR_NO`, S.`CRE_ID`, S.`CRE_DATETM`, S.`MOD_ID`, S.`MOD_DATETM`
                FROM buy_event_mgmt_tbl S LEFT JOIN sell_event_mgmt_tbl T ON S.SELL_EVENT_SNO = T.SELL_EVENT_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                AND S.MEM_ID = :mem_id:
				AND CASE WHEN IFNULL(S.EVENT_STAT, '') <> '99' AND IFNULL(EXR_E_DATE, '') = '' THEN CASE WHEN IFNULL(EXR_S_DATE, '') = '' THEN '01' ELSE '00' END ELSE CASE WHEN S.EVENT_STAT = '00' OR S.EVENT_STAT = '01'  THEN CASE WHEN DATE(NOW()) > DATE(EXR_E_DATE) THEN '99' ELSE CASE WHEN DATE(NOW()) > DATE(EXR_S_DATE) THEN '00' ELSE S.`EVENT_STAT` END END ELSE S.EVENT_STAT END END = :event_stat:
                AND T.M_CATE IS NOT NULL 
                ORDER BY S.MOD_DATETM DESC
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'mem_id'				=> $data['mem_id']
    			,'event_stat'			=> $data['event_stat']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 구매한 상품을 회원 일련번호를 이용하여 가져온다.
     * 구매한 상품의 상태로도 검색을 해야한다.
     * @param array $data
     * @return array
     */
    public function list_buy_event_mem_sno_event_stat_prev(array $data)
    {
        $sql = "SELECT A.*
                FROM buy_event_mgmt_tbl A 
                WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                AND A.MEM_SNO = :mem_sno:
				AND A.EVENT_STAT = :event_stat:
                AND A.EXR_E_DATE >= NOW()
                ORDER BY A.EXR_S_DATE DESC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'mem_sno'				=> $data['mem_sno']
            ,'event_stat'			=> $data['event_stat']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }

    /**
     * 구매한 상품을 회원 일련번호를 이용하여 가져온다.
     * 구매한 상품의 상태로도 검색을 해야한다.
     * @param array $data
     * @return array
     */
    public function list_buy_event_mem_sno_event_stat(array $data)
    {
        $sql = "select A.BUY_EVENT_SNO,
                    A.SELL_EVENT_NM,
                    CASE WHEN (A.DOMCY_DAY - SUM(IFNULL(B.DOMCY_USE_DAY, 0))) < 0 THEN 0 ELSE A.DOMCY_DAY - SUM(IFNULL(B.DOMCY_USE_DAY, 0)) END AS LEFT_DOMCY_POSS_DAY,
                    CASE WHEN (A.DOMCY_CNT -  COUNT(*)) < 0 THEN 0 ELSE A.DOMCY_CNT -  COUNT(*) END AS LEFT_DOMCY_POSS_CNT,
                    A.DOMCY_POSS_EVENT_YN,
                    A.EXR_S_DATE,
                    A.EXR_E_DATE
                from buy_event_mgmt_tbl A LEFT JOIN domcy_mgmt_tbl B ON A.buy_event_sno = B.domcy_aply_buy_sno
                where A.DOMCY_POSS_EVENT_YN = 'Y' AND IFNULL(A.DOMCY_DAY, 0) > 0 AND A.COMP_CD = :comp_cd:
                    AND A.BCOFF_CD = :bcoff_cd:
                    AND A.MEM_SNO = :mem_sno:
                    AND A.EVENT_STAT = :event_stat:
                    AND EXR_E_DATE >= NOW()
                GROUP BY A.BUY_EVENT_SNO,
                    A.SELL_EVENT_NM,
                    A.DOMCY_DAY,
                    A.DOMCY_CNT,
                    A.DOMCY_POSS_EVENT_YN,
                    A.EXR_S_DATE,
                    A.EXR_E_DATE
                ORDER BY A.EXR_E_DATE
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'mem_sno'				=> $data['mem_sno']
            ,'event_stat'			=> $data['event_stat']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 구매상품을 구매상품 일련번호를 이용하여 가져온다.
     * @param array $data
     * @return array
     */
    public function get_buy_event_buy_sno(array $data)
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
     * 회원 아이디를 이용하여 아이디와 이름을 가져온다.
     * @param array $data
     */
    public function get_mem_info_id_idname(array $data)
    {
        $sql = "SELECT MEM_SNO,MEM_ID,MEM_NM
                FROM mem_info_detl_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_ID = :mem_id:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_id'			=> $data['mem_id']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 구매상품관리 update [PT, Golf PT 예약됨 -> 이용중]
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_pt_use(array $data)
    {
    	$sql = "UPDATE buy_event_mgmt_tbl A INNER JOIN sell_event_mgmt_tbl B ON A.SELL_EVENT_SNO = B.SELL_EVENT_SNO SET
					A.EVENT_STAT		    = '00'
					,A.EXR_S_DATE			= :exr_s_date:
                    ,A.EXR_E_DATE			= CASE WHEN IFNULL(B.USE_UNIT, '') = '' 
                                                   THEN A.EXR_E_DATE
                                                   ELSE CASE WHEN B.USE_UNIT = 'D' THEN DATE_ADD(A.EXR_S_DATE, INTERVAL B.USE_PROD DAY)
                                                            ELSE DATE_ADD(A.EXR_S_DATE, INTERVAL B.USE_PROD MONTH) END
                                                   END
					,A.MOD_ID		   		= :mod_id:
					,A.MOD_DATETM		    = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		= :buy_event_sno:
				";
    	$query = $this->db->query($sql, [
    			'buy_event_sno' 		=> $data['buy_event_sno']
    			,'event_stat'			=> $data['event_stat']
    			,'exr_s_date'			=> $data['exr_s_date']
    			,'mod_id'	    		=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 구매상품관리 update [시작일 변경]
     * 구매시 영향받은 상품들의 시작, 종료일을 업데이트 한다.
     * 시작일이 오늘일 경우에는 상태도 업데이트가 된다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_redate(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					EXR_S_DATE		    = :exr_s_date:
					,EXR_E_DATE		    = :exr_e_date:
                    ,EVENT_STAT         = :event_stat:
					,MOD_ID		   		= :mod_id:
					,MOD_DATETM		    = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		= :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'exr_s_date'			=> $data['exr_s_date']
            ,'exr_e_date'			=> $data['exr_e_date']
            ,'event_stat'			=> $data['event_stat']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 구매상품관리 update [종료일 변경]
     * 구매시 영향받은 상품들의 시작, 종료일을 업데이트 한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_redate_edate(array $data)
    {
    	$sql = "UPDATE buy_event_mgmt_tbl SET
					EXR_S_DATE		    = :exr_s_date:
					,EXR_E_DATE		    = :exr_e_date:
					,ADD_SRVC_EXR_DAY	= :add_srvc_exr_day:
					,MOD_ID		   		= :mod_id:
					,MOD_DATETM		    = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		= :buy_event_sno:
				";
    	$query = $this->db->query($sql, [
    			'buy_event_sno' 		=> $data['buy_event_sno']
    			,'exr_s_date'			=> $data['exr_s_date']
    			,'exr_e_date'			=> $data['exr_e_date']
    			,'add_srvc_exr_day'		=> $data['add_srvc_exr_day']
    			,'mod_id'	    		=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 구매상품관리 update [휴회 종료일 변경]
     * 구매시 영향받은 상품들의 시작, 종료일을 업데이트 한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_redate_edate_domcy(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					EXR_S_DATE		        = :exr_s_date:
					,EXR_E_DATE		        = :exr_e_date:
                    ,USE_DOMCY_DAY          = :use_domcy_day:
                    ,LEFT_DOMCY_POSS_DAY	= :left_domcy_poss_day:
					,LEFT_DOMCY_POSS_CNT	= :left_domcy_poss_cnt:
					,MOD_ID		   		    = :mod_id:
					,MOD_DATETM		        = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		= :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'exr_s_date'			=> $data['exr_s_date']
            ,'exr_e_date'			=> $data['exr_e_date']
            ,'use_domcy_day'		=> $data['use_domcy_day']
            ,'left_domcy_poss_day'	=> $data['left_domcy_poss_day']
            ,'left_domcy_poss_cnt'	=> $data['left_domcy_poss_cnt']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 구매상품관리 update [휴회일 추가]
     * 추가 휴회일 , 남은 휴회 가능일 을 업데이트 한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_domcy_day(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					ADD_DOMCY_DAY		     = :add_domcy_day:
					,LEFT_DOMCY_POSS_DAY	 = :left_domcy_poss_day:
					,MOD_ID		   		     = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		 = :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'add_domcy_day'			=> $data['add_domcy_day']
            ,'left_domcy_poss_day'	=> $data['left_domcy_poss_day']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 구매상품관리 update [휴회횟수 추가]
     * 추가 휴회횟수 , 남은 휴회 가능횟수 를 업데이트 한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_domcy_cnt(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					ADD_DOMCY_CNT		     = :add_domcy_cnt:
					,LEFT_DOMCY_POSS_CNT	 = :left_domcy_poss_cnt:
					,MOD_ID		   		     = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		 = :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'add_domcy_cnt'		=> $data['add_domcy_cnt']
            ,'left_domcy_poss_cnt'	=> $data['left_domcy_poss_cnt']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 구매상품관리 update [수업횟수 추가]
     * 추가 수업횟수 , 남은 수업 가능횟수 를 업데이트 한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_clas_cnt(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					ADD_SRVC_CLAS_CNT		 = :add_srvc_clas_cnt:
					,SRVC_CLAS_LEFT_CNT	     = :srvc_clas_left_cnt:
					,MOD_ID		   		     = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		 = :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'add_srvc_clas_cnt'	=> $data['add_srvc_clas_cnt']
            ,'srvc_clas_left_cnt'	=> $data['srvc_clas_left_cnt']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 구매상품관리 update [수업강사 변경]
     * 수업강사를 변경한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_stchr(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					STCHR_ID		 = :stchr_id:
					,STCHR_NM	     = :stchr_nm:
					,MOD_ID		   		     = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		 = :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'stchr_id'	=> $data['stchr_id']
            ,'stchr_nm'	=> $data['stchr_nm']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 구매상품관리 update [판매강사 변경]
     * 판매강사를 변경한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_ptchr(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					PTCHR_ID		 = :ptchr_id:
					,PTCHR_NM	     = :ptchr_nm:
					,MOD_ID		   		     = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		 = :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'ptchr_id'	=> $data['ptchr_id']
            ,'ptchr_nm'	=> $data['ptchr_nm']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 먀출관리 update [판매강사 변경]
     * 판매강사를 변경한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_sales_mgmt_ptchr(array $data)
    {
        $sql = "UPDATE sales_mgmt_tbl SET 
                PTHCR_ID = :ptchr_id:
                ,MOD_ID = :mod_id:
                ,MOD_DATETM = :mod_datetm:
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND BUY_EVENT_SNO = :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'comp_cd'              => $data['comp_cd']
            ,'bcoff_cd'             => $data['bcoff_cd']
            ,'ptchr_id'	            => $data['ptchr_id']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    
    /**
     * 구매상품관리 update [판매강사 변경]
     * 수업강사를 변경한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_lockr(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					EVENT_STAT              = :event_stat:
                    ,EXR_S_DATE             = :exr_s_date:
                    ,EXR_E_DATE             = :exr_e_date:
                    ,LOCKR_GENDR_SET        = :lockr_gendr_set:
                    ,LOCKR_NO               = :lockr_no:
					,MOD_ID		   		    = :mod_id:
					,MOD_DATETM		        = :mod_datetm:
    			WHERE BUY_EVENT_SNO 		= :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'event_stat'           => $data['event_stat']
            ,'exr_s_date'           => $data['exr_s_date']
            ,'exr_e_date'           => $data['exr_e_date']
            ,'lockr_gendr_set'      => $data['lockr_gendr_set']
            ,'lockr_no'             => $data['lockr_no']
            ,'mod_id'	    		=> $data['mod_id']
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
     * 락커 상태 update [ lockr_room ]
     * @param array $data
     */
    public function update_lockr_room_trans_end(array $data)
    {
        $sql = "UPDATE lockr_room SET
                    LOCKR_STAT		    = :lockr_stat:
    			WHERE COMP_CD 		    = :comp_cd:
                    AND BCOFF_CD        = :bcoff_cd:
                    AND LOCKR_KND       = :lockr_knd:
                    AND LOCKR_GENDR_SET = :lockr_gendr_set:
                    AND LOCKR_NO        = :lockr_no:
                    AND BUY_EVENT_SNO   = :buy_event_sno:
                    AND MEM_SNO         = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'lockr_stat'			    => $data['lockr_stat']
            ,'comp_cd'			        => $data['comp_cd']
            ,'bcoff_cd'			        => $data['bcoff_cd']
            ,'lockr_knd'			    => $data['lockr_knd']
            ,'lockr_gendr_set'			=> $data['lockr_gendr_set']
            ,'lockr_no'			        => $data['lockr_no']
            ,'buy_event_sno' 		    => $data['buy_event_sno']
            ,'mem_sno'			        => $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 양도하기 할때 출입조건이 다를 경우 판매상품의 EVENT_REF_SNO를 구한다.
     * @param array $data
     */
    public function get_diff_sell_event_ref_sno(array $data)
    {
        $sql = "SELECT EVENT_REF_SNO FROM sell_event_mgmt_tbl
                WHERE SELL_EVENT_SNO = :sell_event_sno:
				";
        
        $query = $this->db->query($sql, [
            'sell_event_sno' 			=> $data['sell_event_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 양도하기 할때 출입조건이 다를 경우 양수자의 출입조건과 같은 상품을 찾는다.
     * @param array $data
     */
    public function get_diff_sell_event_mgmt(array $data)
    {
        $sql = "SELECT * FROM sell_event_mgmt_tbl
                WHERE EVENT_REF_SNO = :event_ref_sno:
                AND ACC_RTRCT_DV = :acc_rtrct_dv: 
                AND ACC_RTRCT_MTHD = :acc_rtrct_mthd:
                AND USE_PROD = :use_prod: 
                AND USE_UNIT = :use_unit:
				";
        
        $query = $this->db->query($sql, [
            'event_ref_sno' 			=> $data['event_ref_sno']
            ,'acc_rtrct_dv'			=> $data['acc_rtrct_dv']
            ,'acc_rtrct_mthd'			=> $data['acc_rtrct_mthd']
            ,'use_prod'			=> $data['use_prod']
            ,'use_unit'			=> $data['use_unit']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 구매상품관리 update [강세종료]
     * 수업강사를 변경한다.
     * where : BUY_EVENT_SNO
     * @param array $data
     */
    public function update_buy_event_mgmt_just_end(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					EVENT_STAT		     = :event_stat:
                    ,EVENT_STAT_RSON		 = :event_stat_rson:
					,MOD_ID		   		 = :mod_id:
					,MOD_DATETM		     = :mod_datetm:
    			WHERE BUY_EVENT_SNO 	 = :buy_event_sno:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 		=> $data['buy_event_sno']
            ,'event_stat'	        => $data['event_stat']
            ,'event_stat_rson'	    => $data['event_stat_rson']
            ,'mod_id'	    		=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    
    
    
    
}