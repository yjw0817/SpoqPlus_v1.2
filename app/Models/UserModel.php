<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	
    
    
	// 2024-04-27 추가
	public function cg_user_login_chk(array $data)
	{
		$sql = 'SELECT idx, comp_group_name AS user_name , comp_group_id AS user_id
				FROM company_group_tb
				WHERE
				comp_group_id = :user_id:
				AND comp_group_pass = :user_pass:';
		$query = $this->db->query($sql, [
				'user_id' 	=> $data['login_email']
				,'user_pass'	=> $data['login_password']
		]);
		
		return $query->getResultArray();
	}
	
	// 2024-04-27 추가
	public function cg_idxs(array $data)
	{
		$sql = 'SELECT comp_idx
				FROM company_group_manage_tb
				WHERE
				comp_group_idx = :idx:';
		$query = $this->db->query($sql, [
				'idx' 	=> $data['idx']
		]);
		
		return $query->getResultArray();
	}
	
	/**
	 * 제휴사의 로그인 아이디 비번을 체크 한다.
	 * @param array $data
	 * @return array
	 */
	public function user_login_chk(array $data)
	{
		/*
		$sql = "SELECT *
				FROM users
				WHERE
				user_pass = :user_pass:
				AND user_email = :user_email:";
		$query = $this->db->query($sql, [
				'user_pass' 	=> $data['login_password']
				,'user_email'	=> $data['login_email']
		]);
		*/
		
		$sql = 'SELECT idx, comp_name AS user_name , comp_id AS user_id
				FROM company_tb
				WHERE
				comp_id = :user_id:
				AND comp_pass = :user_pass:';
		$query = $this->db->query($sql, [
				'user_id' 	=> $data['login_email']
				,'user_pass'	=> $data['login_password']
		]);
		
		return $query->getResultArray();
	}
	
	/**
	 * TM의 로그인 아이디 비번을 체크한다.
	 * @param array $data
	 * @return array
	 */
	public function tm_user_login_chk(array $data)
	{
		
		$sql = 'SELECT idx, tm_name AS user_name , tm_id AS user_id, comp_idx , tm_phone
				,hf_01,hf_02,hf_03,hf_04,hf_05,hf_06
				FROM tmlist_tb
				WHERE
				tm_id = :user_id:
				AND tm_pass = :user_pass:';
		$query = $this->db->query($sql, [
				'user_id' 	=> $data['login_email']
				,'user_pass'	=> $data['login_password']
		]);
		
		return $query->getResultArray();
	}
	
	
	/**
	 * users 테이블에서 해당 조건을 검색하나 count 값을 리턴
	 * set :: user_google_id
	 * set :: user_email
	 * @param array $var
	 * @return array
	 */
	public function users_google_exist_check_count(array $data)
	{
		$sql = 'SELECT * 
				FROM users
				WHERE 
				user_google_id = :user_google_id:
				AND user_email = :user_email:';
		$query = $this->db->query($sql, [
				'user_google_id' 	=> $data['user_google_id']
				,'user_email'		=> $data['user_email']
		]);
		
		return $query->getResultArray();
		
		/*
		$this->builder = $this->db->table('users');
		$this->builder->select('COUNT(*) AS counter');
		$this->builder->where('user_google_id',$var['user_google_id']);
		$this->builder->where('user_email',$var['user_email']);
		$result['result'] = $this->paginate(10);
		$result['pager'] = $this->pager;
		return $result;
		*/
	}
	
	/**
	 * 구글 회원 가입 이후에 가입 처리를 한다.
	 * @param array $data
	 * @return Object
	 */
	public function insert_moreask_join(array $data)
	{
		$sql = 'INSERT users SET 
					user_phone 		= :user_phone:
					,user_name 		= :user_name:
					,user_email 	= :user_email:
					,user_google_id = :user_google_id:
				WHERE user_email != :user_email:
				';
		$query = $this->db->query($sql, [
				'user_phone' 		=> $data['user_phone']
				,'user_name' 		=> $data['user_name']
				,'user_email' 		=> $data['user_email']
				,'user_google_id'	=> $data['user_google_id']
		]);
		
		array_push($data,$query);
		
		return $data;
	}
}
