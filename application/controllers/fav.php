<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fav extends CI_Controller {
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->load->model('fav_model');
	}
	function add()
	{
		$tweet_id = $this->input->post("tweet_id");
		if(isset($_SESSION['uid']) && $tweet_id)
		{
			echo $this->fav_model->addFav($_SESSION['uid'],$tweet_id)?1:0;
		}
	}
	function del()
	{
		$fav_id = $this->input->post("fav_id");
		if(isset($_SESSION['uid']) && $fav_id)
		{
			echo $this->fav_model->delFav($_SESSION['uid'],$fav_id)?1:0;
		}
	}
}
