<?php
// File Name :utility-helper.php
// Created on :17/5/2010
// Created By :

require_once LIB_DIR_PATH.'vendor/autoload.php';

use MaxMind\Db\Reader;

class UtilityHelper extends Helper
{

	private function __construct()
	{
		// constructor -> not needed
	}

	static function is_valid_email($email)
	{		
		$pattern="/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}/i" ;
		if(preg_match($pattern,$email,$matches))
		{
			if($matches[0]==$email)
			{
				return true;
			}
		}
		return false;

	}
	
	function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	static function get_file_extension($str)
	{
		$i = strrpos($str,".");
		if (!$i) {
			return "";
		}
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
	}

	static function isPositive($mystring)
	{
	
		if($mystring >0)
			return true;
		else
			return false;
	}
	
	static function isPositiveInteger($mystring)
	{
		if($mystring<=0)
			return false;
		for ( $i = 0; $i < strlen($mystring); $i++)
		{
			$ch = $mystring{$i};
	
			if ($ch < "0" || $ch > "9")
			{
			return false;
			}
		}
		return true;
	}
	
	static function isPositiveIntegerWithZero($mystring)
	{
		for ( $i = 0; $i < strlen($mystring); $i++)
		{
			$ch = $mystring{$i};
	
			if ($ch < "0" || $ch > "9")
				{
				return false;
	}
	}
	return true;
	}
	
	
	static function is_valid_domain($site)
	{
		$site_name=$site;
		
		$sub7=substr($site,0,7);
		$sub8=substr($site,0,8);
		
		
		if(strcasecmp($sub7,"http://") ==0)
		$site_name = substr($site,7);
		else if(strcasecmp($sub8,"https://") ==0)
		$site_name = substr($site,8);
		
		$sub4=substr($site_name,0,4);	
		
		if(strcasecmp($sub4,"www.") ==0)
		$site_name = substr($site_name,4);
		
		$sitearray=explode('.',$site_name);
		
		if(!isset($sitearray[1]) || trim($sitearray[1]) =="")
		return false;	
			
		if(preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $site_name) && preg_match("/^.{1,253}$/", $site_name) && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $site_name))
		return true;
		else
		return false;	
	}
	static function is_valid_phone($phone)
	{
		if($phone<=0)
			return false;
		for ( $i = 0; $i < strlen($phone); $i++)
		{
			$ch = $phone{$i};
		
			if (($ch < "0" || $ch > "9") && $ch !="+" && $ch !="-" && $ch !=" ")
			{
				return false;
			}
		}
		return true;
		
		
	}

	
	
	static function get_user_ip()
	{
		$getip="";
		
		if(isset($_SERVER))
		{
			if(isset($_SERVER['HTTP_CLIENT_IP']) && self::check_lan_ip($_SERVER['HTTP_CLIENT_IP']))
			$getip= $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && self::check_lan_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
			$getip= $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']) && self::check_lan_ip($_SERVER['HTTP_X_FORWARDED']))
			$getip= $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && self::check_lan_ip($_SERVER['HTTP_FORWARDED_FOR']))
			$getip= $_SERVER['HTTP_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_FORWARDED']) && self::check_lan_ip($_SERVER['HTTP_FORWARDED']))
			$getip= $_SERVER['HTTP_FORWARDED'];
			else
			$getip= $_SERVER['REMOTE_ADDR'];
		}
		
		if(self::check_lan_ip(getenv('HTTP_CLIENT_IP')))
		$getip= getenv('HTTP_CLIENT_IP');
		else if(self::check_lan_ip(getenv('HTTP_X_FORWARDED_FOR')))
		$getip= getenv('HTTP_X_FORWARDED_FOR');
		else if(self::check_lan_ip(getenv('HTTP_X_FORWARDED')))
		$getip= getenv('HTTP_X_FORWARDED');
		else if(self::check_lan_ip(getenv('HTTP_FORWARDED_FOR')))
		$getip= getenv('HTTP_FORWARDED_FOR');
		else if(self::check_lan_ip(getenv('HTTP_FORWARDED')))
		$getip= getenv('HTTP_FORWARDED');
		else
		$getip= getenv('REMOTE_ADDR');
		
		if($getip !="")
		{
			$getip_array=explode(',',$getip);

			return $getip_array[count($getip_array) -1];
		}
		else
		return $getip;		
	}

	static function check_lan_ip($ip)
	{
		if (!isset($ip) || $ip =='' || $ip ==0 || ($ip >= '127.0.0.0' && $ip <= '127.255.255.255') || ($ip >= '10.0.0.0' && $ip <= '10.255.255.255') || ($ip >= '172.16.0.0' && $ip <= '172.31.255.255') || ($ip >= '192.168.0.0' && $ip <= '192.168.255.255') || ($ip >= '169.254.0.0' && $ip <= '169.254.255.255'))
		return false;
		
		return $ip;
	}

	
	static function get_geo_details_from_ip($ip="")
	{
		if($ip=="")
		$ip=self::get_user_ip();
	
		require_once(LIB_DIR_PATH."geo/geoipcity.inc");
		require_once(LIB_DIR_PATH."geo/geoipregionvars.php");
		$gi = geoip_open(LIB_DIR_PATH."geo/GeoLiteCity.dat",GEOIP_STANDARD);
	
		$record =geoip_record_by_addr($gi,$ip);
	
		geoip_close($gi);
		return $record;
	}
	
	static function get_country_from_ip($ip = "")
	{
		if($ip == "")
		$ip = self::get_user_ip();
		
		
		$geofile 	 = LIB_DIR_PATH."geo/GeoLite2-Country.mmdb";
		
		$datareader  = new Reader($geofile);
		
		$datacontent = $datareader->get($ip);
		
		$datareader->close();
		
		
		$country_code = $datacontent['country']['iso_code'];
		
		return $country_code;
	}
	
	
	

	static function proxyDetection()
	{
		if ($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_X_FORWARDED'] || $_SERVER['HTTP_FORWARDED_FOR'] || $_SERVER['HTTP_CLIENT_IP'] || $_SERVER['HTTP_VIA'] || in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554)))
		return true;			//Proxy
		else
		return false;			//No Proxy 
	}
	

	static function send_mail($to,$sub,$body,$sender_name="",$sender_email="",$replyto="",$html=1,$attachment=array())
	{
		
		if(DEMO_MODE)
			return true;
		
		require_once(LIB_DIR_PATH.'PHPMailer/class.phpmailer.php');
		$mail             = new PHPMailer();
		$mail->CharSet    = DEFAULT_CHARSET;
		
		$db= DAL::get_instance();
		
		$default_sender_email	  = Configuration::get_instance()->read('smtp_sender_email');
		$deafault_sender_name 	  = Configuration::get_instance()->read('smtp_sender_name');
		
		if(Configuration::get_instance()->read('smtp_mailing')=="true")
		{
		
		
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host       = Configuration::get_instance()->read('smtp_host'); // SMTP server
			$mail->SMTPDebug  = (Configuration::get_instance()->read('smtp_debug')==0) ? false : true; // enables SMTP debug information (for testing)
			// 1 = errors and messages
			// 2 = messages only
			$mail->SMTPAuth   = Configuration::get_instance()->read('smtp_auth')=='false' ? false :true; // enable SMTP authentication
			$mail->SMTPSecure = Configuration::get_instance()->read('smtp_secure');
			$mail->Port       = Configuration::get_instance()->read('smtp_port'); // set the SMTP port for the GMAIL server
			$mail->Username   = Configuration::get_instance()->read('smtp_user'); // SMTP account username
			$mail->Password   = Configuration::get_instance()->read('smtp_password'); // SMTP account password
		
			$mail->From = $sender_email=='' ? ( $default_sender_email=='' ? $mail->Username : $default_sender_email ) : $sender_email;
			$mail->FromName = $sender_name=='' ? $deafault_sender_name : $sender_name;
		
		}
		else 
		{
			
			$mail->IsMail(); 
			
			$mail->From = $sender_email=='' ? $default_sender_email : $sender_email;
			$mail->FromName = $sender_name=='' ? $deafault_sender_name : $sender_name;
				
		}
			
		if($replyto == "")
		{
			$replyto =$mail->From;
		}
		$mail->AddReplyTo($replyto );

		$mail->Subject    = $sub;
	
		if($html==1)
		{
			//		$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			$mail->MsgHTML($body);
		}
		else
		{
			$mail->Body = $body;
		}
	
		$address = $to;
		$mail->AddAddress($address,"");
	
		
		if(count($attachment)>0)
		{
		    foreach ($attachment as $file)
		        $mail->AddAttachment($file);
		        
		}   
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
	
		if(!$mail->Send())
		{
			if(ER)
			{
				echo "Mailer Error: " . $mail->ErrorInfo;
			}
			return false;
		} 
		else
		{
			return true;
			//			echo "Message sent!";
		}
	
	}
	
	static function xyz_folder_copy($source, $dest)
	{
		
		if (is_file($source)) {
			return copy($source, $dest);
		}
		
		if (!is_dir($dest)) {
			mkdir($dest);
		}
		
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			//if ($dest !== "$source/$entry") {
				$copy_result=UtilityHelper::xyz_folder_copy("$source/$entry", "$dest/$entry");
				if($copy_result==false)
					return $copy_result;
			//}
		}
		$dir->close();
		return 1;
	}
	
	static function xyz_folder_delete($path)
	{
		if (is_dir($path) === true)
		{
			$files = array_diff(scandir($path), array('.', '..'));
			foreach ($files as $file)
			{
				UtilityHelper::xyz_folder_delete(realpath($path) . '/' . $file);
			}
			return rmdir($path);
		}
		else if (is_file($path) === true)
		{
			return unlink($path);
		}
		return false;
	}
	
	
		static function get_locale_list($localname)
		{  
			$db= DAL::get_instance(); 
			$row=$db->execute_query("select * from ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$string='';
			$string1='';
			$string2='';
			$string3="";
			
			if($row->get_num_records() >1)
			{
				$string3 .='<a href="#" class="dropdown-toggle language-selected" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
				
				if($localname != "")
				{
					$split_select=explode('_',$localname);
					
					$selected_flag=BASE.'images/flags/'.strtolower($split_select[1]).'.png';

					$string3 .='<img style="margin-right:5px;margin-top: -5px;" src="'.$selected_flag.'" />';
				
				}
				else
				$string3 .='<i class="fa fa-language"></i>';
				
				
				$string3 .='<span class="caret"></span></a>';
		
				while($data=$row->fetch_assoc())
				{
					$flagstr="";
		
					$split=explode('_',$data['name']);
		
		
					if($localname == $data['name'])
					{
						$flagstr2=BASE.'images/flags/'.strtolower($split[1]).'.png';
						$string2 .='<li>';
						$string2 .='<a href="#" onclick="LoadLocaleFile(\''.$data['name'].'\');" class="language_link">';
						$string2 .='<img style="margin-right:10px;" src="'.$flagstr2.'" width="17px" /> '.$data['description'].'</a>';
						$string2 .='</li>';
					}
					else
					{
						$flagstr=BASE.'images/flags/'.strtolower($split[1]).'.png';
						$string .='<li>';
						$string .='<a href="#" onclick="LoadLocaleFile(\''.$data['name'].'\');">';
						$string .='<img style="margin-right:10px;width:17px;" src="'.$flagstr.'" />'.$data['description'].'</a>';
						$string .='</li>';
					}
				}
		
				if($string !='')
				{
					$flagstr1="";
		
					if($localname !='')
					{
						$split=explode('_',$localname);
		
						if(isset($split[1]))
						$flagstr1=' style="background-image:url('.BASE.'images/flags/'.strtolower($split[1]).'.png);" ';
					}
		
		
		
					$string1 .=$string3.'<ul class="dropdown-menu language_drop_menu" style="min-width:110px !important;">';
					$string1= $string1.$string2.$string;
					$string1.= ' </ul>';
		
				}
			}
		
		
			return $string1;
		}
		
	
		static function get_category_last_childs_display($catid)
		{
			$db= DAL::get_instance();
			$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=?",array($catid));
			$rowcount=$row->get_num_records();
			
			$string='';
			if($rowcount ==0)
			$string.=$catid;
			
			while($row_data=$row->fetch_assoc())
			{
				$childcount=self::get_category_child_count_display($row_data['id']);
				
				if($string !='')
				$string.=',';
				
				if($childcount >0)
				$string.=self::get_category_last_childs_display($row_data['id']);
				else 
				$string.=$row_data['id'];
			}
			return $string;
		}	
	
	
		static function get_category_child_count_display($cid)
		{
			$db= DAL::get_instance();
		
			$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=?",array($cid));
		
			if($count >0)
			return $count;
			else
			return 0;
		
		}	
		
		static function get_cityid_from_latlng_display($country,$state,$latitude,$longitude)
		{
			$db= DAL::get_instance();
			$cityid=$db->read_single_column("select id from ".TABLE_PREFIX."cities where country=? AND region=? AND latitude=? AND longitude=?",array($country,$state,$latitude,$longitude));
			return $cityid;
		}	
		
		static function is_valid_url($url)
		{      
			if(filter_var($url, FILTER_VALIDATE_URL) == false)
			return false;
			else 
			return true;
		}	
		static function get_withdrawal_configuration_data($field,$uid)
		{
		    $db=DAL::get_instance();
		    $data=$db->read_single_column("SELECT ".$field." FROM ".TABLE_PREFIX."publisher_withdrawal_configuration WHERE pid=?",array($uid));
		    return $data;
		}
};

?>
