<?php
// File Name :device-helper.php
// Created on :15/10/2014
// Created By :

class DeviceHelper extends Helper
{
	static function get_os_version()
	{
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
		$os_platform="Unknown OS Platform";
		
		$os_array=array(
					'/windows nt 6.2/i'     =>  'Windows 8',
					'/windows nt 6.1/i'     =>  'Windows 7',
					'/windows nt 6.0/i'     =>  'Windows Vista',
					'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
					'/windows nt 5.1/i'     =>  'Windows XP',
					'/windows xp/i'         =>  'Windows XP',
					'/windows nt 5.0/i'     =>  'Windows 2000',
					'/windows me/i'         =>  'Windows ME',
					'/win98/i'              =>  'Windows 98',
					'/win95/i'              =>  'Windows 95',
					'/win16/i'              =>  'Windows 3.11',
					'/iphone/i'             =>  'iPhone',
					'/ipod/i'               =>  'iPod',
					'/ipad/i'               =>  'iPad',
					'/android/i'            =>  'Android',
					'/blackberry/i'         =>  'BlackBerry',
					'/macintosh|mac os x/i' =>  'Mac OS X',
					'/mac_powerpc/i'        =>  'Mac OS 9',
					'/linux/i'              =>  'Linux',
					'/ubuntu/i'             =>  'Ubuntu',
					'/webos/i'              =>  'Mobile'
			);
		
			foreach ($os_array as $regex => $value) 
			{
				if(preg_match($regex,$user_agent)) 
				return $os_platform=$value;
			}
			return $os_platform;
	}
	
	static function get_browser_version() 
	{
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
		$browser="Unknown Browser";
	
		$browser_array  =   array(
				'/msie/i'       =>  'Internet Explorer',
				'/firefox/i'    =>  'Firefox',
				'/safari/i'     =>  'Safari',
				'/chrome/i'     =>  'Chrome',
				'/opera/i'      =>  'Opera',
				'/netscape/i'   =>  'Netscape',
				'/maxthon/i'    =>  'Maxthon',
				'/konqueror/i'  =>  'Konqueror',
				'/mobile/i'     =>  'Handheld Browser'
		);
	
		foreach ($browser_array as $regex => $value) 
		{
			if(preg_match($regex,$user_agent)) 
			return $browser=$value;
		}
		return $browser;
	}
};
?>