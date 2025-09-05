<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\TransModel;
use App\Models\DomcyModel;
use App\Models\AttdModel;

class Teventmem extends MainTchrController
{
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 회원 출석 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 회원 출석 현황
     */
    public function mem_attd_manage($mem_sno ='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    	
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if ($mem_sno)
        {
            $initVar['get']['mem_sno'] = $mem_sno;
        }
        
        $boardPage = new Ama_board($initVar);
        $attdModel = new AttdModel();
        
        // 출석 정보를 가져온다.
        $totalCount  = $attdModel->list_attd_mgmt_count($boardPage->getVal());
        $attd_list = $attdModel->list_attd_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        
        if ( !isset($searchVal['scyn']) ) $searchVal['scyn'] = ''; // 자동차감
        if ( !isset($searchVal['satc']) ) $searchVal['satc'] = ''; // 정상/재입장
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        if ( !isset($searchVal['mem_sno']) ) $searchVal['mem_sno'] = ''; // 회원번호
        
        $data['view']['search_val'] = $searchVal;
        $pager = $boardPage->getPager($totalCount);
        $data['view']['pager'] = $pager;
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['attd_list'] = $attd_list; // 출석 리스트
        $this->viewPage('/tchr/teventmem/mem_attd_manage',$data);
    }
    
    /**
     * 회원 출석 현황 검색 처리 [ ajax ]
     */
    public function ajax_mem_attd_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $attdModel = new AttdModel();
        
        // 출석 정보를 가져온다.
        $totalCount = $attdModel->list_attd_mgmt_count($boardPage->getVal());
        $attd_list = $attdModel->list_attd_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        foreach($attd_list as $r) {
            $attd_word = ($r['ATTD_YN'] == "Y") ? "정상" : "재입장";
            $auto_word = ($r['CLAS_MGMT_SNO'] != "") ? "수업차감" : "";
            $backColor = ($r['ATTD_YN'] == "Y") ? "" : "#cfecf0";
            
            $html .= '<tr style="background-color: ' . $backColor . '">';
            $html .= '<td class="text-center">' . $searchVal['listCount'] . '</td>';
            $html .= '<td class="text-center">' . $r['ATTD_DT'] . '</td>';
            $html .= '<td class="text-center">' . $r['ATTD_HM'] . '</td>';
            $html .= '<td class="text-center">' . $r['ATTD_DOTW'] . '요일</td>';
            $html .= '<td class="text-center">';
            $html .= '<img class="preview_mem_photo" id="preview_mem_photo" ';
            $html .= 'src="' . $r['MEM_THUMB_IMG'] . '" ';
            $html .= 'alt="회원사진" style="border-radius: 50%; cursor: pointer;" ';
            $html .= 'onclick="showFullPhoto(\'' . $r['MEM_MAIN_IMG'] . '\')" ';
            $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $r['MEM_GENDR'] . '.png\';">';
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= $r['MEM_NM'] . '(' . $r['MEM_ID'] . ')';
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td class="text-center">' . $attd_word . '</td>';
            $html .= '<td class="text-center">' . $auto_word . '</td>';
            $html .= '<td>';
            $html .= $r['SELL_EVENT_NM'];
            if($r['GX_STCHR_NM'] != '') {
                $html .= ' / 강사:' . $r['GX_STCHR_NM'];
                $html .= ' / 수업:' . $r['GX_CLAS_TITLE'];
                $html .= ' / 시간:' . $r['GX_CLAS_S_HH_II'] . ' ~ ' . $r['GX_CLAS_E_HH_II'];
            } elseif($r['STCHR_NM'] != '') {
                $html .= ' / 강사:' . $r['STCHR_NM'];
                $html .= ' / 수업수:' . $r['CLAS_CNT'];
            }
            $html .= '</td>';
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        if (empty($attd_list)) {
            $html .= '<tr><td colspan="8" class="text-center">검색된 출석 정보가 없습니다.</td></tr>';
        }
        
        // 페이저 HTML
        $pager = $boardPage->getPager($totalCount);
        
        $result['result'] = 'true';
        $result['html'] = $html;
        $result['pager'] = $pager;
        $result['totalCount'] = $totalCount;
        
        
        echo json_encode($result);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 예약 회원 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 예약 회원 현황
     */
    public function mem_resv_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '예약 회원 현황',
            'nav' => array('회원/상품 현황관리' => '' , '예약 회원 현황' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 등록 장소 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 등록 장소 현황
     */
    public function join_loc_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '등록 장소 현황',
            'nav' => array('회원/상품 현황관리' => '' , '등록 장소 현황' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 양도 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 양도 현황
     */
    public function trans_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelTrans = new TransModel();
        
        $sumCost  = $modelTrans->list_transm_mgmt_sum_cost($boardPage->getVal());
        $totalCount  = $modelTrans->list_transm_mgmt_count($boardPage->getVal());
        $trans_list = $modelTrans->list_transm_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['stnm']) ) $searchVal['stnm'] = ''; //양도자
        if ( !isset($searchVal['sanm']) ) $searchVal['sanm'] = ''; //양수자
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; // 양도상품명
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['sumCost'] = $sumCost; // 총 남은금액
        $data['view']['totalCount'] = $totalCount;
        $data['view']['trans_list'] = $trans_list;
        $this->viewPage('/tchr/teventmem/trans_manage',$data);
    }
    
    /**
     * 양도 현황 검색 처리 [ ajax ]
     */
    public function ajax_trans_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelTrans = new TransModel();
        
        $sumCost = $modelTrans->list_transm_mgmt_sum_cost($boardPage->getVal());
        $totalCount = $modelTrans->list_transm_mgmt_count($boardPage->getVal());
        $trans_list = $modelTrans->list_transm_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        foreach($trans_list as $r) {
            $backColor = "";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $searchVal['listCount'] . '</td>';
            
            // 양도자
            $html .= '<td>';
            $html .= '<img class="preview_mem_photo" id="preview_mem_photo" ';
            $html .= 'src="' . $r['S_MEM_THUMB_IMG'] . '" ';
            $html .= 'alt="회원사진" style="border-radius: 50%; cursor: pointer;" ';
            $html .= 'onclick="showFullPhoto(\'' . $r['S_MEM_MAIN_IMG'] . '\')" ';
            $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $r['S_MEM_GENDR'] . '.png\';">';
            $html .= $r['TRANSM_NM'] . ' (' . $r['TRANSM_ID'] . ')';
            $html .= '</td>';
            
            // 양도 상품명
            $html .= '<td>';
            $html .= '<small class="badge bg-success">' . $sDef['ACC_RTRCT_MTHD'][$r['TRANSM_ACC_RTRCT_MTHD']] . '</small> ';
            $html .= $r['SELL_EVENT_NM'];
            $html .= '</td>';
            
            $html .= '<td style="text-align:right">' . $r['TRANSM_LEFT_DAY'] . '</td>';
            $html .= '<td style="text-align:right">' . $r['REAL_TRANSM_CNT'] . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['USE_AMT']) . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['LEFT_AMT']) . '</td>';
            
            // 양수자
            $html .= '<td>';
            $html .= '<img class="preview_mem_photo" id="preview_mem_photo" ';
            $html .= 'src="' . $r['T_MEM_THUMB_IMG'] . '" ';
            $html .= 'alt="회원사진" style="border-radius: 50%; cursor: pointer;" ';
            $html .= 'onclick="showFullPhoto(\'' . $r['T_MEM_MAIN_IMG'] . '\')" ';
            $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $r['T_MEM_GENDR'] . '.png\';">';
            $html .= $r['ASSGN_NM'] . ' (' . $r['ASSGN_ID'] . ')';
            $html .= '</td>';
            
            // 양수 상품명
            $html .= '<td>';
            $html .= '<small class="badge bg-success">' . $sDef['ACC_RTRCT_MTHD'][$r['ASSGN_ACC_RTRCT_MTHD']] . '</small> ';
            $html .= $r['ASSGN_SELL_EVENT_NM'];
            $html .= '</td>';
            
            $html .= '<td style="text-align:right">' . $r['REAL_TRANSM_DAY'] . '</td>';
            $html .= '<td style="text-align:right">' . $r['REAL_TRANSM_CNT'] . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        if (empty($trans_list)) {
            $html .= '<tr><td colspan="12" class="text-center">검색된 양도 정보가 없습니다.</td></tr>';
        }
        
        // 페이저 HTML
        $pager = $boardPage->getPager($totalCount);
        
        $result['result'] = 'true';
        $result['html'] = $html;
        $result['pager'] = $pager;
        $result['totalCount'] = $totalCount;
        $result['sumCost'] = $sumCost;
        
        echo json_encode($result);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 휴회 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 휴회 현황
     */
    public function domcy_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelDomcy = new DomcyModel();
        
        $totalCount  = $modelDomcy->list_domcy_mgmt_count($boardPage->getVal());
        $domcy_list = $modelDomcy->list_domcy_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ss : 적용시작일
        // se : 적용종료일
        // sc : 등록일
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; // 휴회회원명
        if ( !isset($searchVal['sdstat']) ) $searchVal['sdstat'] = ''; // 휴회신청상태
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['domcy_list'] = $domcy_list;
        $this->viewPage('/tchr/teventmem/domcy_manage',$data);
    }
    
    /**
     * 휴회 현황 검색 처리 [ ajax ]
     */
    public function ajax_domcy_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelDomcy = new DomcyModel();
        
        $totalCount = $modelDomcy->list_domcy_mgmt_count($boardPage->getVal());
        $domcy_list = $modelDomcy->list_domcy_mgmt($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        foreach($domcy_list as $r) {
            $backColor = "";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $searchVal['listCount'] . '</td>';
            $html .= '<td>' . $sDef['DOMCY_MGMT_STAT'][$r['DOMCY_MGMT_STAT']] . '</td>';
            $html .= '<td>';
            $html .= '<img class="preview_mem_photo" id="preview_mem_photo" ';
            $html .= 'src="' . $r['MEM_THUMB_IMG'] . '" ';
            $html .= 'alt="회원사진" style="border-radius: 50%; cursor: pointer;" ';
            $html .= 'onclick="showFullPhoto(\'' . $r['MEM_MAIN_IMG'] . '\')" ';
            $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $r['MEM_GENDR'] . '.png\';">';
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= $r['MEM_NM'] . '(' . $r['MEM_ID'] . ')';
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td>' . $r['DOMCY_S_DATE'] . '</td>';
            $html .= '<td>' . $r['DOMCY_E_DATE'] . '</td>';
            $html .= '<td>' . $r['DOMCY_USE_DAY'] . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '<td></td>';
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        if (empty($domcy_list)) {
            $html .= '<tr><td colspan="8" class="text-center">검색된 휴회 정보가 없습니다.</td></tr>';
        }
        
        // 페이저 HTML
        $pager = $boardPage->getPager($totalCount);
        
        $result['result'] = 'true';
        $result['html'] = $html;
        $result['pager'] = $pager;
        $result['totalCount'] = $totalCount;
        
        echo json_encode($result);
    }
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 당일 종료 회원 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 당일 종료 회원
     */
    public function day_end_mem_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '당일 종료 회원',
            'nav' => array('회원/상품 현황관리' => '' , '당일 종료 회원' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 당일 재등록 회원 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 당일 재등록 회원
     */
    public function day_rebuy_mem_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '당일 재등록 회원',
            'nav' => array('회원/상품 현황관리' => '' , '당일 재등록 회원' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 당일 신규 회원 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 당일 신규 회원
     */
    public function day_newbuy_mem_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '당일 신규 회원',
            'nav' => array('회원/상품 현황관리' => '' , '당일 신규 회원' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 당일 가입 회원 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 당일 가입 회원
     */
    public function day_newjoin_mem_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '당일 가입 회원',
            'nav' => array('회원/상품 현황관리' => '' , '당일 가입 회원' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 당일 환불 회원 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 당일 환불 회원
     */
    public function day_refund_mem_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '당일 환불 회원',
            'nav' => array('회원/상품 현황관리' => '' , '당일 환불 회원' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 교체상품 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 교체상품 현황
     */
    public function change_event_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '교체상품 현황',
            'nav' => array('회원/상품 현황관리' => '' , '교체상품 현황' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $this->viewPage('/tchr/dashboard',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 체크인 페이지 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 체크인 페이지
     */
    public function checkin()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        // 필요한 경우 추가 데이터 설정
        $data['title'] = '체크인';
        $data['nav'] = array('체크인' => '');
        
        // ===========================================================================
        // 화면 처리
        // ==================================================
        echo view('/tchr/teventmem/checkin',  $data);
    }
    
    
    
    
    
    
    
}