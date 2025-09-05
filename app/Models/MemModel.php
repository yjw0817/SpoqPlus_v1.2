<?php
namespace App\Models;

use CodeIgniter\Model;

class MemModel extends Model
{
    private function admin_list_mem_where(array $data)
    {
        $add_where = "";
        
        // 회원상태
        if (isset($data['smst']))
        {
            if ($data['smst'] != '')
            {
                $add_where .= " AND MEM_STAT = '{$data['smst']}' ";
            }
        }
        
        // 회원이름
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND ( A.MEM_NM LIKE '%{$data['snm']}%' 
                                     OR A.MEM_TELNO LIKE '%{$data['snm']}%'
                                     OR A.MEM_NM LIKE '%{$data['snm']}%' 
                                     OR A.BTHDAY LIKE '%{$data['snm']}%' 
                                     OR A.MEM_ADDR LIKE '%{$data['snm']}%' 

                                     OR B.T_MEM_NM LIKE '%{$data['snm']}%' 
                                     OR B.S_MEM_NM LIKE '%{$data['snm']}%' 
                                      )";
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
                        case "reg":
                            $add_where .= " AND A.CRE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "end":
                            $add_where .= " AND A.SECE_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 회원 [ List Count ]
     * @param array $data
     * @return array
     */
    public function admin_all_list_mem_count(array $data)
    {
        $add_where = $this->admin_list_mem_where($data);
        $sql = "SELECT COUNT(*) as counter FROM mem_main_info_tbl A 
                                      LEFT JOIN mem_introd_tbl B ON A.MEM_ID = B.T_MEM_ID OR  A.MEM_ID = B.S_MEM_ID
                                    --   LEFT JOIN mem_introd_tbl C ON A.MEM_ID = C.MEM_ID
                WHERE A.MEM_DV = 'M'
                {$add_where}
				";
                
                $query = $this->db->query($sql, [
                    
                ]);
                
                $count = $query->getResultArray();
                return $count[0]['counter'];
    }
    
    /**
     * 회원 [ List Board ]
     * @param array $data
     * @return array
     */
    public function admin_all_list_mem(array $data)
    {
        $add_where = $this->admin_list_mem_where($data);
        $sql = "SELECT * FROM mem_main_info_tbl A 
                    LEFT JOIN mem_introd_tbl B ON A.MEM_ID = B.T_MEM_ID OR  A.MEM_ID = B.S_MEM_ID
                    -- LEFT JOIN mem_introd_tbl C ON A.MEM_ID = C.MEM_ID
                WHERE A.MEM_DV = 'M'
                {$add_where}
                ORDER BY A.CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
                
                $query = $this->db->query($sql, [
                    
                ]);
                array_push($data,$query);
                return $query->getResultArray();
    }
    
    /**
     * 회원 탈퇴 처리를 업데이트 한다.
     * @param array $data
     */
    public function update_member_sece_info_detal(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
                    MEM_STAT = '99'
                    ,MEM_NM = CONCAT('탈퇴-',MEM_NM)
					,END_DATETM 		= :end_datetm:
                    ,MOD_ID         = :mod_id:
                    ,MOD_DATETM     = :mod_datetm:
                    ,USE_YN         = :use_yn:
                WHERE MEM_SNO   = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'mem_sno'		=> $data['mem_sno']
            ,'end_datetm'	=> $data['end_datetm']
            ,'mod_id'		=> $data['mod_id']
            ,'mod_datetm'	=> $data['mod_datetm']
            ,'use_yn'		=> $data['use_yn']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 회원 탈퇴 처리를 업데이트 한다.
     * @param array $data
     */
    public function update_member_sece_info(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
                    MEM_NM = CONCAT('탈퇴-',MEM_NM)
    				,MOD_ID 		    = :mod_id:
                    ,MOD_DATETM     = :mod_datetm:
                    ,CONN_POSS_YN   = :conn_poss_yn:
                    ,USE_YN         = :use_yn:
                    ,SECE_DATETM    = :sece_datetm:
                WHERE MEM_SNO   = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'mem_sno' 		 => $data['mem_sno']
            ,'mod_id'		 => $data['mod_id']
            ,'mod_datetm'	 => $data['mod_datetm']
            ,'conn_poss_yn'	 => $data['conn_poss_yn']
            ,'use_yn'		 => $data['use_yn']
            ,'sece_datetm'	 => $data['sece_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 강사 퇴사 처리를 업데이트 하낟.
     * @param array $data
     */
    public function update_tchr_sece_info_detal(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
                    MEM_STAT = '99'
					,END_DATETM 		= :end_datetm:
                    ,MOD_ID         = :mod_id:
                    ,MOD_DATETM     = :mod_datetm:
                    ,USE_YN         = :use_yn:
                WHERE COMP_CD   = :comp_cd:
                AND BCOFF_CD    = :bcoff_cd:
                AND MEM_SNO     = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 		=> $data['comp_cd']
            ,'bcoff_cd'		=> $data['bcoff_cd']
            ,'mem_sno'		=> $data['mem_sno']
            ,'end_datetm'	=> $data['end_datetm']
            ,'mod_id'		=> $data['mod_id']
            ,'mod_datetm'	=> $data['mod_datetm']
            ,'use_yn'		=> $data['use_yn']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 강사 퇴사 처리를 업데이트 한다.
     * @param array $data
     */
    public function update_tchr_sece_info(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
    				MOD_ID 		    = :mod_id:
                    ,MOD_DATETM     = :mod_datetm:
                    ,CONN_POSS_YN   = :conn_poss_yn:
                    ,USE_YN         = :use_yn:
                    ,SECE_DATETM    = :sece_datetm:
                WHERE MEM_SNO   = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'mem_sno' 		 => $data['mem_sno']
            ,'mod_id'		 => $data['mod_id']
            ,'mod_datetm'	 => $data['mod_datetm']
            ,'conn_poss_yn'	 => $data['conn_poss_yn']
            ,'use_yn'		 => $data['use_yn']
            ,'sece_datetm'	 => $data['sece_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    
    /**
     * 추천한 회원의 카운트를 가져온다.
     * 받은 사람은 타 센터를 이용중일 수도 있다.
     * @param array $data
     * @return array
     */
    public function list_introd_count(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter
                FROM (
                    SELECT DISTINCT T.C_MEM_SNO
                    FROM mem_introd_tbl AS T
                    LEFT JOIN mem_info_detl_tbl AS M ON M.MEM_SNO = T.C_MEM_SNO
                    LEFT JOIN mem_info_detl_tbl AS N ON N.MEM_SNO = T.T_MEM_SNO
                    WHERE M.COMP_CD = :comp_cd:
                    AND M.BCOFF_CD = :bcoff_cd:";

        // 검색어(snm)가 존재할 경우 조건 추가
        if (!empty($data['snm'])) {
            $sql .= " AND (
                N.MEM_NM LIKE :snm:
                OR N.MEM_TELNO LIKE :snm:
                OR N.MEM_ID LIKE :snm:
                OR M.MEM_NM LIKE :snm:
                OR M.MEM_TELNO LIKE :snm:
                OR M.MEM_ID LIKE :snm:
            )";
            $snm = '%' . trim($data['snm']) . '%';
        } else {
            $snm = null;
        }

        $sql .= " ) AS sub";

        $query = $this->db->query($sql, [
            'comp_cd'  => $data['comp_cd'],
            'bcoff_cd' => $data['bcoff_cd'],
            'snm'      => $snm
        ]);

        $count = $query->getResultArray();
        return $count[0]['counter'];
    }

    
    
    /**
     * 추천한 회원의 리스트를 가져온다.
     * 받은 사람은 타 센터를 이용중일 수도 있다.
     * @param array $data
     * @return array
     */
    public function list_introd(array $data)
    {
        $sql = "SELECT DISTINCT 
                    T.*,
                    M.MEM_GENDR AS S_MEM_GENDR, M.MEM_MAIN_IMG AS S_MEM_MAIN_IMG, M.MEM_THUMB_IMG AS S_MEM_THUMB_IMG,
                    N.MEM_MAIN_IMG AS T_MEM_MAIN_IMG, N.MEM_THUMB_IMG AS T_MEM_THUMB_IMG, N.MEM_GENDR AS T_MEM_GENDR
                FROM mem_introd_tbl AS T
                LEFT JOIN mem_info_detl_tbl AS M ON M.MEM_SNO = T.C_MEM_SNO
                LEFT JOIN mem_info_detl_tbl AS N ON N.MEM_SNO = T.T_MEM_SNO
                WHERE M.COMP_CD = :comp_cd:
                AND M.BCOFF_CD = :bcoff_cd:";

        if (!empty($data['snm'])) {
            $sql .= " AND (
                N.MEM_NM LIKE :snm:
                OR N.MEM_TELNO LIKE :snm:
                OR N.MEM_ID LIKE :snm:
                OR M.MEM_NM LIKE :snm:
                OR M.MEM_TELNO LIKE :snm:
                OR M.MEM_ID LIKE :snm:
            )";
            $snm = '%' . trim($data['snm']) . '%';
        } else {
            $snm = null;
        }

        $limit_s = intval($data['limit_s']);
        $limit_e = intval($data['limit_e']);

        $sql .= " ORDER BY T.CRE_DATETM DESC
                LIMIT {$limit_s}, {$limit_e}";

        $query = $this->db->query($sql, [
            'comp_cd'  => $data['comp_cd'],
            'bcoff_cd' => $data['bcoff_cd'],
            'snm'      => $snm
        ]);

        return $query->getResultArray();
    }
    
    
    /**
     * 회원 전용
     * 내 일련번호고 추천를 했는지에 대한 추천 정보를 불러 온다.
     * @param array $data
     * @return array
     */
    public function get_my_introd_info(array $data)
    {
        $sql = "SELECT * FROM mem_introd_tbl
                WHERE C_MEM_SNO = :c_mem_sno:
            ";
        $query = $this->db->query($sql, [
            'c_mem_sno' 			   => $data['c_mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * INSERT 추천회원 정보
     * @param array $data
     */
    public function insert_mem_introd(array $data)
    {
        $sql = "INSERT mem_introd_tbl SET
					T_MEM_SNO 		= :t_mem_sno:
					,T_MEM_ID		= :t_mem_id:
                    ,T_MEM_NM		= :t_mem_nm:
					,S_MEM_SNO		= :s_mem_sno:
					,S_MEM_ID		= :s_mem_id:
					,S_MEM_NM		= :s_mem_nm:
					,C_MEM_SNO		= :c_mem_sno:
					,C_MEM_ID		= :c_mem_id:
					,C_MEM_NM	    = :c_mem_nm:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            't_mem_sno' 		=> $data['t_mem_sno']
            ,'t_mem_id'			=> $data['t_mem_id']
            ,'t_mem_nm'			=> $data['t_mem_nm']
            ,'s_mem_sno'		=> $data['s_mem_sno']
            ,'s_mem_id'		    => $data['s_mem_id']
            ,'s_mem_nm'		    => $data['s_mem_nm']
            ,'c_mem_sno'		=> $data['c_mem_sno']
            ,'c_mem_id'			=> $data['c_mem_id']
            ,'c_mem_nm'	        => $data['c_mem_nm']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 비밀번호 찾기를 이용한 비밀번호 업데이트
     * @param array $data
     */
    public function mobile_update_repass(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
					MEM_PWD 		= :mem_pwd:
                WHERE MEM_ID       = :mem_id:
                AND MEM_NM = :mem_nm:
                AND MEM_TELNO_ENC2 = :mem_phone_enc2:
                AND BTHDAY = :mem_birth:
				";
        $query = $this->db->query($sql, [
            'mem_pwd' 			=> $data['mem_pwd']
            ,'mem_id'		=> $data['mem_id']
            ,'mem_nm'		=> $data['mem_nm']
            ,'mem_phone_enc2'			=> $data['mem_phone_enc2']
            ,'mem_birth'		=> $data['mem_birth']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    public function mobile_join_phone_cbhk(array $data)
    {
        $sql = "SELECT MEM_ID, MEM_NM, MEM_TELNO, BTHDAY, USE_YN 
                FROM mem_main_info_tbl
                WHERE MEM_NM = :mem_nm:
                AND MEM_TELNO_ENC2 = :mem_phone_enc2:
                AND BTHDAY = :mem_birth:
                AND USE_YN = 'Y'
                ";
        $query = $this->db->query($sql, [
            'mem_nm' 			   => $data['mem_nm']
            ,'mem_phone_enc2' 	   => $data['mem_phone_enc2']
            ,'mem_birth' 		   => $data['mem_birth']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 약관 동의 이력 상세내용
     * @param array $data
     * @return array
     */
    public function mobile_get_terms_detail(array $data)
    {
        $sql = "SELECT T.TERMS_TITLE, T.TERMS_CONTS, M.* FROM mem_terms_tbl AS M 
                LEFT OUTER JOIN terms_mgmt_tbl AS T 
                ON M.TERMS_KND_CD = T.TERMS_KND_CD AND M.TERMS_ROUND = T.TERMS_ROUND
                WHERE M.MEM_SNO = :mem_sno:
                AND M.TERMS_KND_CD = :terms_knd_cd:
                AND M.TERMS_ROUND = :terms_round:
                ";
        $query = $this->db->query($sql, [
            'mem_sno' 			   => $data['mem_sno']
            ,'terms_knd_cd' 	   => $data['terms_knd_cd']
            ,'terms_round' 		   => $data['terms_round']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 약관 동의 이력 가져오기
     * @param array $data
     * @return array
     */
    public function mobile_get_terms_hist(array $data)
    {
        $sql = "SELECT T.TERMS_TITLE, M.* FROM mem_terms_tbl AS M 
                LEFT OUTER JOIN terms_mgmt_tbl AS T 
                ON M.TERMS_KND_CD = T.TERMS_KND_CD AND M.TERMS_ROUND = T.TERMS_ROUND
                WHERE M.MEM_SNO = :mem_sno:
                AND T.TERMS_USE_YN = 'Y'
                ORDER BY M.TERMS_BCOFF_CD DESC, M.TERMS_KND_CD ASC, M.TERMS_ROUND ASC
                ";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 회원 약관동의 insert
     * @param array $data
     */
    public function insert_mem_terms(array $data)
    {
        $sql = "INSERT mem_terms_tbl SET
					MEM_SNO 		         = :mem_sno:
					,MEM_ID			         = :mem_id:
                    ,MEM_NM		             = :mem_nm:
					,TERMS_KND_CD			 = :terms_knd_cd:
					,TERMS_COMP_CD			 = :terms_comp_cd:
					,TERMS_BCOFF_CD		     = :terms_bcoff_cd:
					,TERMS_AGREE_YN		     = :terms_agree_yn:
					,TERMS_ROUND		     = :terms_round:
					,TERMS_AGREE_MEM_SNO	 = :terms_agree_mem_sno:
					,TERMS_AGREE_MEM_ID	     = :terms_agree_mem_id:
					,TERMS_AGREE_MEM_NM		 = :terms_agree_mem_nm:
                    ,TERMS_DEPU_YN	         = :terms_depu_yn:
                    ,TERMS_DEPU_MEM_SNO	     = :terms_depu_mem_sno:
                    ,TERMS_DEPU_MEM_ID	     = :terms_depu_mem_id:
                    ,TERMS_DEPU_MEM_NM	     = :terms_depu_mem_nm:
                    ,TERMS_DEPU_DATETM	     = :terms_depu_datetm:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'mem_sno' 			    => $data['mem_sno']
            ,'mem_id'			    => $data['mem_id']
            ,'mem_nm'			    => $data['mem_nm']
            ,'terms_knd_cd'			=> $data['terms_knd_cd']
            ,'terms_comp_cd'		=> $data['terms_comp_cd']
            ,'terms_bcoff_cd'		=> $data['terms_bcoff_cd']
            ,'terms_agree_yn'		=> $data['terms_agree_yn']
            ,'terms_round'			=> $data['terms_round']
            ,'terms_agree_mem_sno'	=> $data['terms_agree_mem_sno']
            ,'terms_agree_mem_id'	=> $data['terms_agree_mem_id']
            ,'terms_agree_mem_nm'	=> $data['terms_agree_mem_nm']
            ,'terms_depu_yn'		=> $data['terms_depu_yn']
            ,'terms_depu_mem_sno'	=> $data['terms_depu_mem_sno']
            ,'terms_depu_mem_id'	=> $data['terms_depu_mem_id']
            ,'terms_depu_mem_nm'	=> $data['terms_depu_mem_nm']
            ,'terms_depu_datetm'	=> $data['terms_depu_datetm']
            ,'cre_id'			=> $data['cre_id']
            ,'cre_datetm'		=> $data['cre_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    public function mobile_list_terms_join_agree(array $data)
    {
        $sql = "SELECT *
                FROM terms_mgmt_tbl
                WHERE TERMS_USE_YN = 'Y'
                ORDER BY TERMS_KND_CD ASC
                ";
        $query = $this->db->query($sql, [
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 약관 미동의 리스트 가져오기
     * @param array $data
     * @return array
     */
    public function mobile_list_terms_basic(array $data)
    {
        $sql = "SELECT T.*
                FROM terms_mgmt_tbl AS T
                LEFT OUTER JOIN mem_terms_tbl AS M
                ON T.TERMS_KND_CD = M.TERMS_KND_CD
                AND T.TERMS_ROUND = M.TERMS_ROUND
                AND M.MEM_SNO = :mem_sno:
                WHERE T.TERMS_USE_YN = 'Y'
                AND IFNULL(M.TERMS_AGREE_YN,'') != 'Y'
                ";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 약관 미동의 카운트 가져오기
     * @param array $data
     * @return array
     */
    public function mobile_chk_terms_basic_count(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter
                FROM terms_mgmt_tbl AS T
                LEFT OUTER JOIN mem_terms_tbl AS M
                ON T.TERMS_KND_CD = M.TERMS_KND_CD 
                AND T.TERMS_ROUND = M.TERMS_ROUND
                AND M.MEM_SNO = :mem_sno:
                WHERE T.TERMS_USE_YN = 'Y'
                AND IFNULL(M.TERMS_AGREE_YN,'') != 'Y'
                ";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 지점설정 리스트 가져오기
     * @param array $data
     * @return array
     */
    public function mobile_get_comp_info(array $data)
    {
        $sql = "SELECT
                *
                FROM mem_main_info_tbl
                WHERE MEM_SNO = :mem_sno:
                ";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 해당 지점의 회원 정보가 존재하는지에 대한 여부를 카운트로 리턴한다.
     * @param array $data
     * @return array
     */
    public function mobile_chk_bcoff_exist_count(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM mem_info_detl_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND MEM_SNO = :mem_sno:
                ";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
            ,'comp_cd'		    => $data['comp_cd']
            ,'bcoff_cd'		   => $data['bcoff_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    
    /**
     * 종료회원상태로 업데이트한다. update
     * @param array $data
     */
    public function update_mem_set_comp(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
					SET_COMP_CD 		= :set_comp_cd:
					,SET_BCOFF_CD	    = :set_bcoff_cd:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE MEM_SNO       = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
            ,'set_comp_cd'		=> $data['set_comp_cd']
            ,'set_bcoff_cd'		=> $data['set_bcoff_cd']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }

    
    
    /**
     * 회사 가져오기
     * @param array $data
     * @return array
     */
    public function list_comp(array $data)
    {
        $sql = "SELECT 
                B.COMP_CD, B.COMP_NM, B.COMP_TELNO 
                FROM  smgmt_mgmt_tbl B
                ";

        if (isset($data['compNm']) && $data['compNm'] !== '') {
            $sql .= " WHERE B.COMP_NM LIKE '%{$data['compNm']}%' ";
        }

        $queryParams = [];

        
        // SQL 실행
        $query = $this->db->query($sql, $queryParams);

        return $query->getResultArray();
    }
    
    /**
     * 지점설정 리스트 가져오기
     * @param array $data
     * @return array
     */
    public function mobile_list_comp_info(array $data)
    {
        $sql = "SELECT 
                B.COMP_CD, B.BCOFF_CD, B.BCOFF_ADDR, B.BCOFF_TELNO, B.BCOFF_NM, B.MNGR_NM, B.MNGR_TELNO, C.COMP_NM, C.COMP_TELNO 
                FROM bcoff_mgmt_tbl AS B 
                LEFT OUTER JOIN smgmt_mgmt_tbl AS C ON B.COMP_CD = C.COMP_CD";

        $queryParams = [];
        $conditions = [];

        // 지점명 필터 추가
        if (!empty($data['bcoffNm'])) {
            $conditions[] = "B.BCOFF_NM LIKE :bcoffNm:";
            $queryParams['bcoffNm'] = "%{$data['bcoffNm']}%";
        }

        // 회사코드 필터 추가
        if (!empty($data['compCd'])) {
            $conditions[] = "C.COMP_CD = :compCd:";
            $queryParams['compCd'] = $data['compCd'];
        }

        // WHERE 절 추가
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // SQL 실행
        $query = $this->db->query($sql, $queryParams);

        return $query->getResultArray();
    }

    
    /**
     * 지점변경 리스트 가져오기
     * @param array $data
     * @return array
     */
    public function mobile_list_comp_info_mem_sno(array $data)
    {
        $sql = "SELECT B.COMP_CD, B.BCOFF_CD, B.BCOFF_ADDR, B.BCOFF_TELNO, B.BCOFF_NM, C.COMP_NM, C.COMP_TELNO, D.MEM_STAT 
                FROM bcoff_mgmt_tbl AS B 
                LEFT OUTER JOIN smgmt_mgmt_tbl AS C
                ON B.COMP_CD = C.COMP_CD 
                LEFT OUTER JOIN mem_info_detl_tbl AS D 
                ON B.COMP_CD = D.COMP_CD 
                AND B.BCOFF_CD = D.BCOFF_CD 
                AND D.MEM_SNO = :mem_sno:
                ";
        $query = $this->db->query($sql, [
            'mem_sno'			=> $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    
    
    /**
     * 지점설정 한후 세션을 위하여 회사명 지점이름 가져오기
     * @param array $data
     * @return array
     */
    public function mobile_get_comp_info_for_bcoff_cd(array $data)
    {
        $sql = "SELECT
                B.COMP_CD, B.BCOFF_CD, B.BCOFF_ADDR, B.BCOFF_TELNO, B.BCOFF_NM, C.COMP_NM, C.COMP_TELNO
                FROM bcoff_mgmt_tbl AS B
                LEFT OUTER JOIN smgmt_mgmt_tbl AS C
                ON B.COMP_CD = C.COMP_CD
                WHERE B.BCOFF_CD = :bcoff_cd:
                ";
        $query = $this->db->query($sql, [
            'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 매출 상세 통계 가져오기
     * @param array $data
     * @return array
     */
    public function all_sales_total(array $data)
    {
        $sql = "SELECT PAYMT_CHNL,PAYMT_MTHD,SALES_DV,SALES_DV_RSON,SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(CRE_DATETM) BETWEEN :sdate: AND :edate:
                GROUP BY PAYMT_CHNL,PAYMT_MTHD,SALES_DV,SALES_DV_RSON;
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 매출 상세 통계 가져오기
     * @param array $data
     * @return array
     */
    public function all_sales_total_1(array $data)
    {
        $sql = "SELECT SALES_DV,SALES_DV_RSON,SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(CRE_DATETM) BETWEEN :sdate: AND :edate:
                GROUP BY SALES_DV,SALES_DV_RSON;
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 매출 상세 통계 가져오기
     * @param array $data
     * @return array
     */
    public function all_sales_total_2(array $data)
    {
        $sql = "SELECT SALES_DV_RSON,SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(CRE_DATETM) BETWEEN :sdate: AND :edate:
                GROUP BY SALES_DV_RSON;
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 매출 상세 통계 가져오기
     * @param array $data
     * @return array
     */
    public function all_sales_total_3(array $data)
    {
        $sql = "SELECT SALES_DV,SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(CRE_DATETM) BETWEEN :sdate: AND :edate:
                GROUP BY SALES_DV;
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 매출 상세 통계 가져오기
     * @param array $data
     * @return array
     */
    public function all_sales_total_4(array $data)
    {
        $sql = "SELECT SALES_MEM_STAT,SUM(PAYMT_AMT) AS sum_cost FROM sales_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(CRE_DATETM) BETWEEN :sdate: AND :edate:
                GROUP BY SALES_MEM_STAT;
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sdate'			=> $data['sdate']
            ,'edate'			=> $data['edate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 현재 모바일 이용중인 회원의 회사 이름을 가져온다.
     * @param array $data
     * @return array
     */
    public function mobile_get_comp_nm(array $data)
    {
        $sql = "SELECT COMP_NM FROM smgmt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 현재 모바일 이용중인 회원의 가맹점 이름을 가져온다.
     * @param array $data
     * @return array
     */
    public function mobile_get_bcoff_nm(array $data)
    {
        $sql = "SELECT BCOFF_NM FROM bcoff_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'           => $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function mobile_list_bcoff(array $data)
    {
        $sql = "SELECT * FROM mem_info_detl_tbl
                WHERE MEM_SNO = :mem_sno:
                ";
        $query = $this->db->query($sql, [
            'mem_sno'			=> $data['mem_sno']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 모바일 로그인 체크 (bio)
     * @param array $data
     * @return array
     */
    public function mobile_login_bio_check(array $data)
    {
        $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM, M.MEM_GENDR, M.MEM_DV, M.SET_COMP_CD, M.SET_BCOFF_CD, M.USE_YN, M.CONN_POSS_YN, M.SECE_DATETM, B.BCOFF_CD, B.BCOFF_MGMT_ID, D.BCOFF_NM, C.TCHR_POSN 
                FROM mem_main_info_tbl M  
                LEFT OUTER JOIN bcoff_mgmt_tbl AS B 
                ON B.BCOFF_MGMT_ID = M.MEM_ID
                LEFT OUTER JOIN bcoff_mgmt_tbl AS D 
                ON D.BCOFF_CD = M.SET_BCOFF_CD                
                LEFT OUTER JOIN mem_info_detl_tbl C ON M.MEM_SNO = C.MEM_SNO
                WHERE M.MEM_ID = :login_id:
                ";
        $query = $this->db->query($sql, [
            'login_id'			=> $data['login_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 모바일 로그인 체크 (kpad)
     * @param array $data
     * @return array
     */
    public function mobile_login_kpad_check(array $data)
    {
        $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM, M.MEM_GENDR, M.MEM_DV, M.SET_COMP_CD, M.SET_BCOFF_CD, M.USE_YN, M.CONN_POSS_YN, M.SECE_DATETM, B.BCOFF_CD, B.BCOFF_MGMT_ID, D.BCOFF_NM, C.TCHR_POSN              
                FROM mem_main_info_tbl M  
                LEFT OUTER JOIN bcoff_mgmt_tbl AS B 
                ON B.BCOFF_MGMT_ID = M.MEM_ID
                LEFT OUTER JOIN bcoff_mgmt_tbl AS D 
                ON D.BCOFF_CD = M.SET_BCOFF_CD       
                LEFT OUTER JOIN mem_info_detl_tbl C ON M.MEM_SNO = C.MEM_SNO
                WHERE M.MEM_ID = :login_id:
                AND M.MEM_SPWD = :login_pwd:
                ";
        $query = $this->db->query($sql, [
            'login_id'			=> $data['login_id']
            ,'login_pwd'		=> $data['login_pwd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 모바일 로그인 체크
     * @param array $data
     * @return array
     */
    public function mobile_login_check(array $data)
    {
        $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM, M.MEM_GENDR, M.MEM_DV, M.SET_COMP_CD, M.SET_BCOFF_CD, M.USE_YN, M.CONN_POSS_YN, M.SECE_DATETM, B.BCOFF_CD, B.BCOFF_MGMT_ID, B.BCOFF_NM, C.TCHR_POSN  
                FROM mem_main_info_tbl M LEFT OUTER JOIN bcoff_mgmt_tbl AS B ON B.BCOFF_CD = M.SET_BCOFF_CD LEFT OUTER JOIN mem_info_detl_tbl C ON M.MEM_SNO = C.MEM_SNO
                WHERE M.MEM_ID = :login_id:
                AND M.MEM_PWD = :login_pwd:";
        $query = $this->db->query($sql, [
            'login_id'			=> $data['login_id']
            ,'login_pwd'		=> $data['login_pwd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 모바일 ID로만 로그인 체크 (전화번호 인증 로그인용)
     * @param array $data
     * @return array
     */
    public function mobile_login_id_check(array $data)
    {
        $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM, M.MEM_GENDR, M.MEM_DV, M.SET_COMP_CD, M.SET_BCOFF_CD, M.USE_YN, M.CONN_POSS_YN, M.SECE_DATETM, B.BCOFF_CD, B.BCOFF_MGMT_ID, B.BCOFF_NM, C.TCHR_POSN  
                FROM mem_main_info_tbl M LEFT OUTER JOIN bcoff_mgmt_tbl AS B ON B.BCOFF_CD = M.SET_BCOFF_CD LEFT OUTER JOIN mem_info_detl_tbl C ON M.MEM_SNO = C.MEM_SNO
                WHERE M.MEM_ID = :login_id:";
        $query = $this->db->query($sql, [
            'login_id' => $data['login_id']
        ]);
        
        return $query->getResultArray();
    }
    
    private function where_get_mem_status(array $data)
    {
        $add_where = "";
        switch ($data['type'])
        {
            case "JON":
                $add_where = " AND SUBSTR(JON_DATETM,1,10) = '{$data['sdate']}'";
                break;
            case "REG":
                $add_where = " AND SUBSTR(REG_DATETM,1,10) = '{$data['sdate']}'";
                break;
            case "RE_REG":
                $add_where = " AND SUBSTR(RE_REG_DATETM,1,10) = '{$data['sdate']}'";
                break;
            case "END":
                $add_where = " AND SUBSTR(END_DATETM,1,10) = '{$data['sdate']}'";
                break;
        }
        
        return $add_where;
    }
    
    /**
     * dashboard 에 가입,등록,재등록,종료 회원 수를 리턴한다.
     * JON_DATETM : 가입
     * REG_DATETM : 등록
     * RE_REG_DATETM : 재등록
     * END_DATETM : 종료
     * @param array $data
     */
    public function get_mem_status(array $data)
    {
        $add_where = $this->where_get_mem_status($data);
        $sql = "SELECT COUNT(*) AS counter FROM mem_info_detl_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                {$add_where}
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd' 		=> $data['bcoff_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
	
    /**
     * 아이디 중복 체크 카운트를 리턴한다.
     * @param array $data
     */
    public function id_chk(array $data)
    {
        $sql = "SELECT COUNT(*) AS counter FROM mem_main_info_tbl
                WHERE MEM_ID = :mem_id:
				";
        $mem_id = $data['mem_id'];
        $query = $this->db->query($sql, [
            'mem_id' 			=> $data["mem_id"]
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 종료회원상태로 업데이트한다. update
     * @param array $data
     */
    public function update_mem_end(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
					MEM_STAT 		= :mem_stat:
					,END_DATETM	    = :end_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE MEM_SNO       = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
            ,'mem_stat'			=> $data['mem_stat']
            ,'end_datetm'		=> $data['end_datetm']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
	 * 로그인 체크
	 * @param array $data
	 * @return array
	 */
    public function get_login_info(array $data)
    {
        $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM,M.MEM_DV, D.COMP_CD, D.BCOFF_CD, C.BCOFF_NM
                ,M.USE_YN, B.BCOFF_MGMT_ID, RU.seq_role, R.nm_role, S.COMP_NM, D.TCHR_POSN
                FROM mem_main_info_tbl AS M 
                LEFT OUTER JOIN mem_info_detl_tbl AS D
                ON M.MEM_SNO = D.MEM_SNO
                LEFT OUTER JOIN bcoff_mgmt_tbl AS B 
                ON B.BCOFF_MGMT_ID = M.MEM_ID
                LEFT OUTER JOIN bcoff_mgmt_tbl AS C 
                ON C.BCOFF_CD = M.SET_BCOFF_CD
                LEFT OUTER JOIN tcmg_role_user AS RU
                ON M.MEM_SNO = RU.mem_sno
                LEFT OUTER JOIN tcmg_role AS R
                ON RU.seq_role = R.seq_role
                LEFT OUTER JOIN smgmt_mgmt_tbl AS S
                ON B.COMP_CD = S.COMP_CD
                WHERE M.MEM_ID = :mem_id:
                ";
        $query = $this->db->query($sql, [
            'mem_id'			=> $data['mem_id']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }


    /**
	 * 로그인 체크
	 * @param array $data
	 * @return array
	 */
    public function tlogin_check(array $data)
    {
        $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM,M.MEM_DV, D.COMP_CD, D.BCOFF_CD, C.BCOFF_NM
                ,M.USE_YN, B.BCOFF_MGMT_ID, RU.seq_role, R.nm_role, S.COMP_NM, D.TCHR_POSN
                FROM mem_main_info_tbl AS M 
                LEFT OUTER JOIN mem_info_detl_tbl AS D
                ON M.MEM_SNO = D.MEM_SNO
                LEFT OUTER JOIN bcoff_mgmt_tbl AS B 
                ON B.BCOFF_MGMT_ID = M.MEM_ID
                LEFT OUTER JOIN bcoff_mgmt_tbl AS C 
                ON C.BCOFF_CD = M.SET_BCOFF_CD
                LEFT OUTER JOIN tcmg_role_user AS RU
                ON M.MEM_SNO = RU.mem_sno
                LEFT OUTER JOIN tcmg_role AS R
                ON RU.seq_role = R.seq_role
                LEFT OUTER JOIN smgmt_mgmt_tbl AS S
                ON B.COMP_CD = S.COMP_CD
                WHERE M.MEM_ID = :login_id:
                AND M.MEM_PWD = :login_pwd:
                ";
        $query = $this->db->query($sql, [
            'login_id'			=> $data['login_id']
            ,'login_pwd'		=> $data['login_pwd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    

    /**
     * 회사코드, 지점코드, 지점 관리자 아이디로 회원정보를 가져온다.
     * @param array $data
     */
    public function get_mgr_info(array $data)
    {
    	$sql = "SELECT * FROM bcoff_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND BCOFF_MGMT_ID = :user_id:
				";
    	$query = $this->db->query($sql, [
    			'comp_cd' 			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    			,'user_id'			=> $data['user_id']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    	
    }

    /**
     * 회원 일련번로를 이용하여 회원정보를 가져온다.
     * @param array $data
     */
    public function get_mem_info_mem_id(array $data)
    {
    	$sql = "SELECT * FROM mem_info_detl_tbl
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
     * 회원 일련번로를 이용하여 회원정보를 가져온다.
     * @param array $data
     */
    public function get_mem_info_mem_sno(array $data)
    {
    	$sql = "SELECT * FROM mem_info_detl_tbl
				WHERE MEM_DV = 'M'  -- 회원'M'에게서 추출   
				AND MEM_SNO = :mem_sno:
				";
    	$query = $this->db->query($sql, [
    			'mem_sno'			=> $data['mem_sno']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    	
    }
    
    /**
     * 회원 일련번로를 이용하여 강사정보를 가져온다.
     * @param array $data
     */
    public function get_tmem_info_mem_sno(array $data)
    {
        $sql = "SELECT * FROM mem_info_detl_tbl
				WHERE MEM_DV = 'T'
				AND MEM_SNO = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'mem_sno'			=> $data['mem_sno']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
        
    }
    
    /**
     * 회원명을 이용하여 like 검색을 한다.
     * @param array $data
     */
    public function search_like_mem_nm(array $data)
    {
    	$sql = "SELECT * FROM mem_info_detl_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd:
				AND MEM_DV = 'M'
				AND ( MEM_NM LIKE :mem_nm: OR MEM_TELNO LIKE :mem_telno_short: OR MEM_ID LIKE :mem_id: )
                AND MEM_STAT <> 99
				";
    	$query = $this->db->query($sql, [
    			'comp_cd' 			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    			,'mem_nm'			=> "%".$data['mem_nm']."%"
    	        ,'mem_telno_short'  => "%".$data['mem_nm']."%"
                ,'mem_id'           => "%".$data['mem_nm']."%"
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    	
    }
    
    /**
     * =================================================================================
     * mem_manage [회원관리] - START
     * =================================================================================
     */
    
    private function list_mem_where(array $data)
    {
        $add_where = "";
        
        // 회원상태
        if (isset($data['smst']))
        {
            if ($data['smst'] != '')
            {
                if($data['smst'] == '02')  // 중지회원
                {
                    $add_where .= " AND MEM_STAT = '02' ";
                } else if($data['smst'] == '03')  // 미결제회원
                {
                    $add_where .= " AND MEM_STAT = '03' ";
                } else if($data['smst'] == '04')  // 미수금회원
                {
                    $add_where .= " AND MEM_STAT = '04' ";
                } else  // 현재회원
                {
                    $add_where .= " AND MEM_STAT = '{$data['smst']}' ";
                }
            }
        }
        
        // 회원이름
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND (MEM_NM LIKE '%{$data['snm']}%' OR MEM_ID LIKE '%{$data['snm']}%' OR MEM_TELNO LIKE '%{$data['snm']}%') ";
            }
        }
        
        // 회원 전화번호
        if (isset($data['stel']))
        {
            if ($data['stel'] != '')
            {
                $add_where .= " AND MEM_TELNO LIKE '%{$data['stel']}%' ";
            }
        }
        
        // 가입장소
        if (isset($data['sjp']))
        {
            if ($data['sjp'] != '')
            {
                $add_where .= " AND JON_PLACE = '{$data['sjp']}' ";
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
                        case "jon":
                            $add_where .= " AND JON_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "reg":
                            $add_where .= " AND REG_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "rereg":
                            $add_where .= " AND RE_REG_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                        case "end":
                            $add_where .= " AND END_DATETM BETWEEN '{$sdate}' AND '{$edate}' ";
                            break;
                    }
                }
            }
        }
        
        return $add_where;
    }
    
    /**
     * 회원 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_mem_count(array $data)
    {
        $add_where = $this->list_mem_where($data);
        $sql = "SELECT COUNT(*) as counter FROM mem_info_detl_tbl
                WHERE MEM_DV = 'M'
                AND COMP_CD = '" . $_SESSION['comp_cd'] . "'
                AND BCOFF_CD = '" . $_SESSION['bcoff_cd'] ."'
                {$add_where}
				";
        
        $query = $this->db->query($sql, [
            
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 회원 [ List Board ]
     * @param array $data
     * @return array
     */
    public function list_mem(array $data)
    {
        $add_where = $this->list_mem_where($data);
        $sql = "SELECT * FROM mem_info_detl_tbl
                WHERE MEM_DV = 'M'
                AND COMP_CD = '" . $_SESSION['comp_cd'] . "'
                AND BCOFF_CD = '" . $_SESSION['bcoff_cd'] ."'
                {$add_where}
                ORDER BY CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
        
        $query = $this->db->query($sql, [
            
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * =================================================================================
     * mem_manage [회원관리] - END
     * =================================================================================
     */
    
    /**
     * =================================================================================
     * tchr_manage [강사관리] - START
     * ================================================================================= 
     */
    
    private function list_tchr_where(array $data)
    {
        $add_where = "";
        
        // 강사이름
        if (isset($data['snm']))
        {
            if ($data['snm'] != '')
            {
                $add_where .= " AND MEM_NM LIKE '%{$data['snm']}%' ";
            }
        }
        
        // 강사직책
        if (isset($data['sposn']))
        {
            if ($data['sposn'] != '')
            {
                $add_where .= " AND TCHR_POSN = '{$data['sposn']}' ";
            }
        }
        
        // 계약형태
        if (isset($data['sctrct']))
        {
            if ($data['sctrct'] != '')
            {
                $add_where .= " AND CTRCT_TYPE = '{$data['sctrct']}' ";
            }
        }
        
        return $add_where;
    }
    
    /**
     * 강사 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_tchr_count(array $data)
    {
        $add_where_tchr = $this->list_tchr_where($data);
        $sql = "SELECT COUNT(*) as counter FROM mem_info_detl_tbl
                WHERE MEM_DV = 'T'
                AND COMP_CD = '" . $_SESSION['comp_cd'] . "'
                AND BCOFF_CD = '" . $_SESSION['bcoff_cd'] ."'
                {$add_where_tchr}
				";
        
        $query = $this->db->query($sql, [
            
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 강사 [ List Board ]
     * @param array $data
     * @return array
     */
    public function list_tchr(array $data)
    {
        $add_where_tchr = $this->list_tchr_where($data);
        $sql = "SELECT a.*, a.MEM_THUMB_IMG, a.MEM_MAIN_IMG
                FROM mem_info_detl_tbl a
                WHERE a.MEM_DV = 'T'
                AND a.COMP_CD = '" . $_SESSION['comp_cd'] . "'
                AND a.BCOFF_CD = '" . $_SESSION['bcoff_cd'] ."'
                {$add_where_tchr}
                ORDER BY a.USE_YN DESC, a.END_DATETM DESC, a.CRE_DATETM DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
        
        $query = $this->db->query($sql, [
            
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * =================================================================================
     * tchr_manage [강사관리] - END
     * =================================================================================
     */
    
    /**
     * 강사 리스트를 가져온다. (수당관리)
     * @param array $data
     */
    public function get_list_tchr_sarly(array $data)
    {
        $sql = "SELECT a.MEM_SNO,a.MEM_ID,a.MEM_NM,a.MEM_GENDR,a.TCHR_POSN,a.CTRCT_TYPE,
                       b.MEM_THUMB_IMG,b.MEM_MAIN_IMG
                FROM mem_info_detl_tbl a
                LEFT JOIN mem_info_detl_tbl b ON a.MEM_SNO = b.MEM_SNO AND a.COMP_CD = b.COMP_CD AND a.BCOFF_CD = b.BCOFF_CD
                WHERE a.COMP_CD = :comp_cd:
                AND a.BCOFF_CD = :bcoff_cd:
                AND a.MEM_DV = 'T'
                AND IFNULL(a.END_DATETM,'9999-12-31 00:00:00') > :sarly_sece_date:
                ORDER BY a.USE_YN DESC, a.END_DATETM DESC, a.CRE_DATETM DESC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'sarly_sece_date'  => $data['sarly_sece_date']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 리스트를 가져온다.
     * @param array $data
     */
    public function get_list_tchr(array $data)
    {
        $sql = "SELECT a.MEM_SNO,a.MEM_ID,a.MEM_NM,a.MEM_GENDR,a.TCHR_POSN,a.CTRCT_TYPE,a.USE_YN,
                       a.MEM_ID as TCHR_ID, -- TCHR_ID 필드 추가 (MEM_ID와 동일값)
                       a.MEM_THUMB_IMG,a.MEM_MAIN_IMG
                FROM mem_info_detl_tbl a
                WHERE a.COMP_CD = :comp_cd:
                AND a.BCOFF_CD = :bcoff_cd:
                AND a.MEM_DV = 'T'
                ORDER BY a.USE_YN DESC, a.END_DATETM DESC, a.CRE_DATETM DESC
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 강사 정보를 가져온다.
     * 강사 아이디를 이용하여 해당 강사의 정보를 가져온다.
     * @param array $data
     */
    public function get_list_tchr_for_id(array $data)
    {
        $sql = "SELECT a.MEM_SNO,a.MEM_ID,a.MEM_NM,a.MEM_GENDR,a.TCHR_POSN,a.CTRCT_TYPE,
                       b.MEM_THUMB_IMG,b.MEM_MAIN_IMG
                FROM mem_info_detl_tbl a
                LEFT JOIN mem_info_detl_tbl b ON a.MEM_SNO = b.MEM_SNO AND a.COMP_CD = b.COMP_CD AND a.BCOFF_CD = b.BCOFF_CD
                WHERE a.COMP_CD = :comp_cd:
                AND a.BCOFF_CD = :bcoff_cd:
                AND a.MEM_ID = :mem_id:
                AND a.MEM_DV = 'T'
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
     * 회원 아이디를 이용하여 아이디와 이름을 가져온다.
     * @param array $data
     */
    public function get_mem_info_id_idname(array $data)
    {
        $sql = "SELECT MEM_SNO,MEM_ID,MEM_NM,MEM_GENDR
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
     * 추천아이디
     * 회원 아이디를 이용하여 아이디와 이름을 가져온다.
     * @param array $data
     */
    public function get_mem_info_id_nocomp(array $data)
    {
        $sql = "SELECT MEM_SNO,MEM_ID,MEM_NM
                FROM mem_main_info_tbl
                WHERE MEM_ID = :mem_id:
				";
        
        $query = $this->db->query($sql, [
            'mem_id'			=> $data['mem_id']
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
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 회원정보상세내역 insert
     * @param array $data
     */
    public function insert_mem_info_detl_hist_tbl(array $data)
    {
    	$sql = "INSERT mem_info_detl_hist_tbl SET
					MEM_INFO_DETL_HIST_SNO = :mem_info_detl_hist_sno:
					,MEM_SNO 		= :mem_sno:
					,COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
                    ,MEM_ID		    = :mem_id:
					,MEM_STAT		= :mem_stat:
					,MEM_DV			= :mem_dv:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'mem_info_detl_hist_sno' => $data['mem_info_detl_hist_sno']
    			,'mem_sno' 			=> $data['mem_sno']
    			,'comp_cd'			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    			,'mem_id'			=> $data['mem_id']
    			,'mem_stat'			=> $data['mem_stat']
    			,'mem_dv'			=> $data['mem_dv']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 강사상세정보 insert
     * @param array $data
     */
    public function insert_tchr_info_detl(array $data)
    {
    	$sql = "INSERT tchr_info_detl_tbl SET
					MEM_SNO 		= :mem_sno:
					,COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
					,MEM_ID			= :mem_id:
					,MEM_DV			= :mem_dv:
					,TCHR_POSN		= :tchr_posn:
					,CTRCT_TYPE		= :ctrct_type:
					,TCHR_SIMP_PWD	= :tchr_simp_pwd:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'mem_sno' 			=> $data['mem_sno']
    			,'comp_cd'			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    			,'mem_id'			=> $data['mem_id']
    			,'mem_dv'			=> $data['mem_dv']
    			,'tchr_posn'		=> $data['tchr_posn']
    			,'ctrct_type'		=> $data['ctrct_type']
    			,'tchr_simp_pwd'	=> $data['tchr_simp_pwd']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 강사상세정보내역 insert
     * @param array $data
     */
    public function insert_tchr_info_detl_hist(array $data)
    {
    	$sql = "INSERT tchr_info_detl_hist_tbl SET
					TCHR_INFO_DETL_HIST_SNO = :tchr_info_detl_hist_sno:
                    ,TCHR_INFO_DETL_SNO = :tchr_info_detl_sno:
					,MEM_SNO 		= :mem_sno:
					,COMP_CD		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
					,MEM_ID			= :mem_id:
					,MEM_DV			= :mem_dv:
					,TCHR_POSN		= :tchr_posn:
					,CTRCT_TYPE		= :ctrct_type:
					,TCHR_SIMP_PWD	= :tchr_simp_pwd:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'tchr_info_detl_hist_sno' => $data['tchr_info_detl_hist_sno']
    			,'tchr_info_detl_sno' => $data['tchr_info_detl_sno']
    			,'mem_sno' 			=> $data['mem_sno']
    			,'comp_cd'			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    			,'mem_id'			=> $data['mem_id']
    			,'mem_dv'			=> $data['mem_dv']
    			,'tchr_posn'		=> $data['tchr_posn']
    			,'ctrct_type'		=> $data['ctrct_type']
    			,'tchr_simp_pwd'	=> $data['tchr_simp_pwd']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 회원메인정보 간편비밀번호변경 update
     * @param array $data
     */
    public function update_mem_main_info_spwd(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
                    MEM_SPWD		= :mem_spwd:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE MEM_SNO = :mem_sno:
				";
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
            ,'mem_spwd'			=> $data['mem_spwd']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 지점관리자 비밀번호변경 update
     * @param array $data
     */
    public function update_bcoff_pwd(array $data)
    {
        $sql = "UPDATE bcoff_mgmt_tbl
 SET
                    BCOFF_MGMT_PWD		= :mem_pwd:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE BCOFF_MGMT_ID = :mem_id:
				";
        $query = $this->db->query($sql, [
            'mem_id' 			=> $data['mem_id']
            ,'mem_pwd'			=> $data['mem_pwd']
            ,'comp_cd'          => $data['comp_cd']
            ,'bcoff_cd'         => $data['bcoff_cd']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }

    /**
     * 지점관리자 비밀번호변경 update
     * @param array $data
     */
    public function update_bcoff_accept_pwd(array $data)
    {
        $sql = "UPDATE bcoff_appct_mgmt_tbl
                SET
                    BCOFF_MGMT_PWD		= :mem_pwd:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE BCOFF_MGMT_ID = :mem_id:
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
				";
        $query = $this->db->query($sql, [
            'mem_id' 			=> $data['mem_id']
            ,'mem_pwd'			=> $data['mem_pwd']
            ,'comp_cd'          => $data['comp_cd']
            ,'bcoff_cd'         => $data['bcoff_cd']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 회원메인정보 update
     * @param array $data
     */
    public function update_mem_main_info(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
                    MEM_NM         = :mem_nm:,
                    BTHDAY         = :bthday:,
                    MEM_GENDR      = :mem_gendr:,
                    MEM_TELNO      = :mem_telno:,
                    MEM_TELNO_ENC  = :mem_telno_enc:,
                    MEM_TELNO_MASK = :mem_telno_mask:,
                    MEM_TELNO_SHORT = :mem_telno_short:,
                    MEM_TELNO_ENC2  = :mem_telno_enc2:,
                    MEM_ADDR       = :mem_addr:,
                    MOD_ID         = :mod_id:,
                    MOD_DATETM     = :mod_datetm:";

        $params = [
            'mem_nm'          => $data['mem_nm'],
            'bthday'          => $data['bthday'],
            'mem_gendr'       => $data['mem_gendr'],
            'mem_telno'       => $data['mem_telno'],
            'mem_telno_enc'   => $data['mem_telno_enc'],
            'mem_telno_mask'  => $data['mem_telno_mask'],
            'mem_telno_short' => $data['mem_telno_short'],
            'mem_telno_enc2'  => $data['mem_telno_enc2'],
            'mem_addr'        => $data['mem_addr'],
            'mod_id'          => $data['mod_id'],
            'mod_datetm'      => $data['mod_datetm'],
        ];

        // 값이 있는 경우에만 쿼리와 파라미터에 추가
        if (!empty($data['mem_main_img'])) {
            $sql .= ", MEM_MAIN_IMG = :mem_main_img:";
            $params['mem_main_img'] = $data['mem_main_img'];
        }
        if (!empty($data['mem_thumb_img'])) {
            $sql .= ", MEM_THUMB_IMG = :mem_thumb_img:";
            $params['mem_thumb_img'] = $data['mem_thumb_img'];
        }

        // WHERE 절
        $sql .= " WHERE MEM_SNO = :mem_sno:";
        $params['mem_sno'] = $data['mem_sno'];

        // 실행
        $query = $this->db->query($sql, $params);

        // 결과 리턴
        $data['query_success'] = $query;
        return $data;
    }

    /**
     * 회원정보상세 update
     * @param array $data
     */
    public function update_mem_info_detl_tbl(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
                    MEM_NM         = :mem_nm:,
                    BTHDAY         = :bthday:,
                    MEM_GENDR      = :mem_gendr:,
                    MEM_TELNO      = :mem_telno:,
                    MEM_TELNO_ENC  = :mem_telno_enc:,
                    MEM_TELNO_MASK = :mem_telno_mask:,
                    MEM_TELNO_SHORT = :mem_telno_short:,
                    MEM_TELNO_ENC2  = :mem_telno_enc2:,
                    MEM_ADDR       = :mem_addr:,
                    MOD_ID         = :mod_id:,
                    MOD_DATETM     = :mod_datetm:";

        $params = [
            'mem_nm'          => $data['mem_nm'],
            'bthday'          => $data['bthday'],
            'mem_gendr'       => $data['mem_gendr'],
            'mem_telno'       => $data['mem_telno'],
            'mem_telno_enc'   => $data['mem_telno_enc'],
            'mem_telno_mask'  => $data['mem_telno_mask'],
            'mem_telno_short' => $data['mem_telno_short'],
            'mem_telno_enc2'  => $data['mem_telno_enc2'],
            'mem_addr'        => $data['mem_addr'],
            'mod_id'          => $data['mod_id'],
            'mod_datetm'      => $data['mod_datetm']
        ];

        // 값이 존재할 경우에만 쿼리와 파라미터에 추가
        if (!empty($data['mem_main_img'])) {
            $sql .= ", MEM_MAIN_IMG = :mem_main_img:";
            $params['mem_main_img'] = $data['mem_main_img'];
        }

        if (!empty($data['mem_thumb_img'])) {
            $sql .= ", MEM_THUMB_IMG = :mem_thumb_img:";
            $params['mem_thumb_img'] = $data['mem_thumb_img'];
        }

            // WHERE 조건 추가
            $sql .= " WHERE MEM_SNO = :mem_sno:";
            $params['mem_sno'] = $data['mem_sno'];

        // 쿼리 실행
        $query = $this->db->query($sql, $params);

        // 결과 포함 반환
        $data['query_success'] = $query;
        return $data;
    }


    /**
     * 지점관리자정보 update
     * @param array $data
     */
    public function update_bcoff_info(array $data)
    {
        $sql = "UPDATE bcoff_mgmt_tbl SET
                    MNGR_NM      = :mem_nm:,
                    MNGR_TELNO   = :mem_telno:,
                    MOD_ID       = :mod_id:,
                    MOD_DATETM   = :mod_datetm:";

        $params = [
            'mem_nm'     => $data['mem_nm'],
            'mem_telno'  => $data['mem_telno'],
            'mod_id'     => $data['mod_id'],
            'mod_datetm' => $data['mod_datetm'],
        ];

        // WHERE 조건 추가
        $sql .= " WHERE BCOFF_MGMT_ID = :mem_id:
        AND COMP_CD = :comp_cd:
        AND BCOFF_CD = :bcoff_cd:";

        $params['mem_id'] = $data['mem_id'];
        $params['comp_cd']  = $data['comp_cd'];
        $params['bcoff_cd'] = $data['bcoff_cd'];

        // 실행
        $query = $this->db->query($sql, $params);

        $data['query_success'] = $query;
        return $data;
    }

    /**
     * 지점관리자정보 update (승인용)
     * @param array $data
     */
    public function update_bcoff_accept_info(array $data)
    {
        $sql = "UPDATE bcoff_appct_mgmt_tbl SET
                    MNGR_NM      = :mem_nm:,
                    MNGR_TELNO   = :mem_telno:,
                    MOD_ID       = :mod_id:,
                    MOD_DATETM   = :mod_datetm:";

        $params = [
            'mem_nm'     => $data['mem_nm'],
            'mem_telno'  => $data['mem_telno'],
            'mod_id'     => $data['mod_id'],
            'mod_datetm' => $data['mod_datetm'],
        ];

        if (!empty($data['mngr_main_img'])) {
            $sql .= ", MNGR_MAIN_IMG = :mem_main_img:";
            $params['mem_main_img'] = $data['mngr_main_img'];
        }

        if (!empty($data['mngr_thumb_img'])) {
            $sql .= ", MNGR_THUMB_IMG = :mem_thumb_img:";
            $params['mem_thumb_img'] = $data['mngr_thumb_img'];
        }

        // WHERE 조건 추가
        $sql .= " WHERE BCOFF_MGMT_ID = :mem_id:
        AND COMP_CD = :comp_cd:
        AND BCOFF_CD = :bcoff_cd:";

        $params['mem_id'] = $data['mem_id'];
        $params['comp_cd']  = $data['comp_cd'];
        $params['bcoff_cd'] = $data['bcoff_cd'];

        $query = $this->db->query($sql, $params);

        $data['query_success'] = $query;
        return $data;
    }

    /**
     * 강사정보상세 update
     * @param array $data
     */
    public function update_tmem_info_detl_tbl(array $data)
    {
        $sql = "UPDATE mem_info_detl_tbl SET
                    MEM_NM		    = :mem_nm:
					,BTHDAY			= :bthday:
					,MEM_GENDR		= :mem_gendr:
					,MEM_TELNO		= :mem_telno:
                    ,MEM_TELNO_ENC  = :mem_telno_enc:
                    ,MEM_TELNO_MASK = :mem_telno_mask:
                    ,MEM_TELNO_SHORT = :mem_telno_short:
                    ,MEM_TELNO_ENC2  = :mem_telno_enc2:
					,MEM_ADDR		= :mem_addr:
                    ,TCHR_POSN		= :tchr_posn:
                    ,CTRCT_TYPE		= :ctrct_type:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'mem_sno' 			=> $data['mem_sno']
            ,'mem_nm'			=> $data['mem_nm']
            ,'bthday'			=> $data['bthday']
            ,'mem_gendr'		=> $data['mem_gendr']
            ,'mem_telno'		=> $data['mem_telno']
            ,'mem_telno_enc'		=> $data['mem_telno_enc']
            ,'mem_telno_mask'		=> $data['mem_telno_mask']
            ,'mem_telno_short'		=> $data['mem_telno_short']
            ,'mem_telno_enc2'		=> $data['mem_telno_enc2']
            ,'mem_addr'			=> $data['mem_addr']
            ,'tchr_posn'		=> $data['tchr_posn']
            ,'ctrct_type'		=> $data['ctrct_type']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 체크인 번호(mem_id) 자동 생성
     * comp_cd별로 중복 체크하여 사용 가능한 번호 반환
     * @param string $phone_number 전화번호
     * @param string $comp_cd 회사코드
     * @param string $bcoff_cd 지점코드
     * @return string 생성된 체크인 번호
     */
    public function generate_mem_id($phone_number, $comp_cd, $bcoff_cd) {
        // 전화번호 뒷 4자리 추출
        $last4 = substr($phone_number, -4);
        $mem_id = $last4;
        $suffix = 0;
        
        // 중복 체크하여 사용 가능한 번호 찾기
        while ($this->check_mem_id_duplicate($mem_id, $comp_cd, $bcoff_cd)) {
            $suffix++;
            $mem_id = $last4 . $suffix;
        }
        
        return $mem_id;
    }
    
    /**
     * comp_cd별 mem_id 중복 체크
     * @param string $mem_id 체크할 회원ID
     * @param string $comp_cd 회사코드
     * @param string $bcoff_cd 지점코드
     * @return bool 중복여부 (true: 중복, false: 사용가능)
     */
    public function check_mem_id_duplicate($mem_id, $comp_cd, $bcoff_cd) {
        $sql = "SELECT COUNT(*) as cnt FROM mem_main_info_tbl 
                WHERE mem_id = :mem_id: 
                AND set_comp_cd = :comp_cd: 
                AND set_bcoff_cd = :bcoff_cd:";
        
        $query = $this->db->query($sql, [
            'mem_id' => $mem_id,
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd
        ]);
        
        $result = $query->getResultArray();
        return $result[0]['cnt'] > 0;
    }
    
    /**
     * 전화번호로 회원 조회 (comp_cd별)
     * @param string $phone_number 전화번호
     * @param string $comp_cd 회사코드
     * @return array|null 회원정보
     */
    public function get_member_by_phone($phone_number, $comp_cd) {
        $sql = "SELECT a.*, b.MEM_STAT 
                FROM mem_main_info_tbl a
                LEFT JOIN mem_info_detl_tbl b ON a.MEM_SNO = b.MEM_SNO
                WHERE a.MEM_TELNO = :phone_number:
                AND a.set_comp_cd = :comp_cd:
                AND a.USE_YN = 'Y'
                AND b.MEM_STAT = '00'
                LIMIT 1";
        
        $query = $this->db->query($sql, [
            'phone_number' => $phone_number,
            'comp_cd' => $comp_cd
        ]);
        
        $result = $query->getResultArray();
        return !empty($result) ? $result[0] : null;
    }
    
    /**
     * 전화번호 중복 체크 (comp_cd별)
     * @param string $phone_number 전화번호
     * @param string $comp_cd 회사코드
     * @param string $bcoff_cd 지점코드
     * @return bool 중복여부 (true: 중복, false: 사용가능)
     */
    public function check_phone_duplicate($phone_number, $comp_cd, $bcoff_cd) {
        $sql = "SELECT COUNT(*) as cnt 
                FROM mem_main_info_tbl a
                LEFT JOIN mem_info_detl_tbl b ON a.MEM_SNO = b.MEM_SNO
                WHERE a.MEM_TELNO = :phone_number:
                AND a.set_comp_cd = :comp_cd:
                AND a.set_bcoff_cd = :bcoff_cd:
                AND a.USE_YN = 'Y'
                AND b.MEM_STAT = '00'";
        
        $query = $this->db->query($sql, [
            'phone_number' => $phone_number,
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd
        ]);
        
        $result = $query->getResultArray();
        return $result[0]['cnt'] > 0;
    }
    
    /**
     * 전화번호로 회원 정보 조회 (새로운 메서드)
     * @param string $phoneNumber
     * @param string $comp_cd
     * @param string $bcoff_cd
     * @return array|null
     */
    public function getMemberByPhone($phoneNumber, $comp_cd = null, $bcoff_cd = null)
    {
        try {
            $sql = "SELECT M.MEM_SNO, M.MEM_ID, M.MEM_NM, M.MEM_GENDR, M.MEM_DV, M.SET_COMP_CD, M.SET_BCOFF_CD, M.USE_YN, M.CONN_POSS_YN, M.SECE_DATETM, B.BCOFF_CD, B.BCOFF_MGMT_ID, B.BCOFF_NM, C.TCHR_POSN  
                    FROM mem_main_info_tbl M 
                    LEFT OUTER JOIN bcoff_mgmt_tbl AS B ON B.BCOFF_CD = M.SET_BCOFF_CD 
                    LEFT OUTER JOIN mem_info_detl_tbl C ON M.MEM_SNO = C.MEM_SNO
                    WHERE M.MEM_TELNO = :phone_number:
                    LIMIT 1";
            
            log_message('debug', "getMemberByPhone SQL: phone={$phoneNumber}");
            
            $query = $this->db->query($sql, [
                'phone_number' => $phoneNumber
            ]);
            
            $result = $query->getResultArray();
            
            if ($result && count($result) > 0) {
                log_message('debug', "getMemberByPhone 성공: " . json_encode($result[0]));
                return $result[0];
            } else {
                log_message('debug', "getMemberByPhone 결과 없음");
                return null;
            }
        } catch (\Exception $e) {
            log_message('error', 'getMemberByPhone 오류: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * 모든 회사 목록 조회
     * @return array
     */
    public function mobile_list_all_companies()
    {
        // USE_YN 컬럼이 없을 수도 있으므로 제거
        $sql = "SELECT COMP_CD, COMP_NM
                FROM smgmt_mgmt_tbl
                ORDER BY COMP_NM";
        
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }
    
    /**
     * 회사별 지점 목록 조회
     * @param string $comp_cd
     * @return array
     */
    public function mobile_list_branches_by_company($comp_cd)
    {
        // USE_YN 컬럼이 없을 수도 있으므로 제거
        $sql = "SELECT COMP_CD, BCOFF_CD, BCOFF_NM
                FROM bcoff_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                ORDER BY BCOFF_NM";
        
        $query = $this->db->query($sql, [
            'comp_cd' => $comp_cd
        ]);
        return $query->getResultArray();
    }
    
    /**
     * 모든 지점 목록 조회
     * @return array
     */
    public function mobile_list_all_branches()
    {
        $sql = "SELECT DISTINCT B.COMP_CD, B.BCOFF_CD, B.BCOFF_NM, B.BCOFF_ADDR, B.BCOFF_TELNO, 
                       C.COMP_NM, C.COMP_TELNO
                FROM bcoff_mgmt_tbl AS B 
                LEFT OUTER JOIN smgmt_mgmt_tbl AS C ON B.COMP_CD = C.COMP_CD
                WHERE B.USE_YN = 'Y'
                ORDER BY B.BCOFF_NM";
        
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }
    
    /**
     * 모바일 회원 등록
     * @param array $data
     * @return bool
     */
    public function insertMemberMobile(array $data)
    {
        try {
            // mem_main_info_tbl에 삽입
            $insertData = [
                'MEM_SNO' => $data['mem_sno'],
                'MEM_ID' => $data['mem_id'],
                'MEM_PWD' => $data['mem_pass'],
                'MEM_NM' => $data['mem_nm'],
                'MEM_TELNO' => $data['mem_telno'],
                'MEM_GENDR' => $data['mem_gendr'],
                'BTHDAY' => $data['bthday'],
                'MEM_ADDR' => $data['mem_addr'],
                'MEM_DV' => $data['mem_dv'],
                'MEM_STAT' => $data['mem_stat'],
                'USE_YN' => $data['use_yn'],
                'CHECKIN_NO' => $data['checkin_no'],
                'SET_COMP_CD' => $data['set_comp_cd'],
                'SET_BCOFF_CD' => $data['set_bcoff_cd'],
                'CRE_ID' => $data['cre_id'],
                'CRE_DATETM' => $data['cre_datetm'],
                'MOD_ID' => $data['mod_id'],
                'MOD_DATETM' => $data['mod_datetm']
            ];
            
            // 사진이 있으면 추가
            if (isset($data['mem_photo'])) {
                $insertData['MEM_PHOTO'] = $data['mem_photo'];
            }
            
            // 얼굴 인식 데이터가 있으면 추가
            if (isset($data['face_encoding'])) {
                $insertData['FACE_ENCODING'] = $data['face_encoding'];
            }
            if (isset($data['glasses_detected'])) {
                $insertData['GLASSES_DETECTED'] = $data['glasses_detected'];
            }
            
            $this->db->table('mem_main_info_tbl')->insert($insertData);
            
            return true;
            
        } catch (\Exception $e) {
            log_message('error', '[insertMemberMobile] 회원 등록 오류: ' . $e->getMessage());
            log_message('error', '[insertMemberMobile] SQL 오류 코드: ' . $e->getCode());
            log_message('error', '[insertMemberMobile] 오류 위치: ' . $e->getFile() . ':' . $e->getLine());
            log_message('error', '[insertMemberMobile] 입력 데이터: ' . json_encode($insertData));
            throw $e; // 오류를 상위로 전파하여 자세한 정보를 볼 수 있도록 함
        }
    }
    
}
