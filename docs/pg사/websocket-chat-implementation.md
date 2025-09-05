# 웹소켓 기반 실시간 채팅 구현 가이드

## 개요
폴링 방식을 웹소켓으로 전환하여 실시간 채팅을 구현하는 방법입니다.

## 서버 측 구현 (PHP)

### 1. Ratchet 라이브러리 설치
```bash
composer require cboden/ratchet
```

### 2. WebSocket 서버 클래스
```php
// src/ChatServer.php
<?php
namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;
    protected $rooms;
    
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
    }
    
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }
    
    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        
        switch($data['action']) {
            case 'join':
                // 채팅방 참가
                $roomId = $data['room_id'];
                $this->rooms[$roomId][] = $from;
                break;
                
            case 'message':
                // 메시지 전송
                $roomId = $data['room_id'];
                $message = [
                    'type' => 'message',
                    'data' => $data['message'],
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                // 같은 방의 모든 사용자에게 전송
                if (isset($this->rooms[$roomId])) {
                    foreach ($this->rooms[$roomId] as $client) {
                        $client->send(json_encode($message));
                    }
                }
                
                // DB에 메시지 저장
                $this->saveMessage($data);
                break;
        }
    }
    
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        
        // 모든 방에서 연결 제거
        foreach ($this->rooms as $roomId => &$clients) {
            $key = array_search($conn, $clients);
            if ($key !== false) {
                unset($clients[$key]);
            }
        }
    }
    
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
    
    private function saveMessage($data) {
        // DB에 메시지 저장 로직
        // 기존 ajax_clas_diary_insert_proc와 동일한 로직
    }
}
```

### 3. WebSocket 서버 실행 스크립트
```php
// bin/websocket-server.php
<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\ChatServer;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080  // WebSocket 포트
);

echo "WebSocket server started on port 8080\n";
$server->run();
```

## 클라이언트 측 구현 (JavaScript)

### teventpt.php 수정
```javascript
var ws = null; // WebSocket 연결

function pt_chk(buy_sno) {
    $('.overlay2').hide();
    $('.overlay').show();
    var h_size = $(window).height();
    var c_size = h_size - 200;
    $('#bottom-menu-area').css("height", h_size + "px");
    $('.direct-chat-messages').css("height", c_size + "px");
    $('.content').addClass('modal-open');
    
    $('#pt_chk_buy_sno').val(buy_sno);
    
    // 기존 WebSocket 연결이 있으면 종료
    if (ws) {
        ws.close();
    }
    
    // 기존 메시지 로드 (AJAX)
    loadInitialMessages(buy_sno);
    
    // WebSocket 연결 시작
    connectWebSocket(buy_sno);
}

function connectWebSocket(buy_sno) {
    // WebSocket 서버 연결
    ws = new WebSocket('ws://localhost:8080');
    
    ws.onopen = function() {
        console.log('WebSocket 연결됨');
        
        // 채팅방 참가
        ws.send(JSON.stringify({
            action: 'join',
            room_id: buy_sno,
            user_type: 'T', // T: 강사, M: 회원
            user_id: '<?php echo $_SESSION['user_id']?>'
        }));
    };
    
    ws.onmessage = function(event) {
        var data = JSON.parse(event.data);
        
        if (data.type === 'message') {
            // 새 메시지 추가
            appendNewMessage(data.data);
        }
    };
    
    ws.onerror = function(error) {
        console.error('WebSocket 에러:', error);
        // 에러 시 폴링으로 폴백
        fallbackToPolling(buy_sno);
    };
    
    ws.onclose = function() {
        console.log('WebSocket 연결 종료');
    };
}

function sendMessage() {
    if (!ws || ws.readyState !== WebSocket.OPEN) {
        alertToast('error', '연결이 끊어졌습니다. 다시 시도해주세요.');
        return;
    }
    
    var message = $('#clas_conts').val();
    if (!message) {
        alertToast('error', '수업 내용을 입력하세요.');
        return;
    }
    
    // WebSocket으로 메시지 전송
    ws.send(JSON.stringify({
        action: 'message',
        room_id: $('#pt_chk_buy_sno').val(),
        message: {
            CLAS_DIARY_CONTS: message,
            MEM_DV: 'T',
            // 기타 필요한 데이터
        }
    }));
    
    $('#clas_conts').val('');
}

function appendNewMessage(messageData) {
    var cmsg = '';
    var r = messageData;
    
    if (r['MEM_DV'] == 'T') {
        // 강사 메시지
        cmsg += "<div class='direct-chat-msg' style='display:flex; margin-bottom:15px; align-items:flex-start; width:100%;'>";
        // ... 메시지 HTML
        cmsg += "</div>";
    } else {
        // 회원 메시지
        cmsg += "<div class='direct-chat-msg right' style='display:flex; margin:0 0 15px 0; padding:0; align-items:flex-start; width:100%; flex-direction:row-reverse;'>";
        // ... 메시지 HTML
        cmsg += "</div>";
    }
    
    $('#clas_msg').append(cmsg);
    
    // 스크롤 처리
    var msgBox = $('#clas_msg');
    if (msgBox.scrollTop() + msgBox.innerHeight() >= msgBox[0].scrollHeight - 50) {
        msgBox.scrollTop(msgBox[0].scrollHeight);
    }
}

// 모달 닫을 때
$("#bottom-menu-close").click(function() {
    $('.content').removeClass('modal-open');
    
    // WebSocket 연결 종료
    if (ws) {
        ws.close();
        ws = null;
    }
});

// 메시지 전송 버튼
$("#btn_clas_comment").click(function() {
    sendMessage();
});
```

## 서버 실행 방법

### 1. 개발 환경
```bash
php bin/websocket-server.php
```

### 2. 프로덕션 환경 (systemd 사용)
```ini
# /etc/systemd/system/websocket.service
[Unit]
Description=WebSocket Chat Server
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/html
ExecStart=/usr/bin/php /var/www/html/bin/websocket-server.php
Restart=on-failure

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable websocket
sudo systemctl start websocket
```

### 3. Nginx 프록시 설정
```nginx
location /websocket {
    proxy_pass http://localhost:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}
```

## 장단점 비교

### 웹소켓 장점
- **실시간성**: 즉각적인 메시지 전달
- **효율성**: 서버 부하 감소
- **양방향 통신**: 서버에서 클라이언트로 푸시 가능
- **확장성**: 타이핑 중 표시, 온라인 상태 등 추가 기능 구현 용이

### 웹소켓 단점
- **인프라 복잡도**: 별도 서버 프로세스 필요
- **방화벽 이슈**: 일부 네트워크에서 차단 가능
- **폴백 필요**: 연결 실패 시 대안 필요

## 하이브리드 접근법

웹소켓 연결 실패 시 폴링으로 폴백:

```javascript
function fallbackToPolling(buy_sno) {
    console.log('WebSocket 연결 실패, 폴링 모드로 전환');
    
    // 기존 폴링 코드 실행
    chatInterval = setInterval(function() {
        loadNewMessages(buy_sno);
    }, 5000);
}
```

## 추가 가능한 기능

1. **타이핑 중 표시**
```javascript
// 타이핑 시작
$('#clas_conts').on('input', function() {
    ws.send(JSON.stringify({
        action: 'typing',
        room_id: buy_sno
    }));
});
```

2. **온라인 상태 표시**
3. **읽음 확인**
4. **파일 전송**
5. **음성/영상 통화**

## 폴링 vs 웹소켓 서버 부하 비교

### 서버 부하 계산

#### 폴링 방식
```
부하 = 사용자 수 × (60초 ÷ 폴링 간격)

예시: 100명이 5초 간격 폴링
- 시간당 요청: 100 × (60 ÷ 5) × 60 = 72,000 요청/시간
- 하루: 1,728,000 요청
```

#### 웹소켓 방식
```
부하 = 연결 수 + 실제 메시지 수

예시: 100명 연결, 평균 10개 메시지/분
- 시간당 요청: 100 (연결) + 6,000 (메시지) = 6,100 요청/시간
- 하루: 146,400 요청
```

### 규모별 권장사항

| 규모 | 동시 접속 | 권장 방식 | 이유 |
|------|-----------|-----------|------|
| 소규모 | ~50명 | 폴링 (5-10초) | 구현 간단, 부하 관리 가능 |
| 중규모 | 50-500명 | 하이브리드/롱폴링 | 부하와 복잡도의 균형 |
| 대규모 | 500명+ | 웹소켓 | 서버 비용 절감 필수 |

### 폴링 최적화 전략

#### 1. 스마트 폴링 (활동 기반)
```javascript
var inactiveTime = 0;
var pollInterval;

// 사용자 활동 감지
$(document).on('mousemove keypress', function() {
    inactiveTime = 0;
});

// 비활성 시간 증가
setInterval(function() {
    inactiveTime++;
}, 1000);

function smartPolling() {
    if (inactiveTime < 60) {
        pollInterval = 5000;  // 활성: 5초
    } else if (inactiveTime < 300) {
        pollInterval = 15000; // 준활성: 15초
    } else {
        pollInterval = 60000; // 비활성: 60초
    }
    
    // 인터벌 재설정
    if (chatInterval) {
        clearInterval(chatInterval);
        chatInterval = setInterval(function() {
            loadNewMessages(buy_sno);
        }, pollInterval);
    }
}
```

#### 2. 적응형 폴링 (메시지 빈도 기반)
```javascript
var recentMessageCount = 0;
var messageTimestamps = [];

function adaptivePolling() {
    // 최근 1분간 메시지 수 계산
    var oneMinuteAgo = Date.now() - 60000;
    messageTimestamps = messageTimestamps.filter(ts => ts > oneMinuteAgo);
    
    if (messageTimestamps.length > 10) {
        pollInterval = 2000;  // 활발한 대화: 2초
    } else if (messageTimestamps.length > 5) {
        pollInterval = 3000;  // 보통 대화: 3초
    } else if (messageTimestamps.length > 0) {
        pollInterval = 5000;  // 느린 대화: 5초
    } else {
        pollInterval = 10000; // 대화 없음: 10초
    }
}
```

#### 3. 백그라운드 탭 최적화
```javascript
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // 탭이 비활성화되면 30초로
        changePollInterval(30000);
    } else {
        // 다시 활성화되면 즉시 확인 후 정상 간격으로
        loadNewMessages(buy_sno);
        changePollInterval(5000);
    }
});

function changePollInterval(newInterval) {
    if (chatInterval) {
        clearInterval(chatInterval);
    }
    chatInterval = setInterval(function() {
        loadNewMessages(buy_sno);
    }, newInterval);
}
```

#### 4. 서버 부하 피드백 기반
```javascript
function loadAwarePolling(response) {
    // 서버가 응답 헤더에 부하 정보 포함
    var serverLoad = response.getResponseHeader('X-Server-Load');
    
    if (serverLoad > 0.8) {
        // 서버 부하 높음: 간격 늘리기
        pollInterval = Math.min(pollInterval * 1.5, 30000);
    } else if (serverLoad < 0.3) {
        // 서버 부하 낮음: 간격 줄이기
        pollInterval = Math.max(pollInterval * 0.8, 2000);
    }
}
```

### 서버 측 최적화

#### 1. 캐싱 전략
```php
// Redis를 사용한 메시지 캐싱
function getCachedMessages($roomId, $lastTime) {
    $cacheKey = "chat:room:{$roomId}:messages";
    $cached = Redis::get($cacheKey);
    
    if ($cached && $cached['last_update'] > $lastTime) {
        return $cached['messages'];
    }
    
    // DB에서 조회
    $messages = $this->getMessagesFromDB($roomId, $lastTime);
    
    // 캐시 업데이트 (10초간 유지)
    Redis::setex($cacheKey, 10, [
        'messages' => $messages,
        'last_update' => time()
    ]);
    
    return $messages;
}
```

#### 2. 연결 풀링
```php
// DB 연결 풀 사용
$config = [
    'pool_size' => 100,
    'idle_time' => 60,
];
```

#### 3. 조기 응답
```php
// 변경사항이 없으면 즉시 응답
if ($lastMessageTime >= $latestMessageTime) {
    header('HTTP/1.1 204 No Content');
    exit;
}
```

### 모니터링 지표

1. **서버 지표**
   - CPU 사용률
   - 메모리 사용량
   - 네트워크 I/O
   - DB 쿼리 시간

2. **애플리케이션 지표**
   - 평균 응답 시간
   - 동시 접속자 수
   - 분당 요청 수
   - 에러율

3. **임계값 설정**
   ```javascript
   // 모니터링 및 알림
   if (avgResponseTime > 1000) {  // 1초 이상
       console.warn('응답 시간 저하');
       increasePollInterval();
   }
   
   if (errorRate > 0.05) {  // 5% 이상
       console.error('높은 에러율');
       fallbackToLongerInterval();
   }
   ```

### 전환 시점 판단

#### 폴링 → 웹소켓 전환 고려 시점
1. **정량적 지표**
   - 동시 접속 50명 초과
   - 서버 CPU 사용률 50% 초과
   - 시간당 요청 10만 건 초과
   - 월 서버 비용 $500 초과

2. **정성적 지표**
   - 사용자 불만 접수
   - 메시지 지연 체감
   - 경쟁 서비스 대비 열위

### 단계별 마이그레이션 전략

#### Phase 1: 현재 (폴링)
- 5초 간격 폴링
- 기본 최적화 적용

#### Phase 2: 최적화 (3-6개월)
- 스마트 폴링 도입
- 캐싱 전략 구현
- 부하 모니터링 시작

#### Phase 3: 하이브리드 (6-12개월)
- 롱 폴링 테스트
- 일부 사용자 웹소켓 테스트
- A/B 테스팅

#### Phase 4: 전환 (12개월+)
- 웹소켓 전면 도입
- 폴링은 폴백용으로만 유지

## 결론

웹소켓은 실시간 채팅에 최적이지만, 서버 인프라 변경이 필요합니다. 
현재 폴링 방식으로 시작하고, 추후 사용자가 늘어나면 웹소켓으로 전환하는 것이 현실적인 접근법입니다.

중요한 것은 처음부터 완벽한 시스템을 만드는 것이 아니라, 현재 상황에 맞는 실용적인 선택을 하고, 성장에 따라 진화시키는 것입니다.