<?php
namespace App\Models;

use CodeIgniter\Model;

class CronModel extends Model
{
	protected $table = 'tb_cron_hist';
	protected $primaryKey = 'cron_hist_sno';
	protected $allowedFields = ['cron_knd', 'cron_cre_datetm'];
	
	public function test_attd_chk(array $data)
	{
	    $sql = "SELECT COUNT(*) AS counter FROM attd_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND ATTD_YMD = :attd_ymd:
				";
	    
	    $query = $this->db->query($sql, [
	        'comp_cd' 	    => $data['comp_cd']
	        ,'bcoff_cd' 	=> $data['bcoff_cd']
	        ,'attd_ymd'     => $data['attd_ymd']
	    ]);
	    array_push($data,$query);
	    return $query->getResultArray();
	}
    
    
    
    
    public function cron_list_smgmt(array $data)
    {
        $sql = "SELECT * FROM smgmt_mgmt_tbl
				";
        
        $query = $this->db->query($sql, [
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function cron_pwd_enc_smgmt_update(array $data)
    {
        $sql = "UPDATE smgmt_mgmt_tbl SET
                    SMGMT_PWD = :smgmt_pwd:
                WHERE SMGMT_ID = :smgmt_id:
				";
        
        $query = $this->db->query($sql, [
            'smgmt_id' 	=> $data['smgmt_id']
            ,'smgmt_pwd' 	=> $data['smgmt_pwd']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    
    
    public function cron_list_mem_main_info(array $data)
    {
        $sql = "SELECT * FROM mem_main_info_tbl
				";
        
        $query = $this->db->query($sql, [
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function cron_pwd_enc_update(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
                    MEM_PWD = :mem_pwd:
                WHERE MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'mem_sno' 	=> $data['mem_sno']
            ,'mem_pwd' 	=> $data['mem_pwd']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    public function cron_telno_enc_update(array $data)
    {
        $sql = "UPDATE mem_main_info_tbl SET
                    MEM_TELNO_ENC = :mem_telno_enc:
                    ,MEM_TELNO_ENC2 = :mem_telno_enc2:
                    ,MEM_TELNO_MASK = :mem_telno_mask:
                    ,MEM_TELNO_SHORT = :mem_telno_short:
                WHERE MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'mem_sno' 	=> $data['mem_sno']
            ,'mem_telno_enc' 	=> $data['mem_telno_enc']
            ,'mem_telno_enc2' 	=> $data['mem_telno_enc2']
            ,'mem_telno_mask' 	=> $data['mem_telno_mask']
            ,'mem_telno_short' 	=> $data['mem_telno_short']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    /* ===========================================================================
     *                              운동 시작일 Cron 
     * ===========================================================================
     */
    
    /**
     * 운동 시작일을 검색하여 오늘 시작할 상품 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_cron_lockr_s_date(array $data)
    {
        $sql = "SELECT * FROM buy_event_mgmt_tbl
                WHERE EXR_S_DATE = :exr_s_date:
                AND EVENT_STAT = '01'
                AND LOCKR_NO != ''
				";
        
        $query = $this->db->query($sql, [
            'exr_s_date'                   => $data['exr_s_date']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 운동 시작일로 인한 이용중으로 업데이트 처리한다.
     * @param array $data
     * @return array
     */
    public function update_cron_exr_s_date(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
                    EVENT_STAT = '00'
                    , MOD_ID = 'cron'
                    , MOD_DATETM = :mod_datetm:
                WHERE EXR_S_DATE = :exr_s_date:
                AND EVENT_STAT = '01'
				";
        
        $query = $this->db->query($sql, [
            'exr_s_date' 	=> $data['exr_s_date']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    /**
     * 운동 종료일로 인한 종료됨으로 업데이트 처리한다. (이용권)
     * @param array $data
     * @return array
     */
    public function update_cron_exr_e_date(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl SET
                    EVENT_STAT = '99'
                    , EVENT_STAT_RSON = '00'
                    , MOD_ID = 'cron'
                    , MOD_DATETM = :mod_datetm:
                WHERE EXR_E_DATE = :exr_e_date:
                AND EVENT_STAT = '00'
				";
        
        $query = $this->db->query($sql, [
            'exr_e_date' 	=> $data['exr_e_date']
            ,'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    /**
     * 운동 종료일로 인한 라커룸 종료됨으로 업데이트 처리한다. (락커)
     * @param array $data
     * @return array
     */
    public function update_cron_lockr_end(array $data)
    {
        $sql = "UPDATE lockr_room SET
                    LOCKR_STAT = '99'
                WHERE LOCKR_USE_E_DATE = :lockr_use_e_date:
				";
        
        $query = $this->db->query($sql, [
            'lockr_use_e_date' 	=> $data['lockr_use_e_date']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    /**
     * 운동 종료일로 인한 라커룸 종료됨으로 업데이트 처리한다. (락커)
     * @param array $data
     * @return array
     */
    public function update_cron_lockr_start(array $data)
    {
        $sql = "UPDATE lockr_room SET
                    BUY_EVENT_SNO = :buy_event_sno:
                    , MEM_SNO = :mem_sno:
                    , MEM_NM = :mem_nm:
                    , LOCKR_USE_S_DATE = :lockr_use_s_date:
                    , LOCKR_USE_E_DATE = :lockr_use_e_date:
                    , LOCKR_STAT = '00'
                WHERE
                    COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
                    AND LOCKR_KND = :lockr_knd:
                    AND LOCKR_GENDR_SET = :lockr_gendr_set:
                    AND LOCKR_NO = :lockr_no:
                    AND MEM_SNO = :mem_sno:
				";
        
        $query = $this->db->query($sql, [
            'buy_event_sno' 	    => $data['buy_event_sno']
            ,'mem_sno' 	            => $data['mem_sno']
            ,'mem_nm' 	            => $data['mem_nm']
            ,'lockr_use_s_date'     => $data['lockr_use_s_date']
            ,'lockr_use_e_date' 	=> $data['lockr_use_e_date']
            ,'comp_cd' 	            => $data['comp_cd']
            ,'bcoff_cd' 	        => $data['bcoff_cd']
            ,'lockr_knd' 	        => $data['lockr_knd']
            ,'lockr_gendr_set' 	    => $data['lockr_gendr_set']
            ,'lockr_no' 	        => $data['lockr_no']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    /**
     * PT 횟수로 인한 종료됨으로 업데이트 처리한다. (PT)
     * @param array $data
     * @return array
     */
    public function update_cron_pt_end(array $data)
    {
        $sql = "UPDATE buy_event_mgmt_tbl AS A
                    INNER JOIN sell_event_mgmt_tbl AS B
                    ON A.SELL_EVENT_SNO = B.SELL_EVENT_SNO
                    SET
                    A.EVENT_STAT = '99',
                    A.EVENT_STAT_RSON = '00',
                    A.MOD_ID = 'cron',
                    A.MOD_DATETM = :mod_datetm:
                WHERE
                    A.MEM_REGUL_CLAS_LEFT_CNT = 0 AND A.SRVC_CLAS_LEFT_CNT = 0 AND (
                        IFNULL(A.CLAS_DV, '') IN ('21', '22') OR B.M_CATE = 'PRV'
                    ) AND A.EVENT_STAT = '00';
				";
        
        $query = $this->db->query($sql, [
            'mod_datetm'		=> $data['mod_datetm']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    /**
     * 추천상품에서 만료일 업데이트를 진행한다.
     * @param array $data
     * @return array
     */
    public function update_cron_send_end(array $data)
    {
        $sql = "UPDATE send_event_mgmt_tbl SET
                SEND_STAT = '90'
                , MOD_ID = 'cron'
                , MOD_DATETM = :mod_datetm:
                WHERE SEND_BUY_E_DATE = :send_buy_e_date:
                AND SEND_STAT = '00'
				";
        
        $query = $this->db->query($sql, [
            'mod_datetm'		=> $data['mod_datetm']
            ,'send_buy_e_date'	=> $data['send_buy_e_date']
        ]);
        
        array_push($data,$query);
        //return $data;
        return $query->getResultArray(); // $conn->affected_rows;
    }
    
    /**
     * 크론 실행 기록을 저장한다.
     */
    public function insert_cron($data)
    {
        $sql = "INSERT cron_hist SET
					CRON_KND 		    = :cron_knd:
					,CRON_CRE_DATETM    = :cron_cre_datetm:
				";
        $query = $this->db->query($sql, [
            'cron_knd' 			        => $data['cron_knd']
            ,'cron_cre_datetm' 			=> $data['cron_cre_datetm']
        ]);
        
        array_push($data,$query);
        return $data;
    }

     /**
     * 크론 실행 기록 목록을 가져온다.
     */
    public function get_cron_list()
    {
        return $this->orderBy('cron_hist_sno', 'DESC')
                   ->limit(50)
                   ->get()
                   ->getResultArray();
    }
}