<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";


class IndexController extends ApplicationController
{
	function index_action()
	{
         
			//$this->set_title($this->get_label('advertiser publisher network'));
			
			$referral=intval($this->read_get_param('rid'));
			
			if($referral ==0)
		    $referral=intval($this->read_page_param(2));		//For old referral clients	
			
			$db= DAL::get_instance();
			
			if(!(LoginHelper::validate_user_login()) && $referral >0 && $this->get_addon_status('referral_enabled') ==1 && (Configuration::get_instance()->read('advertiser_referral_enabled') ==1 || Configuration::get_instance()->read('publisher_referral_enabled') ==1))
			{
				$expired_time=time()+(30*86400);

				setcookie(COOKIE_REFERRAL,$referral,$expired_time,$this->get_base_path(),$this->get_base_domain());


				$referral_url="";

				if(isset($_SERVER['HTTP_REFERER']))
				$referral_url=$_SERVER['HTTP_REFERER'];

				setcookie(COOKIE_REFERRAL_URL,$referral_url,$expired_time,$this->get_base_path(),$this->get_base_domain());



				$impTime =date("Y",time());
				$impTime.=date("m",time());
				$impTime.=date("d",time());

				$sqlup=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_visits_daily SET visits=visits+1 WHERE uid=? AND url=? AND time=?",array($referral,$referral_url,$impTime));

				if($sqlup->get_num_records() ==0)
				$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_visits_daily (`uid`,`visits`,`time`,`url`) values (?,?,?,?)",array($referral,1,$impTime,$referral_url));
			}			
			
			
			$external_theme_exists = Configuration::get_instance()->read('exsternal_theme_exists');
			$exsternal_theme_url   = Configuration::get_instance()->read('exsternal_theme_url'); 	
			
			if($external_theme_exists == 1 && $exsternal_theme_url != "")
			header("Location: ".$exsternal_theme_url);			
			
			
			
			$res =$db->execute_query("SELECT * FROM ".TABLE_PREFIX."custom_banners WHERE status=1 AND banner <>'' ORDER BY priority ASC");
			$this->set_result("res",$res);
			
			if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
			else
			$localname=DEFAULT_LOCALE;
			
			if(Configuration::get_instance()->read('language_enabled') ==1)
			$localeid=intval($this->get_locale_id($localname));
			else
			$localeid=0;
			
			$this->set_variable('localeid',$localeid);
	}

	
	
	function testimonial_action()
	{
		$db= DAL::get_instance();
		
		/*
		$localname='';
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		
	
		if(Configuration::get_instance()->read('language_enabled') ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;
 		
		$this->set_variable('localeid',$localeid);
		*/
		
		
		$testimonial=$db->execute_query("select t.*,u.username,u.domain,u.id as userid from ".TABLE_PREFIX."testimonial t INNER JOIN ".TABLE_PREFIX."users u ON u.id=t.uid WHERE (u.adv_status=1 OR u.pub_status=1) order by t.time desc LIMIT 0,20");
		$this->set_result("res",$testimonial);
	}
	function slider_action()
	{
		$db= DAL::get_instance();
		
		$homedatas=$db->execute_query("SELECT id,logo FROM ".TABLE_PREFIX."users WHERE logo_display=1 AND (adv_status=1 OR pub_status=1) AND logo <>''");
		$ststr="";
		while($homedatas_data=$homedatas->fetch_assoc())
		{
			if($ststr !="")
				$ststr.='='.$homedatas_data['id'].'/'.$homedatas_data['logo'];
			else
				$ststr.=$homedatas_data['id'].'/'.$homedatas_data['logo'];
		}
		
		$this->set_variable("ststr",$ststr);
	}
	function terms_action()
	{
		$db= DAL::get_instance();
	
		$from=$this->read_page_param(1);
		if($from=="")
		$from=0;
	
		
		$row=$db->execute_query("select * from ".TABLE_PREFIX."terms where id=1");
 		$res=$row->fetch_assoc();
		
 		$row1=$db->execute_query("select * from ".TABLE_PREFIX."terms where id=2");
 		$res1=$row1->fetch_assoc();
		
 		
		
		$localname='';
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		
	
		if(Configuration::get_instance()->read('language_enabled') ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;
 		
 		
 		
		if($localeid >0 && isset($res[$localeid.'_description']) && $res[$localeid.'_description'] !='')
		$terms=$res[$localeid.'_description'];
		else 
		$terms=$res['description'];
		
				
		if($localeid >0 && isset($res1[$localeid.'_description']) && $res1[$localeid.'_description'] !='')
		$terms1=$res1[$localeid.'_description'];
		else 
		$terms1=$res1['description'];
		
		
		$terms=str_replace('{ADMARKETNAME}', Configuration::get_instance()->read('admarket_name'), $terms);
		$this->set_variable("terms",nl2br($terms),0);
		
		
		$terms1=str_replace('{ADMARKETNAME}', Configuration::get_instance()->read('admarket_name'), $terms1);
		$this->set_variable("terms1",nl2br($terms1),0);
		
		$this->set_variable("from",$from);
	}
	function about_action()
	{
		//$this->set_title($this->get_label('about us'));
		$db= DAL::get_instance();
	
			
		$row=$db->execute_query("select * from ".TABLE_PREFIX."aboutus where id=1");
 		$res=$row->fetch_assoc();
		
		
		$localname='';
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		
	
		if(Configuration::get_instance()->read('language_enabled') ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;
 		
 		
 		
		if($localeid >0 && isset($res[$localeid.'_description']) && $res[$localeid.'_description'] !='')
		$description=$res[$localeid.'_description'];
		else 
		$description=$res['description'];
		
		
		$description=str_replace('{ADMARKETNAME}', Configuration::get_instance()->read('admarket_name'), $description);
		$this->set_variable("description",nl2br($description),0);

	}
	
	function advertiser_action()
	{
		
		$external_theme_exists = Configuration::get_instance()->read('exsternal_theme_exists');
		$exsternal_theme_url   = Configuration::get_instance()->read('exsternal_theme_url'); 	
		
		if($external_theme_exists == 1 && $exsternal_theme_url != "")
		header("Location: ".$exsternal_theme_url);		
		
		
		//$this->set_title($this->get_label('advertiser'));
		$db= DAL::get_instance();
		$logedin=0;
			
		if(LoginHelper::validate_user_login())					// code to check user login
			$logedin=1;
		$this->set_variable('logedin',$logedin);
		
		$username="";
		if($_POST)
		{
			$username=$this->read_post_param('username');
			$password=$this->read_post_param('password');
		
		
			if($username=="" || $password=="")
			{
				$this->set_notice('mandatory');
			}
			else
			{
		
				$password1=md5($password);
		
		
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=? and (adv_status=1 or pub_status=1)",array($username,$password1));
				if($count >0)
				{
		
		
		
					$res1=$db->execute_query("select * from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
					$value1=$res1->fetch_assoc();
					$userid=$value1['id'];
					$orig_password=$value1['password'];
					$adv_status=$value1['adv_status'];
					$pub_status=$value1['pub_status'];
					$localeid=$value1['locale'];
		
					if($localeid >0)
					{
						$localename=$db->read_single_column("select name from ".TABLE_PREFIX."locale where id=?",array($localeid));
						
						if($localename !='')
						$this->set_locale($localename);
					}
		
					setcookie(COOKIE_USERNAME,$username,0,$this->get_base_path(),$this->get_base_domain());
					setcookie(COOKIE_PASSWORD,$password1,0,$this->get_base_path(),$this->get_base_domain());
					setcookie(COOKIE_LOGINID,$userid,0,$this->get_base_path(),$this->get_base_domain());
		
		
		
					if($adv_status==1 && $pub_status!=1)
					setcookie(COOKIE_ADMARKETTYPE,1,0,$this->get_base_path(),$this->get_base_domain());
					else if($adv_status!=1 && $pub_status==1)
					setcookie(COOKIE_ADMARKETTYPE,2,0,$this->get_base_path(),$this->get_base_domain());
					else if($adv_status==1 && $pub_status==1)
					setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
		
		
		
		
					if($pub_status ==1)
					{
						$day_begin1 =date("Y",time());
						$day_begin1.=date("m",time());
						$day_begin1.=date("d",time());
						$day_begin1.=date("H",time());
						
						$ip=UtilityHelper::get_user_ip();
						$result1=$db->execute_query("update ".TABLE_PREFIX."users set pub_logintime=?,pub_loginip=? where id=?",array($day_begin1,$ip,$userid));
						
					}
		
		
		
					if(($adv_status==1 && $pub_status!=1) || ($adv_status==1 && $pub_status==1))
						header("Location: ".$this->make_url("user/advertiser_home"));
					else if($adv_status!=1 && $pub_status==1)
						header("Location: ".$this->make_url("user/publisher_home"));
		
		
					exit;
		
		
		
				}
				else
				{
					$count_second=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
		
					if($count_second==0)
					{
						$this->set_notice('invalid username or password');
					}
					else
					{
						$this->set_notice('your account is inactive');
					}
		
				}
		
			}
		
			$this->set_variable("username",$username);
		}
		else
		{
			if(DEMO_MODE)
			{
				$this->set_variable('username','demo');
				$this->set_variable('password','demo');
			}
		}

	
	}
	function publisher_action()
	{
		
		$external_theme_exists = Configuration::get_instance()->read('exsternal_theme_exists');
		$exsternal_theme_url   = Configuration::get_instance()->read('exsternal_theme_url'); 	
		
		if($external_theme_exists == 1 && $exsternal_theme_url != "")
		header("Location: ".$exsternal_theme_url);
				
		
		//$this->set_title($this->get_label('publisher'));
		$db= DAL::get_instance();
		
		$username="";
		if($_POST)
		{
			$username=$this->read_post_param('username');
			$password=$this->read_post_param('password');
		
		
			if($username=="" || $password=="")
			{
				$this->set_notice('mandatory');
			}
			else
			{
		
				$password1=md5($password);
		
		
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=? and (adv_status=1 or pub_status=1)",array($username,$password1));
				if($count >0)
				{
		
		
		
					$res1=$db->execute_query("select * from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
					$value1=$res1->fetch_assoc();
					$userid=$value1['id'];
					$orig_password=$value1['password'];
					$adv_status=$value1['adv_status'];
					$pub_status=$value1['pub_status'];
					$localeid=$value1['locale'];
		
					if($localeid >0)
					{
						$localename=$db->read_single_column("select name from ".TABLE_PREFIX."locale where id=?",array($localeid));
						
						if($localename !='')
						$this->set_locale($localename);
					}
		
		
		
					setcookie(COOKIE_USERNAME,$username,0,$this->get_base_path(),$this->get_base_domain());
					setcookie(COOKIE_PASSWORD,$password1,0,$this->get_base_path(),$this->get_base_domain());
					setcookie(COOKIE_LOGINID,$userid,0,$this->get_base_path(),$this->get_base_domain());
		
		
		
					if($adv_status==1 && $pub_status!=1)
						setcookie(COOKIE_ADMARKETTYPE,1,0,$this->get_base_path(),$this->get_base_domain());
					else if($adv_status!=1 && $pub_status==1)
						setcookie(COOKIE_ADMARKETTYPE,2,0,$this->get_base_path(),$this->get_base_domain());
					else if($adv_status==1 && $pub_status==1)
						setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
		
		
		
		
					if($pub_status ==1)
					{
						$day_begin1 =date("Y",time());
						$day_begin1.=date("m",time());
						$day_begin1.=date("d",time());
						$day_begin1.=date("H",time());
						
						$ip=UtilityHelper::get_user_ip();
						$result1=$db->execute_query("update ".TABLE_PREFIX."users set pub_logintime=?,pub_loginip=? where id=?",array($day_begin1,$ip,$userid));
					}
		
		
		
					if(($adv_status==1 && $pub_status!=1) || ($adv_status==1 && $pub_status==1))
						header("Location: ".$this->make_url("user/advertiser_home"));
					else if($adv_status!=1 && $pub_status==1)
						header("Location: ".$this->make_url("user/publisher_home"));
		
		
					exit;
		
		
		
				}
				else
				{
					$count_second=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
		
					if($count_second==0)
					{
						$this->set_notice('invalid username or password');
					}
					else
					{
						$this->set_notice('your account is inactive');
					}
		
				}
		
			}
		
			$this->set_variable("username",$username);
		}
		else
		{
			if(DEMO_MODE)
			{
				$this->set_variable('username','demo');
				$this->set_variable('password','demo');
			}
		}
		
	}
	
	function contact_us_action()
	{
		require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';
		
		//$this->set_title($this->get_label('contact us'));
		$db= DAL::get_instance();
		
		
		$recaptcha_public_key=Configuration::get_instance()->read('recaptcha_public_key');
		$this->set_variable('recaptcha_public_key',$recaptcha_public_key);
		
		
		$name="";
		$email="";
		$subject="";
		$description="";
		$message_response="";
		$success_flag=0;
		$lavender_theme=0;
		
		
		$active_theme	=	Configuration::get_instance()->read('active_theme');
		
		if($active_theme == 'wild-lavender')
		$lavender_theme = 1;
		
		
		
		if(isset($_POST['submit_contact']))
		{
			$name=$this->read_post_param("name");
			$email=$this->read_post_param("email");
			$subject=$this->read_post_param("subject");
			$description=$this->read_post_param("description");
			
			if($lavender_theme > 0)
			$this->disable_notice_area();			
			
			
			$description=nl2br($description);
			
			$cap_flg=0;
			$captcha_response="";


			if(Configuration::get_instance()->read('enable_captcha_verification')==1)
			{
					$recaptcha_private_key = Configuration::get_instance()->read('recaptcha_private_key');
					$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key);
					if(isset($_POST['g-recaptcha-response']))
					{
						$cap_flg=1;
					 	$captcha_response = $reCaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
					}
			}

			
			if($name=="" || $email=="" || $subject=="" || $description=="")
			$message_response = $this->get_message('mandatory');
			else if($cap_flg==1 && !$captcha_response->isSuccess() && Configuration::get_instance()->read('enable_captcha_verification')==1)
			$message_response = $this->get_message('captcha failed');
		    	else if(!UtilityHelper::is_valid_email($email))
			$message_response = $this->get_message('invalid email address');
			else 
			{
				$replayto=$email;
				$replay_name=$name;
				$to=Configuration::get_instance()->read('admin_notification_email');
				
			    if(UtilityHelper::send_mail($to,$subject,$description,"","",$replayto))
				{
					$name 			    = "";
					$email  		    = "";
					$subject  		    = "";
					$description  		= "";					
					$success_flag 	    = 1;
					$message_response   = $this->get_message('mail success');



					if($lavender_theme == 0)
					{
						$this->flash($this->get_message('mail success'),BASE);
						exit;
					}
				}
				else
				$message_response = $this->get_message('error occurred');
			}

			if($lavender_theme == 0)
			$this->set_notice($message_response);
		}
		
		
		
		
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
		
		
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
		
		
		
		$this->set_variable('localeid',$localeid);
		$this->set_variable('direction',$direction);		
		
		
		
		
		
		
		
		$this->set_variable("name",$name);
		$this->set_variable("email",$email);
		$this->set_variable("subject",$subject);
		$this->set_variable("description",$description);
		$this->set_variable("message_response",$message_response);
		$this->set_variable("success_flag",$success_flag);
	}



	
	function register_action()
	{
		if(LoginHelper::validate_user_login())		
		header("Location: ".BASE);
		
		
		require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';
		
		//$this->set_title($this->get_label('user registration'));
		$db= DAL::get_instance();
		$re=$db->execute_query("select code,name from ".TABLE_PREFIX."countries where code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name ASC");
		$this->set_result("re",$re);
		
		
		$client_ip=UtilityHelper::get_user_ip();
		$countrycurrent=UtilityHelper::get_country_from_ip($client_ip);
		$this->set_variable("countrycurrent",$countrycurrent);
		
		
		$recaptcha_public_key=Configuration::get_instance()->read('recaptcha_public_key');
		$this->set_variable('recaptcha_public_key',$recaptcha_public_key);
		
		
		$lavender_theme = 0;
		
		$active_theme	=	Configuration::get_instance()->read('active_theme');
		
		if($active_theme == 'wild-lavender')
		$lavender_theme = 1;		
		
		
		
		if(!$_POST)
		{
			$category=intval($this->read_page_param(1));
			if($category=="" || $category <1 || $category >3)
			$category=3;
			
			$this->set_variable('category',$category);
		}
		
		
		$success_flag = 1;
		$response_message = "";
		
		if(isset($_POST['user_register']))
		{
			if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
			else
			$localname=DEFAULT_LOCALE;
		
			
			$language_enabled=Configuration::get_instance()->read('language_enabled');
			
	
			if($language_enabled ==1)
			$localeid=intval($this->get_locale_id($localname));
			else
			$localeid=0;
			
			
			
			
			$username=$this->read_post_param('username');
			$password=$this->read_post_param('password');
			$cpassword=$this->read_post_param('cpassword');
			$fname=$this->read_post_param('fname');
			$lname=$this->read_post_param('lname');
			$address=$this->read_post_param('address');
			$email=$this->read_post_param('email');
			$phone=$this->read_post_param('phone');
			$country=$this->read_post_param('country');
			$category=$this->read_post_param('category');
			$site=$this->read_post_param('site');
			$terms=$this->read_post_param("terms");
			
			
			if($lavender_theme > 0)
			$this->disable_notice_area();
			

		
			$clogo = "";
			
			if(isset($_FILES["clogo"]["name"]))
			$clogo=$_FILES["clogo"]["name"];
			
			
			$this->set_variable('username',$username);
			$this->set_variable('fname',$fname);
			$this->set_variable('lname',$lname);
			$this->set_variable('address',$address);
			$this->set_variable('email', $email);
			$this->set_variable('phone', $phone);
			$this->set_variable('country',$country);
			$this->set_variable('category',$category);
			$this->set_variable('site',$site);
			$this->set_variable("terms",$terms);
			

			
			$cap_flg=0;
			$captcha_response="";

			if(Configuration::get_instance()->read('enable_captcha_verification')==1)
			{
					$recaptcha_private_key = Configuration::get_instance()->read('recaptcha_private_key');
					$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key);
					if(isset($_POST['g-recaptcha-response']))
					{
						$cap_flg=1;
					 	$captcha_response = $reCaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
					}
			}


				
			$email_verification_enabled=Configuration::get_instance()->read('email_verification_enabled');
			
			
			
			if($category==1)                //Advertiser Only
			{
				if($email_verification_enabled ==1)
				$adv_status=-3;
				else 
				$adv_status=Configuration::get_instance()->read('adv_default_status');
					
				$pub_status=-2;
			}
			else if($category==2)           //Publisher Only
			{
				if($email_verification_enabled ==1)
				$pub_status=-3;
				else
				$pub_status=Configuration::get_instance()->read('pub_default_status');
				
				$adv_status=-2;
			}
			else if($category==3)            //Advertiser & Publisher 
			{
				if($email_verification_enabled ==1)
				{
					$adv_status=-3;
					$pub_status=-3;
				}
				else 
				{
					$adv_status=Configuration::get_instance()->read('adv_default_status');
					$pub_status=Configuration::get_instance()->read('pub_default_status');
				}
			}
			
			$password1=md5($password);
			
			
			if($username=="" || $password=="" || $cpassword=="" || $email=="" || $fname=="" || $lname=="" || $address=="" || $country=="" || $category == 0)
			{
				$success_flag = 0;
				$response_message = $this->get_message('mandatory');			
			}
			else if(!preg_match('/^[a-zA-Z][a-zA-Z0-9\._]{1,}$/', $username))
			{
				$success_flag = 0;
				$response_message = $this->get_message('invalid input for user name');				
			}
			else if($cap_flg==1 && !$captcha_response->isSuccess() && Configuration::get_instance()->read('enable_captcha_verification')==1)
			{
				$success_flag = 0;
				$response_message = $this->get_message('image verification failed');				
			}
			else if(DAL::get_instance()->read_single_column("select username from ".TABLE_PREFIX."users where username='?'", array($username)))
			{
				$success_flag = 0;
				$response_message = $this->get_message('user exists');			
			}
			else if(DAL::get_instance()->read_single_column("select email from ".TABLE_PREFIX."users where email='?'", array($email)))
			{
				$success_flag = 0;
				$response_message = $this->get_message('email exists');				
			}
			else if(strlen($password) < Configuration::get_instance()->read('password_length'))
			{
				$success_flag = 0;
				$response_message = $this->get_message("password length limit",array('x'=>Configuration::get_instance()->read('password_length')));					
			}
			else if($password != $cpassword)
			{
				$success_flag = 0;
				$response_message = $this->get_message('password mismatch');						
			}
			else if($phone != "" && !UtilityHelper::is_valid_phone($phone))
			{
				$success_flag = 0;
				$response_message = $this->get_message('invalid phone no');				
			}
			else if(!UtilityHelper::is_valid_email($email))
			{
				$success_flag = 0;
				$response_message = $this->get_message('invalid email address');					
			}
			else if($site != "" && !UtilityHelper::is_valid_domain($site))
			{
				$success_flag = 0;
				$response_message = $this->get_message('invalid domain');				
			}
			else if($terms !=1)
			{
				$success_flag = 0;
				$response_message = $this->get_message('terms condition agree');				
			}
			else
			{
				$logo_string="";
				$logo_string1="";
				
				if($clogo !="")	
				{
					$extension=explode(".",$_FILES['clogo']['name']);
					$extensionname=strtolower($extension[count($extension)-1]);		
					
					$clogo=time().'.'.$extensionname;
					
					$logo_string=",logo";
					$logo_string1=",?";

					if(!is_dir(DATA_DIR.'/'.LOGO_DIR))
					mkdir(DATA_DIR.'/'.LOGO_DIR,0777);					
				}
				
				
				if($clogo !="" && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg")
				{
					$success_flag = 0;
					$response_message = $this->get_message('image not supported');					
				}
				else if($clogo !="" && $_FILES["clogo"]["error"] > 0)
				{
					$success_flag = 0;
					$response_message = $this->get_message('error occurred');						
				}
				else
				{
					$time1=time();
					$password1=md5($password);
					
					$insert_array=array($username,$password1,$email,$fname,$lname,$address,$country,$phone,$adv_status,$pub_status,$time1,$site,$localeid);
					
					if($clogo !="")	
					$insert_array[]=$clogo;
					
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."users (username,password,email,f_name,l_name,address,country,phone,adv_status,pub_status,regtime,domain,locale".$logo_string.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?".$logo_string1.")",$insert_array);
							
					$uid=$res->get_last_id();							
					
					if($clogo !="")	
					mkdir(DATA_DIR.'/'.LOGO_DIR.'/'.$uid,0777);
					

					if($res->error =="")
					{
						if($clogo !="")	
						{
							if(move_uploaded_file($_FILES["clogo"]["tmp_name"],DATA_DIR."/".LOGO_DIR."/".$uid."/".$clogo))
							{
								$height1=70;
								$width1=120;
									
								$image=new ImageHelper(DATA_DIR."/".LOGO_DIR."/".$uid."/".$clogo);
								$image->resize($width1,$height1,DATA_DIR."/".LOGO_DIR."/".$uid."/".$clogo);
							}						
						}
						
						
						if($this->get_addon_status('referral_enabled') ==1 && (Configuration::get_instance()->read('advertiser_referral_enabled') ==1 || Configuration::get_instance()->read('publisher_referral_enabled') ==1) && isset($_COOKIE[COOKIE_REFERRAL]) && $_COOKIE[COOKIE_REFERRAL] >0)
						{
							$coid=$this->read_cookie_param(COOKIE_REFERRAL);
							$ref_status=$this->check_user_active($coid);
							if($ref_status != 1)
							$coid=0;
							$db->execute_query("UPDATE ".TABLE_PREFIX."users SET rid=?,refferal_status=? WHERE id=?",array($coid,$ref_status,$uid));
							
							$referral_url=$this->read_cookie_param(COOKIE_REFERRAL_URL);

							$impTime =date("Y",time());
							$impTime.=date("m",time());
							$impTime.=date("d",time());

							$sqlup=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_visits_daily SET signup=signup+1 WHERE uid=? AND url=? AND time=?",array($coid,$referral_url,$impTime));

							if($sqlup->get_num_records() ==0)
							$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_visits_daily (`uid`,`signup`,`time`,`url`) values (?,?,?,?)",array($coid,1,$impTime,$referral_url));

							setcookie(COOKIE_REFERRAL,"",0,$this->get_base_path(),$this->get_base_domain());
							setcookie(COOKIE_REFERRAL_URL,"",0,$this->get_base_path(),$this->get_base_domain());
						}								
													
						
						$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
						$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');
									
						if($newsletter_enable ==1)
						{
							$username_field=$db->read_single_column("select id from ".TABLE_PREFIX."additional_field_info where field_name=?",array('Name'));

							$this->create_email_and_mapping($email,$pub_status,$adv_status,$username_field,$fname);
						}

						
						
						
						
					if(Configuration::get_instance()->read('admin_notify_registration') ==1)	
					{
						
					$message ='
                    Hello,

A new user has been registered successfully.

Please find the details below.

User Name			: <a href="'.$this->make_base_url("user/profile/".$uid,ADMIN_DIR).'">'.$username.'</a>
User ID     		: '.$uid.'
Advertiser Status 	: '.$this->get_user_status($adv_status).'
Publisher Status    : '.$this->get_user_status($pub_status).'

Best Regards,
'.Configuration::get_instance()->read('admarket_name').'
';
		
					
                    	$admin_email=Configuration::get_instance()->read('admin_notification_email');
                    
                   		UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - User Signup",nl2br($message));
								
					}							
						
						
						
						
						
						
						if($email_verification_enabled ==1)
						{
							$res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=2");
							$result1=$res1->fetch_assoc();
									
					
							if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
							$message=$result1[$localeid.'_message'];
							else
							$message=$result1['message'];
											
							if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
							$subject=$result1[$localeid.'_subject'];
							else
							$subject=$result1['subject'];
					
									
							$confirmurl=BASE."index.php?page=index/confirm_email/".$uid."/".$username."/".md5($uid.$username);
									
							$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
							$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
							$message=str_replace("{USERNAME}",$username,$message);
							$message=str_replace("{CONFIRMURL}",$confirmurl,$message);
									
							UtilityHelper::send_mail($email,$subject,$message);
							$this->flash($this->get_message('registration success'),BASE);
						}
						else 
						{
							$pub_mail_replace=$this->get_label('no account');
							$adv_mail_replace=$this->get_label('no account');
										
							if($pub_status !=-2 || $adv_status !=-2)
							{
								if($pub_status !=-2)
								$pub_mail_replace=$this->get_user_status($pub_status);
								
											
								if($adv_status !=-2)
								{
									if($adv_status ==1)
									{
										$adv_bonus=Configuration::get_instance()->read('advertiser_bonus');
										
										if($adv_bonus < 0 || $adv_bonus =="")
										$adv_bonus=0;
										
										
										if($adv_bonus >0)
										{
											$bonus_seperately_track=Configuration::get_instance()->read('bonus_seperately_track');
					
											if($bonus_seperately_track ==0)
											$bonus_string=" adv_account_balance=(adv_account_balance+".$adv_bonus.") ";
											else
											$bonus_string=" adv_bonus_balance=(adv_bonus_balance+".$adv_bonus.") ";
							
																			
											$res=$db->execute_query("update ".TABLE_PREFIX."users set ".$bonus_string." where id=? and username=?",array($uid,$username));
														
														
											///////////////////////
										
											$time1_bonus=time();
											$value_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status,manual_entry) values(?,?,?,?,?,?)",array($uid,4,$adv_bonus,$time1_bonus,1,1));
											$id_bonus=$value_bonus->last_id;
														
											$value1_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_bonus_details (paymentid,comment) values(?,?)",array($id_bonus,"Advertiser SignUp Bonus"));
										
											///////////////////////
										}
									}
																				
									$adv_mail_replace=$this->get_user_status($adv_status);
								}
										
										
								$res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=3");
								$result1=$res1->fetch_assoc();
							
								
								if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
								$message=$result1[$localeid.'_message'];
								else
								$message=$result1['message'];
									
									
								if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
								$subject=$result1[$localeid.'_subject'];
								else
								$subject=$result1['subject'];
			
						
										
								$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
							
								$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
								$message=str_replace("{USERNAME}",$username,$message);
								$message=str_replace("{ADVSTATUS}",$adv_mail_replace,$message);
								$message=str_replace("{PUBSTATUS}",$pub_mail_replace,$message);
							
								UtilityHelper::send_mail($email,$subject,$message);

								$this->set_variable('register_success',1);
								
								$external_theme_exists=Configuration::get_instance()->read('exsternal_theme_exists');
								
								if($external_theme_exists ==1)
								header("Location: ".$this->make_url("user/external_theme_home"));

								//$this->flash($this->get_message('registration success no confirm'),BASE);
								//exit;
							}
						}						
					}
					else
					{
						$success_flag = 0;
						$response_message = $this->get_message('error occurred');	
					}
				}
			}
			
			
			if($lavender_theme == 0)
			$this->set_notice($response_message);	
		}

		
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
		
		
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
		
		
		
		$this->set_variable('localeid',$localeid);
		$this->set_variable('direction',$direction);		
		
		
		$this->set_variable('success_flag',$success_flag);
		$this->set_variable('response_message',$response_message);		
		
	}
	
	

	
	function forgot_password_action()
	{
		if(LoginHelper::validate_user_login())		
		header("Location: ".BASE);		
		
		
		//$this->set_title($this->get_label('forgot password'));
		$db= DAL::get_instance();
		
		
		$success_flag = 1;
		$response_message = "";
		
		
		$lavender_theme = 0;
		
		$active_theme	=	Configuration::get_instance()->read('active_theme');
		
		if($active_theme == 'wild-lavender')
		$lavender_theme = 1;		
		
	
		
		if(isset($_POST['password_submit']) || isset($_POST['submit']))
		{
			$username		= $this->read_post_param('username');
					
			$this->set_variable("username",$username);
			
			
			if($lavender_theme > 0)
			$this->disable_notice_area();
			
			
			if($username == "")
			{
				$success_flag = 0;
				$response_message = $this->get_message('mandatory');
			}
			else
			{
				$res=$db->execute_query("select * from ".TABLE_PREFIX."users where username=?",array($username));
				if($res->get_num_records() >0)
				{
					$result=$res->fetch_assoc();
					$uid=$result['id'];
					$email=$result['email'];
					$password=$result['password'];
					$localeid=$result['locale'];
							
							
					$newpass=substr($password,0,8);
					$newpassword=md5($newpass);
					
	                $expiry=strtotime('+1 hour', time());
	                $linkurl=$this->make_url('index/recover_password/'.$uid."_".md5($username.$email).'/'.$expiry);
					
	                
	                if(DEMO_MODE && $uid ==1)
					{
						$this->flash($this->get_message('demo mode'), $this->make_url('index/forgot_password'),0);
						exit;
					}
					
					
					$language_enabled=Configuration::get_instance()->read('language_enabled');
	                        
                    $res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=26");
                    $result1=$res1->fetch_assoc();
                        

	                if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
	                $message=$result1[$localeid.'_message'];
	                else
	                $message=$result1['message'];
	                                
	                                
	                if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
	                $subject=$result1[$localeid.'_subject'];
	                else
	                $subject=$result1['subject'];
	                                        
	                                        
	                                        
	                                        
                    $subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
                                        
                    $message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
                    $message=str_replace("{USERNAME}",$username,$message);
                    $message=str_replace("{LINKURL}",$linkurl,$message);
                                        
                    UtilityHelper::send_mail($email,$subject,$message);
                                        
                    
                    $this->flash($this->get_message('password confirmation'),$this->make_base_url('index/forgot_password_success'));
                    exit;
	                                        
	           }
	           else
	           {
					$success_flag = 0;
					$response_message = $this->get_message('invalid username');	                    	
	           }
	        }
	        
			if($lavender_theme == 0)
			$this->set_notice($response_message);		        
	    }
	        
	    

		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
			
			
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
			
			
			
		$this->set_variable('localeid',$localeid);
		$this->set_variable('direction',$direction);	        
	        
		$this->set_variable('success_flag',$success_flag);
		$this->set_variable('response_message',$response_message);	
	}
	
	
	
	
	
	function recover_password_action()
	{
	    $this->set_title($this->get_label('reset password'));
	    $data=$this->read_page_param(1);
	    $expiry=$this->read_page_param(2);
	    $this->set_variable('data', $data);
	    $this->set_variable('expiry', $expiry);
	    
	    $data_arr=explode('_', $data);
	    $uid=$data_arr['0'];
	    $time=time();
	    $db=DAL::get_instance();
	    if($expiry >=$time )
	    {
	        $f=0;
	     
	            $userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));
	            if($userdata->get_num_records() > 0)
	            {
	                $udata=$userdata->fetch_assoc();
	                $username=$udata['username'];
	                $email=$udata['email'];
	                $localeid=$udata['locale'];
	                $last_update_time=$udata['last_update_time'];
	                
	                $diff_time=$expiry-$last_update_time;
	                
	                if($diff_time < 3600)
	                {
	                    header("Location: ".BASE);
	                    die;
	                }
	                    
	                    
	                if(md5($username.$email) ==$data_arr[1] )
	                {
	                    $f=1;
	                    $pass=$udata['password'];
	                    
	                    
	                    
	                    
	                    $newpass=substr($pass,0,8);
	                    $newpassword=md5($newpass);
	                    
	                    
	                    if(DEMO_MODE && $uid ==1)
	                    {
	                        $this->flash($this->get_message('demo mode'), $this->make_url('index/forgot_password'),0);
	                        exit;
	                    }
	                    
	                    
	                    $language_enabled=Configuration::get_instance()->read('language_enabled');
	                    
	                    
	                    $up1=$db->execute_query("update ".TABLE_PREFIX."users set password=?,last_update_time=? where id=? AND username=?",array($newpassword,time(),$uid,$username));
					if($up1->error=="")
					{
							$res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=1");
							$result1=$res1->fetch_assoc();

							
							
							
							if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
							$message=$result1[$localeid.'_message'];
							else
							$message=$result1['message'];
								
								
							if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
							$subject=$result1[$localeid.'_subject'];
							else
							$subject=$result1['subject'];
				
							
							
							
							$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
							
							$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
							$message=str_replace("{USERNAME}",$username,$message);
							$message=str_replace("{PASSWORD}",$newpass,$message);
							
							UtilityHelper::send_mail($email,$subject,$message);
										
							
							$this->set_variable('message',1);
							
							$this->flash($this->get_message('password recovery success'),$this->make_url("index/forgot_password"));
							exit;
					}
					else
					{

		            	$this->set_variable('message',2);
		            
			    		$this->flash($this->get_message('error occurred'),$this->make_url("index/forgot_password"),0);
		            	exit;

						
					}
	            }
	            else
	           	{
	            	$this->set_variable('message',2);
	            
		    		$this->flash($this->get_message('invalid operation'),$this->make_url("index/forgot_password"),0);
	            	exit;
	        	}
	            
	        }
	        if($f == 0)
	        {
	            $this->set_variable('message',2);
	            
		    $this->flash($this->get_message('invalid operation'),$this->make_url("index/forgot_password"),0);
	            exit;
	        }
	        
	       
	    }
	    else
	    {
	        
		$this->set_variable('message',3);		
		
		
	        $this->flash($this->get_message('password reset link expired'),$this->make_url("index/forgot_password"),0);
  
	        exit;
	    }
	   
	  
	}
	
	
	function confirm_email_action()
	{
		if(LoginHelper::validate_user_login())		
		header("Location: ".BASE);			
		
		$uid=$this->read_page_param(1);
		$uname=$this->read_page_param(2);
		$mdvalue=$this->read_page_param(3);
		
		$mdvalue_new=md5($uid.$uname);
		
		
		if($mdvalue_new != $mdvalue)
		{
			$this->flash($this->get_message('invalid operation'),BASE);
			exit;
		}
		else 
		{
			$language_enabled=Configuration::get_instance()->read('language_enabled');
		
			$db= DAL::get_instance();
		
			$res=$db->execute_query("select * from ".TABLE_PREFIX."users where id=? and username=?",array($uid,$uname));
			$result=$res->fetch_assoc();
			
			$email=$result['email'];
			$adv_status=$result['adv_status'];
			$pub_status=$result['pub_status'];
			$localeid=$result['locale'];
			
			$pub_mail_replace=$this->get_label('no account');
			$adv_mail_replace=$this->get_label('no account');
				
				
			if($pub_status==-3 || $adv_status==-3)
			{
				if($pub_status==-3)
				{
					$pub_status_new=Configuration::get_instance()->read('pub_default_status');
					
					$res=$db->execute_query("update ".TABLE_PREFIX."users set pub_status=? where id=? and username=?",array($pub_status_new,$uid,$uname));
					
					$pub_mail_replace=$this->get_user_status($pub_status_new);
				}
				if($adv_status==-3)
				{
					$adv_status_new=Configuration::get_instance()->read('adv_default_status');
					
					if($adv_status_new==1)
					{
						$adv_bonus=Configuration::get_instance()->read('advertiser_bonus');
						if($adv_bonus < 0 || $adv_bonus=="")
						$adv_bonus=0;
						
						$bonus_seperately_track=Configuration::get_instance()->read('bonus_seperately_track');
					
						if($adv_bonus >0 && $adv_status_new==1)
						{
						
							if($bonus_seperately_track ==0)
							$bonus_string=" ,adv_account_balance=(adv_account_balance+".$adv_bonus.") ";
							else
							$bonus_string=" ,adv_bonus_balance=(adv_bonus_balance+".$adv_bonus.") ";
							
						}
						else
						$bonus_string="";
					}
					
					
					$res=$db->execute_query("update ".TABLE_PREFIX."users set adv_status=? ".$bonus_string." where id=? and username=?",array($adv_status_new,$uid,$uname));
					
					///////////////////////
					
					if($adv_bonus >0 && $adv_status_new==1)
					{
						$time1_bonus=time();
						$value_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status,manual_entry) values(?,?,?,?,?,?)",array($uid,4,$adv_bonus,$time1_bonus,1,1));
						$id_bonus=$value_bonus->last_id;
					
						$value1_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_bonus_details (paymentid,comment) values(?,?)",array($id_bonus,"Advertiser SignUp Bonus"));
					}
					
					///////////////////////
					$adv_mail_replace=$this->get_user_status($adv_status_new);
				}
	
				// newsletter addon support///
				
				$db= DAL::get_instance();
				
				
				$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
				$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');
				
				if($newsletter_enable ==1){
				
					$existing_email= $db->execute_query("select email,adv_status,pub_status  from ".TABLE_PREFIX."users where id = ?",array($uid));
					//print_r($existing_email);die;
					$row=$existing_email->fetch_assoc();//check row
				
					if($existing_email !="")
					{
				
				
				
						$this->email_mapping_update($row['email'],$row['pub_status'],$row['adv_status']);
				
				
					}
				}
				
				
				$res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=3");
				$result1=$res1->fetch_assoc();
				
				if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
				$message=$result1[$localeid.'_message'];
				else
				$message=$result1['message'];
					
					
				if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
				$subject=$result1[$localeid.'_subject'];
				else
				$subject=$result1['subject'];
				
				
		
				$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
				
				$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
				$message=str_replace("{USERNAME}",$username,$message);
				$message=str_replace("{ADVSTATUS}",$adv_mail_replace,$message);
				$message=str_replace("{PUBSTATUS}",$pub_mail_replace,$message);
				
			
				UtilityHelper::send_mail($email,$subject,$message);
				

				$this->set_variable('register_success',1);
				
				$external_theme_exists=Configuration::get_instance()->read('exsternal_theme_exists');
				
				if($external_theme_exists ==1)
				header("Location: ".$this->make_url("user/external_theme_home"));				

			}
			else 
			{
				$this->flash($this->get_message('invalid operation'),BASE,0);
				exit;
			}
		}
	}
	
	

	function login_action()
	{ 
		$external_theme_exists=Configuration::get_instance()->read('exsternal_theme_exists');
	
		$db= DAL::get_instance();
	
		$username="";
		$logedin=0;
	
		if(LoginHelper::validate_user_login())
		$logedin=1;
		
		$this->set_variable('logedin',$logedin);
		
		
		$login_type   = 0;
		$success_flag = 1;
		$response_message = "";
		
		$lavender_theme = 0;
		
		$active_theme	=	Configuration::get_instance()->read('active_theme');
		
		if($active_theme == 'wild-lavender')
		$lavender_theme = 1;		
		
		
		if(isset($_POST['submit_login']) && $logedin ==0)
		{	
			$username=$this->read_post_param('username1');
			$password=$this->read_post_param('password1');
			
			if($lavender_theme > 0)
			$this->disable_notice_area();
			
							
			if($username =="" || $password =="")
			{
				$success_flag = 0;
				$response_message = $this->get_message('mandatory');
			}
			else
			{
					$password1=md5($password);
						
					$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=? and (adv_status=1 or pub_status=1)",array($username,$password1));
					if($count >0)
					{  
						$res1=$db->execute_query("select * from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
						$value1=$res1->fetch_assoc();
						$userid=$value1['id'];
						$orig_password=$value1['password'];
						$adv_status=$value1['adv_status'];
						$pub_status=$value1['pub_status'];
						$localeid=$value1['locale'];
							
		
						if($localeid >0)
						{
							$localename=$db->read_single_column("select name from ".TABLE_PREFIX."locale where id=?",array($localeid));
								
							if($localename !='')
							$this->set_locale($localename);
						}
		
							
						setcookie(COOKIE_USERNAME,$username,0,$this->get_base_path(),$this->get_base_domain());
						setcookie(COOKIE_PASSWORD,$password1,0,$this->get_base_path(),$this->get_base_domain());
						setcookie(COOKIE_LOGINID,$userid,0,$this->get_base_path(),$this->get_base_domain());
							
							
							
						if($adv_status==1 && $pub_status!=1)
						{
							$login_type = 1;
							setcookie(COOKIE_ADMARKETTYPE,1,0,$this->get_base_path(),$this->get_base_domain());
						}
						else if($adv_status!=1 && $pub_status==1)
						{
							$login_type = 2;
							setcookie(COOKIE_ADMARKETTYPE,2,0,$this->get_base_path(),$this->get_base_domain());
						}
						else if($adv_status==1 && $pub_status==1)
						{
							$login_type = 3;
							setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
						}
										
				
						if($pub_status ==1)
						{
								$day_begin1 =date("Y",time());
								$day_begin1.=date("m",time());
								$day_begin1.=date("d",time());
								$day_begin1.=date("H",time());
									
									
									
								$ip=UtilityHelper::get_user_ip();
								$result1=$db->execute_query("update ".TABLE_PREFIX."users set pub_logintime=?,pub_loginip=? where id=?",array($day_begin1,$ip,$userid));
						}
						
	
						if($external_theme_exists==1)
						{
							$this->set_variable('log_success',1);
							$this->set_variable('login_type',$login_type);
						}
						else 
						{
							if(($adv_status ==1 && $pub_status !=1) || ($adv_status ==1 && $pub_status ==1))
							header("Location: ".$this->make_url("user/advertiser_home"));
							else if($adv_status !=1 && $pub_status ==1)
							header("Location: ".$this->make_url("user/publisher_home"));
						}
											
					}
					else
					{
						$count_second=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where username=? and password=?",array($username,$password1));
								
						if($count_second==0)
						{
							$success_flag = 0;
							$response_message = $this->get_message('invalid username or password');
						}
						else
						{
							$success_flag = 0;
							$response_message = $this->get_message('your account is inactive');
						}
					}
			}
			

			
			if($lavender_theme == 0)
			$this->set_notice($response_message);		
			
			$this->set_variable("username1",$username);
		}
		else if($logedin ==1)
		{
		    header("Location: ".$this->make_url("user/account"));
		}
		else
		{
			if(DEMO_MODE)
			{
				$this->set_variable('username1','demo');
				$this->set_variable('password1','demo');
			}
		}
		
		
		
		
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
		
		
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
		
		
		$this->set_variable('localeid',$localeid);
		$this->set_variable('direction',$direction);		
		
		$this->set_variable('success_flag',$success_flag);
		$this->set_variable('response_message',$response_message);
	}

	function notifications_action()
	{
		$this->set_title($this->get_label('notifications'));
		$db= DAL::get_instance();
		
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
		
		
		if(Configuration::get_instance()->read('language_enabled') ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;
			
			
		$this->set_variable('localeid',$localeid);
		
		
		
		if(!(LoginHelper::validate_user_login()))
		$notification=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."notifications WHERE type=3 AND status=1 AND time >=? ORDER BY id DESC",array(time()));
		else
		{
			$uid=$this->read_cookie_param(COOKIE_LOGINID);
			$usedetails=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));
			$usedetailsrow=$usedetails->fetch_assoc();
			
			$advstatus=$usedetailsrow['adv_status'];
			$pubstatus=$usedetailsrow['pub_status'];
			
			$typestring="";
			if($advstatus ==1 && $pubstatus ==1)
			$typestring=" AND (type =0 OR type =1 OR type =2) ";
			else if($advstatus ==1)
			$typestring=" AND (type =0 OR type =2) ";
			else if($pubstatus ==1)
			$typestring=" AND (type =1 OR type =2) ";
			
			
			if($advstatus ==1 || $pubstatus ==1)
			$notification=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."notifications WHERE status=1 AND time >=?".$typestring."  ORDER BY id DESC ",array(time()));
			else
			$notification=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."notifications WHERE type=3 AND status=1 AND time >=? ORDER BY id DESC",array(time()));
		}
			
		
		if($notification->get_num_records() ==0)
		header("Location: ".BASE);

		$this->set_result('notification',$notification);
		
	}
 function forgot_password_success_action()
 {
 }


};
?>
