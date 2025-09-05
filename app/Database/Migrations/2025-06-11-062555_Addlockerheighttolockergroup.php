<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addlockerheighttolockergroup extends Migration
{
	public function up()
	{
		// tb_locker_group 테이블에 locker_height 컬럼 추가
		$this->forge->addColumn('tb_locker_group', [
			'locker_height' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'default' => 30,
				'comment' => '락커 높이 (cm)',
				'after' => 'locker_depth'
			]
		]);
		
		log_message('info', 'Added locker_height column to tb_locker_group table');
	}

	public function down()
	{
		// locker_height 컬럼 제거 (롤백)
		$this->forge->dropColumn('tb_locker_group', 'locker_height');
		
		log_message('info', 'Dropped locker_height column from tb_locker_group table');
	}
}
