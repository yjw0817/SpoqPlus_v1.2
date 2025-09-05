<?php
namespace App\Models;

use CodeIgniter\Model;

class AttdModel extends Model
{
    
    /**
     * 재입장인지 체크를 위한 출석현황 리턴
     * @param array $data
     * @return array
     */
    public function list_tchr_attd_mgmt(array $data)
    {
        $sql = "SELECT * FROM attdt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND ATTD_YY = :attd_yy:
                AND ATTD_MM = :attd_mm:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'attd_yy'                  => $data['attd_yy']
            ,'attd_mm'                  => $data['attd_mm']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 강사 : 년월 , 정상출근, 지각 카운트 리텅
     * @param array $data
     * @return array
     */
    public function count_tchr_attd_mgmt_status(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM attdt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND ATTD_YY = :attd_yy:
                AND ATTD_MM = :attd_mm:
                AND ATTD_DV = :attd_dv:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'attd_yy'                  => $data['attd_yy']
            ,'attd_mm'                  => $data['attd_mm']
            ,'attd_dv'                  => $data['attd_dv']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 강사 : 재입장인지 체크를 위한 출근현황 리턴
     * @param array $data
     * @return array
     */
    public function count_tchr_attd_mgmt(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM attdt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND ATTD_YMD = :attd_ymd:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'attd_ymd'                 => $data['attd_ymd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 강사 : 출석관리 insert
     * @param array $data
     */
    public function insert_attdt_mgmt(array $data)
    {
        $sql = "INSERT attdt_mgmt_tbl SET
					COMP_CD          = :comp_cd:
					,BCOFF_CD        = :bcoff_cd:
                    ,MEM_SNO         = :mem_sno:
                    ,MEM_ID          = :mem_id:
                    ,MEM_NM          = :mem_nm:
                    ,MEM_DV          = :mem_dv:
                    ,ATTD_YMD        = :attd_ymd:
                    ,ATTD_YY         = :attd_yy:
                    ,ATTD_MM         = :attd_mm:
                    ,ATTD_DD         = :attd_dd:
                    ,ATTD_DOTW       = :attd_dotw:
                    ,ATTD_HH         = :attd_hh:
					,ATTD_DV		 = :attd_dv:
                    ,CRE_ID          = :cre_id:
                    ,CRE_DATETM      = :cre_datetm:
                    ,MOD_ID          = :mod_id:
                    ,MOD_DATETM      = :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'comp_cd'           => $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_nm'			=> $data['mem_nm']
            ,'mem_dv'			=> $data['mem_dv']
            ,'attd_ymd'         => $data['attd_ymd']
            ,'attd_yy'          => $data['attd_yy']
            ,'attd_mm'          => $data['attd_mm']
            ,'attd_dd'          => $data['attd_dd']
            ,'attd_dotw'        => $data['attd_dotw']
            ,'attd_hh'			=> $data['attd_hh']
            ,'attd_dv'			=> $data['attd_dv']
            ,'cre_id'           => $data['cre_id']
            ,'cre_datetm'       => $data['cre_datetm']
            ,'mod_id'           => $data['mod_id']
            ,'mod_datetm'       => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 재입장인지 체크를 위한 출석현황 리턴
     * @param array $data
     * @return array
     */
    public function count_1tm_attd_mgmt(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM attd_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND ATTD_YMD = :attd_ymd:
                AND ATTD_YN = 'Y'
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'attd_ymd'                 => $data['attd_ymd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 재입장인지 체크를 위한 출석현황 리턴
     * @param array $data
     * @return array
     */
    public function get_re_attd_mgmt_limit(array $data)
    {
        $sql = "SELECT CRE_DATETM FROM attd_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND ATTD_YMD = :attd_ymd:
                ORDER BY CRE_DATETM DESC LIMIT 1
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'attd_ymd'                 => $data['attd_ymd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 출석할 회원 정보
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
            ,'mem_sno'                  => $data['mem_sno'] ?? 0
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 이용중인 회원정보 리스트
     * @param array $data
     * @return array
     */
    public function list_buy_event_mgmt(array $data)
    {
        $sql = "SELECT B.BUY_EVENT_SNO, B.STCHR_ID, S.SELL_EVENT_NM , B.ACC_RTRCT_DV, B.ACC_RTRCT_MTHD,B.CLAS_DV, S.EVENT_TYPE, S.WEEK_SELECT, S.PRE_ENTER_MIN, S.ENTER_FROM_TIME, S.ENTER_TO_TIME, S.USE_PER_DAY, S.USE_PER_WEEK, S.USE_PER_WEEK, 
                FROM buy_event_mgmt_tbl B INNER JOIN sell_event_mgmt_tbl S ON B.SELL_EVENT_SNO = S.SELL_EVENT_SNO
                WHERE B.COMP_CD = :comp_cd:
                AND B.BCOFF_CD = :bcoff_cd:
                AND B.MEM_SNO = :mem_sno:
                AND B.EVENT_STAT = '00'
                ORDER BY B.ACC_RTRCT_DV DESC, B.CLAS_DV ASC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno'] ?? 0
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }

     /**
     * 입장 가능한 이용권 리스트
     * @param array $data
     * @return array
     */
    

    public function cur_available_membership(array $data)
    {
        // BUY_EVENT_SNO 조건절 구성
        $buyEventCondition = "";
        $params = [
            'comp_cd'   => $data['comp_cd'],
            'bcoff_cd'  => $data['bcoff_cd'],
            'mem_sno'   => $data['mem_sno']
        ];
        
        if (isset($data['buy_event_sno']) && !empty($data['buy_event_sno'])) {
            $buyEventCondition = "AND BUY_EVENT_SNO = :buy_event_sno:";
            $params['buy_event_sno'] = $data['buy_event_sno'];
        }
        
        $sql = "SELECT *
                FROM (SELECT *
                FROM cur_admittable_gx_class
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND EVENT_STAT = '00'
                $buyEventCondition

                UNION

                SELECT *
                FROM cur_available_membership
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                AND EVENT_STAT = '00'
                $buyEventCondition) AS T
                 
                -- WHERE (((T.USE_PER_DAY > 0 AND 오늘출석수 < T.USE_PER_DAY) OR IFNULL(T.USE_PER_DAY, 0) = 0)
                --      AND ((T.USE_PER_WEEK > 0 AND 주간이용일수 < T.USE_PER_WEEK AND USE_PER_WEEK_UNIT = '10') OR IFNULL(T.USE_PER_WEEK, 0) = 0 OR T.USE_PER_WEEK_UNIT = '0')
                --      AND ((T.USE_PER_WEEK > 0 AND 주간출석수 < T.USE_PER_WEEK AND USE_PER_WEEK_UNIT = '20') OR IFNULL(T.USE_PER_WEEK, 0) = 0 OR T.USE_PER_WEEK_UNIT = '0')
                --      )
                ORDER BY ACC_RTRCT_DV DESC, CLAS_DV ASC
				";
        
        $query = $this->db->query($sql, $params);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 전화번호로 현재 이용 가능한 이용권 조회
     * @param array $data
     * @return array
     */
    public function cur_available_membership_by_telno(array $data)
    {
        // BUY_EVENT_SNO 조건절 구성
        $buyEventCondition = "";
        $params = [
            'comp_cd'   => $data['comp_cd'],
            'bcoff_cd'  => $data['bcoff_cd'],
            'mem_telno' => $data['mem_telno']
        ];
        
        if (isset($data['buy_event_sno']) && !empty($data['buy_event_sno'])) {
            $buyEventCondition = "AND BUY_EVENT_SNO = :buy_event_sno:";
            $params['buy_event_sno'] = $data['buy_event_sno'];
        }
        
        $sql = "SELECT * 
                FROM (SELECT *
                FROM cur_admittable_gx_class
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_ID = :mem_telno:
                AND EVENT_STAT = '00'
                $buyEventCondition

                UNION

                SELECT *
                FROM cur_available_membership
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_ID = :mem_telno:
                AND EVENT_STAT = '00'
                $buyEventCondition) AS T
                
                ORDER BY ACC_RTRCT_DV DESC, CLAS_DV ASC
				";
        
        $query = $this->db->query($sql, $params);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 출석관리 insert
     * @param array $data
     */
    public function insert_attd_mgmt(array $data)
    {
        $sql = "INSERT attd_mgmt_tbl SET
					COMP_CD          = :comp_cd:
					,BCOFF_CD        = :bcoff_cd:
                    ,MEM_SNO         = :mem_sno:
                    ,MEM_ID          = :mem_id:
                    ,MEM_NM          = :mem_nm:
                    ,MEM_DV          = :mem_dv:
                    ,ATTD_MTHD       = :attd_mthd:
                    ,ATTD_YN         = :attd_yn:
                    ,ACC_RTRCT_DV    = :acc_rtrct_dv:
                    ,ACC_RTRCT_MTHD  = :acc_rtrct_mthd:
                    ,AGEGRP          = :agegrp:
                    ,MEM_GENDR       = :mem_gendr:
                    ,ATTD_YMD        = :attd_ymd:
                    ,ATTD_YY         = :attd_yy:
                    ,ATTD_MM         = :attd_mm:
                    ,ATTD_DD         = :attd_dd:
                    ,ATTD_DOTW       = :attd_dotw:
                    ,ATTD_HH         = :attd_hh:
					,AUTO_CHK		 = :auto_chk:
                    ,CRE_ID          = :cre_id:
                    ,CRE_DATETM      = :cre_datetm:
                    ,MOD_ID          = :mod_id:
                    ,MOD_DATETM      = :mod_datetm:
                    ,SELL_EVENT_SNO  = :sell_event_sno:
                    ,BUY_EVENT_SNO   = :buy_event_sno:
                    ,M_CATE           = :m_cate:
                    ,GX_SCHD_MGMT_SNO = :gx_schd_mgmt_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd'           => $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_nm'			=> $data['mem_nm']
            ,'mem_dv'			=> $data['mem_dv']
            ,'attd_mthd'		=> $data['attd_mthd']
            ,'attd_yn'			=> $data['attd_yn']
            ,'acc_rtrct_dv'     => $data['acc_rtrct_dv']
            ,'acc_rtrct_mthd'   => $data['acc_rtrct_mthd']
            ,'agegrp'           => $data['agegrp']
            ,'mem_gendr'        => $data['mem_gendr']
            ,'attd_ymd'         => $data['attd_ymd']
            ,'attd_yy'          => $data['attd_yy']
            ,'attd_mm'          => $data['attd_mm']
            ,'attd_dd'          => $data['attd_dd']
            ,'attd_dotw'        => $data['attd_dotw']
            ,'attd_hh'			=> $data['attd_hh']
        	,'auto_chk'			=> $data['auto_chk']
            ,'cre_id'           => $data['cre_id']
            ,'cre_datetm'       => $data['cre_datetm']
            ,'mod_id'           => $data['mod_id']
            ,'mod_datetm'       => $data['mod_datetm']
            ,'sell_event_sno'   => $data['sell_event_sno']
            ,'buy_event_sno'    => $data['buy_event_sno']
            ,'m_cate'           => $data['m_cate']
            ,'gx_schd_mgmt_sno' => $data['gx_schd_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 출석현황의 출석일수를 가져온다.
     * @param array $data
     * @return array
     */
    public function count_attd_mgmt_for_mem_sno(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM 
                (
                    SELECT ATTD_YMD FROM attd_mgmt_tbl
                    WHERE MEM_SNO = :mem_sno:
                    AND COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
                    AND ATTD_YN = 'Y'
                    AND ATTD_YMD BETWEEN :attd_sdate: AND :attd_edate:
                    GROUP BY ATTD_YMD
                ) AS T
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'                   => $data['comp_cd']
            ,'bcoff_cd'                 => $data['bcoff_cd']
            ,'mem_sno'                  => $data['mem_sno']
            ,'attd_sdate'               => $data['attd_sdate']
            ,'attd_edate'               => $data['attd_edate']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 해당 회원의 출석 리스트를 가져온다. limit 16
     * @param array $data
     * @return array
     */
    public function get_attd_mgmt_16_for_mem_sno(array $data)
    {
    	$sql = "SELECT ATTD_YN, AUTO_CHK, CRE_DATETM FROM attd_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_SNO = :mem_sno:
				ORDER BY CRE_DATETM DESC
				LIMIT 16
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'                   => $data['comp_cd']
    			,'bcoff_cd'                 => $data['bcoff_cd']
    			,'mem_sno'                  => $data['mem_sno']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    private function list_attd_mgmt_where(array $data)
    {
        $add_where = "";
        
        // 회원명
        if (isset($data['mem_sno']))
        {
            if ($data['mem_sno'] != '')
            {
                $add_where .= " AND A.MEM_SNO = '{$data['mem_sno']}' ";
            }
        }
        
        // 회원명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND (A.MEM_NM LIKE '%{$data['snm']}%' OR A.MEM_ID LIKE '%{$data['snm']}%') ";
            }
        }
        
        // 자동차감
        if (isset($data['scyn']))
        {
            if ($data['scyn'] != '')
            {
                if($data['scyn'] == 'Y')
                {
                    $add_where .= " AND A.CLAS_MGMT_SNO IS NOT NULL ";
                } else{
                    $add_where .= " AND A.CLAS_MGMT_SNO IS NULL ";
                }
               
            } 
        }
        
        // 정상/재입장
        if (isset($data['satc']))
        {
            if ($data['satc'] != '')
            {
                $add_where .= " AND A.ATTD_YN = '{$data['satc']}' ";
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
                        case "ac":
                            $add_where .= " AND A.MOD_DATETM BETWEEN '{$sdate_full}' AND '{$edate_full}' ";
                            break;
                    }
                }
                // 시작일만 있는 경우
                else if ($sdate != '' && $edate == '')
                {
                    $sdate_full = $sdate . " 00:00:00";
                    
                    switch ($data['sdcon'])
                    {
                        case "ac":
                            $add_where .= " AND A.MOD_DATETM >= '{$sdate_full}' ";
                            break;
                    }
                }
                // 종료일만 있는 경우
                else if ($sdate == '' && $edate != '')
                {
                    $edate_full = $edate . " 23:59:59";
                    
                    switch ($data['sdcon'])
                    {
                        case "ac":
                            $add_where .= " AND A.MOD_DATETM <= '{$edate_full}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 회원 출석 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_attd_mgmt_count(array $data)
    {
        $add_where = $this->list_attd_mgmt_where($data);
    	$sql = "SELECT COUNT(*) as counter FROM attd_mgmt_tbl A
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
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
     * 회원 출석 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_attd_mgmt(array $data)
    {
        $add_where = $this->list_attd_mgmt_where($data);
        $sql = "select A.*, DATE_FORMAT(A.MOD_DATETM, '%Y년 %c월 %e일') AS ATTD_DT,
                DATE_FORMAT(A.CRE_DATETM, '%k시 %i분') AS ATTD_HM, S.SELL_EVENT_NM, G.GX_STCHR_NM, G.GX_CLAS_TITLE, G.GX_CLAS_S_HH_II, G.GX_CLAS_E_HH_II, P.STCHR_NM, P.CLAS_CNT, M.MEM_MAIN_IMG, M.MEM_THUMB_IMG, M.MEM_GENDR
                from attd_mgmt_tbl A  LEFT JOIN mem_info_detl_tbl AS M ON M.MEM_SNO = A.MEM_SNO 
                LEFT JOIN sell_event_mgmt_tbl AS S ON S.SELL_EVENT_SNO = A.SELL_EVENT_SNO
                LEFT JOIN gx_schd_mgmt_tbl AS G ON G.GX_SCHD_MGMT_SNO = A.GX_SCHD_MGMT_SNO
                LEFT JOIN pt_clas_mgmt_tbl AS P ON P.CLAS_MGMT_SNO = A.CLAS_MGMT_SNO
				WHERE A.COMP_CD = :comp_cd:
				AND A.BCOFF_CD = :bcoff_cd:
                {$add_where}
				ORDER BY A.MOD_DATETM DESC
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
    
    
    
    
	
	
	
	
	
	
	
	
	
    
    
    
}