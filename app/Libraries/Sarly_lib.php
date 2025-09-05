<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\SarlyModel;

class Sarly_lib {

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
     * 셋팅된 강사 아이디
     * @var string
     */
    private $set_tid;
    
    /**
     * 이번달의 시작일
     * @var string
     */
    private $set_ms_date;
    
    /**
     * 이번달의 마지막일
     * @var string
     */
    private $set_me_date;
    
    /**
     * 수당 계산해야할 리스트
     * @var array
     */
    private $set_sarly_mgmt;
    
    /**
     * SarlyModel
     * @var object
     */
    private $modelSarly;
    
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
        $this->set_comp_cd = $initVar['comp_cd'];
        $this->set_bcoff_cd = $initVar['bcoff_cd'];
        $this->modelSarly = new SarlyModel();
	}
	
	public function get_sarly_detail($tid,$ym,$sarly_mgmt_sno,$kind)
	{
	    $this->set_tid = $tid;
	    $this->ym_s_e_date($ym); // 이번달의 시작과 끝일을 셋팅한다.
	    $this->real_s_e_date_detail($tid,$sarly_mgmt_sno); // 계산해야할 시작 종료일을 구한다.
	    
	    /**
	     * SARLY_PMT_MTHD 에 따라서 어떤 정보를 구해야하는지를 판단한다.
	     */
	    
	    $new_sarly_mgmt = array();
	    $new_i = 0;
	    foreach ($this->set_sarly_mgmt as $r)
	    {
	        $new_sarly_mgmt[$new_i] = $this->case_pmt_mthd_detail($r,$kind);
	        $new_i++;
	    }
	    $this->set_sarly_mgmt = $new_sarly_mgmt;
	    
	    $this->retunValue['sarly_mgmt'] = $this->set_sarly_mgmt;
	    
	    return $this->retunValue;
	    
	}
	
	/**
	 * 회원의 출입조건을 가져온다.
	 * @return array['acc_mthd']
	 */
	public function get_sarly($tid,$ym)
	{
 	    $this->set_tid = $tid;
	    $this->ym_s_e_date($ym); // 이번달의 시작과 끝일을 셋팅한다.
	    $this->real_s_e_date($tid); // 계산해야할 시작 종료일을 구한다.
	    
	    /**
	     * SARLY_PMT_MTHD 에 따라서 어떤 정보를 구해야하는지를 판단한다.
	     */
	    
	    $new_sarly_mgmt = array();
	    $new_i = 0;
	    foreach ($this->set_sarly_mgmt as $r)
	    {
	    	$new_sarly_mgmt[$new_i] = $this->case_pmt_mthd($r);
	    	$new_i++;
	    }
	    $this->set_sarly_mgmt = $new_sarly_mgmt;
	    
	    $this->retunValue['sarly_mgmt'] = $this->set_sarly_mgmt;
	    return $this->retunValue;
	}
	
	private function case_pmt_mthd_detail($sarly_mgmt,$kind)
	{
	    $sData['comp_cd'] = $this->set_comp_cd;
	    $sData['bcoff_cd'] = $this->set_bcoff_cd;
	    $sData['tid'] = $this->set_tid;
	    $sData['sdate'] = $sarly_mgmt['new_s_date'];
	    $sData['edate'] = $sarly_mgmt['new_e_date'];
	    $sData['item1'] = $sarly_mgmt['SARLY_APLY_ITEM_1'];
	    $sData['item2'] = $sarly_mgmt['SARLY_APLY_ITEM_2'];
	    
	    if ($sData['item1'] == '') $sData['item1'] = "''";
	    if ($sData['item2'] == '') $sData['item2'] = "''";
	    
	    $sData['sarly_pmt_cond'] = $sarly_mgmt['SARLY_PMT_COND'];
	    
	    $detail = array();
	    
	    switch($kind)
	    {
	        case "01" : // sell_amt_detail
	            $detail = $this->modelSarly->get_sarly_sum_amt_detail($sData);
	            break;
	        case "02" : // pt_amt_detail
	            $detail = $this->modelSarly->get_sarly_sum_pt_amt_detail($sData);
	            break;
	        case "03" : //pt_count_detail
	            $detail = $this->modelSarly->get_sarly_sum_pt_count_detail($sData);
	            break;
	        case "04" : //gx_count_detail
	            $detail = $this->modelSarly->get_sarly_sum_gx_count_detail($sData);
	            break;
	    }
	    
	    $sarly_mgmt['detail'] = $detail;
	    
	    return $sarly_mgmt;
	}
	
	
	private function case_pmt_mthd($sarly_mgmt)
	{
	    $sData['comp_cd'] = $this->set_comp_cd;
	    $sData['bcoff_cd'] = $this->set_bcoff_cd;
	    $sData['tid'] = $this->set_tid;
	    $sData['sdate'] = $sarly_mgmt['new_s_date'];
	    $sData['edate'] = $sarly_mgmt['new_e_date'];
	    $sData['item1'] = $sarly_mgmt['SARLY_APLY_ITEM_1'];
	    $sData['item2'] = $sarly_mgmt['SARLY_APLY_ITEM_2'];
	    
	    if ($sData['item1'] == '') $sData['item1'] = "''";
	    if ($sData['item2'] == '') $sData['item2'] = "''";
	    
	    $sData['sarly_pmt_cond'] = $sarly_mgmt['SARLY_PMT_COND'];
	    
	    $result['sell_amt'] = 0;
	    $result['pt_amt'] = 0;
	    $result['pt_count'] = 0;
	    $result['calcu_set_rate'] = 0;
	    $result['calcu_set_amt'] = 0;
	    $result['calcu_set_1tm'] = 0;
	    
	    $result['calcu_set_rate_yn'] = "Y";
	    $result['calcu_set_amt_yn'] = "Y";
	    $result['calcu_set_1tm_yn'] = "Y";
	    
	    $result['cost'] = 0;
	    
	    $cData['comp_cd'] = $this->set_comp_cd;
	    $cData['bcoff_cd'] = $this->set_bcoff_cd;
	    $cData['sarly_pmt_mthd'] = $sarly_mgmt['SARLY_PMT_MTHD'];
	    
	    $cData['sarly_mgmt_sno'] = $sarly_mgmt['SARLY_MGMT_SNO'];
	    
	    switch($sarly_mgmt['SARLY_PMT_MTHD'])
	    {
	        case "11" : // "[수당비율] 판매 매출액 / 판매 매출 요율"
	            $sell_amt = $this->modelSarly->get_sarly_sum_amt($sData);
	            $result['sell_amt'] = $sell_amt[0]['sum_amt'];
	            
	            $cData['base_amt'] = $result['sell_amt'];
	            $calcu_rate = $this->modelSarly->get_calcu_sarly_mgmt_sell_amt($cData);
	            
	            if (count($calcu_rate) > 0)
	            {
	                $result['calcu_set_rate'] = $calcu_rate[0]['SELL_SALES_RATE'];
	            } else
	            {
	                $result['calcu_set_rate'] = 0;
	                $result['calcu_set_rate_yn'] = "N";
	            }
	            
	            $result['cost'] = $result['sell_amt'] * ( $result['calcu_set_rate'] * 0.01 );
	            break;
	        case "12" : // "[수당비율] 판매 매출액 / PT수업 매출 요율"
	            $sell_amt = $this->modelSarly->get_sarly_sum_amt($sData);
	            $pt_amt = $this->modelSarly->get_sarly_sum_pt_amt($sData);
	            
	            $result['sell_amt'] = $sell_amt[0]['sum_amt'];
	            $result['pt_amt'] = $pt_amt[0]['sum_pt_amt'];
	            
	            $cData['base_amt'] = $result['sell_amt'];
	            $calcu_rate = $this->modelSarly->get_calcu_sarly_mgmt_sell_amt($cData);
	            
	            if (count($calcu_rate) > 0)
	            {
	                $result['calcu_set_rate'] = $calcu_rate[0]['PT_CLAS_SALES_RATE'];
	            } else 
	            {
	                $result['calcu_set_rate'] = 0;
	                $result['calcu_set_rate_yn'] = "N";
	            }
	            
	            $result['cost'] = $result['pt_amt'] * ( $result['calcu_set_rate'] * 0.01 );
	            break;
	        case "13" : // "[수당비율] PT수업 매출액 / PT수업 매출 요율"
	            $pt_amt = $this->modelSarly->get_sarly_sum_pt_amt($sData);
	            
	            $result['pt_amt'] = $pt_amt[0]['sum_pt_amt'];
	            $cData['base_amt'] = $result['pt_amt'];
	            $calcu_rate = $this->modelSarly->get_calcu_sarly_mgmt_pt_amt($cData);
	            if (count($calcu_rate) > 0) 
	            {
	                $result['calcu_set_rate'] = $calcu_rate[0]['PT_CLAS_SALES_RATE'];
	            } else 
	            {
	                $result['calcu_set_rate'] = "0";
	                $result['calcu_set_rate_yn'] = "N";
	            }
	            
	            $result['cost'] = $result['pt_amt'] * ( $result['calcu_set_rate'] * 0.01 );
	            //
	            break;
	        case "21" : // "[수당금액] 판매 매출액 / 수당금액"
	            $sell_amt = $this->modelSarly->get_sarly_sum_amt($sData);
	            
	            $result['sell_amt'] = $sell_amt[0]['sum_amt'];
	            $cData['base_amt'] = $result['sell_amt'];
	            $calcu_amt = $this->modelSarly->get_calcu_sarly_mgmt_sell_amt($cData);
	            if (count($calcu_amt) > 0)
	            {
	                $result['calcu_set_amt'] = $calcu_amt[0]['SARLY_AMT'];
	            } else 
	            {
	                $result['calcu_set_amt'] = 0;
	                $result['calcu_set_amt_yn'] = "N";
	            }
	            
	            $result['cost'] = $result['calcu_set_amt'];
	            //
	            break;
	        case "22" : // "[수당금액] PT수업 매출액 / 수당금액"
	            $pt_amt = $this->modelSarly->get_sarly_sum_pt_amt($sData);
	            
	            $result['pt_amt'] = $pt_amt[0]['sum_pt_amt'];
	            $cData['base_amt'] = $result['pt_amt'];
	            $calcu_amt = $this->modelSarly->get_calcu_sarly_mgmt_pt_amt($cData);
	            if (count($calcu_amt) > 0)
	            {
	                $result['calcu_set_amt'] = $calcu_amt[0]['SARLY_AMT'];
	            } else 
	            {
	                $result['calcu_set_amt'] = 0;
	                $result['calcu_set_amt_yn'] = "N";
	            }
	            
	            $result['cost'] = $result['calcu_set_amt'];
	            //
	            break;
	        case "31" : // "[수당회당] PT수업 횟수 / 수업1회금액"
	            $pt_count = $this->modelSarly->get_sarly_sum_pt_count($sData);
	            
	            $result['pt_count'] = $pt_count[0]['sum_pt_count'];
	            $cData['base_cnt'] = $result['pt_count'];
	            $calcu_1tm_amt = $this->modelSarly->get_calcu_sarly_mgmt_pt_count($cData);
	            if (count($calcu_1tm_amt) > 0)
	            {
	                $result['calcu_set_1tm'] = $calcu_1tm_amt[0]['CLAS_1TM_AMT'];
	            } else 
	            {
	                $result['calcu_set_1tm'] = 0;
	                $result['calcu_set_1tm_yn'] = "N";
	            }
	            
	            $result['cost'] = $result['pt_count'] * $result['calcu_set_1tm'];
	            //
	            break;
	        case "32" : // "[수당회당] GX수업 횟수 / 수업1회금액"
	            $pt_count = $this->modelSarly->get_sarly_sum_gx_count($sData);
	            
	            $result['pt_count'] = $pt_count[0]['sum_pt_count'];
	            $cData['base_cnt'] = $result['pt_count'];
	            $calcu_1tm_amt = $this->modelSarly->get_calcu_sarly_mgmt_gx_count($cData);
	            if (count($calcu_1tm_amt) > 0)
	            {
	                $result['calcu_set_1tm'] = $calcu_1tm_amt[0]['CLAS_1TM_AMT'];
	            } else 
	            {
	                $result['calcu_set_1tm'] = 0;
	                $result['calcu_set_1tm_yn'] = "N";
	            }
	            
	            $result['cost'] = $result['pt_count'] * $result['calcu_set_1tm'];
	            break;
	        case "50" : // 기본급 처리
	            $sarly_basic_amt = $this->modelSarly->get_sarly_mgmt_basic_amt($cData);
	            $result['cost'] = $sarly_basic_amt[0]['SARLY_AMT'];
	            break;
	            
	    }
	    
	    $sarly_mgmt['sell_amt'] = $result['sell_amt'];
	    $sarly_mgmt['pt_amt'] = $result['pt_amt'];
	    $sarly_mgmt['pt_count'] = $result['pt_count'];
	    $sarly_mgmt['calcu_set_rate'] = $result['calcu_set_rate'];
	    $sarly_mgmt['calcu_set_amt'] = $result['calcu_set_amt'];
	    $sarly_mgmt['calcu_set_1tm'] = $result['calcu_set_1tm'];
	    
	    $sarly_mgmt['calcu_set_rate_yn'] = $result['calcu_set_rate_yn'];
	    $sarly_mgmt['calcu_set_amt_yn'] = $result['calcu_set_amt_yn'];
	    $sarly_mgmt['calcu_set_1tm_yn'] = $result['calcu_set_1tm_yn'];
	    
	    $sarly_mgmt['cost'] = $result['cost'];
	    
	    return $sarly_mgmt;
	}
	
	// 계산해야할 시작 종료일을 구한다.
	private function real_s_e_date_detail($tid,$sarly_mgmt_sno)
	{
	    $gData['comp_cd'] = $this->set_comp_cd;
	    $gData['bcoff_cd'] = $this->set_bcoff_cd;
	    $gData['tchr_id'] = $tid;
	    $gData['sdate'] = $this->set_ms_date;
	    $gData['edate'] = $this->set_me_date;
	    $gData['sarly_mgmt_sno'] = $sarly_mgmt_sno;
	    $get_sarly_mgmt = $this->modelSarly->get_sarly_mgmt_tid_detail($gData);
	    
	    // 계산해야할 시작 종료일을 구한다.
	    $new_i = 0;
	    $new_sarly_mgmt = array();
	    foreach ($get_sarly_mgmt as $r)
	    {
	        $new_sarly_mgmt[$new_i] = $r;
	        
	        if ($this->set_ms_date < $r['SARLY_APLY_S_DATE'])
	        {
	            $new_sarly_mgmt[$new_i]['new_s_date'] = $r['SARLY_APLY_S_DATE'] . " 00:00:00";
	        } else
	        {
	            $new_sarly_mgmt[$new_i]['new_s_date'] = $this->set_ms_date . " 00:00:00";
	        }
	        
	        if ($this->set_me_date > $r['SARLY_APLY_E_DATE'])
	        {
	            $new_sarly_mgmt[$new_i]['new_e_date'] = $r['SARLY_APLY_E_DATE'] . " 23:59:59";
	        } else
	        {
	            $new_sarly_mgmt[$new_i]['new_e_date'] = $this->set_me_date . " 23:59:59";
	        }
	        
	        $new_i++;
	    }
	    
	    $this->set_sarly_mgmt = $new_sarly_mgmt;
	}
	
	
	// 계산해야할 시작 종료일을 구한다.
	private function real_s_e_date($tid)
	{
	    $gData['comp_cd'] = $this->set_comp_cd;
	    $gData['bcoff_cd'] = $this->set_bcoff_cd;
	    $gData['tchr_id'] = $tid;
	    $gData['sdate'] = $this->set_ms_date;
	    $gData['edate'] = $this->set_me_date;
	    $get_sarly_mgmt = $this->modelSarly->get_sarly_mgmt_tid($gData);
	    
	    // 계산해야할 시작 종료일을 구한다.
	    $new_i = 0;
	    $new_sarly_mgmt = array();
	    foreach ($get_sarly_mgmt as $r)
	    {
	        $new_sarly_mgmt[$new_i] = $r;
	        
	        if ($this->set_ms_date < $r['SARLY_APLY_S_DATE'])
	        {
	            $new_sarly_mgmt[$new_i]['new_s_date'] = $r['SARLY_APLY_S_DATE'] . " 00:00:00";
	        } else
	        {
	            $new_sarly_mgmt[$new_i]['new_s_date'] = $this->set_ms_date . " 00:00:00";
	        }
	        
	        if ($this->set_me_date > $r['SARLY_APLY_E_DATE'])
	        {
	            $new_sarly_mgmt[$new_i]['new_e_date'] = $r['SARLY_APLY_E_DATE'] . " 23:59:59";
	        } else
	        {
	            $new_sarly_mgmt[$new_i]['new_e_date'] = $this->set_me_date . " 23:59:59";
	        }
	        
	        $new_i++;
	    }
	    
	    $this->set_sarly_mgmt = $new_sarly_mgmt;
	}
	
	/**
	 * 이번달의 시작과 끝일을 셋팅한다.
	 * @param string $ym
	 */
	private function ym_s_e_date($ym)
	{
	    $this->set_ms_date =  date("Y-m-d", strtotime($ym."01"));
	    $edate = date("Y-m-d", strtotime("+1 month", strtotime($ym."01")));
	    $this->set_me_date = date("Y-m-d", strtotime("-1 days", strtotime($edate)));
	}
	
	
	
	
	
	
	
}