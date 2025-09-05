<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Services\InicisPaymentService;
use App\Services\KcpPaymentService;
use App\Services\TossPaymentService;
use App\Services\KiccVanService;
use App\Services\NiceVanService;
use App\Services\KsnetVanService;
use App\Models\PayModel;
use Exception;

/**
 * 결제 처리 컨트롤러
 * 
 * 다양한 PG(이니시스, KCP)를 통한 결제 처리를 담당합니다.
 * - 결제 초기화
 * - 결제 완료 처리
 * - 결제 취소
 * - 콜백 처리
 */
class Payment extends BaseController
{
    protected $inicisService;
    protected $kcpService;
    protected $tossService;
    protected $kiccVanService;
    protected $niceVanService;
    protected $ksnetVanService;
    protected $payModel;
    protected $session;

    public function __construct()
    {
        $this->payModel = new PayModel();
        $this->session = session();
    }

    /**
     * 이니시스 결제 초기화
     */
    public function inicisInit()
    {
        try {
            // POST 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $paymentData = $this->getPaymentDataFromRequest();
            $this->validatePaymentData($paymentData);

            // 이니시스 서비스 초기화
            $this->inicisService = new InicisPaymentService();
            $this->inicisService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 초기화
            $result = $this->inicisService->initializePayment($paymentData);

            if ($result['status'] === 'success') {
                // 임시 결제 정보를 세션에 저장
                $this->session->set('payment_data', $paymentData);
                $this->session->set('payment_tid', $result['tid']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'tid' => $result['tid'],
                        'payment_url' => $result['payment_url'],
                        'form_data' => $result['form_data'],
                        'next_redirect_pc_url' => $result['next_redirect_pc_url'],
                        'next_redirect_mobile_url' => $result['next_redirect_mobile_url']
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', '이니시스 결제 초기화 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 이니시스 결제 완료 처리 (Return URL)
     */
    public function inicisReturn()
    {
        try {
            $verificationData = $this->getVerificationDataFromRequest();
            
            // 이니시스 서비스 초기화
            $paymentData = $this->session->get('payment_data');
            if (!$paymentData) {
                throw new Exception('결제 세션 정보를 찾을 수 없습니다.');
            }

            $this->inicisService = new InicisPaymentService();
            $this->inicisService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 검증
            $result = $this->inicisService->verifyPayment($verificationData);

            if ($result['status'] === 'success') {
                // 결제 완료 후 비즈니스 로직 처리
                $this->processPaymentSuccess($paymentData, $result['payment_result']);

                // 세션 정리
                $this->session->remove(['payment_data', 'payment_tid']);

                // 성공 페이지로 리다이렉트
                return redirect()->to('/payment/success')
                    ->with('payment_result', $result['payment_result']);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', '이니시스 결제 완료 처리 실패: ' . $e->getMessage());

            return redirect()->to('/payment/error')
                ->with('error_message', $e->getMessage());
        }
    }

    /**
     * 이니시스 결제 취소 처리
     */
    public function inicisCancel()
    {
        try {
            // 취소 요청 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $cancelData = $this->getCancelDataFromRequest();
            $this->validateCancelData($cancelData);

            // 기존 결제 정보 조회
            $paymentInfo = $this->getPaymentInfo($cancelData['PAYMT_MGMT_SNO']);
            if (!$paymentInfo) {
                throw new Exception('결제 정보를 찾을 수 없습니다.');
            }

            // 이니시스 서비스 초기화
            $this->inicisService = new InicisPaymentService();
            $this->inicisService->initializeWithBranchSettings($paymentInfo['BCOFF_CD']);

            // 결제 취소
            $result = $this->inicisService->cancelPayment($cancelData);

            if ($result['status'] === 'success') {
                // 취소 완료 후 비즈니스 로직 처리
                $this->processPaymentCancel($paymentInfo, $result['cancel_result']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'refund_detail_sno' => $result['refund_detail_sno'],
                        'cancel_amount' => $cancelData['CANCEL_AMT'],
                        'cancel_date' => date('Y-m-d H:i:s')
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', '이니시스 결제 취소 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 이니시스 노티피케이션 처리 (Webhook)
     */
    public function inicisNotification()
    {
        try {
            $notificationData = $this->getNotificationDataFromRequest();
            
            // 이니시스 서비스 초기화 (알림에서 지점 코드 추출)
            $this->inicisService = new InicisPaymentService();
            
            // TID에서 지점 코드 추출
            $tidParts = explode('_', $notificationData['tid']);
            $bcoff_cd = $tidParts[1] ?? null;
            
            if (!$bcoff_cd) {
                throw new Exception('유효하지 않은 거래 ID입니다.');
            }

            $this->inicisService->initializeWithBranchSettings($bcoff_cd);

            // 결제 검증
            $result = $this->inicisService->verifyPayment($notificationData);

            if ($result['status'] === 'success') {
                // 알림 처리 로그
                log_message('info', '이니시스 노티피케이션 처리 성공', [
                    'tid' => $notificationData['tid'],
                    'amount' => $result['payment_result']['amount'] ?? 'N/A'
                ]);

                // 이니시스에 성공 응답
                return $this->response->setBody('OK');
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', '이니시스 노티피케이션 처리 실패: ' . $e->getMessage());

            // 이니시스에 실패 응답
            return $this->response->setBody('FAIL');
        }
    }

    /**
     * 결제 성공 페이지
     */
    public function success()
    {
        $paymentResult = $this->session->getFlashdata('payment_result');
        
        if (!$paymentResult) {
            return redirect()->to('/');
        }

        $data = [
            'title' => '결제 완료',
            'payment_result' => $paymentResult
        ];

        return view('payment/success', $data);
    }

    /**
     * 결제 오류 페이지
     */
    public function error()
    {
        $errorMessage = $this->session->getFlashdata('error_message') ?? '결제 처리 중 오류가 발생했습니다.';
        
        $data = [
            'title' => '결제 오류',
            'error_message' => $errorMessage
        ];

        return view('payment/error', $data);
    }

    /**
     * 요청에서 결제 데이터 추출
     */
    private function getPaymentDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'BCOFF_CD' => $request['bcoff_cd'] ?? '',
            'MEM_SNO' => $request['mem_sno'] ?? '',
            'MEM_ID' => $request['mem_id'] ?? '',
            'MEM_NM' => $request['mem_nm'] ?? '',
            'MEM_TEL' => $request['mem_tel'] ?? '',
            'MEM_EMAIL' => $request['mem_email'] ?? '',
            'SELL_EVENT_SNO' => $request['sell_event_sno'] ?? '',
            'SELL_EVENT_NM' => $request['sell_event_nm'] ?? '',
            'PAYMT_AMT' => $request['paymt_amt'] ?? 0,
            'PAYMT_MTHD' => $request['paymt_mthd'] ?? 'CARD',
            'PAYMT_CHNL' => $request['paymt_chnl'] ?? 'PC'
        ];
    }

    /**
     * 요청에서 검증 데이터 추출
     */
    private function getVerificationDataFromRequest()
    {
        $request = $this->request->getGet();
        
        return [
            'tid' => $request['tid'] ?? '',
            'amount' => $request['amount'] ?? 0,
            'timestamp' => $request['timestamp'] ?? '',
            'verification' => $request['verification'] ?? '',
            'result_code' => $request['resultCode'] ?? '',
            'result_msg' => $request['resultMsg'] ?? ''
        ];
    }

    /**
     * 요청에서 취소 데이터 추출
     */
    private function getCancelDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'PAYMT_MGMT_SNO' => $request['paymt_mgmt_sno'] ?? '',
            'TID' => $request['tid'] ?? '',
            'CANCEL_AMT' => $request['cancel_amt'] ?? 0,
            'CANCEL_RSON' => $request['cancel_rson'] ?? '',
            'PARTIAL_YN' => $request['partial_yn'] ?? 'N'
        ];
    }

    /**
     * 요청에서 노티피케이션 데이터 추출
     */
    private function getNotificationDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'tid' => $request['tid'] ?? '',
            'amount' => $request['amount'] ?? 0,
            'timestamp' => $request['timestamp'] ?? '',
            'verification' => $request['verification'] ?? '',
            'result_code' => $request['resultCode'] ?? '',
            'result_msg' => $request['resultMsg'] ?? ''
        ];
    }

    /**
     * 결제 데이터 검증
     */
    private function validatePaymentData($data)
    {
        $required = ['BCOFF_CD', 'MEM_SNO', 'MEM_ID', 'MEM_NM', 'PAYMT_AMT', 'SELL_EVENT_NM'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("필수 항목이 누락되었습니다: {$field}");
            }
        }
        
        if ((int)$data['PAYMT_AMT'] <= 0) {
            throw new Exception('결제 금액이 올바르지 않습니다.');
        }
    }

    /**
     * 취소 데이터 검증
     */
    private function validateCancelData($data)
    {
        $required = ['PAYMT_MGMT_SNO', 'TID', 'CANCEL_AMT'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("필수 항목이 누락되었습니다: {$field}");
            }
        }
        
        if ((int)$data['CANCEL_AMT'] <= 0) {
            throw new Exception('취소 금액이 올바르지 않습니다.');
        }
    }

    /**
     * 결제 정보 조회
     */
    private function getPaymentInfo($paymt_mgmt_sno)
    {
        $result = $this->payModel->get_paymt_mgmt_info($paymt_mgmt_sno);
        return $result ? $result[0] : null;
    }

    /**
     * 결제 성공 후 비즈니스 로직 처리
     */
    private function processPaymentSuccess($paymentData, $paymentResult)
    {
        // 구매 상품 관리 테이블 업데이트
        $this->updateBuyEventStatus($paymentData);
        
        // 매출 관리 테이블 업데이트
        $this->updateSalesManagement($paymentData, $paymentResult);
        
        // 회원 상태 업데이트 (필요한 경우)
        $this->updateMemberStatus($paymentData);
        
        log_message('info', '결제 성공 후 처리 완료', [
            'paymt_mgmt_sno' => $paymentResult['paymt_mgmt_sno'] ?? 'N/A',
            'mem_id' => $paymentData['MEM_ID']
        ]);
    }

    /**
     * 결제 취소 후 비즈니스 로직 처리
     */
    private function processPaymentCancel($paymentInfo, $cancelResult)
    {
        // 구매 상품 상태 업데이트
        $this->cancelBuyEventStatus($paymentInfo);
        
        // 환불 관리 테이블 업데이트
        $this->updateRefundManagement($paymentInfo, $cancelResult);
        
        log_message('info', '결제 취소 후 처리 완료', [
            'paymt_mgmt_sno' => $paymentInfo['PAYMT_MGMT_SNO'],
            'cancel_amount' => $cancelResult['cancel_amount'] ?? 'N/A'
        ]);
    }

    /**
     * 구매 상품 상태 업데이트
     */
    private function updateBuyEventStatus($paymentData)
    {
        // 구매 상품 관리 테이블 상태를 '결제완료'로 업데이트
        $updateData = [
            'buy_event_sno' => $paymentData['BUY_EVENT_SNO'] ?? '',
            'comp_cd' => $paymentData['COMP_CD'] ?? '',
            'bcoff_cd' => $paymentData['BCOFF_CD'],
            'event_stat' => '01', // 결제완료
            'event_stat_rson' => '정상결제',
            'mod_id' => 'PAYMENT_SYSTEM',
            'mod_datetm' => date('Y-m-d H:i:s')
        ];
        
        // PayModel의 기존 메서드 활용
        // $this->payModel->update_buy_event_mgmt_trans_end($updateData);
    }

    /**
     * 매출 관리 업데이트
     */
    private function updateSalesManagement($paymentData, $paymentResult)
    {
        // 매출 관리 테이블에 결제 완료 정보 저장
        $salesData = [
            'sales_mgmt_sno' => $this->generateSalesMgmtSno(),
            'paymt_mgmt_sno' => $paymentResult['paymt_mgmt_sno'],
            'buy_event_sno' => $paymentData['BUY_EVENT_SNO'] ?? '',
            'sell_event_sno' => $paymentData['SELL_EVENT_SNO'] ?? '',
            'bcoff_cd' => $paymentData['BCOFF_CD'],
            'mem_sno' => $paymentData['MEM_SNO'],
            'mem_id' => $paymentData['MEM_ID'],
            'mem_nm' => $paymentData['MEM_NM'],
            'sell_event_nm' => $paymentData['SELL_EVENT_NM'],
            'paymt_stat' => '00', // 결제완료
            'paymt_mthd' => $paymentData['PAYMT_MTHD'],
            'paymt_amt' => $paymentData['PAYMT_AMT'],
            'sales_dv' => '01', // 신규매출
            'paymt_chnl' => $paymentData['PAYMT_CHNL'],
            'paymt_van_knd' => 'INICIS',
            'cre_id' => 'PAYMENT_SYSTEM',
            'cre_datetm' => date('Y-m-d H:i:s'),
            'mod_id' => 'PAYMENT_SYSTEM',
            'mod_datetm' => date('Y-m-d H:i:s')
        ];
        
        // PayModel의 기존 메서드 활용
        // $this->payModel->insert_sales_mgmt_tbl($salesData);
    }

    /**
     * 회원 상태 업데이트
     */
    private function updateMemberStatus($paymentData)
    {
        // 필요한 경우 회원 상태 업데이트 로직 구현
        // 예: 가입회원 -> 현재회원으로 상태 변경
    }

    /**
     * 구매 상품 취소 상태 업데이트
     */
    private function cancelBuyEventStatus($paymentInfo)
    {
        // 구매 상품 관리 테이블 상태를 '취소'로 업데이트
        $updateData = [
            'buy_event_sno' => $paymentInfo['BUY_EVENT_SNO'] ?? '',
            'comp_cd' => $paymentInfo['COMP_CD'] ?? '',
            'bcoff_cd' => $paymentInfo['BCOFF_CD'],
            'event_stat' => '99', // 취소
            'event_stat_rson' => '결제취소',
            'mod_id' => 'PAYMENT_SYSTEM',
            'mod_datetm' => date('Y-m-d H:i:s')
        ];
        
        // PayModel의 기존 메서드 활용
        // $this->payModel->update_buy_event_mgmt_trans_end($updateData);
    }

    /**
     * 환불 관리 업데이트
     */
    private function updateRefundManagement($paymentInfo, $cancelResult)
    {
        // 환불 관리 테이블에 취소 정보 저장
        $refundData = [
            'refund_mgmt_sno' => $this->generateRefundMgmtSno(),
            'buy_event_sno' => $paymentInfo['BUY_EVENT_SNO'] ?? '',
            'comp_cd' => $paymentInfo['COMP_CD'] ?? '',
            'bcoff_cd' => $paymentInfo['BCOFF_CD'],
            'mem_sno' => $paymentInfo['MEM_SNO'],
            'mem_id' => $paymentInfo['MEM_ID'],
            'mem_nm' => $paymentInfo['MEM_NM'],
            'sell_event_nm' => $paymentInfo['SELL_EVENT_NM'],
            'use_prod' => '',
            'use_unit' => '',
            'clas_cnt' => 0,
            'buy_amt' => $paymentInfo['PAYMT_AMT'],
            'rerund_amt' => $cancelResult['cancel_amount'] ?? 0,
            'pnalt_amt' => 0,
            'etc_amt' => 0,
            'cre_id' => 'PAYMENT_SYSTEM',
            'cre_datetm' => date('Y-m-d H:i:s'),
            'mod_id' => 'PAYMENT_SYSTEM',
            'mod_datetm' => date('Y-m-d H:i:s')
        ];
        
        // PayModel의 기존 메서드 활용
        // $this->payModel->insert_refund_mgmt_tbl($refundData);
    }

    /**
     * 매출 관리 일련번호 생성
     */
    private function generateSalesMgmtSno()
    {
        return date('Ymd') . sprintf('%010d', mt_rand(1, 9999999999));
    }

    /**
     * 환불 관리 일련번호 생성
     */
    private function generateRefundMgmtSno()
    {
        return date('Ymd') . sprintf('%010d', mt_rand(1, 9999999999));
    }

    // ===========================================================================
    // KCP 결제 처리 메서드들
    // ===========================================================================

    /**
     * KCP 결제 초기화
     */
    public function kcpInit()
    {
        try {
            // POST 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $paymentData = $this->getPaymentDataFromRequest();
            $this->validatePaymentData($paymentData);

            // KCP 서비스 초기화
            $this->kcpService = new KcpPaymentService();
            $this->kcpService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 초기화
            $result = $this->kcpService->initializePayment($paymentData);

            if ($result['status'] === 'success') {
                // 임시 결제 정보를 세션에 저장
                $this->session->set('payment_data', $paymentData);
                $this->session->set('payment_tid', $result['tid']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'tid' => $result['tid'],
                        'tno' => $result['tno'],
                        'payment_url' => $result['payment_url'],
                        'form_data' => $result['form_data'],
                        'next_redirect_pc_url' => $result['next_redirect_pc_url'],
                        'next_redirect_mobile_url' => $result['next_redirect_mobile_url']
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KCP 결제 초기화 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * KCP 결제 완료 처리 (Return URL)
     */
    public function kcpReturn()
    {
        try {
            $verificationData = $this->getKcpVerificationDataFromRequest();
            
            // KCP 서비스 초기화
            $paymentData = $this->session->get('payment_data');
            if (!$paymentData) {
                throw new Exception('결제 세션 정보를 찾을 수 없습니다.');
            }

            $this->kcpService = new KcpPaymentService();
            $this->kcpService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 검증
            $result = $this->kcpService->verifyPayment($verificationData);

            if ($result['status'] === 'success') {
                // 결제 완료 후 비즈니스 로직 처리
                $this->processPaymentSuccess($paymentData, $result['payment_result']);

                // 세션 정리
                $this->session->remove(['payment_data', 'payment_tid']);

                // KCP 성공 페이지로 리다이렉트
                return redirect()->to('/payment/kcp/success')
                    ->with('payment_result', $result['payment_result']);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KCP 결제 완료 처리 실패: ' . $e->getMessage());

            return redirect()->to('/payment/kcp/error')
                ->with('error_message', $e->getMessage());
        }
    }

    /**
     * KCP 결제 취소 처리
     */
    public function kcpCancel()
    {
        try {
            // 취소 요청 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $cancelData = $this->getCancelDataFromRequest();
            $this->validateCancelData($cancelData);

            // 기존 결제 정보 조회
            $paymentInfo = $this->getPaymentInfo($cancelData['PAYMT_MGMT_SNO']);
            if (!$paymentInfo) {
                throw new Exception('결제 정보를 찾을 수 없습니다.');
            }

            // KCP 서비스 초기화
            $this->kcpService = new KcpPaymentService();
            $this->kcpService->initializeWithBranchSettings($paymentInfo['BCOFF_CD']);

            // 결제 취소
            $result = $this->kcpService->cancelPayment($cancelData);

            if ($result['status'] === 'success') {
                // 취소 완료 후 비즈니스 로직 처리
                $this->processPaymentCancel($paymentInfo, $result['cancel_result']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'refund_detail_sno' => $result['refund_detail_sno'],
                        'cancel_amount' => $cancelData['CANCEL_AMT'],
                        'cancel_date' => date('Y-m-d H:i:s')
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KCP 결제 취소 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * KCP 노티피케이션 처리 (Webhook)
     */
    public function kcpNotification()
    {
        try {
            $notificationData = $this->getKcpNotificationDataFromRequest();
            
            // KCP 서비스 초기화 (알림에서 지점 코드 추출)
            $this->kcpService = new KcpPaymentService();
            
            // TID에서 지점 코드 추출
            $tidParts = explode('_', $notificationData['ordr_idxx']);
            $bcoff_cd = $tidParts[1] ?? null;
            
            if (!$bcoff_cd) {
                throw new Exception('유효하지 않은 거래 ID입니다.');
            }

            $this->kcpService->initializeWithBranchSettings($bcoff_cd);

            // 결제 검증
            $result = $this->kcpService->verifyPayment($notificationData);

            if ($result['status'] === 'success') {
                // 알림 처리 로그
                log_message('info', 'KCP 노티피케이션 처리 성공', [
                    'ordr_idxx' => $notificationData['ordr_idxx'],
                    'amount' => $result['payment_result']['amount'] ?? 'N/A'
                ]);

                // KCP에 성공 응답
                return $this->response->setBody('0000');
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KCP 노티피케이션 처리 실패: ' . $e->getMessage());

            // KCP에 실패 응답
            return $this->response->setBody('9999');
        }
    }

    /**
     * KCP 결제 성공 페이지
     */
    public function kcpSuccess()
    {
        $paymentResult = $this->session->getFlashdata('payment_result');
        
        if (!$paymentResult) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'KCP 결제 완료',
            'payment_result' => $paymentResult,
            'pg_provider' => 'KCP'
        ];

        return view('payment/kcp_success', $data);
    }

    /**
     * KCP 결제 오류 페이지
     */
    public function kcpError()
    {
        $errorMessage = $this->session->getFlashdata('error_message') ?? 'KCP 결제 처리 중 오류가 발생했습니다.';
        
        $data = [
            'title' => 'KCP 결제 오류',
            'error_message' => $errorMessage,
            'pg_provider' => 'KCP'
        ];

        return view('payment/kcp_error', $data);
    }

    /**
     * 요청에서 KCP 검증 데이터 추출
     */
    private function getKcpVerificationDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'ordr_idxx' => $request['ordr_idxx'] ?? '',
            'good_mny' => $request['good_mny'] ?? 0,
            'tran_date' => $request['tran_date'] ?? '',
            'enc_info' => $request['enc_info'] ?? '',
            'res_cd' => $request['res_cd'] ?? '',
            'res_msg' => $request['res_msg'] ?? '',
            'app_no' => $request['app_no'] ?? '',
            'card_cd' => $request['card_cd'] ?? '',
            'card_name' => $request['card_name'] ?? ''
        ];
    }

    /**
     * 요청에서 KCP 노티피케이션 데이터 추출
     */
    private function getKcpNotificationDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'ordr_idxx' => $request['ordr_idxx'] ?? '',
            'good_mny' => $request['good_mny'] ?? 0,
            'tran_date' => $request['tran_date'] ?? '',
            'enc_info' => $request['enc_info'] ?? '',
            'res_cd' => $request['res_cd'] ?? '',
            'res_msg' => $request['res_msg'] ?? '',
            'app_no' => $request['app_no'] ?? '',
            'card_cd' => $request['card_cd'] ?? '',
            'card_name' => $request['card_name'] ?? '',
            'noti_type' => $request['noti_type'] ?? ''
        ];
    }

    // ===========================================================================
    // 토스페이먼츠 결제 처리 메서드들
    // ===========================================================================

    /**
     * 토스페이먼츠 결제 초기화
     */
    public function tossInit()
    {
        try {
            // POST 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $paymentData = $this->getPaymentDataFromRequest();
            $this->validatePaymentData($paymentData);

            // 토스페이먼츠 서비스 초기화
            $this->tossService = new TossPaymentService();
            $this->tossService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 초기화
            $result = $this->tossService->initializePayment($paymentData);

            if ($result['status'] === 'success') {
                // 임시 결제 정보를 세션에 저장
                $this->session->set('payment_data', $paymentData);
                $this->session->set('payment_order_id', $result['orderId']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'orderId' => $result['orderId'],
                        'paymentKey' => $result['paymentKey'],
                        'clientKey' => $result['clientKey'],
                        'customerKey' => $result['customerKey'],
                        'successUrl' => $result['successUrl'],
                        'failUrl' => $result['failUrl'],
                        'amount' => $result['amount'],
                        'orderName' => $result['orderName'],
                        'customerEmail' => $result['customerEmail'],
                        'customerName' => $result['customerName'],
                        'customerMobilePhone' => $result['customerMobilePhone'],
                        'paymentMethods' => $result['paymentMethods'],
                        'widgetUrl' => $result['widgetUrl']
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', '토스페이먼츠 결제 초기화 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 토스페이먼츠 결제 완료 처리 (Return URL)
     */
    public function tossReturn()
    {
        try {
            $verificationData = $this->getTossVerificationDataFromRequest();
            
            // 토스페이먼츠 서비스 초기화
            $paymentData = $this->session->get('payment_data');
            if (!$paymentData) {
                throw new Exception('결제 세션 정보를 찾을 수 없습니다.');
            }

            $this->tossService = new TossPaymentService();
            $this->tossService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 검증
            $result = $this->tossService->verifyPayment($verificationData);

            if ($result['status'] === 'success') {
                // 결제 완료 후 비즈니스 로직 처리
                $this->processPaymentSuccess($paymentData, $result['payment_result']);

                // 세션 정리
                $this->session->remove(['payment_data', 'payment_order_id']);

                // 토스페이먼츠 성공 페이지로 리다이렉트
                return redirect()->to('/payment/toss/success')
                    ->with('payment_result', $result['payment_result']);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', '토스페이먼츠 결제 완료 처리 실패: ' . $e->getMessage());

            return redirect()->to('/payment/toss/error')
                ->with('error_message', $e->getMessage());
        }
    }

    /**
     * 토스페이먼츠 결제 실패 처리 (Fail URL)
     */
    public function tossFail()
    {
        try {
            $failData = $this->getTossFailDataFromRequest();
            
            // 실패 정보 로깅
            log_message('info', '토스페이먼츠 결제 실패', [
                'code' => $failData['code'] ?? 'N/A',
                'message' => $failData['message'] ?? 'N/A',
                'orderId' => $failData['orderId'] ?? 'N/A'
            ]);

            $errorMessage = $failData['message'] ?? '결제 처리 중 오류가 발생했습니다.';
            
            return redirect()->to('/payment/toss/error')
                ->with('error_message', $errorMessage)
                ->with('error_code', $failData['code'] ?? 'UNKNOWN_ERROR');

        } catch (Exception $e) {
            log_message('error', '토스페이먼츠 결제 실패 처리 오류: ' . $e->getMessage());

            return redirect()->to('/payment/toss/error')
                ->with('error_message', '결제 처리 중 오류가 발생했습니다.');
        }
    }

    /**
     * 토스페이먼츠 결제 취소 처리
     */
    public function tossCancel()
    {
        try {
            // 취소 요청 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $cancelData = $this->getCancelDataFromRequest();
            $this->validateCancelData($cancelData);

            // 기존 결제 정보 조회
            $paymentInfo = $this->getPaymentInfo($cancelData['PAYMT_MGMT_SNO']);
            if (!$paymentInfo) {
                throw new Exception('결제 정보를 찾을 수 없습니다.');
            }

            // 토스페이먼츠 서비스 초기화
            $this->tossService = new TossPaymentService();
            $this->tossService->initializeWithBranchSettings($paymentInfo['BCOFF_CD']);

            // 결제 취소
            $result = $this->tossService->cancelPayment($cancelData);

            if ($result['status'] === 'success') {
                // 취소 완료 후 비즈니스 로직 처리
                $this->processPaymentCancel($paymentInfo, $result['cancel_result']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'refund_detail_sno' => $result['refund_detail_sno'],
                        'cancel_amount' => $cancelData['CANCEL_AMT'],
                        'cancel_date' => date('Y-m-d H:i:s'),
                        'cancel_reason' => $cancelData['CANCEL_RSON'] ?? ''
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', '토스페이먼츠 결제 취소 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 토스페이먼츠 웹훅 노티피케이션 처리
     */
    public function tossNotification()
    {
        try {
            // 웹훅 서명 검증
            $payload = $this->request->getBody();
            $signature = $this->request->getHeaderLine('Toss-Signature');
            
            if (empty($signature)) {
                throw new Exception('웹훅 서명이 없습니다.');
            }

            // 임시로 지점 코드를 세션에서 가져오거나 기본값 사용
            // 실제 구현에서는 웹훅 데이터에서 지점 정보를 추출해야 함
            $this->tossService = new TossPaymentService();
            // $this->tossService->initializeWithBranchSettings('DEFAULT');

            // 서명 검증
            if (!$this->tossService->verifyWebhookSignature($payload, $signature)) {
                throw new Exception('웹훅 서명 검증 실패');
            }

            $notificationData = json_decode($payload, true);
            if (!$notificationData) {
                throw new Exception('웹훅 데이터 파싱 실패');
            }

            // 결제 상태에 따른 처리
            switch ($notificationData['eventType']) {
                case 'PAYMENT_STATUS_CHANGED':
                    $this->processPaymentStatusChange($notificationData);
                    break;
                    
                case 'VIRTUAL_ACCOUNT_ISSUED':
                    $this->processVirtualAccountIssued($notificationData);
                    break;
                    
                default:
                    log_message('info', '처리되지 않은 토스페이먼츠 웹훅 이벤트: ' . $notificationData['eventType']);
            }

            // 토스페이먼츠에 성공 응답
            return $this->response->setStatusCode(200)->setJSON(['status' => 'success']);

        } catch (Exception $e) {
            log_message('error', '토스페이먼츠 웹훅 처리 실패: ' . $e->getMessage());

            // 토스페이먼츠에 실패 응답
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * 토스페이먼츠 결제 성공 페이지
     */
    public function tossSuccess()
    {
        $paymentResult = $this->session->getFlashdata('payment_result');
        
        if (!$paymentResult) {
            return redirect()->to('/');
        }

        $data = [
            'title' => '토스페이먼츠 결제 완료',
            'payment_result' => $paymentResult,
            'pg_provider' => 'TOSS'
        ];

        return view('payment/toss_success', $data);
    }

    /**
     * 토스페이먼츠 결제 오류 페이지
     */
    public function tossError()
    {
        $errorMessage = $this->session->getFlashdata('error_message') ?? '토스페이먼츠 결제 처리 중 오류가 발생했습니다.';
        $errorCode = $this->session->getFlashdata('error_code') ?? 'UNKNOWN_ERROR';
        
        $data = [
            'title' => '토스페이먼츠 결제 오류',
            'error_message' => $errorMessage,
            'error_code' => $errorCode,
            'pg_provider' => 'TOSS'
        ];

        return view('payment/toss_error', $data);
    }

    /**
     * 토스페이먼츠 Payment Widget 설정 반환
     */
    public function tossWidgetConfig()
    {
        try {
            if (!$this->request->isAJAX()) {
                throw new Exception('잘못된 요청입니다.');
            }

            $requestData = $this->request->getJSON(true);
            $orderId = $requestData['orderId'] ?? '';
            $amount = $requestData['amount'] ?? 0;
            $orderName = $requestData['orderName'] ?? '';

            if (empty($orderId) || empty($amount) || empty($orderName)) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }

            // 세션에서 결제 데이터 확인
            $paymentData = $this->session->get('payment_data');
            if (!$paymentData) {
                throw new Exception('결제 세션이 만료되었습니다.');
            }

            $this->tossService = new TossPaymentService();
            $this->tossService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            $config = $this->tossService->getWidgetConfig($orderId, $amount, $orderName);

            return $this->response->setJSON([
                'status' => 'success',
                'config' => $config
            ]);

        } catch (Exception $e) {
            log_message('error', '토스페이먼츠 위젯 설정 요청 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 요청에서 토스페이먼츠 검증 데이터 추출
     */
    private function getTossVerificationDataFromRequest()
    {
        $request = $this->request->getGet();
        
        return [
            'orderId' => $request['orderId'] ?? '',
            'paymentKey' => $request['paymentKey'] ?? '',
            'amount' => $request['amount'] ?? 0
        ];
    }

    /**
     * 요청에서 토스페이먼츠 실패 데이터 추출
     */
    private function getTossFailDataFromRequest()
    {
        $request = $this->request->getGet();
        
        return [
            'code' => $request['code'] ?? '',
            'message' => $request['message'] ?? '',
            'orderId' => $request['orderId'] ?? ''
        ];
    }

    /**
     * 결제 상태 변경 처리
     */
    private function processPaymentStatusChange($notificationData)
    {
        $paymentData = $notificationData['data'] ?? [];
        $orderId = $paymentData['orderId'] ?? '';
        $status = $paymentData['status'] ?? '';

        log_message('info', '토스페이먼츠 결제 상태 변경', [
            'orderId' => $orderId,
            'status' => $status,
            'paymentKey' => $paymentData['paymentKey'] ?? 'N/A'
        ]);

        // 결제 상태에 따른 추가 처리 로직 구현
        switch ($status) {
            case 'DONE':
                // 결제 완료 처리
                break;
            case 'CANCELED':
                // 결제 취소 처리
                break;
            case 'PARTIAL_CANCELED':
                // 부분 취소 처리
                break;
            case 'FAILED':
                // 결제 실패 처리
                break;
        }
    }

    /**
     * 가상계좌 발급 처리
     */
    private function processVirtualAccountIssued($notificationData)
    {
        $paymentData = $notificationData['data'] ?? [];
        $orderId = $paymentData['orderId'] ?? '';
        $virtualAccount = $paymentData['virtualAccount'] ?? [];

        log_message('info', '토스페이먼츠 가상계좌 발급', [
            'orderId' => $orderId,
            'accountNumber' => $virtualAccount['accountNumber'] ?? 'N/A',
            'bankCode' => $virtualAccount['bankCode'] ?? 'N/A',
            'dueDate' => $virtualAccount['dueDate'] ?? 'N/A'
        ]);

        // 가상계좌 정보 저장 및 알림 발송 로직 구현
    }

    // ##########################################################################
    // ============================================================================
    //                                    [ VAN 결제 처리 메서드들 ]
    // ============================================================================
    // ##########################################################################

    // ===========================================================================
    // KICC VAN 결제 처리 메서드들
    // ===========================================================================

    /**
     * KICC VAN 결제 초기화
     */
    public function kiccVanInit()
    {
        try {
            // POST 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $paymentData = $this->getPaymentDataFromRequest();
            $this->validatePaymentData($paymentData);

            // KICC VAN 서비스 초기화
            $this->kiccVanService = new KiccVanService();
            $this->kiccVanService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // VAN 결제 초기화
            $result = $this->kiccVanService->initializePayment($paymentData);

            if ($result['status'] === 'success') {
                // 임시 결제 정보를 세션에 저장
                $this->session->set('van_payment_data', $paymentData);
                $this->session->set('van_transaction_id', $result['van_txn_id']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'van_txn_id' => $result['van_txn_id'],
                        'terminal_id' => $result['terminal_id'],
                        'approval_no' => $result['approval_no'],
                        'van_response_code' => $result['van_response_code'],
                        'payment_form_data' => $result['payment_form_data'],
                        'redirect_url' => $result['redirect_url']
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KICC VAN 결제 초기화 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * KICC VAN 결제 완료 처리 (Return URL)
     */
    public function kiccVanReturn()
    {
        try {
            $verificationData = $this->getKiccVanVerificationDataFromRequest();
            
            // KICC VAN 서비스 초기화
            $paymentData = $this->session->get('van_payment_data');
            if (!$paymentData) {
                throw new Exception('VAN 결제 세션 정보를 찾을 수 없습니다.');
            }

            $this->kiccVanService = new KiccVanService();
            $this->kiccVanService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 검증
            $result = $this->kiccVanService->verifyPayment($verificationData);

            if ($result['status'] === 'success') {
                // 결제 완료 후 비즈니스 로직 처리
                $this->processPaymentSuccess($paymentData, $result['payment_result']);

                // 세션 정리
                $this->session->remove(['van_payment_data', 'van_transaction_id']);

                // 성공 페이지로 리다이렉트
                return redirect()->to('/payment/van/kicc/success')
                    ->with('payment_result', $result['payment_result']);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KICC VAN 결제 완료 처리 실패: ' . $e->getMessage());

            return redirect()->to('/payment/van/kicc/error')
                ->with('error_message', $e->getMessage());
        }
    }

    /**
     * KICC VAN 결제 취소 처리
     */
    public function kiccVanCancel()
    {
        try {
            // 취소 요청 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $cancelData = $this->getCancelDataFromRequest();
            $this->validateCancelData($cancelData);

            // 기존 결제 정보 조회
            $paymentInfo = $this->getPaymentInfo($cancelData['PAYMT_MGMT_SNO']);
            if (!$paymentInfo) {
                throw new Exception('결제 정보를 찾을 수 없습니다.');
            }

            // KICC VAN 서비스 초기화
            $this->kiccVanService = new KiccVanService();
            $this->kiccVanService->initializeWithBranchSettings($paymentInfo['BCOFF_CD']);

            // 결제 취소
            $result = $this->kiccVanService->cancelPayment($cancelData);

            if ($result['status'] === 'success') {
                // 취소 완료 후 비즈니스 로직 처리
                $this->processPaymentCancel($paymentInfo, $result['cancel_result']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'refund_detail_sno' => $result['refund_detail_sno'],
                        'cancel_amount' => $cancelData['CANCEL_AMT'],
                        'cancel_date' => date('Y-m-d H:i:s'),
                        'van_provider' => 'KICC'
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KICC VAN 결제 취소 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * KICC VAN 노티피케이션 처리 (Callback)
     */
    public function kiccVanNotification()
    {
        try {
            $notificationData = $this->getKiccVanNotificationDataFromRequest();
            
            // KICC VAN 서비스 초기화 (알림에서 지점 코드 추출)
            $this->kiccVanService = new KiccVanService();
            
            // VAN TXN ID에서 지점 코드 추출
            $vanTxnId = $notificationData['van_txn_id'] ?? '';
            $bcoff_cd = $this->extractBranchCodeFromVanTxnId($vanTxnId, 'KICC');
            
            if (!$bcoff_cd) {
                throw new Exception('유효하지 않은 VAN 거래 ID입니다.');
            }

            $this->kiccVanService->initializeWithBranchSettings($bcoff_cd);

            // 결제 검증
            $result = $this->kiccVanService->verifyPayment($notificationData);

            if ($result['status'] === 'success') {
                // 알림 처리 로그
                log_message('info', 'KICC VAN 노티피케이션 처리 성공', [
                    'van_txn_id' => $vanTxnId,
                    'amount' => $result['payment_result']['amount'] ?? 'N/A'
                ]);

                // KICC VAN에 성공 응답
                return $this->response->setBody('OK');
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KICC VAN 노티피케이션 처리 실패: ' . $e->getMessage());

            // KICC VAN에 실패 응답
            return $this->response->setBody('FAIL');
        }
    }

    /**
     * KICC VAN 거래 조회
     */
    public function kiccVanInquiry()
    {
        try {
            if (!$this->request->isAJAX()) {
                throw new Exception('잘못된 요청입니다.');
            }

            $vanTxnId = $this->request->getPost('van_txn_id');
            $bcoff_cd = $this->request->getPost('bcoff_cd') ?? $this->extractBranchCodeFromVanTxnId($vanTxnId, 'KICC');

            if (empty($vanTxnId) || empty($bcoff_cd)) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }

            $this->kiccVanService = new KiccVanService();
            $this->kiccVanService->initializeWithBranchSettings($bcoff_cd);

            $result = $this->kiccVanService->inquireTransaction($vanTxnId);

            return $this->response->setJSON([
                'status' => $result['status'],
                'data' => $result
            ]);

        } catch (Exception $e) {
            log_message('error', 'KICC VAN 거래조회 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * KICC VAN 정산 보고서 조회
     */
    public function kiccVanSettlement()
    {
        try {
            if (!$this->request->isAJAX()) {
                throw new Exception('잘못된 요청입니다.');
            }

            $settleDate = $this->request->getPost('settle_date');
            $bcoff_cd = $this->request->getPost('bcoff_cd');

            if (empty($settleDate) || empty($bcoff_cd)) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }

            $this->kiccVanService = new KiccVanService();
            $this->kiccVanService->initializeWithBranchSettings($bcoff_cd);

            $result = $this->kiccVanService->getSettlementReport($settleDate);

            return $this->response->setJSON([
                'status' => $result['status'],
                'data' => $result
            ]);

        } catch (Exception $e) {
            log_message('error', 'KICC VAN 정산조회 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // ===========================================================================
    // Nice VAN 결제 처리 메서드들
    // ===========================================================================

    /**
     * Nice VAN 결제 초기화
     */
    public function niceVanInit()
    {
        try {
            // POST 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $paymentData = $this->getPaymentDataFromRequest();
            $this->validatePaymentData($paymentData);

            // Nice VAN 서비스 초기화
            $this->niceVanService = new NiceVanService();
            $this->niceVanService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // VAN 결제 초기화
            $result = $this->niceVanService->initializePayment($paymentData);

            if ($result['status'] === 'success') {
                // 임시 결제 정보를 세션에 저장
                $this->session->set('van_payment_data', $paymentData);
                $this->session->set('van_order_id', $result['van_order_id']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'van_order_id' => $result['van_order_id'],
                        'nice_txn_id' => $result['nice_txn_id'],
                        'auth_token' => $result['auth_token'],
                        'next_redirect_url' => $result['next_redirect_url'],
                        'payment_form_data' => $result['payment_form_data'],
                        'van_response_code' => $result['van_response_code']
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'Nice VAN 결제 초기화 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Nice VAN 결제 완료 처리 (Return URL)
     */
    public function niceVanReturn()
    {
        try {
            $verificationData = $this->getNiceVanVerificationDataFromRequest();
            
            // Nice VAN 서비스 초기화
            $paymentData = $this->session->get('van_payment_data');
            if (!$paymentData) {
                throw new Exception('VAN 결제 세션 정보를 찾을 수 없습니다.');
            }

            $this->niceVanService = new NiceVanService();
            $this->niceVanService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 검증
            $result = $this->niceVanService->verifyPayment($verificationData);

            if ($result['status'] === 'success') {
                // 결제 완료 후 비즈니스 로직 처리
                $this->processPaymentSuccess($paymentData, $result['payment_result']);

                // 세션 정리
                $this->session->remove(['van_payment_data', 'van_order_id']);

                // 성공 페이지로 리다이렉트
                return redirect()->to('/payment/van/nice/success')
                    ->with('payment_result', $result['payment_result']);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'Nice VAN 결제 완료 처리 실패: ' . $e->getMessage());

            return redirect()->to('/payment/van/nice/error')
                ->with('error_message', $e->getMessage());
        }
    }

    /**
     * Nice VAN 결제 취소 처리
     */
    public function niceVanCancel()
    {
        try {
            // 취소 요청 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $cancelData = $this->getCancelDataFromRequest();
            $this->validateCancelData($cancelData);

            // 기존 결제 정보 조회
            $paymentInfo = $this->getPaymentInfo($cancelData['PAYMT_MGMT_SNO']);
            if (!$paymentInfo) {
                throw new Exception('결제 정보를 찾을 수 없습니다.');
            }

            // Nice VAN 서비스 초기화
            $this->niceVanService = new NiceVanService();
            $this->niceVanService->initializeWithBranchSettings($paymentInfo['BCOFF_CD']);

            // 결제 취소
            $result = $this->niceVanService->cancelPayment($cancelData);

            if ($result['status'] === 'success') {
                // 취소 완료 후 비즈니스 로직 처리
                $this->processPaymentCancel($paymentInfo, $result['cancel_result']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'refund_detail_sno' => $result['refund_detail_sno'],
                        'cancel_amount' => $cancelData['CANCEL_AMT'],
                        'cancel_date' => date('Y-m-d H:i:s'),
                        'van_provider' => 'NICE'
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'Nice VAN 결제 취소 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Nice VAN 배치 결제 처리
     */
    public function niceVanBatch()
    {
        try {
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $batchData = $this->request->getPost('batch_data');
            $bcoff_cd = $this->request->getPost('bcoff_cd');

            if (empty($batchData) || empty($bcoff_cd)) {
                throw new Exception('필수 파라미터가 누락되었습니다.');
            }

            $this->niceVanService = new NiceVanService();
            $this->niceVanService->initializeWithBranchSettings($bcoff_cd);

            $result = $this->niceVanService->processBatchPayments($batchData);

            return $this->response->setJSON([
                'status' => $result['status'],
                'data' => $result
            ]);

        } catch (Exception $e) {
            log_message('error', 'Nice VAN 배치처리 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // ===========================================================================
    // KSNET VAN 결제 처리 메서드들
    // ===========================================================================

    /**
     * KSNET VAN 결제 초기화
     */
    public function ksnetVanInit()
    {
        try {
            // POST 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $paymentData = $this->getPaymentDataFromRequest();
            $this->validatePaymentData($paymentData);

            // KSNET VAN 서비스 초기화
            $this->ksnetVanService = new KsnetVanService();
            $this->ksnetVanService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // VAN 결제 초기화
            $result = $this->ksnetVanService->initializePayment($paymentData);

            if ($result['status'] === 'success') {
                // 임시 결제 정보를 세션에 저장
                $this->session->set('van_payment_data', $paymentData);
                $this->session->set('van_transaction_seq', $result['van_transaction_seq']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'van_transaction_seq' => $result['van_transaction_seq'],
                        'auth_number' => $result['auth_number'],
                        'van_transaction_id' => $result['van_transaction_id'],
                        'terminal_id' => $result['terminal_id'],
                        'store_id' => $result['store_id'],
                        'approval_datetime' => $result['approval_datetime'],
                        'payment_form_data' => $result['payment_form_data'],
                        'van_response_code' => $result['van_response_code'],
                        'redirect_url' => $result['redirect_url']
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KSNET VAN 결제 초기화 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * KSNET VAN 결제 완료 처리 (Return URL)
     */
    public function ksnetVanReturn()
    {
        try {
            $verificationData = $this->getKsnetVanVerificationDataFromRequest();
            
            // KSNET VAN 서비스 초기화
            $paymentData = $this->session->get('van_payment_data');
            if (!$paymentData) {
                throw new Exception('VAN 결제 세션 정보를 찾을 수 없습니다.');
            }

            $this->ksnetVanService = new KsnetVanService();
            $this->ksnetVanService->initializeWithBranchSettings($paymentData['BCOFF_CD']);

            // 결제 검증
            $result = $this->ksnetVanService->verifyPayment($verificationData);

            if ($result['status'] === 'success') {
                // 결제 완료 후 비즈니스 로직 처리
                $this->processPaymentSuccess($paymentData, $result['payment_result']);

                // 세션 정리
                $this->session->remove(['van_payment_data', 'van_transaction_seq']);

                // 성공 페이지로 리다이렉트
                return redirect()->to('/payment/van/ksnet/success')
                    ->with('payment_result', $result['payment_result']);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KSNET VAN 결제 완료 처리 실패: ' . $e->getMessage());

            return redirect()->to('/payment/van/ksnet/error')
                ->with('error_message', $e->getMessage());
        }
    }

    /**
     * KSNET VAN 결제 취소 처리
     */
    public function ksnetVanCancel()
    {
        try {
            // 취소 요청 데이터 검증
            if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
                throw new Exception('잘못된 요청입니다.');
            }

            $cancelData = $this->getCancelDataFromRequest();
            $this->validateCancelData($cancelData);

            // 기존 결제 정보 조회
            $paymentInfo = $this->getPaymentInfo($cancelData['PAYMT_MGMT_SNO']);
            if (!$paymentInfo) {
                throw new Exception('결제 정보를 찾을 수 없습니다.');
            }

            // KSNET VAN 서비스 초기화
            $this->ksnetVanService = new KsnetVanService();
            $this->ksnetVanService->initializeWithBranchSettings($paymentInfo['BCOFF_CD']);

            // 결제 취소
            $result = $this->ksnetVanService->cancelPayment($cancelData);

            if ($result['status'] === 'success') {
                // 취소 완료 후 비즈니스 로직 처리
                $this->processPaymentCancel($paymentInfo, $result['cancel_result']);

                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'refund_detail_sno' => $result['refund_detail_sno'],
                        'cancel_amount' => $cancelData['CANCEL_AMT'],
                        'cancel_date' => date('Y-m-d H:i:s'),
                        'van_provider' => 'KSNET'
                    ]
                ]);
            } else {
                throw new Exception($result['message']);
            }

        } catch (Exception $e) {
            log_message('error', 'KSNET VAN 결제 취소 실패: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // ===========================================================================
    // VAN 공통 처리 메서드들
    // ===========================================================================

    /**
     * VAN 성공 페이지 (공통)
     */
    public function vanSuccess($provider = null)
    {
        $paymentResult = $this->session->getFlashdata('payment_result');
        
        if (!$paymentResult) {
            return redirect()->to('/');
        }

        $data = [
            'title' => strtoupper($provider) . ' VAN 결제 완료',
            'payment_result' => $paymentResult,
            'van_provider' => strtoupper($provider)
        ];

        return view('payment/van_success', $data);
    }

    /**
     * VAN 오류 페이지 (공통)
     */
    public function vanError($provider = null)
    {
        $errorMessage = $this->session->getFlashdata('error_message') ?? strtoupper($provider) . ' VAN 결제 처리 중 오류가 발생했습니다.';
        
        $data = [
            'title' => strtoupper($provider) . ' VAN 결제 오류',
            'error_message' => $errorMessage,
            'van_provider' => strtoupper($provider)
        ];

        return view('payment/van_error', $data);
    }

    // ===========================================================================
    // VAN 전용 헬퍼 메서드들
    // ===========================================================================

    /**
     * KICC VAN 검증 데이터 추출
     */
    private function getKiccVanVerificationDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'van_txn_id' => $request['van_txn_id'] ?? '',
            'approval_no' => $request['approval_no'] ?? '',
            'van_code' => $request['van_code'] ?? '',
            'amount' => $request['amount'] ?? 0,
            'signature' => $request['signature'] ?? ''
        ];
    }

    /**
     * KICC VAN 노티피케이션 데이터 추출
     */
    private function getKiccVanNotificationDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'van_txn_id' => $request['van_txn_id'] ?? '',
            'approval_no' => $request['approval_no'] ?? '',
            'van_code' => $request['van_code'] ?? '',
            'amount' => $request['amount'] ?? 0,
            'signature' => $request['signature'] ?? '',
            'noti_type' => $request['noti_type'] ?? ''
        ];
    }

    /**
     * Nice VAN 검증 데이터 추출
     */
    private function getNiceVanVerificationDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'Moid' => $request['Moid'] ?? '',
            'TxnId' => $request['TxnId'] ?? '',
            'Amt' => $request['Amt'] ?? 0,
            'AuthToken' => $request['AuthToken'] ?? '',
            'SignData' => $request['SignData'] ?? '',
            'ResultCode' => $request['ResultCode'] ?? '',
            'ResultMsg' => $request['ResultMsg'] ?? ''
        ];
    }

    /**
     * KSNET VAN 검증 데이터 추출
     */
    private function getKsnetVanVerificationDataFromRequest()
    {
        $request = $this->request->getPost();
        
        return [
            'transaction_seq' => $request['transaction_seq'] ?? '',
            'auth_number' => $request['auth_number'] ?? '',
            'van_transaction_id' => $request['van_transaction_id'] ?? '',
            'amount' => $request['amount'] ?? 0,
            'hash_data' => $request['hash_data'] ?? '',
            'result_code' => $request['result_code'] ?? '',
            'result_message' => $request['result_message'] ?? ''
        ];
    }

    /**
     * VAN 거래 ID에서 지점 코드 추출
     */
    private function extractBranchCodeFromVanTxnId($vanTxnId, $provider)
    {
        // VAN 거래 ID 형식에 따라 지점 코드 추출
        // 예: KICC{BCOFF_CD}P{YYYYMMDDHHMMSS}{NNNN}
        $providerLength = strlen($provider);
        
        if (strlen($vanTxnId) > $providerLength + 3) {
            // 지점 코드는 보통 3자리
            return substr($vanTxnId, $providerLength, 3);
        }
        
        return null;
    }
}