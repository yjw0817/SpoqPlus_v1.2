<?php
namespace App\Libraries;

use App\Models\PayModel;
use CodeIgniter\I18n\Time;
use App\Models\MemModel;
use App\Models\SendModel;

class Pay_lib {
	
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
     * 보내기 상품 일련번호
     * @var string
     */
    private $pay_send_sno = "";
    
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
     * POS 결제 사용 여부
     * @var boolean
     */
    private $use_pos = false;
    
    /**
     * POS 결제 정보
     * @var array
     */
    private $pos_payment_info = [];
    
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
     * 구매할 운동상품으로 인해 재조종되어야할 상품 배열
     * @var array
     */
    private $after_buy = array();
    
    /**
     * 일반구매, 교체구매, 교체구매일 경우 Y
     * @var string
     */
    private $pay_issue = "N";
    
    /**
     * 선언이 되어있지 않을 경우에는 기본 데스크로 결제내역을 설정함.
     * 만약에 선언이 되어있다면 해당 선언으로 결제 내역을 설정함.
     * P : 데스크 / M : 모바일 / K : 키오스크
     * @var string
     */
    private $paymt_chnl = "P"; // 기본이 데스크임.
    
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
        $misuData['send_event_sno']    = $this->pay_send_sno; // 보내기 상품 처리필요함.
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
	    $payData['send_event_sno'] = $this->pay_send_sno; // 보내기_상품_일련번호
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
	    $payData['paymt_chnl'] = $this->paymt_chnl; // 결제_채널 ( M : 모바일 / P : 데스크 / K : 키오스크 )
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
	    $sdata['send_event_sno'] = $this->pay_send_sno; // 보내기_상품_일련번호
	    $sdata['recvb_hist_sno'] = $payData['recvb_sno']; // 미수금_내역_일련번호
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
	 * 구매정보 화면에서 출입제한 조건을 체크한다.
	 */
	public function buy_run_pre_check()
	{
	    $this->func_sell_event_info(); // 판매 정보를 가져온다.
	    $this->func_buy_event_info(); // 구매 정보를 가져온다 (이용중,예약됨)
	    $this->func_mem_info(); // 회원 정보를 가져온다.
	    
	    $type_acc_rtrct = $this->func_acct_rtrct_check(); // 1. PT 관련 상품인지 2. 이용권관련 상품인지 체크한다.
	    
	    if ($type_acc_rtrct['result'] == false)
	    {
	        $rt['result_msg'] = "출입제한 조건이 다릅니다.";
	        $rt['result'] = false;
	    } else 
	    {
	        $rt['result_msg'] = "체크성공";
	        $rt['result'] = true;
	        
	    }
	    return $rt;
	}
	
	/**
	 * 구매를 실행한다. 
	 */
	public function buy_run($pay_issue)
	{
	    $this->pay_issue = $pay_issue;
	    $amasno = new Ama_sno();
	    $this->set_db_data['buy_sno'] = $amasno->create_buy_sno();
	    
	    $this->func_sell_event_info(); // 판매 정보를 가져온다.
	    $this->func_buy_event_info(); // 구매 정보를 가져온다 (이용중,예약됨)
	    $this->func_mem_info(); // 회원 정보를 가져온다.
	    
	    $type_acc_rtrct = $this->func_acct_rtrct_check(); // 1. PT 관련 상품인지 2. 이용권관련 상품인지 체크한다.
	    
	    if ($type_acc_rtrct['result'] == false)
	    {
	        $rt['result_msg'] = "출입제한 조건이 다릅니다.";
	        $rt['result'] = false;
	        $this->return_pay_result = $rt;
	        return false;
	    }
	    
	    // 이용권 상품에서 라커 설정을 체크 하여 라커라면 3으로 변경한다.
	    if ($type_acc_rtrct['type'] == 2)
	    {
	        if ($this->data_sell_event_info['LOCKR_SET'] == "Y")
	        {
	        	if ($this->pay_lockr_no != '')
	        	{
	        		$type_acc_rtrct['type'] = 3;
	        	} else 
	        	{
	        		$type_acc_rtrct['type'] = 4; // 라커번호가 없기 때문에 무조건 시작, 종료일이없고 예약 상품으로 표기가 되어야 함.
	        	}
	            
	        }
	    }
	    
	    if ($type_acc_rtrct['type'] == 1)
	    {
	        $this->func_pt_yes();
	    } elseif ($type_acc_rtrct['type'] == 2)
	    {
	        $this->func_pt_no();
	    } elseif ($type_acc_rtrct['type'] == 3)
	    {
	        $this->func_pt_no_lockr();
	    } elseif ($type_acc_rtrct['type'] == 4)
	    {
	    	$this->func_pt_no_lockr_empty();
	    }
	    
	    //재배열될 상품들의 시작, 종료일 업데이트
	    $this->func_remake_buy_update();
	    
	    //계좌입금 처리
	    $this->func_acct_proc(); //TODO @@@ 계좌입금 관련된 테이블 설계도 필요함
	    
	    //미수금 처리
	    $this->func_misu_proc();
	    
	    //구매금액 처리
	    $this->func_sum_buy_amt(); //TODO @@@ 위의 미수금을 처리했는데 구매 금액 처리가 별도로 필요한지 생각해 봐야함
	    
	    //구매내역 처리
	    $this->func_buy_proc();
	    
	    //결재관리 처리
	    $this->func_paymt_proc();
	    
	    //매출관리 처리 [ 구매내역 처리에서 바로 처리 한다. ]
	    //$this->func_sales_proc();
	    
	    //회원이 가입회원(00)일 경우에는 현재회원(01) 으로 업데이트가 필요함.
	    //이때 등록일 및 등록장소도 업데이트 해야함.
	    
	    $this->return_pay_result['buy_list'] = $this->data_buy_event_info; // test value 보기
	    
	    $this->return_pay_result['result_msg'] = $type_acc_rtrct; // test value 보기
	    
	}
	
	/**
	 * 미수금 내역을 처리한다.
	 */
	private function func_misu_proc()
	{
	    $nn_now = new Time('now');
	    
	    $acct_amt = $this->emptyZero($this->pay_acct_amt); // 계좌입금액도 미수금으로 처리해야한다.
	    $misu_amt = $this->emptyZero($this->pay_misu_amt); // 미수금
	    
	    $sum_misu = $acct_amt + $misu_amt; //처리할 미수금
	    $this->set_db_data['recvb_amt'] = $sum_misu;
	    
	    if ($sum_misu > 0)
	    {
	        $amasno = new Ama_sno();
	        $misu_sno = $amasno->create_misu_sno();
	        $this->set_db_data['recvb_hist_sno'] = $misu_sno;
	        
	        $misuData['recvb_hist_sno']    = $this->set_db_data['recvb_hist_sno']; // 미수금_내역_일련번호
	        $misuData['buy_event_sno']     = $this->set_db_data['buy_sno']; // 구매_상품_일련번호
	        $misuData['sell_event_sno']    = $this->pay_sell_sno; // 판매_상품_일련번호
	        $misuData['send_event_sno']    = $this->pay_send_sno; // 보내기 상품 처리필요함.
	        $misuData['comp_cd']           = $_SESSION['comp_cd']; // 회사_코드
	        $misuData['bcoff_cd']          = $_SESSION['bcoff_cd']; // 지점_코드
	        $misuData['1rd_cate_cd']       = $this->data_sell_event_info['1RD_CATE_CD']; // 1차_카테고리_코드
	        $misuData['2rd_cate_cd']       = $this->data_sell_event_info['2RD_CATE_CD']; // 2차_카테고리_코드
	        $misuData['mem_sno']           = $this->pay_mem_sno; // 회원_일련번호
	        $misuData['mem_id']            = $this->pay_mem_id; // 회원_아이디
	        $misuData['mem_nm']            = $this->pay_mem_nm; // 회원_명
	        $misuData['sell_event_nm']     = $this->data_sell_event_info['SELL_EVENT_NM']; // 판매_상품_명
	        $misuData['recvb_amt']         = $sum_misu; // 미수_금액
	        $misuData['recvb_paymt_amt']   = 0; // 미수_결제_금액 (구매시에는 미수 결제 금액이 없다.)
	        $misuData['recvb_left_amt']    = $sum_misu; // 미수_남은_금액
	        $misuData['recvb_stat']        = "00"; // 미수금_상태 ( 미수금등록 : 00 / 미수금결제 : 01 )
	        $misuData['buy_datetm']        = $nn_now; // 구매_일시
	        $misuData['grp_cate_set']      = $this->data_sell_event_info['GRP_CATE_SET']; // 그룹_카테고리_설정
	        $misuData['lockr_set']         = $this->data_sell_event_info['LOCKR_SET']; // 락커_설정
	        $misuData['lockr_knd']         = $this->data_sell_event_info['LOCKR_KND']; // 락커_종류
	        $misuData['lockr_gendr_set']   = $this->pay_lockr_gendr_set; // 락커_성별_설정
	        $misuData['lockr_no']          = $this->pay_lockr_no; // 락커_번호
	        $misuData['cre_id']            = $_SESSION['user_id']; // 등록_아이디
	        $misuData['cre_datetm']        = $nn_now; // 등록_일시
	        $misuData['mod_id']            = $_SESSION['user_id']; // 수정_아이디
	        $misuData['mod_datetm']        = $nn_now; // 수정_일시
	        
	        $this->modelPay->insert_recvb_hist_tbl($misuData);
	    } else 
	    {
	        $this->set_db_data['recvb_hist_sno'] = "";;
	    }
	    
	}
	
	/**
	 * 결재관리 처리
	 */
	private function func_paymt_proc()
	{
	    $nn_now = new Time('now');
	    
	    
	    $payData['buy_event_sno'] = $this->set_db_data['buy_sno'];; // 구매_상품_일련번호
	    $payData['sell_event_sno'] = $this->pay_sell_sno; // 판매_상품_일련번호
	    $payData['send_event_sno'] = $this->pay_send_sno; // 보내기_상품_일련번호
	    $payData['recvb_sno'] = $this->set_db_data['recvb_hist_sno']; // 미수금_일련번호
	    
	    $payData['comp_cd'] = $_SESSION['comp_cd']; // 회사_코드
	    $payData['bcoff_cd'] = $_SESSION['bcoff_cd']; // 지점_코드
	    $payData['1rd_cate_cd'] = $this->data_sell_event_info['1RD_CATE_CD']; // 1차_카테고리_코드
	    $payData['2rd_cate_cd'] = $this->data_sell_event_info['2RD_CATE_CD']; // 2차_카테고리_코드
	    $payData['clas_dv'] = $this->data_sell_event_info['CLAS_DV']; // 수업_구분
	    $payData['mem_sno'] = $this->pay_mem_sno; // 회원_일련번호
	    $payData['mem_id'] = $this->pay_mem_id; //회원_아이디
	    $payData['mem_nm'] = $this->pay_mem_nm; //회원_명
	    $payData['sell_event_nm'] = $this->data_sell_event_info['SELL_EVENT_NM']; // 판매_상품_명
	    $payData['paymt_stat'] = "00"; // 결제_상태 ( 00 : 결제 / 01 : 환불 / 99 : 취소 )
	    $payData['paymt_date'] = date("Y-m-d"); // 결제_일자
	    $payData['refund_date'] = ""; // 환불_일자
	    $payData['paymt_chnl'] = $this->paymt_chnl; // 결제_채널 ( M : 모바일 / P : 데스크 / K : 키오스크 )
	    $payData['paymt_van_knd'] = $this->use_pos ? "04" : "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 / POS : 04 )
	    $payData['grp_cate_set'] = $this->data_sell_event_info['GRP_CATE_SET']; // 그룹_카테고리_설정
	    $payData['lockr_set'] = $this->data_sell_event_info['LOCKR_SET']; // 락커_설정
	    $payData['lockr_knd'] = $this->data_sell_event_info['LOCKR_KND']; // 락커_종류
	    $payData['lockr_gendr_set'] = $this->pay_lockr_gendr_set; // 락커_성별_설정
	    $payData['lockr_no'] = $this->pay_lockr_no; // 락커_번호
	    $payData['cre_id'] = $_SESSION['user_id']; // 등록_아이디
	    $payData['cre_datetm'] = $nn_now; // 등록_일시
	    $payData['mod_id'] = $_SESSION['user_id']; // 수정_아이디
	    $payData['mod_datetm'] = $nn_now; // 수정_일시
	    
	    // POS 결제인 경우
	    if ($this->use_pos && !empty($this->pos_payment_info))
	    {
	        $amasno = new Ama_sno();
	        $pay_sno = $amasno->create_pay_sno();
	        
	        $payData['paymt_mgmt_sno'] = $pay_sno; // 결제_관리_일련번호
	        $payData['paymt_van_sno'] = $this->pos_payment_info['transaction_id'] ?? ''; // POS 거래번호
	        $payData['paymt_mthd'] = $this->convertPosPaymentMethod($this->pos_payment_info['payment_method'] ?? 'CARD'); // 결제_수단
	        $payData['acct_no'] = ""; // 계좌_번호
	        $payData['appno_sno'] = $this->pos_payment_info['approval_no'] ?? ''; // 승인번호_일련번호
	        $payData['appno'] = $this->pos_payment_info['approval_no'] ?? ''; // 승인번호
	        $payData['paymt_amt'] = $this->pos_payment_info['amount'] ?? 0; // 결제_금액
	        
	        // POS 제조사 정보 추가 (paymt_memo 필드에 저장)
	        $payData['paymt_memo'] = json_encode([
	            'pos_manufacturer' => $this->pos_payment_info['manufacturer'] ?? '',
	            'pos_model' => $this->pos_payment_info['model'] ?? '',
	            'card_no' => $this->pos_payment_info['card_no'] ?? '',
	            'card_name' => $this->pos_payment_info['card_name'] ?? ''
	        ]);
	        
	        $this->modelPay->insert_paymt_mgmt_tbl($payData);
	        $this->func_sales_proc($payData);
	    }
	    // 카드일 경우 (기존 로직)
	    else if ($this->pay_card_amt != '')
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
	    } else if($this->pay_card_amt == '' || $this->pay_card_amt == '0')  //카드 금액과 캐시 금액이 모두 없는경우에는 캐시 0원인 결제를 만들어준다. 미수결제시 판매가 없어서 오류나기때문
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
	    
	    //매출_회원_상태
	    /*
	    가입회원 이라면 = 신규회원 [ 마지막에 현재회원으로 업데이트, 등록일시 업데이트 ]  
	    현재회원 이라면 , 등록일시 와 오늘 날짜가 같을 경우 = 신규회원
	    현재회원 이라면 , 등록일시 와 오늘 날짜가 다를 경우 = 현재회원
	       재가입일시와 오늘날짜가 같을 경우 = 재등록회원
	    종료회원 이라면 = 재등록회원 [ 마지막에 현재회원으로 업데이트, 재등록일시 업데이트 ]
	    
	    [ 회원_상태 ]
	    가입회원 : 00
	    현재회원 : 01
	    종료회원 : 90
	    탈퇴회원 : 99
	    
	    [매출_회원_상태] - sales_mem_stat
	    신규회원 : 00
	    현재회원 : 01
	    재등록회원 : 02
	    
	    [매출_구분_사유] - sales_dv_rson
	    신규매출 : 00
	    재등록매출 : 01
	    정상환불매출 : 91
	    교체상품환불매출 : 92
	    
	    [매출_구분] - sales_dv
	    정상매출 : 00
	    미수금매출 : 20
	    교체매출 : 50
	    양도비매출 : 60
	    환불매출 : 90
	    위약금매출 : 91
	    */
	    
	    
	    if($this->pay_issue == 'Y')
	    {
	        $sales_dv = "50"; // 교체매출
	    } else 
	    {
	        $sales_dv = "00"; // 정상매출
	    }
	    
	    $sales_dv_rson = "00"; // 신규매출
	    
	    // 재등록 매출인지를 검사한다.
	    // 재등록 매출은 여태 구매한 내용 (종료됨 포함) 1,2차 카테고리 기준으로 오늘 이전에 구매 이력이 있으면 재등록 매출이다.
	    
	    $rson['comp_cd'] = $_SESSION['comp_cd'];
	    $rson['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $rson['mem_sno'] = $this->data_mem_info['MEM_SNO'];
	    $list_rson = $this->modelPay->list_sales_dv_rson($rson);
	    
	    $sell_cate_code = $this->data_sell_event_info['1RD_CATE_CD'].$this->data_sell_event_info['2RD_CATE_CD'];
	    
	    $rson_tf = false;
	    foreach ($list_rson as $r)
	    {
	        //echo "111 ===== " . date('Ymd',strtotime($r['CRE_DATETM'])) . " ::: " . date('Ymd') . "<br />";
	        if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && date('Ymd',strtotime($r['CRE_DATETM'])) < date('Ymd') )
	        {
	            $rson_tf = true;
	        }
	    }
	     
	    if ($rson_tf == true) $sales_dv_rson = "01";
	    
	    //매출회원상태를 구한다.
	    /*
	    가입회원 이라면 = 신규회원 [ 마지막에 현재회원으로 업데이트, 등록일시 업데이트 ]  
	    현재회원 이라면 , 등록일시 와 오늘 날짜가 같을 경우 = 신규회원
	    현재회원 이라면 , 등록일시 와 오늘 날짜가 다를 경우 = 현재회원
	       재등록일시와 오늘날짜가 같을 경우 = 재등록회원
	    종료회원 이라면 = 재등록회원 [ 마지막에 현재회원으로 업데이트, 재등록일시 업데이트 ]
	    
	    [ 회원_상태 ]
	    가입회원 : 00
	    현재회원 : 01
	    종료회원 : 90
	    탈퇴회원 : 99
	    
	    [매출_회원_상태] - sales_mem_stat
	    신규회원 : 00
	    현재회원 : 01
	    재등록회원 : 02
	    */
	    
	    $sales_mem_stat = "00";
	    if ($this->data_mem_info['MEM_STAT'] == "00") $sales_mem_stat = "00";
	    if ($this->data_mem_info['MEM_STAT'] == "90") $sales_mem_stat = "02";
	    
	    if ($this->data_mem_info['MEM_STAT'] == "01")
	    {
	        if ( date('Ymd',strtotime($this->data_mem_info['REG_DATETM'])) == date('Ymd') )
	        {
	            $sales_mem_stat = "00";
	        } else 
	        {
	            $sales_mem_stat = "01";
	            if ( date('Ymd',strtotime($this->data_mem_info['RE_REG_DATETM'])) == date('Ymd') )
	            {
	                $sales_mem_stat = "02";
	            }
	        }
	    }
	    
	    $sdata['sales_mgmt_sno'] = $sales_sno; // 매출_관리_일련번호
	    $sdata['paymt_mgmt_sno'] = $payData['paymt_mgmt_sno']; // 결제_관리_일련번호
	    $sdata['buy_event_sno'] = $payData['buy_event_sno']; // 구매_상품_일련번호
	    $sdata['sell_event_sno'] = $payData['sell_event_sno']; // 판매_상품_일련번호
	    $sdata['send_event_sno'] = $this->pay_send_sno; // 보내기_상품_일련번호
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
	    
	    
	    $mdata['comp_cd'] = $payData['comp_cd']; // 회사_코드
	    $mdata['bcoff_cd'] = $payData['bcoff_cd']; // 지점_코드
	    $mdata['mem_sno'] = $payData['mem_sno']; // 회원 일련번호
	    if ($this->data_mem_info['MEM_STAT'] == "00")
	    {
	        //[ 마지막에 현재회원으로 업데이트, 등록일시 업데이트 ] , 등록장소 업데이트
	        $mdata['mem_stat'] = "01"; // 현재회원
	        $mdata['reg_place'] = "01"; // 01 : 센터방문 / 02 : 모바일 / 03 : 키오스크
	        $mdata['reg_datetm'] = $payData['cre_datetm'];
	        
	        $this->modelPay->update_mem_join_to_now($mdata);
	    }
	    
	    if ($this->data_mem_info['MEM_STAT'] == "90")
	    {
	        // [ 마지막에 현재회원으로 업데이트, 재등록일시 업데이트 ]
	        $mdata['mem_stat'] = "01"; // 현재회원
	        $mdata['re_reg_datetm'] = $payData['cre_datetm'];
	        
	        $this->modelPay->update_mem_end_to_now($mdata);
	    }
	    
	}
	
	/**
	 * 구매실행한 성공 여부를 리턴한다.
	 */
	public function buy_result()
	{
		$this->return_pay_result['set_data'] = $this->set_db_data;
	    return $this->return_pay_result;
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
	    
	    if(isset($init_array['pay_send_sno']))     $this->pay_send_sno =   $this->zeroEmpty($init_array['pay_send_sno']);
	    
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
	    if(isset($init_array['paymt_chnl']))       $this->paymt_chnl = $this->zeroEmpty($init_array['paymt_chnl']);
	    
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
	 * 판매 상품 정보를 가져온다.
	 */
	private function func_sell_event_info()
	{
	    $eData['comp_cd'] = $this->pay_comp_cd;
	    $eData['bcoff_cd'] = $this->pay_bcoff_cd;
	    $eData['sell_event_sno'] = $this->pay_sell_sno;
	    $sell_event_info = $this->modelPay->get_event_use_sno($eData);
	    $this->data_sell_event_info = $sell_event_info[0];
	    
	    if ($this->pay_send_sno != "")
	    {
	    	$modelSend = new SendModel();
	    	$sData['comp_cd'] = $this->pay_comp_cd;
	    	$sData['bcoff_cd'] = $this->pay_bcoff_cd;
	    	$sData['send_event_mgmt_sno'] = $this->pay_send_sno;
	    	$send_data = $modelSend->get_send_event_mgmt($sData);
	    	
	    	$this->data_sell_event_info['SELL_AMT'] = $send_data[0]['SELL_AMT'];
	    	$this->data_sell_event_info['USE_PROD'] = $send_data[0]['USE_PROD'];
	    	$this->data_sell_event_info['USE_UNIT'] = $send_data[0]['USE_UNIT'];
	    	$this->data_sell_event_info['CLAS_CNT'] = $send_data[0]['CLAS_CNT'];
	    	$this->data_sell_event_info['DOMCY_DAY'] = $send_data[0]['DOMCY_DAY'];
	    	$this->data_sell_event_info['DOMCY_CNT'] = $send_data[0]['DOMCY_CNT'];
	    	
	    	$this->data_sell_event_info['ADD_SRVC_EXR_DAY'] = $send_data[0]['ADD_SRVC_EXR_DAY'];
	    	$this->data_sell_event_info['ADD_SRVC_CLAS_CNT'] = $send_data[0]['ADD_SRVC_CLAS_CNT'];
	    	
	    	$this->data_sell_event_info['STCHR_ID'] = $send_data[0]['STCHR_ID'];
	    	$this->data_sell_event_info['STCHR_NM'] = $send_data[0]['STCHR_NM'];
	    	$this->data_sell_event_info['PTCHR_ID'] = $send_data[0]['PTCHR_ID'];
	    	$this->data_sell_event_info['PTCHR_NM'] = $send_data[0]['PTCHR_NM'];
	    }
	    
	    $this->return_pay_result = $this->data_sell_event_info; // test data
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
	 * 이전 미수 내역 정보
	 */
	private function func_misu_info()
	{
	    $mdata['buy_event_sno'] = $this->pay_buy_sno;
	    $misu_info = $this->modelPay->select_recvb_hist_tbl($mdata);
	    $this->data_misu_info = $misu_info[0];
	}
	
	/**
	 * 이전 매출 내역 정보
	 */
	private function func_sales_info()
	{
	    $sdata['recvb_hist_sno'] = $this->data_misu_info['RECVB_HIST_SNO'];
	    $sales_info = $this->modelPay->select_sales_mgmt_tbl($sdata);
		if (empty($sales_info)) {
		} else
		{
			$this->data_sales_info = $sales_info[0];
		}

	    
	}
	
	/**
	 * 결제 상품중에 출입 제한 조건이 있는지를 체크한다. array['type'] , ['result']
	 * 1. PT 관련 상품인지 
	 * 2. 기간 및 PT 기간 상품인지
	 */
    private function func_acct_rtrct_check()
    {
        $return_value['result'] = true;
        // ACC_RTRCT_DV ( 00 : 무제한 / 01 : 1일1회입장 / 99 : 입장불가 / 50 : 수업입장
        if ($this->data_sell_event_info['ACC_RTRCT_DV'] != "99" && $this->data_sell_event_info['ACC_RTRCT_DV'] != "50" ) 
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
        
            // $sell_event['ACC_RTRCT_DV'] = $this->data_sell_event_info['ACC_RTRCT_DV'];
            // $sell_event['ACC_RTRCT_MTHD'] = $this->data_sell_event_info['ACC_RTRCT_MTHD'];
            
            
            // foreach($this->data_buy_event_info as $r)
            // {
            //     if ($r['ACC_RTRCT_DV'] != "99" && $r['ACC_RTRCT_DV'] != "50" )
            //     {
            //         if ($r['ACC_RTRCT_DV'] != $sell_event['ACC_RTRCT_DV']) $return_value = false;
            //         if ($r['ACC_RTRCT_MTHD'] != $sell_event['ACC_RTRCT_MTHD']) $return_value = false;
            //     }
            // }
        
        return $return_value;
    }
    
    /**
     * PT 상품인지, 이용권 및 PT 이용권 상품인지 체크한다.
     * @return string (1: PT 관련 상품 / 2: 기간 및 PT 기간 상품
     */
    private function clas_prod_check()
    {
        $return_value = "";
        ($this->zeroEmpty($this->data_sell_event_info['USE_PROD']) == "") || $this->data_sell_event_info['M_CATE'] == "PRV" ? $return_value = 1 : $return_value = 2;
        return $return_value;
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
        $this->onetime_pt_cost();
    }
    
    /**
     * 1회 수업 진행 금액 계산
     */
    private function onetime_pt_cost()
    {
    	if ($this->pay_real_sell_amt == "")
    	{
    		$this->set_db_data['1TM_CLAS_PRGS_AMT'] = 0;
    	} else 
    	{
    		$total_cost = $this->pay_real_sell_amt; // 총금액
    		$total_clas_cnt = $this->data_sell_event_info['CLAS_CNT']; // 제공될 수업 횟수
    		//1회 수업 진행 금액은 [ 총금액 에서 제공될 수업 횟수를 나누고 소스점일 경우 버림 처리 한다. ]
    		
    		$onetime_cost = $total_cost / $total_clas_cnt;
    		$this->set_db_data['1TM_CLAS_PRGS_AMT'] = floor($onetime_cost);
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
    
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    
    /**
     * 이용권 상품 [락커]
     */
    private function func_pt_no_lockr()
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
                // if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->pay_lockr_no && $this->data_sell_event_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->pay_lockr_gendr_set == $r['LOCKR_GENDR_SET'])
				if($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->pay_lockr_no && $this->data_sell_event_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->pay_lockr_gendr_set == $r['LOCKR_GENDR_SET'])
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
        $this->remake_buy_event_info_lockr();
        return true;
        
    }
    
    /**
     * 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
     */
    private function remake_buy_event_info_lockr()
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
                
                if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->pay_lockr_no && $this->data_sell_event_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->pay_lockr_gendr_set == $r['LOCKR_GENDR_SET'])
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
                    // if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->pay_lockr_no && $this->data_sell_event_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->pay_lockr_gendr_set == $r['LOCKR_GENDR_SET'] )
                    if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->pay_lockr_no && $this->data_sell_event_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->pay_lockr_gendr_set == $r['LOCKR_GENDR_SET'])
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
        
        $this->return_pay_result['after'] = $this->after_buy; // test value 보기
        
    }
    
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    
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
				// if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
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
    	
    	$this->return_pay_result['after'] = $this->after_buy; // test value 보기
    	
    }
    
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    
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
    		if (isset($this->data_sell_event_info['ADD_SRVC_EXR_DAY']))
    		{
    			$plus_dd = "+" . ($uprod + $this->data_sell_event_info['ADD_SRVC_EXR_DAY']) . " days"; // 보내기 상품에서 서비스 추가를 하였기때문에 해당 일자를 더해서 구해야한다.
    		} else 
    		{
    			$plus_dd = "+" . $uprod . " days";
    		}
    		
    	}
    	
    	$calc_e_date = date("Y-m-d", strtotime($plus_dd, strtotime($sdate)));
    	$edate = date("Y-m-d", strtotime("-1 days", strtotime($calc_e_date)));
    	
    	$this->set_db_data['exr_e_date'] = $edate;
    }
    
    /**
     * 계좌입금 내역을 처리한다.
     */
    private function func_acct_proc()
    {
    	return;
    }
    
    
    
    /**
     * 구매금액을 처리한다.
     */
    private function func_sum_buy_amt()
    {
    	$sum_buy_amt = $this->emptyZero($this->pay_card_amt) + $this->emptyZero($this->pay_cash_amt);
    	$this->set_db_data['buy_amt'] = $sum_buy_amt;
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
    	
    	if ($this->pay_real_sell_amt == "") $this->pay_real_sell_amt = $this->data_sell_event_info['SELL_AMT'];
    	
    	$onetime_clas_cost = 0;
    	if (isset($this->set_db_data['1TM_CLAS_PRGS_AMT'])) $onetime_clas_cost = $this->set_db_data['1TM_CLAS_PRGS_AMT'];
    	
    	$bdata['buy_event_sno'] 	= $this->set_db_data['buy_sno'];  // 구매_상품_일련번호, 
    	$bdata['sell_event_sno'] 	= $this->data_sell_event_info['SELL_EVENT_SNO'];  // 판매_상품_일련번호,
    	$bdata['send_event_sno'] 	= $this->pay_send_sno;  // 보내기_상품_일련번호
    	$bdata['comp_cd'] 			= $this->pay_comp_cd;  // 회사_코드,
    	$bdata['bcoff_cd'] 			= $this->pay_bcoff_cd;  // 지점_코드,
    	$bdata['1rd_cate_cd'] 				= $this->data_sell_event_info['1RD_CATE_CD'];  // 1차_카테고리_코드,
    	$bdata['2rd_cate_cd'] 				= $this->data_sell_event_info['2RD_CATE_CD']; // 2차_카테고리_코드,
    	$bdata['mem_sno'] 			= $this->pay_mem_sno;  // 회원_일련번호,
    	$bdata['mem_id'] 			= $this->pay_mem_id;  // 회원_아이디,
    	$bdata['mem_nm'] 			= $this->pay_mem_nm;  // 회원_명,
    	$bdata['stchr_id'] 			= "";  // 수업강사_아이디, TODO @@@ 보내기 상품일 경우에 수업강사를 지정 할 수도있다.
    	$bdata['stchr_nm'] 			= "";  // 수업강사_명 TODO @@@ 보내기 상품일 경우에 수업강사를 지정 할 수도있다.
    	$bdata['ptchr_id'] 							= $_SESSION['user_id'];  // 판매강사_아이디 TODO @@@ 보내기 상품일 경우에 판매강사를 지정 할 수도있다.
    	$bdata['ptchr_nm'] 							= $_SESSION['user_name'];  // 판매강사_명 TODO @@@ 보내기 상품일 경우에 판매강사를 지정 할 수도있다.
    	
    	if ($this->pay_send_sno != "")
    	{
    		$bdata['stchr_id'] = $this->data_sell_event_info['STCHR_ID'];
    		$bdata['stchr_nm'] = $this->data_sell_event_info['STCHR_NM'];
    		$bdata['ptchr_id'] = $this->data_sell_event_info['PTCHR_ID'];
    		$bdata['ptchr_nm'] = $this->data_sell_event_info['PTCHR_NM'];
    	}
    	
    	
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
    	
    	if ($this->pay_issue == 'Y')
    	{
    	    $bdata['event_stat_rson']   = "52"; // 정상(교체)
    	} else 
    	{
    	    $bdata['event_stat_rson'] 	= $this->set_db_data['event_stat_rson'];  // 상품_상태_사유, 00 정상
    	}
    	
    	$bdata['exr_s_date'] 		= $this->set_db_data['exr_s_date'];  // 운동_시작_일자,
    	$bdata['exr_e_date'] 		= $this->set_db_data['exr_e_date']; // 운동_종료_일자,
    	$bdata['left_domay_poss_day'] 		= $this->data_sell_event_info['DOMCY_DAY'];  // 남은_휴회_가능_일,
    	$bdata['left_domcy_poss_cnt'] 		= $this->data_sell_event_info['DOMCY_CNT'];  // 남은_휴회_가능_횟수,
    	$bdata['buy_datetm'] 						= $nn_now; // 구매_일시,
    	$bdata['real_sell_amt'] 	= $this->pay_real_sell_amt;  // 실_판매_금액,
    	$bdata['buy_amt'] 			= $this->set_db_data['buy_amt'];  // 구매_금액,
    	$bdata['recbc_amt'] 		= $this->set_db_data['recvb_amt'];  // 미수_금액,
    	
    	$bdata['add_srvc_exr_day'] 					= 0; // 추가_서비스_운동_일,
    	if (isset($this->data_sell_event_info['ADD_SRVC_EXR_DAY']))
    	{
    		$bdata['add_srvc_exr_day'] = $this->data_sell_event_info['ADD_SRVC_EXR_DAY'];
    	}
    	
    	$bdata['add_srvc_clas_cnt'] 				= 0; // 추가_서비스_수업_횟수,
    	$bdata['srvc_clas_left_cnt'] 				= 0; // 서비스_수업_남은_횟수,
    	
    	if (isset($this->data_sell_event_info['ADD_SRVC_CLAS_CNT']))
    	{
    		$bdata['add_srvc_clas_cnt'] = $this->data_sell_event_info['ADD_SRVC_CLAS_CNT'];
    		$bdata['srvc_clas_left_cnt'] = $this->data_sell_event_info['ADD_SRVC_CLAS_CNT'];
    	}
    	
    	$bdata['add_domcy_day'] 					= 0;  // 추가_휴회_일,
    	$bdata['add_domcy_cnt'] 					= 0; // 추가_휴회_횟수,
    	
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
    	$bdata['lockr_gendr_set'] 	= $this->pay_lockr_gendr_set; // 락커_성별_설정,
    	$bdata['lockr_no'] 			= $this->pay_lockr_no; // 락커_번호,
    	$bdata['cre_id'] 			= $_SESSION['user_id'];  // 등록_아이디,
    	$bdata['cre_datetm'] 		= $nn_now;  // 등록_일시,
    	$bdata['mod_id'] 			= $_SESSION['user_id']; // 수정_아이디,
    	$bdata['mod_datetm'] 		= $nn_now;  // 수정_일시
    	
    	$this->modelPay->insert_buy_event_mgmt($bdata);
    	
    	// 락커 처리
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
    	
    	// 보내기 상품 상태 변경
    	if ($this->pay_send_sno != "")
    	{
    	    $modelSend = new SendModel();
    	    $evData['comp_cd'] = $this->pay_comp_cd;
    	    $evData['bcoff_cd'] = $this->pay_bcoff_cd;
    	    $evData['send_stat'] = "01"; // 결제
    	    $evData['mod_id'] = $_SESSION['user_id'];
    	    $evData['mod_datetm'] = $nn_now;
    	    $evData['send_event_mgmt_sno'] = $this->pay_send_sno;
    	    
    	    $modelSend->update_send_event_mgmt($evData);
    	}
    	
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
	 * POS 결제 정보 설정
	 */
	public function setPosPaymentInfo($posInfo)
	{
	    $this->use_pos = true;
	    $this->pos_payment_info = $posInfo;
	}
	
	/**
	 * POS 결제 수단 변환
	 */
	private function convertPosPaymentMethod($method)
	{
	    $methodMap = [
	        'CARD' => '01',
	        'CASH' => '03',
	        'POINT' => '04',
	        'GIFT' => '05'
	    ];
	    
	    return $methodMap[$method] ?? '01';
	}
	
	/**
	 * POS 결제 처리
	 */
	public function processPosPayment($paymentData)
	{
	    try {
	        // POS 서비스 초기화
	        $posService = new \App\Services\PosIntegrationService();
	        
	        $comp_cd = $_SESSION['comp_cd'] ?? '';
	        $bcoff_cd = $_SESSION['bcoff_cd'] ?? '';
	        
	        if (!$posService->initialize($bcoff_cd, $comp_cd)) {
	            return [
	                'result' => 'error',
	                'message' => 'POS가 설정되지 않았거나 비활성화되어 있습니다.'
	            ];
	        }
	        
	        // POS 결제 요청
	        $result = $posService->requestPayment($paymentData);
	        
	        if ($result['status'] === 'success') {
	            // POS 결제 정보 설정
	            $this->setPosPaymentInfo([
	                'transaction_id' => $result['transaction_id'],
	                'approval_no' => $result['approval_no'],
	                'amount' => $paymentData['amount'],
	                'payment_method' => $paymentData['payment_method'],
	                'card_no' => $result['card_no'] ?? '',
	                'card_name' => $result['card_name'] ?? '',
	                'manufacturer' => $posService->getSettings()['pos_device']['manufacturer'] ?? '',
	                'model' => $posService->getSettings()['pos_device']['model'] ?? ''
	            ]);
	            
	            return [
	                'result' => 'success',
	                'message' => '결제가 완료되었습니다.',
	                'data' => $result
	            ];
	        } else {
	            return [
	                'result' => 'error',
	                'message' => $result['message'] ?? 'POS 결제 실패'
	            ];
	        }
	        
	    } catch (Exception $e) {
	        log_message('error', 'POS 결제 처리 실패: ' . $e->getMessage());
	        return [
	            'result' => 'error',
	            'message' => 'POS 결제 처리 중 오류가 발생했습니다.'
	        ];
	    }
	}
}