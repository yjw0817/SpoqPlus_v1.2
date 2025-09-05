# 앱 닫기 버튼 통합 가이드

## 개요
자동 로그인 기능으로 인해 로그아웃 버튼 대신 "닫기" 버튼을 구현했습니다.

## 구현된 기능

### 1. 웹 브라우저
- `window.close()` 시도
- 보안상 제한으로 닫히지 않으면 안내 메시지 표시

### 2. Android 앱 (WebView)
Android 앱에서는 다음 코드를 MainActivity에 추가해야 합니다:

```java
// WebView와 JavaScript 인터페이스 연결
webView.addJavascriptInterface(new WebAppInterface(this), "AndroidInterface");

// JavaScript 인터페이스 클래스
public class WebAppInterface {
    Context mContext;
    
    WebAppInterface(Context c) {
        mContext = c;
    }
    
    @JavascriptInterface
    public void closeApp() {
        ((Activity) mContext).finish();
    }
}
```

### 3. iOS 앱 (WKWebView)
iOS 앱에서는 다음 코드를 ViewController에 추가해야 합니다:

```swift
// WKWebView 설정 시
override func viewDidLoad() {
    super.viewDidLoad()
    
    // 메시지 핸들러 추가
    let contentController = webView.configuration.userContentController
    contentController.add(self, name: "closeApp")
}

// 메시지 핸들러 구현
extension ViewController: WKScriptMessageHandler {
    func userContentController(_ userContentController: WKUserContentController, 
                             didReceive message: WKScriptMessage) {
        if message.name == "closeApp" {
            // 앱 종료
            exit(0)
            // 또는 더 부드러운 종료
            // UIApplication.shared.perform(#selector(NSXPCConnection.suspend))
        }
    }
}
```

## 사용자 경험

1. **웹 브라우저**: "닫기" 클릭 → 탭 닫기 시도 → 실패 시 안내 메시지
2. **Android 앱**: "닫기" 클릭 → 앱 종료
3. **iOS 앱**: "닫기" 클릭 → 앱 종료

## 주의사항

- iOS의 경우 `exit(0)` 사용은 Apple의 가이드라인에 어긋날 수 있습니다
- 대안으로 홈 화면으로 이동하는 방식을 고려할 수 있습니다
- 자동 로그인 토큰은 localStorage에 유지되므로 다음 실행 시 자동 로그인됩니다