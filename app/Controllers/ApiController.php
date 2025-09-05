<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;
use App\Libraries\SpoQjwt;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\HTTP\URI;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class ApiController extends MainController
{
    
    protected $helpers = ['cookie','My_helper'];
    protected $encryption;
    protected $encrypter;
    
    protected $user_sno;
    protected $enc_user_sno;
    
    private $chkUrl = "api/login";
    private $chkUrlAc = "api/loginProc";
    private $chkUrlAgree = "api/join_agree";
    private $chkUrlFind = "api/find_info";
    
    private $chkPayUrl = "api/nextUrl";
    
    // 체크인 관련 API들 (로그인 체크 제외)
    private $checkinUrls = [
        "api/qrcode_attd",
        "api/get_member_tickets", 
        "api/get_member_tickets_by_telno",
        "api/checkin_with_ticket",
        "api/face/register",
        "api/face/recognize",
        "api/face/health",
        "api/v1/kiosk/face-auth",
        "api/v1/kiosk/checkin",
        "api/v1/kiosk/status",
        "api/v1/kiosk-test/face-auth",
        "api/v1/kiosk-test/checkin",
        "api/v1/kiosk-test/status",
        // SMS 인증 관련 API (로그인 체크 제외)
        "api/sendSmsVerification",
        "api/verifySmsCode",
        "api/loginWithPhone",
        "api/verifySmsAutoLogin",
        "api/checkAutoLogin",
        "api/mobileLogout",
        // 모바일 회원가입 관련 (로그인 체크 제외)
        "api/mobile_register",
        "api/checkPhoneVerification",
        "api/getCompanyList",
        "api/getBranchList",
        "api/mobileRegisterProc"
    ];
    
	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
		$this->session = Services::session();
		$this->encryption = new Encryption();
		$this->encrypter = $this->encryption->initialize();
		
		// 로그인 체크 제외 URL들
		$excludeUrls = array_merge(
		    [$this->chkUrl, $this->chkPayUrl, $this->chkUrlAc, $this->chkUrlAgree, $this->chkUrlFind],
		    $this->checkinUrls
		);
		
		if (!in_array(uri_string(), $excludeUrls)) {
	          $this->SpoQ_chk_cookie();
		}
	}
	
	/**
	 * 쿠키를 생성한다.
	 */
	protected function SpoQ_set_cookie()
	{
	    $SpoQjwt = new SpoQjwt();
	    
	    $this->SpoQ_enc();
	    $login_token = $SpoQjwt->hashing(array(
	        "user_sno"=> $this->enc_user_sno,
	    ));
	    
	    $cookie_name = "SpoQ_token";
	    $cookie_value = $login_token;
	    $cookie_expired = 600; // 초단위 설정
	    $cookie_domain = "";
	    $cookie_path = "/";
	    $cookie_prefix = "";
	    $cookie_secure = FALSE;
	    $cookie_httponly = TRUE;
	    
	    set_cookie($cookie_name, $cookie_value, $cookie_expired, $cookie_domain, $cookie_path, $cookie_prefix, $cookie_secure, $cookie_httponly);
	}
	
	/**
	 * 쿠키를 체크한다.
	 */
	private function SpoQ_chk_cookie()
	{
	    $SpoQjwt = new SpoQjwt();
	    $get_login_token = get_cookie("SpoQ_token");
	    
	    if ($get_login_token == null) :
           session_destroy();	
	       $reUrl = "/login";
           echo "<script>alert('로그인이 만료되었습니다. 다시 로그인해주세요');</script>";
           echo "<script>location.href='" . $reUrl . "';</script>";
	       //echo $_SESSION['user_sno'];
	    else:
    	    $jwt_data = $SpoQjwt->dehashing($get_login_token);
    	    $this->enc_user_sno = $jwt_data["user_sno"];
    	    $this->SpoQ_denc();
    	    $this->SpoQ_set_cookie();
    	    // echo $this->user_sno."<br />";
    	    // echo $_SESSION['user_sno'];
	    endif;
	    
	    // 보안을 위하여 세션을 다시한번 더 체크한다.
	    if (!isset($_SESSION['user_id']))
	    {
	        //session_destroy();
	        $reUrl = "/login";
	        //echo "<script>alert('로그인이 만료되었습니다. 다시 로그인해주세요 [03]');</script>";
	        //echo "<script>location.href='" . $reUrl . "';</script>";
	    }
	    
	}
	
	public function SpoQ_get_cookie()
	{
	    $SpoQjwt = new SpoQjwt();
	    $get_login_token = get_cookie("SpoQ_token");
	    $jwt_data = $SpoQjwt->dehashing($get_login_token);
	    return $jwt_data;
	}
	
	/**
	 * 쿠키를 암호화 한다.
	 */
	private function SpoQ_enc()
	{
	    $enc_word = $this->user_sno;
	    $this->enc_user_sno = base64_encode( $this->encrypter->encrypt($enc_word) );
	}
	
	/**
	 * 쿠키를 복호화 한다.
	 */
	private function SpoQ_denc()
	{
	    $enc_word = $this->enc_user_sno;
	    $this->user_sno = $this->encrypter->decrypt( base64_decode($enc_word) );
	}
	
	public function SpoQ_get_cookie_sno($enc_user_sno)
	{
	    return $this->user_sno = $this->encrypter->decrypt( base64_decode($enc_user_sno) );
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
