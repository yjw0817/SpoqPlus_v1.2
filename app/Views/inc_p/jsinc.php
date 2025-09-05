<div id="load_pre">
	<img src="/dist/img/spinner.gif" alt="loading">
</div>

<div class="modal fade" id="modal_top_mem_search_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">회원 검색</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	
            	<!-- FORM [START] -->
                <div class="input-group input-group-sm mb-1">
                	
                	<table class="table table-bordered table-striped col-md-12" id='top_search_mem_table'>
							<thead>
								<tr>
									<th>상태</th>
									<th>이름</th>
									<th>아이디</th>
									<th>전화번호</th>
									<th>생년월일</th>
									<th>성별</th>
									<th>선택</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
					</table>
                	
                </div>
                
            	
            	
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<form name="form_top_search_user" id="form_top_search_user" method="post" action="/ttotalmain/info_mem" />
	<input type="hidden" name="top_search_mem_sno" id="top_search_mem_sno" />
</form>

<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
	<strong>Copyright &copy; 2024 <a href="http://www.SpoQone.com">Argos SpoQ</a>.
	</strong>
			All rights reserved. 
		<div class="float-right d-none d-sm-inline-block">
		<b>Version</b> 1.0
	</div>
</footer>
</div>
<!-- ./wrapper -->
<div id="fr_storeId" data-name="main" style='display:none' ></div>
<!-- REQUIRED SCRIPTS -->

<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 -->


<!-- jQuery -->
<script src="/plugins/moment/moment.min.js"></script>
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jQuery UI -->
<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="/plugins/jquery.maskMoney.js"></script>
<script src="/plugins/jstree/dist/jstree.min.js"></script>
<link href="/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet">
<!-- date-picker -->
<script src="/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ko.min.js"></script>

<?php if ( in_array('fullCalendar', $useJs) ) : ?>
	
	<div id="fullcalendar_proc_url" data-name="<?=$fullCalendar['url']?>" style='display:none' ></div>
	<!-- fullCalendar 5.5.1 -->
	<script src="/plugins/moment/moment.min.js"></script>
	<script src="/plugins/fullcalendar/main.js"></script>
	<script src="/dist/js/amajs/ama_calendar.js?9"></script>
<?php endif; ?>


<!-- select2 -->
<script src="/plugins/select2/js/select2.js"></script>

<!-- toastr -->
<script src="/plugins/toastr/toastr.min.js"></script>

<!-- SweetAlert2 toastr -->
<script src="/plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- inputmask -->
<script src="/plugins/inputmask/jquery.inputmask.min.js"></script>

<!--  cropper -->
<script src="/plugins/cropperjs-main/dist/cropper.js"></script>

<!-- AdminLTE -->
<script src="/dist/js/adminlte.js"></script>

<script>
	window.onload = function() { 
		setTimeout(() => $('#load_pre').hide(),100);
	}
</script>

<script>

var sitenm = "";

$(function () {
	$("#top_search").on("keyup",function(key){
		if(key.keyCode==13) {
			ff_tsearch();
		}
	});
});

function loc_user_info(user_sno)
{
	location.href="/api/tmem_mem_event_list/"+user_sno;
}

//숫자만 리턴 ( - 미포함 )
function onlyNum2(num)
{
	if (num != '' && num != null)
	{
		var re_num = num.toString().replace(/[0-9]/gi, '');
    	var regex = /[^0-9]/g;
    	return num.replace(regex,"");
	}
}

//숫자만 리턴 ( -포함 )
function onlyNum(num)
{
	if (num != '' && num != null)
	{
		var re_num = num.toString().replace(/[^-0-9]/gi, '');
    	var regex = /[^0-9]/g;
    	if(re_num >= 0 )
    	{
    		return num.replace(regex,"");
    	} else 
    	{
    		return "-" + num.replace(regex,"");
    	}
	}
}

// 천단위 콤마 ( -포함 )
function currencyNum(num)
{
	if (num != '' && num != null)
	{
		return num.toString().replace(/(-?)\B(?=(\d{3})+(?!\d))/g, ",");
	}
}
	
var Toast = Swal.mixin({
      toast: true,
      position: 'top',
      showConfirmButton: false,
      timer: 3000,
      width: '500px',
      footer: "Argos SpoQ",
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });

var ToastConfirm = Swal.mixin({
      //toast: true,
      //position: 'top',
      showConfirmButton: false,
      width: '500px',
      footer: "Argos SpoQ",
    }); 

	function alertToast(type,msg)
	{
		Toast.fire({
	        icon: type,
	        title: "  알림 메세지",
	        html: "<font color='#000000' >" + msg + "</font>"
	    });	
	}
	    

	function ff_tsearch()
	{
		$('#top_search_mem_table > tbody > tr').remove();
		var params = "sv="+$('#top_search').val();
    	jQuery.ajax({
            url: '/ttotalmain/top_search_proc',
            type: 'POST',
            data:params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
            dataType: 'text',
            beforeSend:function(){
            	$('#load_pre').show();
            },
            complete:function(){
            	setTimeout(() => $('#load_pre').hide(),100);
            },
            error:function(){
            	alert('로그인 인증이 만료 되었거나 처리중 오류가 있습니다. 다시 로그인 하세요.');
            	location.href="/tlogin";
            },
            success: function (result) {
    			json_result = $.parseJSON(result);
    			if (json_result['result'] == 'true')
    			{
     				//console.log(json_result);
     				
     				console.log(json_result['search_mem_list'].length);
     				
     				if (json_result['search_mem_list'].length == 0)
     				{
     					alertToast('error','검색된 정보가 없습니다.');
     					return;
     				}
     				
     				if (json_result['search_mem_list'].length == 1)
     				{
      					top_buy_user_select(json_result['search_mem_list'][0]['MEM_SNO']);
      					console.log(json_result['search_mem_list'][0]);
     					return;
     				}
     				
    				$('#modal_top_mem_search_form').modal();
    				
    				json_result['search_mem_list'].forEach(function (r,index) {
						var addTr = "<tr>";
						addTr += "<td>" + r['MEM_STAT_NM'] + "</td>";
						addTr += "<td>" + r['MEM_NM'] + "</td>";
						addTr += "<td>" + r['MEM_ID'] + "</td>";
						addTr += "<td>" + r['MEM_TELNO'] + "</td>";
						addTr += "<td>" + r['BTHDAY'] + "</td>";
						addTr += "<td>" + r['MEM_GENDR_NM'] + "</td>";
						addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"top_buy_user_select('"+ r['MEM_SNO'] +"');\">선택</button></td>";
						addTr += "</tr>";
						
						$('#top_search_mem_table > tbody:last').append(addTr);
					});
					
    			} else 
    			{
    				console.log(json_result);
    			} 
            }
        });
	}
	
	function top_buy_user_select (mem_sno)
	{
		location.href="/ttotalmain/info_mem/"+mem_sno;
		//$('#top_search_mem_sno').val(mem_sno);
		//$('#form_top_search_user').submit();
	}

	function ff_tsearch_clear()
	{
		$("#top_tsearch").val('');
	}
	
	$('[data-mask]').inputmask();
	
	// [ Native Bridge ]
	
	// Bridge 호출
	
	// 외부 링크 이동 (callback 없음)
	function nbCall_outLink(url)
	{
		var urlInterface = {
            "action" : "moveOutLink",
            "moveURL" : url
        };
        sendNativeFunction(urlInterface);
	}
	
	// 속성 저장 (callback 없음)
	function nbCall_save(key,value)
	{
		var savePropertyInterface = {
            "action" : "saveProperty",
            "key" : key,
            "value" : value
        };
        sendNativeFunction(savePropertyInterface);
	}
	
	// 속성 가져오기
	function nbCall_get(key)
	{
		var getPropertyInterface = {
            "action" : "getProperty",
            "key" : key
        };
        sendNativeFunction(getPropertyInterface);
	}
	
	// 생체인증 실행
	function nbCall_bio() {
        var bioAuthInterface = {
            "action" : "bioAuth"
        };
        sendNativeFunction(bioAuthInterface);
    }
    
    // 암호화 실행
    function nbCall_enc(value) {
        var encryptInterface = {
            "action" : "encrypt",
            "text" : value
        };
        sendNativeFunction(encryptInterface);
    }
    
    function nbCall_keypad(title='',subtitle='')
    {
    	//mode : shuffle, normal
    	var keypadInterface = {
            "action" : "keypad",
            "isShow" : true,
            "mode" : "shuffle",
            "maxLength" : 6,
            "title" : title,
            "titleTextColor" : "#000000",
            "subTitle" : subtitle,
            "subTitleColor" : "#000000",
        };
        sendNativeFunction(keypadInterface);
    }
	
	// Native Callback 받기
    function nativeCallback(callbackResult) {
    	
    	const resultObject = JSON.parse(callbackResult);
    	
    	// 생체인증 체크하기
    	if ( resultObject['action'] === "bioAuth" ) bdg_bioAuth(resultObject);
    	
    	// 저장 값 가져오기
    	if ( resultObject['action'] === "getProperty" ) bdg_getProperty(resultObject);
    	
    	// 보안키패드 결과값 가져오기
    	if ( resultObject['action'] === "keypad" ) bdg_keypad(resultObject);
    	
    	// 암호화
    	if ( resultObject['action'] === "encrypt" ) bdg_encrypt(resultObject);
    	
    }
    
    // bioAuth
    function bdg_bioAuth(r)
    {
    	if ( r['result']['status'] == 'success' )
		{
			alert('생체인증성공');
		} else if ( r['result']['status'] == 'fail' )
		{
			alert('생체인증 실패');
		} else if ( r['result']['status'] == 'unavailable' )
		{
			alert('생체인증 사용불가능');
		} else 
		{
			alert('기타오류');
		}
    }
    
    // getProperty
    function bdg_getProperty(r)
    {
    	if ( r['result']['key'] == 'uid' )
    	{
    		if (sitenm == 'mmmain')
    		{
    			mmmain_chk_user_set(r['result']['value']);
    		} else 
    		{
    			alert( sitenm ); 
    			alert( r['result']['value'] );
    		}
    	}
    	
    	if ( r['result']['key'] == 'logintp' )
    	{
    		if (sitenm == 'msetting')
    		{
    			msetting_get_logintp(r['result']['value']);
    		} else 
    		{
    			alert( sitenm ); 
    			alert( r['result']['value'] );
    		}
    	}
    	
    	
    }
    
    // keypad
    function bdg_keypad(r)
    {
    	if (sitenm == 'msetting')
    	{
    		if ( r['result']['status'] == 'complete' )
        	{
        		msetting_keypad_result( r['result']['data'] );
        	} else if ( r['result']['status'] == 'close' )
        	{
        		return false;
        	} else 
        	{
        		return false;
        	}
    	} else 
    	{
    		if ( r['result']['status'] == 'complete' )
        	{
        		alert( r['result']['data'] );
        	} else if ( r['result']['status'] == 'close' )
        	{
        		alert('close');
        	} else 
        	{
        		alert('기타');
        	}
    	}
    }
    
    // bdg_encrypt
    function bdg_encrypt(r)
    {
    	alert( r['result']['encryptData'] );
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
</script>
