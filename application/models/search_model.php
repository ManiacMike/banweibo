<?php
/**
 * @author mike
 * 搜索模型
 * */
class Search_model extends Base_model
{
	function __construct()
	{
		parent::__construct ();
		$this->tbUser = 'tuser';
		$this->load->model('tuser_model');
	}
	function getSearchResult($q,$page =1,$onlyImported = false)
	{
		$memcache = new memcache;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		$data = false;
		$urq = urlencode($q);
		if($mem_connect)
		{			
			$mem_res =$memcache->get("search_result_{$page}_{$urq}");
			if($mem_res !==false){
				$data = $mem_res;
			}
		}
		if($data === false)
		{
			$this->load->model('catch_data_model');
			$data = $this->catch_data_model->getData("search_user",array("q"=>urlencode($q),"page"=>$page));
		}
		if($this->checkApiError($data))
		{
			if($mem_connect)
			{
				$memcache->set("search_result_{$page}_{$urq}",$data,MEMCACHE_COMPRESSED,3600*3);
			}
			if($onlyImported)
			{
				foreach ($data as $key=>$one)
				{
					if($memcache->get($one['screen_name']))
					{
						unset($data[$key]);
					}
				}
			}
			if($mem_connect)
			{
				$memcache->close();
			}
			//TODO去掉renderResultData检查是否存在的查询
			return array_map(array($this,'renderResultData'),$data);
		}
		else{
			return array();
		}
	}
	/**
	 * 检查twitter接口返回数据是否正常
	 * */
	function checkApiError($data)
	{
		if(isset($data['errors']) && $data['errors'])
		{
			return false;
		}
		return true;
	}
	/**
	 * 获取数据库中的名人ID
	 * */
	function getDbTuser($q,$page =1)
	{
		if(isset($_SESSION['uid']) && $_SESSION['uid'])
			$this->tuser_model->uid=$_SESSION['uid'];
		$offset = 20*($page-1);
		$res = $this->getFiledValues("*",$this->tbUser,"name like '%$q%' limit {$offset},{$page}");
		if($res)
		{
			foreach ($res as $key=>$arr)
			{
				$arr = $this->tuser_model->checkFollow($arr);
				$arr ['uid'] = $arr ['id'];
				$arr ['button_type'] = "1";
				$arr ['profile_image_url'] = str_replace("_normal","_reasonably_small",$arr ['profile_image_url']);
				$arr = $this->cutWord($arr);
				$res [$key] = $arr;
			}
			return $res;
		}
		return array();
	}
	/**
	 * 从sphinx获取结果
	 * */
	function getSphinxResult($q,$page =1)
	{
		$this->load->library("coreseek");
		$data = $this->coreseek->result($q,$page);
		if(!$data['ids'])return array();
		$idStr = implode(",",$data['ids']);
		$res = $this->getFiledValues("*",$this->tbUser,"id in ({$idStr}) order by field(id,{$idStr})");
		if($res)
		{
			foreach ($res as $key=>$arr)
			{
				$arr = $this->tuser_model->checkFollow($arr);
				$arr ['uid'] = $arr ['id'];
				$arr ['button_type'] = "1";
				$arr ['profile_image_url'] = str_replace("_normal","_reasonably_small",$arr ['profile_image_url']);
				$arr = $this->cutWord($arr);
				$res [$key] = $arr;
			}
			return $res;
		}
		return array();
	}
	function renderResultData($arr)
	{
		if(isset($_SESSION['uid']) && $_SESSION['uid'])
			$this->tuser_model->uid=$_SESSION['uid'];
		$res = $this->getSingleFiledValues("id,cname,whatsit,intro",$this->tbUser,"tid={$arr['id']}");
		if($res)
		{
			$res = $this->tuser_model->checkFollow($res);
			$arr ['uid'] = $res ['id'];
			$arr ['cname'] = $res ['cname'];
			$arr ['whatsit'] = $res ['whatsit'];
			$arr ['intro'] = $res ['intro'];
			$arr ['button_type'] = "1";
			$arr ['isfollow'] = $res['isfollow'];
		}
		else
		{
			$this->load->model('recommend_model');
			$res1 = $this->recommend_model->getStatusByScreenName($arr['screen_name']);
			if($res1)
			{
				$arr ['button_type'] = "2";
				$arr ['status'] = $res1 ['status'];
			}
			else
			{
				$arr ['button_type'] = "0";
			}
		}
		$arr ['profile_image_url'] = str_replace("_normal","_reasonably_small",$arr ['profile_image_url']);
		$arr = $this->cutWord($arr);
		return $arr;
	}
	function getTagResult($keyword,$row)
	{
		//TODO缓存结果
		$this->load->model('catch_data_model');
		$data = $this->catch_data_model->getData("search",array("q"=>$keyword,"page"=>$row));
		if($data)
		{
			return array_map(array($this,'renderTagResult'),$data['results']);
		}
		else{ 
			return array();
		}		
	}
	function renderTagResult($arr)
	{
		$this->load->model("tweet_model");
		$arr ['profile_image_url'] = str_replace("_normal","_reasonably_small",$arr ['profile_image_url']);
		$arr ['created_at'] = $this->tweet_model->formatTime($arr ['created_at']);
		$arr ['name'] = $arr['from_user_name'];
		$arr ['raw'] = 1;
		$arr = $this->tweet_model->formatText($arr);
		unset($arr ['entities']);
		return $arr;
	}
	function cutWord($data)
	{
		if(strlen($data['screen_name'])>12)
		{
			$data['screen_name_all'] = $data['screen_name'];
			$data['screen_name'] = substr($data['screen_name'],0,11)."...";
		}
		$length = $data['verified']?11:13;
		$this->load->library("cnStr");
		if($this->cnstr->cnStrLen($data['name'])>$length)
		{
			$data['name_all'] = $data['name'];
			$data['name'] = $this->cnstr->cnStrCut($data['name'],$length-1)."...";
		}
		return $data;
	}
}