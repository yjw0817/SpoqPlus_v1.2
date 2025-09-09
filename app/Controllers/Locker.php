<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LockerModel;
use CodeIgniter\Files\File;
use CodeIgniter\I18n\Time;
use App\Libraries\MenuHelper;
use App\Models\FloorModel;
use App\Models\ZoneModel;
use App\Models\LockerGroupModel;

class Locker extends MainTchrController
{
    private $lockerModel;
    protected $floorModel;
    protected $zoneModel;
    protected $lockerGroupModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->lockerModel = new LockerModel();
        $this->floorModel = new FloorModel();
        $this->zoneModel = new ZoneModel();
        $this->lockerGroupModel = new LockerGroupModel();
    }

    /**
     * 락커 (배정)관리
     */
    public function locker_management()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $modelLocker = new \App\Models\LockerModel();
        
        // 데이터 가져오기
        $floors = $modelLocker->get_floor_list([
            'comp_cd' => $this->SpoQCahce->getCacheVar('comp_cd'),
            'bcoff_cd' => $this->SpoQCahce->getCacheVar('bcoff_cd')
        ]);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['floors'] = $floors;
        
        $this->viewPage('/locker/locker_management', $data);
    }

    /**
     * 락커구역별 배치
     */
    public function locker_placement()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $modelLocker = new \App\Models\LockerModel();
        
        // 데이터 설정
        $data['companyCode'] = $this->SpoQCahce->getCacheVar('comp_cd') ?? '001';
        $data['officeCode'] = $this->SpoQCahce->getCacheVar('bcoff_cd') ?? '001';
        
        // ===========================================================================
        // 서버 렌더링을 위한 데이터 미리 로딩
        // ===========================================================================
        $db = \Config\Database::connect();
        
        // 락커 타입 데이터 로딩
        $builder = $db->table('lockr_types');
        $builder->where('COMP_CD', $data['companyCode']);
        $builder->where('BCOFF_CD', $data['officeCode']);
        $result = $builder->get();
        $lockerTypes = $result->getResultArray();
        
        // API 형식으로 변환
        $formattedTypes = [];
        foreach ($lockerTypes as $type) {
            $formattedTypes[] = [
                'LOCKR_TYPE_CD' => $type['LOCKR_TYPE_CD'],
                'LOCKR_TYPE_NM' => $type['LOCKR_TYPE_NM'],
                'WIDTH' => intval($type['WIDTH']),
                'HEIGHT' => intval($type['HEIGHT']),
                'DEPTH' => intval($type['DEPTH']),
                'COLOR' => $type['COLOR']
            ];
        }
        
        $data['lockerTypes'] = $formattedTypes;
        
        // 락커 구역 데이터 로딩
        $builder = $db->table('lockr_area');
        $builder->where('COMP_CD', $data['companyCode']);
        $builder->where('BCOFF_CD', $data['officeCode']);
        $result = $builder->get();
        $lockerZones = $result->getResultArray();
        
        // API 형식으로 변환
        $formattedZones = [];
        foreach ($lockerZones as $zone) {
            $formattedZones[] = [
                'LOCKR_KND_CD' => $zone['LOCKR_KND_CD'],
                'LOCKR_KND_NM' => $zone['LOCKR_KND_NM'],
                'X' => intval($zone['X'] ?? 0),
                'Y' => intval($zone['Y'] ?? 0),
                'WIDTH' => intval($zone['WIDTH'] ?? 800),
                'HEIGHT' => intval($zone['HEIGHT'] ?? 600),
                'COLOR' => $zone['COLOR'] ?? '#e5e7eb'
            ];
        }
        
        $data['lockerZones'] = $formattedZones;
        
        // 락커 데이터 로딩 (모든 락커)
        $builder = $db->table('lockrs');
        $builder->where('COMP_CD', $data['companyCode']);
        $builder->where('BCOFF_CD', $data['officeCode']);
        $builder->orderBy('LOCKR_CD', 'ASC');
        $result = $builder->get();
        $lockers = $result->getResultArray();
        
        $data['lockers'] = $lockers;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $this->viewPage('/locker/locker_placement', $data);
    }

    /**
     * 락커 현황 대시보드
     */
    public function dashboard()
    {
        $data = MenuHelper::getMenuData($this->request);
        $data['title'] = '락커 현황 대시보드';
        
        $this->viewPage('/locker/locker_dashboard', $data);
    }

    /**
     * 도면 업로드 처리
     */
    public function ajax_upload_floor()
    {
        try {
            if ($this->request->getMethod() !== 'post') {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 요청입니다.']);
            }

            $floor_ord = $this->request->getPost('floor_ord');
            if (!is_numeric($floor_ord)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '정렬순서는 숫자만 입력 가능합니다.']);
            }

            $file = $this->request->getFile('floor_img');
            
            if (!$file) {
                return $this->response->setJSON(['status' => 'error', 'message' => '파일이 전송되지 않았습니다.']);
            }

            if (!$file->isValid()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '파일 업로드 오류: ' . $file->getErrorString()]);
            }

            if (!in_array($file->getClientMimeType(), ['image/jpeg', 'image/png'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'JPG 또는 PNG 파일만 업로드 가능합니다.']);
            }

            $comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
            $bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');
            
            $uploadPath = ROOTPATH . 'public/uploads/floors/' . $comp_cd . '/' . $bcoff_cd;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $file->getRandomName();
            if (!$file->move($uploadPath, $newName)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '파일 이동 실패: ' . $file->getErrorString()]);
            }

            $modelLocker = new \App\Models\LockerModel();
            
            $data = [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd,
                'floor_nm' => $this->request->getPost('floor_nm'),
                'floor_img' => $newName,
                'floor_ord' => $this->request->getPost('floor_ord'),
                'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'cre_datetm' => new \CodeIgniter\I18n\Time('now'),
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => new \CodeIgniter\I18n\Time('now')
            ];

            if ($modelLocker->insert_floor($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => '도면이 등록되었습니다.']);
            }

            unlink($uploadPath . '/' . $newName);
            return $this->response->setJSON(['status' => 'error', 'message' => '도면 등록에 실패했습니다.']);
            
        } catch (\Exception $e) {
            log_message('error', '[upload_floor] Error: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => '오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }

    /**
     * 구역 저장
     */
    public function ajax_save_zone()
    {
        try {
            if ($this->request->getMethod() !== 'post') {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 요청입니다.']);
            }

            $modelLocker = new \App\Models\LockerModel();
            $update_mode = $this->request->getPost('update_mode');
            $zone_sno = $this->request->getPost('zone_sno');
            
            // 도면 정보 가져오기
            $floor_info = $modelLocker->get_floor_info([
                'floor_sno' => $this->request->getPost('floor_sno')
            ]);
            
            if (empty($floor_info)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '도면 정보를 찾을 수 없습니다.']);
            }

            // 업데이트 모드인지 확인
            if ($update_mode && $zone_sno) {
                // 기존 구역 업데이트
                $updateData = [
                    'zone_coords' => $this->request->getPost('zone_coords'),
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => new \CodeIgniter\I18n\Time('now')
                ];
                
                log_message('info', '[ajax_save_zone] Updating zone: ' . $zone_sno);
                
                // ZoneModel을 사용하여 업데이트 시도
                $result = $this->zoneModel->update($zone_sno, $updateData);
                
                if ($result) {
                    return $this->response->setJSON([
                        'status' => 'success', 
                        'zone_sno' => $zone_sno,
                        'message' => '구역이 업데이트되었습니다.'
                    ]);
                } else {
                    $errors = $this->zoneModel->errors();
                    log_message('error', '[ajax_save_zone] ZoneModel update failed: ' . print_r($errors, true));
                    
                    return $this->response->setJSON([
                        'status' => 'error', 
                        'message' => '구역 업데이트에 실패했습니다.',
                        'errors' => $errors
                    ]);
                }
            } else {
                // 새로운 구역 생성
                $data = [
                    'comp_cd' => $floor_info['comp_cd'],
                    'bcoff_cd' => $floor_info['bcoff_cd'],
                    'floor_sno' => $this->request->getPost('floor_sno'),
                    'zone_nm' => $this->request->getPost('zone_nm'),
                    'zone_coords' => $this->request->getPost('zone_coords'),
                    'zone_gendr' => $this->request->getPost('zone_gendr'),
                    'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'cre_datetm' => new \CodeIgniter\I18n\Time('now'),
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => new \CodeIgniter\I18n\Time('now')
                ];

                // 데이터에 use_yn 추가 (기본값)
                $data['use_yn'] = 'Y';
                
                // ZoneModel을 사용하여 insert 시도
                $zone_sno = $this->zoneModel->insert($data);
                
                if ($zone_sno) {
                    return $this->response->setJSON([
                        'status' => 'success', 
                        'zone_sno' => $zone_sno,
                        'message' => '구역이 등록되었습니다.'
                    ]);
                }

                // ZoneModel 실패 시 기존 LockerModel 방식으로 백업 시도
                log_message('info', '[ajax_save_zone] ZoneModel failed, trying LockerModel backup');
                $errors = $this->zoneModel->errors();
                log_message('error', '[ajax_save_zone] ZoneModel errors: ' . print_r($errors, true));
                
                if ($backup_result = $modelLocker->insert_zone($data)) {
                    // LockerModel에서 zone_sno 가져오기 - 최근 삽입된 ID 또는 결과값 사용
                    $zone_sno = is_numeric($backup_result) ? $backup_result : $this->zoneModel->getInsertID();
                    
                    if (!$zone_sno) {
                        // 직접 조회로 zone_sno 찾기
                        $zone_sno = $this->zoneModel->where([
                            'floor_sno' => $data['floor_sno'],
                            'zone_nm' => $data['zone_nm'],
                            'zone_coords' => $data['zone_coords'],
                            'cre_id' => $data['cre_id']
                        ])->orderBy('zone_sno', 'DESC')->first()['zone_sno'] ?? null;
                    }
                    
                    return $this->response->setJSON([
                        'status' => 'success', 
                        'zone_sno' => $zone_sno,
                        'message' => '구역이 등록되었습니다.'
                    ]);
                }
                
                return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => '구역 등록에 실패했습니다.',
                    'errors' => $errors
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[ajax_save_zone] Error: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => '오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }

    // 락커 그룹 관리
    public function save_group()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 요청입니다.']);
        }

        $data = [
            'zone_sno' => $this->request->getPost('zone_sno'),
            'group_nm' => $this->request->getPost('group_nm'),
            'rows' => $this->request->getPost('rows'),
            'cols' => $this->request->getPost('cols'),
            'locker_width' => $this->request->getPost('locker_width'),
            'locker_depth' => $this->request->getPost('locker_depth'),
            'group_coords' => $this->request->getPost('group_coords'),
            'cre_id' => session()->get('user_id'),
            'cre_datetm' => new Time('now'),
            'mod_id' => session()->get('user_id'),
            'mod_datetm' => new Time('now')
        ];

        if ($this->lockerModel->insert_group($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => '락커 그룹이 등록되었습니다.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => '락커 그룹 등록에 실패했습니다.']);
    }

    // 정면도 관리
    public function save_front()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 요청입니다.']);
        }

        $data = [
            'group_sno' => $this->request->getPost('group_sno'),
            'front_rows' => $this->request->getPost('front_rows'),
            'front_height' => $this->request->getPost('front_height'),
            'cre_id' => session()->get('user_id'),
            'cre_datetm' => new Time('now'),
            'mod_id' => session()->get('user_id'),
            'mod_datetm' => new Time('now')
        ];

        if ($front_sno = $this->lockerModel->insert_front($data)) {
            // 락커 번호 자동 생성
            $group_info = $this->lockerModel->get_group_info($data['group_sno']);
            $lockers = [];
            
            for ($floor = 1; $floor <= $data['front_rows']; $floor++) {
                for ($row = 1; $row <= $group_info['group_rows']; $row++) {
                    for ($col = 1; $col <= $group_info['group_cols']; $col++) {
                        $lockers[] = [
                            'front_sno' => $front_sno,
                            'locker_no' => '', // 번호는 나중에 업데이트
                            'locker_row' => $row,
                            'locker_col' => $col,
                            'locker_floor' => $floor,
                            'cre_id' => session()->get('user_id'),
                            'cre_datetm' => new Time('now'),
                            'mod_id' => session()->get('user_id'),
                            'mod_datetm' => new Time('now')
                        ];
                    }
                }
            }

            if ($this->lockerModel->insert_locker_batch($lockers)) {
                return $this->response->setJSON(['status' => 'success', 'message' => '정면도가 등록되었습니다.']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => '정면도 등록에 실패했습니다.']);
    }

    // 락커 번호 업데이트
    public function update_locker_numbers()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 요청입니다.']);
        }

        $front_sno = $this->request->getPost('front_sno');
        $numbers = json_decode($this->request->getPost('numbers'), true);

        $success = true;
        foreach ($numbers as $locker_sno => $number) {
            $data = [
                'locker_no' => $number,
                'mod_id' => session()->get('user_id'),
                'mod_datetm' => new Time('now')
            ];

            if (!$this->lockerModel->update_locker($locker_sno, $data)) {
                $success = false;
                break;
            }
        }

        if ($success) {
            return $this->response->setJSON(['status' => 'success', 'message' => '락커 번호가 업데이트되었습니다.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => '락커 번호 업데이트에 실패했습니다.']);
    }

    /**
     * 구역 목록 조회
     */
    public function ajax_get_zones()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
        }

        try {
            $floor_sno = $this->request->getGet('floor_sno');
            
            // 구역 목록 조회
            $zones = $this->zoneModel->where('floor_sno', $floor_sno)
                                   ->where('use_yn', 'Y')
                                   ->findAll();

            // 디버깅을 위한 로그 추가
            log_message('debug', 'Zones found for floor_sno ' . $floor_sno . ': ' . json_encode($zones));

            return $this->response->setJSON([
                'status' => 'success',
                'zones' => $zones
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ajax_get_zones] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '구역 목록을 불러오는데 실패했습니다.'
            ]);
        }
    }

    public function get_groups()
    {
        try {
            $zone_sno = $this->request->getGet('zone_sno');
            
            // 디버깅 로그 추가
            log_message('info', '[get_groups] 요청받은 zone_sno: ' . $zone_sno);
            
            if (empty($zone_sno)) {
                log_message('error', '[get_groups] zone_sno가 비어있습니다.');
                return $this->response->setJSON(['error' => 'zone_sno is required']);
            }
            
            $groups = $this->lockerModel->get_group_list($zone_sno);
            
            // 디버깅 로그 추가
            log_message('info', '[get_groups] 조회된 그룹 수: ' . count($groups));
            log_message('info', '[get_groups] 조회 결과: ' . json_encode($groups));
            
            return $this->response->setJSON($groups);
            
        } catch (\Exception $e) {
            log_message('error', '[get_groups] 오류 발생: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function get_lockers()
    {
        $front_sno = $this->request->getGet('front_sno');
        $zone_id = $this->request->getGet('zone_id');
        $comp_cd = $this->request->getGet('comp_cd') ?? $this->SpoQCahce->getCacheVar('comp_cd') ?? '001';
        $bcoff_cd = $this->request->getGet('bcoff_cd') ?? $this->SpoQCahce->getCacheVar('bcoff_cd') ?? '001';
        
        // lockrs 테이블에서 데이터 조회
        $db = \Config\Database::connect();
        $builder = $db->table('lockrs');
        
        $builder->where('COMP_CD', $comp_cd);
        $builder->where('BCOFF_CD', $bcoff_cd);
        
        if ($zone_id) {
            $builder->where('LOCKR_KND', $zone_id);
        }
        
        $lockers = $builder->get()->getResultArray();
        
        // 데이터가 없으면 기존 방식으로 시도
        if (empty($lockers) && $front_sno) {
            $lockers = $this->lockerModel->get_locker_list($front_sno);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'lockers' => $lockers
        ]);
    }

    /**
     * 락커 생성 (AJAX)
     */
    public function ajax_create_locker()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $data = $this->request->getJSON(true);
            
            // lockrs 테이블에 데이터 삽입
            $db = \Config\Database::connect();
            $builder = $db->table('lockrs');
            
            $insertData = [
                'COMP_CD' => $data['COMP_CD'] ?? '001',
                'BCOFF_CD' => $data['BCOFF_CD'] ?? '001',
                'LOCKR_KND' => $data['LOCKR_KND'] ?? '',
                'LOCKR_TYPE_CD' => $data['LOCKR_TYPE_CD'] ?? '1',
                'X' => $data['X'] ?? 0,
                'Y' => $data['Y'] ?? 0,
                'LOCKR_LABEL' => $data['LOCKR_LABEL'] ?? '',
                'ROTATION' => $data['ROTATION'] ?? 0,
                'LOCKR_STAT' => $data['LOCKR_STAT'] ?? '00',
                'LOCKR_NO' => $data['LOCKR_NO'] ?? null,
                'UPDATE_DT' => date('Y-m-d H:i:s')
            ];
            
            $builder->insert($insertData);
            $lockrCd = $db->insertID();
            
            $insertData['LOCKR_CD'] = $lockrCd;
            
            return $this->response->setJSON([
                'status' => 'success',
                'locker' => $insertData
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[ajax_create_locker] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 업데이트 (AJAX)
     */
    public function ajax_update_locker($lockrCd = null)
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $data = $this->request->getJSON(true);
            
            // lockrs 테이블 업데이트
            $db = \Config\Database::connect();
            $builder = $db->table('lockrs');
            
            $updateData = [
                'X' => $data['X'] ?? 0,
                'Y' => $data['Y'] ?? 0,
                'ROTATION' => $data['ROTATION'] ?? 0,
                'LOCKR_LABEL' => $data['LOCKR_LABEL'] ?? '',
                'LOCKR_TYPE_CD' => $data['LOCKR_TYPE_CD'] ?? '1',
                'LOCKR_KND' => $data['LOCKR_KND'] ?? '',
                'UPDATE_DT' => date('Y-m-d H:i:s')
            ];
            
            $builder->where('LOCKR_CD', $lockrCd);
            $builder->update($updateData);
            
            $updateData['LOCKR_CD'] = $lockrCd;
            
            return $this->response->setJSON([
                'status' => 'success',
                'locker' => $updateData
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[ajax_update_locker] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 삭제 (AJAX)
     */
    public function ajax_delete_locker($lockrCd = null)
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            // lockrs 테이블에서 삭제
            $db = \Config\Database::connect();
            $builder = $db->table('lockrs');
            
            $builder->where('LOCKR_CD', $lockrCd);
            $builder->delete();
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '락커가 삭제되었습니다.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[ajax_delete_locker] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 도면 삭제
     */
    public function ajax_delete_floor()
    {
        try {
            if ($this->request->getMethod() !== 'post') {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 요청입니다.']);
            }

            $floor_sno = $this->request->getPost('floor_sno');
            if (!$floor_sno) {
                return $this->response->setJSON(['status' => 'error', 'message' => '도면 번호가 누락되었습니다.']);
            }

            $modelLocker = new \App\Models\LockerModel();
            $comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
            $bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');

            // 1. 먼저 도면 정보를 가져옵니다
            $floor_info = $modelLocker->get_floor_info([
                'floor_sno' => $floor_sno,
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);

            if (empty($floor_info)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '도면 정보를 찾을 수 없습니다.']);
            }

            // 2. 해당 도면의 구역이 있는지 확인
            $zones = $modelLocker->get_zone_list([
                'floor_sno' => $floor_sno,
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);

            // 3. 구역이 있다면 먼저 삭제
            if (!empty($zones)) {
                $modelLocker->delete_zones([
                    'floor_sno' => $floor_sno,
                    'comp_cd' => $comp_cd,
                    'bcoff_cd' => $bcoff_cd
                ]);
            }

            // 4. 도면 삭제 처리
            $delete_result = $modelLocker->delete_floor([
                'floor_sno' => $floor_sno,
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd
            ]);

            if ($delete_result) {
                // 5. 파일 삭제
                $file_path = ROOTPATH . 'public/uploads/floors/' . $comp_cd . '/' . $bcoff_cd . '/' . $floor_info['floor_img'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }

                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => '도면이 삭제되었습니다.',
                    'had_zones' => !empty($zones)
                ]);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => '도면 삭제에 실패했습니다.']);
            
        } catch (\Exception $e) {
            log_message('error', '[delete_floor] Error: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => '오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }


    // 구역 상세 페이지
    public function zone_detail($zone_sno)
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $zone = $this->zoneModel->find($zone_sno);
        if (!$zone) {
            return redirect()->to('/locker/manage')->with('error', '구역을 찾을 수 없습니다.');
        }

        $floor = $this->floorModel->find($zone['floor_sno']);
        $groups = $this->lockerGroupModel->where('zone_sno', $zone_sno)->findAll();

        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view'] = [
            'title' => '구역 상세',
            'zone' => $zone,
            'floor' => $floor,
            'groups' => $groups
        ];

        $this->viewPage('locker/zone_detail', $data);
    }

    // 락커 그룹 추가
    public function ajax_add_group()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            // 구역 정보 가져오기
            $zone_sno = $this->request->getPost('zone_sno');
            $zone = $this->zoneModel->find($zone_sno);
            
            if (empty($zone)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '구역 정보를 찾을 수 없습니다.']);
            }

            // 좌표값 처리
            $coords = $this->request->getPost('group_coords');
            if (empty($coords)) {
                // 기본 좌표값 설정 (빈 다각형)
                $coords = '[]';
            }

            $data = [
                'zone_sno' => $zone_sno,
                'group_nm' => $this->request->getPost('group_nm'),
                'group_rows' => $this->request->getPost('rows'),
                'group_cols' => $this->request->getPost('cols'),
                'locker_width' => $this->request->getPost('locker_width'),
                'locker_depth' => $this->request->getPost('locker_depth'),
                'locker_height' => $this->request->getPost('locker_height'),
                'locker_type' => $this->request->getPost('locker_type'),
                'total_count' => $this->request->getPost('total_count'),
                'group_coords' => $coords,
                'use_yn' => 'Y',
                'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'cre_datetm' => new \CodeIgniter\I18n\Time('now'),
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => new \CodeIgniter\I18n\Time('now')
            ];

            // 데이터 검증
            if (empty($data['group_nm'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => '그룹 이름을 입력해주세요.']);
            }

            if (!is_numeric($data['group_rows']) || $data['group_rows'] < 1 ||
                !is_numeric($data['group_cols']) || $data['group_cols'] < 1) {
                return $this->response->setJSON(['status' => 'error', 'message' => '행과 열은 1 이상의 숫자여야 합니다.']);
            }

            if (!is_numeric($data['locker_width']) || $data['locker_width'] < 1 ||
                !is_numeric($data['locker_depth']) || $data['locker_depth'] < 1 ||
                !is_numeric($data['locker_height']) || $data['locker_height'] < 1) {
                return $this->response->setJSON(['status' => 'error', 'message' => '락커 크기는 1 이상의 숫자여야 합니다.']);
            }

            if ($group_sno = $this->lockerModel->insert_group($data)) {
                // 개별 락커 자동 생성
                $this->createIndividualLockers($group_sno, $data);
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'group_sno' => $group_sno,
                    'message' => '락커 그룹이 추가되었습니다.'
                ]);
            } else {
                log_message('error', '[ajax_add_group] Insert failed: ' . print_r($this->lockerModel->errors(), true));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => '락커 그룹 추가에 실패했습니다.',
                    'errors' => $this->lockerModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', '[ajax_add_group] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    // 락커 그룹 수정
    public function ajax_update_group()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            // 디버깅: 받은 POST 데이터 로그 출력
            $allPostData = $this->request->getPost();
            log_message('info', '[ajax_update_group] Received POST data: ' . print_r($allPostData, true));

            $group_sno = $this->request->getPost('group_sno');
            log_message('info', '[ajax_update_group] Group SNO: ' . $group_sno);
            
            $group = $this->lockerGroupModel->find($group_sno);
            
            if (empty($group)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '락커 그룹 정보를 찾을 수 없습니다.']);
            }

            // 좌표값 처리 (기존 좌표 유지 또는 새로운 좌표)
            $coords = $this->request->getPost('group_coords');
            if (empty($coords)) {
                $coords = $group['group_coords'] ?: '[]';
            }

            $data = [
                'group_nm' => $this->request->getPost('group_nm'),
                'group_rows' => $this->request->getPost('rows'),        // DB 컬럼명에 맞춤
                'group_cols' => $this->request->getPost('cols'),        // DB 컬럼명에 맞춤
                'locker_width' => $this->request->getPost('locker_width'),
                'locker_depth' => $this->request->getPost('locker_depth'),
                'locker_height' => $this->request->getPost('locker_height'),
                'locker_type' => $this->request->getPost('locker_type'),
                'group_coords' => $coords,
                'total_count' => $this->request->getPost('total_count'),
                'use_yn' => 'Y',   // 기본값 추가
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => new \CodeIgniter\I18n\Time('now')
            ];

            // 디버깅: 처리된 데이터를 로그로 출력
            log_message('info', '[ajax_update_group] Processed data: ' . print_r($data, true));

            // 데이터 검증
            if (empty($data['group_nm'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => '그룹 이름을 입력해주세요.']);
            }

            if (!is_numeric($data['group_rows']) || $data['group_rows'] < 1 ||
                !is_numeric($data['group_cols']) || $data['group_cols'] < 1) {
                return $this->response->setJSON(['status' => 'error', 'message' => '행과 열은 1 이상의 숫자여야 합니다.']);
            }

            if (!is_numeric($data['locker_width']) || $data['locker_width'] < 1 ||
                !is_numeric($data['locker_depth']) || $data['locker_depth'] < 1 ||
                !is_numeric($data['locker_height']) || $data['locker_height'] < 1) {
                return $this->response->setJSON(['status' => 'error', 'message' => '락커 크기는 1 이상의 숫자여야 합니다.']);
            }

            // 락커 그룹 업데이트
            if ($this->lockerGroupModel->update($group_sno, $data)) {
                log_message('info', '[ajax_update_group] Updated group: ' . $group_sno);
                return $this->response->setJSON([
                    'status' => 'success',
                    'group_sno' => $group_sno,
                    'message' => '락커 그룹이 수정되었습니다.'
                ]);
            } else {
                log_message('error', '[ajax_update_group] Update failed: ' . print_r($this->lockerGroupModel->errors(), true));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => '락커 그룹 수정에 실패했습니다.',
                    'errors' => $this->lockerGroupModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', '[ajax_update_group] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    // 락커 그룹 삭제
    public function ajax_delete_group()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $group_sno = $this->request->getPost('group_sno');
            
            if (empty($group_sno)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '그룹 번호가 필요합니다.']);
            }

            // 락커 그룹 존재 확인
            $group = $this->lockerGroupModel->find($group_sno);
            if (empty($group)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '락커 그룹을 찾을 수 없습니다.']);
            }

            log_message('info', '[ajax_delete_group] Deleting group: ' . $group_sno);

            // Database 연결 확보
            $db = \Config\Database::connect();
            
            // 트랜잭션 시작
            $db->transStart();

            // 개별 락커들 삭제 (tb_locker 테이블에 해당 그룹의 락커가 있다면)
            try {
                $deleted_lockers = $db->table('tb_locker')
                    ->where('group_sno', $group_sno)
                    ->delete();
            } catch (\Exception $e) {
                log_message('info', '[ajax_delete_group] No individual lockers to delete or auto-deleted by CASCADE: ' . $e->getMessage());
            }

            // 락커 그룹 삭제
            $deleteResult = $this->lockerGroupModel->delete($group_sno);
            
            if ($deleteResult) {
                $db->transComplete();
                
                if ($db->transStatus() === FALSE) {
                    log_message('error', '[ajax_delete_group] Transaction failed for group: ' . $group_sno);
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => '락커 그룹 삭제 중 트랜잭션 오류가 발생했습니다.'
                    ]);
                }

                log_message('info', '[ajax_delete_group] Successfully deleted group: ' . $group_sno);
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => '락커 그룹이 삭제되었습니다.'
                ]);
            } else {
                $db->transRollback();
                log_message('error', '[ajax_delete_group] Failed to delete group: ' . $group_sno . ', Errors: ' . print_r($this->lockerGroupModel->errors(), true));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => '락커 그룹 삭제에 실패했습니다.',
                    'errors' => $this->lockerGroupModel->errors()
                ]);
            }

        } catch (\Exception $e) {
            if (isset($db)) {
                $db->transRollback();
            }
            log_message('error', '[ajax_delete_group] Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    // 락커 그룹 상세
    public function group_detail($group_sno)
    {
        $group = $this->lockerGroupModel->find($group_sno);
        if (!$group) {
            return redirect()->to('/locker/manage')->with('error', '락커 그룹을 찾을 수 없습니다.');
        }

        $zone = $this->zoneModel->find($group['zone_sno']);
        $floor = $this->floorModel->find($zone['floor_sno']);
        $lockers = $this->lockerModel->where('group_sno', $group_sno)->findAll();

        $data = [
            'title' => '락커 상세',
            'group' => $group,
            'zone' => $zone,
            'floor' => $floor,
            'lockers' => $lockers
        ];

        return view('locker/group_detail', $data);
    }

    // 구역 삭제
    public function ajax_delete_zone()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
        }

        $zone_sno = $this->request->getPost('zone_sno');
        
        // 구역 삭제 (연관된 락커 그룹도 함께 삭제됨 - 외래키 CASCADE 설정)
        if ($this->lockerModel->delete_zone($zone_sno)) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '구역 삭제에 실패했습니다.'
            ]);
        }
    }

    // 구역의 락커 그룹 존재 여부 확인
    public function ajax_check_zone_groups()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
        }

        $zone_sno = $this->request->getPost('zone_sno');
        $groups = $this->lockerModel->get_group_list($zone_sno);
        
        return $this->response->setJSON([
            'status' => 'success',
            'has_groups' => !empty($groups)
        ]);
    }

    // 임시 테스트용 메서드 - 데이터베이스 상태 확인
    public function test_db()
    {
        try {
            // 전체 락커 그룹 수 확인
            $db = \Config\Database::connect();
            
            $total_groups = $db->table('tb_locker_group')->countAllResults();
            
            $all_groups = $db->table('tb_locker_group')
                            ->select('zone_sno, group_sno, group_nm, use_yn')
                            ->get()
                            ->getResultArray();
            
            $zones = $db->table('tb_locker_zone')
                       ->select('zone_sno, zone_nm, floor_sno, use_yn')
                       ->get()
                       ->getResultArray();
            
            return $this->response->setJSON([
                'total_groups' => $total_groups,
                'all_groups' => $all_groups,
                'zones' => $zones,
                'get_group_list_test' => $this->lockerModel->get_group_list(1)
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 번호 저장
     */
    public function ajax_save_locker_numbers()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $group_sno = $this->request->getPost('group_sno');
            $lockers = json_decode($this->request->getPost('lockers'), true);

            if (empty($group_sno) || empty($lockers)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '필수 데이터가 누락되었습니다.']);
            }

            // 트랜잭션 시작
            $this->db->transStart();

            // 기존 락커 삭제 (해당 그룹의)
            $this->db->table('tb_locker')
                     ->where('group_sno', $group_sno)
                     ->delete();

            // 새 락커 데이터 준비
            $insertData = [];
            foreach ($lockers as $locker) {
                $insertData[] = [
                    'group_sno' => $group_sno,
                    'locker_no' => $locker['locker_no'],
                    'locker_row' => $locker['locker_row'],
                    'locker_col' => $locker['locker_col'],
                    'locker_floor' => $locker['locker_floor'],
                    'use_yn' => 'Y',
                    'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'cre_datetm' => new \CodeIgniter\I18n\Time('now'),
                    'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                    'mod_datetm' => new \CodeIgniter\I18n\Time('now')
                ];
            }

            // 새 락커 삽입
            if (!empty($insertData)) {
                $this->db->table('tb_locker')->insertBatch($insertData);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return $this->response->setJSON(['status' => 'error', 'message' => '락커 번호 저장에 실패했습니다.']);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => '락커 번호가 저장되었습니다.',
                'count' => count($insertData)
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ajax_save_locker_numbers] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * 락커 상태 업데이트
     */
    public function ajax_update_locker_status()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $locker_sno = $this->request->getPost('locker_sno');
            $status = $this->request->getPost('status');
            
            if (empty($locker_sno) || empty($status)) {
                return $this->response->setJSON(['status' => 'error', 'message' => '필수 데이터가 누락되었습니다.']);
            }

            // 업데이트 데이터 준비
            $updateData = [
                'locker_status' => $status,
                'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'mod_datetm' => new \CodeIgniter\I18n\Time('now')
            ];

            // 상태별 추가 데이터
            if ($status === 'occupied' || $status === 'reserved') {
                $updateData['assigned_user_id'] = $this->request->getPost('assigned_user_id');
                $updateData['assigned_date'] = new \CodeIgniter\I18n\Time('now');
                $updateData['expire_date'] = $this->request->getPost('expire_date');
            } else {
                $updateData['assigned_user_id'] = null;
                $updateData['assigned_date'] = null;
                $updateData['expire_date'] = null;
            }

            $updateData['notes'] = $this->request->getPost('notes');

            // 락커 상태 업데이트
            $this->db->table('tb_locker')
                     ->where('locker_sno', $locker_sno)
                     ->update($updateData);

            // 이력 추가
            if ($status === 'occupied') {
                $this->addLockerHistory($locker_sno, $updateData['assigned_user_id'], 'assign');
            } elseif ($status === 'available' && $this->request->getPost('previous_status') === 'occupied') {
                $this->addLockerHistory($locker_sno, $updateData['assigned_user_id'], 'release');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => '락커 상태가 업데이트되었습니다.'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ajax_update_locker_status] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 사용 이력 추가
     */
    private function addLockerHistory($locker_sno, $user_id, $action_type)
    {
        try {
            $data = [
                'locker_sno' => $locker_sno,
                'user_id' => $user_id,
                'action_type' => $action_type,
                'action_date' => new \CodeIgniter\I18n\Time('now'),
                'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                'cre_datetm' => new \CodeIgniter\I18n\Time('now')
            ];

            $this->db->table('tb_locker_history')->insert($data);
        } catch (\Exception $e) {
            log_message('error', '[addLockerHistory] Error: ' . $e->getMessage());
        }
    }

    /**
     * 개별 락커 자동 생성
     */
    private function createIndividualLockers($group_sno, $groupData)
    {
        try {
            $insertData = [];
            $horizontalCount = intval($groupData['group_cols']);
            $verticalCount = intval($groupData['group_rows']);
            
            // 각 층별로 락커 생성
            for ($floor = 1; $floor <= $verticalCount; $floor++) {
                for ($col = 1; $col <= $horizontalCount; $col++) {
                    $lockerNo = sprintf('%d-%d-%02d', $floor, 1, $col);
                    
                    $insertData[] = [
                        'group_sno' => $group_sno,
                        'locker_no' => $lockerNo,
                        'locker_row' => 1,
                        'locker_col' => $col,
                        'locker_floor' => $floor,
                        'use_yn' => 'Y',
                        'cre_id' => $this->SpoQCahce->getCacheVar('user_id'),
                        'cre_datetm' => new \CodeIgniter\I18n\Time('now'),
                        'mod_id' => $this->SpoQCahce->getCacheVar('user_id'),
                        'mod_datetm' => new \CodeIgniter\I18n\Time('now')
                    ];
                }
            }
            
            if (!empty($insertData)) {
                $this->db->table('tb_locker')->insertBatch($insertData);
                log_message('info', "[createIndividualLockers] Created {count($insertData)} lockers for group {$group_sno}");
            }
            
        } catch (\Exception $e) {
            log_message('error', '[createIndividualLockers] Error: ' . $e->getMessage());
        }
    }


    /**
     * 락커 타입 목록 조회 (Locker4 호환)
     */
    public function ajax_get_locker_types()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $comp_cd = $this->request->getGet('comp_cd') ?? '001';
            $bcoff_cd = $this->request->getGet('bcoff_cd') ?? '001';
            
            $db = \Config\Database::connect();
            $builder = $db->table('lockr_types');
            $builder->where('COMP_CD', $comp_cd);
            $builder->where('BCOFF_CD', $bcoff_cd);
            
            $result = $builder->get();
            $types = $result->getResultArray();
            
            // API 형식으로 변환
            $formattedTypes = [];
            foreach ($types as $type) {
                $formattedTypes[] = [
                    'LOCKR_TYPE_CD' => $type['LOCKR_TYPE_CD'],
                    'LOCKR_TYPE_NM' => $type['LOCKR_TYPE_NM'],
                    'WIDTH' => intval($type['WIDTH']),
                    'HEIGHT' => intval($type['HEIGHT']),
                    'DEPTH' => intval($type['DEPTH']),
                    'COLOR' => $type['COLOR']
                ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'types' => $formattedTypes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ajax_get_locker_types] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 목록 조회 (Locker4 API 호환)
     */
    public function ajax_get_all_lockers()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $comp_cd = $this->request->getGet('comp_cd') ?? $this->SpoQCahce->getCacheVar('comp_cd') ?? '001';
            $bcoff_cd = $this->request->getGet('bcoff_cd') ?? $this->SpoQCahce->getCacheVar('bcoff_cd') ?? '001';
            $zone_id = $this->request->getGet('zone_id');
            
            // Node.js API로 프록시 요청 (선택적)
            // $nodeApiUrl = 'http://localhost:3333/api/lockrs?COMP_CD=' . $comp_cd . '&BCOFF_CD=' . $bcoff_cd;
            
            // 임시 더미 데이터
            $lockers = [];
            
            // DB에서 락커 데이터 조회 (실제 구현 필요)
            // $db = \Config\Database::connect();
            // $query = $db->table('lockrs')
            //             ->where('COMP_CD', $comp_cd)
            //             ->where('BCOFF_CD', $bcoff_cd);
            // if ($zone_id) {
            //     $query->where('LOCKR_KND', $zone_id);
            // }
            // $lockers = $query->get()->getResultArray();
            
            // 임시 더미 데이터 생성
            if ($zone_id == 1) {
                $lockers = [
                    [
                        'LOCKR_CD' => 1,
                        'COMP_CD' => $comp_cd,
                        'BCOFF_CD' => $bcoff_cd,
                        'LOCKR_KND' => '1',
                        'LOCKR_TYPE_CD' => '1',
                        'X' => 100,
                        'Y' => 100,
                        'LOCKR_LABEL' => 'A-001',
                        'ROTATION' => 0,
                        'LOCKR_STAT' => '00'
                    ],
                    [
                        'LOCKR_CD' => 2,
                        'COMP_CD' => $comp_cd,
                        'BCOFF_CD' => $bcoff_cd,
                        'LOCKR_KND' => '1',
                        'LOCKR_TYPE_CD' => '2',
                        'X' => 200,
                        'Y' => 100,
                        'LOCKR_LABEL' => 'A-002',
                        'ROTATION' => 0,
                        'LOCKR_STAT' => '01'
                    ],
                    [
                        'LOCKR_CD' => 3,
                        'COMP_CD' => $comp_cd,
                        'BCOFF_CD' => $bcoff_cd,
                        'LOCKR_KND' => '1',
                        'LOCKR_TYPE_CD' => '1',
                        'X' => 300,
                        'Y' => 100,
                        'LOCKR_LABEL' => 'A-003',
                        'ROTATION' => 90,
                        'LOCKR_STAT' => '00'
                    ]
                ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'lockers' => $lockers,
                'count' => count($lockers)
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ajax_get_all_lockers] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * 락커 구역 목록 조회 (Locker4 호환)
     */
    public function ajax_get_locker_zones()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $comp_cd = $this->request->getGet('comp_cd') ?? '001';
            $bcoff_cd = $this->request->getGet('bcoff_cd') ?? '001';
            
            $db = \Config\Database::connect();
            $builder = $db->table('lockr_area');
            $builder->where('COMP_CD', $comp_cd);
            $builder->where('BCOFF_CD', $bcoff_cd);
            
            $result = $builder->get();
            $zones = $result->getResultArray();
            
            // API 형식으로 변환
            $formattedZones = [];
            foreach ($zones as $zone) {
                $formattedZones[] = [
                    'LOCKR_KND_CD' => $zone['LOCKR_KND_CD'],
                    'LOCKR_KND_NM' => $zone['LOCKR_KND_NM'],
                    'X' => intval($zone['X'] ?? 0),
                    'Y' => intval($zone['Y'] ?? 0),
                    'WIDTH' => intval($zone['WIDTH'] ?? 800),
                    'HEIGHT' => intval($zone['HEIGHT'] ?? 600),
                    'COLOR' => $zone['COLOR'] ?? '#e5e7eb'
                ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'zones' => $formattedZones
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ajax_get_locker_zones] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 목록 조회 (Locker4 호환 - DB에서 실제 데이터 조회)
     */
    public function ajax_get_lockers()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $comp_cd = $this->request->getGet('comp_cd') ?? '001';
            $bcoff_cd = $this->request->getGet('bcoff_cd') ?? '001';
            $zone_id = $this->request->getGet('zone_id');
            
            $db = \Config\Database::connect();
            $builder = $db->table('lockrs');
            $builder->where('COMP_CD', $comp_cd);
            $builder->where('BCOFF_CD', $bcoff_cd);
            
            if ($zone_id) {
                $builder->where('LOCKR_KND', $zone_id);
            }
            
            $builder->orderBy('LOCKR_CD', 'ASC');
            
            $result = $builder->get();
            $lockers = $result->getResultArray();
            
            return $this->response->setJSON([
                'status' => 'success',
                'lockers' => $lockers
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ajax_get_lockers] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 저장/업데이트 (Locker4 호환 - 실제 DB 저장)
     */
    public function ajax_save_locker()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $data = $this->request->getJSON(true);
            
            
            $db = \Config\Database::connect();
            $builder = $db->table('lockrs');
            
            $lockr_cd = $data['LOCKR_CD'] ?? null;
            
            $saveData = [
                'COMP_CD' => $data['COMP_CD'] ?? '001',
                'BCOFF_CD' => $data['BCOFF_CD'] ?? '001',
                'LOCKR_KND' => $data['LOCKR_KND'] ?? '',
                'LOCKR_TYPE_CD' => $data['LOCKR_TYPE_CD'] ?? '1',
                'X' => $data['X'] ?? 0,
                'Y' => $data['Y'] ?? 0,
                'LOCKR_LABEL' => $data['LOCKR_LABEL'] ?? '',
                'ROTATION' => $data['ROTATION'] ?? 0,
                'LOCKR_STAT' => $data['LOCKR_STAT'] ?? '00',
                'LOCKR_NO' => $data['LOCKR_NO'] ?? null,
                'UPDATE_DT' => date('Y-m-d H:i:s'),
                'UPDATE_BY' => 'system'
            ];
            
            if ($lockr_cd) {
                // 업데이트
                $builder->where('LOCKR_CD', $lockr_cd);
                $builder->update($saveData);
                $saveData['LOCKR_CD'] = $lockr_cd;
            } else {
                // 새로 삽입
                $builder->insert($saveData);
                $saveData['LOCKR_CD'] = $db->insertID();
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'locker' => $saveData
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ajax_save_locker] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 락커 타입 추가 (AJAX)
     */
    public function ajax_add_locker_type()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $data = $this->request->getJSON(true);
            
            
            $db = \Config\Database::connect();
            $builder = $db->table('lockr_types');
            
            // 새 타입 코드 생성 (자동 증가)
            $query = $builder->selectMax('LOCKR_TYPE_CD', 'max_cd')->where('COMP_CD', '001')->where('BCOFF_CD', '001')->get();
            $maxCode = $query->getRow()->max_cd ?? 0;
            $newTypeCode = intval($maxCode) + 1;
            
            $insertData = [
                'LOCKR_TYPE_CD' => strval($newTypeCode),
                'LOCKR_TYPE_NM' => $data['name'] ?? '새 타입',
                'WIDTH' => $data['width'] ?? 40,
                'HEIGHT' => $data['height'] ?? 40,
                'DEPTH' => $data['depth'] ?? 40,
                'COLOR' => $data['color'] ?? '#4A90E2',
                'COMP_CD' => '001',
                'BCOFF_CD' => '001',
                'REG_DT' => date('Y-m-d H:i:s'),
                'UPDATE_DT' => date('Y-m-d H:i:s')
            ];
            
            $builder->insert($insertData);
            
            return $this->response->setJSON([
                'status' => 'success',
                'type' => $insertData
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[ajax_add_locker_type] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 구역 추가 (Locker4 호환)
     */
    public function ajax_add_zone()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => '잘못된 접근입니다.']);
            }

            $data = $this->request->getJSON(true);
            
            
            $db = \Config\Database::connect();
            $builder = $db->table('lockr_area');
            
            // 새 구역 코드 생성
            $newZoneCode = 'zone-' . time();
            
            $insertData = [
                'LOCKR_KND_CD' => $newZoneCode,
                'LOCKR_KND_NM' => $data['zone_nm'] ?? '새 구역',
                'X' => 0,
                'Y' => 0,
                'WIDTH' => 800,
                'HEIGHT' => 600,
                'COLOR' => $data['color'] ?? '#e5e7eb',
                'COMP_CD' => '001',
                'BCOFF_CD' => '001',
                'FLOOR' => 1,
                'REG_DT' => date('Y-m-d H:i:s'),
                'UPDATE_DT' => date('Y-m-d H:i:s')
            ];
            
            $builder->insert($insertData);
            
            return $this->response->setJSON([
                'status' => 'success',
                'zone' => $insertData
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[ajax_add_zone] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

}
