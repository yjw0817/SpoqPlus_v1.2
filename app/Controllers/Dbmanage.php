<?php
namespace App\Controllers;

require_once APPPATH .  '/ThirdParty/phpspreadsheet/vendor/autoload.php';

use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Libraries\Ama_tm_sms;
use App\Models\CompanyModel;
use App\Models\DbmanageModel;
use App\Models\GcodeModel;
use App\Models\LandingModel;
use App\Models\TmModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Dbmanage extends MainController
{
	
	public function ajax_dbhideSet()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		$set_ses = null;
		
		if ($_SESSION['ses_' .$postVar['hf_field']] == 'N'):
			$set_ses = 'Y';
		else:
			$set_ses = 'N';
		endif;
		
		$_SESSION['ses_' .$postVar['hf_field']] = $set_ses;
		
		$upVar['hf_01'] = $_SESSION['ses_hf_01'];
		$upVar['hf_02'] = $_SESSION['ses_hf_02'];
		$upVar['hf_03'] = $_SESSION['ses_hf_03'];
		$upVar['hf_04'] = $_SESSION['ses_hf_04'];
		$upVar['hf_05'] = $_SESSION['ses_hf_05'];
		$upVar['hf_06'] = $_SESSION['ses_hf_06'];
		$upVar['tm_id'] = $_SESSION['user_id'];
		$model->update_tm_hideset($upVar);
		
		$return_json['result'] = 'true';
		$return_json['ses_yn'] = $set_ses;
		$return_json['hf_field'] = $postVar['hf_field'];
		
		return json_encode($return_json);
	}
	
	
	public function ajax_tmSmsSendProc()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		
		
		$sms_content = $postVar['sms_content'];
		//$sms_rphone = $postVar['sms_rphone'];
		//$sms_sphone = $_SESSION['sms_send_phone'];
		
		//$sms_rphone = '01051525316';
		//$sms_sphone = "01045589685";
		
		$sms_rphone = '010-3230-8007';
		$sms_sphone = '010-9527-9685';
		$sms_sphone = str_replace(' ', '',$sms_sphone);
		
		$initVar['msg'] = $sms_content;
		$initVar['revice_phone'] = $sms_rphone;
		$initVar['send_phone'] = $sms_sphone;
		$sms = new Ama_tm_sms($initVar);
		$sms->sendSms();
		
		$return_json['result'] = 'true';
		$return_json['msg'] = $postVar;
		return json_encode($return_json);
		
	}
	
	
	public function ajax_sms_read()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		$get_sms_idx = $model->get_sms_idx($postVar);
		$get_sms_for_db_idx = $model->get_sms_for_db_idx($postVar);
		
		$sms_content = $get_sms_idx[0]['sms_content'];
		$db_info = $get_sms_for_db_idx[0];
		
		/*
		 [#치과명]
		 [#오시는길] : temp_1
		 [#부재알림] : temp_2
		 [#주차안내]
		 $temp_1 = "지하철 2호선 역삼역 4번출구에서 뒤로돌아 신한은행이 보이는 골목으로 오시면 됩니다.";
		 $temp_2 = "부재시 대표번호\n본관 02-565-2876\n별관 02-6952-2877";
		 $sms_content = str_replace("[#치과명]",$db_info['comp_name'],$sms_content);
		 $sms_content = str_replace("[#오시는길]",$temp_1,$sms_content);
		 $sms_content = str_replace("[#부재알림]",$temp_2,$sms_content);
		 
		 -- 아래의 2항목만 치환하여 사용함 --
		 [#이름]
		 [#예약시간]
		 */
		
		$sms_content = str_replace('[#이름]', $db_info['user_name'],$sms_content);
		$sms_content = str_replace('[#예약시간]', disp_date_kr($db_info['ye_sdate']) . ' ' . disp_time_kr($db_info['ye_time']),$sms_content);
		
		$return_json['result'] = 'true';
		$return_json['msg'] = $sms_content;
		
		return json_encode($return_json);
	}
	
	
	public function ajax_mDbTmList_proc()
	{
		$model 		= new DbmanageModel();
		$initVar['post'] = $this->request->getPost();
		
		for($i=0; $i<count($initVar['post']['compset_idx']);$i++)
		{
			
			$upVar['idx'] = $initVar['post']['compset_idx'][$i];
			$upVar['tm_id'] = $initVar['post']['compset_tm'][$initVar['post']['compset_idx'][$i]];
			
			$model->db_tm_id_update($upVar);
		}
		
		$return_json['result'] = 'true';
		$return_json['update_count'] = count($initVar['post']['compset_idx']);
		
		return json_encode($return_json);
	}
	
	/**
	 * 2023-04-16 추가
	 * @return string
	 */
	public function ajax_mDbTmListD_proc()
	{
		$model 		= new DbmanageModel();
		$initVar['post'] = $this->request->getPost();
		
		for($i=0; $i<count($initVar['post']['compset_idx']);$i++)
		{
			$upVar['idx'] = $initVar['post']['compset_idx'][$i];
			$upVar['tm_id'] = $initVar['post']['compset_tm'][$initVar['post']['compset_idx'][$i]];
			$upVar['user_status'] = 'a16'; // 영구재배정 코드 ( a16 fixed )
			
			$model->db_tm_id_gd_update($upVar); // 티엠 배정 및 영구재배정 업데이트
			
			// history insert
			$ygdVar['user_status'] = 'a16';
			$ygdVar['idx'] = $upVar['idx'];
			$this->ygd_history($ygdVar);
			
		}
		
		
		$return_json['result'] = 'true';
		$return_json['update_count'] = count($initVar['post']['compset_idx']);
		$return_json['post'] = $initVar['post'];
		return json_encode($return_json);
	}
	
	
	
	
	/**
	 * 수동배정
	 */
	public function mDbTmList()
	{
		$data = array(
				'title' => '디비수동배정',
				'nav' => array('관리메뉴' => '/main', '디비수동배정' => '', '리스트' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		// 2023-05-18
		$tmModel	= new TmModel();
		$tmPutVar['bae_date'] = date('Ymd');
		$tm_list = $tmModel->get_tmautoset_admin($tmPutVar); // 실제 리스트 값
		
		// 배정할 DB의 업체 갯수를 group by count 한다.
		$db_comp_group_count = $model->db_comp_group_count();
		
		// company테이블의 업체 정보들을 가져온다 delyn != 'Y'
		$db_comp_list = $model->db_comp_list();
		
		// tmcompset_tb 의 각 티엠의 compset 정보를 가져온다.
		$tm_compset_list = $model->tm_compset_list();
		
		// db_comp_list 를 loop 돌면서 각 company_idx 기준으로 tm_compset_list 와 매핑하여 배열을 add 한다.
		$set_comp_bae_list = array();
		foreach ($db_comp_list as $d):
			
			$tm_id_array = array();
		
		
			foreach($tm_compset_list as $t):
				if ($d['idx'] == $t['comp_idx']):
					array_push($tm_id_array,$t['tm_id']);
				endif;
			endforeach;
			
			$set_comp_bae_list[$d['idx']]['tm_list'] = $tm_id_array;
			
		
		endforeach;
		
		// 배정할 업체 group count를 loop 돌면서 각 해당하는 티엠에게 갯수를 할당한다.
		// 만약에 배정갯수가 1개 인데 3명이 담당 하였다면 랜덤으로 티엠에게 할당한다.
		
		$new_bae_tm_count = array();
		
		foreach ($db_comp_group_count as $dg):
			// dg count , set_comp_bae_list에 해당하는 티엠 숫자를 비교한다.
			// 만약에 dg_count 값이 작으면 랜덤으로 티엠에게 배정 카운트를 부여한다.
		$gc = $dg['counter'];
		$tc = count($set_comp_bae_list[$dg['company_idx']]['tm_list']);		
		
				if ($gc < $tc):
					$r_Obj = $this->rnd_bae_tm($set_comp_bae_list[$dg['company_idx']]['tm_list'] , $gc , 0);
					$new_bae_tm_count[$dg['company_idx']]['tm_count'] = $r_Obj;
				else:
				
					if ($tc > 0):
						$set_count =  sprintf('%d', $gc / $tc);
						$remine_count = $gc % $tc;
						$r_Obj = $this->rnd_bae_tm($set_comp_bae_list[$dg['company_idx']]['tm_list'] , $remine_count , $set_count);
						$new_bae_tm_count[$dg['company_idx']]['tm_count'] = $r_Obj;
					endif;
				endif;
		endforeach;
		
		// 배정할 DB의 list를 가져온다.
		$db_list = $model->list_db_tm_bae();
		
		// 배정할 DB list에 티엠을 배정한다.
		
		$new_db_list = array();
		foreach ($db_list as $dd):
			$dd['set_tm_id'] = '';
			if ( isset($new_bae_tm_count[$dd['company_idx']]) ):
			 	$dd['set_tm_id'] = array_rand($new_bae_tm_count[$dd['company_idx']]['tm_count']);
			
			 	$remine_count =  $new_bae_tm_count[$dd['company_idx']]['tm_count'][$dd['set_tm_id']];
			 	$new_bae_tm_count[$dd['company_idx']]['tm_count'][$dd['set_tm_id']] = $remine_count - 1;
				
				if ($new_bae_tm_count[$dd['company_idx']]['tm_count'][$dd['set_tm_id']] == 0):
					unset($new_bae_tm_count[$dd['company_idx']]['tm_count'][$dd['set_tm_id']]);
				endif;
			endif;
			
			array_push($new_db_list,$dd);
			
		endforeach;
		
		$data['view']['set_comp_bae_list'] = $set_comp_bae_list;
		$data['view']['db_comp_group_count'] = $db_comp_group_count;
		$data['view']['db_list'] = $new_db_list;
		$data['view']['tm_list'] = $tm_list;
		
		$this->viewPage('/dbmanage/mDbTmList',$data);
		
	}
	
	/**
	 * 티엠을 랜덤으로 배정을 한다.
	 * db count 값이 tm_list count 값보다 작을 경우 수행을 한다.
	 * @param array $tm_list
	 * @param int $db_count
	 */
	private function rnd_bae_tm($tm_list , $db_count , int $set_count)
	{
		$tmp_tm_list = $tm_list;
		
		// $r_Obj 값에 각 티엠별 count 값을 $set_count 으로 셋팅한다.
		for($t = 0 ; $t < count($tm_list) ; $t++):
			$r_Obj[$tm_list[$t]] = $set_count;
		endfor;
		
		for($i=0; $i < $db_count; $i++):
			$rand_tm_bunji = rand(0,count($tmp_tm_list)-1);
			$r_Obj[$tmp_tm_list[$rand_tm_bunji]] = $set_count + 1;
			array_splice($tmp_tm_list, $rand_tm_bunji,1);
			
		endfor;

		foreach($r_Obj AS $rr => $v):
			if ($v == 0):
				unset($r_Obj[$rr]);
			endif;
		endforeach;
		
		return $r_Obj;
	}
	
	/**
	 * 2023-05-23
	 * 부재디비의 배치 관련 날짜 셋팅을 처리한다.
	 * @return string
	 */
	public function ajax_bu_day_auto_set_proc()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		
		$model->update_bu_day_auto_set($postVar);
		
		$return_json['result'] = 'true';
		return json_encode($return_json);
	}
	
	
	/**
	 * 2023-05-23 수정 : 부재디비 자동배정의 날짜 설정 가져오기 추가
	 * 2023-05-22 추가
	 * 지난부재디비관리 리스트
	 */
	public function mDbTmListB()
	{
		$data = array(
				'title' => '지난부재디비관리',
				'nav' => array('디비관리' => '/main', '지난부재디비관리' => '', '리스트' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		
		$bu_days[0]['bu_days'] = 0;
		$bu_days[0]['user_yn'] = 'N';
		
		$bu_days = $model->get_bu_day_auto_set();
		
		$data['view']['bu_day_auto_set'] = $bu_days[0];
		$data['view']['db_list'] = $model->list_db3();
		
		$postVar = $this->request->getPost();
		
		$this->viewPage('/dbmanage/mDbTmListB',$data);
	}
	
	/**
	 * 2023-05-22 추가
	 * 지난부재디비관리 예약반려, 영구부재 ajax 처리
	 */
	public function ajax_bu_proc()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		
		if ($postVar['scode'] == 'a5')
		{
			$a5_setVar['bu_update'] = $postVar['bu_date'];
			$model->update_budb_a5($a5_setVar);
		}
		
		if ($postVar['scode'] == 'a15')
		{
			$bu_list = $model->get_budb_a15($postVar['bu_date']);
			
			foreach ($bu_list as $r) :
				$ygdVar['user_status'] = 'a15';
				$ygdVar['idx'] = $r['idx'];
				$model->update_budb_a15($ygdVar);
				$this->ygd_history($ygdVar);
			endforeach;
		}
		
		$return_json['scode'] = $postVar['scode'];
		$return_json['bu_date'] = $postVar['bu_date'];
		$return_json['result'] = 'true';
		return json_encode($return_json);
	}
	
	/**
	 * 2023-04-16 추가
	 * 영구부재 history ajax
	 * @return string
	 */
	public function ajax_gd_history()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		
		$alert_msg = '[상세내역]';
		$history_list = $model->get_hd_history($postVar);
		foreach ($history_list as $r) :
			$st_name = '영구부재   ';
			if ($r['user_status'] == 'a16') $st_name = '영구재배정';
			$alert_msg .= "\n[" . $st_name . '] ' . disp_date($r['uptime']) . ' - ' . $r['tm_name'];
		endforeach;
		$return_json['result'] = 'true';
		$return_json['alert_msg'] = $alert_msg;
		
		return json_encode($return_json);
	}
	
	/**
	 * 2023-04-16 추가
	 * 영구부재 디비 관리
	 */
	public function mDbTmListD()
	{
		$data = array(
				'title' => '영구디비배정',
				'nav' => array('디비관리' => '/main', '영구디비배정' => '', '리스트' => ''),
		);
		
		$model 		= new DbmanageModel();
		$model2 = new GcodeModel();
		$data['view']['tm_list'] = $model2->get_tmlist(); // 티엠리스트
		$data['view']['db_list'] = $model->list_db2();
		
		$postVar = $this->request->getPost();
		
		
		if ( !isset($postVar['tm_bae_count']) ) $postVar['tm_bae_count'] = 0;
		if ( !isset($postVar['tm_bae_id']) ) $postVar['tm_bae_id'] = '';
		
		
		$data['view']['tm_bae_count'] = $postVar['tm_bae_count'];
		$data['view']['tm_bae_id'] = $postVar['tm_bae_id'];
		
		$this->viewPage('/dbmanage/mDbTmListD',$data);
	}
	
	/**
	 * 최종 업데이트일 : 2021-04-24
	 * 작성자 : amatelas
	 * 관리메뉴 > 업체&이벤트 관리
	 * 업체를 관리한다.
	 */
	public function mDblist()
	 {
	 	
		$data = array(
				'title' => '디비리스트',
				'nav' => array('디비관리' => '/main', '디비리스트' => '', '리스트' => ''),
		);
		
		$initVar['post'] = $this->request->getPost();
		$initVar['get'] = $this->request->getGet();
		$boardPage 	= new Ama_board($initVar);
		$model 		= new DbmanageModel();
		
		$totalCount  = $model->list_db_count($boardPage->getVal());
		$db_list = $model->list_db($boardPage->getVal());
		
		$searchVal = $boardPage->getVal();
		$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
		
		//if ( !isset($searchVal['didx']) ) $searchVal['didx'] = "";
		
		if (isset($initVar['post']['call_idx'])):
			$tmcall['delyn'] = 'Y';
			$tmcall['idx'] = $initVar['post']['call_idx'];
			$model->update_tmcall_delyn($tmcall);
		endif;
		
		if ( !isset($searchVal['unm']) ) $searchVal['unm'] = '';
		if ( !isset($searchVal['cnm']) ) $searchVal['cnm'] = '';
		if ( !isset($searchVal['ecd']) ) $searchVal['ecd'] = '';
		if ( !isset($searchVal['uph']) ) $searchVal['uph'] = '';
		if ( !isset($searchVal['stu']) ) $searchVal['stu'] = '';
		if ( !isset($searchVal['ncd']) ) $searchVal['ncd'] = '';
		
		if ( !isset($searchVal['uds']) ) $searchVal['uds'] = '';
		if ( !isset($searchVal['ude']) ) $searchVal['ude'] = '';
		if ( !isset($searchVal['yss']) ) $searchVal['yss'] = '';
		if ( !isset($searchVal['yse']) ) $searchVal['yse'] = '';
		if ( !isset($searchVal['yes']) ) $searchVal['yes'] = '';
		if ( !isset($searchVal['yee']) ) $searchVal['yee'] = '';
		
		if( !isset($searchVal['etc_search']) ) $searchVal['etc_search'] = '';
		 
		
		$company_list = $model->get_company_list(array()); // 업체 리스트
		$event_list = $model->get_event_list(array()); // 이벤트 리스트
		
		$sms_list = array();
		if ( $_SESSION['login_type'] == 'tm') :
			$sms_put['tm_id'] = $_SESSION['user_id'];
			$sms_list = $model->get_sms_list($sms_put); // TM 문자관리 리스트
		endif;
		
		$model = new GcodeModel();
		$data['view']['scode_list'] = $model->get_scode(); // 진행상태
		$data['view']['ncode_list'] = $model->get_ncode(); // 내원이유
		$data['view']['tm_list'] = $model->get_tmlist(); // 티엠리스트
		
		$data['view']['search_val'] = $searchVal;
		
		//_vardump($data['view']['search_val']);
		
		$yetime_array = [
				'08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00'
				,'13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30'
				,'20:00','20:30'
		];
		
		$data['view']['sms_list'] = $sms_list;
		$data['view']['yetime'] = $yetime_array;
		$data['view']['company_list'] = $company_list;
		$data['view']['event_list'] = $event_list;
		$data['view']['db_list'] = $db_list;
		$data['view']['pager'] = $boardPage->getPager($totalCount);
		
		$this->viewPage('/dbmanage/mDblist',$data);
	}
	
	public function mDblistExcel()
	{
		$initVar['post'] = $this->request->getPost();
		$initVar['get'] = $this->request->getGet();
		$boardPage 	= new Ama_board($initVar);
		$model 		= new DbmanageModel();
		$db_list = $model->list_db_excel($boardPage->getVal());
		
		$objPHPExcel = new Spreadsheet();
		
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', '신청일시')
		->setCellValue('B1', '이름')
		->setCellValue('C1', '전화번호')
		->setCellValue('D1', '업체')
		->setCellValue('E1', '신청이벤트')
		->setCellValue('F1', '내원이유')
		->setCellValue('G1', '진행상태')
		->setCellValue('H1', '내원예약일')
		->setCellValue('I1', '예약시간')
		->setCellValue('J1', '내원완료일')
		->setCellValue('K1', '견적금액')
		->setCellValue('L1', '동의금액')
		->setCellValue('M1', '신청내용')
		->setCellValue('N1', '상담내용');
		
		$i = 2;
		foreach ($db_list as $r):
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, disp_date($r['uptime']))
			->setCellValue('B'.$i, $r['user_name'])
			->setCellValue('C'.$i, disp_phone($r['user_phone']))
			->setCellValue('D'.$i, $r['comp_name'])
			->setCellValue('E'.$i, $r['event_name'])
			->setCellValue('F'.$i, $r['nname'])
			->setCellValue('G'.$i, $r['sname'])
			->setCellValue('H'.$i, disp_date($r['ye_sdate']))
			->setCellValue('I'.$i, $r['ye_time'])
			->setCellValue('J'.$i, disp_date($r['ye_edate']))
			->setCellValue('K'.$i, $r['cont_cost'])
			->setCellValue('L'.$i, $r['dong_cost'])
			->setCellValue('M'.$i, $r['needs'])
			->setCellValue('N'.$i, $r['scontent']);
			$i++;
		endforeach;
		
		$objPHPExcel->getActiveSheet()->setTitle('DB_List');
		
		header('Content-Disposition: attachment;filename="dbList_'. date('YmdHis') .'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header ('Cache-Control: cache, must-revalidate');
		header ('Pragma: public');
		
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
		$objWriter->save('php://output');
		exit;
	}
	
	public function mDblistSta1()
	{
		$data = array(
				'title' => '디비통계(대행사별)',
				'nav' => array('디비관리' => '/main', '디비통계' => '', '대행사별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('YmdHis', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('YmdHis', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta1_Group($data);
		$db_list = $model->mDblistSta1($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['days']] = $r['comp_count'];
		}
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup; 
		$data['day_count'] = $day_count;
		$this->viewPage('/dbmanage/mDblistSta1',$data);
	}
	
	public function mDblistSta2()
	{
		$data = array(
				'title' => '디비통계(이벤트별)',
				'nav' => array('디비관리' => '/main', '디비통계' => '', '이벤트별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('YmdHis', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('YmdHis', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta2_Group($data);
		
		
		$db_list = $model->mDblistSta2($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][$g['event_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['event_idx']][$r['days']] = $r['event_count'];
		}
		
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup;
		$data['day_count'] = $day_count;
		
		$this->viewPage('/dbmanage/mDblistSta2',$data);
	}
	
	public function mDblistSta3()
	{
		$data = array(
				'title' => '예약통계(대행사별)',
				'nav' => array('디비관리' => '/main', '예약통계' => '', '대행사별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('Ymd', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('Ymd', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta3_Group($data);
		$db_list = $model->mDblistSta3($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['days']] = $r['comp_count'];
		}
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup;
		$data['day_count'] = $day_count;
		$this->viewPage('/dbmanage/mDblistSta3',$data);
	}
	
	public function mDblistSta4()
	{
		$data = array(
				'title' => '예약통계(이벤트별)',
				'nav' => array('디비관리' => '/main', '예약통계' => '', '이벤트별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('Ymd', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('Ymd', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta4_Group($data);
		
		
		$db_list = $model->mDblistSta4($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][$g['event_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['event_idx']][$r['days']] = $r['event_count'];
		}
		
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup;
		$data['day_count'] = $day_count;
		
		$this->viewPage('/dbmanage/mDblistSta4',$data);
	}
	
	/**
	 * ===============================================================================
	 * 2023-04-24 추가 [시작]
	 * ===============================================================================
	 */
	
	/**
	 * 내원예약통계 (대행사별) : 2023-04-24 추가
	 */
	public function mDblistSta5()
	{
		$data = array(
				'title' => '내원예약통계(대행사별)',
				'nav' => array('디비관리' => '/main', '내원예약통계' => '', '대행사별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('Ymd', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('Ymd', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta5_Group($data);
		$db_list = $model->mDblistSta5($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['days']] = $r['comp_count'];
		}
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup;
		$data['day_count'] = $day_count;
		$this->viewPage('/dbmanage/mDblistSta5',$data);
	}
	
	/**
	 * 내원예약통계 (이벤트별) : 2023-04-24 추가
	 */
	public function mDblistSta6()
	{
		$data = array(
				'title' => '내원예약통계(이벤트별)',
				'nav' => array('디비관리' => '/main', '내원예약통계' => '', '이벤트별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('Ymd', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('Ymd', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta6_Group($data);
		
		
		$db_list = $model->mDblistSta6($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][$g['event_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['event_idx']][$r['days']] = $r['event_count'];
		}
		
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup;
		$data['day_count'] = $day_count;
		
		$this->viewPage('/dbmanage/mDblistSta6',$data);
	}
	
	/**
	 * 내원완료통계 (대행사별) : 2023-04-24 추가
	 */
	public function mDblistSta7()
	{
		$data = array(
				'title' => '내원완료통계(대행사별)',
				'nav' => array('디비관리' => '/main', '내원완료통계' => '', '대행사별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('Ymd', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('Ymd', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta7_Group($data);
		$db_list = $model->mDblistSta7($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['days']] = $r['comp_count'];
		}
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup;
		$data['day_count'] = $day_count;
		$this->viewPage('/dbmanage/mDblistSta7',$data);
	}
	
	/**
	 * 내원완료통계 (이벤트별) : 2023-04-24 추가
	 */
	public function mDblistSta8()
	{
		$data = array(
				'title' => '내원완료통계(이벤트별)',
				'nav' => array('디비관리' => '/main', '내원완료통계' => '', '이벤트별' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$postVar = $this->request->getPost();
		
		$syy = date('Y');
		$smm = date('m');
		
		if (isset($postVar['syy']))
		{
			$syy = $postVar['syy'];
		}
		
		if (isset($postVar['smm']))
		{
			$smm = $postVar['smm'];
		}
		
		
		
		$data['sdate'] = $syy . $smm . '01';
		$nextMonth = date('Ymd', strtotime( $data['sdate'] . ' +1 months'));
		$data['edate'] = date('Ymd', strtotime( $nextMonth . ' -1 days'));
		
		$day_count = date('t', strtotime($data['sdate']));
		
		$db_group = $model->mDblistSta8_Group($data);
		
		
		$db_list = $model->mDblistSta8($data);
		
		$dbGroup = array();
		
		foreach ($db_group as $g)
		{
			for($i=1; $i<$day_count+1; $i++)
			{
				$dbGroup[$g['company_idx']][$g['event_idx']][sprintf('%02d', $i)] = 0;
			}
		}
		
		foreach ($db_list as $r)
		{
			$dbGroup[$r['company_idx']][$r['event_idx']][$r['days']] = $r['event_count'];
		}
		
		
		$data['syy'] = $syy;
		$data['smm'] = $smm;
		$data['dbgroup'] = $db_group;
		$data['dblist'] = $dbGroup;
		$data['day_count'] = $day_count;
		
		$this->viewPage('/dbmanage/mDblistSta8',$data);
	}
	
	/**
	 * ===============================================================================
	 * 2023-04-24 추가 [끝]
	 * ===============================================================================
	 */
	
	
	
	public function mDblistMoveEvent()
	{
		$data = array(
				'title' => '이벤트 디비이관',
				'nav' => array('디비관리' => '/main', '이벤트 디비이관' => '', '설정' => ''),
		);
		
		$model 		= new DbmanageModel();
		
		$event_list = $model->get_event_list(array()); // 이벤트 리스트
		$data['view']['event_list'] = $event_list;
		
		$this->viewPage('/dbmanage/mDblistMoveEvent',$data);
	}
	
	
	public function mDblistUpload()
	{
		$data = array(
				'title' => '디비일괄입력',
				'nav' => array('디비관리' => '/main', '디비일괄입력' => '', '업로드' => ''),
		);
		
		$this->viewPage('/dbmanage/mDblistUpload',$data);
	}
	
	public function mDblistUploadProc()
	{
		$file = $this->request->getFile('userfile');
		
		
		$file->move(WRITEPATH . 'uploads');
		
		/*
		$data = [
				'name' =>  $file->getName(),
				'type'  => $file->getClientMimeType()
		];
		*/
		
		$server_inputFileName = $file->getName();
		$file_type= pathinfo($server_inputFileName, PATHINFO_EXTENSION);
		
		if ($file_type == 'xlsx'):
			$reader = new Xlsx();
		endif;
		
		
		$spreadsheet = $reader->load(WRITEPATH . 'uploads/' . $server_inputFileName);
		$spreadData = $spreadsheet-> getActiveSheet()->toArray();
		
		$this->exce_insert($spreadData);
	}
	
	private function exce_insert($excelArray)
	{
		$model = new LandingModel();
		
		foreach ($excelArray as $r)
		{
			if ($r[0] != '이벤트번호')
			{
				$chk_event['idx'] = $r[0];
				$chk_event = $model->chk_event($chk_event);
				
				if ( count($chk_event) > 0 )
				{
					$insVar = array();
					
					$dup_phone['user_phone'] = str_replace('-', '',$r[2]);
					$chk_dup_dblist_a4 = $model->chk_dup_dblist_a4($dup_phone);
					
					if ( $chk_dup_dblist_a4[0]['counter'] > 0):
						$insVar['user_status'] = 'a14';
					else :
						$insVar['user_status'] = '';
					endif;
					
					$insVar['event_idx'] = $r[0];
					$insVar['company_idx'] = $chk_event[0]['comp_idx'];
					$insVar['user_name'] = $r[1];
					$insVar['user_phone'] = str_replace('-', '',$r[2]);
					$insVar['needs'] = $r[3];
					$insVar['uptime'] = date('YmdHis');
					
					$ins_dblist = $model->insert_ex_dblist($insVar);
					
				}
				
			}
		}
		
		echo "<script>alert('Excel file Uploaded. Suceess.');</script>";
		echo "<script>location.href='/dbmanage/mDblistUpload';</script>";
		exit();
	}
	
	public function ajax_eventMoveInfo()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		
		$event_result = $model->get_event_move_info($postVar);
		
		$return_json['result'] = 'true';
		$return_json['event_result'] = $event_result;
		
		return json_encode($return_json);
	}
	
	public function ajax_eventMoveProc()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		$event_update = null;
		
		// $postVar['e2_idx'] 값을 이용해서 comp_idx 값을 가져온다.
		$get_update_event_info = $model->get_update_event_info($postVar);
		
		if ( count($get_update_event_info) > 0 )
		{
			$upVar['company_idx'] = $get_update_event_info[0]['comp_idx'];
			$upVar['event_idx'] = $get_update_event_info[0]['event_idx'];
			$upVar['w_event_idx'] = $postVar['e1_idx'];
			$event_update = $model->update_event_move($upVar);
		}
		
		$return_json['result'] = 'true';
		$return_json['post'] = $postVar;
		$return_json['event_update'] = $event_update;
		
		return json_encode($return_json);
	}
	
	public function ajax_dblist_tmcallProc()
	{
		$postVar = $this->request->getPost();
		
		
		$model = new DbmanageModel();
		$get_user_name = $model->get_dblist_username($postVar);
		
		$postVar['uptime'] = date('YmdHis');
		
		if ( count($get_user_name) > 0 ) :
			$postVar['db_user_name'] = $get_user_name[0]['user_name'];
		else :
			$postVar['db_user_name'] = '';
		endif;
		
		$call_add_time = strtotime('+' .$postVar['t'] . $postVar['dan']);
		
		$postVar['tm_id'] = $postVar['ses_user_id'];
		$postVar['call_date'] = date('Ymd', $call_add_time);
		$postVar['call_time'] = date('Hi', $call_add_time);
		$postVar['uptime'] = date('YmdHis');
		$postVar['delyn'] = 'N';
				
		$dblist_result = $model->insert_tmcall($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $dblist_result;
		$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_dblist_su_tmcallProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$get_user_name = $model->get_dblist_username($postVar);
		
		if ( count($get_user_name) > 0 ) :
		$postVar['db_user_name'] = $get_user_name[0]['user_name'];
		else :
		$postVar['db_user_name'] = '';
		endif;
		
		$postVar['tm_id'] = $postVar['ses_user_id'];
		$postVar['call_date'] = $postVar['td'];
		$postVar['call_time'] = $postVar['tt'];
		$postVar['uptime'] = date('YmdHis');
		$postVar['delyn'] = 'N';
		
		$dblist_result = $model->insert_tmcall($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $dblist_result;
		$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_dblist_deleteProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$db_delete_result = $model->delete_dblist($postVar);
		
		$return_json['result'] = 'true';
		$return_json['db_content'] = $db_delete_result[0];
		
		return json_encode($return_json);
	}
	
	public function ajax_scontentCheck()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$db_content_result = $model->get_scontent($postVar);
		
		$return_json['result'] = 'true';
		$return_json['db_content'] = $db_content_result[0];
		
		return json_encode($return_json);
	}
	
	public function ajax_get_dblist()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$db_get_result = $model->get_get_dblist($postVar);
		
		$r = $db_get_result[0];
		
		$clip_content = '<table><tr><td>' .disp_date($r['uptime']). '</td>';
		$clip_content .= '<td>' . $r['user_name'] . '</td>';
		$clip_content .= '<td>' . disp_phone($r['user_phone']) . '</td>';
		//$clip_content .="<td>" . $r['comp_name'] . "</td>";
		$clip_content .= '<td>' . $r['event_name'] . '</td>';
		//$clip_content .="<td>" . $r['nname'] . "</td>";
		$clip_content .= '<td>' . $r['sname'] . '</td>';
		$clip_content .= '<td>' . disp_date($r['ye_sdate']) . '</td>';
		$clip_content .= '<td>' . $r['ye_time'] . '</td>';
		$clip_content .= '<td>' . disp_date($r['ye_edate']) . '</td>';
		//$clip_content .="<td>" . number_format($r['cont_cost']) . " </td>";
		//$clip_content .="<td>" . number_format($r['dong_cost']) . "</td>";
		$clip_content .= '<td>' . $r['scontent'] . '</td></tr></table>';
		
		$return_json['result'] = 'true';
		$return_json['clip_content'] = $clip_content;
		$return_json['user_name'] = $r['user_name'];
		
		return json_encode($return_json);
	}
	
	/**
	 * 2023-05-24 복사2 추가
	 * @return string
	 */
	public function ajax_get_dblist2()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$db_get_result = $model->get_get_dblist($postVar);
		
		$r = $db_get_result[0];
		
		$clip_content = '';
		
		//$clip_content = "<table><tr><td>".disp_date($r['uptime'])."</td>";
		//$clip_content .="<td>" . $r['user_name'] . "</td>";
		//$clip_content .="<td>" . disp_phone($r['user_phone']) . "</td>";
		//$clip_content .="<td>" . $r['comp_name'] . "</td>";
		//$clip_content .="<td>" . $r['event_name'] . "</td>";
		//$clip_content .="<td>" . $r['nname'] . "</td>";
		//$clip_content .="<td>" . $r['sname'] .  "</td>";
		//$clip_content .="<td>" . disp_date($r['ye_sdate']) . "</td>";
		//$clip_content .="<td>" . $r['ye_time'] .  "</td>";
		//$clip_content .="<td>" . disp_date($r['ye_edate']) . "</td>";
		//$clip_content .="<td>" . number_format($r['cont_cost']) . " </td>";
		//$clip_content .="<td>" . number_format($r['dong_cost']) . "</td>";
		//$clip_content .="<td>" . $r['scontent'] . "</td></tr></table>";
		
		$clip_content .= $r['user_name'];
		$clip_content .= ' ';
		$clip_content .= disp_phone($r['user_phone']);
		
		$return_json['result'] = 'true';
		$return_json['clip_content'] = $clip_content;
		$return_json['user_name'] = $r['user_name'];
		
		return json_encode($return_json);
	}
	
	/**
	 * 2023-04-16 : 상담내용 모달 팝업에서 상태변경 추가로 인해 아래 내용 수정함.
	 * @return string
	 */
	public function ajax_scontentModifyProc()
	{
		
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		
		$db_content_result = $model->update_scontent($postVar);
		
		if (isset($postVar['modal-xl-select'])) :
			if ($postVar['modal-xl-select']) :
				$postVar['user_status'] = $postVar['modal-xl-select'];
				$postVar['idx'] = $postVar['db_idx'];
				$status_result = $model->update_dblist_status($postVar);
				
				$ye_update = null;
				if ($postVar['user_status'] == 'a3'):
					$postVar['ye_update'] = date('Ymd');
					$ye_update = $model->update_dblist_ye_update($postVar);
				endif;
				
				// 2023-05-17
				// 부재중일때 dblist_tb.bu_update 필드에 최종 부재중 상태를 설정한 날짜를 업데이트 한다.
				$bu_update = null;
				if ($postVar['user_status'] == 'a1'):
					$postVar['bu_update'] = date('Ymd');
					$bu_update = $model->update_dblist_bu_update($postVar);
				endif;
				
				// 2023-04-16
				// 영구부재일 경우에 history 테이블에 insert
				// a15 ( 영구부재 코드 ) fixed
				if ($postVar['user_status'] == 'a15'):
					$ygdVar['user_status'] = 'a15';
					$ygdVar['idx'] = $postVar['idx'];
					$this->ygd_history($ygdVar);
				endif;
		
			endif;
		endif;
		
		$return_json['result'] = 'true';
		$return_json['post'] = $db_content_result;
		//$return_json['status_result'] = $status_result; // 의미 없는 리턴
		//$return_json['ye_update'] = $ye_update; // 의미 없는 리턴
		$return_json['insert_id'] = $db_content_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_tmTransModifyProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		
		$upVar['tm_id'] = $postVar['ch_tm'];
		$upVar['idx'] = $postVar['tm_modal_idx'];
		$db_content_result = $model->update_transTmForIdx($upVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $db_content_result;
		$return_json['insert_id'] = $db_content_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
	public function ajax_mDblistInsertProc()
	{
		$postVar = $this->request->getPost();
		$postVar['uptime'] = date('YmdHis');
		$postVar['user_phone'] = str_replace('-', '',$postVar['user_phone']);
		
		$model = new DbmanageModel();
		$db_dup_check = $model->dup_phone_dblist($postVar);
		
		if ($db_dup_check[0]['counter'] == 0):
			$postVar['user_status'] = '';
			$return_json['dup_text'] = '';
		else:
			$postVar['user_status'] = 'a14';
			$return_json['dup_text'] = '[중복]';
		endif;
		
		
		if ( $_SESSION['login_type'] == 'tm') :
			$postVar['tm_id'] = $_SESSION['user_id'];
		else:
			$postVar['tm_id'] = NULL;
		endif;
		
		$dblist_result = $model->insert_dblist($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $dblist_result;
		$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistCompanyChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$status_result = $model->update_dblist_company($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $status_result;
		$return_json['insert_id'] = $status_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistEventChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$status_result = $model->update_dblist_event($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $status_result;
		$return_json['nname'] = $status_result[0]['nname'];
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistStatusChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$status_result = $model->update_dblist_status($postVar);

		$ye_update = null;
		if ($postVar['user_status'] == 'a3'):
			$postVar['ye_update'] = date('Ymd');
			$ye_update = $model->update_dblist_ye_update($postVar);
		endif;
		
		// 2023-05-17 
		// 부재중일때 dblist_tb.bu_update 필드에 최종 부재중 상태를 설정한 날짜를 업데이트 한다.
		$bu_update = null;
		if ($postVar['user_status'] == 'a1'):
			$postVar['bu_update'] = date('Ymd');
			$bu_update = $model->update_dblist_bu_update($postVar);
		endif;
		
		// 2023-04-16
		// 영구부재일 경우에 history 테이블에 insert
		// a15 ( 영구부재 코드 ) fixed
		if ($postVar['user_status'] == 'a15'):
			$ygdVar['user_status'] = 'a15';
			$ygdVar['idx'] = $postVar['idx'];
			$this->ygd_history($ygdVar);
		endif;
		
		
		$return_json['result'] = 'true';
		$return_json['post'] = $status_result;
		$return_json['ye_update'] = $ye_update;
		$return_json['insert_id'] = $status_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	/**
	 * 2023-04-16 추가
	 * 영구부재 일경우와 영구재배정 일경우에 history table에 insert 하여 히스토리를 관리함.
	 * @param array $var
	 */
	private function ygd_history($var)
	{
		// DB에서 기존 TM의 아이디를 구해 오고 history 를 입력함.
		
		$model = new DbmanageModel();
		$tm_id = $model->get_tmid_for_db_list($var['idx']);
		
		$set_tm_id = '';
		if( count($tm_id) > 0 ) $set_tm_id = $tm_id[0]['tm_id'];
		
		$insVar['uptime'] = date('YmdHis');
		$insVar['user_status'] = $var['user_status'];
		$insVar['db_idx'] = $var['idx'];
		$insVar['tm_id'] = $set_tm_id;
		
		$data_result = $model->insert_tmgd_history($insVar);
		
		// 2023-05-16 추가 [시작]
		// tmgd_history_tb 에서 content 내역을 합치고 저장한다. 한다.
		// tmgd_history_tb 중에 a15 영구부재인 것을 배열로 저장하고 implode , 하고 tmgd_content 의 tm_id_sum 에 저장한다.
		// 만약에 count == 0 이라면 tmgd_content_tb 에 insert 를 한다.
		// 만약에 count > 0 이라면 tmgd_content_tb 에 update 를 한다.
		
		$data_history_result = $model->get_hd_history($var);
		$db_content = '';
		$tm_id_sum_array = array();
		$unq_tm_id_sum_array = array();
		foreach ($data_history_result as $r) :
			$st_name = '영구부재   ';
			if ($r['user_status'] == 'a16') $st_name = '영구재배정';
			$db_content .= "\n[" . $st_name . '] ' . disp_date($r['uptime']) . ' - ' . $r['tm_name'];
			
			if ($r['user_status'] == 'a15'): 
				array_push($tm_id_sum_array,$r['tm_id']);
				$unq_tm_id_sum_array = array_unique($tm_id_sum_array);
			endif;
			
		endforeach;
		
		$tmgd_content_count = $model->count_tmgd_content($var);
		
		$contentVar['db_idx'] = $var['idx'];
		$contentVar['db_content'] = $db_content;
		$contentVar['tm_id_sum'] = implode(',', $unq_tm_id_sum_array);
		
		if ($tmgd_content_count[0]['counter'] == 0) :
			$model->insert_tmgd_content($contentVar);
		else :
			$model->update_tmgd_content($contentVar);
		endif;
		
		// 2023-05-16 추가 [끝]
		
	}
	
	
	public function ajax_mDblistyeDateChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$date_result = $model->update_dblist_date($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $date_result;
		$return_json['insert_id'] = $date_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistYetimeChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$date_result = $model->update_dblist_yetime($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $date_result;
		$return_json['insert_id'] = $date_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistDongCostChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$date_result = $model->update_dblist_dong_cost($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $date_result;
		$return_json['insert_id'] = $date_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistContCostChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$date_result = $model->update_dblist_cont_cost($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $date_result;
		$return_json['insert_id'] = $date_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistUsernameChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$db_result = $model->update_dblist_username($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $db_result;
		$return_json['insert_id'] = $db_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mDblistUserphoneChange()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$postVar['user_phone'] = str_replace('-', '',$postVar['user_phone']); // 전화번호는 - 을 제거하고 입력한다.
		$db_result = $model->update_dblist_userphone($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $db_result;
		$return_json['insert_id'] = $db_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
	
	
	public function mCompanyInsert()
	{
		$data = array(
				'title' => '상태관리',
				'nav' => array('관리메뉴' => '/main', '업체&이벤트 관리' => '', '등록하기' => ''),
		);
		
		$model = new GcodeModel();
		$data['view']['gcode_list'] = $model->get_gcode(); // 업체구분
		
		$this->viewPage('/manage/mCompanyInsert',$data);
	}
	
	public function ajax_mCompanyInsertProc()
	{
		$postVar = $this->request->getPost();
		
		if ( !isset($postVar['comp_use']))
		{
			$postVar['comp_use'] = 'N';
		}
		
		$model = new CompanyModel();
		$company_result = $model->insert_company($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $company_result;
		$return_json['insert_id'] = $company_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function mCompanyModify($idx='')
	{
		$data = array(
				'title' => '상태관리',
				'nav' => array('관리메뉴' => '/main', '업체&이벤트 관리' => '', '수정하기' => ''),
		);
		
		$setData['idx'] = $idx;
		
		$model = new CompanyModel();
		$company_result = $model->get_company($setData);
		$data['view']['data'] = $company_result;
		$event_list = $model->list_event(array('comp_idx'=>$idx));
		$data['view']['event_list'] = $event_list;
		
		$model = new GcodeModel();
		$data['view']['gcode_list'] = $model->get_gcode(); // 업체구분
		$data['view']['ncode_list'] = $model->get_ncode(); // 내원이유
		
		$this->viewPage('/manage/mCompanyModify',$data);
	}
	
	public function ajax_mCompanyModifytProc()
	{
		$postVar = $this->request->getPost();
		
		if ( !isset($postVar['comp_use']))
		{
			$postVar['comp_use'] = 'N';
		}
		
		$model = new CompanyModel();
		$company_result = $model->update_company($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $company_result;
		$return_json['insert_id'] = $company_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mEvnetInsertProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$event_result = $model->insert_event($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $event_result;
		$return_json['insert_id'] = $event_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mEventGet()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$event_result = $model->get_event($postVar);
		
		$return_json['result'] = 'true';
		$return_json['eventinfo'] = $event_result[0];
		
		return json_encode($return_json);
		
	}
	
	public function ajax_mEventModifytProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$event_result = $model->update_event($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $event_result;
		$return_json['insert_id'] = $event_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
}