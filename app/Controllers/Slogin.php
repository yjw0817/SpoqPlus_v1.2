<?php
namespace App\Controllers;

use App\Models\SadminModel;
use App\Models\MenuModel;

class Slogin extends BaseController
{
    /**
     * 슈퍼관리자 로그인 페이지를 보여준다.
     */
    public function index()
    {
        echo view('/adminlogin/slogin',array());
    }
    
    /**
     * 슈퍼관리자 로그아웃
     */
    public function logout()
    {
    	session_destroy();
    	
    	echo "<script>alert('LOGOUT');</script>";
    	$reUrl = '/slogin';
    	echo "<script>location.href='" . $reUrl . "';</script>";
    	exit();
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
     * 어드민 로그인 체크를 진행한다.
     */
    public function sLoginAction()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new SadminModel();
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
        $mdata['adm_pwd'] = $this->enc_pass($postVar['admin_pass']);
        
        $chk_admin_login = $model->admin_login_check($mdata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        // 데이터에서 값이
        // 0 이상이면 로그인 성공 처리
        // 0 이면 로그인 실패 처리
        if (count($chk_admin_login) > 0) :
	        $sesMake['user_name'] = $chk_admin_login[0]['MNGR_NM']; //담당자명
            $sesMake['comp_nm'] = $chk_admin_login[0]['COMP_NM']; //회사명
	        $sesMake['user_id'] = $chk_admin_login[0]['SMGMT_ID']; // 슈퍼관리자 아이디
	        $sesMake['comp_cd'] = $chk_admin_login[0]['COMP_CD']; // 회사코드
	        $sesMake['site_type'] = "sadmin";
            $cateData["use_for"]="SU";
            $menu_List = $menuModel->list_menu_by_login($cateData);
            $sesMake['menu_list'] = $menu_List;
        
            $session->set($sesMake);
        
            $reUrl = "/smgrmain/bc_appct_manage";
            echo "<script>location.href='" . $reUrl . "';</script>";
            exit();
        else :
            $reUrl = "/slogin";
            echo "<script>alert('아이디나 비밀번호를 다시 확인해주세요');</script>";
            echo "<script>location.href='" . $reUrl . "';</script>";
            exit();
        endif ;
    }
}