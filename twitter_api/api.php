<?php
session_start ();
require_once ('twitteroauth/twitteroauth.php');
require_once ('config.php');
//如果请求连接中带有token则使用自定义token
$oauth_token = $_GET['token']?$_GET['token']:'297964934-qYqK9yxy6aDONMYueSZwPUeK41qJIfvM3FqsvWFM';
$oauth_token_secret = $_GET['token_secret']?$_GET['token_secret']:'Ze8YQXBdy5Eoj7d5g26TKbpVQ8XqUH5wfnMnW5sjlc';
///* If access tokens are not available redirect to connect page. */
//if (empty ( $_SESSION ['access_token'] ) || empty ( $_SESSION ['access_token'] ['oauth_token'] ) || empty ( $_SESSION ['access_token'] ['oauth_token_secret'] ))
//{
//	$_SESSION ['access_token'] = array (
//		'oauth_token' => $oauth_token,
//		'oauth_token_secret' => $oauth_token_secret
//	);
//
//}
///* Get user access tokens out of the session. */
//$access_token = $_SESSION ['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret );
$param = $_GET;
unset ( $param ['action'] );
if( isset($param ['token']) )unset($param ['token']);
if( isset($param ['token_secret']) )unset($param ['token_secret']);
switch ($_GET ['action'])
{
	case 'follow' :
		echo $connection->post ( 'friendships/create', $param );
		break;
	case 'unfollow':
		echo $connection->post ( 'friendships/destroy', $param );
		break;
	case 'homeline' :
		echo $connection->get ( 'statuses/home_timeline', $param );
		break;
	//include_rts是否包括转发exclude_replies 是否包括回复 contributor_details 
	case 'usertweets' :
		echo $connection->get ( 'statuses/user_timeline', $param );
		break;
	case 'post_tweet':
		$param ['status'] = urldecode($param ['status']);
		echo $connection->post ( 'statuses/update', $param );
		break;
	case 'userinfo' :
		echo $connection->get ( 'users/show', $param );
		break;
	case 'profile_image' :
		$json = $connection->get ( 'users/profile_image', $param );
		if($json)
		{
			if(preg_match("/href=\"(.*)\">/i", $json,$matches))
			{
				//替换normal,large,mini为reasonably_small获取128px的头像
				die($matches[1]);
			}
		}
		die('error 4');
		break;
	case 'get_follow' :
		echo $connection->get ( 'friends/ids', $param );
		die;
	case 'follow_list' :
		echo $connection->get ( 'friends/list', $param );
		die;
	case 'search_user' :
		echo $connection->get ( 'users/search', $param );
		die;
	case 'search':
		$param ['q'] = urlencode($param ['q']);
		$param ['include_entities'] = 1;
		$request_url = "http://search.twitter.com/search.json?";
		$count= 0;
		foreach ($param as $key=>$value)
		{
			if($count != 0)
			{
				$request_url .="&";
			}
			$request_url .= $key."=".$value;
			$count++;
		}
		echo file_get_contents($request_url);
//		echo $connection->http($request_url,"GET",$param);
		die;
	//320493004534185984
	default :
		echo $connection->get ( str_replace("_","/",$_GET ['action']), $param );
		die;
}
