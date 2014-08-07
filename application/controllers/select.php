<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Select extends CI_Controller {
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->load->model ( 'tuser_model' );
		$this->load->model ( 'user_model');
		$this->data = array();
		$this->data ['title'] = "人物列表";
		$this->data ['current'] = "list";
	}
	function index()
	{
		$data = $this->data;
		$data ['total'] = $this->common_model->getTotalTuserNum();
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
		$this->load->model("category_model");
		$data ['order'] = $this->input->cookie("list_order")?$this->input->cookie("list_order"):'alphabet';
		$data ['people'] = $this->listing(1,0,1,$data ['order']);//电影,无子ID,第一页
		$data ['categoryId'] = 1;
		$data ['subCategoryId'] = 0;
		$data ['category'] = $this->category_model->getCategory();
		$this->output('select.tpl',$data);
	}
	function listing($categoryId = null,$subCategoryId = null,$page = null,$order = 'alphabet')
	{
		if(isset($_SESSION['uid']))$this->tuser_model->uid = $_SESSION['uid'];
		if($this->input->post('action')=='ajax')
		{
			$categoryId = $this->input->post('categoryId');
			$subCategoryId = $this->input->post('subCategoryId');
			$page = $this->input->post('page');
			$order = $this->input->post('order');
		}
		$data = $this->tuser_model->getListPeople($categoryId,$subCategoryId,$page,$this->common_model->getTotalTuserNum(),$order);
		if($data ['count'] !=0)
		{
			$data ['pageStr'] = $this->tuser_model->getListPageStr($data['total'],$page);	
		}
		if($this->input->post('action')=='ajax')
		{
			$html = $this->output('ajax/select_data.tpl',array('people'=>$data,'isLogin'=>$this->common_model->checkLogin()?1:0),false);
			echo json_encode(array("html"=>$html,"count"=>$data ['count'],"categoryId"=>$categoryId,"subCategoryId"=>$subCategoryId,"son"=>isset($data ['son'])?$data ['son']:""));
			die;
		}
		return $data;
	}
}