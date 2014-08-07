<?php
/**
 * @author mike
 * 接口数据请求处理类
 * */
class Catch_data_model extends Base_model
{
	private $tbData;
	private $tbTweet;
	public $apiId;
	function __construct()
	{
		parent::__construct ();
		define ( 'API_URL', 'http://yuandupi.com/twitter_api/api.php' );
		define('IMAGE_API','http://yuandupi.com/image_api.php');
		$this->tbData = 'tuser';
		$this->tbTweet = 'tweet';
		$this->tbTfollow = 'tuser_follow';
		$this->apiId = 297964934;//MikeChang0125的twitter id
	}
	/**
	 * 获取某一系统账户关注的twitterID数组
	 * 
	 * */
	function getAllFollow($t_account=1)
	{
		$param = array ( 
			'user_id' => $this->apiId , 
			'cursor' => - 1  //页数
		);
		if($t_account !=1)
		{
			$param = $this->common_model->alterSysUid($param,$t_account);
		}
		$data = $this->getData ( 'get_follow', $param );
		return $data ['ids'];
	}
	function getTuserOrderUpdate($num){
		return $this->getFiledValues("tid",$this->tbData,"`if_error` = 0 order by update_time limit {$num}");
	}
	/**
	 * 更新/插入twitter人物信息
	 * @param $tid twitter的ID
	 * @param $onlyAdd 是否只进行添加
	 * */
	public function updateUserInfo($tid,$onlyAdd = false)
	{
		$res = $this->getSingleFiledValues ( '*', $this->tbData, "tid={$tid}" );
		//是否只更新
		if($onlyAdd && $res)
		{
			return;
		}
		$request = $this->common_model->addRandomToken(array (
			'id' => $tid 
		));
		$vArr = $this->getData ( 'userinfo', $request );
		if(!$vArr) die("api error");
		if(isset($vArr ['errors']) || isset($vArr ['error']))
		{
			if($vArr['errors'][0]['code'] == 88 || $vArr['errors'][0]['code'] == 89)
			{}else{
				$this->common_model->markTuserError($res['id']);
			}
			var_dump($vArr);
			return false;
		}
		$vArr ['tid'] = $vArr ['id'];
		//删除不需要的字段
		unset ( $vArr ['id'], $vArr ['id_str'], $vArr ['status'],$vArr['following'],$vArr['follow_request_sent'],$vArr['notifications'],$vArr['entities']);
		$vArr = array_map ( array ( 
			$this , 
			'boolToInt' 
		), $vArr );
		$vArr ['update_time'] = time();
		if ($res)
		{
			$this->updateRecords ( $this->tbData, $vArr, "`id`={$res['id']}" );
		}
		else
		{
			$vArr ['profile_image_url_128'] = str_replace ( "_normal", "_reasonably_small", $vArr ['profile_image_url'] );
			$vArr ['profile_image_url_ori'] = str_replace ( "_normal", "", $vArr ['profile_image_url'] );
			$this->db->insert ( $this->tbData, $vArr );
		}
//通过替换profile_image_url字段可以直接得到128和ori
//		$this->catchProfileImage ( $tid );
	}
	function updateUserInfoByScreenName($screen_name)
	{
		$res = $this->getSingleFiledValues ( '*', $this->tbData, "screen_name='{$screen_name}'" );
		$request = $this->common_model->addRandomToken(array (
			'screen_name' => $screen_name 
		));
		$vArr = $this->getData ( 'userinfo', $request );
		if(!$vArr) die("api error");
		if(isset($vArr ['errors']) || isset($vArr ['error']))
		{
			if($vArr['errors'][0]['code'] == 88 || $vArr['errors'][0]['code'] == 89)
			{}else{
				$this->common_model->markTuserError($res['id']);
			}
			var_dump($vArr);
			return false;
		}
		$vArr ['tid'] = $vArr ['id'];
		//删除不需要的字段
		unset ( $vArr ['id'], $vArr ['id_str'], $vArr ['status'],$vArr['following'],$vArr['follow_request_sent'],$vArr['notifications'],$vArr['entities']);
		$vArr = array_map ( array ( 
			$this , 
			'boolToInt' 
		), $vArr );
		$vArr ['update_time'] = time();
		if ($res)
		{
			$this->updateRecords ( $this->tbData, $vArr, "`id`={$res['id']}" );
		}
		else
		{
			$vArr ['profile_image_url_128'] = str_replace ( "_normal", "_reasonably_small", $vArr ['profile_image_url'] );
			$vArr ['profile_image_url_ori'] = str_replace ( "_normal", "", $vArr ['profile_image_url'] );
			$this->db->insert ( $this->tbData, $vArr );
		}
	}
	/**
	 * 头像图片入库，采集
	 * @param $tid twitter的ID
	 * @param $mode update更新模式 catch模式更新数据库并采集到本地
	 * */
	protected function catchProfileImage($tid, $mode = 'catch')
	{
		$param = array (
			'user_id' => $tid , 
			'size' => 'bigger' 
		);
		$vArr ['profile_image_url_128'] = str_replace ( "bigger", "reasonably_small", $this->getData ( 'profile_image', $param ) );
		$param ['size'] = 'original';
		$vArr ['profile_image_url_ori'] = $this->getData ( 'profile_image', $param );
		$this->updateRecords ( $this->tbData, $vArr, "tid={$tid}" );
		if ($mode == 'catch')
		{
			$fileFolder = FCPATH . 'images/avatar/' . substr ( $tid, 0, 1 ) . '/';
			if (! file_exists ( $fileFolder )) mkdir ( $fileFolder ,0777,true);
			$this->catchImg ( $vArr ['profile_image_url_128'], $fileFolder . $tid . "128" );
			$this->catchImg ( $vArr ['profile_image_url_ori'], $fileFolder . $tid . "ori" );
		}
	}
	/**************************************************************
	 * @usertweets
	 * user_id 用户ID
	 * screen_name 用户域名
	 * since_id 返回大于该tweetID的
	 * count 数量，最大是200
	 * max_id 返回小于该tweetID的
	 * trim_user 将包括用户信息 默认true
	 * exclude_replies 包括回复信息 默认true
	 * contributor_details 
	 * include_entities true 包含媒体
	 * include_rts 包含转发
	 *
	 *
	 *
	 ***************************************************************/
	/**
	 * 更新/插入tweets
	 * @param $tid twitter的ID
	 * @param $mode update更新模式 fill填充模式
	 * */
	public function updateUserTweet($tid, $mode = 'update',$isMulFetch = 0)
	{
		//获取最近更新的tweetID
		$res = $this->getSingleFiledValues ( 'id,min_tweet_id,max_tweet_id', $this->tbData, "tid={$tid}" );
		$config = array ( 
			'id' => $tid , 
			"include_rts"=>1,
			"include_entities"=>1,
			"exclude_replies"=>0,
			'count' => 200
		);
		//fill表示是填充模式
		if ($mode == 'fill')
		{
			if ($res ['min_tweet_id'] != 0)
			{
				$config ['max_id'] = $res ['min_tweet_id'];
			}
		}
		elseif ($mode == 'update')
		{
			if ($res ['max_tweet_id'] == 0)
			{
				return false; //更新失败
			}
			$config ['since_id'] = $res ['max_tweet_id'];
		}
		$config = $this->common_model-> addRandomToken($config);
		$vArr = $this->getData ( 'usertweets', $config );
		if (! $vArr) {
			//更新主表的导入tweet数
			$this->updateTweetCount ( $tid );
			return $res['id'];
		}
		$vArr = $this->formatTweet ( $vArr ,$res['id'] );	
		$num = count ( $vArr );
		//插入
		foreach ( $vArr as $key => $value )
		{
			if (! $this->checkTwitteridExist ( $value ['tweet_id'] ))
			{
//				if(isset($value['source_image']))
//				{
//					$value ['image'] = $this->catchTweetImg($value['tweet_id'],$value['source_image']);
//				}
				$this->db->insert ( $this->tbTweet, $value );
			}
		}
		//更改标记的tweet_id
		$max = $vArr [0] ['tweet_id'];
		if ($max > $res ['max_tweet_id'])
		{
			$this->updateRecords ( $this->tbData, array ( 
				'max_tweet_id' => $max 
			), "tid={$tid}" );
		}
		$min = $vArr [$num - 1] ['tweet_id'];
		if ($min < $res ['min_tweet_id'] || $res ['min_tweet_id'] == 0)
		{
			$this->updateRecords ( $this->tbData, array ( 
				'min_tweet_id' => $min 
			), "tid={$tid}" );
		}
		//递归
		if ($num > 1)
		{
			$this->htmlRedirect($isMulFetch?BASE_URL."/catch_data/tweet/{$tid}/?action={$mode}&isMulFetch=1":BASE_URL."/catch_data/tweet/{$tid}/?action={$mode}");
			//$this->updateUserTweet ( $tid, $mode );
		}
		else
		{
			//删除memcache缓存
			$memcache = new memcache;
			if($memcache ->connect('localhost', $this->config->item('memcache_port'))){
				$memcache->delete("feed_cache_{$res['id']}_1");
			}
			//更新主表的导入tweet数
			$this->updateTweetCount ( $tid );
			return $res['id'];
		}
	}
	//获取主时间线 每分钟一次
	function updateHomeLine($mode = "update",$sysId=1)
	{
		$config = array (
			"include_rts"=>1,
			"include_entities"=>1,
			"exclude_replies"=>0,
			'count' => 200,
			'trim_user'=>0,
		);
		if($mode == "update")
		{
			$markFile = $sysId==1?FCPATH."cache/sinceid.cae":FCPATH."cache/sinceid_{$sysId}.cae";
			$sinceId = file_get_contents($markFile);
			if($sinceId != 0)
			{
				$config ['since_id'] = $sinceId;
			}
		}
		else
		{
			$markFile = $sysId==1?FCPATH."cache/endid.cae":FCPATH."cache/endid_{$sysId}.cae";
			$endId = file_get_contents($markFile);
			if($endId != 0)
			{
				$config ['max_id'] = $endId;
			}
		}
		if($sysId!=1)
		{
			$config = $this->common_model->addSysToken($config,$sysId);
		}
		$vArr = $this->getData ( 'homeline', $config );
		$vArr = $this->formatTweet ( $vArr ,0 );
		$num = count ( $vArr );
		//插入
		foreach ( $vArr as $key => $value )
		{
			if (! $this->checkTwitteridExist ( $value ['tweet_id'] ) && $value['uid'] !=37)
			{
				$this->db->insert ( $this->tbTweet, $value );
			}
		}
		$this->updateTweetCount ( 0 );
		echo $vArr [0] ['tweet_id'];
		if($mode == "update")
			file_put_contents($markFile,$vArr [0] ['tweet_id']);
		else
			file_put_contents($markFile,$vArr [count($vArr)-1] ['tweet_id']);
	}
	/**
	 * 抓取tweet的图片
	 * @param $tweet_id 
	 * @param $source 图片源地址
	 * @param $proxy_on 是否开启代理
	 * @return 抓取后的地址
	 * */
	protected function catchTweetImg($tweet_id,$source,$proxy_on = false)
	{
		if($proxy_on)
			$url=IMAGE_API."?url=".urlencode($source);
		else
			$url = $source;
		$fileFolder = FCPATH . 'images/tweet/' . substr ( md5($tweet_id), 0, 1 ) . '/';
		if (! file_exists ( $fileFolder )) mkdir ( $fileFolder ,0777,true);
		$file = $fileFolder.substr ( md5($tweet_id), 20 );
		if($this->catchImg($url,$file))
		{
			return str_replace(FCPATH , "",$file);
		}
		return false;
	}
	/**
	 * 根据tweet表中的数据更新tuser表
	 * @param $tid  twitter的用户ID 0表示更新全部
	 * */
	protected function updateTweetCount($tid)
	{
		if($tid == 0)
		{
			$res = $this->fetchRecord("SELECT uid, COUNT( * ) AS num
													FROM  `tweet` 
													WHERE 1 
													GROUP BY uid ");
			foreach ($res as $one)
			{
				$this->updateRecords ( $this->tbData, array ( 
					"input_statuses_count" => $one['num'] 
				), "id={$one['uid']}" );
			}
		}else{
			$res = $this->countRecord ( $this->tbTweet, "tuid = {$tid}" );
			$this->updateRecords ( $this->tbData, array ( 
				"input_statuses_count" => $res 
			), "tid={$tid}" );
		}
	}
	/**
	 * 从代理接口获取数据并数组化
	 * @param $action请求
	 * @param $param请求参数
	 * */
	function getDataOld($action, $param)
	{
		$apiUrl = API_URL . '?action=' . $action;
		foreach ( $param as $key => $value )
		{
			$apiUrl .= "&$key=$value";
		}
		$json = file_get_contents ( $apiUrl );
		if ($action == 'profile_image')
		{
			return $json;
		}
		return json_decode ( $json, true );
	}
	/**
	 * 直接获取数据并数组化
	 * @param $action请求
	 * @param $param请求参数
	 * */
	function getData($action, $param)
	{
		if(isset($param['token']) && isset($param['token_secret']) && $param['token']!="" && $param['token_secret']!="")
		{
			$this->load->library("twitter",array("token"=>$param['token'],
			"token_secret"=>$param['token_secret']));
			unset($param['token'],$param['token_secret']);
		}else{
			$this->load->library("twitter");
		}
		return $this->twitter ->getData($param,$action);
	}
	/**
	 * API采集的数组转成数据库需要的
	 * @param $data 方法getData返回的数据
	 */
	function formatTweet($data ,$uid)
	{
		$return = array();
		foreach ( $data as $key => $value )
		{
			$new = array (
				'uid' =>$uid?$uid:$this->getUidByTuid($value ['user'] ['id']),
				'tweet_id' => $value ['id_str'] , 
				'text' => $value ['text'] , 
				'created_at' => $value ['created_at'] , 
				'truncated' => $this->boolToInt ( $value ['truncated'] ) , 
				'tuid' => $value ['user'] ['id'] , 
				'name' => $value ['user'] ['name'] , 
				'screen_name' => $value ['user'] ['screen_name'] , 
				'profile_image_url' => $value ['user'] ['profile_image_url'] , 
				'source' =>$value ['source'],
				//'add_time' => time () 
			);
			if(isset($value['retweeted_status']))
			{
				$new ['retweet_name'] = $value['retweeted_status']['user']['name'];
				$new ['retweet_tuid'] = $value['retweeted_status']['user']['id'];
				$new ['retweet_screen_name'] = $value['retweeted_status']['user']['screen_name'];
				$new ['retweet_profile_image_url'] = $value['retweeted_status']['user']['profile_image_url'];
				$new ['retweet_description'] = $value['retweeted_status']['user']['description'];
				$new ['retweet_created_at'] = $value['retweeted_status']['created_at'];
			}
			//找到所含图片地址
			if(isset($value['entities']['media']))
			{
				foreach ($value['entities']['media'] as $one)
				{
					if($one ['type'] == "photo")
					{
						$new ['source_image'] = $one['media_url'];
						break;
					}
				}
			}
			unset($value['entities']['media']);
			$new ['entities'] = json_encode($value['entities']);
			$return [$key] = $new;
		}
		return $return;
	}
	/**
	 *更新twitter id为$tid的关注列表
	 * @param $data $tid 
	 */
	function update_tuser_follow($data,$tid)
	{
		$this->load->model("tuser_model");
		$this->load->model("tfollow_model");
		$res = $this->tuser_model->getIdByTid($tid);
		foreach ($data as $key=>$one)
		{
			$vArr = array("f_tid"=>$one['id'],
			"name"=>$one['name'],
			"screen_name"=>$one['screen_name'],
			"profile_image_url"=>$one['profile_image_url'],
			"description"=>$one['description'],
			"statuses_count"=>$one['statuses_count'],
			"friends_count"=>$one['friends_count'],
			"followers_count"=>$one['followers_count'],
			"verified"=>($one['verified']==false?0:1),
			);
			$vArr ['tid'] = $tid;
			$vArr ['tuid'] = $res['id'];
			$this->addRecords($vArr,$this->tbTfollow);
		}
		$this->tfollow_model->updateTuserFriendCount($res['id']);
		return $res['id'];
	}
	/**
	 *检查是否存在twitter_id
	 * @param $twitter_id 
	 */
	private function checkTwitteridExist($tweet_id)
	{
		$res = $this->getSingleFiledValues ( '*', $this->tbTweet, "tweet_id={$tweet_id}" );
		if ($res) return true;
		else
			return false;
	}
	private function boolToInt($data)
	{
		if ($data === true)
		{
			return 1;
		}
		elseif ($data === false)
		{
			return 0;
		}
		elseif ($data === null)
		{
			return '';
		}
		return $data;
	}
	private function catchImg($source, $put)
	{
		$img = file_get_contents ( str_replace ( "https", 'http', $source ) );
		$ext = $this->getFileExn ( $source );
		return file_put_contents ( $put . ".{$ext}", $img );
	}
	/**
	 * 获取文件名的后缀名
	 * */
	private function getFileExn($file_name)
	{
		$extend = explode ( ".", $file_name );
		$va = count ( $extend ) - 1;
		return $extend [$va];
//		return $extend [$va]!=""?$extend [$va]:"jpg";
	}
	/**
	 * 获取下一个tid
	 * */
	function getNextTuid($uid)
	{
		define("DATAFile", FCPATH."cache/datafill_manage.cae");
		$row = unserialize(file_get_contents(DATAFile));
		$nextUid = 0;$flag = false;
		foreach ($row as $one)
		{
			if($flag == true)
			{
				$nextUid = $one['id'];
				break;
			}
			if($one['id'] == $uid)$flag = true;
		}
		if($nextUid == 0)
			return false;
		$res = $this->getSingleFiledValues("tid",$this->tbData,"id={$nextUid}");
		if($res)
			return $res['tid'];
		else 
			return false;
	}
	/**
	 * 根据当前tuid获取人气排名排在这之后的uid
	 * */
	function nextFamousTid($tuid)
	{
		$res = $this->getSingleFiledValues("id,tid",$this->tbData,"follower_count_b >0 && friends_count!=0 && friend_count_input =0 && `if_error` = 0 && `protected` = 0 order by follower_count_b desc");

//		$res = $this->getSingleFiledValues("follower_count_b",$this->tbData,"id=$tuid");
//		$res1 = $this->getSingleFiledValues("id,tid",$this->tbData,"follower_count_b <{$res['follower_count_b']} && id!={$tuid} order by follower_count_b desc");
		return $res?$res['tid']:false;
	}
	function getUidByTuid($tuid)
	{
		$res = $this->getSingleFiledValues("id",$this->tbData,"tid={$tuid}");
		return $res?$res ['id']:0;
	}
	function htmlRedirect($url)
	{
		echo"<html><head><meta http-equiv=\"refresh\" content=\"0;{$url}\"> </head></html>";
		die;
	}
	/**
	 * 更新存在话题的地点
	 * */
	function updateTrendsAvailable($data)
	{
		$this->updateRecords("place",array("if_show"=>0),"1");
		foreach ($data as $one)
		{
			$dbRes = $this->getSingleFiledValues("*","place","`woeid` = '{$one['woeid']}'");
			if(!$dbRes)
			{
				$one ['placeTypeCode'] = $one['placeType']['code'];
				$one ['placeTypeName'] = $one['placeType']['name'];
				unset($one['placeType'],$one['url']);
				$one ['if_show'] = 1;
				$this->addRecords($one,'place');
			}else{
				$vArr = array("if_show"=>1);
				$this->updateRecords("place",$vArr,"`woeid` = '{$one['woeid']}'");
			}		
		}
	}
	/**
	 * 更新城市的经纬度
	 * */
	function updatePlaceLonLat()
	{
		$this->load->library('map');
		$dbRes = $this->getFiledValues("*","place","lon ='' && placeTypeCode = 7");
		foreach ($dbRes as $one)
		{
			//获取城市经纬度
			$res = $this->map->getLonLatByCityName($one ['name']);
			$vArr ['lon'] = $res[0]*10000000;
			$vArr ['lat'] = $res[1]*10000000;
			$this->updateRecords("place",$vArr,"`woeid` = '{$one['woeid']}'");
		}
	}
}