#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
통합 EXE 엔트리 포인트
실행 인자에 따라 개발/프로덕션 모드 선택
"""

import os
import sys
import multiprocessing
from pathlib import Path

# EXE 실행 시 multiprocessing 오류 방지
if __name__ == '__main__':
    multiprocessing.freeze_support()

def run_development():
    """개발 서버 실행"""
    print("🔧 개발 서버 모드")
    from insightface_service_r1 import InsightFaceService, InsightFaceAPI
    from config import Config
    
    face_service = InsightFaceService()
    api = InsightFaceAPI(face_service)
    
    HOST = Config.HOST
    PORT = Config.PORT
    DEBUG = True
    
    print(f"📍 개발 서버: http://{HOST}:{PORT}")
    api.run(host=HOST, port=PORT, debug=DEBUG)

def run_production():
    """프로덕션 서버 실행"""
    print("🚀 프로덕션 서버 모드")
    
    try:
        from waitress import serve
    except ImportError:
        print("❌ Waitress가 설치되지 않았습니다.")
        print("개발 서버로 대체 실행합니다...")
        run_development()
        return
    
    from insightface_service_r1 import InsightFaceService, InsightFaceAPI
    from config import Config
    
    face_service = InsightFaceService()
    api = InsightFaceAPI(face_service)
    
    host = Config.HOST
    port = Config.PORT
    threads = Config.THREADS if hasattr(Config, 'THREADS') else 8
    
    print(f"📍 프로덕션 서버: http://{host}:{port}")
    print(f"🧵 스레드: {threads}개")
    
    serve(
        api.app,
        host=host,
        port=port,
        threads=threads,
        channel_timeout=120,
        cleanup_interval=10,
        url_scheme='http'
    )

def main():
    """메인 실행 함수"""
    print("=" * 60)
    print("InsightFace 서비스")
    print("=" * 60)
    
    # 실행 인자 확인
    if len(sys.argv) > 1:
        mode = sys.argv[1].lower()
        if mode in ['dev', 'development']:
            run_development()
        elif mode in ['prod', 'production']:
            run_production()
        else:
            print(f"❌ 알 수 없는 모드: {mode}")
            print("사용법: InsightFaceService.exe [dev|prod]")
            sys.exit(1)
    else:
        # 기본값: 프로덕션 모드
        print("모드가 지정되지 않음. 프로덕션 모드로 실행합니다.")
        print("개발 모드: InsightFaceService.exe dev")
        print("프로덕션 모드: InsightFaceService.exe prod")
        print("")
        run_production()

if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n👋 서버를 종료합니다.")
        sys.exit(0)
    except Exception as e:
        print(f"❌ 오류 발생: {e}")
        import traceback
        traceback.print_exc()
        input("\nEnter를 눌러 종료...")
        sys.exit(1)