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

.overlay {
  background: #fff;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  transition: all 600ms cubic-bezier(0.86, 0, 0.07, 1);
  top: 100%;
  position: fixed;
  left: 0;
  text-align: left;
  .header {
    padding:20px;
    border-bottom: 1px solid #ddd;
    font: 300 24px Lato;
    position: relative;
    }
  .body {
    padding: 20px;
    font: 300 16px Lato;
  }
}

.content.modal-open .overlay {
  top: 55px;
}

</style>

<!-- Main content -->
 <div class="new-title">출근현황</div>
<section class="content">

		<div class="row" id='status-info'>
            <div class="col-lg-3 col-6">
                <div class="small-box2">
                <div class="inner">
                <h3><?php echo ($chk_y + $chk_n)?><sup style="font-size: 20px">일</sup></h3>
                <p>당월 출근일</p>
                </div>
               
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box2 bg-danger">
                <div class="inner">
                <h3><?php echo $chk_n?><sup style="font-size: 20px">일</sup></h3>
                <p>당월 지각일</p>
                </div>
                
                </div>
            </div>
            
            <div class="col-md-12">
	    		<div class='float-right' style='margin-right:20px;' onclick="search_detail();">
	    			<span style='margin-right:15px;font-size:0.9rem'>
	    			<?php echo $ss_yy?>년 | <?php echo $ss_mm?>월
	    			</span>
	    			<i class="fas fa-chevron-down"></i>
	    		</div>
	    	</div>
        </div>
	
	
		<div class="row">
			<div class="col-md-12 mt40">
              
                <!-- CARD BODY [START] -->
				<div class="pad10">
				 <hr>
				<?php 
				if ( count($list_attd) > 0) :
				foreach ($list_attd as $r) :
				    $disp_attd_dv = "출근";
				    $disp_color = "green";
				    
				    if ($r['ATTD_DV'] == "01") 
				    {
				        $disp_attd_dv = "지각";
				        $disp_color = "red";
				    }
				?>
					<?php disp_attd_stat($disp_attd_dv)?>&nbsp;&nbsp;
                 
                    <span style="font-size:1rem;"><strong><i class="far fa-calendar-alt"></i> <?php echo $r['CRE_DATETM']?></strong></span> 
                    <hr>
                <?php endforeach;
                else :
                ?>
                <div class='text-center' style='height:300px;'>
					<span style="font-size:0.9rem;">출근 현황이 없습니다.</span>
				</div>
				<?php endif;?>
				</div>
                <!-- CARD BODY [END] -->
                			
			</div>
		</div>

	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="overlay">
     <div class="new-title">출근현황 검색</div>
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
    			<form name="form_tmem_payment" id="form_tmem_payment" method="post" action="/api/tmem_payment">
                    <button type="button" class="close" id="bottom-menu-close" style="margin-right:20px;margin-top:-35px; color:#fff">&times;</button>
                    <br />
                  
                    <div class='bottom-content' style='margin-top:15px;'>
                    
                        <div class="card card-success">
                        <div class="card-body">
                        
                        <select class="text-center" style="width:99%;height:50px;margin-bottom:10px;" name="ss_yy" id="ss_yy">
                        	<?php for($i=date('Y');$i>2020;$i--) :?>
                        	<option value="<?php echo $i?>" <?php if ($i == $ss_yy) {?> selected <?php } ?> ><?php echo $i?>년</option>
                        	<?php endfor; ?>
    					</select>
    					
    					<select class="text-center" style="width:99%;height:50px;margin-bottom:10px;" name="ss_mm" id="ss_mm">
    						<option value="01" <?php if ($ss_mm == '01' ) {?> selected <?php } ?> >01월</option>
    						<option value="02" <?php if ($ss_mm == '02' ) {?> selected <?php } ?> >02월</option>
    						<option value="03" <?php if ($ss_mm == '03' ) {?> selected <?php } ?> >03월</option>
    						<option value="04" <?php if ($ss_mm == '04' ) {?> selected <?php } ?> >04월</option>
    						<option value="05" <?php if ($ss_mm == '05' ) {?> selected <?php } ?> >05월</option>
    						<option value="06" <?php if ($ss_mm == '06' ) {?> selected <?php } ?> >06월</option>
    						<option value="07" <?php if ($ss_mm == '07' ) {?> selected <?php } ?> >07월</option>
    						<option value="08" <?php if ($ss_mm == '08' ) {?> selected <?php } ?> >08월</option>
    						<option value="09" <?php if ($ss_mm == '09' ) {?> selected <?php } ?> >09월</option>
    						<option value="10" <?php if ($ss_mm == '10' ) {?> selected <?php } ?> >10월</option>
    						<option value="11" <?php if ($ss_mm == '11' ) {?> selected <?php } ?> >11월</option>
    						<option value="12" <?php if ($ss_mm == '12' ) {?> selected <?php } ?> >12월</option>
    					</select>
                        
                        </div>
                        </div>
                        
                        <button type="button" class='btn btn-block bga-main ft-white btn-sm p-3 bottom-menu' onclick="btn_search();">검색하기</button>
                    
                    </div>
                </form>
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

function btn_search()
{
	var yy = $('#ss_yy').val();
	var mm = $('#ss_mm').val();
	location.href="/api/tattd/"+yy+"/"+mm;
}

function search_detail()
{
 	var h_size = $(window).height();
  	var c_size = h_size - 200;
  	$('#bottom-menu-area').css("height",h_size+"px");
 	$('.content').addClass('modal-open');
}

$(".bottom-menu").click(function(){
	$(".overlay").show();
 	var h_size = $(window).height();
 	$('#bottom-menu-area').css("height",h_size+"px");
});

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
});

// ===================== Modal Script [ START ] ===========================

// ===================== Modal Script [ END ] =============================

</script>