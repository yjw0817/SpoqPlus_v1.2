<?php
namespace App\Models;

use CodeIgniter\Model;

class HistModel extends Model
{
    
    private function add_where_hist(array $data)
    {
        $add_where = "";
        
        // 구분
        if (isset($data['chtp']))
        {
            if ($data['chtp'] != '')
            {
                $add_where .= " AND H.CH_TYPE = '{$data['chtp']}' ";
            }
        }
        
        // 회원명
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND (H.MEM_NM LIKE '%{$data['snm']}%' OR H.MEM_ID LIKE '%{$data['snm']}%') ";
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
                        case "je": // 강제종료 내역
                            $add_where .= " AND H.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "tch": // 강사변경 내역
                            $add_where .= " AND H.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "dch": // 기간변경내역
                            $add_where .= " AND H.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "hdd": // 휴회추가 내역
                            $add_where .= " AND H.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "sdd": // 수업추가 내역
                            $add_where .= " AND H.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 강제종료 hist list count
     * @param array $data
     * @return array
     */
    public function list_hist_event_just_end_count(array $data)
    {
        $add_where = $this->add_where_hist($data);
        $sql = "SELECT COUNT(*) as counter FROM hist_event_just_end H
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
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
     * 강제종료 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_just_end(array $data)
    {
        $add_where = $this->add_where_hist($data);
        $sql = "SELECT H.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM hist_event_just_end H
                LEFT JOIN mem_info_detl_tbl MID ON H.MEM_SNO = MID.MEM_SNO
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY H.CRE_DATETM DESC
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
     * 강사변경 hist list count
     * @param array $data
     * @return array
     */
    public function list_hist_event_tchr_ch_count(array $data)
    {
        $add_where = $this->add_where_hist($data);
        $sql = "SELECT COUNT(*) as counter FROM hist_event_tchr_ch H
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
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
     * 강사변경 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_tchr_ch(array $data)
    {
        $add_where = $this->add_where_hist($data); //tch
        $sql = "SELECT H.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM hist_event_tchr_ch H
                LEFT JOIN mem_info_detl_tbl MID ON H.MEM_SNO = MID.MEM_SNO
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY H.CRE_DATETM DESC
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
     * 기간변경 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_date_ch_count(array $data)
    {
        $add_where = $this->add_where_hist($data); // dch
        $sql = "SELECT COUNT(*) as counter FROM hist_event_date_ch H
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
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
     * 기간변경 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_date_ch(array $data)
    {
        $add_where = $this->add_where_hist($data); // dch
        $sql = "SELECT H.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM hist_event_date_ch H
                LEFT JOIN mem_info_detl_tbl MID ON H.MEM_SNO = MID.MEM_SNO
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY H.CRE_DATETM DESC
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
     * 휴회추가 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_domcy_ch_count(array $data)
    {
        $add_where = $this->add_where_hist($data); // hdd
        $sql = "SELECT COUNT(*) as counter FROM hist_event_domcy_ch H
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
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
     * 휴회추가 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_domcy_ch(array $data)
    {
        $add_where = $this->add_where_hist($data); // hdd
        $sql = "SELECT H.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM hist_event_domcy_ch H
                LEFT JOIN mem_info_detl_tbl MID ON H.MEM_SNO = MID.MEM_SNO
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY H.CRE_DATETM DESC
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
     * 수엊추가 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_srvc_add_count(array $data)
    {
        $add_where = $this->add_where_hist($data); // sdd
        $sql = "SELECT COUNT(*) as counter FROM hist_event_srvc_add H
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
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
     * 수엊추가 hist list
     * @param array $data
     * @return array
     */
    public function list_hist_event_srvc_add(array $data)
    {
        $add_where = $this->add_where_hist($data); // sdd
        $sql = "SELECT H.*, MID.MEM_THUMB_IMG, MID.MEM_MAIN_IMG, MID.MEM_GENDR
                FROM hist_event_srvc_add H
                LEFT JOIN mem_info_detl_tbl MID ON H.MEM_SNO = MID.MEM_SNO
                WHERE H.COMP_CD = :comp_cd:
                AND H.BCOFF_CD = :bcoff_cd:
                {$add_where}
                ORDER BY H.CRE_DATETM DESC
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
     * 강제종료 hist insert
     * @param array $data
     */
    public function insert_hist_event_just_end(array $data)
    {
        $sql = "INSERT hist_event_just_end SET
					COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
                    ,BUY_EVENT_SNO = :buy_event_sno:
                    ,SELL_EVENT_NM = :sell_event_nm:
                    ,MEM_SNO		= :mem_sno:
                    ,MEM_ID		    = :mem_id:
                    ,MEM_NM		    = :mem_nm:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'buy_event_sno'    => $data['buy_event_sno']
            ,'sell_event_nm'    => $data['sell_event_nm']
            ,'mem_sno'			=> $data['mem_sno']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_nm'			=> $data['mem_nm']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 강사변경 hist insert
     * @param array $data
     */
    public function insert_hist_event_tchr_ch(array $data)
    {
        $sql = "INSERT hist_event_tchr_ch SET
					COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
                    ,BUY_EVENT_SNO = :buy_event_sno:
                    ,SELL_EVENT_NM = :sell_event_nm:
                    ,CH_TYPE = :ch_type:
                    ,MEM_SNO		= :mem_sno:
                    ,MEM_ID		    = :mem_id:
                    ,MEM_NM		    = :mem_nm:
                    ,BF_TCHR_ID = :bf_tchr_id:
                    ,BF_TCHR_NM = :bf_tchr_nm:
                    ,AF_TCHR_ID = :af_tchr_id:
                    ,AF_TCHR_NM = :af_tchr_nm:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'buy_event_sno'    => $data['buy_event_sno']
            ,'sell_event_nm'    => $data['sell_event_nm']
            ,'ch_type'          => $data['ch_type']
            ,'mem_sno'			=> $data['mem_sno']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_nm'			=> $data['mem_nm']
            ,'bf_tchr_id'       => $data['bf_tchr_id']
            ,'bf_tchr_nm'       => $data['bf_tchr_nm']
            ,'af_tchr_id'       => $data['af_tchr_id']
            ,'af_tchr_nm'       => $data['af_tchr_nm']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 시작,종료일 변경 hist insert
     * @param array $data
     */
    public function insert_hist_event_date_ch(array $data)
    {
        $sql = "INSERT hist_event_date_ch SET
					COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
                    ,BUY_EVENT_SNO = :buy_event_sno:
                    ,SELL_EVENT_NM = :sell_event_nm:
                    ,CH_TYPE = :ch_type:
                    ,MEM_SNO		= :mem_sno:
                    ,MEM_ID		    = :mem_id:
                    ,MEM_NM		    = :mem_nm:
                    ,BF_EXR_S_DATE = :bf_exr_s_date:
                    ,BF_EXR_E_DATE = :bf_exr_e_date:
                    ,AF_EXR_S_DATE = :af_exr_s_date:
                    ,AF_EXR_E_DATE = :af_exr_e_date:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'buy_event_sno'    => $data['buy_event_sno']
            ,'sell_event_nm'    => $data['sell_event_nm']
            ,'ch_type'          => $data['ch_type']
            ,'mem_sno'			=> $data['mem_sno']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_nm'			=> $data['mem_nm']
            ,'bf_exr_s_date'    => $data['bf_exr_s_date']
            ,'bf_exr_e_date'    => $data['bf_exr_e_date']
            ,'af_exr_s_date'    => $data['af_exr_s_date']
            ,'af_exr_e_date'    => $data['af_exr_e_date']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 휴회일,횟수 변경 hist insert
     * @param array $data
     */
    public function insert_hist_event_domcy_ch(array $data)
    {
        $sql = "INSERT hist_event_domcy_ch SET
					COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
                    ,BUY_EVENT_SNO = :buy_event_sno:
                    ,SELL_EVENT_NM = :sell_event_nm:
                    ,CH_TYPE = :ch_type:
                    ,MEM_SNO		= :mem_sno:
                    ,MEM_ID		    = :mem_id:
                    ,MEM_NM		    = :mem_nm:
                    ,BF_ADD_DOMCY_DAY = :bf_add_domcy_day:
                    ,BF_ADD_DOMCY_CNT = :bf_add_domcy_cnt:
                    ,AF_ADD_DOMCY_DAY = :af_add_domcy_day:
                    ,AF_ADD_DOMCY_CNT = :af_add_domcy_cnt:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'buy_event_sno'    => $data['buy_event_sno']
            ,'sell_event_nm'    => $data['sell_event_nm']
            ,'ch_type'          => $data['ch_type']
            ,'mem_sno'			=> $data['mem_sno']
            ,'mem_id'			=> $data['mem_id']
            ,'mem_nm'			=> $data['mem_nm']
            ,'bf_add_domcy_day'    => $data['bf_add_domcy_day']
            ,'bf_add_domcy_cnt'    => $data['bf_add_domcy_cnt']
            ,'af_add_domcy_day'    => $data['af_add_domcy_day']
            ,'af_add_domcy_cnt'    => $data['af_add_domcy_cnt']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 수업 추가 hist insert
     * @param array $data
     */
    public function insert_hist_event_srvc_add(array $data)
    {
        $sql = "INSERT hist_event_srvc_add SET
					COMP_CD		       = :comp_cd:
					,BCOFF_CD		   = :bcoff_cd:
                    ,BUY_EVENT_SNO     = :buy_event_sno:
                    ,SELL_EVENT_NM     = :sell_event_nm:
                    ,CH_TYPE           = :ch_type:
                    ,MEM_SNO		   = :mem_sno:
                    ,MEM_ID		       = :mem_id:
                    ,MEM_NM		       = :mem_nm:
                    ,ADD_SRVC_CLAS_CNT = :add_srvc_clas_cnt:
                    ,TCHR_SNO		   = :tchr_sno:
                    ,TCHR_ID		   = :tchr_id:
                    ,TCHR_NM		   = :tchr_nm:
					,CRE_ID			   = :cre_id:
					,CRE_DATETM		   = :cre_datetm:
					,MOD_ID			   = :mod_id:
					,MOD_DATETM		   = :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'comp_cd'			    => $data['comp_cd']
            ,'bcoff_cd'			    => $data['bcoff_cd']
            ,'buy_event_sno'        => $data['buy_event_sno']
            ,'sell_event_nm'        => $data['sell_event_nm']
            ,'ch_type'              => $data['ch_type']
            ,'mem_sno'			    => $data['mem_sno']
            ,'mem_id'			    => $data['mem_id']
            ,'mem_nm'			    => $data['mem_nm']
            ,'add_srvc_clas_cnt'    => $data['add_srvc_clas_cnt']
            ,'tchr_sno'			    => $data['tchr_sno']
            ,'tchr_id'			    => $data['tchr_id']
            ,'tchr_nm'			    => $data['tchr_nm']
            ,'cre_id'			    => $data['cre_id']
            ,'cre_datetm'		    => $data['cre_datetm']
            ,'mod_id'			    => $data['mod_id']
            ,'mod_datetm'		    => $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    		
    
    
}