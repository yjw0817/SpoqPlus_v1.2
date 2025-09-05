<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\AttdModel;

class Attd_lib {

    /**
     * AttdModel
     * @var object
     */
    private $modelAttd;
    
    /**
     * 회원정보
     * @var array
     */
    private $data_meminfo;
    
    /**
     * 구매상품 (이용중)
     * @var array
     */
	private $cur_available_membership;
    
    private $set_data;
    
    private $result_info = array("result"=>true);
    
    /**
     * 재입장 가능 시간 (초단위)
     * @var integer
     */
    private $retime; // 10 일경우 10초
    
    private $initVar;
    
    /**
     * initVar['comp_cd']
     * initVar['bcoff_cd']
     * initVar['mem_sno']
     * @param array $initVar
     */
    public function __construct($initVar) {
	    
        $this->retime = getenv('SpoQ_ATTD_RETIME');
        
        $this->initVar = $initVar;
        $this->modelAttd = new AttdModel();
	    
        $meminfo = $this->modelAttd->get_mem_info_detl($initVar);
        $this->data_meminfo = !empty($meminfo[0]) ? $meminfo[0] : []; 
	    
		//$buylist = $this->modelAttd->list_buy_event_mgmt($initVar);
		$this->cur_available_membership  = $this->modelAttd->cur_available_membership($initVar);
	}
      
	
	/**
	 * 출석을 실행한다.
	 */
	public function attd_run()
	{
	    // 이용중 인 상품 카운트를 이용하여 이용 가능한지 확인한다.
	    $buy_count = $this->func_buy_list_count();
	    if ($buy_count == 0)
	    {
	        $this->result_info['msg'] = "이용가능한 상품이 없습니다.";
	        $this->result_info['result'] = false;
	        return $this->result_info;
	    }
	    
	    // 재입장인지 체크를 한다. false (신규입장) , true (재입장)
	    $re_attd_chk = $this->func_re_attd_chk();
	    
	    $this->set_data['auto_clas_chk'] = "";
	    $this->set_data['auto_chk'] = "N";
		$membershipInfo = $this->cur_available_membership[0];
		/*
	    if ($re_attd_chk == false)
	    {
	        
	        $acc_type = $this->acc_chk();
	        
	        if ($acc_type['dv'] == "01") // 일일 1회 입장 제한
	        {
	            // 하루에 한번 입장을 했는지 체크 한다.
	            $attd1tmData = $this->initVar;
	            $attd1tmData['attd_ymd'] = date('Ymd');
	            $attd_count = $this->modelAttd->count_1tm_attd_mgmt($attd1tmData);
	            
	            if ($attd_count[0]['counter'] > 0)
	            {
	                $this->result_info['msg'] = "1회 입장을 이미 하였습니다..[입장불가]";
	                $this->result_info['result'] = false;
	                return $this->result_info;
	            }
	            
	            if ($acc_type['mthd'] == "02") // 주말
	            {
	                $chk_dotw = array("일","0","0","0","0","0","토",);
	                $get_dotw = $chk_dotw[date('w',strtotime(date(date('Y-m-d'))))];
	                
	                if ($get_dotw == "0")
	                {
	                    $this->result_info['msg'] = "주말만 이용 가능합니다. [입장불가]";
	                    $this->result_info['result'] = false;
	                    return $this->result_info;
	                }
	                
	            } elseif ($acc_type['mthd'] == "05") // 평일
	            {
	                $chk_dotw = array("0","월","화","수","목","금","0",);
	                $get_dotw = $chk_dotw[date('w',strtotime(date(date('Y-m-d'))))];
	                
	                if ($get_dotw == "0")
	                {
	                    $this->result_info['msg'] = "평일만 이용 가능합니다. [입장불가]";
	                    $this->result_info['result'] = false;
	                    return $this->result_info;
	                }
	                
	            } elseif($acc_type['mthd'] == "70") // 시간대
	            {
	                // 입장 가능한 시간 정보를 가져와서 현재 시간과 비교 해야한다.
	            }
	            
	            // 00 : 매일 / 02 : 주말 / 05 : 평일 / 70 : 시간
	        } elseif ($acc_type['dv'] == "50")// 수업입장
	        {
	            // 수업입장 상품밖에 없다면 이용중인 수업상품을 자동 차감 해야한다.
	        	$this->func_auto_clas_chk();
	        	
	        } elseif ($acc_type['dv'] == "99") // 입장불가
	        {
	        	$this->result_info['msg'] = "이용가능한 상품이 없습니다.[입장불가]";
	        	$this->result_info['result'] = false;
	        	return $this->result_info;
	        }
	        
	    }*/
	    
		if ($re_attd_chk == false)
	    {
	        
	        $acc_type = $this->acc_chk();
			/*
	        if ($membershipInfo['USE_PER_DAY'] > 0) // 일일 1회 입장 제한
            {
                // 하루에 한번 입장을 했는지 체크 한다.
               
                $attd_count = $membershipInfo['오늘출석수'];
                
                if ($attd_count == $membershipInfo['USE_PER_DAY'] )
                {
                    $this->result_info['msg'] = $membershipInfo['USE_PER_DAY'] . "회 입장을 이미 하였습니다..[입장불가]";
                    $this->result_info['result'] = false;
                    return $this->result_info;
                }               
                
                // 00 : 매일 / 02 : 주말 / 05 : 평일 / 70 : 시간
            } elseif ($membershipInfo['M_CATE'] == "PRV")// 수업입장 */
	        // 이용 제한 체크
	        $limitCheckResult = $this->checkUsageLimits($membershipInfo);
	        if ($limitCheckResult !== null) {
	            $this->result_info['msg'] = $limitCheckResult;
	            $this->result_info['result'] = false;
	            return $this->result_info;
	        }
	        
	        if ($membershipInfo['M_CATE'] == "PRV")// 수업입장
	        {
	            // 수업입장 상품밖에 없다면 이용중인 수업상품을 자동 차감 해야한다.
	        	$this->func_auto_clas_chk2($membershipInfo);
	        	
	        } 
			
			/*
			else// 입장불가7
	        {
	        	$this->result_info['msg'] = "이용가능한 상품이 없습니다.[입장불가]";
	        	$this->result_info['result'] = false;
	        	return $this->result_info;
	        }*/
	        
	    }

			


	    // 휴회중인 상품이 있는지 체크한다.
	    $this->func_domcy_chk();
	    
	    // 출석관리 insert
	    $this->insert_attd_mgmt();
        
	    if ($this->set_data['attd_yn'] == "N")
	    {
	        $this->result_info['msg'] = "재입장 성공";
	    } else 
	    {
	    	$this->result_info['msg'] = "출입성공" . $this->set_data['auto_clas_chk'];
	    }
	    
	    $this->result_info['result'] = true;
	    return $this->result_info;
	}
	
	/**
	 * 휴회중인 상품이 있는지 체크한다.
	 */
    private function func_domcy_chk()
    {
        return;
    }
	
	/**
	 * 자동으로 이용중인 수업에 대해서 수업 체크를 진행한다.
	 */
	private function func_auto_clas_chk()
	{
		
		foreach($this->cur_available_membership as $r)
		{
			if ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22")
			{
				if ($r['STCHR_ID'] != '')
				{
					if ($r['ACC_RTRCT_DV'] == "50")
					{
						$clasLib = new Clas_lib($r['STCHR_ID'], $r['BUY_EVENT_SNO'],'Y');
						//$clas_result = $clasLib->clas_run();
						$this->set_data['auto_clas_chk'] = " [수업차감]";
						$this->set_data['auto_chk'] = "Y";
					}
				}
			}
		}

		
		
		
	}

	private function func_auto_clas_chk2($r)
	{
		if ($r['STCHR_ID'] != '')
		{
			//$clasLib = new Clas_lib($r['STCHR_ID'], $r['BUY_EVENT_SNO'],'Y');
			//$clas_result = $clasLib->clas_run();
			$this->set_data['auto_clas_chk'] = " [수업차감]";
			$this->set_data['auto_chk'] = "Y";
		}
	}
	
	/**
	 * 재입장인지 체크를 한다.
	 */
	private function func_re_attd_chk()
	{
	    $attChkData = $this->initVar;
	    $attChkData['attd_ymd'] = date('Ymd');
	    
	    $re_attd_list = $this->modelAttd->get_re_attd_mgmt_limit($attChkData);
	    
	    if (count($re_attd_list) == 0)
	    {
	        $this->set_data['attd_yn'] = 'Y';
	        return false;
	    } else 
	    {
	        $gapTime = $this->gap_time($re_attd_list[0]['CRE_DATETM']);
	        
	        if ($gapTime < $this->retime)
	        {
	            $this->set_data['attd_yn'] = 'N';
	            return true;
	        } else 
	        {
	            $this->set_data['attd_yn'] = 'Y';
	            return false;
	        }
	    }
	}
	
	/**
	 * 두날짜 사이의 시간 차이를 구한다.
	 * @param string $sdate
	 * @param string $edate
	 */
	private function gap_time($sdate)
	{
	    $nn_now = new Time('now');
	    $edatetime = strtotime($nn_now);
	    $sdatetime = strtotime($sdate);
	    
	    $diff = $edatetime - $sdatetime;
	    
	    return $diff;
	}
	
	/**
	 * 이용 제한을 체크하고 제한 메시지를 반환한다.
	 * @param array $membershipInfo 회원권 정보
	 * @return string|null 제한 메시지 또는 null (제한 없음)
	 */
	public function checkUsageLimits($membershipInfo)
	{
	    $ticketName = $membershipInfo['SELL_EVENT_NM'] ?? '이용권';
	    
	    // 1. 일일 제한 체크
	    if (isset($membershipInfo['USE_PER_DAY']) && $membershipInfo['USE_PER_DAY'] > 0) {
	        $todayCount = $membershipInfo['오늘출석수'] ?? 0;
	        if ($membershipInfo['USE_PER_DAY'] <= $todayCount) {
	            // 오늘마지막이용일시를 "시분" 형태로 포맷
	            $lastUsedTime = '';
	            if (!empty($membershipInfo['오늘마지막이용일시'])) {
	                $dateTime = strtotime($membershipInfo['오늘마지막이용일시']);
	                $hour = date('G', $dateTime); // 0-23시
	                $minute = date('i', $dateTime); // 00-59분
	                if ($minute == '00') {
	                    $lastUsedTime = $hour . '시';
	                } else {
	                    $lastUsedTime = $hour . '시 ' . intval($minute) . '분';
	                }
	            }
	            
	            if ($lastUsedTime) {
	                return "[{$ticketName}]이용권으로 {$lastUsedTime}에 {$membershipInfo['USE_PER_DAY']}회 입장을 이미 하였습니다.";
	            } else {
	                return "[{$ticketName}]이용권으로 {$membershipInfo['USE_PER_DAY']}회 입장을 이미 하였습니다.";
	            }
	        }
	    }
	    
	    // 2. 주간 제한 체크
	    if (isset($membershipInfo['USE_PER_WEEK']) && $membershipInfo['USE_PER_WEEK'] > 0) {
	        $weekUnit = $membershipInfo['USE_PER_WEEK_UNIT'] ?? '';
	        $lastUsedDateTime = '';
	        
	        // 주간마지막이용일을 "월 일 시분" 형태로 포맷
	        if (!empty($membershipInfo['주간마지막이용일'])) {
	            $dateTime = strtotime($membershipInfo['주간마지막이용일']);
	            $month = date('n', $dateTime);
	            $day = date('j', $dateTime);
	            $hour = date('G', $dateTime);
	            $minute = date('i', $dateTime);
	            
	            if ($minute == '00') {
	                $timeStr = $hour . '시';
	            } else {
	                $timeStr = $hour . '시 ' . intval($minute) . '분';
	            }
	            
	            $lastUsedDateTime = $month . '월 ' . $day . '일 ' . $timeStr;
	        }
	        
	        if ($weekUnit == '10') { // 주간이용일수
	            $weeklyDays = $membershipInfo['주간이용일수'] ?? 0;
	            if ($membershipInfo['USE_PER_WEEK'] <= $weeklyDays) {
	                if ($lastUsedDateTime) {
	                    return "[{$ticketName}]이용권으로 {$lastUsedDateTime}에 주간이용일수 {$membershipInfo['USE_PER_WEEK']}일을 마지막으로 사용하였습니다.";
	                } else {
	                    return "[{$ticketName}]이용권으로 주간이용일수 {$membershipInfo['USE_PER_WEEK']}일을 이미 사용하였습니다.";
	                }
	            }
	        } elseif ($weekUnit == '20') { // 주간이용횟수
	            $weeklyCount = $membershipInfo['주간이용횟수'] ?? 0;
	            if ($membershipInfo['USE_PER_WEEK'] <= $weeklyCount) {
	                if ($lastUsedDateTime) {
	                    return "[{$ticketName}]이용권으로 {$lastUsedDateTime}에 주간이용횟수 {$membershipInfo['USE_PER_WEEK']}회를 마지막으로 사용하였습니다.";
	                } else {
	                    return "[{$ticketName}]이용권으로 주간이용횟수 {$membershipInfo['USE_PER_WEEK']}회를 이미 사용하였습니다.";
	                }
	            }
	        }
	    }
	    
	    return null; // 제한 없음
	}

	/**
	 * 입장 제한을 체크한다.
	 */
	private function acc_chk()
	{
		/*
	    $acc['dv'] = "";
	    $acc['mthd'] = "";
	    $acc['clas_dv'] = "";
	    foreach($this->cur_available_membership as $r)
	    {
	        $acc['dv'] = $r['ACC_RTRCT_DV'];
	        $acc['mthd'] = $r['ACC_RTRCT_MTHD'];
	        $acc['clas_dv'] = $r['CLAS_DV'];
	    }
	    */
	    return $this->cur_available_membership;
	}
	
	/**
	 * 이용중인 상품의 카운트를 리턴한다.
	 * @return number
	 */
	private function func_buy_list_count()
	{
	    return count($this->cur_available_membership);
	}
	
	
	/**
	 * 출석관리 insert
	 */
	private function insert_attd_mgmt()
	{
	    $nn_now = new Time('now');
	    
	    $attdData['comp_cd'] = $this->data_meminfo['COMP_CD'];
	    $attdData['bcoff_cd'] = $this->data_meminfo['BCOFF_CD'];
	    
	    $attdData['mem_sno'] = $this->data_meminfo['MEM_SNO'];
	    $attdData['mem_id'] = $this->data_meminfo['MEM_ID'];
	    $attdData['mem_nm'] = $this->data_meminfo['MEM_NM'];
	    
	    $attdData['mem_dv'] = $this->data_meminfo['MEM_DV'];
	    
	    $attdData['attd_mthd'] = "";
	    $attdData['attd_yn'] = $this->set_data['attd_yn'];
	    
	    $attdData['acc_rtrct_dv'] = "";
	    $attdData['acc_rtrct_mthd'] = "";
	    
	    $attdData['agegrp'] = "";
	    $attdData['mem_gendr'] = $this->data_meminfo['MEM_GENDR'];
	    $attdData['attd_ymd'] = date('Ymd');
	    $attdData['attd_yy'] = date('Y');
	    $attdData['attd_mm'] = date('m');
	    $attdData['attd_dd'] = date('d');
	    $attdData['attd_dotw'] = $this->subfunc_dotw(date('Y-m-d'));
	    $attdData['attd_hh'] = date('H');
	    
	    $attdData['auto_chk'] = $this->set_data['auto_chk'];
	    
	    $attdData['cre_id'] = $attdData['mem_id'];
	    $attdData['cre_datetm'] = $nn_now;
	    $attdData['mod_id'] = $attdData['mem_id'];
	    $attdData['mod_datetm'] = $nn_now;
		$attdData['sell_event_sno'] = $this->cur_available_membership[0]['SELL_EVENT_SNO'];
		$attdData['buy_event_sno'] = $this->cur_available_membership[0]['BUY_EVENT_SNO'];
	    $attdData['m_cate'] = $this->cur_available_membership[0]['M_CATE'];
		$attdData['gx_schd_mgmt_sno'] = $this->cur_available_membership[0]['GX_SCHD_MGMT_SNO'];

	    $this->modelAttd->insert_attd_mgmt($attdData);
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