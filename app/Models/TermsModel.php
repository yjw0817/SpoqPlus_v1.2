<?php
namespace App\Models;

use CodeIgniter\Model;

class TermsModel extends Model
{
    
    /**
     * 약관 회차 업데이트시 이전 약관에 대한 사용여부를 사용안함으로 업데이트 한다.
     * @param array $data
     */
    public function update_terms_manage_flag_n(array $data)
    {
        $sql = "UPDATE terms_mgmt_tbl SET TERMS_USE_YN = 'N'
                WHERE TERMS_KND_CD = :terms_knd_cd:
                AND TERMS_COMP_CD = :terms_comp_cd:
                AND TERMS_BCOFF_CD = :terms_bcoff_cd:
                AND TERMS_ROUND < :terms_round:
				";
        $query = $this->db->query($sql, [
            'terms_knd_cd' 			=> $data['terms_knd_cd']
            ,'terms_comp_cd' 		=> $data['terms_comp_cd']
            ,'terms_bcoff_cd'		=> $data['terms_bcoff_cd']
            ,'terms_round'			=> $data['terms_round']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 기본 약관을 생성 한다.
     * @param array $data
     * @return array
     */
    public function insert_terms_manage(array $data)
    {
        $sql = "INSERT terms_mgmt_tbl SET
					TERMS_KND_CD 		= :terms_knd_cd:
					,TERMS_COMP_CD 		= :terms_comp_cd:
					,TERMS_BCOFF_CD		= :terms_bcoff_cd:
					,TERMS_ROUND		= :terms_round:
					,TERMS_TITLE		= :terms_title:
					,TERMS_CONTS		= :terms_conts:
					,TERMS_USE_YN		= :terms_use_yn:
                    ,TERMS_DATE			= :terms_date:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
				";
        $query = $this->db->query($sql, [
            'terms_knd_cd' 			=> $data['terms_knd_cd']
            ,'terms_comp_cd' 		=> $data['terms_comp_cd']
            ,'terms_bcoff_cd'		=> $data['terms_bcoff_cd']
            ,'terms_round'			=> $data['terms_round']
            ,'terms_title'			=> $data['terms_title']
            ,'terms_conts'			=> $data['terms_conts']
            ,'terms_use_yn'			=> $data['terms_use_yn']
            ,'terms_date'			=> $data['terms_date']
            ,'cre_id'				=> $data['cre_id']
            ,'cre_datetm'			=> $data['cre_datetm']
            ,'mod_id'				=> $data['mod_id']
            ,'mod_datetm'			=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    
    /**
     * 기본약관 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_terms(array $data)
    {
        $sql = "SELECT * FROM terms_mgmt_tbl
                WHERE TERMS_COMP_CD = :terms_comp_cd:
                AND TERMS_BCOFF_CD = :terms_bcoff_cd:
                ORDER BY MOD_DATETM DESC
                ";
        $query = $this->db->query($sql, [
            'terms_comp_cd'			=> $data['terms_comp_cd']
            ,'terms_bcoff_cd'		=> $data['terms_bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 기본약관 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_last_terms(array $data)
    {
        $sql = "SELECT * FROM terms_mgmt_tbl
                WHERE TERMS_COMP_CD = :terms_comp_cd:
                AND TERMS_BCOFF_CD = :terms_bcoff_cd:
                AND TERMS_KND_CD = :terms_knd_cd:
                ORDER BY TERMS_ROUND DESC LIMIT 1
                ";
        $query = $this->db->query($sql, [
            'terms_comp_cd'			=> $data['terms_comp_cd']
            ,'terms_bcoff_cd'		=> $data['terms_bcoff_cd']
            ,'terms_knd_cd'		    => $data['terms_knd_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /*
     * ================== [SAMPLE] ====================
     */
    
    
    
    
    /**
     * 대분류 명을 가져온다.
     * @param array $data
     * @return array
     */
    public function disp_cate1(array $data)
    {
        $sql = "SELECT 1RD_CATE_CD, CATE_NM, USE_YN FROM 1rd_event_cate_tbl
                WHERE COMP_CD = :comp_cd:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 대분류 사용안함에 대해서 이미 상품중에 사용한 것이 있는지를 체크 한다.
     * 만약에 상품중에 이미 대분류를 사용 하고 있다면 사용안함으로 설정이 불가능하다.
     */
    public function use_2rd_sell_check_count(array $data)
    {
    	$sql = "SELECT COUNT(*) AS counter 
				FROM sell_event_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd: 
				AND 2RD_CATE_CD = :2rd_cate_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'		=> $data['comp_cd']
    			,'bcoff_cd'		=> $data['bcoff_cd']
    			,'2rd_cate_cd'	=> $data['2rd_cate_cd']
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    
    
    /**
     * 대분류 사용 여부를 insert 한다.
     */
    public function insert_use_2rd_change(array $data)
    {
    	$sql = "INSERT 2rd_event_cate_tbl SET
					1RD_CATE_CD 		= :1rd_cate_cd:
					,2RD_CATE_CD 		= :2rd_cate_cd:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD			= :bcoff_cd:
					,CATE_NM			= :cate_nm:
					,GRP_CATE_SET		= :grp_cate_set:
					,CLAS_DV		    = :clas_dv:
                    ,LOCKR_SET			= :lockr_set:
                    ,LOCKR_KND          = :lockr_knd:
					,USE_YN				= :use_yn:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
    			
				";
    	$query = $this->db->query($sql, [
    			'1rd_cate_cd' 			=> $data['1rd_cate_cd']
    			,'2rd_cate_cd' 			=> $data['2rd_cate_cd']
    			,'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'cate_nm'				=> $data['cate_nm']
    			,'grp_cate_set'			=> $data['grp_cate_set']
    	        ,'clas_dv'			    => $data['clas_dv']
    			,'lockr_set'			=> $data['lockr_set']
    	        ,'lockr_knd'			=> $data['lockr_knd']
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
    public function update_use_2rd_change(array $data)
    {
    	$sql = "UPDATE 2rd_event_cate_tbl SET
					USE_YN				= :use_yn:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
				WHERE COMP_CD			= :comp_cd:
				AND BCOFF_CD			= :bcoff_cd:
				AND 2RD_CATE_CD			= :2rd_cate_cd:
    			
				";
    	$query = $this->db->query($sql, [
    			'2rd_cate_cd' 			=> $data['2rd_cate_cd']
    			,'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'use_yn'				=> $data['use_yn']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    
}