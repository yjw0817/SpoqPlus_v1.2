<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">락커 관리</h4>
            </div>
            <div class="card-body">
                <!-- 도면 업로드 -->
                <div class="mb-4">
                    <h5>도면 업로드</h5>
                    <form id="floorForm" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="floor_nm" placeholder="층 이름" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="floor_ord" placeholder="정렬순서" required>
                        </div>
                        <div class="col-md-4">
                            <input type="file" class="form-control" name="floor_img" accept="image/jpeg,image/png" required>
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
                                    <div class="drawing-container" data-floor="<?= $floor['floor_sno'] ?>">
                                        <canvas class="drawing-board"></canvas>
                                        <img src="<?= base_url('/uploads/floors/' . $floor['comp_cd'] . '/' . $floor['bcoff_cd'] . '/' . $floor['floor_img']) ?>" class="floor-image" style="display: none;" crossorigin="anonymous">
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-primary add-zone" data-floor="<?= $floor['floor_sno'] ?>">구역 추가</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="zoneForm">
                    <input type="hidden" name="floor_sno" id="zone_floor_sno">
                    <input type="hidden" name="zone_coords" id="zone_coords">
                    <div class="mb-3">
                        <label class="form-label">구역 이름</label>
                        <input type="text" class="form-control" name="zone_nm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">성별 구분</label>
                        <select class="form-select" name="zone_gendr" required>
                            <option value="M">남성전용</option>
                            <option value="F">여성전용</option>
                            <option value="A">혼용</option>
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
<div class="modal fade" id="groupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">락커 그룹 추가</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="groupForm">
                    <input type="hidden" name="zone_sno" id="group_zone_sno">
                    <input type="hidden" name="group_coords" id="group_coords">
                    <div class="mb-3">
                        <label class="form-label">그룹 이름</label>
                        <input type="text" class="form-control" name="group_nm" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">가로 칸수</label>
                            <input type="number" class="form-control" name="group_rows" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">세로 칸수</label>
                            <input type="number" class="form-control" name="group_cols" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">가로 크기(mm)</label>
                            <input type="number" class="form-control" name="locker_width">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">세로 크기(mm)</label>
                            <input type="number" class="form-control" name="locker_depth">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" id="saveGroup">저장</button>
            </div>
        </div>
    </div>
</div>

<!-- 정면도 설정 모달 -->
<div class="modal fade" id="frontModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">정면도 설정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="frontForm">
                    <input type="hidden" name="group_sno" id="front_group_sno">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">세로 칸수</label>
                            <input type="number" class="form-control" name="front_rows" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">높이(mm)</label>
                            <input type="number" class="form-control" name="front_height">
                        </div>
                    </div>
                </form>
                <div id="frontPreview" class="mt-3">
                    <!-- 정면도 미리보기가 여기에 그려집니다 -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" id="saveFront">저장</button>
            </div>
        </div>
    </div>
</div>

<style>
.drawing-container {
    position: relative;
    width: 100%;
    height: 400px;
}
.drawing-board {
    width: 100%;
    height: 100%;
    border: 1px solid #ccc;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/assets/js/fabric.min.js"></script>
<script>
$(document).ready(function() {
    // 도면 업로드
    $('#floorForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?= site_url('locker/upload_floor') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // 구역 추가 버튼 클릭
    $('.add-zone').on('click', function() {
        var floor_sno = $(this).data('floor');
        $('#zone_floor_sno').val(floor_sno);
        $('#zoneModal').modal('show');
    });

    // 구역 저장
    $('#saveZone').on('click', function() {
        var formData = new FormData($('#zoneForm')[0]);
        
        $.ajax({
            url: '<?= site_url('locker/save_zone') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#zoneModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // 락커 그룹 저장
    $('#saveGroup').on('click', function() {
        var formData = new FormData($('#groupForm')[0]);
        
        $.ajax({
            url: '<?= site_url('locker/save_group') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#groupModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // 정면도 저장
    $('#saveFront').on('click', function() {
        var formData = new FormData($('#frontForm')[0]);
        
        $.ajax({
            url: '<?= site_url('locker/save_front') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#frontModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // 도면 캔버스 초기화
    $('.drawing-container').each(function() {
        const container = $(this);
        const canvas = container.find('.drawing-board')[0];
        const img = container.find('.floor-image')[0];
        
        // Canvas 크기 설정
        canvas.width = container.width();
        canvas.height = container.height();
        
        // Fabric.js 캔버스 초기화
        const fabricCanvas = new fabric.Canvas(canvas);
        container.data('canvas', fabricCanvas);
        
        // 이미지 로드 에러 처리
        img.onerror = function() {
            console.error('이미지 로드 실패:', img.src);
            // 에러 발생 시 빈 캔버스 표시
            fabricCanvas.renderAll();
        };
        
        // 이미지 로드 후 캔버스에 추가
        img.onload = function() {
            fabric.Image.fromURL(img.src, function(oImg) {
                if (!oImg) {
                    console.error('Fabric.js 이미지 생성 실패');
                    return;
                }
                
                // 이미지 크기를 캔버스에 맞게 조정
                const scale = Math.min(
                    fabricCanvas.width / oImg.width,
                    fabricCanvas.height / oImg.height
                );
                
                oImg.scale(scale);
                
                // 이미지를 캔버스 중앙에 배치
                oImg.set({
                    left: (fabricCanvas.width - oImg.width * scale) / 2,
                    top: (fabricCanvas.height - oImg.height * scale) / 2
                });
                
                fabricCanvas.setBackgroundImage(oImg, fabricCanvas.renderAll.bind(fabricCanvas));
            }, { crossOrigin: 'anonymous' });
        };
        
        // 이미지 다시 로드
        img.src = img.src;
    });
});
</script>
<?= $this->endSection() ?> 