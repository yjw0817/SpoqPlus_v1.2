<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\CronModel;

class Cron_lib {

    private $set_initVar = array();
    
    private $cModel;
    
    /**
     * @param array $initVar
     */
    public function __construct($initVar) {
        $this->set_initVar = $initVar;
        $this->cModel = new CronModel();
	}
	
	public function cron_hist($param)
	{
	    $nn_now = new Time('now');
	    $cronData['cron_knd'] = 'cron_run_sdate';
	    $cronData['cron_cre_datetm'] = $nn_now;
	    $this->cModel->insert_cron($cronData);
	}
	
	/**
	 * cron 운동 시작일
	 * @return array
	 */
	public function cron_run_sdate()
	{
	    $nn_now = new Time('now');
	    $udata['exr_s_date'] = date('Y-m-d');
	    $udata['mod_datetm'] = $nn_now;
	    $this->cModel->update_cron_exr_s_date($udata);
	    
	    // 라커룸 업데이트 처리
	    $lockr_list = $this->cModel->list_cron_lockr_s_date($udata);
	    
	    foreach ($lockr_list as $r)
	    {
	        $udata['buy_event_sno'] = $r['BUY_EVENT_SNO'];
	        $udata['mem_sno'] = $r['MEM_SNO'];
	        $udata['mem_nm'] = $r['MEM_NM'];
	        $udata['lockr_use_s_date'] = $r['EXR_S_DATE'];
	        $udata['lockr_use_e_date'] = $r['EXR_E_DATE'];
	        $udata['comp_cd'] = $r['COMP_CD'];
	        $udata['bcoff_cd'] = $r['BCOFF_CD'];
	        $udata['lockr_knd'] = $r['LOCKR_KND'];
	        $udata['lockr_gendr_set'] = $r['LOCKR_GENDR_SET'];
	        $udata['lockr_no'] = $r['LOCKR_NO'];
	        
	        $this->cModel->update_cron_lockr_start($udata);
	    }
	    
	    $cronData['cron_knd'] = 'cron_run_sdate';
	    $cronData['cron_cre_datetm'] = $nn_now;
	    $this->cModel->insert_cron($cronData);
	}
	
	/**
	 * cron 운동 종료일
	 * @return array
	 */
	public function cron_run_edate()
	{
	    //운동 종료에 대한 처리는 운동 종료일로 인한 종료 처리 및 운동횟수 완료로 인한 종료 처리의 두가지가 있다.
	    $nn_now = new Time('now');
	    $udata['exr_e_date'] = date('Y-m-d');
	    $udata['mod_datetm'] = $nn_now;
	    $udata['lockr_use_e_date'] = date('Y-m-d');
	    
	    $this->cModel->update_cron_exr_e_date($udata);
	    $this->cModel->update_cron_lockr_end($udata);
	    $this->cModel->update_cron_pt_end($udata);
	    
	    $cronData['cron_knd'] = 'cron_run_edate';
	    $cronData['cron_cre_datetm'] = $nn_now;
	    $this->cModel->insert_cron($cronData);
	}
	
	/**
	 * cron 추천상품 구매 안한것 종료
	 * @return array
	 */
	public function cron_run_send_end()
	{
	    $nn_now = new Time('now');
	    $udata['mod_datetm'] = $nn_now;
	    $udata['send_buy_e_date'] = date('Y-m-d');
	    
	    $this->cModel->update_cron_send_end($udata);
	    
	    $cronData['cron_knd'] = 'cron_run_send_end';
	    $cronData['cron_cre_datetm'] = $nn_now;
	    $this->cModel->insert_cron($cronData);
	}
	
}