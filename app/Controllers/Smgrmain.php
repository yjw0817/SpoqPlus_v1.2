<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Libraries\TreeStateVO;
use App\Libraries\TreeVO;
use App\Libraries\RoleMenuVO;
use App\Models\CateModel;
use App\Models\EventModel;
use App\Models\SadminModel;
use App\Models\TcmgCompanyModel;
use App\Models\MemModel;
use App\Models\MenuModel;
use App\Models\AuthModel;
use App\Models\PgVanSettingsModel;


class Smgrmain extends MainSadminController
{
    /**
     * 어드민 DashBaord
     */
    public function dashboard()
    {

		
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '슈퍼관리자 DashBoard',
            'nav' => array('Dashboard' => '/dashboard'),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/sadmin/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 대분류 사용 설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    public function use_1rd_manage()
	{
		// =======================================================================
		// 선언부
		// =======================================================================
		$data = MenuHelper::getMenuData($this->request);
		// =======================================================================
		// Model Data Set & Data Query Return
		// =======================================================================
		
		$model = new SadminModel();
		
		$list_1rd_main = $model->get_1rd_list(array());
		
		$mdata['comp_cd'] = $_SESSION['comp_cd'];
		$list_1rd_use = $model->use_1rd_list($mdata);

		// 이용 여부 및 카운터 데이터를 매핑
		$new_use = array();
		foreach ($list_1rd_use as $r) {
			$new_use[$r['1RD_CATE_CD']] = [
				'USE_YN' => $r['USE_YN'],
				'counter' => isset($r['counter']) ? $r['counter'] : 0 // counter 값 추가
			];
		}

		$new_list = array();
		foreach ($list_1rd_main as $r) {
			$item = $r;
			if (isset($new_use[$r['1RD_CATE_CD']])) {
				$item['USE_YN'] = $new_use[$r['1RD_CATE_CD']]['USE_YN'];
				$item['counter'] = $new_use[$r['1RD_CATE_CD']]['counter']; // counter 값 추가
			} else {
				$item['USE_YN'] = "N";
				$item['counter'] = 0;
			}
			$new_list[] = $item;
		}

		// =======================================================================
		// 화면 처리
		// =======================================================================
		$data['view']['list_1rd_use'] = $new_list;
		$this->viewPage('/sadmin/use_1rd_manage', $data);
	}

	/**
     * 지점의 메뉴 리스트(지점메뉴반영)을 불러온다.
     * @return ArrayList
     */
    public function getMenuTreeListAtBcoff()
    {
        // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        $data["useFor"] = "MA";   //지점 메뉴를 가져온다.
        $menuModel = new MenuModel();
        $treeStateVO = new TreeStateVO();
        $treeVO = new TreeVO();
        $roleMenuVO = new RoleMenuVO();
		$data['compCd'] = $_SESSION['comp_cd'];
        

        // 메뉴 리스트 가져오기
        $result = $menuModel->list_comp_menu($data);  // 배열 (메뉴 리스트)
        $roleMenuList = $menuModel->getBcoffMenuList($data); // 배열 (권한-메뉴 리스트)

        foreach ($result as $key => $vo) {
            foreach ($roleMenuList as $index => $value) {
                if (!empty($vo['id']) && $vo['id'] === $value['cd_menu']) {
                    $state = new TreeStateVO();
                    $state->setSelected(true);
                    $vo['state'] = $state; // 상태 설정
                    
                    // 완료 후 삭제
                    unset($roleMenuList[$index]);
                }
            }
            
            // 업데이트된 값을 다시 저장
            $result[$key] = $vo;
        }
        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $result 
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }

	/**
     * 지점의 메뉴를 저장한다.
     * @return json
     */
    public function saveBcoffMenu()
    {
         // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        $authModel = new AuthModel();
        $data["user_id"] = "test";


        $result = $authModel->deleteBcoffMenu($data);
        $bcoffMenuList = $data["bcoffMenuList"];
        if(!empty($bcoffMenuList) && count($bcoffMenuList) > 0)
        {
            $result = $authModel->insertBcoffMenu($data);
        }

        
        return $this->response->setJSON([
            'result' => $result
        ]);
    }

	/**
     * 슈퍼어드민의 지점별 메뉴 설정
     */
    public function set_menu_bcoff()
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
        
		$data['comp_cd'] = $_SESSION['comp_cd'];

        $companyModel = new TcmgCompanyModel();
        
        
        $data['view']['company_list'] = $companyModel->list_company();
        

        $this->viewPage('/sadmin/set_menu_bcoff',$data);
    }

	public function getCompBcoffList()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);
        
        $memModel = new MemModel();
        

        // 메뉴 리스트 가져오기
        $comp_list = $memModel->mobile_list_comp_info($jsonData);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $comp_list // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }
    
    /**
     * 대분류 사용 설정 ( USE_YN : Y or N )
     * @return string
     */
    public function ajax_use_1rd_change()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$model = new SadminModel();
    	$nn_now = new Time('now');
    	
    	
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * code1 : 1차 카테고리 코드
    	 * use_yn : Y 또는 N
    	 */
    	
    	$postVar = $this->request->getPost();
    	
    	// ===========================================================================
    	// Processs
    	// ===========================================================================

    	$useSetYN = "Y";
    	$mdata['1rd_cate_cd'] = $postVar['code1'];
    	$mdata['comp_cd'] = $_SESSION['comp_cd'];
    	$mdata['use_yn'] = $postVar['use_yn'];
    	
    	// 1. use_yn 이 N 일 경우 판매 상품에서 이미 사용중인지를 체크한다. 사용중이라면 N 설정이 불가능하다.
    	if($postVar['use_yn'] == "N") :
    		
    		$ncheck_count = $model->use_1rd_sell_check_count($mdata);
    		
    		if ($ncheck_count > 0) $useSetYN = "N";
    	endif;
    	
    	if ($useSetYN == "Y") :
    	
	    	// 2. code1 에 따라서 insert 할지 update 할지를 검사한다.
	    	$check_count = $model->use_1rd_check_count($mdata);
	    	$postVar['check_count'] = $check_count;
	    	if ($check_count == 0):
	    		// 3-1 . insert 를 수행한다. ( 수행전 대분류 정보를 가져온다. )
	    		$get_1rd_cate_info = $model->get_use_1rd_info($mdata);
	    	
	    		//$mdata['1rd_cate_cd'] = $get_1rd_cate_info[0]['1RD_CATE_CD'];
	    		//$mdata['comp_cd'] = $get_1rd_cate_info[0]['COMP_CD'];
	    		$mdata['cate_nm'] = $get_1rd_cate_info[0]['CATE_NM'];
	    		$mdata['grp_cate_set'] = $get_1rd_cate_info[0]['GRP_CATE_SET'];
	    		$mdata['lockr_set'] = $get_1rd_cate_info[0]['LOCKR_SET'];
	    		
	    		
	    		$mdata['cre_id'] = $_SESSION['user_id'];
	    		$mdata['cre_datetm'] = $nn_now;
	    		$mdata['mod_id'] = $_SESSION['user_id'];
	    		$mdata['mod_datetm'] = $nn_now;
	    		
	    		$insert_1rd_use = $model->insert_use_1rd_change($mdata);
	    	else:
	    		// 3-2 . update 를 수행한다.
	    		$mdata['mod_id'] = $_SESSION['user_id'];
	    		$mdata['mod_datetm'] = $nn_now;
	    		$update_1rd_use = $model->update_use_1rd_change($mdata);	
	    	endif;
    	
    	endif;
    	$use_set_result = $postVar;
    	
    	$return_json['result'] = 'true';
    	$return_json['use_set_result'] = $use_set_result;
    	
    	return json_encode($return_json);
    }

	/**
     * 중분류 생성할때 대분류 선택시 자동으로 코드를 생성해서 보내준다.
     * @return string
     */
    public function ajax_cate2_code()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['1rd_cate_cd'] = $postVar['cate1'];
        $cate2 = $cateModel->generate_u2rd_code($cateData);
        
        $return_json['result'] = 'true';
        $return_json['cate2_code'] = $cate2;
        
        return json_encode($return_json);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 중뷴류 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 증분류 관리
     */
    public function two_cate_list()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================

		$cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $model = new EventModel();
        
        // ===========================================================================
        // Process
        // ===========================================================================
        $cateModel = new CateModel();
        
        $cate1 = $cateModel->cevent_u1rd_list($cateData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['cate1'] = $cate1;
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        $boardPage = new Ama_board($initVar);
        $model = new SadminModel();
        
        $totalCount  = $model->list_two_cate_main_count($boardPage->getVal());
        $two_cate_main_list = $model->list_two_cate_main($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
		
		//if ( !isset($searchVal['unm']) ) $searchVal['unm'] = '';
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // Model Data Set & Data Query Return
        // ===========================================================================
        // 대분류 리스트를 가져온다.
        
        $mdata['type'] = "list";
        $list_1rd_cate = $model->get_1rd_cate_cd($mdata);
        
        $code_1rd_name = array();
        foreach ($list_1rd_cate as $r)
        {
            $code_1rd_name[$r['1RD_CATE_CD']] = $r['CATE_NM'];
        }
        
        $data['view']['code_1rd_name'] = $code_1rd_name;
        $data['view']['list_1rd_cate'] = $list_1rd_cate;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['two_cate_main_list'] = $two_cate_main_list;
        $this->viewPage('/sadmin/two_cate_list',$data);
    }
    
    /**
     * 중분류 코드 체크
     * @return string
     */
    public function ajax_tow_code_chk()
    {
        //post : cate_cd
        $postVar = $this->request->getPost();
        $model = new SadminModel();
        
        $data['2rd_cate_cd'] = $postVar['cate_cd'];
        $cate_chk = $model->dup_two_cate_main_count($data);
        
        if ($cate_chk == 0)
        {
            $return_json['result'] = 'true';
        } else
        {
            $return_json['result'] = 'false';
        }
        
        return json_encode($return_json);
    }
    
    /**
     * 중분류 등록 처리
     */
    public function ajax_two_cate_proc()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$model = new SadminModel();
    	$nn_now = new Time('now');
    	
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * 1rd_cate_cd : 1차 카테고리 코드
    	 * 2rd_cate_cd : 2차 카테고리 코드
    	 * cate_nm : 카테고리 명
    	 */
    	$postVar = $this->request->getPost();
    	
    	// ===========================================================================
    	// Model Data Set & Data Query Return
    	// ===========================================================================
		
    	$lockr_knd ="";
    	$lockr_gendr_set = "";
    	
    	$mdata['type'] = 'get';
    	$mdata['1rd_cate_cd'] = $postVar['1rd_cate_cd'];
    	$get_1rd_info = $model->get_1rd_cate_cd($mdata);
    	
    	$mdata['2rd_cate_cd'] 	= $postVar['2rd_cate_cd'];
    	$mdata['cate_nm'] 		= $postVar['cate_nm'];
    	
    	$mdata['grp_cate_set'] 	= $get_1rd_info[0]['GRP_CATE_SET'];
    	
    	$mdata['clas_dv'] 	    = $postVar['clas_dv'];
    	$mdata['lockr_set'] 	= $get_1rd_info[0]['LOCKR_SET'];
    	
    	if($get_1rd_info[0]['LOCKR_SET'] == 'Y') :
    	   if(isset($postVar['lockr_knd'])) $lockr_knd = $postVar['lockr_knd'];
    	endif;
    	
    	$mdata['lockr_knd'] 	= $lockr_knd;
    	$mdata['lockr_gendr_set'] 	= $lockr_gendr_set;
    	
    	$mdata['cre_id'] = $_SESSION['user_id'];
    	$mdata['cre_datetm'] = $nn_now;
    	$mdata['mod_id'] = $_SESSION['user_id'];
    	$mdata['mod_datetm'] = $nn_now;

    	$insert_cate_two = $model->insert_two_cate($mdata);
    	
    	scriptAlert('등록되었습니다.');
    	scriptLocation('/smgrmain/two_cate_list');
    	exit();
    	
    	//var_dump($insert_cate_two);
    	
    	// ===========================================================================
    	// Processs
    	// ===========================================================================
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 지점 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 지점 관리 [사용하지 않음]
     */
    public function bc_manage()
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
    	$model = new SadminModel();
    	
    	$totalCount  = $model->list_two_cate_main_count($boardPage->getVal());
    	$two_cate_main_list = $model->list_two_cate_main($boardPage->getVal());
    	
    	$searchVal = $boardPage->getVal();
    	$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
    	
    	//if ( !isset($searchVal['unm']) ) $searchVal['unm'] = '';
    	
    	$data['view']['search_val'] = $searchVal;
    	$data['view']['pager'] = $boardPage->getPager($totalCount);
    	
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	$data['view']['two_cate_main_list'] = $two_cate_main_list;
    	$this->viewPage('/sadmin/bc_appct_manage',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 지점 신청 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 지점 신청 관리 리스트
     */
    public function bc_appct_manage()
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
    	$model = new SadminModel();
    	
    	$totalCount  = $model->bc_appct_count($boardPage->getVal());
    	$bc_appct_list = $model->list_bc_appct($boardPage->getVal());
    	
    	$searchVal = $boardPage->getVal();
    	$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
    	
    	//if ( !isset($searchVal['unm']) ) $searchVal['unm'] = '';
    	
    	$data['view']['search_val'] = $searchVal;
    	$data['view']['pager'] = $boardPage->getPager($totalCount);
    	
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	$data['view']['bc_appct_list'] = $bc_appct_list;
    	$this->viewPage('/sadmin/bc_appct_manage',$data);
    	
    }


    /**
     * 지점 기본 설정 
     */
    public function branch_basic_setup()
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
    	$model = new SadminModel();
    	
    	// 승인된 지점만 조회하도록 필터 추가
    	$searchParams = $boardPage->getVal();
    	$searchParams['bcoff_appct_stat'] = '01'; // 승인된 지점만 조회
    	
    	$totalCount  = $model->bc_appct_count($searchParams);
    	$bc_appct_list = $model->list_bc_appct($searchParams);
    	
    	$searchVal = $boardPage->getVal();
    	$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
    	
    	// 설정 상태 정보 추가 (예시)
    	$setup_status = [
    	    'basic_info_yn' => 'Y',
    	    'pg_setup_yn' => 'N', 
    	    'van_setup_yn' => 'N',
    	    'bank_setup_yn' => 'N',
    	    'ready_yn' => 'N',
    	    'setup_progress' => 20
    	];
    	
    	$data['view']['search_val'] = $searchVal;
    	$data['view']['pager'] = $boardPage->getPager($totalCount);
    	$data['view']['bc_appct_list'] = $bc_appct_list;
    	$data['view']['setup_status'] = $setup_status;
    	
    	// ===============================================================
    	$this->viewPage('/sadmin/branch_basic_setup',$data);
    	
    }


    /**
     * PG 설정 저장 (AJAX)
     */
    public function ajax_save_pg_settings()
    {
        // DB 연결 초기화
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        $pg_settings = $this->request->getPost('pg_settings');
        
        if (!$comp_cd || !$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '세션 정보가 없습니다.'
            ]);
        }
        
        if (!$pg_settings) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'PG 설정 데이터가 없습니다.'
            ]);
        }
        
        try {
            // 기존 PG 설정 조회
            $sql = "SELECT pg_settings FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            $row = $query->getRow();
            $existing_settings = [];
            
            if ($row && $row->pg_settings) {
                $existing_settings = json_decode($row->pg_settings, true) ?? [];
            }
            
            // 기존 설정과 새 설정을 병합 (새 설정이 기존 설정을 업데이트)
            foreach ($pg_settings as $provider => $settings) {
                $existing_settings[$provider] = $settings;
            }
            
            // JSON 데이터 유효성 검증
            $pg_settings_json = json_encode($existing_settings);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data');
            }
            
            // PG 설정 저장
            $sql = "UPDATE bcoff_mgmt_tbl 
                    SET pg_settings = :pg_settings:,
                        mod_datetm = NOW()
                    WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            
            $result = $this->db->query($sql, [
                'pg_settings' => $pg_settings_json,
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            if ($result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => 'PG 설정이 저장되었습니다.'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'PG 설정 저장 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * VAN 설정 저장 (AJAX)
     */
    public function ajax_save_van_settings()
    {
        // DB 연결 초기화
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        $van_settings = $this->request->getPost('van_settings');
        
        if (!$comp_cd || !$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '세션 정보가 없습니다.'
            ]);
        }
        
        if (!$van_settings) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'VAN 설정 데이터가 없습니다.'
            ]);
        }
        
        try {
            // 기존 VAN 설정 조회
            $sql = "SELECT van_settings FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            $row = $query->getRow();
            $existing_settings = [];
            
            if ($row && $row->van_settings) {
                $existing_settings = json_decode($row->van_settings, true) ?? [];
            }
            
            // 기존 설정과 새 설정을 병합 (새 설정이 기존 설정을 업데이트)
            foreach ($van_settings as $provider => $settings) {
                $existing_settings[$provider] = $settings;
            }
            
            // JSON 데이터 유효성 검증
            $van_settings_json = json_encode($existing_settings);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data');
            }
            
            // VAN 설정 저장
            $sql = "UPDATE bcoff_mgmt_tbl 
                    SET van_settings = :van_settings:,
                        updated_at = NOW()
                    WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            
            $result = $this->db->query($sql, [
                'van_settings' => $van_settings_json,
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            if ($result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => 'VAN 설정이 저장되었습니다.'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'VAN 설정 저장 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * PG/VAN 설정 로드 (AJAX)
     */
    public function ajax_load_pg_van_settings()
    {
        // DB 연결 초기화
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getGet('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        
        if (!$comp_cd || !$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '세션 정보가 없습니다.'
            ]);
        }
        
        try {
            $sql = "SELECT pg_settings, van_settings 
                    FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            
            $result = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ])->getResultArray();
            
            if (!empty($result)) {
                $row = $result[0];
                
                // JSON 파싱
                $pg_settings = $row['pg_settings'] ? json_decode($row['pg_settings'], true) : [];
                $van_settings = $row['van_settings'] ? json_decode($row['van_settings'], true) : [];
                
                return $this->response->setJSON([
                    'result' => 'success',
                    'data' => [
                        'pg_settings' => $pg_settings,
                        'van_settings' => $van_settings
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'result' => 'success',
                    'data' => [
                        'pg_settings' => [],
                        'van_settings' => []
                    ]
                ]);
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'PG/VAN 설정 로드 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 결제 설정 관리
     */
    public function payment_settings_overview()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = MenuHelper::getMenuData($this->request);
    	
    	// DB 연결 초기화
    	$this->db = \Config\Database::connect();
    	
    	// 선택된 지점 처리
    	$selected_branch = $this->request->getGet('bcoff_cd');
    	$data['view']['selected_branch'] = $selected_branch;
    	$data['view']['branch_list'] = $this->getBranchListForSelection();
    	
    	// ===========================================================================
    	// 선택된 지점의 설정 상태 로드 (선택된 지점이 있는 경우만)
    	// ===========================================================================
    	if ($selected_branch) {
    		$data['view']['setup_status'] = $this->getBranchSetupStatus($selected_branch);
    		$data['view']['pg_summary'] = $this->getPGSummary($selected_branch);
    		$data['view']['van_summary'] = $this->getVANSummary($selected_branch);
    		$data['view']['bank_summary'] = $this->getBankSummary($selected_branch);
    		$data['view']['payment_stats'] = $this->getPaymentStats($selected_branch);
    		$data['view']['recent_payments'] = $this->getRecentPayments($selected_branch);
    		$data['view']['chart_data'] = $this->getChartData($selected_branch);
    	} else {
    		$data['view']['setup_status'] = [];
    		$data['view']['pg_summary'] = [];
    		$data['view']['van_summary'] = [];
    		$data['view']['bank_summary'] = [];
    		$data['view']['payment_stats'] = [];
    		$data['view']['recent_payments'] = [];
    		$data['view']['chart_data'] = [];
    	}
    	
    	// ===============================================================
    	$this->viewPage('/sadmin/payment_settings_overview',$data);
    	
    }



     /**
     * PG/VAN 설정
     */
    public function pg_van_settings()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = MenuHelper::getMenuData($this->request);
    	
    	// DB 연결 초기화
    	$this->db = \Config\Database::connect();
    	
    	// URL에서 지점 코드 가져오기
    	$selected_bcoff_cd = $this->request->getGet('bcoff_cd') ?? '';
    	
    	// ===========================================================================
    	// 지점 목록 조회 (승인된 지점만)
    	// ===========================================================================
    	$data['view']['branch_list'] = $this->getBranchListForSelection();
    	
    	// 선택된 지점 코드
    	$data['view']['selected_branch'] = $selected_bcoff_cd;
    	$data['view']['selected_bcoff_cd'] = $selected_bcoff_cd;  // 추가: 직접적인 변수 전달
    	
    	// ===========================================================================
    	// 선택된 지점의 PG/VAN/POS 설정 조회
    	// ===========================================================================
    	$data['view']['pg_settings'] = [];
    	$data['view']['van_settings'] = [];
    	$data['view']['pos_settings'] = [];
    	$data['view']['default_settings'] = [
    		'pg' => 'inicis',
    		'pg_type' => 'mobile',
    		'van' => 'kicc',
    		'dev_mode' => false,
    		'auto_settle' => false
    	];
    	
    	if ($selected_bcoff_cd) {
    		$comp_cd = $_SESSION['comp_cd'] ?? '';
    		if ($comp_cd) {
    			$sql = "SELECT pg_settings, van_settings, bank_accounts 
    					FROM bcoff_mgmt_tbl 
    					WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
    			
    			$result = $this->db->query($sql, [
    				'comp_cd' => $comp_cd,
    				'bcoff_cd' => $selected_bcoff_cd
    			])->getRowArray();
    		
    		if ($result) {
    			// 변수 초기화
    			$pg_settings = [];
    			$van_settings = [];
    			
    			// PG 설정 파싱
    			if (!empty($result['pg_settings'])) {
    				$pg_settings = json_decode($result['pg_settings'], true) ?: [];
    				$data['view']['pg_settings'] = $pg_settings;
    			}
    			
    			// VAN 설정 파싱
    			if (!empty($result['van_settings'])) {
    				$van_settings = json_decode($result['van_settings'], true) ?: [];
    				$data['view']['van_settings'] = $van_settings;
    			}
    			
    			// POS 설정 파싱 - pg_settings에서 가져오기
    			$data['view']['pos_settings'] = $pg_settings['pos_settings'] ?? $this->getDefaultPosSettings();
    			
    			// 기본 설정 추출
    			$data['view']['default_settings'] = [
    				'pg' => $pg_settings['default_pg'] ?? 'inicis',
    				'pg_type' => $pg_settings['default_pg_type'] ?? 'mobile',
    				'van' => $van_settings['default_van'] ?? 'kicc',
    				'dev_mode' => $pg_settings['dev_mode'] ?? false,
    				'auto_settle' => $pg_settings['auto_settle'] ?? false
    			];
    		}
    		}
    	}
    	
    	// ===========================================================================
    	// Board Pagzing
    	// ===============================================================
    	$this->viewPage('/sadmin/pg_van_settings',$data);
    	
    }


     /**
     * 계좌정보 관리
     */
    public function bank_account_management()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = MenuHelper::getMenuData($this->request);
    	
    	// DB 연결 초기화
    	$this->db = \Config\Database::connect();
    	
    	// URL에서 지점 코드 가져오기
    	$selected_bcoff_cd = $this->request->getGet('bcoff_cd') ?? '';
    	
    	// ===========================================================================
    	// 지점 목록 조회 (승인된 지점만)
    	// ===========================================================================
    	$data['branch_list'] = $this->getBranchListForSelection();
    	
    	// 선택된 지점 코드
    	$data['selected_bcoff_cd'] = $selected_bcoff_cd;
    	
    	// ===========================================================================
    	// 선택된 지점의 계좌 정보 조회
    	// ===========================================================================
    	$data['bank_accounts'] = [];
    	
    	if ($selected_bcoff_cd) {
    		$comp_cd = $_SESSION['comp_cd'] ?? '';
    		if ($comp_cd) {
    			$sql = "SELECT bank_accounts FROM bcoff_mgmt_tbl 
    					WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
    			
    			$result = $this->db->query($sql, [
    				'comp_cd' => $comp_cd,
    				'bcoff_cd' => $selected_bcoff_cd
    			])->getRowArray();
    			
    			if ($result && !empty($result['bank_accounts'])) {
    				$data['bank_accounts'] = json_decode($result['bank_accounts'], true) ?: [];
    			}
    		}
    	}
    	
    	// ===========================================================================
    	// Board Pagzing
    	// ===============================================================
    	$this->viewPage('/sadmin/bank_account_management',$data);
    	
    }

    /**
     * 결제 상태 조회
     */
    public function payment_status_inquiry()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = MenuHelper::getMenuData($this->request);
    	
    	// DB 연결 초기화
    	$this->db = \Config\Database::connect();
    	
    	// 선택된 지점 처리
    	$selected_branch = $this->request->getGet('bcoff_cd');
    	$data['view']['selected_branch'] = $selected_branch;
    	$data['view']['branch_list'] = $this->getBranchListForSelection();
    	
    	// ===========================================================================
    	// Board Pagzing
    	// ===========================================================================
    	$initVar['post'] = $this->request->getPost();
    	$initVar['get'] = $this->request->getGet();
    	
    	$boardPage = new Ama_board($initVar);
    	$model = new SadminModel();
    	
    	// ===========================================================================
    	// 실제 결제 내역 데이터 로드 (선택된 지점이 있는 경우만)
    	// ===========================================================================
    	if ($selected_branch) {
    		$totalCount = $this->getPaymentInquiryCount($boardPage->getVal(), $selected_branch);
    		$payment_list = $this->getPaymentInquiryList($boardPage->getVal(), $selected_branch);
    		$quick_stats = $this->getPaymentQuickStats($selected_branch);
    	} else {
    		$totalCount = 0;
    		$payment_list = [];
    		$quick_stats = [
    			'total_count' => 0,
    			'success_count' => 0,
    			'pending_count' => 0,
    			'failed_count' => 0,
    			'total_amount' => 0,
    			'success_amount' => 0
    		];
    	}
    	
    	$searchVal = $boardPage->getVal();
    	$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
    	
    	$data['view']['search_val'] = $searchVal;
    	$data['view']['pager'] = $boardPage->getPager($totalCount);
    	$data['view']['payment_list'] = $payment_list;
    	$data['view']['quick_stats'] = $quick_stats;
    	
    	// ===============================================================
    	$this->viewPage('/sadmin/payment_status_inquiry',$data);
    	
    }
    
    /**
     * 결제 조회 건수
     */
    private function getPaymentInquiryCount($params, $bcoff_cd = null)
    {
        $sql = "SELECT COUNT(*) as counter FROM paymt_mgmt_tbl P
                LEFT JOIN mem_info_detl_tbl M ON P.MEM_SNO = M.MEM_SNO AND P.COMP_CD = M.COMP_CD
                WHERE P.COMP_CD = :comp_cd:";
        
        $bindings = ['comp_cd' => $_SESSION['comp_cd']];
        
        // 지점 조건 추가
        if ($bcoff_cd) {
            $sql .= " AND P.BCOFF_CD = :bcoff_cd:";
            $bindings['bcoff_cd'] = $bcoff_cd;
        }
        
        // 검색 조건 추가
        if (!empty($params['search_date_start']) && !empty($params['search_date_end'])) {
            $sql .= " AND DATE(P.PAYMT_DATE) BETWEEN :start_date: AND :end_date:";
            $bindings['start_date'] = $params['search_date_start'];
            $bindings['end_date'] = $params['search_date_end'];
        }
        
        if (!empty($params['search_status'])) {
            $sql .= " AND P.PAYMT_STAT = :status:";
            $bindings['status'] = $params['search_status'];
        }
        
        if (!empty($params['search_method'])) {
            $sql .= " AND P.PAYMT_MTHD = :method:";
            $bindings['method'] = $params['search_method'];
        }
        
        if (!empty($params['search_txt'])) {
            $sql .= " AND (M.MEM_NM LIKE :search_txt: OR M.MEM_ID LIKE :search_txt: OR P.APPNO LIKE :search_txt:)";
            $bindings['search_txt'] = '%' . $params['search_txt'] . '%';
        }
        
        $result = $this->db->query($sql, $bindings)->getResultArray();
        return $result[0]['counter'] ?? 0;
    }
    
    /**
     * 결제 조회 리스트
     */
    private function getPaymentInquiryList($params, $bcoff_cd = null)
    {
        $sql = "SELECT P.*, M.MEM_NM, M.MEM_ID, M.MEM_TELNO
                FROM paymt_mgmt_tbl P
                LEFT JOIN mem_info_detl_tbl M ON P.MEM_SNO = M.MEM_SNO AND P.COMP_CD = M.COMP_CD
                WHERE P.COMP_CD = :comp_cd:";
        
        $bindings = ['comp_cd' => $_SESSION['comp_cd']];
        
        // 지점 조건 추가
        if ($bcoff_cd) {
            $sql .= " AND P.BCOFF_CD = :bcoff_cd:";
            $bindings['bcoff_cd'] = $bcoff_cd;
        }
        
        // 검색 조건 추가 (동일한 로직)
        if (!empty($params['search_date_start']) && !empty($params['search_date_end'])) {
            $sql .= " AND DATE(P.PAYMT_DATE) BETWEEN :start_date: AND :end_date:";
            $bindings['start_date'] = $params['search_date_start'];
            $bindings['end_date'] = $params['search_date_end'];
        }
        
        if (!empty($params['search_status'])) {
            $sql .= " AND P.PAYMT_STAT = :status:";
            $bindings['status'] = $params['search_status'];
        }
        
        if (!empty($params['search_method'])) {
            $sql .= " AND P.PAYMT_MTHD = :method:";
            $bindings['method'] = $params['search_method'];
        }
        
        if (!empty($params['search_txt'])) {
            $sql .= " AND (M.MEM_NM LIKE :search_txt: OR M.MEM_ID LIKE :search_txt: OR P.APPNO LIKE :search_txt:)";
            $bindings['search_txt'] = '%' . $params['search_txt'] . '%';
        }
        
        $sql .= " ORDER BY P.PAYMT_DATE DESC";
        
        // 페이징
        if (isset($params['limit_s']) && isset($params['limit_e'])) {
            $sql .= " LIMIT {$params['limit_s']}, {$params['limit_e']}";
        }
        
        return $this->db->query($sql, $bindings)->getResultArray();
    }
    
    /**
     * 빠른 통계 정보
     */
    private function getPaymentQuickStats($bcoff_cd = null)
    {
        $today = date('Y-m-d');
        
        $sql = "SELECT 
                    COUNT(*) as total_count,
                    SUM(CASE WHEN PAYMT_STAT = '00' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN PAYMT_STAT = '00' THEN PAYMT_AMT ELSE 0 END) as success_amount,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as refund_count,
                    SUM(CASE WHEN PAYMT_STAT = '99' THEN 1 ELSE 0 END) as failed_count,
                    SUM(CASE WHEN PAYMT_STAT = '10' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN DATE(PAYMT_DATE) = :today: THEN 1 ELSE 0 END) as today_count,
                    SUM(PAYMT_AMT) as total_amount
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd:";
        
        $bindings = [
            'comp_cd' => $_SESSION['comp_cd'],
            'today' => $today
        ];
        
        // 지점 조건 추가
        if ($bcoff_cd) {
            $sql .= " AND BCOFF_CD = :bcoff_cd:";
            $bindings['bcoff_cd'] = $bcoff_cd;
        }
        
        $result = $this->db->query($sql, $bindings)->getResultArray();
        
        return $result[0] ?? [
            'total_count' => 0,
            'success_count' => 0,
            'success_amount' => 0,
            'failed_count' => 0,
            'refund_count' => 0,
            'pending_count' => 0,
            'today_count' => 0,
            'total_amount' => 0
        ];
    }

    /**
     * 결제 통계
     */
    public function payment_statistics()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = MenuHelper::getMenuData($this->request);
    	
    	// DB 연결 초기화
    	$this->db = \Config\Database::connect();
    	
    	// 선택된 지점 처리
    	$selected_branch = $this->request->getGet('bcoff_cd');
    	$data['view']['selected_branch'] = $selected_branch;
    	$data['view']['branch_list'] = $this->getBranchListForSelection();
    	
    	// ===========================================================================
    	// 실제 결제 통계 데이터 로드 (선택된 지점이 있는 경우만)
    	// ===========================================================================
    	if ($selected_branch) {
    		$payModel = new \App\Models\PayModel();
    		
    		// 결제 통계 데이터 생성
    		$analytics_data = $this->getPaymentAnalytics($selected_branch);
    		$payment_summary = $this->getPaymentSummary($selected_branch);
    		
    		$data['view']['analytics_data'] = $analytics_data;
    		$data['view']['payment_summary'] = $payment_summary;
    	} else {
    		$data['view']['analytics_data'] = [
    			'total_revenue' => 0,
    			'total_transactions' => 0,
    			'success_rate' => 0,
    			'total_users' => 0,
    			'revenue_change' => 0,
    			'transaction_change' => 0,
    			'success_change' => 0,
    			'user_change' => 0
    		];
    		$data['view']['payment_summary'] = [];
    	}
    	
    	// ===============================================================
    	$this->viewPage('/sadmin/payment_statistics',$data);
    	
    }
    
    /**
     * 결제 분석 데이터 생성
     */
    private function getPaymentAnalytics($bcoff_cd = null)
    {
        $model = new SadminModel();
        
        // 현재 월 결제 통계 계산
        $currentMonth = date('Y-m');
        $previousMonth = date('Y-m', strtotime('-1 month'));
        
        $sql = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as success_count,
                    COUNT(DISTINCT MEM_SNO) as total_users,
                    ROUND(SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as success_rate
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd:";
        
        $bindings = ['comp_cd' => $_SESSION['comp_cd']];
        
        // 지점 조건 추가
        if ($bcoff_cd) {
            $sql .= " AND BCOFF_CD = :bcoff_cd:";
            $bindings['bcoff_cd'] = $bcoff_cd;
        }
        
        $sql .= " AND DATE_FORMAT(PAYMT_DATE, '%Y-%m') = :current_month:";
        
        $current = $this->db->query($sql, array_merge($bindings, ['current_month' => $currentMonth]))->getResultArray()[0];
        
        $previous = $this->db->query($sql, array_merge($bindings, ['current_month' => $previousMonth]))->getResultArray()[0];
        
        // 전월 대비 변화율 계산
        $revenue_change = $previous['total_revenue'] > 0 ? 
            (($current['total_revenue'] - $previous['total_revenue']) / $previous['total_revenue']) * 100 : 0;
        $transaction_change = $previous['total_transactions'] > 0 ? 
            (($current['total_transactions'] - $previous['total_transactions']) / $previous['total_transactions']) * 100 : 0;
        $success_change = $current['success_rate'] - $previous['success_rate'];
        $user_change = $previous['total_users'] > 0 ? 
            (($current['total_users'] - $previous['total_users']) / $previous['total_users']) * 100 : 0;
        
        return [
            'total_revenue' => $current['total_revenue'] ?? 0,
            'total_transactions' => $current['total_transactions'] ?? 0,
            'success_rate' => $current['success_rate'] ?? 0,
            'total_users' => $current['total_users'] ?? 0,
            'revenue_change' => round($revenue_change, 1),
            'transaction_change' => round($transaction_change, 1),
            'success_change' => round($success_change, 1),
            'user_change' => round($user_change, 1)
        ];
    }
    
    /**
     * 결제 요약 데이터 생성
     */
    private function getPaymentSummary($bcoff_cd = null)
    {
        $filters = $this->request->getGet();
        $startDate = $filters['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $filters['end_date'] ?? date('Y-m-d');
        
        $sql = "SELECT 
                    DATE(PAYMT_DATE) as payment_date,
                    PAYMT_MTHD as payment_method,
                    PAYMT_VAN_KND as pg_provider,
                    COUNT(*) as total_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN PAYMT_STAT != '01' THEN 1 ELSE 0 END) as failed_count,
                    ROUND(SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as success_rate,
                    SUM(PAYMT_AMT) as total_amount,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as success_amount,
                    ROUND(AVG(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END), 0) as avg_amount
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd:
                AND DATE(PAYMT_DATE) BETWEEN :start_date: AND :end_date:";
        
        $bindings = [
            'comp_cd' => $_SESSION['comp_cd'],
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        // 지점 조건 추가
        if ($bcoff_cd) {
            $sql .= " AND BCOFF_CD = :bcoff_cd:";
            $bindings['bcoff_cd'] = $bcoff_cd;
        }
        
        // 필터 적용
        if (!empty($filters['payment_method'])) {
            $sql .= " AND PAYMT_MTHD = :payment_method:";
            $bindings['payment_method'] = $filters['payment_method'];
        }
        
        if (!empty($filters['pg_provider'])) {
            $sql .= " AND PAYMT_VAN_KND = :pg_provider:";
            $bindings['pg_provider'] = $filters['pg_provider'];
        }
        
        if (!empty($filters['amount_range'])) {
            $range = explode('-', $filters['amount_range']);
            if (count($range) == 2) {
                if ($range[1] === '') {
                    $sql .= " AND PAYMT_AMT >= :min_amount:";
                    $bindings['min_amount'] = $range[0];
                } else {
                    $sql .= " AND PAYMT_AMT BETWEEN :min_amount: AND :max_amount:";
                    $bindings['min_amount'] = $range[0];
                    $bindings['max_amount'] = $range[1];
                }
            }
        }
        
        $sql .= " GROUP BY DATE(PAYMT_DATE), PAYMT_MTHD, PAYMT_VAN_KND 
                  ORDER BY payment_date DESC, total_amount DESC 
                  LIMIT 100";
        
        return $this->db->query($sql, $bindings)->getResultArray();
    }









    
    /**
     * 지점관리자 아이디 중복체크
     */
    public function ajax_bc_appct_id_chk()
    {
        // smgmt_id
        $postVar = $this->request->getPost();
        $model = new SadminModel();
        
        $data['bcoff_mgmt_id'] = $postVar['bcoff_mgmt_id'];
        
        $cc1 = $model->dup_bcoff_appct_mgmt_count($data); // 지점관리자 신청일때는 이미 중복체크를 하였기때문에 신청인 아이디도 중복 체크를 해야한다.
        $cc2 = $model->dup_bcoff_appct_mgmt2_count($data); // 지점관리자 승인시 메인 회원 테이블의 아이디로 등록 되기때문에 메인 회원테이블에서도 중복 체크를 해야한다.
        
        $dup_chk = $cc1 + $cc2;
        
        if ($dup_chk == 0)
        {
            $return_json['result'] = 'true';
        } else
        {
            $return_json['result'] = 'false';
        }
        
        return json_encode($return_json);
    }

    /**
     * 지점 신청 상세 정보 조회 (AJAX)
     */
    public function ajax_get_request_detail()
    {
        $getVar = $this->request->getGet();
        $model = new SadminModel();
        
        try {
            // 실제 데이터베이스에서 특정 신청 건 조회
            $request_detail = $model->get_bc_appct_detail($getVar['request_sno']);
            
            if (!empty($request_detail)) {
                $return_json = [
                    'result' => 'success',
                    'data' => $request_detail[0]
                ];
            } else {
                $return_json = [
                    'result' => 'error',
                    'message' => '신청 정보를 찾을 수 없습니다.'
                ];
            }
        } catch (Exception $e) {
            $return_json = [
                'result' => 'error',
                'message' => '데이터 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            ];
        }
        
        return $this->response->setJSON($return_json);
    }

    /**
     * 지점 설정 상태 조회 (AJAX)
     */
    public function ajax_get_setup_status()
    {
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $_SESSION['bcoff_cd'] ?? '';
        
        if (!$comp_cd || !$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '세션 정보가 없습니다.'
            ]);
        }
        
        try {
            // 지점 설정 상태 조회
            $setup_status = $this->getBranchSetupStatus($bcoff_cd);
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => $setup_status
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '설정 상태 조회 중 오류가 발생했습니다.'
            ]);
        }
    }

    /**
     * 특정 지점 설정 상태 조회 (AJAX)
     */
    public function ajax_get_branch_setup_status()
    {
        $bcoff_cd = $this->request->getGet('bcoff_cd');
        
        if (!$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '지점 코드가 필요합니다.'
            ]);
        }
        
        // DB 연결 초기화
        $this->db = \Config\Database::connect();
        
        try {
            // 지점 설정 상태 조회
            $setup_status = $this->getBranchSetupStatus($bcoff_cd);
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => $setup_status
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '설정 상태 조회 중 오류가 발생했습니다.'
            ]);
        }
    }

    /**
     * 지점 상세 정보 보기 페이지
     */
    public function branch_detail_view()
    {
        // 디버깅용 로그
        log_message('info', 'branch_detail_view method called');
        
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '지점 상세 정보';
        
        $bcoff_cd = $this->request->getGet('bcoff_cd');
        log_message('info', 'bcoff_cd: ' . $bcoff_cd);
        
        if (!$bcoff_cd) {
            $data['error'] = '지점 코드가 없습니다.';
            $this->viewPage('/sadmin/branch_detail_view', $data);
            return;
        }
        
        // DB 연결 초기화
        $this->db = \Config\Database::connect();
        
        // 지점 정보 조회
        $data['branch_info'] = $this->getBranchDetailInfo($bcoff_cd);
        
        // 지점 설정 상태 조회
        $data['setup_status'] = $this->getBranchSetupStatus($bcoff_cd);
        
        // PG 설정 정보 조회
        $data['pg_settings'] = $this->getPGSettings($bcoff_cd);
        
        // VAN 설정 정보 조회
        $data['van_settings'] = $this->getVANSettings($bcoff_cd);
        
        // 계좌 설정 정보 조회
        $data['bank_settings'] = $this->getBankSettings($bcoff_cd);
        
        // 최근 결제 통계
        $data['payment_stats'] = $this->getPaymentStatsByBranch($bcoff_cd);
        
        $this->viewPage('/sadmin/branch_detail_view', $data);
    }
    
    /**
     * 지점 상세 정보 조회
     */
    private function getBranchDetailInfo($bcoff_cd)
    {
        $sql = "SELECT 
                    b.*, 
                    c.COMP_NM,
                    bm.BCOFF_NM,
                    bm.BCOFF_ADDR,
                    bm.BCOFF_TELNO,
                    bm.BCOFF_TELNO2,
                    DATE_FORMAT(b.BCOFF_APPCT_DATETM, '%Y-%m-%d %H:%i') as APPCT_DATE_FORMAT,
                    DATE_FORMAT(b.BCOFF_APPV_DATETM, '%Y-%m-%d %H:%i') as APPV_DATE_FORMAT
                FROM bcoff_appct_mgmt_tbl AS b
                LEFT JOIN smgmt_mgmt_tbl AS c ON b.COMP_CD = c.COMP_CD
                LEFT JOIN bcoff_mgmt_tbl AS bm ON b.COMP_CD = bm.COMP_CD AND b.BCOFF_CD = bm.BCOFF_CD
                WHERE b.COMP_CD = :comp_cd:
                AND b.BCOFF_CD = :bcoff_cd:
                AND b.BCOFF_APPCT_STAT = '01'
                ORDER BY b.BCOFF_APPCT_MGMT_SNO DESC
                LIMIT 1";
        
        $query = $this->db->query($sql, [
            'comp_cd' => $_SESSION['comp_cd'],
            'bcoff_cd' => $bcoff_cd
        ]);
        
        $result = $query->getResultArray();
        return !empty($result) ? $result[0] : null;
    }
    
    /**
     * 지점 설정 상태 조회
     */
    private function getBranchSetupStatus($bcoff_cd)
    {
        // DB 연결 초기화
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        
        if (!$comp_cd || !$bcoff_cd) {
            return [
                'basic_info_yn' => 'N',
                'pg_setup_yn' => 'N',
                'van_setup_yn' => 'N',
                'bank_setup_yn' => 'N',
                'ready_yn' => 'N',
                'setup_progress' => 0
            ];
        }
        
        try {
            // 지점 기본 정보 및 설정 조회
            $sql = "SELECT 
                        BCOFF_NM,
                        BCOFF_ADDR,
                        BCOFF_TELNO,
                        pg_settings,
                        van_settings,
                        bank_accounts
                    FROM bcoff_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            
            $result = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ])->getResultArray();
            
            if (empty($result)) {
                return [
                    'basic_info_yn' => 'N',
                    'pg_setup_yn' => 'N',
                    'van_setup_yn' => 'N',
                    'bank_setup_yn' => 'N',
                    'ready_yn' => 'N',
                    'setup_progress' => 0
                ];
            }
            
            $branch_data = $result[0];
            
            // 1. 기본 정보 체크
            $basic_info_yn = (!empty($branch_data['BCOFF_NM']) && !empty($branch_data['BCOFF_TELNO'])) ? 'Y' : 'N';
            
            // 2. PG 설정 체크
            $pg_setup_yn = 'N';
            if (!empty($branch_data['pg_settings'])) {
                $pg_settings = json_decode($branch_data['pg_settings'], true);
                if (is_array($pg_settings)) {
                    foreach ($pg_settings as $provider => $config) {
                        if (isset($config['enabled']) && $config['enabled'] === true) {
                            $pg_setup_yn = 'Y';
                            break;
                        }
                    }
                }
            }
            
            // 3. VAN 설정 체크
            $van_setup_yn = 'N';
            if (!empty($branch_data['van_settings'])) {
                $van_settings = json_decode($branch_data['van_settings'], true);
                if (is_array($van_settings)) {
                    foreach ($van_settings as $provider => $config) {
                        if (isset($config['enabled']) && $config['enabled'] === true) {
                            $van_setup_yn = 'Y';
                            break;
                        }
                    }
                }
            }
            
            // 4. 계좌 설정 체크
            $bank_setup_yn = 'N';
            if (!empty($branch_data['bank_accounts'])) {
                $bank_accounts = json_decode($branch_data['bank_accounts'], true);
                if (is_array($bank_accounts) && count($bank_accounts) > 0) {
                    foreach ($bank_accounts as $account) {
                        if (isset($account['enabled']) && $account['enabled'] === true) {
                            $bank_setup_yn = 'Y';
                            break;
                        }
                    }
                }
            }
            
            // 5. 준비 완료 체크 (모든 설정이 완료되었는지)
            $ready_yn = ($basic_info_yn === 'Y' && $pg_setup_yn === 'Y' && $van_setup_yn === 'Y' && $bank_setup_yn === 'Y') ? 'Y' : 'N';
            
            // 6. 진행률 계산
            $progress = 0;
            if ($basic_info_yn === 'Y') $progress += 20;
            if ($pg_setup_yn === 'Y') $progress += 25;
            if ($van_setup_yn === 'Y') $progress += 25;
            if ($bank_setup_yn === 'Y') $progress += 20;
            if ($ready_yn === 'Y') $progress += 10;
            
            return [
                'basic_info_yn' => $basic_info_yn,
                'pg_setup_yn' => $pg_setup_yn,
                'van_setup_yn' => $van_setup_yn,
                'bank_setup_yn' => $bank_setup_yn,
                'ready_yn' => $ready_yn,
                'setup_progress' => $progress
            ];
            
        } catch (Exception $e) {
            return [
                'basic_info_yn' => 'N',
                'pg_setup_yn' => 'N',
                'van_setup_yn' => 'N',
                'bank_setup_yn' => 'N',
                'ready_yn' => 'N',
                'setup_progress' => 0
            ];
        }
    }
    
    /**
     * PG 설정 정보 조회
     */
    private function getPGSettings($bcoff_cd)
    {
        // PG 설정 테이블이 있다면 조회
        return [];
    }
    
    /**
     * VAN 설정 정보 조회
     */
    private function getVANSettings($bcoff_cd)
    {
        // VAN 설정 테이블이 있다면 조회
        return [];
    }
    
    /**
     * 계좌 설정 정보 조회
     */
    private function getBankSettings($bcoff_cd)
    {
        // 계좌 설정 테이블이 있다면 조회
        return [];
    }
    
    /**
     * 지점별 결제 통계 조회
     */
    private function getPaymentStatsByBranch($bcoff_cd)
    {
        $sql = "SELECT 
                    COUNT(*) as total_count,
                    SUM(CASE WHEN PAYMT_STAT = '00' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN PAYMT_STAT = '99' THEN 1 ELSE 0 END) as cancel_count,
                    SUM(CASE WHEN PAYMT_STAT = '00' THEN PAYMT_AMT ELSE 0 END) as total_amount
                FROM paymt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND PAYMT_DATE >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        
        $query = $this->db->query($sql, [
            'comp_cd' => $_SESSION['comp_cd'],
            'bcoff_cd' => $bcoff_cd
        ]);
        
        $result = $query->getResultArray();
        return !empty($result) ? $result[0] : [
            'total_count' => 0,
            'success_count' => 0,
            'cancel_count' => 0,
            'total_amount' => 0
        ];
    }
   
    /**
     * 지점 신청 Insert (ajax)
     */
    public function ajax_bc_appct_proc()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$model = new SadminModel();
    	$nn_now = new Time('now');
    	
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * bcoff_mgmt_id : 지점 관리자 아이디
    	 * bcoff_mgmt_pwd : 지점 관리자 비밀번호
    	 * mngr_nm : 담당자명 ( 지점관리자 이름 )
    	 * mngr_telno : 담당자 전화번호 (지점관리자 전화번호)
    	 * ceo_nm : 대표자 명
    	 * ceo_telno : 대표자 전화번호
    	 * bcoff_nm : 지점명
    	 * bcoff_addr : 지점 주소
    	 * bcoff_telno : 지점 전화번호
    	 * bcoff_telno2 : 지점 전화번호2
    	 * bcoff_memo : 지점 메모
    	 */
    	$postVar = $this->request->getPost();
    	
    	/*
    	 * BCOFF_APPCT_MGMT_SNO : 지점 신청 관리 일련번호
    	 * COMP_CD : $_SESSION['comp_cd']
    	 * BCOFF_CD : 지점 코드 ( 승인시 부여 )
    	 * 
    	 * BCOFF_APPCT_STAT : 지점 신청 상태
    	 * BCOFF_APPCT_DATETM : 지점 신청 일시
    	 */
    	
    	// ===========================================================================
    	// Model Data Set & Data Query Return
    	// ===========================================================================
    	
    	$mdata['comp_cd'] 				= $_SESSION['comp_cd'];
    	$mdata['bcoff_cd'] 				= "bcoff";
    	$mdata['bcoff_mgmt_id'] 		= $postVar['bcoff_mgmt_id'];
    	$mdata['bcoff_mgmt_pwd'] 		= $this->enc_pass($postVar['bcoff_mgmt_pwd']);
    	$mdata['mngr_nm'] 				= $postVar['mngr_nm'];
    	$mdata['mngr_telno'] 			= put_num($postVar['mngr_telno']);
    	$mdata['ceo_nm'] 				= $postVar['ceo_nm'];
    	$mdata['ceo_telno'] 			= put_num($postVar['ceo_telno']);
    	$mdata['bcoff_nm'] 				= $postVar['bcoff_nm'];
    	$mdata['bcoff_addr'] 			= $postVar['bcoff_addr'];
    	$mdata['bcoff_telno'] 			= put_num($postVar['bcoff_telno']);
    	$mdata['bcoff_telno2'] 			= put_num($postVar['bcoff_telno2']);
    	$mdata['bcoff_memo'] 			= $postVar['bcoff_memo'];
    	$mdata['bcoff_appct_stat'] 		= "00"; // 00 (신청) : 01 (승인) : 02 (거절) : 99 (취소)
    	$mdata['bcoff_appct_datetm'] 	= $nn_now;
    	
    	$mdata['cre_id'] = $_SESSION['user_id'];
    	$mdata['cre_datetm'] = $nn_now;
    	$mdata['mod_id'] = $_SESSION['user_id'];
    	$mdata['mod_datetm'] = $nn_now;
    	
    	$insert_bc_appct = $model->insert_bc_appct($mdata);
    	
    	scriptAlert("지점신청이 완료 되었습니다.");
    	$url = "/smgrmain/bc_appct_manage";
    	scriptLocation($url);
    	
    	exit();
    	//var_dump($insert_bc_appct);
    	
    	// ===========================================================================
    	// Processs
    	// ===========================================================================
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * 결제 분석 데이터 AJAX
     */
    public function ajax_get_analytics_data()
    {
        // DB 연결 초기화
        $this->db = \Config\Database::connect();
        
        try {
            $analytics_data = $this->getPaymentAnalytics();
            
            $return_json = [
                'result' => 'success',
                'data' => [
                    'analytics' => $analytics_data,
                    'trend' => $this->getPaymentTrendData(),
                    'methods' => $this->getPaymentMethodData()
                ]
            ];
        } catch (Exception $e) {
            $return_json = [
                'result' => 'error',
                'message' => '데이터 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            ];
        }
        
        return $this->response->setJSON($return_json);
    }
    
    /**
     * 차트 데이터 AJAX
     */
    public function ajax_get_chart_data()
    {
        try {
            $period = $this->request->getGet('period') ?? 'daily';
            
            $return_json = [
                'result' => 'success',
                'data' => [
                    'trend' => $this->getPaymentTrendData($period),
                    'methods' => $this->getPaymentMethodData()
                ]
            ];
        } catch (Exception $e) {
            $return_json = [
                'result' => 'error',
                'message' => '차트 데이터 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            ];
        }
        
        return $this->response->setJSON($return_json);
    }
    
    /**
     * 결제 추이 데이터 생성
     */
    private function getPaymentTrendData($period = 'daily')
    {
        $dateFormat = $period === 'daily' ? '%Y-%m-%d' : 
                     ($period === 'weekly' ? '%Y-%u' : '%Y-%m');
        
        $sql = "SELECT 
                    DATE_FORMAT(PAYMT_DATE, '$dateFormat') as date_label,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as amount,
                    COUNT(*) as count
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd:
                AND PAYMT_DATE >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY date_label
                ORDER BY date_label";
        
        $result = $this->db->query($sql, [
            'comp_cd' => $_SESSION['comp_cd']
        ])->getResultArray();
        
        return [
            'labels' => array_column($result, 'date_label'),
            'amounts' => array_column($result, 'amount'),
            'counts' => array_column($result, 'count')
        ];
    }
    
    /**
     * 결제수단별 데이터 생성
     */
    private function getPaymentMethodData()
    {
        $sql = "SELECT 
                    PAYMT_MTHD,
                    COUNT(*) as count
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd:
                AND PAYMT_STAT = '01'
                AND PAYMT_DATE >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY PAYMT_MTHD";
        
        $result = $this->db->query($sql, [
            'comp_cd' => $_SESSION['comp_cd']
        ])->getResultArray();
        
        $methods = ['CARD' => 0, 'BANK' => 0, 'VBANK' => 0, 'PHONE' => 0];
        foreach ($result as $row) {
            if (isset($methods[$row['PAYMT_MTHD']])) {
                $methods[$row['PAYMT_MTHD']] = (int)$row['count'];
            }
        }
        
        return array_values($methods);
    }

    /**
     * 상세 통계 정보 AJAX
     */
    public function ajax_get_detail_stats()
    {
        try {
            $date = $this->request->getGet('payment_date');
            $method = $this->request->getGet('payment_method');
            $provider = $this->request->getGet('pg_provider');
            
            $sql = "SELECT 
                        COUNT(*) as total_count,
                        SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as success_count,
                        SUM(CASE WHEN PAYMT_STAT != '01' THEN 1 ELSE 0 END) as failed_count,
                        ROUND(SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as success_rate,
                        SUM(PAYMT_AMT) as total_amount,
                        SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as success_amount,
                        ROUND(AVG(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END), 0) as avg_amount,
                        MAX(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END) as max_amount,
                        MIN(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END) as min_amount
                    FROM paymt_mgmt_tbl 
                    WHERE COMP_CD = :comp_cd:
                    AND DATE(PAYMT_DATE) = :date:
                    AND PAYMT_MTHD = :method:
                    AND PAYMT_VAN_KND = :provider:";
            
            $result = $this->db->query($sql, [
                'comp_cd' => $_SESSION['comp_cd'],
                'date' => $date,
                'method' => $method,
                'provider' => $provider
            ])->getResultArray();
            
            $data = $result[0] ?? [];
            $data['date'] = $date;
            $data['method'] = $method;
            $data['provider'] = $provider;
            
            $return_json = [
                'result' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            $return_json = [
                'result' => 'error',
                'message' => '상세 통계 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            ];
        }
        
        return $this->response->setJSON($return_json);
    }
    
    /**
     * 계좌 추가
     */
    public function ajax_add_account()
    {
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        
        $bank_nm = $this->request->getPost('bank_nm');
        $account_no = $this->request->getPost('account_no');
        $account_holder = $this->request->getPost('account_holder');
        $account_type = $this->request->getPost('account_type') ?? '일반';
        $is_default = $this->request->getPost('is_default') === 'on';
        $is_active = $this->request->getPost('is_active') === 'on';
        
        if (!$comp_cd || !$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '세션 정보가 없습니다.'
            ]);
        }
        
        if (!$bank_nm || !$account_no || !$account_holder) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '필수 정보가 누락되었습니다.'
            ]);
        }
        
        try {
            // 기존 계좌 정보 조회
            $sql = "SELECT bank_accounts FROM bcoff_mgmt_tbl WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $result = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ])->getResultArray();
            
            $existing_accounts = [];
            if (!empty($result) && !empty($result[0]['bank_accounts'])) {
                $existing_accounts = json_decode($result[0]['bank_accounts'], true) ?: [];
            }
            
            // 계좌번호 중복 확인
            foreach ($existing_accounts as $account) {
                if ($account['account_no'] === $account_no) {
                    return $this->response->setJSON([
                        'result' => 'error',
                        'message' => '이미 등록된 계좌번호입니다.'
                    ]);
                }
            }
            
            // 기본 계좌 설정 처리
            if ($is_default) {
                foreach ($existing_accounts as &$account) {
                    $account['is_default'] = false;
                }
            }
            
            // 새 계좌 추가
            $new_account = [
                'id' => uniqid(),
                'bank_nm' => $bank_nm,
                'account_no' => $account_no,
                'account_holder' => $account_holder,
                'account_type' => $account_type,
                'is_default' => $is_default,
                'enabled' => $is_active,
                'display_order' => count($existing_accounts) + 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $existing_accounts[] = $new_account;
            
            // 데이터베이스 업데이트
            $sql = "UPDATE bcoff_mgmt_tbl SET bank_accounts = :bank_accounts: WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $update_result = $this->db->query($sql, [
                'bank_accounts' => json_encode($existing_accounts),
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            if ($update_result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => '계좌가 추가되었습니다.'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '계좌 추가 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * 계좌 수정
     */
    public function ajax_update_account()
    {
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        $account_id = $this->request->getPost('account_id');
        
        $bank_nm = $this->request->getPost('bank_nm');
        $account_no = $this->request->getPost('account_no');
        $account_holder = $this->request->getPost('account_holder');
        $account_type = $this->request->getPost('account_type') ?? '일반';
        $is_default = $this->request->getPost('is_default') === 'on';
        $is_active = $this->request->getPost('is_active') === 'on';
        
        if (!$comp_cd || !$bcoff_cd || !$account_id) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '필수 정보가 누락되었습니다.'
            ]);
        }
        
        try {
            // 기존 계좌 정보 조회
            $sql = "SELECT bank_accounts FROM bcoff_mgmt_tbl WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $result = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ])->getResultArray();
            
            if (empty($result) || empty($result[0]['bank_accounts'])) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '계좌 정보를 찾을 수 없습니다.'
                ]);
            }
            
            $accounts = json_decode($result[0]['bank_accounts'], true) ?: [];
            $found = false;
            
            // 기본 계좌 설정 처리
            if ($is_default) {
                foreach ($accounts as &$account) {
                    $account['is_default'] = false;
                }
            }
            
            // 대상 계좌 업데이트
            foreach ($accounts as &$account) {
                if ($account['id'] === $account_id) {
                    $account['bank_nm'] = $bank_nm;
                    $account['account_no'] = $account_no;
                    $account['account_holder'] = $account_holder;
                    $account['account_type'] = $account_type;
                    $account['is_default'] = $is_default;
                    $account['enabled'] = $is_active;
                    $account['updated_at'] = date('Y-m-d H:i:s');
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '수정할 계좌를 찾을 수 없습니다.'
                ]);
            }
            
            // 데이터베이스 업데이트
            $sql = "UPDATE bcoff_mgmt_tbl SET bank_accounts = :bank_accounts: WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $update_result = $this->db->query($sql, [
                'bank_accounts' => json_encode($accounts),
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            if ($update_result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => '계좌가 수정되었습니다.'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '계좌 수정 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * 계좌 삭제
     */
    public function ajax_delete_account()
    {
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        $account_id = $this->request->getPost('account_id');
        
        if (!$comp_cd || !$bcoff_cd || !$account_id) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '필수 정보가 누락되었습니다.'
            ]);
        }
        
        try {
            // 기존 계좌 정보 조회
            $sql = "SELECT bank_accounts FROM bcoff_mgmt_tbl WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $result = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ])->getResultArray();
            
            if (empty($result) || empty($result[0]['bank_accounts'])) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '계좌 정보를 찾을 수 없습니다.'
                ]);
            }
            
            $accounts = json_decode($result[0]['bank_accounts'], true) ?: [];
            $new_accounts = [];
            $found = false;
            
            // 삭제할 계좌 제외하고 새 배열 생성
            foreach ($accounts as $account) {
                if ($account['id'] !== $account_id) {
                    $new_accounts[] = $account;
                } else {
                    $found = true;
                }
            }
            
            if (!$found) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '삭제할 계좌를 찾을 수 없습니다.'
                ]);
            }
            
            // 데이터베이스 업데이트
            $sql = "UPDATE bcoff_mgmt_tbl SET bank_accounts = :bank_accounts: WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $update_result = $this->db->query($sql, [
                'bank_accounts' => json_encode($new_accounts),
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            if ($update_result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => '계좌가 삭제되었습니다.'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '계좌 삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * 기본 계좌 설정
     */
    public function ajax_set_default_account()
    {
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        $account_id = $this->request->getPost('account_id');
        
        if (!$comp_cd || !$bcoff_cd || !$account_id) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '필수 정보가 누락되었습니다.'
            ]);
        }
        
        try {
            // 기존 계좌 정보 조회
            $sql = "SELECT bank_accounts FROM bcoff_mgmt_tbl WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $result = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ])->getResultArray();
            
            if (empty($result) || empty($result[0]['bank_accounts'])) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '계좌 정보를 찾을 수 없습니다.'
                ]);
            }
            
            $accounts = json_decode($result[0]['bank_accounts'], true) ?: [];
            $found = false;
            
            // 모든 계좌의 기본 설정 해제 후 대상 계좌만 기본으로 설정
            foreach ($accounts as &$account) {
                if ($account['id'] === $account_id) {
                    $account['is_default'] = true;
                    $found = true;
                } else {
                    $account['is_default'] = false;
                }
            }
            
            if (!$found) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '대상 계좌를 찾을 수 없습니다.'
                ]);
            }
            
            // 데이터베이스 업데이트
            $sql = "UPDATE bcoff_mgmt_tbl SET bank_accounts = :bank_accounts: WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $update_result = $this->db->query($sql, [
                'bank_accounts' => json_encode($accounts),
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            if ($update_result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => '기본 계좌가 설정되었습니다.'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '기본 계좌 설정 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * 계좌 활성화/비활성화
     */
    public function ajax_toggle_account()
    {
        $this->db = \Config\Database::connect();
        
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $_SESSION['bcoff_cd'] ?? '';
        $account_id = $this->request->getPost('account_id');
        $is_active = $this->request->getPost('is_active') === 'Y';
        
        if (!$comp_cd || !$bcoff_cd || !$account_id) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '필수 정보가 누락되었습니다.'
            ]);
        }
        
        try {
            // 기존 계좌 정보 조회
            $sql = "SELECT bank_accounts FROM bcoff_mgmt_tbl WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $result = $this->db->query($sql, [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ])->getResultArray();
            
            if (empty($result) || empty($result[0]['bank_accounts'])) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '계좌 정보를 찾을 수 없습니다.'
                ]);
            }
            
            $accounts = json_decode($result[0]['bank_accounts'], true) ?: [];
            $found = false;
            
            // 대상 계좌 활성화 상태 변경
            foreach ($accounts as &$account) {
                if ($account['id'] === $account_id) {
                    $account['enabled'] = $is_active;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '대상 계좌를 찾을 수 없습니다.'
                ]);
            }
            
            // 데이터베이스 업데이트
            $sql = "UPDATE bcoff_mgmt_tbl SET bank_accounts = :bank_accounts: WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd:";
            $update_result = $this->db->query($sql, [
                'bank_accounts' => json_encode($accounts),
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);
            
            if ($update_result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => '계좌 상태가 변경되었습니다.'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '계좌 상태 변경 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * 지점 목록 조회 (공통 사용)
     */
    private function getBranchListForSelection()
    {
        $comp_cd = $_SESSION['comp_cd'] ?? '';
        if (!$comp_cd) {
            return [];
        }
        
        $sql = "SELECT BCOFF_CD, BCOFF_NM, MNGR_NM 
                FROM bcoff_appct_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_APPCT_STAT = '01'
                ORDER BY BCOFF_NM";
        
        return $this->db->query($sql, ['comp_cd' => $comp_cd])->getResultArray();
    }

    /**
     * PG 연결 테스트
     */
    public function ajax_test_pg_connection()
    {
        $provider = $this->request->getPost('provider');
        $settings = $this->request->getPost('settings');
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
        
        if (!$provider || !$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '필수 파라미터가 누락되었습니다.'
            ]);
        }
        
        try {
            // 실제 PG 연결 테스트 로직
            $testResult = $this->performPGConnectionTest($provider, $settings, $bcoff_cd);
            
            return $this->response->setJSON([
                'result' => 'success',
                'message' => 'PG 연결 테스트 성공',
                'data' => $testResult
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'PG 연결 테스트 실패: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * VAN 연결 테스트
     */
    public function ajax_test_van_connection()
    {
        $provider = $this->request->getPost('provider');
        $settings = $this->request->getPost('settings');
        $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
        
        if (!$provider || !$bcoff_cd) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '필수 파라미터가 누락되었습니다.'
            ]);
        }
        
        try {
            // 실제 VAN 연결 테스트 로직
            $testResult = $this->performVANConnectionTest($provider, $settings, $bcoff_cd);
            
            return $this->response->setJSON([
                'result' => 'success',
                'message' => 'VAN 연결 테스트 성공',
                'data' => $testResult
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'VAN 연결 테스트 실패: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * PG 연결 테스트 수행
     */
    private function performPGConnectionTest($provider, $settings, $bcoff_cd)
    {
        // 필수 설정값 검증
        $required_fields = $this->getPGRequiredFields($provider);
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (empty($settings[$field])) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            return [
                'status' => 'error',
                'message' => '필수 설정값이 누락되었습니다: ' . implode(', ', $missing_fields),
                'test_mode' => true
            ];
        }
        
        // 실제 환경에서의 PG 연결 테스트 로직
        switch ($provider) {
            case 'inicis':
                return $this->testInicisConnection($settings);
            case 'kcp':
                return $this->testKCPConnection($settings);
            case 'toss':
                return $this->testTossConnection($settings);
            case 'welcome':
                return $this->testWelcomeConnection($settings);
            default:
                throw new Exception('지원하지 않는 PG 제공업체입니다.');
        }
    }

    /**
     * VAN 연결 테스트 수행
     */
    private function performVANConnectionTest($provider, $settings, $bcoff_cd)
    {
        // 필수 설정값 검증
        $required_fields = $this->getVANRequiredFields($provider);
        $missing_fields = [];
        
        foreach ($required_fields as $field) {
            if (empty($settings[$field])) {
                $missing_fields[] = $field;
            }
        }
        
        if (!empty($missing_fields)) {
            return [
                'status' => 'error',
                'message' => '필수 설정값이 누락되었습니다: ' . implode(', ', $missing_fields),
                'test_mode' => true
            ];
        }
        
        // 실제 환경에서의 VAN 연결 테스트 로직
        switch ($provider) {
            case 'kicc':
                return $this->testKICCConnection($settings);
            case 'nice':
                return $this->testNiceConnection($settings);
            case 'ksnet':
                return $this->testKSNetConnection($settings);
            default:
                throw new Exception('지원하지 않는 VAN 제공업체입니다.');
        }
    }

    /**
     * 이니시스 연결 테스트
     */
    private function testInicisConnection($settings)
    {
        try {
            // 이니시스 결제 서비스 인스턴스 생성
            $inicisService = new \App\Services\InicisPaymentService();
            $inicisService->initializeWithSettings($settings);
            
            // 실제 이니시스 API 연결 테스트 수행
            $result = $inicisService->testConnection();
            
            if ($result['status'] === 'success') {
                return [
                    'status' => 'success',
                    'response_time' => $result['response_time'],
                    'test_transaction_id' => $result['test_transaction_id'],
                    'api_version' => $result['api_version'] ?? 'N/A',
                    'merchant_id' => $result['merchant_id'] ?? 'N/A',
                    'test_mode' => ($settings['test_mode'] ?? 'Y') === 'Y' ? true : false,
                    'mid_format_valid' => $result['mid_format_valid'] ?? false,
                    'signkey_format_valid' => $result['signkey_format_valid'] ?? false
                ];
            } else {
                // 에러인 경우 메시지 전달
                return [
                    'status' => 'error',
                    'message' => $result['message'] ?? '연결 테스트 실패',
                    'response_time' => 'N/A',
                    'test_transaction_id' => 'FAILED_' . time()
                ];
            }
            
        } catch (Exception $e) {
            log_message('error', '이니시스 연결 테스트 실패: ' . $e->getMessage());
            
            // 실제 에러 메시지를 그대로 전달
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A',
                'test_transaction_id' => 'FAILED_' . time()
            ];
        }
    }

    /**
     * KCP 연결 테스트
     */
    private function testKCPConnection($settings)
    {
        try {
            // KCP 결제 서비스 로드
            $kcpService = new \App\Services\KcpPaymentService();
            $kcpService->initializeWithSettings($settings);
            
            // 실제 KCP API 연결 테스트 수행
            $result = $kcpService->testConnection();
            
            // status가 error인 경우에도 메시지 그대로 전달
            if ($result['status'] === 'error') {
                return [
                    'status' => 'error',
                    'message' => $result['message'] ?? 'KCP 연결 테스트 실패',
                    'response_time' => $result['response_time'] ?? 'N/A',
                    'test_transaction_id' => 'KCP_TEST_FAILED_' . time()
                ];
            }
            
            return $result;
            
        } catch (Exception $e) {
            // 오류 발생 시 실제 오류 메시지 반환
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A',
                'test_transaction_id' => 'KCP_TEST_FAILED_' . time()
            ];
        }
    }

    /**
     * 토스 연결 테스트
     */
    private function testTossConnection($settings)
    {
        try {
            // 토스페이먼츠 서비스 초기화
            $tossService = new \App\Services\TossPaymentService();
            $tossService->initializeWithSettings($settings);
            
            // 실제 토스페이먼츠 API 연결 테스트
            $result = $tossService->testConnection();
            
            if ($result['status'] === 'success') {
                return $result;
            } else {
                // 에러인 경우 메시지 전달
                return [
                    'status' => 'error',
                    'message' => $result['message'] ?? '연결 테스트 실패',
                    'response_time' => $result['response_time'] ?? 'N/A',
                    'test_transaction_id' => 'TOSS_TEST_FAILED_' . time()
                ];
            }
            
        } catch (Exception $e) {
            log_message('error', '토스페이먼츠 연결 테스트 실패: ' . $e->getMessage());
            
            // 실제 에러 메시지를 그대로 전달
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A',
                'test_transaction_id' => 'TOSS_ERROR_' . time()
            ];
        }
    }

    /**
     * 웰컴페이먼츠 연결 테스트
     */
    private function testWelcomeConnection($settings)
    {
        try {
            // 웰컴페이먼츠 서비스 초기화
            $welcomeService = new \App\Services\WelcomePaymentService();
            $welcomeService->initializeWithSettings($settings);
            
            // 실제 웰컴페이먼츠 API 연결 테스트
            $result = $welcomeService->testConnection();
            
            if ($result['status'] === 'success') {
                return $result;
            } else {
                // 에러인 경우 메시지 전달
                return [
                    'status' => 'error',
                    'message' => $result['message'] ?? '연결 테스트 실패',
                    'response_time' => $result['response_time'] ?? 'N/A',
                    'test_transaction_id' => 'WELCOME_TEST_FAILED_' . time()
                ];
            }
            
        } catch (Exception $e) {
            log_message('error', '웰컴페이먼츠 연결 테스트 실패: ' . $e->getMessage());
            
            // 실제 에러 메시지를 그대로 전달
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A',
                'test_transaction_id' => 'WELCOME_ERROR_' . time()
            ];
        }
    }

    /**
     * KICC VAN 연결 테스트
     */
    private function testKICCConnection($settings)
    {
        try {
            // KICC VAN 서비스 초기화
            $kiccVanService = new \App\Services\KiccVanService();
            $kiccVanService->initializeWithSettings($settings);
            
            // 실제 KICC VAN API 연결 테스트 수행
            $result = $kiccVanService->testConnection();
            
            // 그대로 반환 (에러 메시지 포함)
            return $result;
            
        } catch (Exception $e) {
            log_message('error', 'KICC VAN 연결 테스트 실패: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A',
                'test_transaction_id' => 'KICC_TEST_FAILED_' . time(),
                'van_provider' => 'KICC'
            ];
        }
    }

    /**
     * Nice VAN 연결 테스트
     */
    private function testNiceConnection($settings)
    {
        try {
            // Nice VAN 서비스 초기화
            $niceVanService = new \App\Services\NiceVanService();
            $niceVanService->initializeWithSettings($settings);
            
            // 실제 Nice VAN API 연결 테스트 수행
            $result = $niceVanService->testConnection();
            
            if ($result['status'] === 'success') {
                return [
                    'status' => 'success',
                    'response_time' => $result['response_time'],
                    'terminal_id' => $result['terminal_id'],
                    'merchant_id' => $result['merchant_id'],
                    'api_version' => $result['api_version'],
                    'test_mode' => $result['test_mode'],
                    'van_status' => $result['van_status'],
                    'van_provider' => 'NICE'
                ];
            } else {
                throw new Exception($result['message']);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Nice VAN 연결 테스트 실패: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A',
                'test_transaction_id' => 'NICE_TEST_FAILED_' . time(),
                'van_provider' => 'NICE'
            ];
        }
    }

    /**
     * KSNET VAN 연결 테스트
     */
    private function testKSNetConnection($settings)
    {
        try {
            // KSNET VAN 서비스 초기화
            $ksnetVanService = new \App\Services\KsnetVanService();
            $ksnetVanService->initializeWithSettings($settings);
            
            // 실제 KSNET VAN API 연결 테스트 수행
            $result = $ksnetVanService->testConnection();
            
            if ($result['status'] === 'success') {
                return [
                    'status' => 'success',
                    'response_time' => $result['response_time'],
                    'terminal_id' => $result['terminal_id'],
                    'store_id' => $result['store_id'],
                    'van_id' => $result['van_id'],
                    'api_version' => $result['api_version'],
                    'test_mode' => $result['test_mode'],
                    'van_status' => $result['van_status'],
                    'terminal_status' => $result['terminal_status'],
                    'van_provider' => 'KSNET'
                ];
            } else {
                throw new Exception($result['message']);
            }
            
        } catch (Exception $e) {
            log_message('error', 'KSNET VAN 연결 테스트 실패: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'response_time' => 'N/A',
                'test_transaction_id' => 'KSNET_TEST_FAILED_' . time(),
                'van_provider' => 'KSNET'
            ];
        }
    }

    /**
     * PG 제공업체별 필수 필드 정의
     */
    private function getPGRequiredFields($provider)
    {
        $required_fields = [
            'inicis' => ['mobile_mid', 'mobile_signkey'],  // 실제 폼 필드명과 일치
            'kcp' => ['site_cd', 'site_key'],
            'toss' => ['client_key', 'secret_key'],
            'welcome' => ['mid', 'merchant_key', 'api_key']  // 웰컴페이먼츠 필수 필드
        ];
        
        return $required_fields[$provider] ?? [];
    }
    
    /**
     * VAN 제공업체별 필수 필드 정의
     */
    private function getVANRequiredFields($provider)
    {
        $required_fields = [
            'kicc' => ['terminal_id', 'merchant_no'],
            'nice' => ['terminal_id', 'merchant_id'],
            'ksnet' => ['store_id', 'terminal_id']
        ];
        
        return $required_fields[$provider] ?? [];
    }
    
    /**
     * URL 파라미터나 세션에서 선택된 지점 코드 가져오기
     */
    private function getSelectedBranchCode()
    {
        $bcoff_cd = $this->request->getGet('bcoff_cd');
        if (!$bcoff_cd) {
            $bcoff_cd = $_SESSION['bcoff_cd'] ?? '';
        }
        return $bcoff_cd;
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                     [ 추가 PG/VAN 관리 메서드 ]
    // ============================================================================================================ //
    // ############################################################################################################ //

    /**
     * 제공업체별 상태 조회 AJAX
     */
    public function ajax_get_provider_status()
    {
        try {
            $bcoff_cd = $this->request->getGet('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            if (!$comp_cd || !$bcoff_cd) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            $pgVanModel = new PgVanSettingsModel();
            
            // PG 제공업체 상태 조회
            $pg_providers = ['inicis', 'kcp', 'toss'];
            $van_providers = ['kicc', 'nice', 'ksnet'];
            
            $provider_status = [];
            
            // PG 상태 조회
            foreach ($pg_providers as $provider) {
                $settings = $pgVanModel->getPgSettings($comp_cd, $bcoff_cd, $provider);
                $provider_status['pg'][$provider] = [
                    'enabled' => $settings['enabled'] ?? false,
                    'configured' => !empty($settings['merchant_id']),
                    'last_test' => $settings['last_test_date'] ?? null,
                    'test_status' => $settings['last_test_status'] ?? 'unknown'
                ];
            }
            
            // VAN 상태 조회
            foreach ($van_providers as $provider) {
                $settings = $pgVanModel->getVanSettings($comp_cd, $bcoff_cd, $provider);
                $provider_status['van'][$provider] = [
                    'enabled' => $settings['enabled'] ?? false,
                    'configured' => !empty($settings['terminal_id']),
                    'last_test' => $settings['last_test_date'] ?? null,
                    'test_status' => $settings['last_test_status'] ?? 'unknown'
                ];
            }
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => $provider_status
            ]);
            
        } catch (Exception $e) {
            log_message('error', '제공업체 상태 조회 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '제공업체 상태 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 제공업체 활성화/비활성화 AJAX
     */
    public function ajax_toggle_provider()
    {
        try {
            $provider_type = $this->request->getPost('provider_type'); // 'pg' or 'van'
            $provider_name = $this->request->getPost('provider_name');
            $enabled = $this->request->getPost('enabled') === 'true';
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            if (!$comp_cd || !$bcoff_cd || !$provider_type || !$provider_name) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            $pgVanModel = new PgVanSettingsModel();
            
            if ($provider_type === 'pg') {
                $current_settings = $pgVanModel->getPgSettings($comp_cd, $bcoff_cd, $provider_name);
                $current_settings['enabled'] = $enabled;
                $current_settings['modified_at'] = date('Y-m-d H:i:s');
                $current_settings['modified_by'] = $_SESSION['user_id'] ?? '';
                
                $result = $pgVanModel->savePgSettings($comp_cd, $bcoff_cd, $provider_name, $current_settings);
            } else {
                $current_settings = $pgVanModel->getVanSettings($comp_cd, $bcoff_cd, $provider_name);
                $current_settings['enabled'] = $enabled;
                $current_settings['modified_at'] = date('Y-m-d H:i:s');
                $current_settings['modified_by'] = $_SESSION['user_id'] ?? '';
                
                $result = $pgVanModel->saveVanSettings($comp_cd, $bcoff_cd, $provider_name, $current_settings);
            }
            
            if ($result) {
                // 로그 기록
                log_message('info', sprintf(
                    '제공업체 상태 변경: %s %s %s -> %s (사용자: %s, 지점: %s)',
                    $provider_type,
                    $provider_name,
                    $enabled ? '비활성화' : '활성화',
                    $enabled ? '활성화' : '비활성화',
                    $_SESSION['user_id'] ?? 'unknown',
                    $bcoff_cd
                ));
                
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => '제공업체 상태가 변경되었습니다.'
                ]);
            } else {
                throw new Exception('데이터베이스 업데이트 실패');
            }
            
        } catch (Exception $e) {
            log_message('error', '제공업체 토글 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '제공업체 상태 변경 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 설정 백업 생성 AJAX
     */
    public function ajax_backup_settings()
    {
        try {
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            $backup_name = $this->request->getPost('backup_name') ?? '수동백업_' . date('Y-m-d_H-i-s');
            
            if (!$comp_cd || !$bcoff_cd) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            $pgVanModel = new PgVanSettingsModel();
            
            // 전체 설정 조회
            $all_pg_settings = [];
            $all_van_settings = [];
            
            $pg_providers = ['inicis', 'kcp', 'toss'];
            $van_providers = ['kicc', 'nice', 'ksnet'];
            
            foreach ($pg_providers as $provider) {
                $all_pg_settings[$provider] = $pgVanModel->getPgSettings($comp_cd, $bcoff_cd, $provider);
            }
            
            foreach ($van_providers as $provider) {
                $all_van_settings[$provider] = $pgVanModel->getVanSettings($comp_cd, $bcoff_cd, $provider);
            }
            
            $backup_data = [
                'backup_name' => $backup_name,
                'backup_date' => date('Y-m-d H:i:s'),
                'backup_by' => $_SESSION['user_id'] ?? '',
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd,
                'pg_settings' => $all_pg_settings,
                'van_settings' => $all_van_settings
            ];
            
            // 백업 저장
            $result = $pgVanModel->createBackup($comp_cd, $bcoff_cd, $backup_data);
            
            if ($result) {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => '설정 백업이 생성되었습니다.',
                    'backup_id' => $result,
                    'backup_name' => $backup_name
                ]);
            } else {
                throw new Exception('백업 생성 실패');
            }
            
        } catch (Exception $e) {
            log_message('error', '설정 백업 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '설정 백업 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 백업에서 설정 복원 AJAX
     */
    public function ajax_restore_settings()
    {
        try {
            $backup_id = $this->request->getPost('backup_id');
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            if (!$comp_cd || !$bcoff_cd || !$backup_id) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            $pgVanModel = new PgVanSettingsModel();
            
            // 백업 데이터 조회
            $backup_data = $pgVanModel->getBackup($comp_cd, $bcoff_cd, $backup_id);
            
            if (!$backup_data) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '백업 데이터를 찾을 수 없습니다.'
                ]);
            }
            
            // 설정 복원
            $restored_count = 0;
            
            // PG 설정 복원
            if (isset($backup_data['pg_settings'])) {
                foreach ($backup_data['pg_settings'] as $provider => $settings) {
                    if ($pgVanModel->savePgSettings($comp_cd, $bcoff_cd, $provider, $settings)) {
                        $restored_count++;
                    }
                }
            }
            
            // VAN 설정 복원
            if (isset($backup_data['van_settings'])) {
                foreach ($backup_data['van_settings'] as $provider => $settings) {
                    if ($pgVanModel->saveVanSettings($comp_cd, $bcoff_cd, $provider, $settings)) {
                        $restored_count++;
                    }
                }
            }
            
            // 복원 로그 기록
            log_message('info', sprintf(
                '설정 복원 완료: 백업ID=%s, 복원개수=%d (사용자: %s, 지점: %s)',
                $backup_id,
                $restored_count,
                $_SESSION['user_id'] ?? 'unknown',
                $bcoff_cd
            ));
            
            return $this->response->setJSON([
                'result' => 'success',
                'message' => '설정이 복원되었습니다.',
                'restored_count' => $restored_count
            ]);
            
        } catch (Exception $e) {
            log_message('error', '설정 복원 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => '설정 복원 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                     [ 결제 처리 통합 메서드 ]
    // ============================================================================================================ //
    // ############################################################################################################ //

    /**
     * 결제 초기화 처리 - 적절한 서비스 선택하여 결제 시작
     */
    public function process_payment_init()
    {
        try {
            $payment_data = $this->request->getPost();
            $bcoff_cd = $payment_data['bcoff_cd'] ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            // 필수 파라미터 검증
            $required_fields = ['amount', 'order_id', 'payment_method', 'customer_name'];
            foreach ($required_fields as $field) {
                if (empty($payment_data[$field])) {
                    throw new Exception("필수 파라미터 '{$field}'가 누락되었습니다.");
                }
            }
            
            // 지점별 활성화된 PG 서비스 조회
            $pgVanModel = new PgVanSettingsModel();
            $available_pg = $this->getAvailablePaymentProvider($comp_cd, $bcoff_cd, $payment_data['payment_method']);
            
            if (!$available_pg) {
                throw new Exception('활성화된 결제 서비스가 없습니다.');
            }
            
            // PG 서비스별 결제 초기화
            $payment_result = null;
            switch ($available_pg['provider']) {
                case 'inicis':
                    $inicisService = new \App\Services\InicisPaymentService();
                    $inicisService->initializeWithSettings($available_pg['settings']);
                    $payment_result = $inicisService->initializePayment($payment_data);
                    break;
                    
                case 'kcp':
                    $kcpService = new \App\Services\KcpPaymentService();
                    $kcpService->initializeWithSettings($available_pg['settings']);
                    $payment_result = $kcpService->initializePayment($payment_data);
                    break;
                    
                case 'toss':
                    $tossService = new \App\Services\TossPaymentService();
                    $tossService->initializeWithSettings($available_pg['settings']);
                    $payment_result = $tossService->initializePayment($payment_data);
                    break;
                    
                default:
                    throw new Exception('지원하지 않는 PG 서비스입니다.');
            }
            
            // 결제 초기화 로그 기록
            $this->logPaymentActivity('INIT', $payment_data['order_id'], [
                'provider' => $available_pg['provider'],
                'amount' => $payment_data['amount'],
                'method' => $payment_data['payment_method'],
                'customer' => $payment_data['customer_name'],
                'bcoff_cd' => $bcoff_cd
            ]);
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => $payment_result,
                'provider' => $available_pg['provider']
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 초기화 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 결제 완료 콜백 처리
     */
    public function process_payment_callback()
    {
        try {
            $callback_data = $this->request->getPost();
            $provider = $callback_data['provider'] ?? '';
            $order_id = $callback_data['order_id'] ?? '';
            
            if (!$provider || !$order_id) {
                throw new Exception('필수 콜백 파라미터가 누락되었습니다.');
            }
            
            // 원본 결제 정보 조회
            $original_payment = $this->getPaymentByOrderId($order_id);
            if (!$original_payment) {
                throw new Exception('원본 결제 정보를 찾을 수 없습니다.');
            }
            
            // PG별 콜백 검증 및 처리
            $verification_result = null;
            switch ($provider) {
                case 'inicis':
                    $inicisService = new \App\Services\InicisPaymentService();
                    $verification_result = $inicisService->verifyCallback($callback_data);
                    break;
                    
                case 'kcp':
                    $kcpService = new \App\Services\KcpPaymentService();
                    $verification_result = $kcpService->verifyCallback($callback_data);
                    break;
                    
                case 'toss':
                    $tossService = new \App\Services\TossPaymentService();
                    $verification_result = $tossService->verifyCallback($callback_data);
                    break;
                    
                default:
                    throw new Exception('지원하지 않는 PG 제공업체입니다.');
            }
            
            // 결제 상태 업데이트
            $payment_status = $verification_result['success'] ? '01' : '99'; // 01: 성공, 99: 실패
            $this->updatePaymentStatus($order_id, $payment_status, $verification_result);
            
            // VAN 연동 처리 (카드 결제인 경우)
            if ($verification_result['success'] && $original_payment['payment_method'] === 'CARD') {
                $this->processVanIntegration($original_payment, $verification_result);
            }
            
            // 콜백 처리 로그 기록
            $this->logPaymentActivity('CALLBACK', $order_id, [
                'provider' => $provider,
                'status' => $payment_status,
                'verification_result' => $verification_result
            ]);
            
            return $this->response->setJSON([
                'result' => 'success',
                'payment_status' => $payment_status,
                'verification_result' => $verification_result
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 콜백 처리 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 결제 취소/환불 처리
     */
    public function process_payment_cancellation()
    {
        try {
            $cancel_data = $this->request->getPost();
            $order_id = $cancel_data['order_id'] ?? '';
            $cancel_reason = $cancel_data['cancel_reason'] ?? '고객 요청';
            $partial_amount = $cancel_data['partial_amount'] ?? null;
            
            if (!$order_id) {
                throw new Exception('주문번호가 누락되었습니다.');
            }
            
            // 원본 결제 정보 조회
            $original_payment = $this->getPaymentByOrderId($order_id);
            if (!$original_payment) {
                throw new Exception('원본 결제 정보를 찾을 수 없습니다.');
            }
            
            if ($original_payment['payment_status'] !== '01') {
                throw new Exception('완료된 결제만 취소할 수 있습니다.');
            }
            
            // PG별 취소 처리
            $cancel_result = null;
            $provider = $original_payment['pg_provider'];
            
            switch ($provider) {
                case 'inicis':
                    $inicisService = new \App\Services\InicisPaymentService();
                    $cancel_result = $inicisService->cancelPayment($original_payment, $cancel_reason, $partial_amount);
                    break;
                    
                case 'kcp':
                    $kcpService = new \App\Services\KcpPaymentService();
                    $cancel_result = $kcpService->cancelPayment($original_payment, $cancel_reason, $partial_amount);
                    break;
                    
                case 'toss':
                    $tossService = new \App\Services\TossPaymentService();
                    $cancel_result = $tossService->cancelPayment($original_payment, $cancel_reason, $partial_amount);
                    break;
                    
                default:
                    throw new Exception('지원하지 않는 PG 제공업체입니다.');
            }
            
            // 취소 상태 업데이트
            if ($cancel_result['success']) {
                $new_status = $partial_amount ? '02' : '99'; // 02: 부분취소, 99: 전체취소
                $this->updatePaymentStatus($order_id, $new_status, $cancel_result);
                
                // VAN 취소 처리 (카드 결제인 경우)
                if ($original_payment['payment_method'] === 'CARD') {
                    $this->processVanCancellation($original_payment, $cancel_result);
                }
            }
            
            // 취소 처리 로그 기록
            $this->logPaymentActivity('CANCEL', $order_id, [
                'provider' => $provider,
                'reason' => $cancel_reason,
                'partial_amount' => $partial_amount,
                'cancel_result' => $cancel_result
            ]);
            
            return $this->response->setJSON([
                'result' => $cancel_result['success'] ? 'success' : 'error',
                'message' => $cancel_result['message'],
                'cancel_result' => $cancel_result
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 취소 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 실시간 결제 상태 조회
     */
    public function get_payment_status()
    {
        try {
            $order_id = $this->request->getGet('order_id');
            $transaction_id = $this->request->getGet('transaction_id');
            
            if (!$order_id && !$transaction_id) {
                throw new Exception('주문번호 또는 거래번호가 필요합니다.');
            }
            
            // 데이터베이스에서 결제 정보 조회
            $payment_info = $order_id ? 
                $this->getPaymentByOrderId($order_id) : 
                $this->getPaymentByTransactionId($transaction_id);
                
            if (!$payment_info) {
                throw new Exception('결제 정보를 찾을 수 없습니다.');
            }
            
            // PG사 실시간 상태 조회
            $real_time_status = null;
            $provider = $payment_info['pg_provider'];
            
            switch ($provider) {
                case 'inicis':
                    $inicisService = new \App\Services\InicisPaymentService();
                    $real_time_status = $inicisService->getPaymentStatus($payment_info['transaction_id']);
                    break;
                    
                case 'kcp':
                    $kcpService = new \App\Services\KcpPaymentService();
                    $real_time_status = $kcpService->getPaymentStatus($payment_info['transaction_id']);
                    break;
                    
                case 'toss':
                    $tossService = new \App\Services\TossPaymentService();
                    $real_time_status = $tossService->getPaymentStatus($payment_info['transaction_id']);
                    break;
                    
                default:
                    throw new Exception('지원하지 않는 PG 제공업체입니다.');
            }
            
            // 상태 불일치 시 동기화
            if ($real_time_status && 
                $real_time_status['status'] !== $payment_info['payment_status']) {
                $this->syncPaymentStatus($payment_info['order_id'], $real_time_status);
            }
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => [
                    'order_id' => $payment_info['order_id'],
                    'transaction_id' => $payment_info['transaction_id'],
                    'status' => $payment_info['payment_status'],
                    'amount' => $payment_info['amount'],
                    'real_time_status' => $real_time_status,
                    'last_updated' => $payment_info['updated_at']
                ]
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 상태 조회 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 결제 데이터 동기화
     */
    public function sync_payment_data()
    {
        try {
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            $date_from = $this->request->getPost('date_from') ?? date('Y-m-d', strtotime('-7 days'));
            $date_to = $this->request->getPost('date_to') ?? date('Y-m-d');
            
            if (!$comp_cd || !$bcoff_cd) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }
            
            // 동기화 대상 결제 목록 조회
            $payments_to_sync = $this->getPaymentsForSync($comp_cd, $bcoff_cd, $date_from, $date_to);
            
            $sync_results = [
                'total' => count($payments_to_sync),
                'success' => 0,
                'failed' => 0,
                'updated' => 0
            ];
            
            foreach ($payments_to_sync as $payment) {
                try {
                    // PG별 실시간 상태 조회
                    $real_time_status = null;
                    switch ($payment['pg_provider']) {
                        case 'inicis':
                            $inicisService = new \App\Services\InicisPaymentService();
                            $real_time_status = $inicisService->getPaymentStatus($payment['transaction_id']);
                            break;
                            
                        case 'kcp':
                            $kcpService = new \App\Services\KcpPaymentService();
                            $real_time_status = $kcpService->getPaymentStatus($payment['transaction_id']);
                            break;
                            
                        case 'toss':
                            $tossService = new \App\Services\TossPaymentService();
                            $real_time_status = $tossService->getPaymentStatus($payment['transaction_id']);
                            break;
                    }
                    
                    if ($real_time_status) {
                        // 상태 불일치 시 업데이트
                        if ($real_time_status['status'] !== $payment['payment_status']) {
                            $this->syncPaymentStatus($payment['order_id'], $real_time_status);
                            $sync_results['updated']++;
                        }
                        $sync_results['success']++;
                    } else {
                        $sync_results['failed']++;
                    }
                    
                } catch (Exception $e) {
                    log_message('error', '개별 결제 동기화 실패: ' . $e->getMessage());
                    $sync_results['failed']++;
                }
            }
            
            // 동기화 로그 기록
            log_message('info', sprintf(
                '결제 데이터 동기화 완료: 전체=%d, 성공=%d, 실패=%d, 업데이트=%d (지점: %s)',
                $sync_results['total'],
                $sync_results['success'],
                $sync_results['failed'],
                $sync_results['updated'],
                $bcoff_cd
            ));
            
            return $this->response->setJSON([
                'result' => 'success',
                'message' => '결제 데이터 동기화가 완료되었습니다.',
                'sync_results' => $sync_results
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 데이터 동기화 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                        [ 관리 및 분석 메서드 ]
    // ============================================================================================================ //
    // ############################################################################################################ //

    /**
     * 결제 분석 리포트 생성
     */
    public function generate_payment_report()
    {
        try {
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            $report_type = $this->request->getPost('report_type') ?? 'summary';
            $date_from = $this->request->getPost('date_from') ?? date('Y-m-01');
            $date_to = $this->request->getPost('date_to') ?? date('Y-m-t');
            
            if (!$comp_cd || !$bcoff_cd) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }
            
            $report_data = [];
            
            switch ($report_type) {
                case 'summary':
                    $report_data = $this->generateSummaryReport($comp_cd, $bcoff_cd, $date_from, $date_to);
                    break;
                    
                case 'detailed':
                    $report_data = $this->generateDetailedReport($comp_cd, $bcoff_cd, $date_from, $date_to);
                    break;
                    
                case 'provider':
                    $report_data = $this->generateProviderReport($comp_cd, $bcoff_cd, $date_from, $date_to);
                    break;
                    
                case 'trend':
                    $report_data = $this->generateTrendReport($comp_cd, $bcoff_cd, $date_from, $date_to);
                    break;
                    
                default:
                    throw new Exception('지원하지 않는 리포트 타입입니다.');
            }
            
            // 리포트 생성 로그 기록
            log_message('info', sprintf(
                '결제 리포트 생성: 타입=%s, 기간=%s~%s (사용자: %s, 지점: %s)',
                $report_type,
                $date_from,
                $date_to,
                $_SESSION['user_id'] ?? 'unknown',
                $bcoff_cd
            ));
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => $report_data,
                'report_info' => [
                    'type' => $report_type,
                    'period' => $date_from . ' ~ ' . $date_to,
                    'generated_at' => date('Y-m-d H:i:s'),
                    'generated_by' => $_SESSION['user_id'] ?? ''
                ]
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 리포트 생성 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 결제 로그 내보내기
     */
    public function export_payment_logs()
    {
        try {
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            $export_format = $this->request->getPost('format') ?? 'excel';
            $date_from = $this->request->getPost('date_from') ?? date('Y-m-01');
            $date_to = $this->request->getPost('date_to') ?? date('Y-m-t');
            $log_level = $this->request->getPost('log_level') ?? 'all';
            
            if (!$comp_cd || !$bcoff_cd) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }
            
            // 로그 데이터 조회
            $log_data = $this->getPaymentLogs($comp_cd, $bcoff_cd, $date_from, $date_to, $log_level);
            
            if (empty($log_data)) {
                throw new Exception('내보낼 로그 데이터가 없습니다.');
            }
            
            // 형식별 내보내기 처리
            $export_result = null;
            switch ($export_format) {
                case 'excel':
                    $export_result = $this->exportToExcel($log_data, '결제로그_' . date('Y-m-d'));
                    break;
                    
                case 'csv':
                    $export_result = $this->exportToCsv($log_data, '결제로그_' . date('Y-m-d'));
                    break;
                    
                case 'json':
                    $export_result = $this->exportToJson($log_data, '결제로그_' . date('Y-m-d'));
                    break;
                    
                default:
                    throw new Exception('지원하지 않는 내보내기 형식입니다.');
            }
            
            // 내보내기 로그 기록
            log_message('info', sprintf(
                '결제 로그 내보내기: 형식=%s, 기간=%s~%s, 건수=%d (사용자: %s, 지점: %s)',
                $export_format,
                $date_from,
                $date_to,
                count($log_data),
                $_SESSION['user_id'] ?? 'unknown',
                $bcoff_cd
            ));
            
            return $this->response->setJSON([
                'result' => 'success',
                'export_result' => $export_result,
                'record_count' => count($log_data)
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 로그 내보내기 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 모든 PG/VAN 제공업체 테스트
     */
    public function test_all_providers()
    {
        try {
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            if (!$comp_cd || !$bcoff_cd) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }
            
            $pgVanModel = new PgVanSettingsModel();
            $test_results = [
                'pg' => [],
                'van' => [],
                'summary' => [
                    'total_tested' => 0,
                    'success_count' => 0,
                    'failed_count' => 0
                ]
            ];
            
            // PG 제공업체 테스트
            $pg_providers = ['inicis', 'kcp', 'toss'];
            foreach ($pg_providers as $provider) {
                $settings = $pgVanModel->getPgSettings($comp_cd, $bcoff_cd, $provider);
                
                if ($settings['enabled'] ?? false) {
                    $test_result = $this->performPGConnectionTest($provider, $settings, $bcoff_cd);
                    $test_results['pg'][$provider] = $test_result;
                    $test_results['summary']['total_tested']++;
                    
                    if ($test_result['status'] === 'success') {
                        $test_results['summary']['success_count']++;
                        
                        // 성공한 테스트 결과 저장
                        $settings['last_test_date'] = date('Y-m-d H:i:s');
                        $settings['last_test_status'] = 'success';
                        $pgVanModel->savePgSettings($comp_cd, $bcoff_cd, $provider, $settings);
                    } else {
                        $test_results['summary']['failed_count']++;
                        
                        // 실패한 테스트 결과 저장
                        $settings['last_test_date'] = date('Y-m-d H:i:s');
                        $settings['last_test_status'] = 'failed';
                        $settings['last_test_error'] = $test_result['message'] ?? '알 수 없는 오류';
                        $pgVanModel->savePgSettings($comp_cd, $bcoff_cd, $provider, $settings);
                    }
                } else {
                    $test_results['pg'][$provider] = [
                        'status' => 'skipped',
                        'message' => '비활성화된 제공업체'
                    ];
                }
            }
            
            // VAN 제공업체 테스트
            $van_providers = ['kicc', 'nice', 'ksnet'];
            foreach ($van_providers as $provider) {
                $settings = $pgVanModel->getVanSettings($comp_cd, $bcoff_cd, $provider);
                
                if ($settings['enabled'] ?? false) {
                    $test_result = $this->performVANConnectionTest($provider, $settings, $bcoff_cd);
                    $test_results['van'][$provider] = $test_result;
                    $test_results['summary']['total_tested']++;
                    
                    if ($test_result['status'] === 'success') {
                        $test_results['summary']['success_count']++;
                        
                        // 성공한 테스트 결과 저장
                        $settings['last_test_date'] = date('Y-m-d H:i:s');
                        $settings['last_test_status'] = 'success';
                        $pgVanModel->saveVanSettings($comp_cd, $bcoff_cd, $provider, $settings);
                    } else {
                        $test_results['summary']['failed_count']++;
                        
                        // 실패한 테스트 결과 저장
                        $settings['last_test_date'] = date('Y-m-d H:i:s');
                        $settings['last_test_status'] = 'failed';
                        $settings['last_test_error'] = $test_result['message'] ?? '알 수 없는 오류';
                        $pgVanModel->saveVanSettings($comp_cd, $bcoff_cd, $provider, $settings);
                    }
                } else {
                    $test_results['van'][$provider] = [
                        'status' => 'skipped',
                        'message' => '비활성화된 제공업체'
                    ];
                }
            }
            
            // 전체 테스트 로그 기록
            log_message('info', sprintf(
                '전체 제공업체 테스트 완료: 총=%d, 성공=%d, 실패=%d (사용자: %s, 지점: %s)',
                $test_results['summary']['total_tested'],
                $test_results['summary']['success_count'],
                $test_results['summary']['failed_count'],
                $_SESSION['user_id'] ?? 'unknown',
                $bcoff_cd
            ));
            
            return $this->response->setJSON([
                'result' => 'success',
                'test_results' => $test_results
            ]);
            
        } catch (Exception $e) {
            log_message('error', '전체 제공업체 테스트 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 지점별 결제 통계 조회
     */
    public function get_payment_statistics()
    {
        try {
            $bcoff_cd = $this->request->getGet('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            $period = $this->request->getGet('period') ?? 'monthly';
            $year = $this->request->getGet('year') ?? date('Y');
            $month = $this->request->getGet('month') ?? date('m');
            
            if (!$comp_cd || !$bcoff_cd) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }
            
            $statistics = [];
            
            switch ($period) {
                case 'daily':
                    $statistics = $this->getDailyStatistics($comp_cd, $bcoff_cd, $year, $month);
                    break;
                    
                case 'monthly':
                    $statistics = $this->getMonthlyStatistics($comp_cd, $bcoff_cd, $year);
                    break;
                    
                case 'yearly':
                    $statistics = $this->getYearlyStatistics($comp_cd, $bcoff_cd);
                    break;
                    
                default:
                    throw new Exception('지원하지 않는 통계 기간입니다.');
            }
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => $statistics,
                'period' => $period,
                'filters' => [
                    'comp_cd' => $comp_cd,
                    'bcoff_cd' => $bcoff_cd,
                    'year' => $year,
                    'month' => $month
                ]
            ]);
            
        } catch (Exception $e) {
            log_message('error', '결제 통계 조회 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                          [ 헬퍼 메서드들 ]
    // ============================================================================================================ //
    // ############################################################################################################ //

    /**
     * 사용 가능한 결제 제공업체 조회
     */
    private function getAvailablePaymentProvider($comp_cd, $bcoff_cd, $payment_method)
    {
        $pgVanModel = new PgVanSettingsModel();
        $pg_providers = ['inicis', 'kcp', 'toss'];
        
        // 우선순위 기반으로 활성화된 PG 찾기
        foreach ($pg_providers as $provider) {
            $settings = $pgVanModel->getPgSettings($comp_cd, $bcoff_cd, $provider);
            
            if (($settings['enabled'] ?? false) && 
                !empty($settings['merchant_id']) &&
                $this->supportsPaymentMethod($provider, $payment_method)) {
                return [
                    'provider' => $provider,
                    'settings' => $settings
                ];
            }
        }
        
        return null;
    }

    /**
     * 결제수단 지원 여부 확인
     */
    private function supportsPaymentMethod($provider, $payment_method)
    {
        $supported_methods = [
            'inicis' => ['CARD', 'BANK', 'VBANK', 'PHONE'],
            'kcp' => ['CARD', 'BANK', 'VBANK', 'PHONE'],
            'toss' => ['CARD', 'BANK', 'VBANK']
        ];
        
        return in_array($payment_method, $supported_methods[$provider] ?? []);
    }

    /**
     * 주문번호로 결제 정보 조회
     */
    private function getPaymentByOrderId($order_id)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT * FROM paymt_mgmt_tbl WHERE ORDER_ID = :order_id:";
        $result = $this->db->query($sql, ['order_id' => $order_id])->getResultArray();
        
        return !empty($result) ? $result[0] : null;
    }

    /**
     * 거래번호로 결제 정보 조회
     */
    private function getPaymentByTransactionId($transaction_id)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT * FROM paymt_mgmt_tbl WHERE TRANSACTION_ID = :transaction_id:";
        $result = $this->db->query($sql, ['transaction_id' => $transaction_id])->getResultArray();
        
        return !empty($result) ? $result[0] : null;
    }

    /**
     * 결제 상태 업데이트
     */
    private function updatePaymentStatus($order_id, $status, $additional_data = [])
    {
        $this->db = \Config\Database::connect();
        
        $update_data = [
            'PAYMT_STAT' => $status,
            'MOD_DATETM' => date('Y-m-d H:i:s')
        ];
        
        // 추가 데이터 처리
        if (isset($additional_data['transaction_id'])) {
            $update_data['TRANSACTION_ID'] = $additional_data['transaction_id'];
        }
        if (isset($additional_data['approval_no'])) {
            $update_data['APPROVAL_NO'] = $additional_data['approval_no'];
        }
        if (isset($additional_data['pg_response'])) {
            $update_data['PG_RESPONSE'] = json_encode($additional_data['pg_response']);
        }
        
        $sql = "UPDATE paymt_mgmt_tbl SET ";
        $sets = [];
        foreach ($update_data as $key => $value) {
            $sets[] = "$key = :$key:";
        }
        $sql .= implode(', ', $sets) . " WHERE ORDER_ID = :order_id:";
        
        $update_data['order_id'] = $order_id;
        
        return $this->db->query($sql, $update_data);
    }

    /**
     * VAN 연동 처리
     */
    private function processVanIntegration($payment_data, $verification_result)
    {
        try {
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            $bcoff_cd = $payment_data['bcoff_cd'] ?? '';
            
            $pgVanModel = new PgVanSettingsModel();
            $van_providers = ['kicc', 'nice', 'ksnet'];
            
            // 활성화된 VAN 서비스 찾기
            foreach ($van_providers as $provider) {
                $van_settings = $pgVanModel->getVanSettings($comp_cd, $bcoff_cd, $provider);
                
                if ($van_settings['enabled'] ?? false) {
                    // VAN 서비스별 연동 처리
                    switch ($provider) {
                        case 'kicc':
                            $kiccService = new \App\Services\KiccVanService();
                            $kiccService->processPaymentIntegration($payment_data, $verification_result);
                            break;
                            
                        case 'nice':
                            $niceService = new \App\Services\NiceVanService();
                            $niceService->processPaymentIntegration($payment_data, $verification_result);
                            break;
                            
                        case 'ksnet':
                            $ksnetService = new \App\Services\KsnetVanService();
                            $ksnetService->processPaymentIntegration($payment_data, $verification_result);
                            break;
                    }
                    break; // 첫 번째 활성화된 VAN만 사용
                }
            }
            
        } catch (Exception $e) {
            log_message('error', 'VAN 연동 처리 실패: ' . $e->getMessage());
        }
    }

    /**
     * VAN 취소 처리
     */
    private function processVanCancellation($payment_data, $cancel_result)
    {
        try {
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            $bcoff_cd = $payment_data['bcoff_cd'] ?? '';
            
            $pgVanModel = new PgVanSettingsModel();
            $van_providers = ['kicc', 'nice', 'ksnet'];
            
            // 활성화된 VAN 서비스 찾기
            foreach ($van_providers as $provider) {
                $van_settings = $pgVanModel->getVanSettings($comp_cd, $bcoff_cd, $provider);
                
                if ($van_settings['enabled'] ?? false) {
                    // VAN 서비스별 취소 처리
                    switch ($provider) {
                        case 'kicc':
                            $kiccService = new \App\Services\KiccVanService();
                            $kiccService->processCancellation($payment_data, $cancel_result);
                            break;
                            
                        case 'nice':
                            $niceService = new \App\Services\NiceVanService();
                            $niceService->processCancellation($payment_data, $cancel_result);
                            break;
                            
                        case 'ksnet':
                            $ksnetService = new \App\Services\KsnetVanService();
                            $ksnetService->processCancellation($payment_data, $cancel_result);
                            break;
                    }
                    break; // 첫 번째 활성화된 VAN만 사용
                }
            }
            
        } catch (Exception $e) {
            log_message('error', 'VAN 취소 처리 실패: ' . $e->getMessage());
        }
    }

    /**
     * 결제 활동 로그 기록
     */
    private function logPaymentActivity($activity_type, $order_id, $details = [])
    {
        $log_data = [
            'activity_type' => $activity_type,
            'order_id' => $order_id,
            'user_id' => $_SESSION['user_id'] ?? '',
            'comp_cd' => $_SESSION['comp_cd'] ?? '',
            'bcoff_cd' => $this->getSelectedBranchCode(),
            'details' => json_encode($details),
            'created_at' => date('Y-m-d H:i:s'),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()
        ];
        
        try {
            $this->db = \Config\Database::connect();
            $this->db->table('payment_activity_logs')->insert($log_data);
        } catch (Exception $e) {
            log_message('error', '결제 활동 로그 기록 실패: ' . $e->getMessage());
        }
    }

    /**
     * 결제 상태 동기화
     */
    private function syncPaymentStatus($order_id, $real_time_status)
    {
        try {
            $this->updatePaymentStatus($order_id, $real_time_status['status'], [
                'pg_response' => $real_time_status,
                'sync_date' => date('Y-m-d H:i:s')
            ]);
            
            log_message('info', "결제 상태 동기화 완료: $order_id -> " . $real_time_status['status']);
            
        } catch (Exception $e) {
            log_message('error', '결제 상태 동기화 실패: ' . $e->getMessage());
        }
    }

    /**
     * 동기화 대상 결제 목록 조회
     */
    private function getPaymentsForSync($comp_cd, $bcoff_cd, $date_from, $date_to)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT ORDER_ID, TRANSACTION_ID, PAYMT_STAT as payment_status, PG_PROVIDER as pg_provider 
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(PAYMT_DATE) BETWEEN :date_from: AND :date_to:
                AND PAYMT_STAT IN ('00', '01', '02')
                ORDER BY PAYMT_DATE DESC";
        
        return $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'date_from' => $date_from,
            'date_to' => $date_to
        ])->getResultArray();
    }

    /**
     * 일별 통계 조회
     */
    private function getDailyStatistics($comp_cd, $bcoff_cd, $year, $month)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT 
                    DATE(PAYMT_DATE) as payment_date,
                    COUNT(*) as total_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as success_amount,
                    ROUND(AVG(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END), 0) as avg_amount
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND YEAR(PAYMT_DATE) = :year:
                AND MONTH(PAYMT_DATE) = :month:
                GROUP BY DATE(PAYMT_DATE)
                ORDER BY payment_date";
        
        return $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'year' => $year,
            'month' => $month
        ])->getResultArray();
    }

    /**
     * 월별 통계 조회
     */
    private function getMonthlyStatistics($comp_cd, $bcoff_cd, $year)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT 
                    MONTH(PAYMT_DATE) as payment_month,
                    COUNT(*) as total_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as success_amount,
                    ROUND(AVG(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END), 0) as avg_amount
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND YEAR(PAYMT_DATE) = :year:
                GROUP BY MONTH(PAYMT_DATE)
                ORDER BY payment_month";
        
        return $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'year' => $year
        ])->getResultArray();
    }

    /**
     * 연별 통계 조회
     */
    private function getYearlyStatistics($comp_cd, $bcoff_cd)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT 
                    YEAR(PAYMT_DATE) as payment_year,
                    COUNT(*) as total_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as success_amount,
                    ROUND(AVG(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END), 0) as avg_amount
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                GROUP BY YEAR(PAYMT_DATE)
                ORDER BY payment_year DESC";
        
        return $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd
        ])->getResultArray();
    }

    /**
     * 요약 리포트 생성
     */
    private function generateSummaryReport($comp_cd, $bcoff_cd, $date_from, $date_to)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT 
                    COUNT(*) as total_transactions,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as successful_transactions,
                    SUM(CASE WHEN PAYMT_STAT = '99' THEN 1 ELSE 0 END) as failed_transactions,
                    SUM(CASE WHEN PAYMT_STAT = '02' THEN 1 ELSE 0 END) as cancelled_transactions,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as total_amount,
                    ROUND(AVG(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END), 0) as average_amount,
                    MIN(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END) as min_amount,
                    MAX(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END) as max_amount,
                    ROUND(SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as success_rate
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(PAYMT_DATE) BETWEEN :date_from: AND :date_to:";
        
        $result = $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'date_from' => $date_from,
            'date_to' => $date_to
        ])->getResultArray();
        
        return !empty($result) ? $result[0] : [];
    }

    /**
     * 상세 리포트 생성
     */
    private function generateDetailedReport($comp_cd, $bcoff_cd, $date_from, $date_to)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT 
                    ORDER_ID,
                    TRANSACTION_ID,
                    PAYMT_AMT,
                    PAYMT_MTHD,
                    PAYMT_STAT,
                    PG_PROVIDER,
                    CUST_NM,
                    PAYMT_DATE,
                    APPROVAL_NO
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(PAYMT_DATE) BETWEEN :date_from: AND :date_to:
                ORDER BY PAYMT_DATE DESC";
        
        return $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'date_from' => $date_from,
            'date_to' => $date_to
        ])->getResultArray();
    }

    /**
     * 제공업체별 리포트 생성
     */
    private function generateProviderReport($comp_cd, $bcoff_cd, $date_from, $date_to)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT 
                    PG_PROVIDER,
                    PAYMT_MTHD,
                    COUNT(*) as transaction_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as total_amount,
                    ROUND(AVG(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT END), 0) as avg_amount,
                    ROUND(SUM(CASE WHEN PAYMT_STAT = '01' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as success_rate
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(PAYMT_DATE) BETWEEN :date_from: AND :date_to:
                GROUP BY PG_PROVIDER, PAYMT_MTHD
                ORDER BY PG_PROVIDER, PAYMT_MTHD";
        
        return $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'date_from' => $date_from,
            'date_to' => $date_to
        ])->getResultArray();
    }

    /**
     * 추이 리포트 생성
     */
    private function generateTrendReport($comp_cd, $bcoff_cd, $date_from, $date_to)
    {
        $this->db = \Config\Database::connect();
        
        $sql = "SELECT 
                    DATE(PAYMT_DATE) as payment_date,
                    HOUR(PAYMT_DATE) as payment_hour,
                    COUNT(*) as transaction_count,
                    SUM(CASE WHEN PAYMT_STAT = '01' THEN PAYMT_AMT ELSE 0 END) as hourly_amount
                FROM paymt_mgmt_tbl 
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                AND DATE(PAYMT_DATE) BETWEEN :date_from: AND :date_to:
                GROUP BY DATE(PAYMT_DATE), HOUR(PAYMT_DATE)
                ORDER BY payment_date, payment_hour";
        
        return $this->db->query($sql, [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'date_from' => $date_from,
            'date_to' => $date_to
        ])->getResultArray();
    }

    /**
     * 결제 로그 조회
     */
    private function getPaymentLogs($comp_cd, $bcoff_cd, $date_from, $date_to, $log_level)
    {
        $this->db = \Config\Database::connect();
        
        $where_condition = "WHERE COMP_CD = :comp_cd: AND BCOFF_CD = :bcoff_cd: 
                           AND DATE(created_at) BETWEEN :date_from: AND :date_to:";
        
        if ($log_level !== 'all') {
            $where_condition .= " AND activity_type = :log_level:";
        }
        
        $sql = "SELECT * FROM payment_activity_logs $where_condition ORDER BY created_at DESC";
        
        $params = [
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'date_from' => $date_from,
            'date_to' => $date_to
        ];
        
        if ($log_level !== 'all') {
            $params['log_level'] = strtoupper($log_level);
        }
        
        return $this->db->query($sql, $params)->getResultArray();
    }

    /**
     * Excel 내보내기
     */
    private function exportToExcel($data, $filename)
    {
        // Excel 내보내기 로직 구현
        // 실제 구현에서는 PhpSpreadsheet 등의 라이브러리 사용
        return [
            'format' => 'excel',
            'filename' => $filename . '.xlsx',
            'download_url' => '/exports/' . $filename . '.xlsx',
            'file_size' => '예상 크기'
        ];
    }

    /**
     * CSV 내보내기
     */
    private function exportToCsv($data, $filename)
    {
        // CSV 내보내기 로직 구현
        return [
            'format' => 'csv',
            'filename' => $filename . '.csv',
            'download_url' => '/exports/' . $filename . '.csv',
            'file_size' => '예상 크기'
        ];
    }

    /**
     * JSON 내보내기
     */
    private function exportToJson($data, $filename)
    {
        // JSON 내보내기 로직 구현
        return [
            'format' => 'json',
            'filename' => $filename . '.json',
            'download_url' => '/exports/' . $filename . '.json',
            'file_size' => '예상 크기'
        ];
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                          [ POS 시스템 관리 메서드 ]
    // ============================================================================================================ //
    // ############################################################################################################ //

    /**
     * POS 설정 저장 AJAX
     */
    public function ajax_save_pos_settings()
    {
        try {
            $pos_settings = $this->request->getPost('pos_settings');
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            if (!$comp_cd || !$bcoff_cd) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            // pg_settings JSON 컬럼에 POS 설정 포함
            $this->db = \Config\Database::connect();
            
            // 기존 pg_settings 조회
            $sql = "SELECT pg_settings FROM bcoff_mgmt_tbl WHERE COMP_CD = ? AND BCOFF_CD = ?";
            $query = $this->db->query($sql, [$comp_cd, $bcoff_cd]);
            $result = $query->getRowArray();
            
            $pg_settings = json_decode($result['pg_settings'] ?? '{}', true);
            
            // POS 설정을 pg_settings에 포함
            $pg_settings['pos_settings'] = $pos_settings;
            $pg_settings['pos_last_modified'] = date('Y-m-d H:i:s');
            $pg_settings['pos_modified_by'] = $_SESSION['user_id'] ?? 'system';
            
            // 결제 우선순위 업데이트
            if ($pos_settings['use_pos'] && $pos_settings['pos_priority'] == '1') {
                $pg_settings['payment_method_priority'] = ['pos', 'pg', 'van', 'manual'];
            } elseif ($pos_settings['use_pos'] && $pos_settings['pos_priority'] == '2') {
                $pg_settings['payment_method_priority'] = ['pg', 'pos', 'van', 'manual'];
            } else {
                $pg_settings['payment_method_priority'] = ['pg', 'van', 'manual', 'pos'];
            }
            
            // DB 업데이트
            $update_sql = "UPDATE bcoff_mgmt_tbl SET 
                          pg_settings = ?,
                          MOD_ID = ?,
                          MOD_DATETM = NOW()
                          WHERE COMP_CD = ? AND BCOFF_CD = ?";
            
            $this->db->query($update_sql, [
                json_encode($pg_settings),
                $_SESSION['user_id'] ?? 'system',
                $comp_cd,
                $bcoff_cd
            ]);
            
            // 로그 기록
            log_message('info', sprintf(
                'POS 설정 저장: 지점=%s, 사용여부=%s, 유형=%s, 우선순위=%s',
                $bcoff_cd,
                $pos_settings['use_pos'] ? 'Y' : 'N',
                $pos_settings['pos_type'] ?? 'N/A',
                $pos_settings['pos_priority'] ?? 'N/A'
            ));
            
            return $this->response->setJSON([
                'result' => 'success',
                'message' => 'POS 설정이 저장되었습니다.'
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'POS 설정 저장 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'POS 설정 저장 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * POS 설정 조회 AJAX
     */
    public function ajax_get_pos_settings()
    {
        try {
            $bcoff_cd = $this->request->getGet('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            if (!$comp_cd || !$bcoff_cd) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            // pg_settings JSON 컬럼에서 POS 설정 조회
            $this->db = \Config\Database::connect();
            $sql = "SELECT pg_settings FROM bcoff_mgmt_tbl WHERE COMP_CD = ? AND BCOFF_CD = ?";
            $query = $this->db->query($sql, [$comp_cd, $bcoff_cd]);
            $result = $query->getRowArray();
            
            $pg_settings = json_decode($result['pg_settings'] ?? '{}', true);
            $pos_settings = $pg_settings['pos_settings'] ?? $this->getDefaultPosSettings();
            
            return $this->response->setJSON([
                'result' => 'success',
                'data' => $pos_settings
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'POS 설정 조회 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'POS 설정 조회 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * POS 연결 테스트 AJAX
     */
    public function ajax_test_pos_connection()
    {
        try {
            $settings = $this->request->getPost('settings');
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            
            if (!$settings || !$bcoff_cd) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            // POS 서비스 초기화
            $posService = new \App\Services\PosIntegrationService();
            $posService->initializeWithSettings($settings);
            
            // 연결 테스트 수행
            $testResult = $posService->testConnection();
            
            if ($testResult['status'] === 'success') {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => 'POS 연결 테스트 성공',
                    'data' => $testResult
                ]);
            } else {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => $testResult['message'] ?? 'POS 연결 테스트 실패'
                ]);
            }
            
        } catch (Exception $e) {
            log_message('error', 'POS 연결 테스트 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'POS 연결 테스트 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * POS 결제 시뮬레이션 AJAX
     */
    public function ajax_simulate_pos_payment()
    {
        try {
            $amount = $this->request->getPost('amount');
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->getSelectedBranchCode();
            $comp_cd = $_SESSION['comp_cd'] ?? '';
            
            if (!$amount || !$bcoff_cd || !$comp_cd) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => '필수 파라미터가 누락되었습니다.'
                ]);
            }

            // POS 서비스 초기화
            $posService = new \App\Services\PosIntegrationService();
            
            if (!$posService->initialize($bcoff_cd, $comp_cd)) {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => 'POS가 설정되지 않았거나 비활성화되어 있습니다.'
                ]);
            }
            
            // 시뮬레이션 결제 데이터
            $paymentData = [
                'amount' => $amount,
                'payment_method' => 'CARD',
                'order_id' => 'TEST_' . date('YmdHis'),
                'bcoff_cd' => $bcoff_cd,
                'test_mode' => true
            ];
            
            // POS 결제 요청
            $result = $posService->requestPayment($paymentData);
            
            if ($result['status'] === 'success') {
                return $this->response->setJSON([
                    'result' => 'success',
                    'message' => 'POS 결제 시뮬레이션 성공',
                    'data' => $result
                ]);
            } else {
                return $this->response->setJSON([
                    'result' => 'error',
                    'message' => $result['message'] ?? 'POS 결제 시뮬레이션 실패'
                ]);
            }
            
        } catch (Exception $e) {
            log_message('error', 'POS 결제 시뮬레이션 실패: ' . $e->getMessage());
            return $this->response->setJSON([
                'result' => 'error',
                'message' => 'POS 결제 시뮬레이션 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 기본 POS 설정 반환
     */
    private function getDefaultPosSettings()
    {
        return [
            'use_pos' => false,
            'pos_type' => 'integrated',
            'pos_priority' => '2',
            'integration_mode' => 'api',
            'pos_device' => [
                'manufacturer' => '',
                'model' => '',
                'serial_number' => ''
            ],
            'connection' => [
                'api_endpoint' => '',
                'api_key' => '',
                'com_port' => 'COM1',
                'baud_rate' => '9600',
                'data_bits' => '8',
                'parity' => 'N',
                'ip_address' => '',
                'port' => '9999'
            ],
            'fallback_options' => [
                'manual_card' => true,
                'cash' => true,
                'bank_transfer' => true,
                'pg' => false
            ],
            'auto_receipt' => true,
            'signature_pad' => false,
            'status' => 'disconnected'
        ];
    }
}