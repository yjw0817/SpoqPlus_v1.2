# -*- coding: utf-8 -*-
"""
SpoqPlus 안면인식 시스템 설정 파일
"""

import os
from dotenv import load_dotenv

# 환경변수 로드
load_dotenv()

class Config:
    """기본 설정"""
    
    # 서버 설정
    HOST = os.getenv('FACE_HOST', '0.0.0.0')
    PORT = int(os.getenv('FACE_PORT', 5002))
    DEBUG = os.getenv('FACE_DEBUG', 'False').lower() == 'true'
    
    # 프로덕션 설정
    PRODUCTION = os.getenv('FACE_PRODUCTION', 'False').lower() == 'true'
    WORKERS = int(os.getenv('FACE_WORKERS', '4'))  # Gunicorn 워커 수
    THREADS = int(os.getenv('FACE_THREADS', '8'))  # Waitress 스레드 수
    TIMEOUT = int(os.getenv('FACE_TIMEOUT', '120'))  # 요청 타임아웃 (초)
    
    # 데이터베이스 타입 설정 (mariadb 또는 mssql)
    DB_TYPE = os.getenv('DB_TYPE', 'mariadb').lower()
    
    # MariaDB/MySQL 설정
    MARIADB_CONFIG = {
        'host': os.getenv('DB_HOST', '192.168.0.48'),
        'user': os.getenv('DB_USER', 'root'),
        'password': os.getenv('DB_PASSWORD', 'spoqdb11'),
        'database': os.getenv('DB_NAME', 'spoqplusdb'),
        'charset': 'utf8mb4',
        'collation': 'utf8mb4_unicode_ci',  # MySQL 5.7 호환
        'autocommit': True,
        'connection_timeout': 10
    }
    
    # MSSQL 설정
    MSSQL_CONFIG = {
        'server': os.getenv('MSSQL_SERVER', '192.168.0.48'),
        'database': os.getenv('MSSQL_DATABASE', 'FD'),
        'username': os.getenv('MSSQL_USERNAME', 'sa'),
        'password': os.getenv('MSSQL_PASSWORD', '1qazxsw2'),
        'driver': os.getenv('MSSQL_DRIVER', 'ODBC Driver 17 for SQL Server'),
        'port': int(os.getenv('MSSQL_PORT', '1433')),
        'timeout': int(os.getenv('MSSQL_TIMEOUT', '10')),
        'encrypt': os.getenv('MSSQL_ENCRYPT', 'yes'),
        'trust_server_certificate': os.getenv('MSSQL_TRUST_CERT', 'yes')
    }
    
    # 현재 사용할 DB 설정 (DB_TYPE에 따라 결정)
    @property
    def DB_CONFIG(self):
        if self.DB_TYPE == 'mssql':
            return self.MSSQL_CONFIG
        else:
            return self.MARIADB_CONFIG
    
    # 안면인식 임계값 설정
    THRESHOLDS = {
        'face_recognition': float(os.getenv('FACE_THRESHOLD', '0.6')),
        'liveness_detection': float(os.getenv('LIVENESS_THRESHOLD', '0.6')),
        'image_quality': float(os.getenv('QUALITY_THRESHOLD', '0.7')),
        'glasses_detection': float(os.getenv('GLASSES_THRESHOLD', '0.7')),
    }
    
    # 보안 설정
    SECURITY = {
        'max_faces': int(os.getenv('MAX_FACES', '1')),
        'min_face_size': float(os.getenv('MIN_FACE_SIZE', '0.1')),
        'max_face_size': float(os.getenv('MAX_FACE_SIZE', '0.8')),
        'security_level': int(os.getenv('SECURITY_LEVEL', '3')),
        'max_attempts': int(os.getenv('MAX_ATTEMPTS', '3')),
        'session_timeout': int(os.getenv('SESSION_TIMEOUT', '1800'))  # 30분
    }
    
    # IP 접근 제어 설정
    IP_ACCESS_CONTROL = {
        'mode': os.getenv('FACE_ACCESS_MODE', 'blacklist'),  # 'open' (모두 허용), 'blacklist' (특정 IP 차단)
        'blacklist_ips': os.getenv('FACE_BLACKLIST_IPS', '216.218.206.66,35.203.210.241,3.134.148.59,37.60.241.154,159.196.176.254,184.105.139.68,45.142.154.62,165.154.125.187,3.131.215.38').split(',') if os.getenv('FACE_BLACKLIST_IPS', '216.218.206.66,35.203.210.241,3.134.148.59,37.60.241.154,159.196.176.254,184.105.139.68,45.142.154.62,165.154.125.187,3.131.215.38') else [],
        'internal_ranges': [  # 내부망 IP 대역 (항상 허용, 블랙리스트 무시)
            '192.168.0.0/16',
            '172.16.0.0/12',
            '10.0.0.0/8',
            '127.0.0.1'
        ]
    }
    
    # MediaPipe 설정
    MEDIAPIPE = {
        'face_detection_confidence': 0.7,
        'face_mesh_confidence': 0.7,
        'face_mesh_tracking': 0.5,
        'max_num_faces': 1,
        'refine_landmarks': True,
        'static_image_mode': True
    }
    
    # 파일 경로 설정
    PATHS = {
        'upload_folder': os.getenv('UPLOAD_FOLDER', './uploads'),
        'log_folder': os.getenv('LOG_FOLDER', './logs'),
        'model_folder': os.getenv('MODEL_FOLDER', './models'),
        'temp_folder': os.getenv('TEMP_FOLDER', './temp')
    }
    
    # 로깅 설정
    LOGGING = {
        'level': os.getenv('LOG_LEVEL', 'INFO'),
        'file_enabled': os.getenv('LOG_FILE_ENABLED', 'True').lower() == 'true',
        'max_file_size': int(os.getenv('LOG_MAX_SIZE', '10')) * 1024 * 1024,  # 10MB
        'backup_count': int(os.getenv('LOG_BACKUP_COUNT', '5'))
    }
    
    # 성능 설정
    PERFORMANCE = {
        'face_database_cache_size': int(os.getenv('CACHE_SIZE', '1000')),
        'processing_timeout': int(os.getenv('PROCESSING_TIMEOUT', '30')),  # 30초
        'memory_limit_mb': int(os.getenv('MEMORY_LIMIT', '512')),
        'cpu_cores': int(os.getenv('CPU_CORES', '0'))  # 0 = 자동 감지
    }
    
    # API 응답 설정
    API = {
        'cors_origins': os.getenv('CORS_ORIGINS', '*'),
        'rate_limit': os.getenv('RATE_LIMIT', '100'),  # 분당 요청 수
        'response_timeout': int(os.getenv('RESPONSE_TIMEOUT', '10'))
    }
    
    # 디버그 파일 설정
    DEBUG_OPTIONS = {
        'request_debug_enabled': os.getenv('REQUEST_DEBUG_ENABLED', 'False').lower() == 'true',  # 기본값 True로 변경하면 config에서 켬
        'debug_folder': os.getenv('REQUEST_DEBUG_FOLDER', './debug'),
        'retention_days': int(os.getenv('DEBUG_FILE_RETENTION_DAYS', '7'))
    }

class DevelopmentConfig(Config):
    """개발 환경 설정"""
    DEBUG = True
    LOGGING = {
        **Config.LOGGING,
        'level': 'DEBUG'
    }
    
class ProductionConfig(Config):
    """운영 환경 설정"""
    DEBUG = False
    LOGGING = {
        **Config.LOGGING,
        'level': 'WARNING'
    }
    SECURITY = {
        **Config.SECURITY,
        'max_attempts': 5
    }
    
class TestingConfig(Config):
    """테스트 환경 설정"""
    DEBUG = True
    MARIADB_CONFIG = {
        **Config.MARIADB_CONFIG,
        'database': 'spoqplus_test'
    }
    MSSQL_CONFIG = {
        **Config.MSSQL_CONFIG,
        'database': 'spoqplus_test'
    }

# 환경에 따른 설정 선택
config_dict = {
    'development': DevelopmentConfig,
    'production': ProductionConfig,
    'testing': TestingConfig,
    'default': DevelopmentConfig
}

def get_config():
    """현재 환경에 맞는 설정 반환"""
    env = os.getenv('FLASK_ENV', 'development')
    return config_dict.get(env, config_dict['default'])

# 설정 검증
def validate_config(config):
    """설정값 검증"""
    errors = []
    
    # 필수 디렉토리 확인
    for path_key, path_value in config.PATHS.items():
        if not os.path.exists(path_value):
            try:
                os.makedirs(path_value, exist_ok=True)
                print(f"✅ 디렉토리 생성: {path_value}")
            except Exception as e:
                errors.append(f"디렉토리 생성 실패 ({path_key}): {e}")
    
    # 임계값 범위 확인
    for threshold_key, threshold_value in config.THRESHOLDS.items():
        if not (0.0 <= threshold_value <= 1.0):
            errors.append(f"임계값 범위 오류 ({threshold_key}): {threshold_value}")
    
    # 포트 범위 확인
    if not (1024 <= config.PORT <= 65535):
        errors.append(f"포트 범위 오류: {config.PORT}")
    
    if errors:
        print("❌ 설정 검증 실패:")
        for error in errors:
            print(f"   - {error}")
        return False
    
    print("✅ 설정 검증 완료")
    return True

# 기본 설정 객체
current_config = get_config() 