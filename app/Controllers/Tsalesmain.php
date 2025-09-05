<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\CateModel;
use App\Models\MemModel;
use App\Models\SalesModel;
use App\Models\ClasModel;

class Tsalesmain extends MainTchrController
{
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 전체 매출 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 전체 매출 현황
     */
    public function month_sales_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        
        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['1rd_cate_cd']) ) $searchVal['1rd_cate_cd'] = ''; //대분류
        if ( !isset($searchVal['2rd_cate_cd']) ) $searchVal['2rd_cate_cd'] = ''; //중분류
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['tnm']) ) $searchVal['tnm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        if ( !isset($searchVal['spstat']) ) $searchVal['spstat'] = ''; // 결제상태
        if ( !isset($searchVal['spchnl']) ) $searchVal['spchnl'] = ''; // 결제채널
        if ( !isset($searchVal['spmthd']) ) $searchVal['spmthd'] = ''; // 결제수단
        if ( !isset($searchVal['spdv']) ) $searchVal['spdv'] = ''; // 구분
        if ( !isset($searchVal['spdvr']) ) $searchVal['spdvr'] = ''; // 사유
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'sd'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m'); // 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }
        
        $cate1 = $cateModel->cevent_u1rd_list($cateData);
        
        $sumCost     = $salesModel->list_sales_mgmt_sum_cost($searchVal);
        $totalCount  = $salesModel->list_sales_mgmt_count($searchVal);
        $sales_list  = $salesModel->list_sales_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $cate2 = array();
        if ($searchVal['1rd_cate_cd'] != '')
        {
            $cateData['1rd_cate_cd'] = $searchVal['1rd_cate_cd'];
            $cate2 = $cateModel->cevent_u2rd_list($cateData);
        }
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['cate1'] = $cate1;
        $data['view']['cate2'] = $cate2;
        $data['view']['sumCost'] = $sumCost; // 총 결제금액
        $data['view']['totalCount'] = $totalCount; // 총 건수
        $data['view']['sales_list'] = $sales_list; // 매출 내역 리스트
        $this->viewPage('/tchr/tsalesmain/month_sales_manage',$data);
    }
    
    /**
     * AJAX search for month_sales_manage
     */
    public function ajax_month_sales_manage_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = array(); // AJAX search doesn't use GET params
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        
        $searchVal = $boardPage->getVal();
        
        // Default values
        if (!isset($searchVal['1rd_cate_cd'])) $searchVal['1rd_cate_cd'] = '';
        if (!isset($searchVal['2rd_cate_cd'])) $searchVal['2rd_cate_cd'] = '';
        if (!isset($searchVal['snm'])) $searchVal['snm'] = '';
        if (!isset($searchVal['tnm'])) $searchVal['tnm'] = '';
        if (!isset($searchVal['senm'])) $searchVal['senm'] = '';
        if (!isset($searchVal['spstat'])) $searchVal['spstat'] = '';
        if (!isset($searchVal['spchnl'])) $searchVal['spchnl'] = '';
        if (!isset($searchVal['spmthd'])) $searchVal['spmthd'] = '';
        if (!isset($searchVal['spdv'])) $searchVal['spdv'] = '';
        if (!isset($searchVal['spdvr'])) $searchVal['spdvr'] = '';
        if (!isset($searchVal['sdcon'])) $searchVal['sdcon'] = 'sc';
        if (!isset($searchVal['nthUnit'])) $searchVal['nthUnit'] = date('Y-m');
        if (!isset($searchVal['sdUnit'])) $searchVal['sdUnit'] = 'Month';
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01');
        }
        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t');
        }
        
        $sumCost = $salesModel->list_sales_mgmt_sum_cost($searchVal);
        $totalCount = $salesModel->list_sales_mgmt_count($searchVal);
        $sales_list = $salesModel->list_sales_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // Get pagination
        $pager = $boardPage->getPager($totalCount);
        
        // Get definitions
        $sDef = SpoqDef();
        
        // Format phone function
        function formatPhoneNumber($phone) {
            $phone = preg_replace("/[^0-9]/", "", $phone);
            $length = strlen($phone);
            
            if ($length == 11) {
                return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
            } elseif ($length == 10) {
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            } elseif ($length == 9) {
                return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            } else {
                return $phone;
            }
        }
        
        // Create HTML for table content
        ob_start();
        ?>
        총 건수 : <?php echo $totalCount?> 건 / 총 결제금액 : <?php echo number_format($sumCost)?> 원
        <table class="table table-bordered table-hover table-striped col-md-12 mt20">
            <thead>
                <tr class='text-center'>
                    <th style='width:55px'>순번</th>
                    <th style='width:150px'>회원명(ID)</th>
                    <th style='width:130px'>구분</th>
                    <th>판매상품명</th>
                    <th style='width:100px'>상태</th>
                    <th style='width:100px'>수단</th>
                    <th style='width:100px'>결제채널</th>
                    <th style='width:100px'>결제금액</th>
                    <th style='width:100px'>구분</th>
                    <th style='width:120px'>사유</th>
                    <th style='width:100px'>회원상태</th>
                    <th style='width:80px'>판매강사</th>
                    <th style='width:140px'>등록일시</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($sales_list as $r): 
                    $backColor = "";
                ?>
                <tr style="background-color: <?php echo $backColor ?>;">
                    <td class='text-center'><?php echo $searchVal['listCount']?></td>
                    <td>
                        <?php if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) : ?>
                            <img class="preview_mem_photo"
                                id="preview_mem_photo_<?php echo $r['MEM_SNO']?>"
                                src="<?php echo isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '' ?>"
                                alt="회원사진"
                                style="cursor: pointer;"
                                onclick="showFullPhoto('<?php echo isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '' ?>')"
                                onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M' ?>.png';">
                        <?php endif; ?>
                        <a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']; ?>');">
                            <?php echo $r['MEM_NM']?>
                        </a>(<?php echo $r['MEM_ID']?>)
                    </td>
                    <td class='text-center'> <?php 
                        echo (is_null($r['CLAS_DV']) ) 
                            ? $r['CATE_NM'] 
                            : $sDef['CLAS_DV'][$r['CLAS_DV']];
                    ?></td>
                    <td><?php echo $r['SELL_EVENT_NM']?></td>
                    
                    <td class='text-center'><?php echo $sDef['PAYMT_STAT'][$r['PAYMT_STAT']]?></td>
                    <td class='text-center'><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?></td>
                    <td class='text-center'><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></td>
                    
                    <?php if($r['PAYMT_AMT'] < 0) : ?>
                    <td style="background-color:#f7d5d9; text-align:right;"><?php echo number_format($r['PAYMT_AMT'])?></td>
                    <?php else : ?>
                    <td style="text-align:right"><?php echo number_format($r['PAYMT_AMT'])?></td>
                    <?php endif ; ?>
                    
                    <td><?php echo $sDef['SALES_DV'][$r['SALES_DV']]?></td>
                    <td><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></td>
                    <td><?php echo $sDef['SALES_MEM_STAT'][$r['SALES_MEM_STAT']]?></td>
                    
                    <td><?php echo $r['PTHCR_ID']?></td>
                    
                    <td><?php echo $r['CRE_DATETM']?></td>
                </tr>
                <?php 
                $searchVal['listCount']--;
                endforeach; 
                ?>
            </tbody>
        </table>
        <?php
        $html = ob_get_clean();
        
        $return_json['result'] = 'true';
        $return_json['html'] = $html;
        $return_json['pager'] = $pager;
        $return_json['totalCount'] = $totalCount;
        $return_json['totalAmount'] = number_format($sumCost);
        
        echo json_encode($return_json, JSON_UNESCAPED_UNICODE);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 환불 매출 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 환불 매출 현황
     */
    public function day_refund_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['get']['sales_dv'] = "90"; // add_where
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['1rd_cate_cd']) ) $searchVal['1rd_cate_cd'] = ''; //대분류
        if ( !isset($searchVal['2rd_cate_cd']) ) $searchVal['2rd_cate_cd'] = ''; //중분류
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        if ( !isset($searchVal['spstat']) ) $searchVal['spstat'] = ''; // 결제상태
        if ( !isset($searchVal['spchnl']) ) $searchVal['spchnl'] = ''; // 결제채널
        if ( !isset($searchVal['spmthd']) ) $searchVal['spmthd'] = ''; // 결제수단
        if ( !isset($searchVal['spdv']) ) $searchVal['spdv'] = ''; // 구분
        if ( !isset($searchVal['spdvr']) ) $searchVal['spdvr'] = ''; // 사유
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'sd'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m'); // 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }
        

        
        $cate1 = $cateModel->cevent_u1rd_list($cateData);
        
        $sumCost     = $salesModel->list_sales_mgmt_sum_cost($searchVal);
        $totalCount  = $salesModel->list_sales_mgmt_count($searchVal);
        $sales_list = $salesModel->list_sales_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];


        $cate2 = array();
        if ($searchVal['1rd_cate_cd'] != '')
        {
            $cateData['1rd_cate_cd'] = $searchVal['1rd_cate_cd'];
            $cate2 = $cateModel->cevent_u2rd_list($cateData);
        }
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['cate1'] = $cate1;
        $data['view']['cate2'] = $cate2;
        $data['view']['sumCost'] = $sumCost; // 총 결제금액
        $data['view']['totalCount'] = $totalCount; // 총 건수
        $data['view']['sales_list'] = $sales_list; // 매출 내역 리스트
        $this->viewPage('/tchr/tsalesmain/day_refund_manage',$data);
    }
    
    /**
     * 환불 매출 현황 AJAX 검색
     */
    public function ajax_day_refund_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['get']['sales_dv'] = "90"; // add_where
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['1rd_cate_cd']) ) $searchVal['1rd_cate_cd'] = '';
        if ( !isset($searchVal['2rd_cate_cd']) ) $searchVal['2rd_cate_cd'] = '';
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = '';
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = '';
        if ( !isset($searchVal['spstat']) ) $searchVal['spstat'] = '';
        if ( !isset($searchVal['spchnl']) ) $searchVal['spchnl'] = '';
        if ( !isset($searchVal['spmthd']) ) $searchVal['spmthd'] = '';
        if ( !isset($searchVal['spdv']) ) $searchVal['spdv'] = '';
        if ( !isset($searchVal['spdvr']) ) $searchVal['spdvr'] = '';
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'sc';
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m');
        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month';
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01');
        }
        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t');
        }
        
        $searchVal['listCount'] = $searchVal['sCount'];
        $sumCost     = $salesModel->list_sales_mgmt_sum_cost($searchVal);
        $totalCount  = $salesModel->list_sales_mgmt_count($searchVal);
        $sales_list = $salesModel->list_sales_mgmt($searchVal);
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        // 전화번호 포맷팅 함수
        $formatPhoneNumber = function($phone) {
            $phone = preg_replace("/[^0-9]/", "", $phone);
            $length = strlen($phone);
            
            if ($length == 11) {
                return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
            } elseif ($length == 10) {
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            } elseif ($length == 9) {
                return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            } else {
                return $phone;
            }
        };
        
        // 총 건수와 금액 표시
        $html .= '총 건수 : <span id="total-count">' . $totalCount . '</span> 건 / 총 결제금액 : <span id="total-amount">' . number_format($sumCost) . '</span> 원';
        $html .= '<table class="table table-bordered table-hover col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:55px">순번</th>';
        $html .= '<th style="width:150px">회원명(ID)</th>';
        $html .= '<th style="width:130px">구분</th>';
        $html .= '<th>판매상품명</th>';
        $html .= '<th style="width:100px">상태</th>';
        $html .= '<th style="width:100px">수단</th>';
        $html .= '<th style="width:100px">결제채널</th>';
        $html .= '<th style="width:100px">결제금액</th>';
        $html .= '<th style="width:100px">구분</th>';
        $html .= '<th style="width:120px">사유</th>';
        $html .= '<th style="width:100px">회원상태</th>';
        $html .= '<th style="width:80px">판매강사</th>';
        $html .= '<th style="width:140px">등록일시</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        if (empty($sales_list)) {
            $html .= '<tr><td colspan="13" class="text-center">검색 결과가 없습니다.</td></tr>';
        } else {
            $listCount = $searchVal['listCount'];
            foreach($sales_list as $r) {
                $backColor = "";
                
                $html .= '<tr style="background-color: ' . $backColor . ';">';
                $html .= '<td class="text-center">' . $listCount . '</td>';
                
                $html .= '<td>';
                if (isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                    $html .= '<img class="preview_mem_photo" ';
                    $html .= 'id="preview_mem_photo_' . $r['MEM_SNO'] . '" ';
                    $html .= 'src="' . (isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '') . '" ';
                    $html .= 'alt="회원사진" style="cursor: pointer;" ';
                    $html .= 'onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '') . '\')" ';
                    $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
                }
                $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
                $html .= $r['MEM_NM'];
                $html .= '</a>(' . $r['MEM_ID'] . ')';
                $html .= '</td>';
                
                $html .= '<td class="text-center">' . (is_null($r['CLAS_DV']) ? $r['CATE_NM'] : $sDef['CLAS_DV'][$r['CLAS_DV']]) . '</td>';
                $html .= '<td>' . $r['SELL_EVENT_NM'] . '</td>';
                $html .= '<td class="text-center">' . $sDef['PAYMT_STAT'][$r['PAYMT_STAT']] . '</td>';
                $html .= '<td class="text-center">' . $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']] . '</td>';
                $html .= '<td class="text-center">' . $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']] . '</td>';
                
                if($r['PAYMT_AMT'] < 0) {
                    $html .= '<td style="background-color:#f7d5d9; text-align:right;">' . number_format($r['PAYMT_AMT']) . '</td>';
                } else {
                    $html .= '<td style="text-align:right">' . number_format($r['PAYMT_AMT']) . '</td>';
                }
                
                $html .= '<td>' . $sDef['SALES_DV'][$r['SALES_DV']] . '</td>';
                $html .= '<td>' . $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']] . '</td>';
                $html .= '<td>' . $sDef['SALES_MEM_STAT'][$r['SALES_MEM_STAT']] . '</td>';
                $html .= '<td>' . $r['PTHCR_ID'] . '</td>';
                $html .= '<td>' . $r['CRE_DATETM'] . '</td>';
                $html .= '</tr>';
                
                $listCount--;
            }
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        // 페이저 생성
        $pager = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 응답 데이터 구성
        // ===========================================================================
        $response = [
            'result' => 'true',
            'html' => $html,
            'pager' => $pager,
            'totalCount' => $totalCount,
            'totalAmount' => number_format($sumCost)
        ];
        
        return json_encode($response);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 당일 매출 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 당일 매출 [ 사용안함 ]
     */
    public function day_sales_manage()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '당일 매출',
            'nav' => array('매출 관리' => '' , '당일 매출' => ''),
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
    //                                               [ 수업진행 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 수업진행 현황
     */
    public function month_class_sales_manage($mem_sno ='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);  
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        // URL 파라미터로 전달된 mem_sno 추가
        if ($mem_sno)
        {
            $initVar['get']['mem_sno'] = $mem_sno;
        }
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        
        $searchVal = $boardPage->getVal();
        
        // POST 요청인지 확인 (검색 버튼을 눌렀거나 AJAX 검색인 경우)
        $isSearchRequest = !empty($this->request->getPost());
        
        // 첫 진입시(GET)이고 mem_sno가 있을 때만 회원명 자동 조회
        if (!$isSearchRequest && isset($searchVal['mem_sno']) && $searchVal['mem_sno'])
        {
            $memModel = new MemModel();
            $memInfoArray = $memModel->get_mem_info_mem_sno(['mem_sno' => $searchVal['mem_sno']]);
            if (!empty($memInfoArray)) {
                $searchVal['snm'] = $memInfoArray[0]['MEM_NM']; // 회원명 자동 설정
            }
        }
        // POST 요청이면 mem_sno 제거 (검색 후에는 사용하지 않음)
        else if ($isSearchRequest && isset($searchVal['mem_sno'])) {
            unset($searchVal['mem_sno']);
        }
        
        if ( !isset($searchVal['stnm']) ) $searchVal['stnm'] = ''; //강사명
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        
        if ( !isset($searchVal['scyn']) ) $searchVal['scyn'] = ''; // 자동차감
        if ( !isset($searchVal['scdv']) ) $searchVal['scdv'] = ''; // 정규/서비스 구분
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'sc'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] =  date('Y-m'); // 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }
        

        
        $sumCost = $salesModel->list_pt_clas_mgmt_sum_cost($searchVal);
        $totalCount  = $salesModel->list_pt_clas_mgmt_count($searchVal);
        $sales_list = $salesModel->list_pt_clas_mgmt($searchVal);

        
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['sumCost'] = $sumCost;
        $data['view']['totalCount'] = $totalCount; 
        $data['view']['sales_list'] = $sales_list; // 매출 내역 리스트
        $this->viewPage('/tchr/tsalesmain/month_class_sales_manage',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 수업매출 현황 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 수업매출 현황
     */
    public function month_class_sell_manage()
    {
         // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);  
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['get']['clas_dv'] = "21,22"; // add_where
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        $memModel = new MemModel();
        
        
        $searchVal = $boardPage->getVal();

        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'sd'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m'); // 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }
        if ( !isset($searchVal['ptchr_id']) ) $searchVal['ptchr_id'] = ''; //판매 강사아이디
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        if ( !isset($searchVal['spstat']) ) $searchVal['spstat'] = ''; // 결제상태
        if ( !isset($searchVal['spchnl']) ) $searchVal['spchnl'] = ''; // 결제채널
        if ( !isset($searchVal['spmthd']) ) $searchVal['spmthd'] = ''; // 결제수단
        if ( !isset($searchVal['spdv']) ) $searchVal['spdv'] = ''; // 구분
        if ( !isset($searchVal['spdvr']) ) $searchVal['spdvr'] = ''; // 사유
        
        
        $sumCost     = $salesModel->list_sales_mgmt_sum_cost($searchVal);
        $totalCount  = $salesModel->list_sales_mgmt_count($searchVal);
        $sales_list = $salesModel->list_sales_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        

        
        
        
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        
        $tdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $tdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $list_tchr = $memModel->get_list_tchr($tdata);
        
        $tchr_list = array();
        $sDef = SpoQDef();
        $tchr_i = 0;
        foreach ($list_tchr as $t) :
            $tchr_list[$tchr_i] = $t;
            $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
            if ($t['USE_YN'] == "N") $tchr_list[$tchr_i]['TCHR_POSN_NM'] = "퇴사-".$tchr_list[$tchr_i]['TCHR_POSN_NM'];
            $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
            $tchr_i++;
        endforeach;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['tchr_list'] = $tchr_list; // 강사리스트
        $data['view']['sumCost'] = $sumCost; // 총 결제금액
        $data['view']['totalCount'] = $totalCount; // 총 건수
        $data['view']['sales_list'] = $sales_list; // 매출 내역 리스트
        $this->viewPage('/tchr/tsalesmain/month_class_sell_manage',$data);
    }
    
    /**
     * AJAX search for month_class_sell_manage
     */
    public function ajax_month_class_sell_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = array(); // AJAX search doesn't use GET params
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['get']['clas_dv'] = "21,22"; // add_where for class sales
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        
        $searchVal = $boardPage->getVal();
        
        // Default values
        if (!isset($searchVal['ptchr_id'])) $searchVal['ptchr_id'] = '';
        if (!isset($searchVal['snm'])) $searchVal['snm'] = '';
        if (!isset($searchVal['senm'])) $searchVal['senm'] = '';
        if (!isset($searchVal['spstat'])) $searchVal['spstat'] = '';
        if (!isset($searchVal['spchnl'])) $searchVal['spchnl'] = '';
        if (!isset($searchVal['spmthd'])) $searchVal['spmthd'] = '';
        if (!isset($searchVal['spdv'])) $searchVal['spdv'] = '';
        if (!isset($searchVal['spdvr'])) $searchVal['spdvr'] = '';
        if (!isset($searchVal['sdcon'])) $searchVal['sdcon'] = 'sd';
        if (!isset($searchVal['nthUnit'])) $searchVal['nthUnit'] = date('Y-m');
        if (!isset($searchVal['sdUnit'])) $searchVal['sdUnit'] = 'Month';
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01');
        }
        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t');
        }
        
        $sumCost = $salesModel->list_sales_mgmt_sum_cost($searchVal);
        $totalCount = $salesModel->list_sales_mgmt_count($searchVal);
        $sales_list = $salesModel->list_sales_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // Get pagination
        $pager = $boardPage->getPager($totalCount);
        
        // Get definitions
        $sDef = SpoqDef();
        
        // Format phone function
        function formatPhoneNumber($phone) {
            $phone = preg_replace("/[^0-9]/", "", $phone);
            $length = strlen($phone);
            
            if ($length == 11) {
                return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
            } elseif ($length == 10) {
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            } elseif ($length == 9) {
                return preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            } else {
                return $phone;
            }
        }
        
        // Create HTML for table content
        ob_start();
        ?>
        총 건수 : <?php echo $totalCount?> 건 / 총 결제금액 : <?php echo number_format($sumCost)?> 원
        <table class="table table-bordered table-hover col-md-12 mt20">
            <thead>
                <tr class='text-center'>
                    <th style='width:55px'>순번</th>
                    <th style='width:150px'>회원명(ID)</th>
                    <th style='width:130px'>구분</th>
                    <th>판매상품명</th>
                    
                    <th style='width:100px'>상태</th>
                    <th style='width:100px'>결제채널</th>
                    <th style='width:100px'>수단</th>
                    <th style='width:100px'>결제금액</th>
                    <th style='width:100px'>매출구분</th>
                    <th style='width:120px'>사유</th>
                    <th style='width:100px'>회원상태</th>
                    
                    <th style='width:80px'>판매강사</th>
                    <th style='width:140px'>등록일시</th>
                </tr>
            </thead> 
            <tbody>
                <?php 
                foreach($sales_list as $r) :
                    $backColor = "";
                ?>
                <tr style="background-color: <?php echo $backColor ?>;">
                    <td class='text-center'><?php echo $searchVal['listCount']?></td>
                    <td>
                        <?php if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) : ?>
                            <img class="preview_mem_photo"
                                id="preview_mem_photo_<?php echo $r['MEM_SNO']?>"
                                src="<?php echo isset($r['MEM_THUMB_IMG']) ? $r['MEM_THUMB_IMG'] : '' ?>"
                                alt="회원사진"
                                style="cursor: pointer;"
                                onclick="showFullPhoto('<?php echo isset($r['MEM_MAIN_IMG']) ? $r['MEM_MAIN_IMG'] : '' ?>')"
                                onerror="this.onerror=null; this.src='/dist/img/default_profile_<?php echo isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M' ?>.png';">
                        <?php endif; ?>
                        <a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']; ?>');">
                            <?php echo $r['MEM_NM']?>
                        </a>(<?php echo $r['MEM_ID']?>)
                    </td>
                    <td class='text-center'> <?php 
                        echo (is_null($r['CLAS_DV']) ) 
                            ? $r['CATE_NM'] 
                            : $sDef['CLAS_DV'][$r['CLAS_DV']];
                    ?></td>
                    <td><?php echo $r['SELL_EVENT_NM']?></td>
                    
                    <td class='text-center'><?php echo $sDef['PAYMT_STAT'][$r['PAYMT_STAT']]?></td>
                    <td class='text-center'><?php echo $sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]?></td>
                    <td class='text-center'><?php echo $sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]?></td>
                    
                    <?php if($r['PAYMT_AMT'] < 0) : ?>
                    <td style="background-color:#f7d5d9; text-align:right;"><?php echo number_format($r['PAYMT_AMT'])?></td>
                    <?php else : ?>
                    <td style="text-align:right"><?php echo number_format($r['PAYMT_AMT'])?></td>
                    <?php endif ; ?>
                    
                    <td><?php echo $sDef['SALES_DV'][$r['SALES_DV']]?></td>
                    <td><?php echo $sDef['SALES_DV_RSON'][$r['SALES_DV_RSON']]?></td>
                    <td><?php echo $sDef['SALES_MEM_STAT'][$r['SALES_MEM_STAT']]?></td>
                    
                    <td><?php echo $r['PTHCR_ID']?></td>
                    
                    <td><?php echo $r['CRE_DATETM']?></td>
                </tr>
                <?php 
                $searchVal['listCount']--;
                endforeach;
                ?>
            </tbody>
        </table>
        <?php
        $html = ob_get_clean();
        
        $response = [
            'result' => 'true',
            'html' => $html,
            'pager' => $pager,
            'totalCount' => $totalCount,
            'totalAmount' => number_format($sumCost)
        ];
        
        return json_encode($response);
    }
    
    
    /**
     * PT매출 순위
     */
    public function pt_sales_rank()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);  
        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        
        // 년, 월 날짜 검색 기능 추가 [2024-09-27]
        $DupGetVal = $boardPage->getVal();
        
        $syy = date('Y');
        $smm = date('m');
        
        if (isset($DupGetVal['syy'])) 
        {
            $syy = $DupGetVal['syy'];
        } else 
        {
            $DupGetVal['syy'] = $syy;
        }
        
        if (isset($DupGetVal['smm'])) 
        {
            $smm = $DupGetVal['smm'];
        } else 
        {
            $DupGetVal['smm'] = $smm;
        }
        
        if ($smm == '')
        {
            $DupGetVal['sdate'] = $syy . "-01-01";
            $DupGetVal['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 years", strtotime($DupGetVal['sdate'])))))); ;
        } else 
        {
            $DupGetVal['sdate'] = $syy . "-".$smm."-01";
            $DupGetVal['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 months", strtotime($DupGetVal['sdate'])))))); ;
        }
        
        $DupGetVal['sdate'] = $DupGetVal['sdate'] . " 00:00:00";
        $DupGetVal['edate'] = $DupGetVal['edate'] . " 23:59:59";
        
        $totalCount  = $salesModel->list_pt_rank_count($DupGetVal);
        $pt_rank_list  = $salesModel->list_pt_rank($DupGetVal);
        
        $searchVal = $DupGetVal;
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        /*
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        if ( !isset($searchVal['spstat']) ) $searchVal['spstat'] = ''; // 결제상태
        if ( !isset($searchVal['spchnl']) ) $searchVal['spchnl'] = ''; // 결제채널
        if ( !isset($searchVal['spmthd']) ) $searchVal['spmthd'] = ''; // 결제수단
        if ( !isset($searchVal['spdv']) ) $searchVal['spdv'] = ''; // 구분
        if ( !isset($searchVal['spdvr']) ) $searchVal['spdvr'] = ''; // 사유
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = ''; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        */
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['pt_rank_list'] = $pt_rank_list; // PT 매출 순위 리스트
        $this->viewPage('/tchr/tsalesmain/pt_sales_rank',$data);
    }
    
    /**
     * PT매출 순위 AJAX 검색
     */
    public function ajax_pt_sales_rank_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        
        // 년, 월 날짜 검색 기능 추가
        $DupGetVal = $boardPage->getVal();
        
        $syy = date('Y');
        $smm = date('m');
        
        if (isset($DupGetVal['syy'])) 
        {
            $syy = $DupGetVal['syy'];
        } else 
        {
            $DupGetVal['syy'] = $syy;
        }
        
        if (isset($DupGetVal['smm'])) 
        {
            $smm = $DupGetVal['smm'];
        } else 
        {
            $DupGetVal['smm'] = $smm;
        }
        
        if ($smm == '')
        {
            $DupGetVal['sdate'] = $syy . "-01-01";
            $DupGetVal['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 years", strtotime($DupGetVal['sdate'])))))); ;
        } else 
        {
            $DupGetVal['sdate'] = $syy . "-".$smm."-01";
            $DupGetVal['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 months", strtotime($DupGetVal['sdate'])))))); ;
        }
        
        $DupGetVal['sdate'] = $DupGetVal['sdate'] . " 00:00:00";
        $DupGetVal['edate'] = $DupGetVal['edate'] . " 23:59:59";
        
        $totalCount  = $salesModel->list_pt_rank_count($DupGetVal);
        $pt_rank_list  = $salesModel->list_pt_rank($DupGetVal);
        
        $searchVal = $DupGetVal;
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $pager = $boardPage->getPager($totalCount);
        
        // 테이블 HTML 생성
        $html = '<table class="table table-bordered table-hover table-striped col-md-12">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:150px">회원명(ID)</th>';
        $html .= '<th style="width:120px">순위</th>';
        $html .= '<th style="width:120px">PT매출금액</th>';
        $html .= '<th style="width:150px">최근 매출일</th>';
        $html .= '<th style="width:120px">지난일수</th>';
        $html .= '<th>비고</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach($pt_rank_list as $r) {
            $backColor = "";
            
            $from = new \DateTime( substr($r['last_cre_datetm'],0,10) );
            $to = new \DateTime( date('Y-m-d') );
            $a = $from -> diff( $to ) -> days;
            if ( $from > $to ) { $a = '-' . $a; }
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
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
            $html .= '<td class="text-center">' . $r['paymt_ranking'] . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['sum_paymt_amt']) . '</td>';
            $html .= '<td>' . $r['last_cre_datetm'] . '</td>';
            $html .= '<td class="text-center">' . $a . '</td>';
            $html .= '<td></td>';
            $html .= '</tr>';
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
    
    /**
     * GX 수업 진행 현황
     */
    public function gx_class_attd_list()
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
        $clasModel = new ClasModel();
        $memModel = new MemModel();
        
        // 년, 월 날짜 검색 기능 추가 [2024-09-27]
        $DupGetVal = $boardPage->getVal();
        
        $syy = date('Y');
        $smm = date('m');
        
        if (isset($DupGetVal['syy']))
        {
            $syy = $DupGetVal['syy'];
        } else
        {
            $DupGetVal['syy'] = $syy;
        }
        
        if (isset($DupGetVal['smm']))
        {
            $smm = $DupGetVal['smm'];
        } else
        {
            $DupGetVal['smm'] = $smm;
        }
        
        if ($smm == '')
        {
            $DupGetVal['sdate'] = $syy . "-01-01";
            $DupGetVal['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 years", strtotime($DupGetVal['sdate'])))))); ;
        } else
        {
            $DupGetVal['sdate'] = $syy . "-".$smm."-01";
            $DupGetVal['edate'] = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 months", strtotime($DupGetVal['sdate'])))))); ;
        }
        
        $searchVal = $DupGetVal;
        
        if ( !isset($searchVal['gx_stchr_id']) ) $searchVal['gx_stchr_id'] = ''; //GX 강사아이디
        if ( !isset($searchVal['gx_clas_id']) ) $searchVal['gx_clas_id'] = ''; //GX 수업아이디
        if ( !isset($searchVal['sdate']) ) $searchVal['sdate'] = ''; // 검색 시작일
        if ( !isset($searchVal['edate']) ) $searchVal['edate'] = ''; // 검색 종료일
        
        // 수업 선택이 있으면 수업 제목으로 검색 조건 추가  
        if ($searchVal['gx_clas_id'] != '') {
            $DupGetVal['gx_clas_title'] = $searchVal['gx_clas_id']; // 수업 제목을 직접 사용
        }
        
        $totalCount  = $clasModel->list_tgx_schd_count($DupGetVal);
        $gx_list = $clasModel->list_tgx_schd($DupGetVal);
        
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        $tdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $tdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $list_tchr = $memModel->get_list_tchr($tdata);
        
        $tchr_list = array();
        $sDef = SpoQDef();
        $tchr_i = 0;
        foreach ($list_tchr as $t) :
        $tchr_list[$tchr_i] = $t;
        $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
        $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
        $tchr_i++;
        endforeach;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['tchr_list'] = $tchr_list; // 강사리스트
        $data['view']['totalCount'] = $totalCount; // 총 건수
        $data['view']['gx_list'] = $gx_list; // GX 수업 체크
        $this->viewPage('/tchr/tsalesmain/gx_class_attd_list',$data);
    }
    
    /**
     * 수업진행 현황 AJAX 검색
     */
    public function ajax_month_class_sales_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $salesModel = new SalesModel();
        
        $searchVal = $boardPage->getVal();
        
        // AJAX 검색에서는 mem_sno 항상 제거 (첫 진입 이후의 모든 검색)
        if (isset($searchVal['mem_sno'])) {
            unset($searchVal['mem_sno']);
        }
        
        if ( !isset($searchVal['stnm']) ) $searchVal['stnm'] = ''; //강사명
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        
        if ( !isset($searchVal['scyn']) ) $searchVal['scyn'] = ''; // 자동차감
        if ( !isset($searchVal['scdv']) ) $searchVal['scdv'] = ''; // 정규/서비스 구분
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'sc'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] =  date('Y-m'); // 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }
        
        $sumCost = $salesModel->list_pt_clas_mgmt_sum_cost($searchVal);
        $totalCount  = $salesModel->list_pt_clas_mgmt_count($searchVal);
        $sales_list = $salesModel->list_pt_clas_mgmt($searchVal);

        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // ===========================================================================
        // HTML 생성
        // ===========================================================================
        $sDef = SpoqDef();
        $html = '';
        
        $html .= '총 건수 : ' . number_format($totalCount) . ' 건 / 총 수업금액 : ' . number_format($sumCost) . ' 원' . "\n";
        $html .= '<table class="table table-bordered table-hover col-md-12 mt20">' . "\n";
        $html .= '<thead>' . "\n";
        $html .= '<tr class="text-center">' . "\n";
        $html .= '<th style="width:55px">순번</th>' . "\n";
        $html .= '<th style="width:130px">수업강사</th>' . "\n";
        $html .= '<th style="width:150px">회원명(ID)</th>' . "\n";
        $html .= '<th style="width:100px">수업구분</th>' . "\n";
        $html .= '<th>판매상품명</th>' . "\n";
        $html .= '<th style="width:75px">자동차감</th>' . "\n";
        $html .= '<th style="width:75px">정규<br/>서비스</th>' . "\n";
        $html .= '<th style="width:75px">정규<br/> 수업수</th>' . "\n";
        $html .= '<th style="width:75px">정규<br/>남은수</th>' . "\n";
        $html .= '<th style="width:75px">정규<br/>진행수</th>' . "\n";
        $html .= '<th style="width:75px">서비스<br/>남은수</th>' . "\n";
        $html .= '<th style="width:75px">서비스<br/>진행수</th>' . "\n";
        $html .= '<th style="width:100px">수업금액</th>' . "\n";
        $html .= '<th style="width:100px">수업요일</th>' . "\n";
        $html .= '<th style="width:140px">수업일시</th>' . "\n";
        $html .= '</tr>' . "\n";
        $html .= '</thead>' . "\n";
        $html .= '<tbody>' . "\n";
        
        foreach($sales_list as $r) {
            $backColor = "";
            if ($r['PT_CLAS_DV'] == "01") $backColor = "#cfecf0";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">' . "\n";
            $html .= '<td class="text-center">' . $searchVal['listCount'] . '</td>' . "\n";
            $html .= '<td class="text-center">' . "\n";
            
            // 강사 사진 처리
            if(isset($r['TCHR_THUMB_IMG']) || isset($r['TCHR_GENDR'])) {
                $tchr_thumb = isset($r['TCHR_THUMB_IMG']) ? htmlspecialchars($r['TCHR_THUMB_IMG']) : '';
                $tchr_main = isset($r['TCHR_MAIN_IMG']) ? htmlspecialchars($r['TCHR_MAIN_IMG']) : '';
                $tchr_gendr = isset($r['TCHR_GENDR']) ? $r['TCHR_GENDR'] : 'M';
                
                $html .= '<img class="preview_mem_photo" ';
                $html .= 'id="preview_tchr_photo_' . $r['TCHR_SNO'] . '" ';
                $html .= 'src="' . $tchr_thumb . '" ';
                $html .= 'alt="강사사진" style="cursor: pointer;" ';
                $html .= 'onclick="showFullPhoto(\'' . $tchr_main . '\')" ';
                $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $tchr_gendr . '.png\';">';
            }
            $html .= $r['STCHR_NM'] . "\n";
            $html .= '</td>' . "\n";
            
            $html .= '<td>' . "\n";
            // 회원 사진 처리
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $mem_thumb = isset($r['MEM_THUMB_IMG']) ? htmlspecialchars($r['MEM_THUMB_IMG']) : '';
                $mem_main = isset($r['MEM_MAIN_IMG']) ? htmlspecialchars($r['MEM_MAIN_IMG']) : '';
                $mem_gendr = isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M';
                
                $html .= '<img class="preview_mem_photo" ';
                $html .= 'id="preview_mem_photo_' . $r['MEM_SNO'] . '" ';
                $html .= 'src="' . $mem_thumb . '" ';
                $html .= 'alt="회원사진" style="cursor: pointer;" ';
                $html .= 'onclick="showFullPhoto(\'' . $mem_main . '\')" ';
                $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $mem_gendr . '.png\';">';
            }
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= $r['MEM_NM'];
            $html .= '</a>(' . $r['MEM_ID'] . ')' . "\n";
            $html .= '</td>' . "\n";
            
            $html .= '<td class="text-center">' . $sDef['CLAS_DV'][$r['CLAS_DV']] . '</td>' . "\n";
            $html .= '<td>' . $r['SELL_EVENT_NM'] . '</td>' . "\n";
            $html .= '<td class="text-center">' . $r['AUTO_CHK'] . '</td>' . "\n";
            $html .= '<td class="text-center">' . $sDef['PT_CLAS_DV'][$r['PT_CLAS_DV']] . '</td>' . "\n";
            $html .= '<td class="text-center">' . $r['CLAS_CNT'] . '</td>' . "\n";
            $html .= '<td class="text-center">' . $r['MEM_REGUL_CLAS_LEFT_CNT'] . '</td>' . "\n";
            $html .= '<td class="text-center">' . $r['MEM_REGUL_CLAS_PRGS_CNT'] . '</td>' . "\n";
            $html .= '<td class="text-center">' . $r['SRVC_CLAS_LEFT_CNT'] . '</td>' . "\n";
            $html .= '<td class="text-center">' . $r['SRVC_CLAS_PRGS_CNT'] . '</td>' . "\n";
            $html .= '<td style="text-align:right">' . number_format($r['TCHR_1TM_CLAS_PRGS_AMT']) . '</td>' . "\n";
            $html .= '<td class="text-center">' . $r['CLAS_CHK_DOTW'] . '</td>' . "\n";
            $html .= '<td>' . $r['CRE_DATETM'] . '</td>' . "\n";
            $html .= '</tr>' . "\n";
            
            $searchVal['listCount']--;
        }
        
        $html .= '</tbody>' . "\n";
        $html .= '</table>' . "\n";
        
        // 페이저 생성
        $pager = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 응답 데이터 구성
        // ===========================================================================
        $response = [
            'result' => 'true',
            'html' => $html,
            'pager' => '<div class="card-footer clearfix">' . $pager . '</div>'
        ];
        
        return json_encode($response);
    }

    /**
     * GX 수업 리스트 조회 API (Ajax)
     */
    public function getGxClassList()
    {
        header('Content-Type: application/json');
        
        try {
            $post = $this->request->getPost();
            
            $stchrId = $post['gx_stchr_id'] ?? '';
            $year = $post['syy'] ?? date('Y');
            $month = $post['smm'] ?? '';
            
            $comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
            $bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');
            
            // 날짜 범위 설정
            if ($month == '' || $month == '전체') {
                $sdate = $year . "-01-01";
                $edate = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 years", strtotime($sdate))))));
            } else {
                $sdate = $year . "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
                $edate = date("Y-m-d", strtotime("-1 days", strtotime(date("Y-m-d", strtotime("+1 months", strtotime($sdate))))));
            }
            
            $searchData = [
                'comp_cd' => $comp_cd,
                'bcoff_cd' => $bcoff_cd,
                'gx_stchr_id' => $stchrId,
                'sdate' => $sdate,
                'edate' => $edate,
                'limit_s' => 0,
                'limit_e' => 1000  // 충분한 개수
            ];
            
            $clasModel = new ClasModel();
            $gx_list = $clasModel->list_tgx_schd($searchData);
            
            // 수업 리스트를 드롭다운용으로 변환 (중복 제거)
            $class_options = [];
            $processed_classes = [];
            
            foreach ($gx_list as $item) {
                $class_key = $item['GX_CLAS_TITLE'];
                if (!in_array($class_key, $processed_classes)) {
                    $class_options[] = [
                        'gx_clas_id' => $item['GX_CLAS_TITLE'],  // 수업 제목을 ID로 사용
                        'gx_clas_title' => $item['GX_CLAS_TITLE']
                    ];
                    $processed_classes[] = $class_key;
                }
            }
            
            echo json_encode([
                'success' => true,
                'data' => $class_options,
                'total_count' => count($class_options)
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => '수업 리스트 조회 중 오류가 발생했습니다: ' . $e->getMessage(),
                'data' => []
            ]);
        }
        
        exit;
    }
    
    
    
    
    
    
    
    
    
    
    
    
}