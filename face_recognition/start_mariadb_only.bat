@echo off
echo ========================================
echo SPOQ Plus Face Recognition (MariaDB)
echo ========================================
echo.
echo MariaDB 전용 모드로 시작합니다.
echo pyodbc 설치 없이 실행 가능합니다.
echo.

REM 현재 디렉토리로 이동
cd /d "%~dp0"

REM 환경변수 설정 (MariaDB 강제)
set DB_TYPE=mariadb

REM 서비스 실행
echo Face Recognition 서비스를 시작합니다...
echo 서버 주소: http://localhost:5002
echo.
echo 종료하려면 Ctrl+C를 누르세요.
echo ========================================
echo.

python insightface_service.py

pause