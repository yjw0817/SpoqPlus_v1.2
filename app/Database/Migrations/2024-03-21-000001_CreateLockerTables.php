<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLockerTables extends Migration
{
    public function up()
    {
        // 도면 테이블
        $this->forge->addField([
            'floor_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'comp_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'bcoff_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'floor_nm' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'floor_img' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'floor_ord' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'default' => 0,
            ],
            'use_yn' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => 'Y',
            ],
            'cre_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'cre_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'mod_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'mod_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('floor_sno', true);
        $this->forge->createTable('tb_locker_floor');

        // 구역 테이블
        $this->forge->addField([
            'zone_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'comp_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'bcoff_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'floor_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'zone_nm' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'zone_coords' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'zone_gendr' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'comment' => 'M:남성전용, F:여성전용, A:혼용',
            ],
            'use_yn' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => 'Y',
            ],
            'cre_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'cre_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'mod_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'mod_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('zone_sno', true);
        $this->forge->addForeignKey('floor_sno', 'tb_locker_floor', 'floor_sno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_locker_zone');

        // 락커 그룹 테이블 (윗면 뷰)
        $this->forge->addField([
            'group_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'zone_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'group_nm' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'group_rows' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'comment' => '가로 칸수',
            ],
            'group_cols' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'comment' => '세로 칸수',
            ],
            'locker_width' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
                'comment' => '락커 가로 크기(mm)',
            ],
            'locker_depth' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
                'comment' => '락커 세로 크기(mm)',
            ],
            'group_coords' => [
                'type' => 'TEXT',
                'null' => false,
                'comment' => '도면상 좌표',
            ],
            'use_yn' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => 'Y',
            ],
            'cre_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'cre_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'mod_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'mod_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('group_sno', true);
        $this->forge->addForeignKey('zone_sno', 'tb_locker_zone', 'zone_sno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_locker_group');

        // 락커 정면도 테이블
        $this->forge->addField([
            'front_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'group_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'front_rows' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'comment' => '정면도 세로 칸수',
            ],
            'front_height' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => true,
                'comment' => '락커 높이(mm)',
            ],
            'use_yn' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => 'Y',
            ],
            'cre_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'cre_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'mod_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'mod_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('front_sno', true);
        $this->forge->addForeignKey('group_sno', 'tb_locker_group', 'group_sno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_locker_front');

        // 개별 락커 테이블
        $this->forge->addField([
            'locker_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'front_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'locker_no' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'locker_row' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'comment' => '가로 위치',
            ],
            'locker_col' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'comment' => '세로 위치',
            ],
            'locker_floor' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
                'comment' => '정면도 층수',
            ],
            'use_yn' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => 'Y',
            ],
            'cre_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'cre_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'mod_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'mod_datetm' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('locker_sno', true);
        $this->forge->addForeignKey('front_sno', 'tb_locker_front', 'front_sno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_locker');
    }

    public function down()
    {
        $this->forge->dropTable('tb_locker');
        $this->forge->dropTable('tb_locker_front');
        $this->forge->dropTable('tb_locker_group');
        $this->forge->dropTable('tb_locker_zone');
        $this->forge->dropTable('tb_locker_floor');
    }
} 