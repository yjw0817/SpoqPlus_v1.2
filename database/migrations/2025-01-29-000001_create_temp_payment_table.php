<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * 임시 결제 테이블 생성
 * 
 * 이니시스 결제 처리 중 임시 데이터를 저장하기 위한 테이블
 */
class CreateTempPaymentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'TEMP_PAYMT_SNO' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
                'comment' => '임시결제일련번호'
            ],
            'TID' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => '거래고유번호'
            ],
            'BCOFF_CD' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
                'comment' => '지점코드'
            ],
            'MEM_SNO' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => false,
                'comment' => '회원일련번호'
            ],
            'PAYMT_AMT' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'comment' => '결제금액'
            ],
            'PAYMT_MTHD' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
                'comment' => '결제방법'
            ],
            'PAYMT_STAT' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => false,
                'default' => '01',
                'comment' => '결제상태(00:완료,01:대기,99:실패)'
            ],
            'API_RESPONSE' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'API응답데이터'
            ],
            'CRE_DATETM' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => '생성일시'
            ],
            'EXP_DATETM' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => '만료일시'
            ]
        ]);
        
        $this->forge->addPrimaryKey('TEMP_PAYMT_SNO');
        $this->forge->addUniqueKey('TID');
        $this->forge->addKey(['BCOFF_CD', 'MEM_SNO']);
        $this->forge->addKey('PAYMT_STAT');
        $this->forge->addKey('CRE_DATETM');
        
        $this->forge->createTable('temp_payment_tbl', true);
        
        // 인덱스 추가
        $this->db->query('ALTER TABLE temp_payment_tbl COMMENT = "임시결제관리테이블"');
        
        // 자동 만료 처리를 위한 이벤트 스케줄러 (MySQL 5.1+)
        $this->db->query("
            CREATE EVENT IF NOT EXISTS cleanup_temp_payments
            ON SCHEDULE EVERY 1 HOUR
            DO
            DELETE FROM temp_payment_tbl 
            WHERE CRE_DATETM < DATE_SUB(NOW(), INTERVAL 2 HOUR)
        ");
    }

    public function down()
    {
        // 이벤트 삭제
        $this->db->query('DROP EVENT IF EXISTS cleanup_temp_payments');
        
        // 테이블 삭제
        $this->forge->dropTable('temp_payment_tbl');
    }
}