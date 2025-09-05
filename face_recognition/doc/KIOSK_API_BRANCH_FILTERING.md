# KIOSK API Branch Filtering

## 지점별 필터링 가이드

### 필터링 구조

#### 데이터베이스 레벨
```sql
-- 지점별 회원 조회
SELECT * FROM members 
WHERE branch_code = 'GANGNAM' 
AND status = 'active';
```

#### API 레벨 필터링
```php
public function getMembersByBranch($branchCode) {
    return $this->db->table('members')
        ->where('branch_code', $branchCode)
        ->where('status', 'active')
        ->get()
        ->getResult();
}
```

### 지점 설정

#### 환경 변수 (.env)
```
KIOSK_BRANCH_CODE=GANGNAM
KIOSK_BRANCH_NAME=강남점
```

#### 설정 파일
```php
// app/Config/Kiosk.php
class Kiosk extends BaseConfig {
    public $branches = [
        'GANGNAM' => ['name' => '강남점', 'code' => 'GN'],
        'SEOCHO' => ['name' => '서초점', 'code' => 'SC'],
        'JAMSIL' => ['name' => '잠실점', 'code' => 'JS']
    ];
}
```

### 필터링 로직

#### 1. 회원 조회시
- KIOSK 지점 코드 확인
- 해당 지점 회원만 표시
- 타 지점 회원 접근 차단

#### 2. 체크인시
- 회원의 등록 지점 확인
- 현재 KIOSK 지점과 비교
- 불일치시 안내 메시지

#### 3. 이용권 확인
- 지점별 이용권 구분
- 공통 이용권 처리
- 지점 전용 이용권 처리

### 구현 예시

```php
class KioskController extends BaseController {
    
    private function getBranchCode() {
        return env('KIOSK_BRANCH_CODE', 'DEFAULT');
    }
    
    public function searchMember() {
        $phone = $this->request->getVar('phone');
        $branchCode = $this->getBranchCode();
        
        $member = $this->memberModel
            ->where('phone', $phone)
            ->where('branch_code', $branchCode)
            ->first();
            
        if (!$member) {
            return $this->response->setJSON([
                'success' => false,
                'message' => '해당 지점에 등록된 회원이 아닙니다.'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $member
        ]);
    }
}
```

### 다중 지점 지원

#### 통합 회원
```php
// 여러 지점 이용 가능한 회원
$allowedBranches = ['GANGNAM', 'SEOCHO'];
$member = $this->memberModel
    ->whereIn('branch_code', $allowedBranches)
    ->first();
```

#### 이용권 공유
```php
// 모든 지점 이용 가능한 이용권
if ($pass->is_all_branch) {
    // 지점 제한 없음
} else {
    // 특정 지점만 가능
    if ($pass->branch_code !== $currentBranch) {
        throw new Exception('다른 지점 이용권입니다.');
    }
}
```

### 로그 및 통계
- 지점별 이용 통계
- 교차 이용 현황
- 시간대별 분석