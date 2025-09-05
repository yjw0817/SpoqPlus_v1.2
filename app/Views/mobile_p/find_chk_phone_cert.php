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
        					<form name="form_find_chk_phone_proc" id="form_find_chk_phone_proc" method="post" action="/api/find_chk_phone_proc">
        					<div class="card card-success">
                                <div class="card-body">
                                    <input class="form-control form-control-lg" type="text" name="phone_cert" id="phone_cert" placeholder="인증번호를 입력하세요">
                                    <br />
                                    (임시 인증번호 : <?php echo $send_rnd?>)
                                    <div class='float-right'><span class="time"></span></div>
                                </div>
                            </div>
                            </form>
                            
                            <div class='text-center' style='margin-bottom:10px;display:none;' id='re_cert'>
                                <button type="button" class="btn btn-sm btn-info col-12" style='height:40px;' id='btn_re_cert'>인증번호 재발송</button>
                            </div>
                            
                            <div class='text-center' id='btn_cc'>
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
<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

	
</section>

<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    
    let seconds; // 남은 시간 변수
    let countdown; // 카운트다운을 관리하는 변수
    const $timeSpan = $('.time'); // 시간을 표시할 요소
    const $btnSend = $('.btn_send'); // "인증번호 받기" 버튼 요소

    // 시간을 업데이트하고 화면에 표시하는 함수
    const updateCountdown = function() {
        if (seconds >= 0) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            $timeSpan.text(`${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`);
            seconds--;
        } else {
            clearInterval(countdown);
            alertToast('error','인증번호 유효시간이 만료되었습니다.');
            $('#re_cert').show();
            $('#btn_cc').hide();
        }
    };
    
    clearInterval(countdown);
    seconds = 180; // 3분(180초)

    updateCountdown();
    // 1초마다 카운트다운 업데이트
    countdown = setInterval(updateCountdown, 1000); 
    
    $('#btn_re_cert').on('click', function(e) {
        location.href="/api/find_chk_phone_cert/re";
    });
    
})

function btn_next()
{
	if ($('#phone_cert').val() == '')
	{
		alertToast('error','인증번호를 입력하세요');
		return;
	}
	
	$('#form_find_chk_phone_proc').submit();
}

// ===================== Modal Script [ START ] ===========================

// ===================== Modal Script [ END ] =============================

</script>