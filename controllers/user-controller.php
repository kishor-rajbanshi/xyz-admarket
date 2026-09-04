<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";

class UserController extends ApplicationController
{
	function before_execute()
	{
			parent::before_execute();
			if($this->get_action()=="delete_logo" || $this->get_action()=="account" || $this->get_action()=="change_password" || $this->get_action()=="edit_profile" || $this->get_action()=="home" || $this->get_action()=="publisher_request" || $this->get_action()=="advertiser_request" || $this->get_action()=="support"  || $this->get_action()=="advertiser_home"  || $this->get_action()=="publisher_home" || $this->get_action()=="withdrawal_configuration" || $this->get_action()=="cash_withdrawal" || $this->get_action()=="withdrawal_history" || $this->get_action()=="withdrawal_details" || $this->get_action()=="fund_transfer_request" || $this->get_action()=="withdrawal_delete" || $this->get_action()=="withdrawal_calculation" || $this->get_action()=="invoice_export" || $this->get_action()=="change_email" || $this->get_action()=="change_email_confirmation")
			{
		      if(!(LoginHelper::validate_user_login()))
					{
						$this->flash($this->get_message('login failed'), BASE,0);
					}
			}
	}



	function account_action()
	{
		$this->set_title($this->get_label('my account'));

		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$username=$this->read_cookie_param(COOKIE_USERNAME);
		$db= DAL::get_instance();


		$this->set_variable("username", $username);


		if(isset($_POST['auth_submit']))
		{
			$authKey = md5($uid.time());

			$db->execute_query("UPDATE ".TABLE_PREFIX."users SET feed_auth_key = ? WHERE id = ?",array($authKey,$uid));

			$this->flash($this->get_message('feed api key update success'), $this->make_url('user/account'));
		}



		$res=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));
		$this->set_result("res",$res);
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
		  $mngr_res=$res->fetch_assoc();
		  $advmngrid=$mngr_res['adv_managerid'];
		  $pubmngrid=$mngr_res['pub_managerid'];



			if($advmngrid >0)
			{
			    $resadv=$db->execute_query("select username,phone,skypeid,email from ".TABLE_PREFIX."admin where id=?",array($advmngrid));
			    $advdata=$resadv->fetch_assoc();
			    $this->set_variable('advmngr', $advdata['username']);
			    $this->set_variable('advmngrphone', $advdata['phone']);
			    $this->set_variable('advmngrskype', $advdata['skypeid']);
			    $this->set_variable('advmngremail', $advdata['email']);
			}
			if($pubmngrid >0)
			{
			    $resadv=$db->execute_query("select username,phone,skypeid,email from ".TABLE_PREFIX."admin where id=?",array($pubmngrid));
			    $advdata=$resadv->fetch_assoc();
			    $this->set_variable('pubmngr', $advdata['username']);
			    $this->set_variable('pubmngrphone', $advdata['phone']);
			    $this->set_variable('pubmngrskype', $advdata['skypeid']);
			    $this->set_variable('pubmngremail', $advdata['email']);

			}
		}



	



		$referral_enabled=$this->get_addon_status('referral_enabled');
		$this->set_variable('referral_enabled',$referral_enabled);
	}

	function support_action()
	{
		$this->set_title($this->get_label('support desk'));

		$db= DAL::get_instance();


		$uname=$this->read_cookie_param(COOKIE_USERNAME);
		$pass=$this->read_cookie_param(COOKIE_PASSWORD);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);



		$subject="";
		$description="";
		if($_POST)
		{
			$subject=$this->read_post_param("subject");
			$description=$this->read_post_param("description");

			$description=nl2br($description);

			$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));


			$replayto=$email;
			$replay_name=$uname;


			$to=Configuration::get_instance()->read('admin_notification_email');

			if($subject=="" || $description=="")
			{
				$this->set_notice('mandatory');
			}
			else if(UtilityHelper::send_mail($to,$subject,"Mail From : ".$uname."<br/><br/>".$description,"","",$replayto))
			{



			if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1 || $this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
			$this->flash($this->get_message('send mail success'),$this->make_url("dashboard/advertiser_home"));
			else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
			$this->flash($this->get_message('send mail success'),$this->make_url("dashboard/publisher_home"));

			exit;
			}
			else
			{
				$this->set_notice('error occurred');
			}


		}


		$this->set_variable("subject",$subject);
		$this->set_variable("description",$description);

	}


	function logout_action()
	{
		setcookie(COOKIE_USERNAME,"",0,$this->get_base_path(),$this->get_base_domain());
		setcookie(COOKIE_PASSWORD,"",0,$this->get_base_path(),$this->get_base_domain());
		setcookie(COOKIE_LOGINID,"",0,$this->get_base_path(),$this->get_base_domain());
		setcookie(COOKIE_ADMARKETTYPE,"",0,$this->get_base_path(),$this->get_base_domain());
		setcookie(COOKIE_USERPANEL,"",0,$this->get_base_path(),$this->get_base_domain());


		$external_theme_exists=Configuration::get_instance()->read('external_theme_exists');
		if($external_theme_exists==1)
		{
			$external_theme_url=Configuration::get_instance()->read('external_theme_url');


			if(DEMO_MODE)
			{
				$active_ext_theme = $this->read_cookie_param('active_ext_theme');

				if($active_ext_theme != "")
				$external_theme_url	= 	$this->get_demo_external_url($external_theme_url);
			}




			header("Location:".$external_theme_url);
		}
		else
		{
			$this->flash($this->get_message('logged out success'), BASE);
		}
		exit;
	}








	function change_password_action()
	{
		$this->set_title($this->get_label('change password'));


		$uname=$this->read_cookie_param(COOKIE_USERNAME);
		$pass=$this->read_cookie_param(COOKIE_PASSWORD);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);


		if($_POST)
		{
			if(DEMO_MODE && $uid <= 2)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('user/change_password'),0);
				exit;
			}

			$password=$this->read_post_param('password');
			$newpassword=$this->read_post_param('newpassword');
			$cnewpassword=$this->read_post_param('newpassword1');

			$password1=md5($password);
			$newpassword1=md5($newpassword);

			if($password=="" || $newpassword=="" || $cnewpassword=="")
			{
				$this->set_notice("mandatory");
			}
			elseif($newpassword!=$cnewpassword)
			{
			$this->set_notice("password mismatch");
			}
			else if(strlen($newpassword) < Configuration::get_instance()->read('password_length'))
			{
				$this->set_notice($this->get_message("password length limit",array('x'=>Configuration::get_instance()->read('password_length'))));
			}
            else if($pass !=$password1)
            {
            	$this->set_notice("old password incorrect");

            }
			else
			{
			$db= DAL::get_instance();

			$up="update ".TABLE_PREFIX."users set password=?,last_update_time=? where id=? and username=?";
			$result1=$db->execute_query($up,array($newpassword1,time(),$uid,$uname));

			if($result1->error=="")
			{

			setcookie(COOKIE_PASSWORD,$newpassword1,0,$this->get_base_path(),$this->get_base_domain());


			if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1 || $this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
			$this->flash($this->get_message('password change success'),$this->make_url("dashboard/advertiser_home"));
			else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
			$this->flash($this->get_message('password change success'),$this->make_url("dashboard/publisher_home"));

			exit;
			}
			else
			{
				$this->set_notice("error occurred");
			}

			}
		}
	}

	function edit_profile_action()
	{
		$this->set_title($this->get_label('update profile'));

		$uname=$this->read_cookie_param(COOKIE_USERNAME);
		$pass=$this->read_cookie_param(COOKIE_PASSWORD);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);


		$db = DAL::get_instance();
		$countryResult = $db->execute_query("select code,name from ".TABLE_PREFIX."countries where code!='A1' and code!='A2' and code!='AP' and code!='EU'");
		$this->set_result("countryResult",$countryResult);


		$this->set_variable("uid",$uid);

		if($_POST)
		{

			if(DEMO_MODE && $uid <= 2)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('user/edit_profile'),0);
				exit;
			}

			$fname=$this->read_post_param('fname');
			$lname=$this->read_post_param('lname');
			$address=$this->read_post_param('address');
			$email=$this->read_post_param('email');
			$phone=$this->read_post_param('phone');
			$country=$this->read_post_param('country');
			$site=$this->read_post_param('site');

			$clogo=$_FILES["clogo"]["name"];
			$profile_picture=$_FILES["profile_picture"]["name"];

			$this->set_variable('fname',$fname);
			$this->set_variable('lname',$lname);
			$this->set_variable('address',$address);
			$this->set_variable('email', $email);
			$this->set_variable('phone', $phone);
			$this->set_variable('country',$country);
			$this->set_variable('site',$site);








			if($email=="" || $fname=="" || $lname=="" || $address=="" || $phone=="" || $country=="")
			{
				$this->set_notice("mandatory");
			}
			else if(!UtilityHelper::is_valid_phone($phone))
			{
				$this->set_notice("invalid phone no");
			}
			else if(!UtilityHelper::is_valid_email($email))
			{
				$this->set_notice("invalid email address");
			}
			else if($site != "" && !UtilityHelper::is_valid_domain($site))
			{
				$this->set_notice("invalid domain");
			}
			else if($db->read_single_column("select email from ".TABLE_PREFIX."users where email=? AND id <>?", array($email,$uid)))
			{
				$this->set_notice("email exists");
			}
			else
			{

				$sql1="UPDATE ".TABLE_PREFIX."users set email=?,f_name=?,l_name=?,address=?,country=?,phone=?,domain=?,last_update_time=? where id=?" ;
				$res=$db->execute_query($sql1,array($email,$fname,$lname,$address,$country,$phone,$site,time(),$uid));
				$error_flag=0;
				if($res->error=="")
					{


						// newsletter addon support///
						$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
						$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');

						if($newsletter_enable ==1){

						$ea_Id =$db->read_single_column("select id from ".TABLE_PREFIX."email_address where email = ?",array($email));
						$query4 = "UPDATE ".TABLE_PREFIX."additional_field_value  set field1=?  where ea_id=?";
						$result4 = $db->execute_query("$query4",array($fname,$ea_Id));

							}

				 	}
				 	else
				 		$error_flag=1;

				if($clogo !="")
				{
					$extension=explode(".",$_FILES['clogo']['name']);
					$extensionname=strtolower($extension[count($extension)-1]);


					$clogo=time().'.'.$extensionname;


					if($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg")
					{
						if($_FILES["clogo"]["error"] > 0)
						{
							$error_flag=1;
							$this->set_notice("error occurred");
						}
						else
						{
							$old_name=$db->read_single_column("select logo from ".TABLE_PREFIX."users where id=?",array($uid));


							$sql1="UPDATE ".TABLE_PREFIX."users set logo=?,last_update_time=? where id=?" ;
							$res=$db->execute_query($sql1,array($clogo,time(),$uid));


							///////////////////////////////////
							if(!is_dir(DATA_DIR.'/'.LOGO_DIR))
							{
								mkdir(DATA_DIR.'/'.LOGO_DIR,0777);
							}

							mkdir(DATA_DIR.'/'.LOGO_DIR.'/'.$uid,0777);

							if($res->error=="")
							{
								if(move_uploaded_file($_FILES["clogo"]["tmp_name"],DATA_DIR."/".LOGO_DIR."/".$uid."/".$clogo))
								{
									unlink(DATA_DIR."/".LOGO_DIR."/".$uid."/".$old_name);



									$height1 = 145;
									$width1  = 145;

									$image=new ImageHelper(DATA_DIR."/".LOGO_DIR."/".$uid."/".$clogo);
									$image->resize($width1,$height1,DATA_DIR."/".LOGO_DIR."/".$uid."/".$clogo);
								}
								else
								{
									$error_flag=1;
									$this->set_notice("logo upload failed");
								}


							}
							else
							{
							$error_flag=1;
							$this->set_notice("error occurred");
							}
						}
					}
					else
					{
						$error_flag=1;
						$this->set_notice("image not supported");
					}
				}

				if($profile_picture !="")
				{
					$extension=explode(".",$_FILES['profile_picture']['name']);
					$extensionname=strtolower($extension[count($extension)-1]);


					$profile_picture=time().'.'.$extensionname;


					if($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg")
					{

						if($_FILES["profile_picture"]["error"] > 0)
						{
							$error_flag=1;
							$this->set_notice("error occurred");
						}
						else
						{
							$old_profile_pic=$db->read_single_column("select profile_picture from ".TABLE_PREFIX."users where id=?",array($uid));


							$sql1="UPDATE ".TABLE_PREFIX."users set profile_picture=?,last_update_time=? where id=?" ;
							$res=$db->execute_query($sql1,array($profile_picture,time(),$uid));


							///////////////////////////////////
							if(!is_dir(DATA_DIR.'/'.PROFILE_PICTURE_DIR))
							{
								mkdir(DATA_DIR.'/'.PROFILE_PICTURE_DIR,0777);
							}

							mkdir(DATA_DIR.'/'.PROFILE_PICTURE_DIR.'/'.$uid,0777);

							if($res->error=="")
							{
								if(move_uploaded_file($_FILES["profile_picture"]["tmp_name"],DATA_DIR."/".PROFILE_PICTURE_DIR."/".$uid."/".$profile_picture))
								{
									unlink(DATA_DIR."/".PROFILE_PICTURE_DIR."/".$uid."/".$old_profile_pic);



									$height1 = 60;
									$width1  = 60;

									$image=new ImageHelper(DATA_DIR."/".PROFILE_PICTURE_DIR."/".$uid."/".$profile_picture);
									$image->resize($width1,$height1,DATA_DIR."/".PROFILE_PICTURE_DIR."/".$uid."/".$profile_picture);
								}
								else
								{
									$error_flag=1;
									$this->set_notice("profile picture upload failed");
								}


							}
							else
							{
								$error_flag=1;
								$this->set_notice("error occurred");
							}
						}
					}
					else
					{
						$error_flag=1;
						$this->set_notice("image not supported");
					}
				}

				if($error_flag == 0){

				if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1 || $this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
					$this->flash($this->get_message('profile edit success'),$this->make_url("dashboard/advertiser_home"));
				else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
					$this->flash($this->get_message('profile edit success'),$this->make_url("dashboard/publisher_home"));

					exit;
				}

			}
		}
		else
		{

			$check1= "select email,f_name,l_name,address,country,phone,domain,logo,profile_picture from ".TABLE_PREFIX."users where id=?";
			$res=$db->execute_query($check1,array($uid));

			$this->set_result("res",$res);
		}


	}

	function check_availability_action()
	{
		$db= DAL::get_instance();
		$username=$this->read_page_param(1);

		$res=$db->execute_query("select id from ".TABLE_PREFIX."users where username=?",array($username));
		$value=$res->get_num_records();

		if($value==0)
		echo $this->get_message("user available");
		else
		echo $this->get_message("user unavailable");


		exit;
	}


	function advertiser_request_action()
	{
		$this->set_title($this->get_label('adv account request'));

		$username=$this->read_cookie_param(COOKIE_USERNAME);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);



		$db= DAL::get_instance();


		$already_status = $db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));
		if($already_status != -2)
		{
				if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1 || $this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
				$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/advertiser_home"),0);
				else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
				$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/publisher_home"),0);

				exit;
		}


		if($_POST)
		{

		$adv_status=Configuration::get_instance()->read('adv_default_status');



		$bonus_string = "";

		if($adv_status==1)
		{
			$bonus_seperately_track=Configuration::get_instance()->read('bonus_seperately_track');


			$adv_bonus=Configuration::get_instance()->read('advertiser_bonus');
			if($adv_bonus < 0 || $adv_bonus=="")
			$adv_bonus=0;


			if($adv_bonus >0)
			{
				if($bonus_seperately_track ==0)
				$bonus_string=" ,adv_account_balance=(adv_account_balance+".$adv_bonus.") ";
				else if($bonus_seperately_track ==1)
				$bonus_string=" ,adv_bonus_balance=(adv_bonus_balance+".$adv_bonus.") ";
			}
			else
			$bonus_string="";
		}


		$res=$db->execute_query("update ".TABLE_PREFIX."users set adv_status=? ".$bonus_string." where id=?",array($adv_status,$uid));


		//newsletter addon support///
		$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
		$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');

		if($newsletter_enable ==1){



			$result=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));


			$username_field=$db->read_single_column("select id from ".TABLE_PREFIX."additional_field_info where field_name=?",array('Name'));
			while ($row=$result->fetch_assoc())
			{

				$this->create_email_and_mapping($row['email'],$row['pub_status'],$row['adv_status']);



			}
		}






		if($adv_status ==1)
		{

			setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
		}

		///////////////////////

		if($adv_bonus >0 && $adv_status==1)
		{

			$time1_bonus=time();
			$query_bonus="insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status,manual_entry)
			values('?','?','?','?','?','?')";
			$value_bonus=$db->execute_query($query_bonus,array($uid,4,$adv_bonus,$time1_bonus,1,1));
			$id_bonus=$value_bonus->last_id;

			$query1_bonus="insert into ".TABLE_PREFIX."advertiser_bonus_details (paymentid,comment) values('?','?')";
			$value1_bonus=$db->execute_query($query1_bonus,array($id_bonus,"Advertiser SignUp Bonus"));

		}

		///////////////////////



		$admin_email=Configuration::get_instance()->read('admin_notification_email');

		$message ='

		Hello Admin,

	    The user '.$username.' has requested for advertiser account at '.Configuration::get_instance()->read('admarket_name').'.
		Please login your admin area to see the details.

	    Best Regards,
		'.Configuration::get_instance()->read('admarket_name');


		UtilityHelper::send_mail($admin_email,"Request For Advertiser Account Of ".$username,$message);





		if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1 || $this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
			$this->flash($this->get_message('adv account request success'),$this->make_url("dashboard/advertiser_home"));
		else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
			$this->flash($this->get_message('adv account request success'),$this->make_url("dashboard/publisher_home"));

		exit;

		}

	}

	function publisher_request_action()
	{
		$this->set_title($this->get_label('pub account request'));

		$username=$this->read_cookie_param(COOKIE_USERNAME);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$db = DAL::get_instance();

		$already_status = $db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));

		if($already_status != -2)
		{
				if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1 || $this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
				$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/advertiser_home"),0);
				else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
				$this->flash($this->get_message('invalid operation'),$this->make_url("dashboard/publisher_home"),0);

				exit;
		}


		if($_POST)
		{

		$pub_status=Configuration::get_instance()->read('pub_default_status');

		$sql="update ".TABLE_PREFIX."users set pub_status=? where id=?";
		$res=$db->execute_query($sql,array($pub_status,$uid));

		//newsletter addon support///
		$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
		$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');

		if($newsletter_enable ==1)
		{
			$result=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));

			$username_field=$db->read_single_column("select id from ".TABLE_PREFIX."additional_field_info where field_name=?",array('Name'));
			while ($row=$result->fetch_assoc())
			{
					$this->create_email_and_mapping($row['email'],$row['pub_status'],$row['adv_status']);
			}
		}

		if($pub_status ==1)
		{
			setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());
		}

		$admin_email=Configuration::get_instance()->read('admin_notification_email');

		$message ='

		Hello Admin,

		The user '.$username.' has requested for publisher account at '.Configuration::get_instance()->read('admarket_name').'.
		Please login your admin area to see the details.

		Best Regards,
		'.Configuration::get_instance()->read('admarket_name');


		UtilityHelper::send_mail($admin_email,"Request For Publisher Account Of ".$username,$message);



		if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==1 || $this->read_cookie_param(COOKIE_ADMARKETTYPE)==3)
			$this->flash($this->get_message('pub account request success'),$this->make_url("dashboard/advertiser_home"));
		else if($this->read_cookie_param(COOKIE_ADMARKETTYPE)==2)
			$this->flash($this->get_message('pub account request success'),$this->make_url("dashboard/publisher_home"));



		exit;
		}

	}



	function delete_profile_picture_action()
	{
		$db=DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$fromuid=$this->read_page_param(1);

		if($fromuid != $uid)
		{
			$this->flash($this->get_message('invalid operation'),BASE,0);
			exit;
		}

		$profile_picture = $db->read_single_column("SELECT profile_picture FROM ".TABLE_PREFIX."users where id=?",array($uid));
		$db->execute_query("UPDATE ".TABLE_PREFIX."users SET profile_picture='' where id=?",array($uid));

		unlink(DATA_DIR."/".PROFILE_PICTURE_DIR."/".$uid."/".$profile_picture);
		rmdir(DATA_DIR."/".PROFILE_PICTURE_DIR."/".$uid);

		$this->flash($this->get_message('profile image delete success'), $this->make_url('user/edit_profile'));
		exit;
	}



	function delete_logo_action()
	{
		$db=DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$fromuid=$this->read_page_param(1);

		if($fromuid != $uid)
		{
			$this->flash($this->get_message('invalid operation'),BASE,0);
			exit;
		}

		$logo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."users where id=?",array($uid));
		$db->execute_query("UPDATE ".TABLE_PREFIX."users SET logo='' where id=?",array($uid));
		unlink(DATA_DIR."/".LOGO_DIR."/".$uid."/".$logo);
		rmdir(DATA_DIR."/".LOGO_DIR."/".$uid);

		$this->flash($this->get_message('image delete success'), $this->make_url('user/edit_profile'));
		exit;
	}

	function withdrawal_configuration_action()
	{
		$this->set_title($this->get_label('configure payment details'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$this->set_variable('uid',$uid);


		$client_ip=UtilityHelper::get_user_ip();
		$countrycurrent=$this->get_user_country($uid);
		$this->set_variable("countrycurrent",$countrycurrent);

		$db = DAL::get_instance();

		$already=0;
		if($_POST)
		{
			$payment_mode=$this->read_post_param('payment_mode');
			$this->set_variable("payment_mode",$payment_mode);

		   	$config_id=$db->read_single_column("select id from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));

			if($payment_mode==1)
			{
				$payee_name=$this->read_post_param('payee_name');
				$add1=$this->read_post_param('add1');
				$add2=$this->read_post_param('add2');
				$city=$this->read_post_param('city');
				$state=$this->read_post_param('state');
				$country=$countrycurrent;


				$this->set_variable("check_payee_name",$payee_name);
				$this->set_variable("payee_address_line1",$add1);
				$this->set_variable("payee_address_line2",$add2);
				$this->set_variable("payee_city",$city);
				$this->set_variable("payee_state",$state);
				$this->set_variable("country_check",$country);

				if($payee_name=="" || $add1=="" || $add2=="" || $city=="" || $state=="")
				$this->set_notice("mandatory");
				else
				{
					if($config_id == "")
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."publisher_withdrawal_configuration (pid,preferred_mode,check_payee_name,payee_address_line1,payee_address_line2,payee_city,payee_state,payee_country) values (?,?,?,?,?,?,?,?)",array($uid,1,$payee_name,$add1,$add2,$city,$state,$country));
					else
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_configuration set preferred_mode=?,check_payee_name=?,payee_address_line1=?,payee_address_line2=?,payee_city=?,payee_state=?,payee_country=? where pid=? and id=?",array(1,$payee_name,$add1,$add2,$city,$state,$country,$uid,$config_id));

					if($res->error=="")
					{
						$this->flash($this->get_message('withdrawal configuration success'), $this->make_url('user/withdrawal_configuration'));
						exit;
					}
					else
					{
						$this->set_notice("error occurred");
					}
				}
			}
			else if($payment_mode==2)
			{
				$bank_name=$this->read_post_param('b_name');
				$payee_name=$this->read_post_param('payee_name');
				$add1=$this->read_post_param('add1');
				$add2=$this->read_post_param('add2');
				$city=$this->read_post_param('city');
				$state=$this->read_post_param('state');
				$country=$countrycurrent;
				$acc_number=$this->read_post_param('ac_number');
				$swift=$this->read_post_param('swift');

				$this->set_variable("bank_name",$bank_name);
				$this->set_variable("bank_payee_name",$payee_name);
				$this->set_variable("bank_address_line1",$add1);
				$this->set_variable("bank_address_line2",$add2);
				$this->set_variable("bank_city",$city);
				$this->set_variable("bank_state",$state);
				$this->set_variable("country_bank",$country);
				$this->set_variable("account_number",$acc_number);
				$this->set_variable("swift_number",$swift);


				if($bank_name=="" || $payee_name=="" || $add1=="" || $add2=="" || $city=="" || $state=="" || $acc_number=="" || $swift=="")
				$this->set_notice("mandatory");
				else
				{
					if($config_id == "")
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."publisher_withdrawal_configuration (pid,preferred_mode,bank_name,bank_payee_name,account_number,bank_address_line1,bank_address_line2,bank_city,bank_state,bank_country,swift_number) values (?,?,?,?,?,?,?,?,?,?,?)",array($uid,2,$bank_name,$payee_name,$acc_number,$add1,$add2,$city,$state,$country,$swift));
					else
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_configuration set preferred_mode=?,bank_name=?,bank_payee_name=?,account_number=?,bank_address_line1=?,bank_address_line2=?,bank_city=?,bank_state=?,bank_country=?,swift_number=? where pid=? and id=?",array(2,$bank_name,$payee_name,$acc_number,$add1,$add2,$city,$state,$country,$swift,$uid,$config_id));

					if($res->error=="")
					{
						$this->flash($this->get_message('withdrawal configuration success'), $this->make_url('user/withdrawal_configuration'));
						exit;
					}
					else
					{
						$this->set_notice("error occurred");
					}
				}
			}
			else if($payment_mode==3)
			{
				$email=$this->read_post_param('email');

				$this->set_variable("paypal_email",$email);

				if($email=="")
				$this->set_notice("mandatory");
				elseif(!UtilityHelper::is_valid_email($email))
				$this->set_notice("invalid email address");
				else
				{
					if($config_id == "")
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."publisher_withdrawal_configuration (pid,preferred_mode,paypal_email) values (?,?,?)",array($uid,3,$email));
					else
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_configuration set paypal_email=?,preferred_mode=? where pid=? and id=?",array($email,3,$uid,$config_id));

					if($res->error=="")
					{
						$this->flash($this->get_message('withdrawal configuration success'), $this->make_url('user/withdrawal_configuration'));
						exit;
					}
					else
					{
						$this->set_notice("error occurred");
					}
				}
			}
			else if($payment_mode >5)
			{

				$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_configuration");

				$flag=0;
				$string1='';
				$string2='';
				$string3=array();
				$string4='';

				while($row123 = $colalready->fetch_array())
				{
					$carray=explode('_',$row123['Field']);
					if($carray[0] == $payment_mode)
					{
						$submitdata=$db->sanitize(trim($this->read_post_param($row123['Field'])));

						$this->set_variable($row123['Field'],$submitdata);

						if($submitdata !='' && $flag ==0)
						{
							if(
									(
										strtolower($row123['Type']) =='int(11)' ||
										strtolower($row123['Type']) =='int' ||
										strtolower($row123['Type']) =='bigint(20)' ||
										strtolower($row123['Type']) =='bigint'
									) && !is_numeric($submitdata))
							$flag=2;

							if($flag ==0)
							{
								if($config_id == "")
								{
									$string1.=',`'.$db->sanitize($row123['Field']).'`';
									$string2.=',?';
									$string3[]=$submitdata;
								}
								else
								{
									if($string4 !='')
									$string4.=',';

									$string4.='`'.$db->sanitize($row123['Field'])."`='".$submitdata."' ";
								}
							}
						}
						else if($flag ==0)
						{
							$flag=1;
						}
					}
				}


				if($flag ==0)
				{
					if($config_id == "")
					{
						$array=array($uid,$payment_mode);
						$array=array_merge($array,$string3);

						$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."publisher_withdrawal_configuration (pid,preferred_mode".$string1.") values (?,?".$string2.")",$array);

					}
					else
					{
						if($string4 !='')
						$string4.=',';

						$array=array($payment_mode,$uid,$config_id);

						$res=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_configuration set ".$string4." preferred_mode=? where pid=? and id=?",$array);

					}



					if($res->error=="")
					{
						$this->flash($this->get_message('withdrawal configuration success'), $this->make_url('user/withdrawal_configuration'));
						exit;
					}
					else
					{
						$this->set_notice("error occurred");
					}
				}
				else if($flag ==1)
				{
					$this->set_notice("mandatory");
				}
				else if($flag ==2)
				{
					$this->set_notice("only allowed numeric data");
				}
			}
		}
		else
		{

			$modetype=$db->read_single_column("select preferred_mode from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));
			$modetype=intval($modetype);
			$modetypestatus=$db->read_single_column("select status from ".TABLE_PREFIX."withdrawal_gateway where id=?",array($modetype));



			$gatewayflag=1;
			if($modetype >5)
			{
				$usercountry=$this->get_user_country($uid);
				$gatewayflag=0;
				$alowedcountry=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."withdrawal_country_mapping WHERE gateway=?",array($modetype));


				$returncount= $alowedcountry->get_num_records();

				while($alowedcountrydata=$alowedcountry->fetch_assoc())
				{
					if($alowedcountrydata['country_code'] == $usercountry)
					{
						$gatewayflag=1;
						break;
					}
					else
					$gatewayflag=0;
				}


				if($returncount ==0)
				$gatewayflag=1;

			}


			if($modetype >0 && $modetypestatus ==1 && $gatewayflag ==1)
			{
				$res1=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));
				$already=$res1->get_num_records();

				$this->set_result('res1', $res1);
			}
			else
			$already=0;
		}

		$this->set_variable("already",$already);

		$usercountry=$this->get_user_country($uid);


		$row1234=$db->execute_query("SELECT wg.* FROM ".TABLE_PREFIX."withdrawal_gateway wg LEFT OUTER JOIN ".TABLE_PREFIX."withdrawal_country_mapping wcm ON wg.id = wcm.gateway WHERE wg.status=1 AND wg.id <>5 AND (wcm.country_code=? OR wcm.country_code IS NULL)",array($usercountry));
		$this->set_result("row1234",$row1234);



		$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_configuration");
		$this->set_result("colalready",$colalready);

	}

	function cash_withdrawal_action()
	{
		$this->set_title($this->get_label('cash withdrawal request'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$uname=$this->read_cookie_param(COOKIE_USERNAME);

		$db= DAL::get_instance();

	    $modetype=$db->read_single_column("select preferred_mode from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));
	    $modetype=intval($modetype);
	    $modetypestatus=$db->read_single_column("select status from ".TABLE_PREFIX."withdrawal_gateway where id=?",array($modetype));


	    $usercountry=$this->get_user_country($uid);

	    $this->set_variable('usercountry',$usercountry);



	    $gatewayflag=1;
	    if($modetype >5)
	    {
		    $gatewayflag=0;
		    $alowedcountry=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."withdrawal_country_mapping WHERE gateway = ?",array($modetype));

			$allowedcount=$alowedcountry->get_num_records();

		    if($allowedcount >0)
		    {
			    while($alowedcountrydata=$alowedcountry->fetch_assoc())
			    {
			    	if($alowedcountrydata['country_code'] == $usercountry)
			    	{
			    		$gatewayflag=1;
			    		break;
			    	}
			    }
		    }
		    else
		    $gatewayflag=1;
	    }


	    $referral_enabled=$this->get_addon_status('referral_enabled');
	    $pub_status=$this->get_publisher_status($uid);
	    $adv_status=$this->get_advertiser_status($uid);

	    $this->set_variable('referral_enabled',$referral_enabled);
	 	$this->set_variable('adv_status',$adv_status);
	    $this->set_variable('pub_status',$pub_status);

	    if($modetype ==0 || $modetypestatus !=1 || $gatewayflag ==0)
	    {
	    	$this->flash($this->get_message('configure your payment details'), $this->make_url('user/withdrawal_configuration'));
	    	exit;
	    }


		$res1=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));
		$this->set_result('res1', $res1);


		$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_configuration");
		$this->set_result("colalready",$colalready);


		if($_POST)
		{
			$amount=$this->read_post_param('amount');

			$data=$this->withdrawal_calculator_data($modetype,$amount,$uid,0);
			$fee=$data['fee'];
			$tax=$data['tax'];
			$credited=$data['credited'];
			$wamount=$data['total'];
			$tax_details=$data['tax_details'];


			$from=intval($this->read_post_param('from'));

			if($referral_enabled !=1 && $from ==1)
			$from=0;

			$this->set_variable('from', $from);
		    $this->set_variable('amount', $amount);


			if($from ==1)
			$fieldstring=' referral_balance ';
			else
			$fieldstring=' pub_account_balance ';

			$preferred_mode=$db->read_single_column("select preferred_mode from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));


			$balance=$db->read_single_column("select ".$fieldstring." from ".TABLE_PREFIX."users where id=?",array($uid));

			if($amount=="")
			$this->set_notice("mandatory");
			else if(!is_numeric($amount))
			$this->set_notice("please enter a positive value");
			else if($amount < Configuration::get_instance()->read('min_balance_for_publisher'))
			$this->set_notice("publisher amount less");
			else if($balance < $amount)
			$this->set_notice("account balance low publisher");
			else
			{
				$res=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));
				$val=$res->fetch_assoc();

				$db->execute_query("BEGIN");
				$failed=0;

				$value=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_summary (uid,payment_mode,amount,fee,tax,request_time,process_time,status,withdrawal_type,tax_details) values(?,?,?,?,?,?,?,?,?,?)",array($uid,$preferred_mode,$credited,$fee,$tax,time(),0,-1,$from,$tax_details));
				if($value->error=="")
				{
					$id=$value->last_id;

					if($preferred_mode==1)
					{
						$query1="insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,payee_name,address_line1,address_line2,city,state,country) values(?,?,?,?,?,?,?)";
						$value1=$db->execute_query($query1,array($id,$val['check_payee_name'],$val['payee_address_line1'],$val['payee_address_line2'],$val['payee_city'],$val['payee_state'],$val['payee_country']));
					}
					else if($preferred_mode==2)
					{
						$query1="insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,payee_name,address_line1,address_line2,city,state,country,account_number,bank_name,swift_number) values(?,?,?,?,?,?,?,?,?,?)";
						$value1=$db->execute_query($query1,array($id,$val['bank_payee_name'],$val['bank_address_line1'],$val['bank_address_line2'],$val['bank_city'],$val['bank_state'],$val['bank_country'],$val['account_number'],$val['bank_name'],$val['swift_number']));
					}
					else if($preferred_mode==3)
					$value1=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,paypal_email) values(?,?)",array($id,$val['paypal_email']));
					else if($preferred_mode >5)
					{
						$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_configuration");

						$string1='';
						$string2='';
						$string3=array();

						while($row123 = $colalready->fetch_array())
						{
							$carray=explode('_',$row123['Field']);
							if($carray[0] == $preferred_mode)
							{
								$submitdata=$this->get_withdrawal_configuration_data($row123['Field'],$uid);

								$string1.=',`'.$row123['Field'].'`';
								$string2.=',?';
								$string3[]=$submitdata;
							}
						}

						$array=array($id);
						$array=array_merge($array,$string3);

						$value1=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid".$string1.") values(?".$string2.")",$array);
					}

					if($value1->error=="")
					{
						if($from ==1)
						{
							$fieldstring1=' referral_balance=referral_balance-? ';
							$fieldstring2=' referral_balance >=? ';
						}
						else
						{
							$fieldstring1=' pub_account_balance=pub_account_balance-? ';
							$fieldstring2=' pub_account_balance >=? ';
						}

						$res3=$db->execute_query("UPDATE ".TABLE_PREFIX."users set ".$fieldstring1." where id=? AND ".$fieldstring2." ",array($wamount,$uid,$wamount));

						if($res3->error !="")
						$failed=1;

						if($res3->get_num_records() ==0)
						$failed=1;
					}
					else
						$failed=1;
				}
				else
					$failed=1;



				if($failed == 0)
				{
					$db->execute_query("COMMIT");


	 				if($from == 1)
				  	$from_message = $this->get_label('referral balance');
				  	else
			      	$from_message = $this->get_label('publisher balance');

			    	$fee_string 	= "";
			    	$tax_string 	= "";
			    	$total_string	= "";


			    	if($fee >0)
			    	$fee_string="<br/>Fee     			: ".$this->get_money_format($fee);

			    	if($tax >0)
			    	$tax_string="<br/>Tax     			: ".$this->get_money_format($tax);

			    	if($fee >0 || $tax >0)
			    	$total_string="<br/>Balance Amount  : ".$this->get_money_format($credited+$tax+$fee);


$message ='

Hello,

There is a new withdrawal request.The details are given below.

Username			: '.$uname.'
Withdrawal Mode  	: '.$this->get_withdrawal_mode($preferred_mode).'
Withdrawal From		: '.$from_message.'
Withdrawal Amount	: '.$wamount.'
'.$fee_string.$tax_string.$total_string.'

Best Regards,
'.Configuration::get_instance()->read('admarket_name');

$admin_email=Configuration::get_instance()->read('admin_notification_email');

UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - withdrawal request generated",nl2br($message));



					$this->flash($this->get_message('withdrawal request success'), $this->make_url('user/withdrawal_history'));
				}
				else if($failed == 1)
				{
					$db->execute_query("ROLLBACK");
					$this->set_notice("error occurred");
				}
			}
		}
	}


	function withdrawal_history_action()
	{
		$this->set_title($this->get_label('manage withdrawal'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();


		if($_POST)
		{
			$payment=intval($this->read_post_param('payment'));
			$status=intval($this->read_post_param('status'));
			$payment_type=intval($this->read_post_param('payment_type'));
		}
		else
		{
			$payment=$this->read_page_param(1);
			$status=$this->read_page_param(2);
			$payment_type=$this->read_page_param(3);

			$exp=explode("-",$payment);
			if($exp[0]=="page")
			$payment=0;

			$exp=explode("-",$status);
			if($exp[0]=="page")
			$status=4;

			$exp=explode("-",$payment_type);
			if($exp[0]=="page")
			$payment_type=2;
		}

		$referral_enabled=$this->get_addon_status('referral_enabled');
		$pub_status=$this->get_publisher_status($uid);

		$this->set_variable('referral_enabled', $referral_enabled);
		$this->set_variable('pub_status', $pub_status);



		if($status === "")
		$status=4;


		if($payment === "")
		$payment=0;

		if($pub_status ==1 && $referral_enabled ==1 && ($payment_type =="" || $payment_type ==2))
		$payment_type=2;
		else if($pub_status ==1  && ($payment_type =="" || $payment_type ==2))
		$payment_type=0;
		else if($referral_enabled ==1 && ($payment_type =="" || $payment_type ==2))
		$payment_type=1;


		if($status==4)
		$status_str="";
		else
		$status_str=" and status=".intval($status)." ";




		if($payment==0)
		$payment_str="";
		else
		$payment_str=" and payment_mode=".intval($payment)." ";


		$type_str="";
		if($payment_type != 2)
		$type_str=" AND withdrawal_type=".intval($payment_type)." ";



		$pagination2 = new Pagination("SELECT * FROM ".TABLE_PREFIX."publisher_withdrawal_summary where uid=? ".$payment_str.$status_str.$type_str." ORDER BY id desc",array($uid));
		$res3=$pagination2->get_result();
		$this->set_result("res3",$res3);
		$this->set_variable("pagination2",$pagination2->links(),0);


		$pg=$pagination2->get_page_number();
		$this->set_variable("pg","page-".$pg);

		$this->set_variable('payment', $payment);
		$this->set_variable('status', $status);
		$this->set_variable('payment_type', $payment_type);
	}

	function withdrawal_delete_action()
	{
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();

		$sid=$this->read_page_param(1);
		$frompg=$this->read_page_param(2);
		$pt=$this->read_page_param(3);
		$st=$this->read_page_param(4);
		$payment_type=$this->read_page_param(5);
		$pg=$this->read_page_param(6);

		if($frompg=="")
		$frompg=0;




		if(Configuration::get_instance()->read('enable_auto_withdrawal') == 2)
		{
			if(!$this->get_mywithdrawal($sid,$uid))
			{
				$this->flash($this->get_message('invalid id'), $this->make_url('user/withdrawal_history'),0);
				exit;
			}

			if(!$this->get_transaction_pending($sid))
			{
				$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_history'),0);
				exit;
			}


			$res=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_summary where id=?",array($sid));
			$result=$res->fetch_assoc();

			$amount			= $result['amount'];
			$fee			= $result['fee'];
			$tax			= $result['tax'];
			$payment_mode	= $result['payment_mode'];
			$from			= $result['withdrawal_type'];

			$amount			= $amount+$fee+$tax;

			if($from ==1)
			$fieldstring1=' referral_balance=referral_balance+? ';
			else
			$fieldstring1=' pub_account_balance=pub_account_balance+? ';

			$db->execute_query("BEGIN");
			$failed=0;

			$update=$db->execute_query("update ".TABLE_PREFIX."users set ".$fieldstring1." where id=?",array($amount,$uid));

			if($update->error =="")
			{
				$update1=$db->execute_query("DELETE FROM ".TABLE_PREFIX."publisher_withdrawal_summary WHERE id=?",array($sid));

				if($update1->error =="")
				{
					if($payment_mode !=5)
					{
						$update2=$db->execute_query("DELETE FROM ".TABLE_PREFIX."publisher_withdrawal_details WHERE paymentid=?",array($sid));

						if($update2->error !="")
						$failed=1;
					}
				}
				else
				$failed=1;
			}
			else
			$failed=1;


			if($failed ==0)
			{
				$db->execute_query("COMMIT");
				$messagedata=$this->get_message('withdrawal delete success');
			}
			else
			{
				$db->execute_query("ROLLBACK");
				$messagedata=$this->get_message('error occurred');
			}
		}
		else
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_history'),0);
			exit;
		}



		if($frompg==1)
		$this->flash($messagedata, $this->make_url('user/withdrawal_history/'.$pt.'/'.$st.'/'.$payment_type.'/'.$pg));
		else
		$this->flash($messagedata, $this->make_url('user/withdrawal_history'));

		exit;
	}

	function withdrawal_details_action()
	{
		$this->set_title($this->get_label('withdrawal details'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();
		$sid=$this->read_page_param(1);


		if(!$this->get_mywithdrawal($sid,$uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('user/withdrawal_history'),0);
			exit;
		}



		$mode=$db->read_single_column("select payment_mode from ".TABLE_PREFIX."publisher_withdrawal_summary where id=? and uid=?",array($sid,$uid));


		if($mode !=5)
		$res=$db->execute_query("select s.*,d.*,s.id as sid from ".TABLE_PREFIX."publisher_withdrawal_summary s INNER JOIN ".TABLE_PREFIX."publisher_withdrawal_details d ON s.id=d.paymentid where s.id=? and s.uid=?",array($sid,$uid));
		else if($mode ==5)
		$res=$db->execute_query("select s.*,s.id as sid  from ".TABLE_PREFIX."publisher_withdrawal_summary s where s.id=? and s.uid=?",array($sid,$uid));

		$this->set_result("res",$res,array('tax_details'));

		$this->set_variable("mode",$mode);


		$this->set_variable("usname",$this->get_user_name($uid));


		$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_details");
		$this->set_result("colalready",$colalready);
	}


	function fund_transfer_request_action()
	{
			$this->set_title($this->get_label('fund transfer'));
			$uid = $this->read_cookie_param(COOKIE_LOGINID);

			$db= DAL::get_instance();

			$adst=$db->read_single_column("SELECT adv_status from ".TABLE_PREFIX."users where id=?",array($uid));

			if($adst !=1)
			{
				$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_history'),0);
				exit;
			}

		  $referral_enabled=$this->get_addon_status('referral_enabled');
	    $pub_status=$this->get_publisher_status($uid);

	    $this->set_variable('referral_enabled',$referral_enabled);
	    $this->set_variable('pub_status',$pub_status);
			$this->set_variable('uid',$uid);

			if($_POST)
			{
				$amount=$this->read_post_param('amount');
				$from=intval($this->read_post_param('from'));

				if($referral_enabled !=1 && $from ==1)
				$from=0;

				$this->set_variable('from', $from);
				$this->set_variable('amount', $amount);

				if($from ==1)
				$fieldstring=' referral_balance ';
				else
				$fieldstring=' pub_account_balance ';

				$pub_balance=$db->read_single_column("select ".$fieldstring." from ".TABLE_PREFIX."users where id=?",array($uid));

				$t=time();
				if($amount=="")
				{
					$this->set_notice("mandatory");
				}
				else if(!is_numeric($amount))
				{
					$this->set_notice("please enter a positive value");
				}
				else if($amount < Configuration::get_instance()->read('min_balance_for_publisher'))
				{
					$this->set_notice("publisher amount less");
				}
				else if($pub_balance < $amount)
				{
					$this->set_notice("account balance low");
				}
				else
				{


				$db->execute_query("BEGIN");
				$failed=0;


				$value=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_summary (uid,payment_mode,amount,request_time,process_time,status) values (?,?,?,?,?,?)",array($uid,5,$amount,$t,0,-1));

				if($value->error=="")
				{
					if($from ==1)
					{
						$fieldstring1=' referral_balance=referral_balance-? ';
						$fieldstring2=' referral_balance >=? ';
					}
					else
					{
						$fieldstring1=' pub_account_balance=pub_account_balance-? ';
						$fieldstring2=' pub_account_balance >=? ';
					}

					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."users set ".$fieldstring1." where id=? AND ".$fieldstring2." ",array($amount,$uid,$amount));

					if($res->error !="")
					$failed=1;

					if($res->get_num_records() ==0)
					$failed=1;
				}
				else
				$failed=1;


				if($failed==0)
				{
					$db->execute_query("COMMIT");
					$this->flash($this->get_message('fund transfer request success'), $this->make_url('user/withdrawal_history'));
				}
				if($failed==1)
				{
					$db->execute_query("ROLLBACK");
					$this->set_notice("error occurred");
				}
			}
		}
	}







 	function invoice_export_action()
	{
	    $id=$this->read_page_param(1);
	    $uid=$this->read_cookie_param(COOKIE_LOGINID);

	    if(!$this->get_mywithdrawal($id,$uid))
	    {
	     	$this->flash($this->get_message('invalid id'), $this->make_url('user/withdrawal-history'),0);
	     	exit;
	    }


		$wkhtmlpath=Configuration::get_instance()->read('wkhtml_path');
	    $invoice_download=Configuration::get_instance()->read('invoice_download');

	    if($invoice_download== 0 || $wkhtmlpath=='')
	    {
	     	$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal-history'),0);
	     	exit;
	    }


	    if(!is_callable('shell_exec') || stripos(ini_get('disable_functions'),'shell_exec'))
	    {
	         $this->flash($this->get_message('shell execute function enable for pdf download'),$this->make_url('user/withdrawal-history') ,0);
	    }

	    if(!is_dir(PATH_TO_ROOT.DATA_DIR.'/pdf/'))
	        mkdir(PATH_TO_ROOT.DATA_DIR.'/pdf/',0777);


	        $filename=PATH_TO_ROOT.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';
	        $shellfilename=getcwd().'/'.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';


	        ob_clean();


	        header("Content-Description: File Transfer");
	        header("Pragma: no-cache");
	        header("Expires: 0");
	        header("Pragma: public"); // required
	        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	        header("Cache-Control: private",false); // required for certain browsers

	        if(isset($_COOKIE['my_locale']))
	            $localname=$_COOKIE['my_locale'];
	            else
	                $localname=DEFAULT_LOCALE;



	                $stringdata=md5($id.$uid.$localname.'data'.Configuration::get_instance()->read('admarket_name'));


	                 $execution_path=$this->make_url('user/invoice_pdf/'.$id.'/'.$uid.'/'.$localname.'/'.$stringdata);

	               $filecontent=$wkhtmlpath.'  '.$execution_path.'  '.$shellfilename;
	                 shell_exec($filecontent);

	                 sleep(5);


	                header("Content-Type: application/octet-stream");
	                header("Content-Type: application/download");
	                header("Content-Type: application/pdf");
	                header("Content-Disposition: attachment; filename=".basename($filename));
	                header("Content-Length: " . filesize($filename));


	                readfile($filename);
	                unlink($filename);

	                exit;
	}


	function invoice_pdf_action()
	{
	    $db= DAL::get_instance();

	    $id=$this->read_page_param(1);
	    $uid=$this->read_page_param(2);
            $localname=$this->read_page_param(3);
	    $stringdata=$this->read_page_param(4);

	    $newstringdata=md5($id.$uid.$localname.'data'.Configuration::get_instance()->read('admarket_name'));

	    if($stringdata != $newstringdata)
	    exit;

      	    $languageid=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));

      if($languageid >0)
      {
          $this->set_locale($localname);
          $GLOBALS['locale_name']=$localname;
      }

      $res=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_summary where id=?",array($id));
      $this->set_result("res",$res,array('tax_details'));

      $res1=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));
      $this->set_result("res1",$res1);
      $paypalemail=$db->read_single_column("select paypal_email from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));

      $this->set_variable("paypalemail",$paypalemail);

      $this->set_variable("id",$id);
	}


function withdrawal_calculation_action()
{
    $db= DAL::get_instance();

    $div					= "";
    $payment_fee			= 0;
    $tax					= 0;
    $credited				= 0;
    $taxpercent				= 0;


    $uid		 			= $this->read_cookie_param(COOKIE_LOGINID);
    $ptype		 			= $this->read_post_param('ptype');
    $usercountry 			= $this->read_post_param('country');
	$pamount	 			= $this->read_post_param('amount');
	$from					= intval($this->read_post_param('from'));     // From 1 => Payment edit page , 0 => Add fund page
    $amount		 			= $pamount;

    $apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');
    $tax_calculation		= Configuration::get_instance()->read('tax_calculation');

    $payment_fee=Configuration::get_instance()->read("withdrawal_fee".$ptype);


	    if($apply_tax_rules ==1)
	    {
		    $taxrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."tax_rules WHERE status=1 AND (user_type=2 OR user_type= 3)");

		    $i 					= 0;
		    $tax_percentage		= 0;
		    $enter_tax_section  = 0;

		    while($taxdata = $taxrow->fetch_assoc())
		    {
		    	$json_string = $taxdata['country'];

		    	$json_array  = array();

		    	if($json_string != "")
		    	{
		    		$json_array  = json_decode($json_string,1);

		    		if(!isset($json_array[$usercountry]))
		    		continue;
		    	}


		    	$tax_percentage=$tax_percentage+$taxdata['tax'];


		    	if($i == 0)
		    	{

		    	   	   $enter_tax_section = 1;

	               $div.='<div class="tax-fee-class">';


	               $div.='<div class="col-md-12 col-sm-12 col-xs-12">'.$this->get_label("tax").'</div>';

	               $div.='<div class="col-md-12 col-sm-12 col-xs-12">';
		    	}

                $taxamount=$pamount*($taxdata['tax']/100);
                $amount=$amount-$taxamount;

                $tax=$tax+$taxamount;


                $i++;

                $div.= '<div>'.$i.' . '.$taxdata['name'].' - '.$this->get_money_format($taxamount).'&nbsp;<span class="notification"><bdi>['.$taxdata['tax'].$this->get_label('% of amount').']</bdi></span></div>';
		    }


		    if($enter_tax_section ==1)
		    {
			    $div.='</div></div>';
		    }
	    }


	    if($payment_fee > 0)
		  $fee=$amount*($payment_fee/100);



	    if($fee > 0)
		  {
			 		 $div.='<div class="tax-fee-class">';

			 	 	 $div.='<div class="col-md-12 col-sm-12 col-xs-12" >'.$this->get_label("fee").'</div>';

	         $div.='<div class="col-md-12 col-sm-12 col-xs-12">'.$this->get_money_format($fee);

	         $div.='&nbsp;<span class="notification"><bdi>[';

	         if($enter_tax_section == 0)
	         $div.=$this->get_label('% of withdrawal amount',array('x'=>$payment_fee));
	         else
	         $div.=$this->get_label('% of withdrawal amount tax',array('x'=>$payment_fee));

	         $div.=']</bdi></span>';

			 $div.='</div></div>';
		}



		if($enter_tax_section ==1 || $fee > 0)
		{
			 $div.='<div class="col-md-12 col-sm-12 col-xs-12 tax-fee-class">';

			 $div.='<div class="col-md-12 col-sm-12 col-xs-12">'.$this->get_label("amount receivable").'</div>';

	     $div.='<div class="col-md-12 col-sm-12 col-xs-12">'.$this->get_money_format($amount-$fee);

			 $div.='</div></div>';
		}

	    $ds_places=Configuration::get_instance()->read('decimal_place');

     	$div.='<input type="hidden" id="total'.$ptype.'" name="total'.$ptype.'" value="'.round($pamount,$ds_places).'" />';

    echo  $div;
    die;
}



	function change_email_action()
	{
		$this->set_title($this->get_label('change email'));
		$db = DAL::get_instance();

		$uid    = $this->read_cookie_param(COOKIE_LOGINID);

		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('user/account'),0);
			exit;
		}

		$currentEmail = "";
		$newEmail     = "";

		if($_POST)
		{
            $currentEmail = $this->read_post_param('currentEmail');
            $newEmail     = $this->read_post_param('newEmail');

			$emailExists  = $db->read_single_column("select id from ".TABLE_PREFIX."users where email = ?", array($newEmail));

			$userDetails  = $db->execute_query("select id,username,email,locale from ".TABLE_PREFIX."users where id = ? ", array($uid));

			if($userDetails->get_num_records() > 0)
			{
				$userDetailsRow = $userDetails->fetch_assoc();

				$dbEmail        = $userDetailsRow['email'];
				$localeid 		= $userDetailsRow['locale'];
				$username       = $userDetailsRow['username'];

				if($currentEmail == "" || $newEmail == "")
				$this->set_notice("mandatory");
				else if(!UtilityHelper::is_valid_email($currentEmail) || !UtilityHelper::is_valid_email($newEmail))
				$this->set_notice("invalid email address");
				else if(intval($emailExists) > 0)
				$this->set_notice("email exists");
				else if($dbEmail != $currentEmail || $currentEmail == $newEmail)
				$this->set_notice("invalid email address");
				else
				{
				 	$update = $db->execute_query("UPDATE ".TABLE_PREFIX."users SET change_email = ? WHERE id = ?",array($newEmail,$uid));

				    if($update->error == "")
				    {
						$res1    = $db->execute_query("select * from ".TABLE_PREFIX."email_templates where id = 28");
						$result1 = $res1->fetch_assoc();

						$language_enabled = Configuration::get_instance()->read('language_enabled');
						$admarket_name    = Configuration::get_instance()->read('admarket_name');

						if($language_enabled ==1 && $localeid > 0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
						$message = $result1[$localeid.'_message'];
						else
						$message = $result1['message'];

						if($language_enabled ==1 && $localeid > 0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
						$subject = $result1[$localeid.'_subject'];
						else
						$subject = $result1['subject'];

						$confirmurl = BASE."index.php?page=user/change_email_confirmation/".md5($uid.$admarket_name.$newEmail.$currentEmail);

						$subject=str_replace("{ADMARKETNAME}",$admarket_name,$subject);
						$message=str_replace("{ADMARKETNAME}",$admarket_name,$message);
						$message=str_replace("{USERNAME}",$username,$message);
						$message=str_replace("{CONFIRMURL}",$confirmurl,$message);

						if(UtilityHelper::send_mail($newEmail,$subject,$message))
						$this->flash($this->get_message('change email confirmation'),$this->make_url("user/account"));
						else
						$this->set_notice('change email operation failed');
					}
					else
					$this->set_notice('error occurred');
				}
		    }
		    else
		    $this->set_notice('invalid operation');
		}

		$this->set_variable("currentEmail",$currentEmail);
		$this->set_variable("newEmail",$newEmail);
	}



	function change_email_confirmation_action()
	{
		$uid      = $this->read_cookie_param(COOKIE_LOGINID);
		$mdvalue  = $this->read_page_param(1);

		$db = DAL::get_instance();

		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('user/account'),0);
			exit;
		}

		$admarket_name    = Configuration::get_instance()->read('admarket_name');

		$userDetails  = $db->execute_query("select id,email,change_email from ".TABLE_PREFIX."users where id = ? ", array($uid));

		if($userDetails->get_num_records() > 0)
		{
			$userDetailsRow = $userDetails->fetch_assoc();

			$dbEmail        = $userDetailsRow['email'];
			$dbChangeEmail  = $userDetailsRow['change_email'];

			$mdvalue_new    = md5($uid.$admarket_name.$dbChangeEmail.$dbEmail);

			if($mdvalue_new != $mdvalue)
			{
				$this->flash($this->get_message('invalid operation'),$this->make_url("user/account"));
				exit;
			}
			else
			{
 				$update = $db->execute_query("UPDATE ".TABLE_PREFIX."users SET email = ?,change_email = ? WHERE id = ?",array($dbChangeEmail,'',$uid));

			    if($update->error == "")
			    {
					$newsletteraddon_folder   = $this->xyz_get_addon_folder_name("XYZADMNLR");
					$newsletter_enable        = $this->get_addon_status($newsletteraddon_folder.'_enabled');

					if($newsletter_enable == 1)
					{
						$emailUserID = $db->read_single_column("select id from ".TABLE_PREFIX."email_address where email = ?",array($dbChangeEmail));

						if(intval($emailUserID) == 0)
						$db->execute_query("UPDATE ".TABLE_PREFIX."email_address SET email = ?  WHERE email = ?",array($dbChangeEmail,$dbEmail));
					}

					$this->flash($this->get_message('you have successfully updated the email'),$this->make_url("user/account"));
				}
				else
				$this->flash($this->get_message('error occurred'),$this->make_url("user/account"));
			}
		}
		else
		$this->flash($this->get_message('invalid operation'),$this->make_url("user/account"));
	}
};
?>
