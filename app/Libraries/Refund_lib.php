<?php
namespace App\Libraries;

use App\Models\PayModel;
use CodeIgniter\I18n\Time;
use App\Models\MemModel;
use App\Models\ClasModel;
use App\Models\EventModel;

class Refund_lib {
	
    /**
     * COMP_CD
     * @var string
     */
	private $pay_comp_cd = "";
	
	/**
	 * BCOFF_CD
	 * @var string
	 */
	private $pay_bcoff_cd = "";
    
	/**
	 * 회원 일련번호
	 * @var string
	 */
	private $pay_mem_sno = "";
	
	/**
     * 회원 아이디
     * @var string
     */
	private $pay_mem_id = "";
	
	/**
     * 회원 명
     * @var string
     */
	private $pay_mem_nm = "";
	
	/**
	 * 판매상품 일련번호
	 * @var string
	 */
    private $pay_sell_sno = "";
    
    /**
     * 구매상품 일련번호
     * @var string
     */
    private $pay_buy_sno = "";
    
    /**
     * 승인번호 일련번호
     * @var string
     */
    private $pay_appno_sno = "";
    
    /**
     * 승인번호
     * @var string
     */
    private $pay_appno = "";
    
    /**
     * 카드 결제 금액
     * @var string
     */
    private $pay_card_amt = "";
    
    /**
     * 계좌 이제 금액
     * @var string
     */
    private $pay_acct_amt = "";
    
    /**
     * 현금 결제 금액
     * @var string
     */
    private $pay_cash_amt = "";
    
    /**
     * 미수 금액
     * @var string
     */
    private $pay_misu_amt = "";
    
    /**
     * 계좌 이체 번호
     * @var string
     */
    private $pay_acct_no = "";
    
    /**
     * 희망 운동 시작일
     * @var string
     */
    private $pay_exr_s_date = "";
    
    /**
     * 실제 구매 금액
     * @var string
     */
    private $pay_real_sell_amt = "";
    
    /**
     * 라커 번호
     * @var string
     */
    private $pay_lockr_no = "";
    
    /**
     * 라커 성별
     * @var string
     */
    private $pay_lockr_gendr_set = "";
    
    /**
     * 환불 구매 관리 일련번호 배열
     * @var array
     */
    private $pay_refund_paymt_mgmt_sno = array();
    
    
    private $pay_deposit_appno_sno = "";
    private $pay_deposit_appno = "";
    private $pay_deposit_card_amt = "";
    private $pay_deposit_cash_amt = "";
    
    private $pay_etc_appno_sno = "";
    private $pay_etc_appno = "";
    private $pay_etc_card_amt = "";
    private $pay_etc_cash_amt = "";
    
    
    /**
     * 수행 결과를 리턴한다.
     * array['result_msg'] String
     * array['result'] boolen
     * @var array true , false 를 리턴한다.
     */
    private $return_pay_result = array('result_msg'=>'실패','result'=>false);
    
    /**
     * PayModel
     * @var Object
     */
    private $modelPay;
    
    /**
     * 환불관리번호
     * @var string
     */
    private $refund_mgmt_sno = "";
    
    /**
     * 판매상품 정보
     * @var array
     */
    private $data_sell_event_info;
    
    /**
     * 예약, 이용 상품에 대한 정보
     * @var array
     */
    private $data_buy_event_info = null;
    
    /**
     * 구매 회원 정보
     * @var array
     */
    private $data_mem_info;
    
    /**
     * 구매한 상품 정보 (1개)
     * @var array
     */
    private $data_buy_info;
    
    /**
     * 이전 미수 내역 정보
     * @var array
     */
    private $data_misu_info;
    
    /**
     * 이전 매출 내역 정보
     * @var array
     */
    private $data_sales_info;
    
    /**
     * database에 넣을 데이터 set
     * @var array
     */
    private $set_db_data;
    
    /**
     * 수행결과 여부
     * @var boolean
     */
    private $is_possable = false;
    
    /**
     * 구매할 운동상품으로 인해 재조종되어야할 상품 배열
     * @var array
     */
    private $after_buy = array();
    
    /**
     * 교체환불일 경우 Y 로 셋팅된다.
     * @var string
     */
    private $refund_issue = "N";
    
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
	public function __construct($initVar) {
	    $this->pay_isset($initVar);
	    $this->modelPay = new PayModel();
	    
	    /*
            TRUNCATE TABLE buy_event_mgmt_tbl;
            TRUNCATE TABLE paymt_mgmt_tbl;
            TRUNCATE TABLE recvb_hist_tbl;
            TRUNCATE TABLE sales_mgmt_tbl;
	     */
	}
	
	/**
	 * 배열에 값이 있는지를 검사 하여 private 변수에 할당
	 */
	private function pay_isset($init_array)
	{
	    if(isset($init_array['pay_comp_cd']))      $this->pay_comp_cd =    $this->zeroEmpty($init_array['pay_comp_cd']);
	    if(isset($init_array['pay_bcoff_cd']))     $this->pay_bcoff_cd =   $this->zeroEmpty($init_array['pay_bcoff_cd']);
	    if(isset($init_array['pay_mem_sno']))       $this->pay_mem_sno =     $this->zeroEmpty($init_array['pay_mem_sno']);
	    if(isset($init_array['pay_mem_id']))       $this->pay_mem_id =     $this->zeroEmpty($init_array['pay_mem_id']);
	    if(isset($init_array['pay_mem_nm']))       $this->pay_mem_nm =     $this->zeroEmpty($init_array['pay_mem_nm']);
	    if(isset($init_array['pay_sell_sno']))     $this->pay_sell_sno =   $this->zeroEmpty($init_array['pay_sell_sno']);
	    if(isset($init_array['pay_buy_sno']))     $this->pay_buy_sno =   $this->zeroEmpty($init_array['pay_buy_sno']);
	    if(isset($init_array['pay_appno_sno']))    $this->pay_appno_sno =  $this->zeroEmpty($init_array['pay_appno_sno']);
	    if(isset($init_array['pay_appno']))        $this->pay_appno =      $this->zeroEmpty($init_array['pay_appno']);
	    if(isset($init_array['pay_card_amt']))     $this->pay_card_amt =   $this->zeroEmpty($init_array['pay_card_amt']);
	    if(isset($init_array['pay_acct_amt']))     $this->pay_acct_amt =   $this->zeroEmpty($init_array['pay_acct_amt']);
	    if(isset($init_array['pay_cash_amt']))     $this->pay_cash_amt =   $this->zeroEmpty($init_array['pay_cash_amt']);
	    if(isset($init_array['pay_misu_amt']))     $this->pay_misu_amt =   $this->zeroEmpty($init_array['pay_misu_amt']);
	    if(isset($init_array['pay_acct_no']))      $this->pay_acct_no =    $this->zeroEmpty($init_array['pay_acct_no']);
	    if(isset($init_array['pay_exr_s_date']))   $this->pay_exr_s_date = $this->zeroEmpty($init_array['pay_exr_s_date']);
	    if(isset($init_array['pay_real_sell_amt']))$this->pay_real_sell_amt = $this->zeroEmpty($init_array['pay_real_sell_amt']);
	    if(isset($init_array['pay_lockr_no']))     $this->pay_lockr_no = $this->zeroEmpty($init_array['pay_lockr_no']);
	    if(isset($init_array['pay_lockr_gendr_set']))     $this->pay_lockr_gendr_set = $this->zeroEmpty($init_array['pay_lockr_gendr_set']);
	    
	    if(isset($init_array['pay_refund_paymt_mgmt_sno']))     $this->pay_refund_paymt_mgmt_sno = $this->zeroEmpty($init_array['pay_refund_paymt_mgmt_sno']);
	    
	    
	    if(isset($init_array['pay_deposit_appno_sno']))    $this->pay_deposit_appno_sno =  $this->zeroEmpty($init_array['pay_deposit_appno_sno']);
	    if(isset($init_array['pay_deposit_appno']))        $this->pay_deposit_appno =      $this->zeroEmpty($init_array['pay_deposit_appno']);
	    if(isset($init_array['pay_deposit_card_amt']))     $this->pay_deposit_card_amt =   $this->zeroEmpty($init_array['pay_deposit_card_amt']);
	    if(isset($init_array['pay_deposit_cash_amt']))     $this->pay_deposit_cash_amt =   $this->zeroEmpty($init_array['pay_deposit_cash_amt']);
	    
	    if(isset($init_array['pay_etc_appno_sno']))    $this->pay_etc_appno_sno =  $this->zeroEmpty($init_array['pay_etc_appno_sno']);
	    if(isset($init_array['pay_etc_appno']))        $this->pay_etc_appno =      $this->zeroEmpty($init_array['pay_etc_appno']);
	    if(isset($init_array['pay_etc_card_amt']))     $this->pay_etc_card_amt =   $this->zeroEmpty($init_array['pay_etc_card_amt']);
	    if(isset($init_array['pay_etc_cash_amt']))     $this->pay_etc_cash_amt =   $this->zeroEmpty($init_array['pay_etc_cash_amt']);
	}
	
	/**
	 * 환불 정보를 가져온다.
	 */
	public function refund_info()
	{
	    $this->func_get_buy_event_info(); // 구매 정보를 가져온다.
	    $this->func_mem_info(); // 회원 정보를 가져온다.
	    
	    $this->func_refund_info(); // 환불 정보를 셋팅한다
	    
	    $this->return_pay_result['refund_info'] = $this->set_db_data;
	    $this->return_pay_result['result_msg'] = "성공";
	    $this->return_pay_result['result'] = $this->is_possable;
	}
	
	/**
	 * 환불을 실행한다.
	 */
	public function refund_run($refund_issue)
	{
	    $this->refund_issue = $refund_issue;
	    
	    $this->func_get_buy_event_info(); // 구매 정보를 가져온다.
	    $this->func_mem_info(); // 회원 정보를 가져온다.
	    
	    $this->func_refund_info(); // 환불 정보를 셋팅한다.
	    
	    // 환불하기전에 이용료 결제 부분을 실행한다.
	    if ($this->pay_card_amt != '' || $this->pay_card_amt != '') // 예약상품일 경우에는 이용료 결제 부분이 존재하지 않는다. 다만 위약금 등이 있을경우 고려 해야한다.
	    {
	        
	    	$this->func_refund_paymt_proc();
	    }
	    
	    // 환불하기전에 위약금 결제 부분을 실행한다.
	    if ($this->pay_deposit_card_amt != '' || $this->pay_deposit_cash_amt != '')
	    {
	        $this->func_refund_deposit_paymt_proc();
	        $this->return_pay_result['refund_deposit'] = "Y";
	    }
	    
	    // 환불하기전에 위약금 결제 부분을 실행한다.
	    if ($this->pay_etc_card_amt != '' || $this->pay_etc_cash_amt != '')
	    {
	        $this->func_refund_etc_paymt_proc();
	        $this->return_pay_result['refund_etc'] = "Y";
	    }
	    
	    
	    // 나머지 환불을 실행한다.
	    $this->func_refund_cancel_paymt_proc();
	    
	    // 환불 내역에 정보를 저장한다.
	    $this->func_refund_mgmt();
	    
	    // 해당 상품을 종료 한다.
	    $this->func_refund_event();
	    
	    // 종료회원인시를 체크하여 업데이트 한다.
	    $this->func_end_mem();
	    
	    $is_possable = true;
	    $this->is_possable = $is_possable;
	    $this->return_pay_result['result_msg'] = "성공";
	    $this->return_pay_result['result'] = $this->is_possable;
	    
	    $this->return_pay_result['buy_info'] = $this->data_buy_info;
	    $this->return_pay_result['mem_info'] = $this->data_mem_info;
	}
	
    private function func_end_mem()
    {
        $nn_now = new Time('now');
        
        $modelEvent = new EventModel();
        $modelMem = new MemModel();
        
        $eData['comp_cd'] = $this->pay_comp_cd;
        $eData['bcoff_cd'] = $this->pay_bcoff_cd;
        $eData['mem_sno'] = $this->pay_mem_sno;
        $chk_end_counter = $modelEvent->end_chk_event_mem_sno($eData);
        
        if ($chk_end_counter == 0)
        {
            $upVar['mem_stat'] = '90'; // 종료회원
            $upVar['mem_sno'] = $this->pay_mem_sno;
            $upVar['end_datetm'] = $nn_now;
            $upVar['mod_id'] = $_SESSION['user_id'];
            $upVar['mod_datetm'] = $nn_now;
            
            $modelMem->update_mem_end($upVar);
        }
    }
	
	/**
	 * 구매실행한 성공 여부를 리턴한다.
	 */
	public function refund_result()
	{
	    $this->return_pay_result['set_data'] = $this->set_db_data;
	    return $this->return_pay_result;
	}
	
	/**
	 * 환불관리번호 반환
	 * @return string
	 */
	public function get_refund_mgmt_sno()
	{
	    return $this->refund_mgmt_sno;
	}
	
	/**
	 * 환불 내역에 정보를 저장한다.
	 */
	private function func_refund_mgmt()
	{
		$nn_now = new Time('now');
		
		// REFUND_MGMT_SNO 생성 (BUY_EVENT_SNO를 기반으로)
		$this->refund_mgmt_sno = 'RF' . substr($this->pay_buy_sno, 2);
		
		$rfData['buy_event_sno'] = $this->pay_buy_sno; // 구매_상품_일련번호
		$rfData['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
		$rfData['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
		$rfData['sell_event_nm'] = $this->data_buy_info['SELL_EVENT_NM']; // 판매_상품_명
		$rfData['mem_sno'] = $this->pay_mem_sno; // 회원_일련번호
		$rfData['mem_id'] = $this->pay_mem_id; // 회원_아이디
		$rfData['mem_nm'] = $this->pay_mem_nm; // 회원-명
		$rfData['use_prod'] = $this->data_buy_info['USE_PROD']; // 사용_기간
		$rfData['use_unit'] = $this->data_buy_info['USE_UNIT']; // 사용_단위
		$rfData['clas_cnt'] = $this->data_buy_info['CLAS_CNT']; // 수업_횟수 ???????
		$rfData['exr_s_date'] = $this->set_db_data['exr_s_date']; // 원 운동 시작일
		$rfData['exr_e_date'] = $this->set_db_data['exr_e_date']; // 원 운동 종료일
		$rfData['total_exr_day_cnt'] = $this->set_db_data['total_exr_day_cnt']; // 총_운동_일_수
		$rfData['1tm_exr_amt'] = $this->set_db_data['1tm_exr_amt']; // 1회_운동_금액
		$rfData['use_day_cnt'] = $this->set_db_data['use_day_cnt']; // 사용_일_수
		
		$rfData['1tm_clas_amt'] = $this->data_buy_info['1TM_CLAS_PRGS_AMT']; // 1회_운동_금액
		$rfData['use_clas_cnt'] = $this->data_buy_info['MEM_REGUL_CLAS_PRGS_CNT'] + $this->data_buy_info['SRVC_CLAS_PRGS_CNT']; // 사용_수업_횟수
		
		$rfData['use_amt'] = $this->set_db_data['use_amt']; // 사용_금액
		$rfData['buy_amt'] = $this->set_db_data['buy_amt']; // 구매_금액
		$rfData['rerund_amt'] = $this->set_db_data['refund_amt']; // 환불_금액
		
		$rfData['pnalt_amt'] = ""; // 위약_금액
		$rfData['etc_amt'] = ""; // 기타_금액
		
		$rfData['cre_id'] = $_SESSION['user_id']; // 등록_아이디
		$rfData['cre_datetm'] = $nn_now; // 등록_일시
		$rfData['mod_id'] = $_SESSION['user_id']; // 수정_아이디
		$rfData['mod_datetm'] = $nn_now; // 수정_일시
		
		$this->modelPay->insert_refund_mgmt_tbl($rfData);
	}
	
	/**
	 * 해당 상품을 종료 한다.
	 */
	private function func_refund_event()
	{
		$nn_now = new Time('now');
		
		$uEdata['event_stat'] = "99";
		
		if ($this->refund_issue == "Y")
		{
		    $uEdata['event_stat_rson'] = "51"; // [환불(교체)]
		} else 
		{
		    $uEdata['event_stat_rson'] = "81"; // [환불]
		}
		
		
		if ($this->data_buy_info['EXR_E_DATE'] != "")
		{
		    $uEdata['exr_e_date'] = $this->data_buy_info['EXR_E_DATE'];
		} else 
		{
		    $uEdata['exr_e_date'] = date('Y-m-d');
		}
		
		$uEdata['mod_id'] = $_SESSION['user_id'];
		$uEdata['mod_datetm'] = $nn_now;
		$uEdata['comp_cd'] = $_SESSION['comp_cd'];
		$uEdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
		$uEdata['mem_sno'] = $this->pay_mem_sno;
		$uEdata['buy_event_sno'] = $this->pay_buy_sno;
		
		$this->modelPay->update_buy_event_cancel_tbl($uEdata);
	}
	
	private function func_refund_info()
	{
	    
	    
	    
	    if ($this->data_buy_info['CLAS_DV'] == "21" || $this->data_buy_info['CLAS_DV'] == "22") // 21 : PT , 22 : 골프 PT
	    {
	        $this->subfunc_refund_info_pt(); // PT 관련 환불금액 셋팅
	    } else 
	    {
	        $this->subfunc_refund_info_pt_no(); // 이용권 관련 환불금액 셋팅
	    }
	}
	
	/**
	 * PT 관련 환불금액 셋팅
	 */
	private function subfunc_refund_info_pt()
	{
	    
	    $clasModel = new ClasModel();
	    $uamt['comp_cd'] = $this->pay_comp_cd;
	    $uamt['bcoff_cd'] = $this->pay_bcoff_cd;
	    $uamt['buy_event_sno'] = $this->pay_buy_sno;
	    $use_amt_info = $clasModel->use_amt_pt_clas($uamt);
	    
	    $this->set_db_data['buy_amt'] = $this->data_buy_info['BUY_AMT'];
	    $this->set_db_data['use_amt'] = $use_amt_info[0]['use_amt'];
	    $this->set_db_data['refund_amt'] = $this->set_db_data['buy_amt'] - $this->set_db_data['use_amt'];
	    
	    $this->set_db_data['exr_refund_date'] = date('Y-m-d');
	    $this->set_db_data['exr_s_date'] = "";
	    $this->set_db_data['exr_e_date'] = "";
	    $this->set_db_data['total_exr_day_cnt'] = 0;
	    $this->set_db_data['use_day_cnt'] = 0;
	    $this->set_db_data['left_day_cnt'] = 0;
	    $this->set_db_data['1tm_exr_amt'] = 0;
	    
	    $this->set_db_data['clas_cnt'] = $this->data_buy_info['CLAS_CNT']; // 정규수업 계약 횟수
	    $this->set_db_data['mem_regul_clas_left_cnt'] = $this->data_buy_info['MEM_REGUL_CLAS_LEFT_CNT']; // 정규수업 남은 횟수
	    $this->set_db_data['mem_regul_clas_prgs_cnt'] = $this->data_buy_info['MEM_REGUL_CLAS_PRGS_CNT']; // 정규수업 진행 횟수
	    $this->set_db_data['1tm_clas_prgs_amt'] = $this->data_buy_info['1TM_CLAS_PRGS_AMT']; // 1회 수업 진행금액
	}
	
	/**
	 * 이용권 관련 환불금액 셋팅
	 */
	private function subfunc_refund_info_pt_no()
	{
	    $this->set_db_data['exr_refund_date'] = date('Y-m-d');
	    
	    // $this->set_db_data['exr_s_date'] : 운동 시작일
	    // $this->set_db_data['exr_e_date'] : 운동 종료일
	    $this->refund_calc_buy_edate();
	    
	    // 총 운동일수
	    $this->set_db_data['total_exr_day_cnt'] = $this->refund_calc_days($this->set_db_data['exr_s_date'],$this->set_db_data['exr_e_date']);
	    
	    // 오늘까지의 운동 일수
	    
	    if ($this->data_buy_info['EVENT_STAT'] == "00")
	    {
	        $this->set_db_data['use_day_cnt'] = $this->refund_calc_days($this->set_db_data['exr_s_date'],$this->set_db_data['exr_refund_date']);
	    } else
	    {
	        $this->set_db_data['use_day_cnt'] = 0;
	    }
	    
	    $this->set_db_data['left_day_cnt'] = $this->set_db_data['total_exr_day_cnt'] - $this->set_db_data['use_day_cnt'];
	    
	    // 총기간 대비 구매한 금액에서 1일의 금액을 계산한다.
	    $this->set_db_data['1tm_exr_amt'] = $this->onedays_amt();
	    
	    // 구매금액
	    $this->set_db_data['buy_amt'] = $this->data_buy_info['BUY_AMT'];
	    
	    // 사용금액
	    $this->set_db_data['use_amt'] = floor($this->set_db_data['1tm_exr_amt'] * $this->set_db_data['use_day_cnt']);
	    
	    // 환불금액
	    $this->set_db_data['refund_amt'] = $this->set_db_data['buy_amt'] - $this->set_db_data['use_amt'];
	    
	    $this->set_db_data['clas_cnt'] = $this->data_buy_info['CLAS_CNT']; // 정규수업 계약 횟수
	    $this->set_db_data['mem_regul_clas_left_cnt'] = $this->data_buy_info['MEM_REGUL_CLAS_LEFT_CNT']; // 정규수업 남은 횟수
	    $this->set_db_data['mem_regul_clas_prgs_cnt'] = $this->data_buy_info['MEM_REGUL_CLAS_PRGS_CNT']; // 정규수업 진행 횟수
	    $this->set_db_data['1tm_clas_prgs_amt'] = $this->data_buy_info['1TM_CLAS_PRGS_AMT']; // 1회 수업 진행금액
	}
	
	
	/**
	 * 나머지 환불을 실행한다. - Update 처리
	 */
	private function func_refund_cancel_paymt_proc()
	{
	    $nn_now = new Time('now');
	    
	    foreach ($this->pay_refund_paymt_mgmt_sno as $key => $value)
	    {
	        // 업데이트할 정보 내용
	        
	        $upPaymt['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
	        $upPaymt['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
	        $upPaymt['mem_sno'] = $this->pay_mem_sno; // 회원_일련번호
	        $upPaymt['paymt_mgmt_sno'] = $key;
	        
	        $upPaymt['refund_date'] = $this->set_db_data['exr_refund_date']; // 환불 일자
	        $upPaymt['paymt_stat'] = "01";
	        $upPaymt['mod_id'] = $_SESSION['user_id']; // 수정_아이디
	        $upPaymt['mod_datetm'] = $nn_now; // 수정_일시
	        
	        
	        // 매출에 Insert 할 셋팅 정보
	        $getPaymt = $this->modelPay->get_paymt_mgmt_cancel_tbl($upPaymt);
	        
	        $payData['paymt_mgmt_sno'] = $getPaymt[0]['PAYMT_MGMT_SNO']; // 결제_관리_일련번호
	        $payData['buy_event_sno'] = $getPaymt[0]['BUY_EVENT_SNO']; // 구매_상품_일련번호
	        $payData['sell_event_sno'] = $getPaymt[0]['SELL_EVENT_SNO']; // 판매_상품_일련번호
	        $payData['recvb_hist_sno'] = $getPaymt[0]['RECVB_SNO']; // 미수금_내역_일련번호
	        $payData['paymt_van_sno'] = $getPaymt[0]['PAYMT_VAN_SNO']; // 결제_VAN_일련번호
	        $payData['comp_cd'] = $getPaymt[0]['COMP_CD']; // 회사_코드
	        $payData['bcoff_cd'] = $getPaymt[0]['BCOFF_CD']; // 지점_코드
	        $payData['1rd_cate_cd'] = $getPaymt[0]['1RD_CATE_CD']; // 1차_카테고리_코드
	        $payData['2rd_cate_cd'] = $getPaymt[0]['2RD_CATE_CD']; // 2차_카테고리_콛,
	        $payData['clas_dv'] = $this->data_buy_info['CLAS_DV']; // 수업_구분
	        $payData['mem_sno'] = $getPaymt[0]['MEM_SNO']; // 회원_일련번호
	        $payData['mem_id'] = $getPaymt[0]['MEM_ID']; // 회원_아이디
	        $payData['mem_nm'] = $getPaymt[0]['MEM_NM']; // 회원_명
	        $payData['sell_event_nm'] = $getPaymt[0]['SELL_EVENT_NM']; // 판매_상품_명
	        $payData['paymt_stat'] = $getPaymt[0]['PAYMT_STAT']; // 결제_상태
	        $payData['paymt_mthd'] = $getPaymt[0]['PAYMT_MTHD']; // 결제_수단
	        $payData['acc_sno'] = $getPaymt[0]['ACCT_NO']; // 계좌_번호
	        $payData['appno'] = $getPaymt[0]['APPNO']; // 승인번호
	        $payData['paymt_amt'] = $getPaymt[0]['PAYMT_AMT']; // 결제_금액
	        $payData['paymt_chnl'] = $getPaymt[0]['PAYMT_CHNL']; // 결제_채널
	        $payData['paymt_van_knd'] = $getPaymt[0]['PAYMT_VAN_KND']; // 결제_VAN_종류
	        $payData['grp_cate_set'] = $getPaymt[0]['GRP_CATE_SET']; // 그룹_카테고리_설정
	        $payData['lockr_set'] = $getPaymt[0]['LOCKR_SET']; // 락커_설정
	        $payData['lockr_knd'] = $getPaymt[0]['LOCKR_KND']; // 락커_종류
	        $payData['lockr_gendr_set'] = $getPaymt[0]['LOCKR_GENDR_SET']; // 락커_성별_설정
	        $payData['lockr_no'] = $getPaymt[0]['LOCKR_NO']; // 락커_번호
	        
	        
	        // 카드일 경우
	        if ($getPaymt[0]['PAYMT_MTHD'] == "01")
	        {
	            $amasno = new Ama_sno();
	            $pay_sno = $amasno->create_pay_sno();
	            
	            $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	            $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	            $payData['paymt_mthd'] = "01"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	            $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	            $payData['appno_sno'] = $this->pay_appno_sno; // 승인번호_일련번호
	            $payData['appno'] = $this->pay_appno; // 승인번호
	            //$payData['paymt_amt'] = $this->pay_card_amt; // 결제_금액
	            
	            $this->modelPay->update_paymt_mgmt_cancel_tbl($upPaymt);
	            
	            $this->func_refund_sales_cancel_proc($payData);
	        }
	        
	        // 현금일 경우
	        if ($getPaymt[0]['PAYMT_MTHD'] == "03")
	        {
	            $amasno = new Ama_sno();
	            $pay_sno = $amasno->create_pay_sno();
	            
	            $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	            $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	            $payData['paymt_mthd'] = "03"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	            $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	            $payData['appno_sno'] = ""; // 승인번호_일련번호
	            $payData['appno'] = ""; // 승인번호
	            //$payData['paymt_amt'] = $this->pay_cash_amt; // 결제_금액
	            
	            $this->modelPay->update_paymt_mgmt_cancel_tbl($upPaymt);
	            
	            $this->func_refund_sales_cancel_proc($payData);
	        }
	        
	    }
	}
	
	/**
	 * [환불] 매출관리 처리 - Insert 처리
	 */
	private function func_refund_sales_cancel_proc($payData)
	{
	    $nn_now = new Time('now');
	    $amasno = new Ama_sno();
	    $sales_sno = $amasno->create_sales_sno();
	    
	    $sales_dv = "90"; // 환불 매출 구분.
	    
	    if ($this->refund_issue == 'Y')
	    {
	        $this->data_sales_info['SALES_DV_RSON'] = "92"; // 환불 매출 사유 [교체환불]
	    } else 
	    {
	        $this->data_sales_info['SALES_DV_RSON'] = "91"; // 환불 매출 사유 [정상환불]
	    }
	    
	    $this->data_sales_info['SALES_MEM_STAT'] = "01"; // 환불 회원상태는 01, 현재회원으로 고정함.
	    
	    $sales_dv_rson = $this->data_sales_info['SALES_DV_RSON']; //
	    $sales_mem_stat = $this->data_sales_info['SALES_MEM_STAT']; //
	    
	    $sdata['sales_mgmt_sno'] = $sales_sno; // 매출_관리_일련번호
	    $sdata['paymt_mgmt_sno'] = $payData['paymt_mgmt_sno']; // 결제_관리_일련번호
	    $sdata['buy_event_sno'] = $payData['buy_event_sno']; // 구매_상품_일련번호
	    $sdata['sell_event_sno'] = $payData['sell_event_sno']; // 판매_상품_일련번호
	    $sdata['send_event_sno'] = ""; // TODO @@@ 보내기_상품_일련번호
	    $sdata['recvb_hist_sno'] = ""; // 미수금_내역_일련번호
	    $sdata['paymt_van_sno'] = $payData['paymt_van_sno']; // 결제_VAN_일련번호
	    $sdata['comp_cd'] = $payData['comp_cd']; // 회사_코드
	    $sdata['bcoff_cd'] = $payData['bcoff_cd']; // 지점_코드
	    $sdata['1rd_cate_cd'] = $payData['1rd_cate_cd']; // 1차_카테고리_코드
	    $sdata['2rd_cate_cd'] = $payData['2rd_cate_cd']; // 2차_카테고리_콛,
	    $sdata['clas_dv'] = $payData['clas_dv']; // 2차_카테고리_콛,
	    $sdata['mem_sno'] = $payData['mem_sno']; // 회원_일련번호
	    $sdata['mem_id'] = $payData['mem_id']; // 회원_아이디
	    $sdata['mem_nm'] = $payData['mem_nm']; // 회원_명
	    $sdata['pthcr_id'] = $_SESSION['user_id']; // 판매강사_아이디
	    $sdata['sell_event_nm'] = $payData['sell_event_nm']; // 판매_상품_명
	    $sdata['paymt_stat'] = $payData['paymt_stat']; // 결제_상태
	    $sdata['paymt_mthd'] = $payData['paymt_mthd']; // 결제_수단
	    $sdata['acc_sno'] = $payData['acct_no']; // 계좌_번호
	    $sdata['appno'] = $payData['appno']; // 승인번호
	    $sdata['paymt_amt'] = $payData['paymt_amt'] * -1; // 결제_금액
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
	    $sdata['cre_id'] = $_SESSION['user_id']; // 등록_아이디
	    $sdata['cre_datetm'] = $nn_now; // 등록_일시
	    $sdata['mod_id'] = $_SESSION['user_id']; // 수정_아이디
	    $sdata['mod_datetm'] = $nn_now; // 수정_일시
	    
	    $this->modelPay->insert_sales_mgmt_tbl($sdata);
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/**
	 * [환불] 위약금 결재관리 처리 - Insert 처리
	 */
	private function func_refund_deposit_paymt_proc()
	{
	    $nn_now = new Time('now');
	    
	    $payData['buy_event_sno'] = $this->pay_buy_sno; // 구매_상품_일련번호
	    $payData['sell_event_sno'] = $this->data_buy_info['SELL_EVENT_SNO']; // 판매_상품_일련번호
	    $payData['send_event_sno'] = ""; // 보내기_상품_일련번호
	    $payData['recvb_sno'] = ""; // 환불할때는 미수금이 없다. 미수금_일련번호
	    
	    $payData['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
	    $payData['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
	    $payData['1rd_cate_cd'] = $this->data_buy_info['1RD_CATE_CD']; // 1차_카테고리_코드
	    $payData['2rd_cate_cd'] = $this->data_buy_info['2RD_CATE_CD']; // 2차_카테고리_코드
	    $payData['clas_dv'] = $this->data_buy_info['CLAS_DV']; // 수업_구분
	    $payData['mem_sno'] = $this->pay_mem_sno; // 회원_일련번호
	    $payData['mem_id'] = $this->pay_mem_id; //회원_아이디
	    $payData['mem_nm'] = $this->pay_mem_nm; //회원_명
	    $payData['sell_event_nm'] = $this->data_buy_info['SELL_EVENT_NM']; // 판매_상품_명
	    $payData['paymt_stat'] = "00"; // 결제_상태 ( 00 : 결제 / 01 : 환불 / 99 : 취소 )
	    $payData['paymt_date'] = date("Y-m-d"); // 결제_일자
	    $payData['refund_date'] = ""; // 환불_일자
	    $payData['paymt_chnl'] = "P"; // 결제_채널 ( M : 모바일 / P : 데스크 / K : 키오스크 )
	    $payData['paymt_van_knd'] = "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 )
	    $payData['grp_cate_set'] = $this->data_buy_info['GRP_CATE_SET']; // 그룹_카테고리_설정
	    $payData['lockr_set'] = $this->data_buy_info['LOCKR_SET']; // 락커_설정
	    $payData['lockr_knd'] = $this->data_buy_info['LOCKR_KND']; // 락커_종류
	    $payData['lockr_gendr_set'] = $this->data_buy_info['LOCKR_GENDR_SET']; // 락커_성별_설정
	    $payData['lockr_no'] = $this->data_buy_info['LOCKR_NO']; // 락커_번호
	    $payData['cre_id'] = $_SESSION['user_id']; // 등록_아이디
	    $payData['cre_datetm'] = $nn_now; // 등록_일시
	    $payData['mod_id'] = $_SESSION['user_id']; // 수정_아이디
	    $payData['mod_datetm'] = $nn_now; // 수정_일시
	    
	    // 위약금 카드일 경우
	    if ($this->pay_deposit_card_amt != '')
	    {
	        $amasno = new Ama_sno();
	        $pay_sno = $amasno->create_pay_sno();
	        
	        $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	        $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	        $payData['paymt_mthd'] = "01"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	        $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	        $payData['appno_sno'] = $this->pay_deposit_appno_sno; // 승인번호_일련번호
	        $payData['appno'] = $this->pay_deposit_appno; // 승인번호
	        $payData['paymt_amt'] = $this->pay_deposit_card_amt; // 결제_금액
	        
	        $this->modelPay->insert_paymt_mgmt_tbl($payData);
	        
	        $this->func_refund_sales_proc($payData,'11');
	    }
	    
	    // 위약금 현금일 경우
	    if ($this->pay_deposit_cash_amt != '')
	    {
	        $amasno = new Ama_sno();
	        $pay_sno = $amasno->create_pay_sno();
	        
	        $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	        $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	        $payData['paymt_mthd'] = "03"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	        $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	        $payData['appno_sno'] = ""; // 승인번호_일련번호
	        $payData['appno'] = ""; // 승인번호
	        $payData['paymt_amt'] = $this->pay_deposit_cash_amt; // 결제_금액
	        
	        $this->modelPay->insert_paymt_mgmt_tbl($payData);
	        
	        $this->func_refund_sales_proc($payData,'11');
	    }
	    
	    // TODO @@@ 계좌이체 , 미수금의 경우는 바로 결제가 등록 되지 않는다.
	    
	}
	
	
	
	/**
	 * [환불] 기타금액 결재관리 처리 - Insert 처리
	 */
	private function func_refund_etc_paymt_proc()
	{
	    $nn_now = new Time('now');
	    
	    $payData['buy_event_sno'] = $this->pay_buy_sno; // 구매_상품_일련번호
	    $payData['sell_event_sno'] = $this->data_buy_info['SELL_EVENT_SNO']; // 판매_상품_일련번호
	    $payData['send_event_sno'] = ""; // 보내기_상품_일련번호
	    $payData['recvb_sno'] = ""; // 환불할때는 미수금이 없다. 미수금_일련번호
	    
	    $payData['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
	    $payData['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
	    $payData['1rd_cate_cd'] = $this->data_buy_info['1RD_CATE_CD']; // 1차_카테고리_코드
	    $payData['2rd_cate_cd'] = $this->data_buy_info['2RD_CATE_CD']; // 2차_카테고리_코드
	    $payData['clas_dv'] = $this->data_buy_info['CLAS_DV']; // 수업_구분
	    $payData['mem_sno'] = $this->pay_mem_sno; // 회원_일련번호
	    $payData['mem_id'] = $this->pay_mem_id; //회원_아이디
	    $payData['mem_nm'] = $this->pay_mem_nm; //회원_명
	    $payData['sell_event_nm'] = $this->data_buy_info['SELL_EVENT_NM']; // 판매_상품_명
	    $payData['paymt_stat'] = "00"; // 결제_상태 ( 00 : 결제 / 01 : 환불 / 99 : 취소 )
	    $payData['paymt_date'] = date("Y-m-d"); // 결제_일자
	    $payData['refund_date'] = ""; // 환불_일자
	    $payData['paymt_chnl'] = "P"; // 결제_채널 ( M : 모바일 / P : 데스크 / K : 키오스크 )
	    $payData['paymt_van_knd'] = "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 )
	    $payData['grp_cate_set'] = $this->data_buy_info['GRP_CATE_SET']; // 그룹_카테고리_설정
	    $payData['lockr_set'] = $this->data_buy_info['LOCKR_SET']; // 락커_설정
	    $payData['lockr_knd'] = $this->data_buy_info['LOCKR_KND']; // 락커_종류
	    $payData['lockr_gendr_set'] = $this->data_buy_info['LOCKR_GENDR_SET']; // 락커_성별_설정
	    $payData['lockr_no'] = $this->data_buy_info['LOCKR_NO']; // 락커_번호
	    $payData['cre_id'] = $_SESSION['user_id']; // 등록_아이디
	    $payData['cre_datetm'] = $nn_now; // 등록_일시
	    $payData['mod_id'] = $_SESSION['user_id']; // 수정_아이디
	    $payData['mod_datetm'] = $nn_now; // 수정_일시
	    
	    // 카드일 경우
	    if ($this->pay_etc_card_amt != '')
	    {
	        $amasno = new Ama_sno();
	        $pay_sno = $amasno->create_pay_sno();
	        
	        $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	        $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	        $payData['paymt_mthd'] = "01"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	        $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	        $payData['appno_sno'] = $this->pay_etc_appno_sno; // 승인번호_일련번호
	        $payData['appno'] = $this->pay_etc_appno; // 승인번호
	        $payData['paymt_amt'] = $this->pay_etc_card_amt; // 결제_금액
	        
	        $this->modelPay->insert_paymt_mgmt_tbl($payData);
	        
	        $this->func_refund_sales_proc($payData,'12');
	    }
	    
	    // 현금일 경우
	    if ($this->pay_etc_cash_amt != '')
	    {
	        $amasno = new Ama_sno();
	        $pay_sno = $amasno->create_pay_sno();
	        
	        $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	        $payData['paymt_van_sno'] = ""; // TODO @@@ 결제_VAN_일련번호
	        $payData['paymt_mthd'] = "03"; // TODO @@@ 결제_수단 ( 01 : 카드 / 02 : 계좌 / 03 : 현금 )
	        $payData['acct_no'] = ""; // TODO @@@ 계좌_번호
	        $payData['appno_sno'] = ""; // 승인번호_일련번호
	        $payData['appno'] = ""; // 승인번호
	        $payData['paymt_amt'] = $this->pay_etc_cash_amt; // 결제_금액
	        
	        $this->modelPay->insert_paymt_mgmt_tbl($payData);
	        
	        $this->func_refund_sales_proc($payData,'12');
	    }
	    
	    // TODO @@@ 계좌이체 , 미수금의 경우는 바로 결제가 등록 되지 않는다.
	    
	}
	
	
	
	
	
	
	
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	/**
	 * [환불] 결재관리 처리 - Insert 처리
	 */
	private function func_refund_paymt_proc()
	{
	    $nn_now = new Time('now');
	    
	    $payData['buy_event_sno'] = $this->pay_buy_sno; // 구매_상품_일련번호
	    $payData['sell_event_sno'] = $this->data_buy_info['SELL_EVENT_SNO']; // 판매_상품_일련번호
	    $payData['send_event_sno'] = ""; // 보내기_상품_일련번호
	    $payData['recvb_sno'] = ""; // 환불할때는 미수금이 없다. 미수금_일련번호
	    
	    $payData['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
	    $payData['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
	    $payData['1rd_cate_cd'] = $this->data_buy_info['1RD_CATE_CD']; // 1차_카테고리_코드
	    $payData['2rd_cate_cd'] = $this->data_buy_info['2RD_CATE_CD']; // 2차_카테고리_코드
	    $payData['clas_dv'] = $this->data_buy_info['CLAS_DV']; // 수업_구분
	    $payData['mem_sno'] = $this->pay_mem_sno; // 회원_일련번호
	    $payData['mem_id'] = $this->pay_mem_id; //회원_아이디
	    $payData['mem_nm'] = $this->pay_mem_nm; //회원_명
	    $payData['sell_event_nm'] = $this->data_buy_info['SELL_EVENT_NM']; // 판매_상품_명
	    $payData['paymt_stat'] = "00"; // 결제_상태 ( 00 : 결제 / 01 : 환불 / 99 : 취소 )
	    $payData['paymt_date'] = date("Y-m-d"); // 결제_일자
	    $payData['refund_date'] = ""; // 환불_일자
	    $payData['paymt_chnl'] = "P"; // 결제_채널 ( M : 모바일 / P : 데스크 / K : 키오스크 )
	    $payData['paymt_van_knd'] = "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 )
	    $payData['grp_cate_set'] = $this->data_buy_info['GRP_CATE_SET']; // 그룹_카테고리_설정
	    $payData['lockr_set'] = $this->data_buy_info['LOCKR_SET']; // 락커_설정
	    $payData['lockr_knd'] = $this->data_buy_info['LOCKR_KND']; // 락커_종류
	    $payData['lockr_gendr_set'] = $this->data_buy_info['LOCKR_GENDR_SET']; // 락커_성별_설정
	    $payData['lockr_no'] = $this->data_buy_info['LOCKR_NO']; // 락커_번호
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
	        
	        $this->func_refund_sales_proc($payData);
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
	        
	        $this->func_refund_sales_proc($payData);
	    }
	    
	    // TODO @@@ 계좌이체 , 미수금의 경우는 바로 결제가 등록 되지 않는다.
	    
	}
	
	/**
	 * [환불] 매출관리 처리 - Insert 처리
	 */
	private function func_refund_sales_proc($payData,$set_sales_dv_rson='')
	{
	    $nn_now = new Time('now');
	    
	    $amasno = new Ama_sno();
	    $sales_sno = $amasno->create_sales_sno();
	    
	    $sales_dv = "00"; // 미수금 매출 구분.
	    $this->data_sales_info['SALES_DV_RSON'] = "10"; // 환불 이용료는 10번으로 고정함.
	    $this->data_sales_info['SALES_MEM_STAT'] = "01"; // 환불 회원상태는 01, 현재회원으로 고정함.
	    
	    $sales_dv_rson = $this->data_sales_info['SALES_DV_RSON']; // 
	    $sales_mem_stat = $this->data_sales_info['SALES_MEM_STAT']; // 
	    
	    if ( $set_sales_dv_rson != '' ) $sales_dv_rson = $set_sales_dv_rson;
	    
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
	    $sdata['cre_id'] = $_SESSION['user_id']; // 등록_아이디
	    $sdata['cre_datetm'] = $nn_now; // 등록_일시
	    $sdata['mod_id'] = $_SESSION['user_id']; // 수정_아이디
	    $sdata['mod_datetm'] = $nn_now; // 수정_일시
	    
	    $this->modelPay->insert_sales_mgmt_tbl($sdata);
	}
	
	/**
	 * 1일당 사용 금액 계산
	 */
	private function onedays_amt()
	{
	    $pay_amt = $this->data_buy_info['BUY_AMT'];
	    $one_day_amt = $pay_amt / $this->set_db_data['total_exr_day_cnt'];
	    return $one_day_amt;
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
	 * 회원이 이미 해당 상품 한개를 가져온다.
	 */
	private function func_get_buy_event_info()
	{
	    $eData['comp_cd'] = $this->pay_comp_cd;
	    $eData['bcoff_cd'] = $this->pay_bcoff_cd;
	    $eData['buy_event_sno'] = $this->pay_buy_sno;
	    
	    $buy_info = $this->modelPay->get_event_buy_sno($eData);
	    if(count($buy_info) > 0) $this->data_buy_info = $buy_info[0];
	}
	
	/**
	 * 구매한 회원 정보를 가져온다.
	 */
	private function func_mem_info()
	{
	    $memModel = new MemModel();
	    $mData['comp_cd'] = $this->pay_comp_cd;
	    $mData['bcoff_cd'] = $this->pay_bcoff_cd;
	    $mData['mem_sno'] = $this->pay_mem_sno;
	    
	    $mem_info = $memModel->get_mem_info_mem_sno($mData);
	    $this->data_mem_info = $mem_info[0];
	}
	
	/**
	 * 운동 시작일 기준으로 판매상품의 조건을 이용하여 종료일을 구함.
	 */
	private function refund_calc_buy_edate()
	{
	    $sdate = $this->data_buy_info['EXR_S_DATE']; // 운동 시작일
	    $uprod = $this->data_buy_info['USE_PROD']; // 기간
	    $uunit = $this->data_buy_info['USE_UNIT']; // 단위
	    if ($uunit == 'M')
	    {
	        $plus_dd = "+" . $uprod . " months";
	    } else
	    {
	        $plus_dd = "+" . $uprod . " days";
	    }
	    
	    $calc_e_date = date("Y-m-d", strtotime($plus_dd, strtotime($sdate)));
	    $edate = date("Y-m-d", strtotime("-1 days", strtotime($calc_e_date)));
	    
	    $this->set_db_data['exr_s_date'] = $sdate;
	    $this->set_db_data['exr_e_date'] = $edate;
	}
	
	/**
	 * 두 기간사이의 일수를 구한다.
	 * @param string $sdate
	 * @param string $edate
	 * @return string|mixed
	 */
	private function refund_calc_days($sdate,$edate)
	{
	    $from = new \DateTime( $sdate );
	    $to = new \DateTime( $edate );
	    $a = $from -> diff( $to ) -> days;
	    if ( $from > $to ) { $a = '-' . $a; }
	    
	    return $a+1;
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
     * 계좌입금 내역을 처리한다.
     */
    private function func_acct_proc()
    {
    	return;
    }
    
    
    
    /*
     buy_event_sno BUY_EVENT_SNO // 구매_상품_일련번호,
     sell_event_sno SELL_EVENT_SNO // 판매_상품_일련번호,
     send_event_sno SEND_EVENT_SNO // 보내기_상품_일련번호,
     comp_cd COMP_CD // 회사_코드,
     bcoff_cd BCOFF_CD // 지점_코드,
     1rd_cate_cd 1RD_CATE_CD // 1차_카테고리_코드,
     2rd_cate_cd 2RD_CATE_CD // 2차_카테고리_코드,
     mem_id MEM_ID // 회원_아이디,
     stchr_id STCHR_ID // 수업강사_아이디,
     ptchr_id PTCHR_ID // 판매강사_아이디,
     sell_event_nm SELL_EVENT_NM // 판매_상품_명,
     sell_amt SELL_AMT // 판매_금액,
     use_prod USE_PROD // 이용_기간,
     use_unit USE_UNIT // 이용_단위,
     clas_cnt CLAS_CNT // 수업_횟수,
     domcy_day DOMCY_DAY // 휴회_일,
     domcy_cnt DOMCY_CNT // 휴회_횟수,
     domcy_poss_event_yn DOMCY_POSS_EVENT_YN // 휴회_가능_상품_여부,
     acc_rtrct_dv ACC_RTRCT_DV // 출입_제한_구분,
     acc_rtrct_mthd ACC_RTRCT_MTHD // 출입_제한_방법,
     clas_dv CLAS_DV // 수업_구분,
     event_img EVENT_IMG // 상품_이미지,
     event_icon EVENT_ICON // 상품_아이콘,
     grp_clas_psnnl_cnt GRP_CLAS_PSNNL_CNT // 그룹_수업_인원_수,
     event_stat EVENT_STAT // 상품_상태,
     event_stat_rson EVENT_STAT_RSON // 상품_상태_사유,
     exr_s_date EXR_S_DATE // 운동_시작_일자,
     exr_e_date EXR_E_DATE // 운동_종료_일자,
     left_domay_poss_day LEFT_DOMCY_POSS_DAY // 남은_휴회_가능_일,
     left_domcy_poss_cnt LEFT_DOMCY_POSS_CNT // 남은_휴회_가능_횟수,
     buy_datetm BUY_DATETM // 구매_일시,
     real_sell_amt REAL_SELL_AMT // 실_판매_금액,
     buy_amt BUY_AMT // 구매_금액,
     recbc_amt RECVB_AMT // 미수_금액,
     add_srvc_clas_cnt ADD_SRVC_CLAS_CNT // 추가_서비스_수업_횟수,
     add_domcy_day ADD_DOMCY_DAY // 추가_휴회_일,
     add_domcy_cnt ADD_DOMCY_CNT // 추가_휴회_횟수,
     srvc_clas_left_cnt SRVC_CLAS_LEFT_CNT // 서비스_수업_남은_횟수,
     srvc_clas_prgs_cnt SRVC_CLAS_PRGS_CNT // 서비스_수업_진행_횟수,
     1tm_clas_prgs_amt 1TM_CLAS_PRGS_AMT // 1회_수업_진행_금액,
     mem_regul_clas_left_cnt MEM_REGUL_CLAS_LEFT_CNT // 회원_정규_수업_남은_횟수,
     mem_regul_clas_prgs_cnt MEM_REGUL_CLAS_PRGS_CNT // 회원_정규_수업_진행_횟수,
     ori_exr_s_date ORI_EXR_S_DATE // 원_운동_시작_일자,
     ori_exr_e_date ORI_EXR_E_DATE // 원_운동_종료_일자,
     transm_poss_yn TRANSM_POSS_YN // 양도_가능_여부,
     refund_poss_yn REFUND_POSS_YN // 환불_가능_여부,
     grp_cate_set GRP_CATE_SET // 그룹_카테고리_설정,
     lockr_set LOCKR_SET // 락커_설정,
     lockr_knd LOCKR_KND // 락커_종류,
     lockr_gendr_set LOCKR_GENDR_SET // 락커_성별_설정,
     lockr_no LOCKR_NO // 락커_번호,
     cre_id CRE_ID // 등록_아이디,
     cre_datetm CRE_DATETM // 등록_일시,
     mod_id MOD_ID // 수정_아이디,
     mod_datetm MOD_DATETM // 수정_일시
     */
    
    /*
      BUY_EVENT_SNO // 구매_상품_일련번호,
      SELL_EVENT_SNO // 판매_상품_일련번호,
      SEND_EVENT_SNO // 보내기_상품_일련번호,
      COMP_CD // 회사_코드,
      BCOFF_CD // 지점_코드,
      1RD_CATE_CD // 1차_카테고리_코드,
      2RD_CATE_CD // 2차_카테고리_코드,
      MEM_ID // 회원_아이디,
      STCHR_ID // 수업강사_아이디,
      PTCHR_ID // 판매강사_아이디,
      SELL_EVENT_NM // 판매_상품_명,
      SELL_AMT // 판매_금액,
      USE_PROD // 이용_기간,
      USE_UNIT // 이용_단위,
      CLAS_CNT // 수업_횟수,
      DOMCY_DAY // 휴회_일,
      DOMCY_CNT // 휴회_횟수,
      DOMCY_POSS_EVENT_YN // 휴회_가능_상품_여부,
      ACC_RTRCT_DV // 출입_제한_구분,
      ACC_RTRCT_MTHD // 출입_제한_방법,
      CLAS_DV // 수업_구분,
      EVENT_IMG // 상품_이미지,
      EVENT_ICON // 상품_아이콘,
      GRP_CLAS_PSNNL_CNT // 그룹_수업_인원_수,
      EVENT_STAT // 상품_상태,
      EVENT_STAT_RSON // 상품_상태_사유,
      EXR_S_DATE // 운동_시작_일자,
      EXR_E_DATE // 운동_종료_일자,
      LEFT_DOMCY_POSS_DAY // 남은_휴회_가능_일,
      LEFT_DOMCY_POSS_CNT // 남은_휴회_가능_횟수,
      BUY_DATETM // 구매_일시,
      REAL_SELL_AMT // 실_판매_금액,
      BUY_AMT // 구매_금액,
      RECVB_AMT // 미수_금액,
      ADD_SRVC_CLAS_CNT // 추가_서비스_수업_횟수,
      ADD_DOMCY_DAY // 추가_휴회_일,
      ADD_DOMCY_CNT // 추가_휴회_횟수,
      SRVC_CLAS_LEFT_CNT // 서비스_수업_남은_횟수,
      SRVC_CLAS_PRGS_CNT // 서비스_수업_진행_횟수,
      1TM_CLAS_PRGS_AMT // 1회_수업_진행_금액,
      MEM_REGUL_CLAS_LEFT_CNT // 회원_정규_수업_남은_횟수,
      MEM_REGUL_CLAS_PRGS_CNT // 회원_정규_수업_진행_횟수,
      ORI_EXR_S_DATE // 원_운동_시작_일자,
      ORI_EXR_E_DATE // 원_운동_종료_일자,
      TRANSM_POSS_YN // 양도_가능_여부,
      REFUND_POSS_YN // 환불_가능_여부,
      GRP_CATE_SET // 그룹_카테고리_설정,
      LOCKR_SET // 락커_설정,
      LOCKR_KND // 락커_종류,
      LOCKR_GENDR_SET // 락커_성별_설정,
      LOCKR_NO // 락커_번호,
      CRE_ID // 등록_아이디,
      CRE_DATETM // 등록_일시,
      MOD_ID // 수정_아이디,
      MOD_DATETM // 수정_일시
     */
	
    
    /**
     * 미수금 결재를 실행한다.
     */
    public function misu_run()
    {
        $this->func_get_buy_event_info(); // 구매 정보를 가져온다.
        $this->func_mem_info(); // 회원 정보를 가져온다.
        $this->func_misu_info(); // 미수금 내역 정보를 가져온다.
        $this->func_sales_info(); // 이전 매출 내역 정보를 가져온다.
        
        // 이전 미수금 처리 내역의 상태를 90 : 미수금 처리 상태로 업데이트 한다.
        $this->func_recvb_hist_update();
        
        // 미수금이 남아 있을 경우에 미수금을 등록 처리한다.
        $this->func_misu_reg_proc();
        
        // 원 구매내역에서 구매금액 (BUY_AMT) 와 미수금액 (RECVB_AMT) 를 업데이트 한다.
        $this->func_misu_buy_event_update();
        
        // 결제 관리 테이블 insert
        $this->func_misu_paymt_proc();
        
        $this->return_pay_result['misu_info'] = $this->data_misu_info;
        $this->return_pay_result['buy_info'] = $this->data_buy_info;
        $this->return_pay_result['mem_info'] = $this->data_mem_info;
    }
    
    /**
     * [미수금 결제] 구매상품의 구매금액 과 미수금액을 업데이트 처리한다.
     */
    private function func_misu_buy_event_update()
    {
        $nn_now = new Time('now');
        
        $edata['buy_event_sno'] = $this->pay_buy_sno;
        $edata['buy_amt'] = $this->data_buy_info['BUY_AMT'] + $this->set_db_data['sum_pay'];
        $edata['recvb_amt'] = $this->set_db_data['recvb_amt'];
        $edata['mod_id'] = $_SESSION['user_id'];
        $edata['mod_datetm'] = $nn_now;
        
        $this->modelPay->update_misu_buy_event($edata);
    }
    
    /**
     * [미수금 결제] 미수금 내역의 처리된 상태 (90) 을 업데이트 한다.
     */
    private function func_recvb_hist_update()
    {
        $nn_now = new Time('now');
        
        if ($this->emptyZero($this->pay_misu_amt) == 0)
        {
            $rdata['recvb_stat'] = "01"; // 미수금 완료
        } else
        {
            
        }
        
        $rdata['recvb_stat'] = "90"; // 미수금 처리
        
        $rdata['mod_id'] = $_SESSION['user_id'];
        $rdata['mod_datetm'] = $nn_now;
        $rdata['buy_event_sno'] = $this->pay_buy_sno;
        
        $this->modelPay->update_recvb_hist_tbl($rdata);
    }
    
    /**
     * [미수금 결제] 미수금 내역 등록 처리한다.
     */
    private function func_misu_reg_proc()
    {
        $nn_now = new Time('now');
        
        $acct_amt = $this->emptyZero($this->pay_acct_amt); // 계좌입금액도 미수금으로 처리해야한다.
        $misu_amt = $this->emptyZero($this->pay_misu_amt); // 미수금
        
        $sum_misu = $acct_amt + $misu_amt; //처리할 미수금
        $this->set_db_data['recvb_amt'] = $sum_misu;
        
        $card_amt = $this->emptyZero($this->pay_card_amt);
        $cash_amt = $this->emptyZero($this->pay_cash_amt);
        
        $sum_pay = $card_amt + $cash_amt;
        $this->set_db_data['sum_pay'] = $sum_pay;
        
        $amasno = new Ama_sno();
        $misu_sno = $amasno->create_misu_sno();
        $this->set_db_data['recvb_hist_sno'] = $misu_sno;
        
        $misuData['recvb_hist_sno']    = $this->set_db_data['recvb_hist_sno']; // 미수금_내역_일련번호
        $misuData['buy_event_sno']     = $this->pay_buy_sno; // 구매_상품_일련번호
        $misuData['sell_event_sno']    = $this->data_buy_info['SELL_EVENT_SNO']; // 판매_상품_일련번호
        $misuData['send_event_sno']    = ""; // TODO @@@ 보내기 상품 처리필요함.
        $misuData['comp_cd']           = $_SESSION['comp_cd']; // 회사_코드
        $misuData['bcoff_cd']          = $_SESSION['bcoff_cd']; // 지점_코드
        $misuData['1rd_cate_cd']       = $this->data_buy_info['1RD_CATE_CD']; // 1차_카테고리_코드
        $misuData['2rd_cate_cd']       = $this->data_buy_info['2RD_CATE_CD']; // 2차_카테고리_코드
        $misuData['mem_sno']           = $this->pay_mem_sno; // 회원_일련번호
        $misuData['mem_id']            = $this->pay_mem_id; // 회원_아이디
        $misuData['mem_nm']            = $this->pay_mem_nm; // 회원_명
        $misuData['sell_event_nm']     = $this->data_buy_info['SELL_EVENT_NM']; // 판매_상품_명
        $misuData['recvb_amt']         = $this->data_buy_info['RECVB_AMT']; // 미수_금액
        $misuData['recvb_paymt_amt']   = $sum_pay; // 미수_결제_금액 (구매시에는 미수 결제 금액이 없다.)
        $misuData['recvb_left_amt']    = $sum_misu; // 미수_남은_금액
        
        if ($this->emptyZero($this->pay_misu_amt) == 0)
        {
            $misuData['recvb_stat'] = "01"; // 미수금 완료
        } else
        {
            $misuData['recvb_stat'] = "00";
        }
        
        $misuData['buy_datetm']        = $nn_now; // 구매_일시
        $misuData['grp_cate_set']      = $this->data_buy_info['GRP_CATE_SET']; // 그룹_카테고리_설정
        $misuData['lockr_set']         = $this->data_buy_info['LOCKR_SET']; // 락커_설정
        $misuData['lockr_knd']         = $this->data_buy_info['LOCKR_KND']; // 락커_종류
        $misuData['lockr_gendr_set']   = $this->data_buy_info['LOCKR_GENDR_SET']; // 락커_성별_설정
        $misuData['lockr_no']          = $this->data_buy_info['LOCKR_NO']; // 락커_번호
        $misuData['cre_id']            = $_SESSION['user_id']; // 등록_아이디
        $misuData['cre_datetm']        = $nn_now; // 등록_일시
        $misuData['mod_id']            = $_SESSION['user_id']; // 수정_아이디
        $misuData['mod_datetm']        = $nn_now; // 수정_일시
        
        $this->modelPay->insert_recvb_hist_tbl($misuData);
        
    }
    
    /**
     * [미수금] 결재관리 처리
     */
    private function func_misu_paymt_proc()
    {
        $nn_now = new Time('now');
        
        
        $payData['buy_event_sno'] = $this->pay_buy_sno; // 구매_상품_일련번호
        $payData['sell_event_sno'] = $this->data_buy_info['SELL_EVENT_SNO']; // 판매_상품_일련번호
        $payData['send_event_sno'] = ""; // 보내기_상품_일련번호
        $payData['recvb_sno'] = $this->set_db_data['recvb_hist_sno']; // 미수금_일련번호
        
        $payData['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
        $payData['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
        $payData['1rd_cate_cd'] = $this->data_buy_info['1RD_CATE_CD']; // 1차_카테고리_코드
        $payData['2rd_cate_cd'] = $this->data_buy_info['2RD_CATE_CD']; // 2차_카테고리_코드
        $payData['clas_dv'] = $this->data_buy_info['CLAS_DV']; // 수업_구분
        
        $payData['mem_sno'] = $this->pay_mem_sno; // 회원_일련번호
        $payData['mem_id'] = $this->pay_mem_id; //회원_아이디
        $payData['mem_nm'] = $this->pay_mem_nm; //회원_명
        $payData['sell_event_nm'] = $this->data_buy_info['SELL_EVENT_NM']; // 판매_상품_명
        $payData['paymt_stat'] = "00"; // 결제_상태 ( 00 : 결제 / 01 : 환불 / 99 : 취소 )
        $payData['paymt_date'] = date("Y-m-d"); // 결제_일자
        $payData['refund_date'] = ""; // 환불_일자
        $payData['paymt_chnl'] = "P"; // 결제_채널 ( M : 모바일 / P : 데스크 / K : 키오스크 )
        $payData['paymt_van_knd'] = "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 )
        $payData['grp_cate_set'] = $this->data_buy_info['GRP_CATE_SET']; // 그룹_카테고리_설정
        $payData['lockr_set'] = $this->data_buy_info['LOCKR_SET']; // 락커_설정
        $payData['lockr_knd'] = $this->data_buy_info['LOCKR_KND']; // 락커_종류
        $payData['lockr_gendr_set'] = $this->data_buy_info['LOCKR_GENDR_SET']; // 락커_성별_설정
        $payData['lockr_no'] = $this->data_buy_info['LOCKR_NO']; // 락커_번호
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
            
            $this->func_misu_sales_proc($payData);
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
            
            $this->func_misu_sales_proc($payData);
        }
        
        // TODO @@@ 계좌이체 , 미수금의 경우는 바로 결제가 등록 되지 않는다.
        
    }
    
    /**
     * [미수금] 매출관리 처리
     */
    private function func_misu_sales_proc($payData)
    {
        $amasno = new Ama_sno();
        $sales_sno = $amasno->create_sales_sno();
        
        $sales_dv = "20"; // 미수금 매출 구분.
        
        
        $sales_dv_rson = $this->data_sales_info['SALES_DV_RSON'];; // 처음 구매한 매출_구분_사유
        $sales_mem_stat = $this->data_sales_info['SALES_MEM_STAT'];; // 처음 구매한 매출_회원_상테
        
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
        $sdata['clas_dv'] = $payData['clas_dv']; // 수업_구분
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
    
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}