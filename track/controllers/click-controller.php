<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."browser-helper.php";

include_once COMMON_DIR_PATH.'helpers'.DS."device-helper.php";


include_once LIB_DIR_PATH.DS."dbipclass/dbip.class.php";



require_once LIB_DIR_PATH.'vendor/autoload.php';

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
class ClickController extends ApplicationController
{
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
			    {
			    	$cap_ok=1;
			    }
			}
		}
		else
		$cap_ok=1;
		
		$parameter=substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"validate/")+9);
		
		$parameter=$cap_ok."/".$parameter;
		$set_path=md5($parameter.Configuration::get_instance()->read('admarket_name'));
		
		
		$mk_dif_tm=time()+10;
		setcookie(COOKIE_DIFFERENCE,$set_path,$mk_dif_tm,$this->get_base_path(),$this->get_base_domain());
		
		$clickpath=TRACK_BASE.TRACK_DIR."/index.php?page=click/ad/".$parameter;
		$this->set_variable("clickpath",$clickpath);
	}
	
	function ad_action()
	{
		if(Configuration::get_instance()->read('enable_captcha_verification')==1)
		{
			require_once LIB_DIR_PATH.'recaptcha-master/src/autoload.php';
	
			$recaptcha_public_key=Configuration::get_instance()->read('recaptcha_public_key');
			$this->set_variable('recaptcha_public_key',$recaptcha_public_key);
		}		
		
		
		$db= DAL::get_instance();
		$Browser=new BrowserHelper();
		
		$browsname=$Browser->getBrowser();
		$platform=$Browser->getPlatform();
		$version=$Browser->getVersion();
	    	$useragent=$Browser->getUserAgent();
		$client_ip=UtilityHelper::get_user_ip();
		
		$cap_ok=$this->read_page_param(1);
		$aid=intval($this->read_page_param(2));
		$kid=intval($this->read_page_param(3));
		$adunitid=intval($this->read_page_param(4));
		$sid=intval($this->read_page_param(5));
		$from_ip=$this->read_page_param(6);
		$bs=$this->read_page_param(7);
		$ecommerceid=intval($this->read_page_param(8));		
		$retarget=$this->read_page_param(9);
		$fid=$this->read_page_param(10);
		$fstatus=$this->read_page_param(11);
		
		
		
	
		
		$ads_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND status=1 AND pause_status=0",array($aid));
		$number=$ads_res->get_num_records();
		
		if($number ==0)
		{
			header("location:".BASE);
			exit(0);
		}
		
		$result=$ads_res->fetch_assoc();
		
		$uid=$result['uid'];
		$link=$result['click_url'];
		$amountused=$result['total_budget_used'];
		$budget=$result['total_ad_budget'];
		$adtype=$result['display_type'];   // $adtype   0->PPC   1->CPM  3->Sponsored  6->CPA 12->Affiliate
		
		
		if($adtype ==3)
		$mapid=$kid;
		else
		$mapid=0;
		
		
		if($adtype ==12)
		{
			$cap_ok=1;
			$adunitid=intval($this->read_page_param(3));
			$bs=$this->read_page_param(4);
		}
		
		
		$referral_enabled=$this->get_addon_status('referral_enabled');
		$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
		$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
		$city_enabled=$this->get_addon_status('city-targeting_enabled');
		
		if($city_enabled ==1 && $adtype !=12)
		{
			$record123 =UtilityHelper::get_geo_details_from_ip($client_ip);
			$country=$record123->country_code;
			$state=$record123->region;
			$latitude=$record123->latitude;
			$longitude=$record123->longitude;
			
			$cityid=UtilityHelper::get_cityid_from_latlng_display($country,$state,$latitude,$longitude);
		}
		else
		{
			if(Configuration::get_instance()->read('countrywise_data_tracking') ==1)
			{
				$record=UtilityHelper::get_geo_details_from_ip();
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
		
		
		
		
		
		
		
		$fraud_interval=Configuration::get_instance()->read('fraud_time_interval');
		$max_click_count=Configuration::get_instance()->read('max_no_clicks');
		
		
		
		$category_targeting_enabled=$this->get_addon_status('category-targeting_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$device_enabled=$this->get_addon_status('device-targeting_enabled');
		
		
		$siddata1='';
		$siddata2='';
		$clpath="";
		
		
		if($category_targeting_enabled ==1)
		{
			$siddata1=',`sid`';
			$siddata2=',?';
		}
		
		$refstring='';
		$refstring1='';
		$adv_ref_enabled=0;
		$pub_ref_enabled=0;
		if($referral_enabled ==1)
		{
			$refstring=',`cpc_referral`,`adv_referral`,`pub_referral`';
			$refstring1=',?,?,?';
			
			$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
			$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
		}
		
		
		
		
		
		$current_ip=md5($client_ip);
	
		if($cap_ok=="")
		$cap_ok=0;
		
		if($fid=="")
		$fid=0;
		
		if($fstatus=="")
		$fstatus=0;
		
		if($aid <1 || $kid <0 || $adunitid <1)
		{
			header("location:".BASE);
			exit(0);
		}
	

		
		
		
		$parameter=md5(substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"ad/")+3).Configuration::get_instance()->read('admarket_name'));
		
		$parameter_co=$this->read_cookie_param(COOKIE_DIFFERENCE);
		if($parameter!=$parameter_co)
		{
			header("Location: ".$link);
			exit(0);
		}
		
		$pid_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE id=?",array($adunitid));
		$pid_result=$pid_res->fetch_assoc();
		
		$pid=$pid_result['pubid'];
		
		if($pid =="")
		$pid=0;
		
		if($pid >0)
		{
			$publisher_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));
			$publisher_result=$publisher_res->fetch_assoc();
			
			if($publisher_result['pub_status'] !=1)
			{
				header("location:".BASE);
				exit(0);
			}
			$prid=0;
			if($referral_enabled ==1 && $pub_ref_enabled ==1)
			$prid=intval($publisher_result['rid']);
			
			if($prid >0)
			{
				$datacontent=$this->get_referral_user_active($prid,2);		
		
				$prid			= $datacontent[0];
				$pub_ref_perc	= $datacontent[1];								
			}			
		}
		
		
		
		
		if(Configuration::get_instance()->read('allow_publishers_to_display_their_own_ads') ==0)
		{
			if($uid == $pid)
			{
				header("Location: ".$link);
				exit(0);
			}
		}
		
		
		
		
		$user_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
		$user_result=$user_res->fetch_assoc();
		$rid=0;
		if($referral_enabled ==1 && $adv_ref_enabled ==1)
		$rid=intval($user_result['rid']);
		
		if($rid >0)
		{
			$datacontent=$this->get_referral_user_active($rid,1);		
				
			$rid			= $datacontent[0];
			$adv_ref_perc	= $datacontent[1];
		}			
		
		$new_time=time();
		$currTime=time();
		
		$ntime =date("Y",time());
		$ntime.=date("m",time());
		$ntime.=date("d",time());
		$ntime.=date("H",time());
		
		
		if($ecommerce_enabled ==1)
		{
			$ecommercead=$result['type'];

			if($ecommercead ==7)
			{
				if($ecommerceid ==0)
				$link=$result['headline_link'];
				else
				{
					$ecommercelink=$db->read_single_column("SELECT ad_click_url FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($ecommerceid));

					if($ecommercelink !="")
					$link=$ecommercelink;
					else
					$link=BASE;
				}
			}
		}		
		
		
//******************************** Fraud detection validations ***********************************//

		
/********************************* Device Targeting Restriction **********************************/
		
		
	if($adtype !=12)	
	{
		if($device_enabled ==1 && $adtype !=3)
		{
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			
			DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
			
			$dd = new DeviceDetector($user_agent);
			
			$dd->parse();
			$deviceType = ($dd->isMobile() ? ($dd->isTablet() ? 'tablet' : 'phone') : 'computer');
			
			if($deviceType =='computer')
			$device=0;
			else if($deviceType =='tablet' || $deviceType =='phone')
			$device=1;
			
			$addevice=$db->read_single_column("SELECT device FROM ".TABLE_PREFIX."ads WHERE id=? ",array($aid));
			
			
			
			
			if($addevice !=2 && $device != $addevice)
			{
				header("Location: ".$link);
				exit(0);
			}
			
			
		
			$os_targeting_enabled=Configuration::get_instance()->read('os-targeting_enabled');
			$browser_targeting_enabled=Configuration::get_instance()->read('browser-targeting_enabled');
			
			
			
			if($os_targeting_enabled ==1)
			{
				$oscount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_os_mapping WHERE aid=?",array($aid));				
				
				if($oscount >0)
				{
					if($dd->isBot()) 
					$botInfo = $dd->getBot();
					else 
					{
						$osInfo = $dd->getOs();
						$osname=$osInfo['name'];
						$osid=$db->read_single_column("select id from ".TABLE_PREFIX."os where name=?",array($osname));
						if($osid > 0)
						{
							$adoscount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_os_mapping where aid=? AND osid=?",array($aid,$osid));
							
							if($adoscount ==0)
							{ 
								header("Location: ".$link);
								exit(0);
							}
						}
					}
				}
			}
			
			
			
			if($browser_targeting_enabled ==1)
			{
				if ($dd->isBot()) 
				$botInfo = $dd->getBot();
				else 
				{
					$browsercount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_browser_mapping WHERE aid=?",array($aid));
					
					if($browsercount >0)
					{
						$clientInfo = $dd->getClient();  
						
					 	$browsername=$clientInfo['name'];
					 	$browserid=$db->read_single_column("select id from ".TABLE_PREFIX."browser where name=?",array($browsername));
					 	
					 	if($browserid > 0)
						{
							$adbrcount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_browser_mapping where aid=? AND browserid=?",array($aid,$browserid));
							
							if($adbrcount ==0)
							{ 
								header("Location: ".$link);
								exit(0);
							}
						}
					 	
					 	
					}
				}
			}
		}
		
		
		/************************* Device Targeting Restriction ***************************************************************************************************/
		
		
		
		/*************************** ISP  & Connection Targeting*****************************************************************************/
		
		$isp='';
		$connection='';
		$ispid=0;
		$conn_id=0;
	
	if($isp_enabled ==1 ||$connection_enabled ==1 )
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
							header("Location: ".$link);
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
							header("Location: ".$link);
							exit(0);
						}
					}
			    }
				
			} catch (DBIP_Exception $e) { 
				//echo "error: {$e->getMessage()}\n";
			}
			
		
		}
	}
	/*******************************************************************************************************************************************************************/
	/****************************************** Browser Language Targeting**************************************************************/
	$language=array();
	$language1='';
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
				$language1= implode('', $language_arr);
			}
			
			
			if(count($language_arr)>0)
				{
					$language_data=$db->execute_query("select id from ".TABLE_PREFIX."language where code in (".implode(',', $language_arr).")");
					
					if($language_data->get_num_records()>0)
					{
					    $brlang_id_arr=array();	
						while ($brdata=$language_data->fetch_assoc())
						{
							$brlang_id_arr[]=$brdata['id'];
						}
						if(count($brlang_id_arr )> 0)
						{
							$lng_count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_language_mapping where aid=? AND language_id in ( '.implode(',', $brlang_id_arr).')",array($aid));
						
							if($lng_count ==0)
							{ 
								header("Location: ".$link);
								exit(0);
							}
						}
					}
				}
		}
	}
	/********************************************************************************************************************************/		
		
		
		
		
		
		
		
	/************************* Category Targeting Restriction ************************/
		
		$stringarray=array();
		if($category_targeting_enabled ==1)
		{
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');
	
			if($category_enabled_ads !='')
			$stringarray=explode('_',$category_enabled_ads);
		}
		
		
		if($category_targeting_enabled ==1 && in_array($pid_result['display_type'],$stringarray))
		{
			$siddb=$pid_result['sid'];
			
			if($siddb >0 && $siddb != $sid)
			{
				header("Location: ".$link);
				exit(0);
			}
		}
		
		
		
		if($category_targeting_enabled ==1 && in_array($adtype,$stringarray))
		{
			$siterow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."sites WHERE id=? AND status=1",array($sid));
			$sitedata=$siterow->fetch_assoc();
				
			$catid=$sitedata['catid'];
			
			$catcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));
			
			if($catcount >0)
			{
				$category_string=UtilityHelper::get_category_last_childs_display($catid);				
				
				$catquery="";

				if($category_string !="")
				{
					$catarray=explode(',',$category_string);
					foreach($catarray as $key123=>$value123)
					{
						if($value123 !="")
						{
							if($catquery !="")
							$catquery.=' OR ';
							
							$catquery.=' catid='.$value123.' ';
						}
						
					}
					
				}

				if($catquery !="")
				{
					$catquery=' AND ('.$catquery.') ';
				
					$adcatcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? ".$catquery." ",array($aid));
				}
				else
				$adcatcount=0;	

				
				if($adcatcount ==0)
				{
					header("Location: ".$link);
					exit(0);
				}
			}
		}
		
		/************************* Category Targeting Restriction ************************/
		
		

		/************************** Time Targeting Restriction *****************************/
		
		if($this->get_addon_status('time-targeting_enabled') ==1)
		{
		
			if($aid >0)
			{	
				$datetime=time();
				$date_condition='';
				$date_flag=0;
				if(Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)
				{
				
					if(Configuration::get_instance()->read('date_filter_enabled') ==1)
					{
						$date_condition.=' (a.date_filter =0 OR ((a.date_filter =1 OR a.date_filter =2) AND a.start_date <='.$datetime.' AND a.end_date >='.$datetime.')) ';
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
						$date_condition=' WHERE ('.$date_condition.') ';
						
						$id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ads a ".$date_condition);
						
					
						if($id =='')
						{   
							header("Location: ".$link);
							exit(0);
						}
					}
				}
			}
		}
	}	
		
		
		
		
		/************************** Time Targeting Restriction *****************************/
		
		
		if($pid >0)
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
					
					if($adtype ==0)
					$this->sendFraudNotification($publisher_result['username'],$to,Configuration::get_instance()->read('admarket_name'));
		
					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";
					
					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,2,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;
					
					$db->execute_query($frd_query,$insert_data);
		
					header("Location: ".$link);
					exit(0);
				}
			}
		
			//******** Publisher Fraud Tracking *********//
		
			
			
			
			
			if($adtype !=12)	
			{
				//******** Bot Fraud Validation *************//
				$captchastatus=Configuration::get_instance()->read('captcha_verification');
				$captchatime=Configuration::get_instance()->read('captcha_time_interval');
			
				$pubcaptchastatus=$publisher_result['pub_captcha_status'];
				$pubcaptchatime=$publisher_result['pub_captcha_time'];
			
				$pubcaptchatime=$pubcaptchatime+(60*60*$captchatime);
			
				$imgflag=0;
				if($cap_ok==1)
				{
					$imgflag=1;
					
					if($fid > 0)
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."fraudclicks WHERE `id`=?",array($fid));
				}
				
				$tm=time();
			
				
				
				
				if($pubcaptchastatus==1 && $tm <= $pubcaptchatime && $captchastatus==1 && $imgflag !=1)
				{
					$clpath='click/validate/'.$aid.'/'.$kid.'/'.$adunitid.'/'.$sid.'/'.$from_ip.'/'.$bs.'/'.$ecommerceid.'/'.$retarget;
					
					$this->set_variable('clpath',$clpath);
				}
			
				$bs_key=md5($aid.$kid.$adunitid.$sid.$retarget);
				if($bs != $bs_key && $captchastatus==1 && $clpath =="")
				{
					if($imgflag ==1)
					{
						header("Location: ".$link);
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
	
					$clpath='click/validate/'.$aid.'/'.$kid.'/'.$adunitid.'/'.$sid.'/'.$from_ip.'/'.$bs.'/'.$ecommerceid.'/'.$retarget.'/'.$fid.'/1';
			
					$this->set_variable('clpath',$clpath);
				}
				else if($bs != $bs_key)
				{
					header("Location: ".$link);
					exit(0);
				}
				//******** Bot Fraud Validation *************//
			}
			else
			{
				$bs_key=md5(Configuration::get_instance()->read('admarket_name').$aid.$adunitid);
				if($bs != $bs_key)
				{
					header("Location: ".$link);
					exit(0);
				}
			}
		}
		
		
		
		
		if($clpath =="")
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
			
				header("Location: ".$link);
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
		
			header("Location: ".$link);
			exit(0);
		}
		
				
		//******* Click IP Limit Exceeds *********//
		
		
		
			
			
			
			
			
			
			if($adtype !=12)	
			{
				//******* Invalid IP *********//
				if($from_ip != $current_ip)
				{
					$frd_query="INSERT INTO ".TABLE_PREFIX."fraudclicks (`id`,`pid`,`bid`,`aid`,`org_time`,`time`,`ip`,`fraudtype`,`browser`,`platform`,`version`,`user_agent`,`uid`,`country`,`click_type`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";
					
					$insert_data=array('',$pid,$adunitid,$aid,$new_time,$ntime,$client_ip,3,$browsname,$platform,$version,$useragent,$uid,$country,$adtype);
					if($category_targeting_enabled ==1)
					$insert_data[]=$sid;
					
					$db->execute_query($frd_query,$insert_data);
				
					header("Location: ".$link);
					exit(0);
				}
				
				//******* Invalid IP *********//
				
				//******* Invalid Geo *********//
				
				
				
				$country_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_geographic_mapping WHERE aid=? and country_code <>'0'",array($aid));
				$number=$country_res->get_num_records();
				
				$location_status=0;
				
				if($number ==0)
				$location_status=1;
			
				
				while($country_result=$country_res->fetch_assoc())
				{
					if($country_result['country_code']==$country && $city_enabled  !=1)
					{
						$location_status=1;
						break;
					}
					else if($city_enabled ==1)
					{
						
						if($country_result['country_code'] == $country && $country_result['state_code'] ==0)
						{
							$location_status=1;
							break;
						}
						else if($country_result['country_code'] == $country && $country_result['state_code'] ==$state && $country_result['city'] ==0)
						{
							$location_status=1;
							break;
						}
						else if($country_result['country_code'] == $country && $country_result['state_code'] ==$state && $country_result['city'] ==$cityid)
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
				
					header("Location: ".$link);
					exit(0);
				}
				
				//******* Invalid Geo *********//
			}
			
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
						
				header("Location: ".$link);
				exit(0);
				}
			}	
			//******* Proxy Detection *********//
	
	//******************************** Fraud detection validations ***************************//
	
			
			

			
			
			
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
	
	
			
			if($adtype ==0 && $this->get_addon_status('cpc_enabled') ==1)
			{
	
				$ppc_profit_percentage=$publisher_result['ppc_profit_percentage'];
				$balancestatus=$user_result['balancestatus'];
				$email=$user_result['email'];
				$username=$user_result['username'];	   
				
				if($language_enabled ==1)
				$locale=$user_result['locale'];	    	
				else
				$locale=0;		
				
				
				$admarket_name=Configuration::get_instance()->read('admarket_name');			
				
				
						$cpc_res=$db->execute_query("SELECT default_rate,daily_budget,daily_budget_used,total_ad_budget,total_budget_used FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
						$cpc_result=$cpc_res->fetch_assoc();
						$click_value=$cpc_result['default_rate'];
					
						if(($cpc_result['daily_budget'] == 0 || ($cpc_result['daily_budget'] > 0 && $cpc_result['daily_budget'] >= ($cpc_result['daily_budget_used'] + $click_value))) && $cpc_result['total_ad_budget'] >= ($cpc_result['total_budget_used']+$click_value))
						{ 
						if($click_value >0)
						{
							$newamoutused=$amountused+$click_value;
								
							if($pid >0)
							{
								if($ppc_profit_percentage >0)
								$pub_profit_perc=$ppc_profit_percentage;
								else
								$pub_profit_perc=Configuration::get_instance()->read('profit_percentage');
			
								$newamountbalance=$click_value*$pub_profit_perc/100;
							}
							else
							{
								$newamountbalance=0;
							}
					
					if($newamoutused <= $budget)
					{
						$adv_referral=0;
						$pub_referral=0;
						
						
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
							
							$pub_referral=$newamountbalance*$prefperc/100;
						}
								
						$totalreferral=$adv_referral+$pub_referral;					
						
						$db->execute_query("BEGIN");
						$failed=0;
						
											$org_kid=$db->read_single_column("SELECT kid FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE id=?",array($kid));
												
											$sql="INSERT INTO ".TABLE_PREFIX."dailyclicks (`uid`,`aid`,`kid`,`pid`,`bid`,`clickvalue`,`profit`,`time`,`ip`,`country`,`browser`,`platform`,`version`,`user_agent`,`org_time`,`latitude`,`longitude`".$siddata1.$refstring.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.$refstring1.")";
												
											$insert_data=array($uid,$aid,$org_kid,$pid,$adunitid,$click_value,$newamountbalance,$ntime,$client_ip,$country,$browsname,$platform,$version,$useragent,$currTime,$latitude,$longitude);
												
											if($category_targeting_enabled ==1)
												$insert_data[]=$sid;
													
												if($referral_enabled ==1)
												{
													$insert_data[]=$totalreferral;
													$insert_data[]=$adv_referral;
													$insert_data[]=$pub_referral;
												}
													
												$sql1=$db->execute_query($sql,$insert_data);
													
												if($sql1->error =="")
												{
													$ads_update = $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET daily_budget_used=daily_budget_used+?,total_budget_used=total_budget_used+? WHERE id=?",array($click_value,$click_value,$aid));
			
													if($ads_update->error =="")
													{
														if($pid >0)
														{
															$pubacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_account_balance=pub_account_balance+? WHERE id=?",array($newamountbalance,$pid));
															
															if($pubacupdate->error !="")
															$failed=1;
														}
				
			
														if($referral_enabled ==1 && $failed ==0)
														{
															if($rid >0 && $adv_referral >0)
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
			
			
			
			
													
														
														
														
													
			
														$max_clickvalue=$db->read_single_column("SELECT default_rate FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
			
														if(($budget-$newamoutused)  < $max_clickvalue)
														{
															$pricing_name=$this->get_ad_pricing($aid);
																
															$mail_res1=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."email_templates WHERE id=13");
															$mail_result=$mail_res1->fetch_assoc();
																
																
															if($language_enabled ==1 && $locale >0 && isset($mail_result[$locale.'_message']) && $mail_result[$locale.'_message'] !='')
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
			
		}
		else if($adtype ==1 || $adtype ==3 || $adtype ==6 || $adtype ==12 || $adtype ==13)
		{
	
			
			$orgkid=$db->read_single_column("SELECT kid FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE id=?",array($kid));
			
			if($adtype ==1)
			$clicktype=1;
			else if($adtype ==6)
			{
				$clicktype=6;
				
				$cpa_data=$aid.'-'.$orgkid.'-'.$adunitid.'-'.$sid;
				
				$cpa_interval=Configuration::get_instance()->read('cpa_conversion_tracking_interval');
				
				$cpa_interval=time()+($cpa_interval*24*60*60);
				
				setcookie('cpa_tracking_'.$aid,$cpa_data,$cpa_interval,$this->get_base_path(),$this->get_base_domain());
			}
			else if($adtype ==12)
			{
				$clicktype=12;
				
				$affiliate_data=$aid.'-0-'.$adunitid.'-0';
				
				$affiliate_interval=Configuration::get_instance()->read('affiliate_conversion_tracking_interval');
				
				$affiliate_interval=time()+($affiliate_interval*24*60*60);
				
				setcookie('affiliate_tracking_'.$aid,$affiliate_data,$affiliate_interval,$this->get_base_path(),$this->get_base_domain());
			}
			else if($adtype ==3)
			$clicktype=3;
			else if($adtype ==13)
			$clicktype=13;
			
			
			$sql="INSERT INTO ".TABLE_PREFIX."dailyclicks (`uid`,`aid`,`kid`,`pid`,`bid`,`time`,`ip`,`country`,`browser`,`platform`,`version`,`user_agent`,`org_time`,`click_type`,`latitude`,`longitude`".$siddata1.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.")";
			
			$insert_data=array($uid,$aid,$orgkid,$pid,$adunitid,$ntime,$client_ip,$country,$browsname,$platform,$version,$useragent,$currTime,$clicktype,$latitude,$longitude);
			
			if($category_targeting_enabled ==1)
			$insert_data[]=$sid;
			
			$sql1=$db->execute_query($sql,$insert_data);
			
			
			if($adtype ==3)
			$db->execute_query("UPDATE ".TABLE_PREFIX."sponsored_ad_mapping SET clicks=clicks+1 WHERE id=?",array($mapid));
			
		}

		header("Location: ".$link);
		exit(0);
		}
	}
	
	
	
	function conversion_action()
	{
		$db= DAL::get_instance();
	
		$aid=intval($this->read_page_param(1));
		$adtype=$this->get_ad_pricing_value($aid);
	
		if(($adtype ==6 && $this->get_addon_status('cpa_enabled') !=1) || ($adtype ==12 && $this->get_addon_status('affiliate-ads_enabled') !=1))
			exit;
	
			if($adtype !=6 && $adtype !=12)
				exit;
	
	
				if($this->get_check_advertiser_ad($aid) && $this->get_ad_status_value($aid) ==1)
				{
					$count=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ads WHERE id=? AND pause_status=0 AND display_type =?",array($aid,$adtype));
	
						
					if(intval($count) ==1)
					{
						$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
						$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET tracking_last_checked=? WHERE id=?",array($time,$aid));
	
	
						if($adtype ==6)
							$varstring='cpa_';
							else
								$varstring='affiliate_';
	
	
	
								$cookie_value=$this->read_cookie_param($varstring.'tracking_'.$aid);
	
	
	
								if($cookie_value !="")
								{
									$cookie_array=explode('-',$cookie_value);
										
									$Browser=new BrowserHelper();
									$browsname=$Browser->getBrowser();
									$platform=$Browser->getPlatform();
									$version=$Browser->getVersion();
					    $useragent=$Browser->getUserAgent();
					    $client_ip=UtilityHelper::get_user_ip();
	
					    $city_enabled=$this->get_addon_status('city-targeting_enabled');
					    $category_targeting_enabled=$this->get_addon_status('category-targeting_enabled');
					    $language_enabled=Configuration::get_instance()->read('language_enabled');
					    $referral_enabled=$this->get_addon_status('referral_enabled');
	
					    $cityid=0;
					    	
					    if($adtype ==6 && $city_enabled ==1)
					    {
					    	$record123 =UtilityHelper::get_geo_details_from_ip($client_ip);
					    	$country=$record123->country_code;
					    	$state=$record123->region;
					    	$latitude=$record123->latitude;
					    	$longitude=$record123->longitude;
	
					    	if($country !="" && $state !="" && $latitude !="" && $longitude !="")
					    		$cityid=UtilityHelper::get_cityid_from_latlng_display($country,$state,$latitude,$longitude);
					    }
					    else
					    {
					    	if(Configuration::get_instance()->read('countrywise_data_tracking') ==1)
					    	{
					    		$record=UtilityHelper::get_geo_details_from_ip();
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
	
					    $aid=intval($cookie_array[0]);
					    $kid=intval($cookie_array[1]);
					    $adunitid=intval($cookie_array[2]);
					    $sid=intval($cookie_array[3]);
	
	
					    $siddata1='';
					    $siddata2='';
					    	
					    if($category_targeting_enabled ==1)
					    {
					    	$siddata1=',`sid`';
					    	$siddata2=',?';
					    }
	
					    $refstring='';
					    $refstring1='';
					    $adv_ref_enabled=0;
					    $pub_ref_enabled=0;
					    	
					    if($referral_enabled ==1)
					    {
					    	$refstring=',`'.$varstring.'referral`,`adv_referral`,`pub_referral`';
					    	$refstring1=',?,?,?';
	
					    	$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
					    	$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
					    }
					    	
					    $rid=0;
					    $prid=0;
					    	
	
					    if($aid > 0 && $adunitid >0)
					    {
					    	$ads_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
					    	$result=$ads_res->fetch_assoc();
					    		
					    	$uid=$result['uid'];
					    	$amountused=$result['total_budget_used'];
					    	$budget=$result['total_ad_budget'];
	
						
						$pid_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE id=?",array($adunitid));
						$pid_result=$pid_res->fetch_assoc();
						
						$pid=intval($pid_result['pubid']);
						$pidflag=1;
						
						if($pid >0)
						{
							$publisher_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));
							$publisher_result=$publisher_res->fetch_assoc();
							
							if($publisher_result['pub_status'] !=1)
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
							
							
							if($user_result['adv_status'] ==1)
							{
								$new_time=time();
								$currTime=time();
								
								$ntime =date("Y",time());
								$ntime.=date("m",time());
								$ntime.=date("d",time());
								$ntime.=date("H",time());
							
	
					    					
					    						
								$conv_res=$db->execute_query("SELECT default_rate,total_ad_budget,total_budget_used FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
								$conv_result=$conv_res->fetch_assoc();
								$click_value=$conv_result['default_rate'];
									
								if($conv_result['total_ad_budget'] >= ($conv_result['total_budget_used']+$click_value))
								{
					    								
					    							if($click_value > 0)
					    							{
					    								$newamoutused=$amountused+$click_value;
					    									
					    								if($pid >0)
					    								{
					    									if($publisher_result[$varstring.'profit_percentage'] >0)
					    										$pub_profit_perc=$publisher_result[$varstring.'profit_percentage'];
					    										else
					    											$pub_profit_perc=Configuration::get_instance()->read($varstring.'profit_percentage');
	
					    											$newamountbalance=$click_value*$pub_profit_perc/100;
					    								}
					    								else
					    									$newamountbalance=0;
					    										
					    										
					    									if($newamoutused <= $budget)
					    									{
					    										$adv_referral=0;
					    										$pub_referral=0;
										
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
											
											$pub_referral=$newamountbalance*$prefperc/100;
										}
										
					    												$totalreferral=$adv_referral+$pub_referral;
					    													
	
					    												$db->execute_query("BEGIN");
					    												$failed=0;
	
					    											
					    													$org_kid=0;
					    														
					    													if($adtype ==6)
					    														$org_kid=$db->read_single_column("SELECT kid FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE id=?",array($kid));
					    															
					    															
					    														$sql="INSERT INTO ".TABLE_PREFIX.$varstring."daily_conversions (`uid`,`aid`,`kid`,`pid`,`bid`,`clickvalue`,`profit`,`time`,`ip`,`country`,`browser`,`platform`,`version`,`user_agent`,`org_time`,`latitude`,`longitude`".$siddata1.$refstring.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$siddata2.$refstring1.")";
					    															
					    														$insert_data=array($uid,$aid,$org_kid,$pid,$adunitid,$click_value,$newamountbalance,$ntime,$client_ip,$country,$browsname,$platform,$version,$useragent,$currTime,$latitude,$longitude);
					    															
					    														if($category_targeting_enabled ==1)
					    															$insert_data[]=$sid;
					    																
					    															if($referral_enabled ==1)
					    															{
					    																$insert_data[]=$totalreferral;
					    																$insert_data[]=$adv_referral;
					    																$insert_data[]=$pub_referral;
					    															}
					    																
					    															$sql1=$db->execute_query($sql,$insert_data);
					    																
					    															if($sql1->error =="")
					    															{
					    																$ads_update = $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET total_budget_used=total_budget_used+? WHERE id=?",array($click_value,$aid));
					    																
					    																if($ads_update->error =="")
																						{
						    																if($pid >0)
						    																{
						    																	$pubacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET pub_account_balance=pub_account_balance+? WHERE id=?",array($newamountbalance,$pid));
						    																	if($pubacupdate->error !="")
						    																		$failed=1;
						    																}
	
						    																if($referral_enabled ==1 && $failed ==0)
						    																{
						    																	if($rid >0 && $adv_referral >0)
						    																	{
						    																		$refacupdate=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET referral_balance=referral_balance+? WHERE id=?",array($adv_referral,$rid));
		
						    																		if($refacupdate->error =="")
						    																		{
						    																			$sqlupref=$db->execute_query("UPDATE ".TABLE_PREFIX."referral_hourly_backup SET adv_earning=adv_earning+? WHERE uid=? AND time=? AND type=? LIMIT 1",array($adv_referral,$rid,$ntime,$adtype));
						    																				
						    																			if($sqlupref->get_num_records() ==0)
						    																				$sqlupref=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_hourly_backup (`uid`,`adv_earning`,`time`,`type`) values (?,?,?,?)",array($rid,$adv_referral,$ntime,$adtype));
						    																					
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
	
	
					    																
					    																	$max_clickvalue=$db->read_single_column("SELECT default_rate FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
	
					    																	if(($budget-$newamoutused)  < $max_clickvalue)
					    																	{
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
					    																							
					    																						$subject=str_replace("{PRICING}",$this->get_ad_pricing($aid),$subject);
					    																						$message=str_replace("{PRICING}",$this->get_ad_pricing($aid),$message);
					    																							
					    																						$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
					    																							
					    																						UtilityHelper::send_mail($email,$subject, $message);
					    																	}
					    									}
					    							}
					    				}
							      }
					    	}
					    }
					  }
					}
				}
	
				setcookie($varstring.'tracking_'.$aid,"",0,$this->get_base_path(),$this->get_base_domain());
				exit;
	}
	
	
	
	function default_action()
	{
		$aid=intval($this->read_page_param(1));
		
		$db= DAL::get_instance();		
		if($aid < 1)
		{
			header("location:".BASE);
			exit(0);
		}
		
		$ads_res=$db->execute_query("SELECT click_url FROM ".TABLE_PREFIX."ads WHERE uid=0 AND id=? AND status=1",array($aid));
		$number=$ads_res->get_num_records();
		
		if($number ==0)
		{
			header("location:".BASE);
			exit(0);
		}
		
		$result=$ads_res->fetch_assoc();
		$link=$result['click_url'];
		
		if($link !="")
		{
		header("Location: ".$link);
		exit(0);
		}
		else
		{
			header("location:".BASE);
			exit(0);
		}
	}		


	function data_action()
	{
		$data_get_string=trim($this->read_page_param(1));
		$data_md5=$this->read_page_param(2);
		$come_time=$this->read_page_param(3);  
		$country=$this->read_page_param(4);
		$from_no_script=intval($this->read_page_param(5));
		
		
		if($data_get_string =="")
		exit;

		
		
		if($from_no_script ==0)
		header("Content-type:application/javascript");		
		
		
		$data_get_array=explode('.data.',$data_get_string);
		$data_get_array_count=count($data_get_array);
		
		$adtype=0;
		for($i=0;$i < $data_get_array_count;$i++)
		{
			$data_get_array_data=explode('|',$data_get_array[$i]);
			$adtype=$data_get_array_data[7];
			break;		
		}

		
		
		$dbadlimit=0;
		$interval_time=0;
		
		if($adtype ==1) //CPM
		{
			$dbadlimit=intval(Configuration::get_instance()->read('cpm_ad_impression_limit_hour'));
			$interval_time=intval(Configuration::get_instance()->read('cpm_interval'));
		}
		else if($adtype ==2)  //HTML
		{
			$dbadlimit=intval(Configuration::get_instance()->read('html_ad_impression_limit_hour'));
			$interval_time=intval(Configuration::get_instance()->read('html_interval'));
		}
		else if($adtype ==0 || $adtype ==3 || $adtype ==6)  //CPC/CPD/CPA
		{
			$dbadlimit=1;
			$interval_time=1;
		}
		else if($adtype ==9)  //POP
		{
			$dbadlimit=intval(Configuration::get_instance()->read('pop_ad_impression_limit_hour'));
			$interval_time=intval(Configuration::get_instance()->read('pop_interval'));
		}
		else if($adtype ==13)  //CPV
		{
			$dbadlimit=intval(Configuration::get_instance()->read('cpv_ad_impression_limit_hour'));
			$interval_time=intval(Configuration::get_instance()->read('cpv_interval'));
		}
		
		
		$client_ip=UtilityHelper::get_user_ip();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		$md5_data_string=$client_ip.$user_agent.date('d',time()).date('H',time());
		
		$current_md5=md5($data_get_string.Configuration::get_instance()->read('admarket_name').$md5_data_string.$come_time.$country);


		
		if($data_md5 == $current_md5 && time() < $come_time)           
		{
			if($adtype !=9)
			{
				if($from_no_script ==1){?><script type="text/javascript"><?php }
			?>
				function Set_Cookie( name, value, expires, path, domain, secure ) 
				{	
						if(expires)
						expires = expires * 1000 * 60 * 60;
	
						var expires_date = new Date();
						
						if(expires >0)
				        expires_date.setTime(expires_date.getTime()+expires);
				        else
						expires_date.setHours(expires_date.getHours()+1);
						    
						document.cookie = name + "=" +escape( value ) + ";expires=" + expires_date.toUTCString()  + ( ( path ) ? ";path=" + path : "" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
				}
			<?php if($from_no_script ==1){?></script><?php }?>	
			<?php 
			}

			
			$impression_updation=0;
			if($dbadlimit >0)
			{
				if($interval_time > 0)
				$coexpiry=$interval_time-1;
				else 
				$coexpiry=0;
				
				
				$data_get_array=explode('.data.',$data_get_string);
				$data_get_array_count=count($data_get_array);
				
				$cookiecp='';
				$cocontent='';
				
				
				if($adtype ==1 || $adtype ==2)
				{
					if(isset($_COOKIE['cpm_ads']))
					$cookiecp=$_COOKIE['cpm_ads'];
				}
				else if($adtype ==0 || $adtype ==3 || $adtype ==6)
				{
					if(isset($_COOKIE['imp_ads']))
					$cookiecp=$_COOKIE['imp_ads'];
				}
				else if($adtype ==9)
				{
					if(isset($_COOKIE['pop_ads']))
					$cookiecp=$_COOKIE['pop_ads'];				
				}
				else if($adtype ==13)
				{
					if(isset($_COOKIE['cpv_ads']))
					$cookiecp=$_COOKIE['cpv_ads'];				
				}
				

				for($i=0;$i < $data_get_array_count;$i++)
				{
					$data_single_array=explode('|',$data_get_array[$i]);
					$aid=$data_single_array[1];
					
				
					if($cookiecp !='')
					{
						$cpsflag=0;
						$cocontent='';
						
						$cookiecparray=explode('_',$cookiecp);

						foreach($cookiecparray as $ckey => $cvalue)
						{
							$cvalueexp=explode('-',$cvalue);

							if($cvalueexp[0] == $aid && $cvalueexp[1] < $dbadlimit)
							{
								$conewvalue=$aid.'-'.($cvalueexp[1]+1);
					
								if($cocontent !='')
									$cocontent.='_';
					
								$cocontent.=$conewvalue;
					
								$impression_updation=1;
								$cpsflag=1;
							}
							else
							{
								if($cvalueexp[0] == $aid)
									$cpsflag=1;
					
								if($cocontent !='')
									$cocontent.='_';
					
								$cocontent.=$cvalue;
							}
							
							if($cvalueexp[0] == $aid && $cvalueexp[1] >= $dbadlimit)
								unset($data_get_array[$i]);
						}
					
						if($cpsflag ==0)
						{
							$impression_updation=1;
					
							if($cocontent !='')
								$cocontent.='_';
					
							$cocontent.=$aid.'-1';
						}
												
						$cookiecp=$cocontent;
					}
					else
					{
						$impression_updation=1;
						
						if($cocontent !='')
							$cocontent.='_';
						
						$cocontent.=$aid.'-1';
					}
				}
				
				
				if($from_no_script ==1){?><script type="text/javascript"><?php }
				
				if($adtype ==1 || $adtype ==2){	?>

					Set_Cookie('cpm_ads','<?php echo $cocontent;?>',<?php echo $coexpiry;?>,'<?php echo $this->get_base_path();?>','<?php echo $this->get_base_domain();?>');

				<?php } else if($adtype ==0 || $adtype ==3 || $adtype ==6){	?>

					Set_Cookie('imp_ads','<?php echo $cocontent;?>',<?php echo $coexpiry;?>,'<?php echo $this->get_base_path();?>','<?php echo $this->get_base_domain();?>');

				<?php } else if($adtype ==9){ ?>

					Set_Cookie('pop_ads','<?php echo $cocontent;?>',<?php echo $coexpiry;?>,'<?php echo $this->get_base_path();?>','<?php echo $this->get_base_domain();?>');

				<?php } else if($adtype ==13){ ?>

					Set_Cookie('cpv_ads','<?php echo $cocontent;?>',<?php echo $coexpiry;?>,'<?php echo $this->get_base_path();?>','<?php echo $this->get_base_domain();?>');

				<?php }
				
				if($from_no_script ==1){?></script><?php }
			
				
				if(count($data_get_array) >0)
				$data_get_string=implode('.data.',$data_get_array);
				else
				{
					$data_get_string="";
					$impression_updation=0;
				}
			}
			else 
				$impression_updation=1;
			
			
			if($impression_updation ==1)
			$this->ImpressionUpdate($data_get_string,$client_ip,$country,$dbadlimit);
		}
		
		exit;
	}	
	
	

	
	
	function ImpressionUpdate($data_get_string,$currentipaddress,$country,$dbadlimit)
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
		
		$adtype=0;
		
		
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
				$keyid=0;
				$keymapid=0;
				
				$cpdid=$mapid;
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
			
			if($adtype ==3)
			$cpd_impression=1;
			
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
								
		
		
							if($adtype ==1 || $adtype ==2 || $adtype ==9 || $adtype ==13)
							{
								$impfile=$aid.'_'.$impTime.'.php';
		
								$ip_string="";
								$cpm_ip_array=array();
								$cpm_ip_array_string="";
								$impression_updation=0;
				
				if($dbadlimit >0)
				{
					$impfilepath=PATH_TO_ROOT.CACHE_DIR.'/impression/'.$impfile;
					
					include($impfilepath);
								
					if(isset($cpm_ip_array) && is_array($cpm_ip_array))
					{
						if(array_key_exists($currentipaddress,$cpm_ip_array))
						{
							$cpmcount=$cpm_ip_array[$currentipaddress];
							if($cpmcount < $dbadlimit)
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
									
									$sqladv_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."impression_hourly_".$impTime." (`uid`,`aid`,`kid`,`keymapid`,`pid`,`bid`,`sid`,`ruid`,`rpid`,`country`,`cpc_impression`,`cpa_impression`,`cpd_impression`,`cpm_impression`,`cpm_spend`,`cpm_profit`,`pop_impression`,`pop_spend`,`pop_profit`,`html_impression`,`html_profit`,`cpv_impression`,`cpv_spend`,`cpv_profit`,`cpdid`,`time`,`minute_time`,`second_time`) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$insert_data);
								}
			}
		}
		
	
	function default_update_action()
	{
		header("Content-type:application/javascript");
		
		$db= DAL::get_instance();
		
		$aidstring=$this->read_page_param(1);
		
		
		$time=time();
		
		$impTime =date("Y",time());
		$impTime.=date("m",time());
		$impTime.=date("d",time());
		$impTime.=date("H",time());
		$impTime.=date("i",time());
		$impTime.=date("s",time());
		
		if($aidstring !='')
		{
			$aidarray=explode('_',$aidstring);
			$querystr='';
			$count=count($aidarray);
			for($i=0;$i < $count;$i++)
			{
				if(intval($aidarray[$i]) >0)
				{
					if($querystr !='')
					$querystr.=',';
					
					$querystr.=$aidarray[$i];
				}
			}
			
			
			
			
			
			if($querystr !='')
			$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET last_display=? WHERE id IN (".$querystr.")",array($impTime));	
			
		}
		
		exit;
	}

	
};
?>