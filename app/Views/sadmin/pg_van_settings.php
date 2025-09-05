<style>
.settings-container {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}
.nav-tabs {
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 20px;
}
.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    background: none;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 24px;
}
.nav-tabs .nav-link.active {
    color: #007bff;
    border-bottom-color: #007bff;
    background: none;
}
.nav-tabs .nav-link:hover {
    border-bottom-color: #007bff;
    color: #007bff;
}
.provider-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}
.provider-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.provider-card.active {
    border-color: #007bff;
    background: #f8f9ff;
}
.provider-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}
.provider-logo {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #007bff;
    margin-right: 15px;
}
.provider-info h5 {
    margin: 0;
    font-weight: 600;
}
.provider-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9em;
}
.status-toggle {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
.status-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #007bff;
}
input:checked + .slider:before {
    transform: translateX(26px);
}
.config-form {
    display: none;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}
.config-form.active {
    display: block;
}
.form-group {
    margin-bottom: 15px;
}
.form-label {
    font-weight: 500;
    margin-bottom: 5px;
    color: #495057;
}
.input-group-text {
    background: #f8f9fa;
    border-color: #ced4da;
    color: #495057;
    font-size: 0.9em;
}
.test-button {
    margin-left: 10px;
}
.connection-status {
    display: inline-flex;
    align-items: center;
    font-size: 0.9em;
    margin-right: 20px;
}
.status-icon {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
    animation: pulse 2s infinite;
}
.status-connected { background: #28a745; }
.status-disconnected { background: #dc3545; }
.status-testing { background: #ffc107; }
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
.provider-stats {
    display: flex;
    gap: 20px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}
.stat-item {
    text-align: center;
    flex: 1;
}
.stat-number {
    font-size: 1.2em;
    font-weight: 600;
    color: #007bff;
}
.stat-label {
    font-size: 0.8em;
    color: #6c757d;
    margin-top: 2px;
}
</style>

<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<!-- 지점 선택 -->
<div class="panel panel-inverse mb-4">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-store"></i> 지점 선택</h4>
    </div>
    <div class="panel-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label for="branch_select">설정할 지점을 선택하세요</label>
                    <select id="branch_select" class="form-control" onchange="changeBranch()">
                        <option value="">-- 지점을 선택하세요 --</option>
                        <?php if(isset($view['branch_list']) && is_array($view['branch_list'])): ?>
                            <?php foreach($view['branch_list'] as $branch): ?>
                                <option value="<?= $branch['BCOFF_CD'] ?>" <?= (isset($view['selected_branch']) && $view['selected_branch'] == $branch['BCOFF_CD']) ? 'selected' : '' ?>>
                                    <?= $branch['BCOFF_NM'] ?> (<?= $branch['BCOFF_CD'] ?>) - <?= $branch['MNGR_NM'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <?php if(!empty($view['selected_bcoff_cd'])): ?>
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle"></i> 
                        현재 선택된 지점: <strong><?= $view['selected_bcoff_cd'] ?></strong>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle"></i> 
                        먼저 지점을 선택해주세요.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="settings-container" <?= empty($view['selected_bcoff_cd']) ? 'style="opacity: 0.5; pointer-events: none;"' : '' ?>>
    <div class="row">
        <div class="col-md-8">
            <h4><i class="fas fa-cogs"></i> PG/VAN 설정 관리</h4>
            <p class="text-muted">결제 게이트웨이와 VAN 서비스를 설정하고 관리하세요.</p>
        </div>
        <div class="col-md-4 text-right">
            <button type="button" class="btn btn-success" onclick="saveAllSettings()" <?= empty($view['selected_bcoff_cd']) ? 'disabled' : '' ?>>
                <i class="fas fa-save"></i> 전체 저장
            </button>
            <button type="button" class="btn btn-info" onclick="testAllConnections()" <?= empty($view['selected_bcoff_cd']) ? 'disabled' : '' ?>>
                <i class="fas fa-plug"></i> 연결 테스트
            </button>
        </div>
    </div>
</div>

<!-- 탭 네비게이션 -->
<ul class="nav nav-tabs" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pg-tab" data-bs-toggle="tab" data-bs-target="#pg-content" type="button" role="tab">
            <i class="fas fa-credit-card"></i> PG 설정
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="van-tab" data-bs-toggle="tab" data-bs-target="#van-content" type="button" role="tab">
            <i class="fas fa-network-wired"></i> VAN 설정
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pos-tab" data-bs-toggle="tab" data-bs-target="#pos-content" type="button" role="tab">
            <i class="fas fa-cash-register"></i> POS 설정
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-content" type="button" role="tab">
            <i class="fas fa-wrench"></i> 고급 설정
        </button>
    </li>
</ul>

<!-- 탭 컨텐츠 -->
<div class="tab-content" id="settingsTabContent">
    <!-- PG 설정 탭 -->
    <div class="tab-pane fade show active" id="pg-content" role="tabpanel">
        
        <!-- 이니시스 -->
        <div class="provider-card" data-provider="inicis">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="provider-info">
                        <h5>이니시스 (INICIS)</h5>
                        <p>국내 대표 PG사 - 카드결제, 계좌이체, 가상계좌</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['pg_settings']['inicis']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['pg_settings']['inicis']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="inicis_enabled" <?= ($view['pg_settings']['inicis']['enabled'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['pg_settings']['inicis']['enabled'] ?? false) ? 'active' : '' ?>" id="inicis_config">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">모바일 결제 MID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="inicis_mobile_mid" 
                                       value="<?= $view['pg_settings']['inicis']['mobile']['mid'] ?? '' ?>" 
                                       placeholder="상점 ID를 입력하세요">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-mobile-alt"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">모바일 결제 SignKey</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="inicis_mobile_signkey" 
                                       value="<?= $view['pg_settings']['inicis']['mobile']['signkey'] ?? '' ?>" 
                                       placeholder="서명키를 입력하세요">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">카드 결제 MID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="inicis_card_mid" 
                                       value="<?= $view['pg_settings']['inicis']['card']['mid'] ?? '' ?>" 
                                       placeholder="카드 전용 상점 ID">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-credit-card"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">카드 결제 SignKey</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="inicis_card_signkey" 
                                       value="<?= $view['pg_settings']['inicis']['card']['signkey'] ?? '' ?>" 
                                       placeholder="카드 서명키">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">운영 모드</label>
                            <select class="form-control" name="inicis_test_mode">
                                <option value="Y" <?= ($view['pg_settings']['inicis']['test_mode'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>
                                    테스트 모드 (개발용)
                                </option>
                                <option value="N" <?= ($view['pg_settings']['inicis']['test_mode'] ?? 'Y') == 'N' ? 'selected' : '' ?>>
                                    운영 모드 (실제 결제)
                                </option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 테스트 모드에서는 실제 결제가 이루어지지 않습니다.
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testConnection('inicis')">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveProviderSettings('inicis')">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewDocumentation('inicis')">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                    </div>
                </div>
                
                <!-- 통계 정보 -->
                <?php if(isset($pg_stats['inicis'])): ?>
                <div class="provider-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($pg_stats['inicis']['total_amount'] ?? 0) ?></div>
                        <div class="stat-label">총 결제금액 (원)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($pg_stats['inicis']['total_count'] ?? 0) ?></div>
                        <div class="stat-label">결제건수 (건)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($pg_stats['inicis']['success_rate'] ?? 0, 1) ?>%</div>
                        <div class="stat-label">성공률</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= date('m-d H:i', strtotime($pg_stats['inicis']['last_transaction'] ?? 'now')) ?></div>
                        <div class="stat-label">최종 거래</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- KCP -->
        <div class="provider-card" data-provider="kcp">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo" style="background: #ff6b35; color: white;">
                        KCP
                    </div>
                    <div class="provider-info">
                        <h5>NHN KCP</h5>
                        <p>안정적인 결제 서비스 - 다양한 결제수단 지원</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['pg_settings']['kcp']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['pg_settings']['kcp']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="kcp_enabled" <?= ($view['pg_settings']['kcp']['enabled'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['pg_settings']['kcp']['enabled'] ?? false) ? 'active' : '' ?>" id="kcp_config">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Site CD</label>
                            <input type="text" class="form-control" name="kcp_site_cd" 
                                   value="<?= $view['pg_settings']['kcp']['site_cd'] ?? '' ?>" 
                                   placeholder="사이트 코드">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Site Key</label>
                            <input type="password" class="form-control" name="kcp_site_key" 
                                   value="<?= $view['pg_settings']['kcp']['site_key'] ?? '' ?>" 
                                   placeholder="사이트 키">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">운영 모드</label>
                    <select class="form-control" name="kcp_test_mode">
                        <option value="Y" <?= ($view['pg_settings']['kcp']['test_mode'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>
                            테스트 모드 (개발용)
                        </option>
                        <option value="N" <?= ($view['pg_settings']['kcp']['test_mode'] ?? 'Y') == 'N' ? 'selected' : '' ?>>
                            운영 모드 (실제 결제)
                        </option>
                    </select>
                    <small class="form-text text-muted">테스트 모드에서는 실제 결제가 이루어지지 않습니다.</small>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testConnection('kcp')">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveProviderSettings('kcp')">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewDocumentation('kcp')">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 토스페이먼츠 -->
        <div class="provider-card" data-provider="toss">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo" style="background: #3182f6; color: white;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="provider-info">
                        <h5>토스페이먼츠</h5>
                        <p>혁신적인 핀테크 결제 - 간편결제 전문</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['pg_settings']['toss']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['pg_settings']['toss']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="toss_enabled" <?= ($view['pg_settings']['toss']['enabled'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['pg_settings']['toss']['enabled'] ?? false) ? 'active' : '' ?>" id="toss_config">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Client Key</label>
                            <input type="text" class="form-control" name="toss_client_key" 
                                   value="<?= $view['pg_settings']['toss']['client_key'] ?? '' ?>" 
                                   placeholder="클라이언트 키">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Secret Key</label>
                            <input type="password" class="form-control" name="toss_secret_key" 
                                   value="<?= $view['pg_settings']['toss']['secret_key'] ?? '' ?>" 
                                   placeholder="시크릿 키">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">운영 모드</label>
                    <select class="form-control" name="toss_test_mode">
                        <option value="Y" <?= ($view['pg_settings']['toss']['test_mode'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>
                            테스트 모드 (개발용)
                        </option>
                        <option value="N" <?= ($view['pg_settings']['toss']['test_mode'] ?? 'Y') == 'N' ? 'selected' : '' ?>>
                            운영 모드 (실제 결제)
                        </option>
                    </select>
                    <small class="form-text text-muted">테스트 모드에서는 실제 결제가 이루어지지 않습니다.</small>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testConnection('toss')">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveProviderSettings('toss')">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewDocumentation('toss')">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 웰컴페이먼츠 -->
        <div class="provider-card" data-provider="welcome">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo" style="background: #ff6b6b; color: white;">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="provider-info">
                        <h5>웰컴페이먼츠</h5>
                        <p>Welcome Payments - 통합 결제 솔루션</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['pg_settings']['welcome']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['pg_settings']['welcome']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="welcome_enabled" <?= ($view['pg_settings']['welcome']['enabled'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['pg_settings']['welcome']['enabled'] ?? false) ? 'active' : '' ?>" id="welcome_config">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">상점 ID (MID)</label>
                            <input type="text" class="form-control" name="welcome_mid" 
                                   value="<?= $view['pg_settings']['welcome']['mid'] ?? '' ?>" 
                                   placeholder="웰컴페이먼츠 상점 ID">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">상점 Key</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="welcome_merchant_key" 
                                       value="<?= $view['pg_settings']['welcome']['merchant_key'] ?? '' ?>" 
                                       placeholder="상점 인증키">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">API Key</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="welcome_api_key" 
                                       value="<?= $view['pg_settings']['welcome']['api_key'] ?? '' ?>" 
                                       placeholder="API 키">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">API URL</label>
                            <select class="form-control" name="welcome_api_url">
                                <option value="https://testpgapi.payletter.com" <?= ($view['pg_settings']['welcome']['api_url'] ?? '') == 'https://testpgapi.payletter.com' ? 'selected' : '' ?>>
                                    테스트 서버 (testpgapi.payletter.com)
                                </option>
                                <option value="https://pgapi.payletter.com" <?= ($view['pg_settings']['welcome']['api_url'] ?? '') == 'https://pgapi.payletter.com' ? 'selected' : '' ?>>
                                    운영 서버 (pgapi.payletter.com)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">운영 모드</label>
                    <select class="form-control" name="welcome_test_mode">
                        <option value="Y" <?= ($view['pg_settings']['welcome']['test_mode'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>
                            테스트 모드 (개발용)
                        </option>
                        <option value="N" <?= ($view['pg_settings']['welcome']['test_mode'] ?? 'Y') == 'N' ? 'selected' : '' ?>>
                            운영 모드 (실제 결제)
                        </option>
                    </select>
                    <small class="form-text text-muted">테스트 모드에서는 실제 결제가 이루어지지 않습니다.</small>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testConnection('welcome')">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveProviderSettings('welcome')">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewDocumentation('welcome')">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- VAN 설정 탭 -->
    <div class="tab-pane fade" id="van-content" role="tabpanel">
        
        <!-- KICC -->
        <div class="provider-card" data-provider="kicc">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo" style="background: #1e88e5; color: white;">
                        KICC
                    </div>
                    <div class="provider-info">
                        <h5>KICC</h5>
                        <p>한국정보통신 - 신뢰할 수 있는 VAN 서비스</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['van_settings']['kicc']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['van_settings']['kicc']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="kicc_enabled" <?= ($view['van_settings']['kicc']['enabled'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['van_settings']['kicc']['enabled'] ?? false) ? 'active' : '' ?>" id="kicc_config">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Terminal ID</label>
                            <input type="text" class="form-control" name="kicc_terminal_id" 
                                   value="<?= $view['van_settings']['kicc']['terminal_id'] ?? '' ?>" 
                                   placeholder="단말기 ID">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Merchant No</label>
                            <input type="text" class="form-control" name="kicc_merchant_no" 
                                   value="<?= $view['van_settings']['kicc']['merchant_no'] ?? '' ?>" 
                                   placeholder="가맹점 번호">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">API Key</label>
                            <input type="password" class="form-control" name="kicc_api_key" 
                                   value="<?= $view['van_settings']['kicc']['api_key'] ?? '' ?>" 
                                   placeholder="API 키">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Timeout (초)</label>
                            <input type="number" class="form-control" name="kicc_timeout" 
                                   value="<?= $view['van_settings']['kicc']['timeout'] ?? 30 ?>" 
                                   min="10" max="60">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">운영 모드</label>
                    <select class="form-control" name="kicc_test_mode">
                        <option value="Y" <?= ($view['van_settings']['kicc']['test_mode'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>
                            테스트 모드 (개발용)
                        </option>
                        <option value="N" <?= ($view['van_settings']['kicc']['test_mode'] ?? 'Y') == 'N' ? 'selected' : '' ?>>
                            운영 모드 (실제 결제)
                        </option>
                    </select>
                    <small class="form-text text-muted">테스트 모드에서는 실제 결제가 이루어지지 않습니다.</small>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testVanConnection('kicc')">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveVanSettings('kicc')">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewVanDocumentation('kicc')">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- NICE -->
        <div class="provider-card" data-provider="nice">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo" style="background: #e91e63; color: white;">
                        NICE
                    </div>
                    <div class="provider-info">
                        <h5>NICE</h5>
                        <p>나이스정보통신 - 안정적인 VAN 네트워크</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['van_settings']['nice']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['van_settings']['nice']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="nice_enabled" <?= ($view['van_settings']['nice']['enabled'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['van_settings']['nice']['enabled'] ?? false) ? 'active' : '' ?>" id="nice_config">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Terminal ID</label>
                            <input type="text" class="form-control" name="nice_terminal_id" 
                                   value="<?= $view['van_settings']['nice']['terminal_id'] ?? '' ?>" 
                                   placeholder="단말기 ID">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Merchant No</label>
                            <input type="text" class="form-control" name="nice_merchant_no" 
                                   value="<?= $view['van_settings']['nice']['merchant_no'] ?? '' ?>" 
                                   placeholder="가맹점 번호">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">운영 모드</label>
                    <select class="form-control" name="nice_test_mode">
                        <option value="Y" <?= ($view['van_settings']['nice']['test_mode'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>
                            테스트 모드 (개발용)
                        </option>
                        <option value="N" <?= ($view['van_settings']['nice']['test_mode'] ?? 'Y') == 'N' ? 'selected' : '' ?>>
                            운영 모드 (실제 결제)
                        </option>
                    </select>
                    <small class="form-text text-muted">테스트 모드에서는 실제 결제가 이루어지지 않습니다.</small>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testVanConnection('nice')">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveVanSettings('nice')">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewVanDocumentation('nice')">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KSNET -->
        <div class="provider-card" data-provider="ksnet">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo" style="background: #4caf50; color: white;">
                        KS
                    </div>
                    <div class="provider-info">
                        <h5>KSNET</h5>
                        <p>한국스마트카드 - 카드 전문 VAN</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['van_settings']['ksnet']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['van_settings']['ksnet']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="ksnet_enabled" <?= ($view['van_settings']['ksnet']['enabled'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['van_settings']['ksnet']['enabled'] ?? false) ? 'active' : '' ?>" id="ksnet_config">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Store ID</label>
                            <input type="text" class="form-control" name="ksnet_store_id" 
                                   value="<?= $view['van_settings']['ksnet']['store_id'] ?? '' ?>" 
                                   placeholder="상점 ID">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">API Key</label>
                            <input type="password" class="form-control" name="ksnet_api_key" 
                                   value="<?= $view['van_settings']['ksnet']['api_key'] ?? '' ?>" 
                                   placeholder="API 키">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">운영 모드</label>
                    <select class="form-control" name="ksnet_test_mode">
                        <option value="Y" <?= ($view['van_settings']['ksnet']['test_mode'] ?? 'Y') == 'Y' ? 'selected' : '' ?>>
                            테스트 모드 (개발용)
                        </option>
                        <option value="N" <?= ($view['van_settings']['ksnet']['test_mode'] ?? 'Y') == 'N' ? 'selected' : '' ?>>
                            운영 모드 (실제 결제)
                        </option>
                    </select>
                    <small class="form-text text-muted">테스트 모드에서는 실제 결제가 이루어지지 않습니다.</small>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testVanConnection('ksnet')">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="saveVanSettings('ksnet')">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewVanDocumentation('ksnet')">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 고급 설정 탭 -->
    <div class="tab-pane fade" id="settings-content" role="tabpanel">
        <div class="provider-card">
            <h5><i class="fas fa-cog"></i> 기본 설정</h5>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">기본 PG사</label>
                        <select class="form-control" name="default_pg">
                            <option value="inicis" <?= ($view['default_settings']['pg'] ?? '') == 'inicis' ? 'selected' : '' ?>>이니시스</option>
                            <option value="kcp" <?= ($view['default_settings']['pg'] ?? '') == 'kcp' ? 'selected' : '' ?>>KCP</option>
                            <option value="toss" <?= ($view['default_settings']['pg'] ?? '') == 'toss' ? 'selected' : '' ?>>토스페이먼츠</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">기본 결제 유형</label>
                        <select class="form-control" name="default_pg_type">
                            <option value="mobile" <?= ($view['default_settings']['pg_type'] ?? '') == 'mobile' ? 'selected' : '' ?>>모바일 결제</option>
                            <option value="card" <?= ($view['default_settings']['pg_type'] ?? '') == 'card' ? 'selected' : '' ?>>카드 결제</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">기본 VAN사</label>
                        <select class="form-control" name="default_van">
                            <option value="kicc" <?= ($view['default_settings']['van'] ?? '') == 'kicc' ? 'selected' : '' ?>>KICC</option>
                            <option value="nice" <?= ($view['default_settings']['van'] ?? '') == 'nice' ? 'selected' : '' ?>>NICE</option>
                            <option value="ksnet" <?= ($view['default_settings']['van'] ?? '') == 'ksnet' ? 'selected' : '' ?>>KSNET</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="dev_mode" 
                                   <?= ($view['default_settings']['dev_mode'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="dev_mode">
                                개발 모드 (테스트 환경)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto_settle" 
                                   <?= ($view['default_settings']['auto_settle'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="auto_settle">
                                자동 정산 처리
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" onclick="saveDefaultSettings()">
                        <i class="fas fa-save"></i> 기본 설정 저장
                    </button>
                </div>
            </div>
        </div>
        
        <div class="provider-card">
            <h5><i class="fas fa-shield-alt"></i> 보안 설정</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">암호화 키 갱신 주기 (일)</label>
                        <input type="number" class="form-control" name="key_rotation_period" 
                               value="<?= $view['security_settings']['key_rotation_period'] ?? 30 ?>" 
                               min="7" max="365">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">로그 보관 기간 (일)</label>
                        <input type="number" class="form-control" name="log_retention_period" 
                               value="<?= $view['security_settings']['log_retention_period'] ?? 90 ?>" 
                               min="30" max="730">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-warning" onclick="rotateKeys()">
                        <i class="fas fa-key"></i> 암호화 키 즉시 갱신
                    </button>
                    <button type="button" class="btn btn-info" onclick="downloadLogs()">
                        <i class="fas fa-download"></i> 로그 다운로드
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- POS 설정 탭 -->
    <div class="tab-pane fade" id="pos-content" role="tabpanel">
        <div class="provider-card">
            <div class="provider-header">
                <div class="d-flex align-items-center">
                    <div class="provider-logo" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="provider-info">
                        <h5>POS 시스템 설정</h5>
                        <p>Point of Sale - 통합 결제 단말기 설정</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="connection-status">
                        <div class="status-icon status-<?= ($view['pos_settings']['status'] ?? 'disconnected') ?>"></div>
                        <span><?= ($view['pos_settings']['status'] ?? 'disconnected') == 'connected' ? '연결됨' : '연결안됨' ?></span>
                    </div>
                    <label class="status-toggle">
                        <input type="checkbox" id="pos_enabled" <?= ($view['pos_settings']['use_pos'] ?? false) ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="config-form <?= ($view['pos_settings']['use_pos'] ?? false) ? 'active' : '' ?>" id="pos_config">
                <!-- POS 기본 설정 -->
                <h6 class="mb-3"><i class="fas fa-cog"></i> 기본 설정</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">POS 유형</label>
                            <select class="form-control" name="pos_type" id="pos_type">
                                <option value="integrated" <?= ($view['pos_settings']['pos_type'] ?? '') == 'integrated' ? 'selected' : '' ?>>
                                    통합형 POS (PC 연동)
                                </option>
                                <option value="standalone" <?= ($view['pos_settings']['pos_type'] ?? '') == 'standalone' ? 'selected' : '' ?>>
                                    독립형 POS (별도 단말기)
                                </option>
                                <option value="mobile" <?= ($view['pos_settings']['pos_type'] ?? '') == 'mobile' ? 'selected' : '' ?>>
                                    모바일 POS (태블릿/스마트폰)
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">결제 우선순위</label>
                            <select class="form-control" name="pos_priority">
                                <option value="1" <?= ($view['pos_settings']['pos_priority'] ?? '1') == '1' ? 'selected' : '' ?>>
                                    1순위 (최우선 사용)
                                </option>
                                <option value="2" <?= ($view['pos_settings']['pos_priority'] ?? '1') == '2' ? 'selected' : '' ?>>
                                    2순위 (PG 다음)
                                </option>
                                <option value="3" <?= ($view['pos_settings']['pos_priority'] ?? '1') == '3' ? 'selected' : '' ?>>
                                    3순위 (수동 입력 우선)
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">연동 방식</label>
                            <select class="form-control" name="integration_mode">
                                <option value="api" <?= ($view['pos_settings']['integration_mode'] ?? '') == 'api' ? 'selected' : '' ?>>
                                    API 연동
                                </option>
                                <option value="serial" <?= ($view['pos_settings']['integration_mode'] ?? '') == 'serial' ? 'selected' : '' ?>>
                                    시리얼 통신 (RS-232)
                                </option>
                                <option value="network" <?= ($view['pos_settings']['integration_mode'] ?? '') == 'network' ? 'selected' : '' ?>>
                                    네트워크 통신 (TCP/IP)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- POS 장비 정보 -->
                <h6 class="mb-3 mt-4"><i class="fas fa-desktop"></i> 장비 정보</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">제조사</label>
                            <select class="form-control" name="pos_manufacturer" id="pos_manufacturer">
                                <option value="">선택하세요</option>
                                <option value="KICC" <?= ($view['pos_settings']['pos_device']['manufacturer'] ?? '') == 'KICC' ? 'selected' : '' ?>>
                                    KICC (한국정보통신)
                                </option>
                                <option value="NICE" <?= ($view['pos_settings']['pos_device']['manufacturer'] ?? '') == 'NICE' ? 'selected' : '' ?>>
                                    나이스정보통신
                                </option>
                                <option value="KSNET" <?= ($view['pos_settings']['pos_device']['manufacturer'] ?? '') == 'KSNET' ? 'selected' : '' ?>>
                                    KSNET
                                </option>
                                <option value="KIS" <?= ($view['pos_settings']['pos_device']['manufacturer'] ?? '') == 'KIS' ? 'selected' : '' ?>>
                                    KIS정보통신
                                </option>
                                <option value="FDIK" <?= ($view['pos_settings']['pos_device']['manufacturer'] ?? '') == 'FDIK' ? 'selected' : '' ?>>
                                    FDIK (금융결제원)
                                </option>
                                <option value="OTHER" <?= ($view['pos_settings']['pos_device']['manufacturer'] ?? '') == 'OTHER' ? 'selected' : '' ?>>
                                    기타
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">모델명</label>
                            <input type="text" class="form-control" name="pos_model" 
                                   value="<?= $view['pos_settings']['pos_device']['model'] ?? '' ?>" 
                                   placeholder="예: KIS-7000, IC-5100">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">시리얼 번호</label>
                            <input type="text" class="form-control" name="pos_serial_number" 
                                   value="<?= $view['pos_settings']['pos_device']['serial_number'] ?? '' ?>" 
                                   placeholder="장비 시리얼 번호">
                        </div>
                    </div>
                </div>
                
                <!-- 연결 설정 -->
                <h6 class="mb-3 mt-4"><i class="fas fa-plug"></i> 연결 설정</h6>
                <div class="row" id="connection_settings">
                    <!-- API 연동 설정 -->
                    <div class="col-md-12 connection-type" data-type="api" style="<?= ($view['pos_settings']['integration_mode'] ?? 'api') != 'api' ? 'display:none;' : '' ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">API 엔드포인트</label>
                                    <input type="text" class="form-control" name="api_endpoint" 
                                           value="<?= $view['pos_settings']['connection']['api_endpoint'] ?? '' ?>" 
                                           placeholder="https://pos.example.com/api">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">API 키</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="api_key" 
                                               value="<?= $view['pos_settings']['connection']['api_key'] ?? '' ?>" 
                                               placeholder="API 인증 키">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 시리얼 통신 설정 -->
                    <div class="col-md-12 connection-type" data-type="serial" style="<?= ($view['pos_settings']['integration_mode'] ?? 'api') != 'serial' ? 'display:none;' : '' ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">COM 포트</label>
                                    <select class="form-control" name="com_port">
                                        <option value="COM1" <?= ($view['pos_settings']['connection']['com_port'] ?? '') == 'COM1' ? 'selected' : '' ?>>COM1</option>
                                        <option value="COM2" <?= ($view['pos_settings']['connection']['com_port'] ?? '') == 'COM2' ? 'selected' : '' ?>>COM2</option>
                                        <option value="COM3" <?= ($view['pos_settings']['connection']['com_port'] ?? '') == 'COM3' ? 'selected' : '' ?>>COM3</option>
                                        <option value="COM4" <?= ($view['pos_settings']['connection']['com_port'] ?? '') == 'COM4' ? 'selected' : '' ?>>COM4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">통신 속도</label>
                                    <select class="form-control" name="baud_rate">
                                        <option value="9600" <?= ($view['pos_settings']['connection']['baud_rate'] ?? '9600') == '9600' ? 'selected' : '' ?>>9600</option>
                                        <option value="19200" <?= ($view['pos_settings']['connection']['baud_rate'] ?? '9600') == '19200' ? 'selected' : '' ?>>19200</option>
                                        <option value="38400" <?= ($view['pos_settings']['connection']['baud_rate'] ?? '9600') == '38400' ? 'selected' : '' ?>>38400</option>
                                        <option value="115200" <?= ($view['pos_settings']['connection']['baud_rate'] ?? '9600') == '115200' ? 'selected' : '' ?>>115200</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">데이터 비트</label>
                                    <select class="form-control" name="data_bits">
                                        <option value="7" <?= ($view['pos_settings']['connection']['data_bits'] ?? '8') == '7' ? 'selected' : '' ?>>7</option>
                                        <option value="8" <?= ($view['pos_settings']['connection']['data_bits'] ?? '8') == '8' ? 'selected' : '' ?>>8</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">패리티</label>
                                    <select class="form-control" name="parity">
                                        <option value="N" <?= ($view['pos_settings']['connection']['parity'] ?? 'N') == 'N' ? 'selected' : '' ?>>None</option>
                                        <option value="E" <?= ($view['pos_settings']['connection']['parity'] ?? 'N') == 'E' ? 'selected' : '' ?>>Even</option>
                                        <option value="O" <?= ($view['pos_settings']['connection']['parity'] ?? 'N') == 'O' ? 'selected' : '' ?>>Odd</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 네트워크 통신 설정 -->
                    <div class="col-md-12 connection-type" data-type="network" style="<?= ($view['pos_settings']['integration_mode'] ?? 'api') != 'network' ? 'display:none;' : '' ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">IP 주소</label>
                                    <input type="text" class="form-control" name="pos_ip_address" 
                                           value="<?= $view['pos_settings']['connection']['ip_address'] ?? '' ?>" 
                                           placeholder="192.168.1.100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">포트 번호</label>
                                    <input type="number" class="form-control" name="pos_port" 
                                           value="<?= $view['pos_settings']['connection']['port'] ?? '9999' ?>" 
                                           placeholder="9999">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 대체 결제 수단 설정 -->
                <h6 class="mb-3 mt-4"><i class="fas fa-exchange-alt"></i> 대체 결제 수단 (POS 오류 시)</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fallback_manual_card" name="fallback_manual_card"
                                   <?= ($view['pos_settings']['fallback_options']['manual_card'] ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="fallback_manual_card">
                                수동 카드 결제
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fallback_cash" name="fallback_cash"
                                   <?= ($view['pos_settings']['fallback_options']['cash'] ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="fallback_cash">
                                현금 결제
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fallback_bank_transfer" name="fallback_bank_transfer"
                                   <?= ($view['pos_settings']['fallback_options']['bank_transfer'] ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="fallback_bank_transfer">
                                계좌이체
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fallback_pg" name="fallback_pg"
                                   <?= ($view['pos_settings']['fallback_options']['pg'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="fallback_pg">
                                온라인 PG 결제
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- 추가 옵션 -->
                <h6 class="mb-3 mt-4"><i class="fas fa-sliders-h"></i> 추가 옵션</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto_receipt" name="auto_receipt"
                                   <?= ($view['pos_settings']['auto_receipt'] ?? true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="auto_receipt">
                                자동 영수증 발행
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="signature_pad" name="signature_pad"
                                   <?= ($view['pos_settings']['signature_pad'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="signature_pad">
                                서명 패드 사용
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- 버튼 그룹 -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="testPosConnection()">
                            <i class="fas fa-plug"></i> 연결 테스트
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="savePosSettings()">
                            <i class="fas fa-save"></i> 저장
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="viewPosDocumentation()">
                            <i class="fas fa-book"></i> 연동 가이드
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" onclick="simulatePosPayment()">
                            <i class="fas fa-vial"></i> 결제 시뮬레이션
                        </button>
                    </div>
                </div>
                
                <!-- POS 사용 통계 -->
                <?php if(isset($view['pos_stats'])): ?>
                <div class="provider-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($view['pos_stats']['total_amount'] ?? 0) ?></div>
                        <div class="stat-label">총 결제금액 (원)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= number_format($view['pos_stats']['total_count'] ?? 0) ?></div>
                        <div class="stat-label">결제건수 (건)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $view['pos_stats']['success_rate'] ?? 0 ?>%</div>
                        <div class="stat-label">성공률</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $view['pos_stats']['avg_time'] ?? 0 ?>초</div>
                        <div class="stat-label">평균 처리시간</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<br/>

</section>

<?=$jsinc ?>

<script>
$(function () {
    // 페이지 로드 시 설정 불러오기
    loadPgVanSettings();
    
    // 토글 스위치 이벤트
    $('.status-toggle input').change(function() {
        const provider = $(this).closest('.provider-card').data('provider');
        const isEnabled = $(this).is(':checked');
        
        if (isEnabled) {
            $(this).closest('.provider-card').find('.config-form').addClass('active');
        } else {
            $(this).closest('.provider-card').find('.config-form').removeClass('active');
        }
        
        // 자동 저장 (선택사항)
        updateProviderStatus(provider, isEnabled);
    });
    
    // URL 해시로 탭 전환
    if (window.location.hash) {
        const hash = window.location.hash.replace('#', '');
        if (hash === 'van-tab') {
            $('#van-tab').tab('show');
        }
    }
});

function togglePassword(button) {
    const input = $(button).closest('.input-group').find('input');
    const icon = $(button).find('i');
    
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
}

function testConnection(provider) {
    const card = $(`.provider-card[data-provider="${provider}"]`);
    const statusIcon = card.find('.status-icon');
    const statusText = card.find('.connection-status span');
    
    // 테스트 중 상태로 변경
    statusIcon.removeClass('status-connected status-disconnected').addClass('status-testing');
    statusText.text('테스트 중...');
    
    // 설정 데이터 수집
    const formData = collectProviderData(provider);
    
    $.ajax({
        url: '/smgrmain/ajax_test_pg_connection',
        type: 'POST',
        data: {
            provider: provider,
            settings: formData,
            bcoff_cd: getSelectedBranchCode()
        },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success' && response.data) {
                // 상세한 연결 테스트 결과 확인
                if (response.data.status === 'success') {
                    statusIcon.removeClass('status-testing status-disconnected').addClass('status-connected');
                    statusText.text('연결됨');
                    
                    let message = `${provider.toUpperCase()} 연결 테스트 성공`;
                    if (response.data.test_mode) {
                        message += ' (테스트 모드)';
                    }
                    if (response.data.response_time) {
                        message += ` - 응답시간: ${response.data.response_time}`;
                    }
                    alertToast('success', message);
                } else {
                    // 연결 테스트 실패 (설정값 누락 등)
                    statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
                    statusText.text('연결안됨');
                    alertToast('error', response.data.message || `${provider.toUpperCase()} 연결 실패`);
                }
            } else {
                statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
                statusText.text('연결안됨');
                alertToast('error', response.message || `${provider.toUpperCase()} 연결 실패`);
            }
        },
        error: function() {
            statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
            statusText.text('연결안됨');
            alertToast('error', '연결 테스트 중 오류가 발생했습니다.');
        }
    });
}

function testVanConnection(provider) {
    const card = $(`.provider-card[data-provider="${provider}"]`);
    const statusIcon = card.find('.status-icon');
    const statusText = card.find('.connection-status span');
    
    statusIcon.removeClass('status-connected status-disconnected').addClass('status-testing');
    statusText.text('테스트 중...');
    
    const formData = collectVanData(provider);
    
    $.ajax({
        url: '/smgrmain/ajax_test_van_connection',
        type: 'POST',
        data: {
            provider: provider,
            settings: formData,
            bcoff_cd: getSelectedBranchCode()
        },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success' && response.data) {
                // 상세한 연결 테스트 결과 확인
                if (response.data.status === 'success') {
                    statusIcon.removeClass('status-testing status-disconnected').addClass('status-connected');
                    statusText.text('연결됨');
                    
                    let message = `${provider.toUpperCase()} VAN 연결 테스트 성공`;
                    if (response.data.test_mode) {
                        message += ' (테스트 모드)';
                    }
                    if (response.data.response_time) {
                        message += ` - 응답시간: ${response.data.response_time}`;
                    }
                    alertToast('success', message);
                } else {
                    // 연결 테스트 실패 (설정값 누락 등)
                    statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
                    statusText.text('연결안됨');
                    alertToast('error', response.data.message || `${provider.toUpperCase()} VAN 연결 실패`);
                }
            } else {
                statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
                statusText.text('연결안됨');
                alertToast('error', response.message || `${provider.toUpperCase()} VAN 연결 실패`);
            }
        },
        error: function() {
            statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
            statusText.text('연결안됨');
            alertToast('error', 'VAN 연결 테스트 중 오류가 발생했습니다.');
        }
    });
}

function saveProviderSettings(provider) {
    const formData = collectProviderData(provider);
    const enabled = $(`#${provider}_enabled`).is(':checked');
    
    // PG 설정 구조 생성
    const pgSettings = {};
    pgSettings[provider] = {
        enabled: enabled,
        ...formData
    };
    
    ToastConfirm.fire({
        icon: "question",
        title: "설정 저장",
        html: `<font color='#000000'>${provider.toUpperCase()} 설정을 저장하시겠습니까?</font>`,
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/smgrmain/ajax_save_pg_settings',
                type: 'POST',
                data: {
                    comp_cd: '<?= $_SESSION['comp_cd'] ?? '' ?>',
                    bcoff_cd: getSelectedBranchCode(),
                    pg_settings: pgSettings
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        alertToast('success', `${provider.toUpperCase()} 설정이 저장되었습니다.`);
                    } else {
                        alertToast('error', response.message || '설정 저장에 실패했습니다.');
                    }
                },
                error: function() {
                    alertToast('error', '서버 오류가 발생했습니다.');
                }
            });
        }
    });
}

function saveVanSettings(provider) {
    const formData = collectVanData(provider);
    const enabled = $(`#${provider}_enabled`).is(':checked');
    
    // VAN 설정 구조 생성
    const vanSettings = {};
    vanSettings[provider] = {
        enabled: enabled,
        ...formData
    };
    
    ToastConfirm.fire({
        icon: "question", 
        title: "VAN 설정 저장",
        html: `<font color='#000000'>${provider.toUpperCase()} VAN 설정을 저장하시겠습니까?</font>`,
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/smgrmain/ajax_save_van_settings',
                type: 'POST',
                data: {
                    comp_cd: '<?= $_SESSION['comp_cd'] ?? '' ?>',
                    bcoff_cd: getSelectedBranchCode(),
                    van_settings: vanSettings
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        alertToast('success', `${provider.toUpperCase()} VAN 설정이 저장되었습니다.`);
                    } else {
                        alertToast('error', response.message || 'VAN 설정 저장에 실패했습니다.');
                    }
                },
                error: function() {
                    alertToast('error', '서버 오류가 발생했습니다.');
                }
            });
        }
    });
}

function saveAllSettings() {
    ToastConfirm.fire({
        icon: "question",
        title: "전체 설정 저장",
        html: "<font color='#000000'>모든 PG/VAN 설정을 저장하시겠습니까?</font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
    }).then((result) => {
        if (result.isConfirmed) {
            // 모든 활성화된 제공업체의 설정 수집
            const allSettings = {};
            
            $('.provider-card').each(function() {
                const provider = $(this).data('provider');
                const isEnabled = $(this).find('.status-toggle input').is(':checked');
                
                if (isEnabled) {
                    if ($(this).closest('#pg-content').length > 0) {
                        allSettings[provider] = collectProviderData(provider);
                    } else if ($(this).closest('#van-content').length > 0) {
                        allSettings[provider] = collectVanData(provider);
                    }
                }
            });
            
            $.ajax({
                url: '/smgrmain/ajax_save_all_settings',
                type: 'POST',
                data: { settings: allSettings },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        alertToast('success', '모든 설정이 저장되었습니다.');
                    } else {
                        alertToast('error', response.message || '설정 저장에 실패했습니다.');
                    }
                },
                error: function() {
                    alertToast('error', '서버 오류가 발생했습니다.');
                }
            });
        }
    });
}

function testAllConnections() {
    const enabledProviders = [];
    
    $('.provider-card .status-toggle input:checked').each(function() {
        const provider = $(this).closest('.provider-card').data('provider');
        enabledProviders.push(provider);
    });
    
    if (enabledProviders.length === 0) {
        alertToast('warning', '활성화된 제공업체가 없습니다.');
        return;
    }
    
    alertToast('info', '모든 연결을 테스트하고 있습니다...');
    
    let testCount = 0;
    let successCount = 0;
    
    enabledProviders.forEach(provider => {
        const isPG = $(`.provider-card[data-provider="${provider}"]`).closest('#pg-content').length > 0;
        
        if (isPG) {
            testConnection(provider);
        } else {
            testVanConnection(provider);
        }
    });
}

function collectProviderData(provider) {
    const form = $(`#${provider}_config`);
    const data = {};
    
    form.find('input, select').each(function() {
        const name = $(this).attr('name');
        if (name && name.startsWith(provider)) {
            const key = name.replace(provider + '_', '');
            data[key] = $(this).val();
        }
    });
    
    return data;
}

function collectVanData(provider) {
    return collectProviderData(provider);
}

function updateProviderStatus(provider, enabled) {
    $.ajax({
        url: '/smgrmain/ajax_update_provider_status',
        type: 'POST',
        data: {
            provider: provider,
            enabled: enabled
        },
        dataType: 'json'
    });
}

function viewDocumentation(provider) {
    // PG사별 연동 가이드 URL
    const docsUrls = {
        'inicis': 'https://manual.inicis.com/deved/',
        'kcp': 'https://admin8.kcp.co.kr/assist/manual.BizplusManual.do',
        'toss': 'https://docs.tosspayments.com/',
        'welcome': 'https://docs.welcomepayments.co.kr/'
    };
    
    const url = docsUrls[provider];
    if (url) {
        window.open(url, '_blank');
    } else {
        alertToast('info', `${provider.toUpperCase()} 연동 가이드는 준비 중입니다.`);
    }
}

function viewVanDocumentation(provider) {
    // VAN사별 연동 가이드 URL  
    const docsUrls = {
        'kicc': 'https://www.kicc.co.kr/service/business.jsp',
        'nice': 'https://www.nicepay.co.kr/manual/manual_list.jsp',
        'ksnet': 'https://www.ksnet.co.kr/service/payment_service.html'
    };
    
    const url = docsUrls[provider];
    if (url) {
        window.open(url, '_blank');
    } else {
        alertToast('info', `${provider.toUpperCase()} VAN 연동 가이드는 준비 중입니다.`);
    }
}

function saveDefaultSettings() {
    const settings = {
        pg: $('select[name="default_pg"]').val(),
        pg_type: $('select[name="default_pg_type"]').val(),
        van: $('select[name="default_van"]').val(),
        dev_mode: $('#dev_mode').is(':checked'),
        auto_settle: $('#auto_settle').is(':checked')
    };
    
    $.ajax({
        url: '/smgrmain/ajax_save_default_settings',
        type: 'POST',
        data: { settings: settings },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                alertToast('success', '기본 설정이 저장되었습니다.');
            } else {
                alertToast('error', response.message || '기본 설정 저장에 실패했습니다.');
            }
        }
    });
}

function rotateKeys() {
    ToastConfirm.fire({
        icon: "warning",
        title: "암호화 키 갱신",
        html: "<font color='#000000'>암호화 키를 즉시 갱신하시겠습니까?<br><small>진행 중인 결제가 있다면 영향을 받을 수 있습니다.</small></font>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#ffc107",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/smgrmain/ajax_rotate_encryption_keys',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        alertToast('success', '암호화 키가 갱신되었습니다.');
                    } else {
                        alertToast('error', response.message || '키 갱신에 실패했습니다.');
                    }
                }
            });
        }
    });
}

function downloadLogs() {
    window.open('/smgrmain/download_payment_logs', '_blank');
}

// 헬퍼 함수들
function getSelectedBranchCode() {
    // URL에서 bcoff_cd 파라미터 가져오기
    const urlParams = new URLSearchParams(window.location.search);
    const urlBcoffCd = urlParams.get('bcoff_cd');
    
    // URL에 없으면 세션에서 가져오기 (PHP로 전달된 값)
    const sessionBcoffCd = '<?= $_SESSION['bcoff_cd'] ?? '' ?>';
    
    return urlBcoffCd || sessionBcoffCd || '';
}

// 지점 선택 변경 시
function changeBranch() {
    const selectedBranch = $('#branch_select').val();
    if (selectedBranch) {
        // URL 파라미터 추가하여 페이지 리로드
        window.location.href = '/smgrmain/pg_van_settings?bcoff_cd=' + selectedBranch;
    } else {
        // 지점 선택 해제 시
        window.location.href = '/smgrmain/pg_van_settings';
    }
}

function loadPgVanSettings() {
    const bcoffCd = getSelectedBranchCode();
    
    if (!bcoffCd) {
        console.log('No branch code specified');
        return;
    }
    
    $.ajax({
        url: '/smgrmain/ajax_load_pg_van_settings',
        type: 'GET',
        data: { bcoff_cd: bcoffCd },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                populateSettingsForm(response.data);
            } else {
                console.error('Failed to load settings:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error loading settings:', status, error);
        }
    });
}

function populateSettingsForm(data) {
    const pgSettings = data.pg_settings || {};
    const vanSettings = data.van_settings || {};
    
    // PG 설정 적용
    for (const [provider, config] of Object.entries(pgSettings)) {
        const providerCard = $(`.provider-card[data-provider="${provider}"]`);
        if (providerCard.length > 0 && providerCard.closest('#pg-content').length > 0) {
            // 활성화 상태 설정
            const enabledCheckbox = providerCard.find(`#${provider}_enabled`);
            enabledCheckbox.prop('checked', config.enabled || false);
            
            // config form 표시/숨김
            const configForm = providerCard.find('.config-form');
            if (config.enabled) {
                configForm.addClass('active');
            } else {
                configForm.removeClass('active');
            }
            
            // 설정 값들 적용
            for (const [key, value] of Object.entries(config)) {
                if (key !== 'enabled') {
                    const input = providerCard.find(`input[name="${provider}_${key}"], select[name="${provider}_${key}"]`);
                    if (input.length > 0) {
                        input.val(value);
                    }
                }
            }
        }
    }
    
    // VAN 설정 적용
    for (const [provider, config] of Object.entries(vanSettings)) {
        const providerCard = $(`.provider-card[data-provider="${provider}"]`);
        if (providerCard.length > 0 && providerCard.closest('#van-content').length > 0) {
            // 활성화 상태 설정
            const enabledCheckbox = providerCard.find(`#${provider}_enabled`);
            enabledCheckbox.prop('checked', config.enabled || false);
            
            // config form 표시/숨김
            const configForm = providerCard.find('.config-form');
            if (config.enabled) {
                configForm.addClass('active');
            } else {
                configForm.removeClass('active');
            }
            
            // 설정 값들 적용
            for (const [key, value] of Object.entries(config)) {
                if (key !== 'enabled') {
                    const input = providerCard.find(`input[name="${provider}_${key}"], select[name="${provider}_${key}"]`);
                    if (input.length > 0) {
                        input.val(value);
                    }
                }
            }
        }
    }
}

// POS 설정 관련 함수들
$(document).ready(function() {
    // POS 토글 스위치 이벤트
    $('#pos_enabled').change(function() {
        const isEnabled = $(this).is(':checked');
        if (isEnabled) {
            $('#pos_config').addClass('active');
        } else {
            $('#pos_config').removeClass('active');
        }
    });
    
    // 연동 방식 변경 시 관련 설정 표시/숨김
    $('select[name="integration_mode"]').change(function() {
        const mode = $(this).val();
        $('.connection-type').hide();
        $(`.connection-type[data-type="${mode}"]`).show();
    });
});

// POS 연결 테스트
function testPosConnection() {
    const statusIcon = $('#pos-content .status-icon');
    const statusText = $('#pos-content .connection-status span');
    
    // 테스트 중 상태로 변경
    statusIcon.removeClass('status-connected status-disconnected').addClass('status-testing');
    statusText.text('테스트 중...');
    
    // POS 설정 데이터 수집
    const posSettings = collectPosSettings();
    
    $.ajax({
        url: '/smgrmain/ajax_test_pos_connection',
        type: 'POST',
        data: {
            settings: posSettings,
            bcoff_cd: getSelectedBranchCode()
        },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                statusIcon.removeClass('status-testing status-disconnected').addClass('status-connected');
                statusText.text('연결됨');
                
                let message = 'POS 연결 테스트 성공';
                if (response.data && response.data.device_info) {
                    message += ` - ${response.data.device_info}`;
                }
                alertToast('success', message);
            } else {
                statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
                statusText.text('연결안됨');
                alertToast('error', response.message || 'POS 연결 실패');
            }
        },
        error: function() {
            statusIcon.removeClass('status-testing status-connected').addClass('status-disconnected');
            statusText.text('연결안됨');
            alertToast('error', 'POS 연결 테스트 중 오류가 발생했습니다.');
        }
    });
}

// POS 설정 저장
function savePosSettings() {
    const posSettings = collectPosSettings();
    const bcoffCd = getSelectedBranchCode();
    
    if (!bcoffCd) {
        alertToast('error', '먼저 지점을 선택해주세요.');
        return;
    }
    
    $.ajax({
        url: '/smgrmain/ajax_save_pos_settings',
        type: 'POST',
        data: {
            pos_settings: posSettings,
            bcoff_cd: bcoffCd
        },
        dataType: 'json',
        success: function(response) {
            if (response.result === 'success') {
                alertToast('success', 'POS 설정이 저장되었습니다.');
            } else {
                alertToast('error', response.message || 'POS 설정 저장 실패');
            }
        },
        error: function() {
            alertToast('error', 'POS 설정 저장 중 오류가 발생했습니다.');
        }
    });
}

// POS 설정 데이터 수집
function collectPosSettings() {
    return {
        use_pos: $('#pos_enabled').is(':checked'),
        pos_type: $('select[name="pos_type"]').val(),
        pos_priority: $('select[name="pos_priority"]').val(),
        integration_mode: $('select[name="integration_mode"]').val(),
        pos_device: {
            manufacturer: $('select[name="pos_manufacturer"]').val(),
            model: $('input[name="pos_model"]').val(),
            serial_number: $('input[name="pos_serial_number"]').val()
        },
        connection: {
            // API 설정
            api_endpoint: $('input[name="api_endpoint"]').val(),
            api_key: $('input[name="api_key"]').val(),
            // 시리얼 설정
            com_port: $('select[name="com_port"]').val(),
            baud_rate: $('select[name="baud_rate"]').val(),
            data_bits: $('select[name="data_bits"]').val(),
            parity: $('select[name="parity"]').val(),
            // 네트워크 설정
            ip_address: $('input[name="pos_ip_address"]').val(),
            port: $('input[name="pos_port"]').val()
        },
        fallback_options: {
            manual_card: $('#fallback_manual_card').is(':checked'),
            cash: $('#fallback_cash').is(':checked'),
            bank_transfer: $('#fallback_bank_transfer').is(':checked'),
            pg: $('#fallback_pg').is(':checked')
        },
        auto_receipt: $('#auto_receipt').is(':checked'),
        signature_pad: $('#signature_pad').is(':checked')
    };
}

// POS 연동 가이드 보기
function viewPosDocumentation() {
    window.open('/docs/pos-integration-guide', '_blank');
}

// POS 결제 시뮬레이션
function simulatePosPayment() {
    const posSettings = collectPosSettings();
    
    if (!posSettings.use_pos) {
        alertToast('warning', 'POS를 먼저 활성화해주세요.');
        return;
    }
    
    // 시뮬레이션 모달 열기 또는 테스트 결제 진행
    ToastConfirm.fire({
        icon: 'question',
        title: 'POS 결제 시뮬레이션',
        html: '<font color="#000000">테스트 결제를 진행하시겠습니까?<br>금액: 1,000원 (테스트)</font>',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: '테스트 진행',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {
            // 시뮬레이션 실행
            $.ajax({
                url: '/smgrmain/ajax_simulate_pos_payment',
                type: 'POST',
                data: {
                    amount: 1000,
                    bcoff_cd: getSelectedBranchCode()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result === 'success') {
                        alertToast('success', 'POS 결제 시뮬레이션 성공');
                    } else {
                        alertToast('error', response.message || 'POS 결제 시뮬레이션 실패');
                    }
                }
            });
        }
    });
}
</script>