<?php
/**
 * @author mike
 * 处理收藏
 * */
class Common_model extends Base_model
{
	/**
	 * 检查登陆
	 * @return 1已登录 0未登录
	 * */
	function checkLogin()
	{
		if(isset($_SESSION['uid']) && $_SESSION['uid'] !=0)
		{
			return 1;
		}
//		elseif(1){
//			return 0;
//		}
		elseif(isset($_COOKIE['login_type']) && $_COOKIE['login_type'])
		{
			$this->load->library('encrypt');
			$uid = $this->encrypt->decode($_COOKIE['login_type']);
			if($uid)
			{
				$res = $this->user_model->getUserInfo($uid,false);
				if($res)
				{
					$_SESSION ['uid'] = $res ['id'];
					$_SESSION ['token'] ['access_token']= $res['token'];
					if($res['t_oauth_token'] && $res['t_oauth_token_secret'])
					$_SESSION ['twitter_token'] = array(
					"access_token"=>$res['t_oauth_token'],
					"access_token_secret"=>$res['t_oauth_token_secret']
					);
					return 1;
				}
			}
		}
		return 0;
	}
	/**
	 * 检查twitter是否绑定
	 * */
	function checkTwitterLogin()
	{
//		return 0;
		if(isset($_SESSION['twitter_token']) && isset($_SESSION['twitter_token']['access_token']) && isset($_SESSION['twitter_token']['access_token_secret']))
		{
			if($_SESSION['twitter_token']['access_token'] && $_SESSION['twitter_token']['access_token_secret'])
			{
				return 1;
			}
		}
		return 0;
	}
	/**
	 * 在原数组中加入用户的授权token
	 * @param $param原数组
	 * */
	function addTwitterTokenParam($param)
	{
		$param ['token'] = $_SESSION['twitter_token']['access_token'];
		$param ['token_secret'] = $_SESSION['twitter_token']['access_token_secret'];
		return $param;
	}
	/**
	 * 获取总tuser数量缓存
	 * */
	function getTotalTuserNum()
	{
		return file_get_contents(FCPATH."cache/total.cae");
	}
	/**
	 * 总tuser数量缓存加$num
	 * @param $num加上的数字
	 * */
	function addTotalTuserNum($num)
	{
		$cur = file_get_contents(FCPATH."cache/total.cae");
		return file_put_contents(FCPATH."cache/total.cae",$cur+$num);
	}
	/**
	 * 从db生成tuser数量缓存
	 * */
	function truncTuserNum()
	{
		$this->load->model('admin_model');
		$num = $this->admin_model->getTuserCount();
		return file_put_contents(FCPATH."cache/total.cae",$num);
	}
	/**
	 * 生成个性域名缓存
	 * */
	function createScreenNameCache()
	{
		return file_get_contents(BASE_URL."/sort_data/createScreenNameCache/");
	}
	/**
	 * 获取客户端IP
	 * */
	function getClientIP()
	{
		if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
		else $ip = "Unknow";
		return $ip;
	}
	/**
	 * 初始化当前服务器获取一个twitter api请求对象
	 * 【弃用，现使用twitter library对象】
	 * */
	function getTwitterConnection()
	{
		require_once (FCPATH.'twitter_api/twitteroauth/twitteroauth.php');
		require_once (FCPATH.'twitter_api/config.php');
		if($this->checkTwitterLogin())
		{
			return new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, $_SESSION ['twitter_token'] ['access_token'], $_SESSION ['twitter_token'] ['access_token_secret'] );
		}else{
			return new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, $this->config->item('default_twitter_token'), $this->config->item('default_twitter_token_secret') );
		}
	}
	/**
	 * 在数组中添加随即的token
	 * @param $arr输入原数组
	 * */
	function addRandomToken($arr){
		$res = $this->getSingleFiledValues("t_oauth_token,t_oauth_token_secret","user","t_oauth_token !='' && t_oauth_token_secret !='' order by rand()");
		$arr ['token'] = $res['t_oauth_token'];
		$arr ['token_secret'] = $res['t_oauth_token_secret'];
		return $arr;
	}
	/**
	 * 在数组中添加系统账号的token
	 * @param $arr输入原数组
	 * @param $special特定系统账号ID
	 * */
	function addSysToken($arr,$special=false)
	{
		if($special==false)
		{
			$res = $this->getSingleFiledValues("t_oauth_token,t_oauth_token_secret","sys_account","1 order by rand()");
		}else{
			$res = $this->getSingleFiledValues("t_oauth_token,t_oauth_token_secret","sys_account","id ={$special}");
		}
		$arr ['token'] = $res['t_oauth_token'];
		$arr ['token_secret'] = $res['t_oauth_token_secret'];
		return $arr;
	}
	/**
	 * 将数组中twitterID user_id字段换成特定的系统twitterID
	 * @param $arr输入原数组
	 * @param $special特定系统账号ID
	 * */
	function alterSysUid($arr,$special){
		$res = $this->getSingleFiledValues("tid","sys_account","id ={$special}");
		$arr ['user_id'] = $res['tid'];
		return $arr;
	}
	/**
	 * 获取一个随机的twitter token
	 * */
	function getValidToken()
	{
		$res = $this->getSingleFiledValues("t_oauth_token,t_oauth_token_secret","user","t_oauth_token !='' && t_oauth_token_secret !='' order by rand()");
		return $res;
	}
	/**
	 * 把某一条数据标记为if_error=1
	 * */
	function markTuserError($tuid,$message="")
	{
		$vArr = array("if_error"=>1);
		if($message!="")
			$vArr['error_message'] = $message;
		return $this->updateRecords("tuser",$vArr,"id={$tuid}");
	}
}