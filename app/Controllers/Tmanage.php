<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Libraries\Sarly_lib;
use App\Models\AnnuModel;
use App\Models\MemModel;
use App\Models\SarlyModel;
use App\Models\CateModel;

class Tmanage extends MainTchrController
{
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 연차신청/내역 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 연차신청/내역
     */
    public function annu_appt_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $mem_sno = $this->SpoQCahce->getCacheVar('user_sno');
        
        $modelMem = new MemModel();
        $modelAnnu = new AnnuModel();
        
        $tdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $tdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $tdata['mem_sno'] = $mem_sno;
        $tchr_info = $modelMem->get_tmem_info_mem_sno($tdata);
        
        $annu_list = $modelAnnu->list_tchr_annu_grant_mgmt_pos_use($tdata);
        
        if (count($annu_list) > 0) 
        {
            $tchr_annu_list = $annu_list;
            $annu_grant_mgmt_sno = $annu_list[0]['ANNU_GRANT_MGMT_SNO'];
        } else 
        {
            $tchr_annu_list = array();
            $annu_grant_mgmt_sno = "";
        }
        
        $appct_annu_list = $modelAnnu->list_tchr_annu_grant_mgmt_hist_memsno($tdata);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['annu_grant_mgmt_sno'] = $annu_grant_mgmt_sno;
        $data['view']['tchr_annu_appct_list'] = $appct_annu_list;
        $data['view']['tchr_annu_list'] = $tchr_annu_list;
        $data['view']['tchr_info'] = $tchr_info[0];
        $this->viewPage('/tchr/tmanage/annu_appt_manage',$data);
    }
    
    /**
     * 연차 신청하기
     */
    public function ajax_tchr_annu_appct_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $modelAnnu = new AnnuModel();
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        $hdata['annu_grant_mgmt_sno'] = $postVar['annu_grant_mgmt_sno'];
        $hdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $hdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $hdata['mem_sno'] = $postVar['mem_sno'];
        $hdata['mem_id'] = $postVar['mem_id'];
        $hdata['mem_nm'] = $postVar['mem_nm'];
        $hdata['annu_appv_id'] = "";
        $hdata['annu_appct_knd'] = "01"; // 01 : 정규연차 / 02: 포상연차
        $hdata['annu_appct_s_date'] = $postVar['annu_appct_s_date'];
        $hdata['annu_appct_e_date'] = $postVar['annu_appct_e_date'];
        $hdata['annu_use_day'] = $postVar['annu_days'];
        $hdata['annu_appv_stat'] = "00";
        $hdata['annu_appv_datetm'] = $nn_now;
        $hdata['annu_refus_datetm'] = "";
        $hdata['annu_appct_datetm'] = "";
        $hdata['annu_cancel_datetm'] = "";
        $hdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $hdata['cre_datetm'] = $nn_now;
        $hdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $hdata['mod_datetm'] = $nn_now;
        
        $modelAnnu->insert_tchr_annu_use_mgmt_hist($hdata);
        // ===========================================================================
        // Processs
        // ===========================================================================
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 연차신청 취소하기
     */
    public function ajax_tchr_annu_cancel_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $modelAnnu = new AnnuModel();
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        $cdata['annu_use_mgmt_hist_sno'] = $postVar['hist_sno'];
        $cdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $cdata['annu_appv_stat'] = "99";
        $cdata['annu_cancel_datetm'] = $nn_now;
        $cdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $cdata['mod_datetm'] = $nn_now;
        
        $modelAnnu->update_tchr_annu_use_mgmt_hist_cancel($cdata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 수당 집계표 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    public function tchr_salary_detail()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '수당 집계표(상세)',
            'nav' => array('강사관리' => '' , '수당 집계표(상세)' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        /**
         * ss_yy
         * ss_mm
         * mid
         * kind
         * sno
         * search_mid
         */
        $postVar = $this->request->getPost();
        
        $ss_yy = $postVar['ss_yy'];
        $ss_mm = $postVar['ss_mm'];
        $mid = $postVar['mid'];
        $kind = $postVar['kind'];
        $sarly_mgmt_sno = $postVar['sno'];
        
//         $ss_yy = date('Y');
//         $ss_mm = date('m');
//         if (isset($postVar['ss_yy']))
//         {
//             $ss_yy = $postVar['ss_yy'];
//             $ss_mm = $postVar['ss_mm'];
//         }
        
//         // test
//         $ss_yy = '2024';
//         $ss_mm = '06';
//         $mid = 't0002';
        
        $ss_ym = $ss_yy .  $ss_mm;
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sarlyLib = new Sarly_lib($initVar);
        
//         $sarly_mgmt_sno = '8';
        // kind : 01 sell_amt_detail , 02 pt_amt_detail , 03 pt_count_detail
//         $kind = "03";
        $detail_sarly = $sarlyLib->get_sarly_detail($mid, $ss_ym,$sarly_mgmt_sno,$kind);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['detail_sarly'] = $detail_sarly; // 수당집계표 상세
        $data['view']['postVar'] = $postVar;
        
        if ($kind == "01")
        {
            $this->viewPage('/tchr/tmanage/tchr_salary_detail1',$data); // 매출
        } else if($kind == '04') 
        {
            $this->viewPage('/tchr/tmanage/tchr_salary_detail4',$data); // GX 수업진행
        } else 
        {
            $this->viewPage('/tchr/tmanage/tchr_salary_detail2',$data); // 수업진행
        }
    }
    
    
    /**
     * 수당 집계표
     */
    public function tchr_salary_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    	
        
        $postVar = $this->request->getPost();
        
        $ss_yy = date('Y');
        $ss_mm = date('m');
        if (isset($postVar['ss_yy']))
        {
            $ss_yy = $postVar['ss_yy'];
            $ss_mm = $postVar['ss_mm'];
        }
        
        $ss_ym = $ss_yy .  $ss_mm;
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sarlyLib = new Sarly_lib($initVar);
        
        $memModel = new MemModel();
        
        $tid = "";
        
        $initVar['sarly_sece_date'] = $ss_yy . "-" . $ss_mm . "-01 02:00:00"; 
        
        if ( isset($postVar['tid']))
        {
            if ($postVar['tid'] != '')
            {
                $tid = $postVar['tid'];
                $initVar['mem_id'] = $postVar['tid'];
                $get_list_tchr = $memModel->get_list_tchr_for_id($initVar);
            } else 
            {
                $get_list_tchr = $memModel->get_list_tchr_sarly($initVar);
            }
            
        } else 
        {
            $get_list_tchr = $memModel->get_list_tchr_sarly($initVar);
        }
        
        
        
        
        foreach ($get_list_tchr as $r)
        {
            $list_sarly[$r['MEM_ID']] = $sarlyLib->get_sarly($r['MEM_ID'], $ss_ym);
            $list_sarly[$r['MEM_ID']]['MEM_NM'] = $r['MEM_NM'];
        }
        
        $list_tchr = $memModel->get_list_tchr_sarly($initVar);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($list_tchr as $t) :
            $tchr_list[$tchr_i] = $t;
            $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
            $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
            $tchr_i++;
        endforeach;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['tid'] = $tid; // 검색된 강사 아이디
        $data['view']['tchr_list'] = $tchr_list; // 강사리스트
        $data['view']['ss_yy'] = $ss_yy; // 년도
        $data['view']['ss_mm'] = $ss_mm; // 월
        $data['view']['list_sarly'] = $list_sarly; // 강사 수당 설정 리스트
        $this->viewPage('/tchr/tmanage/tchr_salary_manage',$data);
    }
    
    /**
     * 강사 수당 집계표 AJAX 검색
     */
    public function ajax_tchr_salary_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $ss_yy = isset($postVar['ss_yy']) ? $postVar['ss_yy'] : date('Y');
        $ss_mm = isset($postVar['ss_mm']) ? $postVar['ss_mm'] : date('m');
        $tid = isset($postVar['tid']) ? $postVar['tid'] : '';
        
        $ss_ym = $ss_yy .  $ss_mm;
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['ss_yy'] = $ss_yy;
        $initVar['ss_mm'] = $ss_mm;
        $initVar['ss_ym'] = $ss_ym;
        $initVar['tid'] = $tid;
        
        $memModel = new MemModel();
        $sarlyLib = new Sarly_lib($initVar);
        
        $initVar['sarly_sece_date'] = $ss_yy . "-" . $ss_mm . "-01 02:00:00";
        
        if ($tid != '') {
            $initVar['mem_id'] = $tid;
            $get_list_tchr = $memModel->get_list_tchr_for_id($initVar);
        } else {
            $get_list_tchr = $memModel->get_list_tchr_sarly($initVar);
        }
        
        $list_sarly = array();
        foreach ($get_list_tchr as $r) {
            $list_sarly[$r['MEM_ID']] = $sarlyLib->get_sarly($r['MEM_ID'], $ss_ym);
            $list_sarly[$r['MEM_ID']]['MEM_NM'] = $r['MEM_NM'];
            $list_sarly[$r['MEM_ID']]['MEM_SNO'] = $r['MEM_SNO'];
            $list_sarly[$r['MEM_ID']]['MEM_THUMB_IMG'] = $r['MEM_THUMB_IMG'];
            $list_sarly[$r['MEM_ID']]['MEM_MAIN_IMG'] = $r['MEM_MAIN_IMG'];
            $list_sarly[$r['MEM_ID']]['MEM_GENDR'] = $r['MEM_GENDR'];
        }
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        if (empty($list_sarly)) {
            $html = '<div class="text-center" style="padding: 50px;">검색 결과가 없습니다.</div>';
        } else {
            $html .= '<table class="table table-bordered table-hover table-striped col-md-12">';
            
            foreach($list_sarly as $key => $tchr_sarly) {
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<td class="bg-white text-center bold" colspan="13">';
                
                // 강사 사진 처리
                if (isset($tchr_sarly['MEM_THUMB_IMG']) || isset($tchr_sarly['MEM_GENDR'])) {
                    $html .= '<img class="preview_mem_photo" ';
                    $html .= 'id="preview_mem_photo_' . $tchr_sarly['MEM_SNO'] . '" ';
                    $html .= 'src="' . (isset($tchr_sarly['MEM_THUMB_IMG']) ? $tchr_sarly['MEM_THUMB_IMG'] : '') . '" ';
                    $html .= 'alt="강사사진" style="cursor: pointer;" ';
                    $html .= 'onclick="showFullPhoto(\'' . (isset($tchr_sarly['MEM_MAIN_IMG']) ? $tchr_sarly['MEM_MAIN_IMG'] : '') . '\')" ';
                    $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($tchr_sarly['MEM_GENDR']) ? $tchr_sarly['MEM_GENDR'] : 'M') . '.png\';">';
                }
                $html .= $tchr_sarly['MEM_NM'] . ' (' . $key . ')';
                $html .= '</td>';
                $html .= '</tr>';
                
                $html .= '<tr class="text-center">';
                $html .= '<th class="" style="width:90px">적용 시작일</th>';
                $html .= '<th class="" style="width:90px">적용 종료일</th>';
                $html .= '<th class="" style="width:90px">지급조건</th>';
                $html .= '<th class="">지급방법</th>';
                $html .= '<th class="bg-info" style="width:90px">집계 시작일</th>';
                $html .= '<th class="bg-info" style="width:90px">집계 종료일</th>';
                $html .= '<th class="bg-success" style="width:90px">판매매출액</th>';
                $html .= '<th class="bg-success" style="width:90px">수업매출액</th>';
                $html .= '<th class="bg-success" style="width:90px">수업횟수</th>';
                $html .= '<th class="bg-warning" style="width:90px">요율(%)</th>';
                $html .= '<th class="bg-warning" style="width:90px">수당금액</th>';
                $html .= '<th class="bg-warning" style="width:90px">1회금액</th>';
                $html .= '<th class="bg-pink" style="width:90px">지급금액</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                
                $html .= '<tbody>';
                
                $sum_cost = 0;
                foreach ($tchr_sarly['sarly_mgmt'] as $r) {
                    $sum_cost += $r['cost'];
                    
                    $rate_class = "";
                    $amt_class = "";
                    $onetm_class = "";
                    if($r['calcu_set_rate_yn'] == 'N') $rate_class = 'bg-danger';
                    if($r['calcu_set_amt_yn'] == 'N') $amt_class = 'bg-danger';
                    if($r['calcu_set_1tm_yn'] == 'N') $onetm_class = 'bg-danger';
                    
                    if ($r['sell_amt'] == 0 && $r['pt_amt'] == 0 && $r['pt_count'] == 0) {
                        if($r['calcu_set_rate_yn'] == 'N') $rate_class = 'bg-warning';
                        if($r['calcu_set_amt_yn'] == 'N') $amt_class = 'bg-warning';
                        if($r['calcu_set_1tm_yn'] == 'N') $onetm_class = 'bg-warning';
                    }
                    
                    $html .= '<tr>';
                    $html .= '<td>' . $r['SARLY_APLY_S_DATE'] . '</td>';
                    $html .= '<td>' . $r['SARLY_APLY_E_DATE'] . '</td>';
                    $html .= '<td>' . $sDef['SARLY_PMT_COND'][$r['SARLY_PMT_COND']] . '</td>';
                    
                    $html .= '<td>';
                    $html .= '<i style="cursor:pointer;color:skyblue" class="fas fa-info-circle" onclick="info_detail(\'' . $r['SARLY_MGMT_SNO'] . '\');"></i>';
                    $html .= $sDef['SARLY_PMT_MTHD_NAME'][$r['SARLY_PMT_MTHD']];
                    $html .= '</td>';
                    
                    $html .= '<td>' . substr($r['new_s_date'],0,10) . '</td>';
                    $html .= '<td>' . substr($r['new_e_date'],0,10) . '</td>';
                    
                    // 판매 매출액
                    if($r['sell_amt'] > 0) {
                        $html .= '<td style="cursor:pointer; text-align:right" onclick="detail_info(\'' . $key . '\',\'01\',\'' . $r['SARLY_MGMT_SNO'] . '\');">' . number_format($r['sell_amt']) . '</td>';
                    } else {
                        $html .= '<td style="color:red; text-align:right;">' . number_format($r['sell_amt']) . '</td>';
                    }
                    
                    // 수업 매출액
                    if($r['pt_amt'] > 0) {
                        $html .= '<td style="cursor:pointer; text-align:right;" onclick="detail_info(\'' . $key . '\',\'02\',\'' . $r['SARLY_MGMT_SNO'] . '\');">' . number_format($r['pt_amt']) . '</td>';
                    } else {
                        $html .= '<td style="color:red; text-align:right;">' . number_format($r['pt_amt']) . '</td>';
                    }
                    
                    // 수업횟수
                    if($r['pt_count'] > 0) {
                        if($r['SARLY_PMT_MTHD'] == '32') {
                            $html .= '<td style="cursor:pointer; text-align:right;" onclick="detail_info(\'' . $key . '\',\'04\',\'' . $r['SARLY_MGMT_SNO'] . '\');">' . number_format($r['pt_count']) . '</td>';
                        } else {
                            $html .= '<td style="cursor:pointer; text-align:right;" onclick="detail_info(\'' . $key . '\',\'03\',\'' . $r['SARLY_MGMT_SNO'] . '\');">' . number_format($r['pt_count']) . '</td>';
                        }
                    } else {
                        $html .= '<td style="color:red; text-align:right;">' . number_format($r['pt_count']) . '</td>';
                    }
                    
                    // 요율
                    if($rate_class == 'bg-danger') {
                        $html .= '<td class="text-right ' . $rate_class . '" onclick="more_salary_setting(\'' . $r['SARLY_MGMT_SNO'] . '\');">' . number_format($r['calcu_set_rate']) . '</td>';
                    } else {
                        $html .= '<td class="text-right ' . $rate_class . '">' . number_format($r['calcu_set_rate']) . '</td>';
                    }
                    
                    // 수당금액
                    if($amt_class == 'bg-danger') {
                        $html .= '<td class="text-right ' . $amt_class . '" onclick="more_salary_setting(\'' . $r['SARLY_MGMT_SNO'] . '\');">' . number_format($r['calcu_set_amt']) . '</td>';
                    } else {
                        $html .= '<td class="text-right ' . $amt_class . '">' . number_format($r['calcu_set_amt']) . '</td>';
                    }
                    
                    // 1회금액
                    if($onetm_class == 'bg-danger') {
                        $html .= '<td class="text-right ' . $onetm_class . '" onclick="more_salary_setting(\'' . $r['SARLY_MGMT_SNO'] . '\');">' . number_format($r['calcu_set_1tm']) . '</td>';
                    } else {
                        $html .= '<td class="text-right ' . $onetm_class . '">' . number_format($r['calcu_set_1tm']) . '</td>';
                    }
                    
                    $html .= '<td style="text-align:right">' . number_format($r['cost']) . '</td>';
                    $html .= '</tr>';
                }
                
                $html .= '<tr class="a"><td class="text-right aa" colspan="13"><b>' . number_format($sum_cost) . '</b></td></tr>';
                $html .= '</tbody>';
            }
            
            $html .= '</table>';
        }
        
        // ===========================================================================
        // 응답 데이터 구성
        // ===========================================================================
        $response = [
            'result' => 'true',
            'html' => $html
        ];
        
        return json_encode($response);
    }
    
    /**
     * 수당지급조건 상세 설정 ajax
     */
    public function ajax_salary_detail()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        
        // sno
        $postVar = $this->request->getPost();
        $sarlyModel =  new SarlyModel();
        $cateModel = new CateModel();
        
        $sdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $sdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sdata['sarly_mgmt_sno'] = $postVar['sno'];
        $get_sarly_info = $sarlyModel->get_sarly_mgmt($sdata);
        
        $subData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $subData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $subData['sarly_mgmt_sno'] = $postVar['sno'];
        $list_sub = $sarlyModel->list_sarly_sub_mgmt($subData);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        // 카테고리 정보
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cate1 = $cateModel->disp_cate1($cateData);
        $cate2 = $cateModel->disp_cate2($cateData);
        
        // 1차 2차 카테고리를 코드 배열로 정렬하여 이름을 구할 수 있도록 한다.
        $cate1_info = array();
        foreach ($cate1 as $c1)
        {
            $cate1_info[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        }
        $cate1_info['all'] = "전체";
        
        $cate2_info = array();
        foreach ($cate2 as $c2)
        {
            $cate2_info[$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        }
        $cate2_info['all'] = "전체";
        
        // 2단계 카테고리 정보
        $sDef = SpoqDef();
        $get_sarly_info[0]['TEXT_PMT_COND'] = $sDef['SARLY_PMT_COND'][$get_sarly_info[0]['SARLY_PMT_COND']];
        $get_sarly_info[0]['TEXT_PMT_MTHD'] = $this->disp_text_pmt_mthd($get_sarly_info[0]['SARLY_PMT_MTHD']);
        $get_sarly_info[0]['TEXT_DATE'] = $get_sarly_info[0]['SARLY_APLY_S_DATE'] . " ~ " . $get_sarly_info[0]['SARLY_APLY_E_DATE'];
        
        if ($get_sarly_info[0]['VAT_YN'] == "Y")
        {
            $get_sarly_info[0]['TEXT_VAT_YN'] = "부가세 포함";
        } else 
        {
            $get_sarly_info[0]['TEXT_VAT_YN'] = "부가세 제외";
        }
        
        if ($get_sarly_info[0]['SARLY_APLY_ITEM_1'] != '')
        {
            $item1_dv = explode(',',$get_sarly_info[0]['SARLY_APLY_ITEM_1']);
            $item1_array = array();
            for($i=0;$i<count($item1_dv);$i++)
            {
                $item1_array[$i] = $cate1_info[str_replace("'","",$item1_dv[$i])];
            }
            
            $get_sarly_info[0]['TEXT_ITEM1'] = implode(',',$item1_array);
        } else 
        {
            $get_sarly_info[0]['TEXT_ITEM1'] = "선택없음";
        }
        
        if ($get_sarly_info[0]['SARLY_APLY_ITEM_2'] != '')
        {
            $item2_dv = explode(',',$get_sarly_info[0]['SARLY_APLY_ITEM_2']);
            $item2_array = array();
            for($i=0;$i<count($item2_dv);$i++)
            {
                $item2_array[$i] = $cate2_info[str_replace("'","",$item2_dv[$i])];
            }
            
            $get_sarly_info[0]['TEXT_ITEM2'] = implode(',',$item2_array);
        } else 
        {
            $get_sarly_info[0]['TEXT_ITEM2'] = "선택없음";
        }
        
        $list_sub_array = array();
        $list_sub_i = 0;
        foreach ($list_sub as $r)
        {
            $list_sub_array[$list_sub_i] = $this->disp_text_sub($r);
            $list_sub_i++;
        }
        
        
        $return_json['info'] = $get_sarly_info[0];
        $return_json['sub'] = $list_sub_array;
        $return_json['result'] = 'true';
        return json_encode($return_json);
        
    }
    
    private function disp_text_sub($r)
    {
        $return_value = "";
        switch($r['SARLY_PMT_MTHD'])
        {
            case "11":
                $return_value = number_format($r['SELL_MM_SALES_S_AMT']) . "원 ~ " . number_format($r['SELL_MM_SALES_E_AMT']) . "원 ( " . $r['SELL_SALES_RATE'] . "% )";
                break;
            case "12":
                $return_value = number_format($r['SELL_MM_SALES_S_AMT']) . "원 ~ " . number_format($r['SELL_MM_SALES_E_AMT']) . "원 ( " . $r['PT_CLAS_SALES_RATE'] . "% )";
                break;
            case "13":
                $return_value = number_format($r['PT_CLAS_MM_SALES_S_AMT']) . "원 ~ " . number_format($r['PT_CLAS_MM_SALES_E_AMT']) . "원 ( " . $r['PT_CLAS_SALES_RATE'] . "% )";
                break;
            case "21":
                $return_value = number_format($r['SELL_MM_SALES_S_AMT']) . "원 ~ " . number_format($r['SELL_MM_SALES_E_AMT']) . "원 ( " . number_format($r['SARLY_AMT']) . "원 )";
                break;
            case "22":
                $return_value = number_format($r['PT_CLAS_MM_SALES_S_AMT']) . "원 ~ " . number_format($r['PT_CLAS_MM_SALES_E_AMT']) . "원 ( " . number_format($r['SARLY_AMT']) . "원 )";
                break;
            case "31":
                $return_value = number_format($r['PT_CLAS_S_CNT']) . "회 ~ " . number_format($r['PT_CLAS_E_CNT']) . "회 ( " . number_format($r['CLAS_1TM_AMT']) . "원 )";
                break;
            case "32":
                $return_value = number_format($r['GX_CLAS_S_CNT']) . "회 ~ " . number_format($r['GX_CLAS_E_CNT']) . "회 ( " . number_format($r['CLAS_1TM_AMT']) . "원 )";
                break;
        }
        return $return_value;
    }
    
    private function disp_text_pmt_mthd($mthd)
    {
        $return_value = "";
        switch($mthd)
        {
            case "11":
                $return_value = "판매매출액 (판매매출 요율%)";
                break;
            case "12":
                $return_value = "판매매출액 (PT수업매출 요율%)";
                break;
            case "13":
                $return_value = "PT수업매출액 (PT수업매출 요율%)";
                break;
            case "21":
                $return_value = "판매매출액 (수당금액)";
                break;
            case "22":
                $return_value = "수업매출액 (수당금액)";
                break;
            case "31":
                $return_value = "수업횟수 (수업1회금액)";
                break;
            case "32":
                $return_value = "GX수업횟수 (수업1회금액)";
                break;
        }
        
        return $return_value;
        
    }
    
    
    
    
    
    
    
    
    
    
    
}