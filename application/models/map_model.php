<?php
/**
 * @author mike
 * 地区处理类
 * */
class Map_model extends Base_model
{
	public $tbData;
	function __construct()
	{
		parent::__construct ();
		$this->tbData = 'place';
	}
	function getMapLocation($cityName)
	{
		$this->load->library("map");
		$res = $this->getSingleFiledValues("*",$this->tbData,"name='{$cityName}'");
		return array($this->map->getMapX($res['lon']),$this->map->getMapY($res['lat']));
	}
	function getAllMapLocation()
	{
		$this->load->library("map");
		$res = $this->getFiledValues("name,woeid,placeTypeCode,map_x,map_y,lon,lat,country",$this->tbData,"`if_show`=1 && `first_show`=1");
		foreach ($res as $key =>$one)
		{
			if($one['placeTypeCode'] == 7)
			{
				if($one ['map_x'] == 0 || $one ['map_y'] == 0 ) //没有默认的坐标
				{
					$one ['map_x'] = $this->map->getMapX($one['lon']);
					$one ['map_y'] = $this->map->getMapY($one['lat']);
				}
			}elseif($one['placeTypeCode'] == 12)
			{
				$one ['city'] = $this->getCityByCountry($one['woeid']);
			}
			unset($one ['lon'],$one ['lat']);
			$res[$key] = $one;
			
		}
		return $res;
	}
	function getCityByCountry($id)
	{
		return $this->getFiledValues("woeid,name",$this->tbData,"parentid='{$id}' && if_show=1");
	}
}