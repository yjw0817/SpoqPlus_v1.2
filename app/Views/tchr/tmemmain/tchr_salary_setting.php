<style>
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">

			<div class="bbs_search_box_hf mb10 mt10">
			<ul>
		      <li><button type="button" class="basic_bt02 size14 height50 " onclick="location.href='/tmemmain/tchr_salary_setting_list';">강사수당설정 리스트</button></li>
              <li><button type="button" class="basic_bt04 size14 height50 " onclick="location.href='/tmemmain/tchr_salary_setting';">신규 강사수당설정 등록</button></li>
            </ul>
			</div>
		</div>


		<div class="row">
			<div class="col-md-6">
			
				<?php
				$is_disabled = "";
				if ($sinfo['SARLY_MGMT_SNO'] != '') $is_disabled = "disabled";
				?>
				<form name="from_salary_setting" id="from_salary_setting">
				<input type="hidden" name="sarly_mgmt_sno" id="sarly_mgmt_sno" value="<?php echo $sinfo['SARLY_MGMT_SNO']?>" />
				<input type="hidden" name="sarly_mgmt_sno_old" id="sarly_mgmt_sno_old" value="<?php echo $sinfo['SARLY_MGMT_SNO']?>" />
				
					<!-- CARD HEADER [START] -->
					<div class="panel panel-inverse">
							<div class="panel-heading">
						<h3 class="panel-title">강사수당설정</h3>
					</div>
					<!-- CARD HEADER [END] -->
					
					<!-- CARD BODY [START] -->
					<div class="panel-body">
<div class="bbs_search_box_p1 mb10 mt10">
		<ul>
		  <li>ㆍ 강사선택  </li>

		  <li>
			 <select class="form-control" name="tchr_id" id="tchr_id" <?php echo $is_disabled?> >
                        		<option>강사 선택</option>
                        	<?php foreach ($tchr_list as $r) : ?>
        						<option value="<?php echo $r['MEM_ID']?>" <?php if ($sinfo['TCHR_ID'] == $r['MEM_ID']) { echo "selected"; }?> >[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
        					<?php endforeach; ?>
        	 </select>
		  </li>
</div>
<div class="bbs_search_box_p1 mb10 mt10">
		<ul>
		  <li>ㆍ 적용 시작일 </li>

		  <li>
			<input type="date" class="form-control" name=sarly_aply_s_date id="sarly_aply_s_date" value="<?php echo $sinfo['SARLY_APLY_S_DATE']?>" <?php echo $is_disabled?>>
		  </li>
</div>
<div class="bbs_search_box_p1 mb10 mt10">
		<ul>
		  <li>ㆍ 적용 종료일 </li>

		  <li>
			<input type="date" class="form-control" name="sarly_aply_e_date" id="sarly_aply_e_date" value="<?php echo $sinfo['SARLY_APLY_E_DATE']?>" <?php echo $is_disabled?>>
		  </li>
</div>
<div class="bbs_search_box_p2 mb10 mt20">
		<ul>
		  <li>ㆍ 대분류 상품 </li>
		  <li>
			<div style='margin:15px 5px; font-size:14px'>
                            	<div class="icheck-primary d-inline cb_m01">
                                    <input class="cate1_all" type="checkbox" id="cate1_all" name="cate1_chk[]" value="all" <?php if($sinfo['SARLY_APLY_ITEM_1'] == "'all'") { echo "checked"; } ?> <?php echo $is_disabled?> >
                                    <label for="cate1_all">
                                    	<small><b>전체</b></small>
                                    </label>
                                </div>
                            	<?php foreach ($cateEvent['1rd'] as $r) :?>
                            	<?php 
                            	$is_checked = "";
                            	$cate1rd = explode(',',$sinfo['SARLY_APLY_ITEM_1']);
                            	
                            	for($i=0; $i<count($cate1rd); $i++) :
                            	   if ($cate1rd[$i] == "'" . $r['1RD_CATE_CD'] . "'") $is_checked = "checked";
                            	endfor;
                            	?>
                            	
                            	<div class="icheck-primary d-inline cb_m01">
                                    <input class="cate1_notall" type="checkbox" id="cate1_<?php echo $r['1RD_CATE_CD']?>" name="cate1_chk[]" value="<?php echo $r['1RD_CATE_CD']?>" <?php echo $is_checked?> <?php echo $is_disabled?> >
                                    <label for="cate1_<?php echo $r['1RD_CATE_CD']?>">
                                    	<small><?php echo $r['CATE_NM']?></small>
                                    </label>
                                </div>
                                <?php endforeach;?>
                            </div>
		  </li>
</div>

<div class="bbs_search_box_p2 mb10 mt10">
		<ul>
		  <li>ㆍ 중분류 상품 </li>

		  <li>
			<div style='margin:15px 5px; font-size:14px' id="cate2_container">
                        		<div class="icheck-primary d-inline cb_m01">
                                    <input class="cate2_all" type="checkbox" id="cate2_all" name="cate2_chk[]" value="all" <?php if($sinfo['SARLY_APLY_ITEM_2'] == "'all'") { echo "checked"; } ?> <?php echo $is_disabled?> >
                                    <label for="cate2_all">
                                    	<small><b>전체</b></small>
                                    </label>
                                </div>
                            	<?php foreach ($cateEvent['2rd'] as $r) :?>
                            	<?php 
                            	$is_checked = "";
                            	$cate2rd = explode(',',$sinfo['SARLY_APLY_ITEM_2']);
                            	
                            	for($i=0; $i<count($cate2rd); $i++) :
                            	   if ($cate2rd[$i] == "'" . $r['2RD_CATE_CD'] . "'") $is_checked = "checked";
                            	endfor;
                            	?>
                            	<div class="icheck-primary d-inline cb_m01">
                                    <input class="cate2_notall" type="checkbox" id="cate2_<?php echo $r['2RD_CATE_CD']?>" name="cate2_chk[]" value="<?php echo $r['2RD_CATE_CD']?>" <?php echo $is_checked?> <?php echo $is_disabled?> >
                                    <label for="cate2_<?php echo $r['2RD_CATE_CD']?>">
                                    	<small><?php echo $r['CATE_NM']?></small>
                                    </label>
                                </div>
                                <?php endforeach;?>
                            </div>
		  </li>
</div>
<div class="bbs_search_box_p2 mb10 mt10">
		<ul>
		  <li>ㆍ 수당 계산시 매출 범위</li>

		  <li>
			<?php
                        	// 기본급일 경우에 수정을 하게되면 기본급만 셋팅이 되어야 한다.
                        	// 기본급이 아닐 경우에는 전체강사, 개인강사를 선택 할 수 있어야 한다.
                        	// 하지만 이미 설정된 경우($is_disabled가 설정된 경우)는 모두 disabled 처리
                        	if ($is_disabled != "") :
                        	   $disabled_sarly_pmt_cond_01 = "disabled";
                        	   $disabled_sarly_pmt_cond_02 = "disabled";
                        	   $disabled_sarly_pmt_cond_03 = "disabled";
                        	elseif ($sinfo['SARLY_PMT_COND'] == "03") :
                        	   $disabled_sarly_pmt_cond_01 = "disabled";
                        	   $disabled_sarly_pmt_cond_02 = "disabled";
                        	   $disabled_sarly_pmt_cond_03 = "";
                        	elseif ($sinfo['SARLY_PMT_COND'] == "01") :
                            	$disabled_sarly_pmt_cond_01 = "";
                            	$disabled_sarly_pmt_cond_02 = "";
                            	$disabled_sarly_pmt_cond_03 = "disabled";
                            elseif ($sinfo['SARLY_PMT_COND'] == "02") :
                            	$disabled_sarly_pmt_cond_01 = "";
                            	$disabled_sarly_pmt_cond_02 = "";
                            	$disabled_sarly_pmt_cond_03 = "disabled";
                        	else :
                            	$disabled_sarly_pmt_cond_01 = "";
                            	$disabled_sarly_pmt_cond_02 = "";
                            	$disabled_sarly_pmt_cond_03 = "";
                        	endif;
                        	    
                        	?>
                        	
                        	<div style='margin:15px 5px; font-size:14px'>
                            	<div class="icheck-primary d-inline cb_m01">
                                    <input type="radio" id="radioSarlyPmtCond1" name="sarly_pmt_cond" value="01" <?php if($sinfo['SARLY_PMT_COND'] == "01") { echo "checked"; }?> <?php echo $disabled_sarly_pmt_cond_01?> >
                                    <label for="radioSarlyPmtCond1">
                                    	<small>전체강사</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline cb_m01">
                                    <input type="radio" id="radioSarlyPmtCond2" name="sarly_pmt_cond" value="02" <?php if($sinfo['SARLY_PMT_COND'] == "02") { echo "checked"; }?> <?php echo $disabled_sarly_pmt_cond_02?> >
                                    <label for="radioSarlyPmtCond2">
                                    	<small>개인강사</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline cb_m01">
                                    <input type="radio" id="radioSarlyPmtCond3" name="sarly_pmt_cond" value="03" <?php if($sinfo['SARLY_PMT_COND'] == "03") { echo "checked"; }?> <?php echo $disabled_sarly_pmt_cond_03?> >
                                    <label for="radioSarlyPmtCond3">
                                    	<small>기본급</small>
                                    </label>
                                    <?php
                                    $display_basic_amt = "";
                                    if ($sinfo['SARLY_PMT_COND'] != "03") $display_basic_amt = "display:none";
                                    ?>
                                    <div class="disp_basic_amt" style='<?php echo $display_basic_amt?>'>
                                    	<input type="text" style='width:100px;' class="text-right" name="sarly_basic_amt" id="sarly_basic_amt" value="<?php echo number_format($sarly_basic_amt)?>" <?php echo $is_disabled?>>원
                                    </div>
                                </div>
		  </li>
</div>
<div class="bbs_search_box_p2 mb10 mt10">
		<ul>
		  <li>ㆍ 부가세설정 </li>

		  <li>
			<div style='margin:15px 5px; font-size:14px'>
                            	<div class="icheck-primary d-inline cb_m01">
                                    <input type="radio" id="radioVatYn1" name="vat_yn" value="Y" <?php if($sinfo['VAT_YN'] == "Y") { echo "checked"; }?> <?php echo $is_disabled?> >
                                    <label for="radioVatYn1">
                                    	<small>부가세 포함</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline cb_m01">
                                    <input type="radio" id="radioVatYn2" name="vat_yn" value="N" <?php if($sinfo['VAT_YN'] == "N") { echo "checked"; }?> <?php echo $is_disabled?> >
                                    <label for="radioVatYn2">
                                    	<small>부가세 제외</small>
                                    </label>
             </div>
		  </li>
</div>
<div class="bbs_search_box_p1 mb10 mt10">
		<ul>
		  <li>ㆍ 지급방법 </li>

		  <li>
			<select class="form-control" name="sarly_pmt_mthd" id="sarly_pmt_mthd" <?php echo $is_disabled?> >
                        		<option value="">지급방법</option>
                        		<option value="11" <?php if($sinfo['SARLY_PMT_MTHD'] == "11") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['11']?></option>
                        		<option value="12" <?php if($sinfo['SARLY_PMT_MTHD'] == "12") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['12']?></option>
                        		<option value="13" <?php if($sinfo['SARLY_PMT_MTHD'] == "13") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['13']?></option>
                        		<option value="21" <?php if($sinfo['SARLY_PMT_MTHD'] == "21") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['21']?></option>
                        		<option value="22" <?php if($sinfo['SARLY_PMT_MTHD'] == "22") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['22']?></option>
                        		<option value="31" <?php if($sinfo['SARLY_PMT_MTHD'] == "31") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['31']?></option>
                        		<option value="32" <?php if($sinfo['SARLY_PMT_MTHD'] == "32") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['32']?></option>
        					</select>
		  </li>
</div>




						<!-- <div class="input-group input-group-sm mb-1">
							
						
                        	<select class="select2 form-control" style="width: 350px;" name="tchr_id" id="tchr_id" <?php echo $is_disabled?> >
                        		<option>강사 선택</option>
                        	<?php foreach ($tchr_list as $r) : ?>
        						<option value="<?php echo $r['MEM_ID']?>" <?php if ($sinfo['TCHR_ID'] == $r['MEM_ID']) { echo "selected"; }?> >[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
        					<?php endforeach; ?>
        					</select>

                        </div>
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:150px;margin-left:10px;'>적용 시작일</span>
                        	</span>
                        	<input type="text" class="datepp" name=sarly_aply_s_date id="sarly_aply_s_date" value="<?php echo $sinfo['SARLY_APLY_S_DATE']?>">
                        </div>
                        <div class="input-group input-group-sm mb-1">
                        	<span class="input-group-append">
                        		<span class="input-group-text" style='width:150px;margin-left:10px;'>적용 종료일</span>
                        	</span>
                        	<input type="text" class="datepp" name="sarly_aply_e_date" id="sarly_aply_e_date" value="<?php echo $sinfo['SARLY_APLY_E_DATE']?>">
                        </div>
                        
                        <div class="input-group input-group-sm mb-1">
							<span class="input-group-append">
                        		<span class="input-group-text" style='width:150px;margin-left:10px;'>대분류 상품</span>
                        	</span>
                        	
                        	<div style='margin-top:4px;margin-left:5px;'>
                            	<div class="icheck-primary d-inline">
                                    <input class="cate1_all" type="checkbox" id="cate1_all" name="cate1_chk[]" value="all" <?php if($sinfo['SARLY_APLY_ITEM_1'] == "'all'") { echo "checked"; } ?> <?php echo $is_disabled?> >
                                    <label for="cate1_all">
                                    	<small><b>전체</b></small>
                                    </label>
                                </div>
                            	<?php foreach ($cateEvent['1rd'] as $r) :?>
                            	<?php 
                            	$is_checked = "";
                            	$cate1rd = explode(',',$sinfo['SARLY_APLY_ITEM_1']);
                            	
                            	for($i=0; $i<count($cate1rd); $i++) :
                            	   if ($cate1rd[$i] == "'" . $r['1RD_CATE_CD'] . "'") $is_checked = "checked";
                            	endfor;
                            	?>
                            	
                            	<div class="icheck-primary d-inline">
                                    <input class="cate1_notall" type="checkbox" id="cate1_<?php echo $r['1RD_CATE_CD']?>" name="cate1_chk[]" value="<?php echo $r['1RD_CATE_CD']?>" <?php echo $is_checked?> <?php echo $is_disabled?> >
                                    <label for="cate1_<?php echo $r['1RD_CATE_CD']?>">
                                    	<small><?php echo $r['CATE_NM']?></small>
                                    </label>
                                </div>
                                <?php endforeach;?>
                            </div>
                            
                        </div>
                        
                        <div class="input-group input-group-sm mb-1">
							<span class="input-group-append">
                        		<span class="input-group-text" style='width:150px;margin-left:10px;'>중분류 상품</span>
                        	</span>
                        	
                        	<div style='margin-top:4px;margin-left:5px;' id="cate2_container">
                        		<div class="icheck-primary d-inline">
                                    <input class="cate2_all" type="checkbox" id="cate2_all" name="cate2_chk[]" value="all" <?php if($sinfo['SARLY_APLY_ITEM_2'] == "'all'") { echo "checked"; } ?> <?php echo $is_disabled?> >
                                    <label for="cate2_all">
                                    	<small><b>전체</b></small>
                                    </label>
                                </div>
                            	<?php foreach ($cateEvent['2rd'] as $r) :?>
                            	<?php 
                            	$is_checked = "";
                            	$cate2rd = explode(',',$sinfo['SARLY_APLY_ITEM_2']);
                            	
                            	for($i=0; $i<count($cate2rd); $i++) :
                            	   if ($cate2rd[$i] == "'" . $r['2RD_CATE_CD'] . "'") $is_checked = "checked";
                            	endfor;
                            	?>
                            	<div class="icheck-primary d-inline">
                                    <input class="cate2_notall" type="checkbox" id="cate2_<?php echo $r['2RD_CATE_CD']?>" name="cate2_chk[]" value="<?php echo $r['2RD_CATE_CD']?>" <?php echo $is_checked?> <?php echo $is_disabled?> >
                                    <label for="cate2_<?php echo $r['2RD_CATE_CD']?>">
                                    	<small><?php echo $r['CATE_NM']?></small>
                                    </label>
                                </div>
                                <?php endforeach;?>
                            </div>
                            
                        </div>
                        
                        <div class="input-group input-group-sm mb-1">
							<span class="input-group-append">
                        		<span class="input-group-text" style='width:150px;margin-left:10px;'>수당지급 조건</span>
                        	</span>
                        	
                        	<?php
                        	// 기본급일 경우에 수정을 하게되면 기본급만 셋팅이 되어야 한다.
                        	// 기본급이 아닐 경우에는 전체강사, 개인강사를 선택 할 수 있어야 한다.
                        	// 하지만 이미 설정된 경우($is_disabled가 설정된 경우)는 모두 disabled 처리
                        	if ($is_disabled != "") :
                        	   $disabled_sarly_pmt_cond_01 = "disabled";
                        	   $disabled_sarly_pmt_cond_02 = "disabled";
                        	   $disabled_sarly_pmt_cond_03 = "disabled";
                        	elseif ($sinfo['SARLY_PMT_COND'] == "03") :
                        	   $disabled_sarly_pmt_cond_01 = "disabled";
                        	   $disabled_sarly_pmt_cond_02 = "disabled";
                        	   $disabled_sarly_pmt_cond_03 = "";
                        	elseif ($sinfo['SARLY_PMT_COND'] == "01") :
                            	$disabled_sarly_pmt_cond_01 = "";
                            	$disabled_sarly_pmt_cond_02 = "";
                            	$disabled_sarly_pmt_cond_03 = "disabled";
                            elseif ($sinfo['SARLY_PMT_COND'] == "02") :
                            	$disabled_sarly_pmt_cond_01 = "";
                            	$disabled_sarly_pmt_cond_02 = "";
                            	$disabled_sarly_pmt_cond_03 = "disabled";
                        	else :
                            	$disabled_sarly_pmt_cond_01 = "";
                            	$disabled_sarly_pmt_cond_02 = "";
                            	$disabled_sarly_pmt_cond_03 = "";
                        	endif;
                        	    
                        	?>
                        	
                        	<div style='margin-top:4px;margin-left:5px;'>
                            	<div class="icheck-primary d-inline">
                                    <input type="radio" id="radioSarlyPmtCond1" name="sarly_pmt_cond" value="01" <?php if($sinfo['SARLY_PMT_COND'] == "01") { echo "checked"; }?> <?php echo $disabled_sarly_pmt_cond_01?> >
                                    <label for="radioSarlyPmtCond1">
                                    	<small>전체강사</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioSarlyPmtCond2" name="sarly_pmt_cond" value="02" <?php if($sinfo['SARLY_PMT_COND'] == "02") { echo "checked"; }?> <?php echo $disabled_sarly_pmt_cond_02?> >
                                    <label for="radioSarlyPmtCond2">
                                    	<small>개인강사</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioSarlyPmtCond3" name="sarly_pmt_cond" value="03" <?php if($sinfo['SARLY_PMT_COND'] == "03") { echo "checked"; }?> <?php echo $disabled_sarly_pmt_cond_03?> >
                                    <label for="radioSarlyPmtCond3">
                                    	<small>기본급</small>
                                    </label>
                                    <?php
                                    $display_basic_amt = "";
                                    if ($sinfo['SARLY_PMT_COND'] != "03") $display_basic_amt = "display:none";
                                    ?>
                                    <div class="disp_basic_amt" style='<?php echo $display_basic_amt?>'>
                                    	<input type="text" style='width:100px;' class="text-right" name="sarly_basic_amt" id="sarly_basic_amt" value="<?php echo number_format($sarly_basic_amt)?>" <?php echo $is_disabled?>>원
                                    </div>
                                </div>
                                
                            </div>
                      </div>
                      <div class="input-group input-group-sm mb-1">
						
                            <span class="input-group-append">
                        		<span class="input-group-text" style='width:150px;margin-left:10px;'>부가세 설정</span>
                        	</span>
                        	
                        	<div style='margin-top:4px;margin-left:5px;'>
                            	<div class="icheck-primary d-inline">
                                    <input type="radio" id="radioVatYn1" name="vat_yn" value="Y" <?php if($sinfo['VAT_YN'] == "Y") { echo "checked"; }?> <?php echo $is_disabled?> >
                                    <label for="radioVatYn1">
                                    	<small>부가세 포함</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioVatYn2" name="vat_yn" value="N" <?php if($sinfo['VAT_YN'] == "N") { echo "checked"; }?> <?php echo $is_disabled?> >
                                    <label for="radioVatYn2">
                                    	<small>부가세 제외</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="input-group input-group-sm mb-1">
                        	<select class="select2 form-control" style="width: 400px;" name="sarly_pmt_mthd" id="sarly_pmt_mthd" <?php echo $is_disabled?> >
                        		<option value="">지급방법</option>
                        		<option value="11" <?php if($sinfo['SARLY_PMT_MTHD'] == "11") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['11']?></option>
                        		<option value="12" <?php if($sinfo['SARLY_PMT_MTHD'] == "12") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['12']?></option>
                        		<option value="13" <?php if($sinfo['SARLY_PMT_MTHD'] == "13") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['13']?></option>
                        		<option value="21" <?php if($sinfo['SARLY_PMT_MTHD'] == "21") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['21']?></option>
                        		<option value="22" <?php if($sinfo['SARLY_PMT_MTHD'] == "22") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['22']?></option>
                        		<option value="31" <?php if($sinfo['SARLY_PMT_MTHD'] == "31") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['31']?></option>
                        		<option value="32" <?php if($sinfo['SARLY_PMT_MTHD'] == "32") { echo "selected"; }?> ><?php echo $sDef['SARLY_PMT_MTHD_NAME']['32']?></option>
        					</select>
        				</div> -->





                  <div class="card-footer clearfix mt20">
						<ul class="pagination flex_column flex_al_cnt">
							<li class="ac-btn"><button type="button" class="top_bt btc001 " onclick="btn_pre_setting();">설정하기</button></li>
							<span id="help_word" class="mt10" style="font-size:13px; font-weight;bold; color:#ea0303;">(설정하기 후 조건설정이 가능합니다.)</span>
						</ul>
					</div>
					
						
					</div>
				  </div>
				</form>
			</div>
			<?php
			$disp_cond_area = "";
			if ($sinfo['SARLY_PMT_COND'] == "03") $disp_cond_area = "display:none";
			?>
			
<div class="col-md-6" id="disp_cond_area" style='<?php echo $disp_cond_area?>'>
	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h3 class="panel-title">조건설정</h3>
		</div>
		<div class="panel-body">

<?php if(count($sinfo_sub) > 0) :?>
					
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:150px'><?php echo $table_title['title_s_set']?></th>
									<th style='width:150px'><?php echo $table_title['title_e_set']?></th>
									<th style='width:150px'><?php echo $table_title['title_set']?></th>
									<th></th>
								</tr>
							</thead> 
							<tbody>
							<?php foreach ($sinfo_sub as $r) :?>
								<tr>
									<td><?php echo number_format($r['sarly_s_set'])?></td>
									<td><?php echo number_format($r['sarly_e_set'])?></td>
									<td><?php echo number_format($r['sarly_set'])?></td>
									<td>
										<button type="button" class="btn btn-danger btn-xs" onclick="btn_sub_del('<?php echo $r['sarly_sub_mgmt_sno']?>');">삭제</button>
									</td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
						
						<?php endif;?>

                    <div class="card-footer clearfix">
					
						<?php
						$is_disabled = "";
						if ($sinfo['SARLY_MGMT_SNO'] == '') $is_disabled = "disabled";
						?>
						<!-- BUTTON [START] -->
						<ul class="pagination pagination-sm m-0 float-left">
							<li class="ac-btn">
								<button type="button" class="top_bt width200 btc001" id="btn_add_cond" onclick="btn_add_cond();" <?php echo $is_disabled?> >수당조건 추가하기</button></li>
						</ul>
						
						<!-- BUTTON [END] -->
					</div>
		</div>	
</div>
</div>



			<!-- <div class="col-md-6" id="disp_cond_area" style='<?php echo $disp_cond_area?>'>
				<div class="card card-info">
					
					<div class="page-header">
						<h3 class="panel-title">조건설정</h3>
					</div>

					<div class="panel-body">
						<?php if(count($sinfo_sub) > 0) :?>
					
						<table class="table table-bordered table-hover table-striped col-md-12">
							<thead>
								<tr class='text-center'>
									<th style='width:150px'><?php echo $table_title['title_s_set']?></th>
									<th style='width:150px'><?php echo $table_title['title_e_set']?></th>
									<th style='width:150px'><?php echo $table_title['title_set']?></th>
									<th></th>
								</tr>
							</thead> 
							<tbody>
							<?php foreach ($sinfo_sub as $r) :?>
								<tr>
									<td><?php echo number_format($r['sarly_s_set'])?></td>
									<td><?php echo number_format($r['sarly_e_set'])?></td>
									<td><?php echo number_format($r['sarly_set'])?></td>
									<td>
										<button type="button" class="btn btn-danger btn-xs" onclick="btn_sub_del('<?php echo $r['sarly_sub_mgmt_sno']?>');">삭제</button>
									</td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
						
						<?php endif;?>
        			</div>
        			
        		
					<div class="card-footer clearfix">
					
						<?php
						$is_disabled = "";
						if ($sinfo['SARLY_MGMT_SNO'] == '') $is_disabled = "disabled";
						?>
						
						<ul class="pagination pagination-sm m-0 float-left">
							<li class="ac-btn"><button type="button" class="btn btn-block btn-success btn-xs" id="btn_add_cond" onclick="btn_add_cond();" <?php echo $is_disabled?> >수당조건 추가하기</button></li>
						</ul>
						
						
					</div>
					
        			
        		</div>
			</div> -->
			
		</div>
	</div>
	
<!-- ############################## MODAL [ SATRT ] #################################### -->
	
<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="sarly_add_cond">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">조건설정</h4>
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	<form name="form_sarly_add_cond" id="form_sarly_add_cond">
            	<input type="hidden" name="add_sarly_pmt_mthd" id="add_sarly_pmt_mthd">
            	<input type="hidden" name="add_sarly_mgmt_sno" id="add_sarly_mgmt_sno">



            	<div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:120px' id='add_cond_title'>구간</span>
                	</span>
                	<input type="text" class="form-control"  style='width:110px' name="sarly_s_set" id="sarly_s_set">
                	&nbsp;~&nbsp;
                	<input type="text" class="form-control"  style='width:110px' name="sarly_e_set" id="sarly_e_set">
                </div>
                <div class="input-group input-group-sm mb-1">
                	<span class="input-group-append">
                		<span class="input-group-text" style='width:120px' id='add_cond_set'>셋팅</span>
                	</span>
                	<input type="text"class="form-control"  style='width:110px' name="sarly_set" id="sarly_set">
                	&nbsp;&nbsp;<span id='sarly_unit'>%</span>
                </div>
            	</form>
            	
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default"  data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-sm btn-success" onclick="btn_sarly_add_cond();">추가</button>
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
    
    // 페이지 로드 시 이미 설정된 경우 필드 비활성화
    <?php if ($sinfo['SARLY_MGMT_SNO'] != '') : ?>
        // PHP에서 이미 disabled 처리된 필드들은 제외하고 추가로 disabled 처리
        // disableAllFields(); // 이미 PHP에서 처리됨
    <?php endif; ?>
})
$(document).ready(function () {
	// 강사 선택 변경 시
	$('#tchr_id').on('change', disableAddCondButton);

	// 대분류 체크박스
	$(document).on('change', 'input[name="cate1_chk[]"]', disableAddCondButton);

	// 부가세 라디오
	$(document).on('change', 'input[name="vat_yn"]', disableAddCondButton);

	// 급여 지급조건 라디오
	$(document).on('change', 'input[name="sarly_pmt_cond"]', function () {
		disableAddCondButton();

		// 기본급 선택 시 금액 입력란이 열릴 수 있으므로 기본급 입력에도 감지
		if ($(this).val() === "03") {
			$('#sarly_basic_amt').on('input', disableAddCondButton);
		}
	});

	// 수당 지급방법 (select2)
	$('#sarly_pmt_mthd').on('change', disableAddCondButton);

	// ✅ 수당 적용 시작일
	$('#sarly_aply_s_date').on('change input', disableAddCondButton);

	// ✅ 수당 적용 종료일
	$('#sarly_aply_e_date').on('change input', disableAddCondButton);
	
	// 모달 닫힐 때 readonly 속성 제거
	$('#sarly_add_cond').on('hidden.bs.modal', function () {
		$('#sarly_s_set').prop('readonly', false);
	});
});


$(document).on('change', 'input[name="cate1_chk[]"]', function() {
	let selectedValues = [];
	const isAll = $(this).val() === "all";
	const allCheckbox = $('input[name="cate1_chk[]"][value="all"]');
	const itemCheckboxes = $('input[name="cate1_chk[]"]').not('[value="all"]');

	if (isAll) {
		const isChecked = $(this).is(":checked");

		if (isChecked) {
			// 전체 체크 시 → 전체는 체크, 나머지는 해제
			$('input[name="cate1_chk[]"]').each(function () {
				if ($(this).val() === "all") {
					$(this).prop("checked", true);
				} else {
					$(this).prop("checked", false);
				}
			});
			// 전체 외 항목만 서버 전송
			itemCheckboxes.each(function() {
				selectedValues.push($(this).val());
			});
		} else {
			// 전체 해제 시 → 모두 해제
			$('input[name="cate1_chk[]"]').prop("checked", false);
			$('#cate2_container').empty();
			return;
		}
	} else {
		const totalCount = itemCheckboxes.length;
		const checkedCount = itemCheckboxes.filter(':checked').length;

		if (checkedCount === totalCount) {
			// 전체 제외 모두 체크됨 → 전체 자동 체크 + 나머지 해제
			allCheckbox.prop("checked", true);
			itemCheckboxes.prop("checked", false);
			$('#cate2_container').empty();
			return;
		} else {
			// 일부만 체크 시 전체 해제
			allCheckbox.prop("checked", false);
		}

		// 선택된 항목 서버 전송 준비
		itemCheckboxes.filter(':checked').each(function() {
			selectedValues.push($(this).val());
		});

		// 아무것도 체크 안 됨 → 서버 요청 중단
		if (selectedValues.length === 0) {
			$('#cate2_container').empty();
			return;
		}
	}

	// Ajax 요청
	$.ajax({
		url: '/tmemmain/ajax_get_cate2',
		type: 'POST',
		data: { cate1_selected: selectedValues },
		async: false,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function(result) {
			if (result.substr(0, 8) === '<script>') {
				alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
				location.href = '/tlogin';
				return;
			}

			const json_result = $.parseJSON(result);
			if (json_result['result'] === 'true') {
				// 2rd 초기화 전에 기존 선택값 저장
				const prevCheckedCate2 = [];
				$('input[name="cate2_chk[]"]:checked').each(function() {
					prevCheckedCate2.push($(this).val());
				});

				let html = `<div class="icheck-primary d-inline cb_m01">
					<input class="cate2_all" type="checkbox" id="cate2_all" name="cate2_chk[]" value="all"
						${prevCheckedCate2.includes('all') ? 'checked' : ''}>
					<label for="cate2_all"><small><b>전체</b></small></label>
				</div>`;

				json_result['cate2'].forEach(function(item) {
					const cateCd = item['2RD_CATE_CD'];
					const isChecked = prevCheckedCate2.includes(cateCd) ? 'checked' : '';

					html += `<div class="icheck-primary d-inline cb_m01">
						<input class="cate2_notall" type="checkbox" id="cate2_${cateCd}" name="cate2_chk[]" value="${cateCd}" ${isChecked}>
						<label for="cate2_${cateCd}"><small>${item['CATE_NM']}</small></label>
					</div>`;
				});

				$('#cate2_container').html(html);
			} else {
				alertToast('error', '오류가 발생하였습니다.');
			}
		}
	}).done(() => {
		console.log('통신성공');
	}).fail(() => {
		console.log('통신실패');
		alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
		location.href = '/tlogin';
	});
});


$(document).on('change', 'input[name="cate2_chk[]"]', function () {
	const isAll = $(this).val() === "all";
	const allCheckbox = $('input[name="cate2_chk[]"][value="all"]');
	const itemCheckboxes = $('input[name="cate2_chk[]"]').not('[value="all"]');

	if (isAll) {
		const isChecked = $(this).is(":checked");

		if (isChecked) {
			// 전체가 체크되면 나머지는 모두 해제
			itemCheckboxes.prop("checked", false);
			allCheckbox.prop("checked", true);
		} else {
			// 전체가 해제되면 전부 해제
			$('input[name="cate2_chk[]"]').prop("checked", false);
		}
	} else {
		// 개별 항목 체크 여부 확인
		const totalCount = itemCheckboxes.length;
		const checkedCount = itemCheckboxes.filter(':checked').length;

		if (checkedCount === totalCount) {
			// 전체 제외 모두 체크되었으면 → 전체 자동 체크 + 나머지 해제
			allCheckbox.prop("checked", true);
			itemCheckboxes.prop("checked", false);
		} else {
			// 일부만 체크된 경우 → 전체 해제
			allCheckbox.prop("checked", false);
		}
	}
});

// 기본급을 선택하였을때.
// 1. 대분류 상품에 다른것을 선택하여도 전체로 돌려줘야한다.
// 2. 중분류 상품에 다른것을 선택하여도 전체로 돌려줘야한다.
// 3. 지급방법을 비활성화 해야한다.
// 4. 설정하기 버튼에서도 지급방법 체크 루틴을 지워야한다.
// 5. 기본급 라디오 버튼 옆에 금액 적는 필드를 추가해야한다.

$('#radioSarlyPmtCond3').click(function(){
	$('#cate1_all').prop('checked',true);
	$('.cate1_notall').prop('checked',false);
	
	$('#cate2_all').prop('checked',true);
	$('.cate2_notall').prop('checked',false);
	
	$("#sarly_pmt_mthd").prop('disabled',true);
	$('.disp_basic_amt').show();
	
	$('#disp_cond_area').hide();
	
	$('#help_word').text('(기본급은 설정하기 즉시 기본급 등록이 완료 됩니다.)');
	
});

$('#radioSarlyPmtCond1').click(function(){
	console.log('전체강사 선택');
	
	$("#sarly_pmt_mthd").prop('disabled',false);
	$('.disp_basic_amt').hide();
	$('#disp_cond_area').show();
	
	$('#help_word').text('(설정하기 후 조건설정이 가능합니다.)');
});

$('#radioSarlyPmtCond2').click(function(){
	console.log('개인강사 선택');
	
	$("#sarly_pmt_mthd").prop('disabled',false);
	$('.disp_basic_amt').hide();
	$('#disp_cond_area').show();
	
	$('#help_word').text('(설정하기 후 조건설정이 가능합니다.)');
});


$('.cate1_all').click(function(){
		$('.cate1_notall').prop('checked',false);
});


$('.cate1_notall').click(function(){
	
		$('.cate1_all').prop('checked',false);
});

$('.cate2_all').click(function(){
		$('.cate2_notall').prop('checked',false);
});


$('.cate2_notall').click(function(){
	
		$('.cate2_all').prop('checked',false);
});

$('#sarly_basic_amt').keyup(function(){
	var d_amt = onlyNum( $('#sarly_basic_amt').val() );
	$('#sarly_basic_amt').val(currencyNum(d_amt));
});

$('#sarly_s_set').keyup(function(){
	var d_amt = onlyNum( $('#sarly_s_set').val() );
	$('#sarly_s_set').val(currencyNum(d_amt));
});

$('#sarly_e_set').keyup(function(){
	var d_amt = onlyNum( $('#sarly_e_set').val() );
	$('#sarly_e_set').val(currencyNum(d_amt));
});

$('#sarly_set').keyup(function(){
	var d_amt = onlyNum( $('#sarly_set').val() );
	$('#sarly_set').val(currencyNum(d_amt));
});


function btn_sub_del(sarly_sub_mgmt_sno)
{
	var params = "sarly_sub_mgmt_sno="+sarly_sub_mgmt_sno;
	jQuery.ajax({
        url: '/tmemmain/ajax_salry_setting_del_cond_proc',
        type: 'POST',
        data:params,
        async: false,
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
				alertToast('success','수당조건이 삭제 되었습니다.');
				location.href="/tmemmain/tchr_salary_setting/"+$('#sarly_mgmt_sno').val();
			} else 
			{
				alertToast('error','강사수당 설정에 오류가 있습니다.');
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

function btn_sarly_add_cond()
{
	// 입력값 유효성 검사
	var startVal = parseInt($('#sarly_s_set').val().replace(/,/g, '')) || 0;
	var endVal = parseInt($('#sarly_e_set').val().replace(/,/g, '')) || 0;
	var setVal = parseInt($('#sarly_set').val().replace(/,/g, '')) || 0;
	
	if (endVal === 0) {
		alertToast('error', '종료 금액을 입력해주세요.');
		$('#sarly_e_set').focus();
		return;
	}
	
	if (endVal <= startVal) {
		alertToast('error', '종료 금액은 시작 금액보다 커야 합니다.');
		$('#sarly_e_set').focus();
		return;
	}
	
	if (setVal === 0) {
		alertToast('error', '설정값을 입력해주세요.');
		$('#sarly_set').focus();
		return;
	}
	
	var params = $("#form_sarly_add_cond").serialize();
	jQuery.ajax({
        url: '/tmemmain/ajax_salry_setting_add_cond_proc',
        type: 'POST',
        data:params,
        async: false,
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
			console.log(json_result);
			if (json_result['result'] == 'true')
			{
				alert('수당조건이 추가 되었습니다.');
				location.href="/tmemmain/tchr_salary_setting/"+$('#add_sarly_mgmt_sno').val();
			} else 
			{
				alertToast('error','강사수당 설정에 오류가 있습니다.');
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

function analyzePattern() {
	var conditions = [];
	$('#disp_cond_area tbody tr').each(function() {
		var startVal = parseInt($(this).find('td:eq(0)').text().replace(/,/g, '')) || 0;
		var endVal = parseInt($(this).find('td:eq(1)').text().replace(/,/g, '')) || 0;
		var setVal = parseInt($(this).find('td:eq(2)').text().replace(/,/g, '')) || 0;
		conditions.push({start: startVal, end: endVal, set: setVal, interval: endVal - startVal});
	});
	
	// 최소 2개 이상의 조건이 있어야 패턴 분석 가능
	if (conditions.length < 1) return null;
	
	// 첫 번째 조건만 있는 경우 기본 패턴 적용
	if (conditions.length === 1) {
		var firstCondition = conditions[0];
		return {
			nextStart: firstCondition.end,
			nextEnd: firstCondition.end + firstCondition.interval, // 동일한 구간 크기
			nextSet: firstCondition.set // 동일한 설정값 (사용자가 수정 가능)
		};
	}
	
	// 구간 크기 패턴 분석
	var intervals = conditions.map(c => c.interval);
	var intervalDiffs = [];
	for (var i = 1; i < intervals.length; i++) {
		intervalDiffs.push(intervals[i] - intervals[i-1]);
	}
	
	// 설정값 차이 패턴 분석
	var setDiffs = [];
	for (var i = 1; i < conditions.length; i++) {
		setDiffs.push(conditions[i].set - conditions[i-1].set);
	}
	
	// 패턴이 일정한지 확인
	var isIntervalConsistent = intervals.every(val => val === intervals[0]);
	var isSetConsistent = setDiffs.every(val => val === setDiffs[0]);
	
	// 다음 값 예측
	var lastCondition = conditions[conditions.length - 1];
	var predictedInterval;
	
	if (isIntervalConsistent) {
		// 모든 구간이 동일한 경우
		predictedInterval = intervals[0];
	} else if (intervalDiffs.length > 0 && intervalDiffs.every(val => val === intervalDiffs[0])) {
		// 구간이 일정하게 증가하는 경우
		predictedInterval = lastCondition.interval + intervalDiffs[0];
	} else {
		// 패턴이 없는 경우 마지막 구간과 동일하게
		predictedInterval = lastCondition.interval;
	}
	
	var predictedSetIncrease = isSetConsistent && setDiffs.length > 0 ? setDiffs[0] : 
		(setDiffs.length > 0 ? setDiffs[setDiffs.length - 1] : 0);
	
	return {
		nextStart: lastCondition.end,
		nextEnd: lastCondition.end + predictedInterval,
		nextSet: lastCondition.set + predictedSetIncrease
	};
}

function btn_add_cond()
{

	switch( $('#sarly_pmt_mthd').val() )
	{
		case "11":
			$('#add_cond_title').text("판매매출액");
			$('#add_cond_set').text("판매매출 요율");
			$('#sarly_unit').text("%");
		break;
		case "12":
			$('#add_cond_title').text("판매매출액");
			$('#add_cond_set').text("PT수업매출 요율");
			$('#sarly_unit').text("%");
		break;
		case "13":
			$('#add_cond_title').text("PT수업 매출액");
			$('#add_cond_set').text("PT수업매출 요율");
			$('#sarly_unit').text("%");
		break;
		case "21":
			$('#add_cond_title').text("판매매출액");
			$('#add_cond_set').text("수당금액");
			$('#sarly_unit').text("원");
		break;
		case "22":
			$('#add_cond_title').text("PT수업 매출액");
			$('#add_cond_set').text("수당금액");
			$('#sarly_unit').text("원");
		break;
		case "31":
			$('#add_cond_title').text("PT수업횟수");
			$('#add_cond_set').text("수업1회금액");
			$('#sarly_unit').text("원");
		break;
		case "32":
			$('#add_cond_title').text("GX수업횟수");
			$('#add_cond_set').text("수업1회금액");
			$('#sarly_unit').text("원");
		break;
	}
	
	// 기존 조건들의 최대 종료값 찾기
	var maxEndValue = 0;
	$('#disp_cond_area tbody tr').each(function() {
		var endValue = parseInt($(this).find('td:eq(1)').text().replace(/,/g, '')) || 0;
		if (endValue > maxEndValue) {
			maxEndValue = endValue;
		}
	});
	
	// 패턴 분석을 통한 예측값 가져오기
	var prediction = analyzePattern();
	
	// 첫 번째 조건이면 시작값 0, 아니면 이전 최대값으로 설정
	if (maxEndValue === 0) {
		$('#sarly_s_set').val('0');
		$('#sarly_s_set').prop('readonly', true); // 첫 번째는 수정 불가
		// 첫 번째 조건의 기본값 설정
		$('#sarly_e_set').val('');
		$('#sarly_set').val('');
	} else {
		$('#sarly_s_set').val(currencyNum(maxEndValue));
		$('#sarly_s_set').prop('readonly', true); // 자동 설정된 값은 수정 불가
		
		// 패턴 예측값이 있으면 자동 입력
		if (prediction) {
			$('#sarly_e_set').val(currencyNum(prediction.nextEnd));
			$('#sarly_set').val(currencyNum(prediction.nextSet));
		} else {
			$('#sarly_e_set').val('');
			$('#sarly_set').val('');
		}
	}
	
	$('#add_sarly_mgmt_sno').val($('#sarly_mgmt_sno').val());
	$('#add_sarly_pmt_mthd').val($('#sarly_pmt_mthd').val());
	$("#sarly_add_cond").modal("show");
}

function validate() {
	const selectedText = $('#tchr_id option:selected').text();

	if (selectedText === "강사 선택") {
		alert("강사를 선택해주세요.");
		$('#tchr_id').focus();
		return false;
	}

	const checkedCount = $('input[name="cate1_chk[]"]:checked').length;

	if (checkedCount === 0) {
		alert("대분류를 선택해주세요.");
		
		const target = $('#cate1_all');
		target.focus(); // 포커스 설정
		$('html, body').animate({
			scrollTop: $('#cate1_all').offset().top - 100
		}, 300); // 선택 영역으로 스크롤 이동
		return false;
	}

	const selectedVat = $('input[name="vat_yn"]:checked').val();

	if (!selectedVat) {
		alert("부가세 포함 여부를 선택해주세요.");

		const target = $('#radioVatYn1');
		target.focus(); // 포커스
		$('html, body').animate({
			scrollTop: target.offset().top - 100
		}, 300);

		return false;
	}

	const selectedVal = $('input[name="sarly_pmt_cond"]:checked').val();
	

	if (!selectedVal) {
		alert("급여 지급조건을 선택해주세요.");
		const target = $('#radioSarlyPmtCond1');
		target.focus();
		$('html, body').animate({
			scrollTop: target.offset().top - 100
		}, 300);
		return false;
	}

	// '기본급'이 선택된 경우, 금액 입력 유효성 검사 추가
	if (selectedVal === "03") {
		const amt = parseInt($('#sarly_basic_amt').val().replace(/,/g, ''), 10);

		if (isNaN(amt) || amt <= 0) {
			alert("기본급 금액을 입력해주세요.");
			const target = $('#sarly_basic_amt');
			target.focus();
			$('html, body').animate({
				scrollTop: target.offset().top - 100
			}, 300);
			return false;
		}
	}

	const pmtMthdVal = $('#sarly_pmt_mthd').val(); // select2 원본 select 태그 ID

	// 전체강사 또는 개인강사일 때 → 지급방법 선택 여부 검사
	if (selectedVal === "01" || selectedVal === "02") {
		// 지급방법이 선택되지 않았거나 기본 안내 옵션일 경우
		if (!pmtMthdVal || pmtMthdVal === "" || pmtMthdVal === "지급방법") {
			alert("수당 지급방법을 선택해주세요.");

			// select2가 초기화된 상태에서 열기
			$('#sarly_pmt_mthd').select2('open');

			// 포커스 영역으로 스크롤 이동
			$('html, body').animate({
				scrollTop: $('#sarly_pmt_mthd').offset().top - 100
			}, 300);

			return false;
		}
	}

	return true;
}

function disableAllFields() {
	// 강사선택, 지급방법 disabled 처리
	$('#tchr_id').prop('disabled', true);
	$('#sarly_pmt_mthd').prop('disabled', true);
	
	// 나머지 필드들 disabled 처리
	$('#sarly_aply_s_date').prop('disabled', true);
	$('#sarly_aply_e_date').prop('disabled', true);
	$('input[name="cate1_chk[]"]').prop('disabled', true);
	$('input[name="cate2_chk[]"]').prop('disabled', true);
	$('input[name="vat_yn"]').prop('disabled', true);
	
	// 수당계산시 매출범위 disabled 처리
	$('input[name="sarly_pmt_cond"]').prop('disabled', true);
	$('#sarly_basic_amt').prop('disabled', true);
}

function btn_pre_setting()
{
	if(!validate())
{
	return;
}
	if ( $('#sarly_mgmt_sno').val() != "" )
	{
		$('#btn_add_cond').attr('disabled',false);
		$('#sarly_pmt_mthd').attr('disabled',true);
		
		var params = $("#from_salary_setting").serialize();
    	jQuery.ajax({
            url: '/tmemmain/ajax_salry_setting_base_mod_proc',
            type: 'POST',
            data:params,
            async: false,
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
    				 alertToast('success','수정되었습니다.');
    				 //$('#sarly_mgmt_sno').val(json_result['sarly_mgmt_sno']);
    				 $('#btn_add_cond').attr('disabled',false);
    				 disableAllFields();
    			} else 
    			{
    				alertToast('error','강사수당 설정에 오류가 있습니다.');
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
    	var params = $("#from_salary_setting").serialize();
    	jQuery.ajax({
            url: '/tmemmain/ajax_salry_setting_base_proc',
            type: 'POST',
            data:params,
            async: false,
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
    				 
    				if ( $('#radioSarlyPmtCond3').prop('checked') == true )
                	{
                		alertToast('success','기본급 설정이 완료 되었습니다.');
                	} else 
                	{
                		alertToast('success','조건설정을 추가 할 수 있습니다.');
                	}
    				 
    				 $('#sarly_mgmt_sno').val(json_result['sarly_mgmt_sno']);
    				 $('#btn_add_cond').attr('disabled',false);
    				 disableAllFields();
    				//location.reload();
    			} else 
    			{
    				alertToast('error','강사수당 설정에 오류가 있습니다.');
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
	
}

function disableAddCondButton() {
	$('#btn_add_cond').prop('disabled', true);
}


//Date picker
$('.datepp').datepicker({
    format: "yyyy-mm-dd",	//데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
    autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
    clearBtn : false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
    immediateUpdates: false,	//사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false 
    multidate : false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false 
    templates : {
        leftArrow: '&laquo;',
        rightArrow: '&raquo;'
    }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징 
    showWeekDays : true ,// 위에 요일 보여주는 옵션 기본값 : true
    title: "날짜선택",	//캘린더 상단에 보여주는 타이틀
    todayHighlight : true ,	//오늘 날짜에 하이라이팅 기능 기본값 :false 
    toggleActive : true,	//이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
    weekStart : 0 ,//달력 시작 요일 선택하는 것 기본값은 0인 일요일 
    
    //startDate: '-10d',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
    //endDate: '+10d',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
    //datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
    //daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
    //daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
    //disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
    //calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
    //multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01
    
    language : "ko"	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
});
</script>