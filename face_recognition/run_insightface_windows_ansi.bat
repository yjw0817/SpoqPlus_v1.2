@echo off
echo ========================================
echo    SPOQ Plus InsightFace Service
echo    Face Recognition Service Start
echo ========================================
echo.

echo [1/3] Checking package installation...
pip show insightface >nul 2>&1
if %errorlevel% neq 0 (
    echo InsightFace is not installed. Starting installation...
    pip install -r requirements_insightface.txt
    echo.
)

echo [2/3] Checking model directory...
if not exist "models" (
    echo Creating model directory...
    mkdir models
)

echo [3/3] Starting InsightFace service...
echo.
echo Service URL: http://localhost:5002
echo API Health: http://localhost:5002/api/face/health
echo.
echo Press Ctrl+C to stop the service.
echo ========================================
echo.

python insightface_service.py

pause