<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hot extends CI_Controller {
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->data ['title'] = "热门推文";
		$this->data ['current'] = "hot";
	}
	function index($pageType = 0)
	{
		$data = $this->data;
		$this->load->model('user_model');
		$this->load->model('tweet_model');
		if($this->common_model->checkLogin())
		{
			$data ['isLogin'] = 1;
			$data ['user'] = $this->user_model->getUserInfo($_SESSION['uid']);
			$data ['quit_url'] = BASE_URL."/quit/";
		}
		else
		{
			$data ['redirect_url'] = WB_CALLBACK_URL;
		}
		if($pageType == 0)
		{
			if(!isset($_COOKIE['hot_page_default']) || $_COOKIE['hot_page_default'] ==1)
			{
				$pageType = 1;
			}else
			{
				$pageType = 2;
			}
		}
		$data ['pageType'] = $pageType;
		if($pageType == 1)
		{
			$data ['tweet'] = $this->tweet_model->getHotTweet();
			$data ['people'] = $this->tweet_model->getHotUser($data ['tweet']);
		}else{
			//评论最多的热门
			$data ['tweet'] = $this->tweet_model->getPopularTweet();
			$data ['people'] = $this->tweet_model->getHotUser($data ['tweet'],'popular_tweet_user');
		}
		$this->output('hot.tpl',$data);
	}
}