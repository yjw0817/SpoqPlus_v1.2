<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * PG/VAN 설정 JSON 컬럼 추가 마이그레이션
 * bcoff_mgmt_tbl 테이블에 PG/VAN 설정을 위한 JSON 컬럼들을 추가합니다.
 */
class AddPgVanSettingsColumns extends Migration
{
	public function up()
	{
		// bcoff_mgmt_tbl에 PG/VAN 설정 JSON 컬럼 추가
		$fields = [
			'PG_SETTINGS' => [
				'type' => 'JSON',
				'null' => true,
				'comment' => 'PG(결제대행) 설정 JSON 데이터 - KCP, 이니시스, 토스페이먼츠 등 PG사별 설정'
			],
			'VAN_SETTINGS' => [
				'type' => 'JSON',
				'null' => true,
				'comment' => 'VAN(신용카드단말기) 설정 JSON 데이터 - KSNET, NICE, KICC 등 VAN사별 설정'
			],
			'PAYMENT_DEFAULT_SETTINGS' => [
				'type' => 'JSON',
				'null' => true,
				'comment' => '결제 기본 설정 JSON 데이터 - 기본 결제수단, 우선순위, 공통 설정 등'
			],
			'PG_VAN_BACKUP_SETTINGS' => [
				'type' => 'JSON',
				'null' => true,
				'comment' => 'PG/VAN 설정 백업 데이터 - 이전 설정값 보관용'
			],
			'SETTINGS_UPDATE_HIST' => [
				'type' => 'JSON',
				'null' => true,
				'comment' => '설정 변경 이력 JSON 데이터 - 변경일시, 변경자, 변경내용 등'
			]
		];

		$this->forge->addColumn('bcoff_mgmt_tbl', $fields);
		
		// PG/VAN 설정 업데이트를 위한 인덱스 추가
		$this->db->query("ALTER TABLE bcoff_mgmt_tbl ADD INDEX idx_pg_van_settings (COMP_CD, BCOFF_CD, MOD_DATETM)");
	}

	public function down()
	{
		// 인덱스 제거
		$this->db->query("ALTER TABLE bcoff_mgmt_tbl DROP INDEX idx_pg_van_settings");
		
		// 컬럼 제거
		$this->forge->dropColumn('bcoff_mgmt_tbl', [
			'PG_SETTINGS',
			'VAN_SETTINGS', 
			'PAYMENT_DEFAULT_SETTINGS',
			'PG_VAN_BACKUP_SETTINGS',
			'SETTINGS_UPDATE_HIST'
		]);
	}
}