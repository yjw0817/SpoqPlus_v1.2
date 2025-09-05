<?php
namespace App\Libraries;


use App\Models\CalendarModel;
use CodeIgniter\I18n\Time;
use App\Models\MemModel;

class Ama_calendar {
	
	private $calMode;
	private $calPost;
	
	private $gx_room_mgmt_sno;
	
	private $comp_cd;
	private $bcoff_cd;
	
	public function __construct($calArr) {
		$this->calPost = $calArr;
		$this->calMode = $calArr['cal_type'];
		$this->gx_room_mgmt_sno = $calArr['gx_room_mgmt_sno'];
		
		$this->comp_cd = $_SESSION['comp_cd'];
		$this->bcoff_cd = $_SESSION['bcoff_cd'];
	}
	
	/**
	 * 
	 * @param array $postVar
	 */
	public function getCalendar()
	{
		switch($this->calMode)
		{
			case 'get' :
				return $this->get_calendar();				
			break;
			case 'create' :
				return $this->create_calendar();
			break;
			case 'update' :
				return $this->update_calendar();
			break;
		}
		
		
	}
	
	/**
	 * calendar 의 데이터를 읽어서 json으로 리턴한다.
	 */
	private function get_calendar()
	{
		$model = new CalendarModel();
		$postVar = $this->calPost;
		
		// 디버그 로그: 요청 데이터
		error_log("📅 Ama_calendar::get_calendar() 시작 - POST 데이터: " . json_encode($postVar));
		
		$sdate = '';
		$edate = '';
		
		// 프론트엔드에서 전달한 날짜 범위를 우선 사용
		if (isset($postVar['start_date']) && isset($postVar['end_date'])) {
			$sdate = $postVar['start_date'];
			$edate = $postVar['end_date'];
			error_log("📊 프론트엔드 날짜 범위 사용: {$sdate} ~ {$edate}");
		} else {
			// 기존 방식 - ndate 기준으로 계산
			$ndate = '';
			if (isset($postVar['ndate'])) {
			$ndate = $postVar['ndate'];
		}
		
			if ($ndate == '') $ndate = date('Y-m') . '-01';
		
		$sdate = date('Y-m-d', strtotime($ndate . ' -7 days'));
		$edate = date('Y-m-d', strtotime($ndate . ' +1 months'));
		$edate = date('Y-m-d', strtotime($edate . ' -1 days'));
			error_log("📊 계산된 날짜 범위 (ndate: {$ndate}): {$sdate} ~ {$edate}");
		}
		
		$setData = array(
		    'gx_room_mgmt_sno' 	=> $this->gx_room_mgmt_sno
				,'start_date' 	=> $sdate
				,'end_date'		=> $edate
				,'comp_cd'	    => $this->comp_cd
		        ,'bcoff_cd'	    => $this->bcoff_cd
		);
		
		error_log("🔍 Ama_calendar::get_calendar() 모델 호출 데이터: " . json_encode($setData));
		
		$get_calendar = $model->get_calendar($setData);
		
		error_log("✅ Ama_calendar::get_calendar() 결과: " . count($get_calendar) . "개 이벤트");
		
		return json_encode($get_calendar);
	}
	
	
	/**
	 * external event에서 dreg 했을때 새롭게 이벤트를 생성한다.
	 */
	private function create_calendar()
	{
	    $nn_now = new Time('now');
		$model = new CalendarModel();
		$postVar = $this->calPost;
		
		// 그룹수업 아이템 정보 조회 및 기본값 설정
		$gx_class_min = 60; // 기본값 60분
		$gx_item_detail = null;
		
		if (isset($postVar['gx_item_sno']) && !empty($postVar['gx_item_sno'])) {
			$itemData = array(
				'comp_cd' => $this->comp_cd,
				'bcoff_cd' => $this->bcoff_cd,
				'gx_item_sno' => $postVar['gx_item_sno']
			);
			$gx_item_info = $model->get_gx_item_detail($itemData);
			if (!empty($gx_item_info)) {
				$gx_item_detail = $gx_item_info[0];
				if (isset($gx_item_detail['GX_CLASS_MIN']) && $gx_item_detail['GX_CLASS_MIN'] > 0) {
					$gx_class_min = $gx_item_detail['GX_CLASS_MIN'];
				}
			}
		}
		
		if ( isset($postVar['start']) )
		{
			$split_start = explode('T', $postVar['start']);
			if ( isset($split_start[0]) ) {
				$insVar['start_date'] = $split_start[0];
				$insVar['end_date'] = $split_start[0];
			} else {
				$insVar['start_date'] = '';
			}
			if ( isset($split_start[1]) ) {
				$clean_start_time = substr($split_start[1], 0, 8);
				$insVar['start_time'] = $clean_start_time;
				$insVar['end_time'] = date('H:i:s', strtotime($clean_start_time . ' +' . $gx_class_min . ' minutes'));
			} else {
				$insVar['start_time'] = '';
			}
		}
		
		// 기본 스케줄 정보
		$insVar['title']  = $postVar['title'];
		$insVar['scolor'] = $postVar['scolor'];
		$insVar['gx_room_mgmt_sno'] = $this->gx_room_mgmt_sno;
		$insVar['comp_cd'] = $this->comp_cd;
		$insVar['bcoff_cd'] = $this->bcoff_cd;
		$insVar['gx_item_sno'] = $postVar['gx_item_sno'] ?? null;
		$insVar['gx_stchr_id'] = $postVar['tid'];
		// 요일 설정 (0=일, 1=월, ... 6=토)
		if (isset($insVar['start_date'])) {
			$dayOfWeek = date('w', strtotime($insVar['start_date']));
			$insVar['gx_clas_dotw'] = $dayOfWeek;
		} else {
			$insVar['gx_clas_dotw'] = "";
		}
		
		// 필수 필드 추가
		$insVar['gx_class_min'] = $gx_class_min;
		$insVar['cre_id'] = $_SESSION['user_id'];
		$insVar['cre_datetm'] = $nn_now;
		$insVar['mod_id'] = $_SESSION['user_id'];
		$insVar['mod_datetm'] = $nn_now;
		
		// 그룹수업 아이템 상세 정보 추가
		if ($gx_item_detail) {
			$insVar['gx_deduct_cnt'] = $gx_item_detail['GX_DEDUCT_CNT'] ?? 1;
			$insVar['gx_max_num'] = $gx_item_detail['GX_MAX_NUM'] ?? 10;
			$insVar['gx_max_waiting'] = $gx_item_detail['GX_MAX_WAITING'] ?? 5;
			$insVar['auto_show_yn'] = $gx_item_detail['AUTO_SHOW_YN'] ?? 'N';
			$insVar['auto_show_d'] = $gx_item_detail['AUTO_SHOW_D'] ?? 0;
			$insVar['auto_show_unit'] = $gx_item_detail['AUTO_SHOW_UNIT'] ?? '1';
			$insVar['auto_show_week'] = $gx_item_detail['AUTO_SHOW_WEEK'] ?? '';
			$insVar['auto_show_week_dur'] = $gx_item_detail['AUTO_SHOW_WEEK_DUR'] ?? 1;
			$insVar['auto_show_time'] = $gx_item_detail['AUTO_SHOW_TIME'] ?? '09:00:00';
			$insVar['auto_close_yn'] = $gx_item_detail['AUTO_CLOSE_YN'] ?? 'N';
			$insVar['auto_close_min'] = $gx_item_detail['AUTO_CLOSE_MIN'] ?? '00:30:00';
			$insVar['auto_close_min_num'] = $gx_item_detail['AUTO_CLOSE_MIN_NUM'] ?? 1;
			$insVar['reserv_d'] = $gx_item_detail['RESERV_D'] ?? 1;
			$insVar['use_reserv_yn'] = $gx_item_detail['USE_RESERV_YN'] ?? 'Y';
			$insVar['pay_for_zero_yn'] = $gx_item_detail['PAY_FOR_ZERO_YN'] ?? 'N';
			$insVar['use_pay_rate_yn'] = $gx_item_detail['USE_PAY_RATE_YN'] ?? 'N';
			$insVar['selected_image_id'] = $gx_item_detail['SELECTED_IMAGE_ID'] ?? null;
		} else {
			// 아이템 정보가 없는 경우 기본값 설정
			$insVar['gx_deduct_cnt'] = 1;
			$insVar['gx_max_num'] = 10;
			$insVar['gx_max_waiting'] = 5;
			$insVar['auto_show_yn'] = 'N';
			$insVar['auto_show_d'] = 0;
			$insVar['auto_show_unit'] = '1';
			$insVar['auto_show_week'] = '';
			$insVar['auto_show_week_dur'] = 1;
			$insVar['auto_show_time'] = '09:00:00';
			$insVar['auto_close_yn'] = 'N';
			$insVar['auto_close_min'] = '00:30:00';
			$insVar['auto_close_min_num'] = 1;
			$insVar['reserv_d'] = 1;
			$insVar['use_reserv_yn'] = 'Y';
			$insVar['pay_for_zero_yn'] = 'N';
			$insVar['use_pay_rate_yn'] = 'N';
			$insVar['selected_image_id'] = null;
		}
		
		// 강사 정보 조회
		$memModel = new MemModel();
		$mData['comp_cd'] = $this->comp_cd;
		$mData['bcoff_cd'] = $this->bcoff_cd;
		$mData['mem_id'] = $postVar['tid'];
		$get_tchr_info = $memModel->get_mem_info_id_idname($mData);
		
		// 강사 정보가 있는지 확인
		if (!empty($get_tchr_info) && isset($get_tchr_info[0])) {
			$insVar['gx_stchr_sno'] = $get_tchr_info[0]['MEM_SNO'];
			$insVar['gx_stchr_nm'] = $get_tchr_info[0]['MEM_NM'];
		} else {
			// 강사 정보가 없는 경우 기본값 설정
			$insVar['gx_stchr_sno'] = 0;
			$insVar['gx_stchr_nm'] = '미지정';
			error_log('⚠️ 강사 정보를 찾을 수 없음. tid: ' . $postVar['tid']);
		}
		
		// 로그 출력
		error_log('📝 Create Calendar - 그룹수업 아이템 정보 포함: ' . json_encode([
			'gx_item_sno' => $postVar['gx_item_sno'] ?? 'null',
			'gx_class_min' => $insVar['gx_class_min'],
			'gx_max_num' => $insVar['gx_max_num'],
			'auto_show_yn' => $insVar['auto_show_yn'],
			'auto_close_min' => $insVar['auto_close_min'],
			'use_pay_rate_yn' => $insVar['use_pay_rate_yn'],
			'selected_image_id' => $insVar['selected_image_id'],
			'will_copy_related_data' => isset($postVar['gx_item_sno']) && !empty($postVar['gx_item_sno'])
		]));
		
		// 메인 스케줄 데이터 삽입
		$rValue = $model->insert_calendar($insVar);
		$insert_id = $rValue['new_gx_schd_mgmt_sno'] ?? $rValue[0]->connID->insert_id;
		$returnValue['insert_id'] = $insert_id;
		
		// 연관 데이터 복사 (아이템 정보가 있는 경우만)
		if (isset($postVar['gx_item_sno']) && !empty($postVar['gx_item_sno'])) {
			$copyData = array(
				'gx_schd_mgmt_sno' => $insert_id,
				'gx_item_sno' => $postVar['gx_item_sno'],
				'cre_id' => $_SESSION['user_id'],
				'cre_datetm' => $nn_now
			);
			
			// 1. 참석 가능한 이용권 복사
			try {
				$model->copy_item_events_to_schedule($copyData);
				error_log('✅ 참석 가능한 이용권 복사 완료: 아이템 ' . $postVar['gx_item_sno'] . ' → 스케줄 ' . $insert_id);
			} catch (Exception $e) {
				error_log('❌ 참석 가능한 이용권 복사 실패: ' . $e->getMessage());
				// 테이블이 없는 경우 경고 메시지 추가
				if (strpos($e->getMessage(), "doesn't exist") !== false) {
					error_log('⚠️ gx_schd_event_tbl 테이블이 존재하지 않습니다. 마이그레이션을 실행해주세요.');
				}
			}
			
			// 2. 수당 요율표 복사
			try {
				$model->copy_item_pay_to_schedule($copyData);
				error_log('✅ 수당 요율표 복사 완료: 아이템 ' . $postVar['gx_item_sno'] . ' → 스케줄 ' . $insert_id);
			} catch (Exception $e) {
				error_log('❌ 수당 요율표 복사 실패: ' . $e->getMessage());
				// 테이블이 없는 경우 경고 메시지 추가
				if (strpos($e->getMessage(), "doesn't exist") !== false) {
					error_log('⚠️ gx_schd_pay_tbl 테이블이 존재하지 않습니다. 마이그레이션을 실행해주세요.');
				}
			}
			
			$returnValue['copied_related_data'] = true;
			$returnValue['copied_from_item'] = $postVar['gx_item_sno'];
		} else {
			$returnValue['copied_related_data'] = false;
			error_log('ℹ️ 아이템 정보가 없어서 연관 데이터 복사를 건너뜁니다.');
		}
		
		return json_encode($returnValue);
	}
	
	
	
	private function update_calendar()
	{
		$model = new CalendarModel();
		$postVar = $this->calPost;
		
		$upVar['start_date'] = '';
		$upVar['start_time'] = '';
		$upVar['end_date']	= '';
		$upVar['end_time']	= '';
		
		if ( isset($postVar['start']) )
		{
			$split_start = explode('T', $postVar['start']);
			
			if ( isset($split_start[0]) ) {
				$upVar['start_date'] = $split_start[0];
			} else
			{
				$upVar['start_date'] = '';
			}
			
			if ( isset($split_start[1]) ) {
				$clean_start_time = substr($split_start[1], 0, 8);
				$upVar['start_time'] = $clean_start_time;
			} else
			{
				$upVar['start_time'] = '';
			}
		}
		
		if ( isset($postVar['end']) )
		{
			$split_end = explode('T', $postVar['end']);
			
			if ( isset($split_end[0]) ) {
				$upVar['end_date'] = $split_end[0];
			} else
			{
				$upVar['end_date'] = '';
			}
			
			if ( isset($split_end[1]) ) {
				$clean_end_time = substr($split_end[1], 0, 8);
				$upVar['end_time'] = $clean_end_time;
			} else
			{
				$upVar['end_time'] = '';
			}
		}
		
		$upVar['gx_schd_mgmt_sno'] = $postVar['id'];
		$upVar['gx_room_mgmt_sno'] = $this->gx_room_mgmt_sno;
		
		$model->update_calendar($upVar);
		
		$returnValue['result'] = true;
		
		return json_encode($returnValue);
	}
}