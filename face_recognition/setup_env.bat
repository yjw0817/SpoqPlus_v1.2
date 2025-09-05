@echo off
chcp 65001 > nul
echo ========================================
echo Face Recognition Environment Setup
echo ========================================
echo.

REM Check Python installation
echo Checking Python installation...
python --version 2>nul
if errorlevel 1 (
    echo ERROR: Python is not installed or not in PATH
    echo.
    echo Please install Python 3.8 or higher from:
    echo https://www.python.org/downloads/
    echo.
    echo Make sure to check "Add Python to PATH" during installation
    goto :end
)

REM Create virtual environment
echo.
echo Creating virtual environment...
if exist "face_env" (
    echo Virtual environment already exists. Deleting old environment...
    rmdir /s /q face_env
)

python -m venv face_env
if errorlevel 1 (
    echo ERROR: Failed to create virtual environment
    goto :end
)

REM Activate virtual environment
echo.
echo Activating virtual environment...
call face_env\Scripts\activate.bat

REM Upgrade pip
echo.
echo Upgrading pip...
python -m pip install --upgrade pip

REM Install required packages
echo.
echo Installing required packages...
echo This may take several minutes...

pip install insightface==0.7.3
pip install onnxruntime==1.15.1
pip install flask==2.3.2
pip install flask-cors==4.0.0
pip install opencv-python-headless==4.8.0.74
pip install numpy==1.24.3
pip install scikit-learn==1.3.0
pip install mysql-connector-python==8.0.33
pip install pillow==10.0.0

REM Create necessary directories
echo.
echo Creating necessary directories...
if not exist "models" mkdir models
if not exist "logs" mkdir logs

REM Create .env file from example if not exists
echo.
echo Checking configuration...
if not exist ".env" (
    if exist ".env.example" (
        echo Creating .env file from .env.example...
        copy .env.example .env
        echo .env created. Please update with your database settings.
    ) else (
        echo WARNING: .env.example not found. Please create .env file manually.
    )
)

echo.
echo ========================================
echo Setup completed successfully!
echo ========================================
echo.
echo To start the server, run:
echo   run_server.bat
echo.
echo Or manually:
echo   face_env\Scripts\activate
echo   python insightface_service.py
echo.

:end
pause