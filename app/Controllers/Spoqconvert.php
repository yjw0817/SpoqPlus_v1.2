<?php
namespace App\Controllers;

use App\Libraries\Ama_sno;
use App\Libraries\Cron_lib;
use App\Libraries\Domcy_lib;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\I18n\Time;
use App\Models\CronModel;
use App\Models\ConvertModel;
use App\Models\MemModel;

class SpoQconvert extends BaseController
{
    
    /**
     * 전화번호 암호화 lenth
     * @param string $telno
     */
    protected function enc_tel($telno)
    {
        $encryption = new Encryption();
        $encrypter = $encryption->initialize(
            );
        $ciphertext = base64_encode($encrypter->encrypt($telno));
        return $ciphertext;
    }
    
    /**
     * 전화번호 복호화
     * @param string $enc_telno
     */
    protected function denc_tel($enc_telno)
    {
        $encryption = new Encryption();
        $encrypter = $encryption->initialize(
            );
        $de_ciphertext = $encrypter->decrypt(base64_decode($enc_telno));
        return $de_ciphertext;
    }
    
    protected function enc_phone($var="")
    {
        if ($var == "" || $var == null || strlen($var) < 10)
        {
            $return_val['mask'] = "";
            $return_val['short'] = "";
            $return_val['enc'] = "";
            $return_val['enc2'] = "";
        } else
        {
            $ch_var = disp_phone($var); // 전화번호 형식을 다시 - 형식으로 바꾼다.
            $ex_var = explode("-",$ch_var); // - 형식으로 문자를 나누어 배열로 저장한다.
            
            $mask_len = strlen($ex_var[1]); // 가운데 전화번호를 마스크 하기 위하여 마스크할 길이를 구한다.
            
            $add_mask = "";
            for($i=0; $i<$mask_len; $i++)
            {
                $add_mask .= "#";
            }
            
            $mask_phone = $ex_var[0] . "-" . $add_mask . "-" . $ex_var[2];
            
            $return_val['mask'] = $mask_phone;
            $return_val['short'] = $ex_var[2];
            $return_val['enc'] = $this->enc_tel($var);
            $return_val['enc2'] = $this->enc_pass($var);
            
        }
        
        return $return_val;
    }
    
    /**
     * 비밀번호 암호화 (lenth 64 return)
     * @param string $npass
     */
    protected function enc_pass($npass)
    {
        $enc_word = hash('sha256',$npass);
        return $enc_word;
    }
	
	public function test_cron($params = '')
	{
		echo "Test Cron {$params}" . PHP_EOL;
	}
	
	/**
	 * 회원 비밀번호 암호화 처리
	 * 회원 전화번호 암호화 처리
	 */
	public function update_meminfo()
	{
	    exit();
	    $cronModel = new CronModel();
	    $tData['sss'] = 'sss';
	    $memList = $cronModel->cron_list_mem_main_info($tData);
	    
	    foreach ($memList as $r)
	    {
	        if (strlen($r['MEM_PWD']) < 20)
	        {
	            
	            $uData['mem_sno'] = $r['MEM_SNO'];
	            $uData['mem_pwd'] = $this->enc_pass($r['MEM_PWD']);
	            $cronModel->cron_pwd_enc_update($uData);
	        }
	    }
	    
	    foreach($memList as $r)
	    {
	        if ($r['MEM_TELNO_ENC'] == null)
	        {
	            $denc_phone = $this->enc_phone($r['MEM_TELNO']);
	            $uData2['mem_sno'] = $r['MEM_SNO'];
	            $uData2['mem_telno_enc'] = $denc_phone['enc'];
	            $uData2['mem_telno_enc2'] = $denc_phone['enc2'];
	            $uData2['mem_telno_mask'] = $denc_phone['mask'];
	            $uData2['mem_telno_short'] = $denc_phone['short'];
	            
	            $cronModel->cron_telno_enc_update($uData2);
	        }
	    }
	    
	    $smgmtList = $cronModel->cron_list_smgmt($tData);
	    
	    foreach ($smgmtList as $r)
	    {
	        if (strlen($r['SMGMT_PWD']) < 20)
	        {
	            
	            $uData['smgmt_id'] = $r['SMGMT_ID'];
	            $uData['smgmt_pwd'] = $this->enc_pass($r['SMGMT_PWD']);
	            $cronModel->cron_pwd_enc_smgmt_update($uData);
	        }
	    }
	    
	    
	    echo "succes converting";
	}
	
	/**
	 * =============================================================================
	 *  CONVERT
	 * =============================================================================
	 */
	
	
	/**
	 * 구매상품에서 강사이름 업데이트
	 * @param number $page
	 */
	public function teacher_c2($page=1)
	{
	    echo "컨버팅 불가능";
	    exit();
	    
	    $model = new ConvertModel();
	    // 회원 1명씩 컨버팅을 수행한다.
	    $mdata['comp_cd'] = "C0003";
	    $mdata['bcoff_cd'] = "C0003F0019";
	    $mdata['page'] = $page;
	    $get_member = $model->get_teacher_list($mdata);
	    
	    $mm_count = 0;
	    foreach ($get_member as $m)
	    {
	        $mm_count++;
	        //echo $mm_count . "::: ---" . $m['MEM_NM'] . "--- <br />";
	        $this->update_convert_teacher_nm($m);
	    }
	    
	    $nn = $page + 1;
	    
	    echo "[성공] 강사 이름 CONVERT 성공1" . date('Ymdhis');
	    echo "<br />";
	    // echo "<a href='/SpoQconvert/product_m2/".$nn."'>다음실행 ".$nn."</a>";
	    
	}
	
	
	private function update_convert_teacher_nm($mem_info)
	{
	    $model = new ConvertModel();
	    
	    $udata['comp_cd'] = "C0003";
	    $udata['bcoff_cd'] = "C0003F0019";
	    // buy_event_mgmt_tbl 수업강사 이름 update
	    $udata['stchr_id'] = $mem_info['MEM_ID'];
	    $udata['stchr_nm'] = $mem_info['MEM_NM'];
	    
	    $model->update_buy_event_stchr_nm($udata);
	    
	    // buy_event_mgm_tbl 판매강사 이름 update
	    $udata['ptchr_id'] = $mem_info['MEM_ID'];
	    $udata['ptchr_nm'] = $mem_info['MEM_NM'];
	    
	    $model->update_buy_event_ptchr_nm($udata);
	}
	
	
	
	/**
	 * 회원 테이블 convert
	 */
	public function teacher_c1()
	{
	    echo "컨버팅 불가능";
	    exit();
	    
	    $sData['set_comp_cd'] = "C0003";
	    $sData['set_bcoff_cd'] = "C0003F0019";
	    
	    if ($sData['set_comp_cd'] == "")
	    {
	        echo "프로그램에서 회사코드를 셋팅하세요";
	        return;
	    }
	    
	    if ($sData['set_bcoff_cd'] == "")
	    {
	        echo "프로그램에서 지점코드를 셋팅하세요";
	        return;
	    }
	    
	    $convModel = new ConvertModel();
	    
	    $cdata['temp'] = "temp";
	    $conv_list = $convModel->teacher_list($cdata);
	    
	    $amasno = new Ama_sno();
	    
	    
	    foreach ($conv_list as $r)
	    {
	        $mem_sno = $amasno->create_mem_sno();
	        $re_phone = put_num($r['user_hp']);
	        $denc_phone = $this->enc_phone($re_phone);
	        
	        $sData['mem_sno'] = $mem_sno['mem_sno'];
	        $sData['mem_id'] = $r['user_id'];
	        $sData['mem_pwd'] = $this->enc_pass('1111');
	        $sData['mem_nm'] = $r['user_name'];
	        $sData['qr_cd'] = ""; // empty
	        $sData['bthday'] = put_num($r['user_birth']);
	        $sData['mem_gendr'] = $r['user_sex'];
	        
	        $sData['mem_telno'] = $re_phone;
	        $sData['mem_telno_enc'] = $denc_phone['enc'];
	        $sData['mem_telno_mask'] = $denc_phone['mask'];
	        $sData['mem_telno_short'] = $denc_phone['short'];
	        $sData['mem_telno_enc2'] = $denc_phone['enc2'];
	        
	        $sData['mem_addr'] = $r['user_email']; // empty
	        $sData['mem_main_img'] = ""; // empty
	        $sData['mem_thumb_img'] = ""; // empty
	        $sData['mem_dv'] = "T"; // default T (강사)
	        // 	        $sData['set_comp_cd'] = "";
	        // 	        $sData['set_bcoff_cd'] = "";
	        $sData['cre_id'] = "jamesgymjs";
	        $sData['cre_datetm'] = $r['user_work_date'] . " 01:01:01";
	        $sData['mod_id'] = "jamesgymjs";
	        $sData['mod_datetm'] = $r['user_work_date'] . " 01:01:01";
	        $sData['convert_key'] = $r['user_id'];
	        
	        
	        $convModel->insert_mem_main_info($sData);
	        
	        $dData = $sData;
	        $dData['comp_cd'] = $sData['set_comp_cd'];
	        $dData['bcoff_cd'] = $sData['set_bcoff_cd'];
	        $dData['mem_stat'] = "00"; // 현재회원으로 일단 셋팅함
	        
	        if ($r['user_work_out'] == '1') $dData['mem_stat'] = "90"; // 종료회원으로 일단 셋팅함
	        
	        $dData['tchr_posn'] = "20";
	        
	        $dData['ctrct_type'] = "10"; // 정규직
	        if ($r['user_work'] == '자유소득인') $dData['ctrct_type'] = "20";
	        
	        $dData['tchr_simp_pwd'] = "";
	        
	        $dData['jon_place'] = "01"; // 센터방문
	        $dData['jon_datetm'] = $r['user_work_date'] . " 01:01:01";
	        $dData['reg_place'] = "01";
	        $dData['reg_datetm'] = $r['user_work_date'] . " 01:01:01";
	        $dData['end_datetm'] = $r['user_work_out_date'] . " 01:01:01";
	        
	        $convModel->insert_mem_info_detl_tbl($dData);
	    }
	    
	    echo "[성공] 강사 메인 테이블 CONVERT 성공2" . date('Ymdhis');
	    
	}
	
	
	
	
	
	/**
	 * 컨버팅 전에 회원 아이디 중복을 검증한다.
	 */
	public function member_chk1()
	{
	    $model = new ConvertModel();
	    
	    $cdata['temp'] = "temp";
	    $count_chk1 = $model->member_chk1($cdata);
	    
	    if (count($count_chk1) == 0)
	    {
	        echo "[OK] 중복된 아이디가 없습니다. ";
	    } else 
	    {
	        echo "[ERROR] 중복된 아이디가 있습니다. 정리 후 다음단계를 실행하세요 <br />";
	        foreach ($count_chk1 as $r)
	        {
	            echo $r['user_id'] . " :: " . "중복갯수 : " . $r['counter'] . "<br />";
	        }
	    }
	}
	
	/**
	 * 회원 테이블 convert
	 */
	public function member_c1()
	{
	    echo "컨버팅 불가능";
	    exit();
	    
	    $sData['set_comp_cd'] = "C0003";
	    $sData['set_bcoff_cd'] = "C0003F0019";
	    
	    if ($sData['set_comp_cd'] == "")
	    {
	        echo "프로그램에서 회사코드를 셋팅하세요";
	        return;
	    }
	    
	    if ($sData['set_bcoff_cd'] == "")
	    {
	        echo "프로그램에서 지점코드를 셋팅하세요";
	        return;
	    }
	    
	    $convModel = new ConvertModel();
	    
	    $cdata['temp'] = "temp";
	    $conv_list = $convModel->member_list($cdata);
	    
	    $amasno = new Ama_sno();
	    
	    
	    foreach ($conv_list as $r)
	    {
	        $mem_sno = $amasno->create_mem_sno();
	        $re_phone = put_num($r['user_hp']);
	        $denc_phone = $this->enc_phone($re_phone);
	        
	        $sData['mem_sno'] = $mem_sno['mem_sno'];
	        $sData['mem_id'] = $r['user_id'];
	        $sData['mem_pwd'] = $this->enc_pass(substr($re_phone,-4));
	        $sData['mem_nm'] = $r['user_name'];
	        $sData['qr_cd'] = ""; // empty
	        $sData['bthday'] = put_num($r['user_birth']);
	        $sData['mem_gendr'] = $r['user_sex'];
	        
	        $sData['mem_telno'] = $re_phone;
	        $sData['mem_telno_enc'] = $denc_phone['enc'];
	        $sData['mem_telno_mask'] = $denc_phone['mask'];
	        $sData['mem_telno_short'] = $denc_phone['short'];
	        $sData['mem_telno_enc2'] = $denc_phone['enc2'];
	        
	        $sData['mem_addr'] = ""; // empty
	        $sData['mem_main_img'] = ""; // empty
	        $sData['mem_thumb_img'] = ""; // empty
	        $sData['mem_dv'] = "M"; // default M (회원)
// 	        $sData['set_comp_cd'] = "";
// 	        $sData['set_bcoff_cd'] = "";
	        $sData['cre_id'] = "jamesgymjs";
	        $sData['cre_datetm'] = $r['user_first_date'];
	        $sData['mod_id'] = "jamesgymjs";
	        $sData['mod_datetm'] = $r['user_first_date'];
	        $sData['convert_key'] = $r['uid'];
	        
	        $convModel->insert_mem_main_info($sData);
	        
	        $dData = $sData;
	        $dData['comp_cd'] = $sData['set_comp_cd'];
	        $dData['bcoff_cd'] = $sData['set_bcoff_cd'];
	        $dData['mem_stat'] = "00"; // 현재회원으로 일단 셋팅함
	        
	        $dData['tchr_posn'] = "";
	        $dData['ctrct_type'] = "";
	        $dData['tchr_simp_pwd'] = "";
	        
	        $dData['jon_place'] = "01"; // 센터방문
	        $dData['jon_datetm'] = $r['user_first_date'];
	        $dData['reg_place'] = "";
	        $dData['reg_datetm'] = "";
	        
	        $convModel->insert_mem_info_detl_tbl($dData);
	    }
	    
	    echo "[성공] 회원 메인 테이블 CONVERT 성공2" . date('Ymdhis');
	    
	}
	
	/**
	 * 메모 컨버팅
	 */
	public function memo_c1($page=1)
	{
	    echo "컨버팅 불가능";
	    exit();
	    
	    $convModel = new ConvertModel();
	    
	    $cdata['page'] = $page;
	    $memo_list = $convModel->memo_list($cdata);
	    $err_info = array();
	    
	    foreach ($memo_list as $r)
	    {
	        // 회원정보를 가져온다.
	        $mdata['comp_cd'] = "C0003";
	        $mdata['bcoff_cd'] = "C0003F0019";
	        $mdata['mem_id'] = $r['user_id'];
	        $info = $convModel->get_mem_info_user_id($mdata);
	        
	        if (count($info) > 0)
	        {
	            $mdata['mem_sno'] = $info[0]['MEM_SNO'];
	            $mdata['mem_id'] = $info[0]['MEM_ID'];
	            $mdata['mem_nm'] = $info[0]['MEM_NM'];
	            $mdata['prio_set'] = "N"; // fix
	            $mdata['memo_conts_dv'] = "0001"; // fix
	            $mdata['memo_conts'] = $r['me_content'];
	            $mdata['cre_id'] = "jamesgymjs";
	            $mdata['cre_datetm'] = $r['me_regdate'];
	            $mdata['mod_id'] = "jamesgymjs";
	            $mdata['mod_datetm'] = $r['me_regdate'];
	            
	            $convModel->insert_memo_mgmt($mdata);
	        } else 
	        {
	            array_push($err_info,$r); 
	        }
	        
	    }
	    
	    _vardump($err_info);
	    
	    echo "[성공] 회원 메모 테이블 CONVERT 성공2" . date('Ymdhis');
	    
	}
	
	
	/**
	 * ---------------------------------------------------------------------------------
	 */
	
	
	/**
	 * 구매상품 컨버팅
	 * @param number $page
	 */
	public function product_m1($page=1)
	{
	    $model = new ConvertModel();
	    // 회원 1명씩 컨버팅을 수행한다.
	    $mdata['comp_cd'] = "C0003";
	    $mdata['bcoff_cd'] = "C0003F0019";
	    $mdata['page'] = $page;
	    $get_member = $model->get_member($mdata);
	    
	    $mm_count = 0;
	    foreach ($get_member as $m)
	    {
	        $mm_count++;
	        //echo $mm_count . "::: ---" . $m['MEM_NM'] . "--- <br />";
	        $this->get_convert_product_member($m);
	    }
	    
	    $nn = $page + 1;
	    
	    echo "[성공] 회원 구매상품 테이블 CONVERT 성공1" . date('Ymdhis');
	    echo "<br />";
	    echo "<a href='/SpoQconvert/product_m1/".$nn."'>다음실행 ".$nn."</a>";
	    
	}
	
	
	/**
	 * 회원상태 업데이트
	 * @param number $page
	 */
	public function product_m2($page=1)
	{
	    $model = new ConvertModel();
	    // 회원 1명씩 컨버팅을 수행한다.
	    $mdata['comp_cd'] = "C0003";
	    $mdata['bcoff_cd'] = "C0003F0019";
	    $mdata['page'] = $page;
	    $get_member = $model->get_member_status($mdata);
	    
	    $mm_count = 0;
	    foreach ($get_member as $m)
	    {
	        $mm_count++;
	        //echo $mm_count . "::: ---" . $m['MEM_NM'] . "--- <br />";
	        $this->update_convert_member_status($m);
	    }
	    
	    $nn = $page + 1;
	    
	    echo "[성공] 회원 상태 업데이트 CONVERT 성공1" . date('Ymdhis');
	    echo "<br />";
	   // echo "<a href='/SpoQconvert/product_m2/".$nn."'>다음실행 ".$nn."</a>";
	    
	}
	
	/**
	 * 회원 등록일자 업데이트
	 * @param number $page
	 */
	public function product_m3($page=1)
	{
	    $model = new ConvertModel();
	    // 회원 1명씩 컨버팅을 수행한다.
	    $mdata['comp_cd'] = "C0003";
	    $mdata['bcoff_cd'] = "C0003F0019";
	    $mdata['page'] = $page;
	    $get_member = $model->get_member_status($mdata);
	    
	    $mm_count = 0;
	    foreach ($get_member as $m)
	    {
	        $mm_count++;
	        //echo $mm_count . "::: ---" . $m['MEM_NM'] . "--- <br />";
	        $this->update_convert_member_reg_datetm($m);
	    }
	    
	    $nn = $page + 1;
	    
	    echo "[성공] 회원 등록일자 업데이트 CONVERT 성공1" . date('Ymdhis');
	    echo "<br />";
	    // echo "<a href='/SpoQconvert/product_m2/".$nn."'>다음실행 ".$nn."</a>";
	    
	}
	
	/**
	 * 회원 상태를 검사하여 업데이트 한다.
	 * @param array $mem_info
	 */
	private function update_convert_member_reg_datetm($mem_info)
	{
	    $model = new ConvertModel();
	    
	    $mdata['comp_cd']     = "C0003";
	    $mdata['bcoff_cd']    = "C0003F0019";
	    $mdata['mem_sno']     = $mem_info['MEM_SNO'];
	    
	    $get_reg_datetm = $model->get_reg_datetm($mdata);
	    
	    if (count($get_reg_datetm) > 0 )
	    {
	        $udata = $mdata;
	        $udata['reg_datetm'] = $get_reg_datetm[0]['CRE_DATETM'];
	        $model->update_member_reg_datetm($udata);
	    } 
	}
	
	
	/**
	 * 회원 상태를 검사하여 업데이트 한다.
	 * @param array $mem_info
	 */
	private function update_convert_member_status($mem_info)
	{
	    $model = new ConvertModel();
	    
	    $mdata['comp_cd']     = "C0003"; 
	    $mdata['bcoff_cd']    = "C0003F0019"; 
	    $mdata['mem_sno']     = $mem_info['MEM_SNO'];
	    
	    $get_count = $model->chk_status_member($mdata);
	    
	    if ($get_count[0]['counter'] > 0)
	    {
	        $udata = $mdata;
	        $udata['mem_stat'] = "01"; // 현재회원
	        
	        $model->update_member_status($udata);
	    } else 
	    {
	        $udata = $mdata;
	        $udata['mem_stat'] = "90"; // 종료회원
	        // 종료회원일 경우에는 종료일자 업데이트를 해야한다.
	        $get_end_datetm = $model->end_status_member_date($mdata);
	        
	        if (count($get_end_datetm) > 0 )
	        {
	            $udata['end_datetm'] = $get_end_datetm[0]['CRE_DATETM'];
	            $model->update_member_status_end_datetm($udata);
	        }
	    }
	}
	
	
	/**
	 * 컨버팅할 데이터에서 해당 유저의 상품을 처리한다.
	 * @param string $user_id
	 */
	private function get_convert_product_member($mem_info)
	{
	    $model = new ConvertModel();
	    $pData['user_id'] = $mem_info['MEM_ID'];
	    $get_product = $model->get_convert_product_member($pData);
	    
	    foreach ($get_product as $r)
	    {
	        if ($r['pr_uid'] != "0")
	        {
	            $get_convert_sell_sno = $this->chk_sell_sno($r['pr_uid']);
	            
	            $sell_info = $this->get_sell_event_info($get_convert_sell_sno['sno']);
	            
	            if ($get_convert_sell_sno['ex'] == 'n')
	            {
	                $sell_info['SELL_EVENT_NM'] = $this->get_pr_name($r['pr_uid']);
	            } 
	            
	            //echo $sell_info['SELL_EVENT_NM'] . "<br />";
	            
	            $this->proc_buy_event($sell_info, $mem_info, $r, $get_convert_sell_sno['ex']);
	            
	        }
	    }
	    
	}
	
	/**
	 * buy_event 에 insert 를 수행한다.
	 * @param array $sell_info
	 * @param array $mem_info
	 */
	private function proc_buy_event($sell_info,$mem_info,$conv_info, $ex_yn)
	{
	    $model = new ConvertModel();
	    
	    $amasno = new Ama_sno();
	    $buy_sno = $amasno->create_buy_sno();
	    
	    $bdatap['buy_event_sno']   = $buy_sno;
	    $bdatap['sell_event_sno']  = $sell_info['SELL_EVENT_SNO'];
	    $bdatap['send_event_sno']  = ""; // 없음
	    $bdatap['event_img']       = ""; // 없음
	    $bdatap['event_icon']      = ""; // 없음
	    $bdatap['grp_clas_psnnl_cnt'] = ""; // 없음
	    
	    $bdatap['comp_cd']     = "C0003"; //$sell_info['COMP_CD'];
	    $bdatap['bcoff_cd']    = "C0003F0019"; //$sell_info['BCOFF_CD'];
	    $bdatap['1rd_cate_cd'] = $sell_info['1RD_CATE_CD'];
	    $bdatap['2rd_cate_cd'] = $sell_info['2RD_CATE_CD'];
	    $bdatap['mem_sno']     = $mem_info['MEM_SNO'];
	    $bdatap['mem_id']      = $mem_info['MEM_ID'];
	    $bdatap['mem_nm']      = $mem_info['MEM_NM'];
	    $bdatap['stchr_id']    = $conv_info['user_id_class']; // 컨버팅 데이터
	    $bdatap['stchr_nm']    = $conv_info['user_id_class']; // 컨버팅 데이터
	    $bdatap['ptchr_id']    = $conv_info['user_id_send']; // 컨버팅 데이터
	    $bdatap['ptchr_nm']    = $conv_info['user_id_send']; // 컨버팅 데이터
	    $bdatap['sell_event_nm'] = $sell_info['SELL_EVENT_NM'];
	    $bdatap['sell_amt']    = $sell_info['SELL_AMT'];
	    
	    
	    if ($ex_yn == 'y')
	    {
	        
	        $use_prod = $sell_info['USE_PROD'];
	        $use_unit = $sell_info['USE_UNIT'];
	        $clas_cnt = $sell_info['CLAS_CNT'];
	        $domcy_day = $sell_info['DOMCY_DAY'];
	        $domcy_cnt = $sell_info['DOMCY_CNT'];
	    } else 
	    {
	        $use_prod = $conv_info['pr_month'];
	        $use_unit = "M";
	        $clas_cnt = $conv_info['pr_class_cnt'];
	        $domcy_day = $conv_info['pr_break_day'];
	        $domcy_cnt = $conv_info['pr_break_cnt'];
	        $bdatap['sell_amt'] = $conv_info['pr_price'];
	    }
	    
	    $bdatap['use_prod'] = $use_prod;
	    $bdatap['use_unit'] = $use_unit;
	    $bdatap['clas_cnt'] = $clas_cnt;
	    $bdatap['domcy_day'] = $domcy_day;
	    $bdatap['domcy_cnt'] = $domcy_cnt;
	    
	    $bdatap['domcy_poss_event_yn'] = $sell_info['DOMCY_POSS_EVENT_YN'];
	    $bdatap['acc_rtrct_dv'] = $sell_info['ACC_RTRCT_DV'];
	    $bdatap['acc_rtrct_mthd'] = $sell_info['ACC_RTRCT_MTHD'];
	    $bdatap['clas_dv'] = $sell_info['CLAS_DV'];
	    
	    $total_cost = $conv_info['pr_price'];
	    $total_clas_cnt = $conv_info['pr_class_cnt'];
	    
	    $onetime_cost = 0;
	    
	    if ($total_cost != 0 && $total_clas_cnt != 0)
	    {
	        $onetime_cost = floor($total_cost / $total_clas_cnt);
	    }
	    
	    $bdatap['1tm_clas_prgs_amt'] = $onetime_cost; // 처리가 필요함.
	    
	    if ($conv_info['mp_state'] == "이용중" || $conv_info['mp_state'] == "양수함")
	    {
	        if ($conv_info['pr_start'] > date('Y-m-d'))
	        {
	            $event_stat = "01";
	        } else 
	        {
	            $event_stat = "00";
	        }
	    } else 
	    {
	        $event_stat = "99";
	    }
	    
	    $bdatap['event_stat'] = $event_stat; // 처리가 필요함. 00 : 이용중 / 01: 예약됨 / 99: 종료됨
	    $bdatap['event_stat_rson'] = "00"; // 00 : 정상 으로 일단 처리함.
	    $bdatap['exr_s_date'] = $conv_info['pr_start'];
	    $bdatap['exr_e_date'] = $conv_info['pr_end'];
	    $bdatap['left_domay_poss_day'] = $domcy_day;
	    $bdatap['left_domcy_poss_cnt'] = $domcy_cnt;
	    $bdatap['buy_datetm'] = $conv_info['pr_regdate'];
	    $bdatap['real_sell_amt'] = $conv_info['pr_price'];
	    $bdatap['buy_amt'] = $conv_info['pr_price'];
	    $bdatap['recbc_amt'] = "0"; // fix
	    $bdatap['add_srvc_exr_day'] = "0"; // fix
	    $bdatap['add_srvc_clas_cnt'] = "0"; // fix
	    $bdatap['add_domcy_day'] = "0"; // fix
	    $bdatap['add_domcy_cnt'] = "0"; // fix
	    $bdatap['srvc_clas_left_cnt'] = "0"; // fix
	    $bdatap['srvc_clas_prgs_cnt'] = "0"; // fix
	    
	    $bdatap['mem_regul_clas_left_cnt'] = $conv_info['pr_class_cnt'] - $conv_info['pr_use_cnt'];
	    $bdatap['mem_regul_clas_prgs_cnt'] = $conv_info['pr_use_cnt'];
	    $bdatap['ori_exr_s_date'] = $conv_info['pr_start']; 
	    $bdatap['ori_exr_e_date'] = $conv_info['pr_end'];
	    $bdatap['transm_poss_yn'] = "N"; // fix
	    $bdatap['refund_poss_yn'] = "N"; // fix
	    $bdatap['grp_cate_set'] = $sell_info['GRP_CATE_SET'];
	    $bdatap['lockr_set'] = $sell_info['LOCKR_SET'];
	    $bdatap['lockr_knd'] = $sell_info['LOCKR_KND'];
	    $bdatap['lockr_gendr_set'] = $sell_info['LOCKR_GENDR_SET'];
	    $bdatap['lockr_no'] = ""; // fix
	    $bdatap['cre_id'] = "jamesgymjs";
	    $bdatap['cre_datetm'] = $conv_info['pr_regdate'];
	    $bdatap['mod_id'] = "jamesgymjs";
	    $bdatap['mod_datetm'] = $conv_info['pr_regdate'];
	    
	    $model->insert_buy_event_mgmt($bdatap);
	    
	    
	    $pay_sno = $amasno->create_pay_sno();
	    
	    $payData['paymt_mgmt_sno']     = $pay_sno;
	    $payData['buy_event_sno']      = $buy_sno;
	    $payData['sell_event_sno']     = $sell_info['SELL_EVENT_SNO'];
	    $payData['send_event_sno']     = ""; // 없음
	    $payData['recvb_sno']          = ""; // 없음
	    $payData['paymt_van_sno']      = ""; // TODO @@@ 결제_VAN_일련번호
	    $payData['comp_cd']            = "C0003";
	    $payData['bcoff_cd']           = "C0003F0019";
	    $payData['1rd_cate_cd']        = $sell_info['1RD_CATE_CD'];
	    $payData['2rd_cate_cd']        = $sell_info['2RD_CATE_CD'];
	    $payData['mem_sno']            = $mem_info['MEM_SNO'];
	    $payData['mem_id']             = $mem_info['MEM_ID'];
	    $payData['mem_nm']             = $mem_info['MEM_NM'];
	    $payData['sell_event_nm']      = $sell_info['SELL_EVENT_NM'];
	    $payData['paymt_mthd']         = "01"; // fix 카드 결제로 fix 함
	    $payData['acct_no']            = ""; // 없음 // TODO @@@ 계좌_번호
	    $payData['appno_sno']          = ""; // 없음
	    $payData['appno']              = ""; // 없음
	    $payData['paymt_amt']          = $conv_info['pr_price'];
	    $payData['paymt_stat']         = "00"; // fix 결제_상태 ( 00 : 결제 / 01 : 환불 / 99 : 취소 )
	    $payData['paymt_date']         = substr($conv_info['pr_regdate'],0,10);
	    $payData['refund_date']        = "";
	    $payData['paymt_chnl']         = "P"; // fix
	    $payData['paymt_van_knd']      = "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 )
	    $payData['grp_cate_set']       = $sell_info['GRP_CATE_SET'];
	    $payData['lockr_set']          = $sell_info['LOCKR_SET'];
	    $payData['lockr_knd']          = $sell_info['LOCKR_KND'];
	    $payData['lockr_gendr_set']    = $sell_info['LOCKR_GENDR_SET'];
	    $payData['lockr_no']           = ""; // fix
	    $payData['cre_id']             = "jamesgymjs";
	    $payData['cre_datetm']         = $conv_info['pr_regdate'];
	    $payData['mod_id']             = "jamesgymjs";
	    $payData['mod_datetm']         = $conv_info['pr_regdate'];
	    
	    $model->insert_paymt_mgmt_tbl($payData);
	    
	    $sales_sno = $amasno->create_sales_sno();
	    
	    $salesData['sales_mgmt_sno']   = $sales_sno;
	    $salesData['paymt_mgmt_sno']   = $pay_sno;
	    $salesData['buy_event_sno']    = $buy_sno;
	    $salesData['sell_event_sno']   = $sell_info['SELL_EVENT_SNO'];
	    $salesData['send_event_sno']   = ""; // 없음
	    $salesData['recvb_hist_sno']   = ""; // 없음
	    $salesData['paymt_van_sno']    = ""; // 없음
	    $salesData['comp_cd']          = "C0003";
	    $salesData['bcoff_cd']         = "C0003F0019";
	    $salesData['1rd_cate_cd']      = $sell_info['1RD_CATE_CD'];
	    $salesData['2rd_cate_cd']      = $sell_info['2RD_CATE_CD'];
	    $salesData['clas_dv']          = $sell_info['CLAS_DV'];
	    $salesData['mem_sno']          = $mem_info['MEM_SNO'];
	    $salesData['mem_id']           = $mem_info['MEM_ID'];
	    $salesData['mem_nm']           = $mem_info['MEM_NM'];
	    $salesData['pthcr_id']         = $conv_info['user_id_send'];
	    $salesData['sell_event_nm']    = $sell_info['SELL_EVENT_NM'];
	    $salesData['paymt_stat']       = "00"; // fix
	    $salesData['paymt_mthd']       = "01"; // fix
	    $salesData['acc_sno']          = ""; // 없음
	    $salesData['appno']            = ""; // 없음
	    $salesData['paymt_amt']        = $conv_info['pr_price'];
	    $salesData['sales_dv']         = "00"; // fix 정상매출
	    $salesData['sales_dv_rson']    = "00"; // fix 신규매출
	    $salesData['sales_mem_stat']   = "01"; // fix 현재회원
	    $salesData['paymt_chnl']       = "P"; // fix
	    $salesData['paymt_van_knd']    = "02"; // 결제_VAN_종류 ( 모바일밴이름 : 01 / PC벤이름 : 02 / 키오스크밴이름 : 03 )
	    $salesData['sales_aply_yn']    = "Y"; // 매출_적용_여부
	    $salesData['grp_cate_set']     = $sell_info['GRP_CATE_SET'];
	    $salesData['lockr_set']        = $sell_info['LOCKR_SET'];
	    $salesData['lockr_knd']        = $sell_info['LOCKR_KND'];
	    $salesData['lockr_gendr_set']  = $sell_info['LOCKR_GENDR_SET'];
	    $salesData['lockr_no']         = ""; // 없음
	    $salesData['cre_id']           = "jamesgymjs";
	    $salesData['cre_datetm']       = $conv_info['pr_regdate'];
	    $salesData['mod_id']           = "jamesgymjs";
	    $salesData['mod_datetm']       = $conv_info['pr_regdate'];
	    
	    $model->insert_sales_mgmt_tbl($salesData);
	    
	    
	}
	
	
	
	/**
	 * 상품명이 기타일때 원래 있던 상품명으로 상품명을 대체 하기 위하여 구한다.
	 * @param string $pr_uid
	 * @return array
	 */
	private function get_pr_name($pr_uid)
	{
	    $model = new ConvertModel();
	    $sData['uid'] = $pr_uid;
	    $get_pr_name = $model->get_pr_nmae($sData);
	    
	    $return_value = "상품명 없음";
	    if (count($get_pr_name) > 0)
	    {
	        $return_value = $get_pr_name[0]['pr_name'];
	    }
	    
	    return $return_value;
	}
	
	/**
	 * 판매 정보를 가져온다.
	 * @param string $sell_sno
	 * @return array
	 */
	private function get_sell_event_info($sell_sno)
	{
	    $model = new ConvertModel();
	    
	    $sData['comp_cd'] = "C0003";
	    $sData['bcoff_cd'] = "C0003F0019";
	    $sData['sell_event_sno'] = $sell_sno;
	    
	    $sell_info = $model->get_sell_event_info($sData);
	    
	    return $sell_info[0];
	}
	
	/**
	 * 컨버팅 id를 등록된 판매상품 일련번호로 매핑하여 리턴한다.
	 * @param string $var
	 * @return string
	 */
	private function chk_sell_sno($var)
	{
	    $sno['1105'] = 'EC202406090000000135';
	    $sno['1106'] = 'EC202406090000000136';
	    $sno['1107'] = 'EC202406090000000137';
	    $sno['1108'] = 'EC202406090000000138';
	    
	    $sno['1115'] = 'EC202406090000000140';
	    $sno['1116'] = 'EC202406090000000141';
	    $sno['1174'] = 'EC202406090000000142';
	    $sno['1117'] = 'EC202406090000000143';
	    $sno['1175'] = 'EC202406090000000144';
	    $sno['1308'] = 'EC202406090000000147';
	    $sno['1118'] = 'EC202406090000000145';
	    $sno['1176'] = 'EC202406090000000146';
	    
	    $sno['1213'] = 'EC202406090000000149';
	    $sno['1661'] = 'EC202406090000000151';
	    $sno['1662'] = 'EC202406090000000152';
	    $sno['1504'] = 'EC202406090000000154';
	    $sno['1205'] = 'EC202406090000000156';
	    
	    $sno['1209'] = 'EC202406090000000168';
	    $sno['1247'] = 'EC202406090000000170';
	    $sno['1573'] = 'EC202406090000000172';
	    $sno['1214'] = 'EC202406090000000174';
	    $sno['1523'] = 'EC202406090000000176';
	    $sno['1215'] = 'EC202406090000000178';
	    $sno['1651'] = 'EC202406090000000258';
	    $sno['1670'] = 'EC202406090000000180';
	    $sno['1653'] = 'EC202406090000000181';
	    $sno['1262'] = 'EC202406090000000183';
	    $sno['1541'] = 'EC202406090000000183';
	    
	    $sno['1100'] = 'EC202406090000000158';
	    $sno['1253'] = 'EC202406090000000159';
	    $sno['1101'] = 'EC202406090000000160';
	    $sno['1182'] = 'EC202406090000000160';
	    $sno['1102'] = 'EC202406090000000161';
	    $sno['1183'] = 'EC202406090000000161';
	    $sno['1318'] = 'EC202406090000000161';
	    $sno['1103'] = 'EC202406090000000162';
	    $sno['1184'] = 'EC202406090000000162';
	    $sno['1317'] = 'EC202406090000000163';
	    $sno['1389'] = 'EC202406090000000164';
	    $sno['1390'] = 'EC202406090000000164';
	    $sno['1104'] = 'EC202406090000000165';
	    $sno['1392'] = 'EC202406090000000166';
	    
	    $sno['1119'] = 'EC202406090000000185';
	    $sno['1120'] = 'EC202406090000000186';
	    
	    $sno['1516'] = 'EC202406090000000188';
	    $sno['1713'] = 'EC202406090000000190';
	    
	    $sno['653'] = 'EC202406090000000192';
	    
	    $sno['1134'] = 'EC202406090000000194';
	    $sno['1135'] = 'EC202406090000000195';
	    $sno['1575'] = 'EC202406090000000196';
	    $sno['1136'] = 'EC202406090000000197';
	    $sno['1530'] = 'EC202406090000000198';
	    $sno['1657'] = 'EC202406090000000199';
	    
	    $sno['1138'] = 'EC202406090000000201';
	    $sno['1139'] = 'EC202406090000000202';
	    $sno['1674'] = 'EC202406090000000203';
	    $sno['1140'] = 'EC202406090000000204';
	    $sno['1531'] = 'EC202406090000000205';
	    $sno['1658'] = 'EC202406090000000206';
	    
	    $sno['1121'] = 'EC202406090000000208';
	    $sno['1122'] = 'EC202406090000000209';
	    $sno['1692'] = 'EC202406090000000210';
	    $sno['1123'] = 'EC202406090000000211';
	    $sno['1574'] = 'EC202406090000000212';
	    $sno['1124'] = 'EC202406090000000213';
	    $sno['1534'] = 'EC202406090000000214';
	    $sno['1579'] = 'EC202406090000000214';
	    $sno['1598'] = 'EC202406090000000214';
	    $sno['1659'] = 'EC202406090000000215';
	    $sno['1400'] = 'EC202406090000000216';
	    $sno['1401'] = 'EC202406090000000216';
	    $sno['1402'] = 'EC202406090000000216';
	    $sno['1403'] = 'EC202406090000000216';
	    
	    $sno['1132'] = 'EC202406090000000218';
	    
	    $sno['1125'] = 'EC202406090000000220';
	    $sno['1126'] = 'EC202406090000000221';
	    $sno['1693'] = 'EC202406090000000222';
	    $sno['1127'] = 'EC202406090000000223';
	    $sno['1577'] = 'EC202406090000000224';
	    $sno['1128'] = 'EC202406090000000225';
	    $sno['1537'] = 'EC202406090000000226';
	    $sno['1566'] = 'EC202406090000000226';
	    $sno['1660'] = 'EC202406090000000227';
	    
	    $sno['1212'] = 'EC202406090000000229';
	    $sno['1509'] = 'EC202406090000000229';
	    $sno['1096'] = 'EC202406090000000230';
	    $sno['1217'] = 'EC202406090000000230';
	    $sno['1179'] = 'EC202406090000000230';
	    $sno['1665'] = 'EC202406090000000231';
	    $sno['1097'] = 'EC202406090000000232';
	    $sno['1180'] = 'EC202406090000000232';
	    $sno['1098'] = 'EC202406090000000233';
	    $sno['1181'] = 'EC202406090000000233';
	    $sno['1578'] = 'EC202406090000000234';
	    
	    $sno['1041'] = 'EC202406090000000236';
	    $sno['1522'] = 'EC202406090000000238';
	    $sno['1551'] = 'EC202406090000000240';
	    $sno['1572'] = 'EC202406090000000242';
	    
	    $sno['1647'] = 'EC202406090000000244';
	    $sno['1648'] = 'EC202406090000000245';
	    $sno['1649'] = 'EC202406090000000246';
	    $sno['1650'] = 'EC202406090000000247';
	    
	    $sno['1663'] = 'EC202406090000000249';
	    $sno['1664'] = 'EC202406090000000250';
	    
	    $sno['1540'] = 'EC202406090000000252';
	    $sno['1565'] = 'EC202406090000000252';
	    
	    $sno['1207'] = 'EC202406090000000254';
	    $sno['1208'] = 'EC202406090000000255';
	    
	    $sno['1142'] = 'EC202406090000000257';
	    //$sno['1099'] = 'EC202406090000000260';
	    //$sno['1261'] = 'EC202406090000000260';
	    
	    $return_value['ex'] = 'n';
	    $return_value['sno'] = "EC202406090000000260";
	    if (isset($sno[$var]))
	    {
	        $return_value['ex'] = 'y';
	        $return_value['sno'] = $sno[$var];
	    }
	    
	    return $return_value;
	    
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}