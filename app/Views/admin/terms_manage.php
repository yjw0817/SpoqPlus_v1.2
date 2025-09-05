<style>
	.strike-through {
		text-decoration: line-through;
		color: gray;
	}
	
	/* SweetAlert2 버튼 스타일 통일 */
	.swal2-confirm, .swal2-cancel {
		min-width: 80px !important;
		height: 32px !important;
		padding: 5px 12px !important;
		font-size: 12px !important;
		line-height: 1.2 !important;
		border-radius: 3px !important;
		margin: 0 5px !important;
	}
</style>
<?php
$sDef = SpoqDef();
?>
<!-- CARD HEADER [START] -->
<h1 class="page-header"><?php echo $title ?></h1>
<!-- CARD HEADER [END] -->
<!-- CARD HEADER [END] -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<h4 class="panel-title">약관 리스트</h4>
	</div>
	<!-- CARD HEADER [END] -->
	
	<!-- CARD BODY [START] -->
	<div class="panel-body table-responsive">
		<!-- TABLE [START] -->
		<table class="table table-bordered table-hover col-md-12">
			<thead>
				<tr style='text-align:center'>
					<!-- <th style='width:70px'>번호</th> -->
					<th style='width:70px'>코드</th>
					<th style='width:250px'>약관제목</th>
					
					<th style='width:70px'>회차</th>
					<th style='width:70px'>사용여부</th>
					
					<th style='width:140px'>약관 개정일</th>
					<th style='width:140px'>생성 아이디</th>
					<th style='width:200px'>생성 일자</th>
					<th>옵션</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$list_count = count($terms_list);
				foreach ($terms_list as $r) : 
					$strike_class = ($r['TERMS_USE_YN'] == 'N') ? 'strike-through' : '';
			?>
				<tr class="text-center">
					<td class="<?php echo $strike_class ?>"><?php echo $r['TERMS_KND_CD']?></td>
					<td class="<?php echo $strike_class ?>"><?php echo $r['TERMS_TITLE']?></td>
					<td class="<?php echo $strike_class ?>"><?php echo $r['TERMS_ROUND']?></td>
					<td class="<?php echo $strike_class ?>"><?php echo $r['TERMS_USE_YN']?></td>
					<td class="<?php echo $strike_class ?>"><?php echo $r['TERMS_DATE']?></td>
					<td class="<?php echo $strike_class ?>"><?php echo $r['CRE_ID']?></td>
					<td class="<?php echo $strike_class ?>"><?php echo $r['CRE_DATETM']?></td>
					<td class='text-left' style="width:50px">
						<button type="button" class="btn btn-info btn-xs" onclick="term_add_round('<?php echo $r['TERMS_KND_CD']?>', '<?php echo $r['TERMS_USE_YN']?>');">약관개정</button>
					</td>
				</tr>
			<?php
				$list_count--;
				endforeach;
			?>
			</tbody>
		</table>
		<!-- TABLE [END] -->
		 <!-- CARD FOOTER [START] -->
	<div class="card-footer clearfix">
		<!-- BUTTON [START] -->
		<ul class="pagination pagination-sm m-0 float-right">
			<li class="ac-btn">
				<button type="button" class="btn btn-block btn-success btn-sm" onclick="location.href='/adminmain/terms_insert_form';">기본약관생성</button>
			</li>
		</ul>
		
		<!-- BUTTON [END] -->
		<!-- PAGZING [START] -->
		<!-- PAGZING [END] -->
	</div>
	<!-- CARD FOOTER [END] -->
	</div>
	<!-- CARD BODY [END] -->
	
			
</div>
			

<!-- ============================= [ modal-default START ] ======================================= -->	

<!-- ============================= [ modal-default END ] ======================================= -->	
	
</section>

<?=$jsinc ?>

<script>

$(function () {
    $('.select2').select2();
})

// 약관 개정하기 회차추가
function term_add_round(terms_knd_cd, terms_use_yn)
{
	if(terms_use_yn == 'N') {
		Swal.fire({
			title: '약관 개정 확인',
			text: '최신 약관이 아닙니다. 이 약관으로 개정 하시겠습니까?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: '개정하기',
			cancelButtonText: '취소',
			confirmButtonColor: '#f0ad4e',
			cancelButtonColor: '#6c757d'
		}).then((result) => {
			if (result.isConfirmed) {
				location.href="/adminmain/terms_add_round_form/"+terms_knd_cd;
			}
		});
	} else {
		location.href="/adminmain/terms_add_round_form/"+terms_knd_cd;
	}
}

// 약관 수정하기
function term_modify(terms_knd_cd,terms_round)
{
	location.href="/adminmain/terms_modify_form/"+terms_knd_cd+"/"+terms_round;
}

</script>