<?php
/**
 * @author mike
 * 后台数据处理类
 * */
class Recommend_model extends Base_model
{
	private $tbData;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'recommend_tuser';
		$this->tbUser = 'tuser';
	}
	function addRecommend($tuid, $tname, $tscreen_name, $tip, $user)
	{
		$vArr = array ( 
			'tuid' => $tuid , 
			'tname' => $tname , 
			'tscreen_name' => $tscreen_name , 
			'wuid' => $user ['weibo_id'] , 
			'wname' => $user ['weibo_uname'] , 
			'tip' => $tip , 
			'uid' => $_SESSION['uid'] , 
			'time' => time () 
		);
		return $this->addRecords($vArr,$this->tbData);
	}
	function getRecentApproved($num = 5)
	{
		$memcache = new memcache;
		$mem_conn = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_conn){
			$mem_res = $memcache->get("search_recent_approved");
			if($mem_res)
			{
				return $mem_res;
			}
		}
		$data = $this->getFiledValues("id,tname,wname,tscreen_name,wuid",$this->tbData," status =1 order by atime desc limit $num");
		if($mem_conn)
		{
			$memcache->set("search_recent_approved",$data,MEMCACHE_COMPRESSED,3600*3);
		}
		return $data;
	}
	function getStatusByTuid($id)
	{
		return $this->getSingleFiledValues("id,status",$this->tbData,"tuid={$id}");
	}
	function getStatusByScreenName($screen_name)
	{
		return $this->getSingleFiledValues("id,status",$this->tbData,"tscreen_name='{$screen_name}'");
	}
	function getCurPage()
	{
		$p = $this->pagination->cur_page;
		$p = $p == 0 ? 1 : $p;
		return $p;
	}
	function getRecommend($status)
	{
		$p=$this->getCurPage();
		$offset = PAGESIZE*($p-1);
		$res = $this->getFiledValues("*",$this->tbData," status = {$status} limit {$offset},".PAGESIZE);
		foreach ($res as $key=>$value)
		{
			$res [$key]['time'] =date("Y-m-d H:i:s",$res [$key]['time']);
			$res [$key]['atime'] =$res [$key]['atime']==0?0:date("Y-m-d H:i:s",$res [$key]['atime']);
		}
		return $res;
	}
	function getRecoPageStr($status)
	{
		$this->load->library ( 'pagination' );
		$config ['base_url'] = BASE_URL . "/administration/recommend/{$status}/";
		$config ['total_rows'] = $this->getRecoCount ($status);
		$config ['per_page'] = PAGESIZE;
		$config ['uri_segment'] = 4;
		$this->pagination->initialize ( $config );
		$pageStr = $this->pagination->create_links ();
		return $pageStr;
	}
	function getRecoCount($status)
	{
		return $this->countRecord($this->tbData,"status={$status}");
	}
	function updateRecommend($data)
	{
		$this->load->model("catch_data_model");
		$this->load->model("common_model");
		$this->load->model("tuser_model");
		foreach ($data as $recoId=>$action)
		{
			$res = $this->getSingleFiledValues("*",$this->tbData,"id={$recoId}");
			if($action == 1)
			{
				$request_res = $this->catch_data_model->updateUserInfoByScreenName($res['tscreen_name']);
				if(isset($request_res['error']) || isset($request_res['errors']))
				{
					continue;
				}
				$this->updateRecords($this->tbUser,array("reco_by_wuid"=>$res["wuid"],"reco_by_wname"=>$res["wname"]),"screen_name='{$res['tscreen_name']}'");
				$this->updateRecords($this->tbData,array("status"=>1,"atime"=>time()),"id={$recoId}");
			}
			else
			{
				$this->updateRecords($this->tbData,array("status"=>2,"atime"=>time()),"id={$recoId}");
			}
		}
		$this->common_model->truncTuserNum();
		$this->common_model->createScreenNameCache();
	}
	function getAllAproved()
	{
		return $this->getFiledValues("*",$this->tbData,"status=1 && notification=0");
	}
	//获取通过了却未通知的人数
	function getAprovedCount()
	{
		$this->countRecord($this->tbData,"status=1 && notification=0");
	}
	function updateNotify($id,$status)
	{
		return $this->updateRecords($this->tbData,array("notification"=>$status),"id={$id}");
	}
	//根据tuser表中数据去更新recommend
	function updateRecommendByDelete($status =2){
		$memcache = new memcache;
		$mem_conn = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		$res = $this->getFiledValues("*",$this->tbData,"status=1");
		foreach($res as $one){
			if(!$memcache->get($one['tscreen_name'])){
				echo $one['tname']."<br>";
				$this->updateRecords($this->tbData,array("status"=>$status),"id={$one['id']}");
			}
		}
	}
	function deleteInvalidFollow(){
		$this->load->model("tuser_model");
		//删除无效关注
		$res = $this->fetchRecord("SELECT * 
						FROM  `follow` 
						WHERE 1 
						GROUP BY tuser");
		foreach ($res as $one)
		{
			if(!$this->tuser_model->getUserById($one['tuser']))
			{
				echo $one['tuser']."<br>";
				$this->delRecords("follow","tuser={$one['tuser']}");
			}
		}
		//删除重复条目
//		$res = $this->fetchRecord("SELECT count(*) as num,user,tuser
//						FROM  `follow` 
//						GROUP BY user,tuser having num >1");
		//更新user表中关注数
	}
}
