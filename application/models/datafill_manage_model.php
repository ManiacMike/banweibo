<?php
/**
 * @author mike
 * 搜索模型
 * */
class Datafill_manage_model extends Base_model
{
	function __construct()
	{
		parent::__construct ();
	}
	function add($id,$name)
	{
		$data = unserialize(file_get_contents(DATAFile));
		$data = $data?$data:array();
		if($this->checkExisit($id)==false)
		{
			$data[] = array("id"=>$id,"name"=>$name);
			return file_put_contents(DATAFile, serialize($data));
		}
	}
	function del($id)
	{
		$data = unserialize(file_get_contents(DATAFile));
		if(!$data)return false;
		if($this->checkExisit($id))
		{
			foreach ($data as $key=>$one)
			{
				if($one['id'] == $id)
				{
					unset($data[$key]);
				}
			}
			$data = array_values($data);
			return file_put_contents(DATAFile, serialize($data));
		}
	}
	function checkExisit($id)
	{
		$data = unserialize(file_get_contents(DATAFile));
		if(!$data)return false;
		foreach ($data as $one)
		{
			if($one['id'] == $id)
			{
				return true;
			}
		}
		return false;
	}
}