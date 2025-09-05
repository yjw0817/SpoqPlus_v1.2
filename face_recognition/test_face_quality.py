#!/usr/bin/env python3
"""
얼굴 품질 검증 테스트 스크립트
"""
import requests
import json
import base64
import os
import cv2
import numpy as np
from datetime import datetime
from typing import Dict, List, Tuple
import sys

# 현재 디렉토리를 Python 경로에 추가
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

# Config 가져오기
try:
    from config import Config
    config = Config()
    # config.py의 HOST와 PORT 사용
    API_BASE_URL = f"http://{config.HOST if config.HOST != '0.0.0.0' else 'localhost'}:{config.PORT}"
except ImportError:
    # config.py가 없는 경우 기본값 사용
    print("⚠️  config.py를 찾을 수 없습니다. 기본 설정을 사용합니다.")
    API_BASE_URL = "http://localhost:5002"

def load_test_image(image_path):
    """테스트 이미지 로드"""
    with open(image_path, 'rb') as f:
        image_data = base64.b64encode(f.read()).decode()
    return f"data:image/jpeg;base64,{image_data}"

def test_detect_for_registration(image_path, description):
    """detect_for_registration API 테스트"""
    print(f"\n{'='*60}")
    print(f"테스트: {description}")
    print(f"이미지: {image_path}")
    print(f"{'='*60}")
    
    try:
        # 이미지 로드
        image_data = load_test_image(image_path)
        
        # API 호출
        response = requests.post(
            f"{API_BASE_URL}/api/face/detect_for_registration",
            json={"image": image_data},
            headers={"Content-Type": "application/json"}
        )
        
        result = response.json()
        
        # 결과 출력
        print(f"\n상태 코드: {response.status_code}")
        print(f"성공 여부: {result.get('success')}")
        print(f"얼굴 감지: {result.get('face_detected')}")
        print(f"등록 적합성: {result.get('suitable_for_registration')}")
        
        # 얼굴 포즈 정보
        if 'face_pose' in result:
            pose = result['face_pose']
            print(f"\n[얼굴 각도]")
            print(f"  - Yaw (좌우): {pose['yaw']:.1f}도")
            print(f"  - Pitch (상하): {pose['pitch']:.1f}도")
            print(f"  - Roll (기울기): {pose['roll']:.1f}도")
            print(f"  - 정면 여부: {pose['is_frontal']}")
        
        # 품질 상세 정보
        if 'quality_details' in result:
            quality = result['quality_details']
            print(f"\n[품질 정보]")
            print(f"  - 얼굴 크기 비율: {quality['face_size_ratio']:.2%}")
            print(f"  - 중앙 위치: {quality['face_centered']}")
            print(f"  - 검출 신뢰도: {quality['detection_confidence']:.2f}")
            print(f"  - 전체 품질 점수: {quality['overall_quality_score']:.2f}")
        
        # 권장사항
        if result.get('recommendations'):
            print(f"\n[개선 권장사항]")
            for i, rec in enumerate(result['recommendations'], 1):
                print(f"  {i}. {rec}")
        else:
            print(f"\n✅ 등록에 적합한 이미지입니다!")
        
        # 기타 정보
        print(f"\n[기타 정보]")
        print(f"  - 품질 점수: {result.get('quality_score', 0):.2f}")
        print(f"  - Liveness 점수: {result.get('liveness_score', 0):.2f}")
        print(f"  - 처리 시간: {result.get('processing_time_ms', 0):.0f}ms")
        
        return result
        
    except Exception as e:
        print(f"\n❌ 오류 발생: {str(e)}")
        return None

def create_test_images():
    """테스트 이미지 생성"""
    if not os.path.exists("test_images"):
        os.makedirs("test_images")
        print("✅ test_images 폴더를 생성했습니다.")
    
    # 샘플 이미지 생성 안내
    print("\n테스트 이미지 생성 안내:")
    print("1. 웹캠을 사용하여 테스트 이미지를 캡처하려면 'c' 키를 누르세요")
    print("2. 기존 이미지를 test_images 폴더에 복사하세요")
    print("3. 다음 유형의 이미지를 준비하면 더 좋습니다:")
    print("   - frontal_face.jpg: 정면 얼굴")
    print("   - side_face.jpg: 측면 얼굴 (각도 15도 이상)")
    print("   - small_face.jpg: 작은 얼굴 (화면의 20% 미만)")
    print("   - large_face.jpg: 큰 얼굴 (화면의 60% 이상)")
    print("   - tilted_face.jpg: 기울어진 얼굴")
    print("   - dark_image.jpg: 어두운 조명")
    print("   - bright_image.jpg: 너무 밝은 조명")

def capture_test_images():
    """웹캠을 사용하여 테스트 이미지 캡처"""
    if not os.path.exists("test_images"):
        os.makedirs("test_images")
    
    cap = cv2.VideoCapture(0)
    if not cap.isOpened():
        print("❌ 웹캠을 열 수 없습니다.")
        return
    
    print("\n웹캠 캡처 모드:")
    print("- 'c': 이미지 캡처")
    print("- 'q': 종료")
    print("- '1': 정면 얼굴 캡처")
    print("- '2': 측면 얼굴 캡처")
    print("- '3': 작은 얼굴 캡처")
    print("- '4': 큰 얼굴 캡처")
    print("- '5': 기울어진 얼굴 캡처")
    
    image_types = {
        '1': 'frontal_face.jpg',
        '2': 'side_face.jpg',
        '3': 'small_face.jpg',
        '4': 'large_face.jpg',
        '5': 'tilted_face.jpg'
    }
    
    while True:
        ret, frame = cap.read()
        if not ret:
            break
        
        # 가이드라인 표시
        h, w = frame.shape[:2]
        cv2.rectangle(frame, (w//4, h//4), (3*w//4, 3*h//4), (0, 255, 0), 2)
        cv2.putText(frame, "Place face within rectangle", (w//4, h//4 - 10), 
                    cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 255, 0), 2)
        
        cv2.imshow('Test Image Capture', frame)
        
        key = cv2.waitKey(1) & 0xFF
        if key == ord('q'):
            break
        elif key == ord('c'):
            filename = f"test_images/capture_{datetime.now().strftime('%Y%m%d_%H%M%S')}.jpg"
            cv2.imwrite(filename, frame)
            print(f"✅ 이미지 저장: {filename}")
        elif key in [ord(k) for k in image_types.keys()]:
            filename = f"test_images/{image_types[chr(key)]}"
            cv2.imwrite(filename, frame)
            print(f"✅ 이미지 저장: {filename}")
    
    cap.release()
    cv2.destroyAllWindows()

def analyze_image_quality(image_path: str) -> Dict:
    """이미지 품질 사전 분석"""
    img = cv2.imread(image_path)
    if img is None:
        return {"error": "이미지를 읽을 수 없습니다"}
    
    # 밝기 분석
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    brightness = np.mean(gray)
    
    # 대비 분석
    contrast = np.std(gray)
    
    # 선명도 분석 (Laplacian)
    laplacian = cv2.Laplacian(gray, cv2.CV_64F)
    sharpness = np.var(laplacian)
    
    return {
        "brightness": brightness,
        "contrast": contrast,
        "sharpness": sharpness,
        "is_too_dark": brightness < 50,
        "is_too_bright": brightness > 200,
        "is_blurry": sharpness < 100
    }

def main():
    """메인 테스트 함수"""
    print("="*60)
    print("얼굴 품질 검증 테스트")
    print(f"시작 시간: {datetime.now()}")
    print("="*60)
    
    # 테스트할 이미지 목록 (실제 경로로 변경 필요)
    test_cases = [
        ("test_images/frontal_face.jpg", "정면 얼굴"),
        ("test_images/side_face.jpg", "측면 얼굴"),
        ("test_images/small_face.jpg", "작은 얼굴"),
        ("test_images/large_face.jpg", "큰 얼굴"),
        ("test_images/tilted_face.jpg", "기울어진 얼굴"),
        ("test_images/dark_image.jpg", "어두운 이미지"),
        ("test_images/bright_image.jpg", "밝은 이미지"),
        ("test_images/blurry_image.jpg", "흐릿한 이미지"),
    ]
    
    # 테스트 이미지가 없는 경우 예제 생성 안내
    if not os.path.exists("test_images"):
        print("\n⚠️  test_images 폴더가 없습니다.")
        create_test_images()
        
        # 웹캠 캡처 옵션
        response = input("\n웹캠으로 테스트 이미지를 캡처하시겠습니까? (y/n): ")
        if response.lower() == 'y':
            capture_test_images()
        else:
            print("\ntest_images 폴더에 테스트할 이미지를 준비한 후 다시 실행해주세요.")
            return
    
    # 실제 존재하는 이미지 파일 찾기
    if os.path.exists("test_images"):
        existing_images = [f for f in os.listdir("test_images") if f.endswith(('.jpg', '.jpeg', '.png'))]
        if existing_images:
            print(f"\n발견된 이미지 파일: {len(existing_images)}개")
            test_cases = [(f"test_images/{img}", img) for img in existing_images[:10]]  # 최대 10개만
    
    # 각 테스트 케이스 실행
    results = []
    for image_path, description in test_cases:
        if os.path.exists(image_path):
            # 이미지 품질 사전 분석
            quality_info = analyze_image_quality(image_path)
            print(f"\n[이미지 품질 분석] {description}")
            print(f"  - 밝기: {quality_info.get('brightness', 0):.1f}")
            print(f"  - 대비: {quality_info.get('contrast', 0):.1f}")
            print(f"  - 선명도: {quality_info.get('sharpness', 0):.1f}")
            
            # API 테스트
            result = test_detect_for_registration(image_path, description)
            results.append((description, result))
        else:
            print(f"\n⚠️  {image_path} 파일이 없어 건너뜁니다.")
    
    # 결과 요약
    print("\n" + "="*60)
    print("테스트 결과 요약")
    print("="*60)
    
    suitable_count = 0
    for desc, result in results:
        if result:
            suitable = result.get('suitable_for_registration', False)
            suitable_count += suitable
            status = "✅ 적합" if suitable else "❌ 부적합"
            quality_score = result.get('quality_score', 0)
            liveness_score = result.get('liveness_score', 0)
            print(f"{desc:30} : {status} (품질: {quality_score:.2f}, Liveness: {liveness_score:.2f})")
    
    print(f"\n총 {len(results)}개 중 {suitable_count}개 등록 적합")
    
    # 상세 통계
    if results:
        print("\n" + "="*60)
        print("상세 통계")
        print("="*60)
        
        # 평균 점수 계산
        quality_scores = [r[1].get('quality_score', 0) for r in results if r[1]]
        liveness_scores = [r[1].get('liveness_score', 0) for r in results if r[1]]
        processing_times = [r[1].get('processing_time_ms', 0) for r in results if r[1]]
        
        if quality_scores:
            print(f"평균 품질 점수: {np.mean(quality_scores):.2f}")
            print(f"평균 Liveness 점수: {np.mean(liveness_scores):.2f}")
            print(f"평균 처리 시간: {np.mean(processing_times):.0f}ms")

if __name__ == "__main__":
    import sys
    
    # 명령줄 인자로 단일 이미지 테스트
    if len(sys.argv) > 1:
        image_path = sys.argv[1]
        if os.path.exists(image_path):
            test_detect_for_registration(image_path, "사용자 제공 이미지")
        else:
            print(f"❌ 파일을 찾을 수 없습니다: {image_path}")
    else:
        main()