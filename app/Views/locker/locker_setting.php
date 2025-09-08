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
    
    <div class="panel-body" style="min-height: 800px;">
        <!-- Vue App Container -->
        <div id="app"></div>
    </div>
</div>

<!-- PHP to Vue Data Bridge - MUST be before Vue app loads -->
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
    
    // Initial Route for Vue Router
    initialRoute: '/locker-placement'
};

// Debug info in development
<?php if(ENVIRONMENT === 'development'): ?>
console.log('[Locker4] Configuration loaded:', window.LockerConfig);
<?php endif; ?>
</script>

<?php if(ENVIRONMENT === 'development' && isset($_GET['dev'])): ?>
<!-- Development Mode: Using Vite Dev Server -->
<script type="module" src="http://localhost:5175/assets/locker4/@vite/client"></script>
<script type="module" src="http://localhost:5175/assets/locker4/src/main.ts"></script>
<?php else: ?>
<!-- Production Mode: Using Built Assets -->
<link rel="stylesheet" crossorigin href="<?= base_url('assets/locker4/css/index.css') ?>">
<script type="module" crossorigin src="<?= base_url('assets/locker4/js/index.js') ?>"></script>
<?php endif; ?>

<!-- Debug Info for Development -->
<?php if(ENVIRONMENT === 'development'): ?>
<script>
console.log('[Locker4 Debug] Page loaded');
console.log('[Locker4 Debug] Base URL:', '<?= base_url() ?>');
console.log('[Locker4 Debug] Assets URL:', '<?= base_url('assets/locker4/') ?>');

// Check if Vue app mounts after page load
window.addEventListener('DOMContentLoaded', () => {
    console.log('[Locker4 Debug] DOM loaded');
    const appElement = document.getElementById('app');
    console.log('[Locker4 Debug] App element found:', appElement);
    
    // Check after a delay
    setTimeout(() => {
        if (appElement && appElement.children.length > 0) {
            console.log('[Locker4 Debug] ✅ Vue app mounted successfully!');
        } else {
            console.error('[Locker4 Debug] ❌ Vue app failed to mount');
            console.log('[Locker4 Debug] Checking window.LockerConfig:', window.LockerConfig ? 'Found' : 'Missing');
        }
    }, 2000);
});
</script>
<?php endif; ?>