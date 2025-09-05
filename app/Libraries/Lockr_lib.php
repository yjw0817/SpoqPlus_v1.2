<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\EventModel;
use App\Models\LockrModel;

class Lockr_lib {

    private $set_initVar;
    
    /**
     * 처음 셋팅된 회사 코드
     * @var string
     */
    private $set_comp_cd;
    
    /**
     * 처음 셋팅된 지점 코드
     * @var string
     */
    private $set_bcoff_cd;
    
    /**
     * 라커 정보
     * @var array
     */
    private $data_locrk_info;
    
    /**
     * 처음 셋팅된 회원 일련번호
     * @var string
     */
    private $set_mem_sno;
    
    /**
     * EventModel
     * @var object
     */
    private $modelEvent;
    
    /**
     * LockrModel
     * @var object
     */
    private $modelLockr;
    
    /**
     * 리턴값
     * @var array
     */
    private $retunValue = array();
    
    /**
     * $initVar['comp_cd'];
     * $initVar['bcoff_cd'];
     * $initVar['mem_sno'];
     * @param array $initVar
     */
    public function __construct($initVar) {
        $this->set_initVar = $initVar;
        
        $this->modelLockr = new LockrModel();
        
        $data['comp_cd'] = $_SESSION['comp_cd'];
        $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
        $data['lockr_knd'] = $initVar['knd'];
        $data['lockr_gendr_set'] = $initVar['gendr'];
        $data['lockr_no'] = $initVar['no'];
        
        $get_lockr_info = $this->modelLockr->get_lockr_room_no($data);
        
        $this->data_locrk_info = $get_lockr_info[0];
	}
	
	/**
	 * 라커 [고장]
	 * @return array
	 */
	public function run_lockr_brok()
	{
	    // where
	    $data['comp_cd']           = $this->data_locrk_info['COMP_CD'];
	    $data['bcoff_cd']          = $this->data_locrk_info['BCOFF_CD'];
	    $data['lockr_knd']         = $this->data_locrk_info['LOCKR_KND'];
	    $data['lockr_gendr_set']   = $this->data_locrk_info['LOCKR_GENDR_SET'];
	    $data['lockr_no']          = $this->data_locrk_info['LOCKR_NO'];
	    // set
	    $data['buy_event_sno']     = $this->data_locrk_info['BUY_EVENT_SNO'];
	    $data['mem_sno']           = $this->data_locrk_info['MEM_SNO'];
	    $data['mem_nm']            = $this->data_locrk_info['MEM_NM'];
	    $data['lockr_use_s_date']  = $this->data_locrk_info['LOCKR_USE_S_DATE'];
	    $data['lockr_use_e_date']  = $this->data_locrk_info['LOCKR_USE_E_DATE'];
	    $data['lockr_stat']        = "90"; //수리중
	    
	    $this->modelLockr->update_lockr_room_stat($data);
	    
	    $this->retunValue['msg'] = "라커고장";
	    return $this->retunValue;
	}
	
	/**
	 * 라커 [고장 해제]
	 * @return array
	 */
	public function run_lockr_brokn()
	{
	    // where
	    $data['comp_cd']           = $this->data_locrk_info['COMP_CD'];
	    $data['bcoff_cd']          = $this->data_locrk_info['BCOFF_CD'];
	    $data['lockr_knd']         = $this->data_locrk_info['LOCKR_KND'];
	    $data['lockr_gendr_set']   = $this->data_locrk_info['LOCKR_GENDR_SET'];
	    $data['lockr_no']          = $this->data_locrk_info['LOCKR_NO'];
	    // set
	    $data['buy_event_sno']     = $this->data_locrk_info['BUY_EVENT_SNO'];
	    $data['mem_sno']           = $this->data_locrk_info['MEM_SNO'];
	    $data['mem_nm']            = $this->data_locrk_info['MEM_NM'];
	    $data['lockr_use_s_date']  = $this->data_locrk_info['LOCKR_USE_S_DATE'];
	    $data['lockr_use_e_date']  = $this->data_locrk_info['LOCKR_USE_E_DATE'];
	    $data['lockr_stat']        = "00"; //사용가능
	    
	    $this->modelLockr->update_lockr_room_stat($data);
	    
	    $this->retunValue['msg'] = "라커고장해제";
	    return $this->retunValue;
	}
	
	/**
	 * 라커 [비우기]
	 * @return array
	 */
	public function run_lockr_empty()
	{
	    // where
	    $data['comp_cd']           = $this->data_locrk_info['COMP_CD'];
	    $data['bcoff_cd']          = $this->data_locrk_info['BCOFF_CD'];
	    $data['lockr_knd']         = $this->data_locrk_info['LOCKR_KND'];
	    $data['lockr_gendr_set']   = $this->data_locrk_info['LOCKR_GENDR_SET'];
	    $data['lockr_no']          = $this->data_locrk_info['LOCKR_NO'];
	    // set
	    $data['buy_event_sno']     = "";
	    $data['mem_sno']           = "";
	    $data['mem_nm']            = "";
	    $data['lockr_use_s_date']  = "";
	    $data['lockr_use_e_date']  = "";
	    $data['lockr_stat']        = "00"; //사용가능
	    
	    $this->modelLockr->update_lockr_room_stat($data);
	    
	    $this->retunValue['msg'] = "라커비우기";
	    return $this->retunValue;
	}
	
	/**
	 * 라커 [이동]
	 */
	public function run_lockr_move()
	{
	    //라커 이동 가능한지 체크
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['lockr_knd'] = $this->set_initVar['knd'];
	    $data['lockr_gendr_set'] = $this->set_initVar['gendr'];
	    $data['lockr_no'] = $this->set_initVar['af_no'];
	    
	    $get_af_lockr_info = $this->modelLockr->get_lockr_room_no($data);
	    
	    $tf_rr = true;
	    if (count($get_af_lockr_info) == 0)
	    {
	        $this->retunValue['result'] = "false";
	        $tf_rr = false;
	    } else 
	    {
	        if ($get_af_lockr_info[0]['LOCKR_STAT'] != "00")
	        {
	            $this->retunValue['result'] = "false";
	            $tf_rr = false;
	        }
	    }
	    
	    if ($tf_rr == true)
	    {
	        $this->lockr_move_proc();
	        $this->retunValue['result'] = "true";
	        $tf_rr = true;
	    }
	    
	    return $this->retunValue;
	    
	}
	
	/**
	 * 라커 이동 처리
	 */
	private function lockr_move_proc()
	{
	    // [비우기처리]
	    // where
	    $data['comp_cd']           = $this->data_locrk_info['COMP_CD'];
	    $data['bcoff_cd']          = $this->data_locrk_info['BCOFF_CD'];
	    $data['lockr_knd']         = $this->data_locrk_info['LOCKR_KND'];
	    $data['lockr_gendr_set']   = $this->data_locrk_info['LOCKR_GENDR_SET'];
	    $data['lockr_no']          = $this->data_locrk_info['LOCKR_NO'];
	    // set
	    $data['buy_event_sno']     = "";
	    $data['mem_sno']           = "";
	    $data['mem_nm']            = "";
	    $data['lockr_use_s_date']  = "";
	    $data['lockr_use_e_date']  = "";
	    $data['lockr_stat']        = "00"; //사용가능
	    
	    $this->modelLockr->update_lockr_room_stat($data);
	    
	    // [이동처리]
	    // where
	    $data2['comp_cd']           = $this->data_locrk_info['COMP_CD'];
	    $data2['bcoff_cd']          = $this->data_locrk_info['BCOFF_CD'];
	    $data2['lockr_knd']         = $this->data_locrk_info['LOCKR_KND'];
	    $data2['lockr_gendr_set']   = $this->data_locrk_info['LOCKR_GENDR_SET'];
	    $data2['lockr_no']          = $this->set_initVar['af_no'];
	    // set
	    $data2['buy_event_sno']     = $this->data_locrk_info['BUY_EVENT_SNO'];
	    $data2['mem_sno']           = $this->data_locrk_info['MEM_SNO'];
	    $data2['mem_nm']            = $this->data_locrk_info['MEM_NM'];
	    $data2['lockr_use_s_date']  = $this->data_locrk_info['LOCKR_USE_S_DATE'];
	    $data2['lockr_use_e_date']  = $this->data_locrk_info['LOCKR_USE_E_DATE'];
	    $data2['lockr_stat']        = "01"; //이용중
	    
	    $this->modelLockr->update_lockr_room_stat($data2);
	    
	    // [구매상품 라커번호 업데이트 처리]
	    $data3['comp_cd']           = $this->data_locrk_info['COMP_CD'];
	    $data3['bcoff_cd']          = $this->data_locrk_info['BCOFF_CD'];
	    $data3['buy_event_sno']     = $this->data_locrk_info['BUY_EVENT_SNO'];
	    $data3['lockr_no']          = $this->set_initVar['af_no'];
	    
	    $this->modelLockr->update_buy_event_lockr_no($data3);
	}
	
	
	
	
	
	
	
	
}