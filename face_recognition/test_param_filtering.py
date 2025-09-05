#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
param1/param2 필터링 테스트 스크립트
"""

import requests
import json
import base64
from datetime import datetime

# 테스트 서버 설정
BASE_URL = "http://localhost:5002"

def test_health_check():
    """헬스 체크"""
    print("1. 헬스 체크 테스트")
    response = requests.get(f"{BASE_URL}/api/face/health")
    print(f"상태: {response.status_code}")
    print(f"응답: {json.dumps(response.json(), indent=2, ensure_ascii=False)}")
    print("-" * 50)
    return response.json()

def load_test_image(image_path):
    """테스트 이미지 로드"""
    with open(image_path, 'rb') as f:
        image_data = base64.b64encode(f.read()).decode()
    return f"data:image/jpeg;base64,{image_data}"

def test_register_with_params(member_id, image_data, param1, param2):
    """param1, param2를 사용한 얼굴 등록 테스트"""
    print(f"\n2. 얼굴 등록 테스트 - member_id: {member_id}, param1: {param1}, param2: {param2}")
    
    data = {
        "member_id": member_id,
        "image": image_data,
        "security_level": 3,
        "notes": f"테스트 등록 - {datetime.now()}",
        "param1": param1,
        "param2": param2
    }
    
    response = requests.post(f"{BASE_URL}/api/face/register", json=data)
    print(f"상태: {response.status_code}")
    print(f"응답: {json.dumps(response.json(), indent=2, ensure_ascii=False)}")
    print("-" * 50)
    return response.json()

def test_recognize_with_filtering(image_data, param1=None, param2=None):
    """param1, param2 필터링을 사용한 얼굴 인식 테스트"""
    filter_desc = []
    if param1:
        filter_desc.append(f"param1={param1}")
    if param2:
        filter_desc.append(f"param2={param2}")
    filter_str = ", ".join(filter_desc) if filter_desc else "필터 없음"
    
    print(f"\n3. 얼굴 인식 테스트 - 필터: {filter_str}")
    
    data = {"image": image_data}
    if param1:
        data["param1"] = param1
    if param2:
        data["param2"] = param2
    
    response = requests.post(f"{BASE_URL}/api/face/recognize", json=data)
    print(f"상태: {response.status_code}")
    result = response.json()
    print(f"응답: {json.dumps(result, indent=2, ensure_ascii=False)}")
    
    if result.get('success') and result.get('matched'):
        print(f"✅ 인식 성공! member_id: {result.get('member_id')}, 유사도: {result.get('similarity')}")
    else:
        print("❌ 인식 실패")
    print("-" * 50)
    return result

def test_recognize_for_checkin(image_data, comp_cd, bcoff_cd):
    """체크인용 인식 테스트 (레거시 파라미터 → param1/param2 자동 변환)"""
    print(f"\n4. 체크인용 인식 테스트 - comp_cd: {comp_cd}, bcoff_cd: {bcoff_cd}")
    
    data = {
        "image": image_data,
        "comp_cd": comp_cd,
        "bcoff_cd": bcoff_cd,
        "security_level": 3
    }
    
    response = requests.post(f"{BASE_URL}/api/face/recognize_for_checkin", json=data)
    print(f"상태: {response.status_code}")
    result = response.json()
    print(f"응답: {json.dumps(result, indent=2, ensure_ascii=False)}")
    
    if result.get('success') and result.get('matched'):
        print(f"✅ 체크인 허용! member_id: {result.get('member_id')}")
    else:
        print("❌ 체크인 거부")
    print("-" * 50)
    return result

def run_all_tests():
    """모든 테스트 실행"""
    print("=" * 70)
    print("param1/param2 필터링 종합 테스트")
    print("=" * 70)
    
    # 1. 헬스 체크
    health = test_health_check()
    if health.get('status') != 'healthy':
        print("❌ 서버가 정상적이지 않습니다.")
        return
    
    # 테스트 이미지 준비 (실제 이미지 경로로 변경 필요)
    # image_data = load_test_image("test_face.jpg")
    
    # 테스트용 더미 이미지 데이터
    dummy_image = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k="
    
    print("\n🔹 시나리오 1: 다양한 지점에 회원 등록")
    # 회사 A, 지점 1
    test_register_with_params("MEM2025001", dummy_image, "COMP_A", "BRANCH_01")
    # 회사 A, 지점 2
    test_register_with_params("MEM2025002", dummy_image, "COMP_A", "BRANCH_02")
    # 회사 B, 지점 1
    test_register_with_params("MEM2025003", dummy_image, "COMP_B", "BRANCH_01")
    
    print("\n🔹 시나리오 2: 필터링 없이 전체 조회")
    test_recognize_with_filtering(dummy_image)
    
    print("\n🔹 시나리오 3: 특정 회사만 필터링")
    test_recognize_with_filtering(dummy_image, param1="COMP_A")
    
    print("\n🔹 시나리오 4: 특정 회사의 특정 지점만 필터링")
    test_recognize_with_filtering(dummy_image, param1="COMP_A", param2="BRANCH_01")
    
    print("\n🔹 시나리오 5: 레거시 파라미터 호환성 테스트")
    test_recognize_for_checkin(dummy_image, comp_cd="COMP_A", bcoff_cd="BRANCH_01")
    
    print("\n✅ 모든 테스트 완료!")

if __name__ == "__main__":
    run_all_tests()