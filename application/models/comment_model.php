<?php
/**
 * @author mike
 * 处理收藏
 * */
class Comment_model extends Base_model
{
	function __construct()
	{
		parent::__construct ();
		$this->tbData = "comments";
		$this->tbTweet = "tweet";
		$this->tbUser = "user";
	}
	function addComment($user_id,$tweet_id,$is_translate,$text,$wuid,$wname,$to_user_id,$to_user_name,$to_user_wuid)
	{
		$vArr = array(
		"user_id"=>$user_id,
		"tweet_id"=>$tweet_id,
		"text"=>$text,
		"is_translate"=>$is_translate,
		"time"=>time()
		);
		$this->load->model('user_model');
		$user = $this->user_model->getUserInfo($user_id);
		$vArr ['weibo_uname'] = $user['weibo_uname'];
		$vArr ['weibo_profile_image'] = $user['weibo_profile_image'];
		$vArr ['weibo_uid'] = $user['weibo_id'];
		if($to_user_id)
		{
			$vArr ['to_user_id'] = $to_user_id;	
			$vArr ['to_user_name'] = $to_user_name;
			$vArr ['to_user_wuid'] = $to_user_wuid;
		}
		$res = $this->addRecords($vArr, $this->tbData);
		if($res)
		{
			$this->db->query("update {$this->tbTweet} set comm_count=comm_count+1 where id =$tweet_id limit 1");
// 			$this->db->query("update {$this->tbUser} set comment_count=comment_count+1 where id =$user_id limit 1");
// 			if($is_translate)
// 				$this->db->query("update {$this->tbUser} set translate_count=translate_count+1 where id =$user_id limit 1");
			if($to_user_id)
			{
				$this->db->query("update {$this->tbUser} set reply_notification=reply_notification+1 where id =$to_user_id limit 1");
			}
			if($is_translate == 2)
			{
				$this->updateTweetTranslate($tweet_id,$text,$user_id,$wuid,$wname);
			}
			elseif($is_translate == 3){
				//同步到twitter
				$this->load->model("tweet_model");
				$tinfo = $this->tweet_model->getTweetInfoByTid($tweet_id);
				$this->commentToTwitter("@{$tinfo['screen_name']} ".$text,$tinfo['tweet_id'],$res,$proxy =true);
			}
		}
		return $res;
	}
	function delComment($tweet_id,$comm_id)
	{
		$res = $this->delRecords($this->tbData,"id={$comm_id}");
		if($res)
		{
			$this->db->query("update {$this->tbTweet} set comm_count=comm_count-1 where id =$tweet_id limit 1");
		}
		return $res;
	}
	function updateTweetTranslate($tweet_id,$text,$user_id,$wuid,$wname)
	{
		$vArr = array("translate"=>$text,
				"translate_uid"=>$user_id,
				"translate_wuid"=>$wuid,
				"translate_uname"=>$wname);
		return $this->updateRecords($this->tbTweet,$vArr,"id={$tweet_id}");
	}
	function getCommList($tweet_id,$row)
	{
		$pageSize = ROW_SIZE;
		$offset = $pageSize * ($row-1);
		$res = $this->getFiledValues("*",$this->tbData,"tweet_id={$tweet_id} order by id desc limit {$offset},{$pageSize}");
		if(!$res)return array();
		foreach ($res as $key=>$one)
		{
			$res [$key] ['weibo_url']= "http://weibo.com/u/{$res [$key] ['weibo_uid']}";
			if($res [$key] ['to_user_wuid'])
				$res [$key] ['to_user_wurl']= "http://weibo.com/u/{$res [$key] ['to_user_wuid']}";
		}
		return $res;
	}
	/**
	 * 获取回复我的列表
	 * */
	function getReplyList($uid,$row)
	{
		$pageSize = ROW_SIZE;
		$offset = $pageSize * ($row-1);
		$comments = $this->getFiledValues("*",$this->tbData,"to_user_id={$uid} order by id desc limit {$offset},{$pageSize}");
		if(!$comments)return array();
		return $this->renderComments($comments);
	}
	/**
	 * 获取我发出的评论
	 * */
	function getSendedList($uid,$row)
	{
		$pageSize = ROW_SIZE;
		$offset = $pageSize * ($row-1);
		$comments = $this->getFiledValues("*",$this->tbData,"user_id={$uid} order by id desc limit {$offset},{$pageSize}");
		if(!$comments)return array();
		return $this->renderComments($comments);
	}
	/**
	 * 在评论数据中加入tweet数据
	 * */
	protected function renderComments($comments)
	{
		foreach ($comments as $one)
		{
			$idArr [] = $one['tweet_id'];
		}
		$this->load->model("tweet_model");
		$res = $this->tweet_model->getTweetByIdstr(implode(",",$idArr));
		foreach($res as $key=>$one)
		{
			$tweet [$one['id']] = $one;
			unset($tweet[$key]);
		}
		//合并数组
		foreach ($comments as $key=>$one)
		{
			$data [$key] = $tweet[$one['tweet_id']];
			foreach($one as $key1 =>$value)
			{
				$data [$key] ["comm_".$key1] = $value;
			}
		}
		return $data;
	}
	/**
	 * 获取待审核的翻译
	 * */
	function getTranslateTweet($p)
	{
		$pagesize = PAGESIZE;
		$p = $p == 0 ? 1 : $p;
		$offset = ($p - 1) * PAGESIZE;
		$res = $this->fetchRecord("select {$this->tbData}.*,{$this->tbTweet}.text as t_text,{$this->tbTweet}.translate from {$this->tbData},{$this->tbTweet} where {$this->tbTweet}.id = {$this->tbData}.tweet_id AND {$this->tbData}.is_translate=1 limit {$offset},{$pagesize}");
		return $res;
	}
	function commentToTwitter($text,$tid,$comment_id,$proxy =true)
	{
		if($proxy==true)
		{
			$this->load->model("catch_data_model");
			$param = array("status"=>urlencode($text),"in_reply_to_status_id"=>$tid);
			if($this->common_model->checkTwitterLogin())
			{
				$param = $this->common_model->addTwitterTokenParam($param);
			}
			$res = $this->catch_data_model->getData('post_tweet', $param);
			if(isset($res['id_str']) && $res['id_str'])
			{
				return $this->updateRecords($this->tbData,array("c_tid"=>intval($res['id_str'])),"id={$comment_id}");
			}
			return false;
		}
		else{
			//TODO
			$twitter_connect = $this->common_model->getTwitterConnection();
		}
	}
}