<?php
/**
 * @author mike
 * 抓取数据控制器
 * action:
 * userinfo人物信息
 * follow 关注
 * newsfeed 主时间线
 * usertweets 用户tweets
 * */
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Catch_data extends CI_Controller
{
	function __construct()
	{
		ini_set("display_errors",1);
		set_time_limit ( 0 );
		ini_set("memory_limit","20M");
		parent::__construct ();
		$this->load->model ( 'catch_data_model' );
	}
	//抓取全部twitter账号follow
	function index()
	{
		$ids = $this->catch_data_model->getTuserOrderUpdate (100);
		foreach ( $ids as $one )
		{
			//第二个参数表示只进行添加操作
			$this->catch_data_model->updateUserInfo ( $one['tid'] );
		}
	}
	function homeline($action ='update')
	{
		$sysId = $this->input->get("sysId")?$this->input->get("sysId"):1;
		$this->catch_data_model->updateHomeLine ($action,$sysId);
// 		$this->createIndexCache();
	}
	//更新某一tid的账号信息
	function user($tid)
	{
		$this->catch_data_model->updateUserInfo ( $tid );
	}
	//更新某一tid的tweet
	function tweet($tid)
	{
		$this->load->model("datafill_manage_model");
		$action = $this->input->get ( 'action' ) ? $this->input->get ( 'action' ) : 'fill';
		$isMulFetch = $this->input->get ( 'isMulFetch' ) ? $this->input->get ( 'isMulFetch' ) : '0';
		$id = $this->catch_data_model->updateUserTweet ( $tid, $action ,$isMulFetch);		
		$next = $this->catch_data_model->getNextTuid($id);
		//删除队列
		$this->datafill_manage_model->del($id);
		if($next && $isMulFetch)
		{
			//继续向下
			$this->catch_data_model->htmlRedirect(BASE_URL."/catch_data/tweet/{$next}/?action={$action}&isMulFetch=1");
		}
		else 
		{
			die("--end--");
		}
	}
	//抓取tuser的关注列表
	function catch_tuser_follow($tid)
	{
		$this->load->model("tfollow_model");
		$isMulFetch = $this->input->get ( 'isMulFetch' ) ? $this->input->get ( 'isMulFetch' ) : '0';
		$token = $this->input->get ( 'token' ) ? $this->input->get ( 'token' ) : '297964934-qYqK9yxy6aDONMYueSZwPUeK41qJIfvM3FqsvWFM';
		$token_secret = $this->input->get ( 'token_secret' ) ? $this->input->get ( 'token_secret' ) : 'Ze8YQXBdy5Eoj7d5g26TKbpVQ8XqUH5wfnMnW5sjlc';
		$this->load->library("twitter",array("token"=>$token,
		"token_secret"=>$token_secret));
		$cursor = $this->input->get("cursor")?$this->input->get("cursor"):"-1";
		$param = array("user_id"=>$tid,"skip_status"=>true,"cursor"=>$cursor);
		var_dump($param);echo "<br><br>";
		$action = "follow_list";
		$data = $this->twitter ->getData($param,$action);
//		var_dump($data);
		$next_cursor = $data['next_cursor'];
		if(isset($data['error']))
		{
			var_dump($data);
			die;
		}
		if(isset($data['errors']))
		{
			if($data['errors'][0]['code'] == 88 || $data['errors'][0]['code'] == 89)
			{
				$valid_token = $this->common_model->getValidToken();
				$request = $_GET;
				$request ['token'] = $valid_token['t_oauth_token'];
				$request ['token_secret'] = $valid_token['t_oauth_token_secret'];
				$redirect = BASE_URL."/catch_data/catch_tuser_follow/{$tid}/";
				$i=0;
				foreach ($request as $key=>$value)
				{
					if($i == 0)
						$redirect .="?";
					else
						$redirect .="&";
					$redirect .= $key."=".$value;
					$i++;				
				}
				$this->catch_data_model->htmlRedirect($redirect);
			}else{
				var_dump($data['errors']);
				die;
			}
		}
		$id = $this->catch_data_model->update_tuser_follow($data['users'],$tid);
		$current_count = $this->tfollow_model->countFollowInput($id);
		if($next_cursor == 0 || $current_count>=400)//本ID结束或已入库400条
		{
			$next = $this->catch_data_model->nextFamousTid($id);
			if($next && $isMulFetch)
			{
				//继续向下
				$redirect = BASE_URL."/catch_data/catch_tuser_follow/{$next}/?isMulFetch=1&token={$token}&token_secret={$token_secret}";
				$this->catch_data_model->htmlRedirect($redirect);
			}else{
				die("--end--");
			}
		}else{
			//下一页
			$redirect = BASE_URL."/catch_data/catch_tuser_follow/{$tid}/?cursor={$next_cursor}&token={$token}&token_secret={$token_secret}";
			if($isMulFetch)
				$redirect = $redirect."&isMulFetch=1";
			$this->catch_data_model->htmlRedirect($redirect);
		}
	}
	/**
	 * 弃用
	 * */
	function createIndexCache()
	{
		$time = date("Y-m-d H:i:s");
		$html = file_get_contents("http://banweibo.com/index.php?f=1");
		if($html)
		{
// 			file_put_contents(FCPATH."cache/index.cae", $html."<!-- cache created on  {$time}-->");
		}
	}
	/**
	 * 获取当前存在话题的地点
	 * */
	function getTrendsAvailable()
	{
		$action = "trends_available";
		$param = array();
		$res = $this->catch_data_model->getData($action, $param);
		$this->catch_data_model->updateTrendsAvailable($res);
	}
	/**
	 * 更新城市的经纬度
	 * */
	function updatePlaceLonLat()
	{
		$this->catch_data_model->updatePlaceLonLat();
	}
	function updatePopularTweet()
	{
		$this->load->model("tweet_model");
		$this->tweet_model->updatePopularTweet();
	}
}

