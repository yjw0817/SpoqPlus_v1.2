<?php
namespace App\Controllers;
use App\Libraries\Ama_calendar;
class Main extends MainController
{
	public function index()
	{
		$this->dashboard();
	}
	
	/**
	 * Dashboard에서 보여줄 나의 스케쥴
	 */
	public function dashboard()
	{
		$data = array(
			'title' => 'Schedule',
			'nav' => array('Main' => '/main', 'Schedule' => ''),
		    'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2'),
			'useJs' => array('fullCalendar'),
			'fullCalendar' => array(
									'url' => '/main/dashboard_proc'
									)
				
		);
		
		$this->viewPage('/main/dashboard',$data);
	}
	
	public function dashboard_proc()
	{
		$postVar = $this->request->getPost();
		
		// 디버그 로그: 요청 데이터
		error_log("🎯 Main::dashboard_proc() 시작 - POST 데이터: " . json_encode($postVar));
		error_log("🏢 세션 정보 - comp_cd: " . ($_SESSION['comp_cd'] ?? 'null') . ", bcoff_cd: " . ($_SESSION['bcoff_cd'] ?? 'null'));
		
		$Cal = new Ama_calendar($postVar);
		$result = $Cal->getCalendar();
		
		error_log("📤 Main::dashboard_proc() 완료 - 응답 길이: " . strlen($result));
		
		return $result;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function main2()
	{
		$data = array(
			'useJs' => array('fullCalendar')
		);
		$this->viewPage('/main/main',$data);
	}
	
	/**
	 * xml test
	 */
	public function xml_test()
	{
		$xmlFile = simplexml_load_file('xml/test.xml');
		//$xmlstring = $xmlFile->statement->asXML();
		//$xml_ss = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
		$json_ex = json_encode($xmlFile);
		$array_pr = json_decode($json_ex,TRUE);
		echo '<pre>';
		var_dump($array_pr);
	}
		
}