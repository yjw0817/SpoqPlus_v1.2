<?php

namespace App\Services\PosDrivers;

use Exception;

/**
 * KICC POS 드라이버
 * 
 * 한국정보통신(KICC) POS 단말기 연동 드라이버
 */
class KiccPosDriver extends BasePosDriver
{
    // KICC 전문 구분자
    const STX = "\x02";  // Start of Text
    const ETX = "\x03";  // End of Text
    const FS = "\x1C";   // Field Separator
    const CR = "\x0D";   // Carriage Return
    
    // 거래 구분 코드
    const TRX_APPROVAL = 'D1';      // 승인
    const TRX_CANCEL = 'D2';        // 취소
    const TRX_INQUIRY = 'D5';       // 조회
    
    /**
     * 결제 처리
     */
    public function processPayment($paymentData)
    {
        try {
            // 전문 생성
            $request = $this->buildApprovalRequest($paymentData);
            
            // 요청 전송 및 응답 수신
            $response = $this->sendRequest($request);
            
            // 응답 파싱
            $result = $this->parseResponse($response);
            
            // 결과 변환
            return $this->formatPaymentResult($result);
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'transaction_id' => null,
                'approval_no' => null
            ];
        }
    }
    
    /**
     * 결제 취소
     */
    public function cancelPayment($transactionId, $amount)
    {
        try {
            // 취소 전문 생성
            $request = $this->buildCancelRequest($transactionId, $amount);
            
            // 요청 전송 및 응답 수신
            $response = $this->sendRequest($request);
            
            // 응답 파싱
            $result = $this->parseResponse($response);
            
            // 결과 변환
            return $this->formatCancelResult($result);
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * 연결 상태 확인
     */
    public function checkConnection()
    {
        try {
            // 상태 조회 전문 전송
            $request = $this->buildInquiryRequest();
            $response = $this->sendRequest($request);
            
            if ($response) {
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }
    
    /**
     * 승인 요청 전문 생성
     */
    private function buildApprovalRequest($paymentData)
    {
        $fields = [
            self::TRX_APPROVAL,                           // 거래구분
            date('YmdHis'),                               // 거래일시
            $paymentData['order_id'],                     // 주문번호
            str_pad($paymentData['amount'], 12, '0', STR_PAD_LEFT), // 금액
            '00',                                         // 세금
            '00',                                         // 봉사료
            str_pad($paymentData['installment'] ?? '00', 2, '0', STR_PAD_LEFT), // 할부개월
            $this->convertPaymentMethod($paymentData['payment_method']), // 결제수단
            '',                                           // 카드번호 (IC카드는 공란)
            '',                                           // 유효기간
            'K',                                          // 거래형태 (K:IC카드)
            $this->settings['pos_device']['serial_number'] ?? '', // 단말기번호
            'N'                                           // 서명여부
        ];
        
        // 전문 조립
        $message = self::STX . implode(self::FS, $fields) . self::ETX;
        
        // 체크섬 추가
        $message .= $this->calculateChecksum($message);
        
        return $message;
    }
    
    /**
     * 취소 요청 전문 생성
     */
    private function buildCancelRequest($transactionId, $amount)
    {
        $fields = [
            self::TRX_CANCEL,                             // 거래구분
            date('YmdHis'),                               // 거래일시
            $transactionId,                               // 원거래번호
            str_pad($amount, 12, '0', STR_PAD_LEFT),     // 취소금액
            '',                                           // 원승인번호
            date('Ymd'),                                  // 원거래일자
            $this->settings['pos_device']['serial_number'] ?? '' // 단말기번호
        ];
        
        // 전문 조립
        $message = self::STX . implode(self::FS, $fields) . self::ETX;
        
        // 체크섬 추가
        $message .= $this->calculateChecksum($message);
        
        return $message;
    }
    
    /**
     * 조회 요청 전문 생성
     */
    private function buildInquiryRequest()
    {
        $fields = [
            self::TRX_INQUIRY,                            // 거래구분
            date('YmdHis'),                               // 거래일시
            $this->settings['pos_device']['serial_number'] ?? '' // 단말기번호
        ];
        
        // 전문 조립
        $message = self::STX . implode(self::FS, $fields) . self::ETX;
        
        // 체크섬 추가
        $message .= $this->calculateChecksum($message);
        
        return $message;
    }
    
    /**
     * 요청 전송
     */
    private function sendRequest($request)
    {
        switch ($this->connectionType) {
            case 'api':
                return $this->sendApiRequestKicc($request);
                
            case 'serial':
                return $this->sendSerialCommand($request);
                
            case 'network':
                return $this->sendNetworkCommand($request);
                
            default:
                throw new Exception('지원하지 않는 연동 방식입니다.');
        }
    }
    
    /**
     * KICC API 요청
     */
    private function sendApiRequestKicc($request)
    {
        // Base64 인코딩
        $encodedRequest = base64_encode($request);
        
        $data = [
            'terminal_id' => $this->settings['connection']['terminal_id'] ?? '',
            'message' => $encodedRequest,
            'message_type' => 'payment'
        ];
        
        $response = $this->sendApiRequest('/api/v1/payment', $data);
        
        if (isset($response['message'])) {
            return base64_decode($response['message']);
        }
        
        throw new Exception('API 응답 오류');
    }
    
    /**
     * 응답 파싱
     */
    private function parseResponse($response)
    {
        // STX, ETX 제거
        $response = trim($response, self::STX . self::ETX);
        
        // 필드 분리
        $fields = explode(self::FS, $response);
        
        if (count($fields) < 5) {
            throw new Exception('응답 전문 형식 오류');
        }
        
        // 응답 코드 확인
        $responseCode = $fields[1] ?? '';
        $responseMessage = $fields[2] ?? '';
        
        if ($responseCode !== '0000') {
            throw new Exception('거래 실패: ' . $responseMessage);
        }
        
        return [
            'transaction_type' => $fields[0] ?? '',
            'response_code' => $responseCode,
            'response_message' => $responseMessage,
            'transaction_id' => $fields[3] ?? '',
            'approval_no' => $fields[4] ?? '',
            'transaction_date' => $fields[5] ?? '',
            'card_no' => $fields[6] ?? '',
            'card_name' => $fields[7] ?? '',
            'merchant_no' => $fields[8] ?? ''
        ];
    }
    
    /**
     * 결제 결과 포맷
     */
    private function formatPaymentResult($result)
    {
        return [
            'status' => 'success',
            'message' => '결제가 승인되었습니다.',
            'transaction_id' => $result['transaction_id'],
            'approval_no' => $result['approval_no'],
            'card_no' => $this->maskCardNumber($result['card_no']),
            'card_name' => $result['card_name'],
            'transaction_date' => $result['transaction_date']
        ];
    }
    
    /**
     * 취소 결과 포맷
     */
    private function formatCancelResult($result)
    {
        return [
            'status' => 'success',
            'message' => '결제가 취소되었습니다.',
            'cancel_transaction_id' => $result['transaction_id'],
            'cancel_date' => $result['transaction_date']
        ];
    }
    
    /**
     * 체크섬 계산
     */
    private function calculateChecksum($message)
    {
        $checksum = 0;
        
        for ($i = 0; $i < strlen($message); $i++) {
            $checksum ^= ord($message[$i]);
        }
        
        return sprintf('%02X', $checksum);
    }
    
    /**
     * 카드번호 마스킹
     */
    private function maskCardNumber($cardNo)
    {
        if (strlen($cardNo) < 12) {
            return $cardNo;
        }
        
        return substr($cardNo, 0, 6) . '******' . substr($cardNo, -4);
    }
}