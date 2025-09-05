#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
간단한 데이터 마이그레이션 스크립트
특정 테이블만 선택적으로 마이그레이션
"""

import mysql.connector
import pyodbc
import json
from datetime import datetime
import sys
import os

# config.py에서 설정 가져오기
try:
    from config import Config
    config = Config()
    MARIADB_CONFIG = {
        'host': config.MARIADB_CONFIG['host'],
        'user': config.MARIADB_CONFIG['user'],
        'password': config.MARIADB_CONFIG['password'],
        'database': config.MARIADB_CONFIG['database'],
        'charset': 'utf8mb4',
        'collation': 'utf8mb4_unicode_ci',  # MariaDB 호환 collation
        'use_unicode': True
    }
    MSSQL_CONFIG = {
        'server': config.MSSQL_CONFIG['server'],
        'database': config.MSSQL_CONFIG['database'],
        'username': config.MSSQL_CONFIG['username'],
        'password': config.MSSQL_CONFIG['password'],
        'driver': config.MSSQL_CONFIG['driver']
    }
    print("✅ config.py에서 설정을 불러왔습니다.")
    print(f"   MariaDB: {MARIADB_CONFIG['host']}/{MARIADB_CONFIG['database']}")
    print(f"   MSSQL: {MSSQL_CONFIG['server']}/{MSSQL_CONFIG['database']}")
except ImportError:
    print("❌ config.py를 찾을 수 없습니다. 기본값을 사용합니다.")
    # 기본값
    MARIADB_CONFIG = {
        'host': '192.168.0.48',
        'user': 'root',
        'password': 'spoqdb11',
        'database': 'spoqplusdb',
        'charset': 'utf8mb4',
        'collation': 'utf8mb4_unicode_ci',
        'use_unicode': True
    }
    MSSQL_CONFIG = {
        'server': '192.168.0.48',
        'database': 'FD',
        'username': 'sa',
        'password': '1qazxsw2',
        'driver': 'ODBC Driver 17 for SQL Server'
    }

def migrate_table(table_name, identity_column=None):
    """단일 테이블 마이그레이션"""
    print(f"\n{'='*50}")
    print(f"{table_name} 테이블 마이그레이션 시작")
    print(f"{'='*50}")
    
    # MariaDB 연결
    maria_conn = mysql.connector.connect(**MARIADB_CONFIG)
    maria_cursor = maria_conn.cursor(dictionary=True)
    
    # MSSQL 연결
    conn_str = (
        f"DRIVER={{{MSSQL_CONFIG['driver']}}};"
        f"SERVER={MSSQL_CONFIG['server']};"
        f"DATABASE={MSSQL_CONFIG['database']};"
        f"UID={MSSQL_CONFIG['username']};"
        f"PWD={MSSQL_CONFIG['password']};"
        f"TrustServerCertificate=yes"
    )
    mssql_conn = pyodbc.connect(conn_str)
    mssql_cursor = mssql_conn.cursor()
    
    try:
        # MariaDB에서 데이터 조회
        maria_cursor.execute(f"SELECT * FROM {table_name}")
        rows = maria_cursor.fetchall()
        print(f"MariaDB에서 {len(rows)}개 레코드 조회")
        
        if len(rows) == 0:
            print("마이그레이션할 데이터가 없습니다.")
            return
        
        # 기존 데이터 확인
        mssql_cursor.execute(f"SELECT COUNT(*) FROM {table_name}")
        existing_count = mssql_cursor.fetchone()[0]
        
        if existing_count > 0:
            response = input(f"MSSQL에 이미 {existing_count}개의 레코드가 있습니다. 삭제하시겠습니까? (y/n): ")
            if response.lower() == 'y':
                mssql_cursor.execute(f"DELETE FROM {table_name}")
                print("기존 데이터 삭제 완료")
        
        # IDENTITY_INSERT 설정
        if identity_column:
            mssql_cursor.execute(f"SET IDENTITY_INSERT {table_name} ON")
        
        # 첫 번째 행으로 컬럼 목록 생성
        columns = list(rows[0].keys())
        placeholders = ', '.join(['?' for _ in columns])
        column_list = ', '.join(columns)
        
        # INSERT 문 생성
        insert_sql = f"INSERT INTO {table_name} ({column_list}) VALUES ({placeholders})"
        
        # 데이터 삽입
        success_count = 0
        for i, row in enumerate(rows):
            try:
                # 값 리스트 생성
                values = []
                for col in columns:
                    value = row[col]
                    # JSON 데이터 처리
                    if isinstance(value, (dict, list)):
                        value = json.dumps(value)
                    values.append(value)
                
                mssql_cursor.execute(insert_sql, values)
                success_count += 1
                
                if (i + 1) % 100 == 0:
                    print(f"진행 중: {i + 1}/{len(rows)}")
                    
            except Exception as e:
                print(f"레코드 {i + 1} 삽입 실패: {e}")
                print(f"데이터: {row}")
        
        # IDENTITY_INSERT 해제
        if identity_column:
            mssql_cursor.execute(f"SET IDENTITY_INSERT {table_name} OFF")
        
        # 커밋
        mssql_conn.commit()
        print(f"✅ 마이그레이션 완료: {success_count}/{len(rows)} 레코드")
        
    except Exception as e:
        print(f"❌ 오류 발생: {e}")
        mssql_conn.rollback()
        
    finally:
        maria_cursor.close()
        maria_conn.close()
        mssql_cursor.close()
        mssql_conn.close()


def main():
    """메인 함수"""
    print("\nFace Recognition 데이터 마이그레이션")
    print("MariaDB → MSSQL")
    print("="*50)
    
    while True:
        print("\n마이그레이션할 테이블을 선택하세요:")
        print("1. member_faces")
        print("2. face_recognition_logs")
        print("3. face_system_config")
        print("4. api_keys")
        print("5. 모든 테이블")
        print("0. 종료")
        
        choice = input("\n선택: ")
        
        if choice == '0':
            break
        elif choice == '1':
            migrate_table('member_faces', 'face_id')
        elif choice == '2':
            migrate_table('face_recognition_logs', 'log_id')
        elif choice == '3':
            migrate_table('face_system_config')
        elif choice == '4':
            migrate_table('api_keys', 'id')
        elif choice == '5':
            migrate_table('face_system_config')
            migrate_table('api_keys', 'id')
            migrate_table('member_faces', 'face_id')
            migrate_table('face_recognition_logs', 'log_id')
        else:
            print("잘못된 선택입니다.")


if __name__ == '__main__':
    main()