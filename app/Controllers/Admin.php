<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FaceRecognitionModel;

class Admin extends Controller
{
    protected $faceModel;
    
    public function __construct()
    {
        $this->faceModel = new FaceRecognitionModel();
    }
    
    // ... existing code ...
    
    public function generateTestFloorPlan()
    {
        // Create image
        $width = 1200;
        $height = 800;
        $image = imagecreatetruecolor($width, $height);
        
        // Colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 200, 200, 200);
        
        // Fill background
        imagefill($image, 0, 0, $white);
        
        // Draw room outline
        imagerectangle($image, 50, 50, $width-50, $height-50, $black);
        
        // Draw grid (for locker placement reference)
        for($x = 100; $x < $width-50; $x += 50) {
            imageline($image, $x, 50, $x, $height-50, $gray);
        }
        for($y = 100; $y < $height-50; $y += 50) {
            imageline($image, 50, $y, $width-50, $y, $gray);
        }
        
        // Add entrance
        imagefilledrectangle($image, ($width/2)-30, $height-50, ($width/2)+30, $height-30, $black);
        
        // Add text
        imagestring($image, 5, $width/2-50, $height/2, "Test Floor Plan", $black);
        imagestring($image, 3, $width/2-30, $height-25, "Entrance", $black);
        
        // Save image
        header('Content-Type: image/jpeg');
        imagejpeg($image, FCPATH . 'uploads/floorplans/test_floor_plan.jpg', 90);
        imagedestroy($image);
        
        return redirect()->to('/uploads/floorplans/test_floor_plan.jpg');
    }
    
    /**
     * 데이터베이스 통계 조회 API
     */
    public function getDatabaseStats()
    {
        try {
            // 1. 전체 얼굴 데이터 통계
            $db = \Config\Database::connect();
            $builder = $db->table('member_faces');
            
            $totalStats = $builder->select('
                COUNT(*) as total_faces,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_faces,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_faces
            ')->get()->getRowArray();
            
            // 2. 안경 착용 통계 (활성 데이터만)
            $glassesStats = $builder->select('glasses_detected, COUNT(*) as count')
                ->where('is_active', 1)
                ->groupBy('glasses_detected')
                ->get()->getResultArray();
            
            // 3. 최근 등록된 얼굴 데이터
            $faceData = $builder->select('
                face_id, mem_sno, glasses_detected, quality_score, 
                security_level, liveness_score, is_active, 
                registered_date, last_updated
            ')
                ->orderBy('registered_date', 'DESC')
                ->limit(10)
                ->get()->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'totalStats' => $totalStats,
                'glassesStats' => $glassesStats,
                'faceData' => $faceData,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'getDatabaseStats 오류: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Python 서버 상태 확인 API
     */
    public function getPythonServerStatus()
    {
        try {
            $faceHost = getenv('FACE_HOST') ?: 'localhost';
            $facePort = getenv('FACE_PORT') ?: '5002';
            $python_url = "http://{$faceHost}:{$facePort}/api/face/health";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $python_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($response && $http_code == 200) {
                $data = json_decode($response, true);
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $data,
                    'http_code' => $http_code,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => $error ?: 'Python 서버 연결 실패',
                    'http_code' => $http_code,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'getPythonServerStatus 오류: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * 얼굴 데이터베이스 현황 페이지 (HTML)
     */
    public function databaseStatus()
    {
        $data = [
            'title' => '얼굴 데이터베이스 현황',
            'content' => '얼굴 데이터베이스 현황을 실시간으로 확인할 수 있습니다.'
        ];
        
        // 인라인 HTML 반환
        return '<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 얼굴 데이터베이스 현황 분석</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table { 
            width: 100%;
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            text-align: left; 
            padding: 8px; 
            border: 1px solid #ddd;
        }
        th { 
            background-color: #f0f0f0; 
            font-weight: bold;
        }
        .success { 
            background-color: #e8f5e8; 
            padding: 10px; 
            border-radius: 5px; 
            margin: 10px 0;
        }
        .error { 
            background-color: #ffe8e8; 
            padding: 10px; 
            border-radius: 5px; 
            margin: 10px 0;
        }
        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        .refresh-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .refresh-btn:hover {
            background-color: #0056b3;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔍 얼굴 데이터베이스 현황 분석</h2>
        <button class="refresh-btn" onclick="loadDatabaseStats()">🔄 새로고침</button>
        
        <div id="loading" class="loading">
            <p>📊 데이터 로딩 중...</p>
        </div>
        
        <div id="content" style="display: none;">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>📊 전체 통계</h3>
                    <div id="totalStats"></div>
                </div>
                
                <div class="stat-card">
                    <h3>👓 안경 착용 통계</h3>
                    <div id="glassesStats"></div>
                </div>
                
                <div class="stat-card">
                    <h3>🐍 Python 서버 상태</h3>
                    <div id="pythonStatus"></div>
                </div>
            </div>
            
            <h3>📝 최근 등록된 얼굴 데이터</h3>
            <div id="faceDataTable"></div>
        </div>
    </div>

    <script>
        const baseUrl = window.location.origin;
        
        // 페이지 로드 시 데이터 로드
        window.onload = function() {
            loadDatabaseStats();
        };

        // 10초마다 자동 새로고침
        setInterval(loadDatabaseStats, 10000);

        async function loadDatabaseStats() {
            try {
                document.getElementById("loading").style.display = "block";
                document.getElementById("content").style.display = "none";
                
                // 1. 데이터베이스 통계 조회
                const dbResponse = await fetch(baseUrl + "/admin/getDatabaseStats", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    }
                });
                
                let dbData = null;
                if (dbResponse.ok) {
                    dbData = await dbResponse.json();
                }
                
                // 2. Python 서버 상태 확인
                const pythonResponse = await fetch(baseUrl + "/admin/getPythonServerStatus", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    }
                });
                
                let pythonData = null;
                if (pythonResponse.ok) {
                    pythonData = await pythonResponse.json();
                }
                
                // 결과 표시
                displayResults(dbData, pythonData);
                
            } catch (error) {
                console.error("데이터 로딩 실패:", error);
                displayError("데이터 로딩 중 오류가 발생했습니다: " + error.message);
            }
        }

        function displayResults(dbData, pythonData) {
            console.log("DB 데이터:", dbData);
            console.log("Python 데이터:", pythonData);
            
            const loadingEl = document.getElementById("loading");
            const contentEl = document.getElementById("content");
            
            if (loadingEl) loadingEl.style.display = "none";
            if (contentEl) contentEl.style.display = "block";
            
            // 전체 통계 표시
            const totalStatsEl = document.getElementById("totalStats");
            if (totalStatsEl) {
                if (dbData && dbData.success && dbData.totalStats) {
                    const stats = dbData.totalStats;
                    totalStatsEl.innerHTML = `
                        <ul>
                            <li><strong>총 얼굴 데이터:</strong> ${stats.total_faces || 0}개</li>
                            <li><strong>활성 데이터:</strong> ${stats.active_faces || 0}개</li>
                            <li><strong>비활성 데이터:</strong> ${stats.inactive_faces || 0}개</li>
                        </ul>
                    `;
                } else {
                    totalStatsEl.innerHTML = "<p class=\"error\">데이터베이스 통계를 불러올 수 없습니다.</p>";
                }
            }
            
            // 안경 착용 통계 표시
            const glassesStatsEl = document.getElementById("glassesStats");
            if (glassesStatsEl) {
                if (dbData && dbData.success && dbData.glassesStats && dbData.glassesStats.length > 0) {
                    let glassesHtml = "<ul>";
                    dbData.glassesStats.forEach(stat => {
                        const label = stat.glasses_detected == 1 ? "안경 착용" : "안경 없음";
                        glassesHtml += `<li><strong>${label}:</strong> ${stat.count}명</li>`;
                    });
                    glassesHtml += "</ul>";
                    glassesStatsEl.innerHTML = glassesHtml;
                } else {
                    glassesStatsEl.innerHTML = "<p class=\"error\">안경 통계를 불러올 수 없습니다.</p>";
                }
            }
            
            // Python 서버 상태 표시
            const pythonStatusEl = document.getElementById("pythonStatus");
            if (pythonStatusEl) {
                if (pythonData && pythonData.success && pythonData.data) {
                    // Python 서버 응답 구조 확인
                    let dbStatus = null;
                    if (pythonData.data.data && pythonData.data.data.database_status) {
                        dbStatus = pythonData.data.data.database_status;
                    } else if (pythonData.data.database_status) {
                        dbStatus = pythonData.data.database_status;
                    }
                    
                    if (dbStatus) {
                        pythonStatusEl.innerHTML = `
                            <div class="success">
                                <p><strong>✅ Python 서버 연결 성공!</strong></p>
                                <ul>
                                    <li><strong>일반 얼굴:</strong> ${dbStatus.normal_faces || 0}명</li>
                                    <li><strong>안경 얼굴:</strong> ${dbStatus.glasses_faces || 0}명</li>
                                </ul>
                            </div>
                        `;
                    } else {
                        pythonStatusEl.innerHTML = `
                            <div class="error">
                                <p><strong>❌ Python 서버 응답 구조 오류</strong></p>
                                <p>응답 구조를 확인해주세요.</p>
                            </div>
                        `;
                    }
                } else {
                    pythonStatusEl.innerHTML = `
                        <div class="error">
                            <p><strong>❌ Python 서버 연결 실패</strong></p>
                            <p>서버가 실행 중인지 확인해주세요.</p>
                        </div>
                    `;
                }
            }
            
            // 상세 데이터 테이블 표시
            const faceDataTableEl = document.getElementById("faceDataTable");
            if (faceDataTableEl) {
                if (dbData && dbData.success && dbData.faceData && dbData.faceData.length > 0) {
                    let tableHtml = `
                        <table>
                            <tr>
                                <th>ID</th><th>회원번호</th><th>안경</th><th>품질</th><th>보안</th><th>Liveness</th><th>활성</th><th>등록일</th><th>수정일</th>
                            </tr>
                    `;
                    
                    dbData.faceData.forEach(row => {
                        const glassesIcon = row.glasses_detected == 1 ? "👓" : "👤";
                        const activeStatus = row.is_active == 1 ? "✅" : "❌";
                        const glassesLabel = row.glasses_detected == 1 ? "Yes" : "No";
                        
                        tableHtml += `
                            <tr>
                                <td>${row.face_id}</td>
                                <td>${row.mem_sno}</td>
                                <td>${glassesIcon} ${glassesLabel}</td>
                                <td>${row.quality_score}</td>
                                <td>${row.security_level}</td>
                                <td>${row.liveness_score}</td>
                                <td>${activeStatus}</td>
                                <td>${row.registered_date}</td>
                                <td>${row.last_updated || "N/A"}</td>
                            </tr>
                        `;
                    });
                    
                    tableHtml += "</table>";
                    faceDataTableEl.innerHTML = tableHtml;
                } else {
                    faceDataTableEl.innerHTML = "<p class=\"error\">얼굴 데이터를 불러올 수 없습니다.</p>";
                }
            }
        }

        function displayError(message) {
            const loadingEl = document.getElementById("loading");
            const contentEl = document.getElementById("content");
            
            if (loadingEl) loadingEl.style.display = "none";
            if (contentEl) {
                contentEl.innerHTML = `
                    <div class="error">
                        <h3>❌ 오류 발생</h3>
                        <p>${message}</p>
                    </div>
                `;
                contentEl.style.display = "block";
            }
        }
    </script>
</body>
</html>';
    }
} 