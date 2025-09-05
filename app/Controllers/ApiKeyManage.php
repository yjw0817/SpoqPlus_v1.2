<?php

namespace App\Controllers;

use App\Models\ApiKeyModel;

class ApiKeyManage extends AdminMainController
{
    private $apiKeyModel;
    
    public function __construct()
    {
        $this->apiKeyModel = new ApiKeyModel();
    }
    
    /**
     * API 키 목록 페이지
     */
    public function index()
    {
        $data = [
            'title' => 'API 키 관리',
            'nav' => ['시스템 관리' => '', 'API 키 관리' => ''],
            'menu1' => 'system',
            'menu2' => 'api_key'
        ];
        
        // API 키 목록 조회
        $data['apiKeys'] = $this->apiKeyModel->getBranchApiKeys($_SESSION['comp_cd']);
        
        return $this->viewPage('api_key/list', ['view' => $data]);
    }
    
    /**
     * API 키 재생성
     */
    public function regenerate()
    {
        $bcoffCd = $this->request->getPost('bcoff_cd');
        
        if (!$bcoffCd) {
            return $this->response->setJSON([
                'success' => false,
                'message' => '지점 코드가 필요합니다.'
            ]);
        }
        
        try {
            $newApiKey = $this->apiKeyModel->regenerateApiKey(
                $_SESSION['comp_cd'],
                $bcoffCd,
                $_SESSION['user_id']
            );
            
            return $this->response->setJSON([
                'success' => true,
                'api_key' => $newApiKey,
                'message' => 'API 키가 재생성되었습니다.'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * API 키 활성화/비활성화
     */
    public function toggle()
    {
        $id = $this->request->getPost('id');
        $isActive = $this->request->getPost('is_active');
        
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID가 필요합니다.'
            ]);
        }
        
        $this->apiKeyModel->toggleApiKey($id, $isActive);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => $isActive ? 'API 키가 활성화되었습니다.' : 'API 키가 비활성화되었습니다.'
        ]);
    }
    
    /**
     * API 키 사용 통계
     */
    public function stats()
    {
        $apiKeyId = $this->request->getGet('id');
        
        if (!$apiKeyId) {
            return redirect()->to('/api_key_manage');
        }
        
        $db = \Config\Database::connect();
        
        // 최근 30일 사용 통계
        $sql = "SELECT 
                    DATE(request_time) as date,
                    COUNT(*) as requests,
                    COUNT(DISTINCT ip_address) as unique_ips
                FROM api_key_logs
                WHERE api_key_id = ?
                AND request_time > DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(request_time)
                ORDER BY date DESC";
                
        $stats = $db->query($sql, [$apiKeyId])->getResultArray();
        
        // 엔드포인트별 통계
        $sql = "SELECT 
                    endpoint,
                    COUNT(*) as count
                FROM api_key_logs
                WHERE api_key_id = ?
                AND request_time > DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY endpoint
                ORDER BY count DESC
                LIMIT 10";
                
        $endpoints = $db->query($sql, [$apiKeyId])->getResultArray();
        
        $data = [
            'title' => 'API 키 사용 통계',
            'nav' => ['시스템 관리' => '', 'API 키 관리' => '/api_key_manage', '사용 통계' => ''],
            'menu1' => 'system',
            'menu2' => 'api_key',
            'stats' => $stats,
            'endpoints' => $endpoints
        ];
        
        return $this->viewPage('api_key/stats', ['view' => $data]);
    }
}