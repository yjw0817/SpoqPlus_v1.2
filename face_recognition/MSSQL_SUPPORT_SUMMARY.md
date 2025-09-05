# MSSQL 지원 요약

## ✅ MSSQL 지원 구현 완료

### 1. 데이터베이스 자동 전환
```python
# config.py에서 DB_TYPE 설정
DB_TYPE = 'mssql'  # 또는 'mariadb'
```

### 2. 연결 방식 차이 처리
- **MariaDB**: mysql.connector 사용 (커넥션 풀 지원)
- **MSSQL**: pyodbc 사용 (ODBC 드라이버 필요)

### 3. SQL 문법 차이 자동 처리

#### 파라미터 플레이스홀더
- MariaDB: `%s`
- MSSQL: `?`

#### 날짜 함수
- MariaDB: `NOW()`
- MSSQL: `GETDATE()`

#### LIMIT 구문
- MariaDB: `LIMIT 1`
- MSSQL: `TOP 1`

#### 예시:
```python
# 자동으로 DB 타입에 맞게 변환
if self.db_type == 'mssql':
    cursor.execute("""
        SELECT TOP 1 face_id FROM member_faces 
        WHERE mem_sno = ? AND param1 = ? AND param2 = ?
    """, (member_id, param1, param2))
else:
    cursor.execute("""
        SELECT face_id FROM member_faces 
        WHERE mem_sno = %s AND param1 = %s AND param2 = %s
        LIMIT 1
    """, (member_id, param1, param2))
```

### 4. Cursor 타입 차이 처리

#### MariaDB (Dictionary Cursor)
```python
cursor = connection.cursor(dictionary=True)
# 결과: {'face_id': 1, 'mem_sno': 'MEM001', ...}
face_id = face['face_id']
```

#### MSSQL (Tuple Cursor)
```python
cursor = connection.cursor()
# 결과: (1, 'MEM001', ...)
face_id = face[0]  # 인덱스로 접근
```

### 5. 자동 변환 헬퍼 메서드
```python
def _row_to_dict(self, cursor, row):
    """MSSQL row를 dictionary로 변환"""
    if row is None:
        return None
    columns = [column[0] for column in cursor.description]
    return dict(zip(columns, row))
```

### 6. param1/param2 필터링 (MSSQL도 지원)
```python
# MSSQL에서도 param1, param2 필터링 완벽 지원
if self.db_type == 'mssql':
    cursor.execute("""
        SELECT face_id, mem_sno, face_encoding
        FROM member_faces
        WHERE is_active = 1
        AND param1 = ? AND param2 = ?
    """, (param1, param2))
```

## 🔧 MSSQL 사용 시 필요 사항

### 1. ODBC Driver 설치
```bash
# Windows
https://go.microsoft.com/fwlink/?linkid=2249004

# Linux (Ubuntu)
sudo apt-get install unixodbc-dev
curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
sudo apt-get update
sudo ACCEPT_EULA=Y apt-get install msodbcsql17
```

### 2. pyodbc 설치
```bash
pip install pyodbc
```

### 3. config.py 설정
```python
# MSSQL 설정
DB_TYPE = 'mssql'

MSSQL_CONFIG = {
    'server': '192.168.0.48',
    'database': 'FD',
    'username': 'sa',
    'password': 'your_password',
    'driver': 'ODBC Driver 17 for SQL Server',
    'port': 1433,
    'timeout': 10,
    'encrypt': 'yes',
    'trust_server_certificate': 'yes'
}
```

### 4. 테이블 생성 (MSSQL)
```sql
-- MSSQL용 테이블 생성 스크립트
CREATE TABLE member_faces (
    face_id INT IDENTITY(1,1) PRIMARY KEY,
    mem_sno VARCHAR(20) NOT NULL,
    face_encoding TEXT NOT NULL,
    glasses_detected TINYINT DEFAULT 0,
    is_active TINYINT DEFAULT 1,
    registered_date DATETIME DEFAULT GETDATE(),
    last_updated DATETIME DEFAULT GETDATE(),
    param1 VARCHAR(20),
    param2 VARCHAR(20),
    notes TEXT
);

CREATE INDEX idx_mem_sno ON member_faces(mem_sno);
CREATE INDEX idx_params ON member_faces(param1, param2);

CREATE TABLE face_recognition_logs (
    log_id INT IDENTITY(1,1) PRIMARY KEY,
    mem_sno VARCHAR(20),
    recognition_time DATETIME DEFAULT GETDATE(),
    similarity_score FLOAT,
    success TINYINT,
    param1 VARCHAR(20),
    param2 VARCHAR(20),
    processing_time_ms INT
);

CREATE INDEX idx_log_params ON face_recognition_logs(param1, param2);
```

## 📊 테스트 확인

### 1. 연결 테스트
```python
python insightface_service.py
# 로그에서 "✅ MSSQL 연결 테스트 성공" 확인
```

### 2. 데이터 확인 (MSSQL)
```sql
-- 등록된 얼굴 확인
SELECT TOP 10 * FROM member_faces ORDER BY registered_date DESC;

-- 인식 로그 확인
SELECT TOP 10 * FROM face_recognition_logs ORDER BY recognition_time DESC;
```

### 3. 성능 비교
- MariaDB: 커넥션 풀 사용으로 빠른 응답
- MSSQL: 개별 연결이지만 안정적

## ✅ 결론
MSSQL과 MariaDB 모두 완벽하게 지원되며, config.py의 DB_TYPE 설정만으로 자동 전환됩니다. param1/param2 필터링도 두 데이터베이스 모두에서 정상 작동합니다.