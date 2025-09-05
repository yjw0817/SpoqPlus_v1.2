@echo off
echo ====================================
echo Face Test Remote EXE 빌드 스크립트
echo ====================================
echo.

REM Python 가상환경 활성화 (있는 경우)
if exist "face_env\Scripts\activate.bat" (
    echo 가상환경 활성화 중...
    call face_env\Scripts\activate.bat
)

REM PyInstaller 설치 확인
echo PyInstaller 확인 중...
pip show pyinstaller >nul 2>&1
if errorlevel 1 (
    echo PyInstaller 설치 중...
    pip install pyinstaller
)

REM 기존 빌드 폴더 정리
echo.
echo 기존 빌드 폴더 정리 중...
if exist "build" rmdir /s /q build
if exist "dist" rmdir /s /q dist

REM EXE 파일 빌드
echo.
echo EXE 파일 빌드 시작...
echo.

REM spec 파일이 있으면 spec 파일 사용, 없으면 직접 빌드
if exist "test_face_remote.spec" (
    pyinstaller test_face_remote.spec --clean
) else (
    pyinstaller --onefile ^
                --name FaceTestRemote ^
                --distpath dist ^
                --workpath build ^
                --specpath . ^
                --hidden-import cv2 ^
                --hidden-import numpy ^
                --hidden-import matplotlib ^
                --hidden-import requests ^
                test_face_remote.py
)

echo.
echo ====================================
if exist "dist\FaceTestRemote.exe" (
    echo ✅ 빌드 성공!
    echo.
    echo 실행 파일 위치: dist\FaceTestRemote.exe
    echo 파일 크기: 
    for %%I in ("dist\FaceTestRemote.exe") do echo %%~zI bytes
    echo.
    echo 사용 예시:
    echo   FaceTestRemote.exe --help
    echo   FaceTestRemote.exe --server 192.168.0.48:5002 --mode single --image test.jpg
    echo   FaceTestRemote.exe --host 192.168.0.48 --port 5002 --mode realtime
) else (
    echo ❌ 빌드 실패!
    echo 오류 로그를 확인하세요.
)
echo ====================================

pause