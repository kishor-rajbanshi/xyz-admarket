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
		$db = DAL::get_instance();



		if($_POST)
		{
			$duration  = intval($this->read_post_param("duration"));
			$advertiser = $db->sanitize($this->read_post_param("username"));
			$from_date = $this->read_post_param("from_date");
			$to_date   = $this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$duration  = 1;
			$advertiser = "";
			$from_date = '';
			$to_date   = '';
			$sortBy     = "impression";
			$orderBy    = "desc";
		}

		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;
		else if($sortBy == "spend" || $sortBy == "adminprofit")
		$sorting = 2;
		else if($sortBy == "profit")
		$sorting = 3;
		else 
		$sorting = 0;
		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;
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

		


		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("advertiser",$advertiser);


		$this->set_variable("sortBy",$sortBy);


		$this->set_variable("orderBy",$orderBy);


//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $advertiser = '', $adtype = 0, $isAdvertiserAds = -1
		$reportResult  = $this->get_advertiser_statistics($timePeriod, 1, -1, -1, 0, 0, 5, $sorting, 0, 0, "", $ordering, $advertiser);



		$this->set_array("reportResult",$reportResult);
	}

	function publishers_action()
	{
		$this->set_title($this->get_label('publishers statistics'));
		$db = DAL::get_instance();



		if($_POST)
		{
			$duration  = intval($this->read_post_param("duration"));
			$publisher = $db->sanitize($this->read_post_param("username"));
			$from_date = $this->read_post_param("from_date");
			$to_date   = $this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$duration  = 1;
			$publisher       = "";
			$from_date = '';
			$to_date   = '';
			$sortBy     = "impression";
			$orderBy    = "desc";
		}
		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;
		else if($sortBy == "spend")
		$sorting = 2;
		else if($sortBy == "profit" || $sortBy == "adminprofit")
		$sorting = 3;
		else 
		$sorting = 0;
		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;

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




		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("publisher",$publisher);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);


//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $publisher = '', $isPublisherData = -1
		$reportResult  = $this->get_publisher_statistics($timePeriod, 1, -1, -1, 0, 0, 6, $sorting, 0, 0, "", $ordering, $publisher);
		$this->set_array("reportResult",$reportResult);
	}


	function adcodes_action()
	{
		$this->set_title($this->get_label('manage adunits statistics'));
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$sid=intval($this->read_post_param("sid"));
			$adpricing=intval($this->read_post_param("adpricing"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$duration=1;
			$sid=intval($this->read_page_param(1));
			$adpricing=-1;
			$from_date='';
			$to_date='';
			$sortBy    = "impression";
			$orderBy   = "desc";
		}

		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;
		else if($sortBy == "spend")
		$sorting = 2;
		else if($sortBy == "profit" || $sortBy == "adminprofit")
		$sorting = 3;
		else 
		$sorting = 0;

		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;

		if($from_date != "" && $to_date == "")
		$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

		if($duration == 7 && ($from_date == "" || $to_date == ""))
		{
			$duration  = 1;

			$from_date = "";
			$to_date   = "";
		}

		if($adpricing =='')
		$adpricing=-1;
		
		if($duration == 0)
		$duration=1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}

		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("sid",$sid);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);
	
		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $publisher = '', $isPublisherData = -1
		$reportResult  = $this->get_publisher_statistics($timePeriod, 1, $adpricing, 0, 0, $sid, 5, $sorting, 0, 0, "", $ordering, "", 0);
		$this->set_array("reportResult",$reportResult);	
	}


	function adcodes_publisher_action()
	{
		$this->set_title($this->get_label('manage adcode statistics'));
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration=intval($this->read_post_param("duration"));
			$publisher=$db->sanitize($this->read_post_param("username"));
			$adpricing=intval($this->read_post_param("adpricing"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$duration=1;
			$publisher="";
			$adpricing=-1;
			$from_date='';
			$to_date='';
			$sortBy    = "impression";
			$orderBy   = "desc";
		}
		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;
		else if($sortBy == "spend")
		$sorting = 2;
		else if($sortBy == "profit" || $sortBy == "adminprofit")
		$sorting = 3;
		else 
		$sorting = 0;
		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;

		if($from_date != "" && $to_date == "")
		$to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

		if($duration == 7 && ($from_date == "" || $to_date == ""))
		{
			$duration  = 1;

			$from_date = "";
			$to_date   = "";
		}
		

		if($adpricing === '')
		$adpricing=-1;


		if($duration == 0)
		$duration = 1;

        $timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("duration",$duration);
		$this->set_variable("publisher",$publisher);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);

	
		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $publisher = '', $isPublisherData = -1
		$reportResult  = $this->get_publisher_statistics($timePeriod, 1, $adpricing, -1, 0, 0, 5, $sorting, 0, 0, "", $ordering, $publisher, 1);
		$this->set_array("reportResult",$reportResult);	
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


		$adcodeRow  = $db->execute_query("select name,adcode_type,display_type from ".TABLE_PREFIX."adunit where id=?",array($aduid));
		$adcodeData = $adcodeRow->fetch_assoc();

		$name 		= $adcodeData['name'];
		$adcodeType = $adcodeData['adcode_type'];
		$adpricing  = $adcodeData['display_type'];

		$adp=$this->get_adunit_preference_value($aduid);

		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$cpp_enabled=$this->get_addon_status('cpp_enabled');

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

		if($adp ==18 && $cpp_enabled !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);

		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$tab=$this->read_post_param("tab");
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$duration=1;
			$tab=1;
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


		if($tab=="" || $tab==0)
		$tab=1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}


		$dbResult = $this->get_publisher_statistics($timePeriod, 1, $adp, $uid, $aduid);
	    	$this->set_array("reportResult",$dbResult);


		$dbResultTimeperiod= $this->get_publisher_timeperiod_statistics($timePeriod, 1, $adp, $uid, $aduid);
		$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);


		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
		$this->set_variable("name",$name);
		$this->set_variable("tab",$tab);
		$this->set_variable("aduid",$aduid);
		$this->set_variable("username",$username);
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("adcodeType",$adcodeType);

	}

	function clicks_action()
	{
		$this->set_title($this->get_label('click analysis'));
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$clicktype=$this->read_post_param("clicktype");
			$type=$this->read_post_param("type");
			$search_text=$db->sanitize($this->read_post_param("username"));
			$fraudtype=intval($this->read_post_param("fraudtype"));

		}
		else
		{
			$duration=1;
			$clicktype=1;
			$type=0;
			$search_text="";
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





		 if($type==1)
		 {
		     $search_string=" AND u.username LIKE '".$search_text."%' ";
		     $join_string=" on u.id=c.uid ";
		 }
		 else if($type==2)
		 {
		     $search_string=" AND u.username LIKE '".$search_text."%' ";
		     $join_string=" on u.id=c.pid ";
		 }
		 else
		 {
		     $search_string="";
		     $join_string=" on u.id=c.uid ";
		 }




		if($clicktype==2)
		{
			if($fraudtype==0)
			$fraud_string="";
			else
			$fraud_string=" AND c.fraudtype=".$fraudtype." ";

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
		$timestring=" AND c.time >= ".$time."00 AND c.time <= ".$time."23 ";
		else
		$timestring=" AND c.time >= ".$time."00 ";


		if($search_text=="")
		    $type=0;


		$this->set_variable("duration",$duration);
		$this->set_variable("clicktype",$clicktype);
		$this->set_variable("type",$type);
		$this->set_variable("username",$search_text);
		$this->set_variable("fraudtype",$fraudtype);





		if($clicktype==1)
	        $pagination = new Pagination("select c.*,u.username from ".TABLE_PREFIX."dailyclicks_backup c INNER JOIN ".TABLE_PREFIX."users u ".$join_string." where c.click_type=0 ".$search_string.$timestring."  order by c.time DESC ");
		else if($clicktype==2)
		$pagination = new Pagination("select c.*,u.username from ".TABLE_PREFIX."fraudclicks c INNER JOIN ".TABLE_PREFIX."users u ".$join_string." where click_type=0 ".$search_string.$timestring." ".$fraud_string." order by c.time DESC ");


		$data_query_data=$pagination->get_result();
		$this->set_result("data_query_data",$data_query_data);
		$this->set_variable("pagination",$pagination->links(),0);
	}
	function overall_action()
	{
		$this->set_title($this->get_label('overall statistics'));
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$tab=$this->read_post_param("tab");
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$duration=1;
			$tab=1;
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


		if($tab=="" || $tab==0)
		$tab=1;

		if($duration=="" || $duration==0)
		$duration=1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}

		$dbResult = $this->get_advertiser_statistics($timePeriod, 1);
	  	$this->set_array("reportResult",$dbResult);

      	$dbResultTimeperiod = $this->get_advertiser_timeperiod_statistics($timePeriod, 1);
      	$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);


		$this->set_variable("duration",$duration);
		$this->set_variable("tab",$tab);

	}
	function verify_action()
	{
		$this->set_title($this->get_label('check statistics'));
		$db= DAL::get_instance();



		$dbResultAdvertiserDaily = $this->get_advertiser_timeperiod_statistics(array(3), 1, -1, -1, 0, 1);
		$this->set_array("reportResultAdvertiserDaily",$dbResultAdvertiserDaily);

		$dbResultAdvertiserMonthly = $this->get_advertiser_timeperiod_statistics(array(4), 1, -1, -1, 0, 1);
		$this->set_array("reportResultAdvertiserMonthly",$dbResultAdvertiserMonthly);

		$dbResultAdvertiserYearly = $this->get_advertiser_timeperiod_statistics(array(5), 1, -1, -1, 0, 1);
		$this->set_array("reportResultAdvertiserYearly",$dbResultAdvertiserYearly);


		$dbResultPublisherDaily = $this->get_publisher_timeperiod_statistics(array(3), 1);
		$this->set_array("reportResultPublisherDaily",$dbResultPublisherDaily);

		$dbResultPublisherMonthly = $this->get_publisher_timeperiod_statistics(array(4), 1);
		$this->set_array("reportResultPublisherMonthly",$dbResultPublisherMonthly);

		$dbResultPublisherYearly = $this->get_publisher_timeperiod_statistics(array(5), 1);
		$this->set_array("reportResultPublisherYearly",$dbResultPublisherYearly);
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

	function ads_action()
	{
		$this->set_title($this->get_label('manage ad statistics'));
		$db = DAL::get_instance();



		if($_POST)
		{
			$adtype    = intval($this->read_post_param('type'));
			$duration  = intval($this->read_post_param("duration"));
			$advertiser = $db->sanitize($this->read_post_param("username"));
			$adpricing = intval($this->read_post_param("adpricing"));
			$from_date = $this->read_post_param("from_date");
			$to_date   = $this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$adtype    = 0;
			$duration  = 1;
			$advertiser= "";
			$adpricing = -1;
			$from_date = '';
			$to_date   = '';
			$sortBy    = "impression";
			$orderBy   = "desc";
		}


		if($sortBy == "click")
		$sorting = 1;
		else if($sortBy == "conversion")
		$sorting = 4;
		else if($sortBy == "spend" || $sortBy == "adminprofit")
		$sorting = 2;
		else if($sortBy == "profit")
		$sorting = 3;
		else 
		$sorting = 0;
		if($orderBy == "asc")
		$ordering = 1;
		else 
		$ordering = 0;

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

		if($adtype=="")
		$adtype=0;

		if($adpricing === "")
		$adpricing=-1;

		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("type",$adtype);
		$this->set_variable("duration",$duration);
		$this->set_variable("advertiser",$advertiser);
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);


		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $advertiser = '', $adtype = 0, $isAdvertiserAds = -1
		$reportResult  = $this->get_advertiser_statistics($timePeriod, 1, $adpricing, -1, 0, 0, 4, $sorting, 0, 0, "", $ordering, $advertiser, $adtype, 1);
		$this->set_array("reportResult",$reportResult);
	}


	function top_list_action()
	{
		$this->set_title($this->get_label('toppers list'));
		$db= DAL::get_instance();

		if($_POST)
		{
			$duration  = intval($this->read_post_param("duration"));
			$top       = intval($this->read_post_param("top"));
			$sort      = intval($this->read_post_param("sort"));
			$from_date = $this->read_post_param("from_date");
			$to_date   = $this->read_post_param("to_date");
		}
		else
		{
			$duration   = 1;
			$top        = 0;
			$sort       = 0;
			$from_date  = '';
			$to_date    = '';
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

		$toppers_limit = Configuration::get_instance()->read('toppers_limit');

		if($top == 0)
		{
			//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $sorting=0, $forMap = 0, $resultLimit = 0, $country = ''
			$dbResult = $this->get_advertiser_statistics($timePeriod, 1, -1, -1, 0, 0, 2, $sort, 0, $toppers_limit);
			$this->set_array("reportResult",$dbResult);
		}
		else if($top == 1)
		{
			//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1, $pid = -1, $bid = 0, $sid = 0,  $report_type = 0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = ''
			$dbResult = $this->get_publisher_statistics($timePeriod, 1, -1, -1, 0, 0, 2, $sort, 0, $toppers_limit);
			$this->set_array("reportResult",$dbResult);
		}
		else if($top == 2 && $this->get_addon_status('category-targeting_enabled') ==1)
		{
			//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1, $pid = -1, $bid = 0, $sid = 0,  $report_type = 0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = ''
			$dbResult = $this->get_publisher_statistics($timePeriod, 1, -1, -1, 0, 0, 3, $sort, 0, $toppers_limit);
			$this->set_array("reportResult",$dbResult);
		}

		$this->set_variable("duration",$duration);
		$this->set_variable("top",$top);
		$this->set_variable("sort",$sort);
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
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



		$this->set_variable("duration",$duration);
		$this->set_variable("type",$type);
		$this->set_variable("adv",$adv);
		$this->set_variable("pub",$pub);
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);


		if($type == 0 || $type == 1)
		{
				if($type == 0)
				$adv = -1;

				$dbResult = $this->get_advertiser_statistics($timePeriod, 1, -1, $adv, 0, 0, 1);
				$this->set_array("reportResult",$dbResult);
		}
		else
		{
				//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $pid=-1, $bid=0, $sid=0,  $report_type=0, $sorting=0, $country = ''
				$dbResult = $this->get_publisher_statistics($timePeriod, 1, -1, $pub, 0, 0, 1);
				$this->set_array("reportResult",$dbResult);
		}



		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where adv_status=1 order by id ASC");
		$this->set_result("res1",$res1);


		$res11=$db->execute_query("select id,username from ".TABLE_PREFIX."users where pub_status=1 order by id ASC");
		$this->set_result("res11",$res11);



	}


};
?>
