#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
프로덕션 서버 시작 스크립트
OS에 따라 적절한 WSGI 서버를 자동 선택하여 실행
"""

import os
import sys
import platform
import multiprocessing
from pathlib import Path

# 프로젝트 경로 설정
BASE_DIR = Path(__file__).parent
sys.path.insert(0, str(BASE_DIR))

def get_optimal_workers():
    """최적의 워커 수 계산 (CPU 코어 * 2 + 1)"""
    try:
        cpu_count = multiprocessing.cpu_count()
        return min(cpu_count * 2 + 1, 8)  # 최대 8개로 제한
    except:
        return 4  # 기본값

def start_gunicorn():
    """Gunicorn으로 서버 시작 (Linux/Mac)"""
    workers = get_optimal_workers()
    host = os.getenv('FACE_HOST', '0.0.0.0')
    port = os.getenv('FACE_PORT', '5002')
    
    print(f"🚀 Gunicorn 프로덕션 서버 시작")
    print(f"📍 주소: http://{host}:{port}")
    print(f"👷 워커: {workers}개")
    print("-" * 50)
    
    os.system(f"""
        gunicorn wsgi:application \
        --workers {workers} \
        --worker-class sync \
        --bind {host}:{port} \
        --timeout 120 \
        --keep-alive 5 \
        --max-requests 1000 \
        --max-requests-jitter 50 \
        --access-logfile - \
        --error-logfile - \
        --log-level info \
        --reload
    """)

def start_waitress():
    """Waitress로 서버 시작 (Windows)"""
    from waitress import serve
    from wsgi import application
    
    host = os.getenv('FACE_HOST', '0.0.0.0')
    port = int(os.getenv('FACE_PORT', '5002'))
    threads = get_optimal_workers() * 2
    
    print(f"🚀 Waitress 프로덕션 서버 시작")
    print(f"📍 주소: http://{host}:{port}")
    print(f"🧵 스레드: {threads}개")
    print("-" * 50)
    
    serve(
        application,
        host=host,
        port=port,
        threads=threads,
        channel_timeout=120,
        cleanup_interval=10,
        url_scheme='http'
    )

def main():
    """메인 실행 함수"""
    system = platform.system()
    
    print("=" * 50)
    print("InsightFace 프로덕션 서버")
    print(f"시스템: {system}")
    print(f"Python: {sys.version}")
    print("=" * 50)
    
    # Gunicorn 사용 가능 여부 확인
    try:
        import gunicorn
        has_gunicorn = True
    except ImportError:
        has_gunicorn = False
    
    # Waitress 사용 가능 여부 확인
    try:
        import waitress
        has_waitress = True
    except ImportError:
        has_waitress = False
    
    # OS에 따라 적절한 서버 선택
    if system in ['Linux', 'Darwin']:  # Linux 또는 Mac
        if has_gunicorn:
            start_gunicorn()
        elif has_waitress:
            print("⚠️  Gunicorn이 설치되지 않음. Waitress를 사용합니다.")
            start_waitress()
        else:
            print("❌ WSGI 서버가 설치되지 않았습니다.")
            print("다음 명령어로 설치하세요:")
            print("  pip install gunicorn")
            print("  또는")
            print("  pip install waitress")
            sys.exit(1)
    
    elif system == 'Windows':
        if has_waitress:
            start_waitress()
        else:
            print("❌ Waitress가 설치되지 않았습니다.")
            print("다음 명령어로 설치하세요:")
            print("  pip install waitress")
            sys.exit(1)
    
    else:
        print(f"⚠️  알 수 없는 시스템: {system}")
        if has_waitress:
            print("Waitress를 사용합니다.")
            start_waitress()
        else:
            print("❌ 지원되는 WSGI 서버가 없습니다.")
            sys.exit(1)

if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n👋 서버를 종료합니다.")
        sys.exit(0)
    except Exception as e:
        print(f"\n❌ 오류 발생: {e}")
        import traceback
        traceback.print_exc()
        sys.exit(1)