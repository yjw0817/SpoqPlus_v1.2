<style>

    .table-responsive {
        overflow: visible !important;
    }

    .btn-group {
        position: relative;
    }

    .dropdown-menu {
        z-index: 10000 !important;
        position: fixed !important;
    }

    .table td {
        overflow: visible !important;
    }

    /* 패널과 컨테이너의 overflow 속성 조정 */
    .panel-body {
        overflow: visible !important;
    }

    .container-fluid {
        overflow: visible !important;
    }

    .content {
        overflow: visible !important;
    }

    /* 드롭다운이 footer보다 위에 표시되도록 */
    .dropdown-menu {
        z-index: 99999 !important;
        position: absolute !important;
        will-change: transform;
        min-width: 120px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }

    /* dropup 클래스가 추가될 때 위쪽으로 표시 */
    .dropup .dropdown-menu {
        top: auto !important;
        bottom: 100% !important;
        margin-bottom: 2px;
    }

    /* 오른쪽 정렬 드롭다운 */
    .dropdown-menu-right {
        right: 0 !important;
        left: auto !important;
    }

    /* 테이블 내 드롭다운 특별 처리 */
    .table .btn-group {
        position: static;
    }

    .table .dropdown-menu {
        position: absolute !important;
    }

    .select2-container--open {
        z-index: 9999 !important;
    }

    .photo-row {
        display: flex;
        align-items: flex-end;
        gap: 15px;
    }

    .photo-action {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100px;
    }

    .photo-guide-text {
        font-size: 13px;
        color: #555;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .profile-photo-wrapper {
        position: relative;
        display: inline-block;
        width: 100px;
        height: 100px;
    }

    .preview_mem_photo {
        width: 100px;
        height: 100px;
        object-fit: cover;
        align-content: center;
        text-align: center;
        border-radius: 50%;
        border: 1px solid #ccc;
    }

    .capture-btn {
        bottom: 0;
        right: 0;
        padding: 3px 6px;
        font-size: 12px;
        border-radius: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }

    #camera_wrap {
        position: relative;
        width: 100%;
        max-width: 500px;
    }

    #camera_stream {
        width: 100%;
        border-radius: 8px;
    }

    .face-guide {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 140px;
        height: 180px;
        transform: translate(-50%, -50%);
        border: 2px dashed rgba(0, 0, 0, 0.4);
        border-radius: 60% 60% 50% 50%;
        pointer-events: none;
        background-color: rgba(255, 255, 255, 0.1); // 실루엣 느낌 
    }

    .passport-guide {
        position: absolute;
        top: 47%;
        left: 50%;
        width: 120px;
        height: 150px;
        transform: translate(-50%, -60%);   // 살짝 위로 올려줌
        
        border: 2px dashed rgba(0, 0, 0, 0.4);
        border-radius: 8px;
        pointer-events: none;
        background-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }  

    .table-wrap th,
    .table-wrap td {
        white-space: normal !important;  /* 줄바꿈 허용 */
        overflow: visible !important;    /* 넘침 처리 제거 */
        text-overflow: unset !important; /* 생략 기호 제거 */
    }

    .memo-col-status {
        width: 80px !important;
        min-width: 80px;
        max-width: 80px;
        white-space: nowrap;
    }

    .memo-col-date {
        width: 80px !important;
        min-width: 80px;
        max-width: 80px;
        white-space: nowrap;
    }
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- mem-info-header -->
        <div class="row mem-info-header">
            <div class="col-md-12 p-0">
                <!-- <div class="card card-primary"> -->
                <div class="panel-body">



                    <div class="row sec-mem-info" style='display:none'>
                        <div class="col-md-6">

                                <div class="page-header">
                                    <h3 class="panel-title">회원정보</h3>
                                </div>
                                <div class="panel-body" >


                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">

                                    

                                        <tbody>
                                            <tr>
                                                <td colspan="2" rowspan="4" >
                                                </td>
                                                <td>아이디</td>
                                                <td><?php echo $mem_info['MEM_ID']?></td>
                                                <td>회원정보</td>
                                                <td colspan="3">
                                                    <?php ($mem_info['MEM_GENDR'] == "M") ? $disp_gendr="<font color='blue'><i class='fa fa-mars'></i></font>" : $disp_gendr="<font color='red'><i class='fa fa-venus'></i></font>"; ?>
                                                    <?php echo $disp_gendr ?>
                                                    <?php echo $mem_info['MEM_NM']?> (<?php echo disp_date_kr($mem_info['BTHDAY']) ?> , <?php echo disp_age($mem_info['BTHDAY'])?>세)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>전화번호</td>
                                                <td><?php echo disp_phone($mem_info['MEM_TELNO'])?></td>
                                                <td>매출액</td>
                                                <td class='text-right'><?php echo number_format($rank_info['sum_paymt_amt'])?> 원</td>
                                                <td>매출순위</td>
                                                <td class='text-center'><?php echo number_format($rank_info['paymt_ranking'])?> 위</td>
                                            </tr>
                                            <tr>
                                                <td>락커</td>
                                                <td>
                                                    <?php foreach ($lockr_01 as $r1) :?>
                                                    <?php echo $r1['LOCKR_NO']?>번&nbsp;&nbsp;
                                                    <?php endforeach;?>
                                                </td>
                                                <td>가일입</td>
                                                <td><?php echo $mem_info['JON_DATETM']?></td>
                                                <td>가입장소</td>
                                                <td><?php echo $sDef['JON_PLACE'][$mem_info['JON_PLACE']]?></td>
                                            </tr>
                                            <tr>
                                                <td>골프라카</td>
                                                <td>
                                                    <?php foreach ($lockr_02 as $r2) :?>
                                                    <?php echo $r2['LOCKR_NO']?>번&nbsp;&nbsp;
                                                    <?php endforeach;?>
                                                </td>
                                                <td>등록일</td>
                                                <td><?php echo $mem_info['REG_DATETM']?></td>
                                                <td>등록장소</td>
                                                <td><?php echo $sDef['REG_PLACE'][$mem_info['REG_PLACE']]?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 


                        <div class="col-md-3 col-1p">
                            <div class="card card-olive">
                                <div class="page-header">
                                    <h3 class="panel-title">메모내역</h3>
                                </div>

                                <div class="panel-body">
                                    <table class="table table-wrap table-sm col-md-12">

                                        <tbody>
                                            <?php
                                                $short_cut_i = 0;
                                                foreach ($memo_list as $m) :?>
                                            <tr>
                                                <?php if($m['PRIO_SET'] == "Y") :?>
                                                <td class="memo-col-status memo-text-xs text-center">[중요메모] </td>
                                                <?php else :?>
                                                <td class="memo-col-status memo-text-xs text-center">[메모등록] </td>
                                                <?php endif;?>
                                                <td class="memo-col-date memo-text-xs"><?php echo substr($m['CRE_DATETM'],0,10)?></td>
                                                <td class="memo-text-xs" title="<?php echo $m['MEMO_CONTS']?>"><span style="width:460px;display:block; white-space:nowrap; overflow: hidden; text-overflow:ellipsis;"><?php echo $m['MEMO_CONTS']?></span></td>
                                            </tr>
                                            <?php 
                                                if ($short_cut_i == 5) break;
                                                $short_cut_i++;
                                                endforeach;?>

                                            <?php
                                                $mm_count = 5-count($memo_list);
                                                for($mm=0;$mm<$mm_count;$mm++) :?>
                                            <tr>
                                                <td style='width:80px' class="memo-text-xs">&nbsp;</td>
                                                <td style='width:80px' class="memo-text-xs"></td>
                                                <td class="memo-text-xs" title=""></td>
                                            </tr>
                                            <?php endfor;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row sec-event-info" style='display:none'>
                        <div class="col-md-12 col-1p">
                            <div class="card card-default">
                                <div class="page-header">
                                    <h3 class="panel-title">상품상세내역</h3>
                                </div>
                                <div class="panel-body p-0" id="sec_event_info_detail">
                                    <!-- 환불 또는 양도,양수 일경우 상세 내역을 보여주는 곳 -->
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- 수정시작 -->

                    <div class="row sec-mem-info-detail">


                        <div class="col-xs-12 col-md-12 col-lg-4 col-1p">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h4 class="panel-title">회원정보</h4>
                                </div>

                                <div class="panel-body table-responsive">

                                    <table class="table table-bordered col-md-12 mt20">

                                        <tbody>
                                            <tr>
                                                <td rowspan="4" colspan="2" style="text-align:center;">
                                                    <img class="preview_mem_photo" src="<?php echo empty($mem_info['MEM_THUMB_IMG']) ? '/dist/img/default_profile.png' : $mem_info['MEM_THUMB_IMG']; ?>" 
                                                        alt="회원사진"
                                                        style="border-radius: 0%; cursor: pointer;"
                                                        onclick="showFullPhoto('<?php echo $mem_info['MEM_MAIN_IMG'] ?>')"
                                                        onerror="this.src='/dist/img/default_profile.png'" >
                                                </td>
                                                <th>아이디</th>
                                                <td><?php echo $mem_info['MEM_ID'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>전화번호</th>
                                                <td><?php echo disp_phone($mem_info['MEM_TELNO'])?></td>
                                            </tr>
                                            <tr>
                                                <th>락커</th>
                                                <td>
                                                <?php foreach ($lockr_01 as $r1) :?>
                                                <?php echo $r1['LOCKR_NO']?>번&nbsp;&nbsp;
                                                <?php endforeach;?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>골프라카</th>
                                                <td>
                                                <?php foreach ($lockr_02 as $r2) :?>
                                                <?php echo $r2['LOCKR_NO']?>번&nbsp;&nbsp;
                                                <?php endforeach;?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>회원정보</th>
                                                <td colspan="3">
                                                    <?php ($mem_info['MEM_GENDR'] == "M") ? $disp_gendr="<font color='blue'><i class='fa fa-mars'></i></font>" : $disp_gendr="<font color='red'><i class='fa fa-venus'></i></font>"; ?>
                                                    <?php echo $disp_gendr ?>
                                                    <?php echo $mem_info['MEM_NM'] ?> (<?php echo disp_date_kr($mem_info['BTHDAY']) ?> , <?php echo disp_age($mem_info['BTHDAY'])?>세)
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>총매출액</th>
                                                <td class='text-right'><?php echo number_format($rank_info['sum_paymt_amt'])?> 원</td>
                                                <th>매출순위</th>
                                                <td class='text-center'><?php echo number_format($rank_info['paymt_ranking'])?> 위</td>
                                            </tr>
                                            <tr>
                                                <th>가일입</th>
                                                <td><?php echo date('Y-m-d', strtotime($mem_info['JON_DATETM'])); ?></td>
                                                <th>가입장소</th>
                                                <td class='text-center'><?php echo $sDef['JON_PLACE'][$mem_info['JON_PLACE']]?></td>
                                            </tr>
                                            <tr>
                                                <th>등록일</th>
                                                <td><?php echo date('Y-m-d', strtotime($mem_info['REG_DATETM'])); ?></td>
                                                <th>등록장소</th>
                                                <td class='text-center'><?php echo $sDef['REG_PLACE'][$mem_info['REG_PLACE']]?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="card-footer clearfix mt10">
                                        <!-- BUTTON [START] -->
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="ac-btn"><button type="button" class="btn btn-block btn-warning size13" onclick="mem_info_modify('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-user-edit"></i> 수정하기</button></li>
                                        </ul>
                                        <!-- BUTTON [END] -->
                                    </div>

                                </div>
                            </div>
                        </div>



                        <div class="col-lg-4 col-1p">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h3 class="panel-title">출석 현황</h3>
                                </div>

                                <div class="panel-body" style="padding-bottom: 15px !important">
                                    <?php
                                        // 두날짜 사이의 날수를 계산한다.
                                        $attd_diff_days = disp_diff_date($attd_info['sdate'],$attd_info['edate']) + 1;
                                        // 출석 퍼센트를 구한다.
                                        $attd_per = round(($attd_info['count'] / $attd_diff_days) * 100);
                                    ?>
                                    <table class="table table-bordered table-striped col-md-12 size13">
                                        <tr>
                                            <th style='text-align:center'><?php echo date('Y-m-d',strtotime($attd_info['sdate']))?> ~</th>
                                            <th style='text-align:center'>&nbsp;&nbsp;<?php echo date('Y-m-d',strtotime($attd_info['edate']))?></th>
                                        </tr>
                                        <tr>
                                            <td style='text-align:center'><?php echo $attd_per?>%</td>
                                            <td style='text-align:center'><?php echo $attd_diff_days ?>일(<?php echo $attd_info['count']?>일)</td>
                                        </tr>
                                    </table>
                                    <div class='row'>

                                        <?php foreach ($attd_list as $a) : ?>

                                        <?php if($a['ATTD_YN'] == "Y") : ?>
                                        <?php ($a['AUTO_CHK'] == "Y") ? $auto_chk = "color:red" : $auto_chk = ""; ?>
                                        <div class='col-md-6 attd-div mb2'>
                                            <div class='attd-text'><small class="badge attd-num"></small><small class="badge bg-success bd-fsize"><i class="far fa-clock"></i>정상</small> <span style="<?php echo $auto_chk?>"><?php echo substr($a['CRE_DATETM'],0,16)?></span></div>
                                        </div>
                                        <?php else : ?>
                                        <div class='col-md-6 attd-div'>
                                            <div class='attd-text'><small class="badge attd-num"></small><small class="badge bg-warning bd-fsize"><i class="far fa-clock"></i>재입장</small> <?php echo substr($a['CRE_DATETM'],0,16)?></div>
                                        </div>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php
                                            $attcount = 16 - count($attd_list);
                                            for($att=0;$att<$attcount;$att++) :
                                        ?>
                                        <!-- <div class='col-md-6 attd-div'>&nbsp;</div> -->
                                        <?php endfor;?>

                                    </div>

                                    <div class="card-footer clearfix mt20">
                                        <!-- BUTTON [START] -->
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="ac-btn">
                                                <button type="button" class="btn btn-blue btn-warning size13" onclick="more_attd('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-search"></i> 출석현황 더보기</button>
                                                <button type="button" class="btn btn-purple btn-warning size13" onclick="more_pt_attd('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-search"></i> PT수업내역</button>
                                            </li>
                                        </ul>
                                        <!-- BUTTON [END] -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-4 col-1p">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h3 class="panel-title">메모내역</h3>
                                </div>

                                <div class="panel-body">
                                    <table class="table table-wrap table-sm col-md-12">

                                        <tbody>
                                            <!-- 9개만 나와야한다. -->
                                            <?php foreach ($memo_list as $m) :?>
                                            <tr>
                                                <?php if($m['PRIO_SET'] == "Y") :?>
                                                <td style='cursor:pointer;' class="memo-col-status memo-text-xs text-center" onclick="change_memo_prio_set('N','<?php echo $m['MEMO_MGMT_SNO']?>');">[중요메모] </td>
                                                <?php else :?>
                                                <td style='cursor:pointer;' class="memo-col-status memo-text-xs text-center" onclick="change_memo_prio_set('Y','<?php echo $m['MEMO_MGMT_SNO']?>');">[메모등록] </td>
                                                <?php endif;?>
                                                <td  class="memo-col-date memo-text-xs">
                                                    <?php echo substr($m['CRE_DATETM'],0,10)?>

                                                    <?php if(substr($m['CRE_DATETM'],0,10) == date('Y-m-d')) : ?>
                                                    <i class="fas fa-edit" style='cursor:pointer' onclick="memo_modify('<?php echo $m['MEMO_MGMT_SNO']?>','<?php echo $m['MEM_SNO']?>','<?php echo $mem_info['MEM_SNO']?>');"></i>
                                                    <?php endif ;?>
                                                </td>
                                                <td class="memo-text-xs" title="<?php echo $m['MEMO_CONTS']?>">
                                                    <?php echo $m['MEMO_CONTS']?>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                            <?php
                                                $mm_count = 9-count($memo_list);
                                                for($mm=0;$mm<$mm_count;$mm++) :?>
                                            <tr>
                                                <td style='width:80px' class="memo-text-xs">&nbsp;</td>
                                                <td style='width:80px' class="memo-text-xs"></td>
                                                <td class="memo-text-xs" title=""></td>
                                            </tr>
                                            <?php endfor;?>
                                        </tbody>
                                    </table>

                                    <div class="">
                                        <ul class="pagination pagination-sm float-right">
                                            <li class="ac-btn">
                                                <button type="button" class="btn btn-blue btn-warning size13" onclick="memo_more('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-search"></i> 메모 더보기</button></li>

                                            </li>
                                            <li class="ac-btn ml10">
                                                <button type="button" class="btn btn-blue btn-green size13" onclick="memo_insert('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-pen"></i> 메모 등록하기</button></li>

                                            </li>
                                        </ul>

                                        <ul class="pagination pagination-sm float-left ">
                                            <li style="font-size:0.8rem;color:red;">* 중요메모, 메모등록을 클릭하면 중요메모 설정을 변경 할 수 있습니다.</li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div> <!--  panel-body -->


                <div class=" mt10 mb10">


                    <ul class="pagination pagination-sm m-0 flex_ju_st">
                        <li class="ac-btn"><button type="button" class="btn btn-block btn-white size13 mr5" onclick="more_info_hide();">기본보기</button></li>
                        <li class="ac-btn"><button type="button" class="btn btn-block btn-green size13 mr5" onclick="info_mem_buy_event('<?php echo $mem_info['MEM_SNO']?>');">구매하기</button></li>
                        <li class="ac-btn"><button type="button" class="btn btn-block btn-info size13 mr5" onclick="direct_attd('<?php echo $mem_info['MEM_SNO']?>');">수동출석</button></li>
                        <li class="ac-btn"><button type="button" class="btn btn-block btn-danger size13 mr5" onclick="domcy_acppt();">휴회신청</button></li>
                        <li class="ac-btn"><button type="button" class="btn btn-block btn-warning size13" onclick="send_event('<?php echo $mem_info['MEM_SNO']?>');">상품 보내기</button></li>
                    </ul>
                    <!-- 
					<ul class="pagination pagination-sm m-0 float-right">
						<li class="ac-btn"><button type="button" class="btn btn-block btn-default btn-xs">Sample</button></li>
						<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-xs">Sample</button></li>
						<li class="ac-btn"><button type="button" class="btn btn-block btn-info btn-xs">Sample</button></li>
						<li class="ac-btn"><button type="button" class="btn btn-block btn-warning btn-xs">Sample</button></li>
						<li class="ac-btn"><button type="button" class="btn btn-block btn-danger btn-xs">Sample</button></li>
					</ul>
					 -->
                </div>


            </div>

        </div> <!-- col-12 -->
    </div> <!-- mem-info-header -->





    <div class="panel panel-inverse mb20">
        <div class="panel-heading">
            <h4 class="panel-title">판매 리스트</h4>
            <div class="panel-heading-btn">
            </div>
        </div>
        <div class="panel-body" style="overflow-x: auto;">
            <table class="table table-bordered table-hover col-md-12">
                <thead>
                    <tr class='text-center'>
                        <th>설정하기</th>
                        <th>구매일시</th>

                        <th>판매상태</th>
                        <th>처리일시</th>
                        <th>판매상품명</th>

                        <th>기간</th>
                        <th>시작일</th>
                        <th>종료일</th>
                        <th>수업</th>
                        <th>휴회일</th>
                        <th>휴회횟수</th>
                        <th>판매금액</th>
                        <th>결제금액</th>
                        <th>미수금액</th>

                        <th>수업강사</th>
                        <th>판매강사</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
								$list_count = 0;
								for($i=0; $i<3; $i++) :
								foreach($event_list[$i] as $r) :
									if ($i == 1) $backColor = "";  //이용중
									if ($i == 0) $backColor = "#dfeffb";   //예약됨
									if ($i == 2) $backColor = "#fee6ef";   //종료됨
									
									$list_count++;
								?>
                    <tr style="background-color: <?php echo $backColor ?>;">
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-xs dropdown-toggle dropdown-icon" data-toggle="dropdown" data-boundary="viewport" aria-label="설정 옵션 열기">설정하기
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <?php if ($i == 0): // 이용중 ?>
                                <div class="dropdown-menu text-sm" role="menu">
                                    <a class="dropdown-item" onclick="change_domcy_day('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회일 추가</a>
                                    <a class="dropdown-item" onclick="change_domcy_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회횟수 추가</a>
                                    <a class="dropdown-item" onclick="change_clas_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">수업추가</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동시작일</a>
                                    <a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동종료일</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
                                    <a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_event_trans('<?php echo $r['MEM_SNO'] ?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">양도하기</a>
                                    <a class="dropdown-item" onclick="change_event_just_end('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">강제종료</a>
                                    <a class="dropdown-item" onclick="change_event_refund('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">환불하기</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="test_clas_chk('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">수업하기(TEST)</a>
                                </div>
                                <?php elseif ($i == 1): // 예약됨 ?>
                                <div class="dropdown-menu text-sm" role="menu">
                                    <a class="dropdown-item" onclick="change_domcy_day('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회일 추가</a>
                                    <a class="dropdown-item" onclick="change_domcy_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회횟수 추가</a>
                                    <a class="dropdown-item" onclick="change_clas_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">수업추가</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동시작일</a>
                                    <a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동종료일</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
                                    <a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_event_trans('<?php echo $r['MEM_SNO'] ?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">양도하기</a>
                                    <a class="dropdown-item" onclick="change_event_just_end('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">강제종료</a>
                                    <a class="dropdown-item" onclick="change_event_refund('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">환불하기</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="test_clas_chk('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">수업하기(TEST)</a>
                                </div>
                                <?php elseif ($i == 2): // 종료됨 ?>
                                <div class="dropdown-menu text-sm" role="menu">
                                    <a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동시작일</a>
                                    <a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동종료일</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
                                    <a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo substr($r['BUY_DATETM'],0,16)?></td>
                        <td><?php echo $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]?></td>
                        <td><?php echo substr($r['MOD_DATETM'],0,16)?></td>
                        <td>
                            <?php if ($r['EVENT_STAT_RSON'] == "51" || $r['EVENT_STAT_RSON'] == "81" || $r['EVENT_STAT_RSON'] == "61" || $r['EVENT_STAT_RSON'] == "62") :?>
                            <i class="fa fa-list" onclick="more_info_show('<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['EVENT_STAT_RSON']?>');"></i>
                            <?php endif; ?>
                            <?php 	  
											  echo "<small class='badge bg-success'>".$sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]."</small>";
										?>
                            <input type="hidden" id="<?php echo "sell_event_nm_".$r['BUY_EVENT_SNO']?>" value="<?php echo $r['SELL_EVENT_NM']?>" />
                            <?php echo $r['SELL_EVENT_NM']?>
                            <!--  (<?php echo $r['BUY_EVENT_SNO']?>) -->

                            <?php if($r['LOCKR_SET'] == "Y") : 
												if ($r['LOCKR_NO'] != '') :
													echo disp_locker($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
												else :
												    if ($r['EVENT_STAT'] != '99') :
										?>
                            <small class='badge bg-danger' style='cursor:pointer' onclick="lockr_select('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['LOCKR_KND']?>','<?php echo $mem_info['MEM_GENDR']?>');">선택하기</small>
                            <?php
										            endif;
												endif ;
											  endif;
										?>

                        </td>

                        <td class='text-right'><?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?></td>
                        <td><span id="<?php echo "exr_s_date_".$r['BUY_EVENT_SNO']?>">

                                <?php if($r['EVENT_STAT'] == "01" && ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") ) : ?>
                                <button type='button' class='btn btn-info btn-xs' onclick="pt_use('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">이용시작</button>
                                <?php endif; ?>
                                <?php echo $r['EXR_S_DATE']?>
                            </span></td>
                        <td><span id="<?php echo "exr_e_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_E_DATE']?></span><?php echo disp_add_cnt($r['ADD_SRVC_EXR_DAY'])?></td>

                        <!-- ############### 수업 영역 ################# -->
                        <?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                        <?php
									   $sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT']; // 총 수업 남은 횟수
									?>
                        <td class='text-center'><?php echo $sum_clas_cnt?>
                            <?php if($r['ADD_SRVC_CLAS_CNT'] > 0) : ?>
                            <?php echo disp_add_cnt($r['ADD_SRVC_CLAS_CNT'])?>
                            <?php endif;?>
                        </td>

                        <?php else :?>
                        <td class='text-center'>-</td>
                        <?php endif ;?>
                        <!-- ############### 수업 영역 ################# -->

                        <!-- ############### 휴회 영역 ################# -->
                        <?php if($r['DOMCY_POSS_EVENT_YN'] == "Y") :?>
                        <td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_DAY'] ?></td>
                        <td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_CNT'] ?></td>
                        <?php else :?>
                        <td class='text-center' style='font-size:0.8rem'>불가능</td>
                        <td class='text-center' style='font-size:0.8rem'>불가능</td>
                        <?php endif ;?>
                        <!-- ############### 휴회 영역 ################# -->

                        <td class='text-right'><?php echo number_format($r['REAL_SELL_AMT']) ?></td>
                        <td class='text-right'><?php echo number_format($r['BUY_AMT']) ?></td>
                        <td class='text-right'>
                            <?php if ($r['RECVB_AMT'] == 0) :?>
                            <?php echo number_format($r['RECVB_AMT']) ?>
                            <?php else :?>
                            <button type='button' class='btn btn-danger btn-xs' onclick="misu_select('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO']?>');">
                                <?php echo number_format($r['RECVB_AMT']) ?>
                            </button>
                            <?php endif ;?>
                        </td>

                        <td class='text-center'><?php echo $r['STCHR_NM'] ?></td>
                        <td class='text-center'><?php echo $r['PTCHR_NM'] ?></td>
                    </tr>
                    <?php 
								endforeach;
								endfor; 
								?>
                </tbody>
            </table>
            <?php if($list_count == 0) :?>
            <div>
                <button type="button" class="btn btn-default btn-block btn-sm text-center" style='width:100%; height:240px'>등록된 상품이 없습니다.</button>
            </div>
            <?php endif;?>

        </div>
    </div>





    <!-- ############################## MODAL [ SATRT ] #################################### -->

    <!-- ============================= [ modal-sm START 운동시작일 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_sdate">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">운동 시작일 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="pop_sdate_buy_sno" id="pop_sdate_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text input-readonly" style='width:150px'>현재 운동시작일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_sdate_info_sdate" id="pop_sdate_info_sdate" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>변경할 운동시작일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_sdate_change_sdate" id="pop_sdate_change_sdate">
                    </div>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_change_sdate_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 운동종료일 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_edate">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">운동 종료일 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="pop_edate_buy_sno" id="pop_edate_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <input type="text" class="form-control" name="pop_sell_event_nm" id="pop_sell_event_nm" style="text-align:center" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text input-readonly" style='width:150px'>현재 운동종료일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_edate_info_edate" id="pop_edate_info_edate" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>변경할 운동종료일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_edate_change_edate" id="pop_edate_change_edate" onchange="edate_calu_days();">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가될 일수</span>
                        </span>
                        <input type="text" class="form-control" id="pop_edate_change_add_edate_cnt" readonly>
                    </div>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_change_edate_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 휴회일 추가 ] ============================================ -->
    <div class="modal fade" id="modal_pop_domcy_day" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">휴회일 추가</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="domcy_day_buy_sno" id="domcy_day_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가할 휴회일 수</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_day_day" id="domcy_day_day">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_domcy_day_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 휴회횟수 추가 ] ============================================ -->
    <div class="modal fade" id="modal_pop_domcy_cnt">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">휴회횟수 추가</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="domcy_cnt_buy_sno" id="domcy_cnt_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가할 휴회 횟수</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_cnt_cnt" id="domcy_cnt_cnt">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_domcy_cnt_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 수업 추가 ] ============================================ -->
    <div class="modal fade" id="modal_pop_clas_cnt">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">수업 추가</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="clas_cnt_buy_sno" id="clas_cnt_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가할 수업수</span>
                        </span>
                        <input type="text" class="form-control" name="clas_cnt_cnt" id="clas_cnt_cnt">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_clas_cnt_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 수업강사 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_stchr">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">수업강사 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="stchr_buy_sno" id="stchr_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <select class="select2 form-control" style="width: 250px;" name="ch_stchr_id" id="ch_stchr_id">
                            <option>강사 선택</option>
                            <?php foreach ($tchr_list as $r) : ?>
                            <option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_stchr_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 판매강사 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_ptchr">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">판매강사 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ptchr_buy_sno" id="ptchr_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <select class="select2 form-control" style="width: 250px;" name="ch_ptchr_id" id="ch_ptchr_id">
                            <option>강사 선택</option>
                            <?php foreach ($tchr_list as $r) : ?>
                            <option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_ptchr_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-lg START 양도하기 ] ============================================ -->
    <div class="modal fade" id="modal_trans_mem_search_form">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h5 class="modal-title">양수자 회원 검색</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                    

                    <div class="input-group input-group-sm mb-1 col-md-6">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>회원명</span>
                        </span>
                        <input type="text" class="form-control" style='width:100px !important;' placeholder="회원명을 입력하세요" name="input_trans_mem_search" id="input_trans_mem_search" />
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info btn-flat" id="btn_trans_mem_search">검색</button>
                        </span>
                    </div>

                    <!-- FORM [START] -->
                    <div class="input-group input-group-sm mb-1">

                        <table class="table table-bordered table-hover table-striped col-md-12" id='trans_mem_search_table'>
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
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 휴회하기 ] ============================================ -->
    <div class="modal fade" id="modal_pop_domcy">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">휴회 신청 하기</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>휴회 시작일</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_acppt_i_sdate" id="domcy_acppt_i_sdate">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>휴회 신청일수</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_acppt_i_cnt" id="domcy_acppt_i_cnt" onkeyup="daycnt_calu_date();">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>휴회 종료일</span>
                        </span>
                        <input type="text" class="form-control" id="domcy_acppt_e_sdate" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>휴회 가능일</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_acppt_day" id="domcy_acppt_day" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>휴회 가능횟수</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_acppt_cnt" id="domcy_acppt_cnt" readonly>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_domcy_acppt_submit();">휴회신청하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->


    <!-- ============================= [ modal-sm START 메모등록 ] ============================================ -->
    <form name='form_memo_insert' id='form_mem_insert'>
        <input type="hidden" name="memo_mem_sno" id="memo_mem_sno" />
        <div class="modal fade" id="modal_memo_insert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">메모 등록하기</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:120px'>중요메모 설정</span>
                            </span>

                            <div style='margin-top:4px;margin-left:5px;'>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="memoImp1" name="memo_prio_set" value="N" checked>
                                    <label for="memoImp1">
                                        <small>일반</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="memoImp2" name="memo_prio_set" value="Y">
                                    <label for="memoImp2">
                                        <small>중요</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="input-group input-group-sm mb-1">
                            <textarea rows='5' class="form-control" name="memo_content" id="memo_content"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-sm btn-success" onclick="btn_memo_insert();">메모 등록하기</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- ============================= [ modal-sm END ] ============================================== -->


    <!-- ============================= [ modal-sm START 메모 수정 ] ============================================ -->
    <form name='form_memo_modify' id='form_memo_modify'>
        <input type="hidden" name="modify_memo_mgmt_sno" id="modify_memo_mgmt_sno" />
        <div class="modal fade" id="modal_memo_modify">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">메모 수정하기</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="input-group input-group-sm mb-1">
                            <textarea rows='5' class="form-control" name="memo_content" id="modify_memo_content"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-sm btn-success" onclick="btn_memo_modify();">메모 수정하기</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- ============================= [ modal-sm END ] ============================================== -->






    <div class="modal fade" id="modal_mem_memo">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h5 class="modal-title">메모 더보기</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body" style="overflow-y: scroll; height:600px;">
                    <!-- FORM [START] -->
                    <div class="input-group input-group-sm mb-1">

                        <table class="table table-bordered table-striped" id='mem_memo_table'>
                            <thead>
                                <tr>
                                    <th class="memo-text-xs text-center" style='width:80px'>구분</th>
                                    <th class="memo-text-xs text-center" style='width:140px'>등록일</th>
                                    <th class="memo-text-xs text-center">메모내용</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM [END] -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_mem_modify">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h5 class="modal-title">회원정보수정</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <form id="form_mem_modify">
                            <input type="hidden" id="mem_sno" name="mem_sno" value="<?php echo $mem_info['MEM_SNO']?>" />
                            <div class="form-group">

                                <div class="photo-row">
                                    <!-- 사진 썸네일 -->
                                     <img class="preview_mem_photo"
                                        id="preview_mem_photo" 
                                         src="<?php echo empty($mem_info['MEM_THUMB_IMG']) ? '/dist/img/default_profile.png' : $mem_info['MEM_THUMB_IMG']; ?>" 
                                        data-default-thumb="<?php echo $mem_info['MEM_THUMB_IMG']; ?>" 
                                        data-default-main="<?php echo $mem_info['MEM_MAIN_IMG']; ?>" 

                                        alt="회원사진"
                                        style="border-radius: 0%; cursor: pointer;"
                                        onclick="showFullPhoto('<?php echo $mem_info['MEM_MAIN_IMG'] ?>')"
                                        onerror="this.src='/dist/img/default_profile.png'" >
                                    <!-- 오른쪽 텍스트 + 버튼 -->
                                    <div class="photo-action">
                                        <!-- 안내 문구 -->
                                        <div class="photo-guide-text">
                                            정면을 바라보며,<br>
                                            상반신이 잘 보이도록 촬영해주세요.
                                        </div>
                                        <!-- 버튼: 사진과 같은 행에, 하단 정렬 -->
                                        <button type="button" class="capture-btn" onclick="openCamera()">
                                            <i class="fas fa-camera"></i></button>
                                    </div>
                                </div>

                                <!-- 웹캠 영상 영역 -->
                                <div id="camera_wrap" style="margin-top: 10px; display: none; text-align: center;">
                                    <!-- 웹캠 영상 -->
                                    <video id="camera_stream" autoplay playsinline></video>

                                    <!-- 얼굴 가이드 -->
                                    <div class="passport-guide"></div>

                                    <!-- 촬영 버튼 -->
                                    <button type="button" class="btn btn-success btn-sm mt-2" onclick="capturePhoto()">촬영</button>
                                </div>

                                <input type="hidden" id="captured_photo" name="captured_photo" />
                                
                                <!-- 🔍 얼굴 인식 데이터 필드들 -->
                                <input type="hidden" id="face_encoding_data" name="face_encoding_data" />
                                <input type="hidden" id="glasses_detected" name="glasses_detected" value="0" />
                                <input type="hidden" id="quality_score" name="quality_score" value="0" />
                            </div>

                            <div class="form-group mt20">
                                <label for="inputName">회원 아이디</label>
                                <input type="text" id="mem_id" name="mem_id" class="form-control mt4" value="<?php echo $mem_info['MEM_ID']?>" readonly>
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 비밀번호</label>
                                <input type="text" id="mem_pwd" name="mem_pwd" class="form-control mt4" placeholder="비밀번호 변경시에만 입력해주세요.">
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 이름<span class="text-danger">*</span></label>
                                <input type="text" id="mem_nm" name="mem_nm" class="form-control mt4" value="<?php echo $mem_info['MEM_NM']?>">
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 생년월일</label>
                                <input type="text" id="bthday" name="bthday" class="form-control mt4" value="<?php echo $mem_info['BTHDAY']?>" data-inputmask="'mask': ['9999/99/99']" data-mask>
                            </div>

                            <div class="form-group mt20">
                                <label for="inputName">회원 성별</label>
                                <div class="icheck-primary d-inline ml20">
                                    <input type="radio" id="radioGrpCate1" name="mem_gendr" value="M" <?php if($mem_info['MEM_GENDR'] == "M") {?> checked <?php }?>>
                                    <label for="radioGrpCate1">
                                        <small>남</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline ml20">
                                    <input type="radio" id="radioGrpCate2" name="mem_gendr" value="F" <?php if($mem_info['MEM_GENDR'] == "F") {?> checked <?php }?>>
                                    <label for="radioGrpCate2">
                                        <small>여</small>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 전화번호<span class="text-danger">*</span></label>
                                <input type="text" id="mem_telno" name="mem_telno" class="form-control mt4" value="<?php echo $mem_info['MEM_TELNO']?>" data-inputmask="'mask': ['99-9999-999[9]','999-9999-9999']" data-mask>
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 주소</label>
                                <input type="text" id="mem_addr" name="mem_addr" class="form-control mt4" value="<?php echo $mem_info['MEM_ADDR']?>">
                            </div>


                        </form>
                        <!-- 
                                <div class="form-group">
                                    <label for="inputDescription">Project Description</label>
                                    <textarea id="inputDescription" class="form-control" rows="4"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="inputStatus">Status</label>
                                    <select id="inputStatus" class="form-control custom-select">
                                        <option selected disabled>Select one</option>
                                        <option>On Hold</option>
                                        <option>Canceled</option>
                                        <option>Success</option>
                                    </select>
                                </div>
                                 -->

                    </div>




                    <!-- FORM [END] -->
                </div>
                <div class="modal-footer">
                    <button type="button" class='btn btn-info btn-sm' onclick="mem_modify();">회원정보 수정하기</button>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>






    <!-- ############################## MODAL [ END ] ###################################### -->



</section>

<form name="form_change_sdate" id="form_change_sdate" method="post" action="/ttotalmain/ajax_info_mem_change_sdate_proc">
    <input type="hidden" name="fc_sdate_buy_sno" id="fc_sdate_buy_sno" />
    <input type="hidden" name="fc_sdate_sdate" id="fc_sdate_sdate" />
</form>

<form name="form_change_edate" id="form_change_edate" method="post" action="/ttotalmain/ajax_info_mem_change_edate_proc">
    <input type="hidden" name="fc_edate_buy_sno" id="fc_edate_buy_sno" />
    <input type="hidden" name="fc_edate_edate" id="fc_edate_edate" />
</form>

<form name="form_domcy_day" id="form_domcy_day" method="post" action="/ttotalmain/ajax_info_mem_domcyday_proc">
    <input type="hidden" name="fc_domcy_day_buy_sno" id="fc_domcy_day_buy_sno" />
    <input type="hidden" name="fc_domcy_day_day" id="fc_domcy_day_day" />
</form>

<form name="form_domcy_cnt" id="form_domcy_cnt" method="post" action="/ttotalmain/ajax_info_mem_domcycnt_proc">
    <input type="hidden" name="fc_domcy_cnt_buy_sno" id="fc_domcy_cnt_buy_sno" />
    <input type="hidden" name="fc_domcy_cnt_cnt" id="fc_domcy_cnt_cnt" />
</form>

<form name="form_clas_cnt" id="form_clas_cnt" method="post" action="/ttotalmain/ajax_info_mem_clascnt_proc">
    <input type="hidden" name="fc_clas_cnt_buy_sno" id="fc_clas_cnt_buy_sno" />
    <input type="hidden" name="fc_clas_cnt_cnt" id="fc_clas_cnt_cnt" />
</form>

<form name="form_stchr" id="form_stchr" method="post" action="/ttotalmain/ajax_info_mem_stchr_proc">
    <input type="hidden" name="fc_stchr_buy_sno" id="fc_stchr_buy_sno" />
    <input type="hidden" name="fc_ch_stchr_id" id="fc_ch_stchr_id" />
</form>

<form name="form_ptchr" id="form_ptchr" method="post" action="/ttotalmain/ajax_info_mem_ptchr_proc">
    <input type="hidden" name="fc_ptchr_buy_sno" id="fc_ptchr_buy_sno" />
    <input type="hidden" name="fc_ch_ptchr_id" id="fc_ch_ptchr_id" />
</form>

<form name="form_trans" id="form_trans" method="post" action="/ttotalmain/event_trans_info">
    <input type="hidden" name="fc_trans_buy_sno" id="fc_trans_buy_sno" />
    <input type="hidden" name="fc_trans_mem_sno" id="fc_trans_mem_sno" />
    <input type="hidden" name="fc_trans_tmem_sno" id="fc_trans_tmem_sno" />
</form>

<form name="form_domcy" id="form_domcy" method="post" action="/ttotalmain/ajax_domcy_acppt_proc">
    <input type="hidden" name="fc_domcy_cnt" id="fc_domcy_cnt" value="<?php echo $poss_domcy['cnt']?>" />
    <input type="hidden" name="fc_domcy_day" id="fc_domcy_day" value="<?php echo $poss_domcy['day']?>" />
    <input type="hidden" name="fc_domcy_buy_sno" id="fc_domcy_aply_buy_sno" value="<?php echo $poss_domcy['buy_event_sno']?>" />
    <input type="hidden" name="fc_domcy_mem_sno" id="fc_domcy_mem_sno" value="<?php echo $mem_info['MEM_SNO']?>" />
    <input type="hidden" name="fc_domcy_s_date" id="fc_domcy_s_date" />
    <input type="hidden" name="fc_domcy_use_day" id="fc_domcy_use_day" />
</form>


<?=$jsinc ?>

<script>
    let stream = null;

    function openCamera() {
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(mediaStream) {
                stream = mediaStream;
                const video = document.getElementById('camera_stream');
                video.srcObject = stream;
                document.getElementById('camera_wrap').style.display = 'block';
            })
            .catch(function(err) {
                alert("카메라 접근 권한이 필요합니다.");
            });
    }

    function capturePhoto() {
        console.log('📸 capturePhoto 함수 호출됨!');
        
        const video = document.getElementById('camera_stream');
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0);

        // 📌 JPEG로 base64 생성 (품질 0.9)
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        console.log('📸 Base64 이미지 생성 완료:', dataUrl.substring(0, 50) + '...');

        // 썸네일 이미지 변경
        const preview = document.getElementById('preview_mem_photo');
        preview.src = dataUrl;

        // base64 저장
        document.getElementById('captured_photo').value = dataUrl;
        console.log('📸 captured_photo 필드에 저장 완료');

        // 동적으로 onclick의 원본 이미지도 base64로 변경
        preview.setAttribute('onclick', `showFullPhoto("${dataUrl}")`);

        // 🔍 얼굴 인식 처리 시작
        console.log('📸 얼굴 인식 함수 호출 시작...');
        processFaceRecognition(dataUrl);

        // 웹캠 종료
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        document.getElementById('camera_wrap').style.display = 'none';
        console.log('📸 capturePhoto 함수 완료');
    }

    // 🔍 얼굴 인식 처리 함수
    async function processFaceRecognition(imageBase64) {
        try {
            // 얼굴 인식 상태 표시
            showFaceRecognitionStatus('processing', '얼굴 분석 중...');

            // Base64에서 data:image/jpeg;base64, 부분 제거 - 더 안전한 방법
            let base64Data = imageBase64;
            if (base64Data.includes(',')) {
                base64Data = base64Data.split(',')[1];
            }
            
            // Base64 데이터 검증
            if (!base64Data || base64Data.length < 100) {
                throw new Error('유효하지 않은 이미지 데이터입니다.');
            }

            // 얼굴 인식 API 호출
            console.log('🔍 얼굴 인식 API 호출 시작...');
            console.log('Base64 데이터 크기:', base64Data.length);
            console.log('Base64 시작 부분:', base64Data.substring(0, 50) + '...');
            
            const response = await fetch('/FaceTest/recognize_for_registration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    image: base64Data
                })
            });
            
            console.log('API 응답 상태:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('API 응답 데이터:', result);

            if (result.success && result.face_detected) {
                // 얼굴 검출 성공
                console.log('✅ 얼굴 검출 성공!');
                console.log('얼굴 데이터:', result.face_data);
                
                showFaceRecognitionStatus('success', '얼굴이 정상적으로 인식되었습니다!');
                
                // 얼굴 인코딩 데이터 검증
                if (result.face_data && result.face_data.face_encoding && Array.isArray(result.face_data.face_encoding)) {
                    console.log('얼굴 인코딩 배열 크기:', result.face_data.face_encoding.length);
                    
                    // 얼굴 데이터를 hidden 필드에 저장
                    document.getElementById('face_encoding_data').value = JSON.stringify(result.face_data);
                    document.getElementById('glasses_detected').value = result.face_data.glasses_detected ? '1' : '0';
                    document.getElementById('quality_score').value = result.face_data.quality_score || 0.85;
                    
                    console.log('저장된 얼굴 데이터:', document.getElementById('face_encoding_data').value);
                } else {
                    throw new Error('얼굴 인코딩 데이터가 유효하지 않습니다.');
                }
                
            } else {
                // 얼굴 검출 실패
                const errorMsg = result.error || '얼굴을 인식할 수 없습니다. 다시 촬영해주세요.';
                console.log('❌ 얼굴 검출 실패:', errorMsg);
                showFaceRecognitionStatus('error', errorMsg);
                
                // 얼굴 데이터 초기화
                document.getElementById('face_encoding_data').value = '';
                document.getElementById('glasses_detected').value = '0';
                document.getElementById('quality_score').value = '0';
            }

        } catch (error) {
            console.error('얼굴 인식 오류:', error);
            showFaceRecognitionStatus('error', '얼굴 인식 중 오류가 발생했습니다: ' + error.message);
            
            // 얼굴 데이터 초기화
            document.getElementById('face_encoding_data').value = '';
            document.getElementById('glasses_detected').value = '0';
            document.getElementById('quality_score').value = '0';
        }
    }

    // 얼굴 인식 상태 표시 함수
    function showFaceRecognitionStatus(type, message) {
        // 상태 표시 영역이 없다면 생성
        let statusDiv = document.getElementById('face_recognition_status');
        if (!statusDiv) {
            statusDiv = document.createElement('div');
            statusDiv.id = 'face_recognition_status';
            statusDiv.style.cssText = `
                margin-top: 10px;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 13px;
                text-align: center;
            `;
            
            // 사진 영역 다음에 추가
            const photoRow = document.querySelector('.photo-row');
            if (photoRow && photoRow.parentNode) {
                photoRow.parentNode.insertBefore(statusDiv, photoRow.nextSibling);
            }
        }

        // 타입별 스타일 설정
        if (type === 'processing') {
            statusDiv.style.backgroundColor = '#fff3cd';
            statusDiv.style.color = '#856404';
            statusDiv.style.border = '1px solid #ffeaa7';
            statusDiv.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${message}`;
        } else if (type === 'success') {
            statusDiv.style.backgroundColor = '#d4edda';
            statusDiv.style.color = '#155724';
            statusDiv.style.border = '1px solid #c3e6cb';
            statusDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        } else if (type === 'error') {
            statusDiv.style.backgroundColor = '#f8d7da';
            statusDiv.style.color = '#721c24';
            statusDiv.style.border = '1px solid #f5c6cb';
            statusDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        }

        statusDiv.style.display = 'block';

        // 3초 후 성공/오류 메시지 자동 숨김
        if (type !== 'processing') {
            setTimeout(() => {
                if (statusDiv) {
                    statusDiv.style.display = 'none';
                }
            }, 3000);
        }
    }


    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        document.getElementById('camera_wrap').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal_mem_modify');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                stopCamera();
            });
        }
    });

    document.getElementById('input_trans_mem_search').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            search();
        }
    });

    $(function() {
        $('.select2').select2();
        
        // 드롭다운 메뉴가 화면 밖으로 나가지 않도록 위치 조정
        $('.dropdown-toggle').on('shown.bs.dropdown', function() {
            var $dropdown = $(this).next('.dropdown-menu');
            var dropdownOffset = $dropdown.offset();
            var dropdownHeight = $dropdown.outerHeight();
            var dropdownWidth = $dropdown.outerWidth();
            var windowHeight = $(window).height();
            var windowWidth = $(window).width();
            var scrollTop = $(window).scrollTop();
            
            // 화면 아래로 벗어날 경우 위쪽으로 표시
            if (dropdownOffset.top + dropdownHeight > windowHeight + scrollTop) {
                $dropdown.addClass('dropup');
            }
            
            // 화면 오른쪽으로 벗어날 경우 왼쪽으로 이동
            if (dropdownOffset.left + dropdownWidth > windowWidth) {
                $dropdown.addClass('dropdown-menu-right');
            }
        });
    })

    // 휴회횟수 숫자입력
    $('#domcy_acppt_i_cnt').keyup(function() {
        var d_cnt = onlyNum2($('#domcy_acppt_i_cnt').val());
        $('#domcy_acppt_i_cnt').val(d_cnt);
    });

    // 회원정보 수정하기
    function mem_info_modify(mem_sno) {
        // 썸네일 이미지 초기화
        const img = document.getElementById('preview_mem_photo');
        const thumb = img.getAttribute('data-default-thumb');
        const main = img.getAttribute('data-default-main');

        img.src = thumb;
        img.setAttribute('onclick', `showFullPhoto('${main}')`);

        // hidden 필드 초기화
        document.getElementById('captured_photo').value = "";

        // 모달 열기
        $('#modal_mem_modify').modal("show");
    }

    // 회원정보 수정하기 처리
    function mem_modify() {
        if($('#mem_nm').val() == "" )
		{
			$('#mem_nm').focus();
			alertToast('error','지점관리자명을 입력하세요');
			return;
		}
		
		if( $('#mem_telno').val() == "" )
		{
			$('#mem_telno').focus();
			alertToast('error','지점관리자 전화번호를 입력하세요');
			return;
		} else if(!checkPhoneNumber($('#menu_mem_telno').val()))
		{
			$('#mem_telno').focus();
			alertToast('error','올바른 지점관리자 전화번호를 입력하세요');
			return;
		}


        var params = $("#form_mem_modify").serialize();
        debugger;
        jQuery.ajax({
            url: '/ttotalmain/ajax_mem_modify_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    alert('회원정보가 업데이트되었습니다.');
                    location.reload();
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }


    // 상품 보내기
    function send_event(mem_sno) {
        location.href = "/teventmain/send_event2/" + mem_sno;
    }

    // 메모 등록하기
    function memo_insert(mem_sno) {
        $('#memo_mem_sno').val(mem_sno);
        $('#modal_memo_insert').modal("show");
    }

    // 메모 수정하기
    function memo_modify(memo_sno, memo_mem_sno, mem_sno) {
        $('#modify_memo_mgmt_sno').val(memo_sno);

        var params = "memo_mgmt_sno=" + memo_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_modify',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    $('#modal_memo_modify').modal("show");
                    json_result['memo_list'].forEach(function(r, index) {
                        $('#modify_memo_content').val(r['MEMO_CONTS']);
                    });
                } else {
                    console.log(json_result);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    function change_memo_prio_set(flag_yn, memo_sno) {
        var params = "prio_set=" + flag_yn + "&memo_mgmt_sno=" + memo_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_prio_set',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    console.log(json_result);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    function rn_br(word) {
        return word.replace(/(?:\r\n|\r|\n)/g, '<br />');
    }

    // 메모 더보기
    function memo_more(mem_sno) {
        $('#mem_memo_table > tbody > tr').remove();
        var params = "mem_sno=" + mem_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_more',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    $('#modal_mem_memo').modal("show");

                    json_result['memo_list'].forEach(function(r, index) {
                        var addTr = "<tr>";
                        if (r['PRIO_SET'] == 'Y') {
                            addTr += "<td class='memo-text-xs bg-info text-center'>[중요메모]</td>";
                        } else {
                            addTr += "<td class='memo-text-xs text-center'>[메모등록]</td>";
                        }
                        addTr += "<td class='memo-text-xs'>" + r['CRE_DATETM'] + "</td>";
                        addTr += "<td class='memo-text-xs'>" + rn_br(r['MEMO_CONTS']) + "</td>";
                        //addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"top_buy_user_select('"+ r['MEM_SNO'] +"');\">선택</button></td>";
                        addTr += "</tr>";

                        $('#mem_memo_table > tbody:last').append(addTr);
                    });



                } else {
                    console.log(json_result);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });

    }
    
    function more_pt_attd(mem_sno)
    {
        location.href = "/tsalesmain/month_class_sales_manage/" + mem_sno;
    }

    //출석 더보기
    function more_attd(mem_sno) {
        location.href = "/teventmem/mem_attd_manage/" + mem_sno;
    }

    function btn_memo_modify() {
        var params = $("#form_memo_modify").serialize();
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_modify_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    location.reload();
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    function btn_memo_insert() {
        var params = $("#form_mem_insert").serialize();
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_insert_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    location.reload();
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 휴회 신청하기 (모달 띄우기)
    function domcy_acppt() {
        if ($('#fc_domcy_day').val() == 0 || $('#fc_domcy_cnt').val() == 0) {
            alertToast('error', '휴회 횟수,일수가 부족하여 신청이 불가능 합니다.');
            return;
        } else {
            $('#domcy_acppt_i_sdate').datepicker('destroy');
            $('#domcy_acppt_i_sdate').datepicker({
                autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                language: "ko" //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
            });

            $('#domcy_acppt_day').val($('#fc_domcy_day').val()); // 휴회가능 일
            $('#domcy_acppt_cnt').val($('#fc_domcy_cnt').val()); // 휴회가능 횟수

            $('#modal_pop_domcy').modal("show");
        }
    }

    // 휴회 신청 처리
    function btn_domcy_acppt_submit() {
        $('#fc_domcy_s_date').val($('#domcy_acppt_i_sdate').val());
        $('#fc_domcy_use_day').val($('#domcy_acppt_i_cnt').val());

        $('#form_domcy').submit();
    }

    function test_clas_chk(stchr_id, buy_sno) {
        if (stchr_id == '') {
            alertToast('error', "수업강사를 먼저 배정 해야 합니다.");
            return;
        }

        var params = "stchr_id=" + stchr_id + "&buy_sno=" + buy_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_clas_chk',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    alert(json_result['msg']);
                    location.reload();
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });

        //location.href="/ttotalmain/ajax_clas_chk/"+stchr_id+"/"+buy_sno;
    }

    function trans_tuser_select(tmem_sno) {
        $('#fc_trans_tmem_sno').val(tmem_sno);

        $('#form_trans').submit();
    }

    // 양도하기 모달 띄우기
    function change_event_trans(mem_sno, buy_sno) {
        $('#modal_trans_mem_search_form').modal("show");
        $('#fc_trans_buy_sno').val(buy_sno);
        $('#fc_trans_mem_sno').val(mem_sno);
    }

    // 양도하기 양수자 검색

    $('#btn_trans_mem_search').click(function() {

        search();
    })


    function search() {
        $('#trans_mem_search_table > tbody > tr').remove();
        var params = "sv=" + $('#input_trans_mem_search').val();
        jQuery.ajax({
            url: '/ttotalmain/top_search_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    if (json_result['search_mem_list'].length == 0) {
                        alertToast('error', '검색된 정보가 없습니다.');
                        return;
                    }

                    /*
                    if (json_result['search_mem_list'].length == 1)
                    {
                    	top_buy_user_select(json_result['search_mem_list'][0]['MEM_SNO']);
                    	console.log(json_result['search_mem_list'][0]);
                    	return;
                    }
                    */

                    json_result['search_mem_list'].forEach(function(r, index) {
                        var addTr = "<tr>";
                        addTr += "<td>" + r['MEM_STAT_NM'] + "</td>";
                        addTr += "<td>" + r['MEM_NM'] + "</td>";
                        addTr += "<td>" + r['MEM_ID'] + "</td>";
                        addTr += "<td>" + r['MEM_TELNO'] + "</td>";
                        addTr += "<td>" + r['BTHDAY'] + "</td>";
                        addTr += "<td>" + r['MEM_GENDR_NM'] + "</td>";
                        addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"trans_tuser_select('" + r['MEM_SNO'] + "');\">선택</button></td>";
                        addTr += "</tr>";

                        $('#trans_mem_search_table > tbody:last').append(addTr);
                    });

                } else {
                    console.log(json_result);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // PT, Golf PT 예약중인 상품을 운동 시작일로 변경하기
    function pt_use(stchr_id, buy_sno) {
        if (stchr_id == '') {
            alertToast('error', "수업강사를 먼저 배정 해야 합니다.");
            return;
        }
        //location.href="/ttotalmain/ajax_pt_use/"+stchr_id+"/"+buy_sno;

        ToastConfirm.fire({
            icon: "question",
            title: "  확인 메세지",
            html: "<font color='#000000' >이용시작을 하시겠습니까?</font>",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonColor: "#28a745",
        }).then((result) => {
            if (result.isConfirmed) {
                // 성공일 경우	
                var params = "buy_sno=" + buy_sno;
                jQuery.ajax({
                    url: '/ttotalmain/ajax_pt_use',
                    type: 'POST',
                    data: params,
                    async: false,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: 'text',
                    success: function(result) {
                        if (result.substr(0, 8) == '<script>') {
                            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                            location.href = '/tlogin';
                            return;
                        }

                        json_result = $.parseJSON(result);
                        console.log(json_result);
                        if (json_result['result'] == 'true') {
                            alert('해당 상품의 이용이 시작 되었습니다.');
                            location.reload();
                        }
                    }
                }).done((res) => {
                    // 통신 성공시
                    console.log('통신성공');
                }).fail((error) => {
                    // 통신 실패시
                    console.log('통신실패');
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                    location.href = '/tlogin';
                    return;
                });
            }
        });

    }

    function direct_attd(mem_sno) {
        //location.href="/ttotalmain/ajax_direct_attd/"+mem_sno;
        //return;
        var params = "mem_sno=" + mem_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_direct_attd',
            type: 'POST',
            data: params,
            async: false,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    alert(json_result['msg']);
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }


    function change_event_refund(mem_sno, buy_sno, event_stat) {
        location.href = "/ttotalmain/refund_info/" + mem_sno + "/" + buy_sno;
    }

    function misu_select(mem_sno, buy_sno) {
        location.href = "/teventmain/misu_manage_info/" + mem_sno + "/" + buy_sno;
    }

    // 상품 구매하기
    function info_mem_buy_event(mem_sno) {
        location.href = "/teventmain/event_buy2/" + mem_sno;
    }

    // 라커 선택하기
    function lockr_select(mem_sno, buy_sno, lockr_knd, gendr) {
        location.href = "/ttotalmain/nlockr_select/" + mem_sno + "/" + buy_sno + "/" + lockr_knd + "/" + gendr;
    }

    // 운동 시작일 변경
    function change_exr_s_date(buy_sno, event_stat, m_cate) {
        if (m_cate == 'GRP' || m_cate == 'PRV') {
            alertToast('error', '수업상품의 운동 시작일은 변경이 불가능 합니다. 이용시작 버튼을 이용해주세요');
            return;
        }

        if (event_stat == "01") {
            $('#pop_sdate_change_sdate').datepicker('destroy');

            $('#modal_pop_sdate').modal("show");
            $('#pop_sdate_buy_sno').val(buy_sno);
            $('#pop_sdate_info_sdate').val($.trim($('#exr_s_date_' + buy_sno).text())); // 현재 운동시작일
            $('#pop_sdate_change_sdate').val($('#exr_s_date_' + buy_sno).text()); // 변경할 운동시작일

            $('#pop_sdate_change_sdate').datepicker({
                autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                language: "ko" //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
            });
        } else {
            alertToast('error', '이용중이거나 종료된 상품은 시작일을 변경할 수 없습니다.');
        }
    }

    // 운동 시작일 변경 처리
    function btn_change_sdate_submit() {
        var fc_sdate_buy_sno = $('#pop_sdate_buy_sno').val();
        var fc_sdate_sdate = $('#pop_sdate_change_sdate').val();

        // ### test
        // $('#fc_sdate_buy_sno').val(fc_sdate_buy_sno);
        // $('#fc_sdate_sdate').val(fc_sdate_sdate);
        // $('#form_change_sdate').submit();
        // return;
        var params = "fc_sdate_buy_sno=" + fc_sdate_buy_sno + "&fc_sdate_sdate=" + fc_sdate_sdate;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_change_sdate_proc',
            type: 'POST',
            data: params,
            async: false,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 운동 종료일 변경
    function change_exr_e_date(buy_sno, event_stat, m_cate) {

        if (m_cate == 'GRP') {
          //alertToast('error', '수업상품의 운동 종료일은 변경 할 수 없습니다. 강제 종료기능을 이용해주세요.');
            alertToast('error', 'GX상품의 운동 종료일은 변경 할 수 없습니다. 강제 종료기능을 이용해주세요.');
            return;
        }

        if ($('#exr_e_date_' + buy_sno).text() == '') {
            alertToast('error', '운동종료일이 없는 상품은 운동 종료일을 변경 할 수 없습니다.');
            return;
        }

        if (event_stat != "99") {
            $('#pop_edate_change_edate').datepicker('destroy');

            $('#modal_pop_edate').modal("show");
            $('#pop_edate_buy_sno').val(buy_sno);
            $('#pop_edate_info_edate').val($('#exr_e_date_' + buy_sno).text()); // 현재 운동시작일
            $('#pop_edate_change_edate').val($('#exr_e_date_' + buy_sno).text()); // 변경할 운동시작일
            $("#pop_sell_event_nm").val($("#sell_event_nm_" + buy_sno).val());
            $('#pop_edate_change_add_edate_cnt').val(0);

            $('#pop_edate_change_edate').datepicker({
                autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                language: "ko", //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
            });
        } else {
            alertToast('error', '종료된 상품은 종료일을 변경할 수 없습니다.');
        }
    }

    function edate_calu_days() {
        var sDate = $('#pop_edate_info_edate').val();
        var eDate = $('#pop_edate_change_edate').val();

        const getDateDiff = (d1, d2) => {
            const date1 = new Date(d1);
            const date2 = new Date(d2);

            const diffDate = date2.getTime() - date1.getTime();

            return (diffDate / (1000 * 60 * 60 * 24)); // 밀리세컨 * 초 * 분 * 시 = 일
        }

        var day_cnt = getDateDiff(sDate, eDate);
        $('#pop_edate_change_add_edate_cnt').val(day_cnt);
    }

    function daycnt_calu_date() {
        var sDate = $('#domcy_acppt_i_sdate').val();
        if (sDate == '') {
            alertToast('error', '휴회신청일을 먼저 선택하세요');
            $('#domcy_acppt_i_cnt').val('');
            return;
        }

        var result = new Date(sDate);
        var addDay = $('#domcy_acppt_i_cnt').val();

        result.setDate(result.getDate() + Number(addDay));

        var date_y = result.getFullYear();
        var date_m = result.getMonth() + 1;
        var date_d = result.getDate();

        var result_date = date_y + "-" + (("00" + date_m.toString()).slice(-2)) + "-" + (("00" + date_d.toString()).slice(-2));

        $('#domcy_acppt_e_sdate').val(result_date);
    }


    // 운동 종료일 변경 처리
    function btn_change_edate_submit() {
        var fc_edate_buy_sno = $('#pop_edate_buy_sno').val();
        var fc_edate_edate = $('#pop_edate_change_edate').val();

        // ### test
        // $('#fc_edate_buy_sno').val(fc_edate_buy_sno);
        // $('#fc_edate_edate').val(fc_edate_edate);
        // $('#form_change_edate').submit();
        // return;

        var params = "fc_edate_buy_sno=" + fc_edate_buy_sno + "&fc_edate_edate=" + fc_edate_edate;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_change_edate_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 수업강사 변경
    function change_stchr(buy_sno, event_stat, mem_id, clas_dv) {

        if (event_stat == '99') {
            alertToast('error', '종료된 상품은 수업강사를 변경 할 수 없습니다.');
            return;
        }

        if (clas_dv == '21' || clas_dv == '22') {
            $('#ch_stchr_id').val(mem_id).trigger('change');
            $('#stchr_buy_sno').val(buy_sno);
            $('#modal_pop_stchr').modal("show");
        } else {
            alertToast('error', '수업상품이 아닌 상품은 수업강사를 지정 할 수 없습니다.');
            return;
        }

    }

    // 수업강사 변경 처리
    function btn_stchr_submit() {
        var fc_stchr_buy_sno = $('#stchr_buy_sno').val();
        var fc_ch_stchr_id = $('#ch_stchr_id').val();

        // ### test
        // $('#fc_stchr_buy_sno').val(fc_stchr_buy_sno);
        // $('#fc_ch_stchr_id').val(fc_ch_stchr_id);
        // $('#form_stchr').submit();
        // return;

        var params = "fc_stchr_buy_sno=" + fc_stchr_buy_sno + "&fc_ch_stchr_id=" + fc_ch_stchr_id;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_stchr_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 판매강사 변경
    function change_ptchr(buy_sno, event_stat, mem_id, buy_date) {
        if (event_stat == '99') {
            alertToast('error', '종료된 상품의 판매강사는 변경이 불가능 합니다.');
            return;
        }

        // 오늘날짜 구하기
        var now_date = new Date();
        var disp_now_date = now_date.getFullYear() + "-" + ("0" + (now_date.getMonth() + 1)).slice(-2) + "-" + ("0" + now_date.getDate()).slice(-2);

        if (buy_date != disp_now_date) {
            alertToast('error', '판매강사 변경은 당일만 가능합니다.');
            return;
        }

        $('#ch_ptchr_id').val(mem_id).trigger('change');
        $('#ptchr_buy_sno').val(buy_sno);
        $('#modal_pop_ptchr').modal("show");
    }

    // 판매강사 변경 처리
    function btn_ptchr_submit() {
        var fc_ptchr_buy_sno = $('#ptchr_buy_sno').val();
        var fc_ch_ptchr_id = $('#ch_ptchr_id').val();

        // ### test
        // $('#fc_ptchr_buy_sno').val(fc_ptchr_buy_sno);
        // $('#fc_ch_ptchr_id').val(fc_ch_ptchr_id);
        // $('#form_ptchr').submit();
        // return;

        var params = "fc_ptchr_buy_sno=" + fc_ptchr_buy_sno + "&fc_ch_ptchr_id=" + fc_ch_ptchr_id;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_ptchr_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 휴회일 추가
    function change_domcy_day(buy_sno, event_stat, domcy_yn) {
        if (event_stat == "99") {
            alertToast('error', '종료된 상품은 휴회일 추가를 할 수 없습니다.');
            return;
        }

        if (domcy_yn == "N") {
            alertToast('error', '해당 상품은 휴회이 불가능한 상품입니다.');
            return;
        }

        $('#domcy_day_buy_sno').val(buy_sno);
        $('#modal_pop_domcy_day').modal("show");
    }

    // 휴회일 추가 처리
    function btn_domcy_day_submit() {
        var domcy_day_buy_sno = $('#domcy_day_buy_sno').val();
        var domcy_day_day = $('#domcy_day_day').val();

        // test
        // $('#fc_domcy_day_buy_sno').val(domcy_day_buy_sno);
        // $('#fc_domcy_day_day').val(domcy_day_day);
        // $('#form_domcy_day').submit();
        // return;

        var params = "fc_domcy_day_buy_sno=" + domcy_day_buy_sno + "&fc_domcy_day_day=" + domcy_day_day;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_domcyday_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });

    }

    // 휴회횟수 추가
    function change_domcy_cnt(buy_sno, event_stat, domcy_yn) {
        if (event_stat == "99") {
            alertToast('error', '종료된 상품은 휴회일 추가를 할 수 없습니다.');
            return;
        }

        if (domcy_yn == "N") {
            alertToast('error', '해당 상품은 휴회이 불가능한 상품입니다.');
            return;
        }

        $('#domcy_cnt_buy_sno').val(buy_sno);
        $('#modal_pop_domcy_cnt').modal("show");
    }

    // 휴회횟수 추가 처리
    function btn_domcy_cnt_submit() {
        var domcy_cnt_buy_sno = $('#domcy_cnt_buy_sno').val();
        var domcy_cnt_cnt = $('#domcy_cnt_cnt').val();

        // test
        // $('#fc_domcy_cnt_buy_sno').val(domcy_cnt_buy_sno);
        // $('#fc_domcy_cnt_cnt').val(domcy_cnt_cnt);
        // $('#form_domcy_cnt').submit();
        // return;

        var params = "fc_domcy_cnt_buy_sno=" + domcy_cnt_buy_sno + "&fc_domcy_cnt_cnt=" + domcy_cnt_cnt;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_domcycnt_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });

    }

    // 수업 추가
    function change_clas_cnt(buy_sno, event_stat, m_cate) {
        if (event_stat == '99') {
            alertToast('error', '종료된 상품은 수업 추가를 할 수 없습니다.');
            return;
        }

        if (m_cate=='GRP' || m_cate=='PRV') {
            $('#clas_cnt_buy_sno').val(buy_sno);
            $('#modal_pop_clas_cnt').modal("show");
        } else {
            alertToast('error', '수업 상품이 아닌 상품은 수업 추가를 할 수 없습니다.');
            return;
        }
    }

    function btn_clas_cnt_submit() {
        var clas_cnt_buy_sno = $('#clas_cnt_buy_sno').val();
        var clas_cnt_cnt = $('#clas_cnt_cnt').val();

        // test
        // $('#fc_clas_cnt_buy_sno').val(clas_cnt_buy_sno);
        // $('#fc_clas_cnt_cnt').val(clas_cnt_cnt);
        // $('#form_clas_cnt').submit();
        // return;

        var params = "fc_clas_cnt_buy_sno=" + clas_cnt_buy_sno + "&fc_clas_cnt_cnt=" + clas_cnt_cnt;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_clascnt_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }


    // 강제 종료하기
    function change_event_just_end(buy_sno) {
        ToastConfirm.fire({
            icon: "question",
            title: "  확인 메세지",
            html: "<font color='#000000' >강제 종료를 하시겠습니까?</font>",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonColor: "#28a745",
        }).then((result) => {
            if (result.isConfirmed) {
                // 성공일 경우	
                var params = "buy_sno=" + buy_sno;
                jQuery.ajax({
                    url: '/ttotalmain/ajax_info_mem_just_end_proc',
                    type: 'POST',
                    data: params,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: 'text',
                    success: function(result) {
                        if (result.substr(0, 8) == '<script>') {
                            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                            location.href = '/tlogin';
                            return;
                        }

                        json_result = $.parseJSON(result);
                        console.log(json_result);
                        if (json_result['result'] == 'true') {
                            location.reload();
                        } else {
                            alertToast('error', json_result['msg']);
                        }
                    }
                }).done((res) => {
                    // 통신 성공시
                    console.log('통신성공');
                }).fail((error) => {
                    // 통신 실패시
                    console.log('통신실패');
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                    location.href = '/tlogin';
                    return;
                });
            }
        });

    }

    function more_info_show(buy_sno, rson) {
        if ($('.sec-event-info').css('display') != 'none') {
            more_info_hide();
            return;
        }

        // 51 : 환불(교체) / 81 : 환불 / 61 : 양도 / 62 : 양수	

        if (rson == "61" || rson == "62") {
            var params = "buy_sno=" + buy_sno + "&rson=" + rson;
            jQuery.ajax({
                url: '/ttotalmain/ajax_info_mem_more_trans_info',
                type: 'POST',
                data: params,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: 'text',
                success: function(result) {
                    if (result.substr(0, 8) == '<script>') {
                        alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                        location.href = '/tlogin';
                        return;
                    }

                    json_result = $.parseJSON(result);
                    if (json_result['result'] == 'true') {
                        var info = json_result['info'];
                        console.log(info);
                        if (info['USE_AMT'] == null) info['USE_AMT'] = 0; // null 오류 방지
                        var info_html = "";
                        info_html += "<table class='table table-bordered table-sm col-md-12'>";
                        info_html += "	<tbody>";
                        info_html += "		<tr>";
                        info_html += "			<td colspan='9' class='bg-gray'>양도내역</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도등록일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도자</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도횟수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용금액</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>남은금액</td>";

                        info_html += "			<td style='background-color:#e2e4e7'>양수자</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양수일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양수횟수</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td>" + info['CRE_DATETM'] + "</td>";
                        info_html += "			<td>" + info['TRANSM_NM'] + " (" + info['TRANSM_ID'] + ") </td>";
                        info_html += "			<td>" + info['TRANSM_LEFT_DAY'] + "</td>";
                        info_html += "			<td>" + info['REAL_TRANSM_CNT'] + "</td>";
                        info_html += "			<td>" + info['USE_AMT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";
                        info_html += "			<td>" + info['LEFT_AMT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";

                        info_html += "			<td>" + info['ASSGN_NM'] + " (" + info['ASSGN_ID'] + ") </td>";
                        info_html += "			<td>" + info['REAL_TRANSM_DAY'] + "</td>";
                        info_html += "			<td>" + info['REAL_TRANSM_CNT'] + "</td>";
                        info_html += "		<tr>";
                        info_html += "	</tbody>";
                        info_html += "</table>";

                        $('#sec_event_info_detail').html(info_html);

                    }
                }
            }).done((res) => {
                // 통신 성공시
                console.log('통신성공');
            }).fail((error) => {
                // 통신 실패시
                console.log('통신실패');
                alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                location.href = '/tlogin';
                return;
            });
        }


        if (rson == "51" || rson == "81") {
            var params = "buy_sno=" + buy_sno + "&rson=" + rson;
            jQuery.ajax({
                url: '/ttotalmain/ajax_info_mem_more_refund_info',
                type: 'POST',
                data: params,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: 'text',
                success: function(result) {
                    if (result.substr(0, 8) == '<script>') {
                        alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                        location.href = '/tlogin';
                        return;
                    }

                    json_result = $.parseJSON(result);
                    if (json_result['result'] == 'true') {
                        var info = json_result['info'];
                        var info_html = "";
                        info_html += "<table class='table table-bordered table-sm col-md-12'>";
                        info_html += "	<tbody>";
                        info_html += "		<tr>";
                        info_html += "			<td colspan='9' class='bg-gray'>환불내역</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td style='background-color:#e2e4e7'>환불일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>총이용일수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용일수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>총수업횟수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용수업횟수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용금액</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>환불금액</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td>" + info['CRE_DATETM'] + "</td>";
                        info_html += "			<td>" + info['TOTAL_EXR_DAY_CNT'] + "</td>";
                        info_html += "			<td>" + info['USE_DAY_CNT'] + "</td>";
                        info_html += "			<td>" + info['CLAS_CNT'] + "</td>";
                        info_html += "			<td>" + info['USE_CLAS_CNT'] + "</td>";
                        info_html += "			<td>" + info['TOTAL_EXR_DAY_CNT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";
                        info_html += "			<td>" + info['REFUND_AMT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";
                        info_html += "		<tr>";
                        info_html += "	</tbody>";
                        info_html += "</table>";

                        $('#sec_event_info_detail').html(info_html);

                    }
                }
            }).done((res) => {
                // 통신 성공시
                console.log('통신성공');
            }).fail((error) => {
                // 통신 실패시
                console.log('통신실패');
                alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                location.href = '/tlogin';
                return;
            });
        }

        $('.sec-mem-info-detail').hide();
        $('.sec-mem-info').show();
        $('.sec-event-info').show();




    }

    function more_info_hide() {
        $('.sec-mem-info-detail').show();
        $('.sec-mem-info').hide();
        $('.sec-event-info').hide();
    }

    // ===================== Modal Script [ START ] ===========================

    // ===================== Modal Script [ END ] =============================

    //Date picker
    $('.datepp').datepicker({
        format: "yyyy-mm-dd", //데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
        autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        clearBtn: false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
        immediateUpdates: true, //사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false 
        multidate: false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false 
        templates: {
            leftArrow: '&laquo;',
            rightArrow: '&raquo;'
        }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징 
        showWeekDays: true, // 위에 요일 보여주는 옵션 기본값 : true
        title: "날짜선택", //캘린더 상단에 보여주는 타이틀
        todayHighlight: true, //오늘 날짜에 하이라이팅 기능 기본값 :false 
        toggleActive: true, //이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
        weekStart: 0, //달력 시작 요일 선택하는 것 기본값은 0인 일요일 

        //startDate: '-10d',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
        //endDate: '+10d',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
        //datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
        //daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
        //daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
        //disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
        //calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
        //multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01

        language: "ko" //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });
</script>