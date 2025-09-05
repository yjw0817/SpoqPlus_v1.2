<?php
namespace App\Controllers;

use App\Libraries\Ama_sms;
use App\Models\LandingModel;

class Landing extends BaseController
{
	public function landingCreate()
	{
		$agent = $this->request->getUserAgent();
			
		$model = new LandingModel();
		/**
		 * jsonp 방식으로 전달 받기 때문에
		 * get 방식으로 밖에 받을 수 없다.
		 * event_idx, tel, name, nddes, age(?)
		 */
		$postVar = $this->request->getGet();
		
		
		//정상 이벤트 인지를 검사한다.
		$data['idx'] = $postVar['event_idx'];
		$chk_event = $model->chk_event($data);
		
		if ( count($chk_event) > 0 ):
		
			// 1분안에 다시 똑같은 이벤트를 클릭하게 되면 입력을 방지 해야한다.
			$chk_dup_data['company_idx'] = $chk_event[0]['comp_idx'];
			$chk_dup_data['event_idx'] = $chk_event[0]['idx'];
			$chk_dup_data['user_phone'] = str_replace('-','',$postVar['tel']);
			$chk_dup_dblist = $model->chk_dup_dblist($chk_dup_data);
			
			$pre_uptime_chk = true;
			
			if ( count($chk_dup_dblist) > 0):
				$pre_uptime = date('YmdHis', strtotime( $chk_dup_dblist[0]['uptime'] . ' +1 minutes'));
				if ($pre_uptime < date('YmdHis')):
					$pre_uptime_chk = true;
				else:
					$pre_uptime_chk = false;
				endif;
			endif ;
			
			if ($pre_uptime_chk == true):
				$insData['user_name'] = $postVar['name'];
				$insData['user_phone'] = str_replace('-','',$postVar['tel']);
				$insData['needs'] = $postVar['needs'];
				$insData['company_idx'] = $chk_event[0]['comp_idx'];
				$insData['event_idx'] = $chk_event[0]['idx'];
				$insData['uptime'] = date('YmdHis');
				
				$dup_phone['user_phone'] = str_replace('-','',$postVar['tel']);
				$chk_dup_dblist_a4 = $model->chk_dup_dblist_a4($dup_phone);
				
				if ( $chk_dup_dblist_a4[0]['counter'] > 0):
					$insData['user_status'] = 'a14';
				else :
					$insData['user_status'] = '';
				endif;
				
				$ins_dblist = $model->insert_ex_dblist($insData);
				
				$insert_id = $ins_dblist[0]->connID->insert_id;
				
				if ($insert_id) :
					$sms_check = $model->sms_check($insData);
					if ( count($sms_check) > 0 ):
						if ( $sms_check[0]['phone_list'] !='' ):
							$initVar['event_name'] = $sms_check[0]['event_name'] . ' DB 유입';
							$initVar['revice_phone'] = $sms_check[0]['phone_list'];
							$initVar['send_phone'] = '01045589685';
							$sms = new Ama_sms($initVar);
							$sms->sendSms();
							
							$return_array['result'] = 'true';
							$return_array['msg'] ='신청이 완료 되었습니다.';
							return 'callback_ajax_landing(' . json_encode($return_array) . ')';
						else:
							$return_array['result'] = 'true';
							$return_array['msg'] ='신청이 완료 되었습니다.';
						endif;
					else:
						$return_array['result'] = 'true';
						$return_array['msg'] ='신청이 완료 되었습니다.';
					endif;
				else:
					$return_array['result'] = 'false';
					$return_array['msg'] = '이벤트 신청에 실패 하였습니다.';
				endif;
			else :
				$return_array['result'] = 'false';
				$return_array['msg'] = '이미 신청 하였습니다.';
			endif;
			
			
		else:
			$return_array['result'] = 'false';
			$return_array['msg'] = '정상적인 이벤트가 아닙니다.';
		endif;
		
		
 		$return_array['ref'] = $agent->getReferrer();
// 		$return_array['user_name'] = $postVar['tel'];
// 		$return_array['user_phone'] = $postVar['name'];
// 		$return_array['needs'] = $postVar['needs'];
 		
		return 'callback_ajax_landing(' . json_encode($return_array) . ')';
	}
	
}