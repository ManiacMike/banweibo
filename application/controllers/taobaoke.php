<?php
/**
 * @author mike
 * 淘宝客后台
 * */
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Taobaoke extends CI_Controller
{
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->data = array();
		$this->data ['title'] = "淘宝客后台管理2013-06-06";
		$this->load->model ( 'taobaoke_model' );
		$clientIp = $this->common_model->getClientIP();
		//$ipAccess = array("223.166.199.209","114.60.3.108","127.0.0.1");
		$admin_id_array = array(1);
		if($clientIp !="127.0.0.1")
		{
			if(!isset($_SESSION['uid']) || !in_array($_SESSION['uid'],$admin_id_array) )
			{
				die("error 403");
			}
		}
	}
	//首页商品管理
	function index($keyword = "")
	{
		$data = $this->data;
		$data ['cur_nav'] = "商品管理";
		//搜索
		if($keyword)
		{
			$keyword = urldecode($keyword);
			$this->taobaoke_model->keyword=$keyword;
			$data['keyword'] = $keyword;
		}
		$data ['pageStr'] = $this->taobaoke_model->getProductPageStr();
		//编辑行为
		if($this->input->get('action') == 'edit')
		{
			$data ['edit_res'] = $this->taobaoke_model->updateProduct($_POST);		
		}
		$data ['data']=$this->taobaoke_model->getProduct();
		$this->output('admin/taobaoke_main.tpl',$data);
	}
}