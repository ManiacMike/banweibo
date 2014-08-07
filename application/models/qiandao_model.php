<?php
/**
 * @author mike
 * 签到
 * */
class Qiandao_model extends Base_model
{
	private $tbData;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = "qiandao";
		$this->tbLog = "qiandao_user";
		$this->tbUser = "tuser";
	}
	/**
	 * 添加一个签到统计到memcache
	 * */
	function add($tuid){
		$memcache = new memcache;
		$mem_res = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		$key = "qiandao_num_".$tuid;
		$date = date("Ymd");
		if($mem_res){
			if($memcache->get($key)){
				$memcache->increment($key);
				$res = $memcache->get($key);
			}else{
				//添加签到缓存
				$memcache->set($key,1,MEMCACHE_COMPRESSED,0);
				//添加到日志表
				$res = $this->log($tuid,0,$date,"add");
				if($res == true){
					$res = 1;
				}
			}
			//添加用户记录表
			if(!empty($_SESSION['uid']) && $res!=false){
				$this->logUser($_SESSION['uid'],$tuid,$date,$res);
			}
			$memcache->close();
			return $res;
		}
	}
	/**
	 * 获取签到数据
	 * */
	function get($tuid){
		$memcache = new memcache;
		$mem_res = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		$key = "qiandao_num_".$tuid;
		if($mem_res){
			if($memcache->get($key)){
				return $memcache->get($key);
			}else{
				return 0;
			}
			$memcache->close();
		}
		return false;
	}
	/**
	 * 添加签到数据
	 * */
	function log($tuid,$num,$date,$op){
		if($op == 'add'){
			$vArr = array(
					"uid"=>$tuid,
					"date"=>$date,
					"num"=>$num);
			return $this->addRecords($vArr,$this->tbData);
		}elseif($op == 'update'){
			return $this->updateRecords($this->tbData,array("num"=>$num),"uid = '{$tuid}' && `date` = '{$date}'");
		}
	}
	/**
	 * 更新今天的数据，并重置
	 * */
	function initLog(){
		$memcache = new memcache;
		$mem_res = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_res){
			$date = date("Ymd",strtotime("-1 day"));
// 			$date = date("Ymd");
			$res = $this->getFiledValues("*",$this->tbData,"`date` = '$date'");
			foreach($res as $key=>$one)
			{
				$key = "qiandao_num_".$one['uid'];
				$num = $memcache->get($key);
				if($num){
					echo $num."<br>";
					$this->updateRecords($this->tbData,array("num"=>$num),"id={$one['id']}");
					$memcache->delete($key);
				}
			}
		}
	}
	function updateQiandaoOrder(){
		$date = date("Ymd",strtotime("-1 day"));
// 		$date = date("Ymd");
		$res = $this->getFiledValues("*",$this->tbData,"`date` = '$date' order by num desc,id");
		foreach ($res as $key=>$one)
		{
			$this->updateRecords($this->tbUser,array("qiandao_order"=>$key+1),"id={$one['uid']}");
		}
	}
	/**
	 * 统计用户数据
	 * */
	function logUser($uid,$tuid,$date,$num){
		$vArr = array("uid"=>$uid,
				"tuid"=>$tuid,
				"date"=>$date,
				"num"=>$num);
		return $this->addRecords($vArr,$this->tbLog);
	}
	/**
	 * 获取用户签到记录
	 */
	function getUserRecords($uid){
		$res = $this->fetchRecord("select qiandao_user.*,tuser.name from `qiandao_user`,`tuser` where `qiandao_user`.tuid = `tuser`.id && `qiandao_user`.uid='$uid' order by `qiandao_user`.id desc");
		if($res){
			foreach($res as &$one){
				if($one['date'] == date("Ymd")){
					$one['date_s'] = "今天";
				}elseif($one['date'] ==  date("Ymd",strtotime("-1 day"))){
					$one['date_s'] = "昨天";
				}else{
					$one ['date_s'] = substr($one ['date'], 0,4)."年".substr($one ['date'], 4,2)."月".substr($one ['date'], 6,2)."日";
				}
			}
		}
		return $res;
	}
}