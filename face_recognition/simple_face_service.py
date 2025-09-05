#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
SpoqPlus Advanced Face Recognition Service
OpenCV + MediaPipe 기반 고급 보안 기능:
- 기본 얼굴 검출 (OpenCV Haar Cascade)
- 눈 깜빡임 감지 (MediaPipe + EAR)
- 안경 감지 및 관리 (MediaPipe 랜드마크)
- 스푸핑 방지 (움직임, 텍스처 분석)
"""

import cv2
import numpy as np
import json
import mysql.connector
from flask import Flask, request, jsonify
from flask_cors import CORS
import time
import logging
import sys
import os
from dotenv import load_dotenv
import math
import random
from typing import Dict, List, Tuple, Optional
from werkzeug.utils import secure_filename
from PIL import Image
import pymysql

# MediaPipe 임포트 (설치되어 있을 때만)
try:
    import mediapipe as mp
    MEDIAPIPE_AVAILABLE = True
    print("✅ MediaPipe 사용 가능 - 고급 보안 기능 활성화")
except ImportError:
    MEDIAPIPE_AVAILABLE = False
    print("⚠️  MediaPipe 없음 - 기본 기능만 사용")

# .env 파일 로드 (config.env → .env로 복사 필요)
load_dotenv('config.env')

# 로깅 설정
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def euclidean_distance(point1: Tuple[float, float], point2: Tuple[float, float]) -> float:
    """유클리디안 거리 계산 함수 (scipy 대체)"""
    return math.sqrt((point1[0] - point2[0])**2 + (point1[1] - point2[1])**2)

class AdvancedFaceRecognizer:
    """고급 보안 기능이 포함된 얼굴 인식기"""
    
    def __init__(self):
        logger.info("🚀 Advanced Face Recognizer 초기화")
        
        # OpenCV 얼굴 검출기 로드
        cascade_path = cv2.data.haarcascades + 'haarcascade_frontalface_default.xml'
        self.face_cascade = cv2.CascadeClassifier(cascade_path)
        
        if self.face_cascade.empty():
            logger.error("❌ OpenCV 얼굴 검출기 로드 실패")
            raise Exception("OpenCV 얼굴 검출기를 로드할 수 없습니다.")
        
        logger.info("✅ OpenCV 얼굴 검출기 로드 완료")
        
        # MediaPipe 초기화 (가능한 경우)
        self.mediapipe_enabled = MEDIAPIPE_AVAILABLE
        if self.mediapipe_enabled:
            try:
                mp_face_mesh = mp.solutions.face_mesh
                mp_face_detection = mp.solutions.face_detection
                
                self.face_mesh = mp_face_mesh.FaceMesh(
                    max_num_faces=2,
                    refine_landmarks=True,
                    min_detection_confidence=0.5,
                    min_tracking_confidence=0.5
                )
                self.face_detection = mp_face_detection.FaceDetection(
                    model_selection=0,
                    min_detection_confidence=0.5
                )
                
                # 보안 기능 설정
                self.EAR_THRESHOLD = 0.25
                self.BLINK_FRAMES = 3
                self.blink_counter = 0
                self.total_blinks = 0
                self.movement_history = []
                self.texture_scores = []
                
                print("🎯 MediaPipe 고급 보안 기능 초기화 완료")
                
            except Exception as e:
                print(f"⚠️  MediaPipe 초기화 실패: {e}")
                self.mediapipe_enabled = False
        
        # 환경변수에서 데이터베이스 설정 로드
        self.db_config = {
            'host': os.getenv('DB_HOST', 'localhost'),
            'user': os.getenv('DB_USER', 'root'),
            'password': os.getenv('DB_PASSWORD', ''),
            'database': os.getenv('DB_NAME', 'spoqplus'),
            'charset': 'utf8mb4',
            'collation': 'utf8mb4_general_ci',
            'port': int(os.getenv('DB_PORT', '3306')),
            'autocommit': True,
            'use_unicode': True
        }
        
        logger.info(f"📊 데이터베이스 설정: {self.db_config['host']}:{self.db_config['port']}/{self.db_config['database']}")
        
        # 통계
        self.stats = {
            'start_time': time.time(),
            'total_requests': 0,
            'face_detections': 0,
            'successful_recognitions': 0
        }
        
        # 테스트 얼굴 데이터 (실제로는 데이터베이스에서 로드)
        self.test_faces = {
            999: "테스트 사용자",  # 테스트용 회원
            1: "관리자",
            2: "사용자1"
        }
        
        logger.info("✅ Advanced Face Recognizer 초기화 완료")
    
    def detect_faces_basic(self, image: np.ndarray) -> Dict:
        """기본 OpenCV 얼굴 검출"""
        try:
            gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
            
            # 다양한 파라미터로 검출 시도
            faces = self.face_cascade.detectMultiScale(
                gray, 
                scaleFactor=1.1, 
                minNeighbors=5, 
                minSize=(30, 30)
            )
            
            face_list = []
            for (x, y, w, h) in faces:
                face_list.append({
                    'x': int(x),
                    'y': int(y), 
                    'width': int(w),
                    'height': int(h),
                    'confidence': 0.8  # 기본 신뢰도
                })
            
            return {
                'success': True,
                'face_count': len(faces),
                'faces': face_list,
                'method': 'OpenCV_Haar_Cascade',
                'image_size': {
                    'width': image.shape[1],
                    'height': image.shape[0]
                }
            }
            
        except Exception as e:
            return {
                'success': False,
                'error': str(e),
                'face_count': 0
            }
    
    def calculate_ear(self, eye_landmarks: List[Tuple[float, float]]) -> float:
        """Eye Aspect Ratio (EAR) 계산"""
        try:
            if len(eye_landmarks) < 6:
                return 0.4
                
            # 수직 거리 계산
            A = euclidean_distance(eye_landmarks[1], eye_landmarks[5])
            B = euclidean_distance(eye_landmarks[2], eye_landmarks[4])
            
            # 수평 거리 계산  
            C = euclidean_distance(eye_landmarks[0], eye_landmarks[3])
            
            # EAR 계산
            if C > 0:
                ear = (A + B) / (2.0 * C)
                return ear
            return 0.4
        except:
            return 0.4
    
    def detect_blink(self, landmarks: np.ndarray, image_shape: Tuple[int, int]) -> Dict:
        """눈 깜빡임 감지 (MediaPipe 기반)"""
        try:
            height, width = image_shape[:2]
            
            # 왼쪽 눈 좌표 (MediaPipe 랜드마크 인덱스)
            left_eye_indices = [33, 160, 158, 133, 153, 144]
            left_eye_coords = []
            for idx in left_eye_indices:
                x = landmarks[idx].x * width
                y = landmarks[idx].y * height
                left_eye_coords.append((x, y))
            
            # 오른쪽 눈 좌표
            right_eye_indices = [362, 385, 387, 263, 373, 380]
            right_eye_coords = []
            for idx in right_eye_indices:
                x = landmarks[idx].x * width
                y = landmarks[idx].y * height
                right_eye_coords.append((x, y))
            
            # EAR 계산
            left_ear = self.calculate_ear(left_eye_coords)
            right_ear = self.calculate_ear(right_eye_coords)
            ear = (left_ear + right_ear) / 2.0
            
            # 깜빡임 감지
            blink_detected = False
            if ear < self.EAR_THRESHOLD:
                self.blink_counter += 1
            else:
                if self.blink_counter >= self.BLINK_FRAMES:
                    self.total_blinks += 1
                    blink_detected = True
                self.blink_counter = 0
            
            return {
                'ear': round(ear, 3),
                'blink_detected': blink_detected,
                'total_blinks': self.total_blinks,
                'is_alive': ear > 0.15,
                'eye_status': 'closed' if ear < self.EAR_THRESHOLD else 'open'
            }
            
        except Exception as e:
            return {
                'ear': 0.0,
                'blink_detected': False,
                'total_blinks': 0,
                'is_alive': False,
                'error': str(e)
            }
    
    def detect_glasses(self, landmarks: np.ndarray, image: np.ndarray) -> Dict:
        """안경 착용 감지 (MediaPipe 기반)"""
        try:
            height, width = image.shape[:2]
            
            # 코다리 영역 분석
            nose_bridge_indices = [6, 168, 8, 9, 10, 151]
            nose_points = []
            for idx in nose_bridge_indices:
                x = int(landmarks[idx].x * width)
                y = int(landmarks[idx].y * height)
                nose_points.append((x, y))
            
            # 안경 감지 지표들
            indicators = 0
            
            # 1. 코다리 부분 픽셀 강도 변화
            gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
            
            # 코다리 영역 ROI
            if len(nose_points) >= 4:
                x_coords = [p[0] for p in nose_points]
                y_coords = [p[1] for p in nose_points] 
                
                x_min, x_max = max(0, min(x_coords) - 5), min(width, max(x_coords) + 5)
                y_min, y_max = max(0, min(y_coords) - 10), min(height, max(y_coords) + 10)
                
                nose_roi = gray[y_min:y_max, x_min:x_max]
                
                if nose_roi.size > 0:
                    # 픽셀 강도 분산 (안경다리가 있으면 분산이 큼)
                    intensity_var = np.var(nose_roi)
                    if intensity_var > 200:
                        indicators += 1
            
            # 2. 눈썹 위쪽 수평선 검출 (안경 프레임)
            brow_indices = [70, 63, 105, 66, 107]  # 눈썹 랜드마크
            brow_y_avg = np.mean([landmarks[idx].y * height for idx in brow_indices])
            
            # 눈썹 위쪽 영역에서 엣지 검출
            brow_region = gray[max(0, int(brow_y_avg) - 20):int(brow_y_avg), :]
            if brow_region.size > 0:
                edges = cv2.Canny(brow_region, 50, 150)
                lines = cv2.HoughLinesP(edges, 1, np.pi/180, threshold=20, minLineLength=15, maxLineGap=3)
                
                if lines is not None and len(lines) > 2:
                    indicators += 1
            
            # 3. 반사광 패턴 (렌즈 반사)
            left_eye_center = landmarks[468]  # 왼쪽 눈 중심
            right_eye_center = landmarks[473]  # 오른쪽 눈 중심
            
            reflection_count = 0
            for eye_center in [left_eye_center, right_eye_center]:
                eye_x = int(eye_center.x * width)
                eye_y = int(eye_center.y * height)
                
                # 눈 중심 주변 영역
                eye_roi = gray[max(0, eye_y-15):eye_y+15, max(0, eye_x-15):eye_x+15]
                if eye_roi.size > 0:
                    bright_pixels = cv2.threshold(eye_roi, 200, 255, cv2.THRESH_BINARY)[1]
                    bright_ratio = np.sum(bright_pixels == 255) / eye_roi.size
                    
                    if bright_ratio > 0.1:  # 10% 이상 밝은 픽셀
                        reflection_count += 1
            
            if reflection_count >= 1:
                indicators += 1
            
            # 안경 착용 판단
            confidence = indicators / 3.0
            wearing_glasses = confidence > 0.6
            
            return {
                'wearing_glasses': wearing_glasses,
                'confidence': round(confidence, 3),
                'indicators': indicators,
                'analysis': {
                    'nose_bridge_detected': indicators >= 1,
                    'frame_pattern_detected': indicators >= 2, 
                    'reflection_detected': reflection_count >= 1
                }
            }
            
        except Exception as e:
            return {
                'wearing_glasses': False,
                'confidence': 0.0,
                'error': str(e)
            }
    
    def detect_liveness(self, image: np.ndarray, landmarks: np.ndarray) -> Dict:
        """스푸핑 방지 - 생체 감지"""
        try:
            height, width = image.shape[:2]
            
            # 1. 움직임 감지 (코끝 추적)
            nose_tip = landmarks[1]  # 코끝
            nose_pos = (nose_tip.x * width, nose_tip.y * height)
            
            self.movement_history.append(nose_pos)
            if len(self.movement_history) > 10:
                self.movement_history.pop(0)
            
            movement_score = 0.5
            if len(self.movement_history) >= 3:
                movements = []
                for i in range(1, len(self.movement_history)):
                    prev_pos = self.movement_history[i-1]
                    curr_pos = self.movement_history[i]
                    movement = euclidean_distance(prev_pos, curr_pos)
                    movements.append(movement)
                
                avg_movement = np.mean(movements)
                
                # 자연스러운 움직임 점수
                if 2 < avg_movement < 15:
                    movement_score = 1.0
                elif 1 < avg_movement < 25:
                    movement_score = 0.7
                else:
                    movement_score = 0.3
            
            # 2. 텍스처 분석 (실제 피부 vs 사진)
            face_center_x = int(landmarks[1].x * width)
            face_center_y = int(landmarks[1].y * height)
            
            # 얼굴 중앙 영역 추출
            roi_size = 40
            x1 = max(0, face_center_x - roi_size//2)
            y1 = max(0, face_center_y - roi_size//2)
            x2 = min(width, face_center_x + roi_size//2)
            y2 = min(height, face_center_y + roi_size//2)
            
            gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
            face_roi = gray[y1:y2, x1:x2]
            
            texture_score = 0.5
            if face_roi.size > 0:
                # Laplacian 분산으로 텍스처 품질 측정
                laplacian_var = cv2.Laplacian(face_roi, cv2.CV_64F).var()
                
                if 100 < laplacian_var < 800:  # 실제 피부 범위
                    texture_score = 1.0
                elif 50 < laplacian_var < 1200:
                    texture_score = 0.7
                else:
                    texture_score = 0.3
            
            # 3. 얼굴 크기/비율 검사 (거리 추정)
            left_face = landmarks[234]  # 왼쪽 얼굴 경계
            right_face = landmarks[454]  # 오른쪽 얼굴 경계
            
            face_width = abs(left_face.x - right_face.x) * width
            
            depth_score = 0.5
            if 60 < face_width < 250:  # 적절한 거리
                depth_score = 1.0
            elif 40 < face_width < 350:
                depth_score = 0.7
            else:
                depth_score = 0.3
            
            # 종합 생체 점수
            liveness_score = (movement_score * 0.4 + texture_score * 0.4 + depth_score * 0.2)
            is_real_person = liveness_score > 0.65
            
            return {
                'is_real_person': is_real_person,
                'liveness_score': round(liveness_score, 3),
                'movement_score': round(movement_score, 3),
                'texture_score': round(texture_score, 3),
                'depth_score': round(depth_score, 3),
                'face_width': round(face_width, 1),
                'anti_spoofing_passed': is_real_person
            }
            
        except Exception as e:
            return {
                'is_real_person': False,
                'liveness_score': 0.0,
                'error': str(e)
            }
    
    def process_image_advanced(self, image: np.ndarray) -> Dict:
        """고급 보안 기능이 포함된 이미지 처리"""
        if not self.mediapipe_enabled:
            # MediaPipe가 없으면 기본 검출만
            return self.detect_faces_basic(image)
        
        try:
            # MediaPipe로 얼굴 검출 및 랜드마크 추출
            rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
            mesh_results = self.face_mesh.process(rgb_image)
            detection_results = self.face_detection.process(rgb_image)
            
            if not mesh_results.multi_face_landmarks:
                # MediaPipe 실패 시 기본 검출 사용
                return self.detect_faces_basic(image)
            
            # 첫 번째 얼굴 처리
            face_landmarks = mesh_results.multi_face_landmarks[0]
            landmarks = face_landmarks.landmark
            
            # 각 보안 기능 실행
            blink_result = self.detect_blink(landmarks, image.shape)
            glasses_result = self.detect_glasses(landmarks, image)
            liveness_result = self.detect_liveness(image, landmarks)
            
            # 얼굴 경계 상자 계산 (기본 호환성 위해)
            face_bbox = self._calculate_face_bbox(landmarks, image.shape)
            
            # 종합 보안 점수
            security_score = (
                (1.0 if blink_result['is_alive'] else 0.0) * 0.3 +
                liveness_result['liveness_score'] * 0.5 +
                (0.9 if not glasses_result['wearing_glasses'] else 0.7) * 0.2
            )
            
            return {
                'success': True,
                'face_count': len(mesh_results.multi_face_landmarks),
                'faces': [face_bbox] if face_bbox else [],
                'method': 'MediaPipe_Advanced',
                'security_features': {
                    'blink_detection': blink_result,
                    'glasses_detection': glasses_result,
                    'liveness_detection': liveness_result,
                    'security_score': round(security_score, 3),
                    'security_level': 'HIGH' if security_score > 0.8 else 'MEDIUM' if security_score > 0.6 else 'LOW',
                    'security_passed': security_score > 0.7
                },
                'image_size': {
                    'width': image.shape[1],
                    'height': image.shape[0]
                },
                'advanced_mode': True
            }
            
        except Exception as e:
            # 오류 시 기본 모드로 폴백
            print(f"고급 모드 오류, 기본 모드로 전환: {e}")
            basic_result = self.detect_faces_basic(image)
            basic_result['fallback_to_basic'] = True
            return basic_result
    
    def _calculate_face_bbox(self, landmarks: np.ndarray, image_shape: Tuple[int, int]) -> Optional[Dict]:
        """MediaPipe 랜드마크에서 얼굴 경계상자 계산"""
        try:
            height, width = image_shape[:2]
            
            # 얼굴 경계 랜드마크들
            x_coords = [landmarks[i].x * width for i in [10, 234, 454, 152]]  # 주요 경계점들
            y_coords = [landmarks[i].y * height for i in [10, 234, 454, 152]]
            
            x_min, x_max = int(min(x_coords)), int(max(x_coords))
            y_min, y_max = int(min(y_coords)), int(max(y_coords))
            
            # 여백 추가
            margin = 20
            x_min = max(0, x_min - margin)
            y_min = max(0, y_min - margin)
            x_max = min(width, x_max + margin)
            y_max = min(height, y_max + margin)
            
            return {
                'x': x_min,
                'y': y_min,
                'width': x_max - x_min,
                'height': y_max - y_min,
                'confidence': 0.95
            }
        except:
            return None

    def simulate_recognition(self, face_data):
        """인식 시뮬레이션 (테스트용)"""
        try:
            self.stats['total_requests'] += 1
            
            # 간단한 시뮬레이션 로직
            # 실제로는 face_recognition 라이브러리를 사용
            
            # 랜덤하게 테스트 사용자 인식
            if random.random() > 0.3:  # 70% 확률로 성공
                member_id = 999  # 테스트 사용자
                confidence = 0.75 + random.random() * 0.2  # 0.75-0.95
                
                self.stats['successful_recognitions'] += 1
                
                return {
                    'success': True,
                    'member_id': member_id,
                    'member_name': self.test_faces.get(member_id, "Unknown"),
                    'confidence': round(confidence, 4),
                    'simulation': True
                }
            else:
                return {
                    'success': False,
                    'error': 'No matching face found',
                    'simulation': True
                }
            
        except Exception as e:
            logger.error(f"인식 시뮬레이션 오류: {e}")
            return {'success': False, 'error': str(e)}
    
    def check_database_connection(self):
        """데이터베이스 연결 확인"""
        try:
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor()
            
            # member_faces 테이블 확인
            cursor.execute("SHOW TABLES LIKE 'member_faces'")
            table_exists = cursor.fetchone() is not None
            
            if table_exists:
                cursor.execute("SELECT COUNT(*) FROM member_faces WHERE is_active = 1")
                face_count = cursor.fetchone()[0]
            else:
                face_count = 0
            
            cursor.close()
            conn.close()
            
            return {
                'connected': True,
                'table_exists': table_exists,
                'face_count': face_count
            }
            
        except Exception as e:
            logger.error(f"데이터베이스 연결 오류: {e}")
            return {
                'connected': False,
                'error': str(e)
            }

# Flask 앱 생성
app = Flask(__name__)
CORS(app)
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB 제한

# 서비스 인스턴스
try:
    face_service = AdvancedFaceRecognizer()
    logger.info("🎉 서비스 준비 완료")
except Exception as e:
    logger.error(f"❌ 서비스 초기화 실패: {e}")
    sys.exit(1)

@app.route('/api/face/health', methods=['GET'])
def health_check():
    """헬스체크"""
    try:
        uptime = time.time() - face_service.stats['start_time']
        db_status = face_service.check_database_connection()
        
        return jsonify({
            "status": "healthy",
            "timestamp": time.time(),
            "uptime_seconds": int(uptime),
            "version": "1.0.0-advanced",
            "opencv_version": cv2.__version__,
            "stats": face_service.stats,
            "database": db_status,
            "features": {
                "face_detection": True,
                "face_recognition": False,  # 아직 구현 안됨
                "simulation_mode": True
            }
        })
        
    except Exception as e:
        logger.error(f"헬스체크 오류: {e}")
        return jsonify({
            "status": "unhealthy",
            "error": str(e),
            "timestamp": time.time()
        }), 500

@app.route('/api/face/detect', methods=['POST'])
def detect_faces():
    """얼굴 검출 엔드포인트"""
    try:
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No image file provided'
            }), 400
        
        image_file = request.files['image']
        if image_file.filename == '':
            return jsonify({
                'success': False,
                'error': 'No image file selected'
            }), 400
        
        # 이미지 데이터 읽기
        image_data = image_file.read()
        
        # 얼굴 검출
        result = face_service.process_image_advanced(cv2.imdecode(np.frombuffer(image_data, np.uint8), cv2.IMREAD_COLOR))
        
        return jsonify(result)
        
    except Exception as e:
        logger.error(f"얼굴 검출 API 오류: {e}")
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/face/recognize_simulate', methods=['POST'])
def recognize_simulate():
    """인식 시뮬레이션 엔드포인트"""
    try:
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No image file provided'
            }), 400
        
        image_file = request.files['image']
        if image_file.filename == '':
            return jsonify({
                'success': False,
                'error': 'No image file selected'
            }), 400
        
        # 이미지 데이터 읽기
        image_data = image_file.read()
        
        # 먼저 얼굴 검출
        detection_result = face_service.process_image_advanced(cv2.imdecode(np.frombuffer(image_data, np.uint8), cv2.IMREAD_COLOR))
        
        if not detection_result.get('success'):
            return jsonify({
                'success': False,
                'error': 'Face detection failed',
                'detection_error': detection_result.get('error')
            }), 400
        
        # 시뮬레이션 실행
        result = face_service.simulate_recognition(detection_result)
        
        # 처리 시간 추가
        result['processing_time_ms'] = 50 + int(np.random.random() * 100)  # 50-150ms
        result['faces_detected'] = detection_result.get('face_count', 0)
        
        return jsonify(result)
        
    except Exception as e:
        logger.error(f"인식 시뮬레이션 API 오류: {e}")
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/api/face/stats', methods=['GET'])
def get_stats():
    """통계 조회"""
    try:
        uptime = time.time() - face_service.stats['start_time']
        
        success_rate = 0
        if face_service.stats['total_requests'] > 0:
            success_rate = (face_service.stats['successful_recognitions'] / 
                           face_service.stats['total_requests'] * 100)
        
        return jsonify({
            'uptime_seconds': int(uptime),
            'stats': face_service.stats,
            'success_rate_percent': round(success_rate, 2),
            'database': face_service.check_database_connection()
        })
        
    except Exception as e:
        logger.error(f"통계 조회 오류: {e}")
        return jsonify({'error': str(e)}), 500

@app.route('/checkin', methods=['GET'])
def checkin_interface():
    """얼굴 인식 체크인 웹 인터페이스"""
    return """<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpoqPlus 고급 보안 얼굴 인식 체크인</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 800px;
            width: 95%;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 2.2em;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header p {
            color: #7f8c8d;
            font-size: 1em;
        }
        .video-container {
            position: relative;
            margin: 20px 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        #videoElement {
            width: 100%;
            height: 350px;
            object-fit: cover;
            display: block;
        }
        .face-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        .face-box {
            position: absolute;
            border: 3px solid #00ff00;
            border-radius: 5px;
            background: rgba(0, 255, 0, 0.1);
        }
        .face-label {
            position: absolute;
            background: rgba(0, 255, 0, 0.8);
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .btn {
            padding: 12px 25px;
            margin: 8px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        .btn-success {
            background: linear-gradient(45deg, #00b09b, #96c93d);
            color: white;
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .status {
            margin: 15px 0;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
        }
        .status.info {
            background: rgba(52, 152, 219, 0.1);
            border: 2px solid #3498db;
            color: #2980b9;
        }
        .status.success {
            background: rgba(46, 204, 113, 0.1);
            border: 2px solid #2ecc71;
            color: #27ae60;
        }
        .status.error {
            background: rgba(231, 76, 60, 0.1);
            border: 2px solid #e74c3c;
            color: #c0392b;
        }
        .status.warning {
            background: rgba(241, 196, 15, 0.1);
            border: 2px solid #f1c40f;
            color: #d68910;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin: 20px 0;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #ecf0f1;
        }
        .stat-number {
            font-size: 1.4em;
            font-weight: bold;
            color: #2c3e50;
        }
        .stat-label {
            color: #7f8c8d;
            font-size: 0.8em;
            margin-top: 3px;
        }
        .security-panel {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            border: 2px solid #ecf0f1;
        }
        .security-title {
            font-size: 1.1em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .security-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 8px;
        }
        .security-item {
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.6);
        }
        .security-value {
            font-weight: bold;
            font-size: 1.1em;
        }
        .security-value.good { color: #27ae60; }
        .security-value.warning { color: #f39c12; }
        .security-value.danger { color: #e74c3c; }
        .hidden { display: none; }
        .mode-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 5px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛡️ SpoqPlus 고급 보안 얼굴 인식</h1>
            <p>MediaPipe 기반 고급 보안 기능 (눈 깜빡임, 안경 감지, 스푸핑 방지)</p>
        </div>

        <div class="video-container">
            <video id="videoElement" autoplay muted playsinline></video>
            <div class="face-overlay" id="faceOverlay"></div>
            <div class="mode-indicator" id="modeIndicator">기본 모드</div>
        </div>

        <div class="controls">
            <button id="startBtn" class="btn btn-primary">📷 웹캠 시작</button>
            <button id="checkinBtn" class="btn btn-success hidden" disabled>✅ 보안 체크인</button>
        </div>

        <div id="status" class="status info">
            📋 웹캠을 시작하려면 '웹캠 시작' 버튼을 클릭하세요
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div id="faceCount" class="stat-number">0</div>
                <div class="stat-label">검출된 얼굴</div>
            </div>
            <div class="stat-card">
                <div id="recognitionStatus" class="stat-number">-</div>
                <div class="stat-label">인식 상태</div>
            </div>
            <div class="stat-card">
                <div id="securityScore" class="stat-number">-</div>
                <div class="stat-label">보안 점수</div>
            </div>
            <div class="stat-card">
                <div id="securityLevel" class="stat-number">-</div>
                <div class="stat-label">보안 등급</div>
            </div>
        </div>

        <div class="security-panel" id="securityPanel" style="display: none;">
            <div class="security-title">🔍 실시간 보안 분석</div>
            <div class="security-grid">
                <div class="security-item">
                    <div class="security-label">👁️ 눈 깜빡임</div>
                    <div id="blinkStatus" class="security-value">-</div>
                    <div id="blinkCount" style="font-size: 0.8em; color: #666;">0회</div>
                </div>
                <div class="security-item">
                    <div class="security-label">👓 안경 감지</div>
                    <div id="glassesStatus" class="security-value">-</div>
                    <div id="glassesConf" style="font-size: 0.8em; color: #666;">0%</div>
                </div>
                <div class="security-item">
                    <div class="security-label">🛡️ 생체 감지</div>
                    <div id="livenessStatus" class="security-value">-</div>
                    <div id="livenessScore" style="font-size: 0.8em; color: #666;">0%</div>
                </div>
                <div class="security-item">
                    <div class="security-label">🎯 움직임</div>
                    <div id="movementStatus" class="security-value">-</div>
                    <div id="movementScore" style="font-size: 0.8em; color: #666;">0%</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        class AdvancedFaceRecognitionApp {
            constructor() {
                this.video = document.getElementById('videoElement');
                this.startBtn = document.getElementById('startBtn');
                this.checkinBtn = document.getElementById('checkinBtn');
                this.status = document.getElementById('status');
                this.faceOverlay = document.getElementById('faceOverlay');
                this.securityPanel = document.getElementById('securityPanel');
                this.modeIndicator = document.getElementById('modeIndicator');
                
                this.isStreaming = false;
                this.isProcessing = false;
                this.detectionInterval = null;
                this.lastRecognition = null;
                this.advancedMode = false;
                
                this.init();
            }

            init() {
                this.startBtn.addEventListener('click', () => this.startWebcam());
                this.checkinBtn.addEventListener('click', () => this.performCheckin());
                this.checkServerStatus();
            }

            async checkServerStatus() {
                try {
                    const response = await fetch('/api/face/health');
                    if (response.ok) {
                        const data = await response.json();
                        this.updateStatus('success', `🟢 서버 연결됨 (OpenCV ${data.opencv_version})`);
                        
                        // 보안 기능 상태 확인
                        const secResponse = await fetch('/api/face/security_status');
                        if (secResponse.ok) {
                            const secData = await secResponse.json();
                            this.advancedMode = secData.mediapipe_available;
                            this.modeIndicator.textContent = this.advancedMode ? '고급 보안 모드' : '기본 모드';
                            this.modeIndicator.style.background = this.advancedMode ? 'rgba(0, 255, 0, 0.8)' : 'rgba(255, 165, 0, 0.8)';
                        }
                    } else {
                        this.updateStatus('error', '❌ 서버 연결 실패');
                    }
                } catch (error) {
                    this.updateStatus('error', '❌ 서버에 연결할 수 없습니다');
                }
            }

            async startWebcam() {
                try {
                    this.updateStatus('info', '📷 웹캠 접근 권한을 요청중...');
                    
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                        audio: false
                    });

                    this.video.srcObject = stream;
                    this.isStreaming = true;
                    
                    this.video.onloadedmetadata = () => {
                        this.updateStatus('success', '✅ 웹캠이 시작되었습니다. 얼굴을 카메라에 맞춰주세요');
                        this.startBtn.textContent = '🛑 웹캠 정지';
                        this.startBtn.onclick = () => this.stopWebcam();
                        this.checkinBtn.classList.remove('hidden');
                        
                        if (this.advancedMode) {
                            this.securityPanel.style.display = 'block';
                        }
                        
                        this.startFaceDetection();
                    };

                } catch (error) {
                    this.updateStatus('error', '❌ 웹캠에 접근할 수 없습니다. 권한을 확인해주세요');
                }
            }

            stopWebcam() {
                if (this.video.srcObject) {
                    this.video.srcObject.getTracks().forEach(track => track.stop());
                    this.video.srcObject = null;
                }
                
                if (this.detectionInterval) {
                    clearInterval(this.detectionInterval);
                    this.detectionInterval = null;
                }
                
                this.isStreaming = false;
                this.startBtn.textContent = '📷 웹캠 시작';
                this.startBtn.onclick = () => this.startWebcam();
                this.checkinBtn.classList.add('hidden');
                this.faceOverlay.innerHTML = '';
                this.securityPanel.style.display = 'none';
                
                this.updateStatus('info', '📋 웹캠이 정지되었습니다');
                this.resetStats();
            }

            startFaceDetection() {
                this.detectionInterval = setInterval(() => {
                    if (!this.isProcessing && this.isStreaming) {
                        this.detectFaces();
                    }
                }, 1000);
            }

            async detectFaces() {
                try {
                    this.isProcessing = true;
                    
                    const canvas = document.createElement('canvas');
                    canvas.width = this.video.videoWidth;
                    canvas.height = this.video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(this.video, 0, 0);
                    
                    const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.8));
                    
                    const formData = new FormData();
                    formData.append('image', blob, 'frame.jpg');
                    
                    // 고급 모드 사용 가능 시 고급 검출 사용
                    const endpoint = this.advancedMode ? '/api/face/advanced_detect' : '/api/face/detect';
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        body: formData
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.handleDetectionResult(data);
                        
                        if (data.face_count > 0) {
                            this.attemptRecognition(formData);
                        }
                    }
                    
                } catch (error) {
                    console.error('얼굴 검출 오류:', error);
                } finally {
                    this.isProcessing = false;
                }
            }

            async attemptRecognition(formData) {
                try {
                    const response = await fetch('/api/face/recognize_simulate', {
                        method: 'POST',
                        body: formData
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.handleRecognitionResult(data);
                    }
                } catch (error) {
                    console.error('인식 오류:', error);
                }
            }

            handleDetectionResult(data) {
                this.faceOverlay.innerHTML = '';
                
                // 얼굴 박스 그리기
                if (data.success && data.faces) {
                    const videoRect = this.video.getBoundingClientRect();
                    const scaleX = videoRect.width / data.image_size.width;
                    const scaleY = videoRect.height / data.image_size.height;
                    
                    data.faces.forEach((face, index) => {
                        const faceBox = document.createElement('div');
                        faceBox.className = 'face-box';
                        faceBox.style.left = (face.x * scaleX) + 'px';
                        faceBox.style.top = (face.y * scaleY) + 'px';
                        faceBox.style.width = (face.width * scaleX) + 'px';
                        faceBox.style.height = (face.height * scaleY) + 'px';
                        
                        const label = document.createElement('div');
                        label.className = 'face-label';
                        label.textContent = `얼굴 ${index + 1}`;
                        label.style.left = (face.x * scaleX) + 'px';
                        label.style.top = ((face.y * scaleY) - 20) + 'px';
                        
                        this.faceOverlay.appendChild(faceBox);
                        this.faceOverlay.appendChild(label);
                    });
                }
                
                // 기본 통계 업데이트
                this.updateBasicStats(data.face_count, '-', '-', '-');
                
                // 고급 보안 기능 결과 처리
                if (data.security_features) {
                    this.updateSecurityFeatures(data.security_features);
                }
                
                if (data.face_count > 0) {
                    const securityPassed = data.security_features ? data.security_features.security_passed : true;
                    if (securityPassed) {
                        this.updateStatus('success', `👁️ ${data.face_count}개 얼굴 검출 - 보안 검증 통과`);
                        this.checkinBtn.disabled = false;
                    } else {
                        this.updateStatus('warning', `⚠️ 얼굴 검출되었으나 보안 검증 실패`);
                        this.checkinBtn.disabled = true;
                    }
                } else {
                    this.updateStatus('warning', '👀 얼굴을 찾을 수 없습니다. 카메라 정면을 봐주세요');
                    this.checkinBtn.disabled = true;
                }
            }

            updateSecurityFeatures(security) {
                if (!security) return;
                
                // 보안 점수 및 등급
                document.getElementById('securityScore').textContent = (security.security_score * 100).toFixed(0) + '%';
                document.getElementById('securityLevel').textContent = security.security_level || '-';
                
                // 눈 깜빡임
                if (security.blink_detection) {
                    const blink = security.blink_detection;
                    document.getElementById('blinkStatus').textContent = blink.is_alive ? '정상' : '의심';
                    document.getElementById('blinkStatus').className = 'security-value ' + (blink.is_alive ? 'good' : 'danger');
                    document.getElementById('blinkCount').textContent = blink.total_blinks + '회';
                }
                
                // 안경 감지
                if (security.glasses_detection) {
                    const glasses = security.glasses_detection;
                    document.getElementById('glassesStatus').textContent = glasses.wearing_glasses ? '착용' : '미착용';
                    document.getElementById('glassesStatus').className = 'security-value good';
                    document.getElementById('glassesConf').textContent = (glasses.confidence * 100).toFixed(0) + '%';
                }
                
                // 생체 감지
                if (security.liveness_detection) {
                    const liveness = security.liveness_detection;
                    document.getElementById('livenessStatus').textContent = liveness.is_real_person ? '실제' : '가짜';
                    document.getElementById('livenessStatus').className = 'security-value ' + (liveness.is_real_person ? 'good' : 'danger');
                    document.getElementById('livenessScore').textContent = (liveness.liveness_score * 100).toFixed(0) + '%';
                    document.getElementById('movementScore').textContent = (liveness.movement_score * 100).toFixed(0) + '%';
                }
            }

            handleRecognitionResult(data) {
                if (data.success) {
                    this.lastRecognition = data;
                    this.updateBasicStats(
                        document.getElementById('faceCount').textContent,
                        data.member_name,
                        document.getElementById('securityScore').textContent,
                        document.getElementById('securityLevel').textContent
                    );
                } else {
                    this.updateBasicStats(
                        document.getElementById('faceCount').textContent,
                        '미인식',
                        document.getElementById('securityScore').textContent,
                        document.getElementById('securityLevel').textContent
                    );
                }
            }

            async performCheckin() {
                if (!this.lastRecognition) {
                    this.updateStatus('warning', '⚠️ 먼저 얼굴 인식이 필요합니다');
                    return;
                }
                
                this.checkinBtn.textContent = '체크인 중...';
                this.checkinBtn.disabled = true;
                
                try {
                    await new Promise(resolve => setTimeout(resolve, 1500));
                    
                    this.updateStatus('success', 
                        `🎉 ${this.lastRecognition.member_name}님 보안 체크인이 완료되었습니다!`
                    );
                    
                    setTimeout(() => {
                        this.checkinBtn.textContent = '✅ 보안 체크인';
                        this.checkinBtn.disabled = false;
                        this.lastRecognition = null;
                    }, 3000);
                    
                } catch (error) {
                    this.updateStatus('error', '❌ 체크인 처리 중 오류가 발생했습니다');
                    this.checkinBtn.textContent = '✅ 보안 체크인';
                    this.checkinBtn.disabled = false;
                }
            }

            updateStatus(type, message) {
                this.status.className = `status ${type}`;
                this.status.textContent = message;
            }

            updateBasicStats(faceCount, recognition, securityScore, securityLevel) {
                document.getElementById('faceCount').textContent = faceCount;
                document.getElementById('recognitionStatus').textContent = recognition;
                if (securityScore !== undefined) document.getElementById('securityScore').textContent = securityScore;
                if (securityLevel !== undefined) document.getElementById('securityLevel').textContent = securityLevel;
            }

            resetStats() {
                this.updateBasicStats(0, '-', '-', '-');
                ['blinkStatus', 'glassesStatus', 'livenessStatus', 'movementStatus'].forEach(id => {
                    document.getElementById(id).textContent = '-';
                    document.getElementById(id).className = 'security-value';
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new AdvancedFaceRecognitionApp();
        });
    </script>
</body>
</html>"""

@app.route('/api/face/advanced_detect', methods=['POST'])
def advanced_face_detect():
    """고급 보안 기능이 포함된 얼굴 검출"""
    try:
        if 'image' not in request.files:
            return jsonify({'error': 'No image provided'}), 400
        
        file = request.files['image']
        if file.filename == '':
            return jsonify({'error': 'No image selected'}), 400
        
        # 이미지 읽기
        image_data = file.read()
        nparr = np.frombuffer(image_data, np.uint8)
        image = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        
        if image is None:
            return jsonify({'error': 'Invalid image format'}), 400
        
        # 고급 보안 처리 실행
        result = face_service.process_image_advanced(image)
        
        return jsonify(result)
        
    except Exception as e:
        logger.error(f"고급 얼굴 검출 오류: {e}")
        return jsonify({'error': str(e)}), 500

@app.route('/api/face/security_status', methods=['GET'])
def security_status():
    """보안 기능 상태 확인"""
    try:
        return jsonify({
            'mediapipe_available': face_service.mediapipe_enabled,
            'security_features': {
                'blink_detection': face_service.mediapipe_enabled,
                'glasses_detection': face_service.mediapipe_enabled,
                'liveness_detection': face_service.mediapipe_enabled,
                'anti_spoofing': face_service.mediapipe_enabled
            },
            'current_stats': {
                'total_blinks': getattr(face_service, 'total_blinks', 0),
                'movement_history_size': len(getattr(face_service, 'movement_history', [])),
                'texture_scores_size': len(getattr(face_service, 'texture_scores', []))
            },
            'version': '2.0.0-advanced',
            'timestamp': time.time()
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    logger.info("🚀 Starting Advanced Face Recognition Service on localhost:5000")
    logger.info("📝 OpenCV + MediaPipe 기반 고급 보안 기능")
    app.run(host='0.0.0.0', port=5000, debug=True, threaded=True) 