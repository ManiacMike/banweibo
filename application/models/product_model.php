<?php
/**
 * @author mike
 * 地区处理类
 * */
class Product_model extends Base_model
{
	private $tbData;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'product';
	}
	/**
	 * 根据tuid获取4个商品展示在people页面
	 * */
	function getFourProduct($tuid){
		return $this->getFiledValues("*",$this->tbData,"tuid='{$tuid}' limit 4");
	}
}