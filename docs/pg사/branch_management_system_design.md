# 지점 관리 및 결제 설정 시스템 설계서

## 1. 개요
회사 관리자의 지점 신청부터 PG/VAN 설정까지의 전체 프로세스를 관리하는 시스템 설계서입니다.

## 2. 프로세스 플로우

```
[지점 신청] → [본사 승인] → [기본 정보 설정] → [PG/VAN 설정] → [계좌 설정] → [운영 시작]
```

## 3. 데이터베이스 설계

### 3.1 지점 신청 테이블
```sql
CREATE TABLE bcoff_request_tbl (
    request_sno INT AUTO_INCREMENT PRIMARY KEY COMMENT '신청번호',
    comp_cd VARCHAR(10) NOT NULL COMMENT '회사코드',
    req_bcoff_nm VARCHAR(100) NOT NULL COMMENT '신청지점명',
    req_bcoff_addr VARCHAR(500) COMMENT '지점주소',
    req_bcoff_tel VARCHAR(20) COMMENT '지점전화번호',
    req_manager_nm VARCHAR(50) COMMENT '지점장명',
    req_manager_tel VARCHAR(20) COMMENT '지점장연락처',
    req_manager_email VARCHAR(100) COMMENT '지점장이메일',
    business_no VARCHAR(20) COMMENT '사업자번호',
    business_nm VARCHAR(100) COMMENT '사업자명',
    req_status VARCHAR(2) DEFAULT '00' COMMENT '신청상태(00:신청,10:검토중,20:승인,90:반려)',
    reject_reason TEXT COMMENT '반려사유',
    approved_bcoff_cd VARCHAR(10) COMMENT '승인된지점코드',
    req_date DATE COMMENT '신청일',
    review_date DATE COMMENT '검토일',
    approve_date DATE COMMENT '승인일',
    cre_id VARCHAR(30) COMMENT '신청자ID',
    cre_datetm DATETIME DEFAULT CURRENT_TIMESTAMP,
    mod_id VARCHAR(30),
    mod_datetm DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (req_status),
    INDEX idx_comp (comp_cd)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='지점신청관리';
```

### 3.2 지점 설정 상태 테이블
```sql
CREATE TABLE bcoff_setup_status_tbl (
    bcoff_cd VARCHAR(10) PRIMARY KEY COMMENT '지점코드',
    basic_info_yn CHAR(1) DEFAULT 'N' COMMENT '기본정보설정여부',
    pg_setup_yn CHAR(1) DEFAULT 'N' COMMENT 'PG설정여부',
    van_setup_yn CHAR(1) DEFAULT 'N' COMMENT 'VAN설정여부',
    bank_setup_yn CHAR(1) DEFAULT 'N' COMMENT '계좌설정여부',
    staff_setup_yn CHAR(1) DEFAULT 'N' COMMENT '직원설정여부',
    ready_yn CHAR(1) DEFAULT 'N' COMMENT '운영준비완료여부',
    setup_progress INT DEFAULT 0 COMMENT '설정진행률(%)',
    last_setup_date DATETIME COMMENT '최종설정일시',
    INDEX idx_ready (ready_yn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='지점설정상태';
```

## 4. 화면 설계

### 4.1 지점 신청 화면 (회사 관리자용)

```html
<!-- views/admin/branch_request.php -->
<div class="card">
    <div class="card-header">
        <h3>신규 지점 신청</h3>
    </div>
    <div class="card-body">
        <form id="branchRequestForm">
            <!-- 기본 정보 -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>지점명 <span class="required">*</span></label>
                        <input type="text" class="form-control" name="req_bcoff_nm" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>사업자번호</label>
                        <input type="text" class="form-control" name="business_no" 
                               placeholder="000-00-00000">
                    </div>
                </div>
            </div>
            
            <!-- 주소 정보 -->
            <div class="form-group">
                <label>지점 주소 <span class="required">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" name="req_bcoff_addr" 
                           id="branch_addr" readonly required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" 
                                onclick="searchAddress()">주소검색</button>
                    </div>
                </div>
            </div>
            
            <!-- 지점장 정보 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>지점장명 <span class="required">*</span></label>
                        <input type="text" class="form-control" name="req_manager_nm" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>지점장 연락처 <span class="required">*</span></label>
                        <input type="text" class="form-control" name="req_manager_tel" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>지점장 이메일</label>
                        <input type="email" class="form-control" name="req_manager_email">
                    </div>
                </div>
            </div>
            
            <div class="text-right">
                <button type="submit" class="btn btn-primary">신청하기</button>
            </div>
        </form>
    </div>
</div>
```

### 4.2 본사 승인 화면 (본사 관리자용)

```html
<!-- views/headquarter/branch_approval.php -->
<div class="card">
    <div class="card-header">
        <h3>지점 신청 승인 관리</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>신청번호</th>
                    <th>회사명</th>
                    <th>지점명</th>
                    <th>지점장</th>
                    <th>신청일</th>
                    <th>상태</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($requests as $req): ?>
                <tr>
                    <td><?= $req['request_sno'] ?></td>
                    <td><?= $req['comp_nm'] ?></td>
                    <td><?= $req['req_bcoff_nm'] ?></td>
                    <td><?= $req['req_manager_nm'] ?></td>
                    <td><?= $req['req_date'] ?></td>
                    <td>
                        <?php if($req['req_status'] == '00'): ?>
                            <span class="badge badge-warning">신청</span>
                        <?php elseif($req['req_status'] == '20'): ?>
                            <span class="badge badge-success">승인</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" 
                                onclick="viewDetail(<?= $req['request_sno'] ?>)">상세</button>
                        <?php if($req['req_status'] == '00'): ?>
                        <button class="btn btn-sm btn-success" 
                                onclick="approveRequest(<?= $req['request_sno'] ?>)">승인</button>
                        <button class="btn btn-sm btn-danger" 
                                onclick="rejectRequest(<?= $req['request_sno'] ?>)">반려</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 승인 모달 -->
<div class="modal fade" id="approveModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">지점 승인</h4>
            </div>
            <div class="modal-body">
                <form id="approveForm">
                    <input type="hidden" name="request_sno" id="approve_request_sno">
                    <div class="form-group">
                        <label>지점코드 <span class="required">*</span></label>
                        <input type="text" class="form-control" name="bcoff_cd" 
                               placeholder="B00003" required>
                        <small class="text-muted">* 영문대문자 B + 숫자 5자리</small>
                    </div>
                    <div class="form-group">
                        <label>승인 메모</label>
                        <textarea class="form-control" name="approve_memo" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                <button type="button" class="btn btn-success" onclick="submitApprove()">승인</button>
            </div>
        </div>
    </div>
</div>
```

### 4.3 지점 설정 대시보드 (승인 후)

```html
<!-- views/admin/branch_setup_dashboard.php -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3><?= $bcoff_nm ?> 설정 현황</h3>
                <div class="card-tools">
                    <span class="badge badge-primary">설정 진행률: <?= $setup_progress ?>%</span>
                </div>
            </div>
            <div class="card-body">
                <!-- 설정 진행 상태 -->
                <div class="progress mb-4">
                    <div class="progress-bar" style="width: <?= $setup_progress ?>%"></div>
                </div>
                
                <!-- 설정 단계 -->
                <div class="row">
                    <!-- 기본 정보 -->
                    <div class="col-md-2">
                        <div class="info-box <?= $basic_info_yn == 'Y' ? 'bg-success' : 'bg-gray' ?>">
                            <span class="info-box-icon">
                                <i class="fas fa-building"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">기본정보</span>
                                <span class="info-box-number">
                                    <?= $basic_info_yn == 'Y' ? '완료' : '미설정' ?>
                                </span>
                                <?php if($basic_info_yn == 'N'): ?>
                                <a href="/admin/branch/basic_info/<?= $bcoff_cd ?>" 
                                   class="btn btn-sm btn-primary mt-2">설정하기</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- PG 설정 -->
                    <div class="col-md-2">
                        <div class="info-box <?= $pg_setup_yn == 'Y' ? 'bg-success' : 'bg-gray' ?>">
                            <span class="info-box-icon">
                                <i class="fas fa-credit-card"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">PG 설정</span>
                                <span class="info-box-number">
                                    <?= $pg_setup_yn == 'Y' ? '완료' : '미설정' ?>
                                </span>
                                <?php if($pg_setup_yn == 'N'): ?>
                                <a href="/admin/branch/pg_setup/<?= $bcoff_cd ?>" 
                                   class="btn btn-sm btn-primary mt-2">설정하기</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- VAN 설정 -->
                    <div class="col-md-2">
                        <div class="info-box <?= $van_setup_yn == 'Y' ? 'bg-success' : 'bg-gray' ?>">
                            <span class="info-box-icon">
                                <i class="fas fa-cash-register"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">VAN 설정</span>
                                <span class="info-box-number">
                                    <?= $van_setup_yn == 'Y' ? '완료' : '미설정' ?>
                                </span>
                                <?php if($van_setup_yn == 'N'): ?>
                                <a href="/admin/branch/van_setup/<?= $bcoff_cd ?>" 
                                   class="btn btn-sm btn-primary mt-2">설정하기</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 계좌 설정 -->
                    <div class="col-md-2">
                        <div class="info-box <?= $bank_setup_yn == 'Y' ? 'bg-success' : 'bg-gray' ?>">
                            <span class="info-box-icon">
                                <i class="fas fa-university"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">계좌 설정</span>
                                <span class="info-box-number">
                                    <?= $bank_setup_yn == 'Y' ? '완료' : '미설정' ?>
                                </span>
                                <?php if($bank_setup_yn == 'N'): ?>
                                <a href="/admin/branch/bank_setup/<?= $bcoff_cd ?>" 
                                   class="btn btn-sm btn-primary mt-2">설정하기</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 직원 설정 -->
                    <div class="col-md-2">
                        <div class="info-box <?= $staff_setup_yn == 'Y' ? 'bg-success' : 'bg-gray' ?>">
                            <span class="info-box-icon">
                                <i class="fas fa-users"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">직원 설정</span>
                                <span class="info-box-number">
                                    <?= $staff_setup_yn == 'Y' ? '완료' : '미설정' ?>
                                </span>
                                <?php if($staff_setup_yn == 'N'): ?>
                                <a href="/admin/branch/staff_setup/<?= $bcoff_cd ?>" 
                                   class="btn btn-sm btn-primary mt-2">설정하기</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 운영 시작 -->
                    <div class="col-md-2">
                        <div class="info-box <?= $ready_yn == 'Y' ? 'bg-info' : 'bg-gray' ?>">
                            <span class="info-box-icon">
                                <i class="fas fa-rocket"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">운영 시작</span>
                                <span class="info-box-number">
                                    <?= $ready_yn == 'Y' ? '운영중' : '준비중' ?>
                                </span>
                                <?php if($setup_progress == 100 && $ready_yn == 'N'): ?>
                                <button class="btn btn-sm btn-info mt-2" 
                                        onclick="startOperation()">운영 시작</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 4.4 PG 설정 화면

```html
<!-- views/admin/pg_setup.php -->
<div class="card">
    <div class="card-header">
        <h3>PG사 설정 - <?= $bcoff_nm ?></h3>
    </div>
    <div class="card-body">
        <form id="pgSetupForm">
            <input type="hidden" name="bcoff_cd" value="<?= $bcoff_cd ?>">
            
            <!-- PG사 선택 -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5>사용할 PG사 선택</h5>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input pg-vendor-check" type="checkbox" 
                               id="pg_inicis" value="inicis">
                        <label class="form-check-label" for="pg_inicis">
                            <img src="/assets/img/pg/inicis.png" height="30"> 이니시스
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input pg-vendor-check" type="checkbox" 
                               id="pg_kcp" value="kcp">
                        <label class="form-check-label" for="pg_kcp">
                            <img src="/assets/img/pg/kcp.png" height="30"> NHN KCP
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input pg-vendor-check" type="checkbox" 
                               id="pg_toss" value="toss">
                        <label class="form-check-label" for="pg_toss">
                            <img src="/assets/img/pg/toss.png" height="30"> 토스페이먼츠
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input pg-vendor-check" type="checkbox" 
                               id="pg_nice" value="nice">
                        <label class="form-check-label" for="pg_nice">
                            <img src="/assets/img/pg/nice.png" height="30"> 나이스페이
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- 이니시스 설정 -->
            <div class="pg-config-section" id="inicis_config" style="display:none;">
                <div class="card card-primary">
                    <div class="card-header">
                        <h5>이니시스 설정</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>모바일 결제</h6>
                                <div class="form-group">
                                    <label>상점 ID (MID)</label>
                                    <input type="text" class="form-control" 
                                           name="inicis_mobile_mid" 
                                           placeholder="INIpayTest">
                                </div>
                                <div class="form-group">
                                    <label>Sign Key</label>
                                    <input type="text" class="form-control" 
                                           name="inicis_mobile_signkey">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>카드 결제</h6>
                                <div class="form-group">
                                    <label>상점 ID (MID)</label>
                                    <input type="text" class="form-control" 
                                           name="inicis_card_mid">
                                </div>
                                <div class="form-group">
                                    <label>Sign Key</label>
                                    <input type="text" class="form-control" 
                                           name="inicis_card_signkey">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- KCP 설정 -->
            <div class="pg-config-section" id="kcp_config" style="display:none;">
                <div class="card card-warning">
                    <div class="card-header">
                        <h5>KCP 설정</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>사이트 코드</label>
                                    <input type="text" class="form-control" 
                                           name="kcp_site_cd" 
                                           placeholder="T0000">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>사이트 키</label>
                                    <input type="text" class="form-control" 
                                           name="kcp_site_key">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 기본 PG 설정 -->
            <div class="form-group">
                <label>기본 PG사</label>
                <select class="form-control" name="default_pg" id="default_pg">
                    <option value="">선택하세요</option>
                </select>
            </div>
            
            <div class="text-right">
                <button type="button" class="btn btn-secondary" onclick="history.back()">취소</button>
                <button type="submit" class="btn btn-primary">저장</button>
            </div>
        </form>
    </div>
</div>

<script>
// PG사 선택 시 설정 영역 표시
$('.pg-vendor-check').change(function() {
    var vendor = $(this).val();
    if ($(this).is(':checked')) {
        $('#' + vendor + '_config').show();
        $('#default_pg').append('<option value="' + vendor + '">' + 
                                $(this).next('label').text().trim() + '</option>');
    } else {
        $('#' + vendor + '_config').hide();
        $('#default_pg option[value="' + vendor + '"]').remove();
    }
});

// 폼 제출
$('#pgSetupForm').submit(function(e) {
    e.preventDefault();
    
    var pgSettings = {
        pg: {},
        default: {
            pg: $('#default_pg').val(),
            pg_type: 'mobile'
        }
    };
    
    // 각 PG사별 설정 수집
    $('.pg-vendor-check:checked').each(function() {
        var vendor = $(this).val();
        
        if (vendor == 'inicis') {
            pgSettings.pg.inicis = {
                mobile: {
                    mid: $('[name="inicis_mobile_mid"]').val(),
                    signkey: $('[name="inicis_mobile_signkey"]').val(),
                    enabled: true
                },
                card: {
                    mid: $('[name="inicis_card_mid"]').val(),
                    signkey: $('[name="inicis_card_signkey"]').val(),
                    enabled: true
                }
            };
        } else if (vendor == 'kcp') {
            pgSettings.pg.kcp = {
                mobile: {
                    site_cd: $('[name="kcp_site_cd"]').val(),
                    site_key: $('[name="kcp_site_key"]').val(),
                    enabled: true
                }
            };
        }
        // ... 다른 PG사들도 동일하게 처리
    });
    
    // AJAX로 저장
    $.ajax({
        url: '/admin/branch/save_pg_settings',
        method: 'POST',
        data: {
            bcoff_cd: $('[name="bcoff_cd"]').val(),
            pg_settings: JSON.stringify(pgSettings)
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('성공', 'PG 설정이 저장되었습니다.', 'success')
                    .then(() => {
                        location.href = '/admin/branch/setup_dashboard/' + 
                                       $('[name="bcoff_cd"]').val();
                    });
            }
        }
    });
});
</script>
```

### 4.5 VAN 설정 화면

```html
<!-- views/admin/van_setup.php -->
<div class="card">
    <div class="card-header">
        <h3>VAN사 설정 - <?= $bcoff_nm ?></h3>
    </div>
    <div class="card-body">
        <form id="vanSetupForm">
            <input type="hidden" name="bcoff_cd" value="<?= $bcoff_cd ?>">
            
            <!-- VAN사 선택 -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5>사용할 VAN사 선택</h5>
                    <div class="form-check">
                        <input class="form-check-input van-vendor-check" type="checkbox" 
                               id="van_kicc" value="kicc">
                        <label class="form-check-label" for="van_kicc">KICC</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input van-vendor-check" type="checkbox" 
                               id="van_nice" value="nice">
                        <label class="form-check-label" for="van_nice">NICE</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input van-vendor-check" type="checkbox" 
                               id="van_ksnet" value="ksnet">
                        <label class="form-check-label" for="van_ksnet">KSNET</label>
                    </div>
                </div>
            </div>
            
            <!-- KICC 설정 -->
            <div class="van-config-section" id="kicc_config" style="display:none;">
                <div class="card card-info">
                    <div class="card-header">
                        <h5>KICC 설정</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>단말기 ID</label>
                                    <input type="text" class="form-control" 
                                           name="kicc_terminal_id" 
                                           placeholder="T0001234">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>가맹점 번호</label>
                                    <input type="text" class="form-control" 
                                           name="kicc_merchant_no">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>API Key</label>
                            <input type="text" class="form-control" name="kicc_api_key">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 기본 VAN 설정 -->
            <div class="form-group">
                <label>기본 VAN사</label>
                <select class="form-control" name="default_van" id="default_van">
                    <option value="">선택하세요</option>
                </select>
            </div>
            
            <div class="text-right">
                <button type="button" class="btn btn-secondary" onclick="history.back()">취소</button>
                <button type="submit" class="btn btn-primary">저장</button>
            </div>
        </form>
    </div>
</div>
```

## 5. Controller 구현

### 5.1 BranchManageController.php

```php
<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BranchManageController extends BaseController
{
    /**
     * 지점 신청
     */
    public function request()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            $data['comp_cd'] = $_SESSION['comp_cd'];
            $data['req_status'] = '00';
            $data['req_date'] = date('Y-m-d');
            $data['cre_id'] = $_SESSION['user_id'];
            
            $this->db->table('bcoff_request_tbl')->insert($data);
            
            return redirect()->to('/admin/branch/request_list')
                           ->with('message', '지점 신청이 완료되었습니다.');
        }
        
        return view('admin/branch_request');
    }
    
    /**
     * 지점 승인 (본사)
     */
    public function approve()
    {
        $request_sno = $this->request->getPost('request_sno');
        $bcoff_cd = $this->request->getPost('bcoff_cd');
        
        $this->db->transStart();
        
        // 1. 신청 정보 조회
        $request = $this->db->table('bcoff_request_tbl')
                           ->where('request_sno', $request_sno)
                           ->get()->getRowArray();
        
        // 2. bcoff_mgmt_tbl에 신규 지점 생성
        $bcoffData = [
            'COMP_CD' => $request['comp_cd'],
            'BCOFF_CD' => $bcoff_cd,
            'BCOFF_NM' => $request['req_bcoff_nm'],
            'BCOFF_ADDR' => $request['req_bcoff_addr'],
            'BCOFF_TEL' => $request['req_bcoff_tel'],
            'USE_YN' => 'N', // 설정 완료 전까지는 미사용
            'CRE_ID' => $_SESSION['user_id'],
            'CRE_DATETM' => date('Y-m-d H:i:s')
        ];
        
        $this->db->table('bcoff_mgmt_tbl')->insert($bcoffData);
        
        // 3. 설정 상태 테이블 생성
        $this->db->table('bcoff_setup_status_tbl')->insert([
            'bcoff_cd' => $bcoff_cd
        ]);
        
        // 4. 신청 상태 업데이트
        $this->db->table('bcoff_request_tbl')
                ->where('request_sno', $request_sno)
                ->update([
                    'req_status' => '20',
                    'approved_bcoff_cd' => $bcoff_cd,
                    'approve_date' => date('Y-m-d'),
                    'mod_id' => $_SESSION['user_id']
                ]);
        
        $this->db->transComplete();
        
        // 5. 지점 관리자에게 이메일 발송
        $this->sendApprovalEmail($request['req_manager_email'], $bcoff_cd);
        
        return $this->response->setJSON(['success' => true]);
    }
    
    /**
     * 설정 대시보드
     */
    public function setupDashboard($bcoff_cd)
    {
        $data['bcoff'] = $this->db->table('bcoff_mgmt_tbl')
                                  ->where('BCOFF_CD', $bcoff_cd)
                                  ->get()->getRowArray();
        
        $data['setup_status'] = $this->db->table('bcoff_setup_status_tbl')
                                        ->where('bcoff_cd', $bcoff_cd)
                                        ->get()->getRowArray();
        
        // 설정 진행률 계산
        $completed = 0;
        $total = 5;
        
        if ($data['setup_status']['basic_info_yn'] == 'Y') $completed++;
        if ($data['setup_status']['pg_setup_yn'] == 'Y') $completed++;
        if ($data['setup_status']['van_setup_yn'] == 'Y') $completed++;
        if ($data['setup_status']['bank_setup_yn'] == 'Y') $completed++;
        if ($data['setup_status']['staff_setup_yn'] == 'Y') $completed++;
        
        $data['setup_progress'] = round(($completed / $total) * 100);
        
        // 진행률 업데이트
        $this->db->table('bcoff_setup_status_tbl')
                ->where('bcoff_cd', $bcoff_cd)
                ->update(['setup_progress' => $data['setup_progress']]);
        
        return view('admin/branch_setup_dashboard', $data);
    }
    
    /**
     * PG 설정 저장
     */
    public function savePgSettings()
    {
        $bcoff_cd = $this->request->getPost('bcoff_cd');
        $pg_settings = $this->request->getPost('pg_settings');
        
        // bcoff_mgmt_tbl 업데이트
        $this->db->table('bcoff_mgmt_tbl')
                ->where('BCOFF_CD', $bcoff_cd)
                ->update([
                    'payment_settings' => $pg_settings,
                    'MOD_ID' => $_SESSION['user_id'],
                    'MOD_DATETM' => date('Y-m-d H:i:s')
                ]);
        
        // 설정 상태 업데이트
        $this->db->table('bcoff_setup_status_tbl')
                ->where('bcoff_cd', $bcoff_cd)
                ->update([
                    'pg_setup_yn' => 'Y',
                    'last_setup_date' => date('Y-m-d H:i:s')
                ]);
        
        return $this->response->setJSON(['success' => true]);
    }
    
    /**
     * 운영 시작
     */
    public function startOperation()
    {
        $bcoff_cd = $this->request->getPost('bcoff_cd');
        
        // 모든 설정이 완료되었는지 확인
        $status = $this->db->table('bcoff_setup_status_tbl')
                          ->where('bcoff_cd', $bcoff_cd)
                          ->get()->getRowArray();
        
        if ($status['setup_progress'] != 100) {
            return $this->response->setJSON([
                'success' => false,
                'message' => '모든 설정을 완료해주세요.'
            ]);
        }
        
        // 지점 활성화
        $this->db->table('bcoff_mgmt_tbl')
                ->where('BCOFF_CD', $bcoff_cd)
                ->update(['USE_YN' => 'Y']);
        
        $this->db->table('bcoff_setup_status_tbl')
                ->where('bcoff_cd', $bcoff_cd)
                ->update(['ready_yn' => 'Y']);
        
        return $this->response->setJSON(['success' => true]);
    }
}
```

## 6. 권한 관리

### 6.1 권한 레벨
1. **본사 관리자**: 지점 승인/반려
2. **회사 관리자**: 지점 신청, 설정 관리
3. **지점 관리자**: 자기 지점 설정 수정
4. **일반 직원**: 설정 조회만 가능

### 6.2 권한 체크
```php
// Middleware/BranchAuthMiddleware.php
public function before(RequestInterface $request, $arguments = null)
{
    $user_role = $_SESSION['user_role'];
    $bcoff_cd = $request->getUri()->getSegment(4);
    
    // 본사 관리자는 모든 지점 접근 가능
    if ($user_role == 'HQ_ADMIN') {
        return;
    }
    
    // 회사/지점 관리자는 자기 지점만 접근 가능
    if ($bcoff_cd != $_SESSION['bcoff_cd']) {
        return redirect()->to('/admin/dashboard')
                       ->with('error', '권한이 없습니다.');
    }
}
```

## 7. 장점

1. **체계적인 프로세스**: 신청→승인→설정→운영의 명확한 단계
2. **진행 상황 추적**: 설정 진행률 실시간 확인
3. **유연한 설정**: 각 지점별 독립적인 PG/VAN 조합
4. **권한 관리**: 역할별 접근 권한 제어
5. **사용자 친화적**: 직관적인 UI/UX