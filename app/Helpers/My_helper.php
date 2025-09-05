<?php

function disp_mem_info($mem_sno='')
{
    if ($mem_sno != '')
    {
        echo "<i class='fas fa-user text-info' style='font-size:0.8rem;' onclick='loc_user_info(\"".$mem_sno."\")'></i>";
    }
}

function disp_ynr($stat_word)
{
    switch($stat_word)
    {
        case "승인":
            $badge_style = "bg-success";
            break;
        case "취소":
            $badge_style = "bg-danger";
            break;
		case "반려":
			$badge_style = "bg-danger";
			break;
        case "대기":
            $badge_style = "bg-info";
			break;
		case "신청":
			$badge_style = "bg-info";
            break;
    }
    
    echo "<span class='badge ".$badge_style."'>".$stat_word."</span>";
}

function disp_attd_stat($stat_word)
{
    switch($stat_word)
    {
        case "출근":
            $badge_style = "bg-success";
            break;
        case "지각":
            $badge_style = "bg-danger";
            break;
    }
    
    echo "<span class='badge ".$badge_style."'>".$stat_word."</span>";
}

function disp_badge_acc($acc_rtrct_mthd = '')
{
    $sDef = SpoqDef();
    $badge_style = "bg-warning";
    switch($acc_rtrct_mthd)
    {
        case "99":
            $badge_style = "bg-danger";
            break;
        case "00":
            $badge_style = "bg-success";
            break;
        case "50":
            $badge_style = "bg-info";
            break;
        default:
            $badge_style = "bg-warning";
    }
    
    $word = $sDef['ACC_RTRCT_MTHD'][$acc_rtrct_mthd];
    echo "<span class='badge ".$badge_style."'>".$word."</span>";
}

function SpoqDef()
{
	$SpoQDef = array();
	
	//그룹_카테고리_설정
	$SpoQDef["GRP_CATE_SET"] = array(
			"1RD" => "대분류"
			,"2RD" =>"중분류"
	);

	//메뉴 사용그룹
	$SpoQDef["MENU_GROUP"] = array(
		"AD" => "어드민"
		,"SU" =>"회사"
		,"MA" =>"지점"
		,"MT" =>"모바일(강사)"
		,"MC" =>"모바일(회원)"
	);
	
	//락커_설정
	$SpoQDef["LOCKR_SET"] = array(
			"Y" => "락커"
			,"N" =>"-"
	);
	
	//락커_종류
	$SpoQDef["LOCKR_KND"] = array(
	    "01" => "락커"
        ,"02" => "골프라커"
	    ,"" =>"-"
	);
	
	//락커_성별_설정
	$SpoQDef["LOCKR_GENDR_SET"] = array(
			"M" => "남자"
			,"F" =>"여자"
			,"G" =>"혼용"
			,"" =>""
	);
	
	//지점_신청_상태
	$SpoQDef["BCOFF_APPCT_STAT"] = array(
			"00" => "신청"
			,"01" =>"승인"
			,"02" =>"반려"
			,"99" =>"취소"
	);
	
	//지점_신청_상태
	$SpoQDef["AFFI_SET"] = array(
	    "Y" => "제휴사로 설정"
	    ,"N" =>"설정안함"
	);
	
	//회원_구분
	$SpoQDef["MEM_DV"] = array(
	    "M" => "회원"
	    ,"T" =>"강사"
	);
	
	//회원_상태
	$SpoQDef['MEM_STAT'] = array(
		"00" => "가입회원"
		,"01" => "현재회원"
		,"90" => "종료회원"
		,"99" => "탈퇴회원"
	);
	
	//성별
	$SpoQDef['MEM_GENDR'] = array(
	    "M" => "남"
	    ,"F" => "여"
	    ,"" => ""
	);
	
	//강사_직책
	$SpoQDef['TCHR_POSN'] = array(
	    "10" => "총괄팀장"
		,"15" => "PT 팀장"
	    ,"20" => "PT 트레이너"
	    ,"30" => "GOLF 프로"
	    ,"40" => "CS"
	    ,"50" => "FC 팀장"
	    ,"50" => "FC"
	    ,"60" => "GX 강사"
		,"65" => "스피닝 강사"
	    ,"70" => "미화직원"
	    ,"" => ""
	);
	
	//계약_형태
	$SpoQDef['CTRCT_TYPE'] = array(
	    "10" => "정규직"
	    ,"20" => "프리랜서"
	    ,"30" => "파트타임"
	    ,"" => ""
	);

	//이용권컬러
	$SpoQDef['M_CATE_PROPERTY'] = array(
	    "0" => ""
		,"10" => "기간제"
	    ,"20" => "횟수제"
		
	);

	// 아이콘 정의 (Iconify 아이콘으로 변경)
	// Font Awesome 아이콘은 'fa-' 접두사가 있었지만, Iconify는 '아이콘팩접두사:' 형식입니다.
	$SpoQDef['M_CATE_ICON'] = array(
		"PKG" => "mdi:package-variant-closed" // Material Design Icons - 패키지/상자 아이콘 (더 직접적)
											// 또는 "mdi:gift" (선물)
		,"PRV" => "solar:dumbbell-large-minimalistic-bold-duotone"             // Material Design Icons - 아령 (더 세련된 아령 아이콘)
											// 또는 "mdi:weight-lifter" (역도 선수)
		,"GRP" => "solar:users-group-two-rounded-bold-duotone"     // Material Design Icons - 스피닝 자전거 (그룹 수업에 매우 적합!)
											// 또는 "mdi:dance-ballroom" (댄스)
											// 또는 "mdi:human-dance" (춤추는 사람)
		,"MBS" => "solar:treadmill-round-bold-duotone"                // Material Design Icons - 심박동 (헬스 의미)
											// 또는 "mdi:weight-pound" (무게, 헬스)
		,"OPT" => "solar:key-minimalistic-2-bold-duotone"         // Material Design Icons - 여러 태그 (옵션/부가 서비스)
											// 또는 "mdi:plus-circle-outline" (추가 옵션)
	);

	// 색상 정의 (요청에 따라 밝은 계열 및 핑크색 유지)
	// Iconify 아이콘은 CSS에서 직접 색상을 받습니다.
	$SpoQDef['M_CATE_COLOR'] = array(
		"PKG" => "text-teal"
		,"PRV" => "text-purple-light" // 검정색 배경에서 잘 보이도록 유지
		,"GRP" => "text-green"   
		,"MBS" => "text-blue-light"       
		,"OPT" => "text-orange"      
	);

	//이용권클레스
	
	$SpoQDef['M_CATE_CLASS'] = array(
	    "PKG" => "text-alert-indigo-light"
	    ,"PRV" => "text-purple-light"
	    ,"GRP" => "text-teal-light"
	    ,"MBS" => "text-blue-light"
	    ,"OPT" => "text-warning-light"
	);

	//수업구분
	$SpoQDef['M_CATE'] = array(
	    // "PKG" => "팩키지 이용권",
		"PRV" => "개인레슨 이용권"
	    ,"GRP" => "그룹수업 이용권"
	    ,"MBS" => "회원권 (시설 이용권)"
	    ,"OPT" => "옵션 이용권"
	);

	
	//수업구분
	$SpoQDef['CLAS_DV'] = array(
	    "11" => "이용권"
	    ,"12" => "골프 이용권"
	    ,"21" => "PT"
	    ,"22" => "골프 PT"
	    ,"31" => "그룹 수업"
	    ,"91" => "기타(입장불가)"
	    ,"" => ""
	);
	
	//출입_제한_구분
	$SpoQDef['ACC_RTRCT_DV'] = array(
	    "99" => "입장불가"
	    ,"00" => "입장무제한"
	    ,"01" => "1일1회입장"
		,"50" => "수업입장"
	    ,"" => ""
	);

	//출입_제한_구분
	$SpoQDef['ACC_RTRCT_WEEK_UNIT'] = array(
	    "0" => "무제한"
	    ,"10" => "이용가능일수"
	    ,"20" => "이용가능횟수"
	);


	//출입_제한_구분
	$SpoQDef['ACC_RTRCT_DV2'] = array(
	    "00" => "입장가능"
	    ,"99" => "입장불가"
	);
	
	//출입제한_방법
	$SpoQDef['ACC_RTRCT_MTHD'] = array(
	    "99" => "입장불가"
	    ,"00" => "매일"
	    ,"05" => "평일"
	    ,"02" => "주말"
	    ,"70" => "시간"
		,"50" => "수업입장"
	    ,"" => ""
	);
	
	//이용_단위
	$SpoQDef['USE_UNIT'] = array(
	    "M" => "개월"
	    ,"D" => "일"
	    ,"" => ""
	);
	
	//결제_상테
	$SpoQDef['PAYMT_STAT'] = array(
	    "99" => "취소"
	    ,"00" => "결제"
	    ,"01" => "환불"
	    ,"" => ""
	);
	
	//결제_상테
	$SpoQDef['PAYMT_CHNL'] = array(
	    "M" => "모바일"
	    ,"P" => "데스크"
	    ,"K" => "키오스크"
	    ,"" => ""
	);
	
	//결제_수단
	$SpoQDef['PAYMT_MTHD'] = array(
	    "01" => "카드"
	    ,"02" => "계좌"
	    ,"03" => "현금"
	    ,"" => ""
	);
	
	//판매_상태
	$SpoQDef['SELL_STAT'] = array(
	    "00" => "판매중"
	    ,"90" => "판매종료"
	    ,"99" => "삭제"
	    ,"88" => "홀드"
	    ,"" => ""
	);
	
	//상품_상태
	$SpoQDef['EVENT_STAT'] = array(
	    "00" => "이용중"
	    ,"01" => "예약됨"
	    ,"99" => "종료됨"
	    ,"" => ""
	);
	
	//상품_상태
	$SpoQDef['EVENT_STAT_RSON'] = array(
	    "00" => "정상"
	    ,"51" => "환불(교체)"
	    ,"52" => "정상(교체)"
	    ,"81" => "환불"
	    ,"61" => "<strong><font color='red'>양도</font></strong>"
	    ,"62" => "<strong><font color='blue'>양수</font></strong>"
	    ,"91" => "강제(정상)"
	    ,"92" => "강제(양수)"
	    ,"93" => "강제(교체)"
	    ,"" => ""
	);
	
	//가입_장소
	$SpoQDef['JON_PLACE'] = array(
	    "01" => "센터방문"
	    ,"02" => "인터넷"
	    ,"" => ""
	);
	
	//등록_장소
	$SpoQDef['REG_PLACE'] = array(
	    "01" => "센터방문"
	    ,"02" => "인터넷"
	    ,"03" => "키오스크"
	    ,"" => ""
	);
	
	//이용_단위
	$SpoQDef['USE_UNIT'] = array(
	    "D" => "일"
	    ,"M" => "개월"
	    ,"" => ""
	);
	
	//미수금 처리상태
	$SpoQDef['RECVB_STAT'] = array(
	    "00" => "등록"
	    ,"01" => "완료"
	    ,"90" => "처리"
	    ,"" => ""
	);
	
	//매출_구분
	$SpoQDef['SALES_DV'] = array(
	    "00" => "정상매출"
	    ,"20" => "미수금매출"
	    ,"50" => "교체매출"
	    ,"60" => "양도비매출"
	    ,"90" => "환불매출"
	    ,"91" => "위약금매출"
	    ,"" => ""
	);
	
	
	//매출_구분_사유
	$SpoQDef['SALES_DV_RSON'] = array(
	    "00" => "신규"
	    ,"01" => "재등록"
	    ,"60" => "양도비"
	    ,"91" => "정상환불"
	    ,"92" => "교체환불"
        ,"10" => "환불이용료"
	    ,"11" => "환불위약금"
	    ,"12" => "환불기타금액"
	    ,"" => ""
	);
	
	//매출_회원_상태
	$SpoQDef['SALES_MEM_STAT'] = array(
	    "00" => "신규회원"
	    ,"01" => "현재회원"
	    ,"02" => "재등록회원"
	    ,"" => ""
	);
	
	//PT_수업_구분
	$SpoQDef['PT_CLAS_DV'] = array(
	    "00" => "정규"
	    ,"01" => "서비스"
	    ,"" => ""
	);
	
	//매출_구분_사유
	$SpoQDef['LOCKR_STAT'] = array(
	    "00" => "사용가능"
	    ,"01" => "이용중"
	    ,"50" => "연장됨"
	    ,"90" => "수리중"
	    ,"99" => "종료됨"
	    ,"" => ""
	);
	
	//휴회_관리_상태
	$SpoQDef['DOMCY_MGMT_STAT'] = array(
			"00" => "휴회신청"
			,"01" => "휴회중"
			,"09" => "휴회완료"
			,"90" => "휴회취소"
			,"" => ""
	);
	
	//수당_지급_조건
	$SpoQDef["SARLY_PMT_COND"] = array(
	    "01" =>"전체강사"
	    ,"02" =>"개인강사"
	    ,"03" =>"기본급"
	    ,"" => ""
	);
	
	//수당_지급_방법
	$SpoQDef["SARLY_PMT_MTHD"] = array(
	    "10" =>"수당 비율"
	    ,"20" =>"수당 금액"
	    ,"30" =>"수업회당 금액"
	    ,"" => ""
	);
	
	//수당_지급_방법 이름
	$SpoQDef["SARLY_PMT_MTHD_NAME"] = array(
	    "11" =>"[수당비율] 판매 매출액 / 판매 매출 요율"
	    ,"12" =>"[수당비율] 판매 매출액 / PT수업 매출 요율"
	    ,"13" =>"[수당비율] PT수업 매출액 / PT수업 매출 요율"
	    ,"21" =>"[수당금액] 판매 매출액 / 수당금액"
	    ,"22" =>"[수당금액] PT수업 매출액 / 수당금액"
	    ,"31" =>"[수당회당] PT수업 횟수 / 수업1회금액"
	    ,"32" =>"[수당회당] GX수업 횟수 / 수업1회금액"
	    ,"50" =>"기본급"
	    ,"" => ""
	);
	
	//보내기_상태
	$SpoQDef['SEND_STAT'] = array(
	    "00" => "대기"
	    ,"01" => "결제"
	    ,"90" => "만료"
	    ,"99" => "취소"
	    ,"" => ""
	);
	
	//연차_승인_상태
	$SpoQDef['ANNU_APPV_STAT'] = array(
	    "00" => "신청"
	    ,"01" => "승인"
	    ,"90" => "반려"
	    ,"99" => "취소"
	    ,"" => ""
	);
	
	//연차_신청_종류
	$SpoQDef['ANNU_APPCT_KND'] = array(
	    "01" => "연차"
	    ,"02" => "포상휴가"
	    ,"" => ""
	);
	
	//히스토리 상태
	$SpoQDef["CH_TYPE"] = array(
	    "CHTS" => "수업강사 변경"
        ,"CHTP" =>"판매강사 변경"
	    ,"CHES" =>"시작일 변경"
	    ,"CHEE" =>"종료일 변경"
	    ,"CHDD" =>"휴회일 변경"
	    ,"CHDC" =>"휴회횟수 변경"
	    ,"ADDCNT" =>"수업 추가"
	    ,"" => ""
	);
	
	//공지_상태
	$SpoQDef['NOTI_STAT'] = array(
	    "00" => "등록"
	    ,"09" => "종료"
	    ,"90" => "취소"
	    ,"99" => "삭제"
	    ,"" => ""
	);
	
	//공지 구분
	$SpoQDef["MEM_DV"] = array(
	    "01" => "회원 공지"
	    ,"02" =>"강사 공지"
	    ,"" => ""
	);
	
	return $SpoQDef;
}


/**
 * 두날짜 사이를 계산한다.
 * @param string $sdate
 * @param string $edate
 */
function disp_diff_date($sdate,$edate)
{
    $from = new \DateTime( $sdate );
    $to = new \DateTime( $edate );
    $add_days = $from -> diff( $to ) -> days;
    if ( $from > $to )
    {
        // 빼기
        //$add_days = '-' . $add_days;
        $add_days = $add_days * -1;
    } else
    {
        // 더하기
        $add_days = $add_days * 1;
    }
    
    return $add_days;
}


/**
 * 만나이 계산
 * @param string $birthDay
 */
function disp_age($birthDay = '')
{
    $age = "";
    if ($birthDay)
    {
        $bday = new DateTime($birthDay);
        $nday = new DateTime(date('Ymd'));
        $age = $bday->diff($nday)->y;
    }
    return $age;
}

function disp_locker($knd,$gendr,$lno)
{
	$returnVal = '';
	$gendr_txt = '';
	$knd_txt = '';
	
	if ($gendr == 'M') $gendr_txt = "남자";
	if ($gendr == 'F') $gendr_txt = "여자";
	if ($gendr == 'G') $gendr_txt = "공용";
	
	if ($knd == "01") $knd_txt = "[사]";
	if ($knd == "02") $knd_txt = "[골]";
	
	
	if ($lno != '')
	{
		if ($knd == '01')
		{
			$returnVal = "<small class='badge bg-info'>[사] " . $gendr_txt . " " . $lno . "번</small>";
		} elseif ($knd == '02')
		{
			$returnVal = "<small class='badge bg-info'>[골] " . $gendr_txt . " " . $lno . "번</small>";
		}
	} else 
	{
		$returnVal = "<small class='badge bg-danger'>" . $knd_txt ." 선택하기</small>";
	}
	
	return $returnVal;
}

function disp_locker_word($knd,$gendr,$lno)
{
    $returnVal = '';
    $gendr_txt = '';
    $knd_txt = '';
    
    if ($gendr == 'M') $gendr_txt = "남";
    if ($gendr == 'F') $gendr_txt = "여";
    if ($gendr == 'G') $gendr_txt = "공";
    
    if ($knd == "01") $knd_txt = "[사]";
    if ($knd == "02") $knd_txt = "[골]";
    
    
    if ($lno != '')
    {
        if ($knd == '01')
        {
            $returnVal = "[사:" . $gendr_txt . " " . $lno . "번] ";
        } elseif ($knd == '02')
        {
            $returnVal = "[골:" . $gendr_txt . " " . $lno . "번] ";
        }
    } else
    {
        $returnVal = "" . $knd_txt ." [미배정] ";
    }
    
    return $returnVal;
}

// 개월 수 표기
function disp_produnit($prod,$unit)
{
    $ss_def = SpoqDef();
    $returnVal = "-";
    
    if ($prod > 0)
    {
        $returnVal = $prod . " " . $ss_def['USE_UNIT'][$unit];
    }
    return $returnVal;
}

// 종료일에 추가된 일수 표기
function disp_add_cnt($cnt)
{
    $returnVal = "";
    
    $ccnt = $cnt;
    if ($cnt == "") $ccnt = 0;
    
    if ($ccnt != 0)
    {
        if ($ccnt > 0)
        {
            $returnVal = "(+" . $ccnt . ")";
        } else 
        {
            $returnVal = "(" . $ccnt . ")";
        }
    } else 
    {
        $ccnt = "";
    }
    
    $returnVal = "<font color='red'>" . $returnVal . "</font>";
    
    return $returnVal;
}

function disp_zeronull($var,$add_var='')
{
    $returnVal = "";
    if ($var != "0") $returnVal = $var . $add_var;
    return $returnVal;
}


/**
 * 강사 관리자 페이지에서 상품 리스트의 단계 표시를 함.
 * @param int $num
 */
function disp_depth($num)
{
    $returnVal = "";
    if ($num == 2)
    {
        $returnVal = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='green'>L</font>&nbsp;&nbsp;";
    } else if ($num == 3)
    {
        $returnVal = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='blue'>-</font>";
    }
    return $returnVal;
}


function scriptAlert($msg='')
{
    echo "<script>alert('{$msg}');</script>";
}

function scriptLocation($url='')
{
    echo "<script>location.href='" . $url . "';</script>";
}

function goUrl($url='')
{
	$fr_uri = service('uri');
	$return_url = '';
	if (preg_match('/^fr_/',$fr_uri->getSegment(1),$matches) == 1)
	{
		$return_url .= '/' . $fr_uri->getSegment(1);
	}
	
	$return_url .= $url;
	return $return_url;
}

function disp_nav($nav)
{
	$foreach_i = 0;
	$foreach_count = count($nav);
	foreach ($nav as $key => $value):
	if($foreach_i == 0 && $foreach_count+1 != $foreach_i)
	{
		echo '<li class="breadcrumb-item"><a href="'.$value.'">'.$key.'</a></li>';
	} else
	{
		echo '<li class="breadcrumb-item active">'.$key.'</li>';
	}
	$foreach_i++;
	endforeach;
}

function disp_date($var)
{
	$returnVal = '';
	if ($var) :
	
		if (strlen($var) == 8):
			$splitVar['yy'] = substr($var,0,4);
			$splitVar['mm'] = substr($var,4,2);
			$splitVar['dd'] = substr($var,6,2);
			
			$returnVal = $splitVar['yy'] . '-' . $splitVar['mm'] . '-' . $splitVar['dd'];
		else:
			$splitVar['yy'] = substr($var,0,4);
			$splitVar['mm'] = substr($var,4,2);
			$splitVar['dd'] = substr($var,6,2);
			$splitVar['hh'] = substr($var,8,2);
			$splitVar['ii'] = substr($var,10,2);
			$splitVar['ss'] = substr($var,12,2);
			
			$returnVal = $splitVar['yy'] . '-' . $splitVar['mm'] . '-' . $splitVar['dd'] . ' ' . $splitVar['hh'] . ':' . $splitVar['ii'] . ':' . $splitVar['ss'];
		endif;
		
	endif;
	
	return $returnVal;
}

function disp_hi($var)
{
	$returnVal = '';
	if ($var) :
	
		if (strlen($var) == 4):
			$splitVar['hh'] = substr($var,0,2);
			$splitVar['ii'] = substr($var,2,2);
		
			$returnVal = $splitVar['hh'] . ':' . $splitVar['ii'];
		else:
			$splitVar['hh'] = '';
			$splitVar['ii'] = '';
		
			$returnVal = $splitVar['hh'] . ':' . $splitVar['ii'];
		endif;
	
	endif;
	
	return $returnVal;
}

function disp_date_kr($var)
{
	$returnVal = '';
	if (strlen($var) == 8):
		$splitVar['yy'] = substr($var,0,4);
		$splitVar['mm'] = substr($var,4,2);
		$splitVar['dd'] = substr($var,6,2);
		
		//$returnVal = $splitVar['yy'] . "-" . $splitVar['mm'] . "-" . $splitVar['dd'];
		$returnVal = $splitVar['yy'] .'년' . $splitVar['mm'] . '월' . $splitVar['dd'] . '일';
	endif;
		
	return $returnVal;
}

function disp_time_kr($var)
{
	$returnVal = '';
	if (strlen($var) == 5):
		$splitVar['hh'] = substr($var,0,2);
		$splitVar['ii'] = substr($var,3,2);
		$returnVal = $splitVar['hh'] . '시' . $splitVar['ii'] . '분';
	endif;
	
	return $returnVal;
}


/**
 * 전화번호 형식에 맞게 화면에 표기함.
 */
function disp_phone($var)
{
	return preg_replace('/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/', '$1-$2-$3', $var);
}

function disp_birth($var)
{
    return preg_replace('/([0-9]{4})([0-9]{2})([0-9]{2})/', '$1/$2/$3', $var);
}

/**
 * 문자열을 받아서 0~9로된 숫자만 리턴한다.
 * @param string $var
 * return string 숫자만
 */
function put_num($var)
{
	return preg_replace("/[^0-9]*/s", "", $var);
}

function put_num2($var)
{
    return preg_replace("/[^-0-9]*/s", "", $var);
}

function _vardump($var)
{
	echo ('
	<div class="_vardump">
	<pre>');
	var_dump($var);
	echo ('
	</pre>
	</div>
	');
}

/**
 * 개인키 공개키 생성
 * @param string $password
 * @param number $bits
 * @param string $digest_algorithm
 * @return array[]
 */
function rsa_generate_keys($password , $bits=2048 , $digest_algorithm = 'sha256')
{
    $res = openssl_pkey_new(array(
        'digest_alg' => $digest_algorithm
        , 'private_key_bits' => $bits
        , 'private_key_type' => OPENSSL_KEYTYPE_RSA
    ));
    
    openssl_pkey_export($res, $private_key , $password);
    
    $public_key = openssl_pkey_get_details($res);
    $public_key = $public_key['key'];
    
    return array (
        'private_key' => $private_key
        , 'public_key' => $public_key
    );
}

/**
 * 공개키 암호화
 * @param string $plaintext
 * @param string $public_key
 * @return string
 */
function rsa_encrypt($plaintext, $public_key)
{
    // 용량 절감과 보안 향상을 위해 평문을 압축한다.
    //$plaintext = gzcompress($plaintext);
    
    //공개키를 사용하여 암호화 한다.
    $public_decode = @openssl_pkey_get_public($public_key);
    //if ($public_decode === false) return "false";
    
    $ciphertext = false;
    $status = @openssl_public_encrypt($plaintext, $ciphertext, $public_decode);
    
    //_vardump($status);
    
    //if ($status || $ciphertext === false) return "false";
    
    // 암호문을 base64 로 인코딩 하여 반환한다.
    return base64_encode($ciphertext);
}

/**
 * 개인키 사용한 복호화
 * @param string $ciphertext
 * @param string $private_key
 * @param string $password
 * @return boolean|array
 */
function rsa_decrypt($ciphertext, $private_key , $password)
{
    // 암호문을 base64로 디코딩 한다.
    $ciphertext = @base64_decode($ciphertext, true);
    if ($ciphertext === false) return false;
    
    //개인키를 사용하여 복호화 한다.
    
    $privkey_decoded = @openssl_pkey_get_private($private_key , $password);
    if ($privkey_decoded === false) return false;
    
    $plaintext = false;
    $status = @openssl_private_decrypt($ciphertext, $plaintext, $privkey_decoded);
    @openssl_pkey_free($privkey_decoded);
    if (!$status || $plaintext === false) return false;
    
    //압축을 해제하여 평문을 얻는다.
    
    //$plaintext = @gzuncompress($plaintext);
    if ($plaintext === false) return false;
    
    // 이상이 없는 경우 평문을 반환한다.
    return $plaintext;
}




/**
 * 특정 View 파일이 존재하는지 확인하는 함수
 * @param string $viewName
 * @return bool
 */
if (!function_exists('view_exists')) {
    function view_exists($viewName)
    {
        return file_exists(APPPATH . "Views/{$viewName}.php");
    }
}

