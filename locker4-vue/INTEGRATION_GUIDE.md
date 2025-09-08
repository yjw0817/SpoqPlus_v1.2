# Locker4 Vue.js Integration Guide for CodeIgniter

## 통합 개발 환경 구축 가이드

이 문서는 Locker4 Vue.js 프로젝트를 CodeIgniter와 통합하여 소스 코드를 직접 수정할 수 있는 개발 환경을 설명합니다.

## 프로젝트 구조

```
/mnt/d/Projects/html/SpoqPlus_v1.2/
├── app/
│   └── Views/
│       └── locker/
│           └── locker_setting.php  # CodeIgniter 뷰 (개발/프로덕션 모드 지원)
├── public/
│   └── assets/
│       └── locker4/               # 빌드된 프로덕션 파일
│           ├── js/
│           └── css/
└── locker4-vue/                   # Vue 소스 코드 (직접 수정 가능)
    ├── src/                        # Vue 소스 파일
    │   ├── components/
    │   ├── pages/
    │   ├── api/
    │   └── main.ts
    ├── vite.config.ts             # Vite 설정
    ├── package.json
    └── tsconfig.json
```

## 개발 환경 설정

### 1. 초기 설정 (최초 1회)

```bash
# Vue 프로젝트 디렉토리로 이동
cd /mnt/d/Projects/html/SpoqPlus_v1.2/locker4-vue

# 의존성 설치
npm install
```

### 2. 개발 서버 실행

```bash
# Vue 개발 서버 시작 (포트 5175)
cd /mnt/d/Projects/html/SpoqPlus_v1.2/locker4-vue
npm run dev
```

### 3. 개발 모드로 접속

브라우저에서 CodeIgniter 페이지에 `?dev` 파라미터를 추가하여 접속:

```
http://localhost:3333/locker/setting?dev
```

이렇게 하면 Vite 개발 서버에서 실시간으로 코드를 제공받아 Hot Module Replacement(HMR)가 작동합니다.

## 개발 워크플로우

### 소스 코드 수정

1. **직접 수정 가능한 위치**: `/mnt/d/Projects/html/SpoqPlus_v1.2/locker4-vue/src/`
2. **파일 저장 시 자동 리로드**: HMR이 활성화되어 브라우저가 자동으로 업데이트됩니다.

### 예시: 컴포넌트 수정

```bash
# 락커 관리 페이지 수정
vi /mnt/d/Projects/html/SpoqPlus_v1.2/locker4-vue/src/pages/LockerManagement.vue

# 저장 후 브라우저에서 즉시 확인 가능
```

## 프로덕션 빌드

### 1. 빌드 실행

```bash
cd /mnt/d/Projects/html/SpoqPlus_v1.2/locker4-vue
npm run build
```

빌드된 파일은 자동으로 `../public/assets/locker4/` 디렉토리에 생성됩니다.

### 2. 프로덕션 모드 접속

`?dev` 파라미터 없이 접속하면 빌드된 파일을 사용합니다:

```
http://localhost:3333/locker/setting
```

## 서버 포트 정보

- **Vite 개발 서버**: 5175 (이전 프로젝트와 충돌 방지를 위해 변경)
- **CodeIgniter 서버**: 3333
- **기존 Locker4 프론트엔드**: 5174 (독립 실행 시)

## 주요 설정 파일

### vite.config.ts

```typescript
export default defineConfig({
  server: {
    port: 5175,  // 개발 서버 포트
    strictPort: true,
    host: true
  },
  base: '/assets/locker4/',  // 프로덕션 빌드 경로
  build: {
    outDir: '../public/assets/locker4'  // 빌드 출력 디렉토리
  }
})
```

### locker_setting.php

개발/프로덕션 모드를 자동으로 전환:

```php
<?php if(ENVIRONMENT === 'development' && isset($_GET['dev'])): ?>
<!-- 개발 모드: Vite Dev Server 사용 -->
<script type="module" src="http://localhost:5175/src/main.ts"></script>
<?php else: ?>
<!-- 프로덕션 모드: 빌드된 파일 사용 -->
<script type="module" src="<?= base_url('assets/locker4/js/index.js') ?>"></script>
<?php endif; ?>
```

## 문제 해결

### 포트 충돌

```bash
# 포트 5175 사용 중인 프로세스 확인
lsof -i :5175

# 필요시 프로세스 종료
kill -9 $(lsof -t -i:5175)
```

### 브라우저 캐시 문제

- 개발 모드: Ctrl+Shift+R로 강제 새로고침
- 프로덕션 모드: 빌드 시 자동으로 해시가 추가되어 캐시 문제 방지

### CORS 에러

Vite 설정의 proxy 설정을 확인하세요:

```typescript
proxy: {
  '/api': {
    target: 'http://localhost:3333',
    changeOrigin: true
  }
}
```

## 장점

1. **직접 소스 수정**: CodeIgniter 프로젝트 내에서 Vue 소스를 직접 수정 가능
2. **실시간 반영**: HMR로 수정사항이 즉시 브라우저에 반영
3. **통합 관리**: 하나의 프로젝트로 전체 코드 관리
4. **유연한 배포**: 개발/프로덕션 모드를 쉽게 전환

## 팀 협업 가이드

1. Git에서 `/locker4-vue/node_modules/`는 제외 (.gitignore)
2. 새로운 팀원은 `npm install` 실행 필요
3. 개발 시 항상 `?dev` 파라미터 사용
4. 배포 전 반드시 `npm run build` 실행

## 업데이트 내역

- 2025-09-08: 통합 개발 환경 구축 완료
- Vue 소스 코드를 CodeIgniter 프로젝트에 직접 통합
- 개발/프로덕션 모드 자동 전환 구현