<?php
/**
 * @author mike
 * 处理收藏
 * */
class Fav_model extends Base_model
{
	function __construct()
	{
		parent::__construct ();
		$this->tbData = "fav";
		$this->tbUser = "user";
	}
	function addFav($uid,$tweet_id)
	{
		$vArr = array(
		"uid"=>$uid,
		"tweet_id"=>$tweet_id,
		"add_time"=>time()
		);
		$res = $this->addRecords($vArr, $this->tbData);
		if($res)
		{
			$this->db->query("update {$this->tbUser} set fav_count=fav_count+1 where id =$uid limit 1");
		}
		return $res;
	}
	function delFav($uid,$fav_id)
	{
		$res = $this->delRecords($this->tbData,"id={$fav_id}");
		if($res)
		{
			$this->db->query("update {$this->tbUser} set fav_count=fav_count-1 where id =$uid limit 1");
		}
		return $res;
	}
	function getFavList($uid,$row)
	{
		$pageSize = ROW_SIZE;
		$offset = $pageSize * ($row-1);
		$res = $this->getFiledValues("*",$this->tbData,"uid={$uid} order by id desc limit {$offset},{$pageSize}");
		if(!$res)return array();
		foreach ($res as $one)
		{
			$data [$one['tweet_id']] = array(
			'fav_id'=>$one['id'],
			'fav_time'=>date("Y-m-d H:i:s",$one['add_time'])
			);
		}
		unset($res);
		$ids = implode(",",array_keys($data));
		$this->load->model('tweet_model');
		$feed = $this->tweet_model->getTweetByIdstr($ids);
		foreach ($feed as $key=>$one)
		{
			$feedNew [$one['id']] = $one;
		}
		unset($feed);
		foreach ($data as $key=>$one)
		{
			$data[$key] = array_merge($data[$key],$feedNew[$key]);
		}
		return $data;
	}
}