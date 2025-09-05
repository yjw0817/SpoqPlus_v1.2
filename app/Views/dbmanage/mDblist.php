<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">디비 직접 등록하기</h3>
					</div>
					<div class="panel-body">

						<form name="dblistForm" id="dblistForm" method="post"
							action="/dbmanage/mDblistInsertProc">
							<table class="table table-bordered table-hover table-striped col-md-12">
								<thead>
									<tr>
										<th>이름</th>
										<th>전화번호</th>
										<th>업체</th>
										<th>신청이벤트</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><input type="text" name="user_name" id="user_name" /></td>
										<td><input type="text" name="user_phone" id="user_phone" class="phoneNumber" /></td>
										
										
										
										<td><select class="form-control input-sm select2bs4"
											name="company_idx" id="company_idx"
											style="height: calc(1.90rem + 2px) !important;">
												<option value="">업체 선택</option>
											<?php foreach($company_list as $c) : ?>
											<option value="<?=$c['idx']?>"><?=$c['comp_name']?></option>
											<?php endforeach; ?>
						                  	</select>
						                </td>
										<td><select class="form-control input-sm select2bs4"
											name="event_idx" id="event_idx"
											style="height: calc(1.90rem + 2px) !important;">
												<option value="">이벤트 선택</option>
											<?php foreach($event_list as $e) : ?>
											<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
											<?php endforeach; ?>
						                  	</select>
						                </td>
						                <td>
						                	<a class='btn btn-sm btn-success' onclick="insert_dblist();">디비등록</a>
						                </td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>

			</div>
		</div>
		
		
		
		<div class="card card-primary">
			<div class="page-header">
				<h3 class="panel-title">디비 검색 조건</h3>
			</div>
			<div class="panel-body">
			

				<form name="dblistSearchForm" id="dblistSearchForm" method="post" action="/dbmanage/mDblist">
					<table class="table table-bordered table-hover table-striped col-md-12">
						<thead>
							<tr>
								<th>이름</th><td><input type='text' name="unm" id="unm" value="<?=$search_val['unm']?>" /><a class='btn btn-sm btn-danger' onclick="ipt_clear('unm')">X</a></td>
								<th>업체</th>
								<td>
								<select class="form-control input-sm select2bs4"
								name="cnm" id="cnm"
								style="height: calc(1.90rem + 2px) !important;">
									<option value="">업체 선택</option>
								<?php foreach($company_list as $c) : ?>
									<?php if ($c['idx'] == $search_val['cnm']) : ?>
										<option value="<?=$c['idx']?>" selected><?=$c['comp_name']?></option>
									<?php else: ?>
										<option value="<?=$c['idx']?>"><?=$c['comp_name']?></option>
									<?php endif; ?>
								<?php endforeach; ?>
			                  	</select>
								
								</td>
								<th>신청이벤트</th>
								<td>
								<select class="form-control input-sm select2bs4"
								name="ecd" id="ecd"
								style="height: calc(1.90rem + 2px) !important;">
									<option value="">이벤트 선택</option>
								<?php foreach($event_list as $e) : ?>
									<?php if ($e['idx'] == $search_val['ecd']) : ?>
										<option value="<?=$e['idx']?>" selected><?=$e['event_name']?></option>
									<?php else: ?>
										<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
									<?php endif; ?>
								<?php endforeach; ?>
			                  	</select>
								</td>
							</tr>
							<tr>
								<th>전화번호</th><td><input type='text' name="uph" id="uph" value="<?=$search_val['uph']?>" class="phoneNumber" /><a class='btn btn-sm btn-danger' onclick="ipt_clear('uph')">X</a></td>
								<th>진행상태</th><td>
								<select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								name="stu" id="stu">
									<option value="">선택하세요</option>
								<?php foreach ($scode_list as $s): ?>
									<?php if ($s['scode'] == $search_val['stu']) : ?>
										<option value="<?=$s['scode']?>" selected><?=$s['sname']?></option>
									<?php else: ?>
										<option value="<?=$s['scode']?>"><?=$s['sname']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
								
								</td>
								<th>내원이유</th><td>
								<select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								name="ncd" id="ncd">
									<option value="">선택하세요</option>
								<?php foreach ($ncode_list as $n): ?>
									<?php if ($n['ncode'] == $search_val['ncd']) : ?>
										<option value="<?=$n['ncode']?>" selected><?=$n['nname']?></option>
									<?php else: ?>
										<option value="<?=$n['ncode']?>"><?=$n['nname']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
								
								</td>
							</tr>
							<tr>
								<th>신청일시</th><td><input style='width:90px' type='text' name="uds" id="uds" class="search_date" value="<?=$search_val['uds']?>" />~<input style='width:90px' type='text' name="ude" id="ude" class="search_date" value="<?=$search_val['ude']?>" /></td>
								<th>내원예약일</th><td><input style='width:90px' type='text' name="yss" id="yss" class="search_date" value="<?=$search_val['yss']?>" />~<input style='width:90px' type='text' name="yse" id="yse" class="search_date" value="<?=$search_val['yse']?>" /></td>
								<th>내원완료일</th><td><input style='width:90px' type='text' name="yes" id="yes" class="search_date" value="<?=$search_val['yes']?>" />~<input style='width:90px' type='text' name="yee" id="yee" class="search_date" value="<?=$search_val['yee']?>" />
									<a class='btn btn-sm btn-success' onclick="f_search();" >검색</a>
									<a class='btn btn-sm btn-success' onclick="f_search_excel();" >엑셀다운로드</a>
									
									<?php
										$etc_search_disp_checked = '';
										if ($search_val['etc_search'] == 'Y') $etc_search_disp_checked = 'checked';
									?>
									<div class="icheck-primary d-inline col-md-3">
										<input type="checkbox" id="etc_search" name="etc_search" value="Y" class="" <?=$etc_search_disp_checked?> >
										<label for="etc_search">
										담당 업체의 다른 티엠에게 배정된것을 포함하여 검색하려면 체크하세요.
										</label>
									</div>
									
								</td>
							</tr>
						</thead>
					</table>
				</form>
				
			</div>
		</div>

		

		<div class="row">
			<div class="col-md-12">
				<?=$pager?>
				<?php
					$btn_hf_01 = 'btn-info';
					$btn_hf_02 = 'btn-info';
					$btn_hf_03 = 'btn-info';
					
					if ($_SESSION['ses_hf_01'] == 'Y') $btn_hf_01 = 'btn-secondary';
					if ($_SESSION['ses_hf_02'] == 'Y') $btn_hf_02 = 'btn-secondary';
					if ($_SESSION['ses_hf_03'] == 'Y') $btn_hf_03 = 'btn-secondary';
					
				?>
				<?php if($_SESSION['login_type'] == 'tm') : ?>
				<a id="hf_01" class="btn btn-xs <?=$btn_hf_01?>" onclick="hf_func('hf_01');">업체명</a>
				<a id="hf_02" class="btn btn-xs <?=$btn_hf_02?>" onclick="hf_func('hf_02');">견적금액</a>
				<a id="hf_03" class="btn btn-xs <?=$btn_hf_03?>" onclick="hf_func('hf_03');">동의금액</a>
				<?php endif; ?>
				<table class="table table-bordered table-hover table-striped table-hover col-md-12">
					<thead>
						<tr>
							<?php
							if ( $_SESSION['session_admin'] == 'Y') :
								echo '<th>삭제</th>';
							endif;
							?>
							<th>번호</th>
							<th>담당TM</th>
							<th>신청일시</th>
							<th>이름</th>
							<th>전화번호</th>
							<th class="hf_01" <?php if($_SESSION['ses_hf_01'] == 'Y') echo " style='display:none'"; ?> >업체</th>
							<th>신청이벤트</th>
							<th>내원이유</th>
							<th>진행상태</th>
							<th>내원예약일</th>
							<th>예약시간</th>
							<th>내원완료일</th>
							<th class="hf_02" <?php if($_SESSION['ses_hf_02'] == 'Y') echo " style='display:none'"; ?>>견적금액</th>
							<th class="hf_03" <?php if($_SESSION['ses_hf_03'] == 'Y') echo " style='display:none'"; ?>>동의금액</th>
							<th colspan='2'>상담내용</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$listCount = $search_val['listCount'];
						foreach ( $db_list as $r ) :
							?>
						<tr>
							<?php
							if ( $_SESSION['session_admin'] == 'Y') :
								echo "<td><a class='btn btn-sm btn-danger' onclick='f_del(" . $r['idx'] . ")'>삭제</a></td>";
							endif;
							?>
							<td>
							<a class="btn btn-info btn-xs" onclick="copy_clipboard(<?=$r['idx']?>);">복사</a>
							<a class="btn btn-info btn-xs" onclick="copy_clipboard2(<?=$r['idx']?>);">복사2</a>
							<?=$listCount?>
							</td>
							<td><?=$r['tm_name']?></td>
							<td style='width:160px'><?=disp_date($r['uptime'])?></td>
							<!-- 
							<td><?=$r['user_name'] ?></td>
							<td style='width:130px'><?=disp_phone($r['user_phone'])?></td>
							 -->
							<td><!-- 이름 -->
								<input style='width:90px' type="text" name="user_name" id="user_name_<?=$r['idx']?>" value="<?=$r['user_name']?>" /><a class='btn btn-success btn-xs' onclick="change_user_name(<?=$r['idx']?>);">V</a>
							</td>
							<td><!-- 전화번호 -->
								<input style='width:110px' type="text" name="user_phone" id="user_phone_<?=$r['idx']?>" value="<?=$r['user_phone']?>" /><a class='btn btn-success btn-xs' onclick="change_user_phone(<?=$r['idx']?>);">V</a>
							</td>
							
							<?php
							if ( $_SESSION['session_admin'] == 'Y') :
							?>
							<td><select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="change_company(this,<?=$r['idx']?>);">
									<option value="">선택하세요</option>
									<option value="<?=$r['company_idx']?>" selected><?=$r['comp_name']?></option>
								<?php foreach ($company_list as $c): ?>
									<?php if ($c['idx'] == $r['company_idx']) : ?>
										<!-- <option value="<?=$c['idx']?>" selected><?=$c['comp_name']?></option>  -->
									<?php else: ?>
										<option value="<?=$c['idx']?>"><?=$c['comp_name']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
							</td>
							
							<td><select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="change_event(this,<?=$r['idx']?>);">
									<option value="">선택하세요</option>
									<option value="<?=$r['event_idx']?>" selected><?=$r['event_name']?></option>
								<?php foreach ($event_list as $e): ?>
									<?php if ($e['idx'] == $r['event_idx']) : ?>
										<!-- <option value="<?=$e['idx']?>" selected><?=$e['event_name']?></option>  -->
									<?php else: ?>
										<option value="<?=$e['idx']?>"><?=$e['event_name']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
							</td>
							
							<?php
							else :
							?>
							<td class="hf_01" <?php if($_SESSION['ses_hf_01'] == 'Y') echo " style='display:none'"; ?> ><?=$r['comp_name'] ?></td>
							<td><?=$r['event_name'] ?></td>
							<?php endif; ?>
							
							<td id="nname_<?=$r['idx']?>"><?=$r['nname'] ?></td>
							<td><select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="change_scode(this,<?=$r['idx']?>);">
									<option value="">선택하세요</option>
								<?php foreach ($scode_list as $s): ?>
									<?php if ($s['scode'] == $r['user_status']) : ?>
										<option value="<?=$s['scode']?>" selected><?=$s['sname']?></option>
									<?php else: ?>
										<option value="<?=$s['scode']?>"><?=$s['sname']?></option>
									<?php endif; ?>
			                    <?php endforeach; ?>
			                  	</select>
			                </td>
							<td>
								<input style='width:90px' type="text" name="ye_sdate" class="ye_sdate" data-idx="<?=$r['idx']?>" value="<?=disp_date($r['ye_sdate'])?>">
							</td>
							<td>
								<select class="input-sm select2bs4"
								style="height: calc(1.90rem + 2px) !important;"
								onchange="change_yetime(this,<?=$r['idx']?>);">
									<option value="">선택하세요</option>
									<?php for($i=0; $i< count($yetime) ; $i++) :?>
										<?php if ($yetime[$i] == $r['ye_time']) : ?>
											<option value="<?=$yetime[$i]?>" selected><?=$yetime[$i]?></option>
										<?php else: ?>
											<option value="<?=$yetime[$i]?>"><?=$yetime[$i]?></option>
										<?php endif; ?>
									<?php endfor; ?>
			                  	</select>
							</td>
							<td>
								<input style='width:90px' type="text" name="ye_edate" class="ye_edate" data-idx="<?=$r['idx']?>" value="<?=disp_date($r['ye_edate'])?>">
							</td>
							<?php
								$disp_cont_cost = '';
								$disp_dong_cost = '';
							
								if ($r['cont_cost']):
									$disp_cont_cost = number_format($r['cont_cost']);
								endif;
								
								if ($r['dong_cost']):
									$disp_dong_cost = number_format($r['dong_cost']);
								endif;
								
								if ($_SESSION['ses_hf_02'] == 'Y')
								{
									$style_hf2 = 'display:none;width:140px';
								} else 
								{
									$style_hf2 = 'width:140px';
								}
								
								if ($_SESSION['ses_hf_03'] == 'Y')
								{
									$style_hf3 = 'display:none;width:140px';
								} else
								{
									$style_hf3 = 'width:140px';
								}
								
								
							?>
							<td style='<?=$style_hf2?>' class="hf_02">
								<input style='width:90px' class='numttt' type="text" name="cont_cost" id="cont_cost_<?=$r['idx']?>" value="<?=$disp_cont_cost?>" /><a class='btn btn-success btn-xs' onclick="change_cont_cost(<?=$r['idx']?>);">V</a>
							</td>
							<td style='<?=$style_hf3?>' class="hf_03">
								<input style='width:90px' class='numttt' type="text" name="dong_cost" id="dong_cost_<?=$r['idx']?>" value="<?=$disp_dong_cost?>" /><a class='btn btn-success btn-xs' onclick="change_dong_cost(<?=$r['idx']?>);">V</a>
							</td>
							<td style='width:400px' onmouseover="showTooltip(<?=$r['idx']?>);" onmouseout="hideTooltip(<?=$r['idx']?>);" onmousemove="moveTooltip(event,<?=$r['idx']?>);">
								<span style='width:100%;font-size:9px;background-color:cornsilk'><?=$r['needs']?></span>
								<div style='width:100%;font-size:7px;'><?=nl2br($r['scontent'])?></div>
								<!-- <textarea style='width:100%;font-size:7px;overflow:visible;'></textarea> -->
								<div id='tool_<?=$r['idx']?>' style='position:fixed;width:600px;height:200px;font-size:14px;display:none;border:2px solid blue;background-color:#eee'><?=$r['needs']?><br /><?=nl2br($r['scontent'])?></div>
							</td>
							<td>
								<a class="btn btn-success btn-sm" onclick="scontent_pop(<?=$r['idx']?>,'<?=$r['user_phone']?>');">입력</a>
								<?php
								if ( $_SESSION['login_type'] == 'tm') :
								?>
								<a class="btn btn-info btn-xs" onclick="tmsms_pop(<?=$r['idx']?>,'<?=$r['user_phone']?>');">문자</a>
								<a class="btn btn-info btn-xs" onclick="tmtrans_pop(<?=$r['idx']?>);">TM이관</a>
								
								
								<?php endif; ?>
							</td>
						</tr>
						<?php
						$listCount--;
						endforeach; 
						?>
					</tbody>
				</table>
				<?=$pager?>
			</div>

		</div>

	</div>
	
	<!-- =========================================== 
				TM SMS  
	=========================================== -->
	<div class="modal fade" id="tm_sms_modal_pop">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">문자보내기</h4>
              <button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-md-4">
            	
            	
            <input type='hidden' id='tm_sms_modal_pop_db_idx' />
            <!-- Form Start -->
            <form name="tm_sms_modal_form" id="tm_sms_modal_form" method="post">
            	<input type='hidden' id='sms_rphone' name='sms_rphone' />
            	<table class="table table-bordered table-hover table-striped col-md-12">
					<tr>
						<td>
							<textarea style='width:100%' type="text" class="sms_content" id="sms_content" name="sms_content" rows="10" ></textarea>
							<br />
							<span class="textCount">0자</span>
    						<span class="textTotal">/200자</span>
						</td>
					</tr>
				</table>
			</form>
			<!-- Form End -->
					</div>
					<div class="col-md-4">
						<div id='sms_btn_list'>
							<?php
							foreach($sms_list as $sms):
							?>
							<div>
								<a class='btn btn-xs btn-info' onclick="sms_read(<?=$sms['idx']?>);">[<?=$sms['sms_title'] ?>] - <?=$sms['sms_stitle'] ?></a>
							</div>
							
							
							<?php
							endforeach;
							?>
						</div>
					</div>
					<div class="col-md-4">
						버튼을 클릭하여 템플릿을 선택하거나 <br />
						직접 내용을 입력하여 보낼 수 있습니다.<br />
						문자 전송이 안되면 TM 전화번호 설정이나 <br />
						Cafe24 발신번호 등록이 안되어있을 수 있습니다.<br />
						관리자에게 문의하여 등록 해주세요.
					</div>
            	</div>
            
            
            </div>
            <div class="modal-footer text-left">
            	<button type="button" class="btn btn-primary btn-sm" onclick="tm_sms_modal_action();" id="tm_sms_model_btn">문자보내기</button>
              	<button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      
      
	
	<!-- /.modal [start] 상담내용 모달 팝업 -->
	<div class="modal fade" id="modal-xl">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">상담내용</h4>
					<button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				<div class="row">
				<div class="col-md-6">
            
					<form name="scontent_form" id="scontent_form" method="post"> 
            
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr>
									<th>상담내용</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										진행상태 : 
										<select class="input-sm select2bs4"
										style="height: calc(1.90rem + 2px) !important;"
										name="modal-xl-select" id="modal-xl-select" onchange="scontent_status_ch(this);">
											<option value="">선택하세요</option>
										<?php foreach ($scode_list as $s): ?>
											<?php if ($s['scode'] == $search_val['stu']) : ?>
												<option value="<?=$s['scode']?>" selected><?=$s['sname']?></option>
											<?php else: ?>
												<option value="<?=$s['scode']?>"><?=$s['sname']?></option>
											<?php endif; ?>
					                    <?php endforeach; ?>
					                  	</select>
									</td>
								</tr>
								<tr>
									<td>
										<textarea style="width:100%" name="scontent" id="scontent" rows='10'></textarea>
									</td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" name="db_idx" id="db_idx" />
					</form>
					<input type="hidden" name="ses_user_id" id="ses_user_id" value="<?=$_SESSION['user_id'] ?>" />
					<div class="row">
						<div class="col-md-12">
							콜대기를 선택하세요.
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							콜대기 날짜 선택 : <input style='width:90px' type="text" name="tmcall_su_date" id="tmcall_su_date" class="tmcall_su_date" data-idx="" value="">
							
							<select class="input-sm select2bs4"
							style="height: calc(1.90rem + 2px) !important;"
							name="tmcall_su_time" id="tmcall_su_time">
								<option value="">시간 선택</option>
								<?php for($i=0; $i< count($yetime) ; $i++) :?>
									<option value="<?=$yetime[$i]?>"><?=$yetime[$i]?></option>
								<?php endfor; ?>
		                  	</select>
		                  	
		                  	<a class='btn btn-success btn-sm' onclick="tmcall_su_submit();">콜대기 직접 설정</a>
							
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-md-12">
							<br />
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('30','minutes');">30분후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('60','minutes');">1시간후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('90','minutes');">1시간30분후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('120','minutes');">2시간후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('150','minutes');">2시간30분후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('180','minutes');">3시간후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('210','minutes');">3시간30분후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('240','minutes');">4시간후</a>
							<!-- 
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('300','minutes');">5시간후</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('360','minutes');">6시간후</a>
							 -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('1','days');">1일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('2','days');">2일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('3','days');">3일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('4','days');">4일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('5','days');">5일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('6','days');">6일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('7','days');">7일뒤</a>
							
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('10','days');">10일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('15','days');">15일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('30','days');">30일뒤</a>
							<a class="btn btn-info btn-xs" onclick="tmcall_proc('60','days');">60일뒤</a>
						</div>
					</div>
				
				</div><!-- 추가 col-6 end #1 -->
				
				<div class="col-md-6"> <!-- 추가 col-6 start #2 -->
				<div class="row"><div class="col-md-12">
				
				<div class="row">
            		<div class="col-md-8">
            	
            			<input type='hidden' id='scontxt_tm_sms_modal_pop_db_idx' />
			            <!-- Form Start -->
			            <form name="scontxt_tm_sms_modal_form" id="scontxt_tm_sms_modal_form" method="post">
			            	<input type='hidden' id='scontxt_sms_rphone' name='sms_rphone' />
			            	<table class="table table-bordered table-hover table-striped col-md-12">
								<tr>
									<td>
										<textarea style='width:100%' type="text" class="sms_content" id="scontxt_sms_content" name="sms_content" rows="10" ></textarea>
										<br />
										<span class="scontxt_textCount">0자</span>
			    						<span class="scontxt_textTotal">/200자</span>
									</td>
								</tr>
							</table>
						</form>
						<!-- Form End -->
					</div>
					<div class="col-md-4">
						<div id='sms_btn_list'>
							<?php
							foreach($sms_list as $sms):
							?>
							<div>
								<a class='btn btn-xs btn-info col-md-12' onclick="scontxt_sms_read(<?=$sms['idx']?>);">[<?=$sms['sms_title'] ?>] - <?=$sms['sms_stitle'] ?></a>
							</div>
							
							
							<?php
							endforeach;
							?>
						</div>
					</div>
					
					<button type="button" class="btn btn-primary btn-sm col-md-8" onclick="scontxt_tm_sms_modal_action();" id="scontxt_tm_sms_model_btn">문자보내기</button>
            	</div>			
				
				
				</div></div>
				
				</div><!-- 추가 col-6 end #2 -->
				</div><!-- 추가 row -->
				
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" onclick="scontent_modify();">입력하기</button>
					<button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
				</div>
				
				
			</div>
          	<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal [end] -->
	</div>
	
	<!-- /.modal [start] TM 이관 모달 팝업 -->
	<div class="modal fade" id="tm_modal_pop">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">TM 이관</h4>
					<button type="button" class="close"  data-bs-dismiss="modal" aria-label="닫기">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
            
					<form name="tmtrans_form" id="tmtrans_form" method="post"> 
            
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr>
									<th>TM</th>
								</tr>
							</thead>
							<tbody>
							
								<?php foreach ($tm_list as $tm): ?>
								<tr>
									<td>
										<div class="icheck-primary d-inline">
											<input type="radio" id="<?=$tm['tm_id']?>" name="ch_tm" value="<?=$tm['tm_id']?>" />
											<label for="<?=$tm['tm_id']?>"><?=$tm['tm_name'] ?> ( <?=$tm['tm_id']?> )</label>
										</div>
									</td>
								</tr>

								<?php endforeach; ?>
								
							</tbody>
						</table>
						<input type="hidden" name="tm_modal_idx" id="tm_modal_idx" />
					</form>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" onclick="tmtrans_modify();">변경하기</button>
					<button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
				</div>
			</div>
          	<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal [end] -->	
	
	
	
	
</section>


<?=$jsinc ?>

<script type="text/javascript">
$(document).ready(function(){
	$(".numttt").on("keyup", function() {
		$(this).val(addComma($(this).val().replace(/[^0-9]/g,"")));
	});

	$("#event_idx").select2();
	$("#ecd").select2();
	
});


function hf_func(hf_field)
{
	//$("."+hf_field).hide();
	//$("."+hf_field).show();
	
	var params = "hf_field="+hf_field;
	jQuery.ajax({
        url: '/dbmanage/ajax_dbhideSet',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				json_result['hf_field'];
				json_result['ses_yn']; // N이면 보이기 , Y면 숨김처리
				
				if(json_result['ses_yn'] == "Y")
				{
					$("."+hf_field).hide();
					$("#"+hf_field).removeClass('btn-info');
					$("#"+hf_field).addClass('btn-secondary');
				} else 
				{
					$("."+hf_field).show();
					$("#"+hf_field).removeClass('btn-secondary');
					$("#"+hf_field).addClass('btn-info');
				}
			}
        }
    });
	
	
	
}

$('#sms_content').keyup(function (e) {
	let content = $(this).val();
    
    // 글자수 세기
    if (content.length == 0 || content == '') {
    	$('.textCount').text('0자');
    } else {
    	$('.textCount').text(content.length + '자');
    }
    
    // 글자수 제한
    if (content.length > 200) {
    	// 200자 부터는 타이핑 되지 않도록
        //$(this).val($(this).val().substring(0, 200));
        // 200자 넘으면 알림창 뜨도록
        //alert('글자수는 200자까지 입력 가능합니다.');
    }
});


// 2023-04-16 추가
$('#scontxt_sms_content').keyup(function (e) {
	let content = $(this).val();
    
    // 글자수 세기
    if (content.length == 0 || content == '') {
    	$('.scontxt_textCount').text('0자');
    } else {
    	$('.scontxt_textCount').text(content.length + '자');
    }
    
    // 글자수 제한
    if (content.length > 200) {
    	// 200자 부터는 타이핑 되지 않도록
        //$(this).val($(this).val().substring(0, 200));
        // 200자 넘으면 알림창 뜨도록
        //alert('글자수는 200자까지 입력 가능합니다.');
    }
});


function copy_clipboard(idx) {
  
      
    var params = "idx="+idx;
    jQuery.ajax({
        url: '/dbmanage/ajax_get_dblist',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
			
			const textArea = document.createElement('textarea');
      		textArea.value = json_result['clip_content'];
      		
			document.body.appendChild(textArea);
		      textArea.select();
		      textArea.setSelectionRange(0, 99999);
		      try {
		        document.execCommand('copy');
		      } catch (err) {
		        console.error('복사 실패', err);
		      }
		      textArea.setSelectionRange(0, 0);
		      document.body.removeChild(textArea);
		      
		      
		      
		      toastr["info"](json_result['user_name'] + "님의 정보가 복사 되었습니다.");
		      //alert('복사되었습니다.');
			
			}
        }
    });
      
}

function copy_clipboard2(idx) {
  
      
    var params = "idx="+idx;
    jQuery.ajax({
        url: '/dbmanage/ajax_get_dblist2',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
			
			const textArea = document.createElement('textarea');
      		textArea.value = json_result['clip_content'];
      		
			document.body.appendChild(textArea);
		      textArea.select();
		      textArea.setSelectionRange(0, 99999);
		      try {
		        document.execCommand('copy');
		      } catch (err) {
		        console.error('복사 실패', err);
		      }
		      textArea.setSelectionRange(0, 0);
		      document.body.removeChild(textArea);
		      
		      
		      
		      toastr["info"](json_result['user_name'] + "님의 정보가 복사 되었습니다.");
		      //alert('복사되었습니다.');
			
			}
        }
    });
      
}



toastr.options = {
				  "closeButton": false,
				  "debug": false,
				  "newestOnTop": false,
				  "progressBar": false,
				  "positionClass": "toast-bottom-center",
				  "preventDuplicates": false,
				  "onclick": null,
				  "showDuration": "300",
				  "hideDuration": "300",
				  "timeOut": "1000",
				  "extendedTimeOut": "300",
				  "showEasing": "swing",
				  "hideEasing": "linear",
				  "showMethod": "fadeIn",
				  "hideMethod": "fadeOut"
				};
				


// x 버튼 클릭하면 해당 ID 에 대한 input value 클리어 하기
function ipt_clear(t)
{
	$("#"+t).val('');
}

//천단위마다 콤마 생성
function addComma(data) {
	return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

//모든 콤마 제거 방법
function removeCommas(data) {
	return data.replace(/[,]/g,'');

}


</script>

<script>

	// 2023-04-16 추가
	function scontxt_tm_sms_modal_action()
	{
		var params = $("#scontxt_tm_sms_modal_form").serialize();
		jQuery.ajax({
	        url: '/dbmanage/ajax_tmSmsSendProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					console.log( json_result['msg'] );
					alert('문자전송이 완료되었습니다.');
					//location.href="/manage/mCompanyModify";
					//location.reload();
					
				}
	        }
	    });
	}


	function tm_sms_modal_action()
	{
		var params = $("#tm_sms_modal_form").serialize();
		jQuery.ajax({
	        url: '/dbmanage/ajax_tmSmsSendProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					console.log( json_result['msg'] );
					alert('문자전송이 완료되었습니다.');
					//location.href="/manage/mCompanyModify";
					//location.reload();
					
				}
	        }
	    });
	}


	function tmcall_proc(t,dan)
	{
		var db_idx = $('#db_idx').val();
		var ses_user_id = $('#ses_user_id').val();
		var params = "t="+t+"&dan="+dan+"&db_idx="+db_idx+"&ses_user_id="+ses_user_id;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_dblist_tmcallProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					//location.reload();
					scontent_modify();
				}
	        }
	    });
		
	}
	
	function tmcall_su_submit()
	{
		var db_idx = $('#db_idx').val();
		var ses_user_id = $('#ses_user_id').val();
		
		if ( $('#tmcall_su_date').val() == '' )
		{
			alert('날짜를 선택하세요');
			return;
		}
		
		if ( $('#tmcall_su_time').val() == '' )
		{
			alert('시간을 선택하세요');
			return;
		}
		
		if ( $('#tmcall_su_date').val() && $('#tmcall_su_time').val() )
		{
			var td = $('#tmcall_su_date').val();
			var tt = $('#tmcall_su_time').val();
			
			td = td.replace(/\-/g,'');
			tt = tt.replace(/\:/g,'');
		
			var params = "td="+td+"&tt="+tt+"&db_idx="+db_idx+"&ses_user_id="+ses_user_id;
		    jQuery.ajax({
		        url: '/dbmanage/ajax_dblist_su_tmcallProc',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						//location.reload();
						scontent_modify();
					}
		        }
		    });
		}
		
		
		
	}


	function showTooltip(idx) 
	{
		$('#tool_'+idx).show();
	}

	function hideTooltip(idx) 
	{
		$('#tool_'+idx).hide();
	}

	function moveTooltip(event,idx) 
	{
		var x = event.clientX - 600;
		var y = event.clientY;
		
		$('#tool_'+idx).css({
			"top": y,
			"left": x,
			"position": "fixed"
		})
		
	}

	function f_del(idx)
	{
		if ( confirm("정말로 삭제하시겠습니까?") )
		{
			var params = "idx="+idx;
		    jQuery.ajax({
		        url: '/dbmanage/ajax_dblist_deleteProc',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						alert("삭제가 완료 되었습니다.");
						location.reload();
					}
		        }
		    });
		}
		
	}
	
	function tmsms_pop(tm_sms_idx,rphone)
	{
		$('#tm_sms_modal_pop').modal('show');
		$('#sms_content').val('');
		$('.textCount').text('0자'); // 2023-04-16 추가
		$('#tm_sms_modal_pop_db_idx').val( tm_sms_idx );
		$('#sms_rphone').val( rphone );
	}
	
	
	function sms_read(idx)
	{
		var db_idx = $('#tm_sms_modal_pop_db_idx').val();
		
		var params = "idx="+idx+"&db_idx="+db_idx;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_sms_read',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#sms_content').val( json_result['msg'] );
				}
	        }
	    });
	}
	
	
	// 2023-04-16 추가
	function scontxt_sms_read(idx)
	{
		var db_idx = $('#scontxt_tm_sms_modal_pop_db_idx').val();
		
		var params = "idx="+idx+"&db_idx="+db_idx;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_sms_read',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#scontxt_sms_content').val( json_result['msg'] );
				}
	        }
	    });
	}
	
	
	
	function tmtrans_pop(tm_modal_idx)
	{
		$('#tm_modal_pop').modal('show');
		$('#tm_modal_idx').val( tm_modal_idx );
	}
	
	function tmtrans_modify()
	{
		var f = $("#tmtrans_form");
		var testVal = $('input[name=ch_tm]:checked').val();
		
		if (testVal == undefined)
		{
			alert("이관할 TM을 선택해주세요");

		} else 
		{
			var params = $("#tmtrans_form").serialize();
			jQuery.ajax({
		        url: '/dbmanage/ajax_tmTransModifyProc',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						//alert('상담내용이 입력 되었습니다.');
						//location.href="/manage/mCompanyModify";
						location.reload();
					}
		        }
		    });
		}
	}

	// 2023-04-16 추가	
	function js_now_dt()
	{
		var date = new Date();
		var year = date.getFullYear();
		var month = ("0" + (date.getMonth() + 1)).slice(-2);
		var day = ("0" + date.getDate()).slice(-2);
		var hours = ("0" + date.getHours()).slice(-2);
		var minutes = ("0" + date.getMinutes()).slice(-2);
		var seconds = ("0" + date.getSeconds()).slice(-2);
		var formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
		
		return formattedDate;
	}
	
	// 2023-04-16 추가
	function scontent_status_ch(t)
	{
		var ch_text = "{{" + $(t).children('option:selected').text() + "}}"; 
		var ch_str = $('#scontent').val();
		ch_str = ch_str.replace(/{{[^{}]+}}/g, ch_text);
		$('#scontent').val(ch_str);
	}

	// 2023-04-16 수정
	function scontent_pop(db_idx,rphone)
	{
		$('#scontxt_sms_content').val('');
		$('.scontxt_textCount').text('0자');
		
		var params = "idx="+db_idx;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_scontentCheck',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
				
					$('#modal-xl').modal('show');
					
					$('#db_idx').val( json_result['db_content']['idx'] );
					
					$('#scontxt_tm_sms_modal_pop_db_idx').val(db_idx); // 2023-04-16 추가
					$('#scontxt_sms_rphone').val( rphone );
					
					
					var nnn = "\n";
					if ( json_result['db_content']['scontent'] == null )
					{
						nnn = "";
					}
					
					var result_content = js_now_dt() + " {{상태유지}}" + nnn + nnn + json_result['db_content']['scontent'];
					var result_content_end = result_content.replace(/null/g, "");
					
					$('#scontent').val( result_content_end );
					
					$('#tmcall_su_date').data('datepicker').setDate(null);
					$('#tmcall_su_time').val('');
				}
	        }
	    });
	}
	
	function scontent_modify()
	{
		// 2023-04-16 {{}} 치환 -> [] 추가 (시작) modal-xl-select
		var result_content = $('#scontent').val();
		var result_content_end1 = result_content.replace(/{{/g, "[");
		var result_content_end2 = result_content_end1.replace(/}}/g, "]");
		$('#scontent').val(result_content_end2);
		// 2023-04-16 {{}} 치환 -> [] 추가 (끝)
		
		var params = $("#scontent_form").serialize();
	    jQuery.ajax({
	        url: '/dbmanage/ajax_scontentModifyProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					//alert('상담내용이 입력 되었습니다.');
					//location.href="/manage/mCompanyModify";
					location.reload();
					
					//console.log(params);
				}
	        }
	    });
	}

	function f_search_excel()
	{
		var f = document.dblistSearchForm;
		f.action = "/dbmanage/mDblistExcel";
		
		if ( $('#uds').val() != '' || $('#ude').val() != '' )
		{
			if ( $('#uds').val() == '' )
			{
				alert("신청 시작일을 입력하세요");
				return;
			}
			
			if ( $('#ude').val() == '' )
			{
				alert("신청 종료일을 입력하세요");
				return;
			}
		}
		
		if ( $('#yss').val() != '' || $('#yse').val() != '' )
		{
			if ( $('#yss').val() == '' )
			{
				alert("내원예약 시작일을 입력하세요");
				return;
			}
			
			if ( $('#yse').val() == '' )
			{
				alert("내원예약 종료일을 입력하세요");
				return;
			}
		}
		
		if ( $('#yes').val() != '' || $('#yee').val() != '' )
		{
			if ( $('#yes').val() == '' )
			{
				alert("내원완료 시작일을 입력하세요");
				return;
			}
			
			if ( $('#yee').val() == '' )
			{
				alert("내원완료 종료일을 입력하세요");
				return;
			}
		}
		
		
		f.submit();
		
	}

	function f_search()
	{
		var f = document.dblistSearchForm;
		f.action = "/dbmanage/mDblist";
		
		if ( $('#uds').val() != '' || $('#ude').val() != '' )
		{
			if ( $('#uds').val() == '' )
			{
				alert("신청 시작일을 입력하세요");
				return;
			}
			
			if ( $('#ude').val() == '' )
			{
				alert("신청 종료일을 입력하세요");
				return;
			}
		}
		
		if ( $('#yss').val() != '' || $('#yse').val() != '' )
		{
			if ( $('#yss').val() == '' )
			{
				alert("내원예약 시작일을 입력하세요");
				return;
			}
			
			if ( $('#yse').val() == '' )
			{
				alert("내원예약 종료일을 입력하세요");
				return;
			}
		}
		
		if ( $('#yes').val() != '' || $('#yee').val() != '' )
		{
			if ( $('#yes').val() == '' )
			{
				alert("내원완료 시작일을 입력하세요");
				return;
			}
			
			if ( $('#yee').val() == '' )
			{
				alert("내원완료 종료일을 입력하세요");
				return;
			}
		}
		
		
		f.submit();
	}
	
	function change_yetime(t,idx)
	{
		var params = "idx="+idx+"&ye_time="+t.value;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistYetimeChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('예약시간이 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	function change_dong_cost(idx)
	{
	
		var params = "idx="+idx+"&dong_cost="+removeCommas($('#dong_cost_'+idx).val());
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistDongCostChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('동의금액이 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	function change_cont_cost(idx)
	{
		var params = "idx="+idx+"&cont_cost="+removeCommas($('#cont_cost_'+idx).val());
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistContCostChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('계약금액이 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}

	function change_scode(t,idx)
	{
		var params = "idx="+idx+"&user_status="+t.value;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistStatusChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('상태가 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	function change_company(t,idx)
	{
		var params = "idx="+idx+"&company_idx="+t.value;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistCompanyChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('업체가 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	function change_event(t,idx)
	{
		var params = "idx="+idx+"&event_idx="+t.value;
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistEventChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$("#nname_"+idx).text( json_result['nname'] );
					alert('이벤트가 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	function change_user_name(idx)
	{
		var params = "idx="+idx+"&user_name="+$('#user_name_'+idx).val();
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistUsernameChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('이름이 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	function change_user_phone(idx)
	{
		var params = "idx="+idx+"&user_phone="+$('#user_phone_'+idx).val();
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistUserphoneChange',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('전화번호가 변경되었습니다.');
					//location.reload();
				}
	        }
	    });
	}
	
	function insert_dblist()
	{
	
	
	if ( $('#user_name').val() == "")
	{
		alert('이름을 입력하세요');
		return;
	}
	
	if ( $('#user_phone').val() == "")
	{
		alert('전화번호 입력하세요');
		return;
	}
	
	if ( $('#company_idx').val() == "")
	{
		alert('업체를 선택하세요');
		return;
	}
	
	if ( $('#event_idx').val() == "")
	{
		alert('이벤트를 선택하세요');
		return;
	}
	
		var params = $("#dblistForm").serialize();
	    jQuery.ajax({
	        url: '/dbmanage/ajax_mDblistInsertProc',
	        type: 'POST',
	        data:params,
	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	        dataType: 'text',
	        success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					alert('디비가 ' + json_result['dup_text'] + ' 등록되었습니다.');
					location.reload();
				}
	        }
	    });
	}
	
	$(function () {

		$("#unm").on("keyup",function(key){
			if(key.keyCode==13) {
				f_search();
			}
		});

		$("#uph").on("keyup",function(key){
			if(key.keyCode==13) {
				f_search();
			}
		});

		//Date range picker
		
		$('.tmcall_su_date').datepicker({
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
		    title: "콜대기일 선택",	//캘린더 상단에 보여주는 타이틀
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
		
	    $('.ye_sdate').datepicker({
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
		    title: "내원예약일",	//캘린더 상단에 보여주는 타이틀
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
	    
	    $('.ye_sdate').change(function(){
		     change_ye_date("ye_sdate",this.dataset['idx'],this.value);
		});
		
		$('.ye_edate').change(function(){
		     change_ye_date("ye_edate",this.dataset['idx'],this.value);
		});
		
		function change_ye_date(ye_kind,idx,ch_date)
		{
			var params = "ye_kind=" + ye_kind + "&idx="+idx+"&ch_date="+ch_date;
		    jQuery.ajax({
		        url: '/dbmanage/ajax_mDblistyeDateChange',
		        type: 'POST',
		        data:params,
		        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
		        dataType: 'text',
		        success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						alert('날짜가 변경되었습니다.');
						//location.reload();
					}
		        }
		    });
		}
	    
	    
	    $('.ye_edate').datepicker({
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
		    title: "내원완료일",	//캘린더 상단에 보여주는 타이틀
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
	    
	    $('.search_date').datepicker({
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
		    title: "",	//캘린더 상단에 보여주는 타이틀
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
	    
	});

	$(document).on("keyup", ".phoneNumber", function() { $(this).val( $(this).val().replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/,"$1-$2-$3").replace("--", "-") ); });
</script>