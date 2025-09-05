<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : '락커 배치 관리' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/locker4/css/index.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= base_url('assets/locker4/css/LockerPlacementFigma.css') ?>?v=<?= time() ?>">
</head>
<body>
    <!-- Vue App Container -->
    <div id="app"></div>
    
    <!-- PHP to Vue Data Bridge -->
    <script>
    window.LockerConfig = {
        // API Configuration
        apiUrl: '<?= base_url('api/locker') ?>',
        baseUrl: '<?= rtrim(base_url(), '/') ?>',
        
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
        }
    };
    
    console.log('[Locker4] Configuration loaded:', window.LockerConfig);
    </script>
    
    <!-- Vue App -->
    <script type="module" src="<?= base_url('assets/locker4/js/index.js') ?>?v=<?= time() ?>"></script>
    <script type="module" src="<?= base_url('assets/locker4/js/LockerPlacementFigma.js') ?>?v=<?= time() ?>"></script>
</body>
</html>