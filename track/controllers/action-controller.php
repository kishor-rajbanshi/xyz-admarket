<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."browser-helper.php";

include_once COMMON_DIR_PATH.'helpers'.DS."device-helper.php";

include_once LIB_DIR_PATH.DS."dbipclass/dbip.class.php";
include_once LIB_DIR_PATH.DS."DeviceDetect/BrowserDetection.php";

class ActionController extends ApplicationController
{
	function before_execute()
	{
	     parent::before_execute();
	     setlocale(LC_ALL,'en_US'); //In case of some languages,decimal places replace with ','.
	}

	function replace_macro($click_url,$campaignid="",$adunitid="",$sid="",$country="",$deviceType="",$osname="",$browsername="",$catid="",$clickID = 0)
  {
					//We append additional commas as first and last character.Need to remove before set macro replace.
					$categoryFirstChar = substr($catid,0,1);
					$categoryLastChar  = substr($catid,-1);

					if($categoryFirstChar == ",")
					$catid = substr($catid,1);

					if($categoryLastChar == ",")
					$catid = substr($catid,0,-1);

					$catid       = urlencode($catid);
					$osname      = urlencode($osname);
					$browsername = urlencode($browsername);

					$siteName    = "";
					if($sid > 0)
					$siteName    = $this->get_site_name($sid);

					$siteName    = urlencode($siteName);


	        $click_url=str_replace('[campaignid]',$campaignid,$click_url);
	        $click_url=str_replace('[bid]',$adunitid,$click_url);
	        $click_url=str_replace('[siteid]',$sid,$click_url);
			$click_url=str_replace('[siteurl]',$siteName,$click_url);
	        $click_url=str_replace('[country]',$country,$click_url);
	        $click_url=str_replace('[category]',$catid,$click_url);
	        $click_url=str_replace('[os]',$osname,$click_url);
	        $click_url=str_replace('[clickid]',$clickID,$click_url);
	        $click_url=str_replace('[device]',$deviceType,$click_url);
	        $click_url=str_replace('[browser]',$browsername,$click_url);

			return $click_url;
  }

	function validate_action()
	{
		$cap_ok=0;
		if(Configuration::get_instance()->read('enable_captcha_verification')==1)
		{
			require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';

			$recaptcha_private_key = Configuration::get_instance()->read('recaptcha_private_key');

			$reCaptcha = new \ReCaptcha\ReCaptcha($recaptcha_private_key);

			if(isset($_POST['g-recaptcha-response']))
			{
			    $captcha_response = $reCaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

			    if($captcha_response->isSuccess())
		    	    $cap_ok = 1;
			}
		}
		else
		$cap_ok = 1;

		$parameter = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"validate/")+9);

		$parameter = $cap_ok."/".$parameter;
		$set_path  = md5($parameter.Configuration::get_instance()->read('admarket_name'));

		$cookieExpiryTime = time()+10;
		setcookie(COOKIE_DIFFERENCE,$set_path,$cookieExpiryTime,$this->get_base_path(),$this->get_base_domain());

		$trackDomain   = $this->get_track_domain();
		$clickpath     = $trackDomain.TRACK_DIR."/index.php?page=action/click/".$parameter;

		$this->set_variable("clickpath",$clickpath);
	}

	function click_action()
	{
		if(Configuration::get_instance()->read('enable_captcha_verification')==1)
		{
			require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';

			$recaptcha_public_key=Configuration::get_instance()->read('recaptcha_public_key');
			$this->set_variable('recaptcha_public_key',$recaptcha_public_key);
		}

		$redirect_fraudclick = Configuration::get_instance()->read('redirect_fraudclick');

		if($redirect_fraudclick == 1)
		$redirect_link = Configuration::get_instance()->read('global_redirect_url');

		$db      		= DAL::get_instance();
		$Browser 		= new BrowserHelper();

		$browsname 		= $Browser->getBrowser();
		$platform  		= $Browser->getPlatform();
		$version   		= $Browser->getVersion();
	   	$useragent 		= $Browser->getUserAgent();
		$client_ip 		= UtilityHelper::get_user_ip();
		$current_ip     = md5($client_ip);

		$cap_ok     	= $this->read_page_param(1);
		$aid 			= intval($this->read_page_param(2));
		$kid 			= intval($this->read_page_param(3));
		$adunitid 		= intval($this->read_page_param(4));
		$sid 			= intval($this->read_page_param(5));
		$pid 			= intval($this->read_page_param(6));
		$from_ip 		= $this->read_page_param(7);
		$bs 			= $this->read_page_param(8);
		$ecommerceid 	= intval($this->read_page_param(9));
		$retarget 		= intval($this->read_page_param(10));
		$clickUrlParam 	= $this->read_page_param(11);
		$clickRate     	= $this->read_page_param(12);
		$clickRateHash 	= $this->read_page_param(13);
		$fid 			= $this->read_page_param(14);
		$fstatus 		= $this->read_page_param(15);

		$clickid 			= 0;
		$feedAd 			= 0;
		$exchangeAd 	= 0;
		$cityid 	    	= 0;
		$country 	    	= "";
		$subdivision1   	= "";
		$subdivision2   	= "";
		$latitude	    	= "";
		$longitude	    	= "";
		$siddata1       	= "";
		$siddata2       	= "";
		$clpath         	= "";
		$refstring      	= "";
		$refstring1      	= "";
		$isp 				= "";
		$connection 		= "";
		$date_condition     = "";
		$adv_ref_enabled 	= 0;
		$pub_ref_enabled 	= 0;
		$isp_enabled        = 0;
		$connection_enabled = 0;
		$prid               = 0;
		$rid          		= 0;
		$adv_ref_perc 		= 0;
		$ispid 				= 0;
		$conn_id 			= 0;
		$date_flag          = 0;

		$language 			= array();
		$stringarray 		= array();


		$new_time = time();
		$currTime = time();

		$ntime =date("Y",time());
		$ntime.=date("m",time());
		$ntime.=date("d",time());
		$ntime.=date("H",time());


		$admarket_name 				= Configuration::get_instance()->read('admarket_name');
		$language_enabled   		= Configuration::get_instance()->read('language_enabled');
		$fraud_interval     		= Configuration::get_instance()->read('fraud_time_interval');
		$max_click_count    		= Configuration::get_instance()->read('max_no_clicks');
		$os_targeting_enabled       = Configuration::get_instance()->read('os-targeting_enabled');
		$browser_targeting_enabled  = Configuration::get_instance()->read('browser-targeting_enabled');

		$language_targeting_enabled = $this->get_addon_status('language-targeting_enabled');
		$category_targeting_enabled = $this->get_addon_status('category-targeting_enabled');
		$device_enabled             = $this->get_addon_status('device-targeting_enabled');
		$referral_enabled    		= $this->get_addon_status('referral_enabled');
		$retargeting_enabled 		= $this->get_addon_status('retargeting_enabled');
		$ecommerce_enabled   		= $this->get_addon_status('ecommerce-ads_enabled');
		$city_enabled        		= $this->get_addon_status('city-targeting_enabled');
		$isp_connection_enabled     = $this->get_addon_status('isp-connection-targeting_enabled');
		$directlink_enabled         = $this->get_addon_status('direct-link-ads_enabled');
		$video_enabled              = $this->get_addon_status('video-ads_enabled');

		if($isp_connection_enabled == 1)
		{
			$isp_enabled            = $this->get_addon_status('isp-targeting_enabled');
			$connection_enabled     = $this->get_addon_status('connectiontype-targeting_enabled');
		}

		if($clickRate == "")
		$clickRate = 0;

		if($clickRateHash == "")
		$clickRateHash = 0;

		$ads_res = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id = ? AND status = 1 AND pause_status = 0",array($aid));
		$number  = $ads_res->get_num_records();

		if($number == 0)
		{
		    if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    header("Location: ".BASE);

		    exit(0);
		}

		$result 			= $ads_res->fetch_assoc();

		$uid 				= $result['uid'];
		
		if($result['display_type'] == 6)//CPA
		{
			if($result['type'] == 12)
			$adtype 		= 12;
			else 
			$adtype 		= 6;
		}
		else 
		{
			if($result['type'] == 20 && $result['dsp_video_linear'] == 1)
			$adtype 			= 13;
			else if($result['type'] == 13)
			$adtype 			= 13;
			else 
		$adtype 			= $result['display_type'];   // $adtype   0->PPC   1->CPM  3->Sponsored  6->CPA 
		}		
		
		$default_rate       = $result['default_rate'];
		$budget 			= $result['total_ad_budget'];
		$daily_budget 		= $result['daily_budget'];
		$budget_used 		= $result['total_budget_used'];
		$daily_budget_used 	= $result['daily_budget_used'];


		if($adtype == 12) //Affiliate click
		{
			$cap_ok   = 1;
			$adunitid = intval($this->read_page_param(3));
			$bs 	  = $this->read_page_param(4);
		}


		if($cap_ok == "")
		$cap_ok  = 0;

		if($fid == "")
		$fid     = 0;

		if($fstatus == "")
		$fstatus = 0;

		if($aid < 1 || $kid < 0 || $adunitid < 1)
		{
		    if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    header("Location: ".BASE);

		    exit(0);
		}

		if($result['type'] == 17) //Feed
		$feedAd = 1;

		if($result['type'] == 20)
		$exchangeAd = 1;
		$sourceType = 0;
		if($feedAd == 1)
		$sourceType = 1;
		else if($exchangeAd == 1)
		$sourceType = 2;
		//For manage feed CPC
		if($clickRate > 0 && $result['type'] == 17)
		{
			$hashCreate = md5($aid.$clickRate.$admarket_name);

			if($clickRateHash != $hashCreate)
			$clickRate = 0;
		}


		if($result['type'] != 17 && $result['type'] != 20)
		$clickUrlParam = $this->mybase64_encode($result['click_url']);

		$link          = $this->mybase64_decode($clickUrlParam);

		if($link == '')
		$link = BASE;

		if($adtype == 3) //CPD click
		$mapid = $kid;
		else
		$mapid = 0;

		if($city_enabled ==1 && $adtype != 12)
		{
			$record123 		= UtilityHelper::get_geo_details_from_ip($client_ip,1);
			$country 		= $record123->country_code;
			$subdivision1 	= $record123->subdivision1;
			$subdivision2 	= $record123->subdivision2;
			$latitude 		= $record123->latitude;
			$longitude 		= $record123->longitude;
			$cityid 		= $record123->cityid;
		}
		else
		{
			if(Configuration::get_instance()->read('countrywise_data_tracking') ==1)
			{
				$record    	= UtilityHelper::get_geo_details_from_ip($client_ip);
				$country   	= $record->country_code;
				$latitude  	= $record->latitude;
				$longitude 	= $record->longitude;
			}
			else
			{
				$country   	= UtilityHelper::get_country_from_ip($client_ip);
				$latitude  	= "";
				$longitude 	= "";
			}
		}

		if($category_targeting_enabled ==1)
		{
			$siddata1 = ',`sid`';
			$siddata2 = ',?';
		}

		if($referral_enabled ==1)
		{
			$refstring   = ',`cpc_referral`,`adv_referral`,`pub_referral`';
			$refstring1  = ',?,?,?';

			$adv_ref_enabled = Configuration::get_instance()->read('advertiser_referral_enabled');
			$pub_ref_enabled = Configuration::get_instance()->read('publisher_referral_enabled');
		}

		$parameter    = md5(substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"click/")+6).Configuration::get_instance()->read('admarket_name'));
		$parameter_co = $this->read_cookie_param(COOKIE_DIFFERENCE);

		if($parameter != $parameter_co && $adtype != 6 && $adtype != 12)
		{
		    if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    {
			$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country);
		        header("Location: ".$link);
		    }

		    exit(0);
		}



		$pid_res    = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE id=?",array($adunitid));
		
		if($pid_res->get_num_records() == 0)
		{
		    if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    header("Location: ".BASE);

		    die;
		}
		
		$pid_result = $pid_res->fetch_assoc();

		$pid        = $pid_result['pubid'];
		$adcodeType = $pid_result['adcode_type'];

		if($pid == "")
		$pid = 0;

		if($directlink_enabled != 1 && $adcodeType == 21)
		{
			if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    {
				$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country);
		        header("Location: ".$link);
		    }
			die;
		}

		if($pid > 0)
		{
			$publisher_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));
			
			if($publisher_res->get_num_records() == 0)
			{
				if($redirect_fraudclick == 1)
				header("Location: ".$redirect_link);
				else
				header("Location: ".BASE);

				die;
			}			
			
			$publisher_result=$publisher_res->fetch_assoc();

			if($publisher_result['pub_status'] !=1)
			{
			    if($redirect_fraudclick == 1)
			    header("Location: ".$redirect_link);
			    else
			    header("location:".BASE);

			    die;
			}

			if($directlink_enabled == 1 && $adcodeType == 21 && $publisher_result['directlink_availability'] == 0)
			{
				if($redirect_fraudclick == 1)
				header("Location: ".$redirect_link);
				else
				header("Location: ".BASE);

				die;
			}


			if($referral_enabled ==1 && $pub_ref_enabled ==1)
			$prid               = intval($publisher_result['rid']);

			if($prid > 0)
			{
				$datacontent    = $this->get_referral_user_active($prid,2);

				$prid			= $datacontent[0];
				$pub_ref_perc	= $datacontent[1];
			}
		}


		if($pid > 0 && Configuration::get_instance()->read('allow_publishers_to_display_their_own_ads') == 0)
		{
			if($uid == $pid)
			{
			    if($redirect_fraudclick == 1)
			    header("Location: ".$redirect_link);
			    else
			    {
				   $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country);
			       	   header("Location: ".$link);
			    }
			    exit(0);
			}
		}

		if($feedAd == 0 && $exchangeAd == 0)
		{
			$user_res    = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			$user_result = $user_res->fetch_assoc();

			if($referral_enabled ==1 && $adv_ref_enabled ==1)
			$rid         = intval($user_result['rid']);

			if($rid > 0)
			{
				$datacontent=$this->get_referral_user_active($rid,1);

				$rid			= $datacontent[0];
				$adv_ref_perc	= $datacontent[1];
			}
		}

		if($ecommerce_enabled == 1)
		{
			$ecommercead = $result['type'];

			if($ecommercead == 7)
			{
				if($ecommerceid == 0)
				$link = $result['headline_link'];
				else
				{
					$ecommercelink=$db->read_single_column("SELECT ad_click_url FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($ecommerceid));

					if($ecommercelink != "")
					$link = $ecommercelink;
					else
					$link = BASE;
				}
			}
		}


//******************************** Fraud detection validations ***********************************//


/********************************* Device Targeting Restriction **********************************/

	if($adtype != 6 && $adtype != 12)
	{
		if($device_enabled == 1 && $adtype != 3)
		{
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			$browserClass   = new foroco\BrowserDetection();
			$browserResult  = $browserClass->getAll($user_agent);

			$deviceType = $browserResult['device_type'];

			if($deviceType =='desktop')
			$device=0;
			else if($deviceType =='mobile')
			$device=1;

			$addevice = $db->read_single_column("SELECT device FROM ".TABLE_PREFIX."ads WHERE id=? ",array($aid));


			if($addevice !=2 && $device != $addevice)
			{
			    if($redirect_fraudclick == 1)
			    header("Location: ".$redirect_link);
			    else
			    {
				$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType);
				header("Location: ".$link);
			    }

			    exit(0);
			}

			if($os_targeting_enabled ==1)
			{
				$oscount = $db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_os_mapping WHERE aid=?",array($aid));

				if($oscount > 0)
				{
						$osname=$browserResult['os_name'];

						$osid=$db->read_single_column("select id from ".TABLE_PREFIX."os where name=?",array($osname));
						if($osid > 0)
						{
							$adoscount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_os_mapping where aid=? AND osid=?",array($aid,$osid));

							if($adoscount ==0)
							{
							    if($redirect_fraudclick == 1)
							    header("Location: ".$redirect_link);
							    else
							    {
								    $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname);
								    header("Location: ".$link);
							    }

							    exit(0);
							}
						}
				}
			}

			if($browser_targeting_enabled ==1)
			{
					$browsercount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_browser_mapping WHERE aid=?",array($aid));

					if($browsercount >0)
					{

						$browsername=$browserResult['browser_name'];

					 	$browserid=$db->read_single_column("select id from ".TABLE_PREFIX."browser where name=?",array($browsername));

					 	if($browserid > 0)
						{
							$adbrcount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_browser_mapping where aid=? AND browserid=?",array($aid,$browserid));

							if($adbrcount ==0)
							{
							    if($redirect_fraudclick == 1)
							    header("Location: ".$redirect_link);
							    else
							    {
								$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername);
							        header("Location: ".$link);
							    }

							    exit(0);
							}
						}
					}
			}
		}


		/************************* Device Targeting Restriction *********************/

		/*************************** ISP  & Connection Targeting********************/
	if($isp_enabled ==1 || $connection_enabled ==1 )
	{
		$ispmapping=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_isp_mapping where aid=? ",array($aid));

		$conn_mapping=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_connection_mapping where aid=? ",array($aid));

		if($ispmapping >0  || $conn_mapping >0)
		{
			try
			{
			    $db1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);

				$dbip = new DBIP_MySQLI($db1);

			 	$client_ip=UtilityHelper::get_user_ip();
				$inf = $dbip->Lookup($client_ip,TABLE_PREFIX.'dbip_lookup');
			  	$country=$inf->country ;
				$isp= $inf->isp_name ;
				$connection=$inf->connection_type ;
				$cacheDevice=$cacheDevice.$isp.$connection;

			    if($ispmapping > 0)
			    {
					if($isp !='' && $isp_enabled ==1)
				    $ispid=$db->read_single_column("select id from ".TABLE_PREFIX."isp where name=? and country=?",array($isp,$country));


					if($ispid > 0)
					{
						$ispcount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_isp_mapping where aid=? AND ispid = ?",array($aid,$ispid));

						if($ispcount ==0)
						{
							    if($redirect_fraudclick == 1)
							    header("Location: ".$redirect_link);
							    else
							    {
								 $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername);
								 header("Location: ".$link);
							    }

							    exit(0);
						}
					}
			    }
			    if($conn_mapping > 0)
			    {
					if($connection !='' && $connection_enabled ==1)
					$conn_id=$db->read_single_column("select id from ".TABLE_PREFIX."connection where name=? ",array($connection));

					if($conn_id > 0)
					{
						$conn_count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_connection_mapping where aid=? AND conn_id = ?",array($aid,$conn_id));

						if($conn_count ==0)
						{
							    if($redirect_fraudclick == 1)
							    header("Location: ".$redirect_link);
							    else
							    {
								 $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername);
								 header("Location: ".$link);
							    }

							    exit(0);
						}
					}
			    }

			} catch (DBIP_Exception $e) {
				//echo "error: {$e->getMessage()}\n";
			}
		}
	}
	/*******************************************************************/
	/*************************** Browser Language Targeting**********************/
	if($language_targeting_enabled==1)
	{
		$lmapping=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_language_mapping where aid=? ",array($aid));

		if($lmapping >0)
		{
			if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			{
				$http_accept=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
				$x = explode(",",$http_accept);
				foreach ($x as $val) {

					$language[] ="'". substr($val, 0, 2)."'";

				}
				$language_arr=array_unique($language);
			}


			if(count($language_arr)>0)
			{

				$language_arr1 = '';

				foreach($language_arr as $lkey=>$lvalue)
				{
				      $language_arr[$lkey] = str_replace("'","", $language_arr[$lkey]);

				      if($language_arr1 != '')
				      $language_arr1.=',';

				      $language_arr1.='?';
				}

				$language_data=$db->execute_query("select id from ".TABLE_PREFIX."language where code in (".$language_arr1.")", $language_arr);

				if($language_data->get_num_records()>0)
				{
				    $brlang_id_arr=array();
					while ($brdata=$language_data->fetch_assoc())
					{
						$brlang_id_arr[]=$brdata['id'];
					}
					if(count($brlang_id_arr )> 0)
					{
						$lng_count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_language_mapping where aid=? AND ( language_id in ( ".implode(',', $brlang_id_arr).") OR language_id = 0) ",array($aid));

						if($lng_count ==0)
						{
						    if($redirect_fraudclick == 1)
						    header("Location: ".$redirect_link);
						    else
						    {
							$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername);
						        header("Location: ".$link);
						    }

						    exit(0);
						}
					}
				}
			}
		}
	}
	/*************************************************/

	/*********************** Category Targeting Restriction **********************/
	if($category_targeting_enabled ==1 && $adcodeType != 21)
	{
		$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

		if($category_enabled_ads !='')
		$stringarray=explode('_',$category_enabled_ads);
	}

	if($category_targeting_enabled ==1 && $adcodeType != 21 && in_array($pid_result['display_type'],$stringarray))
	{
		$siddb=$pid_result['sid'];

		if($siddb >0 && $siddb != $sid)
		{
		    if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    {
			  $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername);
			  header("Location: ".$link);
		    }

		    exit(0);
		}
	}

	if($category_targeting_enabled ==1 && $adcodeType != 21 && in_array($adtype,$stringarray))
	{
		$siterow  = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."sites WHERE id=? AND status=1",array($sid));
		$sitedata = $siterow->fetch_assoc();

		$catid    = $sitedata['catid'];

		$catcount = $db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));

		if($catcount > 0)
		{
				$categoryArray     = explode(",",$catid);
				$categoryArrayTemp = array();
				$category_string   = "";

				if(count($categoryArray) == 1) //Single category support
				$category_string = UtilityHelper::get_category_last_childs_display($catid);
				else //Multiple category support
				{
						foreach($categoryArray as $catKey => $catValue)
						{
								$catValue = trim($catValue);

								if($catValue != "")
								{
										if(UtilityHelper::get_category_exists($catValue))
										$categoryArrayTemp[] = $catValue;
								}
						}

						$categoryCount = count($categoryArrayTemp);

						if($categoryCount > 0)
						$category_string = implode("," , $categoryArrayTemp);
				}

			$catquery="";

			if($category_string != "")
			{
					$catarray=explode(',',$category_string);

					foreach($catarray as $key123=>$value123)
					{
							if($value123 !="")
							{
									if($catquery !="")
									$catquery.=' OR ';

									$catquery.=' catid='.intval($value123).' ';
							}
					}
			}

			if($catquery != "")
			{
					$catquery   = ' AND ('.$catquery.') ';

					$adcatcount = $db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? ".$catquery." ",array($aid));
			}
			else
			$adcatcount = 0;


			if($adcatcount == 0)
			{
			    if($redirect_fraudclick == 1)
			    header("Location: ".$redirect_link);
			    else
			    {
              $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
							header("Location: ".$link);
			    }

			    exit(0);
			}
		}
	}

	/************************* Category Targeting Restriction ************************/

		/************************** Time Targeting Restriction *****************************/
		if($this->get_addon_status('time-targeting_enabled') ==1)
		{
			$datetime       = time();

			if(Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)
			{

					if(Configuration::get_instance()->read('date_filter_enabled') ==1)
					{
						$date_condition.=' (a.date_filter =0 OR ((a.date_filter =1 OR a.date_filter =2) AND a.tmt_start_date <='.$datetime.' AND a.tmt_end_date >='.$datetime.')) ';
						$date_flag=1;
					}

				if(Configuration::get_instance()->read('time_filter_enabled') ==1)
				{
					$datehour=date('G',time());

					$dateperiod1='';
					if($datehour >=6 && $datehour < 9)
					$dateperiod1=' AND a.time_period1=1 ';
					else if($datehour >=9 && $datehour < 12)
					$dateperiod1=' AND a.time_period2=1 ';
					else if($datehour >=12 && $datehour < 16)
					$dateperiod1=' AND a.time_period3=1 ';
					else if($datehour >=16 && $datehour < 20)
					$dateperiod1=' AND a.time_period4=1 ';
					else if($datehour >=20 || $datehour < 6)
					$dateperiod1=' AND a.time_period5=1 ';

					if($date_flag ==1)
					$date_condition.=' AND ';

					$date_flag=1;

					$date_condition.=' (a.time_filter =0 OR (a.time_filter =1 AND ((a.tmt_start_time < '.$datehour.' AND a.tmt_end_time > '.$datehour.') OR (a.tmt_start_time > '.$datehour.' AND a.tmt_end_time = '.$datehour.') OR (a.tmt_start_time > '.$datehour.' AND a.tmt_end_time > '.$datehour.' AND a.tmt_start_time > a.tmt_end_time) OR a.tmt_start_time ='.$datehour.' OR a.tmt_end_time ='.$datehour.')) OR (a.time_filter =2 '.$dateperiod1.')) ';
				}


				if(Configuration::get_instance()->read('day_filter_enabled') ==1)
				{
					$dateday=date('w',time());

					if($dateday ==0)
					$dateday=7;

					$dateday1='';
					if($dateday ==7)
					$dateday1=' a.day_period7=1 ';
					else if($dateday ==1)
					$dateday1=' a.day_period1=1 ';
					else if($dateday ==2)
					$dateday1=' a.day_period2=1 ';
					else if($dateday ==3)
					$dateday1=' a.day_period3=1 ';
					else if($dateday ==4)
					$dateday1=' a.day_period4=1 ';
					else if($dateday ==5)
					$dateday1=' a.day_period5=1 ';
					else if($dateday ==6)
					$dateday1=' a.day_period6=1 ';


					if($date_flag ==1)
					$date_condition.=' AND ';

					$date_condition.=' (a.day_filter =0 OR (a.day_filter =1 AND ((a.tmt_start_day < '.$dateday.' AND a.tmt_end_day > '.$dateday.') OR (a.tmt_start_day > '.$dateday.' AND a.tmt_end_day ='.$dateday.') OR (a.tmt_start_day > '.$dateday.' AND a.tmt_end_day >'.$dateday.' AND a.tmt_start_day > a.tmt_end_day) OR a.tmt_start_day ='.$dateday.' OR a.tmt_end_day ='.$dateday.')) OR (a.day_filter =2 AND '.$dateday1.')) ';
				}



				if($date_condition !='')
				{
					$date_condition=' WHERE ( id = '.$aid.' AND '.$date_condition.') ';

					$aidGet = $db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ads a ".$date_condition);

					if(intval($aidGet) == 0)
					{
					    if($redirect_fraudclick==1)
					    header("Location: ".$redirect_link);
                        		    else
					    {
						$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
						header("Location: ".$link);
					    }

					    exit(0);
					}
				}
			}
		}
	}

		/************************** Time Targeting Restriction *****************************/


		if($pid >0 && $adtype != 6 && $adtype != 12)
		{
			//******** Publisher Fraud Tracking *********//

			$day_begin1 =date("Y",time());
			$day_begin1.=date("m",time());
			$day_begin1.=date("d",time());
			$day_begin1.=date("H",time());


			$today =date("Y",time());
			$today.=date("m",time());
			$today.=date("d",time());
			$today.='00';

			for($i=0;$i < $fraud_interval;$i++)
			{
			    $today=$this->get_previous_hour($day_begin1);
			}

			if($publisher_result['pub_logintime'] > $today)
			{
				if($publisher_result['pub_loginip']== $client_ip)
				{
					$to=Configuration::get_instance()->read('admin_notification_email');

					if($adtype == 0)
					$this->sendFraudNotification($publisher_result['username'],$to,Configuration::get_instance()->read('admarket_name'));

					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";

					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,2,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;

					$db->execute_query($frd_query,$insert_data);

					if($redirect_fraudclick == 1)
			    		header("Location: ".$redirect_link);
			    		else
					{
					    $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
					    header("Location: ".$link);
					}

					exit(0);
				}
			}

			//******** Publisher Fraud Tracking *********//

				//******** Bot Fraud Validation *************//
				$captchastatus=Configuration::get_instance()->read('captcha_verification');
				$captchatime=Configuration::get_instance()->read('captcha_time_interval');

				$pubcaptchastatus=$publisher_result['pub_captcha_status'];
				$pubcaptchatime=$publisher_result['pub_captcha_time'];

				$pubcaptchatime=$pubcaptchatime+(60*60*$captchatime);

				$imgflag = 0;

				if($cap_ok == 1)
				{
					$imgflag=1;

					if($fid > 0)
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."fraudclicks WHERE `id`=?",array($fid));
				}

				$tm=time();


				if($pubcaptchastatus==1 && $tm <= $pubcaptchatime && $captchastatus==1 && $imgflag !=1)
				{
					$clpath='action/validate/'.$aid.'/'.$kid.'/'.$adunitid.'/'.$sid.'/'.$pid.'/'.$from_ip.'/'.$bs.'/'.$ecommerceid.'/'.$retarget.'/'.$clickUrlParam.'/'.$clickRate.'/'.$clickRateHash;

					$this->set_variable('clpath',$clpath);
				}

				$bs_key = md5($aid.$kid.$adunitid.$sid.$pid.$retarget);
				if($bs != $bs_key && $captchastatus==1 && $clpath =="")
				{
					if($imgflag ==1)
					{
					    if($redirect_fraudclick==1)
					    header("Location: ".$redirect_link);
					    else
					    {
						$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
					        header("Location: ".$link);
					    }

					    exit(0);
					}
					if($fstatus==0)
					{
						$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";


						$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,6,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
						if($category_targeting_enabled ==1)
						$insert_data[]=$sid;

						$lst_id=$db->execute_query($frd_query,$insert_data);
						$fid=$lst_id->get_last_id();
					}

					$pub_captcha_time=time()+(60*60*$captchatime);
					$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_captcha_status=?,pub_captcha_time=? WHERE id=?",array(1,$pub_captcha_time,$pid));

					$clpath='action/validate/'.$aid.'/'.$kid.'/'.$adunitid.'/'.$sid.'/'.$pid.'/'.$from_ip.'/'.$bs.'/'.$ecommerceid.'/'.$retarget.'/'.$clickUrlParam.'/'.$clickRate.'/'.$clickRateHash.'/'.$fid.'/1';

					$this->set_variable('clpath',$clpath);
				}
				else if($bs != $bs_key)
				{
				    if($redirect_fraudclick == 1)
				    header("Location: ".$redirect_link);
				    else
				    {
					$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
				        header("Location: ".$link);
				    }

				    exit(0);
				}
				//******** Bot Fraud Validation *************//

		}

		if($clpath == "")
		{
			if($adtype != 6 && $adtype != 12)
			{
				//******* Repetitive Click Tracking *********//

				$day_begin1 =date("Y",time());
				$day_begin1.=date("m",time());
				$day_begin1.=date("d",time());
				$day_begin1.=date("H",time());


				$today =date("Y",time());
				$today.=date("m",time());
				$today.=date("d",time());
				$today.=date("H",time());

				for($i=0;$i < $fraud_interval;$i++)
				{
				    $today=$this->get_previous_hour($day_begin1);
				}

				$click1=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."fraudclicks WHERE aid=? and ip=? and time >=? LIMIT 0,1",array($aid,$client_ip,$today));

				$click2_res=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."dailyclicks WHERE aid=? and ip=? and time >=? LIMIT 0,".$max_click_count." ",array($aid,$client_ip,$today));
				$click2=$click2_res->get_num_records();

				if(intval($click1) >0 || (intval($click2) >0 && $click2 >= $max_click_count))
				{
					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";

					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,1,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;

					$db->execute_query($frd_query,$insert_data);

					if($redirect_fraudclick == 1)
					header("Location: ".$redirect_link);
					else
					{
					    $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
					    header("Location: ".$link);
					}
					exit(0);
				}

				//******* Repetitive Click Tracking *********//

				//******* Click IP Limit Exceeds *********//

				$day_begin1 =date("Y",time());
				$day_begin1.=date("m",time());
				$day_begin1.=date("d",time());
				$day_begin1.=date("H",time());


				$today =date("Y",time());
				$today.=date("m",time());
				$today.=date("d",time());
				$today.=date("H",time());

				for($i=0;$i < $fraud_interval;$i++)
				{
				    $today=$this->get_previous_hour($day_begin1);
				}

				$click2_res=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."dailyclicks WHERE ip=? and time >=? LIMIT 0,".$max_click_count." ",array($client_ip,$today));
				$click22=$click2_res->fetch_assoc();

				$click2=$click2_res->get_num_records();


				if(intval($click2) >= $max_click_count)
				{
					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";

					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,7,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;

					$db->execute_query($frd_query,$insert_data);

					if($redirect_fraudclick == 1)
					header("Location: ".$redirect_link);
					else
					{
					    $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
					    header("Location: ".$link);
					}
					exit(0);
				}

				//******* Click IP Limit Exceeds *********//
			}


			if($adtype != 6 && $adtype != 12)
			{
				//******* Invalid IP *********//
				if($from_ip != $current_ip)
				{
					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";

					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,3,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;

					$db->execute_query($frd_query,$insert_data);

					if($redirect_fraudclick==1)
					header("Location: ".$redirect_link);
					else
					{
						$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
						header("Location: ".$link);
					}
					exit(0);
				}

				//******* Invalid IP *********//

				//******* Invalid Geo *********//

				$country_res = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_geographic_mapping WHERE aid=? and country_code <>'0'",array($aid));
				$number      = $country_res->get_num_records();

				$location_status = 0;

				if($number == 0)
				$location_status = 1;


				while($country_result=$country_res->fetch_assoc())
				{
					if($country_result['country_code']==$country && $city_enabled  !=1)
					{
						$location_status=1;
						break;
					}
					else if($city_enabled ==1)
					{
						if($country_result['country_code'] == $country && $country_result['subdivision1'] == '0' && $country_result['subdivision2'] == '0' && $country_result['city'] ==0)
						{
							$location_status=1;
							break;
						}
						else if($country_result['country_code'] == $country && $country_result['subdivision1'] == $subdivision1 && $country_result['subdivision2'] == $subdivision2 && $country_result['city'] ==0)
						{
							$location_status=1;
							break;
						}
						else if($country_result['country_code'] == $country && $country_result['subdivision1'] == $subdivision1 && $country_result['subdivision2'] == $subdivision2 && $country_result['city'] ==$cityid)
						{
							$location_status=1;
							break;
						}
						else if($country_result['country_code'] == $country && $country_result['subdivision1'] == $subdivision1 && $country_result['subdivision2'] == '0' && $country_result['city'] ==0)
						{
							$location_status=1;
							break;
						}
						else if($country_result['country_code'] == $country && $country_result['subdivision1'] == $subdivision1 && $country_result['subdivision2'] == '0' && $country_result['city'] ==$cityid)
						{
							$location_status=1;
							break;
						}
						else if($country_result['country_code'] == $country && $country_result['subdivision1'] == '0' && $country_result['subdivision2'] == '0' && $country_result['city'] ==$cityid)
						{
							$location_status=1;
							break;
						}
					}
				}

				if($location_status == 0)
				{
					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";

					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,4,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;

					$db->execute_query($frd_query,$insert_data);

					if($redirect_fraudclick==1)
					header("Location: ".$redirect_link);
					else
					{
					     $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
					     header("Location: ".$link);
					}
					exit(0);
				}

				//******* Invalid Geo *********//


			//******* Proxy Detection *********//
			if(Configuration::get_instance()->read('proxy_detection')==1)
			{
				if(UtilityHelper::proxyDetection())
				{
					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";

					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,5,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;

					$db->execute_query($frd_query,$insert_data);

					if($redirect_fraudclick == 1)
					header("Location: ".$redirect_link);
					else
					{
					    $link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid);
					    header("Location: ".$link);
					}
					exit(0);
				}
			}
			//******* Proxy Detection *********//
		}

	//********************* Fraud detection validations *******************//



			if(($adtype ==0 || $adtype ==6) && $retargeting_enabled ==1 && $retarget ==1)
			{
				$exclude_cookie=$this->read_cookie_param("ads_retarget_exclude");

				$cotime=time()+($fraud_interval*60*60);

				if($exclude_cookie =="")
				$costring=$aid.'.'.$cotime;
				else
				{
					$explodearray=explode('-',$exclude_cookie);
					$explodearraylength=count($explodearray);

					$arraynew=array();
					$alreadyflag=0;

					for($i=0;$i < $explodearraylength;$i++)
					{
						if($explodearray[$i] !="")
						{
							$explodearray1=explode('.',$explodearray[$i]);

							if($explodearray1[1] > time())
							{
								$arraynew[]=$explodearray[$i];

								if($explodearray1[0] == $aid)
								$alreadyflag=1;
							}
						}
					}

					if($alreadyflag ==0)
					$arraynew[]=$aid.'.'.$cotime;

					$costring=implode('-',$arraynew);
				}

				setcookie("ads_retarget_exclude",$costring,0,"/",$this->get_base_domain());
			}

			if($adtype == 0 && $this->get_addon_status('cpc_enabled') ==1)
			{
				$ppc_profit_percentage = $publisher_result['ppc_profit_percentage'];

				$locale	       = 0;
				$balancestatus = 0;
				$email         = "";
				$username      = "";

				if($feedAd == 0)
				{
					$balancestatus  = $user_result['balancestatus'];
					$email          = $user_result['email'];
					$username       = $user_result['username'];

					if($language_enabled ==1)
					$locale = $user_result['locale'];
					else
					$locale = 0;
				}


			if($feedAd == 1)
			$click_value = $clickRate;
			else
			$click_value = $default_rate;


			if($click_value > 0 && ($daily_budget == 0 || ($daily_budget > 0 && $daily_budget >= ($daily_budget_used + $click_value))) && $budget >= ($budget_used + $click_value))
			{
				$budget_used_new       = $budget_used + $click_value;
                $daily_budget_used_new = $daily_budget_used+$click_value;

				if($pid >0)
				{
					if($ppc_profit_percentage > 0)
					$pub_profit_perc = $ppc_profit_percentage;
					else
					$pub_profit_perc = Configuration::get_instance()->read('profit_percentage');

					$publisherProfit = $click_value*$pub_profit_perc/100;
				}
				else
				{
					$publisherProfit = 0;
				}

				if($budget_used_new <= $budget)
				{
					$adv_referral = 0;
					$pub_referral = 0;

					if($feedAd == 0 && $referral_enabled ==1 && $rid >0 && $adv_ref_enabled ==1)
					{
						if($adv_ref_perc > 0)
						$arefperc = $adv_ref_perc;
						else
						$arefperc = Configuration::get_instance()->read('advertiser_referral_profit_percentage');

						$adv_referral = $click_value*$arefperc/100;
					}

					if($referral_enabled ==1 && $prid >0 && $pub_ref_enabled ==1)
					{
						if($pub_ref_perc > 0)
						$prefperc=$pub_ref_perc;
						else
						$prefperc=Configuration::get_instance()->read('publisher_referral_profit_percentage');

						$pub_referral=$publisherProfit*$prefperc/100;
					}

					$totalreferral=$adv_referral+$pub_referral;

					$db->execute_query("BEGIN");
					$failed = 0;

					$org_kid = $db->read_single_column("SELECT kid FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE id=?",array($kid));

					$sql = "INSERT INTO ".TABLE_PREFIX."dailyclicks (`uid`,`aid`,`kid`,`pid`,`bid`,`clickvalue`,`profit`,`time`,`ip`,`country`,`browser`,`platform`,`version`,`user_agent`,`org_time`,`latitude`,`longitude`,`source`".$siddata1.$refstring.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.$refstring1.")";

					$insert_data=array($uid,$aid,$org_kid,$pid,$adunitid,$click_value,$publisherProfit,$ntime,$client_ip,$country,$browsname,$platform,$version,$useragent,$currTime,$latitude,$longitude,$sourceType);

					if($category_targeting_enabled == 1)
					$insert_data[]=$sid;

					if($referral_enabled ==1)
					{
						$insert_data[]=$totalreferral;
						$insert_data[]=$adv_referral;
						$insert_data[]=$pub_referral;
					}

					$sql1 = $db->execute_query($sql,$insert_data);

					if($sql1->error == "")
					{
						$clickid = $sql1->get_last_id();
						$ads_update = $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET daily_budget_used=daily_budget_used+?,total_budget_used=total_budget_used+? WHERE id=?",array($click_value,$click_value,$aid));

						$ads_cache_update=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET daily_budget_used = daily_budget_used+?,total_budget_used=total_budget_used+? where id=?",array($click_value,$click_value,$aid));

						if($ads_update->error == "" && $ads_cache_update->error == "")
						{
							if($pid >0)
							{
								$pubacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_account_balance=pub_account_balance+? WHERE id=?",array($publisherProfit,$pid));

								if($pubacupdate->error !="")
								$failed=1;
							}


							if($referral_enabled ==1 && $failed ==0)
							{
								if($feedAd == 0 && $rid >0 && $adv_referral >0)
								{
									$refacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance=referral_balance+? WHERE id=?",array($adv_referral,$rid));

									if($refacupdate->error =="")
									{
										$sqlupref=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_hourly_backup SET adv_earning=adv_earning+? WHERE uid=? AND time=? AND type=0 LIMIT 1",array($adv_referral,$rid,$ntime));

										if($sqlupref->get_num_records() ==0)
											$sqlupref=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`uid`,`adv_earning`,`time`,`type`) values (?,?,?,?)",array($rid,$adv_referral,$ntime,0));

											if($sqlupref->error !="")
												$failed=1;
									}
									else
										$failed=1;
								}


								if($prid >0 && $pub_referral >0 && $failed ==0)
								{
									$refacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance=referral_balance+? WHERE id=?",array($pub_referral,$prid));

									if($refacupdate->error =="")
									{
										$sqlupref=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_hourly_backup SET pub_earning=pub_earning+? WHERE uid=? AND time=? AND type=0 LIMIT 1",array($pub_referral,$prid,$ntime));

										if($sqlupref->get_num_records() ==0)
										$sqlupref=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`uid`,`pub_earning`,`time`,`type`) values (?,?,?,?)",array($prid,$pub_referral,$ntime,0));

										if($sqlupref->error !="")
										$failed=1;
									}
									else
									$failed=1;
								}
							}
						}
						else
						$failed=1;
					}
					else
					$failed=1;


					if($failed ==0)
					$db->execute_query("COMMIT");
					else
					$db->execute_query("ROLLBACK");


					if($daily_budget > 0 && ($daily_budget-$daily_budget_used_new) < $default_rate)
					{
						$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET daily_budget_available = 0 where id=?",array($aid));
					}


					if(($budget-$budget_used_new) < $default_rate)
					{
						$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET pricing_status = 0,daily_budget_available = 0,budget_available = 0 WHERE id=?",array($aid));
					}


					if($feedAd == 0 && ($budget-$budget_used_new)  <= $default_rate)
					{
						$pricing_name = $this->get_ad_pricing($aid);

						$mail_res1    = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."email_templates WHERE id=13");
						$mail_result  = $mail_res1->fetch_assoc();


						if($language_enabled ==1 && $locale > 0 && isset($mail_result[$locale.'_message']) && $mail_result[$locale.'_message'] !='')
						$message=$mail_result[$locale.'_message'];
						else
						$message=$mail_result['message'];

						if($language_enabled ==1 && $locale >0 && isset($mail_result[$locale.'_subject']) && $mail_result[$locale.'_subject'] !='')
						$subject=$mail_result[$locale.'_subject'];
						else
						$subject=$mail_result['subject'];

						$message=str_replace("{USERNAME}",$username,$message);
						$message=str_replace("{ADMARKETNAME}",$admarket_name,$message);
						$message=str_replace("{AID}",$aid,$message);

						$subject=str_replace("{PRICING}",$pricing_name,$subject);
						$message=str_replace("{PRICING}",$pricing_name,$message);

						$subject=str_replace("{ADMARKETNAME}",$admarket_name,$subject);

						UtilityHelper::send_mail($email,$subject, $message);
					}
				}
			}
		}
		else if($adtype ==1 || $adtype ==3 || $adtype ==6 || $adtype ==12 || $adtype ==13)
		{
			$spendAmount    = 0;
			$profitAmount   = 0;
			$cpd_target_id  = 0;

			if($adtype == 3)
			{
				$orgkid         = $kid;
				$cpd_target_id  = $kid;
			}
            		else
			$orgkid = $db->read_single_column("SELECT kid FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE id=?",array($kid));

			if($adtype == 1)
			$clicktype = 1;
			else if($adtype == 6)
			{
				$clicktype = 6;

				$exp_interval = Configuration::get_instance()->read('cpa_conversion_tracking_interval');
			}
			else if($adtype ==12)
			{
				$clicktype=12;

				$exp_interval = Configuration::get_instance()->read('cpa_conversion_tracking_interval');
			}
			else if($adtype == 3)
			{
				$clicktype = 3;

				if($mapid > 0)
				{
					$cpdRow    = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE id=?",array($mapid));

					if($cpdRow->get_num_records() > 0)
					{
						$cpdData     = $cpdRow->fetch_assoc();

						$spendAmount  = $cpdData['day_cpd_rate_total'];
						$profitAmount = $cpdData['day_cpd_rate_publisher'];
					}
				}
			}
			else if($adtype ==13)
			$clicktype=13;


			$sql="INSERT INTO ".TABLE_PREFIX."dailyclicks (`uid`,`aid`,`kid`,`pid`,`bid`,`time`,`ip`,`country`,`browser`,`platform`,`version`,`user_agent`,`org_time`,`click_type`,`latitude`,`longitude`,`clickvalue`,`profit`,`cpd_target_id`,`source`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";

			$insert_data=array($uid,$aid,$orgkid,$pid,$adunitid,$ntime,$client_ip,$country,$browsname,$platform,$version,$useragent,$currTime,$clicktype,$latitude,$longitude,$spendAmount,$profitAmount,$cpd_target_id,$sourceType);


			if($category_targeting_enabled ==1)
			$insert_data[]=$sid;

			$sql1 = $db->execute_query($sql,$insert_data);

			$clickid = $sql1->get_last_id();

			if($adtype == 3)
			$db->execute_query("UPDATE ".TABLE_PREFIX."sponsored_ad_mapping SET clicks=clicks+1 WHERE id=?",array($mapid));
		}

		if($adtype == 6 || $adtype == 12)
		{
			$md5_value      = md5($aid."-".$clickid."-".$exp_interval."-".$currTime."-data-".$admarket_name);

			if(strpos($link, '?') === false)
			$link			= $link."?click_data=".$aid."-".$clickid."-".$exp_interval."-".$currTime."-".$md5_value."&click_source=".$admarket_name;
			else 
			$link			= $link."&click_data=".$aid."-".$clickid."-".$exp_interval."-".$currTime."-".$md5_value."&click_source=".$admarket_name;
		}

        	$link = $this->replace_macro($link,$aid,$adunitid,$sid,$country,$deviceType,$osname,$browsername,$catid,$clickid);

		header("Location: ".$link);
		exit(0);
		}
	}
	

	function conversion_action()
	{
		//header("Content-Type: application/javascript");  //Need it for some server requests

		$db= DAL::get_instance();

		$conversionData	     = $this->read_page_param(1);
		$conversionDataArray = explode('-',$conversionData);

		$aid			= intval($conversionDataArray[0]);
		$clickid	    = intval($conversionDataArray[1]);
		$cookie_expiry	= intval($conversionDataArray[2]);
		$clickedTime    = intval($conversionDataArray[3]);
		$md5_data		= $conversionDataArray[4];
		
		$admarket_name	= Configuration::get_instance()->read('admarket_name');

		$md5_generate   = md5($aid."-".$clickid."-".$cookie_expiry."-".$clickedTime."-data-".$admarket_name);

		if($md5_data != $md5_generate)
		exit;


	    $daily_details = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."dailyclicks_backup WHERE clickid = ? AND (click_type = 6 OR click_type = 12)",array($clickid));

	    if($daily_details->get_num_records() == 0)
	    {
	    	$daily_details = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."dailyclicks WHERE id = ? AND (click_type = 6 OR click_type = 12)",array($clickid));

	    	 if($daily_details->get_num_records() == 0)
	    	 exit;
	    }


		$click_result 	= $daily_details->fetch_assoc();


		$dbaid			= $click_result['aid'];
		$org_kid		= $click_result['kid'];
		$adunitid		= $click_result['bid'];
		$sid			= $click_result['sid'];
		$uid			= $click_result['uid'];
		$pid			= $click_result['pid'];


		$pidflag 		= 1;


		if($aid != $dbaid)
		exit;


		$ads_res = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND status = 1 AND pause_status = 0 AND pricing_status = 1",array($aid));

		if($ads_res->get_num_records() > 0 && $adunitid > 0)
		{
			$result 			= $ads_res->fetch_assoc();

			if($result['type'] == 12)
			$adtype 		= 12;
			else 
			$adtype 		= 6;
			
			$budget				= $result['total_ad_budget'];
		   	$budget_used		= $result['total_budget_used'];
			$click_value		= $result['default_rate'];
			$budget_used_new	= $budget_used+$click_value;

			$publisherProfit	= 0;

			$maxConversions	= Configuration::get_instance()->read('max_cpa_conversions_per_click');
			$exp_interval   = Configuration::get_instance()->read('cpa_conversion_tracking_interval');


			if($adtype == 6)
			$varstring		= 'cpa_';
			else
			$varstring      = 'affiliate_';

			if(($clickedTime + ($exp_interval * 86400)) < time())
			exit;

			if($adtype != 6 && $adtype != 12)
			exit;

			if(($adtype == 6 && $this->get_addon_status('cpa_enabled') !=1) || ($adtype ==12 && $this->get_addon_status('affiliate-ads_enabled') !=1))
			exit;


			if($maxConversions == 1)
			{
				$conversion_added = $db->read_single_column("SELECT id FROM ".TABLE_PREFIX."dailyconversions_backup WHERE clickid = ?",array($clickid));

				if(intval($conversion_added) > 0)
				exit;
			}

			
				$Browser 	= new BrowserHelper();
				$browsname 	= $Browser->getBrowser();
				$platform 	= $Browser->getPlatform();
				$version 	= $Browser->getVersion();
			    $useragent 	= $Browser->getUserAgent();
			    $client_ip 	= UtilityHelper::get_user_ip();

			    $city_enabled 				= $this->get_addon_status('city-targeting_enabled');
			    $category_targeting_enabled = $this->get_addon_status('category-targeting_enabled');
			    $language_enabled 			= Configuration::get_instance()->read('language_enabled');
			    $referral_enabled 			= $this->get_addon_status('referral_enabled');

			    $cityid		  		= 0;
				$country 	  		= "";
				$subdivision1 		= "";
				$subdivision2 		= "";
				$latitude	  		= "";
				$longitude	  		= "";
			    $siddata1     		= "";
			    $siddata2     		= "";
			    $refstring       	= "";
			    $refstring1      	= "";
			    $adv_ref_enabled 	= 0;
			    $pub_ref_enabled 	= 0;
			    $rid 				= 0;
			    $prid 				= 0;
			    $adv_referral 		= 0;
			    $pub_referral 		= 0;

			    if($adtype ==6 && $city_enabled ==1)
			    {
			    	$record123     = UtilityHelper::get_geo_details_from_ip($client_ip,1);
			    	$country       = $record123->country_code;
					$subdivision1  = $record123->subdivision1;
					$subdivision2  = $record123->subdivision2;
			    	$latitude      = $record123->latitude;
			    	$longitude     = $record123->longitude;
			    	$cityid        = $record123->cityid;
			    }
			    else
			    {
			    	if(Configuration::get_instance()->read('countrywise_data_tracking') ==1)
			    	{
			    		$record=UtilityHelper::get_geo_details_from_ip($client_ip);
			    		$country=$record->country_code;
			    		$latitude=$record->latitude;
			    		$longitude=$record->longitude;
			    	}
			    	else
			    	{
			    		$country =UtilityHelper::get_country_from_ip($client_ip);
			    		$latitude="";
			    		$longitude="";
			    	}
			    }


			    if($category_targeting_enabled == 1)
			    {
			    	$siddata1=',`sid`';
			    	$siddata2=',?';
			    }


			    if($referral_enabled ==1)
			    {
			    	$refstring=',`cpa_referral`,`adv_referral`,`pub_referral`';
			    	$refstring1=',?,?,?';

			    	$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
			    	$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
			    }


				if($aid > 0 && $adunitid >0)
				{
					if($pid >0)
					{
						$publisher_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));
						$publisher_result=$publisher_res->fetch_assoc();


						if($publisher_result[$varstring.'profit_percentage'] >0)
					    $pub_profit_perc=$publisher_result[$varstring.'profit_percentage'];
					    else
					   	$pub_profit_perc=Configuration::get_instance()->read($varstring.'profit_percentage');

					    $publisherProfit=$click_value*$pub_profit_perc/100;


						if($publisher_result['pub_status'] != 1)
						$pidflag=0;

						if($referral_enabled ==1 && $pub_ref_enabled ==1)
						$prid=intval($publisher_result['rid']);

						if($prid >0)
						{
							$datacontent=$this->get_referral_user_active($prid,2);

							$prid			= $datacontent[0];
							$pub_ref_perc	= $datacontent[1];
						}
					}

					if($pidflag ==1)
					{
						$user_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
						$user_result=$user_res->fetch_assoc();

						if($referral_enabled ==1 && $adv_ref_enabled ==1)
						$rid=intval($user_result['rid']);

						if($rid >0)
						{
							$datacontent=$this->get_referral_user_active($rid,1);

							$rid			= $datacontent[0];
							$adv_ref_perc	= $datacontent[1];
						}


						if($user_result['adv_status'] == 1)
						{
							$new_time=time();
							$currTime=time();

							$ntime =date("Y",time());
							$ntime.=date("m",time());
							$ntime.=date("d",time());
							$ntime.=date("H",time());

							if($budget >= $budget_used_new && $click_value > 0)
							{
								if($referral_enabled ==1 && $rid >0 && $adv_ref_enabled ==1)
								{
									if($adv_ref_perc > 0)
									$arefperc=$adv_ref_perc;
									else
									$arefperc=Configuration::get_instance()->read('advertiser_referral_profit_percentage');

									$adv_referral=$click_value*$arefperc/100;
								}

								if($referral_enabled ==1 && $prid >0 && $pub_ref_enabled ==1)
								{
									if($pub_ref_perc > 0)
									$prefperc=$pub_ref_perc;
									else
									$prefperc=Configuration::get_instance()->read('publisher_referral_profit_percentage');

									$pub_referral=$publisherProfit*$prefperc/100;
								}

			    				$totalreferral=$adv_referral+$pub_referral;


    							$db->execute_query("BEGIN");
    							$failed = 0;

    							$sql="INSERT INTO ".TABLE_PREFIX."dailyconversions_backup (`uid`,`aid`,`kid`,`pid`,`bid`,`clickvalue`,`profit`,`time`,`ip`,`country`,`browser`,`platform`,`version`,`user_agent`,`org_time`,`latitude`,`longitude`,`clickid`,`conversion_type`".$siddata1.$refstring.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.$refstring1.")";

    							$insert_data=array($uid,$aid,$org_kid,$pid,$adunitid,$click_value,$publisherProfit,$ntime,$client_ip,$country,$browsname,$platform,$version,$useragent,$currTime,$latitude,$longitude,$clickid,$adtype);

    							if($category_targeting_enabled ==1)
    							$insert_data[] = $sid;

    							if($referral_enabled ==1)
    							{
    								$insert_data[]=$totalreferral;
    								$insert_data[]=$adv_referral;
    								$insert_data[]=$pub_referral;
    							}

    							$sql1=$db->execute_query($sql,$insert_data);

    							if($sql1->error == "")
    							{
									$time       = mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));

    								$ads_update = $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET total_budget_used=total_budget_used+?,tracking_last_checked=? WHERE id=?",array($click_value,$time,$aid));

									$ads_cache_update = $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET total_budget_used=total_budget_used+?,tracking_last_checked=? WHERE id=?",array($click_value,$time,$aid));

    									if($ads_update->error == "" && $ads_cache_update->error == "")
									{
    									if($pid > 0)
    									{
    										$pubacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_account_balance=pub_account_balance+? WHERE id=?",array($publisherProfit,$pid));
    										if($pubacupdate->error !="")
    										$failed=1;
    									}

    									if($referral_enabled == 1 && $failed == 0)
    									{
    										if($rid >0 && $adv_referral > 0)
    										{
    											$refacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance=referral_balance+? WHERE id=?",array($adv_referral,$rid));

    											if($refacupdate->error == "")
    											{
    												$sqlupref=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_hourly_backup SET adv_earning=adv_earning+? WHERE uid=? AND time=? AND type=? LIMIT 1",array($adv_referral,$rid,$ntime,$adtype));

    												if($sqlupref->get_num_records() == 0)
    												$sqlupref=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`uid`,`adv_earning`,`time`,`type`) values (?,?,?,?)",array($rid,$adv_referral,$ntime,$adtype));

    												if($sqlupref->error !="")
    												$failed=1;
    											}
    											else
    											$failed=1;
    										}


    										if($prid > 0 && $pub_referral > 0 && $failed == 0)
    										{
    											$refacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance=referral_balance+? WHERE id=?",array($pub_referral,$prid));

    											if($refacupdate->error =="")
    											{
    												$sqlupref=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_hourly_backup SET pub_earning=pub_earning+? WHERE uid=? AND time=? AND type=? LIMIT 1",array($pub_referral,$prid,$ntime,$adtype));

    												if($sqlupref->get_num_records() ==0)
    												$sqlupref=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`uid`,`pub_earning`,`time`,`type`) values (?,?,?,?)",array($prid,$pub_referral,$ntime,$adtype));

    												if($sqlupref->error !="")
    												$failed=1;
    											}
    											else
    											$failed=1;
    										}
    									}
									}
									else
    								$failed=1;
    							}
    							else
    							$failed=1;


    							if($failed ==0)
    							$db->execute_query("COMMIT");
    							else
    							$db->execute_query("ROLLBACK");


    							if(($budget-$budget_used_new) < $click_value)
    							{
    							    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET pricing_status = 0,daily_budget_available = 0,budget_available = 0 WHERE id=?",array($aid));

    								$email=$user_result['email'];

    								$mail_res1=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."email_templates WHERE id=13");
    								$mail_result=$mail_res1->fetch_assoc();

    								if($language_enabled ==1 && $user_result['locale'] >0 && isset($mail_result[$user_result['locale'].'_message']) && $mail_result[$user_result['locale'].'_message'] !='')
    								$message=$mail_result[$user_result['locale'].'_message'];
    								else
    								$message=$mail_result['message'];


    								if($language_enabled ==1 && $user_result['locale'] >0 && isset($mail_result[$user_result['locale'].'_subject']) && $mail_result[$user_result['locale'].'_subject'] !='')
    								$subject=$mail_result[$user_result['locale'].'_subject'];
    								else
    								$subject=$mail_result['subject'];


    								$message=str_replace("{USERNAME}",$user_result['username'],$message);
    								$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
    								$message=str_replace("{AID}",$aid,$message);

    								$subject=str_replace("{PRICING}",$this->get_ad_pricing($aid,$adtype),$subject);
    								$message=str_replace("{PRICING}",$this->get_ad_pricing($aid,$adtype),$message);

    								$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);

    								UtilityHelper::send_mail($email,$subject, $message);
    							}
							}
						}
					}
				}
			
		}

		exit;
	}

	function click_default_action()
	{
		$aid = intval($this->read_page_param(1));

    	$redirect_fraudclick = Configuration::get_instance()->read('redirect_fraudclick');

		if($redirect_fraudclick == 1)
		$redirect_link       = Configuration::get_instance()->read('global_redirect_url');

		$db = DAL::get_instance();
		if($aid < 1)
		{
		    if($redirect_fraudclick==1)
		    header("Location: ".$redirect_link);
		    else
		    header("Location: ".BASE);
		    exit(0);
		}

		$ads_res = $db->execute_query("SELECT click_url FROM ".TABLE_PREFIX."ads WHERE uid=0 AND id=? AND status = 1",array($aid));
		$number  = $ads_res->get_num_records();

		if($number == 0)
		{
		    if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    header("Location: ".BASE);
		    exit(0);
		}

		$result = $ads_res->fetch_assoc();
		$link   = $result['click_url'];

		if($link != "")
		{
			header("Location: ".$link);
			exit(0);
		}
		else
		{
		    if($redirect_fraudclick == 1)
		    header("Location: ".$redirect_link);
		    else
		    header("location:".BASE);

		    exit(0);
		}
	}


	function impression_action()
	{
		$data_get_string = trim($this->read_page_param(1));
		$data_md5		 = $this->read_page_param(2);
		$come_time		 = $this->read_page_param(3);
		$country		 = $this->read_page_param(4);
		$sourceType		 = intval($this->read_page_param(5));
		$cookieDataGet	 = $this->read_page_param(6);


			$pixelUrl	= $this->read_page_param(7);


		if($cookieDataGet !== 0 && $cookieDataGet != "")
		$cookieDataGet 		= $this->mybase64_decode($cookieDataGet);
		else
		$cookieDataGet		= "";

		if($pixelUrl != "")
		$pixelUrl		    = $this->mybase64_decode($pixelUrl);
		$vastImpressionUrl	= $this->read_page_param(8);
		if($vastImpressionUrl != "")
		$vastImpressionUrl		    = $this->mybase64_decode($vastImpressionUrl);	

		$fromSupplyFeedImp  = 0;



		if($sourceType == 4) //Impression from supply xml feed
		$fromSupplyFeedImp  = 1;

		$data_get_array       = explode('.data.',$data_get_string);
		$data_get_array_count = count($data_get_array);

		$adcodeid = 0;
		$adtype	  = 0;

		for($i=0;$i < $data_get_array_count;$i++)
		{
			$data_get_array_data  = explode('|',$data_get_array[$i]);
			$adcodeid             = $data_get_array_data[4];
			$adtype               = $data_get_array_data[7];
			break;
		}

		//If img pixel tracking not done properly need to execute below code for 3rd party impression tracking
		if(($sourceType == 1 || $sourceType == 2) && $pixelUrl != "" && ($adtype == 9 || $adtype == 13) && $data_get_string == "")
		{
			file_get_contents($pixelUrl);
			if($sourceType == 2 && $vastImpressionUrl != "" && $adtype == 13 && $data_get_string == "")
			file_get_contents($vastImpressionUrl);
			die;
		}
		if($sourceType != 4)
		header("Content-type:application/javascript");
		$maxImpressionLimit  = 0;
		$capInterval         = 0;
		$cookie_name         = "";

		if($adtype == 1) //CPM
		{
				$cookie_name        = "_data_cpm";
				$maxImpressionLimit = intval(Configuration::get_instance()->read('cpm_ad_impression_limit_hour'));
				$capInterval        = intval(Configuration::get_instance()->read('cpm_interval'));
		}
		else if($adtype == 2)  //HTML
		{
				$sourceType         = 3;
				$cookie_name        = "_data_html";
				$maxImpressionLimit = intval(Configuration::get_instance()->read('html_ad_impression_limit_hour'));
				$capInterval        = intval(Configuration::get_instance()->read('html_interval'));
		}
		else if($adtype == 0 || $adtype == 3 || $adtype == 6)  //CPC/CPD/CPA
		{
				if($adtype == 0)
				$cookie_name = "_data_cpc";
				else if($adtype == 3)
				$cookie_name = "_data_cpd";
				else if($adtype == 6)
				$cookie_name = "_data_cpa";

				$maxImpressionLimit = 5;
				$capInterval        = 1;
		}
		else if($adtype == 9)  //POP
		{
				$cookie_name        = "_data_pop";
				$maxImpressionLimit = intval(Configuration::get_instance()->read('pop_ad_impression_limit_hour'));
				$capInterval        = intval(Configuration::get_instance()->read('pop_interval'));
		}
		else if($adtype == 13)  //CPV HTML5
		{
				$cookie_name        = "_data_cpv";
				$maxImpressionLimit = intval(Configuration::get_instance()->read('cpv_ad_impression_limit_hour'));
				$capInterval        = intval(Configuration::get_instance()->read('cpv_interval'));
		}

		$capTimeCurrent       = time();
		$newElementExpiry     = $capTimeCurrent + ($capInterval * 60 * 60);

		$client_ip            = UtilityHelper::get_user_ip();

		if($fromSupplyFeedImp == 0)
		{
			$user_agent      = $_SERVER['HTTP_USER_AGENT'];
			$md5_data_string = $client_ip.$user_agent.date('d',time()).date('H',time());

			$current_md5     = md5($data_get_string.Configuration::get_instance()->read('admarket_name').$md5_data_string.$come_time.$country);
		}
		else if($fromSupplyFeedImp == 1)
		{
			$md5_data_string = $client_ip.date('d',time()).date('H',time());
			$current_md5     = md5($data_get_string.Configuration::get_instance()->read('admarket_name').$md5_data_string.$come_time.$country."4");
		}

		if($data_md5 == $current_md5 && time() < $come_time)
		{




			$impression_updation = 0;
			if($fromSupplyFeedImp == 0 && $maxImpressionLimit > 0)
			{
				$data_get_array       = explode('.data.',$data_get_string);
				$data_get_array_count = count($data_get_array);

				$capCookieTemp 			  = array();
				$capCookieContent     = "";

				for($i=0;$i < $data_get_array_count;$i++)
				{
					$data_single_array = explode('|',$data_get_array[$i]);
					$aid               = $data_single_array[1];

					if($cookieDataGet != '')
					{
						$cookieFlag      = 0;
						$capCookieArray  = explode('_',$cookieDataGet);

						foreach($capCookieArray as $capKey => $capValue)
						{
							if(trim($capValue) != "")
							{
								$capValueArray = explode('-',$capValue);

								if($capValueArray[2] > $capTimeCurrent)
								{
									if($capValueArray[0] == $aid && $capValueArray[1] < $maxImpressionLimit)
									{
										$capCookieTemp[] = $capValueArray[0]."-".($capValueArray[1]+1)."-".$capValueArray[2];

										$impression_updation = 1;
										$cookieFlag          = 1;
									}
									else
									{
										if($capValueArray[0] == $aid)
										$cookieFlag      = 1;

										$capCookieTemp[] = $capValue;
									}
								}

								if($capValueArray[0] == $aid && $capValueArray[1] >= $maxImpressionLimit && $capValueArray[2] >= $capTimeCurrent)
								unset($data_get_array[$i]);
							}
						}
					}

					if(count($capCookieTemp) == 0 || $cookieFlag == 0)
					{
							$impression_updation = 1;
							$capCookieTemp[]     = $aid."-1-".$newElementExpiry;
					}
				}

				if(count($capCookieTemp) > 0)
				$capCookieContent    = implode("_",$capCookieTemp);


				$trackcontent=array('operation'=>'trackdata','auid'=>$adcodeid,'content'=>$capCookieContent,'expiry'=>0,'dataname'=>$cookie_name,'currentTime'=>$capTimeCurrent);
				$trackcontent=json_encode($trackcontent);

				//POP ads cooke data not tracking from here.Its managed from Query-Controller.php
				//Need to exclude vast player impression case also
				if($adtype != 9){?>
				window.parent.postMessage('<?php echo $trackcontent;?>',"*");
				<?php }


				if(count($data_get_array) >0)
				$data_get_string=implode('.data.',$data_get_array);
				else
				{
					$data_get_string     = "";
					$impression_updation = 0;
				}
			}
			else
			$impression_updation = 1;

			if($impression_updation == 1)
			$this->ImpressionUpdate($data_get_string,$client_ip,$country,$maxImpressionLimit,$sourceType);
		}

		//If img pixel tracking not done properly need to execute below code for 3rd party impression tracking
		if(($sourceType == 1 || $sourceType == 2) && $pixelUrl != "" && ($adtype == 9 || $adtype == 13))
		{
			file_get_contents($pixelUrl);
			if($sourceType == 2 && $vastImpressionUrl != "" && $adtype == 13)
			file_get_contents($vastImpressionUrl);
			die;
		}

		exit;
	}

	function ImpressionUpdate($data_get_string,$currentipaddress,$country,$maxImpressionLimit,$sourceType)
	{
		$time=time();

		$impTime =date("Y",time());
		$impTime.=date("m",time());
		$impTime.=date("d",time());
		$impTime.=date("H",time());

		$impTimeMinute=$impTime.date("i",time());

		$impTimeSecond=$impTimeMinute.date("s",time());

		$db= DAL::get_instance();

		$data_get_array=explode('.data.',$data_get_string);
		$data_get_array_count=count($data_get_array);

		$adtype = 0;

		for($i=0;$i < $data_get_array_count;$i++)
		{
			$data_get_array_data=explode('|',$data_get_array[$i]);

			$uid=$data_get_array_data[0];
			$aid=$data_get_array_data[1];
			$keymapid=$data_get_array_data[2];   //In the case of CPD $keymapid is mapid
			$pid=$data_get_array_data[3];
			$bid=$data_get_array_data[4];
			$impression=$data_get_array_data[5];
			$sid=$data_get_array_data[6];
			$adtype=$data_get_array_data[7];
			$keyid=$data_get_array_data[8];      // Original keyword table row id

			$cpdid=0;
			$mapid=$keymapid;

			if($adtype ==3)
			{
				$keyid    = $mapid;
				$keymapid = 0;
				$cpdid    = $mapid;
			}

			$cpc_impression=0;
			$cpa_impression=0;
			$cpd_impression=0;
			$cpm_impression=0;
			$pop_impression=0;
			$html_impression=0;
			$cpv_impression=0;

			$cpmid=0;

			$cpm_profit=0;
			$cpm_spend=0;

			$cpd_profit=0;
			$cpd_spend=0;

			$cpvid=0;
			$cpv_profit=0;
			$cpv_spend=0;


			$html_profit=0;
			$html_spend=0;

			$pop_profit=0;
			$pop_spend=0;

			$rid=0;
			$prid=0;
			$arefperc=0;
			$prefperc=0;

			if($adtype ==0)
			$cpc_impression=1;

			if($adtype ==6)
			$cpa_impression=1;


			if($adtype ==1 || $adtype ==2 || $adtype ==9 || $adtype ==13)
			{
				if($adtype ==1)
				{
					$cpm_impression=1;
					$cpm_profit=$data_get_array_data[10];
					$cpm_spend=$data_get_array_data[11];
				}

				if($adtype ==2)
				{
					$html_impression=1;
					$html_profit=$data_get_array_data[10];
					$html_spend=$data_get_array_data[11];
				}

				if($adtype ==9)
				{
					$pop_impression=1;
					$pop_profit=$data_get_array_data[10];
					$pop_spend=$data_get_array_data[11];
				}

				if($adtype ==13)
				{
					$cpv_impression=1;
					$cpv_profit=$data_get_array_data[10];
					$cpv_spend=$data_get_array_data[11];
				}


				$rid=intval($data_get_array_data[12]);
				$prid=intval($data_get_array_data[13]);
			}
			else if($adtype == 3)
			{
				$cpd_impression = 1;
				$cpd_profit     = $data_get_array_data[10];
				$cpd_spend      = $data_get_array_data[11];
			}

			if($adtype ==1 || $adtype ==2 || $adtype ==9 || $adtype ==13)
			{
				$impfile=$aid.'_'.$impTime.'.php';

				$ip_string="";
				$cpm_ip_array=array();
				$cpm_ip_array_string="";
				$impression_updation=0;

				if($maxImpressionLimit >0)
				{
					$impfilepath=PATH_TO_ROOT.CACHE_DIR.'/impression/'.$impfile;

					include($impfilepath);

					if(isset($cpm_ip_array) && is_array($cpm_ip_array))
					{
						if(array_key_exists($currentipaddress,$cpm_ip_array))
						{
							$cpmcount=$cpm_ip_array[$currentipaddress];
							if($cpmcount < $maxImpressionLimit)
							{
								$cpm_ip_array[$currentipaddress]=$cpmcount+1;
								$impression_updation=1;
							}
						}
						else
						{
							$cpm_ip_array[$currentipaddress]=1;
							$impression_updation=1;
						}

						foreach($cpm_ip_array as $k1=>$v1)
						{
							if($cpm_ip_array_string !='')
							$cpm_ip_array_string.=',';

							$cpm_ip_array_string.='"'.$k1.'"=>'.$v1;
						}
					}
					else
					{
						$cpm_ip_array_string.='"'.$currentipaddress.'"=>1';
						$impression_updation=1;
					}

					if($impression_updation ==1)
					{
						$filecontent = fopen($impfilepath,'w+');
						flock($filecontent, LOCK_EX);
						fwrite($filecontent,"<?php    \n \$cpm_ip_array=array(");
						fwrite($filecontent,$cpm_ip_array_string);
						fwrite($filecontent,"); \n ?>");
						flock($filecontent, LOCK_UN);
						fclose($filecontent);
					}
				}
				else
				$impression_updation=1;
			}
			else
			$impression_updation=1;
			////////////////////////////////////

			$cpm_spend=str_replace(',','.',$cpm_spend);
			$cpm_profit=str_replace(',','.',$cpm_profit);

			$cpd_spend=str_replace(',','.',$cpd_spend);
			$cpd_profit=str_replace(',','.',$cpd_profit);

			$html_spend=str_replace(',','.',$html_spend);
			$html_profit=str_replace(',','.',$html_profit);

			$pop_spend=str_replace(',','.',$pop_spend);
			$pop_profit=str_replace(',','.',$pop_profit);

			$cpv_spend=str_replace(',','.',$cpv_spend);
			$cpv_profit=str_replace(',','.',$cpv_profit);
			////////////////////////////////////

			if($impression_updation ==1)
			{
				$insert_data=array();

				$insert_data[]=$uid;
				$insert_data[]=$aid;
				$insert_data[]=$keyid;
				$insert_data[]=$keymapid;
				$insert_data[]=$pid;
				$insert_data[]=$bid;
				$insert_data[]=$sid;
				$insert_data[]=$rid;
				$insert_data[]=$prid;
				$insert_data[]=$country;
				$insert_data[]=$cpc_impression;
				$insert_data[]=$cpa_impression;
				$insert_data[]=$cpd_impression;
				$insert_data[]=$cpd_spend;
				$insert_data[]=$cpd_profit;
				$insert_data[]=$cpm_impression;
				$insert_data[]=$cpm_spend;
				$insert_data[]=$cpm_profit;
				$insert_data[]=$pop_impression;
				$insert_data[]=$pop_spend;
				$insert_data[]=$pop_profit;
				$insert_data[]=$html_impression;
				$insert_data[]=$html_profit;
				$insert_data[]=$cpv_impression;
				$insert_data[]=$cpv_spend;
				$insert_data[]=$cpv_profit;
				$insert_data[]=$cpdid;
				$insert_data[]=$impTime;
				$insert_data[]=$impTimeMinute;
				$insert_data[]=$impTimeSecond;
				$insert_data[]=$sourceType;

				$sqladv_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."impression_hourly_".$impTime." (`uid`,`aid`,`kid`,`keymapid`,`pid`,`bid`,`sid`,`ruid`,`rpid`,`country`,`cpc_impression`,`cpa_impression`,`cpd_impression`,`cpd_spend`,`cpd_profit`,`cpm_impression`,`cpm_spend`,`cpm_profit`,`pop_impression`,`pop_spend`,`pop_profit`,`html_impression`,`html_profit`,`cpv_impression`,`cpv_spend`,`cpv_profit`,`cpdid`,`time`,`minute_time`,`second_time`,`source`) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$insert_data);
			}
		}
	}

	function impression_default_action()
	{
		header("Content-type:application/javascript");

		$db		  = DAL::get_instance();
		$adID	  = intval($this->read_page_param(1));
		$adcodeID = intval($this->read_page_param(2));

		$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET default_ad_display_time = ? WHERE id = ?",array(time(),$adcodeID));
		exit;
	}
};
?>
