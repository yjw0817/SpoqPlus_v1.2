<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addlockertypetolockergroup extends Migration
{
	public function up()
	{
		// tb_locker_group 테이블에 locker_type 컬럼 추가
		$this->forge->addColumn('tb_locker_group', [
			'locker_type' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => '일반락커',
				'null' => false,
				'comment' => '락커 타입 (일반락커, 귀중품락커, 냉장락커 등)',
				'after' => 'locker_height'
			]
		]);
		
		log_message('info', 'Added locker_type column to tb_locker_group table');
	}

	public function down()
	{
		// locker_type 컬럼 제거 (롤백)
		$this->forge->dropColumn('tb_locker_group', 'locker_type');
		
		log_message('info', 'Dropped locker_type column from tb_locker_group table');
	}
}
