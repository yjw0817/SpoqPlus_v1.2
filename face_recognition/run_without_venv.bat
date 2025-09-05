@echo off
chcp 65001 > nul
echo ========================================
echo Running with System Python
echo ========================================
echo.

echo Checking system Python...
python --version
if errorlevel 1 (
    echo [ERROR] Python not found in PATH
    pause
    exit /b 1
)

echo.
echo Installing packages to system Python...
echo (This is not recommended for production)
echo.

set /p confirm="Continue? (y/n): "
if /i not "%confirm%"=="y" (
    echo Cancelled.
    pause
    exit /b 0
)

echo.
echo Installing required packages...
python -m pip install --upgrade pip
python -m pip install insightface==0.7.3
python -m pip install onnxruntime==1.15.1
python -m pip install flask==2.3.2
python -m pip install flask-cors==4.0.0
python -m pip install opencv-python-headless==4.8.0.74
python -m pip install numpy==1.24.3
python -m pip install scikit-learn==1.3.0
python -m pip install mysql-connector-python==8.0.33
python -m pip install pillow==10.0.0

echo.
echo Starting server with system Python...
python insightface_service.py

pause