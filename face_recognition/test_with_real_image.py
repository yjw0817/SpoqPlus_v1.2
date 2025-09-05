#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
실제 이미지를 사용한 param1/param2 필터링 테스트
"""

import requests
import json
import base64
from datetime import datetime
import os
import sys

# 테스트 서버 설정
BASE_URL = "http://localhost:5002"

def load_real_image(image_path):
    """실제 이미지 파일 로드"""
    if not os.path.exists(image_path):
        print(f"❌ 이미지 파일을 찾을 수 없습니다: {image_path}")
        return None
        
    try:
        with open(image_path, 'rb') as f:
            image_data = base64.b64encode(f.read()).decode()
        
        # 파일 확장자 확인
        ext = os.path.splitext(image_path)[1].lower()
        if ext in ['.jpg', '.jpeg']:
            mime_type = 'image/jpeg'
        elif ext == '.png':
            mime_type = 'image/png'
        else:
            print(f"❌ 지원하지 않는 이미지 형식: {ext}")
            return None
            
        return f"data:{mime_type};base64,{image_data}"
    except Exception as e:
        print(f"❌ 이미지 로드 실패: {e}")
        return None

def test_database_connection():
    """데이터베이스 연결 테스트"""
    print("0. 데이터베이스 연결 테스트")
    response = requests.get(f"{BASE_URL}/api/face/health")
    health = response.json()
    
    if health.get('status') == 'healthy':
        print("✅ 서비스 정상 작동")
        print(f"   - 모델: {health.get('model')}")
        print(f"   - 임베딩 차원: {health.get('embedding_dimension')}")
    else:
        print("❌ 서비스 오류")
        
    print("-" * 50)
    return health.get('status') == 'healthy'

def test_register_faces(image_paths):
    """여러 이미지로 다양한 지점에 회원 등록"""
    print("\n1. 회원 등록 테스트")
    
    test_cases = [
        ("MEM2025001", "COMP_A", "BRANCH_01", "회사A 지점1"),
        ("MEM2025002", "COMP_A", "BRANCH_02", "회사A 지점2"),
        ("MEM2025003", "COMP_B", "BRANCH_01", "회사B 지점1"),
    ]
    
    registered_count = 0
    
    for i, (member_id, param1, param2, desc) in enumerate(test_cases):
        # 이미지 선택 (여러 이미지가 있으면 순환)
        image_path = image_paths[i % len(image_paths)]
        image_data = load_real_image(image_path)
        
        if not image_data:
            continue
            
        print(f"\n등록: {member_id} - {desc}")
        print(f"이미지: {os.path.basename(image_path)}")
        
        data = {
            "member_id": member_id,
            "image": image_data,
            "security_level": 3,
            "notes": f"{desc} - {datetime.now()}",
            "param1": param1,
            "param2": param2
        }
        
        response = requests.post(f"{BASE_URL}/api/face/register", json=data)
        result = response.json()
        
        if result.get('success'):
            print(f"✅ 등록 성공! face_id: {result.get('face_id')}")
            registered_count += 1
        else:
            print(f"❌ 등록 실패: {result.get('error', '알 수 없는 오류')}")
            
    print(f"\n총 {registered_count}명 등록 완료")
    print("-" * 50)
    return registered_count > 0

def test_filtering(test_image_path):
    """다양한 필터링 조건으로 얼굴 인식 테스트"""
    print("\n2. 필터링 테스트")
    
    image_data = load_real_image(test_image_path)
    if not image_data:
        return
    
    test_cases = [
        (None, None, "필터 없음 (전체 조회)"),
        ("COMP_A", None, "회사 A 전체"),
        ("COMP_A", "BRANCH_01", "회사 A의 지점 1만"),
        ("COMP_A", "BRANCH_02", "회사 A의 지점 2만"),
        ("COMP_B", None, "회사 B 전체"),
        ("COMP_B", "BRANCH_01", "회사 B의 지점 1만"),
    ]
    
    for param1, param2, desc in test_cases:
        print(f"\n테스트: {desc}")
        
        data = {"image": image_data}
        if param1:
            data["param1"] = param1
        if param2:
            data["param2"] = param2
            
        response = requests.post(f"{BASE_URL}/api/face/recognize", json=data)
        result = response.json()
        
        if result.get('success'):
            if result.get('matched'):
                print(f"✅ 인식 성공!")
                print(f"   - member_id: {result.get('member_id')}")
                print(f"   - 유사도: {result.get('similarity'):.3f}")
            else:
                print("⚠️  매칭되는 얼굴 없음")
        else:
            print(f"❌ 오류: {result.get('error', '알 수 없는 오류')}")
            
    print("-" * 50)

def test_legacy_compatibility(test_image_path):
    """레거시 파라미터 호환성 테스트"""
    print("\n3. 레거시 호환성 테스트")
    
    image_data = load_real_image(test_image_path)
    if not image_data:
        return
        
    print("\n체크인 API 테스트 (comp_cd, bcoff_cd 사용)")
    
    data = {
        "image": image_data,
        "comp_cd": "COMP_A",
        "bcoff_cd": "BRANCH_01",
        "security_level": 3
    }
    
    response = requests.post(f"{BASE_URL}/api/face/recognize_for_checkin", json=data)
    result = response.json()
    
    if result.get('success'):
        if result.get('matched'):
            print(f"✅ 체크인 인식 성공!")
            print(f"   - member_id: {result.get('member_id')}")
            print(f"   - 체크인 허용: {result.get('checkin_allowed', False)}")
            if result.get('security_checks'):
                print(f"   - 보안 검사: {result['security_checks']}")
        else:
            print("⚠️  매칭되는 얼굴 없음")
    else:
        print(f"❌ 오류: {result.get('error', '알 수 없는 오류')}")
        
    print("-" * 50)

def check_database_records():
    """데이터베이스 기록 확인 (Python에서 직접)"""
    print("\n4. 데이터베이스 기록 확인")
    print("다음 SQL을 실행하여 확인하세요:")
    print("""
-- 등록된 얼굴 확인
SELECT face_id, mem_sno, param1, param2, created_at 
FROM member_faces 
WHERE param1 IS NOT NULL
ORDER BY created_at DESC LIMIT 10;

-- 인식 로그 확인  
SELECT log_id, mem_sno, param1, param2, similarity, matched
FROM face_recognition_logs 
WHERE param1 IS NOT NULL
ORDER BY recognition_time DESC LIMIT 10;

-- 파라미터별 통계
SELECT param1, param2, COUNT(*) as count 
FROM member_faces 
WHERE param1 IS NOT NULL
GROUP BY param1, param2;
""")

def main():
    """메인 테스트 실행"""
    print("=" * 70)
    print("실제 이미지를 사용한 param1/param2 필터링 테스트")
    print("=" * 70)
    
    # 사용할 이미지 경로 (실제 얼굴 이미지로 변경하세요)
    # 예: ["face1.jpg", "face2.jpg", "face3.jpg"]
    
    if len(sys.argv) < 2:
        print("\n사용법: python test_with_real_image.py <이미지파일1> [이미지파일2] [이미지파일3]")
        print("예: python test_with_real_image.py face1.jpg face2.jpg face3.jpg")
        print("\n테스트용 이미지를 준비해주세요:")
        print("- 정면 얼굴이 잘 보이는 이미지")
        print("- JPG 또는 PNG 형식")
        print("- 가능하면 서로 다른 사람의 얼굴 3개")
        return
        
    image_paths = sys.argv[1:]
    
    # 이미지 파일 확인
    valid_images = []
    for path in image_paths:
        if os.path.exists(path):
            valid_images.append(path)
            print(f"✅ 이미지 확인: {path}")
        else:
            print(f"❌ 이미지 없음: {path}")
            
    if not valid_images:
        print("\n유효한 이미지가 없습니다.")
        return
        
    print(f"\n총 {len(valid_images)}개 이미지로 테스트 시작")
    
    # 테스트 실행
    if test_database_connection():
        if test_register_faces(valid_images):
            # 첫 번째 이미지로 필터링 테스트
            test_filtering(valid_images[0])
            test_legacy_compatibility(valid_images[0])
            check_database_records()
            
    print("\n✅ 테스트 완료!")
    print("TESTING_CHECKLIST.md 파일의 체크리스트를 확인하세요.")

if __name__ == "__main__":
    main()