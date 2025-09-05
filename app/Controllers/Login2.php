<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Router\Exceptions\RedirectException;
use Exception;
use Google_Client;
use Google_Service_Oauth2;

require_once APPPATH .  '/ThirdParty/google-api-php-client--PHP7.4/vendor/autoload.php';

class Login2 extends BaseController
{
	
	public function logout()
	{
		session_destroy();
		
		echo "<script>alert('LOGOUT');</script>";
		$reUrl = '/login';
		echo "<script>location.href='" . $reUrl . "';</script>";
		exit();
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
		
		// 2024-04-27 수정 [시작]
		$comp_idx = '';
		$login_type = '';
		$login_group_idxs = '';
		
		$comp_group_user = $model->cg_user_login_chk($putVar);
		
		if ( count($comp_group_user) == 1):
			$get_user = $comp_group_user;
			$comp_idx = $get_user[0]['idx'];
			$sms_send_phone = '';
			$login_type = 'comp';
			
			$get_group_idxs = $model->cg_idxs($get_user[0]);
			
			$pp_array = array();
			
			foreach($get_group_idxs AS $ii):
				array_push($pp_array,$ii['comp_idx']);
			endforeach;
			
			$login_group_idxs = implode(',', $pp_array);
			
		else :
		
			$comp_user = $model->user_login_chk($putVar);
			if ( count($comp_user) == 1):
				$get_user = $comp_user;
				$comp_idx = $get_user[0]['idx'];
				$sms_send_phone = '';
				$login_type = 'comp';
			else :
				$tm_user = $model->tm_user_login_chk($putVar);
				$get_user = $tm_user;
				$comp_idx = $get_user[0]['comp_idx'];
				$sms_send_phone = $get_user[0]['tm_phone'];
				$login_type = 'tm';
			endif;
		
		endif;
		
		// 2024-04-27 수정 [끝]
		
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
			$sesMake['sms_send_phone'] = $sms_send_phone;
			$sesMake['login_type'] = $login_type;
			$sesMake['login_group_idxs'] = $login_group_idxs; // 2024-04-27
			
			
			if ($login_type == 'tm'):
				
				$setTmAutoSet['tm_id'] = $get_user[0]['user_id'];
				$setTmAutoSet['bae_yn'] = 'Y';
				$setTmAutoSet['bae_date'] = date('Ymd');
			
				$get_tmautoset_status = $tmmodel->get_tmautoset_status($setTmAutoSet);
			
				if ( count($get_tmautoset_status) == 0 ):
					$sesMake['tm_bae_set'] = 'N';
				else:
					$sesMake['tm_bae_set'] = 'Y';
				endif;
			
				$sesMake['ses_hf_01'] = $get_user[0]['hf_01'];
				$sesMake['ses_hf_02'] = $get_user[0]['hf_02'];
				$sesMake['ses_hf_03'] = $get_user[0]['hf_03'];
				$sesMake['ses_hf_04'] = $get_user[0]['hf_04'];
				$sesMake['ses_hf_05'] = $get_user[0]['hf_05'];
				$sesMake['ses_hf_06'] = $get_user[0]['hf_06'];
			else:
				$sesMake['tm_bae_set'] = 'N';
				$sesMake['ses_hf_01'] = 'N';
				$sesMake['ses_hf_02'] = 'N';
				$sesMake['ses_hf_03'] = 'N';
				$sesMake['ses_hf_04'] = 'N';
				$sesMake['ses_hf_05'] = 'N';
				$sesMake['ses_hf_06'] = 'N';
			endif;
			
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
	
	
	/**
	 * 로그인 페이지. 구글 인증 이후에 분기
	 * @throws RedirectException : reflash를 수행할때 발생
	 */
	public function index()
	{
		// AIzaSyDecX4zNtTcn3xrGDHauiyWpF-I1H0_iik    : API KEy
		$this->setprotectKey();
		
		$clientID 		= '671425042458-7lucpbq6u9o6eegf0v2sc111j26500vl.apps.googleusercontent.com';
		$clientsecret 	= 'nLRuxfU0P8vYyGR_XQElZAGt';
		$redirectUri 	= 'http://localhost/login';
		
		$client = new Google_Client();
		$client->setClientId($clientID);
		$client->setClientSecret($clientsecret);
		$client->setRedirectUri($redirectUri);
		$client->addScope('email');
		$client->addScope('profile');
		
		$data['client'] = $client;
		
		if ( isset($_GET['code']))
		{
			try // 구글 인증이을 제데로 했는지를 살펴 보고 인증된 값을 전달한다.
			{
				$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
				$client->setAccessToken($token['access_token']);
				$google_oauth 			= new Google_Service_Oauth2($client);
				$google_account_info 	= $google_oauth->userinfo->get();
				
				$this->setPageKey();
				$this->loginGoogle($google_account_info);
				
			
			} catch (Exception $e) // 다시 reflash 를 할 경우에 위의 해당 토큰을 잃어 버려서 exception 을 발생
			{
				$redirect_route = '/login';
				throw new RedirectException($redirect_route);
			}
			
		} else 
		{
			// 로그인 페이지를 보여준다.
			echo view('/login/login',$data);
		}
	}
	
	/**
	 * 구글 인증 이후 처리
	 * email : 구글 이메일 
	 * familyName
	 * gender
	 * givenName
	 * hd
	 * id : 구글 아이디
	 * locale : 국가
	 * name : 구글에 저장된 이름
	 * picture : 구글에 저장된 이미지
	 * @param Object $data
	 */
	public function loginGoogle($data)
	{
		$session = session();
		$this->ifProtect();
		$model = new UserModel();
		
		// 구글 로그인 이후에 먼저 데이터베이스에서 검사를 하여 가입이 되어있는지를 체크한다.
		// 체크한 이후에 가입이 되어있으면 바로 세션을 만들고 메인 페이지에 접속 할 수 있도록 한다.
		// 만약에 가입 되어 있지 않다면 가입하기로 이동하여 추가 정보를 입력 받고 가입을 한다.
		
		$setData = array(
			'user_google_id' 	=> $data['id']
			,'user_email' 		=> $data['email']
		);
		
		$google_chk = $model->users_google_exist_check_count($setData);
		
		if ( count($google_chk) == 0)
		{
			$this->moreJoinAsk($setData);
			
		} else 
		{
			
			if ( $this->session->has('reUrl') )
			{
				$reUrl = $session->get('reUrl');
				$session->remove('reUrl');
			} else 
			{
				$reUrl = '/main';
			}
			
			$sesMake['session_id'] = $google_chk[0]['idx'];
			$sesMake['user_name'] = $google_chk[0]['user_name'];
			$session->set($sesMake);
			
			echo "<script>location.href='" . $reUrl . "';</script>";
		}
		
	}
	
	/**
	 * 구글 로그인 이후에 회원가입이 되어있지 않으면 추가 정보 입력을 받을 후 회원가입을 진행한다.
	 * @param array $data
	 */
	public function moreJoinAsk($data)
	{
		//echo "moreJoinAsk";
		echo view('/login/moreJoinAsk',$data);
	}
	
	/**
	 * 추가 정보를 입력한것을 데이터베이스에 입력 처리를 한다.
	 * @method : Post
	 * user_phone , user_name
	 */	
	public function moreJoinProc()
	{
		$session = session();
		$model = new UserModel();
		
		$postVar = $this->request->getPost();
		$insVar['user_phone'] = $postVar['user_phone'];
		$insVar['user_name'] = $postVar['user_name'];
		$insVar['user_email'] = $postVar['user_email'];
		$insVar['user_google_id'] = $postVar['user_google_id'];
		
		$rValue = $model->insert_moreask_join($insVar);
		$insert_id = $rValue[0]->connID->insert_id;
		
		$sesMake['session_id'] = $insert_id;
		$sesMake['user_name'] = $insVar['user_name'];
		$session->set($sesMake);
		
		return redirect()->to('/main');
	}
	
	public function goto_main()
	{
		
	}
	
}