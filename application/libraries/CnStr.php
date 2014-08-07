<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class CI_CnStr
{
	/**
	 * 长度是以中2英1标准计算
	 * */
	function cnStrCut($string,$len)
	{
		if($this->cnStrLen($string)<=$len)
			return $string;
		$n = $count = 0;
	    $length = strlen($string);
	    while ($n < $length)
        {
        	$currentByte = ord($string[$n]);
        	$curRes = $this->currentByte($currentByte);
        	switch ($curRes)
        	{
        		case 0:
        		case 1:
        			$count++;
        			$n++;
        			if( $count == $len)
        			{
        				return substr($string,0,$n);
        			}    			
        			break;
        		default:
        			$n+=$curRes;
        			$count+=2;
          			if( $count == $len)
        			{
        				return substr($string,0,$n);
        			}elseif ($count > $len)
        			{
        				return substr($string,0,$n-$curRes);
        			}  			
        	}
        }
	}
	/**
	 * utf8字符 半角，英文数字算1个，全角中文算两个
	 * */
	function cnStrLen($string)
	{
	    $n = $count = 0;
	    $length = strlen($string);
	    while ($n < $length)
        {
        	$currentByte = ord($string[$n]);
        	$curRes = $this->currentByte($currentByte);
        	switch ($curRes)
        	{
        		case 0:
        		case 1:
        			$count++;
        			$n++;
        			break;
        		default:
        			$n+=$curRes;
        			$count+=2;
        	}
            if ($count >= $length)
            {
                break;
            }
        }
        return $count;
	}
	function currentByte($currentByte)
	{     
		if ($currentByte == 9 || $currentByte == 10 || (32 <= $currentByte && $currentByte <= 126))
		{
			return 1;
		}
		elseif (194 <= $currentByte && $currentByte <= 223)
		{
			return 2;
		} 
		elseif (224 <= $currentByte && $currentByte <= 239)
		{
			return 3;
		} 
		elseif (240 <= $currentByte && $currentByte <= 247)
		{
			return 4;
		} 
		elseif (248 <= $currentByte && $currentByte <= 251)
		{
			return 5;
		}
		elseif ($currentByte == 252 || $currentByte == 253)
		{
			return 6;
		}
		return 0;
	}
}