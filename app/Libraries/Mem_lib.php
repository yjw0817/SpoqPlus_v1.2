<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\EventModel;

class Mem_lib {

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
     * $initVar['mem_sno'];
     * @param array $initVar
     */
    public function __construct($initVar) {
        $this->set_initVar = $initVar;
	}
	
	/**
	 * 회원의 출입조건을 가져온다.
	 * @return array['acc_mthd']
	 */
	public function get_acc_methd()
	{
	    $this->modelEvent = new EventModel();
	    
	    $buy_list = $this->modelEvent->get_buy_events_use_memsno($this->set_initVar);
	    
	    $acc_rtrct_mthd = "";
	    foreach ($buy_list as $r)
	    {
	        if ($r['ACC_RTRCT_DV'] == "01")
	        {
	            $acc_rtrct_mthd = $r['ACC_RTRCT_MTHD'];
	        }
	    }
	    
	    $this->retunValue['acc_mthd'] = $acc_rtrct_mthd;
	    return $this->retunValue;
	}
	
	
	
	
	
	
	
}