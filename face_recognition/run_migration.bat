@echo off
echo ========================================
echo Face Recognition 데이터 마이그레이션
echo MariaDB → MSSQL
echo ========================================
echo.
echo 설정 정보:
echo - MariaDB: 192.168.0.48/spoqplusdb
echo - MSSQL: 192.168.0.48/FD
echo.

REM pyodbc 설치 확인
python -c "import pyodbc" 2>NUL
if errorlevel 1 (
    echo ❌ pyodbc가 설치되지 않았습니다.
    echo.
    echo 설치하려면 다음 명령을 실행하세요:
    echo pip install pyodbc
    echo.
    pause
    exit /b 1
)

echo pyodbc가 설치되어 있습니다.
echo.
echo 마이그레이션을 시작하시겠습니까?
pause

REM 마이그레이션 실행
python migrate_simple.py

pause