<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class FaceRecognition extends BaseConfig
{
    /**
     * Face Recognition Server Settings
     */
    public $host = 'localhost';
    public $port = 5002;
    
    /**
     * Get the face recognition server URL
     */
    public function getBaseUrl()
    {
        // 환경 변수 우선 사용
        $host = getenv('FACE_HOST') ?: $this->host;
        $port = getenv('FACE_PORT') ?: $this->port;
        
        return "http://{$host}:{$port}";
    }
}