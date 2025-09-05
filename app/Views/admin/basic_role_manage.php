
<!-- begin icheck -->
<link rel="stylesheet" type="text/css" href="/dist/css/icheck.css">
<script type="text/javascript" src="/dist/js/icheck.min.js"></script>
<!-- end icheck -->

<?php
	$sDef = SpoqDef();
?>
<input type="hidden" id="cdCompany" />
<input type="hidden" id="nmCompany" />
<!-- Main content -->
<section class="content">
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ############################## MODAL [ END ] ###################################### -->
	<!-- CARD HEADER [START] -->
	<h1 class="page-header"><?php echo $title ?></h1>	
	<div class="row">
		<div class="col-xl-6">
			<div class="panel panel-inverse" >
				
				<div class="panel-heading">
					<h4 class="panel-title">권한목록</h4>
					<div class="my-n1">
						<button id="btnAdd" type="button" class="btn btn-primary btn-xs">추가</button>
						<button id="btnDel" type="button" class="btn btn-danger btn-xs ms-2px">삭제</button>
					</div>
				</div>
				<div class="panel-body">
					<div class="row mb-2">
						<label class="form-label col-form-label col-md-2">권한명</label>
						<div class="col-md-3 ps-0">
							<input type="text" id="nmRole" class="form-control enter">
						</div>
						<div class="col-md-auto ps-0">
							<button type="button" class="btn btn-inverse" id="btnSearch"><i class="fa fa-search"></i> 검색</button>
						</div>
					</div>
					<div class="com_ta2">
						<table id="grid" class="table table-striped table-bordered align-middle text-nowrap table-hover">
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-6">
			<div class="panel panel-inverse" >
				<div class="panel-heading">
					<h4 class="panel-title">등록 메뉴</h4>
					<div class="my-n1">
						<button id="btnSaveRoleMenu" type="button" class="btn btn-primary btn-xs">저장</button>
					</div>
				</div>
				<!-- CARD BODY [START] -->
				<div class="panel-body">
					<!-- <div class="row mt-2 justify-content-between">
						<div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
							<div class="dt-search d-flex align-items-center">
								<label for="dt-search-0" class="me-2">메뉴명:</label>
								<input type="search" class="form-control form-control-sm" id="dt-search-0" style="max-width: 170px;"  placeholder="" aria-controls="data-table-default" data-listener-added_f65aae3d="true">
								<button type="button" class="btn btn-inverse btn-sm px-3 py-1 ms-1" id="btnSearch"><i class="fa fa-search"></i> 검색</button>
							</div>
						</div>
					</div> -->
					<div class="pdd-top-20"></div>
					<div id="jstree-ajax"></div>
				</div>
			</div>
		</div>
	</div>
</section>


<!-- ============================= [ modal-default START ] ======================================= -->	
<div class="modal fade" id="modal_notice_modify_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">권한코드 등록</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
               
            </div>
            <div class="modal-body">
            
            	<!-- FORM [START] -->
            	<form id="notice_modify_form">
					<input type="hidden" name="type" id="type" />
					<div class="row mb-15px">
						<label class="form-label col-form-label col-md-3">권한명</label>
						<div class="col-md-9">
							<input type="text" class="form-control mb-5px" id="nm_role" name="nm_role">
						</div>
					</div>
					<div class="row mb-15px">
						<label class="form-label col-form-label col-md-3">권한코드</label>
						<div class="col-md-9">
							<input type="text" class="form-control mb-5px" id="seq_role" name="seq_role" readonly >
						</div>
					</div>
					<div class="row mb-15px">
						<label class="form-label col-form-label col-md-3">사용여부</label>
						<div class="col-md-9">
							<!-- <div class="icheck-primary d-inline">
								<input type="radio"  name="use_yn" value="Y" />
								<label for="mod_radioNotiTop1">
									<small>사용</small>
								</label>
							</div>
							<div class="icheck-primary d-inline">
								<input type="radio" name="use_yn" value="N" />
								<label for="mod_radioNotiTop2">
									<small>사용안함</small>
								</label>
							</div> -->
							<div class="form-check form-check-inline ">
								<label class="radio-inline i-checks">
									<input class="form-check-input" type="radio" name="use_yn" value="Y" checked > <i></i> 사용
								</label>
							</div>
							<div class="form-check form-check-inline ">
								<label class="radio-inline i-checks">
									<input class="form-check-input" type="radio" name="use_yn" value="N" > <i></i> 미사용
								</label>
							</div>
						</div>
					</div>
            	</form>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="btnSave">수정하기</button>
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- ============================= [ modal-default END ] ======================================= -->

<?=$jsinc ?>

<script>
	var mTree;
	var oTable;
	$(document).ready(function() {
		$("#btnSearch").click(function () { fnGridList(); });
		$("#btnAdd").click(function () { fnPop("I"); });
		$("#btnDel").click(function () { fnDel(); });
		$("#btnSave").click(function () { fnSave(); });
		$("#btnSaveRoleMenu").click(function(){ fnSaveRoleMenu();})
		fnControlInit();
		fnGridList();

	});
	


	function fnControlInit() {
		// TODO 회사코드 가져오는 부분 추가 필요
		// 엔터 검색
        $(".enter").keydown(function (event) {
            if (event.keyCode == 13) {
                event.returnValue = false;
                event.cancelBubble = true;

                fnGridList();
            }
        });
        
		mTree = $("#jstree-ajax").jstree({
		    "plugins": ["wholerow", "checkbox", "types"],
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
		
		$("#jstree-ajax").bind("refresh.jstree", function (e, data) { 
			$(this).jstree("open_all");

			var data = mTree.jstree(true).settings.core.data;

			 // 부모 메뉴
            $.each(data, function(index, item){

				if (item.state == null || !item.state.selected) {
					$(this).jstree("deselect_node", item.id);
					
				} else if (item.state.selected) {
					$(this).jstree("select_node", item.id);
					
				}
                
			});

		});
		
		
	}

	// 추가버튼 클릭
	// function fnPop(type, seqRole, cdCompany) {
	// 	$.dnePop({
	// 		id : 'roleAdd' 
	// 		, width : 500
	// 		, height : 195
   	//         , type: 'iframe'		
   	// 		, headerTitle: '권한코드 등록'
   	//    		, url: '<c:url value="/sm/role/popup/smrolepopup010010" />'	 			// 호출 url
   	//    	   	, data: {"type":type, "seqRole":seqRole, "cdCompany":cdCompany}	// 파라미터
   	   		
    // 	});
	// }
	
	// 데이터 조회
	function fnGridList(nowPage) {
		
		var tblParam = {};

		tblParam.nmRole = $("#nmRole").val();
		
		$.ajax({
			type: 'POST',
			url: '/adminmain/getRoleList',
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
			async : false,
			success : function(res) {
				fnGridListBind(res.result);
				fnMenuTreeList(0);
				
			},
			error : function(data) {
				try {
					if (data.status == 403) {
						alert('a');
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

	// 데이터 바인딩
	function fnGridListBind(jsonData){
        oTable = $('#grid').DataTable({
        	"bSort" : false,
            "select" : true,
            "dom":"<r>tp",
            "paging" : false,
            "bAutoWidth" : false,
            "destroy" : true,
            "data" : jsonData,
            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            	$(nRow).data('seq_role', aData.seq_role);
                return nRow;
            },
		    "aoColumns":[  
		    	{ "sTitle": "<input type='checkbox' id='chkSelAll' />", "sWidth": "10%", "bSearchable": false, "sClass": "cen", "bSortable": false,
                    "render": function (oObj) {
                        return "<input type='checkbox' id='chkSel' name='chkSel' value='' />";
                    }
                }
    		 	, { "sTitle": "권한명", "mDataProp": "nm_role", "sWidth": "50%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "권한코드", "mDataProp": "seq_role", "sWidth": "30%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "사용여부", "mDataProp": "use_yn", "sWidth": "10%", "sClass": "cen", "bSortable": false }
           ]
		});

     	// 체크박스 전체 선택
        $("#chkSelAll").unbind("click").click(function (e) {
        	$("#grid input[id=chkSel]").prop('checked', $(this).prop("checked"));
        });

        $('#grid tbody').off('dblclick').on('dblclick', 'tr', function () {
            var data = oTable.row(this).data();
            fnPop("U", data.nm_role, data.seq_role, data.use_yn);
            
        });

        $('#grid tbody').off('click').on( 'click', 'tr', function () {
           	oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

            var data = oTable.row(this).data();
            fnMenuTreeList(data.seq_role);
            
        });
        
	}

	function fnMenuTreeList(seq_role){
		var tblParam = {};
		tblParam.seq_role = seq_role;
		tblParam.cdCompany = $("#cdCompany").val();

		$.ajax({
			type: 'POST',
			url: '/adminmain/getMenuTreeListWithRole',
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
			async : false,
			success : function(res) {
				
				if (res.result != null) {
					mTree.jstree("deselect_all");
					
					// 회사 root 추가
					var root = {};
					root.id = "root";
					root.text = $("#nmCompany").val();
					root.parent = "#";
					root.type = "root";

					res.result.unshift(root);
					fnMenuTreeListBind(res.result);
					
				}
				
			},
			error : function(data) {
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

	function fnMenuTreeListBind(data) {
		mTree.jstree(true).settings.core.data = data;
		mTree.jstree(true).refresh();
		
	}
	
	// 권한 삭제
	function fnDel() {
		var chkSels = $("#grid input[id=chkSel]:checkbox:checked");
    	if (chkSels.length == 0) {
            alert('삭제할 행을 선택해 주세요.');
            return;
        }

    	if (!confirm('선택된 행을 삭제 하시겠습니까?')) {
            return;
        }

    	var tblParam = {};
    	tblParam.delList = fnGetDelChkList(chkSels);
		
    	var url = '/adminmain/deleteRole';

    	$.ajax({
            type: 'POST',
			url: url,
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
    		success: function(res){
    			if (res.result > 0) {
    				fnGridList();
    				alert("삭제되었습니다.");
    				
    			} else if (res.result == -9) {
    				alert("연결된 메뉴가 존재할 경우 삭제가 불가 합니다.");
    				
    			} else {
   				 	alert("삭제에 실패했습니다.");
   				 	
    			}
    		}, error: function () {
                alert("작업이 실패했습니다.");
            }

        });
    	
	}

   	// 삭제 체크  리스트 셋팅
    function fnGetDelChkList(chkSels) {
    	var returnValue = new Array();
		var $tr;

    	chkSels.each(function (row) {
			debugger;
            $tr = $(this).closest("tr");

            var tblParam = {};
            tblParam.seq_role = $tr.data("seq_role");

            returnValue.push(tblParam);
        });

    	return returnValue;

    }

	// 저장버튼 클릭
	function fnSaveRoleMenu() {
		var data = oTable.$('tr.selected').data();
		var cdCompany = $("#cd_company").val();

        
		if (isEmpty(data)) {
			alert("선택된 권한이 없습니다.");
			return false;
		}      

		if (!confirm("저장 하시겠습니까?")) {
     		return false;
 		}

		var url = '/adminmain/saveRoleMenu';

		var tblParam = {};

		tblParam.seq_role = data.seq_role;
        tblParam.cd_company = cdCompany;
		tblParam.roleMenuList = fnGetSaveChkList();
		
		$.ajax
		({
			 type: "POST"
			, contentType: "application/json; charset=utf-8"
			, dataType: "json"
			, url: url
			, async: false
			, data: JSON.stringify(tblParam)
			, success: function (res) {
				if(res.result["status"] == "success"){
	            	alert(res.result["message"] );
	            } else {
	            	alert(res.result["message"]);
	            	
	            }
			}
			, error: function (data) {
				alert("작업에 실패 하였습니다.");
			}
		});
		
		
	}
	
 	// 저장버튼 클릭
	function fnSave() {
		
		var cdCompany = $("#cdCompany").val();

        
		if ($("#nm_role").val().trim() == "") {
			alert("권한명을 입력하세요.");
			return false;
		}      

		if (!confirm("저장 하시겠습니까?")) {
     		return false;
 		}

		var url = '/adminmain/saveAuth';

		var tblParam = {};
		tblParam.type = $("#type").val();
		tblParam.nm_role = $("#nm_role").val();
		tblParam.seq_role = $("#seq_role").val();
        tblParam.cd_company = cdCompany;
		tblParam.use_yn = iCheck.radioGet("use_yn");

		$.ajax
		({
			 type: "POST"
			, contentType: "application/json; charset=utf-8"
			, dataType: "json"
			, url: url
			, async: false
			, data: JSON.stringify(tblParam)
			, success: function (res) {
				if(res.result["status"] == "success"){
					fnGridList();
	            	alert(res.result["message"] );
					$("#modal_notice_modify_form").modal("hide");
	            } else {
	            	alert(res.result["message"]);
	            	
	            }
			}
			, error: function (data) {
				alert("작업에 실패 하였습니다.");
			}
		});
		
	}

	// 저장 체크  리스트 셋팅
    function fnGetSaveChkList() {
    	var returnValue = new Array();
		var arrSelected = $("#jstree-ajax").jstree(true).get_selected('full',true);
		
		$.each(arrSelected, function(index, item){ 

            // 부모 메뉴
            $.each(item.parents, function(index, item){

            	if (item != 'root' && item != '#' ) {

                	if (returnValue.indexOf(item) < 0) {
                		returnValue.push(item);
                   	}
                    
               	}
                
			});

			// 본 메뉴
			if (item.id != 'root' && item.id != '#' ) {
				if (returnValue.indexOf(item.id) < 0) {
	           		returnValue.push(item.id);
	           		
	           	}
			}	
           	
		}) ;

    	return returnValue;

    }


	// function fnPop(type, seq_role, cd_company) {
	// 	// 실패일 경우 warning error success info question
	// 	alertToast('error','대분류 코드를 입력하세요');
	// 	// data: {"type":type, "seq_role":seq_role, "cd_company":cd_company}	// 파라미터
	// 	ToastConfirm.fire({
	// 		icon: "question",
	// 		title: "  확인 메세지",
	// 		html: "<font color='#000000' >공지사항을 수정 하시겠습니까?</font>",
	// 		showConfirmButton: true,
	// 		showCancelButton: true,
	// 		confirmButtonColor: "#28a745",
	// 	}).then((result) => {
	// 		if (result.isConfirmed) 
	// 		{
	// 			var params = $("#notice_modify_form").serialize();
	// 			jQuery.ajax({
	// 				url: '/tbcoffmain/ajax_notice_modify_proc',
	// 				type: 'POST',
	// 				data:params,
	// 				contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
	// 				dataType: 'text',
	// 				success: function (result) {
	// 					if ( result.substr(0,8) == '<script>' )
	// 					{
	// 						alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
	// 						location.href='/tlogin';
	// 						return;
	// 					}
						
	// 					json_result = $.parseJSON(result);
	// 					if (json_result['result'] == 'true')
	// 					{
	// 						location.reload();
	// 					}
	// 				}
	// 			}).done((res) => {
	// 				// 통신 성공시
	// 				console.log('통신성공');
	// 			}).fail((error) => {
	// 				// 통신 실패시
	// 				console.log('통신실패');
	// 				alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
	// 				location.href='/tlogin';
	// 				return;
	// 			});
	// 		}
	// 	});	
		
	// }

	function fnLoadData(type, nm_role, seq_role, use_yn)
	{
		$("#type").val(type);
		$("#nm_role").val(nm_role);
		$("#seq_role").val(seq_role);
		iCheck.radioSet("use_yn", use_yn);
	}
	
	function fnNewData(type)
	{
		$("#type").val(type);
		$("#nm_role").val('');
		$("#seq_role").val('');
		$("#use_yn").val('');
	}
	function fnPop(type, nm_role, seq_role, use_yn) 
	{
		if(type == "U")
		{
			fnLoadData(type, nm_role, seq_role, use_yn);
		} else
		{
			fnNewData(type);
		}
		$('#modal_notice_modify_form').modal('show');
	}
	
</script>
