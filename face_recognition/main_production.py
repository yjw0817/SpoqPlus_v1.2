#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
프로덕션 EXE용 메인 엔트리 포인트
Waitress WSGI 서버로 자동 실행
"""

import os
import sys
import multiprocessing
from pathlib import Path

# EXE 실행 시 multiprocessing 오류 방지
if __name__ == '__main__':
    multiprocessing.freeze_support()

# 환경 변수 설정 (프로덕션 모드)
os.environ['FACE_PRODUCTION'] = 'True'
os.environ['FACE_DEBUG'] = 'False'

def main():
    """메인 실행 함수"""
    try:
        # Waitress 임포트
        from waitress import serve
        
        # Flask 앱 임포트
        from insightface_service_r1 import InsightFaceService, InsightFaceAPI
        from config import Config
        
        print("=" * 60)
        print("InsightFace 프로덕션 서버 (EXE)")
        print("=" * 60)
        
        # 서비스 초기화
        print("🔧 서비스 초기화 중...")
        face_service = InsightFaceService()
        api = InsightFaceAPI(face_service)
        
        # 설정 로드
        host = Config.HOST
        port = Config.PORT
        threads = Config.THREADS if hasattr(Config, 'THREADS') else 8
        
        print(f"🚀 Waitress 서버 시작")
        print(f"📍 주소: http://{host}:{port}")
        print(f"🧵 스레드: {threads}개")
        print("=" * 60)
        print("종료하려면 Ctrl+C를 누르세요")
        print("")
        
        # Waitress 서버 실행
        serve(
            api.app,
            host=host,
            port=port,
            threads=threads,
            channel_timeout=120,
            cleanup_interval=10,
            url_scheme='http'
        )
        
    except ImportError as e:
        print(f"❌ 필수 모듈을 찾을 수 없습니다: {e}")
        print("\n필요한 패키지:")
        print("  - waitress")
        print("  - flask")
        print("  - insightface")
        input("\nEnter를 눌러 종료...")
        sys.exit(1)
        
    except Exception as e:
        print(f"❌ 오류 발생: {e}")
        import traceback
        traceback.print_exc()
        input("\nEnter를 눌러 종료...")
        sys.exit(1)

if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n👋 서버를 종료합니다.")
        sys.exit(0)