<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class CI_Map
{
	public $params;
	public $url;
	public $map_x_start;
	public $map_y_start;
	public $map_model_x;
	public $map_model_y;
	/**
	 * 构造方法里做了一些参数的默认设置
	 * */
	function __construct(){
		$this->url = 'http://maps.google.com/maps/geo';
		$this->params =  array(
		 'sensor' => 'false',
		 'output' =>'json',
		 'oe' => 'utf8',
		 'key' => 'ABQIAAAAm5e8FerSsVCrPjUC9W8BqBShYm95JTkTs6vbZ7nB48Si7EEJuhQJur9kGGJoqUiYond0w-7lKR6JpQ'
		);
		//0度经纬在地图的像素坐标
		$this->map_x_start = 2216;
		$this->map_y_start = 1794;
		//纽约市在地图上的像素坐标
		$this->map_model_x = 1342;
		$this->map_model_y = 1289;
		//纽约市的经纬度
		$this->map_model_lon = -740059731;
		$this->map_model_lat = 407143528;
	}
	/**
	 * 请求google的api获取城市的经纬度
	 * */
	function getLonLatByCityName($city)
	{
		$url = $this->url;
		$i = 0;
		foreach ($this->params as $key=>$value)
		{
			$url .= $i==0?"?":"&";
			$url .= $key.'='.$value;
			$i++;
		}
		$url .= '&q='.urlencode($city);
		$data = json_decode(file_get_contents($url),true);
		if($data ['Status']['code'] == 200)
		{
			$pointers = $data['Placemark'][0]['Point']['coordinates'];
		}
		return $pointers;
	}
	/**
	 * 输入坐标经纬度，返回在地图上的x坐标
	 * */
	function getMapX($lon)
	{
		//系数
		$i = ($this->map_model_x-$this->map_x_start)/$this->map_model_lon;
		return $this->map_x_start + $lon*$i;
	}
	/**
	 * 输入坐标经纬度，返回在地图上的y坐标
	 * */
	function getMapY($lat)
	{
		//系数
		$i = ($this->map_model_y-$this->map_y_start)/$this->map_model_lat;
		return $this->map_y_start + $lat*$i;
	}
}