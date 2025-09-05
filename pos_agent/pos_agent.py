#!/usr/bin/env python3
"""
POS 로컬 에이전트
웹 브라우저와 POS 단말기 간 통신을 중계하는 로컬 서버
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
import serial
import socket
import json
import logging

app = Flask(__name__)
CORS(app)  # 크로스 도메인 허용

# 로깅 설정
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class PosConnector:
    def __init__(self):
        self.serial_conn = None
        self.socket_conn = None
    
    def connect_serial(self, port='COM3', baudrate=9600):
        """시리얼 포트로 POS 연결"""
        try:
            self.serial_conn = serial.Serial(
                port=port,
                baudrate=baudrate,
                timeout=30
            )
            return True
        except Exception as e:
            logger.error(f"시리얼 연결 실패: {e}")
            return False
    
    def connect_socket(self, ip='192.168.1.100', port=9999):
        """네트워크로 POS 연결"""
        try:
            self.socket_conn = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            self.socket_conn.connect((ip, port))
            self.socket_conn.settimeout(30)
            return True
        except Exception as e:
            logger.error(f"소켓 연결 실패: {e}")
            return False
    
    def send_payment_request(self, amount, payment_type='CARD'):
        """POS에 결제 요청 전송"""
        # 각 POS 제조사별 프로토콜에 맞게 구현
        # 예시: KICC 프로토콜
        request_data = {
            'cmd': 'D1',  # 신용승인
            'amount': str(amount).zfill(10),
            'installment': '00',
            'tax': str(int(amount * 0.1)).zfill(10)
        }
        
        # 전문 생성 (제조사별로 다름)
        message = self._build_message(request_data)
        
        # 전송 및 응답 수신
        if self.serial_conn:
            self.serial_conn.write(message.encode())
            response = self.serial_conn.read(1024)
        elif self.socket_conn:
            self.socket_conn.send(message.encode())
            response = self.socket_conn.recv(1024)
        else:
            return {'result': 'error', 'message': 'POS 연결 안됨'}
        
        # 응답 파싱
        return self._parse_response(response)
    
    def _build_message(self, data):
        """POS 전문 생성 (제조사별 구현 필요)"""
        # 예시 구현
        stx = '\x02'
        etx = '\x03'
        body = json.dumps(data)
        return f"{stx}{body}{etx}"
    
    def _parse_response(self, response):
        """POS 응답 파싱 (제조사별 구현 필요)"""
        # 예시 구현
        try:
            data = response.decode('utf-8').strip('\x02\x03')
            return json.loads(data)
        except:
            return {'result': 'error', 'message': '응답 파싱 실패'}

# POS 연결 객체
pos = PosConnector()

@app.route('/health', methods=['GET'])
def health_check():
    """에이전트 상태 확인"""
    return jsonify({'status': 'running', 'version': '1.0.0'})

@app.route('/connect', methods=['POST'])
def connect_pos():
    """POS 연결"""
    data = request.json
    conn_type = data.get('type', 'serial')
    
    if conn_type == 'serial':
        port = data.get('port', 'COM3')
        baudrate = data.get('baudrate', 9600)
        success = pos.connect_serial(port, baudrate)
    else:
        ip = data.get('ip', '192.168.1.100')
        port = data.get('port', 9999)
        success = pos.connect_socket(ip, port)
    
    return jsonify({
        'result': 'success' if success else 'error',
        'message': 'POS 연결 성공' if success else 'POS 연결 실패'
    })

@app.route('/payment', methods=['POST'])
def process_payment():
    """결제 처리"""
    data = request.json
    amount = data.get('amount', 0)
    payment_type = data.get('payment_type', 'CARD')
    
    logger.info(f"결제 요청: {amount}원, 타입: {payment_type}")
    
    # POS로 결제 요청
    result = pos.send_payment_request(amount, payment_type)
    
    # 웹 시스템에 반환할 응답
    if result.get('result') == 'success':
        return jsonify({
            'result': 'success',
            'approval_no': result.get('approval_no'),
            'card_no': result.get('card_no'),
            'transaction_date': result.get('transaction_date')
        })
    else:
        return jsonify({
            'result': 'error',
            'message': result.get('message', '결제 실패')
        })

@app.route('/cancel', methods=['POST'])
def cancel_payment():
    """결제 취소"""
    data = request.json
    approval_no = data.get('approval_no')
    amount = data.get('amount')
    
    # POS로 취소 요청
    # ... 구현 ...
    
    return jsonify({'result': 'success'})

if __name__ == '__main__':
    # 로컬에서만 접근 가능하도록 설정
    app.run(host='127.0.0.1', port=9090, debug=False)