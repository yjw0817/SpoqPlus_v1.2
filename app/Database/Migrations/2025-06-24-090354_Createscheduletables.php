<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScheduleTables extends Migration
{
	public function up()
	{
		// gx_schd_event_tbl: 스케줄별 참석 가능한 이용권 테이블
		$this->forge->addField([
			'GX_SCHD_EVENT_SNO' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true,
				'comment' => 'GX_스케쥴_이벤트_일련번호'
			],
			'GX_SCHD_MGMT_SNO' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'comment' => 'GX_스케쥴_관리_일련번호'
			],
			'SELL_EVENT_SNO' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'comment' => '판매_종목_일련번호'
			],
			'CRE_ID' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'comment' => '등록_아이디'
			],
			'CRE_DATETM' => [
				'type' => 'DATETIME',
				'comment' => '등록_일시'
			]
		]);
		
		$this->forge->addKey('GX_SCHD_EVENT_SNO', true);
		$this->forge->addKey('GX_SCHD_MGMT_SNO');
		$this->forge->addKey('SELL_EVENT_SNO');
		$this->forge->createTable('gx_schd_event_tbl', true, [
			'ENGINE' => 'InnoDB',
			'CHARSET' => 'utf8mb4',
			'COLLATE' => 'utf8mb4_unicode_ci',
			'COMMENT' => 'GX 스케줄별 참석 가능한 이용권'
		]);

		// gx_schd_pay_tbl: 스케줄별 수당 요율표 테이블
		$this->forge->addField([
			'GX_SCHD_PAY_SNO' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true,
				'comment' => 'GX_스케쥴_PAY_일련번호'
			],
			'GX_SCHD_MGMT_SNO' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'comment' => 'GX_스케쥴_일련번호'
			],
			'CLAS_ATD_NUM_S' => [
				'type' => 'INT',
				'constraint' => 11,
				'comment' => '범위 시작'
			],
			'CLAS_ATD_NUM_E' => [
				'type' => 'INT',
				'constraint' => 11,
				'comment' => '범위 종료'
			],
			'PAY_RATE' => [
				'type' => 'DECIMAL',
				'constraint' => '10,2',
				'comment' => '요율'
			],
			'CRE_ID' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
				'comment' => '등록_아이디'
			],
			'CRE_DATETM' => [
				'type' => 'DATETIME',
				'comment' => '등록_일시'
			]
		]);
		
		$this->forge->addKey('GX_SCHD_PAY_SNO', true);
		$this->forge->addKey('GX_SCHD_MGMT_SNO');
		$this->forge->addKey(['CLAS_ATD_NUM_S', 'CLAS_ATD_NUM_E']);
		$this->forge->createTable('gx_schd_pay_tbl', true, [
			'ENGINE' => 'InnoDB',
			'CHARSET' => 'utf8mb4',
			'COLLATE' => 'utf8mb4_unicode_ci',
			'COMMENT' => 'GX 스케줄별 수당 요율표'
		]);
	}

	public function down()
	{
		$this->forge->dropTable('gx_schd_event_tbl', true);
		$this->forge->dropTable('gx_schd_pay_tbl', true);
	}
}
