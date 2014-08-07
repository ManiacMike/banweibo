<?php
/**
 * @author mike
 * 后台数据处理类
 * */
class Filter_model
{
	function checkSensitiveWords($word){
		$sensitiveWords = $this->getSensitiveWords();
		foreach ($sensitiveWords as $sensitiveWords)
		{
			if(strpos($word,$sensitiveWords)!==false)
			{
				return false;
			}
		}
		return true;
	}
	private function getSensitiveWords(){
		return array("连岳","五岳散人","dalai","达赖","民主","64","六四","democracy","天安门","法轮功","李洪志","nude","naked","sex","艾未未","刘小波","零八宪章","aiweiwei","西藏","TIBET","唯色");
	}
}