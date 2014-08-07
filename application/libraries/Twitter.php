<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once (FCPATH.'twitter_api/twitteroauth/twitteroauth.php');
require_once (FCPATH.'twitter_api/config.php');
class CI_Twitter{
	private $oauth_token;
	private $oauth_token_secret;
	function __construct($token =array())
	{
		if($token == array())
		{
			$oauth_token='297964934-qYqK9yxy6aDONMYueSZwPUeK41qJIfvM3FqsvWFM';
			$oauth_token_secret ='Ze8YQXBdy5Eoj7d5g26TKbpVQ8XqUH5wfnMnW5sjlc';
		}else{
			$oauth_token = $token['token'];
			$oauth_token_secret = $token['token_secret'];
		}
		if (empty ( $_SESSION ['access_token'] ) || empty ( $_SESSION ['access_token'] ['oauth_token'] ) || empty ( $_SESSION ['access_token'] ['oauth_token_secret'] ))
		{		
				$this->oauth_token = $oauth_token;
				$this->oauth_token_secret = $oauth_token_secret;
		}
	}
	function getData($param,$action)
	{
		$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, $this->oauth_token, $this->oauth_token_secret );
		switch ($action)
		{
			case 'follow' :
				$data = $connection->post ( 'friendships/create', $param );
				break;
			case 'unfollow':
				$data = $connection->post ( 'friendships/destroy', $param );
				break;
			case 'homeline' :
				$data = $connection->get ( 'statuses/home_timeline', $param );
				break;
			//include_rts是否包括转发exclude_replies 是否包括回复 contributor_details 
			case 'usertweets' :
				$data = $connection->get ( 'statuses/user_timeline', $param );
				break;
			case 'post_tweet':
				$param ['status'] = urldecode($param ['status']);
				$data = $connection->post ( 'statuses/update', $param );
				break;
			case 'userinfo' :
				$data = $connection->get ( 'users/show', $param );
				break;
			case 'get_follow' :
				$data = $connection->get ( 'friends/ids', $param );
				break;
			case 'follow_list' :
				$data = $connection->get ( 'friends/list', $param );
				break;
			case 'search_user' :
				$data = $connection->get ( 'users/search', $param );
				break;
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
				$data = file_get_contents($request_url);
				break;
			default :
				$data = "";
		}
		return json_decode($data,true);
	}
}