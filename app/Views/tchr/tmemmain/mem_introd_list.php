<style>
	.preview_mem_photo {
		width: 30px;
		height: 30px;
		object-fit: cover;
		border-radius: 50%;
		border: 1px solid #ccc;
	}
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->




<div class="panel panel-inverse">
        <div class="card-header py-3">
            <h5 class="m-0 text-white">추천회원 검색</h5>
        </div>
<form name="form_search_mem_manage" id="form_search_mem_manage" method="post" onsubmit="return false;">

   <div class="bbs_search_box_a3 mb10 mt10">
		<ul>
		  <li>ㆍ 검색어  </li>
          <li style="width: 300px;">
			<input type="text" class="form-control"  name="snm" id="snm" value="<?php echo $search_val['snm']?>" placeholder="추천인 또는 피추천인 검색 (Enter 또는 자동 검색)" style="width: 100%;">
		  </li>
		 </ul>
	</div>	
	
</form>
</div>








<!-- <div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">추천 회원 검색</h4>
	</div>
	<div class="card-footer clearfix">
		<form name="form_search_mem_manage" id="form_search_mem_manage" method="post" action="/tmemmain/mem_introd_list">
			<div class="input-group input-group-sm mb-1">
				
				<span class="input-group-append">
					<span class="input-group-text" style='width:120px;margin-left:5px;'>검색어</span>
				</span>
				<input type="text" class="ss-input" name="snm" id="snm" style='width:150px; margin-left:5px;' value="<?php echo $search_val['snm']?>" >
				
				
				
				<button type="button" class="btn btn-sm btn-success" style='margin-left:5px;' onclick="btn_search();">검색</button>
				
			</div>
		
		</form>
	</div>
</div> -->

	
	<!-- CARD BODY [START] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">회원 리스트</h4>
		<div class="panel-heading-btn">
		</div>
	</div>
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		총 회원수 : <?php echo $totalCount?> 명
		<table class="table table-bordered table-hover table-striped col-md-12 mt20">
			<thead>
				<tr class='text-center'>
					<th style='width:60px'>번호</th>
					<th style='width:100px'>추천한 회원</th>
					<th style='width:130px'>추천한 아이디</th>
					<th style='width:160px'>정보보기</th>
					<th style='width:100px'>추천받은 회원</th>
					<th style='width:130px'>추천받은 아이디</th>
					<th style='width:160px'>정보보기</th>
					<th style='width:160px'>추천일</th>
					<th style='width:200px'>추천일시</th>
					<th>옵션</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$listCount = $search_val['listCount'];
				foreach($list_introd as $r) :
					$backColor = "";
				?>
				<tr style="background-color: <?php echo $backColor ?>;">
					<td class='text-center'><?php echo $listCount?></td>
					<td>
						<img class="preview_mem_photo"
							id="preview_mem_photo"
							src="<?php echo $r['S_MEM_THUMB_IMG'] ?>"
							alt="회원사진"
							style="border-radius: 50%; cursor: pointer;"
							onclick="showFullPhoto('<?php echo $r['S_MEM_MAIN_IMG'] ?>')"
							onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo $r['S_MEM_GENDR'] ?>.png';">
						<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info('<?php echo $r['S_MEM_SNO']; ?>');">
							<?php echo $r['S_MEM_NM']; ?>
						</a>
					</td>
					<td><?php echo $r['S_MEM_ID']?></td>
					<td>
						<button type="button" class="btn btn-warning btn-xs" onclick="mem_manage_mem_info('<?php echo $r['S_MEM_SNO']?>');">추천한 회원 정보보기</button>
					</td>
					<td>
						<img class="preview_mem_photo"
							id="preview_mem_photo"
							src="<?php echo $r['T_MEM_THUMB_IMG'] ?>"
							alt="회원사진"
							style="border-radius: 50%; cursor: pointer;"
							onclick="showFullPhoto('<?php echo $r['T_MEM_MAIN_IMG'] ?>')"
							onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo $r['T_MEM_GENDR'] ?>.png';">
						<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info('<?php echo $r['T_MEM_SNO']; ?>');">
							<?php echo $r['T_MEM_NM']; ?>
						</a>
					</td>
					<td><?php echo $r['T_MEM_ID']?></td>
					<td>
						<button type="button" class="btn btn-warning btn-xs" onclick="mem_manage_mem_info('<?php echo $r['T_MEM_SNO']?>');">추천받은 회원 정보보기</button>
					</td>
					<td><?php echo substr($r['CRE_DATETM'],0,10)?></td>
					<td><?php echo $r['CRE_DATETM']?></td>
					<td>
						
					</td>
				</tr>
				<?php
				$listCount--;
				endforeach; 
				?>
			</tbody>
		</table>
		<!-- TABLE [END] -->
		<?php echo $pager ?><br/>
	<!-- CARD BODY [END] -->
	<!-- CARD FOOTER [START] -->
	<!-- CARD FOOTER [END] -->
			
</div>
	<!-- CARD BODY [END] -->

</div>
<br/>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->

<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_mem_insert_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">회원 등록하기</h4>
                <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            	
            	<!-- FORM [START] -->
            	<form id="mem_insert_form">
            	<input type="hidden" name="id_chk" id="id_chk" value="N" />
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회원 아이디<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="회원 아이디 (최대20자리)" name="mem_id" id="mem_id" maxlength='12' autocomplete='off' onkeyup="id_change(this);">
                	<span class="input-group-append">
                    	<button type="button" class="btn btn-info btn-flat btn-sm" id="btn_code_chk" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">중복체크</button>
                    </span>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회원 비밀번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="password" class="form-control" placeholder="회원 비밀번호" name="mem_pwd" id="mem_pwd" autocomplete="new-password">
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회원 이름<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="회원 이름" name="mem_nm" id="mem_nm" autocomplete='off' >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회원 생년월일</span>
                	</span>
                	<input type="text" class="form-control" placeholder="회원 생년월일" name="bthday" id="bthday" autocomplete='off' data-inputmask="'mask': ['9999/99/99']" data-mask >
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회원 성별</span>
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
                		<span class="input-group-text" style='width:150px'>회원 전화번호<span class="text-danger">*</span></span>
                	</span>
                	<input type="text" class="form-control" placeholder="회원 전화번호" name="mem_telno" id="mem_telno" autocomplete='off' data-inputmask="'mask': ['99-9999-999[9]','999-9999-9999']" data-mask>
                </div>
                
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:150px'>회원 주소</span>
                	</span>
                	<input type="text" class="form-control" placeholder="회원 주소" name="mem_addr" autocomplete='off' >
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
                <button type="button" class="btn btn-success btn-sm" id="mem_insert_btn">등록하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
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
    
    if ( $('#sdcon').val() != '')
    {
    	$('#sdate').prop('disabled',false);
		$('#edate').prop('disabled',false);
    }
})

$('#sdate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

$('#edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});

// 회원별 통합정보 보기
function mem_manage_mem_info(mem_sno)
{
	location.href="/ttotalmain/info_mem/"+mem_sno;
}



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

// AJAX 요청 관리를 위한 변수
let currentSearchRequest = null;

$('#mem_insert_btn').click(function(){
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
	
	
	
	if ( $('#mem_telno').val() == "" )
	{
		alertToast('error','전화번호를 입력하세요');
		return;
	}
	
	ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000' >회원을 등록하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
    	if (result.isConfirmed) 
    	{
    		var params = $("#mem_insert_form").serialize();
    	    jQuery.ajax({
    	        url: '/tmemmain/ajax_mem_insert_proc',
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

$('#mem_id').keyup(function(event){    
    if (!(event.keyCode >=37 && event.keyCode<=40)) 
    {    
        var inputVal = $(this).val();    
        $(this).val(inputVal.replace(/[^a-z0-9]/gi,''));   
    }  
});


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
function btn_search()
{
	// 이전 검색 요청이 있으면 취소
	if (currentSearchRequest && currentSearchRequest.readyState !== 4) {
		currentSearchRequest.abort();
	}
	
	// AJAX 검색 요청
	var formData = $('#form_search_mem_manage').serialize();
	
	// 로딩 표시
	$('.panel-body table tbody').html('<tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> 검색중...</td></tr>');
	
	currentSearchRequest = $.ajax({
		url: '/tmemmain/ajax_mem_introd_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 회원수 업데이트
				$('.panel-body').find('table').prev().html('총 회원수 : ' + response.totalCount + ' 명');
				
				// 페이저 업데이트
				$('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
				
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
				var formData = $('#form_search_mem_manage').serialize() + '&page=' + page;
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
		url: '/tmemmain/ajax_mem_introd_search',
		type: 'POST',
		data: formData,
		dataType: 'json',
		success: function(response) {
			if (response.result == 'true') {
				// 테이블 내용 업데이트
				$('.panel-body table tbody').html(response.html);
				
				// 전체 회원수 업데이트
				$('.panel-body').find('table').prev().html('총 회원수 : ' + response.totalCount + ' 명');
				
				// 페이저 업데이트
				$('ul.pagination.pagination-sm.m-0.float-left').html($(response.pager).html());
				
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
		// 추천인/피추천인 이름과 아이디 컬럼에서 검색어 하이라이트
		$('.panel-body table tbody tr').each(function() {
			// 추천한 회원 이름 (2번째 컬럼)
			var cell2 = $(this).find('td:eq(1)');
			highlightCell(cell2, searchTerm);
			
			// 추천한 아이디 (3번째 컬럼)
			var cell3 = $(this).find('td:eq(2)');
			highlightCell(cell3, searchTerm);
			
			// 추천받은 회원 이름 (5번째 컬럼)
			var cell5 = $(this).find('td:eq(4)');
			highlightCell(cell5, searchTerm);
			
			// 추천받은 아이디 (6번째 컬럼)
			var cell6 = $(this).find('td:eq(5)');
			highlightCell(cell6, searchTerm);
		});
	}
}

// 셀 내용 하이라이트
function highlightCell(cell, searchTerm) {
	var cellHtml = cell.html();
	if (cellHtml && cellHtml.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1) {
		var highlightedHtml = cellHtml.replace(new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi'), '<mark style="background-color: yellow;">$1</mark>');
		cell.html(highlightedHtml);
	}
}

// 정규식 특수문자 이스케이프
function escapeRegExp(string) {
	return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// 초기 페이저 이벤트 바인딩
$(document).ready(function() {
	bindPagerEvents();
});
</script>