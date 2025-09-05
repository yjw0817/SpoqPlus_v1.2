@echo off
echo ========================================
echo SPOQ Plus Face Recognition Service
echo ========================================
echo.

REM Python 경로 설정 (필요시 수정)
REM set PYTHON_PATH=C:\Python39\python.exe

REM 현재 디렉토리로 이동
cd /d "%~dp0"

REM 가상환경이 있는 경우 활성화
if exist "venv\Scripts\activate.bat" (
    echo 가상환경 활성화 중...
    call venv\Scripts\activate.bat
)

REM 필수 패키지 설치 확인
echo 패키지 확인 중...
python -c "import insightface" 2>NUL
if errorlevel 1 (
    echo InsightFace가 설치되지 않았습니다.
    echo 다음 명령으로 설치해주세요:
    echo pip install -r requirements.txt
    pause
    exit /b 1
)

REM pyodbc 설치 여부 확인 (선택사항)
python -c "import pyodbc" 2>NUL
if errorlevel 1 (
    echo.
    echo [알림] pyodbc가 설치되지 않았습니다. MSSQL 지원이 비활성화됩니다.
    echo MariaDB만 사용하신다면 무시하셔도 됩니다.
    echo MSSQL을 사용하려면: pip install pyodbc
    echo.
)

REM 포트 확인
set DEFAULT_PORT=5002
if exist ".env" (
    for /f "tokens=1,2 delims==" %%a in ('findstr "FACE_PORT" .env') do (
        set DEFAULT_PORT=%%b
    )
)

echo.
echo Face Recognition 서비스를 시작합니다...
echo 서버 주소: http://localhost:%DEFAULT_PORT%
echo.
echo 종료하려면 Ctrl+C를 누르세요.
echo ========================================
echo.

REM 서비스 실행
python insightface_service.py

REM 서비스 종료 후 가상환경 비활성화
if exist "venv\Scripts\deactivate.bat" (
    call venv\Scripts\deactivate.bat
)

pause