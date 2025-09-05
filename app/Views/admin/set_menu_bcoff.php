<style>
	
</style>
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
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">지점 리스트</h4>
				</div>
				<div class="panel-body">
					<div class="row mb-2">
						<!-- 지점명 검색 -->
						<label class="form-label col-form-label col-auto">회사명:</label>
						<select class="form-select w-25" id="compCd">
							<option value="">전체</option>
							<?php foreach ($company_list as $r) : ?>
							<?php if($r != '') :?>
								<option value="<?php echo $r['COMP_CD']?>" ><?php echo $r['COMP_NM']?></option>
							<?php endif; ?>
							<?php endforeach; ?>
						</select>

						<!-- 회사명 선택 -->
						<label class="form-label col-form-label col-auto">지점명:</label>
						<div class="col-md-3 ps-0">
							<input type="text" id="bcoffNm" class="form-control enter">
						</div>

						<!-- 검색 버튼 -->
						<div class="col-md-auto ps-0">
							<button type="button" class="btn btn-inverse" id="btnSearch">
								<i class="fa fa-search"></i> 검색
							</button>
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
<!-- ============================= [ modal-default END ] ======================================= -->

<?=$jsinc ?>

<script>
	var mTree;
	var oTable;
	$(document).ready(function() {
		$("#btnSearch").click(function () { fnGridList(); });
		$("#btnSaveRoleMenu").click(function () { fnSaveBcoffMenu(); });
		$("#compCd").change(function() {
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

	
	// 데이터 조회
	function fnGridList(nowPage) {
		
		var tblParam = {};

		tblParam.compCd = $("#compCd").val();
		tblParam.bcoffNm = $("#bcoffNm").val();
		
		$.ajax({
			type: 'POST',
			url: '/adminmain/getCompBcoffList',
    		dataType:'json',
    		data:JSON.stringify(tblParam),
    		contentType:'application/json; charset=utf-8',
			async : false,
			success : function(res) {
				fnGridListBind(res.result);
				fnMenuTreeList($("#compCd").val());
				
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
            	$(nRow).data('COMP_CD', aData.COMP_CD);
				$(nRow).data('BCOFF_CD', aData.BCOFF_CD);
                return nRow;
            },
		    "aoColumns":[   { "sTitle": "회사명", "mDataProp": "COMP_NM", "sWidth": "2%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "회사코드", "mDataProp": "COMP_CD", "sWidth": "2%", "sClass": "cen", "bSortable": false }
				, { "sTitle": "지점명", "mDataProp": "BCOFF_NM", "sWidth": "2%", "sClass": "cen", "bSortable": false }
    		 	, { "sTitle": "지점코드", "mDataProp": "BCOFF_CD", "sWidth": "2%", "sClass": "cen", "bSortable": false }
				
           ]
		});

        $('#grid tbody').off('dblclick').on('dblclick', 'tr', function () {
            var data = oTable.row(this).data();
            // fnPop("U", data.COMP_NM, data.COMP_CD, data.use_yn);
            
        });

        $('#grid tbody').off('click').on( 'click', 'tr', function () {
           	oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

            var data = oTable.row(this).data();
            fnMenuTreeList(data.COMP_CD, data.BCOFF_CD);
            
        });
        
	}

	function fnMenuTreeList(compCd, bcoffCd){
		var tblParam = {};
		tblParam.compCd = compCd;
		tblParam.bcoffCd = bcoffCd;

		$.ajax({
			type: 'POST',
			url: '/adminmain/getMenuTreeListAtBcoff',
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
			debugger;
            $tr = $(this).closest("tr");

            var tblParam = {};
            tblParam.seq_role = $tr.data("seq_role");

            returnValue.push(tblParam);
        });

    	return returnValue;

    }

	// 저장버튼 클릭
	function fnSaveBcoffMenu() {
		var data = oTable.$('tr.selected').data();

        
		if (isEmpty(data)) {
			alert("선택된 권한이 없습니다.");
			return false;
		}      

		if (!confirm("저장 하시겠습니까?")) {
     		return false;
 		}

		var url = '/adminmain/saveBcoffMenu';

		var tblParam = {};

		tblParam.bcoffCd = data.BCOFF_CD;
		tblParam.compCd = data.COMP_CD;
		tblParam.bcoffMenuList = fnGetSaveChkList();
		
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

	function fnLoadData(type, nm_role, seq_role, use_yn)
	{
		$("#type").val(type);
		$("#nm_role").val(nm_role);
		$("#seq_role").val(seq_role);
		iCheck.radioSet("use_yn", use_yn);
	}
	
</script>
