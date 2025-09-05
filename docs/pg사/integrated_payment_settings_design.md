# 통합 결제 설정 설계서

## 1. 개요
각 지점별로 여러 PG사와 VAN사를 독립적으로 관리할 수 있는 통합 결제 설정 시스템 설계서입니다.

## 2. DB 스키마 설계

### 2.1 테이블 수정
```sql
-- bcoff_mgmt_tbl 수정
ALTER TABLE bcoff_mgmt_tbl
ADD COLUMN payment_settings JSON COMMENT '통합결제설정' AFTER api_key,
ADD COLUMN bank_accounts JSON COMMENT '계좌정보' AFTER payment_settings;
```

### 2.2 JSON 구조 설계

#### payment_settings 구조:
```json
{
  "pg": {
    "inicis": {
      "mobile": {
        "mid": "wpgymjs200",
        "signkey": "NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09",
        "url": "https://mobile.inicis.com",
        "enabled": true
      },
      "card": {
        "mid": "wpgymjs200c",
        "signkey": "카드결제키값",
        "enabled": true
      }
    },
    "kcp": {
      "mobile": {
        "site_cd": "T0000",
        "site_key": "3grptw1.zW0GSo4PQdaGvsF__",
        "enabled": false
      }
    },
    "toss": {
      "payment": {
        "client_key": "test_ck_123456789",
        "secret_key": "test_sk_123456789",
        "enabled": true
      }
    }
  },
  "van": {
    "kicc": {
      "terminal_id": "T0001234",
      "merchant_no": "M123456789",
      "api_key": "kicc_api_key_here",
      "timeout": 30,
      "enabled": true
    },
    "nice": {
      "terminal_id": "N0001234",
      "merchant_no": "N987654321",
      "enabled": false
    },
    "ksnet": {
      "store_id": "2999199999",
      "api_key": "ksnet_key",
      "enabled": true
    }
  },
  "default": {
    "pg": "inicis",
    "pg_type": "mobile",
    "van": "kicc"
  },
  "dev_mode": false
}
```

#### bank_accounts 구조:
```json
[
  {
    "bank_nm": "국민은행",
    "bank_cd": "004",
    "account_no": "12321-13547-125-25",
    "account_holder": "(주)스포크플러스",
    "account_type": "일반",
    "is_default": true,
    "is_active": true,
    "display_order": 1
  },
  {
    "bank_nm": "하나은행",
    "bank_cd": "081",
    "account_no": "8514-254-45387-11",
    "account_holder": "(주)스포크플러스",
    "account_type": "일반",
    "is_default": false,
    "is_active": true,
    "display_order": 2
  },
  {
    "bank_nm": "가상계좌",
    "bank_cd": "000",
    "account_no": "가상계좌는 자동생성",
    "account_type": "가상",
    "is_active": true,
    "display_order": 3
  }
]
```

## 3. Model 설계

### 3.1 PaymentSettingsModel.php
```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentSettingsModel extends Model
{
    protected $table = 'bcoff_mgmt_tbl';
    
    /**
     * PG 설정 조회
     */
    public function getPgSettings($comp_cd, $bcoff_cd, $pg_vendor = null, $pg_type = 'mobile')
    {
        $result = $this->where([
            'COMP_CD' => $comp_cd,
            'BCOFF_CD' => $bcoff_cd,
            'USE_YN' => 'Y'
        ])->first();
        
        if (!$result || !$result['payment_settings']) {
            return null;
        }
        
        $settings = json_decode($result['payment_settings'], true);
        
        // 특정 PG사 지정
        if ($pg_vendor) {
            return $settings['pg'][$pg_vendor][$pg_type] ?? null;
        }
        
        // 기본 PG사 사용
        $default_pg = $settings['default']['pg'] ?? 'inicis';
        $default_type = $settings['default']['pg_type'] ?? 'mobile';
        
        return $settings['pg'][$default_pg][$default_type] ?? null;
    }
    
    /**
     * VAN 설정 조회
     */
    public function getVanSettings($comp_cd, $bcoff_cd, $van_vendor = null)
    {
        $result = $this->where([
            'COMP_CD' => $comp_cd,
            'BCOFF_CD' => $bcoff_cd,
            'USE_YN' => 'Y'
        ])->first();
        
        if (!$result || !$result['payment_settings']) {
            return null;
        }
        
        $settings = json_decode($result['payment_settings'], true);
        
        // 특정 VAN사 지정
        if ($van_vendor) {
            return $settings['van'][$van_vendor] ?? null;
        }
        
        // 기본 VAN사 사용
        $default_van = $settings['default']['van'] ?? 'kicc';
        
        return $settings['van'][$default_van] ?? null;
    }
    
    /**
     * 활성화된 모든 PG사 목록
     */
    public function getActivePgList($comp_cd, $bcoff_cd)
    {
        $result = $this->where([
            'COMP_CD' => $comp_cd,
            'BCOFF_CD' => $bcoff_cd,
            'USE_YN' => 'Y'
        ])->first();
        
        if (!$result || !$result['payment_settings']) {
            return [];
        }
        
        $settings = json_decode($result['payment_settings'], true);
        $active_list = [];
        
        foreach ($settings['pg'] as $vendor => $types) {
            foreach ($types as $type => $config) {
                if ($config['enabled'] ?? false) {
                    $active_list[] = [
                        'vendor' => $vendor,
                        'type' => $type,
                        'name' => $this->getPgName($vendor),
                        'config' => $config
                    ];
                }
            }
        }
        
        return $active_list;
    }
    
    /**
     * 활성화된 모든 VAN사 목록
     */
    public function getActiveVanList($comp_cd, $bcoff_cd)
    {
        $result = $this->where([
            'COMP_CD' => $comp_cd,
            'BCOFF_CD' => $bcoff_cd,
            'USE_YN' => 'Y'
        ])->first();
        
        if (!$result || !$result['payment_settings']) {
            return [];
        }
        
        $settings = json_decode($result['payment_settings'], true);
        $active_list = [];
        
        foreach ($settings['van'] as $vendor => $config) {
            if ($config['enabled'] ?? false) {
                $active_list[] = [
                    'vendor' => $vendor,
                    'name' => $this->getVanName($vendor),
                    'config' => $config
                ];
            }
        }
        
        return $active_list;
    }
    
    /**
     * 개발모드 확인
     */
    public function isDevMode($comp_cd, $bcoff_cd)
    {
        $result = $this->where([
            'COMP_CD' => $comp_cd,
            'BCOFF_CD' => $bcoff_cd
        ])->first();
        
        if (!$result || !$result['payment_settings']) {
            return false;
        }
        
        $settings = json_decode($result['payment_settings'], true);
        return $settings['dev_mode'] ?? false;
    }
    
    /**
     * PG사 이름 매핑
     */
    private function getPgName($vendor)
    {
        $names = [
            'inicis' => '이니시스',
            'kcp' => 'NHN KCP',
            'toss' => '토스페이먼츠',
            'nice' => '나이스페이',
            'kakao' => '카카오페이',
            'naver' => '네이버페이'
        ];
        
        return $names[$vendor] ?? $vendor;
    }
    
    /**
     * VAN사 이름 매핑
     */
    private function getVanName($vendor)
    {
        $names = [
            'kicc' => 'KICC',
            'nice' => 'NICE',
            'ksnet' => 'KSNET',
            'kovan' => '코밴'
        ];
        
        return $names[$vendor] ?? $vendor;
    }
}
```

## 4. 사용 예시

### 4.1 Controller에서 사용
```php
// PG 설정 조회
$paymentModel = new PaymentSettingsModel();

// 기본 PG 사용
$pgConfig = $paymentModel->getPgSettings($_SESSION['comp_cd'], $_SESSION['bcoff_cd']);

// 특정 PG사 지정
$kcpConfig = $paymentModel->getPgSettings($_SESSION['comp_cd'], $_SESSION['bcoff_cd'], 'kcp', 'mobile');

// VAN 설정 조회
$vanConfig = $paymentModel->getVanSettings($_SESSION['comp_cd'], $_SESSION['bcoff_cd']);

// 활성화된 결제수단 목록 (결제 화면용)
$activePgList = $paymentModel->getActivePgList($_SESSION['comp_cd'], $_SESSION['bcoff_cd']);
```

### 4.2 View에서 결제수단 선택
```php
<select name="payment_method" class="form-control">
    <option value="">결제수단 선택</option>
    <?php foreach ($activePgList as $pg): ?>
        <option value="<?= $pg['vendor'] ?>_<?= $pg['type'] ?>">
            <?= $pg['name'] ?> 
            <?php if($pg['type'] == 'mobile'): ?>(모바일)<?php endif; ?>
            <?php if($pg['type'] == 'card'): ?>(카드)<?php endif; ?>
        </option>
    <?php endforeach; ?>
</select>
```

## 5. 마이그레이션 SQL

### 5.1 잠실점 예시 (여러 PG/VAN 설정)
```sql
UPDATE bcoff_mgmt_tbl 
SET payment_settings = JSON_OBJECT(
    'pg', JSON_OBJECT(
        'inicis', JSON_OBJECT(
            'mobile', JSON_OBJECT(
                'mid', 'wpgymjs200',
                'signkey', 'NkpsY1B4NXM5c0dNN2xYTmpYMUx6UT09',
                'enabled', true
            ),
            'card', JSON_OBJECT(
                'mid', 'wpgymjs200c',
                'signkey', '카드키값',
                'enabled', true
            )
        ),
        'kcp', JSON_OBJECT(
            'mobile', JSON_OBJECT(
                'site_cd', 'T0007',
                'site_key', 'kcp키값',
                'enabled', true
            )
        ),
        'toss', JSON_OBJECT(
            'payment', JSON_OBJECT(
                'client_key', 'test_ck_잠실점',
                'secret_key', 'test_sk_잠실점',
                'enabled', false
            )
        )
    ),
    'van', JSON_OBJECT(
        'kicc', JSON_OBJECT(
            'terminal_id', 'T0001234',
            'merchant_no', 'M123456789',
            'enabled', true
        ),
        'nice', JSON_OBJECT(
            'terminal_id', 'N0001234',
            'merchant_no', 'N987654321',
            'enabled', true
        )
    ),
    'default', JSON_OBJECT(
        'pg', 'inicis',
        'pg_type', 'mobile',
        'van', 'kicc'
    ),
    'dev_mode', false
),
bank_accounts = JSON_ARRAY(
    JSON_OBJECT(
        'bank_nm', '국민은행',
        'bank_cd', '004',
        'account_no', '12321-13547-125-25',
        'account_holder', '(주)스포크플러스 잠실점',
        'is_default', true,
        'is_active', true,
        'display_order', 1
    ),
    JSON_OBJECT(
        'bank_nm', '하나은행',
        'bank_cd', '081',
        'account_no', '8514-254-45387-11',
        'account_holder', '(주)스포크플러스 잠실점',
        'is_default', false,
        'is_active', true,
        'display_order', 2
    )
)
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00001';
```

### 5.2 역삼점 예시 (다른 PG/VAN 조합)
```sql
UPDATE bcoff_mgmt_tbl 
SET payment_settings = JSON_OBJECT(
    'pg', JSON_OBJECT(
        'inicis', JSON_OBJECT(
            'mobile', JSON_OBJECT(
                'mid', 'wpgym200ys',
                'signkey', 'clNSQTgxa2lLZHdMTG1DOEVaME5QZz09',
                'enabled', true
            )
        ),
        'naver', JSON_OBJECT(
            'pay', JSON_OBJECT(
                'client_id', 'naver_역삼점',
                'client_secret', 'secret_역삼점',
                'enabled', true
            )
        )
    ),
    'van', JSON_OBJECT(
        'ksnet', JSON_OBJECT(
            'store_id', '2999199999',
            'api_key', 'ksnet_역삼점_key',
            'enabled', true
        )
    ),
    'default', JSON_OBJECT(
        'pg', 'inicis',
        'pg_type', 'mobile',
        'van', 'ksnet'
    ),
    'dev_mode', false
)
WHERE COMP_CD = 'C00001' AND BCOFF_CD = 'B00002';
```

## 6. 관리자 화면

### 6.1 결제 설정 관리 UI
```
[잠실점 결제 설정]

PG사 설정:
├─ ☑ 이니시스 
│  ├─ ☑ 모바일결제 (MID: wpgymjs200)
│  └─ ☑ 카드결제 (MID: wpgymjs200c)
├─ ☑ KCP
│  └─ ☑ 모바일결제 (Site CD: T0007)
└─ ☐ 토스페이먼츠
   └─ ☐ 통합결제

VAN사 설정:
├─ ☑ KICC (단말기ID: T0001234)
└─ ☑ NICE (단말기ID: N0001234)

기본 결제수단: 이니시스(모바일)
기본 VAN사: KICC
```

## 7. 장점

1. **완전한 독립성**: 각 지점이 완전히 독립적인 결제 설정 보유
2. **다중 벤더 지원**: 여러 PG사/VAN사 동시 사용 가능
3. **유연한 확장**: 새로운 PG사/VAN사 추가 시 JSON 구조만 확장
4. **간편한 전환**: 기본 설정으로 빠른 전환 가능
5. **개발/운영 분리**: dev_mode로 테스트 환경 쉽게 전환

## 8. 보안 고려사항

1. **암호화**: signkey, api_key 등은 application 레벨에서 암호화
2. **권한 관리**: payment_settings 수정은 최고 관리자만 가능
3. **로깅**: 모든 설정 변경 이력 기록
4. **검증**: JSON 구조 검증으로 잘못된 설정 방지