<?php
namespace App\Controllers;

use App\Models\MemModel;

class Login extends BaseController
{
    /**
     * 모바일 로그인 페이지를 보여준다.
     */
    public function index()
    {
        // URL 파라미터에서 clear 값 확인
        $clearParam = $this->request->getGet('clear');
        
        // clear=1이면 세션도 삭제하고 로그인 페이지 표시
        if ($clearParam === '1') {
            // PHP 세션 삭제
            session_destroy();
            
            // 새로운 세션 시작
            session_start();
            
            echo view('/mobile/login',array());
            return;
        }
        
        if ( isset($_SESSION['user_sno']))
        {
            if ($_SESSION['user_sno'] != '')
            {
                $reUrl = "/api/mmmain";
                
                
                if (isset($_SESSION['comp_cd']))
                {
                    if ($_SESSION['site_type'] == "mmlogin")
                    {
                        $reUrl = "/api/mmmain";
                    } elseif ($_SESSION['site_type'] == "mtlogin")
                    {
                        $reUrl = "/api/mtmain";
                    }
                } else 
                {
                    $reUrl = "/api/setcomp";
                }
                    
                echo "<script>location.href='" . $reUrl . "';</script>";
            }
        } else 
        {
            echo view('/mobile/login',array());
        }
    }
    
    /**
     * 모바일 탈퇴나 접속제한으로 불가능 처리
     */
    public function errlogout()
    {
        if(count($_COOKIE))
        {
            foreach($_COOKIE as $key => $value)
            {
                setcookie($key, NULL, -3600, '/');
            }
        }
        
        session_destroy();
        $reUrl = '/login';
        echo "<script>location.href='" . $reUrl . "';</script>";
        exit();
    }
    
    /**
     * 모바일 로그아웃
     */
    public function logout()
    {
        if ( isset($_SESSION['site_type']) )
        {
            if ($_SESSION['site_type'] == "mmlogin" || $_SESSION['site_type'] == "mtlogin")
            {
                if(count($_COOKIE))
                {
                    foreach($_COOKIE as $key => $value)
                    {
                        setcookie($key, NULL, -3600, '/');
                    }
                }
            }
        }
        
        session_destroy();
        
        echo "<script>alert('LOGOUT');</script>";
        $reUrl = '/login';
        echo "<script>location.href='" . $reUrl . "';</script>";
        exit();
    }
    
    public function join_logout()
    {
        if(count($_COOKIE))
        {
            foreach($_COOKIE as $key => $value)
            {
                setcookie($key, NULL, -3600, '/');
            }
        }
        session_destroy();
        echo "<script>alert('회원가입 완료. 다시 로그인 해주세요');</script>";
        $reUrl = '/login';
        echo "<script>location.href='" . $reUrl . "';</script>";
        exit();
        
    }
    
    public function find_logout()
    {
        if(count($_COOKIE))
        {
            foreach($_COOKIE as $key => $value)
            {
                setcookie($key, NULL, -3600, '/');
            }
        }
        session_destroy();
        $reUrl = '/login';
        echo "<script>location.href='" . $reUrl . "';</script>";
        exit();
    }
    
    
    public function find_repass_logout()
    {
        if(count($_COOKIE))
        {
            foreach($_COOKIE as $key => $value)
            {
                setcookie($key, NULL, -3600, '/');
            }
        }
        session_destroy();
        echo "<script>alert('비밀번호 변경완료. 다시 로그인 해주세요');</script>";
        $reUrl = '/login';
        echo "<script>location.href='" . $reUrl . "';</script>";
        exit();
    }
    /**
     * 모바일 로그인 체크를 진행한다. (사용안함)
     */
    public function LoginAction()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $model = new MemModel();
        $session = session();
        
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
        $mdata['login_id'] = $postVar['login_id'];
        $mdata['login_pwd'] = $postVar['login_pass'];
        
        $chk_tlogin = $model->mobile_login_check($mdata);
        
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        // 데이터에서 값이
        // 0 이상이면 로그인 성공 처리
        // 0 이면 로그인 실패 처리
        if (count($chk_tlogin) > 0) :
        $sesMake['user_sno']    = $chk_tlogin[0]['MEM_SNO']; //user sno
        $sesMake['user_name']   = $chk_tlogin[0]['MEM_NM']; //이름
        $sesMake['user_id']     = $chk_tlogin[0]['MEM_ID']; // 아이디
        $sesMake['mem_dv']      = $chk_tlogin[0]['MEM_DV']; // 회원/강사 구분
        
        // 임시
        $sesMake['comp_cd']      = "C0001";
        $sesMake['bcoff_cd']     = "C0001F0014";
        
        if ($chk_tlogin[0]['MEM_DV'] == 'M') :
            $sesMake['site_type']   = "mmlogin";
            $reUrl = "/api/mmmain/1";
        elseif ($chk_tlogin[0]['MEM_DV'] == 'T') :
            $sesMake['site_type']   = "mtlogin";
            $reUrl = "/api/mtmain/1";
        endif;
        
        $session->set($sesMake);
        
        echo "<script>location.href='" . $reUrl . "';</script>";
        exit();
        else :
        $reUrl = "/login";
        echo "<script>alert('아이디나 비밀번호를 다시 확인해주세요');</script>";
        echo "<script>location.href='" . $reUrl . "';</script>";
        exit();
        endif ;
    }
}