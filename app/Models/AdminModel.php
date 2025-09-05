<?php
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
	/**
	 * 어드민 로그인 체크
	 * @param array $data
	 * @return array
	 */
    public function admin_login_check(array $data)
    {
        $sql = "SELECT * FROM adm_mgmt_tbl
                WHERE ADM_ID = :adm_id:
                AND ADM_PWD = :adm_pwd:
                ";
        $query = $this->db->query($sql, [
            'adm_id'			=> $data['adm_id']
            ,'adm_pwd'			=> $data['adm_pwd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 대분류 [ List Count ] 중복체크용도
     * @param array $data
     * @return array
     */
    public function dup_one_cate_main_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM 1rd_event_cate_main_tbl
                WHERE 1RD_CATE_CD = :1rd_cate_cd:
				";
        
        $query = $this->db->query($sql, [
            '1rd_cate_cd' 			=> $data['1rd_cate_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 대분류 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_one_cate_main_count(array $data)
    {
    	$sql = "SELECT COUNT(*) as counter FROM 1rd_event_cate_main_tbl
				";
    	
    	$query = $this->db->query($sql, [
    			
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    /**
     * 대분류 [ List Board ]
     * @param array $data
     * @return array
     */
    public function list_one_cate_main(array $data)
    {
    	$sql = "SELECT * FROM 1rd_event_cate_main_tbl
				limit {$data['limit_s']} , {$data['limit_e']}
				";
    	
    	$query = $this->db->query($sql, [
    		
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    
	/**
	 * 대분류 [ Insert ]
	 * @param array $data
	 * @return array
	 */    
    public function insert_onc_cate(array $data)
    {
    	$sql = "INSERT 1rd_event_cate_main_tbl SET
					1RD_CATE_CD 		= :1rd_cate_cd:
					,CATE_NM			= :cate_nm:
					,GRP_CATE_SET		= :grp_cate_set:
					,LOCKR_SET			= :lockr_set:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
					
				";
    	$query = $this->db->query($sql, [
    			'1rd_cate_cd' 			=> $data['1rd_cate_cd']
    			,'cate_nm'				=> $data['cate_nm']
    			,'grp_cate_set'			=> $data['grp_cate_set']
    			,'lockr_set'			=> $data['lockr_set']
    			,'cre_id'				=> $data['cre_id']
    			,'cre_datetm'			=> $data['cre_datetm']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 슈퍼관리자 [ List Count ] 중복체크 용도
     * @param array $data
     * @return array
     */
    public function dup_sadmin_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM smgmt_mgmt_tbl
                WHERE SMGMT_ID = :smgmt_id:
				";
        
        $query = $this->db->query($sql, [
            'smgmt_id' 			=> $data['smgmt_id']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 슈퍼관리자 [ List Count ]
     * @param array $data
     * @return array
     */
    public function list_sadmin_count(array $data)
    {
    	$sql = "SELECT COUNT(*) as counter FROM smgmt_mgmt_tbl
				";
    	
    	$query = $this->db->query($sql, [
    			
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    /**
     * 슈퍼관리자 [ List Board ]
     * @param array $data
     * @return array
     */
    public function list_sadmin(array $data)
    {
    	$sql = "SELECT * FROM smgmt_mgmt_tbl
				limit {$data['limit_s']} , {$data['limit_e']}
				";
    	
    	$query = $this->db->query($sql, [
    			
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 슈퍼관리자 [ get ]
     * @param array $data
     * @return array
     */
    public function get_sadmin_info(array $data)
    {
        $sql = "SELECT * FROM smgmt_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
                AND SMGMT_ID = :smgmt_id:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'smgmt_id'			=> $data['smgmt_id']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 슈퍼관리자 [ Insert ]
     * @param array $data
     */
    public function insert_sadmin(array $data)
    {
    	$sql = "INSERT smgmt_mgmt_tbl SET
					COMP_CD 		= :comp_cd:
					,SMGMT_ID		= :smgmt_id:
					,SMGMT_PWD		= :smgmt_pwd:
					,MNGR_NM		= :mngr_nm:
					,MNGR_TELNO		= :mngr_telno:
					,COMP_NM		= :comp_nm:
					,CEO_NM			= :ceo_nm:
					,CEO_TELNO		= :ceo_telno:
					,COMP_ADDR		= :comp_addr:
					,COMP_TELNO		= :comp_telno:
					,COMP_MEMO		= :comp_memo:
					,COMP_TELNO2	= :comp_telno2:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
    	$query = $this->db->query($sql, [
    			'comp_cd' 			=> $data['comp_cd']
    			,'smgmt_id'			=> $data['smgmt_id']
    			,'smgmt_pwd'		=> $data['smgmt_pwd']
    			,'mngr_nm'			=> $data['mngr_nm']
    			,'mngr_telno'		=> $data['mngr_telno']
    			,'comp_nm'			=> $data['comp_nm']
    			,'ceo_nm'			=> $data['ceo_nm']
    			,'ceo_telno'		=> $data['ceo_telno']
    			,'comp_addr'		=> $data['comp_addr']
    			,'comp_telno'		=> $data['comp_telno']
    			,'comp_memo'		=> $data['comp_memo']
    			,'comp_telno2'		=> $data['comp_telno2']
    			,'cre_id'			=> $data['cre_id']
    			,'cre_datetm'		=> $data['cre_datetm']
    			,'mod_id'			=> $data['mod_id']
    			,'mod_datetm'		=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 슈퍼관리자 [ Update ]
     * @param array $data
     */
    public function update_sadmin(array $data)
    {
        $sql = "UPDATE smgmt_mgmt_tbl SET
					MNGR_NM		= :mngr_nm:
					,MNGR_TELNO		= :mngr_telno:
					,COMP_NM		= :comp_nm:
					,CEO_NM			= :ceo_nm:
					,CEO_TELNO		= :ceo_telno:
					,COMP_ADDR		= :comp_addr:
					,COMP_TELNO		= :comp_telno:
					,COMP_MEMO		= :comp_memo:
					,COMP_TELNO2	= :comp_telno2:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE COMP_CD = :comp_cd:
                AND SMGMT_ID = :smgmt_id:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'smgmt_id'			=> $data['smgmt_id']
            ,'mngr_nm'			=> $data['mngr_nm']
            ,'mngr_telno'		=> $data['mngr_telno']
            ,'comp_nm'			=> $data['comp_nm']
            ,'ceo_nm'			=> $data['ceo_nm']
            ,'ceo_telno'		=> $data['ceo_telno']
            ,'comp_addr'		=> $data['comp_addr']
            ,'comp_telno'		=> $data['comp_telno']
            ,'comp_memo'		=> $data['comp_memo']
            ,'comp_telno2'		=> $data['comp_telno2']
            ,'mod_id'			=> $data['mod_id']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 슈퍼관리자 비밀번호 [ Update ]
     * @param array $data
     */
    public function update_sadmin_passwd(array $data)
    {
        $sql = "UPDATE smgmt_mgmt_tbl SET
					SMGMT_PWD		= :smgmt_pwd:
                WHERE COMP_CD = :comp_cd:
                AND SMGMT_ID = :smgmt_id:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'smgmt_id'			=> $data['smgmt_id']
            ,'smgmt_pwd'			=> $data['smgmt_pwd']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
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
				";
    	
    	$query = $this->db->query($sql, [
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
				limit {$data['limit_s']} , {$data['limit_e']}
				";
    	
    	$query = $this->db->query($sql, [
    	]);
    	
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 지점 신청관리 정보를 가져온다. ( bcoff_appct_mgmt_sno )
     * @param array $data
     */
    public function get_bcoff_appct_mgmt(array $data)
    {
    	
    			
    	$sql = "SELECT * FROM bcoff_appct_mgmt_tbl
    			WHERE BCOFF_APPCT_MGMT_SNO = :bcoff_appct_mgmt_sno:
                ";
    	$query = $this->db->query($sql, [
    			'bcoff_appct_mgmt_sno'	=> $data['bcoff_appct_mgmt_sno']
    	]);
    	
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 지점 관리 insert
     * @param array $data
     */
	public function insert_bcoff_mgmt(array $data)
	{
		$sql = "INSERT bcoff_mgmt_tbl SET
					COMP_CD 		= :comp_cd:
					,BCOFF_CD		= :bcoff_cd:
					,BCOFF_MGMT_ID	= :bcoff_mgmt_id:
					,MNGR_NM		= :mngr_nm:
					,MCOFF_MGMT_PWD	= :mcoff_mgmt_pwd:
					,MNGR_TELNO		= :mngr_telno:
					,CEO_NM			= :ceo_nm:
					,CEO_TELNO		= :ceo_telno:
					,BCOFF_NM		= :bcoff_nm:
					,BCOFF_ADDR		= :bcoff_addr:
					,BCOFF_TELNO	= :bcoff_telno:
					,BCOFF_TELNO2	= :bcoff_telno2:
					,BCOFF_MEMO		= :bcoff_memo:
					,AFFI_SET		= :affi_set:
					,AFFI_AMT		= :affi_amt:
					,CRE_ID			= :cre_id:
					,CRE_DATETM		= :cre_datetm:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
				";
		$query = $this->db->query($sql, [
				'comp_cd' 			=> $data['comp_cd']
				,'bcoff_cd'			=> $data['bcoff_cd']
				,'bcoff_mgmt_id'	=> $data['bcoff_mgmt_id']
				,'mngr_nm'			=> $data['mngr_nm']
				,'mcoff_mgmt_pwd'	=> $data['mcoff_mgmt_pwd']
				,'mngr_telno'		=> $data['mngr_telno']
				,'ceo_nm'			=> $data['ceo_nm']
				,'ceo_telno'		=> $data['ceo_telno']
				,'bcoff_nm'			=> $data['bcoff_nm']
				,'bcoff_addr'		=> $data['bcoff_addr']
				,'bcoff_telno'		=> $data['bcoff_telno']
				,'bcoff_telno2'		=> $data['bcoff_telno2']
				,'bcoff_memo'		=> $data['bcoff_memo']
				,'affi_set'			=> $data['affi_set']
				,'affi_amt'			=> $data['affi_amt']
				,'cre_id'			=> $data['cre_id']
				,'cre_datetm'		=> $data['cre_datetm']
				,'mod_id'			=> $data['mod_id']
				,'mod_datetm'		=> $data['mod_datetm']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	/**
	 * 지점 신청관리 update
	 * @param array $data
	 */
	public function update_bcoff_appct_mgmt(array $data)
	{
		$sql = "UPDATE bcoff_appct_mgmt_tbl SET
					BCOFF_APPCT_STAT 		= :bcoff_appct_stat:
					,BCOFF_CD 				= :bcoff_cd:
					,BCOFF_APPV_DATETM 		= :bcoff_appv_datetm:
					,BCOFF_REFUS_DATETM 	= :bcoff_refus_datetm:
					,MOD_ID 				= :mod_id:
					,MOD_DATETM 			= :mod_datetm:
				WHERE BCOFF_APPCT_MGMT_SNO 	= :bcoff_appct_mgmt_sno:
				";
		$query = $this->db->query($sql, [
				'bcoff_appct_stat' 			=> $data['bcoff_appct_stat']
				,'bcoff_cd'					=> $data['bcoff_cd']
				,'bcoff_appv_datetm'		=> $data['bcoff_appv_datetm']
				,'bcoff_refus_datetm'	    => $data['bcoff_refus_datetm']
				,'mod_id'					=> $data['mod_id']
				,'mod_datetm'				=> $data['mod_datetm']
				,'bcoff_appct_mgmt_sno'		=> $data['bcoff_appct_mgmt_sno']
		]);
		
		array_push($data,$query);
		return $data;
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
	 * 
	 * @param array $data
	 * @return array
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
	
	
	//////////////////////// admin 지점관리자 update
	
	/**
	 * Admin 지점관리 신청 [ Update ]
	 * @param array $data
	 */
	public function update_admin_bcoff_appct_mgmt(array $data)
	{
	    $sql = "UPDATE bcoff_appct_mgmt_tbl SET
					MNGR_NM		    = :mngr_nm:
					,MNGR_TELNO		= :mngr_telno:
					,CEO_NM			= :ceo_nm:
					,CEO_TELNO		= :ceo_telno:
					,BCOFF_NM		= :bcoff_nm:
					,BCOFF_ADDR		= :bcoff_addr:
					,BCOFF_TELNO	= :bcoff_telno:
					,BCOFF_TELNO2	= :bcoff_telno2:
                    ,BCOFF_MEMO	    = :bcoff_memo:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE BCOFF_APPCT_MGMT_SNO = :bcoff_appct_mgmt_sno:
				";
	    $query = $this->db->query($sql, [
	        'bcoff_appct_mgmt_sno' 	  => $data['bcoff_appct_mgmt_sno']
	        ,'mngr_nm'			      => $data['mngr_nm']
	        ,'mngr_telno'		      => $data['mngr_telno']
	        ,'ceo_nm'			      => $data['ceo_nm']
	        ,'ceo_telno'		      => $data['ceo_telno']
	        ,'bcoff_nm'		          => $data['bcoff_nm']
	        ,'bcoff_addr'		      => $data['bcoff_addr']
	        ,'bcoff_telno'		      => $data['bcoff_telno']
	        ,'bcoff_telno2'		      => $data['bcoff_telno2']
	        ,'bcoff_memo'		      => $data['bcoff_memo']
	        ,'mod_id'			      => $data['mod_id']
	        ,'mod_datetm'		      => $data['mod_datetm']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * Admin 지점관리 신청 [ Update ]
	 * @param array $data
	 */
	public function update_admin_bcoff_appct_mgmt_passwd(array $data)
	{
	    $sql = "UPDATE bcoff_appct_mgmt_tbl SET
					BCOFF_MGMT_PWD		    = :bcoff_mgmt_pwd:
                WHERE BCOFF_APPCT_MGMT_SNO  = :bcoff_appct_mgmt_sno:
				";
	    $query = $this->db->query($sql, [
	        'bcoff_appct_mgmt_sno' 	  => $data['bcoff_appct_mgmt_sno']
	        ,'bcoff_mgmt_pwd'		  => $data['bcoff_mgmt_pwd']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * Admin 지점관리 [ Update ]
	 * @param array $data
	 */
	public function update_admin_bcoff_mgmt(array $data)
	{
	    $sql = "UPDATE bcoff_mgmt_tbl SET
					MNGR_NM		    = :mngr_nm:
					,MNGR_TELNO		= :mngr_telno:
					,CEO_NM			= :ceo_nm:
					,CEO_TELNO		= :ceo_telno:
					,BCOFF_NM		= :bcoff_nm:
					,BCOFF_ADDR		= :bcoff_addr:
					,BCOFF_TELNO	= :bcoff_telno:
					,BCOFF_TELNO2	= :bcoff_telno2:
                    ,BCOFF_MEMO	    = :bcoff_memo:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE BCOFF_MGMT_ID = :bcoff_mgmt_id:
				";
	    $query = $this->db->query($sql, [
	        'bcoff_mgmt_id' 	      => $data['bcoff_mgmt_id']
	        ,'mngr_nm'			      => $data['mngr_nm']
	        ,'mngr_telno'		      => $data['mngr_telno']
	        ,'ceo_nm'			      => $data['ceo_nm']
	        ,'ceo_telno'		      => $data['ceo_telno']
	        ,'bcoff_nm'		          => $data['bcoff_nm']
	        ,'bcoff_addr'		      => $data['bcoff_addr']
	        ,'bcoff_telno'		      => $data['bcoff_telno']
	        ,'bcoff_telno2'		      => $data['bcoff_telno2']
	        ,'bcoff_memo'		      => $data['bcoff_memo']
	        ,'mod_id'			      => $data['mod_id']
	        ,'mod_datetm'		      => $data['mod_datetm']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * Admin 지점관리 [ Update ]
	 * @param array $data
	 */
	public function update_admin_bcoff_mgmt_passwd(array $data)
	{
	    $sql = "UPDATE bcoff_mgmt_tbl SET
					MCOFF_MGMT_PWD		    = :mcoff_mgmt_pwd:
                WHERE BCOFF_MGMT_ID = :bcoff_mgmt_id:
				";
	    $query = $this->db->query($sql, [
	        'bcoff_mgmt_id' 	      => $data['bcoff_mgmt_id']
	        ,'mcoff_mgmt_pwd'		  => $data['mcoff_mgmt_pwd']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * Admin 회원정보 [ Update ]
	 * @param array $data
	 */
	public function update_admin_mem_main_info(array $data)
	{
	    $sql = "UPDATE mem_main_info_tbl SET
					MEM_NM		    = :mem_nm:
					,MEM_TELNO		= :mem_telno:
                    ,MEM_TELNO_ENC  = :mem_telno_enc:
                    ,MEM_TELNO_MASK = :mem_telno_mask:
                    ,MEM_TELNO_SHORT = :mem_telno_short:
                    ,MEM_TELNO_ENC2  = :mem_telno_enc2:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE MEM_ID = :mem_id:
				";
	    $query = $this->db->query($sql, [
	        'mem_id' 	              => $data['mem_id']
	        ,'mem_nm'			      => $data['mem_nm']
	        ,'mem_telno'		      => $data['mem_telno']
	        ,'mem_telno_enc'		=> $data['mem_telno_enc']
	        ,'mem_telno_mask'		=> $data['mem_telno_mask']
	        ,'mem_telno_short'		=> $data['mem_telno_short']
	        ,'mem_telno_enc2'		=> $data['mem_telno_enc2']
	        ,'mod_id'			      => $data['mod_id']
	        ,'mod_datetm'		      => $data['mod_datetm']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * Admin 회원정보 [ Update ]
	 * @param array $data
	 */
	public function update_admin_mem_main_info_passwd(array $data)
	{
	    $sql = "UPDATE mem_main_info_tbl SET
					MEM_PWD		    = :mem_pwd:
                WHERE MEM_ID = :mem_id:
				";
	    $query = $this->db->query($sql, [
	        'mem_id' 	              => $data['mem_id']
	        ,'mem_pwd'			      => $data['mem_pwd']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
	/**
	 * Admin 회원정보상세 [ Update ]
	 * @param array $data
	 */
	public function update_admin_mem_info_detl(array $data)
	{
	    $sql = "UPDATE mem_info_detl_tbl SET
					MEM_NM		    = :mem_nm:
					,MEM_TELNO		= :mem_telno:
                    ,MEM_TELNO_ENC  = :mem_telno_enc:
                    ,MEM_TELNO_MASK = :mem_telno_mask:
                    ,MEM_TELNO_SHORT = :mem_telno_short:
                    ,MEM_TELNO_ENC2  = :mem_telno_enc2:
					,MOD_ID			= :mod_id:
					,MOD_DATETM		= :mod_datetm:
                WHERE MEM_ID = :mem_id:
				";
	    $query = $this->db->query($sql, [
	        'mem_id' 	              => $data['mem_id']
	        ,'mem_nm'			      => $data['mem_nm']
	        ,'mem_telno'		      => $data['mem_telno']
	        ,'mem_telno_enc'		=> $data['mem_telno_enc']
	        ,'mem_telno_mask'		=> $data['mem_telno_mask']
	        ,'mem_telno_short'		=> $data['mem_telno_short']
	        ,'mem_telno_enc2'		=> $data['mem_telno_enc2']
	        ,'mod_id'			      => $data['mod_id']
	        ,'mod_datetm'		      => $data['mod_datetm']
	    ]);
	    
	    array_push($data,$query);
	    return $data;
	}
	
    
    
}