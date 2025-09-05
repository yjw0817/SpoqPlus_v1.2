@echo off
echo ====================================
echo Face Test Remote EXE 빌드 (최소 크기)
echo ====================================
echo.

REM 가상환경 활성화
if exist "face_env\Scripts\activate.bat" (
    echo 가상환경 활성화 중...
    call face_env\Scripts\activate.bat
)

REM PyInstaller 설치 확인
pip show pyinstaller >nul 2>&1
if errorlevel 1 (
    echo PyInstaller 설치 중...
    pip install pyinstaller
)

REM 기존 빌드 정리
if exist "build" rmdir /s /q build
if exist "dist" rmdir /s /q dist

echo.
echo 최소 크기 EXE 빌드 시작...
echo (insightface 관련 패키지 제외)
echo.

pyinstaller --onefile ^
            --name FaceTestRemote ^
            --exclude-module insightface ^
            --exclude-module onnxruntime ^
            --exclude-module onnx ^
            --exclude-module mediapipe ^
            --exclude-module flask ^
            --exclude-module flask_cors ^
            --exclude-module pymssql ^
            --exclude-module pymysql ^
            --exclude-module mariadb ^
            --exclude-module dotenv ^
            --exclude-module werkzeug ^
            --exclude-module jinja2 ^
            --exclude-module click ^
            --exclude-module itsdangerous ^
            --exclude-module markupsafe ^
            test_face_remote.py

echo.
if exist "dist\FaceTestRemote.exe" (
    echo ✅ 빌드 완료!
    echo.
    echo 파일: dist\FaceTestRemote.exe
    for %%I in ("dist\FaceTestRemote.exe") do (
        set /a size=%%~zI/1048576
        echo 크기: %%~zI bytes (약 !size! MB)
    )
) else (
    echo ❌ 빌드 실패!
)

pause