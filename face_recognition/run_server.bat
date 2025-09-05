@echo off
chcp 65001 > nul
echo ========================================
echo InsightFace Recognition Service Launcher
echo ========================================
echo.

REM Check if we're in virtual environment
if defined VIRTUAL_ENV (
    echo Virtual environment detected: %VIRTUAL_ENV%
) else (
    echo Activating virtual environment...
    if exist "face_env\Scripts\activate.bat" (
        call face_env\Scripts\activate.bat
    ) else if exist "venv\Scripts\activate.bat" (
        call venv\Scripts\activate.bat
    ) else (
        echo ERROR: Virtual environment not found!
        echo Please create virtual environment first.
        goto :end
    )
)

REM Check Python
echo.
echo Checking Python installation...
python --version 2>nul
if errorlevel 1 (
    echo ERROR: Python not found in PATH
    echo Please ensure Python is properly installed
    goto :end
)

REM Check required packages
echo.
echo Checking required packages...
python -c "import insightface" 2>nul
if errorlevel 1 (
    echo WARNING: insightface package not found
    echo Installing required packages...
    pip install insightface flask flask-cors opencv-python-headless numpy scikit-learn mysql-connector-python pillow onnxruntime
)

REM Check if models directory exists
echo.
echo Checking models directory...
if not exist "models" (
    echo Creating models directory...
    mkdir models
)

REM Check config file
echo.
echo Checking configuration...
if not exist "config.env" (
    echo ERROR: config.env file not found!
    echo Creating default config.env...
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
    echo Default config.env created. Please update with your settings.
)

REM Start the server
echo.
echo ========================================
echo Starting InsightFace Service...
echo ========================================
echo.
echo Server will run on http://localhost:5002
echo Press Ctrl+C to stop the server
echo.
python insightface_service.py

:end
echo.
pause