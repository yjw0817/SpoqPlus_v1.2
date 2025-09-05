<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLockerTables extends Migration
{
    public function up()
    {
        // floors 테이블 생성
        $this->forge->addField([
            'floor_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'comp_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'bcoff_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'floor_nm' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'floor_ord' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'floor_img' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('floor_sno', true);
        $this->forge->createTable('floors');

        // zones 테이블 생성
        $this->forge->addField([
            'zone_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'floor_sno' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'zone_nm' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'zone_gendr' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'comment' => 'M:남성, F:여성, A:혼용',
            ],
            'zone_coords' => [
                'type' => 'TEXT',
                'comment' => 'JSON format coordinates',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('zone_sno', true);
        $this->forge->addForeignKey('floor_sno', 'floors', 'floor_sno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('zones');

        // locker_groups 테이블 생성
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
                'constraint' => 100,
            ],
            'rows' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'cols' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'levels' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'position_x' => [
                'type' => 'FLOAT',
            ],
            'position_y' => [
                'type' => 'FLOAT',
            ],
            'locker_width' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'cm 단위',
            ],
            'locker_height' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'cm 단위',
            ],
            'locker_depth' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'cm 단위',
            ],
            'level_height' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'cm 단위',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('group_sno', true);
        $this->forge->addForeignKey('zone_sno', 'zones', 'zone_sno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('locker_groups');

        // lockers 테이블 생성
        $this->forge->addField([
            'locker_sno' => [
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
            'locker_no' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'row_idx' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'col_idx' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'level_idx' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'default' => 'A',
                'comment' => 'A:사용가능, U:사용중, D:사용불가',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('locker_sno', true);
        $this->forge->addForeignKey('group_sno', 'locker_groups', 'group_sno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lockers');
    }

    public function down()
    {
        // 테이블 삭제 (역순)
        $this->forge->dropTable('lockers');
        $this->forge->dropTable('locker_groups');
        $this->forge->dropTable('zones');
        $this->forge->dropTable('floors');
    }
} 