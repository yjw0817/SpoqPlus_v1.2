<?php
namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Libraries\Ama_board;
use App\Libraries\MenuHelper;
use App\Models\CateModel;
use App\Models\EventModel;
use App\Libraries\Ama_sno;
use App\Models\MemModel;
use App\Models\VanModel;
use App\Libraries\Pay_lib;
use App\Models\LockrModel;
use App\Libraries\Refund_lib;
use App\Libraries\Send_lib;
use App\Models\SendModel;
use App\Libraries\Lockr_lib;

class Teventmain extends MainTchrController
{
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 상품 만들기 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 상품 만들기
     */
    public function create_event()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '기본 상품 만들기',
            'nav' => array('상품/구매 관리' => '' , '상품 만들기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // Process
        // ===========================================================================
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cate1 = $cateModel->cevent_u1rd_list($cateData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['cate1'] = $cate1;
        $this->viewPage('/tchr/teventmain/create_event',$data);
    }
    
    /**
     * 상품 만들기 add prod
     */
    public function create_event_add_prod($sno='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '추가 상품 만들기',
            'nav' => array('상품/구매 관리' => '' , '상품 만들기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // Process
        // ===========================================================================
        $cateModel = new CateModel();
        $eventModel = new EventModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $cate1nm = $cateModel->disp_cate1($cateData);
        $cate2nm = $cateModel->disp_cate2($cateData);
        
        $cate1_nm = array();
        foreach($cate1nm as $c1) :
            $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        endforeach;
        
        $cate2_nm = array();
        foreach($cate2nm as $c2) :
            $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        endforeach;
        
        $eventData = $cateData;
        $eventData['sell_event_sno'] = $sno;
        $eventInfo = $eventModel->get_event($eventData);
        
        $eventLastStep = $eventModel->get_event_last_step($eventData);
        
        $einfo['sell_event_sno'] = $eventInfo[0]['SELL_EVENT_SNO'];
        $einfo['1rd_cate_cd'] = $eventInfo[0]['1RD_CATE_CD'];
        $einfo['1rd_cate_nm'] = $cate1_nm[$eventInfo[0]['1RD_CATE_CD']];
        $einfo['2rd_cate_cd'] = $eventInfo[0]['2RD_CATE_CD'];
        $einfo['2rd_cate_nm'] = $cate2_nm[$eventInfo[0]['1RD_CATE_CD']][$eventInfo[0]['2RD_CATE_CD']];
        $einfo['sell_event_nm'] = $eventInfo[0]['SELL_EVENT_NM'];
        $einfo['clas_dv'] = $eventInfo[0]['CLAS_DV'];
        $einfo['event_step'] = $eventLastStep[0]['EVENT_STEP'];
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['einfo'] = $einfo;
        $this->viewPage('/tchr/teventmain/create_event_add_prod',$data);
    }
    
    /**
     * 상품 만들기 add acc
     */
    public function create_event_add_acc($sno='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '1일1회입장 상품 만들기',
            'nav' => array('상품/구매 관리' => '' , '상품 만들기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // ===========================================================================
        // Process
        // ===========================================================================
        $cateModel = new CateModel();
        $eventModel = new EventModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $cate1nm = $cateModel->disp_cate1($cateData);
        $cate2nm = $cateModel->disp_cate2($cateData);
        
        $cate1_nm = array();
        foreach($cate1nm as $c1) :
        $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        endforeach;
        
        $cate2_nm = array();
        foreach($cate2nm as $c2) :
        $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        endforeach;
        
        $eventData = $cateData;
        $eventData['sell_event_sno'] = $sno;
        $eventInfo = $eventModel->get_event($eventData);
        
        $einfo['sell_event_sno'] = $eventInfo[0]['EVENT_REF_SNO'];
        $einfo['1rd_cate_cd'] = $eventInfo[0]['1RD_CATE_CD'];
        $einfo['1rd_cate_nm'] = $cate1_nm[$eventInfo[0]['1RD_CATE_CD']];
        $einfo['2rd_cate_cd'] = $eventInfo[0]['2RD_CATE_CD'];
        $einfo['2rd_cate_nm'] = $cate2_nm[$eventInfo[0]['1RD_CATE_CD']][$eventInfo[0]['2RD_CATE_CD']];
        $einfo['sell_event_nm'] = $eventInfo[0]['SELL_EVENT_NM'];
        $einfo['event_acc_ref_sno'] = $eventInfo[0]['EVENT_ACC_REF_SNO'];
        
        $einfo['use_prod'] = $eventInfo[0]['USE_PROD'];
        $einfo['use_unit'] = $eventInfo[0]['USE_UNIT'];
        $einfo['sell_amt'] = $eventInfo[0]['SELL_AMT'];
        
        $einfo['domcy_day'] = $eventInfo[0]['DOMCY_DAY'];
        $einfo['domcy_cnt'] = $eventInfo[0]['DOMCY_CNT'];
        $einfo['domcy_poss_event_yn'] = $eventInfo[0]['DOMCY_POSS_EVENT_YN'];
        
        $einfo['clas_dv'] = $eventInfo[0]['CLAS_DV'];
        $einfo['event_step'] = $eventInfo[0]['EVENT_STEP'];
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['einfo'] = $einfo;
        $this->viewPage('/tchr/teventmain/create_event_add_acc',$data);
    }

    
    /**
     * 이용권 상품을 저장한다.
     * @return string
     */
    public function cate_save()
    {	
        $cateModel = new CateModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        
        $postVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $postVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $postVar['user_id'] = $_SESSION['user_id'];

        ;

        $return_json['result'] = $cateModel->cate_save($postVar) == 1 ? 'true' : "false";
        
        return json_encode($return_json);
    }


    /**
     * 이용권 상품을 저장한다.
     * @return string
     */
    public function event_save()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        
        $postVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $postVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $postVar['user_id'] = $_SESSION['user_id'];
        if( $postVar['type'] == "I")
        {
            $postVar['sell_event_sno'] = $amasno->create_sell_event_sno()['sell_event_sno'];
        }
        $postVar['event_acc_ref_sno'] = $postVar['sell_event_sno'];
        $return_json['sell_event_sno'] = $postVar['sell_event_sno'];
        
        $postVar['cate1'] = $postVar['m_cate'] . $postVar['lockr_set'];
        $postVar['cate2'] = $postVar['event_ref_sno'];
        $postVar['clas_dv'] = ($postVar['m_cate']=="PRV" ? "21" : ($postVar['m_cate']=="GRP" ? "31" : ""));
        $event_group_list = $cateModel->event_save($postVar);
        if( $postVar['type'] == "I")
            $cateModel->event_add_product_cnt($postVar, 1);
        
        $data = array();
        $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $model = new EventModel();
        
        $event_list = $model->list_event2($data);
        $return_json['event_list'] = $event_list;
        $return_json['result'] = 'true';
        
        return json_encode($return_json);
    }
    
    /**
     * 팩키지 상품을 저장한다.
     * @return string
     */
    public function pkg_save()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        
        $postVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $postVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $postVar['user_id'] = $_SESSION['user_id'];

        $mode = "U";
        // 팩키지 그룹 event sno가 없으면 event_sno 생성
        if($postVar['pkg_group_event_sno'] == "")
        {
            $mode = "I";
            $postVar["mode"] = $mode;
            $postVar['pkg_group_event_sno'] = $amasno->create_sell_event_sno()['sell_event_sno'];
            $postVar['sell_yn'] = 'Y';
            $cateModel->pkg_group_event_save($postVar);
        } 

        $mode = "U";
        if($postVar['pkg_sell_event_sno'] == "")
        {
            $mode = "I";
            
            $postVar['pkg_sell_event_sno'] = $amasno->create_sell_event_sno()['sell_event_sno'];
            $postVar['sell_yn'] = 'Y';
        }
        $postVar["mode"] = $mode;
        $cateModel->pkg_event_save($postVar);
        
        $sellEventSnoList = [];
        foreach($postVar["itemList"] as $c1) :
            $mode = "U";
            if (!isset($c1['sell_event_sno']) || !$c1['sell_event_sno']) {
                $mode = "I";
                $c1['sell_event_sno'] = $amasno->create_sell_event_sno()['sell_event_sno'];
                
            }
            $c1["type"] =  $mode ;
            $c1['comp_cd'] = $postVar['comp_cd'];
            $c1['bcoff_cd'] = $postVar['bcoff_cd'];
            $c1['user_id'] = $postVar['user_id'];
            $c1['event_acc_ref_sno'] = $c1['sell_event_sno'];
            $c1['event_ref_sno'] = $postVar['pkg_sell_event_sno'];
            $return_json['sell_event_sno'] = $c1['sell_event_sno'];
            $event_group_list = $cateModel->event_save($c1);
            $sellEventSnoList[] = $c1['sell_event_sno'];
            if( $c1["type"] == "I")
                $cateModel->event_add_product_cnt($c1, 1);
        endforeach;
        $deletedCnt = $cateModel->delete_sell_events1($postVar['pkg_sell_event_sno'], $sellEventSnoList);
        $c1 = [];
        $c1['event_ref_sno'] = $postVar['pkg_sell_event_sno'];
        $cateModel->event_add_product_cnt($c1, $deletedCnt * -1);  //삭제된 상품수 반영



        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $model = new EventModel();
        
        $event_list = $model->list_event2($cateData);

        // $foundArray = array_filter($event_list, function($item) use ($sell_event_sno) {
        //     return isset($item['SELL_EVENT_SNO']) && $item['SELL_EVENT_SNO'] === $sell_event_sno;
        // });
        // $itemObject = array();
        // $groupObject = array();
        
        // if($sell_event_sno!=""){
        //     $itemObject = !empty($foundArray) ? reset($foundArray) : null;
        //     $sell_event_sno = $itemObject["EVENT_REF_SNO"];
        //     $foundArray = array_filter($event_list, function($item) use ($sell_event_sno) {
        //         return isset($item['SELL_EVENT_SNO']) && $item['SELL_EVENT_SNO'] === $sell_event_sno;
        //     });
        //     $groupObject = !empty($foundArray) ? reset($foundArray) : null;
            
        // }
        // $return_json['itemObject'] = $itemObject;
        // $return_json['groupObject'] = $groupObject;
        
        $return_json['pkg_group_event_sno'] = $postVar['pkg_group_event_sno'];
        $return_json['pkg_sell_event_sno'] = $postVar['pkg_sell_event_sno'];
         
        $return_json['event_list'] = $event_list;
        $return_json['result'] = 'true';
        
        return json_encode($return_json);
    }


    /**
     * 이용권 그룹을 삭제한다.
     * @return string
     */
    public function event_group_delete()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        
        $model = new EventModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        $cateModel->event_group_delete($postVar);
        
        $return_json['result'] = 'true';
        
        return json_encode($return_json);
    }
    

    /**
     * 이용권 판매수량을 가져온다.
     * @return string
     */
    public function event_sold_cnt()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        
        $model = new EventModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        $sold_cnt = $cateModel->event_sold_cnt($postVar);

        $return_json['result'] = 'true';
        $return_json["sold_cnt"] = $sold_cnt;
        
        return json_encode($return_json);
    }

    /**
     * 상품을 회원 모바일에 보여질지 여부를 변경한다.
     * @return string
     */
    public function change_mobile_disp()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        
        $postVar = $this->request->getPost();
        $result = $cateModel->change_mem_disp_yn($postVar);
        
        $return_json['result'] = ($result > 0 ? "true" : "false");
        
        return json_encode($return_json);
    }


    /**
     * 이용권 / 그룹의 판매 여부를 변경한다.
     * @return string
     */
    public function change_sell_yn()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        
        $model = new EventModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        $category = $postVar['category'];
        $sellYn = $postVar['sell_yn'];
        $result = true;
        if($category == "group")
        {
            if($sellYn == 'Y')
                $cateModel->change_sell_yn($postVar);
            else
            {
                $cnt = $cateModel->cnt_event_sell_yn($postVar);
                if($cnt > 0)  //판매중인 상품들이 있어서 판매중지로 변경할수 없습니다.
                {
                    $result = false;
                } else
                {
                    
                    $cateModel->change_sell_yn($postVar);
                }
            }
        } else
        {
            $cateModel->change_sell_yn($postVar);
        }
        
        if($result)
        {
            $data = array();
            $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $model = new EventModel();
            
            $event_list = $model->list_event2($data);
            $return_json['event_list'] = $event_list;
        } else
        {
            $return_json['msg'] = "판매중인 이용권이 존재하여 판매중지로 변경할수 없습니다.";
        }
        
        $return_json['result'] = ($result == true ? "true" : "false");
        
        return json_encode($return_json);
    }

    /**
     * 이용권 삭제한다.
     * @return string
     */
    public function event_delete()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        
        $model = new EventModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        $cateModel->event_delete($postVar);
        $cateModel->event_add_product_cnt($postVar, -1);

        $data = array();
        $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $model = new EventModel();
        
        $event_list = $model->list_event2($data);
        $return_json['event_list'] = $event_list;
        $return_json['result'] = 'true';
        
        return json_encode($return_json);
    }

    /**
     * 이용권 그룹을 저장한다.
     * @return string
     */
    public function event_group_save()
    {	
        $amasno = new Ama_sno();
        $cateModel = new CateModel();
        
        $model = new EventModel();
        // JSON 데이터를 받아서 PHP 배열로 변환
        $postVar = $this->request->getPost();
        
        $postVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $postVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $postVar['user_id'] = $_SESSION['user_id'];
        $postVar['clas_dv'] = ($postVar['m_cate']=="PRV" ? "21" : ($postVar['m_cate']=="GRP" ? "31" : ""));
        if($postVar["type"] == "I")
        {
            $postVar['sell_event_sno'] = $amasno->create_sell_event_sno()['sell_event_sno'];
            $postVar['cate1'] = $postVar['m_cate'] . $postVar['lockr_set'];
            $postVar['cate2'] = $postVar['sell_event_sno'];
            $postVar['event_acc_ref_sno'] = $postVar['sell_event_sno'];
            $postVar['event_ref_sno'] = $postVar['sell_event_sno'];
        }
        $cateModel->event_group_save($postVar);
        $data = array();
        $data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $event_list = $model->list_event2($data);
        
        $return_json['result'] = 'true';
        $return_json['event_list'] = $event_list;
        $return_json['sell_event_sno'] = $postVar['sell_event_sno'];
        
        return json_encode($return_json);
    }

    /**
     * 상품만들기에서 대분류 선택시 그에 해당하는 이용권를 리턴한다.
     * @return string
     */
    public function ajax_event_group_list()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['m_cate'] = $postVar['m_cate'];
        $event_group_list = $cateModel->event_group_list($cateData);
        
        $return_json['result'] = 'true';
        $return_json['event_group_list'] = $event_group_list;
        
        return json_encode($return_json);
    }
    

    

    /**
     * 상품만들기에서 대분류 선택시 그에 해당하는 이용권를 리턴한다.
     * @return string
     */
    public function ajax_cate2_list()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['1rd_cate_cd'] = $postVar['cate1'];
        $cate2 = $cateModel->cevent_u2rd_list($cateData);
        
        $return_json['result'] = 'true';
        $return_json['cate2'] = $cate2;
        
        return json_encode($return_json);
    }
    
    /**
     * buy_event_manage1 페이지용 대분류에 따른 중분류 목록 AJAX
     */
    public function ajax_sch_cate2_by_1rd()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['1rd_cate_cd'] = $postVar['search_cate1'];
        $cate2 = $cateModel->sch_cate2_by_1rd($cateData);
        
        $return_json['result'] = 'true';
        $return_json['cate2'] = $cate2;
        
        return json_encode($return_json);
    }
    
    /**
     * buy_event_manage2 페이지용 검색 분류 대분류에 따른 중분류 목록 AJAX
     */
    public function ajax_sch_cate2_by_1rd_set1()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['1rd_cate_cd'] = $postVar['search_cate1'];
        $cate2 = $cateModel->sch_cate2_by_1rd($cateData);
        
        $return_json['result'] = 'true';
        $return_json['cate2'] = $cate2;
        
        return json_encode($return_json);
    }
    
    /**
     * buy_event_manage2 페이지용 미구매 분류 대분류에 따른 중분류 목록 AJAX
     */
    public function ajax_sch_cate2_by_1rd_set2()
    {
        $postVar = $this->request->getPost();
        $cateModel = new CateModel();
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $cateData['1rd_cate_cd'] = $postVar['search_cate11'];
        $cate2 = $cateModel->sch_cate2_by_1rd($cateData);
        
        $return_json['result'] = 'true';
        $return_json['cate2'] = $cate2;
        
        return json_encode($return_json);
    }
    
    /**
     * 상품 만들기를 처리한다.
     */
    public function create_event_proc()
    {
    	$event_model = new EventModel();
    	$cate_model = new CateModel();
    	$nn_now = new Time('now');
    	$amasno = new Ama_sno();
    	
    	$postVar = $this->request->getPost();
    	
    	if ($postVar['sell_event_sno_base'] == "") :
    	   $sell_event_no_base = $amasno->create_sell_event_sno();
    	else :
    	   $sell_event_no_base = $postVar['sell_event_sno_base'];
    	endif;
    	
    	$sell_event_no = $amasno->create_sell_event_sno();
    	
    	if ($postVar['sell_event_sno_base'] == "") :
    	
    	$base_data['sell_event_sno'] = $sell_event_no_base;
    	$base_data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$base_data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$base_data['1rd_cate_cd'] = $postVar['1rd_cate_cd'];
    	$base_data['2rd_cate_cd'] = $postVar['2rd_cate_cd'];
    	$base_data['sell_event_nm'] = $postVar['sell_event_nm'];
    	$base_data['clas_dv'] = $postVar['clas_dv'];
    	
    	$base_data['event_ref_sno'] = $sell_event_no_base;
    	$base_data['event_step'] = "1";
    	$base_data['event_depth'] = "1";
    	$base_data['event_acc_ref_sno'] = $sell_event_no_base;
    	
    	$base_data['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	$base_data['cre_datetm'] = $nn_now;
    	$base_data['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	$base_data['mod_datetm'] = $nn_now;
    	$base_data['sell_yn'] = "N";
    	
    	$insert_sell_event_base = $event_model->insert_sell_event_base($base_data);

    	endif;
    	
    	$cate_data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$cate_data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$cate_data['1rd_cate_cd'] = $postVar['1rd_cate_cd'];
    	$cate_data['2rd_cate_cd'] = $postVar['2rd_cate_cd'];
    	
    	$cate2_info = $cate_model->get_2rd_cate_info($cate_data);
    	
    	$upVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$upVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$upVar['event_ref_sno'] = $sell_event_no_base;
    	$upVar['event_step'] = $postVar['event_step'];
    	
    	$update_event_step = $event_model->update_event_step($upVar);
    	
    	$data['grp_cate_set'] = $cate2_info[0]['GRP_CATE_SET'];
    	$data['lockr_set'] = $cate2_info[0]['LOCKR_SET'];
    	$data['lockr_gendr_set'] = $cate2_info[0]['LOCKR_GENDR_SET'];
    	$data['lockr_knd'] = $cate2_info[0]['LOCKR_KND'];
    	
    	$data['sell_event_sno'] = $sell_event_no;
    	$data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$data['1rd_cate_cd'] = $postVar['1rd_cate_cd'];
    	$data['2rd_cate_cd'] = $postVar['2rd_cate_cd'];
    	$data['sell_event_nm'] = $postVar['sell_event_nm'];
    	$data['sell_amt'] = put_num($postVar['sell_amt']);
    	$data['use_prod'] = $postVar['use_prod'];
    	$data['use_unit'] = $postVar['use_unit'];
    	$data['clas_cnt'] = $postVar['clas_cnt'];
    	$data['domcy_day'] = $postVar['domcy_day'];
    	$data['domcy_cnt'] = $postVar['domcy_cnt'];
    	$data['domcy_poss_event_yn'] = $postVar['domcy_poss_event_yn'];
    	$data['acc_rtrct_dv'] = $postVar['acc_rtrct_dv'];
    	$data['acc_rtrct_mthd'] = $postVar['acc_rtrct_mthd'];
    	$data['clas_dv'] = $postVar['clas_dv'];
    	$data['mem_disp_yn'] = $postVar['mem_disp_yn'];
    	$data['sell_s_date'] = $postVar['sell_s_date'];
    	$data['sell_e_date'] = $postVar['sell_e_date'];
    	$data['event_img'] = "";
    	$data['event_icon'] = "";
    	
    	$data['sell_stat'] = "01";
    	
    	$data['event_ref_sno'] = $sell_event_no_base;
    	$data['event_step'] = $postVar['event_step'] + 1;
    	$data['event_depth'] = $postVar['event_depth'];
    	$data['event_acc_ref_sno'] = $sell_event_no;
    	
    	$data['grp_clas_psnnl_cnt'] = "";
    	
    	$data['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	$data['cre_datetm'] = $nn_now;
    	$data['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
    	$data['mod_datetm'] = $nn_now;
    	$data['sell_yn'] = "Y";
    	
    	$insert_sell_event = $event_model->insert_sell_event($data);
    	
    	scriptLocation("/teventmain/event_manage/".$postVar['1rd_cate_cd']."/".$postVar['2rd_cate_cd']);
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 상품 생성성 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 팩키지 상품 관리
     */
    public function package_manage_create($pkg_event_sno="")
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $model = new EventModel();
        
        $event_list = $model->list_event2($cateData);
        
        $data['view']['pkg_event_sno'] = $pkg_event_sno;
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
       
        $foundArray = array_filter($event_list, function($item) use ($pkg_event_sno) {
            return isset($item['SELL_EVENT_SNO']) && $item['SELL_EVENT_SNO'] === $pkg_event_sno;
        });
        $pkgItemObject = array();
        $pkgGroupObject = array();
        $itemList = array();
        
        if($pkg_event_sno!=""){
            $pkgItemObject = !empty($foundArray) ? reset($foundArray) : null;
            $sell_event_sno = $pkgItemObject["EVENT_REF_SNO"];
            $foundArray = array_filter($event_list, function($item) use ($sell_event_sno) {
                return isset($item['SELL_EVENT_SNO']) && $item['SELL_EVENT_SNO'] === $sell_event_sno;
            });

            $pkgGroupObject = !empty($foundArray) ? reset($foundArray) : null;

            $foundArray = array_filter($event_list, function($item) use ($pkg_event_sno) {
                return isset($item['SELL_EVENT_SNO']) && $item['SELL_EVENT_SNO'] != $pkg_event_sno && $item['EVENT_REF_SNO'] === $pkg_event_sno;
            });

            $itemList = !empty($foundArray) ? reset($foundArray) : null;
            
        }

        $data['view']['pkgItemObject'] = $pkgItemObject;  //팩키지 이용권
        $data['view']['pkgGroupObject'] = $pkgGroupObject; //팩키지 그룹 
        $data['view']['itemList'] =  $itemList;   //이용권
        $data['view']['event_list'] = $event_list;
        
        $this->viewPage('/tchr/teventmain/package_manage_create',$data);
    }

    



    /**
     * 상품 관리
     */
    public function event_manage_create($sell_event_sno="")
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        // list($sell_event_sno, $m_cate) = explode('|', $event_info);
        $url = "event_manage_create";
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $model = new EventModel();
        
        // ===========================================================================
        // Process
        // ===========================================================================
        $cateModel = new CateModel();
        
        $cate1 = $cateModel->cevent_u1rd_list($cateData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['cate1'] = $cate1;
        
        $event_list = $model->list_event2($cateData);
        
        $data['view']['sell_event_sno'] = $sell_event_sno;
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
       
        $foundArray = array_filter($event_list, function($item) use ($sell_event_sno) {
            return isset($item['SELL_EVENT_SNO']) && $item['SELL_EVENT_SNO'] === $sell_event_sno;
        });
        $itemObject = array();
        $groupObject = array();
        
        if($sell_event_sno!=""){
            $itemObject = !empty($foundArray) ? reset($foundArray) : null;
            $sell_event_sno = $itemObject["EVENT_REF_SNO"];
            $foundArray = array_filter($event_list, function($item) use ($sell_event_sno) {
                return isset($item['SELL_EVENT_SNO']) && $item['SELL_EVENT_SNO'] === $sell_event_sno;
            });
            $groupObject = !empty($foundArray) ? reset($foundArray) : null;
            
        }
        $data['view']['itemObject'] = $itemObject;
        $data['view']['groupObject'] = $groupObject;
        $data['view']['event_list'] = $event_list;
        
        $this->viewPage('/tchr/teventmain/event_manage_create', $data);
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 상품 관리2 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 상품 관리
     */
    public function event_manage2($one_cate_cd='',$two_cate_cd='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $model = new EventModel();
        
        $event_list = $model->list_event3($cateData);
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['event_list'] = $event_list;
        $this->viewPage('/tchr/teventmain/event_manage2',$data);
    }
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 상품 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 상품 관리
     */
    public function event_manage($one_cate_cd='',$two_cate_cd='')
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $cateModel = new CateModel();
        $cate1nm = $cateModel->disp_cate1($cateData);
        $cate2nm = $cateModel->disp_cate2($cateData);
        
        $cate1_nm = array();
        foreach($cate1nm as $c1) :
            if ($c1['USE_YN'] == 'Y') $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        endforeach;
        
        $cate2_nm = array();
        foreach($cate2nm as $c2) :
            if ($c2['USE_YN'] == 'Y') $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        endforeach;
        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        if ($one_cate_cd != '')
        {
            $initVar['get']['1rd_cd'] = $one_cate_cd;
        }
        
        if ($two_cate_cd != '')
        {
            $initVar['get']['2rd_cd'] = $two_cate_cd;
        }
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount  = $model->list_event_count($boardPage->getVal());
        $event_list = $model->list_event($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['day_n']) ) $searchVal['day_n'] = 'Y';
        if ( !isset($searchVal['1rd_cd']) ) $searchVal['1rd_cd'] = '';
        if ( !isset($searchVal['2rd_cd']) ) $searchVal['2rd_cd'] = '';
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['cate1_nm'] = $cate1_nm;
        $data['view']['cate2_nm'] = $cate2_nm;
        $data['view']['event_list'] = $event_list;
        $this->viewPage('/tchr/teventmain/event_manage',$data);
    }
    
    
    public function ajax_event_setting_info()
    {
        $postVar = $this->request->getPost();
        $modelEvent = new EventModel();
        
        $eventData['comp_cd'] = $_SESSION['comp_cd'];
        $eventData['bcoff_cd'] = $_SESSION['bcoff_cd'];
        $eventData['sell_event_sno'] = $postVar['sell_event_sno'];
        
        $sell_info = $modelEvent->get_event($eventData);
        
        $return_json['info'] = $sell_info[0];
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    public function ajax_event_setting_proc()
    {
        $postVar = $this->request->getPost();
        $modelEvent = new EventModel();
        
        $eventData['comp_cd'] = $_SESSION['comp_cd'];
        $eventData['bcoff_cd'] = $_SESSION['bcoff_cd'];
        $eventData['sell_event_sno'] = $postVar['sell_event_sno'];
        $eventData['sell_s_date'] = $postVar['sell_s_date'];
        $eventData['sell_e_date'] = $postVar['sell_e_date'];
        $eventData['mem_disp_yn'] = $postVar['mem_disp_yn'];
        
        $modelEvent->update_sell_evnet_setting($eventData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 락커 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 락커 관리
     */
    public function lockr_manage($lockr_knd="01",$gendr="M",$srange=0)
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $lockr_inf['lockr_knd'] = $lockr_knd;
        $lockr_inf['gendr'] = $gendr;
        $lockr_inf['srange'] = $srange;
        
        // 해당 락커의 시작 번호와 끝번호 불러오기
        $lockrModel = new LockrModel();
        
        $Ldata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $Ldata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $Ldata['lockr_knd'] = $lockr_knd;
        $Ldata['lockr_gendr_set'] = $gendr;
        
        $lockr_minmax = $lockrModel->select_lockr_minmax($Ldata);
        
        // 시작번호와 끝번호를 이용하여 100 개씩 표현해야한다. [1 ~ 100] [101~200]
        $min_no = 1;
        $max_no = 1;
        
        if (count($lockr_minmax)>0)
        {
            $min_no = $lockr_minmax[0]['min'] / 100;
            $max_no = $lockr_minmax[0]['max'] / 100;
        }
        
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
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['lockr_range'] = $new_lockr_range;
        $data['view']['list_room'] = $list_room;
        $data['view']['lockr_inf'] = $lockr_inf;
        $this->viewPage('/tchr/teventmain/lockr_manage',$data);
    }
    
    /**
     * 골프라커 관리
     */
    public function lockr_manage2($lockr_knd="02",$gendr="M",$srange=0)
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '락커 관리',
            'nav' => array('상품/구매 관리' => '' , '락커 관리' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $lockr_inf['lockr_knd'] = $lockr_knd;
        $lockr_inf['gendr'] = $gendr;
        $lockr_inf['srange'] = $srange;
        
        // 해당 락커의 시작 번호와 끝번호 불러오기
        $lockrModel = new LockrModel();
        
        $Ldata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $Ldata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $Ldata['lockr_knd'] = $lockr_knd;
        $Ldata['lockr_gendr_set'] = $gendr;
        
        $lockr_minmax = $lockrModel->select_lockr_minmax($Ldata);
        
        // 시작번호와 끝번호를 이용하여 100 개씩 표현해야한다. [1 ~ 100] [101~200]
        
        $min_no = 1;
        $max_no = 1;
        
        if (count($lockr_minmax)>0)
        {
            $min_no = $lockr_minmax[0]['min'] / 100;
            $max_no = $lockr_minmax[0]['max'] / 100;
        }
        
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
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['lockr_range'] = $new_lockr_range;
        $data['view']['list_room'] = $list_room;
        $data['view']['lockr_inf'] = $lockr_inf;
        $this->viewPage('/tchr/teventmain/lockr_manage2',$data);
    }
    
    /**
     * 라커 [고장] 설정
     */
    public function ajax_lockr_brok()
    {
        /**
         * knd,gendr,no,stat
         */
        $postVar = $this->request->getPost();
        
        $initVar['knd'] = $postVar['knd'];
        $initVar['gendr'] = $postVar['gendr'];
        $initVar['no'] = $postVar['no'];
        $initVar['stat'] = $postVar['stat'];
        $lockrLib = new Lockr_lib($initVar);
        
        $lockrLib->run_lockr_brok();
        
        $return_json['result'] = 'true';
        $return_json['msg'] = "고장 설정";
        return json_encode($return_json);
    }
    
    /**
     * 라커 [고장 해제] 설정
     */
    public function ajax_lockr_brokn()
    {
        /**
         * knd,gendr,no,stat
         */
        $postVar = $this->request->getPost();
        
        $initVar['knd'] = $postVar['knd'];
        $initVar['gendr'] = $postVar['gendr'];
        $initVar['no'] = $postVar['no'];
        $initVar['stat'] = $postVar['stat'];
        $lockrLib = new Lockr_lib($initVar);
        
        $lockrLib->run_lockr_brokn();
        
        $return_json['result'] = 'true';
        $return_json['msg'] = "고장 해제 설정";
        return json_encode($return_json);
    }
    
    /**
     * 라커 [비우기] 설정
     */
    public function ajax_lockr_empty()
    {
        /**
         * knd,gendr,no,stat
         */
        $postVar = $this->request->getPost();
        
        $initVar['knd'] = $postVar['knd'];
        $initVar['gendr'] = $postVar['gendr'];
        $initVar['no'] = $postVar['no'];
        $initVar['stat'] = $postVar['stat'];
        $lockrLib = new Lockr_lib($initVar);
        
        $lockrLib->run_lockr_empty();
        
        $return_json['result'] = 'true';
        $return_json['msg'] = "비우기 설정";
        return json_encode($return_json);
    }
    
    /**
     * 라커 [이동] 설정
     */
    public function ajax_lockr_move()
    {
        /**
         * knd,gendr,no,stat
         */
        $postVar = $this->request->getPost();
        
        $initVar['knd'] = $postVar['knd'];
        $initVar['gendr'] = $postVar['gendr'];
        $initVar['no'] = $postVar['no'];
        $initVar['stat'] = $postVar['stat'];
        $initVar['af_no'] = $postVar['af_no'];
        $lockrLib = new Lockr_lib($initVar);
        
        $lockr_result = $lockrLib->run_lockr_move();
        
        $return_json['result'] = $lockr_result['result'];
        $return_json['msg'] = "비우기 설정";
        return json_encode($return_json);
    }
    
    
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 보내기 상품 선택 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 보내기 상품 선택
     */
    public function send_event($mem_sno = "")
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '보내기 상품 선택하기',
            'nav' => array('상품/구매 관리' => '' , '보내기 상품 선택하기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $cateModel = new CateModel();
        $cate1nm = $cateModel->disp_cate1($cateData);
        $cate2nm = $cateModel->disp_cate2($cateData);
        
        $cate1_nm = array();
        foreach($cate1nm as $c1) :
        $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        endforeach;
        
        $cate2_nm = array();
        foreach($cate2nm as $c2) :
        $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        endforeach;
        
        $memModel = new MemModel();
        // 회원 정보를 가져온다.
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $get_tchr_list = $memModel->get_list_tchr($mdata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
        $tchr_list[$tchr_i] = $t;
        $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
        $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
        $tchr_i++;
        endforeach;
        
        // 상품보내기가 통합회원정보를 통하여 보내기를 했을 경우.
        if ($mem_sno != '')
        {
            // 종료되지 않은 예약됨, 이용중인 상품에 대한 리스트를 가져온다.
            $eventModel = new EventModel();
            
            // 구매상품 정보를 가져온다.
            $edata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $edata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $edata['mem_sno'] = $mem_sno;
            $edata['event_stat'] = "00"; // 이용중
            $event_list_00 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
            
            $edata['event_stat'] = "01"; // 예약됨
            $event_list_01 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
        } else 
        {
            $event_list_00 = array();
            $event_list_01 = array();
        }
        
        
        // ===========================================================================
        // Board Pagzing
        // ===========================================================================
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount  = $model->list_event_count($boardPage->getVal());
        $event_list = $model->list_event($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['day_n']) ) $searchVal['day_n'] = 'Y';
        if ( !isset($searchVal['acc_dv']) ) $searchVal['acc_dv'] = '';
        if ( !isset($searchVal['acc_mthd']) ) $searchVal['acc_mthd'] = '';
        if ( !isset($searchVal['1rd_cd']) ) $searchVal['1rd_cd'] = '';
        if ( !isset($searchVal['2rd_cd']) ) $searchVal['2rd_cd'] = '';
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $data['view']['event_list_00'] = $event_list_00;
        $data['view']['event_list_01'] = $event_list_01;
        
        $data['view']['mem_sno'] = $mem_sno;
        
        $data['view']['cate1_nm'] = $cate1_nm;
        $data['view']['cate2_nm'] = $cate2_nm;
        $data['view']['tchr_list'] = $tchr_list;
        $data['view']['event_list'] = $event_list;
        $this->viewPage('/tchr/teventmain/send_event',$data);
    }

    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 보내기 상품 선택 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 보내기 상품 선택
     */
    public function send_event2($mem_sno = "")
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '보내기 상품 선택하기',
            'nav' => array('상품/구매 관리' => '' , '보내기 상품 선택하기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $cateModel = new CateModel();
        $cate1nm = $cateModel->disp_cate1($cateData);
        $cate2nm = $cateModel->disp_cate2($cateData);
        
        $cate1_nm = array();
        foreach($cate1nm as $c1) :
        $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        endforeach;
        
        $cate2_nm = array();
        foreach($cate2nm as $c2) :
        $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        endforeach;
        
        $memModel = new MemModel();
        // 회원 정보를 가져온다.
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $get_tchr_list = $memModel->get_list_tchr($mdata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
        $tchr_list[$tchr_i] = $t;
        $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
        $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
        $tchr_i++;
        endforeach;
        
        // 상품보내기가 통합회원정보를 통하여 보내기를 했을 경우.
        if ($mem_sno != '')
        {
            // 종료되지 않은 예약됨, 이용중인 상품에 대한 리스트를 가져온다.
            $eventModel = new EventModel();
            
            // 구매상품 정보를 가져온다.
            $edata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $edata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $edata['mem_sno'] = $mem_sno;
            $edata['event_stat'] = "00"; // 이용중
            $event_list_00 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
            
            $edata['event_stat'] = "01"; // 예약됨
            $event_list_01 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
        } else 
        {
            $event_list_00 = array();
            $event_list_01 = array();
        }
        
        
        
        $model = new EventModel();
        $event_list = $model->list_event3($cateData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $data['view']['event_list_00'] = $event_list_00;
        $data['view']['event_list_01'] = $event_list_01;
        
        // mem_sno를 배열로 변환
        $data['view']['mem_sno'] = $mem_sno != '' ? array($mem_sno) : array();
        
        $data['view']['cate1_nm'] = $cate1_nm;
        $data['view']['cate2_nm'] = $cate2_nm;
        $data['view']['tchr_list'] = $tchr_list;
        $data['view']['event_list'] = $event_list;
        $data['view']['mode'] = "send";
        $data['view']['title'] = "상품 보내기";
        $this->viewPage('/tchr/teventmain/event_buy2',$data);
    }
    
    /**
     * AJAX: 상품 보내기 파라미터 세션 저장
     */
    public function ajax_set_send_params()
    {
        $postVar = $this->request->getPost();
        
        // 세션에 파라미터 저장
        $_SESSION['send_event3_params'] = array(
            '1RD_CATE_CD' => isset($postVar['1RD_CATE_CD']) ? $postVar['1RD_CATE_CD'] : '',
            '2RD_CATE_CD' => isset($postVar['2RD_CATE_CD']) ? $postVar['2RD_CATE_CD'] : ''
        );
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 상품 보내기 (from buy_event_manage1)
     */
    public function send_event3($mem_sno = "")
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = array(
            'title' => '보내기 상품 선택하기',
            'nav' => array('상품/구매 관리' => '' , '보내기 상품 선택하기' => ''),
            'menu1' => $this->request->getGet('m1'),
            'menu2' => $this->request->getGet('m2')
        );
        
        // 세션에서 파라미터 읽기
        $cate1_cd = '';
        $cate2_cd = '';
        if (isset($_SESSION['send_event3_params'])) {
            // 1RD_CATE_CD의 앞 3글자만 사용
            $cate1_cd = substr($_SESSION['send_event3_params']['1RD_CATE_CD'], 0, 3);
            $cate2_cd = $_SESSION['send_event3_params']['2RD_CATE_CD'];
            // 사용 후 세션 삭제 (선택사항)
            // unset($_SESSION['send_event3_params']);
        }
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        // 카테고리 필터링 적용
        if ($cate1_cd != '') $cateData['1rd_cd'] = $cate1_cd;
        if ($cate2_cd != '') $cateData['2rd_cd'] = $cate2_cd;
        
        $cateModel = new CateModel();
        $cate1nm = $cateModel->disp_cate1($cateData);
        $cate2nm = $cateModel->disp_cate2($cateData);
        $cate2_selected_nm = '';

        $cate1_nm = array();
        foreach($cate1nm as $c1) :
            $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        endforeach;
        
        $cate2_nm = array();
        foreach($cate2nm as $c2) :
            $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
            if ($cate2_cd == $c2['2RD_CATE_CD']) $cate2_selected_nm = $c2['CATE_NM'];
        endforeach;
        
        $memModel = new MemModel();
        // 회원 정보를 가져온다.
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $get_tchr_list = $memModel->get_list_tchr($mdata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
            $tchr_list[$tchr_i] = $t;
            $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
            $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
            $tchr_i++;
        endforeach;
        
        // 상품보내기가 통합회원정보를 통하여 보내기를 했을 경우.
        if ($mem_sno != '')
        {
            // 종료되지 않은 예약됨, 이용중인 상품에 대한 리스트를 가져온다.
            $eventModel = new EventModel();
            
            // 구매상품 정보를 가져온다.
            $edata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $edata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $edata['mem_sno'] = $mem_sno;
            $edata['event_stat'] = "00"; // 이용중
            $event_list_00 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
            
            $edata['event_stat'] = "01"; // 예약됨
            $event_list_01 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
        } else 
        {
            $event_list_00 = array();
            $event_list_01 = array();
        }
        
        $model = new EventModel();
        
        // 카테고리 필터가 적용된 상품 목록 조회
        $eventData = $cateData;
        if ($cate1_cd != '') $eventData['1rd_cd'] ='';
        if ($cate2_cd != '') $eventData['2rd_cd'] ='';
        
        $event_list = $model->list_event3($eventData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $data['view']['event_list_00'] = $event_list_00;
        $data['view']['event_list_01'] = $event_list_01;
        
        $data['view']['mem_sno'] = $mem_sno;
        $data['view']['selected_cate1'] = $cate1_cd;
        $data['view']['selected_cate2'] = $cate2_cd;
        
        // 세션에 저장된 선택된 회원들 가져오기 - selected_mem_sno로 전달
        $data['view']['mem_sno'] = isset($_SESSION['send_mem_sno']) ? $_SESSION['send_mem_sno'] : array();
        
        $data['view']['cate1_nm'] = $cate1_nm;
        $data['view']['cate2_nm'] = $cate2_nm;
        $data['view']['cate2_selected_nm'] = $cate2_selected_nm;
        $data['view']['tchr_list'] = $tchr_list;
        $data['view']['event_list'] = $event_list;
        $data['view']['mode'] = "send";
        $data['view']['title'] = "상품 보내기";
        $this->viewPage('/tchr/teventmain/event_buy2',$data);
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 상품 구매하기 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 상품 구매하기
     */
    public function event_buy($mem_sno = "")
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = MenuHelper::getMenuData($this->request);
    	
    	$cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	
    	$cateModel = new CateModel();
    	$cate1nm = $cateModel->disp_cate1($cateData);
    	$cate2nm = $cateModel->disp_cate2($cateData);
    	
    	$cate1_nm = array();
    	foreach($cate1nm as $c1) :
    	$cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
    	endforeach;
    	
    	$cate2_nm = array();
    	foreach($cate2nm as $c2) :
    	$cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
    	endforeach;
    	
    	// ===========================================================================
    	// Board Pagzing
    	// ===========================================================================
    	
    	$initVar['post'] = $this->request->getPost();
    	$initVar['get'] = $this->request->getGet();
    	
    	$boardPage = new Ama_board($initVar);
    	$model = new EventModel();
    	
    	$totalCount  = $model->list_event_count($boardPage->getVal());
    	$event_list = $model->list_event($boardPage->getVal());
    	
    	$searchVal = $boardPage->getVal();
    	$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
    	
    	if ( !isset($searchVal['day_n']) ) $searchVal['day_n'] = 'Y';
    	if ( !isset($searchVal['1rd_cd']) ) $searchVal['1rd_cd'] = '';
    	if ( !isset($searchVal['2rd_cd']) ) $searchVal['2rd_cd'] = '';
    	
    	$data['view']['search_val'] = $searchVal;
    	$data['view']['pager'] = $boardPage->getPager($totalCount);
    	
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	$data['view']['mem_sno'] = $mem_sno;
    	
    	$data['view']['cate1_nm'] = $cate1_nm;
    	$data['view']['cate2_nm'] = $cate2_nm;
    	
    	$data['view']['event_list'] = $event_list;
    	$this->viewPage('/tchr/teventmain/event_buy',$data);
    }

    /**
     * 상품 구매하기
     */
    public function event_buy2($mem_sno = "")
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = MenuHelper::getMenuData($this->request);
        
        $cateData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cateData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $cateModel = new CateModel();
        $cate1nm = $cateModel->disp_cate1($cateData);
        $cate2nm = $cateModel->disp_cate2($cateData);
        
        $cate1_nm = array();
        foreach($cate1nm as $c1) :
        $cate1_nm[$c1['1RD_CATE_CD']] = $c1['CATE_NM'];
        endforeach;
        
        $cate2_nm = array();
        foreach($cate2nm as $c2) :
        $cate2_nm[$c2['1RD_CATE_CD']][$c2['2RD_CATE_CD']] = $c2['CATE_NM'];
        endforeach;
        
        $memModel = new MemModel();
        // 회원 정보를 가져온다.
        $mdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $mdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $get_tchr_list = $memModel->get_list_tchr($mdata);
        
        $tchr_list = array();
        $sDef = SpoqDef();
        $tchr_i = 0;
        foreach ($get_tchr_list as $t) :
        $tchr_list[$tchr_i] = $t;
        $tchr_list[$tchr_i]['TCHR_POSN_NM'] = $sDef['TCHR_POSN'][$t['TCHR_POSN']];
        $tchr_list[$tchr_i]['CTRCT_TYPE_NM'] = $sDef['CTRCT_TYPE'][$t['CTRCT_TYPE']];
        $tchr_i++;
        endforeach;
        
        // 상품보내기가 통합회원정보를 통하여 보내기를 했을 경우.
        if ($mem_sno != '')
        {
            // 종료되지 않은 예약됨, 이용중인 상품에 대한 리스트를 가져온다.
            $eventModel = new EventModel();
            
            // 구매상품 정보를 가져온다.
            $edata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $edata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $edata['mem_sno'] = $mem_sno;
            $edata['event_stat'] = "00"; // 이용중
            $event_list_00 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
            
            $edata['event_stat'] = "01"; // 예약됨
            $event_list_01 = $eventModel->list_buy_event_mem_sno_event_stat($edata);
        } else 
        {
            $event_list_00 = array();
            $event_list_01 = array();
        }
        
        
        
        $model = new EventModel();
        $event_list = $model->list_event3($cateData);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $data['view']['event_list_00'] = $event_list_00;
        $data['view']['event_list_01'] = $event_list_01;
        
        $data['view']['mem_sno'] = $mem_sno;
        
        $data['view']['cate1_nm'] = $cate1_nm;
        $data['view']['cate2_nm'] = $cate2_nm;
        $data['view']['tchr_list'] = $tchr_list;
        $data['view']['event_list'] = $event_list;
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['event_list'] = $event_list;
        $data['view']['title'] = "상품 구매하기";
    	
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	$data['view']['mem_sno'] = $mem_sno;
        $data['view']['mode'] = "buy";
    	$this->viewPage('/tchr/teventmain/event_buy2',$data);
    }
    
    /**
     * 상품 구매하기에서 회원명을 이용하여 검색을 한다.
     * @return string
     */
    public function ajax_event_buy_search_nm()
    {
    	$postVar = $this->request->getPost();
    	$memModel = new MemModel();
    	
    	$mData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$mData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$mData['mem_nm'] = $postVar['sname'];
    	
    	$mem_list = $memModel->search_like_mem_nm($mData);
    	
    	$SpoQ_def = SpoqDef();
    	
    	$search_mem_list = array();
    	$mem_i = 0;
    	foreach($mem_list as $r):
	    	$search_mem_list[$mem_i] = $r;
	    	$search_mem_list[$mem_i]['MEM_STAT_NM'] = $SpoQ_def['MEM_STAT'][$r['MEM_STAT']];
	    	$search_mem_list[$mem_i]['MEM_GENDR_NM'] = $SpoQ_def['MEM_GENDR'][$r['MEM_GENDR']];
	    	$search_mem_list[$mem_i]['DISP_MEM_TELNO'] = disp_phone($r['MEM_TELNO']);
	    	$search_mem_list[$mem_i]['DISP_BTHDAY'] = disp_birth($r['BTHDAY']);
    		$mem_i++;
    	endforeach;
    	
    	if ( count($search_mem_list) > 0 ) :
    		$return_json['result'] = 'true';
    	else :
    		$return_json['result'] = 'false';
    	endif;
    	
    	$return_json['search_mem_list'] = $search_mem_list;
    	
    	return json_encode($return_json);
    }
    
    public function ajax_send_event_info()
    {
        $postVar = $this->request->getPost();
        $eventModel = new EventModel();
        
        $eventData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $eventData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $eventData['sell_event_sno'] = $postVar['esno'];
        $get_event_info = $eventModel->get_event($eventData);
        $event_info = $get_event_info[0];
        
        $SpoQ_def = SpoqDef();
        $event_info['ACC_RTRCT_DV_NM'] = $SpoQ_def['ACC_RTRCT_DV2'][$event_info['ACC_RTRCT_DV']];
        if($event_info['ACC_RTRCT_DV'] == "00")
        {
            $event_info['ACC_RTRCT_MTHD_NM'] = "일일" . $event_info['USE_PER_DAY']  . '회';
            if($event_info['USE_PER_WEEK_UNIT'] != "0")
            {
                $event_info['ACC_RTRCT_MTHD_NM'] .= "주" . $event_info['USE_PER_WEEK'] . ( $event_info['USE_PER_WEEK_UNIT'] == "10" ? "회": "일");
            }
            
        } else
        {
            $event_info['ACC_RTRCT_DV_NM'] ="";
        }
        
        if ($event_info['USE_PROD'] > 0)
        {
            $event_info['USE_PROD_NM'] = $event_info['USE_PROD'] . $SpoQ_def['USE_UNIT'][$event_info['USE_UNIT']];
        } else 
        {
            $event_info['USE_PROD_NM'] = "";
        }
        
        if ($event_info['CLAS_CNT'] == 0) $event_info['CLAS_CNT'] = "";
        
        
        $return_json['event_info'] = $event_info;
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 상품 보내기 처리
     */
    public function send_event_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        /**
         * $initVar['comp_cd'];
         * $initVar['bcoff_cd'];
         * $initVar['send_sell_event_sno'];
         * $initVar['set_send_amt'];
         * $initVar['set_send_serv_clas_cnt'];
         * $initVar['set_send_serv_day'];
         * $initVar['set_send_domcy_cnt'];
         * $initVar['set_send_domcy_day'];
         * $initVar['set_ptchr_id_nm'];
         * $initVar['set_stchr_id_nm'];
         * $initVar['set_end_day'];
         * $initVar['set_send_mem_snos'];
         */
        
        $initVar = $postVar;
        $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $session_chk = true;
        if (isset($_SESSION['send_mem_sno']))
        {
            $initVar['set_send_mem_snos'] = implode(",", $_SESSION['send_mem_sno']);
            
        } else 
        {
            // send_mem_sno가 배열로 전달되는 경우 처리
            if (isset($postVar['send_mem_sno']) && is_array($postVar['send_mem_sno']))
            {
                $initVar['set_send_mem_snos'] = implode(",", $postVar['send_mem_sno']);
            }
            else 
            {
                $initVar['set_send_mem_snos'] = $postVar['send_mem_sno'];
            }
            $session_chk = false;
        }
        
        $sendLib = new Send_lib($initVar);
        $sendLib->send_event();
        
        unset($_SESSION['send_mem_sno']);
        
        scriptAlert("상품 보내기를 완료 하였습니다.");
        
        if ($session_chk == true)
        {
            scriptLocation("/teventmain/send_event_manage");
        } else 
        {
            // 배열인 경우 첫 번째 회원 번호로 이동
            if (is_array($postVar['send_mem_sno']))
            {
                scriptLocation("/ttotalmain/info_mem/".$postVar['send_mem_sno'][0]);
            }
            else
            {
                scriptLocation("/ttotalmain/info_mem/".$postVar['send_mem_sno']);
            }
        }
        
        exit();
    }
    
    /**
     * 구매할 상품에 대한 정보를 확인한다.
     * @param String $msno
     * @param String $esno
     */
    public function event_buy_info($send_sno="")
    {
    	// ===========================================================================
    	// 선언부
    	// ===========================================================================
    	$data = array(
    			'title' => '구매 상품 정보',
    			'nav' => array('상품/구매 관리' => '' , '상품 구매하기' => ''),
    			'menu1' => $this->request->getGet('m1'),
                'menu2' => $this->request->getGet('m2')
    	);
    	
    	
    	$memModel = new MemModel();
    	$eventModel = new EventModel();
    	$lockrModel = new LockrModel();
    	$sendModel = new SendModel();
    	$postVar = $this->request->getPost();
    	
    	if (isset($postVar['send_memsno']))
    	{
    	    $mem_sno = $postVar['send_memsno'];
    	    $sell_event_sno = $postVar['send_esno'];
    	} else 
    	{
    	    //$send_sno를 이용하여 고객일련번호와 send_esno를 가져온다.
    	    $sData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	    $sData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	    $sData['send_event_mgmt_sno'] = $send_sno;
    	    $send_event = $sendModel->get_send_event_mgmt($sData);
    	    
    	    $mem_sno = $send_event[0]['MEM_SNO'];
    	    $sell_event_sno = $send_event[0]['SELL_EVENT_SNO'];
    	}
    	
    	$initVar['pay_comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$initVar['pay_bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$initVar['pay_mem_sno'] = $mem_sno;
    	$initVar['pay_sell_sno'] = $sell_event_sno;
    	
    	
    	$paylib = new Pay_lib($initVar);
    	$chk_result = $paylib->buy_run_pre_check();
    	
        //상품 판매할때 출입제한조건 체크 안하도록 변경함
    	if($chk_result['result'] == false)
    	{
            scriptAlert($chk_result['result_msg']);
            echo "<script>";
            echo "history.back();";
            echo "</script>";
            exit();
    	}
    	
    	// ===========================================================================
    	// Process
    	// ===========================================================================
		
    	$memData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$memData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$memData['mem_sno'] = $mem_sno;
    	$get_mem_info = $memModel->get_mem_info_mem_sno($memData);
    	$mem_info = $get_mem_info[0];
    	
    	$eventData = $memData;
    	$eventData['sell_event_sno'] = $sell_event_sno;
    	$get_event_info = $eventModel->get_event($eventData);
    	$event_info = $get_event_info[0];
    	
    	// $event_info 에서 보내기 상품일 경우에는 보내기 상품에서 수정한 정보로 바꿔야한다.
    	if (!isset($postVar['send_memsno']))
    	{
    		$event_info['SEND_EVENT_SNO'] = $send_event[0]['SEND_EVENT_MGMT_SNO'];
    		$event_info['SELL_AMT'] = $send_event[0]['SELL_AMT'];
    		$event_info['USE_PROD'] = $send_event[0]['USE_PROD'];
    		$event_info['USE_UNIT'] = $send_event[0]['USE_UNIT'];
    		$event_info['CLAS_CNT'] = $send_event[0]['CLAS_CNT'];
    		$event_info['DOMCY_DAY'] = $send_event[0]['DOMCY_DAY'];
    		$event_info['DOMCY_CNT'] = $send_event[0]['DOMCY_CNT'];
    		$event_info['ADD_SRVC_EXR_DAY'] = $send_event[0]['ADD_SRVC_EXR_DAY'];
    		$event_info['ADD_SRVC_CLAS_CNT'] = $send_event[0]['ADD_SRVC_CLAS_CNT'];
    		
    		$event_info['PTCHR_NM'] = $send_event[0]['PTCHR_NM'];
    		$event_info['STCHR_NM'] = $send_event[0]['STCHR_NM'];
    		
    	} else 
    	{
    		$event_info['SEND_EVENT_SNO'] = "";
    		$event_info['ADD_SRVC_EXR_DAY'] = 0;
    		$event_info['ADD_SRVC_CLAS_CNT'] = 0;
    		
    		$event_info['PTCHR_NM'] = "";
    		$event_info['STCHR_NM'] = "";
    	}
    	
    	$locker_data['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
		$locker_data['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
		$locker_data['lockr_knd'] = $event_info['LOCKR_KND'];
		$locker_data['mem_sno'] = $mem_info['MEM_SNO'];
    	
    	$get_use_locker_info = $lockrModel->select_lockr_room($locker_data);
    	
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	// POS 사용 가능 여부 확인
    	$posService = new \App\Services\PosIntegrationService();
    	$comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
    	$bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$pos_enabled = false;
    	
    	if ($posService->initialize($bcoff_cd, $comp_cd)) {
    		$pos_enabled = $posService->isAvailable();
    	}
    	
    	$data['view']['get_use_locker_info'] = $get_use_locker_info;
    	$data['view']['mem_info'] = $mem_info;
    	$data['view']['event_info'] = $event_info;
    	$data['view']['pos_enabled'] = $pos_enabled;
    	$this->viewPage('/tchr/teventmain/event_buy_info',$data);
    }
    
    /**
     * 미수금 처리를 위한 결제 정보 입력
     * @param string $mem_sno
     * @param string $buy_sno
     */
    public function misu_manage_info($mem_sno,$buy_sno)
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);
        
        $memModel = new MemModel();
        $eventModel = new EventModel();
        
        $memData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $memData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $memData['mem_sno'] = $mem_sno;
        $get_mem_info = $memModel->get_mem_info_mem_sno($memData);
        $mem_info = $get_mem_info[0];
        
        $eventData = $memData;
        $eventData['buy_event_sno'] = $buy_sno;
        $get_event_info = $eventModel->get_buy_event_buy_sno($eventData);
        $event_info = $get_event_info[0];
        
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['mem_info'] = $mem_info;
        $data['view']['event_info'] = $event_info;
        $this->viewPage('/tchr/teventmain/misu_manage_info',$data);
    }
    
    /**
     * DIRECT VAN 결제 [ajax]
     */
    public function ajax_event_buy_van_direct_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $vanModel = new VanModel();
        $nn_now = new Time('now');
        $amasno = new Ama_sno();
        
        // ===========================================================================
        // Process
        // ===========================================================================
        
        $paymt_van_sno = $amasno->create_paymt_mgmt_sno();
        $appno_sno = $amasno->create_appno_sno();
        
        $vdata['paymt_van_sno'] = $paymt_van_sno;
        $vdata['sell_event_sno'] = $postVar['sell_event_sno'];
        $vdata['buy_event_sno'] = "";
        $vdata['send_event_sno'] = "";
        $vdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $vdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $vdata['mem_id'] = $postVar['mem_id'];
        $vdata['appno_sno'] = $appno_sno;
        $vdata['appno'] = $postVar['card_appno'];
        $vdata['paymt_amt'] = put_num($postVar['card_amt']);
        $vdata['paymt_stat'] = "00";
        $vdata['paymt_date'] = date('Y-m-d');
        $vdata['refund_date'] = "";
        
        $vdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $vdata['cre_datetm'] = $nn_now;
        $vdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $vdata['mod_datetm'] = $nn_now;
        
        $van_result = $vanModel->insert_van_direct_hist($vdata);
        
        $return_json['result'] = 'true';
        $return_json['appno_sno'] = $appno_sno;
        $return_json['appno'] = $postVar['card_appno'];
        
        return json_encode($return_json);
        
    }
    
    /**
     * POS 결제 처리 [ajax]
     */
    public function ajax_event_buy_pos_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $vanModel = new VanModel();
        $nn_now = new Time('now');
        $amasno = new Ama_sno();
        
        // ===========================================================================
        // Process
        // ===========================================================================
        
        try {
            // POS 서비스 초기화
            $posService = new \App\Services\PosIntegrationService();
            
            $comp_cd = $this->SpoQCahce->getCacheVar('comp_cd');
            $bcoff_cd = $this->SpoQCahce->getCacheVar('bcoff_cd');
            
            if (!$posService->initialize($bcoff_cd, $comp_cd)) {
                return json_encode([
                    'result' => 'error',
                    'message' => 'POS가 설정되지 않았거나 비활성화되어 있습니다.'
                ]);
            }
            
            // POS 결제 데이터 준비
            $paymentData = [
                'amount' => put_num($postVar['card_amt']),
                'payment_method' => 'CARD',
                'order_id' => 'ORDER_' . date('YmdHis') . '_' . $postVar['mem_id'],
                'bcoff_cd' => $bcoff_cd,
                'sell_event_sno' => $postVar['sell_event_sno'],
                'mem_id' => $postVar['mem_id']
            ];
            
            // POS 결제 요청
            $result = $posService->requestPayment($paymentData);
            
            if ($result['status'] === 'success') {
                // VAN 이력 저장 (POS 결제도 동일하게 저장)
                $paymt_van_sno = $amasno->create_paymt_mgmt_sno();
                $appno_sno = $amasno->create_appno_sno();
                
                $vdata['paymt_van_sno'] = $paymt_van_sno;
                $vdata['sell_event_sno'] = $postVar['sell_event_sno'];
                $vdata['buy_event_sno'] = "";
                $vdata['send_event_sno'] = "";
                $vdata['comp_cd'] = $comp_cd;
                $vdata['bcoff_cd'] = $bcoff_cd;
                $vdata['mem_id'] = $postVar['mem_id'];
                $vdata['appno_sno'] = $appno_sno;
                $vdata['appno'] = $result['approval_no'] ?? '';
                $vdata['paymt_amt'] = put_num($postVar['card_amt']);
                $vdata['paymt_stat'] = "00";
                $vdata['paymt_date'] = date('Y-m-d');
                $vdata['refund_date'] = "";
                $vdata['paymt_method'] = "POS";
                $vdata['pos_transaction_id'] = $result['transaction_id'] ?? '';
                
                $vdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
                $vdata['cre_datetm'] = $nn_now;
                $vdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
                $vdata['mod_datetm'] = $nn_now;
                
                $van_result = $vanModel->insert_van_direct_hist($vdata);
                
                return json_encode([
                    'result' => 'success',
                    'appno_sno' => $appno_sno,
                    'approval_no' => $result['approval_no'] ?? '',
                    'transaction_id' => $result['transaction_id'] ?? '',
                    'card_no' => $result['card_no'] ?? '',
                    'card_name' => $result['card_name'] ?? ''
                ]);
            } else {
                return json_encode([
                    'result' => 'error',
                    'message' => $result['message'] ?? 'POS 결제 실패'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'POS 결제 처리 실패: ' . $e->getMessage());
            return json_encode([
                'result' => 'error',
                'message' => 'POS 결제 처리 중 오류가 발생했습니다.'
            ]);
        }
    }
    
    /**
     * DIRECT VAN 결제 취소 [ajax]
     */
    public function ajax_event_buy_van_direct_cancel_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $vanModel = new VanModel();
        $nn_now = new Time('now');
        $amasno = new Ama_sno();
        
        // ===========================================================================
        // Process
        // ===========================================================================
        
        $paymt_van_sno = $amasno->create_paymt_mgmt_sno();
        $appno_sno = $amasno->create_appno_sno();
        
        $vdata['paymt_van_sno'] = $paymt_van_sno;
        $vdata['sell_event_sno'] = isset($postVar['sell_event_sno']) ? $postVar['sell_event_sno'] : "";
        $vdata['buy_event_sno'] = $postVar['buy_event_sno']; // 환불
        $vdata['send_event_sno'] = "";
        $vdata['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $vdata['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $vdata['mem_id'] = $postVar['mem_id'];
        $vdata['appno_sno'] = $appno_sno;
        //$vdata['appno'] = $postVar['card_appno']; //TODO @@@ 환불시 받지 않음 (추후에는 어떻게 해야할지 결정이 필요함)
        $vdata['appno'] = ""; 
        $vdata['paymt_amt'] = $postVar['paymt_amt']; // 환불 금액
        $vdata['paymt_stat'] = "01"; // 환불코드
        //$vdata['paymt_date'] = date('Y-m-d');
        $vdata['paymt_date'] = "";
        $vdata['refund_date'] = date('Y-m-d');
        
        $vdata['cre_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $vdata['cre_datetm'] = $nn_now;
        $vdata['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $vdata['mod_datetm'] = $nn_now;
        
        $van_result = $vanModel->insert_van_direct_hist($vdata);
        
        $return_json['result'] = 'true';
        $return_json['appno_sno'] = $appno_sno;
        $return_json['appno'] = "";
        
        return json_encode($return_json);
        
    }
    
    /**
     * 환불 완료 처리
     */
    public function refund_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $initVar['pay_comp_cd']     = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['pay_bcoff_cd']    = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['pay_mem_sno']     = $postVar['mem_sno'];
        $initVar['pay_mem_id']      = $postVar['mem_id'];
        $initVar['pay_mem_nm']      = $postVar['mem_nm'];
        $initVar['pay_buy_sno']    = $postVar['buy_event_sno'];
        
        $initVar['pay_appno_sno']   = $postVar['van_appno_sno'];
        $initVar['pay_appno']       = $postVar['van_appno'];
        $initVar['pay_card_amt']    = put_num($postVar['pay_card_amt']);
        $initVar['pay_acct_amt']    = put_num($postVar['pay_acct_amt']);
        $initVar['pay_acct_no']     = $postVar['pay_acct_no'];
        $initVar['pay_cash_amt']    = put_num($postVar['pay_cash_amt']);
        $initVar['pay_misu_amt']    = put_num($postVar['pay_misu_amt']);
        
        // 위약금, 기타금액 추가.
        $initVar['pay_deposit_appno_sno']   = $postVar['van_deposit_appno_sno'];
        $initVar['pay_deposit_appno']       = $postVar['van_deposit_appno'];
        
        $initVar['pay_deposit_card_amt']    = put_num($postVar['pay_deposit_card_amt']);
        $initVar['pay_deposit_cash_amt']    = put_num($postVar['pay_deposit_cash_amt']);
        
        //////////////////////////////////////////
        
        $initVar['pay_etc_appno_sno']   = $postVar['van_etc_appno_sno'];
        $initVar['pay_etc_appno']       = $postVar['van_etc_appno'];
        
        $initVar['pay_etc_card_amt']    = put_num($postVar['pay_etc_card_amt']);
        $initVar['pay_etc_cash_amt']    = put_num($postVar['pay_etc_cash_amt']);
        
        //////////////////////////////////////////

        $initVar['pay_refund_paymt_mgmt_sno'] = $postVar['refund_paymt_mgmt_sno'];
        
        $refundlib = new Refund_lib($initVar);
        $refundlib->refund_run($postVar['refund_issue']);
        $result = $refundlib->refund_result();
        
        // 환불 관리 번호 가져오기
        $refund_mgmt_sno = $refundlib->get_refund_mgmt_sno();
        
        // 환불 방법별 상세 처리
        if(isset($postVar['refund_method']) && !empty($refund_mgmt_sno)) {
            $amaSno = new Ama_sno();
            $payModel = new PayModel();
            
            foreach($postVar['refund_method'] as $paymt_mgmt_sno => $refund_method) {
                if(empty($refund_method)) continue;
                
                // 결제 정보 조회
                $paymt_info = $payModel->get_paymt_mgmt_info($paymt_mgmt_sno);
                if(empty($paymt_info)) continue;
                
                $detail_data = [
                    'refund_detail_sno' => $amaSno->sno_create("RD"),
                    'refund_mgmt_sno' => $refund_mgmt_sno,
                    'buy_event_sno' => $postVar['buy_event_sno'],
                    'paymt_mgmt_sno' => $paymt_mgmt_sno,
                    'refund_mthd' => $refund_method,
                    'refund_amt' => $paymt_info[0]['PAYMT_AMT'],
                    'refund_date' => date('Y-m-d'),
                    'refund_type' => '01', // 이용료
                    'paymt_van_sno' => null,
                    'refund_bank_cd' => null,
                    'refund_acct_no' => null,
                    'refund_acct_nm' => null,
                    'refund_stat' => '00',
                    'comp_cd' => $initVar['pay_comp_cd'],
                    'bcoff_cd' => $initVar['pay_bcoff_cd'],
                    'cre_id' => $this->adminSess['AID'],
                    'cre_datetm' => date('Y-m-d H:i:s'),
                    'mod_id' => $this->adminSess['AID'],
                    'mod_datetm' => date('Y-m-d H:i:s')
                ];
                
                // 카드취소인 경우 VAN 일련번호 저장
                if($refund_method == '01' && isset($postVar['refund_paymt_van_sno'][$paymt_mgmt_sno])) {
                    $detail_data['paymt_van_sno'] = $postVar['refund_paymt_van_sno'][$paymt_mgmt_sno];
                }
                
                $payModel->insert_refund_detail_tbl($detail_data);
            }
        }
        
        // 환불 완료 처리
        scriptAlert("환불이 완료 되었습니다.");
        $url = "/ttotalmain/info_mem/".$postVar['mem_sno'];
        scriptLocation($url);
        exit();
        
    }
    
    
    
    /**
     * 결제 완료 처리
     */
    public function event_buy_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $initVar['pay_comp_cd']     = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['pay_bcoff_cd']    = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['pay_mem_sno']     = $postVar['mem_sno'];
        $initVar['pay_mem_id']      = $postVar['mem_id'];
        $initVar['pay_mem_nm']      = $postVar['mem_nm'];
        $initVar['pay_sell_sno']    = $postVar['sell_event_sno'];
        $initVar['pay_send_sno'] 	= $postVar['send_event_sno'];
        $initVar['pay_appno_sno']   = $postVar['van_appno_sno'];
        $initVar['pay_appno']       = $postVar['van_appno'];
        $initVar['pay_card_amt']    = put_num($postVar['pay_card_amt']);
        $initVar['pay_acct_amt']    = put_num($postVar['pay_acct_amt']);
        $initVar['pay_acct_no']     = $postVar['pay_acct_no'];
        $initVar['pay_cash_amt']    = put_num($postVar['pay_cash_amt']);
        $initVar['pay_misu_amt']    = put_num($postVar['pay_misu_amt']);
        $initVar['pay_exr_s_date']  = $postVar['pay_exr_s_date'];
        $initVar['pay_real_sell_amt'] = put_num($postVar['pay_real_sell_amt']);
        
        
        // 라커 추가
        $initVar['pay_lockr_no']        = $postVar['pay_lockr_no'];
        $initVar['pay_lockr_gendr_set'] = $postVar['pay_lockr_gendr_set'];
        
        $paylib = new Pay_lib($initVar);
        
        // POS 결제 정보가 있는 경우 처리
        if (isset($postVar['use_pos']) && $postVar['use_pos'] === 'Y') {
            $posInfo = [
                'transaction_id' => $postVar['pos_transaction_id'] ?? '',
                'approval_no' => $postVar['van_appno'] ?? '',
                'amount' => put_num($postVar['pay_card_amt']),
                'payment_method' => 'CARD',
                'card_no' => '',
                'card_name' => '',
                'manufacturer' => '',
                'model' => ''
            ];
            $paylib->setPosPaymentInfo($posInfo);
        }
        
        $paylib->buy_run($postVar['pay_issue']);
        $reult_value = $paylib->buy_result();
        
        //_vardump($reult_value);
        
        scriptAlert("구매가 완료 되었습니다.");
        $url = "/ttotalmain/info_mem/".$postVar['mem_sno'];
        scriptLocation($url);
        exit();
        //_vardump($reult_value);
        
        // ===========================================================================
        // Process
        // ===========================================================================
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 구매 상품 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 구매 상품 관리
     */
    public function buy_event_manage()
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
        $eventModel = new EventModel();
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //상품명
        if ( !isset($searchVal['sestat']) ) $searchVal['sestat'] = ''; //상품상태
        if ( !isset($searchVal['sestatr']) ) $searchVal['sestatr'] = ''; //상품상태사유
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'exb'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] =  date('Y-m');// 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }

        
        
        $sumCost1  = $eventModel->list_all_buy_event_sum_cost1($searchVal); // REAL_SELL_AMT
        $sumCost2  = $eventModel->list_all_buy_event_sum_cost2($searchVal); // BUY_AMT
        $sumCost3  = $eventModel->list_all_buy_event_sum_cost3($searchVal); // RECVB_AMT
        
        $totalCount  = $eventModel->list_all_buy_event_count($searchVal);
        $buy_event_list  = $eventModel->list_all_buy_event($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['sumCost1'] = $sumCost1;
        $data['view']['sumCost2'] = $sumCost2;
        $data['view']['sumCost3'] = $sumCost3;
        $data['view']['totalCount'] = $totalCount;
        $data['view']['buy_event_list'] = $buy_event_list;
        $this->viewPage('/tchr/teventmain/buy_event_manage',$data);
    }
    
    /**
     * 상품 보내기(수업)
     */
    public function buy_event_manage3()
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
        
    	unset($initVar['get']['m1']);
        unset($initVar['get']['m2']);
        if ($initVar['get'] == null)
        {
            unset($_SESSION['send_mem_sno']);
            $_SESSION['send_mem_sno'] = array();
        }
        
        $initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if ( !isset($initVar['post']['sch_ccnt']) ) $initVar['post']['sch_ccnt'] = "10";
        //if ( !isset($initVar['post']['sch_end_edate']) ) $initVar['post']['sch_end_edate'] = date('Y-m-d');
        
        if ( !isset($initVar['post']['acc_rtrct_dv']) ) $initVar['post']['acc_rtrct_dv'] = "99";
        if ( !isset($initVar['post']['acc_rtrct_mthd']) ) $initVar['post']['acc_rtrct_dv'] = "99";
        
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount  = $model->list_all_buy_event3_count($boardPage->getVal());
        $buy_event_list = $model->list_all_buy_event3($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['sch_ccnt']) ) $searchVal['sch_ccnt'] = "10";
        //if ( !isset($searchVal['sch_end_edate']) ) $searchVal['sch_end_edate'] = date('Y-m-d');
        
        if ( !isset($searchVal['acc_rtrct_dv']) ) $searchVal['acc_rtrct_dv'] = "99";
        if ( !isset($searchVal['acc_rtrct_mthd']) ) $searchVal['acc_rtrct_dv'] = "99";
        
        if ( !isset($searchVal['search_cate1']) ) $searchVal['search_cate1'] = "";
        if ( !isset($searchVal['search_cate2']) ) $searchVal['search_cate2'] = "";
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $modelCate = new CateModel();
        $cate['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cate['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sch_cate2 = $modelCate->sch_cate3($cate);
        
        $data['view']['sch_cate2'] = $sch_cate2;
        $data['view']['totalCount'] = $totalCount;
        
        $SpoQ_def = SpoqDef();
        $data['view']['acc_rtrct_dv'] = $SpoQ_def['ACC_RTRCT_DV'];
        $data['view']['acc_rtrct_mthd'] = $SpoQ_def['ACC_RTRCT_MTHD'];
        $data['view']['buy_event_list'] = $buy_event_list;
        $data['view']['send_mem_sno'] = $_SESSION['send_mem_sno'];
        $this->viewPage('/tchr/teventmain/buy_event_manage3',$data);
    }
    
    /**
     * 상품 보내기(수업) AJAX 검색
     */
    public function ajax_buy_event_manage3_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if ( !isset($initVar['post']['sch_ccnt']) ) $initVar['post']['sch_ccnt'] = "10";
        
        if ( !isset($initVar['post']['acc_rtrct_dv']) ) $initVar['post']['acc_rtrct_dv'] = "99";
        if ( !isset($initVar['post']['acc_rtrct_mthd']) ) $initVar['post']['acc_rtrct_dv'] = "99";
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount  = $model->list_all_buy_event3_count($boardPage->getVal());
        $buy_event_list = $model->list_all_buy_event3($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
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
        
        // 세션에서 선택된 회원 목록 가져오기
        $send_mem_sno = isset($_SESSION['send_mem_sno']) ? $_SESSION['send_mem_sno'] : array();
        
        // 총 건수 표시
        $html .= '총 건수 : <span id="total-count">' . $totalCount . '</span> 건';
        $html .= '<table class="table table-bordered table-hover col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:45px">선택</th>';
        $html .= '<th style="width:75px">회원명</th>';
        $html .= '<th style="width:100px">전화번호</th>';
        $html .= '<th style="width:115px">구매일시</th>';
        $html .= '<th style="width:80px">상태</th>';
        $html .= '<th style="width:80px">판매상태</th>';
        $html .= '<th>판매상품명</th>';
        $html .= '<th style="width:60px">기간</th>';
        $html .= '<th style="width:80px">시작일</th>';
        $html .= '<th style="width:115px">종료일</th>';
        $html .= '<th style="width:70px">수업</th>';
        $html .= '<th style="width:70px">휴회일</th>';
        $html .= '<th style="width:70px">휴회수</th>';
        $html .= '<th style="width:100px">판매금액</th>';
        $html .= '<th style="width:100px">결제금액</th>';
        $html .= '<th style="width:100px">미수금액</th>';
        $html .= '<th style="width:100px">수업강사</th>';
        $html .= '<th style="width:100px">판매강사</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        if (empty($buy_event_list)) {
            $html .= '<tr><td colspan="18" class="text-center">검색 결과가 없습니다.</td></tr>';
        } else {
            foreach($buy_event_list as $r) {
                $backColor = ""; // 이용중
                if ($r['EVENT_STAT'] == "01") $backColor = "#cfecf0"; // 예약됨
                if ($r['EVENT_STAT'] == "99") $backColor = "#f7d5d9"; // 종료됨
                
                $mem_mem_chk = "";
                if (in_array($r['MEM_SNO'], $send_mem_sno)) $mem_mem_chk = "checked";
                
                $html .= '<tr style="background-color: ' . $backColor . ';">';
                $html .= '<td class="text-center">';
                $html .= '<div class="icheck-primary d-inline">';
                $html .= '<input type="checkbox" name="send_chk[]" id="send_chk_' . $r['BUY_EVENT_SNO'] . '" value="' . $r['MEM_SNO'] . '" onclick="p_chk(this);" ' . $mem_mem_chk . '>';
                $html .= '<label for="send_chk_' . $r['BUY_EVENT_SNO'] . '"><small></small></label>';
                $html .= '</div>';
                $html .= '</td>';
                
                $html .= '<td class="text-center">';
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
                $html .= '</a>';
                $html .= '</td>';
                
                $html .= '<td class="text-center">';
                $html .= isset($r['MEM_TELNO']) && !empty($r['MEM_TELNO']) ? $formatPhoneNumber($r['MEM_TELNO']) : '-';
                $html .= '</td>';
                
                $html .= '<td class="text-center">' . substr($r['BUY_DATETM'],0,16) . '</td>';
                $html .= '<td class="text-center">' . $sDef['EVENT_STAT'][$r['EVENT_STAT']] . '</td>';
                $html .= '<td class="text-center">' . $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']] . '</td>';
                
                $html .= '<td>';
                $html .= $r['SELL_EVENT_NM'];
                if($r['LOCKR_SET'] == "Y") {
                    if ($r['LOCKR_NO'] != '') {
                        $html .= disp_locker_word($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
                    } else {
                        $html .= "[미배정] ";
                    }
                }
                $html .= '</td>';
                
                $html .= '<td class="text-center">' . disp_produnit($r['USE_PROD'],$r['USE_UNIT']) . '</td>';
                $html .= '<td class="text-center" style="width:80px"><span id="exr_s_date_' . $r['BUY_EVENT_SNO'] . '">' . $r['EXR_S_DATE'] . '</span></td>';
                $html .= '<td class="text-center" style="width:80px"><span id="exr_e_date_' . $r['BUY_EVENT_SNO'] . '">' . $r['EXR_E_DATE'] . '</span>' . disp_add_cnt($r['ADD_SRVC_EXR_DAY']) . '</td>';
                
                // 수업 영역
                if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") {
                    $sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT'];
                    $html .= '<td class="text-center">' . $sum_clas_cnt . '</td>';
                } else {
                    $html .= '<td class="text-center">-</td>';
                }
                
                // 휴회 영역
                if($r['DOMCY_POSS_EVENT_YN'] == "Y") {
                    $html .= '<td class="text-center">' . $r['LEFT_DOMCY_POSS_DAY'] . '</td>';
                    $html .= '<td class="text-center">' . $r['LEFT_DOMCY_POSS_CNT'] . '</td>';
                } else {
                    $html .= '<td class="text-center">-</td>';
                    $html .= '<td class="text-center">-</td>';
                }
                
                $html .= '<td style="text-align:right">' . number_format($r['REAL_SELL_AMT']) . '</td>';
                $html .= '<td style="text-align:right">' . number_format($r['BUY_AMT']) . '</td>';
                $html .= '<td style="text-align:right">' . number_format($r['RECVB_AMT']) . '</td>';
                
                $html .= '<td class="text-center">' . $r['STCHR_NM'] . '</td>';
                $html .= '<td class="text-center">' . $r['PTCHR_NM'] . '</td>';
                $html .= '</tr>';
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
            'totalCount' => $totalCount
        ];
        
        return json_encode($response);
    }
    
    /**
     * 세션에서 선택된 회원 목록 가져오기 (buy_event_manage3용)
     */
    public function ajax_get_selected_mem_sno3()
    {
        $send_mem_sno = isset($_SESSION['send_mem_sno']) ? $_SESSION['send_mem_sno'] : array();
        
        $response = [
            'result' => 'true',
            'send_mem_sno' => $send_mem_sno
        ];
        
        return json_encode($response);
    }
    
    /**
     * 상품 보내기(종료일)
     */
    public function buy_event_manage1()
    {
    	// ===========================================================================
        // 선언부
        // ===========================================================================
        $data = MenuHelper::getMenuData($this->request);    	
        
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
    	
    	
    	$initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	
    	if ( !isset($initVar['post']['sch_end_sdate']) ) $initVar['post']['sch_end_sdate'] = date('Y-m-d');
    	//if ( !isset($initVar['post']['sch_end_edate']) ) $initVar['post']['sch_end_edate'] = date('Y-m-d');
    	
    	if ( !isset($initVar['post']['acc_rtrct_dv']) ) $initVar['post']['acc_rtrct_dv'] = "00";
    	if ( !isset($initVar['post']['acc_rtrct_mthd']) ) $initVar['post']['acc_rtrct_mthd'] = "00";
    	
    	
    	$boardPage = new Ama_board($initVar);
    	$model = new EventModel();
    	
    	$totalCount  = $model->list_all_buy_event1_count($boardPage->getVal());
    	$buy_event_list = $model->list_all_buy_event1($boardPage->getVal());
    	
    	$searchVal = $boardPage->getVal();
    	$searchVal['listCount'] = $totalCount - $searchVal['sCount'];
    	
    	if ( !isset($searchVal['sch_end_sdate']) ) $searchVal['sch_end_sdate'] = date('Y-m-d');
    	//if ( !isset($searchVal['sch_end_edate']) ) $searchVal['sch_end_edate'] = date('Y-m-d');
    	
    	if ( !isset($searchVal['acc_rtrct_dv']) ) $searchVal['acc_rtrct_dv'] = "00";
    	if ( !isset($searchVal['acc_rtrct_mthd']) ) $searchVal['acc_rtrct_mthd'] = "00";
    	
    	if ( !isset($searchVal['search_cate1']) ) $searchVal['search_cate1'] = "";
    	if ( !isset($searchVal['search_cate2']) ) $searchVal['search_cate2'] = "";
    	
    	$data['view']['search_val'] = $searchVal;
    	$data['view']['pager'] = $boardPage->getPager($totalCount);
    	
    	// ===========================================================================
    	// 화면 처리
    	// ===========================================================================
    	
    	$modelCate = new CateModel();
    	$cate['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
    	$cate['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
    	$sch_cate1 = $modelCate->sch_cate1($cate);
    	
    	// 대분류가 선택된 경우에만 해당하는 중분류 로딩 (cascading dropdown)
    	$sch_cate2 = array();
    	if (!empty($searchVal['search_cate1'])) {
    	    $cate['1rd_cate_cd'] = $searchVal['search_cate1'];
    	    $sch_cate2 = $modelCate->sch_cate2_by_1rd($cate);
    	}
    	
    	$data['view']['sch_cate1'] = $sch_cate1;
    	$data['view']['sch_cate2'] = $sch_cate2;
    	$data['view']['totalCount'] = $totalCount;
    	
    	$SpoQ_def = SpoqDef();
    	$data['view']['acc_rtrct_dv'] = $SpoQ_def['ACC_RTRCT_DV'];
    	$data['view']['acc_rtrct_mthd'] = $SpoQ_def['ACC_RTRCT_MTHD'];
    	$data['view']['buy_event_list'] = $buy_event_list;

        if(isset($_SESSION['send_mem_sno']))
    	    $data['view']['send_mem_sno'] = $_SESSION['send_mem_sno'];
        else
            $data['view']['send_mem_sno'] = array();
    	$this->viewPage('/tchr/teventmain/buy_event_manage1',$data);
    }
    
    /**
     * 상품 보내기(종료일) AJAX 검색
     */
    public function ajax_buy_event_manage1_search()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if ( !isset($initVar['post']['sch_end_sdate']) ) $initVar['post']['sch_end_sdate'] = date('Y-m-d');
        
        if ( !isset($initVar['post']['acc_rtrct_dv']) ) $initVar['post']['acc_rtrct_dv'] = "00";
        if ( !isset($initVar['post']['acc_rtrct_mthd']) ) $initVar['post']['acc_rtrct_mthd'] = "00";
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount  = $model->list_all_buy_event1_count($boardPage->getVal());
        $buy_event_list = $model->list_all_buy_event1($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
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
        
        // 총 건수 표시
        $html .= '총 건수 : <span id="total-count">' . $totalCount . '</span> 건';
        $html .= '<table class="table table-bordered table-hover col-md-12 mt20">';
        $html .= '<thead>';
        $html .= '<tr class="text-center">';
        $html .= '<th style="width:45px">선택</th>';
        $html .= '<th style="width:75px">회원명</th>';
        $html .= '<th style="width:100px">전화번호</th>';
        $html .= '<th style="width:115px">구매일시</th>';
        $html .= '<th style="width:80px">상태</th>';
        $html .= '<th style="width:80px">구매상태</th>';
        $html .= '<th>이용회원권명</th>';
        $html .= '<th style="width:60px">기간</th>';
        $html .= '<th style="width:80px">시작일</th>';
        $html .= '<th style="width:115px">종료일</th>';
        $html .= '<th style="width:70px">수업</th>';
        $html .= '<th style="width:70px">휴회일</th>';
        $html .= '<th style="width:70px">휴회수</th>';
        $html .= '<th style="width:100px">구매금액</th>';
        $html .= '<th style="width:100px">결제금액</th>';
        $html .= '<th style="width:100px">미수금액</th>';
        $html .= '<th style="width:100px">수업강사</th>';
        $html .= '<th style="width:100px">판매강사</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        if (empty($buy_event_list)) {
            $html .= '<tr><td colspan="18" class="text-center">검색 결과가 없습니다.</td></tr>';
        } else {
            foreach($buy_event_list as $r) {
                $backColor = ""; // 이용중
                if ($r['EVENT_STAT'] == "01") $backColor = "#cfecf0"; // 예약됨
                if ($r['EVENT_STAT'] == "99") $backColor = "#f7d5d9"; // 종료됨
                
                $html .= '<tr style="background-color: ' . $backColor . ';">';
                $html .= '<td class="text-center">';
                $html .= '<div class="icheck-primary d-inline">';
                $html .= '<input type="checkbox" name="send_chk[]" id="send_chk_' . $r['BUY_EVENT_SNO'] . '" value="' . $r['MEM_SNO'] . '" onclick="p_chk(this);">';
                $html .= '<label for="send_chk_' . $r['BUY_EVENT_SNO'] . '"><small></small></label>';
                $html .= '</div>';
                $html .= '</td>';
                
                $html .= '<td class="text-center">';
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
                $html .= '</a>';
                $html .= '</td>';
                
                $html .= '<td class="text-center">';
                $html .= isset($r['MEM_TELNO']) && !empty($r['MEM_TELNO']) ? $formatPhoneNumber($r['MEM_TELNO']) : '-';
                $html .= '</td>';
                
                $html .= '<td class="text-center">' . substr($r['BUY_DATETM'],0,16) . '</td>';
                $html .= '<td class="text-center">' . $sDef['EVENT_STAT'][$r['EVENT_STAT']] . '</td>';
                $html .= '<td class="text-center">' . $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']] . '</td>';
                
                $html .= '<td>';
                $html .= $r['SELL_EVENT_NM'];
                if($r['LOCKR_SET'] == "Y") {
                    if ($r['LOCKR_NO'] != '') {
                        $html .= disp_locker_word($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
                    } else {
                        $html .= "[미배정] ";
                    }
                }
                $html .= '</td>';
                
                $html .= '<td class="text-center">' . disp_produnit($r['USE_PROD'],$r['USE_UNIT']) . '</td>';
                $html .= '<td class="text-center"><span id="exr_s_date_' . $r['BUY_EVENT_SNO'] . '">' . $r['EXR_S_DATE'] . '</span></td>';
                $html .= '<td class="text-center"><span id="exr_e_date_' . $r['BUY_EVENT_SNO'] . '">' . $r['EXR_E_DATE'] . '</span>' . disp_add_cnt($r['ADD_SRVC_EXR_DAY']) . '</td>';
                
                // 수업 영역
                if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") {
                    $sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT'];
                    $html .= '<td class="text-center">' . $sum_clas_cnt . '</td>';
                } else {
                    $html .= '<td class="text-center">-</td>';
                }
                
                // 휴회 영역
                if($r['DOMCY_POSS_EVENT_YN'] == "Y") {
                    $html .= '<td class="text-center">' . $r['LEFT_DOMCY_POSS_DAY'] . '</td>';
                    $html .= '<td class="text-center">' . $r['LEFT_DOMCY_POSS_CNT'] . '</td>';
                } else {
                    $html .= '<td class="text-center">-</td>';
                    $html .= '<td class="text-center">-</td>';
                }
                
                $html .= '<td style="text-align:right">' . number_format($r['REAL_SELL_AMT']) . '</td>';
                $html .= '<td style="text-align:right">' . number_format($r['BUY_AMT']) . '</td>';
                $html .= '<td style="text-align:right">' . number_format($r['RECVB_AMT']) . '</td>';
                
                $html .= '<td class="text-center">' . $r['STCHR_NM'] . '</td>';
                $html .= '<td class="text-center">' . $r['PTCHR_NM'] . '</td>';
                $html .= '</tr>';
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
            'totalCount' => $totalCount
        ];
        
        return json_encode($response);
    }
    
    /**
     * 세션에서 선택된 회원 목록 가져오기
     */
    public function ajax_get_selected_mem_sno()
    {
        $send_mem_sno = isset($_SESSION['send_mem_sno']) ? $_SESSION['send_mem_sno'] : array();
        
        $response = [
            'result' => 'true',
            'send_mem_sno' => $send_mem_sno
        ];
        
        return json_encode($response);
    }
    
    /**
     * 상품 보내기(구매안한상품)
     */
    public function buy_event_manage2()
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
        
    	unset($initVar['get']['m1']);
        unset($initVar['get']['m2']);
        if ($initVar['get'] == null)
        {
            unset($_SESSION['send_mem_sno']);
            $_SESSION['send_mem_sno'] = array();
        }
        
        $initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if ( !isset($initVar['post']['sch_end_sdate']) ) $initVar['post']['sch_end_sdate'] = date('Y-m-d');
        //if ( !isset($initVar['post']['sch_end_edate']) ) $initVar['post']['sch_end_edate'] = date('Y-m-d');
        
        if ( !isset($initVar['post']['acc_rtrct_dv']) ) $initVar['post']['acc_rtrct_dv'] = "00";
        if ( !isset($initVar['post']['acc_rtrct_mthd']) ) $initVar['post']['acc_rtrct_mthd'] = "00";
        
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount  = $model->list_all_buy_event2_count($boardPage->getVal());
        $buy_event_list = $model->list_all_buy_event2($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['sch_end_sdate']) ) $searchVal['sch_end_sdate'] = date('Y-m-d');
        //if ( !isset($searchVal['sch_end_edate']) ) $searchVal['sch_end_edate'] = date('Y-m-d');
        
        if ( !isset($searchVal['acc_rtrct_dv']) ) $searchVal['acc_rtrct_dv'] = "00";
        if ( !isset($searchVal['acc_rtrct_mthd']) ) $searchVal['acc_rtrct_mthd'] = "00";
        
        if ( !isset($searchVal['search_cate1']) ) $searchVal['search_cate1'] = "";
        if ( !isset($searchVal['search_cate2']) ) $searchVal['search_cate2'] = "";
        
        if ( !isset($searchVal['search_cate11']) ) $searchVal['search_cate11'] = "";
        if ( !isset($searchVal['search_cate22']) ) $searchVal['search_cate22'] = "";
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $modelCate = new CateModel();
        $cate['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cate['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sch_cate1 = $modelCate->sch_cate1($cate);
        
        // 대분류가 선택된 경우에만 해당하는 중분류 로딩 (cascading dropdown)
        $sch_cate2 = array();
        // 검색 분류의 대분류가 선택된 경우
        if (!empty($searchVal['search_cate1'])) {
            $cate['1rd_cate_cd'] = $searchVal['search_cate1'];
            $sch_cate2_search = $modelCate->sch_cate2_by_1rd($cate);
            $sch_cate2 = array_merge($sch_cate2, $sch_cate2_search);
        }
        // 미구매 분류의 대분류가 선택된 경우
        if (!empty($searchVal['search_cate11']) && $searchVal['search_cate11'] != $searchVal['search_cate1']) {
            $cate['1rd_cate_cd'] = $searchVal['search_cate11'];
            $sch_cate2_nobuy = $modelCate->sch_cate2_by_1rd($cate);
            $sch_cate2 = array_merge($sch_cate2, $sch_cate2_nobuy);
        }
        
        $data['view']['sch_cate1'] = $sch_cate1;
        $data['view']['sch_cate2'] = $sch_cate2;
        $data['view']['totalCount'] = $totalCount;
        
        $SpoQ_def = SpoqDef();
        $data['view']['acc_rtrct_dv'] = $SpoQ_def['ACC_RTRCT_DV'];
        $data['view']['acc_rtrct_mthd'] = $SpoQ_def['ACC_RTRCT_MTHD'];
        $data['view']['mem_list'] = $buy_event_list;
        $data['view']['send_mem_sno'] = $_SESSION['send_mem_sno'];
        $this->viewPage('/tchr/teventmain/buy_event_manage2',$data);
    }
    
    /**
     * 상품 보내기(구매안한상품)
     */
    public function buy_event_manage4()
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
        
    	unset($initVar['get']['m1']);
        unset($initVar['get']['m2']);
        if ($initVar['get'] == null)
        {
            unset($_SESSION['send_mem_sno']);
            $_SESSION['send_mem_sno'] = array();
        }
        
        $initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        
        
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount  = $model->list_all_buy_event4_count($boardPage->getVal());
        $buy_event_list = $model->list_all_buy_event4($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        if ( !isset($searchVal['sch_end_sdate']) ) $searchVal['sch_end_sdate'] = date('Y-m-d');
        //if ( !isset($searchVal['sch_end_edate']) ) $searchVal['sch_end_edate'] = date('Y-m-d');
        
        if ( !isset($searchVal['acc_rtrct_dv']) ) $searchVal['acc_rtrct_dv'] = "00";
        if ( !isset($searchVal['acc_rtrct_mthd']) ) $searchVal['acc_rtrct_mthd'] = "00";
        
        if ( !isset($searchVal['search_cate1']) ) $searchVal['search_cate1'] = "";
        if ( !isset($searchVal['search_cate2']) ) $searchVal['search_cate2'] = "";
        
        if ( !isset($searchVal['search_cate11']) ) $searchVal['search_cate11'] = "";
        if ( !isset($searchVal['search_cate22']) ) $searchVal['search_cate22'] = "";
        
        if ( !isset($searchVal['end_days']) ) $searchVal['end_days'] = "";
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        
        $modelCate = new CateModel();
        $cate['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $cate['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $sch_cate1 = $modelCate->sch_cate1($cate);
        
        // 대분류가 선택된 경우에만 해당하는 중분류 로딩 (cascading dropdown)
        $sch_cate2 = array();
        // 검색 분류의 대분류가 선택된 경우
        if (!empty($searchVal['search_cate1'])) {
            $cate['1rd_cate_cd'] = $searchVal['search_cate1'];
            $sch_cate2_search = $modelCate->sch_cate2_by_1rd($cate);
            $sch_cate2 = array_merge($sch_cate2, $sch_cate2_search);
        }
        // 미구매 분류의 대분류가 선택된 경우
        if (!empty($searchVal['search_cate11']) && $searchVal['search_cate11'] != $searchVal['search_cate1']) {
            $cate['1rd_cate_cd'] = $searchVal['search_cate11'];
            $sch_cate2_nobuy = $modelCate->sch_cate2_by_1rd($cate);
            $sch_cate2 = array_merge($sch_cate2, $sch_cate2_nobuy);
        }
        
        $data['view']['sch_cate1'] = $sch_cate1;
        $data['view']['sch_cate2'] = $sch_cate2;
        $data['view']['totalCount'] = $totalCount;
        
        $SpoQ_def = SpoqDef();
        $data['view']['acc_rtrct_dv'] = $SpoQ_def['ACC_RTRCT_DV'];
        $data['view']['acc_rtrct_mthd'] = $SpoQ_def['ACC_RTRCT_MTHD'];
        $data['view']['buy_event_list'] = $buy_event_list;
        $data['view']['send_mem_sno'] = $_SESSION['send_mem_sno'];
        $this->viewPage('/tchr/teventmain/buy_event_manage4',$data);
    }
    

    /**
     * send mem_sno chk ajax
     */
    public function ajax_send_mem_sno_chk()
    {
        $postVar = $this->request->getPost();
        
        
        if (!isset($_SESSION['send_mem_sno']))
        {
            $_SESSION['send_mem_sno'] = array();
        }
        
        $chk_sno_array = $_SESSION['send_mem_sno'];
        
        if ($postVar['chk_tf'] == "true")
        {
        	array_push($chk_sno_array,$postVar['send_mem_sno']);
        } else 
        {
        	$chk_sno_array = array_diff($chk_sno_array,array($postVar['send_mem_sno']));
        }
        
        // 중복 제거
        $chk_sno_dup = array();
        $chk_sno_dup = array_unique($chk_sno_array);
        $new_chk_sno_array = array();
        
        $new_i = 0;
        foreach($chk_sno_dup as $r => $key) :
            $new_chk_sno_array[$new_i] = $key;
            $new_i++;
        endforeach;
        
        $_SESSION['send_mem_sno'] = $new_chk_sno_array;
        
        $return_json['postVar'] = $postVar;
        $return_json['send_mem_sno'] = $_SESSION['send_mem_sno'];
        $return_json['msg'] = 'success';
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * AJAX search for buy_event_manage2
     */
    public function ajax_buy_event_manage2_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = array(); // AJAX search doesn't use GET params
        
        $initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if (!isset($initVar['post']['sch_end_sdate'])) $initVar['post']['sch_end_sdate'] = date('Y-m-d');
        if (!isset($initVar['post']['acc_rtrct_dv'])) $initVar['post']['acc_rtrct_dv'] = "00";
        if (!isset($initVar['post']['acc_rtrct_mthd'])) $initVar['post']['acc_rtrct_mthd'] = "00";
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        $a = 1;
        
        $searchData = $boardPage->getVal();
        $totalCount = $model->list_all_buy_event2_count($searchData);
        $mem_list = $model->list_all_buy_event2($searchData);
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // 검색 카테고리 기본값 설정
        if (!isset($searchVal['search_cate1'])) $searchVal['search_cate1'] = "";
        if (!isset($searchVal['search_cate2'])) $searchVal['search_cate2'] = "";
        if (!isset($searchVal['search_cate11'])) $searchVal['search_cate11'] = "";
        if (!isset($searchVal['search_cate22'])) $searchVal['search_cate22'] = "";
        
        // Get pagination
        $pager = $boardPage->getPager($totalCount);
        
        // Get session data
        $send_mem_sno = isset($_SESSION['send_mem_sno']) ? $_SESSION['send_mem_sno'] : array();
        
        // Create HTML for table content
        $sDef = SpoqDef();
        ob_start();
        ?>
        총 건수 : <?php echo $totalCount?> 건
        <table class="table table-bordered table-hover col-md-12 mt20">
            <thead>
                <tr class='text-center'>
                    <th style='width:60px'>선택</th>
                    <th style='width:70px'>상태</th>
                    <th style='width:80px'>이름</th>
                    <th style='width:80px'>아이디</th>
                    <th style='width:120px'>전화번호</th>
                    <th style='width:100px'>생년월일</th>
                    <th style='width:50px'>성별</th>
                    <th>주소</th>
                    <th>가입장소</th>
                    <th style='width:150px'>가입일시</th>
                    <th style='width:150px'>등록일시</th>
                    <th style='width:150px'>재등록일시</th>
                    <th style='width:150px'>종료일시</th>
                    <th>옵션</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($mem_list as $r): ?>
                <?php
                $backColor = "";
                if ($r['MEM_STAT'] == "00") $backColor = "#cfecf0"; //가입회원
                if ($r['MEM_STAT'] == "90") $backColor = "#f7d5d9"; //종료회원
                
                $mem_mem_chk = "";
                if (in_array($r['MEM_SNO'], $send_mem_sno)) $mem_mem_chk = "checked";
                ?>
                <tr style="background-color: <?php echo $backColor ?>;">
                    <td class='text-center'>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="send_chk[]" id="send_chk_<?php echo $r['MEM_SNO']?>" value="<?php echo $r['MEM_SNO']?>" onclick="p_chk(this);" <?php echo $mem_mem_chk?>>
                            <label for="send_chk_<?php echo $r['MEM_SNO']?>">
                                <small></small>
                            </label>
                        </div>
                    </td>
                    <td><?php echo $sDef['MEM_STAT'][$r['MEM_STAT']]?></td>
                    <td><?php echo $r['MEM_NM']?></td>
                    <td><?php echo $r['MEM_ID']?></td>
                    <td><?php echo disp_phone($r['MEM_TELNO'])?></td>
                    <td><?php echo disp_birth($r['BTHDAY'])?></td>
                    <td><?php echo $sDef['MEM_GENDR'][$r['MEM_GENDR']]?></td>
                    <td><?php echo $r['MEM_ADDR']?></td>
                    <td><?php echo $sDef['JON_PLACE'][$r['JON_PLACE']]?></td>
                    <td><?php echo substr($r['JON_DATETM'],0,16)?></td>
                    <td><?php echo substr($r['REG_DATETM'],0,16)?></td>
                    <td><?php echo substr($r['RE_REG_DATETM'],0,16)?></td>
                    <td><?php echo substr($r['END_DATETM'],0,16)?></td>
                    <td style="text-align:center">
                        <button type="button" class="btn btn-warning btn-xs" onclick="mem_manage_mem_info('<?php echo $r['MEM_SNO']?>');">정보보기</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="card-footer clearfix">
            <?=$pager?>
        </div>
        <?php
        $html = ob_get_clean();
        
        $return_json['result'] = 'true';
        $return_json['html'] = $html;
        $return_json['total_count'] = $totalCount;
        $return_json['checked_items'] = $send_mem_sno;
        $return_json['selected_count'] = count($send_mem_sno);
        
        return json_encode($return_json);
    }
    
    /**
     * AJAX search for buy_event_manage4
     */
    public function ajax_buy_event_manage4_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = array(); // AJAX search doesn't use GET params
        
        $initVar['post']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['post']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        if (!isset($initVar['post']['end_days'])) $initVar['post']['end_days'] = "7";
        if (!isset($initVar['post']['acc_rtrct_dv'])) $initVar['post']['acc_rtrct_dv'] = "00";
        if (!isset($initVar['post']['acc_rtrct_mthd'])) $initVar['post']['acc_rtrct_mthd'] = "00";
        
        $boardPage = new Ama_board($initVar);
        $model = new EventModel();
        
        $totalCount = $model->list_all_buy_event4_count($boardPage->getVal());
        $buy_event_list = $model->list_all_buy_event4($boardPage->getVal());
        
        $searchVal = $boardPage->getVal();
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // Get pagination
        $pager = $boardPage->getPager($totalCount);
        
        // Get session data
        $send_mem_sno = isset($_SESSION['send_mem_sno']) ? $_SESSION['send_mem_sno'] : array();
        
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
        총 건수 : <?php echo $totalCount?> 건

        <table class="table table-bordered table-hover  col-md-12 mt20">
            <thead>
                <tr class='text-center'>
                    <th style='width:45px'>선택</th>
                    <th style='width:75px'>회원명</th>
                    <th style='width:100px'>전화번호</th>
                    <th style='width:115px'>구매일시</th>
                    <th style='width:80px'>종료일</th>
                    <th style='width:80px'>판매상태</th>
                    <th>판매상품명</th>
                    <th style='width:60px'>기간</th>
                    <th style='width:80px'>시작일</th>
                    <th style='width:115px'>종료일</th>
                    <th style='width:70px'>수업</th>
                    <th style='width:70px'>휴회일</th>
                    <th style='width:70px'>휴회수</th>
                    <th style='width:100px'>판매금액</th>
                    <th style='width:100px'>결제금액</th>
                    <th style='width:100px'>미수금액</th>
                    <th style='width:100px'>수업강사</th>
                    <th style='width:100px'>판매강사</th>
                </tr>
            </thead> 
            <tbody>
                <?php 
                foreach($buy_event_list as $r) :
                    $mem_mem_chk = "";
                    if (in_array($r['MEM_SNO'], $send_mem_sno)) $mem_mem_chk = "checked";
                ?>
                <tr style="background-color: #f7d5d9;">
                    <td class='text-center'>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="send_chk[]" id="send_chk_<?php echo $r['BUY_EVENT_SNO']?>" value="<?php echo $r['MEM_SNO']?>" onclick="p_chk(this);" <?php echo $mem_mem_chk?>>
                            <label for="send_chk_<?php echo $r['BUY_EVENT_SNO']?>">
                                <small></small>
                            </label>
                        </div>
                    </td>
                    <td class='text-center'>
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
                        </a>
                    </td>
                    <td class='text-center'>
                        <?php echo isset($r['MEM_TELNO']) && !empty($r['MEM_TELNO']) ? formatPhoneNumber($r['MEM_TELNO']) : '-' ?>
                    </td>
                    <td class='text-center'><?php echo substr($r['BUY_DATETM'],0,16)?></td>
                    <td class='text-center'><?php echo $r['END_DAYS']?> 일전</td>
                    <td class="text-center"><?php echo $sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]?></td>
                    <td>
                        <?php echo $r['SELL_EVENT_NM']?>
                        <?php if($r['LOCKR_SET'] == "Y") : 
                                if ($r['LOCKR_NO'] != '') :
                                    echo disp_locker_word($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
                                else :
                                    echo "[미배정] ";
                                endif ;
                                endif;
                        ?>
                    </td>
                    <td class='text-center'><?php echo disp_produnit($r['USE_PROD'],$r['USE_UNIT'])?></td>
                    <td class='text-center' style="width:80px"><span id="<?php echo "exr_s_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_S_DATE']?></span></td>
                    <td class='text-center' style="width:80px"><span id="<?php echo "exr_e_date_".$r['BUY_EVENT_SNO']?>"><?php echo $r['EXR_E_DATE']?></span><?php echo disp_add_cnt($r['ADD_SRVC_EXR_DAY'])?></td>
                    
                    <!-- ############### 수업 영역 ################# -->
                    <?php if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") :?>
                    <?php
                        $sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT']; // 총 수업 남은 횟수
                    ?>
                    <td class='text-center'><?php echo $sum_clas_cnt?></td>
                    
                    <?php else :?>
                    <td class='text-center'>-</td>
                    <?php endif ;?>
                    <!-- ############### 수업 영역 ################# -->
                    
                    <!-- ############### 휴회 영역 ################# -->
                    <?php if($r['DOMCY_POSS_EVENT_YN'] == "Y") :?>
                    <td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_DAY'] ?></td>
                    <td class='text-center'><?php echo $r['LEFT_DOMCY_POSS_CNT'] ?></td>
                    <?php else :?>
                    <td class='text-center'>-</td>
                    <td class='text-center'>-</td>
                    <?php endif ;?>
                    <!-- ############### 휴회 영역 ################# -->
                    
                    <td style="text-align:right"><?php echo number_format($r['REAL_SELL_AMT']) ?></td>
                    <td style="text-align:right"><?php echo number_format($r['BUY_AMT']) ?></td>
                    <td style="text-align:right"><?php echo number_format($r['RECVB_AMT']) ?></td>
                    
                    <td class='text-center'><?php echo $r['STCHR_NM'] ?></td>
                    <td class='text-center'><?php echo $r['PTCHR_NM'] ?></td>
                </tr>
                <?php 
                endforeach;
                ?>
            </tbody>
        </table>
        <div class="card-footer clearfix">
            <?=$pager?>
        </div>
        <?php
        $html = ob_get_clean();
        
        $return_json['result'] = 'true';
        $return_json['html'] = $html;
        $return_json['total_count'] = $totalCount;
        $return_json['checked_items'] = $send_mem_sno;
        $return_json['selected_count'] = count($send_mem_sno);
        
        return json_encode($return_json);
    }
    
    /**
     * selected mem_sno chk ajax (for buy_event_manage1)
     */
    public function ajax_selected_mem_sno_chk()
    {
        $postVar = $this->request->getPost();
        
        if (!isset($_SESSION['send_mem_sno']))
        {
            $_SESSION['send_mem_sno'] = array();
        }
        
        $chk_sno_array = $_SESSION['send_mem_sno'];
        
        if ($postVar['chk_tf'] == "true")
        {
        	array_push($chk_sno_array,$postVar['send_mem_sno']);
        } else 
        {
        	$chk_sno_array = array_diff($chk_sno_array,array($postVar['send_mem_sno']));
        }
        
        // 중복 제거
        $chk_sno_dup = array();
        $chk_sno_dup = array_unique($chk_sno_array);
        $new_chk_sno_array = array();
        
        $new_i = 0;
        foreach($chk_sno_dup as $r => $key) :
            $new_chk_sno_array[$new_i] = $key;
            $new_i++;
        endforeach;
        
        $_SESSION['send_mem_sno'] = $new_chk_sno_array;
        
        $return_json['postVar'] = $postVar;
        $return_json['send_mem_sno'] = $_SESSION['send_mem_sno'];
        $return_json['msg'] = 'success';
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * send mem_sno chk ajax
     */
    public function ajax_send_buy_sno_chk()
    {
        $postVar = $this->request->getPost();
        
        
        if (!isset($_SESSION['send_buy_sno']))
        {
            $_SESSION['send_buy_sno'] = array();
        }
        
        $chk_sno_array = $_SESSION['send_buy_sno'];
        array_push($chk_sno_array,$postVar['send_buy_sno']);
        
        // 중복 제거
        $chk_sno_dup = array();
        $chk_sno_dup = array_unique($chk_sno_array);
        $new_chk_sno_array = array();
        
        $new_i = 0;
        foreach($chk_sno_dup as $r => $key) :
        $new_chk_sno_array[$new_i] = $key;
        $new_i++;
        endforeach;
        
        $_SESSION['send_buy_sno'] = $new_chk_sno_array;
        
        $return_json['send_buy_sno'] = $_SESSION['send_buy_sno'];
        $return_json['msg'] = 'success';
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * 사용하지 않음.
     */
    public function ajax_send_buy_sno()
    {
        //_vardump($_SESSION['send_buy_sno']);
        
        /**
         * $initVar['comp_cd'];
         * $initVar['bcoff_cd'];
         * $initVar['send_sell_event_sno'];
         * $initVar['set_send_amt'];
         * $initVar['set_send_serv_clas_cnt'];
         * $initVar['set_send_serv_day'];
         * $initVar['set_send_domcy_cnt'];
         * $initVar['set_send_domcy_day'];
         * $initVar['set_ptchr_id_nm'];
         * $initVar['set_stchr_id_nm'];
         * $initVar['set_end_day'];
         * $initVar['set_send_mem_snos'];
         */
        
        $modelEvent = new EventModel();
        
        for($i=0;$i<count($_SESSION['send_buy_sno']);$i++)
        {
            $schVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $schVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $schVar['buy_event_sno'] = $_SESSION['send_buy_sno'][$i];
            $get_buy_info = $modelEvent->get_buy_event_buy_sno($schVar);
            
            $initVar['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
            $initVar['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
            $initVar['send_sell_event_sno'] = $get_buy_info[0]['SELL_EVENT_SNO'];
            $initVar['set_send_amt'] = $get_buy_info[0]['SELL_AMT'];
            $initVar['set_send_serv_clas_cnt'] = "0";
            $initVar['set_send_serv_day'] = "0";
            $initVar['set_send_domcy_cnt'] = "0";
            $initVar['set_send_domcy_day'] = "0";
            $initVar['set_stchr_id_nm'] = "";
            $initVar['set_ptchr_id_nm'] = "";
            
            if ($get_buy_info[0]['STCHR_ID'] != '')
            {
                $initVar['set_stchr_id_nm'] = $get_buy_info[0]['STCHR_ID'] . "|" . $get_buy_info[0]['STCHR_NM'];
            }
            
            if ($get_buy_info[0]['PTCHR_ID'] != '')
            {
                $initVar['set_ptchr_id_nm'] = $get_buy_info[0]['PTCHR_ID'] . "|" . $get_buy_info[0]['PTCHR_NM'];
            }
            
            $initVar['set_end_day'] = "7";
            $initVar['set_send_mem_snos'] = $get_buy_info[0]['MEM_SNO'];
            $sendLib = new Send_lib($initVar);
            $sendLib->send_event();
        }
        
        echo "상품 보내기 성공";
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 구매 내역 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 구매 내역 관리
     */
    public function buy_eventhist_manage()
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
        $eventModel = new EventModel();
        
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        if ( !isset($searchVal['spstat']) ) $searchVal['spstat'] = ''; //결제상태
        if ( !isset($searchVal['spchnl']) ) $searchVal['spchnl'] = ''; //결제채널
        if ( !isset($searchVal['spmthd']) ) $searchVal['spmthd'] = ''; //결제수단
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'ps'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m'); // 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }

        
        $totalCount  = $eventModel->list_all_paymt_mgmt_count($searchVal);
        $paymt_list = $eventModel->list_all_paymt_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];

        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['paymt_list'] = $paymt_list;
        $this->viewPage('/tchr/teventmain/buy_eventhist_manage',$data);
        
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 미수금 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 미수금 관리
     */
    public function misu_manage()
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
        $eventModel = new EventModel();
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        if ( !isset($searchVal['srstat']) ) $searchVal['srstat'] = ''; //미수금처리상태
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'ms'; // 날짜검색 조건 // sc : 등록일시
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m'); // 날짜검색 조건 // sc : 등록일시

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month'; // 날짜검색 조건 // sc : 등록일시
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01'); // 검색 시작일
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t'); // 검색 종료일
        }
        
        
        $sumCost = $eventModel->list_all_recvb_mgmt_sum_cost($searchVal);
        $totalCount  = $eventModel->list_all_recvb_mgmt_count($searchVal);
        $misu_list = $eventModel->list_all_recvb_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['sumCost'] = $sumCost;
        $data['view']['totalCount'] = $totalCount;
        $data['view']['misu_list'] = $misu_list;
        $this->viewPage('/tchr/teventmain/misu_manage',$data);
    }
    
    /**
     * 미수금 결재 처리
     */
    public function misu_manage_proc()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        
        $initVar['pay_comp_cd']     = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['pay_bcoff_cd']    = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $initVar['pay_mem_sno']     = $postVar['mem_sno'];
        $initVar['pay_mem_id']      = $postVar['mem_id'];
        $initVar['pay_mem_nm']      = $postVar['mem_nm'];
        $initVar['pay_buy_sno']    = $postVar['buy_event_sno'];
        
        $initVar['pay_appno_sno']   = $postVar['van_appno_sno'];
        $initVar['pay_appno']       = $postVar['van_appno'];
        $initVar['pay_card_amt']    = put_num($postVar['pay_card_amt']);
        $initVar['pay_acct_amt']    = put_num($postVar['pay_acct_amt']);
        $initVar['pay_acct_no']     = $postVar['pay_acct_no'];
        $initVar['pay_cash_amt']    = put_num($postVar['pay_cash_amt']);
        $initVar['pay_misu_amt']    = put_num($postVar['pay_misu_amt']);
        
        $paylib = new Pay_lib($initVar);
        
        $paylib->misu_run();
        
        $result = $paylib->buy_result();
        //_vardump($result);
        
        scriptAlert("미수금 처리가 완료 되었습니다.");
        $url = "/ttotalmain/info_mem/".$postVar['mem_sno'];
        scriptLocation($url);
        exit();
        
        
//         exit();
        
//         $paylib->buy_run();
//         $reult_value = $paylib->buy_result();
        
        
    }
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 상품 보내기 관리 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 상품 보내기 관리
     */
    public function send_event_manage()
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
        $modelSend = new SendModel();
        
        
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
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = ''; //회원명
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = ''; //판매상품명
        if ( !isset($searchVal['ssstat']) ) $searchVal['ssstat'] = ''; // 보내기 상태
        
        $totalCount  = $modelSend->list_send_event_mgmt_count($searchVal);
        $send_event_list = $modelSend->list_send_event_mgmt($searchVal);
        
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $data['view']['search_val'] = $searchVal;
        $data['view']['pager'] = $boardPage->getPager($totalCount);
        
        // ===========================================================================
        // 화면 처리
        // ===========================================================================
        $data['view']['totalCount'] = $totalCount;
        $data['view']['send_event_list'] = $send_event_list;
        $this->viewPage('/tchr/teventmain/send_event_manage',$data);
    }
    
    /**
     * 상품 보내기 한것을 취소한다.
     * 취소코드 : 99
     */
    public function ajax_send_event_cancel()
    {
        // ===========================================================================
        // 선언부
        // ===========================================================================
        $postVar = $this->request->getPost();
        $nn_now = new Time('now');
        
        $modelSend = new SendModel();
        $evData['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $evData['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        $evData['send_stat'] = "99"; // 취소
        $evData['mod_id'] = $this->SpoQCahce->getCacheVar('user_id');
        $evData['mod_datetm'] = $nn_now;
        $evData['send_event_mgmt_sno'] = $postVar['send_sno'];
        
        $modelSend->update_send_event_mgmt($evData);
        
        $return_json['result'] = 'true';
        return json_encode($return_json);
    }
    
    /**
     * send_event_manage AJAX 검색
     */
    public function ajax_send_event_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $modelSend = new SendModel();
        
        $searchVal = $boardPage->getVal();

        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'sd';
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m');

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month';
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01');
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t');
        }
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = '';
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = '';
        if ( !isset($searchVal['ssstat']) ) $searchVal['ssstat'] = '';
        
        $totalCount = $modelSend->list_send_event_mgmt_count($searchVal);
        $send_event_list = $modelSend->list_send_event_mgmt($searchVal);
        
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        $sDef = SpoqDef();
        
        $html = '';
        foreach($send_event_list as $r) {
            $backColor = "";
            if ($r['SEND_STAT'] == "01") $backColor = "#cfecf0";
            if ($r['SEND_STAT'] == "99") $backColor = "#f7d5d9";
            
            $formattedPhone = '';
            if (isset($r['MEM_TELNO']) && !empty($r['MEM_TELNO'])) {
                $phone = preg_replace("/[^0-9]/", "", $r['MEM_TELNO']);
                $length = strlen($phone);
                
                if ($length == 11) {
                    $formattedPhone = preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
                } elseif ($length == 10) {
                    $formattedPhone = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
                } elseif ($length == 9) {
                    $formattedPhone = preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
                } else {
                    $formattedPhone = $phone;
                }
            } else {
                $formattedPhone = '-';
            }

            $mem_thumb = isset($r['MEM_THUMB_IMG']) ? htmlspecialchars($r['MEM_THUMB_IMG']) : '';
            $mem_main = isset($r['MEM_MAIN_IMG']) ? htmlspecialchars($r['MEM_MAIN_IMG']) : '';
            $mem_gendr = isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M';
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . $searchVal['listCount'] . '</td>';
            
            // 회원명 및 사진
            $html .= '<td class="text-center">';
            if (isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo" ';
                $html .= 'id="preview_mem_photo_' . $r['MEM_SNO'] . '" ';
                $html .= 'src="' . $mem_thumb . '" ';
                $html .= 'alt="회원사진" ';
                $html .= 'style="cursor: pointer;" ';
                $html .= 'onclick="showFullPhoto(\'' . $mem_main . '\')" ';
                $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . $mem_gendr . '.png\';">';
            }
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= htmlspecialchars($r['MEM_NM']);
            $html .= '</a>';
            $html .= '</td>';
            
            // 전화번호
            $html .= '<td class="text-center">' . $formattedPhone . '</td>';
            
            // 보낸일시
            $html .= '<td class="text-center">' . substr($r['CRE_DATETM'],0,16) . '</td>';
            
            // 상태
            $html .= '<td class="text-center">' . (isset($sDef['SEND_STAT'][$r['SEND_STAT']]) ? $sDef['SEND_STAT'][$r['SEND_STAT']] : '') . '</td>';
            
            // 판매상품명
            $html .= '<td>' . htmlspecialchars($r['SELL_EVENT_NM']) . '</td>';
            
            // 판매금액
            $html .= '<td style="text-align:right">';
            if(isset($r['SELL_AMT']) && isset($r['ORI_SELL_AMT'])) {
                if($r['SELL_AMT'] != (is_null($r['ORI_SELL_AMT']) ? 0 : $r['ORI_SELL_AMT'])) {
                    $html .= '<span style="text-decoration: line-through;">(' . number_format($r['ORI_SELL_AMT']) . ')</span> ';
                }
                $html .= number_format($r['SELL_AMT']);
            } else {
                $html .= '0';
            }
            $html .= '</td>';
            
            // 기간
            $html .= '<td class="text-center">';
            if(isset($r['USE_PROD']) && isset($r['USE_UNIT'])) {
                $html .= disp_produnit($r['USE_PROD'],$r['USE_UNIT']);
                if(isset($r['ADD_SRVC_EXR_DAY']) && $r['ADD_SRVC_EXR_DAY'] != 0) {
                    $html .= '(+' . $r['ADD_SRVC_EXR_DAY'] . ')';
                }
            }
            $html .= '</td>';
            
            // 수업횟수
            $html .= '<td class="text-center">';
            if(isset($r['CLAS_CNT'])) {
                $html .= $r['CLAS_CNT'];
                if(isset($r['ADD_SRVC_CLAS_CNT']) && $r['ADD_SRVC_CLAS_CNT'] != 0) {
                    $html .= '(+' . $r['ADD_SRVC_CLAS_CNT'] . ')';
                }
            }
            $html .= '</td>';
            
            // 휴회일
            $html .= '<td class="text-center">' . (isset($r['DOMCY_DAY']) ? $r['DOMCY_DAY'] : '0') . '</td>';
            
            // 휴회수
            $html .= '<td class="text-center">' . (isset($r['DOMCY_CNT']) ? $r['DOMCY_CNT'] : '0') . '</td>';
            
            // 수업강사
            $html .= '<td class="text-center">' . (isset($r['STCHR_NM']) ? htmlspecialchars($r['STCHR_NM']) : '') . '</td>';
            
            // 판매강사
            $html .= '<td class="text-center">' . (isset($r['PTCHR_NM']) ? htmlspecialchars($r['PTCHR_NM']) : '') . '</td>';
            
            // 액션 버튼
            $html .= '<td class="text-center">';
            if($r['SEND_STAT'] == '01') {
                $html .= '<a href="#" onclick="send_cancel(\'' . $r['SEND_EVENT_MGMT_SNO'] . '\');return false;" class="btn btn-danger btn-sm">취소</a>';
            }
            $html .= '</td>';
            
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        $pager = $boardPage->getPager($totalCount);
        
        $response = [
            'result' => 'true',
            'html' => $html,
            'pager' => $pager,
            'totalCount' => number_format($totalCount)
        ];
        
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * buy_event_manage AJAX 검색
     */
    public function ajax_buy_event_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $eventModel = new EventModel();
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = '';
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = '';
        if ( !isset($searchVal['sestat']) ) $searchVal['sestat'] = '';
        if ( !isset($searchVal['sestatr']) ) $searchVal['sestatr'] = '';
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'exb';
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] =  date('Y-m');

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month';
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01');
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t');
        }
        
        $sumCost1  = $eventModel->list_all_buy_event_sum_cost1($searchVal);
        $sumCost2  = $eventModel->list_all_buy_event_sum_cost2($searchVal);
        $sumCost3  = $eventModel->list_all_buy_event_sum_cost3($searchVal);
        
        $totalCount  = $eventModel->list_all_buy_event_count($searchVal);
        $buy_event_list  = $eventModel->list_all_buy_event($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // 테이블 HTML 생성
        $html = '';
        $sDef = SpoqDef();
        
        foreach($buy_event_list as $r) {
            $backColor = "";
            if ($r['EVENT_STAT'] == "01") $backColor = "#cfecf0";
            if ($r['EVENT_STAT'] == "99") $backColor = "#f7d5d9";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . htmlspecialchars($searchVal['listCount']) . '</td>';
            $html .= '<td>';
            // 회원 사진 추가 (buy_event_manage는 사진 정보가 없으므로 기본 이미지만 표시)
            if(isset($r['MEM_THUMB_IMG']) && !empty($r['MEM_THUMB_IMG'])) {
                $html .= '<img src="' . htmlspecialchars($r['MEM_THUMB_IMG']) . '" alt="회원사진" class="preview_mem_photo" onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= htmlspecialchars($r['MEM_NM']);
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td>' . htmlspecialchars(substr($r['BUY_DATETM'],0,16)) . '</td>';
            $html .= '<td>' . htmlspecialchars($sDef['EVENT_STAT'][$r['EVENT_STAT']]) . '</td>';
            $html .= '<td>' . htmlspecialchars($sDef['EVENT_STAT_RSON'][$r['EVENT_STAT_RSON']]) . '</td>';
            
            $html .= '<td>';
            $html .= htmlspecialchars($r['SELL_EVENT_NM']);
            if($r['LOCKR_SET'] == "Y") {
                if ($r['LOCKR_NO'] != '') {
                    $html .= disp_locker_word($r['LOCKR_KND'],$r['LOCKR_GENDR_SET'],$r['LOCKR_NO']);
                } else {
                    $html .= "[미배정] ";
                }
            }
            $html .= '(' . htmlspecialchars($sDef['ACC_RTRCT_MTHD'][$r['ACC_RTRCT_MTHD']]) . ')';
            $html .= '</td>';
            
            $html .= '<td class="text-center">' . disp_produnit($r['USE_PROD'],$r['USE_UNIT']) . '</td>';
            $html .= '<td><span id="exr_s_date_' . $r['BUY_EVENT_SNO'] . '">' . htmlspecialchars($r['EXR_S_DATE']) . '</span></td>';
            $html .= '<td><span id="exr_e_date_' . $r['BUY_EVENT_SNO'] . '">' . htmlspecialchars($r['EXR_E_DATE']) . '</span>' . disp_add_cnt($r['ADD_SRVC_EXR_DAY']) . '</td>';
            
            if($r['CLAS_DV'] == "21" || $r['CLAS_DV'] == "22") {
                $sum_clas_cnt = $r['MEM_REGUL_CLAS_LEFT_CNT'] + $r['SRVC_CLAS_LEFT_CNT'];
                $html .= '<td class="text-center">' . $sum_clas_cnt . '</td>';
            } else {
                $html .= '<td class="text-center">-</td>';
            }
            
            if($r['DOMCY_POSS_EVENT_YN'] == "Y") {
                $html .= '<td class="text-center">' . htmlspecialchars($r['LEFT_DOMCY_POSS_DAY']) . '</td>';
                $html .= '<td class="text-center">' . htmlspecialchars($r['LEFT_DOMCY_POSS_CNT']) . '</td>';
            } else {
                $html .= '<td class="text-center">-</td>';
                $html .= '<td class="text-center">-</td>';
            }
            
            $html .= '<td style="text-align:right">' . number_format($r['REAL_SELL_AMT']) . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['BUY_AMT']) . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['RECVB_AMT']) . '</td>';
            $html .= '<td class="text-center">' . htmlspecialchars($r['STCHR_NM']) . '</td>';
            $html .= '<td class="text-center">' . htmlspecialchars($r['PTCHR_NM']) . '</td>';
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $boardPage->getPager($totalCount),
            'totalCount' => number_format($totalCount),
            'sumCost1' => number_format($sumCost1),
            'sumCost2' => number_format($sumCost2),
            'sumCost3' => number_format($sumCost3)
        );
        
        return json_encode($result);
    }
    
    /**
     * buy_eventhist_manage AJAX 검색
     */
    public function ajax_buy_eventhist_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $eventModel = new EventModel();
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = '';
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = '';
        if ( !isset($searchVal['spstat']) ) $searchVal['spstat'] = '';
        if ( !isset($searchVal['spchnl']) ) $searchVal['spchnl'] = '';
        if ( !isset($searchVal['spmthd']) ) $searchVal['spmthd'] = '';
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'ps';
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m');

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month';
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01');
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t');
        }
        
        $totalCount  = $eventModel->list_all_paymt_mgmt_count($searchVal);
        $paymt_list = $eventModel->list_all_paymt_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // 테이블 HTML 생성
        $html = '';
        $sDef = SpoqDef();
        
        foreach($paymt_list as $r) {
            $backColor = "";
            if ($r['PAYMT_STAT'] == "01") $backColor = "#f7d5d9";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . htmlspecialchars($searchVal['listCount']) . '</td>';
            $html .= '<td>';
            // 회원 사진 추가
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo" ';
                $html .= 'id="preview_mem_photo_' . $r['MEM_SNO'] . '" ';
                $html .= 'src="' . (isset($r['MEM_THUMB_IMG']) ? htmlspecialchars($r['MEM_THUMB_IMG']) : '') . '" ';
                $html .= 'alt="회원사진" ';
                $html .= 'style="cursor: pointer;" ';
                $html .= 'onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? htmlspecialchars($r['MEM_MAIN_IMG']) : '') . '\')" ';
                $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= htmlspecialchars($r['MEM_NM']);
            $html .= '</a>(' . htmlspecialchars($r['MEM_ID']) . ')';
            $html .= '</td>';
            $html .= '<td>' . htmlspecialchars($r['SELL_EVENT_NM']) . '</td>';
            $html .= '<td class="text-center">' . htmlspecialchars($sDef['PAYMT_STAT'][$r['PAYMT_STAT']]) . '</td>';
            $html .= '<td class="text-center">' . htmlspecialchars($sDef['PAYMT_CHNL'][$r['PAYMT_CHNL']]) . '</td>';
            $html .= '<td class="text-center">' . htmlspecialchars($sDef['PAYMT_MTHD'][$r['PAYMT_MTHD']]) . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['PAYMT_AMT']) . '</td>';
            $html .= '<td>' . htmlspecialchars($r['PAYMT_DATE']) . '</td>';
            $html .= '<td>' . htmlspecialchars($r['REFUND_DATE']) . '</td>';
            $html .= '<td>' . htmlspecialchars($r['MOD_DATETM']) . '</td>';
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $boardPage->getPager($totalCount),
            'totalCount' => number_format($totalCount)
        );
        
        return json_encode($result);
    }
    
    /**
     * misu_manage AJAX 검색
     */
    public function ajax_misu_search()
    {
        $initVar['post'] = $this->request->getPost();
        $initVar['get'] = $this->request->getGet();
        
        $initVar['get']['comp_cd'] = $this->SpoQCahce->getCacheVar('comp_cd');
        $initVar['get']['bcoff_cd'] = $this->SpoQCahce->getCacheVar('bcoff_cd');
        
        $boardPage = new Ama_board($initVar);
        $eventModel = new EventModel();
        
        $searchVal = $boardPage->getVal();
        
        if ( !isset($searchVal['snm']) ) $searchVal['snm'] = '';
        if ( !isset($searchVal['senm']) ) $searchVal['senm'] = '';
        if ( !isset($searchVal['srstat']) ) $searchVal['srstat'] = '';
        
        if ( !isset($searchVal['sdcon']) ) $searchVal['sdcon'] = 'ms';
        if ( !isset($searchVal['nthUnit']) ) $searchVal['nthUnit'] = date('Y-m');

        if ( !isset($searchVal['sdUnit']) ) $searchVal['sdUnit'] = 'Month';
        if (!isset($searchVal['sdate']) || $searchVal['sdate'] === '') {
            $searchVal['sdate'] = date('Y-m-01');
        }

        if (!isset($searchVal['edate']) || $searchVal['edate'] === '') {
            $searchVal['edate'] = date('Y-m-t');
        }
        
        $sumCost = $eventModel->list_all_recvb_mgmt_sum_cost($searchVal);
        $totalCount  = $eventModel->list_all_recvb_mgmt_count($searchVal);
        $misu_list = $eventModel->list_all_recvb_mgmt($searchVal);
        $searchVal['listCount'] = $totalCount - $searchVal['sCount'];
        
        // 테이블 HTML 생성
        $html = '';
        $sDef = SpoqDef();
        
        foreach($misu_list as $r) {
            $backColor = "";
            if ($r['RECVB_STAT'] == "01") $backColor = "#cfecf0";
            if ($r['RECVB_STAT'] == "90") $backColor = "#f7d5d9";
            
            $html .= '<tr style="background-color: ' . $backColor . ';">';
            $html .= '<td class="text-center">' . htmlspecialchars($searchVal['listCount']) . '</td>';
            $html .= '<td>';
            // 회원 사진 추가
            if(isset($r['MEM_THUMB_IMG']) || isset($r['MEM_GENDR'])) {
                $html .= '<img class="preview_mem_photo" ';
                $html .= 'id="preview_mem_photo_' . $r['MEM_SNO'] . '" ';
                $html .= 'src="' . (isset($r['MEM_THUMB_IMG']) ? htmlspecialchars($r['MEM_THUMB_IMG']) : '') . '" ';
                $html .= 'alt="회원사진" ';
                $html .= 'style="cursor: pointer;" ';
                $html .= 'onclick="showFullPhoto(\'' . (isset($r['MEM_MAIN_IMG']) ? htmlspecialchars($r['MEM_MAIN_IMG']) : '') . '\')" ';
                $html .= 'onerror="this.onerror=null; this.src=\'/dist/img/default_profile_' . (isset($r['MEM_GENDR']) ? $r['MEM_GENDR'] : 'M') . '.png\';">';
            }
            $html .= '<a href="javascript:void(0);" style="color:black;" onclick="mem_manage_mem_info(\'' . $r['MEM_SNO'] . '\');">';
            $html .= htmlspecialchars($r['MEM_NM']);
            $html .= '</a>(' . htmlspecialchars($r['MEM_ID']) . ')';
            $html .= '</td>';
            $html .= '<td>' . htmlspecialchars($r['SELL_EVENT_NM']) . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['RECVB_AMT']) . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['RECVB_PAYMT_AMT']) . '</td>';
            $html .= '<td style="text-align:right">' . number_format($r['RECVB_LEFT_AMT']) . '</td>';
            $html .= '<td class="text-center">' . htmlspecialchars($sDef['RECVB_STAT'][$r['RECVB_STAT']]) . '</td>';
            $html .= '<td>' . htmlspecialchars($r['MOD_DATETM']) . '</td>';
            $html .= '</tr>';
            
            $searchVal['listCount']--;
        }
        
        $result = array(
            'result' => 'true',
            'html' => $html,
            'pager' => $boardPage->getPager($totalCount),
            'totalCount' => number_format($totalCount),
            'sumCost' => number_format($sumCost)
        );
        
        return json_encode($result);
    }
    
}