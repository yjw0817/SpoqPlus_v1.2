<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSettlementFieldsToScheduleTable extends Migration
{
	public function up()
	{
		// gx_schd_mgmt_tbl 테이블에 정산 관련 컬럼 추가
		$fields = [
			'PAY_FOR_ZERO_YN' => [
				'type' => 'CHAR',
				'constraint' => 1,
				'default' => 'N',
				'comment' => '0명 참석시 수업비 지급 여부 (Y/N)'
			],
			'USE_PAY_RATE_YN' => [
				'type' => 'CHAR', 
				'constraint' => 1,
				'default' => 'N',
				'comment' => '인원별 수당 요율 사용 여부 (Y/N)'
			]
		];
		
		$this->forge->addColumn('gx_schd_mgmt_tbl', $fields);
	}

	public function down()
	{
		// 컬럼 삭제
		$this->forge->dropColumn('gx_schd_mgmt_tbl', ['PAY_FOR_ZERO_YN', 'USE_PAY_RATE_YN']);
	}
}
