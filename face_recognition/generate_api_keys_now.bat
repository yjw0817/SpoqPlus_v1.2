@echo off
chcp 65001 > nul
cls
echo ========================================
echo    실제 지점 API 키 생성
echo ========================================
echo.

echo MySQL 비밀번호를 입력하세요:
set /p MYSQL_PASS=

echo.
echo API 키를 생성하는 중...
php generate_real_api_keys.php

pause