<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Relation extends CI_Controller {
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->load->model('relation_model');
	}
	function follow()
	{
		$fid = $this->input->post('fid');
		if($fid && isset($_SESSION['uid']))
		{
			if($this->relation_model->follow($_SESSION['uid'],$fid))
			{
				echo 1;
				die;
			}
		}
		echo 0;
	}
	function unfollow()
	{
		$fid = $this->input->post('fid');
		if($fid && isset($_SESSION['uid']))
		{
			if($this->relation_model->unfollow($_SESSION['uid'],$fid))
			{
				echo 1;
				die;
			}
		}
		echo 0;
	}
}