<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\TransModel;
use App\Models\PayModel;
use App\Models\EventModel;
use App\Models\MemModel;

class Trans_lib {

    /**
     * TransModel
     * @var object
     */
    private $modelTrans;
    
    /**
     * PayModel
     * @var object
     */
    private $modelPay;
    
    /**
     * EventModel
     * @var object;
     */
    private $modelEvent;
    
    /**
     * 회원정보 (양도자)
     * @var array
     */
    private $data_meminfo;
    
    /**
     * 회원정보 (양수자)
     * @var array
     */
    private $data_tmeminfo;
    
    /**
     * 양도상품 정보
     * @var array
     */
    private $data_buyinfo;
    
    private $set_data;
    
    private $result_info = array("result"=>true);
    
    private $initVar;
    
    private $pay_card_amt;
    
    private $pay_cash_amt;
    
    /**
     * 양도자 결제 승인 일련번호
     * @var string
     */
    private $pay_appno_sno;
    
    /**
     * 양도자 결제 승인번호
     * @var string
     */
    private $pay_appno;
    
    private $data_sell_event_info;
    
    private $pay_sell_sno;
    
    private $pay_comp_cd;
    
    private $pay_bcoff_cd;
    
    /**
     * 양수자 이용중, 예약됨 상품 정보
     * @var array
     */
    private $data_buy_event_info = array();
    
    /**
     * 양수자 회원 일련번호
     * @var string
     */
    private $pay_mem_sno;
    
    /**
     * 양수자 운동 시작일
     * @var string
     */
    private $pay_exr_s_date;
    
    /**
     * 양수자 회원 정보
     * @var array
     */
    private $data_mem_info;
    
    /**
     * 양수자 운동관련 셋팅정보
     * @var array
     */
    private $set_db_data;
    
    /**
     * 양수자 영향받을 상품 정보
     * @var array
     */
    private $after_buy = array();
    
    
    private $transinfo;
    
    /**
     * initVar['comp_cd']
     * initVar['bcoff_cd']
     * initVar['mem_sno']
     * initVar['tmem_sno']
     * initVar['buy_sno']
     * @param array $initVar
     */
    public function __construct($initVar) {
	    
        $this->initVar = $initVar;
        $this->modelTrans = new TransModel();
        $this->modelEvent = new EventModel();
	    
        $this->pay_comp_cd = $_SESSION['comp_cd'];
        $this->pay_bcoff_cd = $_SESSION['bcoff_cd'];
        
        // 양도자 회원 정보
        $memData['comp_cd'] = $_SESSION['comp_cd'];
        $memData['bcoff_cd'] = $_SESSION['bcoff_cd'];
        $memData['mem_sno'] = $initVar['mem_sno'];
        $meminfo = $this->modelTrans->get_mem_info_detl($memData);
        $this->data_meminfo = $meminfo[0]; 
	    
        // 양수자 회원 정보
        $tmemData['comp_cd'] = $_SESSION['comp_cd'];
        $tmemData['bcoff_cd'] = $_SESSION['bcoff_cd'];
        $tmemData['mem_sno'] = $initVar['tmem_sno'];
        $tmeminfo = $this->modelTrans->get_mem_info_detl($tmemData);
        $this->data_tmeminfo = $tmeminfo[0];
        
        // 양도할 상품 정보
        $buyinfoData['comp_cd'] = $_SESSION['comp_cd'];
        $buyinfoData['bcoff_cd'] = $_SESSION['bcoff_cd'];
        $buyinfoData['buy_event_sno'] = $initVar['buy_sno'];
        $buyinfo = $this->modelTrans->get_buy_event($buyinfoData);
        $this->data_buyinfo = $buyinfo[0];
        
        // 양수자의 구매한 상품중 출입제한 조건 구하기
        $this->func_set_acc_mthd();
        
	}
	
	/**
	 * 양수자의 구매한 상품중 출입제한 조건 구하기
	 */
	private function func_set_acc_mthd()
	{
	    $tData['comp_cd'] = $this->data_tmeminfo['COMP_CD'];
	    $tData['bcoff_cd'] = $this->data_tmeminfo['BCOFF_CD'];
	    $tData['mem_sno'] = $this->data_tmeminfo['MEM_SNO'];
	    
	    $buy_list = $this->modelEvent->get_buy_events_use_memsno($tData);
	    
	    $acc_rtrct_mthd = "";
	    $acc_rtrct_dv = "";
	    foreach ($buy_list as $r)
	    {
	        if ($r['ACC_RTRCT_DV'] != "99" && $r['ACC_RTRCT_DV'] != "50")
	        {
	            $acc_rtrct_mthd = $r['ACC_RTRCT_MTHD'];
	            $acc_rtrct_dv = $r['ACC_RTRCT_DV'];
	        }
	    }
	    
	    $this->data_tmeminfo['acc_dv'] = $acc_rtrct_dv;
	    $this->data_tmeminfo['acc_mthd'] = $acc_rtrct_mthd;
	}
	
	/**
	 * 양도하기 정보
	 */
	public function trans_info()
	{
	    
	    $this->result_info['mem_info'] = $this->data_meminfo;
	    $this->result_info['tmem_info'] = $this->data_tmeminfo;
	    $this->result_info['data_buyinfo'] = $this->data_buyinfo;
	    
	    $trans_buy_info = $this->trans_buy_info();
	    
	    $this->result_info['trans_buy_info'] = $trans_buy_info;
	    return $this->result_info;
	}
	
	/**
	 * 입장 제한이 다를 경우에 대한 재 계산
	 */
	private function diff_calu_info($rr)
	{
	    $edata['sell_event_sno'] = $this->data_buyinfo['SELL_EVENT_SNO'];
	    $get_event_ref_sno = $this->modelEvent->get_diff_sell_event_ref_sno($edata);
	    
	    $sdata['event_ref_sno'] = $get_event_ref_sno[0]['EVENT_REF_SNO'];
	    $sdata['acc_rtrct_dv'] = $this->data_tmeminfo['acc_dv'];
	    $sdata['acc_rtrct_mthd'] = $this->data_tmeminfo['acc_mthd'];
	    $sdata['use_prod'] = $this->data_buyinfo['USE_PROD'];
	    $sdata['use_unit'] = $this->data_buyinfo['USE_UNIT'];
	    
	    $diff_event = $this->modelEvent->get_diff_sell_event_mgmt($sdata);
	    
	    if (count($diff_event) == 0)
	    {
	        $returnVal['re_prod'] = "";
	        $returnVal['re_sell_event_sno'] = "";
	        $returnVal['diff_result'] = false;
	        return $returnVal;
	    }
	    
	    $this->result_info['diff_event'] = $diff_event;
	    
	    $total_exr_day_cnt = $rr['total_exr_day_cnt'];
	    $sell_amt = $diff_event[0]['SELL_AMT'];
	    $refund_amt = $rr['refund_amt'];
	    
	    $returnVal['diff_result'] = true;
	    $returnVal['re_prod'] = ceil( $refund_amt / ($sell_amt / $total_exr_day_cnt) ); // 양도일 재계산
	    $returnVal['re_sell_event_sno'] = $diff_event[0]['SELL_EVENT_SNO'];
	    return $returnVal;
	}
	
	/**
	 * 양도할 상품에 대한 상태
	 */
	private function trans_buy_info()
	{
	    $initVar['pay_comp_cd']     = $_SESSION['comp_cd'];
	    $initVar['pay_bcoff_cd']    = $_SESSION['bcoff_cd'];
	    $initVar['pay_mem_sno']     = $this->initVar['mem_sno'];
	    $initVar['pay_buy_sno']     = $this->initVar['buy_sno'];
	    
	    $refundlib = new Refund_lib($initVar);
	    
	    $refundlib->refund_info();
	    $rr =  $refundlib->refund_result();
	    $rr['refund_info']['re_sell_event_sno'] = "";
	    $rr['refund_info']['ori_left_day_cnt'] = $rr['refund_info']['left_day_cnt'];
	    $rr['refund_info']['diff_result'] = true;
	    
	    $diff_acc_mthd = true;
	    if ($this->data_buyinfo['ACC_RTRCT_DV'] != "99" && $this->data_buyinfo['ACC_RTRCT_DV'] != "50")
	    {
	        if ($this->data_buyinfo['ACC_RTRCT_MTHD'] != $this->data_tmeminfo['acc_mthd'])
	        {
	            $diff_acc_mthd = false;
	            $diff_info = $this->diff_calu_info($rr['refund_info']);
	            $rr['refund_info']['diff_result'] = $diff_info['diff_result'];
	            $rr['refund_info']['left_day_cnt'] = $diff_info['re_prod'];
	            $rr['refund_info']['re_sell_event_sno'] = $diff_info['re_sell_event_sno'];
	        }
	    }
	    $this->result_info['diff_acc_mthd'] = $diff_acc_mthd;
	    
	    return $rr;
	}
	
	/**
	 * 양도를 실행한다.
	 */
	public function trans_run()
	{
	    $this->modelPay = new PayModel();
	    $this->pay_card_amt = $this->initVar['payinfo']['pay_card_amt'];
	    $this->pay_cash_amt = $this->initVar['payinfo']['pay_cash_amt'];
	    $this->pay_appno_sno = $this->initVar['payinfo']['van_appno_sno']; // 승인번호_일련번호
	    $this->pay_appno = $this->initVar['payinfo']['van_appno']; // 승인번호
	    $this->pay_exr_s_date = $this->initVar['payinfo']['pay_exr_s_date']; // 양수자 운동 시작일
	    
	    $this->pay_sell_sno = $this->data_buyinfo['SELL_EVENT_SNO']; // 판매상품 일련번호
	    $this->pay_mem_sno = $this->data_tmeminfo['MEM_SNO'];
	    
	    $amasno = new Ama_sno();
	    $this->set_db_data['buy_sno'] = $amasno->create_buy_sno();
	    
	    $this->func_sell_event_info(); // 판매 정보를 가져온다.
	    $this->func_buy_event_info(); // 구매 정보를 가져온다 (이용중,예약됨)
	    $this->data_mem_info = $this->data_tmeminfo; // 양수자 회원 정보를 복제한다. (Pay_lib 에서 사용되기때문에)
	    
	    $type_acc_rtrct = $this->func_acct_rtrct_check(); // 1. PT 관련 상품인지 2. 이용권관련 상품인지 체크한다.
	    
	    // TODO @@@ 출입제한 조건이 달라도 구매가 가능해야한다.
	    /*
	    if ($type_acc_rtrct['result'] == false)
	    {
	        $rt['result_msg'] = "출입제한 조건이 다릅니다.";
	        $rt['result'] = false;
	        $this->return_pay_result = $rt;
	        return false;
	    }
	    */
	    
	    // 이용권 상품에서 라커 설정을 체크 하여 라커라면 3으로 변경한다.
	    if ($type_acc_rtrct['type'] == 2)
	    {
	        if ($this->data_sell_event_info['LOCKR_SET'] == "Y")
	        {
	            // 양도는 무조건 라커 번호가 없어야 한다. 그렇기 때문에 4번 조건만 있다.
	            $type_acc_rtrct['type'] = 4; // 라커번호가 없기 때문에 무조건 시작, 종료일이없고 예약 상품으로 표기가 되어야 함.
	        }
	    }
	    
	    if ($type_acc_rtrct['type'] == 1)
	    {
	        $this->func_pt_yes();
	    } elseif ($type_acc_rtrct['type'] == 2)
	    {
	        $this->func_pt_no();
	    } elseif ($type_acc_rtrct['type'] == 4)
	    {
	        $this->func_pt_no_lockr_empty();
	    }
	    
	    //재배열될 상품들의 시작, 종료일 업데이트
	    $this->func_remake_buy_update();
	    
	    
	    //구매내역 처리
	    $this->func_buy_proc();
	    
	    // 양도비를 처리한다.
	    $this->func_paymt_proc();
	    
	    // 양도자의 상품을 종료 처리 한다.
	    $this->func_trans_event_end_proc();
	    
	    // 양도 관리 내역을 처리한다.
	    $this->func_trans_mgmt_proc();
	    
	    // 종료회원인시를 체크하여 업데이트 한다.
	    $this->func_end_mem();
	    
	    
	    return $this->result_info;
	}
	
	private function func_end_mem()
	{
	    $nn_now = new Time('now');
	    
	    $modelEvent = new EventModel();
	    $modelMem = new MemModel();
	    
	    $eData['comp_cd'] = $this->data_meminfo['COMP_CD'];
	    $eData['bcoff_cd'] = $this->data_meminfo['BCOFF_CD'];
	    $eData['mem_sno'] = $this->data_meminfo['MEM_SNO'];
	    $chk_end_counter = $modelEvent->end_chk_event_mem_sno($eData);
	    
	    if ($chk_end_counter == 0)
	    {
	        $upVar['mem_stat'] = '90'; //종료회원
	        $upVar['mem_sno'] = $this->data_meminfo['MEM_SNO'];
	        $upVar['end_datetm'] = $nn_now;
	        $upVar['mod_id'] = $_SESSION['user_id'];
	        $upVar['mod_datetm'] = $nn_now;
	        
	        $modelMem->update_mem_end($upVar);
	    }
	}
	
	/**
	 * 양도 관리 내역을 처리한다.
	 */
	private function func_trans_mgmt_proc()
	{
	    $nn_now = new Time('now');
	    
	    $transData['buy_event_sno'] = $this->data_buyinfo['BUY_EVENT_SNO'];
	    $transData['sell_event_sno'] = $this->data_buyinfo['SELL_EVENT_SNO'];
	    $transData['send_event_sno'] = $this->data_buyinfo['SEND_EVENT_SNO'];
	    $transData['sell_event_nm'] = $this->data_buyinfo['SELL_EVENT_NM'];
	    $transData['assgn_buy_event_sno'] = $this->set_db_data['buy_sno'];
	    $transData['assgn_sell_event_nm'] = $this->data_sell_event_info['SELL_EVENT_NM'];
	    
	    $transData['comp_cd'] = $this->data_buyinfo['COMP_CD'];
	    $transData['bcoff_cd'] = $this->data_buyinfo['BCOFF_CD'];
	    $transData['1rd_cate_cd'] = $this->data_buyinfo['1RD_CATE_CD'];
	    $transData['2rd_cate_cd'] = $this->data_buyinfo['2RD_CATE_CD'];
	    
	    $transData['transm_sno'] = $this->data_meminfo['MEM_SNO'];
	    $transData['transm_id'] = $this->data_meminfo['MEM_ID'];
	    $transData['transm_nm'] = $this->data_meminfo['MEM_NM'];
	    
	    $transData['assgn_sno'] = $this->data_tmeminfo['MEM_SNO'];
	    $transData['assgn_id'] = $this->data_tmeminfo['MEM_ID'];
	    $transData['assgn_nm'] = $this->data_tmeminfo['MEM_NM'];
	    
	    $transData['transm_use_day'] = $this->transinfo['use_day_cnt'];
	    $transData['transm_left_day'] = $this->transinfo['ori_left_day_cnt'];
	    $transData['use_amt'] = $this->transinfo['use_amt'];
	    $transData['left_amt'] = $this->transinfo['refund_amt'];
	    $transData['assgn_exr_s_date'] = $this->pay_exr_s_date;
	    $transData['transm_acc_rtrct_dv'] = $this->data_buyinfo['ACC_RTRCT_DV'];
	    $transData['transm_acc_rtrct_mthd'] = $this->data_buyinfo['ACC_RTRCT_MTHD'];
	    $transData['assgn_acc_rtrct_dv'] = $this->data_sell_event_info['ACC_RTRCT_DV'];
	    $transData['assgn_acc_rtrct_mthd'] = $this->data_sell_event_info['ACC_RTRCT_MTHD'];
	    $transData['real_transm_day'] = $this->transinfo['left_day_cnt'];
	    $transData['real_transm_cnt'] = $this->transinfo['mem_regul_clas_left_cnt'];
	    $transData['transm_cre_date'] = date('Y-m-d');
	    $transData['transm_stat'] = "00";
	    
	    $transData['cre_id'] = $_SESSION['user_id'];
	    $transData['cre_datetm'] = $nn_now;
	    $transData['mod_id'] = $_SESSION['user_id'];
	    $transData['mod_datetm'] = $nn_now;
	    
	    $this->modelTrans->insert_transm_mgmt($transData);
	    
// 	    echo "===================================== <br />";
// 	    _vardump($this->transinfo);
	    
	}
	
	/**
	 * 양도자의 상품을 종료 처리 한다.
	 */
	private function func_trans_event_end_proc()
	{
	    $nn_now = new Time('now');
	    
	    $upEnd['mod_id'] = $_SESSION['user_id'];
	    $upEnd['mod_datetm'] = $nn_now;
	    $upEnd['comp_cd'] = $this->data_buyinfo['COMP_CD'];
	    $upEnd['bcoff_cd'] = $this->data_buyinfo['BCOFF_CD'];
	    $upEnd['buy_event_sno'] = $this->data_buyinfo['BUY_EVENT_SNO'];
	    $upEnd['event_stat'] = "99"; // 종료됨
	    $upEnd['event_stat_rson'] = "61"; // 양도됨
	    
	    $this->modelPay->update_buy_event_mgmt_trans_end($upEnd);
	    
	    // 락커이 있을 경우 락커 관리에서도 종료 처리를 해야한다.
	    if ($this->data_buyinfo['LOCKR_SET'] == "Y")
	    {
	        if ($this->data_buyinfo['LOCKR_NO'] != '')
	        {
	            
	            $lockrData['comp_cd']          = $_SESSION['comp_cd'];
	            $lockrData['bcoff_cd']         = $_SESSION['bcoff_cd'];
	            $lockrData['lockr_knd']        = $this->data_buyinfo['LOCKR_KND'];
	            $lockrData['lockr_gendr_set']  = $this->data_buyinfo['LOCKR_GENDR_SET'];
	            $lockrData['lockr_no']         = $this->data_buyinfo['LOCKR_NO'];
	            $lockrData['buy_event_sno']    = $this->data_buyinfo['BUY_EVENT_SNO'];
	            $lockrData['mem_sno']          = $this->data_buyinfo['MEM_SNO'];
	            $lockrData['lockr_stat']       = "99"; // 종료 상태로 바꿈. 비우기를 따로 해야 사용가능 00 상태가 됨.
	            
	            $this->modelEvent->update_lockr_room_trans_end($lockrData);
	        }
	    }
	}
	
	/**
	 * 0일 경우 '' 을 리턴한다.
	 * @param string $var
	 * @return string
	 */
	
	private function zeroEmpty($var)
	{
	    $return_val = $var;
	    if($var == "0") $return_val = "";
	    
	    return $return_val;
	}
	
	/**
	 * '' 일 경우 0을 리턴한다.
	 * @param string $var
	 * @return string
	 */
	private function emptyZero($var)
	{
	    $return_val = $var;
	    if($var == "") $return_val = 0;
	    
	    return $return_val;
	}
	
	/**
	 * 재계산되어야할 상품들을 시작 종료일을 업데이트 처리한다.
	 */
	private function func_remake_buy_update()
	{
	    $nn_now = new Time('now');
	    
	    $udata['mod_id'] = $_SESSION['user_id'];
	    $udata['mod_datetm'] = $nn_now;
	    
	    if (count($this->after_buy) > 0)
	    {
	        for($i=0; $i < count($this->after_buy); $i++)
	        {
	            $udata['buy_event_sno'] = $this->after_buy[$i]['BUY_EVENT_SNO'];
	            $udata['exr_s_date'] = $this->after_buy[$i]['RE_EXR_S_DATE'];
	            $udata['exr_e_date'] = $this->after_buy[$i]['RE_EXR_E_DATE'];
	            
	            $this->modelPay->update_buy_event_mgmt_redate($udata);
	        }
	    }
	    
	}
	
	/**
	 * 이용권 상품 [락커 - 번호 없음]
	 */
	private function func_pt_no_lockr_empty()
	{
	    $this->set_db_data['event_stat'] = '01'; //예약됨
	    $this->set_db_data['event_stat_rson'] = "00"; // 정상
	    $this->set_db_data['exr_s_date'] = '';
	    $this->set_db_data['exr_e_date'] = '';
	    $this->pay_lockr_gendr_set =  '';
	}
	
	/**
	 * PT 상품
	 */
	private function func_pt_yes()
	{
	    $this->set_db_data['event_stat'] = '01'; //예약됨
	    $this->set_db_data['event_stat_rson'] = "00"; // 정상
	    
	    $this->set_db_data['exr_s_date'] = "";
	    $this->set_db_data['exr_e_date'] = "";
	    
	    //1회 수업 진행 금액 계산
	    // $this->onetime_pt_cost(); // Pay_lib 에서는 계산하지만 양도에서는 이전 양도자의 계산된 금액을 넣는다.
	}
	
	/**
	 * 이용권 상품
	 */
	private function func_pt_no()
	{
	    $chk_range_exr_date = false; // 운동시작일이 범위안에 없으면 false
	    // 그룹 카테고리 설정에 따라서 결제한 운동 시작일이 이용중, 예약됨 범위에 없는가 ?
	    // $sell_cate_code = $this->data_sell_event_info['1RD_CATE_CD'].$this->data_sell_event_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    $sell_cate_code = $this->data_sell_event_info['1RD_CATE_CD'].$this->data_sell_event_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    
	    // 이미 구매한 상품의 시작일 ~ 종료일 사이에 시작일 값이 존재하는지를 체크함.
	    if ($this->data_buy_event_info != null)
	    {
	        foreach($this->data_buy_event_info as $r)
	        {
	            if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
	            {
	                // 이곳에서 로직 구성해야함
	                // 운동시작일이 하나라도 범위안에 있으면 true;
	                // 하나라도 범위에 있으면 제일 마지막 적용된 종료일을 구해야한다.
	                
	                if (strtotime($this->pay_exr_s_date) >= strtotime($r['EXR_S_DATE']) && strtotime($this->pay_exr_s_date) <= strtotime($r['EXR_E_DATE']))
	                {
	                    $chk_range_exr_date = true;
	                    //$this->pay_exr_s_date = $r['EXR_E_DATE'];
	                }
	                
	                if ($chk_range_exr_date == true)
	                {
	                    $this->pay_exr_s_date = $r['EXR_E_DATE'];
	                }
	            }
	        }
	    }
	    
	    
	    
	    
	    if ($chk_range_exr_date == false)
	    {
	        if ($this->pay_exr_s_date == date('Y-m-d')) // 날짜가 지난 경우도 생김 날짜 계산으로 비교를 해야함
	        {
	            // 이용중/정상으로 셋팅 필요함 (그대로 시작일자를 설정함)
	            $this->set_db_data['event_stat'] = '00'; //이용중
	            $this->set_db_data['event_stat_rson'] = "00"; // 정상
	            $this->set_db_data['exr_s_date'] = $this->pay_exr_s_date;
	            
	        } else
	        {
	            // 예약됨/정상으로 셋팅 필요함 (그대로 시작일자를 설정함)
	            $this->set_db_data['event_stat'] = '01'; //예약됨
	            $this->set_db_data['event_stat_rson'] = "00"; // 정상
	            $this->set_db_data['exr_s_date'] = $this->pay_exr_s_date;
	        }
	    } else
	    {
	        //예약됨/정상으로 셋팅 필요함
	        $this->set_db_data['event_stat'] = '01'; //예약됨
	        $this->set_db_data['event_stat_rson'] = "00"; // 정상
	        //위에서 구한 내역중에 가장 큰 종료일 +1일에 시작일자를 설정함
	        $this->set_db_data['exr_s_date'] = date("Y-m-d", strtotime("+1 days", strtotime($this->pay_exr_s_date)));
	        
	    }
	    
	    // 판매상품에서 운동시작일을 적용하여 시작 종료일을 구함.
	    $this->calc_se_date();
	    
	    // 구매상품의 시작 종료일 뒤에 재배치 되어야 하는 상품들을 배열에 저장함.
	    $this->remake_buy_event_info();
	    return true;
	    
	}
	
	/**
	 * 운동 시작일 기준으로 판매상품의 조건을 이용하여 종료일을 구함.
	 */
	private function calc_se_date()
	{
	    $sdate = $this->set_db_data['exr_s_date']; // 운동 시작일
	    $uprod = $this->data_sell_event_info['USE_PROD']; // 기간
	    $uunit = $this->data_sell_event_info['USE_UNIT']; // 단위
	    
	    if ($uunit == 'M')
	    {
	        $plus_dd = "+" . $uprod . " months";
	    } else
	    {
	        $plus_dd = "+" . $uprod . " days";
	    }
	    
	    $calc_e_date = date("Y-m-d", strtotime($plus_dd, strtotime($sdate)));
	    $edate = date("Y-m-d", strtotime("-1 days", strtotime($calc_e_date)));
	    
	    $this->set_db_data['exr_e_date'] = $edate;
	}
	
	/**
	 * 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	 */
	private function remake_buy_event_info()
	{
	    
	    // $sell_cate_code = $this->data_sell_event_info['1RD_CATE_CD'].$this->data_sell_event_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
		$sell_cate_code = $this->data_sell_event_info['1RD_CATE_CD'].$this->data_sell_event_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    
	    $after_buy_sno_chk = false; // 하나라도 범위에 있으면 true 가 체크 되며 이후에 검색되는 상품들을 배열로 저장함.
	    $after_buy_array = array(); // 이후에 검색되는 상품들을 배열에 저장하기 위함.
	    $after_buy_i = 0; // 배열 증가 변수
	    
	    if ($this->data_buy_event_info != null)
	    {
	        
	        // 이미 구매한 상품의 시작일 ~ 종료일 사이에 시작일 값이 존재하는지를 체크함.
	        foreach($this->data_buy_event_info as $r)
	        {
	            //echo $r['1RD_CATE_CD'].$r['2RD_CATE_CD'] . ":::" . $sell_cate_code . "===" . $r['BUY_EVENT_SNO'] . "<br />";
	            
	            // if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
				if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
	            {
	                
	                // 이곳에서 로직 구성해야함
	                // 운동시작일이 하나라도 범위안에 있으면 true;
	                // 하나라도 범위에 있으면 제일 마지막 적용된 종료일을 구해야한다.
	                
	                if (strtotime($this->set_db_data['exr_s_date']) >= strtotime($r['EXR_S_DATE']) && strtotime($this->set_db_data['exr_s_date']) <= strtotime($r['EXR_E_DATE']))
	                {
	                    $after_buy_sno_chk = true;
	                }
	                
	                if ($after_buy_sno_chk == true )
	                {
	                    $after_buy_array[$after_buy_i] = $r;
	                    $after_buy_i++ ;
	                }
	                
	            }
	        }
	        
	        // 이미 구매한 상품의 시작일 ~ 종료일 사이에 시작일 값이 없다면 : 구매시작일 이후에 존재하는 값들을 변경 시켜야 한다.
	        if ($after_buy_sno_chk == false)
	        {
	            foreach($this->data_buy_event_info as $r)
	            {
	                if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
	                {
	                    if (strtotime($this->set_db_data['exr_s_date']) < strtotime($r['EXR_S_DATE']) )
	                    {
	                        $after_buy_array[$after_buy_i] = $r;
	                        $after_buy_i++ ;
	                    }
	                }
	            }
	        }
	        
	        $this->after_buy = $after_buy_array;
	    }
	    
	    $this->remake_buy_event_date();
	    
	}
	
	/**
	 * 재 적용해야할 운동상품들에 대한 시작, 종료일을 재 계산한다.
	 */
	private function remake_buy_event_date()
	{
	    $last_edate = $this->set_db_data['exr_e_date'];
	    if (count($this->after_buy) > 0)
	    {
	        for($i=0; $i < count($this->after_buy); $i++)
	        {
	            $remake_date = $this->remake_calc_se_date($last_edate, $this->after_buy[$i]['USE_PROD'], $this->after_buy[$i]['USE_UNIT'], $this->after_buy[$i]['ADD_SRVC_EXR_DAY']);
	            $last_edate = $remake_date['edate'];
	            
	            $this->after_buy[$i]['RE_EXR_S_DATE'] = $remake_date['sdate'];
	            $this->after_buy[$i]['RE_EXR_E_DATE'] = $remake_date['edate'];
	        }
	    }
	}
	
	/**
	 *
	 * @param string $last_edate 마지막 운동 종료일
	 * @param string $uuprod 기간
	 * @param string $uunut 단위
	 */
	private function remake_calc_se_date($last_edate,$uprod,$uunit,$add_days)
	{
	    // 마지막 운동 종료일에 +1을 더하여 운동 시작일을 구한다.
	    $sdate = date("Y-m-d", strtotime("+1 days", strtotime($last_edate)));
	    
	    // 위에서 구한 시작일에 종료일을 다시 구한다.
	    if ($uunit == 'M')
	    {
	        $plus_dd = "+" . $uprod . " months";
	    } else
	    {
	        $plus_dd = "+" . $uprod . " days";
	    }
	    
	    $calc_e_date = date("Y-m-d", strtotime($plus_dd, strtotime($sdate)));
	    $edate = date("Y-m-d", strtotime("-1 days", strtotime($calc_e_date)));
	    
	    // 종료일을 구한 뒤에 추가 정보가 있는지를 확인한다. (서비스로 준 날짜가 있는지를 확인하여 처리 해야한다.) TODO
	    $edate = date("Y-m-d", strtotime("+".$add_days." days", strtotime($edate)));
	    
	    $return_date['sdate'] = $sdate;
	    $return_date['edate'] = $edate;
	    
	    return $return_date;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 판매 상품 정보를 가져온다.
	 */
	private function func_sell_event_info()
	{
	    // 판매상품을 가져온다.
	    // 양도이기때문에 전 회원이 구매한 정보가 판매상품 정보이다.
	    // 판매 상품에서 아래에 해당되는 부분들을 바꿔야한다.
	    
	    // 이용기간 , 이용단위 ,
	    // 휴회_일 = 남은 휴회 가능 일
	    // 휴회 횟수 = 남은 휴회 가능 횟수
	    // 수업횟수 = 회원 정규 수업 남은 횟수
	    // 양도 가능 여부 = N
	    // 휴회 가능 여부 = N
	    
	    $get_transinfo = $this->trans_buy_info(); // re_sell_event_sno
	    $this->transinfo = $get_transinfo['refund_info'];
	    
	    if ($get_transinfo['refund_info']['re_sell_event_sno'] != '') :
	       $this->pay_sell_sno = 	$get_transinfo['refund_info']['re_sell_event_sno'];
	    endif ;
	    
	    $eData['comp_cd'] = $this->pay_comp_cd;
	    $eData['bcoff_cd'] = $this->pay_bcoff_cd;
	    $eData['sell_event_sno'] = $this->pay_sell_sno;
	    $sell_event_info = $this->modelPay->get_event_use_sno($eData);
	    $this->data_sell_event_info = $sell_event_info[0];
	    
	    $this->data_sell_event_info['USE_PROD'] = $get_transinfo['refund_info']['left_day_cnt'];
	    $this->data_sell_event_info['USE_UNIT'] = "D"; // 양도할때는 무조껀 일수로 양도가 된다.
	    $this->data_sell_event_info['DOMCY_DAY'] = $this->data_buyinfo['LEFT_DOMCY_POSS_DAY'];
	    $this->data_sell_event_info['DOMCY_CNT'] = $this->data_buyinfo['LEFT_DOMCY_POSS_CNT'];
	    $this->data_sell_event_info['CLAS_CNT'] = $this->data_buyinfo['MEM_REGUL_CLAS_LEFT_CNT'];
	    $this->data_sell_event_info['TRANSM_POSS_YN'] = "N"; // 양도된 상품은 다시 양도가 불가능 하다.
	    $this->data_sell_event_info['REFUND_POSS_YN'] = "N"; // 양도된 상품은 환불을 할 수가 없다.
	    
	    $this->set_db_data['1TM_CLAS_PRGS_AMT'] = $this->data_buyinfo['1TM_CLAS_PRGS_AMT'];
	    
	}
	
	/**
	 * 회원이 이미 구매한 상품을 가져온다. (이용중, 예약됨 상품만 )
	 */
	private function func_buy_event_info()
	{
	    $eData['comp_cd'] = $this->pay_comp_cd;
	    $eData['bcoff_cd'] = $this->pay_bcoff_cd;
	    $eData['mem_sno'] = $this->pay_mem_sno;
	    
	    $buy_event_info = $this->modelPay->get_buy_events_use_memsno($eData);
	    if(count($buy_event_info) > 0) $this->data_buy_event_info = $buy_event_info;
	}
	
	/**
	 * 결제 상품중에 출입 제한 조건이 있는지를 체크한다. array['type'] , ['result']
	 * 1. PT 관련 상품인지
	 * 2. 기간 및 PT 기간 상품인지
	 */
	private function func_acct_rtrct_check()
	{
	    $return_value['result'] = true;
	    // ACC_RTRCT_DV ( 00 : 무제한 / 01 : 1일1회입장 / 99 : 입장불가
	    if ($this->data_sell_event_info['ACC_RTRCT_DV'] != "99" && $this->data_sell_event_info['ACC_RTRCT_DV'] != "50")
	    {
	        if ($this->data_buy_event_info != null)
	        {
	            $return_value['result'] = $this->acc_rtrct_equal_check();
	        }
	    }
	    
	    $return_value['type'] = $this->clas_prod_check();
	    
	    return $return_value;
	}
	
	/**
	 * 판매상품과 구매한 (이용중,예약됨) 상품의 출입제한 조건이 같은지 체크한다.
	 * @return boolean
	 */
	private function acc_rtrct_equal_check()
	{
	    $return_value = true;
	    
	    $sell_event['ACC_RTRCT_DV'] = $this->data_sell_event_info['ACC_RTRCT_DV'];
	    $sell_event['ACC_RTRCT_MTHD'] = $this->data_sell_event_info['ACC_RTRCT_MTHD'];
	    
	    
	    foreach($this->data_buy_event_info as $r)
	    {
	        if ($r['ACC_RTRCT_DV'] != "99" && $r['ACC_RTRCT_DV'] != "50")
	        {
	            if ($r['ACC_RTRCT_DV'] != $sell_event['ACC_RTRCT_DV']) $return_value = false;
	            if ($r['ACC_RTRCT_MTHD'] != $sell_event['ACC_RTRCT_MTHD']) $return_value = false;
	        }
	    }
	    
	    return $return_value;
	}
	
	/**
	 * PT 상품인지, 이용권 및 PT 이용권 상품인지 체크한다.
	 * @return string (1: PT 관련 상품 / 2: 기간 및 PT 기간 상품
	 */
	private function clas_prod_check()
	{
	    $return_value = "";
	    ($this->zeroEmpty($this->data_sell_event_info['USE_PROD']) == "") ? $return_value = 1 : $return_value = 2;
	    return $return_value;
	}
	
	
	/**
	 * 결재관리 처리
	 */
	private function func_paymt_proc()
	{
	    $nn_now = new Time('now');
	    
	    $payData['buy_event_sno'] = $this->set_db_data['buy_sno'];; // 구매_상품_일련번호
	    $payData['sell_event_sno'] = $this->data_buyinfo['SELL_EVENT_SNO']; // 판매_상품_일련번호
	    $payData['send_event_sno'] = ""; // 보내기_상품_일련번호
	    $payData['recvb_sno'] = ""; // 미수금_일련번호
	    
	    $payData['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
	    $payData['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
	    $payData['1rd_cate_cd'] = $this->data_buyinfo['1RD_CATE_CD']; // 1차_카테고리_코드
	    $payData['2rd_cate_cd'] = $this->data_buyinfo['2RD_CATE_CD']; // 2차_카테고리_코드
	    $payData['clas_dv'] = $this->data_buyinfo['CLAS_DV']; // 수업_구분
	    $payData['mem_sno'] = $this->data_meminfo['MEM_SNO']; // 회원_일련번호
	    $payData['mem_id'] = $this->data_meminfo['MEM_ID']; //회원_아이디
	    $payData['mem_nm'] = $this->data_meminfo['MEM_NM']; //회원_명
	    $payData['sell_event_nm'] = $this->data_buyinfo['SELL_EVENT_NM']; // 판매_상품_명
	    $payData['paymt_stat'] = "00"; // 결제_상태 ( 00 : 결제 / 01 : 환불 / 99 : 취소 )
	    $payData['paymt_date'] = date("Y-m-d"); // 결제_일자
	    $payData['refund_date'] = ""; // 환불_일자
	    $payData['paymt_chnl'] = "P"; // 결제_채널 ( M : 모바일 / P : 데스크 / K : 키오스크 )
	    $payData['paymt_van_knd'] = "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 )
	    $payData['grp_cate_set'] = $this->data_buyinfo['GRP_CATE_SET']; // 그룹_카테고리_설정
	    $payData['lockr_set'] = $this->data_buyinfo['LOCKR_SET']; // 락커_설정
	    $payData['lockr_knd'] = $this->data_buyinfo['LOCKR_KND']; // 락커_종류
	    $payData['lockr_gendr_set'] = $this->data_buyinfo['LOCKR_GENDR_SET']; // 락커_성별_설정
	    $payData['lockr_no'] = ""; // 락커_번호
	    $payData['cre_id'] = $_SESSION['user_id']; // 등록_아이디
	    $payData['cre_datetm'] = $nn_now; // 등록_일시
	    $payData['mod_id'] = $_SESSION['user_id']; // 수정_아이디
	    $payData['mod_datetm'] = $nn_now; // 수정_일시
	    
	    // 카드일 경우
	    if ($this->pay_card_amt != '')
	    {
	        $amasno = new Ama_sno();
	        $pay_sno = $amasno->create_pay_sno();
	        
	        $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	        $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	        $payData['paymt_mthd'] = "01"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	        $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	        $payData['appno_sno'] = $this->pay_appno_sno; // 승인번호_일련번호
	        $payData['appno'] = $this->pay_appno; // 승인번호
	        $payData['paymt_amt'] = $this->pay_card_amt; // 결제_금액
	        
	        $this->modelPay->insert_paymt_mgmt_tbl($payData);
	        
	        $this->func_sales_proc($payData);
	    }
	    
	    // 현금일 경우
	    if ($this->pay_cash_amt != '')
	    {
	        $amasno = new Ama_sno();
	        $pay_sno = $amasno->create_pay_sno();
	        
	        $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	        $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	        $payData['paymt_mthd'] = "03"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	        $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	        $payData['appno_sno'] = ""; // 승인번호_일련번호
	        $payData['appno'] = ""; // 승인번호
	        $payData['paymt_amt'] = $this->pay_cash_amt; // 결제_금액
	        
	        $this->modelPay->insert_paymt_mgmt_tbl($payData);
	        
	        $this->func_sales_proc($payData);
	    }
	    
	    // TODO @@@ 계좌이체 , 미수금의 경우는 바로 결제가 등록 되지 않는다.
	    
	}
	
	/**
	 * 매출관리 처리
	 */
	private function func_sales_proc($payData)
	{
	    $amasno = new Ama_sno();
	    $sales_sno = $amasno->create_sales_sno();
	    $sales_dv = "60"; // 매출_구분 (양도비매출)
	    $sales_dv_rson = "60"; // 매출_구분_사유 (양도비매출)
	    $sales_mem_stat = "01"; // 매출_회원_상태 (현재회원)
	    
	    $sdata['sales_mgmt_sno'] = $sales_sno; // 매출_관리_일련번호
	    $sdata['paymt_mgmt_sno'] = $payData['paymt_mgmt_sno']; // 결제_관리_일련번호
	    $sdata['buy_event_sno'] = $payData['buy_event_sno']; // 구매_상품_일련번호
	    $sdata['sell_event_sno'] = $payData['sell_event_sno']; // 판매_상품_일련번호
	    $sdata['send_event_sno'] = ""; // TODO @@@ 보내기_상품_일련번호
	    $sdata['recvb_hist_sno'] = $payData['recvb_sno']; // 미수금_내역_일련번호
	    $sdata['paymt_van_sno'] = $payData['paymt_van_sno']; // 결제_VAN_일련번호
	    $sdata['comp_cd'] = $payData['comp_cd']; // 회사_코드
	    $sdata['bcoff_cd'] = $payData['bcoff_cd']; // 지점_코드
	    $sdata['1rd_cate_cd'] = $payData['1rd_cate_cd']; // 1차_카테고리_코드
	    $sdata['2rd_cate_cd'] = $payData['2rd_cate_cd']; // 2차_카테고리_콛,
	    $sdata['clas_dv'] = $payData['clas_dv']; // 회원_일련번호
	    $sdata['mem_sno'] = $payData['mem_sno']; // 회원_일련번호
	    $sdata['mem_id'] = $payData['mem_id']; // 회원_아이디
	    $sdata['mem_nm'] = $payData['mem_nm']; // 회원_명
	    $sdata['pthcr_id'] = $_SESSION['user_id']; // 판매강사_아이디
	    $sdata['sell_event_nm'] = $payData['sell_event_nm']; // 판매_상품_명
	    $sdata['paymt_stat'] = $payData['paymt_stat']; // 결제_상태
	    $sdata['paymt_mthd'] = $payData['paymt_mthd']; // 결제_수단
	    $sdata['acc_sno'] = $payData['acct_no']; // 계좌_번호
	    $sdata['appno'] = $payData['appno']; // 승인번호
	    $sdata['paymt_amt'] = $payData['paymt_amt']; // 결제_금액
	    $sdata['sales_dv'] = $sales_dv; // 매출_구분
	    $sdata['sales_dv_rson'] = $sales_dv_rson; // 매출_구분_사유
	    $sdata['sales_mem_stat'] = $sales_mem_stat; // 매출_회원_상태
	    $sdata['paymt_chnl'] = $payData['paymt_chnl']; // 결제_채널
	    $sdata['paymt_van_knd'] = $payData['paymt_van_knd']; // 결제_VAN_종류
	    $sdata['sales_aply_yn'] = "Y"; // 매출_적용_여부
	    $sdata['grp_cate_set'] = $payData['grp_cate_set']; // 그룹_카테고리_설정
	    $sdata['lockr_set'] = $payData['lockr_set']; // 락커_설정
	    $sdata['lockr_knd'] = $payData['lockr_knd']; // 락커_종류
	    $sdata['lockr_gendr_set'] = $payData['lockr_gendr_set']; // 락커_성별_설정
	    $sdata['lockr_no'] = $payData['lockr_no']; // 락커_번호
	    $sdata['cre_id'] = $payData['cre_id']; // 등록_아이디
	    $sdata['cre_datetm'] = $payData['cre_datetm']; // 등록_일시
	    $sdata['mod_id'] = $payData['mod_id']; // 수정_아이디
	    $sdata['mod_datetm'] = $payData['mod_datetm']; // 수정_일시
	    
	    $this->modelPay->insert_sales_mgmt_tbl($sdata);
	    
	}
	
	/**
	 * 구매 내역을 처리한다.
	 */
	private function func_buy_proc()
	{
	    /**
	     * pay_comp_cd : comp_cd <br />
	     * pay_bcoff_cd : bcoff_cd <br />
	     * pay_mem_id : 회원 아이디 (필수) <br />
	     * pay_sell_sno : 판매상품 일련번호 (필수) <br />
	     * pay_appno_sno : 승인번호 일련번호 (카드결제 금액이 있을 경우에는 필수) <br />
	     * pay_appno : 승인번호 <br />
	     * pay_card_amt : 카드결제 금액 <br />
	     * pay_acct_amt : 계좌이체 금액 <br />
	     * pay_acct_no : 계좌번호 <br />
	     * pay_cash_amt : 현금결제 금액 <br />
	     * pay_misu_amt : 미수금액 <br />
	     * pay_exr_s_date : 희망 운동 시작일 <br />
	     * @param array $initVar
	     */
	    
	    $nn_now = new Time('now');
	    
	    $onetime_clas_cost = 0;
	    if (isset($this->set_db_data['1TM_CLAS_PRGS_AMT'])) $onetime_clas_cost = $this->set_db_data['1TM_CLAS_PRGS_AMT'];
	    
	    $bdata['buy_event_sno'] 	= $this->set_db_data['buy_sno'];  // 구매_상품_일련번호,
	    $bdata['sell_event_sno'] 	= $this->data_sell_event_info['SELL_EVENT_SNO'];  // 판매_상품_일련번호,
	    $bdata['send_event_sno'] 	= "";  // 보내기_상품_일련번호, TODO
	    $bdata['comp_cd'] 			= $this->pay_comp_cd;  // 회사_코드,
	    $bdata['bcoff_cd'] 			= $this->pay_bcoff_cd;  // 지점_코드,
	    $bdata['1rd_cate_cd'] 				= $this->data_sell_event_info['1RD_CATE_CD'];  // 1차_카테고리_코드,
	    $bdata['2rd_cate_cd'] 				= $this->data_sell_event_info['2RD_CATE_CD']; // 2차_카테고리_코드,
	    $bdata['mem_sno'] 			= $this->data_tmeminfo['MEM_SNO']; // $this->pay_mem_sno;  // 회원_일련번호,
	    $bdata['mem_id'] 			= $this->data_tmeminfo['MEM_ID']; // $this->pay_mem_id;  // 회원_아이디,
	    $bdata['mem_nm'] 			= $this->data_tmeminfo['MEM_NM']; // $this->pay_mem_nm;  // 회원_명,
	    $bdata['stchr_id'] 			                = $this->data_buyinfo['STCHR_ID'];  // 수업강사_아이디,
	    $bdata['stchr_nm'] 			                = $this->data_buyinfo['STCHR_ID'];  // 수업강사_명,
	    $bdata['ptchr_id'] 							= $this->data_buyinfo['PTCHR_ID']; // $_SESSION['user_id'];  // 판매강사_아이디,
	    $bdata['ptchr_nm'] 							= $this->data_buyinfo['PTCHR_NM']; // $_SESSION['user_name'];  // 판매강사_명,
	    $bdata['sell_event_nm'] 			= $this->data_sell_event_info['SELL_EVENT_NM'];  // 판매_상품_명,
	    $bdata['sell_amt']  				= $this->data_sell_event_info['SELL_AMT']; // 판매_금액,
	    $bdata['use_prod']  				= $this->data_sell_event_info['USE_PROD']; // 이용_기간,
	    $bdata['use_unit']  				= $this->data_sell_event_info['USE_UNIT'];// 이용_단위,
	    $bdata['clas_cnt']  				= $this->data_sell_event_info['CLAS_CNT']; // 수업_횟수,
	    $bdata['domcy_day']  				= $this->data_sell_event_info['DOMCY_DAY']; // 휴회_일,
	    $bdata['domcy_cnt']  				= $this->data_sell_event_info['DOMCY_CNT']; // 휴회_횟수,
	    $bdata['domcy_poss_event_yn']  		= $this->data_sell_event_info['DOMCY_POSS_EVENT_YN']; // 휴회_가능_상품_여부,
	    $bdata['acc_rtrct_dv']  			= $this->data_sell_event_info['ACC_RTRCT_DV']; // 출입_제한_구분,
	    $bdata['acc_rtrct_mthd']  			= $this->data_sell_event_info['ACC_RTRCT_MTHD']; // 출입_제한_방법,
	    $bdata['clas_dv']  					= $this->data_sell_event_info['CLAS_DV']; // 수업_구분,
	    $bdata['event_img']  				= $this->data_sell_event_info['EVENT_IMG']; // 상품_이미지,
	    $bdata['event_icon'] 				= $this->data_sell_event_info['EVENT_ICON']; // 상품_아이콘,
	    $bdata['grp_clas_psnnl_cnt'] 		= $this->data_sell_event_info['GRP_CLAS_PSNNL_CNT']; // 그룹_수업_인원_수,
	    $bdata['event_stat'] 		= $this->set_db_data['event_stat'];  // 상품_상태,
	    $bdata['event_stat_rson'] 	= "62"; // $this->set_db_data['event_stat_rson'];  // 상품_상태_사유, 양도받은 상품은 양도됨 상태로 사유를 고정한다.
	    $bdata['exr_s_date'] 		= $this->set_db_data['exr_s_date'];  // 운동_시작_일자,
	    $bdata['exr_e_date'] 		= $this->set_db_data['exr_e_date']; // 운동_종료_일자,
	    $bdata['left_domay_poss_day'] 		= $this->data_sell_event_info['DOMCY_DAY'];;  // 남은_휴회_가능_일,
	    $bdata['left_domcy_poss_cnt'] 		= $this->data_sell_event_info['DOMCY_CNT'];  // 남은_휴회_가능_횟수,
	    $bdata['buy_datetm'] 						= $nn_now; // 구매_일시,
	    $bdata['real_sell_amt'] 	= 0; // $this->pay_real_sell_amt;  // 실_판매_금액, 양도받은 상품은 실 판매금액이 0원이다.
	    $bdata['buy_amt'] 			= 0; // $this->set_db_data['buy_amt'];  // 구매_금액, 양도받은 상품은 구매 금액도 0원이다.
	    $bdata['recbc_amt'] 		= 0; // $this->set_db_data['recvb_amt'];  // 미수_금액, 양도받은 상품은 미수금액이 0원이다.
	    $bdata['add_srvc_exr_day'] 					= 0; // 추가_서비스_운동_일,
	    $bdata['add_srvc_clas_cnt'] 				= 0; // 추가_서비스_수업_횟수,
	    $bdata['add_domcy_day'] 					= 0;  // 추가_휴회_일,
	    $bdata['add_domcy_cnt'] 					= 0; // 추가_휴회_횟수,
	    $bdata['srvc_clas_left_cnt'] 				= 0; // 서비스_수업_남은_횟수,
	    $bdata['srvc_clas_prgs_cnt'] 				= 0; // 서비스_수업_진행_횟수,
	    $bdata['1tm_clas_prgs_amt'] 				= $onetime_clas_cost; // 1회_수업_진행_금액,
	    $bdata['mem_regul_clas_left_cnt'] 	= $this->data_sell_event_info['CLAS_CNT']; // 회원_정규_수업_남은_횟수,
	    $bdata['mem_regul_clas_prgs_cnt'] 			= 0; // 회원_정규_수업_진행_횟수,
	    $bdata['ori_exr_s_date'] 	= $this->set_db_data['exr_s_date']; // 원_운동_시작_일자,
	    $bdata['ori_exr_e_date'] 	= $this->set_db_data['exr_e_date'];  // 원_운동_종료_일자,
	    $bdata['transm_poss_yn'] 					="Y"; // 양도_가능_여부,
	    $bdata['refund_poss_yn'] 					="Y"; // 환불_가능_여부,
	    $bdata['grp_cate_set'] 				= $this->data_sell_event_info['GRP_CATE_SET']; // 그룹_카테고리_설정,
	    $bdata['lockr_set'] 				= $this->data_sell_event_info['LOCKR_SET']; // 락커_설정,
	    $bdata['lockr_knd'] 				= $this->data_sell_event_info['LOCKR_KND']; // 락커_종류,
	    $bdata['lockr_gendr_set'] 	= ""; // $this->pay_lockr_gendr_set; // 락커_성별_설정, 양도받은 상품은 락커 선택을 할 수 없다.
	    $bdata['lockr_no'] 			= ""; //$this->pay_lockr_no; // 락커_번호, 양도받은 상품은 락커 선택을 할 수 없다.
	    $bdata['cre_id'] 			= $_SESSION['user_id'];  // 등록_아이디,
	    $bdata['cre_datetm'] 		= $nn_now;  // 등록_일시,
	    $bdata['mod_id'] 			= $_SESSION['user_id']; // 수정_아이디,
	    $bdata['mod_datetm'] 		= $nn_now;  // 수정_일시
	    
	    $this->modelPay->insert_buy_event_mgmt($bdata);
	    
	    // 락커은 예약처리 되기때문에 아래를 실행 하지 않아도 된다.
	    // 락커 처리
	    /*
	    if ($bdata['lockr_set'] == "Y" && $bdata['event_stat'] == "00")
	    {
	        $lockr_data['comp_cd']         = $bdata['comp_cd'];
	        $lockr_data['bcoff_cd']        = $bdata['bcoff_cd'];
	        $lockr_data['lockr_knd']       = $bdata['lockr_knd'];
	        $lockr_data['lockr_gendr_set'] = $bdata['lockr_gendr_set'];
	        $lockr_data['lockr_no']        = $bdata['lockr_no'];
	        
	        $lockr_data['buy_event_sno']   = $bdata['buy_event_sno'];
	        $lockr_data['mem_sno']         = $bdata['mem_sno'];
	        $lockr_data['mem_nm']          = $bdata['mem_nm'];
	        $lockr_data['lockr_use_s_date'] = $bdata['exr_s_date'];
	        $lockr_data['lockr_use_e_date'] = $bdata['exr_e_date'];
	        $lockr_data['lockr_stat']      = "01"; // 01 이용중
	        
	        $this->modelPay->update_lockr_room($lockr_data);
	    }
	    */
	    
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}