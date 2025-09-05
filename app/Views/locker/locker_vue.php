<?php
$sDef = SpoqDef();
helper('form');
?>
<!-- Main content -->
<h1 class="page-header"><?php echo $title ?></h1>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo $title ?></h4>
    </div>
    
    <div class="panel-body" style="min-height: 700px; padding: 15px;">
        <!-- Vue App Container -->
        <div id="app" style="width: 100%; height: 700px;"></div>
    </div>
</div>

<!-- PHP to Vue Data Bridge -->
<script>
window.LockerConfig = {
    // API Configuration
    apiUrl: '<?= base_url('api/locker') ?>',
    baseUrl: '<?= base_url() ?>',
    
    // CSRF Protection
    csrfToken: '<?= csrf_token() ?>',
    csrfHeader: '<?= csrf_header() ?>',
    csrfHash: '<?= csrf_hash() ?>',
    
    // Company and Branch Info
    companyCode: '<?= isset($companyCode) ? $companyCode : '001' ?>',
    officeCode: '<?= isset($officeCode) ? $officeCode : '001' ?>',
    
    // User Session Data
    user: {
        id: '<?= session()->get('user_id') ?? '' ?>',
        name: '<?= session()->get('user_name') ?? '' ?>',
        role: '<?= session()->get('user_role') ?? '' ?>',
        isLoggedIn: <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>
    },
    
    // Application Settings
    settings: {
        dateFormat: 'YYYY-MM-DD',
        timeFormat: 'HH:mm:ss',
        locale: 'ko-KR',
        currency: 'KRW'
    },
    
    // Feature Flags
    features: {
        enableApi: true,
        enableRealtime: false,
        enableDebugMode: <?= ENVIRONMENT === 'development' ? 'true' : 'false' ?>
    },
    
    // Initial Route - 특정 라우트로 시작
    initialRoute: '/locker-placement'
};

console.log('[Locker4] Configuration loaded:', window.LockerConfig);
</script>

<!-- Vue App Assets -->
<link rel="stylesheet" href="<?= base_url('assets/locker4/css/index.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/locker4/css/LockerPlacementFigma.css') ?>">
<script type="module" src="<?= base_url('assets/locker4/js/index.js') ?>"></script>