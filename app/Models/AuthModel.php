<?php
namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{

    public function createRoleForBcoff(array $data)
    {
        $db = \Config\Database::connect(); // DB 연결

        // 1️⃣ 기존 역할을 가져오기 위한 SELECT 쿼리
        $sql = "SELECT seq_role, ? as comp_cd, ? as bcoff_cd, nm_role, use_yn, delete_yn, ? as created_by, NOW() as created_dt
                FROM tcmg_role
                WHERE IFNULL(delete_yn, 'N') != 'Y'
                AND (bcoff_cd = '' OR bcoff_cd IS NULL)";

        // 2️⃣ 기존 역할 데이터 가져오기 (안전한 바인딩 적용)
        $result = $db->query($sql, [$data['comp_cd'], $data['bcoff_cd'], $data['user_id']])->getResultArray();

        if (empty($result)) {
            return 0; // 조회된 데이터가 없으면 종료
        }

        $insertedCount = 0; // 새로 추가된 행 개수 저장

        // 3️⃣ 조회된 데이터 한 줄씩 INSERT
        foreach ($result as $row) {
            $roleData = [
                'comp_cd'  => $row['comp_cd'],
                'bcoff_cd' => $row['bcoff_cd'],
                'nm_role'  => $row['nm_role'],
                'use_yn'   => $row['use_yn'],
                'delete_yn'=> $row['delete_yn'],
                'created_by' => $data['user_id'],
                'created_dt' => date('Y-m-d H:i:s') // PHP에서 현재 날짜시간 생성
            ];

            $db->table('tcmg_role')->insert($roleData);
            $newSeqRole = $db->insertID(); // 새로 생성된 seq_role 값

            if (!$newSeqRole) {
                continue; // 삽입 실패 시 다음 루프로 이동
            }

            // 4️⃣ 해당 seq_role에 연결된 tcmg_role_menu 데이터 조회
            $menuResult = $db->table('tcmg_role_menu')
                ->select("seq_role, cd_menu")
                ->where("seq_role", $row['seq_role'])
                ->get()
                ->getResultArray();

            // 5️⃣ tcmg_role_menu에 새로운 seq_role로 데이터 삽입
            foreach ($menuResult as $menuRow) {
                $menuRow['seq_role'] = $newSeqRole; // 새로운 seq_role 적용
                $menuRow['created_by'] = $data['user_id'];
                $menuRow['created_dt'] = date('Y-m-d H:i:s');

                $db->table('tcmg_role_menu')->insert($menuRow);
            }

            $insertedCount++; // 처리된 역할 개수 증가
        }

        return $insertedCount; // 복사된 역할 개수 반환
    }

    /*
     * 권한이 있는 사용자 리스트를 불러온다.
     */
    public function getEmployeeListHavingRole(array $data)
    {
        $builder = $this->db->table('mem_info_detl_tbl A');
        
        // SELECT 필드 지정
        $builder->select("A.MEM_SNO, A.MEM_ID, A.MEM_NM, A.MEM_TELNO, A.MEM_DV, A.TCHR_POSN, CASE WHEN IFNULL(C.BCOFF_MGMT_ID, '') = '' THEN 0 ELSE 1 END");
        $builder->join('tcmg_role_user B', 'A.MEM_SNO = B.mem_sno', 'left');
        $builder->join('bcoff_mgmt_tbl C', 'A.MEM_ID = C.BCOFF_MGMT_ID', 'left');
        $builder->where("A.use_yn", "Y");
        $builder->where("A.MEM_DV = 'T'");
        $builder->where("A.COMP_CD",  $data['comp_cd']);
        $builder->where("A.BCOFF_CD",  $data['bcoff_cd']);
        
        $builder->groupStart();
        $builder->where("IFNULL(B.seq_role, '')!=", '');
        $builder->orWhere("C.BCOFF_MGMT_ID !=", null);
        $builder->groupEnd();

        // 중복 제거 (필요 시)
        $builder->orderBy("CASE WHEN IFNULL(C.BCOFF_MGMT_ID, '') = '' THEN 1 ELSE 0 END", "", false);
        $builder->orderBy("A.MEM_NM", "ASC");

        // 쿼리 실행
        $query = $builder->get();
        $result = $query->getResultArray(); // 결과를 배열로 반환
        

        return $result;
    }

    /*
     * 권한이 없거가 권한과 관계없는 사용자 리스트를 불러온다.
     */
    public function getEmployeeList(array $data)
    {
        $builder = $this->db->table('mem_info_detl_tbl A');
        
        // SELECT 필드 지정
        $builder->select("A.MEM_SNO, A.MEM_ID, A.MEM_NM, A.MEM_TELNO, A.MEM_DV, A.TCHR_POSN");
        $builder->join('tcmg_role_user B', 'A.MEM_SNO = B.mem_sno', 'left');
        $builder->where("A.use_yn", "Y");
        $builder->where("A.MEM_DV = 'T'");
        $builder->where("A.COMP_CD",  $data['comp_cd']);
        $builder->where("A.BCOFF_CD",  $data['bcoff_cd']);
        $builder->groupStart();
        $builder->where("B.seq_role IS NULL");
        $builder->orWhere("B.seq_role !=", $data['seq_role']);
        $builder->groupEnd();

        // 중복 제거 (필요 시)
        $builder->orderBy("A.MEM_NM");

        // 쿼리 실행
        $query = $builder->get();
        $result = $query->getResultArray(); // 결과를 배열로 반환

        // PHP에서 `TCHR_POSN_NAME` 변환 및 검색 필터링
        $sDef = SpoqDef();
        $filteredResult = [];
        foreach ($result as $row) {
            $row['TCHR_POSN_NAME'] = isset($sDef['TCHR_POSN'][$row['TCHR_POSN']]) ? $sDef['TCHR_POSN'][$row['TCHR_POSN']] : '미지정';
        
            // PHP에서 `TCHR_POSN_NAME` 검색 처리
            if (!empty($data['strSearch'])) {
                if (
                    stripos($row['MEM_NM'], $data['strSearch']) !== false || 
                    stripos($row['MEM_TELNO'], $data['strSearch']) !== false ||
                    stripos($row['MEM_ID'], $data['strSearch']) !== false ||
                    stripos($row['TCHR_POSN_NAME'], $data['strSearch']) !== false
                ) {
                    $filteredResult[] = $row;
                }
            } else {
                $filteredResult[] = $row;
            }
        }

        return $filteredResult;
    }


    public function getRoleUserList(array $data) {
        $builder = $this->db->table('tcmg_role A');
    
        // INNER JOIN 추가
        $builder->join('tcmg_role_user B', 'A.seq_role = B.seq_role', 'inner');
        $builder->join('mem_info_detl_tbl C', 'B.MEM_SNO = C.MEM_SNO', 'inner');
    
        // SELECT 필드 지정
        $builder->select("
            A.nm_role, A.seq_role, C.MEM_NM, C.MEM_DV, C.MEM_SNO, C.MEM_TELNO, C.TCHR_POSN, C.MEM_ID 
        ");
    
        $builder->where("C.use_yn", "Y");
        // 필수 조건 추가 (seq_role)
        if (!empty($data['seqRole'])) {
            $builder->where("A.seq_role", $data['seqRole']);
        }
        
        // 필수 조건 추가 (seq_role)
        if (!empty($data['seq_role'])) {
            $builder->where("A.seq_role", $data['seq_role']);
        }
    
        // 삭제되지 않은 데이터 필터링
        $builder->where("IFNULL(A.delete_yn, 'N') !=", 'Y');
    
        // 역할 이름 LIKE 검색 (nmRole)
        if (!empty($data['nmRole'])) {
            $builder->like("A.nm_role", $data['nmRole']);
        }
    
        // 중복 제거 (필요 시)
        $builder->groupBy("A.seq_role, C.MEM_SNO");
    
        // 쿼리 실행
        $query = $builder->get();
        
        $result = $query->getResultArray(); // 결과를 배열로 반환
        $sDef = SpoqDef();
        $filteredResult = [];
        foreach ($result as $row) {
            $row['TCHR_POSN_NAME'] = isset($sDef['TCHR_POSN'][$row['TCHR_POSN']]) ? $sDef['TCHR_POSN'][$row['TCHR_POSN']] : '미지정';
        
            // PHP에서 `TCHR_POSN_NAME` 검색 처리
            if (!empty($data['strSearch'])) {
                if (
                    stripos($row['MEM_NM'], $data['strSearch']) !== false || 
                    stripos($row['MEM_TELNO'], $data['strSearch']) !== false ||
                    stripos($row['MEM_ID'], $data['strSearch']) !== false ||
                    stripos($row['TCHR_POSN_NAME'], $data['strSearch']) !== false
                ) {
                    $filteredResult[] = $row;
                }
            } else {
                $filteredResult[] = $row;
            }
        }

        return $filteredResult;
    }
    
    public function insertRoleToUser(array $data)
    {
        $builder = $this->db->table("tcmg_role_user");
        $builder->insert($data);
    }
    
    public function getRoleList(array $data) {
        $builder = $this->db->table('tcmg_role A'); // Query Builder 사용
        $builder->select("
            A.seq_role,
            A.comp_cd,
            A.nm_role,
            A.use_yn
        ");

        // 필수 조건 추가 (cdCompany)
        if (!empty($data['comp_cd'])) {
            $builder->where("A.comp_cd", $data['comp_cd']);
        }

        // 필수 조건 추가 (cdCompany)
        if (!empty($data['bcoff_cd'])) {
            $builder->where("A.bcoff_cd", $data['bcoff_cd']);
        }
    
        // 삭제되지 않은 데이터 필터링
        $builder->where("IFNULL(A.delete_yn, 'N') !=", 'Y');
    
        // 역할 이름 LIKE 검색 (nmRole)
        if (!empty($data['nmRole'])) {
            $builder->like("A.nm_role", $data['nmRole']);
        }
    
        $query = $builder->get();
        return $query->getResultArray(); // 결과를 배열로 반환
    }

    /**
     * 권한에 연결된 사용자를 삭제한다.
     */
    public function deleteUserListFromRole(array $data)
    {
        $db = \Config\Database::connect(); // DB 연결
        $builder = $db->table('tcmg_role_user'); // 🔥 별칭(A) 제거

        // 🔍 seq_role과 mem_sno 리스트가 있는지 확인
        if (empty($data['seq_role']) || empty($data['delList']) || !is_array($data['delList'])) {
            return [
                'status' => 'failed',
                'message' => '삭제할 데이터가 없습니다.'
            ];
        }

        // 🔥 `mem_sno` 값만 추출 (배열 속 배열에서 1차원 배열로 변환)
        $memSnoList = array_column($data['delList'], 'mem_sno');

        // 🔥 삭제 실행
        try {
            $builder->where('seq_role', $data['seq_role']);
            $builder->whereIn('mem_sno', $memSnoList);
            $result = $builder->delete();
            
            // 🔍 삭제된 행 개수 확인
            if ($db->affectedRows() > 0) {
                return [
                    'status' => 'success',
                    'message' => '권한-사용자 연결이 성공적으로 삭제되었습니다.',
                    'deleted_count' => $db->affectedRows()
                ];
            } else {
                return [
                    'status' => 'failed',
                    'message' => '삭제할 항목이 없거나 이미 삭제되었습니다.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => '삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            ];
        }
    }

    

    public function deleteRole(array $data) {
        $builder = $this->db->table('tcmg_role');
    
        // $builder->set('delete_yn', 'Y');
        // $builder->set('modify_by', $data["user_id"]);
        // $builder->set('modify_dt', date('Y-m-d H:i:s')); // 현재 시간 설정
    
        $builder->where('seq_role', $data["seq_role"]);
        return $builder->delete();
    }


    public function insertRole(array $data) {
        $builder = $this->db->table('tcmg_role');
    
        // 1. 중복된 권한명(nm_role) 존재 여부 확인
        $existingRole = $builder->select('seq_role')
                                // ->where('cd_company', $data['cdCompany']) // 동일 회사 내에서 중복 체크
                                ->where('nm_role', $data['nm_role'])
                                ->where('comp_cd', $data['comp_cd'])
                                ->where('bcoff_cd', $data['bcoff_cd'])
                                ->get()
                                ->getRowArray();
    
        if ($existingRole) {
            return [
                'status' => 'failed',
                'message' => '이미 존재하는 권한명입니다.'
            ];
        }
    
        // 2. 최신 seq_role을 가져와 +1 증가
        $subQuery = $this->db->table('tcmg_role')
                             ->selectMax('seq_role')
                             ->get()
                             ->getRowArray();
    
        $newSeqRole = ($subQuery['seq_role'] ?? 0) + 1;
    
        // 3. 데이터 삽입
        $insertData = [
            'seq_role'    => $newSeqRole,
            'comp_cd'     => $data['comp_cd'],
            'bcoff_cd'     => $data['bcoff_cd'],
            'nm_role'     => $data['nm_role'],
            'use_yn'      => $data['use_yn'],
            'delete_yn'   => 'N',
            'created_by'  => $data['user_id'],
            'created_dt'  => date('Y-m-d H:i:s') // NOW() 대체
        ];
    
        $result = $builder->insert($insertData);
    
        if ($result) {
            return [
                'status' => 'success',
                'message' => '권한이 성공적으로 추가되었습니다.'
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => '권한 추가 중 오류가 발생했습니다.'
            ];
        }
    }
    
    public function updateRole(array $data) {
        $builder = $this->db->table('tcmg_role');
    
        // 업데이트할 데이터 설정
        $updateData = [
            'nm_role'   => $data['nm_role'],
            'use_yn'    => $data['use_yn'],
            'modify_by' => $data['user_id'],
            'modify_dt' => date('Y-m-d H:i:s') // NOW() 대체
        ];
    
        // 조건 설정
        $builder->where('seq_role', $data['seq_role']);
        $builder->update($updateData);
        // 업데이트 실행
        return [
            'status' => 'success',
            'message' => '권한이 성공적으로 업데이트되었습니다.'
        ];
    }

    public function deleteBcoffMenu(array $data) {
        $builder = $this->db->table('tcmg_bcoff_menu');
    
        // 조건 설정 후 삭제 실행
        $result = $builder->where('bcoff_cd', $data["bcoffCd"])->delete();
    
        if ($result) {
            return [
                'status' => 'success',
                'message' => '권한-메뉴 연결이 성공적으로 삭제되었습니다.'
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => '삭제 중 오류가 발생했습니다.'
            ];
        }
    }
    

    public function insertBcoffMenu(array $data) {
        $builder = $this->db->table('tcmg_bcoff_menu');
    
        // 여러 개의 데이터를 한번에 삽입하기 위한 배열 생성
        $insertData = [];
        $bcoffMenuList = $data["bcoffMenuList"];
    
        foreach ($bcoffMenuList as $item) {
            $insertData[] = [
                'comp_cd'   => $data["compCd"],
                'bcoff_cd'   => $data["bcoffCd"],
                'cd_menu'    => $item, // 각각의 메뉴 코드
                // 'cd_company' => $data["cd_company"],
                'created_by' => $data["user_id"],
                'created_dt' => date('Y-m-d H:i:s') // NOW() 대체
            ];
        }
    
        // 데이터가 있을 경우만 삽입 실행
        if (!empty($insertData)) {
            $result = $builder->insertBatch($insertData);
    
            if ($result) {
                return [
                    'status' => 'success',
                    'message' => '권한-메뉴 연결이 성공적으로 등록되었습니다.'
                ];
            } else {
                return [
                    'status' => 'failed',
                    'message' => '등록 중 오류가 발생했습니다.'
                ];
            }
        }
    
        return [
            'status' => 'failed',
            'message' => '등록할 데이터가 없습니다.'
        ];
    }

    public function deleteRoleMenu(array $data) {
        $builder = $this->db->table('tcmg_role_menu');
    
        // 조건 설정 후 삭제 실행
        $result = $builder->where('seq_role', $data["seq_role"])->delete();
    
        if ($result) {
            return [
                'status' => 'success',
                'message' => '권한-메뉴 연결이 성공적으로 삭제되었습니다.'
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => '삭제 중 오류가 발생했습니다.'
            ];
        }
    }
    

    public function insertRoleMenu(array $data) {
        $builder = $this->db->table('tcmg_role_menu');
    
        // 여러 개의 데이터를 한번에 삽입하기 위한 배열 생성
        $insertData = [];
        $roleMenuList = $data["roleMenuList"];
    
        foreach ($roleMenuList as $item) {
            $insertData[] = [
                'seq_role'   => $data["seq_role"],
                'cd_menu'    => $item, // 각각의 메뉴 코드
                // 'cd_company' => $data["cd_company"],
                'created_by' => $data["user_id"],
                'created_dt' => date('Y-m-d H:i:s') // NOW() 대체
            ];
        }
    
        // 데이터가 있을 경우만 삽입 실행
        if (!empty($insertData)) {
            $result = $builder->insertBatch($insertData);
    
            if ($result) {
                return [
                    'status' => 'success',
                    'message' => '권한-메뉴 연결이 성공적으로 등록되었습니다.'
                ];
            } else {
                return [
                    'status' => 'failed',
                    'message' => '등록 중 오류가 발생했습니다.'
                ];
            }
        }
    
        return [
            'status' => 'failed',
            'message' => '등록할 데이터가 없습니다.'
        ];
    }
    
    public function deleteCompMenu(array $data) {
        $builder = $this->db->table('tcmg_smgmt_menu');
    
        // 조건 설정 후 삭제 실행
        $result = $builder->where('comp_cd', $data["compCd"])->delete();
    
        if ($result) {
            return [
                'status' => 'success',
                'message' => '권한-메뉴 연결이 성공적으로 삭제되었습니다.'
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => '삭제 중 오류가 발생했습니다.'
            ];
        }
    }
    

    public function insertCompMenu(array $data) {
        $builder = $this->db->table('tcmg_smgmt_menu');
    
        // 여러 개의 데이터를 한번에 삽입하기 위한 배열 생성
        $insertData = [];
        $roleMenuList = $data["compMenuList"];
    
        foreach ($roleMenuList as $item) {
            $insertData[] = [
                'comp_cd'   => $data["compCd"],
                'cd_menu'    => $item, // 각각의 메뉴 코드
                // 'cd_company' => $data["cd_company"],
                'created_by' => $data["user_id"],
                'created_dt' => date('Y-m-d H:i:s') // NOW() 대체
            ];
        }
    
        // 데이터가 있을 경우만 삽입 실행
        if (!empty($insertData)) {
            $result = $builder->insertBatch($insertData);
    
            if ($result) {
                return [
                    'status' => 'success',
                    'message' => '권한-메뉴 연결이 성공적으로 등록되었습니다.'
                ];
            } else {
                return [
                    'status' => 'failed',
                    'message' => '등록 중 오류가 발생했습니다.'
                ];
            }
        }
    
        return [
            'status' => 'failed',
            'message' => '등록할 데이터가 없습니다.'
        ];
    }
    
}