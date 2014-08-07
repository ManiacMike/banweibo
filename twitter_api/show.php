<?php
$_screen_name = $_GET['screen_name'];
$cache = dirname(__FILE__)."/cache/{$_screen_name}.cae";
if(file_exists($cache))
{
	echo  file_get_contents($cache);
	die;
}
$res = file_get_contents("http://twitter.com/{$_screen_name}");
if($res)
{
	file_put_contents($cache,$res);
}
echo $res;