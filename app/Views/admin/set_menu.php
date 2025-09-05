<style>
</style>
<!-- begin icheck -->
<link rel="stylesheet" type="text/css" href="/dist/css/icheck.css">
<script type="text/javascript" src="/dist/js/icheck.min.js"></script>
<!-- end icheck -->

<?php
	$sDef = SpoqDef();
?>

<!-- Main content -->
<section class="content">
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ############################## MODAL [ END ] ###################################### -->
	<!-- CARD HEADER [START] -->
	<h1 class="page-header"><?php echo $title ?></h1>	
	<div class="row">
		<div class="col-xl-5">
			<div class="panel panel-inverse" >
				<div class="panel-heading">
					<h4 class="panel-title">등록 메뉴</h4>
				</div>
				<!-- CARD BODY [START] -->
				<div class="panel-body">
					<div class="row mb-2">
						<label class="form-label col-form-label col-md-2">사용구분:</label>
						<select class="form-select w-25" id="useFor">
							<option value="ALL">전체</option>
							<?php foreach ($sDef['MENU_GROUP'] as $r => $v) : ?>
								<option value="<?php echo $r ?>"><?php echo $v ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="pdd-top-20"></div>
					<!-- PHP에서 직접 렌더링한 트리 삽입 -->
					<div id="jstree-ajax">
						<!-- <ul>
							<li id="root">
								<a href="#" class="jstree-anchor">
									<i class="jstree-icon jstree-themeicon fa fa-folder text-warning fa-lg jstree-themeicon-custom" role="presentation"></i> ROOT
								</a>
							</li>
						</ul> -->
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-7">
		<div class="panel panel-inverse table-responsive" data-sortable-id="tree-view-2">
			
			<div class="panel-heading">
				<h4 class="panel-title">메뉴설정</h4>
				<div class="my-n1">
					<button id="btnNew" type="button" class="btn btn-primary btn-xs">신규</button>
					<button id="btnSave" type="button" class="btn btn-success btn-xs ms-2px">저장</button>
					<button id="btnDel" type="button" class="btn btn-danger btn-xs ms-2px">삭제</button>
				</div>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<!-- hiddenfield -->
					<input type="hidden" id="cdCompany" />
					<input type="hidden" id="menuLevel" />
					<input type="hidden" id="insertingMenuLevel"/>
					<input type="hidden" id="parCdMenu" />
					<input type="hidden" id="type" />

					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="parNmMenu">상위메뉴 :</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" id="parNmMenu" name="parNmMenu" data-parsley-required="true"  readonly>
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="module">모듈 :</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" id="module" name="module" data-parsley-required="true" readonly>
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="cdMenu">메뉴 ID :</label>
						<div class="col-lg-10">
							<input class="form-control" type="url" id="cdMenu" name="cdMenu"  readonly>
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="nmMenu">메뉴명 :</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" id="nmMenu" name="nmMenu">
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="urlPath">메뉴URL :</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" id="urlPath" name="urlPath" >
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="menuSort">메뉴정렬 :</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" id="menuSort" name="menuSort">
						</div>
					</div>
					<div class="form-group row mb-3">
						
						<label class="col-lg-2 col-form-label form-label" for="menuSort">아이콘 :</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" id="icon" name="icon">
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="menuSort">CSS class :</label>
						<div class="col-lg-10">
							<input class="form-control" type="text" id="color" name="color">
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="menuSort">설명 :</label>
						<div class="col-lg-10">
							<textarea class="form-control" rows="3" id="desc" name="desc"></textarea>
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="message">메뉴 사용여부 :</label>
						<div class="col-md-9 pt-2">
							<div class="form-check form-check-inline ">
								<label class="radio-inline i-checks">
									<input class="form-check-input" type="radio" name="useYn" value="Y" checked > <i></i> 사용
								</label>
							</div>
							<div class="form-check form-check-inline ">
								<label class="radio-inline i-checks">
									<input class="form-check-input" type="radio" name="useYn" value="N" > <i></i> 미사용
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row mb-3">
						<label class="col-lg-2 col-form-label form-label" for="message">사용구분 :</label>
						<div class="col-md-9 pt-2">
							<?php $loopIndex = 0; // 루프 인덱스 초기화 ?>
							<?php foreach ($sDef['MENU_GROUP'] as $r => $v) : ?>
								<div class="form-check form-check-inline">
									<label class="radio-inline i-checks">
										<input class="form-check-input" type="radio" name="useFor" value="<?php echo $r ?>" <?php echo ($loopIndex === 0) ? 'checked' : ''; ?> > 
										<i></i> <?php echo $v ?>
									</label>
								</div>
								<?php $loopIndex++; // 루프 인덱스 증가 ?>
							<?php endforeach; ?>
						</div>

					</div>
				</div>
			</div>
		</div>	
	</div>
</section>


<?=$jsinc ?>

<script>
	var mTree;
	$(document).ready(function() {
		$("#btnNew").click(function () { fnNew(); });
		$("#btnSave").click(function () { fnSave(); });
		$("#btnDel").click(function () { fnDel(); });
		$("#useFor").change(function () { fnMenuTreeList();});
		var result = JSON.parse('<?= addslashes($result) ?>');

		fnControlInit();
		// fnMenuTreeList();
		// 회사 root 추가
		var root = {};
		root.id = "root";
		root.text = "ROOT";
		root.parent = "#";
		root.type = "root";
		result.unshift(root);
		fnMenuTreeListBind(result);
		$("#menuSort").click(function() {
			if ($(this).val().trim() === "") {
				$(this).val($("#cdMenu").val());
			}
		});
		$("#menuSort").focusin(function() {
			if ($(this).val().trim() === "") {
				$(this).val($("#cdMenu").val());
			}
		});

	});

	function fnControlInit() {
		mTree = $("#jstree-ajax").jstree({
		    "plugins": ["state", "types"],
		    "core": {
		        "themes": {
		            "responsive": false
		        },
		        "check_callback": true,
		        "data": []
		    },
		    "types": {
		        "default": {
		            "icon": "fa fa-folder text-warning fa-lg"
		        },
		        "file": {
		            "icon": "fa fa-file text-warning fa-lg"
		        }
		    }
		});
		
		$("#jstree-ajax").bind("select_node.jstree", function (event, data) { 
			fnMenuClick(data);
			
		});
		
		$("#jstree-ajax").bind("refresh.jstree", function (e, data) { 
			$(this).jstree("open_all");
		});
		
		// TODO 회사코드 가져오는 부분 추가 필요
	}
	
	function fnMenuTreeList(){
		// $("#cdCompany").val($("#selCdCompany").val());
		$("#menuLevel").val('');
		var tblParam = {};
		tblParam.useFor = $("#useFor").val();

		$.ajax({
			type: 'POST',
			url: '/adminmain/getMenuTreeList',
			dataType: 'json',
			data: JSON.stringify(tblParam),
			contentType: 'application/json; charset=utf-8',
			async: false,
			success: function (res) {

				// Ensure res.result is an array
				if (!res.result || !Array.isArray(res.result)) {
					console.warn("Invalid result format. Initializing as an empty array.");
					res.result = [];
				}

				// `null` 또는 `undefined` 요소 제거
				res.result = res.result.filter(item => item !== null && item !== undefined);

				// `toString()`을 호출하기 전에 체크
				res.result.forEach((item, index) => {
					if (!item || typeof item.toString !== 'function') {
						console.warn(`Invalid item at index ${index}:`, item);
					}
				});
				// 회사 root 추가
				var root = {};
					root.id = "root";
					root.text = "ROOT";
					root.parent = "#";
					root.type = "root";
				res.result.unshift(root);
				fnMenuTreeListBind(res.result);
			},
			error: function (data) {
				console.error("AJAX Error:", data);
			}
		});
	}

	function fnMenuTreeListBind(data) {
		mTree.jstree(true).settings.core.data = data;
		mTree.jstree(true).refresh();
		
	}

	function fnMenuClick(data) {
		if (data.node.id == 'root') {
			$("#menuTbl input").attr("readonly", true);
			$("#menuLevel").val("0");
			$("#insertingMenuLevel").val("1");
			$("#module").val("");
			$("#cdMenu").val("");
			$("#nmMenu").val("");
			$("#urlPath").val("");
			$("#menuSort").val("");
			$("#parNmMenu").val("#");
			$("#parCdMenu").val("");
			$("#type").val("");
			$("#icon").val("");
			$("#color").val("");
			$("#desc").val("");
			$("#menuTbl input").val("");
			$("#menuTbl input").attr("readonly", false);
			$("#module").attr("readonly", true);
			$("#parNmMenu").attr("readonly", true);
			$("#cdMenu").attr("readonly", true);

				
		} else {
			$("#menuTbl input").attr("readonly", false);
			$("#module").attr("readonly", true);
			$("#parNmMenu").attr("readonly", true);
			$("#type").val("U");
			fnMenuInfo(data.node.id);
			
		}
		
	}

	// 메뉴 정보 조회
	function fnMenuInfo(cdMenu) {
		var tblParam = {};
		tblParam.cdMenu = cdMenu;
		
		$.ajax({
			type: 'POST',
			url: '/adminmain/getMenuInfo',
			dataType: 'json',
			data: JSON.stringify(tblParam),
			contentType: 'application/json; charset=utf-8',
			async: false,
			success: function (res) {
				console.log("Response Data:", res);
				// Ensure res.result is an array
				if (!res.result || !Array.isArray(res.result)) {
					console.warn("Invalid result format. Initializing as an empty array.");
					res.result = [];
				}
				// `null` 또는 `undefined` 요소 제거
				res.result = res.result.filter(item => item !== null && item !== undefined);

				// `toString()`을 호출하기 전에 체크
				res.result.forEach((item, index) => {
					if (!item || typeof item.toString !== 'function') {
						console.warn(`Invalid item at index ${index}:`, item);
					}
				});
				if (res.result != null) {
					
					$("#cdMenu").attr("readonly", true);
					
					$("#cdMenu").val(res.result[0].cd_menu);
				 	$("#nmMenu").val(res.result[0].nm_menu);
					$("#menuLevel").val(res.result[0].menu_level);
					$("#insertingMenuLevel").val(res.result[0].menu_level);
					$("#parCdMenu").val(res.result[0].par_cd_menu);
					$("#parNmMenu").val(res.result[0].par_nm_menu);					
					$("#icon").val(res.result[0].icon);
					$("#color").val(res.result[0].color);
					$("#desc").val(res.result[0].desc);
					$("#module").val(res.result[0].module);
					$("#urlPath").val(res.result[0].url_path);
					$("#menuSort").val(res.result[0].menu_sort);
					$("#type").val("U");
					iCheck.radioSet("useYn", res.result[0].use_yn);
					iCheck.radioSet("useFor", res.result[0].use_for);
					
				}
			},
			error : function(data) {
				try {
					if (data.status == 403) {
						alert(data.responseJSON.message);
						fnLoginRedirect();

					} else {
						alert("작업에 실패 하였습니다.1");
						console.log("error:::" + data);
					}
					
				}
				catch(e) {
					alert("작업에 실패 하였습니다.2");
					
				}
				
			}
		});
	}

	function getNewCdMenu(parentId) {
		let maxCdMenu = 0;
		let prefix = parentId.replace(/\d/g, ""); // 알파벳 부분 추출
		let maxNumericPart = 0;

		// 부모 노드의 모든 자식 노드 찾기
		$(`#${parentId} > ul > li`).each(function() {
			let childId = $(this).attr('id'); // 자식 노드 ID 가져오기
			let numericPart = parseInt(childId.replace(/\D/g, ""), 10); // 숫자 부분만 추출

			if (!isNaN(numericPart) && numericPart > maxNumericPart) {
				maxNumericPart = numericPart;
			}
		});

		// 숫자 부분이 없으면 기본값 설정
		let newNumericPart = maxNumericPart ? maxNumericPart + 10 : parseInt(parentId.replace(/\D/g, "")) + 10;

		// 새로운 ID 반환 (원래 접두사를 유지)
		return prefix + String(newNumericPart).padStart(parentId.length - prefix.length, "0");
	}



	// 신규버튼 클릭
	function fnNew() {
		debugger
		// fncValidate();
		if ($("#menuLevel").val() == ""){
            alert("선택된 메뉴가 없습니다.");
            return false;
        }
		$("#cdMenu").attr("readonly", false);
		$("#parNmMenu").attr("readonly", true);
		let newCdMenu = "";
		if($("#cdMenu").val() != "")
			newCdMenu = getNewCdMenu($("#cdMenu").val());
		if (isEmpty($("#cdMenu").val())) {
			
			$("#module").attr("readonly", false);
			$("#cdMenu").attr("readonly", false);
			$("#nmMenu").attr("readonly", false);
			$("#urlPath").attr("readonly", false);
			$("#menuSort").attr("readonly", false);
			
		}

		if($("#type").val() != "I")
		{
			$("#parCdMenu").val($("#cdMenu").val());
			$("#parNmMenu").val($("#nmMenu").val());
		}
		
		$("#type").val("I");      
		$("#cdMenu").val(newCdMenu);
	 	$("#nmMenu").val("");
		$("#urlPath").val("");
		$("#menuSort").val("");
		$("#icon").val("");
		$("#color").val("");		
		$("#desc").val("");
		iCheck.radioSet("useYn", "Y");
		iCheck.radioSet("useFor", $("#useFor").val());

	}

	// 저장버튼 클릭
	function fnSave() {
		if (!fncValidate()) {		// 필수값 체크
            return false;
        }
		if (!confirm("저장 하시겠습니까?")) {
            return false;
        }

		var url = '/adminmain/ajax_setMenuSave';

		var tblParam = {};

		tblParam.type = $("#type").val();
		tblParam.cdCompany = $("#cdCompany").val();
		var menuLevel = $("#menuLevel").val();
		if($("#type").val() == "I")
		{
			try {
			menuLevel = parseInt(menuLevel) + 1;
			} catch (e) {
				menuLevel = 1;
			}
		}
		
		
		tblParam.menuLevel = menuLevel;
		tblParam.parCdMenu = $("#parCdMenu").val();
		tblParam.parNmMenu = $("#parNmMenu").val();
		tblParam.module = $("#module").val();
		tblParam.cdMenu = $("#cdMenu").val();
		tblParam.nmMenu = $("#nmMenu").val();
		tblParam.urlPath = $("#urlPath").val();
		tblParam.menuSort = $("#menuSort").val();
		tblParam.icon = $("#icon").val();
		tblParam.color = $("#color").val();
		tblParam.desc = $("#desc").val();
		tblParam.useYn = iCheck.radioGet("useYn");
		tblParam.useFor = iCheck.radioGet("useFor");
		tblParam.type = $("#type").val();
		$.ajax
		({
			 type: "POST"
			, contentType: "application/json; charset=utf-8"
			, dataType: "json"
			, url: url
			, async: false
			, data: JSON.stringify(tblParam)
			, success: function (res) {
				if(res.result > 0){
	            	fnMenuTreeList();
	            	fnMenuInfo($("#cdMenu").val());
	            	alert("저장되었습니다.");
				} else if(res.result == -1){
					alert("중복된 메뉴ID가 존재 합니다. ");
					
	            } else {
	            	alert("변경사항이 없습니다.");
	            	
	            }
			}
			, error : function(data) {
				try {
					if (data.status == 403) {
						alert(data.responseJSON.message);
						fnLoginRedirect();

					} else {
						alert("작업에 실패 하였습니다.");
						console.log("error:::" + data);
					}
					
				}
				catch(e) {
					alert("작업에 실패 하였습니다.");
					
				}
				
			}
		});
		
	}

	// 삭제 버튼 클릭
	function fnDel() {
		if (!confirm("삭제 하시겠습니까?")) {
            return false;
        }

		var url = '/adminmain/ajax_setMenuDelete';

		var tblParam = {};

		tblParam.cdMenu = $("#cdMenu").val();

		$.ajax
		({
			 type: "POST"
			, contentType: "application/json; charset=utf-8"
			, dataType: "json"
			, url: url
			, async: false
			, data: JSON.stringify(tblParam)
			, success: function (res) {
				if(res.result > 0){
	            	alert("삭제 되었습니다.");
	            	fnMenuTreeList();
	            	fnMenuInfo($("#cdMenu").val());
	            	
				} else if(res.result == -9){
					alert("하위 메뉴가 있는경우 삭제가 불가 합니다.");
					
	            } else {
	            	alert("삭제 도중 실패하였습니다. 다시 시도해 주세요.");
	            	
	            }
			}
			, error : function(data) {
				try {
					if (data.status == 403) {
						alert(data.responseJSON.message);
						fnLoginRedirect();

					} else {
						alert("작업에 실패 하였습니다.");
						console.log("error:::" + data);
					}
					
				}
				catch(e) {
					alert("작업에 실패 하였습니다.");
					
				}
				
			}
		});
        
	}

	// 값체크
    function fncValidate() {
        var cdCompany = $("#cdCompany").val();
        var module = $("#module").val();
        var cdMenu = $("#cdMenu").val();
        var nmMenu = $("#nmMenu").val();
        var menuLevel = $("#menuLevel").val();

        if (menuLevel == ""){
            alert("선택된 메뉴가 없습니다.");
            return false;
        }
        
        // if (!$.trim(cdCompany)) {
        //     alert("선택된 회사가 없습니다. 회사를 선택 후 검색 해 주세요.");
        //     return false;
        // }

        // TODO 모듈은 추후에 공통 코드에 넣어 놓고 selectbox 로 수정
        if (!$.trim(module)) {
        	alert("모듈명을 선택 해 주세요.");
            $("#module").focus();
            return false;
        }

        if (!$.trim(cdMenu)) {
        	alert("메뉴ID를 입력 해 주세요.");
            $("#cdMenu").focus();
            return false;
        }
        
        if (!$.trim(nmMenu)) {
        	alert("메뉴명을 입력 해 주세요.");
            $("#nmMenu").focus();
            return false;
        }

        return true;
        
    }
	
</script>