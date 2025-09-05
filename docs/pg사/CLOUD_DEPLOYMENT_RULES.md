# SPOQ Plus 클라우드 배포 규칙 및 가이드

## 📋 목차
1. [클라우드 제공자별 규칙](#클라우드-제공자별-규칙)
2. [보안 규칙](#보안-규칙)
3. [네트워크 구성](#네트워크-구성)
4. [데이터베이스 규칙](#데이터베이스-규칙)
5. [애플리케이션 배포 규칙](#애플리케이션-배포-규칙)
6. [모니터링 및 로깅](#모니터링-및-로깅)
7. [비용 최적화](#비용-최적화)
8. [재해 복구](#재해-복구)

## 🌩️ 클라우드 제공자별 규칙

### AWS (Amazon Web Services)

#### EC2 인스턴스 규칙
```yaml
# EC2 보안 그룹 규칙
SecurityGroups:
  WebServer:
    Inbound:
      - Protocol: TCP
        Port: 443
        Source: 0.0.0.0/0  # HTTPS
      - Protocol: TCP
        Port: 80
        Source: 0.0.0.0/0  # HTTP (리다이렉트용)
      - Protocol: TCP
        Port: 22
        Source: <관리자 IP>/32  # SSH (제한된 IP만)
    Outbound:
      - Protocol: ALL
        Destination: 0.0.0.0/0

  ApplicationServer:
    Inbound:
      - Protocol: TCP
        Port: 8080
        Source: <웹서버 보안그룹>  # 내부 통신만
      - Protocol: TCP
        Port: 5001
        Source: <웹서버 보안그룹>  # Python Face Recognition
    Outbound:
      - Protocol: ALL
        Destination: 0.0.0.0/0

  DatabaseServer:
    Inbound:
      - Protocol: TCP
        Port: 3306
        Source: <앱서버 보안그룹>  # MySQL
    Outbound:
      - Protocol: TCP
        Port: 443
        Destination: 0.0.0.0/0  # AWS 서비스 통신
```

#### RDS 규칙
```yaml
RDS:
  Engine: MySQL 8.0
  InstanceClass: db.t3.medium  # 최소 사양
  Storage:
    Type: gp3
    Size: 100GB
    IOPS: 3000
  Backup:
    RetentionPeriod: 7  # 7일 백업 보관
    PreferredWindow: "03:00-04:00"  # KST 12:00-13:00
  Encryption: true
  MultiAZ: true  # 고가용성
```

### Azure

#### 네트워크 보안 그룹 (NSG) 규칙
```json
{
  "webNSG": {
    "inboundRules": [
      {
        "name": "HTTPS",
        "priority": 100,
        "protocol": "TCP",
        "sourcePort": "*",
        "destinationPort": "443",
        "sourceAddress": "*",
        "destinationAddress": "*",
        "access": "Allow"
      },
      {
        "name": "HTTP",
        "priority": 110,
        "protocol": "TCP",
        "sourcePort": "*",
        "destinationPort": "80",
        "sourceAddress": "*",
        "destinationAddress": "*",
        "access": "Allow"
      }
    ]
  },
  "appNSG": {
    "inboundRules": [
      {
        "name": "FromWebTier",
        "priority": 100,
        "protocol": "TCP",
        "sourcePort": "*",
        "destinationPort": "8080",
        "sourceAddress": "10.0.1.0/24",
        "destinationAddress": "*",
        "access": "Allow"
      }
    ]
  }
}
```

### GCP (Google Cloud Platform)

#### 방화벽 규칙
```yaml
firewall_rules:
  - name: allow-https
    direction: INGRESS
    priority: 1000
    targetTags: ["web-server"]
    sourceRanges: ["0.0.0.0/0"]
    allowed:
      - protocol: tcp
        ports: ["443"]

  - name: allow-internal
    direction: INGRESS
    priority: 1000
    targetTags: ["app-server"]
    sourceTags: ["web-server"]
    allowed:
      - protocol: tcp
        ports: ["8080", "5001"]

  - name: allow-db
    direction: INGRESS
    priority: 1000
    targetTags: ["db-server"]
    sourceTags: ["app-server"]
    allowed:
      - protocol: tcp
        ports: ["3306"]
```

## 🔒 보안 규칙

### 1. 접근 제어
```yaml
AccessControl:
  PublicAccess:
    - LoadBalancer: 443 (HTTPS only)
    - CDN: Static assets only
  
  PrivateAccess:
    - ApplicationServers: VPC/VNet 내부만
    - Database: Private subnet only
    - FaceRecognitionService: Internal only
  
  AdminAccess:
    - SSH/RDP: IP 화이트리스트
    - DatabaseDirect: VPN only
    - ManagementConsole: MFA required
```

### 2. 암호화 규칙
```yaml
Encryption:
  InTransit:
    - TLS 1.2 minimum
    - HTTPS everywhere
    - Database SSL required
  
  AtRest:
    - Database: AES-256
    - Storage: Provider managed keys
    - Backups: Encrypted
  
  Secrets:
    - Use managed secret services (AWS Secrets Manager, Azure Key Vault, GCP Secret Manager)
    - Rotate keys every 90 days
    - No hardcoded credentials
```

### 3. 인증 및 권한
```yaml
Authentication:
  Application:
    - JWT tokens with 1 hour expiry
    - Refresh tokens with 7 day expiry
    - Session timeout: 30 minutes
  
  Database:
    - Separate users for app/admin/backup
    - Principle of least privilege
    - No root access from application
  
  API:
    - API key required
    - Rate limiting: 100 requests/minute
    - IP whitelist for production
```

## 🌐 네트워크 구성

### VPC/VNet 설계
```yaml
Network:
  CIDR: 10.0.0.0/16
  
  Subnets:
    Public:
      - WebTier: 10.0.1.0/24
      - NAT Gateway: 10.0.2.0/24
    
    Private:
      - AppTier: 10.0.10.0/24
      - DatabaseTier: 10.0.20.0/24
      - FaceRecognition: 10.0.30.0/24
    
  RouteTables:
    Public:
      - 0.0.0.0/0 -> Internet Gateway
    Private:
      - 0.0.0.0/0 -> NAT Gateway
```

### 로드 밸런서 규칙
```yaml
LoadBalancer:
  Type: Application Load Balancer
  
  Listeners:
    - Port: 443
      Protocol: HTTPS
      Certificate: ACM/LetsEncrypt
      DefaultAction: Forward to target group
    
    - Port: 80
      Protocol: HTTP
      DefaultAction: Redirect to HTTPS
  
  TargetGroups:
    WebServers:
      Port: 8080
      Protocol: HTTP
      HealthCheck:
        Path: /health
        Interval: 30s
        Timeout: 5s
        HealthyThreshold: 2
        UnhealthyThreshold: 3
  
  StickySessions:
    Enabled: true
    Duration: 3600  # 1 hour
```

## 🗄️ 데이터베이스 규칙

### MySQL 구성
```yaml
Database:
  Version: MySQL 8.0
  
  Performance:
    InstanceType: 
      Production: db.r5.xlarge
      Development: db.t3.medium
    
    Parameters:
      max_connections: 500
      innodb_buffer_pool_size: 70% of RAM
      query_cache_size: 256M
      slow_query_log: ON
      long_query_time: 2
  
  Security:
    - Enable SSL/TLS
    - Restrict access to application subnet
    - Enable audit logging
    - Disable remote root login
  
  Backup:
    Type: Automated
    Retention: 7 days
    Window: 03:00-04:00 KST
    PointInTimeRecovery: Enabled
```

### 읽기 전용 복제본
```yaml
ReadReplicas:
  Count: 2
  Regions:
    - Primary region
    - DR region
  
  Usage:
    - Reporting queries
    - Analytics
    - Backup source
```

## 🚀 애플리케이션 배포 규칙

### 컨테이너화 (Docker)
```dockerfile
# Web Application
FROM php:8.0-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY . /var/www/html
RUN a2enmod rewrite ssl

# Face Recognition Service
FROM python:3.9-slim
RUN pip install --no-cache-dir flask opencv-python mediapipe
COPY face_recognition /app
CMD ["python", "/app/enhanced_face_service.py"]
```

### Kubernetes 배포
```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: spoqplus-web
spec:
  replicas: 3
  selector:
    matchLabels:
      app: spoqplus-web
  template:
    metadata:
      labels:
        app: spoqplus-web
    spec:
      containers:
      - name: web
        image: spoqplus/web:latest
        ports:
        - containerPort: 80
        env:
        - name: DB_HOST
          valueFrom:
            secretKeyRef:
              name: db-secret
              key: host
        resources:
          requests:
            memory: "256Mi"
            cpu: "250m"
          limits:
            memory: "512Mi"
            cpu: "500m"
---
apiVersion: v1
kind: Service
metadata:
  name: spoqplus-web-service
spec:
  selector:
    app: spoqplus-web
  ports:
  - port: 80
    targetPort: 80
  type: LoadBalancer
```

### CI/CD 파이프라인
```yaml
CI/CD:
  Source:
    - GitHub/GitLab/Bitbucket
    - Branch protection rules
    - Code review required
  
  Build:
    - Automated testing
    - Security scanning
    - Docker image build
    - Push to registry
  
  Deploy:
    Staging:
      - Automatic on develop branch
      - Smoke tests
      - Manual approval for production
    
    Production:
      - Blue-green deployment
      - Canary releases (10% -> 50% -> 100%)
      - Automatic rollback on failure
```

## 📊 모니터링 및 로깅

### 모니터링 규칙
```yaml
Monitoring:
  Infrastructure:
    - CPU usage < 80%
    - Memory usage < 85%
    - Disk usage < 90%
    - Network latency < 100ms
  
  Application:
    - Response time < 500ms (p95)
    - Error rate < 1%
    - Successful login rate > 95%
    - Face recognition success rate > 90%
  
  Database:
    - Connection count < 80% of max
    - Query time < 1s (p95)
    - Replication lag < 1s
    - Lock wait time < 100ms
  
  Alerts:
    Critical:
      - Service down
      - Database connection failure
      - Disk space < 10%
    
    Warning:
      - High CPU/Memory usage
      - Slow queries
      - Failed login attempts > 10/min
```

### 로깅 규칙
```yaml
Logging:
  Application:
    - All errors and warnings
    - Authentication events
    - Face recognition attempts
    - API calls
  
  Access:
    - All HTTP requests
    - Source IP, User-Agent
    - Response codes and times
  
  Security:
    - Failed login attempts
    - Permission denied events
    - Configuration changes
    - SSH/RDP access
  
  Retention:
    - Application logs: 30 days
    - Access logs: 90 days
    - Security logs: 1 year
    - Audit logs: 7 years
```

## 💰 비용 최적화

### 자동 스케일링 규칙
```yaml
AutoScaling:
  Web/App Tier:
    MinInstances: 2
    MaxInstances: 10
    TargetCPU: 70%
    ScaleUpCooldown: 300s
    ScaleDownCooldown: 600s
  
  Schedule:
    BusinessHours:  # KST 9-22
      MinInstances: 3
    OffHours:
      MinInstances: 2
    
  FaceRecognition:
    MinInstances: 1
    MaxInstances: 5
    TargetQueueDepth: 10
```

### 리소스 태깅
```yaml
Tags:
  Required:
    - Environment: prod/staging/dev
    - Project: spoqplus
    - Owner: team-name
    - CostCenter: department-code
    - CreatedDate: YYYY-MM-DD
  
  Optional:
    - Purpose: web/app/db/ml
    - Schedule: always-on/business-hours
    - Compliance: required/optional
```

## 🔥 재해 복구

### 백업 전략
```yaml
Backup:
  Database:
    - Full backup: Daily at 03:00 KST
    - Incremental: Every 6 hours
    - Transaction logs: Continuous
    - Retention: 7 days local, 30 days remote
  
  Application:
    - Code: Git repository
    - Configurations: Encrypted in secret manager
    - User uploads: S3/Blob with versioning
  
  FaceData:
    - Encodings: Daily backup
    - Images: Incremental with deduplication
    - Retention: As per privacy policy
```

### DR 계획
```yaml
DisasterRecovery:
  RTO: 4 hours  # Recovery Time Objective
  RPO: 1 hour   # Recovery Point Objective
  
  Strategy:
    Primary: Seoul Region
    Secondary: Tokyo Region
    
    Replication:
      - Database: Async replication
      - Files: Cross-region replication
      - Configuration: Multi-region secrets
    
    Failover:
      - DNS: Route53/Traffic Manager
      - Database: Promote read replica
      - Cache: Rebuild from database
```

## 📋 체크리스트

### 배포 전 체크리스트
- [ ] 모든 보안 그룹/NSG 규칙 검토
- [ ] SSL 인증서 설치 및 검증
- [ ] 환경 변수 및 시크릿 설정
- [ ] 데이터베이스 백업 설정 확인
- [ ] 모니터링 및 알림 설정
- [ ] 로드 테스트 수행
- [ ] 보안 스캔 완료
- [ ] DR 계획 테스트

### 운영 체크리스트 (일일)
- [ ] 시스템 상태 대시보드 확인
- [ ] 에러 로그 검토
- [ ] 백업 성공 여부 확인
- [ ] 보안 이벤트 검토
- [ ] 리소스 사용률 확인

### 월간 체크리스트
- [ ] 보안 패치 적용
- [ ] 비용 분석 및 최적화
- [ ] 용량 계획 검토
- [ ] DR 훈련 실시
- [ ] 접근 권한 감사


## Standard Workflow

1. First think through the problem, read the codebase for relevant files, and write a plan to tasks/todo.md.
2. The plan should have a list of todo items that you can check off as you complete them
3. Before you begin working, check in with me and I will verify the plan.
4. Then, begin working on the todo items, marking them as complete as you go.
5. Please every step of the way just give me a high level explanation of what changes you made
6. Make every task and code change you do as simple as possible. We want to avoid making any massive or complex changes. Every change should impact as little code as possible. Everything is about simplicity.
7. Finally, add a review section to the todo.md file with a summary of the changes you made and any other relevant information.
---

**작성일**: 2025년 1월  
**버전**: 1.0  
**다음 검토일**: 2025년 4월