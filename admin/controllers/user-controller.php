<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

if(file_exists(ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php"))
include_once ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php";


if(file_exists(ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php"))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";


class UserController extends ApplicationController
{

	function before_execute()
	{
		parent::before_execute();

		if($this->get_action() != "invoice_pdf")
		{
			if(!(LoginHelper::validate_admin_login()))
			{
				$this->flash($this->get_message('login failed'), $this->make_url('index/index'),0);
			}
		}

		if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==1)
		{
			if($this->get_addon_status('subadmin_enabled') ==1 && isset($GLOBALS['privilege']))
			$privilege=$GLOBALS['privilege'];
			else
			$privilege=array();


			if(!isset($privilege['mu_1']) && !isset($privilege['mu_2']) && !isset($privilege['mu_3']) && !isset($privilege['mu_4'])&& !isset($privilege['mu_5']) && !isset($privilege['pw_1']) && !isset($privilege['pw_2']) && !isset($privilege['pw_3']) && !isset($privilege['pw_4']))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if($this->get_action() !="list" && $this->get_action() !="profile" && $this->get_action() !="advall" && $this->get_action() !="advadstat" && $this->get_action() !="advtimestat" && $this->get_action() !="advpayment" && $this->get_action() !="puball" && $this->get_action() !="pubadunitstat" && $this->get_action() !="statistics_publisher_profile" && $this->get_action() !="pubtimestat" && $this->get_action() !="pubwithdrawal")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['mu_1']) && $this->get_action() =="list")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['mu_2']) && $this->get_action() =="profile")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['mu_3']) && ($this->get_action() =="advall" || $this->get_action() =="advadstat" || $this->get_action() =="advtimestat"))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['mu_4']) && ($this->get_action() =="puball" || $this->get_action() =="pubadunitstat" || $this->get_action() =="statistics_publisher_profile" || $this->get_action() =="pubtimestat"))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);

			else if(!isset($privilege['mu_5']) && ( $this->get_action() =="advpayment" ||  $this->get_action() =="pubwithdrawal"))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);

			else if(!isset($privilege['pw_1']) && $this->get_action() =="withdrawal_history")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['pw_2']) && $this->get_action() =="approve_withdrawal")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['pw_3']) && $this->get_action() =="reject_withdrawal")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['pw_4']) && $this->get_action() =="withdrawal_details")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);

		}
	}



	function mail_action()
	{

		$this->set_title($this->get_label('send email'));

		$uid=$this->read_page_param(1);
		$frompg=$this->read_page_param(2);
		$status=$this->read_page_param(3);
		$type=$this->read_page_param(4);
		$search_by=$this->read_page_param(5);
		$pg=$this->read_page_param(6);

		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
		$pg="";

		if($pg=="")
		$pg="page-1";

		if($type=="")
		$type=1;

		if($status=="")
		$status=2;

		$this->set_variable("pg",$pg);
		$this->set_variable("type",$type);
		$this->set_variable("status",$status);
		$this->set_variable("search_by",$search_by);


		$db= DAL::get_instance();
		$id=14;
		if($_POST)
		{
			$subject=$this->read_post_param('subject');
			$message=$this->read_post_param('message');

			$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

			if($subject=="" || $message=="")
			{
				$this->set_notice('mandatory');
			}
	        else
	        {

			UtilityHelper::send_mail($email,$subject,$message);


			if($search_by==3)
			    $value=$email;
			else if($search_by==2)
			    $value=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
			else if($search_by==1)
			    $value=$uid;
			else
			    $value='';


		    if($frompg==1)
		        $this->flash($this->get_message('email send'), $this->make_url('user/list/'.$status.'/'.$type."/".$search_by."/".$value.'/'.$pg));
			else
			$this->flash($this->get_message('email send'), $this->make_url('user/profile/'.$uid.'/0'));
	        }

		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
			$detail=$res->fetch_assoc();

			$language_enabled=Configuration::get_instance()->read('language_enabled');
			$localeid=$this->get_user_locale($uid);

			if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_message']) && $detail[$localeid.'_message'] !='')
			$message=$detail[$localeid.'_message'];
			else
			$message=$detail['message'];


			if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_subject']) && $detail[$localeid.'_subject'] !='')
			$subject=$detail[$localeid.'_subject'];
			else
			$subject=$detail['subject'];


			$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));

			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

			$message=str_replace("{USERNAME}",$username,$message);

		}


		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);
		$this->set_variable("uid",$uid);
		$this->set_variable("frompg",$frompg);



	}



	function list_action()
	{
		$this->set_title($this->get_label('manage users'));
		$db= DAL::get_instance();
		$manager_str='';
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=intval($this->read_cookie_param(COOKIE_ADMIN_LOGINID));

		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==2)
			$manager_str=" and adv_managerid=".$adminid;
			else if($admintype ==3)
			$manager_str=" and pub_managerid=".$adminid;
		}

		$uname = "";
		$email = "";
		$uid   = "";

		if($_POST)
		{
			$type=$this->read_post_param('type');
			$status=intval($this->read_post_param('status'));
			$search_by=intval($this->read_post_param('search_by'));
			$email=$this->read_post_param('email');

			$uname=$this->read_post_param('username');
			$uid=intval($this->read_post_param('uid'));
			$this->set_variable("uid",$uid);

		}
		else
		{
		    $status=$this->read_page_param(1);
		    $type=$this->read_page_param(2);
		    $search_by=$this->read_page_param(3);
		    $value=$this->read_page_param(4);

			$exp=explode("-",$status);
			if($exp[0]=="page")
			   $status=2;

			$exp=explode("-",$type);
			if($exp[0]=="page")
			    $type=1;

			$exp=explode("-",$search_by);
			if($exp[0]=="page")
			    $search_by=0;

			$exp=explode("-",$value);
			if($exp[0]=="page")
			    $value="";

			if($search_by ==3)
			    $email=$value;

			else if($search_by == 2)
			    $uname=$value;
		    else if($search_by==1)
		        $uid=$value;


		$this->set_variable("uid",$uid);
		$this->set_variable("type",$type);

		}


		if($type === "")
		$type=1;

		if($status === "")
		$status=2;

		if($search_by === "")
		$search_by=0;


	  $status_str="";
	  $search_by_str="";



	   if($status==2)
	   {
	       if($type==1 && $search_by!=0 && ( $uname!='' || $uid!='' || $email !=''))
	           $status_str=" AND adv_status <> -2 ";
	       else if($type==2 && $search_by!=0 && ( $uname!='' || $uid!='' || $email !=''))
	           $status_str=" AND pub_status <> -2 ";
	       else
	           $status_str="";
	   }
       else
	   {
	        if($type==1)
		$status_str=" AND adv_status=".intval($status)." ";
	  	else if($type==2)
		$status_str=" AND pub_status=".intval($status)." ";

       }





	  if($search_by ==2 && $uname !="")
	  $search_by_str=" AND username LIKE '%".$db->sanitize($uname)."%' ";
	  else if($search_by ==1 && $uid >0)
	  $search_by_str=" AND id =".intval($uid)." ";
	  else if($search_by ==3 && $email !='')
	  $search_by_str=" AND email ='".$db->sanitize($email)."' ";







		$this->set_variable("type",$type);
		$this->set_variable("status",$status);
		$this->set_variable("uname",$uname);
		$this->set_variable("search_by",$search_by);
		$this->set_variable("email",$email);




		$pagination = new Pagination("SELECT id,username,adv_status,pub_status,email FROM ".TABLE_PREFIX."users where id >0 ".$status_str.$search_by_str." ".$manager_str." ORDER BY id desc");
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);

		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);
	}



	function profile_action()
	{
		$this->set_title($this->get_label('view profile'));
		$db= DAL::get_instance();


		$id=$this->read_page_param(1);
		$tab=$this->read_page_param(2);
		$ut=$this->read_page_param(3);

		if($tab=="")
		$tab=0;

		if(!$this->get_user_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('index/control_panel'),0);
			exit;
		}


		$cpc_enabled		= $this->get_addon_status('cpc_enabled');
		$cpm_enabled		= $this->get_addon_status('cpm_enabled');
		$cpa_enabled		= $this->get_addon_status('cpa_enabled');
		$cpd_enabled		= $this->get_addon_status('sponsored_enabled');
		$pop_enabled		= $this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled  	= $this->get_addon_status('affiliate-ads_enabled');
		$video_enabled		= $this->get_addon_status('video-ads_enabled');
		$subadmin_enabled   	= $this->get_addon_status('subadmin_enabled');
		$html5_enabled		= $this->get_addon_status('html5-ads_enabled');

		$feedads_enabled    = intval($this->get_addon_status('feed-ads_enabled'));

		if($feedads_enabled == 1)
		$feedads_enabled = intval(Configuration::get_instance()->read('supply_feed_ads'));

		$direct_link_ads_enabled = $this->get_addon_status('direct-link-ads_enabled');

		if(isset($_POST['saveadvertiserdatabutton']))
		{

			if(DEMO_MODE && $id <= 2)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url("user/profile/".$id."/".$tab),0);
				exit;
			}


		    $ppc_minrate		= $this->read_post_param('ppc_minrate');
		    $cpm_minrate		= $this->read_post_param('cpm_minrate');
		    $cpa_minrate		= $this->read_post_param('cpa_minrate');
		    $pop_minrate		= $this->read_post_param('pop_minrate');
		    $video_minrate		= $this->read_post_param('video_minrate');
		    $affiliate_minrate		= $this->read_post_param('affiliate_minrate');
		    $adv_manager		= $this->read_post_param('adv_mngr');
		    $html5_support		= $this->read_post_param('html5_support');


		    $string_update="";

		    $string_array=array();


			if($cpc_enabled ==1)
			{
				$string_update.='ppc_minrate=?';

				$string_array[]=$ppc_minrate;
			}

			if($cpm_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='cpm_minrate=?';

				$string_array[]=$cpm_minrate;

			}

			if($cpa_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='cpa_minrate=?';

				$string_array[]=$cpa_minrate;
			}

			if($pop_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='pop_minrate=?';

				$string_array[]=$pop_minrate;

			}

			if($video_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='video_minrate=?';

				$string_array[]=$video_minrate;
			}

			if($affiliate_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.= 'affiliate_minrate=?';

				$string_array[]=$affiliate_minrate;
			}

		    	if($subadmin_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='adv_managerid=?';

				$string_array[]=$adv_manager;
			}

		    	if($html5_enabled == 1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='html5_support=?';

				$string_array[]=$html5_support;
			}





			$string_array[]=$id;


			if($string_update !="")
			$data=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET ".$string_update." WHERE id=?",$string_array);


			$this->flash($this->get_message('advertiser configurations successfully updated'), $this->make_url("user/profile/".$id."/".$tab));
			exit;
		}



		if(isset($_POST['savepublisherdatabutton']))
		{

			if(DEMO_MODE && $id <= 2)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url("user/profile/".$id."/".$tab),0);
				exit;
			}


			$ppc_profit_percentage			= $this->read_post_param('ppc_profit_percentage');
			$cpm_profit_percentage			= $this->read_post_param('cpm_profit_percentage');
			$cpa_profit_percentage			= $this->read_post_param('cpa_profit_percentage');
			$sponsored_profit_percentage	= $this->read_post_param('sponsored_profit_percentage');
			$pop_profit_percentage			= $this->read_post_param('pop_profit_percentage');
			$cpv_profit_percentage			= $this->read_post_param('cpv_profit_percentage');
			$affiliate_profit_percentage	= $this->read_post_param('affiliate_profit_percentage');
			$pub_manager					= $this->read_post_param('pub_mngr');

			if($direct_link_ads_enabled == 1)
			$directlink_availability		= $this->read_post_param('directlink_availability');
			


			$vast_adcode_enabled			= $this->read_post_param('vast_adcode_enabled');
			$pub_captcha_status				= $this->read_post_param('pub_captcha_status');

			if($feedads_enabled == 1)
			$supply_feed_ads				= $this->read_post_param('supply_feed_ads');


			$string_update="";

		    $string_array=array();


			if($cpc_enabled ==1)
			{
				$string_update.='ppc_profit_percentage=?,pub_captcha_status=?,pub_captcha_time=?';

				$string_array[]=$ppc_profit_percentage;
				$string_array[]=$pub_captcha_status;
				$string_array[]=0;
			}

			if($cpm_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='cpm_profit_percentage=?';

				$string_array[]=$cpm_profit_percentage;

			}

			if($cpa_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='cpa_profit_percentage=?';

				$string_array[]=$cpa_profit_percentage;
			}

			if($cpd_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='sponsored_profit_percentage=?';

				$string_array[]=$sponsored_profit_percentage;
			}

			if($pop_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='pop_profit_percentage=?';

				$string_array[]=$pop_profit_percentage;
				

			}

			if($direct_link_ads_enabled == 1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='directlink_availability=?';

				$string_array[]= $directlink_availability;				
			}
			
			if($video_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='cpv_profit_percentage=?,vast_adcode_enabled=?';

				$string_array[]=$cpv_profit_percentage;
				$string_array[]=$vast_adcode_enabled;
			}

			if($affiliate_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.= 'affiliate_profit_percentage=?';

				$string_array[]=$affiliate_profit_percentage;
			}

		    if($subadmin_enabled ==1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='pub_managerid=?';

				$string_array[]=$pub_manager;
			}


			if($feedads_enabled == 1)
			{
				if($string_update !="")
				$string_update.=',';

				$string_update.='supply_feed_ads=?';

				$string_array[]=$supply_feed_ads;

				if(isset($_POST['feed_auth_key']))
				{
					if($string_update !="")
					$string_update.=',';

					$string_update.='feed_auth_key=?';

					$string_array[]=$this->read_post_param('feed_auth_key');
				}
			}

			$string_array[]=$id;


			if($string_update !="")
			$data=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET ".$string_update." WHERE id=?",$string_array);


			$this->flash($this->get_message('publisher configurations successfully updated'), $this->make_url("user/profile/".$id."/".$tab));
			exit;
		}




		if(isset($_POST['saveuserdatabutton']))
		{
			if(DEMO_MODE)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url("user/profile/".$id."/".$tab),0);
				exit;
			}

			$logo_display=$this->read_post_param('logo_display');
			$adv_ref_profit_percentage=$this->read_post_param('adv_ref_profit_percentage');
			$pub_ref_profit_percentage=$this->read_post_param('pub_ref_profit_percentage');


			$referral_enabled=$this->get_addon_status('referral_enabled');


			$string_update="logo_display=?";

			$string_array[]=$logo_display;


			if($referral_enabled ==1 || $referral_enabled ==0)
			{
				$string_update.=",adv_ref_profit_percentage=?,pub_ref_profit_percentage=?";

				$string_array[]=$adv_ref_profit_percentage;
				$string_array[]=$pub_ref_profit_percentage;
			}

			$string_array[]=$id;


			$data=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET ".$string_update." WHERE id=?",$string_array);


			$this->flash($this->get_message('user configurations successfully updated'), $this->make_url("user/profile/".$id."/".$tab));
			exit;
		}



		$user_row=$db->execute_query("SELECT adv_status,pub_status FROM ".TABLE_PREFIX."users WHERE id=?",array($id));

		$user_data=$user_row->fetch_assoc();

		$adv_status=$user_data['adv_status'];
		$pub_status=$user_data['pub_status'];


		if($ut != "a" && $ut !="p" && $ut !="o")
		{
			if($adv_status !=-2 && $adv_status !=-3)
			$ut="a";
			else if($pub_status !=-2 && $pub_status !=-3)
			$ut="p";
		}
		else if($ut=="a" && ($adv_status ==-2 || $adv_status ==-3) && ($pub_status !=-2 && $pub_status !=-3))
		$ut="p";
		else if($ut=="p" && ($pub_status ==-2 || $pub_status ==-3) && ($adv_status !=-2 && $adv_status !=-3))
		$ut="a";
		else if($ut=="o" && (($pub_status ==-2 || $pub_status ==-3) && ($adv_status ==-2 || $adv_status ==-3)))
		$ut="";

		if($ut =="a" && $tab ==2)//Ad Statistics Tab
		{
			$dur=$this->read_page_param(4);
			$tp=$this->read_page_param(5);
			$st=$this->read_page_param(6);
			$adpricing=$this->read_page_param(7);
			$premiumStatus=trim($this->read_page_param(8));
			$pg=$this->read_page_param(9);

			if($premiumStatus === "")
			$premiumStatus = 2;
		}
		else
		{
			$dur=1;
			$tp=0;
			$st=2;
			$adpricing=-1;
			$premiumStatus = 2;
			$pg="";
		}

		$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

		if($premium_ad_enabled == 0)
		$premiumStatus=2;


		if($ut =="o" && $tab ==8)//Payment History Tab
		{
			$pt=$this->read_page_param(4);
			$pts=$this->read_page_param(5);
			$ppg=$this->read_page_param(6);
		}
		else
		{
			$pt=0;
			$pts=4;
			$ppg="";
		}

		if($ut =="o" && $tab ==9)//Withdrawal History Tab
		{
			$wt=$this->read_page_param(4);
			$wts=$this->read_page_param(5);
			$wty=$this->read_page_param(6);
			$wpg=$this->read_page_param(7);
		}
		else
		{
			$wt=0;
			$wts=4;
			$wty=2;
			$wpg="";
		}

		if($dur=="")
		$dur=1;

		if($tp=="")
		$tp=0;

		if($st=="")
		$st=2;

		if($adpricing =="")
		$adpricing=-1;

		if($pt=="")
		$pt=0;

		if($pts=="")
		$pts=4;

		if($wt=="")
		$wt=0;

		if($wts=="")
		$wts=4;

        if($wty=="")
		$wty=2;

		if($pg=="")
		$pg="page-1";


		if($ppg=="")
		$ppg="page-1";

		if($wpg=="")
		$wpg="page-1";

		$this->set_variable("ut",$ut);
		$this->set_variable("dur",$dur);
		$this->set_variable("tp",$tp);
		$this->set_variable("st",$st);
		$this->set_variable("pt",$pt);
		$this->set_variable("pts",$pts);
		$this->set_variable("wt",$wt);
		$this->set_variable("wts",$wts);
		$this->set_variable("wty",$wty);

		$this->set_variable("pg",$pg);
		$this->set_variable("ppg",$ppg);
		$this->set_variable("wpg",$wpg);
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("premiumStatus",$premiumStatus);


		$res         = $db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($id));
		$this->set_result("res",$res);

		$site_json   = $db->read_single_column("SELECT restricted_sites FROM ".TABLE_PREFIX."users where id=?",array($id));

		$sites_array = json_decode($site_json);

		if(is_array($sites_array))
		$rest_num = count($sites_array);
	    else
	    $rest_num = 0;

		if($rest_num > 0)
		$this->set_array("sites", $sites_array,array(),1);
		$this->set_variable("rest_num",$rest_num);

		$this->set_variable("tab",$tab);











		$referral_enabled=$this->get_addon_status('referral_enabled');
		$this->set_variable('referral_enabled',$referral_enabled);

		if($subadmin_enabled ==1)
		{
			$res1 = $db->execute_query("SELECT id,username FROM ".TABLE_PREFIX."admin WHERE type = 2 AND status=1");
			$this->set_result('adv_res', $res1);

			$res2 = $db->execute_query("SELECT id,username FROM ".TABLE_PREFIX."admin where type = 3 AND status=1");
			$this->set_result('pub_res', $res2);
		}
}


function advpayment_action()
{
	$db= DAL::get_instance();
	$this->disable_notice_area();



	if($_POST)
	{
		$payment=intval($this->read_post_param('payment'));
		$status=intval($this->read_post_param('status'));
		$uid=intval($this->read_post_param('uid'));
	}
	else
	{
		$uid=$this->read_page_param(1);
		$payment=$this->read_page_param(2);
		$status=$this->read_page_param(3);
	}


	if($status === "")
	$status=4;


	if($payment === "")
	$payment=0;

	$adv_status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));





	if($status==4)
	$status_str="";
	else
	$status_str=" and status=".intval($status)." ";




	if($payment==4)
	$status_str="";


	if($payment==0)
	$payment_str = "";
	else
	$payment_str = " and payment_type=".intval($payment)." ";



			$pagination1 = new Pagination("select * from ".TABLE_PREFIX."advertiser_payment_summary where uid=? ".$payment_str.$status_str." ORDER BY id desc",array($uid));
			$res2=$pagination1->get_result();
			$this->set_result("res2",$res2);
			$this->set_variable("pagination1",$pagination1->links(),0);

			$pg=$pagination1->get_page_number();
			$this->set_variable("pg","page-".$pg);


	$this->set_variable("uid",$uid);
	$this->set_variable("adv_status",$adv_status);
	$this->set_variable('payment', $payment);
	$this->set_variable('status', $status);


}

function advtimestat_action()
{
	$this->disable_notice_area();
	$uid=$this->read_page_param(1);


	if($_POST)
	{
		$duration=$this->read_post_param("duration");
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$duration=1;
		$from_date='';
		$to_date='';
	}



	if($from_date != "" && $to_date == "")
		$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

	if($duration == 7 && ($from_date == "" || $to_date == ""))
	{
		$duration  = 1;

		$from_date = "";
		$to_date   = "";
	}

	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	if($duration=="" || $duration==0)
	$duration=1;

	$timePeriod[] = $duration;

	if($duration == 7)
	{
		$timePeriod[] = $from_date;
		$timePeriod[] = $to_date;
	}

	$dbResultTimeperiod = $this->get_advertiser_timeperiod_statistics($timePeriod, 1, -1, $uid);
	$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);

	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$uid);

}

function advadstat_action()
{
	$this->disable_notice_area();
	$db = DAL::get_instance();
	$id = $this->read_page_param(1);

	if($_POST)
	{
		$adtype 	= intval($this->read_post_param('type'));
		$status 	= intval($this->read_post_param('status'));
		$duration 	= intval($this->read_post_param("duration"));
		$adpricing 	= intval($this->read_post_param("adpricing"));
		$from_date 	= $this->read_post_param("from_date");
		$to_date 	= $this->read_post_param("to_date");

		$premiumStatus = intval($this->read_post_param('premiumStatus'));
	}
	else
	{
	    $duration 	= intval($this->read_page_param(2));
	    $adtype 	= $this->read_page_param(3);
	    $status 	= $this->read_page_param(4);
	    $adpricing 	= $this->read_page_param(5);
		$premiumStatus=trim($this->read_page_param(6));

		if($premiumStatus === "")
		$premiumStatus = 2;

		$premiumStatus = intval($premiumStatus);

	    $from_date 	= '';
	    $to_date 	= '';
	}

	$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

	if($premium_ad_enabled == 0)
	$premiumStatus=2;


	if($from_date != "" && $to_date == "")
	$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

	if($duration == 7 && ($from_date == "" || $to_date == ""))
	{
		$duration  = 1;

		$from_date = "";
		$to_date   = "";
	}


	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);

	$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
	$this->set_variable('text_ads_enabled',$text_ads_enabled);

	$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
	$this->set_variable('ecommerce_enabled',$ecommerce_enabled);

	$cpv_enabled=$this->get_addon_status('video-ads_enabled');

	if($duration == 0)
	$duration = 1;

	$timePeriod[] = $duration;

	if($duration == 7)
	{
		$timePeriod[] = $from_date;
		$timePeriod[] = $to_date;
	}

	if($adpricing === "")
	$adpricing=-1;

	if($adtype === "")
	$adtype=0;

	if($status === "")
	$status=2;

	if($adtype ==0)
	$adtype_str="";
	else
	$adtype_str=" and a.type=".intval($adtype)." ";


	if($text_ads_enabled !=1)
	$adtype_str.=' AND a.type <>1 ';

	if($ecommerce_enabled !=1)
	$adtype_str.=' AND a.type <>7 ';

	if($this->get_addon_status('pop-ads_enabled') !=1)
	$adtype_str.=' AND a.type <>9 ';

	if($this->get_addon_status('direct-link-ads_enabled') !=1)
	$adtype_str.=' AND a.type <>21 ';
	
	if($this->get_addon_status('text-image-ads_enabled') !=1)
	$adtype_str.=' AND a.type <>11 ';

	if($this->get_addon_status('affiliate-ads_enabled') !=1)
	$adtype_str.=' AND a.type <>12 ';

	if($cpv_enabled !=1)
	$adtype_str.=' AND a.type <>13 ';

	if($this->get_addon_status('skin-ads_enabled') !=1)
	$adtype_str.=' AND a.type <>14 ';


	if($status == 2)
	$status_str = " AND a.status <> -2 ";
	else
	$status_str = " AND a.status=".intval($status)." ";


	if($adpricing ==-1)
	$adpricing_str = "";
	else
	$adpricing_str = " and a.display_type=".intval($adpricing)." ";


	if($this->get_addon_status('cpc_enabled') !=1)
	$adpricing_str.=' AND a.display_type <>0 ';

	if($this->get_addon_status('cpm_enabled') !=1)
	$adpricing_str.=' AND a.display_type <>1 ';

	if($this->get_addon_status('cpa_enabled') !=1)
	$adpricing_str.=' AND a.display_type <>6 ';

	if($this->get_addon_status('sponsored_enabled') !=1)
	$adpricing_str.=' AND a.display_type <>3 ';


	$this->set_variable("type",$adtype);
	$this->set_variable("status",$status);
	$this->set_variable("duration",$duration);
	$this->set_variable("adpricing",$adpricing);
	$this->set_variable("premiumStatus",$premiumStatus);


	$premiumString = "";

	if($premiumStatus == 1 || $premiumStatus == 0)
	$premiumString = " AND a.premium_ad = ".$premiumStatus." ";

	$pagination = new Pagination("SELECT id,uid,type,name,title,description,display_url,banner,status,pause_status,display_type,retargeting,html5,ecommerce_parent,mime_type,pricing_status,premium_ad FROM ".TABLE_PREFIX."ads a where uid = ? AND ecommerce_parent = 0 ".$adtype_str.$status_str.$adpricing_str.$premiumString." ORDER BY id DESC",array($id));
	$res11 = $pagination->get_result();
	$this->set_variable("pagination",$pagination->links(),0);

	$pg = $pagination->get_page_number();
	$this->set_variable("pg","page-".$pg);

	$adResult = array();

	while($resultData = $res11->fetch_assoc())
	{
		$parentID         = $resultData['id'];
		$adCurrentPricing = $resultData['display_type'];
		$ecommerceArray   = array();

		if($resultData['type'] == 7)
		$ecommerceArray = $this->get_ecommerce_row_list($resultData['id']);

		$ecommerceCount = count($ecommerceArray);

		if($ecommerceCount == 0)
		{
			$ecommerceCount   = 1;
			$ecommerceArray[] = 0;
		}

		foreach($ecommerceArray as $gkey => $gvalue)
		{
			if($resultData['type'] == 7)
			$aidvalue = $gvalue[0];
			else
			$aidvalue = $parentID;

			//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $country = ''
			$dbResult                 = $this->get_advertiser_statistics($timePeriod, 1, $adpricing, $id, $aidvalue);

			$resultData['id']         = $aidvalue; //Parent ad id replace with child id's in case of ecommerce ads
			$resultData['pricing']    = $this->get_ad_pricing($parentID,$resultData['display_type']);
			$resultData['adtype']     = $this->get_ad_type($resultData['type'],0,1);


			if($resultData['type'] == 7) //E-commerce
			{
				$resultData['status']     = $gvalue[1];
				$resultData['dimension']  = $gvalue[2];
				$resultData['premium_ad'] = $gvalue[5];
			}
			else
			{
				$resultData['status']     = $this->get_ad_status($resultData['status']);
				$resultData['dimension']  = "";
			}



			$pricingKey = "";

			if($adCurrentPricing == 0)
			$pricingKey = "cpc";
			else if($adCurrentPricing == 1)
			$pricingKey = "cpm";
			else if($adCurrentPricing == 3)
			$pricingKey = "cpd";
			else if($adCurrentPricing == 6)
			$pricingKey = "cpa";
			else if($adCurrentPricing == 18)
			$pricingKey = "cpp";


			if(isset($dbResult[$pricingKey]))
			$resultData['reportData'] = $dbResult[$pricingKey];
			else
			$resultData['reportData'] = array();

			$adResult[$parentID][$aidvalue] = $resultData;
		}
	}


	if(count($adResult) > 0)
	$adResult['heading'] = $dbResult['heading'];

	$this->set_array("adResult",$adResult);

	$this->set_variable("uid",$id);
}

function advall_action()
{
	$this->disable_notice_area();
	$id=$this->read_page_param(1);


	if($_POST)
	{
		$duration  = intval($this->read_post_param("duration"));
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$duration        = 1;
		$from_date='';
		$to_date='';
	}

	if($from_date != "" && $to_date == "")
	$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

	if($duration == 7 && ($from_date == "" || $to_date == ""))
	{
		$duration  = 1;

		$from_date = "";
		$to_date   = "";
	}

	if($duration=="" || $duration==0)
	$duration=1;

	$timePeriod[] = $duration;

	if($duration == 7)
	{
		$timePeriod[] = $from_date;
		$timePeriod[] = $to_date;
	}

	//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $country = ''
	$dbResult = $this->get_advertiser_statistics($timePeriod, 1, -1, $id);
	$this->set_array("reportResult",$dbResult);

	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);



	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$id);

}

function pubtimestat_action()
{
	$this->disable_notice_area();
	$uid=$this->read_page_param(1);




	if($_POST)
	{
		$duration=$this->read_post_param("duration");
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$duration=1;
		$from_date='';
		$to_date='';
	}

	if($from_date != "" && $to_date == "")
	$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

	if($duration == 7 && ($from_date == "" || $to_date == ""))
	{
		$duration  = 1;

		$from_date = "";
		$to_date   = "";
	}

	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);

	if($duration=="" || $duration==0)
	$duration=1;

	$timePeriod[] = $duration;

	if($duration == 7)
	{
		$timePeriod[] = $from_date;
		$timePeriod[] = $to_date;
	}

	$dbResultTimeperiod= $this->get_publisher_timeperiod_statistics($timePeriod, 1, -1, $uid);
	$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);

	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$uid);

}

function puball_action()
{
	$this->disable_notice_area();
	$id=$this->read_page_param(1);


	if($_POST)
	{
		$duration=intval($this->read_post_param("duration"));
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$duration=1;
		$from_date='';
		$to_date='';
	}

	if($from_date != "" && $to_date == "")
	$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

	if($duration == 7 && ($from_date == "" || $to_date == ""))
	{
		$duration  = 1;

		$from_date = "";
		$to_date   = "";
	}


	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);

	if($duration=="" || $duration==0)
	$duration=1;

	$timePeriod[] = $duration;

	if($duration == 7)
	{
		$timePeriod[] = $from_date;
		$timePeriod[] = $to_date;
	}

	//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $country = ''
	$dbResult = $this->get_publisher_statistics($timePeriod, 1, -1, $id);
	$this->set_array("reportResult",$dbResult);


	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$id);

}

function pubwithdrawal_action()
{
	$this->disable_notice_area();
	$db= DAL::get_instance();


	if($_POST)
	{
		$payment=intval($this->read_post_param('payment'));
		$status=intval($this->read_post_param('status'));
		$uid=intval($this->read_post_param('uid'));
		$payment_type=intval($this->read_post_param("payment_type"));
	}
	else
	{
		$uid=$this->read_page_param(1);
		$payment=$this->read_page_param(2);
		$status=$this->read_page_param(3);
		$payment_type=$this->read_page_param(4);


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
	$this->set_variable('referral_enabled', $referral_enabled);


	if($status === "")
	$status=4;


	if($payment === "")
	$payment=0;

	if($referral_enabled ==1 && ($payment_type =="" || $payment_type ==2))
	$payment_type=2;
	else if($referral_enabled !=1 && ($payment_type =="" || $payment_type ==2))
	$payment_type=0;


	if($status == 4)
	$status_str = "";
	else
	$status_str=" and status=".intval($status)." ";





	if($payment == 0)
	$payment_str="";
	else


	$payment_str=" and payment_mode=".intval($payment)." ";


	$type_str="";

	if($payment_type !=2)
	$type_str=" AND withdrawal_type=".intval($payment_type)." ";



	$pub_status=$db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));


	$pagination2 = new Pagination("SELECT * FROM ".TABLE_PREFIX."publisher_withdrawal_summary where uid=? ".$payment_str.$status_str.$type_str." ORDER BY id desc",array($uid));
	$res3=$pagination2->get_result();
	$this->set_result("res3",$res3);
	$this->set_variable("pagination2",$pagination2->links(),0);

	$pg=$pagination2->get_page_number();
	$this->set_variable("pg","page-".$pg);


	$this->set_variable("uid",$uid);
	$this->set_variable("pub_status",$pub_status);
	$this->set_variable('payment', $payment);
	$this->set_variable('payment_type', $payment_type);
	$this->set_variable('status', $status);

}

function pubadunitstat_action()
{
	$this->disable_notice_area();
	$db= DAL::get_instance();
	$id=$this->read_page_param(1);

	if($_POST)
	{
		$duration  = intval($this->read_post_param("duration"));
		$sid       = intval($this->read_post_param("sid"));
		$adpricing = intval($this->read_post_param("adpricing"));
		$from_date = $this->read_post_param("from_date");
		$to_date   = $this->read_post_param("to_date");
	}
	else
	{
		$duration  = 1;
		$sid       = 0;
		$adpricing = -1;
		$from_date = '';
		$to_date   = '';
	}

	if($from_date != "" && $to_date == "")
	$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

	if($duration == 7 && ($from_date == "" || $to_date == ""))
	{
		$duration  = 1;

		$from_date = "";
		$to_date   = "";
	}

	if($duration == 0)
	$duration = 1;

    $timePeriod[] = $duration;

	if($duration == 7)
	{
		$timePeriod[] = $from_date;
		$timePeriod[] = $to_date;
	}

	$text_ads_enabled     = Configuration::get_instance()->read('text-ads_enabled');
	$inpage_push_enabled  = $this->get_addon_status('inpage-push-ads_enabled');

	$cpc_enabled          = $this->get_addon_status('cpc_enabled');
	$cpm_enabled          = $this->get_addon_status('cpm_enabled');
	$html_enabled         = $this->get_addon_status('html_enabled');
	$interstitial_enabled = $this->get_addon_status('interstitial_enabled');
	$cpa_enabled       	  = $this->get_addon_status('cpa_enabled');
	$sponsored_enabled 	  = $this->get_addon_status('sponsored_enabled');
	$pop_enabled          = $this->get_addon_status('pop-ads_enabled');
	$affiliate_enabled    = $this->get_addon_status('affiliate-ads_enabled');
	$textimage_enabled    = $this->get_addon_status('text-image-ads_enabled');
	$cpv_enabled          = $this->get_addon_status('video-ads_enabled');
	$skin_enabled         = $this->get_addon_status('skin-ads_enabled');
	$category_targeting_enabled = $this->get_addon_status('category-targeting_enabled');
	$directlink_enabled   = $this->get_addon_status('direct-link-ads_enabled');
	$feedads_enabled      = $this->get_addon_status('feed-ads_enabled');
	$native_enabled       = $this->get_addon_status('native-ad-display_enabled');

	if($cpm_enabled == 1 || $html_enabled == 1)
	$cpm_enabled = 1;

	if($feedads_enabled == 1)
	$feedads_enabled    = intval(Configuration::get_instance()->read('supply_feed_ads'));

	if($feedads_enabled == 1)
	$feedads_enabled    = intval($db->read_single_column("SELECT supply_feed_ads FROM ".TABLE_PREFIX."users WHERE id=?",array($id)));

	$directlink_availability = 0;

	if($directlink_enabled == 1)
	{
		$directlink_availability_user    = intval($db->read_single_column("SELECT directlink_availability FROM ".TABLE_PREFIX."users WHERE id=?",array($id)));	
		$directlink_availability_config  = Configuration::get_instance()->read('directlink_availability_for_publishers');

		if(($directlink_availability_config == 1 && $directlink_availability_user == 2) || $directlink_availability_user == 1)
		$directlink_availability = 1;
	}

	if($adpricing =='')
	$adpricing=-1;

	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	$this->set_variable("adpricing",$adpricing);


	$sidquery='';
	if($category_targeting_enabled == 1)
	{
		if($sid >0)
		$sidquery=' AND sid='.$sid.' ';
	}

	$adpricing_str = "";
	$adtype_str    = "";
	$native_str    = "";

	if($adpricing > -1)
	{
		if($adpricing == 0 || $adpricing == 1 || $adpricing == 6)
		$adpricing_str = " and (a.display_type = ".intval($adpricing)." OR a.display_type = 4) ";
		else
		$adpricing_str = " and a.display_type = ".intval($adpricing)." ";
	}

	if($cpc_enabled !=1)
	$adpricing_str.=' AND display_type <>0 ';

	if($cpm_enabled !=1)
	$adpricing_str.=' AND display_type <>1 ';

	if($sponsored_enabled !=1)
	$adpricing_str.=' AND display_type <>3 ';

	if($cpc_enabled !=1 && $cpm_enabled !=1 && $cpa_enabled !=1)
	$adpricing_str.=' AND display_type <>4 ';

	if($cpa_enabled !=1)
	$adpricing_str.=' AND display_type <>6 ';


	if($interstitial_enabled !=1)
	$adtype_str.=' a.ad_type <> 5 ';

	if($text_ads_enabled !=1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.ad_type <>1 ';
	}

	if($textimage_enabled !=1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.ad_type <> 11 ';
	}
	if($cpv_enabled !=1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';
		$adtype_str.=' a.ad_type <> 13 ';
	}

	if($skin_enabled !=1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.adcode_type <> 14 ';
	}

	if($inpage_push_enabled !=1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.adcode_type <>19 ';
	}
		
	if($pop_enabled != 1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.adcode_type <>9 ';			
	}

	if($feedads_enabled !=1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.adcode_type <>17 ';
	}

	if($affiliate_enabled !=1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.adcode_type <>12 ';
	}

	if($directlink_availability != 1)
	{
		if($adtype_str !="")
		$adtype_str.=' AND ';

		$adtype_str.=' a.adcode_type <>21 ';			
	}

	if($native_enabled != 1)
	$native_str =' AND a.native <>1 ';	

	if($adtype_str !="")
	$adtype_str = ' AND (('.$adtype_str.') OR ab.banner_type IS NULL) ';

	$pagination3 = new Pagination("select a.*,ab.name as abname,ab.type,ab.banner_type,ab.width,ab.height from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE a.pubid=? ".$adpricing_str." ".$sidquery." ".$adtype_str." ".$native_str." ORDER BY a.id desc",array($id));
	$res4=$pagination3->get_result();
	$this->set_variable("pagination3",$pagination3->links(),0);

	$adResult = array();

	while($adcodeData = $res4->fetch_assoc())
	{
		$pubid    = $adcodeData['pubid'];
		$adcodeid = $adcodeData['id'];
		$dbResult = $this->get_publisher_statistics($timePeriod, 1, $adpricing , $pubid, $adcodeid);

			if($category_targeting_enabled == 1 && $adcodeData['sid'] > 0)
		$adcodeData['siteName']   = CategoryHelper::get_site_name($adcodeData['sid']);
		else
		$adcodeData['siteName']   = $this->get_label('na');

		$adcodeData['reportData'] = $dbResult;

		$adResult[$adcodeid]=$adcodeData;
	}
	if(count($adResult) > 0)
	$adResult['heading'] = $dbResult['heading'];

	$this->set_array("adResult",$adResult);


	$this->set_variable("duration",$duration);
	$this->set_variable("sid",$sid);
	$this->set_variable("uid",$id);
}

	function change_status_action()
	{
		$this->set_title($this->get_label('change user status'));
		$uid=$this->read_page_param(1);


		if(DEMO_MODE && $uid <=2)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('user/list'),0);
			exit;
		}


		$frompg=$this->read_page_param(2);

		$status=$this->read_page_param(3);
		$type=$this->read_page_param(4);

		$search_by=$this->read_page_param(5);
		$pg=$this->read_page_param(6);

		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
		$pg="";

		if($pg=="")
		$pg="page-1";

		if($type=="")
		$type=1;

		if($status=="")
		$status=2;

		$this->set_variable("pg",$pg);
		$this->set_variable("type",$type);
		$this->set_variable("status",$status);
		$this->set_variable("search_by",$search_by);

		if($uid=="")
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
		}




		$db= DAL::get_instance();

		$res=$db->execute_query("select adv_status,pub_status from ".TABLE_PREFIX."users where id=?",array($uid));
		$this->set_result("res",$res);


		if($_POST)
		{
			$statusadv=$this->read_post_param('statusadv');
			$statuspub=$this->read_post_param('statuspub');


			if($search_by==3)
			    $value=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));
			else if($search_by==2)
			    $value=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
			else if($search_by==1)
			    $value=$uid;
			else
			    $value='';

			if($statusadv==-4 && $statuspub==-4)
			{
			if($frompg ==1)
			    header("Location: ".$this->make_url("user/list/".$status."/".$type."/".$search_by."/".$value."/".$pg));
			else
			header("Location: ".$this->make_url("user/profile/".$uid."/0"));

			}
			else
			    header("Location: ".$this->make_url("user/status_mail/").$uid."/".$statusadv."/".$statuspub."/".$frompg."/".$status."/".$type."/".$search_by."/".$pg);

		}



		$usname=$this->get_user_name($uid);

		$this->set_variable('usname', $usname);


		$this->set_variable("uid",$uid);
		$this->set_variable("frompg",$frompg);



	}



	function status_mail_action()
	{
		$this->set_title($this->get_label('change user status'));

		if($_POST)
		$uid = $this->read_post_param('uid');
		else
		$uid = $this->read_page_param(1);

		if(!$this->get_user_exists($uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('index/control_panel'),0);
			exit;
		}


		$refferal_enabled=$this->get_addon_status('referral_enabled');

		if(DEMO_MODE && $uid <=2)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('user/list'),0);
			exit;
		}


		$statusadv=$this->read_page_param(2);
		$statuspub=$this->read_page_param(3);
		$frompg=$this->read_page_param(4);



		$status=$this->read_page_param(5);
		$type=$this->read_page_param(6);
		$search_by=$this->read_page_param(7);
		$pg=$this->read_page_param(8);

		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
		$pg="";

		if($pg=="")
		$pg="page-1";

		if($type=="")
		$type=1;

		if($status=="")
		$status=2;

		$this->set_variable("pg",$pg);
		$this->set_variable("type",$type);
		$this->set_variable("status",$status);
		$this->set_variable("search_by",$search_by);



		$db= DAL::get_instance();


		$id=4;

		$yes_no=0;
		if($_POST)
		{


			$subject=$this->read_post_param('subject');
			$message=$this->read_post_param('message');




			$statusadv=$this->read_post_param('statusadv');
			$statuspub=$this->read_post_param('statuspub');
			$frompg=$this->read_post_param('frompg');



			if($search_by==3)
		    $value=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));
			else if($search_by==2)
		    $value=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
			else if($search_by==1)
		    $value=$uid;
			else
		    $value='';


			$advertiserDefaultStatus = Configuration::get_instance()->read('adv_default_status');
			$publisherDefaultStatus  = Configuration::get_instance()->read('pub_default_status');


			if(isset($_POST['yes_no']))
			$yes_no=1;

			if($statusadv == -4 && $statuspub == -4)
			{
				if($frompg ==1)
				header("Location: ".$this->make_url("user/list/".$status."/".$type."/".$search_by."/".$value."/".$pg));
				else
				header("Location: ".$this->make_url("user/profile/".$uid."/0"));

			}


			$userDB     = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."users where id=?",array($uid));
			$userDBData = $userDB->fetch_assoc();

			$old_adv_status = $userDBData['adv_status'];
			$old_pub_status = $userDBData['pub_status'];



			if($statusadv != -4)
			{
				$bonus_string="";
				if($old_adv_status==-1 || $old_adv_status==-3)
				{

					$bonus_seperately_track=Configuration::get_instance()->read('bonus_seperately_track');




					$adv_bonus=Configuration::get_instance()->read('advertiser_bonus');
					if($adv_bonus < 0 || $adv_bonus=="")
					$adv_bonus=0;


					if($adv_bonus >0 && $statusadv==1)
					{
						if($bonus_seperately_track ==0)
						$bonus_string=" ,adv_account_balance=(adv_account_balance+".$adv_bonus.") ";
						else if($bonus_seperately_track ==1)
						$bonus_string=" ,adv_bonus_balance=(adv_bonus_balance+".$adv_bonus.") ";
					}
					else
						$bonus_string="";

				}
				else
					$bonus_string="";

              ///////////////////////


			$res_query=$db->execute_query("update ".TABLE_PREFIX."users set adv_status=? ".$bonus_string." where id=?",array($statusadv,$uid));
			if($refferal_enabled == 1)
			{
				if($statusadv ==1 || $statuspub==1)
				{
					$res_query1=$db->execute_query("update ".TABLE_PREFIX."users set refferal_status=? where rid=?",array(1,$uid));
					$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET refferal_status = ? WHERE refferal_id=?",array(1,$uid));

				}
				else if($statusadv !=1 && $statuspub!=1)
				{
					$res_query1=$db->execute_query("update ".TABLE_PREFIX."users set refferal_status=? where rid=?",array(0,$uid));
					$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET refferal_status = ? WHERE refferal_id=?",array(0,$uid));
					$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET refferal_status = b'?' WHERE refferal_id=?",array(0,$uid));

				}

			}
			/********* code to update user_status field in ads table when activate/block a user -Start   ********/

			$ad_user=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."ads WHERE uid=?",array($uid));
			if($ad_user->num_records > 0)
			{
				while($row=$ad_user->fetch_assoc())
				{

					$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET user_status = ? WHERE id=?",array($statusadv,$row['id']));
					$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET user_status = b'?' WHERE id=?",array($statusadv,$row['id']));

				}
			}

			/********* code to update user_status field in ads table when activate/block a user -End   ********/


			///////////////////////

			if($adv_bonus >0 && ($old_adv_status==-1 || $old_adv_status==-3))
			{

				$time1_bonus=time();
				$value_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status,manual_entry) values(?,?,?,?,?,?)",array($uid,4,$adv_bonus,$time1_bonus,1,1));
				$id_bonus=$value_bonus->last_id;

				$value1_bonus=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_bonus_details (paymentid,comment) values(?,?)",array($id_bonus,"Advertiser SignUp Bonus"));
			}
		}






		if($statuspub != -4)
		$res_query1=$db->execute_query("update ".TABLE_PREFIX."users set pub_status=? where id=?",array($statuspub,$uid));



		$advOperationStatus = -1;
		$pubOperationStatus = -1;

		if($old_adv_status == -3 && $statusadv == -4)
		{
			if($advertiserDefaultStatus == 1)
			{
				if($statuspub == 1 || $statuspub == 0)
				$advOperationStatus = $statuspub;
			}
			else
			$advOperationStatus = $advertiserDefaultStatus;


			$db->execute_query("update ".TABLE_PREFIX."users set adv_status=? where id=?",array($advOperationStatus,$uid));
		}


		if($old_pub_status == -3 && $statuspub == -4)
		{
			if($publisherDefaultStatus == 1)
			{
				if($statusadv == 1 || $statusadv == 0)
				$pubOperationStatus = $statusadv;
			}
			else
			$pubOperationStatus = $publisherDefaultStatus;

			$db->execute_query("update ".TABLE_PREFIX."users set pub_status=? where id=?",array($pubOperationStatus,$uid));
		}




		// newsletter addon support///



		$newsletteraddon_folder = $this->xyz_get_addon_folder_name("XYZADMNLR");
		$newsletter_enable      = $this->get_addon_status($newsletteraddon_folder.'_enabled');

		if($newsletter_enable == 1)
		{
			$existing_email = $db->execute_query("select email,adv_status,pub_status,f_name from ".TABLE_PREFIX."users where id = ?",array($uid));

			if($existing_email->get_num_records() > 0)
			{
				$row = $existing_email->fetch_assoc();

				if($old_adv_status == -3 || $old_pub_status == -3)
				{
					$username_field = $db->read_single_column("select id from ".TABLE_PREFIX."additional_field_info where field_name=?",array('Name'));

					$this->create_email_and_mapping($row['email'],$row['pub_status'],$row['adv_status'],$username_field,$row['f_name']);
				}
				else
				$this->email_mapping_update($row['email'],$row['pub_status'],$row['adv_status']);
			}
		}



		/////////////////////////////////////////////////

			$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

			if(($subject=="" || $message=="") && $yes_no==0)
			{
				$this->set_notice('mandatory');
			}
			else
			{

				if($res_query->error=="" && $res_query1->error=="")
				{


				if($yes_no==0)
				UtilityHelper::send_mail($email,$subject,$message);



				if($frompg ==1)
				    $this->flash($this->get_message('user status updated'), $this->make_url('user/list/'.$status.'/'.$type."/".$search_by."/".$value.'/'.$pg));
				else
				$this->flash($this->get_message('user status updated'), $this->make_url("user/profile/".$uid."/0"));

				}
				else
				{
					if($frompg ==1)
					    $this->flash($this->get_message('error occured'), $this->make_url('user/list/'.$status.'/'.$type."/".$search_by."/".$value.'/'.$pg),0);
					else
					$this->flash($this->get_message('error occured'), $this->make_url("user/profile/".$uid."/0"),0);

				}

			}

		}
		else
		{

			if($statusadv==-4 && $statuspub==-4)
			{
				if($frompg ==1)
				    header("Location: ".$this->make_url("user/list/".$status."/".$type."/".$pg."/".$search_by."/".$value));
				else
				header("Location: ".$this->make_url("user/profile/".$uid."/0"));

			}



			$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));


			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
			$detail=$res->fetch_assoc();

			$language_enabled=Configuration::get_instance()->read('language_enabled');
			$localeid=$this->get_user_locale($uid);

			if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_message']) && $detail[$localeid.'_message'] !='')
			$message=$detail[$localeid.'_message'];
			else
			$message=$detail['message'];


			if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_subject']) && $detail[$localeid.'_subject'] !='')
			$subject=$detail[$localeid.'_subject'];
			else
			$subject=$detail['subject'];




			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

			$message=str_replace("{USERNAME}",$username,$message);


			if($statusadv==-4)
			{
			$db_adv_status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));
			$message=str_replace("{ADVSTATUS}",$this->get_user_status($db_adv_status),$message);
			}
			else
			{
			$message=str_replace("{ADVSTATUS}",$this->get_user_status($statusadv),$message);
			}

			if($statuspub==-4)
			{
			$db_pub_status=$db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));
			$message=str_replace("{PUBSTATUS}",$this->get_user_status($db_pub_status),$message);
			}
			else
			{
			$message=str_replace("{PUBSTATUS}",$this->get_user_status($statuspub),$message);
			}

		}

		$this->set_variable("uid",$uid);
		$this->set_variable('statusadv', $statusadv);
		$this->set_variable('statuspub', $statuspub);
		$this->set_variable('frompg', $frompg);


		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);



		$this->set_variable('yes_no', $yes_no);


	}



	function delete_action()
	{
		$this->set_title($this->get_label('send email'));


		$uid=$this->read_page_param(1);


		if(DEMO_MODE && $uid <=2)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('user/list'),0);
			exit;
		}



		$status=$this->read_page_param(2);
		$type=$this->read_page_param(3);
		$search_by=$this->read_page_param(4);
		$pg=$this->read_page_param(5);

		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
			$pg="";

		if($pg=="")
			$pg="page-1";

		if($type=="")
			$type=1;

		if($status=="")
			$status=2;

		$this->set_variable("pg",$pg);
		$this->set_variable("type",$type);
		$this->set_variable("status",$status);
		$this->set_variable("search_by",$search_by);

		$db= DAL::get_instance();
		$id=6;

		$yes_no=0;
		if($_POST)
		{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');
				$uid=$this->read_post_param('uid');




				if(isset($_POST['yes_no']))
				$yes_no=1;



				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

				if(($subject=="" || $message=="") && $yes_no==0)
				{
					$this->set_notice('mandatory');
				}
				else
				{

				if($yes_no==0)
				UtilityHelper::send_mail($email,$subject,$message);



				$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');


				$drow=$db->execute_query("select * from ".TABLE_PREFIX."ads where uid=?",array($uid));
				while($drowdata=$drow->fetch_assoc())
				{
					if($drowdata['type'] ==2 || $drowdata['type'] ==11)
					{
						$bannertype=intval($drowdata['html5']);

						if($bannertype ==0)
						{
							unlink("../".DATA_DIR."/".$drowdata['id']."_".$drowdata['banner']);

							if($drowdata['type'] ==2 && isset($drowdata['expandable']) && $drowdata['expandable'] ==1)
							{
								$expandable_banner=$drowdata['expandable_banner'];

								unlink("../".DATA_DIR.'/'.$drowdata['id'].'_exp_'.$expandable_banner);
							}
						}
						else
						{
							if(is_dir("../".DATA_DIR.'/html5/'.$drowdata['id'].'/'))
							$this->remove_files("../".DATA_DIR.'/html5/'.$drowdata['id'].'/');

							if($drowdata['type'] ==2 && isset($drowdata['expandable']) && $drowdata['expandable'] ==1)
							{
								if(is_dir("../".DATA_DIR.'/html5/'.$drowdata['id'].'-exp/'))
								$this->remove_files("../".DATA_DIR.'/html5/'.$drowdata['id'].'-exp/');
							}
						}



					}
					else if($drowdata['type'] ==13)
					{
						unlink("../".DATA_DIR."/video/".$drowdata['id']."/".$drowdata['banner']);

						rmdir("../".DATA_DIR.'/video/'.$drowdata['id'].'/');
					}
					else if($drowdata['type'] ==14)
					{
						$filename_array=json_decode($drowdata['banner'],true);

						foreach($filename_array as $rkey=>$rvalue)
						{
							unlink('../'.DATA_DIR.'/'.$drowdata['id'].'/'.$rvalue);
						}

						rmdir('../'.DATA_DIR.'/'.$drowdata['id'].'/');
					}


					if($drowdata['type'] ==2 || $drowdata['type'] ==11)
					{
						$bannerList = $drowdata['banner_list'];

						$bannerListArray = array();

						if($bannerList != "")
						$bannerListArray = json_decode($bannerList,1);


						foreach($bannerListArray as $bKey=>$bValue)
						{
							if(file_exists('../'.DATA_DIR.'/'.$drowdata['id'].'/'.$bValue))
							unlink('../'.DATA_DIR.'/'.$drowdata['id'].'/'.$bValue);
						}

						rmdir('../'.DATA_DIR.'/'.$drowdata['id'].'/');
					}





					if($drowdata['type'] ==7)
					{
						unlink("../".DATA_DIR."/ecommerce/logo/".$drowdata['logo']);

						$row123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($drowdata['id']));
						while($row1234=$row123->fetch_assoc())
						{
							unlink("../".DATA_DIR.'/ecommerce/'.$drowdata['id'].'/'.$row1234['ad_image']);
						}

						rmdir("../".DATA_DIR.'/ecommerce/'.$drowdata['id'].'/');

						$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($drowdata['id']));
					}



					if(($affiliate_enabled ==1 || $affiliate_enabled ==0) && $drowdata['type'] ==12)
					{
						$imagelist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($drowdata['id']));

						while($imagerow=$imagelist->fetch_assoc())
						{
							unlink("../".DATA_DIR.'/banners/'.$drowdata['id'].'/'.$imagerow['image']);
						}

						rmdir("../".DATA_DIR.'/banners/'.$drowdata['id']);

						$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($drowdata['id']));
					}
				}

				$img_res    = $db->execute_query("SELECT logo,profile_picture FROM ".TABLE_PREFIX."users where id=?",array($uid));
				$img_result = $img_res->fetch_assoc();

				$plogo           = $img_result['logo'];
				$profile_picture = $img_result['profile_picture'];

				if($plogo !='')
				unlink("../".DATA_DIR."/".LOGO_DIR."/".$uid."/".$plogo);


				if($profile_picture !='')
				unlink("../".DATA_DIR."/".PROFILE_PICTURE_DIR."/".$uid."/".$profile_picture);


				rmdir("../".DATA_DIR."/".LOGO_DIR."/".$uid."/");


				// newsletter addon support///

				$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
				$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');

				if($newsletter_enable ==1){



					$existing_email= $db->execute_query("select email,adv_status,pub_status  from ".TABLE_PREFIX."users where id = ?",array($uid));



					if($existing_email->get_num_records() > 0)
					{
						$row=$existing_email->fetch_assoc();

						$id =$db->read_single_column("select id from ".TABLE_PREFIX."email_address where email = ?",array($row['email']));


						$db->execute_query("delete from ".TABLE_PREFIX."additional_field_value where ea_id=?",array($id));
						$db->execute_query("delete from ".TABLE_PREFIX."address_list_mapping where ea_id=?",array($id));
						$db->execute_query("delete from ".TABLE_PREFIX."email_address where id=?",array($id));


					}
				}
				//////////////////////////







				$db->execute_query("delete from ".TABLE_PREFIX."users where id=?",array($uid));
				$db->execute_query("delete from ".TABLE_PREFIX."ads where uid=?",array($uid));
				$db->execute_query("delete from ".TABLE_PREFIX."ads_cache where uid=?",array($uid));
				$db->execute_query("delete from ".TABLE_PREFIX."ad_geographic_mapping where uid=?",array($uid));
				$db->execute_query("delete from ".TABLE_PREFIX."ad_keyword_mapping where uid=?",array($uid));


				$cpm_enabled=$this->get_addon_status('cpm_enabled');
				$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
				$category_enabled=$this->get_addon_status('category-targeting_enabled');
				$device_enabled=$this->get_addon_status('device-targeting_enabled');
				$time_enabled=$this->get_addon_status('time-targeting_enabled');
				$pop_enabled=$this->get_addon_status('pop-ads_enabled');
				$video_enabled=$this->get_addon_status('video-ads_enabled');


				$retargeting_enabled=$this->get_addon_status('retargeting_enabled');

				if($retargeting_enabled ==1 || $retargeting_enabled ==0)
				{
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."retargeting_sites WHERE uid=?",array($uid));
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."retargeting_lists WHERE uid=?",array($uid));
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE uid=?",array($uid));
				}



				$language_enabled=$this->get_addon_status('language-targeting_enabled');


				if($category_enabled ==1 || $category_enabled ==0)
				$db->execute_query("delete from ".TABLE_PREFIX."ad_category_mapping where uid=?",array($uid));

				if($language_enabled ==1 || $language_enabled ==0)
				$db->execute_query("delete from ".TABLE_PREFIX."ad_language_mapping where  uid=?",array($uid));





				$isp_enabled=$this->get_addon_status("isp-connection-targeting_enabled");

				if($device_enabled ==1 || $device_enabled ==0)
				{
					$db->execute_query("delete from ".TABLE_PREFIX."ad_os_mapping where  uid=?",array($uid));
					$db->execute_query("delete from ".TABLE_PREFIX."ad_browser_mapping where uid=?",array($uid));

				}

				if($isp_enabled ==1 || $isp_enabled ==0)
				{
					$db->execute_query("delete from ".TABLE_PREFIX."ad_isp_mapping where uid=?",array($uid));
					$db->execute_query("delete from ".TABLE_PREFIX."ad_connection_mapping where uid=?",array($uid));

				}

				if($category_enabled ==1 || $category_enabled ==0)
				{
					$siteRow = $db->execute_query("select * from ".TABLE_PREFIX."sites where pid=?",array($uid));
					while($siteData = $siteRow->fetch_assoc())
					{
						$siteID    = $siteData['id'];
						$siteLogo  = $siteData['logo'];
						$siteImage = $siteData['thumbshot_image'];


						if($siteLogo !='')
						unlink("../".DATA_DIR."/site_logo/".$siteID."/".$siteLogo);

						if($siteImage !='')
						unlink("../".DATA_DIR."/site_logo/".$siteID."/".$siteImage);
					}

					rmdir("../".DATA_DIR."/site_logo/".$sid."/");

				    $db->execute_query("delete from ".TABLE_PREFIX."sites where pid=?",array($uid));
				}


				$slist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE pubid=?",array($uid));

				$db->execute_query("delete from ".TABLE_PREFIX."adunit where pubid=?",array($uid));


				if($sponsored_enabled ==1 || $sponsored_enabled ==0)
				{
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE uid=? AND (status=-1 OR status=0 OR status=1)",array($uid));
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE publisher=? AND (status=-1 OR status=0 OR status=1)",array($uid));
				}

				$db->execute_query("delete from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));


				$db->execute_query("delete from ".TABLE_PREFIX."testimonial where uid=?",array($uid));


				$pay_pend_row=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."advertiser_payment_summary where uid=? and status=-1",array($uid));

				$pa_str="";
				if($pay_pend_row->get_num_records() >0)
				{
					while($pay_pend_data=$pay_pend_row->fetch_assoc())
					{
						$pa_str.=" paymentid=".intval($pay_pend_data['id']).' or';
					}
				}
				if($pa_str !="")
				{
					$pa_str=$db->sanitize(substr($pa_str,0,-2));
					$db->execute_query("delete from ".TABLE_PREFIX."advertiser_payment_details where ".$pa_str." ");
				}



				$with_pend_row=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."publisher_withdrawal_summary where uid=? and status=-1 and payment_mode <> 5",array($uid));


				$wt_str="";
				if($with_pend_row->get_num_records() >0)
				{
					while($with_pend_data=$with_pend_row->fetch_assoc())
					{
						$wt_str.=" paymentid=".intval($with_pend_data['id']).' or';
					}
				}
				if($wt_str !="")
				{
					$wt_str=$db->sanitize(substr($wt_str,0,-2));
					$db->execute_query("delete from ".TABLE_PREFIX."publisher_withdrawal_details where ".$wt_str." ");
				}



				$db->execute_query("delete from ".TABLE_PREFIX."advertiser_payment_summary where uid=? and status=-1",array($uid));
				$db->execute_query("delete from ".TABLE_PREFIX."publisher_withdrawal_summary where uid=? and status=-1",array($uid));

				if($search_by==3)
				    $value=$email;
				else if($search_by==2)
				    $value=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
				else if($search_by==1)
				    $value=$uid;
				else
				$value='';


				$this->flash($this->get_message('user deleted'), $this->make_url('user/list/'.$status.'/'.$type.'/'.$pg."/".$search_by."/".$value));

				}
			}
			else
			{

				$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));


				$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
				$detail=$res->fetch_assoc();

				$language_enabled=Configuration::get_instance()->read('language_enabled');
				$localeid=$this->get_user_locale($uid);

				if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_message']) && $detail[$localeid.'_message'] !='')
				$message=$detail[$localeid.'_message'];
				else
				$message=$detail['message'];


				if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_subject']) && $detail[$localeid.'_subject'] !='')
				$subject=$detail[$localeid.'_subject'];
				else
				$subject=$detail['subject'];


				$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
				$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

				$message=str_replace("{USERNAME}",$username,$message);



			}

			$this->set_variable('subject', $subject);
			$this->set_variable('message', $message,0);
			$this->set_variable("uid",$uid);


			$this->set_variable('yes_no', $yes_no);



	}

	function login_action()
	{

			$uid=$this->read_page_param(1);
			$frompg=$this->read_page_param(2);

			$db= DAL::get_instance();
			$sql1="select username,password,adv_status,pub_status from ".TABLE_PREFIX."users where id=?";
			$res1=$db->execute_query($sql1,array($uid));
			$value1=$res1->fetch_assoc();
			$username=$value1['username'];
			$password=$value1['password'];
			$adv_status=$value1['adv_status'];
			$pub_status=$value1['pub_status'];


			if($adv_status ==1 || $pub_status ==1)
			{
				setcookie(COOKIE_LOGINID,$uid,0,$this->get_base_path(),$this->get_base_domain());
				setcookie(COOKIE_USERNAME,$username,0,$this->get_base_path(),$this->get_base_domain());
				setcookie(COOKIE_PASSWORD,$password,0,$this->get_base_path(),$this->get_base_domain());
			}


			if($adv_status==1 && $pub_status!=1)
			setcookie(COOKIE_ADMARKETTYPE,1,0,$this->get_base_path(),$this->get_base_domain());
			else if($adv_status!=1 && $pub_status==1)
			setcookie(COOKIE_ADMARKETTYPE,2,0,$this->get_base_path(),$this->get_base_domain());
			else if($adv_status==1 && $pub_status==1)
			setcookie(COOKIE_ADMARKETTYPE,3,0,$this->get_base_path(),$this->get_base_domain());

		 	$adv_dashboard_status= Configuration::get_instance()->read('advertiser_dashboard_status');
         		$pub_dashboard_status= Configuration::get_instance()->read('publisher_dashboard_status');


			if($adv_status == 1 && $adv_dashboard_status == 1)
			header("Location: ".$this->make_base_url("dashboard/advertiser_home"));
			else if($pub_status == 1 && $pub_dashboard_status == 1)
			header("Location: ".$this->make_base_url("dashboard/publisher_home"));

				
			else
			{
				if($frompg ==1)
				$this->flash($this->get_message('account status not active'), $this->make_url('user/list'),0);
				else
				$this->flash($this->get_message('account status not active'), $this->make_url("user/profile/".$uid."/0"),0);

			}

			exit;
	}


	function create_account_action()
	{
		$this->set_title($this->get_label('create account'));

		if($_POST)
		{
			$uid=$this->read_post_param("uid");
			$type=$this->read_post_param("type");
		}
		else
		{
			$uid=$this->read_page_param(1);
			$type=$this->read_page_param(2);
		}

		$db= DAL::get_instance();

		if(!$this->get_user_exists($uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('index/control_panel'),0);
		}



		if($type==1)
		{

			$already_status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));
			if($already_status!=-2)
			{
				$this->flash($this->get_message('invalid operation'),$this->make_url("user/profile/".$uid."/0"),0);
				exit;
			}
		}
		else if($type==2)
		{
			$already_status=$db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));
			if($already_status!=-2)
			{
				$this->flash($this->get_message('invalid operation'),$this->make_url("user/profile/".$uid."/0"),0);
				exit;
			}
		}




		$yes_no=0;
		$id=4;
			if($_POST)
			{
					$subject=$this->read_post_param('subject');
					$message=$this->read_post_param('message');

					if(isset($_POST['yes_no']))
					$yes_no=1;



					$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));
					if(($subject=="" || $message=="") && $yes_no==0)
					{
						$this->set_notice('mandatory');
					}
					else
					{

						if($type==1)
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



							$res = $db->execute_query("update ".TABLE_PREFIX."users set adv_status=1 ".$bonus_string." where id=?",array($uid));

							if($adv_bonus >0)
							{
								$time1_bonus=time();
								$query_bonus="insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status,manual_entry)
								values(?,?,?,?,?,?)";
								$value_bonus=$db->execute_query($query_bonus,array($uid,4,$adv_bonus,$time1_bonus,1,1));
								$id_bonus=$value_bonus->last_id;

								$query1_bonus="insert into ".TABLE_PREFIX."advertiser_bonus_details (paymentid,comment) values(?,?)";
								$value1_bonus=$db->execute_query($query1_bonus,array($id_bonus,"Advertiser Account Creation Bonus"));
							}
					}
					if($type==2)
					{

							$sql="update ".TABLE_PREFIX."users set pub_status=? where id=?";
							$res=$db->execute_query($sql,array(1,$uid));

					}

					if($yes_no==0)
					UtilityHelper::send_mail($email,$subject,$message);

					if($type==1)
					{
						$this->flash($this->get_message('adv account creation success'),$this->make_url("user/profile/".$uid."/0"));
						exit;
					}
					else if($type==2)
					{
						$this->flash($this->get_message('pub account creation success'),$this->make_url("user/profile/".$uid."/0"));
						exit;
					}
				}
			}
			else
			{
				$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));

				$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
				$detail=$res->fetch_assoc();

				$language_enabled=Configuration::get_instance()->read('language_enabled');
				$localeid=$this->get_user_locale($uid);

				if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_message']) && $detail[$localeid.'_message'] !='')
				$message=$detail[$localeid.'_message'];
				else
				$message=$detail['message'];


				if($language_enabled ==1 && $localeid >0 && isset($detail[$localeid.'_subject']) && $detail[$localeid.'_subject'] !='')
				$subject=$detail[$localeid.'_subject'];
				else
				$subject=$detail['subject'];


				$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
				$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

				$message=str_replace("{USERNAME}",$username,$message);


				if($type==1)
				{
					$db_pub_status=$db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));

					$message=str_replace("{PUBSTATUS}",$this->get_user_status($db_pub_status),$message);
					$message=str_replace("{ADVSTATUS}",$this->get_user_status(1),$message);
				}
				else if($type==2)
				{
					$db_adv_status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));

					$message=str_replace("{ADVSTATUS}",$this->get_user_status($db_adv_status),$message);
					$message=str_replace("{PUBSTATUS}",$this->get_user_status(1),$message);
				}
			}

		$this->set_variable("uid",$uid);
		$this->set_variable("type",$type);
		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);
		$this->set_variable('yes_no', $yes_no);
	}

function withdrawal_history_action()
{
	$this->set_title($this->get_label('manage withdrawal history'));

	$db= DAL::get_instance();

	$manager_str='';
	$usr_str1='';
	$qrstr="";

	$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
	$adminid=intval($this->read_cookie_param(COOKIE_ADMIN_LOGINID));

	if($this->get_addon_status('subadmin_enabled') ==1)
	{
		if($admintype ==3)
		{
			$manager_str=" and pub_managerid=".$adminid;

			$usr_str=$this->get_users_under_manager($admintype,$adminid);

			if($usr_str !='')
			$usr_str1=" and uid in (".$usr_str.") ";
			else
			$usr_str1=" and uid=-2 ";
		}
	}


	if($_POST)
	{
		$payment=intval($this->read_post_param('payment'));
		$status=intval($this->read_post_param('status'));
		$pub=$this->read_post_param("username");
		$payment_type=intval($this->read_post_param('payment_type'));
		$pdate=$this->read_post_param('pdate');
		$rdate=$this->read_post_param('rdate');
	}
	else
	{
		$status=$this->read_page_param(1);
		$payment=$this->read_page_param(2);
		$pub=$this->read_page_param(3);
		$payment_type=$this->read_page_param(4);
		$pdate=$this->read_page_param(5);
		$rdate=$this->read_page_param(6);

		$exp=explode("-",$status);
		if($exp[0]=="page")
		$status=4;


		$exp=explode("-",$payment);
		if($exp[0]=="page")
		$payment=0;

		$exp=explode("-",$pub);
		if($exp[0]=="page")
		$pub="";

		$exp=explode("-",$payment_type);
		if($exp[0]=="page")
		$payment_type=2;
	}

	$referral_enabled=$this->get_addon_status('referral_enabled');
	$this->set_variable('referral_enabled', $referral_enabled);




	if($status === "")
	$status=4;

	if($payment === "")
	$payment=0;

	if($pdate !="" && $pdate > 0)
	{
	    $pdate_arr=explode('-', $pdate);

	    $pdate1=mktime(0,0,0,$pdate_arr[0],$pdate_arr[1],$pdate_arr[2]);

	    $pdate2=mktime(0,0,0,$pdate_arr[0],$pdate_arr[1]+1,$pdate_arr[2]);

	    if($pdate1 =="" || $pdate2 =="")
	    $pdate="";

		$this->set_variable('pdate', $pdate);
	}
	else
	$pdate="";


	if($rdate !="" && $rdate > 0)
	{
	    $rdate_arr=explode('-', $rdate);

	    $rdate1=mktime(0,0,0,$rdate_arr[0],$rdate_arr[1],$rdate_arr[2]);

	    $rdate2=mktime(0,0,0,$rdate_arr[0],$rdate_arr[1]+1,$rdate_arr[2]);

	    if($rdate1 =="" || $rdate2 =="")
	    $rdate="";

		$this->set_variable('rdate', $rdate);
	}
	else
	$rdate="";



	if($referral_enabled ==1 && ($payment_type =="" || $payment_type ==2))
	$payment_type=2;
	else if($referral_enabled !=1 && ($payment_type =="" || $payment_type ==2))
	$payment_type=0;


	$querydata="";

	if($status !=4)


	$querydata.=" AND p.status=".intval($status)." ";


	if($payment != 0)


	$querydata.=" AND p.payment_mode=".intval($payment)." ";


	if($payment_type &&  $payment_type !=2)


	$querydata.=" AND p.withdrawal_type=".intval($payment_type)." ";


	if($pdate !="" && $pdate > 0)
    	$querydata.="AND p.process_time >= ".$pdate1." AND p.process_time < ".$pdate2." ";

	if($rdate !="" && $rdate > 0)
    	$querydata.="AND p.request_time >= ".$rdate1." AND p.request_time < ".$rdate2." ";


	if($pub != "")


	$querydata.=" AND u.username LIKE '".$db->sanitize($pub)."%' ";




	$pagination2 = new Pagination("SELECT p.*,u.username FROM ".TABLE_PREFIX."publisher_withdrawal_summary p INNER JOIN ".TABLE_PREFIX."users u on p.uid=u.id WHERE p.id >0 ".$querydata." ".$usr_str1." ORDER BY p.id desc");
	$res3=$pagination2->get_result();
	$this->set_result("res3",$res3);
	$this->set_variable("pagination2",$pagination2->links(),0);

	$pg=$pagination2->get_page_number();
	$this->set_variable("pg","page-".$pg);


	$this->set_variable('payment', $payment);
	$this->set_variable('status', $status);
	$this->set_variable("pub",$pub);
	$this->set_variable('payment_type', $payment_type);

	if($referral_enabled ==1)
	$statusstring=' WHERE (adv_status=1 OR pub_status =1) ';
	else
	$statusstring=' WHERE pub_status =1 ';




}

function withdrawal_details_action()
{
	$this->set_title($this->get_label('withdrawal details'));
	$db= DAL::get_instance();
	$sid=$this->read_page_param(1);



	if(!$this->get_pub_withdrawal_exists($sid))
	{
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/withdrawal_history'),0);
	}




	$mode=$db->read_single_column("select payment_mode from ".TABLE_PREFIX."publisher_withdrawal_summary where id=?",array($sid));





	if($mode !=5)
	$res=$db->execute_query("select s.*,d.*,s.id as sid from ".TABLE_PREFIX."publisher_withdrawal_summary s INNER JOIN ".TABLE_PREFIX."publisher_withdrawal_details d ON s.id=d.paymentid where s.id=?",array($sid));
	else if($mode ==5)
	$res=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_summary where id=?",array($sid));

	$this->set_result("res",$res,array('tax_details'));


	$usid_row=$res->fetch_array();



	$usid=$usid_row['uid'];

	$usname=$this->get_user_name($usid);

	$this->set_variable('usname', $usname);

	$this->set_variable("mode",$mode);

	$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_details");
	$this->set_result("colalready",$colalready);

}



function reject_withdrawal_action()
{
	$this->set_title($this->get_label('send mail to pub'));


	if($_POST)
	{
		$sid=$this->read_post_param('sid');
		$frompage=$this->read_post_param('frompage');

		$pmt=$this->read_post_param("pmt");
		$st=$this->read_post_param("st");
		$pg=$this->read_post_param("pg");
		$usr=$this->read_post_param("usr");
		$payment_type=$this->read_post_param("payment_type");
		$pdate=$this->read_post_param('pdate');
		$rdate=$this->read_post_param('rdate');
	}
	else
	{
		$sid=$this->read_page_param(1);
		$frompage=$this->read_page_param(2);

		$pmt=$this->read_page_param(3);
		$st=$this->read_page_param(4);
		$usr=$this->read_page_param(5);
		$payment_type=$this->read_page_param(6);
		$pdate=$this->read_page_param(7);
		$rdate=$this->read_page_param(8);
		$pg=$this->read_page_param(9);
	}

	if($frompage=="")
	$frompage=0;

	if($usr=="")
		$usr=0;

	$comments="";

	$db= DAL::get_instance();


	$res=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_summary where id=?",array($sid));
	$result=$res->fetch_assoc();

	$uid=$result['uid'];
	$amount=$result['amount'];
	$receive_amount=$amount;
	$fee=$result['fee'];
	$tax=$result['tax'];
	$amount=$amount+$fee+$tax;
	$status=$result['status'];
	$payment_mode=$result['payment_mode'];
	$fromtype=$result['withdrawal_type'];
	$yes_no=0;

	if($fromtype ==1)
	$frommessage=$this->get_label('referral balance');
	else
	$frommessage=$this->get_label('publisher balance');

	if(!$this->get_pub_withdrawal_exists($sid))
	{
		if($frompage==1)
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/profile/'.$uid.'/3'),0);
		else if($frompage==2)
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/withdrawal_details/'.$sid),0);
		else
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/withdrawal_history'),0);
	}

	if(!$this->get_transaction_pending($sid))
	{
		if($frompage==1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('user/profile/'.$uid.'/3'),0);
		else if($frompage==2)
		$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_details/'.$sid),0);
		else
		$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_history'),0);
	}

	if($_POST)
	{
		$subject=$this->read_post_param('subject');
		$message=$this->read_post_param('message');
	 	$comments=$this->read_post_param('comments');
	 	$pdate=$db->read_single_column("select pre_withdrawal_requestdate from ".TABLE_PREFIX."users where id=?",array($uid));

	 	if(isset($_POST['yes_no']))
	 	$yes_no=1;

	 	$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

		if(($subject=="" || $message=="") && $yes_no==0)
		{
			$this->set_notice('mandatory');
		}
		else
		{
			$time1=time();

			$db->execute_query("BEGIN");
			$failed=0;

			if($fromtype ==1)
			$fieldstring1=' referral_balance=referral_balance+?,withdrawal_requestdate=? ';
			else
			$fieldstring1=' pub_account_balance=pub_account_balance+?,withdrawal_requestdate=? ';

			$update=$db->execute_query("update ".TABLE_PREFIX."users set ".$fieldstring1." where id=?",array($amount,$pdate,$uid));

			if($update->error =="")
			{
				$update1=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_summary set status=0,process_time=? where id=?",array($time1,$sid));

				if($update1->error =="")
				{
					if($payment_mode ==5)
					$update2=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status) values(?,?,?,?,?)",array($uid,5,$amount,$time1,0));
					else if($payment_mode !=5)
					$update2=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set comments=? where paymentid=?",array($comments,$sid));

					if($update2->error !="")
					$failed=1;
				}
				else
				$failed=1;

			}
			else
			$failed=1;

			if($failed ==0)
			{
				$db->execute_query("COMMIT");
				$messagedata=$this->get_message('withdrawal reject');

				if($yes_no==0)
				UtilityHelper::send_mail($email,$subject,$message);
			}
			else
			{
				$db->execute_query("ROLLBACK");

				$messagedata=$this->get_message('error occurred');
			}

			if($frompage==1)
			$this->flash($messagedata, $this->make_url('user/profile/'.$uid.'/9/o/'.$pmt.'/'.$st.'/'.$payment_type.'/'.$pg));
			else if($frompage==2)
			$this->flash($messagedata, $this->make_url('user/withdrawal_details/'.$sid));
		   	else
			$this->flash($messagedata, $this->make_url('user/withdrawal_history/'.$st.'/'.$pmt.'/'.$usr.'/'.$payment_type.'/'.$pdate.'/'.$rdate.'/'.$pg));
		}
	}
	else
	{
		$id=11;
		$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
		$value1=$res->fetch_assoc();

		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$localeid=$this->get_user_locale($uid);


		if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_message']) && $value1[$localeid.'_message'] !='')
		$message=$value1[$localeid.'_message'];
		else
		$message=$value1['message'];


		if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_subject']) && $value1[$localeid.'_subject'] !='')
		$subject=$value1[$localeid.'_subject'];
		else
		$subject=$value1['subject'];

		$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));


		$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
		$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

		$message=str_replace("{USERNAME}",$username,$message);
		$message=str_replace("{PAYMENTMODE}",$this->get_withdrawal_mode($payment_mode),$message);
		$message=str_replace("{WITHDRAWFROM}",$frommessage,$message);




    	$fee_string 	= "";
    	$tax_string 	= "";
    	$total_string	= "";

    	if($fee >0)
    	$fee_string="<br/>".$this->get_label('fee')." : ".$this->get_money_format($fee);

    	if($tax >0)
    	$tax_string="<br/>".$this->get_label('tax')." : ".$this->get_money_format($tax);

    	if($fee >0 || $tax >0)
    	$total_string="<br/>".$this->get_label('total')." : ".$this->get_money_format($receive_amount+$tax+$fee);

    	$message=str_replace("{AMOUNT}",$this->get_money_format($receive_amount).$fee_string.$tax_string.$total_string,$message);


	}

	$this->set_variable('sid',$sid);
	$this->set_variable('uid',$uid);
	$this->set_variable('yes_no', $yes_no);
	$this->set_variable("subject",$subject);
	$this->set_variable("message",$message,0);
	$this->set_variable("frompage",$frompage);
	$this->set_variable('comments',$comments);
	$this->set_variable('payment_mode',$payment_mode);
	$this->set_variable('payment_type',$payment_type);
	$this->set_variable('pmt', $pmt);
	$this->set_variable('st', $st);
	$this->set_variable('pg', $pg);
	$this->set_variable('usr', $usr);
	$this->set_variable('pdate', $pdate);
	$this->set_variable('rdate', $rdate);
}

function approve_withdrawal_action()
{
	$this->set_title($this->get_label('send mail to pub'));

	$db= DAL::get_instance();

	if($_POST)
	{
		$sid=$this->read_post_param('sid');
		$frompage=$this->read_post_param('frompage');

		$pmt=$this->read_post_param("pmt");
		$st=$this->read_post_param("st");
		$pg=$this->read_post_param("pg");
		$usr=$this->read_post_param("usr");
		$payment_type=$this->read_post_param("payment_type");
		$pdate=$this->read_post_param('pdate');
		$rdate=$this->read_post_param('rdate');
	}
	else
	{
		$sid=$this->read_page_param(1);
		$frompage=$this->read_page_param(2);

		$pmt=$this->read_page_param(3);
		$st=$this->read_page_param(4);
		$usr=$this->read_page_param(5);
		$payment_type=$this->read_page_param(6);
		$pdate=$this->read_page_param(7);
		$rdate=$this->read_page_param(8);
		$pg=$this->read_page_param(9);
	}


	if($frompage=="")
	$frompage=0;

	if($usr=="")
	$usr="";

	$res=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_summary where id=?",array($sid));
	$result=$res->fetch_assoc();

	$uid=$result['uid'];
	$amount=$result['amount'];
	$status=$result['status'];
	$tax=$result['tax'];
	$fee=$result['fee'];

	$payment_mode=$result['payment_mode'];
	$fromtype=$result['withdrawal_type'];

	if($fromtype ==1)
	$frommessage=$this->get_label('referral balance');
	else
	$frommessage=$this->get_label('publisher balance');

	if(!$this->get_pub_withdrawal_exists($sid))
	{
		if($frompage==1)
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/profile/'.$uid.'/3'),0);
		else if($frompage==2)
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/withdrawal_details/'.$sid),0);
		else
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/withdrawal_history'),0);
	}


	if(!$this->get_transaction_pending($sid))
	{
		if($frompage==1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('user/profile/'.$uid.'/3'),0);
		else if($frompage==2)
		$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_details/'.$sid),0);
		else
		$this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_history'),0);
	}

	$transaction="";
	$comments="";
	$yes_no=0;

	if($_POST)
	{
		$subject=$this->read_post_param('subject');
		$message=$this->read_post_param('message');
	 	$comments=$this->read_post_param('comments');
		$transaction=$this->read_post_param('transaction');


		if(isset($_POST['yes_no']))
		$yes_no=1;

	 	$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

	 	if($payment_mode ==5)
	 	{
		 		if(($subject=="" || $message=="") && $yes_no==0)
		 		{
		 			$this->set_notice('mandatory');
		 		}
	 	        else
	 	        {
	 	        	$db->execute_query("BEGIN");
					$failed=0;

	 				$time1=time();


	 				$res0=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status) values(?,?,?,?,?)",array($uid,5,$amount,$time1,1));

	 				if($res0->error =="")
	 				{

		 				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_summary set status=1,process_time=? where id=?",array($time1,$sid));

		 				if($res->error =="")
		 				{
			 				$res2=$db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=adv_account_balance+? where id=?",array($amount,$uid));

			 				if($res2->error =="")
			 				{
				 				////////////////////////////////////////////////////////////////////////////////////////////////////
			                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');

			                    $user_row	= $db->execute_query("SELECT email,adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			                    $user_data	= $user_row->fetch_assoc();

			                    $adv_current_balance	= $user_data['adv_account_balance'];
			                    $balancestatus			= $user_data['balancestatus'];
			                    $email					= $user_data['email'];

			                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
			                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid));

				 				//////////////////////////////////////////////////////////////////////////////////////////////////////
			 				}
			 				else
							$failed=1;
			 			}
	 				    else
						$failed=1;

					}
	 				else
					$failed=1;

	 	        	if($failed ==0)
					{
						$db->execute_query("COMMIT");
						$messagedata=$this->get_message('withdrawal success');

						if($yes_no==0)
	 					UtilityHelper::send_mail($email,$subject,$message);
					}
					else
					{
						$db->execute_query("ROLLBACK");

						$messagedata=$this->get_message('error occurred');
					}

		 			if($frompage==1)
		 			$this->flash($messagedata, $this->make_url('user/profile/'.$uid.'/9/o/'.$pmt.'/'.$st.'/'.$payment_type.'/'.$pg));
		 			else if($frompage==2)
		 			$this->flash($messagedata, $this->make_url('user/withdrawal_details/'.$sid));
		 			else
		 			$this->flash($messagedata, $this->make_url('user/withdrawal_history/'.$st.'/'.$pmt.'/'.$usr.'/'.$payment_type.'/'.$pdate.'/'.$rdate.'/'.$pg));
	 	        }
	 	}
	 	else if($payment_mode < 5)
	 	{

	 		if(($subject=="" || $message=="" || $transaction=="") && $yes_no==0)
	 		{
	 			$this->set_notice('mandatory');
	 		}
	 		else
			{
				$db->execute_query("BEGIN");
				$failed=0;


				$time1=time();

				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_summary set status=1,process_time=? where id=?",array($time1,$sid));

				if($res->error =="")
				{
					if($payment_mode==3)
					$res2=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set paypal_transaction_id=? where paymentid=?",array($transaction,$sid));


					if($payment_mode==1)
					$res2=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set check_number=? where paymentid=?",array($transaction,$sid));


					if($payment_mode==2)
					$res2=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set bank_transaction_id=? where paymentid=?",array($transaction,$sid));


					$res1=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set comments=? where paymentid=?",array($comments,$sid));
				}
				else
				$failed=1;

				if($failed ==0)
				{
					$db->execute_query("COMMIT");
					$messagedata=$this->get_message('withdrawal success');

					if($yes_no ==0)
					UtilityHelper::send_mail($email,$subject,$message);
				}
				else
				{
					$db->execute_query("ROLLBACK");

					$messagedata=$this->get_message('error occurred');
				}

				if($frompage==1)
				$this->flash($messagedata, $this->make_url('user/profile/'.$uid.'/9/o/'.$pmt.'/'.$st.'/'.$payment_type.'/'.$pg));
				else if($frompage==2)
				$this->flash($messagedata, $this->make_url('user/withdrawal_details/'.$sid));
				else
				$this->flash($messagedata, $this->make_url('user/withdrawal_history/'.$st.'/'.$pmt.'/'.$usr.'/'.$payment_type.'/'.$pdate.'/'.$rdate.'/'.$pg));
			}
	 	}
	 	else if($payment_mode > 5)
	 	{
	 		$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_details");
	 		$this->set_result("colalready",$colalready);

	 		$flag=0;
	 		$string4='';

	 		while($row123 = $colalready->fetch_array())
	 		{
	 			$carray=explode('_detail_',$row123['Field']);
	 			if($carray[0] == $payment_mode)
	 			{
	 				$submitdata=$db->sanitize(trim($this->read_post_param($row123['Field'])));

	 				$this->set_variable($row123['Field'],$submitdata);

	 				if($submitdata !='' && $flag ==0)
	 				{
	 					if(strtolower($row123['Type']) =='int(11)' && !is_numeric($submitdata))
	 					$flag=2;

	 					if($flag ==0)
	 					{
 							if($string4 !='')
	 						$string4.=',';

	 						$string4.=$db->sanitize($row123['Field'])."='".$submitdata."' ";
	 					}
	 				}
	 				else if($flag ==0)
 					$flag=1;
	 			}
	 		}


	 		if($flag ==0)
	 		{
	 			if($string4 !='')
	 			$string4.=',';

	 			if(($subject=="" || $message=="") && $yes_no==0)
	 			{
	 				$this->set_notice('mandatory');
	 			}
	 			else
	 			{
	 				$db->execute_query("BEGIN");
					$failed=0;

	 				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."publisher_withdrawal_summary set status=1,process_time=? where id=?",array(time(),$sid));

	 				if($res->error =="")
	 				{
		 				$res1=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set ".$string4." comments=? where paymentid=?",array($comments,$sid));

		 				if($res1->error !="")
		 				$failed=1;
	 				}
	 				else
					$failed=1;

	 				if($failed ==0)
					{
						$db->execute_query("COMMIT");
						$messagedata=$this->get_message('withdrawal success');

						if($yes_no ==0)
						UtilityHelper::send_mail($email,$subject,$message);
					}
					else
					{
						$db->execute_query("ROLLBACK");

						$messagedata=$this->get_message('error occurred');
					}

	 				if($frompage==1)
	 				$this->flash($messagedata, $this->make_url('user/profile/'.$uid.'/9/o/'.$pmt.'/'.$st.'/'.$payment_type.'/'.$pg));
	 				else if($frompage==2)
	 				$this->flash($messagedata, $this->make_url('user/withdrawal_details/'.$sid));
	 				else
	 				$this->flash($messagedata, $this->make_url('user/withdrawal_history/'.$st.'/'.$pmt.'/'.$usr.'/'.$payment_type.'/'.$pdate.'/'.$rdate.'/'.$pg));

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
		$id=10;
		$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
		$value1=$res->fetch_assoc();

		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$localeid=$this->get_user_locale($uid);


		if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_message']) && $value1[$localeid.'_message'] !='')
		$message=$value1[$localeid.'_message'];
		else
		$message=$value1['message'];


		if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_subject']) && $value1[$localeid.'_subject'] !='')
		$subject=$value1[$localeid.'_subject'];
		else
		$subject=$value1['subject'];


		$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));

		$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
		$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

		$message=str_replace("{USERNAME}",$username,$message);
		$message=str_replace("{PAYMENTMODE}",$this->get_withdrawal_mode($payment_mode),$message);
		$message=str_replace("{WITHDRAWFROM}",$frommessage,$message);


    	$fee_string 	= "";
    	$tax_string 	= "";
    	$total_string	= "";

    	if($fee >0)
    	$fee_string="<br/>".$this->get_label('fee')." : ".$this->get_money_format($fee);

    	if($tax >0)
    	$tax_string="<br/>".$this->get_label('tax')." : ".$this->get_money_format($tax);

    	if($fee >0 || $tax >0)
    	$total_string="<br/>".$this->get_label('total')." : ".$this->get_money_format($amount+$tax+$fee);

    	$message=str_replace("{AMOUNT}",$this->get_money_format($amount).$fee_string.$tax_string.$total_string,$message);



		$colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_details");
		$this->set_result("colalready",$colalready);
	}

	$this->set_variable('uid',$uid);
	$this->set_variable('sid',$sid);
	$this->set_variable('yes_no', $yes_no);
	$this->set_variable("subject",$subject);
	$this->set_variable("message",$message,0);
	$this->set_variable("frompage",$frompage);
	$this->set_variable('comments', $comments);
	$this->set_variable('transaction', $transaction);
	$this->set_variable('payment_mode',$payment_mode);
	$this->set_variable('payment_type',$payment_type);

	$this->set_variable('pmt', $pmt);
	$this->set_variable('st', $st);
	$this->set_variable('pg', $pg);
	$this->set_variable('usr', $usr);
	$this->set_variable('pdate', $pdate);
	$this->set_variable('rdate', $rdate);
}
function auto_request_action()
{
    $db= DAL::get_instance();

    $uid=intval($this->read_page_param(1));
    $type=intval($this->read_page_param(2));



    if(Configuration::get_instance()->read('enable_auto_withdrawal') != 0)
    {
    	$this->flash("invalid operation", $this->make_url('user/profile/'.$uid),0);
        exit;
    }







    $unique_user=$uid;
    $w_date=Configuration::get_instance()->read('pub_withdrawal_date');

    $returnval=$this->auto_generate($uid,$type);

    if($returnval ==3)
    {
        $this->flash("Not enough balance for generating withdrawal request", $this->make_url('user/profile/'.$uid),0);
        exit(0);
    }
    else if ($returnval==4)
    {
        $this->flash("This user have already a pending request", $this->make_url('user/profile/'.$uid),0);
        exit(0);
    }
    else if ($returnval==5)
    {
        $this->flash("Please configure a withdrawal mode for this user", $this->make_url('user/profile/'.$uid),0);
        exit(0);
    }
    else if ($returnval==6)
    {
        $this->flash("Invalid withdrawal gateway configured", $this->make_url('user/profile/'.$uid),0);
        exit(0);
    }
    else if($returnval ==0)
    {
        $this->flash("Withdrawal request successfully generated", $this->make_url('user/profile/'.$uid));
        exit(0);
    }
    else
    {
        $this->flash("Withdrawal request generation failed", $this->make_url('user/profile/'.$uid),0);
        exit(0);

    }

}



function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}
function report_files_action()
{
    $this->set_title($this->get_label('report files'));
    $db=DAL::get_instance();
    $sql="select distinct request_time from ".TABLE_PREFIX."publisher_withdrawal_summary  order by request_time desc";
    $pagination=new Pagination($sql);
    $res=$pagination->get_result();
    $this->set_result('res', $res);
    $this->set_variable('link',$pagination->links(),0);



    $customWithdrawals = $db->execute_query("select * from ".TABLE_PREFIX."withdrawal_gateway where id > 5");
    $this->set_result("customWithdrawals",$customWithdrawals);




}
function  download_report_action()
{
    $page=$this->read_page_param(1);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$page);
    header("Content-Length: " . filesize(DATA_DIR_PATH.'payment_requests/'.$page));
    $fp = fopen(DATA_DIR_PATH.'payment_requests/'.$page, "r");
    fpassthru($fp);
    fclose($fp);
    die;
}
function mass_approve_action()
{


    $db= DAL::get_instance();

    $date='';
    if($_POST)
    {
        $date=$this->read_post_param('date');
        $payment=intval($this->read_post_param('payment'));
    }

    if($payment === "")
    $payment = 0;


        if($date=='')

            $this->flash($this->get_message('please fill requested date'),$this->make_url('user/withdrawal_history'),0);
            if($payment==0)
                $payment_str="";
                else
                    $payment_str=" and payment_mode='".$payment."' ";

                    $date_arr=explode('-', $date);


                    $req_time1=mktime(0,0,0,$date_arr[1],$date_arr[2],$date_arr[0]);
                    $req_time2=mktime(23,59,59,$date_arr[1],$date_arr[2],$date_arr[0]);


                    $res=$db->execute_query("select id,amount,status,uid,payment_mode from ".TABLE_PREFIX."publisher_withdrawal_summary where status=? and request_time>=? and request_time<=? ".$payment_str ,array(-1,$req_time1,$req_time2));
                    if($res->num_records>0)
                    {
                        $id=10;
                        $res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
                        $value1=$res1->fetch_assoc();
                        while ($result=$res->fetch_assoc())
                        {
                            $sid=$result['id'];
                            $uid=$result['uid'];
                            $amount=$result['amount'];
                            $status=$result['status'];
                            $payment_mode=$result['payment_mode'];



                            $language_enabled=$this->get_addon_status('language_enabled');
                            $localeid=$this->get_user_locale($uid);


                            if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_message']) && $value1[$localeid.'_message'] !='')
                                $message=$value1[$localeid.'_message'];
                                else
                                    $message=$value1['message'];


                                    if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_subject']) && $value1[$localeid.'_subject'] !='')
                                        $subject=$value1[$localeid.'_subject'];
                                        else
                                            $subject=$value1['subject'];





                                            $username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));

                                            $subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
                                            $message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

                                            $message=str_replace("{USERNAME}",$username,$message);
                                            $message=str_replace("{PAYMENTMODE}",$this->get_withdrawal_mode($payment_mode),$message);
                                            $message=str_replace("{AMOUNT}",$this->get_money_format($amount),$message);


                                            $email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));


                                            $time1=time();
                                            $res1=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_summary set status=1,process_time=? where id=?",array($time1,$sid));

                                            /*
                                             if($payment_mode==3)
                                             $res2=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set paypal_transaction_id=? where paymentid=?",array($transaction,$sid));


                                             if($payment_mode==1)
                                             $res2=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set check_number=? where paymentid=?",array($transaction,$sid));

                                                                                          if($payment_mode==2)
                                             $res2=$db->execute_query("update ".TABLE_PREFIX."publisher_withdrawal_details set bank_transaction_id=? where paymentid=?",array($transaction,$sid));
                                             */


                                             if($res1->error=='')
                                             UtilityHelper::send_mail($email,$subject,$message);


                        }
                        $this->flash($this->get_message('mass approval success'), $this->make_url('user/withdrawal_history'),1);
                    }
                    else 	$this->flash($this->get_message('no request available for this date'), $this->make_url('user/withdrawal_history'),0);

}

function invoice_export_action()
{
    $id=$this->read_page_param(1);
    $uid=$this->read_page_param(2);

	if(!$this->get_pub_withdrawal_exists($id))
	{
		$this->flash($this->get_message('invalid withdrawal id'), $this->make_url('user/withdrawal_history'),0);
	}

    $wkhtmlpath=Configuration::get_instance()->read('wkhtml_path');
    $invoice_download=Configuration::get_instance()->read('invoice_download');

    if($invoice_download== 0 || $wkhtmlpath=='')
    {
	     $this->flash($this->get_message('invalid operation'), $this->make_url('user/withdrawal_history'),0);
	     exit;
    }




    if(!is_callable('shell_exec') || stripos(ini_get('disable_functions'),'shell_exec'))
    {
         $url=$this->make_url('user/withdrawal-history');
         $this->flash($this->get_message('shell execute function enable for pdf download'),$url ,0);
    }

    if(!is_dir(PATH_TO_ROOT.DATA_DIR.'/pdf/'))
    mkdir(PATH_TO_ROOT.DATA_DIR.'/pdf/',0777);


        $filename=PATH_TO_ROOT.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';
        $shellfilename=str_replace('/'.ADMIN_DIR,'',getcwd()).'/'.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';


        ob_clean();


        header("Content-Description: File Transfer");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Pragma: public"); // required
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // required for certain browsers


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
    $stringdata=$this->read_page_param(4);
    $localname=DEFAULT_LOCALE;

    $newstringdata=md5($id.$uid.$localname.'data'.Configuration::get_instance()->read('admarket_name'));

    if($stringdata != $newstringdata)
    exit;


        $direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
        $direction=intval($direction);

        $this->set_variable('direction',$direction);


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

};
?>
