<?php
namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    /**
     * 지점의 MenuList를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_menu(array $data)
    {
        // 기본 SQL 정의
        $sql = "/* getMenuTreeList - 메뉴트리 리스트 조회 */
                    WITH RECURSIVE menu_query AS (
                        SELECT A.cd_menu
                            , A.nm_menu
                            , A.par_cd_menu
                            , CONVERT(A.menu_sort, CHAR(255)) AS sort
                        FROM tcmg_menu A";
        // compCd가 있는경우
        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $sql .= " INNER JOIN tcmg_bcoff_menu D ON A.cd_menu = D.cd_menu AND (D.comp_cd = :comp_cd: )";
        }
        $sql .=        " WHERE A.menu_level = 1
                        AND IFNULL(A.delete_yn, 'N') != 'Y'";

        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['useFor']) && $data['useFor'] !== 'ALL') {
            $sql .= " AND A.use_for = :use_for:";
        }
        

        $sql .= " UNION ALL
                        SELECT B.cd_menu
                            , B.nm_menu
                            , B.par_cd_menu
                            , CONVERT(CONCAT(CONCAT(CONVERT(C.sort, NCHAR), ' > '), CONVERT(B.menu_sort, CHAR(255))), CHAR(255)) sort
                        FROM tcmg_menu B";
        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $sql .= " INNER JOIN tcmg_bcoff_menu D ON B.cd_menu = D.cd_menu AND (D.comp_cd = :comp_cd: )";
        }
        $sql .=       " JOIN menu_query C ON B.par_cd_menu = C.cd_menu 
                        WHERE IFNULL(B.delete_yn, 'N') != 'Y'";

        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['useFor']) && $data['useFor'] !== 'ALL') {
            $sql .= " AND B.use_for = :use_for:";
        }

        $sql .= ") 
                    SELECT 
                        A.cd_menu AS id
                        , A.nm_menu AS text
                        , CASE WHEN IFNULL(A.par_cd_menu, '') = '' THEN 'root' ELSE A.par_cd_menu END AS parent
                        , CASE WHEN IFNULL(B.childCnt, 0) = 0 THEN 'file' ELSE 'root' END AS type
                    FROM menu_query A
                    LEFT JOIN (
                        SELECT 
                            A.par_cd_menu
                            , count(*) AS childCnt
                        FROM tcmg_menu A";
        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $sql .= " INNER JOIN tcmg_bcoff_menu D ON A.cd_menu = D.cd_menu AND (D.comp_cd = :comp_cd: )";
        }
        $sql .=       " WHERE A.par_cd_menu IS NOT NULL
                        AND IFNULL(A.delete_yn, 'N') != 'Y'";

        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['useFor']) && $data['useFor'] !== 'ALL') {
            $sql .= " AND A.use_for = :use_for:";
        }

        $sql .= " GROUP BY A.par_cd_menu
                    ) B ON B.par_cd_menu = A.cd_menu
                    ORDER BY sort";

        // 바인딩할 파라미터 배열 생성
        $queryParams = [];

        if (isset($data['useFor']) && $data['useFor'] !== 'ALL') {
            $queryParams['use_for'] = $data['useFor'];
        }
        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $queryParams['comp_cd'] = $data['compCd'];
        }

        // SQL 실행
        $query = $this->db->query($sql, $queryParams);

        return $query->getResultArray();
    }
    
    /**
     * 회사의 MenuList를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_comp_menu(array $data)
    {
        // 기본 SQL 정의
        $sql = "/* getMenuTreeList - 메뉴트리 리스트 조회 */
                    WITH RECURSIVE menu_query AS (
                        SELECT A.cd_menu
                            , A.nm_menu
                            , A.par_cd_menu
                            , CONVERT(A.menu_sort, CHAR(255)) AS sort
                        FROM tcmg_menu A";
        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $sql .= " INNER JOIN tcmg_smgmt_menu D ON A.cd_menu = D.cd_menu AND (D.comp_cd = :comp_cd: OR D.comp_cd IS NULL)";
        }
        $sql .=        " WHERE A.menu_level = 1
                        AND IFNULL(A.delete_yn, 'N') != 'Y'";

       
        

        $sql .= " UNION ALL
                        SELECT B.cd_menu
                            , B.nm_menu
                            , B.par_cd_menu
                            , CONVERT(CONCAT(CONCAT(CONVERT(C.sort, NCHAR), ' > '), CONVERT(B.menu_sort, CHAR(255))), CHAR(255)) sort
                        FROM tcmg_menu B";
        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $sql .= " INNER JOIN tcmg_smgmt_menu D ON B.cd_menu = D.cd_menu AND (D.comp_cd = :comp_cd: OR D.comp_cd IS NULL)";
        }
        $sql .=       " JOIN menu_query C ON B.par_cd_menu = C.cd_menu 
                        WHERE IFNULL(B.delete_yn, 'N') != 'Y'";


        $sql .= ") 
                    SELECT 
                        A.cd_menu AS id
                        , A.nm_menu AS text
                        , CASE WHEN IFNULL(A.par_cd_menu, '') = '' THEN 'root' ELSE A.par_cd_menu END AS parent
                        , CASE WHEN IFNULL(B.childCnt, 0) = 0 THEN 'file' ELSE 'root' END AS type
                    FROM menu_query A
                    LEFT JOIN (
                        SELECT 
                            A.par_cd_menu
                            , count(*) AS childCnt
                        FROM tcmg_menu A";
        // use_for가 "ALL"이 아닐 경우 조건 추가
        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $sql .= " INNER JOIN tcmg_smgmt_menu D ON A.cd_menu = D.cd_menu AND (D.comp_cd = :comp_cd: OR D.comp_cd IS NULL)";
        }
        $sql .=       " WHERE A.par_cd_menu IS NOT NULL
                        AND IFNULL(A.delete_yn, 'N') != 'Y'";



        $sql .= " GROUP BY A.par_cd_menu
                    ) B ON B.par_cd_menu = A.cd_menu
                    ORDER BY sort";

        // 바인딩할 파라미터 배열 생성
        $queryParams = [];

        if (isset($data['compCd']) && $data['compCd'] !== '') {
            $queryParams['comp_cd'] = $data['compCd'];
        }

        // SQL 실행
        $query = $this->db->query($sql, $queryParams);

        return $query->getResultArray();
    }
    
    /**
     * 해당 Role의 메뉴리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function getRoleMenuList(array $data)
    {
        return $this->db->table('tcmg_role_menu')
                    ->select('seq_role, cd_menu, cd_company')
                    ->where('seq_role', $data['seq_role'])
                    ->orderBy('cd_menu')
                    ->get()
                    ->getResultArray();
    }

    /**
     * 해당 회사의 메뉴 리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function getCompMenuList(array $data)
    {
        return $this->db->table('tcmg_menu A')
            ->select('A.cd_menu, A.nm_menu, A.par_cd_menu, A.use_for, D.comp_cd')
            ->join('tcmg_smgmt_menu D', 'A.cd_menu = D.cd_menu AND D.comp_cd = ' . $this->db->escape($data['compCd']), 'inner')
            ->where('IFNULL(A.delete_yn, "N") !=', 'Y') // 삭제되지 않은 메뉴만 조회
            ->orderBy('A.cd_menu')
            ->get()
            ->getResultArray();
    }

    /**
     * 해당 지점점의 메뉴리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function getBcoffMenuList(array $data)
    {
        if (!isset($data['bcoffCd']) || $data['bcoffCd'] == '') {
            return [];
        }
        return $this->db->table('tcmg_menu A')
            ->select('A.cd_menu, A.nm_menu, A.par_cd_menu, A.use_for, D.comp_cd')
            ->join('tcmg_smgmt_menu B', 'A.cd_menu = B.cd_menu AND B.comp_cd = ' . $this->db->escape($data['compCd']), 'inner')
            ->join('tcmg_bcoff_menu D', 'A.cd_menu = D.cd_menu AND D.bcoff_cd = ' . $this->db->escape($data['bcoffCd']), 'inner')
            ->where('IFNULL(A.delete_yn, "N") !=', 'Y') // 삭제되지 않은 메뉴만 조회
            ->orderBy('A.cd_menu')
            ->get()
            ->getResultArray();
    }

    /**
     * 해당 지점점의 메뉴리스트를 가져온다.
     * @param array $data
     * @return array
     */
    public function getBcoffMenuList2(array $data)
    {
        if (!isset($data['bcoffCd']) || $data['bcoffCd'] == '') {
            return [];
        }
        return $this->db->table('tcmg_menu A')
            ->select("A.cd_menu AS id, A.nm_menu AS text, A.par_cd_menu AS parent, CASE WHEN A.par_cd_menu IS NULL THEN 'root' ELSE 'file' END AS type")
            ->join('tcmg_smgmt_menu B', 'A.cd_menu = B.cd_menu AND B.comp_cd = ' . $this->db->escape($data['compCd']), 'inner')
            ->join('tcmg_bcoff_menu D', 'A.cd_menu = D.cd_menu AND D.bcoff_cd = ' . $this->db->escape($data['bcoffCd']), 'inner')
            ->where('IFNULL(A.delete_yn, "N") !=', 'Y') // 삭제되지 않은 메뉴만 조회
            ->orderBy('A.cd_menu')
            ->get()
            ->getResultArray();
    }

    /**
     * 사용자의 메뉴리스트를 가져온다.(지점 사용자 권한별)
     * @param array $data
     * @return array
     */
    public function list_menu_of_user(array $data)
    {
        $sql = "/* getMenuList - 메뉴 정보 조회 */
                    WITH RECURSIVE menu_query
                        AS (SELECT A.cd_menu
                                , A.nm_menu
                                , A.module
                                , A.par_cd_menu
                                , P.nm_menu AS par_nm_menu
                                , A.menu_sort
                                , A.menu_level
                                , A.use_yn
                                , A.use_for
                                , A.url_path
                                , A.icon
                                , A.color
                                , A.`desc`
                                , CONVERT(A.menu_sort, CHAR(255)) AS sort
                                , CONVERT(A.nm_menu, CHAR(255)) AS depth_fullname
                                , CONVERT(A.cd_menu, CHAR(255)) AS depth_cd
                            FROM   tcmg_menu A LEFT JOIN tcmg_menu P ON A.par_cd_menu = P.cd_menu";
                            
            if (isset($data['bcoff_mgmt_id']) && $data['bcoff_mgmt_id'] !== '' && $data['use_for'] != "MT" && $data['use_for'] != "MC") {
                $sql .= " INNER JOIN tcmg_bcoff_menu M ON A.cd_menu = M.cd_menu AND M.bcoff_cd = :bcoff_cd:";   //지점관리자 아이디가 있다면 지점관리자 임으로 지점의 모든메뉴 접근 가능
            } else if ($data['use_for'] != "MT" && $data['use_for'] != "MC") 
            {
                $sql .= " INNER JOIN tcmg_role_menu M ON A.cd_menu = M.cd_menu";
            }                   
                            
            $sql .=       " WHERE  A.menu_level = 1
                            AND IFNULL(A.delete_yn, 'N') != 'Y'
                            AND IFNULL(A.use_yn, 'Y') != 'N'
                            AND A.use_for = :use_for:";
            if (!isset($data['bcoff_mgmt_id']) && $data['bcoff_mgmt_id'] !== '' && $data['use_for'] != "MT" && $data['use_for'] != "MC") 
            {
                $sql .=    " AND M.seq_role = :seq_role:";
            }
                                        
            $sql .="        UNION ALL
                            SELECT 
                                    B.cd_menu
                                , B.nm_menu
                                , B.module
                                , B.par_cd_menu
                                , P.nm_menu AS par_nm_menu
                                , B.menu_sort
                                , B.menu_level
                                , B.use_yn
                                , B.use_for
                                , B.url_path
                                , B.icon
                                , B.color
                                , B.`desc`
                                , CONVERT( CONCAT(CONCAT(CONVERT( C.sort, NCHAR), ' > '), CONVERT( B.menu_sort, CHAR(255))), CHAR(255)) sort
                                , CONVERT( CONCAT(CONCAT(CONVERT( C.depth_fullname, NCHAR),' > '), CONVERT( B.nm_menu, CHAR(255))), CHAR(255)) depth_fullname
                                ,	CONVERT( CONCAT(CONCAT(CONVERT( C.depth_cd, NCHAR),'|'), CONVERT( B.cd_menu, CHAR(255))), CHAR(255)) depth_cd
                            FROM   tcmg_menu B LEFT JOIN tcmg_menu P ON B.par_cd_menu = P.cd_menu";
            if (isset($data['bcoff_mgmt_id']) && $data['bcoff_mgmt_id'] !== '' && $data['use_for'] != 'MT' && $data['use_for'] != 'MC') {
                $sql .= " INNER JOIN tcmg_bcoff_menu M ON M.cd_menu = B.cd_menu AND M.bcoff_cd = :bcoff_cd:"; 
            } else if($data['use_for'] != 'MT' && $data['use_for'] != 'MC')
            {
                $sql .= " INNER JOIN tcmg_role_menu M ON B.cd_menu = M.cd_menu";
            }                   
                            
            $sql .=       "  , menu_query C
                            WHERE  B.par_cd_menu = C.cd_menu 
                            AND IFNULL(B.delete_yn, 'N') != 'Y'
                            AND IFNULL(B.use_yn, 'Y') != 'N'
                            AND B.use_for = :use_for:";
            if (!isset($data['bcoff_mgmt_id']) && $data['bcoff_mgmt_id'] !== '' && $data['use_for'] != "MT" && $data['use_for'] != "MC")  {
                $sql .=    " AND M.seq_role = :seq_role:";
            }

            $sql .=" )
                    
                    SELECT 
                        A.cd_menu
                        , A.nm_menu
                        , A.module
                        , A.par_cd_menu
                        , A.par_nm_menu
                        , A.menu_sort
                        , A.menu_level
                        , A.use_yn
                        , A.use_for
                        , A.url_path
                        , A.icon
                        , A.color
                        , A.`desc`
                        , A.sort
                        , A.depth_fullname
                        , A.depth_cd
                        , SUBSTRING_INDEX(A.depth_cd, '|', '1') AS top_cd_menu
                        , IFNULL(B.childCnt, 0) AS child_cnt
                    FROM   menu_query A 
                    LEFT JOIN (
                        SELECT 
                            A.par_cd_menu
                            , count(*) AS childCnt
                        FROM tcmg_menu A";
                            
            if (isset($data['bcoff_mgmt_id']) && $data['bcoff_mgmt_id'] !== '' && $data['use_for'] != 'MT' && $data['use_for'] != 'MC') {
                $sql .= " INNER JOIN tcmg_bcoff_menu M ON A.cd_menu = M.cd_menu AND M.bcoff_cd = :bcoff_cd:"; 
            } else if($data['use_for'] != 'MT' && $data['use_for'] != 'MC')
            {
                $sql .= " INNER JOIN tcmg_role_menu M ON A.cd_menu = M.cd_menu";
            }                   
                            
            $sql .=       " WHERE 
                            IFNULL(A.use_yn, 'Y') != 'N'
                            AND IFNULL(A.delete_yn, 'N') != 'Y'
                            AND A.use_for = :use_for:
                            AND A.par_cd_menu IS NOT NULL";
            if (!isset($data['bcoff_mgmt_id']) && $data['bcoff_mgmt_id'] !== '' && $data['use_for'] != 'MT' && $data['use_for'] != 'MC') {
                $sql .=    " AND M.seq_role = :seq_role:";
            }                
            $sql .="  GROUP BY A.par_cd_menu
                    ) B ON B.par_cd_menu = A.cd_menu
                    ORDER  BY sort
                ";
                
       
        
        // 바인딩할 파라미터 배열 생성
        $queryParams = [];
        if (isset($data['bcoff_mgmt_id']) && $data['bcoff_mgmt_id'] !== '') {
            $queryParams['bcoff_cd'] = $data['bcoff_cd'];
            $queryParams['use_for'] = $data['use_for'];
        } else if($data['use_for'] != "MT" && $data['use_for'] != "MC") 
        {
            $queryParams['use_for'] = $data['use_for'];
            $queryParams['seq_role'] = $data['seq_role'];
        } else
        {
            $queryParams['use_for'] = $data['use_for'];
        }


        // SQL 실행
        $query = $this->db->query($sql, $queryParams);

        return $query->getResultArray();
    }



    /**
     * 로그인 별 메뉴리스트를 가져온다. (프로그램 등록관리에서 사용)
     * @param array $data
     * @return array
     */
    public function list_menu_by_login(array $data)
    {
        $sql = "/* getMenuList - 메뉴 정보 조회 */
                    WITH RECURSIVE menu_query
                        AS (SELECT A.cd_menu
                                , A.nm_menu
                                , A.module
                                , A.par_cd_menu
                                , P.nm_menu AS par_nm_menu
                                , A.menu_sort
                                , A.menu_level
                                , A.use_yn
                                , A.use_for
                                , A.url_path
                                , A.icon
                                , A.color
                                , A.`desc`
                                , CONVERT(A.menu_sort, CHAR(255)) AS sort
                                , CONVERT(A.nm_menu, CHAR(255)) AS depth_fullname
                                , CONVERT(A.cd_menu, CHAR(255)) AS depth_cd
                            FROM   tcmg_menu A LEFT JOIN tcmg_menu P ON A.par_cd_menu = P.cd_menu
                            WHERE  A.menu_level = 1
                            AND IFNULL(A.delete_yn, 'N') != 'Y'
                            AND IFNULL(A.use_yn, 'Y') != 'N'
                            AND A.use_for = :use_for:
                            UNION ALL
                            SELECT 
                                    B.cd_menu
                                , B.nm_menu
                                , B.module
                                , B.par_cd_menu
                                , P.nm_menu AS par_nm_menu
                                , B.menu_sort
                                , B.menu_level
                                , B.use_yn
                                , B.use_for
                                , B.url_path
                                , B.icon
                                , B.color
                                , B.`desc`
                                , CONVERT( CONCAT(CONCAT(CONVERT( C.sort, NCHAR), ' > '), CONVERT( B.menu_sort, CHAR(255))), CHAR(255)) sort
                                , CONVERT( CONCAT(CONCAT(CONVERT( C.depth_fullname, NCHAR),' > '), CONVERT( B.nm_menu, CHAR(255))), CHAR(255)) depth_fullname
                                ,	CONVERT( CONCAT(CONCAT(CONVERT( C.depth_cd, NCHAR),'|'), CONVERT( B.cd_menu, CHAR(255))), CHAR(255)) depth_cd
                            FROM   tcmg_menu B LEFT JOIN tcmg_menu P ON B.par_cd_menu = P.cd_menu,
                                    menu_query C
                            WHERE  B.par_cd_menu = C.cd_menu 
                            AND IFNULL(B.delete_yn, 'N') != 'Y'
                            AND IFNULL(B.use_yn, 'Y') != 'N'
                            AND B.use_for = :use_for:)
                    
                    SELECT 
                        A.cd_menu
                        , A.nm_menu
                        , A.module
                        , A.par_cd_menu
                        , A.par_nm_menu
                        , A.menu_sort
                        , A.menu_level
                        , A.use_yn
                        , A.use_for
                        , A.url_path
                        , A.icon
                        , A.color
                        , A.`desc`
                        , A.sort
                        , A.depth_fullname
                        , A.depth_cd
                        , SUBSTRING_INDEX(A.depth_cd, '|', '1') AS top_cd_menu
                        , IFNULL(B.childCnt, 0) AS child_cnt
                    FROM   menu_query A
                    LEFT JOIN (
                        SELECT 
                            A.par_cd_menu
                            , count(*) AS childCnt
                        FROM tcmg_menu A
                        WHERE 
                            IFNULL(A.use_yn, 'Y') != 'N'
                            AND IFNULL(A.delete_yn, 'N') != 'Y'
                            AND A.use_for = :use_for:
                            AND A.par_cd_menu IS NOT NULL
                        GROUP BY A.par_cd_menu
                    ) B ON B.par_cd_menu = A.cd_menu
                    
                    ORDER  BY sort 	
                ";
        $query = $this->db->query($sql, [
            'use_for'           => $data['use_for']
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }

    /**
     * Menu정보를 불러온다.
     * @param array $data
     * @return array
     */
    public function ajax_getMenuInfo(array $data)
    {
        $sql = "/* getMenuInfo 메뉴 정보 조회  */
                SELECT A.cd_company
                    , A.cd_menu
                    , A.nm_menu
                    , A.module
                    , A.par_cd_menu
                    , IFNULL(B.nm_menu, 'ROOT')  AS par_nm_menu
                    , A.menu_sort
                    , A.menu_level
                    , A.use_yn
                    , A.use_for
                    , A.url_path
                    , A.icon
                    , A.color
                    , A.`desc`
                FROM tcmg_menu A
                LEFT JOIN tcmg_menu B ON B.cd_menu = A.par_cd_menu
                WHERE A.cd_menu = :cd_menu:
                ";
        $query = $this->db->query($sql, [
            'cd_menu' 			=> $data
        ]);
        
        array_push($data,$query);
        return $query->getResultArray();
    }

    /**
     * Menu를 저장 (업데이트 또는 신규 저장).
     * @param array $data
     * @return int
     */
    public function saveMenu($data)
    {
        $menuTable = $this->db->table('tcmg_menu');

        // 2️⃣ 삽입/업데이트할 데이터 설정
        $menuData = [
            'cd_menu'    => $data['cdMenu'],
            'nm_menu'    => $data['nmMenu'],
            'module'     => $data['module'],
            'url_path'   => $data['urlPath'],
            'menu_sort'  => $data['menuSort'],
            'menu_level' => $data['menuLevel'],
            'icon'       => $data['icon'],
            'color'      => $data['color'],
            'desc'       => $data['desc'],
            'use_yn'     => $data['useYn'],
            'use_for'     => $data['useFor'],
        ];

        if (!empty($data['parCdMenu'])) {
            $menuData['par_cd_menu'] = $data['parCdMenu'];
        }
        
        if($data['type'] == "I")  //신규시
        {
            // 1️⃣ 해당 메뉴가 존재하는지 확인
            $existingMenu = $menuTable->where('cd_menu', $data['cdMenu'])
            ->get()
            ->getRowArray(); // 기존 데이터 조회
            if($existingMenu)
            {
                return -1;  // 중복 아이디
            } else
            {
                // 4️⃣ 기존 데이터가 없으면 새로 삽입
                $menuTable->insert($menuData);
                return $this->db->affectedRows(); // 삽입된 행 개수 반환
            }
            
        }  else{
            $menuTable->where('cd_menu', $data['cdMenu'])
                    ->update($menuData);
        }

        return $this->db->affectedRows() > 0 ? 1 : 0; // 업데이트 성공: 1, 변경 없음: 0
    }

    public function deleteMenu($cdMenu)
    {
        $db = db_connect(); // DB 연결

        // 하위 메뉴 존재 여부 확인
        $childMenu = $db->table('tcmg_menu')
            ->where('par_cd_menu', $cdMenu)
            ->countAllResults();

        if ($childMenu > 0) {
            return -9; // 하위 메뉴가 있어서 삭제 불가
        }

        // 메뉴 삭제 실행
        $db->table('tcmg_menu')
            ->where('cd_menu', $cdMenu)
            ->delete();

        return $db->affectedRows(); // 삭제된 행 수 반환
    }
}