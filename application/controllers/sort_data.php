<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Sort_data extends CI_Controller
{
	function __construct()
	{
		set_time_limit ( 0 );
		parent::__construct ();
		$this->load->model("sort_data_model");
	}
	function index()
	{}
	function initMem()
	{
		$this->createScreenNameCache();
		$this->load->model("relation_model");
		$this->relation_model->dbToMem();
	}
	//更新个性域名缓存
	function createScreenNameCache()
	{
		$data = $this->sort_data_model->getIdAndScreenName();
		$memcache = new memcache;
		if($memcache ->connect('localhost', '11211'))
		{
			foreach ($data as $screen_name => $id)
			{
				$memcache->set($screen_name,$id,MEMCACHE_COMPRESSED,0);
			}
		}
		$memcache->close();
		$file = FCPATH."cache/screen_name.cae";
		$res = file_put_contents($file,serialize($data));
		echo $res?'ok':'fail';
	}
	//更新接入总数缓存
	function truncTuserNum()
	{
		$res = $this->common_model->truncTuserNum();
		echo $res?'ok':'fail';
	}
	//更新category表的count字段并排序
	function updateCategory()
	{
		$this->sort_data_model->updateCategory();
	}
	//用banweibo的微博去通知通过的用户
	function infoReco()
	{
		$this->load->model("recommend_model");
		$arr = $this->recommend_model->getAllAproved();
		if(!$arr)die;
		$officalToken = "2.00ktVKnD9wzRJD6419e4fb3dAxU45E";
		$this->load->library('weibo',array('access_token'=>$officalToken));
		foreach ($arr as $one)
		{
			$text = "@{$one['wname']}，你推荐的推特账号{$one['tname']}等账号已经接入到搬微博，请戳http://banweibo.com/{$one['tscreen_name']}";
			$res = $this->weibo->upload_url_text($text);
			if(isset($res['id']) && isset($res['created_at']))
				$this->recommend_model->updateNotify($one['id'],1);
			sleep(1);
		}
	}
	//根据tuser表去整合数据
	function sort_delete()
	{
		$this->load->model("recommend_model");
		//根据tuser表更新推荐表
		$arr = $this->recommend_model->updateRecommendByDelete();
		//根据tuser表更新follow表
		$arr = $this->recommend_model->	deleteInvalidFollow();			
	}
	//根据tuser表去follow
	function followTwitter($sysId)
	{
		$this->load->model('catch_data_model');
//		$this->sort_data_model->update_t_follow_account($sysId);die;
		$ids = $this->sort_data_model->getUnfollowAccount();
		$i= 0;
		foreach ($ids as $one)
		{
			if($i>150)
			{
				break;
			}
			$request = array("user_id"=>$one['tid']);
			$request = $this->common_model->addSysToken($request,$sysId);
			$res = $this->catch_data_model->getData("follow",$request);
			//108找不到账号 159账号停用 162被列入黑名单
			if(isset($res['errors']) && ($res['errors'][0]['code']==108 || $res['errors'][0]['code']==159 || $res['errors'][0]['code']==162))
			{
				$this->sort_data_model->markError($one['tid']);
			}
			//161已发送申请
			if(isset($res['errors']) && ($res['errors'][0]['code']==160 || $res['errors'][0]['code']==161 ))
			{
				$this->sort_data_model->markProtected($one['tid']);
				break;
			}
			if(isset($res['errors']) && $res['errors'][0]['code']==64)
			{
				die("系统账号$sysId被冻结");
			}
			if(!isset($res['errors']) && isset($res['name']) && isset($res['id_str'])){
				$this->sort_data_model->updateFollowAccount($one['tid'],$sysId);
			}
			$i++;
		}
//		$this->sort_data_model->update_t_follow_account($sysId);
	}
	function removeInvalidFollow()
	{
		//删除重复的key
		$ids = $this->sort_data_model->fillDataKey();
	}
	//更新user表的follow_count_b
	function updateUserFollowCount()
	{
		$page = $this->input->get("page")?$this->input->get("page"):1;
		$this->sort_data_model->updateUserFollowCount($page);
		$page = $page+1;
		echo "<script>location.href='http://banweibo.com/sort_data/updateUserFollowCount/?page=$page'</script>";
		die;
	}
	function updateHotsortValue()
	{
		$this->sort_data_model->updateHotsortValue();
	}
}