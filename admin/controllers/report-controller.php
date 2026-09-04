<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

if(file_exists(ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php"))
include_once ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php";

if(file_exists(ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php"))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";

class ReportController extends ApplicationController
{
	
	function before_execute()
	{		
		parent::before_execute();
		if(!(LoginHelper::validate_admin_login()))
		{
			$this->flash($this->get_message('login failed'), $this->make_url('index/index'),0);
		}
		
		
		if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==1)
		{
			if($this->get_addon_status('subadmin_enabled') ==1 && isset($GLOBALS['privilege']))
			$privilege=$GLOBALS['privilege'];
			else
			$privilege=array();
		
		
			
			
		
			if(!isset($privilege['sr_1']) && !isset($privilege['sr_2']) && !isset($privilege['sr_3']) && !isset($privilege['sr_4']) && !isset($privilege['ur_1']) && !isset($privilege['ur_2']) && !isset($privilege['ur_3']) && !isset($privilege['ur_4']) && !isset($privilege['ur_5']))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if($this->get_action() !="overall" && $this->get_action() !="clicks" && $this->get_action() !="profit" && $this->get_action() !="verify" && $this->get_action() !="advertisers" && $this->get_action() !="publishers" && $this->get_action() !="ads" && $this->get_action() !="adcodes" && $this->get_action() !="adcodes_publisher" && $this->get_action() !="adcodes_detailed")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['sr_1']) && $this->get_action() =="overall")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['sr_2']) && $this->get_action() =="clicks")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['sr_3']) && $this->get_action() =="profit")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['sr_4']) && $this->get_action() =="verify")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ur_1']) && $this->get_action() =="advertisers")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ur_2']) && $this->get_action() =="publishers")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ur_3']) && $this->get_action() =="ads")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ur_4']) && ($this->get_action() =="adcodes" || $this->get_action() =="adcodes_detailed"))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ur_5']) && ($this->get_action() =="adcodes_publisher" || $this->get_action() =="adcodes_detailed"))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
		}
		
		
		
		
	}
	
	
	function advertisers_action()
	{
	
		$this->set_title($this->get_label('advertisers statistics'));
		$db= DAL::get_instance();
	
		$mngr_str='';$usr_str1='';
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==2)
			{
				$mngr_str=" and adv_managerid=".$adminid;
			
			}
		}
		
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$adv=$this->read_post_param("adv");
		}
		else
		{
			$duration=1;
			$adv=0;
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
	
		if($duration=="" || $duration==0)
			$duration=1;
	
		if($adv==0)
			$adv_string="";
		else
			$adv_string=" and id='$adv' ";
		
		$this->set_variable("duration",$duration);
		$this->set_variable("adv",$adv);
		
	
		$num="select id from ".TABLE_PREFIX."users where adv_status='1' ".$adv_string." ".$mngr_str." LIMIT 0,1";
		$res=$db->execute_query($num);
		$number=$res->get_num_records();
		$this->set_variable("number",$number);
	
	
		$query = "SELECT id,username FROM ".TABLE_PREFIX."users where adv_status='1' ".$adv_string." ".$mngr_str." ORDER BY id desc";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	
		$sql1="select id,username from ".TABLE_PREFIX."users where adv_status=1 ".$mngr_str." order by id ASC";
		$res1=$db->execute_query($sql1);
		$this->set_result("res1",$res1);
	}
	function publishers_action()
	{
		$this->set_title($this->get_label('publishers statistics'));
		$db= DAL::get_instance();
	
		$mngr_str='';
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==3)
			{
				$mngr_str=" and pub_managerid=".$adminid;
			
				
			}
		}
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$pub=$this->read_post_param("pub");
		}
		else
		{
			$duration=1;
			$pub=-1;
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
	
		if($duration=="" || $duration==0)
			$duration=1;
		
		if($pub=="")
			$pub=-1;
		
		
		if($pub==-1)
			$pub_string="";
		else
			$pub_string=" and id='$pub' ";
		

		$this->set_variable("duration",$duration);
		$this->set_variable("pub",$pub);
		
		
	
		
	
		$res=$db->execute_query("select id from ".TABLE_PREFIX."users where pub_status='1' ".$pub_string." ".$mngr_str." LIMIT 0,1");
		$number=$res->get_num_records();
		$this->set_variable("number",$number);
	
	
		$query = "SELECT id,username FROM ".TABLE_PREFIX."users where pub_status='1' ".$pub_string." ".$mngr_str." ORDER BY id desc";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where pub_status=1  ".$mngr_str." order by id ASC");
		$this->set_result("res1",$res1);
	}
	
	
	
	
	
	
	
	function adcodes_action()
	{
		$this->set_title($this->get_label('manage adunits statistics'));
		$db= DAL::get_instance();
	
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$sid=intval($this->read_post_param("sid"));
			$adpricing=$this->read_post_param("adpricing");
		}
		else
		{
			$duration=1;
			$sid=intval($this->read_page_param(1));
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
		
		
		if($adpricing =='')
		$adpricing=-1;
		
		$this->set_variable("adpricing",$adpricing);
		
		
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		if($duration=="" || $duration==0)
		$duration=1;
		
		

		
		$sidstring='';
		if($this->get_addon_status('category-targeting_enabled') ==1)
		{
			if($sid >0)
			$sidstring=' AND sid='.$sid.' ';
		}
		
		
	
		$this->set_variable("duration",$duration);
		$this->set_variable("sid",$sid);
		
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');			
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		
		
		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;				
		
		
		
		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" AND display_type='".$adpricing."' ";
		
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
		
		if($cpv_enabled !=1)
		$adpricing_str.=' AND display_type <>13 ';			
		
		
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
		
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
		
		
		
		
		$res=$db->execute_query("select a.id from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE pubid=0 ".$adpricing_str." ".$sidstring." ".$adtype_str." LIMIT 0,1");
		$number=$res->get_num_records();
		$this->set_variable("number",$number);
		
		
		$query="select a.*,ab.name as abname,ab.type,ab.banner_type from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE pubid=0 ".$adpricing_str." ".$sidstring." ".$adtype_str." ORDER BY a.id desc";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	
	}
	function adcodes_detailed_action()
	{
		$this->set_title($this->get_label('manage adunits statistics'));
	
		$db= DAL::get_instance();
		$aduid=$this->read_page_param(1);
		$uid=$db->read_single_column("select pubid from ".TABLE_PREFIX."adunit where id=?",array($aduid));
	
		if($uid =="")
		$uid=0;
	
		if(!$this->get_adunit_available_check($aduid,$uid))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
			exit;
		}
	
		if($uid >0)
		$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		else
		$username="Admin";
	
		$name=$db->read_single_column("select name from ".TABLE_PREFIX."adunit where id=?",array($aduid));
		$adpricing=$db->read_single_column("select display_type from ".TABLE_PREFIX."adunit where id=?",array($aduid));
		
		
		
		
		$adp=$this->get_adunit_preference_value($aduid);
		
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		
		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;		
		
		
		if($adp ==0 && $cpc_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);			
		
		if($adp ==1 && $cpm_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
		
		if($adp ==3 && $sponsored_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
		
		if($adp ==4 && $cpc_enabled !=1 && $cpm_enabled !=1 && $cpa_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);			
		
		if($adp ==6 && $cpa_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
		
		if($adp ==9 && $pop_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
		
		if($adp ==12 && $affiliate_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);	

		if($adp ==13 && $cpv_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);		
		
		
	
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$tab=$this->read_post_param("tab");
		}
		else
		{
			$duration=1;
			$tab=1;
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
	
		if($duration=="" || $duration==0)
		$duration=1;
	
	
		if($tab=="" || $tab==0)
		$tab=1;
	
	
	
		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
		$this->set_variable("name",$name);
		$this->set_variable("tab",$tab);
		$this->set_variable("aduid",$aduid);
		$this->set_variable("username",$username);
		$this->set_variable("adpricing",$adpricing);
		
		
		
	}
	
	function clicks_action()
	{
		$this->set_title($this->get_label('click analysis'));
		$db= DAL::get_instance();
	
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$clicktype=$this->read_post_param("clicktype");
			$adv=$this->read_post_param("adv");
			$pub=$this->read_post_param("pub");
			$fraudtype=$this->read_post_param("fraudtype");
	
		}
		else
		{
			$duration=1;
			$clicktype=1;
			$adv=0;
			$pub=-1;
			$fraudtype=0;
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
		if($duration=="" || $duration==0)
			$duration=1;
	
		if($clicktype=="" || $clicktype==0)
			$clicktype=1;
	
	
	
	
	
		if($adv==0)
		$adv_string="";
		else
		$adv_string=" AND uid=".$adv." ";
	
	
		if($pub==-1)
		$pub_string="";
		else
		$pub_string=" AND pid=".$pub." ";
	
	
		if($clicktype==2)
		{
			if($fraudtype==0)
			$fraud_string="";
			else
			$fraud_string=" AND fraudtype=".$fraudtype." ";
	
		}
		else
			$fraud_string="";
	
	
	
	
		
			
			
		$day_begin1 =date("Y",time());
		$day_begin1.=date("m",time());
		$day_begin1.=date("d",time());
				
		
			
		$time=$day_begin1;
		
		
		if($duration ==2)
		{
			for($i=0;$i < 13;$i++)
			{
		   		 $time=$this->get_previous_day($time);
			}
		}
		else if($duration==3)
		{
			for($i=0;$i < 29;$i++)
			{
		   		 $time=$this->get_previous_day($time);
			}
		}
		else if($duration==6)
	   	$time=$this->get_previous_day($time);	
			
			
	   	
			
		if($duration==6)
		$timestring=" AND time >= ".$time."00 AND time <= ".$time."23 ";
		else
		$timestring=" AND time >= ".$time."00 ";	
		
		
		
		
	
		$this->set_variable("duration",$duration);
		$this->set_variable("clicktype",$clicktype);
		$this->set_variable("adv",$adv);
		$this->set_variable("pub",$pub);
		$this->set_variable("fraudtype",$fraudtype);
	
	
		if($clicktype==1)
		$data="select * from ".TABLE_PREFIX."dailyclicks_backup where click_type=0 ".$timestring." ".$adv_string.$pub_string." order by time DESC ";
		else if($clicktype==2)
		$data="select * from ".TABLE_PREFIX."fraudclicks where click_type=0 ".$timestring." ".$adv_string.$pub_string.$fraud_string." order by time DESC ";
	
	
		$data_result=$db->execute_query($data);
		$number=$data_result->get_num_records();
		$this->set_variable("number",$number);
	
	
	
		if($clicktype==1)
		$data_query="select * from ".TABLE_PREFIX."dailyclicks_backup where click_type=0 ".$timestring." ".$adv_string.$pub_string." order by time DESC ";
		else if($clicktype==2)
		$data_query="select * from ".TABLE_PREFIX."fraudclicks where click_type=0 ".$timestring." ".$adv_string.$pub_string.$fraud_string." order by time DESC ";
	
		$pagination = new Pagination($data_query);
		$data_query_data=$pagination->get_result();
		$this->set_result("data_query_data",$data_query_data);
		$this->set_variable("pagination",$pagination->links(),0);
	
	
	
		$res=$db->execute_query("select id,username from ".TABLE_PREFIX."users where adv_status=1 order by id ASC");
		$this->set_result("res",$res);
	
		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where pub_status=1 order by id ASC");
		$this->set_result("res1",$res1);
	
	}
	function overall_action()
	{
	
		$this->set_title($this->get_label('overall statistics'));
		$db= DAL::get_instance();
	
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$tab=$this->read_post_param("tab");
		}
		else
		{
			$duration=1;
			$tab=1;
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
		if($duration=="" || $duration==0)
			$duration=1;
	
	
		if($tab=="" || $tab==0)
			$tab=1;
	
	
	
		$this->set_variable("duration",$duration);
		$this->set_variable("tab",$tab);

	}
	function verify_action()
	{
		$this->set_title($this->get_label('check statistics'));
		$db= DAL::get_instance();
	}
	
	
	
	function profit_action()
	{
		$this->set_title($this->get_label('profit statistics'));
		$db= DAL::get_instance();
	
	
		//**************advertiser*******************//
		//$check=$db->read_single_column("select COALESCE(sum(amount),0) as amounts from ".TABLE_PREFIX."advertiser_payment_summary where status='1' and payment_type='1'");
		//$bank=$db->read_single_column("select COALESCE(sum(amount),0) as amounts from ".TABLE_PREFIX."advertiser_payment_summary where status='1' and payment_type='2'");
		//$paypal=$db->read_single_column("select COALESCE(sum(amount),0) as amounts from ".TABLE_PREFIX."advertiser_payment_summary where status='1' and payment_type='3'");
		//$transfer=$db->read_single_column("select COALESCE(sum(amount),0) as amounts from ".TABLE_PREFIX."advertiser_payment_summary where status='1' and payment_type='5'");

		
		$datatransfer=$db->execute_query("select payment_type,COALESCE(sum(amount+fee+tax),0) as amounts from ".TABLE_PREFIX."payment_gateway pg LEFT OUTER JOIN ".TABLE_PREFIX."advertiser_payment_summary ps ON pg.id=ps.payment_type where ps.status=1 and payment_type <>4 AND pg.id <>4 GROUP BY pg.id");
		$this->set_result('datatransfer',$datatransfer);
		
		//**************advertiser*******************//
	
		//**************bonus*******************//
		$bonus=$db->read_single_column("select COALESCE(sum(amount),0) as amounts from ".TABLE_PREFIX."advertiser_payment_summary where status='1' and payment_type='4'");
		//**************bonus*******************//
	
	
	
		//**************publisher*******************//
		$withdrawed=$db->read_single_column("select COALESCE(sum(amount+fee+tax),0) as amounts from ".TABLE_PREFIX."publisher_withdrawal_summary where (status=1 OR status=-1) AND withdrawal_type =0");
		$account=$db->read_single_column("select COALESCE(sum(pub_account_balance),0) as amounts from ".TABLE_PREFIX."users");
	
		//**************publisher*******************//
	
	
		
		
		
		//**************refund*******************//
		

			$refunded_amount=$db->read_single_column("SELECT sum(amount) FROM ".TABLE_PREFIX."refund where type =0");
			$refunded_bonus=$db->read_single_column("SELECT sum(amount) FROM ".TABLE_PREFIX."refund where type =1");
			
			
			if($refunded_amount =='')
			$refunded_amount=0;
			
			
			if($refunded_bonus =='')
			$refunded_bonus=0;
			
			
			$this->set_variable("refunded_amount",$refunded_amount);
			$this->set_variable("refunded_bonus",$refunded_bonus);
		
		
		//**************refund*******************//
		
		
		
		//**************Referral*******************//
		$referral_enabled=$this->get_addon_status('referral_enabled');

		$referralwithdrawed=0;
		$referral_balance=0;

		if($referral_enabled ==1)
		{
			$referralwithdrawed=$db->read_single_column("select COALESCE(sum(amount+fee+tax),0) as amounts from ".TABLE_PREFIX."publisher_withdrawal_summary where (status=1 OR status=-1) AND withdrawal_type =1");
			$referral_balance=$db->read_single_column("select COALESCE(sum(referral_balance),0) as refamount from ".TABLE_PREFIX."users");
		}

		$this->set_variable("referral_enabled",$referral_enabled);
		$this->set_variable("referralwithdrawed",$referralwithdrawed);
		$this->set_variable("referral_balance",$referral_balance);


		//**************Referral*******************//		
		
		
		
		
		
		
	
		//$this->set_variable("check",$check);
		//$this->set_variable("bank",$bank);
		//$this->set_variable("paypal",$paypal);
		//$this->set_variable("transfer",$transfer);
		$this->set_variable("bonus",$bonus);
		$this->set_variable("withdrawed",$withdrawed);
		$this->set_variable("account",$account);
	
	}
	function adcodes_publisher_action()
	{
		$this->set_title($this->get_label('manage adcode statistics'));
		
		$mngr_str='';$usr_str1='';$qrstr="";
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==3)
			{
				$mngr_str=" and pub_managerid=".$adminid;
			
				$usr_str=$this->get_users_under_mngr($admintype,$adminid);
				if($usr_str!='')
					$usr_str1=" and pubid in (".$usr_str.")";
					else  $usr_str1=" and pubid =-2 ";
			}
		}
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$pub=$this->read_post_param("pub");
			$adpricing=$this->read_post_param("adpricing");
		}
		else
		{
			$duration=1;
			$pub=-1;
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
		
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		
		
		if($adpricing =='')
		$adpricing=-1;
		
		$this->set_variable("adpricing",$adpricing);
		
		
		if($duration=="" || $duration==0)
			$duration=1;
		
		
		if($pub=="")
		$pub=-1;
		
		
		if($pub==-1)
		$pub_string="";
		else
		$pub_string=" and pubid='$pub' ";
		
		
		$this->set_variable("duration",$duration);
		$this->set_variable("pub",$pub);
		
		$db= DAL::get_instance();
		
		
		
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
		
		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;	

		
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
		
		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" AND display_type='".$adpricing."' ";
		
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
		
		
		$res6=$db->execute_query("select a.id from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE pubid<>0 ".$adpricing_str." ".$pub_string." ".$adtype_str." LIMIT 0,1");
		$number11=$res6->get_num_records();
		$this->set_variable("number11",$number11);
		
		
		$query4="select a.*,ab.name as abname,ab.type,ab.banner_type from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE pubid<>0 ".$adpricing_str." ".$pub_string." ".$adtype_str."  ".$usr_str1." ORDER BY a.id desc";
		$pagination3 = new Pagination($query4);
		$res4=$pagination3->get_result();
		$this->set_result("res4",$res4);
		$this->set_variable("pagination3",$pagination3->links(),0);
		
		
		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where pub_status=1 ".$mngr_str." order by id ASC");
		$this->set_result("res1",$res1);
		
	}
	function ads_action()
	{
		$this->set_title($this->get_label('manage ad statistics'));
		$db= DAL::get_instance();
		$mngr_str='';$usr_str1='';
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==2)
			{
				$mngr_str=" and adv_managerid=".$adminid;
			
				$usr_str=$this->get_users_under_mngr($admintype,$adminid);
				if($usr_str!='')
					$usr_str1=" and uid in (".$usr_str.")";
				else $usr_str1=" and uid=-2 ";
			}
		}
		if($_POST)
		{
			$adtype=$this->read_post_param('type');
			$status=$this->read_post_param('status');
			$duration=$this->read_post_param("duration");
			$adv=$this->read_post_param("adv");
			$adpricing=$this->read_post_param("adpricing");
			
		}
		else
		{
			$adtype=0;
			$status=2;
			$duration=1;
			$adv=0;
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
		
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		
		
		$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
		$this->set_variable('ecommerce_enabled',$ecommerce_enabled);	

		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		$this->set_variable('text_ads_enabled',$text_ads_enabled);		
			
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');			
		
		
		$pop_addon_usage = 0;
		
		if($this->get_addon_status('pop-ads_enabled') == 1)
		$pop_addon_usage = intval(Configuration::get_instance()->read('pop_addon_usage'));
		
		$this->set_variable('pop_addon_usage',$pop_addon_usage);			
		
		
		
		if($duration=="" || $duration==0)
		$duration=1;
		
		
		if($adtype=="")
		$adtype=0;
		
		if($status=="")
		$status=2;
		
		if($adpricing=="")
		$adpricing=-1;
		
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
		$status_str=" AND a.status <> -2 AND a.status <> -1";
		else
		$status_str=" AND a.status='".$status."' ";
		
		
		if($adv==0)
		$adv_string="";
		else
		$adv_string=" and uid='$adv' ";
		
		
		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" and display_type='".$adpricing."' ";
		
		
		
		if($this->get_addon_status('cpc_enabled') !=1)
		$adpricing_str.=' AND display_type <>0 ';		
		
		if($this->get_addon_status('cpm_enabled') !=1)
		$adpricing_str.=' AND display_type <>1 ';
		
		if($this->get_addon_status('cpa_enabled') !=1)
		$adpricing_str.=' AND display_type <>6 ';
		
		if($this->get_addon_status('sponsored_enabled') !=1)
		$adpricing_str.=' AND display_type <>3 ';
		
		
		if($this->get_addon_status('pop-ads_enabled') !=1)
		$adpricing_str.=' AND display_type <>9 AND a.type <>9 ';
		
		if($this->get_addon_status('affiliate-ads_enabled') !=1)
		$adpricing_str.=' AND display_type <>12 ';
		
		if($cpv_enabled !=1)
		$adpricing_str.=' AND display_type <>13 ';			
		
		
		
		
		
		$this->set_variable("type",$adtype);
		$this->set_variable("status",$status);
		$this->set_variable("duration",$duration);
		$this->set_variable("adv",$adv);
		$this->set_variable("adpricing",$adpricing);
		
		
		
		
		$num="SELECT id FROM ".TABLE_PREFIX."ads a where uid<>0 AND ecommerce_parent =0 ".$adv_string.$adtype_str.$status_str.$adpricing_str." ".$usr_str1." LIMIT 0,1";
		$res4=$db->execute_query($num);
		$number=$res4->get_num_records();
		$this->set_variable("number",$number);
		
		
		$query11= "SELECT * FROM ".TABLE_PREFIX."ads a where uid<>0 AND ecommerce_parent =0 ".$adv_string.$adtype_str.$status_str.$adpricing_str .$usr_str1;
		$pagination = new Pagination($query11);
		$res11=$pagination->get_result();
		$this->set_result("res11",$res11);
		$this->set_variable("pagination",$pagination->links(),0);
		
		
		
		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where adv_status=1 ".$mngr_str." order by id ASC");

		$this->set_result("res1",$res1);
	}
	
	
	function top_list_action()
	{
		$this->set_title($this->get_label('toppers list'));
		$db= DAL::get_instance();
	
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$top=intval($this->read_post_param("top"));
			$sort=intval($this->read_post_param("sort"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$duration=1;
			$top=0;
			$sort=0;
			$from_date='';
			$to_date='';			
		}
		
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		
		
		if($duration=="" || $duration==0)
		$duration=1;
	
		if($from_date =='' && $duration ==7)
		$duration=1;
		
		if($top ==0)
		{
			if($from_date !='')  // for custom date range
			$results=$this->get_range_top_advertisers($from_date,$to_date,$sort);
			else
			$results=$this->get_top_advertisers($duration,$sort);
			
			
			$this->set_array("results",$results,array(0,1));
		}
		else if($top ==1)
		{
			if($from_date !='')  // for custom date range
			$results=$this->get_range_top_publishers($from_date,$to_date,$sort);
			else
			$results=$this->get_top_publishers($duration,$sort);
			
			$this->set_array("results",$results,array(0,1));
		}
		else if($top ==2 && $this->get_addon_status('category-targeting_enabled') ==1)
		{
			if($from_date !='')  // for custom date range
			$results=$this->get_range_top_sites($from_date,$to_date,$sort);
			else
			$results=$this->get_top_sites($duration,$sort);
			
			$this->set_array("results",$results,array(0,1));
		}
		
		$this->set_variable("duration",$duration);
		$this->set_variable("top",$top);
		$this->set_variable("sort",$sort);
	}
	
	function country_action()
	{
		$this->set_title($this->get_label('countrywise report'));
		$db= DAL::get_instance();
		
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$type=intval($this->read_post_param("type"));
			$adv=intval($this->read_post_param("adv"));
			$pub=intval($this->read_post_param("pub"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
			
		}
		else
		{
			$duration=1;
			$type=0;
			$adv=0;
			$pub=0;
			$from_date='';
			$to_date='';
		}

		if($from_date =='' && $duration ==7)
		$duration=1;
		
	
		$this->set_variable("duration",$duration);
		$this->set_variable("type",$type);
		$this->set_variable("adv",$adv);
		$this->set_variable("pub",$pub);
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		
		
		
		
		if($type ==0 || $type ==1)
		{
			if($type ==0)
			$adv=-1;
			
			
			
			if($from_date !='')  // for custom date range
			$results=$this->get_range_top_country_advertisers($from_date,$to_date,$adv);
			else
			$results=$this->get_top_country_advertisers($duration,$adv);
			
			
			$this->set_array("results",$results,array(0,1));
		}
		else 
		{
			if($from_date !='')  // for custom date range
			$results=$this->get_range_top_country_publishers($from_date,$to_date,$pub);
			else
			$results=$this->get_top_country_publishers($duration,$pub);
			
			$this->set_array("results",$results,array(0,1));
		}
		
		
		
		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where adv_status=1 order by id ASC");
		$this->set_result("res1",$res1);
		
		
		$res11=$db->execute_query("select id,username from ".TABLE_PREFIX."users where pub_status=1 order by id ASC");
		$this->set_result("res11",$res11);
		
		
		
	}
		
	
	
	
	
	
	
	
	
	
	
	
	
	
};
?>