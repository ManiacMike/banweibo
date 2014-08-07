<?php
/**
 * @author mike
 * 后台数据处理类
 * */
class Taobaoke_model extends Base_model
{
	public $keyword;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'taobaoke';
		define ( 'PAGESIZE', 20 );
	}
	function getProduct()
	{
		$p = $this->pagination->cur_page;
		$p = $p == 0 ? 1 : $p;
		$offset = ($p - 1) * PAGESIZE;
		$where = $this->getWhere();
		$res = $this->getFiledValues ( "*", $this->tbData, "$where limit {$offset}," . PAGESIZE );
		foreach ($res as $key=>$one)
		{
		}
		return $res;
	}
	function getProductPageStr($module = "index")
	{
		$this->load->library ( 'pagination' );
		$config ['base_url'] = BASE_URL . "/taobaoke/$module/";
		if( $this->keyword )
		{
			$config ['base_url'] .=urlencode($this->keyword)."/";
		}else {
			$config ['base_url'] .="0/";
		}
		$config ['uri_segment'] = 4;
		$config ['total_rows'] = $this->getProductCount ();
		$config ['per_page'] = PAGESIZE;
		$this->pagination->initialize ( $config );
		$pageStr = $this->pagination->create_links ();
		return $pageStr;
	}
	function getProductCount(){
		$where = $this->getWhere();
		return $this->countRecord($this->tbData,$where);
	}
	protected function getWhere()
	{
		return $this->keyword?"name like '%{$this->keyword}%' || show_name like '%{$this->keyword}%' ||  FIND_IN_SET( '{$this->keyword}', sort )||  keyword like '%{$this->keyword}%' order by id ":"1 order by id ";
	}
}