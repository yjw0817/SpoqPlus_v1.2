# jsinc.php 클래스명 고유화 업데이트

## 문제점
jsinc.php는 모든 페이지에 포함되는 공용 파일이므로, 여기서 정의한 스타일이 다른 페이지의 요소들에 영향을 미치고 있었습니다.

특히 `.preview_mem_photo` 클래스가 다른 페이지의 회원 사진 표시 요소에 영향을 주고 있었습니다.

## 해결책
회원 등록 모달에서 사용하는 모든 클래스명을 `new-member-` 접두사를 붙여 고유하게 변경했습니다.

## 변경된 클래스명

### CSS 클래스 변경
- `.photo-row` → `.new-member-photo-row`
- `.photo-action` → `.new-member-photo-action`
- `.photo-guide-text` → `.new-member-photo-guide-text`
- `.profile-photo-wrapper` → `.new-member-photo-wrapper`
- `.preview_mem_photo` → `.new-member-preview-photo`

### 영향받던 요소 예시
```html
<!-- 다른 페이지의 회원 사진 (더 이상 영향받지 않음) -->
<img class="preview_mem_photo" id="preview_mem_photo" src="/upload/photo/member_duser01_thum.jpg" 
     alt="회원사진" style="border-radius: 50%; cursor: pointer;">
```

### 회원 등록 모달의 사진 요소 (고유 클래스 사용)
```html
<!-- 회원 등록 모달의 사진 -->
<img class="new-member-preview-photo" id="new_member_photo_preview" 
     src="/dist/img/default_profile.png" alt="회원사진">
```

## 스타일 정의
```css
/* 회원 등록 모달 - 사진 등록 관련 스타일 */
.new-member-photo-row {
    display: flex;
    align-items: flex-end;
    gap: 15px;
}

.new-member-photo-action {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100px;
}

.new-member-photo-guide-text {
    font-size: 13px;
    color: #555;
    margin-bottom: 10px;
    line-height: 1.4;
}

.new-member-photo-wrapper {
    position: relative;
    display: inline-block;
    width: 100px;
    height: 100px;
}

.new-member-preview-photo {
    width: 100px;
    height: 100px;
    object-fit: cover;
    align-content: center;
    text-align: center;
}
```

## 결과
- 회원 등록 모달의 스타일이 다른 페이지에 영향을 주지 않음
- 각 페이지의 회원 사진 표시가 정상적으로 작동
- 명확한 네이밍 규칙으로 유지보수 용이