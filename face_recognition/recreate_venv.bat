@echo off
chcp 65001 > nul
echo ========================================
echo Recreating Virtual Environment
echo ========================================
echo.

echo Step 1: Removing old virtual environment...
if exist "face_env" (
    rmdir /s /q face_env
    echo [OK] Old environment removed
) else (
    echo [INFO] No existing environment found
)

echo.
echo Step 2: Creating new virtual environment...
python -m venv face_env
if errorlevel 1 (
    echo [ERROR] Failed to create virtual environment
    echo Please check if Python is installed correctly
    pause
    exit /b 1
)

echo [OK] Virtual environment created
echo.

echo Step 3: Installing packages...
echo.

REM Use the full path to pip to avoid PATH issues
face_env\Scripts\python.exe -m pip install --upgrade pip

echo Installing InsightFace packages...
face_env\Scripts\python.exe -m pip install insightface==0.7.3
face_env\Scripts\python.exe -m pip install onnxruntime==1.15.1
face_env\Scripts\python.exe -m pip install flask==2.3.2
face_env\Scripts\python.exe -m pip install flask-cors==4.0.0
face_env\Scripts\python.exe -m pip install opencv-python-headless==4.8.0.74
face_env\Scripts\python.exe -m pip install numpy==1.24.3
face_env\Scripts\python.exe -m pip install scikit-learn==1.3.0
face_env\Scripts\python.exe -m pip install mysql-connector-python==8.0.33
face_env\Scripts\python.exe -m pip install pillow==10.0.0

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo To run the server:
echo   face_env\Scripts\python.exe insightface_service.py
echo.
echo Or use the direct runner:
echo   run_direct.bat
echo.
pause