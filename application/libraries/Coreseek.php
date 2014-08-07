<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// --------------------------------------------------------------------------
// File name   : test_coreseek.php
// Description : coreseek中文全文检索系统测试程序
// Requirement : PHP5 (http://www.php.net)
//
// Copyright(C), HonestQiao, 2011, All Rights Reserved.
//
// Author: HonestQiao (honestqiao@gmail.com)
//
// 最新使用文档，请查看：http://www.coreseek.cn/products/products-install/
//
// --------------------------------------------------------------------------
ini_set("display_errors",1);
require ( APPPATH.'libraries/sphinxapi.php' );
class CI_Coreseek{
	private $pageSize;
	private $cl;
	 function __construct() {
		$this->cl = new SphinxClient ();
		$this->cl->SetServer ( '127.0.0.1', 9312);
		$this->cl->SetConnectTimeout ( 3 );
		$this->cl->SetArrayResult ( true );
		$this->cl->SetMatchMode ( SPH_MATCH_ANY);
		//$this->cl->SetSortMode ( SPH_SORT_ATTR_DESC, "follower_count_b" );
		$this->pageSize = 20;
	 }
	 function result($keyword,$page)
	 {
	 	$this->cl->SetLimits(($page-1)*$this->pageSize,$this->pageSize);
		$res = $this->cl->Query ($keyword, "*" );
		return $this->render($res,$page);
	 }
	private function render($res,$page)
	{
		$new = array("total"=>$res['total'],"page"=>$page,);
		if($res['matches'])
		{
			foreach ($res['matches'] as $one)
			{
				$new ['ids'][] = $one['id'];
			}
		}
		if($res['total'] - ($page*$this->pageSize) >0 )
			$new['next'] = true;
		else
			$new['next'] = false;
		return $new;
	}
	
}