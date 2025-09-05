# 모바일 앱 개발 가이드

## 가장 빠른 방법: Android Webview 앱

### 1. Android Studio 설치
- https://developer.android.com/studio 에서 다운로드

### 2. 새 프로젝트 생성
- Empty Activity 선택
- Package name: com.spoqplus.app
- Minimum SDK: API 21

### 3. MainActivity.java 코드
```java
package com.spoqplus.app;

import android.os.Bundle;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.webkit.WebSettings;
import android.webkit.WebChromeClient;
import android.webkit.GeolocationPermissions;
import androidx.appcompat.app.AppCompatActivity;
import android.view.KeyEvent;

public class MainActivity extends AppCompatActivity {
    private WebView webView;
    private String webUrl = "https://yourdomain.com/login";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        webView = findViewById(R.id.webView);
        WebSettings webSettings = webView.getSettings();
        
        // JavaScript 활성화
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setDatabaseEnabled(true);
        
        // 모바일 뷰포트 설정
        webSettings.setUseWideViewPort(true);
        webSettings.setLoadWithOverviewMode(true);
        
        // 캐시 설정
        webSettings.setCacheMode(WebSettings.LOAD_DEFAULT);
        
        // 위치 권한
        webSettings.setGeolocationEnabled(true);
        
        // 파일 업로드 허용
        webSettings.setAllowFileAccess(true);
        webSettings.setAllowContentAccess(true);
        
        // WebViewClient 설정
        webView.setWebViewClient(new WebViewClient() {
            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                view.loadUrl(url);
                return true;
            }
        });
        
        // WebChromeClient 설정 (JavaScript 알림 등)
        webView.setWebChromeClient(new WebChromeClient() {
            @Override
            public void onGeolocationPermissionsShowPrompt(String origin, 
                GeolocationPermissions.Callback callback) {
                callback.invoke(origin, true, false);
            }
        });
        
        // URL 로드
        webView.loadUrl(webUrl);
    }

    // 뒤로가기 버튼 처리
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK && webView.canGoBack()) {
            webView.goBack();
            return true;
        }
        return super.onKeyDown(keyCode, event);
    }
}
```

### 4. activity_main.xml 레이아웃
```xml
<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <WebView
        android:id="@+id/webView"
        android:layout_width="match_parent"
        android:layout_height="match_parent" />

</RelativeLayout>
```

### 5. AndroidManifest.xml 권한 추가
```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
<uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
<uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />
<uses-permission android:name="android.permission.CAMERA" />
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />

<!-- application 태그 내부에 추가 -->
<application
    android:usesCleartextTraffic="true"
    ...>
```

### 6. 앱 아이콘 설정
- res/mipmap 폴더에 아이콘 추가
- ic_launcher.png (각 해상도별)

### 7. 앱 이름 변경
- res/values/strings.xml
```xml
<string name="app_name">SpoQ Plus</string>
```

### 8. 빌드 및 배포
- Build → Generate Signed Bundle/APK
- APK 선택
- 키스토어 생성 또는 선택
- Release 빌드

## iOS 앱 (Swift)

### 1. Xcode에서 새 프로젝트
- iOS App 선택
- Interface: Storyboard
- Language: Swift

### 2. ViewController.swift
```swift
import UIKit
import WebKit

class ViewController: UIViewController, WKNavigationDelegate, WKUIDelegate {
    
    var webView: WKWebView!
    
    override func loadView() {
        let webConfiguration = WKWebViewConfiguration()
        webView = WKWebView(frame: .zero, configuration: webConfiguration)
        webView.navigationDelegate = self
        webView.uiDelegate = self
        view = webView
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        let myURL = URL(string: "https://yourdomain.com/login")
        let myRequest = URLRequest(url: myURL!)
        webView.load(myRequest)
    }
    
    // JavaScript 알림 처리
    func webView(_ webView: WKWebView, runJavaScriptAlertPanelWithMessage message: String, 
                 initiatedByFrame frame: WKFrameInfo, completionHandler: @escaping () -> Void) {
        let alert = UIAlertController(title: nil, message: message, preferredStyle: .alert)
        alert.addAction(UIAlertAction(title: "확인", style: .default, handler: { _ in
            completionHandler()
        }))
        self.present(alert, animated: true, completion: nil)
    }
}
```

### 3. Info.plist 권한 추가
```xml
<key>NSAppTransportSecurity</key>
<dict>
    <key>NSAllowsArbitraryLoads</key>
    <true/>
</dict>
<key>NSCameraUsageDescription</key>
<string>카메라 접근이 필요합니다</string>
<key>NSLocationWhenInUseUsageDescription</key>
<string>위치 정보가 필요합니다</string>
```

## 더 전문적인 방법: Capacitor 사용

### 1. Capacitor 설치
```bash
npm install @capacitor/core @capacitor/cli
npx cap init
```

### 2. 플랫폼 추가
```bash
npx cap add android
npx cap add ios
```

### 3. 빌드
```bash
npx cap sync
npx cap open android  # Android Studio 열기
npx cap open ios      # Xcode 열기
```

## 장단점 비교

### Webview 앱
✅ 빠른 개발
✅ 웹과 동일한 UI/UX
✅ 서버 업데이트만으로 앱 업데이트
❌ 네이티브 기능 제한
❌ 성능 제한

### 네이티브 앱 (React Native/Flutter)
✅ 최고의 성능
✅ 모든 네이티브 기능 사용
❌ 개발 시간 오래 걸림
❌ 별도 코드베이스 관리

### 추천
현재 상황에서는 **Webview 앱**이 가장 적합합니다:
- 이미 모바일 웹이 잘 구현됨
- 빠른 앱스토어 배포 가능
- 유지보수 쉬움