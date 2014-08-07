<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Quit extends CI_Controller {
	function __construct()
	{
		session_start();
		parent::__construct ();
	}
	function index()
	{
		unset($_SESSION['uid']);
		unset($_SESSION['token']);
		unset($_SESSION['twitter_token']);
		session_destroy();
		setcookie("login_type","",time()-3600,"/");
		setcookie("login_uname","",time()-3600,"/");
		setcookie("login_id","",time()-3600,"/");
		setcookie("login_img","",time()-3600,"/");
		?>
			<html>
			<head><meta http-equiv="refresh" content="0;url=<?php echo BASE_URL."/index/";?>"> </head>
			</html>
		<?php 
	}
	function unbind_twitter()
	{
		$this->load->model("user_model");
		$this->user_model->unbindTwitter();
		unset($_SESSION['twitter_token']);
		?>
			<html>
			<head><meta http-equiv="refresh" content="0;url=<?php echo BASE_URL."/index/";?>"> </head>
			</html>
		<?php 
	}
}