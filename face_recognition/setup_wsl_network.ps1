# WSL2 Face Recognition 서버 네트워크 설정 스크립트
# PowerShell을 관리자 권한으로 실행해야 함

Write-Host "=== Face Recognition WSL2 네트워크 설정 ===" -ForegroundColor Green

# 1. WSL IP 가져오기
$wslIP = wsl hostname -I | ForEach-Object { $_.Split()[0] }
Write-Host "WSL IP 주소: $wslIP" -ForegroundColor Yellow

# 2. Windows IP 가져오기
$windowsIP = (Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.IPAddress -like "192.168.*" -or $_.IPAddress -like "172.*" -or $_.IPAddress -like "10.*" } | Select-Object -First 1).IPAddress
Write-Host "Windows IP 주소: $windowsIP" -ForegroundColor Yellow

# 3. 기존 포트 프록시 규칙 삭제
Write-Host "`n기존 포트 프록시 규칙 삭제 중..." -ForegroundColor Cyan
netsh interface portproxy delete v4tov4 listenport=5002 listenaddress=0.0.0.0 2>$null

# 4. 새 포트 프록시 규칙 추가
Write-Host "포트 프록시 규칙 추가 중..." -ForegroundColor Cyan
netsh interface portproxy add v4tov4 listenport=5002 listenaddress=0.0.0.0 connectport=5002 connectaddress=$wslIP

# 5. 포트 프록시 확인
Write-Host "`n현재 포트 프록시 설정:" -ForegroundColor Cyan
netsh interface portproxy show v4tov4

# 6. Windows 방화벽 규칙 삭제 (중복 방지)
Write-Host "`n기존 방화벽 규칙 삭제 중..." -ForegroundColor Cyan
Remove-NetFirewallRule -DisplayName "Face Recognition Internal" -ErrorAction SilentlyContinue
Remove-NetFirewallRule -DisplayName "Face Recognition Block External" -ErrorAction SilentlyContinue

# 7. 내부망만 허용하는 방화벽 규칙 추가
Write-Host "방화벽 규칙 추가 중..." -ForegroundColor Cyan

# 내부망 허용 규칙
New-NetFirewallRule -DisplayName "Face Recognition Internal" `
    -Direction Inbound `
    -LocalPort 5002 `
    -Protocol TCP `
    -RemoteAddress @("192.168.0.0/16", "172.16.0.0/12", "10.0.0.0/8", "127.0.0.1") `
    -Action Allow `
    -Profile Any

# 외부 차단 규칙 (더 높은 우선순위)
New-NetFirewallRule -DisplayName "Face Recognition Block External" `
    -Direction Inbound `
    -LocalPort 5002 `
    -Protocol TCP `
    -RemoteAddress @("0.0.0.0/0") `
    -Action Block `
    -Priority 100 `
    -Profile Any

Write-Host "`n✅ 설정 완료!" -ForegroundColor Green
Write-Host "내부망에서 접근 가능한 URL:" -ForegroundColor Yellow
Write-Host "  http://${windowsIP}:5002/" -ForegroundColor White
Write-Host "  http://${windowsIP}:5002/api/face/health" -ForegroundColor White

Write-Host "`n⚠️  중요: WSL에서 Face Recognition 서버를 재시작하세요:" -ForegroundColor Red
Write-Host "  python insightface_service.py" -ForegroundColor White