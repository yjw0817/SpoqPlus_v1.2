<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sample</title>
    <style>
        body {
            text-align: center;
        }
        button {
            margin: 10px;
            padding: 15px 30px;
            font-size: 16px;
        }
    </style>
    <script>
        function outLink() {
            // 외부 링크 이동
            var urlInterface = {
                "action" : "moveOutLink",
                "moveURL" : "https://naver.com"
            };
            sendNativeFunction(urlInterface);
        }
        function saveProperty() {
            // 값 저장 1 - 단일 데이터 예시
            var savePropertyInterface1 = {
                "action" : "saveProperty",
                "key" : "test1",
                "value" : "value2"
            };

            // 값 저장 2 - Json 형태
            var saveData = {
                "temp1" : "test1",
                "temp2" : "test2",
                "temp3" : "test3"
            };
            var savePropertyInterface2 = {
                "action" : "saveProperty",
                "key" : "test2",
                "value" : JSON.stringify(saveData)
            };
            sendNativeFunction(savePropertyInterface2);
            alert('데이터 저장 실행 완료');
        }
        function getProperty() {
            var getPropertyInterface1 = {
                "action" : "getProperty",
                "key" : "test2"
            };
            sendNativeFunction(getPropertyInterface1);
        }

        function bioAuth() {
            var bioAuthInterface = {
                "action" : "bioAuth"
            };
            sendNativeFunction(bioAuthInterface);
        }

        function encrypt() {
            var encryptInterface = {
                "action" : "encrypt",
                "text" : "11111111"
            };
            sendNativeFunction(encryptInterface);
        }

        function keypad() {
            //mode : shuffle, normal
            var keypadInterface = {
                "action" : "keypad",
                "isShow" : true,
                "mode" : "normal",
                "maxLength" : 5,
                "title" : "안녕하세요",
                "titleTextColor" : "#000000",
                "subTitle" : "서브메시지입니다.",
                "subTitleColor" : "#000000",
            };
            sendNativeFunction(keypadInterface);

            setTimeout(function(){
                var keypadInterface = {
                    "action" : "keypad",
                    "isShow" : true,
                    "mode" : "shuffle",
                    "maxLength" : 8,
                    "title" : "안녕하세요-2",
                    "titleTextColor" : "#000000",
                    "subTitle" : "서브메시지입니다-2.",
                    "subTitleColor" : "#000000",
                    "pressedColor" : "#E30202",
                    "pressBackgroundColor" : "#BDBCBC",
                };
                sendNativeFunction(keypadInterface);
            },500);

        }
        function nativeCallback(callbackResult) {
            //네이티브 결과값 반환 함수 iOS,Android 동일
            console.log("callback 원문1 = "+callbackResult);
            const resultObject = JSON.parse(callbackResult);
            console.log("callback 원문2 = "+callbackResult);

            // 데이터 Json 형태로 저장 후 사용 방법
            if ( resultObject['action'] === "getProperty"
                && resultObject['result']['key'] === "test2" ) {
                var saveObject = JSON.parse(resultObject['result']['value'])
                alert(saveObject["temp1"])
            }
        }

        var popup;
        function openPopup() {
            // 팝업 옵션 정의
            var popupOptions = 'width=400,height=600,scrollbars=yes,resizable=yes';

            // 새 창(팝업) 열기
            var popup = window.open('https://naver.com', '_blank', popupOptions);

            if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                alert('팝업이 차단되었습니다. 팝업 차단을 해제하세요.');
            } else {
                setTimeout(closePopup, 5000);
            }

            function closePopup() {
                if (popup && !popup.closed) {
                    popup.close();
                    alert('팝업이 자동으로 닫혔습니다.');
                }
            }
        }

        function sendNativeFunction(jsonOBJ) {
            if (isIOSApp()) {
                window.webkit.messageHandlers.baseApp.postMessage(JSON.stringify(jsonOBJ));
            } else if(isAndroidApp()) {
                window.baseApp.run(JSON.stringify(jsonOBJ));
            }
        }

        function isIOSApp() {
          return /iOSApp/.test(navigator.userAgent) && !window.MSStream;
        }

        function isAndroidApp() {
          return /androidApp/.test(navigator.userAgent);
        }
    </script>
</head>
<body>
<button onclick="outLink()">outLink</button>
<button onclick="saveProperty()">데이터 저장</button>
<button onclick="getProperty()">데이터 불러오기</button>
<button onclick="bioAuth()">바이오 인증</button>
<button onclick="encrypt()">암호화</button>
<button onclick="keypad()">키패드</button>
<button onclick="openPopup()">팝업윈도우</button>
<br/><br/>
<div>
    파일 첨부 테스트 <br/>
    <input type="file" />
</div>
</body>
</html>
