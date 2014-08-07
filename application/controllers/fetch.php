<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fetch extends CI_Controller {
	function __construct()
	{
		parent::__construct ();
	}
	function index()
	{
// 		var_dump($_SERVER);die;
		$name= "Ryeo Wook";
		echo file_get_contents("http://www.google.com.hk/search?hl=zh-CN&q=".urlencode($name));die;
		$this->load->library("snoopy");
		$snoopy = &$this->snoopy;
		$snoopy->host = "www.google.com";
		$snoopy->port = "80";
		$snoopy->curl_path = '/usr/bin/curl';
// 		$snoopy->agent = "(compatible; Mozilla/5.0 (Windows NT 6.1))";
// 		$snoopy->rawheaders["Pragma"] = "no-cache";
// 		$snoopy->rawheaders["Content-type"]="text/html";
		$snoopy->rawheaders["Accept"] = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$snoopy->rawheaders["Accept-Encoding"] ="gzip,deflate,sdch";
		$snoopy->rawheaders["User-Agent"] = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36";
		$snoopy->rawheaders['Connection'] = "keep-alive";
		$snoopy->rawheaders['Cache-Control'] = "max-age=0";
// 		$snoopy->rawheaders['Avail-Dictionary'] = "jqg4nL43";
// 		$snoopy->cookies["APISID"] = 'JVHQkvJB_GnWUatx/AvbIq5Gjgzrqg8sXI';
// 		$snoopy->cookies["HSID"] = 'AGfBHCTWyzRnUv1Xt';
// 		$snoopy->cookies["NID"] = "67=D8Q6Exh3p3NIQaI19VrrNb5oAjjt_JfGnitqApndK9kOAfp50pA7mf85bZ2eB4U0ldBrVPRUQ7agQFJya4qGjqi2ITMj4RSuY4rXzzbHPIwCXtN-j3yj4Khn5sJLPz65";
// 		$snoopy->cookies["HSID"] = "AGfBHCTWyzRnUv1Xt";
// 		$snoopy->cookies["PREF"] = "D=48906c2a4734de5e:U=21679d928eab8755:FF=0:LD=zh-CN:TM=1371443355:LM=1371549564:GM=1:S=C1NWVRyboJotGcWA";
		// 		$snoopy->_submit_method = "GET";
// 		$action = "https://www.google.com/search";
// 		$formvars["q"] = urlencode($name);
// 		$snoopy->submit($action,$formvars);
		$snoopy->fetch("http://www.google.com/search?hl=zh-CN&q=".urlencode($name)); //获取所有内容
		echo $snoopy->results; //显示结果
		echo "error fetching document: ".$snoopy->error."\n";
	}
}
