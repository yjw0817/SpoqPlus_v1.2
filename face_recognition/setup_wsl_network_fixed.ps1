# WSL2 Face Recognition 서버 네트워크 설정 스크립트 (수정본)
# PowerShell을 관리자 권한으로 실행해야 함
# UTF-8 인코딩 설정
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "=== Face Recognition WSL2 네트워크 설정 ===" -ForegroundColor Green

# 1. WSL이 실행 중인지 확인
try {
    $wslStatus = wsl --list --running
    if ($wslStatus -notmatch "Ubuntu|Debian|openSUSE|kali") {
        Write-Host "WSL이 실행되지 않았습니다. WSL을 먼저 시작하세요." -ForegroundColor Red
        Write-Host "명령어: wsl" -ForegroundColor Yellow
        exit
    }
} catch {
    Write-Host "WSL이 설치되지 않았거나 실행되지 않았습니다." -ForegroundColor Red
    exit
}

# 2. WSL IP 가져오기
Write-Host "`nWSL IP 주소 확인 중..." -ForegroundColor Cyan
$wslIPRaw = wsl hostname -I
if ([string]::IsNullOrWhiteSpace($wslIPRaw)) {
    Write-Host "WSL IP를 가져올 수 없습니다. WSL이 실행 중인지 확인하세요." -ForegroundColor Red
    exit
}
$wslIP = $wslIPRaw.Trim().Split(' ')[0]
Write-Host "WSL IP 주소: $wslIP" -ForegroundColor Yellow

# 3. Windows IP 가져오기
Write-Host "Windows IP 주소 확인 중..." -ForegroundColor Cyan
$windowsIPs = Get-NetIPAddress -AddressFamily IPv4 | Where-Object { 
    $_.IPAddress -notlike "127.*" -and 
    $_.IPAddress -notlike "169.254.*" -and
    ($_.IPAddress -like "192.168.*" -or $_.IPAddress -like "172.*" -or $_.IPAddress -like "10.*")
}

if ($windowsIPs) {
    $windowsIP = $windowsIPs[0].IPAddress
    Write-Host "Windows IP 주소: $windowsIP" -ForegroundColor Yellow
} else {
    Write-Host "Windows IP를 자동으로 가져올 수 없습니다." -ForegroundColor Yellow
    $windowsIP = Read-Host "Windows IP 주소를 입력하세요 (예: 192.168.0.100)"
}

# 4. 기존 포트 프록시 규칙 삭제
Write-Host "`n기존 포트 프록시 규칙 삭제 중..." -ForegroundColor Cyan
netsh interface portproxy delete v4tov4 listenport=5002 listenaddress=0.0.0.0 2>$null
Start-Sleep -Seconds 1

# 5. 새 포트 프록시 규칙 추가
Write-Host "포트 프록시 규칙 추가 중..." -ForegroundColor Cyan
$proxyCommand = "netsh interface portproxy add v4tov4 listenport=5002 listenaddress=0.0.0.0 connectport=5002 connectaddress=$wslIP"
Write-Host "실행 명령: $proxyCommand" -ForegroundColor Gray
Invoke-Expression $proxyCommand

# 6. 포트 프록시 확인
Write-Host "`n현재 포트 프록시 설정:" -ForegroundColor Cyan
netsh interface portproxy show v4tov4 | Select-String "5002"

# 7. Windows 방화벽 규칙 삭제 (중복 방지)
Write-Host "`n기존 방화벽 규칙 삭제 중..." -ForegroundColor Cyan
try {
    Remove-NetFirewallRule -DisplayName "Face Recognition Internal" -ErrorAction SilentlyContinue
    Remove-NetFirewallRule -DisplayName "Face Recognition Block External" -ErrorAction SilentlyContinue
} catch {
    # 에러 무시
}

# 8. 내부망만 허용하는 방화벽 규칙 추가
Write-Host "방화벽 규칙 추가 중..." -ForegroundColor Cyan

# 내부망 허용 규칙
try {
    $rule = New-NetFirewallRule -DisplayName "Face Recognition Internal" `
        -Direction Inbound `
        -LocalPort 5002 `
        -Protocol TCP `
        -RemoteAddress @("192.168.0.0/16", "172.16.0.0/12", "10.0.0.0/8", "127.0.0.1", "::1") `
        -Action Allow `
        -Profile Any `
        -ErrorAction Stop
    
    Write-Host "✓ 내부망 허용 규칙 추가 완료" -ForegroundColor Green
} catch {
    Write-Host "방화벽 규칙 추가 실패: $_" -ForegroundColor Red
}

# 9. 서비스 상태 확인
Write-Host "`n서비스 확인 중..." -ForegroundColor Cyan
$testConnection = Test-NetConnection -ComputerName localhost -Port 5002 -WarningAction SilentlyContinue

if ($testConnection.TcpTestSucceeded) {
    Write-Host "✓ 포트 5002가 열려 있습니다." -ForegroundColor Green
} else {
    Write-Host "⚠ 포트 5002가 응답하지 않습니다. WSL에서 서버를 시작하세요." -ForegroundColor Yellow
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "✅ 설정 완료!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan

Write-Host "`n내부망에서 접근 가능한 URL:" -ForegroundColor Yellow
Write-Host "  http://${windowsIP}:5002/" -ForegroundColor White
Write-Host "  http://${windowsIP}:5002/api/face/health" -ForegroundColor White

Write-Host "`n로컬에서 테스트:" -ForegroundColor Yellow
Write-Host "  http://localhost:5002/" -ForegroundColor White

Write-Host "`n⚠️  다음 단계:" -ForegroundColor Red
Write-Host "1. WSL 터미널을 열고 다음 명령 실행:" -ForegroundColor Yellow
Write-Host "   cd /mnt/d/Projects/html/SpoqPlus_Color_Admin_Except_Mobile_claude2/face_recognition" -ForegroundColor White
Write-Host "   python insightface_service.py" -ForegroundColor White

Write-Host "`n2. 다른 컴퓨터에서 접속 테스트:" -ForegroundColor Yellow
Write-Host "   브라우저에서: http://${windowsIP}:5002/" -ForegroundColor White

Write-Host "`n========================================" -ForegroundColor Cyan
Read-Host "Enter 키를 눌러 종료"