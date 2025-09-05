<!DOCTYPE html>


<html lang="en" class="hydrated"><head>
	<meta charset="utf-8"><style data-styles="">ion-icon{visibility:hidden}.hydrated{visibility:inherit}</style>
	<title>Argos SpoQ | 모바일 로그인</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="/dist/css/vendor.min.css" rel="stylesheet">
	<link href="/dist/css/apple/app.min.css" rel="stylesheet">
	<!-- ionicons 임시 비활성화 (404 오류 방지) -->
	<!-- <script src="/dist/plugins/ionicons/ionicons.js" type="text/javascript"></script> -->
	<!-- ================== END core-css ================== -->
<style id="monica-reading-highlight-style">
        .monica-reading-highlight {
          animation: fadeInOut 1.5s ease-in-out;
        }

        @keyframes fadeInOut {
          0%, 100% { background-color: transparent; }
          30%, 70% { background-color: rgba(2, 118, 255, 0.20); }
        }
      </style></head>
<body class="pace-done pace-top" monica-id="ofpnmcalabcbjgholdjcjblkibolbppb" monica-version="7.9.1"><div class="pace pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader loaded">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app">
		<!-- BEGIN login -->
		<div class="login login-with-news-feed">
			<!-- BEGIN news-feed -->
			<div class="news-feed">
				<div class="news-image" style="background-image: url(/dist/img/login-bg/login-bg-11.jpg)"></div>
				<div class="news-caption">
					<h4 class="caption-title"><b>ARGOS</b> SpoQ</h4>
					<p>
            DYCIS AI Powered Branch Management System.
					</p>
				</div>
			</div>
			<!-- END news-feed -->
			
			<!-- BEGIN login-container -->
			<div class="login-container">
				<!-- BEGIN login-header -->
				<div class="login-header mb-30px">
					<div class="brand">
						<div class="d-flex align-items-center">
							<span class="logo"><ion-icon name="cloud" role="img" class="md hydrated"></ion-icon></span>
							
							
							<b>ARGOS</b>&nbsp;SpoQ
						</div>
						<small>DYCIS AI Powered Fitness Center Management System</small>
					</div>
					<div class="icon">
						<i class="fa fa-sign-in-alt"></i>
					</div>
				</div>
				<!-- END login-header -->
				
				<!-- BEGIN login-content -->
				<div class="login-content">
					<!-- SMS 인증 안내 - 세련된 디자인 -->
					<div class="text-center mb-4">
						<div class="mb-3">
							<i class="fas fa-mobile-alt fa-3x text-primary opacity-50"></i>
						</div>
						<h5 class="text-gray-800 mb-2">간편 로그인</h5>
						<p class="text-gray-600 fs-13px mb-0">
							전화번호로 간편하게 로그인하세요
						</p>
					</div>
					
					<style>
						.login-content {
							padding: 30px;
						}
						
						.form-control:focus {
							border-color: #4c9aff;
							box-shadow: 0 0 0 0.2rem rgba(76, 154, 255, 0.15);
						}
						
						.btn-primary {
							background: linear-gradient(45deg, #4c9aff, #0066cc);
							border: none;
							transition: all 0.3s ease;
						}
						
						.btn-primary:hover {
							background: linear-gradient(45deg, #2680eb, #0052cc);
							transform: translateY(-1px);
							box-shadow: 0 4px 12px rgba(76, 154, 255, 0.3);
						}
						
						.security-badge {
							position: absolute;
							top: -10px;
							right: 10px;
							background: #28a745;
							color: white;
							font-size: 11px;
							padding: 2px 8px;
							border-radius: 10px;
							display: flex;
							align-items: center;
							gap: 4px;
							z-index: 10;
						}
						
						.security-badge i {
							font-size: 9px;
						}
						
						/* Android 다운로드 버튼 스타일 */
						.btn-success {
							background: linear-gradient(45deg, #28a745, #20c997);
							border: none;
							transition: all 0.3s ease;
						}
						
						.btn-success:hover {
							background: linear-gradient(45deg, #218838, #1aa179);
							transform: translateY(-1px);
							box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
						}
						
						/* 구분선 스타일 */
						.divider-text {
							position: relative;
							text-align: center;
							margin: 20px 0;
						}
						
						.divider-text:before {
							content: '';
							position: absolute;
							top: 50%;
							left: 0;
							right: 0;
							height: 1px;
							background: #e3e3e3;
						}
						
						.divider-text span {
							background: white;
							padding: 0 15px;
							position: relative;
							color: #666;
							font-size: 13px;
						}
						
						/* 전화번호 입력 필드 상태 스타일 */
						.form-floating.has-error .form-control {
							border-color: #dc3545 !important;
						}
						
						.form-floating.has-success .form-control {
							border-color: #28a745 !important;
						}
						
						.error-message {
							font-size: 12px;
							margin-top: 5px;
						}
						
						/* 버튼 비활성화 스타일 */
						button:disabled {
							opacity: 0.6;
							cursor: not-allowed;
						}
					</style>
					
					<form name="direct_login_form" id="direct_login_form" action="/api/loginProc" method="post" onSubmit="return false;" class="fs-13px">
            <input type="hidden" name="logintp" id="logintp" value="">
						<!-- ID/비밀번호 필드 숨김 -->
						<div class="form-floating mb-15px" style="display: none;">
							<input type="text" class="form-control h-45px fs-13px" placeholder="Login ID" name="login_id" id="login_id">
							<label for="login_id" class="d-flex align-items-center fs-13px text-gray-600">User ID</label>
						</div>
						<div class="form-floating mb-15px" style="display: none;">
							<input type="password" class="form-control h-45px fs-13px" placeholder="Password" name="login_pass" id="login_pass">
							<label for="login_pass" class="d-flex align-items-center fs-13px text-gray-600">Password</label>
						</div>
						
						<!-- 전화번호 입력 (항상 표시) -->
						<div class="mb-15px">
							<div class="form-floating" id="phone_input_section">
								<input type="tel" class="form-control h-45px fs-13px" placeholder="전화번호" name="phone_number" id="phone_number" autofocus>
								<label for="phone_number" class="d-flex align-items-center fs-13px text-gray-600">
									<i class="fas fa-mobile-alt me-2"></i>
									전화번호
								</label>
							</div>
							<div class="text-end mt-1">
								<small class="text-muted">
									<i class="fas fa-lock me-1"></i>
									안전한 연결
								</small>
							</div>
						</div>
						
						<!-- SMS 인증 섹션 -->
						<div id="sms_verification_section" style="display: none;">
							<div class="text-center my-3">
								<div class="d-inline-block" style="width: 50px; height: 1px; background: #e3e3e3;"></div>
								<span class="mx-3 text-gray-500 fs-12px">인증번호 입력</span>
								<div class="d-inline-block" style="width: 50px; height: 1px; background: #e3e3e3;"></div>
							</div>
							
							<!-- 인증번호 발송 버튼은 이제 메인 버튼에 통합됨 -->
							
							<!-- 인증번호 입력 -->
							<div class="form-floating mb-15px" id="verification_code_section" style="display: none;">
								<input type="text" class="form-control h-45px fs-13px" placeholder="인증번호" name="verification_code" id="verification_code" maxlength="6">
								<label for="verification_code" class="d-flex align-items-center fs-13px text-gray-600">인증번호 6자리</label>
							</div>
							
							<!-- 타이머 표시 -->
							<div class="text-center mb-3" id="timer_section" style="display: none;">
								<div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-2">
									<i class="far fa-clock text-primary me-2"></i>
									<span class="text-gray-700">남은 시간: <strong id="timer_display" class="text-primary">5:00</strong></span>
								</div>
							</div>
							
							<!-- 재발송 버튼 -->
							<div class="text-center mb-15px" id="resend_section" style="display: none;">
								<button type="button" class="btn btn-link text-decoration-none" onclick="resendSmsVerification()">
									<i class="fas fa-redo me-1"></i>
									인증번호 재발송
								</button>
							</div>
						</div>
						
						<!-- 로그인/인증번호 발송 버튼 -->
						<div class="mb-15px">
							<button type="button" class="btn btn-primary d-block h-45px w-100 btn-lg fs-14px" onclick="processPhoneLogin()">
								<span id="login_button_text">인증번호 발송</span>
							</button>
						</div>
						
						<!-- 구분선 -->
						<div class="divider-text">
							<span>또는</span>
						</div>
						
						<!-- Android APK 다운로드 버튼 -->
						<div class="mb-15px">
							<a href="/downloads/ARGOSSpoQ.apk" class="btn btn-success d-block h-45px w-100 btn-lg fs-14px d-flex align-items-center justify-content-center" download>
								<i class="fab fa-android me-2"></i>
								Android 앱 다운로드
							</a>
						</div>
						
						<!-- 앱 설치 안내 -->
						<div class="text-center mb-3">
							<p class="text-gray-600 fs-12px mb-0">
								<i class="fas fa-info-circle me-1"></i>
								모바일 앱으로 더 편리하게 이용하세요
							</p>
						</div>
            <!--
						<div class="mb-10px pb-10px text-dark">
							회원가입을 원하시면 <a href="#" class="text-primary" onclick="btn_join();">여기</a>를 클릭하세요.
						</div>
            <div class="mb-10px pb-40px text-dark">
							아이디를 찿으시려면 <a href="#" class="text-primary" onclick="btn_find();">아이디 찿기</a>를 클릭하세요.
						</div>
            -->
						<hr class="bg-gray-600 opacity-2">
						<div class="text-gray-600 text-center  mb-0">
							© DYCIS All Right Reserved 2025
						</div>
					</form>
				</div>
				<!-- END login-content -->
			</div>
			<!-- END login-container -->
		</div>
		<!-- END login -->
		
		
		<!-- END theme-panel -->
		<!-- BEGIN scroll-top-btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
		<!-- END scroll-top-btn -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="/dist/js/vendor.min.js" type="text/javascript"></script>
	<script src="/dist/js/app.min.js" type="text/javascript"></script>
	<!-- ================== END core-js ================== -->
	<script async="" src="https://www.googletagmanager.com/gtag/js?id=G-Y3Q0VGQKY3" type="text/javascript"></script>
	<script type="text/javascript">
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
	
		gtag('config', 'G-Y3Q0VGQKY3');
	</script>
<!-- Cloudflare RUM script - disabled for local development -->
<!-- <script defer="" src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon="{&quot;rayId&quot;:&quot;917c61ad49d72f3f&quot;,&quot;version&quot;:&quot;2025.1.0&quot;,&quot;r&quot;:1,&quot;token&quot;:&quot;4db8c6ef997743fda032d4f73cfeff63&quot;,&quot;serverTiming&quot;:{&quot;name&quot;:{&quot;cfExtPri&quot;:true,&quot;cfL4&quot;:true,&quot;cfSpeedBrain&quot;:true,&quot;cfCacheStatus&quot;:true}}}" crossorigin="anonymous"></script> -->

<div id="monica-content-root" class="monica-widget" style="pointer-events: auto;"></div>

<script>
  // SMS 인증 관련 변수
  let smsVerified = false;
  let timerInterval = null;
  let remainingTime = 300; // 5분
  let phoneLoginStep = 'phone'; // 'phone' or 'verify'
  
  $(function () {
      nb_get();
      
      // 웹뷰 또는 iOS 기기에서 앱 다운로드 버튼 숨기기
      if (isWebView() || isIOSDevice()) {
          // Android 앱 다운로드 버튼 숨기기
          $('.btn-success').parent().hide();
          // 구분선도 숨기기
          $('.divider-text').hide();
          // 안내 문구도 숨기기
          $('.btn-success').parent().next().hide();
          
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
      
      // 전화번호 입력 시 포맷팅 및 실시간 검증
      $('#phone_number').on('input', function(e) {
          let value = $(this).val().replace(/[^0-9]/g, '');
          let formatted = '';
          let isValid = true;
          let errorMessage = '';
          
          // 최대 11자리까지만 허용
          if (value.length > 11) {
              value = value.substring(0, 11);
          }
          
          // 전화번호 포맷팅
          if (value.length > 0) {
              // 첫 두자리 검증 (01로 시작해야 함)
              if (value.length >= 2) {
                  if (value.substring(0, 2) !== '01') {
                      isValid = false;
                      errorMessage = '올바른 휴대폰 번호가 아닙니다';
                  }
              }
              
              // 세번째 자리 검증 (010, 011, 016, 017, 018, 019)
              if (value.length >= 3 && isValid) {
                  const validPrefixes = ['010', '011', '016', '017', '018', '019'];
                  const prefix = value.substring(0, 3);
                  if (!validPrefixes.includes(prefix)) {
                      isValid = false;
                      errorMessage = '올바른 휴대폰 번호가 아닙니다';
                  }
              }
              
              // 포맷팅 적용
              if (value.length <= 3) {
                  formatted = value;
              } else if (value.length <= 7) {
                  formatted = value.slice(0, 3) + '-' + value.slice(3);
              } else if (value.length <= 11) {
                  formatted = value.slice(0, 3) + '-' + value.slice(3, 7) + '-' + value.slice(7);
              }
          }
          
          // 포맷된 값 설정
          $(this).val(formatted);
          
          // 에러 메시지 표시/숨김
          const formGroup = $(this).closest('.form-floating');
          const existingError = formGroup.parent().find('.error-message');
          
          if (!isValid && value.length > 0) {
              formGroup.addClass('has-error');
              formGroup.removeClass('has-success');
              
              // 에러 메시지가 없으면 추가
              if (existingError.length === 0) {
                  formGroup.after('<div class="error-message text-danger fs-12px mt-1">' + errorMessage + '</div>');
              } else {
                  existingError.text(errorMessage);
              }
              
              // 버튼 비활성화
              $('button[onclick="processPhoneLogin()"]').prop('disabled', true);
          } else {
              // 유효한 경우 에러 메시지 제거
              formGroup.removeClass('has-error');
              existingError.remove();
              
              // 전체 번호가 입력되었을 때만 버튼 활성화
              if (value.length === 11 && isValid) {
                  $('button[onclick="processPhoneLogin()"]').prop('disabled', false);
                  formGroup.addClass('has-success');
              } else {
                  $('button[onclick="processPhoneLogin()"]').prop('disabled', true);
                  formGroup.removeClass('has-success');
              }
          }
          
          // 전화번호가 완성되면 자동으로 인증번호 발송 안내
          if (value.length === 11 && isValid) {
              $('#login_button_text').html('<i class="fas fa-paper-plane me-1"></i>인증번호 발송');
          } else {
              $('#login_button_text').text('인증번호 발송');
          }
      });
      
      // 백스페이스 처리 개선
      $('#phone_number').on('keydown', function(e) {
          if (e.key === 'Backspace') {
              const cursorPos = this.selectionStart;
              const value = this.value;
              
              // 커서가 하이픈 바로 뒤에 있을 때
              if (cursorPos > 0 && value[cursorPos - 1] === '-') {
                  e.preventDefault();
                  
                  // 하이픈과 그 앞의 숫자를 함께 삭제
                  const newValue = value.substring(0, cursorPos - 2) + value.substring(cursorPos);
                  this.value = newValue;
                  this.setSelectionRange(cursorPos - 2, cursorPos - 2);
                  
                  // input 이벤트 수동 트리거
                  $(this).trigger('input');
              }
          }
      });
      
      // 초기 상태에서 버튼 비활성화
      $('button[onclick="processPhoneLogin()"]').prop('disabled', true);
  })

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
  
  // 자동 로그인 체크
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
        
        if (result && result.result === 'true' && result.phone_number) {
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
          console.log('유효하지 않은 토큰 - 삭제됨');
        }
      },
      error: function(xhr, status, error) {
        console.log('자동 로그인 확인 실패:', error);
        localStorage.removeItem('spoq_auth_token');
      }
    });
  }
  
  // 전화번호 기반 로그인 프로세스
  function processPhoneLogin() {
    const phoneNumber = $('#phone_number').val().replace(/-/g, ''); // 하이픈 제거
    
    if (!phoneNumber) {
      alert('전화번호를 입력해주세요');
      $('#phone_number').focus();
      return;
    }
    
    // 전화번호 유효성 검사
    const phoneRegex = /^01[0-9]{8,9}$/;
    if (!phoneRegex.test(phoneNumber)) {
      alert('올바른 전화번호 형식이 아닙니다');
      $('#phone_number').focus();
      return;
    }
    
    if (phoneLoginStep === 'phone') {
      // 인증번호 발송
      sendPhoneSmsVerification();
    } else if (phoneLoginStep === 'verify') {
      // 인증번호 확인
      verifyPhoneSmsCode();
    }
  }
  
  // 전화번호 기반 SMS 인증번호 발송
  function sendPhoneSmsVerification() {
    const phoneNumber = $('#phone_number').val().replace(/-/g, ''); // 하이픈 제거
    
    // 버튼 비활성화
    $('#login_button_text').html('<i class="fa fa-spinner fa-spin"></i> 발송 중...');
    $('button[onclick="processPhoneLogin()"]').prop('disabled', true);
    
    $.ajax({
      url: '/api/sendSmsVerification',
      type: 'POST',
      dataType: 'json',
      data: {
        phone_number: phoneNumber,
        purpose: 'login'
      },
      success: function(result) {
        
        if (result.result === 'true') {
          alert(result.message);
          
          // 인증번호 입력 섹션 표시
          $('#phone_input_section').hide();
          $('#sms_verification_section').show();
          $('#verification_code_section').show();
          $('#timer_section').show();
          $('#resend_section').show();
          
          // 버튼 텍스트 변경
          $('#login_button_text').text('로그인');
          phoneLoginStep = 'verify';
          
          // 타이머 시작
          startTimer();
          
          // 인증번호 입력 필드에 포커스
          $('#verification_code').focus();
          
          // 인증번호 자동 입력 (실제 SMS 발송 없이 사용)
          if (result.test_code) {
            // 인증번호 자동 입력
            setTimeout(function() {
              $('#verification_code').val(result.test_code);
              
              // 자동으로 인증 진행 (1초 후)
              setTimeout(function() {
                verifyPhoneSmsCode();
              }, 1000);
            }, 500);
          }
          
        } else {
          alert(result.message);
        }
      },
      error: function() {
        alert('SMS 발송 중 오류가 발생했습니다');
      },
      complete: function() {
        $('button[onclick="processPhoneLogin()"]').prop('disabled', false);
        if (phoneLoginStep === 'phone') {
          $('#login_button_text').text('인증번호 발송');
        }
      }
    });
  }
  
  // SMS 인증번호 재발송
  function resendSmsVerification() {
    phoneLoginStep = 'phone';
    $('#phone_input_section').show();
    $('#sms_verification_section').hide();
    $('#login_button_text').text('인증번호 발송');
    
    if (timerInterval) {
      clearInterval(timerInterval);
    }
  }
  
  // 타이머 시작
  function startTimer() {
    remainingTime = 300; // 5분 리셋
    
    if (timerInterval) {
      clearInterval(timerInterval);
    }
    
    timerInterval = setInterval(function() {
      remainingTime--;
      
      const minutes = Math.floor(remainingTime / 60);
      const seconds = remainingTime % 60;
      $('#timer_display').text(minutes + ':' + seconds.toString().padStart(2, '0'));
      
      if (remainingTime <= 0) {
        clearInterval(timerInterval);
        alert('인증 시간이 만료되었습니다. 다시 시도해주세요.');
        $('#verification_code_section').hide();
        $('#timer_section').hide();
        $('#send_sms_section').show();
      }
    }, 1000);
  }
  
  // 전화번호 기반 인증번호 확인
  function verifyPhoneSmsCode() {
    const phoneNumber = $('#phone_number').val().replace(/-/g, ''); // 하이픈 제거
    const verificationCode = $('#verification_code').val();
    
    if (!verificationCode) {
      alert('인증번호를 입력해주세요');
      $('#verification_code').focus();
      return;
    }
    
    // 버튼 비활성화
    $('#login_button_text').html('<i class="fa fa-spinner fa-spin"></i> 인증 중...');
    $('button[onclick="processPhoneLogin()"]').prop('disabled', true);
    
    $.ajax({
      url: '/api/verifySmsCode',
      type: 'POST',
      dataType: 'json',
      data: {
        phone_number: phoneNumber,
        verification_code: verificationCode
      },
      success: function(result) {
        
        if (result.result === 'true') {
          // 타이머 중지
          if (timerInterval) {
            clearInterval(timerInterval);
          }
          
          // 신규 회원 여부 확인
          if (result.is_new_member === true) {
            // 신규 회원인 경우 회원가입 페이지로 이동
            alert('회원 정보가 없습니다. 회원가입 페이지로 이동합니다.');
            window.location.href = '/api/mobile_register?phone=' + encodeURIComponent(phoneNumber);
          } else {
            // 기존 회원인 경우 로그인 진행
            loginWithPhone();
          }
          
        } else {
          alert(result.message);
          $('#verification_code').val('').focus();
          $('#login_button_text').text('로그인');
          $('button[onclick="processPhoneLogin()"]').prop('disabled', false);
        }
      },
      error: function() {
        alert('인증 확인 중 오류가 발생했습니다');
        $('#login_button_text').text('로그인');
        $('button[onclick="processPhoneLogin()"]').prop('disabled', false);
      }
    });
  }
  
  // 전화번호 기반 로그인
  function loginWithPhone() {
    const phoneNumber = $('#phone_number').val().replace(/-/g, ''); // 하이픈 제거
    
    $.ajax({
      url: '/api/loginWithPhone',
      type: 'POST',
      dataType: 'json',
      data: {
        phone_number: phoneNumber
      },
      success: function(result) {
        
        if (result.result === 'true') {
          // 자동 로그인 토큰 저장
          if (result.auth_token) {
            localStorage.setItem('spoq_auth_token', result.auth_token);
            console.log('자동 로그인 토큰 저장됨');
          }
          window.location.href = result.redirect || '/api/mmmain';
        } else {
          alert(result.msg || '로그인에 실패했습니다');
          $('#login_button_text').text('로그인');
          $('button[onclick="processPhoneLogin()"]').prop('disabled', false);
        }
      },
      error: function() {
        alert('로그인 처리 중 오류가 발생했습니다');
        $('#login_button_text').text('로그인');
        $('button[onclick="processPhoneLogin()"]').prop('disabled', false);
      }
    });
  }
  
  // 인증번호 입력 시 자동 확인
  $(document).ready(function() {
    $('#verification_code').on('input', function() {
      if ($(this).val().length === 6) {
        verifyPhoneSmsCode();
      }
    });
    
    // 엔터키 처리
    $('#verification_code').on('keypress', function(e) {
      if (e.which === 13) {
        verifyPhoneSmsCode();
      }
    });
    
    // 전화번호 입력 필드에서 엔터키 처리
    $('#phone_number').on('keypress', function(e) {
      if (e.which === 13) {
        processPhoneLogin();
      }
    });
  });

</script>
</body>
</html>
