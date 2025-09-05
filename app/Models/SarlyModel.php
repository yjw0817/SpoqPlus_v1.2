<?php
namespace App\Models;

use CodeIgniter\Model;

class SarlyModel extends Model
{
	/**
	 * GX수업횟수 구간에 해당하는 1회 금액 가져오기
	 * @param array $data
	 */
	public function get_calcu_sarly_mgmt_gx_count(array $data)
	{
		$sql = "SELECT CLAS_1TM_AMT FROM sarly_sub_mgmt_tbl
				WHERE 
				COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND SARLY_PMT_MTHD = :sarly_pmt_mthd:
				AND SARLY_MGMT_SNO = :sarly_mgmt_sno:
				AND :base_cnt: BETWEEN GX_CLAS_S_CNT AND GX_CLAS_E_CNT
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'sarly_pmt_mthd'		=> $data['sarly_pmt_mthd']
				,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
				,'base_cnt'		        => $data['base_cnt']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	/**
	 * 강사의 GX수업횟수를 가져온다.
	 * @param array $data
	 */
	public function get_sarly_sum_gx_count(array $data)
	{
	    $add_where = "";
	    if ($data['sarly_pmt_cond'] == "02") $add_where = " AND GX_STCHR_ID = :tid: ";
	    
	    $sql = "SELECT COUNT(*) AS sum_pt_count FROM gx_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND CLAS_CHK_YN = 'Y'
                {$add_where}
                AND GX_CLAS_DATE BETWEEN :sdate: AND :edate:
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                    ,'tid'		            => $data['tid']
                    ,'sdate'		        => substr($data['sdate'],0,10)
                    ,'edate'		        => substr($data['edate'],0,10)
                ]);
                array_push($data,$query);
                return $query->getResultArray();
	}
	
	/**
	 * 강사의 GX수업횟수를 가져온다.
	 * @param array $data
	 */
	public function get_sarly_sum_gx_count_detail(array $data)
	{
	    $add_where = "";
	    if ($data['sarly_pmt_cond'] == "02") $add_where = " AND GX_STCHR_ID = :tid: ";
	    
	    $sql = "SELECT * FROM gx_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND CLAS_CHK_YN = 'Y'
                {$add_where}
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
                ORDER BY CRE_DATETM DESC
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                    ,'tid'		            => $data['tid']
                    ,'sdate'		        => $data['sdate']
                    ,'edate'		        => $data['edate']
                ]);
                array_push($data,$query);
                return $query->getResultArray();
	}
	
	/**
	 * PT수업횟수 구간에 해당하는 1회 금액 가져오기
	 * @param array $data
	 */
	public function get_calcu_sarly_mgmt_pt_count(array $data)
	{
		$sql = "SELECT CLAS_1TM_AMT FROM sarly_sub_mgmt_tbl
				WHERE 
				COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND SARLY_PMT_MTHD = :sarly_pmt_mthd:
				AND SARLY_MGMT_SNO = :sarly_mgmt_sno:
				AND :base_cnt: BETWEEN PT_CLAS_S_CNT AND PT_CLAS_E_CNT
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'sarly_pmt_mthd'		=> $data['sarly_pmt_mthd']
				,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
				,'base_cnt'		        => $data['base_cnt']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	
	
	/**
	 * 수업진행금액 구간에 해당하는 비율,수당금액 가져오기
	 * @param array $data
	 */
	public function get_calcu_sarly_mgmt_pt_amt(array $data)
	{
		$sql = "SELECT PT_CLAS_SALES_RATE, SARLY_AMT FROM sarly_sub_mgmt_tbl
				WHERE 
				COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND SARLY_PMT_MTHD = :sarly_pmt_mthd:
				AND SARLY_MGMT_SNO = :sarly_mgmt_sno:
				AND :base_amt: BETWEEN PT_CLAS_MM_SALES_S_AMT AND PT_CLAS_MM_SALES_E_AMT
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'sarly_pmt_mthd'		=> $data['sarly_pmt_mthd']
				,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
				,'base_amt'		        => $data['base_amt']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	/**
	 * 판매매출액 구간에 해당하는 비율, 수당금액 가져오기
	 * @param array $data
	 */
	public function get_calcu_sarly_mgmt_sell_amt(array $data)
	{
		$sql = "SELECT SELL_SALES_RATE, SARLY_AMT, PT_CLAS_SALES_RATE FROM sarly_sub_mgmt_tbl
				WHERE 
				COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND SARLY_PMT_MTHD = :sarly_pmt_mthd:
				AND SARLY_MGMT_SNO = :sarly_mgmt_sno:
				AND :base_amt: BETWEEN SELL_MM_SALES_S_AMT AND SELL_MM_SALES_E_AMT
				";
		
		$query = $this->db->query($sql, [
				'comp_cd'				=> $data['comp_cd']
				,'bcoff_cd'				=> $data['bcoff_cd']
				,'sarly_pmt_mthd'		=> $data['sarly_pmt_mthd']
				,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
				,'base_amt'		        => $data['base_amt']
		]);
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	
	/**
	 * 기본급 가져오기
	 * @param array $data
	 */
	public function get_sarly_mgmt_basic_amt(array $data)
	{
	    $sql = "SELECT SELL_SALES_RATE, SARLY_AMT, PT_CLAS_SALES_RATE FROM sarly_sub_mgmt_tbl
				WHERE
				COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND SARLY_PMT_MTHD = :sarly_pmt_mthd:
				AND SARLY_MGMT_SNO = :sarly_mgmt_sno:
				";
	    
	    $query = $this->db->query($sql, [
	        'comp_cd'				=> $data['comp_cd']
	        ,'bcoff_cd'				=> $data['bcoff_cd']
	        ,'sarly_pmt_mthd'		=> $data['sarly_pmt_mthd']
	        ,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
	    ]);
	    array_push($data,$query);
	    return $query->getResultArray();
	}
	
	
	/**
	 * 해당 강사의 수당 설정을 가져온다. (detail)
	 * @param array $data
	 */
	public function get_sarly_mgmt_tid_detail(array $data)
	{
	    $sql = "SELECT * FROM sarly_mgmt_tbl
                WHERE
                SARLY_MGMT_SNO = :sarly_mgmt_sno:
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND TCHR_ID = :tchr_id:
                AND
                ((
                SARLY_APLY_S_DATE BETWEEN :sdate: AND :edate:
                OR
                SARLY_APLY_E_DATE BETWEEN :sdate: AND :edate:
                )
                OR
                (
                :sdate: BETWEEN SARLY_APLY_S_DATE AND SARLY_APLY_E_DATE
                OR
                :edate: BETWEEN SARLY_APLY_S_DATE AND SARLY_APLY_E_DATE
                ))
				";
	    
	    $query = $this->db->query($sql, [
	        'comp_cd'				=> $data['comp_cd']
	        ,'bcoff_cd'				=> $data['bcoff_cd']
	        ,'tchr_id'		        => $data['tchr_id']
	        ,'sdate'		        => $data['sdate']
	        ,'edate'		        => $data['edate']
	        ,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
	    ]);
	    array_push($data,$query);
	    return $query->getResultArray();
	}
	
	
    
    /**
     * 해당 강사의 수당 설정을 가져온다.
     * @param array $data
     */
    public function get_sarly_mgmt_tid(array $data)
    {
        $sql = "SELECT * FROM sarly_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND TCHR_ID = :tchr_id:
                AND
                ((
                SARLY_APLY_S_DATE BETWEEN :sdate: AND :edate: 
                OR 
                SARLY_APLY_E_DATE BETWEEN :sdate: AND :edate:
                )
                OR
                (
                :sdate: BETWEEN SARLY_APLY_S_DATE AND SARLY_APLY_E_DATE 
                OR 
                :edate: BETWEEN SARLY_APLY_S_DATE AND SARLY_APLY_E_DATE
                ))
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'tchr_id'		        => $data['tchr_id']
            ,'sdate'		        => $data['sdate']
            ,'edate'		        => $data['edate']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사의 PT수업횟수를 가져온다.
     * @param array $data
     */
    public function get_sarly_sum_pt_count(array $data)
    {
        $add_where = "";
        if ($data['sarly_pmt_cond'] == "02") $add_where = " AND STCHR_ID = :tid: ";
        
        $sql = "SELECT COUNT(*) AS sum_pt_count FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND CANCEL_YN = 'N'
                {$add_where}
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
                AND PT_CLAS_DV = '00'
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'tid'		            => $data['tid']
            ,'sdate'		        => $data['sdate']
            ,'edate'		        => $data['edate']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사의 PT수업횟수를 가져온다.
     * @param array $data
     */
    public function get_sarly_sum_pt_count_detail(array $data)
    {
        $add_where = "";
        if ($data['sarly_pmt_cond'] == "02") $add_where = " AND STCHR_ID = :tid: ";
        
        $sql = "SELECT * FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND CANCEL_YN = 'N'
                {$add_where}
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
                ORDER BY CRE_DATETM DESC
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                    ,'tid'		            => $data['tid']
                    ,'sdate'		        => $data['sdate']
                    ,'edate'		        => $data['edate']
                ]);
                array_push($data,$query);
                return $query->getResultArray();
    }
    
    
    
    /**
     * 강사의 PT수업진행 매출액을 가져온다.
     * @param array $data
     */
    public function get_sarly_sum_pt_amt(array $data)
    {
        $add_where = "";
        if ($data['sarly_pmt_cond'] == "02") $add_where = " AND STCHR_ID = :tid: ";
        
        $sql = "SELECT SUM(TCHR_1TM_CLAS_PRGS_AMT) AS sum_pt_amt FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND CANCEL_YN = 'N'
                {$add_where}
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'tid'		            => $data['tid']
            ,'sdate'		        => $data['sdate']
            ,'edate'		        => $data['edate']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사의 PT수업진행 매출액을 가져온다.
     * @param array $data
     */
    public function get_sarly_sum_pt_amt_detail(array $data)
    {
        $add_where = "";
        if ($data['sarly_pmt_cond'] == "02") $add_where = " AND STCHR_ID = :tid: ";
        
        $sql = "SELECT * FROM pt_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND CANCEL_YN = 'N'
                {$add_where}
                AND CRE_DATETM BETWEEN :sdate: AND :edate:
                ORDER BY CRE_DATETM DESC
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                    ,'tid'		            => $data['tid']
                    ,'sdate'		        => $data['sdate']
                    ,'edate'		        => $data['edate']
                ]);
                array_push($data,$query);
                return $query->getResultArray();
    }
    
    /**
     * 강사의 판매 매출액을 가져온다.
     * @param array $data
     */
    public function get_sarly_sum_amt(array $data)
    {
        $add_where = "";
        if ($data['sarly_pmt_cond'] == "02") $add_where = " AND PTHCR_ID = :tid: ";
        
        $add_cate1 = "";
        if ($data['item1'] != "'all'") $add_cate1 = "AND 1RD_CATE_CD IN ({$data['item1']})";
        
        $add_cate2 = "";
        if ($data['item2'] != "'all'") $add_cate2 = "AND 2RD_CATE_CD IN ({$data['item2']})";
        
        $sql = "SELECT SUM(PAYMT_AMT) AS sum_amt FROM sales_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
                AND 
                (
                ( GRP_CATE_SET = '1RD' {$add_cate1} )
                OR
                ( GRP_CATE_SET = '2RD' {$add_cate2} )
                )
                 AND CRE_DATETM BETWEEN :sdate: AND :edate:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'tid'		            => $data['tid']
            ,'sdate'		        => $data['sdate']
            ,'edate'		        => $data['edate']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사의 판매 매출액을 가져온다.
     * @param array $data
     */
    public function get_sarly_sum_amt_detail(array $data)
    {
        $add_where = "";
        if ($data['sarly_pmt_cond'] == "02") $add_where = " AND PTHCR_ID = :tid: ";
        
        $add_cate1 = "";
        if ($data['item1'] != "'all'") $add_cate1 = "AND 1RD_CATE_CD IN ({$data['item1']})";
        
        $add_cate2 = "";
        if ($data['item2'] != "'all'") $add_cate2 = "AND 2RD_CATE_CD IN ({$data['item2']})";
        
        $sql = "SELECT * FROM sales_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
                AND
                (
                ( GRP_CATE_SET = '1RD' {$add_cate1} )
                OR
                ( GRP_CATE_SET = '2RD' {$add_cate2} )
                )
                 AND CRE_DATETM BETWEEN :sdate: AND :edate:
                ORDER BY CRE_DATETM DESC
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                    ,'tid'		            => $data['tid']
                    ,'sdate'		        => $data['sdate']
                    ,'edate'		        => $data['edate']
                ]);
                array_push($data,$query);
                return $query->getResultArray();
    }
    
    
    /**
     * 강사 수당설정 sub 를 delete 한다.
     * @param array $data
     * @return Object
     */
    public function delete_sub_sarly_mgmt(array $data)
    {
        $sql = 'DELETE FROM sarly_sub_mgmt_tbl
                WHERE SARLY_SUB_MGMT_SNO = :sarly_sub_mgmt_sno:
				';
        $query = $this->db->query($sql, [
            'sarly_sub_mgmt_sno' 		    => $data['sarly_sub_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $query;
    }

    /**
     * 강사 수당설정 sub 를 delete 한다.
     * @param array $data
     * @return Object
     */
    public function delete_sub_sarly_mgmt_by_sarly_mgmt_sno(array $data)
    {
        $sql = 'DELETE FROM sarly_sub_mgmt_tbl
                WHERE SARLY_MGMT_SNO = :sarly_mgmt_sno:
				';
        $query = $this->db->query($sql, [
            'sarly_mgmt_sno' 		    => $data['sarly_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    
    /**
     * 강사수당 조건 설정 리스트
     * @param array $data
     */
    public function list_sarly_sub_mgmt(array $data)
    {
        $sql = "SELECT * FROM sarly_sub_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND SARLY_MGMT_SNO = :sarly_mgmt_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사수당 설정 리스트
     * @param array $data
     */
    public function get_sarly_mgmt(array $data)
    {
        $sql = "SELECT * FROM sarly_mgmt_tbl
                WHERE COMP_CD       = :comp_cd:
                AND BCOFF_CD        = :bcoff_cd:
                AND SARLY_MGMT_SNO  = :sarly_mgmt_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'sarly_mgmt_sno'		=> $data['sarly_mgmt_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    private function list_sarly_mgmt_where(array $data)
    {
        $add_where = "";
        
        // 강사명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND (A.TCHR_NM LIKE '%{$data['snm']}%' OR A.TCHR_ID LIKE '%{$data['snm']}%')";
            }
        }
        
        // 지급조건
        if (isset($data['spcond']))
        {
            if ($data['spcond'] != '')
            {
                $add_where .= " AND A.SARLY_PMT_COND = '{$data['spcond']}' ";
            }
        }
        
        // 지급방법
        if (isset($data['spmthdnm']))
        {
            if ($data['spmthdnm'] != '')
            {
                $add_where .= " AND A.SARLY_PMT_MTHD = '{$data['spmthdnm']}' ";
            }
        }
        
        // 날짜검색조건
        if (isset($data['sdcon']))
        {
            if ($data['sdcon'] != '')
            {
                $has_sdate = isset($data['sdate']) && $data['sdate'] != '';
                $has_edate = isset($data['edate']) && $data['edate'] != '';
                
                if ($has_sdate || $has_edate)
                {
                    // 기본값 설정
                    $sdate = $has_sdate ? $data['sdate'] . " 00:00:00" : '1900-01-01 00:00:00';
                    $edate = $has_edate ? $data['edate'] . " 23:59:59" : '2999-12-31 23:59:59';
                    
                    $sdate_only = $has_sdate ? $data['sdate'] : '1900-01-01';
                    $edate_only = $has_edate ? $data['edate'] : '2999-12-31';
                    
                    switch ($data['sdcon'])
                    {
                        case "sc":
                            $add_where .= " AND A.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "ss":
                            $add_where .= " AND A.SARLY_APLY_S_DATE BETWEEN '{$sdate_only}' AND '{$edate_only}' ";
                            break;
                        case "se":
                            $add_where .= " AND A.SARLY_APLY_E_DATE BETWEEN '{$sdate_only}' AND '{$edate_only}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 강사수당 설정 리스트
     * @param array $data
     */
    public function list_sarly_mgmt_count(array $data)
    {
        $add_where = $this->list_sarly_mgmt_where($data);
        $sql = "SELECT COUNT(*) as counter FROM sarly_mgmt_tbl A
                WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
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
     * 강사수당 설정 리스트
     * @param array $data
     */
    public function list_sarly_mgmt(array $data)
    {
        $add_where = $this->list_sarly_mgmt_where($data);
        $sql = "SELECT A.SARLY_MGMT_SNO,
                    A.TCHR_NM,
                    A.TCHR_ID,
                    A.SARLY_APLY_S_DATE,
                    A.SARLY_APLY_E_DATE,
                    A.SARLY_PMT_COND,
                    A.SARLY_PMT_MTHD,
                    A.CRE_DATETM,
                    C.MEM_THUMB_IMG,
                    C.MEM_MAIN_IMG,
                    C.MEM_GENDR,
                COUNT(B.SARLY_SUB_MGMT_SNO) AS SUB_COUNT
                FROM sarly_mgmt_tbl A 
                LEFT JOIN sarly_sub_mgmt_tbl B ON A.SARLY_MGMT_SNO = B.SARLY_MGMT_SNO
                LEFT JOIN mem_info_detl_tbl C ON A.TCHR_ID = C.MEM_ID AND A.COMP_CD = C.COMP_CD AND A.BCOFF_CD = C.BCOFF_CD
                WHERE A.COMP_CD = :comp_cd:
                AND A.BCOFF_CD = :bcoff_cd:
                {$add_where}
                GROUP BY A.SARLY_MGMT_SNO,
                    A.TCHR_NM,
                    A.TCHR_ID,
                    A.SARLY_APLY_S_DATE,
                    A.SARLY_APLY_E_DATE,
                    A.SARLY_PMT_COND,
                    A.SARLY_PMT_MTHD,
                    A.CRE_DATETM,
                    C.MEM_THUMB_IMG,
                    C.MEM_MAIN_IMG,
                    C.MEM_GENDR
                ORDER BY A.TCHR_NM, A.SARLY_APLY_S_DATE DESC, A.SARLY_APLY_E_DATE DESC, A.CRE_DATETM DESC
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
     * 강사 수당설정sub 를 insert 한다.
     * @param array $data
     * @return Object
     */
    public function insert_sub_sarly_mgmt(array $data)
    {
        $sql = 'INSERT sarly_sub_mgmt_tbl SET
                    SARLY_MGMT_SNO          = :sarly_mgmt_sno:
					,COMP_CD 		        = :comp_cd:
					,BCOFF_CD 		        = :bcoff_cd:
                    ,SARLY_PMT_MTHD         = :sarly_pmt_mthd:
                    ,SELL_MM_SALES_S_AMT    = :sell_mm_sales_s_amt:
                    ,SELL_MM_SALES_E_AMT    = :sell_mm_sales_e_amt:
                    ,SELL_SALES_RATE        = :sell_sales_rate:
                    ,PT_CLAS_MM_SALES_S_AMT = :pt_clas_mm_sales_s_amt:
                    ,PT_CLAS_MM_SALES_E_AMT = :pt_clas_mm_sales_e_amt:
                    ,PT_CLAS_SALES_RATE     = :pt_clas_sales_rate:
                    ,SARLY_AMT              = :sarly_amt:
                    ,PT_CLAS_S_CNT          = :pt_clas_s_cnt:
                    ,PT_CLAS_E_CNT          = :pt_clas_e_cnt:
                    ,GX_CLAS_S_CNT          = :gx_clas_s_cnt:
                    ,GX_CLAS_E_CNT          = :gx_clas_e_cnt:
                    ,CLAS_1TM_AMT           = :clas_1tm_amt:
                    ,CRE_ID                 = :cre_id:
                    ,CRE_DATETM             = :cre_datetm:
                    ,MOD_ID                 = :mod_id:
                    ,MOD_DATETM             = :mod_datetm:
				';
        $query = $this->db->query($sql, [
            'sarly_mgmt_sno' 		    => $data['sarly_mgmt_sno']
            ,'comp_cd' 		            => $data['comp_cd']
            ,'bcoff_cd' 		        => $data['bcoff_cd']
            ,'sarly_pmt_mthd' 		    => $data['sarly_pmt_mthd']
            ,'sell_mm_sales_s_amt' 		=> $data['sell_mm_sales_s_amt']
            ,'sell_mm_sales_e_amt'	    => $data['sell_mm_sales_e_amt']
            ,'sell_sales_rate' 		    => $data['sell_sales_rate']
            ,'pt_clas_mm_sales_s_amt' 	=> $data['pt_clas_mm_sales_s_amt']
            ,'pt_clas_mm_sales_e_amt'	=> $data['pt_clas_mm_sales_e_amt']
            ,'pt_clas_sales_rate' 	    => $data['pt_clas_sales_rate']
            ,'sarly_amt' 	            => $data['sarly_amt']
            ,'pt_clas_s_cnt' 	        => $data['pt_clas_s_cnt']
            ,'pt_clas_e_cnt' 	        => $data['pt_clas_e_cnt']
            ,'gx_clas_s_cnt' 	        => $data['gx_clas_s_cnt']
            ,'gx_clas_e_cnt' 	        => $data['gx_clas_e_cnt']
            ,'clas_1tm_amt' 	        => $data['clas_1tm_amt']
            ,'cre_id'	                => $data['cre_id']
            ,'cre_datetm' 		        => $data['cre_datetm']
            ,'mod_id' 		            => $data['mod_id']
            ,'mod_datetm'	            => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    /**
     * 강사 수당설정 sub 를 update 한다. (기본급 처리)
     * @param array $data
     * @return Object
     */
    public function update_sarly_mgmt_for_basic_amt(array $data)
    {
        $sql = 'UPDATE sarly_sub_mgmt_tbl SET
					SARLY_AMT 		   = :sarly_amt:
                WHERE SARLY_MGMT_SNO   = :sarly_mgmt_sno:
                    AND COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
				';
        $query = $this->db->query($sql, [
            'comp_cd' 		        => $data['comp_cd']
            ,'bcoff_cd' 		    => $data['bcoff_cd']
            ,'sarly_amt'	        => $data['sarly_amt']
            ,'sarly_mgmt_sno'	    => $data['sarly_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    /**
     * 강사 수당설정 base 를 insert 한다.
     * @param array $data
     * @return Object
     */
    public function update_sarly_mgmt(array $data)
    {
        $sql = 'UPDATE sarly_mgmt_tbl SET
					COMP_CD 		   = :comp_cd:
					,BCOFF_CD 		   = :bcoff_cd:
					,SARLY_PMT_COND    = :sarly_pmt_cond:
                    ,VAT_YN            = :vat_yn:
                    ,SARLY_MGMT_TITLE  = :sarly_mgmt_title:
                    ,SARLY_MGMT_MEMO   = :sarly_mgmt_memo:
                    ,SARLY_APLY_ITEM_1 = :sarly_aply_item_1:
                    ,SARLY_APLY_ITEM_2 = :sarly_aply_item_2:
                    ,SARLY_APLY_S_DATE = :sarly_aply_s_date:
                    ,SARLY_APLY_E_DATE = :sarly_aply_e_date:
                    ,MOD_ID            = :mod_id:
                    ,MOD_DATETM        = :mod_datetm:
                WHERE SARLY_MGMT_SNO   = :sarly_mgmt_sno:
				';
        $query = $this->db->query($sql, [
            'comp_cd' 		        => $data['comp_cd']
            ,'bcoff_cd' 		    => $data['bcoff_cd']
            ,'sarly_pmt_cond'	    => $data['sarly_pmt_cond']
            ,'vat_yn' 		        => $data['vat_yn']
            ,'sarly_mgmt_title'	    => $data['sarly_mgmt_title']
            ,'sarly_mgmt_memo' 		=> $data['sarly_mgmt_memo']
            ,'sarly_aply_item_1' 	=> $data['sarly_aply_item_1']
            ,'sarly_aply_item_2'	=> $data['sarly_aply_item_2']
            ,'sarly_aply_s_date' 	=> $data['sarly_aply_s_date']
            ,'sarly_aply_e_date' 	=> $data['sarly_aply_e_date']
            ,'mod_id' 		        => $data['mod_id']
            ,'mod_datetm'	        => $data['mod_datetm']
            ,'sarly_mgmt_sno'	    => $data['sarly_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $query;
    }
	
	/**
	 * 강사 수당설정 base 를 insert 한다.
	 * @param array $data
	 * @return Object
	 */
    public function insert_sarly_mgmt(array $data)
	{
		$sql = 'INSERT sarly_mgmt_tbl SET 
					COMP_CD 		   = :comp_cd:
					,BCOFF_CD 		   = :bcoff_cd:
                    ,TCHR_SNO 	       = :tchr_sno:
					,TCHR_ID 	       = :tchr_id:
                    ,TCHR_NM 	       = :tchr_nm:
					,SARLY_PMT_COND    = :sarly_pmt_cond:
                    ,SARLY_PMT_MTHD    = :sarly_pmt_mthd:
                    ,VAT_YN            = :vat_yn:
                    ,SARLY_MGMT_TITLE  = :sarly_mgmt_title:
                    ,SARLY_MGMT_MEMO   = :sarly_mgmt_memo:
                    ,SARLY_APLY_ITEM_1 = :sarly_aply_item_1:
                    ,SARLY_APLY_ITEM_2 = :sarly_aply_item_2:
                    ,SARLY_APLY_S_DATE = :sarly_aply_s_date:
                    ,SARLY_APLY_E_DATE = :sarly_aply_e_date:
                    ,CRE_ID            = :cre_id:
                    ,CRE_DATETM        = :cre_datetm:
                    ,MOD_ID            = :mod_id:
                    ,MOD_DATETM        = :mod_datetm:
				';
		$query = $this->db->query($sql, [
				'comp_cd' 		        => $data['comp_cd']
				,'bcoff_cd' 		    => $data['bcoff_cd']
		        ,'tchr_sno' 		    => $data['tchr_sno']
				,'tchr_id' 		        => $data['tchr_id']
		        ,'tchr_nm' 		        => $data['tchr_nm']
				,'sarly_pmt_cond'	    => $data['sarly_pmt_cond']
    		    ,'sarly_pmt_mthd' 		=> $data['sarly_pmt_mthd']
    		    ,'vat_yn' 		        => $data['vat_yn']
    		    ,'sarly_mgmt_title'	    => $data['sarly_mgmt_title']
    		    ,'sarly_mgmt_memo' 		=> $data['sarly_mgmt_memo']
    		    ,'sarly_aply_item_1' 	=> $data['sarly_aply_item_1']
    		    ,'sarly_aply_item_2'	=> $data['sarly_aply_item_2']
    		    ,'sarly_aply_s_date' 	=> $data['sarly_aply_s_date']
    		    ,'sarly_aply_e_date' 	=> $data['sarly_aply_e_date']
    		    ,'cre_id'	            => $data['cre_id']
    		    ,'cre_datetm' 		    => $data['cre_datetm']
    		    ,'mod_id' 		        => $data['mod_id']
    		    ,'mod_datetm'	        => $data['mod_datetm']
		]);
		
		array_push($data,$query);
		return $query;
	}

    public function delete_sarly_mgmt($data)
    {
        $sql = 'DELETE FROM sarly_mgmt_tbl
                WHERE SARLY_MGMT_SNO = :sarly_mgmt_sno:
				';
        $query = $this->db->query($sql, [
            'sarly_mgmt_sno' 		    => $data['sarly_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $query;
    }
}
