#!/bin/bash
# InsightFace 프로덕션 서버 시작 스크립트 (Linux/Mac)

# 스크립트 디렉토리로 이동
cd "$(dirname "$0")"

# 환경 변수 로드
if [ -f ../.env ]; then
    export $(cat ../.env | grep -v '^#' | xargs)
fi

# 기본값 설정
HOST=${FACE_HOST:-0.0.0.0}
PORT=${FACE_PORT:-5002}

# CPU 코어 수 기반 워커 수 계산
WORKERS=$(($(nproc) * 2 + 1))
if [ $WORKERS -gt 8 ]; then
    WORKERS=8
fi

echo "======================================================"
echo "InsightFace 프로덕션 서버 (Gunicorn)"
echo "======================================================"
echo "주소: http://$HOST:$PORT"
echo "워커: $WORKERS개"
echo "======================================================"

# Gunicorn 실행
exec gunicorn wsgi:application \
    --workers $WORKERS \
    --worker-class sync \
    --bind $HOST:$PORT \
    --timeout 120 \
    --keep-alive 5 \
    --max-requests 1000 \
    --max-requests-jitter 50 \
    --access-logfile - \
    --error-logfile - \
    --log-level info \
    --worker-tmp-dir /dev/shm \
    --reload