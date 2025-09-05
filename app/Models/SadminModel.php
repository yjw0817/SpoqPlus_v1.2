<?php
namespace App\Models;

use CodeIgniter\Model;

class SadminModel extends Model
{
	/**
	 * 어드민 로그인 체크
	 * @param array $data
	 * @return array
	 */
    public function admin_login_check(array $data)
    {
        $sql = "SELECT * FROM smgmt_mgmt_tbl
                WHERE SMGMT_ID 	= :adm_id:
                AND SMGMT_PWD 	= :adm_pwd:
                ";
        $query = $this->db->query($sql, [
            'adm_id'			=> $data['adm_id']
            ,'adm_pwd'			=> $data['adm_pwd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 대분류 사용 설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 사용가능한 대분류 리스트
     * @param array $data
     */
    public function get_1rd_list(array $data)
    {
    	$sql = "SELECT * FROM 1rd_event_cate_main_tbl";
    	$query = $this->db->query($sql, [
    			
    	]);
    	
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 대분류 사용 설정 리스트
     * @return array
     */
    public function use_1rd_list(array $data)
    {
    	$sql = "SELECT a.*, 
               (SELECT COUNT(*) 
                FROM sell_event_mgmt_tbl 
                WHERE COMP_CD = a.COMP_CD 
                  AND 1RD_CATE_CD = a.1RD_CATE_CD
               ) AS counter
        FROM 1rd_event_cate_tbl a
        WHERE a.COMP_CD = :comp_cd:";

		$query = $this->db->query($sql, [
			'comp_cd' => $data['comp_cd']
		]);

    	
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 대분류 사용안함에 대해서 이미 상품중에 사용한 것이 있는지를 체크 한다.
     * 만약에 상품중에 이미 대분류를 사용 하고 있다면 사용안함으로 설정이 불가능하다.
     */
    public function use_1rd_sell_check_count(array $data)
    {
    	$sql = "SELECT COUNT(*) AS counter 
				FROM sell_event_mgmt_tbl
				WHERE COMP_CD = :comp_cd: 
				AND 1RD_CATE_CD = :1rd_cate_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'		=> $data['comp_cd']
    			,'1rd_cate_cd'	=> $data['1rd_cate_cd']
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    /**
     * 대분류를 insert 할지 update 할지를 체크 한다.
     */
    public function use_1rd_check_count(array $data)
    {
    	$sql = "SELECT COUNT(*) AS counter
				FROM 1rd_event_cate_tbl
				WHERE COMP_CD = :comp_cd:
				AND 1RD_CATE_CD = :1rd_cate_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'		=> $data['comp_cd']
    			,'1rd_cate_cd'	=> $data['1rd_cate_cd']
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    /**
     * 대분류를 insert 하기 전에 정보를 가져온다.
     */
    public function get_use_1rd_info(array $data)
    {
    	$sql = "SELECT * FROM 1rd_event_cate_main_tbl
				WHERE 1RD_CATE_CD = :1rd_cate_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'1rd_cate_cd'	=> $data['1rd_cate_cd']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    
    /**
     * 대분류 사용 여부를 insert 한다.
     */
    public function insert_use_1rd_change(array $data)
    {
    	$sql = "INSERT 1rd_event_cate_tbl SET
					1RD_CATE_CD 		= :1rd_cate_cd:
					,COMP_CD			= :comp_cd:
					,CATE_NM			= :cate_nm:
					,GRP_CATE_SET		= :grp_cate_set:
					,LOCKR_SET			= :lockr_set:
					,USE_YN				= :use_yn:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
    			
				";
    	$query = $this->db->query($sql, [
    			'1rd_cate_cd' 			=> $data['1rd_cate_cd']
    			,'comp_cd'				=> $data['comp_cd']
    			,'cate_nm'				=> $data['cate_nm']
    			,'grp_cate_set'			=> $data['grp_cate_set']
    			,'lockr_set'			=> $data['lockr_set']
    			,'use_yn'				=> $data['use_yn']
    			,'cre_id'				=> $data['cre_id']
    			,'cre_datetm'			=> $data['cre_datetm']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 대분류 사용 여부를 update 한다.
     * @param array $data
     */
    public function update_use_1rd_change(array $data)
    {
    	$sql = "UPDATE 1rd_event_cate_tbl SET
					USE_YN				= :use_yn:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
				WHERE COMP_CD			= :comp_cd:
				AND 1RD_CATE_CD			= :1rd_cate_cd:
    			
				";
    	$query = $this->db->query($sql, [
    			'1rd_cate_cd' 			=> $data['1rd_cate_cd']
    			,'comp_cd'				=> $data['comp_cd']
    			,'use_yn'				=> $data['use_yn']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 중뷴류 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 대분류 코드 선택을 위하여 코드와 이름을 가져온다.
     * $data[type] = list 일 경우에는 모두를 가져온다.
     * $data[type] = get 일 경우에는 해당 대분류에 대한 정보를 가져온다.
     */
    public function get_1rd_cate_cd(array $data)
    {
    	if ($data['type'] == 'list'):
	    	$sql = "SELECT * FROM 1rd_event_cate_tbl
					WHERE COMP_CD = :comp_cd:
					AND USE_YN = 'Y'
					";
	    	
	    	$query = $this->db->query($sql, [
	    			'comp_cd'				=> $_SESSION['comp_cd']
	    	]);
    	
    	else :
	    	$sql = "SELECT * FROM 1rd_event_cate_tbl
						WHERE COMP_CD = :comp_cd:
						AND 1RD_CATE_CD = :1rd_cate_cd:
						";
	    	
	    	$query = $this->db->query($sql, [
	    			'comp_cd'				=> $_SESSION['comp_cd']
	    			,'1rd_cate_cd'			=> $data['1rd_cate_cd']
	    	]);
    	endif ;
    	
    	array_push($data,$query);
    	return $query->getResultArray();
    	
    }
    
    /**
     * 지점신청 관리자아이디 [ List Count ] 관리자아이디 중복 체크
     * @param array $data
     * @return array
     */
    public function dup_bcoff_appct_mgmt_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM bcoff_appct_mgmt_tbl
                WHERE BCOFF_MGMT_ID = :bcoff_mgmt_id:
                AND BCOFF_APPCT_STAT = '00'
				";
        
        $query = $this->db->query($sql, [
            'bcoff_mgmt_id'				=> $data['bcoff_mgmt_id']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 지점신청 관리자아이디 [ List Count ] 관리자아이디 중복 체크
     * @param array $data
     * @return array
     */
    public function dup_bcoff_appct_mgmt2_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM mem_main_info_tbl
                WHERE MEM_ID = :mem_id:
				";
        
        $query = $this->db->query($sql, [
            'mem_id'				=> $data['bcoff_mgmt_id']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    
    
    /**
     * 중분류 [ List Count ] 중분류 코드 중복 체크
     * @param array $data
     * @return array
     */
    public function dup_two_cate_main_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM 2rd_event_cate_main_tbl
                WHERE 2RD_CATE_CD = :2rd_cate_cd:
				";
        
        $query = $this->db->query($sql, [
            '2rd_cate_cd'				=> $data['2rd_cate_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 중분류 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_two_cate_main_count(array $data)
    {
    	$sql = "SELECT COUNT(*) as counter 
				FROM 2rd_event_cate_main_tbl
				WHERE COMP_CD = :comp_cd:
				";
    	
    	$bind = [
			'comp_cd' => $_SESSION['comp_cd']
		];
    	
    	// 추가 조건: 1rd_cate_cd가 있을 경우
		if (!empty($data['1rd_cate_cd'])) {
			$sql .= " AND 1rd_cate_cd = :1rd_cate_cd:";
			$bind['1rd_cate_cd'] = $data['1rd_cate_cd'];
		}

		// 추가 조건: search_txt가 있을 경우
		if (!empty($data['search_txt'])) {
			$sql .= " AND CATE_NM LIKE :search_txt:";
			$bind['search_txt'] = '%' . $data['search_txt'] . '%'; // 양쪽 % 와일드카드 처리
		}

		$query = $this->db->query($sql, $bind);
		$count = $query->getResultArray();

		return $count[0]['counter'];
    }
    
    /**
	 * 중분류 [ List Board ]
	 * @param array $data
	 * @return array
	 */
	public function list_two_cate_main(array $data)
	{
		$sql = "SELECT * FROM 2rd_event_cate_main_tbl
				WHERE COMP_CD = :comp_cd:";

		$bind = [
			'comp_cd' => $_SESSION['comp_cd']
		];

		// 1rd_cate_cd가 있을 경우 추가
		if (!empty($data['1rd_cate_cd']) && $data['1rd_cate_cd'] != "전체" ) {
			$sql .= " AND 1RD_CATE_CD = :1rd_cate_cd:";
			$bind['1rd_cate_cd'] = $data['1rd_cate_cd'];
		}

		// 추가 조건: search_txt가 있을 경우
		if (!empty($data['search_txt'])) {
			$sql .= " AND CATE_NM LIKE :search_txt:";
			$bind['search_txt'] = '%' . $data['search_txt'] . '%'; // 양쪽 % 와일드카드 처리
		}

		$sql .= " ORDER BY 1RD_CATE_CD ASC, 2RD_CATE_CD ASC";

		// limit이 있으면 추가
		if (isset($data['limit_s']) && isset($data['limit_e'])) {
			$sql .= " LIMIT {$data['limit_s']}, {$data['limit_e']}";
		}

		$query = $this->db->query($sql, $bind);

		return $query->getResultArray();
	}

    
    
	/**
	 * 중분류 [ Insert ]
	 * @param array $data
	 * @return array
	 */    
    public function insert_two_cate(array $data)
    {
    	$sql = "INSERT 2rd_event_cate_main_tbl SET
					COMP_CD				= :comp_cd:
					,1RD_CATE_CD 		= :1rd_cate_cd:
					,2RD_CATE_CD		= :2rd_cate_cd:
					,CATE_NM			= :cate_nm:
					,GRP_CATE_SET		= :grp_cate_set:
                    ,CLAS_DV            = :clas_dv:
					-- ,PLAN_TYPE			= :plan_type:
					,LOCKR_SET			= :lockr_set:
					,LOCKR_KND			= :lockr_knd:
					,LOCKR_GENDR_SET	= :lockr_gendr_set:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
					
				";
    	$query = $this->db->query($sql, [
    			'comp_cd'				=> $_SESSION['comp_cd']
    			,'1rd_cate_cd' 			=> $data['1rd_cate_cd']
    			,'2rd_cate_cd' 			=> $data['2rd_cate_cd']
    			,'cate_nm'				=> $data['cate_nm']
    	        ,'clas_dv'				=> $data['clas_dv']
    			,'grp_cate_set'			=> $data['grp_cate_set']
				// ,'plan_type'			=> $data['plan_type']
    			,'lockr_set'			=> $data['lockr_set']
    			,'lockr_knd'			=> $data['lockr_knd']
    			,'lockr_gendr_set'		=> $data['lockr_gendr_set']
    			,'cre_id'				=> $data['cre_id']
    			,'cre_datetm'			=> $data['cre_datetm']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 지점 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 지점 신청 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 지점 신청 관리 [ List Count ]
     * @param array $data
     * @return array
     */
    public function bc_appct_count(array $data)
    {
    	$sql = "SELECT COUNT(*) as counter FROM bcoff_appct_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'				=> $_SESSION['comp_cd']
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    /**
     * 지점 신청 관리 [ List Board ]
     * @param array $data
     * @return array
     */
    public function list_bc_appct(array $data)
    {
    	$sql = "SELECT B.* , C.COMP_NM FROM bcoff_appct_mgmt_tbl AS B
				LEFT OUTER JOIN smgmt_mgmt_tbl AS C
				ON B.COMP_CD = C.COMP_CD
				WHERE B.COMP_CD = :comp_cd:
				limit {$data['limit_s']} , {$data['limit_e']}
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'				=> $_SESSION['comp_cd']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    public function insert_bc_appct(array $data)
    {
    	$sql = "INSERT bcoff_appct_mgmt_tbl SET
					COMP_CD 			= :comp_cd:
					,BCOFF_CD			= :bcoff_cd:
					,BCOFF_MGMT_ID		= :bcoff_mgmt_id:
					,BCOFF_MGMT_PWD		= :bcoff_mgmt_pwd:
					,MNGR_NM			= :mngr_nm:
					,MNGR_TELNO			= :mngr_telno:
					,CEO_NM				= :ceo_nm:
					,CEO_TELNO			= :ceo_telno:
					,BCOFF_NM			= :bcoff_nm:
					,BCOFF_ADDR			= :bcoff_addr:
					,BCOFF_TELNO		= :bcoff_telno:
					,BCOFF_TELNO2		= :bcoff_telno2:
					,BCOFF_MEMO			= :bcoff_memo:
					,BCOFF_APPCT_STAT	= :bcoff_appct_stat:
					,BCOFF_APPCT_DATETM	= :bcoff_appct_datetm:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
    			
				";
    	$query = $this->db->query($sql, [
    			'comp_cd'				=> $_SESSION['comp_cd']
    			,'bcoff_cd' 			=> $data['bcoff_cd']
    			,'bcoff_mgmt_id' 		=> $data['bcoff_mgmt_id']
    			,'bcoff_mgmt_pwd'		=> $data['bcoff_mgmt_pwd']
    			,'mngr_nm'				=> $data['mngr_nm']
    			,'mngr_telno'			=> $data['mngr_telno']
    			,'ceo_nm'				=> $data['ceo_nm']
    			,'ceo_telno'			=> $data['ceo_telno']
    			,'bcoff_nm'				=> $data['bcoff_nm']
    			,'bcoff_addr'			=> $data['bcoff_addr']
    			,'bcoff_telno'			=> $data['bcoff_telno']
    			,'bcoff_telno2'			=> $data['bcoff_telno2']
    			,'bcoff_memo'			=> $data['bcoff_memo']
    			,'bcoff_appct_stat'		=> $data['bcoff_appct_stat']
    			,'bcoff_appct_datetm'	=> $data['bcoff_appct_datetm']
    			,'cre_id'				=> $data['cre_id']
    			,'cre_datetm'			=> $data['cre_datetm']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 특정 지점 신청 건의 상세 정보 조회
     */
    public function get_bc_appct_detail($request_sno)
    {
    	$sql = "SELECT B.* , C.COMP_NM FROM bcoff_appct_mgmt_tbl AS B
				LEFT OUTER JOIN smgmt_mgmt_tbl AS C
				ON B.COMP_CD = C.COMP_CD
				WHERE B.COMP_CD = :comp_cd:
				AND B.BCOFF_APPCT_MGMT_SNO = :request_sno:
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'		=> $_SESSION['comp_cd'],
    			'request_sno'	=> $request_sno
    	]);
    	
    	return $query->getResultArray();
    }
    
    
    
    
    
}