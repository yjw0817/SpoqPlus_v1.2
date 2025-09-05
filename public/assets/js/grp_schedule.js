/**
 * SpoqPlus Group Schedule Management System
 * 
 * 그룹수업 스케줄 관리 시스템을 위한 JavaScript 파일
 * - FullCalendar 기반 스케줄 관리
 * - 룸별 그룹수업 아이템 관리
 * - 예약 관리 및 출석 체크
 * - 강사 변경 및 수업 정산 설정
 * 
 * @author SpoqPlus Development Team
 * @version 2.0
 */

/**
 * 로그인 만료 체크 공통 함수
 * AJAX 응답이 로그인 만료를 나타내는 경우 알림 후 로그인 페이지로 이동
 * @param {string} result - AJAX 응답 결과
 * @returns {boolean} 로그인 만료 시 true, 정상 시 false
 */
function handleLoginExpiry(result) {
    // result가 문자열인지 확인
    if (typeof result === 'string' && result.substr(0,8) == '<script>') {
        alert('로그인이 만료 되었습니다. 다시 로그인해주세요.');
        location.href='/tlogin';
        return true;
    }
    // result가 객체이고 substr 메소드가 있는 경우
    if (result && result.substr && typeof result.substr === 'function' && result.substr(0,8) == '<script>') {
        alert('로그인이 만료 되었습니다. 다시 로그인해주세요.');
        location.href='/tlogin';
        return true;
    }
    return false;
}

/**
 * Toast 스타일 확인 대화상자
 * alertToast와 동일한 스타일로 확인/취소 버튼이 있는 대화상자 표시
 * @param {string} type - 메시지 타입 (success, warning, error, info)
 * @param {string} message - 표시할 메시지
 * @param {function} onConfirm - 확인 버튼 클릭 시 실행할 콜백 함수
 */
function alertToastConfirm(type, message, onConfirm) {
    // 타입별 버튼 스타일 설정
    let confirmButtonClass = 'btn btn-primary btn-sm';
    if (type === 'error') {
        confirmButtonClass = 'btn btn-danger btn-sm';
    } else if (type === 'warning') {
        confirmButtonClass = 'btn btn-warning btn-sm';
    } else if (type === 'success') {
        confirmButtonClass = 'btn btn-success btn-sm';
    } else if (type === 'info') {
        confirmButtonClass = 'btn btn-info btn-sm';
    }
    
    Swal.fire({
        icon: type,
        title: "알림 메세지",
        html: "<font color='#000000'>" + message + "</font>",
        showCancelButton: true,
        confirmButtonText: '확인',
        cancelButtonText: '취소',
        customClass: {
            popup: 'custom-toast',
            confirmButton: confirmButtonClass,
            cancelButton: 'btn btn-secondary btn-sm'
        },
        buttonsStyling: false,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed && onConfirm) {
            onConfirm();
        }
    });
}

$('#gx_room_mgmt_sno').change(function(){
	var gx_room_mgmt_sno = $(this).val();
	// 중복 실행 방지 - 이미 같은 룸인 경우 무시
	if (lastRoomId === gx_room_mgmt_sno) {
		console.log('🚫 룸 변경 핸들러 스킵: 이미 같은 룸입니다.', gx_room_mgmt_sno);
		return;
	}
	
	// 중복 실행 방지 - 이미 변경 중인 경우 무시
	if (isRoomChanging) {
		console.log('🚫 룸 변경 핸들러 스킵: 이미 룸 변경 중입니다.');
		return;
	}
	
	console.log('🏢 룸 변경 핸들러 시작:', gx_room_mgmt_sno);
	
	// 로딩 상태 표시
	$('#external-events').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> 로딩 중...</div>');
	
	// 캘린더 부드러운 전환을 위한 페이드 아웃 (더 부드럽게)
	$('#calendar').addClass('calendar-updating').css('opacity', '0.7');
	
	// AJAX로 룸 변경 처리
	jQuery.ajax({
		url: '/tbcoffmain/ajax_grp_schedule_data',
		type: 'POST',
		data: 'gx_room_mgmt_sno=' + gx_room_mgmt_sno,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'json',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			if (result.result == 'true') {
				// 사이드바 GX 아이템 목록 업데이트
				updateGxItemList(result.gx_item_list);
				
				// 강사 목록 업데이트 (GX 아이템 추가 드롭다운)
				updateTeacherList(result.tchr_list);
				
				// 현재 룸 정보 업데이트 (캘린더 refetch 포함)
				updateCurrentRoomInfo(gx_room_mgmt_sno);
				
				// 캘린더 상태 복원 (중복 새로고침 제거)
				setTimeout(function() {
					$('#calendar').removeClass('calendar-updating').css('opacity', '1');
				}, 300);
				
				// URL 업데이트 (브라우저 히스토리에 추가하지 않음)
				var newUrl = '/tbcoffmain/grp_schedule/' + gx_room_mgmt_sno + window.location.search;
				window.history.replaceState({}, '', newUrl);
			} else {
				alertToast('error', result.message || '룸 변경 중 오류가 발생했습니다.');
				// 오류 시에도 캘린더 상태 복원
				$('#calendar').removeClass('calendar-updating').css('opacity', '1');
			}
		}
	}).done((res) => {
		console.log('룸 변경 통신 성공');
	}).fail((error) => {
		console.log('룸 변경 통신 실패', error);
		alertToast('error', '네트워크 오류가 발생했습니다. 다시 시도해주세요.');
		// 캘린더 상태 복원
		$('#calendar').removeClass('calendar-updating').css('opacity', '1');
		// 실패 시 페이지 새로고침으로 대체
		setTimeout(function() {
			location.reload();
		}, 1000);
	});
});

// AJAX 룸 변경을 위한 도우미 함수들

/**
 * GX 아이템 HTML 생성
 * 그룹수업 아이템 목록을 사이드바에 표시할 HTML로 변환
 * @param {Array} gx_item_list - 그룹수업 아이템 배열
 * @returns {string} 생성된 HTML 문자열
 */
function generateGxItemHtml(gx_item_list) {
	if (!gx_item_list || gx_item_list.length === 0) {
		return '<div class="text-center p-3 text-muted">등록된 그룹수업이 없습니다.</div>';
	}
	
	var html = '';
	gx_item_list.forEach(function(item) {
		html += '<span class="input-group-append" style="position: relative;">';
		html += '<div class="external-event form-control mt2" style="color:#ffffff;background-color:' + item.GX_ITEM_COLOR + ';position:relative;user-select:none;cursor:pointer;" ';
		html += 'data-tid="' + item.TCHR_ID + '" ';
		html += 'data-item-sno="' + item.GX_ITEM_SNO + '" ';
		html += 'data-item-name="' + item.GX_ITEM_NM + '" ';
		html += 'data-tchr-name="' + item.TCHR_NM + '" ';
		html += 'data-item-color="' + item.GX_ITEM_COLOR + '" ';
		html += 'data-class-min="' + (item.GX_CLASS_MIN && item.GX_CLASS_MIN > 0 ? item.GX_CLASS_MIN : 60) + '" ';
		html += '>';
		html += item.GX_ITEM_NM + ' (' + item.TCHR_NM + ')';
		html += '<a type="button" class="close5" onclick="gx_item_del(\'' + item.GX_ITEM_SNO + '\'); event.stopPropagation();" style="position:absolute;top:0px;right:3px;color:#fff;font-size:14px;transform:scale(0.8);"><i class="fas fa-times-circle"></i></a>';
		html += '<span class="ticket-count-badge" style="position:absolute;top:-8px;left:-8px;background-color:#dc3545;color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:bold;pointer-events:none;">' + (item.EVENT_COUNT || 0) + '</span>';
		html += '</div>';
		html += '</span>';
	});
	return html;
}

/**
 * FullCalendar 이벤트 데이터 설정
 * external-event 요소들에 FullCalendar가 인식할 수 있는 이벤트 데이터 설정
 */
function setupFullCalendarEventData() {
	$('#external-events .external-event').each(function() {
		if (!$(this).data('event')) {
			$(this).data('event', {
				title: $(this).data('item-name') + ' (' + $(this).data('tchr-name') + ')',
				backgroundColor: $(this).data('item-color'),
				borderColor: $(this).data('item-color'),
				textColor: '#ffffff'
			});
		}
	});
}

/**
 * GX 아이템 목록 업데이트
 * 룸 변경 시 사이드바의 그룹수업 아이템 목록을 새로고침
 * @param {Array} gx_item_list - 업데이트할 그룹수업 아이템 배열
 */
function updateGxItemList(gx_item_list) {
	var externalEventsHtml = '';
	if (gx_item_list && gx_item_list.length > 0) {
		gx_item_list.forEach(function(item) {
			externalEventsHtml += '<span class="input-group-append" style="position: relative;">';
			externalEventsHtml += '<div class="external-event form-control mt2" style="color:#ffffff;background-color:' + item.GX_ITEM_COLOR + ';position:relative;user-select:none;cursor:pointer;" ';
			externalEventsHtml += 'data-tid="' + item.TCHR_ID + '" ';
			externalEventsHtml += 'data-item-sno="' + item.GX_ITEM_SNO + '" ';
			externalEventsHtml += 'data-item-name="' + item.GX_ITEM_NM + '" ';
			externalEventsHtml += 'data-tchr-name="' + item.TCHR_NM + '" ';
			externalEventsHtml += 'data-item-color="' + item.GX_ITEM_COLOR + '" ';
			externalEventsHtml += 'data-class-min="' + (item.GX_CLASS_MIN && item.GX_CLASS_MIN > 0 ? item.GX_CLASS_MIN : 60) + '" ';
							externalEventsHtml += '>';
			externalEventsHtml += item.GX_ITEM_NM + ' (' + item.TCHR_NM + ')';
			externalEventsHtml += '<a type="button" class="close5" onclick="gx_item_del(\'' + item.GX_ITEM_SNO + '\'); event.stopPropagation();" style="position:absolute;top:0px;right:3px;color:#fff;font-size:14px;transform:scale(0.8);"><i class="fas fa-times-circle"></i></a>';
			externalEventsHtml += '<span class="ticket-count-badge" style="position:absolute;top:-8px;left:-8px;background-color:#dc3545;color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:bold;pointer-events:none;">' + (item.EVENT_COUNT || 0) + '</span>';
			externalEventsHtml += '</div>';
			externalEventsHtml += '</span>';
		});
	} else {
		externalEventsHtml = '<div class="text-center p-3 text-muted">등록된 그룹수업이 없습니다.</div>';
	}
	$('#external-events').html(externalEventsHtml);
	
	// FullCalendar external events 자동 인식을 위해 클래스 재설정
	$('#external-events .external-event').each(function() {
		// FullCalendar가 자동으로 인식할 수 있도록 속성 설정
		if (!$(this).data('event')) {
			$(this).data('event', {
				title: $(this).data('item-name') + ' (' + $(this).data('tchr-name') + ')',
				backgroundColor: $(this).data('item-color'),
				borderColor: $(this).data('item-color'),
				textColor: '#ffffff'
			});
		}
	});
	
	// 좌측 수업 아이템 클릭 이벤트 설정
	setupExternalEventClickHandlers();
	
	// 새로 생성된 external events에 draggable 기능 다시 초기화
	console.log('🔄 새로운 external events에 draggable 재초기화');
	reinitializeExternalEvents();
}

/**
 * 좌측 수업 아이템 클릭 이벤트 핸들러 설정
 * 사이드바의 그룹수업 아이템 클릭 시 컨텍스트 메뉴 표시
 */
function setupExternalEventClickHandlers() {
	console.log('🖱️ setupExternalEventClickHandlers 시작');
	
	// 기존 이벤트 제거
	$('#external-events .external-event').off('.submenu');
	
	// 이벤트 위임을 사용하여 동적으로 생성된 요소에도 적용
	$('#external-events').off('.submenu').on('contextmenu.submenu', '.external-event', function(e) {
		e.preventDefault();
		e.stopPropagation();
		
		var $this = $(this);
		console.log('🖱️ 이벤트 위임 - external-event 우클릭:', $this.data('item-name') || $this.text());
		
		// 삭제 버튼 클릭은 무시
		if ($(e.target).closest('.close5').length > 0) {
			console.log('🖱️ 삭제 버튼 우클릭 감지, 이벤트 무시');
			return false;
		}
		
		showExternalItemSubmenu(e, $this);
		return false;
	});
	
	// 더블클릭을 다시 추가 (기존처럼 작동하게)
	$('#external-events').on('dblclick.submenu', '.external-event', function(e) {
		e.preventDefault();
		e.stopPropagation();
		
		var $this = $(this);
		console.log('🖱️ 이벤트 위임 - external-event 더블클릭:', $this.data('item-name') || $this.text());
		
		// 삭제 버튼 클릭은 무시
		if ($(e.target).closest('.close5').length > 0) {
			return;
		}
		
		// 더블클릭 시에도 서브메뉴 표시
		showExternalItemSubmenu(e, $this);
	});
	
	// 단순 클릭 이벤트 (높은 우선순위로)
	$('#external-events').on('click.submenu', '.external-event', function(e) {
		// 짧은 지연 후 처리 (드래그 이벤트가 먼저 처리되도록)
		setTimeout(function() {
			var $this = $(e.currentTarget);
			console.log('🖱️ 지연된 클릭 이벤트:', $this.data('item-name') || $this.text());
			
			// 삭제 버튼 클릭은 무시
			if ($(e.target).closest('.close5').length > 0) {
				return;
			}
			
			// 드래그 중이 아닌 경우에만
			if (!$this.hasClass('fc-event-dragging') && !$this.hasClass('ui-draggable-dragging')) {
				console.log('🖱️ 클릭 서브메뉴 표시');
				showExternalItemSubmenu(e, $this);
			}
		}, 100);
	});
	
	console.log('🖱️ setupExternalEventClickHandlers 완료');
}

// External Events 재초기화 함수
function reinitializeExternalEvents() {
	// 중복 초기화 방지
	if (window.isReinitializingEvents) {
		console.log('⚠️ External events 재초기화가 이미 진행 중입니다.');
		return;
	}
	window.isReinitializingEvents = true;
	
	console.log('🔄 reinitializeExternalEvents 시작');
	
	// 기존 FullCalendar Draggable 인스턴스가 있으면 정리
	if (window.externalDraggable) {
		try {
			window.externalDraggable.destroy();
			window.externalDraggable = null;
			console.log('기존 Draggable 인스턴스 정리됨');
		} catch (e) {
			console.log('기존 Draggable 정리 중 오류 (무시됨):', e);
		}
	}
	
	// 짧은 대기 후 새 인스턴스 생성 (DOM 업데이트 대기)
	setTimeout(function() {
		var containerEl = document.getElementById('external-events');
		if (containerEl && typeof FullCalendar !== 'undefined' && FullCalendar.Draggable) {
			try {
				window.externalDraggable = new FullCalendar.Draggable(containerEl, {
					itemSelector: '.external-event',
					eventData: function(eventEl) {
						var $el = $(eventEl);
						return {
							title: $el.data('item-name') + ' (' + $el.data('tchr-name') + ')',
							tid: $el.data("tid"),
							gx_item_sno: $el.data("item-sno"),
							class_min: $el.data("class-min"),
							backgroundColor: $el.data('item-color') || window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
							borderColor: $el.data('item-color') || window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
							textColor: '#ffffff',
						};
					},
				});
				console.log('✅ 새로운 FullCalendar Draggable 인스턴스 생성됨');
			} catch (e) {
				console.error('❌ FullCalendar Draggable 생성 실패:', e);
				// 에러 발생 시에도 플래그는 나중에 setTimeout 끝에서 해제됨
			
				// Fallback: jQuery UI draggable 사용
				$('#external-events .external-event').each(function() {
					var $this = $(this);
					if (!$this.hasClass('ui-draggable')) {
						var eventObject = {
							title: $this.data('item-name') + ' (' + $this.data('tchr-name') + ')',
							tid: $this.data("tid"),
							gx_item_sno: $this.data("item-sno"),
							class_min: $this.data("class-min"),
							backgroundColor: $this.data('item-color') || $this.css('background-color'),
							borderColor: $this.data('item-color') || $this.css('background-color'),
							textColor: '#ffffff'
						};
						
						$this.data('eventObject', eventObject);
						$this.draggable({
							zIndex: 1070,
							revert: true,
							revertDuration: 0,
							helper: 'clone'
						});
						console.log('jQuery UI draggable 적용됨:', $this.data('item-name'));
					}
				});
				console.log('✅ jQuery UI draggable fallback 적용됨');
			}
		} else {
			console.error('❌ containerEl 또는 FullCalendar.Draggable을 찾을 수 없음');
			// 컨테이너를 찾을 수 없어도 플래그는 나중에 해제됨
		}
		
		// 클릭 이벤트도 다시 설정
		setupExternalEventClickHandlers();
		
		console.log('🔄 reinitializeExternalEvents 완료');
		
		// 플래그 해제
		window.isReinitializingEvents = false;
	}, 100); // 100ms 대기
}

function updateTeacherList(tchr_list) {
	// GX 아이템 추가 시 사용하는 강사 드롭다운 업데이트
	var teacherOptions = '<option value="">강사를 선택하세요</option>';
	if (tchr_list && tchr_list.length > 0) {
		tchr_list.forEach(function(teacher) {
			teacherOptions += '<option value="' + teacher.MEM_ID + '">' + teacher.MEM_NM + ' (' + teacher.TCHR_POSN_NM + ')</option>';
		});
	}
	$('#gx_ptchr_id').html(teacherOptions);
	
	// 그룹수업 수정 모달의 담당강사 드롭다운도 업데이트 (#edit_instructor 사용)
	if ($('#edit_instructor').length > 0) {
		var modalTeacherOptions = '<option value="">강사 선택</option>';
		if (tchr_list && tchr_list.length > 0) {
			tchr_list.forEach(function(teacher) {
				modalTeacherOptions += '<option value="' + teacher.MEM_ID + '">' + teacher.MEM_NM + ' (' + teacher.TCHR_POSN_NM + ')</option>';
			});
		}
		$('#edit_instructor').html(modalTeacherOptions);
		
		// 현재 선택된 강사 유지를 위한 데이터 저장
		$('#edit_instructor').data('teacher-list', tchr_list);
	}
	
	// groupClassTeacher도 있다면 업데이트 (호환성 유지)
	if ($('#groupClassTeacher').length > 0) {
		var modalTeacherOptions2 = '<option value="">담당강사 선택</option>';
		if (tchr_list && tchr_list.length > 0) {
			tchr_list.forEach(function(teacher) {
				modalTeacherOptions2 += '<option value="' + teacher.MEM_ID + '">' + teacher.MEM_NM + ' (' + teacher.TCHR_POSN_NM + ')</option>';
			});
		}
		$('#groupClassTeacher').html(modalTeacherOptions2);
	}
}

// 룸 변경 중복 실행 방지를 위한 전역 변수
var isRoomChanging = false;
var roomChangeTimeout = null;
var lastRoomId = null;

function updateCurrentRoomInfo(gx_room_mgmt_sno) {
	// 같은 룸으로 변경하려는 경우 무시
	if (lastRoomId === gx_room_mgmt_sno) {
		console.log('🚫 룸 변경 스킵: 이미 같은 룸입니다.', gx_room_mgmt_sno);
		return;
	}
	
	// 중복 실행 방지
	if (isRoomChanging) {
		console.log('🚫 룸 변경 중복 실행 방지: 이미 룸 변경 중입니다.');
		return;
	}
	
	// 이전 타이머가 있으면 취소
	if (roomChangeTimeout) {
		clearTimeout(roomChangeTimeout);
		roomChangeTimeout = null;
		console.log('🚫 이전 룸 변경 타이머 취소');
	}
	
	isRoomChanging = true;
	lastRoomId = gx_room_mgmt_sno;
	
	console.log('🏢 룸 변경 시작:', {
		newRoomId: gx_room_mgmt_sno,
		timestamp: new Date().toISOString(),
		beforeUpdate: $('#gx_room_mgmt_sno').val()
	});
	
	// 현재 선택된 룸 정보를 숨겨진 필드에 업데이트
	if ($('#current_gx_room_mgmt_sno').length > 0) {
		$('#current_gx_room_mgmt_sno').val(gx_room_mgmt_sno);
	}
	
	// 폼의 gx_room_mgmt_sno 값도 업데이트
	$('input[name="gx_room_mgmt_sno"]').val(gx_room_mgmt_sno);
	
	// 선택 박스 값 확인 및 강제 업데이트
	console.log('🏢 룸 변경: 선택 박스 값 확인', {
		selectBoxValue: $('#gx_room_mgmt_sno').val(),
		newRoomId: gx_room_mgmt_sno,
		isMatch: $('#gx_room_mgmt_sno').val() === gx_room_mgmt_sno
	});
	
	// 선택 박스 값이 제대로 업데이트되지 않았으면 강제 설정
	if ($('#gx_room_mgmt_sno').val() !== gx_room_mgmt_sno) {
		console.log('🔧 룸 변경: 선택 박스 값 강제 업데이트');
		$('#gx_room_mgmt_sno').val(gx_room_mgmt_sno).trigger('change');
	}
	
	// 캘린더 이벤트 새로고침 (debounce 적용)
	roomChangeTimeout = setTimeout(function() {
		var calendar = getCalendarInstance();
		if (calendar && calendar.refetchEvents) {
			console.log('🏢 룸 변경: 캘린더 이벤트 refetch 시작', {
				newRoomId: gx_room_mgmt_sno,
				actualRoomValue: $('#gx_room_mgmt_sno').val(),
				calendarExists: !!calendar,
				currentDate: calendar.getDate ? calendar.getDate() : 'unknown'
			});
			
			// events 함수가 올바른 룸 ID를 읽을 수 있도록 잠시 대기 후 refetch
			setTimeout(function() {
				console.log('🏢 룸 변경: refetchEvents 실행', {
					roomIdCheck: $('#gx_room_mgmt_sno').val()
				});
				calendar.refetchEvents();
			}, 50);
			
			// refetch 완료 후 플래그 해제 (약간의 지연)
			setTimeout(function() {
				isRoomChanging = false;
				console.log('🏢 룸 변경 완료: 플래그 해제');
			}, 800);
		} else {
			console.error('❌ 룸 변경 실패: 캘린더 인스턴스 또는 refetchEvents 없음', {
				calendar: !!calendar,
				refetchEvents: calendar ? !!calendar.refetchEvents : false
			});
			isRoomChanging = false;
		}
		
		roomChangeTimeout = null;
	}, 100); // 100ms debounce
}

// 캘린더 이벤트 소스 URL을 업데이트하는 함수


// 캘린더 인스턴스를 찾는 공통 함수
function getCalendarInstance() {
	// 방법 1: 전역 window.calendar
	if (typeof window.calendar !== 'undefined') {
		return window.calendar;
	}
	
	// 방법 2: DOM 엘리먼트의 _calendar
	var calendarEl = document.getElementById('calendar');
	if (calendarEl && calendarEl._calendar) {
		return calendarEl._calendar;
	}
	
	return null;
}

function refreshCalendarEvents() {
	console.log('캘린더 이벤트 새로고침');
	var calendar = getCalendarInstance();
	if (calendar && calendar.refetchEvents) {
		calendar.refetchEvents();
	}
}



// 사이드바만 새로고침하는 함수
function refreshSidebarOnly() {
	var currentRoom = $('#gx_room_mgmt_sno').val();
	
	if (!currentRoom) {
		console.log('선택된 룸이 없어 사이드바 새로고침을 건너뜁니다.');
		return;
	}
	
	// 로딩 상태 표시
	$('#external-events').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> 로딩 중...</div>');
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_grp_schedule_data',
		type: 'POST',
		data: 'gx_room_mgmt_sno=' + currentRoom,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'json',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			if (result.result == 'true') {
				// 사이드바 GX 아이템 목록만 업데이트
				updateGxItemList(result.gx_item_list);
				
				// 강사 목록도 업데이트 (새 강사가 추가되었을 수 있음)
				updateTeacherList(result.tchr_list);
			} else {
				alertToast('error', result.message || '데이터 로딩 중 오류가 발생했습니다.');
			}
		}
	}).done((res) => {
		console.log('사이드바 새로고침 완료');
	}).fail((error) => {
		console.log('사이드바 새로고침 실패', error);
		alertToast('error', '데이터 로딩 중 오류가 발생했습니다.');
	});
}

// 클릭된 이벤트 요소를 저장할 전역 변수
var clickedEventElement = null;

// 이벤트 클릭 시 해당 요소를 저장하는 함수
$(document).on('click', '.fc-event', function(e) {
	clickedEventElement = $(this);
	console.log('Event clicked and stored:', clickedEventElement[0]);
});

function btn_gx_stchr_attd()
{
	// 스케줄 수정하기 팝업에서는 currentSelectedEvent.id를 사용하고, 
	// 강사변경 팝업에서는 기존 방식인 hidden input을 사용
	var gx_schd_mgmt_sno = currentSelectedEvent ? currentSelectedEvent.id : $('#gx_schd_mgmt_sno').val();
	
	if (!gx_schd_mgmt_sno) {
		alertToast('error', '스케줄을 선택해주세요.');
		return;
	}
	
	var params = "gx_schd_mgmt_sno="+gx_schd_mgmt_sno;
    jQuery.ajax({
        url: '/tbcoffmain/ajax_gx_attd_proc',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if (handleLoginExpiry(result)) return;
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				alert(json_result['msg']);
				// 스케줄 수정하기 팝업이 열려있으면 닫기
				if ($('#modal-schedule-edit').hasClass('show')) {
					$('#modal-schedule-edit').modal('hide');
				}
				location.reload();
			} else 
			{
				alertToast('error',json_result['msg']);
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	handleLoginExpiry('<script>');
		return;
    });
}

function gx_copy()
{
	// 오는 일요일 계산
	var today = new Date();
	var dayOfWeek = today.getDay(); // 0: 일요일, 1: 월요일, ..., 6: 토요일
	var daysUntilSunday = (7 - dayOfWeek) % 7; // 오늘이 일요일이면 0, 아니면 일요일까지 남은 일수
	
	// 오는 일요일 날짜 설정 (오늘이 일요일이면 다음주 일요일)
	var nextSunday = new Date(today);
	if (daysUntilSunday === 0) {
		nextSunday.setDate(today.getDate() + 7); // 오늘이 일요일이면 다음주 일요일
	} else {
		nextSunday.setDate(today.getDate() + daysUntilSunday); // 이번주 일요일
	}
	
	$('#pop_copy_edate').datepicker('destroy');
	$('#pop_copy_edate').datepicker({
        autoclose : true,	//사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        language : "ko",	//달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
        startDate: nextSunday, // 오는 일요일부터 선택 가능
        format: 'yyyy-mm-dd'
    });
	$('#modal-gx-copy').modal("show");
}

function btn_gx_copy_proc()
{

// 	$('#copy_sdate').val( $('#gx_current_date').val() );
// 	$('#copy_edate').val( $('#pop_copy_edate').val() );
// 	$('#form_copy_date').submit();
	
	var gx_room_mgmt_sno = $('#gx_room_mgmt_sno').val();
	var sdate = $('#gx_current_date').val();
	var edate = $('#pop_copy_edate').val();
	
	// 유효성 검사
	if (!gx_room_mgmt_sno || gx_room_mgmt_sno == '') {
		alertToast('error', 'GX 룸을 선택해주세요.');
		return;
	}
	if (!sdate || sdate == '') {
		alertToast('error', '시작 날짜가 설정되지 않았습니다.');
		return;
	}
	if (!edate || edate == '') {
		alertToast('error', '복사할 종료 날짜를 선택해주세요.');
		return;
	}
	
	var params = "copy_sdate="+sdate+"&copy_edate="+edate+"&gx_room_mgmt_sno="+gx_room_mgmt_sno;
    jQuery.ajax({
        url: '/tbcoffmain/ajax_copy_schedule',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if (handleLoginExpiry(result)) return;
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				//location.reload();
				alertToast('success','스케쥴이 복사되었습니다.');
				$('#modal-gx-copy').modal('hide');
				
				// 스케줄 복사 완료 후 캘린더 새로고침
				console.log('✅ 스케줄 복사 완료 - 캘린더 새로고침 시작');
				refreshCalendarEvents();
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	handleLoginExpiry('<script>');
		return;
    });
    
}


$('.color-choice > li > a').click(function (e) {
	e.preventDefault();
	$('#gx_item_color').val($(this).css('color'));	
});

$('#add-new-event2').click(function(){
	var gx_item_nm = $('#new-event').val();
	var gx_tchr_id = $('#gx_ptchr_id').val();
	var gx_item_color = $('#gx_item_color').val();
	
	if (gx_item_nm == '')
	{
		alertToast('error','수업명을 입력해주세요.');
		return;
	}
	if (gx_tchr_id == '')
	{
		alertToast('error','강사를 선택해주세요.');
		return;
	}
	if (gx_item_color == '')
	{
		alertToast('error','수업 컬러를 선택해주세요.');
		return;
	}
	
	
	var params = $('#form_gx_item').serialize();
    jQuery.ajax({
        url: '/tbcoffmain/ajax_gx_item_insert',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if (handleLoginExpiry(result)) return;
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				alertToast('success', '새 그룹수업이 추가되었습니다.');
				
				// 입력 필드 초기화
				$('#new-event').val('');
				$('#gx_ptchr_id').val('');
				$('#gx_item_color').val('');
				
				// 사이드바 새로고침 (현재 선택된 룸 기준)
				refreshSidebarOnly();
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	handleLoginExpiry('<script>');
		return;
    });
	
});

function gx_item_del(gx_item_sno)
{
	if(!confirm("정말로 삭제하시겠습니까?"))
	{
		return;
	}
	var params = "gx_item_sno="+gx_item_sno;
    jQuery.ajax({
        url: '/tbcoffmain/ajax_gx_item_delete',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if (handleLoginExpiry(result)) return;
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				alertToast('success', '그룹수업이 삭제되었습니다.');
				
				// 사이드바 새로고침 (현재 선택된 룸 기준)
				refreshSidebarOnly();
				
				// 캘린더 이벤트도 새로고침
				refreshCalendarEvents();
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	handleLoginExpiry('<script>');
		return;
    });
}

function btn_gx_stchr_delete()
{
	var gx_schd_mgmt_sno = $('#gx_schd_mgmt_sno').val();
	var params = "gx_schd_mgmt_sno="+gx_schd_mgmt_sno;
    jQuery.ajax({
        url: '/tbcoffmain/ajax_gx_stchr_delete',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if (handleLoginExpiry(result)) return;
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				
				// 모달 닫기
				$('#modal-gx-stchr').modal('hide');
				
				console.log('=== Calendar Refresh Process ===');
				console.log('Event ID deleted:', gx_schd_mgmt_sno);
				
				// 삭제된 이벤트를 즉시 화면에서 제거
				removeSpecificEventFromDisplay(gx_schd_mgmt_sno);
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	handleLoginExpiry('<script>');
		return;
    });
}

// 현재 캘린더 뷰의 데이터를 새로고침하는 함수
function refreshCurrentCalendarView() {
	try {
		console.log('=== Starting Calendar Refresh ===');
		
		// 현재 캘린더 상태 저장
		var currentViewInfo = getCurrentCalendarViewInfo();
		console.log('Current view info:', currentViewInfo);
		
		// FullCalendar 인스턴스 찾기 시도
		var calendar = findCalendarInstance();
		
		if (calendar) {
			console.log('Found calendar instance, using API refresh');
			refreshWithCalendarAPI(calendar);
		} else {
			console.log('No calendar instance found, using custom AJAX method');
			refreshCalendarDataByAjax(currentViewInfo);
		}
		
	} catch (error) {
		console.error('Error refreshing calendar:', error);
		// 실패 시 페이지 새로고침
		console.log('Fallback to page reload');
		setTimeout(function() {
			location.reload();
		}, 500);
	}
}

// FullCalendar 인스턴스를 찾는 함수
function findCalendarInstance() {
	// 방법 1: 전역 변수에서 찾기
	if (window.calendar && typeof window.calendar.refetchEvents === 'function') {
		console.log('Found calendar in window.calendar');
		return window.calendar;
	}
	
	// 방법 2: jQuery data에서 찾기
	var $calendarEl = $('#calendar');
	if ($calendarEl.length > 0) {
		var calendarData = $calendarEl.data('fullCalendar');
		if (calendarData && typeof calendarData.refetchEvents === 'function') {
			console.log('Found calendar in jQuery data');
			return calendarData;
		}
	}
	
	// 방법 3: FullCalendar v5 방식
	var calendarEl = document.getElementById('calendar');
	if (calendarEl && calendarEl._calendar) {
		console.log('Found calendar in element._calendar');
		return calendarEl._calendar;
	}
	
	console.log('No calendar instance found');
	return null;
}

// FullCalendar API를 사용한 새로고침
function refreshWithCalendarAPI(calendar) {
	try {
		if (typeof calendar.refetchEvents === 'function') {
			console.log('Using refetchEvents API');
			calendar.refetchEvents();
			console.log('Calendar refreshed successfully with API');
		} else if (typeof calendar.rerenderEvents === 'function') {
			console.log('Using rerenderEvents API');
			calendar.rerenderEvents();
			console.log('Calendar rerendered successfully with API');
		} else {
			console.log('Calendar API methods not available, falling back to AJAX');
			refreshCalendarDataByAjax(getCurrentCalendarViewInfo());
		}
	} catch (error) {
		console.error('Error using calendar API:', error);
		refreshCalendarDataByAjax(getCurrentCalendarViewInfo());
	}
}

// 현재 캘린더 뷰 정보를 상세하게 가져오는 함수
function getCurrentCalendarViewInfo() {
	var viewInfo = {
		currentDate: new Date().toISOString().split('T')[0],
		viewType: 'timeGridWeek', // 기본값
		startDate: null,
		endDate: null
	};
	
	try {
		// 캘린더 제목에서 날짜 범위 추출
		var titleText = $('.fc-toolbar-title').text();
		console.log('Calendar title:', titleText);
		
		// "2025년 6월 22일 – 28일" 형식 파싱
		var dateRangeMatch = titleText.match(/(\d{4})년\s*(\d{1,2})월\s*(\d{1,2})일\s*[–-]\s*(\d{1,2})일/);
		if (dateRangeMatch) {
			var year = dateRangeMatch[1];
			var month = String(dateRangeMatch[2]).padStart(2, '0');
			var startDay = String(dateRangeMatch[3]).padStart(2, '0');
			var endDay = String(dateRangeMatch[4]).padStart(2, '0');
			
			viewInfo.currentDate = year + '-' + month + '-' + startDay;
			viewInfo.startDate = year + '-' + month + '-' + startDay;
			viewInfo.endDate = year + '-' + month + '-' + endDay;
			viewInfo.viewType = 'timeGridWeek';
		}
		// 월 뷰 형식 "2025년 6월" 파싱
		else {
			var monthMatch = titleText.match(/(\d{4})년\s*(\d{1,2})월/);
			if (monthMatch) {
				var year = monthMatch[1];
				var month = String(monthMatch[2]).padStart(2, '0');
				viewInfo.currentDate = year + '-' + month + '-01';
				viewInfo.viewType = 'dayGridMonth';
			}
		}
		
		// 현재 뷰 타입 감지
		if ($('.fc-timeGridWeek-view').length > 0) {
			viewInfo.viewType = 'timeGridWeek';
		} else if ($('.fc-dayGridMonth-view').length > 0) {
			viewInfo.viewType = 'dayGridMonth';
		} else if ($('.fc-timeGridDay-view').length > 0) {
			viewInfo.viewType = 'timeGridDay';
		}
		
	} catch (error) {
		console.error('Error parsing calendar view info:', error);
	}
	
	return viewInfo;
}

// AJAX로 현재 날짜 범위의 데이터를 다시 로드하는 함수 (개선된 버전)
function refreshCalendarDataByAjax(viewInfo) {
	var gx_room_mgmt_sno = $('#gx_room_mgmt_sno').val();
	var deletedEventId = $('#gx_schd_mgmt_sno').val(); // 삭제된 이벤트 ID
	
	console.log('Refreshing calendar data with viewInfo:', viewInfo);
	console.log('Room management SNO:', gx_room_mgmt_sno);
	console.log('Deleted event ID:', deletedEventId);
	
	// 먼저 삭제된 이벤트만 화면에서 제거
	removeSpecificEventFromDisplay(deletedEventId);
	
	// 캘린더 데이터 다시 로드 (새로운 이벤트나 다른 사용자 변경사항 반영용)
	var params = {
		cal_type: 'get',
		ndate: viewInfo.currentDate,
		gx_room_mgmt_sno: gx_room_mgmt_sno
	};
	
	$.ajax({
		url: '/tbcoffmain/grp_schedule_proc',
		type: 'POST',
		data: params,
		dataType: 'json',
		success: function(events) {
			console.log('New calendar data received:', events);
			
			// 기존 이벤트는 유지하고 새로운 데이터와 동기화
			syncCalendarWithNewData(events, viewInfo);
		},
		error: function(xhr, status, error) {
			console.error('Failed to refresh calendar data:', error);
			console.log('AJAX refresh failed, but deleted event was already removed from display');
			// AJAX 실패해도 삭제된 이벤트는 이미 화면에서 제거됨
		}
	});
}

// 특정 이벤트만 화면에서 제거하는 함수
function removeSpecificEventFromDisplay(eventId) {
	console.log('Removing specific event from display:', eventId);
	
	var eventRemoved = false;
	
	// 클릭된 이벤트 요소가 저장되어 있으면 그것을 제거
	if (clickedEventElement && clickedEventElement.length > 0) {
		console.log('Removing clicked event element');
		var $eventContainer = clickedEventElement.closest('.fc-timegrid-event-harness');
		if ($eventContainer.length === 0) {
			$eventContainer = clickedEventElement.closest('.fc-event-harness');
		}
		if ($eventContainer.length === 0) {
			$eventContainer = clickedEventElement;
		}
		
		$eventContainer.fadeOut(300, function() {
			$(this).remove();
			console.log('Specific event removed from display');
		});
		
		// 사용된 참조 초기화
		clickedEventElement = null;
		eventRemoved = true;
		return;
	}
	
	// 클릭된 요소가 없으면 이벤트 ID로 찾아서 제거
	console.log('Clicked element not available, searching for event to remove by ID');
	
	// 방법 1: data 속성으로 찾기
	$('.fc-event').each(function() {
		var $event = $(this);
		var dataId = $event.data('event-id') || $event.attr('data-event-id') || 
					 $event.data('id') || $event.attr('data-id');
		
		if (dataId == eventId) {
			console.log('Found event by data attribute:', dataId);
			var $container = $event.closest('.fc-timegrid-event-harness, .fc-event-harness');
			if ($container.length === 0) $container = $event;
			
			$container.fadeOut(300, function() {
				$(this).remove();
			});
			eventRemoved = true;
			return false; // break loop
		}
	});
	
	// 방법 2: 이벤트 ID가 없을 때, 현재 모달의 정보로 찾기
	if (!eventRemoved) {
		console.log('Searching by modal content');
		var modalTitle = $('#gx_schedule_title').val();
		var modalTime = $('#gx_schedule_time').text();
		
		$('.fc-event').each(function() {
			var $event = $(this);
			var eventTitle = $event.find('.fc-event-title').text().trim();
			var eventTime = $event.find('.fc-event-time').text().trim();
			
			if (eventTitle === modalTitle || (modalTime && eventTime.includes(modalTime))) {
				console.log('Found event by title/time match:', eventTitle, eventTime);
				var $container = $event.closest('.fc-timegrid-event-harness, .fc-event-harness');
				if ($container.length === 0) $container = $event;
				
				$container.fadeOut(300, function() {
					$(this).remove();
				});
				eventRemoved = true;
				return false; // break loop
			}
		});
	}
	
	if (!eventRemoved) {
		console.log('Could not find event to remove, will rely on page reload');
	}
}

// 새로운 데이터와 기존 캘린더를 동기화하는 함수
function syncCalendarWithNewData(newEvents, viewInfo) {
	try {
		console.log('Syncing calendar with new data while preserving existing events');
		
		// jQuery FullCalendar API로 이벤트 소스만 업데이트
		if (typeof $('#calendar').fullCalendar === 'function') {
			console.log('Using jQuery FullCalendar API for sync');
			
			// 이벤트 소스 교체 (기존 이벤트 유지하면서 새 데이터 반영)
			$('#calendar').fullCalendar('removeEventSources');
			$('#calendar').fullCalendar('addEventSource', newEvents);
			
			// 현재 뷰 날짜 유지
			if (viewInfo.currentDate) {
				$('#calendar').fullCalendar('gotoDate', viewInfo.currentDate);
			}
			
			console.log('Calendar synced successfully');
			return;
		}
		
		// FullCalendar v5 API 시도
		if (window.calendar && typeof window.calendar.getEventSources === 'function') {
			console.log('Using FullCalendar v5 API for sync');
			
			// 기존 이벤트 소스 제거
			var eventSources = window.calendar.getEventSources();
			eventSources.forEach(function(source) {
				source.remove();
			});
			
			// 새 이벤트 소스 추가
			window.calendar.addEventSource(newEvents);
			
			// 현재 뷰 날짜 유지
			if (viewInfo.currentDate) {
				window.calendar.gotoDate(viewInfo.currentDate);
			}
			
			console.log('Calendar synced successfully with v5 API');
			return;
		}
		
		console.log('Calendar API not available for sync, but specific event was already removed');
		
	} catch (error) {
		console.error('Error syncing calendar:', error);
		console.log('Sync failed, but user experience preserved (specific event removed, view maintained)');
	}
}

function btn_gx_stchr_change()
{
	var gx_schd_mgmt_sno = $('#gx_schd_mgmt_sno').val();
	var tid = $('#ch_gx_stchr_id').val();
	var params = "gx_stchr_id="+tid+"&gx_schd_mgmt_sno="+gx_schd_mgmt_sno;
    jQuery.ajax({
        url: '/tbcoffmain/ajax_gx_stchr_change',
        type: 'POST',
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
        dataType: 'text',
        success: function (result) {
        	if (handleLoginExpiry(result)) return;
        	
            json_result = $.parseJSON(result);
			if (json_result['result'] == 'true')
			{
				// 강사 변경 성공 - 전체 새로고침 대신 캘린더만 새로고침
				console.log('=== Calendar Refresh Process ===');
				console.log('Event ID deleted:', gx_schd_mgmt_sno);
				
				// 1. 모달 닫기
				$('#modal-gx-stchr').modal('hide');
				
				// 2. 성공 메시지 표시
				alertToast('success', '강사가 변경되었습니다.');
				
				// 3. 클릭된 이벤트 요소가 있으면 화면에서 제거
				if (clickedEventElement && clickedEventElement.length > 0) {
					console.log('Removing specific event from display:', gx_schd_mgmt_sno);
					// 특정 이벤트 ID와 일치하는 요소만 제거
					$('.fc-event').each(function() {
						var eventEl = $(this);
						var eventId = eventEl.data('event-id') || eventEl.attr('data-event-id');
						if (eventId == gx_schd_mgmt_sno) {
							console.log('Removing clicked event element');
							eventEl.fadeOut(200, function() {
								$(this).remove();
							});
						}
					});
					console.log('Specific event removed from display');
					clickedEventElement = null;
				}
				
				// 4. 캘린더 이벤트 새로고침 (전체 새로고침 없이)
				var calendar = getCalendarInstance();
				if (calendar && calendar.refetchEvents) {
					console.log('Refreshing calendar events only');
					calendar.refetchEvents();
				}
			}
        }
    }).done((res) => {
    	// 통신 성공시
    	console.log('통신성공');
    }).fail((error) => {
    	// 통신 실패시
    	console.log('통신실패');
    	handleLoginExpiry('<script>');
		return;
    });
}

$(function() {
    // 컬러 선택 이벤트 핸들러
    $('#color-chooser a').click(function(e) {
        e.preventDefault();
        // 모든 버튼의 selected 클래스 제거
        $('#color-chooser a').removeClass('selected');
        // 클릭된 버튼에 selected 클래스 추가
        $(this).addClass('selected');
        
        // 색상 값 저장 (css color 값 사용)
        var color = $(this).css('color');
        $('#gx_item_color').val(color);
        console.log('Selected color:', color); // 디버깅용 로그
    });

    // 폼 제출 전에 색상 값이 있는지 확인
    $('form').submit(function(e) {
        var selectedColor = $('#gx_item_color').val();
        if (!selectedColor) {
            e.preventDefault();
            alert('색상을 선택해주세요.');
            return false;
        }
    });
});

// 24시간 형식으로 시간 변환하는 함수 (FullCalendar 설정으로 인해 비활성화)
/*
var isConverting = false;
function convertTo24HourFormat() {
    // 이미 변환 중이거나 캘린더 업데이트 중이면 건너뛰기
    if (isConverting || window.calendarUpdating) {
        return;
    }
    
    isConverting = true;
    
    try {
        // 시간 슬롯 라벨 변환 (왼쪽 시간 표시)
        $('.fc-timegrid-slot-label-cushion').each(function() {
            var $this = $(this);
            var timeText = $this.text().trim();
            
            // 이미 24시간 형식인지 확인 (xx:xx 형태)
            if (timeText && !timeText.match(/^\d{2}:\d{2}$/)) {
                var converted24Hour = convertTimeStringTo24Hour(timeText);
                if (converted24Hour) {
                    $this.text(converted24Hour);
                }
            }
        });
    
        // 30분 단위 rowspan 적용
        apply30MinRowspan();
        
        // 캘린더 제목 중복 정리
        cleanCalendarTitle();
    
        // 이벤트 내 시간 표시 변환
        $('.fc-event-time').each(function() {
            var $this = $(this);
            var timeText = $this.text().trim();
            
            if (timeText) {
                // 모든 종류의 중복/문제 패턴을 정리
                var cleanedText = timeText;
                
                // 강화된 중복 패턴 정리 - 모든 경우에 대응
                var cleanedText = timeText;
                
                // 1. 가장 복잡한 중복 패턴부터 처리
                // "09:00 - 10:0009:00 - 10:00" 같은 공백 없는 중복
                cleanedText = cleanedText.replace(/(\d{2}:\d{2}\s*-\s*\d{2}:\d{2})\1+/g, '$1');
                
                // 2. "09:00 - 10:00 09:00 - 10:00" 같은 공백 있는 중복
                cleanedText = cleanedText.replace(/(\d{2}:\d{2}\s*-\s*\d{2}:\d{2})\s+\1/g, '$1');
                
                // 3. "09:00 - 10:00오후 2:30 - 오후 3:30" 같은 혼재 패턴
                cleanedText = cleanedText.replace(/^(\d{2}:\d{2}\s*-\s*\d{2}:\d{2}).+$/, '$1');
                
                // 4. 첫 번째 유효한 시간 패턴만 추출
                var firstTimeMatch = cleanedText.match(/(\d{2}:\d{2}\s*-\s*\d{2}:\d{2})/);
                if (firstTimeMatch) {
                    cleanedText = firstTimeMatch[1];
                } else {
                    // 5. 한국어 시간이 있으면 변환
                    if (cleanedText.includes('오전') || cleanedText.includes('오후')) {
                        var converted24Hour = convertEventTimeStringTo24Hour(cleanedText);
                        if (converted24Hour) {
                            cleanedText = converted24Hour;
                        }
                    }
                }
                
                // 변경된 경우에만 업데이트
                if (cleanedText !== timeText) {
                    $this.text(cleanedText);
                }
            }
        });
    } finally {
        // 변환 완료 후 플래그 해제
        setTimeout(function() {
            isConverting = false;
        }, 5);
    }
}
*/

// 캘린더 제목 중복 정리 함수 (FullCalendar 설정으로 인해 비활성화)
/*
function cleanCalendarTitle() {
    // 캘린더 제목 요소들 확인
    $('.fc-toolbar-title, .fc-title, h2').each(function() {
        var $this = $(this);
        var titleText = $this.text().trim();
        
        if (titleText) {
            var cleanedText = titleText;
            
            // 1. 연도-월-일 패턴 중복 제거 (예: "2025년 6월 16일 ~ 2025년 6월 22일2025년 6월 9일 – 15일")
            cleanedText = cleanedText.replace(/(\d{4}년\s*\d{1,2}월\s*\d{1,2}일\s*[~–-]\s*\d{4}년\s*\d{1,2}월\s*\d{1,2}일).*$/, '$1');
            
            // 2. 중복된 날짜 범위 패턴 제거
            var dateRangePattern = /^(\d{4}년\s*\d{1,2}월\s*\d{1,2}일\s*[~–-]\s*\d{4}년\s*\d{1,2}월\s*\d{1,2}일)\1/;
            cleanedText = cleanedText.replace(dateRangePattern, '$1');
            
            // 3. 기타 중복 패턴 정리
            cleanedText = cleanedText.replace(/^(.+?)(\1)+$/, '$1');
            
            // 변경된 경우에만 업데이트
            if (cleanedText !== titleText && cleanedText.length > 0) {
                $this.text(cleanedText);
            }
        }
    });
}
*/

// 30분 단위 rowspan 적용 함수 (FullCalendar 설정으로 인해 비활성화)
/*
function apply30MinRowspan() {
    // 기존 rowspan 및 클래스 초기화
    $('.fc-timegrid-slot-label').removeClass('merged-30min').removeAttr('rowspan');
    $('.fc-timegrid-slot-label.fc-timegrid-slot-minor').show();
    
    // 모든 테이블 행을 순회하여 rowspan 적용
    $('.fc-timegrid-slots tbody tr').each(function(index) {
        var $row = $(this);
        var $timeLabel = $row.find('.fc-timegrid-slot-label');
        
        // minor 클래스가 없는 정시 슬롯인 경우
        if ($timeLabel.length > 0 && !$timeLabel.hasClass('fc-timegrid-slot-minor')) {
            var timeText = $timeLabel.find('.fc-timegrid-slot-label-cushion').text().trim();
            
            // 정시(xx:00)인 경우 rowspan 적용
            if (timeText && timeText.match(/^\d{2}:00$/)) {
                // rowspan=2 설정
                $timeLabel.attr('rowspan', '2');
                $timeLabel.addClass('merged-30min');
                
                // 다음 행의 시간 레이블 셀 제거
                var $nextRow = $('.fc-timegrid-slots tbody tr').eq(index + 1);
                if ($nextRow.length > 0) {
                    var $nextTimeLabel = $nextRow.find('.fc-timegrid-slot-label');
                    if ($nextTimeLabel.hasClass('fc-timegrid-slot-minor')) {
                        $nextTimeLabel.hide();
                    }
                }
            }
        }
    });
}
*/

// 시간 문자열을 24시간 형식으로 변환 (FullCalendar 설정으로 인해 비활성화)
/*
function convertTimeStringTo24Hour(timeStr) {
    // "오전 6시", "오후 2시" 형식 처리
    var match = timeStr.match(/(오전|오후)\s*(\d{1,2})시?/);
    if (match) {
        var period = match[1];
        var hour = parseInt(match[2]);
        
        if (period === '오전') {
            if (hour === 12) hour = 0;
        } else { // 오후
            if (hour !== 12) hour += 12;
        }
        
        return String(hour).padStart(2, '0') + ':00';
    }
    
    return null;
}

// 이벤트 시간 문자열을 24시간 형식으로 변환
function convertEventTimeStringTo24Hour(timeStr) {
    // "오후 2:30 - 오후 3:30" 형식 처리
    var converted = timeStr.replace(/(오전|오후)\s*(\d{1,2}):(\d{2})/g, function(match, period, hour, minute) {
        var h = parseInt(hour);
        
        if (period === '오전') {
            if (h === 12) h = 0;
        } else { // 오후
            if (h !== 12) h += 12;
        }
        
        return String(h).padStart(2, '0') + ':' + minute;
    });
    
    return converted !== timeStr ? converted : null;
}
*/

// 페이지 로드 시 및 캘린더 업데이트 시 24시간 형식 적용
$(document).ready(function() {
    // FullCalendar 설정으로 인해 변환 함수는 비활성화하지만 초기화 코드는 실행
    /*
    // 초기 변환 - 디바운스 적용
    var initialConvertTimeout = null;
    function scheduleConversion(delay) {
        if (initialConvertTimeout) {
            clearTimeout(initialConvertTimeout);
        }
        initialConvertTimeout = setTimeout(function() {
            convertTo24HourFormat();
            initialConvertTimeout = null;
        }, delay || 100);
    }
    */
    
    // 초기 실행 - FullCalendar 24시간 형식은 ama_calendar.js에서 이미 설정됨
    // 중복 설정 방지를 위해 비활성화
    /*
    setTimeout(function() {
        if (typeof window.calendar !== 'undefined') {
            // FullCalendar v5 방식
            console.log('Applying 24-hour format to FullCalendar v5');
            window.calendar.setOption('eventTimeFormat', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            window.calendar.setOption('slotLabelFormat', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        } else if (typeof $('#calendar').fullCalendar === 'function') {
            // jQuery FullCalendar 방식
            console.log('Applying 24-hour format to jQuery FullCalendar');
            $('#calendar').fullCalendar('option', 'timeFormat', 'HH:mm');
            $('#calendar').fullCalendar('option', 'axisFormat', 'HH:mm');
            $('#calendar').fullCalendar('option', 'slotLabelFormat', 'HH:mm');
        }
    }, 500);
    
    // 추가로 1초 후에도 한 번 더 시도
    setTimeout(function() {
        if (typeof window.calendar !== 'undefined') {
            window.calendar.setOption('eventTimeFormat', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            window.calendar.setOption('slotLabelFormat', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        } else if (typeof $('#calendar').fullCalendar === 'function') {
            $('#calendar').fullCalendar('option', 'timeFormat', 'HH:mm');
            $('#calendar').fullCalendar('option', 'axisFormat', 'HH:mm');
            $('#calendar').fullCalendar('option', 'slotLabelFormat', 'HH:mm');
        }
    }, 1000);
    */
    
    // FullCalendar 설정이 적용되었으므로 변환 함수는 비활성화
    // convertTo24HourFormat();
    // scheduleConversion(100);
    
    // 커스텀 오늘 버튼 Outlined 스타일 적용
    function enableTodayButton() {
        // 기존 btn-primary 클래스 제거하고 outlined 스타일 적용
        $('.fc-customToday-button').removeClass('btn-primary').addClass('btn-outline-primary');
        
        // 추가 스타일 직접 적용
        $('.fc-customToday-button').css({
            'background-color': 'transparent',
            'background': 'transparent',
            'color': '#007bff',
            'border': '2px solid #007bff',
            'border-color': '#007bff',
            'font-weight': '500'
        });
        
        // 호버 이벤트 재정의
        $('.fc-customToday-button').off('mouseenter mouseleave').on('mouseenter', function() {
            $(this).css({
                'background-color': '#007bff',
                'background': '#007bff',
                'color': 'white',
                'border-color': '#007bff'
            });
        }).on('mouseleave', function() {
            $(this).css({
                'background-color': 'transparent',
                'background': 'transparent',
                'color': '#007bff',
                'border-color': '#007bff'
            });
        });
    }
    
    // 초기 실행
    setTimeout(enableTodayButton, 100);
    setTimeout(enableTodayButton, 500);
    
    // 캘린더 네비게이션 시 버튼 스타일 재적용 (변환 함수는 비활성화)
    $(document).on('click', '.fc-prev-button, .fc-next-button', function() {
        // 버튼 재활성화
        setTimeout(enableTodayButton, 100);
    });
    
    	// 오늘 버튼 클릭시 스타일 재적용
	$(document).on('click', '.fc-customToday-button', function() {
		// 버튼 재활성화
		setTimeout(enableTodayButton, 100);
	});
	
	// 시간 레이블 rowspan 처리 함수 - 깜빡임 방지를 위해 완전 비활성화
	function applyTimeSlotRowspan() {
		// console.log('시간 레이블 rowspan 적용 시작');
		// 
		// // 먼저 모든 기존 rowspan 제거하고 초기화
		// var $timeLabels = $('.fc-timegrid-slot-label');
		// $timeLabels.removeAttr('rowspan').css({
		// 	'height': '',
		// 	'border-bottom': '',
		// 	'vertical-align': ''
		// }).show();
		// 
		// // 30분 단위 행의 시간 레이블 셀 완전 제거
		// $('.fc-timegrid-slot-label.fc-timegrid-slot-minor').remove();
		// 
		// // 추가 확인: data-time 속성으로도 30분 셀 제거
		// $('.fc-timegrid-slot-label[data-time$=":30:00"]').remove();
		// 
		// // 30분 단위 시간 텍스트를 가진 셀도 제거
		// $('.fc-timegrid-slot-label').each(function() {
		// 	var timeText = $(this).find('.fc-timegrid-slot-label-cushion').text();
		// 	if (timeText && timeText.includes(':30')) {
		// 		$(this).remove();
		// 	}
		// });
		// 
		// // 정시 시간 레이블에 rowspan=2 적용
		// $('.fc-timegrid-slot-label').each(function() {
		// 	var $this = $(this);
		// 	var timeText = $this.find('.fc-timegrid-slot-label-cushion').text();
		// 	
		// 	// 정시 슬롯에 rowspan 효과 적용
		// 	if (timeText && timeText.includes(':00')) {
		// 		$this.attr('rowspan', 2);
		// 		$this.css({
		// 			'height': 'auto',
		// 			'border-bottom': '1px solid #ddd',
		// 			'vertical-align': 'middle'
		// 		});
		// 		console.log('rowspan 적용:', timeText);
		// 	}
		// });
		// 
		// console.log('시간 레이블 rowspan 적용 완료');
		
		// 깜빡임 방지를 위해 함수 완전 비활성화
		console.log('applyTimeSlotRowspan: 깜빡임 방지를 위해 비활성화됨');
	}
	
	// 캘린더 로딩 완료 후 rowspan 적용 - 깜빡임 방지를 위해 비활성화
	// applyTimeSlotRowspan();
	
	// 캘린더 네비게이션 시 rowspan 재적용 (깜빡임 방지를 위해 제거)
	// next/prev 버튼 클릭 시 rowspan이 자동으로 유지되므로 재적용 불필요
	/*
	$(document).on('click', '.fc-prev-button, .fc-next-button, .fc-customToday-button, .fc-today-button', function() {
		console.log('네비게이션 버튼 클릭됨');
		
		// 시간 레이블 초기화 및 재적용을 위한 지연 처리
		setTimeout(function() {
			console.log('네비게이션 후 rowspan 재적용');
			applyTimeSlotRowspan();
		}, 100);
	});
	*/
	
	// MutationObserver로 시간 레이블 변경 감지하여 rowspan 자동 적용 (깜빡임 방지를 위해 비활성화)
	/*
	var rowspanTimeout = null;
	var timeSlotObserver = new MutationObserver(function(mutations) {
		var shouldApplyRowspan = false;
		
		mutations.forEach(function(mutation) {
			if (mutation.type === 'childList') {
				for (var i = 0; i < mutation.addedNodes.length; i++) {
					var node = mutation.addedNodes[i];
					if (node.nodeType === 1) { // Element node
						if ($(node).find('.fc-timegrid-slot-label').length > 0 ||
							$(node).hasClass('fc-timegrid-slot-label')) {
							shouldApplyRowspan = true;
							break;
						}
					}
				}
			}
		});
		
		if (shouldApplyRowspan) {
			// 디바운스 처리
			if (rowspanTimeout) {
				clearTimeout(rowspanTimeout);
			}
			rowspanTimeout = setTimeout(function() {
				console.log('MutationObserver에서 rowspan 적용');
				applyTimeSlotRowspan();
				rowspanTimeout = null;
			}, 150);
		}
	});
	
	// 캘린더 영역 관찰 시작
	var calendarEl = document.getElementById('calendar');
	if (calendarEl) {
		timeSlotObserver.observe(calendarEl, {
			childList: true,
			subtree: true
		});
	}
	*/
    
    // MutationObserver로 DOM 변경 감지하여 즉시 자동 변환 (FullCalendar 설정으로 인해 비활성화)
    /*
    var convertTimeout = null;
    var observer = new MutationObserver(function(mutations) {
        var shouldConvert = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                for (var i = 0; i < mutation.addedNodes.length; i++) {
                    var node = mutation.addedNodes[i];
                    if (node.nodeType === 1) { // Element node
                        if ($(node).find('.fc-timegrid-slot-label-cushion, .fc-event-time, .fc-toolbar-title').length > 0 ||
                            $(node).hasClass('fc-timegrid-slot-label-cushion') ||
                            $(node).hasClass('fc-event-time') ||
                            $(node).hasClass('fc-toolbar-title')) {
                            shouldConvert = true;
                            break;
                        }
                    }
                }
            } else if (mutation.type === 'characterData') {
                var $target = $(mutation.target);
                if ($target.closest('.fc-timegrid-slot-label-cushion, .fc-event-time, .fc-toolbar-title').length > 0) {
                    shouldConvert = true;
                }
            }
        });
        
        if (shouldConvert && !window.calendarUpdating) {
            // 디바운스: 이전 타이머 취소하고 새로 설정
            if (convertTimeout) {
                clearTimeout(convertTimeout);
            }
            convertTimeout = setTimeout(function() {
                if (!window.calendarUpdating) {
                    convertTo24HourFormat();
                    cleanCalendarTitle();
                }
                convertTimeout = null;
            }, 10);
        }
    });
    
    // 캘린더 영역 관찰 시작 - 더 세밀한 감지
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        observer.observe(calendarEl, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }
    
    // 추가적으로 전체 문서 감시 (보조)
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        characterData: true
    });
    */
    
    // 밝은 색상 배경의 캘린더 이벤트 글자색 조정
    function adjustEventTextColor() {
        const lightColors = [
            'rgb(90, 200, 250)',
            'rgb(187, 226, 68)', 
            'rgb(255, 204, 0)',
            'rgb(255, 149, 0)',
            'rgb(233, 233, 233)'
        ];
        
        $('.fc-event').each(function() {
            const $event = $(this);
            const bgColor = $event.css('background-color');
            
            if (lightColors.includes(bgColor)) {
                $event.css({
                    'color': '#333333 !important',
                    'font-weight': 'normal'
                });
                $event.find('.fc-event-time').css({
                    'color': '#333333 !important',
                    'font-weight': '500'
                });
                $event.find('.fc-event-title').css({
                    'color': '#333333 !important',
                    'font-weight': '400'
                });
            }
        });
    }
    
    // 캘린더 렌더링 후 색상 조정 실행
    setInterval(adjustEventTextColor, 500);
    
    // 버튼 색상에 따른 글자색 조정
    function adjustButtonTextColor() {
        const lightColors = [
            'rgb(90, 200, 250)',
            'rgb(187, 226, 68)', 
            'rgb(255, 204, 0)',
            'rgb(255, 149, 0)',
            'rgb(233, 233, 233)'
        ];
        
        $('#add-new-event2').each(function() {
            const $button = $(this);
            const bgColor = $button.css('background-color');
            
            if (lightColors.includes(bgColor)) {
                $button.css({
                    'color': '#333333 !important',
                    'font-weight': 'bold'
                });
            } else {
                $button.css({
                    'color': '#ffffff',
                    'font-weight': 'normal'
                });
            }
        });
    }
    
    // 버튼 색상 조정도 주기적으로 실행
    setInterval(adjustButtonTextColor, 500);
    
    // 캘린더 이벤트에서 이용권 수 제거하는 함수 (FullCalendar 설정으로 인해 비활성화)
    /*
    function cleanCalendarEventTitles() {
        $('.fc-event-title').each(function() {
            var $title = $(this);
            var titleText = $title.text();
            
            // 숫자만 있는 패턴 제거 (예: "줌바 (GX강사파트)\n3" -> "줌바 (GX강사파트)")
            var cleanedText = titleText.replace(/\s*\n\s*\d+\s*$/, '');
            
            // 마지막에 단독으로 있는 숫자 제거 (예: "줌바 (GX강사파트) 3" -> "줌바 (GX강사파트)")
            cleanedText = cleanedText.replace(/\s+\d+\s*$/, '');
            
            if (cleanedText !== titleText) {
                // 부드러운 텍스트 변경을 위한 페이드 효과
                $title.fadeTo(100, 0.5, function() {
                    $title.text(cleanedText);
                    $title.fadeTo(100, 1);
                });
            }
        });
    }
    
    // 캘린더 이벤트 제목 정리 - 주기적으로 실행
    setInterval(cleanCalendarEventTitles, 200);
    
    // DOM 변화 감지하여 즉시 정리
    var eventObserver = new MutationObserver(function(mutations) {
        var shouldClean = false;
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                for (var i = 0; i < mutation.addedNodes.length; i++) {
                    var node = mutation.addedNodes[i];
                    if (node.nodeType === 1) { // Element node
                        if ($(node).find('.fc-event-title').length > 0 ||
                            $(node).hasClass('fc-event-title') ||
                            $(node).hasClass('fc-event')) {
                            shouldClean = true;
                            break;
                        }
                    }
                }
            }
        });
        
        if (shouldClean) {
            setTimeout(cleanCalendarEventTitles, 1);
        }
    });
    
    // 캘린더 영역 관찰
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        eventObserver.observe(calendarEl, {
            childList: true,
            subtree: true
        });
    }
    */
});

// 그룹수업 수정 모달 열기
function openGroupClassModal(element) {
	// 이벤트 전파 방지 (드래그 이벤트와 충돌 방지)
	if (event) {
		event.stopPropagation();
		event.preventDefault();
	}
	
	// 데이터 추출
	var itemSno = $(element).data('item-sno');
	
	// AJAX로 데이터 불러오기
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_group_class_data',
		type: 'POST',
		data: { gx_item_sno: itemSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				var classData = data['data'];
				
				// 디버깅을 위한 콘솔 로그
				console.log('그룹수업 데이터:', classData);
				console.log('담당강사 ID:', classData.TCHR_ID);
				
				// 강사 목록이 있다면 먼저 업데이트
				if (data['tchr_list']) {
					updateTeacherList(data['tchr_list']);
					console.log('강사 목록 업데이트 완료');
				} else {
					// 서버에서 강사 목록을 제공하지 않는 경우, 저장된 강사 목록 사용
					var savedTeacherList = $('#edit_instructor').data('teacher-list');
					if (savedTeacherList) {
						console.log('저장된 강사 목록 사용');
					} else {
						console.log('강사 목록이 없습니다. 현재 드롭다운 유지');
					}
				}
				
				// 모달에 데이터 설정
				$('#edit_class_name').val(classData.GX_ITEM_NM || '');
				
				// 강사 선택 설정 (약간의 딜레이를 주어 DOM 업데이트 후 선택)
				setTimeout(function() {
					// 강사 ID 추출 (TCHR_ID가 우선, 없으면 MEM_ID 사용)
					var teacherId = classData.TCHR_ID || classData.MEM_ID || '';
					
					// String으로 변환하여 비교 (데이터 타입 불일치 해결)
					teacherId = String(teacherId).trim();
					
					console.log('강사 선택 시도 - teacherId:', teacherId);
					console.log('현재 dropdown options:');
					$('#edit_instructor option').each(function() {
						console.log('  - value:', $(this).val(), 'text:', $(this).text());
					});
					
					// 먼저 직접 값 설정 시도
					$('#edit_instructor').val(teacherId);
					
					// 값이 설정되지 않았다면 String 비교로 매칭 시도
					if (!$('#edit_instructor').val() && teacherId) {
						var matched = false;
						$('#edit_instructor option').each(function() {
							var optionValue = String($(this).val()).trim();
							if (optionValue === teacherId) {
								$('#edit_instructor').val(optionValue);
								matched = true;
								console.log('✅ 강사 매칭 성공:', optionValue);
								return false;
							}
						});
						
						if (!matched) {
							console.warn('⚠️ 강사 매칭 실패 - teacherId:', teacherId, '가 목록에 없습니다.');
							// 강사 이름으로도 시도 (fallback)
							if (classData.TCHR_NM) {
								console.log('강사 이름으로 매칭 시도:', classData.TCHR_NM);
								$('#edit_instructor option').each(function() {
									if ($(this).text().indexOf(classData.TCHR_NM) !== -1) {
										$('#edit_instructor').val($(this).val());
										console.log('✅ 강사 이름으로 매칭 성공');
										return false;
									}
								});
							}
						}
					}
					
					console.log('최종 선택된 값:', $('#edit_instructor').val());
				}, 100); // 타이밍 안정성을 위해 100ms로 증가
				
				$('#edit_duration').val(classData.GX_CLASS_MIN == '0' ? '' : classData.GX_CLASS_MIN);
				$('#edit_participants').val(classData.GX_DEDUCT_CNT == '0' ? '' : classData.GX_DEDUCT_CNT);
				$('#edit_capacity').val(classData.GX_MAX_NUM == '0' ? '' : classData.GX_MAX_NUM);
				$('#edit_max_capacity').val(classData.GX_MAX_WAITING == '0' ? '' : classData.GX_MAX_WAITING);
				
				// 참석 가능한 이용권 버튼 텍스트 업데이트
				var eventCount = parseInt(classData.EVENT_COUNT) || 0;
				var eventCountText = '';
				if (eventCount === 0) {
					eventCountText = '참석 가능한 이용권 없음 (선택추가)';
				} else {
					eventCountText = '참석 가능한 이용권 ' + eventCount + '개 (선택추가)';
				}
				$('#btn-ticket-selection').text(eventCountText);
				console.log('이용권 버튼 업데이트:', eventCount, eventCountText);
				
				// 자리 예약 가능 설정
				var useReservYn = classData.USE_RESERV_YN || 'N';
				$('#edit_reservation').prop('checked', useReservYn === 'Y');
				$('#edit_reservation_num').prop('disabled', useReservYn === 'N');
				if(useReservYn === 'Y')
				{
					$('#edit_reservation_num').val(classData.RESERV_NUM == '0' ? '' : classData.RESERV_NUM);
				}
				else
				{
					$('#edit_reservation_num').val('');
				}
				
				// 공개/폐강 스케줄 정보 표시
				$('#open_schedule_text').text(classData.OPEN_SCHEDULE || '미설정');
				$('#close_schedule_text').text(classData.CLOSE_SCHEDULE || '미설정');
				
				// 모달에 아이템 SNO 저장
				$('#modal-group-class-edit').data('item-sno', itemSno);
				
				// 수업정산 설정 내역 로드 및 표시
				loadAndDisplaySettlementInfo(itemSno);
				
				// 선택된 이미지 표시
				displaySelectedImage(classData.SELECTED_IMAGE);
				
				// 모달 열기
				$('#modal-group-class-edit').modal('show');
			} else {
				alert('데이터를 불러오는데 실패했습니다.');
			}
		}
	}).done((res) => {
		console.log('그룹수업 데이터 로드 성공');
	}).fail((error) => {
		console.log('그룹수업 데이터 로드 실패');
		handleLoginExpiry('<script>');
	});
}

// 수업 이미지 선택 (원본 크기로 보기 기능 추가)
function selectClassImage(element) {
	// 이미지가 있는지 확인
	var img = $(element).find('img');
	if (img.length > 0) {
		var imageUrl = img.attr('src');
		var altText = img.attr('alt') || '수업 이미지';
		
		// 원본 이미지 모달로 표시
		showImageModal(imageUrl, altText);
	} else {
		// 이미지가 없으면 이미지 설정 모달 열기
		openClassImageModal();
	}
}

// 스케줄 수업 이미지 클릭 (원본 크기로 보기)
function selectScheduleClassImage(element) {
	// 이미지가 있는지 확인
	var img = $(element).find('img');
	if (img.length > 0) {
		var imageUrl = img.attr('src');
		var altText = img.attr('alt') || '수업 이미지';
		
		// 원본 이미지 모달로 표시
		showImageModal(imageUrl, altText);
	} else {
		// 이미지가 없으면 이미지 설정 모달 열기
		openScheduleClassImageModal();
	}
}

// 이미지 원본 크기 모달 표시
function showImageModal(imageUrl, altText) {
	// 모달 HTML이 없으면 생성
	if ($('#image-preview-modal').length === 0) {
		var modalHtml = `
			<div class="modal fade" id="image-preview-modal" tabindex="-1" role="dialog" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="imagePreviewModalLabel">수업 이미지</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body text-center">
							<img id="preview-image" src="" alt="" style="max-width: 100%; max-height: 70vh; object-fit: contain;">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
						</div>
					</div>
				</div>
			</div>
		`;
		$('body').append(modalHtml);
	}
	
	// 이미지 설정
	$('#preview-image').attr('src', imageUrl).attr('alt', altText);
	$('#imagePreviewModalLabel').text(altText);
	
	// 모달 열기
	$('#image-preview-modal').modal('show');
}

// 선택된 이미지 표시
function displaySelectedImage(selectedImage) {
	var imageContainer = $('.col-4 .border');
	
	if (selectedImage && selectedImage.IMAGE_FILE) {
		// 선택된 이미지가 있는 경우
		var imageUrl = selectedImage.IMAGE_URL;
		
		imageContainer.html(`
			<div style="width: 100%; max-height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 4px; overflow: hidden;">
				<img src="${imageUrl}" style="max-width: 100%; max-height: 80px; object-fit: contain; border-radius: 4px;" alt="선택된 수업 이미지">
			</div>
		`);
	} else {
		// 선택된 이미지가 없는 경우
		imageContainer.html(`
			<div style="width: 100%; height: 56px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
				<span style="color: #6c757d; font-size: 12px;">이미지 1</span>
			</div>
		`);
	}
}

// 전역 변수 - 전체 이용권 목록과 선택된 이용권 저장
var allTicketList = [];
var selectedTicketList = [];

// 참석 가능한 이용권 선택 모달 열기
function openTicketSelectionModal() {
	var itemSno = $('#modal-group-class-edit').data('item-sno');
	
	// 부모 모달 비활성화
	$('#modal-group-class-edit .modal-content').addClass('modal-disabled');
	$('#modal-group-class-edit .modal-content *').prop('disabled', true);
	
	// 모달에 아이템 SNO 저장
	$('#modal-ticket-selection').data('item-sno', itemSno);
	
	// 검색 필드 초기화
	$('#ticket-search').val('');
	$('#show-stopped-tickets').prop('checked', false);
	
	// 이용권 목록 로드
	loadTicketList();
	
	// 모달 열기
	$('#modal-ticket-selection').modal('show');
}

// 이용권 목록 로드
function loadTicketList() {
	var itemSno = $('#modal-ticket-selection').data('item-sno');
	var showStopped = $('#show-stopped-tickets').is(':checked');
	
	// AJAX로 이용권 목록과 선택된 이용권 정보 불러오기
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_ticket_list',
		type: 'POST',
		data: { 
			gx_item_sno: itemSno,
			show_stopped: showStopped ? 'Y' : 'N'
		},
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				// 전역 변수에 데이터 저장
				allTicketList = data['ticket_list'];
				selectedTicketList = data['selected_tickets'];
				
				// 이용권 목록 표시
				displayTicketList(allTicketList, selectedTicketList);
				
				// 선택된 이용권 개수 업데이트
				updateSelectedTicketCount();
			} else {
				alert('이용권 목록을 불러오는데 실패했습니다.');
			}
		}
	}).done((res) => {
		console.log('이용권 목록 로드 성공');
	}).fail((error) => {
		console.log('이용권 목록 로드 실패');
		handleLoginExpiry('<script>');
	});
}

// 이용권 목록 표시
function displayTicketList(ticketList, selectedTickets) {
	var html = '';
	var selectedTicketIds = selectedTickets.map(function(ticket) {
		return ticket.SELL_EVENT_SNO;
	});
	
	ticketList.forEach(function(ticket) {
		var isChecked = selectedTicketIds.includes(ticket.SELL_EVENT_SNO);
		var checkedAttr = isChecked ? 'checked' : '';
		var rowClass = isChecked ? 'table-success' : '';
		
		// 판매 상태 표시
		var sellStatus = '';
		var sellYn = ticket.SELL_YN || '';
		if (sellYn === 'Y') {
			sellStatus = '<span class="badge bg-success">판매</span>';
		} else {
			sellStatus = '<span class="badge bg-secondary">판매중지</span>';
		}
		
		html += '<tr class="' + rowClass + '" data-ticket-name="' + ticket.SELL_EVENT_NM.toLowerCase() + '">';
		html += '<td><input type="checkbox" class="ticket-checkbox" value="' + ticket.SELL_EVENT_SNO + '" ' + checkedAttr + ' onchange="updateSelectedTicketCount(); toggleRowColor(this); updateSelectAllCheckbox();"></td>';
		html += '<td>' + ticket.SELL_EVENT_NM + '</td>';
		html += '<td>' + sellStatus + '</td>';
		html += '<td>' + ticket.SELL_EVENT_SNO + '</td>';
		html += '</tr>';
	});
	
	$('#ticket-list').html(html);
	updateSelectAllCheckbox();
}

// 선택된 이용권 개수 업데이트
function updateSelectedTicketCount() {
	var selectedCount = $('.ticket-checkbox:checked').length;
	$('#selected-ticket-count').text('선택된 이용권 : ' + selectedCount + '개');
}

// 체크박스 변경 시 행 색상 토글
function toggleRowColor(checkbox) {
	var row = $(checkbox).closest('tr');
	if (checkbox.checked) {
		row.addClass('table-success');
	} else {
		row.removeClass('table-success');
	}
}

// 전체 선택/해제 토글
function toggleAllTickets(selectAllCheckbox) {
	var isChecked = selectAllCheckbox.checked;
	
	$('.ticket-checkbox:visible').each(function() {
		this.checked = isChecked;
		toggleRowColor(this);
	});
	
	updateSelectedTicketCount();
}

// 전체 선택 체크박스 상태 업데이트
function updateSelectAllCheckbox() {
	var visibleCheckboxes = $('.ticket-checkbox:visible');
	var checkedCheckboxes = $('.ticket-checkbox:visible:checked');
	var selectAllCheckbox = $('#select-all-tickets')[0];
	
	if (visibleCheckboxes.length === 0) {
		selectAllCheckbox.indeterminate = false;
		selectAllCheckbox.checked = false;
	} else if (checkedCheckboxes.length === visibleCheckboxes.length) {
		selectAllCheckbox.indeterminate = false;
		selectAllCheckbox.checked = true;
	} else if (checkedCheckboxes.length > 0) {
		selectAllCheckbox.indeterminate = true;
		selectAllCheckbox.checked = false;
	} else {
		selectAllCheckbox.indeterminate = false;
		selectAllCheckbox.checked = false;
	}
}

// 이용권 검색 필터
function filterTicketList() {
	var searchText = $('#ticket-search').val().toLowerCase();
	
	$('#ticket-list tr').each(function() {
		var ticketName = $(this).data('ticket-name') || '';
		
		if (ticketName.includes(searchText)) {
			$(this).show();
		} else {
			$(this).hide();
		}
	});
	
	updateSelectAllCheckbox();
	updateSelectedTicketCount();
}

// 사이드바의 이용권 수 업데이트
function updateSidebarTicketCount(itemSno, ticketCount) {
	// 사이드바에서 해당 아이템 찾기
	var targetItem = $('[data-item-sno="' + itemSno + '"]');
	if (targetItem.length > 0) {
		// 이용권 수 배지 업데이트
		var badge = targetItem.find('.ticket-count-badge');
		if (badge.length > 0) {
			badge.text(ticketCount);
		}
	}
}

// 이용권 선택 저장
function saveTicketSelection() {
	console.log('💾 saveTicketSelection 호출됨');
	var itemSno = $('#modal-ticket-selection').data('item-sno');
	var scheduleSno = $('#modal-ticket-selection').data('schedule-sno');
	var isScheduleEdit = $('#modal-ticket-selection').data('is-schedule-edit');
	var selectedTickets = [];
	
	$('.ticket-checkbox:checked').each(function() {
		selectedTickets.push($(this).val());
	});
	
	console.log('💾 아이템 SNO:', itemSno);
	console.log('💾 스케줄 SNO:', scheduleSno);
	console.log('💾 is-schedule-edit:', isScheduleEdit);
	console.log('💾 선택된 이용권:', selectedTickets);
	
	// 스케줄 수정인지 그룹수업 수정인지 구분
	var ajaxUrl, ajaxData;
	if (isScheduleEdit && scheduleSno) {
		// 스케줄 수정인 경우
		console.log('💾 스케줄 이용권 저장 모드');
		ajaxUrl = '/tbcoffmain/ajax_save_schedule_ticket_selection';
		ajaxData = {
			gx_schd_mgmt_sno: scheduleSno,
			selected_tickets: selectedTickets
		};
	} else {
		// 그룹수업 수정인 경우
		console.log('💾 그룹수업 이용권 저장 모드');
		ajaxUrl = '/tbcoffmain/ajax_save_ticket_selection';
		ajaxData = {
			gx_item_sno: itemSno,
			selected_tickets: selectedTickets
		};
	}
	
	jQuery.ajax({
		url: ajaxUrl,
		type: 'POST',
		data: ajaxData,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				alert('이용권 설정이 저장되었습니다.');
				
				// 버튼 텍스트 업데이트
				var eventCountText = '';
				if (selectedTickets.length === 0) {
					eventCountText = '참석 가능한 이용권 없음 (선택추가)';
				} else {
					eventCountText = '참석 가능한 이용권 ' + selectedTickets.length + '개 (선택추가)';
				}
				
				if (isScheduleEdit && scheduleSno) {
					// 스케줄 수정 모달의 버튼 텍스트 업데이트
					$('#btn-schedule-ticket-selection').text(eventCountText);
					console.log('💾 스케줄 버튼 텍스트 업데이트:', eventCountText);
				} else {
					// 그룹수업 수정 모달의 버튼 텍스트 업데이트
					$('#btn-ticket-selection').text(eventCountText);
					// 사이드바의 해당 그룹수업 아이템의 이용권 수 업데이트
					updateSidebarTicketCount(itemSno, selectedTickets.length);
					console.log('💾 그룹수업 버튼 텍스트 업데이트:', eventCountText);
				}
				
				// 모달 닫기
				$('#modal-ticket-selection').modal('hide');
			} else {
				alert('저장 중 오류가 발생했습니다.');
			}
		}
	}).done((res) => {
		console.log('💾 이용권 설정 저장 성공');
	}).fail((error) => {
		console.log('💾 이용권 설정 저장 실패');
		handleLoginExpiry('<script>');
	});
}

// 숫자 입력 검증 함수 (0 이상의 정수만 허용, 공백 허용)
function validateNumberInput(input) {
	var value = input.value;
	
	// 공백인 경우 허용
	if (value === '') {
		return;
	}
	
	// 숫자가 아닌 문자나 음수, 소수점이 포함된 경우 제거
	var numericValue = value.replace(/[^0-9]/g, '');
	
	// 값이 변경되었다면 업데이트
	if (value !== numericValue) {
		input.value = numericValue;
	}
	
	// 0으로 시작하는 다자리 숫자는 앞의 0 제거 (단, '0' 하나는 허용)
	if (numericValue.length > 1 && numericValue.charAt(0) === '0') {
		input.value = parseInt(numericValue, 10).toString();
	}
}

// 자리예약 체크박스 변경 시 예약 인원 필드 활성화/비활성화 (그룹수업용)
function toggleReservationField() {
	var isChecked = $('#edit_reservation').is(':checked');
	$('#edit_reservation_num').prop('disabled', !isChecked);
	
	if (!isChecked) {
		$('#edit_reservation_num').val('');
	}
}

// 자리예약 체크박스 변경 시 예약 인원 필드 활성화/비활성화 (스케줄용)
function toggleScheduleReservationField() {
	var isChecked = $('#edit_schedule_reservation').is(':checked');
	$('#edit_schedule_reservation_num').prop('disabled', !isChecked);
	
	if (!isChecked) {
		$('#edit_schedule_reservation_num').val('');
	}
}

// 수업 정원 인원 변경 시 처리
function handleCapacityChange(input) {
	var capacity = parseInt(input.value) || 0;
	var reservationCheckbox = document.getElementById('edit_reservation');
	var reservationNumInput = document.getElementById('edit_reservation_num');
	
	// 자리 예약 가능이 켜져 있고 예약 가능 인원이 수업 정원보다 크면 수업 정원으로 변경
	if (reservationCheckbox.checked) {
		var currentReservationNum = parseInt(reservationNumInput.value) || 0;
		if (currentReservationNum > capacity) {
			reservationNumInput.value = capacity;
		}
		// 최대값 설정
		reservationNumInput.max = capacity;
	}
}

// 자리 예약 가능 토글 시 처리
function handleReservationToggle() {
	var reservationCheckbox = document.getElementById('edit_reservation');
	var reservationNumInput = document.getElementById('edit_reservation_num');
	var capacityInput = document.getElementById('edit_capacity');
	
	if (reservationCheckbox.checked) {
		// 자리 예약 가능이 켜질 때 수업 정원 인원을 자동으로 입력
		var capacity = parseInt(capacityInput.value) || 0;
		if (capacity > 0) {
			reservationNumInput.value = capacity;
		}
		// 최대값 설정
		reservationNumInput.max = capacity;
	} else {
		// 자리 예약 가능이 꺼질 때 최대값 제한 해제
		reservationNumInput.removeAttribute('max');
	}
}

// 자리 예약 가능 인원 변경 시 처리
function handleReservationNumChange(input) {
	var capacityInput = document.getElementById('edit_capacity');
	var capacity = parseInt(capacityInput.value) || 0;
	var reservationNum = parseInt(input.value) || 0;
	
	// 자리 예약 가능 인원이 수업 정원보다 크지 않도록 제한
	if (reservationNum > capacity && capacity > 0) {
		input.value = capacity;
	}
}

// 자동 공개/폐강 설정 모달 열기
function openAutoScheduleModal() {
	var itemSno = $('#modal-group-class-edit').data('item-sno');
	
	// 부모 모달 비활성화
	$('#modal-group-class-edit .modal-content').addClass('modal-disabled');
	$('#modal-group-class-edit .modal-content *').prop('disabled', true);
	
	// 모달에 아이템 SNO 저장
	$('#modal-auto-schedule').data('item-sno', itemSno);
	
	// 기존 설정값 로드 (추후 구현)
	loadAutoScheduleSettings();
	
	// 모달 열기
	$('#modal-auto-schedule').modal('show');
}

// 자동 공개/폐강 설정값 로드
function loadAutoScheduleSettings() {
	var itemSno = $('#modal-auto-schedule').data('item-sno');
	
	// AJAX로 기존 설정값 가져오기
	$.ajax({
		url: '/tbcoffmain/ajax_get_auto_schedule_settings',
		type: 'POST',
		data: {
			gx_item_sno: itemSno
		},
		dataType: 'json',
		success: function(result) {
			if (result.result === 'true' && result.data) {
				var data = result.data;
				
				// 자동 공개 설정 로드
				if (data.AUTO_SHOW_YN === 'Y') {
					$('#auto_open_enable').prop('checked', true);
					$('#auto_open_settings').show();
					
									// 단위에 따른 설정
				$('#auto_open_days').val(data.AUTO_SHOW_D || 1); // AUT_SHOW_D -> 상단 숫자 입력 필드
				
				if (data.AUTO_SHOW_UNIT === '1') {
					$('#auto_open_unit').val('day');
					$('#auto_open_weekday').hide();
				} else {
					$('#auto_open_unit').val('week');
					$('#auto_open_weekday').val(data.AUTO_SHOW_WEEK || '1').show();
					// AUTO_SHOW_WEEK_DUR -> 하단 주 단위 입력 필드 (reserv_d)
					setTimeout(function() {
						$('#reserv_d').val(data.AUTO_SHOW_WEEK_DUR || 1);
					}, 100);
				}
					
					// 시간 설정
					if (data.AUTO_SHOW_TIME) {
						var timeParts = data.AUTO_SHOW_TIME.split(':');
						$('#auto_open_hour').val(timeParts[0] || '13');
						$('#auto_open_minute').val(timeParts[1] || '00');
					}
				} else {
					$('#auto_open_enable').prop('checked', false);
					$('#auto_open_settings').hide();
					$('#auto_open_result').hide();
				}
				
				// 자동 폐강 설정 로드
				if (data.AUTO_CLOSE_YN === 'Y') {
					$('#auto_close_enable').prop('checked', true);
					$('#auto_close_settings').show();
					$('#auto_close_time').val(data.AUTO_CLOSE_MIN || '15');
					$('#auto_close_min_people').val(data.AUTO_CLOSE_MIN_NUM || 28);
				} else {
					$('#auto_close_enable').prop('checked', false);
					$('#auto_close_settings').hide();
				}
				
				updateAutoOpenPreview();
			} else {
				// 기본값 설정
				setDefaultAutoScheduleSettings();
			}
		},
		error: function() {
			console.log('자동 공개/폐강 설정 로드 실패');
			setDefaultAutoScheduleSettings();
		}
	});
	
	// 이벤트 핸들러 등록
	registerAutoScheduleEventHandlers();
}

// 기본값 설정 함수
function setDefaultAutoScheduleSettings() {
	// 기본값으로 자동 공개 체크박스를 체크하고 설정 표시
	$('#auto_open_enable').prop('checked', true);
	$('#auto_open_settings').show();
	$('#auto_open_result').show(); // 미리보기 표시
	$('#auto_open_unit').val('day');
	$('#auto_open_days').val(1); // auto_open_days는 항상 표시
	$('#auto_open_weekday').hide();
	$('#auto_open_hour').val('13');
	$('#auto_open_minute').val('00');
	
	// 기본값으로 자동 폐강 체크박스를 체크하고 설정 표시
	$('#auto_close_enable').prop('checked', true);
	$('#auto_close_settings').show();
	$('#auto_close_time').val('15');
	$('#auto_close_min_people').val(28);
	
	updateAutoOpenPreview();
}


// 이벤트 핸들러 등록 함수
function registerAutoScheduleEventHandlers() {
	// 자동 공개 체크박스 이벤트 핸들러 등록
	$('#auto_open_enable').off('change').on('change', function() {
		if ($(this).is(':checked')) {
			$('#auto_open_settings').show();
			$('#auto_open_result').show();
			updateAutoOpenPreview();
		} else {
			$('#auto_open_settings').hide();
			$('#auto_open_result').hide();
		}
	});
	
	// 자동 폐강 체크박스 이벤트 핸들러 등록
	$('#auto_close_enable').off('change').on('change', function() {
		if ($(this).is(':checked')) {
			$('#auto_close_settings').show();
		} else {
			$('#auto_close_settings').hide();
		}
	});
	
	// 자동 공개 설정 변경 시 미리보기 업데이트
	$('#auto_open_days, #auto_open_unit, #auto_open_hour, #auto_open_minute, #auto_open_weekday').off('change').on('change', function() {
		updateAutoOpenPreview();
	});
	
	// 단위 변경 시 요일 선택기 표시/숨김
	$('#auto_open_unit').off('change').on('change', function() {
		if ($(this).val() === 'week') {
			$('#auto_open_weekday').show();
		} else {
			$('#auto_open_weekday').hide();
		}
		updateAutoOpenPreview();
	});
}

// 자동 공개 미리보기 업데이트
function updateAutoOpenPreview() {
	var days = $('#auto_open_days').val();
	var unit = $('#auto_open_unit').val();
	var hour = $('#auto_open_hour').val();
	var minute = $('#auto_open_minute').val();
	var weekday = $('#auto_open_weekday').val();
	
	var unitText = '';
	var resultText = '';
	var displayValue = '';
	
	if (unit === 'day') {
		unitText = '일';
		displayValue = days || 1; // 사용자가 입력한 일수 사용
		resultText = '<span class="text-primary">' + displayValue + '</span>' + unitText + '씩 예약할 수 있도록 공개됩니다.';
	} else if (unit === 'week') {
		unitText = '주';
		var currentReservD = $('#reserv_d').val() || 1;
		resultText = '<input type="number" class="form-control form-control-sm text-center d-inline-block" id="reserv_d" value="' + currentReservD + '" min="1" max="30" style="width: 60px; margin-right: 3px;" oninput="updateAutoOpenPreview(); validateNumberInput(this)">' + unitText + '씩 예약할 수 있도록 공개됩니다.';
	}
	
	$('#auto_open_result').html(resultText);
}

// 자동 공개/폐강 설정 저장
function saveAutoScheduleSettings() {
	var itemSno = $('#modal-auto-schedule').data('item-sno');
	var scheduleSno = $('#modal-auto-schedule').data('schedule-sno');
	var isScheduleEdit = $('#modal-auto-schedule').data('is-schedule-edit');
	
	console.log('📅 saveAutoScheduleSettings 호출됨');
	console.log('📅 itemSno:', itemSno);
	console.log('📅 scheduleSno:', scheduleSno);
	console.log('📅 isScheduleEdit:', isScheduleEdit);
	
	// 자동 공개 설정
	var autoOpenEnable = $('#auto_open_enable').is(':checked');
	var autoOpenDays = $('#auto_open_days').val(); // AUT_SHOW_D - 상단 숫자 입력 필드
	var reservD = $('#reserv_d').val(); // AUTO_SHOW_WEEK_DUR - 하단 주 단위 입력 필드
	var autoOpenUnit = $('#auto_open_unit').val();
	var autoOpenWeekday = $('#auto_open_weekday').val();
	var autoOpenHour = $('#auto_open_hour').val();
	var autoOpenMinute = $('#auto_open_minute').val();
	
	// 자동 폐강 설정
	var autoCloseEnable = $('#auto_close_enable').is(':checked');
	var autoCloseMin = $('#auto_close_time').val();
	var autoCloseMinPeople = $('#auto_close_min_people').val();
	
	// 유효성 검사
	if (autoOpenEnable && (!autoOpenDays || autoOpenDays < 1)) {
		alert('자동 공개 일수를 올바르게 입력해주세요.');
		return;
	}
	
	if (autoOpenEnable && autoOpenUnit === 'week' && (!reservD || reservD < 1)) {
		alert('주 단위 예약 일수를 올바르게 입력해주세요.');
		return;
	}
	
	if (autoCloseEnable && (!autoCloseMin || autoCloseMin === '')) {
		alert('자동 폐강 시간을 선택해주세요.');
		return;
	}
	
	if (autoCloseEnable && (!autoCloseMinPeople || autoCloseMinPeople < 1)) {
		alert('최소 인원을 올바르게 입력해주세요.');
		return;
	}
	
	// 디버깅을 위한 로그 추가
	console.log('📅 자동 공개/폐강 설정 저장 데이터:');
	console.log('📅 autoOpenDays (AUTO_SHOW_D):', autoOpenDays);
	console.log('📅 reservD (AUTO_SHOW_WEEK_DUR):', reservD);
	console.log('📅 autoOpenUnit:', autoOpenUnit);
	
	// 스케줄 수정과 그룹수업 수정 구분
	var params = {
		auto_show_yn: autoOpenEnable ? 'Y' : 'N',
		auto_show_d: autoOpenEnable ? (autoOpenDays || 1) : 1, // 자동 공개 활성화시에만 사용자 입력값, 비활성화시 기본값 1
		auto_show_week_dur: reservD || 1, // AUTO_SHOW_WEEK_DUR - 하단 주 단위 입력 필드
		auto_show_unit: autoOpenUnit === 'day' ? '1' : (autoOpenUnit === 'week' ? '2' : '1'),
		auto_show_weekday: autoOpenWeekday || '1',
		auto_show_time: autoOpenHour + ':' + autoOpenMinute + ':00',
		auto_close_yn: autoCloseEnable ? 'Y' : 'N',
		auto_close_min: autoCloseEnable ? autoCloseMin : '15', // 자동 폐강 활성화시에만 사용자 입력값
		auto_close_min_num: autoCloseEnable ? autoCloseMinPeople : 1
	};
	
	// 스케줄 수정인지 그룹수업 수정인지 구분하여 파라미터 추가
	if (isScheduleEdit && scheduleSno) {
		params.gx_schd_mgmt_sno = scheduleSno;
		console.log('📅 스케줄 수정 모드 - scheduleSno:', scheduleSno);
	} else if (itemSno) {
		params.gx_item_sno = itemSno;
		console.log('📅 그룹수업 수정 모드 - itemSno:', itemSno);
	} else {
		alert('아이템 또는 스케줄 정보가 없습니다.');
		return;
	}
	
	console.log('📅 전송할 파라미터:', params);
	
	// AJAX로 설정 저장
	$.ajax({
		url: '/tbcoffmain/ajax_save_auto_schedule_settings',
		type: 'POST',
		data: params,
		dataType: 'json',
		success: function(result) {
			console.log('📅 자동 공개/폐강 설정 저장 성공:', result.message);
			if (result.result === 'true') {
				// 스케줄 수정 모드와 그룹수업 수정 모드 구분하여 표시 업데이트
				if (isScheduleEdit) {
					// 스케줄 수정 모달의 공개/폐강 스케줄 정보 업데이트
					updateScheduleScheduleDisplay(params);
				} else {
					// 그룹수업 수정 모달의 공개/폐강 스케줄 정보 업데이트
					updateScheduleDisplay(params);
				}
				
				alert('자동 공개/폐강 설정이 저장되었습니다.');
				$('#modal-auto-schedule').modal('hide');
				
				// 부모 모달 다시 활성화 (스케줄 수정과 그룹수업 수정 구분)
				if (isScheduleEdit) {
					enableScheduleParentModal();
				} else {
					enableParentModal();
				}
			} else {
				alert('설정 저장 중 오류가 발생했습니다: ' + (result.message || '알 수 없는 오류'));
			}
		},
		error: function(xhr, status, error) {
			console.error('자동 공개/폐강 설정 저장 실패:', error);
			console.log('자동 공개/폐강 설정 저장 실패: Internal Server Error');
			alert('자동 공개/폐강 설정 저장 실패: Internal Server Error');
		}
	});
}

// 분을 사용자 친화적인 시간 텍스트로 변환
function convertMinutesToTimeText(minutes) {
	var min = parseInt(minutes);
	
	if (min < 60) {
		return min + '분 전';
	} else if (min % 60 === 0) {
		var hours = min / 60;
		if (hours === 24) {
			return '1일 전';
		} else if (hours === 72) {
			return '3일 전';
		} else {
			return hours + '시간 전';
		}
	} else {
		var hours = Math.floor(min / 60);
		var remainingMin = min % 60;
		return hours + '시간 ' + remainingMin + '분 전';
	}
}

// 공개/폐강 스케줄 표시 업데이트 (그룹수업 수정용)
function updateScheduleDisplay(settings) {
	// 공개 스케줄 업데이트
	if (settings.auto_show_yn === 'Y') {
		var unitText = settings.auto_show_unit === '1' ? '일' : '주';
		var openText = settings.auto_show_d + unitText + '전 ';
		
		// 시간 형식 변경 (HH:MM:SS -> HH시 또는 HH시 MM분)
		var timeParts = settings.auto_show_time.split(':');
		var hour = parseInt(timeParts[0]);
		var minute = parseInt(timeParts[1]);
		
		openText += hour + '시';
		if (minute > 0) {
			openText += ' ' + minute + '분';
		}
		openText += '부터 공개';
		
		$('#open_schedule_text').text(openText);
	} else {
		$('#open_schedule_text').text('미설정');
	}
	
	// 폐강 스케줄 업데이트
	if (settings.auto_close_yn === 'Y') {
		var timeText = convertMinutesToTimeText(settings.auto_close_min);
		var closeText = '수업 시작후 ' + timeText + ' 까지 최소인원 ' + settings.auto_close_min_num + '명이 안될시 폐강';
		$('#close_schedule_text').text(closeText);
	} else {
		$('#close_schedule_text').text('미설정');
	}
}

// 공개/폐강 스케줄 표시 업데이트 (스케줄 수정용)
function updateScheduleScheduleDisplay(settings) {
	console.log('📅 스케줄 수정 모달의 자동 공개/폐강 설정이 업데이트됨:', settings);
	
	// 스케줄 수정 모달의 자동 공개/폐강 설정 표시 업데이트
	// 공개 스케줄 업데이트
	if (settings.auto_show_yn === 'Y') {
		var unitText = settings.auto_show_unit === '1' ? '일' : '주';
		var openText = settings.auto_show_d + unitText + '전 ';
		
		// 시간 형식 변경 (HH:MM:SS -> HH시 또는 HH시 MM분)
		var timeParts = settings.auto_show_time.split(':');
		var hour = parseInt(timeParts[0]);
		var minute = parseInt(timeParts[1]);
		
		openText += hour + '시';
		if (minute > 0) {
			openText += ' ' + minute + '분';
		}
		openText += '부터 공개';
		
		$('#schedule_open_schedule_text').text(openText);
	} else {
		$('#schedule_open_schedule_text').text('미설정');
	}
	
	// 폐강 스케줄 업데이트
	if (settings.auto_close_yn === 'Y') {
		var timeText = convertMinutesToTimeText(settings.auto_close_min);
		var closeText = '수업 시작후 ' + timeText + ' 까지 최소인원 ' + settings.auto_close_min_num + '명이 안될시 폐강';
		$('#schedule_close_schedule_text').text(closeText);
	} else {
		$('#schedule_close_schedule_text').text('미설정');
	}
}

// 부모 모달 활성화 (그룹수업 수정용)
function enableParentModal() {
	console.log('🔴 enableParentModal 호출됨 (그룹수업 수정)');
	$('#modal-group-class-edit .modal-content').removeClass('modal-disabled');
	$('#modal-group-class-edit .modal-content *').prop('disabled', false);
	console.log('🔴 그룹수업 모달 활성화 완료');
}

// 스케줄 수정 모달 활성화
function enableScheduleParentModal() {
	console.log('🟨 enableScheduleParentModal 호출됨');
	$('#modal-schedule-edit .modal-content').removeClass('modal-disabled');
	$('#modal-schedule-edit .modal-content *').prop('disabled', false);
	
	// body에서 스케줄 이미지 모달 클래스 제거
	$('body').removeClass('schedule-image-modal-open');
	console.log('🟨 body에서 schedule-image-modal-open 클래스 제거');
	console.log('🟨 스케줄 모달 활성화 완료');
}

// 스케줄 수정용 자동 공개/폐강 설정 모달 열기
function openScheduleAutoScheduleModal() {
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	console.log('📅 스케줄 자동 공개/폐강 설정 모달 열기 - scheduleSno:', scheduleSno);
	
	if (!scheduleSno) {
		alert('스케줄 정보가 없습니다.');
		return;
	}
	
	// 부모 모달 비활성화
	$('#modal-schedule-edit .modal-content').addClass('modal-disabled');
	$('#modal-schedule-edit .modal-content *').prop('disabled', true);
	
	// 자동 공개/폐강 설정 모달에 스케줄 SNO 저장 및 플래그 설정
	$('#modal-auto-schedule').data('schedule-sno', scheduleSno);
	$('#modal-auto-schedule').data('is-schedule-edit', true);
	
	// 현재 설정 로드
	loadScheduleAutoScheduleSettings(scheduleSno);
	
	// 모달 열기
	$('#modal-auto-schedule').modal('show');
}

// 스케줄의 자동 공개/폐강 설정 로드
function loadScheduleAutoScheduleSettings(scheduleSno) {
	$.ajax({
		url: '/tbcoffmain/ajax_get_schedule_auto_schedule_settings',
		type: 'POST',
		data: { gx_schd_mgmt_sno: scheduleSno },
		dataType: 'json',
		success: function(result) {
			if (result.result === 'true' && result.data) {
				var data = result.data;
				console.log('📅 스케줄 자동 설정 로드 성공:', data);
				
				// 폼에 데이터 설정
				$('#auto_open_enable').prop('checked', data.AUTO_SHOW_YN === 'Y');
				$('#auto_open_days').val(data.AUTO_SHOW_D || 1);
				$('#auto_open_unit').val(data.AUTO_SHOW_UNIT === '2' ? 'week' : 'day');
				$('#auto_open_weekday').val(data.AUTO_SHOW_WEEK || '1');
				
				// 시간 분리
				if (data.AUTO_SHOW_TIME) {
					var timeParts = data.AUTO_SHOW_TIME.split(':');
					$('#auto_open_hour').val(timeParts[0] || '13');
					$('#auto_open_minute').val(timeParts[1] || '00');
				}
				
				$('#auto_close_enable').prop('checked', data.AUTO_CLOSE_YN === 'Y');
				$('#auto_close_min').val(data.AUTO_CLOSE_MIN || '15');
				$('#auto_close_min_people').val(data.AUTO_CLOSE_MIN_NUM || 1);
				
				// 주 단위 기간 설정
				$('#reserv_d').val(data.AUTO_SHOW_WEEK_DUR || 1);
				
				// UI 업데이트
				toggleAutoOpenSettings();
				toggleAutoCloseSettings();
			} else {
				console.log('📅 스케줄 자동 설정 로드 실패:', result.message);
			}
		},
		error: function(xhr, status, error) {
			console.error('스케줄 자동 설정 로드 오류:', error);
		}
	});
}

// 스케줄 수정용 수업정산 설정 모달 열기
function openScheduleSettlementSetupModal() {
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	console.log('💰 스케줄 수업정산 설정 모달 열기 - scheduleSno:', scheduleSno);
	
	if (!scheduleSno) {
		alert('스케줄 정보가 없습니다.');
		return;
	}
	
	// 부모 모달 비활성화
	$('#modal-schedule-edit .modal-content').addClass('modal-disabled');
	$('#modal-schedule-edit .modal-content *').prop('disabled', true);
	
	// 수업정산 설정 모달에 스케줄 SNO 저장 및 플래그 설정
	$('#modal-settlement-setup').data('schedule-sno', scheduleSno);
	$('#modal-settlement-setup').data('is-schedule-edit', true);
	
	// 현재 설정 로드
	loadScheduleSettlementSettings(scheduleSno);
	
	// 모달 열기
	$('#modal-settlement-setup').modal('show');
}

// 스케줄의 수업정산 설정 로드 (그룹수업과 완전 동일한 코드, URL만 다름)
function loadScheduleSettlementSettings(scheduleSno) {
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_schedule_settlement_settings',
		type: 'POST',
		data: { gx_schd_mgmt_sno: scheduleSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				var settlementData = data['data'];
				
				// 디버깅용 로그
				console.log('정산 설정 데이터:', settlementData);
				console.log('PAY_FOR_ZERO_YN:', settlementData.PAY_FOR_ZERO_YN);
				console.log('USE_PAY_RATE_YN:', settlementData.USE_PAY_RATE_YN);
				console.log('PAY_RANGES:', settlementData.PAY_RANGES);
				
				// 0명 참석시 정산 여부 설정
				$('#zero_attendance_payment').prop('checked', settlementData.PAY_FOR_ZERO_YN === 'Y');
				
				// 인원당 수당 사용 여부 설정
				$('#attendance_based_payment').prop('checked', settlementData.USE_PAY_RATE_YN === 'Y');
				toggleAttendanceBasedPayment(); // UI 업데이트
				
				// 구간별 수당 정보 로드
				if (settlementData.PAY_RANGES && settlementData.PAY_RANGES.length > 0) {
					// 기존 구간들 삭제 (첫 번째 구간 제외) - 그룹수업과 완전 동일
					$('.settlement-range[data-range-index]:not([data-range-index="0"])').remove();
					
					settlementData.PAY_RANGES.forEach(function(range, index) {
						console.log('🔥 구간 처리 - index:', index, 'index === 0:', (index === 0), 'range:', range);
						if (index === 0) {
							// 첫 번째 구간 업데이트 - 그룹수업과 완전 동일
							console.log('🔥 첫 번째 구간 처리 중');
							$('#range_start').val(range.CLAS_ATD_NUM_S);
							$('#range_end').val(range.CLAS_ATD_NUM_E);
							$('#range_percent').val(range.PAY_RATE);
							console.log('🔥 첫 번째 구간 처리 완료');
						} else {
							// 추가 구간 생성 - 그룹수업과 완전 동일
							console.log('🔥 두 번째 구간 생성 시작:', range.CLAS_ATD_NUM_S, range.CLAS_ATD_NUM_E, range.PAY_RATE);
							try {
								addSettlementRangeWithData(range.CLAS_ATD_NUM_S, range.CLAS_ATD_NUM_E, range.PAY_RATE);
								console.log('🔥 addSettlementRangeWithData 함수 호출 완료');
							} catch (error) {
								console.error('🔥 addSettlementRangeWithData 오류:', error);
								
								// 직접 HTML 생성으로 대체
								var rangeHtml = `
									<div class="d-flex align-items-center mb-2 settlement-range" data-range-index="${index}">
										<input type="number" class="form-control form-control-sm text-center me-1 range-start" value="${range.CLAS_ATD_NUM_S}" min="${range.CLAS_ATD_NUM_S}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
										<span class="small me-2">명 부터</span>
										<input type="number" class="form-control form-control-sm text-center me-1 range-end" value="${range.CLAS_ATD_NUM_E}" min="${parseInt(range.CLAS_ATD_NUM_S) + 1}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
										<span class="small me-2">명 까지 1 회당 수업비의</span>
										<input type="number" class="form-control form-control-sm text-center me-1 range-percent" value="${range.PAY_RATE}" min="0" max="100" style="width: 60px;" oninput="validateNumberOnly(this); validateNumberInput(this)">
										<span class="small">%</span>
										<button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeSettlementRange(this)" style="padding: 2px 6px;">×</button>
									</div>
								`;
								$('.btn-outline-secondary:contains("+ 구간 추가")').closest('.mb-3').before(rangeHtml);
								console.log('🔥 직접 HTML 생성으로 구간 추가 완료');
							}
						}
					});
					
					updateRangeConstraints();
				}
				
				// 설정 내역 표시 업데이트
				setTimeout(function() {
					updateScheduleSettlementDisplay();
				}, 100);
			} else {
				console.log('수업정산 설정 로드 실패:', data['message']);
			}
		}
	}).fail((error) => {
		console.log('수업정산 설정 로드 실패:', error);
		handleLoginExpiry('<script>');
	});
}

// 스케줄 수업정산 설정 내역 표시 업데이트
function updateScheduleSettlementDisplay() {
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	
	// 현재 설정된 값들 가져오기
	var payForZeroYn = $('#zero_attendance_payment').is(':checked') ? 'Y' : 'N';
	var usePayRateYn = $('#attendance_based_payment').is(':checked') ? 'Y' : 'N';
	
	var settlementHtml = '';
	
	// 0명 참석시 정산 여부
	settlementHtml += '<div class="mb-1">';
	settlementHtml += '<span class="badge ' + (payForZeroYn === 'Y' ? 'bg-success' : 'bg-secondary') + ' me-2">0명 참석시</span>';
	if (payForZeroYn === 'Y') {
		settlementHtml += '<span class="text-success">수업비 지급</span>';
	} else {
		settlementHtml += '<span class="text-muted">수업비 미지급</span>';
	}
	settlementHtml += '</div>';
	
	// 인원당 수당 사용 여부
	settlementHtml += '<div class="mb-1">';
	settlementHtml += '<span class="badge ' + (usePayRateYn === 'Y' ? 'bg-info' : 'bg-secondary') + ' me-2">수업비 방식</span>';
	if (usePayRateYn === 'Y') {
		settlementHtml += '<span class="text-info">수업 참여 인원수에따라 회당 수업비 요율 적용</span>';
		
		// 구간별 수당 정보
		var ranges = [];
		$('.settlement-range, [data-range-index]').each(function() {
			var $range = $(this);
			var startValue = parseInt($range.find('.range-start').val()) || 0;
			var endValue = parseInt($range.find('.range-end').val()) || 0;
			var percentValue = parseInt($range.find('.range-percent').val()) || 0;
			
			if (endValue > startValue) {
				ranges.push(startValue + '~' + endValue + '명: ' + percentValue + '%');
			}
		});
		
		if (ranges.length > 0) {
			settlementHtml += '<div class="mt-1 small text-muted">';
			settlementHtml += '<i class="fas fa-list-ul me-1"></i>';
			settlementHtml += ranges.join(', ');
			settlementHtml += '</div>';
		}
	} else {
		settlementHtml += '<span class="text-muted">고정 수업비</span>';
	}
	settlementHtml += '</div>';
	
	// 스케줄 수정 모달의 수업정산 설정 내역 표시 영역 업데이트
	$('#schedule-settlement-display').html(settlementHtml);
}

// 스케줄 모달 열 때 자동 공개/폐강 설정 내역 로드 및 표시
function loadAndDisplayScheduleAutoSettings(scheduleSno) {
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_schedule_auto_schedule_settings',
		type: 'POST',
		data: { gx_schd_mgmt_sno: scheduleSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'json',
		success: function (result) {
			if (result.result === 'true' && result.data) {
				var autoData = result.data;
				
				// 공개 스케줄 표시
				if (autoData.AUTO_SHOW_YN === 'Y') {
					var unitText = autoData.AUTO_SHOW_UNIT === '2' ? '주' : '일';
					var openText = autoData.AUTO_SHOW_D + unitText + '전 ';
					
					// 시간 형식 변경 (HH:MM:SS -> HH시 또는 HH시 MM분)
					if (autoData.AUTO_SHOW_TIME) {
						var timeParts = autoData.AUTO_SHOW_TIME.split(':');
						var hour = parseInt(timeParts[0]);
						var minute = parseInt(timeParts[1]);
						
						openText += hour + '시';
						if (minute > 0) {
							openText += ' ' + minute + '분';
						}
						openText += '부터 공개';
					}
					
					$('#schedule_open_schedule_text').text(openText);
				} else {
					$('#schedule_open_schedule_text').text('미설정');
				}
				
				// 폐강 스케줄 표시
				if (autoData.AUTO_CLOSE_YN === 'Y') {
					var timeText = convertMinutesToTimeText(autoData.AUTO_CLOSE_MIN);
					var closeText = '수업 시작후 ' + timeText + ' 까지 최소인원 ' + autoData.AUTO_CLOSE_MIN_NUM + '명이 안될시 폐강';
					$('#schedule_close_schedule_text').text(closeText);
				} else {
					$('#schedule_close_schedule_text').text('미설정');
				}
			} else {
				// 데이터가 없으면 기본 설정 표시
				$('#schedule_open_schedule_text').text('미설정');
				$('#schedule_close_schedule_text').text('미설정');
			}
		}
	}).fail((error) => {
		// 오류 시 기본 텍스트 표시
		$('#schedule_open_schedule_text').text('미설정');
		$('#schedule_close_schedule_text').text('미설정');
	});
}

// 스케줄 모달 열 때 수업정산 설정 내역 로드 및 표시
function loadAndDisplayScheduleSettlementInfo(scheduleSno) {
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_schedule_settlement_settings',
		type: 'POST',
		data: { gx_schd_mgmt_sno: scheduleSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'json',
		success: function (result) {
			if (result.result === 'true' && result.data) {
				var settlementData = result.data;
				
				var settlementHtml = '';
				
				// 0명 참석시 정산 여부
				var payForZeroYn = settlementData.PAY_FOR_ZERO_YN || 'N';
				settlementHtml += '<div class="mb-1">';
				settlementHtml += '<span class="badge ' + (payForZeroYn === 'Y' ? 'bg-success' : 'bg-secondary') + ' me-2">0명 참석시</span>';
				if (payForZeroYn === 'Y') {
					settlementHtml += '<span class="text-success">수업비 지급</span>';
				} else {
					settlementHtml += '<span class="text-muted">수업비 미지급</span>';
				}
				settlementHtml += '</div>';
				
				// 인원당 수당 사용 여부
				var usePayRateYn = settlementData.USE_PAY_RATE_YN || 'N';
				settlementHtml += '<div class="mb-1">';
				settlementHtml += '<span class="badge ' + (usePayRateYn === 'Y' ? 'bg-info' : 'bg-secondary') + ' me-2">수업비 방식</span>';
				if (usePayRateYn === 'Y') {
					settlementHtml += '<span class="text-info">수업 참여 인원수에따라 회당 수업비 요율 적용</span>';
					
					// 구간별 수당 정보
					if (settlementData.PAY_RANGES && settlementData.PAY_RANGES.length > 0) {
						var ranges = [];
						settlementData.PAY_RANGES.forEach(function(range) {
							ranges.push(range.CLAS_ATD_NUM_S + '~' + range.CLAS_ATD_NUM_E + '명: ' + range.PAY_RATE + '%');
						});
						
						if (ranges.length > 0) {
							settlementHtml += '<div class="mt-1 small text-muted">';
							settlementHtml += '<i class="fas fa-list-ul me-1"></i>';
							settlementHtml += ranges.join(', ');
							settlementHtml += '</div>';
						}
					}
				} else {
					settlementHtml += '<span class="text-muted">고정 수업비</span>';
				}
				settlementHtml += '</div>';
				
				// 수업정산 설정 내역 표시 영역 업데이트
				$('#schedule-settlement-display').html(settlementHtml);
			} else {
				// 데이터가 없으면 기본 설정 표시
				var defaultHtml = '<div class="text-center text-muted py-2">';
				defaultHtml += '<i class="fas fa-info-circle me-2"></i>';
				defaultHtml += '수업정산 설정이 없습니다. 설정하기 버튼을 클릭하세요.';
				defaultHtml += '</div>';
				$('#schedule-settlement-display').html(defaultHtml);
			}
		}
	}).fail((error) => {
		// 오류 시 기본 텍스트 표시
		$('#schedule-settlement-display').html('미설정');
	});
}

// 수업정산 설정 모달 열기
function openSettlementSetupModal() {
	var itemSno = $('#modal-group-class-edit').data('item-sno');
	
	// 부모 모달 비활성화
	$('#modal-group-class-edit .modal-content').addClass('modal-disabled');
	$('#modal-group-class-edit .modal-content *').prop('disabled', true);
	
	// 모달에 아이템 SNO 저장
	$('#modal-settlement-setup').data('item-sno', itemSno);
	
	// 기존 설정 로드
	loadSettlementSettings(itemSno);
	
	// 수업정산 설정 모달 열기
	$('#modal-settlement-setup').modal('show');
}

// 출석 인원당 수당 체크박스 토글
function toggleAttendanceBasedPayment() {
	var isChecked = $('#attendance_based_payment').is(':checked');
	
	if (isChecked) {
		// 체크된 경우: 구간 설정 표시, 인원당 수당 설명 표시
		$('#range_settings').show();
		$('#attendance_based_description').show();
		$('#fixed_payment_description').hide();
	} else {
		// 미체크된 경우: 구간 설정 숨김, 고정 수업비 설명 표시
		$('#range_settings').hide();
		$('#attendance_based_description').hide();
		$('#fixed_payment_description').show();
	}
}

// 구간 추가 기능
function addSettlementRange() {
	// 현재 구간 개수 확인
	var currentRanges = $('.settlement-range, [data-range-index]').length;
	var nextIndex = currentRanges;
	
	// 이전 구간의 마지막 값 가져오기
	var lastRangeEnd = 0;
	if (currentRanges > 0) {
		var lastRange = $('.settlement-range, [data-range-index]').last();
		lastRangeEnd = parseInt(lastRange.find('.range-end').val()) || 0;
	}
	
	var minStartValue = lastRangeEnd + 1;
	var defaultEndValue = minStartValue + 10; // 기본값으로 시작값 + 10
	
	var rangeHtml = `
		<div class="d-flex align-items-center mb-2 settlement-range" data-range-index="${nextIndex}">
							<input type="number" class="form-control form-control-sm text-center me-1 range-start" value="${minStartValue}" min="${minStartValue}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
			<span class="small me-2">명 부터</span>
			<input type="number" class="form-control form-control-sm text-center me-1 range-end" value="${defaultEndValue}" min="${minStartValue + 1}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
			<span class="small me-2">명 까지 1 회당 수업비의</span>
			<input type="number" class="form-control form-control-sm text-center me-1 range-percent" value="0" min="0" max="100" style="width: 60px;" oninput="validateNumberOnly(this); validateNumberInput(this)">
			<span class="small">%</span>
			<button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeSettlementRange(this)" style="padding: 2px 6px;">×</button>
		</div>
	`;
	
	// 구간 추가 버튼 바로 위에 새 구간 추가
	$('.btn-outline-secondary:contains("+ 구간 추가")').closest('.mb-3').before(rangeHtml);
}

// 구간 삭제 기능
function removeSettlementRange(button) {
	$(button).closest('.settlement-range').remove();
	updateRangeConstraints();
}

// 구간 제약 조건 업데이트
function updateRangeConstraints() {
	var ranges = $('.settlement-range, [data-range-index]');
	
	ranges.each(function(index) {
		var $range = $(this);
		var $startInput = $range.find('.range-start');
		var $endInput = $range.find('.range-end');
		
		if (index === 0) {
			// 첫 번째 구간은 시작값이 0으로 고정
			$startInput.val(0).prop('disabled', true);
			$endInput.attr('min', 1);
			
			// 첫 번째 구간의 종료값이 시작값보다 작으면 조정
			var endValue = parseInt($endInput.val()) || 0;
			if (endValue <= 0) {
				$endInput.val(1);
			}
		} else {
			// 이후 구간들은 이전 구간의 마지막 값 + 1부터 시작
			var prevRange = ranges.eq(index - 1);
			var prevEndValue = parseInt(prevRange.find('.range-end').val()) || 0;
			var minStartValue = prevEndValue + 1;
			
			$startInput.attr('min', minStartValue);
			if (parseInt($startInput.val()) < minStartValue) {
				$startInput.val(minStartValue);
			}
			
			var currentStartValue = parseInt($startInput.val()) || 0;
			$endInput.attr('min', currentStartValue + 1);
			
			// 종료값이 시작값보다 작거나 같으면 조정
			var endValue = parseInt($endInput.val()) || 0;
			if (endValue <= currentStartValue) {
				$endInput.val(currentStartValue + 1);
			}
		}
	});
}

// 범위 입력 검증
function validateRangeInputs(input) {
	var $input = $(input);
	var $range = $input.closest('.settlement-range, [data-range-index]');
	var $startInput = $range.find('.range-start');
	var $endInput = $range.find('.range-end');
	
	// 숫자만 허용
	validateNumberOnly(input);
	
	if ($input.hasClass('range-start')) {
		// 시작값 변경 시 종료값의 최소값 업데이트
		var startValue = parseInt($input.val()) || 0;
		$endInput.attr('min', startValue + 1);
		
		// 종료값이 시작값보다 작거나 같으면 조정
		var endValue = parseInt($endInput.val()) || 0;
		if (endValue <= startValue) {
			$endInput.val(startValue + 1);
		}
		
		// 다음 구간들의 제약 조건 업데이트
		updateRangeConstraints();
	} else if ($input.hasClass('range-end')) {
		// 종료값 변경 시 시작값보다 작으면 시작값에 맞춰 조정
		var endValue = parseInt($input.val()) || 0;
		var startValue = parseInt($startInput.val()) || 0;
		
		if (endValue <= startValue) {
			$input.val(startValue + 1);
		}
		
		// 다음 구간들의 제약 조건 업데이트
		updateRangeConstraints();
	}
}

// 숫자만 입력 허용
function validateNumberOnly(input) {
	var value = input.value;
	// 숫자가 아닌 문자 제거
	var numericValue = value.replace(/[^0-9]/g, '');
	
	if (value !== numericValue) {
		input.value = numericValue;
	}
	
	// 최소값/최대값 검증
	var min = parseInt(input.getAttribute('min'));
	var max = parseInt(input.getAttribute('max'));
	var currentValue = parseInt(input.value);
	
	if (!isNaN(min) && currentValue < min) {
		input.value = min;
	}
	if (!isNaN(max) && currentValue > max) {
		input.value = max;
	}
}

// 자식 모달 닫힘 이벤트 핸들러 등록
$(document).ready(function() {
	// 이용권 선택 모달이 닫힐 때
	$('#modal-ticket-selection').on('hidden.bs.modal', function () {
		console.log('🟤 티켓 선택 모달 닫힘 이벤트 발생');
		var isScheduleEdit = $(this).data('is-schedule-edit');
		console.log('🟤 is-schedule-edit 값:', isScheduleEdit);
		if (isScheduleEdit) {
			console.log('🟤 스케줄 수정 모달 활성화 함수 호출');
			enableScheduleParentModal();
		} else {
			console.log('🟤 그룹수업 수정 모달 활성화 함수 호출');
			enableParentModal();
		}
		
		// 데이터 초기화
		$(this).removeData('is-schedule-edit');
		$(this).removeData('schedule-sno');
		$('body').removeClass('schedule-image-modal-open');
		console.log('🟤 티켓 모달 데이터 초기화 완료');
	});
	
	// 자동 공개/폐강 모달이 닫힐 때
	$('#modal-auto-schedule').on('hidden.bs.modal', function () {
		console.log('🟡 자동 스케줄 모달 닫힘 이벤트 발생');
		var isScheduleEdit = $(this).data('is-schedule-edit');
		console.log('🟡 is-schedule-edit 값:', isScheduleEdit);
		if (isScheduleEdit) {
			console.log('🟡 스케줄 수정 모달 활성화 함수 호출');
			enableScheduleParentModal();
		} else {
			console.log('🟡 그룹수업 수정 모달 활성화 함수 호출');
			enableParentModal();
		}
		
		// 데이터 초기화
		$(this).removeData('is-schedule-edit');
		$(this).removeData('schedule-sno');
		$('body').removeClass('schedule-image-modal-open');
		console.log('🟡 자동 스케줄 모달 데이터 초기화 완료');
	});
	
	// 수업정산 설정 버튼 클릭 이벤트
	$('#btn-settlement-setup').on('click', function() {
		openSettlementSetupModal();
	});
	
	// 수업정산 설정 모달이 닫힐 때
	$('#modal-settlement-setup').on('hidden.bs.modal', function () {
		console.log('🟠 수업정산 모달 닫힘 이벤트 발생');
		var isScheduleEdit = $(this).data('is-schedule-edit');
		console.log('🟠 is-schedule-edit 값:', isScheduleEdit);
		if (isScheduleEdit) {
			console.log('🟠 스케줄 수정 모달 활성화 함수 호출');
			enableScheduleParentModal();
		} else {
			console.log('🟠 그룹수업 수정 모달 활성화 함수 호출');
			enableParentModal();
		}
		
		// 데이터 초기화
		$(this).removeData('is-schedule-edit');
		$(this).removeData('schedule-sno');
		$('body').removeClass('schedule-image-modal-open');
		console.log('🟠 수업정산 모달 데이터 초기화 완료');
	});
	
	// 수업정산 설정 저장 버튼 클릭 이벤트
	$('#btn-save-settlement').on('click', function() {
		saveSettlementSettings();
	});
});

// 수업정산 설정 로드
function loadSettlementSettings(itemSno) {
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_settlement_settings',
		type: 'POST',
		data: { gx_item_sno: itemSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				var settlementData = data['data'];
				
				// 디버깅용 로그
				console.log('정산 설정 데이터:', settlementData);
				console.log('PAY_FOR_ZERO_YN:', settlementData.PAY_FOR_ZERO_YN);
				console.log('USE_PAY_RATE_YN:', settlementData.USE_PAY_RATE_YN);
				console.log('PAY_RANGES:', settlementData.PAY_RANGES);
				
				// 0명 참석시 정산 여부 설정
				$('#zero_attendance_payment').prop('checked', settlementData.PAY_FOR_ZERO_YN === 'Y');
				
				// 인원당 수당 사용 여부 설정
				$('#attendance_based_payment').prop('checked', settlementData.USE_PAY_RATE_YN === 'Y');
				toggleAttendanceBasedPayment(); // UI 업데이트
				
				// 구간별 수당 정보 로드
				if (settlementData.PAY_RANGES && settlementData.PAY_RANGES.length > 0) {
					// 기존 구간들 삭제 (첫 번째 구간 제외)
					$('.settlement-range[data-range-index]:not([data-range-index="0"])').remove();
					
					settlementData.PAY_RANGES.forEach(function(range, index) {
						if (index === 0) {
							// 첫 번째 구간 업데이트
							$('#range_start').val(range.CLAS_ATD_CNT_S);
							$('#range_end').val(range.CLAS_ATD_CNT_E);
							$('#range_percent').val(range.PAY_RATE);
						} else {
							// 추가 구간 생성
							addSettlementRangeWithData(range.CLAS_ATD_CNT_S, range.CLAS_ATD_CNT_E, range.PAY_RATE);
						}
					});
					
					updateRangeConstraints();
				}
				
				// 설정 내역 표시 업데이트
				setTimeout(function() {
					updateSettlementDisplay();
				}, 100);
			} else {
				console.log('수업정산 설정 로드 실패:', data['message']);
			}
		}
	}).fail((error) => {
		console.log('수업정산 설정 로드 실패:', error);
		handleLoginExpiry('<script>');
	});
}

// 데이터와 함께 구간 추가 (로드용)
function addSettlementRangeWithData(startValue, endValue, percentValue) {
	var currentRanges = $('.settlement-range, [data-range-index]').length;
	var nextIndex = currentRanges;
	
	var rangeHtml = `
		<div class="d-flex align-items-center mb-2 settlement-range" data-range-index="${nextIndex}">
			<input type="number" class="form-control form-control-sm text-center me-1 range-start" value="${startValue}" min="${startValue}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
			<span class="small me-2">명 부터</span>
			<input type="number" class="form-control form-control-sm text-center me-1 range-end" value="${endValue}" min="${startValue + 1}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
			<span class="small me-2">명 까지 1 회당 수업비의</span>
			<input type="number" class="form-control form-control-sm text-center me-1 range-percent" value="${percentValue}" min="0" max="100" style="width: 60px;" oninput="validateNumberOnly(this); validateNumberInput(this)">
			<span class="small">%</span>
			<button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeSettlementRange(this)" style="padding: 2px 6px;">×</button>
		</div>
	`;
	
	// 구간 추가 버튼 바로 위에 새 구간 추가
	$('.btn-outline-secondary:contains("+ 구간 추가")').closest('.mb-3').before(rangeHtml);
}

// 수업정산 설정 저장
function saveSettlementSettings() {
	// 스케줄 수정 모드인지 그룹수업 수정 모드인지 확인
	var isScheduleEdit = $('#modal-settlement-setup').data('is-schedule-edit') || false;
	var itemSno = $('#modal-settlement-setup').data('item-sno');
	var scheduleSno = $('#modal-settlement-setup').data('schedule-sno');
	
	console.log('💰 수업정산 설정 저장 - isScheduleEdit:', isScheduleEdit, 'itemSno:', itemSno, 'scheduleSno:', scheduleSno);
	
	// 폼 데이터 수집
	var payForZeroYn = $('#zero_attendance_payment').is(':checked') ? 'Y' : 'N';
	var usePayRateYn = $('#attendance_based_payment').is(':checked') ? 'Y' : 'N';
	
	// 구간별 수당 정보 수집 (인원당 수당 사용시에만)
	var payRanges = [];
	if (usePayRateYn === 'Y') {
		$('.settlement-range, [data-range-index]').each(function() {
			var $range = $(this);
			var startValue = parseInt($range.find('.range-start').val()) || 0;
			var endValue = parseInt($range.find('.range-end').val()) || 0;
			var percentValue = parseInt($range.find('.range-percent').val()) || 0;
			
			if (endValue > startValue && percentValue >= 0) {
				payRanges.push({
					start: startValue,
					end: endValue,
					percent: percentValue
				});
			}
		});
	}
	
	// 저장 데이터 준비 (스케줄 수정과 그룹수업 수정 구분)
	var saveData = {
		pay_for_zero_yn: payForZeroYn,
		use_pay_rate_yn: usePayRateYn,
		pay_ranges: JSON.stringify(payRanges)
	};
	
	if (isScheduleEdit && scheduleSno) {
		saveData.gx_schd_mgmt_sno = scheduleSno;
	} else if (itemSno) {
		saveData.gx_item_sno = itemSno;
	} else {
		alert('아이템 또는 스케줄 정보가 없습니다.');
		return;
	}
	
	// AJAX로 저장 (스케줄 수정과 그룹수업 수정 구분하여 다른 엔드포인트 호출)
	var ajaxUrl = isScheduleEdit ? '/tbcoffmain/ajax_save_schedule_settlement_settings' : '/tbcoffmain/ajax_save_settlement_settings';
	
	console.log('💰 저장 요청 - URL:', ajaxUrl, 'Data:', saveData);
	
	jQuery.ajax({
		url: ajaxUrl,
		type: 'POST',
		data: saveData,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				alert('수업정산 설정이 저장되었습니다.');
				
				// 부모 모달의 수업정산 설정 내역 업데이트 (스케줄 수정과 그룹수업 수정 구분)
				if (isScheduleEdit) {
					updateScheduleSettlementDisplay();
					enableScheduleParentModal();
				} else {
					updateSettlementDisplay();
					enableParentModal();
				}
				
				// 모달 닫기
				$('#modal-settlement-setup').modal('hide');
			} else {
				alert('저장 실패: ' + (data['message'] || '알 수 없는 오류가 발생했습니다.'));
			}
		}
	}).fail((error) => {
		console.log('수업정산 설정 저장 실패:', error);
		handleLoginExpiry('<script>');
	});
}

// 수업정산 설정 내역 표시 업데이트
function updateSettlementDisplay() {
	var itemSno = $('#modal-group-class-edit').data('item-sno');
	
	// 현재 설정된 값들 가져오기
	var payForZeroYn = $('#zero_attendance_payment').is(':checked') ? 'Y' : 'N';
	var usePayRateYn = $('#attendance_based_payment').is(':checked') ? 'Y' : 'N';
	
	var settlementHtml = '';
	
	
	// 0명 참석시 정산 여부
	settlementHtml += '<div class="mb-1">';
	settlementHtml += '<span class="badge ' + (payForZeroYn === 'Y' ? 'bg-success' : 'bg-secondary') + ' me-2">0명 참석시</span>';
	if (payForZeroYn === 'Y') {
		settlementHtml += '<span class="text-success">수업비 지급</span>';
	} else {
		settlementHtml += '<span class="text-muted">수업비 미지급</span>';
	}
	settlementHtml += '</div>';
	
	// 인원당 수당 사용 여부
	settlementHtml += '<div class="mb-1">';
	settlementHtml += '<span class="badge ' + (usePayRateYn === 'Y' ? 'bg-info' : 'bg-secondary') + ' me-2">수업비 방식</span>';
	if (usePayRateYn === 'Y') {
		settlementHtml += '<span class="text-info">수업 참여 인원수에따라 회당 수업비 요율 적용</span>';
		
		// 구간별 수당 정보
		var ranges = [];
		$('.settlement-range, [data-range-index]').each(function() {
			var $range = $(this);
			var startValue = parseInt($range.find('.range-start').val()) || 0;
			var endValue = parseInt($range.find('.range-end').val()) || 0;
			var percentValue = parseInt($range.find('.range-percent').val()) || 0;
			
			if (endValue > startValue) {
				ranges.push(startValue + '~' + endValue + '명: ' + percentValue + '%');
			}
		});
		
		if (ranges.length > 0) {
			settlementHtml += '<div class="mt-1 small text-muted">';
			settlementHtml += '<i class="fas fa-list-ul me-1"></i>';
			settlementHtml += ranges.join(', ');
			settlementHtml += '</div>';
		}
	} else {
		settlementHtml += '<span class="text-muted">고정 수업비</span>';
	}
	settlementHtml += '</div>';
	
	// 수업정산 설정 내역 표시 영역 업데이트
	$('#settlement-display').html(settlementHtml);
}

// 그룹수업 모달 열 때 수업정산 설정 내역 로드 및 표시
function loadAndDisplaySettlementInfo(itemSno) {
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_settlement_settings',
		type: 'POST',
		data: { gx_item_sno: itemSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) {
				$('#settlement-display').html('미설정');
				return;
			}
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				var settlementData = data['data'];
				
				var settlementHtml = '';
				
				
				// 0명 참석시 정산 여부
				var payForZeroYn = settlementData.PAY_FOR_ZERO_YN || 'N';
				settlementHtml += '<div class="mb-1">';
				settlementHtml += '<span class="badge ' + (payForZeroYn === 'Y' ? 'bg-success' : 'bg-secondary') + ' me-2">0명 참석시</span>';
				if (payForZeroYn === 'Y') {
					settlementHtml += '<span class="text-success">수업비 지급</span>';
				} else {
					settlementHtml += '<span class="text-muted">수업비 미지급</span>';
				}
				settlementHtml += '</div>';
				
				// 인원당 수당 사용 여부
				var usePayRateYn = settlementData.USE_PAY_RATE_YN || 'N';
				settlementHtml += '<div class="mb-1">';
				settlementHtml += '<span class="badge ' + (usePayRateYn === 'Y' ? 'bg-info' : 'bg-secondary') + ' me-2">수업비 방식</span>';
				if (usePayRateYn === 'Y') {
					settlementHtml += '<span class="text-info">수업 참여 인원수에따라 회당 수업비 요율 적용</span>';
					
					// 구간별 수당 정보
					if (settlementData.PAY_RANGES && settlementData.PAY_RANGES.length > 0) {
						var ranges = [];
						settlementData.PAY_RANGES.forEach(function(range) {
							ranges.push(range.CLAS_ATD_CNT_S + '~' + range.CLAS_ATD_CNT_E + '명: ' + range.PAY_RATE + '%');
						});
						
						if (ranges.length > 0) {
							settlementHtml += '<div class="mt-1 small text-muted">';
							settlementHtml += '<i class="fas fa-list-ul me-1"></i>';
							settlementHtml += ranges.join(', ');
							settlementHtml += '</div>';
						}
					}
				} else {
					settlementHtml += '<span class="text-muted">고정 수업비</span>';
				}
				settlementHtml += '</div>';
				
				// 수업정산 설정 내역 표시 영역 업데이트
				$('#settlement-display').html(settlementHtml);
			} else {
				// 데이터가 없으면 기본 설정 표시
				var defaultHtml = '<div class="text-center text-muted py-2">';
				defaultHtml += '<i class="fas fa-info-circle me-2"></i>';
				defaultHtml += '수업정산 설정이 없습니다. 설정하기 버튼을 클릭하세요.';
				defaultHtml += '</div>';
				$('#settlement-display').html(defaultHtml);
			}
		}
	}).fail((error) => {
		// 오류 시 기본 텍스트 표시
		$('#settlement-display').html('미설정');
	});
}

// 그룹수업 저장
function saveGroupClass() {
	var itemSno = $('#modal-group-class-edit').data('item-sno');
	var className = $('#edit_class_name').val();
	var instructor = $('#edit_instructor').val();
	var duration = $('#edit_duration').val();
	var participants = $('#edit_participants').val();
	var capacity = $('#edit_capacity').val();
	var maxCapacity = $('#edit_max_capacity').val();
	var reservation = $('#edit_reservation').is(':checked');
	var reservationNum = $('#edit_reservation_num').val();
	var classStatus = $('input[name="class_status"]:checked').val();
	
	// 자리예약 가능 인원이 0이면 자리 예약 가능을 N으로 설정
	if (reservationNum === '' || parseInt(reservationNum) === 0) {
		reservation = false;
		reservationNum = 0;
	}
	
	// 유효성 검사
	if (!className.trim()) {
		alert('수업 이름을 입력해주세요.');
		return;
	}
	
	if (!instructor) {
		alert('담당강사를 선택해주세요.');
		return;
	}
	
	// 숫자 필드 유효성 검사
	if (duration !== '' && (isNaN(duration) || parseInt(duration) < 0)) {
		alert('수업 시간은 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	if (participants !== '' && (isNaN(participants) || parseInt(participants) < 0)) {
		alert('이용권 차감횟수는 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	if (capacity !== '' && (isNaN(capacity) || parseInt(capacity) < 0)) {
		alert('수업 정원 인원은 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	if (maxCapacity !== '' && (isNaN(maxCapacity) || parseInt(maxCapacity) < 0)) {
		alert('대기 가능 인원은 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	var params = {
		gx_item_sno: itemSno,
		gx_item_nm: className,
		gx_tchr_id: instructor,
		gx_class_min: duration,
		gx_deduct_cnt: participants,
		gx_max_num: capacity,
		gx_max_waiting: maxCapacity,
		reserv_num: reservation ? parseInt(reservationNum) : 0,
		use_reserv_yn: reservation ? 'Y' : 'N',
		auto_show_d: classStatus === 'open' ? 1 : 0
	};
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_update_group_class',
		type: 'POST',
		data: params,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true') {
				alertToast('success', '그룹수업이 수정되었습니다.');
				$('#modal-group-class-edit').modal('hide');
				
				// 사이드바와 캘린더 새로고침 (변경된 수업명이나 강사 정보 반영)
				refreshSidebarOnly();
				refreshCalendarEvents();
			} else {
				alert('수정 중 오류가 발생했습니다.');
			}
		}
	}).done((res) => {
		console.log('통신성공');
	}).fail((error) => {
		console.log('통신실패');
		handleLoginExpiry('<script>');
	});
}

// 수업 이미지 설정 모달 열기
function openClassImageModal() {
	console.log('🔵 openClassImageModal 호출됨 (그룹수업 수정)');
	var itemSno = $('#modal-group-class-edit').data('item-sno');
	console.log('🔵 itemSno:', itemSno);
	
	// 부모 모달 비활성화
	$('#modal-group-class-edit .modal-content').addClass('modal-disabled');
	$('#modal-group-class-edit .modal-content *').prop('disabled', true);
	console.log('🔵 그룹수업 모달 비활성화 완료');
	
	// 모달에 아이템 SNO 저장
	$('#modal-class-image').data('item-sno', itemSno);
	console.log('🔵 이미지 모달 데이터 설정 완료 - is-schedule-edit: false');
	
	// 기존 이미지 목록 로드
	loadClassImageList();
	
	// 모달 열기
	console.log('🔵 이미지 모달 열기 시도 (그룹수업)');
	$('#modal-class-image').modal('show');
}

// 수업 이미지 목록 로드
function loadClassImageList() {
	console.log('🖼️ loadClassImageList 호출됨');
	var itemSno = $('#modal-class-image').data('item-sno');
	var scheduleSno = $('#modal-class-image').data('schedule-sno');
	var isScheduleEdit = $('#modal-class-image').data('is-schedule-edit');
	
	console.log('🖼️ itemSno:', itemSno);
	console.log('🖼️ scheduleSno:', scheduleSno);
	console.log('🖼️ isScheduleEdit:', isScheduleEdit);
	
	// 스케줄 수정 모드인 경우 스케줄 전용 이미지 목록 조회
	if (isScheduleEdit && scheduleSno) {
		console.log('🖼️ 스케줄 모드: 스케줄 전용 이미지 목록 조회');
		
		jQuery.ajax({
			url: '/tbcoffmain/ajax_get_schedule_class_images',
			type: 'POST',
			data: { gx_schd_mgmt_sno: scheduleSno },
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			dataType: 'text',
			success: function (result) {
				if (handleLoginExpiry(result)) return;
				
				var data = $.parseJSON(result);
				console.log('🖼️ 스케줄 이미지 목록 응답:', data);
				if (data['result'] == 'true') {
					console.log('🖼️ 스케줄 이미지 데이터:', data['images']);
					displayClassImages(data['images'] || []);
				} else {
					console.log('🖼️ 스케줄 이미지 목록 로드 실패:', data['message']);
					displayClassImages([]);
				}
			},
			error: function() {
				console.log('🖼️ 스케줄 이미지 목록 조회 통신 실패');
				displayClassImages([]);
			}
		});
	} else if (itemSno) {
		// 일반 그룹수업 수정 모드 또는 이미 itemSno가 있는 경우
		console.log('🖼️ 일반 모드 또는 itemSno 존재: 바로 이미지 목록 로드');
		loadClassImageListByItemSno(itemSno);
	} else {
		console.log('🖼️ itemSno와 scheduleSno 모두 없음');
		displayClassImages([]);
	}
}

// itemSno로 이미지 목록 로드
function loadClassImageListByItemSno(itemSno) {
	console.log('🖼️ loadClassImageListByItemSno 호출됨, itemSno:', itemSno);
	
	// AJAX로 이미지 목록 가져오기
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_class_images',
		type: 'POST',
		data: { gx_item_sno: itemSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			console.log('🖼️ 이미지 목록 응답:', data);
			if (data['result'] == 'true') {
				console.log('🖼️ 이미지 데이터:', data['images']);
				displayClassImages(data['images'] || []);
			} else {
				console.log('🖼️ 이미지 목록 로드 실패:', data['message']);
				displayClassImages([]);
			}
		}
	}).done((res) => {
		console.log('🖼️ 이미지 목록 로드 성공');
	}).fail((error) => {
		console.log('🖼️ 이미지 목록 로드 실패');
		displayClassImages([]);
	});
}

// 수업 이미지 목록 표시
function displayClassImages(images) {
	console.log('displayClassImages 호출됨, 이미지 개수:', images.length); // 디버깅 로그 추가
	var html = '';
	
	images.forEach(function(image, index) {
		console.log('이미지 처리중:', image); // 디버깅 로그 추가
		console.log('selected 값:', image.selected, '타입:', typeof image.selected); // 선택 상태 디버깅
		html += '<div class="col-md-4 col-sm-6 mb-3">';
		html += '<div class="card position-relative image-card" data-image-id="' + image.id + '" onclick="selectImage(this);" style="cursor: pointer;">';
		html += '<div class="position-relative">';
		html += '<img src="' + image.url + '" class="card-img-top" style="height: 150px; object-fit: cover;" alt="수업 이미지">';
		
		// 선택된 이미지 표시
		if (image.selected == 1 || image.selected === true) {
			html += '<div class="position-absolute top-0 end-0 p-2">';
			html += '<div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">';
			html += '<i class="fas fa-check"></i>';
			html += '</div>';
			html += '</div>';
		}
		
		html += '</div>';
		html += '<div class="card-body p-2">';
		html += '<div class="d-flex justify-content-between align-items-center">';
		html += '<small class="text-muted">' + (image.name || '이미지 ' + (index + 1)) + '</small>';
		html += '<button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteImage(' + image.id + ', event);" title="삭제">';
		html += '<i class="fas fa-trash-alt"></i>';
		html += '</button>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
	});
	
	if (images.length === 0) {
		html = '<div class="col-12 text-center text-muted py-4">';
		html += '<i class="fas fa-image fa-3x mb-3"></i><br>';
		html += '등록된 이미지가 없습니다.<br>';
		html += '<small>이미지 추가 버튼을 클릭하여 이미지를 업로드하세요.</small>';
		html += '</div>';
	}
	
	$('#class-image-list').html(html);
}

// 이미지 선택/해제
function selectImage(element) {
	var $element = $(element);
	var imageId = $element.data('image-id');
	
	// 기존 선택 해제
	$('.image-card').removeClass('selected');
	$('.image-card .fa-check').parent().parent().remove();
	
	// 새로운 선택 적용
	if (!$element.hasClass('selected')) {
		$element.addClass('selected');
		
		// 체크 표시 추가
		var checkHtml = '<div class="position-absolute top-0 end-0 p-2">';
		checkHtml += '<div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">';
		checkHtml += '<i class="fas fa-check"></i>';
		checkHtml += '</div>';
		checkHtml += '</div>';
		
		$element.find('.position-relative').append(checkHtml);
	}
}

// 이미지 업로드
function uploadClassImage(input) {
	if (!input.files || !input.files[0]) {
		return;
	}
	
	var file = input.files[0];
	var itemSno = $('#modal-class-image').data('item-sno');
	var scheduleSno = $('#modal-class-image').data('schedule-sno');
	var isScheduleEdit = $('#modal-class-image').data('is-schedule-edit');
	
	console.log('🖼️ 이미지 업로드 시도');
	console.log('🖼️ itemSno:', itemSno);
	console.log('🖼️ scheduleSno:', scheduleSno);
	console.log('🖼️ isScheduleEdit:', isScheduleEdit);
	
	// 스케줄 수정 모드인 경우 공통 이미지 저장소 사용 (itemSno = 0)
	if (isScheduleEdit && scheduleSno && !itemSno) {
		itemSno = 0; // 공통 이미지 저장소 사용
		console.log('🖼️ 스케줄 모드: 공통 이미지 저장소 사용 (itemSno = 0)');
	}
	
	if (!itemSno && itemSno !== 0) {
		alert('그룹수업 정보가 없습니다.');
		input.value = '';
		return;
	}
	
	// 파일 크기 체크 (5MB)
	if (file.size > 5 * 1024 * 1024) {
		alert('이미지 크기는 5MB 이하만 업로드 가능합니다.');
		input.value = '';
		return;
	}
	
	// 파일 형식 체크
	if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
		alert('JPG, PNG 형식의 이미지만 업로드 가능합니다.');
		input.value = '';
		return;
	}
	
	// 로딩 표시
	$('#image-loading').show();
	
	var formData = new FormData();
	formData.append('image', file);
	formData.append('gx_item_sno', itemSno);
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_upload_class_image',
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function (result) {
			$('#image-loading').hide();
			
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				// 이미지 목록 새로고침
				loadClassImageList();
				alertToast('success', '이미지가 업로드되었습니다.');
			} else {
				alert(data['message'] || '이미지 업로드 중 오류가 발생했습니다.');
			}
		}
	}).done((res) => {
		console.log('🖼️ 이미지 업로드 성공');
	}).fail((error) => {
		$('#image-loading').hide();
		console.log('🖼️ 이미지 업로드 실패');
		alert('이미지 업로드 중 오류가 발생했습니다.');
	});
	
	// 파일 input 초기화
	input.value = '';
}

// 이미지 삭제
function deleteImage(imageId, event) {
	event.stopPropagation();
	
	if (!confirm('이미지를 삭제하시겠습니까?')) {
		return;
	}
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_delete_class_image',
		type: 'POST',
		data: { image_id: imageId },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				// 이미지 목록 새로고침
				loadClassImageList();
				alertToast('success', '이미지가 삭제되었습니다.');
			} else {
				alert(data['message'] || '이미지 삭제 중 오류가 발생했습니다.');
			}
		}
	}).done((res) => {
		console.log('이미지 삭제 성공');
	}).fail((error) => {
		console.log('이미지 삭제 실패');
		alert('이미지 삭제 중 오류가 발생했습니다.');
	});
}

// 수업 이미지 저장
function saveClassImage() {
	console.log('🟢 saveClassImage 호출됨');
	var selectedImage = $('.image-card.selected');
	var selectedImageId = selectedImage.length > 0 ? selectedImage.data('image-id') : null;
	var itemSno = $('#modal-class-image').data('item-sno');
	var scheduleSno = $('#modal-class-image').data('schedule-sno');
	var isScheduleEdit = $('#modal-class-image').data('is-schedule-edit');
	
	console.log('🟢 선택된 이미지 ID:', selectedImageId);
	console.log('🟢 아이템 SNO:', itemSno);
	console.log('🟢 스케줄 SNO:', scheduleSno);
	console.log('🟢 is-schedule-edit:', isScheduleEdit);
	
	// 스케줄 수정인지 그룹수업 수정인지 구분
	var ajaxUrl, ajaxData;
	if (isScheduleEdit && scheduleSno) {
		// 스케줄 수정인 경우
		console.log('🟢 스케줄 수정 모드로 저장');
		ajaxUrl = '/tbcoffmain/ajax_save_schedule_image_selection';
		ajaxData = {
			gx_schd_mgmt_sno: scheduleSno,
			selected_image_id: selectedImageId
		};
	} else {
		// 그룹수업 수정인 경우
		console.log('🟢 그룹수업 수정 모드로 저장');
		ajaxUrl = '/tbcoffmain/ajax_save_class_image_selection';
		ajaxData = {
			gx_item_sno: itemSno,
			selected_image_id: selectedImageId
		};
	}
	
	jQuery.ajax({
		url: ajaxUrl,
		type: 'POST',
		data: ajaxData,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				alert('수업 이미지가 설정되었습니다.');
				
				// 선택된 이미지 정보를 다시 조회해서 표시
				if (isScheduleEdit && scheduleSno) {
					// 스케줄 수정인 경우 스케줄용 이미지 표시 함수 호출
					loadScheduleSelectedImage(selectedImageId);
				} else {
					// 그룹수업 수정인 경우 기존 함수 호출
					refreshSelectedImageDisplay(itemSno);
				}
				
				// 모달 닫기
				$('#modal-class-image').modal('hide');
				
				// 부모 모달 다시 활성화 (어떤 모달인지에 따라 분기)
				console.log('🟢 저장 성공 - 부모 모달 활성화 진행');
				if (isScheduleEdit) {
					console.log('🟢 스케줄 수정 모달 활성화');
					enableScheduleParentModal();
				} else {
					console.log('🟢 그룹수업 수정 모달 활성화');
					enableParentModal();
				}
			} else {
				alert('저장 중 오류가 발생했습니다.');
			}
		}
	}).done((res) => {
		console.log('수업 이미지 설정 저장 성공');
	}).fail((error) => {
		console.log('수업 이미지 설정 저장 실패');
		handleLoginExpiry('<script>');
	});
}

// 선택된 이미지 표시 새로고침
function refreshSelectedImageDisplay(itemSno) {
	// 그룹수업 데이터를 다시 조회해서 선택된 이미지 정보를 가져옴
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_group_class_data',
		type: 'POST',
		data: { gx_item_sno: itemSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				// 선택된 이미지 표시 업데이트
				displaySelectedImage(data['data'].SELECTED_IMAGE);
			}
		}
	});
}

// 그룹수업 수정 모달의 이미지 미리보기 업데이트
function updateClassImagePreview(imageId, imageUrl) {
	var $imageContainer = $('.col-4 .border');
	
	if (imageId && imageUrl) {
		// 선택된 이미지로 업데이트
		$imageContainer.html('<div style="width: 100%; max-height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 4px; overflow: hidden;"><img src="' + imageUrl + '" style="max-width: 100%; max-height: 80px; object-fit: contain; border-radius: 4px;" alt="선택된 수업 이미지"></div>');
		$imageContainer.addClass('border-primary').removeClass('border');
	} else {
		// 기본 상태로 복원
		$imageContainer.html('<div style="width: 100%; height: 56px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;"><span style="color: #6c757d; font-size: 12px;">이미지 선택</span></div>');
		$imageContainer.removeClass('border-primary').addClass('border');
	}
}

// 모달 닫힐 때 부모 모달 활성화
$('#modal-class-image').on('hidden.bs.modal', function() {
	console.log('🟣 이미지 모달 닫힘 이벤트 발생');
	// 스케줄 수정에서 호출된 경우인지 확인
	var isScheduleEdit = $('#modal-class-image').data('is-schedule-edit');
	console.log('🟣 is-schedule-edit 값:', isScheduleEdit);
	if (isScheduleEdit) {
		console.log('🟣 스케줄 수정 모달 활성화 함수 호출');
		enableScheduleParentModal();
	} else {
		console.log('🟣 그룹수업 수정 모달 활성화 함수 호출');
		enableParentModal();
	}
	
	// 데이터 초기화
	$('#modal-class-image').removeData('is-schedule-edit');
	$('#modal-class-image').removeData('schedule-sno');
	
	// body에서 스케줄 이미지 모달 클래스 제거 (안전장치)
	$('body').removeClass('schedule-image-modal-open');
	console.log('🟣 이미지 모달 데이터 초기화 완료');
});

// ============= 이벤트 서브메뉴 관련 함수들 =============

// 전역 변수 - 현재 선택된 이벤트 정보
var currentSelectedEvent = null;
var currentReservationScheduleId = null; // 예약내역 모달용 schedule ID

// 예약내역 모달이 닫힐 때 변수 초기화
$(document).ready(function() {
	$('#modal-reservation-history').on('hidden.bs.modal', function () {
		currentReservationScheduleId = null;
		console.log('🔄 모달 닫힘: currentReservationScheduleId 초기화');
	});
});

// 서브메뉴 표시 함수
function showEventSubmenu(x, y, eventObject) {
	currentSelectedEvent = eventObject;
	console.log('선택된 이벤트 정보:', eventObject);
	console.log('이벤트 ID:', eventObject.id);
	console.log('이벤트 extendedProps:', eventObject.extendedProps);
	
	var submenu = $('#event-submenu');
	
	// 서브메뉴를 먼저 표시해야 크기를 측정할 수 있음
	submenu.css({
		'left': x + 'px',
		'top': y + 'px',
		'display': 'block'
	});
	
	// 화면 벗어남 방지를 위한 위치 조정
	var submenuWidth = submenu.outerWidth();
	var submenuHeight = submenu.outerHeight();
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	var scrollTop = $(window).scrollTop();
	var scrollLeft = $(window).scrollLeft();
	
	// 우측으로 벗어나는 경우 좌측으로 이동
	if (x + submenuWidth > windowWidth + scrollLeft) {
		x = x - submenuWidth;
	}
	
	// footer 높이 고려 - 하단으로 벗어나거나 footer와 겹치는 경우 상단으로 이동
	var footerHeight = $('.main-footer').outerHeight() || 60;
	if (y + submenuHeight > windowHeight + scrollTop - footerHeight) {
		y = y - submenuHeight - 10; // 여유 공간 10px 추가
	}
	
	// 최종 위치 설정
	submenu.css({
		'left': x + 'px',
		'top': y + 'px'
	});
}

// 서브메뉴 숨기기 함수
function hideEventSubmenu() {
	$('#event-submenu').hide();
	currentSelectedEvent = null;
}

// 좌측 수업 아이템 서브메뉴 표시
var currentSelectedExternalItem = null;

function showExternalItemSubmenu(event, $element) {
	console.log('🎯 showExternalItemSubmenu 함수 시작');
	console.log('🎯 이벤트:', event);
	console.log('🎯 엘리먼트:', $element);
	console.log('🎯 엘리먼트 데이터:', {
		itemName: $element.data('item-name'),
		tchrName: $element.data('tchr-name'),
		itemSno: $element.data('item-sno')
	});
	
	// 현재 선택된 아이템 저장
	currentSelectedExternalItem = $element;
	
	var submenu = $('#external-item-submenu');
	console.log('🎯 서브메뉴 엘리먼트:', submenu.length, submenu);
	
	if (submenu.length === 0) {
		console.error('❌ 서브메뉴 엘리먼트를 찾을 수 없습니다!');
		return;
	}
	
	// 서브메뉴 위치 계산
	var x = event.pageX - 150; // 좌로 150px 이동
	var y = event.pageY - 100; // 위로 100px 이동
	
	console.log('🎯 원본 위치:', { originalX: event.pageX, originalY: event.pageY });
	console.log('🎯 오프셋 적용 후:', { x: x, y: y });
	
	// 화면 경계 체크 및 조정
	var submenuWidth = 160;
	var submenuHeight = 50;
	
	// 왼쪽 경계 체크
	if (x < 10) {
		x = 10;
	}
	// 오른쪽 경계 체크
	if (x + submenuWidth > $(window).width()) {
		x = $(window).width() - submenuWidth - 10;
	}
	// 위쪽 경계 체크
	if (y < 10) {
		y = 10;
	}
	// 아래쪽 경계 체크
	if (y + submenuHeight > $(window).height()) {
		y = $(window).height() - submenuHeight - 10;
	}
	
	console.log('🎯 경계 조정 후 최종 위치:', { x: x, y: y });
	
	// 서브메뉴 표시
	submenu.css({
		left: x + 'px',
		top: y + 'px',
		display: 'block'
	});
	
	console.log('🎯 서브메뉴 스타일 적용 후:', {
		left: submenu.css('left'),
		top: submenu.css('top'),
		display: submenu.css('display'),
		zIndex: submenu.css('z-index')
	});
	
	// 강제로 show() 호출
	submenu.show();
	
	console.log('✅ 좌측 수업 아이템 서브메뉴 표시 완료');
}

// 좌측 수업 아이템 서브메뉴 숨기기
function hideExternalItemSubmenu() {
	$('#external-item-submenu').hide();
	currentSelectedExternalItem = null;
}

// 등록수업 수정
function editExternalItem() {
	if (!currentSelectedExternalItem) {
		alert('선택된 수업이 없습니다.');
		hideExternalItemSubmenu();
		return;
	}
	
	var $item = currentSelectedExternalItem;
	hideExternalItemSubmenu();
	
	// 그룹수업 모달 열기
	openGroupClassModal($item[0]);
}

// 예약내역 보기
function showReservationHistory() {
	console.log('🎯 showReservationHistory 함수 시작');
	console.log('🎯 currentSelectedEvent:', currentSelectedEvent);
	
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		hideEventSubmenu();
		return;
	}
	
	var selectedEvent = currentSelectedEvent;
	hideEventSubmenu();
	
	console.log('예약내역 보기 - 선택된 이벤트:', selectedEvent.id, selectedEvent.title);
	console.log('이벤트 전체 정보:', selectedEvent);
	
	// 수업 정보 표시 (임시로 클라이언트 데이터 사용, 서버 데이터가 오면 업데이트됨)
	var initialTitle = selectedEvent.title || '수업명 조회 중...';
	// 수업명에서 강사명 제거
	initialTitle = initialTitle.replace(/\s*\([^)]*\)$/, '');
	$('#reservation_class_title').text(initialTitle);
	
	// 담당강사 정보
	var instructorName = '-';
	if (selectedEvent.extendedProps && selectedEvent.extendedProps.GX_STCHR_NM) {
		instructorName = selectedEvent.extendedProps.GX_STCHR_NM;
	} else if (selectedEvent.extendedProps && selectedEvent.extendedProps.GX_STCHR_ID) {
		instructorName = selectedEvent.extendedProps.GX_STCHR_ID;
	}
	$('#reservation_instructor').text(instructorName);
	
	// 날짜와 시간 정보 포맷팅 (년도 제거, 24시간제, 분까지만)
	if (selectedEvent.start) {
		var startDate = new Date(selectedEvent.start);
		var dateStr = startDate.toLocaleDateString('ko-KR', {
			month: 'long',
			day: 'numeric',
			weekday: 'short'
		});
		
		// 24시간제로 시:분만 표시
		var timeStr = startDate.getHours().toString().padStart(2, '0') + ':' + 
		              startDate.getMinutes().toString().padStart(2, '0');
		
		if (selectedEvent.end) {
			var endDate = new Date(selectedEvent.end);
			var endTimeStr = endDate.getHours().toString().padStart(2, '0') + ':' + 
			                 endDate.getMinutes().toString().padStart(2, '0');
			timeStr += ' ~ ' + endTimeStr;
		}
		
		$('#reservation_class_datetime').text(dateStr + ' ' + timeStr);
	} else {
		$('#reservation_class_datetime').text('수업일시 조회 중...');
	}
	
	// 정원 정보 표시 (중복 "명" 제거)
	var capacity = '28'; // 기본값 28
	if (selectedEvent.extendedProps && selectedEvent.extendedProps.GX_MAX_NUM) {
		capacity = selectedEvent.extendedProps.GX_MAX_NUM;
	}
	$('#reservation_capacity').text(capacity);
	
	// 테이블 초기화
	$('#reservation_history_tbody').html(`
		<tr>
			<td colspan="8" class="text-center text-muted" style="padding: 40px;">
				예약내역을 조회하고 있습니다...
			</td>
		</tr>
	`);
	
	// 통계 초기화
	$('#stat_current_reservations').text('0');
	$('#stat_attended_reservations').text('0');
	$('#stat_absent_reservations').text('0');
	$('#stat_waiting_reservations').text('0');
	$('#stat_cancelled_reservations').text('0');
			// 초기값 설정
		$('#total_capacity_main').text('(정원:28)');
		$('#total_reservations').text('0');
		$('#total_remaining').text('28');
	
	// 필터 버튼 초기화
	$('.filter-btn').removeClass('active');
	$('.filter-btn[data-filter="all"]').addClass('active');
	
	// 검색 영역 초기화
	$('#search_member_name').val('');
	$('#member_search_results').hide();
	$('#ticket_selection_area').hide();
	$('#member_ticket_list').html('<option value="">이용권을 선택하세요</option>');
	
	// schedule ID를 전역 변수와 모달 데이터에 저장
	currentReservationScheduleId = selectedEvent.id;
	$('#modal-reservation-history').data('schedule-id', selectedEvent.id);
	
	console.log('💾 schedule ID 저장:', selectedEvent.id);
	
	// 모달 표시
	$('#modal-reservation-history').modal('show');
	
	// AJAX로 예약내역 조회
	loadReservationHistory(selectedEvent.id);
}

// 서버에서 받은 수업 정보로 업데이트
function updateClassInfoFromServer(classInfo) {
	console.log('서버에서 받은 수업 정보:', classInfo);
	
	// 수업명 업데이트 (강사명 제거)
	if (classInfo.GX_CLAS_TITLE) {
		var className = classInfo.GX_CLAS_TITLE;
		// 괄호와 그 안의 내용(강사명) 제거
		className = className.replace(/\s*\([^)]*\)$/, '');
		$('#reservation_class_title').text(className);
	}
	
	// 담당강사 업데이트
	if (classInfo.GX_STCHR_NM) {
		$('#reservation_instructor').text(classInfo.GX_STCHR_NM);
	}
	
	// 수업일시 업데이트 (년도 제거, 24시간제, 분까지만)
	if (classInfo.GX_CLAS_S_DATE && classInfo.GX_CLAS_S_HH_II) {
		try {
			var startDate = new Date(classInfo.GX_CLAS_S_DATE + ' ' + classInfo.GX_CLAS_S_HH_II);
			
			// 년도 없이 월/일/요일 표시
			var dateStr = startDate.toLocaleDateString('ko-KR', {
				month: 'long',
				day: 'numeric',
				weekday: 'short'
			});
			
			// 24시간제로 시:분만 표시
			var timeStr = classInfo.GX_CLAS_S_HH_II.substring(0, 5); // HH:MM 형태로
			
			if (classInfo.GX_CLAS_E_HH_II) {
				var endTimeStr = classInfo.GX_CLAS_E_HH_II.substring(0, 5);
				timeStr += ' ~ ' + endTimeStr;
			}
			
			$('#reservation_class_datetime').text(dateStr + ' ' + timeStr);
		} catch (e) {
			// 날짜 파싱 실패 시 원본 데이터 표시
			var displayText = classInfo.GX_CLAS_S_DATE;
			if (classInfo.GX_CLAS_S_HH_II) {
				displayText += ' ' + classInfo.GX_CLAS_S_HH_II.substring(0, 5);
			}
			if (classInfo.GX_CLAS_E_HH_II) {
				displayText += ' ~ ' + classInfo.GX_CLAS_E_HH_II.substring(0, 5);
			}
			$('#reservation_class_datetime').text(displayText);
		}
	}
	
	// 정원 정보 업데이트 (중복 "명" 제거)
	if (classInfo.GX_MAX_NUM) {
		$('#reservation_capacity').text(classInfo.GX_MAX_NUM);
	} else {
		// 서버 데이터가 없으면 기본값 28로 설정
		$('#reservation_capacity').text('28');
	}
}

// 예약내역 조회 함수
function loadReservationHistory(scheduleId) {
	console.log('예약내역 조회 시작 - 스케줄 ID:', scheduleId);
	console.log('스케줄 ID 타입:', typeof scheduleId);
	console.log('스케줄 ID 값이 비어있는가?', !scheduleId);
	
	var requestData = {
		gx_schd_mgmt_sno: scheduleId
	};
	
	console.log('AJAX 요청 데이터:', requestData);
	
	$.ajax({
		url: '/tbcoffmain/ajax_get_reservation_history',
		type: 'POST',
		data: requestData,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			try {
				var data = $.parseJSON(result);
				if (data.result === 'true') {
					// 서버에서 받은 수업 정보로 업데이트
					if (data.class_info && data.class_info.length > 0) {
						updateClassInfoFromServer(data.class_info[0]);
					}
					
					displayReservationHistory(data.data || []);
					updateReservationStatistics(data.statistics || {});
				} else {
					console.error('예약내역 조회 실패:', data.message);
					$('#reservation_history_tbody').html(`
						<tr>
							<td colspan="8" class="text-center text-muted" style="padding: 40px;">
								예약내역을 불러오는데 실패했습니다.<br>
								<small>${data.message || '알 수 없는 오류가 발생했습니다.'}</small>
							</td>
						</tr>
					`);
				}
			} catch (e) {
				console.error('JSON 파싱 오류:', e);
				$('#reservation_history_tbody').html(`
					<tr>
						<td colspan="8" class="text-center text-muted" style="padding: 40px;">
							데이터 처리 중 오류가 발생했습니다.
						</td>
					</tr>
				`);
			}
		},
		error: function(xhr, status, error) {
			console.error('AJAX 요청 실패:', error);
			$('#reservation_history_tbody').html(`
				<tr>
					<td colspan="8" class="text-center text-muted" style="padding: 40px;">
						서버와의 통신에 실패했습니다.
					</td>
				</tr>
			`);
		}
	});
}

// 예약내역 테이블 표시
function displayReservationHistory(reservations) {
	// 백엔드에서 이미 더미 데이터와 실제 데이터를 합쳐서 보내므로 그대로 사용
	console.log('예약내역 표시: ' + (reservations ? reservations.length : 0) + '건');
	
	// 통계 계산
	var stats = calculateCombinedStatistics(reservations);
	updateReservationStatistics(stats);
	
	// 전역 변수에 데이터 저장
	allReservationsData = reservations || [];
	
	// 테이블 업데이트
	updateReservationTable(allReservationsData);
}

// 더미 + 실제 데이터의 통계 계산
function calculateCombinedStatistics(reservations) {
	var stats = {
		total: 0,
		attended: 0,
		absent: 0,
		waiting: 0,
		cancelled: 0,
		confirmed: 0,
		current_count: 0,
		waiting_count: 0
	};
	
	if (!reservations || reservations.length === 0) {
		return stats;
	}
	
	reservations.forEach(function(reservation) {
		var status = reservation.RESERVATION_STATUS || '';
		stats.total++;
		
		switch (status) {
			case '예약':
			case '확정':
				stats.confirmed++;
				stats.current_count++;
				break;
			case '출석':
				stats.attended++;
				break;
			case '결석':
				stats.absent++;
				break;
			case '대기':
				stats.waiting++;
				stats.waiting_count++;
				break;
			case '취소':
				stats.cancelled++;
				break;
		}
	});
	
	return stats;
}

// 테이블 업데이트 함수 (필터링 가능)
function updateReservationTable(reservations) {
	var tbody = $('#reservation_history_tbody');
	var html = '';
	
	if (reservations && reservations.length > 0) {
		reservations.forEach(function(reservation, index) {
			var statusBadge = getStatusBadge(reservation.RESERVATION_STATUS);
			var reservationDate = formatDateTime(reservation.RESERVATION_DATE);
			var slotNo = reservation.RESERV_SLOT_NO || '-';
			
			// 이용권명에 잔여횟수 포함
			var ticketInfo = reservation.TICKET_NAME || '-';
			if (reservation.REMAINING_COUNT && reservation.REMAINING_COUNT !== '-') {
				var remainingText = reservation.REMAINING_COUNT == 999 ? '무제한' : reservation.REMAINING_COUNT + '회 남음';
				ticketInfo += ` (${remainingText})`;
			}
			
			var statusClass = getStatusRowClass(reservation.RESERVATION_STATUS);
			html += `
				<tr class="${statusClass}">
					<td class="fw-bold dt-type-numeric text-center">${index + 1}</td>
					<td><strong>${reservation.MEMBER_NAME || '-'}</strong></td>
					<td>${reservation.PHONE_NUMBER || '-'}</td>
					<td>${reservationDate}</td>
					<td class="text-center">${statusBadge}</td>
					<td>${ticketInfo}</td>
					<td class="dt-type-numeric text-center">${slotNo}</td>
					<td class="text-center">
						<div class="btn-group" role="group">
							<button type="button" class="btn btn-xs btn-outline-primary" onclick="viewMemberDetail('${reservation.MEMBER_ID}');" title="회원상세">
								회원상세
							</button>
							<button type="button" class="btn btn-xs btn-outline-warning" onclick="changeReservationStatus('${reservation.RESERVATION_ID}', '${reservation.RESERVATION_STATUS}');" title="상태변경">
								상태변경
							</button>
							<button type="button" class="btn btn-xs btn-outline-danger" onclick="cancelReservation('${reservation.RESERVATION_ID}');" title="예약취소">
								예약취소
							</button>
						</div>
					</td>
				</tr>
			`;
		});
	} else {
		html = `
			<tr>
				<td colspan="8" class="text-center text-muted" style="padding: 40px;">
					해당 조건의 예약내역이 없습니다.
				</td>
			</tr>
		`;
	}
	
	tbody.html(html);
}

// 예약 상태 뱃지 생성
function getStatusBadge(status) {
	switch(status) {
		case '예약':
		case '확정':
			return '<span class="badge bg-primary">예약</span>';
		case '출석':
			return '<span class="badge bg-success">출석</span>';
		case '결석':
			return '<span class="badge bg-danger">결석</span>';
		case '대기':
			return '<span class="badge bg-warning">대기</span>';
		case '취소':
			return '<span class="badge bg-secondary">취소</span>';
		default:
			return '<span class="badge bg-light text-dark">' + (status || '-') + '</span>';
	}
}

// 상태별 행 클래스 생성
function getStatusRowClass(status) {
	switch(status) {
		case '예약':
		case '확정':
			return 'odd gradeA';
		case '출석':
			return 'even gradeA';
		case '결석':
			return 'odd gradeX';
		case '대기':
			return 'even gradeC';
		case '취소':
			return 'odd gradeC';
		default:
			return 'even';
	}
}

// 결제 상태 뱃지 생성
function getPaymentBadge(paymentYn) {
	if (paymentYn === 'Y') {
		return '<span class="badge bg-success">완료</span>';
	} else if (paymentYn === 'N') {
		return '<span class="badge bg-danger">미결제</span>';
	} else {
		return '<span class="badge bg-secondary">-</span>';
	}
}

// 날짜시간 포맷팅
function formatDateTime(dateTimeString) {
	if (!dateTimeString) return '-';
	
	try {
		var date = new Date(dateTimeString);
		return date.toLocaleDateString('ko-KR', {
			month: 'short',
			day: 'numeric'
		}) + ' ' + date.toLocaleTimeString('ko-KR', {
			hour: '2-digit',
			minute: '2-digit'
		});
	} catch (e) {
		return dateTimeString;
	}
}

// 예약 통계 업데이트
function updateReservationStatistics(statistics) {
	if (!statistics) {
		return;
	}
	
	// 서버에서 계산된 값이 있으면 사용, 없으면 클라이언트에서 계산
	var currentCount = statistics.current_count !== undefined ? statistics.current_count : 
					   (statistics.confirmed || 0); // 현재예약은 예약 상태만
	var waitingCount = statistics.waiting_count !== undefined ? statistics.waiting_count : 
					   (statistics.waiting || 0);
	
	$('#stat_current_reservations').text(currentCount);
	$('#stat_attended_reservations').text(statistics.attended || 0);
	$('#stat_absent_reservations').text(statistics.absent || 0);
	$('#stat_waiting_reservations').text(statistics.waiting || 0);
	$('#stat_cancelled_reservations').text(statistics.cancelled || 0);
	
	// 전체 버튼 정보 업데이트 (정원, 예약, 잔여 정보)
	var capacity = 28; // 고정값 사용 (향후 서버에서 받아올 수 있음)
	var totalReservations = (statistics.confirmed || 0) + (statistics.attended || 0); // 예약 + 출석
	var remaining = capacity - totalReservations; // 잔여 = 정원 - (예약 + 출석)
	
	// 잔여가 음수가 되지 않도록 처리
	if (remaining < 0) remaining = 0;
	
	// 새로운 구조에 맞게 업데이트
	$('#total_capacity_main').text('(정원:' + capacity + ')');
	$('#total_reservations').text(totalReservations);
	$('#total_remaining').text(remaining);
}

// 전역 변수: 전체 예약 데이터 저장
var allReservationsData = [];

// 예약 필터링 함수
function filterReservations(filterType) {
	console.log('필터링 타입:', filterType);
	
	// 버튼 상태 업데이트
	$('.filter-btn').removeClass('active');
	$(`.filter-btn[data-filter="${filterType}"]`).addClass('active');
	
	var filteredData = allReservationsData;
	
	if (filterType !== 'all') {
		filteredData = allReservationsData.filter(function(reservation) {
			var status = reservation.RESERVATION_STATUS || '';
			switch (filterType) {
				case 'confirmed':
					return status === '예약' || status === '확정';
				case 'attended':
					return status === '출석';
				case 'absent':
					return status === '결석';
				case 'waiting':
					return status === '대기';
				case 'cancelled':
					return status === '취소';
				default:
					return true;
			}
		});
	}
	
	console.log('필터링된 데이터:', filteredData.length + '건');
	
	// 테이블 업데이트
	updateReservationTable(filteredData);
}

// 회원 검색 (실시간)
var searchTimeout;
var selectedMember = null;

function searchMembers(event) {
	clearTimeout(searchTimeout);
	
	var searchTerm = $('#search_member_name').val().trim();
	
	if (searchTerm.length < 2) {
		$('#member_search_results').hide();
		$('#ticket_selection_area').hide();
		selectedMember = null;
		return;
	}
	
	// 엔터키가 눌렸거나 2자 이상 입력시 검색
	if (event.key === 'Enter' || searchTerm.length >= 2) {
		searchTimeout = setTimeout(function() {
			performMemberSearch(searchTerm);
		}, event.key === 'Enter' ? 0 : 500);
	}
}

// 실제 회원 검색 수행
function performMemberSearch(searchTerm) {
	console.log('회원 검색:', searchTerm);
	console.log('🔍 currentSelectedEvent:', currentSelectedEvent);
	console.log('🔍 currentSelectedEvent type:', typeof currentSelectedEvent);
	
	// 현재 선택된 수업 정보 가져오기 (우선순위: 전역변수 → currentSelectedEvent → 모달 데이터)
	var currentScheduleId = '';
	
	if (currentReservationScheduleId) {
		currentScheduleId = currentReservationScheduleId;
		console.log('🔍 전역변수에서 schedule ID 사용:', currentScheduleId);
	} else if (currentSelectedEvent && currentSelectedEvent.id) {
		currentScheduleId = currentSelectedEvent.id;
		console.log('🔍 currentSelectedEvent에서 schedule ID 사용:', currentScheduleId);
	} else {
		// 모달의 데이터 속성에서 schedule ID 찾기
		var modalScheduleId = $('#modal-reservation-history').data('schedule-id');
		console.log('🔍 모달에서 찾은 schedule ID:', modalScheduleId);
		
		if (modalScheduleId) {
			currentScheduleId = modalScheduleId;
			console.log('🔍 모달 데이터에서 schedule ID 사용:', currentScheduleId);
		} else {
			console.log('⚠️ 모든 방법으로 schedule ID를 찾을 수 없음');
		}
	}
	
	var currentClassTitle = $('#reservation_class_title').text() || '';
	
	console.log('🔍 최종 currentScheduleId:', currentScheduleId);
	console.log('🔍 currentClassTitle:', currentClassTitle);
	
	// 수업 날짜 정보 추출
	var classDate = '';
	if (currentSelectedEvent && currentSelectedEvent.start) {
		try {
			classDate = new Date(currentSelectedEvent.start).toISOString().split('T')[0]; // YYYY-MM-DD 형식
		} catch (e) {
			console.warn('수업 날짜 파싱 실패:', e);
		}
	}
	
	console.log('🔍 수업 날짜:', classDate);
	
	// AJAX로 회원 검색 (해당 수업과 관련된 이용권을 가진 회원만 조회)
	$.ajax({
		url: '/tbcoffmain/ajax_search_members',
		type: 'POST',
		data: {
			search_term: searchTerm,
			comp_cd: '<?php echo $comp_cd ?? ""; ?>',
			bcoff_cd: '<?php echo $bcoff_cd ?? ""; ?>',
			gx_schd_mgmt_sno: currentScheduleId,
			class_title: currentClassTitle,
			class_date: classDate
		},
		success: function(response) {
			console.log('🔍 AJAX 응답 원본:', response);
			var data = JSON.parse(response);
			console.log('🔍 파싱된 데이터:', data);
			
			if (data.result === 'true') {
				var membersWithTickets = data.data || [];
				console.log('🔍 회원 데이터:', membersWithTickets);
				
				// 각 회원 데이터 구조 확인
				if (membersWithTickets.length > 0) {
					console.log('🔍 첫 번째 회원 데이터 구조:', membersWithTickets[0]);
				}
				
				// 백엔드에서 이미 TICKET_INFO가 설정되어 있으므로 추가 처리하지 않음
				// membersWithTickets.forEach(function(member) {
				//     if (member.ACTIVE_TICKETS && member.ACTIVE_TICKETS.length > 0) {
				//         var ticketInfo = member.ACTIVE_TICKETS.map(function(ticket) {
				//             var remainText = ticket.REMAIN_CNT == 999 ? '무제한' : ticket.REMAIN_CNT + '회';
				//             return ticket.SELL_EVENT_NM + ' (' + remainText + ')';
				//         });
				//         member.TICKET_INFO = ticketInfo.join(', ');
				//     } else {
				//         member.TICKET_INFO = '사용 가능한 이용권 없음';
				//     }
				// });
				
				displayMemberSearchResults(membersWithTickets);
			} else {
				console.log('🔍 검색 실패:', data.message);
				displayMemberSearchResults([]);
			}
		},
		error: function() {
			// 에러시 샘플 데이터 표시
			displaySampleMemberResults(searchTerm);
		}
	});
}

// 샘플 회원 검색 결과 (실제 API가 없을 경우)
function displaySampleMemberResults(searchTerm) {
	var sampleMembers = [
		{ MEM_ID: 'user001', MEM_NM: '김철수', MEM_HP: '010-1234-5678', TICKET_INFO: 'GX 10회권 (7회), 헬스 무제한 (무제한)' },
		{ MEM_ID: 'user002', MEM_NM: '이영희', MEM_HP: '010-2345-6789', TICKET_INFO: '요가 5회권 (3회)' },
		{ MEM_ID: 'user003', MEM_NM: '박민수', MEM_HP: '010-3456-7890', TICKET_INFO: '스피닝 무제한 (무제한)' },
		{ MEM_ID: 'user004', MEM_NM: '정수현', MEM_HP: '010-4567-8901', TICKET_INFO: '필라테스 8회권 (5회)' },
		{ MEM_ID: 'user005', MEM_NM: '최지은', MEM_HP: '010-5678-9012', TICKET_INFO: 'GX 20회권 (12회)' },
		{ MEM_ID: 'user007', MEM_NM: '강민재', MEM_HP: '010-7777-8888', TICKET_INFO: '사용 가능한 이용권 없음' }
	];
	
	var filteredMembers = sampleMembers.filter(function(member) {
		return member.MEM_NM.includes(searchTerm);
	});
	
	displayMemberSearchResults(filteredMembers);
}

// 이미 예약된 회원인지 확인
function checkIfMemberAlreadyReserved(memberId) {
	if (!allReservationsData || allReservationsData.length === 0) {
		return false;
	}
	
	return allReservationsData.some(function(reservation) {
		return reservation.MEMBER_ID === memberId && 
			   (reservation.RESERVATION_STATUS === '예약' || 
				reservation.RESERVATION_STATUS === '확정' || 
				reservation.RESERVATION_STATUS === '출석');
	});
}

// 회원 검색 결과 표시
function displayMemberSearchResults(members) {
	console.log('🎯 displayMemberSearchResults 호출됨, 회원 수:', members.length);
	console.log('🎯 회원 데이터:', members);
	
	var container = $('#member_list_container');
	var html = '';
	
	if (members.length === 0) {
		html = '<div class="text-muted small">검색된 회원이 없습니다.</div>';
	} else {
		members.forEach(function(member, index) {
			console.log(`🎯 회원 ${index + 1} 렌더링:`, member);
			console.log(`🎯 MEM_NM: ${member.MEM_NM}, MEM_HP: ${member.MEM_HP}, TICKET_INFO: ${member.TICKET_INFO}`);
			
			// 이미 예약된 회원인지 확인
			var isAlreadyReserved = checkIfMemberAlreadyReserved(member.MEM_ID);
			
			// 이용권 유효성 확인
			var isTicketValid = member.IS_TICKET_VALID !== 0; // 0이 아니면 유효, 0이면 무효
			
			// 예약 불가능한 조건: 이미 예약됨 OR 이용권이 무효함
			var cannotReserve = isAlreadyReserved || !isTicketValid;
			
			var disabledClass = cannotReserve ? 'disabled' : '';
			var disabledAttr = cannotReserve ? 'disabled' : '';
			var cursorStyle = cannotReserve ? 'cursor: not-allowed;' : 'cursor: pointer;';
			var onclickAttr = cannotReserve ? '' : `onclick="selectMemberDirectly('${member.MEM_ID}', '${member.MEM_NM}', '${member.MEM_HP}', '${member.TICKET_INFO}', '${member.SELL_EVENT_SNO || ''}');"`;
			
			// 이용권 정보 표시 (샘플 데이터 또는 실제 데이터)
			var ticketInfo = member.TICKET_INFO || '이용권 조회 중...';
			
			// undefined 값 체크 및 기본값 설정
			var memberName = member.MEM_NM || '이름 없음';
			var memberPhone = member.MEM_HP || '전화번호 없음';
			
			// 버튼 텍스트 결정
			var buttonText = '예약하기';
			var buttonClass = 'btn-outline-primary';
			if (isAlreadyReserved) {
				buttonText = '예약됨';
				buttonClass = 'btn-outline-secondary';
			} else if (!isTicketValid) {
				buttonText = '이용불가';
				buttonClass = 'btn-outline-danger';
			}
			
			console.log(`🎯 최종 렌더링 값 - 이름: ${memberName}, 전화번호: ${memberPhone}, 이용권: ${ticketInfo}`);
			
			html += `
				<div class="d-flex align-items-center justify-content-between border-bottom py-1 member-item ${cannotReserve ? 'bg-light' : ''}" 
					 style="${cursorStyle}" 
					 ${onclickAttr}>
					<div style="flex: 1;">
						<div class="small">
							<strong>${memberName}</strong>
						</div>
						<div class="text-muted" style="font-size: 11px;">${memberPhone}</div>
						<div class="${!isTicketValid ? 'text-danger' : 'text-info'}" style="font-size: 10px;">${ticketInfo}</div>
					</div>
					<button type="button" class="btn ${buttonClass} btn-xs ${disabledClass}" ${disabledAttr}>
						${buttonText}
					</button>
				</div>
			`;
		});
	}
	
	container.html(html);
	$('#member_search_results').show();
}

// 회원 직접 선택 (이용권 정보 포함)
function selectMemberDirectly(memberId, memberName, memberPhone, ticketInfo, sellEventSno) {
	console.log('회원 직접 선택:', memberId, memberName, '이용권:', ticketInfo, '이용권SNO:', sellEventSno);
	
	selectedMember = {
		id: memberId,
		name: memberName,
		phone: memberPhone,
		ticketInfo: ticketInfo,
		sellEventSno: sellEventSno
	};
	
	// 검색 결과 숨기기
	$('#member_search_results').hide();
	
	// 바로 예약 확인 진행
	confirmReservation(memberName, ticketInfo);
}

// 예약 확인
function confirmReservation(memberName, ticketInfo) {
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		return;
	}
	
	var confirmMessage = `
예약 정보를 확인해주세요:

• 회원명: ${memberName}
• 수업명: ${currentSelectedEvent.title}
• 이용권: ${ticketInfo}

예약을 진행하시겠습니까?
	`.trim();
	
	if (confirm(confirmMessage)) {
		makeReservationDirectly();
	}
}

// 회원 선택 (기존 방식 - 호환성 유지)
function selectMember(memberId, memberName, memberPhone) {
	console.log('회원 선택:', memberId, memberName);
	
	selectedMember = {
		id: memberId,
		name: memberName,
		phone: memberPhone
	};
	
	// 선택된 회원명을 검색창에 표시
	$('#search_member_name').val(memberName);
	
	// 검색 결과 숨기기
	$('#member_search_results').hide();
	
	// 해당 회원의 이용권 목록 조회
	loadMemberTickets(memberId);
}

// 회원의 이용권 목록 조회
function loadMemberTickets(memberId) {
	console.log('회원 이용권 조회:', memberId);
	
	// AJAX로 회원의 이용권 목록 조회
	$.ajax({
		url: '/tbcoffmain/ajax_get_member_tickets',
		type: 'POST',
		data: {
			member_id: memberId,
			gx_schd_mgmt_sno: currentSelectedEvent ? currentSelectedEvent.id : '',
			comp_cd: '<?php echo $comp_cd ?? ""; ?>',
			bcoff_cd: '<?php echo $bcoff_cd ?? ""; ?>'
		},
		success: function(response) {
			var data = JSON.parse(response);
			if (data.result === 'true') {
				displayMemberTickets(data.tickets || []);
			} else {
				displayMemberTickets([]);
			}
		},
		error: function() {
			// 에러시 샘플 이용권 표시
			displaySampleMemberTickets();
		}
	});
}

// 샘플 이용권 데이터 (실제 API가 없을 경우)
function displaySampleMemberTickets() {
	var sampleTickets = [
		{ SELL_EVENT_SNO: '1', SELL_EVENT_NM: 'GX 10회권', REMAIN_CNT: 7 },
		{ SELL_EVENT_SNO: '2', SELL_EVENT_NM: '헬스+GX 무제한', REMAIN_CNT: 999 },
		{ SELL_EVENT_SNO: '3', SELL_EVENT_NM: '스피닝 5회권', REMAIN_CNT: 3 }
	];
	
	displayMemberTickets(sampleTickets);
}

// 회원 이용권 목록 표시
function displayMemberTickets(tickets) {
	var select = $('#member_ticket_list');
	var html = '<option value="">이용권을 선택하세요</option>';
	
	if (tickets.length > 0) {
		tickets.forEach(function(ticket) {
			var remainText = ticket.REMAIN_CNT == 999 ? '무제한' : ticket.REMAIN_CNT + '회 남음';
			html += `<option value="${ticket.SELL_EVENT_SNO}">${ticket.SELL_EVENT_NM} (${remainText})</option>`;
		});
	} else {
		html += '<option value="" disabled>사용 가능한 이용권이 없습니다</option>';
	}
	
	select.html(html);
	$('#ticket_selection_area').show();
}

// 직접 예약하기 (이용권 정보 포함)
function makeReservationDirectly() {
	if (!selectedMember) {
		alert('회원을 선택해주세요.');
		return;
	}
	
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		return;
	}
	
	console.log('직접 예약 진행:', {
		member: selectedMember,
		schedule: currentSelectedEvent.id,
		ticketInfo: selectedMember.ticketInfo
	});
	
	// AJAX로 예약 처리
	$.ajax({
		url: '/tbcoffmain/ajax_make_reservation',
		type: 'POST',
		data: {
			member_id: selectedMember.id,
			sell_event_sno: selectedMember.sellEventSno || '1', // 실제 이용권 SNO 사용
			gx_schd_mgmt_sno: currentSelectedEvent.id,
			comp_cd: '<?php echo $comp_cd ?? ""; ?>',
			bcoff_cd: '<?php echo $bcoff_cd ?? ""; ?>'
		},
		success: function(response) {
			var data = JSON.parse(response);
			if (data.result === 'true') {
				alert('예약이 완료되었습니다.');
				
				// 예약내역 새로고침
				loadReservationHistory(currentSelectedEvent.id);
				
				// 입력 초기화
				$('#search_member_name').val('');
				$('#member_search_results').hide();
				selectedMember = null;
			} else {
				alert('예약 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
			}
		},
		error: function() {
			alert('예약 처리 중 서버 오류가 발생했습니다.');
		}
	});
}

// 예약하기 (기존 방식 - 호환성 유지)
function makeReservation() {
	if (!selectedMember) {
		alert('회원을 선택해주세요.');
		return;
	}
	
	var selectedTicket = $('#member_ticket_list').val();
	if (!selectedTicket) {
		alert('이용권을 선택해주세요.');
		return;
	}
	
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		return;
	}
	
	var confirmation = confirm(`${selectedMember.name} 회원을 ${currentSelectedEvent.title} 수업에 예약하시겠습니까?`);
	if (!confirmation) {
		return;
	}
	
	console.log('예약 진행:', {
		member: selectedMember,
		ticket: selectedTicket,
		schedule: currentSelectedEvent.id
	});
	
	// AJAX로 예약 처리
	$.ajax({
		url: '/tbcoffmain/ajax_make_reservation',
		type: 'POST',
		data: {
			member_id: selectedMember.id,
			sell_event_sno: selectedTicket,
			gx_schd_mgmt_sno: currentSelectedEvent.id,
			comp_cd: '<?php echo $comp_cd ?? ""; ?>',
			bcoff_cd: '<?php echo $bcoff_cd ?? ""; ?>'
		},
		success: function(response) {
			var data = JSON.parse(response);
			if (data.result === 'true') {
				alert('예약이 완료되었습니다.');
				
				// 예약내역 새로고침
				loadReservationHistory(currentSelectedEvent.id);
				
				// 입력 초기화
				$('#search_member_name').val('');
				$('#member_search_results').hide();
				$('#ticket_selection_area').hide();
				selectedMember = null;
			} else {
				alert('예약 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
			}
		},
		error: function() {
			alert('예약 처리 중 서버 오류가 발생했습니다.');
		}
	});
}

// 회원 상세정보 보기
function viewMemberDetail(memberId) {
	if (!memberId) {
		alert('회원 정보가 없습니다.');
		return;
	}
	
	console.log('회원 상세정보 보기:', memberId);
	alert('회원 상세정보 기능이 구현될 예정입니다.\n회원 ID: ' + memberId);
}

// 예약 상태 변경
function changeReservationStatus(reservationId, currentStatus) {
	if (!reservationId) {
		alert('예약 정보가 없습니다.');
		return;
	}
	
	var newStatus = prompt('변경할 상태를 입력하세요\n(예약, 대기, 취소, 완료)', currentStatus);
	if (!newStatus || newStatus === currentStatus) {
		return;
	}
	
	console.log('예약 상태 변경:', reservationId, currentStatus, '->', newStatus);
	alert('예약 상태 변경 기능이 구현될 예정입니다.\n예약 ID: ' + reservationId + '\n상태: ' + currentStatus + ' → ' + newStatus);
}

// 예약 취소
function cancelReservation(reservationId) {
	if (!reservationId) {
		alert('예약 정보가 없습니다.');
		return;
	}
	
	if (!confirm('정말로 이 예약을 취소하시겠습니까?')) {
		return;
	}
	
	console.log('예약 취소:', reservationId);
	alert('예약 취소 기능이 구현될 예정입니다.\n예약 ID: ' + reservationId);
}

// 예약내역 엑셀 다운로드
function exportReservationHistory() {
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		return;
	}
	
	var scheduleId = currentSelectedEvent.id;
	var classTitle = currentSelectedEvent.title;
	
	console.log('예약내역 엑셀 다운로드:', scheduleId, classTitle);
	alert('예약내역 엑셀 다운로드 기능이 구현될 예정입니다.\n수업: ' + classTitle);
}

// 강사변경 (기존 팝업 사용)
function changeInstructor() {
	console.log('강사변경 함수 호출됨');
	console.log('currentSelectedEvent:', currentSelectedEvent);
	
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		console.log('선택된 이벤트가 없음');
		hideEventSubmenu();
		return;
	}
	
	// 이벤트 정보를 백업한 후 서브메뉴 숨기기
	var selectedEvent = currentSelectedEvent;
	hideEventSubmenu();
	
	console.log('이벤트 ID:', selectedEvent.id);
	console.log('강사 ID:', selectedEvent.extendedProps ? selectedEvent.extendedProps.GX_STCHR_ID : 'extendedProps 없음');
	
	// 기존 강사변경 모달 사용
	var stchrId = selectedEvent.extendedProps ? selectedEvent.extendedProps.GX_STCHR_ID : '';
	$('#ch_gx_stchr_id').val(stchrId).trigger('change');
	$('#gx_schd_mgmt_sno').val(selectedEvent.id);
	
	console.log('모달 표시 전 - 강사 ID 설정:', stchrId);
	console.log('모달 표시 전 - 스케줄 ID 설정:', selectedEvent.id);
	
	$("#modal-gx-stchr").modal("show");
}

// 수업 수정
function editClass() {
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		hideEventSubmenu();
		return;
	}
	
	var selectedEvent = currentSelectedEvent;
	hideEventSubmenu();
	
	console.log('수업 수정 - 선택된 이벤트:', selectedEvent.id, selectedEvent.title);
	
	// 스케줄 수정 모달 열기 - eventInfo 형식에 맞게 전달
	var eventInfo = {
		event: selectedEvent
	};

	
	$('#gx_schd_mgmt_sno').val(selectedEvent.id);
	
	openScheduleEditModal(eventInfo);
}

// 수업취소
function cancelClass() {
	if (!currentSelectedEvent) {
		alert('선택된 수업이 없습니다.');
		hideEventSubmenu();
		return;
	}
	
	var selectedEvent = currentSelectedEvent;
	hideEventSubmenu();
	
	console.log('수업취소 - 선택된 이벤트:', selectedEvent.id, selectedEvent.title);
	alert('수업취소 기능이 구현될 예정입니다.\n선택된 수업: ' + selectedEvent.title);
}

// 서브메뉴 hover 효과
$(document).ready(function() {
	// 서브메뉴 아이템 hover 효과
	$(document).on('mouseenter', '.submenu-item', function() {
		$(this).css('background-color', '#f8f9fa');
	});
	
	$(document).on('mouseleave', '.submenu-item', function() {
		$(this).css('background-color', 'white');
	});
	
	// 디버깅용 - 좌측 수업 아이템에 직접 이벤트 추가
	console.log('🚀 Document ready - 좌측 수업 아이템 이벤트 추가');
	
	// 이벤트 위임으로 external-events에 더블클릭 이벤트 추가
	$(document).on('dblclick', '#external-events .external-event', function(e) {
		console.log('📍 Document 레벨 더블클릭 감지!');
		var $this = $(this);
		
		if ($(e.target).closest('.close5').length > 0) {
			return;
		}
		
		console.log('📍 서브메뉴 표시 시도 (더블클릭)');
		showExternalItemSubmenu(e, $this);
	});
	
	// 우클릭 이벤트도 document 레벨에서 추가
	$(document).on('contextmenu', '#external-events .external-event', function(e) {
		e.preventDefault();
		console.log('📍 Document 레벨 우클릭 감지!');
		var $this = $(this);
		
		if ($(e.target).closest('.close5').length > 0) {
			return false;
		}
		
		console.log('📍 서브메뉴 표시 시도 (우클릭)');
		showExternalItemSubmenu(e, $this);
		return false;
	});
	
	// 좌클릭 이벤트 추가 (드래그와 구분)
	$(document).on('mousedown', '#external-events .external-event', function(e) {
		if (e.which !== 1) return; // 좌클릭만 처리
		
		var $this = $(this);
		var startTime = Date.now();
		var startX = e.pageX;
		var startY = e.pageY;
		var isDragging = false;
		
		console.log('📍 Document 레벨 좌클릭 mousedown 감지!');
		
		// 삭제 버튼 클릭은 무시
		if ($(e.target).closest('.close5').length > 0) {
			return;
		}
		
		var mouseMoveHandler = function(moveEvent) {
			var deltaX = Math.abs(moveEvent.pageX - startX);
			var deltaY = Math.abs(moveEvent.pageY - startY);
			if (deltaX > 5 || deltaY > 5) {
				isDragging = true;
				console.log('📍 드래그 감지됨');
			}
		};
		
		var mouseUpHandler = function(upEvent) {
			$(document).off('mousemove', mouseMoveHandler);
			$(document).off('mouseup', mouseUpHandler);
			
			var clickDuration = Date.now() - startTime;
			
			// 드래그가 아니고 짧은 클릭인 경우
			if (!isDragging && clickDuration < 500) {
				console.log('📍 좌클릭 서브메뉴 표시 시도');
				// 약간의 지연을 주어 드래그 이벤트가 완료되도록 함
				setTimeout(function() {
					showExternalItemSubmenu(upEvent, $this);
				}, 50);
			}
		};
		
		$(document).on('mousemove', mouseMoveHandler);
		$(document).on('mouseup', mouseUpHandler);
	});
	
	// 문서 클릭 시 서브메뉴 숨기기
	$(document).on('click', function(e) {
		if (!$(e.target).closest('#event-submenu').length && !$(e.target).closest('.fc-event').length) {
			hideEventSubmenu();
		}
		
		// 좌측 수업 아이템 서브메뉴 숨기기
		if (!$(e.target).closest('#external-item-submenu').length && !$(e.target).closest('.external-event').length) {
			hideExternalItemSubmenu();
		}
	});
});

// 스케줄 삭제 모달 열기
function openDeleteScheduleModal() {
	// 오늘 날짜를 기본값으로 설정 (내일부터 삭제 가능)
	var today = new Date();
	var tomorrow = new Date(today);
	tomorrow.setDate(tomorrow.getDate() + 1);
	
	var tomorrowStr = tomorrow.toISOString().split('T')[0];
	
	// 시작일을 내일로 설정
	document.getElementById('delete_start_date').value = tomorrowStr;
	document.getElementById('delete_start_date').min = tomorrowStr;
	
	// 종료일 초기화
	document.getElementById('delete_end_date').value = '';
	document.getElementById('delete_end_date').min = tomorrowStr;
	
	// 메시지 초기화
	document.getElementById('delete_validation_message').style.display = 'none';
	document.getElementById('delete_summary').style.display = 'none';
	document.getElementById('btn-confirm-delete').disabled = true;
	
	// 미리보기 초기화
	document.getElementById('schedule_preview_content').innerHTML = '<p class="text-muted mb-0">날짜를 선택하면 해당 기간의 수업 일정을 확인할 수 있습니다.</p>';
	
	// 모달 열기
	$('#modal-delete-schedule').modal('show');
	
	// 모달이 완전히 열린 후 수업 일정 로드
	$('#modal-delete-schedule').on('shown.bs.modal', function () {
		console.log('스케줄 삭제 모달이 열렸습니다.');
		loadScheduleHighlightStyles();
	});
}

// 캘린더에 수업 일정 하이라이트 스타일 로드
function loadScheduleHighlightStyles() {
	var gxRoomMgmtSno = $('#gx_room_mgmt_sno').val();
	
	console.log('loadScheduleHighlightStyles 호출됨, gxRoomMgmtSno:', gxRoomMgmtSno);
	
	if (!gxRoomMgmtSno) {
		console.log('gxRoomMgmtSno가 없음');
		return;
	}
	
	// 오늘부터 향후 모든 수업 일정 조회 (2년 후까지)
	var today = new Date();
	var startDate = new Date(today);
	var endDate = new Date(today.getFullYear() + 2, 11, 31); // 2년 후 12월 31일까지
	
	console.log('조회 기간:', startDate.toISOString().split('T')[0], '~', endDate.toISOString().split('T')[0]);
	
	$.ajax({
		url: '/tbcoffmain/ajax_get_schedule_dates',
		type: 'POST',
		data: {
			gx_room_mgmt_sno: gxRoomMgmtSno,
			start_date: startDate.toISOString().split('T')[0],
			end_date: endDate.toISOString().split('T')[0]
		},
		dataType: 'json',
		success: function(result) {
			console.log('수업 날짜 조회 결과:', result);
			if (result.result === 'true' && result.schedule_dates) {
				console.log('수업이 있는 날짜들:', result.schedule_dates);
				applyCalendarHighlights(result.schedule_dates);
			} else {
				console.log('수업 날짜가 없거나 조회 실패');
			}
		},
		error: function(xhr, status, error) {
			console.log('수업 일정 날짜 조회 실패:', error);
			console.log('응답:', xhr.responseText);
		}
	});
}

// 캘린더에 수업 일정 하이라이트 적용
function applyCalendarHighlights(scheduleDates) {
	console.log('applyCalendarHighlights 호출됨, 날짜 개수:', scheduleDates.length);
	
	// 전역 변수로 저장하여 다른 함수에서도 사용 가능
	window.scheduleHighlightDates = scheduleDates;
	
	// 날짜 입력창에 수업 일정 데이터 저장 및 이벤트 리스너 추가
	var startDateInput = document.getElementById('delete_start_date');
	var endDateInput = document.getElementById('delete_end_date');
	
	console.log('날짜 입력창 요소들:', startDateInput, endDateInput);
	
	if (startDateInput) {
		startDateInput.setAttribute('data-schedule-dates', JSON.stringify(scheduleDates));
		addDateInputChangeListener(startDateInput, scheduleDates);
		console.log('시작일 입력창에 리스너 추가됨');
	}
	
	if (endDateInput) {
		endDateInput.setAttribute('data-schedule-dates', JSON.stringify(scheduleDates));
		addDateInputChangeListener(endDateInput, scheduleDates);
		console.log('종료일 입력창에 리스너 추가됨');
	}
	
	// 수업 날짜 미리보기를 모달 하단에 추가
	showScheduleDatesSummary(scheduleDates);
}

// 날짜 입력창 변경 리스너 추가
function addDateInputChangeListener(inputElement, scheduleDates) {
	console.log('addDateInputChangeListener 호출됨, 입력 요소:', inputElement.id);
	
	// 기존 리스너 제거
	if (inputElement._scheduleChangeHandler) {
		inputElement.removeEventListener('change', inputElement._scheduleChangeHandler);
		inputElement.removeEventListener('input', inputElement._scheduleChangeHandler);
	}
	
	// 새로운 리스너 생성
	inputElement._scheduleChangeHandler = function() {
		var selectedDate = this.value;
		console.log(this.id + '에서 날짜 선택됨:', selectedDate);
		
		// 유효성 검사 실행
		validateDeleteDates();
	};
	
	// 리스너 등록 (change와 input 둘 다)
	inputElement.addEventListener('change', inputElement._scheduleChangeHandler);
	inputElement.addEventListener('input', inputElement._scheduleChangeHandler);
	
	console.log('리스너 등록 완료:', inputElement.id);
}

// 수업 날짜 요약 표시
function showScheduleDatesSummary(scheduleDates) {
	if (scheduleDates.length === 0) {
		var summaryHtml = '<p class="mb-3" style="color: #6c757d;"><i class="fas fa-info-circle text-muted"></i> 예정된 수업이 없습니다.</p>';
	} else {
		// 첫 번째 날짜와 마지막 날짜
		var firstDate = new Date(scheduleDates[0]);
		var lastDate = new Date(scheduleDates[scheduleDates.length - 1]);
		
		// 날짜 포맷팅
		var firstDateStr = firstDate.toLocaleDateString('ko-KR', {
			year: 'numeric',
			month: 'long',
			day: 'numeric',
			weekday: 'short'
		});
		
		var lastDateStr = lastDate.toLocaleDateString('ko-KR', {
			year: 'numeric',
			month: 'long',
			day: 'numeric',
			weekday: 'short'
		});
		
		// 기간 계산
		var daysDiff = Math.ceil((lastDate - firstDate) / (1000 * 60 * 60 * 24)) + 1;
		
		var summaryHtml = '<div class="card border-primary mb-3" style="border-left: 4px solid #007bff !important;">';
		summaryHtml += '<div class="card-body p-3">';
		summaryHtml += '<h6 class="text-primary mb-3"><i class="fas fa-info-circle"></i> 수업 일정 요약</h6>';
		
		summaryHtml += '<div class="row text-center">';
		summaryHtml += '<div class="col-md-4 mb-2">';
		summaryHtml += '<h5 class="text-primary mb-1">' + scheduleDates.length + '일</h5>';
		summaryHtml += '<small class="text-muted">총 수업 일수</small>';
		summaryHtml += '</div>';
		
		summaryHtml += '<div class="col-md-4 mb-2">';
		summaryHtml += '<h6 class="text-success mb-1">' + firstDateStr + '</h6>';
		summaryHtml += '<small class="text-muted">첫 수업일</small>';
		summaryHtml += '</div>';
		
		summaryHtml += '<div class="col-md-4 mb-2">';
		summaryHtml += '<h6 class="text-danger mb-1">' + lastDateStr + '</h6>';
		summaryHtml += '<small class="text-muted">마지막 수업일</small>';
		summaryHtml += '</div>';
		summaryHtml += '</div>';
		
		// 기간 정보
		summaryHtml += '<hr class="my-2">';
		summaryHtml += '<div class="text-center">';
		summaryHtml += '<span class="badge badge-info" style="font-size: 0.9rem; padding: 0.5rem 1rem;">전체 기간: ' + daysDiff + '일</span>';
		summaryHtml += '</div>';
		
		summaryHtml += '</div>'; // card-body
		summaryHtml += '</div>'; // card
	}
	
	// 기존 요약 제거
	var existingSummary = document.querySelector('#modal-delete-schedule .schedule-summary');
	if (existingSummary) {
		existingSummary.remove();
	}
	
	// 새 요약 추가 - 미리보기 바로 위에 추가
	var previewDiv = document.querySelector('#modal-delete-schedule #schedule_calendar_preview');
	if (previewDiv) {
		var summaryDiv = document.createElement('div');
		summaryDiv.className = 'schedule-summary';
		summaryDiv.innerHTML = summaryHtml;
		previewDiv.parentNode.insertBefore(summaryDiv, previewDiv);
	}
}

// 삭제 날짜 유효성 검사
function validateDeleteDates() {
	var startDate = document.getElementById('delete_start_date').value;
	var endDate = document.getElementById('delete_end_date').value;
	var messageDiv = document.getElementById('delete_validation_message');
	var summaryDiv = document.getElementById('delete_summary');
	var confirmBtn = document.getElementById('btn-confirm-delete');
	
	// 메시지 초기화
	messageDiv.style.display = 'none';
	summaryDiv.style.display = 'none';
	confirmBtn.disabled = true;
	
	if (!startDate || !endDate) {
		// 미리보기 초기화
		document.getElementById('schedule_preview_content').innerHTML = '<p class="text-muted mb-0">날짜를 선택하면 해당 기간의 수업 일정을 확인할 수 있습니다.</p>';
		return;
	}
	
	var today = new Date();
	var start = new Date(startDate);
	var end = new Date(endDate);
	
	// 오늘 날짜 확인
	if (start <= today) {
		messageDiv.textContent = '삭제 시작일은 오늘 이후 날짜여야 합니다.';
		messageDiv.style.display = 'block';
		return;
	}
	
	// 종료일이 시작일보다 이른지 확인
	if (end < start) {
		messageDiv.textContent = '삭제 종료일은 시작일과 같거나 이후 날짜여야 합니다.';
		messageDiv.style.display = 'block';
		return;
	}
	
	// 유효한 경우 수업 일정 미리보기 로드
	loadSchedulePreview(startDate, endDate);
	
	// 유효한 경우 요약 정보 표시
	var startStr = start.toLocaleDateString('ko-KR');
	var endStr = end.toLocaleDateString('ko-KR');
	
	document.getElementById('delete_period_text').textContent = startStr + ' ~ ' + endStr;
	summaryDiv.style.display = 'block';
	confirmBtn.disabled = false;
}

// 선택 기간의 수업 일정 미리보기 로드
function loadSchedulePreview(startDate, endDate) {
	var gxRoomMgmtSno = $('#gx_room_mgmt_sno').val();
	var previewContent = document.getElementById('schedule_preview_content');
	
	if (!gxRoomMgmtSno) {
		previewContent.innerHTML = '<p class="text-danger mb-0">수업 정보를 불러올 수 없습니다.</p>';
		return;
	}
	
	// 로딩 표시
	previewContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> 수업 일정을 불러오는 중...</div>';
	
	// AJAX 요청으로 해당 기간의 수업 일정 조회
	$.ajax({
		url: '/tbcoffmain/ajax_get_schedule_preview',
		type: 'POST',
		data: {
			gx_room_mgmt_sno: gxRoomMgmtSno,
			start_date: startDate,
			end_date: endDate
		},
		dataType: 'json',
		success: function(result) {
			if (result.result === 'true' && result.schedules) {
				displaySchedulePreview(result.schedules);
				
				// 삭제될 수업 수 업데이트
				document.getElementById('delete_class_count').textContent = result.schedules.length;
			} else {
				previewContent.innerHTML = '<p class="text-muted mb-0">선택한 기간에 수업이 없습니다.</p>';
				document.getElementById('delete_class_count').textContent = '0';
			}
		},
		error: function() {
			previewContent.innerHTML = '<p class="text-danger mb-0">수업 일정을 불러오는 중 오류가 발생했습니다.</p>';
			document.getElementById('delete_class_count').textContent = '0';
		}
	});
}

// 수업 일정 미리보기 표시
function displaySchedulePreview(schedules) {
	var previewContent = document.getElementById('schedule_preview_content');
	var html = '';
	
	if (schedules.length === 0) {
		html = '<p class="text-muted mb-0">선택한 기간에 수업이 없습니다.</p>';
	} else {
		// 날짜별로 그룹화
		var groupedSchedules = {};
		schedules.forEach(function(schedule) {
			var date = schedule.GX_CLAS_S_DATE;
			if (!groupedSchedules[date]) {
				groupedSchedules[date] = [];
			}
			groupedSchedules[date].push(schedule);
		});
		
		// 날짜순으로 정렬
		var sortedDates = Object.keys(groupedSchedules).sort();
		
		sortedDates.forEach(function(date) {
			var dateObj = new Date(date);
			var dateStr = dateObj.toLocaleDateString('ko-KR', {
				month: 'long',
				day: 'numeric',
				weekday: 'short'
			});
			
			html += '<div class="mb-2">';
			html += '<div class="d-flex align-items-center mb-1">';
			html += '<span class="badge badge-success mr-2">●</span>';
			html += '<strong>' + dateStr + '</strong>';
			html += '</div>';
			
			groupedSchedules[date].forEach(function(schedule) {
				// GX_CLAS_S_HH_II와 GX_CLAS_E_HH_II는 "HHMM" 형식 (예: "0900", "1030")
				var startTime = formatTimeFromHHII(schedule.GX_CLAS_S_HH_II);
				var endTime = formatTimeFromHHII(schedule.GX_CLAS_E_HH_II);
				
				html += '<div class="ml-3 small text-muted">';
				html += '• ' + startTime + ' ~ ' + endTime;
				if (schedule.GX_CLAS_TITLE) {
					html += ' (' + schedule.GX_CLAS_TITLE + ')';
				}
				html += '</div>';
			});
			
			html += '</div>';
		});
	}
	
	previewContent.innerHTML = html;
}

// HHMM 형식을 HH:MM 형식으로 변환하는 함수
function formatTimeFromHHII(hhiiString) {
	if (!hhiiString || hhiiString.length < 4) {
		return '';
	}
	
	// "0900" -> "09:00", "1030" -> "10:30"
	var hours = hhiiString.substring(0, 2);
	var minutes = hhiiString.substring(2, 4);
	return hours + ':' + minutes;
}

// 스케줄 삭제 확인
function confirmDeleteSchedule() {
	var startDate = document.getElementById('delete_start_date').value;
	var endDate = document.getElementById('delete_end_date').value;
	var gxRoomMgmtSno = $('#gx_room_mgmt_sno').val();
	
	if (!startDate || !endDate || !gxRoomMgmtSno) {
		alertToast('error', '필수 정보가 누락되었습니다.');
		return;
	}
	
	// 최종 확인
	var startStr = new Date(startDate).toLocaleDateString('ko-KR');
	var endStr = new Date(endDate).toLocaleDateString('ko-KR');
	
	// SweetAlert2 확인 대화상자
	Swal.fire({
		title: '스케줄 삭제 확인',
		html: `정말로 <strong>${startStr} ~ ${endStr}</strong> 기간의<br>모든 스케줄을 삭제하시겠습니까?<br><br><span class="text-danger">⚠️ 이 작업은 되돌릴 수 없습니다.</span>`,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#dc3545',
		cancelButtonColor: '#6c757d',
		confirmButtonText: '<i class="fas fa-trash-alt"></i> 삭제',
		cancelButtonText: '취소',
		reverseButtons: true,
		focusCancel: true
	}).then((result) => {
		if (result.isConfirmed) {
			// 삭제 버튼 비활성화
			$('#btn-confirm-delete').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> 삭제 중...');
			
			// AJAX 요청
			$.ajax({
				url: '/tbcoffmain/ajax_delete_schedule_range',
				type: 'POST',
				data: {
					gx_room_mgmt_sno: gxRoomMgmtSno,
					start_date: startDate,
					end_date: endDate
				},
				dataType: 'json',
				success: function(result) {
					if (result.result === 'true') {
						// 성공 토스트 팝업
						Swal.fire({
							toast: true,
							position: 'top-end',
							icon: 'success',
							title: '스케줄이 삭제되었습니다',
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true
						});
						
						// 모달 닫기
						$('#modal-delete-schedule').modal('hide');
						
						// 캘린더 새로고침
						if (window.calendar && typeof window.calendar.refetchEvents === 'function') {
							window.calendar.refetchEvents();
						}
						
					} else {
						Swal.fire({
							toast: true,
							position: 'top-end',
							icon: 'error',
							title: result.message || '스케줄 삭제 중 오류가 발생했습니다',
							showConfirmButton: false,
							timer: 4000,
							timerProgressBar: true
						});
					}
				},
				error: function() {
					Swal.fire({
						toast: true,
						position: 'top-end',
						icon: 'error',
						title: '네트워크 오류가 발생했습니다',
						showConfirmButton: false,
						timer: 4000,
						timerProgressBar: true
					});
				},
				complete: function() {
					// 버튼 상태 복원
					$('#btn-confirm-delete').prop('disabled', false).html('<i class="fas fa-trash-alt"></i> 스케줄 삭제');
				}
			});
		}
	});
}

// ============= 스케줄 수정 관련 함수들 =============

// 캘린더 이벤트 클릭시 스케줄 수정 모달 열기
function openScheduleEditModal(eventInfo) {
	var eventId = eventInfo.event.id;
	
	// AJAX로 스케줄 데이터 불러오기
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_schedule_detail',
		type: 'POST',
		data: { gx_schd_mgmt_sno: eventId },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				var scheduleData = data['data'];
				
				// 아이템 정보 표시
				var itemInfo = '직접생성 스케줄';
				if (scheduleData.GX_ITEM_SNO && scheduleData.GX_ITEM_SNO != '0') {
					itemInfo = '아이템 기반 (SNO: ' + scheduleData.GX_ITEM_SNO + ')';
					if (scheduleData.ITEM_NAME) {
						itemInfo = '아이템 기반 (' + scheduleData.ITEM_NAME + ')';
					}
				}
				$('#schedule-item-info').text(itemInfo);
				
				// 강사 목록이 있다면 먼저 업데이트
				console.log('🔍 스케줄 수정 - data 확인:', data);
				console.log('🔍 스케줄 수정 - tchr_list 존재 여부:', !!data['tchr_list']);
				
				if (data['tchr_list']) {
					var tchr_list = data['tchr_list'];
					console.log('📋 강사 목록 개수:', tchr_list.length);
					console.log('📋 강사 목록 상세:', tchr_list);
					
					var scheduleTeacherOptions = '<option value="">강사 선택</option>';
					if (tchr_list && tchr_list.length > 0) {
						tchr_list.forEach(function(teacher) {
							console.log('  - 강사:', teacher.MEM_ID, teacher.MEM_NM);
							scheduleTeacherOptions += '<option value="' + teacher.MEM_ID + '">' + 
								teacher.MEM_NM + ' (' + teacher.TCHR_POSN_NM + ')</option>';
						});
					}
					$('#edit_schedule_instructor').html(scheduleTeacherOptions);
					console.log('✅ 스케줄 수정 모달 - 강사 목록 업데이트 완료');
				} else {
					console.warn('⚠️ 스케줄 수정 - tchr_list가 없습니다!');
				}
				
				// 모달에 데이터 설정
				$('#edit_schedule_title').val(scheduleData.GX_CLAS_TITLE || '');
				
				// 강사 선택 설정 (약간의 딜레이를 주어 DOM 업데이트 후 선택)
				setTimeout(function() {
					// TCHR_ID (GX_STCHR_ID)를 MEM_ID로 변환하기 위해 강사 목록에서 찾기
					var teacherId = scheduleData.GX_STCHR_ID || '';
					var targetMemId = '';
					
					console.log('🎯 스케줄 강사 선택 시도 (TCHR_ID → MEM_ID 변환)');
					console.log('  - 스케줄 데이터 GX_STCHR_ID (=TCHR_ID):', teacherId);
					
					// 강사 목록에서 TCHR_ID로 찾아 해당하는 MEM_ID 구하기
					if (data['tchr_list'] && teacherId) {
						data['tchr_list'].forEach(function(teacher) {
							console.log('  - 강사 확인:', {
								MEM_ID: teacher.MEM_ID,
								TCHR_ID: teacher.TCHR_ID,
								MEM_NM: teacher.MEM_NM
							});
							
							// TCHR_ID와 일치하는 강사 찾기
							if (String(teacher.TCHR_ID).trim() === String(teacherId).trim()) {
								targetMemId = teacher.MEM_ID;
								console.log('✅ TCHR_ID → MEM_ID 변환 성공:', teacherId, '→', targetMemId, '(' + teacher.MEM_NM + ')');
								return;
							}
						});
					}
					
					if (!targetMemId && teacherId) {
						console.warn('⚠️ TCHR_ID', teacherId, '에 해당하는 MEM_ID를 찾을 수 없습니다.');
						// fallback: GX_STCHR_ID 그대로 사용해보기
						targetMemId = teacherId;
					}
					
					console.log('🔽 드롭다운 선택 시도 - targetMemId:', targetMemId);
					
					// MEM_ID로 드롭다운 선택
					if (targetMemId) {
						$('#edit_schedule_instructor').val(targetMemId);
						console.log('🔧 MEM_ID로 선택 결과:', $('#edit_schedule_instructor').val());
						
						// 선택이 안 되었다면 문자열 비교로 재시도
						if (!$('#edit_schedule_instructor').val()) {
							console.log('🔍 문자열 비교로 재시도...');
							var matched = false;
							$('#edit_schedule_instructor option').each(function() {
								var optionValue = String($(this).val()).trim();
								if (optionValue === String(targetMemId).trim()) {
									$('#edit_schedule_instructor').val(optionValue);
									matched = true;
									console.log('✅ 문자열 비교로 매칭 성공:', optionValue);
									return false;
								}
							});
							
							if (!matched) {
								console.warn('⚠️ 최종 매칭 실패 - targetMemId:', targetMemId);
							}
							if (scheduleData.GX_STCHR_NM) {
								console.log('🔄 강사 이름으로 매칭 시도:', scheduleData.GX_STCHR_NM);
								$('#edit_schedule_instructor option').each(function() {
									if ($(this).text().indexOf(scheduleData.GX_STCHR_NM) !== -1) {
										$('#edit_schedule_instructor').val($(this).val());
										console.log('✅ 강사 이름으로 매칭 성공');
										return false;
									}
								});
							}
						}
					} else {
						console.log('✅ 직접 값 설정 성공');
					}
					
					console.log('🏁 최종 선택된 값:', $('#edit_schedule_instructor').val());
				}, 100); // 타이밍 안정성을 위해 100ms
				$('#edit_schedule_duration').val(scheduleData.GX_CLASS_MIN == '0' ? '' : scheduleData.GX_CLASS_MIN);
				$('#edit_schedule_deduct').val(scheduleData.GX_DEDUCT_CNT == '0' ? '' : scheduleData.GX_DEDUCT_CNT);
				$('#edit_schedule_capacity').val(scheduleData.GX_MAX_NUM == '0' ? '' : scheduleData.GX_MAX_NUM);
				$('#edit_schedule_waiting').val(scheduleData.GX_MAX_WAITING == '0' ? '' : scheduleData.GX_MAX_WAITING);
				
				// 참석 가능한 이용권 버튼 텍스트 업데이트
				var eventCount = parseInt(scheduleData.EVENT_COUNT) || 0;
				var eventCountText = '';
				if (eventCount === 0) {
					eventCountText = '참석 가능한 이용권 없음 (선택추가)';
				} else {
					eventCountText = '참석 가능한 이용권 ' + eventCount + '개 (선택추가)';
				}
				$('#btn-schedule-ticket-selection').text(eventCountText);
				
				// 자리 예약 가능 설정
				var useReservYn = scheduleData.USE_RESERV_YN || 'N';
				$('#edit_schedule_reservation').prop('checked', useReservYn === 'Y');
				$('#edit_schedule_reservation_num').prop('disabled', useReservYn === 'N');
				if(useReservYn === 'Y') {
					$('#edit_schedule_reservation_num').val(scheduleData.RESERV_NUM == '0' ? '' : scheduleData.RESERV_NUM);
				} else {
					$('#edit_schedule_reservation_num').val('');
				}
				
				// 공개/폐강 스케줄 정보 표시
				$('#schedule_open_schedule_text').text(scheduleData.OPEN_SCHEDULE || '미설정');
				$('#schedule_close_schedule_text').text(scheduleData.CLOSE_SCHEDULE || '미설정');
				
				// 수업 이미지 표시 - 그룹수업 수정과 동일한 방식 사용
				displayScheduleSelectedImage(scheduleData.SELECTED_IMAGE);
				
				// 모달에 스케줄 SNO 저장
				$('#modal-schedule-edit').data('schedule-sno', eventId);
				
				// 스케줄 이벤트와 수당 정보 저장
				if (scheduleData.SCHEDULE_EVENTS) {
					$('#modal-schedule-edit').data('schedule-events', scheduleData.SCHEDULE_EVENTS);
				}
				if (scheduleData.PAY_RANGES) {
					$('#modal-schedule-edit').data('pay-ranges', scheduleData.PAY_RANGES);
				}
				
				// 자동 공개/폐강 설정 표시 업데이트
				loadAndDisplayScheduleAutoSettings(eventId);
				
				// 수업정산 설정 표시 업데이트
				loadAndDisplayScheduleSettlementInfo(eventId);
				
				// 모달 열기
				$('#modal-schedule-edit').modal('show');
			} else {
				alert('스케줄 데이터를 불러오는데 실패했습니다.');
			}
		}
	}).done((res) => {
		console.log('스케줄 데이터 로드 성공');
	}).fail((error) => {
		console.log('스케줄 데이터 로드 실패');
		handleLoginExpiry('<script>');
	});
}

// 스케줄 수정 저장
function saveSchedule() {
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	var scheduleTitle = $('#edit_schedule_title').val();
	var instructor = $('#edit_schedule_instructor').val();
	var duration = $('#edit_schedule_duration').val();
	var deduct = $('#edit_schedule_deduct').val();
	var capacity = $('#edit_schedule_capacity').val();
	var waiting = $('#edit_schedule_waiting').val();
	var reservation = $('#edit_schedule_reservation').is(':checked');
	var reservationNum = $('#edit_schedule_reservation_num').val();
	
	// 자리예약 가능 인원이 0이면 자리 예약 가능을 N으로 설정
	if (reservationNum === '' || parseInt(reservationNum) === 0) {
		reservation = false;
		reservationNum = 0;
	}
	
	// 유효성 검사
	if (!scheduleTitle.trim()) {
		alert('수업 이름을 입력해주세요.');
		return;
	}
	
	if (!instructor) {
		alert('담당강사를 선택해주세요.');
		return;
	}
	
	// 숫자 필드 유효성 검사
	if (duration !== '' && (isNaN(duration) || parseInt(duration) < 0)) {
		alert('수업 시간은 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	if (deduct !== '' && (isNaN(deduct) || parseInt(deduct) < 0)) {
		alert('이용권 차감횟수는 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	if (capacity !== '' && (isNaN(capacity) || parseInt(capacity) < 0)) {
		alert('수업 정원 인원은 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	if (waiting !== '' && (isNaN(waiting) || parseInt(waiting) < 0)) {
		alert('대기 가능 인원은 0 이상의 숫자를 입력해주세요.');
		return;
	}
	
	var params = {
		gx_schd_mgmt_sno: scheduleSno,
		gx_clas_title: scheduleTitle,
		gx_stchr_id: instructor,
		gx_class_min: duration,
		gx_deduct_cnt: deduct,
		gx_max_num: capacity,
		gx_max_waiting: waiting,
		reserv_num: reservation ? parseInt(reservationNum) : 0,
		use_reserv_yn: reservation ? 'Y' : 'N'
	};
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_update_schedule',
		type: 'POST',
		data: params,
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			json_result = $.parseJSON(result);
			if (json_result['result'] == 'true') {
				alertToast('success', '스케줄이 수정되었습니다.');
				
				// 스케줄 수정 완료 후 이용권 버튼 텍스트 새로고침
				refreshScheduleTicketButton(scheduleSno);
				
				$('#modal-schedule-edit').modal('hide');
				
				// 캘린더 새로고침
				refreshCalendarEvents();
			} else {
				alert('수정 중 오류가 발생했습니다.');
			}
		}
	}).done((res) => {
		console.log('통신성공');
	}).fail((error) => {
		console.log('통신실패');
		handleLoginExpiry('<script>');
	});
}

// 스케줄 삭제
function deleteSchedule() {
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	
	if (!scheduleSno) {
		alert('삭제할 스케줄 정보가 없습니다.');
		return;
	}
	
	// toasted 확인 대화상자
	alertToastConfirm('warning', '정말로 이 스케줄을 삭제하시겠습니까?', function() {
		// 확인 버튼 클릭 시 삭제 처리
		jQuery.ajax({
			url: '/tbcoffmain/ajax_delete_schedule',
			type: 'POST',
			data: { gx_schd_mgmt_sno: scheduleSno },
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			dataType: 'text',
			success: function (result) {
				if (handleLoginExpiry(result)) return;
				
				json_result = $.parseJSON(result);
				if (json_result['result'] == 'true') {
					// 삭제 완료 알림 제거 (요청사항에 따라)
					$('#modal-schedule-edit').modal('hide');
					
					// 캘린더 새로고침
					refreshCalendarEvents();
				} else {
					alert('삭제 중 오류가 발생했습니다.');
				}
			}
		}).done((res) => {
			console.log('통신성공');
		}).fail((error) => {
			console.log('통신실패');
			handleLoginExpiry('<script>');
		});
	});
}

// 스케줄 수정 모달의 자리 예약 토글 처리
function handleScheduleReservationToggle() {
	var reservationCheckbox = document.getElementById('edit_schedule_reservation');
	var reservationNumInput = document.getElementById('edit_schedule_reservation_num');
	var capacityInput = document.getElementById('edit_schedule_capacity');
	
	if (reservationCheckbox.checked) {
		// 자리 예약 가능이 켜질 때 수업 정원 인원을 자동으로 입력
		var capacity = parseInt(capacityInput.value) || 0;
		if (capacity > 0) {
			reservationNumInput.value = capacity;
		}
		reservationNumInput.disabled = false;
		// 최대값 설정
		reservationNumInput.max = capacity;
	} else {
		// 자리 예약 가능이 꺼질 때
		reservationNumInput.disabled = true;
		reservationNumInput.value = '';
		// 최대값 제한 해제
		reservationNumInput.removeAttribute('max');
	}
}

// 스케줄 수정 모달의 수업 정원 변경 시 처리
function handleScheduleCapacityChange(input) {
	var reservationCheckbox = document.getElementById('edit_schedule_reservation');
	var reservationNumInput = document.getElementById('edit_schedule_reservation_num');
	var capacity = parseInt(input.value) || 0;
	
	if (reservationCheckbox.checked) {
		// 자리 예약이 활성화된 경우 자동으로 같은 값 설정
		if (capacity > 0) {
			reservationNumInput.value = capacity;
			reservationNumInput.max = capacity;
		}
	}
}

// 스케줄 수정 모달의 자리 예약 가능 인원 변경 시 처리
function handleScheduleReservationNumChange(input) {
	var capacityInput = document.getElementById('edit_schedule_capacity');
	var capacity = parseInt(capacityInput.value) || 0;
	var reservationNum = parseInt(input.value) || 0;
	
	// 자리 예약 가능 인원이 수업 정원보다 크지 않도록 제한
	if (reservationNum > capacity && capacity > 0) {
		input.value = capacity;
	}
}

// 스케줄 이용권 버튼 텍스트 새로고침
function refreshScheduleTicketButton(scheduleSno) {
	console.log('💾 refreshScheduleTicketButton 호출:', scheduleSno);
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_schedule_ticket_list',
		type: 'POST',
		data: { 
			gx_schd_mgmt_sno: scheduleSno,
			show_stopped: 'N'
		},
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				var selectedCount = data['selected_tickets'].length;
				var eventCountText = '';
				
				if (selectedCount === 0) {
					eventCountText = '참석 가능한 이용권 없음 (선택추가)';
				} else {
					eventCountText = '참석 가능한 이용권 ' + selectedCount + '개 (선택추가)';
				}
				
				$('#btn-schedule-ticket-selection').text(eventCountText);
				console.log('💾 스케줄 이용권 버튼 텍스트 업데이트:', eventCountText);
			}
		}
	}).fail((error) => {
		console.log('💾 스케줄 이용권 카운트 새로고침 실패');
	});
}

// ============= 스케줄 수정 모달의 이미지 관련 함수들 =============

// 스케줄의 선택된 이미지 로드
function loadScheduleSelectedImage(imageId) {
	if (!imageId || imageId == '0' || imageId == '') {
		displayScheduleSelectedImage(null);
		return;
	}
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_class_image_info',
		type: 'POST',
		data: { image_id: imageId },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				displayScheduleSelectedImage(data['data']);
			} else {
				displayScheduleSelectedImage(null);
			}
		},
		error: function() {
			displayScheduleSelectedImage(null);
		}
	});
}

// 스케줄 수정 모달의 선택된 이미지 표시
function displayScheduleSelectedImage(selectedImage) {
	var imageContainer = $('#modal-schedule-edit .col-4 .border');
	
	if (selectedImage && selectedImage.IMAGE_FILE) {
		// 선택된 이미지가 있는 경우
		var imageUrl = selectedImage.IMAGE_URL;
		
		imageContainer.html(`
			<div style="width: 100%; max-height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 4px; overflow: hidden;">
				<img src="${imageUrl}" style="max-width: 100%; max-height: 80px; object-fit: contain; border-radius: 4px;" alt="선택된 수업 이미지">
			</div>
		`);
		imageContainer.addClass('border-primary').removeClass('border');
	} else {
		// 선택된 이미지가 없는 경우
		imageContainer.html(`
			<div style="width: 100%; height: 56px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
				<span style="color: #6c757d; font-size: 12px;">이미지 1</span>
			</div>
		`);
		imageContainer.removeClass('border-primary').addClass('border');
	}
}

// 스케줄 수업 이미지 설정 모달 열기
function openScheduleClassImageModal() {
	console.log('🟦 openScheduleClassImageModal 호출됨');
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	console.log('🟦 scheduleSno:', scheduleSno);
	
	// body에 스케줄 이미지 모달 열림 클래스 추가
	$('body').addClass('schedule-image-modal-open');
	console.log('🟦 body에 schedule-image-modal-open 클래스 추가');
	
	// 부모 모달 비활성화
	$('#modal-schedule-edit .modal-content').addClass('modal-disabled');
	$('#modal-schedule-edit .modal-content *').prop('disabled', true);
	console.log('🟦 스케줄 모달 비활성화 완료');
	
	// 모달에 스케줄 SNO 저장
	$('#modal-class-image').data('schedule-sno', scheduleSno);
	$('#modal-class-image').data('is-schedule-edit', true);
	console.log('🟦 이미지 모달 데이터 설정 완료 - is-schedule-edit: true');
	
	// 기존 이미지 목록 로드
	loadClassImageList();
	
	// 모달 열기
	console.log('🟦 이미지 모달 열기 시도');
	$('#modal-class-image').modal('show');
	
	// 모달이 완전히 열린 후 z-index 확인
	$('#modal-class-image').on('shown.bs.modal.scheduleDebug', function() {
		console.log('🟦 이미지 모달 완전 열림');
		console.log('🟦 이미지 모달 z-index:', $('#modal-class-image').css('z-index'));
		console.log('🟦 스케줄 모달 z-index:', $('#modal-schedule-edit').css('z-index'));
		console.log('🟦 스케줄 모달 내용 z-index:', $('#modal-schedule-edit .modal-content').css('z-index'));
		console.log('🟦 backdrop z-index:', $('.modal-backdrop').css('z-index'));
		console.log('🟦 스케줄 모달 클래스:', $('#modal-schedule-edit .modal-content').attr('class'));
		console.log('🟦 스케줄 모달 비활성화 상태:', $('#modal-schedule-edit .modal-content').hasClass('modal-disabled'));
		
		// 클릭 테스트
		setTimeout(function() {
			console.log('🟦 모달 요소들 클릭 가능 상태 확인');
			console.log('🟦 이미지 모달 표시됨:', $('#modal-class-image').is(':visible'));
			console.log('🟦 이미지 모달 pointer-events:', $('#modal-class-image').css('pointer-events'));
			console.log('🟦 이미지 모달 내용 pointer-events:', $('#modal-class-image .modal-content').css('pointer-events'));
			console.log('🟦 스케줄 모달 내용 pointer-events:', $('#modal-schedule-edit .modal-content').css('pointer-events'));
		}, 500);
		
		// 이벤트는 한 번만 실행되도록 제거
		$(this).off('shown.bs.modal.scheduleDebug');
	});
}

// 스케줄 수정 모달에 기본 이미지 표시
function showDefaultScheduleImage() {
	var imageContainer = $('#modal-schedule-edit .col-4 .border');
	
	imageContainer.html(`
		<div style="width: 100%; height: 56px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
			<span style="color: #6c757d; font-size: 12px;">이미지 1</span>
		</div>
	`);
	imageContainer.removeClass('border-primary').addClass('border');
	console.log('스케줄 기본 이미지 표시 완료');
}

// ============= 스케줄 수정 모달의 추가 팝업 함수들 =============

// 스케줄 참석 가능한 이용권 선택 모달 열기
function openScheduleTicketSelectionModal() {
	console.log('🔵 openScheduleTicketSelectionModal 호출됨');
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	console.log('🔵 scheduleSno:', scheduleSno);
	
	// body에 스케줄 이미지 모달 열림 클래스 추가
	$('body').addClass('schedule-image-modal-open');
	
	// 부모 모달 비활성화
	$('#modal-schedule-edit .modal-content').addClass('modal-disabled');
	$('#modal-schedule-edit .modal-content *').prop('disabled', true);
	console.log('🔵 스케줄 모달 비활성화 완료');
	
	// 모달에 스케줄 SNO 저장
	$('#modal-ticket-selection').data('schedule-sno', scheduleSno);
	$('#modal-ticket-selection').data('is-schedule-edit', true);
	console.log('🔵 티켓 모달 데이터 설정 완료 - is-schedule-edit: true');
	
	// 검색 필드 초기화
	$('#ticket-search').val('');
	$('#show-stopped-tickets').prop('checked', false);
	
	// 스케줄용 이용권 목록 로드
	loadScheduleTicketList();
	
	// 모달 열기
	console.log('🔵 티켓 모달 열기 시도');
	$('#modal-ticket-selection').modal('show');
}

// 스케줄용 이용권 목록 로드
function loadScheduleTicketList() {
	var scheduleSno = $('#modal-ticket-selection').data('schedule-sno');
	var showStopped = $('#show-stopped-tickets').is(':checked');
	
	// AJAX로 이용권 목록과 선택된 이용권 정보 불러오기
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_schedule_ticket_list',
		type: 'POST',
		data: { 
			gx_schd_mgmt_sno: scheduleSno,
			show_stopped: showStopped ? 'Y' : 'N'
		},
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true') {
				// 전역 변수에 데이터 저장
				allTicketList = data['ticket_list'];
				selectedTicketList = data['selected_tickets'];
				
				// 이용권 목록 표시
				displayTicketList(allTicketList, selectedTicketList);
				
				// 선택된 이용권 개수 업데이트
				updateSelectedTicketCount();
			} else {
				alert('이용권 목록을 불러오는데 실패했습니다.');
			}
		}
	}).done((res) => {
		console.log('스케줄 이용권 목록 로드 성공');
	}).fail((error) => {
		console.log('스케줄 이용권 목록 로드 실패');
		handleLoginExpiry('<script>');
	});
}

// 스케줄 자동 공개/폐강 설정 모달 열기
function openScheduleAutoScheduleModal() {
	console.log('🟡 openScheduleAutoScheduleModal 호출됨');
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	console.log('🟡 scheduleSno:', scheduleSno);
	
	// body에 스케줄 이미지 모달 열림 클래스 추가
	$('body').addClass('schedule-image-modal-open');
	
	// 부모 모달 비활성화
	$('#modal-schedule-edit .modal-content').addClass('modal-disabled');
	$('#modal-schedule-edit .modal-content *').prop('disabled', true);
	console.log('🟡 스케줄 모달 비활성화 완료');
	
	// 모달에 스케줄 SNO 저장
	$('#modal-auto-schedule').data('schedule-sno', scheduleSno);
	$('#modal-auto-schedule').data('is-schedule-edit', true);
	console.log('🟡 자동 스케줄 모달 데이터 설정 완료 - is-schedule-edit: true');
	
	// 기존 설정값 로드
	loadScheduleAutoScheduleSettings();
	
	// 모달 열기
	console.log('🟡 자동 스케줄 모달 열기 시도');
	$('#modal-auto-schedule').modal('show');
}

// 스케줄용 자동 공개/폐강 설정값 로드
function loadScheduleAutoScheduleSettings() {
	var scheduleSno = $('#modal-auto-schedule').data('schedule-sno');
	
	// AJAX로 기존 설정값 가져오기
	$.ajax({
		url: '/tbcoffmain/ajax_get_schedule_auto_schedule_settings',
		type: 'POST',
		data: {
			gx_schd_mgmt_sno: scheduleSno
		},
		dataType: 'json',
		success: function(result) {
			if (result.result === 'true' && result.data) {
				var data = result.data;
				
				// 자동 공개 설정 로드
				if (data.AUTO_SHOW_YN === 'Y') {
					$('#auto_open_enable').prop('checked', true);
					$('#auto_open_settings').show();
					
					// 단위에 따른 설정
					$('#auto_open_days').val(data.AUTO_SHOW_D || 1);
					
					if (data.AUTO_SHOW_UNIT === '1') {
						$('#auto_open_unit').val('day');
						$('#auto_open_weekday').hide();
					} else {
						$('#auto_open_unit').val('week');
						$('#auto_open_weekday').val(data.AUTO_SHOW_WEEK || '1').show();
						setTimeout(function() {
							$('#reserv_d').val(data.AUTO_SHOW_WEEK_DUR || 1);
						}, 100);
					}
					
					// 시간 설정
					if (data.AUTO_SHOW_TIME) {
						var timeParts = data.AUTO_SHOW_TIME.split(':');
						$('#auto_open_hour').val(timeParts[0] || '13');
						$('#auto_open_minute').val(timeParts[1] || '00');
					}
				} else {
					$('#auto_open_enable').prop('checked', false);
					$('#auto_open_settings').hide();
					$('#auto_open_result').hide();
				}
				
				// 자동 폐강 설정 로드
				if (data.AUTO_CLOSE_YN === 'Y') {
					$('#auto_close_enable').prop('checked', true);
					$('#auto_close_settings').show();
					$('#auto_close_time').val(data.AUTO_CLOSE_MIN || '15');
					$('#auto_close_min_people').val(data.AUTO_CLOSE_MIN_NUM || 28);
				} else {
					$('#auto_close_enable').prop('checked', false);
					$('#auto_close_settings').hide();
				}
				
				updateAutoOpenPreview();
			} else {
				// 기본값 설정
				setDefaultAutoScheduleSettings();
			}
		},
		error: function() {
			console.log('스케줄 자동 공개/폐강 설정 로드 실패');
			setDefaultAutoScheduleSettings();
		}
	});
	
	// 이벤트 핸들러 등록
	registerAutoScheduleEventHandlers();
}

// 스케줄 수업비 정산방법 설정 모달 열기  
function openScheduleSettlementSetupModal() {
	console.log('🟠 openScheduleSettlementSetupModal 호출됨');
	var scheduleSno = $('#modal-schedule-edit').data('schedule-sno');
	console.log('🟠 scheduleSno:', scheduleSno);
	
	// 수업비 정산방법 설정 모달이 있는지 확인 후 열기
	if ($('#modal-settlement-setup').length > 0) {
		// body에 스케줄 이미지 모달 열림 클래스 추가
		$('body').addClass('schedule-image-modal-open');
		
		// 부모 모달 비활성화
		$('#modal-schedule-edit .modal-content').addClass('modal-disabled');
		$('#modal-schedule-edit .modal-content *').prop('disabled', true);
		console.log('🟠 스케줄 모달 비활성화 완료');
		
		// 모달에 스케줄 SNO 저장
		$('#modal-settlement-setup').data('schedule-sno', scheduleSno);
		$('#modal-settlement-setup').data('is-schedule-edit', true);
		console.log('🟠 수업비 정산 모달 데이터 설정 완료 - is-schedule-edit: true');
		
		// 스케줄용 수업비 정산 설정 로드
		loadScheduleSettlementSettings();
		
		// 모달 열기
		console.log('🟠 수업비 정산 모달 열기 시도');
		$('#modal-settlement-setup').modal('show');
	} else {
		alert('수업비 정산방법 설정 모달이 없습니다. 그룹수업 수정 모달의 정산 설정을 참조하세요.');
	}
}

// 스케줄용 수업비 정산 설정 로드 (그룹수업과 완전 동일한 코드)
function loadScheduleSettlementSettings() {
	var scheduleSno = $('#modal-settlement-setup').data('schedule-sno');
	
	jQuery.ajax({
		url: '/tbcoffmain/ajax_get_schedule_settlement_settings',
		type: 'POST',
		data: { gx_schd_mgmt_sno: scheduleSno },
		contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'text',
		success: function (result) {
			if (handleLoginExpiry(result)) return;
			
			var data = $.parseJSON(result);
			if (data['result'] == 'true' && data['data']) {
				var settlementData = data['data'];
				
				// 디버깅용 로그
				console.log('정산 설정 데이터:', settlementData);
				console.log('PAY_FOR_ZERO_YN:', settlementData.PAY_FOR_ZERO_YN);
				console.log('USE_PAY_RATE_YN:', settlementData.USE_PAY_RATE_YN);
				console.log('PAY_RANGES:', settlementData.PAY_RANGES);
				
				// 0명 참석시 정산 여부 설정
				$('#zero_attendance_payment').prop('checked', settlementData.PAY_FOR_ZERO_YN === 'Y');
				
				// 인원당 수당 사용 여부 설정
				$('#attendance_based_payment').prop('checked', settlementData.USE_PAY_RATE_YN === 'Y');
				toggleAttendanceBasedPayment(); // UI 업데이트
				
				// 구간별 수당 정보 로드
				if (settlementData.PAY_RANGES && settlementData.PAY_RANGES.length > 0) {
					// 기존 구간들 삭제 (첫 번째 구간 제외) - 그룹수업과 완전 동일
					$('.settlement-range[data-range-index]:not([data-range-index="0"])').remove();
					
					settlementData.PAY_RANGES.forEach(function(range, index) {
						console.log('🔥 구간 처리 - index:', index, 'index === 0:', (index === 0), 'range:', range);
						if (index === 0) {
							// 첫 번째 구간 업데이트 - 그룹수업과 완전 동일
							console.log('🔥 첫 번째 구간 처리 중');
							$('#range_start').val(range.CLAS_ATD_NUM_S);
							$('#range_end').val(range.CLAS_ATD_NUM_E);
							$('#range_percent').val(range.PAY_RATE);
							console.log('🔥 첫 번째 구간 처리 완료');
						} else {
							// 추가 구간 생성 - 그룹수업과 완전 동일
							console.log('🔥 두 번째 구간 생성 시작:', range.CLAS_ATD_NUM_S, range.CLAS_ATD_NUM_E, range.PAY_RATE);
							try {
								addSettlementRangeWithData(range.CLAS_ATD_NUM_S, range.CLAS_ATD_NUM_E, range.PAY_RATE);
								console.log('🔥 addSettlementRangeWithData 함수 호출 완료');
							} catch (error) {
								console.error('🔥 addSettlementRangeWithData 오류:', error);
								
								// 직접 HTML 생성으로 대체
								var rangeHtml = `
									<div class="d-flex align-items-center mb-2 settlement-range" data-range-index="${index}">
										<input type="number" class="form-control form-control-sm text-center me-1 range-start" value="${range.CLAS_ATD_NUM_S}" min="${range.CLAS_ATD_NUM_S}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
										<span class="small me-2">명 부터</span>
										<input type="number" class="form-control form-control-sm text-center me-1 range-end" value="${range.CLAS_ATD_NUM_E}" min="${parseInt(range.CLAS_ATD_NUM_S) + 1}" style="width: 60px;" oninput="validateRangeInputs(this); validateNumberInput(this)">
										<span class="small me-2">명 까지 1 회당 수업비의</span>
										<input type="number" class="form-control form-control-sm text-center me-1 range-percent" value="${range.PAY_RATE}" min="0" max="100" style="width: 60px;" oninput="validateNumberOnly(this); validateNumberInput(this)">
										<span class="small">%</span>
										<button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeSettlementRange(this)" style="padding: 2px 6px;">×</button>
									</div>
								`;
								$('.btn-outline-secondary:contains("+ 구간 추가")').closest('.mb-3').before(rangeHtml);
								console.log('🔥 직접 HTML 생성으로 구간 추가 완료');
							}
						}
					});
					
					updateRangeConstraints();
				}
				
				// 설정 내역 표시 업데이트
				setTimeout(function() {
					updateScheduleSettlementDisplay();
				}, 100);
			} else {
				console.log('수업정산 설정 로드 실패:', data['message']);
			}
		}
	}).fail((error) => {
		console.log('수업정산 설정 로드 실패:', error);
		handleLoginExpiry('<script>');
	});
}

