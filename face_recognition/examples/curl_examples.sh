#!/bin/bash

# Face Recognition API cURL 예제
# SPOQ Plus Face Recognition API를 cURL로 사용하는 예제

# API 서버 URL
SERVER_URL="http://localhost:5002"

# 색상 코드
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "🚀 Face Recognition API cURL Examples"
echo "======================================"

# 1. 서버 상태 확인
echo -e "\n${YELLOW}1. 서버 상태 확인${NC}"
echo "curl -X GET \"${SERVER_URL}/api/face/health\""
curl -X GET "${SERVER_URL}/api/face/health" \
  -H "Accept: application/json" | python3 -m json.tool

# 2. 이미지를 Base64로 인코딩하는 함수
encode_image() {
    local image_path=$1
    if [ -f "$image_path" ]; then
        echo "data:image/jpeg;base64,$(base64 -w 0 "$image_path")"
    else
        echo ""
    fi
}

# 3. 얼굴 등록
echo -e "\n${YELLOW}2. 얼굴 등록${NC}"
echo "이미지 파일 경로를 입력하세요 (예: /path/to/image.jpg):"
read -r IMAGE_PATH

if [ -f "$IMAGE_PATH" ]; then
    IMAGE_DATA=$(encode_image "$IMAGE_PATH")
    
    echo -e "\n${GREEN}얼굴 등록 요청 전송 중...${NC}"
    cat <<EOF | curl -X POST "${SERVER_URL}/api/face/register" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d @- | python3 -m json.tool
{
    "member_id": "MEM001",
    "image": "$IMAGE_DATA",
    "security_level": 3,
    "notes": "cURL 테스트 등록"
}
EOF
else
    echo -e "${RED}이미지 파일을 찾을 수 없습니다: $IMAGE_PATH${NC}"
fi

# 4. 얼굴 인식
echo -e "\n${YELLOW}3. 얼굴 인식${NC}"
if [ -f "$IMAGE_PATH" ]; then
    echo -e "\n${GREEN}얼굴 인식 요청 전송 중...${NC}"
    cat <<EOF | curl -X POST "${SERVER_URL}/api/face/recognize" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d @- | python3 -m json.tool
{
    "image": "$IMAGE_DATA",
    "comp_cd": "C001",
    "bcoff_cd": "B001"
}
EOF
fi

# 5. 등록용 품질 검사
echo -e "\n${YELLOW}4. 등록용 품질 검사${NC}"
if [ -f "$IMAGE_PATH" ]; then
    echo -e "\n${GREEN}품질 검사 요청 전송 중...${NC}"
    cat <<EOF | curl -X POST "${SERVER_URL}/api/face/detect_for_registration" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d @- | python3 -m json.tool
{
    "image": "$IMAGE_DATA"
}
EOF
fi

# 6. 체크인용 얼굴 인식
echo -e "\n${YELLOW}5. 체크인용 얼굴 인식${NC}"
if [ -f "$IMAGE_PATH" ]; then
    echo -e "\n${GREEN}체크인 요청 전송 중...${NC}"
    cat <<EOF | curl -X POST "${SERVER_URL}/api/face/recognize_for_checkin" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d @- | python3 -m json.tool
{
    "image": "$IMAGE_DATA",
    "comp_cd": "C001",
    "bcoff_cd": "B001",
    "security_level": 3
}
EOF
fi

# 단독 실행 예제들
echo -e "\n${YELLOW}========== 단독 실행 예제 ===========${NC}"

echo -e "\n${GREEN}예제 1: 서버 상태 확인${NC}"
cat << 'EOF'
curl -X GET "http://localhost:5002/api/face/health" \
  -H "Accept: application/json"
EOF

echo -e "\n${GREEN}예제 2: 얼굴 등록 (파일에서 읽기)${NC}"
cat << 'EOF'
# 이미지를 Base64로 인코딩
IMAGE_BASE64=$(base64 -w 0 face.jpg)

# 얼굴 등록 요청
curl -X POST "http://localhost:5002/api/face/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"member_id\": \"MEM001\",
    \"image\": \"data:image/jpeg;base64,$IMAGE_BASE64\",
    \"security_level\": 3,
    \"notes\": \"초기 등록\"
  }"
EOF

echo -e "\n${GREEN}예제 3: 얼굴 인식${NC}"
cat << 'EOF'
# 이미지를 Base64로 인코딩
IMAGE_BASE64=$(base64 -w 0 test_face.jpg)

# 얼굴 인식 요청
curl -X POST "http://localhost:5002/api/face/recognize" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"image\": \"data:image/jpeg;base64,$IMAGE_BASE64\"
  }"
EOF

echo -e "\n${GREEN}예제 4: JSON 파일 사용${NC}"
cat << 'EOF'
# request.json 파일 생성
cat > request.json << END
{
  "member_id": "MEM001",
  "image": "data:image/jpeg;base64,$(base64 -w 0 face.jpg)",
  "security_level": 3
}
END

# 파일을 사용한 요청
curl -X POST "http://localhost:5002/api/face/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d @request.json
EOF

echo -e "\n${GREEN}예제 5: 응답 저장${NC}"
cat << 'EOF'
# 응답을 파일로 저장
curl -X POST "http://localhost:5002/api/face/recognize" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"image\": \"data:image/jpeg;base64,$(base64 -w 0 face.jpg)\"}" \
  -o response.json

# 저장된 응답 확인
cat response.json | python3 -m json.tool
EOF

echo -e "\n${GREEN}예제 6: 에러 처리${NC}"
cat << 'EOF'
# HTTP 상태 코드 확인
HTTP_CODE=$(curl -s -o response.json -w "%{http_code}" \
  -X POST "http://localhost:5002/api/face/recognize" \
  -H "Content-Type: application/json" \
  -d "{\"image\": \"data:image/jpeg;base64,$(base64 -w 0 face.jpg)\"}")

if [ "$HTTP_CODE" -eq 200 ]; then
    echo "Success:"
    cat response.json | python3 -m json.tool
else
    echo "Error (HTTP $HTTP_CODE):"
    cat response.json
fi
EOF

echo -e "\n${GREEN}예제 7: Windows PowerShell${NC}"
cat << 'EOF'
# Windows PowerShell에서 사용
$imageBytes = [System.IO.File]::ReadAllBytes("C:\path\to\face.jpg")
$imageBase64 = [System.Convert]::ToBase64String($imageBytes)

$body = @{
    member_id = "MEM001"
    image = "data:image/jpeg;base64,$imageBase64"
    security_level = 3
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:5002/api/face/register" `
    -Method Post `
    -ContentType "application/json" `
    -Body $body
EOF

echo -e "\n${YELLOW}======================================"
echo "완료!"