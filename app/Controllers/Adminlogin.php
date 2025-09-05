<?php
namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\MenuModel;

class Adminlogin extends BaseController
{
    /**
     * 어드민 로그인 페이지를 보여준다.
     */
    public function index()
    {
        echo view('/adminlogin/adminlogin',array());
    }
    
    /**
     * 어드민 로그아웃
     */
    public function logout()
    {
    	session_destroy();
    	
    	echo "<script>alert('LOGOUT');</script>";
    	$reUrl = '/adminlogin';
    	echo "<script>location.href='" . $reUrl . "';</script>";
    	exit();
    }
    
    /**
     * 어드민 로그인 체크를 진행한다.
     */
    public function adminLoginAction()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new AdminModel();
        $menuModel = new MenuModel();
        $session = session();
        
        // ===========================================================================
        // 전달받기
        // ===========================================================================
        /*
         * admin_id : 아이디
         * admin_pass : 비밀번호
         */
        $postVar = $this->request->getPost();
        
        // ===========================================================================
        // Model Data Set & Data Query Return
        // ===========================================================================
        $mdata['adm_id'] = $postVar['admin_id'];
        $mdata['adm_pwd'] = hash('sha256',$postVar['admin_pass']);
        
        $chk_admin_login = $model->admin_login_check($mdata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        // 데이터에서 값이
        // 0 이상이면 로그인 성공 처리
        // 0 이면 로그인 실패 처리
        if (count($chk_admin_login) > 0) :
            $sesMake['user_name'] = "Admin";
        	$sesMake['user_id'] = "admin";
            $sesMake['site_type'] = "admin";
            $cateData["use_for"]="AD";
            $menu_List = $menuModel->list_menu_by_login($cateData);
            $sesMake['menu_list'] = $menu_List;

            $session->set($sesMake);
        
            $reUrl = "/adminmain/bc_appct_manage";
            echo "<script>location.href='" . $reUrl . "';</script>";
            exit();
        else :
            $reUrl = "/adminlogin";
            echo "<script>alert('아이디나 비밀번호를 다시 확인해주세요');</script>";
            echo "<script>location.href='" . $reUrl . "';</script>";
            exit();
        endif ;
    }
}