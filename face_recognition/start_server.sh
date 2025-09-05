#!/bin/bash

# Face Recognition Server Startup Script
# InsightFace 기반 얼굴 인식 서버 시작

echo "Starting InsightFace Face Recognition Server..."

# 현재 디렉토리 확인
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

# Python 환경 확인
if ! command -v python &> /dev/null; then
    echo "Error: Python is not installed or not in PATH"
    exit 1
fi

# 포트 설정 (.env 파일에서 읽거나 기본값 사용)
if [ -f "../.env" ]; then
    export $(grep -E '^FACE_' ../.env | xargs)
fi

FACE_PORT=${FACE_PORT:-5002}
FACE_HOST=${FACE_HOST:-localhost}

echo "Server will run on: $FACE_HOST:$FACE_PORT"

# 이미 실행 중인지 확인
if lsof -Pi :$FACE_PORT -sTCP:LISTEN -t >/dev/null ; then
    echo "Port $FACE_PORT is already in use. Stopping existing service..."
    lsof -ti :$FACE_PORT | xargs kill -9
    sleep 2
fi

# 서버 시작
echo "Starting server on port $FACE_PORT..."
python insightface_service.py

# 백그라운드로 실행하려면 아래 주석을 해제하고 위 라인을 주석처리
# nohup python insightface_service.py > insightface_service.log 2>&1 &
# echo "Server started in background. Check insightface_service.log for details."