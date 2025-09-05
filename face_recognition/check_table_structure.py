#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
테이블 구조 확인 스크립트
MariaDB와 MSSQL의 테이블 구조 비교
"""

import mysql.connector
import pyodbc
from config import Config

def check_mariadb_structure():
    """MariaDB 테이블 구조 확인"""
    print("\n=== MariaDB 테이블 구조 ===")
    
    config = Config()
    conn = mysql.connector.connect(
        host=config.MARIADB_CONFIG['host'],
        user=config.MARIADB_CONFIG['user'],
        password=config.MARIADB_CONFIG['password'],
        database=config.MARIADB_CONFIG['database'],
        charset='utf8mb4',
        collation='utf8mb4_unicode_ci'
    )
    
    cursor = conn.cursor()
    
    # face_recognition_logs 테이블 구조
    cursor.execute("""
        SHOW COLUMNS FROM face_recognition_logs
    """)
    
    print("\nface_recognition_logs 컬럼:")
    for col in cursor.fetchall():
        print(f"  {col[0]}: {col[1]}")
    
    cursor.close()
    conn.close()

def check_mssql_structure():
    """MSSQL 테이블 구조 확인"""
    print("\n=== MSSQL 테이블 구조 ===")
    
    config = Config()
    conn_str = (
        f"DRIVER={{{config.MSSQL_CONFIG['driver']}}};"
        f"SERVER={config.MSSQL_CONFIG['server']};"
        f"DATABASE={config.MSSQL_CONFIG['database']};"
        f"UID={config.MSSQL_CONFIG['username']};"
        f"PWD={config.MSSQL_CONFIG['password']};"
        f"TrustServerCertificate=yes"
    )
    
    conn = pyodbc.connect(conn_str)
    cursor = conn.cursor()
    
    # face_recognition_logs 테이블 구조
    cursor.execute("""
        SELECT 
            COLUMN_NAME,
            DATA_TYPE,
            CHARACTER_MAXIMUM_LENGTH,
            IS_NULLABLE
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'face_recognition_logs'
        ORDER BY ORDINAL_POSITION
    """)
    
    print("\nface_recognition_logs 컬럼:")
    for col in cursor.fetchall():
        if col[2]:
            print(f"  {col[0]}: {col[1]}({col[2]}) {col[3]}")
        else:
            print(f"  {col[0]}: {col[1]} {col[3]}")
    
    cursor.close()
    conn.close()

if __name__ == '__main__':
    check_mariadb_structure()
    check_mssql_structure()