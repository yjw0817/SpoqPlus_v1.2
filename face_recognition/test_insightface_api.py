#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
InsightFace API 테스트
"""

import requests
import json
import base64

def test_health():
    """헬스 체크 테스트"""
    print("1. 헬스 체크 테스트...")
    try:
        response = requests.get("http://localhost:5002/api/face/health")
        print(f"상태 코드: {response.status_code}")
        print(f"응답: {json.dumps(response.json(), indent=2, ensure_ascii=False)}")
        print("✅ 헬스 체크 성공\n")
    except Exception as e:
        print(f"❌ 헬스 체크 실패: {e}\n")

def test_detect_for_registration():
    """얼굴 검출 테스트"""
    print("2. 얼굴 검출 API 테스트...")
    
    # 테스트용 base64 이미지 (1x1 픽셀 투명 PNG)
    test_image = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=="
    
    data = {
        "image": test_image
    }
    
    try:
        response = requests.post(
            "http://localhost:5002/api/face/detect_for_registration",
            json=data,
            headers={"Content-Type": "application/json"}
        )
        print(f"상태 코드: {response.status_code}")
        print(f"응답: {json.dumps(response.json(), indent=2, ensure_ascii=False)}")
        
        # 응답 필드 확인
        result = response.json()
        required_fields = [
            'success', 'face_detected', 'face_encoding', 
            'glasses_detected', 'glasses_confidence', 
            'quality_score', 'liveness_check', 'security_warnings'
        ]
        
        print("\n필수 필드 확인:")
        for field in required_fields:
            if field in result:
                print(f"  ✅ {field}: 존재")
            else:
                print(f"  ❌ {field}: 없음")
                
    except Exception as e:
        print(f"❌ 얼굴 검출 테스트 실패: {e}\n")

def test_db_connection():
    """데이터베이스 연결 테스트"""
    print("\n3. 데이터베이스 연결 테스트...")
    try:
        import mysql.connector
        from config import Config
        
        conn = mysql.connector.connect(**Config.DB_CONFIG)
        print("✅ 데이터베이스 연결 성공")
        
        cursor = conn.cursor()
        cursor.execute("SELECT VERSION()")
        version = cursor.fetchone()
        print(f"MySQL 버전: {version[0]}")
        
        # collation 확인
        cursor.execute("SHOW VARIABLES LIKE 'collation_database'")
        collation = cursor.fetchone()
        print(f"Database Collation: {collation[1]}")
        
        cursor.close()
        conn.close()
        
    except Exception as e:
        print(f"❌ 데이터베이스 연결 실패: {e}")

if __name__ == "__main__":
    print("=== InsightFace API 테스트 시작 ===\n")
    test_health()
    test_detect_for_registration()
    test_db_connection()
    print("\n=== 테스트 완료 ===")