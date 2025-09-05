#!/usr/bin/env python3
"""
고급 얼굴 품질 검증 테스트 스크립트
- 다양한 테스트 시나리오 지원
- 실시간 웹캠 테스트
- 배치 테스트
- 성능 벤치마크
"""
import requests
import json
import base64
import os
import cv2
import numpy as np
import time
from datetime import datetime
from typing import Dict, List, Tuple, Optional
import argparse
import matplotlib.pyplot as plt
from concurrent.futures import ThreadPoolExecutor, as_completed
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

class FaceQualityTester:
    def __init__(self, api_base_url: str = API_BASE_URL):
        self.api_base_url = api_base_url
        self.test_results = []
        
    def encode_image(self, image_path: str) -> str:
        """이미지를 base64로 인코딩"""
        with open(image_path, 'rb') as f:
            image_data = base64.b64encode(f.read()).decode()
        return f"data:image/jpeg;base64,{image_data}"
    
    def encode_cv2_image(self, image: np.ndarray) -> str:
        """OpenCV 이미지를 base64로 인코딩"""
        _, buffer = cv2.imencode('.jpg', image)
        image_data = base64.b64encode(buffer).decode()
        return f"data:image/jpeg;base64,{image_data}"
    
    def test_single_image(self, image_path: str, description: str = "") -> Dict:
        """단일 이미지 테스트"""
        try:
            # 이미지 인코딩
            image_data = self.encode_image(image_path)
            
            # API 호출
            start_time = time.time()
            response = requests.post(
                f"{self.api_base_url}/api/face/detect_for_registration",
                json={"image": image_data},
                headers={"Content-Type": "application/json"},
                timeout=10
            )
            elapsed_time = (time.time() - start_time) * 1000  # ms
            
            result = response.json()
            result['actual_processing_time_ms'] = elapsed_time
            result['image_path'] = image_path
            result['description'] = description
            
            return result
            
        except Exception as e:
            return {
                'success': False,
                'error': str(e),
                'image_path': image_path,
                'description': description
            }
    
    def test_realtime_webcam(self):
        """실시간 웹캠 테스트"""
        cap = cv2.VideoCapture(0)
        if not cap.isOpened():
            print("❌ 웹캠을 열 수 없습니다.")
            return
        
        print("\n실시간 웹캠 테스트 모드")
        print("- 'q': 종료")
        print("- 's': 현재 프레임 저장 및 테스트")
        print("- 'c': 연속 테스트 모드 토글")
        
        continuous_mode = False
        frame_count = 0
        fps_start_time = time.time()
        
        while True:
            ret, frame = cap.read()
            if not ret:
                break
            
            display_frame = frame.copy()
            h, w = frame.shape[:2]
            
            # 가이드라인 그리기
            guide_color = (0, 255, 0)
            cv2.rectangle(display_frame, (w//4, h//6), (3*w//4, 5*h//6), guide_color, 2)
            
            # FPS 계산 및 표시
            frame_count += 1
            if frame_count % 30 == 0:
                fps = 30 / (time.time() - fps_start_time)
                fps_start_time = time.time()
                
            # 연속 모드에서 실시간 테스트
            if continuous_mode and frame_count % 15 == 0:  # 0.5초마다
                try:
                    image_data = self.encode_cv2_image(frame)
                    response = requests.post(
                        f"{self.api_base_url}/api/face/detect_for_registration",
                        json={"image": image_data},
                        headers={"Content-Type": "application/json"},
                        timeout=2
                    )
                    result = response.json()
                    
                    # 결과 표시
                    if result.get('success'):
                        suitable = result.get('suitable_for_registration', False)
                        color = (0, 255, 0) if suitable else (0, 0, 255)
                        status = "적합" if suitable else "부적합"
                        
                        cv2.putText(display_frame, f"등록 {status}", (10, 30),
                                  cv2.FONT_HERSHEY_SIMPLEX, 1, color, 2)
                        
                        if 'quality_details' in result:
                            quality = result['quality_details']
                            cv2.putText(display_frame, f"품질: {quality.get('overall_quality_score', 0):.2f}", 
                                      (10, 60), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (255, 255, 255), 2)
                        
                        if 'face_pose' in result:
                            pose = result['face_pose']
                            cv2.putText(display_frame, f"Yaw: {pose['yaw']:.1f}", 
                                      (10, 90), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
                            cv2.putText(display_frame, f"Pitch: {pose['pitch']:.1f}", 
                                      (10, 110), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
                            cv2.putText(display_frame, f"Roll: {pose['roll']:.1f}", 
                                      (10, 130), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
                        
                except Exception as e:
                    cv2.putText(display_frame, f"Error: {str(e)[:30]}", (10, 30),
                              cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)
            
            # 모드 표시
            if continuous_mode:
                cv2.putText(display_frame, "연속 테스트 모드", (w-200, 30),
                          cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 255, 255), 2)
            
            cv2.imshow('실시간 얼굴 품질 테스트', display_frame)
            
            key = cv2.waitKey(1) & 0xFF
            if key == ord('q'):
                break
            elif key == ord('s'):
                # 현재 프레임 저장 및 테스트
                filename = f"test_images/realtime_{datetime.now().strftime('%Y%m%d_%H%M%S')}.jpg"
                os.makedirs("test_images", exist_ok=True)
                cv2.imwrite(filename, frame)
                print(f"\n✅ 이미지 저장: {filename}")
                
                result = self.test_single_image(filename, "실시간 캡처")
                self.print_test_result(result)
                
            elif key == ord('c'):
                continuous_mode = not continuous_mode
                print(f"\n연속 테스트 모드: {'ON' if continuous_mode else 'OFF'}")
        
        cap.release()
        cv2.destroyAllWindows()
    
    def test_batch_images(self, image_folder: str, max_images: int = 50):
        """폴더 내 모든 이미지 배치 테스트"""
        if not os.path.exists(image_folder):
            print(f"❌ 폴더를 찾을 수 없습니다: {image_folder}")
            return
        
        # 이미지 파일 찾기
        image_files = []
        for ext in ['.jpg', '.jpeg', '.png', '.bmp']:
            image_files.extend([f for f in os.listdir(image_folder) 
                              if f.lower().endswith(ext)])
        
        if not image_files:
            print(f"❌ {image_folder}에 이미지 파일이 없습니다.")
            return
        
        image_files = image_files[:max_images]
        print(f"\n배치 테스트: {len(image_files)}개 이미지")
        
        # 멀티스레드로 병렬 처리
        results = []
        with ThreadPoolExecutor(max_workers=5) as executor:
            future_to_image = {
                executor.submit(self.test_single_image, 
                              os.path.join(image_folder, img), img): img 
                for img in image_files
            }
            
            for future in as_completed(future_to_image):
                image_name = future_to_image[future]
                try:
                    result = future.result()
                    results.append(result)
                    
                    # 진행 상황 표시
                    status = "✅" if result.get('suitable_for_registration', False) else "❌"
                    print(f"{status} {image_name}")
                    
                except Exception as e:
                    print(f"❌ {image_name}: {str(e)}")
        
        # 결과 분석
        self.analyze_batch_results(results)
        return results
    
    def analyze_batch_results(self, results: List[Dict]):
        """배치 테스트 결과 분석"""
        if not results:
            return
        
        print("\n" + "="*60)
        print("배치 테스트 결과 분석")
        print("="*60)
        
        # 기본 통계
        total = len(results)
        successful = sum(1 for r in results if r.get('success', False))
        suitable = sum(1 for r in results if r.get('suitable_for_registration', False))
        
        print(f"총 이미지: {total}개")
        print(f"검출 성공: {successful}개 ({successful/total*100:.1f}%)")
        print(f"등록 적합: {suitable}개 ({suitable/total*100:.1f}%)")
        
        # 품질 점수 통계
        quality_scores = [r.get('quality_score', 0) for r in results if r.get('success')]
        if quality_scores:
            print(f"\n품질 점수:")
            print(f"  - 평균: {np.mean(quality_scores):.2f}")
            print(f"  - 최소: {np.min(quality_scores):.2f}")
            print(f"  - 최대: {np.max(quality_scores):.2f}")
            print(f"  - 표준편차: {np.std(quality_scores):.2f}")
        
        # 처리 시간 통계
        processing_times = [r.get('actual_processing_time_ms', 0) for r in results if r.get('success')]
        if processing_times:
            print(f"\n처리 시간 (ms):")
            print(f"  - 평균: {np.mean(processing_times):.0f}")
            print(f"  - 최소: {np.min(processing_times):.0f}")
            print(f"  - 최대: {np.max(processing_times):.0f}")
        
        # 실패 원인 분석
        print(f"\n주요 권장사항:")
        recommendations = {}
        for r in results:
            if r.get('recommendations'):
                for rec in r['recommendations']:
                    recommendations[rec] = recommendations.get(rec, 0) + 1
        
        for rec, count in sorted(recommendations.items(), key=lambda x: x[1], reverse=True)[:5]:
            print(f"  - {rec}: {count}회")
    
    def print_test_result(self, result: Dict):
        """테스트 결과 출력"""
        print(f"\n{'='*60}")
        print(f"테스트: {result.get('description', 'N/A')}")
        print(f"이미지: {result.get('image_path', 'N/A')}")
        print(f"{'='*60}")
        
        print(f"\n상태 코드: {'200' if result.get('success') else '400'}")
        print(f"성공 여부: {result.get('success', False)}")
        print(f"얼굴 감지: {result.get('face_detected', False)}")
        print(f"등록 적합성: {result.get('suitable_for_registration', False)}")
        
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
        
        # 처리 시간
        if 'actual_processing_time_ms' in result:
            print(f"\n[처리 시간]")
            print(f"  - 전체: {result['actual_processing_time_ms']:.0f}ms")
            print(f"  - API: {result.get('processing_time_ms', 0):.0f}ms")
    
    def benchmark_performance(self, test_image: str, iterations: int = 100):
        """성능 벤치마크 테스트"""
        if not os.path.exists(test_image):
            print(f"❌ 테스트 이미지를 찾을 수 없습니다: {test_image}")
            return
        
        print(f"\n성능 벤치마크 테스트")
        print(f"이미지: {test_image}")
        print(f"반복 횟수: {iterations}")
        print("-" * 60)
        
        # 이미지 미리 인코딩
        image_data = self.encode_image(test_image)
        
        response_times = []
        errors = 0
        
        # 워밍업
        print("워밍업 중...")
        for _ in range(5):
            try:
                requests.post(
                    f"{self.api_base_url}/api/face/detect_for_registration",
                    json={"image": image_data},
                    headers={"Content-Type": "application/json"},
                    timeout=10
                )
            except:
                pass
        
        # 실제 테스트
        print("벤치마크 시작...")
        start_time = time.time()
        
        for i in range(iterations):
            try:
                req_start = time.time()
                response = requests.post(
                    f"{self.api_base_url}/api/face/detect_for_registration",
                    json={"image": image_data},
                    headers={"Content-Type": "application/json"},
                    timeout=10
                )
                req_time = (time.time() - req_start) * 1000  # ms
                
                if response.status_code == 200:
                    response_times.append(req_time)
                else:
                    errors += 1
                
                # 진행 표시
                if (i + 1) % 10 == 0:
                    print(f"  진행: {i + 1}/{iterations} ({(i + 1)/iterations*100:.0f}%)")
                    
            except Exception as e:
                errors += 1
                print(f"  오류 발생: {str(e)}")
        
        total_time = time.time() - start_time
        
        # 결과 분석
        print(f"\n{'='*60}")
        print("벤치마크 결과")
        print(f"{'='*60}")
        
        if response_times:
            print(f"총 시간: {total_time:.2f}초")
            print(f"성공: {len(response_times)}회")
            print(f"실패: {errors}회")
            print(f"처리량: {len(response_times)/total_time:.1f} req/s")
            
            print(f"\n응답 시간 (ms):")
            print(f"  - 평균: {np.mean(response_times):.1f}")
            print(f"  - 중앙값: {np.median(response_times):.1f}")
            print(f"  - 최소: {np.min(response_times):.1f}")
            print(f"  - 최대: {np.max(response_times):.1f}")
            print(f"  - 표준편차: {np.std(response_times):.1f}")
            print(f"  - 95 백분위: {np.percentile(response_times, 95):.1f}")
            print(f"  - 99 백분위: {np.percentile(response_times, 99):.1f}")
            
            # 히스토그램 생성
            plt.figure(figsize=(10, 6))
            plt.hist(response_times, bins=50, alpha=0.7, color='blue', edgecolor='black')
            plt.axvline(np.mean(response_times), color='red', linestyle='dashed', linewidth=2, label=f'평균: {np.mean(response_times):.1f}ms')
            plt.axvline(np.median(response_times), color='green', linestyle='dashed', linewidth=2, label=f'중앙값: {np.median(response_times):.1f}ms')
            plt.xlabel('응답 시간 (ms)')
            plt.ylabel('빈도')
            plt.title('API 응답 시간 분포')
            plt.legend()
            plt.grid(True, alpha=0.3)
            
            # 그래프 저장
            plt.savefig('benchmark_results.png', dpi=300, bbox_inches='tight')
            print(f"\n📊 히스토그램 저장: benchmark_results.png")
            plt.close()

def main():
    parser = argparse.ArgumentParser(description='얼굴 품질 검증 테스트')
    parser.add_argument('--mode', choices=['single', 'batch', 'realtime', 'benchmark'], 
                       default='single', help='테스트 모드')
    parser.add_argument('--image', type=str, help='테스트할 이미지 경로')
    parser.add_argument('--folder', type=str, default='test_images', help='배치 테스트 폴더')
    parser.add_argument('--api-url', type=str, default=API_BASE_URL, help='API 서버 URL')
    parser.add_argument('--iterations', type=int, default=100, help='벤치마크 반복 횟수')
    
    args = parser.parse_args()
    
    # 테스터 초기화
    tester = FaceQualityTester(args.api_url)
    
    print("="*60)
    print("고급 얼굴 품질 검증 테스트")
    print(f"API URL: {args.api_url}")
    if args.api_url == API_BASE_URL and 'config' in globals():
        print(f"Config: HOST={config.HOST}, PORT={config.PORT}")
    print(f"모드: {args.mode}")
    print("="*60)
    
    if args.mode == 'single':
        if args.image:
            result = tester.test_single_image(args.image, "사용자 지정 이미지")
            tester.print_test_result(result)
        else:
            print("❌ --image 옵션으로 이미지 경로를 지정하세요.")
            
    elif args.mode == 'batch':
        tester.test_batch_images(args.folder)
        
    elif args.mode == 'realtime':
        tester.test_realtime_webcam()
        
    elif args.mode == 'benchmark':
        if args.image:
            tester.benchmark_performance(args.image, args.iterations)
        else:
            # 기본 테스트 이미지 사용
            test_images = ['test_images/frontal_face.jpg', 'test.jpg', 'sample.jpg']
            for img in test_images:
                if os.path.exists(img):
                    tester.benchmark_performance(img, args.iterations)
                    break
            else:
                print("❌ 벤치마크할 이미지를 찾을 수 없습니다. --image 옵션을 사용하세요.")

if __name__ == "__main__":
    main()