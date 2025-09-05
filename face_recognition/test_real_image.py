#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
실제 이미지로 InsightFace API 테스트
"""

import requests
import json
import base64
import os
from PIL import Image
import io

def create_test_face_image():
    """간단한 테스트용 얼굴 이미지 생성"""
    # 단순한 얼굴 모양 이미지 생성 (원 + 눈 + 입)
    from PIL import Image, ImageDraw
    
    # 200x200 흰색 배경
    img = Image.new('RGB', (200, 200), color='white')
    draw = ImageDraw.Draw(img)
    
    # 얼굴 윤곽 (큰 원)
    draw.ellipse([50, 50, 150, 150], outline='black', width=2)
    
    # 눈 (작은 원)
    draw.ellipse([70, 80, 90, 100], fill='black')
    draw.ellipse([110, 80, 130, 100], fill='black')
    
    # 입 (호)
    draw.arc([80, 110, 120, 130], start=0, end=180, fill='black', width=2)
    
    # 이미지를 base64로 변환
    buffer = io.BytesIO()
    img.save(buffer, format='PNG')
    img_data = buffer.getvalue()
    
    return base64.b64encode(img_data).decode('utf-8')

def test_with_real_image(image_path=None):
    """실제 이미지로 테스트"""
    print("=== 실제 이미지로 InsightFace API 테스트 ===\n")
    
    if image_path and os.path.exists(image_path):
        # 제공된 이미지 사용
        print(f"이미지 파일 사용: {image_path}")
        with open(image_path, 'rb') as f:
            image_base64 = base64.b64encode(f.read()).decode('utf-8')
    else:
        # 테스트 이미지 생성
        print("테스트 얼굴 이미지 생성 중...")
        image_base64 = create_test_face_image()
    
    # API 호출
    data = {
        "image": image_base64
    }
    
    try:
        print("\n1. /api/face/detect_for_registration 호출...")
        response = requests.post(
            "http://localhost:5002/api/face/detect_for_registration",
            json=data,
            headers={"Content-Type": "application/json"}
        )
        
        print(f"상태 코드: {response.status_code}")
        result = response.json()
        print(f"응답:\n{json.dumps(result, indent=2, ensure_ascii=False)}")
        
        # 응답 분석
        print("\n=== 응답 분석 ===")
        if result.get('success'):
            print("✅ 얼굴 감지 성공!")
            print(f"  - 임베딩 차원: {len(result.get('face_encoding', []))}")
            print(f"  - 품질 점수: {result.get('quality_score', 0)}")
            print(f"  - 안경 감지: {result.get('glasses_detected', False)}")
            print(f"  - Liveness: {result.get('liveness_check', {}).get('is_live', False)}")
        else:
            print("❌ 얼굴 감지 실패")
            print(f"  - 오류: {result.get('error', 'Unknown error')}")
            
    except Exception as e:
        print(f"❌ API 호출 실패: {e}")

def test_register_face():
    """얼굴 등록 테스트"""
    print("\n\n=== 얼굴 등록 API 테스트 ===\n")
    
    # 테스트 이미지 생성
    image_base64 = create_test_face_image()
    
    # FormData 형식으로 전송
    files = {
        'image': ('test_face.png', base64.b64decode(image_base64), 'image/png')
    }
    data = {
        'mem_sno': 'TEST_USER_001'
    }
    
    try:
        print("2. /api/face/register 호출 (FormData)...")
        response = requests.post(
            "http://localhost:5002/api/face/register",
            files=files,
            data=data
        )
        
        print(f"상태 코드: {response.status_code}")
        result = response.json()
        print(f"응답:\n{json.dumps(result, indent=2, ensure_ascii=False)}")
        
    except Exception as e:
        print(f"❌ API 호출 실패: {e}")

if __name__ == "__main__":
    import sys
    
    # 명령줄 인자로 이미지 경로 받기
    image_path = sys.argv[1] if len(sys.argv) > 1 else None
    
    # 테스트 실행
    test_with_real_image(image_path)
    test_register_face()
    
    print("\n\n💡 팁: 실제 얼굴 이미지로 테스트하려면:")
    print("   python test_real_image.py /path/to/face/image.jpg")