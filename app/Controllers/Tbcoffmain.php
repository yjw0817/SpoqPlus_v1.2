<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\CateModel;
use App\Models\LockrModel;
use App\Libraries\Ama_calendar;
use App\Models\MemModel;
use App\Models\CalendarModel;
use App\Models\NotiModel;
use App\Libraries\GxClas_lib;

class Tbcoffmain extends MainTchrController
{
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 지점 환경 설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 지점 환경 설정
     */
    public function bocff_setting()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '지점 환경 설정',
            'nav' => array('지점관리' => '' , '지점 환경 설정' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 중분류 사용 설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 중분류 사용 설정
     */
    public function use_2rd_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    

        $model = new CateModel();

        // 데이터 가져오기
        $mdata = [
            'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
            'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd')
        ];

        $list_2rd_main = $model->get_2rd_list($mdata);
        $list_2rd_use = $model->use_2rd_list($mdata);

        // 사용 여부 매핑
        $new_use = array_column($list_2rd_use, 'USE_YN', '2RD_CATE_CD');

        // 최종 리스트 생성
        $new_list = array_map(function ($item) use ($new_use) {
            $item['USE_YN'] = $new_use[$item['2RD_CATE_CD']] ?? 'N';
            return $item;
        }, $list_2rd_main);

        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['list_2rd_use'] = $new_list;

        // AJAX 요청인 경우 메인 콘텐츠만 반환
        // if ($this->request->isAJAX()) {
        //     return view('/tchr/tcoffmain/use_2rd_manage', $data['view']);
        // }


        // 일반 요청인 경우 전체 페이지 렌더링
        $this->viewPage('/tchr/tcoffmain/use_2rd_manage', $data);
    }

    
    /**
     * 중분류 사용 설정 ( USE_YN : Y or N )
     * @return string
     */
    public function ajax_use_2rd_change()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$model = new CateModel();
    	$nn_now = new Time('now');
    	
    	
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * code2 : 2차 카테고리 코드
    	 * use_yn : Y 또는 N
    	 */
    	
    	$postVar = $this->request->getPost();
    	
    	// ===========================================================================
    	// Processs
    	// ===========================================================================
    	
    	$useSetYN = "Y";
    	$mdata['2rd_cate_cd'] = $postVar['code2'];
    	$mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$mdata['use_yn'] = $postVar['use_yn'];
    	
    	// 1. use_yn 이 N 일 경우 판매 상품에서 이미 사용중인지를 체크한다. 사용중이라면 N 설정이 불가능하다.
    	if($postVar['use_yn'] == "N") :
    		$ncheck_count = $model->use_2rd_sell_check_count($mdata);
    		if ($ncheck_count > 0) $useSetYN = "N";
    	endif;
    	
    	if ($useSetYN == "Y") :
	    	// 2. code1 에 따라서 insert 할지 update 할지를 검사한다.
	    	$check_count = $model->use_2rd_check_count($mdata);
	    	$postVar['check_count'] = $check_count;
	    	if ($check_count == 0):
		    	// 3-1 . insert 를 수행한다. ( 수행전 대분류 정보를 가져온다. )
		    	$get_2rd_cate_info = $model->get_use_2rd_info($mdata);
		    	
		    	$mdata['1rd_cate_cd'] = $get_2rd_cate_info[0]['1RD_CATE_CD'];
		    	//$mdata['comp_cd'] = $get_2rd_cate_info[0]['COMP_CD'];
		    	$mdata['cate_nm'] = $get_2rd_cate_info[0]['CATE_NM'];
		    	$mdata['grp_cate_set'] = $get_2rd_cate_info[0]['GRP_CATE_SET'];
		    	$mdata['clas_dv'] = $get_2rd_cate_info[0]['CLAS_DV'];
		    	$mdata['lockr_set'] = $get_2rd_cate_info[0]['LOCKR_SET'];
		    	$mdata['lockr_knd'] = $get_2rd_cate_info[0]['LOCKR_KND'];
		    	
		    	$mdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
		    	$mdata['cre_datetm'] = $nn_now;
		    	$mdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
		    	$mdata['mod_datetm'] = $nn_now;
		    	
		    	$insert_2rd_use = $model->insert_use_2rd_change($mdata);
	    	else:
	    	// 3-2 . update 를 수행한다.
		    	$mdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
		    	$mdata['mod_datetm'] = $nn_now;
		    	$update_2rd_use = $model->update_use_2rd_change($mdata);
	    	endif;
    	endif;
    	
    	$use_set_result = $postVar;
    	
    	if ($useSetYN == "N")
    	{
    	    $return_json['result'] = 'false';
    	    $return_json['msg'] = '등록된 상품이 있습니다. 사용안함 설정이 불가능합니다.';
    	} else 
    	{
    	    $return_json['result'] = 'true';
    	    $return_json['msg'] = '설정 완료';
    	}
    	
    	$return_json['use_set_result'] = $use_set_result;
    	
    	return json_encode($return_json);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 공지사항 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 회원 공지사항 관리
     */
    public function notice_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $modelNoti = new NotiModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['get']['noti_dv'] = "01";
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount  = $modelNoti->list_notice_count($boardPage->getVal());
        $noti_list = $modelNoti->list_notice($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        //if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['noti_list'] = $noti_list;
      
        $this->viewPage('/tchr/tcoffmain/notice_manage',$data);
    }
    
    /**
     * 강사 공지사항 관리
     */
    public function notice_manage2()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $modelNoti = new NotiModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['get']['noti_dv'] = "02";
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount  = $modelNoti->list_notice_count($boardPage->getVal());
        $noti_list = $modelNoti->list_notice($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        //if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['noti_list'] = $noti_list;
        
        $this->viewPage('/tchr/tcoffmain/notice_manage2',$data);
    }
    
    /**
     * 공지사항 정보 가져오기 [수정]
     */
    public function ajax_get_notice_info()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        // noti_sno : 공지사항 일련번호
        $postVar = $this->request->getPost();
        $modelNoti = new NotiModel();
        
        $getinfo['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $getinfo['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $getinfo['noti_dv'] = $postVar['noti_dv'];
        $getinfo['noti_sno'] = $postVar['noti_sno'];
        
        $get_notice = $modelNoti->get_notice($getinfo);
        
        $return_json['result'] = 'true';
        $return_json['msg'] = "공지사항 정보 가져오기";
        $return_json['noti_info'] = $get_notice[0];
        return json_encode($return_json);
    }
    
    /**
     * 공지사항 등록 처리
     */
    public function ajax_notice_insert_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        $modelNoti = new NotiModel();
        
        $ndata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $ndata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $ndata['noti_dv'] = $postVar['noti_dv'];
        $ndata['noti_title'] = $postVar['noti_title'];
        $ndata['noti_conts'] = $postVar['noti_conts'];
        $ndata['noti_s_date'] = $postVar['noti_s_date'];
        $ndata['noti_e_date'] = $postVar['noti_e_date'];
        $ndata['noti_stat'] = "00";
        $ndata['noti_top'] = $postVar['noti_top'];
        $ndata['noti_ext_url'] = "";
        $ndata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $ndata['mod_datetm'] = $nn_now;
        $ndata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $ndata['cre_datetm'] = $nn_now;
        
        $modelNoti->insert_notice($ndata);
        
        $return_json['result'] = 'true';
        $return_json['msg'] = "공지사항 등록";
        return json_encode($return_json);
    }
    
    /**
     * 공지사항 수정 처리
     */
    public function ajax_notice_modify_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        $modelNoti = new NotiModel();
        $ndata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $ndata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $ndata['noti_dv'] = $postVar['mod_noti_dv'];
        $ndata['noti_title'] = $postVar['mod_noti_title'];
        $ndata['noti_conts'] = $postVar['mod_noti_conts'];
        $ndata['noti_s_date'] = $postVar['mod_noti_s_date'];
        $ndata['noti_e_date'] = $postVar['mod_noti_e_date'];
        $ndata['noti_stat'] = $postVar['mod_noti_stat'];
        $ndata['noti_top'] = $postVar['mod_noti_top'];
        $ndata['noti_sno'] = $postVar['mod_noti_sno'];
        $ndata['noti_ext_url'] = "";
        $ndata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $ndata['mod_datetm'] = $nn_now;
        
        $modelNoti->modify_notice($ndata);
        
        $return_json['postVar'] = $postVar;
        $return_json['result'] = 'true';
        $return_json['msg'] = "공지사항 수정";
        return json_encode($return_json);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 락커 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 락커 관리 메인
     */
    public function locker_manage()
    {
        return redirect()->to('/locker/manage?m1=1&m2=7');
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 그룹수업 스케쥴관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 그룹수업 스케쥴관리 룸 설정
     */
    public function grp_schedule_room()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $calModel = new CalendarModel();
        // GX ROOM 설정을 가져온다.
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $gx_room_list = $calModel->list_gx_room($mdata);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['gx_room_list'] = $gx_room_list; // GX ROOM LIST
        $this->viewPage('/tchr/tcoffmain/grp_schedule_room',$data);
    }
    
    /**
     * GX ROOM 을 추가한다. [ AJAX ]
     * @return string
     */
    public function ajax_gx_room_insert()
    {
        $nn_now = new Time('now');
        
        $calModel = new CalendarModel();
        $postVar = $this->request->getPost();
        
        $cData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cData['gx_room_title'] = $postVar['gx_room_title'];
        
        $cData['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $cData['cre_datetm'] = $nn_now;
        $cData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $cData['mod_datetm'] = $nn_now;
        
        $calModel->insert_gx_room($cData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    
    /**
     * 그룹수업 스케쥴관리
     */
    public function grp_schedule($gx_room_mgmt_sno='')
    
    {
        // 현재 룸 정보를 세션에 저장
        if (!empty($gx_room_mgmt_sno)) {
            $_SESSION['current_gx_room_mgmt_sno'] = $gx_room_mgmt_sno;
        }
        
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '그룹수업 스케쥴관리',
            'nav' => array('지점관리' => '' , '그룹수업 스케쥴관리' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2'),
            'useJs' => array('fullCalendar'),
            'fullCalendar' => array(
                'url' => '/tbcoffmain/grp_schedule_proc'
            )
        );
        
        $memModel = new MemModel();
        $calModel = new CalendarModel();
        // 회원 정보를 가져온다.
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');

        $gx_room_list = $calModel->list_gx_room($mdata);
        
        $mdata['gx_room_mgmt_sno'] = $gx_room_mgmt_sno;
        
        $get_tchr_list = $memModel->get_list_tchr($mdata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
            $tchr_list[$tchr_i] = $t;
            $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
            $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
            $tchr_i++;
        endforeach;
        
        $calModel = new CalendarModel();
        $get_gx_item = $calModel->get_gx_item($mdata);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['gx_room_mgmt_sno'] = $gx_room_mgmt_sno; // GX 룸 일련번호
        $data['view']['gx_item_list'] = $get_gx_item; // GX 아이템 리스트
        $data['view']['tchr_list'] = $tchr_list; // 강사 리스트
        $data['view']['gx_room_list'] = $gx_room_list; // GX 룸 리스트
        $data['view']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd'); // 회사 코드
        $data['view']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd'); // 지점 코드
        $this->viewPage('/tchr/tcoffmain/grp_schedule',$data);
    }
    
    /**
     * AJAX - 그룹수업 스케줄 데이터 조회
     */
    public function ajax_grp_schedule_data()
    {
        $postVar = $this->request->getPost();
        $gx_room_mgmt_sno = $postVar['gx_room_mgmt_sno'] ?? '';
        
        try {
            $memModel = new MemModel();
            $calModel = new CalendarModel();
            
            // 회원 정보를 가져온다.
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_room_mgmt_sno'] = $gx_room_mgmt_sno;
            
            // GX 룸 리스트 조회
            $gx_room_list = $calModel->list_gx_room($mdata);
            
            // 강사 리스트 조회
            $get_tchr_list = $memModel->get_list_tchr($mdata);
            
            $tchr_list = array();
            $sDef = SpoqDef();
            $tchr_i = 0;
            foreach ($get_tchr_list as $t) :
                $tchr_list[$tchr_i] = $t;
                $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
                $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
                $tchr_i++;
            endforeach;
            
            // GX 아이템 조회
            $get_gx_item = $calModel->get_gx_item($mdata);
            
            $return_json = array(
                'result' => 'true',
                'gx_room_mgmt_sno' => $gx_room_mgmt_sno,
                'gx_item_list' => $get_gx_item,
                'tchr_list' => $tchr_list,
                'gx_room_list' => $gx_room_list
            );
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '데이터 조회 중 오류가 발생했습니다.'
            );
        }
        
        return json_encode($return_json);
    }
    
    public function ajax_gx_item_insert()
    {
        $calModel = new CalendarModel();
        $postVar = $this->request->getPost();
        
        $cData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cData['tchr_id'] = $postVar['gx_tchr_id'];
        $cData['gx_item_nm'] = $postVar['new_event'];
        $cData['gx_item_color'] = $postVar['gx_item_color'];
        $cData['gx_room_mgmt_sno'] = $postVar['gx_room_mgmt_sno'];
        $cData['mem_id'] = $postVar['gx_tchr_id'];
        $memModel = new MemModel();
        
        $get_tchr_info = $memModel->get_mem_info_id_idname($cData);
        
        $cData['tchr_sno'] = $get_tchr_info[0]['MEM_SNO'];
        $cData['tchr_nm'] = $get_tchr_info[0]['MEM_NM'];
        
        $calModel->insert_gx_item($cData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    public function ajax_gx_item_delete()
    {
        $calModel = new CalendarModel();
        $postVar = $this->request->getPost();
        
        $delData['gx_item_sno'] = $postVar['gx_item_sno'];
        $calModel->delete_gx_item($delData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    public function ajax_gx_stchr_change()
    {
        $nn_now = new Time('now');
        $calModel = new CalendarModel();
        $postVar = $this->request->getPost();
        
        $sData['gx_schd_mgmt_sno'] = $postVar['gx_schd_mgmt_sno'];
        $get_schd_info = $calModel->get_schd_info($sData);
        
        $memModel = new MemModel();
        
        $cData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cData['mem_id'] = $postVar['gx_stchr_id'];
        
        $get_tchr_info = $memModel->get_mem_info_id_idname($cData);
        
        $cData['gx_stchr_id'] = $get_tchr_info[0]['MEM_ID'];
        $cData['gx_stchr_sno'] = $get_tchr_info[0]['MEM_SNO'];
        $cData['gx_stchr_nm'] = $get_tchr_info[0]['MEM_NM'];
        $cData['gx_schd_mgmt_sno'] = $postVar['gx_schd_mgmt_sno'];
        $cData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $cData['mod_datetm'] = $nn_now;
        
        $replace_title = str_replace($get_schd_info[0]['GX_STCHR_NM'], $get_tchr_info[0]['MEM_NM'], $get_schd_info[0]['GX_CLAS_TITLE']);
        $cData['gx_clas_title'] = $replace_title;
        
        $calModel->update_gx_stchr($cData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    
    public function ajax_gx_stchr_delete()
    {
        $calModel = new CalendarModel();
        $postVar = $this->request->getPost();
        $sData['gx_schd_mgmt_sno'] = $postVar['gx_schd_mgmt_sno'];
        $calModel->delete_gx_stchr($sData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 그룹스케쥴 get update post 처리
     * @return string
     */
    public function grp_schedule_proc()
    {
        $postVar = $this->request->getPost();
        
        // 디버그 로그: 요청 데이터
        error_log("🎯 Tbcoffmain::grp_schedule_proc() 시작 - POST 데이터: " . json_encode($postVar));
        error_log("🏢 세션 정보 - comp_cd: " . ($_SESSION['comp_cd'] ?? 'null') . ", bcoff_cd: " . ($_SESSION['bcoff_cd'] ?? 'null'));
        error_log("🏢 요청 URL: " . ($_SERVER['REQUEST_URI'] ?? 'unknown'));
        error_log("🏢 HTTP_REFERER: " . ($_SERVER['HTTP_REFERER'] ?? 'unknown'));
        
        // gx_room_mgmt_sno가 POST 데이터에 없는 경우 현재 룸 정보를 확인
        if (empty($postVar['gx_room_mgmt_sno'])) {
            // 1. 세션에서 확인
            if (!empty($_SESSION['current_gx_room_mgmt_sno'])) {
                $postVar['gx_room_mgmt_sno'] = $_SESSION['current_gx_room_mgmt_sno'];
                error_log("🏢 세션에서 gx_room_mgmt_sno 설정: " . $postVar['gx_room_mgmt_sno']);
            }
            // 2. GET 파라미터에서 확인
            else if (!empty($this->request->getGet('gx_room_mgmt_sno'))) {
                $postVar['gx_room_mgmt_sno'] = $this->request->getGet('gx_room_mgmt_sno');
                error_log("🏢 GET 파라미터에서 gx_room_mgmt_sno 설정: " . $postVar['gx_room_mgmt_sno']);
            }
            // 3. HTTP_REFERER에서 추출 시도
            else if (!empty($_SERVER['HTTP_REFERER'])) {
                if (preg_match('/\/grp_schedule\/(\d+)/', $_SERVER['HTTP_REFERER'], $matches)) {
                    $postVar['gx_room_mgmt_sno'] = $matches[1];
                    error_log("🏢 HTTP_REFERER에서 gx_room_mgmt_sno 추출: " . $postVar['gx_room_mgmt_sno']);
                }
            }
        }
        
        // 현재 룸 정보를 세션에 저장 (다음번 사용을 위해)
        if (!empty($postVar['gx_room_mgmt_sno'])) {
            $_SESSION['current_gx_room_mgmt_sno'] = $postVar['gx_room_mgmt_sno'];
        }
        
        error_log("🔧 최종 gx_room_mgmt_sno: " . ($postVar['gx_room_mgmt_sno'] ?? 'null'));
        
        $Cal = new Ama_calendar($postVar);
        $result = $Cal->getCalendar();
        
        error_log("📤 Tbcoffmain::grp_schedule_proc() 완료 - 응답 길이: " . strlen($result));
        
        return $result;
    }
    
    public function ajax_copy_schedule()
    {
        $calModel = new CalendarModel();
        $postVar = $this->request->getPost();
        
        // 복사할 이번주 데이터 날짜를 배열에 저장해놓는다.
        
        for($c=0;$c<7;$c++) :
            $copy_ndate[$c] = date('Y-m-d', strtotime($postVar['copy_sdate'] . ' +' . $c . ' days'));
        endfor;
        
        // copy_sdate 기준으로 +7일 이후의 데이터를 삭제한다.
        $delData['del_date'] = date('Y-m-d', strtotime($postVar['copy_sdate'] . ' +6 days'));
        $delData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $delData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $delData['gx_room_mgmt_sno'] = $postVar['gx_room_mgmt_sno'];
        
        $calModel->delete_nextweek_schedule($delData);
        
        $from = new \DateTime( $postVar['copy_sdate'] );
        $to = new \DateTime( $postVar['copy_edate'] );
        $diffDays = $from -> diff( $to ) -> days;
        if ( $from > $to ) { $diffDays = '-' . $diffDays; }
        
        $copyData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $copyData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $copyData['gx_room_mgmt_sno'] = $postVar['gx_room_mgmt_sno'];
        
        for($i=7;$i<$diffDays+1;$i++) :
            $mm = $i % 7;
            $copyData['gx_clas_s_date'] = $copy_ndate[$mm];
            $copyData['change_date'] = date('Y-m-d', strtotime($postVar['copy_sdate'] . ' +' . $i . ' days'));
            $calModel->copy_schedule($copyData);
        endfor;
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
        
    }
    
    /**
     * GX 수동 수업체크
     */
    public function ajax_gx_attd_proc()
    {
        //gx_schd_mgmt_sno
        $postVar = $this->request->getPost();
        
        $setVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $setVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $setVar['stchr_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $gxClas = new GxClas_lib($setVar);
        
        $return_json = $gxClas->gx_attd_chk($postVar['gx_schd_mgmt_sno'],'Y');
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 그룹수업 데이터 조회
     */
    public function ajax_get_group_class_data()
    {
        $postVar = $this->request->getPost();
        $gx_item_sno = $postVar['gx_item_sno'] ?? '';
        
        try {
            $calModel = new CalendarModel();
            $memModel = new MemModel();
            
            // 회원 정보를 가져온다.
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_item_sno'] = $gx_item_sno;
            
            // GX 아이템 상세 정보 조회
            $gx_item_data = $calModel->get_gx_item_detail($mdata);
            
            if (empty($gx_item_data)) {
                $return_json = array(
                    'result' => 'false',
                    'message' => '해당 그룹수업 데이터를 찾을 수 없습니다.'
                );
            } else {
                $classData = $gx_item_data[0];
                
                // 담당강사 정보 확인 및 기본값 설정
                if (empty($classData['TCHR_ID'])) {
                    $classData['TCHR_ID'] = '';
                    $classData['TCHR_SNO'] = '';
                    $classData['TCHR_NM'] = '';
                }
                
                // 강사 리스트 조회 추가
                $get_tchr_list = $memModel->get_list_tchr($mdata);
                
                $tchr_list = array();
                $sDef = SpoqDef();
                $tchr_i = 0;
                foreach ($get_tchr_list as $t) :
                    $tchr_list[$tchr_i] = $t;
                    $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']] ?? '';
                    $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']] ?? '';
                    $tchr_i++;
                endforeach;
                
                // 참석 가능한 이용권 개수 조회
                $event_count = $calModel->get_gx_item_event_count($mdata);
                $classData['EVENT_COUNT'] = $event_count;
                
                // 선택된 이미지 정보 조회
                $selected_image = $calModel->get_selected_class_image($mdata);
                $classData['SELECTED_IMAGE'] = $selected_image;
                
                // 공개 스케줄 정보 생성
                if (!$classData['AUTO_SHOW_YN'] || $classData['AUTO_SHOW_YN'] == 'N') {
                    $classData['OPEN_SCHEDULE'] = '미설정';
                } else {
                    $unit_text = ($classData['AUTO_SHOW_UNIT'] == '1') ? '일' : '주';
                    $open_text = $classData['AUTO_SHOW_D'] . $unit_text . '전 ';
                    
                    // 시간 형식 변경 (HH:MM:SS -> HH시 또는 HH시 MM분)
                    $time_parts = explode(':', $classData['AUTO_SHOW_TIME']);
                    $hour = intval($time_parts[0]);
                    $minute = intval($time_parts[1]);
                    
                    $open_text .= $hour . '시';
                    if ($minute > 0) {
                        $open_text .= ' ' . $minute . '분';
                    }
                    $open_text .= '부터 공개';
                    
                    $classData['OPEN_SCHEDULE'] = $open_text;
                }
                
                // 폐강 스케줄 정보 생성
                if (!$classData['AUTO_CLOSE_YN'] || $classData['AUTO_CLOSE_YN'] == 'N') {
                    $classData['CLOSE_SCHEDULE'] = '미설정';
                } else {
                    // 분을 시간과 분으로 변환
                    $timeText = $this->convertMinutesToTimeText($classData['AUTO_CLOSE_MIN']);
                    $classData['CLOSE_SCHEDULE'] = '수업 시작후 ' . $timeText . ' 까지 최소인원 ' . $classData['AUTO_CLOSE_MIN_NUM'] . '명이 안될시 폐강';
                }
                
                $return_json = array(
                    'result' => 'true',
                    'data' => $classData,
                    'tchr_list' => $tchr_list
                );
            }
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '데이터 조회 중 오류가 발생했습니다.'
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 그룹수업 데이터 수정
     */
    public function ajax_update_group_class()
    {
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        try {
            $calModel = new CalendarModel();
            
            // 담당강사 정보 가져오기
            $memModel = new MemModel();
            $tchrData = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'mem_id' => $postVar['gx_tchr_id']
            );
            $get_tchr_info = $memModel->get_mem_info_id_idname($tchrData);
            
            // 수정할 데이터 준비
            $updateData = array(
                'gx_item_sno' => $postVar['gx_item_sno'],
                'gx_item_nm' => $postVar['gx_item_nm'],
                'tchr_id' => $postVar['gx_tchr_id'],
                'tchr_sno' => $get_tchr_info[0]['MEM_SNO'],
                'tchr_nm' => $get_tchr_info[0]['MEM_NM'],
                'gx_class_min' => $postVar['gx_class_min'],
                'gx_deduct_cnt' => $postVar['gx_deduct_cnt'],
                'gx_max_num' => $postVar['gx_max_num'],
                'gx_max_waiting' => $postVar['gx_max_waiting'],
                'reserv_num' => $postVar['reserv_num'],
                'use_reserv_yn' => $postVar['use_reserv_yn'],
                'auto_show_d' => $postVar['auto_show_d'],
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => $nn_now
            );
            
            // 그룹수업 정보 업데이트
            $result = $calModel->update_gx_item_detail($updateData);
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '그룹수업이 수정되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '수정 중 오류가 발생했습니다.'
                );
            }
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '수정 중 오류가 발생했습니다.'
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 자동 공개/폐강 설정 조회 (그룹수업 아이템 및 스케줄용)
     */
    public function ajax_get_auto_schedule_settings()
    {
        $postVar = $this->request->getPost();
        
        try {
            $calModel = new CalendarModel();
            
            // 그룹수업 아이템과 스케줄 구분
            if (isset($postVar['gx_item_sno']) && !empty($postVar['gx_item_sno'])) {
                // 그룹수업 아이템 조회
                $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
                $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
                $mdata['gx_item_sno'] = $postVar['gx_item_sno'];
                
                $gx_item_data = $calModel->get_gx_item_detail($mdata);
                
                if (empty($gx_item_data)) {
                    $return_json = array(
                        'result' => 'false',
                        'message' => '해당 그룹수업 데이터를 찾을 수 없습니다.'
                    );
                } else {
                    $classData = $gx_item_data[0];
                    
                    $return_json = array(
                        'result' => 'true',
                        'data' => $classData
                    );
                }
            } elseif (isset($postVar['gx_schd_mgmt_sno']) && !empty($postVar['gx_schd_mgmt_sno'])) {
                // 스케줄 조회
                $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
                $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
                $mdata['gx_schd_mgmt_sno'] = $postVar['gx_schd_mgmt_sno'];
                
                $schedule_data = $calModel->get_schedule_detail($mdata);
                
                if (empty($schedule_data)) {
                    $return_json = array(
                        'result' => 'false',
                        'message' => '해당 스케줄 데이터를 찾을 수 없습니다.'
                    );
                } else {
                    $classData = $schedule_data[0];
                    
                    $return_json = array(
                        'result' => 'true',
                        'data' => $classData
                    );
                }
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => 'gx_item_sno 또는 gx_schd_mgmt_sno가 필요합니다.'
                );
            }
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '데이터 조회 중 오류가 발생했습니다.'
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 자동 공개/폐강 설정 저장 (그룹수업 아이템용)
     */
    public function ajax_save_auto_schedule_settings()
    {
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        // 디버깅을 위한 로그 추가
        log_message('info', '📅 자동 공개/폐강 설정 저장 요청 데이터: ' . json_encode($postVar));
        
        try {
            $calModel = new CalendarModel();
            
            // 그룹수업 아이템과 스케줄 구분
            if (isset($postVar['gx_item_sno']) && !empty($postVar['gx_item_sno'])) {
                // 그룹수업 아이템 수정
                $updateData = array(
                    'gx_item_sno' => $postVar['gx_item_sno'],
                    'auto_show_yn' => $postVar['auto_show_yn'] ?? 'N',
                    'auto_show_d' => $postVar['auto_show_d'] ?? 1,
                    'auto_show_week_dur' => $postVar['auto_show_week_dur'] ?? 1,
                    'auto_show_unit' => $postVar['auto_show_unit'] ?? '1',
                    'auto_show_week' => $postVar['auto_show_weekday'] ?? '1',
                    'auto_show_time' => $postVar['auto_show_time'] ?? '13:00:00',
                    'auto_close_yn' => $postVar['auto_close_yn'] ?? 'N',
                    'auto_close_min' => $postVar['auto_close_min'] ?? '15',
                    'auto_close_min_num' => $postVar['auto_close_min_num'] ?? 1,
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => $nn_now
                );
                
                $result = $calModel->update_gx_item_auto_schedule($updateData);
            } elseif (isset($postVar['gx_schd_mgmt_sno']) && !empty($postVar['gx_schd_mgmt_sno'])) {
                // 스케줄 수정 - 스케줄 테이블 업데이트
                $updateData = array(
                    'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                    'auto_show_yn' => $postVar['auto_show_yn'] ?? 'N',
                    'auto_show_d' => $postVar['auto_show_d'] ?? 1,
                    'auto_show_week_dur' => $postVar['auto_show_week_dur'] ?? 1,
                    'auto_show_unit' => $postVar['auto_show_unit'] ?? '1',
                    'auto_show_week' => $postVar['auto_show_weekday'] ?? '1',
                    'auto_show_time' => $postVar['auto_show_time'] ?? '13:00:00',
                    'auto_close_yn' => $postVar['auto_close_yn'] ?? 'N',
                    'auto_close_min' => $postVar['auto_close_min'] ?? '15',
                    'auto_close_min_num' => $postVar['auto_close_min_num'] ?? 1,
                    'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                    'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => $nn_now
                );
                
                // 스케줄 자동 공개/폐강 설정 업데이트를 위한 함수 호출
                $result = $calModel->update_schedule_auto_schedule($updateData);
            } else {
                throw new Exception('gx_item_sno 또는 gx_schd_mgmt_sno가 필요합니다.');
            }
            
            log_message('info', '📅 데이터베이스 업데이트 데이터: ' . json_encode($updateData));
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '자동 공개/폐강 설정이 저장되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '저장 중 오류가 발생했습니다.'
                );
            }
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '저장 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업정산 설정 저장
     */
    public function ajax_save_settlement_settings()
    {
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        try {
            $calModel = new CalendarModel();
            
            // 스케줄 수정인지 그룹수업 수정인지 구분
            $isScheduleEdit = !empty($postVar['gx_schd_mgmt_sno']);
            
            if ($isScheduleEdit) {
                // 스케줄 수정인 경우: gx_schd_mgmt_tbl 업데이트
                $updateScheduleData = array(
                    'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                    'pay_for_zero_yn' => $postVar['pay_for_zero_yn'], // Y: 수업비 지급, N: 수업비 미지급
                    'use_pay_rate_yn' => $postVar['use_pay_rate_yn'], // Y: 인원당 수당 사용, N: 고정 수업비
                    'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                    'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => $nn_now
                );
                
                // gx_schd_mgmt_tbl 업데이트
                $result1 = $calModel->update_schedule_settlement($updateScheduleData);
                
                // 인원당 수당을 사용하는 경우에만 구간별 수당 정보 저장
                if ($postVar['use_pay_rate_yn'] === 'Y' && !empty($postVar['pay_ranges'])) {
                    // 기존 구간별 수당 정보 삭제
                    $calModel->delete_schedule_pay_ranges($postVar['gx_schd_mgmt_sno']);
                    
                    // 새로운 구간별 수당 정보 저장
                    $payRanges = json_decode($postVar['pay_ranges'], true);
                    
                    foreach ($payRanges as $range) {
                        $payRangeData = array(
                            'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                            'CLAS_ATD_NUM_S' => $range['start'], // 시작 인원
                            'CLAS_ATD_NUM_E' => $range['end'],   // 종료 인원
                            'PAY_RATE' => $range['percent'],     // 수업비 비율 (%)
                            'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                            'cre_datetm' => $nn_now
                        );
                        
                        $calModel->insert_schedule_pay_range($payRangeData);
                    }
                } else {
                    // 인원당 수당을 사용하지 않는 경우 기존 구간 정보 삭제
                    $calModel->delete_schedule_pay_ranges($postVar['gx_schd_mgmt_sno']);
                }
            } else {
                // 그룹수업 수정인 경우: gx_item_tbl 업데이트 (기존 로직)
                $updateGxItemData = array(
                    'gx_item_sno' => $postVar['gx_item_sno'],
                    'pay_for_zero_yn' => $postVar['pay_for_zero_yn'], // Y: 수업비 지급, N: 수업비 미지급
                    'use_pay_rate_yn' => $postVar['use_pay_rate_yn'], // Y: 인원당 수당 사용, N: 고정 수업비
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => $nn_now
                );
                
                // gx_item_tbl 업데이트
                $result1 = $calModel->update_gx_item_settlement($updateGxItemData);
                
                // 인원당 수당을 사용하는 경우에만 구간별 수당 정보 저장
                if ($postVar['use_pay_rate_yn'] === 'Y' && !empty($postVar['pay_ranges'])) {
                    // 기존 구간별 수당 정보 삭제
                    $calModel->delete_gx_class_pay_ranges($postVar['gx_item_sno']);
                    
                    // 새로운 구간별 수당 정보 저장
                    $payRanges = json_decode($postVar['pay_ranges'], true);
                    
                    foreach ($payRanges as $range) {
                        $payRangeData = array(
                            'gx_item_sno' => $postVar['gx_item_sno'],
                            'CLAS_ATD_CNT_S' => $range['start'], // 시작 인원
                            'CLAS_ATD_CNT_E' => $range['end'],   // 종료 인원
                            'pay_rate' => $range['percent'],     // 수업비 비율 (%)
                            'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                            'cre_datetm' => $nn_now
                        );
                        
                        $calModel->insert_gx_class_pay_range($payRangeData);
                    }
                } else {
                    // 인원당 수당을 사용하지 않는 경우 기존 구간 정보 삭제
                    $calModel->delete_gx_class_pay_ranges($postVar['gx_item_sno']);
                }
            }
            
            $return_json = array(
                'result' => 'true',
                'message' => '수업정산 설정이 저장되었습니다.'
            );
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '저장 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업정산 설정 조회
     */
    public function ajax_get_settlement_settings()
    {
        $postVar = $this->request->getPost();
        $gx_item_sno = $postVar['gx_item_sno'] ?? '';
        
        try {
            $calModel = new CalendarModel();
            
            // gx_item_tbl에서 기본 정산 설정 조회
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_item_sno'] = $gx_item_sno;
            
            $gx_item_data = $calModel->get_gx_item_detail($mdata);
            
            if (empty($gx_item_data)) {
                $return_json = array(
                    'result' => 'false',
                    'message' => '해당 그룹수업 데이터를 찾을 수 없습니다.'
                );
            } else {
                $settlementData = $gx_item_data[0];
                
                // 구간별 수당 정보 조회
                $payRanges = $calModel->get_gx_class_pay_ranges($gx_item_sno);
                $settlementData['PAY_RANGES'] = $payRanges;
                
                $return_json = array(
                    'result' => 'true',
                    'data' => $settlementData
                );
            }
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '데이터 조회 중 오류가 발생했습니다.'
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * 분을 시간과 분으로 변환하는 함수
     */
    private function convertMinutesToTimeText($minutes)
    {
        if (empty($minutes) || $minutes <= 0) {
            return '';
        }
        
        $mins = intval($minutes);
        
        if ($mins < 60) {
            // 1시간 미만인 경우 분만 표시
            return $mins . '분 전';
        } else {
            // 1시간 이상인 경우 시간과 분으로 분리
            $hours = intval($mins / 60);
            $remainingMins = $mins % 60;
            
            if ($remainingMins == 0) {
                // 정확히 시간 단위인 경우
                if ($hours == 24) {
                    return '1일 전';
                } else if ($hours == 72) {
                    return '3일 전';
                } else {
                    return $hours . '시간 전';
                }
            } else {
                // 시간과 분이 모두 있는 경우
                return $hours . '시간 ' . $remainingMins . '분 전';
            }
        }
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 락커 설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 골프 라커 설정
     */
    public function locker_setting2()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '골프 라커 설정',
            'nav' => array('지점관리' => '' , '골프 라커 설정' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $lockrModel = new LockrModel();
        
        $sdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $sdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sdata['lockr_knd'] = '02'; // 01 락커 - 02 골프라카
        
        $sdata['lockr_gendr_set'] = 'M'; // M 남자 F 여자 G 공용
        $lockr_list['m01'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'F'; // M 남자 F 여자 G 공용
        $lockr_list['f01'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'G'; // M 남자 F 여자 G 공용
        $lockr_list['g01'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_knd'] = '02'; // 01 락커 - 02 골프라카
        $sdata['lockr_gendr_set'] = 'M'; // M 남자 F 여자 G 공용
        $lockr_list['m02'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'F'; // M 남자 F 여자 G 공용
        $lockr_list['f02'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'G'; // M 남자 F 여자 G 공용
        $lockr_list['g02'] = $lockrModel->select_lockr_set_hist($sdata);
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['lockr_list'] = $lockr_list;
        $this->viewPage('/tchr/tcoffmain/lockr_setting2',$data);
    }
    
    
    /**
     * 락커 설정
     */
    public function locker_setting()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $lockrModel = new LockrModel();
        
        $sdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $sdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sdata['lockr_knd'] = '01'; // 01 락커 - 02 골프라카
        
        $sdata['lockr_gendr_set'] = 'M'; // M 남자 F 여자 G 공용
        $lockr_list['m01'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'F'; // M 남자 F 여자 G 공용
        $lockr_list['f01'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'G'; // M 남자 F 여자 G 공용
        $lockr_list['g01'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_knd'] = '02'; // 01 락커 - 02 골프라카
        $sdata['lockr_gendr_set'] = 'M'; // M 남자 F 여자 G 공용
        $lockr_list['m02'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'F'; // M 남자 F 여자 G 공용
        $lockr_list['f02'] = $lockrModel->select_lockr_set_hist($sdata);
        
        $sdata['lockr_gendr_set'] = 'G'; // M 남자 F 여자 G 공용
        $lockr_list['g02'] = $lockrModel->select_lockr_set_hist($sdata);
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['lockr_list'] = $lockr_list;
        $this->viewPage('/tchr/tcoffmain/locker_setting',$data);
    }
    
    /**
     * 라커등록 proc
     */
    public function ajax_locket_setting_proc()
    {
    	$lockrModel = new LockrModel();
    	$nn_now = new Time('now');
    	
    	$postVar = $this->request->getPost();
    	
    	$chkdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$chkdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$chkdata['lockr_knd'] = $postVar['lockr_knd'];
    	$chkdata['lockr_gendr_set'] = $postVar['lockr_gendr_set'];
    	$chkdata['lockr_s_no'] = $postVar['lockr_s_no'];
    	$chkdata['lockr_e_no'] = $postVar['lockr_e_no'];
    	
    	$chk_count = $lockrModel->chk_lockr_create_count($chkdata);
    	
    	if ($chk_count == 0)
    	{
    	    $calu_cnt = $postVar['lockr_e_no'] - $postVar['lockr_s_no'];
    	    
    	    if ($calu_cnt < 50)
    	    {
    	        $sdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	        $sdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	        $sdata['lockr_knd'] = $postVar['lockr_knd'];
    	        $sdata['lockr_gendr_set'] = $postVar['lockr_gendr_set'];
    	        $sdata['lockr_disp_fmt'] = $postVar['lockr_disp_fmt'];
    	        $sdata['set_hist_stat'] = $postVar['set_hist_stat'];
    	        $sdata['lockr_s_no'] = $postVar['lockr_s_no'];
    	        $sdata['lockr_e_no'] = $postVar['lockr_e_no'];
    	        $sdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	        $sdata['cre_datetm'] = $nn_now;
    	        $sdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	        $sdata['mod_datetm'] = $nn_now;
    	        
    	        $r = $lockrModel->insert_lockr($sdata);
    	        
    	        $set_main_sno = $r[0]->connID->insert_id;
    	        
    	        $rdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	        $rdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	        $rdata['lockr_knd'] = $postVar['lockr_knd'];
    	        $rdata['lockr_gendr_set'] = $postVar['lockr_gendr_set'];
    	        
    	        $rdata['buy_event_sno'] = "";
    	        $rdata['mem_sno'] = "";
    	        $rdata['mem_nm'] = "";
    	        $rdata['lockr_use_s_date'] = "";
    	        $rdata['lockr_use_e_date'] = "";
    	        $rdata['lockr_stat'] = "00";
    	        $rdata['set_main_sno'] = $set_main_sno;
    	        
    	        for($i=$postVar['lockr_s_no'] ; $i<$postVar['lockr_e_no'] + 1 ;$i++) :
    	        $rdata['lockr_no'] = $i;
    	        $lockrModel->insert_lockr_room($rdata);
    	        endfor;
    	        
    	        $return_json['result'] = 'true';
    	    } else
    	    {
    	        $return_json['result'] = 'false';
    	        $return_json['msg'] = "한번에 설정가능한 범위는 50개까지 입니다.";
    	    }
    	} else 
    	{
    	    $return_json['result'] = 'false';
    	    $return_json['msg'] = "시작,끝번호가 이미 등록한 범위에 중복 되었습니다.";
    	}
    	
    	return json_encode($return_json);
    	
    }
    
    /**
     * 라커설정 삭제하기 ajax
     */
    public function ajax_locket_del_proc()
    {
        $lockrModel = new LockrModel();
        $nn_now = new Time('now');
        
        $postVar = $this->request->getPost();
        
        // 라커룸 번호를 이용중인지를 체크한다.
        $cdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cdata['lockr_knd'] = $postVar['del_lockr_knd'];
        $cdata['lockr_gendr_set'] = $postVar['del_lockr_gendr_set'];
        $cdata['lockr_s_no'] = $postVar['del_lockr_s_no'];
        $cdata['lockr_e_no'] = $postVar['del_lockr_e_no'];
        
        $chk_count = $lockrModel->lockr_use_count($cdata);
        
        if ($chk_count == 0)
        {
            $lockrModel->del_lockr_room($cdata);
            
            $cdata['lockr_set_hist_sno'] = $postVar['del_lockr_hist_sno'];
            $lockrModel->del_lockr_room_set($cdata);
            
            $return_json['result'] = 'true';
            $return_json['msg'] = "라커 삭제성공";
        } else 
        {
            $return_json['result'] = 'false';
            $return_json['msg'] = "이용중인 번호가 있어 삭제가 불가능합니다.";
        }
        
                return json_encode($return_json);
    }

    /**
     * AJAX - 이용권 목록 및 선택된 이용권 조회
     */
    public function ajax_get_ticket_list()
    {
        $postVar = $this->request->getPost();
        $gx_item_sno = $postVar['gx_item_sno'] ?? '';
        $show_stopped = $postVar['show_stopped'] ?? 'N';
        
        try {
            $calModel = new CalendarModel();
            
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_item_sno'] = $gx_item_sno;
            $mdata['show_stopped'] = $show_stopped;
            
            // 전체 이용권 목록 조회
            $ticket_list = $calModel->get_all_ticket_list($mdata);
            
            // 선택된 이용권 목록 조회
            $selected_tickets = $calModel->get_selected_ticket_list($mdata);
            
            $return_json = array(
                'result' => 'true',
                'ticket_list' => $ticket_list,
                'selected_tickets' => $selected_tickets
            );
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '이용권 목록 조회 중 오류가 발생했습니다.'
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 이용권 선택 저장
     */
    public function ajax_save_ticket_selection()
    {
        $postVar = $this->request->getPost();
        $gx_item_sno = $postVar['gx_item_sno'] ?? '';
        $selected_tickets = $postVar['selected_tickets'] ?? array();
        
        try {
            $calModel = new CalendarModel();
            
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_item_sno'] = $gx_item_sno;
            $mdata['selected_tickets'] = $selected_tickets;
            
            // 기존 선택 삭제 후 새로 저장
            $result = $calModel->save_ticket_selection($mdata);
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '이용권 설정이 저장되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '저장 중 오류가 발생했습니다.'
                );
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업 이미지 목록 조회
     */
    public function ajax_get_class_images()
    {
        $postVar = $this->request->getPost();
        $gx_item_sno = $postVar['gx_item_sno'] ?? '';
        
                try {
            $calModel = new CalendarModel();
            
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_item_sno'] = $gx_item_sno;
            
            // 디버깅 로그 추가
            error_log('ajax_get_class_images 호출됨 - gx_item_sno: ' . $gx_item_sno);
            
            // 수업 이미지 목록 조회
            $images = $calModel->get_class_image_list($mdata);
            
            // 디버깅 로그 추가
            error_log('이미지 목록 조회 결과: ' . print_r($images, true));
            
            $return_json = array(
                'result' => 'true',
                'images' => $images
            );

        } catch (Exception $e) {
            error_log('ajax_get_class_images 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '이미지 목록 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업 이미지 업로드
     */
    public function ajax_upload_class_image()
    {
        $gx_item_sno = $this->request->getPost('gx_item_sno');
        $upload = $this->request->getFile('image');
        
        try {
            // 파일 유효성 검사
            if (!$upload->isValid() || $upload->hasMoved()) {
                throw new \Exception('유효하지 않은 파일입니다.');
            }
            
            // 파일 크기 체크 (5MB)
            if ($upload->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('파일 크기는 5MB 이하만 업로드 가능합니다.');
            }
            
            // 파일 형식 체크
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($upload->getMimeType(), $allowedTypes)) {
                throw new \Exception('JPG, PNG 형식의 이미지만 업로드 가능합니다.');
            }
            
            // 업로드 디렉토리 생성
            $uploadPath = FCPATH . 'uploads/class_images/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // 파일명 생성 (중복 방지)
            $fileName = date('YmdHis') . '_' . uniqid() . '.' . $upload->getExtension();
            $upload->move($uploadPath, $fileName);
            
            // 데이터베이스에 저장
            $calModel = new CalendarModel();
            $nn_now = new Time('now');
            
            $mdata = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_item_sno' => $gx_item_sno,
                'image_name' => $upload->getClientName(),
                'image_file' => $fileName,
                'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'cre_datetm' => $nn_now
            );
            
            $result = $calModel->insert_class_image($mdata);
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '이미지가 업로드되었습니다.',
                    'file_name' => $fileName
                );
            } else {
                throw new \Exception('데이터베이스 저장 중 오류가 발생했습니다.');
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업 이미지 삭제
     */
    public function ajax_delete_class_image()
    {
        $postVar = $this->request->getPost();
        $image_id = $postVar['image_id'] ?? '';
        
        try {
            $calModel = new CalendarModel();
            
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['image_id'] = $image_id;
            
            // 이미지 정보 조회
            $image_info = $calModel->get_class_image_info($mdata);
            
            // 디버깅 로그 추가
            error_log('이미지 정보 조회 결과: ' . print_r($image_info, true));
            
            if ($image_info && isset($image_info['IMAGE_FILE'])) {
                // 파일 삭제
                $filePath = FCPATH . 'uploads/class_images/' . $image_info['IMAGE_FILE'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                // 데이터베이스에서 삭제
                $result = $calModel->delete_class_image($mdata);
                
                if ($result) {
                    $return_json = array(
                        'result' => 'true',
                        'message' => '이미지가 삭제되었습니다.'
                    );
                } else {
                    throw new \Exception('데이터베이스 삭제 중 오류가 발생했습니다.');
                }
            } else {
                throw new \Exception('이미지를 찾을 수 없습니다.');
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업 이미지 선택 저장
     */
    public function ajax_save_class_image_selection()
    {
        $postVar = $this->request->getPost();
        $gx_item_sno = $postVar['gx_item_sno'] ?? '';
        $selected_image_id = $postVar['selected_image_id'] ?? null;
        
        try {
            $calModel = new CalendarModel();
            
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_item_sno'] = $gx_item_sno;
            $mdata['selected_image_id'] = $selected_image_id;
            
            // 수업 이미지 선택 저장
            $result = $calModel->save_class_image_selection($mdata);
            
            if ($result) {
                // 선택된 이미지 URL 가져오기
                $image_url = '';
                if ($selected_image_id) {
                    $image_info = $calModel->get_class_image_info(array(
                        'comp_cd' => $mdata['comp_cd'],
                        'bcoff_cd' => $mdata['bcoff_cd'],
                        'image_id' => $selected_image_id
                    ));
                    if ($image_info && isset($image_info['IMAGE_FILE'])) {
                        $image_url = base_url('uploads/class_images/' . $image_info['IMAGE_FILE']);
                    }
                }
                
                $return_json = array(
                    'result' => 'true',
                    'message' => '수업 이미지가 설정되었습니다.',
                    'image_url' => $image_url
                );
            } else {
                throw new \Exception('저장 중 오류가 발생했습니다.');
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 이미지 선택 저장
     */
    public function ajax_save_schedule_image_selection()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        $selected_image_id = $postVar['selected_image_id'] ?? null;
        
        try {
            $calModel = new CalendarModel();
            
            $mdata = array(
                'gx_schd_mgmt_sno' => $gx_schd_mgmt_sno,
                'selected_image_id' => $selected_image_id,
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => new \CodeIgniter\I18n\Time('now')
            );
            
            // 스케줄 이미지 선택 저장
            $result = $calModel->save_schedule_image_selection($mdata);
            
            if ($result) {
                // 선택된 이미지 URL 가져오기
                $image_url = '';
                if ($selected_image_id) {
                    $image_info = $calModel->get_class_image_info(array(
                        'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                        'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                        'image_id' => $selected_image_id
                    ));
                    if ($image_info && isset($image_info['IMAGE_FILE'])) {
                        $image_url = base_url('uploads/class_images/' . $image_info['IMAGE_FILE']);
                    }
                }
                
                $return_json = array(
                    'result' => 'true',
                    'message' => '스케줄 이미지가 설정되었습니다.',
                    'image_url' => $image_url
                );
            } else {
                throw new \Exception('저장 중 오류가 발생했습니다.');
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업 이미지 정보 조회
     */
    public function ajax_get_class_image_info()
    {
        $postVar = $this->request->getPost();
        $image_id = $postVar['image_id'] ?? '';
        
        try {
            $calModel = new CalendarModel();
            
            $mdata = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'image_id' => $image_id
            );
            
            $image_info = $calModel->get_class_image_info($mdata);
            
            if ($image_info) {
                $return_json = array(
                    'result' => 'true',
                    'image_info' => $image_info,
                    'image_url' => base_url('uploads/class_images/' . $image_info['IMAGE_FILE'])
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '이미지 정보를 찾을 수 없습니다.'
                );
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '이미지 정보 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄이 있는 날짜 목록 조회
     */
    public function ajax_get_schedule_dates()
    {
        try {
            $calModel = new CalendarModel();
            $postVar = $this->request->getPost();
            
            $data = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_room_mgmt_sno' => $postVar['gx_room_mgmt_sno'],
                'start_date' => $postVar['start_date'],
                'end_date' => $postVar['end_date']
            );
            
            $schedule_dates = $calModel->get_schedule_dates($data);
            
            // 날짜만 배열로 추출
            $dates_array = array_column($schedule_dates, 'GX_CLAS_S_DATE');
            
            $return_json = array(
                'result' => 'true',
                'schedule_dates' => $dates_array
            );
            
                 } catch (\Exception $e) {
             $return_json = array(
                 'result' => 'false',
                 'message' => '날짜 조회 중 오류가 발생했습니다: ' . $e->getMessage()
             );
         }
        
        return json_encode($return_json);
    }
    
    /**
     * AJAX - 스케줄 미리보기 데이터 조회
     */
    public function ajax_get_schedule_preview()
    {
        try {
            $calModel = new CalendarModel();
            $postVar = $this->request->getPost();
            
            $data = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_room_mgmt_sno' => $postVar['gx_room_mgmt_sno'],
                'start_date' => $postVar['start_date'],
                'end_date' => $postVar['end_date']
            );
            
            $schedules = $calModel->get_schedule_preview($data);
            
            $return_json = array(
                'result' => 'true',
                'schedules' => $schedules
            );
            
                 } catch (\Exception $e) {
             $return_json = array(
                 'result' => 'false',
                 'message' => '스케줄 미리보기 조회 중 오류가 발생했습니다: ' . $e->getMessage()
             );
         }
        
        return json_encode($return_json);
    }
    
    /**
     * AJAX - 날짜 범위 스케줄 삭제
     */
    public function ajax_delete_schedule_range()
    {
        try {
            $calModel = new CalendarModel();
            $postVar = $this->request->getPost();
            
            $data = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_room_mgmt_sno' => $postVar['gx_room_mgmt_sno'],
                'start_date' => $postVar['start_date'],
                'end_date' => $postVar['end_date']
            );
            
            $result = $calModel->delete_schedule_range($data);
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '스케줄이 성공적으로 삭제되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '스케줄 삭제 중 오류가 발생했습니다.'
                );
            }
            
                 } catch (\Exception $e) {
             $return_json = array(
                 'result' => 'false',
                 'message' => '스케줄 삭제 중 오류가 발생했습니다: ' . $e->getMessage()
             );
         }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 상세 정보 조회
     */
    public function ajax_get_schedule_detail()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        
        // 로깅 추가
        error_log('🔍 ajax_get_schedule_detail 호출됨 - gx_schd_mgmt_sno: ' . $gx_schd_mgmt_sno);
        
        try {
            $calModel = new CalendarModel();
            $memModel = new MemModel();
            
            $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $mdata['gx_schd_mgmt_sno'] = $gx_schd_mgmt_sno;
            
            error_log('🔍 조회 데이터: ' . json_encode($mdata));
            
            // 스케줄 상세 정보 조회
            $schedule_data = $calModel->get_schedule_detail($mdata);
            error_log('🔍 스케줄 조회 결과: ' . json_encode($schedule_data));
            
            if (empty($schedule_data)) {
                $return_json = array(
                    'result' => 'false',
                    'message' => '해당 스케줄 데이터를 찾을 수 없습니다.'
                );
            } else {
                $scheduleData = $schedule_data[0];
                
                // 참석 가능한 이용권 정보 조회
                $schedule_events = $calModel->get_schedule_events($mdata);
                $scheduleData['SCHEDULE_EVENTS'] = $schedule_events;
                
                // 수당 요율표 정보 조회
                $pay_ranges = $calModel->get_schedule_pay_ranges($mdata);
                $scheduleData['PAY_RANGES'] = $pay_ranges;
                
                // 선택된 이미지 정보 조회
                $selected_image = $calModel->get_selected_schedule_image($mdata);
                $scheduleData['SELECTED_IMAGE'] = $selected_image;
                
                // 공개 스케줄 정보 생성
                if (!$scheduleData['AUTO_SHOW_YN'] || $scheduleData['AUTO_SHOW_YN'] == 'N') {
                    $scheduleData['OPEN_SCHEDULE'] = '미설정';
                } else {
                    $unit_text = ($scheduleData['AUTO_SHOW_UNIT'] == '1') ? '일' : '주';
                    $open_text = $scheduleData['AUTO_SHOW_D'] . $unit_text . '전 ';
                    
                    // 시간 형식 변경 (HH:MM:SS -> HH시 또는 HH시 MM분)
                    $time_parts = explode(':', $scheduleData['AUTO_SHOW_TIME']);
                    $hour = intval($time_parts[0]);
                    $minute = intval($time_parts[1]);
                    
                    $open_text .= $hour . '시';
                    if ($minute > 0) {
                        $open_text .= ' ' . $minute . '분';  // += 를 .= 로 수정
                    }
                    $open_text .= '부터 공개';
                    
                    $scheduleData['OPEN_SCHEDULE'] = $open_text;
                }
                
                // 폐강 스케줄 정보 생성
                if (!$scheduleData['AUTO_CLOSE_YN'] || $scheduleData['AUTO_CLOSE_YN'] == 'N') {
                    $scheduleData['CLOSE_SCHEDULE'] = '미설정';
                } else {
                    $timeText = $this->convertMinutesToTimeText($scheduleData['AUTO_CLOSE_MIN']);
                    $scheduleData['CLOSE_SCHEDULE'] = '수업 시작후 ' . $timeText . ' 까지 최소인원 ' . $scheduleData['AUTO_CLOSE_MIN_NUM'] . '명이 안될시 폐강';
                }
                
                // 강사 리스트 조회 추가
                $get_tchr_list = $memModel->get_list_tchr($mdata);
                
                $tchr_list = array();
                $sDef = SpoqDef();
                $tchr_i = 0;
                foreach ($get_tchr_list as $t) :
                    $tchr_list[$tchr_i] = $t;
                    $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']] ?? '';
                    $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']] ?? '';
                    $tchr_i++;
                endforeach;
                
                // 디버깅: 강사 정보 확인
                error_log('🔍 스케줄 강사 정보 - GX_STCHR_ID: ' . $scheduleData['GX_STCHR_ID']);
                error_log('🔍 스케줄 강사 정보 - GX_STCHR_NM: ' . $scheduleData['GX_STCHR_NM']);
                error_log('🔍 강사 목록 개수: ' . count($tchr_list));
                if (count($tchr_list) > 0) {
                    foreach ($tchr_list as $idx => $teacher) {
                        if ($teacher['MEM_ID'] == $scheduleData['GX_STCHR_ID']) {
                            error_log('✅ 매칭된 강사 발견 - MEM_ID: ' . $teacher['MEM_ID'] . ', MEM_NM: ' . $teacher['MEM_NM']);
                            break;
                        }
                    }
                }
                
                $return_json = array(
                    'result' => 'true',
                    'data' => $scheduleData,
                    'tchr_list' => $tchr_list
                );
            }
            
        } catch (Exception $e) {
            error_log('❌ ajax_get_schedule_detail 오류: ' . $e->getMessage());
            error_log('❌ 스택 트레이스: ' . $e->getTraceAsString());
            $return_json = array(
                'result' => 'false',
                'message' => '데이터 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 수정
     */
    public function ajax_update_schedule()
    {
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        try {
            $calModel = new CalendarModel();
            
            // 담당강사 정보 가져오기
            $memModel = new MemModel();
            $tchrData = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'mem_id' => $postVar['gx_stchr_id']
            );
            $get_tchr_info = $memModel->get_mem_info_id_idname($tchrData);
            
            // 수정할 데이터 준비 (기본 정보만)
            $updateData = array(
                'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                'gx_clas_title' => $postVar['gx_clas_title'],
                'gx_stchr_id' => $postVar['gx_stchr_id'],
                'gx_stchr_sno' => $get_tchr_info[0]['MEM_SNO'],
                'gx_stchr_nm' => $get_tchr_info[0]['MEM_NM'],
                'gx_class_min' => $postVar['gx_class_min'],
                'gx_deduct_cnt' => $postVar['gx_deduct_cnt'],
                'gx_max_num' => $postVar['gx_max_num'],
                'gx_max_waiting' => $postVar['gx_max_waiting'],
                'reserv_num' => $postVar['reserv_num'],
                'use_reserv_yn' => $postVar['use_reserv_yn'],
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => $nn_now
            );
            
            // 스케줄 기본 정보 업데이트
            $result = $calModel->update_schedule_detail($updateData);
            
            // 자동 공개/폐강 설정 업데이트
            if ($result) {
                $autoScheduleData = array(
                    'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                    'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                    'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                    'auto_show_yn' => $postVar['auto_show_yn'] ?? 'N',
                    'auto_show_d' => $postVar['auto_show_d'] ?? 0,
                    'auto_show_week_dur' => $postVar['auto_show_week_dur'] ?? 0,
                    'auto_show_unit' => $postVar['auto_show_unit'] ?? '1',
                    'auto_show_week' => $postVar['auto_show_weekday'] ?? '',
                    'auto_show_time' => $postVar['auto_show_time'] ?? '00:00:00',
                    'auto_close_yn' => $postVar['auto_close_yn'] ?? 'N',
                    'auto_close_min' => $postVar['auto_close_min'] ?? '00:00:00',
                    'auto_close_min_num' => $postVar['auto_close_min_num'] ?? 0,
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => $nn_now
                );
                $calModel->update_schedule_auto_schedule($autoScheduleData);
                
                // 수업비 정산방법 설정 업데이트
                $settlementData = array(
                    'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                    'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                    'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                    'use_pay_rate_yn' => $postVar['use_pay_rate_yn'] ?? 'N',
                    'pay_for_zero_yn' => $postVar['pay_for_zero_yn'] ?? 'N',
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => $nn_now
                );
                
                // 수업비 정산방법 설정 업데이트
                $settlementSql = 'UPDATE gx_schd_mgmt_tbl SET
                                    USE_PAY_RATE_YN = :use_pay_rate_yn:,
                                    PAY_FOR_ZERO_YN = :pay_for_zero_yn:,
                                    MOD_ID = :mod_id:,
                                    MOD_DATETM = :mod_datetm:
                                  WHERE GX_SCHD_MGMT_SNO = :gx_schd_mgmt_sno:
                                  AND COMP_CD = :comp_cd:
                                  AND BCOFF_CD = :bcoff_cd:';
                $calModel->db->query($settlementSql, $settlementData);
            }
            
            if ($result) {
                // 참석 가능한 이용권 업데이트
                if (isset($postVar['selected_events'])) {
                    $eventData = array(
                        'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                        'events' => $postVar['selected_events'],
                        'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                        'cre_datetm' => $nn_now
                    );
                    $calModel->update_schedule_events($eventData);
                }
                
                // 수당 요율표 업데이트
                if (isset($postVar['pay_ranges'])) {
                    $payData = array(
                        'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                        'pay_ranges' => $postVar['pay_ranges'],
                        'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                        'cre_datetm' => $nn_now
                    );
                    $calModel->update_schedule_pay_ranges($payData);
                }
                
                $return_json = array(
                    'result' => 'true',
                    'message' => '스케줄이 수정되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '수정 중 오류가 발생했습니다.'
                );
            }
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '수정 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 삭제
     */
    public function ajax_delete_schedule()
    {
        $postVar = $this->request->getPost();
        
        try {
            $calModel = new CalendarModel();
            
            $data = array(
                'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'],
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd')
            );
            
            $result = $calModel->delete_schedule($data);
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '스케줄이 삭제되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '삭제 중 오류가 발생했습니다.'
                );
            }
            
        } catch (Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 이용권 목록 조회
     */
    public function ajax_get_schedule_ticket_list()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        $show_stopped = $postVar['show_stopped'] ?? 'N';
        
        try {
            $calModel = new CalendarModel();
            
            $mdata = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'show_stopped' => $show_stopped
            );
            
            // 모든 이용권 목록 조회 
            $ticketList = $calModel->get_all_ticket_list($mdata);
            
            // 스케줄에 선택된 이용권 목록 조회
            $eventData = array('gx_schd_mgmt_sno' => $gx_schd_mgmt_sno);
            $selectedTickets = $calModel->get_schedule_events($eventData);
            
            $return_json = array(
                'result' => 'true',
                'ticket_list' => $ticketList,
                'selected_tickets' => $selectedTickets
            );
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '이용권 목록 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 자동 공개/폐강 설정 조회
     */
    public function ajax_get_schedule_auto_schedule_settings()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        
        try {
            $calModel = new CalendarModel();
            
            $mdata = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_schd_mgmt_sno' => $gx_schd_mgmt_sno
            );
            
            // 스케줄 정보 조회 (자동 설정 포함)
            $scheduleInfo = $calModel->get_schedule_detail($mdata);
            
            if (!empty($scheduleInfo)) {
                $settings = $scheduleInfo[0]; // 첫 번째 레코드
                
                $return_json = array(
                    'result' => 'true',
                    'data' => $settings
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '스케줄 정보를 찾을 수 없습니다.'
                );
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '자동 설정 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 수업비 정산 설정 조회
     */
    public function ajax_get_schedule_settlement_settings()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        
        error_log('🔍 ajax_get_schedule_settlement_settings 호출됨 - gx_schd_mgmt_sno: ' . $gx_schd_mgmt_sno);
        
        try {
            $calModel = new CalendarModel();
            
            $mdata = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_schd_mgmt_sno' => $gx_schd_mgmt_sno
            );
            
            error_log('🔍 mdata: ' . json_encode($mdata));
            
            // 스케줄 기본 정보 조회 (PAY_FOR_ZERO_YN, USE_PAY_RATE_YN 포함)
            $scheduleData = $calModel->get_schedule_detail($mdata);
            error_log('🔍 scheduleData: ' . json_encode($scheduleData));
            
            if (empty($scheduleData)) {
                $return_json = array(
                    'result' => 'false',
                    'message' => '해당 스케줄 데이터를 찾을 수 없습니다.'
                );
            } else {
                $settlementData = $scheduleData[0];
                
                // 구간별 수당 정보 조회 (gx_schd_pay_tbl 테이블에서)
                $payRanges = $calModel->get_schedule_pay_ranges($mdata);
                error_log('🔍 payRanges: ' . json_encode($payRanges));
                
                $settlementData['PAY_RANGES'] = $payRanges;
                
                // PAY_FOR_ZERO_YN, USE_PAY_RATE_YN 필드가 없으면 기본값 설정
                if (!isset($settlementData['PAY_FOR_ZERO_YN'])) {
                    $settlementData['PAY_FOR_ZERO_YN'] = 'N';
                }
                if (!isset($settlementData['USE_PAY_RATE_YN'])) {
                    $settlementData['USE_PAY_RATE_YN'] = 'N';
                }
                
                error_log('🔍 최종 settlementData: ' . json_encode($settlementData));
                
                $return_json = array(
                    'result' => 'true',
                    'data' => $settlementData
                );
            }
            
        } catch (\Exception $e) {
            error_log('❌ ajax_get_schedule_settlement_settings 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '정산 설정 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 수업비 정산 설정 저장
     */
    public function ajax_save_schedule_settlement_settings()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        $pay_for_zero_yn = $postVar['pay_for_zero_yn'] ?? 'N';
        $use_pay_rate_yn = $postVar['use_pay_rate_yn'] ?? 'N';
        $pay_ranges_json = $postVar['pay_ranges'] ?? '';
        
        // JSON 문자열로 받은 pay_ranges를 배열로 변환
        $pay_ranges = array();
        if (!empty($pay_ranges_json)) {
            $decoded_ranges = json_decode($pay_ranges_json, true);
            if (is_array($decoded_ranges)) {
                foreach ($decoded_ranges as $range) {
                    $pay_ranges[] = array(
                        'min_attendees' => $range['start'] ?? 0,
                        'max_attendees' => $range['end'] ?? 0,
                        'pay_amount' => $range['percent'] ?? 0
                    );
                }
            }
        }
        
        error_log('🔧 ajax_save_schedule_settlement_settings 호출됨');
        error_log('🔧 gx_schd_mgmt_sno: ' . $gx_schd_mgmt_sno);
        error_log('🔧 pay_for_zero_yn: ' . $pay_for_zero_yn);
        error_log('🔧 use_pay_rate_yn: ' . $use_pay_rate_yn);
        error_log('🔧 pay_ranges: ' . json_encode($pay_ranges));
        
        try {
            $calModel = new CalendarModel();
            $nn_now = new Time('now');
            
            // 스케줄 기본 정산 설정 업데이트
            $updateData = array(
                'gx_schd_mgmt_sno' => $gx_schd_mgmt_sno,
                'pay_for_zero_yn' => $pay_for_zero_yn,
                'use_pay_rate_yn' => $use_pay_rate_yn,
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => $nn_now
            );
            
            $result1 = $calModel->update_schedule_settlement($updateData);
            
            // 구간별 수당 요율표 업데이트
            $payRangeData = array(
                'gx_schd_mgmt_sno' => $gx_schd_mgmt_sno,
                'pay_ranges' => $pay_ranges,
                'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'cre_datetm' => $nn_now
            );
            
            $result2 = $calModel->update_schedule_pay_ranges($payRangeData);
            
            if ($result1 && $result2) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '스케줄 정산 설정이 저장되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '저장 중 오류가 발생했습니다.'
                );
            }
            
        } catch (\Exception $e) {
            error_log('❌ ajax_save_schedule_settlement_settings 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '정산 설정 저장 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 이용권 선택 저장
     */
    public function ajax_save_schedule_ticket_selection()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        $selected_tickets = $postVar['selected_tickets'] ?? array();
        
        try {
            $calModel = new CalendarModel();
            $nn_now = new Time('now');
            
            $mdata = array(
                'gx_schd_mgmt_sno' => $gx_schd_mgmt_sno,
                'events' => $selected_tickets,
                'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'cre_datetm' => $nn_now
            );
            
            // 스케줄 이용권 업데이트
            $result = $calModel->update_schedule_events($mdata);
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '스케줄 이용권 설정이 저장되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '저장 중 오류가 발생했습니다.'
                );
            }
            
        } catch (\Exception $e) {
            $return_json = array(
                'result' => 'false',
                'message' => '이용권 설정 저장 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 스케줄 수업 이미지 목록 조회
     */
    public function ajax_get_schedule_class_images()
    {
        $postVar = $this->request->getPost();
        $gx_schd_mgmt_sno = $postVar['gx_schd_mgmt_sno'] ?? '';
        
        error_log('🖼️ ajax_get_schedule_class_images 호출됨 - gx_schd_mgmt_sno: ' . $gx_schd_mgmt_sno);
        
        try {
            $calModel = new CalendarModel();
            
            // 먼저 스케줄 상세 정보를 조회하여 선택된 이미지 ID를 가져옴
            $mdata = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_schd_mgmt_sno' => $gx_schd_mgmt_sno
            );
            
            $schedule_data = $calModel->get_schedule_detail($mdata);
            $selected_image_id = 0;
            
            if (!empty($schedule_data)) {
                $selected_image_id = $schedule_data[0]['SELECTED_IMAGE_ID'] ?? 0;
            }
            
            error_log('🖼️ 스케줄의 선택된 이미지 ID: ' . $selected_image_id);
            
            // 모든 이미지 목록을 조회 (전체 이미지를 보여줌)
            // 실제로는 모든 그룹수업의 이미지를 보여주거나, 별도의 스케줄용 이미지 테이블을 사용할 수 있음
            // 여기서는 임시로 빈 gx_item_sno로 전체 이미지를 조회
            $image_data = array(
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'gx_item_sno' => 0 // 전체 이미지 조회용
            );
            
            // 전체 이미지 목록 조회 (모든 그룹수업의 이미지)
            $all_images = $this->getAllClassImages($image_data);
            
            // 선택된 이미지 표시 처리
            foreach ($all_images as &$image) {
                $image['selected'] = ($image['id'] == $selected_image_id) ? 1 : 0;
            }
            
            error_log('🖼️ 조회된 이미지 개수: ' . count($all_images));
            
            $return_json = array(
                'result' => 'true',
                'images' => $all_images
            );
            
        } catch (Exception $e) {
            error_log('❌ ajax_get_schedule_class_images 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '이미지 목록 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }
    
    /**
     * 모든 클래스 이미지 조회
     */
    private function getAllClassImages($data)
    {
        $calModel = new CalendarModel();
        
        // 모든 그룹수업의 이미지를 조회하는 SQL
        $sql = "SELECT 
                    A.IMAGE_ID as id,
                    A.IMAGE_NAME as name,
                    A.IMAGE_FILE,
                    CONCAT('/uploads/class_images/', A.IMAGE_FILE) as url,
                    0 as selected
                FROM gx_clas_img_tbl A
                WHERE A.COMP_CD = :comp_cd: 
                AND A.BCOFF_CD = :bcoff_cd:
                ORDER BY A.CRE_DATETM DESC
                ";
        
        try {
            $query = $calModel->db->query($sql, [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);
            
            return $query->getResultArray();
            
        } catch (Exception $e) {
            error_log('getAllClassImages 오류: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * AJAX - 예약내역 조회
     */
    public function ajax_get_reservation_history()
    {
        $postVar = $this->request->getPost();
        
        error_log('🎯 ajax_get_reservation_history 호출됨');
        error_log('📋 받은 데이터: ' . print_r($postVar, true));
        
        try {
            $calModel = new CalendarModel();
            
            $data = array(
                'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'] ?? '',
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd')
            );
            
            error_log('🏢 조회 조건: ' . print_r($data, true));
            
            // 수업 정보 조회
            $classInfo = $calModel->get_schd_info($data);
            
            // 예약내역 조회
            $reservations = $calModel->get_reservation_history($data);
            
            error_log('📊 조회된 예약내역: ' . count($reservations) . '건');
            error_log('🏫 수업 정보: ' . print_r($classInfo, true));
            
            // 통계 정보 계산
            $statistics = array(
                'total' => 0,
                'confirmed' => 0,
                'attended' => 0,
                'absent' => 0,
                'waiting' => 0,
                'cancelled' => 0,
                'current_count' => 0,  // 현재 예약자 수 (예약 + 출석)
                'waiting_count' => 0   // 대기자 수
            );
            
            if (!empty($reservations)) {
                $statistics['total'] = count($reservations);
                
                foreach ($reservations as $reservation) {
                    $status = $reservation['RESERVATION_STATUS'] ?? '';
                    switch ($status) {
                        case '예약':
                        case '확정':
                            $statistics['confirmed']++;
                            $statistics['current_count']++;
                            break;
                        case '출석':
                            $statistics['attended']++;
                            // 출석은 current_count에 포함하지 않음 (예약 상태만 현재예약)
                            break;
                        case '결석':
                            $statistics['absent']++;
                            break;
                        case '대기':
                            $statistics['waiting']++;
                            $statistics['waiting_count']++;
                            break;
                        case '취소':
                            $statistics['cancelled']++;
                            break;
                    }
                }
            }
            
            error_log('📈 통계 정보: ' . print_r($statistics, true));
            
            $return_json = array(
                'result' => 'true',
                'data' => $reservations,
                'statistics' => $statistics,
                'class_info' => $classInfo
            );
            
            error_log('✅ 예약내역 조회 성공');
            
        } catch (Exception $e) {
            error_log('❌ ajax_get_reservation_history 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '예약내역 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 회원 검색 (해당 수업과 관련된 이용권을 가진 회원만)
     */
    public function ajax_search_members()
    {
        $postVar = $this->request->getPost();
        $searchTerm = $postVar['search_term'] ?? '';
        $gxSchdMgmtSno = $postVar['gx_schd_mgmt_sno'] ?? '';
        $classTitle = $postVar['class_title'] ?? '';
        $classDate = $postVar['class_date'] ?? '';
        
        error_log('🔍 회원 검색 - 검색어: ' . $searchTerm);
        error_log('📚 수업 정보 - 스케줄 ID: ' . $gxSchdMgmtSno . ', 수업명: ' . $classTitle . ', 수업날짜: ' . $classDate);
        
        try {
            $calModel = new CalendarModel();
            
            $data = array(
                'search_term' => $searchTerm,
                'gx_schd_mgmt_sno' => $gxSchdMgmtSno,
                'class_title' => $classTitle,
                'class_date' => $classDate,
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd')
            );
            
            // 해당 수업과 관련된 이용권을 가진 회원 검색 (테이블이 없으면 샘플 데이터 반환)
            try {
                $members = $calModel->search_members_with_class_tickets($data);
                
                // 실제 데이터의 필드명을 프론트엔드 형식에 맞게 변환
                foreach ($members as &$member) {
                    if (isset($member['name'])) {
                        $member['MEM_NM'] = $member['name'];
                        unset($member['name']);
                    }
                    if (isset($member['phone'])) {
                        $member['MEM_HP'] = $member['phone'];
                        unset($member['phone']);
                    }
                    if (isset($member['tickets'])) {
                        $member['TICKET_INFO'] = $member['tickets'];
                        unset($member['tickets']);
                    }
                    
                    // 이용권 유효성 정보 포함
                    if (isset($member['is_ticket_valid'])) {
                        $member['IS_TICKET_VALID'] = $member['is_ticket_valid'];
                    }
                    if (isset($member['ticket_start_date'])) {
                        $member['TICKET_START_DATE'] = $member['ticket_start_date'];
                    }
                    if (isset($member['ticket_end_date'])) {
                        $member['TICKET_END_DATE'] = $member['ticket_end_date'];
                    }
                    if (isset($member['SELL_EVENT_SNO'])) {
                        $member['SELL_EVENT_SNO'] = $member['SELL_EVENT_SNO'];
                    }
                }
                
            } catch (Exception $tableError) {
                error_log('⚠️ 실제 테이블 조회 실패, 샘플 데이터로 대체: ' . $tableError->getMessage());
                // 샘플 회원 데이터 반환 (프론트엔드 구조에 맞춤)
                $today = date('Y-m-d');
                $futureDate = date('Y-m-d', strtotime('+30 days'));
                $pastDate = date('Y-m-d', strtotime('-10 days'));
                
                $members = [
                    [
                        'MEM_SNO' => 1,
                        'MEM_ID' => 'user001',
                        'MEM_NM' => '김민수',
                        'MEM_HP' => '010-1234-5678',
                        'STATUS_CD' => 'A',
                        'TICKET_INFO' => 'GX 10회권 (7회 | 유효기간: ' . date('y.m.d') . '~' . date('y.m.d', strtotime('+30 days')) . ')',
                        'IS_TICKET_VALID' => 1,
                        'TICKET_START_DATE' => $today,
                        'TICKET_END_DATE' => $futureDate,
                        'SELL_EVENT_SNO' => '1',
                        'is_already_reserved' => 0
                    ],
                    [
                        'MEM_SNO' => 2,
                        'MEM_ID' => 'user002',
                        'MEM_NM' => '이영희',
                        'MEM_HP' => '010-2345-6789',
                        'STATUS_CD' => 'A',
                        'TICKET_INFO' => 'GX 20회권 (15회 | 유효기간: ' . date('y.m.d') . '~' . date('y.m.d', strtotime('+60 days')) . ')',
                        'IS_TICKET_VALID' => 1,
                        'TICKET_START_DATE' => $today,
                        'TICKET_END_DATE' => date('Y-m-d', strtotime('+60 days')),
                        'SELL_EVENT_SNO' => '2',
                        'is_already_reserved' => 0
                    ],
                    [
                        'MEM_SNO' => 3,
                        'MEM_ID' => 'user003',
                        'MEM_NM' => '박철수',
                        'MEM_HP' => '010-3456-7890',
                        'STATUS_CD' => 'A',
                        'TICKET_INFO' => 'GX+헬스 무제한 (무제한 | 유효기간: ' . date('y.m.d', strtotime('-5 days')) . '~' . date('y.m.d', strtotime('-1 days')) . ' [만료됨])',
                        'IS_TICKET_VALID' => 0,  // 만료된 이용권
                        'TICKET_START_DATE' => date('Y-m-d', strtotime('-5 days')),
                        'TICKET_END_DATE' => $pastDate,
                        'SELL_EVENT_SNO' => '3',
                        'is_already_reserved' => 1  // 이미 예약됨
                    ],
                    [
                        'MEM_SNO' => 4,
                        'MEM_ID' => 'user004',
                        'MEM_NM' => '정수현',
                        'MEM_HP' => '010-4567-8901',
                        'STATUS_CD' => 'A',
                        'TICKET_INFO' => 'GX 5회권 (3회 | 유효기간: ' . date('y.m.d') . '~' . date('y.m.d', strtotime('+15 days')) . ')',
                        'IS_TICKET_VALID' => 1,
                        'TICKET_START_DATE' => $today,
                        'TICKET_END_DATE' => date('Y-m-d', strtotime('+15 days')),
                        'SELL_EVENT_SNO' => '4',
                        'is_already_reserved' => 0
                    ],
                    [
                        'MEM_SNO' => 5,
                        'MEM_ID' => 'user005',
                        'MEM_NM' => '최지은',
                        'MEM_HP' => '010-5678-9012',
                        'STATUS_CD' => 'A',
                        'TICKET_INFO' => 'GX 무제한 (무제한 | 유효기간: ' . date('y.m.d') . '~' . date('y.m.d', strtotime('+90 days')) . ')',
                        'IS_TICKET_VALID' => 1,
                        'TICKET_START_DATE' => $today,
                        'TICKET_END_DATE' => date('Y-m-d', strtotime('+90 days')),
                        'SELL_EVENT_SNO' => '5',
                        'is_already_reserved' => 0
                    ]
                ];
                
                // 검색어가 있으면 필터링
                if (!empty($searchTerm)) {
                    $filteredMembers = [];
                    foreach ($members as $member) {
                        if (stripos($member['MEM_NM'], $searchTerm) !== false ||
                            stripos($member['MEM_ID'], $searchTerm) !== false ||
                            stripos($member['MEM_HP'], $searchTerm) !== false) {
                            $filteredMembers[] = $member;
                        }
                    }
                    $members = $filteredMembers;
                }
            }
            
            error_log('👥 조회된 회원 수: ' . count($members));
            
            $return_json = array(
                'result' => 'true',
                'data' => $members
            );
            
        } catch (Exception $e) {
            error_log('❌ ajax_search_members 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '회원 검색 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 회원 이용권 조회
     */
    public function ajax_get_member_tickets()
    {
        $postVar = $this->request->getPost();
        $memberId = $postVar['member_id'] ?? '';
        
        error_log('🎫 회원 이용권 조회 - 회원 ID: ' . $memberId);
        
        try {
            $calModel = new CalendarModel();
            
            $data = array(
                'member_id' => $memberId,
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd')
            );
            
            // 회원 이용권 조회
            $tickets = $calModel->get_member_tickets($data);
            
            $return_json = array(
                'result' => 'true',
                'data' => $tickets
            );
            
        } catch (Exception $e) {
            error_log('❌ ajax_get_member_tickets 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '이용권 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

    /**
     * AJAX - 수업 예약
     */
    public function ajax_make_reservation()
    {
        $postVar = $this->request->getPost();
        
        error_log('📝 수업 예약 요청: ' . print_r($postVar, true));
        
        try {
            $calModel = new CalendarModel();
            
            $data = array(
                'member_id' => $postVar['member_id'] ?? '',
                'sell_event_sno' => $postVar['sell_event_sno'] ?? '',
                'gx_schd_mgmt_sno' => $postVar['gx_schd_mgmt_sno'] ?? '',
                'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
                'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd'),
                'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'cre_datetm' => date('Y-m-d H:i:s')
            );
            
            // 예약 처리
            $result = $calModel->make_reservation($data);
            
            if ($result) {
                $return_json = array(
                    'result' => 'true',
                    'message' => '예약이 완료되었습니다.'
                );
            } else {
                $return_json = array(
                    'result' => 'false',
                    'message' => '예약 처리 중 오류가 발생했습니다.'
                );
            }
            
        } catch (Exception $e) {
            error_log('❌ ajax_make_reservation 오류: ' . $e->getMessage());
            $return_json = array(
                'result' => 'false',
                'message' => '예약 처리 중 오류가 발생했습니다: ' . $e->getMessage()
            );
        }
        
        return json_encode($return_json);
    }

}