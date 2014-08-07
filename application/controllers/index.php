<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends CI_Controller {
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->load->model ( 'tuser_model' );
		$this->load->model ( 'tweet_model' );
		$this->load->model ( 'user_model' );
		$this->data = array();
		$this->data ['title'] = "搬微博，banweibo，最新的明星资讯，日韩明星，美剧明星，体育明星";
		$this->data ['current'] = "index";
	}
	public function index()
	{
//		ini_set("display_errors",1);
		define("ROW_SIZE",30);
		$data = $this->data;
		$data ['people'] = $this->tuser_model->getIndexPeople(5);
		$data ['popular'] = $this->tuser_model->getPopularPeople(5);
		$data ['pagesize'] = ROW_SIZE;
		if($this->common_model->checkLogin())
		{
			//登录后
			$data ['isLogin'] = 1;
			$data ['user'] = $this->user_model->getUserInfo($_SESSION['uid'],false);
			//检查twitter绑定
			$data ['isBind'] = $this->common_model->checkTwitterLogin();
// 			if($data ['isBind']) //是否读原始时间线
// 				$data ['tweet'] = $this->tweet_model->getRawHomeline();
// 			else
			$data ['tweet'] = $this->tweet_model->getHomeline(1,$_SESSION['uid']);
			$data ['count'] = count($data ['tweet']);
			$data ['quit_url'] = BASE_URL."/quit/";
			$this->output('main.tpl',$data);
		}
		else
		{
			if($this->input->get("f") !=1)
			{
				if($this->checkIndexIfOutdate( FCPATH."cache/index.cae") == false)
				{
					echo file_get_contents( FCPATH."cache/index.cae");
					die;
				}
			}
			$data ['isLogin'] = 0;
			/*信息流
			$data ['tweet'] = $this->getFeed(1,0);
			$data ['count'] = count($data ['tweet']);
			*/
			$data ['redirect_url'] = WB_CALLBACK_URL;
			$data ['body_img'] = "http://a0.twimg.com/profile_background_images/38950068/background.jpg";
			//echo $data ['login_url'] = $this->weibo->oauth->getAuthorizeURL( WB_CALLBACK_URL );die;
			echo $html = $this->output('index.tpl',$data,false);
			file_put_contents(FCPATH."cache/index.cae", $html."<!-- cached -->");
		}
	}
	private function checkIndexIfOutdate($file)
	{
		if(time() - filemtime($file)>60){
			return true;
		}
		return false;
	}
	/**
	 * 输出feed,参数依次是$page,$row,$tid
	 * */
	function getFeed()
	{
		if (!defined("ROW_SIZE"))
		{
			define("ROW_SIZE",30);
		}
		if($this->input->post('action') == 'ajax')
		{
			if($this->input->post('if_homeline'))
			{
//				if($this->common_model->checkTwitterLogin())
//					$data = $this->tweet_model->getRawHomeline();
//				else
					$data = $this->tweet_model->getHomeline($this->input->post('row'),$_SESSION['uid']);
			}
			else 
			{
				$data = $this->tweet_model->getFeed($this->input->post('row'),$this->input->post('tid'));
			}
				$count = count($data);
				$html = $this->output('ajax/feed_ajax.tpl',array('tweet'=>$data),false);
				echo json_encode(array("count"=>$count,"html"=>$html));
		}
		else
		{
			$argus = func_get_args();
			return $this->tweet_model->getFeed($argus[0],$argus[1]);	
		}
	}
	/**
	 * 获取关注
	 * */
	function getFollow()
	{
		define("PAGESIZE",20);
		if($this->input->post('action')=='ajax')
		{
			$page = $this->input->post('row');
		}
		else
		{
			die();
		}
		$this->tuser_model->uid = $_SESSION['uid'];
		$data['people'] = $this->tuser_model->getFollow($page,true);
		$data['isLogin'] = $this->common_model->checkLogin();
		$data ['isBind'] = $this->common_model->checkTwitterLogin();
		if($data ['isBind'])
		{
//			$data ['follow_info'] = $this->user_model->getFollowTwitterInfo();
		}
		$data['count'] = count($data['people']);
		if($this->input->post('noframe')=='1')
		{
			$data ['noframe'] = 1;
		}
		$html = $this->output('ajax/people_list.tpl',$data,false);
		echo json_encode(array("count"=>count($data['people']),"html"=>$html));
	}
	/**
	 * 获取我转发的
	 */
	function rt()
	{
		define("ROW_SIZE",30);
		$row = $this->input->post("row");
		$this->load->model('tweet_model');
		$data ['tweet'] = $this->tweet_model->getRtList($_SESSION['uid'],$row);
		$html = $this->output('ajax/feed_ajax.tpl',$data,false);
		echo json_encode(array("count"=>count($data ['tweet']),"html"=>$html,"ROW_SIZE"=>ROW_SIZE));
	}
	/**
	 * 获取收藏
	 * */
	function fav()
	{
		define("ROW_SIZE",30);
		$row = $this->input->post("row");
		$this->load->model('fav_model');
		$data ['tweet'] = $this->fav_model->getFavList($_SESSION['uid'],$row);
		$html = $this->output('ajax/feed_ajax.tpl',$data,false);
		echo json_encode(array("count"=>count($data ['tweet']),"html"=>$html,"ROW_SIZE"=>ROW_SIZE));
	}
	/**
	 * 获取回复我的评论
	 * */
	function received_comment()
	{
		define("ROW_SIZE",30);
		$row = $this->input->post("row");
		$this->load->model('comment_model');
		$data['tweet'] = $this->comment_model->getReplyList($_SESSION['uid'],$row);
//		var_dump($data['tweet']);die;
		$html = $this->output('ajax/feed_ajax.tpl',$data,false);
		echo json_encode(array("count"=>count($data ['tweet']),"html"=>$html,"ROW_SIZE"=>ROW_SIZE));		
	}
	function comment()
	{
		$this->received_comment();
	}
	/**
	 * 获取我发出的评论
	 * */
	function sended_comment()
	{
		define("ROW_SIZE",30);
		$row = $this->input->post("row");
		$this->load->model('comment_model');
		$data['tweet'] = $this->comment_model->getSendedList($_SESSION['uid'],$row);
		$html = $this->output('ajax/feed_ajax.tpl',$data,false);
		echo json_encode(array("count"=>count($data ['tweet']),"html"=>$html,"ROW_SIZE"=>ROW_SIZE));			
	}
	/**
	 * 发微博
	 * */
	function post_weibo()
	{
		$tweet_id = $this->input->post("tid");
		$text = $this->input->post("text");
		$url = $this->input->post("url");//图片地址
		if($text)
		{
			$this->load->library('weibo',array('access_token'=>$_SESSION['token']['access_token']));
			if($url)
				$res = $this->weibo->upload_url_text($text,$url);
			else
				$res = $this->weibo->update($text);
			if(isset($res['id']) && isset($res['created_at']))
			{
				if($tweet_id)
				{
					$this->load->model("tweet_model");
					$this->tweet_model->add_rt_record($tweet_id,$res['id'],$res['mid'],strtotime($res['created_at']));
				}
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	function send_tweet()
	{
		$this->load->model("tweet_model");
		$text = $this->input->post("text");
		echo $this->tweet_model->send_tweet(urlencode($text))?1:0;
	}
	function getTwitterFollow()
	{
		if($this->common_model->checkTwitterLogin())
		{
			$this->load->model("catch_data_model");
			$this->catch_data_model->getData($action, $param);
		}
		else{
			echo 0;
			die;
		}
	}
	/**
	 * 首页签到接口
	 * */
	function qiandao_records()
	{
		$this->load->model("qiandao_model");
		$data['records'] = $this->qiandao_model->getUserRecords($_SESSION['uid']);
		$html = $this->output('ajax/qiandao_records.tpl',$data,false);
		echo json_encode(array("count"=>count($data['records']),"html"=>$html));
	}
//	function test()
//	{
////		var_dump($_SESSION);
////		$memcache = new memcache;
////		$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
////		$memcache->delete("list_1_0_1_alphabet");
////		$memcache->delete("list_30_65_1");
////		$memcache->delete("list_30_66_1");
////		var_dump( $memcache->get("feed_cache_0_1"));
//	}
}