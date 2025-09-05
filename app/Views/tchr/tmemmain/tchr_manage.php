<style>
	.panel, .panel-inverse {
		overflow: visible !important;
	}
	/* 회원 사진 스타일 */
	.preview_mem_photo {
		width: 30px;
		height: 30px;
		object-fit: cover;
		border-radius: 50%;
		border: 1px solid #ccc;
		margin-right: 8px;
		vertical-align: middle;
	}
</style>
<?php
$sDef = SpoqDef();
?>
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->



<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">강사 검색</h5>
        </div>
<form name="form_search_tchr_manage" id="form_search_tchr_manage" method="post" onsubmit="return false;">
   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 강사명  </li>
		  <li style="width: 300px;">
			<input type="text" class="form-control"  name="snm" id="snm"  value="<?php echo $search_val['snm']?>" placeholder="강사명 검색 (Enter 또는 자동 검색)" style="width: 100%;">
		  </li>
		  <li>
			 <select class="form-control"  name="sposn" id="sposn">
				<option value="">강사직책</option>
				<?php foreach ($sDef['TCHR_POSN'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['sposn'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		  <li>
			 <select class="form-control" name="sctrct" id="sctrct">
				<option value="">계약형태</option>
				<?php foreach ($sDef['CTRCT_TYPE'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['sctrct'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
		  </li>
		 </ul>
	</div>	
</form>
</div>














<!-- <div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">강사 검색</h4>
	</div>
	<div class="clearfix mt10 mb10">
		<form name="form_search_tchr_manage" id="form_search_tchr_manage" method="post" action="/tmemmain/tchr_manage">
			<div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px'>강사명</span>
				</span>
				<input type="text" class="" name="snm" id="snm" style='width:100px' value="<?php echo $search_val['snm']?>" >
				
				<select class=" " style="width: 150px;margin-left:5px;" name="sposn" id="sposn">
				<option value="">강사직책</option>
				<?php foreach ($sDef['TCHR_POSN'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['sposn'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
				
				<select class=" " style="width: 150px;margin-left:5px;" name="sctrct" id="sctrct">
				<option value="">계약형태</option>
				<?php foreach ($sDef['CTRCT_TYPE'] as $r => $v) : ?>
				<?php if($r != '') :?>
					<option value="<?php echo $r?>" <?php if($search_val['sctrct'] == $r) {?> selected <?php } ?> ><?php echo $v?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
		</form>
	</div>
</div> -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">강사 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
		<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		총 강사수 : <?php echo $totalCount?> 명
		<table class="table table-bordered table-hover table-striped col-md-12 mt20">
			<thead>
				<tr class='text-center'>
					<th style='width:70px'>번호</th>
					<th style='width:140px'>강사이름</th>
					<th style='width:150px'>아이디</th>
					<th style='width:110px'>전화번호</th>
					<th style='width:100px'>생년월일</th>
					<th style='width:70px'>성별</th>
					<th style='width:150px'>강사직책</th>
					<th style='width:120px'>계약형태</th>
					
					<th style='width:150px'>등록일시</th>
					<th>옵션</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($tchr_list as $r): 
				$bg_class = "";
				if ($r['USE_YN'] == "N") $bg_class='bg-gray';
				?>
				<tr class="<?php echo $bg_class?>">
					<td class='text-center'><?php echo $search_val['listCount']?></td>
					<td>
						<?php if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) : ?>
							<img class="preview_mem_photo"
								id="preview_mem_photo_<?php echo $r['MEM_SNO']?>"
								src="<?php echo isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '' ?>"
								alt="강사사진"
								style="cursor: pointer;"
								onclick="showFullPhoto('<?php echo isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '' ?>')"
								onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M' ?>.png';">
						<?php endif; ?>
						<?php echo $r['MEM_NM']?>
					</td>
					<td><?php echo $r['MEM_ID']?></td>
					<td><?php echo disp_phone($r['MEM_TELNO'])?></td>
					<td><?php echo disp_birth($r['BTHDAY'])?></td>
					<td><?php echo $sDef['MEM_GENDR'][$r['MEM_GENDR']]?></td>
					<td><?php echo $sDef['TCHR_POSN'][$r['TCHR_POSN']]?></td>
					<td><?php echo $sDef['CTRCT_TYPE'][$r['CTRCT_TYPE']]?></td>
					
					<td><?php echo $r['CRE_DATETM']?></td>
					<td>
						<?php if ($r['USE_YN'] == "Y") : ?>
						<button type="button" class="btn btn-info btn-xs" onclick="annu_set('<?php echo $r['MEM_SNO']?>');">연차설정</button>
						<button type="button" class="btn btn-warning btn-xs" onclick="tinfo_modify('<?php echo $r['MEM_SNO']?>');">정보수정</button>
						<button type="button" class="btn btn-danger btn-xs" onclick="sece_confirm('<?php echo $r['MEM_ID']?>');">퇴사처리</button>
						<?php else: ?>
						퇴사일 ( <?php echo $r['END_DATETM']?> )
						<?php endif;?>
					</td>
				</tr>
				<?php 
				$search_val['listCount']--;
				endforeach; ?>
			</tbody>
		</table>
		<!-- TABLE [END] -->


		<div class="card-footer clearfix">
			<!-- BUTTON [START] -->
			<ul class="pagination pagination-sm m-0 float-right">
				<li class="ac-btn"><a href="#modal_tchr_insert_form" class="btn btn-success btn-sm" data-bs-toggle="modal">등록하기</a></li>
			</ul>

			
			<!-- BUTTON [END] -->
			<!-- PAGZING [START] -->
			<?=$pager?>
			<!-- PAGZING [END] -->
		</div>

	</div>		
</div>
			
			
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_tchr_insert_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">강사 등록하기</h4>
                <button type="button" class="close2"  data-bs-dismiss="modal" aria-label="Close">
                <i class="fas fa-times" style="font-size:18px;"></i>
                </button>
				
            </div>
            <div class="modal-body">
            
            	
            	<!-- FORM [START] -->
            	<form id="tchr_insert_form">
            	<input type="hidden" name="id_chk" id="id_chk" value="N" />
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 아이디<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 아이디" name="mem_id" id="mem_id" autocomplete='off' onkeyup="id_change(this);" >
                	<span class="input-group-append">
						<button type="button" class="basic_bt06" id="btn_code_chk">중복체크</button>
					</span>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 비밀번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="password" class="form-control" placeholder="강사 비밀번호" name="mem_pwd" id="mem_pwd" autocomplete='new-password'>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 이름<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 이름" name="mem_nm" id="mem_nm" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 생년월일<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 생년월일" name="bthday" id='bthday' data-inputmask="'mask': ['9999/99/99']" data-mask autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 성별</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" id="radioGrpCate1" name="mem_gendr" value="M" checked>
                            <label for="radioGrpCate1">
                            	<small>남</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="radioGrpCate2" name="mem_gendr" value="F">
                            <label for="radioGrpCate2">
                            	<small>여</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="강사 전화번호" name="mem_telno" id="mem_telno"  autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 주소</span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 주소" name="mem_addr" id="mem_addr" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 직책<span class="text-danger">*</span></span>
                	</span>
                	<select class="form-select" style="width: 150px;" name="tchr_posn" id="tchr_posn">
                		<option value="">직책 선택</option>
                	<?php foreach ($sDef['TCHR_POSN'] as $r => $v) : ?>
						<option value="<?php echo $r?>"><?php echo $v?></option>
					<?php endforeach; ?>
					</select>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>계약 형태<span class="text-danger">*</span></span>
                	</span>
                	<select class="form-select" style="width: 150px;" name="ctrct_type" id="ctrct_type">
                		<option value="">계약 선택</option>
                	<?php foreach ($sDef['CTRCT_TYPE'] as $r => $v) : ?>
						<option value="<?php echo $r?>"><?php echo $v?></option>
					<?php endforeach; ?>
					</select>
                </div>
                
                <div id="id_chk_true" style="display:none">
                	<button type="button" class="btn btn-info btn-xs btn-block" >중복체크가 완료 되었습니다.</button>
                </div>
                <div id="id_chk_false">
                	<button type="button" class="btn btn-danger btn-xs btn-block" >중복체크를 해주세요</button>
                </div>
            	
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="tchr_insert_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->


<div class="modal fade" id="modal_tchr_modify_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">강사 수정하기</h4>
                <button type="button"class="btn-close" data-bs-dismiss="modal"  aria-hidden="true">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	<!-- FORM [START] -->
            	<form id="tchr_modify_form">
            	<input type="hidden" name="mem_sno" id="modify_mem_sno" />
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 아이디</span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 아이디" name="mem_id" id="modify_mem_id" readonly >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 비밀번호</span>
                	</span>
                	<input type="password" class="form-control" placeholder="비밀번호 수정시에만 입력하세요" name="mem_pwd" id="modify_mem_pwd" autocomplete='new-password'>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 이름<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 이름" name="mem_nm" id="modify_mem_nm" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 생년월일<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 생년월일" name="bthday" id='modify_bthday' data-inputmask="'mask': ['9999/99/99']" data-mask autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 성별</span>
                	</span>
                	
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" id="modify_radioGrpCate1" name="mem_gendr" value="M" checked>
                            <label for="modify_radioGrpCate1">
                            	<small>남</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="modify_radioGrpCate2" name="mem_gendr" value="F">
                            <label for="modify_radioGrpCate2">
                            	<small>여</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control phone-input" placeholder="강사 전화번호" name="mem_telno" id="modify_mem_telno"  autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 주소</span>
                	</span>
                	<input type="text" class="form-control" placeholder="강사 주소" name="mem_addr" id="modify_mem_addr" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>강사 직책<span class="text-danger">*</span></span>
                	</span>
                	<select class="select2 form-control" style="width: 150px;" name="tchr_posn" id="modify_tchr_posn">
                		<option value="">직책 선택</option>
                	<?php foreach ($sDef['TCHR_POSN'] as $r => $v) : ?>
						<option value="<?php echo $r?>"><?php echo $v?></option>
					<?php endforeach; ?>
					</select>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>계약 형태<span class="text-danger">*</span></span>
                	</span>
                	<select class="select2 form-control" style="width: 150px;" name="ctrct_type" id="modify_ctrct_type">
                		<option value="">계약 선택</option>
                	<?php foreach ($sDef['CTRCT_TYPE'] as $r => $v) : ?>
						<option value="<?php echo $r?>"><?php echo $v?></option>
					<?php endforeach; ?>
					</select>
                </div>
                
            	
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="tchr_modify_btn">수정하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

	
<!-- ############################## MODAL [ END ] ###################################### -->
	
	
	
</section>


<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
	$(".phone-input").on("input", function(e) {
		let errorDiv = $(this).closest(".input-group").next(".error-message") || $("#telno-error");
		handlePhoneInput(this, e, errorDiv);
	}).on("focus", function() {
		if (!$(this).val()) {
			$(this).val(""); // 빈 값으로 시작
		}
    });

	$(".phone-input").on("keypress", function(e) {
		const key = String.fromCharCode(e.which);
		if (!/[0-9]/.test(key)) {
			e.preventDefault();
		}
	});
})

function sece_confirm(mem_id)
{
	ToastConfirm.fire({
        icon: "warning",
        title: "  확인 메세지",
        html: "<font color='#000000' >강사 퇴사처리를 진행하시겠습니까?</font><br /><font color='#ff0000'>OK 누르면 퇴사 절차 페이지로 이동합니다.</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		location.href="/tmemmain/tchr_sece/"+mem_id;
    	}
    });
}

function tinfo_modify(mem_sno)
{
	var params = "mem_sno="+mem_sno;
    jQuery.ajax({
        url: '/tmemmain/ajax_tchr_modify',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/tlogin';
        		return;
        	}
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				console.log(json_result['tinfo']);
				
				$('#modify_mem_sno').val( json_result['tinfo']['MEM_SNO'] );
				
				$('#modify_mem_id').val( json_result['tinfo']['MEM_ID'] );
				$('#modify_mem_nm').val( json_result['tinfo']['MEM_NM'] );
				$('#modify_bthday').val( json_result['tinfo']['BTHDAY'] );
				
				if ( json_result['tinfo']['MEM_GENDR'] == 'M' )
				{
					$('#modify_radioGrpCate1').prop('checked',true);
				} else if ( json_result['tinfo']['MEM_GENDR'] == 'F' ) 
				{
					$('#modify_radioGrpCate2').prop('checked',true);
				}
				
				$('#modify_mem_telno').val( formatPhoneNumber(json_result['tinfo']['MEM_TELNO'] ));
				$('#modify_mem_addr').val( json_result['tinfo']['MEM_ADDR'] );
				
				$('#modify_tchr_posn').val( json_result['tinfo']['TCHR_POSN'] ).trigger('change');
				$('#modify_ctrct_type').val( json_result['tinfo']['CTRCT_TYPE'] ).trigger('change');
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href='/tlogin';
		return;
    });
    
	$('#modal_tchr_modify_form').modal("show");
}

$('#tchr_modify_btn').click(function(){
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >강사를 수정하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#tchr_modify_form").serialize();
    	    jQuery.ajax({
    	        url: '/tmemmain/ajax_tchr_modify_proc',
    	        type: 'POST',
    	        data:params,
    	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
    	        dataType: 'text',
    	        success: function (result) {
    	        	if ( result.substr(0,8) == '<script>' )
                	{
                		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                		location.href='/tlogin';
                		return;
                	}
                	
    	            json_result = $.parseJSON(result);
    				if (json_result['result'] == 'true')
    				{
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
        		location.href='/tlogin';
        		return;
            });
    	}
    });
});

$('#mem_id').keyup(function(event){    
    if (!(event.keyCode >=37 && event.keyCode<=40)) 
    {    
        var inputVal = $(this).val();    
        $(this).val(inputVal.replace(/[^a-z0-9]/gi,''));   
    }  
});

// 디바운스 함수
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// 디바운스된 검색 함수 (500ms 지연)
const debouncedSearch = debounce(function() {
    btn_search();
}, 500);

// 검색어 입력 이벤트
$('#snm').on('keydown', function(event) {
    if (event.key === 'Enter' || event.keyCode === 13) {
        event.preventDefault();
        btn_search();
    }
});

// 타이핑 시 자동 검색 (디바운스 적용)
$('#snm').on('input', function() {
    // 검색어가 2자 이상이거나 비어있을 때 자동 검색
    if (this.value.length >= 2 || this.value.length === 0) {
        debouncedSearch();
    }
});

// 강사직책 선택 변경 시 자동 검색
$('#sposn').on('change', function() {
    btn_search();
});

// 계약형태 선택 변경 시 자동 검색
$('#sctrct').on('change', function() {
    btn_search();
});

// AJAX 요청 관리를 위한 변수
let currentSearchRequest = null;

function btn_search()
{
	// 이전 검색 요청이 있으면 취소
	if (currentSearchRequest && currentSearchRequest.readyState !== 4) {
		currentSearchRequest.abort();
	}
	
	// AJAX 검색 요청
	var formData = $('#form_search_tchr_manage').serialize();
	
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</td></tr>');
	
	currentSearchRequest = $.ajax({
		url: '/tmemmain/ajax_tchr_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 강사수 업데이트
				$('.panel-body').find('table').prev().html('총 강사수 : ' + response.totalCount + ' 명');
				
				// 페이저 업데이트
				$('.card-footer').last().html(response.pager);
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 검색어 하이라이트
				highlightSearchTerm();
			} else {
				alertToast('error', '검색 중 오류가 발생했습니다.');
			}
		},
		error: function(xhr) {
			// abort된 경우는 에러 메시지 표시하지 않음
			if (xhr.statusText !== 'abort') {
				alertToast('error', '검색 중 오류가 발생했습니다.');
				$('.panel-body table tbody').html('<tr><td colspan="10" class="text-center">검색 중 오류가 발생했습니다.</td></tr>');
			}
		},
		complete: function() {
			currentSearchRequest = null;
		}
	});
}

// 페이저 이벤트 바인딩 함수
function bindPagerEvents() {
	// 페이지 링크 클릭 이벤트
	$('.pagination a').off('click').on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		if (href && href != '#') {
			// URL에서 페이지 파라미터 추출
			var page = getParameterByName('page', href);
			if (page) {
				// 현재 폼 데이터에 페이지 추가
				var formData = $('#form_search_tchr_manage').serialize() + '&page=' + page;
				ajaxLoadPage(formData);
			}
		}
	});
}

// URL에서 파라미터 값 추출
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

// AJAX로 페이지 로드
function ajaxLoadPage(formData) {
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> 로딩중...</td></tr>');
	
	$.ajax({
		url: '/tmemmain/ajax_tchr_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 강사수 업데이트
				$('.panel-body').find('table').prev().html('총 강사수 : ' + response.totalCount + ' 명');
				
				// 페이저 업데이트
				$('.card-footer').last().html(response.pager);
				
				// 페이저 이벤트 재바인딩
				bindPagerEvents();
				
				// 스크롤을 테이블 상단으로 이동
				$('html, body').animate({
					scrollTop: $('.panel-body').offset().top - 100
				}, 300);
			} else {
				alertToast('error', '페이지 로딩 중 오류가 발생했습니다.');
			}
		},
		error: function() {
			alertToast('error', '페이지 로딩 중 오류가 발생했습니다.');
			$('.panel-body table tbody').html('<tr><td colspan="10" class="text-center">페이지 로딩 중 오류가 발생했습니다.</td></tr>');
		}
	});
}

// 검색어 하이라이트 함수
function highlightSearchTerm() {
	var searchTerm = $('#snm').val().trim();
	if (searchTerm.length > 0) {
		// 강사이름 컬럼에서 검색어 하이라이트 (2번째 컬럼)
		$('.panel-body table tbody tr').each(function() {
			var cell2 = $(this).find('td:eq(1)');
			highlightCell(cell2, searchTerm);
		});
	}
}

// 셀 내용 하이라이트
function highlightCell(cell, searchTerm) {
	// 텍스트 노드만 처리
	cell.contents().each(function() {
		if (this.nodeType === 3) { // 텍스트 노드인 경우
			var text = $(this).text();
			if (text && text.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
				// 텍스트에서 검색어 하이라이트
				var regex = new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi');
				var highlightedText = text.replace(regex, '<mark style="background-color: yellow;">$1</mark>');
				$(this).replaceWith(highlightedText);
			}
		} else if (this.nodeType === 1) { // 엘리먼트 노드인 경우
			// 자식 노드가 있으면 재귀적으로 처리 (img 태그는 제외)
			if (this.tagName.toLowerCase() !== 'img' && this.tagName.toLowerCase() !== 'mark') {
				highlightCell($(this), searchTerm);
			}
		}
	});
}

// 정규식 특수문자 이스케이프
function escapeRegExp(string) {
	return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// 초기 페이저 이벤트 바인딩
$(document).ready(function() {
	bindPagerEvents();
});

function annu_set(mem_sno)
{
	location.href="/tmemmain/tchr_annu_setting/"+mem_sno;
}


$('#tchr_insert_btn').click(function(){
	// 실패일 경우 warning error success info question
	
	if ( $('#id_chk').val() == "N" )
	{
		alertToast('error','아이디 중복체크를 해주세요');
		return;
	}
	
	if ( $('#mem_id').val() == "" )
	{
		alertToast('error','아이디를 입력하세요');
		return;
	}
	
	if ( $('#mem_pwd').val() == "" )
	{
		alertToast('error','비밀번호를 입력하세요');
		return;
	}
	
	if ( $('#mem_nm').val() == "" )
	{
		alertToast('error','이름을 입력하세요');
		return;
	}
	
	if ( $('#bthday').val() == "" )
	{
		alertToast('error','생년월일을 입력하세요');
		return;
	}
	
	if ( $('#mem_telno').val() == "" )
	{
		alertToast('error','전화번호를 입력하세요');
		return;
	}
	
	if ( $('#tchr_posn').val() == "" )
	{
		alertToast('error','직책을 선택하세요');
		return;
	}
	
	if ( $('#ctrct_type').val() == "" )
	{
		alertToast('error','계약형태를 선택하세요');
		return;
	}
	
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >강사를 등록하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#tchr_insert_form").serialize();
    	    jQuery.ajax({
    	        url: '/tmemmain/ajax_tchr_insert_proc',
    	        type: 'POST',
    	        data:params,
    	        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
    	        dataType: 'text',
    	        success: function (result) {
    	        	if ( result.substr(0,8) == '<script>' )
                	{
                		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                		location.href='/tlogin';
                		return;
                	}
                	
    	            json_result = $.parseJSON(result);
    				if (json_result['result'] == 'true')
    				{
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
        		location.href='/tlogin';
        		return;
            });
    	}
    });	
	
});

function id_change(t)
{
	$('#id_chk').val("N");
	$('#id_chk_false').show();
	$('#id_chk_true').hide();
}

$('#btn_code_chk').click(function(){

	if ( $('#mem_id').val() == "" )
	{
		alertToast('error','아이디를 입력하세요');
		return;
	}

	var params = "mem_id="+$('#mem_id').val();
    jQuery.ajax({
        url: '/tmemmain/ajax_id_chk',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if ( result.substr(0,8) == '<script>' )
        	{
        		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
        		location.href='/tlogin';
        		return;
        	}
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				$('#id_chk').val("Y");
            	$('#id_chk_false').hide();
            	$('#id_chk_true').show();			
			} else 
			{
				alertToast('error','아이디가 중복되었습니다. 다른 아이디를 입력하세요');
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href='/tlogin';
		return;
    });
	
});

function showFullPhoto(imageSrc) {
    if (!imageSrc || imageSrc === '') {
        alertToast('info', '원본 사진이 없습니다.');
        return;
    }
    
    // 모달에서 이미지 보여주기
    Swal.fire({
        imageUrl: imageSrc,
        imageAlt: '강사 사진',
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto'
    });
}

</script>