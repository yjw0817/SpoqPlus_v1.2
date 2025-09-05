<?php
namespace App\Libraries;


use Exception;
use function PHPUnit\Framework\throwException;

class Ama_tm_sms {
	
	private $_setVar = array(
			'subject' => '' // LMS일 경우에 사용함
			, 'rdate' => '' // 예약날짜
			, 'rtime' => '' // 예약시간
			, 'testflag' => '' // 테스트 일경우 Y
			, 'destination' => '' // 메세지에 받는 사람 이름을 넣고 싶을때 이용
			, 'repeatFlag' => '' // 반복설정 Y 일경우
			, 'repeatNum' => '' // 반복 횟수
			, 'repeatTime' => '' // 반복 시간
			, 'smsType' => 'L' // S 는 sms L 은 LMS
			, 'nointeractive' => '0' // 1을 설정할 경우 alert창 사용함
			, 'msg' => '' // 전송 메세지
			, 'rphone' => '' // 받는사람 전화번호
			, 'sphone1' => '' // 발신번호 앞자리
			, 'sphone2' => '' // 발신번호 중간자리
			, 'sphone3' => '' // 발신번호 뒷자리
			, 'returnurl' => '' // 전송 후 이동할 페이지 http://를 붙여야함.
	);
	
	
	public function __construct($initVar) {
		/*
		$initVar['event_name'];
		$initVar['revice_phone'];
		$initVar['send_phone'];
		*/
		
		try {
			if ( !isset($initVar['msg'])) throw new Exception('msg 이 정의되지 않았습니다.');
			if ( !isset($initVar['revice_phone'])) throw new Exception('revice_phone 이 정의되지 않았습니다.');
			if ( !isset($initVar['send_phone'])) throw new Exception('send_phone 이 정의되지 않았습니다.');
				
		} catch (Exception $e) {
			_vardump($e->getMessage());
			log_message('error', $e);
			exit();
		}
		
		$this->_set_msg($initVar['msg']);
		$this->_set_rphone($initVar['revice_phone']);
		$this->_set_sphone($initVar['send_phone']);
			
	}
	
	public function ttt()
	{
		_vardump($this->_setVar['rphone']);
	}
	
	private function _set_msg($var)
	{
		// msg는 90 bytes 이하여야 한다.
// 		if ( strlen($var) < 90)
// 		{
// 			$this->_setVar['msg'] = $var;			
// 		}
		$this->_setVar['msg'] = $var;
	}
	
	private function _set_rphone($var)
	{
		// - 이 반드시 있어야 한다.
		$remake_phone = '';
		$split_phone = explode(',',$var);
		
		if ( count($split_phone) > 0):
			for($i=0;$i<count($split_phone);$i++):
				$de_phone = str_replace('-','',$split_phone[$i]);
				$remake_phone .= preg_replace('/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/', '$1-$2-$3', $de_phone) . ',';
			endfor;
			
			$remake_phone = substr($remake_phone,0,-1);
		else:
			$de_phone = str_replace('-','',$var);
			$remake_phone = preg_replace('/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/', '$1-$2-$3', $de_phone);
			
		endif;
		
		$this->_setVar['rphone'] = $remake_phone;
		
	}
	
	private function _set_sphone($var)
	{
		$de_phone = str_replace('-','',$var);
		$remake_phone = preg_replace('/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/', '$1-$2-$3', $de_phone);
		$sphone = explode('-',$remake_phone);
		$this->_setVar['sphone1'] = $sphone[0];
		$this->_setVar['sphone2'] = $sphone[1];
		$this->_setVar['sphone3'] = $sphone[2];
	}
	
	
	public function sendSms()
	{
		/******************** 인증정보 ********************/
		$sms_url = 'https://sslsms.cafe24.com/sms_sender.php'; // HTTPS 전송요청 URL
		//$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
		$sms['user_id'] = base64_encode('jin7ho77'); //SMS 아이디.
		$sms['secure'] = base64_encode('2b09f12d9ba3510c71468c7007429eb7') ;//인증키
		$sms['msg'] = base64_encode(stripslashes($this->_setVar['msg']));
		if( $this->_setVar['smsType'] == 'L'){
			$sms['subject'] =  base64_encode($this->_setVar['subject']);
		}
		$sms['rphone'] = base64_encode($this->_setVar['rphone']);
		$sms['sphone1'] = base64_encode($this->_setVar['sphone1']);
		$sms['sphone2'] = base64_encode($this->_setVar['sphone2']);
		$sms['sphone3'] = base64_encode($this->_setVar['sphone3']);
		$sms['rdate'] = base64_encode($this->_setVar['rdate']);
		$sms['rtime'] = base64_encode($this->_setVar['rtime']);
		$sms['mode'] = base64_encode('1'); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		$sms['returnurl'] = base64_encode($this->_setVar['returnurl']);
		$sms['testflag'] = base64_encode($this->_setVar['testflag']);
		$sms['destination'] = strtr(base64_encode($this->_setVar['destination']), '+/=', '-,');
		$returnurl = $this->_setVar['returnurl'];
		$sms['repeatFlag'] = base64_encode($this->_setVar['repeatFlag']);
		$sms['repeatNum'] = base64_encode($this->_setVar['repeatNum']);
		$sms['repeatTime'] = base64_encode($this->_setVar['repeatTime']);
		$sms['smsType'] = base64_encode($this->_setVar['smsType']); // LMS일경우 L
		$nointeractive = $this->_setVar['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략
		
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
			//echo $alert;
			log_message('error', $alert);
		}
		
		//echo "<script>location.href='".$returnurl."';</script>";
	}
	
}