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
                    <?php if (isset($lockerTypes) && !empty($lockerTypes)): ?>
                        <?php foreach ($lockerTypes as $type): ?>
                            <div class="locker-type-item" 
                                 data-type-id="<?= $type['LOCKR_TYPE_CD'] ?>"
                                 data-width="<?= $type['WIDTH'] ?>"
                                 data-height="<?= $type['HEIGHT'] ?>"
                                 data-depth="<?= $type['DEPTH'] ?>"
                                 data-color="<?= $type['COLOR'] ?>"
                                 draggable="true"
                                 onclick="LockerPlacement.selectLockerType('<?= $type['LOCKR_TYPE_CD'] ?>')">
                                <div class="type-preview" style="background-color: <?= $type['COLOR'] ?>"></div>
                                <div class="type-info">
                                    <div class="type-name"><?= htmlspecialchars($type['LOCKR_TYPE_NM']) ?></div>
                                    <div class="type-dimensions"><?= $type['WIDTH'] ?>×<?= $type['HEIGHT'] ?>×<?= $type['DEPTH'] ?>cm</div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">등록된 락커 타입이 없습니다.</p>
                    <?php endif; ?>
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
                    <!-- 뷰 모드 전환 -->
                    <div class="btn-group me-2" role="group">
                        <button class="btn btn-xs btn-primary active" data-view-mode="floor" onclick="LockerPlacement.setViewMode('floor')" title="평면배치모드 (P)">
                            <i class="fa fa-th"></i> 평면
                        </button>
                        <button class="btn btn-xs btn-default" data-view-mode="front" onclick="LockerPlacement.setViewMode('front')" title="정면배치모드 (F)">
                            <i class="fa fa-square-o"></i> 정면
                        </button>
                    </div>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" onclick="LockerPlacement.zoomOut()"><i class="fa fa-search-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" onclick="LockerPlacement.resetZoom()"><i class="fa fa-compress"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" onclick="LockerPlacement.zoomIn()"><i class="fa fa-search-plus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" onclick="LockerPlacement.autoFitLockers()" title="화면에 맞춤"><i class="fa fa-expand"></i></a>
                </div>
            </div>
            <div class="panel-body p-0">
                <!-- 구역 탭 -->
                <div class="zone-tabs">
                    <div class="tabs-left">
                        <?php if (isset($lockerZones) && !empty($lockerZones)): ?>
                            <?php foreach ($lockerZones as $index => $zone): ?>
                                <button class="zone-tab <?= $index === 0 ? 'active' : '' ?>" 
                                        data-zone-id="<?= $zone['LOCKR_KND_CD'] ?>"
                                        data-zone-color="<?= $zone['COLOR'] ?>"
                                        onclick="LockerPlacement.switchZone('<?= $zone['LOCKR_KND_CD'] ?>')">
                                    <span class="zone-color" style="background-color: <?= $zone['COLOR'] ?>"></span>
                                    <?= htmlspecialchars($zone['LOCKR_KND_NM']) ?>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">등록된 구역이 없습니다.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
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
                        <button class="btn btn-sm btn-primary" onclick="LockerPlacement.saveLayout()">
                            <i class="fa fa-save"></i> 저장
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="LockerPlacement.deleteSelectedLockers()">
                            <i class="fa fa-trash"></i> 삭제
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="LockerPlacement.rotateSelectedLockers()">
                            <i class="fa fa-rotate-right"></i> 회전
                        </button>
                        <button class="btn btn-sm btn-info" onclick="LockerPlacement.selectAllLockers()">
                            <i class="fa fa-check-square"></i> 전체선택
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="LockerPlacement.clearSelection()">
                            <i class="fa fa-times"></i> 선택해제
                        </button>
                    </div>
                    <div class="btn-group ms-2" role="group">
                        <button class="btn btn-sm btn-success" onclick="openZoneModal()">
                            <i class="fa fa-plus"></i> 구역추가
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 락커 타입 등록 모달 -->
<div class="modal fade" id="lockerRegistrationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">락커 타입 등록</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="lockerRegistrationForm">
                    <div class="form-group">
                        <label>타입 이름</label>
                        <input type="text" class="form-control" id="lockerTypeName" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>너비 (cm)</label>
                                <input type="number" class="form-control" id="lockerWidth" min="1" value="40" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>깊이 (cm)</label>
                                <input type="number" class="form-control" id="lockerDepth" min="1" value="40" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>높이 (cm)</label>
                                <input type="number" class="form-control" id="lockerHeight" min="1" value="40" required>
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
                <button type="button" class="btn btn-primary" onclick="registerLockerType()">등록</button>
            </div>
        </div>
    </div>
</div>

<!-- 구역 추가 모달 -->
<div class="modal fade" id="zoneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">구역 추가</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="zoneForm">
                    <div class="form-group">
                        <label>구역 이름</label>
                        <input type="text" class="form-control" id="zoneName" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" onclick="addZone()">추가</button>
            </div>
        </div>
    </div>
</div>

<script>
// 모달 관련 함수들
function openLockerRegistrationModal() {
    $('#lockerRegistrationModal').modal('show');
}

function openZoneModal() {
    $('#zoneModal').modal('show');
}

// 락커 타입 등록
async function registerLockerType() {
    const name = document.getElementById('lockerTypeName').value;
    const width = document.getElementById('lockerWidth').value;
    const depth = document.getElementById('lockerDepth').value;
    const height = document.getElementById('lockerHeight').value;
    const color = document.getElementById('lockerColor').value;
    
    if (!name) {
        alert('타입 이름을 입력해주세요.');
        return;
    }
    
    try {
        // API를 통해 타입 등록
        const newType = await window.LockerAPI.addLockerType({
            name: name,
            width: width,
            depth: depth,
            height: height,
            color: color
        });
        
        if (newType) {
            console.log('Locker type registered:', newType);
            
            // 타입 목록 새로고침
            if (window.LockerPlacement) {
                window.LockerPlacement.loadLockerTypes();
            }
            
            alert('락커 타입이 등록되었습니다.');
        } else {
            alert('락커 타입 등록에 실패했습니다.');
        }
    } catch (error) {
        console.error('Failed to register locker type:', error);
        alert('락커 타입 등록 중 오류가 발생했습니다.');
    }
    
    // 모달 닫기
    $('#lockerRegistrationModal').modal('hide');
    
    // 폼 리셋
    document.getElementById('lockerRegistrationForm').reset();
}

// 구역 추가
async function addZone() {
    const name = document.getElementById('zoneName').value;
    
    if (!name) {
        alert('구역 이름을 입력해주세요.');
        return;
    }
    
    try {
        // API를 통해 구역 추가
        const newZone = await window.LockerAPI.addZone(name);
        
        if (newZone) {
            console.log('Zone added:', newZone);
            
            // 구역 목록 새로고침
            if (window.LockerPlacement) {
                window.LockerPlacement.loadZones();
            }
            
            alert('구역이 추가되었습니다.');
        } else {
            alert('구역 추가에 실패했습니다.');
        }
    } catch (error) {
        console.error('Failed to add zone:', error);
        alert('구역 추가 중 오류가 발생했습니다.');
    }
    
    // 모달 닫기
    $('#zoneModal').modal('hide');
    
    // 폼 리셋
    document.getElementById('zoneForm').reset();
}

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

// 서버에서 미리 로딩된 데이터
window.PreloadedData = {
    lockerTypes: <?= json_encode($lockerTypes ?? []) ?>,
    lockerZones: <?= json_encode($lockerZones ?? []) ?>,
    lockers: <?= json_encode($lockers ?? []) ?>
};

console.log('Preloaded data loaded:', window.PreloadedData);
console.log('Locker Types count:', window.PreloadedData.lockerTypes.length);
console.log('Locker Zones count:', window.PreloadedData.lockerZones.length);
console.log('Lockers count:', window.PreloadedData.lockers.length);

// PHP 변수 확인
console.log('PHP lockerTypes:', <?= json_encode(isset($lockerTypes) ? count($lockerTypes) : 'not set') ?>);
console.log('PHP lockerZones:', <?= json_encode(isset($lockerZones) ? count($lockerZones) : 'not set') ?>);
console.log('PHP lockers:', <?= json_encode(isset($lockers) ? count($lockers) : 'not set') ?>);
</script>

<!-- CSS 파일 추가 -->
<link rel="stylesheet" href="<?= base_url('assets/css/locker-placement-original.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/locker-placement.css') ?>?v=<?= time() ?>">

<!-- JavaScript 파일 추가 -->
<script src="<?= base_url('assets/js/locker-api.js') ?>?v=<?= time() ?>"></script>
<script src="<?= base_url('assets/js/locker-placement-enhanced.js') ?>?v=<?= time() ?>"></script>

<?=$jsinc ?>