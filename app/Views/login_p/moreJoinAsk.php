<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Asolution</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="/" class="h1"><b>ASolu</b>tion</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">추가 정보를 입력하고 회원가입을 진행해주세요</p>

      <form action="/login/moreJoinProc" method="post" onsubmit="return jsForm_moreJoinProc();" id="morejoin_form">
      	
        <input type="hidden" name="user_email" value="<?=$user_email?>" />
        <input type="hidden" name="user_google_id" value="<?=$user_google_id?>" />
      
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="user_phone" id="user_phone" placeholder="전화번호를 입력하세요">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="user_name" id="user_name" placeholder="한글 이름을 입력하세요">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        
      </form>

      <div class="social-auth-links text-center mt-2 mb-3">
      
        
        <button type="submit" form="morejoin_form" class="btn btn-block btn-info">
          <i class="fab fa-google mr-2"></i> 회원가입 후 로그인
        </button>
        
      </div>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>

<script>
	function jsForm_moreJoinProc()
	{
		if ( $("#user_phone").val() == "" )
		{
			alert("핸드폰 번호를 입력하세요");
			$("#user_phone").focus();
			return false;		
		}
		
		if ( $("#user_name").val() == "" )
		{
			alert("이름을 입력하세요");
			$("#user_name").focus();
			return false;
		}
		
		return true;
	}
</script>


<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
</body>
</html>
