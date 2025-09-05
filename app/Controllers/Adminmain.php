<?php
namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Libraries\TreeStateVO;
use App\Libraries\TreeVO;
use App\Libraries\RoleMenuVO;

use App\Libraries\Ama_sno;
use App\Models\MemModel;
use App\Models\SnoModel;
use App\Models\TermsModel;
use App\Models\EventModel;
use App\Models\TcmgCompanyModel;
use App\Models\MenuModel;
use App\Models\AuthModel;

class Adminmain extends MainAdminController
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
            'title' => 'Admin DashBoard',
            'nav' => array('Dashboard' => '/dashboard'),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/dashboard',$data);
    }
    
     
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 대분류 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 메뉴를 저장한다.
     * @return json
     */
    public function saveCompMenu()
    {
         // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        $authModel = new AuthModel();
        $data["user_id"] = $_SESSION['user_id'];;


        $result = $authModel->deleteCompMenu($data);
        $compMenuList = $data["compMenuList"];
        if(!empty($compMenuList) && count($compMenuList) > 0)
        {
            $result = $authModel->insertCompMenu($data);
        }

        
        return $this->response->setJSON([
            'result' => $result
        ]);
    }

    /**
     * 메뉴를 저장한다.
     * @return json
     */
    public function saveBcoffMenu()
    {
         // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        $authModel = new AuthModel();
        $data["user_id"] = $_SESSION['user_id'];;


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
     * 메뉴를 저장한다.
     * @return json
     */
    public function saveRoleMenu()
    {
         // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        $authModel = new AuthModel();
        $data["user_id"] = $_SESSION['user_id'];


        $result = $authModel->deleteRoleMenu($data);
        $roleMenuList = $data["roleMenuList"];
        if(!empty($roleMenuList) && count($roleMenuList) > 0)
        {
            $result = $authModel->insertRoleMenu($data);
        }

        
        return $this->response->setJSON([
            'result' => $result
        ]);
    }
    
    
    
    /**
     * 권한의 사용자 리스트를 가져온다.
     */
    public function getRoleUserList()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);

        // `cdMenu` 값 확인
        $authModel = new AuthModel();
        
        // Cache에서 회사 코드 가져오기
        // 메뉴 리스트 가져오기
        $auth_list = $authModel->getRoleList($jsonData);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $auth_list // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }

    /**
     * 권한을 저장한다.
     * @return json
     */
    public function saveAuth()
    {
         // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 

        // if (empty($data['cdCompany']) || empty($data['cdMenu']) || empty($data['nmMenu'])) {
        //     return $this->response->setJSON([
        //         'status' => 'error',
        //         'message' => '필수 값이 누락되었습니다.'
        //     ]);
        // }

        $authModel = new AuthModel();
        $data["user_id"] = $_SESSION['user_id'];
        $data["comp_cd"] = "DYCIS";
        $data["bcoff_cd"] = "";
        if($data["type"] == "I")
        {
            // 모델에서 데이터 저장
            $result = $authModel->insertRole($data);
        } else
        {   
            $result = $authModel->updateRole($data);
        }

        
        return $this->response->setJSON([
            'result' => $result
        ]);
    }

    /**
     * 권한을 삭제한다.
     */
    public function deleteRole()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);
        
        $authModel = new AuthModel();
        $menuModel = new MenuModel();
        

        $delList = $jsonData["delList"];

        foreach ($delList as $item) {
            $roleList = $menuModel->getRoleMenuList($item);
            if($roleList != null && count($roleList) > 0)
            {
                $return_json = [
                    'status' => 'failed',
                    'result' => "권한-연결 리스트 존재" 
                ];
                break;
            }
            $item["user_id"] = "test";
            $auth_list = $authModel->deleteRole($item);
        }
        if(empty($return_json["status"]))
        {
                // JSON 응답 데이터 구성
            $return_json = [
                'status' => 'success',
                'result' => $auth_list // 기존 menu_List를 result로 변경
            ];
        }

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }


    /**
     * 권한 리스트를 가져온다.
     */
    public function getRoleList()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);
        
        $authModel = new AuthModel();
        $jsonData["comp_cd"] = "DYCIS";

        // 메뉴 리스트 가져오기
        $auth_list = $authModel->getRoleList($jsonData);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $auth_list // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }

    /**
     * 기본 권한관리
     */
    public function basic_role_manage()
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
        

        

        $this->viewPage('/admin/basic_role_manage',$data);
    }

    /**
     * 기본 권한 사용자 등록
     */
    public function basic_role_user_manage()
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
        
   
        // $companyModel = new TcmgCompanyModel();
        
        
        // $data['view']['company_list'] = $companyModel->list_company();
        

        $this->viewPage('/admin/basic_role_user_manage',$data);
    }
    
    /**
     * 어드민 메뉴(프로그램)등록관리
     */
    public function set_menu()
    {
        // ========================================================================
        // 선언부
        // ========================================================================
        $data = MenuHelper::getMenuData($this->request);
        // ========================================================================
        // 메뉴 리스트 가져오기
        // ========================================================================
        $menuModel = new MenuModel();
        $menu_List = $menuModel->list_menu($data);

        // // 트리 데이터로 변환
        // $tree = [];
        // foreach ($menu_List as $node) {
        //     $tree[$node['parent']][] = $node;
        // }

        // // 트리 HTML 렌더링
        // $treeHtml = $this->renderTree('root', $tree);

        // // 데이터 설정
        // $data["treeHtml"] = $treeHtml;
        $data["result"] = json_encode($menu_List, JSON_UNESCAPED_UNICODE);
        // 뷰 로드
        $this->viewPage('/admin/set_menu', $data);
    }

    /**
     * 재귀적으로 트리 HTML을 생성하는 함수
     */
    private function renderTree($parentId, $tree, $level = 1)
    {
        if (!isset($tree[$parentId])) {
            return '';
        }

        $html = '<ul class="jstree-container-ul jstree-children" role="group">';
        foreach ($tree[$parentId] as $node) {
            $icon = ($node['type'] === 'file') ? 'fa-file' : 'fa-folder';
            $hasChildren = isset($tree[$node['id']]);
            $nodeClass = $hasChildren ? 'jstree-node jstree-open' : 'jstree-node jstree-leaf';
            
            $html .= '<li role="none" id="' . $node['id'] . '" class="' . $nodeClass . '">';
            $html .= '<i class="jstree-icon jstree-ocl" role="presentation"></i>';
            $html .= '<a class="jstree-anchor" href="#" tabindex="-1" role="treeitem" aria-selected="false" aria-level="' . $level . '"' . ($hasChildren ? ' aria-expanded="true"' : '') . ' id="' . $node['id'] . '_anchor">';
            $html .= '<i class="jstree-icon jstree-themeicon fa ' . $icon . ' text-warning fa-lg jstree-themeicon-custom" role="presentation"></i> ' . $node['text'];
            $html .= '</a>';

            // 재귀 호출로 하위 노드 추가
            $html .= $this->renderTree($node['id'], $tree, $level + 1);
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }


    
    
    /**
     * 어드민 회사별 메뉴 설정
     */
    public function set_menu_comp()
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
        
   
        $companyModel = new TcmgCompanyModel();
        
        
        $data['view']['company_list'] = $companyModel->list_company();
        

        $this->viewPage('/admin/set_menu_comp',$data);
    }

    /**
     * 어드민 지점별 메뉴 설정
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
        
   
        $companyModel = new TcmgCompanyModel();
        
        
        $data['view']['company_list'] = $companyModel->list_company();
        

        $this->viewPage('/admin/set_menu_bcoff',$data);
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
     * 회사 리스트를 가져온다.
     */
    public function getCompList()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);
        
        $memModel = new MemModel();
        

        // 메뉴 리스트 가져오기
        $comp_list = $memModel->list_comp($jsonData);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $comp_list // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
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
        $compCd = "";
        if (isset($data['compCd'])) {
            $compCd = $data['compCd'];
            unset($data['compCd']);
        }

        // 메뉴 리스트 가져오기
        $result = $menuModel->list_menu($data);  // 배열 (메뉴 리스트)
        $data['compCd'] = $compCd;
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
     * 회사의 메뉴 리스트(회사메뉴반영)을 불러온다.
     * @return ArrayList
     */
    public function getMenuTreeListAtComp()
    {
        // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        $data["useFor"] = "MA";   //지점 메뉴를 가져온다.
        $menuModel = new MenuModel();
        $treeStateVO = new TreeStateVO();
        $treeVO = new TreeVO();
        $roleMenuVO = new RoleMenuVO();
        $compCd = "";
        if (isset($data['compCd'])) {
            $compCd = $data['compCd'];
            unset($data['compCd']);
        }

        // 메뉴 리스트 가져오기
        $result = $menuModel->list_menu($data);  // 배열 (메뉴 리스트)
        $data['compCd'] = $compCd;
        $roleMenuList = $menuModel->getCompMenuList($data); // 배열 (권한-메뉴 리스트)

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
     * 회사의 메뉴 리스트를(롤반영) 불러온다.
     * @return ArrayList
     */
    public function getMenuTreeListWithRole()
    {
        // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        
        $menuModel = new MenuModel();
        $treeStateVO = new TreeStateVO();
        $treeVO = new TreeVO();
        $roleMenuVO = new RoleMenuVO();
        $data["useFor"] = "MA";

        // 메뉴 리스트 가져오기
        $result = $menuModel->list_menu($data);  // 배열 (메뉴 리스트)
        $roleMenuList = $menuModel->getRoleMenuList($data); // 배열 (권한-메뉴 리스트)

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
     * 회사의의 메뉴 리스트를 불러온다.
     * @return ArrayList
     */
    public function getMenuTreeList()
    {
        // POST 데이터 가져오기
        $data = $this->request->getJSON(true); 
        
        $menuModel = new MenuModel();
        
        // Cache에서 회사 코드 가져오기

        // 메뉴 리스트 가져오기
        $menu_List = $menuModel->list_menu($data);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $menu_List // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }


    /**
     * 메뉴를 저장한다.
     * @return json
     */
    public function ajax_setMenuSave()
    {
         // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 

        // if (empty($data['cdCompany']) || empty($data['cdMenu']) || empty($data['nmMenu'])) {
        //     return $this->response->setJSON([
        //         'status' => 'error',
        //         'message' => '필수 값이 누락되었습니다.'
        //     ]);
        // }

        $menuModel = new MenuModel();

        // 모델에서 데이터 저장
        $result = $menuModel->saveMenu($data);

        
        return $this->response->setJSON([
            'status' => 'success',
            'result' => $result
        ]);
    }

    /**
     * 메뉴를 삭제한다.
     * @return json
     */
    public function ajax_setMenuDelete()
    {
        // JSON 데이터 받기
        $jsonData = $this->request->getJSON(true);
        $cdMenu = isset($jsonData['cdMenu']) ? $jsonData['cdMenu'] : null;

        // 메뉴 ID가 없으면 오류 반환
        if (!$cdMenu) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '메뉴 ID가 누락되었습니다.'
            ]);
        }

        $menuModel = new MenuModel();

        // 메뉴 삭제 시도
        $result = $menuModel->deleteMenu($cdMenu);

        if ($result > 0) {
            return $this->response->setJSON([
                'status' => 'success',
                'result' => $result
            ]);
        } elseif ($result == -9) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '하위 메뉴가 있는 경우 삭제할 수 없습니다.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '삭제 도중 오류가 발생했습니다.'
            ]);
        }
    }

    /**
     * 메뉴항목에 대한 상세내역을 불러온다.
     * @return json
     */
    public function getMenuInfo()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);

        // `cdMenu` 값 확인
        $cdMenu = isset($jsonData['cdMenu']) ? $jsonData['cdMenu'] : null;

        if (!$cdMenu) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'cdMenu 값이 누락되었습니다.'
            ]);
        }
        
        $menuModel = new MenuModel();
        
        // Cache에서 회사 코드 가져오기
        // $cateData['comp_cd'] = "KCES";

        // 메뉴 리스트 가져오기
        $menu_List = $menuModel->ajax_getMenuInfo($jsonData);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $menu_List // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }

    /**
     * 어드민 회원관리
     */
    public function all_mem_manage()
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
        $eventModel = new EventModel();
        
        
        $totalCount  = $model->admin_all_list_mem_count($boardPage->getVal());
        $mem_list = $model->admin_all_list_mem($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['smst']) ) $searchVal['smst'] = ''; // 회원상태
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; // 회원명
        if ( !isset($searchVal['stel']) ) $searchVal['stel'] = ''; // 전화번호
        if ( !isset($searchVal['sjp']) ) $searchVal['sjp'] = ''; // 가입장소
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색조건
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 점색시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색종료일
        
        
        $re_mem_list = array();
        $re_i = 0;
        foreach ($mem_list as $r)
        {
            $sData['comp_cd'] = $r['SET_COMP_CD'];
            $sData['bcoff_cd'] = $r['SET_BCOFF_CD'];
            
            $comp_name = $eventModel->get_comp_name_only($sData);
            $bcoff_name = $eventModel->get_bcoff_name_only($sData);
            
            $re_mem_list[$re_i] = $r;
            
            if (count($comp_name) > 0)
            {
                $re_mem_list[$re_i]['COMP_NM'] = $comp_name[0]['COMP_NM'];
                $re_mem_list[$re_i]['BCOFF_NM'] = $bcoff_name[0]['BCOFF_NM'];
            } else 
            {
                $re_mem_list[$re_i]['COMP_NM'] = "";
                $re_mem_list[$re_i]['BCOFF_NM'] = "";
            }
            
            $re_i++;
        }
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['mem_list'] = $re_mem_list;
        $this->viewPage('/admin/all_mem_manage',$data);
    }
    
    /**
     * 회원 탈퇴하기
     * @param string $mem_sno
     */
    public function admin_sece_member_proc()
    {
        $postVar = $this->request->getPost();
        
        $mem_sno = "";
        if (isset($postVar['mem_sno'])) $mem_sno = $postVar['mem_sno'];
        if ($mem_sno == "")
        {
            scriptAlert("잘못된 접근입니다. 로그아웃 됩니다.");
            scriptLocation('/login/errlogout');
        }
        
        // mem_id
        // sece_date
        $nn_now = new Time('now');
        $model = new MemModel();
        // 강사 아이디로 온것을 한번 더 검사하여 MEM_SNO 화 해서 처리함.
        
        // mem_info_detl_tbl 을 업데이트함
        // END_DATETM : sec_date . " 01:01:01";
        // MOD_ID : $_SESSION['user_id'];
        // MOD_DATETM : $nn_now
        // USE_YN : N
        
        $mdData['mem_sno'] = $mem_sno;
        $mdData['end_datetm'] = $nn_now;
        $mdData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $mdData['mod_datetm'] = $nn_now;
        $mdData['use_yn'] = "N";
        $model->update_member_sece_info_detal($mdData);
        
        // mem_main_info_tbl 을 업데이트함
        // MOD_ID : $_SESSION['user_id'];
        // MOD_DATETM : $nn_now
        // CONN_POSS_YN : N
        // USE_YN : N
        // SECE_DATETM : sec_date . " 01:01:01";
        
        $mmData['mem_sno'] = $mem_sno;
        $mmData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $mmData['mod_datetm'] = $nn_now;
        $mmData['conn_poss_yn'] = "N";
        $mmData['use_yn'] = "N";
        $mmData['sece_datetm'] = $nn_now;
        $model->update_member_sece_info($mmData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    
    
    /**
     * 설문조사 관리
     */
    public function survey_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '설문조사 관리',
            'nav' => array('설문조사 관리' => '/설문조사 관리'),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/survey_manage',$data);
    }
    
    
    
    public function ajax_code_chk()
    {
        //post : cate_cd
        $postVar = $this->request->getPost();
        $model = new AdminModel();
        
        $data['1rd_cate_cd'] = $postVar['cate_cd'];
        $cate_chk = $model->dup_one_cate_main_count($data);
        
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
     * 대분류 관리
     */
    public function one_cate_list()
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
        $model = new AdminModel();
        
        $totalCount  = $model->list_one_cate_main_count($boardPage->getVal());
        $one_cate_main_list = $model->list_one_cate_main($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
		//if ( !isset($searchVal['unm']) ) $searchVal['unm'] = '';
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['one_cate_main_list'] = $one_cate_main_list;
        $this->viewPage('/admin/one_cate_list',$data);
    }
    
    /**
     * 대분류 등록 처리
     */
    public function ajax_onc_cate_proc()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$model = new AdminModel();
    	$nn_now = new Time('now');
    	$lockr_set = "N";
    	
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * 1rd_cate_cd : 1차 카테고리 코드
    	 * cate_nm : 카테고리 명
    	 * grp_cate_set : 그룹 카테고리 설정
    	 * lockr_set (opt) : 락커 설정
    	 */
    	$postVar = $this->request->getPost();
    	
    	// ===========================================================================
    	// Model Data Set & Data Query Return
    	// ===========================================================================
    	if(isset($postVar['lockr_set'])) $lockr_set = "Y";
    	
    	$mdata['1rd_cate_cd'] = $postVar['1rd_cate_cd'];
    	$mdata['cate_nm'] = $postVar['cate_nm'];
    	$mdata['grp_cate_set'] = $postVar['grp_cate_set'];
    	$mdata['lockr_set'] = $lockr_set;
    	
    	$mdata['cre_id'] = $_SESSION['user_id'];
    	$mdata['cre_datetm'] = $nn_now;
    	$mdata['mod_id'] = $_SESSION['user_id'];
    	$mdata['mod_datetm'] = $nn_now;

    	$insert_cate_one = $model->insert_onc_cate($mdata);
    	
    	scriptAlert('등록되었습니다.');
    	scriptLocation('/adminmain/one_cate_list');
    	exit();
    	
    	//var_dump($insert_cate_one);
    	
    	// ===========================================================================
    	// Processs
    	// ===========================================================================
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 슈퍼관리자 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 슈퍼관리자 관리 [리스트]
     */
    public function sadmin_list()
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
    	$model = new AdminModel();
    	
    	$totalCount  = $model->list_sadmin_count($boardPage->getVal());
    	$one_cate_main_list = $model->list_sadmin($boardPage->getVal());
    	
    	$searchVal = $boardPage->getVal();
    	$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
    	
    	//if ( !isset($searchVal['unm']) ) $searchVal['unm'] = '';
    	
    	$data['view']['search_val'] = $searchVal;
    	$data['view']['pager'] = $boardPage->getPager($totalCount);
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	$data['view']['one_cate_main_list'] = $one_cate_main_list;
    	
    	$this->viewPage('/admin/sadmin_list',$data);
    }
    
    /**
     * 슈퍼관리자 아이디 중복 체크
     */
    public function ajax_sadmin_smgmt_id_chk()
    {
        $model = new AdminModel();
        // post : smgmt_id
        $postVar = $this->request->getPost();
        $data['smgmt_id'] = $postVar['smgmt_id'];
        
        $id_chk = $model->dup_sadmin_count($data);
        
        if ($id_chk == 0)
        {
            $return_json['result'] = 'true';
        } else
        {
            $return_json['result'] = 'false';
        }
        return json_encode($return_json);
    }
    
    /**
     * 회사코드 중복 체크
     */
    public function ajax_sadmin_comp_cd_chk()
    {
        $model = new SnoModel();
        // post : comp_cd
        $postVar = $this->request->getPost();
        $data['comp_cd'] = $postVar['comp_cd'];
        
        $code_chk = $model->list_zsno_bcoff_no_count($data);
        
        if ($code_chk == 0)
        {
            $return_json['result'] = 'true';
        } else
        {
            $return_json['result'] = 'false';
        }
        return json_encode($return_json);
    }
    
    /**
     * 슈퍼관리자 수정 정보
     */
    public function ajax_sadmin_smgmt_modify()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new AdminModel();
        $postVar = $this->request->getPost(); // comp_cd , smgmt_id
        
        $sdata['comp_cd'] = $postVar['comp_cd'];
        $sdata['smgmt_id'] = $postVar['smgmt_id'];
        $sinfo = $model->get_sadmin_info($sdata);
        
        $return_json['sinfo'] = $sinfo[0];
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 슈퍼관리자 수정 처리
     */
    public function ajax_sadmin_modify_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new AdminModel();
        $nn_now = new Time('now');
        
        
        // ===========================================================================
        // 전달받기
        // ===========================================================================
        /*
         * 1rd_cate_cd : 1차 카테고리 코드
         * cate_nm : 카테고리 명
         * grp_cate_set : 그룹 카테고리 설정
         * lockr_set (opt) : 락커 설정
         */
        $postVar = $this->request->getPost();
        
        // ===========================================================================
        // Model Data Set & Data Query Return
        // ===========================================================================
        
        $mdata['comp_cd'] 		= $postVar['comp_cd'];
        $mdata['smgmt_id'] 		= $postVar['smgmt_id'];
        $mdata['mngr_nm'] 		= $postVar['mngr_nm'];
        $mdata['mngr_telno'] 	= put_num($postVar['mngr_telno']);
        $mdata['ceo_nm'] 		= $postVar['ceo_nm'];
        $mdata['ceo_telno'] 	= put_num($postVar['ceo_telno']);
        $mdata['comp_nm'] 		= $postVar['comp_nm'];
        $mdata['comp_addr'] 	= $postVar['comp_addr'];
        $mdata['comp_telno'] 	= put_num($postVar['comp_telno']);
        $mdata['comp_memo'] 	= $postVar['comp_memo'];
        $mdata['comp_telno2'] 	= put_num($postVar['comp_telno2']);
        $mdata['mod_id'] = $_SESSION['user_id'];
        $mdata['mod_datetm'] = $nn_now;
        
        if ($postVar['smgmt_pwd'] != '')
        {
            $udata['comp_cd'] 		= $postVar['comp_cd'];
            $udata['smgmt_id'] 		= $postVar['smgmt_id'];
            $udata['smgmt_pwd'] 	= $this->enc_pass($postVar['smgmt_pwd']);
            $admin_pass_data = $model->update_sadmin_passwd($udata);
        }
        
        $sadmin_data = $model->update_sadmin($mdata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        scriptAlert("슈퍼관리자 수정이 완료 되었습니다.");
        scriptLocation("/adminmain/sadmin_list");
        exit();
    }
    
    
    /**
     * 슈퍼관리자 등록 처리
     */
    public function ajax_sadmin_insert_proc()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$model = new AdminModel();
    	$nn_now = new Time('now');
    	
    	
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * 1rd_cate_cd : 1차 카테고리 코드
    	 * cate_nm : 카테고리 명
    	 * grp_cate_set : 그룹 카테고리 설정
    	 * lockr_set (opt) : 락커 설정
    	 */
    	$postVar = $this->request->getPost();
    	
    	// ===========================================================================
    	// Model Data Set & Data Query Return
    	// ===========================================================================
    	
    	$mdata['comp_cd'] 		= $postVar['comp_cd'];
    	$mdata['smgmt_id'] 		= $postVar['smgmt_id'];
    	$mdata['smgmt_pwd'] 	= $this->enc_pass($postVar['smgmt_pwd']);
    	$mdata['mngr_nm'] 		= $postVar['mngr_nm'];
    	$mdata['mngr_telno'] 	= put_num($postVar['mngr_telno']);
    	$mdata['ceo_nm'] 		= $postVar['ceo_nm'];
    	$mdata['ceo_telno'] 	= put_num($postVar['ceo_telno']);
    	$mdata['comp_nm'] 		= $postVar['comp_nm'];
    	$mdata['comp_addr'] 	= $postVar['comp_addr'];
    	$mdata['comp_telno'] 	= put_num($postVar['comp_telno']);
    	$mdata['comp_memo'] 	= $postVar['comp_memo'];
    	$mdata['comp_telno2'] 	= put_num($postVar['comp_telno2']);
    	
    	$mdata['cre_id'] = $_SESSION['user_id'];
    	$mdata['cre_datetm'] = $nn_now;
    	$mdata['mod_id'] = $_SESSION['user_id'];
    	$mdata['mod_datetm'] = $nn_now;
    	
    	$sadmin_data = $model->insert_sadmin($mdata);
    	// 지점코드 부여를 위한 테이블 생성
    	// 회사코드가 이미 데이터베이스에 있는지 검사한다.
    	// 검사결과 없으면 insert 한다. 있으면 패스한다.
    	$amasno = new Ama_sno();
    	$amasno->insert_bcoff_no($postVar['comp_cd']);
    	
    	// ===========================================================================
    	// Processs
    	// ===========================================================================
    	scriptAlert("슈퍼관리자 등록이 완료 되었습니다.");
    	scriptLocation("/adminmain/sadmin_list");
    	exit();
    	
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
        $data = MenuHelper::getMenuData($this->request);
    	
    	// ===========================================================================
    	// Board Pagzing
    	// ===========================================================================
    	
    	$initVar['post'] = $this->request->getPost();
    	$initVar['get'] = $this->request->getGet();
    	
    	$boardPage = new Ama_board($initVar);
    	$model = new AdminModel();
    	
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
    	$this->viewPage('/admin/bc_appct_manage',$data);
    	
    }
    
    /**
     * 지점신청관리 수정정보 불러오기
     * @return string
     */
    public function ajax_bcoff_appct_mgmt_modify()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new AdminModel();
        $postVar = $this->request->getPost(); // bcoff_appct_mgmt_sno
        
        $bdata['bcoff_appct_mgmt_sno'] = $postVar['bcoff_appct_mgmt_sno'];
        $binfo = $model->get_bcoff_appct_mgmt($bdata);
        
        $return_json['binfo'] = $binfo[0];
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 지정관리 수정처리
     */
    public function ajax_modify_bc_appct_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new AdminModel();
        $postVar = $this->request->getPost(); // bcoff_appct_mgmt_sno
        
        /*
         * bcoff_appct_mgmt_sno
         * bcoff_mgmt_id
         * bcoff_mgmt_pwd
         * - - mngr_nm
         * - - mngr_telno
         * - - ceo_nm
         * - - ceo_telno
         * - - bcoff_nm
         * - - bcoff_addr
         * - - bcoff_telno
         * - - bcoff_telno2
         * - - bcoff_memo
         */
        
        $nn_now = new Time('now');
        
        $udata['mod_id'] = 'admin';
        $udata['mod_datetm'] = $nn_now;
        
        $udata['bcoff_appct_mgmt_sno'] = $postVar['bcoff_appct_mgmt_sno'];
        
        $udata['bcoff_mgmt_id'] = $postVar['bcoff_mgmt_id'];
        $udata['mem_id'] = $postVar['bcoff_mgmt_id'];
        
        $udata['bcoff_mgmt_pwd'] = $this->enc_pass($postVar['bcoff_mgmt_pwd']);
        $udata['mcoff_mgmt_pwd'] = $this->enc_pass($postVar['bcoff_mgmt_pwd']);
        $udata['mem_pwd'] = $this->enc_pass($postVar['bcoff_mgmt_pwd']);
        
        $udata['mngr_nm'] = $postVar['mngr_nm'];
        $udata['mem_nm'] = $postVar['mngr_nm'];
        
        $udata['mngr_telno'] = put_num($postVar['mngr_telno']);
        $udata['mem_telno'] = put_num($postVar['mngr_telno']);
        
        $re_phone_num = $this->enc_phone(put_num($postVar['mngr_telno']));
        $udata['mem_telno_enc'] = $re_phone_num['enc'];
        $udata['mem_telno_mask'] = $re_phone_num['mask'];
        $udata['mem_telno_short'] = $re_phone_num['short'];
        $udata['mem_telno_enc2'] = $re_phone_num['enc2'];
        
        $udata['ceo_nm'] = $postVar['ceo_nm'];
        $udata['ceo_telno'] = put_num($postVar['ceo_telno']);
        $udata['bcoff_nm'] = $postVar['bcoff_nm'];
        $udata['bcoff_addr'] = $postVar['bcoff_addr'];
        $udata['bcoff_telno'] = put_num($postVar['bcoff_telno']);
        $udata['bcoff_telno2'] = put_num($postVar['bcoff_telno2']);
        $udata['bcoff_memo'] = $postVar['bcoff_memo'];
        
        $model->update_admin_bcoff_appct_mgmt($udata);
        $model->update_admin_bcoff_mgmt($udata);
        $model->update_admin_mem_main_info($udata);
        $model->update_admin_mem_info_detl($udata);
        
        if ($postVar['bcoff_mgmt_pwd'] != '')
        {
            $model->update_admin_bcoff_appct_mgmt_passwd($udata);
            $model->update_admin_bcoff_mgmt_passwd($udata);
            $model->update_admin_mem_main_info_passwd($udata);
        }
        
        // bcoff_appct_mgmt_tbl 테이블에 업데이트 한다.  BCOFF_MGMT_PWD
        /**
         * WHERE BCOFF_APPCT_MGMT_SNO
         * MNGR_NM
         * MNGR_TELNO
         * CEO_NM
         * CEO_TELNO
         * BCOFF_NM
         * BCOFF_ADDR
         * BCOFF_TELNO
         * BCOFF_TELNO2
         * BCOFF_MEMO
         */
        
        // bcoff_mgmt_tbl 테이블에 업데이트 한다. MCOFF_MGMT_PWD
        /**
         * WHERE BCOFF_MGMT_ID
         * MNGR_NM
         * MNGR_TELNO
         * CEO_NM
         * CEO_TELNO
         * BCOFF_NM
         * BCOFF_ADDR
         * BCOFF_TELNO
         * BCOFF_TELNO2
         * BCOFF_MEMO
         */
        
        // mem_main_info_tbl 테이블에 업데이트 한다.
        /**
         * WHERE MEM_ID = BCOFF_MGMT_ID , BCOFF_MGMT_ID
         * MEM_NM , MNGR_NM
         * MEM_TELNO, MNGR_TELNO
         */
        
        // mem_info_detl_tbl 테이블에 업데이트 한다.
        /**
         * WHERE MEM_ID = BCOFF_MGMT_ID , BCOFF_MGMT_ID
         * MEM_NM, MNGR_NM
         * MEM_TELNO, MNGR_TELNO
         */
        
        // TODO tchr_info_detl_hist_tbl 테비을에 업데이트 하는지 점검이 필요함
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        scriptAlert("지점관리자 수정이 완료 되었습니다.");
        scriptLocation("/adminmain/bc_appct_manage");
        exit();
    }
    
    /**
     * 지점 신청 관리 [승인]
     */
    public function bc_appct_appv_proc()
    {
        $nn_now = new Time('now');
    	// ===========================================================================
    	// 전달받기
    	// ===========================================================================
    	/*
    	 * bcoff_appct_mgmt_sno : 지점신청관리 일련번호
    	 */
    	$postVar = $this->request->getPost();
    	
    	// ===========================================================================
    	// Model Data Set & Data Query Return
    	// ===========================================================================
    	$model = new AdminModel();
    	
    	// 지점신청관리 일련번호 정보를 가져온다.
    	$appct_info_data['bcoff_appct_mgmt_sno'] = $postVar['bcoff_appct_mgmt_sno'];
    	$bcoff_appct_info = $model->get_bcoff_appct_mgmt($appct_info_data);
    	// 지점코드를 생성한다.
    	$amasno = new Ama_sno();
    	$bcoff_cd = $amasno->create_bcoff_no($bcoff_appct_info[0]['COMP_CD']);
    	
    	// 지점관리에 insert 한다.
    	
    	$mdata['comp_cd']          = $bcoff_appct_info[0]['COMP_CD'];
    	$mdata['bcoff_cd']         = $bcoff_cd;
    	$mdata['bcoff_mgmt_id']    = $bcoff_appct_info[0]['BCOFF_MGMT_ID'];
    	$mdata['mngr_nm']          = $bcoff_appct_info[0]['MNGR_NM'];
    	$mdata['mcoff_mgmt_pwd']   = $bcoff_appct_info[0]['BCOFF_MGMT_PWD'];
    	$mdata['mngr_telno']       = $bcoff_appct_info[0]['MNGR_TELNO'];
    	$mdata['ceo_nm']           = $bcoff_appct_info[0]['CEO_NM'];
    	$mdata['ceo_telno']        = $bcoff_appct_info[0]['CEO_TELNO'];
    	$mdata['bcoff_nm']         = $bcoff_appct_info[0]['BCOFF_NM'];
    	$mdata['bcoff_addr']       = $bcoff_appct_info[0]['BCOFF_ADDR'];
    	$mdata['bcoff_telno']      = $bcoff_appct_info[0]['BCOFF_TELNO'];
    	$mdata['bcoff_telno2']     = $bcoff_appct_info[0]['BCOFF_TELNO2'];
    	$mdata['bcoff_memo']       = $bcoff_appct_info[0]['BCOFF_MEMO'];
    	$mdata['affi_set']         = "N";
    	$mdata['affi_amt']         = 0;
    	
    	$mdata['cre_id'] = $_SESSION['user_id'];
    	$mdata['cre_datetm'] = $nn_now;
    	$mdata['mod_id'] = $_SESSION['user_id'];
    	$mdata['mod_datetm'] = $nn_now;
    	
    	$insert_bcoff_mgmt = $model->insert_bcoff_mgmt($mdata);
    	
    	// 지점신청관리에 상태와 지점번호를 업데이트 한다. 0000-00-00 00:00:00
    	
    	$updata['bcoff_appct_stat']            = "01"; // 승인
    	$updata['bcoff_cd']                    = $bcoff_cd;
    	$updata['bcoff_appv_datetm']           = $nn_now;
    	$updata['bcoff_refus_datetm']       ="0000-00-00 00:00:00";
    	$updata['mod_id']                      = $_SESSION['user_id'];
    	$updata['mod_datetm']                  = $nn_now;
    	$updata['bcoff_appct_mgmt_sno']        = $postVar['bcoff_appct_mgmt_sno'];
    	
    	$update_bcoff_appct_mgmt = $model->update_bcoff_appct_mgmt($updata);
    	// 회원메인정보 insert
    	
    	$mem_sno = $amasno->create_mem_sno();
    	
    	$minfo['mem_sno']      = $mem_sno['mem_sno']; // 채번이 필요함
    	$minfo['mem_id']       = $bcoff_appct_info[0]['BCOFF_MGMT_ID'];
    	$minfo['mem_pwd']      = $bcoff_appct_info[0]['BCOFF_MGMT_PWD'];
    	$minfo['mem_nm']       = $bcoff_appct_info[0]['MNGR_NM'];
    	$minfo['qr_cd']        = "";
    	$minfo['bthday']       = "";
    	$minfo['mem_gendr']    = "";
    	$minfo['mem_telno']    = $bcoff_appct_info[0]['MNGR_TELNO'];
    	
    	//$denc_telno = $this->denc_tel($bcoff_appct_info[0]['MNGR_TELNO']);
    	$re_phone_num = $this->enc_phone(put_num($bcoff_appct_info[0]['MNGR_TELNO']));
    	$minfo['mem_telno_enc'] = $re_phone_num['enc'];
    	$minfo['mem_telno_mask'] = $re_phone_num['mask'];
    	$minfo['mem_telno_short'] = $re_phone_num['short'];
    	$minfo['mem_telno_enc2'] = $re_phone_num['enc2'];
    	
    	$minfo['mem_addr']     = "";
    	$minfo['mem_main_img'] = "";
    	$minfo['mem_thumb_img']    = "";
    	$minfo['mem_dv']       = "T";
    	$minfo['set_comp_cd']       = $bcoff_appct_info[0]['COMP_CD'];
    	$minfo['set_bcoff_cd']      = $bcoff_cd;
    	$minfo['cre_id'] = $_SESSION['user_id'];
    	$minfo['cre_datetm'] = $nn_now;
    	$minfo['mod_id'] = $_SESSION['user_id'];
    	$minfo['mod_datetm'] = $nn_now;
    	
    	$insert_mem_main_info = $model->insert_mem_main_info($minfo);
    	
    	
    	// 강사정보상세 insert
    	$minfod['mem_sno']         = $mem_sno['mem_sno']; //회원_일련번호
    	$minfod['comp_cd']         = $bcoff_appct_info[0]['COMP_CD']; // 회사_코드
    	$minfod['bcoff_cd']        = $bcoff_cd; // 지점_코드
    	$minfod['mem_id']          = $bcoff_appct_info[0]['BCOFF_MGMT_ID']; // 회원_아이디
    	$minfod['mem_nm']          = $bcoff_appct_info[0]['MNGR_NM'];
    	$minfod['mem_dv']          = "T"; // 회원_구분
    	
    	// 지점 총 관리자라 하더라도 추가 정보의 입력을 위하여 처음에 00 가입회원으로 셋팅을 한다.
    	// TODO 이후에 첫 로그인시 가입회원 상태를 점검하여 필수 항목을 추가로 받고 01의 현재회원 상태로 업데이트 해야한다.
    	$minfod['mem_stat']        = "00"; // 회원_상태 (가입회원) 
    	$minfod['qr_cd']           = "";
    	$minfod['bthday']          = "";
    	$minfod['mem_gendr']       = "";
    	$minfod['mem_telno']       = $bcoff_appct_info[0]['MNGR_TELNO'];
    	$minfod['mem_telno_enc'] = $re_phone_num['enc'];
    	$minfod['mem_telno_mask'] = $re_phone_num['mask'];
    	$minfod['mem_telno_short'] = $re_phone_num['short'];
    	$minfod['mem_telno_enc2'] = $re_phone_num['enc2'];
    	$minfod['mem_addr']        = "";
    	$minfod['mem_main_img']    = "";
    	$minfod['mem_thumb_img']   = "";
    	
    	$minfod['tchr_posn']       = "10";
    	$minfod['ctrct_type']      = "10";
    	$minfod['tchr_simp_pwd']   = "12345678";
    	
    	$minfod['cre_id'] = $_SESSION['user_id'];
    	$minfod['cre_datetm'] = $nn_now;
    	$minfod['mod_id'] = $_SESSION['user_id'];
    	$minfod['mod_datetm'] = $nn_now;
    	
    	$insert_tchr_info_detl = $model->insert_mem_info_detl_tbl($minfod);
    	
    	// 강사정보상세 내역 insert
    	$minfoh['tchr_info_detl_hist_sno'] = $mem_sno['mem_sno_hist'];
    	$minfoh['tchr_info_detl_sno']      = $mem_sno['mem_sno'];
    	$minfoh['mem_sno']         = $mem_sno['mem_sno'];
    	$minfoh['comp_cd']         = $bcoff_appct_info[0]['COMP_CD'];
    	$minfoh['bcoff_cd']        = $bcoff_cd;
    	$minfoh['mem_id']          = $bcoff_appct_info[0]['BCOFF_MGMT_ID'];
    	$minfoh['mem_dv']          = "T";
    	$minfoh['tchr_posn']       = "10";
    	$minfoh['ctrct_type']      = "10";
    	$minfoh['tchr_simp_pwd']   = "12345678";
    	
    	$minfoh['cre_id'] = $_SESSION['user_id'];
    	$minfoh['cre_datetm'] = $nn_now;
    	$minfoh['mod_id'] = $_SESSION['user_id'];
    	$minfoh['mod_datetm'] = $nn_now;
    	
    	$insert_tchr_info_detl_hist = $model->insert_tchr_info_detl_hist($minfoh);
    	
    	scriptAlert("지점관리자 승인이 완료 되었습니다.");
    	scriptLocation("/adminmain/bc_appct_manage");
    	exit();
    	
    	//_vardump($bcoff_cd);
    	//_vardump($bcoff_appct_info);
    }
    
    /**
     * 기본약관 관리
     */
    public function terms_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);        
        $termsModel = new TermsModel();
        
        $tdata['terms_comp_cd'] = "";
        $tdata['terms_bcoff_cd'] = "";
        
        $terms_list = $termsModel->list_terms($tdata);
        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================
        
//         $initVar['post'] = $this->request->getPost();
//         $initVar['get'] = $this->request->getGet();
        
//         $boardPage = new Ama_board($initVar);
//         $model = new AdminModel();
        
//         $totalCount  = $model->bc_appct_count($boardPage->getVal());
//         $bc_appct_list = $model->list_bc_appct($boardPage->getVal());
        
//         $searchVal = $boardPage->getVal();
//         $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
//         //if ( !isset($searchVal['unm']) ) $searchVal['unm'] = '';
        
//         $data['view']['search_val'] = $searchVal;
//         $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['terms_list'] = $terms_list;
        $this->viewPage('/admin/terms_manage',$data);
    }
    
  
    /**
     * 기본 약관 생성 처리
     */
    public function terms_insert_proc()
    {
        $nn_now = new Time('now');
        $model = new TermsModel();
        // ===========================================================================
        // 전달받기
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $insVar['terms_knd_cd'] = $postVar['terms_knd_cd'];
        $insVar['terms_comp_cd'] = "";
        $insVar['terms_bcoff_cd'] = "";
        $insVar['terms_round'] = $postVar['terms_round'];
        $insVar['terms_title'] = $postVar['terms_title'];
        $insVar['terms_conts'] = $postVar['terms_conts'];
        $insVar['terms_use_yn'] = $postVar['terms_use_yn'];
        $insVar['terms_date'] = $postVar['terms_date'];
        
        $insVar['cre_id'] = $_SESSION['user_id'];
        $insVar['cre_datetm'] = $nn_now;
        $insVar['mod_id'] = $_SESSION['user_id'];
        $insVar['mod_datetm'] = $nn_now;
        
        // 이전 회차가 있는지를 검사하여 이전 회차의 사용여부를 사용안함으로 업데이트 한다.
        $model->update_terms_manage_flag_n($insVar);
        $model->insert_terms_manage($insVar);
        
        scriptAlert("기본 약관등록이 완료 되었습니다.");
        scriptLocation("/adminmain/terms_manage");
        exit();
    }
    
    /**
     * 기본 약관 개정하기 (회차추가)
     * @param string $terms_knd_cd
     */
    public function terms_add_round_form($terms_knd_cd = '')
    {
        // 약관 코드를 이용하여 마지막 회차 정보를 가져온다.
        $termsModel = new TermsModel();
        
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);        
        $tdata['terms_comp_cd'] = "";
        $tdata['terms_bcoff_cd'] = "";
        $tdata['terms_knd_cd'] = $terms_knd_cd;
        
        $getTerms = $termsModel->get_last_terms($tdata);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['terms_info'] = $getTerms[0];
        $this->viewPage('/admin/terms_add_round_form',$data);
    }
    
      /**
     * 기본 약관 생성 폼
     */
    public function terms_insert_form()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/terms_insert_form',$data);
    }
    
    


    /**
     * 기본 약관 수정하기
     * @param string $terms_knd_cd
     * @param string $terms_round
     */
    public function terms_modify_form($terms_knd_cd = '', $terms_round = '')
    {
        
    }
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 로그 관리 시스템 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 로그 대시보드
     */
    public function log_dashboard()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '로그 대시보드';
        $data['nav'] = array('시스템 관리' => '', '로그 관리' => '', '로그 대시보드' => '/adminmain/log_dashboard');
        
        // 모델 로드
        $logModel = model('LogAnalysisModel');
        
        // 대시보드 통계 데이터 조회
        $dashboardStats = $logModel->getDashboardStats($_SESSION['comp_cd'] ?? null, $_SESSION['bcoff_cd'] ?? null);
        
        $data['view']['today_stats'] = $dashboardStats['today_stats'] ?? [];
        $data['view']['trend_data'] = $dashboardStats['trend_data'] ?? [];
        $data['view']['top_errors'] = $dashboardStats['top_errors'] ?? [];
        $data['view']['affected_users'] = $dashboardStats['affected_users'] ?? 0;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/log_dashboard', $data);
    }
    
    /**
     * 로그 검색/조회
     */
    public function log_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '로그 조회';
        $data['nav'] = array('시스템 관리' => '', '로그 관리' => '', '로그 조회' => '/adminmain/log_search');
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/log_search', $data);
    }
    
    /**
     * 로그 상세 분석
     */
    public function log_analysis($errorId = null)
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '오류 분석';
        $data['nav'] = array('시스템 관리' => '', '로그 관리' => '', '오류 분석' => '/adminmain/log_analysis');
        
        if ($errorId) {
            $logModel = model('LogAnalysisModel');
            $errorDetail = $logModel->getErrorDetail($errorId);
            $data['view']['error'] = $errorDetail;
        } else {
            $data['view']['error'] = null;
        }
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/log_analysis', $data);
    }
    
    /**
     * 오류 수정
     */
    public function log_fix($errorId = null)
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '오류 수정';
        $data['nav'] = array('시스템 관리' => '', '로그 관리' => '', '오류 수정' => '/adminmain/log_fix');
        
        if ($errorId) {
            $logModel = model('LogAnalysisModel');
            $errorDetail = $logModel->getErrorDetail($errorId);
            $data['view']['error'] = $errorDetail;
            
            // 유사 수정 이력 조회
            if ($errorDetail && isset($errorDetail['error_hash'])) {
                $data['view']['similar_fixes'] = $logModel->db->table('error_fix_history')
                    ->join('log_analysis', 'error_fix_history.error_id = log_analysis.id')
                    ->where('log_analysis.error_hash', $errorDetail['error_hash'])
                    ->where('error_fix_history.error_id !=', $errorId)
                    ->orderBy('error_fix_history.fixed_at', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();
            } else {
                $data['view']['similar_fixes'] = [];
            }
        } else {
            $data['view']['error'] = null;
            $data['view']['similar_fixes'] = [];
        }
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/log_fix', $data);
    }
    
    /**
     * 로그 설정
     */
    public function log_settings()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '로그 설정';
        $data['nav'] = array('시스템 관리' => '', '로그 관리' => '', '로그 설정' => '/adminmain/log_settings');
        
        // 알림 설정 조회
        $db = \Config\Database::connect();
        $data['view']['alert_settings'] = [];
        
        if ($db->tableExists('log_alert_settings')) {
            $alertSettings = $db->table('log_alert_settings')
                               ->orderBy('created_at', 'DESC')
                               ->get()
                               ->getResultArray();
            $data['view']['alert_settings'] = $alertSettings;
        }
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/log_settings', $data);
    }
    
    /**
     * 로그 통계
     */
    public function log_statistics()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '로그 통계';
        $data['nav'] = array('시스템 관리' => '', '로그 관리' => '', '로그 통계' => '/adminmain/log_statistics');
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/admin/log_statistics', $data);
    }
    
    // ============================================================================================================ //
    //                                          [ AJAX 메서드 ]
    // ============================================================================================================ //
    
    /**
     * 로그 데이터 조회 (AJAX)
     */
    public function getLogData()
    {
        $type = $this->request->getPost('type');
        $logModel = model('LogAnalysisModel');
        
        if ($type === 'dashboard') {
            // 대시보드용 데이터
            $dashboardStats = $logModel->getDashboardStats($_SESSION['comp_cd'] ?? null, $_SESSION['bcoff_cd'] ?? null);
            return $this->response->setJSON([
                'success' => true,
                'data' => $dashboardStats
            ]);
        } else {
            // 일반 검색
            $filters = $this->request->getPost();
            $result = $logModel->searchLogs($filters);
            return $this->response->setJSON($result);
        }
    }
    
    /**
     * 오류 상세 정보 조회 (AJAX)
     */
    public function getErrorDetail()
    {
        $errorId = $this->request->getPost('error_id');
        
        if (!$errorId) {
            return $this->response->setJSON(['success' => false, 'message' => '오류 ID가 필요합니다.']);
        }
        
        $logModel = model('LogAnalysisModel');
        $errorDetail = $logModel->getErrorDetail($errorId);
        
        if (!$errorDetail) {
            return $this->response->setJSON(['success' => false, 'message' => '오류 정보를 찾을 수 없습니다.']);
        }
        
        return $this->response->setJSON(['success' => true, 'data' => $errorDetail]);
    }
    
    /**
     * 오류 수정 제안 (AJAX)
     */
    public function suggestFix()
    {
        $errorId = $this->request->getPost('error_id');
        
        if (!$errorId) {
            return $this->response->setJSON(['success' => false, 'message' => '오류 ID가 필요합니다.']);
        }
        
        $logModel = model('LogAnalysisModel');
        $errorDetail = $logModel->getErrorDetail($errorId);
        
        if (!$errorDetail) {
            return $this->response->setJSON(['success' => false, 'message' => '오류 정보를 찾을 수 없습니다.']);
        }
        
        // 오류 타입에 따른 수정 제안 생성
        $suggestions = $this->generateFixSuggestions($errorDetail);
        
        return $this->response->setJSON(['success' => true, 'suggestions' => $suggestions]);
    }
    
    /**
     * 오류 수정 적용 (AJAX)
     */
    public function applyFix()
    {
        $errorId = $this->request->getPost('error_id');
        $fixData = [
            'resolved_by' => $_SESSION['user_id'],
            'file_path' => $this->request->getPost('file_path'),
            'original_code' => $this->request->getPost('original_code'),
            'fixed_code' => $this->request->getPost('fixed_code'),
            'fix_description' => $this->request->getPost('fix_description'),
            'is_auto_fix' => $this->request->getPost('is_auto_fix') ?? false
        ];
        
        $logModel = model('LogAnalysisModel');
        
        // 파일에 수정 사항 적용
        if ($fixData['file_path'] && $fixData['fixed_code']) {
            $filePath = $fixData['file_path'];
            
            // 백업 생성
            $backupPath = $filePath . '.backup.' . date('YmdHis');
            copy($filePath, $backupPath);
            
            // 코드 수정 적용
            $fileContent = file_get_contents($filePath);
            $fileContent = str_replace($fixData['original_code'], $fixData['fixed_code'], $fileContent);
            file_put_contents($filePath, $fileContent);
        }
        
        // DB 업데이트
        $result = $logModel->resolveError($errorId, $fixData);
        
        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => '오류가 성공적으로 수정되었습니다.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => '오류 수정 중 문제가 발생했습니다.']);
        }
    }
    
    /**
     * 로그 통계 데이터 조회 (AJAX)
     */
    public function getLogStatistics()
    {
        $period = $this->request->getPost('period') ?? 'today';
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        
        $logModel = model('LogAnalysisModel');
        $db = \Config\Database::connect();
        
        // 기간 설정
        if ($startDate && $endDate) {
            // 커스텀 기간
        } else {
            switch ($period) {
                case 'today':
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d');
                    break;
                case 'week':
                    $startDate = date('Y-m-d', strtotime('-7 days'));
                    $endDate = date('Y-m-d');
                    break;
                case 'month':
                    $startDate = date('Y-m-d', strtotime('-30 days'));
                    $endDate = date('Y-m-d');
                    break;
                case 'quarter':
                    $startDate = date('Y-m-d', strtotime('-90 days'));
                    $endDate = date('Y-m-d');
                    break;
            }
        }
        
        $result = [
            'summary' => [
                'total_errors' => 0,
                'resolution_rate' => 0,
                'avg_resolution_time' => 0,
                'affected_systems' => 0
            ],
            'trends' => [
                'error_trend' => 0,
                'resolution_trend' => 0,
                'time_trend' => 0,
                'system_trend' => 0,
                'daily' => []
            ],
            'distribution' => [],
            'hourly' => array_fill(0, 24, 0),
            'user_impact' => [],
            'comparison' => [],
            'top_errors' => [],
            'longest_unresolved' => []
        ];
        
        // 테이블이 존재하는 경우에만 통계 조회
        if ($db->tableExists('log_analysis')) {
            // 요약 통계
            $summary = $db->table('log_analysis')
                ->selectCount('id', 'total_errors')
                ->selectAvg('CASE WHEN is_resolved = 1 THEN 1 ELSE 0 END', 'resolution_rate')
                ->selectAvg('CASE WHEN is_resolved = 1 AND resolved_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, first_occurred, resolved_at) ELSE NULL END', 'avg_resolution_time')
                ->where('log_date >=', $startDate . ' 00:00:00')
                ->where('log_date <=', $endDate . ' 23:59:59')
                ->get()
                ->getRowArray();
            
            $result['summary'] = [
                'total_errors' => $summary['total_errors'] ?? 0,
                'resolution_rate' => round(($summary['resolution_rate'] ?? 0) * 100, 1),
                'avg_resolution_time' => round($summary['avg_resolution_time'] ?? 0),
                'affected_systems' => $db->table('log_analysis')
                    ->select('COUNT(DISTINCT file_path) as count')
                    ->where('log_date >=', $startDate . ' 00:00:00')
                    ->where('log_date <=', $endDate . ' 23:59:59')
                    ->get()
                    ->getRow()
                    ->count ?? 0
            ];
            
            // 일별 트렌드
            $dailyTrend = $db->table('log_analysis')
                ->select('DATE(log_date) as date, error_level, COUNT(*) as count')
                ->where('log_date >=', $startDate . ' 00:00:00')
                ->where('log_date <=', $endDate . ' 23:59:59')
                ->groupBy(['DATE(log_date)', 'error_level'])
                ->orderBy('date')
                ->get()
                ->getResultArray();
            
            // 데이터 재구성
            $trendData = [];
            foreach ($dailyTrend as $row) {
                if (!isset($trendData[$row['date']])) {
                    $trendData[$row['date']] = [
                        'date' => $row['date'],
                        'critical' => 0,
                        'error' => 0,
                        'warning' => 0
                    ];
                }
                $trendData[$row['date']][strtolower($row['error_level'])] = $row['count'];
            }
            $result['trends']['daily'] = array_values($trendData);
            
            // 오류 분포
            $distribution = $db->table('log_analysis')
                ->select('error_level, COUNT(*) as count')
                ->where('log_date >=', $startDate . ' 00:00:00')
                ->where('log_date <=', $endDate . ' 23:59:59')
                ->groupBy('error_level')
                ->get()
                ->getResultArray();
            
            foreach ($distribution as $row) {
                $result['distribution'][$row['error_level']] = $row['count'];
            }
            
            // 시간대별 분포
            $hourly = $db->table('log_analysis')
                ->select('HOUR(log_date) as hour, COUNT(*) as count')
                ->where('log_date >=', $startDate . ' 00:00:00')
                ->where('log_date <=', $endDate . ' 23:59:59')
                ->groupBy('HOUR(log_date)')
                ->get()
                ->getResultArray();
            
            foreach ($hourly as $row) {
                $result['hourly'][$row['hour']] = $row['count'];
            }
            
            // 사용자별 영향도
            $userImpact = $db->table('log_analysis')
                ->select('user_id, user_name, COUNT(*) as error_count')
                ->where('log_date >=', $startDate . ' 00:00:00')
                ->where('log_date <=', $endDate . ' 23:59:59')
                ->where('user_id IS NOT NULL')
                ->groupBy(['user_id', 'user_name'])
                ->orderBy('error_count', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();
            
            $result['user_impact'] = $userImpact;
            
            // TOP 오류
            $topErrors = $db->table('log_analysis')
                ->select('id, error_message, COUNT(*) as count')
                ->where('log_date >=', $startDate . ' 00:00:00')
                ->where('log_date <=', $endDate . ' 23:59:59')
                ->groupBy('error_hash')
                ->orderBy('count', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
            
            $result['top_errors'] = $topErrors;
            
            // 미해결 오류
            $unresolvedErrors = $db->table('log_analysis')
                ->select('id, error_message, DATEDIFF(NOW(), first_occurred) as days_unresolved')
                ->where('is_resolved', false)
                ->orderBy('first_occurred', 'ASC')
                ->limit(5)
                ->get()
                ->getResultArray();
            
            $result['longest_unresolved'] = $unresolvedErrors;
            
            // 이전 기간과 비교
            $prevStartDate = date('Y-m-d', strtotime($startDate . ' -' . (strtotime($endDate) - strtotime($startDate)) / 86400 . ' days'));
            $prevEndDate = date('Y-m-d', strtotime($startDate . ' -1 day'));
            
            // 현재 기간 통계
            $currentStats = $db->table('log_analysis')
                ->selectCount('id', 'total_errors')
                ->selectCount('CASE WHEN error_level = "CRITICAL" THEN 1 END', 'critical_errors')
                ->selectAvg('CASE WHEN is_resolved = 1 AND resolved_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, first_occurred, resolved_at) ELSE NULL END', 'avg_resolution_time')
                ->selectCount('DISTINCT user_id', 'affected_users')
                ->selectAvg('CASE WHEN is_resolved = 1 THEN 1 ELSE 0 END', 'resolution_rate')
                ->where('log_date >=', $startDate . ' 00:00:00')
                ->where('log_date <=', $endDate . ' 23:59:59')
                ->get()
                ->getRowArray();
            
            // 이전 기간 통계
            $prevStats = $db->table('log_analysis')
                ->selectCount('id', 'total_errors')
                ->selectCount('CASE WHEN error_level = "CRITICAL" THEN 1 END', 'critical_errors')
                ->selectAvg('CASE WHEN is_resolved = 1 AND resolved_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, first_occurred, resolved_at) ELSE NULL END', 'avg_resolution_time')
                ->selectCount('DISTINCT user_id', 'affected_users')
                ->selectAvg('CASE WHEN is_resolved = 1 THEN 1 ELSE 0 END', 'resolution_rate')
                ->where('log_date >=', $prevStartDate . ' 00:00:00')
                ->where('log_date <=', $prevEndDate . ' 23:59:59')
                ->get()
                ->getRowArray();
            
            // 비교 데이터 생성
            $result['comparison'] = [
                'total_errors' => [
                    'current' => $currentStats['total_errors'] ?? 0,
                    'previous' => $prevStats['total_errors'] ?? 0,
                    'change_rate' => $this->calculateChangeRate($currentStats['total_errors'] ?? 0, $prevStats['total_errors'] ?? 0)
                ],
                'critical_errors' => [
                    'current' => $currentStats['critical_errors'] ?? 0,
                    'previous' => $prevStats['critical_errors'] ?? 0,
                    'change_rate' => $this->calculateChangeRate($currentStats['critical_errors'] ?? 0, $prevStats['critical_errors'] ?? 0)
                ],
                'avg_resolution_time' => [
                    'current' => $this->formatTime($currentStats['avg_resolution_time'] ?? 0),
                    'previous' => $this->formatTime($prevStats['avg_resolution_time'] ?? 0),
                    'change_rate' => $this->calculateChangeRate($currentStats['avg_resolution_time'] ?? 0, $prevStats['avg_resolution_time'] ?? 0)
                ],
                'affected_users' => [
                    'current' => $currentStats['affected_users'] ?? 0,
                    'previous' => $prevStats['affected_users'] ?? 0,
                    'change_rate' => $this->calculateChangeRate($currentStats['affected_users'] ?? 0, $prevStats['affected_users'] ?? 0)
                ],
                'resolution_rate' => [
                    'current' => round(($currentStats['resolution_rate'] ?? 0) * 100, 1),
                    'previous' => round(($prevStats['resolution_rate'] ?? 0) * 100, 1),
                    'change_rate' => $this->calculateChangeRate(($currentStats['resolution_rate'] ?? 0) * 100, ($prevStats['resolution_rate'] ?? 0) * 100)
                ]
            ];
            
            // 트렌드 계산
            $result['trends'] = [
                'error_trend' => $this->calculateChangeRate($currentStats['total_errors'] ?? 0, $prevStats['total_errors'] ?? 0),
                'resolution_trend' => $this->calculateChangeRate(($currentStats['resolution_rate'] ?? 0) * 100, ($prevStats['resolution_rate'] ?? 0) * 100),
                'time_trend' => $this->calculateChangeRate($currentStats['avg_resolution_time'] ?? 0, $prevStats['avg_resolution_time'] ?? 0),
                'system_trend' => $this->calculateChangeRate($result['summary']['affected_systems'], 0),
                'daily' => $result['trends']['daily']
            ];
        }
        
        return $this->response->setJSON($result);
    }
    
    /**
     * 변화율 계산
     */
    private function calculateChangeRate($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }
    
    /**
     * 시간 포맷
     */
    private function formatTime($minutes)
    {
        if ($minutes < 60) {
            return round($minutes) . '분';
        } elseif ($minutes < 1440) {
            return round($minutes / 60, 1) . '시간';
        } else {
            return round($minutes / 1440, 1) . '일';
        }
    }
    
    /**
     * 오류 타입에 따른 수정 제안 생성
     */
    private function generateFixSuggestions($errorDetail)
    {
        $suggestions = [];
        $errorMessage = $errorDetail['error_message'];
        
        // Undefined index 오류
        if (strpos($errorMessage, 'Undefined index:') !== false) {
            preg_match('/Undefined index: (.+)/', $errorMessage, $matches);
            $varName = $matches[1] ?? '';
            
            $suggestions[] = [
                'type' => 'null_coalescing',
                'description' => 'Null 병합 연산자 사용',
                'original' => '$_SESSION["' . $varName . '"]',
                'suggested' => '$_SESSION["' . $varName . '"] ?? \'\'',
                'explanation' => '변수가 정의되지 않았을 때 기본값을 제공합니다.'
            ];
            
            $suggestions[] = [
                'type' => 'isset_check',
                'description' => 'isset() 체크 추가',
                'original' => 'echo $_SESSION["' . $varName . '"]',
                'suggested' => 'echo isset($_SESSION["' . $varName . '"]) ? $_SESSION["' . $varName . '"] : \'\'',
                'explanation' => '변수 존재 여부를 먼저 확인합니다.'
            ];
        }
        
        // Unknown column 오류
        if (strpos($errorMessage, 'Unknown column') !== false) {
            preg_match("/Unknown column '(.+)' in/", $errorMessage, $matches);
            $columnName = $matches[1] ?? '';
            
            $suggestions[] = [
                'type' => 'add_column',
                'description' => '컬럼 추가 SQL',
                'suggested' => "ALTER TABLE [table_name] ADD COLUMN {$columnName} VARCHAR(255) DEFAULT NULL;",
                'explanation' => '데이터베이스에 누락된 컬럼을 추가합니다.'
            ];
            
            $suggestions[] = [
                'type' => 'check_column',
                'description' => '컬럼 존재 여부 확인 코드 추가',
                'suggested' => "SHOW COLUMNS FROM [table_name] LIKE '{$columnName}'",
                'explanation' => '쿼리 실행 전 컬럼 존재 여부를 확인합니다.'
            ];
        }
        
        // Class not found 오류
        if (strpos($errorMessage, 'Class') !== false && strpos($errorMessage, 'not found') !== false) {
            preg_match("/Class '(.+)' not found/", $errorMessage, $matches);
            $className = $matches[1] ?? '';
            
            $suggestions[] = [
                'type' => 'use_statement',
                'description' => 'use 문 추가',
                'suggested' => 'use ' . $className . ';',
                'explanation' => '클래스를 import 합니다.'
            ];
            
            $suggestions[] = [
                'type' => 'autoload',
                'description' => 'Composer autoload 갱신',
                'suggested' => 'composer dump-autoload',
                'explanation' => '클래스 자동 로딩을 갱신합니다.'
            ];
        }
        
        return $suggestions;
    }
    
    /**
     * 로그 파일 실시간 파싱 (크론잡용)
     */
    public function parseLogFiles()
    {
        // CLI에서만 실행 가능
        if (!is_cli()) {
            return $this->response->setStatusCode(403)->setBody('CLI only');
        }
        
        $logModel = model('LogAnalysisModel');
        $logPath = WRITEPATH . 'logs/';
        
        // 오늘 날짜의 로그 파일 찾기
        $today = date('Y-m-d');
        $logFile = $logPath . 'log-' . $today . '.log';
        
        if (file_exists($logFile)) {
            // 로그 파싱
            $logEntries = $logModel->parseLogFile($logFile);
            
            // 세션 정보 추가 (크론잡에서는 세션이 없으므로 기본값 사용)
            foreach ($logEntries as &$entry) {
                $entry['user_id'] = 'system';
                $entry['user_name'] = 'System Parser';
                $entry['company_cd'] = '';
                $entry['branch_cd'] = '';
                $entry['ip_address'] = '127.0.0.1';
                $entry['user_agent'] = 'Log Parser Cron';
                
                // DB에 저장
                $logModel->saveLogEntry($entry);
            }
            
            echo "Parsed " . count($logEntries) . " log entries from {$logFile}\n";
        }
        
        // 통계 요약 생성
        $logModel->generateStatisticsSummary($today);
        echo "Statistics summary generated for {$today}\n";
    }
    
    /**
     * 알림 정보 조회 (AJAX)
     */
    public function getAlert($alertId)
    {
        $db = \Config\Database::connect();
        
        if (!$db->tableExists('log_alert_settings')) {
            return $this->response->setJSON(['success' => false, 'message' => '테이블이 존재하지 않습니다.']);
        }
        
        $alert = $db->table('log_alert_settings')
                    ->where('id', $alertId)
                    ->get()
                    ->getRowArray();
        
        return $this->response->setJSON(['success' => true, 'alert' => $alert]);
    }
    
    /**
     * 알림 저장 (AJAX)
     */
    public function saveAlert()
    {
        $db = \Config\Database::connect();
        
        if (!$db->tableExists('log_alert_settings')) {
            return $this->response->setJSON(['success' => false, 'message' => '테이블이 존재하지 않습니다.']);
        }
        
        $data = $this->request->getPost();
        $alertId = $data['id'] ?? null;
        
        unset($data['id']);
        $data['created_by'] = $_SESSION['user_id'] ?? 'admin';
        
        if ($alertId) {
            // 수정
            $db->table('log_alert_settings')
               ->where('id', $alertId)
               ->update($data);
        } else {
            // 신규
            $db->table('log_alert_settings')
               ->insert($data);
        }
        
        return $this->response->setJSON(['success' => true]);
    }
    
    /**
     * 알림 토글 (AJAX)
     */
    public function toggleAlert()
    {
        $db = \Config\Database::connect();
        
        if (!$db->tableExists('log_alert_settings')) {
            return $this->response->setJSON(['success' => false, 'message' => '테이블이 존재하지 않습니다.']);
        }
        
        $alertId = $this->request->getPost('id');
        $isActive = $this->request->getPost('is_active');
        
        $db->table('log_alert_settings')
           ->where('id', $alertId)
           ->update(['is_active' => $isActive]);
        
        return $this->response->setJSON(['success' => true]);
    }
    
    /**
     * 알림 삭제 (AJAX)
     */
    public function deleteAlert()
    {
        $db = \Config\Database::connect();
        
        if (!$db->tableExists('log_alert_settings')) {
            return $this->response->setJSON(['success' => false, 'message' => '테이블이 존재하지 않습니다.']);
        }
        
        $alertId = $this->request->getPost('id');
        
        $db->table('log_alert_settings')
           ->where('id', $alertId)
           ->delete();
        
        return $this->response->setJSON(['success' => true]);
    }
    
    /**
     * 로그 데이터 내보내기
     */
    public function exportLogData()
    {
        $filters = $this->request->getGet();
        $logModel = model('LogAnalysisModel');
        
        // 전체 데이터 조회 (페이징 없이)
        $filters['per_page'] = 10000;
        $result = $logModel->searchLogs($filters);
        
        // CSV 생성
        $filename = 'log_export_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // BOM 추가 (Excel에서 한글 깨짐 방지)
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // 헤더 작성
        fputcsv($output, [
            'ID', '발생시간', '오류레벨', '오류메시지', '파일경로', '라인번호',
            '사용자ID', '사용자명', 'URL', '발생횟수', '해결여부', '해결시간'
        ]);
        
        // 데이터 작성
        foreach ($result['data'] as $row) {
            fputcsv($output, [
                $row['id'],
                $row['log_date'],
                $row['error_level'],
                $row['error_message'],
                $row['file_path'] ?? '',
                $row['line_number'] ?? '',
                $row['user_id'] ?? '',
                $row['user_name'] ?? '',
                $row['request_url'] ?? '',
                $row['occurrence_count'],
                $row['is_resolved'] ? '해결' : '미해결',
                $row['resolved_at'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * 로그 통계 내보내기
     */
    public function exportLogStatistics()
    {
        $period = $this->request->getGet('period') ?? 'month';
        $format = $this->request->getGet('format') ?? 'excel';
        
        // 통계 데이터 조회 (getLogStatistics 메서드 재사용)
        $_POST['period'] = $period; // POST로 변환
        $statsResponse = $this->getLogStatistics();
        $statsData = json_decode($statsResponse->getBody(), true);
        
        if ($format === 'excel') {
            // CSV 형식으로 내보내기
            $filename = 'log_statistics_' . date('Y-m-d_His') . '.csv';
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            // BOM 추가
            echo "\xEF\xBB\xBF";
            
            $output = fopen('php://output', 'w');
            
            // 요약 정보
            fputcsv($output, ['로그 통계 리포트 - ' . date('Y-m-d H:i:s')]);
            fputcsv($output, []);
            fputcsv($output, ['요약 정보']);
            fputcsv($output, ['총 오류 수', $statsData['summary']['total_errors']]);
            fputcsv($output, ['해결률', $statsData['summary']['resolution_rate'] . '%']);
            fputcsv($output, ['평균 해결 시간', $statsData['summary']['avg_resolution_time'] . '분']);
            fputcsv($output, ['영향받은 시스템', $statsData['summary']['affected_systems']]);
            fputcsv($output, []);
            
            // 일별 트렌드
            fputcsv($output, ['일별 오류 트렌드']);
            fputcsv($output, ['날짜', 'CRITICAL', 'ERROR', 'WARNING']);
            foreach ($statsData['trends']['daily'] as $day) {
                fputcsv($output, [
                    $day['date'],
                    $day['critical'] ?? 0,
                    $day['error'] ?? 0,
                    $day['warning'] ?? 0
                ]);
            }
            fputcsv($output, []);
            
            // TOP 오류
            fputcsv($output, ['가장 많이 발생한 오류']);
            fputcsv($output, ['순위', '오류 메시지', '발생 횟수']);
            foreach ($statsData['top_errors'] as $index => $error) {
                fputcsv($output, [
                    $index + 1,
                    $error['error_message'],
                    $error['count']
                ]);
            }
            
            fclose($output);
            exit;
        }
    }
    
    /**
     * 샘플 로그 데이터 생성 (개발/테스트용)
     */
    public function generateSampleLogs()
    {
        $db = \Config\Database::connect();
        
        // 테이블이 존재하는지 확인
        if (!$db->tableExists('log_analysis')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => '로그 테이블이 존재하지 않습니다. 먼저 데이터베이스 테이블을 생성해주세요.'
            ]);
        }
        
        // 샘플 데이터 생성
        $sampleErrors = [
            [
                'level' => 'CRITICAL',
                'messages' => [
                    'Database connection failed: Access denied for user',
                    'Fatal error: Maximum execution time exceeded',
                    'Critical: Payment gateway timeout'
                ]
            ],
            [
                'level' => 'ERROR',
                'messages' => [
                    'Undefined index: bcoff_nm',
                    'Unknown column \'PAYMENT_DEFAULT_SETTINGS\' in \'field list\'',
                    'File not found: /app/Views/missing_view.php',
                    'Division by zero',
                    'Call to undefined method'
                ]
            ],
            [
                'level' => 'WARNING',
                'messages' => [
                    'Session started after headers sent',
                    'Deprecated function usage detected',
                    'File permissions warning: writable/cache',
                    'Memory usage exceeds 80% threshold'
                ]
            ]
        ];
        
        $files = [
            '/app/Controllers/Adminmain.php',
            '/app/Models/UserModel.php',
            '/app/Views/admin/dashboard.php',
            '/app/Services/PaymentService.php',
            '/app/Libraries/AuthLibrary.php'
        ];
        
        $users = [
            ['id' => 'admin01', 'name' => '관리자1'],
            ['id' => 'admin02', 'name' => '관리자2'],
            ['id' => 'user01', 'name' => '사용자1'],
            ['id' => 'user02', 'name' => '사용자2']
        ];
        
        $insertedCount = 0;
        $logModel = model('LogAnalysisModel');
        
        // 최근 30일간의 샘플 데이터 생성
        for ($days = 29; $days >= 0; $days--) {
            $date = date('Y-m-d', strtotime("-{$days} days"));
            
            // 하루에 5-20개의 랜덤 오류 생성
            $errorCount = rand(5, 20);
            
            for ($i = 0; $i < $errorCount; $i++) {
                $errorType = $sampleErrors[array_rand($sampleErrors)];
                $message = $errorType['messages'][array_rand($errorType['messages'])];
                $file = $files[array_rand($files)];
                $user = $users[array_rand($users)];
                $hour = rand(0, 23);
                $minute = rand(0, 59);
                
                $logData = [
                    'log_date' => $date . ' ' . sprintf('%02d:%02d:00', $hour, $minute),
                    'error_level' => $errorType['level'],
                    'error_message' => $message,
                    'file_path' => $file,
                    'line_number' => rand(100, 500),
                    'user_id' => $user['id'],
                    'user_name' => $user['name'],
                    'company_cd' => $_SESSION['comp_cd'] ?? 'DEMO',
                    'branch_cd' => $_SESSION['bcoff_cd'] ?? 'MAIN',
                    'request_url' => '/adminmain/' . ['dashboard', 'users', 'settings', 'reports'][rand(0, 3)],
                    'request_method' => ['GET', 'POST'][rand(0, 1)],
                    'ip_address' => '192.168.1.' . rand(1, 254),
                    'error_hash' => md5($errorType['level'] . $message . $file),
                    'occurrence_count' => 1,
                    'first_occurred' => $date . ' ' . sprintf('%02d:%02d:00', $hour, $minute),
                    'last_occurred' => $date . ' ' . sprintf('%02d:%02d:00', $hour, $minute),
                    'is_resolved' => rand(0, 10) > 3 ? 1 : 0
                ];
                
                // 해결된 오류는 해결 정보 추가
                if ($logData['is_resolved']) {
                    $logData['resolved_by'] = $users[0]['id']; // admin01
                    $logData['resolved_at'] = date('Y-m-d H:i:s', strtotime($logData['log_date']) + rand(3600, 86400));
                    $logData['fix_applied'] = '오류가 수정되었습니다.';
                }
                
                $logModel->saveLogEntry($logData);
                $insertedCount++;
            }
        }
        
        // 통계 요약 생성
        $logModel->generateStatisticsSummary();
        
        return $this->response->setJSON([
            'success' => true,
            'message' => $insertedCount . '개의 샘플 로그 데이터가 생성되었습니다.'
        ]);
    }
    
}