<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Qiandao extends CI_Controller {
	function __construct()
	{
		session_start();		
		parent::__construct ();
		$this->load->model("qiandao_model");
	}
	public function index()
	{
		$res = $this->input->post("tuid");
		if(!empty($res))
		{
			$return = $this->qiandao_model->add($this->input->post("tuid"));
			if($return == false){
				echo json_encode(array("result"=>0));
			}else{
				echo json_encode(array("result"=>1,"num"=>$return));
			}
		}
	}
	function init()
	{
		$this->qiandao_model->initLog();
		$this->qiandao_model->updateQiandaoOrder();
	}
}