<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Config\Services;
use Psr\Log\LoggerInterface;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\MenuModel;
use App\Libraries\Ama_sno;
use App\Libraries\Attd_lib;
use App\Libraries\Clas_lib;
use App\Libraries\Domcy_lib;
use App\Libraries\Event_lib;
use App\Libraries\Pay_lib;
use App\Libraries\Sarly_lib;
use App\Libraries\Send_lib;
use App\Libraries\SpoQjwt;
use App\Libraries\StdPayUtil;
use App\Models\AnnuModel;
use App\Models\CateModel;
use App\Models\EventModel;
use App\Models\HistModel;
use App\Models\LockrModel;
use App\Models\MemModel;
use App\Models\MemoModel;
use App\Models\SalesModel;
use App\Models\VanModel;
use CodeIgniter\Encryption\Encryption;
use App\Models\MobileTModel;
use App\Models\AttdModel;
use App\Models\ClasModel;
use App\Models\SendModel;
use App\Models\FaceRecognitionModel;
use App\Libraries\SpoQ_sms;
use App\Libraries\GxClas_lib;
use App\Models\SmsModel;
use App\Libraries\SmsService;
use App\Libraries\SpoQ_def;

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

class Api extends ApiController
{
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
	
	public function ntest()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => 'Native Test',
	        'nav' => array('Native Test' => '' , 'TEST' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/ntest',$data);
	}
	
	public function upload_photo()
	{
	    $file = $this->request->getFile('croppedImage');
	    
        $path = $file->store();
        
        //$this->img_resize($path, $path."_thumb", 10); // 원본파일명 , 저장할파일명 , 퀄리티
        $this->img_resize("/var/www/html/writable/uploads/".$path, "/var/www/html/writable/uploads/".$path."_thumb", 10);
        
        $return_json['result'] = 'true';
        $return_json['path'] = WRITEPATH;
	    $return_json['postVar'] = $path;
	
	    return json_encode($return_json);
	}
	
	private function img_resize($ori_img_path, $copy_img_path, $quality) {
	    
	    $size = getimagesize($ori_img_path);
	    
	    if ($size['mime'] == 'image/jpeg')
	        $ori_image = imagecreatefromjpeg($ori_img_path);
	        elseif ($size['mime'] == 'image/gif')
	        $ori_image = imagecreatefromgif($ori_img_path);
	        elseif ($size['mime'] == 'image/png')
	        $ori_image = imagecreatefrompng($ori_img_path);
	        imagejpeg($ori_image, $copy_img_path, $quality);
	        return $copy_img_path;
	}
	
	
	
	
	
	/**
	 * ===============================================================================================
	 * 공개키 암호화 모듈 테스트 [ START ]
	 * ===============================================================================================
	 */
	
	public function enc_tt($var)
	{
	    _vardump($this->enc_phone($var));
	}
	
	public function enc_key()
	{
	    //$this->load->library('encryption');
	    $encryption = new Encryption();
	    $encrypter = $encryption->initialize(
	        );
	    //$key = base64_encode($this->encryption->create_key(16));
	    
// 	    $rnd_word = sprintf('%016d',time()) . "amatelas";
// 	    $rnd_word_hash = hash('sha256' , $rnd_word);
// 	    echo "rnd_key : " . $rnd_word_hash . "<br /><br /><br />";
	    
	    //$plain_text = 'This is a plain-text message!This is a plain-text message!This is a plain-text message!This is a plain-text message!This is a plain-text message!This is a plain-text message!This is a plain-text message!This is a plain-text message!This is a plain-text message!This is a plain-text message!';
	    $plain_text = "abdhjuiskA$3SFokei011234567890";
	    $ciphertext = base64_encode($encrypter->encrypt($plain_text));
	    
	    
	    echo "<br />encryption : " . $ciphertext;
	    //$ciphertext = "1b2df6c38bb70c54360c44ae4954e949962ef5f727d1278615c3d9577584b0688ad32d0da1817318599075772bf8a4fa4a436595a3ad0fc83064500cafda989dX+VAc5SRZh0NZA419g4KSnQ4PRwTbaKLmLFvm0J31GwnBneSBzwDOPMXfJ0F7qKomdIAo0UJA0Ci/EWpmYZmNFx2A1cR6uXOuG5YyiUNW/qfEF8trpmqgDNLWuMhS0Dpz2fLUV38muD04YBRnOi7IgF3Ujef1RZTzbcVTlBQkHbL0KzeDuT+ANIjGKMJcsHEmMwZgAZVIjSYTL9cDmhHkKgwYfWCfsDHxsT6EmgRQGDzAWzn1BourGJ1foni16lqMf9Rc9p7VoFnb/uq61SrhtLRcxzVQEPaNnuguER6I+GcPnAeKz6tks06hBS29520ma/Or3X36ATHu6hkmtDdeIMSUroM+sOqLzF8pXNsT+yd/O0VToUh12oXf7tmB6fqw54bBUfX7n0UgdcCFhF8YV5r";
	    $de_ciphertext = $encrypter->decrypt(base64_decode($ciphertext));
	    echo "<br />";
	    echo "decryption : " . $de_ciphertext;
	    
	}
	
	public function enc_sha256()
	{
	    $enc_word = $this->enc_pass('james1111');
	    echo $enc_word;
	}
	
	public function denc_key()
	{
	    $encryption = new Encryption();
	    $encrypter = $encryption->initialize(
	        );
	    $ciphertext = "bzWFsF5xRwheDcNc6Nx6b5qtoEdcKMrNPIC4G6MNjYuc2sKHtWN/QpZgkudbvTXOJ3sL3W0mpSguZ1pYloI7vy32r5z46S96/dTXGrNga+L302YSqHwHtjl0qRr8dtoZECY6TLDkqWHEjV8QD1Cul+DMYNd0yHcHH8dTAqI2xSXaunSQQ14SVtjSgVjLhVbMuG+bUWrWdd0o0R/5HUtFdO63xJOWcCs35XWmUaVP7Lw3KaL+EtK1jSMyQ/AderHb/C1KA3dHS80IGUfEdqHI0lBsqxqN7DCMCd1pO+kKKPdSZTjiZ5FqwYgu9zB6Uu3I/qKDtrxcwfchrHyli29qH2DCUhcIUpuRpm3eH8SRteWImKnav3E1tT1WXrgQcrwxlyblB/dcZ81fHguhbfp/EF7/h3rQXFzuABA9mgzyWB2EMUKICMVDamQrvFIWdvKnwZ2ZGFqsABXitNstldzct8pU++3c3un3PQVapcjSeMW1sw==";
	    $de_ciphertext = $encrypter->decrypt(base64_decode($ciphertext));
	    echo "<br />";
	    echo "decryption : " . $de_ciphertext;
	}
	
	public function enc_gen()
	{
	    $password = "test";
	    $arr = rsa_generate_keys($password, $bit=2048, $digest_algorithm = 'sha256');
	    
	    $privatekey = $arr['private_key'];
	    $publickey = $arr['public_key'];
	    
	    echo "private key : " . $privatekey . "<br /><br /><br />";
	    echo "public key : " . $publickey . "<br /><br /><br />";
	}
	
	public function enc_key2()
	{
// 	    $password = "test";
// 	    $arr = rsa_generate_keys($password, $bit=2048, $digest_algorithm = 'sha256');
	    
// 	    $privatekey = $arr['private_key'];
// 	    $publickey = $arr['public_key'];
	    
// 	    echo "private key : " . $privatekey . "<br /><br /><br />";
// 	    echo "public key : " . $publickey . "<br /><br /><br />";
	    
	    $password = "test";
	    $privatekey = 
"-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIFHDBOBgkqhkiG9w0BBQ0wQTApBgkqhkiG9w0BBQwwHAQILjibZWQtKsQCAggA
MAwGCCqGSIb3DQIJBQAwFAYIKoZIhvcNAwcECJ6Bnf5eRRPxBIIEyGRwFMdbQ38F
hvbsDckVyJ2Ebk0zgSOlvQ5v2eq9lNJsDoExz2GKvk/VSEHEyNBXKEWsk/SxcjcD
2tpsXt22LlO4KYuB1SZk/H6XTTZZHa9f4g/uI1nHlZBEWvadHH6G4eqO+/Jk+caz
X4qElHRkFUuy2pee9BrtARLmkrk8rJoVvzzjtHQYcnub85b7Z7hjtEPXaPQiytwd
gHiT4ebA6bYefJ6qoMSG4Y4Ehi/jNomkzMga20q/nHxB5J7e25BNz1pX3UrLZsqi
WNtYt4ujzwTR63lo+BO7ylyfogPKag4LW18EKOuC/+VZhGkJp/bZ2tS5nCKBVVAD
eOHzlo70V20bmGJeX9wntZDrT90hNABV4iAcY3a5nq6LBDHxBwT8pK4sUnQXN55S
mdfzNa109zK08WiTXcZcdRmujGTaUZtxU5E2ZiOQ0m2/4Q8fqJrvXWBUcIWWPNrZ
RsD6OXXpqwKQWeLkHbGJipKi8Cz21pq7zTX2oNB7LRlIUQuezZpZmPKFU5CMOO1b
kSnbbUpXChq5QTAJGY31erDrpN7w2GBWTMLds9glYbvaWbxLaUVPL2T95IFYFR9I
yQsaLjTEQDSj0mz3PHkLnfktwI7ElaROcEB+GrxAjH7fjps3PDL8/nukdb/4uRuJ
aid6A3zKwZ/xab+v/QEgTfdfV36SUNviIa4h2R8IOcrZaAoMCPOJPIHBXHoJeK/l
7AFdKU2l+WvxhA4A90j4pRO/67pM9qyUMH74RMiqYV/+hgEWuaHoF2D+wTSj3ezB
l42K6lcIvaCeEb4Bu/y2YCrcjbWw6s+yoWCJg7CVVFydrOSsHpXiukkiZazYFyue
IsG00yQHgUiEdT1cA7ckvlsSIdCzvFl9abqCSRgPZBHbkvJS1dwzQUbAEuEmXzk4
YlAcwGPL5wdeMigkoA51m+89HFEZtCDpO/KjlOs5FfXzPiMmNoFNqNVPl5t9XHcR
00a6fWADltlXY9BJyBGS78JG3ZsVvylUveHW1xVHnBLGWJl08bHhL0LZYupKwtAp
in/aDqQroMszjqRXlFczPeJ0J03oLlg+EnSTPU/qK0QlGThBg/pyKZskhk0R7HFv
DBYMZivSmp40ei8XHTHfUaLYZMNPaIZZnMjBhOMNsSnDqpam4GvWTnT9prITGtJu
QJqxS+WBpFu4WN3Rn4iuuJ/GtJZSZx8EBBqG2n2+bKLULkYSQhCEL/l4ki/nIDNL
klppdvXw9KKGUa2GKkE6i7tgAc7gBNz3FthQ+uwJpZP1AeoXfE9kVZpRkhfIFbdf
HFeLHNxfxF28IBMepFrg+T8dkWLpk0WytNS101/TkxVSkGRZY9DKuJ6nmpubTyim
Z605XNh+I8nDk/LhUat5qZMmS/jNSg5QUcNwEETQiHtQjxUcXR/krti7SMekE5OT
vhPR1i1/ymV+cyCYme1zU0P92Evp/su9W4R65JeTvgNovVWinNTnnNTFb4PDOz0n
2ItrKmSYTTHqFTlYc26yrX2kbWuWde3FPhhtt4uTdlEovxbUzMrjLvrOncr3kA6S
6j5ZXoG0o8FWRNghm+kshP4RbQzp3HQfNYqs81pACt7+SfDas99guYAslEf2uXu/
eqMZfrIBwh9er16ilOPyxw==
-----END ENCRYPTED PRIVATE KEY-----";
	    
	    $publickey = 
"-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzgWiZrXtONdxPR44POYv
AnHGWXxtPCVVnnc7nIjAk+MKMfeOwiCw8/wT9C5wmqO+jUlsa2DZKk4Ha/nQhMVg
K/Pl79EkUlrpibbAq38SQE52IACeczHrxBAKKLkOuQc6CIuRF916p17USpp+Xwdh
DmE3BjtxnoZeplaVr/1joAk9f9EKZO1EXuqQiE9bbOA0AsZaxDEXQ1tn16odMfNF
XMuARMqtoPRD4nh4gB/9Y2T1eJH402yCq3rG/VSVaJfwAXR1YaQo1Sg8D7lvbqgX
WRhiuJaguH2U94rKtxsAyKvSTRZr9CcP3DvWoUNhep0vpruxpOeTcsjuUYTe9+dC
CQIDAQAB
-----END PUBLIC KEY-----";
	    
	    $plaintext = "amatelas";
	    
	    echo "plaintext : " . $plaintext . "<br /><br /><br />";
	    
	    $ciphertext = rsa_encrypt($plaintext, $publickey);
	    
	    echo "ciphertext : " . $ciphertext . "<br /><br /><br />";
	    
	    $resulttext = rsa_decrypt($ciphertext, $privatekey, $password);
	    
	    echo "resulttext : " . $resulttext . "<br /><br /><br />";
	    
	}
	
	/**
	 * ===============================================================================================
	 * 공개키 암호화 모듈 테스트 [ END ]
	 * ===============================================================================================
	 */
	
	/**
	 * 공개키로 암호화된 값을 복호화 한다.
	 * @param string $r
	 */
	private function SpoQ_decrypt($ciphertext)
	{
	    $password = "test";
	    $privatekey =
	    "-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIFHDBOBgkqhkiG9w0BBQ0wQTApBgkqhkiG9w0BBQwwHAQILjibZWQtKsQCAggA
MAwGCCqGSIb3DQIJBQAwFAYIKoZIhvcNAwcECJ6Bnf5eRRPxBIIEyGRwFMdbQ38F
hvbsDckVyJ2Ebk0zgSOlvQ5v2eq9lNJsDoExz2GKvk/VSEHEyNBXKEWsk/SxcjcD
2tpsXt22LlO4KYuB1SZk/H6XTTZZHa9f4g/uI1nHlZBEWvadHH6G4eqO+/Jk+caz
X4qElHRkFUuy2pee9BrtARLmkrk8rJoVvzzjtHQYcnub85b7Z7hjtEPXaPQiytwd
gHiT4ebA6bYefJ6qoMSG4Y4Ehi/jNomkzMga20q/nHxB5J7e25BNz1pX3UrLZsqi
WNtYt4ujzwTR63lo+BO7ylyfogPKag4LW18EKOuC/+VZhGkJp/bZ2tS5nCKBVVAD
eOHzlo70V20bmGJeX9wntZDrT90hNABV4iAcY3a5nq6LBDHxBwT8pK4sUnQXN55S
mdfzNa109zK08WiTXcZcdRmujGTaUZtxU5E2ZiOQ0m2/4Q8fqJrvXWBUcIWWPNrZ
RsD6OXXpqwKQWeLkHbGJipKi8Cz21pq7zTX2oNB7LRlIUQuezZpZmPKFU5CMOO1b
kSnbbUpXChq5QTAJGY31erDrpN7w2GBWTMLds9glYbvaWbxLaUVPL2T95IFYFR9I
yQsaLjTEQDSj0mz3PHkLnfktwI7ElaROcEB+GrxAjH7fjps3PDL8/nukdb/4uRuJ
aid6A3zKwZ/xab+v/QEgTfdfV36SUNviIa4h2R8IOcrZaAoMCPOJPIHBXHoJeK/l
7AFdKU2l+WvxhA4A90j4pRO/67pM9qyUMH74RMiqYV/+hgEWuaHoF2D+wTSj3ezB
l42K6lcIvaCeEb4Bu/y2YCrcjbWw6s+yoWCJg7CVVFydrOSsHpXiukkiZazYFyue
IsG00yQHgUiEdT1cA7ckvlsSIdCzvFl9abqCSRgPZBHbkvJS1dwzQUbAEuEmXzk4
YlAcwGPL5wdeMigkoA51m+89HFEZtCDpO/KjlOs5FfXzPiMmNoFNqNVPl5t9XHcR
00a6fWADltlXY9BJyBGS78JG3ZsVvylUveHW1xVHnBLGWJl08bHhL0LZYupKwtAp
in/aDqQroMszjqRXlFczPeJ0J03oLlg+EnSTPU/qK0QlGThBg/pyKZskhk0R7HFv
DBYMZivSmp40ei8XHTHfUaLYZMNPaIZZnMjBhOMNsSnDqpam4GvWTnT9prITGtJu
QJqxS+WBpFu4WN3Rn4iuuJ/GtJZSZx8EBBqG2n2+bKLULkYSQhCEL/l4ki/nIDNL
klppdvXw9KKGUa2GKkE6i7tgAc7gBNz3FthQ+uwJpZP1AeoXfE9kVZpRkhfIFbdf
HFeLHNxfxF28IBMepFrg+T8dkWLpk0WytNS101/TkxVSkGRZY9DKuJ6nmpubTyim
Z605XNh+I8nDk/LhUat5qZMmS/jNSg5QUcNwEETQiHtQjxUcXR/krti7SMekE5OT
vhPR1i1/ymV+cyCYme1zU0P92Evp/su9W4R65JeTvgNovVWinNTnnNTFb4PDOz0n
2ItrKmSYTTHqFTlYc26yrX2kbWuWde3FPhhtt4uTdlEovxbUzMrjLvrOncr3kA6S
6j5ZXoG0o8FWRNghm+kshP4RbQzp3HQfNYqs81pACt7+SfDas99guYAslEf2uXu/
eqMZfrIBwh9er16ilOPyxw==
-----END ENCRYPTED PRIVATE KEY-----";
	    
	    $resulttext = rsa_decrypt($ciphertext, $privatekey, $password);
	    return $resulttext;
	}
	
	
	
	public function test()
	{
	    //_vardump(getenv()); //
	    phpinfo();
	}
	
	
	
	public function find_info()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '아이디비번찾기',
	        'nav' => array('아이디비번찾기' => '' , '아이디비번찾기' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $this->user_sno = "temp".time();
	    $this->SpoQ_set_cookie();
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/find_info',$data);
	}
	
	
	public function join_agree()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원가입(약관동의)',
	        'nav' => array('회원가입' => '' , '약관동의' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $this->user_sno = "temp".time();
	    $this->SpoQ_set_cookie();
	    
	    $termsModel = new MemModel();
	    $termsData['user_sno'] = $this->user_sno;
	    $termsList = $termsModel->mobile_list_terms_join_agree($termsData);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['terms_list'] = $termsList; // 약관 미동의 리스트
	    $this->viewPageForMobile('/mobile_p/join_agree',$data);
	}
	
	
	public function join_chk_phone()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원가입(휴대폰 번호 체크)',
	        'nav' => array('회원가입' => '' , '휴대폰 번호 체크' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/join_chk_phone',$data);
	}
	
	public function find_chk_phone_cert($rechk="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '아이디비번찾기(휴대폰 번호 체크 처리)',
	        'nav' => array('아이디비번찾기' => '' , '휴대폰 번호 체크 처리' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $model = new MemModel();
	    
	    $send_rnd = sprintf('%06d',rand(0,999999));
	    
	    $initVar['event_name'] = '스포큐원 인증번호 : ' . $send_rnd;
	    $initVar['send_phone'] = '010-5276-5898'; //문자 받을 번호
	    $initVar['revice_phone'] = '010-3230-8007'; //발신번호
	    //$sms = new SpoQ_sms($initVar);
	    //$sms->sendSms();
	    
	    if ($rechk == '')
	    {
	        $postVar = $this->request->getPost();
	        
	        $chkData['mem_nm'] = $postVar['mem_nm'];
	        $chkData['mem_phone'] = put_num($postVar['mem_phone']);
	        $chkData['mem_birth'] = put_num($postVar['mem_birth']);
	        $chkData['mem_phone_enc2'] = $this->enc_pass(put_num($postVar['mem_phone']));
	        
	        $get_chk = $model->mobile_join_phone_cbhk($chkData);
	        
	        // ===========================================================================
	        // 화면 처리
	        // ===========================================================================
	        
	        if ( count($get_chk) > 0)
	        {
	            
	            if ( count($get_chk) == 1)
	            {
	                if ($get_chk[0]['USE_YN'] =="N")
	                {
	                    scriptAlert("탈퇴처리된 회원은 아이디비번 찾기가 불가능 합니다.");
	                    scriptLocation("/login/errlogout");
	                }
	            }
	            
	            //$sms = new SpoQ_sms($initVar);
	            //$sms->sendSms();
	            
	            // 가입을 위한 쿠키 처리 ???
	            $sss = $this->SpoQ_get_cookie();
	            $eee = $this->SpoQ_get_cookie_sno($sss['user_sno']);
	            $_SESSION['temp_mem_nm'] = $eee.$chkData['mem_nm'];
	            $_SESSION['temp_mem_phone'] = $eee.$chkData['mem_phone'];
	            $_SESSION['temp_mem_birth'] = $eee.$chkData['mem_birth'];
	            $_SESSION['temp_send_rnd'] = $eee.$send_rnd;
	            
	            $data['view']['send_rnd'] = $send_rnd; // 랜덤 인증번호
	            $this->viewPageForMobile('/mobile_p/find_chk_phone_cert',$data);
	        } else
	        {
	            $this->viewPageForMobile('/mobile_p/find_chk_phone_err',$data);
	        }
	    } else
	    {
	        //$sms = new SpoQ_sms($initVar);
	        //$sms->sendSms();
	        
	        $sss = $this->SpoQ_get_cookie();
	        $eee = $this->SpoQ_get_cookie_sno($sss['user_sno']);
	        $_SESSION['temp_send_rnd'] = $eee.$send_rnd;
	        
	        $data['view']['send_rnd'] = $send_rnd; // 랜덤 인증번호
	        $this->viewPageForMobile('/mobile_p/find_chk_phone_cert',$data);
	    }
	    
	    
	}
	
	
	
	public function join_chk_phone_cert($rechk="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원가입(휴대폰 번호 체크 처리)',
	        'nav' => array('회원가입' => '' , '휴대폰 번호 체크 처리' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $model = new MemModel();
	    
	    $send_rnd = sprintf('%06d',rand(0,999999)); 
	    
	    $initVar['event_name'] = '스포큐원 인증번호 : ' . $send_rnd;
	    $initVar['send_phone'] = '010-5276-5898'; //발신번호
	    $initVar['revice_phone'] = '010-3230-8007'; // 문자 받을 번호
	    
	    //$sms->sendSms();
	    
	    
	    if ($rechk == '')
	    {
	        $postVar = $this->request->getPost();
	        
	        $chkData['mem_nm'] = $postVar['mem_nm'];
	        $chkData['mem_phone'] = put_num($postVar['mem_phone']);
	        $chkData['mem_birth'] = put_num($postVar['mem_birth']);
	        $chkData['mem_phone_enc2'] = $this->enc_pass(put_num($postVar['mem_phone']));
	        $chkData['mem_gendr'] = $postVar['mem_gendr'];
	        
	        
	        $get_chk = $model->mobile_join_phone_cbhk($chkData);
	        
	        // ===========================================================================
	        // 화면 처리
	        // ===========================================================================
	        
	        if ( count($get_chk) > 0)
	        {
	            $this->viewPageForMobile('/mobile_p/join_chk_phone_dup',$data);
	        } else
	        {
	            //$sms = new SpoQ_sms($initVar);
	            //$sms->sendSms();
	            
	            // 가입을 위한 쿠키 처리 ???
	            $sss = $this->SpoQ_get_cookie();
	            $eee = $this->SpoQ_get_cookie_sno($sss['user_sno']);
	            $_SESSION['temp_mem_nm'] = $eee.$chkData['mem_nm'];
	            $_SESSION['temp_mem_phone'] = $eee.$chkData['mem_phone'];
	            $_SESSION['temp_mem_birth'] = $eee.$chkData['mem_birth'];
	            $_SESSION['temp_mem_gendr'] = $eee.$chkData['mem_gendr'];
	            $_SESSION['temp_send_rnd'] = $eee.$send_rnd;
	            
	            $data['view']['send_rnd'] = $send_rnd; // 랜덤 인증번호
	            $this->viewPageForMobile('/mobile_p/join_chk_phone_cert',$data);
	        }
	    } else 
	    {
	        //$sms = new SpoQ_sms($initVar);
	        //$sms->sendSms();
	        
	        $sss = $this->SpoQ_get_cookie();
	        $eee = $this->SpoQ_get_cookie_sno($sss['user_sno']);
	        $_SESSION['temp_send_rnd'] = $eee.$send_rnd;
	        
	        $data['view']['send_rnd'] = $send_rnd; // 랜덤 인증번호
	        $this->viewPageForMobile('/mobile_p/join_chk_phone_cert',$data);
	    }
	    
	    
	}
	
	public function find_form()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '아이디 찾기 비번 재설정',
	        'nav' => array('아이디비번찾기' => '' , '아이디 찾기 비번 재설정' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    $model = new MemModel();
	    
	    $chkData['mem_nm'] = $this->find_form_decode_var($_SESSION['temp_mem_nm']);
	    $chkData['mem_phone'] = $this->find_form_decode_var($_SESSION['temp_mem_phone']);
	    $chkData['mem_birth'] = $this->find_form_decode_var($_SESSION['temp_mem_birth']);
	    $chkData['mem_phone_enc2'] = $this->enc_pass(put_num($this->find_form_decode_var($_SESSION['temp_mem_phone'])));
	    
	    
	    $get_chk = $model->mobile_join_phone_cbhk($chkData);
	    // 아이디 마스크 처리
	    $f_size = 2;
	    $l_size = 2;
	    
	    $mask_id_len = strlen($get_chk[0]['MEM_ID']);
	    $mask_id_front = substr($get_chk[0]['MEM_ID'],0,$f_size);
	    
	    $mask_id_back = substr($get_chk[0]['MEM_ID'],(-1*$l_size));
	    
	    $mask_add = "";
	    for($i=0;$i<$mask_id_len-($f_size+$l_size);$i++)
	    {
	        $mask_add .="*";
	    }
	    
	    $maked_id = $mask_id_front . $mask_add . $mask_id_back;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['maked_id'] = $maked_id; // 마스크된 아이디
	    $this->viewPageForMobile('/mobile_p/find_form',$data);
	}
	
	private function find_form_decode_var($var)
	{
	    $return_word = "";
	    $sss = $this->SpoQ_get_cookie();
	    $eee = $this->SpoQ_get_cookie_sno($sss['user_sno']);
	    
	    $chk_word = substr($var,0,14);
	    
	    if ($chk_word == $eee)
	    {
	        $return_word = substr($var,14);
	    }
        
	    return $return_word;
	}
	
	public function find_chpass_proc()
	{
	    $postVar = $this->request->getPost();
	    $model = new MemModel();
	    
	    $chkData['mem_nm'] = $this->find_form_decode_var($_SESSION['temp_mem_nm']);
	    $chkData['mem_phone'] = $this->find_form_decode_var($_SESSION['temp_mem_phone']);
	    $chkData['mem_birth'] = $this->find_form_decode_var($_SESSION['temp_mem_birth']);
	    $chkData['mem_phone_enc2'] = $this->enc_pass(put_num($this->find_form_decode_var($_SESSION['temp_mem_phone'])));
	    
	    $get_chk = $model->mobile_join_phone_cbhk($chkData);
	    
	    $chkData['mem_id'] = $get_chk[0]['MEM_ID'];
	    $chkData['mem_pwd'] = $this->enc_pass($postVar['mem_pwd']);
	    
	    $model->mobile_update_repass($chkData);
	    
	    scriptLocation("/login/find_repass_logout");
	}
	
	
	public function join_form()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원가입',
	        'nav' => array('회원가입' => '' , '회원가입' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    
	    $chkData['mem_nm'] = $this->find_form_decode_var($_SESSION['temp_mem_nm']);
	    $chkData['mem_phone'] = $this->find_form_decode_var($_SESSION['temp_mem_phone']);
	    $chkData['mem_birth'] = $this->find_form_decode_var($_SESSION['temp_mem_birth']);
	    $chkData['mem_gendr'] = $this->find_form_decode_var($_SESSION['temp_mem_gendr']);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    
	    $data['view']['chkdata'] = $chkData;
	    $this->viewPageForMobile('/mobile_p/join_form',$data);
	}
	
	/**
	 * 아이디 중복 여부를 체크한다.
	 */
	public function ajax_id_chk()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    $model = new MemModel();
	    
	    $mdata['mem_id'] = $postVar['mem_id'];
	    $id_count = $model->id_chk($mdata);
	    
	    if ($id_count == 0)
	    {
	        $return_json['result'] = 'true';
	    } else
	    {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	}
	
	/**
	 * 회원 등록 처리
	 */
	public function join_form_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $model = new MemModel();
	    $nn_now = new Time('now');
	    
	    // ===========================================================================
	    // 전달받기
	    // ===========================================================================
	    /*
	     * mem_id : 회원 아이디
	     * mem_pwd : 회원 비밀번호
	     * mem_nm : 회원 명
	     * bthday : 생년월일
	     * mem_gendr : 성별
	     * mem_telno : 전화번호
	     * mem_addr : 주소
	     */
	    $postVar = $this->request->getPost();
	    
	    // ===========================================================================
	    // Model Data Set & Data Query Return
	    // ===========================================================================
	    
	    $amasno = new Ama_sno();
	    $mem_sno = $amasno->create_mem_sno();
	    
	    $mdata['mem_sno']		= $mem_sno['mem_sno'];
	    $mdata['mem_id']		= $postVar['mem_id'];
	    $mdata['mem_pwd']		= $this->enc_pass($postVar['mem_pwd']);
	    $mdata['mem_nm']		= $postVar['mem_nm'];
	    $mdata['qr_cd']			= "";
	    $mdata['bthday']		= put_num($postVar['bthday']);
	    $mdata['mem_gendr']		= $postVar['mem_gendr'];
	    $mdata['mem_telno']		= put_num($postVar['mem_telno']);
	    
	    $re_phone_num = $this->enc_phone(put_num($postVar['mem_telno']));
	    $mdata['mem_telno_enc'] = $re_phone_num['enc'];
	    $mdata['mem_telno_mask'] = $re_phone_num['mask'];
	    $mdata['mem_telno_short'] = $re_phone_num['short'];
	    $mdata['mem_telno_enc2'] = $re_phone_num['enc2'];
	    
	    $mdata['mem_addr']		= $postVar['mem_addr'];
	    $mdata['mem_main_img']	= "";
	    $mdata['mem_thumb_img']	= "";
	    $mdata['mem_dv']		= "M";
	    
	    $mdata['set_comp_cd'] = NULL;
	    $mdata['set_bcoff_cd'] = NULL;
	    
	    $mdata['cre_id'] = $postVar['mem_id'];
	    $mdata['cre_datetm'] = $nn_now;
	    $mdata['mod_id'] = $postVar['mem_id'];
	    $mdata['mod_datetm'] = $nn_now;
	    
	    $insert_mem_main_info = $model->insert_mem_main_info($mdata);
	    
	    // 약관 동의 처리 해야함.
	    $termsData['mem_sno'] = $mdata['mem_sno'];
	    $termsList = $model->mobile_list_terms_join_agree($termsData);
	    
	    foreach ($termsList as $r)
	    {
	        $setData['mem_sno'] = $mdata['mem_sno'];
	        $setData['mem_id'] = $mdata['mem_id'];
	        $setData['mem_nm'] = $mdata['mem_nm'];
	        $setData['terms_knd_cd'] = $r['TERMS_KND_CD'];
	        $setData['terms_comp_cd'] = "";
	        $setData['terms_bcoff_cd'] = "";
	        $setData['terms_agree_yn'] = "Y";
	        $setData['terms_round'] = $r['TERMS_ROUND'];
	        $setData['terms_agree_mem_sno'] = $mdata['mem_sno'];
	        $setData['terms_agree_mem_id'] = $mdata['mem_id'];
	        $setData['terms_agree_mem_nm'] = $mdata['mem_nm'];
	        $setData['terms_depu_yn'] = "N";
	        $setData['terms_depu_mem_sno'] = "";
	        $setData['terms_depu_mem_id'] = "";
	        $setData['terms_depu_mem_nm'] = "";
	        $setData['terms_depu_datetm'] = "";
	        $setData['cre_id'] = $mdata['mem_id'];
	        $setData['cre_datetm'] = $nn_now;
	        $setData['mod_id'] = $mdata['mem_id'];
	        $setData['mod_datetm'] = $nn_now;
	        
	        $model->insert_mem_terms($setData);
	    }
	    
	    $_SESSION['join_user_id'] = $postVar['mem_id'];
	    
	    // 모든 처리 이후에 로그인 페이지로 돌리기 위해 쿠키 세션을 다른 방식으로 지움.
	    //scriptLocation("/login/join_logout");
	    scriptLocation('/api/join_introd');
	    
	}
	
	/**
	 * 회원가입 완료 후 회원 추천 아이디
	 */
	public function join_introd()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원가입',
	        'nav' => array('회원가입' => '' , '회원추천' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    
	    $this->viewPageForMobile('/mobile_p/join_introd',$data);
	}
	
	/**
	 * 추천받을 아이디를 처리한다.
	 */
	public function ajax_join_introd_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $nn_now = new Time('now');
	    $postVar = $this->request->getPost();
	    $model = new MemModel();
	    
	    // 타겟 아이디에 대한 정보를 셋팅 (추천받은 아이디)
	    $tdata['mem_id'] = $postVar['mem_id'];
	    $tinfo = $model->get_mem_info_id_nocomp($tdata);
	    
	    $idata['t_mem_sno'] = $tinfo[0]['MEM_SNO'];
	    $idata['t_mem_id'] = $tinfo[0]['MEM_ID'];
	    $idata['t_mem_nm'] = $tinfo[0]['MEM_NM'];
	    
	    $sdata['mem_id'] = $_SESSION['join_user_id'];
	    $sinfo = $model->get_mem_info_id_nocomp($sdata);
	    
	    $idata['s_mem_sno'] = $sinfo[0]['MEM_SNO'];
	    $idata['s_mem_id'] = $sinfo[0]['MEM_ID'];
	    $idata['s_mem_nm'] = $sinfo[0]['MEM_NM'];
	    
	    $idata['c_mem_sno'] = $sinfo[0]['MEM_SNO'];
	    $idata['c_mem_id'] = $sinfo[0]['MEM_ID'];
	    $idata['c_mem_nm'] = $sinfo[0]['MEM_NM'];
	    
	    $idata['mod_id'] = $sinfo[0]['MEM_ID'];
	    $idata['mod_datetm'] = $nn_now;
	    $idata['cre_id'] = $sinfo[0]['MEM_ID'];
	    $idata['cre_datetm'] = $nn_now;
	    
	    $model->insert_mem_introd($idata);
	    
	    $return_json['result'] = 'true';
	    
	    return json_encode($return_json);
	}
	
	public function find_chk_phone_proc()
	{
	    $postVar = $this->request->getPost();
	    $send_rnd = $this->find_form_decode_var($_SESSION['temp_send_rnd']);
	    
	    if ($postVar['phone_cert'] == $send_rnd)
	    {
	        scriptLocation('/api/find_form');
	    } else
	    {
	        scriptAlert('인증번호가 잘못 되었습니다.');
	        scriptLocation('/api/find_chk_phone_cert/re');
	    }
	}
	
	public function join_chk_phone_proc()
	{
	    $postVar = $this->request->getPost();
	    $send_rnd = $this->find_form_decode_var($_SESSION['temp_send_rnd']);
	    
	    if ($postVar['phone_cert'] == $send_rnd)
	    {
	        scriptLocation('/api/join_form');
	    } else 
	    {
	        scriptAlert('인증번호가 잘못 되었습니다.');
	        scriptLocation('/api/join_chk_phone_cert/re');
	    }
	}
	
	
	
	public function loginProc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $model = new MemModel();
        $menuModel = new MenuModel();
	    $session = session();
	    $SpoQDef = SpoqDef(); // 직책 목록을 포함한 배열 가져오기
		
	    // ===========================================================================
	    // 전달받기
	    // ===========================================================================
	    /*
	     * tlogin_id : 아이디
	     * tlogin_pass : 비밀번호
	     */
	    $postVar = $this->request->getPost();
	    
	    // ===========================================================================
	    // Model Data Set & Data Query Return
	    // ===========================================================================
	    
	    if ($postVar['logintp'] == 'bio')
	    {
	        $mdata['login_id'] = $postVar['login_id'];
	        // 지문인식 암호화를 복호화 한다.
	        $dpwd = $this->SpoQ_decrypt($postVar['login_pass']);
	        if (substr($dpwd,10) == 'success')
	        {
	            $chk_tlogin = $model->mobile_login_bio_check($mdata);
	        } else 
	        {
	            $chk_tlogin = array();
	        }
	        
	    } elseif ($postVar['logintp'] == 'kpad')
	    {
	        // 키패드 암호화를 복호화 한다.
	        $dpwd = $this->SpoQ_decrypt($postVar['login_pass']);
	        
	        $mdata['login_id'] = $postVar['login_id'];
	        $mdata['login_pwd'] = $dpwd;
	        
	        $chk_tlogin = $model->mobile_login_kpad_check($mdata);
	        
	    } elseif ($postVar['logintp'] == 'phone')
	    {
	        // 전화번호 로그인 - 비밀번호 체크 없이 ID로만 조회
	        $mdata['login_id'] = $postVar['login_id'];
	        $chk_tlogin = $model->mobile_login_id_check($mdata);
	        
	    } else
	    {
	        $mdata['login_id'] = $postVar['login_id'];
	        $mdata['login_pwd'] = $this->enc_pass($postVar['login_pass']);
	        
	        $chk_tlogin = $model->mobile_login_check($mdata);
	    }
	    
		    
        
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    // 데이터에서 값이
	    // 0 이상이면 로그인 성공 처리
	    // 0 이면 로그인 실패 처리
	    if (count($chk_tlogin) > 0) :
			$sesMake['bcoff_nm']    = $chk_tlogin[0]['BCOFF_NM']; // 지점명
			$position_code = $chk_tlogin[0]['TCHR_POSN']; // 직책 코드
			$position_name = isset($SpoQDef['TCHR_POSN'][$position_code]) ? $SpoQDef['TCHR_POSN'][$position_code] : "알 수 없음";
			$sesMake['TCHR_POSN_NM']   = $position_name; //직책명
			$cateData["bcoff_mgmt_id"] = $chk_tlogin[0]['BCOFF_MGMT_ID']; // 회원, 강사 구분
			$cateData["user_id"] = $chk_tlogin[0]['MEM_ID'];
			$cateData["bcoff_cd"] = $chk_tlogin[0]['BCOFF_CD'];
	    if ($chk_tlogin[0]['USE_YN'] == "N")
	    {
	        $reUrl = "/login/errlogout";
	        echo "<script>alert('탈퇴처리된 회원입니다. " . substr($chk_tlogin[0]['SECE_DATETM'],0,10) . "');</script>";
	        echo "<script>location.href='" . $reUrl . "';</script>";
	    }
	    
	    if ($chk_tlogin[0]['CONN_POSS_YN'] == "N")
	    {
	        $reUrl = "/login/errlogout";
	        echo "<script>alert('접속이 제한된 계정입니다. 관리자에게 문의 하세요');</script>";
	        echo "<script>location.href='" . $reUrl . "';</script>";
	    }
	    
	    $sesMake['user_sno']    = $chk_tlogin[0]['MEM_SNO']; //user sno
	    $sesMake['user_name']   = $chk_tlogin[0]['MEM_NM']; //이름
	    $sesMake['user_id']     = $chk_tlogin[0]['MEM_ID']; // 아이디
	    $sesMake['mem_dv']      = $chk_tlogin[0]['MEM_DV']; // 회원/강사 구분
	    
	    // 약관 동의 여부를 체크한다.
	    $chkTermData['mem_sno'] = $chk_tlogin[0]['MEM_SNO'];
	    $chk_terms_basic_count = $model->mobile_chk_terms_basic_count($chkTermData);
	    
    	    if ($chk_terms_basic_count[0]['counter'] == 0) : // 약관을 모두 동의 하였을 경우.
    	    
        	    if ($chk_tlogin[0]['SET_COMP_CD'] != '' && $chk_tlogin[0]['SET_BCOFF_CD'] != '') :
        	        // 임시
        	        $sesMake['comp_cd']      = $chk_tlogin[0]['SET_COMP_CD'];
        	        $sesMake['bcoff_cd']     = $chk_tlogin[0]['SET_BCOFF_CD'];
        	        
        	        $get_comp_nm = $model->mobile_get_comp_nm($sesMake);
        	        $get_bcoff_nm = $model->mobile_get_bcoff_nm($sesMake);
        	        
        	        $sesMake['comp_nm'] = $get_comp_nm[0]['COMP_NM'];
        	        $sesMake['bcoff_nm'] = $get_bcoff_nm[0]['BCOFF_NM'];

        	        if ($chk_tlogin[0]['MEM_DV'] == 'M') :
						$cateData["use_for"]    = "MC";
            	        $sesMake['site_type']   = "mmlogin";

            	        $reUrl = "/api/mmmain/1";
        	        elseif ($chk_tlogin[0]['MEM_DV'] == 'T') :
						$cateData["use_for"]    = "MT";
            	        $sesMake['site_type']   = "mtlogin";

            	        $reUrl = "/api/mtmain/1";
        	        endif;
        	        
					$menu_List = $menuModel->list_menu_of_user($cateData);

        	        
        	        $this->user_sno = $chk_tlogin[0]['MEM_SNO'];
        	        $this->SpoQ_set_cookie();
        	    else :
        	    
            	    if ($chk_tlogin[0]['MEM_DV'] == 'M') :						
						$cateData["use_for"]    = "MC";
                	    $sesMake['site_type']   = "mmlogin";
                	    $reUrl = "/api/setcomp";
            	    elseif ($chk_tlogin[0]['MEM_DV'] == 'T') :						
						$cateData["use_for"]    = "MT";
                	    $sesMake['site_type']   = "mtlogin";
                	    $reUrl = "/api/mtmain/1";
            	    endif;
            	    
					$menu_List = $menuModel->list_menu_of_user($cateData);

            	    
            	    $this->user_sno = $chk_tlogin[0]['MEM_SNO'];
            	    $this->SpoQ_set_cookie();
        	    endif;
				$sesMake['menu_list'] = $menu_List;
				$session->set($sesMake);
    	       echo "<script>location.replace('" . $reUrl . "');</script>";
    	   else : // 약관 동의가 필요한 경우
    	   
    	       if ($chk_tlogin[0]['MEM_DV'] == "T")
    	       {
    	           $sesMake['comp_cd']      = $chk_tlogin[0]['SET_COMP_CD'];
    	           $sesMake['bcoff_cd']     = $chk_tlogin[0]['SET_BCOFF_CD'];
    	           $sesMake['site_type']   = "mtlogin";
    	           $reUrl = "/api/mtmain";
				   $cateData["use_for"]    = "MT";
				   $menu_List = $menuModel->list_menu_of_user($cateData);
    	           
    	           $this->user_sno = $chk_tlogin[0]['MEM_SNO'];
    	           $this->SpoQ_set_cookie();
    	           echo "<script>location.replace('" . $reUrl . "');</script>";
    	       } 
			   	else 
    	       {
    	           $sesMake['site_type']   = "mmlogin";
    	           $reUrl = "/api/terms_agree_auth";
    	           $cateData["use_for"]    = "MC";
				   $menu_List = $menuModel->list_menu_of_user($cateData);
    	           $this->user_sno = $chk_tlogin[0]['MEM_SNO'];
    	           $this->SpoQ_set_cookie();
    	           echo "<script>location.replace('" . $reUrl . "');</script>";
    	       }
        	   
				   
    	   endif;
		   $sesMake['menu_list'] = $menu_List;
			$session->set($sesMake);
	    else :
    	    $reUrl = "/login";
    	    echo "<script>alert('아이디나 비밀번호를 다시 확인해주세요');</script>";
    	    echo "<script>location.href='" . $reUrl . "';</script>";
	    endif ;
	}
	
	/**
	 * 회원 모바일 메인 ( SAMPLE )
	 */
	public function sample()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원 메인',
	        'nav' => array('회원관리' => '' , '회원 메인' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/sample',$data);
	}
	
	/**
	 * 약관동의 ( 회원 로그인 이후 약관 동의가 필요할 경우 )
	 */
	public function terms_agree_auth()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '약관동의',
	        'nav' => array('회원' => '' , '약관동의' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $termsModel = new MemModel();
	    $termsData['mem_sno'] = $_SESSION['user_sno'];
	    $termsList = $termsModel->mobile_list_terms_basic($termsData);
	    
	    if (count($termsList) == 0)
	    {
	        if ($_SESSION['site_type'] == "mmlogin")
	        {
	            scriptLocation('/api/mmmain');
	        } else 
	        {
	            scriptLocation('/api/mtmain');
	        }
	    }
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['terms_list'] = $termsList; // 약관 미동의 리스트
	    $this->viewPageForMobile('/mobile_p/terms_agree_auth',$data);
	}
	
	/**
	 * 약관동의 처리 ( 회원 로그인 이후 약관 동의 처리 )
	 */
	public function terms_agree_proc()
	{
	    $nn_now = new Time('now');
	    $termsModel = new MemModel();
	    $termsData['mem_sno'] = $_SESSION['user_sno'];
	    $termsList = $termsModel->mobile_list_terms_basic($termsData);
	    
	    foreach ($termsList as $r)
	    {
	        $setData['mem_sno'] = $_SESSION['user_sno'];
	        $setData['mem_id'] = $_SESSION['user_id'];
	        $setData['mem_nm'] = $_SESSION['user_name'];
	        $setData['terms_knd_cd'] = $r['TERMS_KND_CD'];
	        $setData['terms_comp_cd'] = "";
	        $setData['terms_bcoff_cd'] = "";
	        $setData['terms_agree_yn'] = "Y";
	        $setData['terms_round'] = $r['TERMS_ROUND'];
	        $setData['terms_agree_mem_sno'] = $_SESSION['user_sno'];
	        $setData['terms_agree_mem_id'] = $_SESSION['user_id'];
	        $setData['terms_agree_mem_nm'] = $_SESSION['user_name'];
	        $setData['terms_depu_yn'] = "N";
	        $setData['terms_depu_mem_sno'] = "";
	        $setData['terms_depu_mem_id'] = "";
	        $setData['terms_depu_mem_nm'] = "";
	        $setData['terms_depu_datetm'] = "";
	        $setData['cre_id'] = $_SESSION['user_id'];
	        $setData['cre_datetm'] = $nn_now;
	        $setData['mod_id'] = $_SESSION['user_id'];
	        $setData['mod_datetm'] = $nn_now;
	        
	        $termsModel->insert_mem_terms($setData);
	    }
	    
	    // 약관 동의 체크 이후에 지점설정이 되어있는지를 검사한다.
	    $session = session();
	    $mdata['mem_sno'] = $_SESSION['user_sno'];
	    $chk_tlogin = $termsModel->mobile_get_comp_info($mdata);
	    
	    if ($chk_tlogin[0]['SET_COMP_CD'] != '' && $chk_tlogin[0]['SET_BCOFF_CD']) :
    	    // 임시
    	    $sesMake['comp_cd']      = $chk_tlogin[0]['SET_COMP_CD'];
    	    $sesMake['bcoff_cd']     = $chk_tlogin[0]['SET_BCOFF_CD'];
    	    
    	    $get_comp_nm = $termsModel->mobile_get_comp_nm($sesMake);
    	    $get_bcoff_nm = $termsModel->mobile_get_bcoff_nm($sesMake);
    	    
    	    $sesMake['comp_nm'] = $get_comp_nm[0]['COMP_NM'];
    	    $sesMake['bcoff_nm'] = $get_bcoff_nm[0]['BCOFF_NM'];
	    
    	    if ($chk_tlogin[0]['MEM_DV'] == 'M') :
        	    $sesMake['site_type']   = "mmlogin";
        	    $reUrl = "/api/mmmain/1";
    	    elseif ($chk_tlogin[0]['MEM_DV'] == 'T') :
        	    $sesMake['site_type']   = "mtlogin";
        	    $reUrl = "/api/mtmain/1";
    	    endif;
	    
	        $session->set($sesMake);
	    
    	    $this->user_sno = $chk_tlogin[0]['MEM_SNO'];
    	    $this->SpoQ_set_cookie();
	    else :
	    
    	    if ($chk_tlogin[0]['MEM_DV'] == 'M') :
        	    $sesMake['site_type']   = "mmlogin";
        	    $reUrl = "/api/setcomp";
    	    elseif ($chk_tlogin[0]['MEM_DV'] == 'T') :
        	    $sesMake['site_type']   = "mtlogin";
        	    $reUrl = "/api/mtmain/1";
    	    endif;
	    
    	    $session->set($sesMake);
    	    
    	    $this->user_sno = $chk_tlogin[0]['MEM_SNO'];
    	    $this->SpoQ_set_cookie();
	    endif;
	    
	    echo "<script>location.href='" . $reUrl . "';</script>";
	    
	}
	
	/**
	 * 모바일 회원가입 페이지
	 */
	public function mobile_register()
	{
	    $data = array(
	        'title' => '회원가입',
	        'nav' => array('회원가입' => '' , '정보입력' => ''),
	        'menu1' => $this->request->getGet("m1"),
	        'menu2' => $this->request->getGet("m2")
	    );
	    
	    // 전화번호 인증 확인
	    $phone = $this->request->getGet('phone');
	    if (!$phone || !isset($_SESSION['sms_verified_phone']) || $_SESSION['sms_verified_phone'] !== $phone) {
	        echo "<script>alert('전화번호 인증이 필요합니다.'); location.href='/login';</script>";
	        return;
	    }
	    
	    // 인증 시간 확인 (10분)
	    if ((time() - $_SESSION['sms_verified_time']) > 600) {
	        unset($_SESSION['sms_verified_phone']);
	        unset($_SESSION['sms_verified_time']);
	        echo "<script>alert('인증 시간이 만료되었습니다. 다시 인증해주세요.'); location.href='/login';</script>";
	        return;
	    }
	    
	    $this->viewPageForMobile('/mobile_p/register', $data);
	}
	
	/**
	 * 전화번호 인증 상태 확인
	 */
	public function checkPhoneVerification()
	{
	    try {
	        $postVar = $this->request->getPost();
	        $phoneNumber = $postVar['phone_number'] ?? '';
	        
	        if (isset($_SESSION['sms_verified_phone']) && 
	            $_SESSION['sms_verified_phone'] === $phoneNumber &&
	            (time() - $_SESSION['sms_verified_time']) < 600) { // 10분 유효
	            
	            return $this->response->setJSON([
	                'result' => 'true',
	                'message' => '인증 확인됨'
	            ]);
	        } else {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'message' => '인증 정보가 없거나 만료되었습니다.'
	            ]);
	        }
	    } catch (\Exception $e) {
	        return $this->response->setJSON([
	            'result' => 'false',
	            'message' => '오류가 발생했습니다.'
	        ]);
	    }
	}
	
	/**
	 * 회사 목록 조회 (회원가입용)
	 */
	public function getCompanyList()
	{
	    try {
	        $model = new MemModel();
	        
	        // 모든 회사 목록 조회
	        $companies = $model->mobile_list_all_companies();
	        
	        // 디버깅용 로그
	        log_message('info', '회사 목록 조회 결과: ' . count($companies) . '개');
	        
	        // 회사가 없으면 기본 회사 추가 (테스트용)
	        if (empty($companies)) {
	            $companies = [
	                ['COMP_CD' => 'DEFAULT', 'COMP_NM' => '기본 회사']
	            ];
	        }
	        
	        return $this->response->setJSON([
	            'result' => 'true',
	            'companies' => $companies,
	            'count' => count($companies)
	        ]);
	        
	    } catch (\Exception $e) {
	        log_message('error', '회사 목록 조회 오류: ' . $e->getMessage());
	        return $this->response->setJSON([
	            'result' => 'false',
	            'message' => '회사 목록을 불러올 수 없습니다.',
	            'error' => $e->getMessage()
	        ]);
	    }
	}
	
	/**
	 * 지점 목록 조회 (회원가입용)
	 */
	public function getBranchList()
	{
	    try {
	        $comp_cd = $this->request->getGet('comp_cd');
	        
	        if (!$comp_cd) {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'message' => '회사를 선택해주세요.'
	            ]);
	        }
	        
	        $model = new MemModel();
	        
	        // 선택된 회사의 지점 목록 조회
	        $branches = $model->mobile_list_branches_by_company($comp_cd);
	        
	        // 디버깅용 로그
	        log_message('info', '지점 목록 조회 (회사: ' . $comp_cd . ') 결과: ' . count($branches) . '개');
	        
	        // 지점이 없으면 기본 지점 추가 (테스트용)
	        if (empty($branches)) {
	            $branches = [
	                ['BCOFF_CD' => 'DEFAULT', 'BCOFF_NM' => '기본 지점']
	            ];
	        }
	        
	        return $this->response->setJSON([
	            'result' => 'true',
	            'branches' => $branches,
	            'count' => count($branches)
	        ]);
	        
	    } catch (\Exception $e) {
	        log_message('error', '지점 목록 조회 오류: ' . $e->getMessage());
	        return $this->response->setJSON([
	            'result' => 'false',
	            'message' => '지점 목록을 불러올 수 없습니다.',
	            'error' => $e->getMessage()
	        ]);
	    }
	}
	
	/**
	 * 모바일 회원가입 처리
	 */
	public function mobileRegisterProc()
	{
	    try {
	        $postVar = $this->request->getPost();
	        $model = new MemModel();
	        $nn_now = new Time('now');
	        
	        // 디버깅을 위한 로그
	        log_message('info', '[mobileRegisterProc] 회원가입 시작');
	        log_message('info', '[mobileRegisterProc] POST 데이터: ' . json_encode($postVar));
	        
	        // 전화번호 인증 확인
	        $phoneNumber = $postVar['mem_telno'] ?? '';
	        if (!isset($_SESSION['sms_verified_phone']) || 
	            $_SESSION['sms_verified_phone'] !== $phoneNumber) {
	            
	            log_message('error', '[mobileRegisterProc] 전화번호 인증 실패');
	            return $this->response->setJSON([
	                'result' => 'false',
	                'msg' => '전화번호 인증이 필요합니다.'
	            ]);
	        }
	        
	        // 지점 정보 분리
	        $comp_bcoff = explode('|', $postVar['comp_bcoff']);
	        $comp_cd = $comp_bcoff[0];
	        $bcoff_cd = $comp_bcoff[1];
	        
	        // 전화번호 정리
	        $phone_clean = put_num($phoneNumber);
	        
	        // 중복 체크
	        if ($model->check_phone_duplicate($phone_clean, $comp_cd, $bcoff_cd)) {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'is_duplicate' => true,
	                'msg' => '이미 등록된 전화번호입니다.'
	            ]);
	        }
	        
	        // 회원 데이터 준비 (ajax_mem_insert_proc 방식)
	        $amasno = new Ama_sno();
	        $mem_sno = $amasno->create_mem_sno();
	        
	        // 체크인 번호 자동 생성 (전화번호 기반, 중복 시 숫자 추가)
	        $generated_mem_id = $model->generate_mem_id($phone_clean, $comp_cd, $bcoff_cd);
	        $mem_id = $generated_mem_id;  // 체크인번호 = mem_id (숫자만)
	        
	        // 전화번호 암호화
	        $re_phone_num = $this->enc_phone($phone_clean);
	        
	        // mem_main_info_tbl 데이터 준비
	        $mdata = [
	            'mem_sno' => $mem_sno['mem_sno'],
	            'mem_id' => $mem_id,
	            'mem_pwd' => hash('sha256', $postVar['mem_pass']),
	            'mem_nm' => $postVar['mem_nm'],
	            'qr_cd' => "",
	            'bthday' => $postVar['bthday'] ? str_replace('-', '', $postVar['bthday']) : '',
	            'mem_gendr' => $postVar['mem_gendr'],
	            'mem_telno' => $phone_clean,
	            'mem_telno_enc' => $re_phone_num['enc'],
	            'mem_telno_mask' => $re_phone_num['mask'],
	            'mem_telno_short' => $re_phone_num['short'],
	            'mem_telno_enc2' => $re_phone_num['enc2'],
	            'mem_addr' => $postVar['mem_addr'] ?? '',
	            'mem_main_img' => "",
	            'mem_thumb_img' => "",
	            'mem_dv' => "M",
	            'set_comp_cd' => $comp_cd,
	            'set_bcoff_cd' => $bcoff_cd,
	            'cre_id' => $mem_id,
	            'cre_datetm' => $nn_now,
	            'mod_id' => $mem_id,
	            'mod_datetm' => $nn_now
	        ];
	        
	        // 회원 메인 정보 저장
	        log_message('info', '[mobileRegisterProc] mem_main_info_tbl 저장 시작');
	        $insert_mem_main_info = $model->insert_mem_main_info($mdata);
	        
	        // mem_info_detl_tbl 데이터 준비
	        $mdatad = [
	            'mem_sno' => $mem_sno['mem_sno'],
	            'comp_cd' => $comp_cd,
	            'bcoff_cd' => $bcoff_cd,
	            'mem_id' => $mem_id,
	            'mem_nm' => $postVar['mem_nm'],
	            'mem_stat' => "01", // 정상
	            'qr_cd' => "",
	            'bthday' => $postVar['bthday'] ? str_replace('-', '', $postVar['bthday']) : '',
	            'mem_gendr' => $postVar['mem_gendr'],
	            'mem_telno' => $phone_clean,
	            'mem_telno_enc' => $re_phone_num['enc'],
	            'mem_telno_mask' => $re_phone_num['mask'],
	            'mem_telno_short' => $re_phone_num['short'],
	            'mem_telno_enc2' => $re_phone_num['enc2'],
	            'mem_addr' => $postVar['mem_addr'] ?? '',
	            'mem_main_img' => "",
	            'mem_thumb_img' => "",
	            'tchr_posn' => "",
	            'ctrct_type' => "",
	            'tchr_simp_pwd' => "",
	            'jon_place' => "01",
	            'jon_datetm' => $nn_now,
	            'reg_place' => "",
	            'reg_datetm' => "",
	            'mem_dv' => "M",
	            'cre_id' => $mem_id,
	            'cre_datetm' => $nn_now,
	            'mod_id' => $mem_id,
	            'mod_datetm' => $nn_now
	        ];
	        
	        // 회원 상세 정보 저장
	        log_message('info', '[mobileRegisterProc] mem_info_detl_tbl 저장 시작');
	        $insert_mem_info_detl_tbl = $model->insert_mem_info_detl_tbl($mdatad);
	        
	        // 회원 이력 저장 (필요시)
	        $mdatad['mem_info_detl_hist_sno'] = $mem_sno['mem_sno_hist'];
	        $insert_mem_info_detl_hist_tbl = $model->insert_mem_info_detl_hist_tbl($mdatad);
	        
	        // 📸 이미지 파일 저장 처리
        $imageFilePaths = [];
        if (!empty($postVar['mem_photo'])) {
            log_message('info', '[mobileRegisterProc] 프로필 이미지 저장 시작');
            
            try {
                $base64_string = $postVar['mem_photo'];
                
                // Base64 데이터 파싱 (data:image/jpeg;base64, 부분 제거)
                if (strpos($base64_string, 'base64,') !== false) {
                    $base64_string = explode(',', $base64_string)[1];
                }
                
                // 이미지 데이터 디코드
                $image_data = base64_decode($base64_string);
                
                if ($image_data !== false) {
                    // 업로드 디렉토리 설정
                    $upload_dir = WRITEPATH . '../public/upload/photo/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    // 파일 경로 설정 (member_{mem_sno}.jpg 형식)
                    $file_path = $upload_dir . 'member_' . $mem_sno['mem_sno'] . '.jpg';
                    $thumb_path = $upload_dir . 'member_' . $mem_sno['mem_sno'] . '_thum.jpg';
                    
                    // GD 라이브러리로 이미지 처리
                    $src = imagecreatefromstring($image_data);
                    if ($src !== false) {
                        // 원본 이미지 저장 (JPG, 85% 품질)
                        imagejpeg($src, $file_path, 85);
                        
                        // 썸네일 생성 (120px 너비)
                        $width = imagesx($src);
                        $height = imagesy($src);
                        $thumb_width = 120;
                        $thumb_height = intval(($thumb_width / $width) * $height);
                        
                        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
                        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                        imagejpeg($thumb, $thumb_path, 85);
                        
                        // 리소스 해제
                        imagedestroy($src);
                        imagedestroy($thumb);
                        
                        // DB 경로 저장용
                        $imageFilePaths['mem_main_img'] = '/upload/photo/member_' . $mem_sno['mem_sno'] . '.jpg';
                        $imageFilePaths['mem_thumb_img'] = '/upload/photo/member_' . $mem_sno['mem_sno'] . '_thum.jpg';
                        
                        log_message('info', '[mobileRegisterProc] 이미지 파일 저장 성공: ' . $file_path);
                        
                        // 데이터베이스 업데이트 - mem_main_info와 mem_info_detl_tbl 모두 업데이트
                        $updateData = [
                            'mem_sno' => $mem_sno['mem_sno'],
                            'mem_main_img' => $imageFilePaths['mem_main_img'],
                            'mem_thumb_img' => $imageFilePaths['mem_thumb_img'],
                            'mod_id' => $mem_id,
                            'mod_datetm' => $nn_now
                        ];
                        
                        // mem_main_info 업데이트
                        $model->update_mem_main_info($updateData);
                        
                        // mem_info_detl_tbl 업데이트
                        $model->update_mem_info_detl_tbl($updateData);
                        
                        log_message('info', '[mobileRegisterProc] DB 이미지 경로 업데이트 완료');
                    }
                }
            } catch (\Exception $e) {
                log_message('error', '[mobileRegisterProc] 이미지 저장 오류: ' . $e->getMessage());
                // 이미지 저장 실패해도 회원가입은 성공으로 처리
            }
        }
        
        // 얼굴 데이터가 있으면 Python 서버에 저장
        if (!empty($postVar['face_data']) && !empty($postVar['mem_photo'])) {
            log_message('info', '[mobileRegisterProc] 얼굴 데이터 저장 시작');
            
            try {
                // face_data JSON 파싱
                $faceData = json_decode($postVar['face_data'], true);
                
                if (isset($faceData['face_data']['face_encoding'])) {
                    // FaceTest 컨트롤러 인스턴스 생성
                    $faceTest = new \App\Controllers\FaceTest();
                    
                    // 얼굴 등록 요청 데이터 - MEM_SNO를 member_id로 사용
                    $registerData = [
                        'member_id' => $mem_sno['mem_sno'],  // MEM_ID 대신 MEM_SNO 사용
                        'image' => $postVar['mem_photo'],
                        'param1' => $comp_cd,
                        'param2' => $bcoff_cd,
                        'face_encoding' => $faceData['face_data']['face_encoding'],
                        'quality_score' => $faceData['face_data']['quality_score'] ?? 0.85,
                        'glasses_detected' => $faceData['face_data']['glasses_detected'] ?? false
                    ];
                    
                    log_message('info', '[mobileRegisterProc] 얼굴 등록 요청 - member_id(MEM_SNO): ' . $mem_sno['mem_sno']);
                    
                    // Python 서버에 얼굴 데이터 저장
                    $result = $faceTest->callPythonAPI('/api/face/register', 'POST', $registerData);
                    
                    if ($result['success']) {
                        log_message('info', '[mobileRegisterProc] 얼굴 데이터 저장 성공');
                    } else {
                        log_message('error', '[mobileRegisterProc] 얼굴 데이터 저장 실패: ' . json_encode($result));
                        // 얼굴 등록 실패해도 회원가입은 성공으로 처리
                    }
                }
            } catch (\Exception $e) {
                log_message('error', '[mobileRegisterProc] 얼굴 데이터 처리 오류: ' . $e->getMessage());
                // 얼굴 등록 실패해도 회원가입은 성공으로 처리
            }
        }
        
        log_message('info', '[mobileRegisterProc] 회원가입 완료');
	        
	        return $this->response->setJSON([
	            'result' => 'true',
	            'msg' => '회원가입이 완료되었습니다.',
	            'mem_id' => $mem_id,
	            'checkin_no' => $mem_id // 생성된 체크인 번호 (mem_id와 동일)
	        ]);
	        
	    } catch (\Exception $e) {
	        log_message('error', '[mobileRegisterProc] 회원가입 처리 오류: ' . $e->getMessage());
	        log_message('error', '[mobileRegisterProc] 오류 위치: ' . $e->getFile() . ':' . $e->getLine());
	        log_message('error', '[mobileRegisterProc] POST 데이터: ' . json_encode($postVar));
	        
	        return $this->response->setJSON([
	            'result' => 'false',
	            'msg' => '시스템 오류가 발생했습니다: ' . $e->getMessage()
	        ]);
	    }
	}
	
	/**
	 * 회원 설정
	 */
	public function msetting()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '설정',
	        'nav' => array('회원' => '' , '설정' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/msetting',$data);
	}
	
	/**
	 * 간편비밀번호 셋팅
	 * @return string
	 */
	public function ajax_spwd_change()
	{
	    $nn_now = new Time('now');
	    $memModel = new MemModel();
	    $postVar = $this->request->getPost();
	    $de_spwd = $this->SpoQ_decrypt($postVar['spwd']);
	    
	    $upVar['mem_sno'] = $_SESSION['user_sno'];
	    $upVar['mem_spwd'] = $de_spwd;
	    $upVar['mod_id'] = $_SESSION['user_id'];
	    $upVar['mod_datetm'] = $nn_now;
	    
	    $memModel->update_mem_main_info_spwd($upVar);
	    
	    $return_json['msg'] = "간편비밀번호가 설정 되었습니다.";
	    $return_json['result'] = 'true';
	    
	    return json_encode($return_json);
	}
	
	public function mmmain($first_mode='')
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원메인',
	        'nav' => array('회원메인' => '' , '회원메인' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // 상단 회원 기본 정보를 가져온다.
	    // 세션 항목으로 처리함.
	    
	    $eventModel = new EventModel();
	    $model = new MobileTModel();
	    
	    // 구매상품 정보를 가져온다.
	    $edata['comp_cd'] = $_SESSION['comp_cd'];
	    $edata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $edata['mem_id'] = $_SESSION['user_id'];
	    $edata['mem_sno'] = $_SESSION['user_sno'];
	    $edata['event_stat'] = "00";
	    $edata['ndate'] = date('Y-m-d');
	    $edata['noti_dv'] = "01";
	    // 예약
	    $event_list_00 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    // 이용중
	    $edata['event_stat'] = "01";
	    $event_list_01 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    // 종료됨
	    $edata['event_stat'] = "99";
	    $event_list_99 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    
	    // 추천상품 [대기]
	    $send_list = $model->list_sendevent_memsno($edata);
	    
	    // 회원 공지사항
	    $list_notice = $model->list_tnotice($edata);
	    
	    $gxData['comp_cd'] = $_SESSION['comp_cd'];
	    $gxData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $gxClas = new GxClas_lib($gxData);
	    
	    $today = date('Y-m-d');
	    $gx_list = $gxClas->list_gx_today_schd_user_new($today);
	    
	    $new_gx_list_group = $gx_list['list_gx_today_group'];
	    $new_gx_list = array();
	    foreach($gx_list['list_gx_today_group'] as $g)
	    {
	        foreach($gx_list['list_gx_today'] as $d)
	        {
	            if ($g['GX_ROOM_MGMT_SNO'] == $d['GX_ROOM_MGMT_SNO'] && $g['GX_CLAS_TITLE'] == $d['GX_CLAS_TITLE'])
	            {
	                $new_gx_list[$d['GX_ROOM_MGMT_SNO']][$d['GX_CLAS_TITLE']][substr($d['GX_CLAS_S_HH_II'],0,2)] = $d;
	            }
	        }
	    }
	    
	    // 2단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cateModel = new CateModel();
	    $cate1nm = $cateModel->disp_cate1($cateData);
	    $cate2nm = $cateModel->disp_cate2($cateData);
	    
	    $cate2_nm = array();
	    
	    foreach($cate1nm as $c1) :
    	    foreach($cate2nm as $c2) :
        	    if ($c2['1RD_CATE_CD'] == $c1['1RD_CATE_CD']) :

        	       $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        	    endif;
    	    endforeach;
	    endforeach;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['first_mode'] = $first_mode;
	    $data['view']['event_list1'] = $event_list_01; // 예약
	    $data['view']['event_list2'] = $event_list_00; // 이용
	    $data['view']['event_list3'] = $event_list_99; // 종료
	    $data['view']['send_list'] = $send_list; // 추천상품
	    $data['view']['list_notice'] = $list_notice; // 회원 공지사항
	    $data['view']['list_gx_group'] = $new_gx_list_group; // GX 스케쥴
	    $data['view']['list_gx'] = $new_gx_list; // GX 스케쥴
	    $data['view']['cate_nm'] = $cate2_nm; // 2단계 카테고리 이름
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/main2',$data);
	}
	
	/**
	 * 회원 모바일 메인
	 */
	public function mmmain_backup($first_mode='')
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원 메인',
	        'nav' => array('회원관리' => '' , '회원 메인' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // 상단 회원 기본 정보를 가져온다.
	    // 세션 항목으로 처리함.
	    
	    $eventModel = new EventModel();
	    $model = new MobileTModel();
	    
	    // 구매상품 정보를 가져온다.
	    $edata['comp_cd'] = $_SESSION['comp_cd'];
	    $edata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $edata['mem_id'] = $_SESSION['user_id'];
	    $edata['mem_sno'] = $_SESSION['user_sno'];
	    $edata['event_stat'] = "00";
	    $edata['ndate'] = date('Y-m-d');
	    $edata['noti_dv'] = "01";
	    // 예약
	    $event_list_00 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    // 이용중
	    $edata['event_stat'] = "01";
	    $event_list_01 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
        // 종료됨	    
	    $edata['event_stat'] = "99";
	    $event_list_99 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    
	    // 추천상품 [대기]
	    $send_list = $model->list_sendevent_memsno($edata);
	    
	    // 회원 공지사항
	    $list_notice = $model->list_tnotice($edata);
	    
	    $gxData['comp_cd'] = $_SESSION['comp_cd'];
	    $gxData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $gxClas = new GxClas_lib($gxData);
	    
	    $today = date('Y-m-d');
	    $gx_list = $gxClas->list_gx_today_schd_user($today);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['first_mode'] = $first_mode;
	    $data['view']['event_list1'] = $event_list_01; // 예약
	    $data['view']['event_list2'] = $event_list_00; // 이용
	    $data['view']['event_list3'] = $event_list_99; // 종료
	    $data['view']['send_list'] = $send_list; // 추천상품
	    $data['view']['list_notice'] = $list_notice; // 회원 공지사항
	    $data['view']['list_gx'] = $gx_list; // GX 스케쥴
	    
	    $this->viewPageForMobile('/mobile_p/mmmain',$data);
	}
	
	/**
	 * 회원 입장하기 (QR CODE)
	 */
	public function qrcode()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '입장하기',
	        'nav' => array('입장하기' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $base_qrcode = $_SESSION['comp_cd'] . "|" . $_SESSION['bcoff_cd'] . "|" . $_SESSION['user_sno'];
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['base_qrcode'] = $base_qrcode;
	    $this->viewPageForMobile('/mobile_p/qrcode',$data);
	}
	
	public function qrcode_attd()
	{
	    $postVar = $this->request->getPost();
	    
	    $qr_div = explode("|",$postVar['qrcode_val']);
	    $set_time = time();
	    
	    if (count($qr_div) == 4)
	    {
	        $chk_qr = substr($qr_div[3],0,9);
	        $chk_qr = $chk_qr + 1;
	        $chk_time = substr($set_time,0,9);
	        
	                if ($chk_qr < $chk_time)
        {
            $return_json['msg'] = '만료된 QR코드입니다. 새로운 QR코드를 다시 스캔해주세요.';
            $return_json['result'] = 'false';
        } else
	        {
	            
	            $return_json['msg'] = '정상';
	            $return_json['result'] = 'true';
	            
	            $initVar['comp_cd'] = $qr_div[0];
	            $initVar['bcoff_cd'] = $qr_div[1];
	            $initVar['mem_sno'] = $qr_div[2];
	            
	            // QR 체크인의 경우 사용 가능한 이용권 중 첫 번째를 자동 선택
	            $attdModel = new AttdModel();
	            $ticketList = $attdModel->cur_available_membership($initVar);
	            
	            // 데이터 정리: 배열이 아닌 요소들 제거
	            if (is_array($ticketList)) {
	                $ticketList = array_filter($ticketList, function($item) {
	                    return is_array($item) && isset($item['BUY_EVENT_SNO']);
	                });
	                $ticketList = array_values($ticketList);
	            }
	            
	            $ticketInfo = null;
	            if (!empty($ticketList)) {
	                $selectedTicket = $ticketList[0]; // 첫 번째 이용권 자동 선택
	                $initVar['buy_event_sno'] = $selectedTicket['BUY_EVENT_SNO'];
	                
	                // 선택된 이용권 정보 구성
	                $remainingInfo = '';
	                if (isset($selectedTicket['CNT_LEFT']) && !empty($selectedTicket['CNT_LEFT']) && $selectedTicket['CNT_LEFT'] !== "0") {
	                    $remainingInfo = "남은 횟수: {$selectedTicket['CNT_LEFT']}회";
	                } else {
	                    $remainingDays = $this->calculateRemainingDays($selectedTicket['EXR_S_DATE'], $selectedTicket['EXR_E_DATE']);
	                    if ($remainingDays > 0) {
	                        $remainingInfo = "남은 일수: {$remainingDays}일";
	                    } else {
	                        $remainingInfo = "무제한";
	                    }
	                }
	                
	                $ticketInfo = [
	                    'ticket_id' => $selectedTicket['BUY_EVENT_SNO'],
	                    'ticket_name' => $selectedTicket['SELL_EVENT_NM'],
	                    'remaining_info' => $remainingInfo,
	                    'expire_date' => $selectedTicket['EXR_E_DATE'] ?? ''
	                ];
	            }
	            
	            $attdLib = new Attd_lib($initVar);
	            $attdResult = $attdLib->attd_run();
	            
	            $return_json['msg'] = $attdResult['msg'];
	            
	            if ($attdResult['result'] == true)
	            {
	                $return_json['result'] = 'true';
	                
	                // 성공 시 사용된 이용권 정보 포함
	                if ($ticketInfo) {
	                    $return_json['used_ticket'] = $ticketInfo;
	                    $return_json['msg'] = $ticketInfo['ticket_name'] . '이용권으로 입장이 완료되었습니다.';
	                }
	            } else
	            {
	                $return_json['result'] = 'false';
	            }
	            
	        }
	        
	        $return_json['qr_div'] = $chk_qr;
	        $return_json['chk_time'] = $chk_time;
	        } else 
    {
        $return_json['msg'] = '올바르지 않은 QR코드 형식입니다. 정확한 QR코드를 다시 스캔해주세요.';
        $return_json['result'] = 'false';
    }
	    
	    return json_encode($return_json);
	    
	}
	
	/**
	 * 회원 이용권 목록 조회 (QR 스캔용)
	 */
	public function get_member_tickets()
	{
	    $postVar = $this->request->getPost();
	    $attdModel = new AttdModel();
	    
	    if (!isset($postVar['mem_sno']) || empty($postVar['mem_sno'])) {
	        $return_json['msg'] = '회원 정보가 없습니다.';
	        $return_json['result'] = 'false';
	        return json_encode($return_json);
	    }
	    
	    $initVar['comp_cd'] = $postVar['comp_cd'] ?? $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $postVar['bcoff_cd'] ?? $_SESSION['bcoff_cd'];
	    $initVar['mem_sno'] = $postVar['mem_sno'];
	    
		$attdLib = new Attd_lib($initVar);
	    
	    // 이용중인 회원권 목록 조회
	    $ticketList = $attdModel->cur_available_membership($initVar);
	    
	    // 데이터 정리: 배열이 아닌 요소들 제거 (query 객체 등)
	    if (is_array($ticketList)) {
	        $ticketList = array_filter($ticketList, function($item) {
	            return is_array($item) && isset($item['BUY_EVENT_SNO']);
	        });
	        $ticketList = array_values($ticketList); // 인덱스 재정렬
	    }
	    
	    if (empty($ticketList)) {
	        $return_json['msg'] = '사용 가능한 이용권이 없습니다.';
	        $return_json['result'] = 'false';
	        $return_json['ticket_count'] = 0;
	        return json_encode($return_json);
	    }
	    
	    // 이용권 정보를 체크인 페이지 형식에 맞게 변환
	    $formattedTickets = [];
	    foreach ($ticketList as $ticket) {
	        $remainingInfo = '';
	        $detailInfo = '';
			$limitCheckResult = $attdLib->checkUsageLimits($ticket);
	        if ($limitCheckResult !== null) {
	            
	            $ticket['ENTER_YN']= 'Y';
	        } else{
				$ticket['ENTER_YN']= 'N';
			}
	        // CNT_LEFT가 있으면 횟수 단위 이용권
	        if (isset($ticket['CNT_LEFT']) && !empty($ticket['CNT_LEFT']) && $ticket['CNT_LEFT'] !== "0") {
	            $remainingInfo = "남은 횟수: {$ticket['CNT_LEFT']}회";
	        } else {
	            // 기간 단위 이용권 - 남은 일수 계산
	            $remainingDays = $this->calculateRemainingDays($ticket['EXR_S_DATE'], $ticket['EXR_E_DATE']);
	            if ($remainingDays > 0) {
	                $remainingInfo = "남은 일수: {$remainingDays}일";
	            } else {
	                $remainingInfo = "무제한";
	            }
	        }
	        
	        // 상세 정보 구성
	        $details = [];
	        
	        // 개월 수
	        if (!empty($ticket['USE_PROD'])) {
	            $details[] = $ticket['USE_PROD'] . '개월';
	        }
	        
	        // 횟수 (개인수업)
	        if (!empty($ticket['CLAS_CNT'])) {
	            $details[] = $ticket['CLAS_CNT'] . '회';
	        }
	        
	        // 분 (개인수업)
	        if (!empty($ticket['CLAS_MIN'])) {
	            $details[] = $ticket['CLAS_MIN'] . '분';
	        }
	        
	        // 일일 이용 횟수
	        if (!empty($ticket['USE_PER_DAY'])) {
	            $details[] = '일일 ' . $ticket['USE_PER_DAY'] . '회';
	        }
	        
		    /*
	        // 휴회기간
	        if (!empty($ticket['DOMCY_DAY'])) {
	            $details[] = '휴회기간 ' . $ticket['DOMCY_DAY'] . '일';
	        }
	        
	        // 휴회 가능 여부
	        if (empty($ticket['DOMCY_POSS_EVENT_YN']) || $ticket['DOMCY_POSS_EVENT_YN'] == 'N') {
	            $details[] = '휴회불가';
	        }
	        
	        // 휴회횟수
	        if (!empty($ticket['DOMCY_CNT'])) {
	            $details[] = '휴회횟수 ' . $ticket['DOMCY_CNT'] . '회';
	        }
	        */
			
	        // 이용요일
	        if (!empty($ticket['WEEK_SELECT']) && $ticket['WEEK_SELECT'] != "") {
	            if ($ticket['WEEK_SELECT'] == "월화수목금토일") {
	                $details[] = '이용요일: 무제한';
	            } else {
	                $details[] = '이용요일: ' . $ticket['WEEK_SELECT'];
	            }
	        } else {
	            $details[] = '이용요일: 무제한';
	        }
	        
	        $detailInfo = implode(' / ', $details);
	        
	        // GX 수업 정보 추가 (시간은 분단위까지만)
	        $gxInfo = null;
	        if (!empty($ticket['GX_SCHD_MGMT_SNO'])) {
	            $startTime = isset($ticket['CLASS_START_TIME']) ? substr($ticket['CLASS_START_TIME'], 0, 5) : '';
	            $endTime = isset($ticket['CLASS_END_TIME']) ? substr($ticket['CLASS_END_TIME'], 0, 5) : '';
	            
	            $gxInfo = [
	                'gx_clas_title' => $ticket['GX_CLAS_TITLE'] ?? '',
	                'gx_room_title' => $ticket['GX_ROOM_TITLE'] ?? '',
	                'class_start_time' => $startTime,
	                'class_end_time' => $endTime
	            ];
	        }
	        
	        // 출석 관련 정보 및 입장가능 여부 판단
	        $usageInfo = null;
	        $isAvailable = true;
	        $availabilityMessage = '입장가능';
	        
	        if (empty($ticket['GX_SCHD_MGMT_SNO'])) { // 일반 이용권인 경우
	            $usageDetails = [];
	            
	            // 일일 출석수 체크
	            if (isset($ticket['USE_PER_DAY']) && $ticket['USE_PER_DAY'] > 0) {
	                $todayCount = $ticket['오늘출석수'] ?? 0;
	                $usageDetails[] = "금일 출석수: {$todayCount}";
	                
	                if ($todayCount >= $ticket['USE_PER_DAY']) {
	                    $isAvailable = false;
	                    $availabilityMessage = '입장불가';
	                }
	            }
	            
	            // 주간 이용 체크
	            if (isset($ticket['USE_PER_WEEK']) && $ticket['USE_PER_WEEK'] > 0) {
	                if ($ticket['USE_PER_WEEK_UNIT'] == '10') {
	                    $weeklyDays = $ticket['주간이용일수'] ?? 0;
	                    $usageDetails[] = "주간이용일수: {$weeklyDays}";
	                    
	                    if ($weeklyDays >= $ticket['USE_PER_WEEK']) {
	                        $isAvailable = false;
	                        $availabilityMessage = '입장불가';
	                    }
	                } elseif ($ticket['USE_PER_WEEK_UNIT'] == '20') {
	                    $weeklyCount = $ticket['주간이용횟수'] ?? 0;
	                    $usageDetails[] = "주간이용횟수: {$weeklyCount}";
	                    
	                    if ($weeklyCount >= $ticket['USE_PER_WEEK']) {
	                        $isAvailable = false;
	                        $availabilityMessage = '입장불가';
	                    }
	                }
	            }
	            
	            if (!empty($usageDetails)) {
	                $usageInfo = implode(' / ', $usageDetails);
	            }
	        }
	        
	        $formattedTickets[] = [
	            'ticket_id' => $ticket['BUY_EVENT_SNO'],
	            'ticket_name' => $ticket['SELL_EVENT_NM'],
	            'mem_nm' => $ticket['MEM_NM'],  // 회원명 추가
	            'mem_sno' => $initVar['mem_sno'],  // 회원번호 추가
				'enter_yn' => $ticket['ENTER_YN'],
	            'remaining_info' => $remainingInfo,
	            'detail_info' => $detailInfo,
	            'expire_date' => $ticket['EXR_E_DATE'] ?? '',
	            'status' => $isAvailable ? 'active' : 'inactive',
	            'availability_message' => $availabilityMessage,
	            'gx_info' => $gxInfo,
	            'usage_info' => $usageInfo,
	            'raw_data' => $ticket // 디버깅용
	        ];
	    }
	    
	    $return_json['result'] = 'true';
	    $return_json['ticket_count'] = count($formattedTickets);
	    $return_json['tickets'] = $formattedTickets;
	    $return_json['msg'] = '이용권 목록을 조회했습니다.';
	    
		
	    return json_encode($return_json);
	}
	
	/**
	 * 전화번호로 회원 이용권 목록 조회
	 */
	public function get_member_tickets_by_telno()
	{
	    $postVar = $this->request->getPost();
	    $attdModel = new AttdModel();
	    
	    if (!isset($postVar['mem_telno']) || empty($postVar['mem_telno'])) {
	        $return_json['msg'] = '전화번호가 없습니다.';
	        $return_json['result'] = 'false';
	        return json_encode($return_json);
	    }
	    
	    $initVar['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $initVar['mem_telno'] = $postVar['mem_telno'];
	    
	    // 이용중인 회원권 목록 조회
	    $ticketList = $attdModel->cur_available_membership_by_telno($initVar);
	    
	    // 데이터 정리: 배열이 아닌 요소들 제거 (query 객체 등)
	    if (is_array($ticketList)) {
	        $ticketList = array_filter($ticketList, function($item) {
	            return is_array($item) && isset($item['BUY_EVENT_SNO']);
	        });
	        $ticketList = array_values($ticketList); // 인덱스 재정렬
	    }
	    
	    if (empty($ticketList)) {
	        $return_json['msg'] = '사용 가능한 이용권이 없습니다.';
	        $return_json['result'] = 'false';
	        $return_json['ticket_count'] = 0;
	        return json_encode($return_json);
	    }
	    
	    // 이용권 정보를 체크인 페이지 형식에 맞게 변환
	    $formattedTickets = [];
	    foreach ($ticketList as $ticket) {
	        $remainingInfo = '';
	        $detailInfo = '';
	        
	        // CNT_LEFT가 있으면 횟수 단위 이용권
	        if (isset($ticket['CNT_LEFT']) && !empty($ticket['CNT_LEFT']) && $ticket['CNT_LEFT'] !== "0") {
	            $remainingInfo = "남은 횟수: {$ticket['CNT_LEFT']}회";
	        } else {
	            // 기간 단위 이용권 - 남은 일수 계산
	            $remainingDays = $this->calculateRemainingDays($ticket['EXR_S_DATE'], $ticket['EXR_E_DATE']);
	            if ($remainingDays > 0) {
	                $remainingInfo = "남은 일수: {$remainingDays}일";
	            } else {
	                $remainingInfo = "무제한";
	            }
	        }
	        
	        // 상세 정보 구성
	        $details = [];
	        
	        // 개월 수
	        if (!empty($ticket['USE_PROD'])) {
	            $details[] = $ticket['USE_PROD'] . '개월';
	        }
	        
	        // 횟수 (개인수업)
	        if (!empty($ticket['CLAS_CNT'])) {
	            $details[] = $ticket['CLAS_CNT'] . '회';
	        }
	        
	        // 분 (개인수업)
	        if (!empty($ticket['CLAS_MIN'])) {
	            $details[] = $ticket['CLAS_MIN'] . '분';
	        }
	        
	        // 일일 이용 횟수
	        if (!empty($ticket['USE_PER_DAY'])) {
	            $details[] = '일일 ' . $ticket['USE_PER_DAY'] . '회';
	        }
	        
	        // 이용요일
	        if (!empty($ticket['WEEK_SELECT']) && $ticket['WEEK_SELECT'] != "") {
	            if ($ticket['WEEK_SELECT'] == "월화수목금토일") {
	                $details[] = '이용요일: 무제한';
	            } else {
	                $details[] = '이용요일: ' . $ticket['WEEK_SELECT'];
	            }
	        } else {
	            $details[] = '이용요일: 무제한';
	        }
	        
	        $detailInfo = implode(' / ', $details);
	        
	        // GX 수업 정보 추가 (시간은 분단위까지만)
	        $gxInfo = null;
	        if (!empty($ticket['GX_SCHD_MGMT_SNO'])) {
	            $startTime = isset($ticket['CLASS_START_TIME']) ? substr($ticket['CLASS_START_TIME'], 0, 5) : '';
	            $endTime = isset($ticket['CLASS_END_TIME']) ? substr($ticket['CLASS_END_TIME'], 0, 5) : '';
	            
	            $gxInfo = [
	                'gx_clas_title' => $ticket['GX_CLAS_TITLE'] ?? '',
	                'gx_room_title' => $ticket['GX_ROOM_TITLE'] ?? '',
	                'class_start_time' => $startTime,
	                'class_end_time' => $endTime
	            ];
	        }
	        
	        // 출석 관련 정보 및 입장가능 여부 판단
	        $usageInfo = null;
	        $isAvailable = true;
	        $availabilityMessage = '입장가능';
	        
	        if (empty($ticket['GX_SCHD_MGMT_SNO'])) { // 일반 이용권인 경우
	            $usageDetails = [];
	            
	            // 일일 출석수 체크
	            if (isset($ticket['USE_PER_DAY']) && $ticket['USE_PER_DAY'] > 0) {
	                $todayCount = $ticket['오늘출석수'] ?? 0;
	                $usageDetails[] = "금일 출석수: {$todayCount}";
	                
	                if ($todayCount >= $ticket['USE_PER_DAY']) {
	                    $isAvailable = false;
	                    $availabilityMessage = '입장불가';
	                }
	            }
	            
	            // 주간 이용 체크
	            if (isset($ticket['USE_PER_WEEK']) && $ticket['USE_PER_WEEK'] > 0) {
	                if ($ticket['USE_PER_WEEK_UNIT'] == '10') {
	                    $weeklyDays = $ticket['주간이용일수'] ?? 0;
	                    $usageDetails[] = "주간이용일수: {$weeklyDays}";
	                    
	                    if ($weeklyDays >= $ticket['USE_PER_WEEK']) {
	                        $isAvailable = false;
	                        $availabilityMessage = '입장불가';
	                    }
	                } elseif ($ticket['USE_PER_WEEK_UNIT'] == '20') {
	                    $weeklyCount = $ticket['주간이용횟수'] ?? 0;
	                    $usageDetails[] = "주간이용횟수: {$weeklyCount}";
	                    
	                    if ($weeklyCount >= $ticket['USE_PER_WEEK']) {
	                        $isAvailable = false;
	                        $availabilityMessage = '입장불가';
	                    }
	                }
	            }
	            
	            if (!empty($usageDetails)) {
	                $usageInfo = implode(' / ', $usageDetails);
	            }
	        }
	        
	        $formattedTickets[] = [
	            'ticket_id' => $ticket['BUY_EVENT_SNO'],
	            'ticket_name' => $ticket['SELL_EVENT_NM'],
	            'remaining_info' => $remainingInfo,
	            'detail_info' => $detailInfo,
	            'expire_date' => $ticket['EXR_E_DATE'] ?? '',
	            'status' => $isAvailable ? 'active' : 'inactive',
	            'availability_message' => $availabilityMessage,
	            'gx_info' => $gxInfo,
	            'usage_info' => $usageInfo,
				'mem_nm' => $ticket['MEM_NM'], // 회원명 추가
	            'mem_sno' => $ticket['MEM_SNO'], // 회원번호 추가 (체크인 처리시 필요)
	            'raw_data' => $ticket // 디버깅용
	        ];
	    }
	    
	    $return_json['result'] = 'true';
	    $return_json['ticket_count'] = count($formattedTickets);
	    $return_json['tickets'] = $formattedTickets;
	    $return_json['msg'] = '이용권 목록을 조회했습니다.';
	    
	    return json_encode($return_json);
	}
	
	/**
	 * 선택된 이용권으로 체크인 처리
	 */
	public function checkin_with_ticket()
	{
	    $postVar = $this->request->getPost();
	    
	    if (!isset($postVar['mem_sno']) || !isset($postVar['ticket_id'])) {
	        $return_json['msg'] = '필수 정보가 누락되었습니다.';
	        $return_json['result'] = 'false';
	        return json_encode($return_json);
	    }
	    
	    $initVar['comp_cd'] = $postVar['comp_cd'] ?? $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $postVar['bcoff_cd'] ?? $_SESSION['bcoff_cd'];
	    $initVar['mem_sno'] = $postVar['mem_sno'];
	    $initVar['buy_event_sno'] = $postVar['ticket_id'];
	    
	    // 체크인 전에 이용권 정보 조회 (사용 전 정보)
	    $ticketInfo = $this->getTicketInfo($initVar['comp_cd'], $initVar['bcoff_cd'], $initVar['mem_sno'], $initVar['buy_event_sno']);
	    
	    // 출석 처리
	    $attdLib = new Attd_lib($initVar);
	    $attdResult = $attdLib->attd_run();
	    
	    $return_json['msg'] = $attdResult['msg'];
	    
	    if ($attdResult['result'] == true) {
	        $return_json['result'] = 'true';
	        
	        // 성공 시 사용된 이용권 정보 포함
	        if ($ticketInfo) {
	            $return_json['used_ticket'] = $ticketInfo;
	            $return_json['msg'] = $ticketInfo['mem_nm'] . '님, ' . $ticketInfo['ticket_name'] . '이용권으로 입장이 완료되었습니다.';
	        }
	    } else {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	}
	
	/**
	 * 남은 일수 계산 헬퍼 함수
	 */
	private function calculateRemainingDays($startDate, $endDate)
	{
	    if (empty($endDate)) return 0;
	    
	    $today = new \DateTime();
	    $end = new \DateTime($endDate);
	    
	    if ($end < $today) return 0;
	    
	    $diff = $today->diff($end);
	    return $diff->days + 1;
	}
	
	/**
	 * 특정 이용권 정보 조회 헬퍼 함수
	 */
	private function getTicketInfo($comp_cd, $bcoff_cd, $mem_sno, $buy_event_sno)
	{
	    $attdModel = new AttdModel();
	    $initVar = [
	        'comp_cd' => $comp_cd,
	        'bcoff_cd' => $bcoff_cd,
	        'mem_sno' => $mem_sno,
	        'buy_event_sno' => $buy_event_sno
	    ];
	    
	    $ticketList = $attdModel->cur_available_membership($initVar);
	    
	    // 데이터 정리: 배열이 아닌 요소들 제거
	    if (is_array($ticketList)) {
	        $ticketList = array_filter($ticketList, function($item) {
	            return is_array($item) && isset($item['BUY_EVENT_SNO']);
	        });
	        $ticketList = array_values($ticketList);
	    }
	    
	    if (!empty($ticketList)) {
	        $ticket = $ticketList[0]; // 첫 번째 결과 사용
	        
	        $remainingInfo = '';
	        // CNT_LEFT가 있으면 횟수 단위 이용권
	        if (isset($ticket['CNT_LEFT']) && !empty($ticket['CNT_LEFT']) && $ticket['CNT_LEFT'] !== "0") {
	            $remainingInfo = "남은 횟수: {$ticket['CNT_LEFT']}회";
	        } else {
	            // 기간 단위 이용권 - 남은 일수 계산
	            $remainingDays = $this->calculateRemainingDays($ticket['EXR_S_DATE'], $ticket['EXR_E_DATE']);
	            if ($remainingDays > 0) {
	                $remainingInfo = "남은 일수: {$remainingDays}일";
	            } else {
	                $remainingInfo = "무제한";
	            }
	        }
	        
	        return [
	            'ticket_id' => $ticket['BUY_EVENT_SNO'],
	            'ticket_name' => $ticket['SELL_EVENT_NM'],
				'mem_nm' => $ticket['MEM_NM'],
	            'remaining_info' => $remainingInfo,
	            'expire_date' => $ticket['EXR_E_DATE'] ?? ''
	        ];
	    }
	    
	    return null;
	}
	
	public function tqrcode_attd()
	{
	    $attdModel = new AttdModel();
	    $postVar = $this->request->getPost();
	    
	    $qr_div = explode("|",$postVar['qrcode_val']);
	    $set_time = time();
	    
	    if (count($qr_div) == 4)
	    {
	        $chk_qr = substr($qr_div[3],0,9);
	        $chk_qr = $chk_qr + 1;
	        $chk_time = substr($set_time,0,9);
	        
	        if ($chk_qr < $chk_time)
	        {
	            $return_json['msg'] = 'QR을 확인해주세요.';
	            $return_json['result'] = 'false';
	        } else
	        {
	            
	            $return_json['msg'] = '정상';
	            $return_json['result'] = 'true';
	            
                // 강사 출근 데이터를 처리한다.
                // 1. 강사가 오늘 출근 했는지 카운팅을 확인한다.
                // 2. 카운팅이 없다면 오늘 출근 데이터를 insert 한다.
                // 2-1. insert 전에 지각인지 정상 출근인지 확인한다.
                
	            $nn_now = new Time('now');
	            
	            $insVar['comp_cd'] = $qr_div[0];
	            $insVar['bcoff_cd'] = $qr_div[1];
	            $insVar['mem_sno'] = $qr_div[2];
	            $insVar['attd_ymd'] = date('Ymd');
	            
	            $chk_count = $attdModel->count_tchr_attd_mgmt($insVar);
	            
	            if ($chk_count == 0)
	            {
	                $insVar['mem_id'] = $_SESSION['user_id'];
	                $insVar['mem_nm'] = $_SESSION['user_name'];
	                $insVar['mem_dv'] = "T";
	                $insVar['attd_yy'] = date('Y');
	                $insVar['attd_mm'] = date('m');
	                $insVar['attd_dd'] = date('d');
	                
	                $dotw_word = array("일","월","화","수","목","금","토",);
	                $insVar['attd_dotw'] = $dotw_word[date('w',strtotime(date(date('Y-m-d'))))];
	                $insVar['attd_hh'] = date('H');
	                
	                $base_hh = "10"; // 11시 (출근 가능시각)
	                $base_mm = "00"; // 00분 (출근 가능분)
	                $base_time = strtotime( date('Y-m-d') . " " . $base_hh . ":" . $base_mm . ":00" );
	                
	                if ($base_time > $set_time)
	                {
	                    $insVar['attd_dv'] = "00"; // 정상출근
	                    $attdResult['msg'] = "출근 하였습니다.[정상]";
	                } else
	                {
	                    $insVar['attd_dv'] = "01"; // 지각
	                    $attdResult['msg'] = "출근 하였습니다.[지각]";
	                }
	                
	                $insVar['cre_id'] = $_SESSION['user_id'];
	                $insVar['cre_datetm'] = $nn_now;
	                $insVar['mod_id'] = $_SESSION['user_id'];
	                $insVar['mod_datetm'] = $nn_now;
	                
	                $attdModel->insert_attdt_mgmt($insVar);
	                
	            } else 
	            {
	                $attdResult['msg'] = "이미 출근 하였습니다.";
	            }
	            
	            $attdResult['result'] = true;
	            
	            $return_json['msg'] = $attdResult['msg'];
	            
	            if ($attdResult['result'] == true)
	            {
	                $return_json['result'] = 'true';
	            } else
	            {
	                $return_json['result'] = 'false';
	            }
	            
	        }
	        
	        $return_json['qr_div'] = $chk_qr;
	        $return_json['chk_time'] = $chk_time;
	    } else
	    {
	        $return_json['msg'] = '정상적인 QR이 아닙니다.';
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	    
	}
	
	/**
	 * 휴회하기
	 */
	public function domcy()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '휴회하기',
	        'nav' => array('휴회하기' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // 휴회 가능 상품 및 정보를 가져온다.
	    $domcyInit['comp_cd'] = $_SESSION['comp_cd'];
	    $domcyInit['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $domcyInit['mem_sno'] = $_SESSION['user_sno'];
	    $domcyInit['type'] = "user";
	    $domcyLib = new Domcy_lib($domcyInit);
	    $poss_domcy = $domcyLib->get_poss_domcy();
	    
	    $domcy_list = $model->list_domcy_memsno($domcyInit);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['poss_domcy'] = $poss_domcy; // 휴회 가능일, 횟수
	    $data['view']['domcy_list'] = $domcy_list; // 휴회 리스트
	    $this->viewPageForMobile('/mobile_p/new_domcy2',$data);
	}
	
	/**
	 * 휴회 신청하기
	 */
	public function ajax_domcy_acppt_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    
	    $initVar['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $initVar['type'] = "user";
	    
	    if (!empty($postVar['items']) && is_array($postVar['items'])) {
	        // 먼저 모든 항목의 날짜 중복을 체크
	        $domcyModel = new \App\Models\DomcyModel();
	        $hasOverlap = false;
	        $overlapDates = [];
	        
	        foreach ($postVar['items'] as $item) {
	            // 휴회 종료일 계산 (시작일 + 사용일수 - 1)
	            $add_days = intval($item['fc_domcy_use_day']) - 1;
	            if ($add_days < 0) {
	                $domcy_e_date = date("Y-m-d", strtotime($add_days." days", strtotime($item['fc_domcy_s_date'])));
	            } else {
	                $domcy_e_date = date("Y-m-d", strtotime("+".$add_days." days", strtotime($item['fc_domcy_s_date'])));
	            }
	            
	            $checkData = [
	                'comp_cd' => $initVar['comp_cd'],
	                'bcoff_cd' => $initVar['bcoff_cd'],
	                'mem_sno' => $item['fc_domcy_mem_sno'],
	                'domcy_s_date' => $item['fc_domcy_s_date'],
	                'domcy_e_date' => $domcy_e_date
	            ];
	            
	            $overlapResult = $domcyModel->check_domcy_date_overlap($checkData);
	            
	            if ($overlapResult[0]['overlap_count'] > 0) {
	                $hasOverlap = true;
	                $overlapDates[] = $item['fc_domcy_s_date'];
	            }
	        }
	        
	        // 중복이 있으면 오류 반환
	        if ($hasOverlap) {
	            $return_json['result'] = 'false';
	            $return_json['msg'] = '다음 날짜들이 이미 신청된 휴회 기간과 중복됩니다: ' . implode(', ', array_unique($overlapDates));
	            return json_encode($return_json);
	        }
	        
	        // 중복이 없으면 모든 항목 처리
			foreach ($postVar['items'] as $item) {
				$initVar['domcy_aply_buy_sno'] = $item['fc_domcy_buy_sno'];
				$initVar['domcy_s_date'] = $item['fc_domcy_s_date'];
				$initVar['domcy_use_day'] = $item['fc_domcy_use_day'];
				$initVar['mem_sno'] = $item['fc_domcy_mem_sno'];
				
				$domcyLib = new Domcy_lib($initVar);
				$domcyLib->insert_domcy($initVar);
				// $domcyLib->update_buy_event_domcy($initVar);
			}
		}
	    
	    $return_json['result'] = 'true';
		$return_json['msg'] = '휴회 신청이 완료되었습니다.';
	    return json_encode($return_json);
	}
	
	/**
	 * 회원정보 수정
	 */
	public function mem_pre_modify()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원정보 수정(비밀번호 확인)',
	        'nav' => array('회원정보 수정' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/mem_pre_modify',$data);
	}
	
	/**
	 * 회원정보 수정
	 */
	public function mem_modify()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원정보 수정',
	        'nav' => array('회원정보 수정' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MemModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['mem_sno'] = $_SESSION['user_sno'];
	    $tinfo = $model->mobile_list_bcoff($data);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['tinfo'] = $tinfo[0];
	    $this->viewPageForMobile('/mobile_p/mem_modify',$data);
	}
	
	/**
	 * 약관동의 이력
	 */
	public function terms()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '약관동의 이력',
	        'nav' => array('약관동의 이력' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $termsModel = new MemModel();
	    
	    $tdata['mem_sno'] = $_SESSION['user_sno'];
	    $terms_list = $termsModel->mobile_get_terms_hist($tdata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['terms_list'] = $terms_list;
	    $this->viewPageForMobile('/mobile_p/new_terms',$data);
	}
	
	/**
	 * 약관 동의이력 상세 내용 보기
	 */
	public function terms_detail()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    $model = new MemModel();
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    $tdata['mem_sno'] = $_SESSION['user_sno'];
	    $tdata['terms_knd_cd'] = $postVar['terms_knd_cd'];
	    $tdata['terms_round'] = $postVar['terms_round'];
	    
	    $terms_detail = $model->mobile_get_terms_detail($tdata);
	    
	    $return_json['content'] = $terms_detail[0]['TERMS_CONTS'];
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * 메인지점변경
	 */
	public function setcomp()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '메인지점설정',
	        'nav' => array('메인지점설정' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $compModel = new MemModel();
	    
	    $ndata = array();
	    $comp_list = $compModel->mobile_list_comp_info($ndata);
	    
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['comp_list'] = $comp_list; // 지점 목록
	    $this->viewPageForMobile('/mobile_p/setcomp',$data);
	}
	
	/**
	 * 추천 아이디
	 */
	public function introd()
	{
	    
	    // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
	    
	    // 아이디 한명당 한명의 한번의 추천을 할 수 있다.
	    // 추천을 했는지에 검사를 진행한다.
	    $memModel = new MemModel();
	    
	    $mdata['c_mem_sno'] = $_SESSION['user_sno'];
	    $get_introd = $memModel->get_my_introd_info($mdata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['get_introd'] = $get_introd; // 추천한 정보
	    $this->viewPageForMobile('/mobile_p/introd',$data);
	}
	
	/**
	 * 추천받을 아이디의 존재 여부를 확인한다.
	 */
	public function ajax_introd_id_chk()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    $model = new MemModel();
	    
	    $mdata['mem_id'] = $postVar['mem_id'];
		$userId =  $_SESSION['user_id'];
	    $id_count = $model->id_chk($mdata);
	    
		if($postVar['mem_id'] == $userId)
		{
			$return_json['result'] = 'false';
			$return_json['msg'] = '다른 아이디를 입력하세요';
		}
	    else
	    {
			if ($id_count == 0){
				$return_json['result'] = 'false';
				$return_json['msg'] = '아이디가 존재하지 않습니다. 다른 아이디를 입력하세요';
			} else
			{
				$return_json['result'] = 'true';
			}
		}
	    
	    return json_encode($return_json);
	}
	
	/**
	 * 추천받을 아이디를 처리한다.
	 */
	public function ajax_introd_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $nn_now = new Time('now');
	    $postVar = $this->request->getPost();
	    $model = new MemModel();
	    
	    // 타겟 아이디에 대한 정보를 셋팅 (추천받은 아이디)
	    $tdata['mem_id'] = $postVar['mem_id'];
	    $tinfo = $model->get_mem_info_id_nocomp($tdata);
	    
	    $idata['t_mem_sno'] = $tinfo[0]['MEM_SNO'];
	    $idata['t_mem_id'] = $tinfo[0]['MEM_ID'];
	    $idata['t_mem_nm'] = $tinfo[0]['MEM_NM'];
	    
	    $idata['s_mem_sno'] = $_SESSION['user_sno'];
	    $idata['s_mem_id'] = $_SESSION['user_id'];
	    $idata['s_mem_nm'] = $_SESSION['user_name'];
	    
	    $idata['c_mem_sno'] = $_SESSION['user_sno'];
	    $idata['c_mem_id'] = $_SESSION['user_id'];
	    $idata['c_mem_nm'] = $_SESSION['user_name'];
	    
	    $idata['mod_id'] = $_SESSION['user_id'];
	    $idata['mod_datetm'] = $nn_now;
	    $idata['cre_id'] = $_SESSION['user_id'];
	    $idata['cre_datetm'] = $nn_now;
	    
	    $model->insert_mem_introd($idata);
	    
	    $return_json['result'] = 'true';
	    
	    return json_encode($return_json);
	}
	
	
	
	
	/**
	 * 탈퇴하기 (비밀번호 체크)
	 */
	public function sece_pre_member()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '탈퇴하기',
	        'nav' => array('탈퇴하기' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/sece_pre_member',$data);
	}
	
	
	
	public function sece_member()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '탈퇴하기',
	        'nav' => array('탈퇴하기' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MemModel();
	    $eventModel = new EventModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    $user_sno = $this->SpoQCahce->getCacheVar('user_sno');
	    $sdata['mem_sno'] = $user_sno;
	    $tinfo = $model->mobile_list_bcoff($sdata);
	    $sece_event_list = $eventModel->list_sece_buy_event_for_memsno($sdata);
	    
	    $event_list = array();
	    $ecount = 0;
	    foreach ($sece_event_list as $r)
	    {
	        $gdata['comp_cd'] = $r['COMP_CD'];
	        $gdata['bcoff_cd'] = $r['BCOFF_CD'];
	        $comp_name = $eventModel->get_comp_name_only($gdata);
	        $bcoff_name = $eventModel->get_bcoff_name_only($gdata);
	        
	        $event_list[$ecount] = $r;
	        $event_list[$ecount]['comp_nm'] = $comp_name[0]['COMP_NM'];
	        $event_list[$ecount]['bcoff_nm'] = $bcoff_name[0]['BCOFF_NM'];
	        
	        $ecount++;
	    }
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['event_list'] = $event_list;
	    $data['view']['tinfo'] = $tinfo[0];
	    $this->viewPageForMobile('/mobile_p/sece_member',$data);
	}
	
	/**
	 * 회원 탈퇴하기
	 * @param string $mem_sno
	 */
	public function sece_member_proc($mem_sno="")
	{
	    if ($mem_sno == "")
	    {
	        scriptAlert("잘못된 접근입니다. 로그아웃 됩니다.");
	        scriptLocation('/login/errlogout');
	    }
	    
	    // mem_id
	    // sece_date
	    $nn_now = new Time('now');
	    $model = new MemModel();
	    // 강사 아이디로 온것을 한번 더 검사하여 MEM_SNO 화 해서 처리함.
	    
	    // mem_info_detl_tbl 을 업데이트함
	    // END_DATETM : sec_date . " 01:01:01";
	    // MOD_ID : $_SESSION['user_id'];
	    // MOD_DATETM : $nn_now
	    // USE_YN : N
	    
	    $mdData['mem_sno'] = $mem_sno;
	    $mdData['end_datetm'] = $nn_now;
	    $mdData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
	    $mdData['mod_datetm'] = $nn_now;
	    $mdData['use_yn'] = "N";
	    $model->update_member_sece_info_detal($mdData);
	    
	    // mem_main_info_tbl 을 업데이트함
	    // MOD_ID : $_SESSION['user_id'];
	    // MOD_DATETM : $nn_now
	    // CONN_POSS_YN : N
	    // USE_YN : N
	    // SECE_DATETM : sec_date . " 01:01:01";
	    
	    $mmData['mem_sno'] = $mem_sno;
	    $mmData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
	    $mmData['mod_datetm'] = $nn_now;
	    $mmData['conn_poss_yn'] = "N";
	    $mmData['use_yn'] = "N";
	    $mmData['sece_datetm'] = $nn_now;
	    $model->update_member_sece_info($mmData);
	    
	    scriptLocation("/login/errlogout");
	}
	
	
	
	/**
	 * 메인지점변경
	 */
	public function chcomp()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '메인지점변경',
	        'nav' => array('메인지점변경' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $compModel = new MemModel();
	    
	    $ndata['mem_sno'] = $_SESSION['user_sno'];
	    $comp_list = $compModel->mobile_list_comp_info_mem_sno($ndata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['comp_list'] = $comp_list; // 지점 목록
	    $this->viewPageForMobile('/mobile_p/new_chcomp',$data);
	}
	
	/**
	 * 메인지점 설정 처리
	 * @param string $comp_cd
	 * @param string $bcoff_cd
	 */
	public function setcomp_proc($comp_cd,$bcoff_cd)
	{
	    $modelMem = new MemModel();
	    $nn_now = new Time('now');
	    
	    try {
	        // 현재 설정된 지점 정보 가져오기
	        $currentData['mem_sno'] = $_SESSION['user_sno'];
	        $currentInfo = $modelMem->mobile_get_comp_info($currentData);
	        $currentBcoffName = '';
	        if (count($currentInfo) > 0) {
	            $currentBcoffData['bcoff_cd'] = $currentInfo[0]['SET_BCOFF_CD'];
	            $currentBcoffInfo = $modelMem->mobile_get_comp_info_for_bcoff_cd($currentBcoffData);
	            if (count($currentBcoffInfo) > 0) {
	                $currentBcoffName = $currentBcoffInfo[0]['BCOFF_NM'];
	            }
	        }
	        
	        // 새로 설정할 지점 정보 가져오기
	        $newBcoffData['bcoff_cd'] = $bcoff_cd;
	        $newBcoffInfo = $modelMem->mobile_get_comp_info_for_bcoff_cd($newBcoffData);
	        $newBcoffName = '';
	        if (count($newBcoffInfo) > 0) {
	            $newBcoffName = $newBcoffInfo[0]['BCOFF_NM'];
	        }
	        
	        // 설정된 회사코드, 지점코드를 업데이트 한다.
	        $udata['mem_sno'] = $_SESSION['user_sno'];
	        $udata['set_comp_cd'] = $comp_cd;
	        $udata['set_bcoff_cd'] = $bcoff_cd;
	        $udata['mod_id'] = $_SESSION['user_id'];
	        $udata['mod_datetm'] = $nn_now;
	        
	        $updateResult = $modelMem->update_mem_set_comp($udata);
	        
	        // 설정된 회사코드, 지점코드에 정보가 있는지를 체크 한다.
	        $edata['comp_cd'] = $comp_cd;
	        $edata['bcoff_cd'] = $bcoff_cd;
	        $edata['mem_sno'] = $_SESSION['user_sno'];
	        $isBcoffCount = $modelMem->mobile_chk_bcoff_exist_count($edata);
	        
	        // 만약에 없다면 해당 지점에 회원 정보를 생성한다.
	        if ($isBcoffCount == 0)
	        {
	            $gData['mem_sno'] = $_SESSION['user_sno'];
	            $get_main_info = $modelMem->mobile_get_comp_info($gData);
	            
	            $iData['mem_sno'] = $get_main_info[0]['MEM_SNO'];
	            $iData['comp_cd'] = $comp_cd;
	            $iData['bcoff_cd'] = $bcoff_cd;
	            $iData['mem_id'] = $get_main_info[0]['MEM_ID'];
	            $iData['mem_nm'] = $get_main_info[0]['MEM_NM'];
	            $iData['mem_stat'] = "00"; //가입회원
	            $iData['qr_cd'] = "";
	            $iData['bthday'] = $get_main_info[0]['BTHDAY'];
	            $iData['mem_gendr'] = $get_main_info[0]['MEM_GENDR'];
	            $iData['mem_telno'] = $get_main_info[0]['MEM_TELNO'];
	            
	            $denc_telno = $this->denc_tel($get_main_info[0]['MEM_TELNO_ENC']);
	            $re_phone_num = $this->enc_phone(put_num($denc_telno));
	            $iData['mem_telno_enc'] = $re_phone_num['enc'];
	            $iData['mem_telno_mask'] = $re_phone_num['mask'];
	            $iData['mem_telno_short'] = $re_phone_num['short'];
	            $iData['mem_telno_enc2'] = $re_phone_num['enc2'];
	            
	            $iData['mem_addr'] = $get_main_info[0]['MEM_ADDR'];
	            $iData['mem_main_img'] = "";
	            $iData['mem_thumb_img'] = "";
	            $iData['tchr_posn'] = "";
	            $iData['ctrct_type'] = "";
	            $iData['tchr_simp_pwd'] = "";
	            $iData['mem_dv'] = "M"; // 회원
	            $iData['jon_place'] = "02"; //인터넷
	            $iData['jon_datetm'] = $nn_now;
	            $iData['reg_place'] = "";
	            $iData['reg_datetm'] = "";
	            $iData['cre_id'] = $_SESSION['user_id'];
	            $iData['cre_datetm'] = $nn_now;
	            $iData['mod_id'] = $_SESSION['user_id'];
	            $iData['mod_datetm'] = $nn_now;
	            
	            $modelMem->insert_mem_info_detl_tbl($iData);
	        }
	        
	        $_SESSION['comp_cd'] = $comp_cd;
	        $_SESSION['bcoff_cd'] = $bcoff_cd;
	        
	        $nnData['bcoff_cd'] = $bcoff_cd;	    
	        $get_nm = $modelMem->mobile_get_comp_info_for_bcoff_cd($nnData);
	        
	        $_SESSION['comp_nm'] = $get_nm[0]['BCOFF_NM'];
	        $_SESSION['bcoff_nm'] = $get_nm[0]['COMP_NM'];
	        
	        $_SESSION['site_type'] = 'mmlogin';
	        
	        // 성공 응답
	        $return_json['result'] = 'true';
	        $return_json['msg'] = "메인지점이 '{$currentBcoffName}'에서 '{$newBcoffName}'으로 변경되었습니다.";
	        $return_json['current_bcoff'] = $currentBcoffName;
	        $return_json['new_bcoff'] = $newBcoffName;
	        $return_json['redirect_url'] = "/api/mmmain/1";
	        
	        return json_encode($return_json);
	        
	    } catch (Exception $e) {
	        // 실패 응답
	        $return_json['result'] = 'false';
	        $return_json['msg'] = "메인지점 설정에 실패했습니다. 다시 시도해 주세요.";
	        $return_json['error'] = $e->getMessage();
	        
	        return json_encode($return_json);
	    }
	}
	
	/**
	 * 추천상품
	 */
	public function event_reco()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = MenuHelper::getMenuData($this->request);    
	    
	    $model = new MobileTModel();
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['mem_sno'] = $_SESSION['user_sno'];
	    
	    $list_send1 = $model->list_sendevent_memsno($data);
	    $list_send2 = $model->list_sendevent_memsno_01($data);
	    
	    // 2단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cateModel = new CateModel();
	    $cate1nm = $cateModel->disp_cate1($cateData);
	    $cate2nm = $cateModel->disp_cate2($cateData);
	    
	    $cate2_nm = array();
	    
	    foreach($cate1nm as $c1) :
	    foreach($cate2nm as $c2) :
	    if ($c2['1RD_CATE_CD'] == $c1['1RD_CATE_CD']) :
	    $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
	    endif;
	    endforeach;
	    endforeach;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['list_send1'] = $list_send1; // 추천상품 현황
	    $data['view']['list_send2'] = $list_send2; // 추천상품 구매 현황
	    $data['view']['cate_nm'] = $cate2_nm;
	    $this->viewPageForMobile('/mobile_p/new_event_reco',$data);
	}
	
	/**
	 * 상품구매
	 */
	public function event_buy()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = MenuHelper::getMenuData($this->request);    
	    
	    $model = new MobileTModel();
	    
	    // 상품 구매시 구매할수 있는 출입 조건을 가져온다.
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['mem_sno'] = $_SESSION['user_sno'];
	    $mem_acc = $model->get_mem_acc($data);
	    
	    if ( count($mem_acc) > 0)
	    {
	        $_SESSION['acc_dv'] = $mem_acc[0]['ACC_RTRCT_DV'];
	        $_SESSION['acc_mthd'] = $mem_acc[0]['ACC_RTRCT_MTHD'];
	    } else 
	    {
	        $_SESSION['acc_dv'] = "";
	        $_SESSION['acc_mthd'] = "";
	    }
	    
	    // 1단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cateModel = new CateModel();
	    $cate1nm = $cateModel->disp_cate1($cateData);
	    $cate2nm = $cateModel->disp_cate2($cateData);
	    
	    $cate1_nm = array();
	    foreach($cate1nm as $c1) :
	    $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
	    endforeach;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['cate1_nm'] = $cate1_nm; // 1단계 카테고리 가져오기
	    $this->viewPageForMobile('/mobile_p/event_buy',$data);
	}
	
	/**
	 * 상품구매 (개월수로 구매하기 1단계) - 대분류 선택 후
	 */
	public function event_buy1($cate1)
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품구매',
	        'nav' => array('상품구매' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    
	    // 2단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
		$cateData['cate1'] = $cate1;

		$cateModel = new CateModel();
	    $prods = $cateModel->get_prod_by_cate1($cateData);
	    
	    // $cateModel = new CateModel();
	    // $cate2nm = $cateModel->disp_cate2($cateData);
	    
	    // $cate2_nm = array();
	    // foreach($cate2nm as $c2) :
	    //    if ($c2['1RD_CATE_CD'] == $cate1) :
	    //        $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
	    //    endif;
	    // endforeach;
	    
		// if ($cate1 == '2000')
	    if ($cate1 =='PRVN')
	    {
	       scriptLocation('/api/event_buy1_1/'.$cate1.'/0');    
	    }
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['cate1'] = $cate1; // 1단계 카테고리 가져오기
		$data['view']['prods'] = $prods;
	    // $data['view']['cate2_nm'] = $cate2_nm; // 2단계 카테고리 가져오기
	    $this->viewPageForMobile('/mobile_p/event_buy1',$data);
	}
	
	/**
	 * 상품구매 (개월수로 구매하기 2단계) - 개월수 선택 후
	 */
	public function event_buy1_1($cate1,$prod)
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품구매',
	        'nav' => array('상품구매' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    $sdata['comp_cd'] = $_SESSION['comp_cd'];
	    $sdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $sdata['1rd_cate_cd'] = $cate1;
	    $sdata['use_prod'] = $prod;
	    
	    // if ($cate1 == '2000')
	    if ($cate1 =='PRVN')
	    {
	        $sdata['use_type'] = "P";
	    } else 
	    {
	        $sdata['use_type'] = "H";
	    }
	    
	    $sdata['mem_disp_yn'] = "Y";
	    
	    // 2단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $cateData['cate1'] = $cate1;
	    $cateModel = new CateModel();
	    $cate1nm = $cateModel->disp_cate1($cateData);
	    $cate2nm = $cateModel->disp_cate2($cateData);
	    $prods = $cateModel->get_prod_by_cate1($cateData);
	    
	    $cate2_nm = array();
	    
	    foreach($cate1nm as $c1) :
	    foreach($cate2nm as $c2) :
	    if ($c2['1RD_CATE_CD'] == $c1['1RD_CATE_CD']) :
	    $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
	    endif;
	    endforeach;
	    endforeach;
	    
	    $list_event = $model->list_buy_event($sdata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    
	    $data['view']['cate1'] = $cate1;
	    $data['view']['cate_nm'] = $cate2_nm;
		$data['view']['prods'] = $prods;
	    $data['view']['list_event'] = $list_event; // 1단계 카테고리 가져오기
	    $this->viewPageForMobile('/mobile_p/new_event_buy1_1',$data);
	}
	
	
	
	/**
	 * 상품구매 (입장제한으로 구매하기 1단계)
	 */
	public function event_buy_info($sell_event_sno='',$send_sno="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품구매',
	        'nav' => array('상품구매' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $memModel = new MemModel();
	    $eventModel = new EventModel();
	    $lockrModel = new LockrModel();
	    $sendModel = new SendModel();
	    
	    $mem_sno = $_SESSION['user_sno'];
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    
	    $memData['comp_cd'] = $_SESSION['comp_cd'];
	    $memData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $memData['mem_sno'] = $mem_sno;
	    $get_mem_info = $memModel->get_mem_info_mem_sno($memData);
	    $mem_info = $get_mem_info[0];
	    
	    $eventData = $memData;
	    $eventData['sell_event_sno'] = $sell_event_sno;
	    $get_event_info = $eventModel->get_event($eventData);
	    $event_info = $get_event_info[0];
	    
	    if ($send_sno == '')
	    {
	        $event_info['SEND_EVENT_SNO'] = "";
	        $event_info['ADD_SRVC_EXR_DAY'] = 0;
	        $event_info['ADD_SRVC_CLAS_CNT'] = 0;
	        
	        $event_info['PTCHR_NM'] = "";
	        $event_info['STCHR_NM'] = "";
	        
	        $event_info['ORI_SELL_AMT'] = $event_info['SELL_AMT'];
	    } else 
	    {
	        $sendData['comp_cd'] = $_SESSION['comp_cd'];
	        $sendData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	        $sendData['send_event_mgmt_sno'] = $send_sno;
	        
	        $getSendInfo = $sendModel->get_send_event_mgmt($sendData);
	        
	        $event_info['SEND_EVENT_SNO'] = $getSendInfo[0]['SEND_EVENT_MGMT_SNO'];
	        $event_info['ADD_SRVC_EXR_DAY'] = $getSendInfo[0]['ADD_SRVC_EXR_DAY'];
	        $event_info['ADD_SRVC_CLAS_CNT'] = $getSendInfo[0]['ADD_SRVC_CLAS_CNT'];
	        
	        $event_info['PTCHR_NM'] = $getSendInfo[0]['PTCHR_NM'];
	        $event_info['STCHR_NM'] = $getSendInfo[0]['STCHR_NM'];
	        
	        $event_info['DOMCY_DAY'] = $getSendInfo[0]['DOMCY_DAY'];
	        $event_info['DOMCY_CNT'] = $getSendInfo[0]['DOMCY_CNT'];
	        
	        $event_info['SELL_AMT'] = $getSendInfo[0]['SELL_AMT'];
	        $event_info['ORI_SELL_AMT'] = $getSendInfo[0]['ORI_SELL_AMT'];
	    }
	    
	    $locker_data['comp_cd'] = $_SESSION['comp_cd'];
	    $locker_data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $locker_data['lockr_knd'] = $event_info['LOCKR_KND'];
	    $locker_data['mem_sno'] = $mem_info['MEM_SNO'];
	    
	    $get_use_locker_info = $lockrModel->select_lockr_room($locker_data);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['get_use_locker_info'] = $get_use_locker_info;
	    $data['view']['mem_info'] = $mem_info;
	    $data['view']['event_info'] = $event_info;
	    $this->viewPageForMobile('/mobile_p/new_event_buy_info',$data);
	}
	
	/**
	 * 구매상품
	 */
	public function event_list()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '구매상품',
	        'nav' => array('구매상품' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // 상단 회원 기본 정보를 가져온다.
	    // 세션 항목으로 처리함.
	    
	    $eventModel = new EventModel();
	    $model = new MobileTModel();
	    
	    // 구매상품 정보를 가져온다.
	    $edata['comp_cd'] = $_SESSION['comp_cd'];
	    $edata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $edata['mem_id'] = $_SESSION['user_id'];
	    $edata['mem_sno'] = $_SESSION['user_sno'];
	    // 예약
	    $edata['event_stat'] = "00";
	    $event_list_00 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    // 이용중
	    $edata['event_stat'] = "01";
	    $event_list_01 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    // 종료됨
	    $edata['event_stat'] = "99";
	    $event_list_99 = $eventModel->list_buy_event_user_id_event_stat_for_mobile($edata);
	    
	    // 2단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cateModel = new CateModel();
	    $cate1nm = $cateModel->disp_cate1($cateData);
	    $cate2nm = $cateModel->disp_cate2($cateData);
	    
	    $cate2_nm = array();
	    
	    foreach($cate1nm as $c1) :
	    foreach($cate2nm as $c2) :
	    if ($c2['1RD_CATE_CD'] == $c1['1RD_CATE_CD']) :
	    $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
	    endif;
	    endforeach;
	    endforeach;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['cate_nm'] = $cate2_nm; // 카테고리
	    $data['view']['event_list1'] = $event_list_01; // 예약
	    $data['view']['event_list2'] = $event_list_00; // 이용
	    $data['view']['event_list3'] = $event_list_99; // 종료
	    $this->viewPageForMobile('/mobile_p/new_event_list',$data);
	}
	
	/**
	 * 모바일 결제를 위한 사전 거래 처리
	 */
	public function ajax_pre_event_buy_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    $model = new MobileTModel();
	    $vanModel = new VanModel();
	    $nn_now = new Time('now');
	    $amasno = new Ama_sno();
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    
	    $pVar['pay_comp_cd'] = $_SESSION['comp_cd'];
	    $pVar['pay_bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $pVar['pay_mem_sno'] = $postVar['mem_sno'];
	    $pVar['pay_sell_sno'] = $postVar['sell_event_sno'];
	    
	    $paylib = new Pay_lib($pVar);
	    $chk_result = $paylib->buy_run_pre_check();
	    
	    if($chk_result['result'] == false)
	    {
	        $return_json['msg'] = $chk_result['result_msg'];
	        $return_json['result'] = 'false';
	        return json_encode($return_json);
	    }
	    
	    $paymt_van_sno = $amasno->create_paymt_mgmt_sno();
	    $appno_sno = $amasno->create_appno_sno();
	    
	    $vdata['paymt_van_sno'] = $paymt_van_sno;
	    $vdata['sell_event_sno'] = $postVar['sell_event_sno'];
	    $vdata['buy_event_sno'] = "";
	    $vdata['send_event_sno'] = $postVar['send_event_sno'];
	    $vdata['comp_cd'] = $_SESSION['comp_cd'];
	    $vdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $vdata['mem_id'] = $postVar['mem_id'];
	    $vdata['appno_sno'] = $appno_sno;
	    $vdata['appno'] = ""; // 모바일 승인번호 ????
	    $vdata['paymt_amt'] = put_num($postVar['pay_card_amt']);
	    $vdata['paymt_stat'] = "00";
	    $vdata['paymt_date'] = date('Y-m-d');
	    $vdata['refund_date'] = "";
	    
	    $vdata['cre_id'] = $_SESSION['user_id'];
	    $vdata['cre_datetm'] = $nn_now;
	    $vdata['mod_id'] = $_SESSION['user_id'];
	    $vdata['mod_datetm'] = $nn_now;
	    
	    $vanModel->insert_van_direct_hist($vdata);
	    
	    $initVar['pay_comp_cd']     = $_SESSION['comp_cd'];
	    $initVar['pay_bcoff_cd']    = $_SESSION['bcoff_cd'];
	    $initVar['pay_mem_sno']     = $postVar['mem_sno'];
	    $initVar['pay_mem_id']      = $postVar['mem_id'];
	    $initVar['pay_mem_nm']      = $postVar['mem_nm'];
	    $initVar['pay_sell_sno']    = $postVar['sell_event_sno'];
	    $initVar['pay_send_sno'] 	= $postVar['send_event_sno'];
	    $initVar['pay_appno_sno']   = $appno_sno;
	    $initVar['pay_appno']       = $postVar['van_appno'];
	    $initVar['pay_card_amt']    = put_num($postVar['pay_card_amt']);
	    $initVar['pay_acct_amt']    = put_num($postVar['pay_acct_amt']);
	    $initVar['pay_acct_no']     = ""; //$postVar['pay_acct_no'];
	    $initVar['pay_cash_amt']    = put_num($postVar['pay_cash_amt']);
	    $initVar['pay_misu_amt']    = put_num($postVar['pay_misu_amt']);
	    $initVar['pay_exr_s_date']  = $postVar['pay_exr_s_date'];
	    $initVar['pay_real_sell_amt'] = put_num($postVar['pay_real_sell_amt']);
	    
	    // 라커 추가
	    $initVar['pay_lockr_no']        = $postVar['pay_lockr_no'];
	    $initVar['pay_lockr_gendr_set'] = $postVar['pay_lockr_gendr_set'];
	    $initVar['pay_issue'] = $postVar['pay_issue'];
	    
	    //사전거래 90 / 거래완료 00 / 거래취소 01
	    $initVar['pay_stat'] = "90";
	    
	    $initVar['cre_id'] = $_SESSION['user_id'];
	    $initVar['cre_datetm'] = $nn_now;
	    $initVar['mod_id'] = $_SESSION['user_id'];
	    $initVar['mod_datetm'] = $nn_now;
	    
	    $model->insert_paymt_mobile($initVar);
	    
	    $return_json['sno'] = $appno_sno;
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	
	public function mobile_pay($pay_appno_sno = "")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '구매내역',
	        'nav' => array('구매내역' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    if ($pay_appno_sno == '')
	    {
	        scriptAlert("잘못된 접근입니다.");
	        echo "<script>history.back();</script>";
	    }
	    
	    $model = new MobileTModel();
	    
	    $gdata['pay_comp_cd'] = $_SESSION['comp_cd'];
	    $gdata['pay_bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $gdata['pay_mem_sno'] = $_SESSION['user_sno'];
	    $gdata['pay_mem_id'] = $_SESSION['user_id'];
	    $gdata['pay_appno_sno'] = $pay_appno_sno;
	    
	    $get_info = $model->get_paymt_mobile($gdata);
	    
	    $memModel = new MemModel();
	    
	    // 회원의 가맹점 이름을 가져온다.
	    $gdata1['comp_cd'] = $_SESSION['comp_cd'];
	    $gdata1['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $get_comp_info = $memModel->mobile_get_bcoff_nm($gdata1);
	    
	    $mdata['comp_cd'] = $_SESSION['comp_cd'];
	    $mdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $mdata['mem_sno'] = $_SESSION['user_sno'];
	    $get_mem_info = $memModel->get_mem_info_mem_sno($mdata);
	    
	    
	    
	    /*
	    $postVar = $this->request->getPost();
	    
	    $initVar['pay_comp_cd']     = $_SESSION['comp_cd'];
	    $initVar['pay_bcoff_cd']    = $_SESSION['bcoff_cd'];
	    $initVar['pay_mem_sno']     = $postVar['mem_sno'];
	    $initVar['pay_mem_id']      = $postVar['mem_id'];
	    $initVar['pay_mem_nm']      = $postVar['mem_nm'];
	    $initVar['pay_sell_sno']    = $postVar['sell_event_sno'];
	    $initVar['pay_send_sno'] 	= $postVar['send_event_sno'];
	    $initVar['pay_appno_sno']   = $postVar['van_appno_sno'];
	    $initVar['pay_appno']       = $postVar['van_appno'];
	    $initVar['pay_card_amt']    = put_num($postVar['pay_card_amt']);
	    $initVar['pay_acct_amt']    = put_num($postVar['pay_acct_amt']);
	    $initVar['pay_acct_no']     = $postVar['pay_acct_no'];
	    $initVar['pay_cash_amt']    = put_num($postVar['pay_cash_amt']);
	    $initVar['pay_misu_amt']    = put_num($postVar['pay_misu_amt']);
	    $initVar['pay_exr_s_date']  = $postVar['pay_exr_s_date'];
	    $initVar['pay_real_sell_amt'] = put_num($postVar['pay_real_sell_amt']);
	    
	    // 라커 추가
	    $initVar['pay_lockr_no']        = $postVar['pay_lockr_no'];
	    $initVar['pay_lockr_gendr_set'] = $postVar['pay_lockr_gendr_set'];
	    
	    
	    [ 잠실점 ]
	    sc_mid : wpgymjs200
	    sc_signeky : NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09
	    
	    [ 역삼점 ]
	    sc_mid : wpgym200ys
	    sc_signeky : clNSQTgxa2lLZHdMTG1DOEVaME5QZz09
	    */
	    
	    
	    
	    $sc_mid = ""; // 상점 아이디
	    $sc_signkey = ""; // signkey
	    $od_id = $get_info[0]['PAY_APPNO_SNO']; // "1234567890123456"; // 가맹점 주문번호 ( 가맹점에서 직접 설정 )
	    $price = $get_info[0]['PAY_CARD_AMT']; //"30000"; // 결제할 금액
	    
	    // test
	    $price = 1000;
	    
	    $set_dev = "D"; // D 개발용 / P 운영용
	    
	    if ($set_dev == "D")
	    {
	        // 개발용
	        $call_url="https://tmobile.paywelcome.co.kr";
	        $mid = "welcometst";
	        $signKey = "QjZXWDZDRmxYUXJPYnMvelEvSjJ5QT09";
	    } else 
	    {
	        // 운영용
	        $call_url="https://mobile.paywelcome.co.kr";
	        $mid = $sc_mid;
	        $signKey = $sc_signkey;
	    }
	    
	    $SignatureUtil = new StdPayUtil();
	    
	    $timestamp = $SignatureUtil->getTimestamp();
	    
	    $oid = $od_id; // 가맹점 주문번호
	    $cardNoInterestQuota = ""; // 카드 분담 무이자 여부 설정(별도 카드사와 계약한 가맹점에서 직접 설정 예시: 11-2:3,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4)
	    $cardQuotaBase = "2:3:4";  // 가맹점에서 사용할 할부 개월수 설정
	    
	    //###################################
	    // 2. 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
	    //###################################
	    $mKey = $SignatureUtil->makeHash($signKey, "sha256");
	    
	    $params = array(
	        "mkey" => $mKey,
	        "P_AMT" => $price,
	        "P_OID" => $oid,
	        "P_TIMESTAMP" => $timestamp
	    );
	    
	    $sign = $SignatureUtil->makeSignature($params, "sha256");
	    
	    /* 기타 */
	    //$siteDomain = "https://jamesgym.kr/pay/mobile"; //가맹점 도메인 입력
	    $siteDomain = getenv('SpoQ_PAY_SITEDOMAIN');
	    
	    $_SESSION['ss_od_id'] = '';
	    $_SESSION['ss_price'] = '';
	    $_SESSION['ss_sp_uid'] = '';
	    $_SESSION['ss_pr_method'] = '';
	    
	    $info['od_id'] = $od_id;
	    $info['mid'] = $mid;
	    $info['oid'] = $oid;
	    $info['price'] = $price;
	    $info['user_name'] = $get_info[0]['PAY_MEM_NM'];
	    $info['p_mname'] = $get_comp_info[0]['BCOFF_NM'];
	    $info['p_goods'] = "상품결제";
	    $info['user_hp'] = disp_phone($get_mem_info[0]['MEM_TELNO']);
	    $info['user_email'] = "";
	    $info['siteDomain'] = $siteDomain;
	    $info['timestamp'] = $timestamp;
	    $info['sign'] = $sign;
	    $info['call_url'] = $call_url;
	    
	    /*
	    if ($get_info[0]['PAY_SEND_SNO'] == '')
	    {
	        $info['p_noti'] = $info['oid']."-".$_SESSION['user_sno'];
	    } else 
	    {
	        $info['p_noti'] = $info['oid']."-".$_SESSION['user_sno']."-".$get_info[0]['PAY_SEND_SNO'];
	    }
	    */
	    $info['p_noti'] = $info['oid']."-".$_SESSION['user_sno'];
	    
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['info'] = $info; // 결제정보
	    $this->viewPageForMobile('/mobile_p/mobile_pay',$data);
	}
	
	/**
	 * 결제결과
	 */
	public function nextUrl()
	{
	    $session = session();
	    $nn_now = new Time('now');
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '구매내역',
	        'nav' => array('구매내역' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    $memModel = new MemModel();
	    
	    $postVar = $this->request->getPost();
	    
	    
	    /*
	     * P_STATUS : 00 성공 / 01 : 실패
	     * P_RMESG1 : 메세지 EUC_KR
	     * P_RMESG2
	     * P_TID : 결제승인 번호
	     * P_REQ_URL
	     * P_NOTI : PAY_APPNO_SNO-PAY_MEM_SNO
	     * P_AMT : PAY_CARD_AMT
	     */
	    
	        $exp_noti = explode('-', $postVar['P_NOTI']);
	        
	        $gdata['pay_appno_sno'] = $exp_noti[0];
	        $gdata['pay_mem_sno'] = $exp_noti[1];
	        
	        $get_paymt_info = $model->get_paymt_mobile_paysno($gdata);
	        
	        if (count($get_paymt_info) > 0)
	        {
	            // 쿠키와 세션이 끊기기 때문에 다시 쿠키와 세션을 생성한다.
	            
	            $sesMake['user_sno']    = $get_paymt_info[0]['PAY_MEM_SNO']; //user sno
	            $sesMake['user_name']   = $get_paymt_info[0]['PAY_MEM_NM']; //이름
	            $sesMake['user_id']     = $get_paymt_info[0]['PAY_MEM_ID']; // 아이디
	            $sesMake['mem_dv']      = "M"; // 회원/강사 구분
	            
	            // 임시
	            $sesMake['comp_cd']      = $get_paymt_info[0]['PAY_COMP_CD'];
	            $sesMake['bcoff_cd']     = $get_paymt_info[0]['PAY_BCOFF_CD'];
	            
	            $get_comp_nm = $memModel->mobile_get_comp_nm($sesMake);
	            $get_bcoff_nm = $memModel->mobile_get_bcoff_nm($sesMake);
	            
	            $sesMake['comp_nm'] = $get_comp_nm[0]['COMP_NM'];
	            $sesMake['bcoff_nm'] = $get_bcoff_nm[0]['BCOFF_NM'];
	            
	            $sesMake['site_type']   = "mmlogin";
	            
	            $session->set($sesMake);
	            
	            $this->user_sno = $get_paymt_info[0]['PAY_MEM_SNO'];
	            $this->SpoQ_set_cookie();
	            
	        }
	        
	        if ($postVar['P_STATUS'] == "00")
	        {
// 	            _vardump($postVar);
// 	            echo iconv("EUC-KR","UTF-8",$postVar['P_RMESG1']);
	            // 최종 결재된 가격을 비교한다.
// 	            if ($postVar['P_AMT'] != put_num($get_paymt_info[0]['PAY_CARD_AMT']))
// 	            {
// 	                scriptAlert("결재 금액에 오류가 있어서 결재가 실패 하였습니다.");
// 	                $url = "/api/mmmain";
// 	                scriptLocation($url);
// 	            }
                
                $arr_post['P_MID'] = "welcometst"; // substr($postVar['P_TID'],10,10);
                $arr_post['P_TID'] = $postVar['P_TID'];
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $postVar['P_REQ_URL']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_post);
                $data = curl_exec($ch);
                if (curl_error($ch)) {
                    exit('CURL Error('.curl_errno( $ch ).') '. curl_error($ch));
                }
                curl_close($ch);
                
                $json = array();
                
                parse_str($data, $json);
                
//                 _vardump($json);
                
//                 echo "<br /> P_RMESG1 : ";
//                 echo iconv("EUC-KR","UTF-8",$json['P_RMESG1']);
//                 echo "<br /> P_UNAME : ";
//                 echo iconv("EUC-KR","UTF-8",$json['P_UNAME']);
//                 echo "<br /> P_MNAME : ";
//                 echo iconv("EUC-KR","UTF-8",$json['P_MNAME']);
//                 echo "<br /> P_FN_NM : ";
//                 echo iconv("EUC-KR","UTF-8",$json['P_FN_NM']);

                if ($json['P_STATUS'] == "00")
                {
                    $initVar['pay_comp_cd']     = $_SESSION['comp_cd'];
                    $initVar['pay_bcoff_cd']    = $_SESSION['bcoff_cd'];
                    $initVar['pay_mem_sno']     = $get_paymt_info[0]['PAY_MEM_SNO'];
                    $initVar['pay_mem_id']      = $get_paymt_info[0]['PAY_MEM_ID'];
                    $initVar['pay_mem_nm']      = $get_paymt_info[0]['PAY_MEM_NM'];
                    $initVar['pay_sell_sno']    = $get_paymt_info[0]['PAY_SELL_SNO'];
                    $initVar['pay_send_sno'] 	= $get_paymt_info[0]['PAY_SEND_SNO'];
                    $initVar['pay_appno_sno']   = $get_paymt_info[0]['PAY_APPNO_SNO'];
                    $initVar['pay_appno']       = $get_paymt_info[0]['PAY_APPNO'];
                    $initVar['pay_card_amt']    = put_num($get_paymt_info[0]['PAY_CARD_AMT']);
                    $initVar['pay_acct_amt']    = put_num($get_paymt_info[0]['PAY_ACCT_AMT']);
                    $initVar['pay_acct_no']     = $get_paymt_info[0]['PAY_ACCT_NO'];
                    $initVar['pay_cash_amt']    = put_num($get_paymt_info[0]['PAY_CASH_AMT']);
                    $initVar['pay_misu_amt']    = put_num($get_paymt_info[0]['PAY_MISU_AMT']);
                    $initVar['pay_exr_s_date']  = $get_paymt_info[0]['PAY_EXR_S_DATE'];
                    $initVar['pay_real_sell_amt'] = put_num($get_paymt_info[0]['PAY_REAL_SELL_AMT']);
                    
                    // 라커 추가
                    $initVar['pay_lockr_no']        = $get_paymt_info[0]['PAY_LOCKR_NO'];
                    $initVar['pay_lockr_gendr_set'] = $get_paymt_info[0]['PAY_LOCKR_GENDR_SET'];
                    $initVar['paymt_chnl'] = "M"; // 모바일 결제 셋팅
                    
                    $paylib = new Pay_lib($initVar);
                    
                    $paylib->buy_run($get_paymt_info[0]['PAY_ISSUE']);
                    $reult_value = $paylib->buy_result();
                    
                } 
                
                $rData['p_status'] = "";
                $rData['p_auth_dt'] = "";
                $rData['p_auth_no'] = "";
                $rData['p_rmesg1'] = "";
                $rData['p_rmesg2'] = "";
                $rData['p_tid'] = "";
                $rData['p_fn_cd1'] = "";
                $rData['p_amt'] = "";
                $rData['p_type'] = "";
                $rData['p_uname'] = "";
                $rData['p_mid'] = "";
                $rData['p_oid'] = "";
                $rData['p_noti'] = "";
                $rData['p_next_url'] = "";
                $rData['p_mname'] = "";
                $rData['p_noteurl'] = "";
                $rData['p_card_member_num'] = "";
                $rData['p_card_num'] = "";
                $rData['p_card_issuer_code'] = "";
                $rData['p_card_purchase_code'] = "";
                $rData['p_card_prtc_code'] = "";
                $rData['p_card_interest'] = "";
                $rData['p_card_checkflag'] = "";
                $rData['p_card_issuer_name'] = "";
                $rData['p_card_purchase_name'] = "";
                $rData['p_card_point'] = "";
                $rData['p_fn_nm'] = "";
                $rData['p_flg_escrow'] = "";
                $rData['p_merchant_reserved'] = "";
                $rData['p_card_applprice'] = "";
                
                $rData['mem_sno'] = $gdata['pay_mem_sno'];
                $rData['cre_id'] = $_SESSION['user_id'];
                $rData['cre_datetm'] = $nn_now;
                
                if (isset($json['P_STATUS']))           $rData['p_status'] =            $json['P_STATUS'];
                if (isset($json['P_AUTH_DT']))          $rData['p_auth_dt'] =           $json['P_AUTH_DT'];
                if (isset($json['P_AUTH_NO']))          $rData['p_auth_no'] =           $json['P_AUTH_NO'];
                if (isset($json['P_RMESG1']))           $rData['p_rmesg1'] =            iconv("EUC-KR","UTF-8",$json['P_RMESG1']); // $json['P_RMESG1'];
                if (isset($json['P_RMESG2']))           $rData['p_rmesg2'] =            iconv("EUC-KR","UTF-8",$json['P_RMESG2']); //$json['P_RMESG2'];
                if (isset($json['P_TID']))              $rData['p_tid'] =               $json['P_TID'];
                if (isset($json['P_FN_CD1']))           $rData['p_fn_cd1'] =            $json['P_FN_CD1'];
                if (isset($json['P_AMT']))              $rData['p_amt'] =               $json['P_AMT'];
                if (isset($json['P_TYPE']))             $rData['p_type'] =              $json['P_TYPE'];
                if (isset($json['P_UNAME']))            $rData['p_uname'] =             iconv("EUC-KR","UTF-8",$json['P_UNAME']); //$json['P_UNAME'];
                if (isset($json['P_MID']))              $rData['p_mid'] =               $json['P_MID'];
                if (isset($json['P_OID']))              $rData['p_oid'] =               $json['P_OID'];
                if (isset($json['P_NOTI']))             $rData['p_noti'] =              $json['P_NOTI'];
                if (isset($json['P_NEXT_URL']))         $rData['p_next_url'] =          $json['P_NEXT_URL'];
                if (isset($json['P_MNAME']))            $rData['p_mname'] =             iconv("EUC-KR","UTF-8",$json['P_MNAME']); //$json['P_MNAME'];
                if (isset($json['P_NOTEURL']))          $rData['p_noteurl'] =           $json['P_NOTEURL'];
                if (isset($json['P_CARD_MEMBER_NUM']))  $rData['p_card_member_num'] =   $json['P_CARD_MEMBER_NUM'];
                if (isset($json['P_CARD_NUM']))         $rData['p_card_num'] =          $json['P_CARD_NUM'];
                if (isset($json['P_CARD_ISSUER_CODE'])) $rData['p_card_issuer_code'] =  $json['P_CARD_ISSUER_CODE'];
                if (isset($json['P_CARD_PURCHASE_CODE'])) $rData['p_card_purchase_code'] = $json['P_CARD_PURCHASE_CODE'];
                if (isset($json['P_CARD_PRTC_CODE']))   $rData['p_card_prtc_code'] =    $json['P_CARD_PRTC_CODE'];
                if (isset($json['P_CARD_INTEREST']))    $rData['p_card_interest'] =     $json['P_CARD_INTEREST'];
                if (isset($json['P_CARD_CHECKFLAG']))   $rData['p_card_checkflag'] =    $json['P_CARD_CHECKFLAG'];
                if (isset($json['P_CARD_ISSUER_NAME'])) $rData['p_card_issuer_name'] =  $json['P_CARD_ISSUER_NAME'];
                if (isset($json['P_CARD_PURCHASE_NAME'])) $rData['p_card_purchase_name'] = $json['P_CARD_PURCHASE_NAME'];
                if (isset($json['P_CARD_POINT']))       $rData['p_card_point'] =        $json['P_CARD_POINT'];
                if (isset($json['P_FN_NM']))            $rData['p_fn_nm'] =             iconv("EUC-KR","UTF-8",$json['P_FN_NM']); // $json['P_FN_NM'];
                if (isset($json['P_FLG_ESCROW']))       $rData['p_flg_escrow'] =        $json['P_FLG_ESCROW'];
                if (isset($json['P_MERCHANT_RESERVED'])) $rData['p_merchant_reserved'] = $json['P_MERCHANT_RESERVED'];
                if (isset($json['P_CARD_APPLPRICE']))   $rData['p_card_applprice'] =    $json['P_CARD_APPLPRICE'];
                
                $model->insert_paymt_mobile_result($rData);
                
                scriptAlert(iconv("EUC-KR","UTF-8",$json['P_RMESG1']));
                $url = "/api/mmmain";
                scriptLocation($url);
	            
	        } else 
	        {
	            scriptAlert(iconv("EUC-KR","UTF-8",$postVar['P_RMESG1']));
	            $url = "/api/mmmain";
	            scriptLocation($url);
	        }
	}
	
	/**
	 * 결제 완료 처리
	 */
	public function event_buy_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    
	    $initVar['pay_comp_cd']     = $_SESSION['comp_cd'];
	    $initVar['pay_bcoff_cd']    = $_SESSION['bcoff_cd'];
	    $initVar['pay_mem_sno']     = $postVar['mem_sno'];
	    $initVar['pay_mem_id']      = $postVar['mem_id'];
	    $initVar['pay_mem_nm']      = $postVar['mem_nm'];
	    $initVar['pay_sell_sno']    = $postVar['sell_event_sno'];
	    $initVar['pay_send_sno'] 	= $postVar['send_event_sno'];
	    $initVar['pay_appno_sno']   = $postVar['van_appno_sno'];
	    $initVar['pay_appno']       = $postVar['van_appno'];
	    $initVar['pay_card_amt']    = put_num($postVar['pay_card_amt']);
	    $initVar['pay_acct_amt']    = put_num($postVar['pay_acct_amt']);
	    $initVar['pay_acct_no']     = $postVar['pay_acct_no'];
	    $initVar['pay_cash_amt']    = put_num($postVar['pay_cash_amt']);
	    $initVar['pay_misu_amt']    = put_num($postVar['pay_misu_amt']);
	    $initVar['pay_exr_s_date']  = $postVar['pay_exr_s_date'];
	    $initVar['pay_real_sell_amt'] = put_num($postVar['pay_real_sell_amt']);
	    
	    // 라커 추가
	    $initVar['pay_lockr_no']        = $postVar['pay_lockr_no'];
	    $initVar['pay_lockr_gendr_set'] = $postVar['pay_lockr_gendr_set'];
	    
	    $paylib = new Pay_lib($initVar);
	    
	    $paylib->buy_run($postVar['pay_issue']);
	    $reult_value = $paylib->buy_result();
	    
	    scriptAlert("구매가 완료 되었습니다.");
	    $url = "/api/mmmain";
	    scriptLocation($url);
	    //_vardump($reult_value);
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	}
	
	/**
	 * DIRECT VAN 결제 [ajax]
	 */
	public function ajax_event_buy_van_direct_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    $vanModel = new VanModel();
	    $nn_now = new Time('now');
	    $amasno = new Ama_sno();
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    
	    $paymt_van_sno = $amasno->create_paymt_mgmt_sno();
	    $appno_sno = $amasno->create_appno_sno();
	    
	    $vdata['paymt_van_sno'] = $paymt_van_sno;
	    $vdata['sell_event_sno'] = $postVar['sell_event_sno'];
	    $vdata['buy_event_sno'] = "";
	    $vdata['send_event_sno'] = "";
	    $vdata['comp_cd'] = $_SESSION['comp_cd'];
	    $vdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $vdata['mem_id'] = $postVar['mem_id'];
	    $vdata['appno_sno'] = $appno_sno;
	    $vdata['appno'] = $postVar['card_appno'];
	    $vdata['paymt_amt'] = put_num($postVar['card_amt']);
	    $vdata['paymt_stat'] = "00";
	    $vdata['paymt_date'] = date('Y-m-d');
	    $vdata['refund_date'] = "";
	    
	    $vdata['cre_id'] = $_SESSION['user_id'];
	    $vdata['cre_datetm'] = $nn_now;
	    $vdata['mod_id'] = $_SESSION['user_id'];
	    $vdata['mod_datetm'] = $nn_now;
	    
	    $van_result = $vanModel->insert_van_direct_hist($vdata);
	    
	    $return_json['result'] = 'true';
	    $return_json['appno_sno'] = $appno_sno;
	    $return_json['appno'] = $postVar['card_appno'];
	    
	    return json_encode($return_json);
	    
	}
	
	
	/**
	 * 구매내역
	 */
	public function payment()
	{
	    $data = MenuHelper::getMenuData($this->request);
	    
	    $model = new MobileTModel();
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['mem_sno'] = $_SESSION['user_sno'];
	    
	    $sum1 = $model->sum_sales1_memsno($data);
	    $sum2 = $model->sum_sales2_memsno($data);
	    
	    $list1 = $model->list_sales1_memsno($data);
	    $list2 = $model->list_sales2_memsno($data);
	    
	    // 2단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cateModel = new CateModel();
	    $cate1nm = $cateModel->disp_cate1($cateData);
	    $cate2nm = $cateModel->disp_cate2($cateData);
	    
	    $cate2_nm = array();
	    
	    foreach($cate1nm as $c1) :
	    foreach($cate2nm as $c2) :
	    if ($c2['1RD_CATE_CD'] == $c1['1RD_CATE_CD']) :
	    $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
	    endif;
	    endforeach;
	    endforeach;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['cate_nm'] = $cate2_nm; // 카테고리
	    $data['view']['sum1'] = $sum1; // 환불제외 총 합계
	    $data['view']['sum2'] = $sum2; // 환불만 총 합계
	    $data['view']['list1'] = $list1; // 환불제외 리스트
	    $data['view']['list2'] = $list2; // 환불만 리스트
	    
	    $this->viewPageForMobile('/mobile_p/new_payment',$data);
	}
	
	/**
	 * 강사모바일
	 * 판매매출
	 */
	public function tmem_payment($ss_yy="",$ss_mm="")
	{
	    
	    $data = MenuHelper::getMenuData($this->request);
	    
	    $model = new MobileTModel();
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['pthcr_id'] = $_SESSION['user_id'];
	    
	    if ($ss_yy == "") $ss_yy = date('Y');
	    if ($ss_mm == "") $ss_mm = date('m');
	    
	    $def_yy = $ss_yy;
	    $def_mm = $ss_mm;
	    
	    $sdate = $def_yy . "-" . $def_mm . "-01";
	    
	    $pre_edate = date("Y-m-d", strtotime("+1 month", strtotime($sdate)));
	    $edate = date("Y-m-d", strtotime("-1 days", strtotime($pre_edate)));
	    
	    $sdate = $sdate . " 00:00:00";
	    $edate = $edate . " 23:59:59";
	    
	    $data['sdate'] = $sdate;
	    $data['edate'] = $edate;
	    
	    $sum1 = $model->sum_sales1_tmemid($data);
	    $sum2 = $model->sum_sales2_tmemid($data);
	    
	    $list1 = $model->list_sales1_tmemid($data);
	    $list2 = $model->list_sales2_tmemid($data);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_yy'] = $def_yy;
	    $data['view']['ss_mm'] = $def_mm;
	    $data['view']['sum1'] = $sum1; // 환불제외 총 합계
	    $data['view']['sum2'] = $sum2; // 환불만 총 합계
	    
	    $data['view']['list1'] = $list1; // 환불제외 리스트
	    $data['view']['list2'] = $list2; // 환불만 리스트
	    
	    $this->viewPageForMobile('/tmobile_p/tmem_payment',$data);
	}
	
	/**
	 * 락커
	 */
	public function lockr()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '락커현황',
	        'nav' => array('락커' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // 라커룸 정보를 가져온다.
	    $lockrModel = new LockrModel();
	    $lockrData['comp_cd'] = $_SESSION['comp_cd'];
	    $lockrData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $lockrData['mem_sno'] = $_SESSION['user_sno'];
	    
	    $lockrData['lockr_knd'] = "01";
	    $lockr_01 = $lockrModel->get_lockr_room($lockrData);
	    
	    $lockrData['lockr_knd'] = "02";
	    $lockr_02 = $lockrModel->get_lockr_room($lockrData);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['lockr_01'] = $lockr_01; // 락커
	    $data['view']['lockr_02'] = $lockr_02; // 골프라커
	    $this->viewPageForMobile('/mobile_p/lockr',$data);
	}
	
	/**
	 * 락커 변경 선택
	 */
	public function lockr_change()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '락커 변경 선택',
	        'nav' => array('락커 변경 선택' => '' ),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/mobile_p/lockr_change',$data);
	}

	
// ===========================================================================
// ===========================================================================
// ===========================================================================
//                              강사 모바일
// ===========================================================================
// ===========================================================================
// ===========================================================================
	
	
	
	/**
	 * 강사 모바일 메인
	 */
	public function mtmain($first_mode = '')
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '강사 메인',
	        'nav' => array('강사관리' => '' , '강사 메인' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['stchr_id'] = $_SESSION['user_id'];
	    $data['attd_ymd'] = date('Ymd');
	    $data['clas_chk_ymd'] = date('Ymd');
	    $data['ndate'] = date('Y-m-d');
	    // 강사메인 - 수업회원출석
	    $get1 = $model->get_todayClasAttdCount($data);
	    // 강사메인 - 오늘 수업수
	    $get2 = $model->get_todayClasCount($data);
	    // 강사메인 - 수업종료예정
	    $get3 = $model->get_clasEndCount($data);
	    
	    // 강사메인 - 이번달 수당집계
	    //$ss_ym = "202407";
	    $ss_ym = date('Ym');
	    $initVar['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $sarlyLib = new Sarly_lib($initVar);
	    $sarlyGet = $sarlyLib->get_sarly($data['stchr_id'], $ss_ym);
	    
	    $sum_cost = 0; // 총 수당금액
	    $sum_sell_cost = 0; // 판매 매출액
	    $sum_pt_cost = 0; // 수업 매출액
	    foreach ($sarlyGet['sarly_mgmt'] as $r)
	    {
	        $sum_cost += $r['cost'];
	        $sum_sell_cost += $r['sell_amt'];
	        $sum_pt_cost += $r['pt_amt'];
	    }
	    	     
	    $midinfo['get1'] = $sum_cost;
	    $midinfo['get2'] = $sum_sell_cost;
	    $midinfo['get3'] = $sum_pt_cost;
	    
	    $topinfo['get1'] = $get1;
	    $topinfo['get2'] = $get2;
	    $topinfo['get3'] = $get3;
	    
	    $data['noti_dv'] = "02"; // 강사 구분
	    $list_notice = $model->list_tnotice($data);
	    
	    $data['noti_dv'] = "01"; // 회원 구분
	    $list_unotice = $model->list_tnotice($data);
	    
	    // GX schedule
	    
	    $gxData['comp_cd'] = $_SESSION['comp_cd'];
	    $gxData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $gxData['stchr_id'] = $_SESSION['user_id'];
	    $gxClas = new GxClas_lib($gxData);
	    
	    $today = date('Y-m-d');
	    $gx_list = $gxClas->list_gx_today_schd_stchr_id($today);
	    
	    
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['first_mode'] = $first_mode;
	    $data['view']['topinfo'] = $topinfo;
	    $data['view']['midinfo'] = $midinfo;
	    $data['view']['list_notice'] = $list_notice;
	    $data['view']['list_unotice'] = $list_unotice;
	    $data['view']['list_gx'] = $gx_list;
	    $this->viewPageForMobile('/tmobile_p/mtmain',$data);
	}
	
	/**
	 * ajax 회원메인 - 공지사항 상세 내용 보기
	 * @return string
	 */
	public function ajax_mmmain_get_tnotice_detail()
	{
	    $postVar = $this->request->getPost();
	    
	    $model = new MobileTModel();
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['noti_sno'] = $postVar['noti_sno'];
	    $get_content = $model->get_mnotice($data);
	    
	    $return_json['content'] = $get_content[0];
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * ajax 강사메인 - 강사 공지사항 상세 내용 보기
	 * @return string
	 */
	public function ajax_mtmain_get_tnotice_detail()
	{
	    $postVar = $this->request->getPost();
	    
	    $model = new MobileTModel();
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['noti_sno'] = $postVar['noti_sno'];
	    $get_content = $model->get_tnotice($data);
	    
	    $return_json['content'] = $get_content[0];
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * ajax 강사메인 - 회원 공지사항 상세 내용 보기
	 * @return string
	 */
	public function ajax_mtmain_get_unotice_detail()
	{
	    $postVar = $this->request->getPost();
	    
	    $model = new MobileTModel();
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['noti_sno'] = $postVar['noti_sno'];
	    $get_content = $model->get_mnotice($data);
	    
	    $return_json['content'] = $get_content[0];
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * 강사 출(퇴근) 하기
	 */
	public function tqrcode()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '출근하기',
	        'nav' => array('출근하기' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $base_qrcode = $_SESSION['comp_cd'] . "|" . $_SESSION['bcoff_cd'] . "|" . $_SESSION['user_sno'];
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['base_qrcode'] = $base_qrcode;
	    $this->viewPageForMobile('/tmobile_p/tqrcode',$data);
	}
	
	/**
	 * 강사 공지사항
	 */
	public function tnotice()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '강사 공지사항',
	        'nav' => array('강사 공지사항' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['noti_dv'] = '02';
	    $data['ndate'] = date('Y-m-d');
	    $list_notice = $model->list_tnotice($data);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['list_notice'] = $list_notice;
	    $this->viewPageForMobile('/tmobile_p/tnotice',$data);
	}
	
	/**
	 * 수당집계
	 */
	public function tsalary($ss_yy="",$ss_mm="")
	{
	    if ($ss_yy == "") $ss_yy = date("Y");
	    if ($ss_mm == "") $ss_mm = date('m');
	    $ss_ym = $ss_yy.$ss_mm;
	    
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '수당집계 (' . $ss_yy . '년 '. $ss_mm.'월)',
	        'nav' => array('수당집계 (' . $ss_ym . ')' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    
	    // 강사메인 - 이번달 수당집계
	    
	    
	    
	    $initVar['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $sarlyLib = new Sarly_lib($initVar);
	    $sarlyGet = $sarlyLib->get_sarly($_SESSION['user_id'], $ss_ym);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_yy'] = $ss_yy;
	    $data['view']['ss_mm'] = $ss_mm;
	    $data['view']['sarly_mgmt'] = $sarlyGet['sarly_mgmt'];
	    $this->viewPageForMobile('/tmobile_p/tsalary',$data);
	}
	
	public function tsalary_detail($ss_yy,$ss_mm,$mid,$kind,$sarly_mgmt_sno)
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '수당 집계표(상세)',
	        'nav' => array('강사관리' => '' , '수당 집계표(상세)' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $postVar['ss_yy'] = $ss_yy;
	    $postVar['ss_mm'] = $ss_mm;
	    $postVar['mid'] = $mid;
	    $postVar['kind'] = $kind;
	    $postVar['sarly_mgmt_sno'] = $sarly_mgmt_sno;
	    
	    $ss_ym = $ss_yy .  $ss_mm;
	    
	    $initVar['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $sarlyLib = new Sarly_lib($initVar);
	    
	    //         $sarly_mgmt_sno = '8';
	    // kind : 01 sell_amt_detail , 02 pt_amt_detail , 03 pt_count_detail
	    //         $kind = "03";
	    $detail_sarly = $sarlyLib->get_sarly_detail($mid, $ss_ym,$sarly_mgmt_sno,$kind);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['detail_sarly'] = $detail_sarly; // 수당집계표 상세
	    $data['view']['postVar'] = $postVar;
	    
	    if ($kind == "01")
	    {
	        $this->viewPageForMobile('/tmobile_p/tsalary_detail1',$data); // 매출
	    } else
	    {
	        if ($kind == "04")
	        {
	            $this->viewPageForMobile('/tmobile_p/tsalary_detail4',$data); // GX 수업진행
	        } else 
	        {
	            $this->viewPageForMobile('/tmobile_p/tsalary_detail2',$data); // 수업진행
	        }
	        
	    }
	}
	
	/**
	 * 연차관리
	 */
	public function tannu($ss_yy="",$ss_mm="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '연차관리',
	        'nav' => array('연차관리' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    
	    $mem_sno = $_SESSION['user_sno'];
	    $modelMem = new MemModel();
	    $modelAnnu = new AnnuModel();
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    
	    $tdata['comp_cd'] = $_SESSION['comp_cd'];
	    $tdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $tdata['mem_sno'] = $mem_sno;
	    
	    $tchr_info = $modelMem->get_tmem_info_mem_sno($tdata);
	    
	    $annu_list = $modelAnnu->list_tchr_annu_grant_mgmt_pos_use($tdata);
	    
	    if (count($annu_list) > 0)
	    {
	        $tchr_annu_list = $annu_list;
	        $annu_grant_mgmt_sno = $annu_list[0]['ANNU_GRANT_MGMT_SNO'];
	    } else
	    {
	        $tchr_annu_list[0]['ANNU_GRANT_DAY'] = "0";
	        $tchr_annu_list[0]['ANNU_USE_DAY'] = "0";
	        $tchr_annu_list[0]['ANNU_LEFT_DAY'] = "0";
	        $tchr_annu_list = array();
	        $annu_grant_mgmt_sno = "";
	    }
	    
	    $appct_annu_list = $modelAnnu->list_tchr_annu_grant_mgmt_hist_memsno($tdata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_yy'] = $ss_yy;
	    $data['view']['ss_mm'] = $ss_mm;
	    $data['view']['annu_grant_mgmt_sno'] = $annu_grant_mgmt_sno;
	    $data['view']['tchr_annu_appct_list'] = $appct_annu_list;
	    $data['view']['tchr_annu_list'] = $tchr_annu_list;
	    $data['view']['tchr_info'] = $tchr_info[0];
	    $this->viewPageForMobile('/tmobile_p/tannu',$data);
	}
	
	/**
	 * 연차 신청하기
	 */
	public function ajax_tchr_annu_appct_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $modelAnnu = new AnnuModel();
	    $nn_now = new Time('now');
	    $postVar = $this->request->getPost();
	    
	    $hdata['annu_grant_mgmt_sno'] = $postVar['annu_grant_mgmt_sno'];
	    $hdata['comp_cd'] = $_SESSION['comp_cd'];
	    $hdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $hdata['mem_sno'] = $postVar['mem_sno'];
	    $hdata['mem_id'] = $postVar['mem_id'];
	    $hdata['mem_nm'] = $postVar['mem_nm'];
	    $hdata['annu_appv_id'] = "";
	    $hdata['annu_appct_knd'] = "01"; // 01 : 정규연차 / 02: 포상연차
	    $hdata['annu_appct_s_date'] = $postVar['annu_appct_s_date'];
	    $hdata['annu_appct_e_date'] = $postVar['annu_appct_e_date'];
	    $hdata['annu_use_day'] = $postVar['annu_days'];
	    $hdata['annu_appv_stat'] = "00";
	    $hdata['annu_appv_datetm'] = $nn_now;
	    $hdata['annu_refus_datetm'] = "";
	    $hdata['annu_appct_datetm'] = "";
	    $hdata['annu_cancel_datetm'] = "";
	    $hdata['cre_id'] = $_SESSION['user_id'];
	    $hdata['cre_datetm'] = $nn_now;
	    $hdata['mod_id'] = $_SESSION['user_id'];
	    $hdata['mod_datetm'] = $nn_now;
	    
	    $modelAnnu->insert_tchr_annu_use_mgmt_hist($hdata);
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * 연차신청 취소하기
	 */
	public function ajax_tchr_annu_cancel_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $modelAnnu = new AnnuModel();
	    $nn_now = new Time('now');
	    $postVar = $this->request->getPost();
	    
	    $cdata['annu_use_mgmt_hist_sno'] = $postVar['hist_sno'];
	    $cdata['comp_cd'] = $_SESSION['comp_cd'];
	    $cdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cdata['annu_appv_stat'] = "99";
	    $cdata['annu_cancel_datetm'] = $nn_now;
	    $cdata['mod_id'] = $_SESSION['user_id'];
	    $cdata['mod_datetm'] = $nn_now;
	    
	    $modelAnnu->update_tchr_annu_use_mgmt_hist_cancel($cdata);
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * 출근현황
	 */
	public function tattd($ss_yy="",$ss_mm="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '출근현황',
	        'nav' => array('출근현황' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $attdModel = new AttdModel();
	    
	    $cData['comp_cd'] = $_SESSION['comp_cd'];
	    $cData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $cData['mem_sno'] = $_SESSION['user_sno'];
	    
	    if ($ss_yy == "") $ss_yy = date('Y');
	    if ($ss_mm == "") $ss_mm = date('m');
	    
	    $cData['attd_yy'] = $ss_yy;
	    $cData['attd_mm'] = $ss_mm;
	    $cData['attd_dv'] = '00';
	    
	    $chk_y_count = $attdModel->count_tchr_attd_mgmt_status($cData);
	    $cData['attd_dv'] = '01';
	    $chk_n_count = $attdModel->count_tchr_attd_mgmt_status($cData);
	    
	    $list_attd = $attdModel->list_tchr_attd_mgmt($cData);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_yy'] = $ss_yy;
	    $data['view']['ss_mm'] = $ss_mm;
	    $data['view']['chk_y'] = $chk_y_count;
	    $data['view']['chk_n'] = $chk_n_count;
	    $data['view']['list_attd'] = $list_attd;
	    $this->viewPageForMobile('/tmobile_p/tattd',$data);
	}
	
	/**
	 * 정보수정 - 강사 비밀번호 확인
	 */
	public function tmem_pre_modify()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '정보수정',
	        'nav' => array('정보수정' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/tmobile_p/tmem_pre_modify',$data);
	}
	
	/**
	 * 강사 비밀번호 확인 - 비밀번호 체크
	 */
	public function ajax_tmem_pass_check()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost(); // re_pass
	    $model = new MemModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['login_id'] = $_SESSION['user_id'];
	    $data['login_pwd'] = $this->enc_pass($postVar['re_pass']);
	    $pass_check = $model->mobile_login_check($data);
	    
	    if ( count($pass_check) > 0 )
	    {
	        $return_json['result'] = 'true';
	    } else 
	    {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	}
	
	/**
	 * 정보수정
	 */
	public function tmem_modify()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '정보수정',
	        'nav' => array('정보수정' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MemModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['mem_sno'] = $_SESSION['user_sno'];
	    $tinfo = $model->mobile_list_bcoff($data);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['tinfo'] = $tinfo[0];
	    $this->viewPageForMobile('/tmobile_p/tmem_modify',$data);
	}
	
	/**
	 * 강사정보 수정 처리
	 */
	public function ajax_tmem_modify_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost(); // re_pass
	    $model = new MemModel();
	    $nn_now = new Time('now');
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['mem_sno'] = $_SESSION['user_sno'];
	    $data['mem_nm'] = $postVar['mem_nm'];
	    $data['bthday'] = put_num($postVar['bthday']);
	    $data['mem_gendr'] = $postVar['mem_gendr'];
	    $data['mem_telno'] = put_num($postVar['mem_telno']);
	    
	    $re_phone_num = $this->enc_phone(put_num($postVar['mem_telno']));
	    $data['mem_telno_enc'] = $re_phone_num['enc'];
	    $data['mem_telno_mask'] = $re_phone_num['mask'];
	    $data['mem_telno_short'] = $re_phone_num['short'];
	    $data['mem_telno_enc2'] = $re_phone_num['enc2'];
	    
	    $data['mem_addr'] = $postVar['mem_addr'];
	    $data['mod_id'] = $_SESSION['user_id'];
	    $data['mod_datetm'] = $nn_now;
	    
	    $model->update_mem_main_info($data);
	    $model->update_mem_info_detl_tbl($data);
	    
	    if ($postVar['mem_pwd'])
	    {
	        $pdata['mem_pwd'] = $this->enc_pass($postVar['mem_pwd']);
	        $pdata['mem_sno'] = $_SESSION['user_sno'];
	        $pdata['mod_id'] = $_SESSION['user_id'];
	        $pdata['mod_datetm'] = $nn_now;
	        $model->update_mem_main_info_pwd($pdata);
	    }
	    
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	
	/**
	 * 수업 상품관리
	 */
	public function teventpt($ss_yy="",$ss_mm="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '수업 상품관리',
	        'nav' => array('수업 상품관리' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['stchr_id'] = $_SESSION['user_id'];
	    
	    if ($ss_yy == "") $ss_yy = date('Y');
	    if ($ss_mm == "") $ss_mm = date('m');
	    
	    $data['clas_chk_yy'] = $ss_yy;
	    $data['clas_chk_mm'] = $ss_mm;
	    
	    $get1 = $model->get_clas_all_left_cnt($data);
	    $get2 = $model->get_clas_prgs_cnt($data);
	    $event_list = $model->list_buy_event_tchr_use($data);
	    
	    $topinfo['get1'] = $get1;
	    $topinfo['get2'] = $get2;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_yy'] = $ss_yy;
	    $data['view']['ss_mm'] = $ss_mm;
	    $data['view']['topinfo'] = $topinfo;
	    $data['view']['event_list'] = $event_list;
	    $this->viewPageForMobile('/tmobile_p/teventpt',$data);
	}
	
	/**
	 * 판매 상품현황
	 */
	public function teventsell()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '판매 상품현황',
	        'nav' => array('판매 상품현황' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $sdate = date('Y-m')."-01";
	    $pre_edate = date("Y-m-d", strtotime("+1 month", strtotime($sdate)));
	    $edate = date("Y-m-d", strtotime("-1 days", strtotime($pre_edate)));
	    
	    //$sdate = "2024-01-01";
	    //$edate = "2025-01-01";
	    
	    //$sdate = "2024-06-01"; // test
	    //$sdate = $sdate . " 00:00:00";
	    //$edate = $edate . " 23:59:59";
	    
	    $data['sdate'] = $sdate;
	    $data['edate'] = $edate;
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['ptchr_id'] = $_SESSION['user_id'];
	    //$data['ptchr_id'] = 'jamescoex'; // test
	    
	    $event_list = $model->list_buy_event_pchr($data);
	    
	    $paymt_sum = $model->sum_buy_event_pchr($data);
	    
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['event_list'] = $event_list;
	    $data['view']['paymt_sum'] = $paymt_sum;
	    $this->viewPageForMobile('/tmobile_p/teventsell',$data);
	}
	
	/**
	 * 종료(예정) 상품
	 */
	public function teventend()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '종료(예정) 상품',
	        'nav' => array('종료(예정) 상품' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $sdate = date('Y-m')."-01";
	    $pre_edate = date("Y-m-d", strtotime("+1 month", strtotime($sdate)));
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['edate'] = $pre_edate;
	    $data['ptchr_id'] = $_SESSION['user_id'];
	    $data['stchr_id'] = $_SESSION['user_id'];
	    
	    $event_list1 = $model->end_buy_event_pchr($data); //개인레슨 제외
	    
	    $event_list2 = $model->end_buy_event_schr($data); //개인레슨
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['event_list1'] = $event_list1;
	    $data['view']['event_list2'] = $event_list2;
	    $this->viewPageForMobile('/tmobile_p/teventend',$data);
	}
	
	
	/**
	 * 회원 : 수업 내역
	 */
	public function attdmemlist()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '수업 내역',
	        'nav' => array('수업 내역' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['mem_sno'] = $_SESSION['user_sno'];
	    $data['clas_chk_yy'] = date('Y');
	    $data['clas_chk_mm'] = date('m');
	    
	    $get1 = $model->get_mem_clas_all_left_cnt($data);
	    $get2 = $model->get_mem_clas_prgs_dd_cnt($data);
	    
	    $event_list = $model->list_attd_mem_use($data);
	    
	    $topinfo['get1'] = $get1;
	    $topinfo['get2'] = $get2;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['topinfo'] = $topinfo;
	    $data['view']['event_list'] = $event_list;
	    $this->viewPageForMobile('/mobile_p/new_attdmemlist',$data);
	}
	
	/**
	 * 강사 : 수업진행금액
	 */
	public function tmem_attdmemlist()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '수업진행금액',
	        'nav' => array('수업진행금액' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $postVar = $this->request->getPost(); 
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['stchr_id'] = $_SESSION['user_id'];
	    
	    $def_yy = date('Y');
	    $def_mm = date('m');
	    if (isset($postVar['ss_yy'])) $def_yy = $postVar['ss_yy'];
	    if (isset($postVar['ss_mm'])) $def_mm = $postVar['ss_mm'];
	    
	    $data['clas_chk_ymd'] = date('Ymd');
	    $data['clas_chk_yy'] = $def_yy;
	    $data['clas_chk_mm'] = $def_mm;
	    
	    $get1 = $model->list_attd_tchr_use_month_cost($data);
	    $get2 = $model->list_attd_tchr_use_month_cnt($data);
	    
	    $event_list = $model->list_attd_tchr_use_month($data);
	    
	    $topinfo['get1'] = $get1[0]['sum_cost'];
	    $topinfo['get2'] = $get2[0]['counter'];
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_yy'] = $def_yy;
	    $data['view']['ss_mm'] = $def_mm;
	    $data['view']['topinfo'] = $topinfo;
	    $data['view']['event_list'] = $event_list;
	    $this->viewPageForMobile('/tmobile_p/tmem_attdmemlist',$data);
	}
	
	/**
	 * 강사 : 수업진행금액
	 */
	public function tmem_gxlist($ss_yy='',$ss_mm='')
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => 'GX수업현황',
	        'nav' => array('수업현황' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $gxData['comp_cd'] = $_SESSION['comp_cd'];
	    $gxData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $gxData['gx_stchr_id'] = $_SESSION['user_id'];
	    
	    $clasModel = new ClasModel();
	    
	    $syy = date('Y');
	    $smm = date('m');
	    
	    if ($ss_yy != '')
	    {
	        $syy = $ss_yy;
	    }
	    
	    if ($ss_mm != '')
	    {
	        $smm = $ss_mm;
	    }
	    
	    if ($smm == '')
	    {
	        $gxData['sdate'] = $syy . "-01-01";
	        $gxData['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 years", strtotime($gxData['sdate'])))))); ;
	    } else
	    {
	        $gxData['sdate'] = $syy . "-".$smm."-01";
	        $gxData['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 months", strtotime($gxData['sdate'])))))); ;
	    }
	    
	    $gx_list = $clasModel->list_tgx_schd_mobile($gxData);
	    
	    $topinfo['get1'] = $clasModel->list_tgx_schd_mobile_all_count($gxData);
	    $topinfo['get2'] = $clasModel->list_tgx_schd_mobile_y_count($gxData);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['topinfo'] = $topinfo; // 상단 횟수 정보
	    $data['view']['ss_yy'] = $syy; // 년
	    $data['view']['ss_mm'] = $smm; // 월
	    $data['view']['list_gx'] = $gx_list; // GX 수업 체크
	    $this->viewPageForMobile('/tmobile_p/tmem_gxlist',$data);
	}
	
	
	/**
	 * 강사 : 수업 내역
	 */
	public function tattdmemlist($ss_ymd="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '수업 내역',
	        'nav' => array('수업 내역' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['stchr_id'] = $_SESSION['user_id'];
	    
	    if ($ss_ymd == '') $ss_ymd = date('Y-m-d');
	    
	    $data['clas_chk_ymd'] = put_num($ss_ymd);
	    
	    $get1 = $model->get_clas_all_left_cnt($data);
	    $get2 = $model->get_clas_prgs_dd_cnt($data);
	    
	    $event_list = $model->list_attd_tchr_use($data);
	    
	    $topinfo['get1'] = $get1;
	    $topinfo['get2'] = $get2;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_ymd'] =  $ss_ymd;
	    $data['view']['topinfo'] = $topinfo;
	    $data['view']['event_list'] = $event_list;
	    $this->viewPageForMobile('/tmobile_p/tattdmemlist',$data);
	}
	
	
	
	/**
	 * 수업 출석회원
	 */
	public function tattdmem($ss_ymd="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '수업 출석회원',
	        'nav' => array('수업 출석회원' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $mobileModel = new MobileTModel();
	    
	    $aData['comp_cd'] = $_SESSION['comp_cd'];
	    $aData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    //$aData['attd_ymd'] = '20240627'; // test
	    
	    if ($ss_ymd == '') $ss_ymd = date('Y-m-d');
	    
	    $aData['attd_ymd'] = put_num($ss_ymd);
	    $aData['stchr_id'] = $_SESSION['user_id'];
	    
	    $aData['auto_chk'] = 'Y';
	    $list_clas_attd_y = $mobileModel->list_clas_attd($aData);
	    
	    $aData['auto_chk'] = 'N';
	    $list_clas_attd_n = $mobileModel->list_clas_attd($aData);
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['ss_ymd'] =  $ss_ymd;
	    $data['view']['list_clas_attd_y'] = $list_clas_attd_y; // 수업입장
	    $data['view']['list_clas_attd_n'] = $list_clas_attd_n; // 일반입장
	    $this->viewPageForMobile('/tmobile_p/tattdmem',$data);
	}
	
	/**
	 * 해당 수업에 대한 메세지를 입력한다.
	 */
	public function ajax_clas_diary_insert_proc()
	{
	    $postVar = $this->request->getPost();
	    $stchr_id = $_SESSION['user_id'];
	    $buy_sno = $postVar['buy_sno'];
	    $clasLib = new Clas_lib($stchr_id, $buy_sno);
	    $clasLib->clas_msg_ins($postVar['clas_conts'], 'T');
	    
	    $return_json['msg'] = "입력 성공";
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * 해당 수업에 대한 회원 메세지를 입력한다.
	 */
	public function ajax_clas_diary_mem_insert_proc()
	{
	    $postVar = $this->request->getPost();
	    $stchr_id = $_SESSION['user_id'];
	    $buy_sno = $postVar['buy_sno'];
	    $clasLib = new Clas_lib($stchr_id, $buy_sno);
	    $clasLib->clas_msg_ins($postVar['clas_conts'], 'M');
	    
	    $return_json['msg'] = "입력 성공";
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	/**
	 * 수업체크
	 * @return string
	 */
	public function ajax_clas_ok()
	{
	    $postVar = $this->request->getPost();
	    
	    $stchr_id = $_SESSION['user_id'];
	    $buy_sno = $postVar['buy_sno'];
		$attd_mgmt_sno = $postVar['attd_mgmt_sno'];	
	
	    $clasLib = new Clas_lib($stchr_id, $buy_sno);
		$clasLib->set_attd_mgmt_sno($attd_mgmt_sno);
	    $clas_result = $clasLib->clas_run();
	    
	    $clasLib->clas_msg_ins('수업 1회를 진행하였습니다.', 'T');
	    
	    $return_json['clas_result'] = $clas_result;
	    $return_json['msg'] = $clas_result['msg'];
	    
	    if ($clas_result['result'] == true)
	    {
	        $return_json['result'] = 'true';
	    } else 
	    {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	}

	/**
	 * 수업체크
	 * @return string
	 */
	public function ajax_clas_ok_mem()
	{
	    $postVar = $this->request->getPost();
	    
	   
		$stchr_id = $postVar['stchr_id'];
	    $buy_sno = $postVar['buy_sno'];
		$attd_mgmt_sno = $postVar['attd_mgmt_sno'];	
	
	    $clasLib = new Clas_lib($stchr_id, $buy_sno);
		$clasLib->set_attd_mgmt_sno($attd_mgmt_sno);
	    $clas_result = $clasLib->clas_run();
		$stchr_id = $_SESSION['user_id'];
		$clasLib = new Clas_lib($stchr_id, $buy_sno);
	    
	    // 메시지 입력을 위해 임시로 강사 ID로 세션 설정
	    $original_user_id = $_SESSION['user_id'];
	    $_SESSION['user_id'] = $stchr_id;
	    
	    $clasLib->clas_msg_ins('PT수업 1회 진행을 확인합니다.', 'M');
	    
	    // 원래 세션으로 복원
	    $_SESSION['user_id'] = $original_user_id;
	    
	    $return_json['clas_result'] = $clas_result;
	    $return_json['msg'] = $clas_result['msg'];
	    
	    if ($clas_result['result'] == true)
	    {
	        $return_json['result'] = 'true';
	    } else 
	    {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	}
	

	/**
	 * 수업체크 취소
	 * @return string
	 */
	public function ajax_clas_cancel_mem()
	{
	    $postVar = $this->request->getPost();
	    
	    $stchr_id = $_SESSION['user_id'];
	    $buy_sno = $postVar['buy_sno'];
	    $clasLib = new Clas_lib($stchr_id, $buy_sno);
	    $clas_result = $clasLib->clas_cancel_mem();
		
	    $clasLib->clas_msg_ins("수업 친행을 취소하였습니다.", 'T');
	    
	    $return_json['clas_result'] = $clas_result;
	    $return_json['msg'] = $clas_result['msg'];


	    

	    
	    if ($clas_result['result'] == true)
	    {
	        $return_json['result'] = 'true';
	    } else
	    {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	}


	/**
	 * 수업체크 취소
	 * @return string
	 */
	public function ajax_clas_cancel()
	{
	    $postVar = $this->request->getPost();
	    
	    $stchr_id = $_SESSION['user_id'];
	    $buy_sno = $postVar['buy_sno'];
	    $clasLib = new Clas_lib($stchr_id, $buy_sno);
	    $clas_result = $clasLib->clas_cancel();
	    
	    $return_json['clas_result'] = $clas_result;
	    $return_json['msg'] = $clas_result['msg'];
	    
	    if ($clas_result['result'] == true)
	    {
	        $return_json['result'] = 'true';
	    } else
	    {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	}
	
	/**
	 * 해당 수업에 대한 메세지 내용을 가져온다.
	 */
	public function ajax_clas_msg()
	{
	    $clasModel = new ClasModel();
	    
	    $postVar = $this->request->getPost();
	    $mdata['comp_cd'] = $_SESSION['comp_cd'];
	    $mdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $mdata['buy_event_sno'] = $postVar['buy_sno'];
	    
	    // last_time 파라미터 추가 처리
	    if (isset($postVar['last_time']) && !empty($postVar['last_time'])) {
	        $mdata['last_time'] = $postVar['last_time'];
	    }
	    
	    $msg_list = $clasModel->list_clas_msg($mdata);



		$mobileModel = new MobileTModel();
	    
	    // $mdata['mem_sno'] = $_SESSION['user_sno'];
	    
	    // attd_ymd가 있을 때만 설정
	    if (isset($postVar['attd_ymd']) && !empty($postVar['attd_ymd'])) {
	        $mdata['attd_ymd'] = $postVar['attd_ymd'];
	    }
	    
	    $list_clas_attd_without_pt_check = $mobileModel->list_clas_attd_without_pt_check($mdata);
		
		
		$list_clas_attd_with_pt_check = $mobileModel->list_clas_attd_with_pt_check($mdata);
	    
	    $return_json['msg_list'] = $msg_list;
		// 서버 코드에서
		if (!empty($list_clas_attd_without_pt_check) && (isset($mdata['attd_ymd']) && $mdata['attd_ymd'] == date('Ymd'))) {   // 출석일자가 오늘이 아닌것도 제외
			$return_json['attend_pt_without_pt_check_attd_mgmt_sno'] =
		$list_clas_attd_without_pt_check[0]['ATTD_MGMT_SNO'];
		} else {
			$return_json['attend_pt_without_pt_check_attd_mgmt_sno'] = null;
		}
		if (!empty($list_clas_attd_with_pt_check) ) {   // 출석일자가 오늘이 아닌것도 제외
			$return_json['attend_pt_with_pt_check_attd_mgmt_sno'] =
		$list_clas_attd_with_pt_check[0]['ATTD_MGMT_SNO'];
		} else {
			$return_json['attend_pt_with_pt_check_attd_mgmt_sno'] = null;
		}
	    $return_json['msg'] = '성공';
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	
	/**
	 * 보낸상품현황
	 */
	public function teventsend()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '보낸상품현황',
	        'nav' => array('보낸상품현황' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    $model = new MobileTModel();
	    
	    // ===========================================================================
	    // Processs
	    // ===========================================================================
	    
	    $data['comp_cd'] = $_SESSION['comp_cd'];
	    $data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $data['cre_id'] = $_SESSION['user_id'];
	    
	    //$data['cre_id'] = "jamescoex"; // test
	    
	    
	    $event_list1 = $model->list_send_event_tchr1($data);
	    $event_list2 = $model->list_send_event_tchr2($data);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['event_list1'] = $event_list1;
	    $data['view']['event_list2'] = $event_list2;
	    $this->viewPageForMobile('/tmobile_p/teventsend',$data);
	}
	
	/**
	 * 보내기(종료)
	 */
	public function teventsend1()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '보내기(종료)',
	        'nav' => array('보내기(종료)' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // Board Pagzing
	    // ===========================================================================
	    
	    $initVar['post'] = $this->request->getPost();
	    $initVar['get'] = $this->request->getGet();
	    
	    if ($initVar['get'] == null)
	    {
	        unset($_SESSION['send_mem_sno']);
	        $_SESSION['send_mem_sno'] = array();
	    }
	    
	    $initVar['post']['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['post']['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    if ( !isset($initVar['post']['sch_end_sdate']) ) $initVar['post']['sch_end_sdate'] = date('Y-m-d');
	    //if ( !isset($initVar['post']['sch_end_edate']) ) $initVar['post']['sch_end_edate'] = date('Y-m-d');
	    
	    if ( !isset($initVar['post']['acc_rtrct_dv']) ) $initVar['post']['acc_rtrct_dv'] = "00";
	    if ( !isset($initVar['post']['acc_rtrct_mthd']) ) $initVar['post']['acc_rtrct_mthd'] = "00";
	    
	    
	    $boardPage = new Ama_board($initVar);
	    $model = new EventModel();
	    
	    $totalCount  = $model->list_all_buy_event1_count($boardPage->getVal());
	    $buy_event_list = $model->list_all_buy_event1($boardPage->getVal());
	    
	    $searchVal = $boardPage->getVal();
	    $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
	    
	    if ( !isset($searchVal['sch_end_sdate']) ) $searchVal['sch_end_sdate'] = date('Y-m-d');
	    //if ( !isset($searchVal['sch_end_edate']) ) $searchVal['sch_end_edate'] = date('Y-m-d');
	    
	    if ( !isset($searchVal['acc_rtrct_dv']) ) $searchVal['acc_rtrct_dv'] = "00";
	    if ( !isset($searchVal['acc_rtrct_mthd']) ) $searchVal['acc_rtrct_mthd'] = "00";
	    
	    if ( !isset($searchVal['search_cate1']) ) $searchVal['search_cate1'] = "";
	    if ( !isset($searchVal['search_cate2']) ) $searchVal['search_cate2'] = "";
	    
	    $data['view']['search_val'] = $searchVal;
	    $data['view']['pager'] = $boardPage->getPager($totalCount);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    
	    $modelCate = new CateModel();
	    $cate['comp_cd'] = $_SESSION['comp_cd'];
	    $cate['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $sch_cate1 = $modelCate->sch_cate1($cate);
	    $sch_cate2 = $modelCate->sch_cate2($cate);
	    
	    $data['view']['sch_cate1'] = $sch_cate1;
	    $data['view']['sch_cate2'] = $sch_cate2;
	    
	    $SpoQ_def = SpoqDef();
	    $data['view']['acc_rtrct_dv'] = $SpoQ_def['ACC_RTRCT_DV'];
	    $data['view']['acc_rtrct_mthd'] = $SpoQ_def['ACC_RTRCT_MTHD'];
	    $data['view']['buy_event_list'] = $buy_event_list;
	    $data['view']['send_mem_sno'] = $_SESSION['send_mem_sno'];
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/tmobile_p/teventsend1',$data);
	}
	
	/**
	 * 보내기(수업)
	 */
	public function teventsend2()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '보내기(수업)',
	        'nav' => array('보내기(수업)' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/tmobile_p/teventsend2',$data);
	}
	
	/**
	 * 보내기(구매안한상품)
	 */
	public function teventsend3()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '보내기(구매안한상품)',
	        'nav' => array('보내기(구매안한상품)' => ''),
	        'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/tmobile_p/teventsend3',$data);
	}
	
	
	/**
	 * send mem_sno chk ajax
	 */
	public function ajax_send_mem_sno_chk()
	{
	    $postVar = $this->request->getPost();
	    
	    
	    if (!isset($_SESSION['send_mem_sno']))
	    {
	        $_SESSION['send_mem_sno'] = array();
	    }
	    
	    $chk_sno_array = $_SESSION['send_mem_sno'];
	    
	    if ($postVar['chk_tf'] == "true")
	    {
	        array_push($chk_sno_array,$postVar['send_mem_sno']);
	    } else
	    {
	        $chk_sno_array = array_diff($chk_sno_array,array($postVar['send_mem_sno']));
	    }
	    
	    // 중복 제거
	    $chk_sno_dup = array();
	    $chk_sno_dup = array_unique($chk_sno_array);
	    $new_chk_sno_array = array();
	    
	    $new_i = 0;
	    foreach($chk_sno_dup as $r => $key) :
	    $new_chk_sno_array[$new_i] = $key;
	    $new_i++;
	    endforeach;
	    
	    $_SESSION['send_mem_sno'] = $new_chk_sno_array;
	    
	    $return_json['postVar'] = $postVar;
	    $return_json['send_mem_sno'] = $_SESSION['send_mem_sno'];
	    $return_json['msg'] = 'success';
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	}
	
	
	public function tmem_usearch()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '회원검색',
	        'nav' => array('회원검색' => ''),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $this->viewPageForMobile('/tmobile_p/tmem_usearch',$data);
	}
	
	public function top_search_proc()
	{
	    $memModel = new MemModel();
	    $eventModel = new EventModel();
	    
	    $postVar = $this->request->getPost();
	    
	    $mdata['comp_cd'] = $_SESSION['comp_cd'];
	    $mdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $mdata['mem_nm'] = $postVar['sv'];
	    
	    $mem_list = $memModel->search_like_mem_nm($mdata);
	    $SpoQ_def = SpoqDef();
	    $search_mem_list = array();
	    $mem_i = 0;
	    foreach($mem_list as $r):
	    $search_mem_list[$mem_i] = $r;
	    $search_mem_list[$mem_i]['MEM_STAT_NM'] = $SpoQ_def['MEM_STAT'][$r['MEM_STAT']];
	    $search_mem_list[$mem_i]['MEM_GENDR_NM'] = $SpoQ_def['MEM_GENDR'][$r['MEM_GENDR']];
	    
	       $mdata['mem_sno'] = $r['MEM_SNO'];
	       
	       $search_mem_list[$mem_i]['EVENT_00'] = 0;
	       $search_mem_list[$mem_i]['EVENT_01'] = 0;
	       $search_mem_list[$mem_i]['EVENT_99'] = 0;
	       
	       $event_stat_count = $eventModel->event_stat_group_count($mdata);
	       foreach ($event_stat_count as $e)
	       {
	           if ($e['EVENT_STAT'] ==  "00") $search_mem_list[$mem_i]['EVENT_00'] = $e['counter']; //이용중
	           if ($e['EVENT_STAT'] ==  "01") $search_mem_list[$mem_i]['EVENT_01'] = $e['counter']; //예약됨
	           if ($e['EVENT_STAT'] ==  "99") $search_mem_list[$mem_i]['EVENT_99'] = $e['counter']; //종료됨
	       }
	    
	    $mem_i++;
	    endforeach;
	    
	    $return_json['result'] = 'true';
	    $return_json['search_mem_list'] = $search_mem_list;
	    
	    return json_encode($return_json);
	}
	
	/**
	 * 구매상품
	 */
	public function tmem_mem_event_list($mem_sno)
	{
	    $memModel = new MemModel();
	    
	    $edata['comp_cd'] = $_SESSION['comp_cd'];
	    $edata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $edata['mem_sno'] = $mem_sno;
	    
	    $mem_info = $memModel->get_mem_info_mem_sno($edata);
	    
	    $get_tchr_list = $memModel->get_list_tchr($edata);
	    
	    $tchr_list = array();
	    $sDef = SpoqDef();
	    $tchr_i = 0;
	    foreach ($get_tchr_list as $t) :
	    $tchr_list[$tchr_i] = $t;
	    $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
	    $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
	    $tchr_i++;
	    endforeach;
	    
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => $mem_info[0]['MEM_NM']." 회원정보",
	        'nav' => array($mem_info[0]['MEM_NM'] => '' ),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // 상단 회원 기본 정보를 가져온다.
	    // 출석 정보를 가져온다.
	    $attdModel = new AttdModel();
	    $attdData['comp_cd'] = $_SESSION['comp_cd'];
	    $attdData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $attdData['mem_sno'] = $mem_sno;
	    $attd_list = $attdModel->get_attd_mgmt_16_for_mem_sno($attdData);
	    
	    // 출석현황
	    //// 이번달 1일부터 오늘까지의 날짜를 구한다.
	    $attdData['attd_sdate'] = date('Ym') . "01";
	    $attdData['attd_edate'] = date('Ymd');
	    $attd_day_count = $attdModel->count_attd_mgmt_for_mem_sno($attdData);
	    
	    $attd_info['count'] = $attd_day_count;
	    $attd_info['sdate'] = $attdData['attd_sdate'];
	    $attd_info['edate'] = $attdData['attd_edate'];
	    
	    $domcyInit['comp_cd'] = $_SESSION['comp_cd'];
	    $domcyInit['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $domcyInit['mem_sno'] = $mem_sno;
	    $domcyInit['type'] = "user";
	    
	    // 해당 회원의 메모 정보를 가져옵니다.
	    $memoModel = new MemoModel();
	    $memoData = $domcyInit;
	    $get_memo = $memoModel->get_memo_mem_sno_9($memoData);
	    
	    // 매출 순위 및 랭킹 정보를 가져온다.
	    $salesModel = new SalesModel();
	    $rankData = $domcyInit;
	    $rankInfo = $salesModel->get_rank_info($rankData);
	    
	    if (count($rankInfo) == 0)
	    {
	        $rankInfo[0]['sum_paymt_amt'] = 0;
	        $rankInfo[0]['paymt_ranking'] = 0;
	    }
	    
	    
	    
	    // 라커룸 정보를 가져온다.
	    $lockrModel = new LockrModel();
	    $lockrData = $domcyInit;
	    $lockrData['lockr_knd'] = "01";
	    $lockr_01 = $lockrModel->get_lockr_room($lockrData);
	    
	    $lockrData['lockr_knd'] = "02";
	    $lockr_02 = $lockrModel->get_lockr_room($lockrData);
	    
	    
	    
	    $eventModel = new EventModel();
	    
	    // 구매상품 정보를 가져온다.
	    
	    // 예약
	    $edata['event_stat'] = "00";
	    $event_list_00 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
	    // 이용중
	    $edata['event_stat'] = "01";
	    $event_list_01 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
	    // 종료됨
	    $edata['event_stat'] = "99";
	    $event_list_99 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['attd_info'] = $attd_info; // 이번달 출석현황 시작일,종료일,출석일수
	    $data['view']['lockr_01'] = $lockr_01; // 락커 이용 번호 정보
	    $data['view']['lockr_02'] = $lockr_02; // 골프라커 이용 번호 정보
	    $data['view']['rank_info'] = $rankInfo[0]; // 매출 순위 및 합계 정보
	    $data['view']['memo_list'] = $get_memo; // 메모 정보
	    $data['view']['attd_list'] = $attd_list; // 출석 리스트
	    $data['view']['tchr_list'] = $tchr_list; // 강사 리스트
	    $data['view']['mem_info'] = $mem_info[0]; //회원 기본 정보
	    $data['view']['event_list1'] = $event_list_01; // 예약
	    $data['view']['event_list2'] = $event_list_00; // 이용
	    $data['view']['event_list3'] = $event_list_99; // 종료
	    $this->viewPageForMobile('/tmobile_p/tmem_mem_event_list',$data);
	}
	
	/**
	 * [모바일 강사] 메모 등록 ajax
	 */
	public function ajax_memo_insert_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    $nn_now = new Time('now');
	    $memModel = new MemModel();
	    $memoModel = new MemoModel();
	    
	    $memData['comp_cd'] = $_SESSION['comp_cd'];
	    $memData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $memData['mem_sno'] = $postVar['memo_mem_sno'];
	    $meminfo = $memModel->get_mem_info_mem_sno($memData);
	    
	    $memo['comp_cd'] = $memData['comp_cd'];
	    $memo['bcoff_cd'] = $memData['bcoff_cd'];
	    $memo['mem_sno'] = $meminfo[0]['MEM_SNO'];
	    $memo['mem_id'] = $meminfo[0]['MEM_ID'];
	    $memo['mem_nm'] = $meminfo[0]['MEM_NM'];
	    $memo['prio_set'] = $postVar['memo_prio_set'];
	    $memo['memo_conts_dv'] = "0001"; // 수동메모 등록
	    $memo['memo_conts'] = $postVar['memo_content'];
	    $memo['cre_id'] = $_SESSION['user_id'];
	    $memo['cre_datetm'] = $nn_now;
	    $memo['mod_id'] = $_SESSION['user_id'];
	    $memo['mod_datetm'] = $nn_now;
	    
	    $memoModel->insert_memo_mgmt($memo);
	    
	    $return_json['postVar'] = $postVar;
	    $return_json['result'] = "true";
	    $return_json['msg'] = "메모를 등록 하였습니다.";
	    return json_encode($return_json);
	}
	
	/**
	 * 강사모바일
	 * 상품 추천하기 > 상품 선택
	 * @param string $user_sno
	 */
	public function tmem_send1($user_sno)
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품 추천하기',
	        'nav' => array('상품 추천하기' => '' ),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    // 1단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cateModel = new CateModel();
	    $cate1nm = $cateModel->disp_cate1($cateData);
	    
	    $cate1_nm = array();
	    foreach($cate1nm as $c1) :
	    $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
	    endforeach;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['user_sno'] = $user_sno; // 1단계 카테고리 가져오기
	    $data['view']['cate1_nm'] = $cate1_nm; // 1단계 카테고리 가져오기
	    $this->viewPageForMobile('/tmobile_p/tmem_send1',$data);
	}
	
	/**
	 * 강사모바일
	 * 상품 추천하기
	 * 상품구매 (개월수로 구매하기 1단계) - 대분류 선택 후
	 */
	public function tmem_send2($user_sno,$cate1)
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품 추천하기',
	        'nav' => array('상품 추천하기' => '' ),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    
	    // 2단계 카테고리 가져오기
	    $cateData['comp_cd'] = $_SESSION['comp_cd'];
	    $cateData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    $cateModel = new CateModel();
	    $cate2nm = $cateModel->disp_cate2($cateData);
	    
	    $cate2_nm = array();
	    foreach($cate2nm as $c2) :
	    if ($c2['1RD_CATE_CD'] == $cate1) :
	    $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
	    endif;
	    endforeach;
	    
	    if ($cate1 == '2000')
	    {
	        scriptLocation('/api/tmem_send2_1/'.$user_sno.'/'.$cate1.'/0');
	    }
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['user_sno'] = $user_sno; // 1단계 카테고리 가져오기
	    $data['view']['cate1'] = $cate1; // 1단계 카테고리 가져오기
	    $data['view']['cate2_nm'] = $cate2_nm; // 2단계 카테고리 가져오기
	    $this->viewPageForMobile('/tmobile_p/tmem_send2',$data);
	}
	
	/**
	 * 상품구매 (개월수로 구매하기 2단계) - 개월수 선택 후
	 */
	public function tmem_send2_1($user_sno,$cate1,$prod)
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품 추천하기',
	        'nav' => array('상품 추천하기' => '' ),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $model = new MobileTModel();
	    
	    $sdata['comp_cd'] = $_SESSION['comp_cd'];
	    $sdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $sdata['1rd_cate_cd'] = $cate1;
	    $sdata['use_prod'] = $prod;
	    
	    if ($cate1 == "2000")
	    {
	        $sdata['use_type'] = "P";
	    } else
	    {
	        $sdata['use_type'] = "H";
	    }
	    
	    $list_event = $model->list_buy_event($sdata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['user_sno'] = $user_sno; // 회원 일련번호
	    $data['view']['list_event'] = $list_event; // 1단계 카테고리 가져오기
	    $this->viewPageForMobile('/tmobile_p/tmem_send2_1',$data);
	}
	
	/**
	 * 강사모바일
	 * 상품 추천하기
	 * 상품구매 (개월수로 구매하기 2단계) - 개월수 선택 후
	 */
	public function tmem_send3($user_sno,$cate1,$prod)
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품 추천하기',
	        'nav' => array('상품 추천하기' => '' ),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $model = new MobileTModel();
	    
	    $sdata['comp_cd'] = $_SESSION['comp_cd'];
	    $sdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $sdata['1rd_cate_cd'] = $cate1;
	    $sdata['use_prod'] = $prod;
	    
	    if ($cate1 == "2000")
	    {
	        $sdata['use_type'] = "P";
	    } else
	    {
	        $sdata['use_type'] = "H";
	    }
	    
	    $list_event = $model->list_buy_event($sdata);
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['user_sno'] = $user_sno; // 회원 일련번호
	    $data['view']['list_event'] = $list_event; // 1단계 카테고리 가져오기
	    $this->viewPageForMobile('/tmobile_p/tmem_send3',$data);
	}
	
	/**
	 * 상품구매 (입장제한으로 구매하기 1단계)
	 */
	public function tmem_send_info($user_sno='',$sell_event_sno="")
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $data = array(
	        'title' => '상품 추천하기',
	        'nav' => array('상품 추천하기' => '' ),
	        'menu1' => $this->request->getGet("m1"),
            'menu2' => $this->request->getGet("m2")
	    );
	    
	    $memModel = new MemModel();
	    $eventModel = new EventModel();
	    $lockrModel = new LockrModel();
	    $sendModel = new SendModel();
	    
	    $mem_sno = $user_sno;
	    
	    // ===========================================================================
	    // Process
	    // ===========================================================================
	    
	    $memData['comp_cd'] = $_SESSION['comp_cd'];
	    $memData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $memData['mem_sno'] = $mem_sno;
	    $get_mem_info = $memModel->get_mem_info_mem_sno($memData);
	    $mem_info = $get_mem_info[0];
	    
	    $eventData = $memData;
	    $eventData['sell_event_sno'] = $sell_event_sno;
	    $get_event_info = $eventModel->get_event($eventData);
	    $event_info = $get_event_info[0];
	    
	    $send_sno = "";
	    
	    if ($send_sno == '')
	    {
	        $event_info['SEND_EVENT_SNO'] = "";
	        $event_info['ADD_SRVC_EXR_DAY'] = 0;
	        $event_info['ADD_SRVC_CLAS_CNT'] = 0;
	        
	        $event_info['PTCHR_NM'] = "";
	        $event_info['STCHR_NM'] = "";
	        
	        $event_info['ORI_SELL_AMT'] = $event_info['SELL_AMT'];
	    } else
	    {
	        $sendData['comp_cd'] = $_SESSION['comp_cd'];
	        $sendData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	        $sendData['send_event_mgmt_sno'] = $send_sno;
	        
	        $getSendInfo = $sendModel->get_send_event_mgmt($sendData);
	        
	        $event_info['SEND_EVENT_SNO'] = $getSendInfo[0]['SEND_EVENT_MGMT_SNO'];
	        $event_info['ADD_SRVC_EXR_DAY'] = $getSendInfo[0]['ADD_SRVC_EXR_DAY'];
	        $event_info['ADD_SRVC_CLAS_CNT'] = $getSendInfo[0]['ADD_SRVC_CLAS_CNT'];
	        
	        $event_info['PTCHR_NM'] = $getSendInfo[0]['PTCHR_NM'];
	        $event_info['STCHR_NM'] = $getSendInfo[0]['STCHR_NM'];
	        
	        $event_info['DOMCY_DAY'] = $getSendInfo[0]['DOMCY_DAY'];
	        $event_info['DOMCY_CNT'] = $getSendInfo[0]['DOMCY_CNT'];
	        
	        $event_info['SELL_AMT'] = $getSendInfo[0]['SELL_AMT'];
	        $event_info['ORI_SELL_AMT'] = $getSendInfo[0]['ORI_SELL_AMT'];
	    }
	    
	    $locker_data['comp_cd'] = $_SESSION['comp_cd'];
	    $locker_data['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $locker_data['lockr_knd'] = $event_info['LOCKR_KND'];
	    $locker_data['mem_sno'] = $mem_info['MEM_SNO'];
	    
	    
	    // 회원 정보를 가져온다.
	    $mdata['comp_cd'] = $_SESSION['comp_cd'];
	    $mdata['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $get_tchr_list = $memModel->get_list_tchr($mdata);
	    
	    $get_use_locker_info = $lockrModel->select_lockr_room($locker_data);
	    
	    $tchr_list = array();
	    $sDef = SpoqDef();
	    $tchr_i = 0;
	    foreach ($get_tchr_list as $t) :
	    $tchr_list[$tchr_i] = $t;
	    $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
	    $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
	    $tchr_i++;
	    endforeach;
	    
	    // ===========================================================================
	    // 화면 처리
	    // ===========================================================================
	    $data['view']['tchr_list'] = $tchr_list;
	    $data['view']['get_use_locker_info'] = $get_use_locker_info;
	    $data['view']['mem_info'] = $mem_info;
	    $data['view']['event_info'] = $event_info;
	    $this->viewPageForMobile('/tmobile_p/tmem_send_info',$data);
	}
	
	
	/**
	 * 상품 보내기 처리
	 */
	public function send_event_proc()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    
	    /**
	     * $initVar['comp_cd'];
	     * $initVar['bcoff_cd'];
	     * $initVar['send_sell_event_sno'];
	     * $initVar['set_send_amt'];
	     * $initVar['set_send_serv_clas_cnt'];
	     * $initVar['set_send_serv_day'];
	     * $initVar['set_send_domcy_cnt'];
	     * $initVar['set_send_domcy_day'];
	     * $initVar['set_ptchr_id_nm'];
	     * $initVar['set_stchr_id_nm'];
	     * $initVar['set_end_day'];
	     * $initVar['set_send_mem_snos'];
	     */
	    
	    $initVar = $postVar;
	    $initVar['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    
	    // send_mem_sno가 배열로 전달되는 경우 처리
	    if (isset($postVar['send_mem_sno']) && is_array($postVar['send_mem_sno']))
	    {
	        $initVar['set_send_mem_snos'] = implode(",", $postVar['send_mem_sno']);
	    }
	    else 
	    {
	        $initVar['set_send_mem_snos'] = $postVar['send_mem_sno'];
	    }
	    
	    $sendLib = new Send_lib($initVar);
	    $sendLib->send_event();
	    
	    scriptAlert("상품 보내기를 완료 하였습니다.");
	    scriptLocation("/api/teventsend");
	}
	
	
	
	// 설정하기 [ 시작 ]
	
	/**
	 * [회원별 통합정보] 휴회일 추가
	 */
	public function ajax_info_mem_domcyday_proc()
	{
	    $postVar = $this->request->getPost();
	    //         _vardump($postVar);
	    // fc_domcy_day_buy_sno
	    // fc_domcy_day_day
	    
	    $buy_sno = $postVar['fc_domcy_day_buy_sno'];
	    $domcy_day = $postVar['fc_domcy_day_day'];
	    
	    $initVar['buy_sno'] = $buy_sno;
	    $initVar['btype'] = "domcy_day";
	    $eventlib = new Event_lib($initVar);
	    $result_event = $eventlib->run_domcy_day($domcy_day);
	    
	    $is_tf = "true";
	    if ($result_event['is_possable'] == false) $is_tf = "false";
	    
	    $return_json['result'] = $is_tf;
	    $return_json['msg'] = $result_event['msg'];
	    
	    return json_encode($return_json);
	    
	}
	
	/**
	 * [회원별 통합정보] 휴회횟수 추가
	 */
	public function ajax_info_mem_domcycnt_proc()
	{
	    $postVar = $this->request->getPost();
	    
	    $buy_sno = $postVar['fc_domcy_cnt_buy_sno'];
	    $domcy_cnt = $postVar['fc_domcy_cnt_cnt'];
	    
	    $initVar['buy_sno'] = $buy_sno;
	    $initVar['btype'] = "domcy_dnt";
	    $eventlib = new Event_lib($initVar);
	    $result_event = $eventlib->run_domcy_cnt($domcy_cnt);
	    
	    $is_tf = "true";
	    if ($result_event['is_possable'] == false) $is_tf = "false";
	    
	    $return_json['result'] = $is_tf;
	    $return_json['msg'] = $result_event['msg'];
	    
	    return json_encode($return_json);
	}
	
	/**
	 * [회원별 통합정보] 운동 시작일 변경
	 */
	public function ajax_info_mem_change_sdate_proc()
	{
	    $postVar = $this->request->getPost();
	    
	    $buy_sno = $postVar['fc_sdate_buy_sno'];
	    $sdate = $postVar['fc_sdate_sdate'];
	    
	    $initVar['buy_sno'] = $buy_sno;
	    $initVar['btype'] = "sdate";
	    $eventlib = new Event_lib($initVar);
	    $result_event = $eventlib->run_sdate($sdate);
	    
	    $is_tf = "true";
	    if ($result_event['is_possable'] == false) $is_tf = "false";
	    
	    $return_json['result'] = $is_tf;
	    $return_json['msg'] = $result_event['msg'];
	    
	    return json_encode($return_json);
	}
	
	/**
	 * [회원별 통합정보] 운동 종료일 변경
	 */
	public function ajax_info_mem_change_edate_proc()
	{
	    $postVar = $this->request->getPost();
	    
	    $buy_sno = $postVar['fc_edate_buy_sno'];
	    $edate = $postVar['fc_edate_edate'];
	    
	    $initVar['buy_sno'] = $buy_sno;
	    $initVar['btype'] = "edate";
	    $eventlib = new Event_lib($initVar);
	    $result_event = $eventlib->run_edate($edate);
	    
	    $is_tf = "true";
	    if ($result_event['is_possable'] == false) $is_tf = "false";
	    
	    $return_json['result'] = $is_tf;
	    $return_json['msg'] = $result_event['msg'];
	    
	    return json_encode($return_json);
	}
	
	/**
	 * [회원별 통합정보] 수업횟수 추가
	 */
	public function ajax_info_mem_clascnt_proc()
	{
	    $postVar = $this->request->getPost();
	    
	    $buy_sno = $postVar['fc_clas_cnt_buy_sno'];
	    $clas_cnt = $postVar['fc_clas_cnt_cnt'];
	    
	    $initVar['buy_sno'] = $buy_sno;
	    $initVar['btype'] = "clas_cnt";
	    $eventlib = new Event_lib($initVar);
	    $result_event = $eventlib->run_clas_cnt($clas_cnt);
	    
	    $is_tf = "true";
	    if ($result_event['is_possable'] == false) $is_tf = "false";
	    
	    $return_json['result'] = $is_tf;
	    $return_json['msg'] = $result_event['msg'];
	    
	    return json_encode($return_json);
	}
	
	public function ajax_info_mem_just_end_proc()
	{
	    $postVar = $this->request->getPost();
	    $nn_now = new Time('now');
	    $modelEvent = new EventModel();
	    $modelMem = new MemModel();
	    
	    $endData['comp_cd'] = $_SESSION['comp_cd'];
	    $endData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $endData['buy_event_sno'] = $postVar['buy_sno'];
	    $endData['event_stat'] = "99";
	    $endData['event_stat_rson'] = "91";
	    $endData['mod_id'] = $_SESSION['user_id'];
	    $endData['mod_datetm'] = $nn_now;
	    
	    $modelEvent->update_buy_event_mgmt_just_end($endData);
	    
	    // 강제종료 history
	    $nn_now = new Time('now');
	    $histData = $endData;
	    $hist_event_info = $modelEvent->get_buy_event_buy_sno($histData);
	    
	    $histModel = new HistModel();
	    $hist['comp_cd'] = $hist_event_info[0]['COMP_CD'];
	    $hist['bcoff_cd'] = $hist_event_info[0]['BCOFF_CD'];
	    $hist['buy_event_sno'] = $hist_event_info[0]['BUY_EVENT_SNO'];
	    $hist['sell_event_nm'] = $hist_event_info[0]['SELL_EVENT_NM'];
	    $hist['mem_sno'] = $hist_event_info[0]['MEM_SNO'];
	    $hist['mem_id'] = $hist_event_info[0]['MEM_ID'];
	    $hist['mem_nm'] = $hist_event_info[0]['MEM_NM'];
	    $hist['mod_id'] = $_SESSION['user_id'];
	    $hist['mod_datetm'] = $nn_now;
	    $hist['cre_id'] = $_SESSION['user_id'];
	    $hist['cre_datetm'] = $nn_now;
	    $histModel->insert_hist_event_just_end($hist);
	    
	    // 강제 종료 후에 회원의 상태를 검사하여 이용중이나 예약됨 상품이 하나도 없을 경우 회원의 상태를 변경 해야한다.
	    
	    $endChk['comp_cd'] = $hist_event_info[0]['COMP_CD'];
	    $endChk['bcoff_cd'] = $hist_event_info[0]['BCOFF_CD'];
	    $endChk['mem_sno'] = $hist_event_info[0]['MEM_SNO'];
	    $endCount = $modelEvent->end_chk_event_mem_sno($endChk);
	    
	    if ($endCount == 0)
	    {
	        $upVar['mem_stat'] = '90'; // 종료회원
	        $upVar['mem_sno'] = $hist_event_info[0]['MEM_SNO'];
	        $upVar['end_datetm'] = $nn_now;
	        $upVar['mod_id'] = $_SESSION['user_id'];
	        $upVar['mod_datetm'] = $nn_now;
	        $modelMem->update_mem_end($upVar);
	    }
	    
	    $return_json['result'] = "true";
	    $return_json['msg'] = "강제종료";
	    return json_encode($return_json);
	}
	
	/**
	 * [ TEST ] 수업 체크 하기
	 * @param string $tchr_id
	 * @param string $buy_sno
	 */
	public function ajax_clas_chk()
	{
	    $postVar = $this->request->getPost();
	    $stchr_id = $postVar['stchr_id'];
	    $buy_sno = $postVar['buy_sno'];
	    
	    $clasLib = new Clas_lib($stchr_id, $buy_sno);
	    
	    $clas_result = $clasLib->clas_run();
	    
	    $return_json['msg'] = $clas_result['msg'];
	    $return_json['result'] = 'true';
	    return json_encode($return_json);
	    //_vardump($clas_result);
	}
	
	/**
	 * [회원별 통합정보] 중요메모 FLAG 설정 ajax
	 */
	public function ajax_memo_prio_set()
	{
	    // ===========================================================================
	    // 선언부
	    // ===========================================================================
	    $postVar = $this->request->getPost();
	    $nn_now = new Time('now');
	    $memoModel = new MemoModel();
	    
	    $memoData['comp_cd'] = $_SESSION['comp_cd'];
	    $memoData['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $memoData['memo_mgmt_sno'] = $postVar['memo_mgmt_sno'];
	    $memoData['prio_set'] = $postVar['prio_set'];
	    $memoData['mod_id'] = $_SESSION['user_id'];
	    $memoData['mod_datetm'] = $nn_now;
	    
	    $memoModel->modify_memo_prio_set($memoData);
	    
	    $return_json['result'] = "true";
	    $return_json['msg'] = "중요메모 설정 변경";
	    return json_encode($return_json);
	}
	
	
	// 설정하기 [ 끝 ]
	
	
	/**
	 * GX 수업체크
	 */
	public function ajax_gx_attd_proc()
	{
	    //gx_schd_mgmt_sno
	    $postVar = $this->request->getPost();
	    
	    $setVar['comp_cd'] = $_SESSION['comp_cd'];
	    $setVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $setVar['stchr_id'] = $_SESSION['user_id'];
	    $gxClas = new GxClas_lib($setVar);
	    $return_json = $gxClas->gx_attd_chk($postVar['gx_schd_mgmt_sno'],$postVar['gx_flag']);
	    
	    return json_encode($return_json);
	}
	
	
	/**
	 * 수동 출석
	 * @param string $mem_sno
	 */
	public function ajax_direct_attd($mem_sno='')
	{
	    if ($mem_sno != '')
	    {
	        $put_mem_sno = $mem_sno;
	    } else
	    {
	        $postVar = $this->request->getPost();
	        $put_mem_sno = $postVar['mem_sno'];
	    }
	    
	    $initVar['comp_cd'] = $_SESSION['comp_cd'];
	    $initVar['bcoff_cd'] = $_SESSION['bcoff_cd'];
	    $initVar['mem_sno'] = $put_mem_sno;
	    $attdLib = new Attd_lib($initVar);
	    $attdResult = $attdLib->attd_run();
	    
	    $return_json['msg'] = $attdResult['msg'];
	    
	    if ($attdResult['result'] == true)
	    {
	        $return_json['result'] = 'true';
	    } else
	    {
	        $return_json['result'] = 'false';
	    }
	    
	    return json_encode($return_json);
	    
	    //_vardump($attdResult);
	}
	
	public function main()
	{
	    echo "main";
	}
	
	/**
	 * SMS 인증 번호 발송
	 */
	public function sendSmsVerification()
	{
	    try {
	        $postVar = $this->request->getPost();
	        $phoneNumber = $postVar['phone_number'] ?? '';
	        $purpose = $postVar['purpose'] ?? 'login';
	        
	        if (empty($phoneNumber)) {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'message' => '전화번호를 입력해주세요.'
	            ]);
	        }
	        
	        // SMS 서비스 호출
	        $smsService = new SmsService();
	        $result = $smsService->sendVerificationSMS($phoneNumber, $purpose);
	        
	        if ($result['success']) {
	            // 세션에 인증 정보 저장
	            $_SESSION['sms_verified_phone'] = $phoneNumber;
	            $_SESSION['sms_verified_time'] = time();
	            
	            return $this->response->setJSON([
	                'result' => 'true',
	                'message' => $result['message'],
	                'test_code' => $result['test_code'] ?? null // 테스트 모드일 때만 표시
	            ]);
	        } else {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'message' => $result['message']
	            ]);
	        }
	        
	    } catch (\Exception $e) {
	        log_message('error', 'SMS 발송 오류: ' . $e->getMessage());
	        return $this->response->setJSON([
	            'result' => 'false',
	            'message' => '시스템 오류가 발생했습니다: ' . $e->getMessage()
	        ]);
	    }
	}
	
	/**
	 * SMS 인증 번호 확인
	 */
	public function verifySmsCode()
	{
	    try {
	        $postVar = $this->request->getPost();
	        $phoneNumber = $postVar['phone_number'] ?? '';
	        $verificationCode = $postVar['verification_code'] ?? '';
	        
	        if (empty($phoneNumber) || empty($verificationCode)) {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'message' => '전화번호와 인증번호를 입력해주세요.'
	            ]);
	        }
	        
	        // SMS 서비스 호출
	        $smsService = new SmsService();
	        $isValid = $smsService->verifyCode($phoneNumber, $verificationCode);
	        
	        if ($isValid) {
	            // 인증 성공 시 세션에 저장
	            $_SESSION['sms_verified_phone'] = $phoneNumber;
	            $_SESSION['sms_verified_time'] = time();
	            
	            // 회원 존재 여부 확인
	            $model = new MemModel();
	            
	            $memberInfo = $model->getMemberByPhone($phoneNumber);
	            
	            if (!$memberInfo) {
	                // 미등록 회원인 경우
	                return $this->response->setJSON([
	                    'result' => 'true',
	                    'message' => '인증이 완료되었습니다.',
	                    'is_new_member' => true
	                ]);
	            } else {
	                // 기존 회원인 경우
	                return $this->response->setJSON([
	                    'result' => 'true',
	                    'message' => '인증이 완료되었습니다.',
	                    'is_new_member' => false
	                ]);
	            }
	        } else {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'message' => '인증번호가 올바르지 않습니다.'
	            ]);
	        }
	        
	    } catch (\Exception $e) {
	        log_message('error', 'SMS 인증 확인 오류: ' . $e->getMessage());
	        return $this->response->setJSON([
	            'result' => 'false',
	            'message' => '시스템 오류가 발생했습니다: ' . $e->getMessage()
	        ]);
	    }
	}
	
	/**
	 * SMS 인증 로그인 처리
	 */
	public function loginProcWithSms()
	{
	    try {
	        $postVar = $this->request->getPost();
	        $loginId = $postVar['login_id'] ?? '';
	        $loginPass = $postVar['login_pass'] ?? '';
	        $phoneNumber = $postVar['phone_number'] ?? '';
	        
	        // SMS 인증 확인
	        if (!isset($_SESSION['sms_verified_phone']) || 
	            $_SESSION['sms_verified_phone'] !== $phoneNumber ||
	            (time() - $_SESSION['sms_verified_time']) > 600) { // 10분 유효
	            
	            return json_encode([
	                'result' => 'false',
	                'message' => 'SMS 인증이 필요합니다.'
	            ]);
	        }
	        
	        // 기존 로그인 로직 호출
	        $_POST['login_id'] = $loginId;
	        $_POST['login_pass'] = $loginPass;
	        $_POST['logintp'] = '';
	        
	        return $this->loginProc();
	        
	    } catch (\Exception $e) {
	        log_message('error', 'SMS 로그인 처리 오류: ' . $e->getMessage());
	        return json_encode([
	            'result' => 'false',
	            'message' => '시스템 오류가 발생했습니다.'
	        ]);
	    }
	}
	
	/**
	 * 전화번호 기반 로그인 처리 (새로운 메서드)
	 */
	public function loginWithPhone()
	{
	    try {
	        $postVar = $this->request->getPost();
	        $phoneNumber = $postVar['phone_number'] ?? '';
	        
	        // SMS 인증 확인
	        if (!isset($_SESSION['sms_verified_phone']) || 
	            $_SESSION['sms_verified_phone'] !== $phoneNumber ||
	            (time() - $_SESSION['sms_verified_time']) > 600) { // 10분 유효
	            
	            return $this->response->setJSON([
	                'result' => 'false',
	                'msg' => 'SMS 인증이 필요합니다.'
	            ]);
	        }
	        
	        // 전화번호로 회원 정보 조회하여 ID 가져오기
	        $model = new MemModel();
	        $menuModel = new MenuModel();
	        
	        $session = session();
	    	
	        $SpoQDef = SpoqDef();
	        
	        
	        $chk_tlogin = $model->getMemberByPhone($phoneNumber);
	        
	     
	        
	        if (count($chk_tlogin) > 0) {
	            // 탈퇴 여부 확인
	            if ($chk_tlogin['USE_YN'] == "N") {
	                return $this->response->setJSON([
	                    'result' => 'false',
	                    'msg' => '탈퇴처리된 회원입니다. ' . substr($chk_tlogin[0]['SECE_DATETM'],0,10)
	                ]);
	            }
	            
	            // 접속 제한 확인
	            if ($chk_tlogin['CONN_POSS_YN'] == "N") {
	                return $this->response->setJSON([
	                    'result' => 'false',
	                    'msg' => '접속이 제한된 계정입니다. 관리자에게 문의 하세요'
	                ]);
	            }
	            
	            // 세션 데이터 준비
	            $sesMake = [];
	            $sesMake['bcoff_nm'] =$chk_tlogin['BCOFF_NM'];
	            $position_code =$chk_tlogin['TCHR_POSN'];
	            $position_name = isset($SpoQDef->TCHR_POSN[$position_code]) ? $SpoQDef->TCHR_POSN[$position_code] : "알 수 없음";
	            $sesMake['TCHR_POSN_NM'] = $position_name;
	            $sesMake['user_sno'] =$chk_tlogin['MEM_SNO'];
	            $sesMake['user_name'] =$chk_tlogin['MEM_NM'];
	            $sesMake['user_id'] =$chk_tlogin['MEM_ID'];
	            $sesMake['mem_dv'] =$chk_tlogin['MEM_DV'];
	            
	            $cateData = [];
	            $cateData["bcoff_mgmt_id"] =$chk_tlogin['BCOFF_MGMT_ID'];
	            $cateData["user_id"] =$chk_tlogin['MEM_ID'];
	            $cateData["bcoff_cd"] =$chk_tlogin['BCOFF_CD'];
	            
	            // 약관 동의 여부 체크
	            $chkTermData['mem_sno'] =$chk_tlogin['MEM_SNO'];
	            $chk_terms_basic_count = $model->mobile_chk_terms_basic_count($chkTermData);
	            
	            if ($chk_terms_basic_count[0]['counter'] == 0) { // 약관을 모두 동의한 경우
	                if ($chk_tlogin['SET_COMP_CD'] != '' &&$chk_tlogin['SET_BCOFF_CD'] != '') {
	                    $sesMake['comp_cd'] =$chk_tlogin['SET_COMP_CD'];
	                    $sesMake['bcoff_cd'] =$chk_tlogin['SET_BCOFF_CD'];
	                    
	                    $get_comp_nm = $model->mobile_get_comp_nm($sesMake);
	                    $get_bcoff_nm = $model->mobile_get_bcoff_nm($sesMake);
	                    
	                    $sesMake['comp_nm'] = $get_comp_nm[0]['COMP_NM'];
	                    $sesMake['bcoff_nm'] = $get_bcoff_nm[0]['BCOFF_NM'];
	                    
	                    if ($chk_tlogin['MEM_DV'] == 'M') {
	                        $cateData["use_for"] = "MC";
	                        $sesMake['site_type'] = "mmlogin";
	                        $reUrl = "/api/mmmain/1";
	                    } elseif ($chk_tlogin['MEM_DV'] == 'T') {
	                        $cateData["use_for"] = "MT";
	                        $sesMake['site_type'] = "mtlogin";
	                        $reUrl = "/api/mtmain/1";
	                    }
	                } else {
	                    if ($chk_tlogin['MEM_DV'] == 'M') {
	                        $cateData["use_for"] = "MC";
	                        $sesMake['site_type'] = "mmlogin";
	                        $reUrl = "/api/setcomp";
	                    } elseif ($chk_tlogin['MEM_DV'] == 'T') {
	                        $cateData["use_for"] = "MT";
	                        $sesMake['site_type'] = "mtlogin";
	                        $reUrl = "/api/mtmain/1";
	                    }
	                }
	            } else { // 약관 동의가 필요한 경우
	                if ($chk_tlogin['MEM_DV'] == "T") {
	                    $sesMake['comp_cd'] =$chk_tlogin['SET_COMP_CD'];
	                    $sesMake['bcoff_cd'] =$chk_tlogin['SET_BCOFF_CD'];
	                    $sesMake['site_type'] = "mtlogin";
	                    $reUrl = "/api/mtmain";
	                    $cateData["use_for"] = "MT";
	                } else {
	                    $sesMake['site_type'] = "mmlogin";
	                    $reUrl = "/api/terms_agree_auth";
	                    $cateData["use_for"] = "MC";
	                }
	            }
	            
	            // 메뉴 리스트 가져오기
	            $menu_List = $menuModel->list_menu_of_user($cateData);
	            $sesMake['menu_list'] = $menu_List;
	            
	            // 세션 설정
	            $session->set($sesMake);
	            
	            // 쿠키 설정
	            $this->user_sno =$chk_tlogin['MEM_SNO'];
	            $this->SpoQ_set_cookie();
	            
	            // 자동 로그인 토큰 생성 및 저장
	            $authToken = $this->generateAuthToken($chk_tlogin['MEM_SNO'], $phoneNumber);
	            $smsModel = new \App\Models\SmsModel();
	            $smsModel->saveAuthToken(
	                $chk_tlogin['MEM_SNO'], 
	                $authToken, 
	                $phoneNumber,
	                $_SERVER['HTTP_USER_AGENT'] ?? null
	            );
	            
	            // 성공 응답 (토큰 포함)
	            return $this->response->setJSON([
	                'result' => 'true',
	                'msg' => '로그인되었습니다.',
	                'redirect' => $reUrl,
	                'auth_token' => $authToken
	            ]);
	            
	        } else {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'msg' => '회원 정보를 찾을 수 없습니다.'
	            ]);
	        }
	        
	    } catch (\Exception $e) {
	        log_message('error', '전화번호 로그인 처리 오류: ' . $e->getMessage());
	        return $this->response->setJSON([
	            'result' => 'false',
	            'msg' => '시스템 오류가 발생했습니다: ' . $e->getMessage()
	        ]);
	    }
	}
	
	/**
	 * 자동 로그인 확인
	 */
	public function checkAutoLogin()
	{
	    try {
	        // JSON 응답 헤더 설정
	        $this->response->setHeader('Content-Type', 'application/json');
	        
	        $postVar = $this->request->getPost();
	        $authToken = $postVar['auth_token'] ?? '';
	        
	        if (empty($authToken)) {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'msg' => '토큰이 없습니다.'
	            ]);
	        }
	        
	        // 토큰 검증
	        $smsModel = new \App\Models\SmsModel();
	        $tokenData = $smsModel->verifyAuthToken($authToken);
	        
	        if (!$tokenData) {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'msg' => '유효하지 않은 토큰입니다.'
	            ]);
	        }
	        
	        // 회원 정보 조회
	        $model = new MemModel();
	        $memberData = $model->getMemberByPhone($tokenData['phone_number']);
	        
	        if (!$memberData) {
	            return $this->response->setJSON([
	                'result' => 'false',
	                'msg' => '회원 정보를 찾을 수 없습니다.'
	            ]);
	        }
	        
	        // SMS 인증 세션 설정 (자동 로그인이므로 바로 인증된 것으로 처리)
	        $_SESSION['sms_verified_phone'] = $tokenData['phone_number'];
	        $_SESSION['sms_verified_time'] = time();
	        
	        return $this->response->setJSON([
	            'result' => 'true',
	            'msg' => '자동 로그인 가능',
	            'phone_number' => $tokenData['phone_number']
	        ]);
	        
	    } catch (\Exception $e) {
	        log_message('error', '자동 로그인 확인 오류: ' . $e->getMessage());
	        return $this->response->setJSON([
	            'result' => 'false',
	            'msg' => '시스템 오류가 발생했습니다.'
	        ]);
	    }
	}
	
	/**
	 * 자동 로그인 토큰 생성
	 */
	private function generateAuthToken($mem_id, $phone_number)
	{
	    $tokenData = $mem_id . '|' . $phone_number . '|' . time() . '|' . bin2hex(random_bytes(16));
	    return base64_encode(hash('sha256', $tokenData, true));
	}
	
	/**
	 * 모바일 로그아웃 (토큰은 유지)
	 */
	public function mobileLogout()
	{
	    // 세션만 삭제하고 토큰은 유지
	    session()->destroy();
	    
	    return $this->response->setJSON([
	        'result' => 'true',
	        'msg' => '로그아웃되었습니다.'
	    ]);
	}
	
}
