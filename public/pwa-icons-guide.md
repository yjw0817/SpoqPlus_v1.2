# PWA 아이콘 생성 가이드

## 필요한 아이콘 크기
PWA를 위해서는 다음 크기의 아이콘이 필요합니다:
- 192x192 픽셀 (필수)
- 512x512 픽셀 (필수)

## 아이콘 생성 방법

### 1. 디자인 툴 사용
- Photoshop, Illustrator, Figma 등을 사용하여 아이콘 디자인
- 정사각형 형태로 제작
- 배경은 투명하게 하거나 단색으로 설정

### 2. 온라인 도구 사용
다음 온라인 도구들을 사용하여 쉽게 생성할 수 있습니다:
- https://www.pwabuilder.com/imageGenerator
- https://realfavicongenerator.net/
- https://favicon.io/

### 3. 간단한 텍스트 기반 아이콘 생성
```html
<!-- 이 HTML을 브라우저에서 열고 스크린샷 찍기 -->
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 512px;
            width: 512px;
            background: linear-gradient(45deg, #0647a9, #8fcbfd);
        }
        .icon {
            font-family: 'Arial Black', sans-serif;
            font-size: 120px;
            color: white;
            text-align: center;
            font-weight: bold;
            text-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="icon">SpoQ<br>Plus</div>
</body>
</html>
```

## 아이콘 저장 위치
생성한 아이콘은 다음 경로에 저장하세요:
- `/public/images/icon-192.png` (192x192 픽셀)
- `/public/images/icon-512.png` (512x512 픽셀)

## 권장 사항
- PNG 형식 사용
- 배경은 투명하거나 앱의 주 색상 사용
- 간단하고 명확한 디자인
- 작은 크기에서도 인식 가능한 디자인

## 테스트
아이콘이 제대로 표시되는지 확인하려면:
1. Chrome 개발자 도구 열기 (F12)
2. Application 탭 > Manifest 확인
3. 아이콘이 올바르게 로드되는지 확인