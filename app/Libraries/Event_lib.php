<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\EventModel;
use App\Models\HistModel;

class Event_lib {
	
    /**
     * 구매상품 BUY_EVENT_SNO
     * @var string
     */
    private $buy_sno;
    
    /**
     * sdate : 시작일 변경
     * edate : 종료일 변경
     * domcyd : 휴회일 추가
     * domcyc : 휴명횟수 추가
     * stchr : 수업강사 변경하기
     * ptchr : 판매강사 변경하기
     * clas : 수업수 추가
     * @var string
     */
    private $btype;
    
    private $buy_info;
    
    private $buy_list = null;
    
    private $buy_mem_sno;
    
    private $buy_mem_nm;
    
    private $set_value;
    
    private $rvalue;
    
    private $is_possable;
    
    private $eventModel;
    
    private $after_buy = array();
    
    /**
     * buy_sno
     * btype
     * @param array $initVar
     */
	public function __construct($initVar) {
	    
	    $is_possable = true;
	    
	    if(isset($initVar['buy_sno']))
	    {
	        $this->buy_sno = $initVar['buy_sno'];
	    } else 
	    {
	        $is_possable = false;
	    }
	    
	    if(isset($initVar['btype']))
	    {
	        $this->btype = $initVar['btype'];
	    } else 
	    {
	        $is_possable = false;
	    }
	    
	    $this->is_possable = $is_possable;
	    
	    if ($this->is_possable == true)
	    {
	        $this->eventModel = new EventModel();
	        
	        $iData['comp_cd'] = $_SESSION['comp_cd'];
	        $iData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	        $iData['buy_event_sno'] = $this->buy_sno;
	        $get_buy_info = $this->eventModel->get_buy_event_buy_sno($iData);
	        
	        if (count($get_buy_info) > 0) 
	        {
	            $this->buy_info = $get_buy_info[0];
	            $this->buy_mem_sno = $get_buy_info[0]['MEM_SNO'];
	            $this->buy_mem_nm  = $get_buy_info[0]['MEM_NM'];
	        }
	    }
	    
	    $this->rvalue['msg'] = "";
	}
	
	/**
	 * 라커룸 지정을 수행한다.
	 * lockr_no , lockr_knd , lockr_gendr
	 * @param array $lockr_inf
	 * @return array
	 */
	public function run_lockr_select($lockr_inf)
	{
	    $this->func_locker_select($lockr_inf);
	    
        return $this->rvalue;	    
	}
	
	/**
	 * 라커룸 지정을 처리한다.
	 * @param array $lockr_inf
	 */
	private function func_locker_select($lockr_inf)
	{
	    $nn_now = new Time('now');
	    $this->buy_event_list(); // 회원의 이용중, 예약됨 상품들을 가져온다.
	    
	    $sell_cate_code = $this->buy_info['1RD_CATE_CD'].$this->buy_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    
	    $set_exr_edate = date('Y-m-d');
	    
	    if ($this->buy_list != null)
	    {
	        foreach($this->buy_list as $r)
	        {
	            if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $lockr_inf['lockr_no'])
	            {
	                if ($r['EXR_E_DATE'] !='' )
	                {
	                    echo "set_exr_edate ::: " . $set_exr_edate . "<br />";
	                    if ( strtotime($r['EXR_E_DATE']) > strtotime($set_exr_edate) )
	                    {
	                        $set_exr_edate = $r['EXR_E_DATE'];
	                        echo "set_exr_edate ::: " . $set_exr_edate . "<br />";
	                    }
	                }
	            }
	        }
	    }
	    
	    // 예약된 락커을 이용중 락커으로 바꿔야한다.
	    if ($set_exr_edate == date('Y-m-d'))
	    {
	        $sdate = date('Y-m-d');
	        $lockrData['event_stat'] = "00";
	        
	    } else 
	    {
	        $sdate = date("Y-m-d", strtotime("+1 days", strtotime($set_exr_edate)));
	        $lockrData['event_stat'] = "01";
	    }
	    
	    $this->calc_se_date($sdate);
	    $lockrData['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $lockrData['exr_s_date'] = $this->set_value['exr_s_date'];
	    $lockrData['exr_e_date'] = $this->set_value['exr_e_date'];
	    
	    $lockrData['lockr_gendr_set'] = $lockr_inf['lockr_gendr'];
	    $lockrData['lockr_no'] = $lockr_inf['lockr_no'];
	    
	    $lockrData['mod_id'] = $_SESSION['user_id'];
	    $lockrData['mod_datetm'] = $nn_now;
	    
	    $this->eventModel->update_buy_event_mgmt_lockr($lockrData);
	    
	    $this->rvalue['lockrData'] = $lockrData;
	    
	    if ($lockrData['event_stat'] == "00")
	    {
	        $LroomData['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	        $LroomData['mem_sno'] = $this->buy_mem_sno;
	        $LroomData['mem_nm'] = $this->buy_mem_nm;
	        $LroomData['lockr_use_s_date'] = $this->set_value['exr_s_date'];
	        $LroomData['lockr_use_e_date'] = $this->set_value['exr_e_date'];
	        $LroomData['lockr_stat'] = "01";
	        
	        $LroomData['comp_cd'] = $_SESSION['comp_cd'];
	        $LroomData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	        $LroomData['lockr_knd'] = $lockr_inf['lockr_knd'];
	        $LroomData['lockr_gendr_set'] = $lockr_inf['lockr_gendr'];
	        $LroomData['lockr_no'] = $lockr_inf['lockr_no'];
	        
	        $this->eventModel->update_lockr_room($LroomData);
	    }
	    
	    $this->is_possable = true;
	    $this->rvalue['is_possable'] = $this->is_possable;
	    $this->rvalue['msg'] = "";
	}
	
	/**
	 * 시작일 변경을 수행한다.
	 * @param string $sdate
	 * @return array
	 */
	public function run_sdate($sdate)
	{
	    $this->func_sdate($sdate);
	    return $this->rvalue;
	}
	
	/**
	 * 종료일 변경을 수행한다.
	 * @param string $edate
	 * @return array
	 */
	public function run_edate($edate)
	{
		$this->func_edate($edate);
		return $this->rvalue;
	}
	
	/**
	 * 휴회일 추가를 수행한다.
	 * @param array $domcy_day
	 */
	public function run_domcy_day($domcy_day)
	{
	    $this->func_domcy_day($domcy_day);
	    return $this->rvalue;
	}
	
	/**
	 * 휴회횟수 추가를 수행한다.
	 * @param array $domcy_cnt
	 */
	public function run_domcy_cnt($domcy_cnt)
	{
	    $this->func_domcy_cnt($domcy_cnt);
	    return $this->rvalue;
	}
	
	/**
	 * 수업 추가를 수행한다.
	 * @param array $clas_cont
	 */
	public function run_clas_cnt($clas_cnt)
	{
	    $this->func_clas_cnt($clas_cnt);
	    return $this->rvalue;
	}
	
	/**
	 * 수업강사 변경을 수행한다.
	 * @param array $stchr_id
	 */
	public function run_stchr($stchr_id)
	{
	    $this->func_stchr($stchr_id);
	    return $this->rvalue;
	}
	
	/**
	 * 판매강사 변경을 수행한다.
	 * @param array $ptchr_id
	 */
	public function run_ptchr($ptchr_id)
	{
	    $this->func_ptchr($ptchr_id);
	    return $this->rvalue;
	}
	
	/**
	 * 수업강사 변경을 처리한다.
	 * @param array $stchr_id
	 */
	private function func_stchr($stchr_id)
	{
	    // 수업강사명을 데이터베이스에서 찾아온다.
	    $memData['comp_cd'] = $_SESSION['comp_cd'];
	    $memData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $memData['mem_id'] = $stchr_id;
	    $mem_info = $this->eventModel->get_mem_info_id_idname($memData);
	    
	    $stchr_nm = isset($mem_info[0]['MEM_NM']) ? $mem_info[0]['MEM_NM'] : '';
	    if($stchr_nm == '')
	    {
	        $stchr_id = '';
	    }
	    
	    $nn_now = new Time('now');
	    $udata['mod_id'] = $_SESSION['user_id'];
	    $udata['mod_datetm'] = $nn_now;
	    $udata['buy_event_sno'] = $this->buy_sno;
	    $udata['stchr_id'] = $stchr_id;
	    $udata['stchr_nm'] = $stchr_nm;
	    
	    $this->eventModel->update_buy_event_mgmt_stchr($udata);
	    
	    // 강사변경 history insert
	    $nn_now = new Time('now');
	    $histModel = new HistModel();
	    
	    $hist['comp_cd'] = $_SESSION['comp_cd'];
	    $hist['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $hist['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $hist['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
	    $hist['ch_type'] = 'CHTS';
	    $hist['mem_sno'] = $this->buy_info['MEM_SNO'];
	    $hist['mem_id'] = $this->buy_info['MEM_ID'];
	    $hist['mem_nm'] = $this->buy_info['MEM_NM'];
	    $hist['bf_tchr_id'] = $this->buy_info['STCHR_ID'];
	    $hist['bf_tchr_nm'] = $this->buy_info['STCHR_NM'];
	    $hist['af_tchr_id'] = $stchr_id;
	    $hist['af_tchr_nm'] = $stchr_nm;
	    $hist['mod_id'] = $_SESSION['user_id'];
	    $hist['mod_datetm'] = $nn_now;
	    $hist['cre_id'] = $_SESSION['user_id'];
	    $hist['cre_datetm'] = $nn_now;
	    $histModel->insert_hist_event_tchr_ch($hist);
	    
	    $this->is_possable = true;
	    $this->rvalue['is_possable'] = $this->is_possable;
	    $this->rvalue['msg'] = "";
	}
	
	/**
	 * 판매강사 변경을 처리한다.
	 * @param array $ptchr_id
	 */
	private function func_ptchr($ptchr_id)
	{
	    // 수업강사명을 데이터베이스에서 찾아온다.
	    $memData['comp_cd'] = $_SESSION['comp_cd'];
	    $memData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $memData['mem_id'] = $ptchr_id;
	    $mem_info = $this->eventModel->get_mem_info_id_idname($memData);
	    
	    $ptchr_nm = $mem_info[0]['MEM_NM'];
	    
	    $nn_now = new Time('now');
	    $udata['mod_id'] = $_SESSION['user_id'];
	    $udata['mod_datetm'] = $nn_now;
	    $udata['buy_event_sno'] = $this->buy_sno;
	    $udata['ptchr_id'] = $ptchr_id;
	    $udata['ptchr_nm'] = $ptchr_nm;
	    
	    $this->eventModel->update_buy_event_mgmt_ptchr($udata);
	    
	    // 매출 정보의 판매강사도 변경해야한다.
	    $udata['comp_cd'] = $memData['comp_cd'];
	    $udata['bcoff_cd'] = $memData['bcoff_cd'];
	    $this->eventModel->update_sales_mgmt_ptchr($udata);
	    
	    // 강사변경 history insert
	    $nn_now = new Time('now');
	    $histModel = new HistModel();
	    
	    $hist['comp_cd'] = $_SESSION['comp_cd'];
	    $hist['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $hist['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $hist['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
	    $hist['ch_type'] = 'CHTP';
	    $hist['mem_sno'] = $this->buy_info['MEM_SNO'];
	    $hist['mem_id'] = $this->buy_info['MEM_ID'];
	    $hist['mem_nm'] = $this->buy_info['MEM_NM'];
	    $hist['bf_tchr_id'] = $this->buy_info['PTCHR_ID'];
	    $hist['bf_tchr_nm'] = $this->buy_info['PTCHR_NM'];
	    $hist['af_tchr_id'] = $ptchr_id;
	    $hist['af_tchr_nm'] = $ptchr_nm;
	    $hist['mod_id'] = $_SESSION['user_id'];
	    $hist['mod_datetm'] = $nn_now;
	    $hist['cre_id'] = $_SESSION['user_id'];
	    $hist['cre_datetm'] = $nn_now;
	    $histModel->insert_hist_event_tchr_ch($hist);
	    
	    $this->is_possable = true;
	    $this->rvalue['is_possable'] = $this->is_possable;
	    $this->rvalue['msg'] = "";
	}
	
	/**
	 * 수업 추가를 처리한다.
	 * @param array $clas_cnt
	 */
	private function func_clas_cnt($clas_cnt)
	{
	    $add_clas_cnt = $this->buy_info['ADD_SRVC_CLAS_CNT'] + $clas_cnt;
	    $left_clas_cnt = $this->buy_info['SRVC_CLAS_LEFT_CNT'] + $clas_cnt;
	    
	    $nn_now = new Time('now');
	    $udata['mod_id'] = $_SESSION['user_id'];
	    $udata['mod_datetm'] = $nn_now;
	    $udata['buy_event_sno'] = $this->buy_sno;
	    $udata['add_srvc_clas_cnt'] = $add_clas_cnt;
	    $udata['srvc_clas_left_cnt'] = $left_clas_cnt;
	    
	    $this->eventModel->update_buy_event_mgmt_clas_cnt($udata);
	    
	    // 수업 추가 history
	    $nn_now = new Time('now');
	    $histModel = new HistModel();
	    
	    $hist['comp_cd'] = $_SESSION['comp_cd'];
	    $hist['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $hist['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $hist['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
	    $hist['ch_type'] = 'ADDCNT';
	    $hist['mem_sno'] = $this->buy_info['MEM_SNO'];
	    $hist['mem_id'] = $this->buy_info['MEM_ID'];
	    $hist['mem_nm'] = $this->buy_info['MEM_NM'];
	    $hist['add_srvc_clas_cnt'] = $clas_cnt;
	    $hist['tchr_sno'] = $_SESSION['user_sno'];
	    $hist['tchr_id'] = $_SESSION['user_id'];
	    $hist['tchr_nm'] = $_SESSION['user_name'];
	    $hist['mod_id'] = $_SESSION['user_id'];
	    $hist['mod_datetm'] = $nn_now;
	    $hist['cre_id'] = $_SESSION['user_id'];
	    $hist['cre_datetm'] = $nn_now;
	    $histModel->insert_hist_event_srvc_add($hist);
	    
	    $this->is_possable = true;
	    $this->rvalue['is_possable'] = $this->is_possable;
	    $this->rvalue['msg'] = "";
	}
	
	
	
	/**
	 * 휴회일 추가를 처리한다.
	 * @param array $domcy_day
	 */
	private function func_domcy_day($domcy_day)
	{
	    
	    $add_domcy_day = $this->buy_info['ADD_DOMCY_DAY'] + $domcy_day; // 추가 휴회일을 구한다.
	    $left_domcy_day = $this->buy_info['LEFT_DOMCY_POSS_DAY'] + $domcy_day; // 남은 휴회 가능일을 구한다.
	    
	    $nn_now = new Time('now');
	    $udata['mod_id'] = $_SESSION['user_id'];
	    $udata['mod_datetm'] = $nn_now;
	    $udata['buy_event_sno'] = $this->buy_sno;
	    $udata['add_domcy_day'] = $add_domcy_day;
	    $udata['left_domcy_poss_day'] = $left_domcy_day;
	    
	    $this->eventModel->update_buy_event_mgmt_domcy_day($udata);
	    
	    // 휴회일 history insert
	    $nn_now = new Time('now');
	    $histModel = new HistModel();
	    
	    $hist['comp_cd'] = $_SESSION['comp_cd'];
	    $hist['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $hist['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $hist['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
	    $hist['ch_type'] = 'CHDD';
	    $hist['mem_sno'] = $this->buy_info['MEM_SNO'];
	    $hist['mem_id'] = $this->buy_info['MEM_ID'];
	    $hist['mem_nm'] = $this->buy_info['MEM_NM'];
	    $hist['bf_add_domcy_day'] = $this->buy_info['ADD_DOMCY_DAY'];
	    $hist['bf_add_domcy_cnt'] = $this->buy_info['ADD_DOMCY_CNT'];
	    $hist['af_add_domcy_day'] = $add_domcy_day;
	    $hist['af_add_domcy_cnt'] = $this->buy_info['ADD_DOMCY_CNT'];
	    $hist['mod_id'] = $_SESSION['user_id'];
	    $hist['mod_datetm'] = $nn_now;
	    $hist['cre_id'] = $_SESSION['user_id'];
	    $hist['cre_datetm'] = $nn_now;
	    $histModel->insert_hist_event_domcy_ch($hist);
	    
	    $this->is_possable = true;
	    $this->rvalue['is_possable'] = $this->is_possable;
	    $this->rvalue['msg'] = "";
	}
	
	/**
	 * 휴회일 추가를 처리한다.
	 * @param array $domcy_day
	 */
	private function func_domcy_cnt($domcy_cnt)
	{
	    $add_domcy_cnt = $this->buy_info['ADD_DOMCY_CNT'] + $domcy_cnt; // 추가 휴회일을 구한다.
	    $left_domcy_cnt = $this->buy_info['LEFT_DOMCY_POSS_CNT'] + $domcy_cnt; // 남은 휴회 가능일을 구한다.
	    
	    $nn_now = new Time('now');
	    $udata['mod_id'] = $_SESSION['user_id'];
	    $udata['mod_datetm'] = $nn_now;
	    $udata['buy_event_sno'] = $this->buy_sno;
	    $udata['add_domcy_cnt'] = $add_domcy_cnt;
	    $udata['left_domcy_poss_cnt'] = $left_domcy_cnt;
	    
	    $this->eventModel->update_buy_event_mgmt_domcy_cnt($udata);
	    
	    // 휴회일 history insert
	    $nn_now = new Time('now');
	    $histModel = new HistModel();
	    
	    $hist['comp_cd'] = $_SESSION['comp_cd'];
	    $hist['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $hist['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $hist['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
	    $hist['ch_type'] = 'CHDC';
	    $hist['mem_sno'] = $this->buy_info['MEM_SNO'];
	    $hist['mem_id'] = $this->buy_info['MEM_ID'];
	    $hist['mem_nm'] = $this->buy_info['MEM_NM'];
	    $hist['bf_add_domcy_day'] = $this->buy_info['ADD_DOMCY_DAY'];
	    $hist['bf_add_domcy_cnt'] = $this->buy_info['ADD_DOMCY_CNT'];
	    $hist['af_add_domcy_day'] = $this->buy_info['ADD_DOMCY_DAY'];
	    $hist['af_add_domcy_cnt'] = $add_domcy_cnt;
	    $hist['mod_id'] = $_SESSION['user_id'];
	    $hist['mod_datetm'] = $nn_now;
	    $hist['cre_id'] = $_SESSION['user_id'];
	    $hist['cre_datetm'] = $nn_now;
	    $histModel->insert_hist_event_domcy_ch($hist);
	    
	    $this->is_possable = true;
	    $this->rvalue['is_possable'] = $this->is_possable;
	    $this->rvalue['msg'] = "";
	}
	
	/**
	 * 종료일을 변경한다.
	 * @param string $edate
	 */
	private function func_edate($edate)
	{
		$this->buy_event_list(); // 회원의 이용중, 예약됨 상품들을 가져온다.
		
		$this->set_value['exr_s_date'] = $this->buy_info['EXR_S_DATE'];
		$this->set_value['exr_e_date'] = $edate;
		
		if ($this->buy_info['LOCKR_SET'] == 'Y')
		{
		    $this->remake_edate_buy_event_info_lockr(); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
		} else 
		{
		    $this->remake_edate_buy_event_info(); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
		}
		
		
		$this->func_remake_buy_update(); //재계산되어야할 상품들을 시작 종료일을 업데이트 처리한다.
		
		$this->func_buy_edate_update(); //종료날짜를 변경한 데이터를 업데이트 한다.
		
		$this->is_possable = true;
		
		$this->rvalue['after'] = $this->after_buy;
		$this->rvalue['is_possable'] = $this->is_possable;
	}
	
	private function remake_edate_buy_event_info_lockr()
	{
	    $sell_cate_code = $this->buy_info['1RD_CATE_CD'].$this->buy_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    $after_buy_array = array(); // 이후에 검색되는 상품들을 배열에 저장하기 위함.
	    $after_buy_i = 0; // 배열 증가 변수
	    
	    if ($this->buy_list != null)
	    {
	        foreach($this->buy_list as $r)
	        {
	            if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->buy_info['LOCKR_NO'])
	            {
	                if (strtotime($this->set_value['exr_s_date']) < strtotime($r['EXR_S_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
	                {
	                    $after_buy_array[$after_buy_i] = $r;
	                    $after_buy_i++ ;
	                }
	            }
	        }
	    }
	    
	    $this->after_buy = $after_buy_array;
	    $this->remake_buy_event_date();
	}
	
	
	private function remake_edate_buy_event_info()
	{
	    $sell_cate_code = $this->buy_info['1RD_CATE_CD'].$this->buy_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
		$after_buy_array = array(); // 이후에 검색되는 상품들을 배열에 저장하기 위함.
		$after_buy_i = 0; // 배열 증가 변수
		
		if ($this->buy_list != null)
		{
			foreach($this->buy_list as $r)
			{
			    if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
			    {
			        if (strtotime($this->set_value['exr_s_date']) < strtotime($r['EXR_S_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
			        {
			            $after_buy_array[$after_buy_i] = $r;
			            $after_buy_i++ ;
			        }
			    }
				
			}
		}
		
		$this->after_buy = $after_buy_array;
		$this->remake_buy_event_date();
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
	 * 시작일을 변경한다.
	 */
	private function func_sdate($sdate)
	{
	    $this->buy_event_list(); // 회원의 이용중, 예약됨 상품들을 가져온다.
	    
	    // 입력된 시작일이 이용중인 상품중의 범위안에 있는지를 체크한다.
	    $this->chk_sdate($sdate);
	    if ($this->is_possable == false) return;
	    
	    $this->calc_se_date($sdate); // 입력된 시작일 기준으로 종료일을 구한다.
	    
	    if ($this->buy_info['LOCKR_SET'] == 'Y')
	    {
	        $this->remake_buy_event_info_lockr(); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	    } else 
	    {
	        $this->remake_buy_event_info(); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	    }
	    
	    
	    $this->func_remake_buy_update(); //재계산되어야할 상품들을 시작 종료일을 업데이트 처리한다.
	    
	    $this->func_buy_sdate_update();
	    
	    $this->rvalue['is_possable'] = $this->is_possable;
	    
// 	    $this->rvalue['set_Value'] = $this->set_value;
// 	    $this->rvalue['buy_list'] = $this->buy_list;
// 	    $this->rvalue['buy_mem_sno'] = $this->buy_mem_sno;
// 	    $this->rvalue['buy_info'] = $this->buy_info;
	    
// 	    $this->rvalue['btype'] = $this->btype;
// 	    $this->rvalue['test'] = "ok";
	    
	}
	
	/**
	 * 회원이 이미 구매한 상품을 가져온다. (이용중, 예약됨 상품만 )
	 */
	private function buy_event_list()
	{
	    $eData['comp_cd'] = $_SESSION['comp_cd'];
	    $eData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $eData['mem_sno'] = $this->buy_mem_sno;
	    
	    $buy_event_list = $this->eventModel->get_buy_events_use_memsno($eData);
	    if(count($buy_event_list) > 0) $this->buy_list = $buy_event_list;
	}
	
	/**
	 * 입력된 시작일이 이용중인 상품중의 범위안에 있는지를 체크한다.
	 */
	private function chk_sdate($sdate)
	{
	    if ($this->buy_list != null)
	    {
	        if ($this->buy_info['LOCKR_SET'] == "Y")
	        {
	            foreach($this->buy_list as $r)
	            {
	                if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] ==$this->buy_info['1RD_CATE_CD'].$this->buy_info['2RD_CATE_CD'] && $r['EVENT_STAT'] == "00" && $this->buy_info['LOCKR_NO'] == $r['LOCKR_NO'] && $this->buy_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->buy_info['LOCKR_GENDR_SET'] == $r['LOCKR_GENDR_SET'])
	                {
	                    if (strtotime($sdate) >= strtotime($r['EXR_S_DATE']) && strtotime($sdate) <= strtotime($r['EXR_E_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
	                    {
	                        $this->is_possable = false;
	                        $this->rvalue['is_possable'] = $this->is_possable;
	                        $this->rvalue['msg'] = "이용중인 상품 기간내에는 시작일을 변경 할 수 없습니다.";
	                    }
	                }
	            }
	        } else 
	        {
	            foreach($this->buy_list as $r)
	            {
	                if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] ==$this->buy_info['1RD_CATE_CD'].$this->buy_info['2RD_CATE_CD'] && $r['EVENT_STAT'] == "00")
	                {
	                    if (strtotime($sdate) >= strtotime($r['EXR_S_DATE']) && strtotime($sdate) <= strtotime($r['EXR_E_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
	                    {
	                        $this->is_possable = false;
	                        $this->rvalue['is_possable'] = $this->is_possable;
	                        $this->rvalue['msg'] = "이용중인 상품 기간내에는 시작일을 변경 할 수 없습니다.";
	                    }
	                }
	            }
	        }
	        
	        
	    }
	}
	
	/**
	 * 운동 시작일 기준으로 판매상품의 조건을 이용하여 종료일을 구함.
	 */
	private function calc_se_date($sdate)
	{
	    $uprod = $this->buy_info['USE_PROD']; // 기간
	    $uunit = $this->buy_info['USE_UNIT']; // 단위
	    
	    if ($uunit == 'M')
	    {
	        $plus_dd = "+" . $uprod . " months";
	    } else
	    {
	        $plus_dd = "+" . $uprod . " days";
	    }
	    
	    $calc_e_date = date("Y-m-d", strtotime($plus_dd, strtotime($sdate)));
	    $edate = date("Y-m-d", strtotime("-1 days", strtotime($calc_e_date)));
	    // 종료일을 구한 뒤에 추가 정보가 있는지를 확인한다. (서비스로 준 날짜가 있는지를 확인하여 처리 해야한다.)
	    $add_days = $this->buy_info['ADD_SRVC_EXR_DAY'];
	    $edate = date("Y-m-d", strtotime("+".$add_days." days", strtotime($edate)));
	    
	    $this->set_value['exr_s_date'] = $sdate;
	    $this->set_value['exr_e_date'] = $edate;
	}
	
	/**
	 * 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	 */
	private function remake_buy_event_info_lockr()
	{
	    
	    $sell_cate_code = $this->buy_info['1RD_CATE_CD'].$this->buy_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    
	    if ($this->buy_list != null)
	    {
	        $after_buy_sno_chk = false; // 하나라도 범위에 있으면 true 가 체크 되며 이후에 검색되는 상품들을 배열로 저장함.
	        $after_buy_array = array(); // 이후에 검색되는 상품들을 배열에 저장하기 위함.
	        $after_buy_i = 0; // 배열 증가 변수
	        
	        foreach($this->buy_list as $r)
	        {
	            if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->buy_info['LOCKR_NO'] && $this->buy_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->buy_info['LOCKR_GENDR_SET'] == $r['LOCKR_GENDR_SET'])
	            {
	                // 이곳에서 로직 구성해야함
	                // 운동시작일이 하나라도 범위안에 있으면 true;
	                // 하나라도 범위에 있으면 제일 마지막 적용된 종료일을 구해야한다.
	                
	                if (strtotime($this->set_value['exr_s_date']) >= strtotime($r['EXR_S_DATE']) && strtotime($this->set_value['exr_s_date']) <= strtotime($r['EXR_E_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
	                {
	                    $after_buy_sno_chk = true;
	                }
	                
	                if ($after_buy_sno_chk == true && $r['BUY_EVENT_SNO'] != $this->buy_sno )
	                {
	                    $after_buy_array[$after_buy_i] = $r;
	                    $after_buy_i++ ;
	                }
	                
	            }
	        }
	        
	        // 이미 구매한 상품의 시작일 ~ 종료일 사이에 시작일 값이 없다면 : 구매시작일 이후에 존재하는 값들을 변경 시켜야 한다.
	        if ($after_buy_sno_chk == false)
	        {
                foreach($this->buy_list as $r)
                {
                    if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $this->buy_info['LOCKR_NO'] && $this->buy_info['LOCKR_KND'] == $r['LOCKR_KND'] && $this->buy_info['LOCKR_GENDR_SET'] == $r['LOCKR_GENDR_SET'])
                    {
                        if (strtotime($this->set_value['exr_s_date']) < strtotime($r['EXR_S_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
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
	    
	    $this->rvalue['after'] = $this->after_buy; // test value 보기
	    
	}
	
	/**
	 * 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	 */
	private function remake_buy_event_info()
	{
	    
	    $sell_cate_code = $this->buy_info['1RD_CATE_CD'].$this->buy_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    
	    if ($this->buy_list != null)
	    {
	        $after_buy_sno_chk = false; // 하나라도 범위에 있으면 true 가 체크 되며 이후에 검색되는 상품들을 배열로 저장함.
	        $after_buy_array = array(); // 이후에 검색되는 상품들을 배열에 저장하기 위함.
	        $after_buy_i = 0; // 배열 증가 변수
	        
	        foreach($this->buy_list as $r)
	        {
	            if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
	            {
	                // 이곳에서 로직 구성해야함
	                // 운동시작일이 하나라도 범위안에 있으면 true;
	                // 하나라도 범위에 있으면 제일 마지막 적용된 종료일을 구해야한다.
	            	
	                if (strtotime($this->set_value['exr_s_date']) >= strtotime($r['EXR_S_DATE']) && strtotime($this->set_value['exr_s_date']) <= strtotime($r['EXR_E_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
	                {
	                    $after_buy_sno_chk = true;
	                }
	                
	                if ($after_buy_sno_chk == true && $r['BUY_EVENT_SNO'] != $this->buy_sno )
	                {
	                    $after_buy_array[$after_buy_i] = $r;
	                    $after_buy_i++ ;
	                }
	                
	            }
	        }
	        
	        // 이미 구매한 상품의 시작일 ~ 종료일 사이에 시작일 값이 없다면 : 구매시작일 이후에 존재하는 값들을 변경 시켜야 한다.
	        if ($after_buy_sno_chk == false)
	        {
                foreach($this->buy_list as $r)
                {
                    if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
                    {
                        if (strtotime($this->set_value['exr_s_date']) < strtotime($r['EXR_S_DATE']) && $r['BUY_EVENT_SNO'] != $this->buy_sno)
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
	    
	    $this->rvalue['after'] = $this->after_buy; // test value 보기
	    
	}
	
	/**
	 * 재 적용해야할 운동상품들에 대한 시작, 종료일을 재 계산한다.
	 */
	private function remake_buy_event_date()
	{
	    $last_edate = $this->set_value['exr_e_date'];
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
	    // 종료일을 구한 뒤에 추가 정보가 있는지를 확인한다. (서비스로 준 날짜가 있는지를 확인하여 처리 해야한다.)
	    $edate = date("Y-m-d", strtotime("+".$add_days." days", strtotime($edate)));
	    
	    $return_date['sdate'] = $sdate;
	    $return_date['edate'] = $edate;
	    
	    return $return_date;
	}
	
	/**
	 * 시작일 상품의 정보를 업데이트 한다.
	 */
	private function func_buy_sdate_update()
	{
	    if ($this->set_value['exr_s_date'] == date('Y-m-d'))	    
	    {
	        $udata['event_stat'] = "00"; //예약됨
	    } else 
	    {
	        $udata['event_stat'] = "01"; //이용중
	    }
	    
	    $nn_now = new Time('now');
	    $udata['mod_id'] = $_SESSION['user_id'];
	    $udata['mod_datetm'] = $nn_now;
	    $udata['buy_event_sno'] = $this->buy_sno;
	    $udata['exr_s_date'] = $this->set_value['exr_s_date'];
	    $udata['exr_e_date'] = $this->set_value['exr_e_date'];
	    
	    $this->eventModel->update_buy_event_mgmt_redate($udata);
	    
	    // TODO @@@ 이용중에 대한 락커 업데이트의 경우에는 락커 lockr_room 테이블에도 업데이트가 이루어져야 한다.
	    
	    // 날짜변경 history insert
	    $nn_now = new Time('now');
	    $histModel = new HistModel();
	    
	    $hist['comp_cd'] = $_SESSION['comp_cd'];
	    $hist['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $hist['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
	    $hist['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
	    $hist['ch_type'] = 'CHES';
	    $hist['mem_sno'] = $this->buy_info['MEM_SNO'];
	    $hist['mem_id'] = $this->buy_info['MEM_ID'];
	    $hist['mem_nm'] = $this->buy_info['MEM_NM'];
	    $hist['bf_exr_s_date'] = $this->buy_info['EXR_S_DATE'];
	    $hist['bf_exr_e_date'] = $this->buy_info['EXR_E_DATE'];
	    $hist['af_exr_s_date'] = $this->set_value['exr_s_date'];
	    $hist['af_exr_e_date'] = $this->set_value['exr_e_date'];
	    $hist['mod_id'] = $_SESSION['user_id'];
	    $hist['mod_datetm'] = $nn_now;
	    $hist['cre_id'] = $_SESSION['user_id'];
	    $hist['cre_datetm'] = $nn_now;
	    $histModel->insert_hist_event_date_ch($hist);
	    
	}
	
	/**
	 * 종료일 상품의 정보를 업데이트 한다.
	 */
	private function func_buy_edate_update()
	{
		$from = new \DateTime( $this->buy_info['EXR_E_DATE'] );
		$to = new \DateTime( $this->set_value['exr_e_date'] );
		$add_days = $from -> diff( $to ) -> days;
		if ( $from > $to ) 
		{ 
			// 빼기
			//$add_days = '-' . $add_days;
			$sum_days = $this->buy_info['ADD_SRVC_EXR_DAY'] - $add_days;
		} else 
		{
			// 더하기
			$sum_days = $this->buy_info['ADD_SRVC_EXR_DAY'] + $add_days;
		}
		
		$nn_now = new Time('now');
		$udata['mod_id'] = $_SESSION['user_id'];
		$udata['mod_datetm'] = $nn_now;
		$udata['buy_event_sno'] = $this->buy_sno;
		$udata['exr_s_date'] = $this->set_value['exr_s_date'];
		$udata['exr_e_date'] = $this->set_value['exr_e_date'];
		$udata['add_srvc_exr_day'] = $sum_days;
		
		$this->eventModel->update_buy_event_mgmt_redate_edate($udata);
		
		// TODO @@@ 이용중에 대한 락커 업데이트의 경우에는 락커 lockr_room 테이블에도 업데이트가 이루어져야 한다. 
		
		// 날짜변경 history insert
		$nn_now = new Time('now');
		$histModel = new HistModel();
		
		$hist['comp_cd'] = $_SESSION['comp_cd'];
		$hist['bcoff_cd'] = $_SESSION['bcoff_cd'];
		$hist['buy_event_sno'] = $this->buy_info['BUY_EVENT_SNO'];
		$hist['sell_event_nm'] = $this->buy_info['SELL_EVENT_NM'];
		$hist['ch_type'] = 'CHEE';
		$hist['mem_sno'] = $this->buy_info['MEM_SNO'];
		$hist['mem_id'] = $this->buy_info['MEM_ID'];
		$hist['mem_nm'] = $this->buy_info['MEM_NM'];
		$hist['bf_exr_s_date'] = $this->buy_info['EXR_S_DATE'];
		$hist['bf_exr_e_date'] = $this->buy_info['EXR_E_DATE'];
		$hist['af_exr_s_date'] = $this->set_value['exr_s_date'];
		$hist['af_exr_e_date'] = $this->set_value['exr_e_date'];
		$hist['mod_id'] = $_SESSION['user_id'];
		$hist['mod_datetm'] = $nn_now;
		$hist['cre_id'] = $_SESSION['user_id'];
		$hist['cre_datetm'] = $nn_now;
		$histModel->insert_hist_event_date_ch($hist);
		
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
	            
	            if ($this->after_buy[$i]['RE_EXR_S_DATE'] == date('Y-m-d'))
	            {
	                $udata['event_stat'] = "00"; //예약됨
	            } else
	            {
	                $udata['event_stat'] = "01"; //이용중
	            }
	            
	            $this->eventModel->update_buy_event_mgmt_redate($udata);
	        }
	    }
	    
	}
	
	
    
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}