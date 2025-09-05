#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Face Recognition 데이터 마이그레이션 스크립트
MariaDB에서 MSSQL로 데이터 이전
"""

import mysql.connector
import pyodbc
import json
import base64
from datetime import datetime
import logging
from dotenv import load_dotenv
import os
import sys

# 환경변수 로드
load_dotenv()

# 로깅 설정
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('migration.log', encoding='utf-8'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

class FaceDataMigration:
    def __init__(self):
        """초기화"""
        # config.py에서 설정 가져오기
        try:
            from config import Config
            config = Config()
            
            # MariaDB 연결 설정
            self.mariadb_config = {
                'host': config.MARIADB_CONFIG['host'],
                'user': config.MARIADB_CONFIG['user'],
                'password': config.MARIADB_CONFIG['password'],
                'database': config.MARIADB_CONFIG['database'],
                'charset': 'utf8mb4',
                'collation': 'utf8mb4_unicode_ci',
                'use_unicode': True
            }
            
            # MSSQL 연결 설정
            self.mssql_config = {
                'server': config.MSSQL_CONFIG['server'],
                'database': config.MSSQL_CONFIG['database'],
                'username': config.MSSQL_CONFIG['username'],
                'password': config.MSSQL_CONFIG['password'],
                'driver': config.MSSQL_CONFIG['driver']
            }
            
            logger.info("config.py에서 설정 로드 완료")
            
        except ImportError:
            # config.py가 없으면 환경변수/기본값 사용
            logger.warning("config.py를 찾을 수 없습니다. 환경변수를 사용합니다.")
            
            self.mariadb_config = {
                'host': os.getenv('DB_HOST', '192.168.0.48'),
                'user': os.getenv('DB_USER', 'root'),
                'password': os.getenv('DB_PASSWORD', 'spoqdb11'),
                'database': os.getenv('DB_NAME', 'spoqplusdb'),
                'charset': 'utf8mb4',
                'collation': 'utf8mb4_unicode_ci',
                'use_unicode': True
            }
            
            self.mssql_config = {
                'server': os.getenv('MSSQL_SERVER', '192.168.0.48'),
                'database': os.getenv('MSSQL_DATABASE', 'FD'),
                'username': os.getenv('MSSQL_USERNAME', 'sa'),
                'password': os.getenv('MSSQL_PASSWORD', '1qazxsw2'),
                'driver': os.getenv('MSSQL_DRIVER', 'ODBC Driver 17 for SQL Server')
            }
        
        self.mariadb_conn = None
        self.mssql_conn = None
        
    def connect_databases(self):
        """데이터베이스 연결"""
        try:
            # MariaDB 연결
            logger.info("MariaDB 연결 중...")
            self.mariadb_conn = mysql.connector.connect(**self.mariadb_config)
            logger.info("✅ MariaDB 연결 성공")
            
            # MSSQL 연결
            logger.info("MSSQL 연결 중...")
            conn_str = (
                f"DRIVER={{{self.mssql_config['driver']}}};"
                f"SERVER={self.mssql_config['server']};"
                f"DATABASE={self.mssql_config['database']};"
                f"UID={self.mssql_config['username']};"
                f"PWD={self.mssql_config['password']};"
                f"TrustServerCertificate=yes"
            )
            self.mssql_conn = pyodbc.connect(conn_str)
            logger.info("✅ MSSQL 연결 성공")
            
            return True
            
        except Exception as e:
            logger.error(f"❌ 데이터베이스 연결 실패: {e}")
            return False
    
    def migrate_member_faces(self):
        """member_faces 테이블 마이그레이션"""
        logger.info("\n=== member_faces 테이블 마이그레이션 시작 ===")
        
        try:
            # MariaDB에서 데이터 조회
            maria_cursor = self.mariadb_conn.cursor(dictionary=True)
            maria_cursor.execute("""
                SELECT 
                    face_id,
                    member_id,
                    face_encoding,
                    face_image_data,
                    image_quality,
                    created_at,
                    updated_at,
                    is_primary,
                    is_active,
                    encoding_version,
                    feature_vector,
                    face_landmarks,
                    quality_score,
                    liveness_score,
                    glasses_detected,
                    security_level,
                    notes
                FROM member_faces
                ORDER BY face_id
            """)
            
            rows = maria_cursor.fetchall()
            logger.info(f"MariaDB에서 {len(rows)}개의 레코드 조회")
            
            # MSSQL에 데이터 삽입
            mssql_cursor = self.mssql_conn.cursor()
            
            # 기존 데이터 확인
            mssql_cursor.execute("SELECT COUNT(*) FROM member_faces")
            existing_count = mssql_cursor.fetchone()[0]
            
            if existing_count > 0:
                logger.warning(f"MSSQL에 이미 {existing_count}개의 레코드가 있습니다.")
                response = input("기존 데이터를 삭제하고 진행하시겠습니까? (y/n): ")
                if response.lower() == 'y':
                    mssql_cursor.execute("DELETE FROM member_faces")
                    logger.info("기존 데이터 삭제 완료")
                else:
                    logger.info("마이그레이션 중단")
                    return
            
            # IDENTITY_INSERT 활성화
            mssql_cursor.execute("SET IDENTITY_INSERT member_faces ON")
            
            # 데이터 삽입
            insert_count = 0
            for row in rows:
                try:
                    # BLOB 데이터 처리
                    face_encoding = row['face_encoding']
                    face_image_data = row['face_image_data']
                    feature_vector = row['feature_vector']
                    
                    # NULL 처리
                    face_landmarks = json.dumps(row['face_landmarks']) if row['face_landmarks'] else None
                    
                    mssql_cursor.execute("""
                        INSERT INTO member_faces (
                            face_id, member_id, face_encoding, face_image_data,
                            image_quality, created_at, updated_at, is_primary,
                            is_active, encoding_version, feature_vector, face_landmarks,
                            quality_score, liveness_score, glasses_detected,
                            security_level, notes
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    """, (
                        row['face_id'],
                        row['member_id'],
                        face_encoding,
                        face_image_data,
                        row['image_quality'],
                        row['created_at'],
                        row['updated_at'],
                        row['is_primary'],
                        row['is_active'],
                        row['encoding_version'],
                        feature_vector,
                        face_landmarks,
                        row['quality_score'],
                        row['liveness_score'],
                        row['glasses_detected'],
                        row['security_level'],
                        row['notes']
                    ))
                    
                    insert_count += 1
                    if insert_count % 100 == 0:
                        logger.info(f"진행 중: {insert_count}/{len(rows)}")
                        
                except Exception as e:
                    logger.error(f"레코드 삽입 실패 (face_id: {row['face_id']}): {e}")
            
            # IDENTITY_INSERT 비활성화
            mssql_cursor.execute("SET IDENTITY_INSERT member_faces OFF")
            
            # 커밋
            self.mssql_conn.commit()
            logger.info(f"✅ member_faces 마이그레이션 완료: {insert_count}개 레코드")
            
        except Exception as e:
            logger.error(f"❌ member_faces 마이그레이션 실패: {e}")
            self.mssql_conn.rollback()
    
    def migrate_face_recognition_logs(self):
        """face_recognition_logs 테이블 마이그레이션"""
        logger.info("\n=== face_recognition_logs 테이블 마이그레이션 시작 ===")
        
        try:
            # MariaDB에서 데이터 조회
            maria_cursor = self.mariadb_conn.cursor(dictionary=True)
            maria_cursor.execute("""
                SELECT 
                    log_id,
                    member_id,
                    face_id,
                    recognition_time,
                    similarity_score,
                    quality_score,
                    liveness_score,
                    action_type,
                    result,
                    error_message,
                    device_info,
                    location_info,
                    processing_time,
                    comp_cd,
                    bcoff_cd,
                    security_level,
                    api_key_id
                FROM face_recognition_logs
                ORDER BY log_id
            """)
            
            rows = maria_cursor.fetchall()
            logger.info(f"MariaDB에서 {len(rows)}개의 레코드 조회")
            
            # MSSQL에 데이터 삽입
            mssql_cursor = self.mssql_conn.cursor()
            
            # IDENTITY_INSERT 활성화
            mssql_cursor.execute("SET IDENTITY_INSERT face_recognition_logs ON")
            
            insert_count = 0
            for row in rows:
                try:
                    # JSON 데이터 처리
                    device_info = json.dumps(row['device_info']) if row['device_info'] else None
                    location_info = json.dumps(row['location_info']) if row['location_info'] else None
                    
                    mssql_cursor.execute("""
                        INSERT INTO face_recognition_logs (
                            log_id, member_id, face_id, recognition_time,
                            similarity_score, quality_score, liveness_score,
                            action_type, result, error_message, device_info,
                            location_info, processing_time, comp_cd, bcoff_cd,
                            security_level, api_key_id
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    """, (
                        row['log_id'],
                        row['member_id'],
                        row['face_id'],
                        row['recognition_time'],
                        row['similarity_score'],
                        row['quality_score'],
                        row['liveness_score'],
                        row['action_type'],
                        row['result'],
                        row['error_message'],
                        device_info,
                        location_info,
                        row['processing_time'],
                        row['comp_cd'],
                        row['bcoff_cd'],
                        row['security_level'],
                        row['api_key_id']
                    ))
                    
                    insert_count += 1
                    if insert_count % 1000 == 0:
                        logger.info(f"진행 중: {insert_count}/{len(rows)}")
                        
                except Exception as e:
                    logger.error(f"레코드 삽입 실패 (log_id: {row['log_id']}): {e}")
            
            # IDENTITY_INSERT 비활성화
            mssql_cursor.execute("SET IDENTITY_INSERT face_recognition_logs OFF")
            
            # 커밋
            self.mssql_conn.commit()
            logger.info(f"✅ face_recognition_logs 마이그레이션 완료: {insert_count}개 레코드")
            
        except Exception as e:
            logger.error(f"❌ face_recognition_logs 마이그레이션 실패: {e}")
            self.mssql_conn.rollback()
    
    def migrate_face_system_config(self):
        """face_system_config 테이블 마이그레이션"""
        logger.info("\n=== face_system_config 테이블 마이그레이션 시작 ===")
        
        try:
            # MariaDB에서 데이터 조회
            maria_cursor = self.mariadb_conn.cursor(dictionary=True)
            maria_cursor.execute("SELECT * FROM face_system_config")
            
            rows = maria_cursor.fetchall()
            logger.info(f"MariaDB에서 {len(rows)}개의 레코드 조회")
            
            # MSSQL에 데이터 삽입
            mssql_cursor = self.mssql_conn.cursor()
            
            for row in rows:
                mssql_cursor.execute("""
                    INSERT INTO face_system_config (
                        config_key, config_value, description,
                        created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?)
                """, (
                    row['config_key'],
                    row['config_value'],
                    row['description'],
                    row['created_at'],
                    row['updated_at']
                ))
            
            self.mssql_conn.commit()
            logger.info(f"✅ face_system_config 마이그레이션 완료: {len(rows)}개 레코드")
            
        except Exception as e:
            logger.error(f"❌ face_system_config 마이그레이션 실패: {e}")
            self.mssql_conn.rollback()
    
    def migrate_api_keys(self):
        """api_keys 테이블 마이그레이션"""
        logger.info("\n=== api_keys 테이블 마이그레이션 시작 ===")
        
        try:
            # MariaDB에서 데이터 조회
            maria_cursor = self.mariadb_conn.cursor(dictionary=True)
            maria_cursor.execute("SELECT * FROM api_keys")
            
            rows = maria_cursor.fetchall()
            logger.info(f"MariaDB에서 {len(rows)}개의 레코드 조회")
            
            # MSSQL에 데이터 삽입
            mssql_cursor = self.mssql_conn.cursor()
            
            # IDENTITY_INSERT 활성화
            mssql_cursor.execute("SET IDENTITY_INSERT api_keys ON")
            
            for row in rows:
                # permissions JSON 처리
                permissions = json.dumps(row['permissions']) if row['permissions'] else None
                
                mssql_cursor.execute("""
                    INSERT INTO api_keys (
                        id, api_key, comp_cd, bcoff_cd, description,
                        permissions, is_active, created_at, updated_at,
                        expires_at, last_used_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                """, (
                    row['id'],
                    row['api_key'],
                    row['comp_cd'],
                    row['bcoff_cd'],
                    row['description'],
                    permissions,
                    row['is_active'],
                    row['created_at'],
                    row['updated_at'],
                    row['expires_at'],
                    row['last_used_at']
                ))
            
            # IDENTITY_INSERT 비활성화
            mssql_cursor.execute("SET IDENTITY_INSERT api_keys OFF")
            
            self.mssql_conn.commit()
            logger.info(f"✅ api_keys 마이그레이션 완료: {len(rows)}개 레코드")
            
        except Exception as e:
            logger.error(f"❌ api_keys 마이그레이션 실패: {e}")
            self.mssql_conn.rollback()
    
    def verify_migration(self):
        """마이그레이션 검증"""
        logger.info("\n=== 마이그레이션 검증 ===")
        
        try:
            maria_cursor = self.mariadb_conn.cursor()
            mssql_cursor = self.mssql_conn.cursor()
            
            tables = [
                'member_faces',
                'face_recognition_logs',
                'face_system_config',
                'api_keys'
            ]
            
            for table in tables:
                # MariaDB 카운트
                maria_cursor.execute(f"SELECT COUNT(*) FROM {table}")
                maria_count = maria_cursor.fetchone()[0]
                
                # MSSQL 카운트
                mssql_cursor.execute(f"SELECT COUNT(*) FROM {table}")
                mssql_count = mssql_cursor.fetchone()[0]
                
                if maria_count == mssql_count:
                    logger.info(f"✅ {table}: {maria_count} 레코드 (일치)")
                else:
                    logger.warning(f"⚠️ {table}: MariaDB {maria_count} / MSSQL {mssql_count} (불일치)")
                    
        except Exception as e:
            logger.error(f"검증 실패: {e}")
    
    def close_connections(self):
        """연결 종료"""
        if self.mariadb_conn:
            self.mariadb_conn.close()
            logger.info("MariaDB 연결 종료")
            
        if self.mssql_conn:
            self.mssql_conn.close()
            logger.info("MSSQL 연결 종료")
    
    def run(self):
        """전체 마이그레이션 실행"""
        logger.info("=" * 50)
        logger.info("Face Recognition 데이터 마이그레이션 시작")
        logger.info("=" * 50)
        
        if not self.connect_databases():
            return
        
        try:
            # 순서대로 마이그레이션
            self.migrate_face_system_config()
            self.migrate_api_keys()
            self.migrate_member_faces()
            self.migrate_face_recognition_logs()
            
            # 검증
            self.verify_migration()
            
            logger.info("\n✅ 마이그레이션 완료!")
            
        except Exception as e:
            logger.error(f"마이그레이션 중 오류 발생: {e}")
            
        finally:
            self.close_connections()


if __name__ == '__main__':
    # pyodbc 설치 확인
    try:
        import pyodbc
    except ImportError:
        print("❌ pyodbc가 설치되지 않았습니다.")
        print("설치: pip install pyodbc")
        sys.exit(1)
    
    # 마이그레이션 실행
    migration = FaceDataMigration()
    migration.run()