<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once(LIB_DIR_PATH."FCKeditor/fckeditor.php") ;

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
			
			
		
		    if($frompg==1)
			$this->flash($this->get_message('email send'), $this->make_url('user/list/'.$status.'/'.$type.'/'.$pg));
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
		$mngr_str='';
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
		
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==2)
			$mngr_str=" and adv_managerid=".$adminid;
			else if($admintype ==3)
			$mngr_str=" and pub_managerid=".$adminid;
		}
		
		if($_POST)
		{
			$type=$this->read_post_param('type');
			$status=$this->read_post_param('status');
			$uname=$this->read_post_param('uname');
			$uid=$this->read_post_param('uid');
			$this->set_variable("uid",$uid);
			
		}
		else
		{
			$status=$this->read_page_param(1);
			$type=$this->read_page_param(2);
			
			$exp=explode("-",$status);
			if($exp[0]=="page")
			{
				$status=$this->read_page_param(2);
				$type=$this->read_page_param(3);
			}
			
			
			$uname="";
			
			
		//	if($status !=-1)
		//	$status=2;
			
		}
				
		
		if($type=="")
		$type=1;
		
		if($status=="")
		$status=2;
		
		
		
		
	  $status_str="";
	  
	  
	  if($type < 3)
	  {
		  if($status==2)
		  $status_str="";
		  else 
		  {
		  	if($type==1)
		  	$status_str=" AND adv_status='".$status."' ";
		  	else if($type==2)
		  	$status_str=" AND pub_status='".$status."' ";
		  }
	  }
	  else if($type ==3 && $uname !="")
	  {
	  		$status_str=" AND username LIKE '%".$uname."%' ";
	  }
	  else if($type ==4 && $uid >0)
	  {
	         $status_str=" AND id =".$uid." ";
	  }
	  
	  
	  
		
		
		
		$this->set_variable("type",$type);
		$this->set_variable("status",$status);
		$this->set_variable("uname",$uname);
		
		
		
		
		
		
		$query = "SELECT id,username,adv_status,pub_status,email FROM ".TABLE_PREFIX."users where id >0 ".$status_str." ".$mngr_str." ORDER BY id desc";
		$pagination = new Pagination($query);
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
		$affiliate_enabled  = $this->get_addon_status('affiliate-ads_enabled');
		$video_enabled		= $this->get_addon_status('video-ads_enabled');
		$subadmin_enabled   = $this->get_addon_status('subadmin_enabled');
		
		
		
		
		if(isset($_POST['saveadvertiserdatabutton']))
		{
		    $ppc_minrate		= $this->read_post_param('ppc_minrate');
		    $cpm_minrate		= $this->read_post_param('cpm_minrate');
		    $cpa_minrate		= $this->read_post_param('cpa_minrate');
		    $pop_minrate		= $this->read_post_param('pop_minrate');
		    $video_minrate		= $this->read_post_param('video_minrate');
		    $affiliate_minrate	= $this->read_post_param('affiliate_minrate');
			$adv_manager		= $this->read_post_param('adv_mngr');
		    
		    		    
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
		    
			
			$string_array[]=$id;
			
				
			if($string_update !="")
			$data=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET ".$string_update." WHERE id=?",$string_array);
			
		
			$this->flash($this->get_message('advertiser configurations successfully updated'), $this->make_url("user/profile/".$id."/".$tab));
			exit;
		}				
		
		
		
		if(isset($_POST['savepublisherdatabutton']))
		{
			$ppc_profit_percentage			= $this->read_post_param('ppc_profit_percentage');
			$cpm_profit_percentage			= $this->read_post_param('cpm_profit_percentage');
			$cpa_profit_percentage			= $this->read_post_param('cpa_profit_percentage');
			$sponsored_profit_percentage	= $this->read_post_param('sponsored_profit_percentage');
			$pop_profit_percentage			= $this->read_post_param('pop_profit_percentage');
			$cpv_profit_percentage			= $this->read_post_param('cpv_profit_percentage');
			$affiliate_profit_percentage	= $this->read_post_param('affiliate_profit_percentage');
			$pub_manager					= $this->read_post_param('pub_mngr');
			$get_pop_link					= $this->read_post_param('get_pop_link');
			$vast_adcode_enabled			= $this->read_post_param('vast_adcode_enabled');
			$pub_captcha_status				= $this->read_post_param('pub_captcha_status');
			
		
		    		    
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
			
				$string_update.='pop_profit_percentage=?,get_pop_link=?';
				
				$string_array[]=$pop_profit_percentage;
				$string_array[]=$get_pop_link;
				
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
		    
			
			$string_array[]=$id;
			
				
			if($string_update !="")
			$data=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET ".$string_update." WHERE id=?",$string_array);
			
		
			$this->flash($this->get_message('publisher configurations successfully updated'), $this->make_url("user/profile/".$id."/".$tab));
			exit;
		}				
				

		
		
		if(isset($_POST['saveuserdatabutton']))
		{
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
			$pg=$this->read_page_param(8);
		}
		else 
		{
			$dur=1;
			$tp=0;
			$st=2;
			$adpricing=-1;
			$pg="";
		}
		
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
		
		
		
		
		$res=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($id));
		$this->set_result("res",$res);
		
		$site_json=$db->read_single_column("SELECT restricted_sites FROM ".TABLE_PREFIX."users where id=?",array($id));
		
		$sites_array=json_decode($site_json); 
		$rest_num=count($sites_array); 
		if($rest_num > 0)
		$this->set_array("sites", $sites_array,array(),1);
		$this->set_variable("rest_num",$rest_num);
		
		$this->set_variable("tab",$tab);
		
		
			$day_begin1 =date("Y",time());
			$day_begin1.=date("m",time());
			$day_begin1.=date("d",time());
			$day_begin1.=date("H",time());
			
		
		
		
		$pfsum=$db->read_single_column("select SUM(profit) from ".TABLE_PREFIX."dailyclicks where pid=? AND profit >0 AND time >=?",array($id,$day_begin1));
		


		$this->set_variable('pfsum',$pfsum);

		
		
		
		
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
		$payment=$this->read_post_param('payment');
		$status=$this->read_post_param('status');
		$uid=$this->read_post_param('uid');
	}
	else 
	{
		$uid=$this->read_page_param(1);
		$payment=$this->read_page_param(2);
		$status=$this->read_page_param(3);
	}

	
	if($status=="")
	$status=4;
	
	
	if($payment=="")
	$payment=0;
	
	$adv_status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));
	
	

	
	
	if($status==4)
	$status_str="";
	else 
	$status_str=" and status='".$status."' ";
	
	
	
	if($payment==4)
	$status_str="";
	
	
	if($payment==0)
		$payment_str="";
	else
	$payment_str=" and payment_type='".$payment."' ";
	
	
			$query1="select * from ".TABLE_PREFIX."advertiser_payment_summary where uid=? ".$payment_str.$status_str." ORDER BY id desc";
			$pagination1 = new Pagination($query1,array($uid));
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
	$duration=$this->read_post_param("duration");
	else
	$duration=1;

	if($_POST)
	{
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$from_date='';
		$to_date='';
	}
	
	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	if($duration=="" || $duration==0)
	$duration=1;
	
	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$uid);
	
}

function advadstat_action()
{
	$this->disable_notice_area();
	$db= DAL::get_instance();
	$id=$this->read_page_param(1);
	
	if($_POST)
	{
		$adtype=$this->read_post_param('type');
		$status=$this->read_post_param('status');
		$duration=$this->read_post_param("duration");
		$adpricing=$this->read_post_param("adpricing");
	}
	else
	{

	    
	    $duration=$this->read_page_param(2);
	    $adtype=$this->read_page_param(3);
	    $status=$this->read_page_param(4);
	    $adpricing=$this->read_page_param(5);
	    
	}
	if($_POST)
	{
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$from_date='';
		$to_date='';
	}
	
	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	
	$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
	$this->set_variable('text_ads_enabled',$text_ads_enabled);		
	
	$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
	$this->set_variable('ecommerce_enabled',$ecommerce_enabled);	
	
	
	$pop_addon_usage = 0;
	
	if($this->get_addon_status('pop-ads_enabled') == 1)
	$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));
	
	$this->set_variable('pop_addon_usage',$pop_addon_usage);			
	
	
	
	$cpv_enabled=$this->get_addon_status('video-ads_enabled');			
	
	if($duration=="" || $duration==0)
	$duration=1;
	
	if($adpricing=="")
	$adpricing=-1;
	
	if($adtype=="")
	$adtype=0;
	
	if($status=="")
	$status=2;
	
	if($adtype ==0)
	$adtype_str="";
	else
	$adtype_str=" and a.type='".$adtype."' ";
	
	
	if($text_ads_enabled !=1)		
	$adtype_str.=' AND a.type <>1 ';		
	
	if($this->get_addon_status('interstitial_enabled') !=1)
	$adtype_str.=' AND a.type <>5 ';	
	
	if($ecommerce_enabled !=1)
	$adtype_str.=' AND a.type <>7 ';		
		
	if($this->get_addon_status('text-image-ads_enabled') !=1)
	$adtype_str.=' AND a.type <>11 ';		
		
	if($cpv_enabled !=1)
	$adtype_str.=' AND a.type <>13 ';		
	
	if($this->get_addon_status('skin-ads_enabled') !=1)
	$adtype_str.=' AND a.type <>14 ';		
	
	
	if($status ==2)
	$status_str=" AND a.status <> -2 ";
	else
	$status_str=" AND a.status='".$status."' ";
	
	if($adpricing ==-1)
	$adpricing_str="";
	else
	$adpricing_str=" and a.display_type='".$adpricing."' ";
		
		
	
		if($this->get_addon_status('cpc_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>0 ';	
	
		if($this->get_addon_status('cpm_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>1 ';
		
		if($this->get_addon_status('cpa_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>6 ';
		
		if($this->get_addon_status('sponsored_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>3 ';
		
		if($this->get_addon_status('pop-ads_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>9 AND a.type <>9 ';
		
		if($this->get_addon_status('affiliate-ads_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>12 ';	
		
		if($cpv_enabled !=1)
		$adpricing_str.=' AND a.display_type <>13 ';	
				
	
	$this->set_variable("type",$adtype);
	$this->set_variable("status",$status);
	$this->set_variable("duration",$duration);
	$this->set_variable("adpricing",$adpricing);
	
	
		
	$query11= "SELECT * FROM ".TABLE_PREFIX."ads a where uid=? AND ecommerce_parent =0 ".$adtype_str.$status_str.$adpricing_str." ORDER BY id DESC";
	$pagination = new Pagination($query11,array($id));
	$res11=$pagination->get_result();
	$this->set_result("res11",$res11);
	$this->set_variable("pagination",$pagination->links(),0);
	
	
	$pg=$pagination->get_page_number();
	$this->set_variable("pg","page-".$pg);
	
	
	$this->set_variable("uid",$id);
}

function advall_action()
{
	$this->disable_notice_area();
	$id=$this->read_page_param(1);
	
	if($_POST)
	$duration=$this->read_post_param("duration");
	else
	$duration=1;

	if($_POST)
	{
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$from_date='';
		$to_date='';
	}
	
	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	if($duration=="" || $duration==0)
	$duration=1;
	
	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$id);
	
}

function pubtimestat_action()
{
	$this->disable_notice_area();
	$uid=$this->read_page_param(1);


	if($_POST)
	$duration=$this->read_post_param("duration");
	else
	$duration=1;

	if($_POST)
	{
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$from_date='';
		$to_date='';
	}
	
	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	if($duration=="" || $duration==0)
	$duration=1;

	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$uid);

}

function puball_action()
{
	$this->disable_notice_area();
	$id=$this->read_page_param(1);


	if($_POST)
	$duration=$this->read_post_param("duration");
	else
	$duration=1;

	if($_POST)
	{
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$from_date='';
		$to_date='';
	}
	
	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	if($duration=="" || $duration==0)
	$duration=1;

	$this->set_variable("duration",$duration);
	$this->set_variable("uid",$id);

}

function pubwithdrawal_action()
{
	$this->disable_notice_area();
	$db= DAL::get_instance();
	
	
	if($_POST)
	{
		$payment=$this->read_post_param('payment');
		$status=$this->read_post_param('status');
		$uid=$this->read_post_param('uid');
		$payment_type=$this->read_post_param("payment_type");
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
	
	
	if($status=="")
	$status=4;
	
	
	if($payment=="")
	$payment=0;
		
	if($referral_enabled ==1 && ($payment_type =="" || $payment_type ==2))
	$payment_type=2;
	else if($referral_enabled !=1 && ($payment_type =="" || $payment_type ==2))
	$payment_type=0;		
	
	
	if($status==4)
		$status_str="";
	else
		$status_str=" and status='".$status."' ";
	
	
	
	
	if($payment==0)
	$payment_str="";
	else
	$payment_str=" and payment_mode='".$payment."' ";
	
	$type_str="";
	if($payment_type !=2)
	$type_str=" AND withdrawal_type=".$payment_type." ";	
	
	
	$pub_status=$db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));
	
	
	$query3 = "SELECT * FROM ".TABLE_PREFIX."publisher_withdrawal_summary where uid=? ".$payment_str.$status_str.$type_str." ORDER BY id desc";
	$pagination2 = new Pagination($query3,array($uid));
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
		$duration=$this->read_post_param("duration");
		$sid=intval($this->read_post_param("sid"));
		$adpricing=$this->read_post_param("adpricing");
	}
	else
	{
		$duration=1;
		$sid=0;
		$adpricing=-1;
	}
	if($_POST)
	{
		$from_date=$this->read_post_param("from_date");
		$to_date=$this->read_post_param("to_date");
	}
	else
	{
		$from_date='';
		$to_date='';
	}
	
	
	
		
	$cpc_enabled=$this->get_addon_status('cpc_enabled');
	$cpm_enabled=$this->get_addon_status('cpm_enabled');
	$html_enabled=$this->get_addon_status('html_enabled');
	$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
	$cpa_enabled=$this->get_addon_status('cpa_enabled');		
	$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
	$pop_enabled=$this->get_addon_status('pop-ads_enabled');
	$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
	$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');	
	$cpv_enabled=$this->get_addon_status('video-ads_enabled');			
	$skin_enabled=$this->get_addon_status('skin-ads_enabled');	
	$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
	
	
	if($cpm_enabled ==1 || $html_enabled ==1)
	$cpm_enabled=1;			

	
	
	
	
	if($adpricing =='')
	$adpricing=-1;
	
	$this->set_variable("from_date",$from_date);
	$this->set_variable("to_date",$to_date);
	$this->set_variable("adpricing",$adpricing);
	
	if($duration=="" || $duration==0)
	$duration=1;
	
	$sidquery='';
	if($this->get_addon_status('category-targeting_enabled') ==1)
	{
		if($sid >0)
		$sidquery=' AND sid='.$sid.' ';
	}
	
	
	
	
		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" and display_type='".$adpricing."' ";
	
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
		
		if($pop_enabled !=1)
		$adpricing_str.=' AND display_type <>9 AND adcode_type <>9 ';
		
		if($affiliate_enabled !=1)
		$adpricing_str.=' AND display_type <>12 ';	
		
		if($cpv_enabled !=1)
		$adpricing_str.=' AND display_type <>13 ';			
		
		$adtype_str="";
		
		
		if($interstitial_enabled !=1)
		$adtype_str.=' ab.banner_type <>1 ';		
		
		if($textimage_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';
			
			$adtype_str.=' ab.banner_type <>3 ';	
		}	
			
		if($skin_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';
			
			$adtype_str.=' ab.banner_type <>4 ';	
		}		

		
		if($text_ads_enabled ==0)
		{		
			if($adtype_str !="")
			$adtype_str.=' AND ';			
			
			$adtype_str.=' ab.type <>1 ';		
		}		
		
		if($cpv_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';
			
			$adtype_str.=' ab.type <>5 ';	
		}			

		if($adtype_str !="")
		$adtype_str=' AND (('.$adtype_str.') OR ab.banner_type IS NULL) ';			
		
		
		$query4="select a.*,ab.name as abname,ab.type,ab.banner_type from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE a.pubid=? ".$adpricing_str." ".$sidquery." ".$adtype_str." ORDER BY a.id desc";
		
	
		$pagination3 = new Pagination($query4,array($id));
		$res4=$pagination3->get_result();
		$this->set_result("res4",$res4);
		$this->set_variable("pagination3",$pagination3->links(),0);
		
		
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
			
			if($statusadv==-4 && $statuspub==-4)
			{
			if($frompg ==1)	
			header("Location: ".$this->make_url("user/list/".$status."/".$type."/".$pg));
			else 
			header("Location: ".$this->make_url("user/profile/".$uid."/0"));
			
			}
			else
			header("Location: ".$this->make_url("user/status_mail/").$uid."/".$statusadv."/".$statuspub."/".$frompg."/".$status."/".$type."/".$pg);
		
		}
		
		
		
		$usname=$this->get_user_name($uid);
		
		$this->set_variable('usname', $usname);
		
		
		$this->set_variable("uid",$uid);
		$this->set_variable("frompg",$frompg);
		
		
		
	}
	

	
	function status_mail_action()
	{
		$this->set_title($this->get_label('change user status'));
		$uid=$this->read_page_param(1);
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
		$pg=$this->read_page_param(7);
		
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
		
		
		
		$db= DAL::get_instance();
		
		
		$id=4;
	
		$yes_no=0;
		if($_POST)
		{
			
			$subject=$this->read_post_param('subject');
			$message=$this->read_post_param('message');
			$uid=$this->read_post_param('uid');
			
			
			if(DEMO_MODE && $uid ==1)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('user/list'),0);
				exit;
			}
			
			
			
			
			$statusadv=$this->read_post_param('statusadv');
			$statuspub=$this->read_post_param('statuspub');
			$frompg=$this->read_post_param('frompg');
	
			if(isset($_POST['yes_no']))
			$yes_no=1;
				
			if($statusadv==-4 && $statuspub==-4)
			{
				if($frompg ==1)
				header("Location: ".$this->make_url("user/list/".$status."/".$type."/".$pg));
				else
				header("Location: ".$this->make_url("user/profile/".$uid."/0"));
			
			}
			
			
			if($statusadv!=-4)
			{
				
				
				
				//////////////////////////////////////////
				
				$old_adv_status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));
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
						
				}
					
			}
			/********* code to update user_status field in ads table when activate/block a user -Start   ********/
			
			$ad_user=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."ads WHERE uid=?",array($uid));
			if($ad_user->num_records > 0)
			{
				while($row=$ad_user->fetch_assoc())
				{
					
					$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET user_status = ? WHERE id=?",array($statusadv,$row['id']));
						
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
			
			///////////////////////
			
			
			
			
			}
			
			
			
			if($statuspub!=-4)
			$res_query1=$db->execute_query("update ".TABLE_PREFIX."users set pub_status=? where id=?",array($statuspub,$uid));
			
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
			
			/////////////////////////////////////////////////////////////////////////////////////////////
			
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
				$this->flash($this->get_message('user status updated'), $this->make_url('user/list/'.$status.'/'.$type.'/'.$pg));
				else
				$this->flash($this->get_message('user status updated'), $this->make_url("user/profile/".$uid."/0"));
				
				}
				else 
				{
					if($frompg ==1)
					$this->flash($this->get_message('error occured'), $this->make_url('user/list/'.$status.'/'.$type.'/'.$pg),0);
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
				header("Location: ".$this->make_url("user/list/".$status."/".$type."/".$pg));
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
		$pg=$this->read_page_param(4);
		
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
		
		
		$db= DAL::get_instance();
		$id=6;
		
		$yes_no=0;
		if($_POST)
		{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');
				$uid=$this->read_post_param('uid');
				
				
				if(DEMO_MODE && $uid ==1)
				{
					$this->flash($this->get_message('demo mode'), $this->make_url('user/list'),0);
					exit;
				}
				
			
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
					if($drowdata['type'] ==2 || $drowdata['type'] ==5 || $drowdata['type'] ==10 || $drowdata['type'] ==11)
					{
						unlink("../".DATA_DIR."/".$drowdata['id']."_".$drowdata['banner']);
						
						if($drowdata['type'] ==2 && isset($drowdata['expandable']) && $drowdata['expandable'] ==1)
						{
							$expandable_banner=$drowdata['expandable_banner'];
							
							unlink("../".DATA_DIR.'/'.$drowdata['id'].'_exp_'.$expandable_banner);
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
					
					
					
					if(($affiliate_enabled ==1 || $affiliate_enabled ==0) && $drowdata['display_type'] ==12)
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
				
				
				
				
				
				$plogo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."users where id=?",array($uid));
				if($plogo !='')
				{
					unlink("../".DATA_DIR."/".LOGO_DIR."/".$uid."/".$plogo);
					rmdir("../".DATA_DIR."/".LOGO_DIR."/".$uid."/");
				}
				
				
				
				// newsletter addon support///
				
				$newsletteraddon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
				$newsletter_enable=$this->get_addon_status($newsletteraddon_folder.'_enabled');
				
				if($newsletter_enable ==1){
		
				
				
					$existing_email= $db->execute_query("select email,adv_status,pub_status  from ".TABLE_PREFIX."users where id = ?",array($uid));
		
					$row=$existing_email->fetch_assoc();
				
					if($existing_email !="")
					{
			
						$id =$db->read_single_column("select id from ".TABLE_PREFIX."email_address where email = ?",array($row['email']));
										
										
						$db->execute_query("delete from ".TABLE_PREFIX."additional_field_value where ea_id=?",array($id));
						$db->execute_query("delete from ".TABLE_PREFIX."address_list_mapping where ea_id=?",array($id));
						$db->execute_query("delete from ".TABLE_PREFIX."email_address where id=?",array($id));
						
					
					}
				}
				//////////////////////////
				
				$db->execute_query("delete from ".TABLE_PREFIX."users where id=?",array($uid));
				$db->execute_query("delete from ".TABLE_PREFIX."ads where uid=?",array($uid));
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
				$db->execute_query("delete from ".TABLE_PREFIX."sites where pid=?",array($uid));
				
				
				$slist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE pubid=?",array($uid));
				
				$db->execute_query("delete from ".TABLE_PREFIX."adunit where pubid=?",array($uid));
				
				
				if($sponsored_enabled ==1 || $sponsored_enabled ==0)
				{
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE uid=? AND (status=-1 OR status=0 OR status=1)",array($uid));
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE publisher=? AND (status=-1 OR status=0 OR status=1)",array($uid));
					

					while($slistrow=$slist->fetch_assoc())
					{
						$db->execute_query("DELETE FROM ".TABLE_PREFIX."packages WHERE posid=?",array($slistrow['id']));
					}
				}
				
				$db->execute_query("delete from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));
				
				
				$db->execute_query("delete from ".TABLE_PREFIX."testimonial where uid=?",array($uid));				
				
				
				$pay_pend_row=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."advertiser_payment_summary where uid=? and status=-1",array($uid));
				
				$pa_str="";
				if($pay_pend_row->get_num_records() >0)
				{
					while($pay_pend_data=$pay_pend_row->fetch_assoc())
					{
						$pa_str.=" paymentid=".$pay_pend_data['id'].' or';
					}
				}
				if($pa_str !="")
				{
					$pa_str=substr($pa_str,0,-2);
					$db->execute_query("delete from ".TABLE_PREFIX."advertiser_payment_details where ".$pa_str." ");
				}
				
				
				
				$with_pend_row=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."publisher_withdrawal_summary where uid=? and status=-1 and payment_mode <> 5",array($uid));
				
				
				$wt_str="";
				if($with_pend_row->get_num_records() >0)
				{
					while($with_pend_data=$with_pend_row->fetch_assoc())
					{
						$wt_str.=" paymentid=".$with_pend_data['id'].' or';
					}
				}
				if($wt_str !="")
				{
					$wt_str=substr($wt_str,0,-2);
					$db->execute_query("delete from ".TABLE_PREFIX."publisher_withdrawal_details where ".$wt_str." ");
				}
				
				
				
				$db->execute_query("delete from ".TABLE_PREFIX."advertiser_payment_summary where uid=? and status=-1",array($uid));
				$db->execute_query("delete from ".TABLE_PREFIX."publisher_withdrawal_summary where uid=? and status=-1",array($uid));
				
				
				
				$this->flash($this->get_message('user deleted'), $this->make_url('user/list/'.$status.'/'.$type.'/'.$pg));
				
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
																			
					
				if(($adv_status==1 && $pub_status!=1) || ($adv_status==1 && $pub_status==1))
				header("Location: ".$this->make_base_url("user/advertiser_home"));
				else if($adv_status!=1 && $pub_status==1)
				header("Location: ".$this->make_base_url("user/publisher_home"));
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
	
	$mngr_str='';
	$usr_str1='';
	$qrstr="";
	
	$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
	$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
	
	if($this->get_addon_status('subadmin_enabled') ==1)
	{
		if($admintype ==3)
		{
			$mngr_str=" and pub_managerid=".$adminid;
		
			$usr_str=$this->get_users_under_mngr($admintype,$adminid);
			
			if($usr_str !='')
			$usr_str1=" and uid in (".$usr_str.") ";
			else 
			$usr_str1=" and uid=-2 ";
		}
	}	
	
	
	if($_POST)
	{
		$payment=$this->read_post_param('payment');
		$status=$this->read_post_param('status');
		$pub=$this->read_post_param("pub");
		$payment_type=$this->read_post_param('payment_type');
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
		$pub=0;
		
		$exp=explode("-",$payment_type);
		if($exp[0]=="page")
		$payment_type=2;		
	}
	
	$referral_enabled=$this->get_addon_status('referral_enabled');
	$this->set_variable('referral_enabled', $referral_enabled);	
	
	
	
	if($pub =="")
	$pub=0;
	
	if($status=="")
	$status=4;
	
	if($payment=="")
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
	$querydata.=" AND status=".$status." ";

	if($payment !=0)
	$querydata.=" AND payment_mode=".$payment." ";

	if($payment_type &&  $payment_type !=2)
	$querydata.=" AND withdrawal_type=".$payment_type." ";
    
	if($pdate !="" && $pdate > 0)
    $querydata.="AND process_time >= ".$pdate1." AND process_time < ".$pdate2." ";
    
	if($rdate !="" && $rdate > 0)
    $querydata.="AND request_time >= ".$rdate1." AND request_time < ".$rdate2." ";
    
	
	if($pub >0)
	$querydata.=" AND uid=".$pub." ";
	
	
	
	$query3 = "SELECT * FROM ".TABLE_PREFIX."publisher_withdrawal_summary WHERE id >0 ".$querydata." ".$usr_str1." ORDER BY id desc";
	$pagination2 = new Pagination($query3);
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
	
	
	$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users ".$statusstring." ".$mngr_str."  order by id ASC");
	$this->set_result("res1",$res1);
	
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
	$usr=0;
	
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
	 				$submitdata=trim($this->read_post_param($row123['Field']));
	 		
	 				$this->set_variable($row123['Field'],$submitdata);
	 		
	 				if($submitdata !='' && $flag ==0)
	 				{
	 					if(strtolower($row123['Type']) =='int(11)' && !is_numeric($submitdata))
	 					$flag=2;
	 		
	 					if($flag ==0)
	 					{
 							if($string4 !='')
	 						$string4.=',';
	 		
	 						$string4.=$row123['Field']."='".$submitdata."' ";
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
        $payment=$this->read_post_param('payment');
    }
    
    if($payment=="")
        $payment=0;
        
        
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