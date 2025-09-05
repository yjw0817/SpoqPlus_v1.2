<?php
namespace App\Libraries;


class Ama_board {
	
	private $returnVal;
	private $per_page = 20;
	
	public function __construct($initVar) {
		
		$uri = service('uri');
		
		$setPostGet = array();
		$postVar = $initVar['post'];
		$getVar = $initVar['get'];
		
		//$uri->setQueryArray(['foo' => 'bar', 'foo2' => 'bar333']); // POST로 받으면 이부분의 값을 셋팅한다.
		$uri->setQueryArray($postVar); // POST로 받으면 이부분의 값을 셋팅한다.
		
		if ( count($postVar) > 0 ) :
			foreach ($postVar as $key => $value):
				$setPostGet[$key] = $value;
			endforeach;
		endif;
		
		if ( count($getVar) > 0 ) :
			foreach ($getVar as $key => $value):
				$setPostGet[$key] = $value;
			endforeach;
		endif;
		
		if ( !isset($setPostGet['page']) ) $setPostGet['page'] =1;
		if ($setPostGet['page'] < 1) $setPostGet['page'] =1;
		
		$setPostGet['limit_s'] = ( $setPostGet['page'] - 1 ) * $this->per_page;
		$setPostGet['limit_e'] = $this->per_page;
		
		$this->returnVal = $setPostGet;
	}
	
	public function getVal()
	{
		$dup_returnVal = $this->returnVal;
		
		if ( isset($dup_returnVal['pager']) )
		{
			unset($dup_returnVal['pager']);
		}
		
		$dup_returnVal['sCount'] = ( $this->returnVal['page'] - 1 ) * $this->returnVal['limit_e'];
		
		return $dup_returnVal;
	}
	
	public function getPager($totalCount = 0)
	{
		$pager = service('pager');
		$dup_returnVal = $this->returnVal;
		
		if ($totalCount > $this->per_page) :
			$returnPager = $pager->makeLinks($dup_returnVal['page'], $this->per_page, $totalCount ,'ama_page');
		else:
			$returnPager = '';
		endif;
		
		return $returnPager;
	}
}