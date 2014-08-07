<?php 
header('HTTP/1.1 200 OK');
var_dump($_SERVER);DIE;
if(getUidByScreenName($screen_name))
{
	$_GET['c'] = 'u';
	include("index.php");
}
function getUidByScreenName($screen_name)
{
	//TODO放入内存服务器中
	$data = unserialize("cache/screen_name.cae");
	if(isset($data [$screen_name]))
	{
		return $data [$screen_name];
	}
	return false;
}
header('HTTP/1.1 404 Not Found');
file_get_contents("404.html");
die;