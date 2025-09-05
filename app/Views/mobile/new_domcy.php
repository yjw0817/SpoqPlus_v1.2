<style>

.datepicker 
{
    top:50px;
    text-align:center;
    width:100% !important;
    left:0px !important;
}

.datepicker.datepicker-days
{
    width:100% !important;
}


.overlay {
    position: fixed;
    bottom: -500px;  /* 메뉴는 화면 밖에 시작 */
    left: 0;
    width: 100%;
    height: 500px;
    z-index: 1000;
    overflow-y: auto;
    transition: bottom 0.3s ease-in-out;  /* 슬라이드 애니메이션 */
    /* transition: all 600ms cubic-bezier(0.86, 0, 0.07, 1); */
}

.domcy-box {
  margin-top:10px;
  display: flex;
  align-items: center; /* 세로 가운데 정렬 */
  justify-content: center; /* 가로 가운데 정렬 */
  position: relative;
  width: 100%;
  height: 70px;
  border: 1px solid #eee;
  border-radius: 5px; /* 모서리를 5px 둥글게 처리 */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06); /* 그림자 추가 */
  background-color: #fff; /* 배경색 추가 (필요시) */
}

.domcy-content-left,
.domcy-content-right {
  width: 50%;
  text-align: center;
}

.domcy-box::before {
  content: '';
  position: absolute;
  height: 70%; /* 선의 높이를 90%로 설정 */
  width: 1px; /* 기본 너비 */
  background-color: #aaa; /* 선 색상 */
  left: 50%; /* 가로 정 가운데 */
  top: 50%; /* 세로 정 가운데 기준 */
  transform: translate(-50%, -50%) scaleX(0.5); /* 너비를 0.5로 축소 */
  transform-origin: center; /* 축소 기준점 설정 */
}
.bottom-menu {
    transition: none; /* 불필요한 애니메이션 제거 */
}

.btn-close {
    position: absolute; /* 절대 위치 */
    top: 15px; 
    right: 10px;       /* 오른쪽에서 10px */
    transform: scale(1); /* 버튼을 80% 크기로 축소 */
    z-index: 1000;
    display: block !important; /* 버튼이 항상 보이도록 강제 설정 */
}
.app-content{
    padding:3px;
}
#domcy_acppt_i_cnt {
    padding-right: 20px; /* "분" 공간 확보 */
    text-align: right; /* 텍스트 우측 정렬 (선택 사항) */
}
.unit-label {
    position: absolute;
    right: 10px; /* input 오른쪽 안쪽 여백 */
    top: 50%;
    transform: translateY(-50%); /* 수직 가운데 정렬 */
    pointer-events: none; /* 클릭 방지 */
    color: #6c757d; /* 텍스트 색상 (Bootstrap text-body와 유사) */
}
.bottom-menu-area::-webkit-scrollbar {
    width: 8px;
}

.bottom-menu-area::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.bottom-menu-area::-webkit-scrollbar-track {
    background: #f1f1f1;
}


</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
        <div class="row ama-header1">
            <div class="ama-header-card">
                <div class="ama-title">휴회 신청하기</div>
                <div class="ama-message" style="font-size:0.9rem; ">
                    <div style="text-align: left;">
                        <div>한동안 이용이 어려우신가요?</div>
                        <div>휴회 가능일과 횟수가 남아있다면</div>
                        <div>언제든 휴회가 가능해요.</div>
                    </div>
                    <div class="text-right bottom-menu" style="color:#4ED1E2; text-align: right; transition: none;">
                        휴회신청하기 
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row ama-header2">
            <div class="col-md-12">
            	<div class="stats-container col-md-12">
                    <div class="stat-item">
                        <div class="number reservation"><?php echo $poss_domcy['day']?>일</div>
                        <div class="number-label">휴회 가능일</div>
                    </div>
                    <div class="stat-item">
                        <div class="number used"><?php echo $poss_domcy['cnt']?>회</div>
                        <div class="number-label">휴회 가능횟수</div>
                    </div>
                </div>
            
            </div>
        </div>

        
            <div class="row">
                <div class="col-12" style="padding:0px;">
                    <div class="domcy-box">
                        <div class="domcy-content-left">
                            <div>휴회 사용 가능일</div>
                            <?php foreach ($poss_domcy['list'] as $row) : ?>
                                <div>[<?php echo $row['sell_event_nm']?>] <?php echo $row['day']?>일(<?php echo $row['cnt']?>회)</div>
                            <?php endforeach; ?>
                        </div>
                        <div class="domcy-content-right"> 
                        <div>휴회 사용 가능기간</div>
                        <?php foreach ($poss_domcy['list'] as $row) : ?>
                            <div><?php echo $row['s_date']?> ~ <?php echo $row['e_date']?></div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        
        
        <div class="row">
            <div class="col-md-12" style="padding:0px;">
            	<div class='a-title'>휴회 현황</div>
            	<?php if ( count($domcy_list) > 0) :?>
            	<?php foreach($domcy_list as $r) :?>
            	<div class="a-list">
                    <div class="a-item">
                    	<div class='a-item-sec item-center item-bold ft-sky'>
                    		<h5><?php echo $sDef['DOMCY_MGMT_STAT'][$r['DOMCY_MGMT_STAT']]?></h5>
                    		<!-- <span class="item-bold ft-default">추천강사 : 이시영</span> -->
                    	</div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">
                    			<span class=""><?php echo $r['DOMCY_S_DATE']?> ~ <?php echo $r['DOMCY_E_DATE']?></span>
                    		</span>
                    		<span class="ft-blue item-bold"><?php echo $r['DOMCY_USE_DAY']?>일간</span>
                    	</div>
                    	<div class="a-item-line"></div>
                    </div>
                    <div class="a-item">
                    	<div class='a-item-sec'>
                    		<span class="ft-default">
                    			<span class="">신청일 : <?php echo substr($r['CRE_DATETM'],0,10)?></span>
                    		</span>
                    		<div class='item-btn-area'>
                    			<!-- <div class="btn bga-red">취소하기</div> -->
                    		</div>
                    	</div>
                    </div>
                </div>
                <?php endforeach;?>
                <?php else :?>
            	<div class="a-list">
                    <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                    	휴회 신청 내역이 없습니다.
                    </div>
                </div>
            	<?php endif;?>
            </div>
        </div>
        
        
        <div class="overlay" id='ooooo'>
            <div class="row">
            	<div class="col-12">
                    <div id="bottom-menu-area">
                    <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                        <br />
                        <h4 class='modal-title text-center'>휴회신청</h4>
                        <div class="card card-success" style="padding-top:10px; margin-top:15px;">
                            <div class="row">
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="flex-fill">
                                        <label class="form-label col-form-label col-md-3">휴회시작일</label>
                                        <div class="d-flex align-items-center text-body text-opacity-60">
                                            <div class="text-body text-opacity-60 use-prod-width me-2">
                                                <input class="form-control" type="text"  name="domcy_acppt_i_sdate" id="domcy_acppt_i_sdate"  >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-fill" id="lesson-cnt-section" >
                                        <label class="form-label col-form-label col-md-3">휴회 신청일수</label>
                                        <div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center me-2">
                                            <input class="form-control" type="text" name="domcy_acppt_i_cnt" id="domcy_acppt_i_cnt" onkeyup="daycnt_calu_date();">
                                            <span class="unit-label">일</span>
                                        </div>
                                    </div>
                                    <div class="flex-fill" style="visibility:hidden">
                                        <label class="form-label col-form-label col-md-3">휴회 종료일</label>
                                        <div class="text-body text-opacity-60 sell-price-width position-relative d-flex align-items-center">
                                            <input class="form-control "  id="domcy_acppt_e_sdate" readonly >
                                        </div>
                                    </div>		
                                </div>
                            </div>
                            
                            <div class="a-list" id="a-list">
                            </div>
                            <button type="button" class='btn btn-block btn-success btn-sm p-3 bottom-menu-submit' style="text-align:center">휴회신청 등록하기</button>
                        </div>
                       
                        <div id="domcy_template" style="display:none">
                            <div class="a-item" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 8px;">
                                <div class="row align-items-center">
        
                                    <!-- ✅ 첫 번째 열: 체크박스 -->
                                    <div class="col-auto d-flex justify-content-center align-items-center">
                                        <input type="checkbox" class="item-check" id="item_check" style="transform: scale(1.2);">
                                    </div>
                                    
                                    <!-- ✅ 두 번째 열: 휴회 기간 + 남은 휴회일 -->
                                    <div class="col d-flex flex-column justify-content-center">
                                    <div class="mb-1" id="title">GX</div>
                                    <div class="ft-default mb-1">휴회 기간: 2025-04-30 ~ 2025-06-28</div>
                                    <div class="ft-left">남은 휴회 일: 0일 / 남은 휴회횟수: 2회</div>
                                    </div>
                                    
                                    <!-- ✅ 세 번째 열: 사용일 입력 -->
                                    <div class="col-auto d-flex align-items-center">
                                    <span class="ft-blue item-bold">
                                        <input type="text" id="cnt" value="60" style="width: 40px; text-align: right; margin-right: 5px;">일 사용
                                    </span>
                                    </div>
                                </div>

                                <!-- hidden fields -->
                                <input type="hidden" id="sell_event_sno">
                                <input type="hidden" id="domcy_s_date">
                                <input type="hidden" id="domcy_e_date">
                                <input type="hidden" id="domcy_day">
                            </div>
                        </div>
                        



                        <!-- <div class='modal-content' style='margin-top:15px;'>
                            <div class="card card-success">
                                <div class="panel-body">
                                    
                                    <br>
                                    
                                    <br>
                                    
                                </div>
                            </div>
                            <button type="button" class='btn btn-block btn-success btn-sm p-3 bottom-menu-submit'>휴회신청 등록하기</button>
                        </div> -->
                    </div>
            	</div>
            </div>
        </div>
	</div>
	
    <!-- ############################## MODAL [ SATRT ] #################################### -->
    <!-- ============================= [ modal-default START ] ======================================= -->	

    <!-- ============================= [ modal-default END ] ======================================= -->
    <!-- ############################## MODAL [ END ] ###################################### -->

    <form name="form_domcy" id="form_domcy" method="post" action="/ttotalmain/ajax_domcy_acppt_proc">
        <input type="hidden" name="fc_domcy_cnt" id="fc_domcy_cnt" value="<?php echo $poss_domcy['cnt']?>" />
        <input type="hidden" name="fc_domcy_day" id="fc_domcy_day" value="<?php echo $poss_domcy['day']?>" />
        <input type="hidden" name="fc_domcy_buy_sno" id="fc_domcy_aply_buy_sno" value="<?php echo $poss_domcy['buy_event_sno']?>" />
        <input type="hidden" name="fc_domcy_mem_sno" id="fc_domcy_mem_sno" value="<?php echo $_SESSION['user_sno']?>" />
        <input type="hidden" name="fc_domcy_s_date" id="fc_domcy_s_date" />
        <input type="hidden" name="fc_domcy_use_day" id="fc_domcy_use_day" />
    </form>
</section>

<?=$jsinc ?>

<script>
    
    let possDomcyList = <?php echo json_encode($poss_domcy['list']); ?>;
    $(function () {
        $('.select2').select2();
    })

    document.addEventListener("scroll", () => {
        const scrollY = window.scrollY;
        document.body.classList.toggle("scrolled", scrollY > 120);
    });

    function daycnt_calu_date()
    {
        var sDate = $('#domcy_acppt_i_sdate').val();
        if ( sDate == '' )
        {
            alertToast('error','휴회신청일을 먼저 선택하세요');
            $('#domcy_acppt_i_cnt').val('');
            return;
        }
        
        var result = new Date(sDate);
        var addDay = $('#domcy_acppt_i_cnt').val();
        
        result.setDate(result.getDate() + Number(addDay - 1));
        
        var date_y = result.getFullYear();
        var date_m = result.getMonth()+1;
        var date_d = result.getDate();
        
        var result_date = date_y+"-"+(("00"+date_m.toString()).slice(-2))+"-"+(("00"+date_d.toString()).slice(-2));
        
        $('#domcy_acppt_e_sdate').val(result_date);
        autoAssignDomcy($('#domcy_acppt_i_sdate').val(), $('#domcy_acppt_i_cnt').val());
        
    }

    $(".bottom-menu").click(function() {
        if (isDomcyAvailable()) {
            $('#domcy_acppt_i_sdate').datepicker('destroy').datepicker({
                autoclose: true,
                language: "ko",
                startDate: new Date(new Date().setDate(new Date().getDate() + 1)), // ✅ 오늘 이후만 선택 가능
                todayHighlight: true
            }).on('changeDate', () => {
                autoAssignDomcy($('#domcy_acppt_i_sdate').val(), $('#domcy_acppt_i_cnt').val());
            });

            const slideMenu = document.getElementById('ooooo');
            slideMenu.style.bottom = '0px';  // 메뉴 올라오게

            // bottom-menu-area 동적 높이 설정 제거 가능 (스크롤 처리하므로)
            $('#bottom-menu-area').css("height", "auto");
        }
    });


    




    function renderAssignmentResult(result) {
        const assignedContainer = document.getElementById('assignedDates');
        const unassignedContainer = document.getElementById('unassignedDates');

        assignedContainer.innerHTML = '';
        result.assignedDates.forEach(item => {
            assignedContainer.innerHTML += `<div>${item.date} - ${item.buy_event_sno} 사용 예정</div>`;
        });

        unassignedContainer.innerHTML = '';
        result.unassignedDates.forEach(date => {
            unassignedContainer.innerHTML += `<div style="color:red;">${date} - 휴회 불가</div>`;
        });
    }

    function isDomcyAvailable()
    {
        let poss_domcy_day = <?php echo $poss_domcy['day']?>;
        let poss_domcy_cnt = <?php echo $poss_domcy['cnt']?>;
        if(poss_domcy_day == 0)
        {
            alertToast('error','사용 가능한 휴회일이 없습니다.');
            return false;
        } else if(poss_domcy_cnt == 0)
        {
            alertToast('error','사용 가능한 휴회 횟수를 모두 소진하였습니다.');
            return false;
        }
        return true;
    }

   

    $(".bottom-menu-submit").click(function(){
        $('#fc_domcy_s_date').val($('#domcy_acppt_i_sdate').val());
        $('#fc_domcy_use_day').val($('#domcy_acppt_i_cnt').val());

        autoAssignDomcy($('#domcy_acppt_i_sdate').val(), $('#domcy_acppt_i_cnt').val());
        
        // var params = $("#form_domcy").serialize();
        // jQuery.ajax({
        //     url: '/api/ajax_domcy_acppt_proc',
        //     type: 'POST',
        //     data:params,
        //     contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        //     dataType: 'text',
        //     success: function (result) {
        //     	if ( result.substr(0,8) == '<script>' )
        //     	{
        //     		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        //     		location.href='/login';
        //     		return;
        //     	}
        //         json_result = $.parseJSON(result);
        // 		if (json_result['result'] == 'true')
        // 		{
        // 			location.reload();
        // 		}
        //     }
        // }).done((res) => {
        // 	// 통신 성공시
        // 	console.log('통신성공');
        // }).fail((error) => {
        // 	// 통신 실패시
        // 	console.log('통신실패');
        // 	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
        // 	location.href='/login';
        // 	return;
        // });
        
    });

    $("#bottom-menu-close").click(function(){
        const slideMenu = document.getElementById('ooooo');
        slideMenu.style.bottom = '-500px';  // 메뉴 닫기
    });

    // ===================== Modal Script [ START ] ===========================

    $("#script_modal_default").click(function(){
        $("#modal-default").modal("show");
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

    // parentId의 하위 요소들을 복사하여 반환
    function cloneChildren(parentId) {
        const parent = document.getElementById(parentId);
        if (!parent) {
            console.error(`ID가 ${parentId}인 요소를 찾을 수 없습니다.`);
            return [];
        }

        const children = parent.children;
        const clonedChildren = [];
        for (let i = 0; i < children.length; i++) {
            const clonedChild = children[i].cloneNode(true);
            clonedChildren.push(clonedChild);
        }
        return clonedChildren;
    }

    // clonedChildren을 targetId 요소에 붙여넣기
    function appendElement(clonedChildren, targetId) {
        const target = document.getElementById(targetId);
        if (!target) {
            console.error(`ID가 ${targetId}인 요소를 찾을 수 없습니다.`);
            return;
        }

        clonedChildren.forEach(child => {
            target.appendChild(child);
        });
    }


    // targetId 요소의 하위 요소와 콘텐츠를 모두 삭제
    function emptyElement(targetId) {
    const target = document.getElementById(targetId);
    if (!target) {
        console.error(`ID가 ${targetId}인 요소를 찾을 수 없습니다.`);
        return;
    }
    while (target.firstChild) {
        target.removeChild(target.firstChild);
    }
    }


    function autoAssignDomcy(startDate, wantDays) {
        emptyElement('a-list');
        const localDomcyList = possDomcyList.map(domcy => ({ ...domcy }));

        const assignedDates = [];
        const unassignedDates = [];
        let currentDate = new Date(startDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const possibleDateSet = new Set();
        const validDateMap = {};

        localDomcyList.forEach(domcy => {
            if (domcy.day > 0 && domcy.cnt > 0) {
                const sDate = new Date(domcy.s_date);
                const eDate = new Date(domcy.e_date);
                let tempDate = new Date(sDate);
                let daysLeft = domcy.day;
                while (tempDate <= eDate && daysLeft > 0) {
                    const isoDate = formatDateLocal(tempDate);
                    possibleDateSet.add(isoDate);
                    if (!validDateMap[domcy.buy_event_sno]) validDateMap[domcy.buy_event_sno] = new Set();
                    validDateMap[domcy.buy_event_sno].add(isoDate);
                    tempDate.setDate(tempDate.getDate() + 1);
                    daysLeft--;
                }
            }
        });

        const possibleDays = possibleDateSet.size;
        if (wantDays > possibleDays) {
            wantDays = possibleDays;
            $('#domcy_acppt_i_cnt').val(possibleDays);
        }

        let loopBreaker = 0;
        const cntUsedMap = {};

        while (wantDays > 0 && loopBreaker < 1000) {
            loopBreaker++;
            const todayStr = formatDateLocal(currentDate);
            let assignedToday = false;

            localDomcyList.forEach(domcy => {
                if (domcy.day > 0 && domcy.cnt > 0) {
                    const sDate = new Date(domcy.s_date);
                    const eDate = new Date(domcy.e_date);
                    if (currentDate >= sDate && currentDate <= eDate) {
                        assignedDates.push({
                            date: todayStr,
                            buy_event_sno: domcy.buy_event_sno
                        });
                        domcy.day--;
                        if (!cntUsedMap[domcy.buy_event_sno]) {
                            domcy.cnt--;
                            cntUsedMap[domcy.buy_event_sno] = true;
                        }
                        assignedToday = true;
                    }
                }
            });

            if (assignedToday) {
                wantDays--;
            } else {
                unassignedDates.push(todayStr);
            }

            currentDate.setDate(currentDate.getDate() + 1);
        }

        assignedDates.sort((a, b) => a.date.localeCompare(b.date));
        const eventUsage = {};
        assignedDates.forEach(item => {
            if (!eventUsage[item.buy_event_sno]) {
                eventUsage[item.buy_event_sno] = [];
            }
            eventUsage[item.buy_event_sno].push(item.date);
        });

        for (const [eventSno, dates] of Object.entries(eventUsage)) {
            dates.sort();
            const startDate = dates[0];
            const endDate = dates[dates.length - 1];
            const useDays = dates.length;

            const domcyInfo = localDomcyList.find(domcy => domcy.buy_event_sno === eventSno);
            const remainingDay = domcyInfo ? domcyInfo.day : 0;
            const remainingCnt = domcyInfo ? domcyInfo.cnt : 0;
            const eventNm = domcyInfo ? domcyInfo.sell_event_nm : '';

            const clonedChildren = cloneChildren('domcy_template');

            clonedChildren.forEach(clone => {
                const ftDefault = clone.querySelector('.ft-default');
                const title = clone.querySelector('#title');
                const ftLeft = clone.querySelector('.ft-left');
                const item_check = clone.querySelector('#item_check');

                if (item_check) {
                    item_check.checked = true;
                    item_check.addEventListener('change', () => {
                        // ftLeft.innerHTML = '';
                        // const remainingDay = domcyInfo ? domcyInfo.day : 0;
                        // const remainingCnt = domcyInfo ? domcyInfo.cnt : 0;
                        const newSpan = clone.querySelector('span');
                        const cnt = clone.querySelector('#cnt');
                        if(item_check.checked)
                        {   
                            newSpan.textContent = `남은 휴회 일: ${remainingDay }일 남은 휴회횟수: ${remainingCnt - 1}회`;
                        } else
                        {
                            newSpan.textContent = `남은 휴회 일: ${remainingDay + parseInt(cnt.value)}일 남은 휴회횟수: ${remainingCnt}회`;
                            cnt.value = 0;
                        } 
                        

                        // ftLeft.appendChild(newSpan);
                        // const isChecked = item_check.checked;
                        // const parent = item_check.closest('.a-item');
                        // if (!isChecked) {
                        //     parent.querySelector('.domcy-start-date').value = '';
                        //     parent.querySelector('.domcy-end-date').value = '';
                        //     parent.querySelector('#cnt').value = '';
                        // } else {
                        //     autoAssignDomcy($('#domcy_acppt_i_sdate').val(), $('#domcy_acppt_i_cnt').val());
                        // }
                    });
                }

                if (title) title.innerHTML = `<b>${eventNm}</b>`;

                let startInput, endInput, cntInput;

                if (ftDefault) {
                    ftDefault.innerHTML = '';

                    const startDiv = document.createElement('div');
                    startDiv.className = 'd-flex align-items-center mb-1';
                    startInput = document.createElement('input');
                    startInput.type = 'text';
                    startInput.className = 'form-control form-control-sm domcy-start-date';
                    startInput.value = startDate;
                    const startBtn = document.createElement('button');
                    startBtn.type = 'button';
                    startBtn.className = 'btn btn-outline-secondary btn-sm ms-2 calendar-btn';
                    startBtn.innerHTML = '<i class="fa fa-calendar"></i>';
                    startDiv.appendChild(startInput);
                    startDiv.appendChild(startBtn);

                    const endDiv = document.createElement('div');
                    endDiv.className = 'd-flex align-items-center';
                    endInput = document.createElement('input');
                    endInput.type = 'text';
                    endInput.className = 'form-control form-control-sm domcy-end-date';
                    endInput.value = endDate;
                    const endBtn = document.createElement('button');
                    endBtn.type = 'button';
                    endBtn.className = 'btn btn-outline-secondary btn-sm ms-2 calendar-btn';
                    endBtn.innerHTML = '<i class="fa fa-calendar"></i>';
                    endDiv.appendChild(endInput);
                    endDiv.appendChild(endBtn);

                    ftDefault.appendChild(startDiv);
                    ftDefault.appendChild(endDiv);

                    const hint = document.createElement('small');
                    hint.className = 'text-muted ms-2';
                    hint.textContent = `최대 ${useDays + remainingDay}일까지 가능합니다.`;
                    ftDefault.appendChild(hint);

                    const maxDay = useDays + remainingDay;
                    const eLimit = new Date(domcyInfo.e_date);
                    const sLimit = new Date(Math.max(today.getTime(), new Date(domcyInfo.s_date).getTime()));

                    

                    $(startInput).datepicker('destroy').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'ko',
                        todayHighlight: true
                    }).on('changeDate', function () {
                        const start = new Date(startInput.value);
                        let newEnd = new Date(start);
                        newEnd.setDate(start.getDate() + parseInt(cntInput.value) - 1);
                        if (newEnd > new Date(domcyInfo.e_date)) {
                            newEnd = new Date(domcyInfo.e_date);
                            const adjusted = Math.floor((newEnd - start) / (1000 * 60 * 60 * 24)) + 1;
                            cntInput.value = adjusted;
                        }
                        endInput.value = formatDateLocal(newEnd);
                        updateDatepickers();
                    });

                    const updateDatepickers = () => {
                        const startVal = new Date(startInput.value);
                        const endVal = new Date(endInput.value);
                        const reqStart = new Date(domcy_acppt_i_sdate.value);

                        const startMax = new Date(endVal);
                        startMax.setDate(startMax.getDate() - maxDay + 1);
                        const finalStart = new Date(Math.max(sLimit.getTime(), startMax.getTime(), reqStart.getTime()));

                        $(startInput).datepicker('setStartDate', finalStart);
                        $(startInput).datepicker('setEndDate', endVal);

                        const endMax = new Date(startVal);
                        endMax.setDate(endMax.getDate() + maxDay - 1);
                        const finalEnd = new Date(Math.min(endMax, eLimit.getTime()));

                        $(endInput).datepicker('setStartDate', startVal);
                        $(endInput).datepicker('setEndDate', finalEnd);
                    };

                    $(endInput).datepicker('destroy').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'ko',
                        todayHighlight: true
                    }).on('changeDate', function () {
                        debugger;
                        const end = new Date(endInput.value);
                        const start = new Date(startInput.value);
                        const duration = parseInt(cntInput.value);
                        const reqStart = new Date(domcy_acppt_i_sdate.value);
                        const newStart = new Date(end);
                        newStart.setDate(end.getDate() - duration + 1);
                        today.setDate(today.getDate())
                        const sLimit = new Date(Math.max(today.getTime(), new Date(domcyInfo.s_date).getTime(), reqStart.getTime()));
                        const eLimit = new Date(domcyInfo.e_date);

                        if (newStart < sLimit) {
                            newStart.setTime(sLimit.getTime());
                            const adjustedEnd = new Date(sLimit);
                            adjustedEnd.setDate(sLimit.getDate() + duration - 1);
                            endInput.value = formatDateLocal(adjustedEnd);
                        }
                        startInput.value = formatDateLocal(newStart);
                        updateDatepickers();
                    });
                    
                  
                    setTimeout(updateDatepickers, 10);
                }

                if (ftLeft) {
                    ftLeft.innerHTML = '';
                    const newSpan = document.createElement('span');
                    newSpan.textContent = `남은 휴회 일: ${remainingDay}일 남은 휴회횟수: ${remainingCnt}회`;
                    ftLeft.appendChild(newSpan);
                }

                const ftBlue = clone.querySelector('.ft-blue');
                if (ftBlue) {
                    ftBlue.innerHTML = `<input type="text" id="cnt" value="${useDays}" style="width:30px; text-align:right;"/>일 사용`;
                    cntInput = ftBlue.querySelector('#cnt');
                    cntInput.addEventListener('input', () => {
                        let newDays = parseInt(cntInput.value);
                        const startVal = new Date(startInput.value);
                        if (!isNaN(newDays)) {
                            const maxDay = useDays + remainingDay;
                            newDays = Math.min(Math.max(newDays, 1), maxDay);
                            cntInput.value = newDays;
                            const newEnd = new Date(startVal);
                            newEnd.setDate(startVal.getDate() + newDays - 1);
                            const limitedEnd = newEnd > new Date(domcyInfo.e_date) ? new Date(domcyInfo.e_date) : newEnd;
                            endInput.value = formatDateLocal(limitedEnd);
                            const updateDatepickers = () => {
                                const startVal = new Date(startInput.value);
                                const endVal = new Date(endInput.value);
                                const eLimit = new Date(domcyInfo.e_date);
                                const sLimit = new Date(Math.max(today.getTime(), new Date(domcyInfo.s_date).getTime()));

                                const startMax = new Date(endVal);
                                startMax.setDate(startMax.getDate() - maxDay + 1);
                                const finalStart = new Date(Math.max(sLimit.getTime(), startMax.getTime()));

                                $(startInput).datepicker('setStartDate', finalStart);
                                $(startInput).datepicker('setEndDate', endVal);

                                const endMax = new Date(startVal);
                                endMax.setDate(endMax.getDate() + maxDay - 1);
                                const finalEnd = new Date(Math.min(endMax, eLimit.getTime()));

                                $(endInput).datepicker('setStartDate', startVal);
                                $(endInput).datepicker('setEndDate', finalEnd);
                            };
                            updateDatepickers();
                        }
                    });
                }
            });

            appendElement(clonedChildren, 'a-list');
        }

        return { assignedDates, unassignedDates, eventUsage };
    }





</script>