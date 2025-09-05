<style>
.branch-detail-card {
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.info-table td {
    padding: 8px 12px;
}
.info-table tr:hover {
    background-color: #f8f9fa;
}
.stat-box {
    background: #fff;
    border: 2px solid #e9ecef;
    padding: 25px 20px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}
.stat-box:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.stat-box h3 {
    font-size: 2.2rem;
    margin: 0;
    font-weight: 700;
}
.stat-box p {
    margin: 8px 0 0 0;
    color: #6c757d;
    font-size: 0.95rem;
    font-weight: 500;
}
.stat-box.primary {
    border-color: #007bff;
}
.stat-box.primary h3 {
    color: #007bff;
}
.stat-box.success {
    border-color: #28a745;
}
.stat-box.success h3 {
    color: #28a745;
}
.stat-box.warning {
    border-color: #ffc107;
}
.stat-box.warning h3 {
    color: #ffc107;
}
.stat-box.danger {
    border-color: #dc3545;
}
.stat-box.danger h3 {
    color: #dc3545;
}
.setup-status-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}
.setup-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}
.setup-item:last-child {
    border-bottom: none;
}
.setup-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}
.setup-icon.completed {
    background: #28a745;
    color: white;
}
.setup-icon.pending {
    background: #ffc107;
    color: white;
}
.setup-icon.not-started {
    background: #e9ecef;
    color: #6c757d;
}
</style>

<!-- Main content -->
<h1 class="page-header"><?php echo $title ?? '지점 상세 정보' ?></h1>

<?php if(isset($error)): ?>
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i> <?php echo $error ?>
</div>
<?php else: ?>

<!-- 지점 기본 정보 -->
<div class="panel panel-inverse branch-detail-card">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-store"></i> 지점 기본 정보</h4>
    </div>
    <div class="panel-body">
        <?php if($branch_info): ?>
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">지점 정보</h5>
                <table class="table table-sm info-table">
                    <tr>
                        <td width="30%" class="text-muted">지점명</td>
                        <td><strong><?php echo $branch_info['BCOFF_NM'] ?? '-' ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">지점 코드</td>
                        <td><?php echo $branch_info['BCOFF_CD'] ?? '-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">회사명</td>
                        <td><?php echo $branch_info['COMP_NM'] ?? '-' ?> (<?php echo $branch_info['COMP_CD'] ?? '-' ?>)</td>
                    </tr>
                    <tr>
                        <td class="text-muted">지점 주소</td>
                        <td><?php echo $branch_info['BCOFF_ADDR'] ?? '-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">지점 전화번호</td>
                        <td><?php echo disp_phone($branch_info['BCOFF_TELNO'] ?? '') ?></td>
                    </tr>
                    <?php if(!empty($branch_info['BCOFF_TELNO2'])): ?>
                    <tr>
                        <td class="text-muted">지점 전화번호2</td>
                        <td><?php echo disp_phone($branch_info['BCOFF_TELNO2']) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3">관리자 정보</h5>
                <table class="table table-sm info-table">
                    <tr>
                        <td width="30%" class="text-muted">관리자 ID</td>
                        <td><?php echo $branch_info['BCOFF_MGMT_ID'] ?? '-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">관리자명</td>
                        <td><?php echo $branch_info['MNGR_NM'] ?? '-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">관리자 연락처</td>
                        <td><?php echo disp_phone($branch_info['MNGR_TELNO'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">대표자명</td>
                        <td><?php echo $branch_info['CEO_NM'] ?? '-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">대표자 연락처</td>
                        <td><?php echo disp_phone($branch_info['CEO_TELNO'] ?? '') ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h5 class="mb-3">신청/승인 정보</h5>
                <table class="table table-sm info-table">
                    <tr>
                        <td width="15%" class="text-muted">신청일시</td>
                        <td width="35%"><?php echo $branch_info['APPCT_DATE_FORMAT'] ?? '-' ?></td>
                        <td width="15%" class="text-muted">승인일시</td>
                        <td width="35%"><?php echo $branch_info['APPV_DATE_FORMAT'] ?? '-' ?></td>
                    </tr>
                    <?php if(!empty($branch_info['BCOFF_MEMO'])): ?>
                    <tr>
                        <td class="text-muted">메모</td>
                        <td colspan="3"><?php echo $branch_info['BCOFF_MEMO'] ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> 지점 정보를 찾을 수 없습니다.
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- 결제 통계 -->
<div class="panel panel-inverse branch-detail-card">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-chart-bar"></i> 최근 30일 결제 통계</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-box primary">
                    <h3><?php echo number_format($payment_stats['total_count'] ?? 0) ?></h3>
                    <p>전체 결제 건수</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box success">
                    <h3><?php echo number_format($payment_stats['success_count'] ?? 0) ?></h3>
                    <p>성공 건수</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box warning">
                    <h3><?php echo number_format($payment_stats['cancel_count'] ?? 0) ?></h3>
                    <p>취소 건수</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box danger">
                    <h3>₩<?php echo number_format($payment_stats['total_amount'] ?? 0) ?></h3>
                    <p>총 결제 금액</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 설정 상태 -->
<div class="panel panel-inverse branch-detail-card">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-cogs"></i> 지점 설정 상태</h4>
    </div>
    <div class="panel-body">
        <div class="setup-status-card">
            <div class="setup-item">
                <div class="setup-icon <?php echo ($setup_status['basic_info_yn'] ?? 'N') == 'Y' ? 'completed' : 'not-started' ?>">
                    <i class="fas <?php echo ($setup_status['basic_info_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-times' ?>"></i>
                </div>
                <div>
                    <h5 class="mb-1">기본 정보 설정</h5>
                    <small class="text-muted">지점 기본 정보 및 관리자 정보</small>
                </div>
            </div>
            
            <div class="setup-item">
                <div class="setup-icon <?php echo ($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'completed' : 'pending' ?>">
                    <i class="fas <?php echo ($setup_status['pg_setup_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-clock' ?>"></i>
                </div>
                <div>
                    <h5 class="mb-1">PG 설정</h5>
                    <small class="text-muted">온라인 결제 게이트웨이 설정</small>
                    <?php if(!empty($pg_settings)): ?>
                    <div class="mt-2">
                        <?php foreach($pg_settings as $pg): ?>
                        <span class="badge badge-primary mr-1"><?php echo $pg['provider_name'] ?? '' ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="setup-item">
                <div class="setup-icon <?php echo ($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'completed' : 'not-started' ?>">
                    <i class="fas <?php echo ($setup_status['van_setup_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-times' ?>"></i>
                </div>
                <div>
                    <h5 class="mb-1">VAN 설정</h5>
                    <small class="text-muted">오프라인 카드 단말기 설정</small>
                    <?php if(!empty($van_settings)): ?>
                    <div class="mt-2">
                        <?php foreach($van_settings as $van): ?>
                        <span class="badge badge-info mr-1"><?php echo $van['provider_name'] ?? '' ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="setup-item">
                <div class="setup-icon <?php echo ($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'completed' : 'not-started' ?>">
                    <i class="fas <?php echo ($setup_status['bank_setup_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-times' ?>"></i>
                </div>
                <div>
                    <h5 class="mb-1">계좌 설정</h5>
                    <small class="text-muted">입금 계좌 및 환불 계좌 설정</small>
                    <?php if(!empty($bank_settings)): ?>
                    <div class="mt-2">
                        <?php foreach($bank_settings as $bank): ?>
                        <span class="badge badge-success mr-1"><?php echo $bank['bank_name'] ?? '' ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="setup-item">
                <div class="setup-icon <?php echo ($setup_status['ready_yn'] ?? 'N') == 'Y' ? 'completed' : 'not-started' ?>">
                    <i class="fas <?php echo ($setup_status['ready_yn'] ?? 'N') == 'Y' ? 'fa-check' : 'fa-times' ?>"></i>
                </div>
                <div>
                    <h5 class="mb-1">운영 준비 완료</h5>
                    <small class="text-muted">모든 설정 완료 및 운영 가능 상태</small>
                </div>
            </div>
            
            <!-- 진행률 표시 -->
            <div class="mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>전체 설정 진행률</span>
                    <span class="font-weight-bold"><?php echo $setup_status['setup_progress'] ?? 0 ?>%</span>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: <?php echo $setup_status['setup_progress'] ?? 0 ?>%"
                         aria-valuenow="<?php echo $setup_status['setup_progress'] ?? 0 ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <?php echo $setup_status['setup_progress'] ?? 0 ?>%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 액션 버튼 -->
<div class="text-center mb-4">
    <button type="button" class="btn btn-secondary" onclick="window.history.back();">
        <i class="fas fa-arrow-left"></i> 목록으로
    </button>
    <?php if(($setup_status['ready_yn'] ?? 'N') != 'Y'): ?>
    <button type="button" class="btn btn-primary" onclick="continueBranchSetup();">
        <i class="fas fa-cogs"></i> 설정 계속하기
    </button>
    <?php endif; ?>
    <button type="button" class="btn btn-success" onclick="viewPaymentList();">
        <i class="fas fa-list"></i> 결제 내역 보기
    </button>
</div>

<?php endif; ?>

<script>
function continueBranchSetup() {
    const bcoffCd = '<?php echo $branch_info['BCOFF_CD'] ?? '' ?>';
    if (bcoffCd) {
        window.location.href = '/smgrmain/payment_settings_overview?bcoff_cd=' + bcoffCd;
    }
}

function viewPaymentList() {
    const bcoffCd = '<?php echo $branch_info['BCOFF_CD'] ?? '' ?>';
    if (bcoffCd) {
        window.location.href = '/smgrmain/payment_status_inquiry?bcoff_cd=' + bcoffCd;
    }
}
</script>