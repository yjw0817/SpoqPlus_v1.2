<?php
$sDef = SpoqDef();
helper('form');
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<div class="row">
    <!-- 좌측 사이드바 - 락커 선택창 -->
    <div class="col-xl-3 col-lg-4">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">락커 선택창</h4>
            </div>
            <div class="panel-body">
                <!-- 락커 타입 목록 -->
                <div id="lockerTypeList" class="locker-type-list">
                    <!-- JavaScript로 동적 생성 -->
                </div>
                
                <!-- 락커 등록 버튼 -->
                <button class="btn btn-primary btn-block mt-3" onclick="openLockerRegistrationModal()">
                    <i class="fa fa-plus"></i> 락커 등록
                </button>
            </div>
        </div>
    </div>
    
    <!-- 우측 메인 영역 - 캔버스 -->
    <div class="col-xl-9 col-lg-8">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">락커 배치도</h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" onclick="zoomOut()"><i class="fa fa-search-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" onclick="resetZoom()"><i class="fa fa-compress"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" onclick="zoomIn()"><i class="fa fa-search-plus"></i></a>
                </div>
            </div>
            <div class="panel-body p-0">
                <!-- 구역 탭 -->
                <ul class="nav nav-tabs" id="zoneTabs">
                    <!-- JavaScript로 동적 생성 -->
                </ul>
                
                <!-- 캔버스 영역 -->
                <div class="canvas-wrapper" style="position: relative; width: 100%; height: 700px; overflow: auto; background: white;">
                    <svg id="lockerCanvas" class="locker-canvas" width="100%" height="100%">
                        <!-- 그리드 패턴 -->
                        <defs>
                            <pattern id="grid" width="30" height="30" patternUnits="userSpaceOnUse">
                                <path d="M 30 0 L 0 0 0 30" fill="none" stroke="#e5e5e5" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                        
                        <!-- 락커 그룹 -->
                        <g id="lockersGroup">
                            <!-- JavaScript로 동적 생성 -->
                        </g>
                    </svg>
                </div>
                
                <!-- 툴바 -->
                <div class="p-2 bg-light border-top">
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" onclick="saveLayout()">
                            <i class="fa fa-save"></i> 저장
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteSelected()">
                            <i class="fa fa-trash"></i> 삭제
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="rotateSelected()">
                            <i class="fa fa-rotate-right"></i> 회전
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 락커 등록 모달 -->
<div class="modal fade" id="lockerRegistrationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">락커 등록</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="lockerRegistrationForm">
                    <div class="form-group">
                        <label>락커 이름</label>
                        <input type="text" class="form-control" id="lockerName" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>너비 (cm)</label>
                                <input type="number" class="form-control" id="lockerWidth" min="1" value="30" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>깊이 (cm)</label>
                                <input type="number" class="form-control" id="lockerDepth" min="1" value="30" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>높이 (cm)</label>
                                <input type="number" class="form-control" id="lockerHeight" min="1" value="30" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>색상</label>
                        <input type="color" class="form-control" id="lockerColor" value="#4A90E2">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" onclick="registerLocker()">등록</button>
            </div>
        </div>
    </div>
</div>

<script>
// PHP에서 JavaScript로 데이터 전달
window.LockerConfig = {
    apiUrl: '<?= base_url('api/locker') ?>',
    baseUrl: '<?= rtrim(base_url(), '/') ?>',
    csrfToken: '<?= csrf_token() ?>',
    csrfHeader: '<?= csrf_header() ?>',
    csrfHash: '<?= csrf_hash() ?>',
    companyCode: '<?= isset($companyCode) ? $companyCode : '001' ?>',
    officeCode: '<?= isset($officeCode) ? $officeCode : '001' ?>',
};
</script>

<!-- CSS 파일 추가 -->
<link rel="stylesheet" href="<?= base_url('assets/css/locker-placement.css') ?>?v=<?= time() ?>">

<!-- JavaScript 파일 추가 -->
<script src="<?= base_url('assets/js/locker-placement.js') ?>?v=<?= time() ?>"></script>

<?=$jsinc ?>