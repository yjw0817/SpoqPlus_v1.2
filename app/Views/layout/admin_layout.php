<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8" />
    <title>관리자 페이지</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="/dist/css/vendor.min.css" rel="stylesheet" />
    <link href="/dist/css/apple/app.min.css" rel="stylesheet" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL CSS ================== -->
    <link href="/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
    <link href="/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/assets/css/locker.css" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL CSS ================== -->
</head>
<body>
    <!-- begin #app -->
    <div id="app" class="app app-header-fixed app-sidebar-fixed">
        <!-- begin #header -->
        <div id="header" class="app-header">
            <!-- begin navbar-header -->
            <div class="navbar-header">
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- end navbar-header -->
        </div>
        <!-- end #header -->
        
        <!-- begin #sidebar -->
        <div id="sidebar" class="app-sidebar">
            <!-- begin scrollbar -->
            <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
                <!-- begin menu -->
                <div class="menu">
                    <div class="menu-item">
                        <a href="<?= site_url('locker') ?>" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-box"></i>
                            </div>
                            <div class="menu-text">락커 관리</div>
                        </a>
                    </div>
                </div>
                <!-- end menu -->
            </div>
            <!-- end scrollbar -->
        </div>
        <!-- end #sidebar -->
        
        <!-- begin #content -->
        <div id="content" class="app-content">
            <?= $this->renderSection('content') ?>
        </div>
        <!-- end #content -->
    </div>
    <!-- end #app -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="/plugins/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
    <script src="/dist/js/vendor.min.js"></script>
    <script src="/dist/js/app.min.js"></script>
    <script src="/dist/js/theme/apple.min.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/js/locker.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    
    <script>
    // Initialize PerfectScrollbar
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.querySelector('.app-sidebar-content');
        var ps = new PerfectScrollbar(container, {
            wheelSpeed: 2,
            wheelPropagation: false,
            minScrollbarLength: 20
        });
    });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html> 