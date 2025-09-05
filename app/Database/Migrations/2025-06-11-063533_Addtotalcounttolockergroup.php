<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addtotalcounttolockergroup extends Migration
{
	public function up()
	{
		// tb_locker_group 테이블에 total_count 컬럼 추가
		$this->forge->addColumn('tb_locker_group', [
			'total_count' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'default' => 0,
				'null' => false,
				'comment' => '락커 총 개수',
				'after' => 'locker_type'
			]
		]);
		
		log_message('info', 'Added total_count column to tb_locker_group table');
	}

	public function down()
	{
		// total_count 컬럼 제거 (롤백)
		$this->forge->dropColumn('tb_locker_group', 'total_count');
		
		log_message('info', 'Dropped total_count column from tb_locker_group table');
	}
}
