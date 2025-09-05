<?php
namespace App\Controllers;

use App\Models\MemModel;
use App\Libraries\SpoQCache_lib;
use App\Models\MenuModel;
use App\Models\AuthModel;

class Tlogin extends BaseController
{
    /**
     * 강사 로그인 페이지를 보여준다.
     */
    public function index()
    {
        echo view('/adminlogin/tlogin',array());
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
    
    /**
     * 강사 로그아웃
     */
    public function logout()
    {
    	session_destroy();
    	
    	echo "<script>alert('LOGOUT');</script>";
    	$reUrl = '/tlogin';
    	echo "<script>location.href='" . $reUrl . "';</script>";
    	exit();
    }

    public function changeTLogin()
    {

        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new MemModel();
        $menuModel = new MenuModel();
        $SpoQDef = SpoqDef(); // 직책 목록을 포함한 배열 가져오기
        $SpoQCache = new SpoQCache_lib();


        // ===========================================================================
        // 전달받기
        // ===========================================================================
        /*
         * tlogin_id : 아이디
         * tlogin_pass : 비밀번호
         */
        $postVar = $this->request->getPost();
        
        $mdata['login_id'] = $postVar['mem_id'];
        $mdata['login_pwd'] = $postVar['password'];
        $mdata['login_pwd'] = $this->enc_pass($postVar['password']);
        
        $chk_tlogin = $model->tlogin_check($mdata);

        // ===========================================================================
        // Processs
        // ===========================================================================
        
        // 데이터에서 값이
        // 0 이상이면 로그인 성공 처리
        // 0 이면 로그인 실패 처리
        if (count($chk_tlogin) > 0) :
                
            if ($chk_tlogin[0]['USE_YN'] == "N") :
                $reUrl = "/tlogin";
                echo "<script>alert('접속이 제한되었습니다. 관리자에게 문의하세요');</script>";
                echo "<script>location.href='" . $reUrl . "';</script>";
                exit();
            endif;
        
            $sesMake['user_sno']    = $chk_tlogin[0]['MEM_SNO']; //user sno
            $sesMake['user_name']   = $chk_tlogin[0]['MEM_NM']; //이름
            $sesMake['user_id']     = $chk_tlogin[0]['MEM_ID']; // 아이디
            $sesMake['comp_cd']     = $chk_tlogin[0]['COMP_CD']; // 회사코드
            $sesMake['comp_nm']     = $chk_tlogin[0]['COMP_NM']; // 지점코드            
            $sesMake['bcoff_nm']    = $chk_tlogin[0]['BCOFF_NM']; // 지점명
            $sesMake['bcoff_cd']    = $chk_tlogin[0]['BCOFF_CD']; // 지점코드
            $sesMake['mem_dv']      = $chk_tlogin[0]['MEM_DV']; // 회원, 강사 구분
            $sesMake['nm_role']     = $chk_tlogin[0]['nm_role']; // 권한명
            $position_code = $chk_tlogin[0]['TCHR_POSN']; // 직책 코드
            $position_name = isset($SpoQDef['TCHR_POSN'][$position_code]) ? $SpoQDef['TCHR_POSN'][$position_code] : "알 수 없음";
            $sesMake['TCHR_POSN_NM']   = $position_name; //직책명
	        $sesMake['site_type']   = "tlogin";
            $cateData["use_for"]    = "MA";
            $cateData["bcoff_mgmt_id"] = $chk_tlogin[0]['BCOFF_MGMT_ID']; // 지점 관리자 아이디.
            if(isset($chk_tlogin[0]['BCOFF_MGMT_ID']))
            {
                $sesMake['nm_role'] = "지점관리자";
            }
            $cateData["user_id"]  = $chk_tlogin[0]['MEM_ID'];
            $cateData["bcoff_cd"] = $sesMake['bcoff_cd'];
            $cateData['seq_role'] = $chk_tlogin[0]['seq_role'];
            $menu_List = $menuModel->list_menu_of_user($cateData);
            $sesMake['menu_list'] = $menu_List;
            
        
            // 회원 정보를 가져온다.
            $sesMake['mem_id']   = $chk_tlogin[0]['MEM_ID'];
            $get_mem_info = $model->get_mem_info_mem_id($sesMake);
            $mem_info = $get_mem_info[0];


            $authModel = new AuthModel();
            $employeeList = $authModel->getEmployeeListHavingRole($sesMake);
           
            $sesMake['mem_info'] = $mem_info;
            $sesMake['employee_list'] = $employeeList;
	        $SpoQCache->setCacheObj($sesMake);

            $return_json['result'] = 'true';
        else:
            $return_json['result'] = 'false';
        endif;
        
        return json_encode($return_json);
    }
    
    /**
     * 강사 로그인 체크를 진행한다.
     */
    public function tLoginAction()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new MemModel();
        $menuModel = new MenuModel();
        $SpoQDef = SpoqDef(); // 직책 목록을 포함한 배열 가져오기
        $SpoQCache = new SpoQCache_lib();
        
        // ===========================================================================
        // 전달받기
        // ===========================================================================
        /*
         * tlogin_id : 아이디
         * tlogin_pass : 비밀번호
         */
        $postVar = $this->request->getPost();
        
        // ===========================================================================
        // Model Data Set & Data Query Return
        // ===========================================================================
        $mdata['login_id'] = $postVar['tlogin_id'];
        $mdata['login_pwd'] = $postVar['tlogin_pass'];
        $mdata['login_pwd'] = $this->enc_pass($postVar['tlogin_pass']);
        
        $chk_tlogin = $model->tlogin_check($mdata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        // 데이터에서 값이
        // 0 이상이면 로그인 성공 처리
        // 0 이면 로그인 실패 처리
        if (count($chk_tlogin) > 0) :
                
            if ($chk_tlogin[0]['USE_YN'] == "N") :
                $reUrl = "/tlogin";
                echo "<script>alert('접속이 제한되었습니다. 관리자에게 문의하세요');</script>";
                echo "<script>location.href='" . $reUrl . "';</script>";
                exit();
            endif;
        
            $sesMake['user_sno']    = $chk_tlogin[0]['MEM_SNO']; //user sno
            $sesMake['user_name']   = $chk_tlogin[0]['MEM_NM']; //이름
            $sesMake['user_id']     = $chk_tlogin[0]['MEM_ID']; // 아이디
            $sesMake['comp_cd']     = $chk_tlogin[0]['COMP_CD']; // 회사코드
            $sesMake['comp_nm']     = $chk_tlogin[0]['COMP_NM']; // 지점코드            
            $sesMake['bcoff_nm']    = $chk_tlogin[0]['BCOFF_NM']; // 지점명
            $sesMake['bcoff_cd']    = $chk_tlogin[0]['BCOFF_CD']; // 지점코드
            $sesMake['mem_dv']      = $chk_tlogin[0]['MEM_DV']; // 회원, 강사 구분
            $sesMake['nm_role']     = $chk_tlogin[0]['nm_role']; // 권한명
            $position_code = $chk_tlogin[0]['TCHR_POSN']; // 직책 코드
            $position_name = isset($SpoQDef['TCHR_POSN'][$position_code]) ? $SpoQDef['TCHR_POSN'][$position_code] : "알 수 없음";
            $sesMake['TCHR_POSN_NM']   = $position_name; //직책명
	        $sesMake['site_type']   = "tlogin";
            $cateData["use_for"]    = "MA";
            $cateData["bcoff_mgmt_id"] = $chk_tlogin[0]['BCOFF_MGMT_ID']; // 지점 관리자 아이디.
            if(isset($chk_tlogin[0]['BCOFF_MGMT_ID']))
            {
                $sesMake['nm_role'] = "지점관리자";
            }
            $cateData["user_id"]  = $chk_tlogin[0]['MEM_ID'];
            $cateData["bcoff_cd"] = $sesMake['bcoff_cd'];
            $cateData['seq_role'] = $chk_tlogin[0]['seq_role'];
            $menu_List = $menuModel->list_menu_of_user($cateData);
            $sesMake['menu_list'] = $menu_List;
            
        
            // 회원 정보를 가져온다.
            $sesMake['mem_id']   = $chk_tlogin[0]['MEM_ID'];
            $get_mem_info = $model->get_mem_info_mem_id($sesMake);
            $mem_info = $get_mem_info[0];


            $authModel = new AuthModel();
            $employeeList = $authModel->getEmployeeListHavingRole($sesMake);
           
            $sesMake['mem_info'] = $mem_info;
            $sesMake['employee_list'] = $employeeList;
	        $SpoQCache->setCacheObj($sesMake);
        
            $reUrl = "/tchrmain/dashboard";
            echo "<script>location.href='" . $reUrl . "';</script>";
            exit();
        else :
            $reUrl = "/tlogin";
            echo "<script>alert('아이디나 비밀번호를 다시 확인해주세요');</script>";
            echo "<script>location.href='" . $reUrl . "';</script>";
            exit();
        endif ;
    }
}