# API 키 시스템 설정 스크립트

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    API 키 시스템 설정" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# MySQL 정보 입력
$dbUser = Read-Host -Prompt "MySQL 사용자명 입력 (기본값: root)"
if ([string]::IsNullOrEmpty($dbUser)) { $dbUser = "root" }

$dbPass = Read-Host -Prompt "MySQL 비밀번호 입력" -AsSecureString
$dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))

$dbName = Read-Host -Prompt "데이터베이스명 입력 (기본값: spoq_db)"
if ([string]::IsNullOrEmpty($dbName)) { $dbName = "spoq_db" }

Write-Host ""
Write-Host "1. API 키 테이블 생성 중..." -ForegroundColor Yellow

$createTableCmd = "mysql -u $dbUser -p$dbPassPlain $dbName < sql\create_api_keys_table.sql 2>&1"
$result = cmd /c $createTableCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ 테이블 생성 완료" -ForegroundColor Green
} else {
    Write-Host "✗ 테이블 생성 실패!" -ForegroundColor Red
    Write-Host $result
    Read-Host "Enter를 눌러 종료"
    exit 1
}

Write-Host ""
Write-Host "2. 테스트 API 키 삽입 중..." -ForegroundColor Yellow

$insertTestCmd = "mysql -u $dbUser -p$dbPassPlain $dbName < sql\insert_test_api_keys.sql 2>&1"
$result = cmd /c $insertTestCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ 테스트 API 키 생성 완료" -ForegroundColor Green
} else {
    Write-Host "✗ 테스트 데이터 삽입 실패!" -ForegroundColor Red
    Write-Host $result
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    설정 완료!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "테스트 API 키:" -ForegroundColor Yellow
Write-Host "- 개발용: test-api-key-dy" -ForegroundColor White
Write-Host "- 지점용: dy-bcoff-test-b00001-1234567890abcdef" -ForegroundColor White
Write-Host ""
Write-Host "상태 확인: php check_api_keys.php" -ForegroundColor Gray
Write-Host ""

Read-Host "Enter를 눌러 종료"