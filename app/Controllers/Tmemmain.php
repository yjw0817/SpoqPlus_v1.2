<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\MemModel;
use App\Libraries\Ama_sno;
use App\Models\EventModel;
use App\Models\SarlyModel;
use App\Models\AnnuModel;
use App\Models\CateModel;

class Tmemmain extends MainTchrController
{
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 강사관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 아이디 중복 여부를 체크한다.
     */
    public function ajax_id_chk()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $model = new MemModel();
        
        $mdata['mem_id'] = $postVar['mem_id'];
        $id_count = $model->id_chk($mdata);
        
        if ($id_count == 0)
        {
            $return_json['result'] = 'true';
        } else 
        {
            $return_json['result'] = 'false';
        }
        
        return json_encode($return_json);
    }
    
    /**
     * 체크인 번호(mem_id) 자동 생성 AJAX
     * 전화번호를 받아서 사용 가능한 체크인 번호를 반환
     */
    public function ajax_generate_mem_id()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $model = new MemModel();
        
        // 전화번호가 없으면 에러 반환
        if (empty($postVar['phone_number'])) {
            return json_encode(['result' => 'false', 'msg' => '전화번호를 입력해주세요.']);
        }
        
        // 전화번호 숫자만 추출
        $phone_number = put_num($postVar['phone_number']);
        
        // 11자리 미만이면 에러 반환
        if (strlen($phone_number) < 11) {
            return json_encode(['result' => 'false', 'msg' => '올바른 전화번호를 입력해주세요.']);
        }
        
        // comp_cd, bcoff_cd 가져오기
        $comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
        $bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        // 전화번호 중복 체크
        $is_duplicate = $model->check_phone_duplicate($phone_number, $comp_cd, $bcoff_cd);
        
        if ($is_duplicate) {
            return json_encode([
                'result' => 'false',
                'is_duplicate' => true,
                'msg' => '이미 등록된 전화번호입니다.'
            ]);
        }
        
        // 체크인 번호 생성
        $mem_id = $model->generate_mem_id($phone_number, $comp_cd, $bcoff_cd);
        
        return json_encode([
            'result' => 'true',
            'mem_id' => $mem_id,
            'is_duplicate' => false,
            'message' => "사용 가능한 체크인 번호: {$mem_id}"
        ]);
    }
    
    
    /**
     * 강사관리
     */
    public function tchr_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $boardPage = new Ama_board($initVar);
        $model = new MemModel();
        
        $totalCount  = $model->list_tchr_count($boardPage->getVal());
        $tchr_list = $model->list_tchr($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; // 강사명
        if ( !isset($searchVal['sposn']) ) $searchVal['sposn'] = ''; // 강사직책
        if ( !isset($searchVal['sctrct']) ) $searchVal['sctrct'] = ''; // 계약형태
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['tchr_list'] = $tchr_list;
        $this->viewPage('/tchr/tmemmain/tchr_manage',$data);
    }
    
    /**
     * 강사 검색 처리 [ ajax ]
     */
    public function ajax_tchr_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $boardPage = new Ama_board($initVar);
        $model = new MemModel();
        
        $totalCount = $model->list_tchr_count($boardPage->getVal());
        $tchr_list = $model->list_tchr($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        foreach($tchr_list as $r) {
            $bg_class = "";
            if ($r['USE_YN'] == "N") $bg_class='bg-gray';
            
            $html .= '<tr class="' . $bg_class . '">';
            $html .= '<td class="text-center">' . $searchVal['listCount'] . '</td>';
            $html .= '<td>';
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo" ';
                $html .= 'id="preview_mem_photo_' . $r['MEM_SNO'] . '" ';
                $html .= 'src="' . (isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '') . '" ';
                $html .= 'alt="강사사진" style="cursor: pointer;" ';
                $html .= 'onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '') . '\')" ';
                $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            $html .= $r['MEM_NM'];
            $html .= '</td>';
            $html .= '<td>' . $r['MEM_ID'] . '</td>';
            $html .= '<td>' . disp_phone($r['MEM_TELNO']) . '</td>';
            $html .= '<td>' . disp_birth($r['BTHDAY']) . '</td>';
            $html .= '<td>' . $sDef['MEM_GENDR'][$r['MEM_GENDR']] . '</td>';
            $html .= '<td>' . $sDef['TCHR_POSN'][$r['TCHR_POSN']] . '</td>';
            $html .= '<td>' . $sDef['CTRCT_TYPE'][$r['CTRCT_TYPE']] . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '<td>';
            if ($r['USE_YN'] == "Y") {
                $html .= '<button type="button" class="btn btn-info btn-xs" onclick="annu_set(\'' . $r['MEM_SNO'] . '\');">연차설정</button> ';
                $html .= '<button type="button" class="btn btn-warning btn-xs" onclick="tinfo_modify(\'' . $r['MEM_SNO'] . '\');">정보수정</button> ';
                $html .= '<button type="button" class="btn btn-danger btn-xs" onclick="sece_confirm(\'' . $r['MEM_ID'] . '\');">퇴사처리</button>';
            } else {
                $html .= '퇴사일 ( ' . $r['END_DATETM'] . ' )';
            }
            $html .= '</td>';
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        if (empty($tchr_list)) {
            $html .= '<tr><td colspan="10" class="text-center">검색된 강사가 없습니다.</td></tr>';
        }
        
        // 페이저 HTML
        $pager = $boardPage->getPager($totalCount);
        
        $result['result'] = 'true';
        $result['html'] = $html;
        $result['pager'] = $pager;
        $result['totalCount'] = $totalCount;
        
        echo json_encode($result);
    }
    
    /**
     * 강사 등록 처리 [ ajax ]
     */
    public function ajax_tchr_insert_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new MemModel();
        $nn_now = new Time('now');
        
        // ===========================================================================
        // 전달받기
        // ===========================================================================
        /*
         * mem_id : 회원 아이디
         * mem_pwd : 회원 비밀번호
         * mem_nm : 회원 명
         * bthday : 생년월일
         * mem_gendr : 성별
         * mem_telno : 전화번호
         * mem_addr : 주소
         */
        $postVar = $this->request->getPost();
        
        // ===========================================================================
        // Model Data Set & Data Query Return
        // ===========================================================================
        
        $amasno = new Ama_sno();
        $mem_sno = $amasno->create_mem_sno();
        
        $mdata['mem_sno']		= $mem_sno['mem_sno'];
        $mdata['mem_id']		= $postVar['mem_id'];
        $mdata['mem_pwd']		= $this->enc_pass($postVar['mem_pwd']);
        $mdata['mem_nm']		= $postVar['mem_nm'];
        $mdata['qr_cd']			= "";
        $mdata['bthday']		= put_num($postVar['bthday']); // 숫자만
        $mdata['mem_gendr']		= $postVar['mem_gendr'];
        $mdata['mem_telno']		= put_num($postVar['mem_telno']); // 숫자만
        
        $re_phone_num = $this->enc_phone(put_num($postVar['mem_telno']));
        $mdata['mem_telno_enc'] = $re_phone_num['enc'];
        $mdata['mem_telno_mask'] = $re_phone_num['mask'];
        $mdata['mem_telno_short'] = $re_phone_num['short'];
        $mdata['mem_telno_enc2'] = $re_phone_num['enc2'];
        
        $mdata['mem_addr']		= $postVar['mem_addr'];
        $mdata['mem_main_img']	= "";
        $mdata['mem_thumb_img']	= "";
        $mdata['mem_dv']		= "T";
        
        $mdata['set_comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['set_bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $mdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $mdata['cre_datetm'] = $nn_now;
        $mdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $mdata['mod_datetm'] = $nn_now;
        
        $insert_mem_main_info = $model->insert_mem_main_info($mdata);
        
        $mdatad['mem_sno']			= $mem_sno['mem_sno'];
        $mdatad['comp_cd']			= $this->SpoQCahce->getCacheVar('comp_cd');
        $mdatad['bcoff_cd']			= $this->SpoQCahce->getCacheVar('bcoff_cd');
        $mdatad['mem_id']			= $postVar['mem_id'];
        $mdatad['mem_nm']			= $postVar['mem_nm'];
        $mdatad['mem_stat']			= "00";
        $mdatad['qr_cd']			= "";
        
        $mdatad['bthday']			= put_num($postVar['bthday']);
        $mdatad['mem_gendr']		= $postVar['mem_gendr'];
        
        $mdatad['mem_telno']		= put_num($postVar['mem_telno']);
        $mdatad['mem_telno_enc'] = $re_phone_num['enc'];
        $mdatad['mem_telno_mask'] = $re_phone_num['mask'];
        $mdatad['mem_telno_short'] = $re_phone_num['short'];
        $mdatad['mem_telno_enc2'] = $re_phone_num['enc2'];
        
        $mdatad['mem_addr']			= $postVar['mem_addr'];
        $mdatad['mem_main_img']		= "";
        $mdatad['mem_thumb_img']	= "";
        $mdatad['tchr_posn']		= $postVar['tchr_posn'];
        $mdatad['ctrct_type']		= $postVar['ctrct_type'];
        $mdatad['tchr_simp_pwd']	= "123456";
        
        $mdatad['jon_place']		= "01";
        $mdatad['jon_datetm']		= $nn_now;
        $mdatad['reg_place']		= "";
        $mdatad['reg_datetm']		= "";
        
        $mdatad['mem_dv']			= "T";
        $mdatad['cre_id'] 			= $this->SpoQCahce->getCacheVar('user_id');
        $mdatad['cre_datetm'] 		= $nn_now;
        $mdatad['mod_id'] 			= $this->SpoQCahce->getCacheVar('user_id');
        $mdatad['mod_datetm'] 		= $nn_now;
        
        $insert_mem_info_detl_tbl = $model->insert_mem_info_detl_tbl($mdatad);
        
        $mdatad['tchr_info_detl_hist_sno'] = $mem_sno['mem_sno_hist'];
        $mdatad['tchr_info_detl_sno'] = $mem_sno['mem_sno'];
        
        $insert_tchr_info_detl_hist = $model->insert_tchr_info_detl_hist($mdatad);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 강사정보 수정하기 불러오기
     */
    public function ajax_tchr_modify()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $model = new MemModel();
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        $tdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $tdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $tdata['mem_sno'] = $postVar['mem_sno'];
        
        $get_tinfo = $model->get_tmem_info_mem_sno($tdata);
        
        $return_json['tinfo'] = $get_tinfo[0];
        $return_json['msg'] = '강사정보 불러오기 성공';
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 강사정보 수정하기 처리
     */
    public function ajax_tchr_modify_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        $model = new MemModel();
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        $mdata['mem_sno']		= $postVar['mem_sno'];
        $mdata['mem_nm']		= $postVar['mem_nm'];
        $mdata['bthday']		= put_num($postVar['bthday']); // 숫자만
        $mdata['mem_gendr']		= $postVar['mem_gendr'];
        $mdata['mem_telno']		= put_num($postVar['mem_telno']); // 숫자만
        
        $re_phone_num = $this->enc_phone(put_num($postVar['mem_telno']));
        $mdata['mem_telno_enc'] = $re_phone_num['enc'];
        $mdata['mem_telno_mask'] = $re_phone_num['mask'];
        $mdata['mem_telno_short'] = $re_phone_num['short'];
        $mdata['mem_telno_enc2'] = $re_phone_num['enc2'];
        
        $mdata['mem_addr']		= $postVar['mem_addr'];
        $mdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $mdata['mod_datetm'] = $nn_now;
        
        $modify_mem_main_info = $model->update_mem_main_info($mdata);
        
        
        $mdatad['mem_sno']			= $postVar['mem_sno'];
        $mdatad['mem_nm']			= $postVar['mem_nm'];
        $mdatad['bthday']			= put_num($postVar['bthday']);
        $mdatad['mem_gendr']		= $postVar['mem_gendr'];
        $mdatad['mem_telno']		= put_num($postVar['mem_telno']);
        $re_phone_num = $this->enc_phone(put_num($postVar['mem_telno']));
        $mdatad['mem_telno_enc'] = $re_phone_num['enc'];
        $mdatad['mem_telno_mask'] = $re_phone_num['mask'];
        $mdatad['mem_telno_short'] = $re_phone_num['short'];
        $mdatad['mem_telno_enc2'] = $re_phone_num['enc2'];
        
        $mdatad['mem_addr']			= $postVar['mem_addr'];
        $mdatad['tchr_posn']		= $postVar['tchr_posn'];
        $mdatad['ctrct_type']		= $postVar['ctrct_type'];
        $mdatad['mod_id'] 			= $this->SpoQCahce->getCacheVar('user_id');
        $mdatad['mod_datetm'] 		= $nn_now;
        
        $modify_mem_info_detl_tbl = $model->update_tmem_info_detl_tbl($mdatad);
        
        
        if ($postVar['mem_pwd'])
        {
            $pdata['mem_pwd'] = $this->enc_pass($postVar['mem_pwd']);
            $pdata['mem_sno'] = $postVar['mem_sno'];
            $pdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $pdata['mod_datetm'] = $nn_now;
            $model->update_mem_main_info_pwd($pdata);
        }
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 퇴사진행하기
     * @param string $mem_id
     */
    public function tchr_sece($mem_id='')
    {
        if ($mem_id == '')
        {
            scriptAlert('잘못된 접근입니다.');
            scriptLocation('/tlogin/logout');
            exit();
        }
        
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '강사관리',
            'nav' => array('직원/회원설정' => '' , '강사관리' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $eventModel = new EventModel();
        $memModel = new MemModel();
        
        // 구매상품 정보를 가져온다.
        $edata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $edata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $edata['stchr_id'] = $mem_id;
        
        $get_tchr_list = $memModel->get_list_tchr($edata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
        $tchr_list[$tchr_i] = $t;
        $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
        $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
        $tchr_i++;
        endforeach;
        
        $event_list = $eventModel->list_buy_event_stchr_id_sece($edata);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['mem_id'] = $mem_id;
        $data['view']['tchr_list'] = $tchr_list; // 강사 리스트
        $data['view']['event_list'] = $event_list;
        $this->viewPage('/tchr/tmemmain/tchr_sece',$data);
    }
    
    
    /**
     * ajax
     * 최종 퇴사 처리하기
     */
    public function ajax_tchr_sece_proc()
    {
        // mem_id
        // sece_date
        $ajax_return_yn = "Y";
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        $model = new MemModel();
        // 강사 아이디로 온것을 한번 더 검사하여 MEM_SNO 화 해서 처리함.
        
        $tdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $tdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $tdata['mem_id'] = $postVar['mem_id'];
        $tchr_info = $model->get_list_tchr_for_id($tdata);
        
        if (count($tchr_info) == 0) {
            $ajax_return_yn = "N";
        } else 
        {
            // mem_info_detl_tbl 을 업데이트함
            // END_DATETM : sec_date . " 01:01:01";
            // MOD_ID : $_SESSION['user_id'];
            // MOD_DATETM : $nn_now
            // USE_YN : N
            
            $mdData['comp_cd'] = $tdata['comp_cd'];
            $mdData['bcoff_cd'] = $tdata['bcoff_cd'];
            $mdData['mem_sno'] = $tchr_info[0]['MEM_SNO'];
            $mdData['end_datetm'] = $postVar['sece_date'] . " 01:01:01";
            $mdData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $mdData['mod_datetm'] = $nn_now;
            $mdData['use_yn'] = "N";
            $model->update_tchr_sece_info_detal($mdData);
            
            // mem_main_info_tbl 을 업데이트함
            // MOD_ID : $_SESSION['user_id'];
            // MOD_DATETM : $nn_now
            // CONN_POSS_YN : N
            // USE_YN : N
            // SECE_DATETM : sec_date . " 01:01:01";
            
            $mmData['mem_sno'] = $tchr_info[0]['MEM_SNO'];
            $mmData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $mmData['mod_datetm'] = $nn_now;
            $mmData['conn_poss_yn'] = "N";
            $mmData['use_yn'] = "N";
            $mmData['sece_datetm'] = $postVar['sece_date'] . " 01:01:01";
            $model->update_tchr_sece_info($mmData);
            
        }
        
        if ($ajax_return_yn == "Y")
        {
            $return_json['result'] = 'true';
        } else 
        {
            $return_json['result'] = 'false';
        }
        
        return json_encode($return_json);
        
    }
    
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 회원관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 회원관리
     */
    public function mem_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    	
        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $boardPage = new Ama_board($initVar);
        $model = new MemModel();
        $cateModel = new CateModel(); // EventModel -> CateModel
        
        $totalCount  = $model->list_mem_count($boardPage->getVal());
        $mem_list = $model->list_mem($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['smst']) ) $searchVal['smst'] = ''; // 회원상태
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; // 회원명
        if ( !isset($searchVal['stel']) ) $searchVal['stel'] = ''; // 전화번호
        if ( !isset($searchVal['sjp']) ) $searchVal['sjp'] = ''; // 가입장소
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색조건
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 점색시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색종료일
        if ( !isset($searchVal['search_cate1']) ) $searchVal['search_cate1'] = '';
        if ( !isset($searchVal['search_cate2']) ) $searchVal['search_cate2'] = '';
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);

        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $eventModel = new EventModel();
        $sch_cate1 = $eventModel->sarly_cate_1rd($mdata);
        $sch_cate2 = [];
        if (!empty($searchVal['search_cate1'])) {
            $mdata['1rd_cate_cd'] = $searchVal['search_cate1'];
            $sch_cate2 = $eventModel->sarly_cate_2rd($mdata); // sarly_cate_2rd_by_1rd -> sarly_cate_2rd
        }

        $data['view']['sch_cate1'] = $sch_cate1;
        $data['view']['sch_cate2'] = $sch_cate2;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['mem_list'] = $mem_list;
        $this->viewPage('/tchr/tmemmain/mem_manage',$data);
    }
    
    /**
     * 회원 검색 처리 [ ajax ]
     */
    public function ajax_mem_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $boardPage = new Ama_board($initVar);
        $model = new MemModel();
        
        $totalCount = $model->list_mem_count($boardPage->getVal());
        $mem_list = $model->list_mem($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        $listCount = $searchVal['listCount'];
        
        foreach($mem_list as $r) {
            $backColor = "";
            if ($r['MEM_STAT'] == "00") $backColor = "#cfecf0"; // 가입회원
            if ($r['MEM_STAT'] == "90") $backColor = "#fee6ef"; // 종료회원
            if ($r['MEM_STAT'] == "99") $backColor = "#fdcce1"; // 탈퇴회원
            
            $html .= '<tr style="background-color: ' . $backColor . ' !important;">';
            $html .= '<td class="text-center">' . $listCount . '</td>';
            $html .= '<td>' . $sDef['MEM_STAT'][$r['MEM_STAT']] . '</td>';
            $html .= '<td>';
            $html .= '<img class="preview_mem_photo" id="preview_mem_photo" src="' . $r['MEM_THUMB_IMG'] . '" ';
            $html .= 'alt="회원사진" style="border-radius: 50%; cursor: pointer;" ';
            $html .= 'onclick="showFullPhoto(\'' . $r['MEM_MAIN_IMG'] . '\')" ';
            $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $r['MEM_GENDR'] . '.png\';">';
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= $r['MEM_NM'];
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td>' . $r['MEM_ID'] . '</td>';
            $html .= '<td>' . disp_phone($r['MEM_TELNO']) . '</td>';
            $html .= '<td>' . disp_birth($r['BTHDAY']) . '</td>';
            $html .= '<td>' . $sDef['MEM_GENDR'][$r['MEM_GENDR']] . '</td>';
            $html .= '<td>' . $r['MEM_ADDR'] . '</td>';
            $html .= '<td>' . $sDef['JON_PLACE'][$r['JON_PLACE']] . '</td>';
            $html .= '<td>' . substr($r['JON_DATETM'],0,16) . '</td>';
            $html .= '<td>' . substr($r['REG_DATETM'],0,16) . '</td>';
            $html .= '<td>' . substr($r['RE_REG_DATETM'],0,16) . '</td>';
            $html .= '<td>';
            $html .= substr($r['END_DATETM'],0,16);
            $html .= '<input type="hidden" id="men_sno" value="' . $r['MEM_SNO'] . '">';
            $html .= '</td>';
            $html .= '</tr>';
            
            $listCount--;
        }
        
        if (empty($mem_list)) {
            $html .= '<tr><td colspan="13" class="text-center">검색된 회원이 없습니다.</td></tr>';
        }
        
        // 페이저 HTML
        $pager = $boardPage->getPager($totalCount);
        
        $result['result'] = 'true';
        $result['html'] = $html;
        $result['pager'] = $pager;
        $result['totalCount'] = $totalCount;
        
        echo json_encode($result);
    }
    
    /**
     * 회원 등록 처리 [ ajax ]
     */
    public function ajax_mem_insert_proc()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$model = new MemModel();
    	$nn_now = new Time('now');
    	
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * mem_id : 회원 아이디
    	 * mem_pwd : 회원 비밀번호
    	 * mem_nm : 회원 명
    	 * bthday : 생년월일
    	 * mem_gendr : 성별
    	 * mem_telno : 전화번호
    	 * mem_addr : 주소
    	 */
    	$postVar = $this->request->getPost();
    	
    	// ===========================================================================
    	// Model Data Set & Data Query Return
    	// ===========================================================================
    	
    	$amasno = new Ama_sno(); 
    	$mem_sno = $amasno->create_mem_sno();
    	
    	$mdata['mem_sno']		= $mem_sno['mem_sno'];
    	
    	// 체크인 번호 자동 생성 (전화번호 기반)
    	$phone_number = put_num($postVar['mem_telno']);
    	$comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
    	$bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	
    	// 등록 전 전화번호 중복 체크
    	if ($model->check_phone_duplicate($phone_number, $comp_cd, $bcoff_cd)) {
    		$return_json['result'] = 'false';
    		$return_json['is_duplicate'] = true;
    		$return_json['msg'] = '이미 등록된 전화번호입니다.';
    		return json_encode($return_json);
    	}
    	
    	$generated_mem_id = $model->generate_mem_id($phone_number, $comp_cd, $bcoff_cd);
    	
    	$mdata['mem_id']		= $generated_mem_id;
    	// .env에서 기본 비밀번호 가져오기
    	$default_password = getenv('DEFAULT_MEMBER_PASSWORD') ?: 'SpoQ2025!@#';
    	$mdata['mem_pwd']		= $this->enc_pass($generated_mem_id . '5898'); // 체크인번호 + 5898
    	$mdata['mem_nm']		= $postVar['new_mem_nm'];
    	$mdata['qr_cd']			= "";
    	$mdata['bthday']		= put_num($postVar['bthday']);
    	$mdata['mem_gendr']		= $postVar['mem_gendr'];
    	$mdata['mem_telno']		= put_num($postVar['mem_telno']);
    	
    	$re_phone_num = $this->enc_phone(put_num($postVar['mem_telno']));
    	$mdata['mem_telno_enc'] = $re_phone_num['enc'];
    	$mdata['mem_telno_mask'] = $re_phone_num['mask'];
    	$mdata['mem_telno_short'] = $re_phone_num['short'];
    	$mdata['mem_telno_enc2'] = $re_phone_num['enc2'];
    	
    	$mdata['mem_addr']		= $postVar['mem_addr'];
    	$mdata['mem_email']		= $postVar['mem_email'] ?? '';  // 이메일 추가
    	$mdata['mem_main_img']	= "";
    	$mdata['mem_thumb_img']	= "";
    	$mdata['mem_dv']		= "M";
    	
    	// 사진 데이터 처리
    	if (!empty($postVar['captured_photo'])) {
    		// Base64 이미지 데이터 처리
    		$imageData = $postVar['captured_photo'];
    		$imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    		$imageData = str_replace(' ', '+', $imageData);
    		$imageData = base64_decode($imageData);
    		
    		// 파일명 생성
    		$fileName = $mem_sno['mem_sno'] . '_' . date('YmdHis') . '.jpg';
    		$uploadPath = FCPATH . 'uploads/member_photos/';
    		
    		// 디렉토리가 없으면 생성
    		if (!is_dir($uploadPath)) {
    			mkdir($uploadPath, 0777, true);
    		}
    		
    		// 파일 저장
    		if (file_put_contents($uploadPath . $fileName, $imageData)) {
    			$mdata['mem_main_img'] = '/uploads/member_photos/' . $fileName;
    			$mdata['mem_thumb_img'] = '/uploads/member_photos/' . $fileName;
    		}
    	}
    	
    	// 얼굴 인식 데이터 저장
    	$mdata['face_encoding_data'] = $postVar['face_encoding_data'] ?? '';
    	$mdata['glasses_detected'] = $postVar['glasses_detected'] ?? '0';
    	$mdata['quality_score'] = $postVar['quality_score'] ?? '0';
    	
    	$mdata['set_comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$mdata['set_bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	
    	$mdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	$mdata['cre_datetm'] = $nn_now;
    	$mdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	$mdata['mod_datetm'] = $nn_now;
    	
    	$insert_mem_main_info = $model->insert_mem_main_info($mdata);
    	
    	$mdatad['mem_sno']			= $mem_sno['mem_sno'];
    	$mdatad['comp_cd']			= $this->SpoQCahce->getCacheVar('comp_cd');
    	$mdatad['bcoff_cd']			= $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$mdatad['mem_id']			= $generated_mem_id;
    	$mdatad['mem_nm']			= $postVar['new_mem_nm'];
    	$mdatad['mem_stat']			= "00";
    	$mdatad['qr_cd']			= "";
    	
    	$mdatad['bthday']			= put_num($postVar['bthday']);
    	$mdatad['mem_gendr']		= $postVar['mem_gendr'];
    	$mdatad['mem_telno']		= put_num($postVar['mem_telno']);
    	$mdatad['mem_telno_enc'] = $re_phone_num['enc'];
    	$mdatad['mem_telno_mask'] = $re_phone_num['mask'];
    	$mdatad['mem_telno_short'] = $re_phone_num['short'];
    	$mdatad['mem_telno_enc2'] = $re_phone_num['enc2'];
    	
    	$mdatad['mem_addr']			= $postVar['mem_addr'];
    	$mdatad['mem_email']		= $postVar['mem_email'] ?? '';  // 이메일 추가
    	$mdatad['mem_main_img']		= $mdata['mem_main_img'];  // 위에서 처리한 이미지 경로 사용
    	$mdatad['mem_thumb_img']	= $mdata['mem_thumb_img'];  // 위에서 처리한 이미지 경로 사용
    	$mdatad['tchr_posn']		= "";
    	$mdatad['ctrct_type']		= "";
    	$mdatad['tchr_simp_pwd']	= "";
    	
    	// 얼굴 인식 데이터 저장
    	$mdatad['face_encoding_data'] = $postVar['face_encoding_data'] ?? '';
    	$mdatad['glasses_detected'] = $postVar['glasses_detected'] ?? '0';
    	$mdatad['quality_score'] = $postVar['quality_score'] ?? '0';
    	
    	$mdatad['jon_place']		= "01";
    	$mdatad['jon_datetm']		= $nn_now;
    	$mdatad['reg_place']		= "";
    	$mdatad['reg_datetm']		= "";
    	
    	$mdatad['mem_dv']			= "M";
    	$mdatad['cre_id'] 			= $this->SpoQCahce->getCacheVar('user_id');
    	$mdatad['cre_datetm'] 		= $nn_now;
    	$mdatad['mod_id'] 			= $this->SpoQCahce->getCacheVar('user_id');
    	$mdatad['mod_datetm'] 		= $nn_now;
    	
    	$insert_mem_info_detl_tbl = $model->insert_mem_info_detl_tbl($mdatad);
    	
    	$mdatad['mem_info_detl_hist_sno'] = $mem_sno['mem_sno_hist'];
    	
    	$insert_mem_info_detl_hist_tbl = $model->insert_mem_info_detl_hist_tbl($mdatad);
    	
    	// 🔍 얼굴 인식 데이터 저장 (info_mem2와 동일한 방식)
    	if (!empty($postVar['face_encoding_data'])) {
    		log_message('info', '🔍 신규 회원 얼굴 데이터 처리 시작');
    		log_message('info', '📊 받은 얼굴 데이터: ' . $postVar['face_encoding_data']);
    		log_message('info', '👓 안경 검출: ' . ($postVar['glasses_detected'] ?? '미제공'));
    		log_message('info', '⭐ 품질 점수: ' . ($postVar['quality_score'] ?? '미제공'));
    		
    		$faceModel = new \App\Models\FaceRecognitionModel();
    		
    		try {
    			$faceData = json_decode($postVar['face_encoding_data'], true);
    			log_message('info', '📝 JSON 디코딩 결과: ' . ($faceData ? 'SUCCESS' : 'FAILED'));
    			
    			if ($faceData && isset($faceData['face_encoding'])) {
    				log_message('info', '✅ face_encoding 필드 존재');
    				log_message('info', '📏 face_encoding 타입: ' . gettype($faceData['face_encoding']));
    				log_message('info', '📏 face_encoding 크기: ' . (is_array($faceData['face_encoding']) ? count($faceData['face_encoding']) : 'NOT_ARRAY'));
    				
    				// Python 서버에 얼굴 등록
    				if (!empty($postVar['captured_photo'])) {
    					$faceTest = new \App\Controllers\FaceTest();
    					$comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
    					$bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');
    					
    					// Base64 이미지 준비
    					$imageBase64 = $postVar['captured_photo'];
    					if (strpos($imageBase64, 'data:image') === 0) {
    						$imageBase64 = substr($imageBase64, strpos($imageBase64, ',') + 1);
    					}
    					
    					// Python 서버에 등록 요청
    					$pythonData = [
    						'member_id' => $mem_sno['mem_sno'],
    						'image' => $imageBase64,
    						'param1' => $comp_cd,
    						'param2' => $bcoff_cd
    					];
    					
    					$pythonResult = $faceTest->callPythonAPI('/api/face/register', 'POST', $pythonData);
    					log_message('info', '🐍 Python 서버 얼굴 등록 결과: ' . json_encode($pythonResult));
    				}
    				
    				// 얼굴 데이터 저장
    				$dbData = [
    					'mem_sno' => $mem_sno['mem_sno'],
    					'face_encoding' => json_encode($faceData['face_encoding']),
    					'glasses_detected' => $postVar['glasses_detected'] ?? 0,
    					'quality_score' => $postVar['quality_score'] ?? 0.85,
    					'security_level' => $faceData['security_level'] ?? 'medium',
    					'liveness_score' => $faceData['liveness_score'] ?? 0.95,
    					'notes' => 'Registered via member registration'
    				];
    				
    				$registrationResult = $faceModel->registerFace($dbData);
    				if ($registrationResult) {
    					log_message('info', '✅ 신규 회원 얼굴 데이터 저장 성공');
    				} else {
    					log_message('error', '❌ 신규 회원 얼굴 데이터 저장 실패');
    				}
    			}
    		} catch (\Exception $e) {
    			log_message('error', '❌ 신규 회원 얼굴 처리 중 오류: ' . $e->getMessage());
    		}
    	}
    	
//     	var_dump($insert_mem_main_info);
    	
    	// ===========================================================================
    	// Processs
    	// ===========================================================================
    	
    	$return_json['result'] = 'true';
    	$return_json['data'] = $insert_mem_info_detl_hist_tbl;
    	$return_json['mem_sno'] = $mem_sno['mem_sno'];
    	return json_encode($return_json);
    }

    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 강사 연차관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 강사 연차관리
     */
    public function tchr_annu_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelAnnu = new AnnuModel();
        
        $totalCount  = $modelAnnu->list_tchr_annu_grant_mgmt_hist_count($boardPage->getVal());
        $appct_annu_list = $modelAnnu->list_tchr_annu_grant_mgmt_hist($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // as : 연차 사용 가능 시작일
        // ae : 연차 사용 가능 종료일
        // 00 : 신청일시
        // 01 : 승인일시
        // 90 : 반려일시
        // 99 : 취소일시
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; // 강사 신청자명
        if ( !isset($searchVal['sappstat']) ) $searchVal['sappstat'] = ''; // 연차 신청상태
        
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['tchr_annu_appct_list'] = $appct_annu_list;
        $this->viewPage('/tchr/tmemmain/tchr_annu_manage',$data);
    }
    
    /**
     * 강사 연차관리 AJAX 검색
     */
    public function ajax_tchr_annu_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelAnnu = new AnnuModel();
        
        $totalCount = $modelAnnu->list_tchr_annu_grant_mgmt_hist_count($boardPage->getVal());
        $appct_annu_list = $modelAnnu->list_tchr_annu_grant_mgmt_hist($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        $listCount = $searchVal['listCount'];
        
        if (empty($appct_annu_list)) {
            $html = '<tr><td colspan="11" class="text-center">검색 결과가 없습니다.</td></tr>';
        } else {
            foreach($appct_annu_list as $r) {
                $html .= '<tr>';
                $html .= '<td class="text-center">' . $listCount . '</td>';
                $html .= '<td>';
                
                // 회원 사진 처리
                if (isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                    $html .= '<img class="preview_mem_photo" ';
                    $html .= 'id="preview_mem_photo_' . $r['MEM_SNO'] . '" ';
                    $html .= 'src="' . (isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '') . '" ';
                    $html .= 'alt="신청자사진" style="cursor: pointer;" ';
                    $html .= 'onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '') . '\')" ';
                    $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
                }
                $html .= $r['MEM_NM'];
                $html .= '</td>';
                
                $html .= '<td>' . $sDef['ANNU_APPV_STAT'][$r['ANNU_APPV_STAT']] . '</td>';
                $html .= '<td>' . $r['ANNU_APPCT_S_DATE'] . '</td>';
                $html .= '<td>' . $r['ANNU_APPCT_E_DATE'] . '</td>';
                $html .= '<td>' . $r['ANNU_USE_DAY'] . '</td>';
                $html .= '<td>' . $r['ANNU_APPV_DATETM'] . '</td>';
                $html .= '<td>' . $r['ANNU_APPCT_DATETM'] . '</td>';
                $html .= '<td>' . $r['ANNU_REFUS_DATETM'] . '</td>';
                $html .= '<td>' . $r['ANNU_CANCEL_DATETM'] . '</td>';
                
                $html .= '<td style="text-align:center">';
                if ($r['ANNU_APPV_STAT'] == "00") {
                    $html .= '<button type="button" class="btn btn-success btn-xs" onclick="annu_appct(\'' . $r['ANNU_USE_MGMT_HIST_SNO'] . '\',\'' . $r['ANNU_GRANT_MGMT_SNO'] . '\',\'' . $r['ANNU_USE_DAY'] . '\');">승인하기</button>';
                    $html .= '<button type="button" class="btn btn-warning btn-xs" onclick="annu_refus(\'' . $r['ANNU_USE_MGMT_HIST_SNO'] . '\');">반려하기</button>';
                }
                $html .= '</td>';
                $html .= '</tr>';
                
                $listCount--;
            }
        }
        
        // 페이저 생성
        $pager = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 응답 데이터 구성
        // ===========================================================================
        $response = [
            'result' => 'true',
            'html' => $html,
            'totalCount' => $totalCount,
            'pager' => $pager
        ];
        
        return json_encode($response);
    }
    
    /**
     * 연차 승인하기
     * @return string
     */
    public function ajax_tchr_annu_appct_proc()
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
        
        $cdata['annu_appv_stat'] = "01";
        $cdata['annu_appct_datetm'] = $nn_now;
        $cdata['annu_appv_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $cdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $cdata['mod_datetm'] = $nn_now;
        
        $modelAnnu->update_tchr_annu_use_mgmt_hist_appct($cdata);
        
        // 연차 셋팅 정보에 업데이트를 수행한다.
        $udata['annu_grant_mgmt_sno'] = $postVar['mgmt_sno'];
        $udata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $udata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $udata['use_day'] = $postVar['use_day'];
        $udata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $udata['mod_datetm'] = $nn_now;
        
        $modelAnnu->update_tchr_annu_use_mgmt_appct($udata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 연차 반려하기
     * @return string
     */
    public function ajax_tchr_annu_refus_proc()
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
        
        $cdata['annu_appv_stat'] = "90";
        $cdata['annu_refus_datetm'] = $nn_now;
        $cdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $cdata['mod_datetm'] = $nn_now;
        
        $modelAnnu->update_tchr_annu_use_mgmt_hist_refus($cdata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 강사 연차설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 강사 연차 설정
     */
    public function tchr_annu_setting($mem_sno='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '강사 연차관리',
            'nav' => array('직원/회원설정' => '' , '강사 연차설정' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $modelMem = new MemModel();
        $modelAnnu = new AnnuModel();
        
        $tdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $tdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $tdata['mem_sno'] = $mem_sno;
        $tchr_info = $modelMem->get_tmem_info_mem_sno($tdata);
        
        $tchr_annu_list = $modelAnnu->list_tchr_annu_grant_mgmt_memsno($tdata);
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['tchr_annu_list'] = $tchr_annu_list;
        $data['view']['tchr_info'] = $tchr_info[0];
        $this->viewPage('/tchr/tmemmain/tchr_annu_setting',$data);
    }
    
    /**
     * 강사 연차 설정 insert 처리
     */
    public function ajax_tchr_annu_setting_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $modelAnnu = new AnnuModel();
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        $adata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $adata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $adata['mem_sno'] = $postVar['mem_sno'];
        $adata['mem_id'] = $postVar['mem_id'];
        $adata['mem_nm'] = $postVar['mem_nm'];
        $adata['annu_grant_s_date'] = $postVar['annu_set_s_date'];
        $adata['annu_grant_e_date'] = $postVar['annu_set_e_date'];
        $adata['annu_grant_day'] = $postVar['annu_set_days'];
        $adata['annu_left_day'] = $postVar['annu_set_days'];
        $adata['annu_use_day'] = 0;
        $adata['revac_left_day'] = 0;
        $adata['revac_use_day'] = 0;
        
        $adata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $adata['cre_datetm'] = $nn_now;
        $adata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $adata['mod_datetm'] = $nn_now;
        
        $modelAnnu->insert_tchr_annu_grant_mgmt($adata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 강사 수당설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //

    public function ajax_sarly_del()
    {
        $sarlyModel = new SarlyModel();
        $postVar = $this->request->getPost();
        $postVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $postVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');

        $sarlyModel->delete_sarly_mgmt($postVar);
        $sarlyModel->delete_sub_sarly_mgmt_by_sarly_mgmt_sno($postVar);
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 강사 수당설정 (리스트)
     */
    public function tchr_salary_setting_list()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    	
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $sarlyModel = new SarlyModel();
        
        $totalCount  = $sarlyModel->list_sarly_mgmt_count($boardPage->getVal());
        $list_sarly = $sarlyModel->list_sarly_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ss : 적용시작일
        // se : 적용종료일
        // sc : 등록일
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; // 강사명
        if ( !isset($searchVal['spcond']) ) $searchVal['spcond'] = ''; // 지급조건
        if ( !isset($searchVal['spmthdnm']) ) $searchVal['spmthdnm'] = ''; // 지급방법
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['list_sarly'] = $list_sarly; // 강사 수당 설정 리스트
        $this->viewPage('/tchr/tmemmain/tchr_salary_setting_list',$data);
    }

    /**
     * 강사 수당설정 AJAX 검색
     */
    public function ajax_tchr_salary_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelSarly = new SarlyModel();
        
        $totalCount = $modelSarly->list_sarly_mgmt_count($boardPage->getVal());
        $list_sarly = $modelSarly->list_sarly_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        if (empty($list_sarly)) {
            $html = '<tr><td colspan="7" class="text-center">검색 결과가 없습니다.</td></tr>';
        } else {
            foreach($list_sarly as $r) {
                $html .= '<tr id="row_' . $r['SARLY_MGMT_SNO'] . '">';
                $html .= '<td>';
                
                // 강사 사진 처리
                if (isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                    $html .= '<img class="preview_mem_photo" ';
                    $html .= 'id="preview_mem_photo_' . $r['SARLY_MGMT_SNO'] . '" ';
                    $html .= 'src="' . (isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '') . '" ';
                    $html .= 'alt="강사사진" style="cursor: pointer;" ';
                    $html .= 'onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '') . '\')" ';
                    $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
                }
                $html .= $r['TCHR_NM'] . ' (' . $r['TCHR_ID'] . ')';
                $html .= '</td>';
                
                $html .= '<td>' . $r['SARLY_APLY_S_DATE'] . '</td>';
                $html .= '<td>' . $r['SARLY_APLY_E_DATE'] . '</td>';
                $html .= '<td>' . $sDef['SARLY_PMT_COND'][$r['SARLY_PMT_COND']] . '</td>';
                
                $html .= '<td>';
                $html .= $sDef['SARLY_PMT_MTHD_NAME'][$r['SARLY_PMT_MTHD']];
                if (isset($r['SUB_COUNT']) && intval($r['SUB_COUNT']) > 0) {
                    $html .= ' (수당설정 있음)';
                }
                $html .= '</td>';
                
                $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
                
                $html .= '<td style="text-align:center">';
                $html .= '<button type="button" class="btn btn-block btn-primary btn-xs" id="btn_sarly_mod" onclick="sarly_mod(\'' . $r['SARLY_MGMT_SNO'] . '\');">수정하기</button>';
                $html .= '<button type="button" class="btn btn-block btn-warning btn-xs" id="btn_sarly_mod" onclick="sarly_del(\'' . $r['SARLY_MGMT_SNO'] . '\');">삭제하기</button>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        }
        
        // 페이저 생성
        $pager = $boardPage->getPager($totalCount);
        $pager .= '<ul class="pagination pagination-sm m-0 float-right">';
        $pager .= '<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm" onclick="create_sarly();">등록하기</button></li>';
        $pager .= '</ul>';
        
        // ===========================================================================
        // 응답 데이터 구성
        // ===========================================================================
        $response = [
            'result' => 'true',
            'html' => $html,
            'pager' => $pager
        ];
        
        return json_encode($response);
    }

    /**
     * 상품만들기에서 대분류 선택시 그에 해당하는 이용권를 리턴한다.
     * @return string
     */
    public function ajax_get_cate2()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['1rd_cate_cd_arr'] = $postVar['cate1_selected'];
        $cate2 = $cateModel->get_2rd_list_by_1rd($cateData);
        
        $return_json['result'] = 'true';
        $return_json['cate2'] = $cate2;
        
        return json_encode($return_json);
    }


    /**
     * 강사 수당설정 (등록화면)
     */
    public function tchr_salary_setting($sarly_mgmt_sno='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '강사 수당설정',
            'nav' => array('직원/회원설정' => '' , '강사 수당설정' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        if ($sarly_mgmt_sno != '') :
            $sarlyModel = new SarlyModel();
            $getsData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $getsData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $getsData['sarly_mgmt_sno'] = $sarly_mgmt_sno;
            $get_sarly = $sarlyModel->get_sarly_mgmt($getsData);
            
            $get_sinfo = $get_sarly[0];
            
            $sinfo['SARLY_MGMT_SNO'] = $get_sinfo['SARLY_MGMT_SNO'];
            $sinfo['TCHR_ID'] = $get_sinfo['TCHR_ID'];
            $sinfo['SARLY_PMT_COND'] = $get_sinfo['SARLY_PMT_COND'];
            $sinfo['SARLY_PMT_MTHD'] = $get_sinfo['SARLY_PMT_MTHD'];
            $sinfo['VAT_YN'] = $get_sinfo['VAT_YN'];
            $sinfo['SARLY_APLY_ITEM_1'] = $get_sinfo['SARLY_APLY_ITEM_1'];
            $sinfo['SARLY_APLY_ITEM_2'] = $get_sinfo['SARLY_APLY_ITEM_2'];
            $sinfo['SARLY_APLY_S_DATE'] = $get_sinfo['SARLY_APLY_S_DATE'];
            $sinfo['SARLY_APLY_E_DATE'] = $get_sinfo['SARLY_APLY_E_DATE'];
            
            $sinfo_sub = $this->list_sub_sarly_mgmt($sinfo['SARLY_MGMT_SNO'], $sinfo['SARLY_PMT_MTHD']);
            
            $sarly_basic_amt = $sinfo_sub['sarly_basic_amt'];
            
        else :
            $sinfo['SARLY_MGMT_SNO'] = "";
            $sinfo['TCHR_ID'] = "";
            $sinfo['SARLY_PMT_COND'] = "";
            $sinfo['SARLY_PMT_MTHD'] = "";
            $sinfo['VAT_YN'] = "";
            $sinfo['SARLY_APLY_ITEM_1'] = "";
            $sinfo['SARLY_APLY_ITEM_2'] = "";
            $sinfo['SARLY_APLY_S_DATE'] = "";
            $sinfo['SARLY_APLY_E_DATE'] = "";
            
            $sinfo_sub['list_sub'] = array();
            $sinfo_sub['table_title'] = array();
            
            $sarly_basic_amt = "0";
        endif;
        
        $memModel = new MemModel();
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $get_tchr_list = $memModel->get_list_tchr($mdata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
            $tchr_list[$tchr_i] = $t;
            $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
            if ($t['USE_YN'] == "N") $tchr_list[$tchr_i]['TCHR_POSN_NM'] = "퇴사-".$tchr_list[$tchr_i]['TCHR_POSN_NM'];
            $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
            $tchr_i++;
        endforeach;
        
        // 대분류 사용 카테고리 목록
        $eventModel = new EventModel();
        
        $cateEvent['1rd'] = $eventModel->sarly_cate_1rd($mdata);
        $cateEvent['2rd'] = $eventModel->sarly_cate_2rd($mdata);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $data['view']['sarly_basic_amt'] = $sarly_basic_amt; // 기본급
        
        $data['view']['table_title'] = $sinfo_sub['table_title']; // 강사수당조건 설정 테이블 헤더
        $data['view']['sinfo_sub'] = $sinfo_sub['list_sub']; // 강사수당조건 설정 리스트
        $data['view']['sinfo'] = $sinfo; // 강사수당설정 내용
        $data['view']['tchr_list'] = $tchr_list; // 강사 리스트
        $data['view']['cateEvent'] = $cateEvent; // 카테고리 상품
        $this->viewPage('/tchr/tmemmain/tchr_salary_setting',$data);
    }

    /**
     * 상품만들기에서 대분류 선택시 그에 해당하는 이용권를 리턴한다.
     * @return string
     */
    public function sarly_del()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['1rd_cate_cd_arr'] = $postVar['cate1_selected'];
        $cate2 = $cateModel->get_2rd_list_by_1rd($cateData);
        $cateModel->sarly_del($cateData);
        
        $return_json['result'] = 'true';
        $return_json['cate2'] = $cate2;
        
        return json_encode($return_json);
    }

    
    /**
     * 강사수당설정 기본 수정 처리 [AJAX]
     */
    public function ajax_salry_setting_base_mod_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        $sarlyModel = new SarlyModel();
        
        $baseData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $baseData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        //$baseData['tchr_id'] = $postVar['tchr_id'];
        $baseData['sarly_pmt_cond'] = $postVar['sarly_pmt_cond'];
        //$baseData['sarly_pmt_mthd'] = $postVar['sarly_pmt_mthd'];
        $baseData['sarly_aply_s_date'] = $postVar['sarly_aply_s_date'];
        $baseData['sarly_aply_e_date'] = $postVar['sarly_aply_e_date'];
        $baseData['vat_yn'] = $postVar['vat_yn'];
        $baseData['sarly_mgmt_sno'] = $postVar['sarly_mgmt_sno'];
        
        if (isset($postVar['cate1_chk']))
        {
            $baseData['sarly_aply_item_1'] = "'".implode("','",$postVar['cate1_chk'])."'";
        } else
        {
            $baseData['sarly_aply_item_1'] = "";
        }
        
        if (isset($postVar['cate2_chk']))
        {
            $baseData['sarly_aply_item_2'] = "'".implode("','",$postVar['cate2_chk'])."'";
        } else
        {
            $baseData['sarly_aply_item_2'] = "";
        }
        
        $baseData['sarly_mgmt_memo'] = "";
        $baseData['sarly_mgmt_title'] = "";
        
        $baseData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $baseData['mod_datetm'] = $nn_now;
        
        $sarlyModel->update_sarly_mgmt($baseData);
        
        // 기본급일 경우에는 수당조건 설정하기가 필요 없기때문에 이부분에 처리를 해줘야함.
        if ($postVar['sarly_pmt_cond'] == "03") // 기본급 처리
        {
            $upData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $upData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $upData['sarly_mgmt_sno'] = $postVar['sarly_mgmt_sno'];
            $upData['sarly_amt'] = put_num($postVar['sarly_basic_amt']);
            
            $sarlyModel->update_sarly_mgmt_for_basic_amt($upData);
        }
        
        $return_json['result'] = "true";
        $return_json['msg'] = "강사수당설정 기본 수정";
        return json_encode($return_json);
    }
    
    /**
     * 강사수당설정 기본 처리 [AJAX]
     */
    public function ajax_salry_setting_base_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        $sarlyModel = new SarlyModel();
        $memModel = new MemModel();
        
        
        $baseData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $baseData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $baseData['mem_id'] = $postVar['tchr_id'];
        
        $tinfo = $memModel->get_mem_info_id_idname($baseData);
        $baseData['tchr_id'] = $postVar['tchr_id'];
        $baseData['tchr_sno'] = $tinfo[0]['MEM_SNO'];
        $baseData['tchr_nm'] = $tinfo[0]['MEM_NM'];
        
        $baseData['sarly_pmt_cond'] = $postVar['sarly_pmt_cond'];
        
        if ($postVar['sarly_pmt_cond'] == "03") // 기본급 처리
        {
            $baseData['sarly_pmt_mthd'] = "50"; // 기본급에 대한 코드는 50으로 고정함.
        } else 
        {
            $baseData['sarly_pmt_mthd'] = $postVar['sarly_pmt_mthd'];
        }
        
        $baseData['sarly_aply_s_date'] = $postVar['sarly_aply_s_date'];
        $baseData['sarly_aply_e_date'] = $postVar['sarly_aply_e_date'];
        $baseData['vat_yn'] = $postVar['vat_yn'];
        
        if (isset($postVar['cate1_chk']))
        {
            $baseData['sarly_aply_item_1'] = "'".implode("','",$postVar['cate1_chk'])."'";
        } else 
        {
            $baseData['sarly_aply_item_1'] = "";
        }
        
        if (isset($postVar['cate2_chk']))
        {
            $baseData['sarly_aply_item_2'] = "'".implode("','",$postVar['cate2_chk'])."'";
        } else
        {
            $baseData['sarly_aply_item_2'] = "";
        }
        
        $baseData['sarly_mgmt_memo'] = "";
        $baseData['sarly_mgmt_title'] = "";
        
        $baseData['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $baseData['cre_datetm'] = $nn_now;
        $baseData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $baseData['mod_datetm'] = $nn_now;
        
        $r = $sarlyModel->insert_sarly_mgmt($baseData);
        
        $return_json['result'] = "true";
        $return_json['sarly_mgmt_sno'] = $r->connID->insert_id;
        
        // 기본급일 경우에는 수당조건 설정하기가 필요 없기때문에 이부분에 처리를 해줘야함.
        if ($postVar['sarly_pmt_cond'] == "03") // 기본급 처리
        {
            $addData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $addData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            
            $addData['sarly_mgmt_sno'] = $return_json['sarly_mgmt_sno'];
            
            $addData['sell_mm_sales_s_amt'] = 0; //판매_월_매출_시작_금액
            $addData['sell_mm_sales_e_amt'] = 0; //판매_월_매출_종료_금액
            
            $addData['pt_clas_mm_sales_s_amt'] = 0; // PT수업_월_매출_시작_금액
            $addData['pt_clas_mm_sales_e_amt'] = 0; // PT수업_월_매출_종료_금액
            
            $addData['pt_clas_s_cnt'] = 0; // PT_수업_시작_횟수
            $addData['pt_clas_e_cnt'] = 0; // PT_수업_종료_횟수
            
            $addData['gx_clas_s_cnt'] = 0; // GX_수업_시작_횟수
            $addData['gx_clas_e_cnt'] = 0; // GX_수업_시작_횟수
            
            $addData['sell_sales_rate'] = 0; // 판매_매출_요율
            $addData['pt_clas_sales_rate'] = 0; // PT수업_월_매출_요율
            $addData['sarly_amt'] = 0; // 수당_금액
            $addData['clas_1tm_amt'] = 0; // 수업_1회_금액
            
            $addData['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $addData['cre_datetm'] = $nn_now;
            $addData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $addData['mod_datetm'] = $nn_now;
            
            $addData['sarly_pmt_mthd'] = $baseData['sarly_pmt_mthd'];
            $addData['sarly_amt'] = put_num($postVar['sarly_basic_amt']);
            
            $sarlyModel->insert_sub_sarly_mgmt($addData);
        }
        
        
        $return_json['msg'] = "강사수당설정 기본 저장";
        return json_encode($return_json);
    }
    
    public function ajax_salry_setting_del_cond_proc()
    {
        $postVar = $this->request->getPost();
        $sarlyModel = new SarlyModel();
        
        
        $delData['sarly_sub_mgmt_sno'] = $postVar['sarly_sub_mgmt_sno'];
        $sarlyModel->delete_sub_sarly_mgmt($delData);
        
        $return_json['result'] = "true";
        $return_json['msg'] = "강사수당설정 기본 삭제";
        return json_encode($return_json);
        
    }
    
    
    public function ajax_salry_setting_add_cond_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        $sarlyModel = new SarlyModel();
        
        $addData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $addData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $addData['sarly_mgmt_sno'] = $postVar['add_sarly_mgmt_sno'];
        
        $addData['sell_mm_sales_s_amt'] = 0; //판매_월_매출_시작_금액
        $addData['sell_mm_sales_e_amt'] = 0; //판매_월_매출_종료_금액

        $addData['pt_clas_mm_sales_s_amt'] = 0; // PT수업_월_매출_시작_금액
        $addData['pt_clas_mm_sales_e_amt'] = 0; // PT수업_월_매출_종료_금액
        
        $addData['pt_clas_s_cnt'] = 0; // PT_수업_시작_횟수
        $addData['pt_clas_e_cnt'] = 0; // PT_수업_종료_횟수
        
        $addData['gx_clas_s_cnt'] = 0; // GX_수업_시작_횟수
        $addData['gx_clas_e_cnt'] = 0; // GX_수업_시작_횟수
        
        $addData['sell_sales_rate'] = 0; // 판매_매출_요율
        $addData['pt_clas_sales_rate'] = 0; // PT수업_월_매출_요율
        $addData['sarly_amt'] = 0; // 수당_금액
        $addData['clas_1tm_amt'] = 0; // 수업_1회_금액
        
        $addData['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $addData['cre_datetm'] = $nn_now;
        $addData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $addData['mod_datetm'] = $nn_now;
        
        $addData['sarly_pmt_mthd'] = $postVar['add_sarly_pmt_mthd'];
        
        switch($postVar['add_sarly_pmt_mthd'])
        {
            case "11" : // "[수당비율] 판매 매출액 / 판매 매출 요율"
                $addData['sell_mm_sales_s_amt'] = put_num2($postVar['sarly_s_set']);
                $addData['sell_mm_sales_e_amt'] = put_num2($postVar['sarly_e_set']);
                $addData['sell_sales_rate'] = put_num2($postVar['sarly_set']);
            break;
            case "12" : // "[수당비율] 판매 매출액 / PT수업 매출 요율"
                $addData['sell_mm_sales_s_amt'] = put_num2($postVar['sarly_s_set']);
                $addData['sell_mm_sales_e_amt'] = put_num2($postVar['sarly_e_set']);
                $addData['pt_clas_sales_rate'] = put_num2($postVar['sarly_set']);
            break;
            case "13" : // "[수당비율] PT수업 매출액 / PT수업 매출 요율"
                $addData['pt_clas_mm_sales_s_amt'] = put_num2($postVar['sarly_s_set']);
                $addData['pt_clas_mm_sales_e_amt'] = put_num2($postVar['sarly_e_set']);
                $addData['pt_clas_sales_rate'] = put_num2($postVar['sarly_set']);
            break;
            case "21" : // "[수당금액] 판매 매출액 / 수당금액"
                $addData['sell_mm_sales_s_amt'] = put_num2($postVar['sarly_s_set']);
                $addData['sell_mm_sales_e_amt'] = put_num2($postVar['sarly_e_set']);
                $addData['sarly_amt'] = put_num2($postVar['sarly_set']);
            break;
            case "22" : // "[수당금액] PT수업 매출액 / 수당금액"
                $addData['pt_clas_mm_sales_s_amt'] = put_num2($postVar['sarly_s_set']);
                $addData['pt_clas_mm_sales_e_amt'] = put_num2($postVar['sarly_e_set']);
                $addData['sarly_amt'] = put_num2($postVar['sarly_set']);
            break;
            case "31" : // "[수당회당] PT수업 횟수 / 수업1회금액"
                $addData['pt_clas_s_cnt'] = put_num2($postVar['sarly_s_set']);
                $addData['pt_clas_e_cnt'] = put_num2($postVar['sarly_e_set']);
                $addData['clas_1tm_amt'] = put_num2($postVar['sarly_set']);
            break;
            case "32" : // "[수당회당] GX수업 횟수 / 수업1회금액"
                $addData['gx_clas_s_cnt'] = put_num2($postVar['sarly_s_set']);
                $addData['gx_clas_e_cnt'] = put_num2($postVar['sarly_e_set']);
                $addData['clas_1tm_amt'] = put_num2($postVar['sarly_set']);
            break;
                
        }
        
        $sarlyModel->insert_sub_sarly_mgmt($addData);
        
        $table_info = $this->list_sub_sarly_mgmt($postVar['add_sarly_mgmt_sno'], $postVar['add_sarly_pmt_mthd']);
        
        $return_json['result'] = "true";
        $return_json['msg'] = "강사수당설정 조건 추가";
        return json_encode($return_json);
        
    }
    
    private function list_sub_sarly_mgmt($sarly_mgmt_sno,$sarly_pmt_mthd)
    {
        $returnVal = array();
        $sarlyModel = new SarlyModel();
        
        $sData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $sData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sData['sarly_mgmt_sno'] = $sarly_mgmt_sno;
        $list_sub = $sarlyModel->list_sarly_sub_mgmt($sData);
        
        $sarly_basic_amt = "0";
        
        $new_list_sub = array();
        $new_i = 0;
        foreach ($list_sub as $r) :
            $new_list_sub[$new_i]['sarly_sub_mgmt_sno'] = $r['SARLY_SUB_MGMT_SNO'];
        
            switch($sarly_pmt_mthd)
            {
                case "11" : // "[수당비율] 판매 매출액 / 판매 매출 요율"
                    $new_list_sub[$new_i]['sarly_s_set'] = $r['SELL_MM_SALES_S_AMT'];
                    $new_list_sub[$new_i]['sarly_e_set'] = $r['SELL_MM_SALES_E_AMT'];
                    $new_list_sub[$new_i]['sarly_set'] = $r['SELL_SALES_RATE'];
                    break;
                case "12" : // "[수당비율] 판매 매출액 / PT수업 매출 요율"
                    $new_list_sub[$new_i]['sarly_s_set'] = $r['SELL_MM_SALES_S_AMT'];
                    $new_list_sub[$new_i]['sarly_e_set'] = $r['SELL_MM_SALES_E_AMT'];
                    $new_list_sub[$new_i]['sarly_set'] = $r['PT_CLAS_SALES_RATE'];
                    break;
                case "13" : // "[수당비율] PT수업 매출액 / PT수업 매출 요율"
                    $new_list_sub[$new_i]['sarly_s_set'] = $r['PT_CLAS_MM_SALES_S_AMT'];
                    $new_list_sub[$new_i]['sarly_e_set'] = $r['PT_CLAS_MM_SALES_E_AMT'];
                    $new_list_sub[$new_i]['sarly_set'] = $r['PT_CLAS_SALES_RATE'];
                    break;
                case "21" : // "[수당금액] 판매 매출액 / 수당금액"
                    $new_list_sub[$new_i]['sarly_s_set'] = $r['SELL_MM_SALES_S_AMT'];
                    $new_list_sub[$new_i]['sarly_e_set'] = $r['SELL_MM_SALES_E_AMT'];
                    $new_list_sub[$new_i]['sarly_set'] = $r['SARLY_AMT'];
                    break;
                case "22" : // "[수당금액] PT수업 매출액 / 수당금액"
                    $new_list_sub[$new_i]['sarly_s_set'] = $r['PT_CLAS_MM_SALES_S_AMT'];
                    $new_list_sub[$new_i]['sarly_e_set'] = $r['PT_CLAS_MM_SALES_E_AMT'];
                    $new_list_sub[$new_i]['sarly_set'] = $r['SARLY_AMT'];
                    break;
                case "31" : // "[수당회당] PT수업 횟수 / 수업1회금액"
                    $new_list_sub[$new_i]['sarly_s_set'] = $r['PT_CLAS_S_CNT'];
                    $new_list_sub[$new_i]['sarly_e_set'] = $r['PT_CLAS_E_CNT'];
                    $new_list_sub[$new_i]['sarly_set'] = $r['CLAS_1TM_AMT'];
                    break;
                case "32" : // "[수당회당] GX수업 횟수 / 수업1회금액"
                    $new_list_sub[$new_i]['sarly_s_set'] = $r['GX_CLAS_S_CNT'];
                    $new_list_sub[$new_i]['sarly_e_set'] = $r['GX_CLAS_E_CNT'];
                    $new_list_sub[$new_i]['sarly_set'] = $r['CLAS_1TM_AMT'];
                    break;
                case "50" : // 기본급
                    $new_list_sub[$new_i]['sarly_s_set'] = "0";
                    $new_list_sub[$new_i]['sarly_e_set'] = "0";
                    $new_list_sub[$new_i]['sarly_set'] = $r['SARLY_AMT'];
                    $sarly_basic_amt = $r['SARLY_AMT'];
                    break;
            }
            
            $new_i++;
            
        endforeach;
        
        switch($sarly_pmt_mthd)
        {
            case "11" : // "[수당비율] 판매 매출액 / 판매 매출 요율"
                $table_title['title_s_set'] = "판매매출액(시작)";
                $table_title['title_e_set'] = "판매매출액(종료)";
                $table_title['title_set'] = "판매매출 요율(%)";
                break;
            case "12" : // "[수당비율] 판매 매출액 / PT수업 매출 요율"
                $table_title['title_s_set'] = "판매매출액(시작)";
                $table_title['title_e_set'] = "판매매출액(종료)";
                $table_title['title_set'] = "PT수업 매출요율(%)";
                break;
            case "13" : // "[수당비율] PT수업 매출액 / PT수업 매출 요율"
                $table_title['title_s_set'] = "PT수업매출액(시작)";
                $table_title['title_e_set'] = "PT수업매출액(종료)";
                $table_title['title_set'] = "PT수업 매출요율(%)";
                break;
            case "21" : // "[수당금액] 판매 매출액 / 수당금액"
                $table_title['title_s_set'] = "판매매출액(시작)";
                $table_title['title_e_set'] = "판매매출액(종료)";
                $table_title['title_set'] = "수당금액(원)";
                break;
            case "22" : // "[수당금액] PT수업 매출액 / 수당금액"
                $table_title['title_s_set'] = "PT수업매출액(시작)";
                $table_title['title_e_set'] = "PT수업매출액(종료)";
                $table_title['title_set'] = "수당금액(원)";
                break;
            case "31" : // "[수당회당] PT수업 횟수 / 수업1회금액"
                $table_title['title_s_set'] = "PT수업횟수(시작)";
                $table_title['title_e_set'] = "PT수업횟수(종료)";
                $table_title['title_set'] = "수업1회금액(원)";
                break;
            case "32" : // "[수당회당] GX수업 횟수 / 수업1회금액"
                $table_title['title_s_set'] = "GX수업횟수(시작)";
                $table_title['title_e_set'] = "GX수업횟수(종료)";
                $table_title['title_set'] = "수업1회금액(원)";
                break;
            case "50" : // 기본급
                $table_title['title_s_set'] = "";
                $table_title['title_e_set'] = "";
                $table_title['title_set'] = "";
                break;
        }
        
        $returnVal['sarly_basic_amt'] = $sarly_basic_amt;
        $returnVal['list_sub'] = $new_list_sub;
        $returnVal['table_title'] = $table_title;
        return $returnVal;
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 추천 회원 리스트 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 추천회원 (리스트)
     */
    public function mem_introd_list()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    	
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $memModel = new MemModel();
        
        $totalCount  = $memModel->list_introd_count($boardPage->getVal());
        $list_introd = $memModel->list_introd($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ss : 적용시작일
        // se : 적용종료일
        // sc : 등록일
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; // 강사명
        if ( !isset($searchVal['spcond']) ) $searchVal['spcond'] = ''; // 지급조건
        if ( !isset($searchVal['spmthdnm']) ) $searchVal['spmthdnm'] = ''; // 지급방법
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['list_introd'] = $list_introd; // 추천한 아이디 리스트
        $this->viewPage('/tchr/tmemmain/mem_introd_list',$data);
    }
    
    /**
     * 추천회원 검색 처리 [ ajax ]
     */
    public function ajax_mem_introd_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $memModel = new MemModel();
        
        $totalCount = $memModel->list_introd_count($boardPage->getVal());
        $list_introd = $memModel->list_introd($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        $listCount = $searchVal['listCount'];
        
        foreach($list_introd as $r) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $listCount . '</td>';
            
            // 추천한 회원
            $html .= '<td>';
            $html .= '<img class="preview_mem_photo" src="' . $r['S_MEM_THUMB_IMG'] . '" ';
            $html .= 'alt="회원사진" style="border-radius: 50%; cursor: pointer;" ';
            $html .= 'onclick="showFullPhoto(\'' . $r['S_MEM_MAIN_IMG'] . '\')" ';
            $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $r['S_MEM_GENDR'] . '.png\';">';
            $html .= $r['S_MEM_NM'];
            $html .= '</td>';
            
            // 추천한 아이디
            $html .= '<td>' . $r['S_MEM_ID'] . '</td>';
            
            // 추천한 회원 정보보기
            $html .= '<td>';
            $html .= '<button type="button" class="btn btn-warning btn-xs" onclick="mem_manage_mem_info(\'' . $r['S_MEM_SNO'] . '\');">추천한 회원 정보보기</button>';
            $html .= '</td>';
            
            // 추천받은 회원
            $html .= '<td>';
            $html .= '<img class="preview_mem_photo" src="' . $r['T_MEM_THUMB_IMG'] . '" ';
            $html .= 'alt="회원사진" style="border-radius: 50%; cursor: pointer;" ';
            $html .= 'onclick="showFullPhoto(\'' . $r['T_MEM_MAIN_IMG'] . '\')" ';
            $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $r['T_MEM_GENDR'] . '.png\';">';
            $html .= $r['T_MEM_NM'];
            $html .= '</td>';
            
            // 추천받은 아이디
            $html .= '<td>' . $r['T_MEM_ID'] . '</td>';
            
            // 추천받은 회원 정보보기
            $html .= '<td>';
            $html .= '<button type="button" class="btn btn-warning btn-xs" onclick="mem_manage_mem_info(\'' . $r['T_MEM_SNO'] . '\');">추천받은 회원 정보보기</button>';
            $html .= '</td>';
            
            // 추천일
            $html .= '<td>' . substr($r['CRE_DATETM'],0,10) . '</td>';
            
            // 추천일시
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            
            // 옵션
            $html .= '<td></td>';
            $html .= '</tr>';
            
            $listCount--;
        }
        
        if (empty($list_introd)) {
            $html .= '<tr><td colspan="10" class="text-center">검색된 추천회원이 없습니다.</td></tr>';
        }
        
        // 페이저 HTML
        $pager = $boardPage->getPager($totalCount);
        
        $result['result'] = 'true';
        $result['html'] = $html;
        $result['pager'] = $pager;
        $result['totalCount'] = $totalCount;
        
        echo json_encode($result);
    }
}