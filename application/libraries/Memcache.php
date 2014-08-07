<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class CI_Memcache extends Memcache
{
	function __construct()
	{
		$this->connect('127.0.0.1', 12000);
	}
}