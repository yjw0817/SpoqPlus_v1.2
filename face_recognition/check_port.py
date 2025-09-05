import os
from dotenv import load_dotenv

# .env 파일 로드
load_dotenv()

# 환경변수 확인
print("환경변수 확인:")
print(f"FACE_PORT 환경변수: {os.getenv('FACE_PORT')}")
print(f"기본값 적용: {os.getenv('FACE_PORT', 5002)}")

# config.py 확인
try:
    from config import Config
    config = Config()
    print(f"\nconfig.py에서:")
    print(f"PORT 설정: {config.PORT}")
    print(f"HOST 설정: {config.HOST}")
except Exception as e:
    print(f"\nconfig.py 로드 오류: {e}")

# 다른 프로세스가 포트를 사용 중인지 확인
import socket
for port in [5000, 5001, 5002]:
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    result = sock.connect_ex(('localhost', port))
    if result == 0:
        print(f"\n포트 {port}: 사용 중")
    else:
        print(f"\n포트 {port}: 사용 가능")
    sock.close()