@echo off
chcp 65001 > nul
echo ========================================
echo Direct InsightFace Server Runner
echo ========================================
echo.

REM Check if virtual environment exists
if not exist "face_env\Scripts\python.exe" (
    echo [ERROR] Virtual environment not found!
    echo Please run: recreate_venv.bat
    pause
    exit /b 1
)

REM Check config file
if not exist "config.env" (
    echo [WARNING] config.env not found, creating default...
    (
        echo # Database Configuration
        echo DB_HOST=192.168.0.48
        echo DB_USER=root
        echo DB_PASSWORD=spoqdb11
        echo DB_NAME=spoqplusdb
        echo.
        echo # Server Configuration
        echo SERVER_PORT=5002
        echo SERVER_HOST=0.0.0.0
    ) > config.env
)

echo Starting InsightFace server...
echo Using Python: face_env\Scripts\python.exe
echo.

REM Run server with full path
face_env\Scripts\python.exe insightface_service.py

if errorlevel 1 (
    echo.
    echo [ERROR] Server failed to start
    echo Check the error messages above
)

pause