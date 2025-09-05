#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Enhanced MediaPipe 기반 얼굴 인식 서비스 v2.0
- MediaPipe 468개 랜드마크 기반 얼굴 임베딩
- 3단계 보안 시스템
- 안경 인식 기능
- 코사인 유사도 기반 매칭
- MySQL 데이터베이스 연동
"""

import cv2
import numpy as np
import mediapipe as mp
import time
import json
import math
import mysql.connector
from sklearn.metrics.pairwise import cosine_similarity
from flask import Flask, request, jsonify, render_template_string
from flask_cors import CORS
from typing import Dict, List, Tuple, Optional, Any
import logging
from io import BytesIO
from PIL import Image
import base64
import io
import secrets
import string

# 로깅 설정
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)

# 데이터베이스 설정
DB_CONFIG = {
    'host': '192.168.0.48',
    'user': 'root',
    'password': 'spoqdb11',
    'database': 'spoqplusdb',
    'charset': 'utf8mb4',
    'collation': 'utf8mb4_unicode_ci',  # 기존 테이블과 동일한 collation 사용
    'autocommit': True,
    'connection_timeout': 10
}

def get_real_client_ip():
    """실제 클라이언트 IP 주소 가져오기 (PHP getIPAddress()와 동일한 로직)"""
    # 프록시나 로드 밸런서를 통해 올 수 있는 헤더들 확인
    headers_to_check = [
        'HTTP_CF_CONNECTING_IP',      # Cloudflare
        'HTTP_CLIENT_IP',             # 클라이언트 IP
        'HTTP_X_FORWARDED_FOR',       # 프록시를 통한 포워딩
        'HTTP_X_FORWARDED',           # 포워딩
        'HTTP_X_CLUSTER_CLIENT_IP',   # 클러스터 클라이언트 IP
        'HTTP_FORWARDED_FOR',         # 포워딩
        'HTTP_FORWARDED',             # 포워딩
        'HTTP_VIA',                   # Via 헤더
        'REMOTE_ADDR'                 # 기본 원격 주소
    ]
    
    for header in headers_to_check:
        ip = request.environ.get(header)
        if ip and ip != 'unknown':
            # 여러 IP가 콤마로 구분된 경우 첫 번째 IP 사용
            if ',' in ip:
                ip = ip.split(',')[0].strip()
            
            # IPv4 또는 IPv6 형식 확인
            if ip and ip not in ['127.0.0.1', '::1', 'localhost']:
                return ip
    
    # 모든 헤더가 localhost인 경우, 실제 네트워크 인터페이스 IP 가져오기
    try:
        import socket
        hostname = socket.gethostname()
        local_ip = socket.gethostbyname(hostname)
        if local_ip and local_ip != '127.0.0.1':
            return local_ip
    except:
        pass
    
    # 최종적으로 REMOTE_ADDR 반환
    return request.environ.get('REMOTE_ADDR', '127.0.0.1')

def generate_session_id():
    """PHP session_id()와 유사한 형식의 세션 ID 생성"""
    # PHP 세션 ID와 유사한 26자리 랜덤 문자열 생성
    chars = string.ascii_lowercase + string.digits
    return ''.join(secrets.choice(chars) for _ in range(26))

def safe_convert(obj: Any) -> Any:
    """NumPy 타입을 JSON 직렬화 가능한 Python 타입으로 변환"""
    if isinstance(obj, np.integer):
        return int(obj)
    elif isinstance(obj, np.floating):
        return float(obj)
    elif isinstance(obj, np.bool_):
        return bool(obj)
    elif isinstance(obj, np.ndarray):
        return obj.tolist()
    elif isinstance(obj, dict):
        return {key: safe_convert(value) for key, value in obj.items()}
    elif isinstance(obj, list):
        return [safe_convert(item) for item in obj]
    elif isinstance(obj, tuple):
        return tuple(safe_convert(item) for item in obj)
    else:
        return obj

def safe_jsonify(data: Dict) -> Any:
    """안전한 JSON 직렬화"""
    try:
        converted_data = safe_convert(data)
        return jsonify(converted_data)
    except Exception as e:
        logger.error(f"JSON 직렬화 오류: {e}")
        return jsonify({
            'success': False,
            'error': f'JSON 직렬화 오류: {str(e)}'
        }), 500

class EnhancedFaceRecognitionService:
    def __init__(self):
        logger.info("🎯 Enhanced Face Recognition Service 초기화 중...")
        
        # MediaPipe 초기화
        self.mp_face_detection = mp.solutions.face_detection
        self.mp_face_mesh = mp.solutions.face_mesh
        
        # 얼굴 검출기 (고정밀도)
        self.face_detection = self.mp_face_detection.FaceDetection(
            model_selection=1,  # long-range 모델
            min_detection_confidence=0.7
        )
        
        # 얼굴 메시 (468개 랜드마크)
        self.face_mesh = self.mp_face_mesh.FaceMesh(
            static_image_mode=True,
            max_num_faces=1,
            refine_landmarks=True,
            min_detection_confidence=0.7,
            min_tracking_confidence=0.5
        )
        
        # 보안 임계값
        self.security_thresholds = {
            'face_similarity_threshold': 0.85,
            'face_size_min': 0.1,
            'face_size_max': 0.8,
            'glasses_confidence_threshold': 0.25,  # 0.35 → 0.25로 더 민감하게 조정
            'liveness_threshold': 0.7,            # Liveness 검출 임계값
            'texture_variance_min': 50,           # 최소 텍스처 분산 (사진 vs 실제)
            'reflection_pattern_threshold': 0.3    # 화면 반사 패턴 임계값
        }
        
        # 얼굴 데이터베이스 (메모리)
        self.face_database = {
            'normal': {'embeddings': [], 'mem_snos': []},
            'glasses': {'embeddings': [], 'mem_snos': []}
        }
        
        # 랜드마크 인덱스 확장
        self.LEFT_EYE_LANDMARKS = [33, 7, 163, 144, 145, 153, 154, 155, 133, 173, 157, 158, 159, 160, 161, 246]
        self.RIGHT_EYE_LANDMARKS = [362, 382, 381, 380, 374, 373, 390, 249, 263, 466, 388, 387, 386, 385, 384, 398]
        self.NOSE_BRIDGE_LANDMARKS = [6, 8, 9, 10, 151]      # 코다리 (안경 착용 지점)
        self.FOREHEAD_LANDMARKS = [10, 151, 9, 8]            # 이마 영역 (반사 검출)
        
        # 데이터베이스 연결 확인 및 얼굴 데이터 로드
        self.check_database_connection()
        self.load_face_database()
        
        logger.info("✅ Enhanced Face Recognition Service 초기화 완료")

    def check_database_connection(self):
        """데이터베이스 연결 확인"""
        try:
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()
            
            # member_faces 테이블 확인
            cursor.execute("SHOW TABLES LIKE 'member_faces'")
            table_exists = cursor.fetchone() is not None
            
            if table_exists:
                cursor.execute("SELECT COUNT(*) FROM member_faces WHERE is_active = 1")
                face_count = cursor.fetchone()[0]
                logger.info(f"✅ 데이터베이스 연결 성공 - member_faces 테이블: {face_count}개 레코드")
            else:
                logger.warning("⚠️ member_faces 테이블이 존재하지 않습니다")
            
            cursor.close()
            conn.close()
            return True
            
        except Exception as e:
            logger.error(f"❌ 데이터베이스 연결 실패: {e}")
            return False

    def load_face_database(self):
        """데이터베이스에서 얼굴 데이터 로드"""
        try:
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()
            
            cursor.execute("""
                SELECT mem_sno, face_encoding, glasses_detected, quality_score
                FROM member_faces 
                WHERE is_active = 1
            """)
            
            results = cursor.fetchall()
            
            # 초기화
            self.face_database = {
                'normal': {'embeddings': [], 'mem_snos': []},
                'glasses': {'embeddings': [], 'mem_snos': []}
            }
            
            for mem_sno, encoding_json, glasses_detected, quality_score in results:
                try:
                    encoding = np.array(json.loads(encoding_json))
                    if len(encoding) == 128:
                        category = 'glasses' if glasses_detected else 'normal'
                        
                        if len(self.face_database[category]['embeddings']) == 0:
                            self.face_database[category]['embeddings'] = encoding.reshape(1, -1)
                        else:
                            self.face_database[category]['embeddings'] = np.vstack([
                                self.face_database[category]['embeddings'], 
                                encoding.reshape(1, -1)
                            ])
                        
                        # mem_sno를 그대로 사용 (VARCHAR로 저장됨)
                        self.face_database[category]['mem_snos'].append(mem_sno)
                        
                except Exception as e:
                    logger.warning(f"인코딩 파싱 실패 (회원 {mem_sno}): {e}")
                    continue
            
            cursor.close()
            conn.close()
            
            normal_count = len(self.face_database['normal']['mem_snos'])
            glasses_count = len(self.face_database['glasses']['mem_snos'])
            logger.info(f"✅ 얼굴 데이터 로드 완료 - 일반: {normal_count}개, 안경: {glasses_count}개")
            
        except Exception as e:
            logger.error(f"❌ 얼굴 데이터 로드 실패: {e}")

    def save_face_to_database(self, mem_sno: str, face_embedding: np.ndarray, glasses_detected: bool, quality_score: float = 0.85):
        """얼굴 데이터를 데이터베이스에 저장"""
        try:
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()
            
            # 기존 데이터 확인 (mem_sno를 문자열 그대로 사용)
            cursor.execute("SELECT face_id FROM member_faces WHERE mem_sno = %s AND is_active = 1", (mem_sno,))
            existing = cursor.fetchone()
            
            # NumPy 타입을 Python 기본 타입으로 변환
            glasses_detected_db = bool(glasses_detected)  # numpy.bool_ → bool 변환
            quality_score_db = float(quality_score)       # numpy.float → float 변환
            
            # JSON으로 인코딩 저장
            encoding_json = json.dumps(face_embedding.tolist())
            
            if existing:
                # 업데이트
                cursor.execute("""
                    UPDATE member_faces SET
                        face_encoding = %s,
                        glasses_detected = %s,
                        quality_score = %s,
                        last_updated = NOW()
                    WHERE mem_sno = %s AND is_active = 1
                """, (encoding_json, glasses_detected_db, quality_score_db, mem_sno))
                logger.info(f"🔄 얼굴 데이터 업데이트: {mem_sno}")
            else:
                # 새로 삽입
                cursor.execute("""
                    INSERT INTO member_faces 
                    (mem_sno, face_encoding, glasses_detected, quality_score, security_level, liveness_score, notes)
                    VALUES (%s, %s, %s, %s, %s, %s, %s)
                """, (mem_sno, encoding_json, glasses_detected_db, quality_score_db, 3, 0.75, f"Enhanced Face Service v2.0 등록"))
                logger.info(f"✅ 새 얼굴 데이터 저장: {mem_sno}")
            
            conn.commit()
            cursor.close()
            conn.close()
            
            return True
            
        except Exception as e:
            logger.error(f"❌ 얼굴 데이터 저장 실패: {e}")
            return False

    def extract_face_embedding(self, image: np.ndarray, landmarks: List) -> Optional[np.ndarray]:
        """MediaPipe 랜드마크로 128차원 얼굴 임베딩 생성"""
        try:
            h, w = image.shape[:2]
            face_features = []
            
            # 1. 눈 영역 특징
            left_eye_points = [(landmarks[i].x * w, landmarks[i].y * h) for i in self.LEFT_EYE_LANDMARKS]
            right_eye_points = [(landmarks[i].x * w, landmarks[i].y * h) for i in self.RIGHT_EYE_LANDMARKS]
            
            left_eye_center = np.mean(left_eye_points, axis=0)
            right_eye_center = np.mean(right_eye_points, axis=0)
            eye_distance = np.linalg.norm(left_eye_center - right_eye_center)
            
            face_features.extend([
                left_eye_center[0], left_eye_center[1],
                right_eye_center[0], right_eye_center[1],
                eye_distance
            ])
            
            # 2. 코 영역
            nose_tip = landmarks[1]
            nose_bridge = landmarks[6]
            face_features.extend([nose_tip.x, nose_tip.y, nose_bridge.x, nose_bridge.y])
            
            # 3. 입 영역
            mouth_left = landmarks[61]
            mouth_right = landmarks[291]
            face_features.extend([mouth_left.x, mouth_left.y, mouth_right.x, mouth_right.y])
            
            # 4. 주요 얼굴 윤곽선 포인트들
            outline_indices = [10, 151, 9, 8, 168, 6, 148, 176, 149, 150]
            for idx in outline_indices:
                if idx < len(landmarks):
                    face_features.extend([landmarks[idx].x, landmarks[idx].y])
            
            # 5. 추가 특징점들로 128차원 채우기
            additional_indices = list(range(0, min(468, len(landmarks)), 5))[:50]
            for idx in additional_indices:
                face_features.extend([landmarks[idx].x, landmarks[idx].y])
            
            # 128차원으로 맞추기
            embedding = np.array(face_features[:128])
            if len(embedding) < 128:
                padding = np.zeros(128 - len(embedding))
                embedding = np.concatenate([embedding, padding])
            
            # 정규화
            norm = np.linalg.norm(embedding)
            if norm > 0:
                embedding = embedding / norm
            
            return embedding
            
        except Exception as e:
            logger.warning(f"얼굴 임베딩 생성 실패: {e}")
            return None

    def find_face_match(self, query_embedding: np.ndarray, has_glasses: bool) -> Dict:
        """얼굴 임베딩 매칭"""
        try:
            if query_embedding is None:
                return {'success': False, 'error': '유효하지 않은 임베딩'}
            
            best_match = None
            best_similarity = 0.0
            
            # 안경 상태에 따라 검색 순서 결정
            search_categories = ['glasses', 'normal'] if has_glasses else ['normal', 'glasses']
            
            for category in search_categories:
                if len(self.face_database[category]['embeddings']) == 0:
                    continue
                
                # 코사인 유사도 계산
                similarities = cosine_similarity(
                    query_embedding.reshape(1, -1),
                    self.face_database[category]['embeddings']
                )[0]
                
                max_idx = np.argmax(similarities)
                max_similarity = similarities[max_idx]
                
                if max_similarity > best_similarity:
                    best_similarity = max_similarity
                    best_match = {
                        'mem_sno': self.face_database[category]['mem_snos'][max_idx],
                        'similarity': max_similarity,
                        'category': category
                    }
            
            # 임계값 확인
            if best_match and best_similarity >= self.security_thresholds['face_similarity_threshold']:
                return {
                    'success': True,
                    'match_found': True,
                    'member': best_match,
                    'similarity_score': best_similarity
                }
            else:
                return {
                    'success': True,
                    'match_found': False,
                    'best_similarity': best_similarity,
                    'threshold': self.security_thresholds['face_similarity_threshold']
                }
                
        except Exception as e:
            logger.error(f"얼굴 매칭 오류: {e}")
            return {'success': False, 'error': str(e)}

    def detect_glasses(self, image: np.ndarray, landmarks: List) -> Dict:
        """개선된 안경 착용 검출"""
        try:
            h, w = image.shape[:2]
            
            # 1. 기존 눈 영역 분석
            left_score = self._analyze_eye_region(image, landmarks, self.LEFT_EYE_LANDMARKS, w, h)
            right_score = self._analyze_eye_region(image, landmarks, self.RIGHT_EYE_LANDMARKS, w, h)
            
            # 2. 코다리 영역 분석 (안경 프레임이 놓이는 부분)
            nose_bridge_score = self._analyze_nose_bridge_region(image, landmarks, w, h)
            
            # 3. 안경 프레임 연결성 분석
            frame_connectivity_score = self._analyze_frame_connectivity(image, landmarks, w, h)
            
            # 종합 점수 계산 (가중평균)
            avg_eye_score = (left_score + right_score) / 2.0
            combined_score = (
                avg_eye_score * 0.4 +           # 눈 영역 40%
                nose_bridge_score * 0.3 +      # 코다리 30%
                frame_connectivity_score * 0.3  # 프레임 연결성 30%
            )
            
            has_glasses = combined_score > self.security_thresholds['glasses_confidence_threshold']
            
            # 디버깅 로그 추가
            logger.info(f"🔍 안경 검출 세부점수 - 왼쪽눈:{left_score:.3f}, 오른쪽눈:{right_score:.3f}, 코다리:{nose_bridge_score:.3f}, 연결성:{frame_connectivity_score:.3f}, 종합:{combined_score:.3f}")
            logger.info(f"👓 안경 검출 임계값: {self.security_thresholds['glasses_confidence_threshold']}")
            logger.info(f"🎯 안경 검출 최종 결과: {has_glasses} (점수: {combined_score:.3f} {'>' if has_glasses else '<='} {self.security_thresholds['glasses_confidence_threshold']})")
            
            return {
                'has_glasses': has_glasses,
                'confidence': combined_score,
                'left_score': left_score,
                'right_score': right_score,
                'nose_bridge_score': nose_bridge_score,
                'frame_connectivity_score': frame_connectivity_score,
                'threshold_used': self.security_thresholds['glasses_confidence_threshold']
            }
            
        except Exception as e:
            logger.warning(f"안경 검출 오류: {e}")
            return {'has_glasses': False, 'confidence': 0.0}

    def _analyze_eye_region(self, image: np.ndarray, landmarks: List, eye_indices: List, w: int, h: int) -> float:
        """개선된 눈 영역 안경 프레임 분석"""
        try:
            # 눈 영역 바운딩 박스 (더 넓게 확장)
            x_coords = [landmarks[i].x * w for i in eye_indices]
            y_coords = [landmarks[i].y * h for i in eye_indices]
            
            x_min, x_max = int(min(x_coords)) - 25, int(max(x_coords)) + 25
            y_min, y_max = int(min(y_coords)) - 20, int(max(y_coords)) + 20
            
            x_min, y_min = max(0, x_min), max(0, y_min)
            x_max, y_max = min(w, x_max), min(h, y_max)
            
            roi = image[y_min:y_max, x_min:x_max]
            if roi.size == 0:
                return 0.0
            
            # 그레이스케일 변환
            gray_roi = cv2.cvtColor(roi, cv2.COLOR_BGR2GRAY) if len(roi.shape) == 3 else roi
            
            # 1. 다중 엣지 검출 (더 민감하게)
            edges_low = cv2.Canny(gray_roi, 20, 60)   # 낮은 임계값
            edges_mid = cv2.Canny(gray_roi, 50, 120)  # 중간 임계값
            edges_high = cv2.Canny(gray_roi, 80, 160) # 높은 임계값
            
            # 엣지 밀도 계산
            edge_low_density = np.sum(edges_low > 0) / edges_low.size
            edge_mid_density = np.sum(edges_mid > 0) / edges_mid.size
            edge_high_density = np.sum(edges_high > 0) / edges_high.size
            
            # 가중평균으로 엣지 점수 계산
            edge_score = (edge_low_density * 0.5 + edge_mid_density * 0.3 + edge_high_density * 0.2)
            
            # 2. 안경 프레임 형태 검출 (호리존탈 라인)
            horizontal_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (7, 1))
            horizontal_lines = cv2.morphologyEx(gray_roi, cv2.MORPH_OPEN, horizontal_kernel)
            horizontal_score = np.sum(horizontal_lines > 100) / roi.size
            
            # 3. 렌즈 반사 검출 (여러 임계값)
            bright_200 = np.sum(gray_roi > 200) / gray_roi.size  # 매우 밝은
            bright_180 = np.sum(gray_roi > 180) / gray_roi.size  # 밝은
            bright_160 = np.sum(gray_roi > 160) / gray_roi.size  # 약간 밝은
            
            reflection_score = bright_200 * 0.5 + bright_180 * 0.3 + bright_160 * 0.2
            
            # 4. 색상 균일성 검사 (안경 프레임의 특징)
            blur = cv2.GaussianBlur(gray_roi, (5, 5), 0)
            laplacian_var = cv2.Laplacian(blur, cv2.CV_64F).var()
            texture_score = min(1.0, laplacian_var / 500.0)  # 텍스처 변화량
            
            # 5. 원형/타원형 검출 (안경 렌즈 형태)
            contours, _ = cv2.findContours(edges_mid, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
            circular_score = 0.0
            for contour in contours:
                if len(contour) > 10:
                    # 타원 피팅
                    try:
                        ellipse = cv2.fitEllipse(contour)
                        # 적절한 크기의 타원인지 확인
                        width, height = ellipse[1]
                        if 15 < width < 80 and 10 < height < 60:
                            circular_score += 0.2
                    except:
                        pass
            
            circular_score = min(1.0, circular_score)
            
            # 종합 점수 계산 (가중치 조정)
            glasses_score = (
                edge_score * 0.25 +          # 엣지 25%
                horizontal_score * 0.2 +     # 수평선 20%
                reflection_score * 0.2 +     # 반사 20%
                texture_score * 0.2 +        # 텍스처 20%
                circular_score * 0.15        # 원형 검출 15%
            )
            
            # 점수 증폭 (더 민감하게)
            final_score = min(1.0, glasses_score * 2.5)
            
            return final_score
            
        except Exception as e:
            logger.warning(f"눈 영역 분석 실패: {e}")
            return 0.0

    def _analyze_nose_bridge_region(self, image: np.ndarray, landmarks: List, w: int, h: int) -> float:
        """코다리 영역에서 안경 프레임 검출"""
        try:
            # 코다리 영역 좌표
            bridge_coords = [(landmarks[i].x * w, landmarks[i].y * h) for i in self.NOSE_BRIDGE_LANDMARKS]
            
            x_coords = [coord[0] for coord in bridge_coords]
            y_coords = [coord[1] for coord in bridge_coords]
            
            x_min, x_max = int(min(x_coords)) - 10, int(max(x_coords)) + 10
            y_min, y_max = int(min(y_coords)) - 15, int(max(y_coords)) + 5
            
            x_min, y_min = max(0, x_min), max(0, y_min)
            x_max, y_max = min(w, x_max), min(h, y_max)
            
            roi = image[y_min:y_max, x_min:x_max]
            if roi.size == 0:
                return 0.0
            
            gray_roi = cv2.cvtColor(roi, cv2.COLOR_BGR2GRAY) if len(roi.shape) == 3 else roi
            
            # 수평선 검출 (안경 프레임의 특징)
            horizontal_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (5, 1))
            horizontal_lines = cv2.morphologyEx(gray_roi, cv2.MORPH_OPEN, horizontal_kernel)
            
            # 엣지 검출
            edges = cv2.Canny(gray_roi, 20, 60)
            
            # 점수 계산
            horizontal_score = np.sum(horizontal_lines > 50) / roi.size
            edge_score = np.sum(edges > 0) / edges.size
            
            return min(1.0, (horizontal_score * 0.6 + edge_score * 0.4) * 2.0)
            
        except Exception:
            return 0.0

    def _analyze_frame_connectivity(self, image: np.ndarray, landmarks: List, w: int, h: int) -> float:
        """안경 프레임의 연결성 분석"""
        try:
            # 양쪽 눈과 코다리를 연결하는 영역
            left_eye_center = np.mean([(landmarks[i].x * w, landmarks[i].y * h) for i in self.LEFT_EYE_LANDMARKS[:6]], axis=0)
            right_eye_center = np.mean([(landmarks[i].x * w, landmarks[i].y * h) for i in self.RIGHT_EYE_LANDMARKS[:6]], axis=0)
            nose_center = np.mean([(landmarks[i].x * w, landmarks[i].y * h) for i in self.NOSE_BRIDGE_LANDMARKS], axis=0)
            
            # 프레임 영역 정의
            y_top = int(min(left_eye_center[1], right_eye_center[1]) - 20)
            y_bottom = int(max(left_eye_center[1], right_eye_center[1]) + 10)
            x_left = int(left_eye_center[0] - 20)
            x_right = int(right_eye_center[0] + 20)
            
            x_left, y_top = max(0, x_left), max(0, y_top)
            x_right, y_bottom = min(w, x_right), min(h, y_bottom)
            
            frame_roi = image[y_top:y_bottom, x_left:x_right]
            if frame_roi.size == 0:
                return 0.0
            
            gray_frame = cv2.cvtColor(frame_roi, cv2.COLOR_BGR2GRAY) if len(frame_roi.shape) == 3 else frame_roi
            
            # 연결된 컴포넌트 분석
            edges = cv2.Canny(gray_frame, 30, 90)
            contours, _ = cv2.findContours(edges, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
            
            # 긴 수평선 찾기 (안경 프레임의 특징)
            horizontal_lines = 0
            for contour in contours:
                if len(contour) > 20:  # 충분히 긴 윤곽선
                    rect = cv2.boundingRect(contour)
                    if rect[2] > rect[3] * 2:  # 폭이 높이의 2배 이상 (수평선)
                        horizontal_lines += 1
            
            connectivity_score = min(1.0, horizontal_lines / 3.0)
            return connectivity_score
            
        except Exception:
            return 0.0

    def detect_liveness(self, image: np.ndarray, landmarks: List) -> Dict:
        """Liveness Detection - 실제 얼굴 vs 사진/화면 구분"""
        try:
            h, w = image.shape[:2]
            liveness_score = 0.0
            checks = {}
            
            # 1. 텍스처 분석 (실제 피부 vs 인쇄물)
            texture_score = self._analyze_skin_texture(image, landmarks, w, h)
            checks['texture_analysis'] = texture_score
            
            # 2. 깊이 정보 분석 (2D vs 3D)
            depth_score = self._analyze_face_depth(landmarks)
            checks['depth_analysis'] = depth_score
            
            # 3. 반사 패턴 분석 (화면 반사 검출)
            reflection_score = self._analyze_reflection_patterns(image, landmarks, w, h)
            checks['reflection_analysis'] = reflection_score
            
            # 4. 색상 분포 분석
            color_score = self._analyze_color_distribution(image, landmarks, w, h)
            checks['color_analysis'] = color_score
            
            # 종합 점수 계산
            liveness_score = (
                texture_score * 0.3 +
                depth_score * 0.25 +
                reflection_score * 0.25 +
                color_score * 0.2
            )
            
            is_live = liveness_score > self.security_thresholds['liveness_threshold']
            
            logger.info(f"🔍 Liveness 검출 - 텍스처:{texture_score:.3f}, 깊이:{depth_score:.3f}, 반사:{reflection_score:.3f}, 색상:{color_score:.3f}, 종합:{liveness_score:.3f}")
            
            return {
                'is_live': is_live,
                'confidence': liveness_score,
                'checks': checks
            }
            
        except Exception as e:
            logger.warning(f"Liveness 검출 오류: {e}")
            return {'is_live': True, 'confidence': 0.5}  # 오류시 기본값으로 통과

    def _analyze_skin_texture(self, image: np.ndarray, landmarks: List, w: int, h: int) -> float:
        """피부 텍스처 분석 (실제 vs 인쇄물)"""
        try:
            # 뺨 영역 추출 (텍스처가 잘 드러나는 부분)
            left_cheek = [(landmarks[i].x * w, landmarks[i].y * h) for i in [116, 117, 118, 119, 120, 121]]
            right_cheek = [(landmarks[i].x * w, landmarks[i].y * h) for i in [345, 346, 347, 348, 349, 350]]
            
            all_cheek_points = left_cheek + right_cheek
            x_coords = [p[0] for p in all_cheek_points]
            y_coords = [p[1] for p in all_cheek_points]
            
            x_min, x_max = int(min(x_coords)), int(max(x_coords))
            y_min, y_max = int(min(y_coords)), int(max(y_coords))
            
            x_min, y_min = max(0, x_min), max(0, y_min)
            x_max, y_max = min(w, x_max), min(h, y_max)
            
            cheek_roi = image[y_min:y_max, x_min:x_max]
            if cheek_roi.size == 0:
                return 0.5
            
            gray_cheek = cv2.cvtColor(cheek_roi, cv2.COLOR_BGR2GRAY) if len(cheek_roi.shape) == 3 else cheek_roi
            
            # 텍스처 분산 계산
            texture_variance = np.var(gray_cheek)
            
            # 정규화된 점수 (높은 분산 = 실제 피부)
            texture_score = min(1.0, texture_variance / 1000.0)
            
            return texture_score
            
        except Exception:
            return 0.5

    def _analyze_face_depth(self, landmarks: List) -> float:
        """얼굴 깊이 정보 분석"""
        try:
            # 3D 랜드마크의 z 좌표 분석 (MediaPipe는 상대적 깊이 정보 제공)
            z_coords = [landmark.z for landmark in landmarks[:468]]  # 얼굴 랜드마크만
            
            z_variance = np.var(z_coords)
            z_range = max(z_coords) - min(z_coords)
            
            # 2D 이미지는 깊이 변화가 거의 없음
            depth_score = min(1.0, (z_variance * 1000 + z_range * 10))
            
            return depth_score
            
        except Exception:
            return 0.5

    def _analyze_reflection_patterns(self, image: np.ndarray, landmarks: List, w: int, h: int) -> float:
        """화면 반사 패턴 분석"""
        try:
            # 이마 영역에서 화면 반사 검출
            forehead_coords = [(landmarks[i].x * w, landmarks[i].y * h) for i in self.FOREHEAD_LANDMARKS]
            
            x_coords = [coord[0] for coord in forehead_coords]
            y_coords = [coord[1] for coord in forehead_coords]
            
            x_min, x_max = int(min(x_coords)) - 20, int(max(x_coords)) + 20
            y_min, y_max = int(min(y_coords)) - 30, int(max(y_coords))
            
            x_min, y_min = max(0, x_min), max(0, y_min)
            x_max, y_max = min(w, x_max), min(h, y_max)
            
            forehead_roi = image[y_min:y_max, x_min:x_max]
            if forehead_roi.size == 0:
                return 1.0
            
            gray_forehead = cv2.cvtColor(forehead_roi, cv2.COLOR_BGR2GRAY) if len(forehead_roi.shape) == 3 else forehead_roi
            
            # 과도한 반사(화면 글레어) 검출
            bright_pixels = np.sum(gray_forehead > 220)
            total_pixels = gray_forehead.size
            
            bright_ratio = bright_pixels / total_pixels
            
            # 반사가 적을수록 실제 얼굴 (높은 점수)
            reflection_score = max(0.0, 1.0 - bright_ratio * 3)
            
            return reflection_score
            
        except Exception:
            return 1.0

    def _analyze_color_distribution(self, image: np.ndarray, landmarks: List, w: int, h: int) -> float:
        """색상 분포 분석"""
        try:
            # 얼굴 영역 추출
            face_points = [(landmarks[i].x * w, landmarks[i].y * h) for i in range(0, 17)]  # 얼굴 윤곽
            
            x_coords = [p[0] for p in face_points]
            y_coords = [p[1] for p in face_points]
            
            x_min, x_max = int(min(x_coords)), int(max(x_coords))
            y_min, y_max = int(min(y_coords)), int(max(y_coords))
            
            face_roi = image[y_min:y_max, x_min:x_max]
            if face_roi.size == 0:
                return 0.5
            
            # HSV 색상 공간에서 분석
            hsv_face = cv2.cvtColor(face_roi, cv2.COLOR_BGR2HSV)
            
            # 피부톤 범위에서의 분포 분석
            hue_variance = np.var(hsv_face[:, :, 0])
            saturation_mean = np.mean(hsv_face[:, :, 1])
            
            # 자연스러운 색상 분포일수록 높은 점수
            color_score = min(1.0, (hue_variance / 100.0 + saturation_mean / 255.0) / 2)
            
            return color_score
            
        except Exception:
            return 0.5

    def process_frame_for_recognition(self, frame: np.ndarray) -> Dict:
        """얼굴 인식 처리"""
        start_time = time.time()
        
        try:
            # 이미지 크기 최적화
            h, w = frame.shape[:2]
            if w > 640:
                scale = 640 / w
                frame = cv2.resize(frame, (int(w * scale), int(h * scale)))
            
            # RGB 변환
            rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            
            # 얼굴 검출
            detection_results = self.face_detection.process(rgb_frame)
            mesh_results = self.face_mesh.process(rgb_frame)
            
            if not detection_results.detections or not mesh_results.multi_face_landmarks:
                return {
                    'success': False,
                    'error': '얼굴이 검출되지 않았습니다',
                    'processing_time': time.time() - start_time
                }
            
            # 첫 번째 얼굴 처리
            detection = detection_results.detections[0]
            landmarks = mesh_results.multi_face_landmarks[0].landmark
            
            # 기본 보안 체크
            basic_check = self._basic_security_check(detection)
            if not basic_check['passed']:
                return {
                    'success': False,
                    'error': f"보안 체크 실패: {basic_check['reason']}",
                    'processing_time': time.time() - start_time
                }
            
            # 안경 검출
            glasses_result = self.detect_glasses(frame, landmarks)
            
            # Liveness 검사 추가
            liveness_result = self.detect_liveness(frame, landmarks)
            
            # 얼굴 임베딩 생성
            face_embedding = self.extract_face_embedding(frame, landmarks)
            if face_embedding is None:
                return {
                    'success': False,
                    'error': '얼굴 임베딩 생성 실패',
                    'processing_time': time.time() - start_time
                }
            
            # 얼굴 매칭
            match_result = self.find_face_match(face_embedding, glasses_result['has_glasses'])
            
            # 품질 점수 계산 (liveness confidence와 face embedding quality 결합)
            quality_score = min(0.95, max(0.5, 
                basic_check.get('confidence', 0.8) * 0.4 + 
                glasses_result['confidence'] * 0.3 + 
                liveness_result['confidence'] * 0.3
            ))
            
            # 보안 경고 수집
            security_warnings = []
            if not liveness_result['is_live']:
                security_warnings.append("Liveness check failed")
            if glasses_result['confidence'] < 0.3:
                security_warnings.append("Low glasses detection confidence")
            if match_result.get('similarity_score', 0) < 0.8:
                security_warnings.append("Low face similarity")
            
            # 상세한 보안 정보 반환
            return {
                'success': True,
                'face_detected': True,
                'glasses_detection': glasses_result,
                'liveness_check': liveness_result,
                'face_matching': match_result,
                'basic_security': basic_check,
                'processing_time': time.time() - start_time,
                'confidence_score': match_result.get('similarity_score', 0.0),
                'security_details': {
                    'liveness_passed': liveness_result['is_live'],
                    'liveness_confidence': liveness_result['confidence'],
                    'quality_score': quality_score,
                    'glasses_confidence': glasses_result['confidence'],
                    'security_warnings': security_warnings,
                    'liveness_checks': liveness_result.get('checks', {}),
                    'glasses_details': {
                        'left_score': glasses_result.get('left_score', 0),
                        'right_score': glasses_result.get('right_score', 0),
                        'nose_bridge_score': glasses_result.get('nose_bridge_score', 0),
                        'frame_connectivity_score': glasses_result.get('frame_connectivity_score', 0)
                    }
                }
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': f'처리 오류: {str(e)}',
                'processing_time': time.time() - start_time
            }

    def _basic_security_check(self, detection) -> Dict:
        """기본 보안 체크"""
        try:
            bbox = detection.location_data.relative_bounding_box
            face_area = bbox.width * bbox.height
            
            if face_area < self.security_thresholds['face_size_min']:
                return {'passed': False, 'reason': '얼굴이 너무 작습니다'}
            
            if face_area > self.security_thresholds['face_size_max']:
                return {'passed': False, 'reason': '얼굴이 너무 큽니다'}
            
            # 얼굴 위치 체크
            face_center_x = bbox.xmin + bbox.width / 2
            face_center_y = bbox.ymin + bbox.height / 2
            
            if face_center_x < 0.1 or face_center_x > 0.9:
                return {'passed': False, 'reason': '얼굴을 화면 중앙에 맞춰주세요'}
            
            if detection.score[0] < 0.7:
                return {'passed': False, 'reason': '얼굴 검출 신뢰도가 낮습니다'}
            
            return {'passed': True, 'face_area': face_area, 'confidence': detection.score[0]}
            
        except Exception as e:
            return {'passed': False, 'reason': '보안 체크 처리 오류'}

    def add_face_to_database(self, embedding: np.ndarray, mem_sno: str, has_glasses: bool):
        """얼굴을 메모리 데이터베이스에 추가"""
        category = 'glasses' if has_glasses else 'normal'
        
        if len(self.face_database[category]['embeddings']) == 0:
            self.face_database[category]['embeddings'] = embedding.reshape(1, -1)
        else:
            self.face_database[category]['embeddings'] = np.vstack([
                self.face_database[category]['embeddings'], 
                embedding.reshape(1, -1)
            ])
        
        self.face_database[category]['mem_snos'].append(mem_sno)
        logger.info(f"🔒 얼굴 추가: {mem_sno} ({'안경' if has_glasses else '일반'})")

    def save_face_recognition_log(self, log_data: Dict) -> bool:
        """얼굴 인식 로그를 데이터베이스에 저장"""
        try:
            logger.info(f"🔍 로그 저장 시작: {log_data.get('mem_sno', 'UNKNOWN')}")
            
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()
            
            # IP 주소와 User Agent는 클라이언트에서 받거나 헬퍼 함수 사용
            ip_address = log_data.get('ip_address') or get_real_client_ip()
            user_agent = log_data.get('user_agent', 'Python Face Recognition Service')
            session_id = log_data.get('session_id') or generate_session_id()
            
            # 데이터 준비 (PHP 모델과 일치하도록 수정)
            values = (
                log_data.get('mem_sno'),
                log_data.get('confidence_score', 0),
                log_data.get('similarity_score', 0),
                log_data.get('quality_score', 0),
                int(log_data.get('processing_time_ms', 0)),
                bool(log_data.get('glasses_detected', False)),
                log_data.get('match_category', 'recognition'),
                json.dumps(safe_convert(log_data.get('security_checks_passed', {}))),
                bool(log_data.get('success', False)),
                log_data.get('error_message'),
                ip_address,
                user_agent,
                session_id
            )
            
            logger.info(f"🔍 삽입 데이터: mem_sno={values[0]}, confidence={values[1]}, similarity={values[2]}, quality={values[3]}, success={values[8]}")
            
            # PHP 모델과 일치하도록 수정
            cursor.execute("""
                INSERT INTO face_recognition_logs 
                (mem_sno, confidence_score, similarity_score, quality_score, 
                 processing_time_ms, glasses_detected, match_category, 
                 security_checks_passed, success, error_message, 
                 ip_address, user_agent, session_id)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, values)
            
            conn.commit()
            log_id = cursor.lastrowid
            cursor.close()
            conn.close()
            
            logger.info(f"✅ 얼굴 인식 로그 저장 성공: {log_data.get('mem_sno', 'UNKNOWN')} (ID: {log_id})")
            return True
            
        except Exception as e:
            logger.error(f"❌ 얼굴 인식 로그 저장 실패: {e}")
            # 더 상세한 오류 정보 출력
            import traceback
            logger.error(f"상세 오류: {traceback.format_exc()}")
            return False

# 전역 서비스 인스턴스
face_service = EnhancedFaceRecognitionService()

# Flask 라우트
@app.route('/api/face/health', methods=['GET'])
def health_check():
    """헬스체크"""
    try:
        return safe_jsonify({
            "status": "healthy",
            "service": "Enhanced Face Recognition v2.0",
            "timestamp": time.time(),
            "database_status": {
                "normal_faces": len(face_service.face_database['normal']['mem_snos']),
                "glasses_faces": len(face_service.face_database['glasses']['mem_snos'])
            }
        })
    except Exception as e:
        return safe_jsonify({
            "status": "unhealthy",
            "error": str(e)
        })

@app.route('/api/face/recognize', methods=['POST'])
def recognize_face():
    """얼굴 인식 API"""
    start_time = time.time()
    
    try:
        # 이미지 데이터 받기 (JSON과 파일 업로드 모두 지원)
        image_data = None
        frame = None
        
        if request.is_json:
            # JSON 요청 (Base64)
            data = request.get_json()
            if 'image' in data:
                image_data = data['image']
            elif 'image_base64' in data:
                image_data = data['image_base64']
                
                # Base64 디코딩
                try:
                    # data:image/jpeg;base64, 접두사 제거
                    if ',' in image_data:
                        image_data = image_data.split(',')[1]
                    
                    # Base64 패딩 문제 해결
                    missing_padding = len(image_data) % 4
                    if missing_padding:
                        image_data += '=' * (4 - missing_padding)
                    
                    # 디코딩
                    image_bytes = base64.b64decode(image_data)
                    pil_image = Image.open(io.BytesIO(image_bytes)).convert('RGB')
                    image_array = np.array(pil_image)
                    frame = cv2.cvtColor(image_array, cv2.COLOR_RGB2BGR)
                    
                except Exception as e:
                    logger.error(f"Base64 디코딩 실패: {e}")
                    return safe_jsonify({
                        'success': False,
                        'error': f'이미지 디코딩 실패: {str(e)}'
                    })
        else:
            # 파일 업로드 요청
            if 'image' not in request.files:
                return safe_jsonify({'success': False, 'error': '이미지가 제공되지 않았습니다'})
            
            image_file = request.files['image']
            image_data = image_file.read()
            
            # PIL로 디코딩
            pil_image = Image.open(BytesIO(image_data))
            frame = cv2.cvtColor(np.array(pil_image), cv2.COLOR_RGB2BGR)
        
        if frame is None:
            return safe_jsonify({'success': False, 'error': '이미지 처리 실패'})
        
        # 얼굴 인식 처리
        result = face_service.process_frame_for_recognition(frame)
        
        # 처리 시간 계산
        processing_time_ms = (time.time() - start_time) * 1000
        
        # 로그 데이터 준비
        log_data = {
            'processing_time_ms': processing_time_ms,
            'success': result.get('success', False),
            'error_message': result.get('error') if not result.get('success', False) else None,
            'glasses_detected': result.get('glasses_detection', {}).get('has_glasses', False),
            'match_category': 'recognition',
            'security_checks_passed': result.get('security_details', {}),
            'ip_address': get_real_client_ip(),
            'user_agent': request.headers.get('User-Agent', 'Unknown'),
            'session_id': generate_session_id()
        }
        
        # 인식 성공 시 추가 정보
        if result.get('success') and result.get('face_matching', {}).get('match_found'):
            member_info = result['face_matching']['member']
            log_data.update({
                'mem_sno': member_info.get('mem_sno'),
                'confidence_score': result.get('confidence_score', 0),
                'similarity_score': result['face_matching'].get('similarity_score', 0),
                'quality_score': result.get('security_details', {}).get('quality_score', 0)
            })
        else:
            # 인식 실패 시에도 기본 정보 저장
            log_data.update({
                'mem_sno': None,
                'confidence_score': 0,
                'similarity_score': 0,
                'quality_score': result.get('security_details', {}).get('quality_score', 0)
            })
        
        # 로그 저장 (별도 스레드에서 처리하여 응답 속도에 영향 없음)
        try:
            face_service.save_face_recognition_log(log_data)
        except Exception as log_error:
            logger.error(f"로그 저장 오류: {log_error}")
            # 로그 저장 실패는 메인 응답에 영향 주지 않음
        
        return safe_jsonify(result)
        
    except Exception as e:
        # 오류 발생 시에도 로그 저장
        processing_time_ms = (time.time() - start_time) * 1000
        error_log_data = {
            'processing_time_ms': processing_time_ms,
            'success': False,
            'error_message': str(e),
            'match_category': 'error',
            'ip_address': get_real_client_ip(),
            'user_agent': request.headers.get('User-Agent', 'Unknown'),
            'session_id': generate_session_id()
        }
        
        try:
            face_service.save_face_recognition_log(error_log_data)
        except:
            pass  # 로그 저장 실패는 무시
        
        logger.error(f"얼굴 인식 API 오류: {e}")
        return safe_jsonify({
            'success': False,
            'error': f'서버 오류: {str(e)}'
        })

@app.route('/api/face/register', methods=['POST'])
def register_face():
    """얼굴 등록 API"""
    try:
        if 'image' not in request.files or 'mem_sno' not in request.form:
            return safe_jsonify({'success': False, 'error': '이미지와 회원번호가 필요합니다'})
        
        mem_sno = request.form['mem_sno']
        image_file = request.files['image']
        image_data = image_file.read()
        
        # 이미지 처리
        pil_image = Image.open(BytesIO(image_data))
        frame = cv2.cvtColor(np.array(pil_image), cv2.COLOR_RGB2BGR)
        
        # 얼굴 검출 및 임베딩 생성
        h, w = frame.shape[:2]
        if w > 640:
            scale = 640 / w
            frame = cv2.resize(frame, (int(w * scale), int(h * scale)))
        
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        
        detection_results = face_service.face_detection.process(rgb_frame)
        mesh_results = face_service.face_mesh.process(rgb_frame)
        
        if not detection_results.detections or not mesh_results.multi_face_landmarks:
            return safe_jsonify({'success': False, 'error': '얼굴이 검출되지 않았습니다'})
        
        landmarks = mesh_results.multi_face_landmarks[0].landmark
        detection = detection_results.detections[0]
        
        # 안경 검출
        glasses_result = face_service.detect_glasses(frame, landmarks)
        
        # Liveness 검사 추가
        liveness_result = face_service.detect_liveness(frame, landmarks)
        
        # 기본 보안 체크
        basic_check = face_service._basic_security_check(detection)
        
        # 품질 점수 동적 계산
        quality_score = min(0.95, max(0.5, 
            basic_check.get('confidence', 0.8) * 0.4 + 
            glasses_result['confidence'] * 0.3 + 
            liveness_result['confidence'] * 0.3
        ))
        
        # 얼굴 임베딩 생성
        face_embedding = face_service.extract_face_embedding(frame, landmarks)
        if face_embedding is None:
            return safe_jsonify({'success': False, 'error': '얼굴 임베딩 생성 실패'})
        
        # MySQL 데이터베이스에 저장 (계산된 품질 점수 사용)
        db_save_success = face_service.save_face_to_database(
            mem_sno, 
            face_embedding, 
            glasses_result['has_glasses'],
            quality_score  # 동적으로 계산된 값 사용
        )
        
        if db_save_success:
            # 메모리 데이터베이스에도 추가
            face_service.add_face_to_database(face_embedding, mem_sno, glasses_result['has_glasses'])
            
            return safe_jsonify({
                'success': True,
                'message': f'회원 {mem_sno}의 얼굴이 데이터베이스에 등록되었습니다',
                'glasses_detected': glasses_result['has_glasses'],
                'embedding_size': len(face_embedding),
                'saved_to_database': True,
                'database_status': {
                    "normal_faces": len(face_service.face_database['normal']['mem_snos']),
                    "glasses_faces": len(face_service.face_database['glasses']['mem_snos'])
                }
            })
        else:
            return safe_jsonify({
                'success': False,
                'error': '데이터베이스 저장에 실패했습니다',
                'glasses_detected': glasses_result['has_glasses'],
                'saved_to_database': False
            })
        
    except Exception as e:
        logger.error(f"얼굴 등록 API 오류: {e}")
        return safe_jsonify({
            'success': False,
            'error': f'서버 오류: {str(e)}'
        })

@app.route('/api/face/detect_for_registration', methods=['POST'])
def detect_for_registration():
    """회원 등록용 얼굴 검출 API (저장하지 않음, 검출만)"""
    start_time = time.time()
    
    try:
        logger.info("🔍 얼굴 검출 API 호출됨")
        
        # 이미지 데이터 받기
        image_data = None
        
        if request.is_json:
            # JSON 요청
            data = request.get_json()
            if 'image' in data:
                image_data = data['image']
        else:
            # FormData 요청
            if 'image' in request.form:
                image_data = request.form['image']
            elif 'image' in request.files:
                file = request.files['image']
                image_data = base64.b64encode(file.read()).decode('utf-8')
        
        if not image_data:
            return safe_jsonify({
                'success': False,
                'error': '이미지 데이터가 제공되지 않았습니다'
            })
        
        # Base64 디코딩
        try:
            # data:image/jpeg;base64, 접두사 제거
            if ',' in image_data:
                image_data = image_data.split(',')[1]
            
            # Base64 패딩 문제 해결
            missing_padding = len(image_data) % 4
            if missing_padding:
                image_data += '=' * (4 - missing_padding)
            
            logger.info(f"Base64 이미지 데이터 길이: {len(image_data)}")
            
            # 디코딩
            image_bytes = base64.b64decode(image_data)
            logger.info(f"디코딩된 이미지 데이터 크기: {len(image_bytes)} bytes")
            
        except Exception as e:
            logger.error(f"Base64 디코딩 실패: {e}")
            return safe_jsonify({
                'success': False,
                'error': f'이미지 디코딩 실패: {str(e)}'
            })
        
        # PIL로 이미지 로드
        try:
            pil_image = Image.open(io.BytesIO(image_bytes)).convert('RGB')
            logger.info(f"PIL 이미지 크기: {pil_image.size}, 모드: {pil_image.mode}")
        except Exception as e:
            logger.error(f"PIL 이미지 로드 실패: {e}")
            return safe_jsonify({
                'success': False,
                'error': f'이미지 형식 오류: {str(e)}'
            })
        
        # NumPy 배열로 변환
        try:
            image_array = np.array(pil_image)
            # RGB → BGR 변환 (OpenCV 호환)
            frame = cv2.cvtColor(image_array, cv2.COLOR_RGB2BGR)
            logger.info(f"OpenCV 프레임 크기: {frame.shape}")
        except Exception as e:
            logger.error(f"이미지 변환 실패: {e}")
            return safe_jsonify({
                'success': False,
                'error': f'이미지 변환 실패: {str(e)}'
            })
        
        # 얼굴 검출
        try:
            rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            mesh_results = face_service.face_mesh.process(rgb_frame)
            detection_results = face_service.face_detection.process(rgb_frame)
            
            if not mesh_results.multi_face_landmarks or not detection_results.detections:
                logger.warning("얼굴이 검출되지 않음")
                return safe_jsonify({
                    'success': True,
                    'face_detected': False,
                    'error': '얼굴이 검출되지 않았습니다. 얼굴을 카메라 정면에 맞춰주세요.',
                    'processing_time_ms': int((time.time() - start_time) * 1000)
                })
            
            # 첫 번째 얼굴 처리
            landmarks = mesh_results.multi_face_landmarks[0].landmark
            detection = detection_results.detections[0]
            
            logger.info(f"얼굴 랜드마크 검출됨: {len(landmarks)}개")
            
            # 🔥 개선된 안경 검출
            glasses_result = face_service.detect_glasses(frame, landmarks)
            logger.info(f"안경 검출 결과: {glasses_result['has_glasses']}")
            
            # 🔥 Liveness Detection (스푸핑 방지)
            liveness_result = face_service.detect_liveness(frame, landmarks)
            logger.info(f"Liveness 검출 결과: {liveness_result['is_live']}")
            
            # 기본 보안 체크
            security_check = face_service._basic_security_check(detection)
            
            # 얼굴 임베딩 생성
            face_embedding = face_service.extract_face_embedding(frame, landmarks)
            
            if face_embedding is None:
                return safe_jsonify({
                    'success': False,
                    'error': '얼굴 임베딩 생성에 실패했습니다',
                    'processing_time_ms': int((time.time() - start_time) * 1000)
                })
            
            processing_time_ms = int((time.time() - start_time) * 1000)
            logger.info(f"✅ 얼굴 검출 성공 - 임베딩 크기: {len(face_embedding)}, 처리시간: {processing_time_ms}ms")
            
            # 🚨 보안 체크 실패시 경고
            security_warnings = []
            if not security_check['passed']:
                security_warnings.append(f"기본 보안 체크: {security_check['reason']}")
            
            if not liveness_result['is_live']:
                security_warnings.append(f"Liveness 검사 실패 (점수: {liveness_result['confidence']:.3f}) - 실제 얼굴인지 확인해주세요")
            
            return safe_jsonify({
                'success': True,
                'face_detected': True,
                'face_encoding': face_embedding.tolist(),
                'glasses_detected': glasses_result['has_glasses'],
                'glasses_confidence': glasses_result['confidence'],
                'glasses_details': {
                    'left_score': glasses_result.get('left_score', 0),
                    'right_score': glasses_result.get('right_score', 0),
                    'nose_bridge': glasses_result.get('nose_bridge_score', 0),
                    'frame_connectivity': glasses_result.get('frame_connectivity_score', 0)
                },
                'liveness_check': {
                    'is_live': liveness_result['is_live'],
                    'confidence': liveness_result['confidence'],
                    'details': liveness_result['checks']
                },
                'quality_score': min(0.95, max(0.5, 
                    security_check.get('confidence', 0.8) * 0.4 + 
                    glasses_result['confidence'] * 0.3 + 
                    liveness_result['confidence'] * 0.3
                )),
                'security_check': security_check,
                'security_warnings': security_warnings,
                'processing_time_ms': processing_time_ms,
                'embedding_dimensions': len(face_embedding),
                'landmark_count': len(landmarks)
            })
            
        except Exception as e:
            logger.error(f"얼굴 처리 오류: {e}")
            return safe_jsonify({
                'success': False,
                'error': f'얼굴 처리 중 오류가 발생했습니다: {str(e)}',
                'processing_time_ms': int((time.time() - start_time) * 1000)
            })
            
    except Exception as e:
        logger.error(f"API 오류: {e}")
        return safe_jsonify({
            'success': False,
            'error': f'서버 오류: {str(e)}',
            'processing_time_ms': int((time.time() - start_time) * 1000)
        })

# 웹 인터페이스
WEB_INTERFACE = """
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Face Recognition v2.0</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .header { text-align: center; color: #333; margin-bottom: 30px; }
        .upload-section { border: 2px dashed #ddd; padding: 30px; text-align: center; margin: 20px 0; border-radius: 10px; }
        .upload-section:hover { border-color: #007bff; }
        .button-group { display: flex; gap: 10px; justify-content: center; margin: 20px 0; }
        button { padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .result { margin: 20px 0; padding: 15px; border-radius: 8px; font-family: monospace; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .status-card { background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 10px 0; }
        input[type="file"] { margin: 10px 0; }
        input[type="text"] { padding: 8px; border: 1px solid #ddd; border-radius: 4px; margin: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Enhanced Face Recognition v2.0</h1>
            <p>MediaPipe 기반 고급 얼굴 인식 시스템</p>
        </div>
        
        <div class="status-card">
            <h3>📊 시스템 상태</h3>
            <div id="systemStatus">상태 확인 중...</div>
            <button class="btn-info" onclick="checkHealth()">상태 새로고침</button>
        </div>
        
        <div class="upload-section">
            <h3>📝 얼굴 등록</h3>
            <input type="text" id="memberNo" placeholder="회원번호 입력" style="display: block; margin: 10px auto;">
            <input type="file" id="registerImage" accept="image/*">
            <button class="btn-success" onclick="registerFace()">얼굴 등록</button>
        </div>
        
        <div class="upload-section">
            <h3>🔍 얼굴 인식</h3>
            <input type="file" id="recognizeImage" accept="image/*">
            <button class="btn-primary" onclick="recognizeFace()">얼굴 인식</button>
        </div>
        
        <div id="result"></div>
    </div>

    <script>
        function checkHealth() {
            fetch('/api/face/health')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('systemStatus').innerHTML = 
                        `<strong>상태:</strong> ${data.status}<br>
                         <strong>서비스:</strong> ${data.service}<br>
                         <strong>일반 얼굴:</strong> ${data.database_status.normal_faces}명<br>
                         <strong>안경 얼굴:</strong> ${data.database_status.glasses_faces}명`;
                })
                .catch(error => {
                    document.getElementById('systemStatus').innerHTML = '❌ 연결 실패';
                });
        }
        
        function registerFace() {
            const memberNo = document.getElementById('memberNo').value;
            const input = document.getElementById('registerImage');
            
            if (!memberNo) {
                alert('회원번호를 입력해주세요.');
                return;
            }
            
            if (!input.files[0]) {
                alert('이미지를 선택해주세요.');
                return;
            }
            
            const formData = new FormData();
            formData.append('image', input.files[0]);
            formData.append('mem_sno', memberNo);
            
            document.getElementById('result').innerHTML = '<div class="result">처리 중...</div>';
            
            fetch('/api/face/register', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const resultClass = data.success ? 'success' : 'error';
                document.getElementById('result').innerHTML = 
                    `<div class="result ${resultClass}"><pre>${JSON.stringify(data, null, 2)}</pre></div>`;
                checkHealth(); // 상태 업데이트
            })
            .catch(error => {
                document.getElementById('result').innerHTML = 
                    `<div class="result error">오류: ${error}</div>`;
            });
        }
        
        function recognizeFace() {
            const input = document.getElementById('recognizeImage');
            if (!input.files[0]) {
                alert('이미지를 선택해주세요.');
                return;
            }
            
            const formData = new FormData();
            formData.append('image', input.files[0]);
            
            document.getElementById('result').innerHTML = '<div class="result">인식 중...</div>';
            
            fetch('/api/face/recognize', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const resultClass = data.success ? 'success' : 'error';
                document.getElementById('result').innerHTML = 
                    `<div class="result ${resultClass}"><pre>${JSON.stringify(data, null, 2)}</pre></div>`;
            })
            .catch(error => {
                document.getElementById('result').innerHTML = 
                    `<div class="result error">오류: ${error}</div>`;
            });
        }
        
        // 페이지 로드시 상태 확인
        window.onload = checkHealth;
    </script>
</body>
</html>
"""

@app.route('/')
def index():
    return render_template_string(WEB_INTERFACE)

if __name__ == '__main__':
    logger.info("🚀 Enhanced Face Recognition Service v2.0 시작")
    logger.info(f"🌐 서비스 URL: http://localhost:5001")
    logger.info("🎯 기능: 얼굴 등록/인식, 안경 검출, 코사인 유사도 매칭")
    
    app.run(
        host='0.0.0.0',
        port=5001,
        debug=False,
        threaded=True
    )

print(" Enhanced Face Service v2.0 완성!")
