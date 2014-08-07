<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class People extends CI_Controller {
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->load->model("tuser_model");
		$this->load->model("user_model");
		$this->load->model("tweet_model");
		if($this->common_model->checkLogin())
		{
			$data ['isLogin'] = 1;
			$this->tuser_model->uid = $_SESSION['uid'];
			$data ['user'] = $this->user_model->getUserInfo($_SESSION['uid']);
			$data ['quit_url'] = BASE_URL."/quit/";
		}
		else
		{
			$data ['redirect_url'] = WB_CALLBACK_URL;
		}
		$this->data = $data;
	}
	function index($id)
	{
		define("ROW_SIZE",30);
		$data = $this->data;
		$data ['data'] = $this->tuser_model->getUser($id);
		$data ['title'] = $data['data']['name'];
		$data ['follower'] =$this->tuser_model->getFollower($id);
		$data ['tweet'] = $this->tweet_model->getFeed(1,$id);
		$data ['pagesize'] = ROW_SIZE;
		$data ['count'] = count($data ['tweet']);
		$data ['body_img'] = $data ['data'] ['profile_background_image_url'];
		$this->load->model("qiandao_model");
		$data['qiandao_num'] = $this->qiandao_model->get($id);
		//淘宝客展示商品
		$this->load->model("product_model");
		$data['products'] = $this->product_model->getFourProduct($id);
		$this->output('people.tpl',$data);
	}
	function tuser_follow()
	{
		$data = $this->data;
		$id = $this->input->post("id");
		$page = $this->input->post("page");
		$total = $this->input->post("total");
		$data ['name'] = $this->input->post("name");
		$this->load->model("tfollow_model");
		$this->tfollow_model->uid = $id;
		$data ['people'] = $this->tfollow_model->getFollowById($page);
		$data ['pageStr'] = $this->tfollow_model->getPageStr($total,$page);
		$this->output('ajax/tfollow_data.tpl',$data);
	}
	function tuser($screen_name)
	{
		$data = $this->data;
		if(eregi("^[0-9]+$",$screen_name))
		{
			$tid = $screen_name;
			$screen_name = "用户";
			$res = $this->tuser_model->getIdByTid($tid);
			if($res)
			{
				$screen_name = $res['sceen_name'];
			}
		}
		else
		{
			$res = $this->tuser_model->getIdByScreenName($screen_name);
		}
		if($res)
		{
			header("Location:".BASE_URL."/{$screen_name}");
			die;
		}
		else
		{
			$data ['title'] = "暂时未接入{$screen_name}的数据";
			$data ['screen_name'] = $screen_name;
			$this->output('no_people.tpl',$data);
		}
	}
}