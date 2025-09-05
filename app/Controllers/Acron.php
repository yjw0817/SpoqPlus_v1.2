<?php
namespace App\Controllers;

use App\Models\DbmanageModel;
use App\Models\TmModel;
use App\Models\UserModel;

require_once APPPATH .  '/ThirdParty/google-api-php-client--PHP7.4/vendor/autoload.php';

class Acron extends BaseController
{
	
	public function dbtm()
	{
		$model = new UserModel();
		
		echo date('Ymd');
		echo '<br />';
		echo date('Hi');
		echo '<br />';
		
		$bu_days = '14'; // 임시 설정
		$bu_update = date('Ymd', strtotime( date('Ymd') . " -{$bu_days} days"));
		
		echo $bu_update;
		echo '<br />';
		$sql = "SELECT idx FROM dblist_tb WHERE user_status = 'a1' AND delyn = 'N' AND bu_update > '20230430' AND bu_update < '{$bu_update}'";
		echo $sql;
		
	}
	
	public function cron_chk_func()
	{
		$model 		= new TmModel();
		$dbmodel 		= new DbmanageModel();
		$putVar['ctype'] = 'db_to_tm_auto_bae';
		$get_auto_time_info = $model->get_auto_time_info($putVar);
		
		$now_time = date('Hi');
		
		$insVar['cron_type'] = 'db_to_tm_auto_bae';
		$insVar['uptime'] = date('YmdHis');
		
		if ($get_auto_time_info[0]['h_'.$now_time] == 'Y'):
			$insVar['cron_yn'] = 'Y';
			$model->ins_cronchk($insVar);
			//$this->auto_bae_tm();
		else:
			$insVar['cron_yn'] = 'N';
			$model->ins_cronchk($insVar);
		endif;
		
		// 부재디비 -> 영구부재디비 설정
		if ($now_time == '0100')
		{
			$bu_day_auto_set = $dbmodel->get_bu_day_auto_set();
			if ($bu_day_auto_set[0]['use_yn'] == 'Y')
			{
				$this->auto_a15_bu_proc();
				$insVar2['cron_type'] = 'bu_day_auto_set';
				$insVar2['uptime'] = date('YmdHis');
				$insVar2['cron_yn'] = 'Y';
				$model->ins_cronchk($insVar2);
			}
		}
	}
	
	/**
	 * 2023-05-23 추가
	 * 부재디비중에 일자에 맞게 영구부재로 자동으로 처리함
	 */
	private function auto_a15_bu_proc()
	{
		$model 		= new DbmanageModel();
		
		// 날짜를 계산해야한다.
		// cronset_tb 테이블에서 ctype = 'bu_day_auto_set'인 곳에서 bu_days 필드를 읽는다.
		// bu_days 날짜를 현재 날짜에서 뺀값을 $bu_update 로 셋팅한다.
		
		$bu_day_auto_set = $model->get_bu_day_auto_set();
		
		$bu_days = '30'; // 임시 설정
		
		if (count($bu_day_auto_set) > 0 )
		{
			$bu_days = $bu_day_auto_set[0]['bu_days'];
		}
		
		$bu_update = date('Ymd', strtotime( date('Ymd') . " -{$bu_days} days"));
		
		$bu_list = $model->get_budb_a15_auto($bu_update);
		
		foreach ($bu_list as $r) :
			$ygdVar['user_status'] = 'a15';
			$ygdVar['idx'] = $r['idx'];
			$model->update_budb_a15($ygdVar);
			$this->ygd_history($ygdVar);
		endforeach;
		
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
		$db_content .= "\n[" . $st_name . '] ' . $this->cron_disp_date($r['uptime']) . ' - ' . $r['tm_name'];
		
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
	
	
	public function auto_bae_tm()
	{
		$model 		= new DbmanageModel();
		
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
		
		$upVar['idx'] = $dd['idx'];
		$upVar['tm_id'] = $dd['set_tm_id'];
		
		$model->db_tm_id_update($upVar);
		
		endif;
		
		endforeach;
		
		
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
	
	private function cron_disp_date($var)
	{
		$returnVal = '';
		if ($var) :
		
		if (strlen($var) == 8):
		$splitVar['yy'] = substr($var,0,4);
		$splitVar['mm'] = substr($var,4,2);
		$splitVar['dd'] = substr($var,6,2);
		
		$returnVal = $splitVar['yy'] . '-' . $splitVar['mm'] . '-' . $splitVar['dd'];
		else:
		$splitVar['yy'] = substr($var,0,4);
		$splitVar['mm'] = substr($var,4,2);
		$splitVar['dd'] = substr($var,6,2);
		$splitVar['hh'] = substr($var,8,2);
		$splitVar['ii'] = substr($var,10,2);
		$splitVar['ss'] = substr($var,12,2);
		
		$returnVal = $splitVar['yy'] . '-' . $splitVar['mm'] . '-' . $splitVar['dd'] . ' ' . $splitVar['hh'] . ':' . $splitVar['ii'] . ':' . $splitVar['ss'];
		endif;
		
		endif;
		
		return $returnVal;
	}
	
	
	
	
	
	/**
	 * 이메일와 암호를 직접 입력하고 로그인을 했을때의 처리 부분
	 */
	public function loginAction()
	{
		$session = session();
		$postVar = $this->request->getPost();
		$model = new UserModel();
		
		$putVar['login_email'] = $postVar['login_email'];
		$putVar['login_password'] = $postVar['login_password'];
		
		//$get_user = $model->user_login_chk($putVar);
		$comp_user = $model->user_login_chk($putVar);
		$comp_idx = '';
		$login_type = '';
		if ( count($comp_user) == 1):
			$get_user = $comp_user;
			$comp_idx = $get_user[0]['idx'];
			$login_type = 'comp';
		else :
			$tm_user = $model->tm_user_login_chk($putVar);
			$get_user = $tm_user;
			$comp_idx = $get_user[0]['comp_idx'];
			$login_type = 'tm';
		endif;
		
		if ( count($get_user) == 1 ):
		
			if ($putVar['login_email'] == 'healingmk'):
				$sesMake['session_admin'] = 'Y';
			else:
				$sesMake['session_admin'] = 'N';
			endif;
		
			$sesMake['session_id'] = $get_user[0]['idx'];
			$sesMake['user_name'] = $get_user[0]['user_name'];
			$sesMake['user_id'] = $get_user[0]['user_id'];
			$sesMake['comp_idx'] = $comp_idx;
			$sesMake['login_type'] = $login_type;
			$session->set($sesMake);
			
			//$reUrl = '/main';
			
			$reUrl = '/dbmanage/mDblist';
			echo "<script>location.href='" . $reUrl . "';</script>";
			exit();
		else :
			echo "<script>alert('check you`re id or password');</script>";
			$reUrl = '/login';
			echo "<script>location.href='" . $reUrl . "';</script>";
			exit();
			
		endif;
		
		
		
	}
	
	public function php_ttt()
	{
		phpinfo();
	}
	
	
	
}