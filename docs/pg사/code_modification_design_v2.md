# SpoqPlus 코드 수정 설계 문서 (개정판)

## 1. 개요
기존 테이블 구조를 활용한 최소한의 코드 수정으로 사업장별 PG 설정을 관리하는 설계 문서입니다.

## 2. Model 수정

### 2.1 BcoffModel 확장

기존 BcoffModel에 메소드 추가:

```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class BcoffModel extends Model
{
    // 기존 코드...
    
    /**
     * PG 설정 조회
     * @param string $comp_cd 회사코드
     * @param string $bcoff_cd 지점코드
     * @param string $pg_type PG유형 (mobile/card)
     * @param bool $is_dev 개발모드 여부
     * @return array|null
     */
    public function getPgSettings($comp_cd, $bcoff_cd, $pg_type = 'mobile', $is_dev = false)
    {
        $builder = $this->db->table('bcoff_mgmt_tbl');
        $result = $builder->where([
            'COMP_CD' => $comp_cd,
            'BCOFF_CD' => $bcoff_cd,
            'USE_YN' => 'Y'
        ])->get()->getRowArray();
        
        if (!$result || !$result['pg_settings']) {
            return null;
        }
        
        $settings = json_decode($result['pg_settings'], true);
        
        // 개발 모드
        if ($is_dev && isset($settings['dev'][$pg_type])) {
            return $settings['dev'][$pg_type];
        }
        
        // 운영 모드 (inicis 기본)
        if (isset($settings['inicis'][$pg_type])) {
            return $settings['inicis'][$pg_type];
        }
        
        return null;
    }
    
    /**
     * 계좌 정보 조회
     * @param string $comp_cd 회사코드
     * @param string $bcoff_cd 지점코드
     * @return array
     */
    public function getBankAccounts($comp_cd, $bcoff_cd)
    {
        $builder = $this->db->table('bcoff_mgmt_tbl');
        $result = $builder->where([
            'COMP_CD' => $comp_cd,
            'BCOFF_CD' => $bcoff_cd,
            'USE_YN' => 'Y'
        ])->get()->getRowArray();
        
        if (!$result || !$result['bank_accounts']) {
            return [];
        }
        
        $accounts = json_decode($result['bank_accounts'], true);
        
        // display_order로 정렬
        usort($accounts, function($a, $b) {
            return ($a['display_order'] ?? 999) - ($b['display_order'] ?? 999);
        });
        
        return $accounts;
    }
    
    /**
     * 기본 계좌 조회
     * @param string $comp_cd
     * @param string $bcoff_cd
     * @return array|null
     */
    public function getDefaultBankAccount($comp_cd, $bcoff_cd)
    {
        $accounts = $this->getBankAccounts($comp_cd, $bcoff_cd);
        
        foreach ($accounts as $account) {
            if ($account['is_default'] ?? false) {
                return $account;
            }
        }
        
        // 기본 계좌가 없으면 첫 번째 계좌 반환
        return $accounts[0] ?? null;
    }
}
```

## 3. Controller 수정

### 3.1 Api.php - mobile_pay() 메소드

```php
public function mobile_pay($pay_appno_sno = "")
{
    // 기존 코드...
    
    $model = new MobileTModel();
    $memModel = new MemModel();
    $bcoffModel = new BcoffModel(); // 추가
    
    // 기존 코드...
    
    // PG 설정 조회
    $is_dev = false; // 개발 모드 판단 (환경변수나 설정으로 관리)
    $pgSettings = $bcoffModel->getPgSettings(
        $_SESSION['comp_cd'], 
        $_SESSION['bcoff_cd'], 
        'mobile',
        $is_dev
    );
    
    if (!$pgSettings) {
        scriptAlert("PG 설정이 없습니다. 관리자에게 문의하세요.");
        echo "<script>history.back();</script>";
        return;
    }
    
    // 기존 하드코딩 제거하고 DB 값 사용
    $mid = $pgSettings['mid'];
    $signKey = $pgSettings['signkey'];
    
    // URL 설정
    if ($pgSettings['mode'] == 'dev') {
        $call_url = "https://tmobile.paywelcome.co.kr";
    } else {
        $call_url = "https://mobile.paywelcome.co.kr";
    }
    
    // 기존 코드 계속...
    
    $info['mid'] = $mid;
    $info['siteDomain'] = base_url();
    // ... 나머지 코드
}
```

### 3.2 Api.php - event_buy_info() 메소드

```php
public function event_buy_info($sell_event_sno='', $send_sno="")
{
    // 기존 코드...
    
    $bcoffModel = new BcoffModel(); // 추가
    
    // 계좌 정보 조회
    $bank_accounts = $bcoffModel->getBankAccounts(
        $_SESSION['comp_cd'],
        $_SESSION['bcoff_cd']
    );
    
    // 기존 코드...
    
    // View 데이터에 추가
    $data['view']['bank_accounts'] = $bank_accounts;
    $data['view']['get_use_locker_info'] = $get_use_locker_info;
    $data['view']['mem_info'] = $mem_info;
    $data['view']['event_info'] = $event_info;
    
    $this->viewPageForMobile('/mobile_p/new_event_buy_info', $data);
}
```

### 3.3 Api.php - ajax_pre_event_buy_proc() 메소드

```php
public function ajax_pre_event_buy_proc()
{
    // 기존 코드...
    
    $bcoffModel = new BcoffModel(); // 추가
    
    // PG 설정 확인 (옵션)
    $pgSettings = $bcoffModel->getPgSettings(
        $_SESSION['comp_cd'],
        $_SESSION['bcoff_cd'],
        'mobile'
    );
    
    if (!$pgSettings) {
        $return_json['msg'] = 'PG 설정이 없습니다.';
        $return_json['result'] = 'false';
        return json_encode($return_json);
    }
    
    // 기존 VAN 처리 로직...
    
    // paymt_mobile_tbl에 PG 정보 저장 (선택사항)
    if (isset($vdata)) {
        $vdata['PAY_PG_MID'] = $pgSettings['mid'];
        $vdata['PAY_PG_PROVIDER'] = 'inicis';
    }
    
    // 기존 코드 계속...
}
```

## 4. View 파일 수정

### 4.1 new_event_buy_info.php

```php
<!-- 계좌이체 섹션 수정 -->
<div class="input-group input-group-sm mb-1" style='display:none'>
    <span class="input-group-append">
        <span class="input-group-text" style='width:100px'>계좌이체</span>
    </span>
    <input type="text" class="text-right" style='width:100px; margin-left:5px' 
           placeholder="" name="acct_amt" id="acct_amt" value="" />
    <select class="select2 form-control" style="width: 260px;" 
            name="acct_no" id="acct_no">
        <option value="">계좌 선택</option>
        <?php if (isset($bank_accounts) && is_array($bank_accounts)) : ?>
            <?php foreach ($bank_accounts as $account) : ?>
                <option value="<?php echo htmlspecialchars($account['account_no'])?>" 
                    <?php echo (isset($account['is_default']) && $account['is_default']) ? 'selected' : ''?>>
                    <?php echo htmlspecialchars($account['bank_nm'])?> : 
                    <?php echo htmlspecialchars($account['account_no'])?>
                </option>
            <?php endforeach; ?>
        <?php else : ?>
            <!-- 기존 하드코딩 계좌 (fallback) -->
            <option>국민 : 12321-13547-125-25</option>
            <option>하나 : 8514-254-45387-11</option>
        <?php endif; ?>
    </select>
</div>
```

### 4.2 mobile_pay.php

변경 없음 (Controller에서 전달된 값 그대로 사용)

## 5. 헬퍼 함수 (선택사항)

공통으로 사용할 헬퍼 함수 추가:

```php
// app/Helpers/pg_helper.php

if (!function_exists('get_pg_mode')) {
    /**
     * 현재 PG 모드 확인 (개발/운영)
     */
    function get_pg_mode() {
        // 환경변수 또는 설정 파일에서 읽기
        return getenv('PG_MODE') ?: 'prod';
    }
}

if (!function_exists('mask_account_number')) {
    /**
     * 계좌번호 마스킹
     */
    function mask_account_number($account_no) {
        $length = strlen($account_no);
        if ($length <= 4) return $account_no;
        
        return substr($account_no, 0, 4) . str_repeat('*', $length - 8) . substr($account_no, -4);
    }
}
```

## 6. 설정 관리 화면 (관리자용)

관리자가 PG 설정을 관리할 수 있는 화면 추가 (선택사항):

```php
// AdminController.php
public function pg_settings()
{
    $bcoffModel = new BcoffModel();
    
    if ($this->request->getMethod() === 'post') {
        $postData = $this->request->getPost();
        
        // JSON 형식으로 저장
        $pgSettings = [
            'inicis' => [
                'mobile' => [
                    'mid' => $postData['pg_mid_mobile'],
                    'signkey' => $postData['pg_signkey_mobile'],
                    'mode' => 'prod'
                ]
            ]
        ];
        
        $bankAccounts = [];
        foreach ($postData['bank_nm'] as $idx => $bank_nm) {
            if (!empty($bank_nm)) {
                $bankAccounts[] = [
                    'bank_nm' => $bank_nm,
                    'account_no' => $postData['account_no'][$idx],
                    'is_default' => ($postData['default_account'] == $idx),
                    'display_order' => $idx + 1
                ];
            }
        }
        
        // DB 업데이트
        $this->db->table('bcoff_mgmt_tbl')->where([
            'COMP_CD' => $postData['comp_cd'],
            'BCOFF_CD' => $postData['bcoff_cd']
        ])->update([
            'pg_settings' => json_encode($pgSettings),
            'bank_accounts' => json_encode($bankAccounts),
            'MOD_ID' => $_SESSION['user_id'],
            'MOD_DATETM' => date('Y-m-d H:i:s')
        ]);
    }
    
    // View로 전달
}
```

## 7. 테스트 체크리스트

1. **기존 기능 테스트**
   - [ ] 잠실점 결제 정상 동작
   - [ ] 역삼점 결제 정상 동작
   - [ ] 개발 모드 전환 테스트

2. **신규 기능 테스트**
   - [ ] 신규 지점 PG 설정 추가
   - [ ] 계좌 정보 동적 표시
   - [ ] JSON 데이터 정합성

3. **오류 처리**
   - [ ] PG 설정 없는 경우
   - [ ] JSON 파싱 오류
   - [ ] 네트워크 오류

## 8. 배포 시 주의사항

1. **DB 백업**: bcoff_mgmt_tbl 반드시 백업
2. **단계별 배포**:
   - 1단계: DB 스키마 변경
   - 2단계: Model 파일 배포
   - 3단계: Controller/View 배포
3. **캐시 클리어**: CodeIgniter 캐시 삭제
4. **모니터링**: 결제 로그 실시간 모니터링