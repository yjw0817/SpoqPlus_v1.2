@echo off
echo ====================================
echo Building FaceTestRemote.exe
echo ====================================
echo.

if exist "face_env\Scripts\activate.bat" (
    echo Activating virtual environment...
    call face_env\Scripts\activate.bat
)

pip show pyinstaller >nul 2>&1
if errorlevel 1 (
    echo Installing PyInstaller...
    pip install pyinstaller
)

if exist "build" rmdir /s /q build
if exist "dist" rmdir /s /q dist

echo.
echo Building EXE file...
echo.

pyinstaller --onefile --name FaceTestRemote test_face_remote.py

echo.
if exist "dist\FaceTestRemote.exe" (
    echo Build Success!
    echo File: dist\FaceTestRemote.exe
    dir dist\FaceTestRemote.exe
) else (
    echo Build Failed!
)

pause