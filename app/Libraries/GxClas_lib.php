<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\ClasModel;
use App\Models\MemModel;

class GxClas_lib {
	
    /**
     * 수업강사 아이디
     * @var string
     */
    private $stchr_id;
    
    /**
     * gx 스케쥴 일련번호
     * @var string
     */
    private $gx_schd_mgmt_sno;
    
    private $comp_cd;
    
    private $bcoff_cd;
    
    private $modelClas;
    
    private $gx_schd_info;
    
    private $set_data;
    
    private $result_info = array("result"=>true);
    
    public function __construct($setVar) {
        $this->modelClas = new ClasModel();
        $this->comp_cd = $setVar['comp_cd'];
        $this->bcoff_cd = $setVar['bcoff_cd'];
        
        if (isset($setVar['stchr_id']))
        {
            $this->stchr_id = $setVar['stchr_id'];
        }
        
	}
	
	/**
	 * 회원 모바일에서 오늘의 GX 스케쥴 리스트 
	 * @param string $today
	 * @return array
	 */
	public function list_gx_today_schd_user($today)
	{
	    $gxData['comp_cd'] = $this->comp_cd;
	    $gxData['bcoff_cd'] = $this->bcoff_cd;
	    $gxData['gx_clas_s_date'] = $today;
	    
	    $list_gx_today = $this->modelClas->user_today_gx_schd($gxData);
	    return $list_gx_today;
	}
	
	/**
	 * 회원 모바일에서 오늘의 GX 스케쥴 리스트
	 * @param string $today
	 * @return array
	 */
	public function list_gx_today_schd_user_new($today)
	{
	    $gxData['comp_cd'] = $this->comp_cd;
	    $gxData['bcoff_cd'] = $this->bcoff_cd;
	    $gxData['gx_clas_s_date'] = $today;
	    
	    $return_val['list_gx_today_group'] = $this->modelClas->user_today_gx_schd_group($gxData);
	    $return_val['list_gx_today'] = $this->modelClas->user_today_gx_schd($gxData);
	    return $return_val;
	}
    
	/**
	 * 해당날짜에 해당하는 GX 리스트를 가져온다. (
	 * @param string $today
	 * @return array
	 */
	public function list_gx_today_schd_stchr_id($today)
	{
	    $gxData['comp_cd'] = $this->comp_cd;
	    $gxData['bcoff_cd'] = $this->bcoff_cd;
	    $gxData['gx_stchr_id'] = $this->stchr_id;
	    $gxData['gx_clas_s_date'] = $today;
	    
	    $list_gx_today = $this->modelClas->my_today_gx_schd($gxData);
	    return $list_gx_today;
	}
	
	/**
	 * Gx 수업을 체크 한다.
	 * @param string $chk_flg ( Y/N )
	 */
	public function gx_attd_chk($gx_schd_mgmt_sno,$chk_flg)
	{
	    $nn_now = new Time('now');
	    
	    $this->gx_schd_mgmt_sno = $gx_schd_mgmt_sno;
	    $gxData['comp_cd'] = $this->comp_cd;
	    $gxData['bcoff_cd'] = $this->bcoff_cd;
	    $gxData['gx_schd_mgmt_sno'] = $this->gx_schd_mgmt_sno;
	    $gx_schd_info = $this->modelClas->get_gx_schedule($gxData);
	    
	    $this->gx_schd_info = $gx_schd_info[0];
	    
	    
	    $return_value['msg'] = "";
	    $return_value['result'] = "";
	    
	    //gx_schd_info 에서 GX_CLAS_S_DATE 와 현재 날짜를 비교하여 맞는지를 검사한다.
	    if ($this->gx_schd_info['GX_CLAS_S_DATE'] != date('Y-m-d'))
	    {
	        $return_value['msg'] = "오늘 GX 수업 상품이 아닙니다.";
	        $return_value['result'] = "false";
	        return $return_value;
	    }
	    
	    $chk_s_time = strtotime($this->gx_schd_info['GX_CLAS_S_DATE'] . " " . $this->gx_schd_info['GX_CLAS_S_HH_II']);
	    $chk_e_time = strtotime($this->gx_schd_info['GX_CLAS_E_DATE'] . " " . $this->gx_schd_info['GX_CLAS_E_HH_II']);
	    $chk_n_time = time();
	    // 시간을 비교하여 체크가 가능한 시간대인지를 검사한다.
	    
	    if ( !($chk_s_time < $chk_n_time && $chk_n_time < $chk_e_time) )
	    {
	        $return_value['msg'] = "GX수업체크가 가능한 시간대가 아닙니다.";
	        $return_value['result'] = "false";
	        return $return_value;
	    }
	    
	    $chkData['comp_cd'] = $this->comp_cd;
	    $chkData['bcoff_cd'] = $this->bcoff_cd;
	    $chkData['clas_chk_yn'] = $chk_flg;
	    $chkData['gx_schd_mgmt_sno'] = $this->gx_schd_mgmt_sno;
	    
	    $ex_gx_clas_mgmt_chk = $this->modelClas->get_gx_schedule_chk($chkData);
	    
	    if ($ex_gx_clas_mgmt_chk > 0)
	    {
	        $return_value['msg'] = "이미 처리 되었습니다.";
	        $return_value['result'] = "false";
	        return $return_value;
	    }
	    
	    $iu_chk = $this->modelClas->get_gx_schedule_update_chk($chkData);
	    
	    if ($iu_chk == 0) // insert 
	    {
	        $idata['gx_schd_mgmt_sno'] = $this->gx_schd_info['GX_SCHD_MGMT_SNO'];
	        $idata['gx_room_mgmt_sno'] = $this->gx_schd_info['GX_ROOM_MGMT_SNO'];
	        $idata['comp_cd'] = $this->gx_schd_info['COMP_CD'];
	        $idata['bcoff_cd'] = $this->gx_schd_info['BCOFF_CD'];
	        $idata['gx_stchr_sno'] = $this->gx_schd_info['GX_STCHR_SNO'];
	        $idata['gx_stchr_id'] = $this->gx_schd_info['GX_STCHR_ID'];
	        $idata['gx_stchr_nm'] = $this->gx_schd_info['GX_STCHR_NM'];
	        $idata['gx_stchr_dv'] = "00";
	        $idata['gx_clas_title'] = $this->gx_schd_info['GX_CLAS_TITLE'];
	        $idata['gx_clas_date'] = date('Y-m-d');
	        $idata['gx_clas_dotw'] = $this->gx_schd_info['GX_CLAS_DOTW'];
	        $idata['gx_clas_s_hh_ii'] = $this->gx_schd_info['GX_CLAS_S_HH_II'];
	        $idata['gx_clas_e_hh_ii'] = $this->gx_schd_info['GX_CLAS_E_HH_II'];
	        $idata['clas_chk_yn'] = $chk_flg;
	        
	        $idata['cre_id'] = $_SESSION['user_id'];
	        $idata['cre_datetm'] = $nn_now;
	        $idata['mod_id'] = $_SESSION['user_id'];
	        $idata['mod_datetm'] = $nn_now;
	        
	        $this->modelClas->insert_gx_clas_mgmt($idata);
	        
	        $return_value['msg'] = "GX 수업체크가 완료 되었습니다. [00]";
	        $return_value['result'] = "true";
	        
	    } else // update 
	    {
	        $udata['comp_cd'] = $this->gx_schd_info['COMP_CD'];
	        $udata['bcoff_cd'] = $this->gx_schd_info['BCOFF_CD'];
	        $udata['gx_schd_mgmt_sno'] = $this->gx_schd_info['GX_SCHD_MGMT_SNO'];
	        $udata['clas_chk_yn'] = $chk_flg;
	        $udata['mod_id'] = $_SESSION['user_id'];
	        $udata['mod_datetm'] = $nn_now;
	        
	        $this->modelClas->update_gx_clas_mgmt($udata);
	        
	        $return_value['msg'] = "GX 수업체크가 업데이트 되었습니다. [01]";
	        $return_value['result'] = "true";
	    }
	    
	    return $return_value;
	}
	
}