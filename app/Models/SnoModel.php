<?php
namespace App\Models;

use CodeIgniter\Model;

class SnoModel extends Model
{
	
    /**
     * 지점코드 테이블 : 회사코드가 이미 있는지를 체크 하기 위한 카운트 리턴
     * @param array $data
     * @return array
     */
    public function list_zsno_bcoff_no_count(array $data)
    {
        $sql = "SELECT COUNT(*) as counter FROM smgmt_mgmt_tbl
                WHERE COMP_CD = :comp_cd:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
        ]);
        
        $count = $query->getResultArray();
        return $count[0]['counter'];
    }
    
    /**
     * 지점코드 테이블에 insert 한다.
     * @param array $data
     * @return array
     */
    public function insert_zsno_bcoff_no(array $data)
    {
        $sql = "INSERT zsno_bcoff_no SET
					COMP_CD 		= :comp_cd:
					,BCOFF_SNO_CNT	= :bcoff_sno_cnt:
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
            ,'bcoff_sno_cnt'	=> $data['bcoff_sno_cnt']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    
    /**
     * 지점 코드를 update 한다.
     * @param array $data
     * @return array
     */
    public function update_zsno_bcoff_no(array $data)
    {
        $sql = "UPDATE zsno_bcoff_no SET
					COMP_CD 		= :comp_cd:
					,BCOFF_SNO_CNT	= BCOFF_SNO_CNT + 1
				";
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
        ]);
        
        array_push($data,$query);
        return $data;
    }
    

    /**
     * 지점 코드 수를 가져온다.
     * @param array $data
     * @return array
     */
    public function cnt_zsno_bcoff_no(array $data)
    {
        $sql = "SELECT COUNT(*) AS BCOFF_SNO_CNT FROM zsno_bcoff_no
				WHERE COMP_CD = :comp_cd:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
        ]);
        
        array_push($data,$query);
        $r = $query->getResultArray();
        
        return $r[0]['BCOFF_SNO_CNT'];
    }

    /**
     * 업데이트된 지점 코드를 가져온다.
     * @param array $data
     * @return array
     */
    public function get_zsno_bcoff_no(array $data)
    {
        $sql = "SELECT * FROM zsno_bcoff_no
				WHERE COMP_CD = :comp_cd:
				";
        
        $query = $this->db->query($sql, [
            'comp_cd' 			=> $data['comp_cd']
        ]);
        
        array_push($data,$query);
        $r = $query->getResultArray();
        
        return $r[0]['BCOFF_SNO_CNT'];
    }
    
    public function mem_sno(array $data)
    {
        $sql_chk ="SELECT * FROM zsno_mem_no WHERE idx = 1";
        $query_chk = $this->db->query($sql_chk, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query_chk);
        $chk_date = $query_chk->getResultArray();
        
        if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
        
            $sql_tu = "TRUNCATE TABLE zsno_mem_no";
            $query_tu = $this->db->query($sql_tu, [
            ]);
            array_push($data,$query_tu);
        endif;
        
        $sql = "INSERT zsno_mem_no SET SNO_DATE = :sno_date:
			";
        
        $query = $this->db->query($sql, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    public function sell_event_sno(array $data)
    {
    	$sql_chk ="SELECT * FROM zsno_sell_event_no WHERE idx = 1";
    	$query_chk = $this->db->query($sql_chk, [
    			'sno_date' => $data['sno_date']
    	]);
    	
    	array_push($data,$query_chk);
    	$chk_date = $query_chk->getResultArray();
    	
    	if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
    	
    	$sql_tu = "TRUNCATE TABLE zsno_sell_event_no";
    	$query_tu = $this->db->query($sql_tu, [
    	]);
    	array_push($data,$query_tu);
    	endif;
    	
    	$sql = "INSERT zsno_sell_event_no SET SNO_DATE = :sno_date:
			";
    	
    	$query = $this->db->query($sql, [
    			'sno_date' => $data['sno_date']
    	]);
    	
    	array_push($data,$query);
    	return $query;
    }
    
    public function paymt_mgmt_sno(array $data)
    {
        $sql_chk ="SELECT * FROM zsno_paymt_van_no WHERE idx = 1";
        $query_chk = $this->db->query($sql_chk, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query_chk);
        $chk_date = $query_chk->getResultArray();
        
        if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
        
        $sql_tu = "TRUNCATE TABLE zsno_paymt_van_no";
        $query_tu = $this->db->query($sql_tu, [
        ]);
        array_push($data,$query_tu);
        endif;
        
        $sql = "INSERT zsno_paymt_van_no SET SNO_DATE = :sno_date:
			";
        
        $query = $this->db->query($sql, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    public function appno_sno(array $data)
    {
        $sql_chk ="SELECT * FROM zsno_appno_no WHERE idx = 1";
        $query_chk = $this->db->query($sql_chk, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query_chk);
        $chk_date = $query_chk->getResultArray();
        
        if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
        
        $sql_tu = "TRUNCATE TABLE zsno_appno_no";
        $query_tu = $this->db->query($sql_tu, [
        ]);
        array_push($data,$query_tu);
        endif;
        
        $sql = "INSERT zsno_appno_no SET SNO_DATE = :sno_date:
			";
        
        $query = $this->db->query($sql, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    public function buy_sno(array $data)
    {
    	$sql_chk ="SELECT * FROM zsno_buy_event_no WHERE idx = 1";
    	$query_chk = $this->db->query($sql_chk, [
    			'sno_date' => $data['sno_date']
    	]);
    	
    	array_push($data,$query_chk);
    	$chk_date = $query_chk->getResultArray();
    	
    	if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
    	
    	$sql_tu = "TRUNCATE TABLE zsno_buy_event_no";
    	$query_tu = $this->db->query($sql_tu, [
    	]);
    	array_push($data,$query_tu);
    	endif;
    	
    	$sql = "INSERT zsno_buy_event_no SET SNO_DATE = :sno_date:
			";
    	
    	$query = $this->db->query($sql, [
    			'sno_date' => $data['sno_date']
    	]);
    	
    	array_push($data,$query);
    	return $query;
    }
    
    public function misu_sno(array $data)
    {
        $sql_chk ="SELECT * FROM zsno_misu_no WHERE idx = 1";
        $query_chk = $this->db->query($sql_chk, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query_chk);
        $chk_date = $query_chk->getResultArray();
        
        if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
        
        $sql_tu = "TRUNCATE TABLE zsno_misu_no";
        $query_tu = $this->db->query($sql_tu, [
        ]);
        array_push($data,$query_tu);
        endif;
        
        $sql = "INSERT zsno_misu_no SET SNO_DATE = :sno_date:
			";
        
        $query = $this->db->query($sql, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    public function pay_sno(array $data)
    {
        $sql_chk ="SELECT * FROM zsno_pay_no WHERE idx = 1";
        $query_chk = $this->db->query($sql_chk, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query_chk);
        $chk_date = $query_chk->getResultArray();
        
        if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
        
        $sql_tu = "TRUNCATE TABLE zsno_pay_no";
        $query_tu = $this->db->query($sql_tu, [
        ]);
        array_push($data,$query_tu);
        endif;
        
        $sql = "INSERT zsno_pay_no SET SNO_DATE = :sno_date:
			";
        
        $query = $this->db->query($sql, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    public function sales_sno(array $data)
    {
        $sql_chk ="SELECT * FROM zsno_sales_no WHERE idx = 1";
        $query_chk = $this->db->query($sql_chk, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query_chk);
        $chk_date = $query_chk->getResultArray();
        
        if (empty($chk_date) || $chk_date[0]['SNO_DATE'] != $data['sno_date']) :
        
        $sql_tu = "TRUNCATE TABLE zsno_sales_no";
        $query_tu = $this->db->query($sql_tu, [
        ]);
        array_push($data,$query_tu);
        endif;
        
        $sql = "INSERT zsno_sales_no SET SNO_DATE = :sno_date:
			";
        
        $query = $this->db->query($sql, [
            'sno_date' => $data['sno_date']
        ]);
        
        array_push($data,$query);
        return $query;
    }
    
    
    
}