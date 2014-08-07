<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Comment extends CI_Controller {
	private $data;
	function __construct()
	{
		session_start();
		parent::__construct ();
		$this->load->model('comment_model');
	}
	function getComment()
	{
		$tweet_id =$this->input->post("tweet_id");
		$row =$this->input->post("comm_row");
		if($tweet_id && $row)
		{
			define("ROW_SIZE",10);
			$data ['pagesize'] = ROW_SIZE;
			$data ['comments'] = $this->comment_model->getCommList($tweet_id,$row);
			$data ['count'] = count($data ['comments']);
			$data ['isLogin'] = isset($_SESSION['uid'])?1:0;
			if(isset($_SESSION['uid']))
				$data ['user']['uid'] = $_SESSION['uid'];
			$this->output('ajax/comment.tpl',$data);
		}
	}
	function getCommentMore()
	{
		$tweet_id =$this->input->post("tweet_id");
		$row =$this->input->post("comm_row");
		if($tweet_id && $row)
		{
			define("ROW_SIZE",10);
			$data ['comments'] = $this->comment_model->getCommList($tweet_id,$row);
			$data ['isLogin'] = isset($_SESSION['uid'])?1:0;
			$data ['user']['uid'] = $_SESSION['uid'];
			$html = $this->output('ajax/comment_content.tpl',$data,false);
			echo json_encode(array("count"=>count($data ['comments']),"pagesize"=>ROW_SIZE,"html"=>$html));
		}
	}
	function add()
	{
		$tweet_id = $this->input->post("tweet_id");
		$is_translate = $this->input->post("is_translate");
		$text = $this->input->post("text");
		$wuid = $this->input->post("weibo_uid");
		$wname = $this->input->post("weibo_uname");
		$to_user_id = $this->input->post("to_user_id")?$this->input->post("to_user_id"):0;
		$to_user_name = $this->input->post("to_user_name");
		$to_user_wuid = $this->input->post("to_user_wuid")?$this->input->post("to_user_wuid"):0;
		if(isset($_SESSION['uid']) && $tweet_id && $text)
		{
			echo $this->comment_model->addComment($_SESSION['uid'],$tweet_id,$is_translate,$text,$wuid,$wname,$to_user_id,$to_user_name,$to_user_wuid)?1:0;
		}
	}
	function del()
	{
		$comm_id = $this->input->post("comm_id");
		$tweet_id = $this->input->post("tweet_id");
		$user_id = $this->input->post("user_id");
		if(isset($_SESSION['uid']) && $_SESSION['uid']==$user_id &&$comm_id && $tweet_id)
		{
			echo $this->comment_model->delComment($tweet_id,$comm_id)?1:0;
		}
	}
}
