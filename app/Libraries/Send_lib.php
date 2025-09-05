<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\EventModel;
use App\Models\SendModel;
use App\Models\MemModel;

class Send_lib {

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
     * 처음 셋팅된 회원 일련번호
     * @var string
     */
    private $set_mem_sno;
    
    /**
     * 상품 보내기 최대 노출 일자
     * @var string
     */
    private $set_end_day;
    
    /**
     * 판매상품 정보
     * @var array
     */
    private $set_buy_info;
    
    /**
     * EventModel
     * @var object
     */
    private $modelEvent;
    
    /**
     * 리턴값
     * @var array
     */
    private $retunValue = array();
    
    
    /**
     * $initVar['comp_cd'];
     * $initVar['bcoff_cd'];
     * $initVar['send_sell_event_sno'];
     * $initVar['set_send_amt'];
     * $initVar['set_send_serv_clas_cnt'];
     * $initVar['set_send_serv_day'];
     * $initVar['set_send_domcy_cnt'];
     * $initVar['set_send_domcy_day'];
     * $initVar['set_ptchr_id_nm'];
     * $initVar['set_stchr_id_nm'];
     * $initVar['set_send_mem_snos'];
     * @param array $initVar
     */
    public function __construct($initVar) {
        $this->set_initVar = $initVar;
        $this->set_comp_cd = $initVar['comp_cd'];
        $this->set_bcoff_cd = $initVar['bcoff_cd'];
        $this->set_end_day = $initVar['set_end_day'];
	}
	
	/**
	 * 종복 보내기
	 */
	public function send_event()
	{
	    $this->modelEvent = new EventModel();
	    
	    $sData['comp_cd'] = $this->set_comp_cd;
	    $sData['bcoff_cd'] = $this->set_bcoff_cd;
	    $sData['sell_event_sno'] = $this->set_initVar['send_sell_event_sno'];
	    
	    $buy_info = $this->modelEvent->get_event($sData);
	    $this->set_buy_info = $buy_info[0];
        
	    $this->insert_send_event();
	}
	
	/**
	 * select 해온 아이디|이름 을 배열로 나누어서 리턴한다.
	 * @param string $var
	 * @return array
	 */
	private function tchr_id_nm($var)
	{
	    $returnVal['tid'] = "";
	    $returnVal['tnm'] = "";
	    
	    if ($var != '')
	    {
	        $tinfo = explode("|", $var);
	        $returnVal['tid'] = $tinfo[0];
	        $returnVal['tnm'] = $tinfo[1];
	    }
	    
	    return $returnVal;
	}
	
	/**
	 * 상품 보내기 database insert
	 */
	private function insert_send_event()
	{
	    $nn_now = new Time('now');
	    $modelSend = new SendModel();
	    $modelMem = new MemModel();
	    
	    $stchr = $this->tchr_id_nm($this->set_initVar['set_stchr_id_nm']);
	    $ptchr = $this->tchr_id_nm($this->set_initVar['set_ptchr_id_nm']);
	    
	    $sdate = date('Y-m-d');
	    $edate = date('Y-m-d', strtotime($sdate . ' +'.$this->set_end_day.' days'));
	    
	    //$insData['send_event_mgmt_sno'] = "";
	    $insData['sell_event_sno'] = $this->set_buy_info['SELL_EVENT_SNO'];
	    $insData['comp_cd'] = $this->set_buy_info['COMP_CD'];
	    $insData['bcoff_cd'] = $this->set_buy_info['BCOFF_CD'];
	    $insData['1rd_cate_cd'] = $this->set_buy_info['1RD_CATE_CD'];
	    $insData['2rd_cate_cd'] = $this->set_buy_info['2RD_CATE_CD'];
	    
	    $insData['sell_event_nm'] = $this->set_buy_info['SELL_EVENT_NM'];
	    $insData['use_prod'] = $this->set_buy_info['USE_PROD'];
	    $insData['use_unit'] = $this->set_buy_info['USE_UNIT'];
	    $insData['clas_cnt'] = $this->set_buy_info['CLAS_CNT'];
	    $insData['domcy_poss_event_yn'] = $this->set_buy_info['DOMCY_POSS_EVENT_YN'];
	    $insData['acc_rtrct_dv'] = $this->set_buy_info['ACC_RTRCT_DV'];
	    $insData['acc_rtrct_mthd'] = $this->set_buy_info['ACC_RTRCT_MTHD'];
	    $insData['clas_dv'] = $this->set_buy_info['CLAS_DV'];
	    $insData['mem_disp_yn'] = $this->set_buy_info['MEM_DISP_YN'];
	    $insData['sell_s_date'] = $this->set_buy_info['SELL_S_DATE'];
	    $insData['sell_e_date'] = $this->set_buy_info['SELL_E_DATE'];
	    $insData['event_img'] = $this->set_buy_info['EVENT_IMG'];
	    $insData['event_icon'] = $this->set_buy_info['EVENT_ICON'];
	    $insData['grp_clas_psnnl_cnt'] = $this->set_buy_info['GRP_CLAS_PSNNL_CNT'];
	    $insData['sell_stat'] = $this->set_buy_info['SELL_STAT'];
	    $insData['ori_sell_amt'] = put_num($this->set_buy_info['SELL_AMT']);
	    $insData['ori_clas_cnt'] = $this->set_buy_info['CLAS_CNT'];
	    $insData['ori_domcy_day'] = $this->set_buy_info['DOMCY_DAY'];
	    $insData['ori_domcy_cnt'] = $this->set_buy_info['DOMCY_CNT'];
	    $insData['grp_cate_set'] = $this->set_buy_info['GRP_CATE_SET'];
	    $insData['lockr_set'] = $this->set_buy_info['LOCKR_SET'];
	    $insData['lockr_knd'] = $this->set_buy_info['LOCKR_KND'];
	    $insData['lockr_gendr_set'] = $this->set_buy_info['LOCKR_GENDR_SET'];
	    $insData['lockr_no'] = "";
	    
	    $insData['stchr_id'] = $stchr['tid'];
	    $insData['stchr_nm'] = $stchr['tnm'];
	    $insData['ptchr_id'] = $ptchr['tid'];
	    $insData['ptchr_nm'] = $ptchr['tnm'];
	    
	    $insData['sell_amt'] = put_num($this->set_initVar['set_send_amt']); // set_send_amt
	    $insData['add_srvc_exr_day'] = $this->set_initVar['set_send_serv_day']; // set_send_serv_day
	    $insData['add_srvc_clas_cnt'] = $this->set_initVar['set_send_serv_clas_cnt']; // set_send_serv_clas_cnt
	    $insData['domcy_day'] = $this->set_initVar['set_send_domcy_day']; // set_send_domcy_day
	    $insData['domcy_cnt'] = $this->set_initVar['set_send_domcy_cnt']; // set_send_domcy_cnt
	    
	    $insData['send_buy_s_date'] = $sdate;
	    $insData['send_buy_e_date'] = $edate;
	    $insData['send_stat'] = "00"; // 00 : 대기 상태
	    
	    $insData['cre_id'] = $_SESSION['user_id'];
	    $insData['cre_datetm'] = $nn_now;
	    $insData['mod_id'] = $_SESSION['user_id'];
	    $insData['mod_datetm'] = $nn_now;
	    
	    $loop_mem = explode(",",$this->set_initVar['set_send_mem_snos']);
	    
	    for($i=0;$i<count($loop_mem);$i++)
	    {
	        $mData['comp_cd'] = $this->set_comp_cd;
	        $mData['bcoff_cd'] = $this->set_bcoff_cd;
	        $mData['mem_sno'] = $loop_mem[$i];
	        $mem_info = $modelMem->get_mem_info_mem_sno($mData);
	        
	        if (count($mem_info) > 0)
	        {
	            $insData['mem_sno'] = $mem_info[0]['MEM_SNO'];
	            $insData['mem_id'] = $mem_info[0]['MEM_ID'];
	            $insData['mem_nm'] = $mem_info[0]['MEM_NM'];
	            $modelSend->insert_send_event_mgmt($insData);
	        }
	    }
	}
	
	
	
	
	
}