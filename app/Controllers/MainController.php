<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;
use App\Libraries\SpoQCache_lib;

/**
 * Class MainController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class MainController extends Controller
{
	private $protect_key; // 직접 접근을 막기 위한 암호화 변수
	private $protect_page_key; // 연속된 페이지에서 입력되는 암호화 변수
	
	private $chkUrl = "api/login";
	private $chkUrlAc = "api/loginProc";
	
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['My_helper'];
	protected $session;
	protected $init_fr; // 프랜차이즈 URL로 접속했을때의 아이디 저장소
	protected $loginUrl; // 세션이 끊겼을때 리턴해주는 로그인 URL
	
	public $SpoQCahce;
	
	
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

		$this->init_fr['storeId'] = '';
		
		$base_login_url = "/login";
		if ($this->loginUrl) $base_login_url = $this->loginUrl;
		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
		$this->session = Services::session();
		
		$fr_uri = service('uri');
		$bb_url =  $fr_uri->getScheme() . "://" . $fr_uri->getHost();
		/* ==================================================================
		 * Session을 체크한다.
		 * 세션을 체크해서 세션이 끊기면 로그인 페이지로 이동을 한다.
		 ================================================================== */
		/*
		if (uri_string() != $this->chkUrl) :
		if (uri_string() != $this->chkUrlAc) :
    		if ( !$this->session->has('user_name') )
    		{
    		    $sesMake['reUrl'] = '/' . $this->request->uri->getPath();
    		    $this->session->set($sesMake);
    		    echo "<script>location.href='" . $bb_url . $base_login_url . "?reUrl=" . $this->request->uri->getPath() . "';</script>";
    		    exit();
    		}
		endif;
		endif;
		*/
		
		$this->SpoQCahce = new SpoQCache_lib();
	}
	
	protected function setprotectKey()
	{
		$this->protect_key = base64_encode(hash('sha256', time(), true));
	}
	
	protected function getProtectKey()
	{
		return $this->protect_key;	
	}
	
	protected function setPageKey()
	{
		$this->protect_page_key = $this->protect_key;
	}
	
	protected function getPageKey()
	{
		return $this->protect_page_key;
	}
	
	protected function ifProtect()
	{
		if ($this->getPageKey() != '' && $this->getPageKey() == $this->getPageKey())
		{
			return true;
		} else 
		{
			echo '접근 방법이 잘못 되었습니다.';
			die();
		}
	}
	
	/**
	 * 전화번호 암호화 lenth
	 * @param string $telno
	 */
	protected function enc_tel($telno)
	{
	    $encryption = new Encryption();
	    $encrypter = $encryption->initialize(
	        );
	    $ciphertext = base64_encode($encrypter->encrypt($telno));
	    return $ciphertext;
	}
	
	/**
	 * 전화번호 복호화
	 * @param string $enc_telno
	 */
	protected function denc_tel($enc_telno)
	{
	    $encryption = new Encryption();
	    $encrypter = $encryption->initialize(
	        );
	    $de_ciphertext = $encrypter->decrypt(base64_decode($enc_telno));
	    return $de_ciphertext;
	}
	
	protected function enc_phone($var="")
	{
	    if ($var == "" || $var == null)
	    {
	        $return_val['mask'] = "";
	        $return_val['short'] = "";
	        $return_val['enc'] = "";
	        $return_val['enc2'] = "";
	    } else
	    {
	        $ch_var = disp_phone($var); // 전화번호 형식을 다시 - 형식으로 바꾼다.
	        $ex_var = explode("-",$ch_var); // - 형식으로 문자를 나누어 배열로 저장한다.
	        
	        $mask_len = strlen($ex_var[1]); // 가운데 전화번호를 마스크 하기 위하여 마스크할 길이를 구한다.
	        
	        $add_mask = "";
	        for($i=0; $i<$mask_len; $i++)
	        {
	            $add_mask .= "#";
	        }
	        
	        $mask_phone = $ex_var[0] . "-" . $add_mask . "-" . $ex_var[2];
	        
	        $return_val['mask'] = $mask_phone;
	        $return_val['short'] = $ex_var[2];
	        $return_val['enc'] = $this->enc_tel($var);
	        $return_val['enc2'] = $this->enc_pass($var);
	        
	    }
	    
	    return $return_val;
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
	 * Main Frame의 View 페이지를 로드 합니다.
	 * @param String $viewUrl
	 * @param Array $data (header, topMenu, left, topSearch, topMessage, topNotifi, ContentTitle, view, footer)
	 */
	public function viewPageForMobile($viewUrl, $data = null)
	{
		
		// 만약에 값이 없으면 배열값들을 초기화 한다.
		if ($data == null) {
			$data['header'] = array();
			$data['topMenu'] = array();
			$data['left'] = array();
			$data['topSearch'] = array();
			$data['topMessage'] = array();
			$data['topNotifi'] = array();
			$data['contentTitle'] = array();
			$data['view'] = array();
			$data['footer'] = array();
		} else { // null이 아니라면 각 셋팅값을 비교하여 없는 배열값에 대한 초기화를 한다.
			if (!isset($data['header'])) $data['header'] = array();
			if (!isset($data['topMenu'])) $data['topMenu'] = array();
			if (!isset($data['left'])) $data['left'] = array();
			if (!isset($data['topSearch'])) $data['topSearch'] = array();
			if (!isset($data['topMessage'])) $data['topMessage'] = array();
			if (!isset($data['topNotifi'])) $data['topNotifi'] = array();
			if (!isset($data['contentTitle'])) $data['contentTitle'] = array();
			if (!isset($data['view'])) $data['view'] = array();
			if (!isset($data['footer'])) $data['footer'] = array();
		}

		

		$data['left']['ses'] = $this->SpoQCahce->getCacheObj(); // $_SESSION;
		$data['left']['menu1'] = $data['menu1'];
		$data['left']['menu2'] = $data['menu2'];

		if (isset($data['useJs'])) {
			$data['header']['useJs'] = $data['useJs'];
		} else {
			$data['header']['useJs'] = array();
			$data['useJs'] = array();
		}

		$data['footer']['jsurl'] = $this->request->uri->getPath();
		$data['init_fr'] = $this->init_fr;
		if (!$this->request->isAJAX()) {
			$data['view']['jsinc'] = view('/inc_p/jsinc', $data);
			$data['view']['init_fr'] = $this->init_fr;
		}

		if (isset($data['title'])) {
			$data['header']['title'] = $data['title'];
			$data['contentTitle']['title'] = $data['title'];
		} else {
			$data['header']['title'] = '';
			$data['contentTitle']['title'] = '';
		}

		if (isset($data['nav'])) {
			$data['header']['nav'] = $data['nav'];
			$data['contentTitle']['nav'] = $data['nav'];
		} else {
			$data['header']['nav'] = '';
			$data['contentTitle']['nav'] = '';
		}

		// AJAX 요청 여부 확인
		// if ($this->request->isAJAX()) {
		// 	$output = view('/inc/contentTitle', $data['contentTitle']);
		// 	$output = view($viewUrl, $data['view']);
		// 	$output .= view('inc/footer', $data['footer']); // 푸터
        //     return $output;
        // }

		echo view('/inc_p/header', $data['header']); // 헤더
		echo view('/inc_p/top'); // 상단 
		echo view('/inc_p/topMenu', $data['topMenu']); // 상단 메뉴
		echo view('/inc_p/topSearch', $data['topSearch']); // 상단 검색바
		// echo view('/inc/topMessage', $data['topMessage']); // 상단 메세지 (드랍다운)
		// echo view('/inc/topNotifi', $data['topNotifi']); // 상단 알림 (드랍다운)
		echo view('inc_p/topEnd'); // 상단 끝

		$cache_site_type = $this->SpoQCahce->getCacheVar('site_type');

		if ($cache_site_type != '') {
			if ($cache_site_type == 'admin') {
				echo view('/inc_p/left_admin', $data['left']); // 어드민 왼쪽 메뉴
			} elseif ($cache_site_type == 'sadmin') {
				echo view('/inc_p/left_sadmin', $data['left']); // 슈퍼관리자 왼쪽 메뉴
			} elseif ($cache_site_type == 'tlogin') {
				echo view('/inc_p/left_tlogin', $data['left']); // 지점 관리자 왼쪽 메뉴
			} elseif ($cache_site_type == 'mmlogin') {
				echo view('/inc_p/left_mmlogin', $data['left']); // 모바일 회원 왼쪽 메뉴
				echo view('/inc_p/style_mmlogin', $data['left']); // 모바일 회원,강사 스타일
			} elseif ($cache_site_type == 'mtlogin') {
				echo view('/inc_p/left_mtlogin', $data['left']); // 모바일 강사 왼쪽 메뉴
				echo view('/inc_p/style_mmlogin', $data['left']); // 모바일 회원,강사 스타일
			} else {
				echo view('/inc_p/left', $data['left']); // 왼쪽 메뉴
			}
		}

		// $output = view('/inc/contentTitle', $data['contentTitle']);
		// $output .= view($viewUrl, $data['view']);
		// $output .= view('inc/footer', $data['footer']); // 푸터
		
		echo view('/inc_p/contentTitle', $data['contentTitle']); // 컨텐츠 상단의 타이틀 및 네비게이션바
		echo view($viewUrl, $data['view']); // 컨텐츠 페이지
		echo view('inc_p/footer', $data['footer']); // 푸터
	}
	
	/**
	 * Main Frame의 View 페이지를 로드 합니다.
	 * @param String $viewUrl
	 * @param Array $data (header, topMenu, left, topSearch, topMessage, topNotifi, ContentTitle, view, footer)
	 */
	public function viewPage($viewUrl, $data = null)
	{
		
		// 만약에 값이 없으면 배열값들을 초기화 한다.
		if ($data == null) {
			$data['header'] = array();
			$data['topMenu'] = array();
			$data['left'] = array();
			$data['topSearch'] = array();
			$data['topMessage'] = array();
			$data['topNotifi'] = array();
			$data['contentTitle'] = array();
			$data['view'] = array();
			$data['footer'] = array();
		} else { // null이 아니라면 각 셋팅값을 비교하여 없는 배열값에 대한 초기화를 한다.
			if (!isset($data['header'])) $data['header'] = array();
			if (!isset($data['topMenu'])) $data['topMenu'] = array();
			if (!isset($data['left'])) $data['left'] = array();
			if (!isset($data['topSearch'])) $data['topSearch'] = array();
			if (!isset($data['topMessage'])) $data['topMessage'] = array();
			if (!isset($data['topNotifi'])) $data['topNotifi'] = array();
			if (!isset($data['contentTitle'])) $data['contentTitle'] = array();
			if (!isset($data['view'])) $data['view'] = array();
			if (!isset($data['footer'])) $data['footer'] = array();
		}

		

		$data['left']['ses'] = $this->SpoQCahce->getCacheObj(); // $_SESSION;
		$data['left']['menu1'] = $data['menu1'];
		$data['left']['menu2'] = $data['menu2'];

		if (isset($data['useJs'])) {
			$data['header']['useJs'] = $data['useJs'];
		} else {
			$data['header']['useJs'] = array();
			$data['useJs'] = array();
		}

		$data['footer']['jsurl'] = $this->request->uri->getPath();
		$data['init_fr'] = $this->init_fr;
		if (!$this->request->isAJAX()) {
			$data['view']['jsinc'] = view('/inc/jsinc', $data);
			$data['view']['init_fr'] = $this->init_fr;
		}

		if (isset($data['title'])) {
			$data['header']['title'] = $data['title'];
			$data['contentTitle']['title'] = $data['title'];
		} else {
			$data['header']['title'] = '';
			$data['contentTitle']['title'] = '';
		}

		if (isset($data['nav'])) {
			$data['header']['nav'] = $data['nav'];
			$data['contentTitle']['nav'] = $data['nav'];
		} else {
			$data['header']['nav'] = '';
			$data['contentTitle']['nav'] = '';
		}

		// AJAX 요청 여부 확인
		// if ($this->request->isAJAX()) {
		// 	$output = view('/inc/contentTitle', $data['contentTitle']);
		// 	$output = view($viewUrl, $data['view']);
		// 	$output .= view('inc/footer', $data['footer']); // 푸터
        //     return $output;
        // }

		echo view('/inc/header', $data['header']); // 헤더
		$cache_site_type = $this->SpoQCahce->getCacheVar('site_type');
		// if($cache_site_type == 'tlogin' || $cache_site_type == 'mmlogin' || $cache_site_type == 'mtlogin')
		// {
		// 	echo view('/inc/top'); // 상단 
		// 	echo view('/inc/topMenu', $data['topMenu']); // 상단 메뉴
		// 	echo view('/inc/topSearch', $data['topSearch']); // 상단 검색바
		// 	// echo view('/inc/topMessage', $data['topMessage']); // 상단 메세지 (드랍다운)
		// 	// echo view('/inc/topNotifi', $data['topNotifi']); // 상단 알림 (드랍다운)
		// 	echo view('inc/topEnd'); // 상단 끝
		// }

		$cache_site_type = $this->SpoQCahce->getCacheVar('site_type');

		if ($cache_site_type != '') {
			if ($cache_site_type == 'admin') {
				echo view('/inc/left_admin', $data['left']); // 어드민 왼쪽 메뉴
			} elseif ($cache_site_type == 'sadmin') {
				echo view('/inc/left_sadmin', $data['left']); // 슈퍼관리자 왼쪽 메뉴
			} elseif ($cache_site_type == 'tlogin') {
				echo view('/inc/left_tlogin', $data['left']); // 지점 관리자 왼쪽 메뉴
			} elseif ($cache_site_type == 'mmlogin') {
				echo view('/inc/left_mmlogin', $data['left']); // 모바일 회원 왼쪽 메뉴
				echo view('/inc/style_mmlogin', $data['left']); // 모바일 회원,강사 스타일
			} elseif ($cache_site_type == 'mtlogin') {
				echo view('/inc/left_mtlogin', $data['left']); // 모바일 강사 왼쪽 메뉴
				echo view('/inc/style_mmlogin', $data['left']); // 모바일 회원,강사 스타일
			} else {
				echo view('/inc/left', $data['left']); // 왼쪽 메뉴
			}
		}

		// $output = view('/inc/contentTitle', $data['contentTitle']);
		// $output .= view($viewUrl, $data['view']);
		// $output .= view('inc/footer', $data['footer']); // 푸터
		
		echo view('/inc/contentTitle', $data['contentTitle']); // 컨텐츠 상단의 타이틀 및 네비게이션바
		echo view($viewUrl, $data['view']); // 컨텐츠 페이지
		echo view('inc/footer', $data['footer']); // 푸터
	}

}
