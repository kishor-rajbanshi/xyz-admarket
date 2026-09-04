<?php
class LoginHelper extends Helper
{
	private function __construct()
	{
		// constructor -> not needed
	}


	static function  validate_user_login($redir="")
	{
		$valid=true;
		if(isset($_COOKIE[COOKIE_USERNAME]) && isset($_COOKIE[COOKIE_PASSWORD]))
		{
			$username = self::read_cookie_param(COOKIE_USERNAME);
			$password = self::read_cookie_param(COOKIE_PASSWORD);
			$db= DAL::get_instance();
			$loginId = $db->read_single_column("select id from ".TABLE_PREFIX."users where username=? and password=? and (adv_status=1 or pub_status=1) LIMIT 0,1", array($username,$password));
			
			if(self::read_cookie_param(COOKIE_LOGINID) != $loginId)
			$valid=false;
		}
		else
		{
			$valid=false;
		}
		
		
		if(!$valid && $redir !="")
		{
			header("Location: $redir");
			die;
		}
		return $valid;

	}

	static function get_usr_id($usr_email="")
	{
		if($usr_email=="")
		{
			$usr_email=self::read_cookie_param(COOKIE_LOGINID);
		}
		return $db= DAL::get_instance()->read_single_column("select id  from ".TABLE_PREFIX."users where email=?", array($usr_email));
	}

	
	
	static function  validate_admin_login($redir="")
	{
		$valid=true;
		if(isset($_COOKIE[COOKIE_ADMIN_USERNAME]) && isset($_COOKIE[COOKIE_ADMIN_PASSWORD]))
		{
			$username = self::read_cookie_param(COOKIE_ADMIN_USERNAME);
			$password = self::read_cookie_param(COOKIE_ADMIN_PASSWORD);
			$admintype= self::read_cookie_param(COOKIE_ADMIN_TYPE);
			$db= DAL::get_instance();
			
			
			if($admintype ==1)
			$loginId = $db->read_single_column("select id from ".TABLE_PREFIX."admin where username=? and password=? AND type=? AND status=1", array($username,$password,$admintype));
			else
			$loginId = $db->read_single_column("select id from ".TABLE_PREFIX."admin where username=? and password=? AND type=?", array($username,$password,$admintype));
			
			
			if($_COOKIE[COOKIE_ADMIN_LOGINID] != $loginId)
			{
				$valid=false;
			}
			else 
			{
				if(self::get_addon_status('subadmin_enabled') ==1 && $admintype > 0 && $valid)
				{
					$db= DAL::get_instance();
				
					$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."admin WHERE id=? AND type > 0 AND status=1",array($loginId));
					$rowdata=$row->fetch_assoc();
				
					$array=array('manage_user-mu-5','system_reports-sr-4','advertiser_payments-ap-4','user_reports-ur-4','publisher_withdrawals-pw-4','advertisers_ads-aa-3','adcodes-ac-5');
					
					if(self::get_addon_status('category-targeting_enabled') ==1)
					{
					$array=	array_diff($array, array('user_reports-ur-5'));
						$array=array_merge($array,array('user_reports-ur-5','publishers_site-ms-4'));
					}
					
					if(self::get_addon_status('newsletter-admarket_enabled') ==1)
					{
						$array=array_merge($array,array('newsletter_nm-nm-3'));
					
					}
					
					if(self::get_addon_status('referral_enabled') ==1)
					{
					    $array=array_merge($array,array('referral_stat-rr-4'));
					}
					
				
					$globalarray=array();
					foreach($array as $key=>$value)
					{
						$valuearray=explode('-',$value);
						for($i=1;$i <= $valuearray[2];$i++)
						{
							if($rowdata[$valuearray[1].'_'.$i] ==1)
							$globalarray[$valuearray[1].'_'.$i]=1;
						}
					}
				
				
				$GLOBALS['privilege']=$globalarray;
				
				}
			}
		}
		else
		{
			$valid=false;
		}
		if(!$valid && $redir !="")
		{
			header("Location: $redir");
			die;
		}
		
		return $valid;
	}
	
	
	static function get_addon_status($type)
	{
		$db= DAL::get_instance();
		$id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."config WHERE name=?",array($type));
	
		$value=-1;
		if($id >0)
		$value=$db->read_single_column("SELECT value FROM ".TABLE_PREFIX."config WHERE name=?",array($type));
	
	
		return $value;
	}

};
?>