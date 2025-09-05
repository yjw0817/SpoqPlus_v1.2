<style>

</style>
<script language="javascript" type="text/javascript" src="https://tstdpay.paywelcome.co.kr/stdjs/INIStdPay.js" charset="UTF-8"></script>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
		<div class="row">
			<div class="col-md-12" style='margin:10px'>
				
				
			<form name="payForm" method="post" accept-charset="euc-kr">
			
			<li class="list-group-item">
                <b>가맹점</b> <a class="float-right"><?php echo $info['p_mname'] ?></a>
            </li>
            <li class="list-group-item">
                <b>결제 상품명</b> <a class="float-right"><?php echo $info['p_goods'] ?></a>
            </li>
            <li class="list-group-item">
                <b>결제금액</b> <a class="float-right"><?php echo number_format($info['price'])?> 원</a>
            </li>
            <li class="list-group-item">
                <b>결제 회원명</b> <a class="float-right"><?php echo $info['user_name'] ?></a>
            </li>
            <li class="list-group-item">
                <b>회원 휴대폰번호</b> <a class="float-right"><?php echo $info['user_hp'] ?></a>
            </li>
            <li class="list-group-item">
                <b>회원 이메일</b> <a class="float-right"><input type="text" style='width:150px' name="P_EMAIL" value="<?php echo $info['user_email']; ?>" /></a>
            </li>
			
			
			<table class="table table-bordered table-hover col-md-12">
			
<input type="hidden" name="od_id" id="od_id" value="<?php echo $info['od_id'] ?>" />
<input type="hidden" name="P_NOTI" value="<?php echo $info['p_noti'] ?>" />
		<tr style="display:none;">
			<td class="key">P_MID</td>
			<td>
				<select style="width:48%; height:25px; margin-bottom:5px;" name="MID" onchange="mid_change(this.form)">
					<option value="<?php echo $info['mid'] ?>"><?php echo $info['mid'] ?></option>
					<option value="0">직접입력</option>
				</select>
				<br>
				<input type="text" name="P_MID" id="P_MID" value="<?php echo $info['mid'] ?>" readonly="readonly" />
			</td>
		</tr>
		
		<!-- P_OID(가맹점 주문번호) -->
		<input type="hidden" name="P_OID" id="P_OID" value="<?php echo $info['oid'] ?>" />
		<!-- P_AMT(금액) -->
		<input type="hidden" class="P_AMT" name="P_AMT" id="P_AMT" value="<?php echo $info['price'] ?>" />
		<!-- P_UNAME(고객명) -->
		<input type="hidden" name="P_UNAME" value="<?php echo $info['user_name'] ?>" />
		<!-- P_MNAME(가맹점) -->
		<input type="hidden" name="P_MNAME" value="<?php echo $info['p_mname'] ?>" />
		<!-- P_GOODS(상품명) -->
		<input type="hidden" name="P_GOODS" value="<?php echo $info['p_goods'] ?>" />
		<!-- P_MOBILE(고객 휴대폰번호) -->
		<input type="hidden" name="P_MOBILE" value="<?php echo $info['user_hp'] ?>" />
		
		<tr style="display:none;">
			<td>P_CHARSET(인증/승인 결과 수신 CHARSET)</td>
			<td><select name="P_CHARSET" id="P_CHARSET"> 
				<option value="" selected>없음</option>
				<option value="utf8">utf8</option>
			</select>
			</td>
		</tr>
		
		<tr style="display:none;">
			<td>P_NEXT_URL(인증결과 수신 URL)</td>
			<td><input type="text" name="P_NEXT_URL" value="<?php echo $info['siteDomain'] ?>/nextUrl"/> </td>
		</tr>
		<tr style="display:none;">
			<td>P_RETURN_URL</td>
			<td><input type="text" name="P_RETURN_URL" id="return" value="<?php echo $info['siteDomain'] ?>/return" ></td>
		</tr>
		<tr style="display:none;">
			<td>P_NOTI_URL(노티수신 URL)</td>
			<td><input type="text" name="P_NOTI_URL" id="P_NOTI_URL" value="<?php echo $info['siteDomain'] ?>/noti" /></td>
		</tr>
		
		<tr style="display:none;">
			<td>P_TAX</td>
			<td><input type="text" name="P_TAX" value="" ></td>
		</tr>
		
		<tr style="display:none;">
			<td>P_TAXFREE</td>
			<td><input type="text" name="P_TAXFREE" value="" ></td>
		</tr>
		
		<tr style="display:none;">
			<td>P_TIMESTAMP</td>
			<td><input type="text" name="P_TIMESTAMP" value="<?php echo $info['timestamp'] ?>" ></td>
		</tr>
		<tr style="display:none;">
			<td>P_SIGNATURE</td>
			<td><input type="text" id="signature" name="P_SIGNATURE" value="<?php echo $info['sign'] ?>" ></td>
		</tr>
		<tr style="display:none;">
			<td rowspan="2">P_OFFER_PERIOD</td>
			<td><input type="text" name="P_OFFER_PERIOD" value="" size="50"></td>
		</tr>
	</table>

	<div id="middle">
		<table border="1" style="width:100%; display:none;" class="option-table">
			<tr>
				<td rowspan="1">P_CARD_OPTION</td>
				<td>
					<input type="text" name="P_CARD_OPTION" value="" />
					<br />
					selcode="카드코드"(selcode=14) 결제 카드 선택시 우선으로 보임(selected, 간편결제 불가능) visa3d만 : onlycard=visa3d isp만 : onlycard=isp 간편결제만 : onlycard=easypay (selcode=14:onlycard=visa3d)
				</td>
			</tr>
			<tr>
				<td rowspan="1">P_ONLY_CARDCODE</td>
				<td>
					<input type="text" name="P_ONLY_CARDCODE" value="" >
					<br/>
					가맹점 선택 카드코드 (예)03:롯데,01:외환,11:bC를 설정한 경우, 03:01:11 로 설정
				</td>
			</tr>
			<tr>
				<td rowspan="1">P_ONLY_EASYPAYCODE</td>
				<td>
					<input type="text" name="P_ONLY_EASYPAYCODE" value="" >
					<br/>
					가맹점 선택 간편결제코드 (예)KAKAOPAY:카카오페이,LPAY:엘페이,PAYCO:페이코를 설정한 경우, KAKAOPAY:LPAY:PAYCO 로 설정
				</td>
			</tr>			
			<tr>
				<td rowspan="1">P_QUOTABASE</td>
				<td>
					<input type="text" name="P_QUOTABASE" value="" >
					<br />
					가맹점 선택 할부기간 지정(36개월 MAX)
					<br/>(예) 01:02:03:04... 01은 일시불, 02는 2개월, 99은 일시불 없애는 옵션. 등등
				</td>
			</tr>
			<!-- P_HPP_METHOD -->
			<tr>
				<td>P_HPP_METHOD</td>
				<td><input type="text" name="P_HPP_METHOD" value="2" > 
				<br>(1:컨텐츠 , 2: 실물)
				</td>
			</tr>
			<tr>
				<td>P_VBANK_DT</td>
				<td><input type="text" name="P_VBANK_DT" value="" ></td>
			</tr>
			<tr>
				<td>P_VBANK_TM</td>
				<td><input type="text" name="P_VBANK_TM" value="" ></td>
			</tr> 
			<tr>
				<td class="key">P_RESERVED</td>
				<td>
				<textarea name="P_RESERVED" id="reserved" style="width:100%; height:25px" onKeyDown="reserved_keyDown(this);"></textarea><br />
				복합 파라미터<br>
			</tr>
			<tr>
				<td><b>P_RESERVED 설명</b></td>
				<td>
					<b>1. ISP 관련 옵션</b><br/>
					1) ISP앱 새창 방지 옵션 & ISP 2트렌젝션<br/>
					block_isp=Y&twotrs_isp=Y
					<input type="checkbox" name="p_rsd" value="twotrs_isp=Y&block_isp=Y&" onclick="reserved_change()" checked><br />
					2) isp 2trs 노티 미발생 옵션<br/>
					twotrs_isp_noti=N
					<input type="checkbox" name="p_rsd" value="twotrs_isp_noti=N&" onclick="reserved_change()" checked>
					3)  휴대폰 사용 통신사 옵션<br/>
					hpp_corp=SKT:KTF:LGT
					<input type="checkbox" name="p_rsd" value="hpp_corp=KTF:LGT:MVNO&" onclick="reserved_change()" checked>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>2. 카드포인트 사용하기 옵션</b><br/>
					1) cp_yn=Y
					<input type="checkbox" name="p_rsd" value="cp_yn=Y&" onclick="reserved_change()"><br/>
					<font color="red"><b>단, 직접 호출 설정시에는 </b></font> dcp_yn=Y<b> 를 사용합니다.</b><br />
					2) cp_yn=Y&cp_option=03
					<input type="checkbox" name="p_rsd" value="cp_yn=Y&cp_option=03&" onclick="reserved_change()"><br/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>3. 안심클릭/ISP 결제창 직접 호출 옵션</b><br/>
					카드사&할부> d_card=00(코드)&d_quota=00(개월)<input type="checkbox" name="p_rsd" value="d_card=&d_quota=&" onclick="reserved_change()"><br/>
					ex> d_card=04&d_quota=03<br/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>4. 상점무이자 옵션</b><br/>
					사용여부 > merc_noint=Y<br/>
					설정방법 > noint_quota=00-00:00(카드-개월:개월)<br/>
					** <b>[카드-월:월]^</b> 카드는 00 두자리, 할부개월 01->1 형태, <font color="red"><b>잘못된 예 ></b></font>  <b>[카드-월:월<font color="red">:</font>]^</b> **<br/>
					ex> merc_noint=Y&noint_quota=11-2:3<font color="red"><b>^</b></font>06-3:6:9<font color="red"><b>^</b></font>03-8:9 <font color="red"><b>카드,개월 직접 입력</b></font>
					<input type="checkbox" name="p_rsd" value="merc_noint=Y&noint_quota=04-7:9^06-6:9:12^12-9:10:12&" onclick="reserved_change()">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>5. 앱 설치 유/무 체크</b><br/>
					apprun_check=Y
					<input type="checkbox" name="p_rsd" value="apprun_check=Y&" onclick="reserved_change()" checked>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>7. below1000(1000원 미만 결제허용 / 미사용 : 지정안해주면 자동 미사용)</b><br/>
					사용 : below1000=Y
					<input type="checkbox" name="p_rsd" value="below1000=Y&" onclick="reserved_change()">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<b>8. 가상계좌 현금영수증사용유무,가상계좌는 기본미사용</b><br/>
					vbank_receipt=Y
					<input type="checkbox" name="p_rsd" value="vbank_receipt=Y&" onclick="reserved_change()">
				</td>
			</tr>
	</div>
</form>

<div id="btnSection"></div>

				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
                </table>
			
			
			
			
			</div>
			<button type="button" class="btn btn-block btn-sm" style='height:50px;background-color:#0698A9;color:#fff;' onclick="pay_submit('visa3d','<?php echo $info['call_url']?>','_self');">모바일 결제</button>
		</div>
		
		
		
	</div>
	
	<div class="row">
        <div class="col-md-12">
        	<div class="a-warning">
                <div class="a-item">
                	<div class='a-item-sec item-center item-bold ft-red'>
                		유의사항
                		<span class="item-bold ft-default item-light"></span>
                	</div>
                </div>
                <div class="a-item">
                	<div class='a-item-sec'>
                		<div class='' style='font-size:0.9rem'>
                			<div>- 최종 결제 금액을 꼭 확인하세요.</div>
                    		<div>- 모바일 결재의 환불은 센터에 문의 하세요.</div>
                    		<div>- 회원 이메일을 입력하시면 영수증을 확인 할 수 있습니다.</div>
                    		<div>- 모바일 결제 버튼 클릭 후 결제 페이지가 뜰떄까지 잠시만 기다려주세요</div>
                    		<div>- 결제 종류에 따라서 설치가 필요한 앱이 있을 수 있습니다.</div>
                		</div>
                		
                	</div>
                </div>
            </div>
        </div>
    </div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})


//지불 수단 별 호출 URL
	//paymethod : 신용카드 (visa3d), 휴대폰(mobile), 가상계좌(vbank), 계좌이체(bank)  
	//call_url : 호출할 도메인
	//type : form target 설정 (현재창:self 새창:self외)
	function pay_submit(paymethod,call_url,type){
		debugger;
		var payForm = document.payForm;
		
		if (type == 'self') {
			payForm.target = "_self";
		} else {
			payForm.target = "_blank";
		}
		payForm.target = "_self";
		
		if(call_url.substr(-1,1).indexOf("/")<0){
			call_url+="/";
		}
		
		if (paymethod == 'visa3d') {
			payForm.action = call_url + "smart/wcard/";
		} else if (paymethod == 'mobile') {
			payForm.action = call_url + "smart/mobile/";
		} else if (paymethod == 'vbank') {
			payForm.action = call_url + "smart/vbank/";
		} else if (paymethod == 'bank') {
			payForm.action = call_url + "smart/bank/";
		} else {
			alert('등록되지 않은 지불 수단 입니다(paymethod:' + paymethod + ')');
			return;
		}
		document.charset = 'euc-kr';
		payForm.submit();
	}	
	
	/* 
		아래부터 현재 페이지 편의성을 위한 script.
		실제 개발시 제거해도 무방 
	*/
	// mid select box 직접입력 함수
	function mid_change(userinput) {
		var pmid = userinput.MID.value;
	
		if(pmid == "0") {
			userinput.P_MID.value="";
			userinput.P_MID.readOnly=false;
		} else {
			userinput.P_MID.value=pmid;
			userinput.P_MID.readOnly=true;
		}
	}
	
	// checked된 항목 P_RESERVED필드 추가하기 위한 함수 
	function reserved_change(){
		var checkboxs = document.getElementsByName("p_rsd");
		var rVal = "";
		for(i=0; i<checkboxs.length; i++){
			if(checkboxs[i].checked)
				rVal += checkboxs[i].value;
		}
		
		document.getElementById("reserved").value = rVal;
	}
	
	// P_RESERVED필드 height 조절 위한 함수
	function reserved_keyDown(obj){
		var reserved_value=document.getElementById("reserved").value;
		document.getElementById("reserved").setAttribute("height",(reserved_value.length/50*15)+15);
	}
	



function loc1(cate1,prod)
{
	location.href="/api/event_buy1_1/"+cate1+"/"+prod;
}

$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
});

$("#bottom-menu-close").click(function(){
	$(".overlay").hide();
});

// ===================== Modal Script [ START ] ===========================

$("#script_modal_default").click(function(){
	$("#modal-default").modal();
});

// ===================== Modal Script [ END ] =============================

//Date picker
$('.datepp').datepicker({
    format: "yyyy-mm-dd",	//데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
    autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
    clearBtn : false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
    immediateUpdates: false,	//사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false 
    multidate : false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false 
    templates : {
        leftArrow: '&laquo;',
        rightArrow: '&raquo;'
    }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징 
    showWeekDays : true ,// 위에 요일 보여주는 옵션 기본값 : true
    title: "날짜선택",	//캘린더 상단에 보여주는 타이틀
    todayHighlight : true ,	//오늘 날짜에 하이라이팅 기능 기본값 :false 
    toggleActive : true,	//이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
    weekStart : 0 ,//달력 시작 요일 선택하는 것 기본값은 0인 일요일 
    
    //startDate: '-10d',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
    //endDate: '+10d',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
    //datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
    //daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
    //daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
    //disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
    //calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
    //multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01
    
    language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

</script>