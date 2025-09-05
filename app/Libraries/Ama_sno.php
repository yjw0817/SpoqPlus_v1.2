<?php
namespace App\Libraries;


use App\Models\SnoModel;

class Ama_sno {
	
	private $snoType;
	private $model;
	
	public function __construct() {
	    $this->model= new SnoModel();
	}
	
	/**
	 * zsno_bcoff_no 테이블에 생성한다.
	 * @param String $comp_cd
	 */
	public function insert_bcoff_no($comp_cd)
	{
	    $sno_mdata['comp_cd'] = $comp_cd;
	    $sno_mdata['bcoff_sno_cnt'] = 0;
	    
	    $count_zson_bcoff_no = $this->model->list_zsno_bcoff_no_count($sno_mdata);
	    
	    if ($count_zson_bcoff_no == 0) :
	       $insert_zsno_bcoff_no = $this->model->insert_zsno_bcoff_no($sno_mdata);
	    endif;
	}
	
	/**
	 * 지점코드를 리턴한다.
	 * @param string $comp_cd
	 * @return string
	 */
	public function create_bcoff_no($comp_cd)
	{
	    $mdata['comp_cd'] = $comp_cd;
		
	    $bcoff_sno_cnt = $this->model->cnt_zsno_bcoff_no($mdata);
		if($bcoff_sno_cnt == 0)
		{
			$mdata['bcoff_sno_cnt'] = $bcoff_sno_cnt + 1;
			$this->model->insert_zsno_bcoff_no($mdata);
		} else
		{
	    	$this->model->update_zsno_bcoff_no($mdata);
		}
	    $bcoff_sno = $this->model->get_zsno_bcoff_no($mdata);
	    $returnVal = $comp_cd . "F" . sprintf('%04d',$bcoff_sno);
	    return $returnVal;
	}
	
	/**
	 * 회원번호를 리턴한다.
	 * @return string
	 */
	public function create_mem_sno()
	{
	    $mdata["sno_date"] = date("Ymd");
	    $r = $this->model->mem_sno($mdata);
	    
	    $returnVal['mem_sno'] = "MM" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id);
	    $returnVal['mem_sno_detl'] = "MD" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id);
	    $returnVal['mem_sno_hist'] = "MH" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id);
	    
	    return $returnVal;
	}
	
	/**
	 * 판매상품 일련번호를 리턴한다.
	 * @return string
	 */
	public function create_sell_event_sno()
	{
		$mdata["sno_date"] = date("Ymd");
		$r = $this->model->sell_event_sno($mdata);
		
		$returnVal['sell_event_sno'] = "EC" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id); // event creat : EC
		
		return $returnVal;
	}
	
	/**
	 * 결제 VAN 일련번호를 리턴한다.
	 * @return string
	 */
	public function create_paymt_mgmt_sno()
	{
	    $mdata["sno_date"] = date("Ymd");
	    $r = $this->model->paymt_mgmt_sno($mdata);
	    
	    $returnVal = "PV" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id); // payment van : PV
	    
	    return $returnVal;
	}
	
	/**
	 * 승인번호 일련번호를 리턴한다.
	 * @return string
	 */
	public function create_appno_sno()
	{
	    $mdata["sno_date"] = date("Ymd");
	    $r = $this->model->appno_sno($mdata);
	    
	    $returnVal = "AS" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id); // appno sno : AS
	    
	    return $returnVal;
	}
	
	/**
	 * 구매 일련번호를 리턴한다.
	 * @return string
	 */
	public function create_buy_sno()
	{
		$mdata["sno_date"] = date("Ymd");
		$r = $this->model->buy_sno($mdata);
		
		$returnVal = "BE" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id); // buy event : BE
		
		return $returnVal;
	}
	
	/**
	 * 미수 일련번호를 리턴한다.
	 * @return string
	 */
	public function create_misu_sno()
	{
	    $mdata["sno_date"] = date("Ymd");
	    $r = $this->model->misu_sno($mdata);
	    
	    $returnVal = "MS" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id); // misu : MS
	    
	    return $returnVal;
	}
	
	/**
	 * 결제관리 일련번호
	 * @return string
	 */
	public function create_pay_sno()
	{
	    $mdata["sno_date"] = date("Ymd");
	    $r = $this->model->pay_sno($mdata);
	    
	    $returnVal = "PY" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id); // payment
	    
	    return $returnVal;
	}
	
	/**
	 * 매출관리 일련번호
	 * @return string
	 */
	public function create_sales_sno()
	{
	    $mdata["sno_date"] = date("Ymd");
	    $r = $this->model->sales_sno($mdata);
	    
	    $returnVal = "SS" . $mdata["sno_date"] . sprintf('%010d',$r->connID->insert_id); // sales
	    
	    return $returnVal;
	}
	
	
	
	/**
	 * sample
	 * @return 
	 */
	public function getSno()
	{
		switch($this->snoType)
		{
			case 'get' :
				return $this->get_calendar();
				break;
			case 'create' :
				return $this->create_calendar();
				break;
			case 'update' :
				return $this->update_calendar();
				break;
		}
	}
}