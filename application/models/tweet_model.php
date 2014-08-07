<?php
/**
 * @author mike
 * 读tweet表的类
 * */
class Tweet_model extends Base_model
{
	private $tbData;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'tweet';
		$this->tbHot = 'hot';
		$this->tbUser = 'tuser';
		$this->tbRt = 'retweet';
	}
	/**
	 * 获取全部人的feed
	 * @param $row 段 一页分几段
	 * */
	function getFeed($row,$tuid)
	{
		$memcache = new memcache;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_connect)
		{
			$mem_res = $memcache->get("feed_cache_{$tuid}_{$row}");
			if($mem_res !== false)
			{
				return $mem_res;
			}
		}
		$offset = ($row-1)*ROW_SIZE;
		$where = "";
		if($tuid)
			$where .="uid={$tuid} order by tweet_id desc limit {$offset},".ROW_SIZE;
		else
			$where .="1 order by tweet_id desc limit {$offset},".ROW_SIZE;
		$res = $this->getFiledValues("*",$this->tbData,$where);
		$res = array_map(array($this,'renderFeed'),$res);
		if($mem_connect)
		{
			$memcache->set("feed_cache_{$tuid}_{$row}",$res,MEMCACHE_COMPRESSED, $this->config->item('data_cache_time'));
		}
		return $res;
	}
	/**
	 * 获取某用户的时间线
	 * @param $uid
	 * */
	function getHomeline($row,$uid)
	{
		$this->load->model('relation_model');
		$tid_res = $this->relation_model->getFollowIds($uid);
		if(!$tid_res)return array();
		$tid = implode(",",$tid_res);
		$offset = ($row-1)*ROW_SIZE;
		$where ="uid in($tid) order by tweet_id desc limit {$offset},".ROW_SIZE;
		$res = $this->getFiledValues("*",$this->tbData,$where);
		$res = array_map(array($this,'renderFeed'),$res);
		return $res;
	}
	/**
	 * 实时获取用户时间线
	 * @param $uid
	 */
	function getRawHomeline($row = 1)
	{
		$this->load->model("catch_data_model");
		$data = $this->catch_data_model->getData("homeline",array(
		"page"=>$row,
		"count"=>ROW_SIZE,
		"token"=>$_SESSION['twitter_token']['access_token'],
		"token_secret"=>$_SESSION['twitter_token']['access_token_secret'],
		)
		);
		if(isset($data['errors']))return array();
		$data = $this->catch_data_model->formatTweet($data,$_SESSION['uid']);
		$data = array_map(array($this,'renderFeed'),$data);
		return $data;
	}
	/**
	 * 通过ID串获取feed
	 * */
	function getTweetByIdstr($ids)
	{
		$res = $this->getFiledValues("*",$this->tbData,"id in({$ids})");
		if($res)
			return array_map(array($this,'renderFeed'),$res);
		else
			return array();
	}
	/**
	 * 获取HOT表中的信息
	 * */
	function getHotTweet($cache = true)
	{	
		if($cache == true)
		{
			$memcache = new memcache;
			$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
			if($mem_connect)
			{
				$r = $memcache->get("hot_page_content");
				if($r){
					return $r;
				}
			}
		}
		$res = $this->fetchRecord("SELECT {$this->tbHot}.*,{$this->tbData}.* from {$this->tbHot},{$this->tbData} where {$this->tbHot}.tid={$this->tbData}.id order by {$this->tbData}.hotsort");
		$res = array_map(array($this,'renderFeed'),$res);
		$res = $this->renderHotFeed($res);
		if($cache == true){
			if($mem_connect)
			{
				$memcache->set("hot_page_content",$res,MEMCACHE_COMPRESSED,0);
				$memcache->close();
			}
		}
		return $res;
	}
	/**
	 * 获取最近评论最多的tweet
	 * */
	function getPopularTweet()
	{
		$memcache = new memcache;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_connect)
		{
			$r = $memcache->get("popular_tweet_content");
			if($r){
				return $r;
			}
		}
		return array();
	}
	/**
	 * 生成最近评论最多的tweet
	 * */
	function updatePopularTweet()
	{
		$memcache = new memcache;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		echo $dateLine = time() - 3600*24*3;
		$res = $this->getFiledValues("",$this->tbData,"comm_count>10 && comm_count<50000 &&  UNIX_TIMESTAMP(add_time) > '{$dateLine}' order by comm_count desc limit 20");
		$res = array_map(array($this,'renderFeed'),$res);
		$res = $this->renderHotFeed($res);
		if($mem_connect)
		{
			$memcache->delete("popular_tweet_user");
			$memcache->set("popular_tweet_content",$res,MEMCACHE_COMPRESSED,0);
			$memcache->close();
		}
		return $res;
	}
	/**
	 * 用原来的我们库里的ID获取原生的tweet_id
	 * */
	function getTweetInfoByTid($id)
	{
		$res = $this->getSingleFiledValues("tweet_id,screen_name",$this->tbData,"id={$id}");
		return $res?$res:false;
	}
	function renderHotFeed($data)
	{
		$new = array();
		$badge = array("badge-important","badge-warning","badge-success","badge-info","badge-inverse");
		$badge_key = 0;
		foreach ($data as $key=>$value)
		{
			if($badge_key >4)
				$badge_key=0;
			$value ['badge'] = $badge[$badge_key];
			$badge_key ++;			
			$new [$key+1] = $value;
		}
		return $new;
	}
	/**
	 * 根据推文获取相应热门的用户
	 * */
	function getHotUser($data,$cache_key='hot_page_people')
	{
		$memcache = new memcache;
		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_connect)
		{
			$r = $memcache->get($cache_key);
			if($r){
				return $r;
			}
		}
		$count = array();
		$return = array();
		foreach ($data as $one)
		{
			if(isset($count [$one['uid']]))
				$count [$one['uid']] +=1;
			else
				$count [$one['uid']] =0;
		}
		arsort($count);
		foreach ($count as $id=>$num)
		{
			$res = $this->getSingleFiledValues("id,name,screen_name,cname,intro,whatsit,profile_image_url_128",$this->tbUser,"id={$id}");
			$res ['num'] = $num;
			$return [] = $res;
		}
		if($mem_connect)
		{
			$memcache->set($cache_key,$return,MEMCACHE_COMPRESSED,0);
			$memcache->close();
		}
		return $return;
	}
	/**
	 * 获取转发记录
	 * */
	function getRtList($uid,$row)
	{
		$pageSize = ROW_SIZE;
		$offset = $pageSize * ($row-1);
		$res = $this->fetchRecord("select {$this->tbRt}.weibo_mid,{$this->tbRt}.created_at as rt_created_at,{$this->tbData}.* from {$this->tbRt},{$this->tbData} where {$this->tbRt}.tweet_id={$this->tbData}.id AND {$this->tbRt}.uid={$uid} order by {$this->tbRt}.id desc limit {$offset},{$pageSize}");
		if(!$res)return array();
		$res = $this->renderRtFeed(array_map(array($this,'renderFeed'),$res));
		return $res;
	}
	function renderRtFeed($data)
	{
		foreach ($data as $key=>$one)
		{
			$data [$key] ['rt_created_at'] = $this->relatively_date(date("Y-m-d H:i:s",$data [$key] ['rt_created_at']));
		}
		return $data;
	}
	/**
	 * 增加转发记录
	 * */
	function add_rt_record($tweet_id,$weibo_id,$weibo_mid,$created_at)
	{
		$vArr = array("tweet_id"=>$tweet_id,
		"uid"=>$_SESSION['uid'],
		"weibo_id"=>$weibo_id,
		"weibo_mid"=>$weibo_mid,
		"created_at"=>$created_at
		);
		return $this->addRecords($vArr,$this->tbRt);
	}
	/**
	 * 格式化tweet
	 * */
	function renderFeed($tweet)
	{
		$tweet ['profile_image_url'] = str_replace("normal","bigger",$tweet ['profile_image_url']);
		$tweet ['entities'] = json_decode($tweet ['entities'],true);
		$tweet ['created_at'] = $this->formatTime($tweet ['created_at']);
		if(isset($tweet ['retweet_tuid']) && $tweet ['retweet_tuid'])
			$tweet ['retweet_created_at'] = $this->formatTime($tweet ['retweet_created_at']);
		if(isset($tweet ['text_show']) && $tweet ['text_show'])
		{
			$tweet ['text'] = $this->$tweet ['text_show'];
			unset($this->$tweet ['text_show'],$tweet ['entities']);
		}else{
			$tweet= $this->formatText($tweet);
			unset($tweet ['entities']);
		}
		return $tweet;
	}
	function formatText($tweet)
	{
		if(isset($tweet ['retweet_tuid']) && $tweet ['retweet_tuid'])
			$tweet ['text'] = $this->str_replace_once( "RT @{$tweet ['retweet_screen_name']}: ","",$tweet ['text']);
		$tweet ['text'] = str_replace("RT ","//",$tweet ['text']);
		if(isset($tweet ['entities'] ['hashtags']))
		{
			foreach ($tweet ['entities'] ['hashtags'] as $one)
			{
				$tweet ['text'] = str_replace("#".$one['text'],"<a href=\"".BASE_URL."/search/tag/?q={$one['text']}\">#{$one['text']}</a>",$tweet ['text']);
			}
		}
		if(isset($tweet ['entities'] ['urls']))
		{
			foreach ($tweet ['entities'] ['urls'] as $one)
			{
				$tweet ['text'] = str_replace($one['url'],"<a title=\"{$one['display_url']}\" href=\"{$one['expanded_url']}\" rel=\"nofollow\" target=\"_blank\">{$one['url']}</a>",$tweet ['text']);
			}
		}
		if(isset($tweet ['entities'] ['user_mentions']))
		{
			foreach ($tweet ['entities'] ['user_mentions'] as $one)
			{
				$tweet ['text'] = str_ireplace("@".$one['screen_name'],"<a title=\"{$one['name']}\" href=\"".BASE_URL."/u/{$one['screen_name']}\">@{$one['screen_name']}</a>",$tweet ['text']);
			}
		}
		if(isset($tweet ['source_image']) && $tweet ['source_image'])
		{
			$tmp = explode("http://t.co",$tweet ['text']);
			$tweet ['text'] = str_replace("http://t.co".$tmp[count($tmp)-1],"",$tweet ['text']);
		}
		return $tweet;
	}
	/**
	 * 格式化时间
	 * */
	function formatTime($str)
	{
		return $this->relatively_date(date("Y-m-d H:i:s",strtotime($str)));
	}
	/**
	 * 相对时间
	 * */
	private function relatively_date($date) 
	{
		$a=explode(" ",$date);
		$d=$a[0];
		$time=$a[1];
		$b=explode("-",$d);
		$y=$b[0];
		$mo=$b[1];
		$d=$b[2];
		$c=explode(":",$time);
		$h=$c[0];
		$m=$c[1];
		$sec = time() - strtotime($date);
		if($sec<3600){
		return round($sec/60).'分钟前';
		}elseif($y.$mo.$d==date("Ymd")){
		return "今天 ".$h.":".$m;
		}elseif(($y.$mo==date("Ym"))&&($d==date("d")-1)){
		return "昨天 ".$h.":".$m;
		}elseif($y==date("Y")){
		return $mo."月".$d."日 ".$h.":".$m;
		}else{
		return $y."-".$mo."-".$d." ".$h.":".$m;
		}
	}
	private function str_replace_once($needle, $replace, $haystack) {
	   $pos = strpos($haystack, $needle);
	   if ($pos === false) {
	      return $haystack;
	   }
	   return substr_replace($haystack, $replace, $pos, strlen($needle));
	}
	function send_tweet($text,$proxy =true)
	{
		if($proxy==true)
		{
			$this->load->model("catch_data_model");
			$param = array("status"=>urlencode($text));
			if($this->common_model->checkTwitterLogin())
			{
				$param = $this->common_model->addTwitterTokenParam($param);
			}else{
				return false;
			}
			$res = $this->catch_data_model->getData('post_tweet', $param);
			if(isset($res['id_str']) && $res['id_str'])
			{
				return true;
			}
			return false;
		}
		else{
			//TODO
			$twitter_connect = $this->common_model->getTwitterConnection();
		}		
	}
}