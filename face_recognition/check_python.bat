@echo off
echo ========================================
echo Python Environment Checker
echo ========================================
echo.

echo 1. Checking system Python:
python --version 2>nul
if errorlevel 1 (
    echo    [ERROR] Python not found in system PATH
) else (
    echo    [OK] Python found
    where python
)

echo.
echo 2. Checking pip:
pip --version 2>nul
if errorlevel 1 (
    echo    [ERROR] pip not found
) else (
    echo    [OK] pip found
)

echo.
echo 3. Checking virtual environments:
if exist "face_env\Scripts\python.exe" (
    echo    [OK] face_env found
    face_env\Scripts\python.exe --version
) else (
    echo    [NOT FOUND] face_env
)

if exist "venv\Scripts\python.exe" (
    echo    [OK] venv found
    venv\Scripts\python.exe --version
) else (
    echo    [NOT FOUND] venv
)

echo.
echo 4. Checking PATH:
echo %PATH% | findstr /i python >nul
if errorlevel 1 (
    echo    [WARNING] No Python in PATH
) else (
    echo    [OK] Python found in PATH
)

echo.
echo 5. Common Python locations:
if exist "C:\Python38\python.exe" echo    [FOUND] C:\Python38
if exist "C:\Python39\python.exe" echo    [FOUND] C:\Python39
if exist "C:\Python310\python.exe" echo    [FOUND] C:\Python310
if exist "C:\Python311\python.exe" echo    [FOUND] C:\Python311
if exist "C:\Users\%USERNAME%\AppData\Local\Programs\Python\Python38\python.exe" echo    [FOUND] User Python 3.8
if exist "C:\Users\%USERNAME%\AppData\Local\Programs\Python\Python39\python.exe" echo    [FOUND] User Python 3.9
if exist "C:\Users\%USERNAME%\AppData\Local\Programs\Python\Python310\python.exe" echo    [FOUND] User Python 3.10
if exist "C:\Users\%USERNAME%\AppData\Local\Programs\Python\Python311\python.exe" echo    [FOUND] User Python 3.11

echo.
echo ========================================
echo Recommendations:
echo ========================================
echo.
echo If Python is not found:
echo 1. Install Python from https://www.python.org/downloads/
echo 2. During installation, check "Add Python to PATH"
echo 3. Restart your command prompt after installation
echo.
echo If virtual environment is missing:
echo Run: setup_env.bat
echo.
pause