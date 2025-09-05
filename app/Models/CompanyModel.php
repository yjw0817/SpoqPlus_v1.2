<?php
namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
	/**
	 * =========================================================================================
	 * [ Start ] 2024-04-27
	 * company_group_list [ 업체 그룹 리스트 ]
	 * =========================================================================================
	 */
	
	private function list_compnay_group_query(array $data)
	{
		$add_query = '';
		
		return $add_query;
	}
	
	private function id_dup_check_tm(array $data)
	{
		$sql = 'SELECT COUNT(*) AS counter FROM tmlist_tb
				WHERE tm_id = :dup_id:
				';
		$query = $this->db->query($sql, [
				'dup_id'			=> $data['dup_id']
		]);
		
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	private function id_dup_check_companyGroup(array $data)
	{
		$sql = 'SELECT COUNT(*) AS counter FROM company_group_tb
				WHERE comp_group_id = :dup_id:
				';
		$query = $this->db->query($sql, [
				'dup_id'			=> $data['dup_id']
		]);
		
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	private function id_dup_check_company(array $data)
	{
		$sql = 'SELECT COUNT(*) AS counter FROM company_tb
				WHERE comp_id = :dup_id:
				';
		$query = $this->db->query($sql, [
				'dup_id'			=> $data['dup_id']
		]);
		
		array_push($data,$query);
		return $query->getResultArray();
	}
	
	public function id_dup_check(array $data)
	{
		$result_company = $this->id_dup_check_company($data);
		$result_companyGroup = $this->id_dup_check_companyGroup($data);
		$result_Tm = $this->id_dup_check_tm($data);
		
		$result = $result_company[0]['counter'] + $result_companyGroup[0]['counter'] + $result_Tm[0]['counter'];
		
		return $result;
	}
	
	public function list_company_group_count(array $data)
	{
		$add_query = $this->list_compnay_group_query($data);
		
		$sql = "SELECT
					count(*) AS counter
				FROM company_group_tb
				WHERE 1=1 AND IFNULL(delyn,'') != 'Y' {$add_query}
				";
		$query = $this->db->query($sql);
		$count = $query->getResultArray();
		return $count[0]['counter'];
	}
	
	public function list_company_group(array $data)
	{
		$add_query = $this->list_compnay_group_query($data);
		
		$sql = "SELECT
					C.idx, C.comp_group_id, C.comp_group_name, C.comp_group_use, C.delyn
				FROM company_group_tb AS C
				WHERE 1=1 AND IFNULL(C.delyn,'') != 'Y' {$add_query}
				ORDER BY C.idx DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
		$query = $this->db->query($sql);
		
		return $query->getResultArray();
	}
	
	public function list_company_group_del(array $data)
	{
		$add_query = $this->list_compnay_group_query($data);
		
		$sql = "SELECT
					C.idx, C.comp_group_id, C.comp_group_name, C.comp_group_use, C.delyn
				FROM company_group_tb AS C
				WHERE 1=1 AND IFNULL(C.delyn,'') = 'Y' {$add_query}
				ORDER BY C.idx DESC
				";
		$query = $this->db->query($sql);
		
		return $query->getResultArray();
	}
	
	public function insert_company_group(array $data)
	{
		$sql = 'INSERT company_group_tb SET
					comp_group_name 	= :comp_group_name:
					,comp_group_id		= :comp_group_id:
					,comp_group_pass	= :comp_group_pass:
					,comp_group_use		= :comp_group_use:
				';
		$query = $this->db->query($sql, [
				'comp_group_name' 	=> $data['comp_group_name']
				,'comp_group_id'	=> $data['comp_group_id']
				,'comp_group_pass'	=> $data['comp_group_pass']
				,'comp_group_use'	=> $data['comp_group_use']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function update_company_group_delyn(array $data)
	{
		$sql = 'UPDATE company_group_tb SET
					delyn 		= :delyn:
				WHERE idx			= :idx:
				';
		$query = $this->db->query($sql, [
				'delyn' 	=> $data['delyn']
				,'idx'			=> $data['idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function get_company_group(array $data)
	{
		$sql = 'SELECT
					idx,comp_group_name,comp_group_pass,comp_group_use,comp_group_id
				
				FROM company_group_tb
				WHERE idx		= :idx:
				';
		
		$query = $this->db->query($sql, [
				'idx' 	=> $data['idx']
		]);
		return $query->getResultArray();
	}
	
	public function get_company_list()
	{
		$sql = 'SELECT
					*
				FROM company_tb
				';
		
		$query = $this->db->query($sql);
		return $query->getResultArray();
	}
	
	public function update_group_company(array $data)
	{
		$sql = 'UPDATE company_group_tb SET
					comp_group_name 		= :comp_group_name:
					,comp_group_pass		= :comp_group_pass:
					,comp_group_use		= :comp_group_use:
				WHERE idx			= :idx:
				';
		$query = $this->db->query($sql, [
				'comp_group_name' 	=> $data['comp_group_name']
				,'comp_group_pass'	=> $data['comp_group_pass']
				,'comp_group_use'		=> $data['comp_group_use']
				,'idx'			=> $data['idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function insert_company_group_manage(array $data)
	{
		$sql = 'INSERT company_group_manage_tb SET
					comp_group_idx 		= :comp_group_idx:
					,comp_idx			= :comp_idx:
				';
		$query = $this->db->query($sql, [
				'comp_group_idx' 	=> $data['comp_group_idx']
				,'comp_idx'			=> $data['comp_idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function get_company_group_manage(array $data)
	{
		$sql = 'SELECT
					C.idx,C.comp_name,C.comp_id, CG.idx AS cg_idx
				FROM company_group_manage_tb AS CG
				LEFT OUTER JOIN company_tb AS C ON CG.comp_idx = C.idx
				WHERE CG.comp_group_idx		= :idx:
				';
		
		$query = $this->db->query($sql, [
				'idx' 	=> $data['idx']
		]);
		return $query->getResultArray();
	}
	
	
	
	public function delete_company_group_manage(array $data)
	{
		$sql = 'delete FROM company_group_manage_tb WHERE
					idx 		= :idx:
				';
		$query = $this->db->query($sql, [
				'idx' 	=> $data['idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	/**
	 * =========================================================================================
	 * [ END ]
	 * company_group_list [ 업체 그룹 리스트 ]
	 * =========================================================================================
	 */
	
	public function insert_company(array $data)
	{
		$sql = 'INSERT company_tb SET
					comp_name 		= :comp_name:
					,comp_div		= :comp_div:
					,comp_id		= :comp_id:
					,comp_pass		= :comp_pass:
					,comp_use		= :comp_use:
				';
		$query = $this->db->query($sql, [
				'comp_name' 	=> $data['comp_name']
				,'comp_div'		=> $data['comp_div']
				,'comp_id'		=> $data['comp_id']
				,'comp_pass'	=> $data['comp_pass']
				,'comp_use'		=> $data['comp_use']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function update_company(array $data)
	{
		$sql = 'UPDATE company_tb SET
					comp_name 		= :comp_name:
					,comp_div		= :comp_div:
					,comp_pass		= :comp_pass:
					,comp_use		= :comp_use:
				WHERE idx			= :idx:
				';
		$query = $this->db->query($sql, [
				'comp_name' 	=> $data['comp_name']
				,'comp_div'		=> $data['comp_div']
				,'comp_pass'	=> $data['comp_pass']
				,'comp_use'		=> $data['comp_use']
				,'idx'			=> $data['idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function update_company_delyn(array $data)
	{
		$sql = 'UPDATE company_tb SET
					delyn 		= :delyn:
				WHERE idx			= :idx:
				';
		$query = $this->db->query($sql, [
				'delyn' 	=> $data['delyn']
				,'idx'			=> $data['idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function get_company(array $data)
	{
		$sql = 'SELECT
					idx,comp_name,comp_div,comp_pass,comp_use,comp_id
					
				FROM company_tb
				WHERE idx		= :idx:
				';
		
		$query = $this->db->query($sql, [
				'idx' 	=> $data['idx']
		]);
		return $query->getResultArray();
	}
	
	/**
	 * =========================================================================================
	 * [ Start ]
	 * company_list [ 업체 리스트 ]
	 * =========================================================================================
	 */
	
	private function list_compnay_query(array $data)
	{
		$add_query = '';
		if ( isset($data['comp_id']) ) :
			if ( $data['comp_id'] != '' ) :
				$add_query .= " AND C.comp_id LIKE '%{$data['comp_id']}%' ";
			endif;
		endif;
		return $add_query;
	}
	
	public function list_company_count(array $data)
	{
		$add_query = $this->list_compnay_query($data);
		
		$sql = "SELECT
					count(*) AS counter
				FROM company_tb
				WHERE 1=1 AND IFNULL(delyn,'') != 'Y' {$add_query}
				";
		$query = $this->db->query($sql);
		$count = $query->getResultArray();
		return $count[0]['counter'];
	}
	
	public function list_company(array $data)
	{
		$add_query = $this->list_compnay_query($data);
		
		$sql = "SELECT
					C.idx,C.comp_name,C.comp_div,C.comp_pass,C.comp_use,C.comp_id , GC.gname
					, (SELECT count(*) AS counter FROM event_tb WHERE event_tb.comp_idx = C.idx) AS comp_event_counter
				FROM company_tb AS C
				LEFT OUTER JOIN gcode_tb AS GC ON C.comp_div = GC.gcode
				WHERE 1=1 AND IFNULL(delyn,'') != 'Y' {$add_query}
				ORDER BY C.idx DESC
				limit {$data['limit_s']} , {$data['limit_e']}
				";
		$query = $this->db->query($sql);
		
		return $query->getResultArray();
	}
	
	public function list_company_del(array $data)
	{
		$add_query = $this->list_compnay_query($data);
		
		$sql = "SELECT
					C.idx,C.comp_name,C.comp_div,C.comp_pass,C.comp_use,C.comp_id , GC.gname
					, (SELECT count(*) AS counter FROM event_tb WHERE event_tb.comp_idx = C.idx) AS comp_event_counter
				FROM company_tb AS C
				LEFT OUTER JOIN gcode_tb AS GC ON C.comp_div = GC.gcode
				WHERE 1=1 AND IFNULL(delyn,'') = 'Y' {$add_query}
				ORDER BY C.idx DESC
				";
		$query = $this->db->query($sql);
		
		return $query->getResultArray();
	}
	
	/**
	 * 티엠 관리에서 업체 설정을 위해 사용함.
	 * 업체 리스트를 가져온다.
	 * @param array $data
	 * @return array
	 */
	public function list_tm_company(array $data)
	{
		$sql = 'SELECT
					idx AS comp_idx,comp_name,comp_id,comp_use
				FROM company_tb
				WHERE comp_use		=:comp_use:
				';
		$query = $this->db->query($sql, [
				'comp_use' 	=> $data['comp_use']
		]);
		
		return $query->getResultArray();
	}
	
	/**
	 * =========================================================================================
	 * [ End ]
	 * company_list [ 업체 리스트 ]
	 * =========================================================================================
	 */
	
	public function insert_event(array $data)
	{
		$sql = 'INSERT event_tb SET
					event_name 		= :event_name:
					,event_div		= :event_div:
					,event_damdang	= :event_damdang:
					,event_url		= :event_url:
					,comp_idx		= :comp_idx:
				';
		$query = $this->db->query($sql, [
				'event_name' 		=> $data['event_name']
				,'event_div'		=> $data['event_div']
				,'event_damdang'	=> $data['event_damdang']
				,'event_url'		=> $data['event_url']
				,'comp_idx'			=> $data['comp_idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function list_event(array $data)
	{
		$sql = "SELECT
					E.idx,E.comp_idx,E.event_name,E.event_div,E.event_damdang,E.event_url
					, NC.nname
				FROM event_tb as E
				LEFT OUTER JOIN ncode_tb AS NC ON E.event_div = NC.ncode
				WHERE IFNULL(E.delyn,'') != 'Y' AND E.comp_idx		= :comp_idx:
				ORDER BY E.idx DESC
				";
		$query = $this->db->query($sql, [
				'comp_idx'			=> $data['comp_idx']
		]);
		
		return $query->getResultArray();
	}
	
	public function list_event_del(array $data)
	{
		$sql = "SELECT
					E.idx,E.comp_idx,E.event_name,E.event_div,E.event_damdang,E.event_url
					, NC.nname
				FROM event_tb as E
				LEFT OUTER JOIN ncode_tb AS NC ON E.event_div = NC.ncode
				WHERE IFNULL(E.delyn,'') = 'Y' AND E.comp_idx		= :comp_idx:
				ORDER BY E.idx DESC
				";
		$query = $this->db->query($sql, [
				'comp_idx'			=> $data['comp_idx']
		]);
		
		return $query->getResultArray();
	}
	
	
	
	
	public function get_event(array $data)
	{
		$sql = 'SELECT
					idx,comp_idx,event_name,event_div,event_damdang,event_url
				FROM event_tb
				WHERE 
					comp_idx 		= :comp_idx:
					AND idx			= :idx:
				ORDER BY idx DESC
				';
		$query = $this->db->query($sql, [
				'comp_idx'	=> $data['comp_idx']
				,'idx'		=> $data['idx']
		]);
		
		return $query->getResultArray();
	}
	
	public function update_event(array $data)
	{
		$sql = 'UPDATE event_tb SET
					event_name 		= :event_name:
					,event_div		= :event_div:
					,event_damdang	= :event_damdang:
					,event_url		= :event_url:
				WHERE 
					idx			= :idx:
					AND comp_idx = :comp_idx:
				';
		$query = $this->db->query($sql, [
				'event_name' 		=> $data['get_event_name']
				,'event_div'		=> $data['get_event_div']
				,'event_damdang'	=> $data['get_event_damdang']
				,'event_url'		=> $data['get_event_url']
				,'idx'				=> $data['get_event_idx']
				,'comp_idx'			=> $data['get_event_comp_idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	public function update_event_delyn(array $data)
	{
		$sql = 'UPDATE event_tb SET
					delyn 		= :delyn:
				WHERE
					idx			= :idx:
				';
		$query = $this->db->query($sql, [
				'delyn' 		=> $data['delyn']
				,'idx'				=> $data['idx']
		]);
		
		array_push($data,$query);
		return $data;
	}
	
	
}
