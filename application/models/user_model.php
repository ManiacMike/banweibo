<?php
/**
 * @author mike
 * 处理user表的类
 * */
class User_model extends Base_model
{
	private $tbData;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'user';
		$this->follow = 'follow';
	}
	function getUserInfo($id,$cache = true)
	{
		if($cache == true)
		{
			if(isset($_COOKIE['login_uname']) && isset($_COOKIE['login_id']) && $_COOKIE['login_id'] && $_COOKIE['login_uname'])
			{
				$this->load->library('encrypt');
				return array(
				"weibo_uname"=>$this->encrypt->decode($_COOKIE['login_uname']),
				"weibo_id"=>$this->encrypt->decode($_COOKIE['login_id']),
				"weibo_profile_image"=>$this->encrypt->decode($_COOKIE['login_img']),
				);
			}
		}
		return $this->getSingleFiledValues("*",$this->tbData,"id={$id}");	
	}
	function getIdByWuid($id)
	{
		$res = $this->getSingleFiledValues("id",$this->tbData,"weibo_id={$id}");
		return $res?$res['id']:false;
	}
	function getInfoByWuid($id)
	{
		return $this->getSingleFiledValues("*",$this->tbData,"weibo_id={$id}");
	}
	function formatArr($data)
	{
		$vArr = array(
		"weibo_uname"=>$data ['name'],
		"weibo_screen_name"=>$data ['screen_name'],
		"province"=>$data ['province'],
		"city"=>$data ['city'],
		"location"=>$data ['location'],
		"description"=>$data ['description'],
		"weibo_profile_image"=>$data ['profile_image_url'],
		"url"=>$data ['url'],
		"profile_url"=>$data ['profile_url'],
		"domain"=>$data ['domain'],
		"gender"=>$data ['gender'],
		"verified"=>$data ['verified'],
		"verified_type"=>$data ['verified_type'],
		"token"=>$_SESSION['token']['access_token']
		);
		return $vArr;
	}
	function addUser($data)
	{
		$id = $data['idstr'];
		$vArr = $this->formatArr($data);
		$vArr ['atime'] = time();
		$vArr ['auth_update'] = time();
		$vArr ['weibo_id'] = $id;
		return $this->addRecords($vArr,$this->tbData);
	}
	function updateUser($data)
	{
		$id = $data['idstr'];
		$vArr = $this->formatArr($data);
		$vArr ['auth_update'] = time();
		$res = $this->updateRecords($this->tbData,$vArr,"weibo_id={$id}");
		return $res['id'];
	}
	function countUser()
	{
		return $this->countRecord($this->tbData,"1");
	}
	function countFollow()
	{
		return $this->countRecord($this->follow,"1");
	}
	function getGenderRate()
	{
		$res = $this->fetchRecord("select count(*) as num,gender from {$this->tbData} group by gender");
		foreach ($res as $one)
		{
			$new [$one['gender']] = $one['num'];
		}
		return $new;
	}
	function getProvinceRate()
	{
		$res = $this->fetchRecord("select count(*) as num,province,location from {$this->tbData} group by province");
		foreach ($res as $key=>$one)
		{
			$tmp = explode(" ",$one['location']);
			$res [$key]['location'] = $tmp[0];
		}
		return $res;
	}
	function updateTwitterToken($token,$token_secret)
	{
		if(isset($_SESSION['uid']) && $_SESSION['uid'])
		{
			$res = $this->getSingleFiledValues("t_atime",$this->tbData,"id={$_SESSION['uid']}");
			if($res['t_atime'])
			{
				$vArr = array("t_oauth_token"=>$token,
				"t_oauth_token_secret"=>$token_secret,
				"t_atime_update"=>time()
				);
			}
			else {
				$vArr = array("t_oauth_token"=>$token,
				"t_oauth_token_secret"=>$token_secret,
				"t_atime"=>time(),
				"t_atime_update"=>time()
				);				
			}
			return $this->updateRecords($this->tbData,$vArr,"id={$_SESSION['uid']}");
		}
		return false;
	}
	//删除user表中的twitter token
	function unbindTwitter()
	{
		$vArr = array("t_oauth_token"=>"","t_oauth_token_secret"=>"","t_atime_update"=>0);
		return $this->updateRecords($this->tbData,$vArr,"id={$_SESSION['uid']}");
	}
	//获取follow twitter的信息
	function getFollowTwitterInfo()
	{
		if(!isset($_SESSION['uid']))return false;
		$res = $this->getSingleFiledValues("twitter_follow_access,twitter_follow_update,syn_follow",$this->tbData,"id={$_SESSION['uid']}");
		$res['btn_enable'] = $res['twitter_follow_update'] ==0?true:((time() - $res['twitter_follow_update']>3600*24)?true:false);
		return $res;
	}
}