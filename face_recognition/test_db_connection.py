#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
데이터베이스 연결 테스트
"""

import mysql.connector
from config import Config

def test_db_connection():
    """데이터베이스 연결 테스트"""
    print("데이터베이스 연결 테스트 시작...")
    print(f"설정: {Config.DB_CONFIG}")
    
    try:
        # 연결 시도
        conn = mysql.connector.connect(**Config.DB_CONFIG)
        print("✅ 데이터베이스 연결 성공!")
        
        # 커서 생성
        cursor = conn.cursor()
        
        # 테이블 확인
        cursor.execute("SHOW TABLES LIKE 'member_faces'")
        result = cursor.fetchone()
        if result:
            print("✅ member_faces 테이블 존재")
            
            # 테이블 구조 확인
            cursor.execute("DESCRIBE member_faces")
            columns = cursor.fetchall()
            print("\n📋 member_faces 테이블 구조:")
            for col in columns:
                print(f"  - {col[0]}: {col[1]}")
        else:
            print("❌ member_faces 테이블이 없습니다!")
        
        # face_recognition_logs 테이블 확인
        cursor.execute("SHOW TABLES LIKE 'face_recognition_logs'")
        result = cursor.fetchone()
        if result:
            print("\n✅ face_recognition_logs 테이블 존재")
        else:
            print("\n❌ face_recognition_logs 테이블이 없습니다!")
        
        cursor.close()
        conn.close()
        
    except mysql.connector.Error as err:
        print(f"❌ 데이터베이스 연결 실패: {err}")
        print(f"   에러 코드: {err.errno}")
        print(f"   에러 메시지: {err.msg}")
    except Exception as e:
        print(f"❌ 예외 발생: {e}")

if __name__ == "__main__":
    test_db_connection()