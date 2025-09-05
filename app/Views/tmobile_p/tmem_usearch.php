<style>

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

/* 채팅 말풍선 스타일 */
.direct-chat-text {
  position: relative;
  background-color: #f0f0f0;
  border-radius: 10px;
  padding: 10px 15px;
  margin: 5px 0;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* 강사 메시지 (왼쪽) - 사진 쪽 라운드 줄임 */
.direct-chat-msg .direct-chat-text {
  background-color: #FFF2B3 !important;
  color: #333 !important;
  border: 1px solid #e0e0e0 !important;
  border-radius: 4px 18px 18px 18px !important;
  position: relative !important;
}

/* 회원 메시지 (오른쪽) - 사진 쪽 라운드 줄임 */
.direct-chat-msg.right .direct-chat-text {
  background-color: #f0f0f0 !important;
  color: #333 !important;
  border: 1px solid #e0e0e0 !important;
  border-radius: 18px 4px 18px 18px !important;
  position: relative !important;
}

/* 모든 꼭지 요소 완전 제거 */
.direct-chat-msg .direct-chat-text::before,
.direct-chat-msg .direct-chat-text::after,
.direct-chat-msg.right .direct-chat-text::before,
.direct-chat-msg.right .direct-chat-text::after {
  display: none !important;
  content: none !important;
}

/* 회원 메시지 우측 정렬 보정 */
.overlay .direct-chat-msg.right {
  margin-left: 0 !important;
  margin-right: 0 !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}

/* 메시지 영역 전체를 화면 너비로 확장 */
.overlay .direct-chat-messages {
  margin-left: -1.25rem !important;
  margin-right: -1.25rem !important;
  padding: 10px 0 !important;
}

/* 회원 메시지 이미지 우측 끝에 고정 */
.overlay .direct-chat-msg.right .direct-chat-img {
  margin-right: 1.25rem !important;
  margin-left: 10px !important;
}

/* 강사 메시지 이미지 좌측 끝에 고정 */
.overlay .direct-chat-msg:not(.right) .direct-chat-img {
  margin-left: 1.25rem !important;
  margin-right: 10px !important;
}

/* 회원 메시지 말풍선 스타일 조정 */
.overlay .direct-chat-msg.right .direct-chat-text {
  max-width: calc(100% - 60px) !important;
  width: auto !important;
  margin-right: 0 !important;
  margin-left: 0 !important;
}

/* 강사 메시지 말풍선 최대 너비 제한 */
.overlay .direct-chat-msg:not(.right) .direct-chat-text {
  max-width: calc(100% - 60px) !important;
}
</style>

<!-- Main content --><div class="new-title">회원검색</div>
<section class="content">

	
		<div class="row">
			<div class="col-md-12">
                
                <div class="row">
        			<div class="col-md-12">
        				<div class="pad10">
        					<form name="form_find_mem_search_proc" id="form_find_mem_search_proc" onsubmit="return false;">
        					<div class=" card-success">
                                <div class="card-body">
                                	
                                	<div class="form-group">
                                		
                                            <label for="inputName" class="size16">회원명</label>
                                            <input type="text" id="top_search_mem_nm" name="top_search_mem_nm" class="form-control height50" placeholder="회원명을 입력하세요">
                                       
                                    </div>
                                </div>
                            </div>
                            </form>
      
                        </div>
        			</div>
        		</div>
                			
			</div>
		</div>

	
<!-- ############################## MODAL [ SATRT ] #################################### -->
<!-- ============================= [ modal-default START ] ======================================= -->	

<div class="overlay">
    <div class="row">
    	<div class="col_md-12" style='width:100%'>
    		<div class="" id="bottom-menu-area">
                <button type="button" class="close" id="bottom-menu-close" style="margin-right:10px;margin-top:5px;">&times;</button>
                <br />
                <div id='bottom-content'>
					<div class="card-body">
						<div class="input-group input-group-sm" style='margin-bottom:10px;'>
                        	검색된 회원 내역
                    	</div>

                        <div class="direct-chat-messages" id="clas_msg">
                        
                            <ul class="products-list product-list-in-card pl-2 pr-2" id='search_list'>
                            	
                            </ul>
                        
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
</div>


<!-- ============================= [ modal-default END ] ======================================= -->
<!-- ############################## MODAL [ END ] ###################################### -->

	
</section>


     <button type="button" class="btn btn-block bga-main ft-white height60 radius0 " style='height:40px;' onclick="btn_tmem_mem_search();">검색하기</button>



<?=$jsinc ?>

<script>
$(function () {
    $('.select2').select2();
    
    $("#top_search_mem_nm").on("keyup",function(key){
		if(key.keyCode==13) {
			btn_tmem_mem_search();
		}
	});
})

$("#bottom-menu-close").click(function(){
	$('.content').removeClass('modal-open');
});

function btn_tmem_mem_search()
{
	var params = "sv="+$('#top_search_mem_nm').val();
    	jQuery.ajax({
            url: '/api/top_search_proc',
            type: 'POST',
            data:params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
            dataType: 'text',
            beforeSend:function(){
            	$('#load_pre').show();
            },
            complete:function(){
            	setTimeout(() => $('#load_pre').hide(),100);
            },
            error:function(){
            	alert('로그인 인증이 만료 되었거나 처리중 오류가 있습니다. 다시 로그인 하세요.');
            	location.href="/login";
            },
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
     				console.log(json_result);
     				console.log(json_result['search_mem_list'].length);
     				
     				if (json_result['search_mem_list'].length == 0)
     				{
     					alertToast('error','검색된 정보가 없습니다.');
     					return;
     				}
     				
     				if (json_result['search_mem_list'].length == 1)
     				{
      					go_mem_info(json_result['search_mem_list'][0]['MEM_SNO']);
      					console.log(json_result['search_mem_list'][0]);
     					return;
     				}
     				
     				var h_size = $(window).height();
                  	var c_size = h_size - 200;
                  	$('#bottom-menu-area').css("height",h_size+"px");
                  	$('.direct-chat-messages').css("height",c_size+"px");
                 	$('.content').addClass('modal-open')
    				
    				json_result['search_mem_list'].forEach(function (r,index) {
    				
var addList = "";
addList += "<li class='item'>";
addList += "    <div class=''>";
addList += "    <a href='javascript:void(0)' class='product-title'>";
addList += "    	<span class='badge badge-success'>"+r['MEM_STAT_NM']+"</span>";
addList += "    	"+r['MEM_NM']+" ("+r['MEM_ID']+" | "+r['MEM_TELNO_SHORT']+")";
addList += "    </a>";
addList += "    <span class='product-description'>";
addList += "    	<span class='badge badge-info'>예약됨 "+r['EVENT_01']+"건</span>";
addList += "    	<span class='badge badge-warning'>이용중 "+r['EVENT_00']+"건</span>";
addList += "    	<span class='badge badge-danger'>종료됨 "+r['EVENT_99']+"건</span>";
addList += "    	<span style='float:right'>";
addList += "        	<button type='button' class='btn btn-xs bottom-menu' onclick='go_mem_info(\""+r['MEM_SNO']+"\");'><i class='fas fa-chevron-right'></i></button>";
addList += "        </span>";
addList += "    </span>";
addList += "    <span style='font-size:0.8rem;'>";
addList += "     가입일 : "+r['JON_DATETM'];
addList += "    </span>";
addList += "    </div>";
addList += "</li>";
    				
						$('#search_list').append(addList);
					});
					
    			} else 
    			{
    				console.log(json_result);
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

function go_mem_info(sno)
{
	location.href="/api/tmem_mem_event_list/"+sno;
}

// ===================== Modal Script [ START ] ===========================

// ===================== Modal Script [ END ] =============================
</script>