<?php
/**
 * @author mike
 * 处理用户关系
 * */
class Relation_model extends Base_model
{
	function __construct()
	{
		parent::__construct ();
		$this->tbFollow = 'follow';
		$this->user = 'user';
		$this->tuser = 'tuser';
	}
	function follow($user,$tuser)
	{
		$memcache = new memcache;
		if($memcache ->connect('localhost', $this->config->item('memcache_port'))){
			if($memcache->get("relation_{$user}_{$tuser}")){			
				$memcache->close();
				return true;
			}else{				
				$memcache->set("relation_{$user}_{$tuser}",1,MEMCACHE_COMPRESSED,0);
				$memcache->delete("user_{$user}_follow_ids");
			}
			$memcache->close();
		}
		$this->load->model('user_model');
		$userInfo = $this->user_model->getUserInfo($user);
		$res = $this->addRecords(
		array(
		"user"=>$user,
		"tuser"=>$tuser,
		"wname"=>$userInfo['weibo_uname'],
		"wuid"=>$userInfo['weibo_id'],
		"w_profile_image_url"=>$userInfo['weibo_profile_image'],
		"add_time"=>time()
		),$this->tbFollow);
		if($res)
		{
			//更新其他表
			$res1 = $this->db->query("update {$this->user} set follow_count_b=follow_count_b+1 where id =$user limit 1");
			$res2 = $this->db->query("update {$this->tuser} set follower_count_b=follower_count_b+1 where id =$tuser limit 1");
			return true;
		}
		return false;	
	}
	function unfollow($user,$tuser)
	{
		$memcache = new memcache;
		if($memcache ->connect('localhost', $this->config->item('memcache_port')))
		{
			$memcache->delete("relation_{$user}_{$tuser}");
			$memcache->delete("user_{$user}_follow_ids");
			$memcache->close();
		}
		$res = $this->delRecords($this->tbFollow,"user = {$user} && tuser ={$tuser}");
		if($res)
		{
			//更新其他表
			$res1 = $this->db->query("update {$this->user} set follow_count_b=follow_count_b-1 where id =$user limit 1");
			$res2 = $this->db->query("update {$this->tuser} set follower_count_b=follower_count_b-1 where id =$tuser limit 1");
			return true;
		}
		return false;	
	}
	function getFollowIds($uid)
	{
		$memcache = new memcache;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_connect){
			$mem_res = $memcache->get("user_{$uid}_follow_ids");
			if($mem_res !==false)
			{
				$memcache->close();
				return $mem_res;
			}
		}
		$new = array();
		$res = $this->getFiledValues("tuser",$this->tbFollow,"user={$uid}");
		if($res){
			foreach ($res as $one)
			{
				$new [] =$one['tuser'];
			}
		}
		if($mem_connect){
			$memcache->set("user_{$uid}_follow_ids",$new,MEMCACHE_COMPRESSED,0);
			$memcache->close();
		}
		return $new;
	}
	function dbToMem()
	{
		$size = 1000;
		$memcache = new memcache;
		if($memcache ->connect('localhost', $this->config->item('memcache_port'))){
			for($i=0;$i<1000;$i++)
			{
				$offset = $size*$i;
				$data = $this->getFiledValues("*",$this->tbFollow,"1 order by id limit {$offset},{$size}");
				if(!$data)break;
				foreach ($data as $key=>$one)
				{
					$memcache->set("relation_{$one['user']}_{$one['tuser']}",1,MEMCACHE_COMPRESSED,0);			
				}
			}
			$memcache->close();
		}
	}
}