<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/saetv2.ex.class.php');  
class CI_Weibo extends SaeTClientV2{
	function __construct($params = null) 
	{
		define( "WB_AKEY" , '2885675204' );
		define( "WB_SKEY" , 'dd6450567536e902d1741e18e85a6388' );
		$client_id = WB_AKEY;
		$client_secret = WB_SKEY;
		$access_token = isset($params['access_token'])?$params['access_token']:NULL;
		$refresh_token = isset($params['refresh_token'])?$params['refresh_token']:NULL;
         parent::__construct($client_id,$client_secret,$access_token,$refresh_token); 
     }
}
