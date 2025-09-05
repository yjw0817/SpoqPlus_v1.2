<style>
#status-info {
  position: sticky;
  top: 57px;
  height: 120px;
  z-index: 998;
}

.row {
    background-color:#ffffff !important;
}

.container-fluid {
    background-color:#ffffff !important;
}

</style>
<?php
$sDef = SpoqDef();
?>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
	
		<div class="row" style='margin-top:20px'>
			<div class="col-md-12">
                
                <div class="card card-info">
				
    				<!-- CARD HEADER [START] -->
    				<div class="card-header">
    					<h3 class="card-title">추천 판매 상품 리스트</h3>
    				</div>
    				<!-- CARD HEADER [END] -->
    			
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            
                            <?php foreach ($list_event as $r) :?>
                            <li class="item">
                                
                                <div class="">
                                <a href="javascript:void(0)" class="product-title">
                                	
                                	<?php echo $r['SELL_EVENT_NM']?>
                                	<span style='float:right'>
                                    	<button type="button" class="btn btn-xs" onclick="buy_info('<?php echo $user_sno?>','<?php echo $r['SELL_EVENT_SNO']?>');"><i class="fas fa-chevron-right"></i></button>
                                    </span>
                                </a>
                                <span class="product-description" style='margin-top:5px;'>
                                	
                                	<span class="badge badge-info"><?php echo $sDef['ACC_RTRCT_DV'][$r['ACC_RTRCT_DV']]?></span>
                                	<?php disp_badge_acc($r['ACC_RTRCT_MTHD'])?>
                                	<?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                                	<?php echo $r['CLAS_CNT']?>회
                                	<?php else:?>
                                	<?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?>
                                	<?php endif;?>
                                	<span class="badge badge-info float-right"><?php echo number_format($r['SELL_AMT'])?></span>
                                </span>
                                <!-- 
                                <span style="font-size:0.8rem;color:blue">
                                &nbsp;
                                </span>
                                 -->
                                </div>
                            </li>
                            <?php endforeach; ?>
                            
                        </ul>
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
})

function buy_info(sno,sell_event_sno)
{
	location.href="/api/tmem_send_info/"+sno+"/"+sell_event_sno;
}

// ===================== Modal Script [ START ] ===========================

// ===================== Modal Script [ END ] =============================

</script>