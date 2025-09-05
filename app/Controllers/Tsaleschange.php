<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\HistModel;

class Tsaleschange extends MainTchrController
{
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 강제종료 내역 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 강제종료 내역
     */
    public function event_jend_hist()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $modelHist = new HistModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount  = $modelHist->list_hist_event_just_end_count($boardPage->getVal());
        $hist_list = $modelHist->list_hist_event_just_end($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['chtp']) ) $searchVal['chtp'] = ''; //구분
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // je : 강제종료일
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['hist_list'] = $hist_list; // 내역 리스트
        $this->viewPage('/tchr/tsaleschange/event_jend_hist',$data);
    }
    
    /**
     * 강제종료 내역 AJAX 검색
     */
    public function ajax_event_jend_hist_search()
    {
        $modelHist = new HistModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount  = $modelHist->list_hist_event_just_end_count($boardPage->getVal());
        $hist_list = $modelHist->list_hist_event_just_end($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $pager = $boardPage->getPager($totalCount);
        
        // 테이블 HTML 생성
        $html = '총 건수 : ' . $totalCount . ' 건';
        $html .= '<table class="table table-bordered table-hover table-striped col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:70px">순번</th>';
        $html .= '<th style="width:150px">회원명(ID)</th>';
        $html .= '<th style="width:130px">구분</th>';
        $html .= '<th>판매상품명</th>';
        $html .= '<th style="width:140px">강제종료일</th>';
        $html .= '<th style="width:140px">강제종료ID</th>';
        $html .= '<th style="width:140px">등록일시</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $listCount = $searchVal['listCount'];
        foreach($hist_list as $r) {
            $backColor = "";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $listCount . '</td>';
            $html .= '<td>';
            
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo"';
                $html .= ' id="preview_mem_photo_' . $r['MEM_SNO'] . '"';
                $html .= ' src="' . (isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '') . '"';
                $html .= ' alt="회원사진"';
                $html .= ' style="cursor: pointer;"';
                $html .= ' onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '') . '\')"';
                $html .= ' onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= $r['MEM_NM'];
            $html .= '</a>(' . $r['MEM_ID'] . ')';
            $html .= '</td>';
            $html .= '<td class="text-center">강제종료</td>';
            $html .= '<td>' . $r['SELL_EVENT_NM'] . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '<td>' . $r['CRE_ID'] . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '</tr>';
            
            $listCount--;
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $pager
        );
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 강사변경 내역 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 강사변경 내역
     */
    public function tchr_change_hist()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $modelHist = new HistModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_tchr_ch_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_tchr_ch($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['chtp']) ) $searchVal['chtp'] = ''; //구분
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // je : 강제종료일
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['hist_list'] = $hist_list; // 내역 리스트
        $this->viewPage('/tchr/tsaleschange/tchr_change_hist',$data);
    }
    
    /**
     * 강사변경 내역 AJAX 검색
     */
    public function ajax_tchr_change_hist_search()
    {
        $modelHist = new HistModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_tchr_ch_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_tchr_ch($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $pager = $boardPage->getPager($totalCount);
        
        // 테이블 HTML 생성
        $html = '총 건수 : ' . $totalCount . ' 건';
        $html .= '<table class="table table-bordered table-hover table-striped col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:70px">순번</th>';
        $html .= '<th style="width:150px">회원명(ID)</th>';
        $html .= '<th style="width:130px">구분</th>';
        $html .= '<th>판매상품명</th>';
        $html .= '<th style="width:130px">변경전 강사</th>';
        $html .= '<th style="width:130px">변경후 강사</th>';
        $html .= '<th style="width:120px">변경일</th>';
        $html .= '<th style="width:140px">등록아이디</th>';
        $html .= '<th style="width:140px">등록일시</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $listCount = $searchVal['listCount'];
        $sDef = SpoqDef();
        
        foreach($hist_list as $r) {
            $backColor = "";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $listCount . '</td>';
            $html .= '<td>';
            
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo"';
                $html .= ' id="preview_mem_photo_' . $r['MEM_SNO'] . '"';
                $html .= ' src="' . (isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '') . '"';
                $html .= ' alt="회원사진"';
                $html .= ' style="cursor: pointer;"';
                $html .= ' onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '') . '\')"';
                $html .= ' onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= $r['MEM_NM'];
            $html .= '</a>(' . $r['MEM_ID'] . ')';
            $html .= '</td>';
            $html .= '<td>' . $sDef['CH_TYPE'][$r['CH_TYPE']] . '</td>';
            $html .= '<td>' . $r['SELL_EVENT_NM'] . '</td>';
            $html .= '<td>' . $r['BF_TCHR_NM'] . '</td>';
            $html .= '<td>' . $r['AF_TCHR_NM'] . '</td>';
            $html .= '<td>' . substr($r['CRE_DATETM'],0,10) . '</td>';
            $html .= '<td>' . $r['CRE_ID'] . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '</tr>';
            
            $listCount--;
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $pager
        );
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 기간변경 내역 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 기간변경 내역
     */
    public function priod_change_hist()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $modelHist = new HistModel();
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_date_ch_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_date_ch($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['chtp']) ) $searchVal['chtp'] = ''; //구분
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // je : 강제종료일
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['hist_list'] = $hist_list; // 내역 리스트
        $this->viewPage('/tchr/tsaleschange/priod_change_hist',$data);
    }
    
    /**
     * 기간변경 내역 AJAX 검색
     */
    public function ajax_priod_change_hist_search()
    {
        $modelHist = new HistModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_date_ch_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_date_ch($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $pager = $boardPage->getPager($totalCount);
        
        // 테이블 HTML 생성
        $html = '총 건수 : ' . $totalCount . ' 건';
        $html .= '<table class="table table-bordered table-hover table-striped col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:70px">순번</th>';
        $html .= '<th style="width:150px">회원명(ID)</th>';
        $html .= '<th style="width:130px">구분</th>';
        $html .= '<th>판매상품명</th>';
        $html .= '<th style="width:100px">변경전 <br />운동시작일</th>';
        $html .= '<th style="width:100px">변경전 <br />운동종료일</th>';
        $html .= '<th style="width:100px">변경후 <br />운동시작일</th>';
        $html .= '<th style="width:100px">변경후 <br />운동종료일</th>';
        $html .= '<th style="width:120px">변경일</th>';
        $html .= '<th style="width:140px">등록아이디</th>';
        $html .= '<th style="width:140px">등록일시</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $listCount = $searchVal['listCount'];
        $sDef = SpoqDef();
        
        foreach($hist_list as $r) {
            $backColor = "";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $listCount . '</td>';
            $html .= '<td>';
            
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo"';
                $html .= ' id="preview_mem_photo_' . $r['MEM_SNO'] . '"';
                $html .= ' src="' . (isset($r['MEM_THUMB_IMG']) ? htmlspecialchars($r['MEM_THUMB_IMG']) : '') . '"';
                $html .= ' alt="회원사진"';
                $html .= ' style="cursor: pointer;"';
                $html .= ' onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? htmlspecialchars($r['MEM_MAIN_IMG']) : '') . '\')"';
                $html .= ' onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= htmlspecialchars($r['MEM_NM']);
            $html .= '</a>(' . htmlspecialchars($r['MEM_ID']) . ')';
            $html .= '</td>';
            $html .= '<td>' . (isset($sDef['CH_TYPE'][$r['CH_TYPE']]) ? $sDef['CH_TYPE'][$r['CH_TYPE']] : '') . '</td>';
            $html .= '<td>' . htmlspecialchars($r['SELL_EVENT_NM']) . '</td>';
            $html .= '<td>' . (isset($r['BF_EXR_S_DATE']) ? $r['BF_EXR_S_DATE'] : '') . '</td>';
            $html .= '<td>' . (isset($r['BF_EXR_E_DATE']) ? $r['BF_EXR_E_DATE'] : '') . '</td>';
            $html .= '<td>' . (isset($r['AF_EXR_S_DATE']) ? $r['AF_EXR_S_DATE'] : '') . '</td>';
            $html .= '<td>' . (isset($r['AF_EXR_E_DATE']) ? $r['AF_EXR_E_DATE'] : '') . '</td>';
            $html .= '<td>' . substr($r['CRE_DATETM'],0,10) . '</td>';
            $html .= '<td>' . htmlspecialchars($r['CRE_ID']) . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '</tr>';
            
            $listCount--;
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $pager
        );
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 휴회추가 내역 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 휴회추가 내역
     */
    public function domcy_change_hist()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $modelHist = new HistModel();
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_domcy_ch_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_domcy_ch($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['chtp']) ) $searchVal['chtp'] = ''; //구분
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // je : 강제종료일
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['hist_list'] = $hist_list; // 내역 리스트
        $this->viewPage('/tchr/tsaleschange/domcy_change_hist',$data);
    }
    
    /**
     * 휴회추가 내역 AJAX 검색
     */
    public function ajax_domcy_change_hist_search()
    {
        $modelHist = new HistModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_domcy_ch_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_domcy_ch($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $pager = $boardPage->getPager($totalCount);
        
        // 테이블 HTML 생성
        $html = '총 건수 : ' . $totalCount . ' 건';
        $html .= '<table class="table table-bordered table-hover table-striped col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:70px">순번</th>';
        $html .= '<th style="width:150px">회원명(ID)</th>';
        $html .= '<th style="width:130px">구분</th>';
        $html .= '<th>판매상품명</th>';
        $html .= '<th style="width:100px">변경전 <br />추가 휴회일</th>';
        $html .= '<th style="width:100px">변경전 <br />추가 휴회횟수</th>';
        $html .= '<th style="width:100px">변경후 <br />추가 휴회일</th>';
        $html .= '<th style="width:100px">변경후 <br />추가 휴회횟수</th>';
        $html .= '<th style="width:120px">변경일</th>';
        $html .= '<th style="width:140px">등록아이디</th>';
        $html .= '<th style="width:140px">등록일시</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $listCount = $searchVal['listCount'];
        $sDef = SpoqDef();
        
        foreach($hist_list as $r) {
            $backColor = "";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $listCount . '</td>';
            $html .= '<td>';
            
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo"';
                $html .= ' id="preview_mem_photo_' . $r['MEM_SNO'] . '"';
                $html .= ' src="' . (isset($r['MEM_THUMB_IMG']) ? htmlspecialchars($r['MEM_THUMB_IMG']) : '') . '"';
                $html .= ' alt="회원사진"';
                $html .= ' style="cursor: pointer;"';
                $html .= ' onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? htmlspecialchars($r['MEM_MAIN_IMG']) : '') . '\')"';
                $html .= ' onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= htmlspecialchars($r['MEM_NM']);
            $html .= '</a>(' . htmlspecialchars($r['MEM_ID']) . ')';
            $html .= '</td>';
            $html .= '<td>' . (isset($sDef['CH_TYPE'][$r['CH_TYPE']]) ? $sDef['CH_TYPE'][$r['CH_TYPE']] : '') . '</td>';
            $html .= '<td>' . htmlspecialchars($r['SELL_EVENT_NM']) . '</td>';
            $html .= '<td>' . (isset($r['BF_ADD_DOMCY_DAY']) ? $r['BF_ADD_DOMCY_DAY'] : '') . '</td>';
            $html .= '<td>' . (isset($r['BF_ADD_DOMCY_CNT']) ? $r['BF_ADD_DOMCY_CNT'] : '') . '</td>';
            $html .= '<td>' . (isset($r['AF_ADD_DOMCY_DAY']) ? $r['AF_ADD_DOMCY_DAY'] : '') . '</td>';
            $html .= '<td>' . (isset($r['AF_ADD_DOMCY_CNT']) ? $r['AF_ADD_DOMCY_CNT'] : '') . '</td>';
            $html .= '<td>' . substr($r['CRE_DATETM'],0,10) . '</td>';
            $html .= '<td>' . htmlspecialchars($r['CRE_ID']) . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '</tr>';
            
            $listCount--;
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $pager
        );
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 수업추가 내역 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 수업추가 내역
     */
    public function add_srvc_hist()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $modelHist = new HistModel();
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_srvc_add_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_srvc_add($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // je : 강제종료일
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['hist_list'] = $hist_list; // 내역 리스트
        $this->viewPage('/tchr/tsaleschange/add_srvc_hist',$data);
    }
    
    /**
     * 수업추가 내역 AJAX 검색
     */
    public function ajax_add_srvc_hist_search()
    {
        $modelHist = new HistModel();
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        
        $totalCount = $modelHist->list_hist_event_srvc_add_count($boardPage->getVal());
        $hist_list  = $modelHist->list_hist_event_srvc_add($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $pager = $boardPage->getPager($totalCount);
        
        // 테이블 HTML 생성
        $html = '총 건수 : ' . $totalCount . ' 건';
        $html .= '<table class="table table-bordered table-hover table-striped col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:70px">순번</th>';
        $html .= '<th style="width:150px">회원명(ID)</th>';
        $html .= '<th style="width:130px">구분</th>';
        $html .= '<th>판매상품명</th>';
        $html .= '<th style="width:180px">추가 수업수</th>';
        $html .= '<th style="width:180px">수업 진행 강사</th>';
        $html .= '<th style="width:120px">변경일</th>';
        $html .= '<th style="width:140px">등록아이디</th>';
        $html .= '<th style="width:140px">등록일시</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $listCount = $searchVal['listCount'];
        $sDef = SpoqDef();
        
        foreach($hist_list as $r) {
            $backColor = "";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $listCount . '</td>';
            $html .= '<td>';
            
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo"';
                $html .= ' id="preview_mem_photo_' . $r['MEM_SNO'] . '"';
                $html .= ' src="' . (isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '') . '"';
                $html .= ' alt="회원사진"';
                $html .= ' style="cursor: pointer;"';
                $html .= ' onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '') . '\')"';
                $html .= ' onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= $r['MEM_NM'];
            $html .= '</a>(' . $r['MEM_ID'] . ')';
            $html .= '</td>';
            $html .= '<td>' . $sDef['CH_TYPE'][$r['CH_TYPE']] . '</td>';
            $html .= '<td>' . $r['SELL_EVENT_NM'] . '</td>';
            $html .= '<td>' . $r['ADD_SRVC_CLAS_CNT'] . '</td>';
            $html .= '<td>' . $r['TCHR_NM'] . '</td>';
            $html .= '<td>' . substr($r['CRE_DATETM'],0,10) . '</td>';
            $html .= '<td>' . $r['CRE_ID'] . '</td>';
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
            $html .= '</tr>';
            
            $listCount--;
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $pager
        );
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}