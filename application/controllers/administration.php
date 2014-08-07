<?php
/**
 * @author mike
 * 后台
 * */
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Administration extends CI_Controller
{
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->data = array();
		$this->data ['title'] = "后台管理2013-04-03";
		$this->load->model ( 'admin_model' );
		$clientIp = $this->common_model->getClientIP();
		//$ipAccess = array("223.166.199.209","114.60.3.108","127.0.0.1");
		$admin_id_array = array(1,3,6,7,12,44,6082,8789,40403,47240,74490,32239,41086,6239,54762,30827);
		if($clientIp !="127.0.0.1")
		{
			if(!isset($_SESSION['uid']) || !in_array($_SESSION['uid'],$admin_id_array) )
			{
				die("error 403");
			}
		}
	}
	//首页微博管理
	function index($keyword = "")
	{
		$data = $this->data;
		$this->load->model('category_model');
		$data ['cur_nav'] = "微博账户管理";
		//搜索
		if($keyword)
		{
			$keyword = urldecode($keyword);
			$this->admin_model->keyword=$keyword;
			$data['keyword'] = $keyword;
		}
		$data ['pageStr'] = $this->admin_model->getUserPageStr();
		//编辑行为
		if($this->input->get('action') == 'edit')
		{
			$data ['edit_res'] = $this->admin_model->updateHotsort($_POST['index_order']);		
		}
		$data ['data']=$this->admin_model->getTusers();
		$data ['category'] = $this->category_model->getAll();
		if(0){
			$this->output('admin/index.tpl',$data);
		}else{
			$this->output('admin/main.tpl',$data);
		}
	}
	//微博账户资料管理
	function twitter_info($id)
	{
		$data = $this->data;
		$data ['cur_nav'] = "微博账户管理";
		$config = array(
		"id"=>array("name"=>"ID","type"=>"p"),
		"tid"=>array("name"=>"twitter的ID","type"=>"p"),
		"name"=>array("name"=>"名字","type"=>"p"),
		"cname"=>array("name"=>"中文别名","type"=>"text"),
		"nation"=>array("name"=>"国家","type"=>"p"),
		"whatsit"=>array("name"=>"TA是","type"=>"p"),
		"description"=>array("name"=>"twitter简介","type"=>"p"),
		"intro"=>array("name"=>"中文介绍","type"=>"textarea","syn"=>1),
		"screen_name"=>array("name"=>"twitter域名","type"=>"p"),
		"location"=>array("name"=>"twitter所在地","type"=>"p"),
		"location_show"=>array("name"=>"所在地展现","type"=>"text","syn"=>1),
		"url"=>array("name"=>"个人主页","type"=>"p"),
		"verified"=>array("name"=>"twitter认证","type"=>"int"),
		"statuses_count"=>array("name"=>"tweet条数","type"=>"p"),
		"input_statuses_count"=>array("name"=>"导入的tweet条数","type"=>"p"),
		);
		$this->load->model ( 'tuser_model' );
		$data['data'] = $this->admin_model->getUserByColumn($id,$config);
		$data ['nation'] = $this->tuser_model->getAllNation($id);
		$data ['whatsit'] = $this->tuser_model->getAllWhatsit($id);
		//编辑行为
		if($this->input->get('action') == 'edit')
		{
			$input = $_POST;
			if(isset($input ['nation']))
			{
				$input ['nation'] = $data ['nation'] [$input['nation']-1]['name'];
				$this->admin_model->updateNationSelect($id,$_POST['nation']);
			}
			if(isset($input ['whatsit']))
			{
				foreach ($input ['whatsit'] as $key=>$one)
				{
					$tmp[] = $data ['whatsit'] [$key-1] ['name'];
				}
				unset($input ['whatsit']);
				$input ['whatsit'] = implode("，",$tmp);
				$this->admin_model->updateWhatsitSelect($id,$_POST ['whatsit']);
			}
			$data ['edit_res'] = $this->admin_model->updateUser($id,$input);
			header("Location:".BASE_URL."/administration/twitter_info/{$id}/");
			die;
		}
		$this->output('admin/twitter_info.tpl',$data);	
	}
	function tweet($uid=0)
	{
		$data = $this->data;
		$data ['pageStr'] = $this->admin_model->getTweetPageStr($uid);
		//编辑行为
		if($this->input->get('action') == 'edit')
		{
			$data ['edit_res'] = $this->admin_model->updateTweetHotsort($_POST['hotsort']);
			$p = $this->admin_model->pagination->cur_page;
			$p = $p == 0 ? 1 : $p;
			header("Location:".BASE_URL."/administration/tweet/{$uid}/$p");
			die;
		}
		$data ['data'] = $this->admin_model->getTweet($uid);	
		if($uid && $data ['data'])
			$data ['uname'] = $data ['data'][0]['name'];
		$data ['cur_nav'] = "推文管理";
		$this->output('admin/tweet_list.tpl',$data);
	}
	function hot()
	{
		$data = $this->data;
		//编辑行为
		if($this->input->get('action') == 'edit')
		{
			$data ['edit_res'] = $this->admin_model->updateHotDesc($_POST['desc']);
			$memcache = new memcache;
			$mem_connect = $memcache ->connect('localhost', $this->config->item('memcache_port'));
			$memcache->delete("hot_page_content");
			$memcache->delete("hot_page_people");
			$memcache ->close();
			header("Location:".BASE_URL."/administration/hot/");
			die;
		}
		$this->load->model("tweet_model");
		$data['data'] = $this->tweet_model->getHotTweet(false);
		$data ['cur_nav'] = "热推管理";
		$this->output('admin/tweet_list.tpl',$data);
	}
	function translate()
	{
		$data = $this->data;
		//编辑行为
		if($this->input->get('action') == 'edit')
		{
			$data ['edit_res'] = $this->admin_model->updateTranslate($_POST);
			header("Location:".BASE_URL."/administration/translate/");
			die;
		}
		$data ['pageStr'] = $this->admin_model->getUserPageStr("translate");
		$this->load->model("comment_model");
		$data['data'] = $this->comment_model->getTranslateTweet($this->admin_model->pagination->cur_page);
		$data ['cur_nav'] = "翻译管理";
		$this->output('admin/comment_list.tpl',$data);	
	}
	function recommend($status)
	{
		set_time_limit(0);
		$data = $this->data;
		$this->load->model("recommend_model");
		//编辑行为		
		if($this->input->get('action') == 'edit')
		{
			$page = $this->input->post('page');
			$status = $this->input->post('status');
			unset($_POST['page'],$_POST['status']);
			$data ['edit_res'] = $this->recommend_model->updateRecommend($_POST['pass']);
			header("Location:".BASE_URL."/administration/recommend/{$status}/{$page}");
			die;
		}
		$data ['status'] = $status;
		$data ['pageStr'] = $this->recommend_model->getRecoPageStr($status);
		$data ['page'] = $this->recommend_model->getCurPage();
		$data ['notification'] = $this->recommend_model->getAprovedCount();
		$data['data'] = $this->recommend_model->getRecommend($status);
		$data ['cur_nav'] = "用户推荐管理";	
		$this->output('admin/reco.tpl',$data);		
	}
	function category()
	{
		$data = $this->data;
		$this->load->model("category_model");
		if($this->input->get('action') == 'add')
		{
			 $this->category_model->add($_POST);
			header("Location:".BASE_URL."/administration/category/");
			die;
		}
		elseif ($this->input->get('action') == 'update')
		{
			$this->category_model->update($_POST);
			header("Location:".BASE_URL."/administration/category/");
			die;			
		}
		$data ['data'] = $this->category_model->getAll();
		$data ['cur_nav'] = "分类管理";
		$this->output('admin/category.tpl',$data);				
	}
	function datafill_manage()
	{
		$data = $this->data;
		define("DATAFile", FCPATH."cache/datafill_manage.cae");
		$this->load->model("datafill_manage_model");
		if($this->input->get('action') == 'add')
		{
			$res = $this->datafill_manage_model->add($this->input->get('id'),$this->input->get('name') );
			if($this->input->get("from") != 'recommend')
			{
				header("Location:".BASE_URL."/administration/datafill_manage/");
			}
			die;
		}elseif ($this->input->get('action') == 'del')
		{
			$res = $this->datafill_manage_model->del($this->input->get('id') );
			header("Location:".BASE_URL."/administration/datafill_manage/");
			die;
		}
		$this->load->model("tuser_model");		
		$data ['data'] = unserialize(file_get_contents(DATAFile));
		if($data ['data'])
		{
			$user = $this->tuser_model->getUserById($data ['data'][0]['id']);
			$data['fid']= $user['tid'];
		}
		$data ['cur_nav'] = "抓取管理";
		$this->output('admin/datafill_manage.tpl',$data);
	}
	function user_statics()
	{
		$data = $this->data;
		$this->load->model("user_model");
		$data ['count'] = $this->user_model->countUser();
		$data ['follow_count'] = $this->user_model->countFollow();
		$data ['gender_data'] = $this->user_model->getGenderRate();
		$data ['province_date'] = $this->user_model->getProvinceRate();
		$data ['average'] = round($data ['follow_count']/$data ['count'],2);
		$data ['cur_nav'] = "用户统计";
		$this->output('admin/user_statics.tpl',$data);
	}
	function memcache_status()
	{
// 		if($_SESSION['uid'] != 1 && $_SESSION['uid'] != 12)
// 		{
// 			header("Location:".BASE_URL."/administration/");
// 			die;			
// 		}
		$data = $this->data;
		$memcache = new memcache;
		$host = 'localhost';
 		$port = $this->config->item('memcache_port');
		$memcache ->connect($host, $port);
		if($this->input->get("action") =="flush")
		{
			$memcache->flush();
			header("Location:".BASE_URL."/administration/memcache_status/");
			die;
		}elseif($this->input->get("action") =="init")
		{
			file_get_contents(BASE_URL."/sort_data/initMem");
			header("Location:".BASE_URL."/administration/memcache_status/");
			die;
		}
		$data ['sys'] = $memcache->getStats();
		$data ['sys'] ['剩余容量'] = round(($data ['sys']['limit_maxbytes'] - $data ['sys']['bytes'])/(1024*1024),5) . "M";
		$data ['cur_nav'] = "memcache状态";
		$items=$memcache->getExtendedStats ('items');
		/*
		$data ['sys'] ['关系缓存'] = 0;
		$data ['sys'] ['列表页缓存'] = 0;
		$data ['sys'] ['人名缓存'] = 0;
		$data ['sys'] ['热门缓存'] = 0;
		if(isset($items["$host:$port"]['items'])){
		$items=$items["$host:$port"]['items'];
		foreach($items as $key=>$values){
			$number=$key;
			$str=$memcache->getExtendedStats ("cachedump",$number,0);
			$line=$str["$host:$port"];
			if( is_array($line) && count($line)>0){
	            foreach($line as $key=>$value){
	               $line[$key]=$memcache->get($key);
	               if(strpos($key,"relation")!==false)
	               {
	               		$data ['sys'] ['关系缓存'] ++;
	               }else if(strpos($key,"list")!==false)
	               {
	               		$data ['sys'] ['列表页缓存'] ++;
	               }else if(strpos($key,"hot_page_")!==false)
	               {
	               		$data ['sys'] ['热门缓存'] ++;
	               }else{
	               		$data ['sys'] ['人名缓存'] ++;
	               }
	            }
			}
		}
		}else{
			$data ['data'] = array();
		}
		*/
// 		$data ['sys'] ['列表页缓存'] = isset($data ['data'] ['list'])? count($data ['data'] ['list']):0;
// 		$data ['sys'] ['关系缓存'] = isset($data ['data'] ['relation'])? count($data ['data'] ['relation']):0;
// 		$data ['sys'] ['人名缓存'] = isset($data ['data'] ['screen_name'])? count($data ['data'] ['screen_name']):0;
// 		$data ['sys'] ['热门缓存'] = isset($data ['data'] ['hot'])? count($data ['data'] ['hot']):0;
		$memcache ->close();
		$this->output('admin/mem_status.tpl',$data);
	}
	function cate_save()
	{
		$this->load->model("category_model");
		$id = $this->input->post("id");
		//检查分类是否合法
		$main_cid = "";
		if($this->input->post("main_cid"))
		{
			$main_cid = str_replace("，",",",$this->input->post("main_cid"));
			$main_cid = $this->category_model->checkName(explode(",",$main_cid));
		}
		$sub_cid = "";
		if($this->input->post("sub_cid")){
			$sub_cid =  str_replace("，",",",$this->input->post("sub_cid"));		
			$sub_cid = $this->category_model->checkName(explode(",",$sub_cid));
		}
		//清空category表中的数据
		$this->category_model->removeCurrentCategory($id);
		//更新tuser表
		$vArr = array("main_cid"=>$main_cid,"sub_cid"=>$sub_cid);
		$this->category_model->updateCategoryTuser($vArr,$id);
		//更新category
		$this->category_model->updateTuserCategory($vArr,$id);
		file_get_contents(BASE_URL."/sort_data/updateCategory/");
		echo json_encode($vArr);
	}
	function place()
	{
		$this->load->model("place_model");
		$data ['cur_nav'] = "地图管理";
	}
}
