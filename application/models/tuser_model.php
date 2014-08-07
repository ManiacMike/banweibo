<?php
/**
 * @author mike
 * 读tuser表的类
 * */
class Tuser_model extends Base_model
{
	public $uid;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'tuser';
		$this->tbNation = 'nation_select';
		$this->tbWhatsit = 'whatsit_select';
		$this->tbFollow = 'follow';
		$this->listComm = "id,tid,name,cname,screen_name,profile_image_url_128,intro,statuses_count,whatsit,verified";
		define('LIST_PAGESIZE',20);
	}
	function getIndexPeople($num = 5)
	{
		$memcache = new memcache;
		$res = false;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_connect){
				$res = $memcache->get("index_people_recommend");
				$memcache->close();
		}
		if($res ==false){
			$res = $this->getFiledValues($this->listComm,$this->tbData,"1 order by index_order,id limit {$num}");
			if($mem_connect)
			{
				$memcache->set("index_people_recommend",$res,MEMCACHE_COMPRESSED,3600);
			}
		}
		if(isset($_SESSION['uid']))
		{
			$this->load->model('relation_model');
			$fids = $this->relation_model->getFollowIds($_SESSION['uid']);
			if($fids)
			{
				foreach ($res as $key=>$one)
				{
					if(array_search($one['id'],$fids)!==false)
						unset($res[$key]);
				}
			}
		}else{
			$res = array_map(array($this,"cutWord"),$res);
		}
		return $res;
	}
	function getPopularPeople($num)
	{
		$res = $this->getFiledValues($this->listComm,$this->tbData,"1 order by follower_count_b desc limit {$num}");
		$res = array_map(array($this,"cutWord"),$res);
		return $res;
	}
	function getListPeople($categoryId,$subCategoryId,$page,$total,$order)
	{
		$data = false;
		$memcache = new memcache;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_connect)
		{
			$data = $memcache ->get("list_{$categoryId}_{$subCategoryId}_{$page}_{$order}");	
		}
//		echo "list_{$categoryId}_{$subCategoryId}_{$page}_{$order}";die;
		if(!$data)
		{
			$this->load->model("category_model");
			if($categoryId && $subCategoryId)
			{
				$res = $this->category_model->getItemById($subCategoryId);
			}
			elseif($categoryId && !$subCategoryId)
			{
				$res = $this->category_model->getItemById($categoryId);
			}
			$data = array("son"=>$res['son'],"total"=>$res['count']);
			$data['son'] = $data['son']?unserialize($data['son']):"";
			switch($order)
			{
				case 'hot':
					$value_column = 'hot_value';
					break;
				case 'tfans':
					$value_column = 't_value';
					break;
				default:
					$value_column = 'value';
			}
			if($res[$value_column])
			{
				$where = "id in ({$res[$value_column]})";	
				$data ['data'] = $this->getFiledValues($this->listComm,$this->tbData,"{$where} order by field(id,{$res[$value_column]}) limit ".($page-1)*LIST_PAGESIZE.",".LIST_PAGESIZE);
				$data ['data'] = array_map(array($this,"cutWord"),$data ['data']);
				$data ['count'] = count($data['data']);
			}
			else
			{
				$data = array("count"=>0,"data"=>array());
			}
			//设置缓存
			if($mem_connect)
			{
				$memcache->set("list_{$categoryId}_{$subCategoryId}_{$page}_{$order}",$data,MEMCACHE_COMPRESSED,1);
			}
		}
		if($mem_connect)	$memcache->close();//关闭memcache
		if($data ['data'] && $this->uid)
				$data ['data'] = array_map(array($this,"checkFollow"),$data ['data']);
		return $data;
	}
	/**
	 * 检查是不是已关注
	 * */
	function checkFollow($data)
	{
		$memcache = new memcache;
		if($memcache ->connect('localhost', $this->config->item('memcache_port'))){
			$data ['isfollow'] = $memcache->get("relation_{$this->uid}_{$data['id']}")?1:0;
		}else{
			$res = $this->getSingleFiledValues("id",$this->tbFollow,"user={$this->uid} && tuser={$data['id']}");
			$data ['isfollow'] = $res?1:0;
		}
		return $data;
	}
	function checkFollowIndex($data)
	{
		$data ['isfollow'] = 1;
		return $data;
	}
	/**
	 *获取列表页的分页
	 * */
	function getListPageStr($total,$page)
	{
		$this->load->library ( 'pagination' );
		$config['base_url'] = "";
		$config['total_rows'] = $total;
		$config['per_page'] = LIST_PAGESIZE;
		$config['full_tag_open'] = '<ul id="list_page_ul">';
//		$config['cur_page'] = $page;
		$this->pagination->cur_page = $page;
		$this->pagination->initialize($config);
		$pageStr = $this->pagination->create_links();
		return $pageStr;
	}
	/**
	*获取user总数
	*
	*/
	function getTuserCount()
	{
		$res = $this->countRecord($this->tbData,"1");
		return $res;
	}
	function getAllNation($tuid)
	{
		$config = $this->getFiledValues("*",$this->tbNation,"1");
		foreach ($config as $key=>$one)
		{
			if($one['value'])
			{
				if(array_search($tuid,explode(",",$one['value']))!==false)
				{
					$config [$key] ['on'] = 1;
					break;
				}
			}
		}
		return $config;
	}
	function getAllWhatsit($tuid)
	{
		$config = $this->getFiledValues("*",$this->tbWhatsit,"1");
		foreach ($config as $key=>$one)
		{
			if($one['value'])
			{
				if(array_search($tuid,explode(",",$one['value']))!==false)
				{
					$config [$key] ['on'] = 1;
				}
			}
		}
		return $config;
	}
	//获取用户
	function getUser($id)
	{
		$data = $this->getSingleFiledValues("*",$this->tbData,"id={$id}");
		$data ['profile_image_url_128'] = str_replace("https://", "http://", $data ['profile_image_url_128']);
		$data ['profile_image_url_ori'] = str_replace("https://", "http://", $data ['profile_image_url_ori']);
		$data ['followers_count'] = $data ['followers_count']>100000?round($data ['followers_count']/10000,1)."<span>万</span>":$data ['followers_count'];
		$data ['friends_count'] = $data ['friends_count']>100000?round($data ['friends_count']/10000,1)."<span>万</span>":$data ['friends_count'];
		if($this->uid)
			$data = $this->checkFollow($data);
		return $data;
	}
	//获取粉丝
	function getFollower($id,$page = 1)
	{
		$pagesize = 12;
		$offset = $pagesize * ($page-1);
		$res = $this->getFiledValues("*",$this->tbFollow,"tuser={$id}  order by add_time desc limit {$offset},{$pagesize}");
		return $res;
	}
	//获取关注的人
	function getFollow($page = 1,$if_from_index=false)
	{
		$pagesize = PAGESIZE;
		$offset = $pagesize * ($page-1);
		$this->load->model('relation_model');
		$ids = $this->relation_model->getFollowIds($this->uid);
		if($ids)
		{
			$ids = implode(",",$ids);
			$res = $this->getFiledValues($this->listComm,$this->tbData,"id in ({$ids}) limit {$offset},{$pagesize}");
			if($if_from_index)
				$res = array_map(array($this,"checkFollowIndex"),$res);
			else
				$res = array_map(array($this,"checkFollow"),$res);
			$res = array_map(array($this,"cutWord"),$res);
			return $res;
		}
		return array();
	}
	function getIdByScreenName($screen_name)
	{
		return $res = $this->getSingleFiledValues("id",$this->tbData,"screen_name='{$screen_name}'");	
	}
	function getIdByTid($tid)
	{
		return $res = $this->getSingleFiledValues("id,tid,screen_name,name",$this->tbData,"tid='{$tid}'");
	}
	function getUserById($id)
	{
		return $res = $this->getSingleFiledValues("id,tid,screen_name",$this->tbData,"id='{$id}'");
	}
	/**
	 * TODO获取twitter关注入库
	 * */
	function updateUserTwitterFollow($tid)
	{
		$this->load->model("catch_data_model");
		$this->catch_data_model->apiId = $tid;
		$ids = $this->catch_data_model->getAllFollow();
		foreach ( $ids as $id )
		{
		}
		return false;
	}
	/**
	 * TODO获取twitter信息入库
	 * */
	function updateUserTwitterInfo($tid)
	{
		
	}
	function cutWord($data)
	{
		$length = $data['verified']?11:13;
		$this->load->library("cnStr");
		if($this->cnstr->cnStrLen($data['name'])>$length)
		{
			$data['name_all'] = $data['name'];
			$data['name'] = $this->cnstr->cnStrCut($data['name'],$length-1)."...";
		}
		if($this->cnstr->cnStrLen($data['intro'])>18)
		{
			$data['intro_all'] = $data['intro'];
			$data['intro'] = $this->cnstr->cnStrCut($data['intro'],16)."...";
		}
		return $data;
	}
}