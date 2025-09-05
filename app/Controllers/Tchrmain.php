<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\MemModel;

class Tchrmain extends MainTchrController
{
    public function lockscreen()
    {
        $data = array(
            'title' => '잠금화면',
            'nav' => array('잠금화면' => '' , '잠금화면' => ''),
            'menu1' => '0',
            'menu2' => '0'
        );
        $data['ref_url'] = "aa";
        echo view('/tchr/lockrscreen',$data); // 상단
    }
    
    /**
     * 어드민 DashBaord
     */
    public function dashboard()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $model = new MemModel();
        
        $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        // 이번 달 시작일과 종료일 설정
        $data['sdate'] = date('Y-m-01'); // 이번 달 1일
        $data['edate'] = date('Y-m-t');  // 이번 달 마지막일
        
        $all_sales_total = $model->all_sales_total($data);
        $all_sales_total_1 = $model->all_sales_total_1($data);
        $all_sales_total_2 = $model->all_sales_total_2($data);
        $all_sales_total_3 = $model->all_sales_total_3($data);
        $all_sales_total_4 = $model->all_sales_total_4($data);
        
        $data['type'] = "JON";
        $get_mem_count['JON'] = $model->get_mem_status($data);
        
        $data['type'] = "REG";
        $get_mem_count['REG'] = $model->get_mem_status($data);
        
        $data['type'] = "RE_REG";
        $get_mem_count['RE_REG'] = $model->get_mem_status($data);
        
        $data['type'] = "END";
        $get_mem_count['END'] = $model->get_mem_status($data);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['all_sales_total'] = $all_sales_total;
        $data['view']['all_sales_total_1'] = $all_sales_total_1;
        $data['view']['all_sales_total_2'] = $all_sales_total_2;
        $data['view']['all_sales_total_3'] = $all_sales_total_3;
        $data['view']['all_sales_total_4'] = $all_sales_total_4;
        $data['view']['get_mem_count'] = $get_mem_count;
        $this->viewPage('/tchr/info_tchr',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 대분류 사용 설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    
    
    
    
    
    
    
    
    
    
}