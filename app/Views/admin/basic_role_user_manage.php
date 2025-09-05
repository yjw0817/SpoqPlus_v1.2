
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
				</div>
				<div class="panel-body">
					<div class="row mb-2">
						<label class="form-label col-form-label col-md-2">권한명</label>
						<div class="col-md-3 ps-0">
							<input type="email" class="form-control">
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
			<div class="panel panel-inverse" data-sortable-id="tree-view-1">
				<div class="panel-heading">
					<h4 class="panel-title">사용자목록</h4>
					<div class="my-n1">
						<button id="btnUserPop" type="button" class="btn btn-primary btn-xs">사용자선택</button>
						<button id="btnDel" type="button" class="btn btn-danger btn-xs ms-2px">삭제</button>
					</div>
				</div>
				<div class="panel-body">
					<div class="row mb-2">
						<label class="form-label col-form-label col-md-2">사용자</label>
						<div class="col-md-3 ps-0">
							<input type="text" class="form-control">
						</div>
						<div class="col-md-auto ps-0">
							<button type="button" class="btn btn-inverse" id="btnSearch"><i class="fa fa-search"></i> 검색</button>
						</div>
					</div>
					<div class="com_ta2">
						<table id="gridUser" class="table table-striped table-bordered align-middle text-nowrap table-hover">
						</table>
					</div>
				</div>
			</div>
		</div>
			
	</div>
	
</section>


<?=$jsinc ?>

<script>
	var oTable;
	var userTable;
	$(document).ready(function() {
		$("#btnSearch").click(function () { fnGridList(); });
		$("#btnUserSearch").click(function () { fnUserGridList(); });
		$("#btnUserPop").click(function () { fnUserPop(); });
		$("#btnDel").click(function () { fnDel(); });
		$("#selCdCompany").change(function(){
			fnGridList();
		});
		
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
			url: '/adminmain/getRoleList',
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
    		 	, { "sTitle": "적용회사", "mDataProp": "nm_company", "sWidth": "35%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "사용여부", "mDataProp": "use_yn", "sWidth": "15%", "sClass": "cen", "bSortable": false }
           ]
		});

        $('#grid tbody').off('click').on( 'click', 'tr', function () {
           	oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            fnUserGridList();
            
        });
        
	}

	function fnUserGridList() {
		var data = oTable.$('tr.selected').data();

		if (isEmpty(data)) {
			alert("선택된 권한이 없습니다.");
			return false;
		}      

		var url = '/adminmain/getRoleUserList';

		var tblParam = {};

		tblParam.seqRole = data.seq_role;
        tblParam.cdCompany = $("#cdCompany").val();
		tblParam.strSearch = $("#strSearch").val();
		
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
            "paging" : false,
            "bAutoWidth" : false,
            "destroy" : true,
            "data" : jsonData,
		    "aoColumns":[
		    	{ "sTitle": "<input type='checkbox' id='chkSelAll' />", "sWidth": "10%", "bSearchable": false, "sClass": "cen", "bSortable": false,
                    "render": function (oObj) {
                        return "<input type='checkbox' id='chkSel' name='chkSel' value='' />";
                    }
                }
    		 	, { "sTitle": "부서", "mDataProp": "nmDept", "sWidth": "25%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "직급", "mDataProp": "nmDuty", "sWidth": "20%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "사번", "mDataProp": "noEmp", "sWidth": "20%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "사용자명", "mDataProp": "nmEmp", "sWidth": "25%", "sClass": "cen", "bSortable": false }
           ]
		});

     	// 체크박스 전체 선택
        $("#chkSelAll").unbind("click").click(function (e) {
        	$("#gridUser input[id=chkSel]").prop('checked', $(this).prop("checked"));
        });

	}
	
	// 조직도 팝업
	function fnUserPop(type, seqRole, cdCompany) {
        var data = oTable.$('tr.selected').data();
        
		if (isEmpty(data)) {
			alert("선택된 권한이 없습니다.");
			return false;
		}

		var tblParam = {};
		tblParam.cdCompany = $("#cdCompany").val();
		tblParam.callback = "fnOrgPopCallback";		// callback function
		tblParam.callbackClose = "N";					// callback 호출후 팝업 닫기 여부	
		tblParam.selMode = "u";							// u: 사용자, al: 결재라인, d: 부서 (현재X, 필요시 추가)
		tblParam.selItem = "m";							// s: 단일선택, m: 멀티선택
		
		$.dneOrgPop({
			id : 'orgPop' 
			, width : 1000
			, height : 625
   	        , type: 'iframe'		
   			, headerTitle: '조직도'
   	   		, url: '<c:url value="/cm/org/popup/cmorgpopup010010" />'
   	   	   	, data: tblParam	// 파라미터
   	   	   	, jsonData: userTable.rows().data()	// 이미 선택되어 있는 내용
    	});
	}

	// 조직도 선택 콜백 
	function fnOrgPopCallback(data){

    	if (data.length == 0) {
            alert('선택된 사원이 없습니다.');
            return;
        }
        
		if (!confirm("저장 하시겠습니까?")) {
     		return false;
 		}

		var empArr = new Array(); 
		
		$.each(data, function(index, item){
			empArr.push(item.noEmp);
            
		});

		var data = oTable.$('tr.selected').data();
		var tblParam = {};
		
    	tblParam.list = empArr;
    	tblParam.seqRole = data.seq_role;
    	tblParam.cdCompany = $("#cdCompany").val();

    	var url = '<c:url value="/sm/role/setRoleUserSave" />';

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
	            	$.dnePopColse("orgPop");
	            	fnUserGridList();
	            	
	            } else {
	            	alert("저장 도중 실패하였습니다. 다시 시도해 주세요.");
	            	
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
	
	function fnMenuTreeList(seqRole){
		var tblParam = {};
		tblParam.seqRole = seqRole;
		tblParam.cdCompany = $("#cdCompany").val();

		$.ajax({
			type: 'POST',
			url: '<c:url value="/sm/role/getMenuTreeList" />',
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

    	var url = '<c:url value="/sm/role/setRoleDelete" />';

    	$.ajax({
            type: 'POST',
			url: url,
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
    		success: function(res){
    			if (res.result > 0) {
    				alert("삭제 되었습니다.");
    				fnGridList();
    				
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
            $tr = $(this).closest("tr");

            var tblParam = {};
            tblParam.seqRole = $tr.data("seq_role");

            returnValue.push(tblParam);
        });

    	return returnValue;

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

 	// 사용자 삭제
	function fnDel() {
		var chkSels = $("#gridUser input[id=chkSel]:checkbox:checked");
    	if (chkSels.length == 0) {
            alert('삭제할 사용자를 선택해 주세요.');
            return;
        }

    	if (!confirm('삭제 하시겠습니까?')) {
            return;
        }
    	
    	var noEmps = new Array();
   		var $tr;
		var seqRole;
		var cdCompany;
		
    	chkSels.each(function (row) {
            $tr = $(this).closest("tr");
            var data = userTable.row($tr).data();
            noEmps.push(data.noEmp);

            if (row == 0) {
            	seqRole = data.seqRole;
        		cdCompany = data.cdCompany;
        	}
            
        });

    	var tblParam = {};
    	tblParam.noEmps = noEmps;
    	tblParam.seqRole = seqRole;
        tblParam.cdCompany = cdCompany;
        
    	var url = '<c:url value="/sm/role/deleteRoleUser" />';

    	$.ajax({
            type: 'POST',
			url: url,
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
    		success: function(res){
    			if (res.result > 0) {
    				alert("삭제 되었습니다.");
    				fnUserGridList();
    				
    			} else {
   				 	alert("삭제에 실패했습니다.");
   				 	
    			}
    		}, error: function () {
                alert("작업이 실패했습니다.");
            }

        });
    	
	}
	
</script>
