<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;

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

class BaseController extends Controller
{
	private $protect_key; // 직접 접근을 막기 위한 암호화 변수
	private $protect_page_key; // 연속된 페이지에서 입력되는 암호화 변수
	
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['My_helper'];
	protected $session;

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
	 * Main Frame의 View 페이지를 로드 합니다.
	 * @param String $viewUrl
	 * @param Array $data (header, topMenu, left, topSearch, topMessage, topNotifi, ContentTitle, view, footer)
	 */
	protected function viewPage($viewUrl,$data = null)
	{
		// 만약에 값이 없으면 배열값들을 초기화 한다.
		if ($data == null)
		{
			$data['header'] 		= array();
			$data['topMenu'] 		= array();
			$data['left'] 			= array();
			$data['topSearch'] 		= array();
			$data['topMessage'] 	= array();
			$data['topNotifi'] 		= array();
			$data['contentTitle'] 	= array();
			$data['view'] 			= array();
			$data['footer'] 		= array();
		} else // null이 아니라면 각 셋팅값을 비교하여 없는 배열값에 대한 초기화를 한다.
		{
			if ( is_array($data['header']) ) 		$data['header'] 		= array();
			if ( is_array($data['topMenu']) ) 		$data['top'] 			= array();
			if ( is_array($data['left']) ) 			$data['left'] 			= array();
			if ( is_array($data['topSearch']) ) 	$data['topSearch'] 		= array();
			if ( is_array($data['topMessage']) ) 	$data['topMessage'] 	= array();
			if ( is_array($data['topNotifi']) ) 	$data['topNotifi'] 		= array();
			if ( is_array($data['contentTitle']) ) 	$data['contentTitle'] 	= array();
			if ( is_array($data['view']) ) 			$data['view'] 			= array();
			if ( is_array($data['footer']) ) 		$data['footer'] 		= array();
		}
		
		echo view('/inc/header'		,$data['header']		); // 헤더
		echo view('/inc/top'								); // 상단 
		echo view('/inc/topMenu' 	,$data['topMenu']		); // 상단 메뉴
		echo view('/inc/topSearch'	,$data['topSearch']		); // 상단 검색바
		echo view('/inc/topMessage'	,$data['topMessage']	); // 상단 메세지 (드랍다운)
		echo view('/inc/topNotifi'	,$data['topNotifi']		); // 상단 알림 (드랍다운)
		echo view('inc/topEnd'								); // 상단 끝
		echo view('/inc/left'		,$data['left']			); // 왼쪽 메뉴
		echo view('/inc/contentTitle',$data['contentTitle']	); // 컨텐츠 상단의 타이틀 및 네비게이션바
		echo view($viewUrl			,$data['view']			); // 컨텐츠 페이지
		echo view('inc/footer'		,$data['footer']		); // 푸터
	}
}
