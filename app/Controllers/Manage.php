<?php
namespace App\Controllers;


use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Libraries\Ama_sms;
use App\Models\CompanyModel;
use App\Models\DbmanageModel;
use App\Models\GcodeModel;
use App\Models\TmModel;

/**
 * @author Amatelas
 * @copyright 2022-11-23
 */
class Manage extends MainController
{
	
	public function ajax_tm_autoset()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		// 2023-05-19
		// 관리자모드 , 수동배정에서 티엠 배정시작, 종료를 하기 위해서 로직을 수정함.
		if (isset($postVar['tm_id']))
		{
			$setVar['tm_id'] = $postVar['tm_id'];
		} else 
		{
			$setVar['tm_id'] = $_SESSION['user_id'];
		}
		
		$setVar['bae_date'] = date('Ymd');
		$setVar['bae_yn'] = $postVar['flag'];
		$count_tmautoset = $model->count_tmautoset($setVar);
		
		if ($count_tmautoset[0]['counter'] == 0)
		{
			$model->ins_tmautoset($setVar);
		} else 
		{
			$model->update_tmautoset($setVar);
		}
		
		$_SESSION['tm_bae_set'] = $postVar['flag'];
		
		$return_json['result'] = 'true';
		$return_json['tm_id'] = $setVar['tm_id']; // 2023-05-19
		$return_json['flag'] = $postVar['flag'];
		return json_encode($return_json);
	}
	
	
	public function ajax_set_auto_time()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		//$db_content_result = $model->get_tm_sms($postVar);
		
		for($i=0; $i<24; $i++):
			$num = sprintf('%02d',$i);
			$upVar['h_'.$num.'00'] = 'N';
			$upVar['h_'.$num.'30'] = 'N';
			
			if ( isset($postVar['h_'.$num.'00']) ) $upVar['h_'.$num.'00'] = $postVar['h_'.$num.'00'];
			if ( isset($postVar['h_'.$num.'30']) ) $upVar['h_'.$num.'30'] = $postVar['h_'.$num.'30'];
		endfor;
		
		$upVar['ctype'] = 'db_to_tm_auto_bae';
		
		$time_info = $model->set_auto_time_info($upVar);
		
		$return_json['result'] = 'true';
		$return_json['time_info'] = $time_info;
		//$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		return json_encode($return_json);
	}
	
	/**
	 * 관리메뉴 > 자동배정관리
	 */
	public function mAutoManager()
	{
		// title 과 navigation을 설정한다.
		$data = array(
				'title' => '자동배정관리',
				'nav' => array('관리메뉴' => '/main', '자동배정관리' => ''),
		);
		
		$model 		= new TmModel();
		$putVar['ctype'] = 'db_to_tm_auto_bae';
		
		$get_auto_time_info = $model->get_auto_time_info($putVar);
		
		$data['view']['get_auto_time_info'] = $get_auto_time_info[0];
		$this->viewPage('/manage/mAutoManager',$data);
	}
	
	/**
	 * 디비관리 > TM문자관리 <br />
	 * TM문자관리 메뉴 링크
	 *
	 * @param
	 * @return
	 */
	public function mTmsmsManager()
	{
		// title 과 navigation을 설정한다.
		$data = array(
				'title' => 'TM 문자관리',
				'nav' => array('관리메뉴' => '/main', 'TM 문자관리' => ''),
		);
		
		$model 		= new TmModel();
		$putVar['tm_id'] = $_SESSION['user_id'];
		
		$tm_sms_list = $model->list_tm_sms($putVar);
		
		$data['view']['tm_sms_list'] = $tm_sms_list;
		$this->viewPage('/manage/mTmsmsManager',$data);
	}
	
	/**
	 * TM 문자관리
	 * ajax . 해당 TM 문자정보 가져오기
	 * @return string
	 */
	public function ajax_get_tmSms()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		$postVar['tm_id'] = $_SESSION['user_id'];
		$db_content_result = $model->get_tm_sms($postVar);
		
		$return_json['result'] = 'true';
		$return_json['db_content'] = $db_content_result[0];
		//$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		return json_encode($return_json);
	}
	
	/**
	 * TM 문자관리
	 * ajax . idx 값 유무에 따라서 TM 문자관리 insert , Modify 를 수행한다.
	 * @return string
	 */
	public function ajax_action_tm_sms_proc()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		$postVar['tm_id'] = $_SESSION['user_id'];
		
		if ($postVar['idx'])
		{
			$return_json['mode'] = 'modify';
			$db_content_result = $model->update_tm_sms($postVar);
		} else
		{
			$return_json['mode'] = 'insert';
			$db_content_result = $model->insert_tm_sms($postVar);
		}
		
		$return_json['result'] = 'true';
		$return_json['post'] = $db_content_result;
		$return_json['insert_id'] = $db_content_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_delete_tm_sms_proc()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		$postVar['tm_id'] = $_SESSION['user_id'];
		$postVar['idx'] = $postVar['delete_idx'];
		$db_content_result = $model->delete_tm_sms($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $db_content_result;
		$return_json['insert_id'] = $db_content_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
	
	
	/**
	 * 관리메뉴 > TM관리 <br />
	 * TM관리 메뉴 링크
	 * 
	 * @param 
	 * @return
	 */
	public function mTmManager()
	{
		// title 과 navigation을 설정한다.
		$data = array(
				'title' => 'TM관리',
				'nav' => array('관리메뉴' => '/main', 'TM관리' => ''),
		);
		
		$initVar['post'] = $this->request->getPost();
		$initVar['get'] = $this->request->getGet();
		$boardPage 	= new Ama_board($initVar);
		$model 		= new TmModel();
		$comp_model = new CompanyModel();
		
		$totalCount  = $model->list_tm_count($boardPage->getVal()); // count 값
		$tm_list = $model->list_tm($boardPage->getVal()); // 실제 리스트 값
		
		$compset_setData['comp_use'] = 'Y';
		$compset_list = $comp_model->list_tm_company($compset_setData);
		
		$searchVal = $boardPage->getVal();
		$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
		
		$data['view']['tm_list'] = $tm_list;
		$data['view']['compset_list'] = $compset_list;
		$data['view']['search_val'] = $searchVal;
		$data['view']['pager'] = $boardPage->getPager($totalCount);
		$this->viewPage('/manage/mTmManager',$data);
	}
	
	/**
	 * TM 관리
	 * ajax . 해당 TM 정보 가져오기
	 * @return string
	 */
	public function ajax_get_tmInfo()
	{
		$postVar = $this->request->getPost();
		$model = new TmModel();
		
		$db_content_result = $model->get_tm($postVar);
		
		$return_json['result'] = 'true';
		$return_json['db_content'] = $db_content_result[0];
		//$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		return json_encode($return_json);
	}
	
	/**
	 * TM 관리
	 * ajax . idx 값 유무에 따라서 TM insert , Modify 를 수행한다.
	 * @return string
	 */
	public function ajax_action_tm_proc()
	{
		$postVar = $this->request->getPost();
		
		$model = new TmModel();
		
		if ($postVar['idx'])
		{
			$return_json['mode'] = 'modify';
			$db_content_result = $model->update_tm($postVar);
		} else 
		{
			$return_json['mode'] = 'insert';
			$db_content_result = $model->insert_tm($postVar);
		}
		
		$return_json['result'] = 'true';
		$return_json['post'] = $db_content_result;
		$return_json['insert_id'] = $db_content_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	/**
	 * TM 관리
	 * ajax . TM 업체 설정을 저장한다.
	 * @return string
	 */
	public function ajax_action_tm_compset_proc()
	{
		$postVar = $this->request->getPost();
		
		$model = new TmModel();
		
		
		$postVar['update_compset_idx'] = ''; // 체크박스에 체크된 comp_idx 배열값 저장 변수
		$db_compset_delete = '';
		$db_compset_insert = '';
		
		// 이전에 저장되었던 데이터를 삭제 한다.
		$delArray['compset_tm_id'] = $postVar['compset_tm_id'];
		$db_compset_delete =  $model->delete_tmcompset($delArray);
		
		if ( isset($postVar['compset_idx']) )
		{
			// 배열을 , 구분으로 풀어서 문자화 한다.
			$postVar['update_compset_idx'] = implode(',', $postVar['compset_idx']);
			
			// compset_idx 를 이용하여 loop 만큼 insert 를 수행한다.
			$insArray['tm_id'] = $postVar['compset_tm_id'];
			foreach ($postVar['compset_idx'] as $r)
			{
				$insArray['comp_idx'] = $r;
				$db_compset_insert = $model->insert_tmcompset($insArray);
			}
		}
		
		$db_content_result = $model->update_tm_comp_idx($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post_del'] = $db_compset_delete;
		$return_json['post_insert'] = $db_compset_insert;
		$return_json['post'] = $db_content_result;
		return json_encode($return_json);
	}
	
	
	public function mSmsManager()
	{
		$data = array(
				'title' => '문자관리',
				'nav' => array('관리메뉴' => '/main', '문자관리' => ''),
		);
		
		$initVar['post'] = $this->request->getPost();
		$initVar['get'] = $this->request->getGet();
		$boardPage 	= new Ama_board($initVar);
		$model 		= new DbmanageModel();
		
		$totalCount  = $model->list_sms_count($boardPage->getVal());
		$sms_list = $model->list_sms($boardPage->getVal());
		
		$searchVal = $boardPage->getVal();
		$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
		
		if ( !isset($searchVal['cnm']) ) $searchVal['cnm'] = '';
		if ( !isset($searchVal['ecd']) ) $searchVal['ecd'] = '';
		
		
		$company_list = $model->get_company_list(array());
		$event_list = $model->get_event_list(array());
		
		$data['view']['company_list'] = $company_list;
		$data['view']['event_list'] = $event_list;
		$data['view']['sms_list'] = $sms_list;
		$data['view']['search_val'] = $searchVal;
		$data['view']['pager'] = $boardPage->getPager($totalCount);
		
		$this->viewPage('/manage/mSmsManager',$data);
	}
	
	public function ajax_update_phonelist()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		
		$dblist_result = $model->update_phonelist($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $dblist_result;
		$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		
		return json_encode($return_json);
		
	}
	
	public function ajax_update_use_sms()
	{
		$postVar = $this->request->getPost();
		$model = new DbmanageModel();
		
		$dblist_result = $model->update_use_sms($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $dblist_result;
		$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
	public function ajax_mSmslistInsertProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		
		$dblist_result = $model->insert_smslist($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $dblist_result;
		$return_json['insert_id'] = $dblist_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_smslist_deleteProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new DbmanageModel();
		$db_delete_result = $model->delete_smslist($postVar);
		
		$return_json['result'] = 'true';
		$return_json['db_content'] = $db_delete_result[0];
		
		return json_encode($return_json);
	}
	
	
	/**
	 * 최종 업데이트일 : 2021-04-24
	 * 작성자 : amatelas
	 * 관리메뉴 > 상태관리
	 * 디비의 상태값을 관리한다.
	 */
	public function mStatus()
	{
		$model = new GcodeModel();
		
		$view['scode_list'] = $model->get_scode(); // 진행상태
		$view['ncode_list'] = $model->get_ncode(); // 내원이유
		$view['gcode_list'] = $model->get_gcode(); // 업체구분
		
		$data = array(
				'title' => '코드관리',
				'nav' => array('관리메뉴' => '/main', '코드관리' => ''),
				'view' => $view,
		);
		
		$this->viewPage('/manage/mStatus',$data);
	}
	
	public function ajax_scode_delete()
	{
		$postVar = $this->request->getPost();
		
		$model = new GcodeModel();
		$scode_result = $model->delete_scode($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $scode_result;
		
		return json_encode($return_json);
	}
	
	public function ajax_ncode_delete()
	{
		$postVar = $this->request->getPost();
		
		$model = new GcodeModel();
		$ncode_result = $model->delete_ncode($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $ncode_result;
		
		return json_encode($return_json);
	}
	
	public function ajax_gcode_delete()
	{
		$postVar = $this->request->getPost();
		
		$model = new GcodeModel();
		$gcode_result = $model->delete_gcode($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $gcode_result;
		
		return json_encode($return_json);
	}
	
	
	
	
	public function ajax_scode_insert()
	{
		$postVar = $this->request->getPost();
		
		$model = new GcodeModel();
		$scode_result = $model->insert_scode($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $scode_result;
		
		return json_encode($return_json);
	}
	
	public function ajax_ncode_insert()
	{
		$postVar = $this->request->getPost();
		
		$model = new GcodeModel();
		$ncode_result = $model->insert_ncode($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $ncode_result;
		
		return json_encode($return_json);
	}
	
	public function ajax_gcode_insert()
	{
		$postVar = $this->request->getPost();
		
		$model = new GcodeModel();
		$gcode_result = $model->insert_gcode($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $gcode_result;
		
		return json_encode($return_json);
	}
	
	/**
	 * 최종 업데이트일 : 2024-04-27
	 * 작성자 : amatelas
	 * 관리메뉴 > 업체그룹 관리
	 * 업체그룹을 관리한다.
	 */
	public function mCompanyGroup()
	{
		$data = array(
				'title' => '상태관리',
				'nav' => array('관리메뉴' => '/main', '업체그룹 관리' => '', '리스트' => ''),
		);
		
		$initVar['post'] = $this->request->getPost();
		$initVar['get'] = $this->request->getGet();
		$boardPage 	= new Ama_board($initVar);
		$model 		= new CompanyModel();
		
		$totalCount  = $model->list_company_group_count($boardPage->getVal());
		$company_list = $model->list_company_group($boardPage->getVal());
		$company_list_del = $model->list_company_group_del($boardPage->getVal());
		
		$data['view']['company_group_list'] = $company_list;
		$data['view']['pager'] = $boardPage->getPager($totalCount);
		$data['view']['company_group_list_del'] = $company_list_del;
		
		
		$model = new GcodeModel();
		$data['view']['gcode_list'] = $model->get_gcode(); // 업체구분
		
		$this->viewPage('/manage/mCompanyGroup',$data);
	}
	
	public function mCompanyGroupInsert()
	{
		$data = array(
				'title' => '상태관리',
				'nav' => array('관리메뉴' => '/main', '업체그룹 관리' => '', '등록하기' => ''),
		);
		
		$this->viewPage('/manage/mCompanyGroupInsert',$data);
	}
	
	public function ajax_mCompanyGroupInsertProc()
	{
		$postVar = $this->request->getPost();
		
		if ( !isset($postVar['comp_group_use']))
		{
			$postVar['comp_group_use'] = 'N';
		}
		
		$model = new CompanyModel();
		$company_result = $model->insert_company_group($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $company_result;
		$return_json['insert_id'] = $company_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_mCompanyGroupDeleteProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$company_result = $model->update_company_group_delyn($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $company_result;
		$return_json['insert_id'] = $company_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function mCompanyGroupModify($idx='')
	{
		$data = array(
				'title' => '상태관리',
				'nav' => array('관리메뉴' => '/main', '업체그룹 관리' => '', '수정하기' => ''),
		);
		
		$setData['idx'] = $idx;
		
		$model = new CompanyModel();
		$company_result = $model->get_company_group($setData);
		$data['view']['data'] = $company_result;
		
		$company_list = $model->get_company_list();
		$data['view']['company_list'] = $company_list;
		
		$company_group_manage_list = $model->get_company_group_manage($setData);
		$data['view']['company_group_manage_list'] = $company_group_manage_list;
		
		
		$this->viewPage('/manage/mCompanyGroupModify',$data);
	}
	
	public function ajax_mCompanyGroupModifytProc()
	{
		$postVar = $this->request->getPost();
		
		if ( !isset($postVar['comp_group_use']))
		{
			$postVar['comp_group_use'] = 'N';
		}
		
		$model = new CompanyModel();
		$company_result = $model->update_group_company($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $company_result;
		
		$return_json['insert_id'] = $company_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
	public function ajax_mCompanyGroupDchk()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		
		$postVar2['dup_id'] = $postVar['comp_group_id'];
		$event_result = $model->id_dup_check($postVar2);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $event_result;
		//$return_json['insert_id'] = $event_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
	public function ajax_CompanyGroupManageInsertProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$event_result = $model->insert_company_group_manage($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $event_result;
		$return_json['insert_id'] = $event_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	public function ajax_CompanyGroupManageDeleteProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$event_result = $model->delete_company_group_manage($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $event_result;
		$return_json['insert_id'] = $event_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	/**
	 * 최종 업데이트일 : 2021-04-24
	 * 작성자 : amatelas
	 * 관리메뉴 > 업체&이벤트 관리
	 * 업체를 관리한다.
	 */
	public function mCompany()
	 {
		$data = array(
				'title' => '상태관리',
				'nav' => array('관리메뉴' => '/main', '업체&이벤트 관리' => '', '리스트' => ''),
		);
		
		$initVar['post'] = $this->request->getPost();
		$initVar['get'] = $this->request->getGet();
		$boardPage 	= new Ama_board($initVar);
		$model 		= new CompanyModel();
		
		$totalCount  = $model->list_company_count($boardPage->getVal());
		$company_list = $model->list_company($boardPage->getVal());
		$company_list_del = $model->list_company_del($boardPage->getVal());
		
		$data['view']['company_list'] = $company_list;
		$data['view']['pager'] = $boardPage->getPager($totalCount);
		$data['view']['company_list_del'] = $company_list_del;
		
		
		$model = new GcodeModel();
		$data['view']['gcode_list'] = $model->get_gcode(); // 업체구분
		
		$this->viewPage('/manage/mCompany',$data);
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
		$event_list_del = $model->list_event_del(array('comp_idx'=>$idx));
		
		$data['view']['event_list'] = $event_list;
		$data['view']['event_list_del'] = $event_list_del;
		
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
	
	public function ajax_mCompanyDeleteProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$company_result = $model->update_company_delyn($postVar);
		
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
	
	
	public function ajax_mEventDeleteProc()
	{
		$postVar = $this->request->getPost();
		
		$model = new CompanyModel();
		$event_result = $model->update_event_delyn($postVar);
		
		$return_json['result'] = 'true';
		$return_json['post'] = $event_result;
		$return_json['insert_id'] = $event_result[0]->connID->insert_id;
		
		return json_encode($return_json);
	}
	
	
	
	
	public function hmkSms()
	{
		echo 'test sms ';
		
		$initVar['event_name'] = '이벤트이름1';
		$initVar['revice_phone'] = '01032308007,01096468007';
		$initVar['send_phone'] = '01045589685';
		$sms = new Ama_sms($initVar);
		$sms->ttt();
		
		exit();
		
		
		
		$_POST['action'] = 'go';
		$_POST['msg'] = '테스트 메세지입니다.'; // 전송 메세지 
		$_POST['subject'] = ''; // LMS일 경우에 사용함
		$_POST['rphone'] = '010-3230-8007'; // 받는사람 전화번호
		$_POST['sphone1'] = '010'; // 발신번호 앞자리
		$_POST['sphone2'] = '4558'; // 발신번호 중간자리
		$_POST['sphone3'] = '9685'; // 발신번호 뒷자리
		$_POST['rdate'] = ''; // 예약날짜
		$_POST['rtime'] = ''; // 예약시간
		$_POST['returnurl'] = 'http://www2.amatelas.com/manage/mSmsManager'; // 전송 후 이동할 페이지 http://를 붙여야함.
		$_POST['testflag'] = ''; // 테스트 일경우 Y
		$_POST['destination'] = ''; // 메세지에 받는 사람 이름을 넣고 싶을때 이용
		$_POST['repeatFlag'] = ''; // 반복설정 Y 일경우
		$_POST['repeatNum'] = ''; // 반복 횟수
		$_POST['repeatTime'] = ''; // 반복 시간
		$_POST['smsType'] = 'S'; // S 는 sms L 은 LMS
		$_POST['nointeractive'] = '1'; // 1을 설정할 경우 alert창 사용안함
		
		
		
		
		
		if($_POST['action']=='go'){
			
			/******************** 인증정보 ********************/
			$sms_url = 'https://sslsms.cafe24.com/sms_sender.php'; // HTTPS 전송요청 URL
			//$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
			$sms['user_id'] = base64_encode('jin7ho77'); //SMS 아이디.
			$sms['secure'] = base64_encode('2b09f12d9ba3510c71468c7007429eb7') ;//인증키
			$sms['msg'] = base64_encode(stripslashes($_POST['msg']));
			if( $_POST['smsType'] == 'L'){
				$sms['subject'] =  base64_encode($_POST['subject']);
			}
			$sms['rphone'] = base64_encode($_POST['rphone']);
			$sms['sphone1'] = base64_encode($_POST['sphone1']);
			$sms['sphone2'] = base64_encode($_POST['sphone2']);
			$sms['sphone3'] = base64_encode($_POST['sphone3']);
			$sms['rdate'] = base64_encode($_POST['rdate']);
			$sms['rtime'] = base64_encode($_POST['rtime']);
			$sms['mode'] = base64_encode('1'); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
			$sms['returnurl'] = base64_encode($_POST['returnurl']);
			$sms['testflag'] = base64_encode($_POST['testflag']);
			$sms['destination'] = strtr(base64_encode($_POST['destination']), '+/=', '-,');
			$returnurl = $_POST['returnurl'];
			$sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);
			$sms['repeatNum'] = base64_encode($_POST['repeatNum']);
			$sms['repeatTime'] = base64_encode($_POST['repeatTime']);
			$sms['smsType'] = base64_encode($_POST['smsType']); // LMS일경우 L
			$nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략
			
			$host_info = explode('/', $sms_url);
			
			
			$host = $host_info[2];
			//$path = $host_info[3]."/".$host_info[4];
			$path = '/' .$host_info[3];
			
			srand((double)microtime()*1000000);
			$boundary = '---------------------' .substr(md5(rand(0,32000)),0,10);
			//print_r($sms);
			
			// 헤더 생성
			$header = 'POST /' .$path ." HTTP/1.0\r\n";
			$header .= 'Host: ' .$host."\r\n";
			$header .= 'Content-type: multipart/form-data, boundary=' .$boundary."\r\n";
			
			$data = '';
			// 본문 생성
			foreach($sms AS $index => $value){
				$data .="--$boundary\r\n";
				$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
				$data .= "\r\n".$value."\r\n";
				$data .="--$boundary\r\n";
			}
			$header .= 'Content-length: ' . strlen($data) . "\r\n\r\n";
			
			$fp = fsockopen($host, 80);
			
			if ($fp) {
				fputs($fp, $header.$data);
				$rsp = '';
				while(!feof($fp)) {
					$rsp .= fgets($fp,8192);
				}
				fclose($fp);
				$msg = explode("\r\n\r\n",trim($rsp));
				$rMsg = explode(',', $msg[1]);
				$Result= $rMsg[0]; //발송결과
				$Count= $rMsg[1]; //잔여건수
				
				//발송결과 알림
				if($Result== 'success') {
					$alert = '성공';
					$alert .= ' 잔여건수는 ' .$Count. '건 입니다.';
				}
				else if($Result== 'reserved') {
					$alert = '성공적으로 예약되었습니다.';
					$alert .= ' 잔여건수는 ' .$Count. '건 입니다.';
				}
				else if($Result== '3205') {
					$alert = '잘못된 번호형식입니다.';
				}
				
				else if($Result== '0044') {
					$alert = '스팸문자는발송되지 않습니다.';
				}
				
				else {
					$alert = '[Error]' .$Result;
				}
			}
			else {
				$alert = 'Connection Failed';
			}
			
			if($nointeractive== '1' && ($Result!= 'success' && $Result!= 'Test Success!' && $Result!= 'reserved') ) {
				echo "<script>alert('".$alert ."')</script>";
			}
			else if($nointeractive!= '1') {
				echo "<script>alert('".$alert ."')</script>";
			}
			echo "<script>location.href='".$returnurl."';</script>";
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}