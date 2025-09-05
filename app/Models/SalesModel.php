<?php
namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    private function add_where_rank(array $data)
    {
        $add_where = "";
        
        if (isset($data['sdate']))
        {
            if ($data['sdate'] != '')
            {
                $add_where .= "AND S.CRE_DATETM BETWEEN '" . $data['sdate'] . "' AND '" . $data['edate'] . "'";
            }
        }
        
        return $add_where;
    }
    
    /**
     * PT 매출 순위 [counter]
     * @param array $data
     * @return array
     */
    public function list_pt_rank_count(array $data)
    {
        $add_where = $this->add_where_rank($data);
        $sql = "SELECT COUNT(*) AS counter FROM 
                (
                SELECT MEM_SNO
                FROM sales_mgmt_tbl S
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD = 'PRVN'
                -- AND CLAS_DV IN ('11','21')
                {$add_where}
                GROUP BY MEM_SNO
                ) AS t
				";
                
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * PT 매출 순위
     * @param array $data
     */
    public function list_pt_rank(array $data)
    {
        $add_where = $this->add_where_rank($data);
        $sql = "SELECT S.COMP_CD, S.BCOFF_CD, S.MEM_SNO, S.MEM_NM , S.MEM_ID , SUM(S.PAYMT_AMT) AS sum_paymt_amt , MAX(S.CRE_DATETM) as last_cre_datetm
                    , rank() over (ORDER BY SUM(S.PAYMT_AMT) DESC ) AS paymt_ranking, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                    FROM sales_mgmt_tbl S
                    LEFT JOIN mem_info_detl_tbl MID ON S.MEM_SNO = MID.MEM_SNO
                    WHERE S.COMP_CD = :comp_cd:
                    AND S.BCOFF_CD = :bcoff_cd:
                    AND S.1RD_CATE_CD = 'PRVN'
                    -- AND CLAS_DV IN ('11','21')
                    {$add_where}
                    GROUP BY S.MEM_SNO, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                    ORDER BY paymt_ranking ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    
	/**
	 * 매출 총합 및 매출 순위를 가져온다.
	 * @param array $data
	 * @return array
	 */
    public function get_rank_info(array $data)
    {
        $sql = "SELECT * FROM (
                    SELECT COMP_CD, BCOFF_CD, MEM_SNO, MEM_NM , SUM(PAYMT_AMT) AS sum_paymt_amt , 
                    rank() over (ORDER BY SUM(PAYMT_AMT) DESC ) AS paymt_ranking 
                    FROM sales_mgmt_tbl
                    WHERE COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
                    GROUP BY MEM_SNO
                    ) AS rt
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
    
    
    private function add_where(array $data)
    {
        $add_where = "";
        
        if (isset($data['1rd_cate_cd']))
        {
            if ($data['1rd_cate_cd'] != '')
            {
                $add_where .= "AND S.1RD_CATE_CD = '" . $data['1rd_cate_cd'] . "'";
            }
        }
        
        if (isset($data['2rd_cate_cd']))
        {
            if ($data['2rd_cate_cd'] != '')
            {
                $add_where .= "AND S.2RD_CATE_CD = '" . $data['2rd_cate_cd'] . "'";
            }
        }
        
        if (isset($data['sales_dv']))
        {
            if ($data['sales_dv'] != '')
            {
                $add_where .= "AND S.SALES_DV = '" . $data['sales_dv'] . "'";
            }
        }
        
        if (isset($data['clas_dv']))
        {
            if ($data['clas_dv'] != '')
            {
                if($data['clas_dv']=='21,22')
                    $add_where .= "AND (S.1RD_CATE_CD = 'PRVN' OR IFNULL(S.CLAS_DV, '') IN (" . $data['clas_dv'] . "))";
                else
                    $add_where .= "AND (IFNULL(S.CLAS_DV, '') IN (" . $data['clas_dv'] . ") OR S.1RD_CATE_CD = 'PRVN')";
            }
        }
        
        // 회원명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND (S.MEM_NM LIKE '%{$data['snm']}%' OR S.MEM_ID LIKE '%{$data['snm']}%') ";
            }
        }
        
        // 강사명
        if (isset($data['tnm']))
        {
            if ($data['tnm'] != '')
            {
                $add_where .= " AND (S.PTHCR_ID LIKE '%{$data['tnm']}%' OR M2.MEM_ID LIKE '%{$data['tnm']}%') ";
            }
        }

        // 상품명
        if (isset($data['senm']))
        {
            if ($data['senm'] != '')
            {
                $add_where .= " AND S.SELL_EVENT_NM LIKE '%{$data['senm']}%' ";
            }
        }
        
        // 결제상태
        if (isset($data['spstat']))
        {
            if ($data['spstat'] != '')
            {
                $add_where .= " AND S.PAYMT_STAT = '{$data['spstat']}' ";
            }
        }
        
        // 결제채널
        if (isset($data['spchnl']))
        {
            if ($data['spchnl'] != '')
            {
                $add_where .= " AND S.PAYMT_CHNL = '{$data['spchnl']}' ";
            }
        }
        
        // 결제수단
        if (isset($data['spmthd']))
        {
            if ($data['spmthd'] != '')
            {
                $add_where .= " AND S.PAYMT_MTHD = '{$data['spmthd']}' ";
            }
        }
        
        // 결제수단
        if (isset($data['spdv']))
        {
            if ($data['spdv'] != '')
            {
                $add_where .= " AND S.SALES_DV = '{$data['spdv']}' ";
            }
        }
        
        // 결제수단
        if (isset($data['spdvr']))
        {
            if ($data['spdvr'] != '')
            {
                $add_where .= " AND S.SALES_DV_RSON = '{$data['spdvr']}' ";
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
                        case "sc":
                            $add_where .= " AND S.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "sd":
                            $add_where .= " AND S.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        // 판매강사 아이디
        if (isset($data['ptchr_id']))
        {
            if ($data['ptchr_id'] != '')
            {
                $add_where .= " AND S.PTHCR_ID = '{$data['ptchr_id']}' ";
            }
        }
        
        return $add_where;
    }
    
    
    /**
     * 매출 정보를 가져온다. [sum_cost]
     * @param array $data
     * @return array
     */
    public function list_sales_mgmt_sum_cost(array $data)
    {
        $add_where = $this->add_where($data);
        $sql = "SELECT SUM(S.PAYMT_AMT) as sum_cost FROM
                sales_mgmt_tbl S LEFT JOIN mem_main_info_tbl M2 ON S.PTHCR_ID = M2.MEM_ID AND S.COMP_CD = M2.SET_COMP_CD AND S.BCOFF_CD = M2.SET_BCOFF_CD
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
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
     * 매출 정보를 가져온다. [counter]
     * @param array $data
     * @return array
     */
    public function list_sales_mgmt_count(array $data)
    {
    	$add_where = $this->add_where($data);
    	$sql = "SELECT COUNT(*) as counter FROM
                sales_mgmt_tbl S LEFT JOIN mem_main_info_tbl M2 ON S.PTHCR_ID = M2.MEM_ID AND S.COMP_CD = M2.SET_COMP_CD AND S.BCOFF_CD = M2.SET_BCOFF_CD
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
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
     * 매출 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_sales_mgmt(array $data)
    {
        $add_where = $this->add_where($data);
        $sql = "SELECT S.*, M.CATE_NM, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM  sales_mgmt_tbl S LEFT JOIN m_cate2 M ON S.1RD_CATE_CD = M.M_CATE 
                                       LEFT JOIN mem_main_info_tbl M2 ON S.PTHCR_ID = M2.MEM_ID AND S.COMP_CD = M2.SET_COMP_CD AND S.BCOFF_CD = M2.SET_BCOFF_CD
                                       LEFT JOIN mem_info_detl_tbl MID ON S.MEM_SNO = MID.MEM_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY S.CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    
    private function list_pt_clas_mgmt_where(array $data)
    {
        $add_where = "";
        
        // 강사명
        if (isset($data['stnm']))
        {
            if ($data['stnm'] != '')
            {
                $add_where .= " AND (a.STCHR_NM LIKE '%{$data['stnm']}%' OR a.STCHR_ID LIKE '%{$data['stnm']}%') ";
            }
        }

        // 회원명
        if (isset($data['mem_sno']))
        {
            if ($data['mem_sno'] != '')
            {
                $add_where .= " AND (a.MEM_SNO  = '{$data['mem_sno']}') ";
            }
        }
        
        // 회원명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND (a.MEM_NM LIKE '%{$data['snm']}%' OR a.MEM_ID LIKE '%{$data['snm']}%') ";
            }
        }
        
        // 상품명
        if (isset($data['senm']))
        {
            if ($data['senm'] != '')
            {
                $add_where .= " AND a.SELL_EVENT_NM LIKE '%{$data['senm']}%' ";
            }
        }
        
        // 자동차감
        if (isset($data['scyn']))
        {
            if ($data['scyn'] != '')
            {
                $add_where .= " AND a.AUTO_CHK = '{$data['scyn']}' ";
            }
        }
        
        // 정규/서비스구분
        if (isset($data['scdv']))
        {
            if ($data['scdv'] != '')
            {
                $add_where .= " AND a.PT_CLAS_DV = '{$data['scdv']}' ";
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
                        case "sc":
                            $add_where .= " AND a.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * PT 진행 금액을 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_pt_clas_mgmt_sum_cost(array $data)
    {
        $add_where = $this->list_pt_clas_mgmt_where($data);
        $sql = "SELECT SUM(TCHR_1TM_CLAS_PRGS_AMT) as sum_cost FROM
                pt_clas_mgmt_tbl a
                WHERE a.COMP_CD = :comp_cd:
                AND a.BCOFF_CD = :bcoff_cd:
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
     * PT 진행 금액을 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_pt_clas_mgmt_count(array $data)
    {
        $add_where = $this->list_pt_clas_mgmt_where($data);
    	$sql = "SELECT COUNT(*) as counter FROM
                pt_clas_mgmt_tbl a
                WHERE a.COMP_CD = :comp_cd:
                AND a.BCOFF_CD = :bcoff_cd:
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
     * PT 진행 금액을 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_pt_clas_mgmt(array $data)
    {
        $add_where = $this->list_pt_clas_mgmt_where($data);
        $sql = "SELECT a.*,
                       b.MEM_THUMB_IMG AS MEM_THUMB_IMG,
                       b.MEM_MAIN_IMG AS MEM_MAIN_IMG,
                       b.MEM_GENDR AS MEM_GENDR,
                       c.MEM_THUMB_IMG AS TCHR_THUMB_IMG,
                       c.MEM_MAIN_IMG AS TCHR_MAIN_IMG,
                       c.MEM_GENDR AS TCHR_GENDR,
                       c.MEM_SNO AS TCHR_SNO
                FROM pt_clas_mgmt_tbl a
                LEFT JOIN mem_info_detl_tbl b ON a.MEM_ID = b.MEM_ID AND a.COMP_CD = b.COMP_CD AND a.BCOFF_CD = b.BCOFF_CD
                LEFT JOIN mem_info_detl_tbl c ON a.STCHR_ID = c.MEM_ID AND a.COMP_CD = c.COMP_CD AND a.BCOFF_CD = c.BCOFF_CD
                WHERE a.COMP_CD = :comp_cd:
                AND a.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY a.CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                ]);
                array_push($data,$query);
                return $query->getResultArray();
    }
	
    
}