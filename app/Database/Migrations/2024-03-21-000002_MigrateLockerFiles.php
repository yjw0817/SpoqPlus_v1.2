<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateLockerFiles extends Migration
{
    public function up()
    {
        // 1. comp_cd와 bcoff_cd 컬럼이 없는 경우 추가
        $fields = $this->db->getFieldNames('tb_locker_floor');
        if (!in_array('comp_cd', $fields)) {
            $this->forge->addColumn('tb_locker_floor', [
                'comp_cd' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                    'after' => 'floor_sno'
                ]
            ]);
        }
        if (!in_array('bcoff_cd', $fields)) {
            $this->forge->addColumn('tb_locker_floor', [
                'bcoff_cd' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                    'after' => 'comp_cd'
                ]
            ]);
        }

        // 2. 기존 데이터 업데이트
        $this->db->query("UPDATE tb_locker_floor SET comp_cd = '0001', bcoff_cd = '0001' WHERE comp_cd IS NULL OR comp_cd = ''");

        // 3. NULL 허용하지 않도록 변경
        $this->forge->modifyColumn('tb_locker_floor', [
            'comp_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'bcoff_cd' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ]
        ]);

        // 4. 기존 도면 데이터 가져오기
        $floors = $this->db->table('tb_locker_floor')->get()->getResultArray();
        
        foreach ($floors as $floor) {
            // 5. 새로운 디렉토리 생성
            $old_path = ROOTPATH . 'public/uploads/floors/' . $floor['floor_img'];
            $new_dir = ROOTPATH . 'public/uploads/floors/' . $floor['comp_cd'] . '/' . $floor['bcoff_cd'];
            $new_path = $new_dir . '/' . $floor['floor_img'];
            
            // 6. 디렉토리가 없으면 생성
            if (!is_dir($new_dir)) {
                mkdir($new_dir, 0777, true);
            }
            
            // 7. 파일 이동 (기존 파일이 있는 경우에만)
            if (file_exists($old_path)) {
                rename($old_path, $new_path);
            }
        }
    }

    public function down()
    {
        // 1. 기존 도면 데이터 가져오기
        $floors = $this->db->table('tb_locker_floor')->get()->getResultArray();
        
        foreach ($floors as $floor) {
            // 2. 파일 원래 위치로 이동
            $new_path = ROOTPATH . 'public/uploads/floors/' . $floor['comp_cd'] . '/' . $floor['bcoff_cd'] . '/' . $floor['floor_img'];
            $old_path = ROOTPATH . 'public/uploads/floors/' . $floor['floor_img'];
            
            if (file_exists($new_path)) {
                // 원래 디렉토리가 없으면 생성
                $old_dir = dirname($old_path);
                if (!is_dir($old_dir)) {
                    mkdir($old_dir, 0777, true);
                }
                rename($new_path, $old_path);
            }
            
            // 3. 빈 디렉토리 삭제
            $company_dir = ROOTPATH . 'public/uploads/floors/' . $floor['comp_cd'];
            if (is_dir($company_dir)) {
                $branch_dir = $company_dir . '/' . $floor['bcoff_cd'];
                if (is_dir($branch_dir) && count(scandir($branch_dir)) <= 2) {
                    rmdir($branch_dir);
                }
                if (count(scandir($company_dir)) <= 2) {
                    rmdir($company_dir);
                }
            }
        }

        // 4. comp_cd와 bcoff_cd 컬럼 삭제
        $this->forge->dropColumn('tb_locker_floor', ['comp_cd', 'bcoff_cd']);
    }
} 