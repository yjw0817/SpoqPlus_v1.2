#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
WSGI Entry Point for InsightFace Service
프로덕션 환경용 WSGI 엔트리 포인트
"""

import sys
import os
from pathlib import Path

# 현재 디렉토리를 Python 경로에 추가
sys.path.insert(0, str(Path(__file__).parent))

# 환경 변수 설정
os.environ.setdefault('FACE_DEBUG', 'False')

# InsightFace 서비스 초기화
from insightface_service_r1 import InsightFaceService, InsightFaceAPI
from config import Config

# 서비스 인스턴스 생성
face_service = InsightFaceService()
api = InsightFaceAPI(face_service)

# Flask 앱 인스턴스 (WSGI 서버가 사용)
application = api.app
app = application  # 일부 WSGI 서버는 'app' 이름을 찾음

if __name__ == '__main__':
    # 직접 실행 시 경고 메시지
    print("⚠️  이 파일은 WSGI 서버를 통해 실행되어야 합니다.")
    print("실행 방법:")
    print("  gunicorn wsgi:application")
    print("  또는")
    print("  python start_production.py")