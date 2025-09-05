# Face Recognition 통신_전문_v1.0.xlsx 수정 가이드

## 📝 수정이 필요한 항목들

### 1. 기본 설정 시트
| 항목 | 현재값 | 수정값 | 비고 |
|------|--------|--------|------|
| 서비스 포트 | 5001 | **5002** | 실제 사용 포트 |
| Base URL | http://localhost:5001 | **http://localhost:5002** | Python 서비스 |
| PHP Proxy URL | - | **/FaceTest/*** | Frontend 접근점 추가 |

### 2. API 엔드포인트 시트

#### 2.1 얼굴 등록 API
| 항목 | 현재값 | 수정값 |
|------|--------|--------|
| 파라미터명 | member_number | **member_id** |
| PHP 경로 | - | **/Ttotalmain/ajax_mgr_modify_proc** |

#### 2.2 회원 등록용 얼굴 검출 (신규 추가)
| 항목 | 값 |
|------|-----|
| 엔드포인트 | /api/face/detect_for_registration |
| PHP Proxy | /FaceTest/recognize_for_registration |
| 메소드 | POST |
| 파라미터 | image, param1, param2 |
| 용도 | 회원 등록 시 얼굴 품질 검증 |

#### 2.3 체크인용 얼굴 인식
| 항목 | 현재값 | 수정값 |
|------|--------|--------|
| PHP 경로 | - | **/FaceTest/recognize_for_checkin** |
| 추가 파라미터 | - | **check_liveness, check_blink, security_level** |

### 3. 파라미터 매핑 시트 (신규 추가 권장)

| Frontend | PHP Controller | Python API | 설명 |
|----------|----------------|------------|------|
| comp_cd | comp_cd | param1 | 회사 코드 |
| bcoff_cd | bcoff_cd | param2 | 지점 코드 |
| image_base64 | image | image | 이미지 데이터 |
| mem_sno | mem_sno | member_id | 회원 번호 |

### 4. 호출 경로 시트 (신규 추가 권장)

```
Frontend (JavaScript/Ajax)
    ↓
PHP Controller (프록시/보안/세션)
    ↓  
Python Service (InsightFace 처리)
    ↓
Database (MariaDB/MSSQL)
```

### 5. 실제 사용 경로 매트릭스

| 기능 | Frontend 파일 | PHP Controller | Python API |
|------|--------------|----------------|------------|
| 헬스체크 | - | /FaceTest/health | /api/face/health |
| 회원 얼굴 검출 | jsinc.php | /FaceTest/recognize_for_registration | /api/face/detect_for_registration |
| 회원 얼굴 등록 | info_mem.php | /Ttotalmain/ajax_mgr_modify_proc | /api/face/register |
| 체크인 인식 | checkin.php | /FaceTest/recognize_for_checkin | /api/face/recognize_for_checkin |

## 🔧 엑셀 파일 수정 방법

1. **Face Recognition 통신_전문_v1.0.xlsx** 파일을 엑셀로 열기
2. 위 표의 내용대로 각 시트 수정
3. 버전을 **v2.0**으로 업데이트
4. 파일명을 **Face Recognition 통신_전문_v2.0.xlsx**로 저장

## 📌 주의사항

- 포트 번호는 환경 변수로 관리 권장: `FACE_PORT=5002`
- 파라미터 이름 통일 필요 (image vs image_base64)
- PHP 프록시 경로는 보안상 유지 권장

## 🎯 권장 개선사항

1. **파라미터 표준화**
   - 모든 API에서 'image' 사용
   - member_number → member_id 통일
   - param1/param2 → company_code/branch_code로 명확하게 변경

2. **응답 형식 통일**
   ```json
   {
       "success": true|false,
       "message": "설명",
       "data": {},
       "error": "에러코드",
       "timestamp": "ISO 8601"
   }
   ```

3. **버전 관리**
   - API 버전 명시: /api/v2/face/*
   - 하위 호환성 유지