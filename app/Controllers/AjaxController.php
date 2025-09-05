<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class AjaxController extends Controller
{
    public function loadView()
    {
        // 요청된 URL (href)로부터 컨트롤러와 메서드를 파싱
        $postData = json_decode($this->request->getBody(), true);
        $requestedUrl = $postData['url'] ?? null;
        
        if (empty($requestedUrl)) {
            return $this->response->setStatusCode(400, 'Invalid Request: URL Missing');
        }

        // URL 구조 파싱
        $segments = explode('/', trim($requestedUrl, '/'));
        if (count($segments) < 2) {
            return $this->response->setStatusCode(400, 'Invalid URL Structure');
        }

        // 컨트롤러와 메서드 추출
        $controllerFile = ucfirst($segments[0]); // 첫 번째 세그먼트: 컨트롤러 이름
        $methodName = $segments[1];             // 두 번째 세그먼트: 메서드 이름
        $params = array_slice($segments, 2);    // 나머지는 메서드의 매개변수

        $controllerClass = "App\\Controllers\\{$controllerFile}";

        // 컨트롤러와 메서드 확인
        if (!class_exists($controllerClass)) {
            return $this->response->setStatusCode(404, 'Controller Not Found');
        }

        if (!method_exists($controllerClass, $methodName)) {
            return $this->response->setStatusCode(404, 'Method Not Found');
        }

        try {
            // 컨트롤러 인스턴스 생성
            $controllerInstance = new $controllerClass();

            // 컨트롤러 초기화 (MainController 상속 클래스에서 필요한 초기화)
            if (method_exists($controllerInstance, 'initController')) {
                $controllerInstance->initController(
                    $this->request,
                    $this->response,
                    \Config\Services::logger()
                );
            }

            // 출력 버퍼 시작
            ob_start();
            $controllerInstance->$methodName(...$params); // 메서드 호출
            $output = ob_get_clean(); // 출력된 내용 캡처

            // 출력된 내용 반환
            return $this->response->setBody($output)->setStatusCode(200);
        } catch (\Exception $e) {
            // 예외 처리
            log_message('error', 'AjaxController Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500, 'Internal Server Error');
        }
    }
}