<style>

.datepicker 
{
    top:50px;
    text-align:center;
    width:280px !important;
    left:0px !important;
    font-size: 14px !important;
    max-width: 280px !important;
    min-width: 280px !important;
}

.datepicker.datepicker-days
{
    width:280px !important;
    max-width: 280px !important;
    min-width: 280px !important;
}

/* datepicker 테이블 크기 조절 */
.datepicker table {
    width: 100% !important;
    table-layout: fixed !important;
}

/* datepicker 셀 크기 조절 */
.datepicker td, 
.datepicker th {
    width: 35px !important;
    height: 35px !important;
    padding: 2px !important;
    font-size: 13px !important;
}

/* datepicker 헤더 조절 */
.datepicker .datepicker-switch {
    font-size: 14px !important;
}

/* 오버레이 내부 datepicker 위치 조절 */
.overlay .datepicker {
    position: absolute !important;
    z-index: 1050 !important;
}

.overlay {
    position: fixed;
    bottom: -550px;  /* 메뉴는 화면 밖에 시작 */
    left: 0;
    width: 100%;
    height: 550px;
    z-index: 1000;
    overflow-y: auto;
    transition: bottom 0.3s ease-in-out;  /* 슬라이드 애니메이션 */
    padding: 0 15px; /* 좌우 패딩 추가 */
    box-sizing: border-box; /* 패딩을 너비에 포함 */
}

/* 오버레이 배경 */
.overlay-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    display: none;
    transition: opacity 0.3s ease-in-out;
}

/* 오버레이가 활성화되었을 때 배경 표시 */
.overlay-backdrop.active {
    display: block;
}

/* 오버레이가 활성화되었을 때 하위 요소 비활성화 */
.overlay.active ~ * {
    pointer-events: none;
    filter: blur(2px);
    transition: filter 0.3s ease-in-out;
}

.domcy-box {
  margin-top:10px;
  display: flex;
  align-items: center; /* 세로 가운데 정렬 */
  justify-content: center; /* 가로 가운데 정렬 */
  position: relative;
  width: 100%;
  max-height: 108px;         /* ✅ 최대 높이 제한 */
  overflow-y: auto;          /* ✅ 높이 초과 시 스크롤 표시 */
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
    right: 20px;       /* 오른쪽에서 10px */
    transform: scale(1); /* 버튼을 80% 크기로 축소 */
    z-index: 1000;
    display: block !important; /* 버튼이 항상 보이도록 강제 설정 */
}
.app-content{
    padding:3px;
}
#domcy_acppt_i_cnt {
    padding-right: 25px; /* "분" 공간 확보 */
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

/* 항상 가로 유지 */
.a-item .row {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
}

/* 300px 이하에서만 세로로 전환 */
@media (max-width: 430px) {
    .a-item .row {
        flex-direction: column !important;
        flex-wrap: wrap !important;
    }
    .a-item .col-auto,
    .a-item .col {
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 10px;
    }
}

/* 체크박스 열 고정 너비 */
.checkbox-column {
    width: 40px !important;
    min-width: 40px !important;
    max-width: 40px !important;
    padding: 0 !important;
    flex: 0 0 40px !important;
}

/* Bootstrap col 클래스 오버라이드 */
.a-item .col-auto {
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: none !important;
}

/* 사용일 입력 열 고정 너비 */
.usage-column {
    width: 60px !important;  /* 50px → 60px로 복원 */
    min-width: 60px !important;
    max-width: 60px !important;
    padding: 0 !important;
    flex: 0 0 60px !important; /* flex 고정 */
}

/* 사용일 입력 열은 절대 아래로 내려가지 않음 */
.col-auto.usage-column {
    order: 3 !important; /* 항상 마지막 위치 */
    margin-left: auto !important; /* 우측 정렬 */
}

/* a-item 내부 row 패딩 조정 */
.a-item .row {
    margin: 0 !important;
    padding: 0 !important;
    flex-wrap: nowrap !important; /* 줄바꿈 방지 */
}

/* 중앙 열 유동적 너비 */
.middle-column {
    flex: 1 1 auto !important;
    min-width: 0 !important;
    overflow: hidden;
    padding: 0 3px !important; /* 좌우 패딩 더 줄이기 */
}

/* 중앙 열 전체 줄 높이 조정 */
.middle-column > div {
    line-height: 1.2 !important;
}

/* 텍스트 줄바꿈 방지 */
.ft-blue.item-bold,
.ft-left span,
.ft-default small {
    white-space: nowrap;
}

/* 남은 휴회 일, 남은 휴회횟수 폰트 크기 축소 */
.ft-left {
    font-size: 11px !important;
}

/* 제목 여백 조정 */
#title {
    margin-bottom: 0.1rem !important; /* 여백 더 축소 */
    font-size: 12px; /* 폰트 더 작게 */
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    line-height: 1.2 !important;
}

/* ft-av 여백 제거 */
.ft-av {
    margin: 0 !important;
    line-height: 1.2;
}

/* 힌트 텍스트 스타일 */
.ft-av small {
    font-size: 11px !important;  /* 10px → 11px로 증가 */
    white-space: nowrap !important;
}


/* 입력 필드와 버튼 간격 조정 */
.domcy-start-date,
.domcy-end-date {
    width: 80px !important;  /* 75px + 5px = 80px */
    max-width: 80px !important;
    min-width: 80px !important;
    padding: 2px 4px !important;
    flex: 0 0 80px !important; /* flex 고정 */
    display: inline-block !important;
}
/* 시작 날짜, ~, 종료 날짜는 항상 가로로 유지 */
.date-inputs {
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
}

/* 날짜 행 줄바꿈 방지 및 크기 조정 */
.ft-default.row {
    display: flex !important;
    flex-wrap: nowrap !important;
    white-space: nowrap !important;
    margin: 0 !important;
    align-items: center !important;
}

/* 중앙 열 내부 날짜 표시 영역 */
.middle-column .ft-default {
    display: flex !important;
    flex-direction: row !important; /* 가로 방향 강제 */
    flex-wrap: nowrap !important;
    align-items: center !important;
    gap: 2px !important; /* flex 아이템 간 간격 */
}

/* flex-column 클래스 오버라이드 */
.middle-column.flex-column {
    flex-direction: column !important; /* 중간 열 자체는 세로 유지 */
}

/* 하지만 날짜 표시 영역은 가로로 */
.middle-column .ft-default {
    flex-direction: row !important;
}

/* d-flex 클래스가 있는 날짜 입력 컨테이너도 가로로 */
.middle-column .ft-default .d-flex {
    display: inline-flex !important;
    flex-direction: row !important;
}

/* 날짜 입력 컨테이너 */
.ft-default .d-flex {
    flex: 0 0 auto !important;
}

/* me-2 클래스 여백 제거 */
.ft-default .me-2 {
    margin-right: 0 !important; /* 여백 완전 제거 */
}

/* 물결표 여백 조정 */
.tilde {
    margin: 0 2px !important; /* 좌우 2px만 유지 */
    font-size: 10px;
    flex: 0 0 auto !important;
}

/* 날짜 입력 필드에 클릭 커서 추가 */
#domcy_acppt_i_sdate {
    cursor: pointer !important;
}

/* 하단 동적 렌더링 날짜 입력 필드 스타일 */
.domcy-start-date,
.domcy-end-date {
    cursor: pointer !important;
    font-size: 13px !important;  /* 12px → 13px로 증가 */
}

.date-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    flex-wrap: nowrap;
}

/* ~ 기호 스타일 */
.tilde {
    font-weight: bold;
}

/* 캘린더 버튼 크기 조정 */
.calendar-btn {
    padding: 2px 5px;
}

/* 태블릿 및 모바일에서도 가로 배치 유지 */
@media (max-width: 768px) {
    .a-item .row {
        flex-direction: row !important;
        flex-wrap: nowrap !important;
    }
    .a-item .col-auto.usage-column {
        width: 60px !important;
        flex: 0 0 60px !important;
    }
    .a-item .middle-column {
        flex: 1 1 auto !important;
    }
}

/* 좁은 화면에서도 고정 크기 유지 */
@media (max-width: 400px) {
    .domcy-start-date,
    .domcy-end-date {
        width: 80px !important;  /* 데스크톱과 동일한 크기 유지 */
        max-width: 80px !important;
        min-width: 80px !important;
    }
    .calendar-btn {
        padding: 1px 3px;
    }
    .ft-default small {
        font-size: 10px !important;  /* 좁은 화면에서도 읽기 쉽게 */
    }
    .ft-left span {
        font-size: 0.75rem;
    }
    .middle-column {
        min-width: 0 !important; /* 최소 너비 제한 완전 해제 */
    }
    /* 모바일에서도 날짜는 가로로 유지 */
    .middle-column .ft-default {
        flex-direction: row !important;
    }
    .domcy-start-date,
    .domcy-end-date {
        width: 75px !important; /* 70px + 5px = 75px */
        max-width: 75px !important;
        min-width: 75px !important;
        font-size: 12px !important;  /* 폰트도 약간 증가 */
    }
    .usage-column {
        width: 45px !important;
        min-width: 45px !important;
        max-width: 45px !important;
    }
    /* 입력 필드 내 input도 작게 */
    #cnt {
        width: 25px !important;
    }
}

/* 오버레이 내부 컨텐츠 영역 */
#bottom-menu-area {
    padding: 0 5px; /* 내부 컨텐츠 영역 패딩 */
    max-width: 100%; /* 최대 너비 제한 */
    box-sizing: border-box;
}

/* 오버레이 내부 카드 스타일 조정 */
.card.card-success {
    margin: 15px 0;
    width: 100%;
    box-sizing: border-box;
}

/* 리스트 아이템 스타일 조정 */
.a-item {
    width: 100%;
    box-sizing: border-box;
    margin-right: 0;
    min-width: 320px; /* 최소 너비 설정 */
    overflow-x: auto; /* 좁은 화면에서 가로 스크롤 */
}

.list-group-item {
    padding: 10px 10px 10px 30px !important; /* 좌우 패딩 줄이기 */
    margin: 0 !important;
}

.overlay .row {
    margin: 0;
    width: 100%;
}

/* 동적 리스트의 row는 항상 가로 유지 */
#domcy_template .row,
#a-list .row {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
}

.overlay .col-12 {
    padding: 0;
    width: 100%;
}

#bottom-menu-area {
    width: 100%;
    padding: 0;
}

/* datepicker 풍선 화살표 위치 조정 - 상단 표시시 아래방향 화살표 */
.datepicker:before,
.datepicker:after {
    left: auto !important;
    right: 20px !important; /* 우측에서 20px 떨어진 위치 */
    top: 100% !important; /* datepicker 하단에 위치 */
    bottom: auto !important;
}

.datepicker.datepicker-orient-top:before {
    border-top-color: #ccc !important;
    border-bottom-color: transparent !important;
    top: 100% !important;
    bottom: auto !important;
    left: auto !important;
    right: 20px !important;
}

.datepicker.datepicker-orient-top:after {
    border-top-color: #fff !important;
    border-bottom-color: transparent !important;
    top: 100% !important;
    bottom: auto !important;
    left: auto !important;
    right: 20px !important;
    margin-top: -1px !important;
}

.datepicker.datepicker-orient-bottom:before {
    border-bottom-color: #ccc !important;
    border-top-color: transparent !important;
    bottom: 100% !important;
    top: auto !important;
    left: auto !important;
    right: 20px !important;
}

.datepicker.datepicker-orient-bottom:after {
    border-bottom-color: #fff !important;
    border-top-color: transparent !important;
    bottom: 100% !important;
    top: auto !important;
    left: auto !important;
    right: 20px !important;
    margin-bottom: -1px !important;
}
</style>
<?php
$sDef = SpoqDef();
?>



<!-- Main content -->
<section class="content">
    <div class="row">   
        <div class="new-title">휴회 신청하기
            <a href="#" class="bg-red ft-white bottom-menu"><i class="fas fa-clipboard-check"></i> 신청하기</a>
        </div>	
        <div class="newbx01">
            
            휴회 가능일과 휴회 가능 횟수가 남아있다면 휴회가 가능합니다.
        </div>
    </div>
         
      <div class="container-fluid pad0">
         <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12" style="padding:0px;">
                        <div class="domcy-box">
                            <div class="domcy-content-left">
                                <div>휴회 가능</div>
                                <?php foreach ($poss_domcy['list'] as $row) : ?>
                                    <div>[<?php echo $row['sell_event_nm']?>] <?php echo $row['day']?>일(<?php echo $row['cnt']?>회)</div>
                                <?php endforeach; ?>
                            </div>
                            <div class="domcy-content-right"> 
                                <div>회원권 기간</div>
                                <?php foreach ($poss_domcy['list'] as $row) : ?>
                                    <div><?php echo $row['s_date']?> ~ <?php echo $row['e_date']?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        

<div class="row mt20">
             <div class="col-md-12" style="padding:0px;">    
                <div class='a-title'>휴회 현황</div>
                <?php if ( count($domcy_list) > 0) :?>
                    <?php foreach($domcy_list as $r) :?>
                        <div class="a-list">
                            <div class="a-item">
                                <div class='a-item-sec'>
                                    <div class='item-btn-area'>
                                        <div class="cate bga-cate"><?php echo $sDef['DOMCY_MGMT_STAT'][$r['DOMCY_MGMT_STAT']]?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="a-item mt10">
                                <div class='a-item-sec'>
                                    <span class="ft-blue item-bold"><?php echo $r['SELL_EVENT_NM']?>

                                        - <span class="ft-blue item-light"><?php echo $r['DOMCY_S_DATE']?> ~ <?php echo $r['DOMCY_E_DATE']?></span>
                                    </span>
                                    <span class="bold mr10 ft-red"><?php echo $r['DOMCY_USE_DAY']?>일간</span>
                                </div> 
                            </div>


                            <div class="a-item mt5 ">
                                <div class='a-item-sec'><span class="">- 신청일 : <?php echo substr($r['CRE_DATETM'],0,10)?></span></div>
                            </div>  
                            
                            <div class="line-y mt5"></div>
                        </div>
                    <?php endforeach;?>
                <?php else :?>
                    <div class="a-list">
                        <div class="a-item text-center" style='height:50px;margin-top:20px;'>
                           	휴회 신청 내역이 없습니다
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>

        
        <div class="overlay-backdrop" id="overlay-backdrop"></div>
        <div class="overlay" id='ooooo'>
            <div class="row">
            	<div class="col-12">
                    <div id="bottom-menu-area">
                    <button type="button" class="close btn-close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                        <br />
                        <h4 class='modal-title text-center'>휴회신청</h4>
                        <div class="card card-success" style="padding-top:10px; margin-top:15px;">
                            <div class="row">
                                <div class="list-group-item d-flex align-items-center" style="border:none;">
                                    <div class="flex-fill">
                                        <label class="form-label col-form-label col-md-3">휴회시작일</label>
                                        <div class="d-flex align-items-center text-body text-opacity-60">
                                            <div class="d-flex align-items-center ">
                                                <input class="form-control" type="text"  name="domcy_acppt_i_sdate" id="domcy_acppt_i_sdate" style="width:120px" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-fill" id="lesson-cnt-section" style="margin-left: 20px;">
                                        <label class="form-label col-form-label col-md-3">휴회 신청일수</label>
                                        <div class="text-body text-opacity-60 lesson-time-width position-relative d-flex align-items-center me-2">
                                            <input class="form-control" type="text" name="domcy_acppt_i_cnt" id="domcy_acppt_i_cnt" style="width:75px" onkeyup="daycnt_calu_date();">
                                            <span class="unit-label">일</span>
                                        </div>
                                    </div>
                                    <div class="flex-fill" style="display:none">
                                        <label class="form-label col-form-label col-md-3">휴회 종료일</label>
                                        <div class="text-body text-opacity-60 sell-price-width position-relative d-flex align-items-center">
                                            <input class="form-control "  id="domcy_acppt_e_sdate" readonly >
                                        </div>
                                    </div>		
                                </div>
                            </div>
                            
                            <div class="a-list" id="a-list">
                            </div>
                            <button type="button" class='btn btn-success btn-sm p-3 bottom-menu-submit' >휴회신청 등록하기</button>
                        </div>
                       
                        <div id="domcy_template" style="display:none">
                            <div class="a-item" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 8px;">
                                <div class="row align-items-center">
        
                                    <!-- ✅ 첫 번째 열: 체크박스 -->
                                    <div class="col-auto d-flex justify-content-center align-items-center checkbox-column">
                                        <input type="checkbox" class="item-check" id="item_check" style="transform: scale(1.2);">
                                    </div>
                                    
                                    <!-- ✅ 두 번째 열: 휴회 기간 + 남은 휴회일 -->
                                    <div class="col d-flex flex-column justify-content-center middle-column">
                                        <div class="mb-1" id="title">GX</div>
                                        <div class="ft-default row">휴회 기간: 2025-04-30 ~ 2025-06-28</div>
                                        <div class="ft-av"></div>
                                        <div class="ft-left">남은 휴회 일: 0일 / 남은 휴회횟수: 2회</div>
                                    </div>
                                    
                                    <!-- ✅ 세 번째 열: 사용일 입력 -->
                                    <div class="col-auto d-flex align-items-center usage-column">
                                        <span class="ft-blue item-bold">
                                            <input type="text" id="cnt" value="60" style="width: 40px; text-align: right; margin-right: 5px;">일
                                        </span>
                                    </div>
                                </div>

                                <!-- hidden fields -->
                                <input type="hidden" id="sell_event_sno">
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
        <input type="hidden" name="fc_domcy_mem_sno" id="fc_domcy_mem_sno" value="<?php echo $_SESSION['user_sno']?>" />
    </form>
</section>

<?=$jsinc ?>

<script>
    const domcy_start_btn = document.getElementById("domcy_start_btn");
    let possDomcyList = <?php echo json_encode($poss_domcy['list']); ?>;
    const overlay = document.getElementById('ooooo');
    const backdrop = document.getElementById('overlay-backdrop');
    
    $(function () {
        $('.select2').select2();
    })
    
    // document.addEventListener("scroll", () => {
    //     const scrollY = window.scrollY;
    //     document.body.classList.toggle("scrolled", scrollY > 120);
    // });

    // 휴회시작일 input을 클릭하면 달력이 나타나도록 수정
    const domcy_acppt_i_sdate = document.getElementById("domcy_acppt_i_sdate");
    domcy_acppt_i_sdate.addEventListener("click", () => {
        $(domcy_acppt_i_sdate).datepicker('show');
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
        if(overlay.style.bottom == "-550px" || overlay.style.bottom == "" )
        {
            if (isDomcyAvailable()) {
                $('#domcy_acppt_i_sdate').datepicker('destroy').datepicker({
                    autoclose: true,
                    language: "ko",
                    startDate: new Date(new Date().setDate(new Date().getDate() + 1)), // ✅ 오늘 이후만 선택 가능
                    todayHighlight: true,
                    orientation: 'top left' // 상단에 표시되도록 설정
                }).on('changeDate', () => {
                    autoAssignDomcy($('#domcy_acppt_i_sdate').val(), $('#domcy_acppt_i_cnt').val());
                });

                overlay.style.bottom = '0px';  // 메뉴 올라오게
                overlay.classList.add('active');
                backdrop.classList.add('active');

                // bottom-menu-area 동적 높이 설정 제거 가능 (스크롤 처리하므로)
                $('#bottom-menu-area').css("height", "auto");
            }
        } else
        {
            overlay.style.bottom = '-550px';  // 메뉴 닫기
            overlay.classList.remove('active');
            backdrop.classList.remove('active');
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
        const aList = document.getElementById("a-list");
        const fc_domcy_mem_sno = document.getElementById("fc_domcy_mem_sno")?.value || '';
        const selectedItems = [];
        aList.querySelectorAll('.a-item').forEach(item => {
            const checkbox = item.querySelector('.item-check');
            
            if (checkbox && checkbox.checked) {
                const fc_domcy_s_date = item.querySelector('.domcy-start-date')?.value || '';
                const fc_domcy_use_day = item.querySelector('#cnt')?.value || '';
                const fc_domcy_buy_sno = item.querySelector('#sell_event_sno')?.value || '';

                selectedItems.push({
                    fc_domcy_mem_sno,
                    fc_domcy_buy_sno,
                    fc_domcy_s_date,
                    fc_domcy_use_day
                });
            }
        });
        
        // 선택된 항목이 없을 때 처리
        if (selectedItems.length === 0) {
            alertToast('error', '휴회 신청할 항목을 선택해주세요.');
            return;
        }
        
        var params = {
            items: selectedItems
        };
        jQuery.ajax({
            url: '/api/ajax_domcy_acppt_proc',
            type: 'POST',
            data:params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
            dataType: 'text',
            success: function (result) {
            	if ( result.substr(0,8) == '<script>' )
            	{
            		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
            		location.href='/login';
            		return;
            	}
                json_result = $.parseJSON(result);
        		if (json_result['result'] == 'true')
        		{
                    alert(json_result['msg']);
        			location.reload();
        		}
        		else
        		{
        		    // 중복된 날짜 등의 오류 메시지 표시
                    alert(json_result['msg']);
        		}
            }
        }).done((res) => {
        	// 통신 성공시
        	console.log('통신성공');
        }).fail((error) => {
        	// 통신 실패시
        	console.log('통신실패');
        	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
        	location.href='/login';
        	return;
        });
        
    });

    $("#bottom-menu-close").click(function(){
        overlay.style.bottom = '-550px';  // 메뉴 닫기
        overlay.classList.remove('active');
        backdrop.classList.remove('active');
    });

    // 배경 클릭시 메뉴 닫기
    backdrop.addEventListener('click', function() {
        overlay.style.bottom = '-550px';
        overlay.classList.remove('active');
        backdrop.classList.remove('active');
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
                const ftAv = clone.querySelector('.ft-av');
                const sellEventSno = clone.querySelector("#sell_event_sno");
                sellEventSno.value = eventSno;
                
                if (item_check) {
                    item_check.checked = true;
                    item_check.addEventListener('change', (event) => {
                        // ftLeft.innerHTML = '';
                        // const remainingDay = domcyInfo ? domcyInfo.day : 0;
                        // const remainingCnt = domcyInfo ? domcyInfo.cnt : 0;
                        
                        const target = event.target; // 이벤트 대상 (체크박스)
                        const closestAItem = target.closest('.a-item');

                        const newSpan = closestAItem.querySelector('.ft-left');
                        const cnt = closestAItem.querySelector('#cnt');
                        const domcyStartDate = closestAItem.querySelector(".domcy-start-date");
                        const domcyEndDate = closestAItem.querySelector(".domcy-end-date");

                        if(!target.checked)
                        {
                            // newSpan.textContent = `남은 휴회 일: ${remainingDay + parseInt(cnt.value)}일, 남은 휴회횟수: ${remainingCnt}회`;
                            cnt.disabled = true;
                            domcyStartDate.disabled = true;
                            domcyEndDate.disabled = true;
                        } else
                        {
                            cnt.disabled = false;
                            domcyStartDate.disabled = false;
                            domcyEndDate.disabled = false;
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
                    startDiv.className = 'd-flex align-items-center';
                    startInput = document.createElement('input');
                    startInput.type = 'text';
                    startInput.className = 'form-control form-control-sm domcy-start-date';
                    startInput.value = startDate;
                    startInput.readOnly = true;
                    startDiv.appendChild(startInput);
                    
                    // 시작일 input 클릭시 달력 표시
                    startInput.addEventListener("click", (event) => {
                        $(startInput).datepicker('show');
                    });

                    const endDiv = document.createElement('div');
                    endDiv.className = 'd-flex align-items-center';
                    endInput = document.createElement('input');
                    endInput.type = 'text';
                    endInput.className = 'form-control form-control-sm domcy-end-date';
                    endInput.value = endDate;
                    endInput.readOnly = true;
                    endDiv.appendChild(endInput);
                    
                    // 종료일 input 클릭시 달력 표시
                    endInput.addEventListener("click", (event) => {
                        $(endInput).datepicker('show');
                    });
                    const betweenSpan = document.createElement('span');
                    betweenSpan.innerHTML = "~";
                    betweenSpan.className = 'tilde';

                    ftDefault.appendChild(startDiv);
                    ftDefault.appendChild(betweenSpan);
                    ftDefault.appendChild(endDiv);
                    
                    ftAv.innerHTML = '';
                    const hint = document.createElement('small');
                    hint.className = 'text-muted ms-2';
                    hint.textContent = `최대 ${useDays + remainingDay}일 가능`;
                    ftAv.appendChild(hint);
                    const maxDay = useDays + remainingDay;
                    const eLimit = new Date(domcyInfo.e_date);
                    const sLimit = new Date(Math.max(today.getTime(), new Date(domcyInfo.s_date).getTime()));

                    $(startInput).datepicker('destroy').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'ko',
                        todayHighlight: true,
                        orientation: 'top left' // 상단에 표시되도록 설정
                    }).on('changeDate', function () {
                        debugger;
                        const start = new Date(startInput.value); 
                        let newEnd = new Date(start);
                        newEnd.setDate(start.getDate() + parseInt(cntInput.value) - 1);
                        if (newEnd > new Date(domcyInfo.e_date)) {
                            newEnd = new Date(domcyInfo.e_date);
                            const adjusted = Math.floor((newEnd - start) / (1000 * 60 * 60 * 24)) + 1;
                            const prev_used_cnt = parseInt(cntInput.value);
                            cntInput.value = adjusted;
                            if (ftLeft) {
                            ftLeft.innerHTML = '';
                            const newSpan = document.createElement('span');
                            domcyInfo.day = domcyInfo.day + prev_used_cnt - adjusted;
                            newSpan.textContent = `남은 휴회 일: ${domcyInfo.day}일, 남은 휴회횟수: ${remainingCnt}회`;
                            ftLeft.appendChild(newSpan);
                        }
                        }
                        endInput.value = formatDateLocal(newEnd);
                        updateDatepickersForStartChange();
                    });

                    const updateDatepickersForStartChange = () => {
                        const startVal = new Date(startInput.value);
                        const endVal = new Date(endInput.value);
                        const reqStart = new Date(domcy_acppt_i_sdate.value);

                        const startMax = new Date(endVal);
                        startMax.setDate(startMax.getDate() - maxDay + 1);
                        const finalStart = new Date(Math.max(sLimit.getTime(), startMax.getTime(), reqStart.getTime()));

                        // $(startInput).datepicker('setStartDate', finalStart);
                        $(startInput).datepicker('setEndDate', endVal);

                        const endMax = new Date(startVal);
                        endMax.setDate(startVal.getDate() + maxDay - 1);
                        const finalEnd = new Date(Math.min(endMax, eLimit.getTime()));

                        $(endInput).datepicker('setStartDate', startVal);
                        $(endInput).datepicker('setEndDate', finalEnd);
                    };

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
                        todayHighlight: true,
                        orientation: 'top left' // 상단에 표시되도록 설정
                    }).on('changeDate', function () {
                        debugger;
                        const end = new Date(endInput.value);
                        const start = new Date(startInput.value);

                        
                        const adjusted = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;

                        // const duration = parseInt(cntInput.value);
                        // if(adjusted >= duration)
                        // {
                        //     const reqStart = new Date(domcy_acppt_i_sdate.value);
                        //     const newStart = new Date(end);
                        //     newStart.setDate(end.getDate() - duration + 1);
                        //     today.setDate(today.getDate())
                        //     const sLimit = new Date(Math.max(today.getTime(), new Date(domcyInfo.s_date).getTime(), reqStart.getTime()));
                        //     const eLimit = new Date(domcyInfo.e_date);

                        //     if (newStart < sLimit) {
                        //         newStart.setTime(sLimit.getTime());
                        //         const adjustedEnd = new Date(sLimit);
                        //         adjustedEnd.setDate(sLimit.getDate() + duration - 1);
                        //         endInput.value = formatDateLocal(adjustedEnd);
                        //     }
                        //     startInput.value = formatDateLocal(newStart);
                        // } else
                        // {
                            const prev_used_cnt = parseInt(cntInput.value);
                            cntInput.value = adjusted;
                        // }
                        
                        if (ftLeft) {
                            ftLeft.innerHTML = '';
                            const newSpan = document.createElement('span');
                            domcyInfo.day = domcyInfo.day + prev_used_cnt - adjusted;
                            newSpan.textContent = `남은 휴회 일: ${domcyInfo.day}일, 남은 휴회횟수: ${remainingCnt}회`;
                            ftLeft.appendChild(newSpan);
                        }
                        updateDatepickers();
                    });
                  
                    setTimeout(updateDatepickers, 10);
                }

                if (ftLeft) {
                    ftLeft.innerHTML = '';
                    const newSpan = document.createElement('span');
                    newSpan.textContent = `남은 휴회 일: ${remainingDay}일, 남은 휴회횟수: ${remainingCnt}회`;
                    ftLeft.appendChild(newSpan);
                }

                const ftBlue = clone.querySelector('.ft-blue');
                if (ftBlue) {
                    ftBlue.innerHTML = `<input type="text" id="cnt" value="${useDays}" style="width:30px; text-align:right;"/>일`;
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
                            
                            if (ftLeft) {
                                ftLeft.innerHTML = '';
                                const newSpan = document.createElement('span');
                                newSpan.textContent = `남은 휴회 일: ${domcyInfo.poss_day - newDays}일, 남은 휴회횟수: ${domcyInfo.poss_cnt - (newDays > 0 ? 1 : 0)}회`;
                                ftLeft.appendChild(newSpan);
                            }
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