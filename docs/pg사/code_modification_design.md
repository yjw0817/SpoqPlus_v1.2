# SpoqPlus 코드 수정 설계 문서

## 1. 개요
사업장별 분리를 위한 애플리케이션 코드 수정 설계 문서입니다. 기존 디비를 사용하기로하여 이 문서 보다는 code_modification_design_v2참고

## 2. Model 클래스 설계

### 2.1 PgConfigModel.php

```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class PgConfigModel extends Model
{
    protected $table = 'pg_config_tbl';
    protected $primaryKey = 'config_sno';
    
    /**
     * 사업장별 PG 설정 조회
     * @param string $comp_cd 회사코드
     * @param string $bcoff_cd 지점코드
     * @param string $pg_type PG유형
     * @return array
     */
    public function getPgConfig($comp_cd, $bcoff_cd, $pg_type = 'mobile')
    {
        $builder = $this->db->table($this->table);
        
        // 먼저 해당 지점의 설정을 찾음
        $result = $builder->where([
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'pg_type' => $pg_type,
            'is_active' => 'Y'
        ])->get()->getRowArray();
        
        // 없으면 개발모드 설정을 찾음
        if (!$result) {
            $result = $builder->where([
                'comp_cd' => $comp_cd,
                'bcoff_cd' => 'B00000', // 공통 개발 코드
                'pg_type' => $pg_type,
                'pg_mode' => 'DEV',
                'is_active' => 'Y'
            ])->get()->getRowArray();
        }
        
        return $result;
    }
    
    /**
     * PG URL 정보 포함 전체 설정 조회
     */
    public function getPgConfigWithUrls($comp_cd, $bcoff_cd, $pg_type = 'mobile')
    {
        $config = $this->getPgConfig($comp_cd, $bcoff_cd, $pg_type);
        
        if ($config) {
            // 기본 URL 설정
            if ($config['pg_mode'] == 'DEV') {
                $config['base_url'] = 'https://tmobile.paywelcome.co.kr';
            } else {
                $config['base_url'] = 'https://mobile.paywelcome.co.kr';
            }
            
            // 사이트 도메인 설정
            $config['site_domain'] = base_url();
            
            // 콜백 URL 설정
            $config['next_url'] = $config['pg_return_url'] ?: base_url('nextUrl');
            $config['cancel_url'] = $config['pg_cancel_url'] ?: base_url('return');
            $config['noti_url'] = $config['pg_noti_url'] ?: base_url('noti');
        }
        
        return $config;
    }
    
    /**
     * PG 거래 로그 저장
     */
    public function saveTransactionLog($data)
    {
        $logData = [
            'comp_cd' => $data['comp_cd'],
            'bcoff_cd' => $data['bcoff_cd'],
            'pg_type' => $data['pg_type'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'order_id' => $data['order_id'],
            'amount' => $data['amount'],
            'status' => $data['status'],
            'request_data' => json_encode($data['request_data'] ?? []),
            'response_data' => json_encode($data['response_data'] ?? []),
            'error_message' => $data['error_message'] ?? null
        ];
        
        return $this->db->table('pg_transaction_log')->insert($logData);
    }
}
```

### 2.2 BankAccountModel.php

```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class BankAccountModel extends Model
{
    protected $table = 'bank_account_tbl';
    protected $primaryKey = 'account_sno';
    
    /**
     * 사업장별 활성 계좌 목록 조회
     * @param string $comp_cd 회사코드
     * @param string $bcoff_cd 지점코드
     * @return array
     */
    public function getActiveAccounts($comp_cd, $bcoff_cd)
    {
        return $this->where([
            'comp_cd' => $comp_cd,
            'bcoff_cd' => $bcoff_cd,
            'is_active' => 'Y'
        ])
        ->orderBy('display_order', 'ASC')
        ->orderBy('is_default', 'DESC')
        ->findAll();
    }
    
    /**
     * 계좌번호 마스킹 처리
     * @param string $account_no 계좌번호
     * @return string
     */
    public function maskAccountNumber($account_no)
    {
        $length = strlen($account_no);
        if ($length <= 4) {
            return $account_no;
        }
        
        $visible = 4; // 마지막 4자리만 표시
        $masked = str_repeat('*', $length - $visible);
        return $masked . substr($account_no, -$visible);
    }
    
    /**
     * Select 옵션용 계좌 목록 생성
     * @param string $comp_cd
     * @param string $bcoff_cd
     * @return array
     */
    public function getAccountOptions($comp_cd, $bcoff_cd)
    {
        $accounts = $this->getActiveAccounts($comp_cd, $bcoff_cd);
        $options = [];
        
        foreach ($accounts as $account) {
            $options[] = [
                'value' => $account['account_sno'],
                'text' => sprintf('%s : %s', 
                    $account['bank_nm'], 
                    $account['account_no']
                ),
                'is_default' => $account['is_default']
            ];
        }
        
        return $options;
    }
}
```

## 3. Controller 수정 설계

### 3.1 Api.php 수정

#### mobile_pay() 메소드 수정

```php
public function mobile_pay($pay_appno_sno = "")
{
    // 기존 코드...
    
    // PG 설정 조회로 변경
    $pgModel = new PgConfigModel();
    $pgConfig = $pgModel->getPgConfigWithUrls(
        $_SESSION['comp_cd'], 
        $_SESSION['bcoff_cd'], 
        'mobile'
    );
    
    if (!$pgConfig) {
        scriptAlert("PG 설정이 없습니다. 관리자에게 문의하세요.");
        echo "<script>history.back();</script>";
        return;
    }
    
    // 기존 하드코딩 제거
    // $sc_mid = ""; 
    // $sc_signkey = "";
    
    // DB에서 조회한 값 사용
    $mid = $pgConfig['pg_mid'];
    $signKey = $pgConfig['pg_signkey'];
    $call_url = $pgConfig['base_url'];
    
    // 거래 로그 시작
    $pgModel->saveTransactionLog([
        'comp_cd' => $_SESSION['comp_cd'],
        'bcoff_cd' => $_SESSION['bcoff_cd'],
        'pg_type' => 'mobile',
        'order_id' => $od_id,
        'amount' => $get_info[0]['PAY_PAYMT_AMT'],
        'status' => 'REQUEST',
        'request_data' => [
            'mid' => $mid,
            'order_id' => $od_id,
            'amount' => $get_info[0]['PAY_PAYMT_AMT']
        ]
    ]);
    
    // View에 전달할 데이터
    $info['mid'] = $mid;
    $info['signKey'] = $signKey;
    $info['siteDomain'] = $pgConfig['site_domain'];
    $info['next_url'] = $pgConfig['next_url'];
    $info['cancel_url'] = $pgConfig['cancel_url'];
    $info['noti_url'] = $pgConfig['noti_url'];
    
    // 기존 코드 계속...
}
```

### 3.2 ajax_pre_event_buy_proc() 수정

```php
public function ajax_pre_event_buy_proc()
{
    // 기존 코드...
    
    // 계좌 정보 조회 추가
    $bankModel = new BankAccountModel();
    $accounts = $bankModel->getActiveAccounts(
        $_SESSION['comp_cd'],
        $_SESSION['bcoff_cd']
    );
    
    // 기본 계좌 찾기
    $defaultAccount = null;
    foreach ($accounts as $account) {
        if ($account['is_default'] == 'Y') {
            $defaultAccount = $account;
            break;
        }
    }
    
    // 응답에 계좌 정보 포함
    $return_json['accounts'] = $accounts;
    $return_json['default_account'] = $defaultAccount;
    
    // 기존 코드 계속...
}
```

## 4. View 파일 수정 설계

### 4.1 new_event_buy_info.php 수정

```php
<!-- 계좌이체 섹션 동적 생성 -->
<div class="input-group input-group-sm mb-1" style='display:none'>
    <span class="input-group-append">
        <span class="input-group-text" style='width:100px'>계좌이체</span>
    </span>
    <input type="text" class="text-right" style='width:100px; margin-left:5px' 
           placeholder="" name="acct_amt" id="acct_amt" value="" />
    <select class="select2 form-control" style="width: 260px;" 
            name="acct_no" id="acct_no">
        <option value="">계좌 선택</option>
        <?php if (isset($bank_accounts)) : ?>
            <?php foreach ($bank_accounts as $account) : ?>
                <option value="<?php echo $account['account_sno']?>" 
                    <?php echo ($account['is_default'] == 'Y') ? 'selected' : ''?>>
                    <?php echo $account['bank_nm']?> : <?php echo $account['account_no']?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
```

### 4.2 mobile_pay.php 수정

```php
<!-- PG 정보 동적 설정 -->
<form name="payForm" method="post" accept-charset="euc-kr">
    <!-- 기존 하드코딩된 값 제거 -->
    <input type="hidden" name="P_MID" id="P_MID" 
           value="<?php echo $pg_config['pg_mid'] ?>" />
    
    <!-- PG URL 동적 설정 -->
    <input type="hidden" name="P_NEXT_URL" 
           value="<?php echo $pg_config['next_url'] ?>"/>
    <input type="hidden" name="P_RETURN_URL" 
           value="<?php echo $pg_config['cancel_url'] ?>"/>
    <input type="hidden" name="P_NOTI_URL" 
           value="<?php echo $pg_config['noti_url'] ?>"/>
           
    <!-- 기존 코드 계속... -->
</form>

<script>
// PG 초기화 시 동적 설정 사용
var pgConfig = {
    mid: '<?php echo $pg_config['pg_mid'] ?>',
    mode: '<?php echo $pg_config['pg_mode'] ?>',
    provider: '<?php echo $pg_config['pg_provider'] ?>'
};
</script>
```

## 5. JavaScript 수정

### 5.1 결제 프로세스 개선

```javascript
// 계좌 선택 동적 처리
$('#btn_pay_confirm').click(function() {
    // 기존 검증 로직...
    
    // 계좌번호 처리 개선
    var selectedAccount = $('#acct_no option:selected');
    if (selectedAccount.val()) {
        $('#pay_acct_no').val(selectedAccount.val());
        $('#pay_acct_info').val(selectedAccount.text()); // 표시용
    }
    
    // AJAX 요청에 사업장 정보 포함
    var params = $("#form_payment_submit").serialize();
    params += '&req_comp_cd=' + '<?php echo $_SESSION['comp_cd']?>';
    params += '&req_bcoff_cd=' + '<?php echo $_SESSION['bcoff_cd']?>';
    
    // 기존 AJAX 처리...
});
```

## 6. 테스트 시나리오

### 6.1 단위 테스트
1. PG 설정 조회 테스트
2. 계좌 정보 조회 테스트
3. 거래 로그 저장 테스트

### 6.2 통합 테스트
1. 신규 사업장 추가 시나리오
2. 기존 사업장 결제 프로세스
3. 개발/운영 모드 전환
4. 오류 상황 처리

### 6.3 회귀 테스트
1. 기존 결제 프로세스 정상 동작
2. 사업장 전환 기능
3. 관리자 설정 기능

## 7. 배포 전략

### 7.1 단계별 배포
1. **Phase 1**: DB 스키마 생성 및 데이터 마이그레이션
2. **Phase 2**: Model 클래스 배포
3. **Phase 3**: Controller 수정 배포
4. **Phase 4**: View 파일 배포

### 7.2 롤백 계획
- 각 Phase별 롤백 스크립트 준비
- DB 백업 및 복원 절차
- 코드 버전 관리

## 8. 모니터링 및 로깅

### 8.1 로그 수집
- PG 거래 로그 모니터링
- 오류 발생 시 알림
- 성능 메트릭 수집

### 8.2 대시보드
- 사업장별 거래 현황
- 실시간 오류 모니터링
- 일별/월별 통계