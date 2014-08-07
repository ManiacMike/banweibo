<?php
/**
 * @author mike
 * 处理分类
 * */
class Category_model extends Base_model
{
	private $tbData;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'tuser_category';
		$this->tbUser = 'tuser';
	}
	function getAll()
	{
		$res = $this->getFiledValues("*",$this->tbData,"1");
		foreach ($res as $key=>$one)
		{
			$res [$key] ['father_id'] = $res [$key] ['father'];
			$res [$key] ['father'] = $one ['father']==0?"":$this->getColumnById($one ['father'],"name");
			if($one['son'])
			{
				$son = unserialize($one ['son']);
				$son = array_map(array($this,"arrPop"),$son);
				$res [$key] ['son'] = implode(",",$son);
			}
		}
		return $res;
	}
	function arrPop($arr)
	{
		return $arr['name'];
	}
	function add($data)
	{
		if($this->checkNameExist($data['name']))
		{
			header("Content-type:text/html;charset=utf-8");
			echo "<script>alert('名称\"{$data['name']}\"已存在');location.href='".BASE_URL."/administration/category/'</script>";
			die;
		}
		$vArr = $data;
		if($vArr['name'])
		{
			$id = $this->addRecords($vArr,$this->tbData);
			if($vArr ['father'])
			{
				$father = $this->getColumnById($vArr ['father'],"son");
				$father = $father?unserialize($father):array();
				$father [] = array("name"=>$vArr['name'],"id"=>$id);
				$this->updateRecords($this->tbData,array("son"=>serialize($father)),"id={$vArr ['father']}");
			}
		}
	}
	function checkNameExist($name){
		return $this->getSingleFiledValues("id",$this->tbData,"name='{$name}'")?true:false;
	}
	function update($data)
	{
		foreach ($data as $id=>$vArr)
		{
			$vArr['cover'] = trim($vArr['cover']);
			if($vArr['cover'])
			{
				$vArr['cover_url'] = $this->getPurlByName($vArr['cover']);
			}
			$tmp = $vArr;
			unset($vArr['old_name'],$vArr['father_id']);
			$this->updateRecords($this->tbData,$vArr,"id={$id}");
			$vArr= $tmp;
			//检查名称是不是更换
			if($vArr['name'] !=$vArr['old_name']){
				//是不是子类，更换主类的son
				if($vArr['father_id'] != 0)
				{
					$son = unserialize($this->getColumnById($vArr['father_id'],"son"));
					foreach ($son as $key=>$one)
					{
						if($one['id'] == $id)
						{
							$son[$key]['name'] = $vArr['name'];
							break;
						}
					}
					$this->updateRecords($this->tbData,array("son"=>serialize($son)),"id={$vArr['father_id']}");
					$replace_column = "sub_cid";
				}else {
					$replace_column = "main_cid";
				}
				//替换tuser表中数据
				$relevant = $this->getFiledValues("id,{$replace_column}",$this->tbUser,"find_in_set('{$vArr['old_name']}',{$replace_column})");
				foreach ($relevant as $one)
				{
					$replace = str_replace($vArr['old_name'],$vArr['name'],$one[$replace_column]);
					$this->updateRecords($this->tbUser,array($replace_column=>$replace),"id={$one['id']}");
				}
			}
		}
		//排序
		file_get_contents(BASE_URL."/sort_data/updateCategory/");
	}
	function getPurlByName($name)
	{
		$res = $this->getSingleFiledValues("profile_image_url",$this->tbUser,"name='{$name}'");
		return $res?$res['profile_image_url']:'';
	}
	function del($id)
	{
		return $this->delRecords($this->tbData,"id={$id}");
	}
	function getColumnById($id,$column)
	{
		$res=$this->getSingleFiledValues($column,$this->tbData,"id={$id}");
		return $res?$res[$column]:"";
	}
	function getItemById($id)
	{
		return $this->getSingleFiledValues("*",$this->tbData,"id={$id}");
	}
	function getIdByName($name)
	{
		$res=$this->getSingleFiledValues("id",$this->tbData,"name='{$name}'");
		return $res?$res['id']:"";
	}
	function getCategory()
	{
		$res = $this->getFiledValues("id,name,cover,cover_url",$this->tbData,"father = 0 && ifshow=1");
		return $res;
	}
	function checkName($name_arr)
	{
		foreach($name_arr as $key=>$name)
		{
			if(!$this->getIdByName($name))
			{
				unset($name_arr[$key]);
			}
		}
		return implode(",",$name_arr);
	}
	function getIdsByNames($name_arr) {
		if($name_arr){
			$name_arr = explode(",",$name_arr);
		}
		foreach($name_arr as $key=>$name){
			$new [] = $this->getIdByName($name);
		}
		return $new;
	}
	//从分类中删除特定ID
	function delTuidFromCategory($cid,$id){
		$value=$this->getColumnById($cid,"value");
		if($value !=""){
			$ids = explode(",",$value);
			$s = array_search($id,$ids);
			if($s !==false)
			{
				unset($ids[$s]);
			}
			$value = implode(",",$ids);
			$this->updateRecords($this->tbData,array("value"=>$value),"id={$cid}");
		}
	}
	//从分类中删除特定ID
	function addTuidFromCategory($cid,$id){
		$value=$this->getColumnById($cid,"value");
		if($value == "")
		{
			$this->updateRecords($this->tbData,array("value"=>$id),"id={$cid}");
		}
		else{
			$ids = explode(",",$value);
			$s = array_search($id,$ids);
			if($s ===false)
			{
				$ids [] = $id;
				$value = implode(",",$ids);
				$this->updateRecords($this->tbData,array("value"=>$value),"id={$cid}");
			}
		}
	}
	//更新tuser中的分类数据
	function updateCategoryTuser($vArr,$id){
		return $this->updateRecords($this->tbUser,$vArr,"id=$id");
	}
	//更行category表中数据
	function updateTuserCategory($vArr,$id){
		if($vArr['main_cid'])
		{
			$ids = $this->getIdsByNames($vArr['main_cid']);
			foreach ($ids as $cid)
			{
				$this->addTuidFromCategory($cid,$id);
			}
		}
		if($vArr['sub_cid'])
		{
			$ids = $this->getIdsByNames($vArr['sub_cid']);
			foreach ($ids as $cid)
			{
				$this->addTuidFromCategory($cid,$id);
			}
		}
	}
	//从category表中删除这个tuser的
	function removeCurrentCategory($id){
		$now = $this->getCurrentCategory($id);
		if($now['main_cid'])
		{
			$ids = $this->getIdsByNames($now['main_cid']);
			foreach ($ids as $cid)
			{
				$this->delTuidFromCategory($cid,$id);
			}
		}
		if($now['sub_cid'])
		{
			$ids = $this->getIdsByNames($now['sub_cid']);
			foreach ($ids as $cid)
			{
				$this->delTuidFromCategory($cid,$id);
			}
		}
	}
	function getCurrentCategory($id)
	{
		return $this->getSingleFiledValues("main_cid,sub_cid",$this->tbUser,"id=$id");
	}
}