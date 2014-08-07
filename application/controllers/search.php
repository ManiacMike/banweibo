<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search extends CI_Controller {
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->load->model ( 'user_model' );
		$this->load->model ( 'search_model' );
		$this->load->model('recommend_model');
		$this->data = array();
	}
	public function index()
	{
		if(0)
		{
			$this->output("blank.tpl",array("redirect_time"=>2,"redirect"=>BASE_URL,"message"=>"访问量过大，搜索无法使用了，跳转中..."));
			die;
		}
		$data = $this->data;
		$data ['current'] = "search";
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
		$keyword = htmlspecialchars(trim($this->input->get("q")));
		$this->load->model("filter_model");
		$ifSafe = $this->filter_model->checkSensitiveWords($keyword);
		if($ifSafe){
			if($keyword)
			{
				$result1 = $this->search_model->getSearchResult($keyword,1,true);
				$result2 = $this->search_model->getSphinxResult($keyword,1);
				$data ['data'] = array_merge($result2,$result1);
				$data ['data_count'] = count($data ['data']);
				if(!$data ['data'])$data['error_message'] = "sorry,twitter搜索接口超过频率限制,请稍后再试";
				$data ['keyword'] = $keyword;
			}
		}else{
			$data = array(
			"keyword"=>$keyword,
			"data"=>array(),
			"data_count"=>0,
			"error_message"=>"搜索词中含有敏感词汇"
			);
		}
		$data ['title'] = $keyword?"搜索 {$keyword}":"搜索用户";
		$data ['default_search'] = "搜索用户";
		$data ['recent'] = $this->recommend_model->getRecentApproved();
		$this->output('search.tpl',$data);
	}
	function result()
	{
		$data = array();
		$q = $this->input->post('q');
		$page = $this->input->post('page');
		$data ['isLogin'] = isset($_SESSION['token']['uid'])?1:0;
		$data ['data'] = $this->search_model->getSearchResult($q,$page);
		$data ['data_count'] =count($data ['data']);
		$this->output('ajax/search_data.tpl',$data);
	}
	function tag()
	{
		$data = $this->data;		
		$keyword = htmlspecialchars($this->input->get("q"));
		$data ['title'] = "{$keyword}相关推文";
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
		if($keyword)
		{
			$data ['tweet'] = $this->search_model->getTagResult($keyword,1);
			$data ['data_count'] = count($data ['tweet']);
			$data ['keyword'] = $keyword;
		}
		$this->output('search_tag.tpl',$data);
	}
	function tag_result()
	{
		$data = array();
		$q = $this->input->post('q');
		$page = $this->input->post('row');
		$data ['tweet'] = $this->search_model->getTagResult($q,$page);
		$data ['data_count'] =count($data ['tweet']);
		echo json_encode(array("html"=>$this->output('ajax/feed_ajax.tpl',$data,false),"count"=>$data ['data_count']));
	}
	function recommend()
	{
		$tuid = $this->input->post('tuid');
		$tname = $this->input->post('tname');
		$tscreen_name = $this->input->post('tscreen_name');
		$tip = $this->input->post('tip');
		if( isset($_SESSION['uid']) && $tuid && $tname)
		{
			$user = $this->user_model->getUserInfo($_SESSION['uid']);
			$res = $this->recommend_model->addRecommend($tuid,$tname,$tscreen_name,$tip,$user);
			echo $res?1:0;
		}
		else
		{
			echo 'lost args';
		}
	}
	function checkSearchLock()
	{
		$memcache = new memcache;
		$host = 'localhost';
		$port = $this->config->item('memcache_port');
		$connect = $memcache ->connect($host, $port);
		if($connect)
		{
			return false;
		}
		return $memcache->get("mem_search_lock")?$memcache->get("mem_search_lock"):0;
	}
}