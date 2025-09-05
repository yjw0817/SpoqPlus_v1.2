<style>
.ditem10 {
    margin: 10px;
    padding: 10px;
    background-color: #b5c1dc57;
    border-radius: 8px;
}

.ditem20 {
    margin: 10px;
    padding: 10px;
    background-color: #05c1dc57;
    border-radius: 8px;
}

.ritem_d {
    margin: 10px;
    padding: 10px;
    border-radius: 8px;
    border: dotted;
}

.aitem1 {
    background-color: #b5c1dc57;
}

.aitem2 {
    background-color: #05c1dc57;
}

</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			
				<div class="card card-primary">
					<!-- CARD HEADER [START] -->
					<div class="page-header">
						<h3 class="panel-title">설문조사 관리</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm">등록하기</button></li>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-sm">상세보기</button></li>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-warning btn-sm">수정하기</button></li>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-danger btn-sm">삭제하기</button></li>
						</ul>
						<!-- BUTTON [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
					
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-3 ditem1">
					
        						<div draggable="true" class="ditem10" id="dd1">
        							<div id="p1">번호</div>
        						</div>
        						<div draggable="true" class="ditem20" id="dd2">
        							<div id="p2">텍스트</div>
        						</div>
        					</div>
        					
        					<div class="col-md-3 ritem">
        						<div class="ritem_d" id="c_i1">
        							<p id="i1">형식2</p>
        						</div>
        						<div class="ritem_d" id="c_i2">
        							<p id="i2">형식2</p>
        						</div>
        						<div class="ritem_d" id="c_i3">
        							<p id="i3">형식3</p>
        						</div>
        						<div class="ritem_d" id="c_i4">
        							<p id="i4">형식4</p>
        						</div>
        						<div class="ritem_d" id="c_i5">
        							<p id="i5">형식5</p>
        						</div>
        						<div class="ritem_d" id="c_i6">
        							<p id="i6">형식6</p>
        						</div>
        					</div>
        					
        					
        				</div>
					
					</div>
					<!-- CARD BODY [END] -->
					<!-- CARD FOOTER [START] -->
					<div class="card-footer clearfix">
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-right">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-sm">등록하기</button></li>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-sm">상세보기</button></li>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-warning btn-sm">수정하기</button></li>
							<li class="ac-btn"><button type="button" class="btn btn-block btn-danger btn-sm">삭제하기</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
						<!-- PAGZING [START] -->
                        <ul class="pagination pagination-sm m-0 float-left">
                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                        </ul>
                        <!-- PAGZING [END] -->
					</div>
					<!-- CARD FOOTER [END] -->
			
				</div>
			
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
})

var set_item;
var temp_item;

const ditem1 = document.querySelector(".ditem1");

ditem1.addEventListener("dragleave", (e) => {
	e.preventDefault();
	if (e.target.id != '')
	{
		temp_item = e.target.id;
		console.log( e.target.id );
	}
});

const ritem = document.querySelector(".ritem");

ritem.addEventListener("dragover",(e) => {
	e.preventDefault();
});

ritem.addEventListener("drop",(e) => {
	e.preventDefault();
	set_item = temp_item;
	
	console.log('타켓정보');
	console.log( set_item );
	console.log('드랍정보');
	console.log( e.target.id );
	
	if (set_item != '')
	{
		if (set_item == "p1")
    	{
    		$("#c_"+(e.target.id)).addClass("aitem1");
    	} else 
    	{
    		$("#c_"+(e.target.id)).addClass("aitem2");
    	}
	}
	
});


// ===================== Modal Script [ START ] ===========================

$("#script_modal_sm").click(function(){
	$("#modal-sm").modal("show");
});

$("#script_modal_default").click(function(){
	$("#modal-default").modal("show");
});

$("#script_modal_lg").click(function(){
	$("#modal-lg").modal("show");
});

$("#script_modal_xl").click(function(){
	$("#modal-xl").modal("show");
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