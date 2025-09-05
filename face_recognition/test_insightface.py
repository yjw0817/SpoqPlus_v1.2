#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
InsightFace 서비스 테스트 스크립트
로컬에서 서비스가 정상 작동하는지 확인
"""

import requests
import json
import base64
import sys
import os
from datetime import datetime

# API 기본 URL
API_BASE_URL = "http://localhost:5002"

def test_health_check():
    """헬스 체크 테스트"""
    print("=== 헬스 체크 테스트 ===")
    try:
        response = requests.get(f"{API_BASE_URL}/api/face/health")
        result = response.json()
        print(f"상태: {result.get('status')}")
        print(f"서비스: {result.get('service')}")
        print(f"모델: {result.get('model')}")
        print(f"임베딩 차원: {result.get('embedding_dimension')}")
        print("✅ 헬스 체크 성공\n")
        return True
    except Exception as e:
        print(f"❌ 헬스 체크 실패: {e}\n")
        return False

def encode_image(image_path):
    """이미지를 Base64로 인코딩"""
    with open(image_path, "rb") as image_file:
        encoded = base64.b64encode(image_file.read()).decode('utf-8')
        return f"data:image/jpeg;base64,{encoded}"

def test_face_registration(member_id, image_path):
    """얼굴 등록 테스트"""
    print(f"=== 얼굴 등록 테스트 (회원: {member_id}) ===")
    
    if not os.path.exists(image_path):
        print(f"❌ 이미지 파일을 찾을 수 없습니다: {image_path}\n")
        return False
    
    try:
        # 이미지 인코딩
        image_data = encode_image(image_path)
        
        # API 호출
        response = requests.post(
            f"{API_BASE_URL}/api/face/register",
            json={
                "member_id": member_id,
                "image": image_data
            }
        )
        
        result = response.json()
        
        if result.get('success'):
            print(f"✅ 얼굴 등록 성공!")
            print(f"  - Face ID: {result.get('face_id')}")
            print(f"  - 품질 점수: {result.get('quality_score', 0):.2f}")
            print(f"  - 안경 감지: {'예' if result.get('glasses_detected') else '아니오'}")
            print(f"  - 처리 시간: {result.get('processing_time_ms', 0):.2f}ms")
            print(f"  - 임베딩 차원: {len(result.get('embedding', []))}")
            print()
            return True
        else:
            print(f"❌ 얼굴 등록 실패: {result.get('error', '알 수 없는 오류')}")
            print(f"  - 메시지: {result.get('message', '')}\n")
            return False
            
    except Exception as e:
        print(f"❌ API 호출 실패: {e}\n")
        return False

def test_face_recognition(image_path):
    """얼굴 인식 테스트"""
    print(f"=== 얼굴 인식 테스트 ===")
    
    if not os.path.exists(image_path):
        print(f"❌ 이미지 파일을 찾을 수 없습니다: {image_path}\n")
        return False
    
    try:
        # 이미지 인코딩
        image_data = encode_image(image_path)
        
        # API 호출
        response = requests.post(
            f"{API_BASE_URL}/api/face/recognize",
            json={"image": image_data}
        )
        
        result = response.json()
        
        if result.get('success'):
            if result.get('matched'):
                print(f"✅ 얼굴 인식 성공!")
                print(f"  - 회원 ID: {result.get('member_id')}")
                print(f"  - 유사도: {result.get('similarity', 0)*100:.2f}%")
                print(f"  - 신뢰도: {result.get('confidence')}")
                print(f"  - 매칭 타입: {result.get('match_type')}")
                print(f"  - 안경 감지: {'예' if result.get('glasses_detected') else '아니오'}")
                print(f"  - 처리 시간: {result.get('processing_time_ms', 0):.2f}ms")
            else:
                print(f"⚠️  일치하는 얼굴을 찾을 수 없습니다")
                print(f"  - 최대 유사도: {result.get('similarity', 0)*100:.2f}%")
                print(f"  - 임계값: {result.get('threshold', 0)}")
                print(f"  - 메시지: {result.get('message', '')}")
            print()
            return True
        else:
            print(f"❌ 얼굴 인식 실패: {result.get('error', '알 수 없는 오류')}")
            print(f"  - 메시지: {result.get('message', '')}\n")
            return False
            
    except Exception as e:
        print(f"❌ API 호출 실패: {e}\n")
        return False

def run_full_test():
    """전체 테스트 실행"""
    print("\n" + "="*50)
    print("    InsightFace 서비스 통합 테스트")
    print("="*50 + "\n")
    
    # 1. 헬스 체크
    if not test_health_check():
        print("⚠️  서비스가 실행 중이지 않습니다.")
        print("먼저 'python insightface_service.py'를 실행해주세요.\n")
        return
    
    # 2. 테스트 이미지 준비
    test_images = [
        ("TEST001", "test_images/person1_register.jpg"),
        ("TEST002", "test_images/person2_register.jpg"),
    ]
    
    recognition_images = [
        "test_images/person1_test.jpg",
        "test_images/person2_test.jpg",
        "test_images/unknown_person.jpg",
    ]
    
    # 3. 얼굴 등록 테스트
    print("\n### 얼굴 등록 단계 ###\n")
    for member_id, image_path in test_images:
        test_face_registration(member_id, image_path)
    
    # 4. 얼굴 인식 테스트
    print("\n### 얼굴 인식 단계 ###\n")
    for image_path in recognition_images:
        test_face_recognition(image_path)
    
    print("\n" + "="*50)
    print("    테스트 완료")
    print("="*50 + "\n")

def create_test_directories():
    """테스트 디렉토리 생성"""
    os.makedirs("test_images", exist_ok=True)
    os.makedirs("models", exist_ok=True)
    print("✅ 테스트 디렉토리가 생성되었습니다.")
    print("test_images/ 폴더에 테스트할 이미지를 넣어주세요.")

if __name__ == "__main__":
    if len(sys.argv) > 1:
        if sys.argv[1] == "setup":
            create_test_directories()
        elif sys.argv[1] == "health":
            test_health_check()
        elif sys.argv[1] == "register" and len(sys.argv) >= 4:
            test_face_registration(sys.argv[2], sys.argv[3])
        elif sys.argv[1] == "recognize" and len(sys.argv) >= 3:
            test_face_recognition(sys.argv[2])
        else:
            print("사용법:")
            print("  python test_insightface.py setup       - 테스트 디렉토리 생성")
            print("  python test_insightface.py health      - 헬스 체크")
            print("  python test_insightface.py register <member_id> <image_path>")
            print("  python test_insightface.py recognize <image_path>")
            print("  python test_insightface.py            - 전체 테스트")
    else:
        run_full_test()