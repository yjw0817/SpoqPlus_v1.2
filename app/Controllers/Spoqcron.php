<?php
namespace App\Controllers;

use App\Libraries\Cron_lib;
use App\Libraries\Domcy_lib;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\I18n\Time;
use App\Models\CronModel;
use App\Models\SpoQOldModel;
use App\Models\MemModel;
use App\Models\AttdModel;

class SpoQcron extends BaseController
{
    
    /**
     * 전화번호 암호화 lenth
     * @param string $telno
     */
    protected function enc_tel($telno)
    {
        $encryption = new Encryption();
        $encrypter = $encryption->initialize(
            );
        $ciphertext = base64_encode($encrypter->encrypt($telno));
        return $ciphertext;
    }
    
    /**
     * 전화번호 복호화
     * @param string $enc_telno
     */
    protected function denc_tel($enc_telno)
    {
        $encryption = new Encryption();
        $encrypter = $encryption->initialize(
            );
        $de_ciphertext = $encrypter->decrypt(base64_decode($enc_telno));
        return $de_ciphertext;
    }
    
    protected function enc_phone($var="")
    {
        if ($var == "" || $var == null)
        {
            $return_val['mask'] = "";
            $return_val['short'] = "";
            $return_val['enc'] = "";
            $return_val['enc2'] = "";
        } else
        {
            $ch_var = disp_phone($var); // 전화번호 형식을 다시 - 형식으로 바꾼다.
            $ex_var = explode("-",$ch_var); // - 형식으로 문자를 나누어 배열로 저장한다.
            
            $mask_len = strlen($ex_var[1]); // 가운데 전화번호를 마스크 하기 위하여 마스크할 길이를 구한다.
            
            $add_mask = "";
            for($i=0; $i<$mask_len; $i++)
            {
                $add_mask .= "#";
            }
            
            $mask_phone = $ex_var[0] . "-" . $add_mask . "-" . $ex_var[2];
            
            $return_val['mask'] = $mask_phone;
            $return_val['short'] = $ex_var[2];
            $return_val['enc'] = $this->enc_tel($var);
            $return_val['enc2'] = $this->enc_pass($var);
            
        }
        
        return $return_val;
    }
    
    /**
     * 비밀번호 암호화 (lenth 64 return)
     * @param string $npass
     */
    protected function enc_pass($npass)
    {
        $enc_word = hash('sha256',$npass);
        return $enc_word;
    }
    
    public function old_attd()
    {
        
        $SpoQOldDb = new SpoQOldModel();
        
        $oldData['ndate'] = date('Y-m-d') . " 00:00:00";
        $oldData['user_id_select'] = "jamesgymjs";
        $oldData['user_type'] = "MEMBER";
        $get_old_count = $SpoQOldDb->today_SpoQ_old_attd($oldData);
        
        $model = new CronModel();
        $nowData['comp_cd'] = 'C0003';
        $nowData['bcoff_cd'] = 'C0003F0019';
        $nowData['attd_ymd'] = date('Ymd');
        $get_now_count = $model->test_attd_chk($nowData);
        
        if ($get_old_count[0]['counter'] > $get_now_count[0]['counter'])
        {
            $culc_count = $get_old_count[0]['counter'] - $get_now_count[0]['counter'];
            if ( $culc_count > 0 )
            {
                $lData['limit_s'] = $culc_count;
                $lData['ndate'] = date('Y-m-d') . " 00:00:00";
                $lData['user_id_select'] = "jamesgymjs";
                $lData['user_type'] = "MEMBER";
                
                $get_list = $SpoQOldDb->today_SpoQ_old_attd_list($lData);
                
                foreach ($get_list as $r)
                {
                    $this->attd_test_chk($r);
                }
                
            }
        }
        
    }
    
    private function attd_test_chk($attd_info)
    {
        $memModel = new MemModel();
        
        $mdata['comp_cd'] = 'C0003';
        $mdata['bcoff_cd'] = 'C0003F0019';
        $mdata['mem_id'] = $attd_info['user_id'];
        
        $mem_info = $memModel->get_mem_info_id_idname($mdata);
        
        if (count($mem_info) > 0)
        {
            $attdModel = new AttdModel();    
            
            $adata['comp_cd'] = 'C0003';
            $adata['bcoff_cd'] = 'C0003F0019';
            
            $adata['mem_sno'] = $mem_info[0]['MEM_SNO'];
            $adata['mem_id'] = $mem_info[0]['MEM_ID'];
            $adata['mem_nm'] = $mem_info[0]['MEM_NM'];
            
            $adata['mem_dv'] = 'M'; // fix
            $adata['attd_mthd'] = ''; // 없음
            $adata['attd_yn'] = 'Y'; // fix
            $adata['acc_rtrct_dv'] = '';  // 없음
            $adata['acc_rtrct_mthd'] = ''; // 없음
            $adata['agegrp'] = ''; // 없음
            $adata['mem_gendr'] = $mem_info[0]['MEM_GENDR'];
            
            $adata['attd_ymd'] = date('Ymd');
            $adata['attd_yy'] = date('Y');
            $adata['attd_mm'] = date('m');
            $adata['attd_dd'] = date('d');
            $adata['attd_dotw'] = $this->subfunc_dotw(date('Y-m-d'));
            $adata['attd_hh'] = substr($attd_info['user_date'],11,2);
            $adata['auto_chk'] = 'N'; // fix
            
            $adata['cre_id'] = $attd_info['user_id'];
            $adata['cre_datetm'] = $attd_info['user_date'];
            $adata['mod_id'] = $attd_info['user_id'];
            $adata['mod_datetm'] = $attd_info['user_date'];
            
            $attdModel->insert_attd_mgmt($adata);
            
        }
        
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
	
	public function test_cron($params = '')
	{
		echo "Test Cron {$params}" . PHP_EOL;
	}
	
	/**
	 * 회원 비밀번호 암호화 처리
	 * 회원 전화번호 암호화 처리
	 */
	public function update_meminfo()
	{
	    exit();
	    $cronModel = new CronModel();
	    $tData['sss'] = 'sss';
	    $memList = $cronModel->cron_list_mem_main_info($tData);
	    
	    foreach ($memList as $r)
	    {
	        if (strlen($r['MEM_PWD']) < 20)
	        {
	            
	            $uData['mem_sno'] = $r['MEM_SNO'];
	            $uData['mem_pwd'] = $this->enc_pass($r['MEM_PWD']);
	            $cronModel->cron_pwd_enc_update($uData);
	        }
	    }
	    
	    foreach($memList as $r)
	    {
	        if ($r['MEM_TELNO_ENC'] == null)
	        {
	            $denc_phone = $this->enc_phone($r['MEM_TELNO']);
	            $uData2['mem_sno'] = $r['MEM_SNO'];
	            $uData2['mem_telno_enc'] = $denc_phone['enc'];
	            $uData2['mem_telno_enc2'] = $denc_phone['enc2'];
	            $uData2['mem_telno_mask'] = $denc_phone['mask'];
	            $uData2['mem_telno_short'] = $denc_phone['short'];
	            
	            $cronModel->cron_telno_enc_update($uData2);
	        }
	    }
	    
	    $smgmtList = $cronModel->cron_list_smgmt($tData);
	    
	    foreach ($smgmtList as $r)
	    {
	        if (strlen($r['SMGMT_PWD']) < 20)
	        {
	            
	            $uData['smgmt_id'] = $r['SMGMT_ID'];
	            $uData['smgmt_pwd'] = $this->enc_pass($r['SMGMT_PWD']);
	            $cronModel->cron_pwd_enc_smgmt_update($uData);
	        }
	    }
	    
	    
	    echo "succes converting";
	}
	
	private function record_cron($cron_type) 
	{
	    $cModel = new CronModel();
	    $nn_now = new Time('now');
	    $cronData['cron_knd'] = $cron_type;
	    $cronData['cron_cre_datetm'] = $nn_now;
	    $cModel->insert_cron($cronData);
	}
	
	/**
	 * 운동 종료일을 이용하여 종료됨으로 처리한다.
	 * 운동종료일은 당일 23시 30분에 처리한다.
	 */
	public function cron_exr_e_date()
	{
	    $initVar['type'] = "cron";
	    $cronLib = new Cron_lib($initVar);
	    $cronLib->cron_run_edate();
	    
	    $this->record_cron('exr_e_date');
	    echo "cron_exr_e_date completed at " . date('Y-m-d H:i:s');
	}
	
	/**
	 * 운동 시작일을 이용하여 이용중으로 처리한다.
	 * 운동시작일은 당일 00시 10분에 처리한다.
	 */
	public function cron_exr_s_date()
	{
	    $initVar['type'] = "cron";
	    $cronLib = new Cron_lib($initVar);
	    $cronLib->cron_run_sdate();
	    
	    $this->record_cron('exr_s_date');
	    echo "cron_exr_s_date completed at " . date('Y-m-d H:i:s');
	}
	
	/**
	 * 이용안한 추천상품 상태를 종료한다.
	 * 당일 23시 20분에 처리한다.
	 */
	public function cron_send_end()
	{
	    $initVar['type'] = "cron";
	    $cronLib = new Cron_lib($initVar);
	    $cronLib->cron_run_send_end();
	    
	    $this->record_cron('send_end');
	    echo "cron_send_end completed at " . date('Y-m-d H:i:s');
	}
    
	/**
	 * 휴회에 대한 실행 순서
	 * 1. 휴회을 새로 적용하기전에 이전에 신청한 세부적용 상품을 종료 처리한다.
	 * 2. 휴회 신청 리스트에서 휴회이 완료된 내역을 먼저 처리한다.
	 * 3. 휴회을 새롭게 신청한 내역을 실행한다.
	 */
	
	
	/**
	 * [CRON] 휴회 세부적용 상품 종료하기
	 * 하루에 한번 실행
	 * 당일 23시 40분에 처리한다.
	 */
	public function cron_domcy_hist_end()
	{
	    $initVar['type'] = "cron";
	    $domcyLib = new Domcy_lib($initVar);
	    $domcyLib->domcy_cron_hist_end();
	    
	    $this->record_cron('domcy_hist_end');
	    echo "cron_domcy_hist_end completed at " . date('Y-m-d H:i:s');
	}
	
	/**
	 * [CRON] 휴회 신청 리스트 종료하기
	 * 당일 23시 50분에 처리한다.
	 */
	public function cron_domcy_cron_end()
	{
	    $initVar['type'] = "cron";
	    $domcyLib = new Domcy_lib($initVar);
	    $domcyLib->domcy_cron_end();
	    
	    $this->record_cron('domcy_cron_end');
	    echo "cron_domcy_cron_end completed at " . date('Y-m-d H:i:s');
	}
	
	/**
	 * [CRON] 휴회 실행하기
	 * 하루에 한번 실행
	 * 당일 00시 20분에 처리한다.
	 */
	public function cron_domcy_run()
	{
	    $initVar['type'] = "cron";
	    $domcyLib = new Domcy_lib($initVar);
	    $domcyLib->domcy_cron_run();
	    
	    $this->record_cron('domcy_run');
	    echo "cron_domcy_run completed at " . date('Y-m-d H:i:s');
	}
	
	
}