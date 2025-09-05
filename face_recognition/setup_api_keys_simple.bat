@echo off
chcp 65001 > nul
cls
echo ========================================
echo    API Key System Setup
echo ========================================
echo.

echo Enter MySQL username (default: root):
set /p DB_USER=
if "%DB_USER%"=="" set DB_USER=root

echo Enter MySQL password:
set /p DB_PASS=
if "%DB_PASS%"=="" (
    echo Password required!
    pause
    exit /b 1
)

echo Enter database name (default: spoq_db):
set /p DB_NAME=
if "%DB_NAME%"=="" set DB_NAME=spoq_db

echo.
echo Creating API key tables...
mysql -u %DB_USER% -p%DB_PASS% %DB_NAME% < sql\create_api_keys_table.sql
if %errorlevel% neq 0 (
    echo Table creation failed!
    pause
    exit /b 1
)
echo Table creation complete

echo.
echo Inserting test API keys...
mysql -u %DB_USER% -p%DB_PASS% %DB_NAME% < sql\insert_test_api_keys.sql
if %errorlevel% neq 0 (
    echo Test data insertion failed!
    pause
    exit /b 1
)
echo Test API keys created

echo.
echo ========================================
echo    Setup Complete!
echo ========================================
echo.
echo Test API keys:
echo - Development: test-api-key-dy
echo - Branch: dy-bcoff-test-b00001-1234567890abcdef
echo.
pause