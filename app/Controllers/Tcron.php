<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Cron_lib;
use App\Libraries\Domcy_lib;
use App\Models\CronModel;
use CodeIgniter\I18n\Time;

class Tcron extends BaseController
{
    public function __construct()
    {
        // 세션 시작
        $session = session();
        
        // 로그인 체크
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['site_type']) || $_SESSION['site_type'] != 'tlogin') {
            echo "<script>alert('로그인이 필요합니다.');</script>";
            echo "<script>location.href='/tlogin';</script>";
            exit;
        }
    }
    
    public function index()
    {
        $cModel = new CronModel();
        $data['cron_list'] = $cModel->get_cron_list();
        
        return view('tcron/cron_test', $data);
    }
    
    public function run_cron()
    {
        $cron_type = $this->request->getPost('cron_type');
        
        switch($cron_type) {
            case 'exr_e_date':
                $initVar['type'] = "cron";
                $cronLib = new Cron_lib($initVar);
                $cronLib->cron_run_edate();
                break;
                
            case 'exr_s_date':
                $initVar['type'] = "cron";
                $cronLib = new Cron_lib($initVar);
                $cronLib->cron_run_sdate();
                break;
                
            case 'send_end':
                $initVar['type'] = "cron";
                $cronLib = new Cron_lib($initVar);
                $cronLib->cron_run_send_end();
                break;
                
            case 'domcy_hist_end':
                $initVar['type'] = "cron";
                $domcyLib = new Domcy_lib($initVar);
                $domcyLib->domcy_cron_hist_end();
                break;
                
            case 'domcy_cron_end':
                $initVar['type'] = "cron";
                $domcyLib = new Domcy_lib($initVar);
                $domcyLib->domcy_cron_end();
                break;
                
            case 'domcy_run':
                $initVar['type'] = "cron";
                $domcyLib = new Domcy_lib($initVar);
                $domcyLib->domcy_cron_run();
                break;
        }
        
        // 크론 실행 기록 저장
        $cModel = new CronModel();
        $nn_now = new Time('now');
        $cronData['cron_knd'] = $cron_type;
        $cronData['cron_cre_datetm'] = $nn_now;
        $cModel->insert_cron($cronData);
        
        return redirect()->to('/tcron');
    }
} 