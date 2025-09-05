<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}



/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

// 체크인 페이지 라우트 (우선순위 높음)
$routes->match(['get', 'post'], 'checkin', 'Teventmem::checkin');
$routes->match(['get', 'post'], 'checkin/(:any)', 'Teventmem::checkin/$1');

// 추가된 사용자 정의 라우트
$routes->post('/ajax/loadView', 'AjaxController::loadView');

// 이니시스 결제 관련 라우트
$routes->group('payment', function($routes) {
    // 이니시스 결제 초기화
    $routes->post('inicis/init', 'Payment::inicisInit');
    
    // 이니시스 결제 완료 처리 (Return URL)
    $routes->match(['get', 'post'], 'inicis/return', 'Payment::inicisReturn');
    
    // 이니시스 결제 취소
    $routes->post('inicis/cancel', 'Payment::inicisCancel');
    
    // 이니시스 노티피케이션 (Webhook)
    $routes->post('inicis/notification', 'Payment::inicisNotification');
    
    // 결제 결과 페이지
    $routes->get('success', 'Payment::success');
    $routes->get('error', 'Payment::error');
});


$uri = service('uri');
$ext_uri = '';
for ($uri_seg_count=3; $uri_seg_count < $uri->getTotalSegments()+1; $uri_seg_count++)
{
	if ($uri_seg_count > 3) $ext_uri .= '/';
	$ext_uri .= $uri->getSegment($uri_seg_count);
}
if (preg_match('/^fr_/',$uri->getSegment(1),$matches) == 1)
{
	$routes->get($uri->getSegment(1).'/(:any)', ucfirst($uri->getSegment(2)).'::'.$ext_uri);
}

//$routes->match(['get','post'],'news/create','News::create');
//$routes->get('news/(:segment)','News::view/$1/$2');
//$routes->get('news', 'News::index');
//$routes->get('(:any)', 'Pages::view/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

$routes->get('tcron', 'Tcron::index');
$routes->post('tcron/run_cron', 'Tcron::run_cron');

// 락커 관리 라우트
$routes->get('locker', 'Locker::index');
$routes->get('locker/setting', 'Locker::setting');        // 락커 배치 설정
$routes->get('locker/placement', 'Locker::placement');    // 락커 배치 Vue 앱
$routes->post('locker/upload_floor', 'Locker::upload_floor');
$routes->post('locker/save_zone', 'Locker::save_zone');
$routes->post('locker/save_group', 'Locker::save_group');
$routes->post('locker/save_front', 'Locker::save_front');
$routes->post('locker/update_locker_numbers', 'Locker::update_locker_numbers');
$routes->get('locker/get_zones', 'Locker::get_zones');
$routes->get('locker/get_groups', 'Locker::get_groups');
$routes->get('locker/get_lockers', 'Locker::get_lockers');

$routes->get('/admin/generate-test-floor-plan', 'Admin::generateTestFloorPlan');
$routes->get('/admin/getDatabaseStats', 'Admin::getDatabaseStats');
$routes->get('/admin/getPythonServerStatus', 'Admin::getPythonServerStatus');
$routes->get('/admin/databaseStatus', 'Admin::databaseStatus');

// API 라우트
$routes->post('api/qrcode_attd', 'Api::qrcode_attd');
$routes->post('api/tqrcode_attd', 'Api::tqrcode_attd');
$routes->post('api/get_member_tickets', 'Api::get_member_tickets');
$routes->post('api/get_member_tickets_by_telno', 'Api::get_member_tickets_by_telno');
$routes->post('api/checkin_with_ticket', 'Api::checkin_with_ticket');
$routes->post('api/direct_attd/(:segment)', 'Api::ajax_direct_attd/$1');
$routes->get('api/setcomp_proc/(:segment)/(:segment)', 'Api::ajax_setcomp_proc/$1/$2');

// 얼굴 인식 API 라우트
$routes->post('api/face/register', 'FaceApi::register');
$routes->post('api/face/recognize', 'FaceApi::recognize');
$routes->get('api/face/health', 'FaceApi::health_check');

// FaceTest 컨트롤러 라우트 (회원가입용)
$routes->get('FaceTest/health', 'FaceTest::health');
$routes->post('FaceTest/recognize_for_registration', 'FaceTest::recognize_for_registration');
$routes->post('FaceTest/test_register', 'FaceTest::test_register');
$routes->post('FaceTest/test_recognize', 'FaceTest::test_recognize');

/*
 * --------------------------------------------------------------------
 * API Routes - Kiosk Face Authentication
 * --------------------------------------------------------------------
 */
$routes->group('api/v1/kiosk', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // 얼굴 인증 API
    $routes->post('face-auth', 'KioskAuthApi::faceAuth');
    
    // 체크인 처리 API
    $routes->post('checkin', 'KioskAuthApi::checkin');
    
    // API 상태 확인
    $routes->get('status', 'KioskAuthApi::status');
});

// 테스트용 API (로그인 체크 없음)
$routes->group('api/v1/kiosk-test', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->get('status', 'KioskAuthApiTest::status');
    $routes->post('face-auth', 'KioskAuthApiTest::faceAuth');
    $routes->post('checkin', 'KioskAuthApiTest::checkin');
});

// 디버깅용 API
$routes->get('api/v1/kiosk/debug/test-api-key', 'Api\KioskAuthApiDebug::testApiKey');

// SMS 인증 로그인 관련 라우트
$routes->post('api/loginWithPhone', 'Api::loginWithPhone');
$routes->post('api/verifySmsAutoLogin', 'Api::verifySmsAutoLogin');
$routes->post('api/checkAutoLogin', 'Api::checkAutoLogin');
$routes->post('api/mobileLogout', 'Api::mobileLogout');
// 모바일 회원가입 관련 라우트
$routes->get('api/mobile_register', 'Api::mobile_register');
$routes->post('api/checkPhoneVerification', 'Api::checkPhoneVerification');
$routes->get('api/getCompanyList', 'Api::getCompanyList');
$routes->get('api/getBranchList', 'Api::getBranchList');
$routes->post('api/mobileRegisterProc', 'Api::mobileRegisterProc');

// Locker API Routes
$routes->group('api/locker', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->get('/', 'Locker::index');                    // GET all lockers
    $routes->get('(:num)', 'Locker::show/$1');            // GET single locker
    $routes->post('/', 'Locker::create');                  // POST create locker
    $routes->put('(:num)', 'Locker::update/$1');          // PUT update locker
    $routes->delete('(:num)', 'Locker::delete/$1');       // DELETE locker
    $routes->post('(:num)/tiers', 'Locker::addTiers/$1'); // POST add tiers
    $routes->put('batch', 'Locker::batchUpdate');         // PUT batch update
    $routes->options('/', 'Locker::options');             // OPTIONS for CORS
    $routes->options('(:any)', 'Locker::options');        // OPTIONS for CORS
});

// Node.js API Compatible Routes
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // Locker routes (Node.js compatible)
    $routes->get('lockrs', 'Locker::index');               // GET all lockers
    $routes->post('lockrs', 'Locker::create');             // POST create locker
    $routes->put('lockrs/(:num)', 'Locker::update/$1');   // PUT update locker
    $routes->delete('lockrs/(:num)', 'Locker::delete/$1'); // DELETE locker
    $routes->post('lockrs/(:num)/tiers', 'Locker::addTiers/$1'); // POST add tiers
    
    // Zone routes - Node.js compatible paths
    $routes->get('lockrs/zones', 'Locker::zones');         // GET all zones
    $routes->post('lockrs/zones', 'Locker::createZone');   // POST create zone
    $routes->delete('lockrs/zones/(:segment)', 'Locker::deleteZone/$1'); // DELETE zone
    
    // Type routes - Node.js compatible paths
    $routes->get('lockrs/types', 'Locker::types');         // GET all types
    $routes->post('lockrs/types', 'Locker::createType');   // POST create type
    $routes->delete('lockrs/types/(:segment)', 'Locker::deleteType/$1'); // DELETE type
    
    // Keep old routes for backward compatibility
    $routes->get('zones', 'Locker::zones');                // GET all zones
    $routes->get('types', 'Locker::types');                // GET all types
});
