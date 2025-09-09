# Locker4 프로젝트 정밀 분석 보고서

## 📋 목차
1. [프로젝트 개요](#프로젝트-개요)
2. [시스템 아키텍처](#시스템-아키텍처)
3. [데이터베이스 구조](#데이터베이스-구조)
4. [백엔드 API 구조](#백엔드-api-구조)
5. [프론트엔드 구조](#프론트엔드-구조)
6. [핵심 비즈니스 로직](#핵심-비즈니스-로직)
7. [데이터 흐름](#데이터-흐름)
8. [통합 전략](#통합-전략)

---

## 🎯 프로젝트 개요

### 프로젝트 정보
- **프로젝트명**: Locker4
- **목적**: 락커 배치 및 관리 시스템
- **기술 스택**: 
  - Backend: Node.js + Express + MySQL
  - Frontend: Vue.js 3 + TypeScript + Pinia
  - 통합 대상: CodeIgniter 4 (PHP)

### 주요 특징
- **이중 뷰 시스템**: 평면도(Floor View)와 정면도(Front View) 동시 지원
- **계층적 구조**: 부모-자식 락커 관계를 통한 다단 락커 구현
- **실시간 상호작용**: 드래그 앤 드롭, 충돌 감지, 정렬 가이드
- **오프라인 지원**: 로컬 캐싱 및 동기화 메커니즘

---

## 🏗️ 시스템 아키텍처

### 전체 구조
```
Locker4/
├── backend/                 # Node.js Express 서버
│   ├── server.js           # 메인 서버 (포트 3333)
│   ├── config/
│   │   └── database.js     # MySQL 연결 설정
│   └── routes/
│       ├── lockers.js      # 락커 CRUD API
│       ├── zones.js        # 구역 관리 API  
│       └── types.js        # 락커 타입 API
│
└── frontend/               # Vue.js 애플리케이션
    ├── src/
    │   ├── components/
    │   │   ├── LockerPlacementFigma.vue  # 메인 컴포넌트
    │   │   ├── LockerManager.vue         # 관리 컴포넌트
    │   │   └── LockerCanvas.vue          # 캔버스 컴포넌트
    │   ├── services/
    │   │   └── lockerApi.ts               # API 서비스 레이어
    │   └── stores/
    │       └── lockerStore.ts             # Pinia 상태 관리
    └── package.json
```

---

## 💾 데이터베이스 구조

### 1. lockrs 테이블 (메인 락커 테이블)
```sql
CREATE TABLE `lockrs` (
  `LOCKR_CD` int(11) NOT NULL AUTO_INCREMENT,        -- 락커 코드 (PK)
  `COMP_CD` varchar(10) NOT NULL DEFAULT '001',      -- 회사 코드
  `BCOFF_CD` varchar(10) NOT NULL DEFAULT '001',     -- 지점 코드
  `LOCKR_KND` varchar(10) DEFAULT NULL,              -- 구역 코드 (Zone ID)
  `LOCKR_TYPE_CD` varchar(10) NOT NULL DEFAULT '1',  -- 락커 타입 코드
  `X` int(11) NOT NULL DEFAULT 0,                    -- X 좌표 (평면도)
  `Y` int(11) NOT NULL DEFAULT 0,                    -- Y 좌표 (평면도)
  `LOCKR_LABEL` varchar(50) NOT NULL,                -- 락커 라벨/번호
  `ROTATION` int(11) DEFAULT 0,                      -- 회전 각도 (0, 90, 180, 270)
  `DOOR_DIRECTION` varchar(10) DEFAULT NULL,         -- 문 방향
  `FRONT_VIEW_X` int(11) DEFAULT NULL,               -- 정면도 X 좌표
  `FRONT_VIEW_Y` int(11) DEFAULT NULL,               -- 정면도 Y 좌표
  `GROUP_NUM` varchar(10) DEFAULT NULL,              -- 그룹 번호
  `LOCKR_GENDR_SET` varchar(10) DEFAULT NULL,        -- 성별 설정
  `LOCKR_NO` varchar(20) DEFAULT NULL,               -- 락커 고유 번호
  `PARENT_LOCKR_CD` int(11) DEFAULT NULL,            -- 부모 락커 코드 (다단 락커용)
  `TIER_LEVEL` int(11) DEFAULT NULL,                 -- 단 레벨 (1, 2, 3...)
  `BUY_EVENT_SNO` int(11) DEFAULT NULL,              -- 구매 이벤트 번호
  `MEM_SNO` int(11) DEFAULT NULL,                    -- 회원 번호
  `MEM_NM` varchar(100) DEFAULT NULL,                -- 회원명
  `LOCKR_USE_S_DATE` date DEFAULT NULL,              -- 사용 시작일
  `LOCKR_USE_E_DATE` date DEFAULT NULL,              -- 사용 종료일
  `LOCKR_STAT` varchar(2) DEFAULT '00',              -- 락커 상태
  `MEMO` text DEFAULT NULL,                          -- 메모
  `UPDATE_BY` varchar(50) DEFAULT NULL,              -- 수정자
  `UPDATE_DT` datetime DEFAULT NULL,                 -- 수정일시
  PRIMARY KEY (`LOCKR_CD`),
  KEY `idx_comp_bcoff` (`COMP_CD`, `BCOFF_CD`),
  KEY `idx_parent` (`PARENT_LOCKR_CD`),
  KEY `idx_stat` (`LOCKR_STAT`),
  KEY `idx_member` (`MEM_SNO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2. lockr_area 테이블 (구역/Zone 테이블)
```sql
CREATE TABLE `lockr_area` (
  `LOCKR_KND_CD` varchar(36) NOT NULL,               -- 구역 코드
  `LOCKR_KND_NM` varchar(50),                        -- 구역명
  `COMP_CD` varchar(20) NOT NULL,                    -- 회사 코드
  `BCOFF_CD` varchar(20) NOT NULL,                   -- 지점 코드
  `X` int NOT NULL,                                  -- 구역 X 좌표
  `Y` int NOT NULL,                                  -- 구역 Y 좌표
  `WIDTH` int NOT NULL,                               -- 구역 너비
  `HEIGHT` int NOT NULL,                              -- 구역 높이
  `COLOR` varchar(7),                                -- 구역 색상 (#RRGGBB)
  `FLOOR` int DEFAULT 1,                             -- 층 번호
  `CRE_DATETM` datetime DEFAULT CURRENT_TIMESTAMP,   -- 생성일시
  PRIMARY KEY (`LOCKR_KND_CD`, `COMP_CD`, `BCOFF_CD`),
  INDEX idx_comp_bcoff (`COMP_CD`, `BCOFF_CD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. lockr_types 테이블 (락커 타입 테이블)
```sql
CREATE TABLE `lockr_types` (
  `LOCKR_TYPE_CD` varchar(10) NOT NULL,              -- 타입 코드
  `LOCKR_TYPE_NM` varchar(50),                       -- 타입명
  `WIDTH` int NOT NULL,                              -- 너비
  `HEIGHT` int NOT NULL,                             -- 높이
  `DEPTH` int NOT NULL,                              -- 깊이
  `COLOR` varchar(7),                                -- 색상
  `PRICE` decimal(10,2),                             -- 가격
  PRIMARY KEY (`LOCKR_TYPE_CD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 락커 상태 코드 (LOCKR_STAT)
- `00`: 사용 가능 (Available)
- `01`: 사용 중 (Occupied)
- `02`: 예약됨 (Reserved)
- `03`: 정비 중 (Maintenance)
- `04`: 비활성화 (Disabled)
- `05`: 만료됨 (Expired)

---

## 🔌 백엔드 API 구조

### 서버 설정
- **포트**: 3333
- **CORS 설정**: localhost:3000, 5173, 5174, 3001, 8080
- **데이터베이스**: MySQL (mysql2/promise)

### API 엔드포인트

#### 1. 락커 API (/api/lockrs)
```javascript
GET    /api/lockrs              // 전체 락커 조회
       ?COMP_CD=001             // 회사 코드 필터
       &BCOFF_CD=001            // 지점 코드 필터
       &parentOnly=true         // 부모 락커만 조회

GET    /api/lockrs/:lockrCd     // 특정 락커 조회

POST   /api/lockrs               // 새 락커 생성
       Body: {
         COMP_CD, BCOFF_CD, LOCKR_KND, LOCKR_TYPE_CD,
         X, Y, LOCKR_LABEL, ROTATION, etc.
       }

PUT    /api/lockrs/:lockrCd     // 락커 업데이트

DELETE /api/lockrs/:lockrCd     // 락커 삭제

POST   /api/lockrs/:lockrCd/tiers  // 다단 락커 추가
       Body: { tierCount: 3 }

PUT    /api/lockrs/batch         // 일괄 업데이트
       Body: { updates: [...] }
```

#### 2. 구역 API (/api/zones)
```javascript
GET    /api/zones                // 전체 구역 조회
POST   /api/zones                // 새 구역 생성
DELETE /api/zones/:zoneId        // 구역 삭제
```

#### 3. 타입 API (/api/types)
```javascript
GET    /api/types                // 전체 타입 조회
POST   /api/types                // 새 타입 생성
DELETE /api/types/:typeId        // 타입 삭제
```

---

## 🎨 프론트엔드 구조

### 주요 컴포넌트

#### 1. LockerPlacementFigma.vue
- **역할**: 메인 락커 배치 컴포넌트
- **주요 기능**:
  - 평면도/정면도 뷰 전환
  - 드래그 앤 드롭 구현
  - 충돌 감지 및 정렬 가이드
  - 다중 선택 및 그룹 작업

#### 2. API 서비스 (lockerApi.ts)
```typescript
export interface ApiLocker {
  LOCKR_CD: number;
  COMP_CD: string;
  BCOFF_CD: string;
  LOCKR_KND: string;
  LOCKR_TYPE_CD: string;
  X: number;
  Y: number;
  LOCKR_LABEL: string;
  ROTATION: number;
  LOCKR_STAT: string;
  // ... 기타 필드
}

export interface AppLocker {
  id: string;
  typeId: string;
  x: number;
  y: number;
  rotation: number;
  zoneId: string;
  number: string;
  status: 'available' | 'occupied' | 'maintenance';
  // ... 기타 필드
}
```

### 데이터 변환 로직
```typescript
// DB → App 형식 변환
convertDbToApp(dbLocker: ApiLocker): AppLocker {
  return {
    id: `locker-${dbLocker.LOCKR_CD}`,
    typeId: String(dbLocker.LOCKR_TYPE_CD),
    x: dbLocker.X,
    y: dbLocker.Y,
    rotation: dbLocker.ROTATION || 0,
    zoneId: dbLocker.LOCKR_KND,
    number: dbLocker.LOCKR_LABEL,
    status: mapDbStatusToApp(dbLocker.LOCKR_STAT)
  };
}

// App → DB 형식 변환
convertAppToDb(appLocker: AppLocker): ApiLocker {
  return {
    LOCKR_CD: parseInt(appLocker.id.replace('locker-', '')),
    COMP_CD: '001',
    BCOFF_CD: '001',
    LOCKR_KND: appLocker.zoneId,
    LOCKR_TYPE_CD: appLocker.typeId,
    X: Math.round(appLocker.x),
    Y: Math.round(appLocker.y),
    LOCKR_LABEL: appLocker.number,
    ROTATION: appLocker.rotation,
    LOCKR_STAT: mapAppStatusToDb(appLocker.status)
  };
}
```

---

## 🔧 핵심 비즈니스 로직

### 1. 좌표 시스템
- **평면도 (Floor View)**: X, Y 좌표 사용
- **정면도 (Front View)**: FRONT_VIEW_X, FRONT_VIEW_Y 사용
- **회전**: 0°, 90°, 180°, 270° 지원
- **그리드 스냅**: 20px 단위로 정렬

### 2. 계층적 락커 구조
```
부모 락커 (PARENT_LOCKR_CD = NULL)
  ├── 1단 (TIER_LEVEL = 1)
  ├── 2단 (TIER_LEVEL = 2)
  └── 3단 (TIER_LEVEL = 3)
```

### 3. 충돌 감지
```javascript
function checkCollision(locker1, locker2) {
  const type1 = getLockerType(locker1.typeId);
  const type2 = getLockerType(locker2.typeId);
  
  return !(locker1.x + type1.width <= locker2.x ||
           locker2.x + type2.width <= locker1.x ||
           locker1.y + type1.height <= locker2.y ||
           locker2.y + type2.height <= locker1.y);
}
```

### 4. 정렬 가이드
- 수직선: X 좌표가 근접할 때 표시
- 수평선: Y 좌표가 근접할 때 표시
- 스냅 임계값: 10px

---

## 📊 데이터 흐름

### 1. 데이터 로드 플로우
```
1. 페이지 로드
   ↓
2. API 호출 (GET /api/zones, /api/types, /api/lockrs)
   ↓
3. 데이터 변환 (DB → App 형식)
   ↓
4. Pinia Store 저장
   ↓
5. Vue 컴포넌트 렌더링
```

### 2. 데이터 저장 플로우
```
1. 사용자 작업 (드래그, 생성, 삭제)
   ↓
2. 로컬 상태 업데이트
   ↓
3. API 호출 (POST/PUT/DELETE)
   ↓
4. 데이터 변환 (App → DB 형식)
   ↓
5. 데이터베이스 저장
   ↓
6. 응답 처리 및 UI 업데이트
```

---

## 🔗 통합 전략

### CodeIgniter와의 통합

#### 1. PHP 컨트롤러 구현
```php
// app/Controllers/Locker.php
public function get_lockers() {
    $db = \Config\Database::connect();
    $builder = $db->table('lockrs');
    
    $builder->where('COMP_CD', $comp_cd);
    $builder->where('BCOFF_CD', $bcoff_cd);
    
    $lockers = $builder->get()->getResultArray();
    
    return $this->response->setJSON([
        'status' => 'success',
        'lockers' => $lockers
    ]);
}
```

#### 2. JavaScript API 레이어
```javascript
// locker-api.js
window.LockerAPI = {
    getApiUrl: function() {
        return window.LockerConfig.baseUrl + '/api';
    },
    
    getLockers: async function(compCd, bcoffCd, zoneId) {
        const response = await fetch(`${this.getApiUrl()}/locker/lockers`, {
            method: 'GET',
            headers: this.getCsrfHeaders()
        });
        const data = await response.json();
        return data.lockers.map(l => this.convertDbToApp(l));
    }
};
```

#### 3. 라우팅 설정
```php
// app/Config/Routes.php
$routes->group('api/locker', function($routes) {
    $routes->get('zones', 'Locker::ajax_get_zones');
    $routes->get('types', 'Locker::ajax_get_locker_types');
    $routes->get('lockers', 'Locker::get_lockers');
    $routes->post('lockers', 'Locker::ajax_create_locker');
    $routes->put('lockers/(:num)', 'Locker::ajax_update_locker/$1');
    $routes->delete('lockers/(:num)', 'Locker::ajax_delete_locker/$1');
});
```

---

## 📝 주요 특징 및 장점

### 장점
1. **모듈화된 구조**: 프론트엔드와 백엔드가 명확히 분리
2. **확장 가능성**: 새로운 락커 타입이나 기능 추가 용이
3. **실시간 상호작용**: 사용자 친화적인 드래그 앤 드롭
4. **데이터 정합성**: 트랜잭션 처리 및 외래키 제약
5. **유연한 통합**: Node.js와 PHP 모두 지원

### 개선 가능한 부분
1. **캐싱 전략**: Redis 등을 활용한 성능 개선
2. **실시간 동기화**: WebSocket을 통한 다중 사용자 지원
3. **권한 관리**: 사용자별 접근 권한 세분화
4. **로깅 시스템**: 상세한 작업 이력 관리
5. **백업 전략**: 자동 백업 및 복구 메커니즘

---

## 🚀 통합 체크리스트

- [x] 데이터베이스 테이블 생성 (lockrs, lockr_area, lockr_types)
- [x] PHP 컨트롤러 메서드 구현
- [x] JavaScript API 서비스 레이어 구현
- [x] 라우팅 설정
- [x] CSRF 토큰 처리
- [x] 데이터 변환 로직 구현
- [ ] 실제 데이터베이스 연결 테스트
- [ ] 에러 핸들링 강화
- [ ] 성능 최적화
- [ ] 사용자 권한 검증

---

## 📚 참고 자료

### API 테스트
```bash
# 락커 목록 조회
curl http://localhost:3333/api/lockrs?COMP_CD=001&BCOFF_CD=001

# 새 락커 생성
curl -X POST http://localhost:3333/api/lockrs \
  -H "Content-Type: application/json" \
  -d '{"COMP_CD":"001","BCOFF_CD":"001","LOCKR_LABEL":"A001","X":100,"Y":100}'
```

### 데이터베이스 쿼리
```sql
-- 전체 락커 조회
SELECT * FROM lockrs WHERE COMP_CD='001' AND BCOFF_CD='001';

-- 구역별 락커 수
SELECT LOCKR_KND, COUNT(*) as cnt 
FROM lockrs 
GROUP BY LOCKR_KND;

-- 상태별 통계
SELECT LOCKR_STAT, COUNT(*) as cnt 
FROM lockrs 
GROUP BY LOCKR_STAT;
```

---

*마지막 업데이트: 2025-01-17*