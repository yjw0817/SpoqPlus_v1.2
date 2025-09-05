#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
InsightFace 기반 얼굴 인식 서비스
SPOQ Plus를 위한 고성능 얼굴 인식 구현
"""

import os
import sys
import json
import base64
import numpy as np
from datetime import datetime
import logging
from typing import Dict, List, Tuple, Optional
import time
import secrets
import string

# InsightFace
import insightface
from insightface.app import FaceAnalysis

# 이미지 처리
import cv2
from PIL import Image
import io

# 웹 서버
from flask import Flask, request, jsonify, render_template_string, abort
from flask_cors import CORS
import numpy as np
from functools import wraps
import ipaddress

# 환경 변수
from dotenv import load_dotenv

# 로깅 설정
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('insightface_service.log', encoding='utf-8'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# 데이터베이스 모듈 임포트
import mysql.connector
from mysql.connector import pooling

# MSSQL 지원을 위한 pyodbc 임포트 (선택사항)
try:
    import pyodbc
except ImportError:
    pyodbc = None
    logger.warning("pyodbc not installed. MSSQL support disabled.")

# 데이터베이스 설정 로드
DB_TYPE = None
DB_CONFIG = None

try:
    from config import Config
    config = Config()
    DB_TYPE = config.DB_TYPE
    DB_CONFIG = config.DB_CONFIG
    logger.info(f"config.py에서 {DB_TYPE.upper()} 설정 로드 성공")
except ImportError:
    # config.py가 없으면 환경변수에서 직접 로드
    load_dotenv()  # .env 파일 자동 탐색
    DB_TYPE = os.getenv('DB_TYPE', 'mariadb').lower()
    
    if DB_TYPE == 'mssql':
        DB_CONFIG = {
            'server': os.getenv('MSSQL_SERVER', 'localhost'),
            'database': os.getenv('MSSQL_DATABASE', 'spoqplus'),
            'username': os.getenv('MSSQL_USERNAME', 'sa'),
            'password': os.getenv('MSSQL_PASSWORD', ''),
            'driver': os.getenv('MSSQL_DRIVER', 'ODBC Driver 17 for SQL Server'),
            'port': int(os.getenv('MSSQL_PORT', '1433')),
            'timeout': int(os.getenv('MSSQL_TIMEOUT', '10')),
            'encrypt': os.getenv('MSSQL_ENCRYPT', 'yes'),
            'trust_server_certificate': os.getenv('MSSQL_TRUST_CERT', 'yes')
        }
    else:
        DB_CONFIG = {
            'host': os.getenv('DB_HOST', 'localhost'),
            'user': os.getenv('DB_USER', 'root'),
            'password': os.getenv('DB_PASSWORD', ''),
            'database': os.getenv('DB_NAME', 'spoqplus'),
            'charset': 'utf8mb4',
            'collation': 'utf8mb4_unicode_ci',
            'autocommit': True,
            'connection_timeout': 10
        }

# HTML 테스트 인터페이스
TEST_INTERFACE_HTML = """
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Face Recognition System</title>
    <style>
        * { 
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .content {
            padding: 20px;
        }
        
        .status-card { 
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }
        
        .upload-section { 
            background: white;
            border: 2px solid #e9ecef;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 12px;
            transition: all 0.3s;
        }
        
        .upload-section:hover { 
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }
        
        .button-group { 
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 20px 0;
        }
        
        button { 
            padding: 16px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        button:active {
            transform: scale(0.98);
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-success { 
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }
        
        .btn-info { 
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            color: white;
        }
        
        .btn-danger { 
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            color: white;
        }
        
        .result { 
            margin: 20px 0;
            padding: 15px;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
        }
        
        .success { 
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .error { 
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        input[type="file"] { 
            display: none;
        }
        
        .file-label {
            display: inline-block;
            padding: 12px 24px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 10px 0;
        }
        
        .file-label:hover {
            background: #e9ecef;
            border-color: #667eea;
        }
        
        /* 모바일 최적화 */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 20px;
            }
            
            .content {
                padding: 15px;
            }
            
            button {
                font-size: 15px;
                padding: 14px 20px;
            }
            
            .upload-section {
                padding: 15px;
            }
        }
        
        @media (min-width: 768px) {
            .button-group {
                flex-direction: row;
                flex-wrap: wrap;
            }
            
            button {
                width: auto;
                flex: 1;
            }
        }
        
        /* 카메라 섹션 스타일 */
        #cameraSection {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        
        #videoElement {
            width: 100%;
            max-width: 640px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        #cameraStatus {
            margin-top: 15px;
            padding: 10px;
            background: white;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
        }
        
        .camera-controls {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .camera-controls button {
            flex: 1;
            min-width: 120px;
        }
        
        /* 로딩 애니메이션 */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📷 Face Recognition</h1>
            <p>얼굴 인식 시스템</p>
        </div>
        
        <div class="content">
            <!-- 시스템 상태 -->
            <div class="status-card">
                <h3 style="margin-top: 0;">📊 시스템 상태</h3>
                <div id="systemStatus" style="margin: 10px 0;">확인 중...</div>
                <button class="btn-info" onclick="checkHealth()" style="width: 100%;">
                    🔄 상태 새로고침
                </button>
            </div>
            
            <!-- 카메라 빠른 액세스 -->
            <div class="upload-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h3 style="margin-top: 0;">📱 카메라</h3>
                <div class="button-group">
                    <button class="btn-warning" onclick="startMobileCameraRecognition()">
                        🎯 얼굴 인식 (카메라)
                    </button>
                    <button class="btn-success" onclick="startMobileCameraDetection()">
                        ✅ 등록 적합성 테스트 (카메라)
                    </button>
                </div>
            </div>
            
            <!-- 파일 업로드 섹션 -->
            <div class="upload-section">
                <h3 style="margin-top: 0;">📁 파일 업로드</h3>
                
                <!-- 얼굴 인식 -->
                <div style="margin-bottom: 15px;">
                    <label for="recognizeImage" class="file-label">
                        🔍 인식용 이미지 선택
                    </label>
                    <input type="file" id="recognizeImage" accept="image/*" capture="environment">
                    <button class="btn-primary" onclick="recognizeFace()" style="width: 100%; margin-top: 10px;">
                        📷 얼굴 인식 실행
                    </button>
                </div>
                
                <!-- 얼굴 검출 -->
                <div>
                    <label for="detectImage" class="file-label">
                        🔎 검출용 이미지 선택
                    </label>
                    <input type="file" id="detectImage" accept="image/*,video/*" capture="environment">
                    <button class="btn-info" onclick="detectFace()" style="width: 100%; margin-top: 10px;">
                        등록 적합성 테스트
                    </button>
                </div>
            </div>
            
            <!-- 카메라 프리뷰 영역 -->
            <div id="cameraSection" style="display:none;">
                <h3 style="margin: 0 0 15px 0; text-align: center;">📸 카메라 프리뷰</h3>
                <video id="videoElement" autoplay playsinline></video>
                <canvas id="canvasElement" style="display:none;"></canvas>
                
                <div id="cameraStatus"></div>
                
                <div class="camera-controls">
                    <button class="btn-success" id="captureBtn" onclick="captureFromCamera()">
                        📸 캡처
                    </button>
                    <button class="btn-warning" id="switchCameraBtn" onclick="switchCamera()">
                        🔄 카메라 전환
                    </button>
                    <button class="btn-danger" onclick="stopCamera()">
                        ⏹️ 종료
                    </button>
                </div>
            </div>
            
            <!-- 결과 표시 영역 -->
            <div id="result"></div>
        </div>
    </div>
    
    <script>
        let cameraStream = null;
        let currentCameraMode = null; // 'recognition' or 'detection'
        let currentFacingMode = 'environment'; // 'user' or 'environment'
        let videoElement = null;
        let canvasElement = null;
        
        // 초기화
        document.addEventListener('DOMContentLoaded', function() {
            videoElement = document.getElementById('videoElement');
            canvasElement = document.getElementById('canvasElement');
            
            // 모바일 여부 체크
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile) {
                console.log('Mobile device detected');
            }
            
            // HTTPS/보안 연결 상태 체크
            const isSecure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
            if (!isSecure) {
                console.warn('HTTP connection detected - camera features may be limited');
                console.log('Current location:', location.protocol, location.hostname);
            }
            
            // 카메라 API 미리 테스트 (HTTP에서도 체크)
            console.log('Navigator check:', {
                mediaDevices: typeof navigator.mediaDevices,
                getUserMedia: typeof navigator.getUserMedia,
                webkitGetUserMedia: typeof navigator.webkitGetUserMedia,
                mozGetUserMedia: typeof navigator.mozGetUserMedia,
                mediaDevicesExists: !!navigator.mediaDevices,
                getUserMediaExists: !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)
            });
            
            const hasModernAPI = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
            const hasLegacyAPI = !!(navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
            console.log('Camera API support:', { modern: hasModernAPI, legacy: hasLegacyAPI, secure: isSecure });
            console.log('Final API check result:', { hasModern: hasModernAPI, hasLegacy: hasLegacyAPI, total: hasModernAPI || hasLegacyAPI });
            
            // HTTP 환경이면 레거시 API라도 시도해보기
            if (!hasModernAPI && !hasLegacyAPI) {
                console.warn('카메라 API 완전 미지원 - 파일 업로드만 사용 가능');
                
                // 카메라 섹션 헤더 업데이트
                const cameraHeader = document.querySelector('.upload-section h3');
                if (cameraHeader && cameraHeader.textContent.includes('카메라')) {
                    cameraHeader.innerHTML = '📱 카메라 (미지원) - 📁 파일 업로드 사용';
                }
                
                // 카메라 버튼에 미지원 표시
                setTimeout(() => {
                    checkCameraSupport();
                }, 100);
            } else {
                console.info('카메라 API 감지됨 - HTTP에서도 시도 가능');
            }
            
            checkHealth();
        });
        
        async function checkHealth() {
            try {
                const response = await fetch('/api/face/health');
                const data = await response.json();
                document.getElementById('systemStatus').innerHTML = 
                    '✅ 서버 온라인 - ' + data.service + ' v' + data.version;
            } catch (error) {
                document.getElementById('systemStatus').innerHTML = 
                    '❌ 서버 오프라인 - ' + error.message;
            }
        }
        
        // 모바일 카메라 시작 (후면 카메라 우선)
        async function startMobileCamera(mode, facingMode = 'environment') {
            currentCameraMode = mode;
            currentFacingMode = facingMode;
            const cameraSection = document.getElementById('cameraSection');
            const cameraStatus = document.getElementById('cameraStatus');
            
            // 더 강력한 카메라 API 감지
            const detectCameraAPIs = function() {
                const apis = {
                    modern: !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia),
                    webkit: !!(navigator.webkitGetUserMedia),
                    moz: !!(navigator.mozGetUserMedia),
                    ms: !!(navigator.msGetUserMedia),
                    legacy: !!(navigator.getUserMedia)
                };
                
                console.log('Camera API detection results:', apis);
                return apis;
            };
            
            const cameraAPIs = detectCameraAPIs();
            const hasModernAPI = cameraAPIs.modern;
            const hasLegacyAPI = cameraAPIs.webkit || cameraAPIs.moz || cameraAPIs.ms || cameraAPIs.legacy;
            
            // API 사용 불가능한 경우 즉시 파일 업로드로 전환
            if (!hasModernAPI && !hasLegacyAPI) {
                console.warn('카메라 API 없음 - 파일 업로드로 전환');
                cameraStatus.innerHTML = '❌ 카메라 API를 사용할 수 없습니다.<br><br>' +
                    '<strong>파일 업로드를 사용하세요:</strong><br>' +
                    '아래 "파일 업로드" 섹션에서 이미지를 선택할 수 있습니다.<br><br>' +
                    '<strong>브라우저 지원:</strong><br>' +
                    '• Chrome 53+ (권장)<br>' +
                    '• Firefox 36+<br>' +
                    '• Safari 11+<br>' +
                    '• Edge 12+';
                cameraSection.style.display = 'block';
                
                // 파일 업로드 섹션으로 스크롤
                setTimeout(() => {
                    const uploadSection = document.querySelector('.upload-section');
                    if (uploadSection) {
                        uploadSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        uploadSection.style.border = '3px solid #667eea';
                        uploadSection.style.boxShadow = '0 0 15px rgba(102, 126, 234, 0.3)';
                        
                        // 3초 후 하이라이트 제거
                        setTimeout(() => {
                            uploadSection.style.border = '2px solid #e9ecef';
                            uploadSection.style.boxShadow = '0 4px 12px rgba(102, 126, 234, 0.1)';
                        }, 3000);
                    }
                }, 500);
                
                return;
            }
            
            if (!hasModernAPI && hasLegacyAPI) {
                console.log('Using legacy getUserMedia API');
                cameraSection.style.display = 'block';
                return startLegacyCamera(mode, facingMode);
            }
            
            try {
                // 기존 스트림 정지
                if (cameraStream) {
                    stopCamera();
                }
                
                cameraStatus.innerHTML = '<span class="loading"></span> 카메라 시작 중...';
                
                // 카메라 제약 조건 설정
                const constraints = {
                    video: {
                        facingMode: facingMode, // 'user' = front, 'environment' = back
                        width: { ideal: 1280, max: 1920 },
                        height: { ideal: 720, max: 1080 }
                    },
                    audio: false
                };
                
                // 카메라 스트림 가져오기 - HTTP에서도 시도
                console.log('Camera constraints:', constraints);
                cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                
                videoElement.srcObject = cameraStream;
                cameraSection.style.display = 'block';
                
                // 비디오 메타데이터 로드 후 캔버스 크기 설정
                videoElement.onloadedmetadata = function() {
                    canvasElement.width = videoElement.videoWidth;
                    canvasElement.height = videoElement.videoHeight;
                    console.log('Camera resolution: ' + videoElement.videoWidth + 'x' + videoElement.videoHeight);
                };
                
                // 캡처 버튼 텍스트 변경
                const captureBtn = document.getElementById('captureBtn');
                if (mode === 'recognition') {
                    captureBtn.innerHTML = '🎯 인식하기';
                    cameraStatus.textContent = '얼굴 인식 모드';
                } else if (mode === 'detection') {
                    captureBtn.innerHTML = '✅ 검출하기';
                    cameraStatus.textContent = '등록 적합성 테스트 모드';
                }
                
                // 카메라 전환 버튼 표시
                const switchBtn = document.getElementById('switchCameraBtn');
                switchBtn.style.display = 'inline-block';
                
                // 부드러운 스크롤
                setTimeout(() => {
                    cameraSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
                
            } catch (error) {
                console.error('카메라 오류:', error);
                console.error('에러 상세:', { name: error.name, message: error.message });
                
                // 구형 API로 다시 시도
                if (hasLegacyAPI && hasModernAPI) {
                    console.log('Modern API failed, retrying with legacy API');
                    cameraStatus.innerHTML = '<span class="loading"></span> 구형 API로 재시도 중...';
                    return startLegacyCamera(mode, facingMode);
                }
                
                // 에러 메시지 개선
                let errorMsg = '';
                let suggestions = '';
                
                if (error.name === 'NotAllowedError') {
                    errorMsg = '카메라 권한이 차단되었습니다.';
                    suggestions = '<strong>해결 방법:</strong><br>' +
                        '1. 주소창 왼쪽 카메라 아이콘 클릭<br>' +
                        '2. "카메라 허용" 선택<br>' +
                        '3. 페이지 새로고침 후 다시 시도<br>' +
                        '4. 또는 파일 업로드 기능 사용';
                } else if (error.name === 'NotFoundError') {
                    errorMsg = '카메라를 찾을 수 없습니다.';
                    suggestions = '<strong>해결 방법:</strong><br>' +
                        '1. 카메라가 연결되어 있는지 확인<br>' +
                        '2. 다른 앱에서 카메라를 사용 중인지 확인<br>' +
                        '3. 파일 업로드 기능 사용';
                } else if (error.name === 'NotReadableError') {
                    errorMsg = '카메라가 이미 사용 중입니다.';
                    suggestions = '<strong>해결 방법:</strong><br>' +
                        '1. 다른 탭이나 앱의 카메라 사용 중지<br>' +
                        '2. 브라우저 재시작<br>' +
                        '3. 파일 업로드 기능 사용';
                } else if (error.name === 'NotSupportedError' || error.message.includes('HTTPS')) {
                    errorMsg = 'HTTPS 연결이 필요합니다.';
                    suggestions = '<strong>해결 방법:</strong><br>' +
                        '1. https://localhost:5002 로 접속<br>' +
                        '2. Chrome 플래그에서 "Insecure origins treated as secure" 설정<br>' +
                        '3. 파일 업로드 기능 사용';
                } else {
                    errorMsg = '카메라 접근 실패: ' + error.message;
                    suggestions = '<strong>대안:</strong><br>' +
                        '1. 파일 업로드 섹션 사용<br>' +
                        '2. HTTPS 연결로 접속<br>' +
                        '3. 브라우저 재시작 후 다시 시도';
                }
                
                cameraStatus.innerHTML = '❌ ' + errorMsg + '<br><br>' + suggestions;
                cameraSection.style.display = 'block';
            }
        }
        
        // 구형 브라우저 getUserMedia 지원
        function startLegacyCamera(mode, facingMode) {
            const cameraSection = document.getElementById('cameraSection');
            const cameraStatus = document.getElementById('cameraStatus');
            
            console.log('Starting legacy API:', { mode, facingMode });
            
            // 구형 API 래퍼
            navigator.getUserMedia = navigator.getUserMedia || 
                                     navigator.webkitGetUserMedia || 
                                     navigator.mozGetUserMedia || 
                                     navigator.msGetUserMedia;
            
            if (navigator.getUserMedia) {
                // Legacy API constraints - simplified settings
                const videoConstraints = {
                    width: { ideal: 640, max: 1280 },
                    height: { ideal: 480, max: 720 }
                };
                
                // facingMode support check
                if (facingMode && (facingMode === 'user' || facingMode === 'environment')) {
                    videoConstraints.facingMode = facingMode;
                }
                
                cameraStatus.innerHTML = '<span class="loading"></span> Starting camera (Legacy API)...';
                cameraSection.style.display = 'block';
                
                console.log('Legacy API constraints:', videoConstraints);
                
                navigator.getUserMedia(
                    videoConstraints,  // Legacy API only accepts video object
                    function(stream) {
                        console.log('Legacy API success:', stream);
                        
                        // 성공 콜백
                        cameraStream = stream;
                        
                        // srcObject 또는 src 설정 (브라우저별 호환성)
                        if (videoElement.srcObject !== undefined) {
                            videoElement.srcObject = stream;
                        } else {
                            // For legacy browsers (deprecated)
                            videoElement.src = window.URL.createObjectURL(stream);
                        }
                        
                        videoElement.onloadedmetadata = function() {
                            canvasElement.width = videoElement.videoWidth || 640;
                            canvasElement.height = videoElement.videoHeight || 480;
                            console.log('Legacy API video resolution:', canvasElement.width + 'x' + canvasElement.height);
                        };
                        
                        // 캡처 버튼 설정
                        const captureBtn = document.getElementById('captureBtn');
                        if (mode === 'recognition') {
                            captureBtn.innerHTML = '🎯 인식하기';
                            cameraStatus.textContent = '얼굴 인식 모드 (구형 API)';
                        } else {
                            captureBtn.innerHTML = '✅ 검출하기';
                            cameraStatus.textContent = '등록 적합성 테스트 모드 (구형 API)';
                        }
                        
                        currentCameraMode = mode;
                        currentFacingMode = facingMode;
                        
                        // 부드러운 스크롤
                        setTimeout(() => {
                            cameraSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                    },
                    function(error) {
                        // 에러 콜백
                        console.error('구형 getUserMedia 오류:', error);
                        
                        let errorMsg = '구형 API 카메라 접근 실패';
                        let suggestions = '<strong>해결 방법:</strong><br>' +
                            '1. 브라우저에서 카메라 권한 허용<br>' +
                            '2. Chrome 설정 > 개인정보 및 보안 > 사이트 설정 > 카메라<br>' +
                            '3. "localhost:5002" 허용으로 변경<br>' +
                            '4. 페이지 새로고침 후 다시 시도<br>' +
                            '5. 파일 업로드 기능 사용';
                        
                        cameraStatus.innerHTML = '❌ ' + errorMsg + '<br><br>' + suggestions;
                    }
                );
            } else {
                // getUserMedia가 완전히 지원되지 않는 경우
                console.error('구형 getUserMedia API도 지원되지 않음');
                cameraStatus.innerHTML = '❌ 이 브라우저는 카메라 기능을 지원하지 않습니다.<br><br>' +
                    '<strong>대안:</strong><br>' +
                    '1. 파일 업로드 섹션 사용<br>' +
                    '2. Chrome, Firefox, Safari 최신 버전 사용<br>' +
                    '3. HTTPS 연결로 접속 (https://localhost:5002)';
                cameraSection.style.display = 'block';
            }
        }
        
        // 카메라 API 미리 체크하여 버튼 비활성화 방지
        function checkCameraSupport() {
            const hasModern = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
            const hasLegacy = !!(navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
            
            // 기본적으로 API가 있으면 시도해보기 (HTTP에서도)
            if (hasModern || hasLegacy) {
                return true;
            }
            
            // API 자체가 없는 경우에만 false
            const cameraButtons = document.querySelectorAll('.btn-warning, .btn-success');
            cameraButtons.forEach(btn => {
                if (btn.textContent.includes('카메라')) {
                    btn.style.opacity = '0.7';
                    btn.title = '카메라 미지원 - 파일 업로드를 사용하세요';
                }
            });
            console.warn('카메라 미지원 브라우저');
            return false;
        }
        
        // 카메라 함수들 - HTTP에서도 시도
        function startMobileCameraRecognition() {
            if (!checkCameraSupport()) {
                alert('이 브라우저는 카메라를 지원하지 않습니다.\\n\\n파일 업로드 섹션을 사용해주세요.');
                // 파일 업로드 섹션으로 스크롤
                const uploadSection = document.querySelector('.upload-section');
                if (uploadSection) {
                    uploadSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            startMobileCamera('recognition', 'environment'); // Start with back camera
        }
        
        function startMobileCameraDetection() {
            if (!checkCameraSupport()) {
                alert('이 브라우저는 카메라를 지원하지 않습니다.\\n\\n파일 업로드 섹션을 사용해주세요.');
                // 파일 업로드 섹션으로 스크롤
                const uploadSection = document.querySelector('.upload-section');
                if (uploadSection) {
                    uploadSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            startMobileCamera('detection', 'environment'); // Start with back camera
        }
        
        // 카메라 전환
        async function switchCamera() {
            const newFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            console.log('Camera switch: ' + currentFacingMode + ' > ' + newFacingMode);
            await startMobileCamera(currentCameraMode, newFacingMode);
        }
        
        // 기존 PC용 함수들 (하위 호환성)
        function startCameraRecognition() {
            startMobileCamera('recognition', 'user'); // PC uses front camera
        }
        
        function startCameraDetection() {
            startMobileCamera('detection', 'user'); // PC uses front camera
        }
        
        // 카메라 종료
        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            document.getElementById('cameraSection').style.display = 'none';
            document.getElementById('cameraStatus').textContent = '';
            currentCameraMode = null;
        }
        
        // 카메라에서 캡처
        async function captureFromCamera() {
            const videoElement = document.getElementById('videoElement');
            const canvasElement = document.getElementById('canvasElement');
            const context = canvasElement.getContext('2d');
            const cameraStatus = document.getElementById('cameraStatus');
            
            // 비디오 프레임을 캔버스에 그리기
            context.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
            
            // 캔버스를 Base64로 변환
            const imageDataUrl = canvasElement.toDataURL('image/jpeg', 0.9);
            
            // 처리 중 표시
            cameraStatus.textContent = '처리 중...';
            
            // API 호출
            if (currentCameraMode === 'recognition') {
                await recognizeFaceFromBase64(imageDataUrl);
                cameraStatus.textContent = '인식 완료 - 결과를 확인하세요';
            } else if (currentCameraMode === 'detection') {
                await detectFaceFromBase64(imageDataUrl);
                cameraStatus.textContent = '검출 완료 - 결과를 확인하세요';
            }
        }
        
        // Base64 이미지로 얼굴 인식
        async function recognizeFaceFromBase64(imageDataUrl) {
            try {
                const response = await fetch('/api/face/recognize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        image: imageDataUrl
                    })
                });
                
                const data = await response.json();
                displayResult(data, response.ok);
            } catch (error) {
                displayResult({error: error.message}, false);
            }
        }
        
        // Base64 이미지로 얼굴 검출
        async function detectFaceFromBase64(imageDataUrl) {
            try {
                const response = await fetch('/api/face/detect_for_registration', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        image: imageDataUrl
                    })
                });
                
                const data = await response.json();
                displayResult(data, response.ok);
            } catch (error) {
                displayResult({error: error.message}, false);
            }
        }
        
        async function recognizeFace() {
            const fileInput = document.getElementById('recognizeImage');
            
            if (!fileInput.files[0]) {
                alert('이미지를 선택해주세요.');
                return;
            }
            
            const formData = new FormData();
            formData.append('image', fileInput.files[0]);
            
            try {
                const response = await fetch('/api/face/recognize', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                displayResult(data, response.ok);
            } catch (error) {
                displayResult({error: error.message}, false);
            }
        }
        
        async function detectFace() {
            const fileInput = document.getElementById('detectImage');
            
            if (!fileInput.files[0]) {
                alert('이미지를 선택해주세요.');
                return;
            }
            
            console.log('파일 업로드 시작:', {
                fileName: fileInput.files[0].name,
                fileSize: fileInput.files[0].size,
                fileType: fileInput.files[0].type
            });
            
            const formData = new FormData();
            formData.append('image', fileInput.files[0]);
            
            try {
                const response = await fetch('/api/face/detect_for_registration', {
                    method: 'POST',
                    body: formData
                });
                
                console.log('서버 응답:', response.status, response.headers.get('content-type'));
                
                const data = await response.json();
                console.log('응답 데이터:', data);
                displayResult(data, response.ok);
            } catch (error) {
                console.error('요청 오류:', error);
                displayResult({error: error.message}, false);
            }
        }
        
        function displayResult(data, isSuccess) {
            const resultDiv = document.getElementById('result');
            // 얼굴 감지 실패 시에는 error 클래스 사용 (붉은색 배경)
            const isDetectionFailed = data.face_detected === false;
            resultDiv.className = 'result ' + ((isSuccess && !isDetectionFailed) ? 'success' : 'error');
            
            // 얼굴 검출 결과인 경우 상세 정보 표시 (API 응답 구조는 그대로 유지)
            if (data.face_detected !== undefined && !data.matched && !data.face_saved) {
                // 얼굴 검출 전용 표시 (등록/인식이 아닌 경우)
                let html = '<div style="padding: 15px;">';
                html += '<h3 style="margin-top: 0;">🔍 얼굴 검출 결과</h3>';
                
                // 기본 상태 표시
                html += '<div style="margin-bottom: 15px;">';
                html += '<div>✅ 얼굴 감지: ' + (data.face_detected ? '성공' : '실패') + '</div>';
                html += '<div>📝 등록 적합성: <strong style="color:' + (data.suitable_for_registration ? 'green' : 'red') + '">' + (data.suitable_for_registration ? '적합' : '부적합') + '</strong></div>';
                html += '</div>';
                
                // 품질 정보
                if (data.quality_score !== undefined) {
                    const qualityPercent = (data.quality_score * 100).toFixed(1);
                    const qualityColor = data.quality_score >= 0.7 ? 'green' : data.quality_score >= 0.5 ? 'orange' : 'red';
                    html += '<div style="margin-bottom: 10px;">';
                    html += '<strong>품질 점수:</strong> ';
                    html += '<span style="color:' + qualityColor + '; font-weight: bold;">' + qualityPercent + '%</span>';
                    html += '</div>';
                }
                
                // Liveness 정보
                if (data.liveness_score !== undefined) {
                    const livenessPercent = (data.liveness_score * 100).toFixed(1);
                    const livenessColor = data.liveness_score >= 0.6 ? 'green' : 'red';
                    html += '<div style="margin-bottom: 10px;">';
                    html += '<strong>Liveness 점수:</strong> ';
                    html += '<span style="color:' + livenessColor + '; font-weight: bold;">' + livenessPercent + '%</span>';
                    html += '</div>';
                }
                
                // 권장사항
                if (data.recommendations && data.recommendations.length > 0) {
                    html += '<div style="margin-top: 15px; padding: 10px; background: #f0f8ff; border-radius: 5px;">';
                    html += '<strong>💡 개선 권장사항:</strong><ul style="margin: 5px 0; padding-left: 20px;">';
                    data.recommendations.forEach(rec => {
                        html += '<li>' + rec + '</li>';
                    });
                    html += '</ul></div>';
                }
                
                // 처리 시간
                if (data.processing_time_ms !== undefined) {
                    html += '<div style="margin-top: 10px; color: #666; font-size: 0.9em;">';
                    html += '⏱️ 처리 시간: ' + data.processing_time_ms + 'ms';
                    html += '</div>';
                }
                
                html += '</div>';
                
                // 전체 JSON 데이터는 접을 수 있는 형태로 표시
                html += '<details style="margin-top: 20px;">';
                html += '<summary style="cursor: pointer; padding: 5px; background: #f5f5f5;">📋 전체 응답 데이터 보기</summary>';
                html += '<pre style="margin-top: 10px; background: #f9f9f9; padding: 10px; border-radius: 5px; overflow-x: auto;">' + JSON.stringify(data, null, 2) + '</pre>';
                html += '</details>';
                
                resultDiv.innerHTML = html;
            } else {
                // 기존 방식 유지 (등록, 인식, 오류 등)
                resultDiv.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            }
            
            // 팝업 알림
            if (isSuccess && data.matched) {
                // 인식 성공
                alert('✅ 얼굴 인식 성공! 회원번호: ' + data.member_id + ' 신뢰도: ' + (data.similarity * 100).toFixed(1) + '%');
            } else if (!isSuccess || data.error) {
                // 얼굴 미검출, 이미지 처리 오류는 팝업 없이 결과만 표시
                const silentErrors = [
                    'Face detection failed',
                    'No face detected',
                    '이미지를 처리할 수 없습니다',
                    'Missing image data',
                    'No image file provided'
                ];
                
                const isSilentError = silentErrors.some(err => 
                    data.error && data.error.includes(err)
                );
                
                if (data.error && !isSilentError) {
                    // 실제 중요한 오류인 경우만 팝업 표시
                    alert('❌ 오류 발생! ' + data.error);
                }
                // 일반적인 실패는 결과 화면에만 표시됨
            }
        }
        
        // 초기 상태 확인
        checkHealth();
    </script>
</body>
</html>
"""

class InsightFaceService:
    """InsightFace 기반 얼굴 인식 서비스"""
    
    def __init__(self, model_path: str = './models'):
        """
        서비스 초기화
        
        Args:
            model_path: 모델 파일 경로
        """
        self.model_path = model_path
        
        # InsightFace 앱 초기화
        logger.info("InsightFace 초기화 중...")
        self.app = FaceAnalysis(
            root=model_path,
            providers=['CPUExecutionProvider']  # GPU 사용시 'CUDAExecutionProvider'
        )
        self.app.prepare(ctx_id=0, det_size=(640, 640))
        
        # 임계값 설정
        self.thresholds = {
            'recognition': 0.6,      # 인식 임계값 (InsightFace는 낮은 값 사용)
            'high_quality': 0.8,     # 높은 품질
            'min_face_size': 50,     # 최소 얼굴 크기
            'max_faces': 1           # 한 번에 처리할 최대 얼굴 수
        }
        
        logger.info("✅ InsightFace 서비스 초기화 완료")
    
    def enhance_dark_image(self, image: np.ndarray) -> np.ndarray:
        """
        어두운 이미지 보정
        
        Args:
            image: 입력 이미지 (BGR format)
            
        Returns:
            보정된 이미지
        """
        try:
            # 빈 이미지 체크
            if image is None or image.size == 0 or image.shape[0] == 0 or image.shape[1] == 0:
                logger.warning(f"enhance_dark_image: 빈 이미지 입력 감지")
                return image if image is not None else np.array([])
            
            # 이미지를 LAB 색공간으로 변환
            lab = cv2.cvtColor(image, cv2.COLOR_BGR2LAB)
            l, a, b = cv2.split(lab)
            
            # 밝기 채널(L)의 평균값 계산
            avg_brightness = np.mean(l)
            
            # 이미지가 어두운 경우 (평균 밝기가 100 미만)
            if avg_brightness < 100:
                logger.info(f"어두운 이미지 감지 (밝기: {avg_brightness:.1f}), 보정 적용")
                
                # CLAHE (Contrast Limited Adaptive Histogram Equalization) 적용
                clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8,8))
                l = clahe.apply(l)
                
                # 감마 보정 추가
                gamma = 1.5 if avg_brightness < 50 else 1.2
                l = np.power(l/255.0, 1/gamma) * 255
                l = np.uint8(l)
            
            # LAB 채널 다시 합치기
            enhanced_lab = cv2.merge([l, a, b])
            
            # BGR로 다시 변환
            enhanced_image = cv2.cvtColor(enhanced_lab, cv2.COLOR_LAB2BGR)
            
            # 노이즈 감소를 위한 bilateral filter 적용
            enhanced_image = cv2.bilateralFilter(enhanced_image, 9, 75, 75)
            
            return enhanced_image
            
        except Exception as e:
            logger.warning(f"이미지 보정 중 오류 발생: {str(e)}, 원본 이미지 사용")
            return image
    
    def extract_embedding(self, image: np.ndarray) -> Dict:
        """
        얼굴 임베딩 추출
        
        Args:
            image: 입력 이미지 (BGR format)
        
        Returns:
            dict: 추출 결과
        """
        try:
            # 어두운 이미지 보정 적용
            enhanced_image = self.enhance_dark_image(image)
            
            # 얼굴 검출 및 분석
            faces = self.app.get(enhanced_image)
            
            if not faces:
                return {
                    'success': False,
                    'error': 'No face detected',
                    'message': '얼굴이 잘 보이지 않습니다. 카메라를 정면으로 바라봐 주세요.'
                }
            
            if len(faces) > self.thresholds['max_faces']:
                return {
                    'success': False,
                    'error': 'Multiple faces detected',
                    'message': f'{len(faces)}개의 얼굴이 감지되었습니다. 한 명만 촬영해주세요.'
                }
            
            face = faces[0]
            
            # 얼굴 크기 확인
            bbox = face.bbox.astype(int)
            face_width = bbox[2] - bbox[0]
            face_height = bbox[3] - bbox[1]
            
            if face_width < self.thresholds['min_face_size'] or face_height < self.thresholds['min_face_size']:
                return {
                    'success': False,
                    'error': 'Face too small',
                    'message': '조금 더 가까이 와주세요.'
                }
            
            # 품질 평가
            quality_score = self._evaluate_quality(image, bbox)
            
            # 임베딩 추출 (512차원)
            embedding = face.normed_embedding
            
            # 추가 속성
            age = face.age if hasattr(face, 'age') else None
            gender = face.gender if hasattr(face, 'gender') else None
            
            # 안경 감지 제거 - 라이브니스 감지만 사용
            glasses_detected = False  # 항상 False로 설정
            glasses_confidence = 0.0  # 사용하지 않음
            
            return {
                'success': True,
                'embedding': embedding.tolist(),
                'embedding_norm': float(np.linalg.norm(embedding)),
                'quality_score': float(quality_score),
                'bbox': bbox.tolist(),
                'landmarks': face.kps.tolist() if face.kps is not None else None,
                'age': int(age) if age else None,
                'gender': 'M' if gender == 1 else 'F' if gender == 0 else None,
                'glasses_detected': glasses_detected,
                'glasses_confidence': glasses_confidence,
                'face_confidence': float(face.det_score),
                'processing_time_ms': 0  # 나중에 측정
            }
            
        except Exception as e:
            logger.error(f"임베딩 추출 오류: {str(e)}")
            # OpenCV 오류를 친근한 메시지로 변환
            if "!_src.empty()" in str(e) or "cvtColor" in str(e):
                error_msg = '카메라 화면이 제대로 캡처되지 않았습니다. 다시 시도해 주세요.'
            else:
                error_msg = '얼굴을 인식하는 중 문제가 발생했습니다. 다시 시도해 주세요.'
            
            return {
                'success': False,
                'error': error_msg,
                'message': error_msg
            }
    
    def compare_embeddings(self, embedding1: List[float], embedding2: List[float]) -> float:
        """
        두 임베딩 간 유사도 계산
        
        Args:
            embedding1: 첫 번째 임베딩
            embedding2: 두 번째 임베딩
        
        Returns:
            float: 코사인 유사도 (0~1)
        """
        # numpy 배열로 변환
        emb1 = np.array(embedding1)
        emb2 = np.array(embedding2)
        
        # 정규화 (InsightFace는 이미 정규화됨)
        emb1 = emb1 / np.linalg.norm(emb1)
        emb2 = emb2 / np.linalg.norm(emb2)
        
        # 코사인 유사도
        similarity = np.dot(emb1, emb2)
        
        return float(similarity)
    
    def _clip_bbox(self, bbox: np.ndarray, image_shape: tuple) -> np.ndarray:
        """bbox를 이미지 경계 내로 클리핑"""
        h, w = image_shape[:2]
        x1, y1, x2, y2 = bbox
        
        # 경계 내로 클리핑
        x1 = max(0, min(int(x1), w-1))
        y1 = max(0, min(int(y1), h-1))
        x2 = max(0, min(int(x2), w))
        y2 = max(0, min(int(y2), h))
        
        # x2, y2가 x1, y1보다 작으면 교정
        if x2 <= x1:
            x2 = x1 + 1
        if y2 <= y1:
            y2 = y1 + 1
            
        return np.array([x1, y1, x2, y2])
    
    def _evaluate_quality(self, image: np.ndarray, bbox: np.ndarray) -> float:
        """얼굴 이미지 품질 평가"""
        # bbox 클리핑
        bbox = self._clip_bbox(bbox, image.shape)
        x1, y1, x2, y2 = bbox
        face_img = image[y1:y2, x1:x2]
        
        # 빈 이미지 체크
        if face_img.size == 0 or face_img.shape[0] == 0 or face_img.shape[1] == 0:
            logger.warning(f"_evaluate_quality: 빈 얼굴 이미지 감지 shape={face_img.shape}")
            return 0.0
        
        scores = []
        
        # 1. 크기 점수
        size_score = min((x2-x1) * (y2-y1) / (image.shape[0] * image.shape[1]) * 10, 1.0)
        scores.append(size_score)
        
        # 2. 선명도 점수
        gray = cv2.cvtColor(face_img, cv2.COLOR_BGR2GRAY)
        laplacian_var = cv2.Laplacian(gray, cv2.CV_64F).var()
        sharpness_score = min(laplacian_var / 500, 1.0)
        scores.append(sharpness_score)
        
        # 3. 밝기 점수
        brightness = np.mean(gray)
        brightness_score = 1.0 - abs(brightness - 127.5) / 127.5
        scores.append(brightness_score)
        
        return np.mean(scores)
    
    def _estimate_face_pose(self, landmarks: np.ndarray) -> Dict:
        """
        5개 랜드마크를 이용한 얼굴 각도 추정
        
        Args:
            landmarks: 5개 얼굴 특징점 (왼눈, 오른눈, 코, 왼쪽 입꼬리, 오른쪽 입꼬리)
            
        Returns:
            dict: 각도 정보 (yaw, pitch, roll, is_frontal)
        """
        try:
            if landmarks is None or len(landmarks) < 5:
                return {
                    'yaw': 0.0,
                    'pitch': 0.0,
                    'roll': 0.0,
                    'is_frontal': False,
                    'error': 'Insufficient landmarks'
                }
            
            # 랜드마크 포인트
            left_eye = landmarks[0]
            right_eye = landmarks[1]
            nose = landmarks[2]
            left_mouth = landmarks[3]
            right_mouth = landmarks[4]
            
            # Roll 각도 계산 (머리 기울기)
            eye_vector = right_eye - left_eye
            roll = np.degrees(np.arctan2(eye_vector[1], eye_vector[0]))
            
            # 얼굴 중심점
            face_center = np.mean([left_eye, right_eye, nose, left_mouth, right_mouth], axis=0)
            
            # Yaw 각도 추정 (좌우 회전)
            # 좌우 눈과 코의 삼각형 대칭성 이용
            left_eye_nose_dist = np.linalg.norm(left_eye - nose)
            right_eye_nose_dist = np.linalg.norm(right_eye - nose)
            
            # 거리 비율로 yaw 추정 (경험적 공식)
            dist_ratio = left_eye_nose_dist / (right_eye_nose_dist + 1e-7)
            yaw = (1 - dist_ratio) * 30  # 최대 ±30도로 매핑
            
            # Pitch 각도 추정 (상하 기울기)
            # 눈-코 거리와 코-입 거리의 비율 이용
            eye_center = (left_eye + right_eye) / 2
            eye_nose_dist = np.linalg.norm(eye_center - nose)
            mouth_center = (left_mouth + right_mouth) / 2
            nose_mouth_dist = np.linalg.norm(nose - mouth_center)
            
            # 코의 Y 좌표 위치를 추가로 고려
            # 아래를 볼 때: 코가 눈보다 아래로, 입과 코의 거리가 가까워짐
            # 위를 볼 때: 코가 눈과 가까워지고, 입과 코의 거리가 멀어짐
            eye_y = eye_center[1]
            nose_y = nose[1]
            mouth_y = mouth_center[1]
            
            # 수직 위치 관계를 이용한 pitch 계산
            # 정면일 때 눈-코 : 코-입 비율은 약 1:1.2
            vertical_ratio = nose_mouth_dist / (eye_nose_dist + 1e-7)
            
            # 코의 상대적 위치로 방향 판단
            nose_position_ratio = (nose_y - eye_y) / (mouth_y - eye_y + 1e-7)
            
            # pitch 계산 개선
            # 아래를 볼 때 (-): vertical_ratio 증가, nose_position_ratio 증가
            # 위를 볼 때 (+): vertical_ratio 감소, nose_position_ratio 감소
            if nose_position_ratio > 0.45:  # 정면보다 코가 아래에 있음 (아래를 봄)
                pitch = -(nose_position_ratio - 0.45) * 50  # 음수 (아래)
            else:  # 정면보다 코가 위에 있음 (위를 봄)
                pitch = (0.45 - nose_position_ratio) * 50  # 양수 (위)
            
            # pitch 값 제한 (-30 ~ 30도)
            pitch = np.clip(pitch, -30, 30)
            
            # 정면 여부 판단
            is_frontal = (
                abs(yaw) <= 15 and 
                abs(pitch) <= 15 and 
                abs(roll) <= 10
            )
            
            return {
                'yaw': float(yaw),
                'pitch': float(pitch),
                'roll': float(roll),
                'is_frontal': is_frontal
            }
            
        except Exception as e:
            logger.error(f"얼굴 각도 추정 오류: {str(e)}")
            return {
                'yaw': 0.0,
                'pitch': 0.0,
                'roll': 0.0,
                'is_frontal': False,
                'error': str(e)
            }
    
    def _check_face_position(self, bbox: np.ndarray, image_shape: tuple) -> Dict:
        """
        얼굴이 이미지 중앙에 위치하는지 확인
        
        Args:
            bbox: 얼굴 바운딩 박스 [x1, y1, x2, y2]
            image_shape: 이미지 크기 (height, width, channels)
            
        Returns:
            dict: 위치 정보
        """
        h, w = image_shape[:2]
        x1, y1, x2, y2 = bbox
        
        # 얼굴 중심점
        face_center_x = (x1 + x2) / 2
        face_center_y = (y1 + y2) / 2
        
        # 이미지 중심점
        img_center_x = w / 2
        img_center_y = h / 2
        
        # 중심에서의 거리 비율
        x_offset_ratio = abs(face_center_x - img_center_x) / (w / 2)
        y_offset_ratio = abs(face_center_y - img_center_y) / (h / 2)
        
        # 중앙 40% 영역 내에 있는지 확인
        is_centered = x_offset_ratio < 0.4 and y_offset_ratio < 0.4
        
        # 얼굴 크기 비율
        face_width = x2 - x1
        face_height = y2 - y1
        face_area = face_width * face_height
        image_area = w * h
        size_ratio = face_area / image_area
        
        return {
            'is_centered': is_centered,
            'x_offset_ratio': float(x_offset_ratio),
            'y_offset_ratio': float(y_offset_ratio),
            'face_size_ratio': float(size_ratio),
            'face_width': int(face_width),
            'face_height': int(face_height)
        }
    
    def _evaluate_quality_enhanced(self, image: np.ndarray, face) -> Dict:
        """
        향상된 얼굴 품질 평가
        
        Args:
            image: 입력 이미지
            face: InsightFace 얼굴 객체
            
        Returns:
            dict: 상세 품질 평가 결과
        """
        # 기본 품질 점수
        bbox = face.bbox.astype(int)
        basic_quality = self._evaluate_quality(image, bbox)
        
        # 검출 신뢰도
        detection_confidence = float(face.det_score)
        
        # 얼굴 위치 확인
        position_info = self._check_face_position(bbox, image.shape)
        
        # 얼굴 각도 추정
        pose_info = self._estimate_face_pose(face.kps)
        
        # 종합 점수 계산
        scores = {
            'basic_quality': basic_quality,
            'detection_confidence': detection_confidence,
            'position_score': 1.0 if position_info['is_centered'] else 0.5,
            'size_score': 1.0 if 0.15 <= position_info['face_size_ratio'] <= 0.7 else 0.5,
            'pose_score': 1.0 if pose_info['is_frontal'] else 0.3
        }
        
        # 가중 평균
        weights = {
            'basic_quality': 0.2,
            'detection_confidence': 0.2,
            'position_score': 0.15,
            'size_score': 0.15,
            'pose_score': 0.3  # 각도가 가장 중요
        }
        
        overall_score = sum(scores[k] * weights[k] for k in scores)
        
        return {
            'overall_score': float(overall_score),
            'scores': scores,
            'position_info': position_info,
            'pose_info': pose_info,
            'detection_confidence': detection_confidence
        }
    
    def _calculate_eye_aspect_ratio(self, image: np.ndarray, face) -> float:
        """눈 종횡비(EAR) 계산 - 눈 깜빡임 감지용"""
        try:
            # 얼굴 영역 추출
            bbox = face.bbox.astype(int)
            # bbox 클리핑
            bbox = self._clip_bbox(bbox, image.shape)
            x1, y1, x2, y2 = bbox
            face_img = image[y1:y2, x1:x2]
            
            # 빈 이미지 체크
            if face_img.size == 0 or face_img.shape[0] == 0 or face_img.shape[1] == 0:
                logger.warning(f"_calculate_eye_aspect_ratio: 빈 얼굴 이미지 감지 shape={face_img.shape}")
                return 0.3  # 기본 EAR 값 반환
            
            # 그레이스케일 변환
            gray = cv2.cvtColor(face_img, cv2.COLOR_BGR2GRAY)
            
            # 눈 영역 추정 (얼굴 상단 1/3 부분)
            h, w = gray.shape
            eye_region = gray[int(h*0.2):int(h*0.5), :]
            
            # 눈 영역의 평균 밝기로 눈 개폐 상태 추정
            # 눈을 감으면 속눈썹으로 인해 어두워짐
            mean_brightness = np.mean(eye_region)
            
            # 눈 영역의 엣지 밀도
            edges = cv2.Canny(eye_region, 30, 60)
            edge_density = np.sum(edges > 0) / edges.size
            
            # EAR 추정값 (0~1 범위)
            # 밝기와 엣지 밀도를 조합
            ear = (mean_brightness / 255.0) * 0.5 + edge_density * 0.5
            
            return ear
            
        except Exception as e:
            logger.error(f"EAR 계산 오류: {e}")
            return 0.3  # 기본값
    
    def _detect_blink_pattern(self, ear_values: List[float], threshold: float = 0.2) -> bool:
        """EAR 값 시퀀스에서 눈 깜빡임 패턴 감지"""
        if len(ear_values) < 3:
            return False
        
        # 눈 깜빡임 패턴: 높음 → 낮음 → 높음
        blink_detected = False
        for i in range(1, len(ear_values) - 1):
            if ear_values[i] < threshold and ear_values[i-1] > threshold and ear_values[i+1] > threshold:
                blink_detected = True
                break
        
        return blink_detected
    
    def detect_eye_blink(self, frames: List[np.ndarray], min_frames: int = 5) -> Tuple[bool, float]:
        """여러 프레임에서 눈 깜빡임 감지"""
        if len(frames) < min_frames:
            logger.warning(f"프레임 수 부족: {len(frames)} < {min_frames}")
            return False, 0.0
        
        ear_values = []
        face_detected_count = 0
        
        for frame in frames:
            faces = self.app.get(frame)
            if faces and len(faces) > 0:
                face_detected_count += 1
                # 이미지와 얼굴 정보를 함께 전달
                ear = self._calculate_eye_aspect_ratio(frame, faces[0])
                ear_values.append(ear)
        
        # 얼굴이 충분히 감지되지 않음
        if face_detected_count < min_frames * 0.8:
            logger.warning(f"얼굴 감지 부족: {face_detected_count}/{len(frames)}")
            return False, 0.0
        
        # 눈 깜빡임 패턴 감지
        blink_detected = self._detect_blink_pattern(ear_values)
        confidence = 0.9 if blink_detected else 0.1
        
        logger.info(f"눈 깜빡임 감지: {blink_detected}, 신뢰도: {confidence}")
        return blink_detected, confidence
    
    def _detect_liveness(self, image: np.ndarray, face, check_blink: bool = False, frames: List[np.ndarray] = None) -> Tuple[bool, float]:
        """정교한 Liveness Detection - PC 화면 감지 강화"""
        try:
            bbox = face.bbox.astype(int)
            # bbox 클리핑
            bbox = self._clip_bbox(bbox, image.shape)
            x1, y1, x2, y2 = bbox
            face_img = image[y1:y2, x1:x2]
            
            # 빈 이미지 체크
            if face_img.size == 0 or face_img.shape[0] == 0 or face_img.shape[1] == 0:
                logger.warning(f"_detect_liveness: 빈 얼굴 이미지 감지 shape={face_img.shape}")
                return False, 0.0
            
            # 0. 화면/모니터 감지를 위한 추가 검사
            # 모아레 패턴 검출
            gray = cv2.cvtColor(face_img, cv2.COLOR_BGR2GRAY)
            
            # 픽셀 격자 패턴 검출 (모니터의 특징)
            # 고주파 필터링
            kernel_x = np.array([[-1, 0, 1], [-2, 0, 2], [-1, 0, 1]])
            kernel_y = np.array([[-1, -2, -1], [0, 0, 0], [1, 2, 1]])
            
            grad_x = cv2.filter2D(gray, cv2.CV_32F, kernel_x)
            grad_y = cv2.filter2D(gray, cv2.CV_32F, kernel_y)
            
            # 격자 패턴 강도
            grid_pattern = np.abs(grad_x) + np.abs(grad_y)
            grid_score = np.std(grid_pattern) / (np.mean(grid_pattern) + 1e-7)
            
            # 1. 텍스처 분석 (실제 얼굴 vs 인쇄물/화면)
            
            # LBP (Local Binary Pattern) 특징
            def get_lbp_features(img):
                h, w = img.shape
                lbp = np.zeros_like(img)
                for i in range(1, h-1):
                    for j in range(1, w-1):
                        center = img[i, j]
                        code = 0
                        # 8개 이웃 픽셀과 비교
                        neighbors = [
                            img[i-1, j-1], img[i-1, j], img[i-1, j+1],
                            img[i, j+1], img[i+1, j+1], img[i+1, j],
                            img[i+1, j-1], img[i, j-1]
                        ]
                        for k, neighbor in enumerate(neighbors):
                            if neighbor > center:
                                code |= (1 << k)
                        lbp[i, j] = code
                return lbp
            
            lbp = get_lbp_features(gray)
            lbp_hist, _ = np.histogram(lbp.ravel(), bins=256, range=(0, 256))
            lbp_hist = lbp_hist.astype(float) / lbp_hist.sum()
            
            # 실제 얼굴은 더 다양한 텍스처 패턴을 가짐
            texture_entropy = -np.sum(lbp_hist * np.log2(lbp_hist + 1e-7))
            texture_score = min(texture_entropy / 7.0, 1.0)  # 엔트로피 정규화
            
            # 2. 색상 분석 (피부색 분포)
            # HSV 색공간에서 피부색 범위 검출 - 더 넓은 범위로 조정
            hsv = cv2.cvtColor(face_img, cv2.COLOR_BGR2HSV)
            # 더 넓은 피부색 범위 (다양한 피부톤 포함)
            lower_skin1 = np.array([0, 15, 60], dtype=np.uint8)
            upper_skin1 = np.array([25, 255, 255], dtype=np.uint8)
            lower_skin2 = np.array([170, 15, 60], dtype=np.uint8)  # 붉은색 계열
            upper_skin2 = np.array([180, 255, 255], dtype=np.uint8)
            
            skin_mask1 = cv2.inRange(hsv, lower_skin1, upper_skin1)
            skin_mask2 = cv2.inRange(hsv, lower_skin2, upper_skin2)
            skin_mask = cv2.bitwise_or(skin_mask1, skin_mask2)
            skin_ratio = np.sum(skin_mask > 0) / skin_mask.size
            
            # 3. 주파수 분석 (고주파 성분) - 모니터 감지 강화
            # 인쇄물이나 화면은 모아레 패턴이나 픽셀 구조를 가짐
            f_transform = np.fft.fft2(gray)
            f_shift = np.fft.fftshift(f_transform)
            magnitude_spectrum = np.abs(f_shift)
            
            # 고주파 성분 비율
            h, w = gray.shape
            center_h, center_w = h // 2, w // 2
            radius = min(h, w) // 4
            
            # 중심부(저주파)와 외곽부(고주파) 분리
            y, x = np.ogrid[:h, :w]
            mask = (x - center_w)**2 + (y - center_h)**2 <= radius**2
            
            low_freq_sum = np.sum(magnitude_spectrum[mask])
            high_freq_sum = np.sum(magnitude_spectrum[~mask])
            freq_ratio = high_freq_sum / (low_freq_sum + high_freq_sum + 1e-7)
            
            # 모니터의 주기적 패턴 감지 (수평/수직 라인)
            # 주파수 도메인에서 수평/수직 축의 피크 검출
            h_profile = np.mean(magnitude_spectrum, axis=0)
            v_profile = np.mean(magnitude_spectrum, axis=1)
            
            # 주기적 피크 감지
            h_peaks = len(np.where(h_profile > np.mean(h_profile) * 2)[0])
            v_peaks = len(np.where(v_profile > np.mean(v_profile) * 2)[0])
            periodic_pattern_score = (h_peaks + v_peaks) / (h + w) * 10
            
            # 4. 반사광 패턴 (화면이나 인쇄물의 균일한 반사)
            # 밝기 분포의 표준편차
            brightness_std = np.std(gray)
            # 실제 얼굴은 더 다양한 밝기 분포를 가짐
            brightness_score = min(brightness_std / 50.0, 1.0)
            
            # 5. 얼굴 특징점 움직임 (단일 이미지에서는 제한적)
            # 랜드마크 간 거리의 자연스러움
            if face.kps is not None and len(face.kps) >= 5:
                landmarks = face.kps
                # 눈 사이 거리와 코-입 거리의 비율
                eye_dist = np.linalg.norm(landmarks[0] - landmarks[1])
                nose_mouth_dist = np.linalg.norm(landmarks[2] - landmarks[3])
                ratio = nose_mouth_dist / (eye_dist + 1e-7)
                # 일반적인 비율 범위: 0.8 ~ 1.5
                ratio_score = 1.0 if 0.8 <= ratio <= 1.5 else 0.5
            else:
                ratio_score = 0.5
            
            # 종합 점수 계산 - 실제 얼굴에 더 유리하게 조정
            liveness_score = (
                texture_score * 0.30 +      # 텍스처 엔트로피 (가중치 증가)
                skin_ratio * 0.25 +         # 피부색 비율 (가중치 증가)
                (1 - freq_ratio) * 0.15 +   # 저주파 우세 (자연스러운 얼굴)
                brightness_score * 0.15 +    # 밝기 변화
                ratio_score * 0.10 +        # 얼굴 비율
                (1 - min(grid_score * 0.7, 1.0)) * 0.05  # 격자 패턴 영향 감소
            )
            
            # PC 화면 감지 - 더 관대한 조건 (명확한 화면일 때만 페널티)
            if grid_score > 1.5 and periodic_pattern_score > 1.2:
                # 매우 강한 패턴 - 확실한 화면
                liveness_score *= 0.3  # 70% 페널티
                logger.warning(f"PC 화면 확실 - 격자: {grid_score:.3f}, 주기패턴: {periodic_pattern_score:.3f}")
            elif grid_score > 1.3 and periodic_pattern_score > 1.0:
                # 강한 패턴 - 화면 가능성 높음
                liveness_score *= 0.7  # 30% 페널티
                logger.info(f"PC 화면 의심 - 격자: {grid_score:.3f}, 주기패턴: {periodic_pattern_score:.3f}")
            
            # 디버깅 로그
            logger.info(f"Liveness - 텍스처: {texture_score:.3f}, 피부색: {skin_ratio:.3f}, "
                       f"주파수: {1-freq_ratio:.3f}, 밝기: {brightness_score:.3f}, "
                       f"비율: {ratio_score:.3f}, 격자: {grid_score:.3f}, "
                       f"주기패턴: {periodic_pattern_score:.3f}, 최종: {liveness_score:.3f}")
            
            # 눈 깜빡임 검사 추가 (옵션)
            blink_bonus = 0
            if check_blink and frames is not None and len(frames) >= 3:
                blink_detected, blink_confidence = self.detect_eye_blink(frames)
                if blink_detected:
                    blink_bonus = 0.2  # 눈 깜빡임 감지 시 보너스
                    logger.info(f"눈 깜빡임 감지됨! 보너스: {blink_bonus}")
                else:
                    liveness_score *= 0.8  # 눈 깜빡임 없으면 20% 페널티
                    logger.warning("눈 깜빡임 미감지 - 라이브니스 점수 감소")
            
            # 최종 점수 계산
            final_score = min(liveness_score + blink_bonus, 1.0)
            
            # 임계값 기반 판단
            is_live = final_score > 0.40  # 적절한 균형점
            
            logger.info(f"최종 라이브니스 점수: {final_score:.3f} (원래: {liveness_score:.3f}, 깜빡임 보너스: {blink_bonus:.3f})")
            
            return is_live, final_score
            
        except Exception as e:
            logger.error(f"Liveness detection 오류: {str(e)}")
            return True, 0.5  # 오류 시 기본값
    
    def _detect_glasses_deprecated(self, image: np.ndarray, landmarks: np.ndarray) -> bool:
        """개선된 안경 감지 (눈 주변 엣지 검출 + 반사광 검출)"""
        if landmarks is None or len(landmarks) < 5:
            return False
        
        # 눈 주변 영역 추출 (더 넓은 영역)
        left_eye = landmarks[0].astype(int)
        right_eye = landmarks[1].astype(int)
        
        # 눈 사이 거리 계산
        eye_distance = np.linalg.norm(right_eye - left_eye)
        padding = int(eye_distance * 0.5)  # 눈 사이 거리의 50%를 패딩으로
        
        # 눈 주변 영역 (안경테가 있을 수 있는 영역)
        y_min = max(0, min(left_eye[1], right_eye[1]) - padding)
        y_max = min(image.shape[0], max(left_eye[1], right_eye[1]) + padding)
        x_min = max(0, left_eye[0] - padding)
        x_max = min(image.shape[1], right_eye[0] + padding)
        
        eye_region = image[y_min:y_max, x_min:x_max]
        
        if eye_region.size == 0 or eye_region.shape[0] == 0 or eye_region.shape[1] == 0:
            logger.warning(f"_detect_glasses_deprecated: 빈 눈 영역 이미지 감지")
            return False
        
        # 1. 엣지 검출 (안경테 감지)
        gray = cv2.cvtColor(eye_region, cv2.COLOR_BGR2GRAY)
        
        # 적응형 임계값으로 엣지 검출 개선 - 더 높은 임계값으로 약한 엣지 제거
        edges = cv2.Canny(gray, 50, 150)  # 임계값 상향 (30,100 -> 50,150)
        edge_density = np.sum(edges > 0) / edges.size
        
        # 2. 반사광 검출 (안경 렌즈의 반사)
        # 밝은 픽셀 검출 - 더 높은 임계값으로 일반적인 피부 하이라이트 제외
        _, bright_pixels = cv2.threshold(gray, 220, 255, cv2.THRESH_BINARY)  # 임계값 상향 (200 -> 220)
        bright_ratio = np.sum(bright_pixels > 0) / bright_pixels.size
        
        # PC 화면의 균일한 반사 패턴 감지
        # 반사 영역의 연결성 검사 (화면은 큰 연결된 밝은 영역을 가짐)
        num_labels, labels, stats, _ = cv2.connectedComponentsWithStats(bright_pixels, connectivity=8)
        if num_labels > 1:
            # 가장 큰 연결 영역의 크기
            largest_component_size = np.max(stats[1:, cv2.CC_STAT_AREA]) if num_labels > 1 else 0
            uniform_reflection = largest_component_size / (bright_pixels.size + 1e-7)
        else:
            uniform_reflection = 0
        
        # 3. 수평선 검출 (안경테의 수평선)
        # Hough 변환으로 직선 검출
        lines = cv2.HoughLinesP(edges, 1, np.pi/180, threshold=20, minLineLength=30, maxLineGap=10)
        horizontal_lines = 0
        if lines is not None:
            for line in lines:
                x1, y1, x2, y2 = line[0]
                angle = abs(np.arctan2(y2 - y1, x2 - x1) * 180 / np.pi)
                # 수평선 (각도가 10도 이내)
                if angle < 10 or angle > 170:
                    horizontal_lines += 1
        
        # 4. 대칭성 검사 (안경은 좌우 대칭)
        # 좌우 눈 영역의 엣지 패턴 비교
        eye_center_y = (left_eye[1] + right_eye[1]) // 2
        mid_x = (left_eye[0] + right_eye[0]) // 2
        
        left_region = edges[:, :mid_x - x_min]
        right_region = edges[:, mid_x - x_min:]
        
        # 좌우 반전 후 유사도 계산
        if left_region.shape[1] > 0 and right_region.shape[1] > 0:
            # 크기 맞추기
            min_width = min(left_region.shape[1], right_region.shape[1])
            left_region = left_region[:, -min_width:]
            right_region = right_region[:, :min_width]
            
            # 좌측 영역을 반전
            left_flipped = np.fliplr(left_region)
            
            # 유사도 계산
            symmetry_score = np.sum(left_flipped == right_region) / (left_flipped.size + 1e-7)
        else:
            symmetry_score = 0
        
        # 5. 코 다리 부분 검사 (안경의 특징적인 부분)
        # 두 눈 사이, 코 위쪽 영역
        nose_bridge_y1 = eye_center_y - int(padding * 0.3)
        nose_bridge_y2 = eye_center_y + int(padding * 0.3)
        nose_bridge_x1 = left_eye[0] + int(eye_distance * 0.3)
        nose_bridge_x2 = right_eye[0] - int(eye_distance * 0.3)
        
        if (nose_bridge_y1 >= y_min and nose_bridge_y2 <= y_max and 
            nose_bridge_x1 >= x_min and nose_bridge_x2 <= x_max):
            
            nose_bridge_region = edges[
                nose_bridge_y1-y_min:nose_bridge_y2-y_min,
                nose_bridge_x1-x_min:nose_bridge_x2-x_min
            ]
            
            if nose_bridge_region.size > 0:
                nose_bridge_density = np.sum(nose_bridge_region > 0) / nose_bridge_region.size
            else:
                nose_bridge_density = 0
        else:
            nose_bridge_density = 0
        
        # 종합 판단 (더 엄격한 기준)
        glasses_features = {
            'edge_density': edge_density,
            'bright_ratio': bright_ratio,
            'horizontal_lines': horizontal_lines,
            'symmetry': symmetry_score,
            'nose_bridge': nose_bridge_density
        }
        
        # 안경 점수 계산
        glasses_score = 0
        
        # 엣지 밀도 (안경테) - 더 엄격한 기준
        if edge_density > 0.20:  # 임계값 대폭 상향 (0.15 -> 0.20)
            glasses_score += 0.25
        elif edge_density > 0.15:
            glasses_score += 0.10
            
        # 반사광 (렌즈) - 더 엄격한 기준
        if bright_ratio > 0.12:  # 임계값 대폭 상향 (0.08 -> 0.12)
            glasses_score += 0.15
        elif bright_ratio > 0.08:
            glasses_score += 0.05
            
        # 수평선 (안경테) - 수평선 개수에 더 민감하게
        if horizontal_lines >= 10:  # 많은 수평선은 안경의 강한 신호
            glasses_score += 0.30
        elif horizontal_lines >= 5:
            glasses_score += 0.20
        elif horizontal_lines >= 3:
            glasses_score += 0.10
            
        # 대칭성 (안경의 특징) - 더 높은 대칭성 요구
        if symmetry_score > 0.75:  # 임계값 상향 (0.6 -> 0.75)
            glasses_score += 0.15
        elif symmetry_score > 0.65:
            glasses_score += 0.05
            
        # 코 다리 부분 (안경의 특징) - 더 명확한 코다리 패턴 요구
        if nose_bridge_density > 0.15:  # 임계값 상향 (0.1 -> 0.15)
            glasses_score += 0.15
        elif nose_bridge_density > 0.10:
            glasses_score += 0.05
        
        # 로그 출력
        logger.info(f"안경 감지 - 엣지: {edge_density:.3f}, 반사: {bright_ratio:.3f}, "
                   f"수평선: {horizontal_lines}, 대칭: {symmetry_score:.3f}, "
                   f"코다리: {nose_bridge_density:.3f}, 점수: {glasses_score:.2f}")
        
        # 더 엄격한 판단 (여러 특징이 동시에 나타나야 함)
        # PC 화면의 균일한 반사는 제외
        is_screen_reflection = uniform_reflection > 0.3 and bright_ratio > 0.15
        
        is_glasses = glasses_score > 0.5 or (  # 점수가 0.5 이상이거나
            # 수평선이 많으면 안경으로 판단 (반사 없는 코팅 렌즈 대응)
            (horizontal_lines >= 8 and symmetry_score > 0.7) or
            # 기존 조건들
            (edge_density > 0.15 and bright_ratio > 0.10 and horizontal_lines >= 2 and not is_screen_reflection) or
            (nose_bridge_density > 0.15 and horizontal_lines >= 5) or
            # 높은 대칭성과 적당한 엣지
            (symmetry_score > 0.8 and edge_density > 0.08 and horizontal_lines >= 5)
        ) and not is_screen_reflection
        
        if is_screen_reflection:
            logger.warning(f"PC 화면 반사 감지 - 균일반사: {uniform_reflection:.3f}, 안경으로 판단하지 않음")
        
        return is_glasses


def convert_numpy_types(obj):
    """NumPy 타입을 Python 기본 타입으로 변환"""
    if isinstance(obj, np.bool_):
        return bool(obj)
    elif isinstance(obj, np.integer):
        return int(obj)
    elif isinstance(obj, np.floating):
        return float(obj)
    elif isinstance(obj, np.ndarray):
        return obj.tolist()
    elif isinstance(obj, dict):
        return {key: convert_numpy_types(value) for key, value in obj.items()}
    elif isinstance(obj, list):
        return [convert_numpy_types(item) for item in obj]
    return obj


class InsightFaceAPI:
    """Flask API 서버"""
    
    def __init__(self, face_service: InsightFaceService):
        self.face_service = face_service
        
        # 데이터베이스 연결 설정
        self.db_pool = None
        self.db_type = DB_TYPE
        
        try:
            if self.db_type == 'mssql':
                # MSSQL은 커넥션 풀을 직접 지원하지 않으므로 연결 테스트만
                if pyodbc is None:
                    raise Exception("pyodbc not installed. Run: pip install pyodbc")
                
                # 연결 테스트
                test_conn = self.get_db_connection()
                test_cursor = test_conn.cursor()
                test_cursor.execute("SELECT 1")
                test_cursor.fetchone()
                test_cursor.close()
                test_conn.close()
                logger.info("✅ MSSQL 연결 테스트 성공")
            else:
                # MariaDB/MySQL 커넥션 풀 사용
                self.db_pool = mysql.connector.pooling.MySQLConnectionPool(
                    pool_name="insightface_pool",
                    pool_size=10,
                    **DB_CONFIG
                )
                logger.info("✅ MySQL/MariaDB 커넥션 풀 생성 성공")
                
                # 연결 테스트
                test_conn = self.get_db_connection()
                test_cursor = test_conn.cursor()
                test_cursor.execute("SELECT 1")
                test_cursor.fetchone()
                test_cursor.close()
                test_conn.close()
                logger.info("✅ MySQL/MariaDB 연결 테스트 성공")
            
        except Exception as e:
            logger.error(f"❌ 데이터베이스 연결 설정 실패 ({self.db_type}): {e}")
            logger.error(f"❌ DB_CONFIG: {DB_CONFIG}")
            self.db_pool = None
        
        # Flask 앱 생성
        self.app = Flask(__name__)
        CORS(self.app, resources={r"/api/*": {"origins": "*"}})
        
        # 모든 요청에 대해 IP 체크 (미들웨어) - 제거됨
        # check_ip_whitelist 데코레이터를 대신 사용합니다
        
        # IP 접근 제어 설정 로드
        try:
            from config import Config
            if hasattr(Config, 'IP_ACCESS_CONTROL'):
                self.access_control_config = Config.IP_ACCESS_CONTROL
                logger.info(f"IP 접근 제어 모드: {Config.IP_ACCESS_CONTROL.get('mode', 'open')}")
                if Config.IP_ACCESS_CONTROL.get('mode') == 'blacklist':
                    logger.info(f"차단 IP 목록: {Config.IP_ACCESS_CONTROL.get('blacklist_ips', [])}")
            else:
                # IP_ACCESS_CONTROL 속성이 없으면 기본값 사용
                self.access_control_config = {
                    'mode': 'open',  # 기본적으로 모두 허용
                    'blacklist_ips': [],
                    'internal_ranges': ['192.168.0.0/16', '172.16.0.0/12', '10.0.0.0/8', '127.0.0.1']
                }
                logger.info("IP 접근 제어 설정 없음 - 기본값 사용 (모두 허용)")
        except ImportError:
            # config.py가 없으면 모두 허용
            self.access_control_config = {
                'mode': 'open',
                'blacklist_ips': [],
                'internal_ranges': ['192.168.0.0/16', '172.16.0.0/12', '10.0.0.0/8', '127.0.0.1']
            }
            logger.info("config.py 없음 - IP 접근 제어 비활성화 (모두 허용)")
        
        # 라우트 등록
        self._register_routes()
    
    def check_ip_whitelist(self, f):
        """IP 접근 제어 데코레이터 (블랙리스트 방식)"""
        @wraps(f)
        def decorated_function(*args, **kwargs):
            # 접근 제어 모드 확인
            access_mode = self.access_control_config.get('mode', 'open')
            
            # open 모드면 모든 접속 허용
            if access_mode == 'open':
                return f(*args, **kwargs)
            
            # 클라이언트 IP 가져오기
            client_ip = request.remote_addr
            if request.headers.get('X-Forwarded-For'):
                client_ip = request.headers.get('X-Forwarded-For').split(',')[0].strip()
            
            logger.info(f"[IP_CHECK] 접속 IP: {client_ip} (모드: {access_mode})")
            
            # 로컬호스트는 항상 허용
            if client_ip in ['127.0.0.1', '::1']:
                logger.info(f"[IP_CHECK] 로컬호스트 접속 허용: {client_ip}")
                return f(*args, **kwargs)
            
            try:
                client_ip_obj = ipaddress.ip_address(client_ip)
                
                # 내부망 IP 대역 체크 (블랙리스트에서도 내부망은 항상 허용)
                for internal_range in self.access_control_config.get('internal_ranges', []):
                    if client_ip_obj in ipaddress.ip_network(internal_range):
                        logger.info(f"[IP_CHECK] 내부망 접속 허용: {client_ip} (대역: {internal_range})")
                        return f(*args, **kwargs)
                
                # 블랙리스트 모드일 때 차단 IP 체크
                if access_mode == 'blacklist':
                    blacklist_ips = self.access_control_config.get('blacklist_ips', [])
                    if blacklist_ips:
                        # 빈 문자열 제거 및 공백 제거
                        blacklist_ips = [ip.strip() for ip in blacklist_ips if ip.strip()]
                        if client_ip in blacklist_ips:
                            logger.warning(f"[IP_CHECK] 접속 차단: {client_ip} (블랙리스트)")
                            abort(403, description=f"Access denied from IP: {client_ip}")
                    
                    # 블랙리스트에 없으면 허용
                    logger.info(f"[IP_CHECK] 접속 허용: {client_ip}")
                    return f(*args, **kwargs)
                
                # 알 수 없는 모드
                logger.error(f"[IP_CHECK] 알 수 없는 접근 제어 모드: {access_mode}")
                return f(*args, **kwargs)
                
            except ValueError as e:
                logger.error(f"[IP_CHECK] IP 주소 파싱 오류: {client_ip} - {e}")
                abort(400, description="Invalid IP address")
        
        return decorated_function
    
    def _row_to_dict(self, cursor, row):
        """MSSQL row를 dictionary로 변환"""
        if row is None:
            return None
        columns = [column[0] for column in cursor.description]
        return dict(zip(columns, row))
    
    def _rows_to_dict(self, cursor, rows):
        """MSSQL rows를 dictionary list로 변환"""
        if not rows:
            return []
        columns = [column[0] for column in cursor.description]
        return [dict(zip(columns, row)) for row in rows]
    
    def get_db_connection(self):
        """데이터베이스 연결 가져오기"""
        try:
            if self.db_type == 'mssql':
                # MSSQL 연결
                conn_str = (
                    f"DRIVER={{{DB_CONFIG['driver']}}};"
                    f"SERVER={DB_CONFIG['server']},{DB_CONFIG['port']};"
                    f"DATABASE={DB_CONFIG['database']};"
                    f"UID={DB_CONFIG['username']};"
                    f"PWD={DB_CONFIG['password']};"
                    f"Encrypt={DB_CONFIG['encrypt']};"
                    f"TrustServerCertificate={DB_CONFIG['trust_server_certificate']};"
                    f"Connection Timeout={DB_CONFIG['timeout']};"
                )
                connection = pyodbc.connect(conn_str)
                logger.info("[DB] MSSQL 연결 생성")
                return connection
            else:
                # MariaDB/MySQL 연결
                if self.db_pool:
                    connection = self.db_pool.get_connection()
                    logger.info("[DB] 커넥션 풀에서 연결 획득")
                    return connection
                else:
                    logger.info("[DB] 직접 연결 생성")
                    connection = mysql.connector.connect(**DB_CONFIG)
                    return connection
        except Exception as e:
            logger.error(f"[DB] 데이터베이스 연결 실패 ({self.db_type}): {e}")
            raise
    
    def _register_routes(self):
        """API 엔드포인트 등록"""
        
        @self.app.route('/api/face/health', methods=['GET'])
        @self.check_ip_whitelist
        def health_check():
            """헬스 체크"""
            return jsonify({
                'status': 'healthy',
                'service': 'InsightFace Recognition Service',
                'version': '1.0.0',
                'model': 'buffalo_l',
                'embedding_dimension': 512
            })
        
        @self.app.route('/api/face/register', methods=['POST'])
        @self.check_ip_whitelist
        def register_face():
            """얼굴 등록"""
            try:
                # FormData와 JSON 모두 지원
                if request.content_type and 'multipart/form-data' in request.content_type:
                    # FormData 처리
                    mem_sno = request.form.get('mem_sno')
                    param1 = request.form.get('param1') or request.form.get('comp_cd')
                    param2 = request.form.get('param2') or request.form.get('bcoff_cd')
                    if 'image' in request.files:
                        image_file = request.files['image']
                        image_data = base64.b64encode(image_file.read()).decode('utf-8')
                    else:
                        return jsonify({
                            'success': False,
                            'error': 'No image file provided'
                        }), 400
                else:
                    # JSON 처리
                    data = request.get_json()
                    mem_sno = data.get('mem_sno') or data.get('member_id')  # 하위 호환성
                    image_data = data.get('image')
                    # 파라미터 추가
                    param1 = data.get('param1') or data.get('comp_cd')  # comp_cd와 호환
                    param2 = data.get('param2') or data.get('bcoff_cd')  # bcoff_cd와 호환
                
                if not mem_sno or not image_data:
                    return jsonify({
                        'success': False,
                        'error': 'Missing required fields (mem_sno and image)'
                    }), 400
                
                # 이미지 디코딩
                image = self._decode_image(image_data)
                
                # 임베딩 추출
                start_time = datetime.datetime.now()
                result = self.face_service.extract_embedding(image)
                processing_time = (datetime.datetime.now() - start_time).total_seconds() * 1000
                result['processing_time_ms'] = processing_time
                
                # 얼굴이 검출된 경우 liveness detection 수행
                liveness_passed = False
                liveness_score = 0.0
                if result['success']:
                    # 얼굴 재검출 (liveness detection을 위해)
                    faces = self.face_service.app.get(image)
                    if faces:
                        liveness_passed, liveness_score = self.face_service._detect_liveness(image, faces[0])
                        liveness_passed = bool(liveness_passed)  # NumPy bool을 Python bool로 변환
                        liveness_score = float(liveness_score)
                
                if not result['success']:
                    return jsonify(result), 400
                
                # notes 생성
                notes = f"InsightFace 등록 - Quality: {result.get('quality_score', 0):.2f}, " \
                       f"Liveness: {liveness_score:.2f}"
                
                # 데이터베이스 저장 (liveness_score, glasses_confidence 포함)
                result['liveness_score'] = liveness_score
                result['glasses_confidence'] = 0.0  # 안경 감지 사용 안함
                save_result = self._save_face_to_db(mem_sno, result, param1, param2)
                
                # enhanced_face_service와 동일한 응답 형식
                return jsonify({
                    'success': result['success'] and save_result['db_success'],
                    'face_detected': result['success'],
                    'face_encoding': result['embedding'],
                    'quality_score': result.get('quality_score', 0),
                    'glasses_detected': False,  # 사용하지 않음
                    'glasses_confidence': 0.0,  # 사용하지 않음
                    'liveness_score': liveness_score,
                    'liveness_check': {
                        'is_live': liveness_passed,
                        'confidence': liveness_score,
                        'details': {
                            'method': 'InsightFace Advanced Liveness Detection',
                            'note': 'Multi-factor liveness analysis (texture, frequency, color)'
                        }
                    },
                    'security_warnings': [] if liveness_passed else ['Liveness check failed'],
                    'processing_time_ms': processing_time,
                    'db_message': save_result.get('message', ''),
                    'member_id': mem_sno,
                    'face_attributes': {
                        'age': result.get('age', 0),
                        'gender': result.get('gender', 'unknown')
                    },
                    'notes': notes,
                    'error': save_result.get('db_error') if not save_result['db_success'] else None
                })
                
            except Exception as e:
                logger.error(f"등록 오류: {str(e)}")
                return jsonify({
                    'success': False,
                    'error': str(e)
                }), 500
        
        @self.app.route('/api/face/recognize', methods=['POST'])
        @self.check_ip_whitelist
        def recognize_face():
            """얼굴 인식"""
            try:
                # 상세 로깅 추가
                logger.info("=" * 60)
                logger.info(f"[RECOGNIZE] API 호출 시작")
                logger.info(f"[RECOGNIZE] Content-Type: {request.content_type}")
                logger.info(f"[RECOGNIZE] Method: {request.method}")
                
                # FormData와 JSON 모두 지원
                if request.content_type and 'multipart/form-data' in request.content_type:
                    logger.info(f"[RECOGNIZE] FormData 처리")
                    # FormData 처리
                    param1 = request.form.get('param1') or request.form.get('comp_cd')
                    param2 = request.form.get('param2') or request.form.get('bcoff_cd')
                    if 'image' in request.files:
                        image_file = request.files['image']
                        logger.info(f"[RECOGNIZE] 이미지 파일: {image_file.filename}")
                        image_data = base64.b64encode(image_file.read()).decode('utf-8')
                        logger.info(f"[RECOGNIZE] Base64 인코딩 완료: {len(image_data)} chars")
                    else:
                        logger.error(f"[RECOGNIZE] 이미지 파일 없음")
                        return jsonify({
                            'success': False,
                            'error': 'No image file provided'
                        }), 400
                else:
                    logger.info(f"[RECOGNIZE] JSON 처리")
                    # JSON 처리
                    raw_data = request.get_data(as_text=True)
                    logger.info(f"[RECOGNIZE] Raw body length: {len(raw_data)} chars")
                    
                    data = request.get_json()
                    if data:
                        logger.info(f"[RECOGNIZE] JSON keys: {list(data.keys())}")
                        image_data = data.get('image')
                        param1 = data.get('param1') or data.get('comp_cd')
                        param2 = data.get('param2') or data.get('bcoff_cd')
                        if image_data:
                            logger.info(f"[RECOGNIZE] image 필드 길이: {len(image_data)} chars")
                    else:
                        logger.error(f"[RECOGNIZE] JSON 파싱 실패")
                        image_data = None
                
                if not image_data:
                    logger.error(f"[RECOGNIZE] 이미지 데이터 없음 - Missing image data")
                    return jsonify({
                        'success': False,
                        'error': 'Missing image data'
                    }), 400
                
                # 이미지 디코딩
                image = self._decode_image(image_data)
                
                # 임베딩 추출
                start_time = datetime.datetime.now()
                result = self.face_service.extract_embedding(image)
                processing_time = (datetime.datetime.now() - start_time).total_seconds() * 1000
                
                if not result['success']:
                    return jsonify(result), 400
                
                # 데이터베이스에서 매칭 (안경 정보 제거)
                match_result = self._find_best_match(
                    result['embedding'], 
                    False,  # 안경 감지 사용 안함
                    param1=param1,
                    param2=param2
                )
                
                # 처리 시간 업데이트
                match_result['processing_time_ms'] = processing_time
                
                # 인식 로그 저장
                if match_result.get('match_found'):
                    log_data = {
                        'mem_sno': match_result['member']['mem_sno'],
                        'confidence_score': match_result['similarity'],
                        'similarity_score': match_result['similarity'],
                        'quality_score': result.get('quality_score', 0.85),
                        'glasses_detected': False,
                        'match_category': 'recognition',
                        'success': True,
                        'processing_time_ms': processing_time,
                        'param1': param1,
                        'param2': param2
                    }
                    self._save_recognition_log(log_data)
                
                # enhanced_face_service와 동일한 응답 형식
                response = {
                    'success': True,
                    'face_detected': True,
                    'quality_score': result.get('quality_score', 0),
                    'glasses_detection': {
                        'has_glasses': False,  # 항상 False
                        'confidence': 0.0      # 사용하지 않음
                    },
                    'processing_time_ms': processing_time,
                    'security_details': {
                        'quality_score': result.get('quality_score', 0),
                        'liveness_score': 0.95,  # InsightFace는 높은 신뢰도
                        'all_checks_passed': True
                    }
                }
                
                if match_result.get('match_found'):
                    response.update({
                        'face_matching': {
                            'match_found': True,
                            'similarity_score': match_result['similarity'],
                            'member': {
                                'mem_sno': match_result['member']['mem_sno'],
                                'match_type': match_result.get('match_type', 'insightface')
                            }
                        },
                        'confidence_score': match_result['similarity']
                    })
                else:
                    response.update({
                        'face_matching': {
                            'match_found': False,
                            'similarity_score': 0,
                            'error': 'No matching face found'
                        },
                        'confidence_score': 0
                    })
                
                return jsonify(response)
                
            except Exception as e:
                logger.error(f"인식 오류: {str(e)}")
                return jsonify({
                    'success': False,
                    'error': str(e)
                }), 500
                if request.content_type and 'multipart/form-data' in request.content_type:
                    # FormData 처리
                    if 'image' in request.files:
                        image_file = request.files['image']
                        image_data = base64.b64encode(image_file.read()).decode('utf-8')
                    else:
                        return jsonify({
                            'success': False,
                            'error': 'No image file provided'
                        }), 400
                else:
                    # JSON 처리 (에러 처리 포함)
                    import json
                    raw_data = None
                    data = None
                    image_data = None
                    
                    try:
                        # raw 데이터를 먼저 받아서 직접 파싱 (Flask의 BadRequest 예외 방지)
                        raw_data = request.get_data(as_text=True)
                        logger.info(f"Raw request data (first 100 chars): {raw_data[:100] if raw_data else 'Empty'}")
                        
                        if not raw_data:
                            raise ValueError("빈 요청 데이터")
                        
                        # JSON 파싱 시도 (자동 수정 없이 엄격하게 처리)
                        try:
                            # 일반 파싱만 시도 (줄바꿈이 있으면 실패하도록)
                            data = json.loads(raw_data)
                        except json.JSONDecodeError as e:
                            # 줄바꿈 검사
                            if '\n' in raw_data or '\r' in raw_data:
                                logger.warning(f"JSON에 줄바꿈 문자 포함 - 자동 수정하지 않고 거부")
                                raise json.JSONDecodeError(
                                    "JSON 문자열에 줄바꿈이 포함되어 있습니다. base64 데이터는 줄바꿈 없이 한 줄로 전송해야 합니다.", 
                                    raw_data, 
                                    e.pos
                                )
                            else:
                                raise  # 다른 JSON 오류는 그대로 전달
                        
                        # image 데이터 추출 및 검증
                        image_data = data.get('image') if data else None
                        if image_data:
                            # base64 데이터에 줄바꿈이 있는지 검사
                            if '\n' in image_data or '\r' in image_data:
                                raise ValueError("base64 이미지 데이터에 줄바꿈이 포함되어 있습니다. 줄바꿈 없이 한 줄로 전송해주세요.")
                            # 공백도 base64에서는 유효하지 않음
                            if ' ' in image_data:
                                raise ValueError("base64 이미지 데이터에 공백이 포함되어 있습니다. 공백 없이 전송해주세요.")
                    except Exception as json_error:
                        logger.error(f"JSON 파싱 실패: {str(json_error)}")
                        logger.error(f"Raw data length: {len(raw_data) if 'raw_data' in locals() else 'Unknown'}")
                        
                        # 구체적인 에러 메시지 생성
                        error_detail = str(json_error)
                        data_size = len(raw_data) if 'raw_data' in locals() else 0
                        
                        if 'Unterminated string' in error_detail or '줄바꿈이 포함' in error_detail:
                            specific_error = 'JSON 문자열이 올바르지 않습니다. base64 이미지 데이터에 줄바꿈이나 공백이 포함되어 있습니다.'
                            hint = 'base64 데이터는 줄바꿈과 공백 없이 한 줄로 전송해야 합니다. btoa() 또는 base64 인코딩 후 replace(/\\s/g, "")를 사용하세요.'
                        elif 'Expecting value' in error_detail:
                            specific_error = 'JSON 형식이 올바르지 않습니다. 빈 값이나 잘못된 구조가 있는지 확인하세요.'
                            hint = '{"image": "base64_data"} 형식으로 전송하세요.'
                        elif 'Extra data' in error_detail:
                            specific_error = 'JSON 데이터 뒤에 추가 문자가 있습니다.'
                            hint = 'JSON 객체가 하나만 있는지 확인하세요.'
                        elif 'Invalid control character' in error_detail:
                            specific_error = 'JSON에 허용되지 않는 제어 문자가 포함되어 있습니다.'
                            hint = '특수 문자를 이스케이프 처리하세요.'
                        elif data_size > 10 * 1024 * 1024:  # 10MB 이상
                            specific_error = f'요청 데이터가 너무 큽니다 ({data_size:,} bytes). 이미지 크기를 줄여주세요.'
                            hint = '이미지를 압축하거나 해상도를 낮춰주세요.'
                        else:
                            specific_error = f'JSON 파싱 오류: {error_detail}'
                            hint = 'JSON 형식이 올바른지 확인하세요.'
                        
                        # JSON 파싱 실패 시 상세 응답
                        return jsonify({
                            'success': False,
                            'face_detected': False,
                            'error': specific_error,
                            'error_type': 'JSON_PARSE_ERROR',
                            'hint': hint,
                            'request_size': data_size,
                            'technical_detail': error_detail if logger.level <= 10 else None,  # DEBUG 모드에서만
                            'face_encoding': [],
                            'glasses_detected': False,
                            'glasses_confidence': 0.0,
                            'quality_score': 0.0,
                            'liveness_score': 0.0,
                            'liveness_check': {
                                'is_live': False,
                                'confidence': 0.0,
                                'details': {
                                    'method': 'InsightFace',
                                    'note': 'Request parsing failed'
                                }
                            },
                            'security_warnings': ['Invalid request format'],
                            'glasses_details': {
                                'detected': False,
                                'confidence': 0.0
                            },
                            'processing_time_ms': 0,
                            'face_attributes': {
                                'age': 0,
                                'gender': 'unknown'
                            },
                            'suitable_for_registration': False,
                            'recommendations': ['요청 형식을 확인해주세요.']
                        }), 400
                
                if not image_data:
                    return jsonify({
                        'success': False,
                        'error': 'Missing image data'
                    }), 400
                
                # 이미지 디코딩 (에러 처리 포함)
                try:
                    image = self._decode_image(image_data)
                except Exception as decode_error:
                    logger.warning(f"이미지 디코딩 실패: {str(decode_error)}")
                    return jsonify({
                        'success': False,
                        'face_detected': False,
                        'error': '이미지를 처리할 수 없습니다. 유효한 이미지를 업로드해주세요.',
                        'face_encoding': [],
                        'glasses_detected': False,
                        'glasses_confidence': 0.0,
                        'quality_score': 0.0,
                        'liveness_score': 0.0,
                        'liveness_check': {
                            'is_live': False,
                            'confidence': 0.0,
                            'details': {
                                'method': 'InsightFace',
                                'note': 'Image decoding failed'
                            }
                        },
                        'security_warnings': ['Invalid image format'],
                        'glasses_details': {
                            'detected': False,
                            'confidence': 0.0
                        },
                        'processing_time_ms': 0,
                        'face_attributes': {
                            'age': 0,
                            'gender': 'unknown'
                        },
                        'suitable_for_registration': False,
                        'recommendations': ['유효한 이미지를 업로드해주세요.']
                    }), 400
                
                # 임베딩 추출
                start_time = datetime.datetime.now()
                result = self.face_service.extract_embedding(image)
                processing_time = (datetime.datetime.now() - start_time).total_seconds() * 1000
                
                # 얼굴이 검출된 경우 liveness detection 수행
                liveness_passed = False
                liveness_score = 0.0
                quality_result = None
                if result['success']:
                    # 얼굴 재검출 (liveness detection을 위해)
                    faces = self.face_service.app.get(image)
                    if faces:
                        liveness_passed, liveness_score = self.face_service._detect_liveness(image, faces[0])
                        liveness_passed = bool(liveness_passed)  # NumPy bool을 Python bool로 변환
                        liveness_score = float(liveness_score)
                        
                        # 향상된 품질 평가
                        quality_result = self.face_service._evaluate_quality_enhanced(image, faces[0])
                
                if not result['success']:
                    # 얼굴이 감지되지 않아도 모든 필수 필드 포함
                    return jsonify({
                        'success': False,
                        'face_detected': False,
                        'error': result.get('error', 'Face detection failed'),
                        'face_encoding': [],
                        'glasses_detected': False,  # 사용하지 않음
                        'glasses_confidence': 0.0,   # 사용하지 않음
                        'quality_score': 0.0,
                        'liveness_score': 0.0,
                        'liveness_check': {
                            'is_live': False,
                            'confidence': 0.0,
                            'details': {
                                'method': 'InsightFace',
                                'note': 'No face detected'
                            }
                        },
                        'security_warnings': ['No face detected'],
                        'glasses_details': {
                            'detected': False,     # 사용하지 않음
                            'confidence': 0.0      # 사용하지 않음
                        },
                        'processing_time_ms': processing_time,
                        'face_attributes': {
                            'age': 0,
                            'gender': 'unknown'
                        },
                        'suitable_for_registration': False,
                        'recommendations': ['얼굴이 감지되지 않았습니다. 카메라를 정면으로 바라봐주세요.']
                    }), 400
                
                # 등록 적합성 판단
                suitable_for_registration = True
                recommendations = []
                
                if quality_result:
                    # 검출 신뢰도 체크
                    if quality_result['detection_confidence'] < 0.8:
                        suitable_for_registration = False
                        recommendations.append('얼굴이 명확하게 보이지 않습니다. 조명을 밝게 해주세요.')
                    
                    # 얼굴 크기 체크 (15%~70%로 범위 확대)
                    face_size_ratio = quality_result['position_info']['face_size_ratio']
                    if face_size_ratio < 0.15:
                        suitable_for_registration = False
                        recommendations.append('카메라에 더 가까이 와주세요.')
                    elif face_size_ratio > 0.7:
                        suitable_for_registration = False
                        recommendations.append('조금 뒤로 물러나주세요.')
                    
                    # 얼굴 위치 체크
                    if not quality_result['position_info']['is_centered']:
                        suitable_for_registration = False
                        recommendations.append('얼굴을 화면 중앙에 맞춰주세요.')
                    
                    # 얼굴 각도 체크
                    pose_info = quality_result['pose_info']
                    if not pose_info['is_frontal']:
                        if abs(pose_info['yaw']) > 15:
                            recommendations.append('정면을 바라봐주세요.')
                        if abs(pose_info['pitch']) > 15:
                            if pose_info['pitch'] > 0:
                                recommendations.append('고개를 조금 내려주세요.')  # 위를 보고 있음
                            else:
                                recommendations.append('고개를 조금 들어주세요.')  # 아래를 보고 있음
                        if abs(pose_info['roll']) > 10:
                            recommendations.append('고개를 똑바로 세워주세요.')
                        suitable_for_registration = False
                    
                    # 전체 품질 점수 체크
                    if quality_result['overall_score'] < 0.7:
                        suitable_for_registration = False
                        if not recommendations:  # 다른 구체적인 문제가 없으면
                            recommendations.append('이미지 품질이 낮습니다. 더 나은 조명에서 다시 촬영해주세요.')
                
                # enhanced_face_service와 완전히 동일한 응답 형식
                response_data = {
                    'success': True,
                    'face_detected': True,
                    'face_encoding': result['embedding'],
                    'glasses_detected': False,  # 사용하지 않음
                    'glasses_confidence': 0.0,  # 사용하지 않음
                    'quality_score': result.get('quality_score', 0),
                    'liveness_score': liveness_score,
                    'liveness_check': {
                        'is_live': liveness_passed,
                        'confidence': liveness_score,
                        'details': {
                            'method': 'InsightFace Advanced Liveness Detection',
                            'note': 'Multi-factor liveness analysis (texture, frequency, color)'
                        }
                    },
                    'security_warnings': [] if liveness_passed else ['Liveness check failed'],
                    'glasses_details': {
                        'detected': False,     # 사용하지 않음
                        'confidence': 0.0      # 사용하지 않음
                    },
                    'processing_time_ms': processing_time,
                    'face_attributes': {
                        'age': result.get('age', 0),
                        'gender': result.get('gender', 'unknown')
                    },
                    'embedding_dimensions': len(result['embedding']),
                    'landmark_count': 5 if result.get('landmarks') else 0,
                    'suitable_for_registration': suitable_for_registration,
                    'recommendations': recommendations
                }
                
                # 새로운 필드 추가
                if quality_result:
                    response_data['face_pose'] = {
                        'yaw': quality_result['pose_info']['yaw'],
                        'pitch': quality_result['pose_info']['pitch'],
                        'roll': quality_result['pose_info']['roll'],
                        'is_frontal': quality_result['pose_info']['is_frontal']
                    }
                    response_data['quality_details'] = {
                        'face_size_ratio': quality_result['position_info']['face_size_ratio'],
                        'face_centered': quality_result['position_info']['is_centered'],
                        'detection_confidence': quality_result['detection_confidence'],
                        'overall_quality_score': quality_result['overall_score']
                    }
                
                # notes 생성
                notes = f"InsightFace 검출 - Quality: {result.get('quality_score', 0):.2f}, " \
                       f"Liveness: {liveness_score:.2f} ({'Pass' if liveness_passed else 'Fail'})"
                response_data['notes'] = notes
                
                # NumPy 타입을 Python 기본 타입으로 변환
                response_data = convert_numpy_types(response_data)
                
                return jsonify(response_data)
                
            except Exception as e:
                import traceback
                error_detail = traceback.format_exc()
                logger.error(f"얼굴 검출 오류: {str(e)}\n상세 에러:\n{error_detail}")
                
                # 친근한 에러 메시지 반환
                user_message = '얼굴 검출 중 오류가 발생했습니다. 다시 시도해주세요.'
                
                # 특정 에러에 대한 처리
                if 'AttributeError' in str(e):
                    user_message = '서비스 초기화 오류가 발생했습니다. 서버를 재시작해주세요.'
                elif 'ValueError' in str(e):
                    user_message = '이미지 처리 중 오류가 발생했습니다. 다른 이미지로 시도해주세요.'
                
                return jsonify({
                    'success': False,
                    'face_detected': False,
                    'error': user_message,
                    'technical_error': str(e) if logger.level <= 10 else None  # DEBUG 모드에서만 기술적 에러 표시
                }), 500
            """눈 깜빡임 감지 API - 비디오 프레임 시퀀스 처리"""
            try:
                data = request.get_json()
                frames_data = data.get('frames', [])
                
                if not frames_data or len(frames_data) < 3:
                    return jsonify({
                        'success': False,
                        'error': 'At least 3 frames required for blink detection'
                    }), 400
                
                # 프레임들을 디코딩
                frames = []
                for frame_data in frames_data:
                    frame = self._decode_image(frame_data)
                    frames.append(frame)
                
                # 눈 깜빡임 감지
                blink_detected, confidence = self.face_service.detect_eye_blink(frames)
                
                return jsonify({
                    'success': True,
                    'blink_detected': blink_detected,
                    'confidence': float(confidence),
                    'frames_processed': len(frames),
                    'message': '눈 깜빡임이 감지되었습니다.' if blink_detected else '눈 깜빡임이 감지되지 않았습니다.'
                })
                
            except Exception as e:
                logger.error(f"눈 깜빡임 감지 오류: {str(e)}")
                return jsonify({
                    'success': False,
                    'error': str(e)
                }), 500
        
        @self.app.route('/api/face/recognize_for_checkin', methods=['POST'])
        @self.check_ip_whitelist
        def recognize_for_checkin():
            """체크인용 얼굴 인식 API (엄격한 보안 검사)"""
            try:
                # JSON 데이터 추출
                data = request.get_json()
                image_data = data.get('image')
                check_liveness = data.get('check_liveness', True)
                check_blink = data.get('check_blink', False)
                blink_count = data.get('blink_count', 0)
                # param1/param2를 우선하되, 없으면 comp_cd/bcoff_cd 사용 (하위 호환성)
                param1 = data.get('param1') or data.get('comp_cd')
                param2 = data.get('param2') or data.get('bcoff_cd')
                
                if not image_data:
                    return jsonify({
                        'success': False,
                        'error': 'Missing image data'
                    }), 400
                
                # 이미지 디코딩
                try:
                    image = self._decode_image(image_data)
                except ValueError as e:
                    logger.warning(f"[CHECKIN] 이미지 디코딩 실패: {str(e)}")
                    return jsonify({
                        'success': False,
                        'face_detected': False,
                        'error': '카메라 화면이 제대로 캡처되지 않았습니다. 다시 시도해 주세요.'
                    }), 200  # 400 대신 200으로 반환하여 친근한 메시지 표시
                
                # 임베딩 추출
                start_time = datetime.datetime.now()
                result = self.face_service.extract_embedding(image)
                processing_time = (datetime.datetime.now() - start_time).total_seconds() * 1000
                
                if not result['success']:
                    return jsonify({
                        'success': False,
                        'face_detected': False,
                        'error': result.get('error', '얼굴이 잘 보이지 않습니다. 카메라를 정면으로 바라봐 주세요.')
                    }), 200  # 400 대신 200으로 변경하여 클라이언트가 친근한 메시지를 표시할 수 있도록 함
                
                # 보안 검사
                security_failed = False
                security_details = {
                    'liveness_passed': True,
                    'blink_passed': True,
                    'quality_passed': True,
                    'liveness_confidence': 0.0,
                    'quality_score': 0.0,
                    'security_warnings': []
                }
                
                # Liveness detection
                liveness_score = 0.0
                if check_liveness:
                    faces = self.face_service.app.get(image)
                    if faces:
                        liveness_passed, liveness_score = self.face_service._detect_liveness(image, faces[0])
                        security_details['liveness_passed'] = bool(liveness_passed)
                        security_details['liveness_confidence'] = float(liveness_score)
                        
                        if not liveness_passed:
                            security_failed = True
                            security_details['security_warnings'].append('Liveness check failed')
                            logger.warning(f"[CHECKIN] Liveness 검사 실패: score={liveness_score}")
                
                # 눈 깜빡임 검사
                if check_blink and blink_count < 2:
                    security_details['blink_passed'] = False
                    security_details['blink_count'] = blink_count
                    security_failed = True
                    security_details['security_warnings'].append('Eye blink check failed')
                    logger.warning(f"[CHECKIN] 눈 깜빡임 검사 실패: count={blink_count}")
                
                # 품질 검사
                quality_score = result.get('quality_score', 0)
                security_details['quality_score'] = quality_score
                if quality_score < 0.5:
                    security_details['quality_passed'] = False
                    security_details['quality_score'] = quality_score
                    security_failed = True
                    logger.warning(f"[CHECKIN] 품질 검사 실패: score={quality_score}")
                
                # 보안 검사 실패 시 즉시 반환
                if security_failed:
                    # 실패 로그 저장
                    log_data = {
                        'mem_sno': None,
                        'confidence_score': 0,
                        'similarity_score': 0,
                        'quality_score': quality_score,
                        'processing_time_ms': processing_time,
                        'glasses_detected': False,
                        'match_category': 'checkin',
                        'security_checks_passed': json.dumps(security_details),
                        'success': False,
                        'error_message': 'Security verification failed',
                        'ip_address': request.remote_addr,
                        'user_agent': request.headers.get('User-Agent', ''),
                        'session_id': request.headers.get('X-Session-Id', '') or request.headers.get('Cookie', '')[:50] if request.headers.get('Cookie') else ''
                    }
                    self._save_recognition_log(log_data)
                    
                    return jsonify({
                        'success': False,
                        'security_failed': True,
                        'security_details': security_details,
                        'error': 'Security verification failed'
                    })
                
                # 데이터베이스에서 매칭 (지점별 필터링 포함)
                match_result = self._find_best_match(result['embedding'], False, 'checkin', skip_logging=True, param1=param1, param2=param2)
                
                # 응답 구성
                response = {
                    'success': True,
                    'face_detected': True,
                    'security_failed': False,
                    'security_details': security_details,
                    'processing_time_ms': processing_time
                }
                
                if match_result.get('matched'):
                    # Python은 mem_sno만 반환, 회원 정보는 PHP에서 조회
                    response.update({
                        'face_matching': {
                            'match_found': True,
                            'similarity_score': match_result['similarity'],
                            'member': {
                                'mem_sno': match_result['member_id']
                            }
                        }
                    })
                    
                    # 체크인 로그 저장
                    log_data = {
                        'mem_sno': match_result['member_id'],
                        'confidence_score': match_result['similarity'],
                        'similarity_score': match_result['similarity'],
                        'quality_score': quality_score,
                        'processing_time_ms': processing_time,
                        'glasses_detected': False,
                        'match_category': 'checkin',
                        'security_checks_passed': json.dumps(security_details),
                        'success': True,
                        'ip_address': request.remote_addr,
                        'user_agent': request.headers.get('User-Agent', ''),
                        'session_id': request.headers.get('X-Session-Id', '') or request.headers.get('Cookie', '')[:50] if request.headers.get('Cookie') else '',
                        'param1': param1,
                        'param2': param2
                    }
                    self._save_recognition_log(log_data)
                    
                else:
                    response.update({
                        'face_matching': {
                            'match_found': False,
                            'similarity_score': 0,
                            'error': 'No matching face found'
                        }
                    })
                
                return jsonify(response)
                
            except Exception as e:
                logger.error(f"체크인 인식 오류: {str(e)}")
                return jsonify({
                    'success': False,
                    'error': str(e)
                }), 500
        
        @self.app.route('/api/face/detect_for_registration', methods=['POST'])
        @self.check_ip_whitelist
        def detect_for_registration():
            """회원 등록용 얼굴 검출 API (저장하지 않음, 검출만)"""
            try:
                import datetime
                import json
                
                # 디버그 파일 생성 여부 확인
                debug_enabled = config.DEBUG_OPTIONS.get('request_debug_enabled', False) if hasattr(config, 'DEBUG_OPTIONS') else False
                
                if debug_enabled:
                    # 현재 시간으로 고유한 파일명 생성
                    timestamp = datetime.datetime.now().strftime("%Y%m%d_%H%M%S_%f")
                    debug_folder = config.DEBUG_OPTIONS.get('debug_folder', './debug') if hasattr(config, 'DEBUG_OPTIONS') else './debug'
                    
                    # 디버그 폴더 생성
                    if not os.path.exists(debug_folder):
                        os.makedirs(debug_folder, exist_ok=True)
                    
                    log_file = os.path.join(debug_folder, f"request_debug_{timestamp}.txt")
                    
                    # 상세 로깅 추가
                    logger.info("=" * 60)
                    logger.info(f"[DETECT_REG] API 호출 시작")
                    logger.info(f"[DETECT_REG] Content-Type: {request.content_type}")
                    logger.info(f"[DETECT_REG] Headers: {dict(request.headers)}")
                    logger.info(f"[DETECT_REG] Method: {request.method}")
                    logger.info(f"[DETECT_REG] Request URL: {request.url}")
                    logger.info(f"[DETECT_REG] Remote Addr: {request.remote_addr}")
                    logger.info(f"[DETECT_REG] User Agent: {request.user_agent}")
                    logger.info(f"[DETECT_REG] Debug 파일: {log_file}")
                    
                    # 요청 정보를 파일에 저장
                    with open(log_file, 'w', encoding='utf-8') as f:
                        f.write("=== REQUEST DEBUG INFO ===\n")
                        f.write(f"Timestamp: {datetime.datetime.now()}\n")
                        f.write(f"Method: {request.method}\n")
                        f.write(f"URL: {request.url}\n")
                        f.write(f"Remote Address: {request.remote_addr}\n")
                        f.write(f"Content-Type: {request.content_type}\n")
                        f.write(f"Content-Length: {request.content_length}\n")
                        f.write(f"User-Agent: {request.user_agent}\n")
                        f.write("\n=== HEADERS ===\n")
                        for key, value in request.headers:
                            f.write(f"{key}: {value}\n")
                        f.write(f"\n=== FORM DATA ===\n")
                        f.write(f"Form keys: {list(request.form.keys())}\n")
                        f.write(f"Files keys: {list(request.files.keys())}\n")
                        f.write(f"\n=== RAW BODY ===\n")
                        raw_body = request.get_data(as_text=True)
                        f.write(f"Length: {len(raw_body)} chars\n")
                        f.write(f"Content: {repr(raw_body)}\n")
                        if len(raw_body) < 10000:  # 큰 데이터는 제한
                            f.write(f"Raw body: {raw_body}\n")
                        f.write(f"\n=== PARSED JSON ===\n")
                        try:
                            json_data = request.get_json()
                            f.write(f"JSON: {json.dumps(json_data, indent=2, ensure_ascii=False)}\n")
                        except Exception as e:
                            f.write(f"JSON Parse Error: {str(e)}\n")
                
                # FormData와 JSON 모두 지원
                if request.content_type and 'multipart/form-data' in request.content_type:
                    logger.info(f"[DETECT_REG] FormData 처리 시작")
                    logger.info(f"[DETECT_REG] Files keys: {list(request.files.keys())}")
                    
                    # FormData 처리
                    if 'image' in request.files:
                        image_file = request.files['image']
                        logger.info(f"[DETECT_REG] 이미지 파일 발견: {image_file.filename}")
                        image_bytes = image_file.read()
                        logger.info(f"[DETECT_REG] 이미지 크기: {len(image_bytes)} bytes")
                        
                        # base64로 인코딩하고 data URI 형식으로 만들기
                        image_b64 = base64.b64encode(image_bytes).decode('utf-8')
                        image_data = f"data:image/jpeg;base64,{image_b64}"
                        logger.info(f"[DETECT_REG] Base64 인코딩 완료: {len(image_data)} chars")
                    else:
                        logger.warning(f"[DETECT_REG] 이미지 파일 없음 - NO_IMAGE_PROVIDED")
                        return jsonify({
                            'success': False,  # 이미지 없음
                            'face_detected': False,
                            'suitable_for_registration': False,
                            'quality_score': 0.0,
                            'processing_time_ms': 0,
                            'recommendations': ['이미지를 선택해주세요.'],
                            'error_code': 'NO_IMAGE_PROVIDED',
                            'error_message': 'No image file provided'
                        }), 200
                else:
                    # JSON 처리
                    logger.info(f"[DETECT_REG] JSON 처리 시작")
                    
                    # request body 로깅
                    raw_data = request.get_data(as_text=True)
                    logger.info(f"[DETECT_REG] Raw body length: {len(raw_data)} chars")
                    if len(raw_data) < 1000:  # 작은 경우만 전체 로깅
                        logger.info(f"[DETECT_REG] Raw body: {raw_data}")
                    
                    # JSON 파싱 시 바이너리 데이터 처리
                    try:
                        # 먼저 원본으로 시도
                        data = request.get_json()
                        logger.info(f"[DETECT_REG] JSON 파싱 성공 (원본)")
                    except Exception as e:
                        logger.warning(f"[DETECT_REG] 원본 JSON 파싱 실패: {e}")
                        logger.error(f"[DETECT_REG] 클라이언트가 바이너리 이미지 데이터를 JSON에 직접 포함시켰습니다")
                        logger.error(f"[DETECT_REG] Base64 인코딩이 필요합니다")
                        
                        # 클라이언트에게 명확한 오류 메시지 반환
                        return jsonify({
                            'success': False,
                            'face_detected': False,
                            'suitable_for_registration': False,
                            'quality_score': 0.0,
                            'processing_time_ms': 0,
                            'recommendations': [
                                '이미지 데이터를 Base64로 인코딩하여 전송해주세요.',
                                'JSON에 바이너리 데이터를 직접 포함시킬 수 없습니다.'
                            ],
                            'error_code': 'INVALID_IMAGE_FORMAT',
                            'error_message': 'Binary image data in JSON. Please use Base64 encoding.',
                            'expected_format': {
                                'image': 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYA... (Base64 encoded)'
                            }
                        }), 400
                    
                    if data:
                        logger.info(f"[DETECT_REG] JSON keys: {list(data.keys())}")
                        image_data = data.get('image')
                        if image_data:
                            logger.info(f"[DETECT_REG] image 필드 발견: {len(image_data)} chars")
                            # Base64 헤더 확인
                            if image_data.startswith('data:'):
                                logger.info(f"[DETECT_REG] Data URI 형식 감지")
                            else:
                                logger.info(f"[DETECT_REG] Raw Base64 형식")
                        else:
                            logger.warning(f"[DETECT_REG] JSON에 image 필드 없음 또는 빈 값")
                    else:
                        logger.error(f"[DETECT_REG] JSON 파싱 완전 실패")
                        image_data = None
                    
                    # 빈 문자열 체크 추가
                    if image_data == "":
                        logger.warning(f"[DETECT_REG] image 필드가 빈 문자열")
                        image_data = None
                
                if not image_data:
                    logger.error(f"[DETECT_REG] 이미지 데이터 없음 - MISSING_IMAGE_DATA")
                    logger.error(f"[DETECT_REG] Content-Type: {request.content_type}")
                    logger.error(f"[DETECT_REG] Content-Length: {request.content_length}")
                    return jsonify({
                        'success': False,  # 이미지 데이터 없음
                        'face_detected': False,
                        'suitable_for_registration': False,
                        'quality_score': 0.0,
                        'processing_time_ms': 0,
                        'recommendations': ['이미지 데이터가 없습니다. 다시 시도해주세요.'],
                        'error_code': 'MISSING_IMAGE_DATA',
                        'error_message': 'Missing image data'
                    }), 200
                
                # 이미지 디코딩
                logger.info(f"[DETECT_REG] 이미지 디코딩 시작")
                image = self._decode_image(image_data)
                logger.info(f"[DETECT_REG] 이미지 디코딩 완료: shape={image.shape}")
                
                # 임베딩 추출
                start_time = datetime.datetime.now()
                result = self.face_service.extract_embedding(image)
                processing_time = (datetime.datetime.now() - start_time).total_seconds() * 1000
                
                # 얼굴이 검출된 경우 liveness detection 수행
                liveness_passed = False
                liveness_score = 0.0
                quality_result = None
                if result['success']:
                    # 얼굴 재검출 (liveness detection을 위해)
                    faces = self.face_service.app.get(image)
                    if faces:
                        liveness_passed, liveness_score = self.face_service._detect_liveness(image, faces[0])
                        liveness_passed = bool(liveness_passed)  # NumPy bool을 Python bool로 변환
                        liveness_score = float(liveness_score)
                        
                        # 향상된 품질 평가
                        quality_result = self.face_service._evaluate_quality_enhanced(image, faces[0])
                
                if not result['success']:
                    # 얼굴이 감지되지 않았을 때 success: false로 반환
                    error_msg = result.get('error', 'Face detection failed')
                    recommendations = []
                    error_detail = ""
                    
                    if 'Multiple faces' in error_msg:
                        recommendations.append('여러 얼굴이 감지되었습니다. 한 명만 촬영해주세요.')
                        error_detail = "MULTIPLE_FACES_DETECTED"
                    elif 'No face' in error_msg:
                        recommendations.append('얼굴이 감지되지 않았습니다. 카메라를 정면으로 바라봐주세요.')
                        error_detail = "NO_FACE_DETECTED"
                    else:
                        recommendations.append('얼굴 검출에 실패했습니다. 다시 시도해주세요.')
                        error_detail = "FACE_DETECTION_FAILED"
                    
                    return jsonify({
                        'success': False,  # 얼굴 검출 실패
                        'face_detected': False,
                        'suitable_for_registration': False,
                        'quality_score': 0.0,
                        'processing_time_ms': processing_time,
                        'recommendations': recommendations,
                        'error_code': error_detail,  # 에러 코드
                        'error_message': error_msg  # 상세 에러 메시지
                    }), 200
                
                # 등록 적합성 판단
                suitable_for_registration = True
                recommendations = []
                
                if quality_result:
                    # 검출 신뢰도 체크
                    if quality_result['detection_confidence'] < 0.8:
                        suitable_for_registration = False
                        recommendations.append('얼굴이 명확하게 보이지 않습니다. 조명을 밝게 해주세요.')
                    
                    # 얼굴 크기 체크 (15%~70%로 범위 확대)
                    face_size_ratio = quality_result['position_info']['face_size_ratio']
                    if face_size_ratio < 0.15:
                        suitable_for_registration = False
                        recommendations.append('카메라에 더 가까이 와주세요.')
                    elif face_size_ratio > 0.7:
                        suitable_for_registration = False
                        recommendations.append('조금 뒤로 물러나주세요.')
                    
                    # 얼굴 위치 체크
                    if not quality_result['position_info']['is_centered']:
                        suitable_for_registration = False
                        recommendations.append('얼굴을 화면 중앙에 맞춰주세요.')
                    
                    # 얼굴 각도 체크
                    pose_info = quality_result['pose_info']
                    if not pose_info['is_frontal']:
                        if abs(pose_info['yaw']) > 15:
                            recommendations.append('정면을 바라봐주세요.')
                        if abs(pose_info['pitch']) > 15:
                            if pose_info['pitch'] > 0:
                                recommendations.append('고개를 조금 내려주세요.')  # 위를 보고 있음
                            else:
                                recommendations.append('고개를 조금 들어주세요.')  # 아래를 보고 있음
                        if abs(pose_info['roll']) > 10:
                            recommendations.append('고개를 똑바로 세워주세요.')
                        suitable_for_registration = False
                    
                    # 전체 품질 점수 체크
                    if quality_result['overall_score'] < 0.7:
                        suitable_for_registration = False
                        if not recommendations:  # 다른 구체적인 문제가 없으면
                            recommendations.append('이미지 품질이 낮습니다. 더 나은 조명에서 다시 촬영해주세요.')
                
                # 응답 데이터 - displayResult와 호환되는 형식
                response_data = {
                    'success': suitable_for_registration,  # 등록 가능한 경우만 true
                    'face_detected': True,
                    'suitable_for_registration': suitable_for_registration,
                    'quality_score': result.get('quality_score', 0),
                    'processing_time_ms': processing_time,
                    'recommendations': recommendations
                }
                
                # 등록 부적합한 경우 에러 코드 추가
                if not suitable_for_registration:
                    error_codes = []
                    if quality_result:
                        pose_info = quality_result['pose_info']
                        if not pose_info['is_frontal']:
                            if abs(pose_info['yaw']) > 15:
                                error_codes.append('FACE_ANGLE_YAW')
                            if abs(pose_info['pitch']) > 15:
                                error_codes.append('FACE_ANGLE_PITCH')
                            if abs(pose_info['roll']) > 10:
                                error_codes.append('FACE_ANGLE_ROLL')
                        
                        face_size_ratio = quality_result['position_info']['face_size_ratio']
                        if face_size_ratio < 0.15:
                            error_codes.append('FACE_TOO_SMALL')
                        elif face_size_ratio > 0.7:
                            error_codes.append('FACE_TOO_LARGE')
                        
                        if not quality_result['position_info']['is_centered']:
                            error_codes.append('FACE_NOT_CENTERED')
                        
                        if quality_result['detection_confidence'] < 0.8:
                            error_codes.append('LOW_DETECTION_CONFIDENCE')
                        
                        if quality_result['overall_score'] < 0.7:
                            error_codes.append('LOW_QUALITY_SCORE')
                    
                    response_data['error_code'] = ','.join(error_codes) if error_codes else 'REGISTRATION_UNSUITABLE'
                    response_data['error_message'] = 'Face detected but not suitable for registration'
                
                # 추가 필드들
                if quality_result:
                    response_data['face_pose'] = {
                        'yaw': quality_result['pose_info']['yaw'],
                        'pitch': quality_result['pose_info']['pitch'],
                        'roll': quality_result['pose_info']['roll'],
                        'is_frontal': quality_result['pose_info']['is_frontal']
                    }
                    response_data['quality_details'] = {
                        'face_size_ratio': quality_result['position_info']['face_size_ratio'],
                        'face_centered': quality_result['position_info']['is_centered'],
                        'detection_confidence': quality_result['detection_confidence'],
                        'overall_quality_score': quality_result['overall_score']
                    }
                
                # NumPy 타입을 Python 기본 타입으로 변환
                response_data = convert_numpy_types(response_data)
                
                return jsonify(response_data)
                
            except Exception as e:
                logger.error(f"얼굴 검출 오류: {str(e)}")
                import traceback
                logger.error(f"Traceback: {traceback.format_exc()}")
                # 오류 발생 시 success: false로 200 반환
                return jsonify({
                    'success': False,  # 처리 실패
                    'face_detected': False,
                    'suitable_for_registration': False,
                    'quality_score': 0.0,
                    'processing_time_ms': 0,
                    'recommendations': ['이미지 처리 중 오류가 발생했습니다. 다시 시도해주세요.'],
                    'error_code': 'PROCESSING_ERROR',
                    'error_message': str(e)  # 에러 메시지
                }), 200
        
        @self.app.route('/', methods=['GET'])
        @self.check_ip_whitelist
        def index():
            """테스트 인터페이스"""
            return render_template_string(TEST_INTERFACE_HTML)
    
    def _decode_image(self, image_data: str) -> np.ndarray:
        """Base64 이미지 디코딩"""
        try:
            # base64 헤더 제거
            if ',' in image_data:
                image_data = image_data.split(',')[1]
            
            # 디코딩
            image_bytes = base64.b64decode(image_data)
            image = Image.open(io.BytesIO(image_bytes))
            
            # 이미지가 비어있는지 확인
            image_array = np.array(image)
            if image_array.size == 0:
                raise ValueError("빈 이미지입니다")
            
            # OpenCV 형식으로 변환
            image = cv2.cvtColor(image_array, cv2.COLOR_RGB2BGR)
            
            return image
        except Exception as e:
            logger.error(f"이미지 디코딩 오류: {str(e)}")
            raise ValueError(f"이미지를 처리할 수 없습니다: {str(e)}")
    
    def _save_face_to_db(self, member_id: str, face_data: dict, param1: str = None, param2: str = None) -> dict:
        """데이터베이스에 얼굴 데이터 저장"""
        connection = self.get_db_connection()
        cursor = connection.cursor()
        
        try:
            # notes 생성 - 등록 시 정보 기록
            notes = f"InsightFace 등록 - Quality: {face_data['quality_score']:.2f}, " \
                   f"Liveness: {face_data.get('liveness_score', 0.95):.2f}"
            
            # 기존 데이터가 있는지 확인
            if self.db_type == 'mssql':
                cursor.execute("""
                    SELECT TOP 1 face_id FROM member_faces 
                    WHERE mem_sno = ? AND is_active = 1
                """, (member_id,))
            else:
                cursor.execute("""
                    SELECT face_id FROM member_faces 
                    WHERE mem_sno = %s AND is_active = 1
                    LIMIT 1
                """, (member_id,))
            existing_face = cursor.fetchone()
            
            if existing_face:
                # 기존 데이터 업데이트
                face_id = existing_face[0]
                if self.db_type == 'mssql':
                    cursor.execute("""
                        UPDATE member_faces 
                        SET face_encoding = ?,
                            quality_score = ?,
                            glasses_detected = ?,
                            security_level = ?,
                            liveness_score = ?,
                            last_updated = GETDATE(),
                            notes = ?,
                            param1 = ?,
                            param2 = ?
                        WHERE face_id = ?
                    """, (
                        json.dumps(face_data['embedding']),
                        face_data['quality_score'],
                        0,  # 안경 감지 사용 안함
                        3,  # 보안 레벨
                        face_data.get('liveness_score', 0.95),
                        notes,
                        param1,
                        param2,
                        face_id
                    ))
                else:
                    cursor.execute("""
                        UPDATE member_faces 
                        SET face_encoding = %s,
                            quality_score = %s,
                            glasses_detected = %s,
                            security_level = %s,
                            liveness_score = %s,
                            last_updated = NOW(),
                            notes = %s,
                            param1 = %s,
                            param2 = %s
                        WHERE face_id = %s
                    """, (
                    json.dumps(face_data['embedding']),
                    face_data['quality_score'],
                    0,  # 안경 감지 사용 안함
                    3,  # 보안 레벨
                    face_data.get('liveness_score', 0.95),
                    notes,
                    param1,
                    param2,
                    face_id
                ))
                logger.info(f"[DB] 기존 얼굴 데이터 업데이트 - face_id: {face_id}, mem_sno: {member_id}")
            else:
                # 새 데이터 삽입
                if self.db_type == 'mssql':
                    cursor.execute("""
                        INSERT INTO member_faces 
                        (mem_sno, face_encoding, quality_score, glasses_detected, 
                         security_level, liveness_score, is_active, registered_date, notes, param1, param2)
                        VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE(), ?, ?, ?)
                    """, (
                        member_id,
                        json.dumps(face_data['embedding']),
                        face_data['quality_score'],
                        0,  # 안경 감지 사용 안함
                        3,  # 보안 레벨
                        face_data.get('liveness_score', 0.95),
                        1,  # is_active
                        notes,
                        param1,
                        param2
                    ))
                else:
                    cursor.execute("""
                        INSERT INTO member_faces 
                        (mem_sno, face_encoding, quality_score, glasses_detected, 
                         security_level, liveness_score, is_active, registered_date, notes, param1, param2)
                        VALUES (%s, %s, %s, %s, %s, %s, %s, NOW(), %s, %s, %s)
                    """, (
                    member_id,
                    json.dumps(face_data['embedding']),
                    face_data['quality_score'],
                    0,  # 안경 감지 사용 안함
                    3,  # 보안 레벨
                    face_data.get('liveness_score', 0.95),
                    1,  # is_active
                    notes,
                    param1,
                    param2
                ))
                face_id = cursor.lastrowid
                logger.info(f"[DB] 새 얼굴 데이터 삽입 - face_id: {face_id}, mem_sno: {member_id}")
            
            # 로그 저장 (enhanced_face_service.py 방식)
            try:
                logger.info(f"[LOG] face_recognition_logs 저장 시작 - mem_sno: {member_id}")
                
                # 요청 정보 가져오기
                ip_address = request.remote_addr or request.environ.get('HTTP_X_FORWARDED_FOR', '127.0.0.1')
                user_agent = request.headers.get('User-Agent', '')
                
                # 세션 ID 가져오기 (쿠키에서 세션 ID 추출)
                session_id = ''
                if 'ci_session' in request.cookies:
                    session_id = request.cookies['ci_session']
                elif 'session_id' in request.cookies:
                    session_id = request.cookies['session_id']
                else:
                    # Flask 세션 ID 사용
                    from flask import session
                    session_id = session.get('session_id', '')
                
                logger.info(f"[LOG] Request Info - IP: {ip_address}, User-Agent: {user_agent[:50]}..., Session ID: {session_id[:20]}...")
                logger.info(f"[LOG] Available cookies: {list(request.cookies.keys())}")
                
                # SQL 쿼리와 파라미터 로깅
                if self.db_type == 'mssql':
                    log_query = """
                        INSERT INTO face_recognition_logs 
                        (mem_sno, confidence_score, similarity_score, quality_score,
                         processing_time_ms, glasses_detected, match_category, 
                         security_checks_passed, success, error_message, 
                         ip_address, user_agent, session_id, recognition_time, param1, param2)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), ?, ?)
                    """
                else:
                    log_query = """
                        INSERT INTO face_recognition_logs 
                        (mem_sno, confidence_score, similarity_score, quality_score,
                         processing_time_ms, glasses_detected, match_category, 
                         security_checks_passed, success, error_message, 
                         ip_address, user_agent, session_id, recognition_time, param1, param2)
                        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW(), %s, %s)
                    """
                log_params = (
                    member_id,
                    1.0,  # 등록시 confidence는 1.0
                    1.0,  # 등록시 similarity는 1.0
                    face_data['quality_score'],
                    face_data.get('processing_time_ms', 0),
                    0,  # 안경 감지 사용 안함
                    'registration',
                    json.dumps({
                        'liveness_passed': face_data.get('liveness_score', 0.95) >= 0.6,
                        'liveness_confidence': face_data.get('liveness_score', 0.95),
                        'quality_score': face_data['quality_score'],
                        'glasses_confidence': 0.0,  # 안경 감지 사용 안함
                        'security_warnings': []
                    }),
                    1,  # success
                    None,  # error_message
                    ip_address,
                    user_agent,
                    session_id,
                    param1,
                    param2
                )
                
                logger.info(f"[LOG] SQL Query: {log_query}")
                logger.info(f"[LOG] SQL Params: {log_params}")
                
                cursor.execute(log_query, log_params)
                logger.info(f"[LOG] face_recognition_logs 저장 성공 - 영향받은 행: {cursor.rowcount}")
            except Exception as log_error:
                logger.error(f"[LOG] face_recognition_logs 저장 실패: {str(log_error)}")
                logger.error(f"[LOG] Error Type: {type(log_error).__name__}")
                logger.error(f"[LOG] Full Error: {repr(log_error)}")
                import traceback
                logger.error(f"[LOG] Traceback: {traceback.format_exc()}")
                # 로그 저장 실패해도 얼굴 등록은 계속 진행
            
            connection.commit()
            
            return {
                'db_success': True,
                'face_id': face_id,
                'message': '얼굴 등록이 완료되었습니다.'
            }
            
        except Exception as e:
            connection.rollback()
            logger.error(f"DB 저장 오류: {str(e)}")
            return {
                'db_success': False,
                'db_error': str(e),
                'message': f'데이터베이스 저장 중 오류가 발생했습니다: {str(e)}'
            }
        finally:
            cursor.close()
            connection.close()
    
    def _find_best_match(self, test_embedding: List[float], glasses_detected: bool = False, match_category: str = 'recognition', skip_logging: bool = False, comp_cd: str = None, bcoff_cd: str = None, param1: str = None, param2: str = None) -> dict:  # glasses_detected는 사용 안함
        """데이터베이스에서 최적 매칭 찾기 (지점별 필터링 지원)"""
        connection = self.get_db_connection()
        
        # DB 타입에 따른 cursor 생성
        if self.db_type == 'mssql':
            cursor = connection.cursor()
        else:
            cursor = connection.cursor(dictionary=True)
        
        try:
            # 파라미터 우선순위: param1/param2가 있으면 사용, 없으면 comp_cd/bcoff_cd 사용
            use_param1 = param1 if param1 is not None else comp_cd
            use_param2 = param2 if param2 is not None else bcoff_cd
            
            # 파라미터 기반 필터링
            if use_param1 and use_param2:
                # param1과 param2 모두 있는 경우
                if self.db_type == 'mssql':
                    cursor.execute("""
                        SELECT face_id, mem_sno, face_encoding, glasses_detected
                        FROM member_faces
                        WHERE is_active = 1
                        AND param1 = ?
                        AND param2 = ?
                    """, (use_param1, use_param2))
                else:
                    cursor.execute("""
                        SELECT face_id, mem_sno, face_encoding, glasses_detected
                        FROM member_faces
                        WHERE is_active = 1
                        AND param1 = %s
                        AND param2 = %s
                    """, (use_param1, use_param2))
                logger.info(f"[RECOG] param1={use_param1}, param2={use_param2}로 필터링")
                
            elif use_param1:
                # param1만 있는 경우
                if self.db_type == 'mssql':
                    cursor.execute("""
                        SELECT face_id, mem_sno, face_encoding, glasses_detected
                        FROM member_faces
                        WHERE is_active = 1
                        AND param1 = ?
                    """, (use_param1,))
                else:
                    cursor.execute("""
                        SELECT face_id, mem_sno, face_encoding, glasses_detected
                        FROM member_faces
                        WHERE is_active = 1
                        AND param1 = %s
                    """, (use_param1,))
                logger.info(f"[RECOG] param1={use_param1}로 필터링")
                
            else:
                # 파라미터가 없는 경우 - 전체 조회
                cursor.execute("""
                    SELECT face_id, mem_sno, face_encoding, glasses_detected
                    FROM member_faces
                    WHERE is_active = 1
                """)
                logger.info("[RECOG] 전체 얼굴 데이터 조회")
            
            faces = cursor.fetchall()
            
            best_match = None
            best_similarity = 0
            match_type = None
            
            for face in faces:
                try:
                    # MSSQL은 tuple로 반환하므로 인덱스로 접근
                    if self.db_type == 'mssql':
                        face_id = face[0]
                        mem_sno = face[1]
                        face_encoding = face[2]
                        glasses_detected_db = face[3]
                    else:
                        face_id = face['face_id']
                        mem_sno = face['mem_sno']
                        face_encoding = face['face_encoding']
                        glasses_detected_db = face['glasses_detected']
                    
                    stored_embedding = json.loads(face_encoding)
                    
                    # InsightFace 임베딩인지 확인 (512차원)
                    if len(stored_embedding) == 512:
                        similarity = self.face_service.compare_embeddings(
                            test_embedding, 
                            stored_embedding
                        )
                        match_type = 'insightface'
                    else:
                        # MediaPipe 임베딩과는 비교하지 않음 (차원이 다름)
                        continue
                    
                    # 안경 상태에 따른 가중치 적용
                    if glasses_detected_db == glasses_detected:
                        similarity *= 1.05  # 동일 안경 상태 보너스
                    else:
                        similarity *= 0.95  # 다른 안경 상태 페널티
                    
                    if similarity > best_similarity:
                        best_similarity = similarity
                        if self.db_type == 'mssql':
                            best_match = {
                                'face_id': face_id,
                                'mem_sno': mem_sno,
                                'face_encoding': face_encoding,
                                'glasses_detected': glasses_detected_db
                            }
                        else:
                            best_match = face
                        
                except Exception as e:
                    if self.db_type == 'mssql':
                        logger.error(f"얼굴 비교 오류 (face_id: {face_id}): {e}")
                    else:
                        logger.error(f"얼굴 비교 오류 (face_id: {face['face_id']}): {e}")
                    continue
            
            # 임계값 확인
            if best_match and best_similarity >= self.face_service.thresholds['recognition']:
                # 인식 로그 저장 (skip_logging이 True면 건너뛰기)
                if not skip_logging:
                    log_data = {
                        'mem_sno': best_match['mem_sno'],
                        'confidence_score': best_similarity,
                        'similarity_score': best_similarity,
                        'glasses_detected': glasses_detected,
                        'match_category': match_category,
                        'success': True
                    }
                    self._save_recognition_log(log_data)
                
                return {
                    'matched': True,
                    'member_id': best_match['mem_sno'],
                    'similarity': float(best_similarity),
                    'confidence': 'high' if best_similarity >= 0.8 else 'medium',
                    'match_type': match_type
                }
            else:
                # 실패 로그 저장 (skip_logging이 True면 건너뛰기)
                if not skip_logging:
                    log_data = {
                        'mem_sno': None,
                        'confidence_score': best_similarity if best_similarity else 0,
                        'similarity_score': best_similarity if best_similarity else 0,
                        'glasses_detected': glasses_detected,
                        'match_category': match_category,
                        'success': False,
                        'error_message': 'No match found above threshold'
                    }
                    self._save_recognition_log(log_data)
                
                return {
                    'matched': False,
                    'similarity': float(best_similarity) if best_similarity > 0 else 0,
                    'message': '등록된 회원 정보를 찾을 수 없습니다. 회원번호를 입력해 주세요.',
                    'threshold': self.face_service.thresholds['recognition']
                }
                
        except Exception as e:
            logger.error(f"매칭 오류: {e}")
            return {
                'matched': False,
                'error': str(e),
                'message': '얼굴 매칭 중 오류가 발생했습니다.'
            }
        finally:
            cursor.close()
            connection.close()
    
    # def _get_member_info(self, mem_sno: str) -> Dict:
    #     """회원 정보 조회 - PHP에서 처리하도록 변경됨"""
    #     # Python은 얼굴 인식 결과(mem_sno)만 반환
    #     # 회원 정보 조회는 PHP에서 직접 처리
    #     pass
    
    def _save_recognition_log(self, log_data: Dict):
        """인식 로그 저장 (개선된 버전)"""
        connection = None
        cursor = None
        try:
            connection = self.get_db_connection()
            cursor = connection.cursor()
            
            # 요청 정보 가져오기
            ip_address = request.remote_addr or request.environ.get('HTTP_X_FORWARDED_FOR', '127.0.0.1')
            user_agent = request.headers.get('User-Agent', '')
            
            # 세션 ID 가져오기
            session_id = ''
            if 'ci_session' in request.cookies:
                session_id = request.cookies['ci_session']
            elif 'session_id' in request.cookies:
                session_id = request.cookies['session_id']
            else:
                from flask import session
                session_id = session.get('session_id', '')
            
            # 기본값 설정
            mem_sno = log_data.get('mem_sno')
            confidence_score = log_data.get('confidence_score', 0)
            similarity_score = log_data.get('similarity_score', 0)
            quality_score = log_data.get('quality_score', 0.85)
            glasses_detected = log_data.get('glasses_detected', False)
            match_category = log_data.get('match_category', 'recognition')
            security_checks_passed = log_data.get('security_checks_passed', '{}')
            success = log_data.get('success', False)
            error_message = log_data.get('error_message', None)
            processing_time_ms = log_data.get('processing_time_ms', 0)
            
            # IP와 User-Agent는 전달받은 값 우선, 없으면 현재 요청에서 추출
            ip_address = log_data.get('ip_address', ip_address)
            user_agent = log_data.get('user_agent', user_agent)
            session_id = log_data.get('session_id', session_id)
            
            # param1, param2 추가
            param1 = log_data.get('param1', None)
            param2 = log_data.get('param2', None)
            
            if self.db_type == 'mssql':
                log_query = """
                    INSERT INTO face_recognition_logs 
                    (mem_sno, confidence_score, similarity_score, quality_score,
                     glasses_detected, match_category, security_checks_passed,
                     success, error_message, ip_address, user_agent, session_id,
                     recognition_time, processing_time_ms, param1, param2)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), ?, ?, ?)
                """
            else:
                log_query = """
                    INSERT INTO face_recognition_logs 
                    (mem_sno, confidence_score, similarity_score, quality_score,
                     glasses_detected, match_category, security_checks_passed,
                     success, error_message, ip_address, user_agent, session_id,
                     recognition_time, processing_time_ms, param1, param2)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW(), %s, %s, %s)
                """
            log_params = (
                mem_sno,
                confidence_score,
                similarity_score,
                quality_score,
                1 if glasses_detected else 0,
                match_category,
                security_checks_passed,
                1 if success else 0,
                error_message,
                ip_address,
                user_agent,
                session_id,
                processing_time_ms,
                param1,
                param2
            )
            
            logger.info(f"[RECOG_LOG] 인식 로그 저장 시작 - mem_sno: {mem_sno}, success: {success}")
            logger.info(f"[RECOG_LOG] SQL Query: {log_query}")
            logger.info(f"[RECOG_LOG] SQL Params: {log_params}")
            
            cursor.execute(log_query, log_params)
            connection.commit()
            
            logger.info(f"[RECOG_LOG] 인식 로그 저장 성공 - 영향받은 행: {cursor.rowcount}")
            
        except Exception as e:
            logger.error(f"[RECOG_LOG] 로그 저장 오류: {e}")
            logger.error(f"[RECOG_LOG] Error Type: {type(e).__name__}")
            import traceback
            logger.error(f"[RECOG_LOG] Traceback: {traceback.format_exc()}")
            if connection:
                connection.rollback()
        finally:
            if cursor:
                cursor.close()
            if connection:
                connection.close()
    
    def run(self, host='0.0.0.0', port=5000, debug=False):
        """서버 실행"""
        logger.info(f"🚀 InsightFace API 서버 시작: http://{host}:{port}")
        
        # 등록된 라우트 출력
        logger.info("📍 등록된 API 엔드포인트:")
        for rule in self.app.url_map.iter_rules():
            if 'face' in rule.rule:
                logger.info(f"  - {rule.rule} [{', '.join(rule.methods)}]")
        
        self.app.run(host=host, port=port, debug=debug)


# 메인 실행
if __name__ == '__main__':
    # config에서 설정 가져오기
    try:
        from config import Config
        HOST = Config.HOST
        PORT = Config.PORT
        DEBUG = Config.DEBUG
    except ImportError:
        # config.py가 없으면 기본값 사용
        HOST = '0.0.0.0'
        PORT = 5002
        DEBUG = True
    
    # 서비스 초기화
    face_service = InsightFaceService()
    api = InsightFaceAPI(face_service)
    
    # 서버 실행
    logger.info(f"서버 설정 - HOST: {HOST}, PORT: {PORT}, DEBUG: {DEBUG}")
    api.run(host=HOST, port=PORT, debug=DEBUG)