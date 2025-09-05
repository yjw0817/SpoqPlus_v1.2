
<!-- begin icheck -->
<link rel="stylesheet" type="text/css" href="/dist/css/icheck.css">
<script type="text/javascript" src="/dist/js/icheck.min.js"></script>
<style>
	.com_ta2, .table-responsive {
		max-width: 100%;
		overflow-x: auto;  /* 넘칠 경우 스크롤 추가 */
	}
	#gridEmployee, #gridUser {
		table-layout: fixed;  /* 테이블의 열 크기를 고정 */
		width: 100%;  /* 부모 요소에 맞게 설정 */
		max-width: 100%;
		word-wrap: break-word; /* 긴 텍스트 자동 줄바꿈 */
		overflow-x: auto;  /* 가로 스크롤 */
	}
</style>
<!-- end icheck -->
<?php
	$sDef = SpoqDef();
?>
<input type="hidden" id="sel_seq_role" />
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
				</div>
				<div class="panel-body">
					<div class="row mb-2">
						<label class="form-label col-form-label col-md-2">권한명</label>
						<div class="col-md-3 ps-0">
							<input type="text" class="form-control">
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
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">권한 사용자목록</h4>
					<div class="my-n1">
						<button id="btnEmployeePop" type="button" class="btn btn-primary btn-xs">사용자선택</button>
						<button id="btnDel" type="button" class="btn btn-danger btn-xs ms-2px">삭제</button>
					</div>
				</div>
				<div class="panel-body">
					<div class="row mb-2">
						<label class="form-label col-form-label col-md-2">검색어</label>
						<div class="col-md-3 ps-0">
							<input type="text" class="form-control" id="txtEmpSearch">
						</div>
						<div class="col-md-auto ps-0">
							<button type="button" class="btn btn-inverse" id="btnEmpSearch"><i class="fa fa-search"></i> 검색</button>
						</div>
					</div>
					<div class="com_ta2 table-responsive">
						<table id="gridUser" class="table table-striped table-bordered align-middle text-nowrap table-hover">
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="modal fade" id="search_user_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h4 class="modal-title">사용자 선택</h4> 
                <button type="button" class="close3"  data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div class="row mb-2">
					<label class="form-label col-form-label col-md-2">검색어</label>
					<div class="col-md-3 ps-0">
						<input type="text" id="sEmployee" class="form-control">
					</div>
					<div class="col-md-auto ps-0">
						<button type="button" class="btn btn-inverse " id="btnTxtSearch"><i class="fa fa-search"></i> 검색</button>
						<button type="button" class="btn btn-inverse btn-primary" id="btnEmpSave" data-bs-dismiss="modal">선택반영</button>
					</div>
				</div>
				<span class="size12 mt10 mb10"> * 해당 권한에 이미 등록되어 있는 직원은 목록에서 제외됩니다.</span>
				<div class="com_ta2 mt10">
					<table id="gridEmployee" class="table table-striped table-bordered align-middle text-nowrap table-hover">
					</table>
				</div>
            	<!-- FORM [END] -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<?=$jsinc ?>

<script>
	var oTable;
	var userTable;


	$(document).ready(function() {
		$("#btnSearch").click(function () { fnGridList(); });
		$("#btnUserSearch").click(function () { fnUserGridList(); });
		$("#btnEmployeePop").click(function () { fnEmployeePop(); });
		$("#btnEmpSearch").click(function() { 
			fnUserGridList($("#sel_seq_role").val()); });
		$("#btnTxtSearch").click(function(){fnSearchEmployee()});
		$("#btnEmpSave").click(function(){fnEmpSave()});
		$("#sEmployee").on("keyup", function(event) {
			if (event.key === "Enter" || event.keyCode === 13) {
				event.preventDefault();  // 기본 동작 방지 (폼 제출 방지)
				fnSearchEmployee();  // 검색 함수 실행
			}
		});
		$("#txtEmpSearch").on("keyup", function(event) {
			if (event.key === "Enter" || event.keyCode === 13) {
				event.preventDefault();  // 기본 동작 방지 (폼 제출 방지)
				fnUserGridList($("#sel_seq_role").val());
			}
		});

		
		$("#btnDel").click(function () { fnDel(); });
		
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

     	// 엔터 검색
        $(".enter2").keydown(function (event) {
            if (event.keyCode == 13) {
                event.returnValue = false;
                event.cancelBubble = true;

                fnUserGridList();
            }
        });
        
	}

	// 데이터 조회
	function fnGridList(nowPage) {
		// $("#cdCompany").val($("#selCdCompany").val());
		// $("#nmCompany").val( $("#selCdCompany option:checked").text() );
		
		var tblParam = {};

		$.ajax({
			type: 'POST',
			url: '/rolemanage/getRoleList',
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
			async : false,
			success : function(res) {
				fnGridListBind(res.result);
				fnUserGridListBind([]);
				
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
            	$(nRow).data('seq_role', aData.seqRole);
                return nRow;
            },
		    "aoColumns":[  
    		 	{ "sTitle": "권한명", "mDataProp": "nm_role", "sWidth": "50%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "사용여부", "mDataProp": "use_yn", "sWidth": "15%", "sClass": "cen", "bSortable": false }
           ]
		});

        $('#grid tbody').off('click').on( 'click', 'tr', function () {
           	oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			var data = oTable.row(this).data();
            fnUserGridList(data.seq_role, data.nm_role);
        });
        
	}

	function fnUserGridList(seq_role, nm_role) {
		$("#sel_seq_role").val(seq_role);
		if(nm_role != null)
			$(".modal-title").text("사용자 선택 - " + nm_role);
		var url = '/rolemanage/getRoleUserList';

		var tblParam = {};

		tblParam.seqRole = seq_role;
		tblParam.strSearch = $("#txtEmpSearch").val();
		
		$.ajax
		({
			 type: "POST"
			, contentType: "application/json; charset=utf-8"
			, dataType: "json"
			, url: url
			, async: false
			, data: JSON.stringify(tblParam)
			, success: function (res) {
				fnUserGridListBind(res.result);
				
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

	// 데이터 바인딩
	function fnUserGridListBind(jsonData){
        userTable = $('#gridUser').DataTable({
        	"bSort" : false,
            "select" : true,
            "dom":"<r>tp",
            "paging" : true,
            "bAutoWidth" : false,
            "destroy" : true,
            "data" : jsonData,
			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
            	$(nRow).data('MEM_SNO', aData.MEM_SNO);
                return nRow;
            },
		    "aoColumns":[
		    	{ "sTitle": "<input type='checkbox' id='chkSelAll' />", "sWidth": "1%", "bSearchable": false, "sClass": "cen", "bSortable": false,
                    "render": function (oObj) {
                        return "<input type='checkbox' id='chkSel' name='chkSel'  value='' />";
                    }
                }
    		 	, { "sTitle": "권한", "mDataProp": "nm_role", "sWidth": "5%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "회원명", "mDataProp": "MEM_NM", "sWidth": "5%", "sClass": "cen", "bSortable": false }
				, { "sTitle": "직책", "mDataProp": "TCHR_POSN_NAME", "sWidth": "5%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "아이디", "mDataProp": "MEM_ID", "sWidth": "5%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "전화번호", "mDataProp": "MEM_TELNO", "sWidth": "5%", "sClass": "cen", "bSortable": false }
				
           ]
		});
     	// 체크박스 전체 선택
        $("#chkSelAll").unbind("click").click(function (e) {
        	$("#gridUser input[id=chkSel]").prop('checked', $(this).prop("checked"));
        });
	}

	// 조직도 팝업
	function fnSearchEmployee() {
		var url = '/rolemanage/getEmployeeList';

		var tblParam = {};
		tblParam.strSearch = $("#sEmployee").val();
		tblParam.seq_role = $("#sel_seq_role").val();
		$.ajax
		({
			 type: "POST"
			, contentType: "application/json; charset=utf-8"
			, dataType: "json"
			, url: url
			, async: false
			, data: JSON.stringify(tblParam)
			, success: function (res) {
				fnEmployeeGridListBind(res.result);
				
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

	// 조직도 팝업
	function fnEmployeePop() {
		if($("#sel_seq_role").val() == "")
		{
			alert("먼저 권한목록에서 권한을 선택해주세요.");
			return 
		}
		 $("#search_user_form").modal("show");
		var url = '/rolemanage/getEmployeeList';

		var tblParam = {};
		$("#sEmployee").val('');
		tblParam.strSearch = $("#sEmployee").val();
		tblParam.seq_role = $("#sel_seq_role").val();
		
		$.ajax
		({
			 type: "POST"
			, contentType: "application/json; charset=utf-8"
			, dataType: "json"
			, url: url
			, async: false
			, data: JSON.stringify(tblParam)
			, success: function (res) {
				fnEmployeeGridListBind(res.result);
				
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

	// 데이터 바인딩
	function fnEmployeeGridListBind(jsonData) {
		userTable = $('#gridEmployee').DataTable({
			"bSort": false,
			"select": true,
			"dom": "<r>tp",
			"paging": true,
			"bAutoWidth": false,
			"destroy": true,
			"data": jsonData,
			"aoColumns": [
				{
					"sTitle": "<input type='checkbox' id='chkSelAllEmp' />",
					"sWidth": "1%",
					"bSearchable": false,
					"sClass": "cen",
					"bSortable": false,
					"render": function () {
						return "<input type='checkbox' class='chkSel' name='chkSel' />";
					}
				},
				{ "sTitle": "구분", "mDataProp": "MEM_DV", "sWidth": "2%", "sClass": "cen", "bSortable": false },
				{ "sTitle": "회원명", "mDataProp": "MEM_NM", "sWidth": "5%", "sClass": "cen", "bSortable": false },
				{ "sTitle": "직책", "mDataProp": "TCHR_POSN_NAME", "sWidth": "5%", "sClass": "cen", "bSortable": false },
				{ "sTitle": "아이디", "mDataProp": "MEM_ID", "sWidth": "15%", "sClass": "cen", "bSortable": false },
				{ "sTitle": "전화번호", "mDataProp": "MEM_TELNO", "sWidth": "10%", "sClass": "cen", "bSortable": false }
			]
		});

		// Select All Checkboxes Across All Pages
		$("#chkSelAllEmp").unbind("click").click(function () {
			var isChecked = $(this).prop("checked");

			// Select checkboxes on all pages
			userTable.$(".chkSel").prop("checked", isChecked);
		});

		// Uncheck "Select All" if any checkbox is unchecked
		$('#gridEmployee tbody').on("click", ".chkSel", function () {
			if (!$(this).prop("checked")) {
				$("#chkSelAllEmp").prop("checked", false);
			}
			// If all checkboxes are checked, select "Select All"
			else if (userTable.$(".chkSel:checked").length === userTable.$(".chkSel").length) {
				$("#chkSelAllEmp").prop("checked", true);
			}
		});
	}
	
	// 권한 삭제
	function fnDel() {
		debugger;
		var chkSels = $("#gridUser input[id=chkSel]:checkbox:checked");
    	if (chkSels.length == 0) {
            alert('삭제할 행을 선택해 주세요.');
            return;
        }

    	if (!confirm('선택된 행을 삭제 하시겠습니까?')) {
            return;
        }

    	var tblParam = {};
    	tblParam.delList = fnGetDelChkList(chkSels);
		tblParam.seq_role = $("#sel_seq_role").val();

    	var url = '/rolemanage/deleteUserListFromRole';

    	$.ajax({
            type: 'POST',
			url: url,
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
    		success: function(res){
    			if (res.status == "success") {
    				fnUserGridList($("#sel_seq_role").val());
    			} 
				alert(res.message); 
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
            $tr = $(this).closest("tr");

            var tblParam = {};
            tblParam.mem_sno = $tr.data("MEM_SNO");

            returnValue.push(tblParam);
        });

    	return returnValue;

    }

	function fnEmpSave() {
		debugger;
		var selectedRows = [];

		// Get `sel_seq_role` value
		var selSeqRole = $("#sel_seq_role").val(); // Assuming this is a hidden input or dropdown

		if (!selSeqRole) {
			alert("권한이 선택되지 않았습니다.");
			return;
		}

		// Get all checked checkboxes across all pages
		userTable.$(".chkSel:checked").each(function () {
			var rowData = userTable.row($(this).closest("tr")).data();
			if (rowData) {
				selectedRows.push(rowData.MEM_SNO); // Store the MEM_SNO value
			}
		});

		if (selectedRows.length === 0) {
			alert("선택된 항목이 없습니다.");
			return;
		}

		// Confirm before saving
		if (!confirm("선택된 데이터를 저장하시겠습니까?")) {
			return;
		}

		// AJAX request to send data to server
		$.ajax({
			type: "POST",
			url: "/rolemanage/applyEmployeesWithRole", // Server-side API endpoint
			contentType: "application/json; charset=utf-8",
			data: JSON.stringify({
				mem_sno_list: selectedRows,  // Array of selected MEM_SNO
				seq_role: selSeqRole         // Selected Role ID
			}),
			dataType: "json",
			success: function (response) {
				if (response.success) {
					fnUserGridListBind(response.result);
					alert("저장되었습니다.");
					// userTable.ajax.reload(); // Refresh table if using AJAX loading
				} else {
					alert("저장 중 오류가 발생했습니다.");
				}
			},
			error: function (xhr, status, error) {
				console.error("Error:", error);
				alert("서버와의 통신 중 오류가 발생했습니다.");
			}
		});
	}



 	// 저장버튼 클릭
	function fnSave() {
        var data = oTable.$('tr.selected').data();
		var cdCompany = $("#cdCompany").val();

		if (!$.trim(cdCompany)) {
            alert("선택된 회사가 없습니다.");
            return false;
        }
        
		if (isEmpty(data)) {
			alert("선택된 권한이 없습니다.");
			return false;
		}

		if (!confirm("저장 하시겠습니까?")) {
     		return false;
 		}

		var url = '<c:url value="/sm/role/setRoleMenuSave" />';

		var tblParam = {};

		tblParam.seqRole = data.seq_role;
        tblParam.cdCompany = cdCompany;
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
				if(res.result > 0){
	            	alert("저장되었습니다.");

				} else if (res.result == -9) {
					alert("저장 도중 실패하였습니다. 다시 시도해 주세요.");
    				
	            } else {
	            	alert("저장된 내역이 없습니다.");
	            	
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
	
</script>
