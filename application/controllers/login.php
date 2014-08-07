<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
	function __construct()
	{
		session_start();
		parent::__construct ();
	}
	function callback($url)
	{
		$url = str_replace("dash_code","/",urldecode($url));
		$this->load->library('weibo');
		if (isset($_REQUEST['code'])) 
		{
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try 
			{
				$token = $this->weibo->oauth->getAccessToken( 'code', $keys ) ;
			}
			catch (OAuthException $e) 
			{}
		}
		if (isset($token)) 
		{
			$_SESSION['token'] = $token;
		}
		//更新信息
		$this->load->library('weibo',array('access_token'=>$_SESSION['token']['access_token']));
		$this->load->model('user_model');
		$user_data = $this->weibo->show_user_by_id($_SESSION['token']['uid']);
// 		if(intval($user_data['idstr']) == '2147483647')
// 		{
// 			echo intval('3436468640');
// 			echo "<br><br>";
// 			var_dump($user_data);die;
// 			$data ['message'] = "API授权失败，请稍后再试，跳转中...";
// 			$data ['redirect_time'] = 3;
// 			$data ['redirect'] = BASE_URL;
// 			$this->output("blank.tpl",$data);
// 			die;
// 		}
		$res = $this->user_model->getInfoByWuid($user_data['idstr']);
		if($res)
		{
			$this->user_model->updateUser($user_data);
			$id= $res['id'];
		}else{
			//发连接微博
			$text = "神器啊，banweibo.com，不用翻墙也能看世界名人的推特了。信息同步，一键转发到微博，高端洋气上档次。http://banweibo.com";
//			$this->weibo->upload_url_text($text,"http://banweibo.com/img/intro.jpg");
			$id = $this->user_model->addUser($user_data);
		}
		if($id)
		{
			$_SESSION['uid'] = $id;
			//加入twitter session
			if(isset($res['t_oauth_token']) && isset($res['t_oauth_token_secret']))
			{
				if( $res['t_oauth_token'] && $res['t_oauth_token_secret']){
					$_SESSION ['twitter_token'] = array(
					"access_token"=>$res['t_oauth_token'],
					"access_token_secret"=>$res['t_oauth_token_secret']
					);
				}
			}
			//设置登陆cookie
			$this->load->library('encrypt');
			$token_cookie = $this->encrypt->encode($id);
			setcookie("login_type",$token_cookie,time()+3600*24*7,"/",null,false,true);
			$new_user_info = $this->user_model->getUserInfo($_SESSION['uid'],false);
			setcookie("login_uname",$this->encrypt->encode($new_user_info['weibo_uname']),time()+3600*24*7,"/",null,false,true);
			setcookie("login_id",$this->encrypt->encode($new_user_info['weibo_id']),time()+3600*24*7,"/",null,false,true);
			setcookie("login_img",$this->encrypt->encode($new_user_info['weibo_profile_image']),time()+3600*24*7,"/",null,false,true);	
			?>
			<html>
			<head><meta http-equiv="refresh" content="0;url=<?php echo BASE_URL.$url;?>"> </head>
			</html>
			<?php 
		}
	}
	function twitter_callback()
	{
		$status = $this->input->get("status");
		$token =  $this->input->get("token");
		$token_secret =  $this->input->get("token_secret");
		if($status && $token && $token_secret)
		{
			$this->load->model('user_model');
			$res = $this->user_model->updateTwitterToken($token,$token_secret);
			if($res){
				$data['message'] = "绑定成功，跳转中...";
				$_SESSION ['twitter_token'] = array(
				"access_token"=>$token,
				"access_token_secret"=>$token_secret
				);
			//TODO更新用户twitter账户基本信息
			//TODO更新用户twitter账户关注情况
			}else{
				$data['message'] = "sorry,绑定失败，跳转中...";
			}	
		}
		else{
			$data['message'] = "sorry,绑定失败，跳转中...";
		}
		$data ['redirect_time'] = 3;
		$data ['redirect'] = BASE_URL."/index/";
		$this->output("blank.tpl",$data);
	}
}