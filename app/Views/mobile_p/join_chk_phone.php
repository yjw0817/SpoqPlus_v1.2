<style>
    #hamb_menu { display:none !important; }
</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
		<div class="row">
			<div class="col-md-12">
                
                <div class="row">
        			<div class="col-md-12">
        				<div class="card-body">
        					<form name="form_join_chk_phone" id="form_join_chk_phone" method="post" action="/api/join_chk_phone_cert">
        					<div class="card card-success">
                                <div class="card-body">
                                    <input class="form-control form-control-lg" type="text" name="mem_nm" id="mem_nm" placeholder="이름을 입력하세요" autocomplete='off'>
                                    <br>
                                    <input class="form-control form-control-lg" type="text" name="mem_phone" id="mem_phone" placeholder="휴대폰번호를 입력하세요" data-inputmask="'mask': ['99-9999-999[9]','999-9999-9999']" data-mask  autocomplete='off'>
                                    <br>
                                    <input class="form-control form-control-lg" type="text" name="mem_birth" id="mem_birth" placeholder="생년월일을 입력하세요"  data-inputmask="'mask': ['9999/99/99']" data-mask  autocomplete='off'>
                                    <br>
                                    
                                    <div class="form-group-lg">
                                        <label for="inputName">회원 성별</label>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radioGrpCate1" name="mem_gendr" value="M"  >
                                            <label for="radioGrpCate1">
                                            	<large>남</large>
                                            </label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="radioGrpCate2" name="mem_gendr" value="F"  >
                                            <label for="radioGrpCate2">
                                            	<large>여</large>
                                            </label>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            </form>
                            <div class='text-center'>
                                <button type="button" class="btn btn-sm btn-success col-12" style='height:40px;' onclick="btn_next();">다음 단계로</button>
                            </div>
                        </div>
        			</div>
        		</div>
                			
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
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
})

function btn_next()
{
	if ($('#mem_nm').val() == '')
	{
		alertToast('error','이름을 입력하세요');
		return;
	}
	
	if ($('#mem_phone').val() == '')
	{
		alertToast('error','휴대폰번호를 입력하세요');
		return;
	}
	
	if ($('#mem_birth').val() == '')
	{
		alertToast('error','생년월일을 입력하세요');
		return;
	}
	
	if ( $("input:radio[name=mem_gendr]:checked").length == 0 )
	{
		alertToast('error','성별을 선택하세요.');
		return;
	}
	
	$('#form_join_chk_phone').submit();
}

// ===================== Modal Script [ START ] ===========================

// ===================== Modal Script [ END ] =============================

</script>