<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";

class IndexController extends ApplicationController
{
	function before_execute()
	{
			parent::before_execute();
	}


	function index_action()
	{
			//$this->set_title($this->get_label('advertiser publisher network'));

			$referral=intval($this->read_get_param('rid'));

			if($referral == 0)
		  	$referral=intval($this->read_page_param(2));		//For old referral clients

			$db = DAL::get_instance();

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


			$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
			$external_theme_url   = Configuration::get_instance()->read('external_theme_url');


			if(DEMO_MODE)
			{
					$active_ext_theme = $this->read_cookie_param('active_ext_theme');

					if($active_ext_theme != "")
					$external_theme_url	= 	$this->get_demo_external_url($external_theme_url);
			}


			if($external_theme_exists == 1 && $external_theme_url != "")
			header("Location: ".$external_theme_url);


			$active_theme=Configuration::get_instance()->read('active_theme');

			if($active_theme == "")
			$active_theme="cherry-red";

			if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
			else
			$localname=DEFAULT_LOCALE;

			if(Configuration::get_instance()->read('language_enabled') ==1)
			$localeid=intval($this->get_locale_id($localname));
			else
			$localeid=0;

			$this->set_variable('localeid',$localeid);

			$cpc_enabled    = $this->get_addon_status('cpc_enabled');
			$cpm_enabled    = $this->get_addon_status('cpm_enabled');
			$cpa_enabled    = $this->get_addon_status('cpa_enabled');
			$cpd_enabled    = $this->get_addon_status('sponsored_enabled');
			$cpp_enabled		=$this->get_addon_status("cpp_enabled");
			$pop_enabled		=$this->get_addon_status("pop-ads_enabled");
			$affiliate_enabled		=$this->get_addon_status("affiliate-ads_enabled");

			$this->set_variable('cpc_enabled',$cpc_enabled);
			$this->set_variable('cpm_enabled',$cpm_enabled);
			$this->set_variable('cpa_enabled',$cpa_enabled);
			$this->set_variable('sponsored_enabled',$cpd_enabled);
			$this->set_variable('cpp_enabled',$cpp_enabled);
			$this->set_variable('pop_ads_enabled',$pop_enabled);
			$this->set_variable('affiliate_ads_enabled',$affiliate_enabled);

			$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."payment_gateway  WHERE status = 1 AND id <> 4 AND id <> 5");
			$this->set_result("row",$row);

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


		$testimonial=$db->execute_query("select t.*,u.username,u.adv_status, u.pub_status, u.domain,u.id as userid,u.profile_picture from ".TABLE_PREFIX."testimonial t INNER JOIN ".TABLE_PREFIX."users u ON u.id=t.uid WHERE (u.adv_status=1 OR u.pub_status=1) order by t.time desc LIMIT 0,20");
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


		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;


		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);


		$this->set_variable('direction',$direction);
	}

	function terms_action()
	{
		$db   = DAL::get_instance();

		$from = intval($this->read_page_param(1));

		$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
		$external_theme_url   = Configuration::get_instance()->read('external_theme_url');



		if($from == 2 && $external_theme_exists == 1 && $external_theme_url != "")
		$fromExternalTheme = 1;
		else
		$fromExternalTheme = 0;

		$this->set_variable("fromExternalTheme",$fromExternalTheme);




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
		$db = DAL::get_instance();




		$fromExternalTheme = intval($this->read_page_param(1));

		$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
		$external_theme_url   = Configuration::get_instance()->read('external_theme_url');


		if($fromExternalTheme == 1 && $external_theme_exists == 1 && $external_theme_url != "")
		$fromExternalTheme = 1;
		else
		$fromExternalTheme = 0;

		$this->set_variable("fromExternalTheme",$fromExternalTheme);



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
		$db = DAL::get_instance();

		$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
		$external_theme_url   = Configuration::get_instance()->read('external_theme_url');


		if(DEMO_MODE)
		{
			$active_ext_theme = $this->read_cookie_param('active_ext_theme');

			if($active_ext_theme != "")
			$external_theme_url	= 	$this->get_demo_external_url($external_theme_url);
		}



		if($external_theme_exists == 1 && $external_theme_url != "")
		header("Location: ".$external_theme_url);


		//$this->set_title($this->get_label('advertiser'));
		$logedin=0;

		if(LoginHelper::validate_user_login())					// code to check user login
		$logedin=1;

		$this->set_variable('logedin',$logedin);


		$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."payment_gateway  WHERE status = 1 AND id <> 4 AND id <> 5");
		$this->set_result("row",$row);
	}
	function publisher_action()
	{
		$db = DAL::get_instance();

		$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
		$external_theme_url   = Configuration::get_instance()->read('external_theme_url');


		if(DEMO_MODE)
		{
			$active_ext_theme = $this->read_cookie_param('active_ext_theme');

			if($active_ext_theme != "")
			$external_theme_url	= 	$this->get_demo_external_url($external_theme_url);
		}



		if($external_theme_exists == 1 && $external_theme_url != "")
		header("Location: ".$external_theme_url);


		//$this->set_title($this->get_label('publisher'));

		$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."withdrawal_gateway WHERE status = 1 AND id <= 3");
		$this->set_result("row",$row);
	}

	function contact_us_action()
	{
		require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';

		//$this->set_title($this->get_label('contact us'));
		$db= DAL::get_instance();




		$fromExternalTheme = intval($this->read_page_param(1));
		$this->set_variable("fromExternalTheme",$fromExternalTheme);





		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;


		$language_enabled=Configuration::get_instance()->read('language_enabled');


		if($language_enabled ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;



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

			//if($lavender_theme > 0)
			//$this->disable_notice_area();


			$description=nl2br($description);

			$cap_flg=0;
			$captcha_response="";
			$enable_captcha_verification = Configuration::get_instance()->read('enable_captcha_verification');


			if($enable_captcha_verification == 1)
			{
					$recaptcha_private_key = Configuration::get_instance()->read('recaptcha_private_key');
					$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key);


					//If url fopen don't support, use below code and comment above code
					//$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key, new \ReCaptcha\RequestMethod\CurlPost());

					if(isset($_POST['g-recaptcha-response']))
					{
						$cap_flg=1;
					 	$captcha_response = $reCaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
					}
			}


			if($name=="" || $email=="" || $subject=="" || $description=="")
			$message_response = $this->get_message('mandatory');
			else if($enable_captcha_verification == 1 && (!isset($_POST['g-recaptcha-response']) || ($cap_flg==1 && !$captcha_response->isSuccess())))
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
					//$message_response   = $this->get_message('mail success');


					/*
					if($lavender_theme == 0)
					{
						$this->flash($this->get_message('mail success'),BASE);
						exit;
					}
					*/



				    $contactseo=$this->get_seo_name('index/contact_us');

				    if($contactseo !='')
				    $contacturl=BASE.$contactseo;
				    else
				    $contacturl=$this->make_url("index/contact_us");

				    $this->flash($this->get_message('mail success'),$contacturl);

						exit;
				}
				else
				$message_response = $this->get_message('error occurred');
			}

			//if($lavender_theme == 0)
			//$this->set_notice($message_response);



			if($message_response != "")
			$this->set_notice($message_response);
		}



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

	    if(Configuration::get_instance()->read('external_theme_exists') != 1)
	    {
		  if(LoginHelper::validate_user_login())
		  header("Location: ".BASE);
	    }

		require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';

		//$this->set_title($this->get_label('user registration'));
		$db= DAL::get_instance();


		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;


		$language_enabled=Configuration::get_instance()->read('language_enabled');


		if($language_enabled ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;


		$resultCountry = $db->execute_query("select code,name from ".TABLE_PREFIX."countries where code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name ASC");
		$this->set_result("resultCountry",$resultCountry);


		$client_ip=UtilityHelper::get_user_ip();
		$countrycurrent=UtilityHelper::get_country_from_ip($client_ip);
		$this->set_variable("countrycurrent",$countrycurrent);


		$recaptcha_public_key=Configuration::get_instance()->read('recaptcha_public_key');
		$this->set_variable('recaptcha_public_key',$recaptcha_public_key);





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





			$username=$this->read_post_param('username');
			$password=$this->read_post_param('password');
			$cpassword=$this->read_post_param('cpassword');
			$fname=$this->read_post_param('fname');
			$lname=$this->read_post_param('lname');
			$email=$this->read_post_param('email');
			$phone=$this->read_post_param('phone');
			$country=$this->read_post_param('country');
			$category=$this->read_post_param('category');
			$terms=$this->read_post_param("terms");



			$clogo = "";

			if(isset($_FILES["clogo"]["name"]))
			$clogo=$_FILES["clogo"]["name"];


			$this->set_variable('username',$username);
			$this->set_variable('fname',$fname);
			$this->set_variable('lname',$lname);
			$this->set_variable('email', $email);
			$this->set_variable('phone', $phone);
			$this->set_variable('country',$country);
			$this->set_variable('category',$category);
			$this->set_variable("terms",$terms);



			$cap_flg                     = 0;
			$captcha_response            = "";
			$enable_captcha_verification = Configuration::get_instance()->read('enable_captcha_verification');

			
			if($enable_captcha_verification == 1)
			{
				$recaptcha_private_key = Configuration::get_instance()->read('recaptcha_private_key');
				$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key);


				//If url fopen don't support, use below code and comment above code
				//$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key, new \ReCaptcha\RequestMethod\CurlPost());




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


			if($username=="" || $password=="" || $cpassword=="" || $email=="" || $fname=="" || $lname=="" || $country=="" || $phone=="" || $category == 0)
			{
				$success_flag = 0;
				$response_message = $this->get_message('mandatory');
			}
			else if(!preg_match('/^[a-zA-Z][a-zA-Z0-9\._]{1,}$/', $username))
			{
				$success_flag = 0;
				$response_message = $this->get_message('invalid input for user name');
			}
			else if($enable_captcha_verification == 1 && (!isset($_POST['g-recaptcha-response']) || ($cap_flg==1 && !$captcha_response->isSuccess())))
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
			else if(!UtilityHelper::is_valid_phone($phone))
			{
				$success_flag = 0;
				$response_message = $this->get_message('invalid phone no');
			}
			else if(!UtilityHelper::is_valid_email($email))
			{
				$success_flag = 0;
				$response_message = $this->get_message('invalid email address');
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


				if($clogo !="" && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg")
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

					$insert_array=array($username,$password1,$email,$fname,$lname,$country,$phone,$adv_status,$pub_status,$time1,$time1,$localeid);

					if($clogo !="")
					$insert_array[]=$clogo;

					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."users (username,password,email,f_name,l_name,country,phone,adv_status,pub_status,regtime,last_update_time,locale".$logo_string.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?".$logo_string1.")",$insert_array);
					$uid=$res->get_last_id();


					$resendCookieExpiry = time()+(10 * 60);

					if($email_verification_enabled == 1)
					setcookie(COOKIE_CONFIRM_EMAIL,$uid,$resendCookieExpiry,$this->get_base_path(),$this->get_base_domain());

					if($clogo !="")
					mkdir(DATA_DIR.'/'.LOGO_DIR.'/'.$uid,0777);


					if($res->error =="")
					{
						if($clogo !="")
						{
							if(move_uploaded_file($_FILES["clogo"]["tmp_name"],DATA_DIR."/".LOGO_DIR."/".$uid."/".$clogo))
							{
								$height1 = 145;
								$width1  = 145;

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




						if($email_verification_enabled == 0)
						{
							$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
							$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');

							if($newsletter_enable ==1)
							{
								$username_field=$db->read_single_column("select id from ".TABLE_PREFIX."additional_field_info where field_name=?",array('Name'));

								$this->create_email_and_mapping($email,$pub_status,$adv_status,$username_field,$fname);
							}
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

							$this->set_variable('register_success',1);

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


			if($response_message !="" )
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

	function email_confirmation_resend_action()
	{
			$db  = DAL::get_instance();

			$uid = intval($this->read_cookie_param(COOKIE_CONFIRM_EMAIL));

			if($uid == 0)
			{
				echo 1;
				die;
			}


			$res=$db->execute_query("select * from ".TABLE_PREFIX."users where id = ?",array($uid));

			if($res->get_num_records() > 0)
			$row=$res->fetch_assoc();
			else
			{
				echo 1;
				die;
			}

			$email    = $row['email'];
			$username = $row['username'];

			if(isset($_COOKIE['my_locale']))
			$localname = $_COOKIE['my_locale'];
			else
			$localname = DEFAULT_LOCALE;

			$language_enabled = Configuration::get_instance()->read('language_enabled');

			if($language_enabled == 1)
			$localeid = intval($this->get_locale_id($localname));
			else
			$localeid = 0;

			if($row['adv_status'] == -3 || $row['pub_status'] == -3)
			{
				$email_verification_enabled = Configuration::get_instance()->read('email_verification_enabled');

				if($email_verification_enabled == 1)
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

					if(UtilityHelper::send_mail($email,$subject,$message))
					{
						echo 3;
						die;
					}
					else
					{
						echo 2;
						die;
					}
				}
				else
				{
					echo 1;
					die;
				}
			}
			else
			{
				echo 1;
				die;
			}
	}

	function forgot_password_action()
	{
	    if(Configuration::get_instance()->read('external_theme_exists') != 1)
	    {
	     	 if(LoginHelper::validate_user_login())
		 header("Location: ".BASE);
	    }


		require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';
		$db= DAL::get_instance();


		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;


		$language_enabled=Configuration::get_instance()->read('language_enabled');


		if($language_enabled ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;

		$success_flag = 1;
		$response_message = "";
		$recaptcha_public_key=Configuration::get_instance()->read('recaptcha_public_key');
		$this->set_variable('recaptcha_public_key',$recaptcha_public_key);


		if(isset($_POST['password_submit']) || isset($_POST['submit']))
		{
			$username		= $this->read_post_param('username');

			$this->set_variable("username",$username);

			$cap_flg                     = 0;
			$captcha_response            = "";
			$enable_captcha_verification = Configuration::get_instance()->read('enable_captcha_verification');

			
			if($enable_captcha_verification == 1)
			{
				$recaptcha_private_key = Configuration::get_instance()->read('recaptcha_private_key');
				$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key);

				if(isset($_POST['g-recaptcha-response']))
				{
					$cap_flg          = 1;
				 	$captcha_response = $reCaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
				}
			}

			if($username == "")
			{
				$success_flag = 0;
				$response_message = $this->get_message('mandatory');
			}
		
			else if($enable_captcha_verification == 1 && (!isset($_POST['g-recaptcha-response']) || ($cap_flg==1 && !$captcha_response->isSuccess())))

			{
				$success_flag = 0;
				$response_message = $this->get_message('image verification failed');
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


	                $expiry=strtotime('+1 hour', time());
	                $linkurl=$this->make_url('index/recover_password/'.$uid."_".md5($username.$email).'/'.$expiry);


	                if(DEMO_MODE && $uid <= 2)
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



                    $this->flash($this->get_message('password confirmation'),$this->make_url("index/forgot_password"));

	           }
	           else
	           {
					$success_flag = 0;
					$response_message = $this->get_message('invalid username');
	           }
	        }

					if($response_message != "")
					$this->set_notice($response_message);
	    }



		$this->set_variable('success_flag',$success_flag);
		$this->set_variable('response_message',$response_message);
	}





	function recover_password_action()
	{
	    $this->set_title($this->get_label('reset password'));
	    $data    = $this->read_page_param(1);
	    $expiry  = $this->read_page_param(2);

	    $this->set_variable('data', $data);
	    $this->set_variable('expiry', $expiry);

	    $data_arr = explode('_', $data);
	    $uid      = $data_arr[0];
	    $time     = time();
	    $db       = DAL::get_instance();

	    if($expiry >= $time )
	    {
	        $success = 0;

            $userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));
            if($userdata->get_num_records() > 0)
            {
	            $udata    			= $userdata->fetch_assoc();
                $username 			= $udata['username'];
                $email    			= $udata['email'];
                $localeid 			= $udata['locale'];
                $last_update_time 	= $udata['last_update_time'];

	            $diff_time 			= $expiry-$last_update_time;

                if($diff_time < 3600)
                {
                    header("Location: ".BASE);
                    die;
                }


	            if(md5($username.$email) == $data_arr[1])
	            {
	                $password    = $udata['password'];

	                $newpass     = substr($password,0,8);
	                $newpassword = md5($newpass);

                    if(DEMO_MODE && $uid <= 2)
                    {
                        $this->flash($this->get_message('demo mode'), $this->make_url('index/forgot_password'),0);
                        exit;
                    }


	                $language_enabled=Configuration::get_instance()->read('language_enabled');


	                $up1 = $db->execute_query("update ".TABLE_PREFIX."users set password=?,last_update_time=? where id=? AND username=?",array($newpassword,time(),$uid,$username));

					if($up1->error == "")
					{
						$res1    = $db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=1");
						$result1 = $res1->fetch_assoc();


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

						$success     = 1;
						$this->set_variable('message',1);
					}
					else
					{
						$this->flash($this->get_message('error occurred'), $this->make_url('index/forgot_password'),0);
                        exit;
					}
	            }
	        }

	        if($success == 0)
            $this->set_variable('message',2);
	    }
	    else
		$this->set_variable('message',3);
	}

	function confirm_email_action()
	{
	    if(Configuration::get_instance()->read('external_theme_exists') != 1)
	    {
			if(LoginHelper::validate_user_login())
			header("Location: ".BASE);
	    }

		$uid      		= $this->read_page_param(1);
		$uname    		= $this->read_page_param(2);
		$mdvalue  		= $this->read_page_param(3);

		$mdvalue_new 	= md5($uid.$uname);

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
			$username = $result['username'];


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


					if($adv_bonus > 0 && $adv_status_new == 1)
					{
						$time1_bonus=time();
						$value_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status,manual_entry) values(?,?,?,?,?,?)",array($uid,4,$adv_bonus,$time1_bonus,1,1));
						$id_bonus=$value_bonus->last_id;

						$value1_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_bonus_details (paymentid,comment) values(?,?)",array($id_bonus,"Advertiser SignUp Bonus"));
					}

					$adv_mail_replace = $this->get_user_status($adv_status_new);
				}

				// newsletter addon support///


				$email_verification_enabled = Configuration::get_instance()->read('email_verification_enabled');

				if($email_verification_enabled == 1)
				{
					$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
					$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');

					if($newsletter_enable ==1)
					{
						$username_field = $db->read_single_column("select id from ".TABLE_PREFIX."additional_field_info where field_name=?",array('Name'));

						$existing_email = $db->execute_query("select email,adv_status,pub_status,f_name from ".TABLE_PREFIX."users where id = ?",array($uid));

						if($existing_email->get_num_records() > 0)
						{
							$row = $existing_email->fetch_assoc();

							$this->create_email_and_mapping($row['email'],$row['pub_status'],$row['adv_status'],$username_field,$row['f_name']);
						}

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
			}
			else
			{
				$this->flash($this->get_message('invalid operation'),BASE,0);
				exit;
			}

			setcookie(COOKIE_CONFIRM_EMAIL,"",0,$this->get_base_path(),$this->get_base_domain());
		}
	}



	function login_action()
	{
		$external_theme_exists=Configuration::get_instance()->read('external_theme_exists');

		$db= DAL::get_instance();
		require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';

		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;


		$language_enabled=Configuration::get_instance()->read('language_enabled');


		if($language_enabled ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;



		$username="";
		$logedin=0;

		if(LoginHelper::validate_user_login())
		$logedin=1;

		$this->set_variable('logedin',$logedin);


		$login_type   = 0;
		$success_flag = 1;
		$response_message = "";


		$recaptcha_public_key=Configuration::get_instance()->read('recaptcha_public_key');
		$this->set_variable('recaptcha_public_key',$recaptcha_public_key);


		if(isset($_POST['submit_login']) && $logedin ==0)
		{
			$username=$this->read_post_param('username1');
			$password=$this->read_post_param('password1');

			$cap_flg                     = 0;
			$captcha_response            = "";
			$enable_captcha_verification = Configuration::get_instance()->read('enable_captcha_verification');


			if($enable_captcha_verification == 1)
			{
				$recaptcha_private_key = Configuration::get_instance()->read('recaptcha_private_key');
				$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key);

				if(isset($_POST['g-recaptcha-response']))
				{
					$cap_flg          = 1;
				 	$captcha_response = $reCaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
				}
			}

			if($username =="" || $password =="")
			{
				$success_flag = 0;
				$response_message = $this->get_message('mandatory');
			}
			else if($enable_captcha_verification == 1 && (!isset($_POST['g-recaptcha-response']) || ($cap_flg==1 && !$captcha_response->isSuccess())))
			{
				$success_flag = 0;
				$response_message = $this->get_message('image verification failed');
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


						if($external_theme_exists == 1)
						{
							$this->set_variable('log_success',1);

							if(($adv_status ==1 && $pub_status !=1) || ($adv_status ==1 && $pub_status ==1))
							$url=$this->make_url("dashboard/advertiser_home");
							else if($adv_status !=1 && $pub_status ==1)
							$url=$this->make_url("dashboard/publisher_home");

							$this->set_variable('post_url',$url);


							$this->set_variable('login_type',$login_type);
						}
						else
						{
								if(($adv_status ==1 && $pub_status !=1) || ($adv_status ==1 && $pub_status ==1))
								header("Location: ".$this->make_url("dashboard/advertiser_home"));
								else if($adv_status !=1 && $pub_status ==1)
								header("Location: ".$this->make_url("dashboard/publisher_home"));
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

			if($response_message != "")
			$this->set_notice($response_message);

			$this->set_variable("username1",$username);
		}
		else if($logedin ==1)
		{

		           $this->set_variable('log_success',1);


		           $url=$this->make_url("user/account");

		           $this->set_variable('post_url',$url);
		}
		else
		{
			if(DEMO_MODE)
			{
					$this->set_variable('username1','demo');
					$this->set_variable('password1','demo');
			}
			else
			{
					$this->set_variable('username1','');
					$this->set_variable('password1','');
			}
		}

		$this->set_variable('success_flag',$success_flag);
		$this->set_variable('response_message',$response_message);
	}

	function notifications_action()
	{
		$this->set_title($this->get_label('notifications'));
		$db= DAL::get_instance();


		$fromExternalTheme = intval($this->read_page_param(1));

		$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
		$external_theme_url   = Configuration::get_instance()->read('external_theme_url');


		if($fromExternalTheme == 1 && $external_theme_exists == 1 && $external_theme_url != "")
		$fromExternalTheme = 1;
		else
		$fromExternalTheme = 0;

		$this->set_variable("fromExternalTheme",$fromExternalTheme);



		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;


		if(Configuration::get_instance()->read('language_enabled') ==1)
		$localeid=intval($this->get_locale_id($localname));
		else
		$localeid=0;

		$logedin=0;

		if(LoginHelper::validate_user_login())
		$logedin=1;

		$this->set_variable('logedin',$logedin);


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


	function cookie_policy_action()
	{
		$this->set_title($this->get_label('cookie policy'));
		$db= DAL::get_instance();
		$this->disable_notice_area();

		$fromExternalTheme = intval($this->read_page_param(1));

		$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
		$external_theme_url   = Configuration::get_instance()->read('external_theme_url');



		if($fromExternalTheme == 1 && $external_theme_exists == 1 && $external_theme_url != "")
		$fromExternalTheme = 1;
		else
		$fromExternalTheme = 0;

		$this->set_variable("fromExternalTheme",$fromExternalTheme);


		$row=$db->execute_query("select * from ".TABLE_PREFIX."cookie_policy where id=1");
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
		$description=str_replace('{ADMIN-EMAIL}', Configuration::get_instance()->read('admin_notification_email'), $description);
		$description=str_replace('{ADMIN-PHONE}', Configuration::get_instance()->read('admin_phone_no'), $description);



        if(LoginHelper::validate_user_login())
				$contact_string = '<a href="'.$this->make_base_url("user/support").'">'.$this->get_label('support').'</a>';
        else
        {
	      	$contactseo=$this->get_seo_name('index/contact_us');

	      	if($contactseo !='')
	      	$contacturl=BASE.$contactseo;
	      	else
	        $contacturl=$this->make_base_url("index/contact_us");

	      	$contact_string = '<a href="'.$contacturl.'">'.$this->get_label('contact us').'</a>';
        }


		$description=str_replace('{CONTACT-PAGE}', $contact_string, $description);


		$this->set_variable("description",nl2br($description),0);
	}
	function privacy_policy_action()
	{

	    $this->set_title($this->get_label('privacy policy'));
	    $db = DAL::get_instance();
	    $this->disable_notice_area();


		$fromExternalTheme = intval($this->read_page_param(1));

		$external_theme_exists = Configuration::get_instance()->read('external_theme_exists');
		$external_theme_url   = Configuration::get_instance()->read('external_theme_url');



		if($fromExternalTheme == 1 && $external_theme_exists == 1 && $external_theme_url != "")
		$fromExternalTheme = 1;
		else
		$fromExternalTheme = 0;

		$this->set_variable("fromExternalTheme",$fromExternalTheme);


	    $row=$db->execute_query("select * from ".TABLE_PREFIX."privacy_policy where id=1");
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
	                        $description=str_replace('{ADMARKETURL}',BASE, $description);
	                        $description=str_replace('{ADMARKETMAIL}',Configuration::get_instance()->read('admin_notification_email'), $description);

	                        $this->set_variable("description",nl2br($description),0);

	}



    function get_site_content_action()
    {
      		$this->disable_notice_area();

       		$res1						= array();
      		$userid     				= intval($this->read_page_param(1));
      		$notificationContent        = $this->read_page_param(2);
      		$localname 					= $this->read_page_param(3);
      		$md5DataGet                 = $this->read_page_param(4);

      		$logedin 					= 0;
      		$localeid                 	= 0;

      		$notificationContentArray  	= array();

      		if($userid > 0)
	  		$logedin = 1;

	  		if($notificationContent != "")
	  		$notificationContentArray  	= explode('-',$notificationContent);


	  		$md5Data    				= md5($userid.$notificationContent.$localname);

	  		if($md5Data != $md5DataGet)
	  		die;



		  	$res1['userLogin']					= $logedin;
		  	$res1['locale'] 					= array();
          	$res1['signoutUrl']					= "";

          	$res1['advertiserUrl']				= "advertiser.php";
         	$res1['publisherUrl']				= "publisher.php";
         	$res1['supportUrl']				    = "";
		  	$res1['cpdmarketplaceUrl']			= "";
		  	$res1['affiliatemarketplaceUrl']	= "";

          	$res1['testimonial']				= array();
          	$res1['slider']						= array();
          	$res1['custompage']	    			= array();
          	$res1['dbExists']					= 0;


      	  	$res1['logo']				= BASE."images/logo.png";
          	$res1['favicon']			= BASE."favicon.ico";
		  	$res1['admarketName']		= "";
		  	$res1['admarketBASE']		= BASE;
		  	$res1['charSET']			= DEFAULT_CHARSET;


			$res1['localnameFull']		= 'en_US';
			$res1['localnameFirst']		= 'en';


			if($localname != "")
			{
				$localnameFull 	= strtolower(str_replace("_","-",$localname));
				$localnameArray = explode("-",$localnameFull);
				$localnameFirst	= "";

				if(isset($localnameArray[0]))
				$localnameFirst	= $localnameArray[0];

				$res1['localnameFull']		= $localnameFull;
				$res1['localnameFirst']		= $localnameFirst;
			}



            $res1['social_links']	 	= array("facebook_url"=>"","twitter_url"=>"","linkedin_url"=>"","youtube_url"=>"");
	        $res1['contact_details'] 	= array("admin_email"=>"","admin_phone_no"=>"","default_time_zone"=>"GMT");
	        $res1['notification']	 	= array("count"=>0,"count1"=>0,"notificationID"=>"");
		    $res1['powered_by_data'] 	= array("link"=>"","label"=>"");
            $res1['newsletterSupport']  = array("status"=>0,"iframeUrl"=>"");







        	if(file_exists(CONFIG_DIR_PATH.'system.php'))
        	{
          	  	$db					  = DAL::get_instance();

	  		  	$admarket_name    	  = Configuration::get_instance()->read('admarket_name');
				$public_page_logo 	  = Configuration::get_instance()->read('public_page_logo');


          		if($public_page_logo != "")
          		$res1['logo']		  = BASE.DATA_DIR."/logo/".$public_page_logo;


          		$res1['admarketName'] = $admarket_name;


				$res1['addons_enabled']['cpc_enabled'] 				=  $this->get_addon_status('cpc_enabled');
				$res1['addons_enabled']['cpm_enabled']    			= $this->get_addon_status('cpm_enabled');
				$res1['addons_enabled']['cpa_enabled']    			= $this->get_addon_status('cpa_enabled');
				$res1['addons_enabled']['cpd_enabled']    			= $this->get_addon_status('sponsored_enabled');
				$res1['addons_enabled']['cpv_enabled']       		= $this->get_addon_status('video-ads_enabled');


				$res1['addons_enabled']['pop_ads_enabled']         	= $this->get_addon_status('pop-ads_enabled');
				$res1['addons_enabled']['skin_ads_enabled']        	= $this->get_addon_status('skin-ads_enabled');
				$res1['addons_enabled']['native_ads_enabled']      	= $this->get_addon_status('native-ad-display_enabled');
				$res1['addons_enabled']['ecommerce_ads_enabled']    = $this->get_addon_status('ecommerce-ads_enabled');
				$res1['addons_enabled']['textimage_ads_enabled']    = $this->get_addon_status('text-image-ads_enabled');
				$res1['addons_enabled']['interstitial_ads_enabled'] = $this->get_addon_status('interstitial_enabled');


				$res1['addons_enabled']['os_targeting_enabled']          = $this->get_addon_status('os_enabled');
				$res1['addons_enabled']['browser_targeting_enabled']     = $this->get_addon_status('browser_enabled');
				$res1['addons_enabled']['time_targeting_enabled']        = $this->get_addon_status('time-targeting_enabled');
				$res1['addons_enabled']['city_targeting_enabled']        = $this->get_addon_status('city-targeting_enabled');
				$res1['addons_enabled']['category_targeting_enabled']    = $this->get_addon_status('category-targeting_enabled');
				$res1['addons_enabled']['language_targeting_enabled']    = $this->get_addon_status('language-targeting_enabled');
				$res1['addons_enabled']['retargeting_targeting_enabled'] = $this->get_addon_status('retargeting_enabled');



          	  	$language_enabled 	= Configuration::get_instance()->read('language_enabled');

          	  	if($language_enabled == 1)
          	  	{
	          		$result		= $db->execute_query("select * from ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");

	          		while($res 	= $result->fetch_assoc())
	          		{
	              		$res1['locale'][] = $res;
	          		}
	          	}


	          	$localeID = intval($db->read_single_column("select id from ".TABLE_PREFIX."locale where name = ?",array($localname)));


				//Advertiser
				$advertiserData = $db->execute_query("select * from ".TABLE_PREFIX."meta where pageid = 1");
				$advertiserRow  = $advertiserData->fetch_assoc();

				$advertiserMKeyword  	 = "";
				$advertiserMDescription  = "";
				$advertiserMTitle        = "";


				if($localeID > 0 && isset($advertiserRow[$localeID.'_keyword']))
				$advertiserMKeyword  = $advertiserRow[$localeID.'_keyword'];

				if($advertiserMKeyword == "")
				$advertiserMKeyword  = $advertiserRow['keyword'];


				if($localeID > 0 && isset($advertiserRow[$localeID.'_description']))
				$advertiserMDescription  = $advertiserRow[$localeID.'_description'];

				if($advertiserMDescription == "")
				$advertiserMDescription  = $advertiserRow['description'];


				if($localeID > 0 && isset($advertiserRow[$localeID.'_title']))
				$advertiserMTitle  = $advertiserRow[$localeID.'_title'];

				if($advertiserMTitle == "")
				$advertiserMTitle  = $advertiserRow['title'];


				//Publisher
				$publisherData = $db->execute_query("select * from ".TABLE_PREFIX."meta where pageid = 2");
				$publisherRow  = $publisherData->fetch_assoc();

				$publisherMKeyword  	= "";
				$publisherMDescription  = "";
				$publisherMTitle        = "";


				if($localeID > 0 && isset($publisherRow[$localeID.'_keyword']))
				$publisherMKeyword  = $publisherRow[$localeID.'_keyword'];

				if($publisherMKeyword == "")
				$publisherMKeyword  = $publisherRow['keyword'];


				if($localeID > 0 && isset($publisherRow[$localeID.'_description']))
				$publisherMDescription  = $publisherRow[$localeID.'_description'];

				if($publisherMDescription == "")
				$publisherMDescription  = $publisherRow['description'];


				if($localeID > 0 && isset($publisherRow[$localeID.'_title']))
				$publisherMTitle  = $publisherRow[$localeID.'_title'];

				if($publisherMTitle == "")
				$publisherMTitle  = $publisherRow['title'];


				//Home
				$homeData = $db->execute_query("select * from ".TABLE_PREFIX."meta where pageid = 5");
				$homeRow  = $homeData->fetch_assoc();

				$homeMKeyword  		= "";
				$homeMDescription  	= "";
				$homeMTitle        	= "";


				if($localeID > 0 && isset($homeRow[$localeID.'_keyword']))
				$homeMKeyword  = $homeRow[$localeID.'_keyword'];

				if($homeMKeyword == "")
				$homeMKeyword  = $homeRow['keyword'];


				if($localeID > 0 && isset($homeRow[$localeID.'_description']))
				$homeMDescription  = $homeRow[$localeID.'_description'];

				if($homeMDescription == "")
				$homeMDescription  = $homeRow['description'];


				if($localeID > 0 && isset($homeRow[$localeID.'_title']))
				$homeMTitle  = $homeRow[$localeID.'_title'];

				if($homeMTitle == "")
				$homeMTitle  = $homeRow['title'];



				$res1['advertiser']['meta_keywords']    = $advertiserMKeyword;
				$res1['advertiser']['meta_description'] = $advertiserMDescription;
				$res1['advertiser']['meta_title']       = $advertiserMTitle;

				$res1['publisher']['meta_keywords']     = $publisherMKeyword;
				$res1['publisher']['meta_description']  = $publisherMDescription;
				$res1['publisher']['meta_title']        = $publisherMTitle;

				$res1['index']['meta_keywords']     	= $homeMKeyword;
				$res1['index']['meta_description']  	= $homeMDescription;
				$res1['index']['meta_title']        	= $homeMTitle;



	          	$testimonial = $db->execute_query("select t.*,u.username,u.domain,u.id as userid from ".TABLE_PREFIX."testimonial t INNER JOIN ".TABLE_PREFIX."users u ON u.id=t.uid WHERE (u.adv_status=1 OR u.pub_status=1) order by t.time desc LIMIT 0,20");


	          	while($value = $testimonial->fetch_assoc())
			  	{
					$res1['testimonial'][]	 = array("user"=>$value['username'],"description"=>$value['description']);
			  	}




				$sliderRow = $db->execute_query("SELECT id,logo,domain FROM ".TABLE_PREFIX."users WHERE logo_display = 1 AND (adv_status=1 OR pub_status=1) AND logo <> ? ",array(''));

				while($sliderData = $sliderRow->fetch_assoc())
				{
					$domainName = $sliderData['domain'];

					if($domainName !="")
					{
						if(substr($domainName,0,7) !="http://")
						{
							if(substr($domainName,0,8) !="https://")
							$domainName = "http://".$domainName;
						}
					}

					$res1['slider'][]	 = array("id"=>$sliderData['id'],"logo"=>BASE.DATA_DIR."/".LOGO_DIR."/".$sliderData['id']."/".$sliderData['logo'],"domain"=>$domainName);
				}






		        $res1['social_links']	 	 = array("facebook_url"=>Configuration::get_instance()->read('facebook_url'),"twitter_url"=>Configuration::get_instance()->read('twitter_url'),"linkedin_url"=>Configuration::get_instance()->read('linkedin_url'),"youtube_url"=>Configuration::get_instance()->read('youtube_url'));
		        $res1['contact_details'] 	 = array("admin_email"=>Configuration::get_instance()->read('admin_notification_email'),"admin_phone_no"=>Configuration::get_instance()->read('admin_phone_no'),"default_time_zone"=>Configuration::get_instance()->read('default_time_zone'));

				$res1['powered_by_data'] 	 = array("link"=>Configuration::get_instance()->read('powered_by_link'),"label"=>Configuration::get_instance()->read('powered_by_label'));


	          	$res1['dbExists']			 = 1;



 				$newsletter_addon_folder     = $this->xyz_get_addon_folder_name("XYZADMNLR");

				if(Configuration::get_instance()->read($newsletter_addon_folder.'_enabled') == 1 && Configuration::get_instance()->read('enable_optinform_for_site_footer') == 1)
          	  	{
					$active_theme = Configuration::get_instance()->read('active_theme');

					$iframeUrl    = BASE.ADDON_DIR.'/'.$newsletter_addon_folder.'/admin/index.php?page=list/optin_'.$active_theme.'/1';

          	  		$res1['newsletterSupport']= array("status"=>1,"iframeUrl"=>$iframeUrl);
          	  	}



			  	$notificationUnReaded = 0;
			  	$notificationCount    = 0;
			  	$valuestring          = "";
			  	$typestring           = "";


          	  	if($logedin == 1)
          	  	{
		          	$res1['signoutUrl']			= $this->make_url("user/logout");

          	  		$userRow    = $db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($userid));

          	  		$userData   = $userRow->fetch_assoc();


					$advstatus  = $userData['adv_status'];
					$pubstatus  = $userData['pub_status'];

					if($advstatus == 1)
          			$res1['advertiserUrl']	= $this->make_base_url("dashboard/advertiser_home");

          			if($pubstatus == 1)
          			$res1['publisherUrl']	= $this->make_base_url("dashboard/publisher_home");

          			$res1['supportUrl']	    = $this->make_base_url("user/support");


					if($advstatus ==1 && $pubstatus ==1)
					$typestring=" AND (type =0 OR type =1 OR type =2) ";
					else if($advstatus ==1)
					$typestring=" AND (type =0 OR type =2) ";
					else if($pubstatus ==1)
					$typestring=" AND (type =1 OR type =2) ";

					if($advstatus ==1 || $pubstatus ==1)
					{
						$notification=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."notifications WHERE status=1 AND time >=?".$typestring." ",array(time()));
						while($notificationdata=$notification->fetch_assoc())
						{
							$indexvalue=array_search($notificationdata['id'],$notificationContentArray);

							if(!($indexvalue > -1))
							{
								$notificationUnReaded = $notificationUnReaded+1;

								if($valuestring !="")
								$valuestring.='-';

								$valuestring.=$notificationdata['id'];
							}

							$notificationCount = $notificationCount+1;
						}
					}
	          	}
	          	else
	          	{
					$notification=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."notifications WHERE type=3 AND status=1 AND time >=?",array(time()));
					while($notificationdata=$notification->fetch_assoc())
					{
						$indexvalue=array_search($notificationdata['id'],$notificationContentArray);

						if(!($indexvalue > -1))
						{
							$notificationUnReaded = $notificationUnReaded+1;

							if($valuestring !="")
							$valuestring.='-';

							$valuestring.=$notificationdata['id'];
						}

						$notificationCount = $notificationCount+1;
					}
				}

				$res1['notification']	 	= array("unReaded"=>$notificationUnReaded,"count"=>$notificationCount,"notificationID"=>$valuestring);


          	  	$customres = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."custom_pages WHERE status=1 ORDER BY priority ASC");

				$customrescount = $customres->get_num_records();


				if($language_enabled == 1)
				$localeid 			= intval($this->get_locale_id($localname));

				while($value = $customres->fetch_assoc())
				{
					$seoName = $this->get_seo_name_custom($value['id']);
					$seoUrl  = BASE.$seoName;

					$customname = "";

					if($localeid > 0 && isset($value[$localeid.'_name']))
					$customname = $value[$localeid.'_name'];

					if($customname =="")
					$customname = $value['name'];

					$res1['custompage'][]	 = array("id"=>$value['id'],"url"=>$seoUrl,"name"=>$this->get_label(ucwords(strtolower($customname))));
				}
        	}

          	echo json_encode($res1);
          	die;
      }
			function faq_action()
			{
				$db = DAL::get_instance();

				$this->set_title($this->get_label('faq'));
				
			

				

				if(isset($_COOKIE['my_locale']))
				$localname=$_COOKIE['my_locale'];
				else
				$localname=DEFAULT_LOCALE;

				if(Configuration::get_instance()->read('language_enabled') ==1)
				$localeid=intval($this->get_locale_id($localname));
				else
				$localeid=0;

				$this->set_variable('localeid',$localeid);

				$adv_faq_res =$db->execute_query("SELECT * FROM ".TABLE_PREFIX."faq WHERE type=? AND status=? ORDER BY priority ASC", array(0,1));
				$this->set_result("adv_faq_res",$adv_faq_res, array('answer', $localeid.'_answer'));

				$pub_faq_res =$db->execute_query("SELECT * FROM ".TABLE_PREFIX."faq WHERE type=? AND status=? ORDER BY priority ASC", array(1,1));
				$this->set_result("pub_faq_res",$pub_faq_res, array('answer', $localeid.'_answer'));

				
			}
};
?>
