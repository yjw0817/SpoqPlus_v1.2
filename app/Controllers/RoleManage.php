<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\MemModel;
use App\Models\AuthModel;
use App\Models\MenuModel;
use App\Libraries\TreeStateVO;
use App\Libraries\TreeVO;
use App\Libraries\RoleMenuVO;

class RoleManage extends MainTchrController
{
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
        

        

        $this->viewPage('/tchr/rolemanage/basic_role_manage',$data);
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
        

        $this->viewPage('/tchr/rolemanage/basic_role_user_manage',$data);
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
        $data["user_id"] = $_SESSION["user_id"];
        $data["bcoff_cd"] = $_SESSION["bcoff_cd"];
        $data["comp_cd"] = $_SESSION["comp_cd"];
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
     * 권한에 연결된 사용자를 삭제한다.
     */
    public function deleteUserListFromRole()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);
        
        $authModel = new AuthModel();
        $menuModel = new MenuModel();
        

        $delList = $jsonData["delList"];
        $authModel = new AuthModel();
        $result = $authModel->deleteUserListFromRole($jsonData);
       
        

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($result);
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
                    'result' => "권한에 할당된 메뉴가 존재하여 경우 삭제가 불가 합니다. 메뉴 할당을 해제후 삭제하세요." 
                ];
                break;
            } else
            {
                $roleList = $authModel -> getRoleUserList($item);
                if($roleList != null && count($roleList) > 0)
                {
                    $return_json = [
                        'status' => 'failed',
                        'result' => "권한이 부여된 사용자가 존재하여 삭제가 불가 합니다. 권한이 부여된 사용자를 먼저 해제후 삭제하세요." 
                    ];
                    break;
                }
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
     * 직원 리스트를 가져온다.
     */
    public function getEmployeeList()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);

        // `cdMenu` 값 확인
        $authModel = new AuthModel();
        $jsonData["comp_cd"] = $_SESSION["comp_cd"];
        $jsonData["bcoff_cd"] = $_SESSION["bcoff_cd"];
        // Cache에서 회사 코드 가져오기
        // 메뉴 리스트 가져오기
        $auth_list = $authModel->getEmployeeList($jsonData);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $auth_list // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
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
        $auth_list = $authModel->getRoleUserList($jsonData);

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $auth_list // 기존 menu_List를 result로 변경
        ];

        // JSON 헤더 설정 후 응답 반환
        return $this->response->setJSON($return_json);
    }

    public function applyEmployeesWithRole()
    {
        $authModel = new AuthModel();
        $input = $this->request->getJSON(true); // Get JSON input

        if (empty($input['mem_sno_list']) || !is_array($input['mem_sno_list']) || empty($input['seq_role'])) {
            return $this->response->setJSON(["success" => false, "message" => "Invalid data received"]);
        }

        $memSnoList = $input['mem_sno_list'];
        $seqRole = $input['seq_role'];
        $createdBy = $_SESSION["user_id"];
        $currentDateTime = date('Y-m-d H:i:s');

        // Insert selected employees into the role table
        foreach ($memSnoList as $memSno) {
            $data = [
                "mem_sno" => $memSno,
                "seq_role" => $seqRole,
                "created_by" => $createdBy,
                "created_dt" => $currentDateTime
            ];
            $authModel->insertRoleToUser($data);
        }

        $auth_list = $authModel->getRoleUserList($data);

        return $this->response->setJSON([
                                          "success" => true,
                                          'result' => $auth_list
                                        ]);
    }

    /**
     * 권한 리스트를 가져온다.
     */
    public function getRoleList()
    {
        // POST 데이터 가져오기
        $jsonData = $this->request->getJSON(true);
        
        $authModel = new AuthModel();
        
        $jsonData["comp_cd"] = $_SESSION["comp_cd"];
        $jsonData["bcoff_cd"] = $_SESSION["bcoff_cd"];
        $jsonData["user_id"] =  $_SESSION["user_id"];
        // 메뉴 리스트 가져오기
        $auth_list = $authModel->getRoleList($jsonData);
        if(count($auth_list) == 0)
        {
            $authModel->createRoleForBcoff($jsonData);
            $auth_list = $authModel->getRoleList($jsonData);
        }

        // JSON 응답 데이터 구성
        $return_json = [
            'status' => 'success',
            'result' => $auth_list // 기존 menu_List를 result로 변경
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
        $data["compCd"] = $_SESSION["comp_cd"];
        $data["bcoff_cd"] = $_SESSION["bcoff_cd"];

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
     * 메뉴를 저장한다.
     * @return json
     */
    public function saveRoleMenu()
    {
         // JSON 데이터를 받아서 PHP 배열로 변환
        $data = $this->request->getJSON(true); 
        $authModel = new AuthModel();
        $data["user_id"] = $_SESSION['user_id'];;
        $data["cd_company"] = $_SESSION['comp_cd'];;


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
}
    