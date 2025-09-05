<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

Class Cors implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		// 환경 변수에서 허용할 도메인 가져오기
		$allowed_origins_env = env('CORS_ALLOWED_ORIGINS', 'http://183.99.33.126:8088,http://localhost:8080');
		$allowed_origins = explode(',', $allowed_origins_env);
		
		// 기본 허용 도메인들
		$default_origins = [
			'http://183.99.33.126:8088',
			'http://localhost:8080',
			'http://localhost:3000'
		];
		
		$allowed_origins = array_merge($default_origins, $allowed_origins);
		$origin = $request->getServer('HTTP_ORIGIN');
		
		if (in_array($origin, $allowed_origins)) {
			header('Access-Control-Allow-Origin: ' . $origin);
		} else {
			// 개발 환경에서만 * 허용
			if (env('CI_ENVIRONMENT') === 'development') {
				header('Access-Control-Allow-Origin: *');
			} else {
				// 운영 환경에서는 특정 도메인만 허용
				header('Access-Control-Allow-Origin: ' . $allowed_origins[0]);
			}
		}
		
		header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400'); // 24시간 캐시
		
		$method = $_SERVER['REQUEST_METHOD'] ?? '';
		if ($method == 'OPTIONS') {
			// Preflight 요청에 대한 응답
			http_response_code(200);
			die();
		}
	}
	
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// 환경 변수에서 허용할 도메인 가져오기
		$allowed_origins_env = env('CORS_ALLOWED_ORIGINS', 'http://183.99.33.126:8088,http://localhost:8080');
		$allowed_origins = explode(',', $allowed_origins_env);
		
		// 기본 허용 도메인들
		$default_origins = [
			'http://183.99.33.126:8088',
			'http://localhost:8080',
			'http://localhost:3000'
		];
		
		$allowed_origins = array_merge($default_origins, $allowed_origins);
		$origin = $request->getServer('HTTP_ORIGIN');
		
		if (in_array($origin, $allowed_origins)) {
			$response->setHeader('Access-Control-Allow-Origin', $origin);
		} else {
			// 개발 환경에서만 * 허용
			if (env('CI_ENVIRONMENT') === 'development') {
				$response->setHeader('Access-Control-Allow-Origin', '*');
			} else {
				// 운영 환경에서는 특정 도메인만 허용
				$response->setHeader('Access-Control-Allow-Origin', $allowed_origins[0]);
			}
		}
		
		$response->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
		$response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
		$response->setHeader('Access-Control-Allow-Credentials', 'true');
		$response->setHeader('Access-Control-Max-Age', '86400'); // 24시간 캐시
		
		return $response;
	}
}