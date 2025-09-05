#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Face Recognition Python Client
SPOQ Plus Face Recognition API를 사용하기 위한 Python 클라이언트
"""

import requests
import base64
import json
import os
from typing import Optional, Dict, Any
from datetime import datetime
import argparse
import cv2
import numpy as np


class FaceRecognitionClient:
    """Face Recognition API 클라이언트"""
    
    def __init__(self, base_url: str = "http://localhost:5002", timeout: int = 30):
        """
        초기화
        
        Args:
            base_url: API 서버 URL
            timeout: 요청 타임아웃 (초)
        """
        self.base_url = base_url.rstrip('/')
        self.timeout = timeout
        self.session = requests.Session()
        self.session.headers.update({
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        })
    
    def health_check(self) -> Dict[str, Any]:
        """서버 상태 확인"""
        return self._make_request('GET', '/api/face/health')
    
    def register_face(self, member_id: str, image_data: str, 
                     security_level: int = 3, notes: Optional[str] = None) -> Dict[str, Any]:
        """
        얼굴 등록
        
        Args:
            member_id: 회원 ID
            image_data: Base64 인코딩된 이미지 데이터
            security_level: 보안 레벨 (1-3)
            notes: 메모
        
        Returns:
            API 응답
        """
        data = {
            'member_id': member_id,
            'image': image_data,
            'security_level': security_level
        }
        if notes:
            data['notes'] = notes
        
        return self._make_request('POST', '/api/face/register', data)
    
    def recognize_face(self, image_data: str, 
                      comp_cd: Optional[str] = None, 
                      bcoff_cd: Optional[str] = None) -> Dict[str, Any]:
        """
        얼굴 인식
        
        Args:
            image_data: Base64 인코딩된 이미지 데이터
            comp_cd: 회사 코드
            bcoff_cd: 지점 코드
        
        Returns:
            API 응답
        """
        data = {'image': image_data}
        if comp_cd:
            data['comp_cd'] = comp_cd
        if bcoff_cd:
            data['bcoff_cd'] = bcoff_cd
        
        return self._make_request('POST', '/api/face/recognize', data)
    
    def recognize_for_checkin(self, image_data: str, comp_cd: str, bcoff_cd: str,
                             security_level: int = 3) -> Dict[str, Any]:
        """
        체크인용 얼굴 인식
        
        Args:
            image_data: Base64 인코딩된 이미지 데이터
            comp_cd: 회사 코드
            bcoff_cd: 지점 코드
            security_level: 보안 레벨
        
        Returns:
            API 응답
        """
        data = {
            'image': image_data,
            'comp_cd': comp_cd,
            'bcoff_cd': bcoff_cd,
            'security_level': security_level
        }
        
        return self._make_request('POST', '/api/face/recognize_for_checkin', data)
    
    def detect_for_registration(self, image_data: str) -> Dict[str, Any]:
        """
        등록용 얼굴 검출 (품질 체크)
        
        Args:
            image_data: Base64 인코딩된 이미지 데이터
        
        Returns:
            API 응답
        """
        return self._make_request('POST', '/api/face/detect_for_registration', {'image': image_data})
    
    def detect_blink(self, frames: list) -> Dict[str, Any]:
        """
        눈 깜빡임 감지
        
        Args:
            frames: Base64 인코딩된 이미지 프레임 리스트
        
        Returns:
            API 응답
        """
        return self._make_request('POST', '/api/face/blink_detection', {'frames': frames})
    
    def _make_request(self, method: str, endpoint: str, data: Optional[Dict] = None) -> Dict[str, Any]:
        """HTTP 요청 처리"""
        url = f"{self.base_url}{endpoint}"
        
        try:
            if method == 'GET':
                response = self.session.get(url, timeout=self.timeout)
            elif method == 'POST':
                response = self.session.post(url, json=data, timeout=self.timeout)
            else:
                raise ValueError(f"Unsupported method: {method}")
            
            # 상태 코드 확인
            if response.status_code >= 400:
                return {
                    'success': False,
                    'error': f'HTTP {response.status_code}',
                    'message': response.text
                }
            
            return response.json()
            
        except requests.exceptions.Timeout:
            return {'success': False, 'error': 'Request timeout'}
        except requests.exceptions.ConnectionError:
            return {'success': False, 'error': 'Connection error'}
        except Exception as e:
            return {'success': False, 'error': str(e)}
    
    @staticmethod
    def encode_image_file(file_path: str) -> Optional[str]:
        """
        이미지 파일을 Base64로 인코딩
        
        Args:
            file_path: 이미지 파일 경로
        
        Returns:
            Base64 인코딩된 데이터 URI
        """
        if not os.path.exists(file_path):
            print(f"File not found: {file_path}")
            return None
        
        try:
            with open(file_path, 'rb') as f:
                image_data = f.read()
            
            # MIME 타입 결정
            ext = os.path.splitext(file_path)[1].lower()
            mime_types = {
                '.jpg': 'image/jpeg',
                '.jpeg': 'image/jpeg',
                '.png': 'image/png'
            }
            mime_type = mime_types.get(ext, 'image/jpeg')
            
            # Base64 인코딩
            encoded = base64.b64encode(image_data).decode('utf-8')
            return f"data:{mime_type};base64,{encoded}"
            
        except Exception as e:
            print(f"Error encoding image: {e}")
            return None
    
    @staticmethod
    def encode_cv2_image(image: np.ndarray) -> str:
        """
        OpenCV 이미지를 Base64로 인코딩
        
        Args:
            image: OpenCV 이미지 (numpy array)
        
        Returns:
            Base64 인코딩된 데이터 URI
        """
        _, buffer = cv2.imencode('.jpg', image)
        encoded = base64.b64encode(buffer).decode('utf-8')
        return f"data:image/jpeg;base64,{encoded}"


class FaceRecognitionDemo:
    """Face Recognition 데모 애플리케이션"""
    
    def __init__(self, server_url: str = "http://localhost:5002"):
        self.client = FaceRecognitionClient(server_url)
        self.cap = None
    
    def run_health_check(self):
        """서버 상태 확인"""
        print("🔍 서버 상태 확인 중...")
        result = self.client.health_check()
        
        if 'status' in result and result['status'] == 'healthy':
            print("✅ 서버 정상 작동 중")
            print(f"   서비스: {result.get('service', 'Unknown')}")
            print(f"   버전: {result.get('version', 'Unknown')}")
            print(f"   데이터베이스: {result.get('database', 'Unknown')}")
        else:
            print("❌ 서버 연결 실패")
            print(f"   오류: {result.get('error', 'Unknown error')}")
    
    def register_face_from_file(self, member_id: str, image_path: str):
        """파일에서 얼굴 등록"""
        print(f"\n📸 얼굴 등록: {member_id}")
        
        # 이미지 인코딩
        image_data = self.client.encode_image_file(image_path)
        if not image_data:
            return
        
        # 품질 검사
        print("품질 검사 중...")
        quality_result = self.client.detect_for_registration(image_data)
        
        if quality_result.get('success') and quality_result.get('recommendations', {}).get('suitable_for_registration'):
            print("✅ 품질 검사 통과")
            print(f"   품질 점수: {quality_result.get('quality_score', 0) * 100:.0f}%")
            print(f"   생체 점수: {quality_result.get('liveness_score', 0) * 100:.0f}%")
        else:
            print("❌ 품질 검사 실패")
            messages = quality_result.get('recommendations', {}).get('messages', [])
            for msg in messages:
                print(f"   - {msg}")
            return
        
        # 얼굴 등록
        print("얼굴 등록 중...")
        result = self.client.register_face(member_id, image_data)
        
        if result.get('success'):
            print("✅ 얼굴 등록 성공")
            print(f"   Face ID: {result.get('face_id')}")
            print(f"   품질 점수: {result.get('quality_score', 0) * 100:.0f}%")
            print(f"   안경 감지: {'예' if result.get('glasses_detected') else '아니오'}")
        else:
            print("❌ 얼굴 등록 실패")
            print(f"   오류: {result.get('error', 'Unknown error')}")
    
    def recognize_face_from_file(self, image_path: str):
        """파일에서 얼굴 인식"""
        print(f"\n🔍 얼굴 인식: {image_path}")
        
        # 이미지 인코딩
        image_data = self.client.encode_image_file(image_path)
        if not image_data:
            return
        
        # 얼굴 인식
        result = self.client.recognize_face(image_data)
        
        if result.get('success') and result.get('matched'):
            print("✅ 얼굴 인식 성공")
            print(f"   회원 ID: {result.get('member_id')}")
            print(f"   유사도: {result.get('similarity', 0) * 100:.1f}%")
            print(f"   신뢰도: {result.get('confidence', 0) * 100:.1f}%")
            
            member_info = result.get('member_info', {})
            if member_info:
                print(f"   회원명: {member_info.get('mem_nm', 'Unknown')}")
                print(f"   연락처: {member_info.get('mem_telno_mask', 'Unknown')}")
        elif result.get('success') and not result.get('matched'):
            print("❌ 등록된 얼굴을 찾을 수 없습니다")
            print(f"   최대 유사도: {result.get('similarity', 0) * 100:.1f}%")
        else:
            print("❌ 얼굴 인식 실패")
            print(f"   오류: {result.get('error', 'Unknown error')}")
    
    def start_camera_demo(self):
        """카메라 데모 시작"""
        print("\n📷 카메라 데모 시작")
        print("사용법:")
        print("  - 'c': 사진 촬영 및 인식")
        print("  - 'r': 사진 촬영 및 등록")
        print("  - 'q': 종료")
        
        self.cap = cv2.VideoCapture(0)
        if not self.cap.isOpened():
            print("❌ 카메라를 열 수 없습니다")
            return
        
        while True:
            ret, frame = self.cap.read()
            if not ret:
                break
            
            # 얼굴 감지 영역 표시 (선택사항)
            height, width = frame.shape[:2]
            cv2.rectangle(frame, 
                         (int(width * 0.2), int(height * 0.1)),
                         (int(width * 0.8), int(height * 0.9)),
                         (0, 255, 0), 2)
            
            cv2.imshow('Face Recognition Demo', frame)
            
            key = cv2.waitKey(1) & 0xFF
            
            if key == ord('q'):
                break
            elif key == ord('c'):
                # 촬영 및 인식
                print("\n📸 사진 촬영...")
                image_data = self.client.encode_cv2_image(frame)
                
                result = self.client.recognize_face(image_data)
                if result.get('success') and result.get('matched'):
                    print(f"✅ 인식 성공: {result.get('member_id')} (유사도: {result.get('similarity', 0) * 100:.1f}%)")
                else:
                    print("❌ 인식 실패")
            
            elif key == ord('r'):
                # 촬영 및 등록
                member_id = input("\n회원 ID 입력: ")
                if member_id:
                    print("📸 사진 촬영...")
                    image_data = self.client.encode_cv2_image(frame)
                    
                    result = self.client.register_face(member_id, image_data)
                    if result.get('success'):
                        print(f"✅ 등록 성공: Face ID {result.get('face_id')}")
                    else:
                        print("❌ 등록 실패")
        
        self.cap.release()
        cv2.destroyAllWindows()


def main():
    """메인 함수"""
    parser = argparse.ArgumentParser(description='Face Recognition Client')
    parser.add_argument('--server', default='http://localhost:5002', 
                       help='API 서버 URL (기본값: http://localhost:5002)')
    parser.add_argument('--action', choices=['health', 'register', 'recognize', 'camera'],
                       help='수행할 작업')
    parser.add_argument('--member-id', help='회원 ID (등록 시 필요)')
    parser.add_argument('--image', help='이미지 파일 경로')
    
    args = parser.parse_args()
    
    # 데모 객체 생성
    demo = FaceRecognitionDemo(args.server)
    
    # 작업 수행
    if args.action == 'health':
        demo.run_health_check()
    
    elif args.action == 'register':
        if not args.member_id or not args.image:
            print("❌ --member-id와 --image 옵션이 필요합니다")
            return
        demo.register_face_from_file(args.member_id, args.image)
    
    elif args.action == 'recognize':
        if not args.image:
            print("❌ --image 옵션이 필요합니다")
            return
        demo.recognize_face_from_file(args.image)
    
    elif args.action == 'camera':
        demo.start_camera_demo()
    
    else:
        # 대화형 모드
        print("🚀 Face Recognition Client - 대화형 모드")
        demo.run_health_check()
        
        while True:
            print("\n메뉴:")
            print("1. 얼굴 등록 (파일)")
            print("2. 얼굴 인식 (파일)")
            print("3. 카메라 데모")
            print("4. 서버 상태 확인")
            print("0. 종료")
            
            choice = input("\n선택: ")
            
            if choice == '0':
                break
            elif choice == '1':
                member_id = input("회원 ID: ")
                image_path = input("이미지 파일 경로: ")
                if member_id and image_path:
                    demo.register_face_from_file(member_id, image_path)
            elif choice == '2':
                image_path = input("이미지 파일 경로: ")
                if image_path:
                    demo.recognize_face_from_file(image_path)
            elif choice == '3':
                demo.start_camera_demo()
            elif choice == '4':
                demo.run_health_check()


if __name__ == '__main__':
    main()