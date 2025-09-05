<?php
namespace App\Models;

use CodeIgniter\Model;

class ClasModel extends Model
{
    /**
     * GX 현황 검색에 대한 검색 조건
     * @param array $data
     * @return array
     */
    private function tgx_where(array $data)
    {
        $return_where = "";
        
        if (isset($data['sdate']))
        {
            if ($data['sdate'] != '')
            {
                $return_where .= " AND GX_CLAS_S_DATE BETWEEN '{$data['sdate']}' AND '{$data['edate']}'";
            }
        }
        
        if (isset($data['gx_stchr_id']))
        {
            if ($data['gx_stchr_id'] != '')
            {
                $return_where .= " AND S.GX_STCHR_ID = '{$data['gx_stchr_id']}'";
            }
        }
        
        if (isset($data['gx_clas_title']))
        {
            if ($data['gx_clas_title'] != '')
            {
                $return_where .= " AND S.GX_CLAS_TITLE = '{$data['gx_clas_title']}'";
            }
        }
        
        return $return_where;
    }
    
    
    
    /**
     * GX에 대한 수업 진행 여부 및 수업 관련 리스트
     * @param array $data
     * @return array
     */
    public function list_tgx_schd_count(array $data)
    {
        $add_where = $this->tgx_where($data);
        $sql = "SELECT COUNT(*) as counter FROM
                gx_schd_mgmt_tbl AS S
                LEFT OUTER JOIN gx_room_mgmt_tbl AS R
                ON S.GX_ROOM_MGMT_SNO = R.GX_ROOM_MGMT_SNO
                LEFT OUTER JOIN
                gx_clas_mgmt_tbl AS M
                ON S.GX_SCHD_MGMT_SNO = M.GX_SCHD_MGMT_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
                
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    
    /**
     * GX에 대한 수업 진행 여부 및 수업 관련 리스트
     * @param array $data
     * @return array
     */
    public function list_tgx_schd(array $data)
    {
        $add_where = $this->tgx_where($data);
        $sql = "SELECT R.GX_ROOM_TITLE, S.*, M.GX_CLAS_DATE, M.CLAS_CHK_YN, M.CRE_DATETM AS chk_cre_datetm, T.MEM_THUMB_IMG, T.MEM_MAIN_IMG, T.MEM_GENDR, T.MEM_SNO AS GX_STCHR_SNO FROM 
                gx_schd_mgmt_tbl AS S
                LEFT OUTER JOIN gx_room_mgmt_tbl AS R
                ON S.GX_ROOM_MGMT_SNO = R.GX_ROOM_MGMT_SNO
                LEFT OUTER JOIN 
                gx_clas_mgmt_tbl AS M
                ON S.GX_SCHD_MGMT_SNO = M.GX_SCHD_MGMT_SNO
                LEFT OUTER JOIN
                mem_info_detl_tbl AS T
                ON S.GX_STCHR_SNO = T.MEM_SNO AND S.COMP_CD = T.COMP_CD AND S.BCOFF_CD = T.BCOFF_CD
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY S.GX_CLAS_S_DATE DESC, S.GX_CLAS_S_HH_II DESC, S.GX_ROOM_MGMT_SNO ASC
                limit {$data['limit_s']} , {$data['limit_e']}";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * GX에 대한 수업 진행 여부 및 수업 관련 리스트
     * @param array $data
     * @return array
     */
    public function list_tgx_schd_mobile(array $data)
    {
        $sql = "SELECT R.GX_ROOM_TITLE, S.*, M.GX_CLAS_DATE, M.CLAS_CHK_YN, M.CRE_DATETM AS chk_cre_datetm FROM
                gx_schd_mgmt_tbl AS S
                LEFT OUTER JOIN gx_room_mgmt_tbl AS R
                ON S.GX_ROOM_MGMT_SNO = R.GX_ROOM_MGMT_SNO
                LEFT OUTER JOIN
                gx_clas_mgmt_tbl AS M
                ON S.GX_SCHD_MGMT_SNO = M.GX_SCHD_MGMT_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                AND S.GX_STCHR_ID = :gx_stchr_id:
                AND GX_CLAS_S_DATE BETWEEN :sdate: AND :edate: 
                ORDER BY S.GX_CLAS_S_DATE DESC, S.GX_CLAS_S_HH_II DESC, S.GX_ROOM_MGMT_SNO ASC
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'            => $data['sdate']
            ,'edate'            => $data['edate']
            ,'gx_stchr_id'      => $data['gx_stchr_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * GX에 대한 수업 진행 여부 및 수업 관련 리스트
     * @param array $data
     * @return array
     */
    public function list_tgx_schd_mobile_all_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM
                gx_schd_mgmt_tbl AS S
                LEFT OUTER JOIN gx_room_mgmt_tbl AS R
                ON S.GX_ROOM_MGMT_SNO = R.GX_ROOM_MGMT_SNO
                LEFT OUTER JOIN
                gx_clas_mgmt_tbl AS M
                ON S.GX_SCHD_MGMT_SNO = M.GX_SCHD_MGMT_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                AND S.GX_STCHR_ID = :gx_stchr_id:
                AND GX_CLAS_S_DATE BETWEEN :sdate: AND :edate:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'            => $data['sdate']
            ,'edate'            => $data['edate']
            ,'gx_stchr_id'      => $data['gx_stchr_id']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * GX에 대한 수업 진행 여부 및 수업 관련 리스트
     * @param array $data
     * @return array
     */
    public function list_tgx_schd_mobile_y_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM
                gx_schd_mgmt_tbl AS S
                LEFT OUTER JOIN gx_room_mgmt_tbl AS R
                ON S.GX_ROOM_MGMT_SNO = R.GX_ROOM_MGMT_SNO
                LEFT OUTER JOIN
                gx_clas_mgmt_tbl AS M
                ON S.GX_SCHD_MGMT_SNO = M.GX_SCHD_MGMT_SNO
                WHERE S.COMP_CD = :comp_cd:
                AND S.BCOFF_CD = :bcoff_cd:
                AND S.GX_STCHR_ID = :gx_stchr_id:
                AND GX_CLAS_S_DATE BETWEEN :sdate: AND :edate:
                AND CLAS_CHK_YN = 'Y'
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'            => $data['sdate']
            ,'edate'            => $data['edate']
            ,'gx_stchr_id'      => $data['gx_stchr_id']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 회원 모바일 오늘 진행해야할 GX 수업 내역을 가져온다.
     * @param array $data
     */
    public function user_today_gx_schd(array $data)
    {
        $sql = "SELECT T.*, R.GX_ROOM_TITLE
                FROM gx_schd_mgmt_tbl AS T
                LEFT OUTER JOIN gx_room_mgmt_tbl AS R ON T.GX_ROOM_MGMT_SNO = R.GX_ROOM_MGMT_SNO
                WHERE 
                T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                AND T.GX_CLAS_S_DATE = :gx_clas_s_date:
                ORDER BY T.GX_CLAS_S_HH_II ASC
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'gx_clas_s_date'	=> $data['gx_clas_s_date']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 모바일 오늘 진행해야할 GX 수업 내역을 가져온다.
     * @param array $data
     */
    public function user_today_gx_schd_group(array $data)
    {
        $sql = "SELECT 
                T.GX_ROOM_MGMT_SNO,T.COMP_CD,T.BCOFF_CD,T.GX_SCHD_MGMT_SNO,T.GX_STCHR_ID,T.GX_STCHR_NM,R.GX_ROOM_TITLE,T.GX_CLAS_TITLE
                FROM gx_schd_mgmt_tbl AS T
                LEFT OUTER JOIN gx_room_mgmt_tbl AS R ON T.GX_ROOM_MGMT_SNO = R.GX_ROOM_MGMT_SNO
                WHERE
                T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                AND T.GX_CLAS_S_DATE = :gx_clas_s_date:
                GROUP BY GX_ROOM_MGMT_SNO , GX_CLAS_TITLE
                ORDER BY GX_ROOM_MGMT_SNO ASC
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'gx_clas_s_date'	=> $data['gx_clas_s_date']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 오늘 진행해야할 GX 수업 내역을 가져온다.
     * @param array $data
     */
    public function my_today_gx_schd(array $data)
    {
        $sql = "SELECT T.*, A.CLAS_CHK_YN, A.GX_STCHR_NM AS CHK_GX_STCHR_NM FROM gx_schd_mgmt_tbl AS T
                LEFT OUTER JOIN gx_clas_mgmt_tbl AS A ON T.GX_SCHD_MGMT_SNO = A.GX_SCHD_MGMT_SNO
                WHERE 
                T.COMP_CD = :comp_cd:
                AND T.BCOFF_CD = :bcoff_cd:
                AND T.GX_STCHR_ID = :gx_stchr_id:
                AND T.GX_CLAS_S_DATE = :gx_clas_s_date:
                ORDER BY T.GX_CLAS_S_HH_II ASC
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'gx_stchr_id'	    => $data['gx_stchr_id']
            ,'gx_clas_s_date'	=> $data['gx_clas_s_date']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * GX 수업관리 update
     * @param array $data
     * @return array
     */
    public function update_gx_clas_mgmt(array $data)
    {
        $sql = "UPDATE gx_clas_mgmt_tbl SET
					CLAS_CHK_YN                  = :clas_chk_yn:
                    , MOD_ID                    = :mod_id:
                    , MOD_DATETM                = :mod_datetm:
                    WHERE GX_SCHD_MGMT_SNO        = :gx_schd_mgmt_sno:
                    AND COMP_CD                = :comp_cd:
					AND BCOFF_CD               = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'clas_chk_yn' 	     => $data['clas_chk_yn']
            ,'mod_id'	         => $data['mod_id']
            ,'mod_datetm'		 => $data['mod_datetm']
            ,'gx_schd_mgmt_sno'	 => $data['gx_schd_mgmt_sno']
            ,'comp_cd'		     => $data['comp_cd']
            ,'bcoff_cd'		     => $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * GX 수업관리 insert
     * @param array $data
     */
    public function insert_gx_clas_mgmt(array $data)
    {
        $sql = "INSERT gx_clas_mgmt_tbl SET
					GX_SCHD_MGMT_SNO        = :gx_schd_mgmt_sno:
                    ,GX_ROOM_MGMT_SNO       = :gx_room_mgmt_sno:
                    ,COMP_CD                = :comp_cd:
                    ,BCOFF_CD               = :bcoff_cd:
					,GX_STCHR_SNO		    = :gx_stchr_sno:
                    ,GX_STCHR_ID            = :gx_stchr_id:
                    ,GX_STCHR_NM            = :gx_stchr_nm:
                    ,GX_STCHR_DV            = :gx_stchr_dv:
                    ,GX_CLAS_TITLE          = :gx_clas_title:
                    ,GX_CLAS_DATE           = :gx_clas_date:
                    ,GX_CLAS_DOTW		    = :gx_clas_dotw:
                    ,GX_CLAS_S_HH_II		= :gx_clas_s_hh_ii:
                    ,GX_CLAS_E_HH_II        = :gx_clas_e_hh_ii:
					,CLAS_CHK_YN            = :clas_chk_yn:
                    ,CRE_ID                 = :cre_id:
					,CRE_DATETM             = :cre_datetm:
					,MOD_ID                 = :mod_id:
					,MOD_DATETM             = :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'gx_schd_mgmt_sno' 	  => $data['gx_schd_mgmt_sno']
            ,'gx_room_mgmt_sno'	  => $data['gx_room_mgmt_sno']
            ,'comp_cd'	          => $data['comp_cd']
            ,'bcoff_cd'           => $data['bcoff_cd']
            ,'gx_stchr_sno'		  => $data['gx_stchr_sno']
            ,'gx_stchr_id'		  => $data['gx_stchr_id']
            ,'gx_stchr_nm'		  => $data['gx_stchr_nm']
            ,'gx_stchr_dv'	      => $data['gx_stchr_dv']
            ,'gx_clas_title'	  => $data['gx_clas_title']
            ,'gx_clas_date'		  => $data['gx_clas_date']
            ,'gx_clas_dotw'		  => $data['gx_clas_dotw']
            ,'gx_clas_s_hh_ii'	  => $data['gx_clas_s_hh_ii']
            ,'gx_clas_e_hh_ii'	  => $data['gx_clas_e_hh_ii']
            ,'clas_chk_yn'		  => $data['clas_chk_yn']
            ,'cre_id'			  => $data['cre_id']
            ,'cre_datetm'		  => $data['cre_datetm']
            ,'mod_id'			  => $data['mod_id']
            ,'mod_datetm'		  => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * GX 수업 update insert count check [counter]
     * @param array $data
     * @return array
     */
    public function get_gx_schedule_update_chk(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM gx_clas_mgmt_tbl
                WHERE
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND GX_SCHD_MGMT_SNO = :gx_schd_mgmt_sno:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'				=> $data['comp_cd']
            ,'bcoff_cd'				=> $data['bcoff_cd']
            ,'gx_schd_mgmt_sno'		=> $data['gx_schd_mgmt_sno']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * GX 수업을 체크(Y,N) 을 했는지를 검사한다. [counter]
     * @param array $data
     * @return array
     */
    public function get_gx_schedule_chk(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM gx_clas_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND GX_SCHD_MGMT_SNO = :gx_schd_mgmt_sno:
                AND CLAS_CHK_YN = :clas_chk_yn:
				";
                
                $query = $this->db->query($sql, [
                    'comp_cd'				=> $data['comp_cd']
                    ,'bcoff_cd'				=> $data['bcoff_cd']
                    ,'gx_schd_mgmt_sno'		=> $data['gx_schd_mgmt_sno']
                    ,'clas_chk_yn'			=> $data['clas_chk_yn']
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['counter'];
    }
    
    /**
     * GX 해당 일련번호에 대한 정보를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_gx_schedule(array $data)
    {
        $sql = "SELECT * FROM gx_schd_mgmt_tbl
                WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND GX_SCHD_MGMT_SNO = :gx_schd_mgmt_sno:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'gx_schd_mgmt_sno'	=> $data['gx_schd_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    
    /**
     * 오늘 수업한 취소가 아닌 최근 수업체크 내역을 가져온다.
     * @param array $data
     * @return array
     */
    public function list_clas_msg(array $data)
    {
        $sql = "SELECT PT.*, M.MEM_MAIN_IMG, MEM_THUMB_IMG
                FROM pt_clas_diary_tbl PT INNER JOIN mem_info_detl_tbl M ON PT.MEM_SNO = M.MEM_SNO
                WHERE  PT.COMP_CD = :comp_cd:
                AND  PT.BCOFF_CD = :bcoff_cd:
                AND  PT.BUY_EVENT_SNO = :buy_event_sno:";
        
        // last_time이 있으면 해당 시간 이후의 메시지만 가져오기
        if (isset($data['last_time']) && !empty($data['last_time'])) {
            $sql .= " AND PT.CRE_DATETM > :last_time:";
        }
        
        $sql .= " ORDER BY PT.CRE_DATETM ASC";
        
        $queryParams = [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'buy_event_sno'	=> $data['buy_event_sno']
        ];
        
        // last_time 파라미터 추가
        if (isset($data['last_time']) && !empty($data['last_time'])) {
            $queryParams['last_time'] = $data['last_time'];
        }
        
        $query = $this->db->query($sql, $queryParams);
        
        return $query->getResultArray();
    }
    
    
    /**
     * 오늘 수업한 PT 내역을 cancel update 처리 한다.
     * @param array $data
     * @return array
     */
    public function delete_last_pt_clas(array $data)
    {
        $sql = "DELETE FROM pt_clas_mgmt_tbl 
                    WHERE  CLAS_MGMT_SNO          = :clas_mgmt_sno:
				";
        $query = $this->db->query($sql, [
            'clas_mgmt_sno'		 => $data['clas_mgmt_sno']
        ]);
        
        return $query !== false;
	}
    
    /**
     * 오늘 수업한 취소가 아닌 최근 수업체크 내역을 가져온다.
     * @param array $data
     * @return array
     */
    public function last_today_pt_clas(array $data)
    {
        $sql = "SELECT * FROM pt_clas_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND BUY_EVENT_SNO = :buy_event_sno:
                AND STCHR_ID = :stchr_id:
                AND CLAS_CHK_YMD = :clas_chk_ymd:
                AND CANCEL_YN = 'N'
                ORDER BY CRE_DATETM DESC LIMIT 1
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'buy_event_sno'	=> $data['buy_event_sno']
            ,'stchr_id'	        => $data['stchr_id']
            ,'clas_chk_ymd'	    => $data['clas_chk_ymd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
	
    /**
     * 이용한 PT 금액 합계를 리턴한다.
     * @param array $data
     * @return array
     */
    public function use_amt_pt_clas(array $data)
    {
        $sql = "SELECT SUM(MEM_1TM_CLAS_PRGS_AMT) AS use_amt FROM pt_clas_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND BUY_EVENT_SNO = :buy_event_sno:
                AND CANCEL_YN = 'N'
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
     * 구매상품에서 예약일 경우 이용중 상태와 운동 시작일을 업데이트 한다.
     * @param array $data
     * @return array
     */
    public function update_buy_event_stat_sdate(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					EVENT_STAT          = :event_stat:
                    ,EXR_S_DATE         = :exr_s_date:
                    ,EXR_E_DATE         = :exr_e_date:
                    WHERE BUY_EVENT_SNO              = :buy_event_sno:
                    AND COMP_CD                         = :comp_cd:
					AND BCOFF_CD                        = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 	 => $data['buy_event_sno']
            ,'comp_cd'	         => $data['comp_cd']
            ,'bcoff_cd'			 => $data['bcoff_cd']
            ,'event_stat'			=> $data['event_stat']
            ,'exr_s_date'		    => $data['exr_s_date']
            ,'exr_e_date'		    => $data['exr_e_date']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 정규 수업 update
     * @param array $data
     * @return array
     */
    public function update_buy_event_regul_clas(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					MEM_REGUL_CLAS_PRGS_CNT          = :mem_regul_clas_prgs_cnt:
                    ,MEM_REGUL_CLAS_LEFT_CNT         = :mem_regul_clas_left_cnt:
                    WHERE BUY_EVENT_SNO              = :buy_event_sno:
                    AND COMP_CD                         = :comp_cd:
					AND BCOFF_CD                        = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 	 => $data['buy_event_sno']
            ,'comp_cd'	         => $data['comp_cd']
            ,'bcoff_cd'			 => $data['bcoff_cd']
            ,'mem_regul_clas_prgs_cnt'			=> $data['mem_regul_clas_prgs_cnt']
            ,'mem_regul_clas_left_cnt'		    => $data['mem_regul_clas_left_cnt']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 서비스 수업 update
     * @param array $data
     * @return array
     */
    public function update_buy_event_srvc_clas(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
					SRVC_CLAS_PRGS_CNT          = :srvc_clas_prgs_cnt:
                    ,SRVC_CLAS_LEFT_CNT         = :srvc_clas_left_cnt:
                    WHERE BUY_EVENT_SNO              = :buy_event_sno:
                    AND COMP_CD                         = :comp_cd:
					AND BCOFF_CD                        = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'buy_event_sno' 	 => $data['buy_event_sno']
            ,'comp_cd'	         => $data['comp_cd']
            ,'bcoff_cd'			 => $data['bcoff_cd']
            ,'srvc_clas_prgs_cnt'			=> $data['srvc_clas_prgs_cnt']
            ,'srvc_clas_left_cnt'		    => $data['srvc_clas_left_cnt']
        ]);
        
        array_push($data,$query);
        return $data;
    }

    /**
     * 출석 관리 테이블에 PT수업정보 업데이트
     * @param array $data
     * @return array
     */
    public function update_attd_mgmt_mem(array $data)
    {
        $sql = "UPDATE attd_mgmt_tbl SET
                CLAS_MGMT_SNO = NULL
                WHERE  CLAS_MGMT_SNO = :clas_mgmt_sno:
                ";
        $query = $this->db->query($sql, ['clas_mgmt_sno' => $data['clas_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $data;   
    }

    
    /**
     * 출석 관리 테이블에 PT수업정보 업데이트
     * @param array $data
     * @return array
     */
    public function update_attd_mgmt(array $data)
    {
        $sql = "UPDATE attd_mgmt_tbl SET
                CLAS_MGMT_SNO = :clas_mgmt_sno:
                WHERE ATTD_MGMT_SNO = :attd_mgmt_sno:
                ";
        $query = $this->db->query($sql, [
            'attd_mgmt_sno' => $data['attd_mgmt_sno']
            ,'clas_mgmt_sno' => $data['clas_mgmt_sno']
        ]);
        
        array_push($data,$query);
        return $data;   
    }

	/**
	 * 수업관리 insert
	 * @param array $data
	 */
    public function insert_pt_clas_mgmt(array $data)
	{
		$sql = "INSERT pt_clas_mgmt_tbl SET
					BUY_EVENT_SNO           = :buy_event_sno:
                    ,SELL_EVENT_SNO         = :sell_event_sno:
                    ,SEND_EVENT_SNO         = :send_event_sno:
                    ,COMP_CD                = :comp_cd:
					,BCOFF_CD               = :bcoff_cd:
					,1RD_CATE_CD		    = :1rd_cate_cd:
                    ,2RD_CATE_CD            = :2rd_cate_cd:
                    ,SELL_EVENT_NM          = :sell_event_nm:
                    ,MEM_SNO                = :mem_sno:
                    ,MEM_ID                 = :mem_id:
                    ,MEM_NM                 = :mem_nm:
                    ,STCHR_ID		        = :stchr_id:
                    ,STCHR_NM               = :stchr_nm:
                    ,AGEGRP                 = :agegrp:
					,MEM_GNDR               = :mem_gndr:
                    ,CLAS_CNT               = :clas_cnt:
                    ,SRVC_CLAS_PRGS_CNT     = :srvc_clas_left_cnt:
                    ,SRVC_CLAS_LEFT_CNT     = :srvc_clas_prgs_cnt:
                    ,MEM_REGUL_CLAS_PRGS_CNT = :mem_regul_clas_left_cnt:
                    ,MEM_REGUL_CLAS_LEFT_CNT = :mem_regul_clas_prgs_cnt:
                    ,CLAS_CHK_YMD           = :clas_chk_ymd:
                    ,CLAS_CHK_YY            = :clas_chk_yy:
                    ,CLAS_CHK_MM            = :clas_chk_mm:
					,CLAS_CHK_DD			= :clas_chk_dd:
					,CLAS_CHK_DOTW		    = :clas_chk_dotw:
                    ,CLAS_CHK_HH            = :clas_chk_hh:
					,CANCEL_YN              = :cancel_yn:
					,TCHR_1TM_CLAS_PRGS_AMT = :tchr_1tm_clas_prgs_amt:
                    ,PT_CLAS_DV             = :pt_clas_dv:
                    ,CLAS_DV                = :clas_dv:
					,MEM_1TM_CLAS_PRGS_AMT  = :mem_1tm_clas_prgs_amt:
					,AUTO_CHK				= :auto_chk:
                    ,CRE_ID                 = :cre_id:
					,CRE_DATETM             = :cre_datetm:
					,MOD_ID                 = :mod_id:
					,MOD_DATETM             = :mod_datetm:
				";
		$query = $this->db->query($sql, [
				'buy_event_sno' 	  => $data['buy_event_sno']
				,'sell_event_sno'	  => $data['sell_event_sno']
				,'send_event_sno'	  => $data['send_event_sno']
		        ,'comp_cd'            => $data['comp_cd']
				,'bcoff_cd'			  => $data['bcoff_cd']
				,'1rd_cate_cd'		  => $data['1rd_cate_cd']
				,'2rd_cate_cd'		  => $data['2rd_cate_cd']
		        ,'sell_event_nm'	  => $data['sell_event_nm']
    		    ,'mem_sno'		      => $data['mem_sno']
    		    ,'mem_id'		      => $data['mem_id']
    		    ,'mem_nm'		      => $data['mem_nm']
    		    ,'stchr_id'		      => $data['stchr_id']
    		    ,'stchr_nm'		      => $data['stchr_nm']
				,'agegrp'		      => $data['agegrp']
				,'mem_gndr'		      => $data['mem_gndr']
		    ,'clas_cnt'		          => $data['clas_cnt']
		    ,'srvc_clas_left_cnt'	  => $data['srvc_clas_left_cnt']
		    ,'srvc_clas_prgs_cnt'     => $data['srvc_clas_prgs_cnt']
		    ,'mem_regul_clas_left_cnt' => $data['mem_regul_clas_left_cnt']
		    ,'mem_regul_clas_prgs_cnt' => $data['mem_regul_clas_prgs_cnt']
				,'clas_chk_ymd'	       => $data['clas_chk_ymd']
		        ,'clas_chk_yy'	       => $data['clas_chk_yy']
    		    ,'clas_chk_mm'	       => $data['clas_chk_mm']
    		    ,'clas_chk_dd'		   => $data['clas_chk_dd']
    		    ,'clas_chk_dotw'	   => $data['clas_chk_dotw']
    		    ,'clas_chk_hh'		   => $data['clas_chk_hh']
    		    ,'cancel_yn'		   => $data['cancel_yn']
    		    ,'tchr_1tm_clas_prgs_amt' => $data['tchr_1tm_clas_prgs_amt']
    		    ,'clas_dv'		          => $data['clas_dv']
    		    ,'pt_clas_dv'		      => $data['pt_clas_dv']
    		    ,'mem_1tm_clas_prgs_amt'  => $data['mem_1tm_clas_prgs_amt']
				,'auto_chk'			=> $data['auto_chk']
				,'cre_id'			=> $data['cre_id']
				,'cre_datetm'		=> $data['cre_datetm']
				,'mod_id'			=> $data['mod_id']
				,'mod_datetm'		=> $data['mod_datetm']
		]);
		
		return $this->db->insertID();
	}
	
	/**
	 * 수업관리 insert
	 * @param array $data
	 */
	public function insert_pt_clas_diary(array $data)
	{
	    $sql = "INSERT pt_clas_diary_tbl SET
					BUY_EVENT_SNO           = :buy_event_sno:
                    ,SELL_EVENT_SNO         = :sell_event_sno:
                    ,SEND_EVENT_SNO         = :send_event_sno:
                    ,COMP_CD                = :comp_cd:
					,BCOFF_CD               = :bcoff_cd:
					,1RD_CATE_CD		    = :1rd_cate_cd:
                    ,2RD_CATE_CD            = :2rd_cate_cd:
                    ,SELL_EVENT_NM          = :sell_event_nm:
                    ,MEM_SNO                = :mem_sno:
                    ,MEM_ID                 = :mem_id:
                    ,MEM_NM                 = :mem_nm:
                    ,STCHR_SNO		        = :stchr_sno:
                    ,STCHR_ID		        = :stchr_id:
                    ,STCHR_NM               = :stchr_nm:
                    ,CLAS_DIARY_CONTS       = :clas_diary_conts:
					,MEM_DV                 = :mem_dv:
					,DEL_YN				    = :del_yn:
                    ,CRE_ID                 = :cre_id:
					,CRE_DATETM             = :cre_datetm:
					,MOD_ID                 = :mod_id:
					,MOD_DATETM             = :mod_datetm:
				";
	    $query = $this->db->query($sql, [
	        'buy_event_sno' 	  => $data['buy_event_sno']
	        ,'sell_event_sno'	  => $data['sell_event_sno']
	        ,'send_event_sno'	  => $data['send_event_sno']
	        ,'comp_cd'            => $data['comp_cd']
	        ,'bcoff_cd'			  => $data['bcoff_cd']
	        ,'1rd_cate_cd'		  => $data['1rd_cate_cd']
	        ,'2rd_cate_cd'		  => $data['2rd_cate_cd']
	        ,'sell_event_nm'	  => $data['sell_event_nm']
	        ,'mem_sno'		      => $data['mem_sno']
	        ,'mem_id'		      => $data['mem_id']
	        ,'mem_nm'		      => $data['mem_nm']
	        ,'stchr_sno'		  => $data['stchr_sno']
	        ,'stchr_id'		      => $data['stchr_id']
	        ,'stchr_nm'		      => $data['stchr_nm']
	        ,'clas_diary_conts'	  => $data['clas_diary_conts']
	        ,'mem_dv'             => $data['mem_dv']
	        ,'del_yn'			  => $data['del_yn']
	        ,'cre_id'			=> $data['cre_id']
	        ,'cre_datetm'		=> $data['cre_datetm']
	        ,'mod_id'			=> $data['mod_id']
	        ,'mod_datetm'		=> $data['mod_datetm']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
    
    
    
}