<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogAnalysisTables extends Migration
{
    public function up()
    {
        // log_analysis 테이블
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'log_date' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => '로그 발생 시간',
            ],
            'error_level' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
                'comment' => '오류 레벨 (CRITICAL, ERROR, WARNING, INFO)',
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => false,
                'comment' => '오류 메시지',
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
                'comment' => '오류 발생 파일 경로',
            ],
            'line_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => '오류 발생 라인 번호',
            ],
            'stack_trace' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => '스택 트레이스',
            ],
            'user_id' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => '사용자 ID',
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'comment' => '사용자 이름',
            ],
            'company_cd' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => '회사 코드',
            ],
            'branch_cd' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => '지점 코드',
            ],
            'request_url' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
                'comment' => '요청 URL',
            ],
            'request_method' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
                'comment' => '요청 메서드 (GET, POST 등)',
            ],
            'request_params' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => '요청 파라미터',
            ],
            'session_data' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => '세션 데이터',
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
                'comment' => '사용자 에이전트',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'IP 주소',
            ],
            'error_hash' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => true,
                'comment' => '오류 고유 해시 (중복 체크용)',
            ],
            'occurrence_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'comment' => '발생 횟수',
            ],
            'first_occurred' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => '최초 발생 시간',
            ],
            'last_occurred' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => '최근 발생 시간',
            ],
            'is_resolved' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => '해결 여부',
            ],
            'resolved_by' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => '해결한 사용자',
            ],
            'resolved_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => '해결 시간',
            ],
            'fix_applied' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => '적용된 수정 사항',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('log_date');
        $this->forge->addKey('error_level');
        $this->forge->addKey('user_id');
        $this->forge->addKey('error_hash');
        $this->forge->addKey(['company_cd', 'branch_cd']);
        $this->forge->addKey('is_resolved');
        $this->forge->createTable('log_analysis', true);

        // error_fix_history 테이블
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'error_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'comment' => '관련 오류 ID',
            ],
            'fixed_by' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
                'comment' => '수정한 사용자',
            ],
            'fixed_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'comment' => '수정 시간',
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => false,
                'comment' => '수정한 파일 경로',
            ],
            'original_code' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => '원본 코드',
            ],
            'fixed_code' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => '수정된 코드',
            ],
            'fix_description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => '수정 설명',
            ],
            'fix_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => '수정 유형 (AI, TEMPLATE, MANUAL)',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('error_id');
        $this->forge->addKey('fixed_by');
        $this->forge->createTable('error_fix_history', true);

        // log_statistics_summary 테이블
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'date' => [
                'type' => 'DATE',
                'null' => false,
                'comment' => '통계 날짜',
            ],
            'error_level' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
                'comment' => '오류 레벨',
            ],
            'count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => '발생 횟수',
            ],
            'unique_errors' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => '고유 오류 수',
            ],
            'affected_users' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => '영향받은 사용자 수',
            ],
            'resolved_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => '해결된 오류 수',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['date', 'error_level']);
        $this->forge->createTable('log_statistics_summary', true);

        // log_alert_settings 테이블
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'alert_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'comment' => '알림 이름',
            ],
            'error_level' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
                'comment' => '모니터링할 오류 레벨',
            ],
            'threshold_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'comment' => '임계값 (이상 발생시 알림)',
            ],
            'time_window' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 60,
                'comment' => '시간 윈도우 (분)',
            ],
            'alert_email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'comment' => '알림 이메일',
            ],
            'alert_sms' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'comment' => '알림 SMS 번호',
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => '활성화 여부',
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => '생성자',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('error_level');
        $this->forge->createTable('log_alert_settings', true);
    }

    public function down()
    {
        $this->forge->dropTable('log_alert_settings', true);
        $this->forge->dropTable('log_statistics_summary', true);
        $this->forge->dropTable('error_fix_history', true);
        $this->forge->dropTable('log_analysis', true);
    }
}