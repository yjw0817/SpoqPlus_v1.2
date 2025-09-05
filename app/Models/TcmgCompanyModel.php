<?php
namespace App\Models;

use CodeIgniter\Model;

class TcmgCompanyModel extends Model
{
    /**
     * CompanyList를 가져온다.
     * @param array $data
     * @return array
     */
    public function list_company()
    {
        $sql = "SELECT * FROM smgmt_mgmt_tbl";
        $query = $this->db->query($sql, );
        
        return $query->getResultArray();
    }
}
