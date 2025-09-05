<?php
namespace App\Models;

use CodeIgniter\Model;

class AnnuModel extends Model
{
    /**
     * 연차신청을 승인한다 - 신청관리테이블. update
     * @param array $data
     */
    public function update_tchr_annu_use_mgmt_appct(array $data)
    {
        $sql = "UPDATE tchr_annu_grant_mgmt_tbl SET
					ANNU_LEFT_DAY 		     = ANNU_LEFT_DAY - :use_day:
					,ANNU_USE_DAY	     = ANNU_USE_DAY + :use_day:
					,MOD_ID			         = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
                WHERE ANNU_GRANT_MGMT_SNO = :annu_grant_mgmt_sno:
                AND COMP_CD                  = :comp_cd:
                AND BCOFF_CD                 = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'mod_id'			        => $data['mod_id']
            ,'mod_datetm'		        => $data['mod_datetm']
            ,'annu_grant_mgmt_sno'	    => $data['annu_grant_mgmt_sno']
            ,'comp_cd'		            => $data['comp_cd']
            ,'bcoff_cd'		            => $data['bcoff_cd']
            ,'use_day'		            => $data['use_day']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 연차신청을 승인한다. update
     * @param array $data
     */
    public function update_tchr_annu_use_mgmt_hist_appct(array $data)
    {
        $sql = "UPDATE tchr_annu_use_mgmt_hist_tbl SET
					ANNU_APPV_STAT 		     = :annu_appv_stat:
					,ANNU_APPCT_DATETM	     = :annu_appct_datetm:
                    ,ANNU_APPV_ID	         = :annu_appv_id:
					,MOD_ID			         = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
                WHERE ANNU_USE_MGMT_HIST_SNO = :annu_use_mgmt_hist_sno:
                AND COMP_CD                  = :comp_cd:
                AND BCOFF_CD                 = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'annu_appv_stat' 			=> $data['annu_appv_stat']
            ,'annu_appct_datetm'		=> $data['annu_appct_datetm']
            ,'annu_appv_id'		        => $data['annu_appv_id']
            ,'mod_id'			        => $data['mod_id']
            ,'mod_datetm'		        => $data['mod_datetm']
            ,'annu_use_mgmt_hist_sno'	=> $data['annu_use_mgmt_hist_sno']
            ,'comp_cd'		            => $data['comp_cd']
            ,'bcoff_cd'		            => $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 연차신청을 반려한다. update
     * @param array $data
     */
    public function update_tchr_annu_use_mgmt_hist_refus(array $data)
    {
        $sql = "UPDATE tchr_annu_use_mgmt_hist_tbl SET
					ANNU_APPV_STAT 		     = :annu_appv_stat:
					,ANNU_REFUS_DATETM	     = :annu_refus_datetm:
					,MOD_ID			         = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
                WHERE ANNU_USE_MGMT_HIST_SNO = :annu_use_mgmt_hist_sno:
                AND COMP_CD                  = :comp_cd:
                AND BCOFF_CD                 = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'annu_appv_stat' 			=> $data['annu_appv_stat']
            ,'annu_refus_datetm'		=> $data['annu_refus_datetm']
            ,'mod_id'			        => $data['mod_id']
            ,'mod_datetm'		        => $data['mod_datetm']
            ,'annu_use_mgmt_hist_sno'	=> $data['annu_use_mgmt_hist_sno']
            ,'comp_cd'		            => $data['comp_cd']
            ,'bcoff_cd'		            => $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 연차신청을 취소한다. update
     * @param array $data
     */
    public function update_tchr_annu_use_mgmt_hist_cancel(array $data)
    {
        $sql = "UPDATE tchr_annu_use_mgmt_hist_tbl SET
					ANNU_APPV_STAT 		     = :annu_appv_stat:
					,ANNU_CANCEL_DATETM	     = :annu_cancel_datetm:
					,MOD_ID			         = :mod_id:
					,MOD_DATETM		         = :mod_datetm:
                WHERE ANNU_USE_MGMT_HIST_SNO = :annu_use_mgmt_hist_sno:
                AND COMP_CD                  = :comp_cd:
                AND BCOFF_CD                 = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'annu_appv_stat' 			=> $data['annu_appv_stat']
            ,'annu_cancel_datetm'		=> $data['annu_cancel_datetm']
            ,'mod_id'			        => $data['mod_id']
            ,'mod_datetm'		        => $data['mod_datetm']
            ,'annu_use_mgmt_hist_sno'	=> $data['annu_use_mgmt_hist_sno']
            ,'comp_cd'		            => $data['comp_cd']
            ,'bcoff_cd'		            => $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * =================================================================================
     * tchr_annu_manage [강사 연차관리] - START
     * =================================================================================
     */
    
    private function list_tchr_annu_grant_mgmt_hist_where(array $data)
    {
        $add_where = "";
        
        // 강사 신청자명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND a.MEM_NM LIKE '%{$data['snm']}%' ";
            }
        }
        
        // 연차 상태
        if (isset($data['sappstat']))
        {
            if ($data['sappstat'] != '')
            {
                $add_where .= " AND a.ANNU_APPV_STAT = '{$data['sappstat']}' ";
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
                        case "as":
                            $add_where .= " AND a.ANNU_APPCT_S_DATE BETWEEN '{$data['sdate']}' AND '{$data['edate']}' ";
                            break;
                        case "ae":
                            $add_where .= " AND a.ANNU_APPCT_E_DATE BETWEEN '{$data['sdate']}' AND '{$data['edate']}' ";
                            break;
                        case "00":
                            $add_where .= " AND a.ANNU_APPV_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "01":
                            $add_where .= " AND a.ANNU_APPCT_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "90":
                            $add_where .= " AND a.ANNU_REFUS_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "99":
                            $add_where .= " AND a.ANNU_CANCEL_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 연차관리 신청 목록을 가져온다.
     * @param array $data
     */
    public function list_tchr_annu_grant_mgmt_hist_count(array $data)
    {
        $add_where = $this->list_tchr_annu_grant_mgmt_hist_where($data);
        $sql = "SELECT COUNT(*) as counter FROM tchr_annu_use_mgmt_hist_tbl a
				WHERE a.COMP_CD = :comp_cd:
				AND a.BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 연차관리 신청 목록을 가져온다.
     * @param array $data
     */
    public function list_tchr_annu_grant_mgmt_hist(array $data)
    {
        $add_where = $this->list_tchr_annu_grant_mgmt_hist_where($data);
        $sql = "SELECT a.*, b.MEM_THUMB_IMG, b.MEM_MAIN_IMG, b.MEM_GENDR 
                FROM tchr_annu_use_mgmt_hist_tbl a
                LEFT JOIN mem_info_detl_tbl b ON a.MEM_SNO = b.MEM_SNO AND a.COMP_CD = b.COMP_CD AND a.BCOFF_CD = b.BCOFF_CD
				WHERE a.COMP_CD = :comp_cd:
				AND a.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY a.CRE_DATETM DESC
                limit {$data['limit_s']} , {$data['limit_e']}
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * =================================================================================
     * tchr_annu_manage [강사 연차관리] - END
     * =================================================================================
     */
    
    
    /**
     * 회원 일련번로를 이용하여 연차관리 사용 목록을 가져온다.
     * @param array $data
     */
    public function list_tchr_annu_grant_mgmt_hist_memsno(array $data)
    {
        $sql = "SELECT * FROM tchr_annu_use_mgmt_hist_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_SNO = :mem_sno:
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
     * 회원 일련번로를 이용하여 연차관리 셋팅 목록을 가져온다.
     * @param array $data
     */
    public function list_tchr_annu_grant_mgmt_memsno(array $data)
    {
        $sql = "SELECT * FROM tchr_annu_grant_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_SNO = :mem_sno:
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
     * 회원 일련번로를 이용하여 연차관리 셋팅 목록을 가져온다.
     * @param array $data
     */
    public function list_tchr_annu_grant_mgmt_pos_use(array $data)
    {
        $sql = "SELECT * FROM tchr_annu_grant_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_SNO = :mem_sno:
                AND '".date('Y-m-d')."' BETWEEN ANNU_GRANT_S_DATE AND ANNU_GRANT_E_DATE
				limit 1";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'mem_sno'			=> $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
        
    }
    
    /**
     * 상품 보내기 insert
     * @param array $data
     */
    public function insert_tchr_annu_grant_mgmt(array $data)
    {
    	$sql = "INSERT tchr_annu_grant_mgmt_tbl SET
					COMP_CD 		     = :comp_cd:
					,BCOFF_CD			 = :bcoff_cd:
                    ,MEM_SNO		     = :mem_sno:
					,MEM_ID		         = :mem_id:
					,MEM_NM			     = :mem_nm:
					,ANNU_GRANT_S_DATE	 = :annu_grant_s_date:
					,ANNU_GRANT_E_DATE	 = :annu_grant_e_date:
					,ANNU_GRANT_DAY		 = :annu_grant_day:
					,ANNU_LEFT_DAY	     = :annu_left_day:
					,ANNU_USE_DAY	     = :annu_use_day:
					,REVAC_LEFT_DAY		 = :revac_left_day:
                    ,REVAC_USE_DAY		 = :revac_use_day:
					,CRE_ID			     = :cre_id:
					,CRE_DATETM		     = :cre_datetm:
					,MOD_ID			     = :mod_id:
					,MOD_DATETM		     = :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'comp_cd'			    => $data['comp_cd']
    			,'bcoff_cd'			    => $data['bcoff_cd']
    			,'mem_sno'			    => $data['mem_sno']
    			,'mem_id'			    => $data['mem_id']
    			,'mem_nm'			    => $data['mem_nm']
    			,'annu_grant_s_date'	=> $data['annu_grant_s_date']
    			,'annu_grant_e_date'	=> $data['annu_grant_e_date']
    			,'annu_grant_day'		=> $data['annu_grant_day']
    			,'annu_left_day'		=> $data['annu_left_day']
    			,'annu_use_day'	        => $data['annu_use_day']
    	        ,'revac_left_day'	    => $data['revac_left_day']
    	        ,'revac_use_day'	    => $data['revac_use_day']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 상품 보내기 insert
     * @param array $data
     */
    public function insert_tchr_annu_use_mgmt_hist(array $data)
    {
        $sql = "INSERT tchr_annu_use_mgmt_hist_tbl SET
                    ANNU_GRANT_MGMT_SNO  = :annu_grant_mgmt_sno:
					,COMP_CD 		     = :comp_cd:
					,BCOFF_CD			 = :bcoff_cd:
                    ,MEM_SNO		     = :mem_sno:
					,MEM_ID		         = :mem_id:
					,MEM_NM			     = :mem_nm:
                    ,ANNU_APPV_ID		 = :annu_appv_id:
                    ,ANNU_APPCT_KND		 = :annu_appct_knd:
					,ANNU_APPCT_S_DATE	 = :annu_appct_s_date:
					,ANNU_APPCT_E_DATE	 = :annu_appct_e_date:
					,ANNU_USE_DAY		 = :annu_use_day:
					,ANNU_APPV_STAT	     = :annu_appv_stat:
					,ANNU_APPV_DATETM	 = :annu_appv_datetm:
                    ,ANNU_REFUS_DATETM	 = :annu_refus_datetm:
					,ANNU_APPCT_DATETM	 = :annu_appct_datetm:
                    ,ANNU_CANCEL_DATETM	 = :annu_cancel_datetm:
					,CRE_ID			     = :cre_id:
					,CRE_DATETM		     = :cre_datetm:
					,MOD_ID			     = :mod_id:
					,MOD_DATETM		     = :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'annu_grant_mgmt_sno'	=> $data['annu_grant_mgmt_sno']
            ,'comp_cd'			    => $data['comp_cd']
            ,'bcoff_cd'			    => $data['bcoff_cd']
            ,'mem_sno'			    => $data['mem_sno']
            ,'mem_id'			    => $data['mem_id']
            ,'mem_nm'			    => $data['mem_nm']
            ,'annu_appv_id'			=> $data['annu_appv_id']
            ,'annu_appct_knd'		=> $data['annu_appct_knd']
            ,'annu_appct_s_date'	=> $data['annu_appct_s_date']
            ,'annu_appct_e_date'	=> $data['annu_appct_e_date']
            ,'annu_use_day'		    => $data['annu_use_day']
            ,'annu_appv_stat'		=> $data['annu_appv_stat']
            ,'annu_appv_datetm'	    => $data['annu_appv_datetm']
            ,'annu_refus_datetm'	=> $data['annu_refus_datetm']
            ,'annu_appct_datetm'	=> $data['annu_appct_datetm']
            ,'annu_cancel_datetm'	=> $data['annu_cancel_datetm']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    		
    
    
    
}