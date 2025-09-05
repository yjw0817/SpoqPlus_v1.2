# SPOQ Plus 백업 및 재해 복구 전략

## 📋 목차
1. [백업 정책](#백업-정책)
2. [백업 구성 요소](#백업-구성-요소)
3. [백업 스케줄](#백업-스케줄)
4. [복구 절차](#복구-절차)
5. [테스트 계획](#테스트-계획)
6. [자동화 스크립트](#자동화-스크립트)

## 🎯 백업 정책

### 3-2-1 규칙
- **3개**의 데이터 복사본 유지
- **2개**의 서로 다른 미디어에 저장
- **1개**의 오프사이트 백업

### RTO/RPO 목표
- **RTO (Recovery Time Objective)**: 4시간
- **RPO (Recovery Point Objective)**: 1시간

## 🗄️ 백업 구성 요소

### 1. 데이터베이스 백업

#### MySQL 백업 전략
```bash
# 전체 백업 (매일 03:00 KST)
mysqldump --single-transaction --routines --triggers --all-databases > full_backup_$(date +%Y%m%d).sql

# 증분 백업 (6시간마다)
mysqlbinlog --start-datetime="$(date -d '6 hours ago' '+%Y-%m-%d %H:%M:%S')" > incremental_$(date +%Y%m%d_%H%M%S).sql

# 특정 테이블 백업 (중요 데이터)
mysqldump spoqplus member_faces face_recognition_logs > critical_tables_$(date +%Y%m%d).sql
```

#### 백업 압축 및 암호화
```bash
# 압축
gzip -9 full_backup_$(date +%Y%m%d).sql

# 암호화
openssl enc -aes-256-cbc -salt -in backup.sql.gz -out backup.sql.gz.enc -k $BACKUP_PASSWORD
```

### 2. 파일 시스템 백업

#### 업로드 파일
```bash
# rsync를 이용한 증분 백업
rsync -avz --delete /var/www/html/public/uploads/ /backup/uploads/

# tar를 이용한 전체 백업
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz /var/www/html/public/uploads/
```

#### 얼굴 인식 데이터
```bash
# 얼굴 이미지 백업 (일일)
aws s3 sync /var/www/html/face_images/ s3://spoqplus-backup/face-images/ --delete

# 얼굴 인코딩 데이터 백업
mysqldump spoqplus member_faces --where="is_active=1" > face_encodings_$(date +%Y%m%d).sql
```

### 3. 설정 파일 백업

#### 애플리케이션 설정
```bash
# Git 저장소로 버전 관리
git add -A
git commit -m "Backup configuration $(date +%Y-%m-%d)"
git push origin backup-$(date +%Y%m%d)

# 환경 변수 백업 (암호화)
env | grep -E '^(DB_|APP_|AWS_)' | gpg -e -r backup@spoqplus.com > env_backup_$(date +%Y%m%d).gpg
```

## 📅 백업 스케줄

### 일일 백업 (03:00 KST)
```cron
0 3 * * * /opt/spoqplus/scripts/daily_backup.sh
```

### 시간별 백업
```cron
0 */6 * * * /opt/spoqplus/scripts/incremental_backup.sh
```

### 주간 백업 (일요일 04:00 KST)
```cron
0 4 * * 0 /opt/spoqplus/scripts/weekly_backup.sh
```

### 월간 백업 (매월 1일 05:00 KST)
```cron
0 5 1 * * /opt/spoqplus/scripts/monthly_backup.sh
```

## 🔄 복구 절차

### 1. 데이터베이스 복구

#### 전체 복구
```bash
# 1. MySQL 서비스 중지
systemctl stop mysql

# 2. 기존 데이터 백업
mv /var/lib/mysql /var/lib/mysql.old

# 3. MySQL 초기화
mysql_install_db --user=mysql

# 4. MySQL 시작
systemctl start mysql

# 5. 백업 복원
gunzip < full_backup_20250101.sql.gz | mysql -u root -p

# 6. 증분 백업 적용
mysqlbinlog incremental_*.sql | mysql -u root -p
```

#### 특정 테이블 복구
```bash
# 특정 테이블만 복원
mysql -u root -p spoqplus < member_faces_backup.sql
```

### 2. 파일 시스템 복구

```bash
# 업로드 파일 복구
tar -xzf uploads_backup_20250101.tar.gz -C /

# 권한 설정
chown -R www-data:www-data /var/www/html/public/uploads
chmod -R 755 /var/www/html/public/uploads
```

### 3. 재해 복구 (DR)

#### AWS 기반 DR
```bash
# 1. DR 지역으로 전환
aws route53 change-resource-record-sets --hosted-zone-id Z123456 --change-batch file://dr-failover.json

# 2. DR RDS 승격
aws rds promote-read-replica --db-instance-identifier spoqplus-dr-replica

# 3. 애플리케이션 서버 시작
terraform apply -var="region=ap-northeast-1" -var="environment=dr"
```

## 🧪 테스트 계획

### 월간 복구 테스트
```bash
#!/bin/bash
# recovery_test.sh

# 1. 테스트 환경 준비
docker-compose -f docker-compose.test.yml up -d

# 2. 백업 복원
./restore_backup.sh test-environment latest

# 3. 데이터 검증
./verify_data.sh

# 4. 기능 테스트
./run_tests.sh

# 5. 결과 리포트
./generate_recovery_report.sh
```

### 검증 체크리스트
- [ ] 데이터베이스 무결성 확인
- [ ] 모든 테이블 레코드 수 일치
- [ ] 업로드 파일 접근 가능
- [ ] 얼굴 인식 기능 정상 작동
- [ ] 로그인 및 세션 관리 정상
- [ ] 결제 시스템 연동 확인

## 🤖 자동화 스크립트

### 통합 백업 스크립트
```bash
#!/bin/bash
# /opt/spoqplus/scripts/backup_all.sh

set -euo pipefail

# 설정
BACKUP_DIR="/backup/spoqplus"
S3_BUCKET="s3://spoqplus-backup"
DATE=$(date +%Y%m%d_%H%M%S)
LOG_FILE="/var/log/spoqplus/backup_${DATE}.log"

# 로깅 함수
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# 백업 시작
log "백업 시작..."

# 1. 데이터베이스 백업
log "데이터베이스 백업 중..."
mysqldump --single-transaction \
    --routines \
    --triggers \
    --events \
    --all-databases | gzip -9 > "${BACKUP_DIR}/mysql_${DATE}.sql.gz"

# 2. 파일 시스템 백업
log "파일 시스템 백업 중..."
tar -czf "${BACKUP_DIR}/files_${DATE}.tar.gz" \
    /var/www/html/public/uploads \
    /var/www/html/writable/logs \
    --exclude="*.tmp"

# 3. 설정 백업
log "설정 파일 백업 중..."
tar -czf "${BACKUP_DIR}/config_${DATE}.tar.gz" \
    /var/www/html/.env \
    /var/www/html/app/Config \
    /etc/nginx/sites-available

# 4. S3 업로드
log "S3로 업로드 중..."
aws s3 sync "$BACKUP_DIR" "$S3_BUCKET/daily/" \
    --exclude "*" \
    --include "*_${DATE}*"

# 5. 오래된 백업 정리
log "오래된 백업 정리 중..."
find "$BACKUP_DIR" -name "*.gz" -mtime +7 -delete
aws s3 ls "$S3_BUCKET/daily/" | \
    awk '$1 < "'$(date -d '30 days ago' '+%Y-%m-%d')'" {print $4}' | \
    xargs -I {} aws s3 rm "$S3_BUCKET/daily/{}"

# 6. 백업 검증
log "백업 검증 중..."
if gzip -t "${BACKUP_DIR}/mysql_${DATE}.sql.gz"; then
    log "데이터베이스 백업 검증 성공"
else
    log "데이터베이스 백업 검증 실패!"
    exit 1
fi

# 7. 알림 전송
log "백업 완료. 알림 전송 중..."
curl -X POST https://hooks.slack.com/services/YOUR/WEBHOOK/URL \
    -H 'Content-type: application/json' \
    -d '{
        "text": "✅ SPOQ Plus 백업 완료",
        "attachments": [{
            "color": "good",
            "fields": [
                {"title": "날짜", "value": "'${DATE}'", "short": true},
                {"title": "크기", "value": "'$(du -sh ${BACKUP_DIR} | cut -f1)'", "short": true}
            ]
        }]
    }'

log "백업 완료!"
```

### 복구 스크립트
```bash
#!/bin/bash
# /opt/spoqplus/scripts/restore_backup.sh

set -euo pipefail

# 파라미터 확인
if [ $# -ne 2 ]; then
    echo "Usage: $0 <environment> <backup_date>"
    echo "Example: $0 production 20250101"
    exit 1
fi

ENVIRONMENT=$1
BACKUP_DATE=$2
BACKUP_DIR="/backup/spoqplus"
S3_BUCKET="s3://spoqplus-backup"

# 복구 함수
restore_database() {
    echo "데이터베이스 복구 중..."
    
    # S3에서 백업 다운로드
    aws s3 cp "${S3_BUCKET}/daily/mysql_${BACKUP_DATE}.sql.gz" /tmp/
    
    # 복구
    gunzip < "/tmp/mysql_${BACKUP_DATE}.sql.gz" | mysql -u root -p
    
    echo "데이터베이스 복구 완료"
}

restore_files() {
    echo "파일 시스템 복구 중..."
    
    # S3에서 백업 다운로드
    aws s3 cp "${S3_BUCKET}/daily/files_${BACKUP_DATE}.tar.gz" /tmp/
    
    # 복구
    tar -xzf "/tmp/files_${BACKUP_DATE}.tar.gz" -C /
    
    # 권한 설정
    chown -R www-data:www-data /var/www/html/public/uploads
    
    echo "파일 시스템 복구 완료"
}

# 메인 실행
case $ENVIRONMENT in
    production)
        restore_database
        restore_files
        ;;
    test)
        restore_database
        ;;
    *)
        echo "Unknown environment: $ENVIRONMENT"
        exit 1
        ;;
esac

echo "복구 완료!"
```

## 📊 모니터링 및 알림

### 백업 모니터링
```yaml
# Prometheus 알림 규칙
groups:
  - name: backup_alerts
    rules:
      - alert: BackupFailed
        expr: backup_last_success_timestamp < (time() - 86400)
        for: 1h
        labels:
          severity: critical
        annotations:
          summary: "백업 실패"
          description: "24시간 이상 백업이 수행되지 않았습니다."
      
      - alert: BackupSizeTooSmall
        expr: backup_size_bytes < 1073741824  # 1GB
        for: 15m
        labels:
          severity: warning
        annotations:
          summary: "백업 크기 이상"
          description: "백업 크기가 비정상적으로 작습니다."
```

## 📝 문서화

### 복구 매뉴얼
1. **긴급 연락처**
   - DBA: +82-10-XXXX-XXXX
   - 시스템 관리자: +82-10-XXXX-XXXX
   - AWS 지원: +1-XXX-XXX-XXXX

2. **복구 우선순위**
   1. 데이터베이스 (회원 정보, 결제 정보)
   2. 얼굴 인식 데이터
   3. 업로드 파일
   4. 로그 파일

3. **복구 시간 목표**
   - 부분 장애: 1시간 이내
   - 전체 장애: 4시간 이내
   - 데이터 손실: 최대 1시간

---

**작성일**: 2025년 1월  
**버전**: 1.0  
**다음 검토일**: 2025년 4월