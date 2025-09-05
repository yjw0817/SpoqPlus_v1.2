#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
고급 얼굴 인식 서비스 - FaceNet/DeepFace 기반
실제 얼굴 특징을 추출하는 전문 모델 사용
"""

import numpy as np
from deepface import DeepFace
import face_recognition
import cv2
import logging
from datetime import datetime
import json
import mysql.connector
from mysql.connector import pooling

# 로깅 설정
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class AdvancedFaceRecognitionService:
    def __init__(self, db_config):
        """
        고급 얼굴 인식 서비스 초기화
        여러 모델을 선택적으로 사용 가능
        """
        self.db_config = db_config
        self.connection_pool = mysql.connector.pooling.MySQLConnectionPool(
            pool_name="face_pool",
            pool_size=10,
            **db_config
        )
        
        # 사용 가능한 모델들
        self.available_models = {
            'facenet': {'dim': 128, 'accuracy': 'high'},
            'facenet512': {'dim': 512, 'accuracy': 'very_high'},
            'vgg-face': {'dim': 2622, 'accuracy': 'high'},
            'openface': {'dim': 128, 'accuracy': 'medium'},
            'deepface': {'dim': 4096, 'accuracy': 'very_high'},
            'arcface': {'dim': 512, 'accuracy': 'highest'}
        }
        
        # 기본 모델 설정 (FaceNet 512)
        self.model_name = 'facenet512'
        self.encoding_dim = self.available_models[self.model_name]['dim']
        
        logger.info(f"고급 얼굴 인식 서비스 초기화 - 모델: {self.model_name}")
    
    def extract_face_embedding(self, image_path_or_array, model='facenet512'):
        """
        전문 모델을 사용한 얼굴 임베딩 추출
        
        Args:
            image_path_or_array: 이미지 경로 또는 numpy array
            model: 사용할 모델 이름
        
        Returns:
            dict: 임베딩 및 메타데이터
        """
        try:
            # DeepFace를 사용한 임베딩 추출
            if model in ['facenet', 'facenet512', 'vgg-face', 'arcface']:
                embedding_obj = DeepFace.represent(
                    img_path=image_path_or_array,
                    model_name=model,
                    enforce_detection=True,
                    detector_backend='opencv',
                    align=True
                )
                
                if embedding_obj:
                    embedding = embedding_obj[0]['embedding']
                    face_region = embedding_obj[0]['facial_area']
                    
                    # 품질 평가
                    quality_score = self.evaluate_face_quality(
                        image_path_or_array, 
                        face_region
                    )
                    
                    return {
                        'success': True,
                        'embedding': embedding,
                        'dimension': len(embedding),
                        'model': model,
                        'face_region': face_region,
                        'quality_score': quality_score,
                        'extraction_method': 'deepface'
                    }
            
            # face_recognition 라이브러리 사용 (dlib 기반)
            elif model == 'dlib':
                if isinstance(image_path_or_array, str):
                    image = face_recognition.load_image_file(image_path_or_array)
                else:
                    image = image_path_or_array
                
                face_locations = face_recognition.face_locations(image)
                if face_locations:
                    face_encodings = face_recognition.face_encodings(image, face_locations)
                    if face_encodings:
                        return {
                            'success': True,
                            'embedding': face_encodings[0].tolist(),
                            'dimension': 128,
                            'model': 'dlib',
                            'face_region': face_locations[0],
                            'quality_score': 0.85,  # dlib은 기본적으로 높은 품질
                            'extraction_method': 'face_recognition'
                        }
            
            return {
                'success': False,
                'error': 'No face detected'
            }
            
        except Exception as e:
            logger.error(f"임베딩 추출 오류: {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
    
    def evaluate_face_quality(self, image, face_region):
        """
        얼굴 이미지 품질 평가
        """
        if isinstance(image, str):
            image = cv2.imread(image)
        
        # 얼굴 영역 추출
        x, y, w, h = face_region['x'], face_region['y'], face_region['w'], face_region['h']
        face_img = image[y:y+h, x:x+w]
        
        # 품질 지표 계산
        scores = []
        
        # 1. 크기 점수
        size_score = min(w * h / (image.shape[0] * image.shape[1]) * 10, 1.0)
        scores.append(size_score)
        
        # 2. 선명도 점수 (Laplacian)
        gray = cv2.cvtColor(face_img, cv2.COLOR_BGR2GRAY)
        sharpness = cv2.Laplacian(gray, cv2.CV_64F).var()
        sharpness_score = min(sharpness / 1000, 1.0)
        scores.append(sharpness_score)
        
        # 3. 밝기 점수
        brightness = np.mean(gray)
        brightness_score = 1.0 - abs(brightness - 127.5) / 127.5
        scores.append(brightness_score)
        
        # 4. 대비 점수
        contrast = gray.std()
        contrast_score = min(contrast / 50, 1.0)
        scores.append(contrast_score)
        
        return np.mean(scores)
    
    def compare_embeddings(self, embedding1, embedding2, method='cosine'):
        """
        두 임베딩 간 유사도 계산
        
        Args:
            embedding1: 첫 번째 임베딩
            embedding2: 두 번째 임베딩
            method: 비교 방법 ('cosine', 'euclidean')
        
        Returns:
            float: 유사도 점수 (0-1)
        """
        embedding1 = np.array(embedding1)
        embedding2 = np.array(embedding2)
        
        if method == 'cosine':
            # 코사인 유사도
            similarity = np.dot(embedding1, embedding2) / (
                np.linalg.norm(embedding1) * np.linalg.norm(embedding2)
            )
            return float(similarity)
        
        elif method == 'euclidean':
            # 유클리드 거리를 유사도로 변환
            distance = np.linalg.norm(embedding1 - embedding2)
            # 거리를 0-1 범위의 유사도로 변환
            similarity = 1 / (1 + distance)
            return float(similarity)
        
        else:
            raise ValueError(f"Unknown method: {method}")
    
    def migrate_existing_data(self, batch_size=100):
        """
        기존 MediaPipe 데이터를 고급 모델로 마이그레이션
        """
        connection = self.connection_pool.get_connection()
        cursor = connection.cursor(dictionary=True)
        
        try:
            # 마이그레이션 테이블 생성
            cursor.execute("""
                CREATE TABLE IF NOT EXISTS member_faces_advanced (
                    face_id INT AUTO_INCREMENT PRIMARY KEY,
                    mem_sno VARCHAR(50) NOT NULL,
                    face_encoding_original TEXT,  -- 기존 MediaPipe 데이터
                    face_encoding_advanced TEXT,  -- 새로운 고급 임베딩
                    encoding_model VARCHAR(50),   -- 사용된 모델
                    encoding_dimension INT,       -- 임베딩 차원
                    quality_score DECIMAL(3,2),
                    glasses_detected BOOLEAN DEFAULT FALSE,
                    migration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                    is_active BOOLEAN DEFAULT TRUE,
                    INDEX idx_mem_sno (mem_sno),
                    INDEX idx_model (encoding_model)
                )
            """)
            
            # 기존 데이터 조회
            cursor.execute("""
                SELECT 
                    face_id, 
                    mem_sno, 
                    face_encoding,
                    face_image_path,
                    glasses_detected
                FROM member_faces
                WHERE is_active = 1
                LIMIT %s
            """, (batch_size,))
            
            faces = cursor.fetchall()
            migrated_count = 0
            failed_count = 0
            
            for face in faces:
                try:
                    # 이미지가 있는 경우에만 새로운 임베딩 생성
                    if face.get('face_image_path'):
                        # 새로운 임베딩 추출
                        result = self.extract_face_embedding(
                            face['face_image_path'],
                            model=self.model_name
                        )
                        
                        if result['success']:
                            # 마이그레이션 데이터 저장
                            cursor.execute("""
                                INSERT INTO member_faces_advanced 
                                (mem_sno, face_encoding_original, face_encoding_advanced,
                                 encoding_model, encoding_dimension, quality_score, glasses_detected)
                                VALUES (%s, %s, %s, %s, %s, %s, %s)
                            """, (
                                face['mem_sno'],
                                face['face_encoding'],
                                json.dumps(result['embedding']),
                                result['model'],
                                result['dimension'],
                                result['quality_score'],
                                face['glasses_detected']
                            ))
                            migrated_count += 1
                        else:
                            failed_count += 1
                            logger.error(f"마이그레이션 실패 - mem_sno: {face['mem_sno']}")
                    else:
                        # 이미지가 없는 경우 원본 데이터만 저장
                        cursor.execute("""
                            INSERT INTO member_faces_advanced 
                            (mem_sno, face_encoding_original, encoding_model, glasses_detected)
                            VALUES (%s, %s, 'mediapipe_original', %s)
                        """, (
                            face['mem_sno'],
                            face['face_encoding'],
                            face['glasses_detected']
                        ))
                
                except Exception as e:
                    failed_count += 1
                    logger.error(f"개별 마이그레이션 오류: {str(e)}")
            
            connection.commit()
            
            return {
                'success': True,
                'migrated': migrated_count,
                'failed': failed_count,
                'total': len(faces)
            }
            
        except Exception as e:
            connection.rollback()
            logger.error(f"마이그레이션 오류: {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
        finally:
            cursor.close()
            connection.close()
    
    def hybrid_matching(self, test_embedding, member_id):
        """
        하이브리드 매칭: 새로운 임베딩이 없으면 기존 데이터 사용
        """
        connection = self.connection_pool.get_connection()
        cursor = connection.cursor(dictionary=True)
        
        try:
            # 1. 먼저 고급 임베딩 확인
            cursor.execute("""
                SELECT face_encoding_advanced, encoding_model, glasses_detected
                FROM member_faces_advanced
                WHERE mem_sno = %s 
                AND face_encoding_advanced IS NOT NULL
                AND is_active = 1
            """, (member_id,))
            
            advanced_face = cursor.fetchone()
            
            if advanced_face:
                # 고급 임베딩으로 비교
                stored_embedding = json.loads(advanced_face['face_encoding_advanced'])
                similarity = self.compare_embeddings(test_embedding, stored_embedding)
                
                return {
                    'matched': similarity >= 0.6,  # FaceNet은 더 낮은 임계값 사용
                    'similarity': similarity,
                    'method': 'advanced',
                    'model': advanced_face['encoding_model']
                }
            
            # 2. 고급 임베딩이 없으면 기존 MediaPipe 데이터 사용
            cursor.execute("""
                SELECT face_encoding, glasses_detected
                FROM member_faces
                WHERE mem_sno = %s AND is_active = 1
            """, (member_id,))
            
            original_face = cursor.fetchone()
            
            if original_face:
                # 기존 방식으로 비교 (임계값 낮춤)
                stored_embedding = json.loads(original_face['face_encoding'])
                similarity = self.compare_embeddings(test_embedding[:128], stored_embedding[:128])
                
                return {
                    'matched': similarity >= 0.70,  # 기존 데이터는 더 관대한 임계값
                    'similarity': similarity,
                    'method': 'legacy',
                    'model': 'mediapipe'
                }
            
            return {
                'matched': False,
                'similarity': 0,
                'method': 'none',
                'error': 'No face data found'
            }
            
        finally:
            cursor.close()
            connection.close()


# 사용 예시
if __name__ == "__main__":
    db_config = {
        'host': 'localhost',
        'user': 'root',
        'password': 'password',
        'database': 'spoqplus'
    }
    
    service = AdvancedFaceRecognitionService(db_config)
    
    # 테스트: 새로운 얼굴 임베딩 추출
    result = service.extract_face_embedding("test_face.jpg", model='facenet512')
    if result['success']:
        print(f"임베딩 추출 성공: {result['dimension']}차원")
        print(f"품질 점수: {result['quality_score']}")
    
    # 마이그레이션 테스트
    migration_result = service.migrate_existing_data(batch_size=10)
    print(f"마이그레이션 결과: {migration_result}")