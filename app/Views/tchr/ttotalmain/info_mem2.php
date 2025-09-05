<style>
    /* 전체 레이아웃 개선 */
    .content {
        background-color: #f8f9fa;
        padding: 20px 15px;
    }

    .container-fluid {
        overflow: visible !important;
    }

    /* 카드/패널 스타일 개선 */
    .panel {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 20px;
        background: #fff;
        transition: all 0.3s ease;
    }

    .panel:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .panel-heading {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 15px 20px;
        border: none;
    }

    .panel-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .panel-body {
        padding: 20px;
        overflow: visible !important;
    }

    /* 테이블 스타일 개선 */
    .table {
        margin-bottom: 0;
    }

    .table-bordered {
        border: 1px solid #e9ecef;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e9ecef;
        padding: 12px 15px;
        vertical-align: middle !important;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }

    .table td {
        font-size: 14px;
        color: #6c757d;
        overflow: visible !important;
        vertical-align: middle !important;
    }
    
    /* 판매 리스트 테이블 특별 처리 */
    .panel-body .table td,
    .panel-body .table th {
        vertical-align: middle !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.08);
        transition: background-color 0.2s ease;
    }

    /* 버튼 스타일 개선 */
    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.3s ease;
        border: none;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
        color: white;
    }

    .btn-green {
        background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
        color: white;
    }

    .btn-blue {
        background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
        color: white;
    }

    .btn-purple {
        background: linear-gradient(135deg, #ab47bc 0%, #8e24aa 100%);
        color: white;
    }

    .btn-white {
        background: white;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .btn-xs {
        padding: 4px 10px;
        font-size: 12px;
    }

    /* 드롭다운 스타일 개선 */
    .btn-group {
        position: relative;
    }

    .dropdown-menu {
        z-index: 99999 !important;
        min-width: 160px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border: none;
        border-radius: 8px;
        padding: 8px 0;
        margin-top: 2px;
    }

    .dropdown-item {
        padding: 8px 16px;
        font-size: 14px;
        color: #495057;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #667eea;
        padding-left: 20px;
    }

    .dropdown-divider {
        margin: 8px 0;
    }

    /* dropup 클래스가 추가될 때 위쪽으로 표시 */
    .dropup .dropdown-menu {
        top: auto !important;
        bottom: 100% !important;
        margin-bottom: 2px;
    }

    /* 오른쪽 정렬 드롭다운 */
    .dropdown-menu-right {
        right: 0 !important;
        left: auto !important;
    }

    /* 테이블 내 드롭다운 특별 처리 */
    .table .btn-group {
        position: static;
    }

    /* 프로필 사진 스타일 */
    .preview_mem_photo {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e9ecef;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .preview_mem_photo:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* 뱃지 스타일 개선 */
    .badge {
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 500;
        border-radius: 4px;
    }

    .badge.bg-success {
        background-color: #28a745;
    }

    .badge.bg-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge.bg-danger {
        background-color: #dc3545;
    }

    /* 출석 현황 스타일 */
    .attd-div {
        margin-bottom: 8px;
    }

    .attd-text {
        font-size: 13px;
        padding: 8px;
        background-color: #f8f9fa;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }

    .attd-text:hover {
        background-color: #e9ecef;
    }

    /* 메모 스타일 개선 */
    .memo-text-xs {
        font-size: 13px;
        line-height: 1.5;
    }

    .memo-col-status {
        width: 80px !important;
        min-width: 80px;
        max-width: 80px;
        white-space: nowrap;
        font-weight: 600;
        color: #667eea;
    }

    .memo-col-date {
        width: 90px !important;
        min-width: 90px;
        max-width: 90px;
        white-space: nowrap;
        color: #6c757d;
    }
    
    /* 메모 타입 버튼 스타일 */
    .memo-item .btn-group {
        margin-bottom: 5px;
    }
    
    /* 새 메모 토글 버튼 스타일 */
    #new-memo-toggle {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
    }
    
    #new-memo-toggle i {
        font-size: 10px;
        margin-right: 3px;
    }
    
    /* 메모 내용 줄간격 조정 */
    .memo-content {
        line-height: 1.5 !important;
        transition: all 0.3s ease-in-out;
        position: relative;
        white-space: pre-wrap !important;  /* 줄바꿈 유지하면서 자동 줄바꿈 */
        word-wrap: break-word !important;
        word-break: break-word !important;
    }
    
    /* 메모 포커스 효과 */
    .memo-content:focus {
        outline: none;
        border-color: rgba(102, 126, 234, 0.3) !important;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }
    
    /* 메모 호버 효과 */
    .memo-content:hover {
        background-color: rgba(248, 249, 250, 0.5);
        border-color: rgba(102, 126, 234, 0.2) !important;
    }
    
    .memo-item .btn-group .btn {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
    }
    
    .memo-item .btn-group .btn i {
        font-size: 10px;
        margin-right: 3px;
    }
    
    /* 메모 타입 토글 버튼 스타일 */
    .memo-type-toggle {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
    }
    
    .memo-type-toggle i {
        font-size: 10px;
        margin-right: 3px;
    }
    
    /* 출석 아이템 스타일 */
    .attd-item {
        border: 1px solid #dee2e6 !important;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .attd-item:hover {
        background-color: rgba(248, 249, 250, 0.5);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .attd-item.border-start.border-success {
        border-left: 3px solid #28a745 !important;
    }
    
    .attd-item.border-start.border-warning {
        border-left: 3px solid #ffc107 !important;
    }
    
    /* 새 메모 추가 폼 스타일 */
    #new-memo-form {
        border-color: #dee2e6 !important;
        background-color: transparent;
    }
    
    #new-memo-content[placeholder]:empty:before {
        content: attr(placeholder);
        color: #a8a8a8;
        pointer-events: none;
    }
    
    /* 메모 리스트 스크롤바 공간 확보 */
    .memo-list {
        /* 스크롤바가 생겨도 레이아웃이 변하지 않도록 */
        overflow-y: scroll !important;
        scrollbar-gutter: stable;
    }
    
    /* 스크롤바 스타일링 (선택사항) */
    .memo-list::-webkit-scrollbar,
    .attendance-list::-webkit-scrollbar {
        width: 8px;
    }
    
    .memo-list::-webkit-scrollbar-track,
    .attendance-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .memo-list::-webkit-scrollbar-thumb,
    .attendance-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .memo-list::-webkit-scrollbar-thumb:hover,
    .attendance-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* 출석 리스트 스크롤 설정 */
    .attendance-list {
        overflow-y: scroll !important;
        scrollbar-gutter: stable;
    }
    
    

    /* 페이지네이션 스타일 */
    .pagination {
        margin-bottom: 0;
    }

    .ac-btn {
        margin-right: 5px;
    }

    /* 반응형 디자인 */
    @media (max-width: 768px) {
        .col-1p {
            padding: 0 10px;
        }
        
        .table-responsive {
            border-radius: 8px;
        }
        
        .btn {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .panel-heading {
            padding: 12px 15px;
        }
        
        .panel-title {
            font-size: 15px;
        }
    }

    /* 애니메이션 효과 */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .panel {
        animation: fadeIn 0.5s ease-out;
    }

    /* 기타 스타일 */
    .select2-container--open {
        z-index: 9999 !important;
    }

    .table-responsive {
        overflow: visible !important;
        border-radius: 8px;
    }

    .table-wrap th,
    .table-wrap td {
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: unset !important;
    }

    /* 새로운 스타일 추가 */
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .stat-box {
        background: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #667eea;
    }

    .stat-label {
        font-size: 13px;
        color: #6c757d;
        margin-top: 5px;
    }

    /* 카메라 관련 스타일 */
    .photo-row {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .photo-action {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-end;
        gap: 5px;
    }

    .photo-guide-text {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.4;
        text-align: left;
        margin-bottom: 0;
    }

    .profile-photo-wrapper {
        position: relative;
        display: inline-block;
        width: 120px;
        height: 120px;
    }

    .capture-btn {
        padding: 4px 10px;
        font-size: 12px;
        border-radius: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .capture-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .capture-btn i {
        margin-right: 5px;
    }

    /* 카메라 모달 스타일 */
    #camera_wrap_modal {
        position: relative;
        width: 100%;
        max-width: 520px;  /* 적당한 크기로 조정 */
        margin: 0 auto;
        min-height: 400px;  /* 높이 조정 */
        background-color: #000;  /* 배경색 추가 */
        border-radius: 4px;
        overflow: hidden;  /* 넘치는 부분 숨김 */
    }
    
    #camera_stream_modal {
        width: 100%;
        height: 400px;  /* 고정 높이 설정 */
        display: block;
        object-fit: cover;  /* 비디오 비율 유지 */
    }
    
    /* 얼굴 실루엣 가이드 */
    .face-silhouette {
        position: absolute;
        top: 50%;  /* 중앙으로 조정 */
        left: 50%;
        transform: translate(-50%, -50%);
        width: 220px;  /* 200px에서 220px로 증가 */
        height: 270px;  /* 250px에서 270px로 증가 */
        border: 3px dashed #6c757d;  /* 회색톤으로 변경 */
        border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        pointer-events: none;
        opacity: 0.6;
    }
    
    .face-silhouette::after {  /* before를 after로 변경하여 아래쪽에 표시 */
        content: '얼굴을 이 영역에 맞춰주세요';
        position: absolute;
        bottom: -40px;  /* 아래쪽에 위치 */
        left: 50%;
        transform: translateX(-50%);
        color: #6c757d;  /* 회색톤으로 변경 */
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
        background-color: rgba(255, 255, 255, 0.95);
        padding: 6px 12px;
        border-radius: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* 촬영 버튼을 모달 밖으로 이동 */
    .camera-capture-btn {
        position: relative;  /* absolute에서 relative로 변경 */
        margin: 20px auto;
        display: block;
        padding: 12px 35px;
        font-size: 18px;
        background-color: #007bff;  /* 파란색톤으로 변경 */
        color: white;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .camera-capture-btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    }
    
    /* 모달 X 버튼 세련된 스타일 */
    #modal_camera .close {
        position: relative;
        width: 32px;
        height: 32px;
        opacity: 0.6;
        transition: all 0.3s ease;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    
    #modal_camera .close:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    #modal_camera .close::before,
    #modal_camera .close::after {
        content: '';
        position: absolute;
        height: 2px;
        width: 20px;
        background: #495057;
        border-radius: 2px;
        top: 50%;
        left: 50%;
        transition: all 0.3s ease;
    }
    
    #modal_camera .close::before {
        transform: translate(-50%, -50%) rotate(45deg);
    }
    
    #modal_camera .close::after {
        transform: translate(-50%, -50%) rotate(-45deg);
    }
    
    #modal_camera .close:hover::before,
    #modal_camera .close:hover::after {
        background: #212529;
    }

    /* 기존 인라인 카메라 스타일 (숨김) */
    #camera_wrap {
        position: relative;
        width: 100%;
        max-width: 500px;
        display: none !important;
    }

    #camera_stream {
        width: 100%;
        border-radius: 8px;
    }

    .face-guide {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 140px;
        height: 180px;
        transform: translate(-50%, -50%);
        border: 2px dashed rgba(0, 0, 0, 0.4);
        border-radius: 60% 60% 50% 50%;
        pointer-events: none;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .passport-guide {
        position: absolute;
        top: 47%;
        left: 50%;
        width: 120px;
        height: 150px;
        transform: translate(-50%, -60%);
        border: 2px dashed rgba(0, 0, 0, 0.4);
        border-radius: 8px;
        pointer-events: none;
        background-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    /* 섹션 타이틀 */
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    /* 로딩 효과 */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* 휴회 신청 스타일 개선 */
    #modal_pop_domcy .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
        padding: 1.2rem;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    
    #modal_pop_domcy .modal-header .close3 {
        color: white;
        opacity: 0.8;
        font-size: 1.5rem;
        transition: opacity 0.3s;
    }
    
    #modal_pop_domcy .modal-header .close3:hover {
        opacity: 1;
    }
    
    #modal_pop_domcy .modal-body {
        padding: 1.5rem;
        background-color: #f8f9fa;
    }
    
    #modal_pop_domcy .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }
    
    /* 휴회 시작일 섹션 */
    .domcy-date-section {
        background: white;
        padding: 1.2rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .domcy-date-section h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0.8rem;
        font-size: 0.9rem;
    }
    
    .domcy-date-inputs {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .domcy-input-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .domcy-input-group label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0;
        white-space: nowrap;
    }
    
    .domcy-start-date,
    .domcy-end-date {
        cursor: pointer !important;
        font-size: 0.85rem !important;
        padding: 0.4rem 0.75rem !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        transition: all 0.3s ease;
    }
    
    .domcy-start-date:focus,
    .domcy-end-date:focus,
    .domcy-start-date:hover,
    .domcy-end-date:hover {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1) !important;
    }
    
    .domcy-application-days {
        width: 80px !important;
        font-size: 0.85rem !important;
        padding: 0.4rem 0.5rem !important;
        text-align: center !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        transition: all 0.3s ease;
    }
    
    .domcy-application-days:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1) !important;
    }
    
    /* 회원권 목록 섹션 */
    .domcy-list-section {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .domcy-list-header {
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    /* 모달 내 datepicker z-index 조정 */
    .datepicker {
        z-index: 9999 !important;
    }
    
    /* 휴회 신청 항목 스타일 개선 */
    .dormancy-item {
        padding: 1rem;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        background-color: #fff;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .dormancy-item:hover {
        border-color: #dee2e6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }
    
    .dormancy-item.selected {
        background-color: #f0f4ff;
        border-color: #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
    }
    
    /* 체크박스 스타일 개선 */
    .dormancy-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #667eea;
    }
    
    /* 회원권 정보 */
    .dormancy-item-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .dormancy-item-title {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        flex: 1;
    }
    
    /* 날짜 입력 그룹 */
    .dormancy-date-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .dormancy-date-field {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .dormancy-date-field label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0;
        white-space: nowrap;
    }
    
    /* 날짜 입력 필드 너비 조정 */
    .domcy-start-date {
        width: 110px !important;
    }
    
    .domcy-end-date {
        width: 110px !important;
    }
    
    .use-days {
        width: 60px !important;
        text-align: center !important;
        font-size: 0.85rem !important;
        padding: 0.25rem 0.5rem !important;
    }
    
    /* 남은 정보 텍스트 */
    .info-text {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    /* 스크롤바 스타일 */
    .domcy-list-section::-webkit-scrollbar {
        width: 6px;
    }
    
    .domcy-list-section::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .domcy-list-section::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .domcy-list-section::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* 빈 상태 메시지 */
    #a-list:empty::after {
        content: "휴회 시작일과 휴회일수를 입력하면 사용 가능한 회원권이 표시됩니다.";
        display: block;
        text-align: center;
        color: #6c757d;
        font-size: 0.85rem;
        padding: 2rem;
    }
</style>
<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- mem-info-header -->
        <div class="row mem-info-header">
            <div class="col-md-12 p-0">
                <!-- <div class="card card-primary"> -->
                <div class="panel-body">



                    <div class="row sec-mem-info" style='display:none'>
                        <div class="col-md-6">

                                <div class="page-header">
                                    <h3 class="panel-title">회원정보</h3>
                                </div>
                                <div class="panel-body" >


                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">

                                    

                                        <tbody>
                                            <tr>
                                                <td colspan="2" rowspan="4" >
                                                </td>
                                                <td>아이디</td>
                                                <td><?php echo $mem_info['MEM_ID']?></td>
                                                <td>회원정보</td>
                                                <td colspan="3">
                                                    <?php ($mem_info['MEM_GENDR'] == "M") ? $disp_gendr="<font color='blue'><i class='fa fa-mars'></i></font>" : $disp_gendr="<font color='red'><i class='fa fa-venus'></i></font>"; ?>
                                                    <?php echo $disp_gendr ?>
                                                    <?php echo $mem_info['MEM_NM']?> (<?php echo disp_date_kr($mem_info['BTHDAY']) ?> , <?php echo disp_age($mem_info['BTHDAY'])?>세)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>전화번호</td>
                                                <td><?php echo disp_phone($mem_info['MEM_TELNO'])?></td>
                                                <td>매출액</td>
                                                <td style="text-align:right"><?php echo number_format($rank_info['sum_paymt_amt'])?> 원</td>
                                                <td>매출순위</td>
                                                <td class='text-center'><?php echo number_format($rank_info['paymt_ranking'])?> 위</td>
                                            </tr>
                                            <tr>
                                                <td>락커</td>
                                                <td>
                                                    <?php foreach ($lockr_01 as $r1) :?>
                                                    <?php echo $r1['LOCKR_NO']?>번&nbsp;&nbsp;
                                                    <?php endforeach;?>
                                                </td>
                                                <td>가일입</td>
                                                <td><?php echo $mem_info['JON_DATETM']?></td>
                                                <td>가입장소</td>
                                                <td><?php echo $sDef['JON_PLACE'][$mem_info['JON_PLACE']]?></td>
                                            </tr>
                                            <tr>
                                                <td>골프라카</td>
                                                <td>
                                                    <?php foreach ($lockr_02 as $r2) :?>
                                                    <?php echo $r2['LOCKR_NO']?>번&nbsp;&nbsp;
                                                    <?php endforeach;?>
                                                </td>
                                                <td>등록일</td>
                                                <td><?php echo $mem_info['REG_DATETM']?></td>
                                                <td>등록장소</td>
                                                <td><?php echo $sDef['REG_PLACE'][$mem_info['REG_PLACE']]?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 


                        <div class="col-md-3 col-1p">
                            <div class="card card-olive">
                                <div class="page-header">
                                    <h3 class="panel-title">메모내역</h3>
                                </div>

                                <div class="panel-body">
                                    <table class="table table-wrap table-sm col-md-12">

                                        <tbody>
                                            <?php
                                                $short_cut_i = 0;
                                                foreach ($memo_list as $m) :?>
                                            <tr>
                                                <?php if($m['PRIO_SET'] == "Y") :?>
                                                <td class="memo-col-status memo-text-xs text-center">[중요메모] </td>
                                                <?php else :?>
                                                <td class="memo-col-status memo-text-xs text-center">[메모등록] </td>
                                                <?php endif;?>
                                                <td class="memo-col-date memo-text-xs"><?php echo substr($m['CRE_DATETM'],0,10)?></td>
                                                <td class="memo-text-xs" title="<?php echo $m['MEMO_CONTS']?>"><span style="width:460px;display:block; white-space:nowrap; overflow: hidden; text-overflow:ellipsis;"><?php echo $m['MEMO_CONTS']?></span></td>
                                            </tr>
                                            <?php 
                                                if ($short_cut_i == 5) break;
                                                $short_cut_i++;
                                                endforeach;?>

                                            <?php
                                                $mm_count = 5-count($memo_list);
                                                for($mm=0;$mm<$mm_count;$mm++) :?>
                                            <tr>
                                                <td style='width:80px' class="memo-text-xs">&nbsp;</td>
                                                <td style='width:80px' class="memo-text-xs"></td>
                                                <td class="memo-text-xs" title=""></td>
                                            </tr>
                                            <?php endfor;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row sec-event-info" style='display:none'>
                        <div class="col-md-12 col-1p">
                            <div class="card card-default">
                                <div class="page-header">
                                    <h3 class="panel-title">상품상세내역</h3>
                                </div>
                                <div class="panel-body p-0" id="sec_event_info_detail">
                                    <!-- 환불 또는 양도,양수 일경우 상세 내역을 보여주는 곳 -->
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- 수정시작 -->

                    <div class="row sec-mem-info-detail">


                        <div class="col-xs-12 col-md-12 col-lg-4 col-1p">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><i class="fas fa-user-circle mr-2"></i>회원정보</h4>
                                </div>

                                <div class="panel-body">
                                    <!-- 프로필 사진 영역 -->
                                    <div class="text-center mb-4">
                                        <div class="profile-photo-wrapper d-inline-block">
                                            <img class="preview_mem_photo" src="<?php echo empty($mem_info['MEM_THUMB_IMG']) ? '/dist/img/default_profile.png' : $mem_info['MEM_THUMB_IMG']; ?>" 
                                                alt="회원사진"
                                                onclick="showFullPhoto('<?php echo addslashes($mem_info['MEM_MAIN_IMG'] ?? '') ?>')"
                                                onerror="this.src='/dist/img/default_profile.png'" >
                                        </div>
                                        <div class="mt-3">
                                            <h5 class="mb-1">
                                                <?php ($mem_info['MEM_GENDR'] == "M") ? $disp_gendr="<i class='fa fa-mars' style='color:#3498db'></i>" : $disp_gendr="<i class='fa fa-venus' style='color:#e74c3c'></i>"; ?>
                                                <?php echo $disp_gendr ?>
                                                <strong><?php echo $mem_info['MEM_NM'] ?></strong>
                                            </h5>
                                            <p class="text-muted mb-0">
                                                <?php echo disp_date_kr($mem_info['BTHDAY']) ?> (<?php echo disp_age($mem_info['BTHDAY'])?>세)
                                            </p>
                                        </div>
                                    </div>

                                    <!-- 회원 정보 테이블 -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th width="30%"><i class="fas fa-id-card mr-1"></i>아이디</th>
                                                    <td><?php echo $mem_info['MEM_ID'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th><i class="fas fa-phone mr-1"></i>전화번호</th>
                                                    <td><?php echo disp_phone($mem_info['MEM_TELNO'])?></td>
                                                </tr>
                                                <tr>
                                                    <th><i class="fas fa-lock mr-1"></i>락커</th>
                                                    <td>
                                                        <?php if(empty($lockr_01)) : ?>
                                                            <span class="text-muted">미배정</span>
                                                        <?php else : ?>
                                                            <?php foreach ($lockr_01 as $r1) :?>
                                                                <span class="badge bg-info"><?php echo $r1['LOCKR_NO']?>번</span>
                                                            <?php endforeach;?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><i class="fas fa-golf-ball mr-1"></i>골프라카</th>
                                                    <td>
                                                        <?php if(empty($lockr_02)) : ?>
                                                            <span class="text-muted">미배정</span>
                                                        <?php else : ?>
                                                            <?php foreach ($lockr_02 as $r2) :?>
                                                                <span class="badge bg-success"><?php echo $r2['LOCKR_NO']?>번</span>
                                                            <?php endforeach;?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- 통계 정보 -->
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <div class="stat-box">
                                                <div class="stat-number"><?php echo number_format($rank_info['sum_paymt_amt'])?></div>
                                                <div class="stat-label">총 매출액 (원)</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-box">
                                                <div class="stat-number"><?php echo number_format($rank_info['paymt_ranking'])?></div>
                                                <div class="stat-label">매출 순위 (위)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 가입 정보 -->
                                    <div class="mt-3">
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <th width="25%">가입일</th>
                                                    <td width="25%"><?php echo date('Y-m-d', strtotime($mem_info['JON_DATETM'])); ?></td>
                                                    <th width="25%">가입장소</th>
                                                    <td width="25%"><?php echo $sDef['JON_PLACE'][$mem_info['JON_PLACE']]?></td>
                                                </tr>
                                                <tr>
                                                    <th>등록일</th>
                                                    <td><?php echo date('Y-m-d', strtotime($mem_info['REG_DATETM'])); ?></td>
                                                    <th>등록장소</th>
                                                    <td><?php echo $sDef['REG_PLACE'][$mem_info['REG_PLACE']]?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <!-- BUTTON [START] -->
                                        <button type="button" class="btn btn-warning btn-block" onclick="mem_info_modify('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-user-edit"></i> 수정하기</button></li>
                                        </ul>
                                        <!-- BUTTON [END] -->
                                    </div>

                                </div>
                            </div>
                        </div>



                        <div class="col-lg-4 col-1p">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fas fa-calendar-check mr-2"></i>출석 현황</h3>
                                </div>

                                <div class="panel-body">
                                    <?php
                                        // 두날짜 사이의 날수를 계산한다.
                                        $attd_diff_days = disp_diff_date($attd_info['sdate'],$attd_info['edate']) + 1;
                                        // 출석 퍼센트를 구한다.
                                        $attd_per = round(($attd_info['count'] / $attd_diff_days) * 100);
                                    ?>
                                    
                                    <!-- 출석률 프로그레스 바 -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="font-weight-bold">출석률</span>
                                            <span class="text-primary font-weight-bold"><?php echo $attd_per?>%</span>
                                        </div>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-gradient-primary" role="progressbar" 
                                                 style="width: <?php echo $attd_per?>%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
                                                 aria-valuenow="<?php echo $attd_per?>" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 출석 통계 -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="stat-box">
                                                <div class="stat-number"><?php echo $attd_info['count']?></div>
                                                <div class="stat-label">출석 일수</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-box">
                                                <div class="stat-number"><?php echo $attd_diff_days?></div>
                                                <div class="stat-label">전체 일수</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 기간 표시 -->
                                    <div class="alert alert-light text-center" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <?php echo date('Y-m-d',strtotime($attd_info['sdate']))?> ~ <?php echo date('Y-m-d',strtotime($attd_info['edate']))?>
                                    </div>
                                    <!-- 최근 출석 내역 -->
                                    <div class="mt-3">
                                        <h6 class="mb-3"><i class="fas fa-history mr-2"></i>최근 출석 내역</h6>
                                        <div class="attendance-list" style="max-height: 300px;">
                                            <?php if(empty($attd_list)) : ?>
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p>출석 내역이 없습니다.</p>
                                                </div>
                                            <?php else : ?>
                                                <?php foreach ($attd_list as $a) : ?>
                                                    <?php if($a['ATTD_YN'] == "Y") : ?>
                                                        <?php ($a['AUTO_CHK'] == "Y") ? $auto_chk = "text-danger font-weight-bold" : $auto_chk = ""; ?>
                                                        <div class="attd-item mb-2 p-2 border-start border-3 border-success">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="badge bg-success me-2">
                                                                        <i class="far fa-check-circle"></i> 정상
                                                                    </span>
                                                                    <span class="<?php echo $auto_chk?>">
                                                                        <?php echo substr($a['CRE_DATETM'],0,16)?>
                                                                    </span>
                                                                    <?php if($a['AUTO_CHK'] == "Y") : ?>
                                                                        <span class="badge bg-danger ms-2">자동</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="attd-item mb-2 p-2 border-start border-3 border-warning">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="badge bg-warning text-dark me-2">
                                                                        <i class="far fa-clock"></i> 재입장
                                                                    </span>
                                                                    <span><?php echo substr($a['CRE_DATETM'],0,16)?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="card-footer clearfix mt20">
                                        <!-- BUTTON [START] -->
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="ac-btn">
                                                <button type="button" class="btn btn-blue btn-warning size13" onclick="more_attd('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-search"></i> 출석현황 더보기</button>
                                                <button type="button" class="btn btn-purple btn-warning size13" onclick="more_pt_attd('<?php echo $mem_info['MEM_SNO']?>');">
                                                    <i class="fa fa-search"></i> PT수업내역</button>
                                            </li>
                                        </ul>
                                        <!-- BUTTON [END] -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-1p">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fas fa-sticky-note mr-2"></i>메모내역</h3>
                                </div>

                                <div class="panel-body">
                                    <!-- 메모 검색 -->
                                    <div class="mb-3">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" id="memo-search" placeholder="메모 검색...">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- 메모 추가 폼 -->
                                    <div class="memo-item mb-3 p-3 border rounded" id="new-memo-form">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <button type="button" class="btn btn-sm btn-secondary" id="new-memo-toggle" onclick="toggleNewMemoType()">
                                                <i class="fas fa-star"></i> 일반메모
                                            </button>
                                            <div class="text-muted" style="font-size: 14px;" id="new-memo-datetime">
                                                <?php echo date('Y-m-d')?>
                                            </div>
                                        </div>
                                        <div class="memo-content" 
                                             contenteditable="true" 
                                             id="new-memo-content"
                                             style="font-size: 14px; line-height: 1.5; padding: 8px; border: 1px solid transparent; border-radius: 4px; min-height: 50px; cursor: text;"
                                             onfocus="onNewMemoFocus()" 
                                             onblur="onNewMemoBlur('<?php echo $mem_info['MEM_SNO']?>')"
                                             oninput="onNewMemoInput()"
                                             placeholder="새 메모를 입력하세요...">
                                        </div>
                                    </div>
                                    
                                    <div class="memo-list" style="max-height: 400px;">
                                        <?php if(empty($memo_list)) : ?>
                                            <div class="text-center text-muted py-5">
                                                <i class="fas fa-sticky-note fa-3x mb-3"></i>
                                                <p>등록된 메모가 없습니다.</p>
                                            </div>
                                        <?php else : ?>
                                            <?php foreach ($memo_list as $m) :?>
                                                <div class="memo-item mb-3 p-3 border rounded" style="border-color: #dee2e6; <?php echo $m['PRIO_SET'] == 'Y' ? 'background-color: #fff5f5;' : ''; ?>">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <button type="button" 
                                                                class="btn btn-sm memo-type-toggle <?php echo $m['PRIO_SET'] == 'Y' ? 'btn-danger' : 'btn-secondary'; ?>" 
                                                                data-memo-sno="<?php echo $m['MEMO_MGMT_SNO']?>"
                                                                data-current-type="<?php echo $m['PRIO_SET']?>"
                                                                onclick="changeMemoType(this, '<?php echo $m['MEMO_MGMT_SNO']?>')">
                                                            <i class="fas fa-star"></i> <?php echo $m['PRIO_SET'] == 'Y' ? '중요메모' : '일반메모'; ?>
                                                        </button>
                                                        <div class="text-muted" style="font-size: 14px;">
                                                            <?php echo substr($m['CRE_DATETM'],0,16)?>
                                                        </div>
                                                    </div>
                                                    <div class="memo-content" 
                                                         contenteditable="true" 
                                                         data-memo-sno="<?php echo $m['MEMO_MGMT_SNO']?>" 
                                                         data-original-content="<?php echo htmlspecialchars($m['MEMO_CONTS'])?>" 
                                                         style="font-size: 14px; line-height: 1.5; padding: 8px; border: 1px solid transparent; border-radius: 4px; min-height: 50px; cursor: text;">
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- 메모 버튼 영역 -->
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <small class="text-muted" style="font-size:0.8rem;">
                                                <i class="fas fa-info-circle"></i> 메모를 클릭하면 직접 수정할 수 있습니다.
                                            </small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div> <!--  panel-body -->


                <!-- 주요 기능 버튼 -->
                <div class="mt-4 mb-4">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <button type="button" class="btn btn-white" onclick="more_info_hide();">
                            <i class="fas fa-eye mr-2"></i>기본보기
                        </button>
                        <button type="button" class="btn btn-green" onclick="info_mem_buy_event('<?php echo $mem_info['MEM_SNO']?>');">  
                            <i class="fas fa-shopping-cart mr-2"></i>구매하기
                        </button>
                        <button type="button" style="display:none" class="btn btn-info" onclick="direct_attd('<?php echo $mem_info['MEM_SNO']?>');"> 
                            <i class="fas fa-user-check mr-2"></i>수동출석
                        </button>
                        <button type="button" class="btn btn-danger" onclick="domcy_acppt();"> 
                            <i class="fas fa-pause-circle mr-2"></i>휴회신청
                        </button>
                        <button type="button" class="btn btn-warning" onclick="send_event('<?php echo $mem_info['MEM_SNO']?>');"> 
                            <i class="fas fa-gift mr-2"></i>상품 보내기
                        </button>
                    </div>
                </div>


            </div>

        </div> <!-- col-12 -->
    </div> <!-- mem-info-header -->





    <div class="panel panel-inverse mb-4">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-shopping-cart mr-2"></i>판매 리스트</h4>
        </div>
        <div class="panel-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr class="text-center">
                            <th class="text-nowrap" width="100">옵션</th>
                            <th class="text-nowrap">구매일시</th>
                            <th class="text-nowrap">판매상태</th>
                            <th class="text-nowrap">처리일시</th>
                            <th class="text-nowrap">판매상품명</th>
                            <th class="text-nowrap">기간</th>
                            <th class="text-nowrap">시작일</th>
                            <th class="text-nowrap">종료일</th>
                            <th class="text-nowrap">수업</th>
                            <th class="text-nowrap">휴회일</th>
                            <th class="text-nowrap">휴회횟수</th>
                            <th class="text-nowrap">판매금액</th>
                            <th class="text-nowrap">결제금액</th>
                            <th class="text-nowrap">미수금액</th>
                            <th class="text-nowrap">수업강사</th>
                            <th class="text-nowrap">판매강사</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
								$list_count = 0;
								for($i=0; $i<3; $i++) :
								foreach($event_list[$i] as $r) :
									if ($i == 1) { // 이용중
										$backColor = "";
										$rowClass = "table-success";
									}
									if ($i == 0) { // 예약됨
										$backColor = "";
										$rowClass = "table-info";
									}
									if ($i == 2) { // 종료됨
										$backColor = "";
										$rowClass = "table-secondary";
									}
									
									$list_count++;
								?>
                    <tr class="<?php echo $rowClass ?>">
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-label="추가옵션 메뉴 열기">
                                    <i class="fas fa-ellipsis-v"></i> 추가옵션
                                </button>
                                <?php if ($i == 0): // 이용중 ?>
                                <div class="dropdown-menu text-sm" role="menu">
                                    <a class="dropdown-item" onclick="change_domcy_day('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회일 추가</a>
                                    <a class="dropdown-item" onclick="change_domcy_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회횟수 추가</a>
                                    <a class="dropdown-item" onclick="change_clas_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">수업추가</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동시작일</a>
                                    <a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동종료일</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
                                    <a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_event_trans('<?php echo $r['MEM_SNO'] ?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">양도하기</a>
                                    <a class="dropdown-item" onclick="change_event_just_end('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">강제종료</a>
                                    <a class="dropdown-item" onclick="change_event_refund('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">환불하기</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="test_clas_chk('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">수업하기(TEST)</a>
                                </div>
                                <?php elseif ($i == 1): // 예약됨 ?>
                                <div class="dropdown-menu text-sm" role="menu">
                                    <a class="dropdown-item" onclick="change_domcy_day('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회일 추가</a>
                                    <a class="dropdown-item" onclick="change_domcy_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['DOMCY_POSS_EVENT_YN'] ?>');">휴회횟수 추가</a>
                                    <a class="dropdown-item" onclick="change_clas_cnt('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">수업추가</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동시작일</a>
                                    <a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동종료일</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
                                    <a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_event_trans('<?php echo $r['MEM_SNO'] ?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">양도하기</a>
                                    <a class="dropdown-item" onclick="change_event_just_end('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">강제종료</a>
                                    <a class="dropdown-item" onclick="change_event_refund('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>');">환불하기</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="test_clas_chk('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">수업하기(TEST)</a>
                                </div>
                                <?php elseif ($i == 2): // 종료됨 ?>
                                <div class="dropdown-menu text-sm" role="menu">
                                    <a class="dropdown-item" onclick="change_exr_s_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동시작일</a>
                                    <a class="dropdown-item" onclick="change_exr_e_date('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['M_CATE']?>');">운동종료일</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" onclick="change_stchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['STCHR_ID'] ?>','<?php echo $r['CLAS_DV']?>');">수업강사</a>
                                    <a class="dropdown-item" onclick="change_ptchr('<?php echo $r['BUY_EVENT_SNO'] ?>','<?php echo $r['EVENT_STAT'] ?>','<?php echo $r['PTCHR_ID'] ?>','<?php echo substr($r['BUY_DATETM'],0,10)?>');">판매강사</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo substr($r['BUY_DATETM'],0,16)?></td>
                        <td class="text-center"><?php echo $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]?></td>
                        <td><?php echo substr($r['MOD_DATETM'],0,16)?></td>
                        <td>
                            <?php if ($r['EVENT_STAT_RSON'] == "51" || $r['EVENT_STAT_RSON'] == "81" || $r['EVENT_STAT_RSON'] == "61" || $r['EVENT_STAT_RSON'] == "62") :?>
                            <i class="fa fa-list" onclick="more_info_show('<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['EVENT_STAT_RSON']?>');"></i>
                            <?php endif; ?>
                            <?php 	  
											  echo "<small class='badge bg-success'>".$sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]."</small>";
										?>
                            <input type="hidden" id="<?php echo "sell_event_nm_".$r['BUY_EVENT_SNO']?>" value="<?php echo $r['SELL_EVENT_NM']?>" />
                            <?php echo $r['SELL_EVENT_NM']?>
                            <!--  (<?php echo $r['BUY_EVENT_SNO']?>) -->

                            <?php if($r['LOCKR_SET'] == "Y") : 
												if ($r['LOCKR_NO'] != '') :
													echo disp_locker($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
												else :
												    if ($r['EVENT_STAT'] != '99') :
										?>
                            <small class='badge bg-danger' style='cursor:pointer' onclick="lockr_select('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO']?>','<?php echo $r['LOCKR_KND']?>','<?php echo $mem_info['MEM_GENDR']?>');">선택하기</small>
                            <?php
										            endif;
												endif ;
											  endif;
										?>

                        </td>

                        <td style="text-align:right"><?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?></td>
                        <td><span id="<?php echo "exr_s_date_".$r['BUY_EVENT_SNO']?>">

                                <?php if($r['EVENT_STAT'] == "01" && ($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") ) : ?>
                                <button type='button' class='btn btn-info btn-xs' onclick="pt_use('<?php echo $r['STCHR_ID']?>','<?php echo $r['BUY_EVENT_SNO'] ?>');">이용시작</button>
                                <?php endif; ?>
                                <?php echo $r['EXR_S_DATE']?>
                            </span></td>
                        <td><span id="<?php echo "exr_e_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_E_DATE']?></span><?php echo disp_add_cnt($r['ADD_SRVC_EXR_DAY'])?></td>

                        <!-- ############### 수업 영역 ################# -->
                        <?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                        <?php
									   $sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT']; // 총 수업 남은 횟수
									?>
                        <td class='text-center'><?php echo $sum_clas_cnt?>
                            <?php if($r['ADD_SRVC_CLAS_CNT'] > 0) : ?>
                            <?php echo disp_add_cnt($r['ADD_SRVC_CLAS_CNT'])?>
                            <?php endif;?>
                        </td>

                        <?php else :?>
                        <td class='text-center'>-</td>
                        <?php endif ;?>
                        <!-- ############### 수업 영역 ################# -->

                        <!-- ############### 휴회 영역 ################# -->
                        <?php if($r['DOMCY_POSS_EVENT_YN'] == "Y") :?>
                        <td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_DAY'] ?></td>
                        <td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_CNT'] ?></td>
                        <?php else :?>
                        <td class='text-center' style='font-size:0.8rem'>불가능</td>
                        <td class='text-center' style='font-size:0.8rem'>불가능</td>
                        <?php endif ;?>
                        <!-- ############### 휴회 영역 ################# -->

                        <td style="text-align:right"><?php echo number_format($r['REAL_SELL_AMT']) ?></td>
                        <td style="text-align:right"><?php echo number_format($r['BUY_AMT']) ?></td>
                        <td style="text-align:right">
                            <?php if ($r['RECVB_AMT'] == 0) :?>
                            <?php echo number_format($r['RECVB_AMT']) ?>
                            <?php else :?>
                            <button type='button' class='btn btn-danger btn-xs' onclick="misu_select('<?php echo $r['MEM_SNO']?>','<?php echo $r['BUY_EVENT_SNO']?>');">
                                <?php echo number_format($r['RECVB_AMT']) ?>
                            </button>
                            <?php endif ;?>
                        </td>

                        <td class='text-center'><?php echo $r['STCHR_NM'] ?></td>
                        <td class='text-center'><?php echo $r['PTCHR_NM'] ?></td>
                    </tr>
                    <?php 
								endforeach;
								endfor; 
								?>
                </tbody>
            </table>
            <?php if($list_count == 0) :?>
            <div>
                <button type="button" class="btn btn-default btn-block btn-sm text-center" style='width:100%; height:240px'>등록된 상품이 없습니다.</button>
            </div>
            <?php endif;?>

        </div>
    </div>





    <!-- ############################## MODAL [ SATRT ] #################################### -->

    <!-- ============================= [ modal-sm START 운동시작일 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_sdate">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">운동 시작일 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="pop_sdate_buy_sno" id="pop_sdate_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text input-readonly" style='width:150px'>현재 운동시작일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_sdate_info_sdate" id="pop_sdate_info_sdate" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>변경할 운동시작일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_sdate_change_sdate" id="pop_sdate_change_sdate">
                    </div>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_change_sdate_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 운동종료일 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_edate">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">운동 종료일 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="pop_edate_buy_sno" id="pop_edate_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <input type="text" class="form-control" name="pop_sell_event_nm" id="pop_sell_event_nm" style="text-align:center" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text input-readonly" style='width:150px'>현재 운동종료일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_edate_info_edate" id="pop_edate_info_edate" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>변경할 운동종료일</span>
                        </span>
                        <input type="text" class="form-control" name="pop_edate_change_edate" id="pop_edate_change_edate" onchange="edate_calu_days();">
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가될 일수</span>
                        </span>
                        <input type="text" class="form-control" id="pop_edate_change_add_edate_cnt" readonly>
                    </div>


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_change_edate_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 휴회일 추가 ] ============================================ -->
    <div class="modal fade" id="modal_pop_domcy_day" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">휴회일 추가</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="domcy_day_buy_sno" id="domcy_day_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가할 휴회일 수</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_day_day" id="domcy_day_day">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_domcy_day_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 휴회횟수 추가 ] ============================================ -->
    <div class="modal fade" id="modal_pop_domcy_cnt">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">휴회횟수 추가</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="domcy_cnt_buy_sno" id="domcy_cnt_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가할 휴회 횟수</span>
                        </span>
                        <input type="text" class="form-control" name="domcy_cnt_cnt" id="domcy_cnt_cnt">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_domcy_cnt_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 수업 추가 ] ============================================ -->
    <div class="modal fade" id="modal_pop_clas_cnt">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">수업 추가</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="clas_cnt_buy_sno" id="clas_cnt_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>추가할 수업수</span>
                        </span>
                        <input type="text" class="form-control" name="clas_cnt_cnt" id="clas_cnt_cnt">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_clas_cnt_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 수업강사 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_stchr">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">수업강사 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="stchr_buy_sno" id="stchr_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <select class="select2 form-control" style="width: 250px;" name="ch_stchr_id" id="ch_stchr_id">
                            <option>강사 선택</option>
                            <?php foreach ($tchr_list as $r) : ?>
                            <option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_stchr_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 판매강사 변경 ] ============================================ -->
    <div class="modal fade" id="modal_pop_ptchr">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">판매강사 변경</h4>
                    <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ptchr_buy_sno" id="ptchr_buy_sno" />
                    <div class="input-group input-group-sm mb-1">
                        <select class="select2 form-control" style="width: 250px;" name="ch_ptchr_id" id="ch_ptchr_id">
                            <option>강사 선택</option>
                            <?php foreach ($tchr_list as $r) : ?>
                            <option value="<?php echo $r['MEM_ID']?>">[<?php echo $r['TCHR_POSN_NM']?>] <?php echo $r['MEM_NM']?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-sm btn-success" onclick="btn_ptchr_submit();">변경하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-lg START 양도하기 ] ============================================ -->
    <div class="modal fade" id="modal_trans_mem_search_form">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h5 class="modal-title">양수자 회원 검색</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                    

                    <div class="input-group input-group-sm mb-1 col-md-6">
                        <span class="input-group-append">
                            <span class="input-group-text" style='width:150px'>회원명</span>
                        </span>
                        <input type="text" class="form-control" style='width:100px !important;' placeholder="회원명을 입력하세요" name="input_trans_mem_search" id="input_trans_mem_search" />
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info btn-flat" id="btn_trans_mem_search">검색</button>
                        </span>
                    </div>

                    <!-- FORM [START] -->
                    <div class="input-group input-group-sm mb-1">

                        <table class="table table-bordered table-hover table-striped col-md-12" id='trans_mem_search_table'>
                            <thead>
                                <tr>
                                    <th>상태</th>
                                    <th>이름</th>
                                    <th>아이디</th>
                                    <th>전화번호</th>
                                    <th>생년월일</th>
                                    <th>성별</th>
                                    <th>선택</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>

                    

                    <!-- FORM [END] -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->

    <!-- ============================= [ modal-sm START 휴회하기 ] ============================================ -->
    <div class="modal fade" id="modal_pop_domcy">
        <div class="modal-dialog" style="max-width: 600px;">
            <form id="frmDomcy" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-calendar-times mr-2"></i>휴회 신청</h5>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="domcy-date-section">
                            <h6><i class="fas fa-calendar-alt mr-2"></i>휴회 기간 설정</h6>
                            <div class="domcy-date-inputs">
                                <div class="domcy-input-group">
                                    <label>시작일</label>
                                    <input type="text" class="form-control form-control-sm domcy-start-date" id="domcy_acppt_i_sdate" readonly>
                                </div>
                                <div class="domcy-input-group">
                                    <label>휴회일수</label>
                                    <input type="text" class="form-control form-control-sm domcy-application-days" id="domcy_acppt_i_cnt" placeholder="일수">
                                    <span class="text-muted">일</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="domcy-list-section">
                            <div class="domcy-list-header">
                                <i class="fas fa-list-check mr-2"></i>휴회 가능한 회원권
                            </div>
                            <div id="a-list">
                                <!-- 여기에 회원권 목록이 동적으로 추가됩니다 -->
                            </div>
                        </div>
                        
                        <!-- 템플릿 -->
                        <div id="domcy_template" style="display:none">
                            <div class="dormancy-item">
                                <div class="dormancy-item-header">
                                    <input type="checkbox" class="dormancy-checkbox">
                                    <strong class="dormancy-item-title item-title"></strong>
                                </div>
                                <div class="dormancy-date-group">
                                    <div class="dormancy-date-field">
                                        <label>시작:</label>
                                        <input type="text" class="form-control form-control-sm domcy-start-date item-start-date" readonly>
                                    </div>
                                    <div class="dormancy-date-field">
                                        <label>종료:</label>
                                        <input type="text" class="form-control form-control-sm domcy-end-date item-end-date" readonly>
                                    </div>
                                    <div class="dormancy-date-field">
                                        <label>사용일수:</label>
                                        <input type="text" class="form-control form-control-sm use-days item-use-days">
                                        <span class="text-muted">일</span>
                                    </div>
                                </div>
                                <div class="info-text item-info">
                                    <!-- 남은 정보가 여기에 표시됩니다 -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- 기존 필드들은 숨김 처리 -->
                        <div style="display:none;">
                            <input type="text" class="form-control" id="domcy_acppt_e_sdate" readonly>
                            <input type="text" class="form-control" name="domcy_acppt_day" id="domcy_acppt_day" readonly>
                            <input type="text" class="form-control" name="domcy_acppt_cnt" id="domcy_acppt_cnt" readonly>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>닫기
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="submitDomcyApplication();">
                            <i class="fas fa-check mr-1"></i>휴회신청 등록
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- ============================= [ modal-sm END ] ============================================== -->


    <!-- ============================= [ modal-sm START 메모등록 ] ============================================ -->
    <form name='form_memo_insert' id='form_mem_insert'>
        <input type="hidden" name="memo_mem_sno" id="memo_mem_sno" />
        <div class="modal fade" id="modal_memo_insert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">메모 등록하기</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-append">
                                <span class="input-group-text" style='width:120px'>중요메모 설정</span>
                            </span>

                            <div style='margin-top:4px;margin-left:5px;'>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="memoImp1" name="memo_prio_set" value="N" checked>
                                    <label for="memoImp1">
                                        <small>일반</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="memoImp2" name="memo_prio_set" value="Y">
                                    <label for="memoImp2">
                                        <small>중요</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="input-group input-group-sm mb-1">
                            <textarea rows='5' class="form-control" name="memo_content" id="memo_content"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-sm btn-success" onclick="btn_memo_insert();">메모 등록하기</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- ============================= [ modal-sm END ] ============================================== -->


    <!-- ============================= [ modal-sm START 메모 수정 ] ============================================ -->
    <form name='form_memo_modify' id='form_memo_modify'>
        <input type="hidden" name="modify_memo_mgmt_sno" id="modify_memo_mgmt_sno" />
        <div class="modal fade" id="modal_memo_modify">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">메모 수정하기</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="input-group input-group-sm mb-1">
                            <textarea rows='5' class="form-control" name="memo_content" id="modify_memo_content"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-sm btn-success" onclick="btn_memo_modify();">메모 수정하기</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- ============================= [ modal-sm END ] ============================================== -->






    <div class="modal fade" id="modal_mem_memo">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h5 class="modal-title">메모 더보기</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body" style="overflow-y: scroll; height:600px;">
                    <!-- FORM [START] -->
                    <div class="input-group input-group-sm mb-1">

                        <table class="table table-bordered table-striped" id='mem_memo_table'>
                            <thead>
                                <tr>
                                    <th class="memo-text-xs text-center" style='width:80px'>구분</th>
                                    <th class="memo-text-xs text-center" style='width:140px'>등록일</th>
                                    <th class="memo-text-xs text-center">메모내용</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <!-- FORM [END] -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal_mem_modify">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-lightblue">
                    <h5 class="modal-title">회원정보수정</h4>
                        <button type="button" class="close3" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <form id="form_mem_modify">
                            <input type="hidden" id="mem_sno" name="mem_sno" value="<?php echo $mem_info['MEM_SNO']?>" />
                            <div class="form-group">

                                <div class="photo-row">
                                    <!-- 프로필 사진 영역 -->
                                    <div>
                                        <img class="preview_mem_photo" id="preview_mem_photo"
                                            src="<?php echo empty($mem_info['MEM_THUMB_IMG']) ? '/dist/img/default_profile.png' : $mem_info['MEM_THUMB_IMG']; ?>" 
                                            data-default-thumb="<?php echo htmlspecialchars($mem_info['MEM_THUMB_IMG'] ?? '', ENT_QUOTES); ?>" 
                                            data-default-main="<?php echo htmlspecialchars($mem_info['MEM_MAIN_IMG'] ?? '', ENT_QUOTES); ?>" 
                                            alt="회원사진"
                                            style="border-radius: 50%; cursor: pointer; width: 120px; height: 120px; object-fit: cover; border: 3px solid #e9ecef;"
                                            onclick="showFullPhoto('<?php echo addslashes($mem_info['MEM_MAIN_IMG'] ?? '') ?>')"
                                            onerror="this.src='/dist/img/default_profile.png'" >
                                    </div>
                                    
                                    <!-- 오른쪽 컨텐츠 -->
                                    <div class="photo-action">
                                        <!-- 안내 문구 -->
                                        <div class="photo-guide-text">
                                            정면을 바라보며,<br>
                                            상반신이 잘 보이도록 촬영해주세요.
                                        </div>
                                        <!-- 촬영 버튼 -->
                                        <button type="button" class="capture-btn" onclick="openCameraModal()">
                                            <i class="fas fa-camera"></i> 촬영
                                        </button>
                                    </div>
                                </div>

                                <!-- 웹캠 영상 영역 -->
                                <div id="camera_wrap" style="margin-top: 10px; display: none; text-align: center;">
                                    <!-- 웹캠 영상 -->
                                    <video id="camera_stream" autoplay playsinline></video>

                                    <!-- 얼굴 가이드 -->
                                    <div class="passport-guide"></div>

                                    <!-- 촬영 버튼 -->
                                    <button type="button" class="btn btn-success btn-sm mt-2" onclick="capturePhoto()">촬영</button>
                                </div>

                                <input type="hidden" id="captured_photo" name="captured_photo" />
                                
                                <!-- 🔍 얼굴 인식 데이터 필드들 -->
                                <input type="hidden" id="face_encoding_data" name="face_encoding_data" />
                                <input type="hidden" id="glasses_detected" name="glasses_detected" value="0" />
                                <input type="hidden" id="quality_score" name="quality_score" value="0" />
                            </div>

                            <div class="form-group mt20">
                                <label for="inputName">회원 아이디</label>
                                <input type="text" id="mem_id" name="mem_id" class="form-control mt4" value="<?php echo $mem_info['MEM_ID']?>" readonly>
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 비밀번호</label>
                                <input type="text" id="mem_pwd" name="mem_pwd" class="form-control mt4" placeholder="비밀번호 변경시에만 입력해주세요.">
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 이름<span class="text-danger">*</span></label>
                                <input type="text" id="mem_nm" name="mem_nm" class="form-control mt4" value="<?php echo $mem_info['MEM_NM']?>">
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 생년월일</label>
                                <input type="text" id="bthday" name="bthday" class="form-control mt4" value="<?php echo $mem_info['BTHDAY']?>" data-inputmask="'mask': ['9999/99/99']" data-mask>
                            </div>

                            <div class="form-group mt20">
                                <label for="inputName">회원 성별</label>
                                <div class="icheck-primary d-inline ml20">
                                    <input type="radio" id="radioGrpCate1" name="mem_gendr" value="M" <?php if($mem_info['MEM_GENDR'] == "M") {?> checked <?php }?>>
                                    <label for="radioGrpCate1">
                                        <small>남</small>
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline ml20">
                                    <input type="radio" id="radioGrpCate2" name="mem_gendr" value="F" <?php if($mem_info['MEM_GENDR'] == "F") {?> checked <?php }?>>
                                    <label for="radioGrpCate2">
                                        <small>여</small>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 전화번호<span class="text-danger">*</span></label>
                                <input type="text" id="mem_telno" name="mem_telno" class="form-control mt4" value="<?php echo $mem_info['MEM_TELNO']?>" data-inputmask="'mask': ['99-9999-999[9]','999-9999-9999']" data-mask>
                            </div>

                            <div class="form-group mt10">
                                <label for="inputName">회원 주소</label>
                                <input type="text" id="mem_addr" name="mem_addr" class="form-control mt4" value="<?php echo $mem_info['MEM_ADDR']?>">
                            </div>


                        </form>

                    </div>




                    <!-- FORM [END] -->
                </div>
                <div class="modal-footer">
                    <button type="button" class='btn btn-info btn-sm' onclick="mem_modify();">회원정보 수정하기</button>
                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>






    <!-- ============================= [ 카메라 모달 START ] ============================================ -->
    <div class="modal fade" id="modal_camera" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-camera"></i> 프로필 사진 촬영
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="stopCameraModal()">
                    </button>
                </div>
                <div class="modal-body">
                    <div id="camera_wrap_modal">
                        <video id="camera_stream_modal" autoplay playsinline></video>
                        <!-- 얼굴 실루엣 가이드 -->
                        <div class="face-silhouette"></div>
                    </div>
                    
                    <!-- 촬영 버튼을 비디오 밖으로 이동 -->
                    <button type="button" class="camera-capture-btn" onclick="capturePhotoModal()">
                        <i class="fas fa-camera"></i> 촬영하기
                    </button>
                    
                    <!-- 얼굴 인식 상태 표시 -->
                    <div id="face_recognition_status_modal" style="display: none; margin-top: 15px; padding: 10px; border-radius: 5px; text-align: center;">
                        <span id="face_status_text_modal"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="stopCameraModal()">취소</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= [ 카메라 모달 END ] ============================================== -->

    <!-- ############################## MODAL [ END ] ###################################### -->



</section>

<form name="form_change_sdate" id="form_change_sdate" method="post" action="/ttotalmain/ajax_info_mem_change_sdate_proc">
    <input type="hidden" name="fc_sdate_buy_sno" id="fc_sdate_buy_sno" />
    <input type="hidden" name="fc_sdate_sdate" id="fc_sdate_sdate" />
</form>

<form name="form_change_edate" id="form_change_edate" method="post" action="/ttotalmain/ajax_info_mem_change_edate_proc">
    <input type="hidden" name="fc_edate_buy_sno" id="fc_edate_buy_sno" />
    <input type="hidden" name="fc_edate_edate" id="fc_edate_edate" />
</form>

<form name="form_domcy_day" id="form_domcy_day" method="post" action="/ttotalmain/ajax_info_mem_domcyday_proc">
    <input type="hidden" name="fc_domcy_day_buy_sno" id="fc_domcy_day_buy_sno" />
    <input type="hidden" name="fc_domcy_day_day" id="fc_domcy_day_day" />
</form>

<form name="form_domcy_cnt" id="form_domcy_cnt" method="post" action="/ttotalmain/ajax_info_mem_domcycnt_proc">
    <input type="hidden" name="fc_domcy_cnt_buy_sno" id="fc_domcy_cnt_buy_sno" />
    <input type="hidden" name="fc_domcy_cnt_cnt" id="fc_domcy_cnt_cnt" />
</form>

<form name="form_clas_cnt" id="form_clas_cnt" method="post" action="/ttotalmain/ajax_info_mem_clascnt_proc">
    <input type="hidden" name="fc_clas_cnt_buy_sno" id="fc_clas_cnt_buy_sno" />
    <input type="hidden" name="fc_clas_cnt_cnt" id="fc_clas_cnt_cnt" />
</form>

<form name="form_stchr" id="form_stchr" method="post" action="/ttotalmain/ajax_info_mem_stchr_proc">
    <input type="hidden" name="fc_stchr_buy_sno" id="fc_stchr_buy_sno" />
    <input type="hidden" name="fc_ch_stchr_id" id="fc_ch_stchr_id" />
</form>

<form name="form_ptchr" id="form_ptchr" method="post" action="/ttotalmain/ajax_info_mem_ptchr_proc">
    <input type="hidden" name="fc_ptchr_buy_sno" id="fc_ptchr_buy_sno" />
    <input type="hidden" name="fc_ch_ptchr_id" id="fc_ch_ptchr_id" />
</form>

<form name="form_trans" id="form_trans" method="post" action="/ttotalmain/event_trans_info">
    <input type="hidden" name="fc_trans_buy_sno" id="fc_trans_buy_sno" />
    <input type="hidden" name="fc_trans_mem_sno" id="fc_trans_mem_sno" />
    <input type="hidden" name="fc_trans_tmem_sno" id="fc_trans_tmem_sno" />
</form>

<form name="form_domcy" id="form_domcy" method="post" action="/api/ajax_domcy_acppt_proc">
    <input type="hidden" name="fc_domcy_cnt" id="fc_domcy_cnt" value="<?php echo $poss_domcy['cnt']?>" />
    <input type="hidden" name="fc_domcy_day" id="fc_domcy_day" value="<?php echo $poss_domcy['day']?>" />
    <input type="hidden" name="fc_domcy_buy_sno" id="fc_domcy_aply_buy_sno" value="<?php echo $poss_domcy['buy_event_sno']?>" />
    <input type="hidden" name="fc_domcy_mem_sno" id="fc_domcy_mem_sno" value="<?php echo $mem_info['MEM_SNO']?>" />
    <input type="hidden" name="fc_domcy_s_date" id="fc_domcy_s_date" />
    <input type="hidden" name="fc_domcy_use_day" id="fc_domcy_use_day" />
</form>


<?=$jsinc ?>

<script>
    // PHP에서 JavaScript로 데이터 전달
    const eventListData = <?php echo json_encode($event_list ?? [], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); ?>;
    const memInfo = <?php echo json_encode($mem_info ?? [], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); ?>;
    const possDomcyData = <?php echo json_encode($poss_domcy ?? [], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); ?>;
    
    console.log('EventListData loaded:', eventListData);
    console.log('MemInfo loaded:', memInfo);
    console.log('PossDomcyData loaded:', possDomcyData);
    
    let stream = null;

    function openCamera() {
        // 브라우저 호환성 체크
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error('getUserMedia is not supported in this browser/context');
            
            // HTTPS 체크
            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
                alert('카메라를 사용하려면 HTTPS 연결이 필요합니다.\n\n' +
                      '현재 연결: ' + window.location.protocol + '//' + window.location.hostname + '\n' +
                      'HTTPS로 접속하거나 시스템 관리자에게 문의하세요.');
            } else {
                alert('이 브라우저는 카메라 기능을 지원하지 않습니다.\nChrome, Firefox, Edge 등 최신 브라우저를 사용해주세요.');
            }
            return;
        }

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(mediaStream) {
                stream = mediaStream;
                const video = document.getElementById('camera_stream');
                video.srcObject = stream;
                document.getElementById('camera_wrap').style.display = 'block';
            })
            .catch(function(err) {
                console.error('카메라 접근 오류:', err);
                alert("카메라 접근 권한이 필요합니다.");
            });
    }

    function capturePhoto() {
        console.log('📸 capturePhoto 함수 호출됨!');
        
        const video = document.getElementById('camera_stream');
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0);

        // 📌 JPEG로 base64 생성 (품질 0.9)
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        console.log('📸 Base64 이미지 생성 완료:', dataUrl.substring(0, 50) + '...');

        // 썸네일 이미지 변경
        const preview = document.getElementById('preview_mem_photo');
        preview.src = dataUrl;

        // base64 저장
        document.getElementById('captured_photo').value = dataUrl;
        console.log('📸 captured_photo 필드에 저장 완료');

        // 동적으로 onclick의 원본 이미지도 base64로 변경
        preview.setAttribute('onclick', `showFullPhoto("${dataUrl}")`);

        // 🔍 얼굴 인식 처리 시작
        console.log('📸 얼굴 인식 함수 호출 시작...');
        processFaceRecognition(dataUrl);

        // 웹캠 종료
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        document.getElementById('camera_wrap').style.display = 'none';
        console.log('📸 capturePhoto 함수 완료');
    }

    // 🔍 얼굴 인식 처리 함수
    async function processFaceRecognition(imageBase64) {
        try {
            // 얼굴 인식 상태 표시
            showFaceRecognitionStatus('processing', '얼굴 분석 중...');

            // Base64에서 data:image/jpeg;base64, 부분 제거 - 더 안전한 방법
            let base64Data = imageBase64;
            if (base64Data.includes(',')) {
                base64Data = base64Data.split(',')[1];
            }
            
            // Base64 데이터 검증
            if (!base64Data || base64Data.length < 100) {
                throw new Error('유효하지 않은 이미지 데이터입니다.');
            }

            // 얼굴 인식 API 호출
            console.log('🔍 얼굴 인식 API 호출 시작...');
            console.log('Base64 데이터 크기:', base64Data.length);
            console.log('Base64 시작 부분:', base64Data.substring(0, 50) + '...');
            
            const response = await fetch('/FaceTest/recognize_for_registration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    image: base64Data
                })
            });
            
            console.log('API 응답 상태:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('API 응답 데이터:', result);
            debugger;
            if (result.suitable_for_registration) {
                // 얼굴 검출 성공
                showFaceRecognitionStatusModal('success', '얼굴이 정상적으로 인식되었습니다!');
                
                // 얼굴 데이터를 hidden 필드에 저장
                document.getElementById('face_encoding_data').value = JSON.stringify(result.face_data);
                document.getElementById('glasses_detected').value = result.face_data.glasses_detected ? '1' : '0';
                document.getElementById('quality_score').value = result.face_data.quality_score || 0.85;
                
                // 2초 후 모달 자동 닫기
                setTimeout(() => {
                    $('#modal_camera').modal('hide');
                    stopCameraModal();
                }, 2000);
                
            } else {
                 // 얼굴 검출 실패 - 상세한 안내 메시지 제공
                 let userMessage = '얼굴이 감지되지 않았습니다.';
                let guidance = [];
                // 품질 검증 실패 시 상세 권장사항 제공
                if (result.suitable_for_registration === false && result.recommendations) {
                    // API에서 제공하는 권장사항 사용
                    guidance = result.recommendations;
                } else if (result.face_data && result.face_data.quality_details) {
                    // 품질 상세 정보를 기반으로 안내
                    const details = result.face_data.quality_details;
                    
                    if (details.face_size_ratio < 0.15) {
                        guidance.push('• 얼굴이 너무 작습니다. 카메라에 더 가까이 와주세요.');
                    } else if (details.face_size_ratio > 0.7) {
                        guidance.push('• 얼굴이 너무 큽니다. 조금 뒤로 물러나주세요.');
                    }
                    
                    if (!details.face_centered) {
                        guidance.push('• 얼굴을 화면 중앙에 위치시켜주세요.');
                    }
                    
                    if (result.face_data.face_pose && !result.face_data.face_pose.is_frontal) {
                        const pose = result.face_data.face_pose;
                        if (Math.abs(pose.yaw) > 15) {
                            guidance.push('• 정면을 바라봐주세요. (좌우 각도 조정)');
                        }
                        if (Math.abs(pose.pitch) > 15) {
                            guidance.push('• 고개를 똑바로 들어주세요. (상하 각도 조정)');
                        }
                        if (Math.abs(pose.roll) > 10) {
                            guidance.push('• 머리를 똑바로 세워주세요. (기울기 조정)');
                        }
                    }
                    
                } else {
                    // 기본 안내 메시지
                    guidance = [
                        '• 카메라를 정면으로 바라봐주세요1.',
                        '• 조명이 밝은 곳에서 촬영해주세요.',
                        '• 얼굴이 화면 중앙에 오도록 조정해주세요.'
                    ];
                }
                
                // 최종 메시지 구성 (모바일용 HTML 포맷)
                if (guidance.length > 0) {
                    userMessage = '얼굴 인식 품질이 충분하지 않습니다.<br><br>' + 
                                    '<strong>개선 방법:</strong><br>' + 
                                    guidance.map(g => g.replace('•', '▫')).join('<br>');
                } else {
                    userMessage = response.error || '얼굴이 감지되지 않았습니다. 다시 촬영해주세요.';
                }
                
                
                // 재촬영 버튼 활성화를 위해 이미지 데이터 제거
               
                document.getElementById('face_encoding_data').value = '';
                document.getElementById('glasses_detected').value = '0';
                document.getElementById('quality_score').value = '0';
                // 얼굴 검출 실패
                showFaceRecognitionStatusModal('error', userMessage);
            }

        } catch (error) {
            console.error('얼굴 인식 오류:', error);
            showFaceRecognitionStatus('error', '얼굴 인식 중 오류가 발생했습니다: ' + error.message);
            
            // 얼굴 데이터 초기화
            document.getElementById('face_encoding_data').value = '';
            document.getElementById('glasses_detected').value = '0';
            document.getElementById('quality_score').value = '0';
        }
    }

    // 얼굴 인식 상태 표시 함수
    function showFaceRecognitionStatus(type, message) {
        // 상태 표시 영역이 없다면 생성
        let statusDiv = document.getElementById('face_recognition_status');
        if (!statusDiv) {
            statusDiv = document.createElement('div');
            statusDiv.id = 'face_recognition_status';
            statusDiv.style.cssText = `
                margin-top: 10px;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 13px;
                text-align: center;
            `;
            
            // 사진 영역 다음에 추가
            const photoRow = document.querySelector('.photo-row');
            if (photoRow && photoRow.parentNode) {
                photoRow.parentNode.insertBefore(statusDiv, photoRow.nextSibling);
            }
        }

        // 타입별 스타일 설정
        if (type === 'processing') {
            statusDiv.style.backgroundColor = '#fff3cd';
            statusDiv.style.color = '#856404';
            statusDiv.style.border = '1px solid #ffeaa7';
            statusDiv.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${message}`;
        } else if (type === 'success') {
            statusDiv.style.backgroundColor = '#d4edda';
            statusDiv.style.color = '#155724';
            statusDiv.style.border = '1px solid #c3e6cb';
            statusDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        } else if (type === 'error') {
            statusDiv.style.backgroundColor = '#f8d7da';
            statusDiv.style.color = '#721c24';
            statusDiv.style.border = '1px solid #f5c6cb';
            statusDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        } else if (type === 'info') {
            statusDiv.style.backgroundColor = '#d1ecf1';
            statusDiv.style.color = '#0c5460';
            statusDiv.style.border = '1px solid #bee5eb';
            statusDiv.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
        }

        statusDiv.style.display = 'block';

        // 3초 후 성공/오류 메시지 자동 숨김
        if (type !== 'processing') {
            setTimeout(() => {
                if (statusDiv) {
                    statusDiv.style.display = 'none';
                }
            }, 3000);
        }
    }


    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        document.getElementById('camera_wrap').style.display = 'none';
    }

    // 모달용 카메라 함수들
    let modalStream = null;

    function openCameraModal() {
        // jQuery로 모달 열기
        $('#modal_camera').modal('show');
        
        // 브라우저 호환성 체크
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error('getUserMedia is not supported in this browser/context');
            
            // HTTPS 체크
            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
                alert('카메라를 사용하려면 HTTPS 연결이 필요합니다.\n\n' +
                      '현재 연결: ' + window.location.protocol + '//' + window.location.hostname + '\n' +
                      'HTTPS로 접속하거나 시스템 관리자에게 문의하세요.');
            } else {
                alert('이 브라우저는 카메라 기능을 지원하지 않습니다.\nChrome, Firefox, Edge 등 최신 브라우저를 사용해주세요.');
            }
            $('#modal_camera').modal('hide');
            return;
        }

        navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'user' // 전면 카메라 우선
            }
        })
        .then(function(mediaStream) {
            modalStream = mediaStream;
            const video = document.getElementById('camera_stream_modal');
            video.srcObject = modalStream;
        })
        .catch(function(err) {
            console.error('카메라 접근 오류:', err);
            alert("카메라 접근 권한이 필요합니다.");
            $('#modal_camera').modal('hide');
        });
    }

    function capturePhotoModal() {
        console.log('📸 capturePhotoModal 함수 호출됨!');
        
        const video = document.getElementById('camera_stream_modal');
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0);

        // 📌 JPEG로 base64 생성 (품질 0.9)
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        console.log('📸 Base64 이미지 생성 완료:', dataUrl.substring(0, 50) + '...');

        // 썸네일 이미지 변경
        document.getElementById('preview_mem_photo').src = dataUrl;
        
        // base64 저장
        document.getElementById('captured_photo').value = dataUrl;
        
        // 🔍 얼굴 인식 처리 시작 (모달 내에서)
        processFaceRecognitionModal(dataUrl);
    }

    function stopCameraModal() {
        console.log('stopCameraModal 호출됨');
        
        // 스트림 정지
        if (modalStream) {
            modalStream.getTracks().forEach(track => {
                console.log('트랙 정지:', track.kind);
                track.stop();
            });
            modalStream = null;
        }
        
        // 비디오 요소 초기화
        const video = document.getElementById('camera_stream_modal');
        if (video) {
            video.pause();
            video.srcObject = null;
            video.load();
        }
        
        // 상태 메시지 초기화
        const statusDiv = document.getElementById('face_recognition_status_modal');
        if (statusDiv) {
            statusDiv.style.display = 'none';
        }
        
        // 모달 강제 닫기
        $('#modal_camera').modal('hide');
        
        // 모달 배경 제거 (혹시 남아있을 경우를 대비)
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
    }

    // 모달용 얼굴 인식 처리 함수
    async function processFaceRecognitionModal(imageBase64) {
        console.log('🔍 얼굴 인식 API 호출 시작...');
        
        // 얼굴 인식 상태 표시
        showFaceRecognitionStatusModal('processing', '얼굴 분석 중...');

        // Base64에서 data:image/jpeg;base64, 부분 제거
        let base64Data = imageBase64;
        if (base64Data.includes(',')) {
            base64Data = base64Data.split(',')[1];
        }
        console.log('Base64 데이터 크기:', base64Data.length);
        
        try {
            const response = await fetch('/FaceTest/recognize_for_registration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    image: base64Data
                })
            });
            
            const result = await response.json();
            console.log('API 응답 데이터:', result);
            
            if (result.suitable_for_registration) {
                // 얼굴 검출 성공
                showFaceRecognitionStatusModal('success', '얼굴이 정상적으로 인식되었습니다!');
                
                // 얼굴 데이터를 hidden 필드에 저장
                document.getElementById('face_encoding_data').value = JSON.stringify(result.face_data);
                document.getElementById('glasses_detected').value = result.face_data.glasses_detected ? '1' : '0';
                document.getElementById('quality_score').value = result.face_data.quality_score || 0.85;
                
                // 2초 후 모달 자동 닫기
                setTimeout(() => {
                    $('#modal_camera').modal('hide');
                    stopCameraModal();
                }, 2000);
                
            } else {
                 // 얼굴 검출 실패 - 상세한 안내 메시지 제공
                 let userMessage = '얼굴이 감지되지 않았습니다.';
                let guidance = [];
                // 품질 검증 실패 시 상세 권장사항 제공
                if (result.suitable_for_registration === false && result.recommendations) {
                    // API에서 제공하는 권장사항 사용
                    guidance = result.recommendations;
                } else if (result.face_data && result.face_data.quality_details) {
                    // 품질 상세 정보를 기반으로 안내
                    const details = result.face_data.quality_details;
                    
                    if (details.face_size_ratio < 0.15) {
                        guidance.push('• 얼굴이 너무 작습니다. 카메라에 더 가까이 와주세요.');
                    } else if (details.face_size_ratio > 0.7) {
                        guidance.push('• 얼굴이 너무 큽니다. 조금 뒤로 물러나주세요.');
                    }
                    
                    if (!details.face_centered) {
                        guidance.push('• 얼굴을 화면 중앙에 위치시켜주세요.');
                    }
                    
                    if (result.face_data.face_pose && !result.face_data.face_pose.is_frontal) {
                        const pose = result.face_data.face_pose;
                        if (Math.abs(pose.yaw) > 15) {
                            guidance.push('• 정면을 바라봐주세요. (좌우 각도 조정)');
                        }
                        if (Math.abs(pose.pitch) > 15) {
                            guidance.push('• 고개를 똑바로 들어주세요. (상하 각도 조정)');
                        }
                        if (Math.abs(pose.roll) > 10) {
                            guidance.push('• 머리를 똑바로 세워주세요. (기울기 조정)');
                        }
                    }
                    
                } else {
                    // 기본 안내 메시지
                    guidance = [
                        '• 카메라를 정면으로 바라봐주세요1.',
                        '• 조명이 밝은 곳에서 촬영해주세요.',
                        '• 얼굴이 화면 중앙에 오도록 조정해주세요.'
                    ];
                }
                
                // 최종 메시지 구성 (모바일용 HTML 포맷)
                if (guidance.length > 0) {
                    userMessage = '얼굴 인식 품질이 충분하지 않습니다.<br><br>' + 
                                    '<strong>개선 방법:</strong><br>' + 
                                    guidance.map(g => g.replace('•', '▫')).join('<br>');
                } else {
                    userMessage = response.error || '얼굴이 감지되지 않았습니다. 다시 촬영해주세요.';
                }
                
                
                // 재촬영 버튼 활성화를 위해 이미지 데이터 제거
               
                document.getElementById('face_encoding_data').value = '';
                document.getElementById('glasses_detected').value = '0';
                document.getElementById('quality_score').value = '0';
                // 얼굴 검출 실패
                showFaceRecognitionStatusModal('error', userMessage);
            }
            
        } catch (error) {
            console.error('API 호출 오류:', error);
            showFaceRecognitionStatusModal('error', '서버 연결에 실패했습니다.');
        }
    }

    function showFaceRecognitionStatusModal(type, message) {
        const statusDiv = document.getElementById('face_recognition_status_modal');
        const statusText = document.getElementById('face_status_text_modal');
        
        statusDiv.style.display = 'block';
        statusText.innerHTML = message;  // textContent를 innerHTML로 변경
        
        // 타입별 스타일 설정
        if (type === 'processing') {
            statusDiv.style.backgroundColor = '#e3f2fd';
            statusDiv.style.color = '#1976d2';
            statusDiv.style.border = '1px solid #bbdefb';
        } else if (type === 'success') {
            statusDiv.style.backgroundColor = '#e8f5e9';
            statusDiv.style.color = '#388e3c';
            statusDiv.style.border = '1px solid #c8e6c9';
        } else if (type === 'error') {
            statusDiv.style.backgroundColor = '#ffebee';
            statusDiv.style.color = '#d32f2f';
            statusDiv.style.border = '1px solid #ffcdd2';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal_mem_modify');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                stopCamera();
            });
        }
        
        // 카메라 모달 닫힐 때 카메라 종료
        const cameraModal = document.getElementById('modal_camera');
        if (cameraModal) {
            cameraModal.addEventListener('hidden.bs.modal', function() {
                stopCameraModal();
            });
        }
    });

    document.getElementById('input_trans_mem_search').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            search();
        }
    });

    $(function() {
        $('.select2').select2();
        
        // 메모 내용 초기화
        $('.memo-content[data-original-content]').each(function() {
            var originalContent = $(this).data('original-content');
            if (originalContent) {
                // white-space: pre-wrap으로 처리하므로 그대로 텍스트로 설정
                $(this).text(originalContent);
            }
        });
        
        // 드롭다운 메뉴가 화면 밖으로 나가지 않도록 위치 조정
        $('.dropdown-toggle').on('shown.bs.dropdown', function() {
            var $dropdown = $(this).next('.dropdown-menu');
            var dropdownOffset = $dropdown.offset();
            var dropdownHeight = $dropdown.outerHeight();
            var dropdownWidth = $dropdown.outerWidth();
            var windowHeight = $(window).height();
            var windowWidth = $(window).width();
            var scrollTop = $(window).scrollTop();
            
            // 화면 아래로 벗어날 경우 위쪽으로 표시
            if (dropdownOffset.top + dropdownHeight > windowHeight + scrollTop) {
                $dropdown.addClass('dropup');
            }
            
            // 화면 오른쪽으로 벗어날 경우 왼쪽으로 이동
            if (dropdownOffset.left + dropdownWidth > windowWidth) {
                $dropdown.addClass('dropdown-menu-right');
            }
        });
    })

    // 휴회일수 숫자입력 및 자동 계산
    $(document).on('input', '#domcy_acppt_i_cnt', function() {
        // 숫자가 아닌 문자 제거
        var value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
        
        // daycnt_calu_date 함수 호출
        if (value !== '') {
            daycnt_calu_date();
        }
    });

    // 회원정보 수정하기
    function mem_info_modify(mem_sno) {
        // 썸네일 이미지 초기화
        const img = document.getElementById('preview_mem_photo');
        const thumb = img.getAttribute('data-default-thumb');
        const main = img.getAttribute('data-default-main');

        img.src = thumb;
        img.setAttribute('onclick', `showFullPhoto('${main ? main.replace(/'/g, "\\'") : ""}')`);

        // hidden 필드 초기화
        document.getElementById('captured_photo').value = "";

        // 모달 열기
        $('#modal_mem_modify').modal("show");
    }

    // 회원정보 수정하기 처리
    function mem_modify() {
        if($('#mem_nm').val() == "" )
		{
			$('#mem_nm').focus();
			alertToast('error','지점관리자명을 입력하세요');
			return;
		}
		
		if( $('#mem_telno').val() == "" )
		{
			$('#mem_telno').focus();
			alertToast('error','지점관리자 전화번호를 입력하세요');
			return;
		} else if(!checkPhoneNumber($('#menu_mem_telno').val()))
		{
			$('#mem_telno').focus();
			alertToast('error','올바른 지점관리자 전화번호를 입력하세요');
			return;
		}


        var params = $("#form_mem_modify").serialize();
        debugger;
        jQuery.ajax({
            url: '/ttotalmain/ajax_mem_modify_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    // 얼굴 등록 정보가 있는 경우 처리
                    if (json_result['face_registration']) {
                        var faceReg = json_result['face_registration'];
                        if (faceReg['success']) {
                            // 보안 경고가 있는 경우 (라이브니스 실패 등)
                            if (faceReg['security_warnings'] && faceReg['security_warnings'].length > 0) {
                                alert('회원정보가 업데이트되고 얼굴 정보가 등록되었습니다.\n\n⚠️ 경고: ' + faceReg['security_warnings'].join('\n'));
                            } else {
                                alert('회원정보가 업데이트되고 얼굴 정보가 등록되었습니다.');
                            }
                        } else {
                            alert('회원정보는 업데이트되었으나, ' + faceReg['message']);
                        }
                    } else {
                        alert('회원정보가 업데이트되었습니다.');
                    }
                    location.reload();
                } else {
                    // result가 false인 경우 (라이브니스 실패 등)
                    var errorMsg = json_result['message'] || '회원정보 수정에 실패했습니다.';
                    alert(errorMsg);
                    
                    // 얼굴 등록 관련 상세 에러가 있는 경우
                    if (json_result['face_registration'] && json_result['face_registration']['security_warnings']) {
                        console.log('보안 경고:', json_result['face_registration']['security_warnings']);
                    }
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }


    // 상품 보내기
    function send_event(mem_sno) {
        location.href = "/teventmain/send_event2/" + mem_sno;
    }

    // 메모 등록하기
    function memo_insert(mem_sno) {
        $('#memo_mem_sno').val(mem_sno);
        $('#modal_memo_insert').modal("show");
    }

    // 메모 수정하기
    function memo_modify(memo_sno, memo_mem_sno, mem_sno) {
        $('#modify_memo_mgmt_sno').val(memo_sno);

        var params = "memo_mgmt_sno=" + memo_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_modify',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    $('#modal_memo_modify').modal("show");
                    json_result['memo_list'].forEach(function(r, index) {
                        $('#modify_memo_content').val(r['MEMO_CONTS']);
                    });
                } else {
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
            location.href = '/tlogin';
            return;
        });
    }

    function change_memo_prio_set(flag_yn, memo_sno) {
        var params = "prio_set=" + flag_yn + "&memo_mgmt_sno=" + memo_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_prio_set',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
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
            location.href = '/tlogin';
            return;
        });
    }

    function rn_br(word) {
        return word.replace(/(?:\r\n|\r|\n)/g, '<br />');
    }

    // 메모 더보기
    function memo_more(mem_sno) {
        $('#mem_memo_table > tbody > tr').remove();
        var params = "mem_sno=" + mem_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_more',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    $('#modal_mem_memo').modal("show");

                    json_result['memo_list'].forEach(function(r, index) {
                        var addTr = "<tr>";
                        if (r['PRIO_SET'] == 'Y') {
                            addTr += "<td class='memo-text-xs bg-info text-center'>[중요메모]</td>";
                        } else {
                            addTr += "<td class='memo-text-xs text-center'>[메모등록]</td>";
                        }
                        addTr += "<td class='memo-text-xs'>" + r['CRE_DATETM'] + "</td>";
                        addTr += "<td class='memo-text-xs' style='white-space: pre-wrap;'>" + r['MEMO_CONTS'] + "</td>";
                        //addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"top_buy_user_select('"+ r['MEM_SNO'] +"');\">선택</button></td>";
                        addTr += "</tr>";

                        $('#mem_memo_table > tbody:last').append(addTr);
                    });



                } else {
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
            location.href = '/tlogin';
            return;
        });

    }
    
    function more_pt_attd(mem_sno)
    {
        location.href = "/tsalesmain/month_class_sales_manage/" + mem_sno;
    }

    //출석 더보기
    function more_attd(mem_sno) {
        location.href = "/teventmem/mem_attd_manage/" + mem_sno;
    }

    function btn_memo_modify() {
        var params = $("#form_memo_modify").serialize();
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_modify_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    location.reload();
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    function btn_memo_insert() {
        var params = $("#form_mem_insert").serialize();
        jQuery.ajax({
            url: '/ttotalmain/ajax_memo_insert_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    location.reload();
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 휴회 신청하기 (모달 띄우기)
    function domcy_acppt() {
        // 휴회 가능한 회원권 정보를 수집
        possDomcyList = [];
        
        // event_list에서 휴회 가능한 회원권 정보 수집
        var mem_sno = $('#fc_domcy_mem_sno').val();
        
        jQuery.ajax({
            url: '/ttotalmain/ajax_get_domcy_list',
            type: 'POST',
            data: { mem_sno: mem_sno },
            dataType: 'json',
            success: function(data) {
                console.log('AJAX Response:', data);
                
                // 다양한 응답 형식 처리
                if (data.result === 'success' || data.result === 'true') {
                    possDomcyList = data.list || [];
                } else if (Array.isArray(data)) {
                    possDomcyList = data;
                } else if (data.list) {
                    possDomcyList = data.list;
                } else {
                    // 기본값으로 처리
                    possDomcyList = [];
                }
                    
                console.log('possDomcyList:', possDomcyList);
                    
                if (possDomcyList.length === 0) {
                        alertToast('error', '휴회 가능한 회원권이 없습니다.');
                        return;
                    }
                    
                    // datepicker 초기화
                    $('#domcy_acppt_i_sdate').datepicker('destroy');
                    $('#domcy_acppt_i_sdate').datepicker({
                        format: "yyyy-mm-dd",
                        autoclose: true,
                        language: "ko",
                        startDate: new Date(), // 오늘부터 선택 가능
                        todayHighlight: true
                    }).on('changeDate', function() {
                        // 날짜가 변경되면 휴회일수가 있을 때만 재계산
                        if ($('#domcy_acppt_i_cnt').val()) {
                            daycnt_calu_date();
                        }
                    });
                    
                    // 휴회시작일 클릭시 달력 표시
                    $('#domcy_acppt_i_sdate').off('click').on('click', function() {
                        $(this).datepicker('show');
                    });
                    
                    // 초기값 비우기 (사용자가 직접 선택하도록)
                    $('#domcy_acppt_i_sdate').val('');
                    $('#domcy_acppt_i_cnt').val('');
                    $('#domcy_acppt_e_sdate').val('');
                    
                    // 모달 열기
                    $('#modal_pop_domcy').modal("show");
                    
                    // a-list 초기화 (비워둠)
                    emptyElement('a-list');
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                console.log('Using eventListData instead');
                
                // 에러시 possDomcyData에서 휴회 가능한 회원권 정보 사용
                possDomcyList = [];
                
                console.log('Using possDomcyData:', possDomcyData);
                
                // possDomcyData.list가 있으면 사용
                if (possDomcyData && possDomcyData.list && Array.isArray(possDomcyData.list)) {
                    possDomcyList = possDomcyData.list;
                    console.log('possDomcyList from possDomcyData:', possDomcyList);
                } else {
                    // 폴백으로 eventListData 사용
                    console.log('possDomcyData.list not found, trying eventListData');
                    console.log('Full eventListData:', eventListData);
                    
                    if (eventListData && eventListData[1]) {
                        console.log('Number of items in eventListData[1]:', eventListData[1].length);
                        
                        eventListData[1].forEach(function(item, index) {
                            console.log(`Item ${index}:`, item);
                            // 휴회 가능한 회원권만 추가
                            if (item.CLS_DOMCY_YN === 'Y' || item.USE_DOMCY_YN === 'Y') {
                                const domcyDay = parseInt(item.DOMCY_DAY) || 0;
                                const domcyCnt = parseInt(item.DOMCY_CNT) || 0;
                                
                                if (domcyDay > 0 && domcyCnt > 0) {
                                    possDomcyList.push({
                                        buy_event_sno: item.BUY_EVENT_SNO,
                                        sell_event_nm: item.SELL_EVENT_NM,
                                        event_name: item.SELL_EVENT_NM,
                                        buy_prod_name: item.SELL_EVENT_NM,
                                        s_date: item.USE_SDATE,
                                        e_date: item.USE_EDATE,
                                        day: domcyDay,
                                        cnt: domcyCnt
                                    });
                                }
                            }
                        });
                    }
                }
                
                console.log('Collected possDomcyList from eventListData:', possDomcyList);
                
                if (possDomcyList.length === 0) {
                    alertToast('error', '휴회 가능한 회원권이 없습니다.');
                    return;
                }
                
                $('#domcy_acppt_i_sdate').datepicker('destroy');
                $('#domcy_acppt_i_sdate').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    language: "ko",
                    startDate: new Date(), // 오늘부터 선택 가능
                    todayHighlight: true
                }).on('changeDate', function() {
                    // 날짜가 변경되면 휴회일수가 있을 때만 재계산
                    if ($('#domcy_acppt_i_cnt').val()) {
                        daycnt_calu_date();
                    }
                });
                
                $('#domcy_acppt_day').val($('#fc_domcy_day').val());
                $('#domcy_acppt_cnt').val($('#fc_domcy_cnt').val());
                
                // 초기값 비우기 (사용자가 직접 선택하도록)
                $('#domcy_acppt_i_sdate').val('');
                $('#domcy_acppt_i_cnt').val('');
                $('#domcy_acppt_e_sdate').val('');
                
                // 모달 열기
                $('#modal_pop_domcy').modal("show");
                
                // a-list 초기화 (비워둠)
                emptyElement('a-list');
            }
        });
    }

    // 휴회 신청 처리 - 새로운 방식
    function submitDomcyApplication() {
        // 먼저 휴회 시작일과 휴회일수가 입력되었는지 확인
        const startDate = $('#domcy_acppt_i_sdate').val();
        const days = $('#domcy_acppt_i_cnt').val();
        
        if (!startDate) {
            alertToast('error', '휴회 시작일을 선택해주세요.');
            return;
        }
        
        if (!days || parseInt(days) < 1) {
            alertToast('error', '휴회일수를 입력해주세요. (1일 이상)');
            return;
        }
        
        const aList = document.getElementById("a-list");
        const items = aList.querySelectorAll(".dormancy-item");
        const selectedItems = [];
        
        if (items.length === 0) {
            alertToast('error', '휴회 가능한 회원권이 없습니다. 휴회 시작일과 휴회일수를 확인해주세요.');
            return;
        }
        
        items.forEach(item => {
            const checkbox = item.querySelector(".dormancy-checkbox");
            if (checkbox && checkbox.checked) {
                const startDateInput = item.querySelector(".domcy-start-date");
                const endDateInput = item.querySelector(".domcy-end-date");
                const daysInput = item.querySelector(".use-days"); // 클래스명 수정
                
                // 날짜가 모두 입력되었는지 확인
                if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
                    const buyEventSno = item.dataset.buyEventSno;
                    console.log('buy_event_sno:', buyEventSno); // 디버깅용
                    
                    selectedItems.push({
                        buy_event_sno: buyEventSno,
                        start_date: startDateInput.value,
                        end_date: endDateInput.value,
                        use_days: daysInput ? daysInput.value : '1'
                    });
                }
            }
        });
        
        if (selectedItems.length === 0) {
            alertToast('error', '휴회 신청할 항목을 선택해주세요.');
            return;
        }
        
        // 신청 확인
        const totalDays = selectedItems.reduce((sum, item) => sum + parseInt(item.use_days || 0), 0);
        
        // SweetAlert2로 확인 다이얼로그 표시
        Swal.fire({
            title: '휴회 신청 확인',
            html: `<p>총 <strong>${totalDays}일</strong>의 휴회를 신청하시겠습니까?</p>
                   <p class="text-muted small">선택하신 ${selectedItems.length}개의 회원권에 대해 휴회가 적용됩니다.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '신청하기',
            cancelButtonText: '취소',
            footer: 'Argos SpoQ'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }
            
            // 신청 처리 계속 진행
            proceedWithDomcyApplication(selectedItems, totalDays);
        });
    }
    
    // 실제 휴회 신청 처리 함수 (분리)
    function proceedWithDomcyApplication(selectedItems, totalDays) {
        // API 형식에 맞춰 items 배열로 전송
        const mem_sno = $('#fc_domcy_mem_sno').val();
        // API가 기대하는 형식으로 데이터 구성
        const apiData = {
            items: selectedItems.map(item => ({
                fc_domcy_mem_sno: mem_sno,
                fc_domcy_buy_sno: item.buy_event_sno,
                fc_domcy_s_date: item.start_date,
                fc_domcy_use_day: item.use_days
            }))
        };
        
        // 디버깅용 로그
        console.log('선택된 항목들:', selectedItems);
        console.log('전송할 데이터:', apiData);
        
        // 로딩 표시
        alertToast('info', '휴회 신청을 처리중입니다...');
        
        // jQuery ajax 요청 - 교사 전용 메서드 사용
        jQuery.ajax({
            url: '/ttotalmain/ajax_domcy_acppt_items_proc',
            type: 'POST',
            data: apiData,  // 객체 그대로 전송 (jQuery가 자동으로 serialize)
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',  // text로 받아서 수동 파싱
            success: function(result) {
                console.log('API 응답:', result);
                
                // 로그인 만료 체크
                if (result.substr(0,8) == '<script>') {
                    alertToast('error', '로그인이 만료 되었습니다. 다시 로그인해주세요');
                    setTimeout(function() {
                        location.href='/login';
                    }, 1500);
                    return;
                }
                
                // JSON 파싱
                let json_result;
                try {
                    json_result = $.parseJSON(result);
                } catch (e) {
                    console.error('JSON 파싱 오류:', e);
                    alertToast('error', '응답 처리 중 오류가 발생했습니다.');
                    return;
                }
                
                console.log('파싱된 결과:', json_result);
                
                // 결과 처리
                if (json_result['result'] == 'true') {
                    // 성공 메시지를 토스트로 표시
                    alertToast('success', json_result['msg'] || '휴회신청이 완료되었습니다.');
                    
                    // 모달 닫기
                    $('#modal_pop_domcy').modal('hide');
                    
                    // 약간의 지연 후 페이지 새로고침
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    // 오류 메시지를 토스트로 표시
                    alertToast('error', json_result['msg'] || '휴회신청을 처리할 수 없습니다.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX 오류:', error);
                alertToast('error', '휴회신청 처리 중 오류가 발생했습니다.');
            }
        });
    }
    
    // 기존 방식 유지 (폴백용)
    function btn_domcy_acppt_submit() {
        $('#fc_domcy_s_date').val($('#domcy_acppt_i_sdate').val());
        $('#fc_domcy_use_day').val($('#domcy_acppt_i_cnt').val());

        $('#form_domcy').submit();
    }
    
    // Helper 함수들 추가
    function cloneChildren(parentId) {
        const parent = document.getElementById(parentId);
        if (!parent) {
            console.error(`ID가 ${parentId}인 요소를 찾을 수 없습니다.`);
            return [];
        }
        
        return Array.from(parent.children).map(child => child.cloneNode(true));
    }
    
    function addDateDays(dateStr, days) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return '';
        
        date.setDate(date.getDate() + days);
        return formatDateLocal(date);
    }
    
    function formatDateLocal(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    function getDateDiff(start, end) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    }
    
    let possDomcyList = [];
    
    // 날짜 변경시 다른 회원권들의 날짜 재배치
    function redistributeDates(changedItem, changedIndex) {
        const aList = document.getElementById("a-list");
        if (!aList) return;
        
        const items = aList.querySelectorAll(".dormancy-item");
        let previousEndDate = null;
        
        // 변경된 항목 이전의 마지막 종료일 찾기
        for (let i = 0; i < changedIndex; i++) {
            const item = items[i];
            const checkbox = item.querySelector(".dormancy-checkbox");
            const endInput = item.querySelector(".item-end-date");
            
            if (checkbox && checkbox.checked && endInput && endInput.value) {
                previousEndDate = new Date(endInput.value);
            }
        }
        
        // 변경된 항목부터 시작해서 연속적으로 날짜 재배치
        let currentStartDate = previousEndDate ? new Date(previousEndDate) : null;
        
        for (let i = changedIndex; i < items.length; i++) {
            const item = items[i];
            const checkbox = item.querySelector(".dormancy-checkbox");
            const startInput = item.querySelector(".item-start-date");
            const endInput = item.querySelector(".item-end-date");
            const daysInput = item.querySelector(".dormancy-days");
            
            if (!checkbox || !checkbox.checked) continue;
            
            if (i === changedIndex) {
                // 변경된 항목인 경우
                if (currentStartDate && startInput.value) {
                    // 이전 항목이 있으면 그 다음날부터 시작
                    currentStartDate.setDate(currentStartDate.getDate() + 1);
                    const changedStart = new Date(startInput.value);
                    
                    // 변경된 시작일이 이전 종료일 다음날보다 늦으면 그대로 사용
                    if (changedStart > currentStartDate) {
                        currentStartDate = changedStart;
                    } else {
                        // 그렇지 않으면 연속적으로 배치
                        startInput.value = formatDateLocal(currentStartDate);
                        $(startInput).datepicker('update', currentStartDate);
                    }
                }
                
                // 현재 항목의 종료일을 다음 시작 기준으로 설정
                if (endInput.value) {
                    currentStartDate = new Date(endInput.value);
                }
            } else {
                // 다음 항목들인 경우
                if (currentStartDate) {
                    currentStartDate.setDate(currentStartDate.getDate() + 1);
                    
                    // 회원권 이용 가능 기간 확인
                    const itemData = possDomcyList[i];
                    if (itemData) {
                        const memberStartLimit = new Date(itemData.s_date);
                        const memberEndLimit = new Date(itemData.e_date);
                        
                        // 시작일이 회원권 이용 가능 기간 내에 있는지 확인
                        if (currentStartDate < memberStartLimit) {
                            currentStartDate = new Date(memberStartLimit);
                        }
                        
                        startInput.value = formatDateLocal(currentStartDate);
                        $(startInput).datepicker('update', currentStartDate);
                        
                        // 종료일 계산
                        const days = parseInt(daysInput.value) || 1;
                        let endDate = new Date(currentStartDate);
                        endDate.setDate(endDate.getDate() + days - 1);
                        
                        // 종료일이 회원권 이용 가능 기간을 초과하면 조정
                        if (endDate > memberEndLimit) {
                            endDate = memberEndLimit;
                            // 일수 재계산
                            const adjustedDays = Math.floor((endDate - currentStartDate) / (1000 * 60 * 60 * 24)) + 1;
                            daysInput.value = adjustedDays;
                        }
                        
                        endInput.value = formatDateLocal(endDate);
                        $(endInput).datepicker('update', endDate);
                        
                        currentStartDate = endDate;
                    }
                }
            }
        }
    }
    
    // 하단 리스트의 날짜 변경시 남은 정보만 업데이트 (상단 필드는 건드리지 않음)
    function updateTotalDays() {
        // 이 함수는 하단 리스트의 남은 정보를 업데이트하는 용도로만 사용
        // 상단 휴회일수와 종료일은 사용자가 직접 입력한 값을 유지
        
        // 필요한 경우 여기에 추가 로직 구현
        // 현재는 아무 작업도 하지 않음
        return;
    }
    
    // Helper function to empty element
    function emptyElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = '';
        }
    }
    
    // 휴회 자동 할당 함수 (병렬 할당 방식 - new_domcy2.php 방식)
    function autoAssignDomcy(startDateStr, wantDays) {
        console.log('=== autoAssignDomcy called (병렬 할당 방식) ===');
        console.log('startDateStr:', startDateStr);
        console.log('wantDays:', wantDays);
        
        const aList = document.getElementById("a-list");
        if (!aList) {
            console.error('a-list element not found!');
            return;
        }
        
        // 기존 항목 모두 제거
        emptyElement('a-list');
        
        // 날짜나 일수가 입력되지 않았으면 리턴
        if (!startDateStr || !wantDays || parseInt(wantDays) <= 0) {
            console.log('날짜 또는 일수가 올바르지 않음');
            return;
        }
        
        wantDays = parseInt(wantDays) || 0;
        
        // possDomcyData 확인
        if (!possDomcyData || !possDomcyData.list || possDomcyData.list.length === 0) {
            console.log('휴회 가능한 회원권이 없습니다');
            alertToast('error', '휴회 가능한 회원권이 없습니다');
            return;
        }
        
        const localDomcyList = JSON.parse(JSON.stringify(possDomcyData.list));
        console.log('사용 가능한 회원권:', localDomcyList);
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        let currentDate = new Date(startDateStr);
        currentDate.setHours(0, 0, 0, 0);
        
        // 선택된 날짜 범위 계산
        const endDate = new Date(currentDate);
        endDate.setDate(currentDate.getDate() + wantDays - 1);
        
        // 선택된 날짜 범위에 유효한 회원권만 필터링
        const validMemberships = localDomcyList.filter(domcy => {
            const memberStart = new Date(domcy.s_date);
            const memberEnd = new Date(domcy.e_date);
            memberStart.setHours(0, 0, 0, 0);
            memberEnd.setHours(0, 0, 0, 0);
            
            // 회원권이 선택된 날짜 범위와 겹치는지 확인
            const hasOverlap = memberStart <= endDate && memberEnd >= currentDate;
            
            // 회원권에 사용 가능한 휴회일이 있는지 확인
            const dayValue = (domcy.day !== undefined && domcy.day !== null) ? parseInt(domcy.day) : 0;
            const cntValue = (domcy.cnt !== undefined && domcy.cnt !== null) ? parseInt(domcy.cnt) : 0;
            const hasAvailableDays = dayValue > 0 && cntValue > 0;
            
            return hasOverlap && hasAvailableDays;
        });
        
        console.log('유효한 회원권:', validMemberships);
        
        if (validMemberships.length === 0) {
            alertToast('error', '선택한 날짜에 사용 가능한 회원권이 없습니다');
            return;
        }
        
        // 각 회원권별로 사용할 날짜 계산 (병렬 할당)
        const assignedDates = [];
        const eventUsage = {};
        
        // 각 날짜별로 사용 가능한 모든 회원권 찾기
        for (let i = 0; i < wantDays; i++) {
            const checkDate = new Date(currentDate);
            checkDate.setDate(currentDate.getDate() + i);
            const dateStr = formatDateLocal(checkDate);
            
            // 이 날짜에 사용 가능한 모든 회원권 확인
            validMemberships.forEach(domcy => {
                const memberStart = new Date(domcy.s_date);
                const memberEnd = new Date(domcy.e_date);
                memberStart.setHours(0, 0, 0, 0);
                memberEnd.setHours(0, 0, 0, 0);
                
                const dayValue = (domcy.day !== undefined && domcy.day !== null) ? parseInt(domcy.day) : 0;
                if (checkDate >= memberStart && checkDate <= memberEnd && dayValue > 0) {
                    assignedDates.push({
                        date: dateStr,
                        buy_event_sno: domcy.buy_event_sno
                    });
                }
            });
        }
        
        // 회원권별로 날짜 그룹화
        assignedDates.forEach(item => {
            if (!eventUsage[item.buy_event_sno]) {
                eventUsage[item.buy_event_sno] = [];
            }
            eventUsage[item.buy_event_sno].push(item.date);
        });
        
        // 각 회원권별로 UI 생성
        Object.entries(eventUsage).forEach(([eventSno, dates], index) => {
            dates.sort();
            const startDateValue = dates[0];
            const endDateValue = dates[dates.length - 1];
            const useDays = Math.min(dates.length, wantDays);
            
            const domcyInfo = validMemberships.find(d => d.buy_event_sno === eventSno);
            if (!domcyInfo) return;
            
            // domcy.day가 undefined인 경우 처리
            const maxDomcyDays = (domcyInfo.day !== undefined && domcyInfo.day !== null) ? parseInt(domcyInfo.day) : 0;
            
            // 실제 사용할 수 있는 일수로 제한
            const actualUseDays = Math.min(useDays, maxDomcyDays);
            
            // 최대 휴회일이 0인 경우 건너뛰기
            if (maxDomcyDays === 0) {
                return;
            }
            
            // dormancy-item 생성
            const itemDiv = document.createElement("div");
            itemDiv.className = "dormancy-item";
            itemDiv.dataset.buyEventSno = domcyInfo.buy_event_sno; // buy_event_sno 추가
            
            // 헤더 (체크박스와 회원권명)
            const headerDiv = document.createElement("div");
            headerDiv.className = "dormancy-item-header";
            
            const checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.id = `item_check_${index}`;
            checkbox.className = "dormancy-checkbox";
            checkbox.checked = true;
            
            const titleStrong = document.createElement("strong");
            titleStrong.className = "dormancy-item-title";
            titleStrong.textContent = domcyInfo.sell_event_nm;
            
            headerDiv.appendChild(checkbox);
            headerDiv.appendChild(titleStrong);
            
            // 날짜 그룹
            const dateGroupDiv = document.createElement("div");
            dateGroupDiv.className = "dormancy-date-group";
            
            // 시작일 필드
            const startFieldDiv = document.createElement("div");
            startFieldDiv.className = "dormancy-date-field";
            
            const startLabel = document.createElement("label");
            startLabel.textContent = "시작:";
            
            const startDateInput = document.createElement("input");
            startDateInput.type = "text";
            startDateInput.className = "form-control form-control-sm domcy-start-date";
            startDateInput.value = startDateValue;
            startDateInput.readOnly = true;
            startDateInput.dataset.index = index;
            
            startFieldDiv.appendChild(startLabel);
            startFieldDiv.appendChild(startDateInput);
            
            // 종료일 필드
            const endFieldDiv = document.createElement("div");
            endFieldDiv.className = "dormancy-date-field";
            
            const endLabel = document.createElement("label");
            endLabel.textContent = "종료:";
            
            const endDateInput = document.createElement("input");
            endDateInput.type = "text";
            endDateInput.className = "form-control form-control-sm domcy-end-date";
            endDateInput.value = endDateValue;
            endDateInput.readOnly = true;
            endDateInput.dataset.index = index;
            
            endFieldDiv.appendChild(endLabel);
            endFieldDiv.appendChild(endDateInput);
            
            // 사용일수 필드
            const daysFieldDiv = document.createElement("div");
            daysFieldDiv.className = "dormancy-date-field";
            
            const daysLabel = document.createElement("label");
            daysLabel.textContent = "사용일수:";
            
            const daysInput = document.createElement("input");
            daysInput.type = "text";
            daysInput.className = "form-control form-control-sm use-days";
            daysInput.value = actualUseDays;
            daysInput.dataset.index = index;
            daysInput.dataset.prevValue = actualUseDays;
            daysInput.dataset.maxDays = maxDomcyDays;
            daysInput.dataset.maxCnt = domcyInfo.cnt || 1;
            daysInput.dataset.memberStart = domcyInfo.s_date;
            daysInput.dataset.memberEnd = domcyInfo.e_date;
            
            const daysSpan = document.createElement("span");
            daysSpan.className = "text-muted";
            daysSpan.textContent = "일";
            
            daysFieldDiv.appendChild(daysLabel);
            daysFieldDiv.appendChild(daysInput);
            daysFieldDiv.appendChild(daysSpan);
            
            // 날짜 그룹에 필드들 추가
            dateGroupDiv.appendChild(startFieldDiv);
            dateGroupDiv.appendChild(endFieldDiv);
            dateGroupDiv.appendChild(daysFieldDiv);
            
            // 남은 정보
            const infoDiv = document.createElement("div");
            infoDiv.className = "info-text";
            infoDiv.innerHTML = `남은 휴회일: <strong>${maxDomcyDays - actualUseDays}일</strong>, 남은 휴회횟수: <strong>${(domcyInfo.cnt || 1) - 1}회</strong> (최대 ${maxDomcyDays}일 사용 가능)`;
            
            // hidden fields
            const hiddenSno = document.createElement("input");
            hiddenSno.type = "hidden";
            hiddenSno.className = "buy-event-sno";
            hiddenSno.value = domcyInfo.buy_event_sno;
            
            // 아이템에 모든 요소 추가
            itemDiv.appendChild(headerDiv);
            itemDiv.appendChild(dateGroupDiv);
            itemDiv.appendChild(infoDiv);
            itemDiv.appendChild(hiddenSno);
            
            aList.appendChild(itemDiv);
        });
        
        // 전체 일수 업데이트
        updateTotalDays();
    }

    function test_clas_chk(stchr_id, buy_sno) {
        if (stchr_id == '') {
            alertToast('error', "수업강사를 먼저 배정 해야 합니다.");
            return;
        }

        var params = "stchr_id=" + stchr_id + "&buy_sno=" + buy_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_clas_chk',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    alert(json_result['msg']);
                    location.reload();
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });

        //location.href="/ttotalmain/ajax_clas_chk/"+stchr_id+"/"+buy_sno;
    }

    function trans_tuser_select(tmem_sno) {
        $('#fc_trans_tmem_sno').val(tmem_sno);

        $('#form_trans').submit();
    }

    // 양도하기 모달 띄우기
    function change_event_trans(mem_sno, buy_sno) {
        $('#modal_trans_mem_search_form').modal("show");
        $('#fc_trans_buy_sno').val(buy_sno);
        $('#fc_trans_mem_sno').val(mem_sno);
    }

    // 양도하기 양수자 검색

    $('#btn_trans_mem_search').click(function() {

        search();
    })


    function search() {
        $('#trans_mem_search_table > tbody > tr').remove();
        var params = "sv=" + $('#input_trans_mem_search').val();
        jQuery.ajax({
            url: '/ttotalmain/top_search_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                if (json_result['result'] == 'true') {
                    if (json_result['search_mem_list'].length == 0) {
                        alertToast('error', '검색된 정보가 없습니다.');
                        return;
                    }

                    /*
                    if (json_result['search_mem_list'].length == 1)
                    {
                    	top_buy_user_select(json_result['search_mem_list'][0]['MEM_SNO']);
                    	console.log(json_result['search_mem_list'][0]);
                    	return;
                    }
                    */

                    json_result['search_mem_list'].forEach(function(r, index) {
                        var addTr = "<tr>";
                        addTr += "<td>" + r['MEM_STAT_NM'] + "</td>";
                        addTr += "<td>" + r['MEM_NM'] + "</td>";
                        addTr += "<td>" + r['MEM_ID'] + "</td>";
                        addTr += "<td>" + r['MEM_TELNO'] + "</td>";
                        addTr += "<td>" + r['BTHDAY'] + "</td>";
                        addTr += "<td>" + r['MEM_GENDR_NM'] + "</td>";
                        addTr += "<td><button type=\"button\" class=\"btn btn-info btn-xs ac-btn\" onclick=\"trans_tuser_select('" + r['MEM_SNO'] + "');\">선택</button></td>";
                        addTr += "</tr>";

                        $('#trans_mem_search_table > tbody:last').append(addTr);
                    });

                } else {
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
            location.href = '/tlogin';
            return;
        });
    }

    // PT, Golf PT 예약중인 상품을 운동 시작일로 변경하기
    function pt_use(stchr_id, buy_sno) {
        if (stchr_id == '') {
            alertToast('error', "수업강사를 먼저 배정 해야 합니다.");
            return;
        }
        //location.href="/ttotalmain/ajax_pt_use/"+stchr_id+"/"+buy_sno;

        ToastConfirm.fire({
            icon: "question",
            title: "  확인 메세지",
            html: "<font color='#000000' >이용시작을 하시겠습니까?</font>",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonColor: "#28a745",
        }).then((result) => {
            if (result.isConfirmed) {
                // 성공일 경우	
                var params = "buy_sno=" + buy_sno;
                jQuery.ajax({
                    url: '/ttotalmain/ajax_pt_use',
                    type: 'POST',
                    data: params,
                    async: false,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: 'text',
                    success: function(result) {
                        if (result.substr(0, 8) == '<script>') {
                            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                            location.href = '/tlogin';
                            return;
                        }

                        json_result = $.parseJSON(result);
                        console.log(json_result);
                        if (json_result['result'] == 'true') {
                            alert('해당 상품의 이용이 시작 되었습니다.');
                            location.reload();
                        }
                    }
                }).done((res) => {
                    // 통신 성공시
                    console.log('통신성공');
                }).fail((error) => {
                    // 통신 실패시
                    console.log('통신실패');
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                    location.href = '/tlogin';
                    return;
                });
            }
        });

    }

    function direct_attd(mem_sno) {
        //location.href="/ttotalmain/ajax_direct_attd/"+mem_sno;
        //return;
        var params = "mem_sno=" + mem_sno;
        jQuery.ajax({
            url: '/ttotalmain/ajax_direct_attd',
            type: 'POST',
            data: params,
            async: false,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    alert(json_result['msg']);
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }


    function change_event_refund(mem_sno, buy_sno, event_stat) {
        location.href = "/ttotalmain/refund_info/" + mem_sno + "/" + buy_sno;
    }

    function misu_select(mem_sno, buy_sno) {
        location.href = "/teventmain/misu_manage_info/" + mem_sno + "/" + buy_sno;
    }

    // 상품 구매하기
    function info_mem_buy_event(mem_sno) {
        location.href = "/teventmain/event_buy2/" + mem_sno;
    }

    // 라커 선택하기
    function lockr_select(mem_sno, buy_sno, lockr_knd, gendr) {
        location.href = "/ttotalmain/nlockr_select/" + mem_sno + "/" + buy_sno + "/" + lockr_knd + "/" + gendr;
    }

    // 운동 시작일 변경
    function change_exr_s_date(buy_sno, event_stat, m_cate) {
        if (m_cate == 'GRP' || m_cate == 'PRV') {
            alertToast('error', '수업상품의 운동 시작일은 변경이 불가능 합니다. 이용시작 버튼을 이용해주세요');
            return;
        }

        if (event_stat == "01") {
            $('#pop_sdate_change_sdate').datepicker('destroy');

            $('#modal_pop_sdate').modal("show");
            $('#pop_sdate_buy_sno').val(buy_sno);
            $('#pop_sdate_info_sdate').val($.trim($('#exr_s_date_' + buy_sno).text())); // 현재 운동시작일
            $('#pop_sdate_change_sdate').val($('#exr_s_date_' + buy_sno).text()); // 변경할 운동시작일

            $('#pop_sdate_change_sdate').datepicker({
                autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                language: "ko" //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
            });
        } else {
            alertToast('error', '이용중이거나 종료된 상품은 시작일을 변경할 수 없습니다.');
        }
    }

    // 운동 시작일 변경 처리
    function btn_change_sdate_submit() {
        var fc_sdate_buy_sno = $('#pop_sdate_buy_sno').val();
        var fc_sdate_sdate = $('#pop_sdate_change_sdate').val();

        // ### test
        // $('#fc_sdate_buy_sno').val(fc_sdate_buy_sno);
        // $('#fc_sdate_sdate').val(fc_sdate_sdate);
        // $('#form_change_sdate').submit();
        // return;
        var params = "fc_sdate_buy_sno=" + fc_sdate_buy_sno + "&fc_sdate_sdate=" + fc_sdate_sdate;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_change_sdate_proc',
            type: 'POST',
            data: params,
            async: false,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 운동 종료일 변경
    function change_exr_e_date(buy_sno, event_stat, m_cate) {

        if (m_cate == 'GRP') {
          //alertToast('error', '수업상품의 운동 종료일은 변경 할 수 없습니다. 강제 종료기능을 이용해주세요.');
            alertToast('error', 'GX상품의 운동 종료일은 변경 할 수 없습니다. 강제 종료기능을 이용해주세요.');
            return;
        }

        if ($('#exr_e_date_' + buy_sno).text() == '') {
            alertToast('error', '운동종료일이 없는 상품은 운동 종료일을 변경 할 수 없습니다.');
            return;
        }

        if (event_stat != "99") {
            $('#pop_edate_change_edate').datepicker('destroy');

            $('#modal_pop_edate').modal("show");
            $('#pop_edate_buy_sno').val(buy_sno);
            $('#pop_edate_info_edate').val($('#exr_e_date_' + buy_sno).text()); // 현재 운동시작일
            $('#pop_edate_change_edate').val($('#exr_e_date_' + buy_sno).text()); // 변경할 운동시작일
            $("#pop_sell_event_nm").val($("#sell_event_nm_" + buy_sno).val());
            $('#pop_edate_change_add_edate_cnt').val(0);

            $('#pop_edate_change_edate').datepicker({
                autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
                language: "ko", //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
            });
        } else {
            alertToast('error', '종료된 상품은 종료일을 변경할 수 없습니다.');
        }
    }

    function edate_calu_days() {
        var sDate = $('#pop_edate_info_edate').val();
        var eDate = $('#pop_edate_change_edate').val();

        const getDateDiff = (d1, d2) => {
            const date1 = new Date(d1);
            const date2 = new Date(d2);

            const diffDate = date2.getTime() - date1.getTime();

            return (diffDate / (1000 * 60 * 60 * 24)); // 밀리세컨 * 초 * 분 * 시 = 일
        }

        var day_cnt = getDateDiff(sDate, eDate);
        $('#pop_edate_change_add_edate_cnt').val(day_cnt);
    }

    function daycnt_calu_date() {
        var sDate = $('#domcy_acppt_i_sdate').val();
        var addDay = $('#domcy_acppt_i_cnt').val();
        
        if (sDate == '') {
            // 시작일이 없으면 리스트만 초기화하고 입력값은 유지
            emptyElement('a-list');
            $('#domcy_acppt_e_sdate').val('');
            return;
        }

        console.log('daycnt_calu_date - addDay:', addDay);
        
        if (addDay && Number(addDay) > 0) {
            // 종료일 계산 (시작일 + 사용일수 - 1)
            var result = new Date(sDate);
            result.setDate(result.getDate() + Number(addDay) - 1);

            var date_y = result.getFullYear();
            var date_m = result.getMonth() + 1;
            var date_d = result.getDate();

            var result_date = date_y + "-" + (("00" + date_m.toString()).slice(-2)) + "-" + (("00" + date_d.toString()).slice(-2));

            $('#domcy_acppt_e_sdate').val(result_date);
            
            // 휴회 항목 자동 할당 호출
            console.log('Calling autoAssignDomcy from daycnt_calu_date');
            autoAssignDomcy(sDate, addDay);
        } else {
            // 일수가 비어있거나 0이면 리스트 초기화
            $('#domcy_acppt_e_sdate').val('');
            emptyElement('a-list');
        }
    }


    // 운동 종료일 변경 처리
    function btn_change_edate_submit() {
        var fc_edate_buy_sno = $('#pop_edate_buy_sno').val();
        var fc_edate_edate = $('#pop_edate_change_edate').val();

        // ### test
        // $('#fc_edate_buy_sno').val(fc_edate_buy_sno);
        // $('#fc_edate_edate').val(fc_edate_edate);
        // $('#form_change_edate').submit();
        // return;

        var params = "fc_edate_buy_sno=" + fc_edate_buy_sno + "&fc_edate_edate=" + fc_edate_edate;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_change_edate_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 수업강사 변경
    function change_stchr(buy_sno, event_stat, mem_id, clas_dv) {

        if (event_stat == '99') {
            alertToast('error', '종료된 상품은 수업강사를 변경 할 수 없습니다.');
            return;
        }

        if (clas_dv == '21' || clas_dv == '22') {
            $('#ch_stchr_id').val(mem_id).trigger('change');
            $('#stchr_buy_sno').val(buy_sno);
            $('#modal_pop_stchr').modal("show");
        } else {
            alertToast('error', '수업상품이 아닌 상품은 수업강사를 지정 할 수 없습니다.');
            return;
        }

    }

    // 수업강사 변경 처리
    function btn_stchr_submit() {
        var fc_stchr_buy_sno = $('#stchr_buy_sno').val();
        var fc_ch_stchr_id = $('#ch_stchr_id').val();

        // ### test
        // $('#fc_stchr_buy_sno').val(fc_stchr_buy_sno);
        // $('#fc_ch_stchr_id').val(fc_ch_stchr_id);
        // $('#form_stchr').submit();
        // return;

        var params = "fc_stchr_buy_sno=" + fc_stchr_buy_sno + "&fc_ch_stchr_id=" + fc_ch_stchr_id;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_stchr_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 판매강사 변경
    function change_ptchr(buy_sno, event_stat, mem_id, buy_date) {
        if (event_stat == '99') {
            alertToast('error', '종료된 상품의 판매강사는 변경이 불가능 합니다.');
            return;
        }

        // 오늘날짜 구하기
        var now_date = new Date();
        var disp_now_date = now_date.getFullYear() + "-" + ("0" + (now_date.getMonth() + 1)).slice(-2) + "-" + ("0" + now_date.getDate()).slice(-2);

        if (buy_date != disp_now_date) {
            alertToast('error', '판매강사 변경은 당일만 가능합니다.');
            return;
        }

        $('#ch_ptchr_id').val(mem_id).trigger('change');
        $('#ptchr_buy_sno').val(buy_sno);
        $('#modal_pop_ptchr').modal("show");
    }

    // 판매강사 변경 처리
    function btn_ptchr_submit() {
        var fc_ptchr_buy_sno = $('#ptchr_buy_sno').val();
        var fc_ch_ptchr_id = $('#ch_ptchr_id').val();

        // ### test
        // $('#fc_ptchr_buy_sno').val(fc_ptchr_buy_sno);
        // $('#fc_ch_ptchr_id').val(fc_ch_ptchr_id);
        // $('#form_ptchr').submit();
        // return;

        var params = "fc_ptchr_buy_sno=" + fc_ptchr_buy_sno + "&fc_ch_ptchr_id=" + fc_ch_ptchr_id;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_ptchr_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }

    // 휴회일 추가
    function change_domcy_day(buy_sno, event_stat, domcy_yn) {
        if (event_stat == "99") {
            alertToast('error', '종료된 상품은 휴회일 추가를 할 수 없습니다.');
            return;
        }

        if (domcy_yn == "N") {
            alertToast('error', '해당 상품은 휴회이 불가능한 상품입니다.');
            return;
        }

        $('#domcy_day_buy_sno').val(buy_sno);
        $('#modal_pop_domcy_day').modal("show");
    }

    // 휴회일 추가 처리
    function btn_domcy_day_submit() {
        var domcy_day_buy_sno = $('#domcy_day_buy_sno').val();
        var domcy_day_day = $('#domcy_day_day').val();

        // test
        // $('#fc_domcy_day_buy_sno').val(domcy_day_buy_sno);
        // $('#fc_domcy_day_day').val(domcy_day_day);
        // $('#form_domcy_day').submit();
        // return;

        var params = "fc_domcy_day_buy_sno=" + domcy_day_buy_sno + "&fc_domcy_day_day=" + domcy_day_day;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_domcyday_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });

    }

    // 휴회횟수 추가
    function change_domcy_cnt(buy_sno, event_stat, domcy_yn) {
        if (event_stat == "99") {
            alertToast('error', '종료된 상품은 휴회일 추가를 할 수 없습니다.');
            return;
        }

        if (domcy_yn == "N") {
            alertToast('error', '해당 상품은 휴회이 불가능한 상품입니다.');
            return;
        }

        $('#domcy_cnt_buy_sno').val(buy_sno);
        $('#modal_pop_domcy_cnt').modal("show");
    }

    // 휴회횟수 추가 처리
    function btn_domcy_cnt_submit() {
        var domcy_cnt_buy_sno = $('#domcy_cnt_buy_sno').val();
        var domcy_cnt_cnt = $('#domcy_cnt_cnt').val();

        // test
        // $('#fc_domcy_cnt_buy_sno').val(domcy_cnt_buy_sno);
        // $('#fc_domcy_cnt_cnt').val(domcy_cnt_cnt);
        // $('#form_domcy_cnt').submit();
        // return;

        var params = "fc_domcy_cnt_buy_sno=" + domcy_cnt_buy_sno + "&fc_domcy_cnt_cnt=" + domcy_cnt_cnt;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_domcycnt_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });

    }

    // 수업 추가
    function change_clas_cnt(buy_sno, event_stat, m_cate) {
        if (event_stat == '99') {
            alertToast('error', '종료된 상품은 수업 추가를 할 수 없습니다.');
            return;
        }

        if (m_cate=='GRP' || m_cate=='PRV') {
            $('#clas_cnt_buy_sno').val(buy_sno);
            $('#modal_pop_clas_cnt').modal("show");
        } else {
            alertToast('error', '수업 상품이 아닌 상품은 수업 추가를 할 수 없습니다.');
            return;
        }
    }

    function btn_clas_cnt_submit() {
        var clas_cnt_buy_sno = $('#clas_cnt_buy_sno').val();
        var clas_cnt_cnt = $('#clas_cnt_cnt').val();

        // test
        // $('#fc_clas_cnt_buy_sno').val(clas_cnt_buy_sno);
        // $('#fc_clas_cnt_cnt').val(clas_cnt_cnt);
        // $('#form_clas_cnt').submit();
        // return;

        var params = "fc_clas_cnt_buy_sno=" + clas_cnt_buy_sno + "&fc_clas_cnt_cnt=" + clas_cnt_cnt;
        jQuery.ajax({
            url: '/ttotalmain/ajax_info_mem_clascnt_proc',
            type: 'POST',
            data: params,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'text',
            success: function(result) {
                if (result.substr(0, 8) == '<script>') {
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                    location.href = '/tlogin';
                    return;
                }

                json_result = $.parseJSON(result);
                console.log(json_result);
                if (json_result['result'] == 'true') {
                    location.reload();
                } else {
                    alertToast('error', json_result['msg']);
                }
            }
        }).done((res) => {
            // 통신 성공시
            console.log('통신성공');
        }).fail((error) => {
            // 통신 실패시
            console.log('통신실패');
            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
            location.href = '/tlogin';
            return;
        });
    }


    // 강제 종료하기
    function change_event_just_end(buy_sno) {
        ToastConfirm.fire({
            icon: "question",
            title: "  확인 메세지",
            html: "<font color='#000000' >강제 종료를 하시겠습니까?</font>",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonColor: "#28a745",
        }).then((result) => {
            if (result.isConfirmed) {
                // 성공일 경우	
                var params = "buy_sno=" + buy_sno;
                jQuery.ajax({
                    url: '/ttotalmain/ajax_info_mem_just_end_proc',
                    type: 'POST',
                    data: params,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: 'text',
                    success: function(result) {
                        if (result.substr(0, 8) == '<script>') {
                            alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                            location.href = '/tlogin';
                            return;
                        }

                        json_result = $.parseJSON(result);
                        console.log(json_result);
                        if (json_result['result'] == 'true') {
                            location.reload();
                        } else {
                            alertToast('error', json_result['msg']);
                        }
                    }
                }).done((res) => {
                    // 통신 성공시
                    console.log('통신성공');
                }).fail((error) => {
                    // 통신 실패시
                    console.log('통신실패');
                    alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                    location.href = '/tlogin';
                    return;
                });
            }
        });

    }

    function more_info_show(buy_sno, rson) {
        if ($('.sec-event-info').css('display') != 'none') {
            more_info_hide();
            return;
        }

        // 51 : 환불(교체) / 81 : 환불 / 61 : 양도 / 62 : 양수	

        if (rson == "61" || rson == "62") {
            var params = "buy_sno=" + buy_sno + "&rson=" + rson;
            jQuery.ajax({
                url: '/ttotalmain/ajax_info_mem_more_trans_info',
                type: 'POST',
                data: params,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: 'text',
                success: function(result) {
                    if (result.substr(0, 8) == '<script>') {
                        alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                        location.href = '/tlogin';
                        return;
                    }

                    json_result = $.parseJSON(result);
                    if (json_result['result'] == 'true') {
                        var info = json_result['info'];
                        console.log(info);
                        if (info['USE_AMT'] == null) info['USE_AMT'] = 0; // null 오류 방지
                        var info_html = "";
                        info_html += "<table class='table table-bordered table-sm col-md-12'>";
                        info_html += "	<tbody>";
                        info_html += "		<tr>";
                        info_html += "			<td colspan='9' class='bg-gray'>양도내역</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도등록일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도자</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양도횟수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용금액</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>남은금액</td>";

                        info_html += "			<td style='background-color:#e2e4e7'>양수자</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양수일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>양수횟수</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td>" + info['CRE_DATETM'] + "</td>";
                        info_html += "			<td>" + info['TRANSM_NM'] + " (" + info['TRANSM_ID'] + ") </td>";
                        info_html += "			<td>" + info['TRANSM_LEFT_DAY'] + "</td>";
                        info_html += "			<td>" + info['REAL_TRANSM_CNT'] + "</td>";
                        info_html += "			<td>" + info['USE_AMT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";
                        info_html += "			<td>" + info['LEFT_AMT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";

                        info_html += "			<td>" + info['ASSGN_NM'] + " (" + info['ASSGN_ID'] + ") </td>";
                        info_html += "			<td>" + info['REAL_TRANSM_DAY'] + "</td>";
                        info_html += "			<td>" + info['REAL_TRANSM_CNT'] + "</td>";
                        info_html += "		<tr>";
                        info_html += "	</tbody>";
                        info_html += "</table>";

                        $('#sec_event_info_detail').html(info_html);

                    }
                }
            }).done((res) => {
                // 통신 성공시
                console.log('통신성공');
            }).fail((error) => {
                // 통신 실패시
                console.log('통신실패');
                alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                location.href = '/tlogin';
                return;
            });
        }


        if (rson == "51" || rson == "81") {
            var params = "buy_sno=" + buy_sno + "&rson=" + rson;
            jQuery.ajax({
                url: '/ttotalmain/ajax_info_mem_more_refund_info',
                type: 'POST',
                data: params,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: 'text',
                success: function(result) {
                    if (result.substr(0, 8) == '<script>') {
                        alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [01]');
                        location.href = '/tlogin';
                        return;
                    }

                    json_result = $.parseJSON(result);
                    if (json_result['result'] == 'true') {
                        var info = json_result['info'];
                        var info_html = "";
                        info_html += "<table class='table table-bordered table-sm col-md-12'>";
                        info_html += "	<tbody>";
                        info_html += "		<tr>";
                        info_html += "			<td colspan='9' class='bg-gray'>환불내역</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td style='background-color:#e2e4e7'>환불일</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>총이용일수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용일수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>총수업횟수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용수업횟수</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>사용금액</td>";
                        info_html += "			<td style='background-color:#e2e4e7'>환불금액</td>";
                        info_html += "		</tr>";
                        info_html += "		<tr class='text-center'>";
                        info_html += "			<td>" + info['CRE_DATETM'] + "</td>";
                        info_html += "			<td>" + info['TOTAL_EXR_DAY_CNT'] + "</td>";
                        info_html += "			<td>" + info['USE_DAY_CNT'] + "</td>";
                        info_html += "			<td>" + info['CLAS_CNT'] + "</td>";
                        info_html += "			<td>" + info['USE_CLAS_CNT'] + "</td>";
                        info_html += "			<td>" + info['TOTAL_EXR_DAY_CNT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";
                        info_html += "			<td>" + info['REFUND_AMT'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); + "</td>";
                        info_html += "		<tr>";
                        info_html += "	</tbody>";
                        info_html += "</table>";

                        $('#sec_event_info_detail').html(info_html);

                    }
                }
            }).done((res) => {
                // 통신 성공시
                console.log('통신성공');
            }).fail((error) => {
                // 통신 실패시
                console.log('통신실패');
                alert('로그인이 만료 되었습니다. 다시 로그인해주세요 [02]');
                location.href = '/tlogin';
                return;
            });
        }

        $('.sec-mem-info-detail').hide();
        $('.sec-mem-info').show();
        $('.sec-event-info').show();




    }

    function more_info_hide() {
        $('.sec-mem-info-detail').show();
        $('.sec-mem-info').hide();
        $('.sec-event-info').hide();
    }

    // ===================== Modal Script [ START ] ===========================

    // ===================== Modal Script [ END ] =============================

    //Date picker
    $('.datepp').datepicker({
        format: "yyyy-mm-dd", //데이터 포맷 형식(yyyy : 년 mm : 월 dd : 일 )
        autoclose: true, //사용자가 날짜를 클릭하면 자동 캘린더가 닫히는 옵션
        clearBtn: false, //날짜 선택한 값 초기화 해주는 버튼 보여주는 옵션 기본값 false 보여주려면 true
        immediateUpdates: true, //사용자가 보는 화면으로 바로바로 날짜를 변경할지 여부 기본값 :false 
        multidate: false, //여러 날짜 선택할 수 있게 하는 옵션 기본값 :false 
        templates: {
            leftArrow: '&laquo;',
            rightArrow: '&raquo;'
        }, //다음달 이전달로 넘어가는 화살표 모양 커스텀 마이징 
        showWeekDays: true, // 위에 요일 보여주는 옵션 기본값 : true
        title: "날짜선택", //캘린더 상단에 보여주는 타이틀
        todayHighlight: true, //오늘 날짜에 하이라이팅 기능 기본값 :false 
        toggleActive: true, //이미 선택된 날짜 선택하면 기본값 : false인경우 그대로 유지 true인 경우 날짜 삭제
        weekStart: 0, //달력 시작 요일 선택하는 것 기본값은 0인 일요일 

        //startDate: '-10d',	//달력에서 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
        //endDate: '+10d',	//달력에서 선택 할 수 있는 가장 느린 날짜. 이후로 선택 불가 ( d : 일 m : 달 y : 년 w : 주)
        //datesDisabled : ['2019-06-24','2019-06-26'],//선택 불가능한 일 설정 하는 배열 위에 있는 format 과 형식이 같아야함.
        //daysOfWeekDisabled : [0,6],	//선택 불가능한 요일 설정 0 : 일요일 ~ 6 : 토요일
        //daysOfWeekHighlighted : [3], //강조 되어야 하는 요일 설정
        //disableTouchKeyboard : false,	//모바일에서 플러그인 작동 여부 기본값 false 가 작동 true가 작동 안함.
        //calendarWeeks : false, //캘린더 옆에 몇 주차인지 보여주는 옵션 기본값 false 보여주려면 true
        //multidateSeparator :",", //여러 날짜를 선택했을 때 사이에 나타나는 글짜 2019-05-01,2019-06-01

        language: "ko" //달력의 언어 선택, 그에 맞는 js로 교체해줘야한다.
    });

    // 드롭다운 메뉴 위치 자동 조정
    $(document).ready(function() {
        // 드롭다운 메뉴가 화면 밖으로 나가는 것을 방지
        $('.dropdown').on('shown.bs.dropdown', function() {
            var $button = $(this).find('.dropdown-toggle');
            var $menu = $(this).find('.dropdown-menu');
            
            // 버튼과 메뉴의 위치 확인
            var buttonOffset = $button.offset();
            var menuHeight = $menu.outerHeight();
            var windowHeight = $(window).height();
            var windowScroll = $(window).scrollTop();
            
            // 화면 하단 공간 확인
            var spaceBelow = windowHeight + windowScroll - buttonOffset.top - $button.outerHeight();
            
            // 공간이 부족하면 위쪽으로 표시
            if (spaceBelow < menuHeight + 10) {
                $(this).addClass('dropup');
            } else {
                $(this).removeClass('dropup');
            }
        });
    });

    // 메모 내용 자동 저장
    function saveMemo(element) {
        var memoSno = $(element).data('memo-sno');
        var originalContent = $(element).data('original-content') || '';
        
        // 현재 HTML 내용을 가져옴
        var $element = $(element);
        var htmlContent = $element.html();
        
        // br 태그를 줄바꿈으로 변환하고 다른 태그는 제거
        var newContent = htmlContent
            .replace(/<br\s*\/?>/gi, '\n')
            .replace(/<div>/gi, '\n')
            .replace(/<\/div>/gi, '')
            .replace(/<[^>]*>/g, '')
            .replace(/&nbsp;/g, ' ')
            .replace(/&amp;/g, '&')
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'")
            .trim();
        
        // 내용이 변경되지 않았으면 저장하지 않음
        if (originalContent.trim() === newContent) {
            return;
        }
        
        // 저장 중 표시
        $(element).css('opacity', '0.7');
        
        $.ajax({
            url: '/ttotalmain/ajax_memo_content_update',
            type: 'POST',
            data: {
                memo_sno: memoSno,
                memo_content: newContent
            },
            success: function(response) {
                $(element).css('opacity', '1');
                $(element).data('original-content', newContent);
                
                // 저장 완료 표시 (일시적으로 초록색 그림자 효과)
                $(element).css('box-shadow', '0 0 0 3px rgba(40, 167, 69, 0.3)');
                setTimeout(function() {
                    $(element).css('box-shadow', '');
                }, 1000);
            },
            error: function() {
                $(element).css('opacity', '1');
                alert('메모 저장에 실패했습니다.');
                // 원래 내용으로 복원
                $(element).html(originalContent.replace(/\n/g, '<br>'));
            }
        });
    }

    // 메모 타입 변경 (중요/일반)
    function changeMemoType(element, memoSno) {
        var $button = $(element);
        var currentType = $button.data('current-type');
        var newType = (currentType === 'Y') ? 'N' : 'Y';
        
        $.ajax({
            url: '/ttotalmain/ajax_memo_prio_update',
            type: 'POST',
            data: {
                memo_sno: memoSno,
                prio_set: newType
            },
            success: function(response) {
                // 버튼 상태 업데이트
                if (newType === 'Y') {
                    $button.removeClass('btn-secondary').addClass('btn-danger');
                    $button.html('<i class="fas fa-star"></i> 중요메모');
                } else {
                    $button.removeClass('btn-danger').addClass('btn-secondary');
                    $button.html('<i class="fas fa-star"></i> 일반메모');
                }
                
                // data 속성 업데이트
                $button.data('current-type', newType);
                
                // 메모 아이템의 배경색 변경
                var memoItem = $button.closest('.memo-item');
                if (newType === 'Y') {
                    memoItem.css('background-color', '#fff5f5');
                } else {
                    memoItem.css('background-color', '');
                }
            },
            error: function() {
                alert('메모 타입 변경에 실패했습니다.');
            }
        });
    }

    // Enter 키로 줄바꿈 처리 (기존 메모만)
    $(document).on('keydown', '.memo-content[data-memo-sno]', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            // 현재 커서 위치에 줄바꿈 삽입
            var selection = window.getSelection();
            var range = selection.getRangeAt(0);
            var br = document.createTextNode('\n');
            range.insertNode(br);
            range.setStartAfter(br);
            range.setEndAfter(br);
            selection.removeAllRanges();
            selection.addRange(range);
            return false;
        }
    });
    
    // 기존 메모 자동 저장 - blur 이벤트
    $(document).on('blur', '.memo-content[data-memo-sno]', function() {
        var $this = $(this);
        var memoSno = $this.data('memo-sno');
        var originalContent = $this.data('original-content');
        
        // 텍스트 내용 가져오기
        var currentContent = $this.text().trim();
        
        // 내용이 변경되었을 때만 저장
        if (currentContent !== originalContent && memoSno) {
            $.ajax({
                url: '/ttotalmain/ajax_memo_modify_proc',
                type: 'POST',
                data: {
                    modify_memo_mgmt_sno: memoSno,
                    memo_content: currentContent
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.result === 'true') {
                        // 원본 데이터 업데이트
                        $this.data('original-content', currentContent);
                        // 저장 완료 시각적 피드백
                        $this.css('border-color', '#28a745');
                        setTimeout(function() {
                            $this.css('border-color', 'transparent');
                        }, 1000);
                    } else {
                        alert('메모 수정에 실패했습니다.');
                        // 원본 내용으로 되돌리기
                        $this.text(originalContent);
                    }
                },
                error: function() {
                    alert('메모 수정 중 오류가 발생했습니다.');
                    // 원본 내용으로 되돌리기
                    $this.text(originalContent);
                }
            });
        }
    });
    
    // 새 메모에서 Enter 키 처리
    $(document).on('keydown', '#new-memo-content', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            // 현재 커서 위치에 줄바꿈 삽입
            var selection = window.getSelection();
            var range = selection.getRangeAt(0);
            var br = document.createTextNode('\n');
            range.insertNode(br);
            range.setStartAfter(br);
            range.setEndAfter(br);
            selection.removeAllRanges();
            selection.addRange(range);
            return false;
        }
    });

    // 새 메모 타입 토글
    var newMemoType = 'N'; // 기본값은 일반메모
    var isNewMemoSaving = false;
    var newMemoTimeInterval = null;
    
    
    function toggleNewMemoType() {
        // 현재 타입을 토글
        newMemoType = (newMemoType === 'N') ? 'Y' : 'N';
        
        var $toggleBtn = $('#new-memo-toggle');
        
        if (newMemoType === 'Y') {
            // 중요메모 상태
            $toggleBtn.removeClass('btn-secondary').addClass('btn-danger');
            $toggleBtn.html('<i class="fas fa-star"></i> 중요메모');
            $('#new-memo-form').css('background-color', '#fff5f5');
        } else {
            // 일반메모 상태
            $toggleBtn.removeClass('btn-danger').addClass('btn-secondary');
            $toggleBtn.html('<i class="fas fa-star"></i> 일반메모');
            $('#new-memo-form').css('background-color', '');
        }
    }
    
    // 새 메모 포커스 시
    function onNewMemoFocus() {
        // 시간 표시 시작
        updateNewMemoTime();
        newMemoTimeInterval = setInterval(updateNewMemoTime, 1000);
    }
    
    // 새 메모 블러 시
    function onNewMemoBlur(memSno) {
        // 시간 업데이트 중지
        if (newMemoTimeInterval) {
            clearInterval(newMemoTimeInterval);
            newMemoTimeInterval = null;
        }
        
        // 내용이 있으면 저장
        var content = $('#new-memo-content').text().trim();
        if (content && !isNewMemoSaving) {
            saveNewMemo(memSno);
        }
    }
    
    // 새 메모 입력 시
    function onNewMemoInput() {
        // 실시간으로 시간 업데이트
        updateNewMemoTime();
    }
    
    // 시간 업데이트
    function updateNewMemoTime() {
        var now = new Date();
        var dateStr = now.getFullYear() + '-' + 
                      String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                      String(now.getDate()).padStart(2, '0') + ' ' +
                      String(now.getHours()).padStart(2, '0') + ':' +
                      String(now.getMinutes()).padStart(2, '0');
        $('#new-memo-datetime').text(dateStr);
    }
    
    // 새 메모 저장
    function saveNewMemo(memSno) {
        // 텍스트 내용 가져오기
        var content = $('#new-memo-content').text().trim();
        
        if (!content || isNewMemoSaving) {
            return;
        }
        
        isNewMemoSaving = true;
        
        $.ajax({
            url: '/ttotalmain/ajax_memo_insert_proc',
            type: 'POST',
            data: {
                memo_mem_sno: memSno,
                memo_content: content,
                memo_prio_set: newMemoType
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.result === 'true') {
                    // 페이지 새로고침하여 새 메모 표시
                    location.reload();
                } else {
                    alert('메모 등록에 실패했습니다.');
                    isNewMemoSaving = false;
                }
            },
            error: function() {
                alert('메모 등록 중 오류가 발생했습니다.');
                isNewMemoSaving = false;
            }
        });
    }
    
    // 메모 검색 기능
    $(document).on('input', '#memo-search', function() {
        var searchText = $(this).val().toLowerCase();
        
        $('.memo-list .memo-item').each(function() {
            var memoContent = $(this).find('.memo-content').text().toLowerCase();
            var memoDate = $(this).find('.text-muted').text().toLowerCase();
            
            if (searchText === '' || memoContent.includes(searchText) || memoDate.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // 검색 결과가 없을 때 메시지 표시
        var visibleMemos = $('.memo-list .memo-item:visible').length;
        if (visibleMemos === 0 && searchText !== '') {
            if ($('#no-search-results').length === 0) {
                $('.memo-list').append(
                    '<div id="no-search-results" class="text-center text-muted py-5">' +
                    '<i class="fas fa-search fa-3x mb-3"></i>' +
                    '<p>검색 결과가 없습니다.</p>' +
                    '</div>'
                );
            }
        } else {
            $('#no-search-results').remove();
        }
    });
</script>