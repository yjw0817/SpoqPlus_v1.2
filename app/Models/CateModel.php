<?php
namespace App\Models;

use CodeIgniter\Model;

class CateModel extends Model
{
    // /**
    //  * 대분류 명을 가져온다.
    //  * @param array $data
    //  * @return array
    //  */
    // public function disp_cate1(array $data)
    // {
    //     $sql = "SELECT 1RD_CATE_CD, CATE_NM, USE_YN FROM 1rd_event_cate_tbl
    //             WHERE COMP_CD = :comp_cd:
    //             ";
    //     $query = $this->db->query($sql, [
    //         'comp_cd'			=> $data['comp_cd']
    //     ]);
        
    //     array_push($data,$query);
    //     return $query->getResultArray();
    // }
    
    /**
     * 중분류 명을 가져온다.
     * @param array $data
     * @return array
     */
    // public function disp_cate2(array $data)
    // {
    //     $sql = "SELECT 1RD_CATE_CD, 2RD_CATE_CD, CATE_NM, USE_YN, CLAS_DV FROM 2rd_event_cate_tbl
    //             WHERE COMP_CD = :comp_cd:
    //             AND BCOFF_CD = :bcoff_cd:
    //             ";
    //     $query = $this->db->query($sql, [
    //         'comp_cd'			=> $data['comp_cd']
    //         ,'bcoff_cd'			=> $data['bcoff_cd']
    //     ]);
        
    //     array_push($data,$query);
    //     return $query->getResultArray();
    // }


    /**
     * 대분류 명을 가져온다.
     * @param array $data
     * @return array
     */
    public function disp_cate1(array $data)
    {
       
        $result = [];
        $SpoQDef = SpoqDef();
        foreach ($SpoQDef['M_CATE'] as $code => $label) {
            $result[] = [
                '1RD_CATE_CD' => $code.'N',
                'CATE_NM' => $label,
                'USE_YN' => 'Y',
            ];
        }
        array_push($result, [
            '1RD_CATE_CD' => 'OPTY',
            'CATE_NM' => '옵션이용권 (락커)',
            'USE_YN' => 'Y',
        ]);
        return $result;
    }

    /**
     * 카테고리에 대한 등록 기간을 가져온다.
     * @param array $data
     * @return array
     */
    public function get_prod_by_cate1(array $data)
    {
        $sql = "SELECT DISTINCT CAST(CASE WHEN USE_PROD > 12 THEN 13 ELSE USE_PROD END AS SIGNED) AS USE_PROD
                FROM sell_event_mgmt_tbl
                WHERE SELL_YN = 'Y' AND MEM_DISP_YN = 'Y' 
                AND EVENT_TYPE = '10'
                AND COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND USE_UNIT = 'M'
                AND 1rd_CATE_CD = :cate1:
                ORDER BY CAST(CASE WHEN USE_PROD > 12 THEN 13 ELSE USE_PROD END AS SIGNED) 
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'cate1'           => $data['cate1']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }

    /**
     * 중분류 명을 가져온다.
     * @param array $data
     * @return array
     */
    public function disp_cate2(array $data)
    {
        $sql = "SELECT CONCAT(M_CATE, CASE WHEN LOCKR_SET = '' THEN 'N' ELSE IFNULL(LOCKR_SET, 'N') END) AS 1RD_CATE_CD, 
                       SELL_EVENT_SNO AS 2RD_CATE_CD, 
                       SELL_EVENT_NM AS CATE_NM, 
                       'Y' AS USE_YN, 
                       CLAS_DV 
                FROM sell_event_mgmt_tbl
                WHERE COMP_CD = :comp_cd: 
                AND BCOFF_CD = :bcoff_cd:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    /**
     * 상품 만들기 대분류 사용 리스트
     * @return array
     */
    public function cevent_u1rd_list(array $data)
    {
        $sql = "SELECT DISTINCT 1RD_CATE_CD, COMP_CD, CATE_NM, '' AS GRP_CATE_SET, LOCKR_SET, USE_YN FROM 1rd_event_cate_tbl
				WHERE COMP_CD = :comp_cd:
                AND USE_YN = 'Y'
                ORDER BY 1rd_cate_cd
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    

    /**
     * 중분류 그룹 리스트
     * @return array
     */
    public function event_group_list(array $data)
    {
        $sql = "SELECT * FROM sell_event_mgmt_tbl

				WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND M_CATE = :m_cate:
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'m_cate'		=> $data['m_cate']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }


    /**
     * 상품 만들기 대분류 사용 리스트
     * @return string
     */
    public function generate_u2rd_code(array $data)
    {
        $sql = "SELECT 2RD_CATE_CD + 1 AS new_code
                FROM 2rd_event_cate_tbl 
				WHERE COMP_CD = :comp_cd:
                AND 1RD_CATE_CD = :1rd_cate_cd:
                ORDER BY 2RD_CATE_CD DESC limit 1 
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'1rd_cate_cd'		=> $data['1rd_cate_cd']
        ]);
        
        $result = $query->getRowArray();

        // 결과가 없으면 '001' 반환
        return isset($result['new_code']) ? strval($result['new_code']) : $data['1rd_cate_cd'] . '0001';
    }



    public function get_2rd_list_by_1rd(array $data)
    {
        if (empty($data['1rd_cate_cd_arr']) || !is_array($data['1rd_cate_cd_arr'])) {
            return [];
        }

        // 'all'이 포함되어 있다면 전체 반환
        if (in_array('all', $data['1rd_cate_cd_arr'])) {
            $sql = "SELECT * FROM 2rd_event_cate_tbl
                    WHERE COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
                    AND USE_YN = 'Y'";
            
            $query = $this->db->query($sql, [
                'comp_cd' => $data['comp_cd'],
                'bcoff_cd' => $data['bcoff_cd']
            ]);

            return $query->getResultArray();
        }

        // 일반적인 다중 IN 조건
        $placeholders = [];
        $bind = [
            'comp_cd' => $data['comp_cd'],
            'bcoff_cd' => $data['bcoff_cd']
        ];

        foreach ($data['1rd_cate_cd_arr'] as $idx => $cate_cd) {
            $key = "cate_cd_" . $idx;
            $placeholders[] = ":$key:";
            $bind[$key] = $cate_cd;
        }

        $sql = "SELECT * FROM 2rd_event_cate_tbl
                WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD IN (" . implode(",", $placeholders) . ")
                AND USE_YN = 'Y'";

        $query = $this->db->query($sql, $bind);
        return $query->getResultArray();
    }


    /**
     * 상품 만들기 대분류 사용 리스트
     * @return array
     */
    public function cevent_u2rd_list(array $data)
    {
        $sql = "SELECT * FROM 2rd_event_cate_tbl
				WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD = :1rd_cate_cd:
                AND USE_YN = 'Y'
                ";
        $query = $this->db->query($sql, [
            'comp_cd'			=> $data['comp_cd']
            ,'bcoff_cd'			=> $data['bcoff_cd']
            ,'1rd_cate_cd'		=> $data['1rd_cate_cd']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    /**
     * 이용권에 대한 정보를 가져온다.
     * @param array $data
     */
    public function get_2rd_cate_info(array $data)
    {
    	$sql = "SELECT * FROM 2rd_event_cate_tbl
				WHERE COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD = :1rd_cate_cd:
				AND 2RD_CATE_CD = :2rd_cate_cd:
                AND USE_YN = 'Y'
                ";
    	$query = $this->db->query($sql, [
    			'comp_cd'			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    			,'1rd_cate_cd'		=> $data['1rd_cate_cd']
    			,'2rd_cate_cd'		=> $data['2rd_cate_cd']
    	]);
    	
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    
    // ############################################################################################################ //
    // ============================================================================================================ //
    //                                               [ 중분류 사용 설정 ]
    // ============================================================================================================ //
    // ############################################################################################################ //
    
    /**
     * 사용가능한 대분류 리스트
     * @param array $data
     */
    public function get_2rd_list(array $data)
    {
    	$sql = "SELECT A.*, B.CATE_NM AS CATE_NM1 FROM 2rd_event_cate_main_tbl AS A
                LEFT OUTER JOIN 1rd_event_cate_main_tbl AS B ON A.1RD_CATE_CD = B.1RD_CATE_CD
                WHERE COMP_CD = :comp_cd:
                ORDER BY B.CATE_NM, A.CATE_NM
				";
    	$query = $this->db->query($sql, [
    			'comp_cd'			=> $data['comp_cd']
    	]);
    	
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 대분류 사용 설정 리스트
     * @return array
     */
    public function use_2rd_list(array $data)
    {
    	$sql = "SELECT * FROM 2rd_event_cate_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd: 
                ";
    	$query = $this->db->query($sql, [
    			'comp_cd'			=> $data['comp_cd']
    			,'bcoff_cd'			=> $data['bcoff_cd']
    	]);
    	
    	array_push($data,$query);
    	return $query->getResultArray();
    }
    
    /**
     * 대분류 사용안함에 대해서 이미 상품중에 사용한 것이 있는지를 체크 한다.
     * 만약에 상품중에 이미 대분류를 사용 하고 있다면 사용안함으로 설정이 불가능하다.
     */
    public function use_2rd_sell_check_count(array $data)
    {
    	$sql = "SELECT COUNT(*) AS counter 
				FROM sell_event_mgmt_tbl
				WHERE COMP_CD = :comp_cd:
				AND BCOFF_CD = :bcoff_cd: 
				AND 2RD_CATE_CD = :2rd_cate_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'		=> $data['comp_cd']
    			,'bcoff_cd'		=> $data['bcoff_cd']
    			,'2rd_cate_cd'	=> $data['2rd_cate_cd']
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    /**
     * 대분류를 insert 할지 update 할지를 체크 한다.
     */
    public function use_2rd_check_count(array $data)
    {
    	$sql = "SELECT COUNT(*) AS counter
				FROM 2rd_event_cate_tbl
				WHERE COMP_CD 	= :comp_cd:
				AND BCOFF_CD 	= :bcoff_cd:
				AND 2RD_CATE_CD = :2rd_cate_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'comp_cd'		=> $data['comp_cd']
    			,'bcoff_cd'	=> $data['bcoff_cd']
    			,'2rd_cate_cd'	=> $data['2rd_cate_cd']
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    /**
     * 대분류를 insert 하기 전에 정보를 가져온다.
     */
    public function get_use_2rd_info(array $data)
    {
    	$sql = "SELECT * FROM 2rd_event_cate_main_tbl
				WHERE 2RD_CATE_CD = :2rd_cate_cd:
				AND COMP_CD = :comp_cd:
				";
    	
    	$query = $this->db->query($sql, [
    			'2rd_cate_cd'	=> $data['2rd_cate_cd']
    			,'comp_cd'	=> $data['comp_cd']
    	]);
    	array_push($data,$query);
    	return $query->getResultArray();
    }

    public function event_add_product_cnt($data, $cnt)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        $updateExpression = "GREATEST(COALESCE(product_cnt, 0) + {$cnt}, 0)";

        $sellEventMgmtTbl
            ->set('product_cnt', $updateExpression, false)
            ->where('sell_event_sno', $data['event_ref_sno'])
            ->update();
    }
    
    /**
     * 상품의 판매된 수량을 가져온다.
     */
    public function event_sold_cnt($data)
    {
        $sql = "SELECT COUNT(*) AS counter
				FROM sales_mgmt_tbl
				WHERE SELL_EVENT_SNO = :sell_event_sno:
				";
    	
    	$query = $this->db->query($sql, [
    			'sell_event_sno'		=> $data['sell_event_sno']
    	]);
    	
    	$count = $query->getResultArray();
    	return $count[0]['counter'];
    }
    
    public function pkg_group_event_save($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // 2️⃣ 삽입/업데이트할 데이터 설정
        $sellEventMgmtData = [
            'comp_cd'    => $data['comp_cd'],
            'bcoff_cd'    => $data['bcoff_cd'],
            'sell_event_nm'     => $data['pkg_group_event_nm'],
            'm_cate'  => 'PKG',
            'sell_yn'     => $data['sell_yn'],
        ];
        
        $sellEventMgmtData['sell_event_sno'] =  $data['pkg_group_event_sno'];
        $sellEventMgmtData['event_acc_ref_sno'] =  $data['pkg_group_event_sno'];
        $sellEventMgmtData['event_ref_sno'] =  $data['pkg_group_event_sno'];
        if($data['mode'] == "I")
        {
            $sellEventMgmtData['cre_id'] = $data['user_id'];
            $sellEventMgmtData['cre_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtTbl->insert($sellEventMgmtData);
        } else
        {   
            $sellEventMgmtData['mod_id'] = $data['user_id'];
            $sellEventMgmtData['mod_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtTbl->where('sell_event_sno', $data['pkg_group_event_sno'])
                ->update($sellEventMgmtData);
        }
        
        return $this->db->affectedRows() > 0 ? 1 : 0; // 업데이트 성공: 1, 변경 없음: 0
    }


    public function pkg_event_save($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // 2️⃣ 삽입/업데이트할 데이터 설정
        $sellEventMgmtData = [
            'comp_cd'    => $data['comp_cd'],
            'bcoff_cd'    => $data['bcoff_cd'],
            'sell_event_nm'     => $data['pkg_sell_event_nm'],
            'm_cate'  => 'PKG',
            'sell_amt' => $data["sell_amt"],
        ];
        if(isset($data['sell_yn']))
        {
            $sellEventMgmtData['sell_yn'] =  $data['sell_yn'];
        }
        $sellEventMgmtData['sell_s_date'] =  $data['sell_s_date'];
        $sellEventMgmtData['sell_e_date'] =  $data['sell_e_date'];
        $sellEventMgmtData['sell_event_sno'] =  $data['pkg_sell_event_sno'];
        $sellEventMgmtData['event_acc_ref_sno'] =  $data['pkg_sell_event_sno'];
        $sellEventMgmtData['event_ref_sno'] =  $data['pkg_group_event_sno'];
        if($data['mode'] == "I")
        {
            $sellEventMgmtData['cre_id'] = $data['user_id'];
            $sellEventMgmtData['cre_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtTbl->insert($sellEventMgmtData);
        } else
        {   
            $sellEventMgmtData['mod_id'] = $data['user_id'];
            $sellEventMgmtData['mod_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtTbl->where('sell_event_sno', $data['pkg_sell_event_sno'])
                ->update($sellEventMgmtData);
        }
        
        return $this->db->affectedRows() > 0 ? 1 : 0; // 업데이트 성공: 1, 변경 없음: 0
    }

    /**
     * 이벤트 판매 상품을 삭제한다.
     * @param array $data
     * @return int
     */
    public function delete_event_by_event_ref_sno($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // sell_event_sno 기준으로 삭제
        $sellEventMgmtTbl
            ->where('sell_event_sno', $data['sell_event_sno'])
            ->delete();

        // 삭제된 행 개수 반환
        return $this->db->affectedRows();
    }


    /**
     * 팩키지 아이템중 업데이트되거나 추가된 아이템을 제외하고 삭제한다. 
     * @param string $eventRefSno
     * @param array $excludeSnos
     * @return int
     */
    public function delete_sell_events1($eventRefSno, $excludeSnos) {
        $builder = $this->db->table('sell_event_mgmt_tbl');
        
        $builder->where('event_ref_sno', $eventRefSno);
        
        if (!empty($excludeSnos)) {
            $builder->whereNotIn('sell_event_sno', $excludeSnos);
        }
        
        $builder->delete();
        
        return $this->db->affectedRows();
    }

     /**
     * 분류를 저장한다.
     * @param array $data
     * @return int
     */
    public function cate_save($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // 2️⃣ 삽입/업데이트할 데이터 설정
        $sellEventMgmtData = [
            '1rd_cate_cd'    => $data['cate1'],
            '2rd_cate_cd'    => $data['cate2'],
            'grp_cate_set'   => $data['grp_cate_set'],
        ];

        $sellEventMgmtTbl->where('event_ref_sno', $data['event_ref_sno'])
            ->update($sellEventMgmtData);
        
        return $this->db->affectedRows() > 0 ? 1 : 0; // 업데이트 성공: 1, 변경 없음: 0
    }
    
    
     /**
     * 이벤트 판매 상품을 저장한다.
     * @param array $data
     * @return int
     */
    public function event_save($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // 2️⃣ 삽입/업데이트할 데이터 설정
        $sellEventMgmtData = [
            'comp_cd'    => $data['comp_cd'],
            'bcoff_cd'    => $data['bcoff_cd'],
            'sell_event_nm'     => $data['sell_event_nm'],
            'acc_rtrct_dv'   => $data['acc_rtrct_dv'],
            'clas_dv'   => $data['clas_dv'],
            'lockr_set' => $data['lockr_set'],
            'm_cate'  => $data['m_cate'],
            '1rd_cate_cd' => $data['cate1'],
            '2rd_cate_cd' => $data['cate2'],
            'event_type'       => $data['event_type'],
            'event_desc'      => $data['event_desc'],
            'pre_enter_min'       => $data['pre_enter_min'],
            'sell_yn'     => $data['sell_yn'],
            'use_prod' => $data['use_prod'],
            'use_unit' => $data['use_unit'],
            'sell_amt' => $data['sell_amt'],
            'lockr_knd'     => $data['lockr_knd'],   // 락커위치
            'lockr_set'     => $data['lockr_set'],   // 락커홀수·짝수
            'lockr_gendr_set' => $data['lockr_gendr_set'],  //락커사용혼용 때는 혼용 빼고
            // 이 부분은 띄어쓰기 주의: enter_from_time(맨 끝 스페이스 제거)
            'enter_from_time' => ($data['enter_from_time'] === '') ? null : $data['enter_from_time'],
            'enter_to_time'   => ($data['enter_to_time'] === '')   ? null : $data['enter_to_time'],
            'sell_s_date'   => ($data['sell_s_date'] === '')   ? null : $data['sell_s_date'],
            'sell_e_date'   => ($data['sell_e_date'] === '')   ? null : $data['sell_e_date'],

            'week_select'     => $data['week_select']   ?? '',
            'use_per_day'     => ($data['use_per_day'] === '') ? null : $data['use_per_day'],
            'use_per_week_unit'   => $data['use_per_week_unit'] ?? '',
            'use_per_week'    => ($data['use_per_week'] === '') ? null : $data['use_per_week'],
            'domcy_poss_event_yn' => ($data['domcy_poss_event_yn'] === '') ? null : $data['domcy_poss_event_yn'],
            'domcy_day'       => ($data['domcy_day'] === '') ? null : $data['domcy_day'],
            'domcy_cnt'       => ($data['domcy_cnt'] === '') ? null : $data['domcy_cnt'],
            'clas_cnt'        => ($data['clas_cnt'] === '') ? null : $data['clas_cnt'],
        ];

        $sellEventMgmtData['sell_event_sno'] = $data['sell_event_sno'];
        $sellEventMgmtData['event_acc_ref_sno'] = $data['event_acc_ref_sno'];
        $sellEventMgmtData['event_ref_sno'] = $data['event_ref_sno'];
        if($data['type'] == "I")
        {
            $sellEventMgmtData['cre_id'] = $data['user_id'];
            $sellEventMgmtData['cre_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtTbl->insert($sellEventMgmtData);
        } else
        {   
            $sellEventMgmtData['mod_id'] = $data['user_id'];
            $sellEventMgmtData['mod_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtTbl->where('sell_event_sno', $data['sell_event_sno'])
                ->update($sellEventMgmtData);
        }
        
        return $this->db->affectedRows() > 0 ? 1 : 0; // 업데이트 성공: 1, 변경 없음: 0
    }
    
    /**
     * 중분류 / 그룹의 판매 여부를 변경한다.
     * @param array $data
     * @return int
     */
    public function change_mem_disp_yn($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');
        $sellEventMgmtData = [
            'mem_disp_yn'    => $data['mem_disp_yn'],
        ];
        // sell_event_sno 기준으로 삭제
        $sellEventMgmtTbl
            ->where('sell_event_sno', $data['sell_event_sno'])
            ->update($sellEventMgmtData);

        // 삭제된 행 개수 반환
        return $this->db->affectedRows();
    }

    /**
     * 중분류 / 그룹의 판매 여부를 변경한다.
     * @param array $data
     * @return int
     */
    public function change_sell_yn($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');
        $sellEventMgmtData = [
            'sell_yn'    => $data['sell_yn'],
        ];
        // sell_event_sno 기준으로 삭제
        $sellEventMgmtTbl
            ->where('sell_event_sno', $data['sell_event_sno'])
            ->update($sellEventMgmtData);

        // 삭제된 행 개수 반환
        return $this->db->affectedRows();
    }

    /**
     * 판매중인 중분류 수량을 가져온다.
     * @param array $data
     * @return int
     */
    public function cnt_event_sell_yn($data)
    {
        $sql = "SELECT COUNT(*) AS counter
                FROM sell_event_mgmt_tbl
                WHERE EVENT_REF_SNO = :sell_event_sno:
                AND SELL_EVENT_SNO <> :sell_event_sno:
                AND SELL_YN = :sell_yn:";
        $query = $this->db->query($sql, [
            'sell_event_sno' => $data['sell_event_sno'],
            'sell_yn' => 'Y' ?? 'Y' // 기본값 'Y' 설정
        ]);

        $count = $query->getResultArray();
        return $count[0]['counter'] ?? 0; // 결과가 없으면 0 반환
    }

    
    /**
     * 이벤트를 삭제한다.
     * @param array $data
     * @return int
     */
    public function event_delete($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // sell_event_sno 기준으로 삭제
        $sellEventMgmtTbl
            ->where('sell_event_sno', $data['sell_event_sno'])
            ->delete();

        // 삭제된 행 개수 반환
        return $this->db->affectedRows();
    }

    /**
     * 이벤트 판매 그룹을 삭제한다.
     * @param array $data
     * @return int
     */
    public function event_group_delete($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // sell_event_sno 기준으로 삭제
        $sellEventMgmtTbl
            ->where('sell_event_sno', $data['sell_event_sno'])
            ->delete();

        // 삭제된 행 개수 반환
        return $this->db->affectedRows();
    }

    /**
     * 이벤트 판매 그룹을 저장한다.
     * @param array $data
     * @return int
     */
    public function event_group_save($data)
    {
        $sellEventMgmtTbl = $this->db->table('sell_event_mgmt_tbl');

        // 2️⃣ 삽입/업데이트할 데이터 설정
        $sellEventMgmtData = [
            'comp_cd'    => $data['comp_cd'],
            'bcoff_cd'    => $data['bcoff_cd'],
            'sell_event_nm'     => $data['sell_event_nm'],
            '1rd_cate_cd'   => $data['cate1'],
            '2rd_cate_cd'   => $data['cate2'],
            'acc_rtrct_dv'   => $data['acc_rtrct_dv'],
            'clas_dv'   => $data['clas_dv'],
            'm_cate'  => $data['m_cate'],
            'event_type'       => $data['event_type'],
            'event_desc'      => $data['event_desc'],
            'pre_enter_min'       => $data['pre_enter_min'],
            'domcy_poss_event_yn' => $data['domcy_poss_event_yn'],
            'clas_min'      => $data['clas_min'],
            'lockr_knd'     => $data['lockr_knd'],   // 락커위치
            'lockr_set'     => $data['lockr_set'],   // 락커 사용유무
            'sell_yn'     => $data['sell_yn']
        ];
        
        if($data['type'] == "I")  //신규시
        {
            $sellEventMgmtData['cre_id'] = $data['user_id'];
            $sellEventMgmtData['cre_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtData['sell_event_sno'] = $data['sell_event_sno'];
            $sellEventMgmtData['event_acc_ref_sno'] = $data['event_acc_ref_sno'];
            $sellEventMgmtData['event_ref_sno'] = $data['event_ref_sno'];
            $sellEventMgmtTbl->insert($sellEventMgmtData);
            return $this->db->affectedRows(); // 삽입된 행 개수 반환
            
        }  else{
            $sellEventMgmtData['mod_id'] = $data['user_id'];
            $sellEventMgmtData['mod_datetm'] = date('Y-m-d H:i:s');
            $sellEventMgmtTbl->where('sell_event_sno', $data['sell_event_sno'])
                    ->update($sellEventMgmtData);
        }

        return $this->db->affectedRows() > 0 ? 1 : 0; // 업데이트 성공: 1, 변경 없음: 0
    }

    /**
     * 대분류 사용 여부를 insert 한다.
     */
    public function insert_use_2rd_change(array $data)
    {
    	$sql = "INSERT 2rd_event_cate_tbl SET
					1RD_CATE_CD 		= :1rd_cate_cd:
					,2RD_CATE_CD 		= :2rd_cate_cd:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD			= :bcoff_cd:
					,CATE_NM			= :cate_nm:
					,GRP_CATE_SET		= :grp_cate_set:
					,CLAS_DV		    = :clas_dv:
                    ,LOCKR_SET			= :lockr_set:
                    ,LOCKR_KND          = :lockr_knd:
					,USE_YN				= :use_yn:
					,CRE_ID				= :cre_id:
					,CRE_DATETM			= :cre_datetm:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
    			
				";
    	$query = $this->db->query($sql, [
    			'1rd_cate_cd' 			=> $data['1rd_cate_cd']
    			,'2rd_cate_cd' 			=> $data['2rd_cate_cd']
    			,'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'cate_nm'				=> $data['cate_nm']
    			,'grp_cate_set'			=> $data['grp_cate_set']
    	        ,'clas_dv'			    => $data['clas_dv']
    			,'lockr_set'			=> $data['lockr_set']
    	        ,'lockr_knd'			=> $data['lockr_knd']
    			,'use_yn'				=> $data['use_yn']
    			,'cre_id'				=> $data['cre_id']
    			,'cre_datetm'			=> $data['cre_datetm']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    /**
     * 대분류 사용 여부를 update 한다.
     * @param array $data
     */
    public function update_use_2rd_change(array $data)
    {
    	$sql = "UPDATE 2rd_event_cate_tbl SET
					USE_YN				= :use_yn:
					,MOD_ID				= :mod_id:
					,MOD_DATETM			= :mod_datetm:
				WHERE COMP_CD			= :comp_cd:
				AND BCOFF_CD			= :bcoff_cd:
				AND 2RD_CATE_CD			= :2rd_cate_cd:
    			
				";
    	$query = $this->db->query($sql, [
    			'2rd_cate_cd' 			=> $data['2rd_cate_cd']
    			,'comp_cd'				=> $data['comp_cd']
    			,'bcoff_cd'				=> $data['bcoff_cd']
    			,'use_yn'				=> $data['use_yn']
    			,'mod_id'				=> $data['mod_id']
    			,'mod_datetm'			=> $data['mod_datetm']
    	]);
    	
    	array_push($data,$query);
    	return $data;
    }
    
    
    
    
    public function sch_cate1(array $data)
    {
        $sql = "SELECT 1RD_CATE_CD,CATE_NM FROM 1rd_event_cate_tbl WHERE 
                COMP_CD = :comp_cd:
                AND 1RD_CATE_CD IN ('PRVN', 'GRPN', 'MBSN')
                -- AND GRP_CATE_SET = '1RD'   
                AND USE_YN = 'Y'
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'	=> $data['comp_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }

    /**
     * PT나 횟수제 수업 검색
     */
    public function sch_cate3(array $data)
    {
        $sql = "SELECT 2RD_CATE_CD,CATE_NM 
                FROM 2rd_event_cate_tbl 
                WHERE 
                    COMP_CD = :comp_cd:
                    AND BCOFF_CD = :bcoff_cd:
                    AND EVENT_TYPE = '20'
                    -- AND GRP_CATE_SET = '2RD' 
                    AND USE_YN = 'Y'
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'	=> $data['comp_cd']
            ,'bcoff_cd'	=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function sch_cate2(array $data)
    {
        $sql = "SELECT 2RD_CATE_CD,CATE_NM FROM 2rd_event_cate_tbl WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD IN ('PRVN', 'GRPN', 'MBSN')
                -- AND GRP_CATE_SET = '2RD' 
                AND USE_YN = 'Y'
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'	=> $data['comp_cd']
            ,'bcoff_cd'	=> $data['bcoff_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    public function sch_cate2_by_1rd(array $data)
    {
        $sql = "SELECT 2RD_CATE_CD,CATE_NM FROM 2rd_event_cate_tbl WHERE 
                COMP_CD = :comp_cd:
                AND BCOFF_CD = :bcoff_cd:
                AND 1RD_CATE_CD = :1rd_cate_cd:
                -- AND GRP_CATE_SET = '2RD' 
                AND USE_YN = 'Y'
                ORDER BY CATE_NM
				";
        
        $query = $this->db->query($sql, [
            'comp_cd'	=> $data['comp_cd']
            ,'bcoff_cd'	=> $data['bcoff_cd']
            ,'1rd_cate_cd' => $data['1rd_cate_cd']
        ]);
        array_push($data,$query);
        return $query->getResultArray();
    }
    
    
    
    
    
    
}