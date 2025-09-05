#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
개선된 얼굴 인식 서비스 - 정확도 향상 버전
적응형 임계값과 안경 교차 매칭 지원
"""

import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from scipy.spatial.distance import cosine
import cv2
import logging
from datetime import datetime
import json

# 로깅 설정
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

class ImprovedFaceRecognitionService:
    def __init__(self):
        """개선된 얼굴 인식 서비스 초기화"""
        
        # 적응형 임계값 설정
        self.thresholds = {
            'high_confidence': 0.85,      # 즉시 승인
            'medium_confidence': 0.75,    # 추가 검증 필요
            'low_confidence': 0.70,       # 특별 조건 필요
            'rejection': 0.70,            # 거부 임계값
            
            # 상황별 조정값
            'glasses_change_penalty': 0.95,  # 안경 변화 시 페널티
            'quality_bonus': 1.02,           # 고품질 이미지 보너스
            'time_penalty': 0.99,            # 시간 경과 페널티
        }
        
        # 품질 관련 설정
        self.quality_settings = {
            'min_face_size': (80, 80),
            'blur_threshold': 100.0,
            'brightness_range': (50, 200),
            'contrast_threshold': 40.0,
        }
        
        logger.info("개선된 얼굴 인식 서비스 초기화 완료")
    
    def adaptive_matching(self, stored_encoding, test_encoding, metadata=None):
        """
        적응형 매칭 시스템
        
        Args:
            stored_encoding: 저장된 얼굴 인코딩
            test_encoding: 테스트 얼굴 인코딩
            metadata: 추가 메타데이터 (안경, 품질 등)
        
        Returns:
            tuple: (매칭 여부, 유사도, 결정 이유)
        """
        if metadata is None:
            metadata = {}
        
        # 기본 유사도 계산
        similarity = 1 - cosine(stored_encoding, test_encoding)
        adjusted_similarity = similarity
        
        # 메타데이터 기반 조정
        adjustments = []
        
        # 안경 상태 변화 확인
        if metadata.get('stored_glasses') != metadata.get('test_glasses'):
            adjusted_similarity *= self.thresholds['glasses_change_penalty']
            adjustments.append(f"안경 변화 페널티 적용 ({self.thresholds['glasses_change_penalty']})")
        
        # 이미지 품질 보너스
        if metadata.get('quality_score', 0) >= 0.8:
            adjusted_similarity *= self.thresholds['quality_bonus']
            adjustments.append(f"고품질 보너스 적용 ({self.thresholds['quality_bonus']})")
        
        # 시간 경과 페널티 (오래된 등록 데이터)
        if metadata.get('days_since_registration', 0) > 180:
            adjusted_similarity *= self.thresholds['time_penalty']
            adjustments.append("180일 이상 경과 페널티 적용")
        
        # 단계별 결정
        decision_reason = f"원본 유사도: {similarity:.4f}, 조정된 유사도: {adjusted_similarity:.4f}"
        if adjustments:
            decision_reason += f", 조정사항: {', '.join(adjustments)}"
        
        # 1단계: 높은 신뢰도
        if adjusted_similarity >= self.thresholds['high_confidence']:
            return True, adjusted_similarity, f"높은 신뢰도 매칭 - {decision_reason}"
        
        # 2단계: 중간 신뢰도
        elif adjusted_similarity >= self.thresholds['medium_confidence']:
            # 추가 조건 확인
            if metadata.get('consistent_features', True):
                return True, adjusted_similarity, f"중간 신뢰도 매칭 (특징 일관성) - {decision_reason}"
            else:
                return False, adjusted_similarity, f"중간 신뢰도 거부 (특징 불일치) - {decision_reason}"
        
        # 3단계: 낮은 신뢰도
        elif adjusted_similarity >= self.thresholds['low_confidence']:
            # 특별 조건 확인
            if metadata.get('manual_verification', False):
                return True, adjusted_similarity, f"낮은 신뢰도 수동 승인 - {decision_reason}"
            else:
                return False, adjusted_similarity, f"낮은 신뢰도 거부 - {decision_reason}"
        
        # 4단계: 거부
        else:
            return False, adjusted_similarity, f"임계값 미달 거부 - {decision_reason}"
    
    def find_best_match_with_tolerance(self, test_encoding, database_faces, test_metadata=None):
        """
        안경 변화와 품질을 고려한 최적 매칭 찾기
        
        Args:
            test_encoding: 테스트 얼굴 인코딩
            database_faces: 데이터베이스의 얼굴 목록
            test_metadata: 테스트 이미지 메타데이터
        
        Returns:
            dict: 최적 매칭 결과
        """
        if test_metadata is None:
            test_metadata = {}
        
        best_match = None
        best_similarity = 0
        match_details = []
        
        # 1차: 동일 조건 매칭
        for face in database_faces:
            if face.get('glasses_detected') == test_metadata.get('glasses_detected'):
                similarity = 1 - cosine(face['encoding'], test_encoding)
                
                match_info = {
                    'face_id': face['face_id'],
                    'member_id': face['mem_sno'],
                    'similarity': similarity,
                    'glasses_match': True,
                    'stage': 'primary'
                }
                
                if similarity > best_similarity:
                    best_similarity = similarity
                    best_match = match_info
                
                match_details.append(match_info)
        
        # 2차: 교차 조건 매칭 (임계값 조정)
        if best_similarity < self.thresholds['medium_confidence']:
            for face in database_faces:
                if face.get('glasses_detected') != test_metadata.get('glasses_detected'):
                    similarity = 1 - cosine(face['encoding'], test_encoding)
                    # 안경 변화 페널티 적용
                    adjusted_similarity = similarity * self.thresholds['glasses_change_penalty']
                    
                    match_info = {
                        'face_id': face['face_id'],
                        'member_id': face['mem_sno'],
                        'similarity': similarity,
                        'adjusted_similarity': adjusted_similarity,
                        'glasses_match': False,
                        'stage': 'secondary'
                    }
                    
                    if adjusted_similarity > best_similarity:
                        best_similarity = adjusted_similarity
                        best_match = match_info
                    
                    match_details.append(match_info)
        
        # 결과 구성
        result = {
            'best_match': best_match,
            'best_similarity': best_similarity,
            'total_candidates': len(database_faces),
            'match_details': sorted(match_details, key=lambda x: x.get('adjusted_similarity', x['similarity']), reverse=True)[:5]
        }
        
        # 매칭 성공 여부 결정
        if best_match:
            metadata = {
                'stored_glasses': best_match.get('glasses_match', True),
                'test_glasses': test_metadata.get('glasses_detected', False),
                'quality_score': test_metadata.get('quality_score', 0.7)
            }
            
            matched, final_score, reason = self.adaptive_matching(
                np.array([1] * 128),  # Dummy encoding for similarity-based decision
                np.array([1] * 128),
                metadata
            )
            
            # 유사도 기반 결정으로 대체
            if best_similarity >= self.thresholds['low_confidence']:
                result['matched'] = True
                result['confidence'] = 'high' if best_similarity >= self.thresholds['high_confidence'] else 'medium'
            else:
                result['matched'] = False
                result['confidence'] = 'low'
            
            result['decision_reason'] = reason
        else:
            result['matched'] = False
            result['confidence'] = 'none'
            result['decision_reason'] = '매칭 후보 없음'
        
        return result
    
    def enhance_face_image(self, image):
        """
        얼굴 이미지 품질 개선
        
        Args:
            image: 원본 이미지 (numpy array)
        
        Returns:
            tuple: (개선된 이미지, 품질 점수, 개선 내역)
        """
        enhancements = []
        quality_score = 1.0
        
        # 원본 복사
        enhanced = image.copy()
        
        # 1. 블러 검사 및 샤프닝
        blur_score = cv2.Laplacian(cv2.cvtColor(enhanced, cv2.COLOR_BGR2GRAY), cv2.CV_64F).var()
        if blur_score < self.quality_settings['blur_threshold']:
            kernel = np.array([[-1,-1,-1],
                              [-1, 9,-1],
                              [-1,-1,-1]])
            enhanced = cv2.filter2D(enhanced, -1, kernel)
            enhancements.append("샤프닝 적용")
            quality_score *= 0.9
        
        # 2. 밝기 조정
        gray = cv2.cvtColor(enhanced, cv2.COLOR_BGR2GRAY)
        mean_brightness = np.mean(gray)
        
        if mean_brightness < self.quality_settings['brightness_range'][0]:
            # 너무 어두움
            enhanced = cv2.convertScaleAbs(enhanced, alpha=1.3, beta=30)
            enhancements.append("밝기 증가")
            quality_score *= 0.95
        elif mean_brightness > self.quality_settings['brightness_range'][1]:
            # 너무 밝음
            enhanced = cv2.convertScaleAbs(enhanced, alpha=0.8, beta=-20)
            enhancements.append("밝기 감소")
            quality_score *= 0.95
        
        # 3. 히스토그램 균등화 (대비 개선)
        contrast = gray.std()
        if contrast < self.quality_settings['contrast_threshold']:
            # YCrCb 색공간에서 균등화
            ycrcb = cv2.cvtColor(enhanced, cv2.COLOR_BGR2YCrCb)
            ycrcb[:, :, 0] = cv2.equalizeHist(ycrcb[:, :, 0])
            enhanced = cv2.cvtColor(ycrcb, cv2.COLOR_YCrCb2BGR)
            enhancements.append("대비 개선")
            quality_score *= 0.95
        
        # 4. 노이즈 제거
        enhanced = cv2.bilateralFilter(enhanced, 9, 75, 75)
        enhancements.append("노이즈 제거")
        
        # 품질 점수 정규화
        quality_score = max(0.5, min(1.0, quality_score))
        
        return enhanced, quality_score, enhancements
    
    def analyze_recognition_patterns(self, logs):
        """
        인식 패턴 분석 및 임계값 추천
        
        Args:
            logs: 최근 인식 로그 리스트
        
        Returns:
            dict: 분석 결과 및 추천사항
        """
        if not logs:
            return {
                'status': 'no_data',
                'message': '분석할 데이터가 없습니다.'
            }
        
        # 통계 계산
        total_attempts = len(logs)
        successful = sum(1 for log in logs if log.get('success', False))
        success_rate = successful / total_attempts if total_attempts > 0 else 0
        
        # 신뢰도 분포 분석
        confidence_scores = [log.get('confidence_score', 0) for log in logs if log.get('confidence_score') is not None]
        if confidence_scores:
            avg_confidence = np.mean(confidence_scores)
            confidence_distribution = {
                'high': sum(1 for score in confidence_scores if score >= 0.85),
                'medium': sum(1 for score in confidence_scores if 0.75 <= score < 0.85),
                'low': sum(1 for score in confidence_scores if 0.70 <= score < 0.75),
                'very_low': sum(1 for score in confidence_scores if score < 0.70)
            }
        else:
            avg_confidence = 0
            confidence_distribution = {'high': 0, 'medium': 0, 'low': 0, 'very_low': 0}
        
        # 안경 관련 분석
        glasses_logs = [log for log in logs if log.get('glasses_detected', False)]
        glasses_success_rate = sum(1 for log in glasses_logs if log.get('success', False)) / len(glasses_logs) if glasses_logs else 0
        
        # 시간대별 분석
        hourly_stats = {}
        for log in logs:
            hour = log.get('recognition_time', datetime.now()).hour
            if hour not in hourly_stats:
                hourly_stats[hour] = {'attempts': 0, 'success': 0}
            hourly_stats[hour]['attempts'] += 1
            if log.get('success', False):
                hourly_stats[hour]['success'] += 1
        
        # 추천사항 생성
        recommendations = []
        
        if success_rate < 0.8:
            recommendations.append({
                'type': 'threshold',
                'priority': 'high',
                'message': f'성공률이 {success_rate:.1%}로 낮습니다. 임계값을 0.70으로 낮추는 것을 권장합니다.',
                'action': 'lower_threshold'
            })
        
        if confidence_distribution['low'] > total_attempts * 0.2:
            recommendations.append({
                'type': 'algorithm',
                'priority': 'medium',
                'message': '경계선 케이스가 많습니다. 적응형 임계값 시스템 도입을 권장합니다.',
                'action': 'adaptive_threshold'
            })
        
        if glasses_success_rate < 0.7 and glasses_logs:
            recommendations.append({
                'type': 'glasses',
                'priority': 'high',
                'message': f'안경 착용자 성공률이 {glasses_success_rate:.1%}로 낮습니다. 안경 교차 매칭을 활성화하세요.',
                'action': 'enable_cross_matching'
            })
        
        # 결과 반환
        return {
            'status': 'analyzed',
            'summary': {
                'total_attempts': total_attempts,
                'success_rate': success_rate,
                'avg_confidence': avg_confidence,
                'glasses_success_rate': glasses_success_rate
            },
            'confidence_distribution': confidence_distribution,
            'hourly_stats': hourly_stats,
            'recommendations': recommendations
        }
    
    def get_optimal_thresholds(self, performance_data):
        """
        성능 데이터 기반 최적 임계값 계산
        
        Args:
            performance_data: 성능 측정 데이터
        
        Returns:
            dict: 최적화된 임계값 설정
        """
        # 기본값
        optimal = self.thresholds.copy()
        
        # False Positive Rate (FPR)과 False Negative Rate (FNR) 계산
        if 'false_positives' in performance_data and 'false_negatives' in performance_data:
            total_negative = performance_data.get('true_negatives', 0) + performance_data.get('false_positives', 0)
            total_positive = performance_data.get('true_positives', 0) + performance_data.get('false_negatives', 0)
            
            fpr = performance_data['false_positives'] / total_negative if total_negative > 0 else 0
            fnr = performance_data['false_negatives'] / total_positive if total_positive > 0 else 0
            
            # FPR이 높으면 임계값 상향
            if fpr > 0.05:
                optimal['medium_confidence'] += 0.02
                optimal['low_confidence'] += 0.02
            
            # FNR이 높으면 임계값 하향
            if fnr > 0.1:
                optimal['medium_confidence'] -= 0.02
                optimal['low_confidence'] -= 0.02
        
        # 경계값 보정
        optimal['high_confidence'] = max(0.80, min(0.90, optimal['high_confidence']))
        optimal['medium_confidence'] = max(0.70, min(0.80, optimal['medium_confidence']))
        optimal['low_confidence'] = max(0.65, min(0.75, optimal['low_confidence']))
        
        return optimal


# 테스트 코드
if __name__ == "__main__":
    service = ImprovedFaceRecognitionService()
    
    # 테스트 데이터
    test_logs = [
        {'success': True, 'confidence_score': 0.88, 'glasses_detected': False, 'recognition_time': datetime.now()},
        {'success': True, 'confidence_score': 0.76, 'glasses_detected': True, 'recognition_time': datetime.now()},
        {'success': False, 'confidence_score': 0.65, 'glasses_detected': False, 'recognition_time': datetime.now()},
        {'success': True, 'confidence_score': 0.72, 'glasses_detected': True, 'recognition_time': datetime.now()},
    ]
    
    # 패턴 분석
    analysis = service.analyze_recognition_patterns(test_logs)
    print("분석 결과:", json.dumps(analysis, indent=2, default=str))
    
    # 최적 임계값 계산
    performance_data = {
        'true_positives': 85,
        'false_positives': 5,
        'true_negatives': 95,
        'false_negatives': 15
    }
    
    optimal_thresholds = service.get_optimal_thresholds(performance_data)
    print("\n최적 임계값:", json.dumps(optimal_thresholds, indent=2))