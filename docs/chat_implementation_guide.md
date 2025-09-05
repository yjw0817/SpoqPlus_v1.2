# 채팅 기능 구현 가이드 및 업데이트 기록

## 목차
1. [개요](#개요)
2. [채팅 스타일링](#채팅-스타일링)
3. [실시간 업데이트 (폴링)](#실시간-업데이트-폴링)
4. [폴링 vs 웹소켓 비교](#폴링-vs-웹소켓-비교)
5. [파일별 업데이트 내역](#파일별-업데이트-내역)
6. [구현 패턴](#구현-패턴)
7. [주의사항](#주의사항)

## 개요

SpoqPlus 프로젝트의 채팅 기능을 현대적인 UI/UX로 업데이트하고 실시간 메시지 업데이트 기능을 구현했습니다.

### 주요 개선사항
- AdminLTE 기본 스타일 문제 해결
- 우측 정렬 메시지 간격 문제 수정
- 메시지 말풍선 최대 너비 제한
- 실시간 메시지 업데이트 (5초 폴링)
- 프로필 이미지 지원 및 클릭 시 확대 기능
- 스마트 스크롤 (사용자 위치 유지)

## 채팅 스타일링

### 기본 CSS 구조

```css
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
```

### 색상 스키마

#### tmobile_p 폴더 (기본)
- 강사 메시지: 노란색 (#FFF2B3)
- 회원 메시지: 회색 (#f0f0f0)
- 위치: 강사 왼쪽, 회원 오른쪽

#### mobile_p 폴더 (변경됨)
- 강사 메시지: 회색 (#f0f0f0)
- 회원 메시지: 노란색 (#FFF2B3)
- 위치: 강사 오른쪽, 회원 왼쪽

## 실시간 업데이트 (폴링)

### 폴링 구현 패턴

```javascript
// 전역 변수
var chatInterval; // 폴링 인터벌 저장
var lastMessageTime = null; // 마지막 메시지 시간 저장

// 채팅창 열기 함수
function pt_chk(buy_sno) {
    // 기존 인터벌 정리
    if (chatInterval) {
        clearInterval(chatInterval);
        chatInterval = null;
    }
    
    // AJAX로 메시지 로드
    jQuery.ajax({
        url: '/api/ajax_clas_msg',
        type: 'POST',
        data: params,
        success: function(result) {
            // 메시지 렌더링
            $('#clas_msg').html(cmsg);
            
            // 마지막 메시지 시간 저장
            if (json_result['msg_list'].length > 0) {
                lastMessageTime = json_result['msg_list'][json_result['msg_list'].length - 1]['CRE_DATETM'];
            }
            
            // 스크롤 맨 아래로
            $('.direct-chat-messages').scrollTop($('.direct-chat-messages')[0].scrollHeight);
            
            // 5초마다 새 메시지 확인
            chatInterval = setInterval(function() {
                loadNewMessages();
            }, 5000);
        }
    });
}

// 새 메시지 로드 함수
function loadNewMessages() {
    var buy_sno = $('#pt_chk_buy_sno').val();
    if (!buy_sno) return;
    
    // 현재 스크롤 위치 확인
    var messages = $('.direct-chat-messages');
    var isScrolledToBottom = messages[0].scrollHeight - messages.scrollTop() <= messages.height() + 50;
    
    var params = "buy_sno=" + buy_sno;
    if (lastMessageTime) {
        params += "&last_time=" + encodeURIComponent(lastMessageTime);
    }
    
    jQuery.ajax({
        url: '/api/ajax_clas_msg',
        type: 'POST',
        data: params,
        success: function(result) {
            // 새 메시지만 추가
            if (json_result['result'] == 'true' && json_result['msg_list'].length > 0) {
                // 메시지 렌더링 및 추가
                $('#clas_msg').append(cmsg);
                
                // 마지막 메시지 시간 업데이트
                lastMessageTime = json_result['msg_list'][json_result['msg_list'].length - 1]['CRE_DATETM'];
                
                // 자동 스크롤 (사용자가 맨 아래에 있었을 때만)
                if (isScrolledToBottom) {
                    messages.scrollTop(messages[0].scrollHeight);
                }
            }
        }
    });
}

// 채팅창 닫기 처리
$("#bottom-menu-close").click(function(){
    $('.content').removeClass('modal-open');
    // 인터벌 정리
    if (chatInterval) {
        clearInterval(chatInterval);
        chatInterval = null;
    }
});
```

## 폴링 vs 웹소켓 비교

### 폴링 방식 (현재 구현)

**장점:**
- 구현이 간단하고 직관적
- 기존 인프라 변경 불필요
- 방화벽/프록시 문제 없음
- 디버깅이 쉬움
- 서버 부하 예측 가능

**단점:**
- 불필요한 요청 발생 가능
- 실시간성이 상대적으로 떨어짐 (5초 지연)
- 대규모 사용자 시 서버 부하 증가

**서버 부하 계산:**
- 동시 접속자 100명, 5초 폴링 = 분당 1,200 요청
- 동시 접속자 1,000명 = 분당 12,000 요청

### 웹소켓 방식

**장점:**
- 진정한 실시간 통신
- 서버 리소스 효율적
- 양방향 통신 가능
- 낮은 레이턴시

**단점:**
- 별도 WebSocket 서버 필요 (Node.js 등)
- 복잡한 구현과 인프라
- 연결 상태 관리 필요
- 방화벽/프록시 설정 필요

### 권장사항

현재 시스템 규모와 요구사항을 고려할 때 **폴링 방식을 유지**하는 것을 권장합니다.

이유:
1. PT 수업 특성상 동시 채팅 사용자가 제한적
2. 5초 지연이 사용성에 큰 영향 없음
3. 구현 복잡도 대비 이득이 크지 않음
4. 안정성과 유지보수가 중요

## 파일별 업데이트 내역

### /tmobile_p/ 폴더

| 파일명 | 채팅스타일 | 폴링 | 색상 | 위치 | 특이사항 |
|--------|----------|------|------|------|----------|
| teventpt.php | ✅ 최신 | ✅ 구현 | 강사: #FFF2B3<br>회원: #f0f0f0 | 강사: 왼쪽<br>회원: 오른쪽 | 참조 파일 |
| tattdmem.php | ✅ 최신 | ✅ 구현 | 강사: #FFF2B3<br>회원: #f0f0f0 | 강사: 왼쪽<br>회원: 오른쪽 | - |
| tattdmemlist.php | ✅ 최신 | ✅ 추가 | 강사: #FFF2B3<br>회원: #f0f0f0 | 강사: 왼쪽<br>회원: 오른쪽 | 폴링 새로 추가 |
| tmem_attdmemlist.php | ✅ 최신 | ✅ 추가 | 강사: #FFF2B3<br>회원: #f0f0f0 | 강사: 왼쪽<br>회원: 오른쪽 | 폴링 새로 추가 |
| tmem_usearch.php | ✅ 최신 | ❌ 불필요 | - | - | 검색 화면 |
| tmem_mem_event_list.php | ✅ 최신 | ✅ 구현 | 강사: #FFF2B3<br>회원: #f0f0f0 | 강사: 왼쪽<br>회원: 오른쪽 | - |

### /mobile_p/ 폴더

| 파일명 | 채팅스타일 | 폴링 | 색상 | 위치 | 특이사항 |
|--------|----------|------|------|------|----------|
| main2.php | ✅ 최신 | ✅ 구현 | 강사: #f0f0f0<br>회원: #FFF2B3 | 강사: 오른쪽<br>회원: 왼쪽 | 위치/색상 변경 |
| new_event_list.php | ✅ 최신 | ✅ 구현 | 강사: #f0f0f0<br>회원: #FFF2B3 | 강사: 오른쪽<br>회원: 왼쪽 | 위치/색상 변경 |
| new_attdmemlist.php | ✅ 최신 | ✅ 구현 | 강사: #f0f0f0<br>회원: #FFF2B3 | 강사: 오른쪽<br>회원: 왼쪽 | 위치/색상 변경 |
| attdmemlist.php | ✅ 최신 | ✅ 구현 | 강사: #f0f0f0<br>회원: #FFF2B3 | 강사: 오른쪽<br>회원: 왼쪽 | 위치/색상 변경 |
| mmmain.php | ✅ 최신 | ✅ 구현 | 강사: #f0f0f0<br>회원: #FFF2B3 | 강사: 오른쪽<br>회원: 왼쪽 | 위치/색상 변경 |

## 구현 패턴

### 1. 메시지 HTML 생성 (Flexbox 레이아웃)

#### 강사 메시지 (왼쪽 정렬)
```javascript
cmsg += "<div class='direct-chat-msg' style='display:flex; margin-bottom:15px; align-items:flex-start; width:100%;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "' alt='강사사진' style='width:40px; height:40px; border-radius:50%; margin-right:10px; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['STCHR_MAIN_IMG'] || r['STCHR_THUMB_IMG'] || '/dist/img/default_profile_' + (r['STCHR_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['STCHR_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['STCHR_NM'] +" 강사</span>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-left:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; display:inline-block;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";
```

#### 회원 메시지 (오른쪽 정렬)
```javascript
cmsg += "<div class='direct-chat-msg right' style='display:flex; margin:0 0 15px 0; padding:0; align-items:flex-start; width:100%; flex-direction:row-reverse;'>";
cmsg += "    <img class='direct-chat-img' src='" + (r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "' alt='회원사진' style='width:40px; height:40px; border-radius:50%; margin-left:10px; margin-right:0; cursor:pointer; object-fit:cover; flex-shrink:0;' onclick='showFullPhoto(\"" + (r['MEM_MAIN_IMG'] || r['MEM_THUMB_IMG'] || '/dist/img/default_profile_' + (r['MEM_GENDR'] || 'M') + '.png') + "\")' onerror='this.src=\"/dist/img/default_profile_" + (r['MEM_GENDR'] || 'M') + ".png\"'>";
cmsg += "    <div style='flex:1; display:flex; flex-direction:column; align-items:flex-end; margin-right:0;'>";
cmsg += "        <div class='direct-chat-infos' style='margin-bottom:2px;'>";
cmsg += "            <span class='direct-chat-timestamp' style='font-size:0.7rem; color:#999; margin-right:10px;'>"+ r['CRE_DATETM'] +"</span>";
cmsg += "            <span class='direct-chat-name' style='font-size:0.75rem; color:#666;'>"+ r['MEM_NM'] +" 회원</span>";
cmsg += "        </div>";
cmsg += "        <div class='direct-chat-text' style='font-size:0.8rem; white-space: pre-wrap; text-align:left;'>";
cmsg += rn_br(r['CLAS_DIARY_CONTS']);
cmsg += "        </div>";
cmsg += "    </div>";
cmsg += "</div>";
```

### 2. 필수 헬퍼 함수

```javascript
// 줄바꿈 처리
function rn_br(word) {
    return word.replace(/(?:\r\n|\r|\n)/g, '<br />');
}

// 프로필 사진 확대 보기
function showFullPhoto(imageSrc) {
    if (!imageSrc || imageSrc === '') {
        alertToast('error', '사진이 없습니다.');
        return;
    }
    
    // 모달이 이미 있다면 제거
    $('#photoModal').remove();
    
    // 모달 HTML 생성
    var modalHtml = `
        <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="photoModalLabel">사진 보기</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageSrc}" class="img-fluid" alt="상세 사진" style="max-width: 100%; height: auto;" 
                             onerror="this.src='/dist/img/no_image.png'; this.alt='이미지를 불러올 수 없습니다.';">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // 모달을 body에 추가
    $('body').append(modalHtml);
    
    // 모달 표시
    $('#photoModal').modal('show');
    
    // 모달이 닫힐 때 DOM에서 제거
    $('#photoModal').on('hidden.bs.modal', function () {
        $(this).remove();
    });
}
```

## 주의사항

### 1. CSS 우선순위
- AdminLTE의 기본 스타일을 덮어쓰기 위해 `!important` 사용
- 특히 `.direct-chat-msg.right`의 마진/패딩 초기화 필수

### 2. 폴링 구현 시
- 페이지 이동이나 채팅창 닫을 때 반드시 인터벌 정리
- 새 채팅창 열 때 기존 인터벌 확인 및 정리
- 메시지 중복 방지를 위한 시간 체크 구현

### 3. 스크롤 처리
- 새 메시지 도착 시 사용자가 이전 메시지를 읽고 있을 수 있음
- 스크롤이 바닥에 있을 때만 자동 스크롤
- 첫 로드 시에는 항상 맨 아래로 스크롤

### 4. 성능 고려사항
- 대량의 메시지가 있을 경우 가상 스크롤 고려
- 이미지 로딩 최적화 (lazy loading)
- 오래된 메시지 제거 또는 페이징 처리

### 5. 보안 고려사항
- XSS 방지를 위한 입력값 이스케이프
- 세션 만료 체크 및 처리
- AJAX 요청 시 CSRF 토큰 확인

## 향후 개선 고려사항

1. **메시지 상태 표시**
   - 읽음/안읽음 표시
   - 전송 중/실패 상태 표시

2. **알림 기능**
   - 새 메시지 도착 시 브라우저 알림
   - 소리 알림 옵션

3. **파일 전송**
   - 이미지 첨부 기능
   - 문서 파일 공유

4. **메시지 기능 확장**
   - 메시지 수정/삭제
   - 답장 기능
   - 이모티콘 지원

5. **성능 최적화**
   - 메시지 캐싱
   - 증분 업데이트
   - WebSocket 마이그레이션 (필요시)

---

작성일: 2025-01-14
최종 수정일: 2025-01-14