<?php
$sDef = SpoqDef();
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $view['title'] ?></h1>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-title">
            <?= $view['floor']['floor_nm'] ?> - <?= $view['zone']['zone_nm'] ?>
            <span class="badge bg-<?= $view['zone']['zone_gendr'] === 'M' ? 'primary' : ($view['zone']['zone_gendr'] === 'F' ? 'pink' : 'success') ?>">
                <?= $view['zone']['zone_gendr'] === 'M' ? '남성 전용' : ($view['zone']['zone_gendr'] === 'F' ? '여성 전용' : '혼용') ?>
            </span>
        </div>
        <div class="panel-heading-btn">
            <a href="<?= site_url('locker/manage') ?>" class="btn btn-sm btn-default">
                <i class="fas fa-arrow-left"></i> 도면으로 돌아가기
            </a>
        </div>
    </div>
    
    <div class="panel-body">
        <!-- 락커 그룹 추가 버튼 -->
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#groupModal">
                <i class="fas fa-plus"></i> 락커 그룹 추가
            </button>
        </div>

        <!-- 락커 그룹 목록 -->
        <div class="row">
            <?php foreach ($view['groups'] as $group): ?>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $group['group_nm'] ?></h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>크기:</strong> 
                                    <?= $group['group_rows'] ?>행 × <?= $group['group_cols'] ?>열
                                </p>
                                <p class="mb-1">
                                    <strong>락커 규격:</strong><br>
                                    가로 <?= $group['locker_width'] ?>mm ×
                                    깊이 <?= $group['locker_depth'] ?>mm
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-sm btn-info view-lockers" data-group="<?= $group['group_sno'] ?>">
                                    <i class="fas fa-th"></i> 락커 보기
                                </button>
                                <button class="btn btn-sm btn-danger delete-group" data-group="<?= $group['group_sno'] ?>">
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

<!-- 락커 그룹 추가 모달 -->
<div class="modal fade" id="groupModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-lightblue">
                <h5 class="modal-title">락커 그룹 추가</h5>
                <button type="button" class="close2" data-bs-dismiss="modal">
                    <i class="fas fa-times" style="font-size:18px;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="groupForm">
                    <input type="hidden" name="zone_sno" value="<?= $view['zone']['zone_sno'] ?>">
                    <div class="mb-3">
                        <label class="form-label">그룹 이름</label>
                        <input type="text" class="form-control" name="group_nm" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">가로 칸수</label>
                            <input type="number" class="form-control" name="group_rows" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">세로 칸수</label>
                            <input type="number" class="form-control" name="group_cols" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">가로 크기(mm)</label>
                            <input type="number" class="form-control" name="locker_width" min="1" value="300">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">깊이(mm)</label>
                            <input type="number" class="form-control" name="locker_depth" min="1" value="450">
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

<style>
.badge.bg-pink {
    background-color: #ff69b4 !important;
}
</style>

<script>
$(document).ready(function() {
    // 락커 그룹 저장
    $('#saveGroup').on('click', function() {
        const form = $('#groupForm');
        const data = {
            zone_sno: form.find('[name="zone_sno"]').val(),
            group_nm: form.find('[name="group_nm"]').val(),
            group_rows: form.find('[name="group_rows"]').val(),
            group_cols: form.find('[name="group_cols"]').val(),
            locker_width: form.find('[name="locker_width"]').val(),
            locker_depth: form.find('[name="locker_depth"]').val(),
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        };

        $.ajax({
            url: '<?= site_url('locker/ajax_add_group') ?>',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#groupModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: '락커 그룹이 추가되었습니다.'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: response.message || '락커 그룹 추가에 실패했습니다.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: '서버 오류가 발생했습니다.'
                });
            }
        });
    });

    // 락커 그룹 삭제
    $('.delete-group').on('click', function() {
        const group_sno = $(this).data('group');
        
        Swal.fire({
            icon: 'warning',
            title: '락커 그룹 삭제',
            text: '이 락커 그룹을 삭제하시겠습니까?',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: '삭제',
            cancelButtonText: '취소',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url('locker/ajax_delete_group') ?>',
                    type: 'POST',
                    data: {
                        group_sno: group_sno,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '락커 그룹이 삭제되었습니다.'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message || '삭제에 실패했습니다.'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: '서버 오류가 발생했습니다.'
                        });
                    }
                });
            }
        });
    });

    // 락커 보기
    $('.view-lockers').on('click', function() {
        const group_sno = $(this).data('group');
        window.location.href = `<?= site_url('locker/group_detail') ?>/${group_sno}`;
    });
});
</script>

<?= $jsinc ?> 