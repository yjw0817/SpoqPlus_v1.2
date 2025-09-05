@echo off
chcp 65001 >nul
REM InsightFace Service for Windows
REM SPOQ Plus Face Recognition Service

echo ========================================
echo    SPOQ Plus InsightFace Service
echo    Face Recognition Service Starting
echo ========================================
echo.

REM Python 가상환경 활성화 (선택사항)
REM call venv\Scripts\activate

REM Check package installation
echo [1/4] Checking package installation...
pip show insightface >nul 2>&1
if %errorlevel% neq 0 (
    echo InsightFace not installed. Installing...
    pip install -r requirements_insightface.txt
    echo.
)

REM Create model directory
echo [2/4] Checking model directory...
if not exist "models" (
    echo Creating model directory...
    mkdir models
)

REM Get port from config.py
echo [3/4] Reading configuration...
for /f "tokens=*" %%a in ('python -c "try: from config import Config; print(Config.PORT)" 2^>nul') do set PORT=%%a
if "%PORT%"=="" set PORT=5002

REM Start InsightFace service
echo [4/4] Starting InsightFace service...
echo.
echo Service URL: http://localhost:%PORT%
echo API Docs: http://localhost:%PORT%/api/face/health
echo.
echo Press Ctrl+C to stop the service.
echo ========================================
echo.

REM Python 서비스 실행
python insightface_service.py

pause