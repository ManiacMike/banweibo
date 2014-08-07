<?php
/**
 * @author mike
 * 读tuser表的类
 * */
class Tfollow_model extends Base_model
{
	public $uid;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'tuser_follow';
		$this->tbTuser = 'tuser';
		$this->pageSize = 20;
	}
	/**
	 * 获取一个tid下库中已经导入的关注人数
	 * @param $tuid tuser表的ID
	 * */
	function countFollowInput($tuid)
	{
		return $this->countRecord($this->tbData,"tuid={$tuid}");
	}
	/**
	 * 更新tuser表中已经导入的关注人数
	 * @param $tuid tuser表的ID
	 * */
	function updateTuserFriendCount($tuid)
	{
		$count = $this->countFollowInput($tuid);
		$vArr = array("friend_count_input"=>$count);
		return $this->updateRecords($this->tbTuser,$vArr,"id={$tuid}");
	}
	function getFollowById($page)
	{
		$this->load->model('tuser_model');
		$id = $this->uid;
		$pageSize = $this->pageSize;
		$offset = $pageSize*($page-1);
		$res = $this->getFiledValues("*",$this->tbData,"tuid={$id} limit {$offset},{$pageSize}");
		$res = array_map(array($this,"renderFollow"),$res);
		return $res;
	}
	private function renderFollow($arr)
	{
		//图片
		$arr ['profile_image_url'] = str_replace("_normal","_bigger",$arr ['profile_image_url']);
		//按键形式
		if(isset($_SESSION['uid']) && $_SESSION['uid'])
			$this->tuser_model->uid=$_SESSION['uid'];
		$res = $this->getSingleFiledValues("id,intro",$this->tbTuser,"tid={$arr['f_tid']}");
		if($res)
		{
			$res = $this->tuser_model->checkFollow($res);
			$arr ['uid'] = $res ['id'];
			$arr ['intro'] = $res ['intro'];
			$arr ['button_type'] = "1";
			$arr ['isfollow'] = $res['isfollow'];
		}
		else
		{
			$this->load->model('recommend_model');
			$res1 = $this->recommend_model->getStatusByScreenName($arr['screen_name']);
			if($res1)
			{
				$arr ['button_type'] = "2";
				$arr ['status'] = $res1 ['status'];
			}
			else
			{
				$arr ['button_type'] = "0";
			}
		}
		//粉丝数
		$arr ['followers_count'] = $arr ['followers_count']>100000?round($arr ['followers_count']/10000,1)."<span>万</span>":$arr ['followers_count'];
		return $arr;
	}
	/**
	 *获取关注的分页
	 * */
	function getPageStr($total,$page)
	{
		$this->load->library ( 'pagination' );
		$config['base_url'] = "";
		$config['total_rows'] = $total;
		$config['per_page'] =$this->pageSize;
		$config['full_tag_open'] = '<ul id="follow_page_ul">';
//		$config['cur_page'] = $page;
		$this->pagination->cur_page = $page;
		$this->pagination->initialize($config);
		$pageStr = $this->pagination->create_links();
		return $pageStr;
	}
}