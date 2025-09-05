@echo off
chcp 65001 > nul
echo ========================================
echo    API 키 시스템 설정
echo ========================================
echo.

set /p DB_USER=MySQL 사용자명 입력 (기본값: root): 
if "%DB_USER%"=="" set DB_USER=root

set /p DB_PASS=MySQL 비밀번호 입력: 
if "%DB_PASS%"=="" (
    echo 비밀번호가 필요합니다!
    pause
    exit /b 1
)

set /p DB_NAME=데이터베이스명 입력 (기본값: spoq_db): 
if "%DB_NAME%"=="" set DB_NAME=spoq_db

echo.
echo 1. API 키 테이블 생성 중...
mysql -u %DB_USER% -p%DB_PASS% %DB_NAME% < sql\create_api_keys_table.sql
if %errorlevel% neq 0 (
    echo 테이블 생성 실패!
    pause
    exit /b 1
)
echo ✓ 테이블 생성 완료

echo.
echo 2. 테스트 API 키 삽입 중...
mysql -u %DB_USER% -p%DB_PASS% %DB_NAME% < sql\insert_test_api_keys.sql
if %errorlevel% neq 0 (
    echo 테스트 데이터 삽입 실패!
    pause
    exit /b 1
)
echo ✓ 테스트 API 키 생성 완료

echo.
echo 3. 기존 지점 API 키 생성...
echo 이 작업은 PHP가 필요합니다. 수동으로 실행하세요:
echo php generate_branch_api_keys.php
echo.

echo ========================================
echo    설정 완료!
echo ========================================
echo.
echo 테스트 API 키:
echo - 개발용: test-api-key-dy
echo - 지점용: dy-bcoff-test-b00001-1234567890abcdef
echo.
pause