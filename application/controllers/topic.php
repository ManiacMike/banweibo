<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Topic extends CI_Controller {
	function __construct()
	{
		parent::__construct ();
		$this->load->model("map_model");
	}
	function index()
	{
		$this->output("topic.tpl",array());
	}
	/**
	 * 返回坐标值 默认返回全部
	 * */
	function mapping()
	{
		$city = $this->input->post('city');
		$city = !$city?'all':$city;
		if($city == 'all')
		{
			$result['locations'] = $this->map_model->getAllMapLocation();
		}else{
			$result['locations'] = $this->map_model->getMapLocation($city);
		}
		$result['width'] = 4704;
		$result['height'] = 2953;
		echo json_encode($result);
	}
	/**
	 * 获取一个国家的信息
	 * */
	function getTopic()
	{
		$woeid = $this->input->post('id');
		$topic = $this->getTopicByWoeid($woeid);
		$cities = $this->map_model->getCityByCountry($woeid);
//		foreach($cities as $key=>$city)
//		{
//			$cities[$key]['topic'] = $this->getTopicByWoeid($city['woeid']);
//		}
		echo json_encode(array('topic'=>$topic,'city'=>$cities));
	}
	/**
	 * 根据ID获取相应的话题
	 * */
	private function getTopicByWoeid($id)
	{
		$memcache = new memcache;
		$mem_con = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		if($mem_con && $memcache->get("topics_trends_{$id}"))
		{
			return $memcache->get("topics_trends_{$id}");
		}
		$this->load->model("catch_data_model");
		$res = $this->catch_data_model->getDataOld("trends_place",array("id"=>$id));
		if(isset($res[0]["trends"]))
		{
			if($mem_con)
			{
				$memcache->set("topics_trends_{$id}",$res[0]["trends"],MEMCACHE_COMPRESSED,180);
			}
			return $res[0]["trends"];
		}
		return array();
	}
	/**
	 * 测试
	 * */
	function map()
	{
		$memcache = new memcache;
		$mem_con = $memcache ->connect('localhost', $this->config->item('memcache_port'));
		$memcache->delete("topics_trends_23424768");
		$this->output("map.tpl",array());
	}
}