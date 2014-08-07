<?php
class Sort_data_model extends Base_model
{
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'tuser';
		$this->tbCate = 'tuser_category';
		$this->tbFollow = 'follow';
	}
	function getIdAndScreenName()
	{
		$new =array();
		$res = $this->getFiledValues("id,screen_name",$this->tbData,"1");
		foreach ($res as $one)
		{
			$new [$one['screen_name']] = $one['id'];
		}
		return $new;
	}
	function updateCategory()
	{
		$res = $this->getFiledValues("id,father,value",$this->tbCate,"1");
		$cid_arr = array();
		foreach($res as $one)
		{
			if($one ['value'])
			{
				$ids = $this->getNameByIds($one ['value']);
				//更新tuser表
				foreach ($ids as $tuser_id)
				{
					if(isset($one['father']) && $one['father'])
						$cid_arr [$tuser_id] ['sub_cid'] [] = $one['id'];
					else
						$cid_arr [$tuser_id] ['main_cid'] [] = $one['id'];
				}
				$vArr = array("count"=>count($ids),"value"=>implode(",",$ids));
				$this->updateRecords($this->tbCate,$vArr,"id={$one['id']}");
			}
		}
		foreach ($cid_arr as $tuser=>$vArr){
			if(isset($vArr['main_cid']))
			{
				$vArr['main_cid'] = array_map(array($this,"getNameById"),$vArr['main_cid']);
				$vArr['main_cid']=implode(",",$vArr['main_cid']);
			}
			else
			{
				$vArr['main_cid']= "";
			}
			if(isset($vArr['sub_cid']))
			{
				$vArr['sub_cid'] = array_map(array($this,"getNameById"),$vArr['sub_cid']);
				$vArr['sub_cid']=implode(",",$vArr['sub_cid']);
			}else{
				$vArr['sub_cid']= "";
			}
			$this->updateRecords($this->tbData,$vArr,"id={$tuser}");
		}
	}
	function getNameById($id)
	{
		$this->load->model("category_model");
		$name = $this->category_model->getColumnById($id,"name");
		return $name;
	}
	function getNameByIds($ids)
	{
		$res = $this->getFiledValues("id,name",$this->tbData,"id in ({$ids})");
		$new = array();
		foreach ($res as $one)
		{
			$new [$one['name']] = $one['id'];
		}
		ksort($new);
		return array_values($new);
	}
	//获取ID大于某数的
	function getIdMoreThan($id = 1813)
	{
		return $this->getFiledValues("id,tid",$this->tbData,"id>{$id}");
	}
	//更新tuser表中的t_follow_account字段
	function update_t_follow_account($t_account = 1){
		$this->load->model('catch_data_model');
		$res = $this->catch_data_model->getAllFollow($t_account);
		foreach ($res as $id)
		{
			$this->updateRecords($this->tbData,array("t_follow_account"=>$t_account),"tid=$id");
		}
	}
	//获取未在twitter关注的ID
	function getUnfollowAccount()
	{
		return $this->getFiledValues("id,tid",$this->tbData,"t_follow_account =0 && tid != 297964934 && protected =0 && if_error=0");
	}
	function markError($tid){
		return $this->updateRecords($this->tbData,array("if_error"=>1),"tid=$tid");
	}
	function updateFollowAccount($tid,$sys){
		return $this->updateRecords($this->tbData,array("t_follow_account"=>$sys),"tid=$tid");
	}	
	function markProtected($tid){
		return $this->updateRecords($this->tbData,array("protected"=>1),"tid=$tid");
	}
	//生成follow data_key
	function fillDataKey()
	{
		$size = 1000;
		for($i=0;$i<1000;$i++)
		{
			$offset = $size*$i;
			$data = $this->getFiledValues("*",$this->tbFollow,"id>969236 order by id limit {$offset},{$size}");
			if(!$data)break;
			foreach ($data as $key=>$one)
			{
				if(!$this->checkKeyExist("{$one['user']}_{$one['tuser']}"))
					$this->updateRecords($this->tbFollow,array("data_key"=>"{$one['user']}_{$one['tuser']}"),"id={$one['id']}");
			}
		}
	}
	function checkKeyExist($datakey)
	{
		if($datakey == '')
		return false;
//		echo "data_key='$datakey'";die;
		$res = $this->getSingleFiledValues("id",$this->tbFollow,"`data_key`='$datakey'");
		return $res?true:false;
	}
	//更新user表的follow_count_b
	function updateUserFollowCount($page)
	{
		$pageSize = 1000;
		$offset = $pageSize*($page-1);
		$data = $this->fetchRecord("SELECT user, COUNT( * ) AS num
		FROM  `follow` 
		GROUP BY user
		LIMIT $offset,$pageSize",false);
		if(!$data)die("--end--");
		foreach ($data as $one)
		{		
			$this->updateRecords("user",array("follow_count_b"=>$one['num']),"id={$one['user']}");
		}
	}
	//更新热门表中的排序value
	function updateHotsortValue()
	{
		$data = $this->getFiledValues("id,value,count",$this->tbCate ,"1");
		foreach ($data as $value) {
			$ids = array();
			$ids1 = array();
			if($value['count']>1){
				$res = $this->getFiledValues("id",$this->tbData,"id in ({$value['value']}) order by follower_count_b desc");
				foreach ($res as $one)
				{
					$ids[]=$one['id'];
				}
				$hot_value = implode(",",$ids);
				$res1 = $this->getFiledValues("id",$this->tbData,"id in ({$value['value']}) order by followers_count desc");
				foreach ($res1 as $one)
				{
					$ids1[]=$one['id'];
				}
				$t_value = implode(",",$ids1);				
			}else{
				$hot_value = $value['value'];
				$t_value = $value['value'];
			}
			$this->updateRecords($this->tbCate,array('hot_value'=>$hot_value,'t_value'=>$t_value),"id={$value['id']}");
		}
	}
}