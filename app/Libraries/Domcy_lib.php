<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\EventModel;
use App\Models\DomcyModel;
use App\Models\MemModel;

class Domcy_lib {

	/**
	 * EventModel
	 * @var object
	 */
	private $modelEvent;
	
	/**
	 * DomcyModel
	 * @var object
	 */
	private $modelDomcy;
	
	/**
	 * MemModel
	 * @var object
	 */
	private $modelMem;
    
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
     * 회원 이름
     * @var string
     */
    private $set_mem_nm;
    
    /**
     * 회원 아이디
     * @var string
     */
    private $set_mem_id;
    
    
    
    private $set_initVar;
    
    /**
     * 리턴값
     * @var array
     */
    private $retunValue = array();
    
    /**
     * $initVar['comp_cd']; <br>
     * $initVar['bcoff_cd']; <br>
     * $initVar['mem_sno']; <br>
     * $initVar['type']; // user , cron <br>
     * @param array $initVar
     */
    public function __construct($initVar) {
    	$this->set_initVar = $initVar;
    	$this->modelDomcy = new DomcyModel();
    	$this->modelEvent = new EventModel();
    	
		if ($initVar['type'] == "user")
		{
			$this->modelMem = new MemModel();
			
			$this->set_mem_sno = $initVar['mem_sno'];
			$this->set_comp_cd = $initVar['comp_cd'];
			$this->set_bcoff_cd = $initVar['bcoff_cd'];
			
			$get_mem_info = $this->modelMem->get_mem_info_mem_sno($this->set_initVar);
			$this->set_mem_id = $get_mem_info[0]['MEM_ID'];
			$this->set_mem_nm = $get_mem_info[0]['MEM_NM'];
		}
	}
	
	// /**
	//  * 회원이 가능한 휴회일 휴회횟수를 가져온다.
	//  * @return array['acc_mthd']
	//  */
	// public function get_poss_domcy()
	// {
	//     // 이용중인 상품을 가져온다.
	//     $ueData = $this->set_initVar;
	//     $ueData['event_stat'] = "00";
	//     $event_use_list = $this->modelEvent->list_buy_event_mem_sno_event_stat($ueData);
        
	//     $diff_domcy_day = 0;
	//     $poss_domcy['day'] = 0;
	//     $poss_domcy['cnt'] = 0;
	//     $poss_domcy['buy_event_sno'] = "";
	// 	$poss_domcy['s_date'] = "";
	// 	$poss_domcy['e_date'] = "";
	//     foreach ($event_use_list as $r)
	//     {
	//         if ($r['DOMCY_POSS_EVENT_YN'] == "Y" && $r['LEFT_DOMCY_POSS_CNT'] > 0 && $r['RECVB_AMT'] == 0)
	//         {
	//             if ($diff_domcy_day < $r['LEFT_DOMCY_POSS_DAY'])
	//             {
	//                 $diff_domcy_day = $r['LEFT_DOMCY_POSS_DAY'];
	                
	//                 $poss_domcy['buy_event_sno'] = $r['BUY_EVENT_SNO'];
	//                 $poss_domcy['day'] = $r['LEFT_DOMCY_POSS_DAY'];
	//                 $poss_domcy['cnt'] = $r['LEFT_DOMCY_POSS_CNT'];
	// 				$poss_domcy['s_date'] = $r['EXR_S_DATE'];
	// 				$poss_domcy['e_date'] = $r['EXR_E_DATE'];
	//             }
	//         }
	//     }

	//     return $poss_domcy;
	// }


	/**
	 * 회원이 가능한 휴회일, 휴회횟수를 가져온다.
	 * @return array ['list' => [...], 'total_day' => int, 'total_cnt' => int]
	 */
	public function get_poss_domcy()
	{
	    // 이용중인 상품을 가져온다.
	    $ueData = $this->set_initVar;
	    $ueData['event_stat'] = "00";
	    $event_use_list = $this->modelEvent->list_buy_event_mem_sno_event_stat($ueData);
        
	    // $diff_domcy_day = 0;
	    // $poss_domcy['day'] = 0;
	    // $poss_domcy['cnt'] = 0;
	    // $poss_domcy['buy_event_sno'] = "";
		
		// $poss_domcy['s_date'] = "";
		// $poss_domcy['e_date'] = "";
	    // foreach ($event_use_list as $r)
	    // {
	    //     if ($r['DOMCY_POSS_EVENT_YN'] == "Y" && $r['LEFT_DOMCY_POSS_CNT'] > 0 && $r['RECVB_AMT'] == 0)
	    //     {
	    //         if ($diff_domcy_day < $r['LEFT_DOMCY_POSS_DAY'])
	    //         {
	    //             $diff_domcy_day = $r['LEFT_DOMCY_POSS_DAY'];
	                
	    //             $poss_domcy['buy_event_sno'] = $r['BUY_EVENT_SNO'];
	    //             $poss_domcy['day'] = $r['LEFT_DOMCY_POSS_DAY'];
	    //             $poss_domcy['cnt'] = $r['LEFT_DOMCY_POSS_CNT'];
		// 			$poss_domcy['s_date'] = $poss_domcy['s_date'] == "" ? $r['EXR_S_DATE']  : ($poss_domcy['s_date'] > $r['EXR_S_DATE'] ?  $r['EXR_S_DATE'] : $poss_domcy['s_date']);
		// 			$poss_domcy['e_date'] = $poss_domcy['e_date'] == "" ? $r['EXR_E_DATE']  : ($poss_domcy['e_date'] < $r['EXR_E_DATE'] ?  $r['EXR_E_DATE'] : $poss_domcy['e_date']);
		// 		}	
	    //     }
	    // }


		$diff_domcy_day = 0;
	    $total_day = 0;
		$total_cnt = 0;
	    $poss_domcy['buy_event_sno'] = "";
		$poss_domcy['s_date'] = "";
		$poss_domcy['e_date'] = "";
		$poss_domcy_list = [];
	    foreach ($event_use_list as $r)
		{
			if ($r['DOMCY_POSS_EVENT_YN'] == "Y" && $r['LEFT_DOMCY_POSS_CNT'] > 0 && $r['LEFT_DOMCY_POSS_DAY']) // 미수금 있는 경우 제외
			{
				$poss_domcy = [
					'buy_event_sno' => $r['BUY_EVENT_SNO'],
					'sell_event_nm' => $r['SELL_EVENT_NM'],
					'poss_day' => (int)$r['LEFT_DOMCY_POSS_DAY'],
					'poss_cnt' => (int)$r['LEFT_DOMCY_POSS_CNT'],
					'day' => (int)$r['LEFT_DOMCY_POSS_DAY'],
					'cnt' => (int)$r['LEFT_DOMCY_POSS_CNT'],
					's_date' => $r['EXR_S_DATE'],
					'e_date' => $r['EXR_E_DATE'],
				];

				$poss_domcy_list[] = $poss_domcy;
				$total_day += $r['LEFT_DOMCY_POSS_DAY'];
				$total_cnt += $r['LEFT_DOMCY_POSS_CNT'];
			}
		}

		return [
			'list' => $poss_domcy_list,
			'day' => $total_day,
			'cnt' => $total_cnt,
			'buy_event_sno' => ""
		];
	}
	
	
	/**
	 * 휴회 신청을 insert 한다. <br>
	 * $var['domcy_aply_buy_sno'] <br>
	 * $var['domcy_s_date'] <br>
	 * $var['domcy_use_day'] <br>
	 */
	public function insert_domcy($var)
	{
		$nn_now = new Time('now');
		
		$domData['domcy_aply_buy_sno'] = $var['domcy_aply_buy_sno']; // 휴회 기준이되는 구매 상품
		$domData['comp_cd']  = $this->set_comp_cd; // 회사 코드
		$domData['bcoff_cd'] = $this->set_bcoff_cd; // 지점 코드
		
		$domData['mem_sno']  = $this->set_mem_sno; // 회원_일련번호
		$domData['mem_id']   = $this->set_mem_id; // 회원_아이디
		$domData['mem_nm']   = $this->set_mem_nm; // 회원_명
		
		$domData['domcy_s_date']    = $var['domcy_s_date']; // 휴회 시작일
		$domData['domcy_e_date']    = $this->calcu_domcy_edate($var['domcy_s_date'], $var['domcy_use_day']); // 휴회 종료일
		$domData['domcy_use_day']   = $var['domcy_use_day']; // 휴회 사용일
		$domData['domcy_mgmt_stat'] = "00"; //휴회신청
		
		$domData['cre_id']     = $_SESSION['user_id'];
		$domData['cre_datetm'] = $nn_now;
		$domData['mod_id']     = $_SESSION['user_id'];
		$domData['mod_datetm'] = $nn_now;
		
		$this->modelDomcy->insert_domcy_mgmt($domData);
		
	}

	/**
	 * 휴회 신청을 insert 한다. <br>
	 * $var['domcy_aply_buy_sno'] <br>
	 * $var['domcy_s_date'] <br>
	 * $var['domcy_use_day'] <br>
	 */
	public function update_buy_event_domcy($var)
	{
		$this->modelDomcy->update_buy_event_domcy($var);
	}
	
	/**
	 * [CRON] 휴회을 실행한다.
	 */
	public function domcy_cron_run()
	{
		$now_date = date('Y-m-d'); // 휴회을 실행할 일자.
		//$now_date = "2024-06-27"; // test 휴회 실행할 일자.
		
		//입력 날짜 기준으로 실행해야할 휴회 리스트를 가져온다.
		$get_run_list = $this->modelDomcy->run_list_domcy(array("domcy_s_date" => $now_date));
		
		// 하나가 한명의 휴회을 처리 해야하는 부분이다.
		foreach ($get_run_list as $r)
		{
			$this->domcy_event_find($r);
		}
	}
	
	/**
	 * [CRON] 휴회중 출석 처리
	 * @param string $mem_sno 회원_일련번호
	 */
	public function domcy_cron_attd($mem_sno)
	{
	    $attdData['mem_sno'] = $mem_sno;
	    $get_domcy_attd = $this->modelDomcy->get_domcy_attd($attdData);
	    
	    foreach($get_domcy_attd as $r)
	    {
	        $this->domcy_pre_attd_event_proc($r);
	    }
	}
	
	/**
	 * 세부 휴회처리 리스트에서 휴회 종료일에 따라 휴회 종료 처리를 해야한다.
	 * 휴회 종료 처리는 다음날 하게 되며 하루전 휴회 종료에 대해서 종료 처리를 해야한다.
	 */
	public function domcy_cron_hist_end()
	{
	    $nn_now = new Time('now');
	    $now_date = date('Y-m-d');
	    $end_date = $this->calcu_domcy_edate($now_date,0);
	    
	    $upVar['mod_id'] = 'cron';
	    $upVar['mod_datetm'] = $nn_now;
	    $upVar['domcy_real_e_date'] = $end_date;
	    $upVar['domcy_mgmt_stat'] = '09'; // 09 휴회완료
	    $this->modelDomcy->update_domcy_hist_end($upVar);
	}
	
	/**
	 * 대표 휴회 신청 리스트를 종료 처리한다.
	 * 휴회 종료 처리는 다음날 하게 되며 하루전 휴회 종료에 대해서 종료 처리를 해야한다.
	 */
	public function domcy_cron_end()
	{
	    $nn_now = new Time('now');
	    $now_date = date('Y-m-d');
	    $end_date = $this->calcu_domcy_edate($now_date,0);
	    
	    $upVar['mod_id'] = 'cron';
	    $upVar['mod_datetm'] = $nn_now;
	    $upVar['domcy_e_date'] = $end_date;
	    $upVar['domcy_mgmt_stat'] = '09'; // 09 휴회완료
	    $this->modelDomcy->update_domcy_end($upVar);
	}
	
	/**
	 * ====================================================================================
	 * ssssssssssss
	 * ====================================================================================
	 */
	
	/**
	 * 중간 휴회처리로 인하여 휴회을 중간 환산 처리를 함.
	 */
	private function domcy_pre_attd_event_proc($domcy_info)
	{
	    // 오늘 출석을 하였으면 실제 휴회 종료는 어제가 되어야 한다.
	    $now_date = date('Y-m-d');
	    $add_days = $this->calcu_diff_date($domcy_info['DOMCY_REAL_E_DATE'],$now_date)-1;
	    
	    // 휴회 시작일로부터 중간 출석일까지의 실 휴회 사용일
	    $real_use = $this->calcu_diff_date($domcy_info['DOMCY_REAL_S_DATE'],$now_date);
	    $reData['domcy_real_use_day'] = $real_use;
	    
	    // 사용후 휴일남을 일수를 재계산한다.
	    $reData['use_af_domcy_day'] = $domcy_info['USE_BF_DOMCY_DAY'] - $real_use;
	    
	    $reData['domcy_real_e_date'] = $this->calcu_domcy_edate($domcy_info['DOMCY_REAL_E_DATE'],$add_days+1);
	    
	    $reData['use_af_exr_e_date'] = $this->calcu_domcy_edate($domcy_info['USE_AF_EXR_E_DATE'],$add_days+1);
	    
	    //echo "-------------------------" . "<br />";
	    //_vardump($domcy_info);
	    //_vardump("aa2342aa".$add_days);
	    //_vardump($reData);
	    //_vardump($domcy_info['USE_AF_EXR_E_DATE']);
	    //_vardump($real_use);
	    
	    $this->update_domcy_hist($domcy_info,$reData);
	    $this->update_domcy_cron_end_attd($domcy_info);
	    
	    $this->func_edate_attd($domcy_info, $reData['use_af_exr_e_date'], $reData);
	}
	
	/**
	 * 대표 휴회 신청 리스트를 종료 처리한다.
	 * 휴회 종료 처리는 다음날 하게 되며 하루전 휴회 종료에 대해서 종료 처리를 해야한다.
	 */
	public function update_domcy_cron_end_attd($domcy_info)
	{
	    $nn_now = new Time('now');
	    $now_date = date('Y-m-d');
	    $end_date = $this->calcu_domcy_edate($now_date,0);
	    
	    $upVar['domcy_mgmt_sno'] = $domcy_info['DOMCY_MGMT_SNO'];
	    $upVar['mod_id'] = 'cron';
	    $upVar['mod_datetm'] = $nn_now;
	    $upVar['domcy_e_date'] = $end_date;
	    $upVar['domcy_mgmt_stat'] = '09'; // 09 휴회완료
	    $this->modelDomcy->update_domcy_end_attd($upVar);
	}
	
	/**
	 * domcy_mgmt_hist_tbl 을 업데이트 처리한다.
	 * @param array $domcy_info
	 * @param array $reData
	 */
	private function update_domcy_hist($domcy_info,$reData)
	{
	    $nn_now = new Time('now');
	    
	    $upVar['domcy_mgmt_hist_sno'] = $domcy_info['DOMCY_MGMT_HIST_SNO'];
	    
	    $upVar['domcy_real_use_day'] = $reData['domcy_real_use_day'];
	    $upVar['domcy_real_e_date'] = $reData['domcy_real_e_date'];
	    $upVar['use_af_domcy_day'] = $reData['use_af_domcy_day'];
	    $upVar['use_af_exr_e_date'] = $reData['use_af_exr_e_date'];
	    $upVar['domcy_mgmt_stat'] = "09"; // 09 휴회 완료
	    $upVar['mod_id'] = "cron";
	    $upVar['mod_datetm'] = $nn_now;
	    $this->modelDomcy->update_domcy_mgmt_hist($upVar);
	}
	
	/**
	 * 종료일을 변경한다.
	 * @param string $sdate
	 * @param string $edate
	 */
	private function func_edate_attd($domcy_info,$edate,$reData)
	{
	    $buyData['comp_cd'] = $domcy_info['COMP_CD'];
	    $buyData['bcoff_cd'] = $domcy_info['BCOFF_CD'];
	    $buyData['buy_event_sno'] = $domcy_info['BUY_EVENT_SNO'];
	    $get_buy_info = $this->modelEvent->get_buy_event_buy_sno($buyData);
	    $buy_info = $get_buy_info[0];
	    
	    $setVar['comp_cd'] = $buy_info['COMP_CD'];
	    $setVar['bcoff_cd'] = $buy_info['BCOFF_CD'];
	    $setVar['mem_sno'] = $buy_info['MEM_SNO'];
	    
	    $buy_list = array();
	    $set_value = array();
	    
	    $buy_list = $this->buy_event_list($setVar); // 회원의 이용중, 예약됨 상품들을 가져온다.
	    
	    $set_value['exr_s_date'] = $buy_info['EXR_S_DATE'];
	    $set_value['exr_e_date'] = $edate;
	    
	    if ($buy_info['LOCKR_SET'] == 'Y')
	    {
	        $after_buy = $this->remake_edate_buy_event_info_lockr($buy_info,$buy_list,$set_value); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	    } else
	    {
	        $after_buy = $this->remake_edate_buy_event_info($buy_info,$buy_list,$set_value); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	    }
	    
	    $this->func_remake_buy_update($after_buy); //재계산되어야할 상품들을 시작 종료일을 업데이트 처리한다.
	    $this->func_buy_edate_domcy_attd_update($buy_info,$set_value,$reData); //종료날짜를 변경한 데이터를 업데이트 한다.
	    
	    // 	    $this->is_possable = true;
	    // 	    $this->rvalue['after'] = $this->after_buy;
	    // 	    $this->rvalue['is_possable'] = $this->is_possable;
	}
	
	/**
	 * 종료일 상품의 정보를 업데이트 한다.
	 */
	private function func_buy_edate_domcy_attd_update($buy_info,$set_value,$reData)
	{
	    
	    $nn_now = new Time('now');
	    $udata['mod_id'] = 'cron';
	    $udata['mod_datetm'] = $nn_now;
	    $udata['buy_event_sno'] = $buy_info['BUY_EVENT_SNO'];
	    $udata['exr_s_date'] = $set_value['exr_s_date'];
	    $udata['exr_e_date'] = $set_value['exr_e_date'];
	    
	    $udata['use_domcy_day'] = $reData['domcy_real_use_day'];
	    $udata['left_domcy_poss_day'] = $reData['use_af_domcy_day'];
	    $udata['left_domcy_poss_cnt'] = $buy_info['LEFT_DOMCY_POSS_CNT'];
	    
	    $this->modelEvent->update_buy_event_mgmt_redate_edate_domcy($udata);
	    
	    // TODO @@@ 이용중에 대한 락커 업데이트의 경우에는 락커 lockr_room 테이블에도 업데이트가 이루어져야 한다.
	}
	
	/**
	 * 두날짜 사이를 계산한다.
	 * @param string $sdate
	 * @param string $edate
	 */
	private function calcu_diff_date($sdate,$edate)
	{
	    $from = new \DateTime( $sdate );
	    $to = new \DateTime( $edate );
	    $add_days = $from -> diff( $to ) -> days;
	    if ( $from > $to )
	    {
	        // 빼기
	        //$add_days = '-' . $add_days;
	        $add_days = $add_days * -1;
	    } else
	    {
	        // 더하기
	        $add_days = $add_days * 1;
	    }
	    
	    return $add_days;
	}
	
	/**
	 * ====================================================================================
	 * ssssssssssss
	 * ====================================================================================
	 */
	
	
	/**
	 * 휴회신청에 영향받는 휴회 상품을 찾는다.
	 */
	private function domcy_event_find($var)
	{
		$ueData['comp_cd'] = $var['COMP_CD'];
		$ueData['bcoff_cd'] = $var['BCOFF_CD'];
		$ueData['mem_sno'] = $var['MEM_SNO'];
		$ueData['event_stat'] = "00";
		$event_use_list = $this->modelEvent->list_buy_event_mem_sno_event_stat($ueData);
		
		$use_domcy_event = array();
		$use_domcy_i = 0;
		foreach ($event_use_list as $r)
		{
			if ($r['DOMCY_POSS_EVENT_YN'] == "Y" && $r['LEFT_DOMCY_POSS_CNT'] > 0 && $r['RECVB_AMT'] == 0)
			{
				$use_domcy_event[$use_domcy_i] = $r;
				$use_domcy_i++;
			}
		}
		
		$this->domcy_calculate($var,$use_domcy_event);
		
	}
	
	/**
	 * 휴회 적용될 상품들을 계산한다.
	 */
	private function domcy_calculate($var,$use_domcy_event)
	{
		foreach ($use_domcy_event as $r)
		{
			// 휴회 신청일수보다 현재 휴회신청 가능일수가 많은지를 체크한다. 적다면 휴회신청 가능일수만큼만 휴회을 적용해야한다.
			if ($var['DOMCY_USE_DAY'] <= $r['LEFT_DOMCY_POSS_DAY'])
			{
				$use_domcy_day = $var['DOMCY_USE_DAY'];
			} else 
			{
				$use_domcy_day = $r['LEFT_DOMCY_POSS_DAY'];
			}
			
			$reData['use_af_domcy_day'] = $r['LEFT_DOMCY_POSS_DAY'] - $use_domcy_day; // 사용_후_휴회_일
			$reData['use_af_exr_s_date'] = $r['EXR_S_DATE'];
			$reData['use_af_exr_e_date'] = $this->calcu_domcy_edate($r['EXR_E_DATE'], $use_domcy_day+1); //사용_후_운동_종료_일자
			$reData['domcy_real_e_date'] = $this->calcu_domcy_edate($var['DOMCY_S_DATE'], $use_domcy_day+1); //휴회_실_종료_일자
			$reData['domcy_real_use_day'] = $use_domcy_day; // 휴회_실_사용_일
			
			$insData['domcy_info'] = $var;
			$insData['reData'] = $reData;
			$insData['buy_info'] = $r;
			
			$this->insert_domcy_hist($insData);
			
			$this->func_edate($r,$reData['use_af_exr_e_date'],$reData);
		}
	}
	
	/**
	 * ====================================================================================
	 * 
	 * ====================================================================================
	 */
	
	/**
	 * 종료일을 변경한다.
	 * @param string $sdate
	 * @param string $edate
	 */
	private function func_edate($buy_info,$edate,$reData)
	{
	    $setVar['comp_cd'] = $buy_info['COMP_CD'];
	    $setVar['bcoff_cd'] = $buy_info['BCOFF_CD'];
	    $setVar['mem_sno'] = $buy_info['MEM_SNO'];
	    
	    $buy_list = array();
	    $set_value = array();
	    
	    $buy_list = $this->buy_event_list($setVar); // 회원의 이용중, 예약됨 상품들을 가져온다.
	    
	    $set_value['exr_s_date'] = $buy_info['EXR_S_DATE']; 
	    $set_value['exr_e_date'] = $edate; 
	    
	    if ($buy_info['LOCKR_SET'] == 'Y')
	    {
	        $after_buy = $this->remake_edate_buy_event_info_lockr($buy_info,$buy_list,$set_value); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	    } else
	    {
	        $after_buy = $this->remake_edate_buy_event_info($buy_info,$buy_list,$set_value); // 구매상품의 시작 종료일 기반으로 재배열 되어야할 상품들 처리
	    }
	    
	    $this->func_remake_buy_update($after_buy); //재계산되어야할 상품들을 시작 종료일을 업데이트 처리한다.
	    $this->func_buy_edate_domcy_update($buy_info,$set_value,$reData); //종료날짜를 변경한 데이터를 업데이트 한다.
	    
// 	    $this->is_possable = true;
// 	    $this->rvalue['after'] = $this->after_buy;
// 	    $this->rvalue['is_possable'] = $this->is_possable;
	}
	
	/**
	 * 종료일 상품의 정보를 업데이트 한다.
	 */
	private function func_buy_edate_domcy_update($buy_info,$set_value,$reData)
	{
	    
		$nn_now = new Time('now');
		$udata['mod_id'] = 'cron';
		$udata['mod_datetm'] = $nn_now;
		$udata['buy_event_sno'] = $buy_info['BUY_EVENT_SNO'];
		$udata['exr_s_date'] = $set_value['exr_s_date'];
		$udata['exr_e_date'] = $set_value['exr_e_date'];
		
		$udata['use_domcy_day'] = $reData['domcy_real_use_day'];
		$udata['left_domcy_poss_day'] = $reData['use_af_domcy_day'];
		$udata['left_domcy_poss_cnt'] = $buy_info['LEFT_DOMCY_POSS_CNT'] - 1;
		
		$this->modelEvent->update_buy_event_mgmt_redate_edate_domcy($udata);
		
		// TODO @@@ 이용중에 대한 락커 업데이트의 경우에는 락커 lockr_room 테이블에도 업데이트가 이루어져야 한다. 
	}
	
	/**
	 * 재계산되어야할 상품들을 시작 종료일을 업데이트 처리한다.
	 */
	private function func_remake_buy_update($after_buy)
	{
	    $nn_now = new Time('now');
	    
	    $udata['mod_id'] = 'cron';
	    $udata['mod_datetm'] = $nn_now;
	    
	    if (count($after_buy) > 0)
	    {
	        for($i=0; $i < count($after_buy); $i++)
	        {
	            $udata['buy_event_sno'] = $after_buy[$i]['BUY_EVENT_SNO'];
	            $udata['exr_s_date'] = $after_buy[$i]['RE_EXR_S_DATE'];
	            $udata['exr_e_date'] = $after_buy[$i]['RE_EXR_E_DATE'];
	            
	            if ($after_buy[$i]['RE_EXR_S_DATE'] == date('Y-m-d'))
	            {
	                $udata['event_stat'] = "00"; //예약됨
	            } else
	            {
	                $udata['event_stat'] = "01"; //이용중
	            }
	            
	            $this->modelEvent->update_buy_event_mgmt_redate($udata);
	        }
	    }
	}
	
	/**
	 * 회원이 이미 구매한 상품을 가져온다. (이용중, 예약됨 상품만 )
	 */
	private function buy_event_list($var)
	{
	    $buy_list = array();
	    $eData['comp_cd']  = $var['comp_cd'];
	    $eData['bcoff_cd'] = $var['bcoff_cd'];
	    $eData['mem_sno']  = $var['mem_sno'];
	    
	    $buy_event_list = $this->modelEvent->get_buy_events_use_memsno($eData);
	    if(count($buy_event_list) > 0) $buy_list = $buy_event_list;
	    
	    return $buy_list;
	}
	
	private function remake_edate_buy_event_info_lockr($buy_info,$buy_list,$set_value)
	{
	    $sell_cate_code = $buy_info['1RD_CATE_CD'].$buy_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    $after_buy_array = array(); // 이후에 검색되는 상품들을 배열에 저장하기 위함.
	    $after_buy_i = 0; // 배열 증가 변수
	    
	    if ($buy_list != null)
	    {
	        foreach($buy_list as $r)
	        {
	            if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code && $r['LOCKR_NO'] == $buy_info['LOCKR_NO'])
	            {
	                if (strtotime($set_value['exr_s_date']) < strtotime($r['EXR_S_DATE']) && $r['BUY_EVENT_SNO'] != $buy_info['BUY_EVENT_SNO'])
	                {
	                    $after_buy_array[$after_buy_i] = $r;
	                    $after_buy_i++ ;
	                }
	            }
	        }
	    }
	    //$this->after_buy = $after_buy_array;
	    $after_buy = $this->remake_buy_event_date($after_buy_array,$set_value);
	    return $after_buy;
	}
	
	private function remake_edate_buy_event_info($buy_info,$buy_list,$set_value)
	{
	    $sell_cate_code = $buy_info['1RD_CATE_CD'].$buy_info['2RD_CATE_CD']; // 그룹 카테고리 적용된 1,2 카테고리 코드
	    $after_buy_array = array(); // 이후에 검색되는 상품들을 배열에 저장하기 위함.
	    $after_buy_i = 0; // 배열 증가 변수
	    
	    if ($buy_list != null)
	    {
	        foreach($buy_list as $r)
	        {
	            if ($r['1RD_CATE_CD'].$r['2RD_CATE_CD'] == $sell_cate_code)
	            {
	                if (strtotime($set_value['exr_s_date']) < strtotime($r['EXR_S_DATE']) && $r['BUY_EVENT_SNO'] != $buy_info['BUY_EVENT_SNO'])
	                {
	                    $after_buy_array[$after_buy_i] = $r;
	                    $after_buy_i++ ;
	                }
	            }
	            
	        }
	    }
	    
	    //$this->after_buy = $after_buy_array;
	    $after_buy = $this->remake_buy_event_date($after_buy_array,$set_value);
	    return $after_buy;
	}
	
	/**
	 * 재 적용해야할 운동상품들에 대한 시작, 종료일을 재 계산한다.
	 */
	private function remake_buy_event_date($after_buy,$set_value)
	{
	    $last_edate = $set_value['exr_e_date'];
	    if (count($after_buy) > 0)
	    {
	        for($i=0; $i < count($after_buy); $i++)
	        {
	            $remake_date = $this->remake_calc_se_date($last_edate, $after_buy[$i]['USE_PROD'], $after_buy[$i]['USE_UNIT'], $after_buy[$i]['ADD_SRVC_EXR_DAY']);
	            $last_edate = $remake_date['edate'];
	            
	            $after_buy[$i]['RE_EXR_S_DATE'] = $remake_date['sdate'];
	            $after_buy[$i]['RE_EXR_E_DATE'] = $remake_date['edate'];
	        }
	    }
	    
	    return $after_buy;
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
	 * ====================================================================================
	 *
	 * ====================================================================================
	 */
	
	
	
	/**
	 * 휴회 적용 관련된 구매 상품 정보를 insert 한다.
	 * @param array $var
	 */
	public function insert_domcy_hist($var)
	{
		$nn_now = new Time('now');
		//DOMCY_MGMT_HIST_SNO//휴회_관리_내역_일련번호
		
		$domhData['domcy_mgmt_sno']               = $var['domcy_info']['DOMCY_MGMT_SNO']; //휴회_관리_일련번호
		$domhData['domcy_s_date']                 = $var['domcy_info']['DOMCY_S_DATE']; //휴회_시작_일자
		$domhData['domcy_e_date']                 = $var['domcy_info']['DOMCY_E_DATE']; //휴회_종료_일자
		$domhData['domcy_use_day']                = $var['domcy_info']['DOMCY_USE_DAY']; //휴회_사용_일
		$domhData['domcy_real_s_date']            = $var['domcy_info']['DOMCY_S_DATE']; //휴회_실_시작_일자
		
		$domhData['use_af_domcy_day']         = $var['reData']['use_af_domcy_day']; //사용_후_휴회_일
		$domhData['use_af_exr_s_date']        = $var['reData']['use_af_exr_s_date']; //사용_후_운동_시작_일자
		$domhData['use_af_exr_e_date']        = $var['reData']['use_af_exr_e_date']; //사용_후_운동_종료_일자
		$domhData['domcy_real_e_date']        = $var['reData']['domcy_real_e_date']; //휴회_실_종료_일자
		$domhData['domcy_real_use_day']       = $var['reData']['domcy_real_use_day']; //휴회_실_사용_일
		$domhData['domcy_mgmt_stat']          = "01"; //휴회_관리_상태
		
		$domhData['buy_event_sno']        = $var['buy_info']['BUY_EVENT_SNO']; //구매_상품_일련번호
		$domhData['sell_event_sno']       = $var['buy_info']['SELL_EVENT_SNO']; //판매_상품_일련번호
		$domhData['send_event_sno']       = $var['buy_info']['SEND_EVENT_SNO']; //보내기_상품_일련번호
		$domhData['comp_cd']              = $var['buy_info']['COMP_CD']; //회사_코드
		$domhData['bcoff_cd']             = $var['buy_info']['BCOFF_CD']; //지점_코드
		$domhData['1rd_cate_cd']          = $var['buy_info']['1RD_CATE_CD']; //1차_카테고리_코드
		$domhData['2rd_cate_cd']          = $var['buy_info']['2RD_CATE_CD']; //2차_카테고리_코드
		$domhData['mem_sno']              = $var['buy_info']['MEM_SNO']; //회원_일련번호
		$domhData['mem_id']               = $var['buy_info']['MEM_ID']; //회원_아이디
		$domhData['mem_nm']               = $var['buy_info']['MEM_NM']; //회원_명
		$domhData['sell_event_nm']        = $var['buy_info']['SELL_EVENT_NM']; //판매_상품_명
		$domhData['use_bf_domcy_day']     = $var['buy_info']['LEFT_DOMCY_POSS_DAY']; //사용_전_휴회_일
		$domhData['use_bf_domcy_cnt']     = $var['buy_info']['LEFT_DOMCY_POSS_CNT']; //사용_전_휴회_횟수
		$domhData['use_bf_exr_s_date']    = $var['buy_info']['EXR_S_DATE']; //사용_전_운동_시작_일자
		$domhData['use_bf_exr_e_date']    = $var['buy_info']['EXR_E_DATE']; //사용_전_운동_종료_일자
		$domhData['grp_cate_set']         = $var['buy_info']['GRP_CATE_SET']; //그룹_카테고리_설정
		$domhData['lockr_set']            = $var['buy_info']['LOCKR_SET']; //락커_설정
		$domhData['lockr_knd']            = $var['buy_info']['LOCKR_KND']; //락커_종류
		$domhData['lockr_gendr_set']      = $var['buy_info']['LOCKR_GENDR_SET']; //락커_성별_설정
		$domhData['lockr_no']             = $var['buy_info']['LOCKR_NO']; //락커_번호
		$domhData['cre_id'] = "cron"; //등록_아이디
		$domhData['cre_datetm'] = $nn_now; //등록_일시
		$domhData['mod_id'] = "cron"; //수정_아이디
		$domhData['mod_datetm'] = $nn_now; //수정_일시
		
		$this->modelDomcy->insert_domcy_mgmt_hist($domhData);
	}
	
	/**
	 * 날짜 기준으로 사용일수만큼을 계산하여 리턴한다.
	 * @param string $sdate
	 * @param string $add_days
	 */
	private function calcu_domcy_edate($sdate,$add_days)
	{
		$add_days = intval($add_days) - 1;
		
		if ($add_days < 0)
		{
		    $result_date = date("Y-m-d", strtotime($add_days." days", strtotime($sdate)));
		} else 
		{
		    $result_date = date("Y-m-d", strtotime("+".$add_days." days", strtotime($sdate)));
		}
		
		return $result_date;
	}
	
	
	
	
	
	
	
}