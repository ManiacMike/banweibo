<?php
/**
 * @author mike
 * 后台数据处理类
 * */
class Admin_model extends Base_model
{
	private $tbData;
	private $tbTweet;
	public $keyword;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'tuser';
		$this->tbTweet = 'tweet';
		$this->tbNation = 'nation_select';
		$this->tbWhatsit = 'whatsit_select';
		$this->tbReco = ' recommend_tuser';
		$this->tbHot = 'hot';
		define ( 'PAGESIZE', 20 );
		define ( 'AUTH_EXPIRE', 3600 * 24 );
	}
	/**
	 *获取user的账户
	 *
	 */
	function getTusers()
	{
		$p = $this->pagination->cur_page;
		$p = $p == 0 ? 1 : $p;
		$offset = ($p - 1) * PAGESIZE;
		if($this->keyword == "未加标签的认证账号")
		{
			$where = "verified = 1 && main_cid='' && sub_cid ='' order by followers_count desc";
		}elseif($this->keyword == "未加标签的非认证账号")
		{
			$where = "verified = 0 && main_cid='' && sub_cid ='' order by followers_count desc";
		}else{
			$where =  $this->keyword?"screen_name like '%{$this->keyword}%' || name like '%{$this->keyword}%' ||  FIND_IN_SET( '{$this->keyword}', main_cid )||  FIND_IN_SET( '{$this->keyword}', sub_cid ) order by index_order,id ":"1 order by index_order,id ";
		}
		$res = $this->getFiledValues ( "*", $this->tbData, "$where limit {$offset}," . PAGESIZE );
		foreach ($res as $key=>$one)
		{
			$res [$key]['urlencoded_name'] = urlencode($one['name']);
			$res [$key]['reco'] = $this->getRecoByTid($res [$key]['tid']);
		}
		return $res;
	}
	function getRecoByTid($tid){
		$res = $this->getSingleFiledValues("tip",$this->tbReco,"tuid=$tid");
		return $res?$res['tip']:"";
	}
	/**
	 *获取use总数
	 *
	 */
	function getTuserCount()
	{	
		if($this->keyword == "未加标签的认证账号")
		{
			$where = "verified = 1 && main_cid='' && sub_cid ='' ";
		}elseif($this->keyword == "未加标签的非认证账号")
		{
			$where = "verified = 0 && main_cid='' && sub_cid ='' ";
		}else{
			$where =  $this->keyword?"screen_name like '%{$this->keyword}%' || name like '%{$this->keyword}%' ||  FIND_IN_SET( '{$this->keyword}', main_cid )||  FIND_IN_SET( '{$this->keyword}', sub_cid )":"1";
		}
		$res = $this->countRecord ( $this->tbData, $where );
		return $res;
	}
	function getUserPageStr($module = "index")
	{
		$this->load->library ( 'pagination' );
		$config ['base_url'] = BASE_URL . "/administration/$module/";
		if( $this->keyword )
		{
			$config ['base_url'] .=urlencode($this->keyword)."/";
		}else {
			$config ['base_url'] .="0/";
		}
		$config ['uri_segment'] = 4;
		$config ['total_rows'] = $this->getTuserCount ();
		$config ['per_page'] = PAGESIZE;
		$this->pagination->initialize ( $config );
		$pageStr = $this->pagination->create_links ();
		return $pageStr;
	}
	function createRand()
	{
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ_';
		for($i = 0; $i < 4; $i ++)
		{
			$code .= $pattern {mt_rand ( 0, 61 )};
		}
		return $code;
	}
	function checkAuth($auth_update)
	{
		$left = $auth_update + AUTH_EXPIRE - time ();
		if ($left <= 0)
		{
			return false;
		}
		$daysecond = 3600 * 24;
		$day = round ( $left / $daysecond );
		$hour = round ( ($left % $daysecond) / 3600 );
		$min = round ( ($left % 3600) / 60 );
		return $day . "天" . $hour . "小时" . $min . "分钟";
	}
	/**
	 * 获取单个ID对应字段的数据
	 * 
	 * */
	function getUserByColumn($id, $config)
	{
		$column = implode ( ",", array_keys ( $config ) );
		$res = $this->getSingleFiledValues ( $column, $this->tbData, "id={$id}" );
		foreach ( $config as $key => $value )
		{
			$config [$key] ['data'] = $res [$key];
		}
		return $config;
	}
	/**
	 * 更新user信息
	 * 
	 * */
	function updateUser($id, $data)
	{
		return $this->updateRecords ( $this->tbData, $data, "`id`={$id}" );
	}
	/**
	 * 更新列表信息
	 * */
	function updateHotsort($data)
	{
		foreach ( $data as $id => $value )
		{
			$value = $value == "" ? 999999 : $value;
			$vArr = array ( 
				"index_order" => $value 
			);
			$this->updateRecords ( $this->tbData, $vArr, "`id`={$id}" );
		}
	}
	/**
	 * 更新国家表信息
	 * */
	function updateNationSelect($tuid, $nation_id)
	{
		$res = $this->getSingleFiledValues ( "*", $this->tbNation, "id={$nation_id}" );
		$arr = $res ['value'] ? explode ( ",", $res ['value'] ) : array ();
		if (array_search ( $tuid, $arr ) === false)
		{
			$arr [] = $tuid;
			$this->updateRecords ( $this->tbNation, array ( 
				'value' => implode ( ",", $arr ) 
			), "id={$nation_id}" );
		}
	}
	/**
	 * 更新whatsit表信息
	 * */
	function updateWhatsitSelect($tuid, $whatArr)
	{
		foreach ( $whatArr as $id => $value )
		{
			$res = $this->getSingleFiledValues ( "*", $this->tbWhatsit, "id={$id}" );
			$arr = $res ['value'] ? explode ( ",", $res ['value'] ) : array ();
			if (array_search ( $tuid, $arr ) === false)
			{
				$arr [] = $tuid;
				$this->updateRecords ( $this->tbWhatsit, array ( 
					'value' => implode ( ",", $arr ) 
				), "id={$id}" );
			}
		}
	}
	/**
	 * 获取tweet数组
	 * @param $uid 用户ID
	 * */
	function getTweet($uid)
	{
		$p = $this->pagination->cur_page;
		$p = $p == 0 ? 1 : $p;
		$offset = ($p - 1) * PAGESIZE;
		if ($uid) $where = "uid = $uid";
		else
			$where = 1;
		$res = $this->getFiledValues ( "*", $this->tbTweet, "$where order by tweet_id desc limit {$offset}," . PAGESIZE );
		if ($res) $res = array_map ( array ( 
			$this , 
			"formatTweetToEdit" 
		), $res );
		return $res;
	}
	function getOneTweet($id)
	{
		return $this->getSingleFiledValues("*",$this->tbTweet,"id={$id}");
	}
	private function formatTweetToEdit($one)
	{
		return $one;
	}
	function getTweetCount($uid)
	{
		if ($uid) $res = $this->countRecord ( $this->tbTweet, "uid = $uid" );
		else
			$res = $this->countRecord ( $this->tbTweet, "1" );
		return $res;
	}
	function getTweetPageStr($uid)
	{
		$this->load->library ( 'pagination' );
		$config ['base_url'] = BASE_URL . '/administration/tweet/';
		$config ['base_url'] .=$uid."/";
		$config ['uri_segment'] = 4;
		$config ['total_rows'] = $this->getTweetCount ( $uid );
		$config ['per_page'] = PAGESIZE;
		$this->pagination->initialize ( $config );
		$pageStr = $this->pagination->create_links ();
		return $pageStr;
	}
	/**
	 * 更新热推信息
	 * @param $data 键值为tweet表id和热度的数组
	 * */
	function updateTweetHotsort($data)
	{
		foreach ( $data as $id => $value )
		{
			$value = $value == "" ? 0 : $value;
			$vArr = array ( 
				"hotsort" => $value 
			);
			$this->updateRecords ( $this->tbTweet, $vArr, "`id`={$id}" );
			$res = $this->getSingleFiledValues ( "*", $this->tbHot, "`tid`={$id}" );
			if ($res)
			{
				if ($value == 0)
				{
					$this->delRecords ( $this->tbHot, "`tid`={$id}" );
				}
			}
			else
			{
				if ($value != 0)
				{
					$this->addRecords ( array("tid"=>$id), $this->tbHot );
				}
			}
		}
	}
	/**
	 * 获取HOT表中的信息
	 * */
	function getHotTweet()
	{
		return $this->getFiledValues("*",$this->tbHot,"1");
	}
	/**
	 * 更新HOT表中的描述
	 * */
	function updateHotDesc($data)
	{
		foreach ( $data as $id => $value )
		{
			if($value)
				$this->updateRecords ( $this->tbHot, array('desc'=>$value), "`tid`={$id}" );
		}
	}
}