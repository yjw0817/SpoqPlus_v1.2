<style>
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card card-primary">
					<div class="page-header">
						<h3 class="panel-title">디비 일괄업로드</h3>
					</div>
					<div class="panel-body">

						<form name="dblistUploadForm" id="dblistUploadForm" method="post"
							enctype="multipart/form-data"
							action="/dbmanage/mDblistUploadProc">
							
							<div>
								<input type="file" name="userfile" />
							</div>
							<div>
								<a class='btn btn-success btn-sm' onclick="excel_upload();">엑셀 업로드</a>
							</div>
								
						</form>
					</div>
				</div>

			</div>
		</div>

	</div>
	
</section>

<?=$jsinc ?>

<script>
	function excel_upload()
	{
		var f = document.dblistUploadForm;
		if ( f.userfile.value != '' )
		{
			f.submit();
		} else 
		{
			alert('파일을 선택하세요');

		}
		
	}
</script>