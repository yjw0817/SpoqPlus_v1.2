<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->

<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">중분류 검색</h4>
	</div>
	<div class="clearfix mt10 mb10">
		<form name="form_search_tchr_manage" id="form_search_tchr_manage" method="post" action="/smgrmain/two_cate_list">
			
			<div class="bbs_search_box_a4 mt10 mb10">
		<ul>
			<li>ㆍ 대분류  </li>
			<li>
                <select class="form-control" name="1rd_cate_cd" id="1rd_cate_cd" onChange="btn_search();">
					<option>전체</option>
					
					<?php foreach($cate1 as $c1) :?>
						<?php 
							$selected =  "";
							$selected = (isset($search_val['1rd_cate_cd']) && $search_val['1rd_cate_cd'] == $c1['1RD_CATE_CD']) ? 'selected' : '';
						?>
						<option value="<?php echo $c1['1RD_CATE_CD']?>" <?php echo $selected; ?>><?php echo $c1['CATE_NM']?></option>
					<?php endforeach;?>
				</select>
            </li>
			<li>
				<?php 
						$search_txt = (isset($search_val['search_txt'])  ? $search_val['search_txt'] : "");
					?>
				<input type="text"  class="form-control" name="search_txt" id="search_txt" placeholder="검색어를 입력하세요"  value = "<?php echo $search_txt?>">
				
			</li>
			<li>
				<a href="#"  onclick="btn_search();" class="serch_bt" type="button"><i class="fas fa-search"></i> 검색</a>
			</li>
		</ul>
	</div>

		
		<!-- <div class="input-group input-group-sm mb-1">
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px'>대분류</span>
				</span>
				
				<select  style="width: 150px;" name="1rd_cate_cd" id="1rd_cate_cd" onChange="btn_search();">
					<option>전체</option>
					
					<?php foreach($cate1 as $c1) :?>
						<?php 
							$selected =  "";
							$selected = (isset($search_val['1rd_cate_cd']) && $search_val['1rd_cate_cd'] == $c1['1RD_CATE_CD']) ? 'selected' : '';
						?>
						<option value="<?php echo $c1['1RD_CATE_CD']?>" <?php echo $selected; ?>><?php echo $c1['CATE_NM']?></option>
					<?php endforeach;?>
				</select>
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px'>검색어</span>
				</span>
				<?php 
						$search_txt = (isset($search_val['search_txt'])  ? $search_val['search_txt'] : "");
					?>
				<input type="text" class="" name="search_txt" id="search_txt" style='width:100px'  value = "<?php echo $search_txt?>">
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();" >검색</button>
				
			</div> -->

		</form>
	</div>



<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h3 class="panel-title">중분류 리스트</h3>
	</div>
	
	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<table class="table table-bordered table-hover table-striped col-md-12">
			<thead>
				<tr style='text-align:center'>
					<th style='width:70px'>번호</th>
					<th style='width:160px'>대분류 코드</th>
					<th style='width:160px'>대분류명</th>
					
					<th style='width:160px'>중분류 코드</th>
					<th style='width:160px'>중분류 명</th>
					
					<th style='width:100px'>그룹적용 설정</th>
					<th style='width:100px'>락커설정</th>
					<th style='width:100px'>수업구분</th>
					<th style='width:150px'>등록일시</th>
					<th>옵션</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($two_cate_main_list as $r): ?>
				<tr>
					<td class='text-center'><?php echo $search_val['listCount']?></td>
					<td class='text-center'><?php echo $r['1RD_CATE_CD'] ?></td>
					<td class='text-center'><?php echo $code_1rd_name[$r['1RD_CATE_CD']]?></td>
					<td class='text-center'><?php echo $r['2RD_CATE_CD'] ?></td>
					<td class='text-center'><?php echo $r['CATE_NM'] ?></td>
					<td class='text-center'><?php echo $sDef['GRP_CATE_SET'][$r['GRP_CATE_SET']] ?></td>
					<td class='text-center'><?php echo $sDef['LOCKR_KND'][$r['LOCKR_KND']] ?></td>
					<td class='text-center'><?php echo $sDef['CLAS_DV'][$r['CLAS_DV']] ?></td>
					<td class='text-center'><?php echo $r['CRE_DATETM'] ?></td>
					<td>-</td>
				</tr>
				<?php
				$search_val['listCount']--;
				endforeach; ?>
			</tbody>
		</table>
		<!-- TABLE [END] -->
	</div>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<div class="card-footer clearfix">
		<!-- BUTTON [START] -->
		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn">
				<a href="#modal_one_cate_form" class="btn btn-success btn-sm" data-bs-toggle="modal">등록하기</a>
			</li>
		</ul>
		
		<!-- BUTTON [END] -->
		<!-- PAGZING [START] -->
		<?=$pager?>
		<!-- PAGZING [END] -->
	</div>
	<!-- CARD FOOTER [END] -->
</div>

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_one_cate_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">중분류 등록하기</h4>
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	<!-- FORM [START] -->
            	<form id="two_cate_form" method="post" action="/smgrmain/ajax_two_cate_proc">
            	<input type="hidden" name="code_chk" id="code_chk" value="N" />
            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>대분류 코드</span>
                	</span>
                	<select class="form-select" style="width: 150px;" name="1rd_cate_cd" id="1rd_cate_cd" onChange="get_cate2_code(this);">
                		<option>대분류 선택</option>
                	<?php foreach ($list_1rd_cate as $r) : ?>
						<option value="<?php echo $r['1RD_CATE_CD']?>" data-lockr="<?php echo $r['LOCKR_SET']?>">[<?php echo $r['1RD_CATE_CD']?>] <?php echo $r['CATE_NM']?> </option>
					<?php endforeach; ?>
					</select>
                </div>
            	
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>중분류 코드</span>
                	</span>
                	<input type="text" class="form-control" placeholder="중분류 코드 8 ~ 14자리" name="2rd_cate_cd" id="2rd_cate_cd" autocomplete='off' maxlength="14">
                	<span class="input-group-append">
                    	<button type="button" class="btn btn-info btn-flat" id="btn_code_chk">중복체크</button>
                    </span>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>중분류 명</span>
                	</span>
                	<input type="text" class="form-control" placeholder="표시할 중분류을 입력하세요" name="cate_nm" id="cate_nm" autocomplete='off'>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>수업 구분</span>
                	</span>
                	<select class="form-select" style="width: 150px;" name="clas_dv" id="clas_dv">
                		<option>수업구분 선택</option>
                		<?php foreach ($sDef["CLAS_DV"] as $r => $v) :?>
                		<option value="<?php echo $r;?>"><?php echo $v;?></option>
                		<?php endforeach;?>
					</select>
                </div>
                
                <!-- 라커 설정 [START] -->
                <div class="input-group input-group-sm mb-1 disp-lockr"  style='display:none'>
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>라커종류 선택</span>
                	</span>
                	<div style='margin-top:4px;margin-left:5px;'>
                    	<div class="icheck-primary d-inline">
                            <input type="radio" name="lockr_knd" id="lockr_knd_n" value="01" checked>
                            <label for="lockr_knd_n">
                            	<small>락커</small>
                            </label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="radio" name="lockr_knd" id="lockr_knd_g" value="02">
                            <label for="lockr_knd_g">
                            	<small>골프라커</small>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- 라커 설정 [START] -->
                
                <div id="code_chk_true" style="display:none">
                	<button type="button" class="btn btn-info btn-xs btn-block" >중복체크가 완료 되었습니다.</button>
                </div>
                <div id="code_chk_false">
                	<button type="button" class="btn btn-danger btn-xs btn-block" >중복체크를 해주세요</button>
                </div>
                
            	
            	
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="two_cate_form_create_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->	
	
</section>


<?=$jsinc ?>

<script>

	$(function () {
		$('.select2').select2();
	})


	function btn_search() {
		const m1 = getUrlParam('m1');
		const m2 = getUrlParam('m2');
		
		if (m1 && m2) {
			const actionUrl = `/smgrmain/two_cate_list?m1=${m1}&m2=${m2}`;
			$('#form_search_tchr_manage').attr('action', actionUrl);
		}
		
		$('#form_search_tchr_manage').submit();
	}

	// URL에서 m1, m2 값을 가져오는 함수
	function getUrlParam(paramName) {
		const urlParams = new URLSearchParams(window.location.search);
		return urlParams.get(paramName);
	}

	$("#1rd_cate_cd").on("change", function () {
		var lockr_set = $(this).find("option:selected").data("lockr");
		
		if (lockr_set == "Y")
		{
			$('.disp-lockr').show();	
		} else 
		{
			$('.disp-lockr').hide();
		}
	});


	// 대분류 코드 4자리 숫자 입력 체크
	$('#2rd_cate_cd').keyup(function(e){
		$('#code_chk').val("N");
		$('#code_chk_false').show();
		$('#code_chk_true').hide();
	});


	$('#two_cate_form_create_btn').click(function(){
		// 실패일 경우 warning error success info question
		//alertToast('error','대분류 코드를 입력하세요');
		
		if ( $('#code_chk').val() == "N" )
		{
			alertToast('error','중분류 코드 중복체크를 해주세요.');
			return;
		}
		
		if ( $('#1rd_cate_cd').val() == "" )
		{
			alertToast('error','대분류 코드를 선택하세요');
			return;
		}
		
		if ( $('#2rd_cate_cd').val() == "" )
		{
			alertToast('error','중분류 코드를 입력하세요');
			return;
		}
		
		if ( $('#2rd_cate_cd').val().length < 8 )
		{
			alertToast('error','중분류 코드는 8자리 이상이어야 합니다.');
			return;
		}
		
		if ( $('#cate_nm').val() == "" )
		{
			alertToast('error','중분류을 입력하세요');
			return;
		}
		
		if ( $('#clas_dv').val() == "" )
		{
			alertToast('error','수업 구분을 선택하세요');
			return;
		}
		
		
		ToastConfirm.fire({
			icon: "question",
			title: "  확인 메세지",
			html: "<font color='#000000' >생성하시겠습니까?</font>",
			showConfirmButton: true,
			showCancelButton: true,
			confirmButtonColor: "#28a745",
		}).then((result) => {
			if (result.isConfirmed) 
			{
				$('#two_cate_form').submit();
				// 성공일 경우	
				//$("#modal_two_cate_form").modal('hide');
				//alertToast('success','중분류가 생성 되었습니다.');
			}
		});	
		
	});

	$('#btn_code_chk').click(function(){

		if ( $('#2rd_cate_cd').val() == "" )
		{
			alertToast('error','대분류 코드를 입력하세요');
			return;
		}
		
		if ( $('#2rd_cate_cd').val().length < 8 )
		{
			alertToast('error',"중분류 코드는 8자리가 되어야 합니다.");
			$('#1rd_cate_cd').focus();
			return;
		}

		var params = "cate_cd="+$('#2rd_cate_cd').val();
		jQuery.ajax({
			url: '/smgrmain/ajax_tow_code_chk',
			type: 'POST',
			data:params,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
			dataType: 'text',
			success: function (result) {
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true')
				{
					$('#code_chk').val("Y");
					$('#code_chk_false').hide();
					$('#code_chk_true').show();			
				} else 
				{
					alertToast('error','코드가 중복되었습니다. 다른 코드를 입력하세요');
				}
			}
		});
	});

	// 대분류 선택시 -> 중분류 코드 자동생성
	function get_cate2_code(arg)
	{
		var cate1 = $(arg).val();
		
		$('#code_chk_false').show();
		$('#code_chk_true').hide();		
		if(cate1 != "대분류 선택")
		{
			var params = "cate1="+cate1;
			jQuery.ajax({
				url: '/smgrmain/ajax_cate2_code',
				type: 'POST',
				data:params,
				contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
				dataType: 'text',
				success: function (result) {
					json_result = $.parseJSON(result);
					if (json_result['result'] == 'true')
					{
						$('#2rd_cate_cd').val(json_result['cate2_code']);
					} else
					{
						alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
						location.href='/tlogin';
						return;
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
		} else
		{
			$('#2rd_cate_cd').val("");
		}
	}

</script>