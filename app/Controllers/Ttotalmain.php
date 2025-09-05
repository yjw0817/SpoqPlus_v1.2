<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\MemModel;
use App\Models\EventModel;
use App\Libraries\Event_lib;
use App\Models\LockrModel;
use App\Libraries\Attd_lib;
use App\Libraries\Clas_lib;
use App\Libraries\Refund_lib;
use App\Libraries\Trans_lib;
use App\Models\AttdModel;
use App\Libraries\Mem_lib;
use App\Libraries\Domcy_lib;
use App\Models\MemoModel;
use App\Models\SalesModel;
use App\Models\HistModel;
use App\Models\TransModel;
use App\Models\FaceRecognitionModel;

class Ttotalmain extends MainTchrController
{
    public function top_search_proc()
    {
        $memModel = new MemModel();
        
        $postVar = $this->request->getPost();
        
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $mdata['mem_nm'] = $postVar['sv'];
        
        $mem_list = $memModel->search_like_mem_nm($mdata);
        $SpoQ_def = SpoqDef();
        $search_mem_list = array();
        $mem_i = 0;
        foreach($mem_list as $r):
	        $search_mem_list[$mem_i] = $r;
	        $search_mem_list[$mem_i]['MEM_STAT_NM'] = $SpoQ_def['MEM_STAT'][$r['MEM_STAT']];
	        $search_mem_list[$mem_i]['MEM_GENDR_NM'] = $SpoQ_def['MEM_GENDR'][$r['MEM_GENDR']];
	        $mem_i++;
        endforeach;
        
        $return_json['result'] = 'true';
        $return_json['search_mem_list'] = $search_mem_list;
        
        return json_encode($return_json);
    }

    /**
     * 회원정보 수정 처리
     */
    public function ajax_mgr_modify_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost(); // re_pass
        $model = new MemModel();
        $nn_now = new Time('now');
        $postVar = $this->request->getPost();
        
        $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        $data['mem_id'] = $postVar['menu_mem_id'];
        $data['mem_nm'] = $postVar['menu_mem_nm'];
        $data['mem_telno'] = put_num($postVar['menu_mem_telno']);

        $data['mem_sno'] = $postVar['menu_mem_sno'];
        $data['bthday'] = put_num($postVar['menu_bthday']);
        $data['mem_gendr'] = isset($postVar['menu_mem_gendr']) ? $postVar['menu_mem_gendr'] : "";
        
        $re_phone_num = $this->enc_phone(put_num($postVar['menu_mem_telno']));
        $data['mem_telno_enc'] = $re_phone_num['enc'];
        $data['mem_telno_mask'] = $re_phone_num['mask'];
        $data['mem_telno_short'] = $re_phone_num['short'];
		$data['mem_telno_enc2'] = $re_phone_num['enc2'];
        
        $data['mem_addr'] = $postVar['menu_mem_addr'];
        
        
        $data['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $data['mod_datetm'] = $nn_now;
        


        // 📸 웹캠 이미지 저장 처리
        if (isset($postVar['menu_captured_photo']) && !empty($postVar['menu_captured_photo'])) {
            $base64_string = $postVar['menu_captured_photo'];
        
            // 데이터 파싱
            if (strpos($base64_string, 'base64,') !== false) {
                $base64_string = explode(',', $base64_string)[1];
            }
        
            $image_data = base64_decode($base64_string);
            $user_id = $postVar['menu_mem_id'];
        
            $upload_dir = FCPATH . 'upload/photo/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
        
            // 파일 경로 설정 (JPG로 변경)
            $file_path = $upload_dir . 'mngr_' . $user_id . '.jpg';
            $thumb_path = $upload_dir . 'mngr_' . $user_id . '_thum.jpg';
        
            // 원본 이미지 저장 (JPG)
            $src = imagecreatefromstring($image_data);
            imagejpeg($src, $file_path, 85); // 85% 품질로 저장
        
            // 썸네일 생성
            $width = imagesx($src);
            $height = imagesy($src);
            $thumb_width = 120;
            $thumb_height = intval(($thumb_width / $width) * $height);
        
            $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
            imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
            imagejpeg($thumb, $thumb_path, 85); // 썸네일도 JPG로 저장
        
            // 리소스 해제
            imagedestroy($src);
            imagedestroy($thumb);
        
            // DB 경로 반영
            $data['mem_main_img'] = '/upload/photo/mngr_' . $user_id . '.jpg';
            $data['mem_thumb_img'] = '/upload/photo/mngr_' . $user_id . '_thum.jpg';
        }

        $model->update_bcoff_info($data);
        $model->update_bcoff_accept_info($data);
        
        if ($postVar['menu_mem_pwd'])
        {
            $pdata['mem_pwd'] = $this->enc_pass($postVar['menu_mem_pwd']);
            $pdata['mem_id'] = $postVar['menu_mem_id'];
            $pdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $pdata['mem_sno'] = $postVar['menu_mem_sno'];
            $pdata['mod_datetm'] = $nn_now;
            $model->update_bcoff_pwd($pdata);
            $model->update_bcoff_accept_pwd($pdata);
        }
        
        $model->update_mem_main_info($data);
        $model->update_mem_info_detl_tbl($data);
        
        // 회원 정보를 가져온다.
        $get_mem_info = $model->get_mem_info_mem_id($data);
        $mem_info = $get_mem_info[0];
       
        $_SESSION['mem_info']= $mem_info;
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 회원정보 수정 처리
     */
    public function ajax_mem_modify_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost(); // re_pass
        $model = new MemModel();
        $nn_now = new Time('now');
        // ===========================================================================
        // Processs
        // ===========================================================================
        
        $data['mem_sno'] = $postVar['mem_sno'];
        $data['mem_nm'] = $postVar['mem_nm'];
        $data['bthday'] = put_num($postVar['bthday']);
        $data['mem_gendr'] = $postVar['mem_gendr'];
        $data['mem_telno'] = put_num($postVar['mem_telno']);
        
        $re_phone_num = $this->enc_phone(put_num($postVar['mem_telno']));
        $data['mem_telno_enc'] = $re_phone_num['enc'];
        $data['mem_telno_mask'] = $re_phone_num['mask'];
        $data['mem_telno_short'] = $re_phone_num['short'];
		$data['mem_telno_enc2'] = $re_phone_num['enc2'];
        
        $data['mem_addr'] = $postVar['mem_addr'];
        $data['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $data['mod_datetm'] = $nn_now;
        


        // 📸 웹캠 이미지 저장 처리
        if (isset($postVar['captured_photo']) && !empty($postVar['captured_photo'])) {
            $base64_string = $postVar['captured_photo'];
        
            // 데이터 파싱
            if (strpos($base64_string, 'base64,') !== false) {
                $base64_string = explode(',', $base64_string)[1];
            }
        
            $image_data = base64_decode($base64_string);
            $user_id = $postVar['mem_id'];
        
            $upload_dir = FCPATH . 'upload/photo/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
        
            // 파일 경로 설정 (JPG로 변경)
            $file_path = $upload_dir . 'member_' . $user_id . '.jpg';
            $thumb_path = $upload_dir . 'member_' . $user_id . '_thum.jpg';
        
            // 원본 이미지 저장 (JPG)
            $src = imagecreatefromstring($image_data);
            imagejpeg($src, $file_path, 85); // 85% 품질로 저장
        
            // 썸네일 생성
            $width = imagesx($src);
            $height = imagesy($src);
            $thumb_width = 120;
            $thumb_height = intval(($thumb_width / $width) * $height);
        
            $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
            imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
            imagejpeg($thumb, $thumb_path, 85); // 썸네일도 JPG로 저장
        
            // 리소스 해제
            imagedestroy($src);
            imagedestroy($thumb);
        
            // DB 경로 반영
            $data['mem_main_img'] = '/upload/photo/member_' . $user_id . '.jpg';
            $data['mem_thumb_img'] = '/upload/photo/member_' . $user_id . '_thum.jpg';
        }

        $model->update_mem_main_info($data);
        $model->update_mem_info_detl_tbl($data);
        
        // 🔍 얼굴 인식 데이터 저장
        if (isset($postVar['face_encoding_data']) && !empty($postVar['face_encoding_data'])) {
            log_message('info', '🔍 얼굴 데이터 처리 시작');
            log_message('info', '📊 받은 얼굴 데이터: ' . $postVar['face_encoding_data']);
            log_message('info', '👓 안경 검출: ' . ($postVar['glasses_detected'] ?? '미제공'));
            log_message('info', '⭐ 품질 점수: ' . ($postVar['quality_score'] ?? '미제공'));
            
            $faceModel = new FaceRecognitionModel();
            
            try {
                $faceData = json_decode($postVar['face_encoding_data'], true);
                log_message('info', '📝 JSON 디코딩 결과: ' . ($faceData ? 'SUCCESS' : 'FAILED'));
                
                if ($faceData && isset($faceData['face_encoding'])) {
                    log_message('info', '✅ face_encoding 필드 존재');
                    log_message('info', '📏 face_encoding 타입: ' . gettype($faceData['face_encoding']));
                    log_message('info', '📏 face_encoding 크기: ' . (is_array($faceData['face_encoding']) ? count($faceData['face_encoding']) : 'NOT_ARRAY'));
                    
                    if (is_array($faceData['face_encoding']) && count($faceData['face_encoding']) > 0) {
                        log_message('info', '📋 face_encoding 샘플: ' . implode(',', array_slice($faceData['face_encoding'], 0, 5)));
                    }
                    
                    // Python 얼굴 인식 API 호출 (InsightFace 서버)
                    $faceHost = getenv('FACE_HOST') ?: 'localhost';
                    $facePort = getenv('FACE_PORT') ?: '5002';
                    // 얼굴 등록 API로 변경 (detect_for_registration이 아닌 register)
                    $pythonUrl = "http://{$faceHost}:{$facePort}/api/face/register";
                    
                    // 🔍 이미지 데이터 추출 - 다양한 소스에서 확인
                    $imageData = null;
                    if (isset($faceData['face_image_data'])) {
                        $imageData = $faceData['face_image_data'];
                        log_message('info', '[FACE] 이미지 데이터 출처: face_image_data');
                    } elseif (isset($postVar['captured_photo'])) {
                        $imageData = $postVar['captured_photo'];
                        log_message('info', '[FACE] 이미지 데이터 출처: captured_photo');
                    } elseif (isset($faceData['image_data'])) {
                        $imageData = $faceData['image_data'];
                        log_message('info', '[FACE] 이미지 데이터 출처: image_data');
                    } else {
                        log_message('error', '[FACE] 이미지 데이터를 찾을 수 없음');
                        log_message('error', '[FACE] 사용 가능한 키들: ' . implode(', ', array_keys($faceData)));
                        log_message('error', '[FACE] POST 데이터 키들: ' . implode(', ', array_keys($postVar)));
                        // 회원정보는 수정하되, 얼굴 등록 실패 정보 추가
                        $return_json['face_registration'] = [
                            'success' => false,
                            'message' => '이미지 데이터를 찾을 수 없습니다.'
                        ];
                    }
                    
                    // 이미지 데이터가 있는 경우에만 Python API 호출
                    if ($imageData) {
                        // 세션에서 회사/지점 정보 가져오기
                        $session = session();
                        $param1 = $session->get('comp_cd') ?? 'DEFAULT';
                        $param2 = $session->get('bcoff_cd') ?? 'DEFAULT';
                        
                        $pythonData = json_encode([
                            'image' => $imageData,
                            'member_id' => $postVar['mem_sno'],  // 회원 ID 추가
                            'param1' => $param1,  // 회사 코드
                            'param2' => $param2   // 지점 코드
                        ]);
                    
                        log_message('info', '[FACE] Python API 호출: ' . $pythonUrl);
                        log_message('info', '[FACE] 전송 데이터 크기: ' . strlen($pythonData) . ' bytes');
                        log_message('info', '[FACE] param1(회사): ' . $param1 . ', param2(지점): ' . $param2);
                        
                        // 이미지 밝기 확인을 위한 로그
                        log_message('info', '[FACE] 이미지 데이터 첫 50자: ' . substr($imageData, 0, 50));
                    
                        $pythonResponse = $this->callPythonAPI($pythonUrl, $pythonData);
                    
                        if ($pythonResponse && isset($pythonResponse['success']) && $pythonResponse['success'] === true) {
                            // register API는 성공 시 바로 데이터를 반환
                            log_message('info', '[FACE] Python register API 성공');
                            
                            // register API 응답에서 데이터 추출
                            $registrationData = $pythonResponse['data'] ?? $pythonResponse;
                            
                            // 🔍 Python 응답 전체 로깅
                            log_message('info', '[FACE] 🔍 Python register 응답: ' . json_encode($pythonResponse));
                            
                            // 얼굴 데이터 추출 (register API 응답 형식에 맞게)
                            $faceEncoding = $registrationData['face_encoding'] ?? [];
                            $glassesDetected = $registrationData['glasses_detected'] ?? false;
                            $qualityScore = $registrationData['quality_score'] ?? 0.85;
                            $livenessScore = $registrationData['liveness_score'] ?? 0.75;
                            $securityLevel = $registrationData['security_level'] ?? 3;
                            
                            log_message('info', '[FACE] 얼굴 등록 성공');
                            log_message('info', '[FACE] - 회원번호: ' . $postVar['mem_sno']);
                            log_message('info', '[FACE] - 임베딩 크기: ' . count($faceEncoding));
                            log_message('info', '[FACE] - 안경 착용: ' . ($glassesDetected ? 'Yes' : 'No'));
                            log_message('info', '[FACE] - 품질 점수: ' . number_format($qualityScore, 3));
                            
                            // 데이터베이스에는 이미 Python API에서 저장되었으므로 응답만 처리
                            $return_json['face_registration'] = [
                                'success' => true,
                                'message' => '얼굴 정보가 성공적으로 등록되었습니다.',
                                'face_info' => [
                                    'glasses_detected' => $glassesDetected,
                                    'quality_score' => number_format($qualityScore, 3),
                                    'liveness_score' => number_format($livenessScore, 3),
                                    'security_level' => $securityLevel
                                ]
                            ];
                            
                            
                            // 로그는 Python API에서 처리되므로 PHP에서는 별도 로그 저장 불필요
                            
                        } else {
                            // Python API 등록 실패
                            $errorMsg = '얼굴 등록에 실패했습니다.';
                            if ($pythonResponse === null) {
                                $errorMsg = 'Python 서버 연결 실패 (포트 확인 필요)';
                            } elseif (isset($pythonResponse['error'])) {
                                $errorMsg = $pythonResponse['error'];
                                // 밝기 문제인 경우 사용자 친화적 메시지
                                if (strpos($errorMsg, '어두운') !== false || strpos($errorMsg, 'brightness') !== false) {
                                    $errorMsg = '이미지가 너무 어둡습니다. 밝은 곳에서 다시 촬영해주세요.';
                                }
                            }
                            
                            log_message('error', '[FACE] Python API 등록 실패: ' . json_encode($pythonResponse));
                            $return_json['face_registration'] = [
                                'success' => false,
                                'message' => $errorMsg,
                                'debug_info' => [
                                    'response' => $pythonResponse,
                                    'url' => $pythonUrl ?? 'URL not set'
                                ]
                            ];
                        }
                    } else {
                        // 이미지 데이터가 없는 경우
                        log_message('error', '[FACE] 이미지 데이터를 찾을 수 없음');
                        $return_json['face_registration'] = [
                            'success' => false,
                            'message' => '이미지 데이터를 찾을 수 없습니다.'
                        ];
                    }
                } else {
                    log_message('error', '❌ face_encoding 필드가 없거나 유효하지 않음');
                    log_message('error', '❌ faceData 내용: ' . json_encode($faceData));
                }
            } catch (\Exception $e) {
                // 얼굴 등록 실패해도 회원정보 수정은 계속 진행
                log_message('error', '❌ 얼굴 등록 실패: ' . $e->getMessage());
                log_message('error', '❌ 스택 트레이스: ' . $e->getTraceAsString());
            }
        } else {
            log_message('info', '⏭️ 얼굴 데이터 없음 - 일반 회원정보 수정만 진행');
            log_message('info', '📋 POST 데이터 키들: ' . implode(', ', array_keys($postVar)));
        }
        
        if ($postVar['mem_pwd'])
        {
            $pdata['mem_pwd'] = $this->enc_pass($postVar['mem_pwd']);
            $pdata['mem_sno'] = $postVar['mem_sno'];
            $pdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $pdata['mod_datetm'] = $nn_now;
            $model->update_mem_main_info_pwd($pdata);
        }
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 회원통합정보 - 양도정보 더보기 (ajax)
     */
    public function ajax_info_mem_more_trans_info()
    {
        
        $transModel = new TransModel();
        $postVar = $this->request->getPost();
        
        $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if ($postVar['rson'] == "61") // 양도
        {
            $data['buy_event_sno'] = $postVar['buy_sno'];
            $get_trans_info = $transModel->get_transm_mgmt_for_buy_sno($data);
        }
        
        if ($postVar['rson'] == "62") // 양수
        {
            $data['assgn_buy_event_sno'] = $postVar['buy_sno'];
            $get_trans_info = $transModel->get_transm_mgmt_for_assgn_buy_sno($data);
        }
        
        
        $return_json['info'] = $get_trans_info[0];
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 회원통합정보 - 환불정보 더보기 (ajax)
     */
    public function ajax_info_mem_more_refund_info()
    {
        $refundModel = new EventModel();
        $postVar = $this->request->getPost();
        
        $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $data['buy_event_sno'] = $postVar['buy_sno'];
        $get_refund_info = $refundModel->get_refund_mgmt_for_buy_sno($data);
        
        $return_json['info'] = $get_refund_info[0];
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * [ TEST ] 수업 체크 하기 
     * @param string $tchr_id
     * @param string $buy_sno
     */
    public function ajax_clas_chk()
    {
        $postVar = $this->request->getPost();
        $stchr_id = $postVar['stchr_id'];
        $buy_sno = $postVar['buy_sno'];
        
        $clasLib = new Clas_lib($stchr_id, $buy_sno);
        
        $clas_result = $clasLib->clas_run();
        
        $return_json['msg'] = $clas_result['msg'];
        $return_json['result'] = 'true';
        return json_encode($return_json);
        //_vardump($clas_result);
    }
    
    
    /**
     * 휴회 가능한 회원권 목록 조회
     */
    public function ajax_get_domcy_list()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['mem_sno'] = $postVar['mem_sno'];
        $initVar['type'] = "user";
        
        // 휴회 라이브러리 초기화
        $domcyLib = new Domcy_lib($initVar);
        
        // 휴회 가능한 회원권 조회
        $poss_domcy = $domcyLib->get_poss_domcy();
        
        // 응답 데이터 구성
        $return_json['result'] = 'success';
        $return_json['list'] = $poss_domcy['list'] ?? [];
        
        return json_encode($return_json);
    }

    /**
     * 휴회 신청하기
     */
    public function ajax_domcy_acppt_proc()
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$postVar = $this->request->getPost();
    	
    	$initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$initVar['mem_sno'] = $postVar['fc_domcy_mem_sno'];
    	$initVar['type'] = "user";
    	$domcyLib = new Domcy_lib($initVar);
    	
    	$var['domcy_aply_buy_sno'] = $postVar['fc_domcy_buy_sno'];
    	$var['domcy_s_date'] = $postVar['fc_domcy_s_date'];
    	$var['domcy_use_day'] = $postVar['fc_domcy_use_day'];
    	$domcyLib->insert_domcy($var);
    	
    	scriptAlert("휴회신청이 완료 되었습니다.");
    	scriptLocation("/teventmem/domcy_manage");
    	exit();
    	
    	//_vardump($postVar);
    }
    
    /**
     * 휴회 신청하기 (새로운 방식 - 여러 회원권 동시 처리)
     */
    public function ajax_domcy_acppt_multi_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['mem_sno'] = $postVar['mem_sno'];
        $initVar['type'] = "user";
        $domcyLib = new Domcy_lib($initVar);
        
        // 선택된 회원권들 처리
        $items = $postVar['items'] ?? [];
        $success_count = 0;
        $error_messages = [];
        
        foreach ($items as $item) {
            try {
                $var['domcy_aply_buy_sno'] = $item['buy_event_sno'];
                $var['domcy_s_date'] = $item['start_date'];
                $var['domcy_e_date'] = $item['end_date'];
                
                // 종료일과 시작일로 사용일수 계산
                $start = new \DateTime($item['start_date']);
                $end = new \DateTime($item['end_date']);
                $interval = $start->diff($end);
                $var['domcy_use_day'] = $interval->days + 1;
                
                $result = $domcyLib->insert_domcy($var);
                if ($result) {
                    $success_count++;
                }
            } catch (\Exception $e) {
                $error_messages[] = "회원권 {$item['buy_event_sno']} 처리 실패: " . $e->getMessage();
            }
        }
        
        // 응답 데이터 구성
        $return_json['result'] = $success_count > 0 ? 'true' : 'false';
        $return_json['success_count'] = $success_count;
        $return_json['total_count'] = count($items);
        $return_json['errors'] = $error_messages;
        
        if ($success_count > 0) {
            $return_json['message'] = "{$success_count}개의 휴회 신청이 완료되었습니다.";
        } else {
            $return_json['message'] = "휴회 신청에 실패했습니다.";
        }
        
        return json_encode($return_json);
    }
    
    /**
     * 휴회 신청하기 (교사용 - API 메서드 복사)
     */
    public function ajax_domcy_acppt_items_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['type'] = "user";
        
        if (!empty($postVar['items']) && is_array($postVar['items'])) {
            // 먼저 모든 항목의 날짜 중복을 체크
            $domcyModel = new \App\Models\DomcyModel();
            $hasOverlap = false;
            $overlapDates = [];
            
            foreach ($postVar['items'] as $item) {
                // 휴회 종료일 계산 (시작일 + 사용일수 - 1)
                $add_days = intval($item['fc_domcy_use_day']) - 1;
                if ($add_days < 0) {
                    $domcy_e_date = date("Y-m-d", strtotime($add_days." days", strtotime($item['fc_domcy_s_date'])));
                } else {
                    $domcy_e_date = date("Y-m-d", strtotime("+".$add_days." days", strtotime($item['fc_domcy_s_date'])));
                }
                
                $checkData = [
                    'comp_cd' => $initVar['comp_cd'],
                    'bcoff_cd' => $initVar['bcoff_cd'],
                    'mem_sno' => $item['fc_domcy_mem_sno'],
                    'domcy_aply_buy_sno' => $item['fc_domcy_buy_sno'],
                    'domcy_s_date' => $item['fc_domcy_s_date'],
                    'domcy_e_date' => $domcy_e_date
                ];
                
                $overlapResult = $domcyModel->check_domcy_date_overlap($checkData);
                
                if (!empty($overlapResult) && $overlapResult[0]['overlap_count'] > 0) {
                    $hasOverlap = true;
                    $overlapDates[] = $item['fc_domcy_s_date'];
                }
            }
            
            // 중복이 있으면 오류 반환
            if ($hasOverlap) {
                $return_json['result'] = 'false';
                $return_json['msg'] = '다음 날짜들이 이미 신청된 휴회 기간과 중복됩니다: ' . implode(', ', array_unique($overlapDates));
                echo json_encode($return_json);
                return;
            }
            
            // 중복이 없으면 모든 항목 처리
            foreach ($postVar['items'] as $item) {
                $initVar['domcy_aply_buy_sno'] = $item['fc_domcy_buy_sno'];
                $initVar['domcy_s_date'] = $item['fc_domcy_s_date'];
                $initVar['domcy_use_day'] = $item['fc_domcy_use_day'];
                $initVar['mem_sno'] = $item['fc_domcy_mem_sno'];
                
                $domcyLib = new Domcy_lib($initVar);
                $domcyLib->insert_domcy($initVar);
                // $domcyLib->update_buy_event_domcy($initVar);
            }
        }
        
        $return_json['result'] = 'true';
        $return_json['msg'] = '휴회 신청이 완료되었습니다.';
        echo json_encode($return_json);
    }
    
    /**
     * [CRON] 테스트 휴회 실행하기
     */
    public function test_domcy_run()
    {
    	$initVar['type'] = "cron";
    	$domcyLib = new Domcy_lib($initVar);
    	$domcyLib->domcy_cron_run();
    }
    
    /**
     * [CRON] 테스트 휴회 세부적용 상품 종료하기
     */
    public function test_domcy_hist_end()
    {
        $initVar['type'] = "cron";
        $domcyLib = new Domcy_lib($initVar);
        $domcyLib->domcy_cron_hist_end();
    }
    
    /**
     * [CRON] 테스트 휴회 신청 리스트 종료하기
     */
    public function test_domcy_cron_end()
    {
        $initVar['type'] = "cron";
        $domcyLib = new Domcy_lib($initVar);
        $domcyLib->domcy_cron_end();
        
    }
    
    /**
     * 휴회중 중간 출석 처리
     */
    public function test_domcy_attd()
    {
        $test_mem_sno = "MM202406090000000041";
        
        $initVar['type'] = "cron";
        $domcyLib = new Domcy_lib($initVar);
        $domcyLib->domcy_cron_attd($test_mem_sno);
    }
    
    /**
     * 양도하기 정보
     * @param string $buy_sno
     */
    public function event_trans_info()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '양도하기',
            'nav' => array('통합정보' => '' , '양도하기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $postVar = $this->request->getPost();
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['mem_sno'] = $postVar['fc_trans_mem_sno'];
        $initVar['tmem_sno'] = $postVar['fc_trans_tmem_sno'];
        $initVar['buy_sno'] = $postVar['fc_trans_buy_sno'];
        
        $transLib = new Trans_lib($initVar);
        $trans_info = $transLib->trans_info();
        
        //_vardump($trans_info['trans_buy_info']['refund_info']['diff_result']);
        
        // ===========================================================================
        // 출입조건이 다를 경우 추가 처리
        // ===========================================================================
        
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['mem_info'] = $trans_info['mem_info'];
        $data['view']['tmem_info'] = $trans_info['tmem_info'];
        $data['view']['data_buy_info'] = $trans_info['data_buyinfo'];
        $data['view']['trans_buy_info'] = $trans_info['trans_buy_info']['refund_info'];
        $this->viewPage('/tchr/ttotalmain/event_trans_info',$data);
    }
    
    /**
     * 양도하기 처리하기
     */
    public function event_trans_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        
        $postVar = $this->request->getPost();
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['mem_sno'] = $postVar['mem_sno'];
        $initVar['tmem_sno'] = $postVar['tmem_sno'];
        $initVar['buy_sno'] = $postVar['buy_event_sno'];
        
        $initVar['payinfo']['van_appno'] = $postVar['van_appno'];
        $initVar['payinfo']['van_appno_sno'] = $postVar['van_appno_sno'];
        $initVar['payinfo']['pay_exr_s_date'] = $postVar['pay_exr_s_date'];
        $initVar['payinfo']['pay_card_amt'] = put_num($postVar['pay_card_amt']);
        $initVar['payinfo']['pay_cash_amt'] = put_num($postVar['pay_cash_amt']);
        
        
        $transLib = new Trans_lib($initVar);
        $result = $transLib->trans_run();
        
        //_vardump($result);
        
        scriptAlert('양도처리가 완료되었습니다.');
        $url = "/ttotalmain/info_mem/".$postVar['mem_sno'];
        scriptLocation($url);
        exit();
    }
    
    /**
     * PT , Golf PT 예약중 -> 이용중 상태 변경
     * @param array $stchr_id
     * @param array $buy_sno
     */
    public function ajax_pt_use($stchr_id='',$buy_sno='')
    {
    	$nn_now = new Time('now');
    	$eventModel = new EventModel();
    	$postVar = $this->request->getPost();
    	
    	$eventData['buy_event_sno'] = $postVar['buy_sno'];
    	$eventData['event_stat'] = "00";
    	$eventData['exr_s_date'] = date('Y-m-d');
    	$eventData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	$eventData['mod_datetm'] = $nn_now;
    	
    	$eventModel->update_buy_event_mgmt_pt_use($eventData);
    	
    	$return_json['result'] = 'true';
    	return json_encode($return_json);
    	
    }
    
    
    /**
     * 수동 출석
     * @param string $mem_sno
     */
    public function ajax_direct_attd($mem_sno='')
    {
        if ($mem_sno != '')
        {
            $put_mem_sno = $mem_sno;
        } else 
        {
            $postVar = $this->request->getPost();
            $put_mem_sno = $postVar['mem_sno'];
        }
        
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['mem_sno'] = $put_mem_sno;
        $attdLib = new Attd_lib($initVar);
        $attdResult = $attdLib->attd_run();
        
        $return_json['msg'] = $attdResult['msg'];
        
        if ($attdResult['result'] == true)
        {
            $return_json['result'] = 'true';
        } else 
        {
            $return_json['result'] = 'false';
        }
        
        return json_encode($return_json);
        
        //_vardump($attdResult);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 회원별 통합정보 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 회원별 통합정보
     */
    public function info_mem($mem_sno = "")
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    	
        
        $postVar = $this->request->getPost();
        
        if ($mem_sno == "") 
        {
            if (isset($postVar['top_search_mem_sno']))
            {
                $mem_sno = $postVar['top_search_mem_sno'];
            } else 
            {
                $this->viewPage('/tchr/ttotalmain/info_mem_search',$data);
                exit();
            }
        }
        
        $memModel = new MemModel();
        
        // 회원 정보를 가져온다.
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $mdata['mem_sno'] = $mem_sno;
        $get_mem_info = $memModel->get_mem_info_mem_sno($mdata);
        $mem_info = $get_mem_info[0];
        
        $get_tchr_list = $memModel->get_list_tchr($mdata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
            $tchr_list[$tchr_i] = $t;
            $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
            if ($t['USE_YN'] == "N") $tchr_list[$tchr_i]['TCHR_POSN_NM'] = "퇴사-".$tchr_list[$tchr_i]['TCHR_POSN_NM'];
            $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
        $tchr_i++;
        endforeach;
        
        // 출석 정보를 가져온다.
        $attdModel = new AttdModel();
        $attdData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $attdData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $attdData['mem_sno'] = $mem_info['MEM_SNO'];
        $attd_list = $attdModel->get_attd_mgmt_16_for_mem_sno($attdData);
        
        // 출석현황
        //// 이번달 1일부터 오늘까지의 날짜를 구한다.
        $attdData['attd_sdate'] = date('Ym') . "01";
        $attdData['attd_edate'] = date('Ymd');
        $attd_day_count = $attdModel->count_attd_mgmt_for_mem_sno($attdData);
        
        $attd_info['count'] = $attd_day_count;
        $attd_info['sdate'] = $attdData['attd_sdate'];
        $attd_info['edate'] = $attdData['attd_edate'];
        
        $eventModel = new EventModel();
        
        // 구매상품 정보를 가져온다.
        $edata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $edata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $edata['mem_id'] = $mem_info['MEM_ID'];
        $edata['event_stat'] = "00";  //이용중
        $event_list_00 = $eventModel->list_buy_event_user_id_event_stat($edata);
        
        $edata['event_stat'] = "01";  //예약됨
        $event_list_01 = $eventModel->list_buy_event_user_id_event_stat($edata);
        
        $edata['event_stat'] = "99";  //종료됨
        $event_list_99 = $eventModel->list_buy_event_user_id_event_stat($edata);
        
        // 휴회 가능 상품 및 정보를 가져온다.
        $domcyInit['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $domcyInit['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $domcyInit['mem_sno'] = $mem_sno;
        $domcyInit['type'] = "user";
        $domcyLib = new Domcy_lib($domcyInit);
        $poss_domcy = $domcyLib->get_poss_domcy();
        
        // 해당 회원의 메모 정보를 가져옵니다.
        $memoModel = new MemoModel();
        $memoData = $domcyInit;
        $get_memo = $memoModel->list_memo_mem_sno($memoData);
        
        // 매출 순위 및 랭킹 정보를 가져온다.
        $salesModel = new SalesModel();
        $rankData = $domcyInit;
        $rankInfo = $salesModel->get_rank_info($rankData);
        
        if (count($rankInfo) == 0)
        {
            $rankInfo[0]['sum_paymt_amt'] = 0;
            $rankInfo[0]['paymt_ranking'] = 0;
        }
        
        
        // 락커룸 정보를 가져온다.
        $lockrModel = new LockrModel();
        $lockrData = $domcyInit;
        $lockrData['lockr_knd'] = "01";
        $lockr_01 = $lockrModel->get_lockr_room($lockrData);
        
        $lockrData['lockr_knd'] = "02";
        $lockr_02 = $lockrModel->get_lockr_room($lockrData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $data['view']['attd_info'] = $attd_info; // 이번달 출석현황 시작일,종료일,출석일수
        $data['view']['lockr_01'] = $lockr_01; // 락커 이용 번호 정보
        $data['view']['lockr_02'] = $lockr_02; // 골프라커 이용 번호 정보
        $data['view']['rank_info'] = $rankInfo[0]; // 매출 순위 및 합계 정보
        $data['view']['memo_list'] = $get_memo; // 메모 정보
        $data['view']['poss_domcy'] = $poss_domcy; // 휴회가능 일,횟수 정보
        $data['view']['attd_list'] = $attd_list; // 출석 리스트
        $data['view']['tchr_list'] = $tchr_list; // 강사 리스트
        $data['view']['event_list'][0] = $event_list_01; // 예약
        $data['view']['event_list'][1] = $event_list_00; // 이용
        $data['view']['event_list'][2] = $event_list_99; // 종료
        $data['view']['mem_info'] = $mem_info;
        $this->viewPage('/tchr/ttotalmain/info_mem2',$data);
    }
    
    /**
     * [회원별 통합정보] 메모 등록 ajax
     */
    public function ajax_memo_insert_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $nn_now = new Time('now');
        $memModel = new MemModel();
        $memoModel = new MemoModel();
        
        $memData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memData['mem_sno'] = $postVar['memo_mem_sno'];
        $meminfo = $memModel->get_mem_info_mem_sno($memData);
        
        $memo['comp_cd'] = $memData['comp_cd'];
        $memo['bcoff_cd'] = $memData['bcoff_cd'];
        $memo['mem_sno'] = $meminfo[0]['MEM_SNO'];
        $memo['mem_id'] = $meminfo[0]['MEM_ID'];
        $memo['mem_nm'] = $meminfo[0]['MEM_NM'];
        $memo['prio_set'] = $postVar['memo_prio_set'];
        $memo['memo_conts_dv'] = "0001"; // 수동메모 등록
        $memo['memo_conts'] = $postVar['memo_content'];
        $memo['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $memo['cre_datetm'] = $nn_now;
        $memo['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $memo['mod_datetm'] = $nn_now;
        
        $memoModel->insert_memo_mgmt($memo);
        
        $return_json['postVar'] = $postVar;
        $return_json['result'] = "true";
        $return_json['msg'] = "메모를 등록 하였습니다.";
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 메모 수정 ajax
     */
    public function ajax_memo_modify_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $nn_now = new Time('now');
        $memoModel = new MemoModel();
        
        $memo['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memo['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memo['memo_mgmt_sno'] = $postVar['modify_memo_mgmt_sno'];
        $memo['memo_conts'] = $postVar['memo_content'];
        $memo['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $memo['mod_datetm'] = $nn_now;
        
        $memoModel->modify_memo_mgmt($memo);
        
        $return_json['postVar'] = $postVar;
        $return_json['result'] = "true";
        $return_json['msg'] = "메모를 수정 하였습니다.";
        return json_encode($return_json);
    }
    
    
    /**
     * [회원별 통합정보] 메모 더보기 ajax
     */
    public function ajax_memo_more()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $memoModel = new MemoModel();
        
        $memoData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memoData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memoData['mem_sno'] = $postVar['mem_sno'];
        
        $memo_list = $memoModel->list_memo_mem_sno($memoData);
        
        $return_json['memo_list'] = $memo_list;
        $return_json['result'] = "true";
        $return_json['msg'] = "메모 더보기 성공";
        return json_encode($return_json);
    }
    
    
    /**
     * [회원별 통합정보] 메모 수정 정보 불러오기 ajax
     */
    public function ajax_memo_modify()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $memoModel = new MemoModel();
        
        $memoData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memoData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memoData['memo_mgmt_sno'] = $postVar['memo_mgmt_sno'];
        
        $memo_list = $memoModel->get_memo_info($memoData);
        
        $return_json['memo_list'] = $memo_list;
        $return_json['result'] = "true";
        $return_json['msg'] = "메모 수정정보 불러오기 성공";
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 중요메모 FLAG 설정 ajax
     */
    public function ajax_memo_prio_set()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $nn_now = new Time('now');
        $memoModel = new MemoModel();
        
        $memoData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memoData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memoData['memo_mgmt_sno'] = $postVar['memo_mgmt_sno'];
        $memoData['prio_set'] = $postVar['prio_set'];
        $memoData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $memoData['mod_datetm'] = $nn_now;
        
        $memoModel->modify_memo_prio_set($memoData);
        
        $return_json['result'] = "true";
        $return_json['msg'] = "중요메모 설정 변경";
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 메모 내용 직접 수정 ajax
     */
    public function ajax_memo_content_update()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $nn_now = new Time('now');
        $memoModel = new MemoModel();
        
        // 권한 체크
        if (!$this->SpoQCahce->getCacheVar('user_id')) {
            $return_json['result'] = "false";
            $return_json['msg'] = "로그인이 필요합니다.";
            return json_encode($return_json);
        }
        
        $memo['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memo['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memo['memo_mgmt_sno'] = $postVar['memo_sno'];
        $memo['memo_conts'] = $postVar['memo_content'];
        $memo['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $memo['mod_datetm'] = $nn_now;
        
        // 메모 내용 업데이트
        $memoModel->modify_memo_mgmt($memo);
        
        $return_json['result'] = "true";
        $return_json['msg'] = "메모가 저장되었습니다.";
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 메모 우선순위 직접 수정 ajax
     */
    public function ajax_memo_prio_update()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $nn_now = new Time('now');
        $memoModel = new MemoModel();
        
        // 권한 체크
        if (!$this->SpoQCahce->getCacheVar('user_id')) {
            $return_json['result'] = "false";
            $return_json['msg'] = "로그인이 필요합니다.";
            return json_encode($return_json);
        }
        
        $memoData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memoData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memoData['memo_mgmt_sno'] = $postVar['memo_sno'];
        $memoData['prio_set'] = $postVar['prio_set'];
        $memoData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $memoData['mod_datetm'] = $nn_now;
        
        // 우선순위 업데이트
        $memoModel->modify_memo_prio_set($memoData);
        
        $return_json['result'] = "true";
        $return_json['msg'] = "메모 타입이 변경되었습니다.";
        return json_encode($return_json);
    }
    
    
    /**
     * [회원별 통합정보] 운동 시작일 변경
     */
    public function ajax_info_mem_change_sdate_proc()
    {
        $postVar = $this->request->getPost();
        
        $buy_sno = $postVar['fc_sdate_buy_sno'];
        $sdate = $postVar['fc_sdate_sdate'];
        
        $initVar['buy_sno'] = $buy_sno;
        $initVar['btype'] = "sdate";
        $eventlib = new Event_lib($initVar);
        $result_event = $eventlib->run_sdate($sdate);

        $is_tf = "true";
        if ($result_event['is_possable'] == false) $is_tf = "false";
        
        $return_json['result'] = $is_tf;
        $return_json['msg'] = $result_event['msg'];
        
//         _vardump($result_event);
        
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 운동 종료일 변경
     */
    public function ajax_info_mem_change_edate_proc()
    {
    	$postVar = $this->request->getPost();
    	
    	$buy_sno = $postVar['fc_edate_buy_sno'];
    	$edate = $postVar['fc_edate_edate'];
    	
//     	_vardump($postVar);
    	
    	$initVar['buy_sno'] = $buy_sno;
    	$initVar['btype'] = "edate";
    	$eventlib = new Event_lib($initVar);
    	$result_event = $eventlib->run_edate($edate);
    	
    	$is_tf = "true";
    	if ($result_event['is_possable'] == false) $is_tf = "false";
    	
    	$return_json['result'] = $is_tf;
    	$return_json['msg'] = $result_event['msg'];
    	
    	return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 휴회일 추가
     */
    public function ajax_info_mem_domcyday_proc()
    {
        $postVar = $this->request->getPost();
//         _vardump($postVar);
        // fc_domcy_day_buy_sno
        // fc_domcy_day_day
        
        $buy_sno = $postVar['fc_domcy_day_buy_sno'];
        $domcy_day = $postVar['fc_domcy_day_day'];
        
        $initVar['buy_sno'] = $buy_sno;
        $initVar['btype'] = "domcy_day";
        $eventlib = new Event_lib($initVar);
        $result_event = $eventlib->run_domcy_day($domcy_day);
        
        $is_tf = "true";
        if ($result_event['is_possable'] == false) $is_tf = "false";
        
        $return_json['result'] = $is_tf;
        $return_json['msg'] = $result_event['msg'];
        
//         _vardump($result_event);
        
        return json_encode($return_json);
        
    }
    
    /**
     * [회원별 통합정보] 휴회횟수 추가
     */
    public function ajax_info_mem_domcycnt_proc()
    {
        $postVar = $this->request->getPost();
        //         _vardump($postVar);
        // fc_domcy_day_buy_sno
        // fc_domcy_day_day
        
        $buy_sno = $postVar['fc_domcy_cnt_buy_sno'];
        $domcy_cnt = $postVar['fc_domcy_cnt_cnt'];
        
        $initVar['buy_sno'] = $buy_sno;
        $initVar['btype'] = "domcy_dnt";
        $eventlib = new Event_lib($initVar);
        $result_event = $eventlib->run_domcy_cnt($domcy_cnt);
        
        $is_tf = "true";
        if ($result_event['is_possable'] == false) $is_tf = "false";
        
        $return_json['result'] = $is_tf;
        $return_json['msg'] = $result_event['msg'];
        
//         _vardump($result_event);
        
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 수업횟수 추가
     */
    public function ajax_info_mem_clascnt_proc()
    {
        $postVar = $this->request->getPost();
        // fc_clas_cnt_buy_sno
        // fc_clas_cnt_cnt
        
        $buy_sno = $postVar['fc_clas_cnt_buy_sno'];
        $clas_cnt = $postVar['fc_clas_cnt_cnt'];
        
        $initVar['buy_sno'] = $buy_sno;
        $initVar['btype'] = "clas_cnt";
        $eventlib = new Event_lib($initVar);
        $result_event = $eventlib->run_clas_cnt($clas_cnt);
        
//         _vardump($result_event);
        
        $is_tf = "true";
        if ($result_event['is_possable'] == false) $is_tf = "false";
        
        $return_json['result'] = $is_tf;
        $return_json['msg'] = $result_event['msg'];
        
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 수업강사 변경하기
     */
    public function ajax_info_mem_stchr_proc()
    {
        $postVar = $this->request->getPost();
        // fc_stchr_buy_sno
        // fc_ch_stchr_id
        
        $buy_sno = $postVar['fc_stchr_buy_sno'];
        $stchr_id = $postVar['fc_ch_stchr_id'];
        
        $initVar['buy_sno'] = $buy_sno;
        $initVar['btype'] = "stchr";
        $eventlib = new Event_lib($initVar);
        $result_event = $eventlib->run_stchr($stchr_id);
        
//         _vardump($result_event);
        
        $is_tf = "true";
        if ($result_event['is_possable'] == false) $is_tf = "false";
        
        $return_json['result'] = $is_tf;
        $return_json['msg'] = $result_event['msg'];
        
        return json_encode($return_json);
    }
    
    /**
     * [회원별 통합정보] 판매강사 변경하기
     */
    public function ajax_info_mem_ptchr_proc()
    {
        $postVar = $this->request->getPost();
        // fc_ptchr_buy_sno
        // fc_ch_ptchr_id
        
        $buy_sno = $postVar['fc_ptchr_buy_sno'];
        $ptchr_id = $postVar['fc_ch_ptchr_id'];
        
        $initVar['buy_sno'] = $buy_sno;
        $initVar['btype'] = "ptchr";
        $eventlib = new Event_lib($initVar);
        $result_event = $eventlib->run_ptchr($ptchr_id);
        
//         _vardump($result_event);
        
        $is_tf = "true";
        if ($result_event['is_possable'] == false) $is_tf = "false";
        
        $return_json['result'] = $is_tf;
        $return_json['msg'] = $result_event['msg'];
        
        return json_encode($return_json);
    }
    
    public function ajax_info_mem_just_end_proc()
    {
        $postVar = $this->request->getPost();
        $nn_now = new Time('now');
        $modelEvent = new EventModel();
        $modelMem = new MemModel();
        
        $endData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $endData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $endData['buy_event_sno'] = $postVar['buy_sno'];
        $endData['event_stat'] = "99";
        $endData['event_stat_rson'] = "91";
        $endData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $endData['mod_datetm'] = $nn_now;
        
        $modelEvent->update_buy_event_mgmt_just_end($endData);
        
        // 강제종료 history
        $nn_now = new Time('now');
        $histData = $endData;
        $hist_event_info = $modelEvent->get_buy_event_buy_sno($histData);
        
        $histModel = new HistModel();
        $hist['comp_cd'] = $hist_event_info[0]['COMP_CD'];
        $hist['bcoff_cd'] = $hist_event_info[0]['BCOFF_CD'];
        $hist['buy_event_sno'] = $hist_event_info[0]['BUY_EVENT_SNO'];
        $hist['sell_event_nm'] = $hist_event_info[0]['SELL_EVENT_NM'];
        $hist['mem_sno'] = $hist_event_info[0]['MEM_SNO'];
        $hist['mem_id'] = $hist_event_info[0]['MEM_ID'];
        $hist['mem_nm'] = $hist_event_info[0]['MEM_NM'];
        $hist['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $hist['mod_datetm'] = $nn_now;
        $hist['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $hist['cre_datetm'] = $nn_now;
        $histModel->insert_hist_event_just_end($hist);
        
        // 강제 종료 후에 회원의 상태를 검사하여 이용중이나 예약됨 상품이 하나도 없을 경우 회원의 상태를 변경 해야한다.
        
        $endChk['comp_cd'] = $hist_event_info[0]['COMP_CD'];
        $endChk['bcoff_cd'] = $hist_event_info[0]['BCOFF_CD'];
        $endChk['mem_sno'] = $hist_event_info[0]['MEM_SNO'];
        $endCount = $modelEvent->end_chk_event_mem_sno($endChk);
        
        if ($endCount == 0)
        {
            $upVar['mem_stat'] = '90'; // 종료회원
            $upVar['mem_sno'] = $hist_event_info[0]['MEM_SNO'];
            $upVar['end_datetm'] = $nn_now;
            $upVar['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
            $upVar['mod_datetm'] = $nn_now;
            $modelMem->update_mem_end($upVar);
        }
        
        $return_json['result'] = "true";
        $return_json['msg'] = "강제종료";
        return json_encode($return_json);
    }
    
    
    /**
     * 락커 선택
     */
    public function nlockr_select($mem_sno,$buy_sno,$lockr_knd,$gendr,$srange=0)
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	
        if ($lockr_knd == "01")
        {
            $disp_title = "락커";
        } else
        {
            $disp_title = "골프라커";
        }
        
    	$data = array(
    	        'title' => $disp_title.' 선택',
    	        'nav' => array('통합정보' => '' , $disp_title.' 선택' => ''),
    			'menu1' => $this->request->getGet('m1'),
                'menu2' => $this->request->getGet('m2')
    	);
    	
    	$lockr_inf['mem_sno'] = $mem_sno;
    	$lockr_inf['buy_sno'] = $buy_sno;
    	$lockr_inf['lockr_knd'] = $lockr_knd;
    	$lockr_inf['gendr'] = $gendr;
    	$lockr_inf['srange'] = $srange;
    	
    	// 해당 락커의 시작 번호와 끝번호 불러오기
    	$lockrModel = new LockrModel();
    	
    	$Ldata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$Ldata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$Ldata['lockr_knd'] = $lockr_knd;
    	$Ldata['lockr_gendr_set'] = $gendr;
    	$Ldata['mem_sno'] = $mem_sno;
    	
    	$lockr_minmax = $lockrModel->select_lockr_minmax($Ldata);
    	
    	// 시작번호와 끝번호를 이용하여 100 개씩 표현해야한다. [1 ~ 100] [101~200]
    	$min_no = $lockr_minmax[0]['min'] / 100;
    	$max_no = $lockr_minmax[0]['max'] / 100;
    	
    	$lockr_range = array();
    	$count_lockr = 0;
    	for($i=floor($min_no); $i<$max_no ;$i++) :
    		$lockr_range[$count_lockr]['min'] = ($i*100) + 1;
    		$lockr_range[$count_lockr]['max'] = ($i+1) * 100;
    		$count_lockr++;
    	endfor;
    	
    	$new_lockr_range = array();
    	$new_count = 0;
    	foreach ($lockr_range as $r) :
    		$Ldata['lockr_min'] = $r['min']; 
    		$Ldata['lockr_max'] = $r['max']; 
    		$new_lockr_range[$new_count] = $r;
    		$new_lockr_range[$new_count]['poss_cnt'] = $lockrModel->count_lockr_range($Ldata);
    		$new_count++;
    	endforeach;
    	
    	
    	$Ldata['lockr_min'] = 1;
    	$Ldata['lockr_max'] = 1;
    	
    	if (isset($lockr_range[$srange]['min']))
    	{
    	    $Ldata['lockr_min'] = $lockr_range[$srange]['min'];
    	    $Ldata['lockr_max'] = $lockr_range[$srange]['max'];
    	}
    	
    	$list_room = $lockrModel->list_lockr_range($Ldata);
    	
    	// 이미 이용중인 락커 번호를 가져온다.
    	$my_room = $lockrModel->get_lockr_room($Ldata);
    	
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	$data['view']['my_room'] = $my_room;
    	$data['view']['lockr_range'] = $new_lockr_range;
    	$data['view']['list_room'] = $list_room;
    	$data['view']['lockr_inf'] = $lockr_inf;
    	$this->viewPage('/tchr/ttotalmain/nlockr_select',$data);
    }
    
    /**
     * 락커 배정 처리
     */
    public function lockr_select_proc()
    {
        $postVar = $this->request->getPost();
        // buy_sno btyp emem_sno 
        $initVar['buy_sno'] = $postVar['set_buy_sno'];
        $initVar['btype'] = "lockr_select";
        $eventLib = new Event_lib($initVar);
        
        $lockr_inf['lockr_no']      = $postVar['set_lockr_no'];
        $lockr_inf['lockr_knd']     = $postVar['set_lockr_knd'];
        $lockr_inf['lockr_gendr']   = $postVar['set_lockr_gendr'];
        
        $result_event = $eventLib->run_lockr_select($lockr_inf);
        
        scriptLocation("/ttotalmain/info_mem/".$postVar['set_mem_sno']);
        exit();
    }
    
    /**
     * 환불 정보를 확인한다.
     * @param array $buy_sno
     */
    public function refund_info($mem_sno,$buy_sno)
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '환불하기',
            'nav' => array('통합정보' => '' , '환불하기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $memModel = new MemModel();
        $eventModel = new EventModel();
        
        $initVar['pay_comp_cd']     = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['pay_bcoff_cd']    = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['pay_mem_sno']     = $mem_sno;
        $initVar['pay_buy_sno']     = $buy_sno;
        
        $refundlib = new Refund_lib($initVar);
        $refundlib->refund_info();
        $result = $refundlib->refund_result();
        
        
        
        $refund_info['refund_amt'] = $result['set_data']['refund_amt'];
        $refund_info['use_amt'] = $result['set_data']['use_amt'];
        
        // 회원정보
        $memData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memData['mem_sno'] = $mem_sno;
        $get_mem_info = $memModel->get_mem_info_mem_sno($memData);
        $mem_info = $get_mem_info[0];
        
        // 환불한 구매 상품 정보
        $eventData = $memData;
        $eventData['buy_event_sno'] = $buy_sno;
        $get_event_info = $eventModel->get_buy_event_buy_sno($eventData);
        $event_info = $get_event_info[0];
        
        // 환불하기 위한 구매 내역 정보
        $refData = $eventData;
        $refund_list = $eventModel->list_refund_paymt_mgmt($refData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['refund_info'] = $refund_info;
        $data['view']['mem_info'] = $mem_info;
        $data['view']['event_info'] = $event_info;
        $data['view']['refund_list'] = $refund_list;
        $this->viewPage('/tchr/ttotalmain/refund_info',$data);
    }
    
    
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 강사별 통합정보 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 강사별 통합정보
     */
    public function info_tchr()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '통합정보',
            'nav' => array('통합정보' => '' , '통합정보' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/info_tchr',$data);
    }
    
    /**
     * Python API 호출 함수
     */
    private function callPythonAPI($url, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // 🔍 API 호출 결과 로깅
        log_message('info', '[PYTHON_API] URL: ' . $url);
        log_message('info', '[PYTHON_API] HTTP Code: ' . $httpCode);
        log_message('info', '[PYTHON_API] Raw Response: ' . $response);
        
        if ($curlError) {
            log_message('error', '[PYTHON_API] CURL Error: ' . $curlError);
            return null;
        }
        
        if ($httpCode !== 200) {
            log_message('error', '[PYTHON_API] HTTP Error ' . $httpCode);
            return null;
        }
        
        $decoded = json_decode($response, true);
        log_message('info', '[PYTHON_API] Decoded Response: ' . json_encode($decoded));
        
        return $decoded;
    }
    
}