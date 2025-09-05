<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\ClasModel;
use App\Models\MemModel;

class Clas_lib {
	
    /**
     * 수업강사 아이디
     * @var string
     */
    private $stchr_id;
    
    /**
     * 구매상품 일련번호
     * @var string
     */
    private $buy_sno;
    
    private $modelClas;
    
    private $buy_info;
    
    private $set_data;

	private $attd_mgmt_sno;
    
    private $result_info = array("result"=>true);
    
    public function __construct($stchr_id,$buy_sno,$auto_chk = "N") {
        $this->modelClas = new ClasModel();
        $this->stchr_id = $stchr_id;
	    $this->buy_sno = $buy_sno;
	    
	    $buyData['comp_cd'] = $_SESSION['comp_cd'];
	    $buyData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $buyData['buy_event_sno'] = $this->buy_sno;
	    $buy_info = $this->modelClas->get_buy_event($buyData);
	    
	    $this->buy_info = $buy_info[0];
	    
	    $this->set_data['auto_chk'] = $auto_chk;
	}
	
	/**
	 * 수업 메모 입력
	 * $msg : 메모내용
	 * $msg_type : M, 회원 / T, 강사
	 */
	public function clas_msg_ins($msg,$msg_type)
	{
	    $nn_now = new Time('now');
	    
	    $memModel = new MemModel();
	    
	    $idata['comp_cd'] = $_SESSION['comp_cd'];
	    $idata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $idata['mem_id'] = $this->buy_info['STCHR_ID'];
	    
	    $tinfo = $memModel->get_mem_info_id_idname($idata);
	    
	    $mdata['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $mdata['sell_event_sno'] = $this->buy_info['SELL_EVENT_SNO'];
	    $mdata['send_event_sno'] = $this->buy_info['SEND_EVENT_SNO'];
	    $mdata['comp_cd'] = $this->buy_info['COMP_CD'];
	    $mdata['bcoff_cd'] = $this->buy_info['BCOFF_CD'];
	    $mdata['1rd_cate_cd'] = $this->buy_info['1RD_CATE_CD'];
	    $mdata['2rd_cate_cd'] = $this->buy_info['2RD_CATE_CD'];
	    $mdata['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
	    $mdata['mem_sno'] = $this->buy_info['MEM_SNO'];
	    $mdata['mem_id'] = $this->buy_info['MEM_ID'];
	    $mdata['mem_nm'] = $this->buy_info['MEM_NM'];
	    $mdata['stchr_sno'] = $tinfo[0]['MEM_SNO'];
	    $mdata['stchr_id'] = $tinfo[0]['MEM_ID'];
	    $mdata['stchr_nm'] = $tinfo[0]['MEM_NM'];
	    $mdata['clas_diary_conts'] = $msg;
	    $mdata['mem_dv'] = $msg_type;
	    $mdata['del_yn'] = "N";
	    $mdata['cre_id'] = $_SESSION['user_id'];
	    $mdata['cre_datetm'] = $nn_now;
	    $mdata['mod_id'] = $_SESSION['user_id'];
	    $mdata['mod_datetm'] = $nn_now;
	    
	    $this->modelClas->insert_pt_clas_diary($mdata);
	}
	
	/**
	 * 수업체크
	 * @return boolean[]
	 */
	public function clas_run()
	{
	    // 수업구분, 수업강사 여부를 체크한다.
	    $this->func_chk_clas();
	    // 수업관리 정보 셋팅
	    $this->func_set_clas();

		

	    // 수업관리 insert
	    $clas_mgmt_sno = $this->insert_pt_clas_mgmt();


		// 출석 관리 테이블에 출석 체크
		$this->update_attd_mgmt($clas_mgmt_sno);

		
	    // 구매상품 update ( 예약중일 경우에 이용중으로 바꿔야함 )
	    $this->update_buy_info();
	    
	    
	    return $this->result_info;
	}

	public function set_attd_mgmt_sno($attd_mgmt_sno)
	{
		$this->attd_mgmt_sno = $attd_mgmt_sno;
	}

	/**
	 * 수업취소
	 */
	public function clas_cancel_mem()
	{
	    // 오늘 수업체크한 수업만 취소가 가능하다.
	    // 오늘 수업한 최근 내용을 가져온다.
	    
	    $ldata['comp_cd'] = $_SESSION['comp_cd'];
	    $ldata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $ldata['buy_event_sno'] = $this->buy_sno;
	    $ldata['stchr_id'] = $this->stchr_id;
	    $ldata['clas_chk_ymd'] = date('Ymd');
	    
	    $last_today_pt_clas = $this->modelClas->last_today_pt_clas($ldata);
	    
	    if ( count($last_today_pt_clas) == 0 )
	    {
	        $this->result_info['result'] = false;
	        $this->result_info['msg'] = "오늘 수업체크가 없어서 취소를 할 수 없습니다.";
	    } else 
	    {
	        $upData['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	        $upData['comp_cd'] = $this->buy_info['COMP_CD'];
	        $upData['bcoff_cd'] = $this->buy_info['BCOFF_CD'];
	        
	        // $last_today_pt_clas[0]['CLAS_MGMT_SNO'] 에 CANCEL_YN 을 Y 로 업데이트 한다.
	        $upData['cancel_yn'] = 'Y';
	        $upData['clas_mgmt_sno'] = $last_today_pt_clas[0]['CLAS_MGMT_SNO'];
	        $this->modelClas->delete_last_pt_clas($upData);
	        
	        // buy_event_mgmt_tbl 테이블에 각각의 정보를 업데이트 한다.
	        // 업데이트 할때 위의 정보에서 PT_CLAS_DV 가 00 일경우에 정규 수업 01일 경우에 서비스 수업으로 업데이트 처리한다.
	        
	        if($last_today_pt_clas[0]['PT_CLAS_DV'] == "00") // 정규 수업일 경우
	        {
	            $upData['mem_regul_clas_prgs_cnt'] = $this->buy_info['MEM_REGUL_CLAS_PRGS_CNT'] - 1;
	            $upData['mem_regul_clas_left_cnt'] = $this->buy_info['MEM_REGUL_CLAS_LEFT_CNT'] + 1;
	            $this->modelClas->update_buy_event_regul_clas($upData);
	            
	        } else // 서비스 수업일 경우
	        {
	            $upData['srvc_clas_prgs_cnt'] = $this->buy_info['SRVC_CLAS_PRGS_CNT'] - 1;
	            $upData['srvc_clas_left_cnt'] = $this->buy_info['SRVC_CLAS_LEFT_CNT'] + 1;
	            $this->modelClas->update_buy_event_srvc_clas($upData);
	        }
			$this->modelClas->update_attd_mgmt_mem($upData);

	        $this->result_info['result'] = true;
	        $this->result_info['msg'] = "수업 취소가 완료 되었습니다.";
	    }
	    
	    return $this->result_info;
	}
	
	/**
	 * 수업취소
	 */
	public function clas_cancel()
	{
	    // 오늘 수업체크한 수업만 취소가 가능하다.
	    // 오늘 수업한 최근 내용을 가져온다.
	    
	    $ldata['comp_cd'] = $_SESSION['comp_cd'];
	    $ldata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $ldata['buy_event_sno'] = $this->buy_sno;
	    $ldata['stchr_id'] = $this->stchr_id;
	    $ldata['clas_chk_ymd'] = date('Ymd');
	    
	    $last_today_pt_clas = $this->modelClas->last_today_pt_clas($ldata);
	    
	    if ( count($last_today_pt_clas) == 0 )
	    {
	        $this->result_info['result'] = false;
	        $this->result_info['msg'] = "오늘 수업체크가 없어서 취소를 할 수 없습니다.";
	    } else 
	    {
	        $upData['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	        $upData['comp_cd'] = $this->buy_info['COMP_CD'];
	        $upData['bcoff_cd'] = $this->buy_info['BCOFF_CD'];
	        
	        // $last_today_pt_clas[0]['CLAS_MGMT_SNO'] 에 CANCEL_YN 을 Y 로 업데이트 한다.
	        $upData['cancel_yn'] = 'Y';
	        $upData['clas_mgmt_sno'] = $last_today_pt_clas[0]['CLAS_MGMT_SNO'];
	        $this->modelClas->update_today_pt_cancel($upData);
	        
	        // buy_event_mgmt_tbl 테이블에 각각의 정보를 업데이트 한다.
	        // 업데이트 할때 위의 정보에서 PT_CLAS_DV 가 00 일경우에 정규 수업 01일 경우에 서비스 수업으로 업데이트 처리한다.
	        
	        if($last_today_pt_clas[0]['PT_CLAS_DV'] == "00") // 정규 수업일 경우
	        {
	            $upData['mem_regul_clas_prgs_cnt'] = $this->buy_info['MEM_REGUL_CLAS_PRGS_CNT'] - 1;
	            $upData['mem_regul_clas_left_cnt'] = $this->buy_info['MEM_REGUL_CLAS_LEFT_CNT'] + 1;
	            $this->modelClas->update_buy_event_regul_clas($upData);
	            
	        } else // 서비스 수업일 경우
	        {
	            $upData['srvc_clas_prgs_cnt'] = $this->buy_info['SRVC_CLAS_PRGS_CNT'] - 1;
	            $upData['srvc_clas_left_cnt'] = $this->buy_info['SRVC_CLAS_LEFT_CNT'] + 1;
	            $this->modelClas->update_buy_event_srvc_clas($upData);
	        }
	        
	        $this->result_info['result'] = true;
	        $this->result_info['msg'] = "수업 취소가 완료 되었습니다.";
	    }
	    
	    return $this->result_info;
	}
	
	/**
	 * 수업구분 , 수업강사 여부를 체크한다.
	 */
	private function func_chk_clas()
	{
        /*
        "11" => "이용권"
        ,"12" => "골프 이용권"
        ,"21" => "PT"
        ,"22" => "골프 PT"
        ,"31" => "그룹 수업"
        ,"91" => "기타(입장불가)"
         */
	    if($this->result_info['result'] == false) return;
	    
	    
	    // if ($this->buy_info['CLAS_DV'] == "21" || $this->buy_info['CLAS_DV'] == "22") // 21 : PT , 22 : 골프 PT
	    if($this->buy_info['1RD_CATE_CD'] == 'PRVN')
		{
	        $this->result_info['result'] = true;
	        $this->result_info['msg'] = "수업상품 체크 성공";
	    } else 
	    {
	        $this->result_info['result'] = false;
	        $this->result_info['msg'] = "수업 상품이 아닙니다.";
	        return;
	    }
	    
	    if ($this->buy_info['STCHR_ID'] == $this->stchr_id)
	    {
	        $this->result_info['result'] = true;
	        $this->result_info['msg'] = "수업강사 체크 성공";
	    } else 
	    {
	        $this->result_info['result'] = false;
	        $this->result_info['msg'] = "수업 강사가 아닙니다.";
	        return;
	    }
	}
	
	private function func_set_clas()
	{
	    if($this->result_info['result'] == false) return;
	    
	    // 서비스 수업 남은 횟수가 0 인가 ?
	    if ($this->buy_info['SRVC_CLAS_LEFT_CNT'] == 0)
	    {
	        // 정규 수업 남은 횟수가 0보다 큰가 ?
	        if ($this->buy_info['MEM_REGUL_CLAS_LEFT_CNT'] > 0)
	        {
	            // 강사 1회 수업 진행금액 = 구매상품의 1회 수업 진행금액
	            // PT 수업구분 : 정규수업
	            $this->set_data['tchr_1tm_clas_prgs_amt'] = $this->buy_info['1TM_CLAS_PRGS_AMT'];
	            $this->set_data['pt_clas_dv'] = "00"; // 정규 수업
	        } else 
	        {
	            $this->result_info['result'] = false;
	            $this->result_info['msg'] = "더이상 진행할 수업이 없습니다.";
	            return;
	        }
	    } else 
	    {
	        // 강사1회 진행금액 = 0원
	        // PT 수업구분 : 서비스 수업
	        
	        $this->set_data['tchr_1tm_clas_prgs_amt'] = 0;
	        $this->set_data['pt_clas_dv'] = "01"; // 서비스 수업
	    }
	    
	    // 회원 1회 수업 진행 금액 계산
	    // 수업횟수 - (서비스 수업 진행횟수 + 회원 정슈 수업 진행 횟수 ) > 0
	    $acc_clas_cnt = $this->buy_info['CLAS_CNT'] - ($this->buy_info['SRVC_CLAS_PRGS_CNT'] + $this->buy_info['MEM_REGUL_CLAS_PRGS_CNT']);
	    if ($acc_clas_cnt > 0)
	    {
	        // 회원1회 수업 진행 금액 = 1회 수업 진행 금액
	        $this->set_data['mem_1tm_clas_prgs_amt'] = $this->buy_info['1TM_CLAS_PRGS_AMT'];
	    } else 
	    {
	        $this->set_data['mem_1tm_clas_prgs_amt'] = 0;
	    }
	    
	    $this->result_info['result'] = true;
	    $this->result_info['msg'] = "수업구분,셋팅 성공";
	    
	    
	}
	
	private function update_attd_mgmt($clas_mgmt_sno)
	{
		$attdData['attd_mgmt_sno'] = $this->attd_mgmt_sno;
		$attdData['clas_mgmt_sno'] = $clas_mgmt_sno;
		$this->modelClas->update_attd_mgmt($attdData);
	}
	
	/**
	 * 수업관리 테이블에 insert
	 */
	private function insert_pt_clas_mgmt()
	{
	    $nn_now = new Time('now');
	    
	    $clasData['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO']; // 구매_상품_일련번호
	    $clasData['sell_event_sno'] = $this->buy_info['SELL_EVENT_SNO']; // 판매_상품_일련번호
	    $clasData['send_event_sno'] = $this->buy_info['SEND_EVENT_SNO']; // 보내기_상품_일련번호
	    $clasData['comp_cd'] = $this->buy_info['COMP_CD']; // 회사_코드
	    $clasData['bcoff_cd'] = $this->buy_info['BCOFF_CD']; // 지점_코드
	    
	    $clasData['1rd_cate_cd'] = $this->buy_info['1RD_CATE_CD']; // 1차_카테고리_코드
	    $clasData['2rd_cate_cd'] = $this->buy_info['2RD_CATE_CD']; // 2차_카테고리_코드
	    
	    $clasData['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM']; // 판매_상품_명
	    
	    $clasData['mem_sno'] = $this->buy_info['MEM_SNO']; // 회원_일련번호
	    $clasData['mem_id'] = $this->buy_info['MEM_ID']; // 회원_아이디
	    $clasData['mem_nm'] = $this->buy_info['MEM_NM']; // 회원_명
	    
	    $clasData['stchr_id'] = $this->buy_info['STCHR_ID']; // 강사_아이디
	    $clasData['stchr_nm'] = $this->buy_info['STCHR_NM']; // 강사_명
	    
	    $clasData['agegrp'] = "40"; // TODO @@@ 연령대
	    $clasData['mem_gndr'] = "M"; // TODO @@@ 회원_성별
	    
	    $clasData['clas_cnt'] = $this->buy_info['CLAS_CNT']; // 수업 횟수
	    
	    if($this->set_data['pt_clas_dv'] == "00") 
	    {
	        $clasData['srvc_clas_left_cnt'] = $this->buy_info['SRVC_CLAS_PRGS_CNT'];
	        $clasData['srvc_clas_prgs_cnt'] = $this->buy_info['SRVC_CLAS_LEFT_CNT'];
	        $clasData['mem_regul_clas_left_cnt'] = $this->buy_info['MEM_REGUL_CLAS_PRGS_CNT'] + 1;
	        $clasData['mem_regul_clas_prgs_cnt'] = $this->buy_info['MEM_REGUL_CLAS_LEFT_CNT'] - 1;
	    } else 
	    {
	        $clasData['srvc_clas_left_cnt'] = $this->buy_info['SRVC_CLAS_PRGS_CNT'] + 1;
	        $clasData['srvc_clas_prgs_cnt'] = $this->buy_info['SRVC_CLAS_LEFT_CNT'] - 1;
	        $clasData['mem_regul_clas_left_cnt'] = $this->buy_info['MEM_REGUL_CLAS_PRGS_CNT'];
	        $clasData['mem_regul_clas_prgs_cnt'] = $this->buy_info['MEM_REGUL_CLAS_LEFT_CNT'];
	    }
	    
	    $clasData['clas_chk_ymd'] = date('Ymd'); // 수업_체크_년월일
	    $clasData['clas_chk_yy'] = date('Y'); // 수업_체크_년
	    $clasData['clas_chk_mm'] = date('m'); // 수업_체크_월
	    $clasData['clas_chk_dd'] = date('d'); // 수업_체크_일
	    $clasData['clas_chk_dotw'] = $this->subfunc_dotw(date('Y-m-d')); // 수업_체크_요일
	    $clasData['clas_chk_hh'] = date('H'); // 수업_체크_시간
	    
	    $clasData['cancel_yn'] = "N"; // 취소_여부
	    $clasData['tchr_1tm_clas_prgs_amt'] = $this->set_data['tchr_1tm_clas_prgs_amt']; // 강사_1회_수업_진행_금액
	    $clasData['clas_dv'] = $this->buy_info['CLAS_DV']; // 수업_구분
	    $clasData['pt_clas_dv'] = $this->set_data['pt_clas_dv']; // PT_수업_구분
	    $clasData['mem_1tm_clas_prgs_amt'] = $this->set_data['mem_1tm_clas_prgs_amt']; // 회원_1회_수업_진행_금액
	    
	    $clasData['auto_chk'] = $this->set_data['auto_chk']; // PT 자동 차감 여부
	    
	    $clasData['cre_id'] = $this->stchr_id; // 등록_아이디
	    $clasData['cre_datetm'] = $nn_now; // 등록_일시
	    $clasData['mod_id'] = $this->stchr_id; // 수정_아이디
	    $clasData['mod_datetm'] = $nn_now; // 수정_일시
	    
	    $clas_mgmt_sno = $this->modelClas->insert_pt_clas_mgmt($clasData);
	    
	    $this->result_info['result'] = true;
	    $this->result_info['msg'] = "수업체크가 완료되었습니다.";
	    
	    return $clas_mgmt_sno;
	}
	
	private function update_buy_info()
	{
	    $upData['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $upData['comp_cd'] = $this->buy_info['COMP_CD'];
	    $upData['bcoff_cd'] = $this->buy_info['BCOFF_CD'];
	    
	    
	    
	    if($this->set_data['pt_clas_dv'] == "00") // 정규 수업일 경우
	    {
	        $upData['mem_regul_clas_prgs_cnt'] = $this->buy_info['MEM_REGUL_CLAS_PRGS_CNT'] + 1;
	        $upData['mem_regul_clas_left_cnt'] = $this->buy_info['MEM_REGUL_CLAS_LEFT_CNT'] - 1;
	        $this->modelClas->update_buy_event_regul_clas($upData);
	        
	    } else // 서비스 수업일 경우
	    {
	        $upData['srvc_clas_prgs_cnt'] = $this->buy_info['SRVC_CLAS_PRGS_CNT'] + 1;
	        $upData['srvc_clas_left_cnt'] = $this->buy_info['SRVC_CLAS_LEFT_CNT'] - 1;
	        $this->modelClas->update_buy_event_srvc_clas($upData);
	    }
	    
	    if ($this->buy_info['EVENT_STAT'] == "01")
	    {
	        $upData['event_stat'] = "00"; // 이용중 상태
	        $upData['exr_s_date'] = date('Y-m-d'); // 운동 시작일을 오늘 날짜로 업데이트
	        $upData['exr_e_date'] = ""; // 운동 시작일을 오늘 날짜로 업데이트
	        $this->modelClas->update_buy_event_stat_sdate($upData);
	    }
	    
	    if ($this->buy_info['SRVC_CLAS_LEFT_CNT'] + $this->buy_info['MEM_REGUL_CLAS_LEFT_CNT'] == 1 )
	    {
	        if ($this->buy_info['EVENT_STAT'] == "00")
	        {
	            $upData['event_stat'] = "99"; // 이용중 상태
	            $upData['exr_s_date'] = $this->buy_info['EXR_S_DATE'];
	            $upData['exr_e_date'] = date('Y-m-d'); // 운동 종료일을 오늘 날짜로 업데이트
	            $this->modelClas->update_buy_event_stat_sdate($upData);
	        }
	    }
	    
	}
	
	/**
	 * 생일을 입력하면 만 나이['age']와 연령대['agegrp'] 를 리턴한다.
	 * @param string $set_birthday (YYYY-MM-DD)형식의 생일
	 * @return array
	 */
	private function subfunc_agegrp($set_birthday)
	{
	    $return_value = array();
	    $birthday  = new \DateTime($set_birthday);
	    $today     = new \DateTime(date('Y-m-d'));
	    
	    $man_age = $birthday->diff($today)->y;
	    $agegrp = sprintf('%d', $man_age / 10);
	    
	    $return_value['age'] = $man_age;
	    $return_value['agegrp'] = $agegrp;
	    
	    return $return_value;
	}
	
	/**
	 * 일자를 입력하면 요일을 리턴한다.
	 * @param string $set_today (YYYY-MM-DD)형식의 오늘일자
	 * @return string
	 */
	private function subfunc_dotw($set_today)
	{
	    $dotw_word = array("일","월","화","수","목","금","토",);
	    $get_dotw = $dotw_word[date('w',strtotime(date($set_today)))];
	    return $get_dotw;
	}
	
	
	
}