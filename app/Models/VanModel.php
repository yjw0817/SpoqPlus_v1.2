<?php
namespace App\Models;

use CodeIgniter\Model;

class VanModel extends Model
{
	
    /**
	 * 직접 결제 van insert
	 * @param array $data
	 */
	public function insert_van_direct_hist(array $data)
	{
		$sql = "INSERT van_direct_hist SET
					PAYMT_VAN_SNO 		= :paymt_van_sno:
                    ,SELL_EVENT_SNO 	= :sell_event_sno:
                    ,SEND_EVENT_SNO 	= :send_event_sno:
                    ,BUY_EVENT_SNO 	    = :buy_event_sno:
					,COMP_CD			= :comp_cd:
					,BCOFF_CD		    = :bcoff_cd:
                    ,MEM_ID		        = :mem_id:
					,APPNO_SNO		    = :appno_sno:
					,APPNO		        = :appno:
                    ,PAYMT_AMT          = :paymt_amt:
					,PAYMT_STAT		    = :paymt_stat:
					,PAYMT_DATE			= :paymt_date:
					,REFUND_DATE		= :refund_date:
					,CRE_ID			    = :cre_id:
					,CRE_DATETM		    = :cre_datetm:
					,MOD_ID			    = :mod_id:
					,MOD_DATETM		    = :mod_datetm:
				";
		$query = $this->db->query($sql, [
				'paymt_van_sno' 	=> $data['paymt_van_sno']
				,'sell_event_sno'	=> $data['sell_event_sno']
				,'send_event_sno'	=> $data['send_event_sno']
		        ,'buy_event_sno'	=> $data['buy_event_sno']
				,'comp_cd'			=> $data['comp_cd']
				,'bcoff_cd'			=> $data['bcoff_cd']
				,'mem_id'		    => $data['mem_id']
		        ,'appno_sno'		=> $data['appno_sno']
				,'appno'		    => $data['appno']
				,'paymt_amt'		=> $data['paymt_amt']
				,'paymt_stat'		=> $data['paymt_stat']
				,'paymt_date'	    => $data['paymt_date']
		        ,'refund_date'	    => $data['refund_date']
				,'cre_id'			=> $data['cre_id']
				,'cre_datetm'		=> $data['cre_datetm']
				,'mod_id'			=> $data['mod_id']
				,'mod_datetm'		=> $data['mod_datetm']
		]);
		
		array_push($data,$query);
		return $data;
	}
    
    
    
}