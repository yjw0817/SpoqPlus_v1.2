# param1/param2 필터링 테스트 체크리스트

## 1. 서비스 실행
```bash
# face_recognition 디렉터리에서
python insightface_service.py
```

## 2. Python 테스트 실행
```bash
# 새 터미널에서
python test_param_filtering.py
```

## 3. PHP 테스트 실행
```bash
# examples 디렉터리에서
php test_param_filtering.php
```

## 4. 테스트 시나리오 체크리스트

### ✅ 회원 등록 테스트
- [ ] 회사 A, 지점 1에 회원 등록 성공
- [ ] 회사 A, 지점 2에 회원 등록 성공
- [ ] 회사 B, 지점 1에 회원 등록 성공
- [ ] member_faces 테이블에 param1, param2 값 저장 확인

### ✅ 필터링 테스트
- [ ] 필터 없이 전체 조회 시 모든 회원 검색됨
- [ ] param1="COMP_A"만 지정 시 회사 A의 모든 지점 회원 검색됨
- [ ] param1="COMP_A", param2="BRANCH_01" 지정 시 해당 지점만 검색됨
- [ ] 다른 회사/지점 지정 시 해당 회원만 검색됨

### ✅ 레거시 호환성 테스트
- [ ] comp_cd, bcoff_cd 파라미터 사용 시 정상 동작
- [ ] recognize_for_checkin API가 param1, param2로 자동 변환됨
- [ ] 기존 코드 수정 없이도 정상 동작

### ✅ 로그 확인
- [ ] face_recognition_logs 테이블에 param1, param2 값 저장됨
- [ ] insightface_service.log에 필터링 로그 출력됨

## 5. 데이터베이스 확인 SQL

```sql
-- 등록된 회원 얼굴 확인
SELECT face_id, mem_sno, param1, param2, created_at 
FROM member_faces 
ORDER BY created_at DESC 
LIMIT 10;

-- 인식 로그 확인
SELECT log_id, mem_sno, param1, param2, similarity, matched, recognition_time 
FROM face_recognition_logs 
ORDER BY recognition_time DESC 
LIMIT 10;

-- 특정 param1, param2의 회원 수 확인
SELECT param1, param2, COUNT(*) as count 
FROM member_faces 
GROUP BY param1, param2;
```

## 6. 실제 적용 시 확인사항

### PHP 코드에서 사용
```php
// 로그인 시 세션에 저장
$_SESSION['comp_cd'] = 'COMP_A';
$_SESSION['bcoff_cd'] = 'BRANCH_01';

// 얼굴 등록 시
$client->registerFace(
    $memberId,
    $imageData,
    3,
    '회원 등록',
    $_SESSION['comp_cd'],   // param1
    $_SESSION['bcoff_cd']   // param2
);

// 체크인 시 (해당 지점만 검색)
$result = $client->recognizeFaceForCheckin(
    $imageData,
    $_SESSION['comp_cd'],
    $_SESSION['bcoff_cd']
);
```

## 7. 문제 해결

### 인식이 안 될 때
1. member_faces 테이블의 param1, param2 값 확인
2. 요청 시 전달되는 param1, param2 값 확인
3. 로그 파일에서 SQL 쿼리 확인

### 필터링이 안 될 때
1. 데이터베이스 컬럼 추가 확인
2. config.py의 DB_TYPE 설정 확인
3. SQL 쿼리 로그 확인

## 8. 성능 모니터링

- 필터링 없이 전체 조회 시 응답 시간
- 특정 지점만 필터링 시 응답 시간
- 인덱스 효과 확인 (idx_params)

## 테스트 완료 후 체크

- [ ] 모든 테스트 시나리오 통과
- [ ] 로그에 에러 없음
- [ ] 데이터베이스에 정상 저장
- [ ] 기존 기능 영향 없음
- [ ] 성능 저하 없음