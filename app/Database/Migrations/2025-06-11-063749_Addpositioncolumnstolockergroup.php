<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addpositioncolumnstolockergroup extends Migration
{
	public function up()
	{
		// tb_locker_group 테이블에 position 컬럼들 추가
		$this->forge->addColumn('tb_locker_group', [
			'position_x' => [
				'type' => 'DECIMAL',
				'constraint' => '10,2',
				'default' => 0.00,
				'null' => false,
				'comment' => 'X 좌표 (픽셀)',
				'after' => 'total_count'
			],
			'position_y' => [
				'type' => 'DECIMAL',
				'constraint' => '10,2',
				'default' => 0.00,
				'null' => false,
				'comment' => 'Y 좌표 (픽셀)',
				'after' => 'position_x'
			]
		]);
		
		log_message('info', 'Added position_x, position_y columns to tb_locker_group table');
	}

	public function down()
	{
		// position 컬럼들 제거 (롤백)
		$this->forge->dropColumn('tb_locker_group', ['position_x', 'position_y']);
		
		log_message('info', 'Dropped position_x, position_y columns from tb_locker_group table');
	}
}
