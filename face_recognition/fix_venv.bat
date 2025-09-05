@echo off
chcp 65001 > nul
echo ========================================
echo Virtual Environment Fix Tool
echo ========================================
echo.

echo 1. Checking current Python installation...
where python
echo.

echo 2. Checking virtual environment...
if exist "face_env\Scripts\python.exe" (
    echo [OK] face_env\Scripts\python.exe exists
    echo Path: %CD%\face_env\Scripts\python.exe
    echo.
    echo Testing direct execution:
    face_env\Scripts\python.exe --version
) else (
    echo [ERROR] face_env\Scripts\python.exe not found!
)

echo.
echo 3. Checking pyvenv.cfg...
if exist "face_env\pyvenv.cfg" (
    echo [OK] pyvenv.cfg found:
    type face_env\pyvenv.cfg
) else (
    echo [ERROR] pyvenv.cfg not found!
)

echo.
echo ========================================
echo Recommended Solutions:
echo ========================================
echo.
echo Option 1: Delete and recreate virtual environment
echo   rmdir /s /q face_env
echo   python -m venv face_env
echo.
echo Option 2: Use direct Python path
echo   face_env\Scripts\python.exe insightface_service.py
echo.
echo Option 3: Fix activate script
echo   Run: fix_activate.bat
echo.
pause