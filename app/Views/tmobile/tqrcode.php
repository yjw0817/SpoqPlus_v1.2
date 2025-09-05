<style>


</style>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class='text-center' style='margin-top:100px'>센터 입장 위한 QR 코드 입니다.</div>	
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div style='width:100%;text-align:center;'>
					<div id="qrcode" class='qrcode' style="margin:80px 0px;"></div>
				</div> 
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class='text-center' style='margin:10px'>
				위에 발급된 QR 코드는 시간 단위로 새롭게 갱신되어 발급되고 있습니다.<br /> 
				이전에 발급된 QR코드를 캡쳐해서 이용시 체크가 되지 않습니다. 이점 참고하시어 이용 하시기 바랍니다.
				</div>	
			</div>
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ############################## MODAL [ END ] ###################################### -->
<input type='hidden' id='base_qrcode' value="<?php echo $base_qrcode?>" />
<input type='hidden' id='qrcode_val' />

<button type='button' class='btn btn-block btn-sm btn-info' onclick="temp_tattd();">임시 출근</button>
</section>

<?=$jsinc ?>
<script src="/plugins/qrcode.js"></script>
<script>

function temp_tattd()
{
	var qrcode_val = $('#qrcode_val').val();
	var params = "qrcode_val="+qrcode_val;
	jQuery.ajax({
        url: '/api/tqrcode_attd',
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
				alertToast('success',json_result['msg']);
				//console.log(json_result);	
			} else 
			{
				alertToast('error',json_result['msg']);
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
}

$("#qrcode > img").css({"margin":"auto"});

$(function () {
    let qrInstance = null; // QRCode 인스턴스 저장
    const qrcodeElement = document.getElementById("qrcode");

    // 초기 QR 코드 생성
    const base_qrcode = $('#base_qrcode').val();
    if (!base_qrcode) {
        console.error('base_qrcode 값이 없습니다.');
        return;
    }
    const date_timestamp = Date.now();
    const qr_text = `${base_qrcode}|${date_timestamp}`;
    $('#qrcode_val').val(qr_text);

    qrInstance = new QRCode(qrcodeElement, {
        text: qr_text,
        width: 200,
        height: 200,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // 10초마다 갱신
    setInterval(function () {
        const base_qrcode = $('#base_qrcode').val();
        if (!base_qrcode) {
            console.error('base_qrcode 값이 없습니다.');
            return;
        }
        const date_timestamp = Date.now();
        const qr_text = `${base_qrcode}|${date_timestamp}`;
        $('#qrcode_val').val(qr_text);

        if (qrInstance) {
            qrInstance.clear(); // 기존 QR 코드 지우기
            qrInstance.makeCode(qr_text); // 새 QR 코드 생성
        } else {
            qrInstance = new QRCode(qrcodeElement, {
                text: qr_text,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    }, 10000);
});

</script>