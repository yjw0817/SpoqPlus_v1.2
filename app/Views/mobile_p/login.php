<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Argos SpoQ</title>
  
  <!-- PWA 설정 -->
  <link rel="manifest" href="/manifest.json">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="SpoQ Plus">
  <link rel="apple-touch-icon" href="/images/icon-192.png">
  <meta name="theme-color" content="#007bff">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
  
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  
  <style>
.logo-container {
    width: 100%;
    height: auto;
}

.logo {
    width: 100%;
    height: auto;
}

/* SpoQ 텍스트 */
.SpoQ, .plus {
    font-family: 'Orbitron', sans-serif; /* 멋진 글꼴 */
    font-size: 100px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 5px;
    stroke: #000; /* 검은색 테두리 */
    stroke-width: 2px;
    filter: drop-shadow(5px 5px 10px rgba(0, 0, 0, 0.7)); /* 텍스트 음영 */
    opacity: 0; /* 초기 상태 숨김 */
}

/* SpoQ 애니메이션: 아래에서 위로 */
.SpoQ {
    animation: slideUp 0.5s ease-out forwards;
}

/* PLUS 애니메이션: 위에서 아래로 */
.plus {
    animation: slideDown 0.5s ease-out forwards;
    animation-delay: 0.5s; /* PLUS는 1초 후에 시작 */
}

/* 아래에서 위로 나타나는 애니메이션 */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px); /* 아래에서 시작 */
    }
    to {
        opacity: 1;
        transform: translateY(0); /* 최종 위치 */
    }
}

/* 위에서 아래로 나타나는 애니메이션 */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px); /* 위에서 시작 */
    }
    to {
        opacity: 1;
        transform: translateY(0); /* 최종 위치 */
    }
}

.card {
box-shadow:0 0 0;
}
  
  </style>
  
</head>
<body class="hold-transition login-page" style='background-color:#fff;'>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card" >
    <div class="card-header text-center">
    
    
    	<div class="logo-container">
            <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 240">
                <!-- Gradient Definitions -->
                <defs>
                    <linearGradient id="SpoQ-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#0647a9; stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#8fcbfd; stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="plus-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#a96806; stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#d6c281; stop-opacity:1" />
                    </linearGradient>
                </defs>
                <!-- SpoQ -->
                <text class="SpoQ" x="15" y="80" fill="url(#SpoQ-gradient)">ARGOS</text>
                <!-- PLUS -->
                <text class="plus" x="200" y="200" fill="url(#plus-gradient)">SpoQ</text>
            </svg>
        </div>
    
      
      
      
    </div>
    <div class="card-body">

      <form name="direct_login_form" id="direct_login_form" action="/api/loginProc" method="post" onSubmit="return direct_login_form_submit();">
      	<input type="hidden" name="logintp" id="logintp" value="">
        <div class="input-group mb-3">
          <input type="text" name="login_id" id="login_id" class="form-control" placeholder="아이디">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-portrait"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="login_pass" id="login_pass" class="form-control" placeholder="비밀번호">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12 clearfix" style='margin-bottom:10px'>
          	<button type="submit" class="btn btn-block" style='height:55px;border-radius:35px;background-color:#0698A9;color:#fff;'>로그인</button>
          </div>
          <!-- PWA 설치 버튼 (설치 가능할 때만 표시) -->
          <div class="col-12 clearfix" id="installButton" style="display:none; margin-bottom:10px">
              <button type="button" class="btn btn-block btn-warning" style='height:55px;border-radius:35px;' onclick="installApp()">
                  <i class="fas fa-download"></i> 앱으로 설치하기
              </button>
          </div>
          <!-- Android APK 다운로드 버튼 -->
          <div class="col-12 clearfix" style='margin-bottom:10px'>
              <a href="/downloads/ARGOSSpoQ.apk" class="btn btn-block btn-success" style='height:55px;border-radius:35px;display:flex;align-items:center;justify-content:center;text-decoration:none;color:#fff;' download>
                  <i class="fab fa-android" style="font-size:20px;margin-right:10px;"></i> Android 앱 다운로드
              </a>
          </div>
          <div class="col-12 clearfix">
          	<div class='float-right' onclick="btn_find();"><small>아이디, 비밀번호를 잃어버렸어요.</small></div>
          </div>
          <div class="col-12 clearfix">
          	<div class='float-right' onclick="btn_join();"><small>회원가입</small></div>
          </div>
          <!-- /.col -->
        </div>
      </form>
		
		
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>

<script>
	function direct_login_form_submit()
	{
		var f = document.direct_login_form;
		if ( f.login_id.value == "" )
		{
			alert("아이디를 입력하세요");
			f.login_id.focus();
			return false;
		}
		
		if ( f.login_pass.value == "" )
		{
			alert("비밀번호를 입력하세요.");
			f.login_pass.focus();
			return false;
		}
		
		return true;
		
	}
	
	function btn_join()
	{
		location.href="/api/join_agree";
	}
	
	function btn_find()
	{
		location.href="/api/find_info";
	}
</script>

<script>

$(function () {
    // 웹뷰 또는 iOS 기기에서 앱 다운로드 버튼 숨기기
    if (isWebView() || isIOSDevice()) {
        // Android 앱 다운로드 버튼 숨기기
        $('.btn-success').parent().hide();
        
        if (isWebView()) {
            console.log('웹뷰 환경 감지 - 앱 다운로드 버튼 숨김');
        } else if (isIOSDevice()) {
            console.log('iOS 기기 감지 - Android 앱 다운로드 버튼 숨김');
        }
    }
    
    // URL 파라미터 확인
    var urlParams = new URLSearchParams(window.location.search);
    var clearParam = urlParams.get('clear');
    
    if (clearParam === '1') {
        // clear=1 파라미터가 있으면 토큰 삭제
        localStorage.removeItem('spoq_auth_token');
        console.log('개발자 모드: 자동 로그인 토큰이 삭제되었습니다');
        
        // URL에서 파라미터 제거 (깔끔한 URL 유지)
        window.history.replaceState({}, document.title, window.location.pathname);
    } else {
        // 자동 로그인 체크
        checkAutoLogin();
    }
    
    nb_get();
})

// 자동 로그인 체크 함수
function checkAutoLogin() {
    var authToken = localStorage.getItem('spoq_auth_token');
    if (!authToken) {
        console.log('저장된 토큰이 없습니다');
        return;
    }
    
    console.log('자동 로그인 시도...');
    
    $.ajax({
        url: '/api/checkAutoLogin',
        type: 'POST',
        data: { auth_token: authToken },
        dataType: 'json',
        success: function(result) {
            console.log('자동 로그인 응답:', result);
            
            if (result.result === 'true' && result.phone_number) {
                // 자동 로그인 성공 - SMS 인증된 상태로 로그인 처리
                $.ajax({
                    url: '/api/loginWithPhone',
                    type: 'POST',
                    data: { phone_number: result.phone_number },
                    dataType: 'json',
                    success: function(loginResult) {
                        if (loginResult.result === 'true') {
                            // 새 토큰으로 업데이트
                            if (loginResult.auth_token) {
                                localStorage.setItem('spoq_auth_token', loginResult.auth_token);
                            }
                            window.location.href = loginResult.redirect || '/api/mmmain';
                        }
                    }
                });
            } else {
                // 토큰이 유효하지 않으면 삭제
                localStorage.removeItem('spoq_auth_token');
            }
        },
        error: function() {
            console.log('자동 로그인 확인 실패');
            localStorage.removeItem('spoq_auth_token');
        }
    });
}

function nb_get()
{
	var getPropertyInterface1 = {
        "action" : "getProperty",
        "key" : "uid"
    };
    sendNativeFunction(getPropertyInterface1);
}

function logintp_chk()
{
	var getPropertyInterface1 = {
        "action" : "getProperty",
        "key" : "logintp"
    };
    sendNativeFunction(getPropertyInterface1);
}

// 생체인증 실행하기
function bioAuth() {
    var bioAuthInterface = {
        "action" : "bioAuth",
        "session" : "1234567890"
    };
    sendNativeFunction(bioAuthInterface);
}

// 키패드 실행하기
function kpadAuth() {
	var keypadInterface = {
        "action" : "keypad",
        "isShow" : true,
        "mode" : "shuffle",
        "maxLength" : 6,
        "title" : "간편비밀번호로 로그인하세요",
        "titleTextColor" : "#000000",
        "subTitle" : "",
        "subTitleColor" : "#000000",
    };
    sendNativeFunction(keypadInterface);
}


// Native Callback 받기
function nativeCallback(callbackResult) {
	
	const resultObject = JSON.parse(callbackResult);
	
	if ( resultObject['action'] === "getProperty" )
	{
		if ( resultObject['result']['key'] == 'uid' )
		{
			$('#login_id').val( resultObject['result']['value'] );
			logintp_chk();
		}
		
		if ( resultObject['result']['key'] == 'logintp' )
		{
			if ( resultObject['result']['value'] == 'bio' )
			{
				bioAuth();
			} else if ( resultObject['result']['value'] == 'kpad' )
			{
				kpadAuth();
			} else 
			{
				return false;
			}
		}
	}
	
	// 생체인증 체크하기
	if ( resultObject['action'] === "bioAuth" )
	{
		if ( resultObject['result']['status'] === 'success' )
		{
			$('#login_pass').val(resultObject['result']['session']);
			$('#logintp').val('bio');
			
			// auth 값을 추가해야함.
			$('#direct_login_form').submit();
			
		} else if ( resultObject['result']['status'] === 'fail' )
		{
			return false;
		} else if ( resultObject['result']['status'] === 'unavailable' )
		{
			return false;
		} else 
		{
			return false;
		}
	}
	
	// 키패드인증 체크하기
	if ( resultObject['action'] === "keypad" )
	{
		if ( resultObject['result']['status'] == 'complete' )
    	{
    		$('#logintp').val('kpad');
    		$('#login_pass').val(resultObject['result']['data']); 
			$('#direct_login_form').submit();
    	} else if ( resultObject['result']['status'] == 'close' )
    	{
    		return false;
    	} else 
    	{
    		return false;
    	}
	}
}

// Native Bridge 실행을 위한 기본 설정
function sendNativeFunction(jsonOBJ) {
    if (isIOSApp()) {
        window.webkit.messageHandlers.baseApp.postMessage(JSON.stringify(jsonOBJ));
    } else if(isAndroidApp()) {
        window.baseApp.run(JSON.stringify(jsonOBJ));
    }
}

function isIOSApp() {
  return /iOSApp/.test(navigator.userAgent) && !window.MSStream;
}

function isAndroidApp() {
  return /androidApp/.test(navigator.userAgent);
}

// 웹뷰 감지 함수
function isWebView() {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;
  
  // Android 웹뷰 감지
  if (userAgent.indexOf('wv') > -1) {
    return true;
  }
  
  // AndroidInterface 객체 존재 여부로 판단
  if (typeof window.AndroidInterface !== 'undefined') {
    return true;
  }
  
  // iOS 웹뷰 감지
  if (window.webkit && window.webkit.messageHandlers) {
    return true;
  }
  
  return false;
}

// iOS 기기 감지 함수
function isIOSDevice() {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;
  
  // iPhone, iPad, iPod 감지
  if (/iPhone|iPad|iPod/i.test(userAgent)) {
    return true;
  }
  
  // iOS 13+ iPad 감지 (desktop mode)
  if (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1) {
    return true;
  }
  
  return false;
}

</script>

<!-- PWA 서비스 워커 등록 -->
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}

// PWA 설치 프롬프트
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    // 기본 설치 프롬프트 방지
    e.preventDefault();
    // 나중에 사용할 수 있도록 이벤트 저장
    deferredPrompt = e;
    // 설치 버튼 표시
    showInstallPromotion();
});

function showInstallPromotion() {
    // 설치 안내 버튼 표시
    document.getElementById('installButton').style.display = 'block';
    console.log('앱 설치 가능');
}

// 설치 버튼 클릭 시 호출
function installApp() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('앱이 설치되었습니다');
            }
            deferredPrompt = null;
        });
    }
}
</script>

</body>
</html>
