<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<!-- Fabric.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">락커 관리</h4>
    </div>
    
    <div class="panel-body">
        <!-- 도면 업로드 -->
        <div class="mb-4">
            <h5>도면 업로드</h5>
            <form id="floorForm" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="floor_nm" placeholder="층 이름" required>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="floor_ord" placeholder="정렬순서" required 
                           oninput="this.value = this.value.replace(/[^-0-9]/g, '');"
                           onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45)">
                </div>
                <div class="col-md-4">
                    <input type="file" class="form-control" name="floor_img" accept="image/jpeg,image/png,image/webp" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">업로드</button>
                </div>
            </form>
        </div>

        <!-- 도면 목록 -->
        <div class="mb-4">
            <h5>도면 목록</h5>
            <div class="row">
                <?php foreach ($floors as $floor): ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title"><?= $floor['floor_nm'] ?></h6>
                            <div class="drawing-container" 
                                 data-floor="<?= $floor['floor_sno'] ?>"
                                 data-image="<?= '/uploads/floors/' . $floor['comp_cd'] . '/' . $floor['bcoff_cd'] . '/' . $floor['floor_img'] ?>">
                                <div class="loading-overlay">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="drawing-tools">
                                    <div class="handle">도구</div>
                                    <div class="tool-buttons">
                                        <button type="button" class="btn btn-sm btn-outline-primary draw-zone" title="구역 그리기">
                                            <i class="fas fa-draw-polygon"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning toggle-edit-mode" title="편집 모드">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary clear-drawing" title="그리기 취소">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary reset-zoom" title="원래 크기">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                    </div>
                                </div>
                                <canvas id="canvas_<?= $floor['floor_sno'] ?>" class="drawing-board"></canvas>
                            </div>
                            <div class="mt-3">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger delete-floor" data-floor="<?= $floor['floor_sno'] ?>">
                                        <i class="fas fa-trash"></i> 삭제
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Toast 컨테이너 -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
</div>



<!-- 구역 추가 모달 -->
<div class="modal fade" id="zoneModal" tabindex="-1" role="dialog" aria-labelledby="zoneModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoneModalLabel">구역 추가</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="닫기"></button>
            </div>
            <div class="modal-body">
                <form id="zoneForm">
                    <input type="hidden" name="floor_sno" id="zone_floor_sno">
                    <input type="hidden" name="zone_coords" id="zone_coords">
                    <div class="mb-3">
                        <label class="form-label" for="zone_nm">구역 이름</label>
                        <input type="text" class="form-control" name="zone_nm" id="zone_nm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="zone_gendr">성별 구분</label>
                        <select class="form-select" name="zone_gendr" id="zone_gendr" required>
                            <option value="">선택하세요</option>
                            <option value="M">남성 전용</option>
                            <option value="F">여성 전용</option>
                            <option value="C">혼용</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" id="saveZone">저장</button>
            </div>
        </div>
    </div>
</div>

<!-- 락커 그룹 추가 모달 -->
<div class="modal fade" id="lockerGroupModal" tabindex="-1" role="dialog" aria-labelledby="lockerGroupModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lockerGroupModalLabel">락커 그룹 추가</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="닫기"></button>
            </div>
            <div class="modal-body">
                <form id="lockerGroupForm">
                    <div class="mb-3">
                        <label for="lockerGroupName" class="form-label">그룹명 <small class="text-muted">(옵션)</small></label>
                        <input type="text" class="form-control" id="lockerGroupName" placeholder="락커 그룹 이름 (비어있으면 자동 생성)">
                    </div>
                    
                    <div class="mb-3">
                        <label for="lockerType" class="form-label">락커 타입 <span class="text-danger">*</span></label>
                        <select class="form-select" id="lockerType">
                            <option value="">선택하세요</option>
                            <option value="일반락커">일반락커</option>
                            <option value="콜프락커">콜프락커</option>
                            <option value="기타">기타 (직접입력)</option>
                        </select>
                        <input type="text" class="form-control mt-2 d-none" id="customLockerType" placeholder="락커 타입을 직접 입력하세요">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="horizontalCount" class="form-label">가로 칸수 <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="horizontalCount" min="1" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="verticalCount" class="form-label">단수 (높이) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="verticalCount" min="1" value="1">
                                <small class="text-muted">정면에서 보았을 때 세로로 쌓이는 층수</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lockerWidth" class="form-label">가로 크기 (cm) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="lockerWidth" min="1" step="0.1" value="30">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lockerDepth" class="form-label">깊이 (cm) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="lockerDepth" min="1" step="0.1" value="37">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lockerHeight" class="form-label">세로 크기 (cm) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="lockerHeight" min="1" step="0.1" value="30">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="autoCalculate" checked>
                                    <label class="form-check-label" for="autoCalculate">
                                        총 칸수 자동 계산
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info alert-sm">
                        <i class="fas fa-info-circle"></i>
                        <small><strong>안내:</strong> 캔버스에서 시작점과 끝점을 클릭하세요.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" id="cancelLockerGroup">취소</button>
                <button type="button" class="btn btn-primary btn-sm" id="startLockerDrawing">그리기 시작</button>
            </div>
        </div>
    </div>
</div>

<style>
/* 도면 카드 테두리 제거 */
.card {
    border: none !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-left: 10px; /* 카드 전체를 오른쪽으로 이동 */
}

/* 층 제목 여백 조정 */
.card-title {
    margin-left: 8px;
    margin-bottom: 15px;
}

.drawing-container {
    position: relative;
    width: 100%;
    height: 600px;
    border: 1px solid #ccc;
    background: #fff;
    overflow: hidden;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.drawing-tools {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 1000;
    background: white;
    padding: 5px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
}

.drawing-tools .handle {
    padding: 2px 5px;
    margin-bottom: 5px;
    background: #f8f9fa;
    border-radius: 3px;
    font-size: 12px;
    text-align: center;
    cursor: move;
}

.drawing-tools .tool-buttons {
    display: flex;
    flex-direction: row;
    gap: 3px;
}

.drawing-tools .btn {
    width: 32px !important;
    height: 32px !important;
    padding: 4px !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.btn-equal-size {
    min-width: 80px !important;
    width: 80px !important;
    height: 40px !important;
    padding: 8px 16px !important;
    margin: 0 5px !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
    display: inline-block !important;
    text-align: center !important;
    vertical-align: middle !important;
}

.drawing-board {
    width: 100%;
    height: 100%;
}

.zone-detail-panel {
    position: absolute;
    top: 10px;
    right: 10px;
    background: white;
    padding: 15px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    z-index: 1000;
}

/* 캔버스 커서 스타일 */
.drawing-container.drawing .drawing-board {
    cursor: crosshair !important;
}

.drawing-container.panning .drawing-board {
    cursor: grabbing !important;
}
</style>

<?= $jsinc ?>

<!-- Fabric.js 로딩 -->
<script>
// 전역 변수 선언
let fabricCanvases = {};
let isDrawing = false;
let currentPoints = [];
let currentPolygon = null;
let firstPointMarker = null;
let selectedZone = null;
let tempPolygon = null;
let currentFloorSno = null;
let isDragging = false;
let lastPosX, lastPosY;
let tempLine = null;  // 임시 선을 위한 변수 추가
let editModeStates = {};  // 각 캔버스별 편집 모드 상태
let originalZoneStates = {};  // 각 캔버스별 구역 원래 상태 저장

// 락커 그룹 관련 변수
let isDrawingLockerGroup = false;
let lockerGroupPoints = [];
let currentZoneSno = null;
let currentZoneObject = null;
let isLockerGroupMode = false;

// 각도를 45도 단위로 제한하는 함수
function snapAngle(dx, dy) {
    const angle = Math.atan2(dy, dx) * 180 / Math.PI;
    const snapAngle = Math.round(angle / 45) * 45;
    const distance = Math.sqrt(dx * dx + dy * dy);
    const radians = snapAngle * Math.PI / 180;
    return {
        x: Math.cos(radians) * distance,
        y: Math.sin(radians) * distance
    };
}

// 포인트가 현재 구역 내에 있는지 확인하는 함수 (Ray Casting 알고리즘)
function isPointInCurrentZone(point) {
    console.log('🔍 isPointInCurrentZone 호출됨:', {
        point: point,
        currentZoneObject: !!currentZoneObject,
        currentZoneSno: currentZoneSno
    });
    
    if (!currentZoneObject || !currentZoneSno) {
        console.log('❌ No current zone object or zone SNO');
        return false;
    }
    
    console.log('📋 currentZoneObject 구조:', {
        type: currentZoneObject.type,
        objectsCount: currentZoneObject._objects?.length,
        data: currentZoneObject.data
    });
    
    // 현재 구역 객체에서 폴리곤 가져오기
    const polygon = currentZoneObject._objects[0]; // 첫 번째 객체가 폴리곤
    if (!polygon || !polygon.points) {
        console.log('❌ No polygon points found in current zone object');
        console.log('Polygon object:', polygon);
        return false;
    }
    
    console.log('📐 Original polygon points:', polygon.points);
    console.log('🔄 Zone object transform properties:', {
        left: currentZoneObject.left,
        top: currentZoneObject.top,
        scaleX: currentZoneObject.scaleX,
        scaleY: currentZoneObject.scaleY,
        angle: currentZoneObject.angle
    });
    
    // 폴리곤의 실제 좌표 계산 (변환 적용)
    const zonePoints = polygon.points.map(p => {
        // 구역 객체의 변환을 적용한 실제 좌표
        const transformed = fabric.util.transformPoint(
            { x: p.x, y: p.y },
            currentZoneObject.calcTransformMatrix()
        );
        console.log(`점 변환: (${p.x}, ${p.y}) → (${transformed.x}, ${transformed.y})`);
        return transformed;
    });
    
    console.log('🗺️ Zone boundary points (transformed):', zonePoints);
    console.log('🎯 Testing point:', point);
    
    // 폴리곤의 바운딩 박스를 먼저 체크해서 명백히 벗어난 경우 빠르게 제외
    const minX = Math.min(...zonePoints.map(p => p.x));
    const maxX = Math.max(...zonePoints.map(p => p.x));
    const minY = Math.min(...zonePoints.map(p => p.y));
    const maxY = Math.max(...zonePoints.map(p => p.y));
    
    console.log('📦 Zone bounding box:', {
        minX: minX,
        maxX: maxX,
        minY: minY,
        maxY: maxY
    });
    
    if (point.x < minX || point.x > maxX || point.y < minY || point.y > maxY) {
        console.log('❌ Point outside bounding box');
        return false;
    }
    
    console.log('✅ Point inside bounding box, checking with Ray Casting...');
    
    // Ray Casting 알고리즘으로 점이 폴리곤 내부에 있는지 확인
    let inside = false;
    let j = zonePoints.length - 1;
    
    for (let i = 0; i < zonePoints.length; i++) {
        const xi = zonePoints[i].x;
        const yi = zonePoints[i].y;
        const xj = zonePoints[j].x;
        const yj = zonePoints[j].y;
        
        console.log(`Ray casting step ${i}: edge from (${xj}, ${yj}) to (${xi}, ${yi})`);
        
        if (((yi > point.y) !== (yj > point.y))) {
            const intersectX = (xj - xi) * (point.y - yi) / (yj - yi) + xi;
            console.log(`  Y 조건 만족, 교차점 X: ${intersectX}, 점 X: ${point.x}`);
            
            if (point.x < intersectX) {
                inside = !inside;
                console.log(`  교차! inside 상태: ${inside}`);
            }
        }
        j = i;
    }
    
    console.log('🎯 Final result - Point in zone:', inside);
    return inside;
}

// 여백을 포함한 구역 체크 함수 (Fabric.js 내장 기능 사용)
function isPointInCurrentZoneWithMargin(point, margin = 10) {
    console.log('🔍 isPointInCurrentZoneWithMargin 호출됨:', {
        point: point,
        margin: margin,
        currentZoneObject: !!currentZoneObject,
        currentZoneSno: currentZoneSno
    });
    
    if (!currentZoneObject || !currentZoneSno) {
        console.log('❌ No current zone object or zone SNO');
        return false;
    }
    
    // Fabric.js의 내장 기능을 사용하여 점이 객체 내부에 있는지 확인
    try {
        // 구역 객체의 바운딩 박스 가져오기
        const boundingRect = currentZoneObject.getBoundingRect(true);
        console.log('📦 Zone bounding rect:', boundingRect);
        
        // 여백을 포함한 바운딩 박스
        const extendedRect = {
            left: boundingRect.left - margin,
            top: boundingRect.top - margin,
            width: boundingRect.width + (margin * 2),
            height: boundingRect.height + (margin * 2)
        };
        
        console.log('📦 Extended bounding rect:', extendedRect);
        console.log('🎯 Testing point:', point);
        
        // 확장된 바운딩 박스 체크
        const inExtendedBox = (
            point.x >= extendedRect.left &&
            point.x <= extendedRect.left + extendedRect.width &&
            point.y >= extendedRect.top &&
            point.y <= extendedRect.top + extendedRect.height
        );
        
        console.log('📍 Point in extended box:', inExtendedBox);
        
        if (inExtendedBox) {
            console.log('✅ Point inside extended bounding box');
            
            // Fabric.js의 containsPoint 메서드 사용 (더 정확함)
            const containsPoint = currentZoneObject.containsPoint(new fabric.Point(point.x, point.y));
            console.log('📍 Fabric.js containsPoint result:', containsPoint);
            
            if (containsPoint) {
                console.log('✅ Point exactly inside zone (Fabric.js)');
                return true;
            } else {
                console.log('⚠️ Point not exactly in zone but within margin, allowing...');
                return true; // 여백 내에 있으면 허용
            }
        }
        
        console.log('❌ Point outside extended bounding box');
        return false;
        
    } catch (error) {
        console.error('❌ Error in zone boundary check:', error);
        // 오류 발생 시 안전하게 허용
        console.log('⚠️ Error occurred, allowing point...');
        return true;
    }
}

// 두 점 사이의 각도 계산 (도 단위)
function getAngle(p1, p2) {
    return Math.atan2(p2.y - p1.y, p2.x - p1.x) * 180 / Math.PI;
}

// 각도와 거리를 기반으로 새로운 점 계산
function getPointFromAngleAndDistance(startPoint, angle, distance) {
    const radians = angle * Math.PI / 180;
    return {
        x: startPoint.x + distance * Math.cos(radians),
        y: startPoint.y + distance * Math.sin(radians)
    };
}

// 선과 수직/수평선의 교차점 계산
function findIntersectionPoint(p1, p2, referencePoint, isVertical) {
    // p1: 선의 시작점, p2: 선의 끝점
    // referencePoint: 기준점 (첫 점)
    // isVertical: true면 수직선, false면 수평선과의 교차점 계산

    // 선의 방향 벡터
    const dx = p2.x - p1.x;
    const dy = p2.y - p1.y;

    if (isVertical) {
        // 수직선과의 교차점
        if (Math.abs(dx) < 0.0001) return null; // 두 선이 평행한 경우

        // 교차점의 y좌표 계산
        const slope = dy / dx;
        const intersectY = slope * (referencePoint.x - p1.x) + p1.y;

        return {
            x: referencePoint.x,
            y: intersectY
        };
    } else {
        // 수평선과의 교차점
        if (Math.abs(dy) < 0.0001) return null; // 두 선이 평행한 경우

        // 교차점의 x좌표 계산
        const slope = dx / dy;
        const intersectX = slope * (referencePoint.y - p1.y) + p1.x;

        return {
            x: intersectX,
            y: referencePoint.y
        };
    }
}

// 교차점이 선분 범위 내에 있는지 확인
function isPointNearLine(point, lineStart, lineEnd, threshold) {
    const lineLength = Math.sqrt(
        Math.pow(lineEnd.x - lineStart.x, 2) + 
        Math.pow(lineEnd.y - lineStart.y, 2)
    );
    
    if (lineLength === 0) return false;

    // 점과 선의 거리 계산
    const t = (
        (point.x - lineStart.x) * (lineEnd.x - lineStart.x) + 
        (point.y - lineStart.y) * (lineEnd.y - lineStart.y)
    ) / (lineLength * lineLength);

    // 선분 외부의 점 제외
    if (t < -0.1 || t > 1.1) return false;

    // 점과 선의 수직 거리 계산
    const projX = lineStart.x + t * (lineEnd.x - lineStart.x);
    const projY = lineStart.y + t * (lineEnd.y - lineStart.y);
    
    const distance = Math.sqrt(
        Math.pow(point.x - projX, 2) + 
        Math.pow(point.y - projY, 2)
    );

    return distance < threshold;
}

// 가장 가까운 스냅 포인트 찾기
function findNearestIntersection(currentPoint, lastPoint, firstPoint) {
    const SNAP_THRESHOLD = 20;
    let allIntersections = [];

    // 마지막 점에 대한 기본 수직/수평 스냅
    if (lastPoint) {
        // 마지막 점 기준 수직/수평 방향 확인
        const isMovingVertically = Math.abs(currentPoint.x - lastPoint.x) < SNAP_THRESHOLD;
        const isMovingHorizontally = Math.abs(currentPoint.y - lastPoint.y) < SNAP_THRESHOLD;

        if (isMovingVertically) {
            // 수직 이동 중일 때는 x좌표를 마지막 점과 같게 유지
            const verticalPoint = {
                x: lastPoint.x,
                y: currentPoint.y,
                distance: Math.abs(currentPoint.x - lastPoint.x),
                isVertical: true
            };
            allIntersections.push(verticalPoint);

            // 수직선과 첫 점의 수평선의 교차점
            if (firstPoint) {
                const horizontalIntersect = {
                    x: lastPoint.x,
                    y: firstPoint.y,
                    distance: Math.sqrt(
                        Math.pow(currentPoint.x - lastPoint.x, 2) +
                        Math.pow(currentPoint.y - firstPoint.y, 2)
                    ),
                    isIntersection: true
                };
                if (Math.abs(currentPoint.y - firstPoint.y) < SNAP_THRESHOLD) {
                    allIntersections.push(horizontalIntersect);
                }
            }
        }

        if (isMovingHorizontally) {
            // 수평 이동 중일 때는 y좌표를 마지막 점과 같게 유지
            const horizontalPoint = {
                x: currentPoint.x,
                y: lastPoint.y,
                distance: Math.abs(currentPoint.y - lastPoint.y),
                isHorizontal: true
            };
            allIntersections.push(horizontalPoint);

            // 수평선과 첫 점의 수직선의 교차점
            if (firstPoint) {
                const verticalIntersect = {
                    x: firstPoint.x,
                    y: lastPoint.y,
                    distance: Math.sqrt(
                        Math.pow(currentPoint.x - firstPoint.x, 2) +
                        Math.pow(currentPoint.y - lastPoint.y, 2)
                    ),
                    isIntersection: true
                };
                if (Math.abs(currentPoint.x - firstPoint.x) < SNAP_THRESHOLD) {
                    allIntersections.push(verticalIntersect);
                }
            }
        }
    }

    // 교차점이 없으면 현재 점 반환
    if (allIntersections.length === 0) {
        return currentPoint;
    }

    // 교차점 우선, 그 다음 일반 스냅 포인트
    let bestPoint = currentPoint;
    let minDistance = Infinity;

    // 먼저 교차점 확인
    const intersectionPoints = allIntersections.filter(p => p.isIntersection);
    if (intersectionPoints.length > 0) {
        bestPoint = intersectionPoints.reduce((best, current) => 
            current.distance < best.distance ? current : best
        );
        return bestPoint;
    }

    // 교차점이 없으면 일반 스냅 포인트 중 가장 가까운 것 선택
    return allIntersections.reduce((best, current) => 
        current.distance < best.distance ? current : best
    );
}

// 전역 함수들 (jsinc.php의 alertToast 사용)
function showToast(message, type = 'success') {
    alertToast(type, message);
}



// 확인 대화상자 함수 (jsinc.php의 SweetAlert2 기반 ToastConfirm 사용)
function showConfirm(message, callback) {
    ToastConfirm.fire({
        icon: "question",
        title: "  확인 메세지",
        html: "<font color='#000000'>" + message + "</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

// 도면 이미지 로드 및 초기화
$(document).ready(function() {
    $('.drawing-container').each(function() {
        const container = $(this);
        const floorSno = container.data('floor');
        const imageUrl = container.data('image');
        
        console.log('Loading floor:', floorSno);
        console.log('Image URL:', imageUrl);
        
        if (!imageUrl) {
            console.error('No image URL found for floor:', floorSno);
            container.find('.loading-overlay').html('<div class="alert alert-danger">이미지 URL을 찾을 수 없습니다.</div>');
            return;
        }

        // 이미지 로드 시작
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.src = imageUrl;
        
        if (img.complete) {
            console.log('Image already loaded:', floorSno);
            initCanvas(floorSno, imageUrl).then(() => {
                console.log('Canvas initialized, loading zones for floor:', floorSno);
                loadZones(floorSno);
            });
        } else {
            console.log('Waiting for image to load:', floorSno);
            img.onload = function() {
                console.log('Image load complete:', floorSno);
                initCanvas(floorSno, imageUrl).then(() => {
                    console.log('Canvas initialized, loading zones for floor:', floorSno);
                    loadZones(floorSno);
                });
            };
            img.onerror = function(error) {
                console.error('Image load failed:', error);
                container.find('.loading-overlay').html('<div class="alert alert-danger">이미지 로드 실패</div>');
            };
        }
    });

    // 도면 업로드
    $('#floorForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?= site_url('locker/ajax_upload_floor') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    showToast(response.message || '도면이 성공적으로 업로드되었습니다.', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(response.message || '도면 업로드에 실패했습니다.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Upload Error:', error);
                showToast('도면 업로드 중 오류가 발생했습니다.', 'error');
            }
        });
    });
    
    // 편집 모드 토글 버튼 클릭 이벤트 (전역 이벤트 핸들러)
    $(document).on('click', '.toggle-edit-mode', function(e) {
        const button = $(this);
        const container = button.closest('.drawing-container');
        const floorSno = container.data('floor');
        const canvas = fabricCanvases[floorSno];
        
        if (!canvas) {
            console.error('No canvas found for floor:', floorSno);
            return;
        }
        
        // 현재 편집 모드 상태 토글
        const currentEditMode = editModeStates[floorSno] || false;
        const newEditMode = !currentEditMode;
        
        // 상태 업데이트
        editModeStates[floorSno] = newEditMode;
        
        if (newEditMode) {
            // 편집 모드 활성화
            button.removeClass('btn-outline-warning').addClass('btn-warning');
            button.attr('title', '편집 모드 종료');
            
            // 원래 상태 저장
            saveOriginalZoneStates(canvas, floorSno);
            
            // 모든 구역을 편집 가능하게 설정
            canvas.getObjects().forEach(function(obj) {
                if (obj.type === 'group' && obj.data && obj.data.zone_sno) {
                    obj.set({
                        selectable: true,
                        evented: true,
                        hasControls: true,
                        hasBorders: true,
                        lockMovementX: false,
                        lockMovementY: false,
                        lockRotation: false,
                        lockScalingX: false,
                        lockScalingY: false
                    });
                }
            });
            
            // 캔버스 설정
            canvas.selection = true;
            canvas.interactive = true;
            
            console.log('Edit mode enabled for floor:', floorSno);
        } else {
            // 편집 모드 비활성화 시 변경내역 확인
            checkAndHandleChanges(canvas, floorSno, button);
        }
        
        canvas.renderAll();
    });
    
    // 페이지 로드 완료 후 모든 캔버스의 객체에 strokeUniform 적용 및 캔버스 영역 체크
    setTimeout(() => {
        console.log('Applying strokeUniform to all canvases after page load');
        Object.values(fabricCanvases).forEach(canvas => {
            if (canvas) {
                updateExistingObjectsStrokeUniform(canvas);
                
                // 로드 완료 후 캔버스 영역 체크 (도면 크기는 고정 유지)
                const canvasElement = canvas.getElement();
                const container = $(canvasElement).closest('.drawing-container');
                if (container.is(':visible')) {
                    const floorSno = container.data('floor');
                    resizeCanvasArea(canvas, floorSno);
                }
            }
        });
    }, 2000); // 2초 후 실행 (모든 로딩이 완료된 후)
});

// Canvas 초기화
function initCanvas(floorSno, imageUrl) {
    return new Promise((resolve, reject) => {
        try {
            const canvasElement = document.getElementById('canvas_' + floorSno);
            const container = $(canvasElement).closest('.drawing-container');
            const containerWidth = container.width();
            const containerHeight = container.height();

            // Canvas 크기 설정
            canvasElement.width = containerWidth;
            canvasElement.height = containerHeight;

            // Fabric.js Canvas 초기화
            const canvas = new fabric.Canvas(canvasElement, {
                preserveObjectStacking: true,
                selection: true
            });

            // 이미지 로드
            fabric.Image.fromURL(imageUrl, function(img) {
                // 캔버스 컨테이너의 최대 가능한 크기 계산
                const viewport = {
                    width: window.innerWidth,
                    height: window.innerHeight
                };
                
                // 페이지의 여백과 헤더 등을 고려한 캔버스 최대 가능 크기
                // 좌측 여백(10px) + 우측 여백(10px) + 기타 UI 요소들을 고려
                const MAX_CANVAS_WIDTH = viewport.width - 40;  // 40px 여백
                const MAX_CANVAS_HEIGHT = viewport.height - 200; // 200px (헤더, 푸터, 기타 UI)
                
                // 도면이 캔버스 최대 크기에 맞도록 스케일 계산 (잘리지 않게)
                const fixedScale = Math.min(
                    MAX_CANVAS_WIDTH / img.width,
                    MAX_CANVAS_HEIGHT / img.height
                );
                
                console.log(`🎯 캔버스 최대 크기 기준 도면 스케일 설정:`);
                console.log(`   - 뷰포트 크기: ${viewport.width}x${viewport.height}`);
                console.log(`   - 캔버스 최대 크기: ${MAX_CANVAS_WIDTH}x${MAX_CANVAS_HEIGHT}`);
                console.log(`   - 원본 이미지 크기: ${img.width}x${img.height}`);
                console.log(`   - 계산된 스케일: ${fixedScale}`);
                console.log(`   - 최종 도면 크기: ${Math.round(img.width * fixedScale)}x${Math.round(img.height * fixedScale)}`);

                // 이미지 원본 정보와 고정 스케일 저장
                canvas.originalImageWidth = img.width;
                canvas.originalImageHeight = img.height;
                canvas.fixedImageScale = fixedScale;  // 캔버스 최대 크기 기준으로 계산된 고정 스케일

                img.set({
                    scaleX: fixedScale,
                    scaleY: fixedScale,
                    selectable: false
                });

                // 이미지를 고정 위치에 배치 (위치 변경 방지)
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    originX: 'left',
                    originY: 'top',
                    left: 0,  // 고정 위치
                    top: 0    // 고정 위치
                });

                // 캔버스 저장
                fabricCanvases[floorSno] = canvas;

                // 윈도우 리사이즈 이벤트 리스너 추가 (캔버스 크기만 조정, 도면 크기는 고정)
                setupCanvasResizeHandler(canvas, floorSno);

                // 로딩 오버레이 숨기기
                container.find('.loading-overlay').hide();

                // 이벤트 핸들러 설정
                setupCanvasEventHandlers(canvas);

                console.log('Canvas initialization complete for floor:', floorSno);
                resolve(canvas);
            }, { crossOrigin: 'anonymous' });

        } catch (error) {
            console.error('Error initializing canvas:', error);
            reject(error);
        }
    });
}

// 캔버스 리사이즈 핸들러 설정 (도면 크기는 고정, 캔버스 영역만 조정)
function setupCanvasResizeHandler(canvas, floorSno) {
    let resizeTimeout;
    
    const handleResize = () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            resizeCanvasArea(canvas, floorSno);
        }, 100); // 100ms 디바운스
    };
    
    // 윈도우 리사이즈 이벤트 리스너
    $(window).on(`resize.canvas_${floorSno}`, handleResize);
    
    // 캔버스가 제거될 때 이벤트 리스너도 정리
    canvas.on('canvas:disposed', () => {
        $(window).off(`resize.canvas_${floorSno}`);
        clearTimeout(resizeTimeout);
    });
}

// 캔버스 영역만 리사이즈 (도면 크기/줌 레벨은 유지)
function resizeCanvasArea(canvas, floorSno) {
    try {
        const canvasElement = canvas.getElement();
        const container = $(canvasElement).closest('.drawing-container');
        
        // 현재 컨테이너가 보이지 않으면 리사이즈 안함
        if (!container.is(':visible')) {
            return;
        }
        
        const newContainerWidth = container.width();
        const newContainerHeight = container.height();
        
        // 크기가 변경되지 않았으면 처리 안함
        if (newContainerWidth === canvas.width && newContainerHeight === canvas.height) {
            return;
        }
        
        console.log(`🔄 캔버스 영역 리사이즈: ${canvas.width}x${canvas.height} → ${newContainerWidth}x${newContainerHeight}`);
        console.log(`📐 도면 크기는 최대 캔버스 크기 기준으로 고정 유지 (스케일: ${canvas.fixedImageScale})`);
        
        // 현재 뷰포트 상태 저장 (줌과 팬 상태를 그대로 유지)
        const currentZoom = canvas.getZoom();
        const currentVpt = canvas.viewportTransform.slice();
        
        // 캔버스 영역 크기만 업데이트 (도면 크기는 최대 캔버스 기준으로 고정)
        canvas.setDimensions({
            width: newContainerWidth,
            height: newContainerHeight
        });
        
        // 뷰포트 변환 상태 복원 (줌과 팬 위치 그대로 유지)
        canvas.setViewportTransform(currentVpt);
        
        // 배경 이미지는 최대 캔버스 크기 기준 고정 스케일 유지
        const backgroundImage = canvas.backgroundImage;
        if (backgroundImage && canvas.fixedImageScale) {
            // 도면 크기를 최대 캔버스 크기 기준으로 고정 (캔버스가 작아져도 도면 크기는 유지)
            backgroundImage.set({
                scaleX: canvas.fixedImageScale,
                scaleY: canvas.fixedImageScale
            });
            
            console.log(`✅ 도면 크기 고정 유지: ${Math.round(canvas.originalImageWidth * canvas.fixedImageScale)}x${Math.round(canvas.originalImageHeight * canvas.fixedImageScale)}`);
        }
        
        // 캔버스 다시 그리기
        canvas.renderAll();
        
        console.log('✅ 캔버스 영역 리사이즈 완료 (도면 크기는 최대 캔버스 크기 기준으로 고정 유지)');
        
    } catch (error) {
        console.error('캔버스 영역 리사이즈 중 오류:', error);
    }
}

// 활성 캔버스 찾기 함수 (개선된 버전)
function getActiveCanvas() {
    // 1순위: 현재 포커스된 캔버스 요소
    const focusedCanvas = $('canvas:focus').first();
    if (focusedCanvas.length > 0) {
        const canvasId = focusedCanvas.attr('id');
        console.log('🔍 포커스된 캔버스 ID:', canvasId);
        
        if (canvasId && canvasId.includes('canvas_')) {
            const floorSno = canvasId.replace('canvas_', '');
            console.log('🔍 추출된 floorSno:', floorSno);
            
            if (fabricCanvases[floorSno]) {
                console.log('🎯 포커스된 캔버스 발견:', floorSno);
                return fabricCanvases[floorSno];
            } else {
                console.log('⚠️ fabricCanvases에서 floorSno를 찾을 수 없음:', floorSno);
            }
        } else {
            console.log('⚠️ 캔버스 ID가 예상 형태가 아님:', canvasId);
            
            // 대안: drawing-container로부터 floor 정보 추출
            const container = focusedCanvas.closest('.drawing-container');
            if (container.length > 0) {
                const floorSno = container.data('floor');
                console.log('🔍 컨테이너에서 추출된 floorSno:', floorSno);
                if (floorSno && fabricCanvases[floorSno]) {
                    console.log('🎯 컨테이너 방식으로 캔버스 발견:', floorSno);
                    return fabricCanvases[floorSno];
                }
            }
        }
    }
    
    // 2순위: 마우스가 올라간 캔버스 요소
    const hoveredCanvas = $('canvas:hover').first();
    if (hoveredCanvas.length > 0) {
        const canvasId = hoveredCanvas.attr('id');
        console.log('🔍 마우스 오버된 캔버스 ID:', canvasId);
        
        if (canvasId && canvasId.includes('canvas_')) {
            const floorSno = canvasId.replace('canvas_', '');
            console.log('🔍 추출된 floorSno:', floorSno);
            
            if (fabricCanvases[floorSno]) {
                console.log('🎯 마우스 오버된 캔버스 발견:', floorSno);
                return fabricCanvases[floorSno];
            } else {
                console.log('⚠️ fabricCanvases에서 floorSno를 찾을 수 없음:', floorSno);
                console.log('📊 사용 가능한 fabricCanvases 키들:', Object.keys(fabricCanvases));
            }
        } else {
            console.log('⚠️ 캔버스 ID가 예상 형태가 아님:', canvasId);
            
            // 대안: drawing-container로부터 floor 정보 추출
            const container = hoveredCanvas.closest('.drawing-container');
            if (container.length > 0) {
                const floorSno = container.data('floor');
                console.log('🔍 컨테이너에서 추출된 floorSno:', floorSno);
                if (floorSno && fabricCanvases[floorSno]) {
                    console.log('🎯 컨테이너 방식으로 캔버스 발견:', floorSno);
                    return fabricCanvases[floorSno];
                }
            }
        }
    }
    
    // 3순위: 현재 선택된 탭/액티브 상태인 캔버스
    const activeTab = $('.nav-tabs .nav-link.active').first();
    if (activeTab.length > 0) {
        const targetId = activeTab.attr('href') || activeTab.data('bs-target');
        if (targetId) {
            const tabPane = $(targetId);
            const canvas = tabPane.find('canvas').first();
            if (canvas.length > 0) {
                const canvasId = canvas.attr('id');
                console.log('🔍 활성 탭의 캔버스 ID:', canvasId);
                
                if (canvasId && canvasId.includes('canvas_')) {
                    const floorSno = canvasId.replace('canvas_', '');
                    console.log('🔍 추출된 floorSno:', floorSno);
                    
                    if (fabricCanvases[floorSno]) {
                        console.log('🎯 활성 탭의 캔버스 발견:', floorSno);
                        return fabricCanvases[floorSno];
                    } else {
                        console.log('⚠️ fabricCanvases에서 floorSno를 찾을 수 없음:', floorSno);
                    }
                } else {
                    console.log('⚠️ 캔버스 ID가 예상 형태가 아님:', canvasId);
                    
                    // 대안: drawing-container로부터 floor 정보 추출
                    const container = canvas.closest('.drawing-container');
                    if (container.length > 0) {
                        const floorSno = container.data('floor');
                        console.log('🔍 컨테이너에서 추출된 floorSno:', floorSno);
                        if (floorSno && fabricCanvases[floorSno]) {
                            console.log('🎯 컨테이너 방식으로 캔버스 발견:', floorSno);
                            return fabricCanvases[floorSno];
                        }
                    }
                }
            }
        }
    }
    
    // 4순위: 보이는 상태인 캔버스 중 가장 최근에 상호작용한 것
    let bestCanvas = null;
    let latestInteraction = 0;
    
    Object.keys(fabricCanvases).forEach(floorSno => {
        const canvas = fabricCanvases[floorSno];
        const container = $(canvas.wrapperEl).closest('.drawing-container');
        
        if (container.is(':visible')) {
            // 캔버스별 최근 상호작용 시간 체크
            const lastInteraction = canvas._lastInteractionTime || 0;
            if (lastInteraction > latestInteraction) {
                latestInteraction = lastInteraction;
                bestCanvas = canvas;
            }
        }
    });
    
    if (bestCanvas) {
        const floorSno = $(bestCanvas.wrapperEl).closest('.drawing-container').data('floor');
        console.log('🎯 최근 상호작용 캔버스 발견:', floorSno);
        return bestCanvas;
    }
    
    // 5순위: 첫 번째 보이는 캔버스 (기존 방식)
    const fallbackCanvas = Object.values(fabricCanvases).find(c => 
        $(c.wrapperEl).closest('.drawing-container').is(':visible')
    );
    
    if (fallbackCanvas) {
        const floorSno = $(fallbackCanvas.wrapperEl).closest('.drawing-container').data('floor');
        console.log('🎯 폴백 캔버스 사용:', floorSno);
    }
    
    return fallbackCanvas || null;
}

// 캔버스 상호작용 시간 업데이트
function updateCanvasInteractionTime(canvas) {
    if (canvas) {
        canvas._lastInteractionTime = Date.now();
    }
}

// Fabric.js _renderStroke 메서드 오버라이드로 선 두께 고정
(function() {
    // 원본 _renderStroke 메서드 백업
    const originalRenderStroke = fabric.Object.prototype._renderStroke;
    
    // _renderStroke 메서드 오버라이드
    fabric.Object.prototype._renderStroke = function(ctx) {
        if (!this.stroke || this.strokeWidth === 0) {
            return;
        }
        
        if (this.shadow && !this.shadow.affectStroke) {
            this._removeShadow(ctx);
        }
        
        ctx.save();
        
        // 캔버스 줌 레벨 가져오기
        const canvas = this.canvas;
        if (canvas) {
            const zoom = canvas.getZoom();
            // 캔버스 줌의 역수로 스케일링하여 선 두께를 시각적으로 고정
            ctx.scale(1 / zoom, 1 / zoom);
        }
        
        this._setLineDash(ctx, this.strokeDashArray);
        this._setStrokeStyles(ctx, this);
        ctx.stroke();
        ctx.restore();
    };
    
    console.log('Fabric.js _renderStroke method overridden for fixed stroke width');
})();

// 백업용: 줌 레벨에 따른 선 두께 조정 함수 (더 이상 필요하지 않지만 보관)
function updateStrokeWidthForZoom(canvas, zoom) {
    // _renderStroke 오버라이드로 인해 더 이상 필요하지 않음
    // 하지만 특수한 경우를 위해 보관
    console.log('updateStrokeWidthForZoom called but not needed due to _renderStroke override');
}

// 기존 객체들에 strokeUniform 속성 적용하는 함수
function updateExistingObjectsStrokeUniform(canvas) {
    console.log('Updating existing objects with strokeUniform property');
    
    canvas.getObjects().forEach(function(obj) {
        if (obj.type === 'group') {
            // 그룹 내부의 모든 객체 처리
            if (obj._objects && obj._objects.length > 0) {
                obj._objects.forEach(function(innerObj) {
                    if (innerObj.stroke && innerObj.strokeWidth !== undefined) {
                        innerObj.set('strokeUniform', true);
                        console.log('Applied strokeUniform to inner object:', innerObj.type);
                    }
                });
            }
        } else if (obj.stroke && obj.strokeWidth !== undefined) {
            // 개별 객체 처리
            obj.set('strokeUniform', true);
            console.log('Applied strokeUniform to object:', obj.type);
        }
    });
    
    canvas.renderAll();
    console.log('Finished updating existing objects with strokeUniform');
}

// 저장된 구역 로드
function loadZones(floorSno) {
    console.log('Loading zones for floor:', floorSno);
    
    if (!fabricCanvases[floorSno]) {
        console.error('Canvas not initialized for floor:', floorSno);
        return;
    }

    const canvas = fabricCanvases[floorSno];
    
    // 기존 구역 삭제
    const existingObjects = canvas.getObjects().filter(obj => 
        obj.type === 'group' || obj.type === 'polygon' || (obj.type === 'text' && obj.zoneText)
    );
    existingObjects.forEach(obj => canvas.remove(obj));

    // 성별에 따른 색상 정의
    const colors = {
        'F': {  // 여성
            fill: 'rgba(255,182,193,0.2)',    // 연한 분홍색
            stroke: '#FF69B4',                 // 진한 분홍색
            selectedStroke: '#FF1493'          // 선택시 더 진한 분홍색
        },
        'M': {  // 남성
            fill: 'rgba(135,206,235,0.2)',    // 연한 파란색
            stroke: '#4169E1',                 // 진한 파란색
            selectedStroke: '#0000FF'          // 선택시 더 진한 파란색
        },
        'C': {  // 혼용(공용)
            fill: 'rgba(255,223,0,0.3)',      // 개나리색
            stroke: '#FFD700',                 // 개나리색
            selectedStroke: '#FFA500'          // 선택시 더 진한 개나리색
        }
    };

    // 기본 색상 (성별이 지정되지 않은 경우)
    const defaultColors = {
        fill: 'rgba(192,192,192,0.2)',        // 연한 회색
        stroke: '#808080',                     // 진한 회색
        selectedStroke: '#404040'              // 선택시 더 진한 회색
    };

    $.ajax({
        url: '<?= site_url('locker/ajax_get_zones') ?>',
        type: 'GET',
        data: { 
            floor_sno: floorSno
        },
        dataType: 'json',
        beforeSend: function(xhr) {
            console.log('Sending request for floor:', floorSno);
            console.log('Request URL:', this.url + '?' + $.param(this.data));
        },
        success: function(response) {
            console.log('Raw response:', response);
            
            if (!response) {
                console.error('Empty response received');
                return;
            }

            let zones = response.zones;
            if (!zones && response.status && response.status.zones) {
                zones = response.status.zones;
            }

            if (Array.isArray(zones) && zones.length > 0) {
                console.log(`Found ${zones.length} zones to render`);
                
                zones.forEach((zone, index) => {
                    try {
                        console.log(`Processing zone ${index + 1}/${zones.length}:`, zone);
                        
                        // addZoneToCanvas 함수를 사용하여 일관된 방식으로 구역 추가
                        const group = addZoneToCanvas(canvas, zone);
                        if (group) {
                            console.log(`Zone ${index + 1} added successfully:`, zone.zone_nm);
                        } else {
                            console.error(`Failed to add zone ${index + 1}:`, zone.zone_nm);
                        }
                    } catch (error) {
                        console.error(`Error processing zone ${index + 1}:`, error);
                    }
                });

                canvas.renderAll();
                
                // 기존 객체들에 strokeUniform 속성 적용
                updateExistingObjectsStrokeUniform(canvas);
                
                // _renderStroke 오버라이드로 인해 별도의 선 두께 설정 불필요
                console.log('All zones rendered successfully');
                
                // ✅ 각 구역별로 락커 그룹들을 자동 로드 (리프레시 시 올바른 층에 표시)
                console.log(`🔄 ${floorSno}층의 락커 그룹들을 자동 로드합니다...`);
                zones.forEach((zone) => {
                    console.log(`📍 구역 ${zone.zone_sno}의 락커 그룹 로드 중...`);
                    loadLockerGroupsForZone(canvas, zone.zone_sno);
                });
                console.log(`✅ ${floorSno}층의 모든 락커 그룹 로드 요청 완료`);
            } else {
                console.log('No zones found in response:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to load zones');
            console.log('Status:', status);
            console.log('Error:', error);
            console.log('Response:', xhr.responseText);
            try {
                const errorResponse = JSON.parse(xhr.responseText);
                console.log('Parsed error response:', errorResponse);
            } catch (e) {
                console.log('Raw error response:', xhr.responseText);
            }
        }
    });
}

// 캔버스 이벤트 핸들러 설정
function setupCanvasEventHandlers(canvas) {
    let isDrawing = false;
    let currentPoints = [];
    let currentPolygon = null;
    let firstPointMarker = null;
    let tempLine = null;
    let isPanning = false;
    let lastPosX;
    let lastPosY;

    // 캔버스 초기화 설정
    canvas.selection = true;

    // 그리기 시작/취소
    $('.draw-zone').on('click', function() {
        const canvasContainer = $(canvas.wrapperEl).closest('.drawing-container');
        const currentFloorSno = canvasContainer.data('floor');
        const isEditMode = editModeStates[currentFloorSno] || false;
        
        // 편집 모드가 활성화되어 있으면 편집 모드 종료 후 드로잉 시작
        if (isEditMode) {
            const editButton = canvasContainer.find('.toggle-edit-mode');
            // 편집 모드 즉시 종료 (변경사항 확인 없이)
            disableEditMode(canvas, currentFloorSno, editButton);
            // 편집 모드 종료 후 드로잉 모드 시작
        }
        
        // 이미 드로잉 모드라면 드로잉 취소
        if (isDrawing) {
            cancelDrawing(canvas);
            $(this).removeClass('active');
            return;
        }
        
        // 드로잉 모드 시작
        isDrawing = true;
        currentPoints = [];
        $(this).addClass('active');
        canvas.selection = false;
        canvas.discardActiveObject();
        canvas.renderAll();
        
        console.log('Drawing mode started');
    });

    // Del 키를 통한 락커 그룹 삭제 기능 추가
    canvas.on('selection:created', function() {
        // 캔버스 상호작용 시간 업데이트
        updateCanvasInteractionTime(canvas);
        
        // 객체가 선택되었을 때 키보드 이벤트 리스너 활성화
        $(document).on('keydown.lockerGroup', function(e) {
            if (e.key === 'Delete' || e.which === 46) {
                const activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.data && activeObject.data.type === 'locker-group') {
                    e.preventDefault();
                    console.log('🔑 Del 키로 락커 그룹 삭제 요청:', activeObject.data.groupName);
                    
                    // 락커 그룹 추가 모드에서만 Del 키 삭제 허용
                    if (!isLockerGroupMode) {
                        showToast('락커 그룹 삭제는 락커 그룹 추가 모드에서만 가능합니다.', 'warning', 3000);
                        return;
                    }
                    
                    // 직접 삭제 함수 호출
                    const groupName = activeObject.data.groupName;
                    const groupSno = activeObject.data.groupSno || activeObject.data.group_sno;
                    
                    if (confirm(`"${groupName}" 락커 그룹을 삭제하시겠습니까?\n\n⚠️ 이 작업은 되돌릴 수 없습니다.`)) {
                        performLockerGroupDeletion(activeObject, groupName, groupSno, canvas);
                    }
                }
            }
        });
    });

    canvas.on('selection:cleared', function() {
        // 캔버스 상호작용 시간 업데이트
        updateCanvasInteractionTime(canvas);
        
        // 선택이 해제되었을 때 키보드 이벤트 리스너 제거
        $(document).off('keydown.lockerGroup');
    });

    // 마우스 다운 이벤트
    canvas.on('mouse:down', function(opt) {
        const evt = opt.e;
        
        // 캔버스 상호작용 시간 업데이트
        updateCanvasInteractionTime(canvas);
        
        if (evt.button === 0) {  // 좌클릭
            if (evt.altKey) {
                // 락커 그룹 추가 모드일 때는 패닝 제한
                if (isLockerGroupMode) {
                    showToast('락커 그룹 추가 모드에서는 지도 이동이 제한됩니다', 'warning', 2000);
                    return;
                }
                
                isPanning = true;
                canvas.selection = false;
                lastPosX = evt.clientX;
                lastPosY = evt.clientY;
                return;
            }

            // 락커 그룹 그리기 모드 처리
            if (isDrawingLockerGroup) {
                console.log('🎯 락커 그룹 그리기 모드에서 마우스 클릭 감지됨');
                
                // 이미 2개 포인트로 락커 그룹이 생성된 경우 추가 클릭 무시
                if (lockerGroupPoints.length >= 2) {
                    console.log('⚠️ 락커 그룹이 이미 생성됨 - 추가 클릭 무시');
                    return;
                }
                
                // 캔버스 변환 정보 로그
                console.log('Canvas zoom level:', canvas.getZoom());
                console.log('Viewport transform:', canvas.viewportTransform);
                
                // canvas.getPointer()는 이미 뷰포트 변환이 적용된 좌표를 반환하므로 직접 사용
                const pointer = canvas.getPointer(evt);
                console.log('Pointer (already viewport transformed):', pointer);
                
                console.log('Calculated actual pointer:', pointer);
                console.log('Current isDrawingLockerGroup:', isDrawingLockerGroup);
                console.log('Current lockerGroupPoints length:', lockerGroupPoints.length);
                
                // 현재 구역 경계 내에 있는지 확인 (여백 포함)
                // 디버깅을 위해 경계 체크를 임시로 비활성화할 수 있습니다
                const DISABLE_BOUNDARY_CHECK = false; // true로 설정하면 경계 체크 비활성화
                
                if (!DISABLE_BOUNDARY_CHECK) {
                    const isInZone = isPointInCurrentZoneWithMargin(pointer, 20); // 20픽셀 여백으로 증가
                    console.log('Point in zone check result:', isInZone);
                    
                    if (!isInZone) {
                        console.log('Point outside zone (including margin), blocking action');
                        showToast('락커 그룹은 현재 구역 내에서만 그릴 수 있습니다.', 'warning');
                        return;
                    }
                } else {
                    console.log('⚠️ 경계 체크가 비활성화되었습니다 (디버깅 모드)');
                }
                
                lockerGroupPoints.push({ x: pointer.x, y: pointer.y });
                console.log('Point added to lockerGroupPoints:', { x: pointer.x, y: pointer.y });
                
                if (lockerGroupPoints.length === 1) {
                    showToast('✅ 시작점 설정 완료! 📍 2단계: 방향과 크기를 결정할 끝점을 클릭하세요', 'success', 5000);
                    
                    // 확대된 화면에서 적당한 크기로 보이도록 줌 레벨을 고려한 점 크기 계산
                    const currentZoom = canvas.getZoom();
                    const targetRadius = 3 / currentZoom; // 실제 화면에서 3px 반지름으로 보이도록
                    const targetStrokeWidth = 1 / currentZoom; // 실제 화면에서 1px 테두리로 보이도록
                    
                    console.log(`Point - Current zoom: ${currentZoom}, Target radius: ${targetRadius}, Target strokeWidth: ${targetStrokeWidth}`);
                    
                    // 첫 번째 점 시각화
                    const dot = new fabric.Circle({
                        left: pointer.x,
                        top: pointer.y,
                        radius: targetRadius,
                        fill: '#ff4444',
                        stroke: '#ffffff',
                        strokeWidth: targetStrokeWidth,
                        strokeUniform: true, // 스케일링 시 선 두께 고정
                        originX: 'center',
                        originY: 'center',
                        selectable: false,
                        evented: false,
                        data: { type: 'locker-group-temp' }
                    });
                    canvas.add(dot);
                    canvas.renderAll();
                    
                } else if (lockerGroupPoints.length === 2) {
                    console.log('두 번째 점 클릭 완료, 락커 그룹 생성 시작');
                    showToast('🎉 락커 그룹이 생성됩니다...', 'success', 2000);
                    // 두 점이 모두 클릭되면 락커 그룹 생성
                    try {
                        createLockerGroup();
                    } catch (error) {
                        console.error('락커 그룹 생성 중 오류:', error);
                        showToast('락커 그룹 생성 중 오류가 발생했습니다.', 'error');
                    }
                }
                return;
            }

            if (!isDrawing) return;

            // 확대된 상태에서도 정확한 좌표 계산
            const rawPointer = canvas.getPointer(evt);
            console.log('Raw pointer (with viewport transform):', rawPointer);
            
            // 뷰포트 변환을 고려한 실제 캔버스 좌표 계산
            const zoom = canvas.getZoom();
            const vpt = canvas.viewportTransform;
            const actualX = (rawPointer.x - vpt[4]) / zoom;
            const actualY = (rawPointer.y - vpt[5]) / zoom;
            let point = { x: actualX, y: actualY };
            
            console.log('Calculated actual point for zone drawing:', point);

            if (currentPoints.length > 0) {
                const lastPoint = currentPoints[currentPoints.length - 1];
                const firstPoint = currentPoints[0];
                
                // 수직/수평 스냅과 교차점 스냅 적용
                point = findNearestIntersection(point, lastPoint, firstPoint);
            }

            currentPoints.push(point);

            if (currentPolygon) {
                canvas.remove(currentPolygon);
            }
            if (currentPoints.length > 1) {
                currentPolygon = new fabric.Polygon(currentPoints, {
                    fill: 'rgba(0,0,255,0.2)',
                    stroke: 'blue',
                    strokeWidth: 2,
                    strokeUniform: true, // 스케일링 시 선 두께 고정
                    selectable: false
                });
                canvas.add(currentPolygon);
            }
            canvas.renderAll();
        }
    });

    // 마우스 업 이벤트
    canvas.on('mouse:up', function(opt) {
        // 캔버스 상호작용 시간 업데이트
        updateCanvasInteractionTime(canvas);
        
        if (isPanning) {
            isPanning = false;
            canvas.selection = true;
        }
    });

    // 마우스 이동 이벤트
    canvas.on('mouse:move', function(opt) {
        const evt = opt.e;
        
        // 마우스 이동도 상호작용으로 간주 (패닝이나 그리기 중일 때만)
        if (isPanning || isDrawing) {
            updateCanvasInteractionTime(canvas);
        }
        
        // 패닝 중일 때만 이동
        if (isPanning && evt.buttons === 1) {  // 마우스 버튼이 눌려있을 때만
            const deltaX = evt.clientX - lastPosX;
            const deltaY = evt.clientY - lastPosY;
            
            canvas.relativePan(new fabric.Point(deltaX, deltaY));
            
            lastPosX = evt.clientX;
            lastPosY = evt.clientY;
        }
        
        // 그리기 모드일 때 임시 선 그리기
        if (isDrawing && currentPoints.length > 0) {
            // 확대된 상태에서도 정확한 좌표 계산
            const rawPointer = canvas.getPointer(evt);
            const zoom = canvas.getZoom();
            const vpt = canvas.viewportTransform;
            const actualX = (rawPointer.x - vpt[4]) / zoom;
            const actualY = (rawPointer.y - vpt[5]) / zoom;
            let currentPoint = { x: actualX, y: actualY };
            
            const lastPoint = currentPoints[currentPoints.length - 1];
            const firstPoint = currentPoints[0];

            // 수직/수평 스냅과 교차점 스냅 적용
            currentPoint = findNearestIntersection(currentPoint, lastPoint, firstPoint);

            if (tempLine) {
                canvas.remove(tempLine);
            }
            tempLine = new fabric.Line([
                lastPoint.x,
                lastPoint.y,
                currentPoint.x,
                currentPoint.y
            ], {
                stroke: 'blue',
                strokeWidth: 2,
                strokeDashArray: [5, 5],
                strokeUniform: true, // 스케일링 시 선 두께 고정
                selectable: false
            });
            canvas.add(tempLine);
            if (currentPolygon) {
                currentPolygon.bringToFront();
            }
            canvas.renderAll();
        }
    });

    // 우클릭 메뉴 방지 및 이벤트 처리
    canvas.upperCanvasEl.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        
        if (isDrawing && currentPoints.length >= 3) {
            // 그리기 모드 종료
            isDrawing = false;
            canvas.selection = true;
            canvas.defaultCursor = 'default';
            
            // 임시 선 제거
            if (tempLine) {
                canvas.remove(tempLine);
                tempLine = null;
            }
            
            // 저장 팝업 표시
            showZoneSavePopup(currentPoints);
        }
        
        return false;
    }, false);

    // 키보드 이벤트
    $(document).on('keydown', function(e) {
        // 모달이 열려있는지 확인
        const isModalOpen = $('.modal.show').length > 0;
        
        // 텍스트 입력 필드에 포커스가 있는지 확인
        const isTextInputFocused = $('input:focus, textarea:focus, select:focus, [contenteditable="true"]:focus').length > 0;
        
        // 모달이 열려있거나 텍스트 입력 필드에 포커스가 있으면 기본 동작 허용
        if (isModalOpen || isTextInputFocused) {
            // ESC 키만 모달 닫기용으로 처리
            if (e.key === 'Escape' && isModalOpen) {
                // Bootstrap 모달 ESC 기능을 그대로 사용
                return;
            }
            // 나머지 키는 기본 동작 허용 (텍스트 입력 등)
            return;
        }
        
        // ESC 키
        if (e.key === 'Escape') {
            // 1순위: 락커 그룹 그리기 중이면 취소 (락커그룹 모드는 유지)
            if (isDrawingLockerGroup) {
                cancelLockerGroupDrawing();
                return;
            }
            
            // 현재 활성화된 캔버스 찾기
            const activeCanvas = getActiveCanvas();
            
            if (activeCanvas) {
                const canvasContainer = $(activeCanvas.wrapperEl).closest('.drawing-container');
                const currentFloorSno = canvasContainer.data('floor');
                const isEditMode = editModeStates[currentFloorSno] || false;
                
                // 2순위: 편집 모드가 활성화되어 있으면 편집 모드 종료 (변경사항 확인 없이)
                if (isEditMode) {
                    const editButton = canvasContainer.find('.toggle-edit-mode');
                    disableEditMode(activeCanvas, currentFloorSno, editButton);
                    return;
                }
                
                // 3순위: 드로잉 모드가 활성화되어 있으면 드로잉 취소
                if (isDrawing) {
                    cancelDrawing(activeCanvas);
                    canvasContainer.find('.draw-zone').removeClass('active');
                    return;
                }
            }
            return;
        }

        // Delete 키 - 모달이 열려있지 않고 텍스트 입력 필드에 포커스가 없을 때만 처리
        if (e.key === 'Delete' || e.key === 'Backspace') {
            e.preventDefault();
            e.stopPropagation();

            // 1순위: 드로잉 중일 때는 마지막 점 삭제
            if (isDrawing && currentPoints.length > 0) {
                currentPoints.pop();
                if (currentPolygon) {
                    canvas.remove(currentPolygon);
                }
                if (currentPoints.length > 1) {
                    currentPolygon = new fabric.Polygon(currentPoints, {
                        fill: 'rgba(0,0,255,0.2)',
                        stroke: 'blue',
                        strokeWidth: 2,
                        selectable: false
                    });
                    canvas.add(currentPolygon);
                }
                if (tempLine) {
                    canvas.remove(tempLine);
                    tempLine = null;
                }
                canvas.renderAll();
            } 
            // 2순위: 드로잉은 끝났지만 저장되지 않은 임시 폴리곤이 있을 때 드로잉 취소
            else if (currentPolygon && !isDrawing) {
                console.log('Cancelling unsaved drawing');
                // 현재 활성화된 캔버스 찾기
                const activeCanvas = getActiveCanvas();
                if (activeCanvas) {
                    cancelDrawing(activeCanvas);
                    const canvasContainer = $(activeCanvas.wrapperEl).closest('.drawing-container');
                    canvasContainer.find('.draw-zone').removeClass('active');
                }
            }
            // 3순위: 편집 모드에서 구역 삭제
            else {
                const canvasContainer = $(canvas.wrapperEl).closest('.drawing-container');
                const currentFloorSno = canvasContainer.data('floor');
                const isEditMode = editModeStates[currentFloorSno] || false;
                
                if (isEditMode) {
                    const activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'group' && activeObject.data && activeObject.data.zone_sno) {
                    // 구역 삭제 확인
                    const zoneName = activeObject.data.zone_nm || '구역';
                    
                    // 기본 삭제 확인 (락커 확인 기능은 서버 엔드포인트 준비 후 활성화)
                    const confirmMessage = `구역 "${zoneName}"을(를) 삭제하시겠습니까?`;
                    
                    showConfirm(confirmMessage, function() {
                        deleteSelectedZone(activeObject);
                    });
                    
                    /* TODO: 서버 엔드포인트 준비 후 활성화
                    // 락커 그룹 존재 여부 확인
                    $.ajax({
                        url: '/locker/ajax_check_zone_lockers',
                        type: 'POST',
                        data: {
                            zone_sno: activeObject.data.zone_sno,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                let confirmMessage = `구역 "${zoneName}"을(를) 삭제하시겠습니까?`;
                                
                                if (response.has_lockers) {
                                    confirmMessage = `구역 "${zoneName}"에는 락커 그룹이 있습니다.\n정말 삭제하시겠습니까? (락커 그룹도 함께 삭제됩니다)`;
                                }
                                
                                showConfirm(confirmMessage, function() {
                                    deleteSelectedZone(activeObject);
                                });
                            } else {
                                // 락커 확인에 실패한 경우 기본 삭제 확인
                                showConfirm(`구역 "${zoneName}"을(를) 삭제하시겠습니까?`, function() {
                                    deleteSelectedZone(activeObject);
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Failed to check zone lockers:', error);
                            // 에러 발생 시에도 기본 삭제 확인
                            showConfirm(`구역 "${zoneName}"을(를) 삭제하시겠습니까?`, function() {
                                deleteSelectedZone(activeObject);
                            });
                        }
                    });
                    */
                    }
                }
            }
        }
    });

    // 그리기 취소 (완전 삭제)
    function cancelDrawing() {
        isDrawing = false;
        currentPoints = [];
        if (currentPolygon) {
            // 이벤트 리스너 제거 후 객체 삭제
            currentPolygon.off('mousedblclick');
            canvas.remove(currentPolygon);
            currentPolygon = null;
        }
        if (tempLine) {
            canvas.remove(tempLine);
            tempLine = null;
        }
        $('.draw-zone').removeClass('active');
        canvas.selection = true;
        canvas.renderAll();
    }

    // 그리기 중단 (임시 오브젝트 유지하고 더블클릭 가능하게)
    function finishDrawingWithoutSave() {
        if (currentPoints.length < 3) {
            // 점이 충분하지 않으면 완전 취소
            cancelDrawing();
            return;
        }

        isDrawing = false;
        $('.draw-zone').removeClass('active');
        canvas.selection = true;

        // 임시 선 제거
        if (tempLine) {
            canvas.remove(tempLine);
            tempLine = null;
        }

        // 현재 폴리곤이 있다면 더블클릭 이벤트 추가하고 임시 스타일 적용
        if (currentPolygon) {
            // 더블클릭 이벤트 추가
            currentPolygon.on('mousedblclick', function() {
                console.log('Double-clicked on temp polygon, opening save modal');
                showZoneSavePopup(currentPoints);
            });

            // 임시 상태 스타일 적용
            currentPolygon.set({
                stroke: 'orange',
                strokeDashArray: [10, 5],
                strokeWidth: 2,
                selectable: false
            });
        }

        canvas.renderAll();
        console.log('Drawing finished without save, double-click to save');
    }

    // 그리기 완료
    function finishDrawing() {
        if (currentPoints.length < 3) return;

        const points = [...currentPoints];
        
        // 현재 상태 정리
        if (tempLine) {
            canvas.remove(tempLine);
            tempLine = null;
        }
        if (currentPolygon) {
            canvas.remove(currentPolygon);
        }

        // _renderStroke 오버라이드로 인해 줌 계산 불필요
        
        // 최종 폴리곤 생성
        const finalPolygon = new fabric.Polygon(points, {
            fill: 'transparent',
            stroke: 'blue',
            strokeWidth: 2,
            strokeUniform: true, // 스케일링 시 선 두께 고정
            selectable: true
        });
        canvas.add(finalPolygon);

        // 상태 초기화
        isDrawing = false;
        currentPoints = [];
        currentPolygon = null;
        $('.draw-zone').removeClass('active');
        canvas.selection = true;
        canvas.renderAll();
        
        // 모달 표시 전에 임시 저장
        tempPolygon = finalPolygon;
        showLockerZoneModal();
    }

    // 저장 팝업 표시 함수
    function showZoneSavePopup(points) {
        // 기존 팝업이 있다면 제거
        $('#zoneSavePopup').remove();
        
        // 현재 활성화된 캔버스의 도면 번호 가져오기
        const currentFloorSno = $(canvas.wrapperEl).closest('.drawing-container').data('floor');
        
        // 팝업 HTML 생성
        const popupHtml = `
            <div id="zoneSavePopup" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">구역 저장</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="zoneName" class="form-label">구역 이름</label>
                                <input type="text" class="form-control" id="zoneName" placeholder="구역 이름을 입력하세요">
                            </div>
                            <div class="mb-3">
                                <label for="zoneType" class="form-label">성별</label>
                                <select class="form-select" id="zoneType">
                                    <option value="">선택하세요</option>
                                    <option value="M">남성 락커</option>
                                    <option value="F">여성 락커</option>
                                    <option value="C">혼용 락커</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                            <button type="button" class="btn btn-primary" id="saveZoneBtn">저장</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // 팝업 추가 및 표시
        $('body').append(popupHtml);
        const popup = new bootstrap.Modal(document.getElementById('zoneSavePopup'));
        popup.show();
        
        // 저장 버튼 클릭 이벤트
        $('#saveZoneBtn').on('click', function() {
            const zoneName = $('#zoneName').val().trim();
            const zoneType = $('#zoneType').val();
            
            if (!zoneName) {
                showToast('구역 이름을 입력해주세요.', 'warning');
                return;
            }
            
            if (!zoneType) {
                showToast('락커 타입을 선택해주세요.', 'warning');
                return;
            }
            
            // 디버깅용 로그
            console.log('Saving zone with floor_sno:', currentFloorSno);
            
            // 좌표를 순수한 x, y 형태로 정리 (distance, isHorizontal 등 제거)
            const cleanPoints = points.map(point => ({
                x: Math.round(point.x * 100) / 100,
                y: Math.round(point.y * 100) / 100
            }));
            
            console.log('NEW ZONE - Original coordinates:', points);
            console.log('NEW ZONE - Cleaned coordinates:', cleanPoints);
            
            // 서버에 저장 요청
            $.ajax({
                url: '/locker/ajax_save_zone',
                type: 'POST',
                data: {
                    zone_nm: zoneName,
                    zone_gendr: zoneType,
                    zone_coords: JSON.stringify(cleanPoints),
                    floor_sno: currentFloorSno,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    console.log('Server response:', response); // 디버깅용 로그
                    console.log('Response zone_sno:', response.zone_sno);
                    console.log('Response zoneId:', response.zoneId);
                    
                    if (response.success || response.status === 'success') {
                        // 서버에서 반환된 구역 정보로 새 구역 생성
                        const newZone = {
                            zone_sno: response.zone_sno || response.zoneId,
                            zone_nm: zoneName,
                            zone_gendr: zoneType,
                            zone_coords: JSON.stringify(cleanPoints)
                        };
                        
                        console.log('NEW ZONE - Created with zone_sno:', newZone.zone_sno);
                        
                        console.log('Creating new zone:', newZone);
                        
                        // 임시 그리기 관련 객체들 제거
                        if (tempLine) {
                            canvas.remove(tempLine);
                            tempLine = null;
                        }
                        if (currentPolygon) {
                            // 이벤트 리스너 제거 후 객체 삭제
                            currentPolygon.off('mousedblclick');
                            canvas.remove(currentPolygon);
                            currentPolygon = null;
                        }
                        
                        // zone_sno가 없으면 오류 처리 (하지만 백업 저장이 성공했을 수도 있음)
                        if (!newZone.zone_sno) {
                            console.warn('No zone_sno returned from server, but save might have succeeded');
                            newZone.zone_sno = 'temp_' + Date.now(); // 임시 ID로 계속 진행
                        }
                        
                        // addZoneToCanvas 함수를 사용하여 일관된 방식으로 구역 추가
                        const zoneGroup = addZoneToCanvas(canvas, newZone);
                        
                        if (zoneGroup) {
                            // 새로 추가된 구역은 편집 불가능 상태로 설정
                            zoneGroup.set({
                                selectable: false,
                                evented: true,
                                hasControls: false,
                                hasBorders: false,
                                lockMovementX: true,
                                lockMovementY: true,
                                lockRotation: true,
                                lockScalingX: true,
                                lockScalingY: true
                            });
                            canvas.renderAll();
                        }
                        
                        // 팝업 닫기 - Bootstrap 모달 완전 정리
                        popup.hide();
                        
                        // 모달 이벤트 리스너로 완전 정리
                        $('#zoneSavePopup').on('hidden.bs.modal', function() {
                            $(this).remove();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                        });
                        
                        // 즉시 정리 (이벤트가 안 걸릴 경우 대비)
                        setTimeout(function() {
                            $('#zoneSavePopup').remove();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                        }, 500);
                        
                        // 그리기 모드 완전히 종료
                        isDrawing = false;
                        canvas.selection = true;
                        canvas.defaultCursor = 'default';
                        
                        // 점들 초기화
                        currentPoints = [];
                        
                        // 캔버스 객체들 다시 활성화
                        canvas.forEachObject(function(obj) {
                            obj.selectable = true;
                            obj.evented = true;
                        });
                        
                        canvas.renderAll();
                        
                        // 성공 메시지
                        showToast('구역이 성공적으로 저장되었습니다.', 'success');
                    } else {
                        showToast(response.message || '구역 저장에 실패했습니다.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Save Error:', error);
                    console.error('Response:', xhr.responseText);
                    showToast('구역 저장 중 오류가 발생했습니다. 다시 시도해주세요.', 'error');
                }
            });
        });
        
        // 취소 버튼 클릭 이벤트
        $('#zoneSavePopup').on('click', '.btn-secondary', function() {
            console.log('Cancel button clicked');
            popup.hide();
        });
        
        // 모달 닫힘 이벤트 처리 (X 버튼이나 취소 버튼으로 닫힐 때)
        $('#zoneSavePopup').on('hidden.bs.modal', function() {
            console.log('Modal closed, adding double-click handler to temp objects');
            
            // 임시 그리기 오브젝트에 더블클릭 이벤트 추가
            if (currentPolygon) {
                // 임시 폴리곤에 더블클릭 이벤트 추가
                currentPolygon.on('mousedblclick', function() {
                    console.log('Double-clicked on temp polygon, reopening save modal');
                    showZoneSavePopup(currentPoints);
                });
                
                // 폴리곤을 임시 상태로 표시 (점선 테두리 등)
                currentPolygon.set({
                    stroke: 'orange',
                    strokeDashArray: [10, 5],
                    strokeWidth: 2
                });
                canvas.renderAll();
            }
            
            // 모달 DOM 요소 완전 정리
            $(this).remove();
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
        });
    }

    // 마우스 휠 이벤트 (Alt + 스크롤로 확대/축소)
    canvas.on('mouse:wheel', function(opt) {
        const evt = opt.e;
        
        if (evt.altKey) {
            // 락커 그룹 추가 모드일 때는 줌 제한
            if (isLockerGroupMode) {
                showToast('락커 그룹 추가 모드에서는 확대/축소가 제한됩니다', 'warning', 2000);
                evt.preventDefault();
                evt.stopPropagation();
                return false;
            }
            
            evt.preventDefault();
            evt.stopPropagation();
            
            const delta = evt.deltaY;
            let zoom = canvas.getZoom();
            
            // 확대/축소 비율 설정
            zoom *= 0.999 ** delta;
            
            // 최소/최대 줌 레벨 제한
            if (zoom > 20) zoom = 20;
            if (zoom < 0.01) zoom = 0.01;
            
            // 마우스 포인터 위치를 중심으로 확대/축소
            const point = new fabric.Point(opt.e.offsetX, opt.e.offsetY);
            canvas.zoomToPoint(point, zoom);
            
            // _renderStroke 오버라이드로 인해 별도의 선 두께 업데이트 불필요
            canvas.renderAll();
            
            return false;
        }
    });

    // X 버튼(clear-drawing) 클릭 이벤트
    $('.clear-drawing').on('click', function() {
        const canvasContainer = $(canvas.wrapperEl).closest('.drawing-container');
        const currentFloorSno = canvasContainer.data('floor');
        const isEditMode = editModeStates[currentFloorSno] || false;
        
        // 편집 모드가 활성화되어 있으면 편집 모드 종료 (변경사항 확인 없이)
        if (isEditMode) {
            const editButton = canvasContainer.find('.toggle-edit-mode');
            disableEditMode(canvas, currentFloorSno, editButton);
        }
        
        // 드로잉 모드가 활성화되어 있으면 드로잉 취소
        if (isDrawing) {
            cancelDrawing(canvas);
            // 드로잉 버튼 비활성화
            canvasContainer.find('.draw-zone').removeClass('active');
        }
    });

    // 원래 크기로 되돌리기 버튼 클릭 이벤트 (애니메이션 효과)
    $('.reset-zoom').on('click', function() {
        // 락커 그룹 추가 모드일 때는 줌 리셋 제한
        if (isLockerGroupMode) {
            showToast('락커 그룹 추가 모드에서는 줌 변경이 제한됩니다. 모드를 종료하고 다시 시도하세요.', 'warning', 3000);
            return;
        }
        
        // 현재 활성 캔버스 찾기
        const activeCanvas = getActiveCanvas();
        
        if (!activeCanvas) {
            console.error('No active canvas found for zoom reset');
            return;
        }
        
        // 현재 상태 확인
        const currentZoom = activeCanvas.getZoom();
        const currentVpt = activeCanvas.viewportTransform.slice();
        
        // 이미 원래 크기라면 메시지만 표시
        if (Math.abs(currentZoom - 1) < 0.01 && 
            Math.abs(currentVpt[4]) < 1 && Math.abs(currentVpt[5]) < 1) {
            showToast('이미 원래 크기입니다.', 'info', 2000);
            return;
        }
        
        // 애니메이션으로 원래 크기로 복귀
        animateZoomReset(activeCanvas, () => {
            console.log('Zoom reset to original size with animation');
        });
    });



// 드로잉 취소 함수
function cancelDrawing(canvas) {
    isDrawing = false;
    canvas.selection = true;
    canvas.defaultCursor = 'default';
    
    // 임시 선과 다각형 제거
    if (tempLine) {
        canvas.remove(tempLine);
        tempLine = null;
    }
    if (currentPolygon) {
        // 이벤트 리스너 제거 후 객체 삭제
        currentPolygon.off('mousedblclick');
        canvas.remove(currentPolygon);
        currentPolygon = null;
    }
    
    // 점들 초기화
    currentPoints = [];
    
    // 캔버스 객체들 다시 활성화
    canvas.forEachObject(function(obj) {
        obj.selectable = true;
        obj.evented = true;
    });
    
    canvas.renderAll();
    console.log('Drawing cancelled');
}

// 편집 모드 토글 버튼 클릭 이벤트
$('.toggle-edit-mode').on('click', function() {
    const canvasContainer = $(this).closest('.drawing-container');
    const floorSno = canvasContainer.data('floor');
    const canvas = fabricCanvases[floorSno];
    
    if (!canvas) {
        console.error('Canvas not found for floor:', floorSno);
        return;
    }
    
    // 드로잉 모드가 활성화되어 있으면 드로잉 취소
    if (isDrawing) {
        cancelDrawing(canvas);
        canvasContainer.find('.draw-zone').removeClass('active');
    }
    
    // 편집 모드 토글
    const isCurrentlyEditMode = editModeStates[floorSno] || false;
    
    if (isCurrentlyEditMode) {
        // 편집 모드 종료
        exitEditMode(canvas, floorSno, $(this));
    } else {
        // 편집 모드 시작
        enableEditMode(canvas, floorSno, $(this));
    }
});

}

// 구역 삭제 함수
function deleteSelectedZone(zoneObject) {
    if (!zoneObject || !zoneObject.data || !zoneObject.data.zone_sno) {
        showToast('유효하지 않은 구역입니다.', 'error');
        return;
    }
    
    // 현재 캔버스 찾기
    const canvas = zoneObject.canvas || fabricCanvases[currentFloorSno];
    if (!canvas) {
        showToast('캔버스를 찾을 수 없습니다.', 'error');
        return;
    }
    
    $.ajax({
        url: '/locker/ajax_delete_zone',
        type: 'POST',
        data: {
            zone_sno: zoneObject.data.zone_sno,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function(response) {
            console.log('Delete zone response:', response); // 디버깅용 로그
            
            if (response.status === 'success') {
                // 캔버스에서 구역 제거
                canvas.remove(zoneObject);
                canvas.discardActiveObject();
                canvas.renderAll();
                
                // 선택된 구역 초기화
                selectedZone = null;
                
                showToast('구역이 삭제되었습니다.', 'success');
            } else {
                showToast(response.message || '구역 삭제에 실패했습니다.', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete Error:', error);
            showToast('구역 삭제 중 오류가 발생했습니다.', 'error');
        }
    });
}

// 구역 추가
function addZoneToCanvas(canvas, zone) {
    if (!canvas || !zone) {
        console.error('Invalid parameters for addZoneToCanvas');
        return;
    }

    try {
        // 좌표 파싱
        const coords = typeof zone.zone_coords === 'string' ? 
            JSON.parse(zone.zone_coords) : zone.zone_coords;
        
        // 좌표를 순수한 x, y 형태로 정리 (distance, isHorizontal 등 무시)
        const cleanCoords = coords.map(point => ({
            x: point.x,
            y: point.y
        }));
        
        console.log('LOADING ZONE - Zone:', zone.zone_sno, 'Original coordinates:', coords);
        console.log('LOADING ZONE - Zone:', zone.zone_sno, 'Cleaned coordinates:', cleanCoords);
        
        if (!Array.isArray(cleanCoords) || cleanCoords.length < 3) {
            console.error('Invalid coordinates for zone:', zone.zone_sno);
            return;
        }
        
        // 구역 색상 가져오기
        const color = getZoneColor(zone.zone_gendr);
        
        // _renderStroke 오버라이드로 인해 줌 계산 불필요
        
        // 폴리곤 생성
        const polygon = new fabric.Polygon(cleanCoords, {
            fill: 'transparent',
            stroke: color.stroke,
            strokeWidth: 2,
            strokeUniform: true, // 스케일링 시 선 두께 고정
            selectable: false,
            hoverCursor: 'pointer'
        });
        
        // 구역 이름 추가
        const center = getPolygonCenter(cleanCoords);
        const text = new fabric.Text(zone.zone_nm || '이름 없음', {
            left: center.x,
            top: center.y,
            fontSize: 14,
            fill: '#000000',
            fontFamily: 'Arial',
            originX: 'center',
            originY: 'center',
            selectable: false,
            hoverCursor: 'pointer'
        });
        
        // 구역 그룹 생성
        const group = new fabric.Group([polygon, text], {
            data: { 
                zone_sno: zone.zone_sno,
                zone_nm: zone.zone_nm,
                gender: zone.zone_gendr
            },
            selectable: false,
            evented: true,
            hasControls: false,
            hasBorders: false,
            lockMovementX: true,
            lockMovementY: true,
            lockRotation: true,
            lockScalingX: true,
            lockScalingY: true,
            hoverCursor: 'pointer'
        });
        
        // 이벤트 핸들러 설정
        setupZoneEventHandlers(group, canvas);
        
        // 현재 편집 모드 상태 확인하여 적절한 설정 적용
        const canvasContainer = $(canvas.wrapperEl).closest('.drawing-container');
        const currentFloorSno = canvasContainer.data('floor');
        const isEditMode = editModeStates[currentFloorSno] || false;
        
        if (isEditMode) {
            // 편집 모드라면 편집 가능하게 설정
            group.set({
                selectable: true,
                hasControls: true,
                hasBorders: true,
                lockMovementX: false,
                lockMovementY: false,
                lockRotation: false,
                lockScalingX: false,
                lockScalingY: false
            });
        }
        
        // 캔버스에 추가
        canvas.add(group);
        return group;
    } catch (error) {
        console.error('Error in addZoneToCanvas:', error);
        throw error;
    }
}

// 구역 이벤트 핸들러 설정
function setupZoneEventHandlers(group, canvas) {
    let clickTimeout;
    
    group.on('mousedown', function(e) {
        const canvasContainer = $(canvas.wrapperEl).closest('.drawing-container');
        const currentFloorSno = canvasContainer.data('floor');
        const isEditMode = editModeStates[currentFloorSno] || false;
        
        console.log('Zone clicked, edit mode:', isEditMode, 'zone:', this.data.zone_nm);
        
        // 더블클릭 감지
        if (clickTimeout) {
            // 더블클릭 - 구역 확대 및 락커 그룹 관리 UI 표시
            clearTimeout(clickTimeout);
            clickTimeout = null;
            
            try {
                const zone_sno = this.data.zone_sno;
                if (zone_sno) {
                    console.log('Zooming to zone:', zone_sno);
                    zoomToZone(this, zone_sno);
                }
            } catch (error) {
                console.error('Error zooming to zone:', error);
                showToast('구역 확대에 실패했습니다.', 'error');
            }
            return;
        }
        
        // 단일클릭 타이머 설정
        clickTimeout = setTimeout(() => {
            clickTimeout = null;
            
            // 편집 모드에서만 선택 표시
            if (isEditMode) {
                // 이전 선택 해제
                if (selectedZone && selectedZone !== this) {
                    const prevColor = getZoneColor(selectedZone.data.gender);
                    selectedZone._objects[0].set({
                        fill: 'transparent',
                        stroke: prevColor.stroke,
                        strokeWidth: 2
                    });
                }
                
                // 새로운 선택
                selectedZone = this;
                this._objects[0].set({
                    stroke: '#FF0000',
                    strokeWidth: 2
                });
                canvas.renderAll();
                console.log('Zone selected for editing:', this.data.zone_nm);
            }
        }, 300);
    });
}

// 구역 색상 정의
function getZoneColor(gender) {
    const colors = {
        'M': { fill: 'rgba(173,216,230,0.3)', stroke: '#0000FF' }, // 남성 - 연하늘색
        'F': { fill: 'rgba(255,182,193,0.3)', stroke: '#FF69B4' }, // 여성 - 연분홍색
        'C': { fill: 'rgba(255,223,0,0.3)', stroke: '#FFD700' }, // 혼용 - 개나리색(노란색)
        'default': { fill: 'rgba(200,200,200,0.3)', stroke: '#666666' } // 기본 - 회색
    };
    return colors[gender] || colors.default;
}

// 폴리곤 중심점 계산
function getPolygonCenter(points) {
    if (!Array.isArray(points) || points.length === 0) {
        console.error('Invalid points array for getPolygonCenter');
        return { x: 0, y: 0 };
    }
    
    try {
        const xs = points.map(p => p.x);
        const ys = points.map(p => p.y);
        
        return {
            x: (Math.min(...xs) + Math.max(...xs)) / 2,
            y: (Math.min(...ys) + Math.max(...ys)) / 2
        };
    } catch (error) {
        console.error('Error calculating polygon center:', error);
        return { x: 0, y: 0 };
    }
}

// 구역 목록 새로고침 함수 추가
function loadZoneList(floorSno) {
    console.log('Loading zone list for floor:', floorSno); // 디버깅용 로그
    
    // AJAX 요청으로 구역 목록 가져오기
    $.ajax({
        url: '<?= site_url('locker/ajax_get_zones') ?>',
        type: 'GET',
        data: { floor_sno: floorSno },
        dataType: 'json',
        success: function(response) {
            console.log('Zone list response:', response); // 디버깅용 로그
            if (response.status === 'success') {
                // 성공적으로 구역 목록을 가져왔을 때의 처리
                location.reload(); // 임시로 페이지 새로고침
            } else {
                console.error('Failed to load zone list:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to load zone list:', error);
        }
    });
}

// 구역 선택 해제 함수
function deselectZone() {
    if (selectedZone) {
        selectedZone.set({
            stroke: 'blue',
            strokeWidth: 2
        });
        selectedZone = null;
        canvas.renderAll();
    }
}

// 구역 선택 함수 수정
function selectZone(zone) {
    // 이전에 선택된 구역이 있다면 선택 해제
    if (selectedZone) {
        deselectZone();
    }
    
    // 새 구역 선택
    selectedZone = zone;
    zone.set({
        stroke: 'red',
        strokeWidth: 2
    });
    canvas.renderAll();
}

// 구역 상세 보기 및 락커 그룹 관리 UI
function showZoneDetail(zoneSno) {
    // 현재 선택된 구역의 정보를 가져옴
    $.ajax({
        url: '<?= site_url('locker/ajax_get_zone_detail') ?>',
        type: 'GET',
        data: { zone_sno: zoneSno },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const zone = response.data;
                const canvas = fabricCanvases[currentFloorSno];
                
                // 해당 구역 객체 찾기
                const zoneObject = canvas.getObjects().find(obj => 
                    obj.type === 'group' && obj.data && obj.data.zone_sno == zoneSno
                );
                
                if (zoneObject) {
                    // 현재 구역 객체 저장
                    currentZoneObject = zoneObject;
                    
                    // 구역 경계 계산
                    const boundingRect = zoneObject.getBoundingRect();
                    const padding = 50; // 여백
                    
                    // 확대할 영역 계산
                    const zoomArea = {
                        left: boundingRect.left - padding,
                        top: boundingRect.top - padding,
                        width: boundingRect.width + (padding * 2),
                        height: boundingRect.height + (padding * 2)
                    };
                    
                    // 캔버스 크기에 맞춰 줌 레벨 계산
                    const canvasWidth = canvas.getWidth();
                    const canvasHeight = canvas.getHeight();
                    const scaleX = canvasWidth / zoomArea.width;
                    const scaleY = canvasHeight / zoomArea.height;
                    const zoomLevel = Math.min(scaleX, scaleY, 3); // 최대 3배 확대
                    
                    // 구역을 중심으로 확대
                    const centerX = zoomArea.left + zoomArea.width / 2;
                    const centerY = zoomArea.top + zoomArea.height / 2;
                    
                    canvas.zoomToPoint(new fabric.Point(centerX, centerY), zoomLevel);
                    
                    // 다른 구역들 숨기기
                    canvas.getObjects().forEach(obj => {
                        if (obj.type === 'group' && obj.data && obj.data.zone_sno != zoneSno) {
                            obj.visible = false;
                        }
                    });
                    canvas.renderAll();
                }
                
                // 구역 상세 정보와 락커 그룹 추가 버튼을 보여주는 UI 추가
                const detailHtml = `
                    <div class="zone-detail-panel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>구역: ${zone.zone_nm}</h5>
                            <button type="button" class="btn btn-primary btn-sm" onclick="showLockerGroupModal(${zoneSno})">
                                <i class="fas fa-plus"></i> 락커 그룹 추가
                            </button>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="showAllZones(${currentFloorSno})">
                                <i class="fas fa-arrow-left"></i> 전체 구역 보기
                            </button>
                        </div>
                    </div>
                `;
                
                // 상세 패널을 페이지에 추가
                const container = $(`[data-floor="${currentFloorSno}"]`);
                container.append(detailHtml);
            }
        }
    });
}

// 전체 구역 보기로 돌아가는 함수
function showAllZones(floorSno) {
    const canvas = fabricCanvases[floorSno];
    if (!canvas) {
        console.error('Canvas not found for floor:', floorSno);
        return;
    }

    // 줌 레벨을 원래대로 되돌리기
    canvas.setZoom(1);
    canvas.setViewportTransform([1, 0, 0, 1, 0, 0]);

    // 모든 객체를 다시 보이게 함
    canvas.getObjects().forEach(obj => {
        obj.visible = true;
    });
    canvas.renderAll();
    
    // 상세 패널 제거
    $('.zone-detail-panel').remove();
    
    // 락커 그룹 그리기 모드 종료
    stopLockerGroupDrawing();
    
    // 현재 구역 객체 초기화
    currentZoneObject = null;
}

// 락커 그룹 추가 모달 표시
function showLockerGroupModal(zoneSno) {
    // 기존 모달 제거
    $('#lockerGroupModal').remove();
    
          const modalHtml = `
          <div id="lockerGroupModal" class="modal fade" tabindex="-1">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">락커 그룹 추가</h5>
                        <button type="button" class="btn-close" id="closeLockerGroupModal"></button>
                    </div>
                                          <div class="modal-body">
                          <!-- 1. 그룹명 + 락커 타입 (나란히) -->
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="lockerGroupName" class="form-label">그룹명 <small class="text-muted">(옵션)</small></label>
                                      <input type="text" class="form-control form-control-sm" id="lockerGroupName" placeholder="그룹 이름">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="lockerType" class="form-label">락커 타입 <span class="text-danger">*</span></label>
                                      <select class="form-select form-select-sm" id="lockerType">
                                          <option value="">선택하세요</option>
                                          <option value="일반락커">일반락커</option>
                                          <option value="콜프락커">콜프락커</option>
                                          <option value="기타">기타 (직접입력)</option>
                                      </select>
                                  </div>
                              </div>
                          </div>
                          
                          <!-- 기타 타입 직접 입력 -->
                          <div class="mb-3">
                              <input type="text" class="form-control form-control-sm mt-2 d-none" id="customLockerType" placeholder="락커 타입을 직접 입력하세요">
                          </div>
                        
                                                  <!-- 2. 가로 칸수 + 단수 (나란히) -->
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="horizontalCount" class="form-label">가로 칸수 <span class="text-danger">*</span></label>
                                      <input type="number" class="form-control form-control-sm" id="horizontalCount" min="1" value="1">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="verticalCount" class="form-label">단수 <span class="text-danger">*</span></label>
                                      <input type="number" class="form-control form-control-sm" id="verticalCount" min="1" value="1">
                                      <small class="text-muted">세로 층수</small>
                                  </div>
                              </div>
                          </div>
                        
                                                  <!-- 3. 가로 크기 + 깊이 (나란히) -->
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="lockerWidth" class="form-label">가로 크기 (cm) <span class="text-danger">*</span></label>
                                      <input type="number" class="form-control form-control-sm" id="lockerWidth" min="1" step="0.1" value="30">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="lockerDepth" class="form-label">깊이 (cm) <span class="text-danger">*</span></label>
                                      <input type="number" class="form-control form-control-sm" id="lockerDepth" min="1" step="0.1" value="37">
                                  </div>
                              </div>
                          </div>
                        
                                                  <!-- 4. 세로 크기 + 자동 계산 체크박스 (나란히) -->
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="lockerHeight" class="form-label">세로 크기 (cm) <span class="text-danger">*</span></label>
                                      <input type="number" class="form-control form-control-sm" id="lockerHeight" min="1" step="0.1" value="30">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="mb-3">
                                      <div class="form-check mt-4">
                                          <input class="form-check-input" type="checkbox" id="autoCalculate" checked>
                                          <label class="form-check-label small" for="autoCalculate">
                                              총 칸수 자동 계산
                                          </label>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>안내:</strong> 락커 그룹을 추가하려면 캔버스에서 시작점과 끝점을 클릭하세요.<br>
                            • 위에서 본 모습으로 그려지므로 가로 칸수만큼 1줄로 그려집니다<br>
                            • 단수(높이)는 정보로만 저장되며 평면도에는 표시되지 않습니다<br>
                            • 클릭한 점들은 반드시 구역 내부에 있어야 합니다
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelLockerGroup">취소</button>
                        <button type="button" class="btn btn-primary" id="startLockerDrawing">그리기 시작</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('lockerGroupModal'), {
        backdrop: 'static',  // 모달 바깥쪽 클릭으로 닫히지 않도록 설정
        keyboard: false      // ESC 키로 닫히지 않도록 설정 (우리는 별도 ESC 처리가 있음)
    });
    modal.show();
    
    // 락커 타입 변경 이벤트
    $('#lockerType').on('change', function() {
        if ($(this).val() === '기타') {
            $('#customLockerType').removeClass('d-none').focus();
        } else {
            $('#customLockerType').addClass('d-none');
        }
    });
    
    // 자동 계산 체크박스 이벤트
    $('#autoCalculate').on('change', function() {
        if ($(this).is(':checked')) {
            calculateTotalCount();
        }
    });
    
    // 가로 칸수, 단수 변경 시 자동 계산
    $('#horizontalCount, #verticalCount').on('input', function() {
        if ($('#autoCalculate').is(':checked')) {
            calculateTotalCount();
        }
    });
    
    // X 버튼 이벤트
    $('#closeLockerGroupModal').on('click', function() {
        modal.hide();
    });
    
    // 취소 버튼 이벤트
    $('#cancelLockerGroup').on('click', function() {
        modal.hide();
    });
    
    // 그리기 시작 버튼 이벤트
    $('#startLockerDrawing').on('click', function() {
        console.log('그리기 시작 버튼 클릭됨');
        if (validateLockerGroupForm()) {
            console.log('폼 검증 통과, 그리기 시작');
            startLockerGroupDrawing(zoneSno);
            modal.hide();
        } else {
            console.log('폼 검증 실패');
        }
    });
    
    // 모달이 닫힐 때 이벤트 처리 (모달만 닫고 락커그룹 모드는 유지)
    $('#lockerGroupModal').on('hidden.bs.modal', function() {
        // 더 이상 모달이 닫힐 때 락커그룹 모드를 해제하지 않음
        // 락커그룹 모드는 UI의 X 버튼이나 전체보기 버튼을 통해서만 해제됨
        console.log('락커 그룹 모달이 닫혔지만 락커그룹 모드는 유지됩니다.');
    });
}

// 락커 그룹 그리기 관련 전역 변수
let lockerGroupData = null;

// 편집 모드 활성화
function enableEditMode(canvas, floorSno, button) {
    // 편집 모드 상태 업데이트
    editModeStates[floorSno] = true;
    
    // 버튼 상태 변경
    button.removeClass('btn-outline-warning').addClass('btn-warning');
    button.attr('title', '편집 모드 (활성)');
    
    // 모든 구역을 편집 가능하게 설정
    canvas.getObjects().forEach(function(obj) {
        if (obj.type === 'group' && obj.data && obj.data.zone_sno) {
            obj.set({
                selectable: true,
                evented: true,
                hasControls: true,
                hasBorders: true,
                lockMovementX: false,
                lockMovementY: false,
                lockRotation: false,
                lockScalingX: false,
                lockScalingY: false
            });
        }
    });
    
    // 원래 상태 저장
    saveOriginalZoneStates(canvas, floorSno);
    
    canvas.renderAll();
    console.log('Edit mode enabled for floor:', floorSno);
    showToast('편집 모드가 활성화되었습니다.', 'info');
}

// 편집 모드 종료
function exitEditMode(canvas, floorSno, button) {
    // 변경사항 확인 및 처리
    checkAndHandleChanges(canvas, floorSno, button);
}

// 편집 모드 관련 함수들
function saveOriginalZoneStates(canvas, floorSno) {
    const zones = canvas.getObjects().filter(obj => 
        obj.type === 'group' && obj.data && obj.data.zone_sno
    );
    
    originalZoneStates[floorSno] = {};
    
    zones.forEach(zone => {
        const zoneSno = zone.data.zone_sno;
        originalZoneStates[floorSno][zoneSno] = {
            left: zone.left,
            top: zone.top,
            angle: zone.angle,
            scaleX: zone.scaleX,
            scaleY: zone.scaleY,
            polygon_points: zone._objects[0].points.map(p => ({x: p.x, y: p.y})) // 원본 폴리곤 좌표 저장
        };
    });
    
    console.log('Original zone states saved for floor:', floorSno, originalZoneStates[floorSno]);
}

function checkAndHandleChanges(canvas, floorSno, button) {
    const zones = canvas.getObjects().filter(obj => 
        obj.type === 'group' && obj.data && obj.data.zone_sno
    );
    
    let hasChanges = false;
    const changedZones = [];
    
    // 변경내역 확인
    zones.forEach(zone => {
        const zoneSno = zone.data.zone_sno;
        const original = originalZoneStates[floorSno] && originalZoneStates[floorSno][zoneSno];
        if (original) {
            // 변경 감지 임계값 - 속성별로 다르게 설정
            const positionThreshold = 0.1;  // 위치 변화 (픽셀)
            const angleThreshold = 0.1;     // 각도 변화 (도)
            const scaleThreshold = 0.001;   // 스케일 변화 (매우 세밀하게)
            
            if (Math.abs(zone.left - original.left) > positionThreshold ||
                Math.abs(zone.top - original.top) > positionThreshold ||
                Math.abs(zone.angle - original.angle) > angleThreshold ||
                Math.abs(zone.scaleX - original.scaleX) > scaleThreshold ||
                Math.abs(zone.scaleY - original.scaleY) > scaleThreshold) {
                
                hasChanges = true;
                changedZones.push({
                    zone_sno: zoneSno,
                    zone_nm: zone.data.zone_nm,
                    left: zone.left,
                    top: zone.top,
                    angle: zone.angle,
                    scaleX: zone.scaleX,
                    scaleY: zone.scaleY,
                    original: original
                });
            }
        }
    });
    
    if (hasChanges) {
        console.log('Changes detected:', changedZones);
        showSaveChangesConfirm(canvas, floorSno, button, changedZones);
    } else {
        // 변경사항이 없으면 바로 편집 모드 종료
        disableEditMode(canvas, floorSno, button);
    }
}

function showSaveChangesConfirm(canvas, floorSno, button, changedZones) {
    Swal.fire({
        title: '변경내역 저장',
        html: `
            <p>구역의 위치나 크기가 변경되었습니다.</p>
            <p><strong>변경된 구역: ${changedZones.length}개</strong></p>
            <p>변경내역을 저장하시겠습니까?</p>
        `,
        icon: 'question',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: '저장',
        cancelButtonText: '취소',
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            confirmButton: 'btn btn-success btn-equal-size',
            cancelButton: 'btn btn-secondary btn-equal-size'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // 변경내역 저장
            saveZoneChanges(canvas, floorSno, button, changedZones);
        } else {
            // 원래 상태로 되돌리기
            revertZoneChanges(canvas, floorSno, button);
        }
    });
}

// 저장 후 구역 업데이트 함수 - 완전히 새로 생성하는 방식
function updateZoneAfterSave(canvas, zone, transformedPoints) {
    try {
        console.log('Updating zone after save:', zone.data.zone_sno);
        console.log('NEW COORDS to apply:', transformedPoints);
        
        // 기존 구역 제거
        canvas.remove(zone);
        
        // 새로운 구역 데이터 생성
        const newZoneData = {
            zone_sno: zone.data.zone_sno,
            zone_nm: zone.data.zone_nm,
            zone_gendr: zone.data.gender,
            zone_coords: transformedPoints
        };
        
        // 새로운 구역을 캔버스에 추가 (addZoneToCanvas 함수 사용)
        const newZone = addZoneToCanvas(canvas, newZoneData);
        
        if (newZone) {
            console.log('Zone recreated successfully with new coordinates');
        }
        
        canvas.renderAll();
    } catch (error) {
        console.error('Error updating zone after save:', error);
    }
}

// 폴리곤 중심점 계산 함수
function getPolygonCenter(points) {
    if (!points || points.length === 0) return {x: 0, y: 0};
    
    const sumX = points.reduce((sum, p) => sum + p.x, 0);
    const sumY = points.reduce((sum, p) => sum + p.y, 0);
    
    return {
        x: sumX / points.length,
        y: sumY / points.length
    };
}

function saveZoneChanges(canvas, floorSno, button, changedZones) {
    console.log('Saving zone changes:', changedZones);
    
    // 변경된 구역들을 개별적으로 처리
    let savedCount = 0;
    let totalCount = changedZones.length;
    let hasError = false;
    
    changedZones.forEach((zoneData, index) => {
        // 구역의 좌표 정보를 업데이트하기 위해 기존 zone_coords를 재계산
        const zone = canvas.getObjects().find(obj => 
            obj.type === 'group' && obj.data && obj.data.zone_sno == zoneData.zone_sno
        );
        
        if (!zone) {
            console.error('Zone not found on canvas:', zoneData.zone_sno);
            hasError = true;
            checkCompletion();
            return;
        }
        
        // zone_sno 검증
        if (!zoneData.zone_sno || zoneData.zone_sno === 'undefined') {
            console.error('Invalid zone_sno:', zoneData.zone_sno);
            hasError = true;
            checkCompletion();
            return;
        }
        
        // 구역이 변환되지 않았다면 저장할 필요 없음
        if (zone.left === originalZoneStates[floorSno][zoneData.zone_sno].left &&
            zone.top === originalZoneStates[floorSno][zoneData.zone_sno].top &&
            zone.angle === originalZoneStates[floorSno][zoneData.zone_sno].angle &&
            zone.scaleX === originalZoneStates[floorSno][zoneData.zone_sno].scaleX &&
            zone.scaleY === originalZoneStates[floorSno][zoneData.zone_sno].scaleY) {
            
            console.log('Zone not changed, skipping save:', zoneData.zone_sno);
            savedCount++;
            checkCompletion();
            return;
        }
        
        const polygon = zone._objects[0];
        
        // 회전이 있는지 확인 (임계값 1도)
        const hasRotation = Math.abs(zone.angle % 360) > 1;
        
        // 디버깅을 위해 회전된 구역도 사각형으로 처리하는 옵션
        const forceRectangle = false; // 실제 회전 모양 보존
        
        console.log('MOVED ZONE - Has rotation:', hasRotation, 'Angle:', zone.angle);
        
        // 원본 상태와 현재 상태 비교
        const originalState = originalZoneStates[floorSno][zoneData.zone_sno];
        console.log('COMPARISON - Original state:', originalState);
        console.log('COMPARISON - Current state:', {
            left: zone.left,
            top: zone.top,
            angle: zone.angle,
            scaleX: zone.scaleX,
            scaleY: zone.scaleY
        });
        console.log('COMPARISON - Changes:', {
            deltaLeft: zone.left - originalState.left,
            deltaTop: zone.top - originalState.top,
            deltaAngle: zone.angle - originalState.angle,
            deltaScaleX: zone.scaleX - originalState.scaleX,
            deltaScaleY: zone.scaleY - originalState.scaleY
        });
        
        let transformedPoints;
        
        if (hasRotation && !forceRectangle) {
            // 회전이 있는 경우: 올바른 회전 중심점 사용
            console.log('Processing rotated zone with correct rotation center...');
            
            console.log('ROTATED ZONE - Zone properties:', {
                left: zone.left,
                top: zone.top,
                angle: zone.angle,
                scaleX: zone.scaleX,
                scaleY: zone.scaleY
            });
            
            // 바운딩 박스 중심점 사용 (Fabric.js 회전축과 일치)
            const currentCenter = zone.getCenterPoint();
            const originalPolygonCenter = getPolygonCenter(originalState.polygon_points);
            
            console.log('ROTATED ZONE - Current center:', currentCenter);
            console.log('ROTATED ZONE - Original polygon center:', originalPolygonCenter);
            console.log('ROTATED ZONE - Original points:', polygon.points);
            
            // 각 점을 올바르게 변환
            transformedPoints = polygon.points.map(point => {
                // 1. 원본 폴리곤 중심점 기준으로 상대 좌표 구하기
                const relativeX = point.x - originalPolygonCenter.x;
                const relativeY = point.y - originalPolygonCenter.y;
                
                // 2. 스케일 적용
                const scaledX = relativeX * zone.scaleX;
                const scaledY = relativeY * zone.scaleY;
                
                // 3. 회전 적용
                const angleRad = zone.angle * Math.PI / 180;
                const cos = Math.cos(angleRad);
                const sin = Math.sin(angleRad);
                
                const rotatedX = scaledX * cos - scaledY * sin;
                const rotatedY = scaledX * sin + scaledY * cos;
                
                // 4. 현재 중심점 기준으로 절대 좌표 구하기
                const finalX = rotatedX + currentCenter.x;
                const finalY = rotatedY + currentCenter.y;
                
                console.log('ROTATED ZONE - Point transform:', {
                    original: {x: point.x, y: point.y},
                    relative: {x: relativeX, y: relativeY},
                    scaled: {x: scaledX, y: scaledY},
                    rotated: {x: rotatedX, y: rotatedY},
                    final: {x: finalX, y: finalY}
                });
                
                return {
                    x: Math.round(finalX * 100) / 100,
                    y: Math.round(finalY * 100) / 100
                };
            });
            
            console.log('ROTATED ZONE - Transformed points:', transformedPoints);
            
        } else {
            // 회전이 없는 경우: 일관성을 위해 동일한 변환 매트릭스 사용
            console.log('Processing non-rotated zone using same transform matrix...');
            
            // zone 객체의 변환 속성들 확인
            console.log('NON-ROTATED ZONE - Zone properties:', {
                left: zone.left,
                top: zone.top,
                angle: zone.angle,
                scaleX: zone.scaleX,
                scaleY: zone.scaleY,
                originX: zone.originX,
                originY: zone.originY
            });
            
            const transform = zone.calcTransformMatrix();
            console.log('NON-ROTATED ZONE - Fabric.js transform matrix:', transform);
            
            // 수동 변환 매트릭스 (이동만 있는 경우)
            const manualTransform = [
                zone.scaleX,  // a
                0,            // b  
                0,            // c
                zone.scaleY,  // d
                zone.left,    // e (translateX)
                zone.top      // f (translateY)
            ];
            console.log('NON-ROTATED ZONE - Manual transform matrix:', manualTransform);
            
            console.log('NON-ROTATED ZONE - Original points:', polygon.points);
            
            // 폴리곤의 실제 중심점을 계산합니다
            const originalPolygonPoints = originalState.polygon_points;
            const originalPolygonCenter = getPolygonCenter(originalPolygonPoints);
            
            // 현재 폴리곤의 중심점도 계산
            const currentPolygonCenter = getPolygonCenter(polygon.points);
            
            console.log('NON-ROTATED ZONE - Original polygon center:', originalPolygonCenter);
            console.log('NON-ROTATED ZONE - Current polygon center:', currentPolygonCenter);
            
            // 실제 이동량 계산
            const actualDelta = {
                x: zone.left - originalState.left,
                y: zone.top - originalState.top
            };
            console.log('NON-ROTATED ZONE - Actual delta:', actualDelta);
            
            // 이동량과 스케일 변화 모두 적용
            transformedPoints = polygon.points.map(point => {
                // 1. 원본 중심점 기준으로 상대 좌표 구하기
                const relativeX = point.x - originalPolygonCenter.x;
                const relativeY = point.y - originalPolygonCenter.y;
                
                // 2. 스케일 적용
                const scaledX = relativeX * zone.scaleX;
                const scaledY = relativeY * zone.scaleY;
                
                // 3. 현재 중심점 기준으로 절대 좌표 구하기 + 이동량 적용
                const finalX = scaledX + currentPolygonCenter.x + actualDelta.x;
                const finalY = scaledY + currentPolygonCenter.y + actualDelta.y;
                
                console.log('NON-ROTATED ZONE - Point transform:', {
                    original: {x: point.x, y: point.y},
                    relative: {x: relativeX, y: relativeY},
                    scaled: {x: scaledX, y: scaledY},
                    final: {x: finalX, y: finalY}
                });
                
                return {
                    x: Math.round(finalX * 100) / 100,
                    y: Math.round(finalY * 100) / 100
                };
            });
            
            console.log('NON-ROTATED ZONE - Transformed points:', transformedPoints);
            
            // 변환 결과 검증
            if (transformedPoints.some(p => isNaN(p.x) || isNaN(p.y))) {
                console.error('Invalid transformation result, falling back to bounding rect');
                const boundingRect = zone.getBoundingRect(true, true);
                transformedPoints = [
                    { x: Math.round(boundingRect.left * 100) / 100, y: Math.round(boundingRect.top * 100) / 100 },
                    { x: Math.round((boundingRect.left + boundingRect.width) * 100) / 100, y: Math.round(boundingRect.top * 100) / 100 },
                    { x: Math.round((boundingRect.left + boundingRect.width) * 100) / 100, y: Math.round((boundingRect.top + boundingRect.height) * 100) / 100 },
                    { x: Math.round(boundingRect.left * 100) / 100, y: Math.round((boundingRect.top + boundingRect.height) * 100) / 100 }
                ];
            }
        }
        
        console.log('MOVED ZONE - Final coordinates:', transformedPoints);
        
        $.ajax({
            url: '<?= site_url('locker/ajax_save_zone') ?>',
            type: 'POST',
            data: {
                zone_sno: zoneData.zone_sno,
                zone_nm: zone.data.zone_nm,
                zone_gendr: zone.data.gender,
                zone_coords: JSON.stringify(transformedPoints),
                floor_sno: floorSno,
                update_mode: true, // 업데이트 모드임을 표시
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                console.log(`Zone ${zoneData.zone_sno} save response:`, response);
                
                if (response.success || response.status === 'success') {
                    savedCount++;
                    console.log(`Zone ${zoneData.zone_sno} saved successfully (${savedCount}/${totalCount})`);
                    
                    // 저장 성공시에만 구역 업데이트
                    updateZoneAfterSave(canvas, zone, transformedPoints);
                } else {
                    console.error(`Failed to save zone ${zoneData.zone_sno}:`, response.message || response);
                    console.error('Full response:', response);
                    hasError = true;
                }
                
                checkCompletion();
            },
            error: function(xhr, status, error) {
                console.error(`AJAX error saving zone ${zoneData.zone_sno}:`, error);
                console.error('Response text:', xhr.responseText);
                hasError = true;
                checkCompletion();
            }
        });
    });
    
    function checkCompletion() {
        if (savedCount + (hasError ? 1 : 0) >= totalCount) {
            if (hasError) {
                console.error('Some zones failed to save');
                showToast('일부 구역 변경사항 저장에 실패했습니다.', 'error');
                // 저장 실패 시 편집 모드 유지
                editModeStates[floorSno] = true;
            } else {
                console.log('All zone changes saved successfully');
                showToast('구역 변경사항이 저장되었습니다.', 'success');
                
                // 원래 상태 업데이트 (현재 상태가 새로운 원래 상태)
                saveOriginalZoneStates(canvas, floorSno);
                
                // 편집 모드 종료
                disableEditMode(canvas, floorSno, button);
            }
        }
    }
}

function revertZoneChanges(canvas, floorSno, button) {
    const zones = canvas.getObjects().filter(obj => 
        obj.type === 'group' && obj.data && obj.data.zone_sno
    );
    
    // 원래 상태로 되돌리기
    zones.forEach(zone => {
        const zoneSno = zone.data.zone_sno;
        const original = originalZoneStates[floorSno] && originalZoneStates[floorSno][zoneSno];
        
        if (original) {
            zone.set({
                left: original.left,
                top: original.top,
                angle: original.angle,
                scaleX: original.scaleX,
                scaleY: original.scaleY
            });
        }
    });
    
    canvas.renderAll();
    console.log('Zone changes reverted');
    showToast('변경사항이 취소되었습니다.', 'info');
    
    // 편집 모드 종료
    disableEditMode(canvas, floorSno, button);
}

function disableEditMode(canvas, floorSno, button) {
    // 편집 모드 상태 업데이트
    editModeStates[floorSno] = false;
    
    // 버튼 상태 변경
    button.removeClass('btn-warning').addClass('btn-outline-warning');
    button.attr('title', '편집 모드');
    
    // 현재 선택된 객체 해제
    canvas.discardActiveObject();
    selectedZone = null;
    
    // 모든 구역을 편집 불가능하게 설정 (하지만 이벤트는 유지)
    canvas.getObjects().forEach(function(obj) {
        if (obj.type === 'group' && obj.data && obj.data.zone_sno) {
            obj.set({
                selectable: false,
                evented: true,
                hasControls: false,
                hasBorders: false,
                lockMovementX: true,
                lockMovementY: true,
                lockRotation: true,
                lockScalingX: true,
                lockScalingY: true
            });
        }
    });
    
    canvas.renderAll();
    console.log('Edit mode disabled for floor:', floorSno);
}

// 구역 확대 함수 (화면의 90%까지 확대, 중앙 배치 - 애니메이션 효과)
function zoomToZone(zoneObject, zoneSno) {
    const canvas = zoneObject.canvas;
    
    // 구역 경계 계산 (정확한 경계 포함)
    const boundingRect = zoneObject.getBoundingRect(true, true); // 변환 포함하여 정확한 경계 계산
    
    // 캔버스 크기
    const canvasWidth = canvas.getWidth();
    const canvasHeight = canvas.getHeight();
    
    // 화면의 90%를 목표로 하는 줌 레벨 계산
    const targetWidthRatio = 0.9;  // 화면 가로의 90%
    const targetHeightRatio = 0.9; // 화면 세로의 90%
    
    const scaleX = (canvasWidth * targetWidthRatio) / boundingRect.width;
    const scaleY = (canvasHeight * targetHeightRatio) / boundingRect.height;
    
    // 가로, 세로 중 작은 비율을 선택하여 구역이 화면에 완전히 들어가도록 함
    const targetZoom = Math.min(scaleX, scaleY);
    
    // 구역의 실제 중심점 계산
    const zoneCenterX = boundingRect.left + boundingRect.width / 2;
    const zoneCenterY = boundingRect.top + boundingRect.height / 2;
    
    // 캔버스의 중심점 계산
    const canvasCenterX = canvasWidth / 2;
    const canvasCenterY = canvasHeight / 2;
    
    // 현재 상태 저장
    const currentZoom = canvas.getZoom();
    const currentVpt = canvas.viewportTransform.slice(); // 복사
    
    console.log('Zone zoom to 90% details:', {
        boundingRect,
        canvasSize: { width: canvasWidth, height: canvasHeight },
        targetRatio: { width: targetWidthRatio, height: targetHeightRatio },
        scales: { scaleX, scaleY },
        currentZoom,
        targetZoom,
        zoneCenter: { x: zoneCenterX, y: zoneCenterY },
        canvasCenter: { x: canvasCenterX, y: canvasCenterY }
    });
    
    // 목표 뷰포트 변환 계산
    const tempCanvas = new fabric.Canvas(); // 임시 캔버스로 계산
    tempCanvas.setDimensions({ width: canvasWidth, height: canvasHeight });
    tempCanvas.zoomToPoint(new fabric.Point(zoneCenterX, zoneCenterY), targetZoom);
    
    const targetVpt = tempCanvas.viewportTransform.slice();
    const zoomedZoneCenterX = zoneCenterX * targetZoom + targetVpt[4];
    const zoomedZoneCenterY = zoneCenterY * targetZoom + targetVpt[5];
    
    const deltaX = canvasCenterX - zoomedZoneCenterX;
    const deltaY = canvasCenterY - zoomedZoneCenterY;
    
    // 최종 목표 뷰포트 변환
    const finalVpt = [
        targetZoom, 0, 0, targetZoom,
        targetVpt[4] + deltaX,
        targetVpt[5] + deltaY
    ];
    
    // 부드러운 애니메이션 효과
    const animationDuration = 600; // 0.6초
    const startTime = Date.now();
    
    // easeInOutCubic 함수 (부드러운 가속/감속)
    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }
    
    function animateZoom() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / animationDuration, 1);
        const easedProgress = easeInOutCubic(progress);
        
        // 줌 레벨 보간
        const currentAnimZoom = currentZoom + (targetZoom - currentZoom) * easedProgress;
        
        // 뷰포트 변환 보간
        const animVpt = [
            currentAnimZoom, 0, 0, currentAnimZoom,
            currentVpt[4] + (finalVpt[4] - currentVpt[4]) * easedProgress,
            currentVpt[5] + (finalVpt[5] - currentVpt[5]) * easedProgress
        ];
        
        // 캔버스에 적용
        canvas.setZoom(currentAnimZoom);
        canvas.setViewportTransform(animVpt);
        canvas.renderAll();
        
        if (progress < 1) {
            requestAnimationFrame(animateZoom);
        } else {
            // 애니메이션 완료
            onZoomAnimationComplete();
        }
    }
    
    // 애니메이션 완료 처리
    function onZoomAnimationComplete() {
        // 락커 그룹 추가 모드 활성화
        isLockerGroupMode = true;
        
        // 현재 구역 객체와 번호 저장
        currentZoneObject = zoneObject;
        currentZoneSno = zoneSno;
        console.log('✅ currentZoneObject와 currentZoneSno 설정됨:', {
            currentZoneObject: !!currentZoneObject,
            currentZoneSno: currentZoneSno
        });
        
        // 도구모음 숨기기
        $('.drawing-tools').hide();
        
        // 다른 층 비활성화 (현재 구역이 있는 캔버스 기준)
        const currentCanvas = Object.values(fabricCanvases).find(c => {
            const objects = c.getObjects();
            return objects.some(obj => obj === zoneObject);
        });
        if (currentCanvas) {
            disableOtherFloors(currentCanvas);
        } else {
            disableOtherFloors();
        }
        
        // 락커 그룹 추가 UI 표시 (약간의 지연으로 자연스럽게)
        setTimeout(() => {
            showLockerGroupUI(zoneObject, zoneSno);
        }, 200);
    }
    
    // 애니메이션 시작
    requestAnimationFrame(animateZoom);
}

// 락커 그룹 관리 UI 표시
function showLockerGroupUI(zoneObject, zoneSno) {
    // 기존 UI 제거
    $('.locker-group-ui').remove();
    
    const uiHtml = `
        <div class="locker-group-ui position-fixed animate__animated animate__slideInRight" style="top: 20px; right: 20px; z-index: 1050; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; padding: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); min-width: 320px; max-width: 400px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1 text-white"><i class="fas fa-cube me-2"></i>락커 그룹 추가 모드</h5>
                    <small class="text-white-50">구역: ${zoneObject.data.zone_nm}</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-light" onclick="closeLockerGroupUI()" title="모드 종료">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="alert alert-light mb-3" style="background: rgba(255,255,255,0.15); border: none; color: white;">
                <i class="fas fa-info-circle me-2"></i>
                <strong>안내:</strong> 이 구역에 락커 그룹을 추가할 수 있습니다.
            </div>
            
            <!-- 락커 그룹 도구모음 -->
            <div class="mb-3">
                <div class="d-flex gap-2 mb-2">
                    <button type="button" class="btn btn-warning btn-sm flex-fill" onclick="editSelectedLockerGroup()" style="border-radius: 6px;">
                        <i class="fas fa-edit me-1"></i>수정
                    </button>
                    <button type="button" class="btn btn-danger btn-sm flex-fill" onclick="deleteSelectedLockerGroup()" style="border-radius: 6px;">
                        <i class="fas fa-trash me-1"></i>삭제
                    </button>
                </div>
                <small class="text-white-50"><i class="fas fa-info-circle me-1"></i>락커 그룹을 선택한 후 수정/삭제가 가능합니다</small>
            </div>
            
            <div class="d-grid gap-2 mb-3">
                <button type="button" class="btn btn-primary btn-lg" onclick="showLockerGroupModal(${zoneSno})" style="border-radius: 8px; background-color: #0d6efd; border-color: #0d6efd; color: white; font-weight: 600;">
                    <i class="fas fa-plus me-2"></i>새 락커 그룹 추가
                </button>
                <button type="button" class="btn btn-outline-light" onclick="resetZoomView()" style="border-radius: 8px;">
                    <i class="fas fa-search-minus me-2"></i>전체 보기로 돌아가기
                </button>
            </div>
            
            <div class="small" style="color: rgba(255,255,255,0.8); line-height: 1.5;">
                <div class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>락커 그룹은 이 구역 내에서만 생성됩니다</div>
                <div class="mb-2"><i class="fas fa-mouse-pointer text-info me-2"></i>시작점과 끝점을 클릭하여 그룹 영역을 지정하세요</div>
                <div class="mb-2"><i class="fas fa-move text-primary me-2"></i>생성된 락커 그룹은 이동, 회전, 크기 조정이 가능합니다</div>
                <div><i class="fas fa-keyboard text-warning me-2"></i>ESC 키를 눌러 언제든 취소할 수 있습니다</div>
            </div>
        </div>
    `;
    
    $('body').append(uiHtml);
    
    // 페이드 인 애니메이션을 위한 약간의 지연
    setTimeout(() => {
        $('.locker-group-ui').addClass('show');
    }, 100);
    
    // ✅ 락커 그룹 추가 모드 진입 시 기존 락커 그룹을 다시 로드하지 않음
    // 이유: initCanvas()에서 이미 모든 락커 그룹이 로드되었으므로 중복 로드 방지
    console.log('🔍 showLockerGroupUI 함수 실행됨 - 락커 그룹 추가 모드 활성화');
    console.log('📊 현재 구역:', zoneSno);
    console.log('💡 기존 락커 그룹들은 이미 페이지 로드 시 로드되었으므로 다시 로드하지 않습니다.');
}

// 락커 그룹 UI 닫기 (애니메이션 효과로 원래 크기 복귀)
function closeLockerGroupUI() {
    $('.locker-group-ui').removeClass('animate__slideInRight').addClass('animate__slideOutRight');
    
    // 확대된 상태라면 애니메이션으로 원래 크기로 복귀
    const activeCanvas = getActiveCanvas();
    
    if (activeCanvas) {
        const currentZoom = activeCanvas.getZoom();
        const currentVpt = activeCanvas.viewportTransform.slice();
        
        // 확대된 상태인지 확인 (1.1배 이상 확대되어 있으면 애니메이션 복귀)
        if (currentZoom > 1.1 || Math.abs(currentVpt[4]) > 10 || Math.abs(currentVpt[5]) > 10) {
            // 애니메이션으로 원래 크기로 복귀
            animateZoomReset(activeCanvas, () => {
                // 애니메이션 완료 후 UI 정리
                finalizeUIClose();
            });
            return; // 여기서 리턴하여 바로 아래 코드는 실행하지 않음
        }
    }
    
    // 확대되지 않은 상태라면 바로 UI 정리
    finalizeUIClose();
    
    function finalizeUIClose() {
        setTimeout(() => {
            $('.locker-group-ui').remove();
        }, 300);
        
        // 락커 그룹 모드 해제
        isLockerGroupMode = false;
        isDrawingLockerGroup = false;
        currentZoneObject = null;
        currentZoneSno = null;
        lockerGroupPoints = [];
        
        console.log('🔄 락커 그룹 모드 변수들이 정리되었습니다');
        
        // 도구모음 다시 보이기
        $('.drawing-tools').show();
        
        // 다른 층 다시 활성화
        enableOtherFloors();
    }
}

// 줌 리셋 애니메이션 함수 (재사용 가능)
function animateZoomReset(canvas, onComplete = null) {
    // 현재 상태 저장
    const currentZoom = canvas.getZoom();
    const currentVpt = canvas.viewportTransform.slice();
    
    // 목표 상태 (원래 크기)
    const targetZoom = 1;
    const targetVpt = [1, 0, 0, 1, 0, 0];
    
    // 부드러운 애니메이션 효과
    const animationDuration = 500; // 0.5초
    const startTime = Date.now();
    
    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }
    
    function animateReset() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / animationDuration, 1);
        const easedProgress = easeInOutCubic(progress);
        
        // 줌 레벨 보간
        const currentAnimZoom = currentZoom + (targetZoom - currentZoom) * easedProgress;
        
        // 뷰포트 변환 보간
        const animVpt = [
            currentAnimZoom, 0, 0, currentAnimZoom,
            currentVpt[4] + (targetVpt[4] - currentVpt[4]) * easedProgress,
            currentVpt[5] + (targetVpt[5] - currentVpt[5]) * easedProgress
        ];
        
        // 캔버스에 적용
        canvas.setZoom(currentAnimZoom);
        canvas.setViewportTransform(animVpt);
        
        // _renderStroke 오버라이드로 인해 별도의 선 두께 조정 불필요
        canvas.renderAll();
        
        if (progress < 1) {
            requestAnimationFrame(animateReset);
        } else {
            // 애니메이션 완료
            canvas.renderAll();
            if (onComplete) {
                setTimeout(onComplete, 100);
            }
        }
    }
    
    // 애니메이션 시작
    requestAnimationFrame(animateReset);
}

// 락커 그룹 그리기 모드일 때 UI 업데이트
function updateLockerGroupUIForDrawing(formData) {
    const existingUI = $('.locker-group-ui');
    if (existingUI.length === 0) return;
    
    const drawingModeHtml = `
        <div class="alert alert-warning mb-3" style="background: rgba(255,193,7,0.2); border: 1px solid rgba(255,193,7,0.5); color: #856404; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-pencil-alt fa-spin me-2"></i>
                <div>
                    <strong>그리기 모드 활성</strong><br>
                    <small>"${formData.groupName}" 락커 그룹을 그리는 중...</small>
                </div>
            </div>
        </div>
        
        <div class="d-grid gap-2 mb-3">
            <button type="button" class="btn btn-primary btn-lg" onclick="showLockerGroupModal(currentZoneSno)" style="border-radius: 8px; background-color: #0d6efd; border-color: #0d6efd; color: white; font-weight: 600;">
                <i class="fas fa-plus me-2"></i>새 락커 그룹 추가
            </button>
            <button type="button" class="btn btn-outline-light" onclick="resetZoomView()" style="border-radius: 8px;">
                <i class="fas fa-search-minus me-2"></i>전체 보기로 돌아가기
            </button>
        </div>
        
        <div class="small" style="color: rgba(255,255,255,0.9); line-height: 1.6;">
            <div class="mb-2 p-2" style="background: rgba(255,255,255,0.1); border-radius: 6px;">
                <i class="fas fa-info-circle text-info me-2"></i>
                <strong>락커 그룹 정보:</strong><br>
                • 그룹명: ${formData.groupName}<br>
                • 타입: ${formData.lockerType}<br>
                • 총 ${formData.totalCount}칸 (가로 ${formData.horizontalCount}칸 × ${formData.verticalCount}단)
            </div>
            <div class="mb-2"><i class="fas fa-mouse-pointer text-success me-2"></i><strong>1단계:</strong> 시작점(좌상단) 클릭</div>
            <div class="mb-2"><i class="fas fa-mouse-pointer text-info me-2"></i><strong>2단계:</strong> 끝점(우하단) 클릭</div>
            <div><i class="fas fa-keyboard text-warning me-2"></i><strong>ESC</strong> 키로 언제든 취소 가능</div>
        </div>
    `;
    
    // 기존 버튼들과 안내문구 영역 교체
    existingUI.find('.alert, .d-grid, .small').remove();
    existingUI.append(drawingModeHtml);
}

// 전체 보기로 복귀 (애니메이션 효과)
function resetZoomView() {
    const activeCanvas = getActiveCanvas();
    
    if (!activeCanvas) return;
    
    // 현재 상태 확인
    const currentZoom = activeCanvas.getZoom();
    const currentVpt = activeCanvas.viewportTransform.slice();
    
    // 이미 원래 크기라면 바로 종료
    if (Math.abs(currentZoom - 1) < 0.01 && 
        Math.abs(currentVpt[4]) < 1 && Math.abs(currentVpt[5]) < 1) {
        isLockerGroupMode = false;
        closeLockerGroupUI();
        return;
    }
    
    // 애니메이션으로 원래 크기로 복귀
    animateZoomReset(activeCanvas, () => {
        // 애니메이션 완료 후 처리
        isLockerGroupMode = false;
        
        // 도구모음 다시 보이기
        $('.drawing-tools').show();
        
        // 다른 층 다시 활성화
        enableOtherFloors();
        
        setTimeout(() => {
            closeLockerGroupUI();
        }, 100);
    });
}

// 락커 그룹 폼 데이터 가져오기
function getLockerGroupFormData() {
    // 모달이 닫혔을 때 저장된 데이터 사용
    if (!window.lockerGroupFormData) {
        showToast('락커 그룹 정보가 없습니다.', 'error');
        return null;
    }
    
    return window.lockerGroupFormData;
}



// 락커 그룹 그리기 시작 함수 수정
function startLockerGroupDrawing(zoneSno) {
    // 폼 데이터 저장
    const horizontalCount = parseInt($('#horizontalCount').val());
    const verticalCount = parseInt($('#verticalCount').val());
    const totalCount = horizontalCount * verticalCount; // 자동 계산
    
    const formData = {
        groupName: $('#lockerGroupName').val().trim(),
        lockerType: $('#lockerType').val() === '기타' ? $('#customLockerType').val().trim() : $('#lockerType').val(),
        horizontalCount: horizontalCount,
        verticalCount: verticalCount,
        totalCount: totalCount,
        lockerWidth: parseFloat($('#lockerWidth').val()),
        lockerHeight: parseFloat($('#lockerHeight').val()),
        lockerDepth: parseFloat($('#lockerDepth').val())
    };
    
    // 그룹명이 비어있으면 자동 생성
    if (!formData.groupName) {
        formData.groupName = `${formData.lockerType}_${horizontalCount}x${verticalCount}`;
    }
    
    window.lockerGroupFormData = formData;
    
    console.log('🚀 락커 그룹 그리기 모드 활성화 중...');
    isDrawingLockerGroup = true;
    lockerGroupPoints = [];
    currentZoneSno = zoneSno;
    console.log('✅ isDrawingLockerGroup 설정됨:', isDrawingLockerGroup);
    console.log('✅ currentZoneSno 설정됨:', currentZoneSno);
    console.log('✅ lockerGroupPoints 초기화됨:', lockerGroupPoints);
    
    // 현재 활성 캔버스 찾기
    const activeCanvas = getActiveCanvas();
    
    if (!activeCanvas) {
        showToast('활성 캔버스를 찾을 수 없습니다.', 'error');
        return;
    }
    
    // 캔버스 커서 변경
    activeCanvas.defaultCursor = 'crosshair';
    
    // 락커 그룹 UI 업데이트 (그리기 모드 표시)
    updateLockerGroupUIForDrawing(formData);
    
    // 단계별 안내 메시지 표시
    showToast('📍 1단계: 락커 그룹의 시작점을 클릭하세요', 'info', 5000);
    
    console.log('Locker group drawing started for zone:', zoneSno, 'with data:', formData);
}

// 락커 그룹 생성 함수 (개별 락커 박스들을 자동으로 그리기)
function createLockerGroup() {
    debugger;
    const formData = window.lockerGroupFormData;
    if (!formData) {
        showToast('락커 그룹 데이터가 없습니다.', 'error');
        return;
    }
    
    // 현재 활성 캔버스 찾기
    const activeCanvas = getActiveCanvas();
    
    if (!activeCanvas) {
        showToast('활성 캔버스를 찾을 수 없습니다.', 'error');
        return;
    }
    
    console.log('Creating locker group with data:', formData);
    console.log('Points:', lockerGroupPoints);
    
    // 첫 번째 점: 기준점 (시작점)
    const startPoint = lockerGroupPoints[0];
    // 두 번째 점: 방향과 크기를 결정하는 점
    const endPoint = lockerGroupPoints[1];
    
    // 방향 벡터 계산
    const deltaX = endPoint.x - startPoint.x;
    const deltaY = endPoint.y - startPoint.y;
    const totalDistance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
    
    // 방향 결정 (가로 방향인지 세로 방향인지) - 직각으로 좌우냐 위아래냐
    const isHorizontal = Math.abs(deltaX) > Math.abs(deltaY);
    
    console.log(`Direction determined: ${isHorizontal ? 'Horizontal (좌우)' : 'Vertical (위아래)'}`);
    console.log(`Total distance: ${totalDistance} pixels`);
    
    // 락커 비율 (30:37)
    const lockerWidthRatio = formData.lockerWidth;   // 30
    const lockerDepthRatio = formData.lockerDepth;   // 37
    
    // 두 점 사이의 거리를 기준으로 락커 크기 계산
    let lockerPixelWidth, lockerPixelDepth;
    
    if (isHorizontal) {
        // 가로 방향: 전체 거리를 가로 칸수로 나누어 각 락커의 가로 크기 결정
        lockerPixelWidth = totalDistance / formData.horizontalCount;
        // 비율에 맞춰 깊이 계산 (30:37 비율 유지)
        lockerPixelDepth = lockerPixelWidth * (lockerDepthRatio / lockerWidthRatio);
    } else {
        // 세로 방향: 전체 거리를 가로 칸수로 나누어 각 락커의 깊이 크기 결정
        lockerPixelDepth = totalDistance / formData.horizontalCount;
        // 비율에 맞춰 가로 계산 (30:37 비율 유지)
        lockerPixelWidth = lockerPixelDepth * (lockerWidthRatio / lockerDepthRatio);
    }
    
    console.log(`Locker pixel size: ${lockerPixelWidth} x ${lockerPixelDepth}`);
    console.log(`Locker ratio maintained: ${lockerWidthRatio}:${lockerDepthRatio}`)
    
    let lockerBoxes = [];
    
    if (isHorizontal) {
        // 가로 방향: 락커들을 좌우로 배치
        createHorizontalLockers();
    } else {
        // 세로 방향: 락커들을 위아래로 배치
        createVerticalLockers();
    }
    
    function createHorizontalLockers() {
        console.log(`가로 방향 락커 생성:`);
        console.log(`- 락커 픽셀 크기: ${lockerPixelWidth} x ${lockerPixelDepth}`);
        console.log(`- 락커 비율: ${lockerWidthRatio}:${lockerDepthRatio}`);
        
        // 가로 칸수만큼 좌우로 나열
        for (let i = 0; i < formData.horizontalCount; i++) {
            // 각 락커의 위치 계산 (단순히 가로로 나열)
            const lockerLeft = startPoint.x + (i * lockerPixelWidth);
            const lockerTop = startPoint.y;
            
            const rect = new fabric.Rect({
                left: lockerLeft,
                top: lockerTop,
                width: lockerPixelWidth,      // 가로: 폭(width) 사용
                height: lockerPixelDepth,     // 가로: 깊이(depth) 사용
                fill: 'transparent',
                stroke: '#ff8c00',
                strokeWidth: 3,
                strokeUniform: true,
                selectable: true,
                evented: true,
                data: {
                    type: 'locker-cell',
                    groupName: formData.groupName,
                    row: 0, 
                    col: i,
                    zoneSno: currentZoneSno,
                    logicalHeight: formData.verticalCount,
                    realWidth: lockerWidthRatio,
                    realDepth: lockerDepthRatio,
                    realHeight: formData.lockerHeight,
                    direction: 'horizontal'  // 방향 정보 추가
                }
            });
            
            lockerBoxes.push(rect);
            activeCanvas.add(rect);
        }
        
        console.log(`Created ${formData.horizontalCount} horizontal lockers arranged left to right`);
    }
    
    function createVerticalLockers() {
        console.log(`세로 방향 락커 생성 (90도 회전):`);
        console.log(`- 락커 픽셀 크기: ${lockerPixelDepth} x ${lockerPixelWidth} (가로/세로 바뀜)`);
        console.log(`- 락커 비율: ${lockerDepthRatio}:${lockerWidthRatio} (depth:width)`);
        
        // 가로 칸수만큼 위아래로 나열
        for (let i = 0; i < formData.horizontalCount; i++) {
            // 각 락커의 위치 계산 (단순히 세로로 나열)
            const lockerLeft = startPoint.x;
            const lockerTop = startPoint.y + (i * lockerPixelWidth);  // 세로방향은 width만큼 간격
            
            const rect = new fabric.Rect({
                left: lockerLeft,
                top: lockerTop,
                width: lockerPixelDepth,      // 세로: 깊이(depth)를 width로 사용 (90도 회전)
                height: lockerPixelWidth,     // 세로: 폭(width)을 height로 사용 (90도 회전)
                fill: 'transparent',
                stroke: '#ff8c00',
                strokeWidth: 3,
                strokeUniform: true,
                selectable: true,
                evented: true,
                data: {
                    type: 'locker-cell',
                    groupName: formData.groupName,
                    row: i,
                    col: 0,
                    zoneSno: currentZoneSno,
                    logicalHeight: formData.verticalCount,
                    realWidth: lockerWidthRatio,
                    realDepth: lockerDepthRatio,
                    realHeight: formData.lockerHeight,
                    direction: 'vertical'    // 방향 정보 추가
                }
            });
            
            lockerBoxes.push(rect);
            activeCanvas.add(rect);
        }
        
        console.log(`Created ${formData.horizontalCount} vertical lockers arranged top to bottom (rotated 90°)`);
    }
    
    // 락커 그룹 변수를 블록 밖에서 선언
    let lockerGroup = null;
    
    // 락커 그룹 생성 (이동/회전/크기조정 가능하게)
    if (lockerBoxes.length > 0) {
        // 개별 사각형들을 캔버스에서 제거
        lockerBoxes.forEach(rect => activeCanvas.remove(rect));
        
        // 그룹으로 만들기
        lockerGroup = new fabric.Group(lockerBoxes, {
            selectable: true,
            evented: true,
            subTargetCheck: true, // 개별 락커도 선택 가능
            data: {
                type: 'locker-group',
                groupName: formData.groupName,
                zoneSno: currentZoneSno,
                totalCount: formData.totalCount,
                // 생성 시 계산된 실제 락커 크기 정보 저장
                actualLockerSize: {
                    pixelWidth: lockerPixelWidth,
                    pixelDepth: lockerPixelDepth,
                    method: 'user_created',
                    horizontalCount: formData.horizontalCount,
                    verticalCount: formData.verticalCount
                }
            }
        });
        
        // ✅ Scaling 완료 시 자동 저장
        lockerGroup.on('scaling', function() {
            console.log('🔍 새 락커 그룹 Scale 변경:', {
                scaleX: this.scaleX,
                scaleY: this.scaleY,
                width: this.width,
                height: this.height,
                scaledWidth: this.width * this.scaleX,
                scaledHeight: this.height * this.scaleY,
                groupName: this.data.groupName
            });
            
            // ✅ scaling 시에도 자동 저장 (group_sno가 있는 경우에만)
            const groupSno = this.data.groupSno || this.data.group_sno;
            
            if (this.data && groupSno) {
                console.log('💾 새 락커 그룹 Scale 변경 - 자동 저장 실행');
                
                // 안전한 데이터 참조 (내부 데이터 기반)
                const lockerObjects = this.getObjects();
                const horizontalCount = lockerObjects.length || 1;
                
                saveLockerGroupToDatabase(this, {
                    groupName: this.data.groupName,
                    lockerType: this.data.lockerType || '일반락커',
                    horizontalCount: horizontalCount,
                    verticalCount: this.data.logicalHeight || 1,
                    lockerWidth: this.data.realWidth || 30,
                    lockerDepth: this.data.realDepth || 37,
                    lockerHeight: this.data.realHeight || 30,
                    totalCount: this.data.totalCount || horizontalCount
                });
            } else {
                console.log('⚠️ Scaling 자동 저장 조건 불충족 (group_sno 없음)');
            }
        });

        // 수정 완료 시 자동 저장 (group_sno가 있는 경우에만)
        lockerGroup.on('modified', function() {
            console.log('💾 새 락커 그룹 수정 완료 - 자동 저장:', {
                scaleX: this.scaleX,
                scaleY: this.scaleY,
                left: this.left,
                top: this.top,
                angle: this.angle,
                groupName: this.data.groupName,
                hasGroupSno: !!(this.data.groupSno || this.data.group_sno)
            });
            
            // ✅ 디버깅을 위한 상세 로그
            console.log('🔍 Modified 이벤트 상세 data:', this.data);
            console.log('🔍 data.groupSno:', this.data.groupSno);
            console.log('🔍 data.group_sno:', this.data.group_sno);

            // ✅ 원래 조건으로 복원: group_sno가 있는 경우에만 UPDATE 실행
            const groupSno = this.data.groupSno || this.data.group_sno;
            
            if (this.data && groupSno) {
                // 안전한 데이터 참조 (내부 데이터 기반)
                const lockerObjects = this.getObjects();
                const horizontalCount = lockerObjects.length || 1;
                
                console.log(`🔄 락커 그룹 자동 저장 실행: ${this.data.groupName} (group_sno: ${groupSno})`);
                
                saveLockerGroupToDatabase(this, {
                    groupName: this.data.groupName,
                    lockerType: this.data.lockerType || '일반락커',
                    horizontalCount: horizontalCount,
                    verticalCount: this.data.logicalHeight || 1,
                    lockerWidth: this.data.realWidth || 30,
                    lockerDepth: this.data.realDepth || 37,
                    lockerHeight: this.data.realHeight || 30,
                    totalCount: this.data.totalCount || horizontalCount
                });
            } else {
                console.log('⚠️ 자동 저장 조건 불충족 (group_sno 없음):', {
                    hasData: !!this.data,
                    groupSno: groupSno,
                    type: this.data?.type
                });
            }
        });

        // 그룹을 캔버스에 추가
        activeCanvas.add(lockerGroup);
        
        // _renderStroke 오버라이드로 인해 별도의 선 두께 조정 불필요
        // 모든 락커 셀의 stroke width는 이미 3으로 설정됨
        
        console.log(`Created locker group with ${lockerBoxes.length} cells, group fill:`, lockerGroup.fill);
    }
    
    // 임시 점들 제거
    const tempObjects = activeCanvas.getObjects().filter(obj => 
        obj.data && obj.data.type === 'locker-group-temp'
    );
    tempObjects.forEach(obj => activeCanvas.remove(obj));
    
    // strokeUniform: true로 설정했으므로 줌과 관계없이 선 두께가 유지됨
    console.log('Locker cells created with strokeUniform: true for consistent line thickness (3px in group mode, 2px in normal mode)');
    
    // 캔버스 업데이트
    activeCanvas.renderAll();
    
    // _renderStroke 오버라이드로 인해 별도의 선 두께 업데이트 불필요
    activeCanvas.renderAll();
    
    // 로커 셀 투명도 설정 완료
    console.log('Locker cells created with transparent fill');
    
    // 그리기 모드 종료
    isDrawingLockerGroup = false;
    lockerGroupPoints = [];
    activeCanvas.defaultCursor = 'default';
    
    // ✅ 새로 생성된 락커 그룹을 데이터베이스에 즉시 저장하고 group_sno 설정
    if (lockerGroup) {
        console.log('🔄 새 락커 그룹 즉시 저장 시작:', formData.groupName);
        
        // 즉시 저장하여 group_sno를 받아오도록 함수 호출
        saveLockerGroupToDatabase(lockerGroup, {
            groupName: formData.groupName,
            lockerType: formData.lockerType || '일반락커',
            horizontalCount: formData.horizontalCount,
            verticalCount: formData.verticalCount,
            totalCount: formData.totalCount,
            lockerWidth: formData.lockerWidth,
            lockerDepth: formData.lockerDepth,
            lockerHeight: formData.lockerHeight
        });
    } else {
        console.warn('Locker group was not created, skipping database save');
    }
    
    // 성공 메시지
    showToast(`🎉 ${formData.groupName} 락커 그룹이 생성되었습니다! (${formData.horizontalCount}칸 × ${formData.verticalCount}단 = 총 ${formData.totalCount}칸)`, 'success', 4000);
    
    // 락커 그룹 생성 완료 후 그리기 모드 완전 종료
    // UI 재활성화 제거로 추가 클릭 방지
    console.log('Locker group creation completed, drawing mode fully disabled');
    
    console.log(`Created locker grid for group: ${formData.groupName}`);
}

// 선택된 락커 그룹 수정
function editSelectedLockerGroup() {
    const activeCanvas = getActiveCanvas();
    
    if (!activeCanvas) {
        showToast('활성 캔버스를 찾을 수 없습니다.', 'error', 3000);
        return;
    }
    
    const activeObject = activeCanvas.getActiveObject();
    
    if (!activeObject) {
        showToast('수정할 락커 그룹을 선택해주세요.', 'warning', 3000);
        return;
    }
    
    // 락커 그룹인지 확인
    if (!activeObject.data || activeObject.data.type !== 'locker-group') {
        showToast('락커 그룹만 수정할 수 있습니다.', 'warning', 3000);
        return;
    }
    
    // 선택된 락커 그룹의 데이터를 가져와서 수정 모달 표시
    const lockerGroupData = activeObject.data;
    showLockerGroupEditModal(lockerGroupData, activeObject);
    
    console.log('Selected locker group for editing:', activeObject.data);
}

// 공통 락커 그룹 삭제 함수
function performLockerGroupDeletion(activeObject, groupName, groupSno, targetCanvas) {
    try {
        // 데이터베이스에서 삭제 (group_sno가 있는 경우에만)
        if (groupSno) {
            $.ajax({
                url: '<?= site_url('locker/ajax_delete_group') ?>',
                type: 'POST',
                data: {
                    group_sno: groupSno,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                beforeSend: function() {
                    console.log('Deleting locker group from database:', groupSno);
                },
                success: function(response) {
                    console.log('Database delete response:', response);
                    
                    if (response.status === 'success') {
                        // 캔버스에서도 제거
                        targetCanvas.remove(activeObject);
                        targetCanvas.renderAll();
                        
                        showToast(`🗑️ "${groupName}" 락커 그룹이 완전히 삭제되었습니다.`, 'success', 3000);
                        console.log('✅ Locker group deleted from both canvas and database:', groupName);
                    } else {
                        showToast('데이터베이스 삭제에 실패했습니다: ' + (response.message || '알 수 없는 오류'), 'error', 3000);
                        console.error('Database deletion failed:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error during deletion:', { xhr, status, error });
                    showToast('삭제 중 서버 오류가 발생했습니다.', 'error', 3000);
                }
            });
        } else {
            // group_sno가 없는 경우 (아직 저장되지 않은 임시 그룹)
            console.log('⚠️ group_sno 없음, 캔버스에서만 제거');
            targetCanvas.remove(activeObject);
            targetCanvas.renderAll();
            
            showToast(`🗑️ "${groupName}" 락커 그룹이 삭제되었습니다. (임시 그룹)`, 'success', 3000);
            console.log('Deleted temporary locker group from canvas only:', groupName);
        }
        
    } catch (error) {
        console.error('Error deleting locker group:', error);
        showToast('락커 그룹 삭제 중 오류가 발생했습니다: ' + error.message, 'error', 3000);
    }
}

// 선택된 락커 그룹 삭제 (버튼용)
function deleteSelectedLockerGroup() {
    const activeCanvas = getActiveCanvas();
    
    if (!activeCanvas) {
        showToast('활성 캔버스를 찾을 수 없습니다.', 'error', 3000);
        return;
    }
    
    const activeObject = activeCanvas.getActiveObject();
    
    if (!activeObject) {
        showToast('삭제할 락커 그룹을 선택해주세요.', 'warning', 3000);
        return;
    }
    
    // 락커 그룹인지 확인
    if (!activeObject.data || activeObject.data.type !== 'locker-group') {
        showToast('락커 그룹만 삭제할 수 있습니다.', 'warning', 3000);
        return;
    }
    
    const groupName = activeObject.data.groupName;
    const groupSno = activeObject.data.groupSno || activeObject.data.group_sno;
    
    // 삭제 확인
    if (confirm(`"${groupName}" 락커 그룹을 삭제하시겠습니까?\n\n⚠️ 이 작업은 되돌릴 수 없습니다.`)) {
        performLockerGroupDeletion(activeObject, groupName, groupSno, activeCanvas);
    }
}

// 락커 그룹 수정 모달 표시
function showLockerGroupEditModal(lockerGroupData, lockerGroupObject) {
    // 기존 모달 제거
    $('#lockerGroupEditModal').remove();
    
    // 락커 그룹의 기존 데이터에서 개별 락커 수 계산
    const lockerObjects = lockerGroupObject.getObjects ? lockerGroupObject.getObjects() : [];
    const currentHorizontalCount = lockerObjects.length;
    const currentVerticalCount = lockerGroupData.logicalHeight || 1;
    
    const modalHtml = `
        <div id="lockerGroupEditModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>락커 그룹 수정</h5>
                        <button type="button" class="btn-close" id="closeLockerGroupEditModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 1. 그룹명 (전체 너비) -->
                        <div class="mb-3">
                            <label for="editLockerGroupName" class="form-label">그룹명</label>
                            <input type="text" class="form-control" id="editLockerGroupName" value="${lockerGroupData.groupName || ''}">
                        </div>
                        
                        <!-- 2. 락커 타입 (전체 너비) -->
                        <div class="mb-3">
                            <label for="editLockerType" class="form-label">락커 타입</label>
                            <input type="text" class="form-control" id="editLockerType" value="${lockerGroupData.lockerType || '일반락커'}">
                        </div>
                        
                        <!-- 3. 가로 칸수 + 단수 (나란히) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editHorizontalCount" class="form-label">가로 칸수</label>
                                    <input type="number" class="form-control" id="editHorizontalCount" min="1" value="${currentHorizontalCount}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editVerticalCount" class="form-label">단수 (높이)</label>
                                    <input type="number" class="form-control" id="editVerticalCount" min="1" value="${currentVerticalCount}">
                                    <small class="text-muted">정면에서 보았을 때 세로로 쌓이는 층수</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 4. 가로 크기 + 깊이 (나란히) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editLockerWidth" class="form-label">가로 크기 (cm)</label>
                                    <input type="number" class="form-control" id="editLockerWidth" min="1" step="0.1" value="${lockerGroupData.realWidth || 30}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editLockerDepth" class="form-label">깊이 (cm)</label>
                                    <input type="number" class="form-control" id="editLockerDepth" min="1" step="0.1" value="${lockerGroupData.realDepth || 37}">
                                </div>
                            </div>
                        </div>
                        
                        <!-- 5. 세로 크기 (전체 너비) -->
                        <div class="mb-3">
                            <label for="editLockerHeight" class="form-label">세로 크기 (cm)</label>
                            <input type="number" class="form-control" id="editLockerHeight" min="1" step="0.1" value="${lockerGroupData.realHeight || 30}">
                        </div>
                        
                        <!-- 6. 총 칸수 표시 -->
                        <div class="mb-3">
                            <label class="form-label">총 칸수</label>
                            <div class="form-control bg-light" id="editTotalCount">${currentHorizontalCount * currentVerticalCount}칸</div>
                            <small class="text-muted">가로 칸수 × 단수로 자동 계산됩니다</small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>주의:</strong> 가로 칸수를 변경하면 락커 그룹의 크기가 재조정됩니다.<br>
                            크기나 비율 변경은 즉시 반영되며, 기존 설정된 개별 락커 정보는 유지됩니다.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelLockerGroupEdit">취소</button>
                        <button type="button" class="btn btn-primary" id="saveLockerGroupEdit">수정 완료</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('lockerGroupEditModal'), {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();
    
    // 가로 칸수, 단수 변경 시 총 칸수 자동 계산
    $('#editHorizontalCount, #editVerticalCount').on('input', function() {
        const horizontal = parseInt($('#editHorizontalCount').val()) || 1;
        const vertical = parseInt($('#editVerticalCount').val()) || 1;
        $('#editTotalCount').text(`${horizontal * vertical}칸`);
    });
    
    // X 버튼 이벤트
    $('#closeLockerGroupEditModal').on('click', function() {
        modal.hide();
    });
    
    // 취소 버튼 이벤트
    $('#cancelLockerGroupEdit').on('click', function() {
        modal.hide();
    });
    
    // 수정 완료 버튼 이벤트
    $('#saveLockerGroupEdit').on('click', function() {
        if (validateLockerGroupEditForm()) {
            updateLockerGroup(lockerGroupObject);
            modal.hide();
        }
    });
    
    // 모달이 닫힐 때 이벤트 정리
    $('#lockerGroupEditModal').on('hidden.bs.modal', function() {
        $(this).remove();
    });
}

// 락커 그룹 수정 폼 검증
function validateLockerGroupEditForm() {
    const groupName = $('#editLockerGroupName').val().trim();
    const lockerType = $('#editLockerType').val().trim();
    const horizontalCount = parseInt($('#editHorizontalCount').val());
    const verticalCount = parseInt($('#editVerticalCount').val());
    const lockerWidth = parseFloat($('#editLockerWidth').val());
    const lockerDepth = parseFloat($('#editLockerDepth').val());
    const lockerHeight = parseFloat($('#editLockerHeight').val());
    
    if (!lockerType) {
        showToast('락커 타입을 입력해주세요.', 'warning', 3000);
        $('#editLockerType').focus();
        return false;
    }
    
    if (!horizontalCount || horizontalCount < 1) {
        showToast('가로 칸수는 1 이상이어야 합니다.', 'warning', 3000);
        $('#editHorizontalCount').focus();
        return false;
    }
    
    if (!verticalCount || verticalCount < 1) {
        showToast('단수는 1 이상이어야 합니다.', 'warning', 3000);
        $('#editVerticalCount').focus();
        return false;
    }
    
    if (!lockerWidth || lockerWidth <= 0) {
        showToast('가로 크기는 0보다 커야 합니다.', 'warning', 3000);
        $('#editLockerWidth').focus();
        return false;
    }
    
    if (!lockerDepth || lockerDepth <= 0) {
        showToast('깊이는 0보다 커야 합니다.', 'warning', 3000);
        $('#editLockerDepth').focus();
        return false;
    }
    
    if (!lockerHeight || lockerHeight <= 0) {
        showToast('세로 크기는 0보다 커야 합니다.', 'warning', 3000);
        $('#editLockerHeight').focus();
        return false;
    }
    
    return true;
}

// 락커 그룹 업데이트
function updateLockerGroup(lockerGroupObject) {
    try {
        const groupName = $('#editLockerGroupName').val().trim() || '락커그룹';
        const lockerType = $('#editLockerType').val().trim();
        const horizontalCount = parseInt($('#editHorizontalCount').val());
        const verticalCount = parseInt($('#editVerticalCount').val());
        const lockerWidth = parseFloat($('#editLockerWidth').val());
        const lockerDepth = parseFloat($('#editLockerDepth').val());
        const lockerHeight = parseFloat($('#editLockerHeight').val());
        const totalCount = horizontalCount * verticalCount;
        
        // 활성 캔버스 가져오기
        const activeCanvas = Object.values(fabricCanvases).find(c => 
            $(c.wrapperEl).closest('.drawing-container').is(':visible')
        );
        
        if (!activeCanvas) {
            throw new Error('활성 캔버스를 찾을 수 없습니다.');
        }
        
        // 현재 락커 객체들 가져오기
        const currentLockers = lockerGroupObject.getObjects ? lockerGroupObject.getObjects() : [];
        const currentCount = currentLockers.length;
        
        console.log(`Updating locker group: ${currentCount} -> ${horizontalCount} lockers`);
        
        // 기존 group_sno 추출 (가장 먼저 선언)
        const originalGroupSno = lockerGroupObject.data.groupSno || lockerGroupObject.data.group_sno;
        
        // 락커 그룹의 현재 위치와 방향 정보 저장
        const groupLeft = lockerGroupObject.left;
        const groupTop = lockerGroupObject.top;
        const groupAngle = lockerGroupObject.angle || 0;
        
        // 첫 번째 락커의 위치를 기준으로 방향 결정
        let isHorizontal = true; // 기본값은 가로 방향
        if (currentLockers.length >= 2) {
            const first = currentLockers[0];
            const second = currentLockers[1];
            const deltaX = Math.abs(second.left - first.left);
            const deltaY = Math.abs(second.top - first.top);
            isHorizontal = deltaX > deltaY;
        }
        
        // 기존 actualLockerSize 정보 우선 사용 (크기 일관성 유지)
        const originalActualSize = lockerGroupObject.data.actualLockerSize;
        let newLockerPixelWidth, newLockerPixelDepth;
        
        if (originalActualSize && originalActualSize.pixelWidth && originalActualSize.pixelDepth) {
            // ✅ 원래 생성 시의 정확한 락커 사이즈 유지
            newLockerPixelWidth = originalActualSize.pixelWidth;
            newLockerPixelDepth = originalActualSize.pixelDepth;
            console.log('🔄 수정 모드: 원본 락커 사이즈 유지됨:', {
                width: newLockerPixelWidth,
                depth: newLockerPixelDepth,
                method: originalActualSize.method
            });
        } else {
            // 원본 사이즈 정보가 없는 경우에만 다시 계산
            console.log('⚠️ 원본 사이즈 정보 없음, 기존 락커 기준으로 계산');
            const widthRatio = lockerWidth;
            const depthRatio = lockerDepth;
            
            if (currentLockers.length > 0) {
                // 기존 락커들의 전체 크기를 기준으로 새로운 크기 계산
                if (isHorizontal) {
                    // 가로 방향: 전체 너비를 새로운 칸수로 나누기
                    const totalWidth = currentLockers.length > 1 ? 
                        Math.abs(currentLockers[currentLockers.length - 1].left - currentLockers[0].left) + currentLockers[0].width :
                        currentLockers[0].width;
                    newLockerPixelWidth = totalWidth / horizontalCount;
                    newLockerPixelDepth = newLockerPixelWidth * (depthRatio / widthRatio);
                } else {
                    // 세로 방향: 전체 높이를 새로운 칸수로 나누기  
                    const totalHeight = currentLockers.length > 1 ?
                        Math.abs(currentLockers[currentLockers.length - 1].top - currentLockers[0].top) + currentLockers[0].height :
                        currentLockers[0].height;
                    newLockerPixelDepth = totalHeight / horizontalCount;
                    newLockerPixelWidth = newLockerPixelDepth * (widthRatio / depthRatio);
                }
            } else {
                // 기본 크기 설정
                newLockerPixelWidth = 90;  // 30cm * 3px
                newLockerPixelDepth = 111; // 37cm * 3px
            }
        }
        
        console.log(`New locker size: ${newLockerPixelWidth} x ${newLockerPixelDepth}`);
        console.log(`Direction: ${isHorizontal ? 'Horizontal' : 'Vertical'}`);
        
        // 그룹에서 기존 락커들 제거
        activeCanvas.remove(lockerGroupObject);
        
        // 새로운 락커들 생성
        let newLockerBoxes = [];
        const startLeft = currentLockers.length > 0 ? currentLockers[0].left : groupLeft;
        const startTop = currentLockers.length > 0 ? currentLockers[0].top : groupTop;
        
        for (let i = 0; i < horizontalCount; i++) {
            let lockerLeft, lockerTop, lockerWidth, lockerHeight;
            
            if (isHorizontal) {
                // 가로 방향 배치: 락커가 정상 방향 (폭 x 깊이)
                lockerLeft = startLeft + (i * newLockerPixelWidth);
                lockerTop = startTop;
                lockerWidth = newLockerPixelWidth;      // 가로: 폭(width) 사용
                lockerHeight = newLockerPixelDepth;     // 가로: 깊이(depth) 사용
            } else {
                // 세로 방향 배치: 락커가 90도 회전 (깊이 x 폭)
                lockerLeft = startLeft;
                lockerTop = startTop + (i * newLockerPixelWidth);  // 세로방향은 width만큼 간격
                lockerWidth = newLockerPixelDepth;      // 세로: 깊이(depth)를 width로 사용 (90도 회전)
                lockerHeight = newLockerPixelWidth;     // 세로: 폭(width)을 height로 사용 (90도 회전)
            }
            
            const rect = new fabric.Rect({
                left: lockerLeft,
                top: lockerTop,
                width: lockerWidth,
                height: lockerHeight,
                fill: 'transparent',
                stroke: '#ff8c00',
                strokeWidth: isLockerGroupMode ? 3 : 2,  // 락커 그룹 모드에서는 3px, 일반 모드에서는 2px
                strokeUniform: true,
                selectable: true,
                evented: true,
                data: {
                    type: 'locker-cell',
                    groupName: groupName,
                    row: isHorizontal ? 0 : i,
                    col: isHorizontal ? i : 0,
                    zoneSno: lockerGroupObject.data.zoneSno,
                    groupSno: originalGroupSno,        // ✅ 기존 group_sno 유지
                    group_sno: originalGroupSno,       // ✅ 기존 group_sno 유지 (호환성)
                    logicalHeight: verticalCount,
                    realWidth: lockerWidth,
                    realDepth: lockerDepth,
                    realHeight: lockerHeight,
                    direction: isHorizontal ? 'horizontal' : 'vertical'  // 방향 정보 추가
                }
            });
            
            newLockerBoxes.push(rect);
        }
        
        // 새로운 그룹 생성 (기존 group_sno 유지 - 이미 위에서 선언됨)
        
        const newLockerGroup = new fabric.Group(newLockerBoxes, {
            left: groupLeft,
            top: groupTop,
            angle: groupAngle,
            selectable: true,
            evented: true,
            subTargetCheck: true,
            data: {
                type: 'locker-group',
                groupName: groupName,
                lockerType: lockerType,
                zoneSno: lockerGroupObject.data.zoneSno,
                groupSno: originalGroupSno,        // ✅ 기존 group_sno 유지
                group_sno: originalGroupSno,       // ✅ 기존 group_sno 유지 (호환성)
                logicalHeight: verticalCount,
                totalCount: totalCount,
                realWidth: lockerWidth,
                realDepth: lockerDepth,
                realHeight: lockerHeight,
                // ✅ 수정 후에도 원본 사이즈 정보 유지
                actualLockerSize: originalActualSize || {
                    pixelWidth: newLockerPixelWidth,
                    pixelDepth: newLockerPixelDepth,
                    method: 'updated_group',
                    horizontalCount: horizontalCount,
                    verticalCount: verticalCount
                }
            }
        });
        
        console.log('✅ 수정 모드: 기존 group_sno 유지됨:', originalGroupSno);
        
        // 새 그룹을 캔버스에 추가
        activeCanvas.add(newLockerGroup);
        
        // 새 그룹 선택
        activeCanvas.setActiveObject(newLockerGroup);
        
        // 캔버스 다시 그리기
        activeCanvas.renderAll();
        
        // 데이터베이스에 저장
        saveLockerGroupToDatabase(newLockerGroup, {
            groupName,
            lockerType,
            horizontalCount,
            verticalCount,
            totalCount,
            lockerWidth,
            lockerDepth,
            lockerHeight
        });
        
        showToast(`🎉 "${groupName}" 락커 그룹이 수정되었습니다! (${horizontalCount}칸 × ${verticalCount}단 = 총 ${totalCount}칸)`, 'success', 4000);
        console.log('Locker group updated successfully:', {
            groupName,
            lockerType,
            horizontalCount,
            verticalCount,
            totalCount,
            dimensions: { width: lockerWidth, depth: lockerDepth, height: lockerHeight },
            pixelSize: { width: newLockerPixelWidth, depth: newLockerPixelDepth }
        });
        
    } catch (error) {
        console.error('Error updating locker group:', error);
        showToast('락커 그룹 수정 중 오류가 발생했습니다: ' + error.message, 'error', 3000);
    }
}

// 락커 그룹 데이터베이스 저장 함수
function saveLockerGroupToDatabase(lockerGroupObject, updateData) {
    // 🔧 안전한 group_sno 추출 (배열 처리 포함)
    let groupSno = lockerGroupObject.data.groupSno || lockerGroupObject.data.group_sno;
    
    // group_sno가 배열인 경우 첫 번째 값 사용
    if (Array.isArray(groupSno)) {
        console.log('⚠️ groupSno가 배열로 반환됨:', groupSno);
        groupSno = parseInt(groupSno[0]) || null;
        console.log('🔧 배열에서 첫 번째 값 추출:', groupSno);
    } else if (groupSno) {
        groupSno = parseInt(groupSno) || null;
    }
    
    console.log('🔍 최종 group_sno:', groupSno, '(타입:', typeof groupSno, ')');
    console.log('📊 전체 락커 그룹 데이터:', lockerGroupObject.data);
    
    // group_sno가 없으면 새로 생성 (INSERT), 있으면 수정 (UPDATE)
    const isNewGroup = !groupSno;
    
    // 실제 락커 크기 정보 추출 (생성 시 계산된 값)
    const actualLockerSize = lockerGroupObject.data.actualLockerSize || {};
    
    // 방향 정보 추출 (개별 락커들의 data에서)
    const lockerObjects = lockerGroupObject.getObjects ? lockerGroupObject.getObjects() : [];
    const direction = lockerObjects.length > 0 ? (lockerObjects[0].data?.direction || 'horizontal') : 'horizontal';
    
            // 좌표 정보 생성 (위치, 크기, 회전, 방향 정보 포함)
    const groupCoords = {
        left: lockerGroupObject.left,
        top: lockerGroupObject.top,
        angle: lockerGroupObject.angle || 0,
        width: lockerGroupObject.width || lockerGroupObject.getWidth(),
        height: lockerGroupObject.height || lockerGroupObject.getHeight(),
        scaleX: lockerGroupObject.scaleX !== undefined ? lockerGroupObject.scaleX : 1,
        scaleY: lockerGroupObject.scaleY !== undefined ? lockerGroupObject.scaleY : 1,
        // ✅ 실제 락커 크기 정보 추가 (생성 시 계산된 정확한 값)
        actualLockerPixelWidth: actualLockerSize.pixelWidth,
        actualLockerPixelDepth: actualLockerSize.pixelDepth,
        creationMethod: actualLockerSize.method || 'unknown',
        // ✅ 원본 그룹 크기 저장 (scale 적용 이전)
        originalGroupWidth: lockerGroupObject.width || lockerGroupObject.getWidth(),
        originalGroupHeight: lockerGroupObject.height || lockerGroupObject.getHeight(),
        // ✅ 락커 방향 정보 추가
        direction: direction
    };
    
    console.log('📦 저장할 크기 정보:', {
        width: groupCoords.width,
        height: groupCoords.height,
        scaleX: groupCoords.scaleX,
        scaleY: groupCoords.scaleY,
        calculatedWidth: groupCoords.width * groupCoords.scaleX,
        calculatedHeight: groupCoords.height * groupCoords.scaleY,
        actualLockerPixelWidth: groupCoords.actualLockerPixelWidth,
        actualLockerPixelDepth: groupCoords.actualLockerPixelDepth,
        creationMethod: groupCoords.creationMethod
    });
    
    // 현재 활성 캔버스에서 구역 정보 가져오기
    const zoneSno = lockerGroupObject.data.zoneSno || currentZoneSno;
    
    if (!zoneSno) {
        console.error('Zone SNO not found for locker group');
        showToast('구역 정보를 찾을 수 없어 데이터베이스 저장에 실패했습니다.', 'warning', 3000);
        return;
    }
    
    const ajaxUrl = isNewGroup ? 
        '<?= site_url('locker/ajax_add_group') ?>' : 
        '<?= site_url('locker/ajax_update_group') ?>';
    
    const ajaxData = {
        group_nm: updateData.groupName,
        locker_type: updateData.lockerType,
        rows: updateData.verticalCount,  // 단수 (논리적 높이)
        cols: updateData.horizontalCount, // 가로 칸수
        locker_width: updateData.lockerWidth,
        locker_depth: updateData.lockerDepth,
        locker_height: updateData.lockerHeight,
        total_count: updateData.totalCount,
        group_coords: JSON.stringify(groupCoords),
        zone_sno: zoneSno
    };
    
    // 수정인 경우 group_sno 추가
    if (!isNewGroup) {
        ajaxData.group_sno = groupSno;
    }
    
    // 디버깅: 전송할 데이터 로그 출력
    console.log('🔍 AJAX 전송 데이터:', ajaxData);
    console.log('🔍 URL:', ajaxUrl);
    console.log('🔍 isNewGroup:', isNewGroup);
    
    $.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: ajaxData,
        dataType: 'json',
        beforeSend: function() {
            console.log('Sending locker group update to server...');
        },
        success: function(response) {
            console.log('Database save response:', response);
            
            if (response.status === 'success') {
                console.log('✅ 락커 그룹이 데이터베이스에 성공적으로 저장되었습니다.');
                
                // 🔧 안전한 group_sno 처리 (서버 응답)
                let finalGroupSno = groupSno; // 기본값은 기존 groupSno
                
                if (response.group_sno) {
                    if (Array.isArray(response.group_sno)) {
                        finalGroupSno = parseInt(response.group_sno[0]) || groupSno;
                        console.log('⚠️ 서버 응답 group_sno가 배열임:', response.group_sno, '→ 첫 번째 값 사용:', finalGroupSno);
                    } else {
                        finalGroupSno = parseInt(response.group_sno) || groupSno;
                        console.log('✅ 서버 응답 group_sno:', response.group_sno, '→ 정수 변환:', finalGroupSno);
                    }
                }
                
                if (isNewGroup && finalGroupSno) {
                    console.log('🆕 새 락커 그룹 생성 완료:', finalGroupSno);
                    showToast(`새 락커 그룹이 데이터베이스에 저장되었습니다.`, 'info', 2000);
                }
                
                // ✅ 락커 그룹 객체에 group_sno 즉시 업데이트 (정수값으로)
                console.log('🔧 락커 그룹 객체에 group_sno 즉시 설정:', finalGroupSno);
                console.log('🔍 설정 전 현재 data:', lockerGroupObject.data);
                
                // 기존 데이터 보존하면서 group_sno만 업데이트
                const updatedData = {
                    ...lockerGroupObject.data,
                    groupSno: finalGroupSno,
                    group_sno: finalGroupSno,
                    zoneSno: zoneSno
                };
                
                // ✅ 여러 방법으로 강제 설정 (Fabric.js 호환성)
                lockerGroupObject.data = updatedData;
                lockerGroupObject.set('data', updatedData);
                
                // ✅ 추가: 직접 속성 설정 (더 강력한 방법)
                if (!lockerGroupObject.data) {
                    lockerGroupObject.data = {};
                }
                lockerGroupObject.data.groupSno = finalGroupSno;
                lockerGroupObject.data.group_sno = finalGroupSno;
                lockerGroupObject.data.zoneSno = zoneSno;
                
                console.log('✅ 락커 그룹 객체 data 업데이트 완료:', lockerGroupObject.data);
                console.log('🔍 검증: groupSno =', lockerGroupObject.data.groupSno);
                console.log('🔍 검증: group_sno =', lockerGroupObject.data.group_sno);
                
                // 개별 락커 객체들도 안전하게 업데이트
                if (lockerGroupObject.getObjects) {
                    const lockerObjects = lockerGroupObject.getObjects();
                    console.log(`🔧 개별 락커 ${lockerObjects.length}개에 group_sno(${finalGroupSno}) 업데이트`);
                    lockerObjects.forEach((lockerObj, index) => {
                        if (lockerObj.data) {
                            const updatedLockerData = {
                                ...lockerObj.data,
                                groupSno: finalGroupSno,
                                group_sno: finalGroupSno
                            };
                            
                            // 즉시 반영되도록 강제 설정
                            lockerObj.data = updatedLockerData;
                            lockerObj.set('data', updatedLockerData);
                            
                            console.log(`  └─ 락커 ${index + 1}: group_sno = ${finalGroupSno} 즉시 설정`);
                        }
                    });
                }
                
                console.log('✅ 모든 락커 객체에 group_sno 즉시 업데이트 완료');
                
                // ✅ 캔버스 강제 업데이트로 변경사항 즉시 반영
                if (lockerGroupObject.canvas) {
                    lockerGroupObject.canvas.renderAll();
                    console.log('✅ 캔버스 강제 업데이트로 변경사항 반영');
                }
                
            } else {
                console.error('Database save failed:', response.message);
                showToast('데이터베이스 저장 실패: ' + response.message, 'error', 3000);
            }
        },
        error: function(xhr, status, error) {
            console.error('Database save error:', {
                status: status,
                error: error,
                response: xhr.responseText
            });
            
            try {
                const errorResponse = JSON.parse(xhr.responseText);
                showToast('데이터베이스 저장 오류: ' + errorResponse.message, 'error', 3000);
            } catch (e) {
                showToast('데이터베이스 저장 중 네트워크 오류가 발생했습니다.', 'error', 3000);
            }
        }
    });
}

// 페이지 로드 시 이벤트 연결 (중복 제거됨 - 메인 키보드 이벤트에 통합)
$(document).ready(function() {
    // 기본 페이지 로드 이벤트들
});

// 락커 그룹 폼 검증 함수
function validateLockerGroupForm() {
    console.log('폼 검증 시작');
    
    // 락커 타입 검증
    const lockerType = $('#lockerType').val();
    console.log('락커 타입:', lockerType);
    if (!lockerType) {
        showToast('락커 타입을 선택해주세요.', 'error');
        $('#lockerType').focus();
        return false;
    }
    
    // 기타 선택 시 직접 입력값 검증
    if (lockerType === '기타') {
        const customType = $('#customLockerType').val().trim();
        if (!customType) {
            showToast('락커 타입을 직접 입력해주세요.', 'error');
            $('#customLockerType').focus();
            return false;
        }
    }
    
    // 가로 칸수 검증
    const horizontalCount = parseInt($('#horizontalCount').val());
    console.log('가로 칸수:', horizontalCount);
    if (!horizontalCount || horizontalCount < 1) {
        showToast('가로 칸수를 1 이상 입력해주세요.', 'error');
        $('#horizontalCount').focus();
        return false;
    }
    
    // 단수 검증
    const verticalCount = parseInt($('#verticalCount').val());
    console.log('단수:', verticalCount);
    if (!verticalCount || verticalCount < 1) {
        showToast('단수(높이)를 1 이상 입력해주세요.', 'error');
        $('#verticalCount').focus();
        return false;
    }
    
    // 락커 크기 검증
    const lockerWidth = parseFloat($('#lockerWidth').val());
    if (!lockerWidth || lockerWidth <= 0) {
        showToast('가로 크기를 올바르게 입력해주세요.', 'error');
        $('#lockerWidth').focus();
        return false;
    }
    
    const lockerHeight = parseFloat($('#lockerHeight').val());
    if (!lockerHeight || lockerHeight <= 0) {
        showToast('세로 크기를 올바르게 입력해주세요.', 'error');
        $('#lockerHeight').focus();
        return false;
    }
    
    const lockerDepth = parseFloat($('#lockerDepth').val());
    if (!lockerDepth || lockerDepth <= 0) {
        showToast('깊이를 올바르게 입력해주세요.', 'error');
        $('#lockerDepth').focus();
        return false;
    }
    
    console.log('모든 폼 검증 통과');
    return true;
}

// 총칸수 자동 계산 (가로칸수 × 단수)
function calculateTotalCount() {
    const horizontalCount = parseInt($('#horizontalCount').val()) || 0;
    const verticalCount = parseInt($('#verticalCount').val()) || 0;
    const totalCount = horizontalCount * verticalCount;
    
    // UI에 표시
    const info = `총 칸수: ${totalCount}칸 (가로 ${horizontalCount}칸 × ${verticalCount}단 = ${totalCount}칸)`;
    $('.total-count-info').remove();
    $('#autoCalculate').parent().append(`<div class="total-count-info small text-info mt-1">${info}</div>`);
}

// 다른 층 비활성화 함수
function disableOtherFloors(targetCanvas) {
    // 현재 활성 캔버스 찾기 (파라미터로 전달된 캔버스 우선)
    const activeCanvas = targetCanvas || getActiveCanvas();
    
    if (!activeCanvas) return;
    
    const currentFloorSno = $(activeCanvas.wrapperEl).closest('.drawing-container').data('floor');
    
    console.log(`🔒 disableOtherFloors 실행 - 활성 층: ${currentFloorSno}`);
    
    // 모든 층 카드를 순회하면서 현재 층이 아닌 경우 비활성화
    $('.drawing-container').each(function() {
        const floorSno = $(this).data('floor');
        const floorCard = $(this).closest('.card');
        
        if (floorSno != currentFloorSno) {
            console.log(`  ➤ 비활성화: 층 ${floorSno}`);
            // 카드 전체를 어둡게 하고 포인터 이벤트 차단
            floorCard.addClass('floor-disabled');
            
            // 비활성화 오버레이 추가
            if (!floorCard.find('.floor-disabled-overlay').length) {
                const overlay = $(`
                    <div class="floor-disabled-overlay" style="
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(0,0,0,0.6);
                        z-index: 1000;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 0.375rem;
                    ">
                        <div class="text-center text-white">
                            <i class="fas fa-lock fa-2x mb-2"></i><br>
                            <small>락커그룹 추가 모드 중<br>이 층은 사용할 수 없습니다</small>
                        </div>
                    </div>
                `);
                floorCard.css('position', 'relative').append(overlay);
            }
        }
    });
    
    console.log('Other floors disabled for locker group mode');
}

// 다른 층 다시 활성화 함수
function enableOtherFloors() {
    // 모든 층 카드에서 비활성화 클래스 제거 및 오버레이 제거
    $('.drawing-container').each(function() {
        const floorCard = $(this).closest('.card');
        floorCard.removeClass('floor-disabled');
        floorCard.find('.floor-disabled-overlay').remove();
    });
    
    console.log('All floors enabled again');
}

// 락커 그룹 모드 종료 함수
function exitLockerGroupMode() {
    console.log('🚪 락커 그룹 추가 모드를 종료합니다...');
    
    // 전역 상태 초기화
    isLockerGroupMode = false;
    isDrawingLockerGroup = false;
    currentZoneSno = null;
    lockerGroupPoints = [];
    
    // UI 업데이트
    $('.add-locker-group').removeClass('active');
    $('.locker-group-ui').hide();
    $('.zone-highlight').removeClass('zone-highlight');
    
    // 다른 층 활성화
    enableOtherFloors();
    
    // 임시 객체들 제거
    const activeCanvas = getActiveCanvas();
    
    if (activeCanvas) {
        const objectsToRemove = activeCanvas.getObjects().filter(obj => 
            obj.data && obj.data.type === 'locker-group-temp'
        );
        objectsToRemove.forEach(obj => activeCanvas.remove(obj));
        activeCanvas.renderAll();
    }
    
    console.log('✅ 락커 그룹 추가 모드가 종료되었습니다.');
    showToast('락커 그룹 추가 모드를 종료했습니다.', 'info', 2000);
}

// 현재 구역의 락커 그룹들을 다시 로드하는 함수
function reloadLockerGroupsForCurrentZone() {
    console.log('🔄 현재 구역의 락커 그룹들을 다시 로드합니다...');
    
    // 현재 활성 캔버스 찾기
    const activeCanvas = getActiveCanvas();
    
    if (!activeCanvas) {
        console.error('활성 캔버스를 찾을 수 없습니다.');
        return;
    }
    
    const floorSno = $(activeCanvas.wrapperEl).closest('.drawing-container').data('floor');
    
    // 현재 캔버스의 모든 구역을 가져와서 락커 그룹들을 로드
    $.ajax({
        url: '<?= site_url('locker/ajax_get_zones') ?>',
        type: 'GET',
        data: { 
            floor_sno: floorSno
        },
        dataType: 'json',
        success: function(response) {
            console.log('구역 정보 로드 응답:', response);
            
            let zones = response.zones;
            if (!zones && response.status && response.status.zones) {
                zones = response.status.zones;
            }
            
            if (Array.isArray(zones) && zones.length > 0) {
                // 각 구역에 대해 락커 그룹들을 로드
                zones.forEach((zone) => {
                    loadLockerGroupsForZone(activeCanvas, zone.zone_sno);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('구역 정보 로드 실패:', error);
            showToast('구역 정보를 불러오는데 실패했습니다.', 'error', 3000);
        }
    });
}

// 특정 구역의 락커 그룹들을 로드하는 함수
function loadLockerGroupsForZone(canvas, zoneSno) {
    console.log(`🔍 구역 ${zoneSno}의 락커 그룹들을 로드합니다...`);
    console.log(`📊 요청 URL: <?= site_url('locker/get_groups') ?>`);
    console.log(`📊 요청 파라미터: zone_sno=${zoneSno}`);
    
    $.ajax({
        url: '<?= site_url('locker/get_groups') ?>',
        type: 'GET',
        data: { 
            zone_sno: zoneSno
        },
        dataType: 'json',
        beforeSend: function() {
            console.log(`📤 AJAX 요청 전송 중... (구역: ${zoneSno})`);
        },
        success: function(groups) {
            console.log(`📥 AJAX 응답 수신:`, groups);
            console.log(`📊 응답 타입:`, typeof groups);
            console.log(`📊 응답 길이:`, Array.isArray(groups) ? groups.length : 'Not array');
            
            if (Array.isArray(groups) && groups.length > 0) {
                console.log(`🎯 ${groups.length}개의 락커 그룹을 캔버스에 추가합니다...`);
                groups.forEach((group, index) => {
                    console.log(`🔸 락커 그룹 ${index + 1}:`, group);
                    addLockerGroupToCanvas(canvas, group);
                });
                canvas.renderAll();
                console.log(`✅ 구역 ${zoneSno}에 ${groups.length}개의 락커 그룹을 추가했습니다.`);
                

            } else {
                console.log(`ℹ️ 구역 ${zoneSno}에 락커 그룹이 없습니다.`);
            }
        },
        error: function(xhr, status, error) {
            console.error(`❌ 구역 ${zoneSno}의 락커 그룹 로드 실패:`, {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            showToast('락커 그룹을 불러오는데 실패했습니다.', 'error', 5000);
        }
    });
}

// 락커 그룹을 캔버스에 추가하는 함수
function addLockerGroupToCanvas(canvas, groupData) {
    console.log('🎯 락커 그룹을 캔버스에 추가:', groupData);
    
    try {
        // 좌표 파싱
        let coords = {};
        if (groupData.group_coords && typeof groupData.group_coords === 'string') {
            try {
                coords = JSON.parse(groupData.group_coords);
                console.log(`📍 저장된 좌표:`, coords);
            } catch (e) {
                console.error('좌표 파싱 오류:', e);
                coords = { left: 100, top: 100, angle: 0 };
            }
        } else {
            coords = { left: 100, top: 100, angle: 0 };
        }
        
        // 락커 박스들 생성
        const lockerBoxes = [];
        const horizontalCount = parseInt(groupData.group_cols) || 1;
        const verticalCount = parseInt(groupData.group_rows) || 1;
        
        // ✅ 저장된 실제 락커 크기 정보 우선 사용 (크기 일관성 보장)
        let lockerPixelWidth, lockerPixelDepth;
        
        // 1순위: 저장된 실제 락커 크기 정보 사용 (생성 시 계산된 정확한 값)
        if (coords.actualLockerPixelWidth && coords.actualLockerPixelDepth) {
            lockerPixelWidth = coords.actualLockerPixelWidth;
            lockerPixelDepth = coords.actualLockerPixelDepth;
            
            console.log(`🎯 저장된 실제 락커 크기 사용: ${lockerPixelWidth}x${lockerPixelDepth} (방법: ${coords.creationMethod})`);
        }
        // 2순위: ✅ 원본 그룹 크기 정보 사용 (scale 미적용 순수 크기)
        else if (coords.originalGroupWidth && coords.originalGroupHeight) {
            // ✅ 원본 크기를 사용하여 scale 누적 방지
            lockerPixelWidth = coords.originalGroupWidth / horizontalCount;
            lockerPixelDepth = coords.originalGroupHeight;
            
            console.log(`📏 원본 그룹 크기 사용: 원본 ${coords.originalGroupWidth}x${coords.originalGroupHeight} → 개별 락커 ${lockerPixelWidth}x${lockerPixelDepth} (scale 누적 방지)`);
        }
        // 3순위: 저장된 그룹 크기에서 계산 (호환성 유지)
        else if (coords.width && coords.height) {
            // ❌ 기존 방식: scale 누적으로 인한 크기 증가 문제
            console.warn('⚠️ 호환성 모드: scale 누적 가능성 있음');
            
            // 스케일 정보 무시하고 원본 크기만 사용
            lockerPixelWidth = coords.width / horizontalCount;
            lockerPixelDepth = coords.height;
            
            console.log(`📏 호환성 모드 - 그룹 크기: 기본 ${coords.width}x${coords.height} → 개별 락커 ${lockerPixelWidth}x${lockerPixelDepth} (scale 정보 무시)`);
        } 
        // 4순위: 기본 크기 사용
        else {
            // 저장된 크기 정보가 없는 경우: 기본 비율로 최소 크기 설정
            const defaultWidth = 30; // 기본 30cm
            const defaultDepth = 37;  // 기본 37cm
            
            // 매우 작은 픽셀 크기로 설정 (cm를 픽셀로 직접 변환)
            lockerPixelWidth = defaultWidth;
            lockerPixelDepth = defaultDepth;
            
            console.log(`📏 기본 크기 사용: ${lockerPixelWidth}x${lockerPixelDepth} (저장된 크기 정보 없음)`);
        }
        
        console.log(`📐 락커 크기 계산:`, {
            method: coords.width && coords.height ? 'stored_size' : 'default_size',
            storedGroupSize: coords.width && coords.height ? `${coords.width}x${coords.height}` : 'none',
            realWidth: groupData.locker_width,
            realDepth: groupData.locker_depth,
            calculatedPixelWidth: lockerPixelWidth,
            calculatedPixelDepth: lockerPixelDepth,
            horizontalCount: horizontalCount,
            verticalCount: verticalCount,
            totalCalculatedWidth: lockerPixelWidth * horizontalCount,
            totalCalculatedDepth: lockerPixelDepth
        });
        
        // 방향 결정: 저장된 크기 정보로부터 방향 추정
        let isHorizontalDirection = true; // 기본값: 가로 방향
        
        // 1. 저장된 좌표에서 방향 정보 확인 (새로 생성된 그룹들)
        if (coords.direction) {
            isHorizontalDirection = coords.direction === 'horizontal';
            console.log(`🧭 저장된 방향 정보 사용: ${coords.direction}`);
        }
        // 2. 그룹 크기 비율로 방향 추정 (기존 그룹들 호환성)
        else if (coords.width && coords.height) {
            // 가로가 세로보다 긴 경우 가로 방향으로 추정
            isHorizontalDirection = coords.width >= coords.height;
            console.log(`🧭 크기 비율로 방향 추정: ${isHorizontalDirection ? '가로' : '세로'} (${coords.width}x${coords.height})`);
        }
        // 3. 칸수 정보로 방향 추정
        else if (horizontalCount > 1 && verticalCount === 1) {
            isHorizontalDirection = true;
            console.log(`🧭 칸수 정보로 방향 추정: 가로 (${horizontalCount}칸 x ${verticalCount}단)`);
        }
        
        console.log(`📐 최종 락커 방향: ${isHorizontalDirection ? '가로 (→)' : '세로 (↓)'}`);
        
        for (let i = 0; i < horizontalCount; i++) {
            let rect;
            
            if (isHorizontalDirection) {
                // 가로 방향: 락커들을 좌우로 배치
                rect = new fabric.Rect({
                    left: i * lockerPixelWidth,
                    top: 0,
                    width: lockerPixelWidth,      // 가로: 폭(width) 사용
                    height: lockerPixelDepth,     // 가로: 깊이(depth) 사용
                    fill: 'transparent',
                    stroke: '#ff8c00',
                    strokeWidth: isLockerGroupMode ? 3 : 2,  // 락커 그룹 모드에서는 3px, 일반 모드에서는 2px
                    strokeUniform: true,
                    selectable: true,
                    evented: true,
                    data: {
                        type: 'locker-cell',
                        groupName: groupData.group_nm,
                        row: 0,
                        col: i,
                        zoneSno: groupData.zone_sno,
                        groupSno: groupData.group_sno,
                        logicalHeight: verticalCount,
                        realWidth: groupData.locker_width,
                        realDepth: groupData.locker_depth,
                        realHeight: groupData.locker_height || 30,
                        direction: 'horizontal'
                    }
                });
            } else {
                // 세로 방향: 락커들을 위아래로 배치 (90도 회전)
                rect = new fabric.Rect({
                    left: 0,
                    top: i * lockerPixelWidth,   // 세로방향은 width만큼 간격
                    width: lockerPixelDepth,     // 세로: 깊이(depth)를 width로 사용 (90도 회전)
                    height: lockerPixelWidth,    // 세로: 폭(width)을 height로 사용 (90도 회전)
                    fill: 'transparent',
                    stroke: '#ff8c00',
                    strokeWidth: isLockerGroupMode ? 3 : 2,  // 락커 그룹 모드에서는 3px, 일반 모드에서는 2px
                    strokeUniform: true,
                    selectable: true,
                    evented: true,
                    data: {
                        type: 'locker-cell',
                        groupName: groupData.group_nm,
                        row: i,
                        col: 0,
                        zoneSno: groupData.zone_sno,
                        groupSno: groupData.group_sno,
                        logicalHeight: verticalCount,
                        realWidth: groupData.locker_width,
                        realDepth: groupData.locker_depth,
                        realHeight: groupData.locker_height || 30,
                        direction: 'vertical'
                    }
                });
            }
            
            lockerBoxes.push(rect);
        }
        
        // 락커 그룹 생성
        const lockerGroup = new fabric.Group(lockerBoxes, {
            left: coords.left || 100,
            top: coords.top || 100,
            angle: coords.angle || 0,
            selectable: true,
            evented: true,
            subTargetCheck: true,
            data: {
                type: 'locker-group',
                groupName: groupData.group_nm,
                lockerType: groupData.locker_type || '일반',
                zoneSno: groupData.zone_sno,
                groupSno: groupData.group_sno,
                logicalHeight: verticalCount,
                totalCount: horizontalCount * verticalCount,
                realWidth: groupData.locker_width,
                realDepth: groupData.locker_depth,
                realHeight: groupData.locker_height || 30,
                // ✅ 로딩 시에도 실제 락커 크기 정보 저장
                actualLockerSize: {
                    pixelWidth: lockerPixelWidth,
                    pixelDepth: lockerPixelDepth,
                    method: 'loaded_from_db',
                    horizontalCount: horizontalCount,
                    verticalCount: verticalCount
                }
            }
        });
        
        // ✅ Scaling 완료 시 자동 저장
        lockerGroup.on('scaling', function() {
            console.log('🔍 로딩된 락커 그룹 Scale 변경:', {
                scaleX: this.scaleX,
                scaleY: this.scaleY,
                width: this.width,
                height: this.height,
                scaledWidth: this.width * this.scaleX,
                scaledHeight: this.height * this.scaleY,
                groupName: this.data.groupName
            });
            
            // ✅ scaling 시에도 자동 저장 (group_sno가 있는 경우에만)
            const groupSno = this.data.groupSno || this.data.group_sno;
            
            if (this.data && groupSno) {
                console.log('💾 로딩된 락커 그룹 Scale 변경 - 자동 저장 실행');
                
                // 안전한 데이터 참조 (내부 데이터 기반)
                const lockerObjects = this.getObjects();
                const horizontalCount = lockerObjects.length || 1;
                
                saveLockerGroupToDatabase(this, {
                    groupName: this.data.groupName,
                    lockerType: this.data.lockerType || '일반락커',
                    horizontalCount: horizontalCount,
                    verticalCount: this.data.logicalHeight || 1,
                    lockerWidth: this.data.realWidth || 30,
                    lockerDepth: this.data.realDepth || 37,
                    lockerHeight: this.data.realHeight || 30,
                    totalCount: this.data.totalCount || horizontalCount
                });
            } else {
                console.log('⚠️ 로딩된 그룹 Scaling 자동 저장 조건 불충족 (group_sno 없음)');
            }
        });

        // 수정 완료 시 자동 저장 (group_sno가 있는 경우에만)
        lockerGroup.on('modified', function() {
            console.log('💾 로딩된 락커 그룹 수정 완료 - 자동 저장:', {
                scaleX: this.scaleX,
                scaleY: this.scaleY,
                left: this.left,
                top: this.top,
                angle: this.angle,
                groupName: this.data.groupName,
                hasGroupSno: !!(this.data.groupSno || this.data.group_sno)
            });
            
            // ✅ 원래 조건으로 복원: group_sno가 있는 경우에만 UPDATE 실행
            const groupSno = this.data.groupSno || this.data.group_sno;
            
            if (this.data && groupSno) {
                // 안전한 데이터 참조 (내부 데이터 기반)
                const lockerObjects = this.getObjects();
                const horizontalCount = lockerObjects.length || 1;
                
                console.log(`🔄 로딩된 락커 그룹 자동 저장 실행: ${this.data.groupName} (group_sno: ${groupSno})`);
                
                saveLockerGroupToDatabase(this, {
                    groupName: this.data.groupName,
                    lockerType: this.data.lockerType || '일반락커',
                    horizontalCount: horizontalCount,
                    verticalCount: this.data.logicalHeight || 1,
                    lockerWidth: this.data.realWidth || 30,
                    lockerDepth: this.data.realDepth || 37,
                    lockerHeight: this.data.realHeight || 30,
                    totalCount: this.data.totalCount || horizontalCount
                });
            } else {
                console.log('⚠️ 로딩된 그룹 자동 저장 조건 불충족 (group_sno 없음):', {
                    hasData: !!this.data,
                    groupSno: groupSno,
                    type: this.data?.type
                });
            }
        });

        canvas.add(lockerGroup);
        console.log('✅ 락커 그룹이 캔버스에 추가되었습니다:', groupData.group_nm);
        
        return lockerGroup;
        
    } catch (error) {
        console.error('락커 그룹 추가 중 오류:', error);
        return null;
    }
}

// 다른 층 활성화 함수
function enableOtherFloors() {
    $('.drawing-container').each(function() {
        const floorCard = $(this).closest('.card');
        floorCard.removeClass('floor-disabled');
        floorCard.find('.floor-disabled-overlay').remove();
    });
    console.log('모든 층이 다시 활성화되었습니다.');
}
</script>

<style>
/* 다른 층 비활성화 스타일 */
.floor-disabled {
    opacity: 0.5;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.floor-disabled-overlay {
    animation: fadeInOverlay 0.3s ease;
}

@keyframes fadeInOverlay {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* 비활성화된 층에 대한 추가 시각적 효과 */
.floor-disabled .card {
    filter: grayscale(30%);
    transition: filter 0.3s ease;
}

.floor-disabled-overlay .text-white {
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
}

/* 락커 그룹 UI 버튼 호버 효과 */
.locker-group-ui .btn-primary:hover {
    background-color: #5a8cc7 !important;
    border-color: #5a8cc7 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    transition: all 0.2s ease-in-out;
}

.locker-group-ui .btn-primary {
    transition: all 0.2s ease-in-out;
}

.locker-group-ui .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
    border-color: rgba(255, 255, 255, 0.6) !important;
    color: rgba(255, 255, 255, 0.9) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(255, 255, 255, 0.2);
    transition: all 0.2s ease-in-out;
}

.locker-group-ui .btn-outline-light {
    transition: all 0.2s ease-in-out;
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.4);
}

.locker-group-ui .btn-outline-light:hover i {
    color: rgba(255, 255, 255, 0.9) !important;
}

/* 그리기 취소 버튼 호버 효과 */
.locker-group-ui .btn-danger:hover {
    background-color: #c53030 !important;
    border-color: #c53030 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    transition: all 0.2s ease-in-out;
}

.locker-group-ui .btn-danger {
    transition: all 0.2s ease-in-out;
}

/* 작은 Alert 스타일 */
.alert-sm {
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.75rem;
    border-radius: 0.375rem;
}

.alert-sm small {
    font-size: 0.875rem;
}

/* 컴팩트한 모달 스타일 */
.modal-md .modal-body {
    padding: 1rem;
}

.modal-md .modal-header {
    padding: 0.75rem 1rem;
}

.modal-md .modal-footer {
    padding: 0.75rem 1rem;
}

.modal-md .form-label {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.modal-md .form-control-sm,
.modal-md .form-select-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.modal-md .mb-3 {
    margin-bottom: 0.75rem !important;
}
</style> 