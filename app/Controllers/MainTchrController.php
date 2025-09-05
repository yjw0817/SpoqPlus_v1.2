<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;

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

class MainTchrController extends MainController
{
	
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
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
		
		$this->loginUrl = "/tlogin";
		
		if ( isset($_SESSION['site_type']) ) 
		{
		    if($_SESSION['site_type'] != 'tlogin')
		    {
		        echo "<script>";
		        echo "alert('잘못된 접근입니다.');";
		        echo "location.href='/tlogin';";
		        echo "</script>";
		        exit();
		    }
		} else 
		{
		    echo "<script>";
		    echo "alert('로그인 인증이 만료 되었습니다. 다시 로그인해주세요.');";
		    echo "location.href='/tlogin';";
		    echo "</script>";
		    exit();
		}
		
		
	}
	
}
