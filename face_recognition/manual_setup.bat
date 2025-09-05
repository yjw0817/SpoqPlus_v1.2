@echo off
chcp 65001 >nul
echo === Face Recognition WSL2 네트워크 수동 설정 ===
echo.

REM WSL IP 가져오기
echo 1. WSL IP 확인 중...
for /f "tokens=1" %%i in ('wsl hostname -I') do set WSL_IP=%%i
echo    WSL IP: %WSL_IP%
echo.

REM Windows IP 확인 (수동 입력 필요)
echo 2. Windows IP 확인
echo    아래 목록에서 사용 중인 네트워크의 IPv4 주소를 확인하세요:
ipconfig | findstr /C:"IPv4"
echo.
set /p WIN_IP="Windows IP 주소를 입력하세요 (예: 192.168.0.100): "
echo.

REM 포트 프록시 설정
echo 3. 포트 프록시 설정 중...
netsh interface portproxy delete v4tov4 listenport=5002 listenaddress=0.0.0.0 2>nul
netsh interface portproxy add v4tov4 listenport=5002 listenaddress=0.0.0.0 connectport=5002 connectaddress=%WSL_IP%
echo    완료!
echo.

REM 설정 확인
echo 4. 현재 포트 프록시 설정:
netsh interface portproxy show v4tov4 | findstr 5002
echo.

REM 방화벽 설정 안내
echo 5. Windows 방화벽 설정
echo    Windows Defender 방화벽을 열어서 다음을 설정하세요:
echo    - 인바운드 규칙 추가
echo    - 포트: 5002
echo    - 프로토콜: TCP
echo    - 원격 IP: 192.168.0.0/16, 172.16.0.0/12, 10.0.0.0/8
echo.

echo ========================================
echo 설정 완료!
echo ========================================
echo.
echo 내부망에서 접근 가능한 URL:
echo   http://%WIN_IP%:5002/
echo   http://%WIN_IP%:5002/api/face/health
echo.
echo WSL에서 서버 시작:
echo   python insightface_service.py
echo.
pause