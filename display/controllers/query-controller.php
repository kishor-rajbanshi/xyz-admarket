<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."device-helper.php";



/********* Move this section to action section*************/

include_once LIB_DIR_PATH.DS."dbipclass/dbip.class.php";
	
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

/********* Move this section to action section*************/


class QueryController extends ApplicationController
{
	var $cfile_name="";
    var $total_impressions=0;
	var $cache_encip=""; 
    var $cache_ip=""; 
    var $cache_user_agent="";
    var $cache_date="";	
	
	
	
	var $impression_updated=0;
	var $display_type_value=0;
	
	
	var $pop_ad_string_data="";
	var $originalpopad=0;
	var $popaid=0;
	
	var $cpm_impression_updated=0;
	var $cpm_ad_strings_array='';
	var $current_site_name='';
	var $current_site_id=0;
	
	var $cache_cpm_string="";
	

	var $cpc_enabled_value=0;
	var $cpm_enabled_value=0;
	var $html_enabled_value=0;
	var $interstitial_enabled_value=0;
	var $category_enabled_value=0;
	var $sponsored_enabled_value=0;
	var $device_enabled_value=0;
	var $cpa_enabled_value=0;
	var $textimage_enabled_value=0;
	var $referral_enabled_value=0;
	
	
	
	var $video_enabled_value=0;	
	
	
	var $ppc_tracking_interval=0;
	var $cpa_tracking_interval=0;
	var $cpm_tracking_interval=0;
	var $html_tracking_interval=0;
	var $interstitial_tracking_interval=0;
	var $sponsored_tracking_interval=0;
	
		
	
	var $pop_enabled_value=0;
	var $pop_tracking_interval=0;
	var $video_tracking_interval=0;
	
	
	
	var $geo_total_impressions=0;
	var $geo_country="";
	var $geo_tracking_enabled=0;
	
	
	var $language_targeting_enabled=0;
	var $isp_targeting_enabled=0;
	
	
	var $popdirect=0;
	
	var $vast_aid=0;
	var $vast_ad_get=0;
	var $vast_ad_type=0;
	var $vast_ad_duration=0;
	var $vast_ad_tracking_time=0;
	

	
	function items_action()
	{ 
		$time=time();
		
		$impTime =date("Y",time());
		$impTime.=date("m",time());
		$impTime.=date("d",time());
		$impTime.=date("H",time());
		
		$impTimeMinute=$impTime.date("i",time());
		
		/************  memcache server connection check **********/
		
		$mem_obj=$this->memcache_connect();
		
		/************  memcache server connection check **********/
		
		$client_ip=UtilityHelper::get_user_ip(); 
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$adunitid=intval($this->read_get_param("aduid"));
		$displaytype=intval($this->read_get_param("displaytype"));
		
		$popdirect=0;
		
		if($displaytype ==9)
		$popdirect=intval($this->read_get_param("direct"));
		
		$this->popdirect=$popdirect;
		

		$expandable_type              = 0;
		$expandable_style        	  = 0;
		$expandablesupport			  = 0;
		$expandable_width			  = 0;
		$expandable_height			  = 0;		
		
		
		
		$debugmode_status = Configuration::get_instance()->read('debugmode_enable');

		$debugmode_values = array();

		$debug_adunitid = "";

		if($debugmode_status == 1)
		{
			$debugmode_values = json_decode(Configuration::get_instance()->read('debugmode_values'),true);
			$debug_adunitid = $debugmode_values['adcode-id'];
		}

		if($adunitid != $debug_adunitid)
		{
			$debugmode_status = 0;
		}
		
		$ipagent=$client_ip.$user_agent;	
		
        $this->cache_ip=$client_ip;
        $this->cache_user_agent=$user_agent;
        $this->cache_date=date('d',time()).date('H',time());		
		

		
		$this->set_variable("client_ip",$client_ip);
		$encip=md5($client_ip);
		
		$this->cache_encip=$encip;
		
		if(Configuration::get_instance()->read('proxy_detection_for_ad_display') ==1)
		{
			if(UtilityHelper::proxyDetection())	
			{
				echo $this->remove_iframe($adunitid,1);
				die;	
			}
		}
		
		$retargeting_addon_code = "XYZADMRTG";
		$device_targeting_addon_code = "XYZADMDEV";
		$city_targeting_addon_code = "XYZADMCTY";
		$time_targeting_addon_code = "XYZADMTME";
		$category_targeting_addon_code = "XYZADMCAT";
		$language_targeting_addon_code = "XYZADMLNG";
		$isp_targeting_addon_code = "XYZADMISP";
		
		$user_status_check_string = "";

		if(($debugmode_status == 1 && $debugmode_values['user-status-check'] == 1) || $debugmode_status != 1)
		$user_status_check_string = " AND a.user_status =1 ";
		

		$ad_status_check_string = "";

		if(($debugmode_status == 1 && $debugmode_values['ad-status-check'] == 1) || $debugmode_status != 1)
		$ad_status_check_string = " AND a.status =1 ";


		$ad_pause_status_check_string = "";

		if(($debugmode_status == 1 && $debugmode_values['pause-status-check'] == 1) || $debugmode_status != 1)
		$ad_pause_status_check_string = " AND a.pause_status =0 ";


	
		if($debugmode_status == 1 && $debugmode_values[$city_targeting_addon_code] == 1)
		$city_enabled = 1;
		elseif($debugmode_status == 1 && $debugmode_values[$city_targeting_addon_code] != 1)
		$city_enabled = 0;
		elseif($debugmode_status != 1)
		$city_enabled=$this->get_addon_status('city-targeting_enabled');
		

		
		if($debugmode_status == 1 && $debugmode_values[$device_targeting_addon_code] == 1)
		$device_targeting_enabled = 1;
		elseif($debugmode_status == 1 && $debugmode_values[$device_targeting_addon_code] != 1)
		$device_targeting_enabled = 0;
		elseif($debugmode_status != 1)
		{	
			$device_targeting_enabled=$this->get_addon_status('device-targeting_enabled');
		}


		
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		
		
		$sticky_enabled=$this->get_addon_status('sticky-ad-display_enabled');
		if($debugmode_status == 1 && $debugmode_values[$category_targeting_addon_code] == 1)
		$category_targeting_enabled = 1;
		elseif($debugmode_status == 1 && $debugmode_values[$category_targeting_addon_code] != 1)
		$category_targeting_enabled = 0;
		elseif($debugmode_status != 1)
		{
			$category_targeting_enabled=$this->get_addon_status('category-targeting_enabled');
		}

		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		
		
		
		
		
		if($debugmode_status == 1 && $debugmode_values[$time_targeting_addon_code] == 1)
		$time_targeting_enabled = 1;
		elseif($debugmode_status == 1 && $debugmode_values[$time_targeting_addon_code] != 1)
		$time_targeting_enabled = 0;
		elseif($debugmode_status != 1)
		{
			$time_targeting_enabled=$this->get_addon_status('time-targeting_enabled');
		}

		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
		$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		
		$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$html5_enabled=$this->get_addon_status('html5-ads_enabled');
		if($debugmode_status == 1 && $debugmode_values[$retargeting_addon_code] == 1)
		$retargeting_enabled = 1;
		
		elseif($debugmode_status == 1 && $debugmode_values[$retargeting_addon_code] != 1)
		$retargeting_enabled = 0;
		
		elseif($debugmode_status != 1)
		{
			$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
		}


		if($debugmode_status == 1 && $debugmode_values[$language_targeting_addon_code] == 1)
		$language_targeting_enabled = 1;
		
		elseif($debugmode_status == 1 && $debugmode_values[$language_targeting_addon_code] != 1)
		$language_targeting_enabled = 0;
		
		elseif($debugmode_status != 1)
		{
			$language_targeting_enabled=$this->get_addon_status('language-targeting_enabled');
			
		}

		$referral_enabled=$this->get_addon_status('referral_enabled');
		
		if($debugmode_status == 1 && $debugmode_values[$isp_targeting_addon_code] == 1)
		$isp_connection_enabled = 1;
		
		elseif($debugmode_status == 1 && $debugmode_values[$isp_targeting_addon_code] != 1)
		$isp_connection_enabled = 0;
		
		elseif($debugmode_status != 1)
		{
			$isp_connection_enabled=$this->get_addon_status('isp-connection-targeting_enabled');
			
		}
		
		if($isp_connection_enabled==1)
		{
			if($debugmode_status == 1 && $debugmode_values['isp-targeting'] == 1 )
			$isp_enabled = 1;
			
			elseif($debugmode_status == 1 && $debugmode_values['isp-targeting'] != 1)
			$isp_enabled = 0;
			
			elseif($debugmode_status != 1)
			
			$isp_enabled=$this->get_addon_status('isp-targeting_enabled');
			

			if($debugmode_status == 1 && $debugmode_values['connection-targeting'] == 1 )
			$connection_enabled = 1;
			
			elseif($debugmode_status == 1 && $debugmode_values['connection-targeting'] != 1)
			$connection_enabled = 0;
			
			elseif($debugmode_status != 1)
			
			$connection_enabled=$this->get_addon_status('connectiontype-targeting_enabled');
			
			
		}
		else
		{
			$isp_enabled=0;
			$connection_enabled=0;
		}
		
		$native_enabled=$this->get_addon_status('native-ad-display_enabled');
		
		
		
		$specialcache="";
		$country="";
		$state="";
		$cityname="";
		$latitude="";
		$longitude="";
		
		
		if($city_enabled ==1)
		{
			$record =UtilityHelper::get_geo_details_from_ip($client_ip);
			$country=$record->country_code;
			$state=$record->region;
			$cityname=$record->city;
			$latitude=$record->latitude;
			$longitude=$record->longitude;
			
			$specialcache=$state.$cityname;
		}
		else
		{
			$country =UtilityHelper::get_country_from_ip($client_ip);
		}
		
		

		
		
		$this->geo_country=$country;
		
		$number_balance=0;
	
		$ads_title="";
		$ads_descs="";
		
		
		$page_data=trim($this->read_get_param("page_data"));
		$key_time=intval($this->read_get_param("time"))+40;
		
		$validator=md5($client_ip."ADM-DATA-ADM".$user_agent.$key_time);

		
		if(Configuration::get_instance()->read('drop_suspicious_ad_query_requests') ==1)
		{
			if($popdirect ==0)
			{
				if(($debugmode_status == 1 && $debugmode_values['page-data-validation'] == 1) || $debugmode_status != 1)
				{
					if($page_data != $validator)
					{
						echo $this->remove_iframe($adunitid,2);
						exit;
					}
				}
			}
		}
		
		
		$ad_container_width=intval($this->read_get_param("width"));
		$ad_container_height=intval($this->read_get_param("height"));
		
		
		
		$display_site=strtolower(urldecode(trim($this->read_get_param("deliver"))));
		$key_query_string=urldecode(trim($this->read_get_param("search_keywords")));
		$ads_title=urldecode(trim($this->read_get_param("page_title")));
		$ads_descs=urldecode(trim($this->read_get_param("meta_description")));
		$page_referrer=$this->mybase64_decode(trim($this->read_get_param("page_referrer")));

		$retarget=$this->read_cookie_param('ads_retarget');
		$retarget_already=$this->read_cookie_param('ads_retarget_exclude');

		
		$native=0;
		
		if($native_enabled ==1)
		$native=intval($this->read_get_param("native"));
		
		$sid=0;
		$catid=0;
		$sitename='';
		
		
		$cacheDevice='';
		$osid=0;
		$osname='';
		$browserid=0;
		$browsername='';
			
			
		if($device_targeting_enabled ==1)
		{
			DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
			
			$dd = new DeviceDetector($user_agent);
			
			$dd->parse();
			$deviceType = ($dd->isMobile() ? ($dd->isTablet() ? 'tablet' : 'phone') : 'computer');
			
			if($deviceType =='computer')
			$cacheDevice='c';
			else if($deviceType =='tablet' || $deviceType =='phone')
			$cacheDevice='m';
			
		
			$os_targeting_enabled=Configuration::get_instance()->read('os-targeting_enabled');
			$browser_targeting_enabled=Configuration::get_instance()->read('browser-targeting_enabled');
			
			
			
			if($os_targeting_enabled ==1)
			{
				
				if ($dd->isBot()) {
					// handle bots,spiders,crawlers,...
					$botInfo = $dd->getBot();
				} else {
					$osInfo = $dd->getOs();
					$osname=$osInfo['name'];
				$cacheDevice=$cacheDevice.$osname;
				}
				
			}
			
			if($browser_targeting_enabled ==1)
			{
				
				if ($dd->isBot()) {
					// handle bots,spiders,crawlers,...
					$botInfo = $dd->getBot();
				} else {
					$clientInfo = $dd->getClient(); // holds information about browser, feed reader, media player, ...
					
				 	$browsername=$clientInfo['name'];
				 	$cacheDevice=$cacheDevice.$browsername;
				 	
				}
			
			
			
			}
		}
		
		

		
		
		
		/***************************** Ad Retargeting **************************/
		$retarget_string="";
		$retarget_exclude="";
		$retarget_mapping_join="";
		$retarget_cache="";
		$retarget_display=0;

		if($retargeting_enabled ==1 && $retarget !="")
		{
			$retargetarray=explode(',',$retarget);

			foreach($retargetarray as $rkey=>$rvalue)
			{
				if($rvalue !="")
				{
					$rvaluearray=explode('-',$rvalue);

					if($rvaluearray[2] > time())
					{
						if($retarget_string !="")
						$retarget_string.=",";

						$retarget_string.=$rvaluearray[1];
					}
				}
			}



			if($retarget_string !="")
			{
				$retarget_cache=$retarget_string;

				$retarget_display=1;

				$retarget_string=" AND rm.rid IN (".$retarget_string.") ";

				$retarget_mapping_join=' INNER JOIN '.TABLE_PREFIX.'ad_retargeting_mapping rm ON a.id = rm.aid ';



				if($retarget_already !="")
				{
					$explodearray=explode('-',$retarget_already);
					$explodearraylength=count($explodearray);

					$mapstring="";

					for($i=0;$i < $explodearraylength;$i++)
					{
						if($explodearray[$i] !="")
						{
							$explodearray1=explode('.',$explodearray[$i]);

							if($explodearray1[1] > time())
							{
								if($mapstring !="")
								$mapstring.=",";

								$mapstring.=$explodearray1[0];
							}
						}
					}

					if($mapstring !="")
					$retarget_exclude=' AND a.id NOT IN ('.$mapstring.') ';
				}
			}
		}

		/***************************** Ad Retargeting ***************************/

		$isp='';
		$connection='';
		$ispid=0;
		$conn_id=0;
	
	if($isp_enabled ==1 || $connection_enabled ==1)
	{ 
		try
		{
			
		    $db1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);
			
			$dbip = new DBIP_MySQLI($db1);
			$inf = $dbip->Lookup($client_ip,TABLE_PREFIX.'dbip_lookup');
			$isp= $inf->isp_name ;
			$connection=$inf->connection_type ;
			$cacheDevice=$cacheDevice.$isp.$connection;
			
			
		} catch (DBIP_Exception $e) { 
			//echo "error: {$e->getMessage()}\n";
		}
		

	}
	
	
	$language=array();
	$language1='';
	if($language_targeting_enabled==1)
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
	}

		$cache_one=$adunitid.$display_site.$country.$specialcache.$cacheDevice.'0'.$popdirect; // ROS Ads
		$cache_one=$adunitid."-".md5($cache_one).".php";

		$cache_two=$adunitid.$display_site.$country.$specialcache.$cacheDevice.'1'.$ipagent.$retarget_cache.$popdirect; // RON Retargeting
		$cache_two=$adunitid."-".md5($cache_two).".php";

		$cache_three=$adunitid.$display_site.$country.$specialcache.$cacheDevice.'1'.$popdirect; // RON Ads
		$cache_three=$adunitid."-".md5($cache_three).".php";		
		
		
		

		$this->cfile_name=$cache_three;		
		
		$cached_flag=0;
		
		
		
		if(($debugmode_status == 1 && $debugmode_values['cache'] == 1) || $debugmode_status != 1)
		{
			if($sponsored_enabled ==1 && $displaytype ==3)
			{
				include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_one);
				
				if($cached_flag ==1)
				die;
				else if($cached_flag ==2)
				unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_one);
			}
		}
		

		if($retarget_display ==1)
		{

			if(($debugmode_status == 1 && $debugmode_values['cache'] == 1) || $debugmode_status != 1)
			{
				include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_two);
				
				if($cached_flag ==1)
				die;
				else if($cached_flag ==2)
				unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_two);				
			}
		}


		if(($debugmode_status == 1 && $debugmode_values['cache'] == 1) || $debugmode_status != 1)
		{
			if($retarget_display ==0)
			{
				include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_three);
					
				if($cached_flag ==1)
				die;
				else if($cached_flag ==2)
				unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_three);			
			}
		}
		

		$db= DAL::get_instance();
		
	
		if($mem_obj !=false && ADUNIT_MEMCACHE_ENABLED == 1)
		{     
			$adunit_array=$mem_obj->get('adunit_res_'.$adunitid);
		
		}
		
		if($mem_obj !=false && ADUNIT_MEMCACHE_ENABLED == 1 && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
		{
			$adblock_array=$mem_obj->get('adblock_res_'.$adunit_array['ad_block_id']);      
			$credit_txt_array=$mem_obj->get('credit_txt_'.$adunit_array['credittext']); 
			$result=array_merge($adunit_array,$adblock_array,$credit_txt_array);
			$number=count($result);
		}
		else 
		{
			if($displaytype ==9 || $native==1)
			$res=$db->execute_query("select au.*,au.id as auid,au.name as auname from ".TABLE_PREFIX."adunit au where au.id=?",array($adunitid));
			else
			$res=$db->execute_query("select ab.*,au.*,ab.id as adblockid,au.id as auid,au.name as auname,au.credittext,at_color,ad_color,au_color,ab_color,ac_color,abr_color,abr_type,ab.credit_text,ab.banner_type from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au  where ab.id=au.blockid and au.id=?",array($adunitid));
		
		
		$number=$res->get_num_records();
			
		$result=$res->fetch_assoc();
		
		
		
		if($mem_obj != false && ADUNIT_MEMCACHE_ENABLED == 1)
		{
			$mem_adunit_array=array(
									"name"=>$result["name"],
									"auid"=>$result["auid"],
									"pubid"=>$result["pubid"],		
									"display_type"=>$result["display_type"],							
									"ad_block_id"=>$result["blockid"],
									"credittext"=>$result["credittext"],			
									"at_color"=>$result["at_color"],
									"ad_color"=>$result["ad_color"],
									"au_color"=>$result["au_color"],
									"ab_color"=>$result["ab_color"],
									"abr_color"=>$result["abr_color"],    
									"abr_type"=>$result["abr_type"],
									"ac_color"=>$result["ac_color"],
									"video_type"=>$result["video_type"],     
			
			
			
									"native"=>$result["native"],                           //Native
									"layout"=>$result["layout"],
									"nativeimg_position"=>$result["nativeimg_position"],
									"nativeimg_dimension"=>$result["nativeimg_dimension"],									
									"responsive"=>$result["responsive"],
									"custom_code"=>$result["custom_code"],
									"htxt_color"=>$result["htxt_color"],
									"htxt_bgcolor"=>$result["htxt_bgcolor"],		

			
									"sid"=>$result["sid"],                                 //Category
									
									"pop_up_support"=>$result["pop_up_support"],		   //POP
									"pop_under_support"=>$result["pop_under_support"],
									"pop_tab_support"=>$result["pop_tab_support"],
									

									"container_id"=>$result["container_id"]				   //Skin
					);
			
			$mem_obj->set("adunit_res_".$adunitid,$mem_adunit_array,MEMCACHE_EXPIRY);
			
			if($displaytype !=9 && $native !=1)
			{
			
			$mem_adblock_array=array(
									"width"=>$result["width"],
									"height"=>$result["height"],
									"textadcount"=>$result["textadcount"],
									"banner_type"=>$result["banner_type"],
									"type"=>$result["type"],
									"bannersize"=>$result["bannersize"],
									"credit_text"=>$result["credit_text"],
									"tlineheight"=>$result["tlineheight"],
									"tfont"=>$result["tfont"],
									"tsize"=>$result["tsize"],
									"t_weight"=>$result["t_weight"],
									"t_decoration"=>$result["t_decoration"],
									"dlineheight"=>$result["dlineheight"],
									"dfont"=>$result["dfont"],
									"dsize"=>$result["dsize"],
									"d_weight"=>$result["d_weight"],
									"d_decoration"=>$result["d_decoration"],
									"ulineheight"=>$result["ulineheight"],
									"ufont"=>$result["ufont"],
									"usize"=>$result["usize"],
									"u_weight"=>$result["u_weight"],
									"u_decoration"=>$result["u_decoration"],
									"clineheight"=>$result["clineheight"],
									"cfont"=>$result["cfont"],
									"csize"=>$result["csize"],
									"c_weight"=>$result["c_weight"],
									"c_decoration"=>$result["c_decoration"],
									"creditposition"=>$result["creditposition"],
									"creditalignment"=>$result["creditalignment"],
									"lineseperator"=>$result["lineseperator"],
									"orientaion"=>$result["orientaion"],
									"bordertype"=>$result["bordertype"],
									"tcolor"=>$result["tcolor"],
									"dcolor"=>$result["dcolor"],
									"ucolor"=>$result["ucolor"],    
									"ccolor"=>$result["ccolor"],
									"bcolor"=>$result["bcolor"],
									"br_color"=>$result["br_color"],
									"adblockid"=>$result["adblockid"],
									"textimage_size"=>$result["textimage_size"],     
									"image_position"=>$result["image_position"],    

			
									"skin_positions"=>$result["skin_positions"]       //Skin
									
									
									
			);
			
			$mem_obj->set("adblock_res_".$result['blockid'],$mem_adblock_array,MEMCACHE_EXPIRY);
			
			if($result['credittext'] > 0)
			{
				$cres=$db->execute_query("select * from ".TABLE_PREFIX."credittext where id=? ",array($result["credittext"]));
				$cre_txt=$cres->fetch_assoc();
				$mem_credittxt_array=array(
									"ctxt_name"=>$cre_txt["credittext"],
									"cttxt_type"=>$cre_txt["type"],
									"image"=>$cre_txt["image"],
									"ctxt_icon_type"=>$cre_txt["icon_type"],
									"ctxt_icon_content"=>$cre_txt["icon_content"]
			);
			
			$mem_obj->set("credit_txt_".$result['credittext'],$mem_credittxt_array,MEMCACHE_EXPIRY); 
			}
			
			}
			
			
		}
		
		}
	
		$display_type=$result['display_type'];
		$pid=intval($result['pubid']);
		
		
		if($native ==1)		
		$native=$result['native'];
		
		
		$this->set_variable("pid",$pid);
		$this->set_variable("native_enabled",$native_enabled);
		
		$this->set_variable("native",$native);
		
		$total_text_ads=0;
		$cpm_data_enabled=0;
		$ad_container_type=0;
		
		
		if($native_enabled ==0 && $native ==1)
		{
			echo $this->remove_iframe($adunitid,3);
			exit;
		}
		
				
		if($native_enabled==1 && $native==1)
		{ 
			$layout=$result['layout'];
			$lres=$db->execute_query("select * from ".TABLE_PREFIX."nativeads_layout where id=? ",array($layout));
			$lrow=$lres->fetch_assoc();
			$rows=$lrow['rows'];
			$this->set_variable('rows', $rows);
			$columns=$lrow['columns'];
			$this->set_variable('columns', $columns);
			
			$img_postion=$result['nativeimg_position'];
			$ad_container_type=$lrow['type'];
			
			if($lrow['type'] ==2)
			$ad_container_type=4;
			
			$total_text_ads=$rows*$columns;
		}
		

		if($displaytype !=5 && $displaytype !=14 && $displaytype != $display_type)  //For exclude interstitial / skin ads case. Interstitial / skin ads display type 0,1,3,6 etc.
		exit(0);
		
		if($interstitial_enabled ==0 && $displaytype ==5)
		{
			echo $this->remove_iframe($adunitid,4);
			exit;	
		}		
		
		if($skin_enabled ==0 && $displaytype ==14)
		{
			echo $this->remove_iframe($adunitid,5);
			exit;		
		}	
		
		if($video_enabled ==0 && $display_type ==13)
		{
			echo $this->remove_iframe($adunitid,6);
			exit;	
		}
		
		
		
		$html5_player_support=0;
		
		if($video_enabled ==1)
		$html5_player_support=intval(Configuration::get_instance()->read('html5_player_support'));
				
		
		if($video_enabled ==1 && $html5_player_support ==0 && $display_type ==13)
		{
			echo $this->remove_iframe($adunitid,7);
			exit;	
		}	
		
		if($video_enabled ==1 && $display_type ==13 && $result['video_type'] ==1)
		{
			echo $this->remove_iframe($adunitid,8);
			exit;			
		}
		
		$aspect_ratio_id=0;
		$aspect_ratio_value=0;
		$aspect_ratio_string="";
		
		if($video_enabled ==1 && $display_type ==13)
		{
			$this->set_variable('player_width',$result['width']);
			$this->set_variable('player_height',$result['height']);
			
			$aspect_ratio_value=round($result['width']/$result['height'],3);
			
			
			if($aspect_ratio_value >0)
			{
				$aspect_row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."aspect_ratio WHERE aspect_float_value=?",array($aspect_ratio_value));
				
				$aspect_count=$aspect_row->get_num_records();
				
				if($aspect_count >0)
				{
					$aspect_data=$aspect_row->fetch_assoc();
					
					$aspect_ratio_id=$aspect_data['id'];
					
					$minimum_width=$aspect_data['minimum_width'];
					$minimum_height=$aspect_data['minimum_height'];
					
					if($aspect_ratio_value >= 1)
					{
						if($result['height'] < $minimum_height)
						{
							echo $this->remove_iframe($adunitid,9);
							exit;
						}
					}
					else
					{
						if($result['width'] < $minimum_width)
						{
							echo $this->remove_iframe($adunitid,10);
							exit;
						}
					}
				}
			}
			
			if($aspect_ratio_id >0)
			$aspect_ratio_string=" AND a.aspect_ratio=".$aspect_ratio_id." ";
			else
			{
				echo $this->remove_iframe($adunitid,11);
				exit;
			}
			
			$ad_container_type=13;
		}

		
		if($html_enabled ==1 || $cpm_enabled ==1)
		$cpm_data_enabled=1;
		
		
		if($display_type !=9 && $display_type !=13)
		{
			if($display_type !=3)
			{
				if($display_type !=6)
				{
					if((($cpc_enabled ==1 && $cpm_data_enabled ==1) || ($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpm_data_enabled ==1 && $cpa_enabled ==1)) && Configuration::get_instance()->read('ad_preference') ==0 && $pid >0)
					$display_type=4;
				}
			    if($native ==0)
			    $total_text_ads=$result['textadcount'];
			}
			else if($display_type ==3)
			{
				$total_text_ads=1;
			}
			
			if($native ==0)
			{
				if($result['banner_type'] ==1)
				$ad_container_type=5;
				else if($result['banner_type'] ==4)
				$ad_container_type=14;
				else
				$ad_container_type=$result['type'];
			}
		
		}
		
		
		
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
		if($text_ads_enabled ==0 && $ad_container_type ==1)
		{	
			echo $this->remove_iframe($adunitid,12);
			exit(0);
		}
		
		
		if($text_ads_enabled ==0 && $ad_container_type ==3)
		$ad_container_type=2;
		
		
		if($textimage_enabled ==0 && $ad_container_type ==4)
		{
			echo $this->remove_iframe($adunitid,13);
			exit(0);	
		}

		
		if($number <=0 || $number =="")
		{
			echo $this->remove_iframe($adunitid,14);
			exit(0);
		}
		
		
		
		
		$this->cpc_enabled_value=$cpc_enabled;		
		$this->cpm_enabled_value=$cpm_enabled;
		$this->html_enabled_value=$html_enabled;
		$this->interstitial_enabled_value=$interstitial_enabled;
		$this->category_enabled_value=$category_targeting_enabled;
		$this->sponsored_enabled_value=$sponsored_enabled;
		$this->device_enabled_value=$device_targeting_enabled;
		$this->cpa_enabled_value=$cpa_enabled;
		$this->pop_enabled_value=$pop_enabled;
		$this->textimage_enabled_value=$textimage_enabled;
		$this->referral_enabled_value=$referral_enabled;		
		$this->video_enabled_value=$video_enabled;		
		
		
		$this->language_targeting_enabled_value=$language_targeting_enabled;
		$this->isp_targeting_enabled_value=$isp_enabled;
		$this->connectiontype_targeting_enabled_value=$connection_enabled;
		
		$adv_ref_enabled=0;
		$pub_ref_enabled=0;

		if($referral_enabled ==1)
		{
			$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
			$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
		}
		
		
		$this->geo_tracking_enabled=intval(Configuration::get_instance()->read('countrywise_data_tracking'));
		
		if($expandable_enabled ==1)
		{
			$expandable_type=intval(Configuration::get_instance()->read('expandable_type'));
			$expandable_style=intval(Configuration::get_instance()->read('expandable_style'));
		}		
		
		
		
		$ppctrack=0;
		$cpmtrack=0;
		$htmltrack=0;
		$cpatrack=0;
		$sponsoredtrack=0;
		$poptrack=0;
		$videotrack=0;
		
		
		if($display_type !=9 && $display_type !=13)
		{
			if($cpc_enabled ==1)
			$ppctrack=intval(Configuration::get_instance()->read('ppc_impression_tracking_interval'));
			
			if($cpa_enabled ==1)
			$cpatrack=intval(Configuration::get_instance()->read('cpa_impression_tracking_interval'));
			
			
			if($cpm_enabled ==1)
			$cpmtrack=intval(Configuration::get_instance()->read('cpm_impression_tracking_interval'));
			
			if($html_enabled ==1)
			$htmltrack=intval(Configuration::get_instance()->read('html_impression_tracking_interval'));
			
			if($sponsored_enabled ==1)
			$sponsoredtrack=intval(Configuration::get_instance()->read('sponsored_impression_tracking_interval'));
		}
		else if($display_type ==9)
		{
			if($pop_enabled ==1)
			{
				$poptrack=intval(Configuration::get_instance()->read('pop_impression_tracking_interval'));
				
				if($poptrack ==0)
				$poptrack=1;
			}
		}
		else if($display_type ==13)
		{
			if($video_enabled ==1)
			$videotrack=intval(Configuration::get_instance()->read('html5_player_impression_tracking_interval'));
		}
		
		
		
		$this->pop_tracking_interval=$poptrack;
		$this->ppc_tracking_interval=$ppctrack;
		$this->cpa_tracking_interval=$cpatrack;
		$this->cpm_tracking_interval=$cpmtrack;
		$this->html_tracking_interval=$htmltrack;
		$this->sponsored_tracking_interval=$sponsoredtrack;
		$this->video_tracking_interval=$videotrack;
		
		
		
		
		$this->set_variable('ppctrack',$ppctrack);
		$this->set_variable('cpatrack',$cpatrack);
		$this->set_variable('cpmtrack',$cpmtrack);
		$this->set_variable('htmltrack',$htmltrack);
		$this->set_variable('sponsoredtrack',$sponsoredtrack);
		$this->set_variable('videotrack',$videotrack);
				
		
		
		$cityid=0;

		if($city_enabled ==1 && $country !="" && $state !="" && $latitude !="" && $longitude !="")
		$cityid=UtilityHelper::get_cityid_from_latlng_display($country,$state,$latitude,$longitude);
		
		
		
		
		$stringarray=array();
		if($category_targeting_enabled ==1)
		{
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');
	
			if($category_enabled_ads !='')
			$stringarray=explode('_',$category_enabled_ads);
		}
		
		$rotation_variable=Configuration::get_instance()->read('ad_rotation');
		

		/*************** For Site Restriction **************/
		
		if($category_targeting_enabled ==1 && $popdirect ==0)
		{
			$sid=$result['sid'];
			if($sid >0)
			{
				if($mem_obj != false)
				$sitedata=$mem_obj->get('site_'.$sid);
				
				if($mem_obj !=false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
				{  
					$catid=$sitedata['catid'];
					$sitename=strtolower($sitedata['url']);
				}
				else 
				{
					$siterow=$db->execute_query("SELECT catid,url FROM ".TABLE_PREFIX."sites WHERE id=? AND status=1",array($sid));
					$sitedata=$siterow->fetch_assoc();
				
					$catid=$sitedata['catid'];
					$sitename=strtolower($sitedata['url']);
					
					if($mem_obj != false)
					$mem_obj->set('site_'.$sid,$sitedata,MEMCACHE_EXPIRY);
				}
				
				$this->current_site_name=$sitename;
				$this->current_site_id=$sid;
		
				
				if(in_array($display_type,$stringarray))
				{
					/************ Modification For Support blogspot.com/blogspot.in etc ***********/
					$site_checked=0;
					$display_site_array=explode('.',$display_site);
					
					if(isset($display_site_array[1]) && $display_site_array[1] == 'blogspot')
					{
						$site_array=explode('.',$sitename);
						
						if(isset($site_array[0]) && isset($site_array[1]))
						{
							if($display_site_array[0].'.'.$display_site_array[1] != $site_array[0].'.'.$site_array[1])
							{
								echo $this->remove_iframe($adunitid,15);
								exit(0);
							}
							else
							$site_checked=1;
						}
					}
					/************ Modification For Support blogspot.com/blogspot.in etc ***********/
	
					
					if($display_site != $sitename && $site_checked ==0)
					{
						$sitelength=strlen($sitename)+1;
						$displaysub=substr($display_site,-$sitelength);
						
						
						if(($debugmode_status == 1 && $debugmode_values['site-restriction'] == 1) || $debugmode_status != 1)
						{
							if($displaysub != '.'.$sitename)
							{
								echo $this->remove_iframe($adunitid,15);
								exit(0);
							}
						}
					}
				}
			}
		}
		/*************** For Site Restriction **************/
		
		
		
		/*************** For device Restriction **************/
		$devicestring='';
		$devicestring1='';
		$os_string='';
		$os_string1='';
		$browser_string='';
		$browser_string1='';
		if($device_targeting_enabled ==1 && $display_type !=3)
		{
		
			$devicestring.=' AND (a.device IS NULL OR  a.device=2 ';
		
			if($deviceType =='computer')
			{
				if($devicestring !='')
				$devicestring.=' OR ';
		
				$devicestring.=' a.device=0  ';
			}
		
			if($deviceType =='tablet' || $deviceType =='phone')
			{
				if($devicestring !='')
				$devicestring.=' OR ';
				$devicestring1.='  a.device=1 ';
				
			}
			$devicestring.=$devicestring1.' ) ';
		
		
		
		
			/********************************** OS Targeting ********************************/
			if($os_targeting_enabled==1)
			{
				
				if($mem_obj != false && $mem_obj->get($osname)!='')	
				{	
					$osid=$mem_obj->get($osname);                  /****getting os id from memcache ********/
				}
				else
				{
					$osid=$db->read_single_column("select id from ".TABLE_PREFIX."os where name=?",array($osname));
					if($mem_obj != false)
						$mem_obj->set($osname,$osid,MEMCACHE_EXPIRY);
				}	
				
				if($osid > 0)
				{
					$os_string=' AND ( os.osid = '.$osid.' OR os.osid IS NULL OR os.osid=0)';
					
					$os_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_os_mapping os ON a.id = os.aid ';
				}
				$this->set_variable('osid', intval($osid));
			}
			/********************************** OS Targeting ********************************/
			
		
			/********************************** Browser Targeting ********************************/
				
			if($browser_targeting_enabled==1)
			{
			
				if($mem_obj != false && $mem_obj->get($browsername)!='')
				{
					$browserid=$mem_obj->get($browsername);          /****getting browser id from memcache ********/
				}
				else
				{
					$browserid=$db->read_single_column("select id from ".TABLE_PREFIX."browser where name=?",array($browsername));
					if($mem_obj != false)
						$mem_obj->set($browsername,$browserid,MEMCACHE_EXPIRY);
				}
				
				if($browserid > 0)
				{
					$browser_string=' AND ( br.browserid = '.$browserid.' OR br.browserid IS NULL OR br.browserid=0)';
				
					$browser_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_browser_mapping br ON a.id = br.aid ';
				} 
				$this->set_variable('browserid', intval($browserid));
			}
			/********************************** Browser Targeting ********************************/
		}
		/*************** For device Restriction **************/
		
	
		if($ad_container_type != 14 && $display_type !=9 && $native ==0)
		{
			if($ad_container_width != $result['width'] || $ad_container_height != $result['height'])
			{
				echo $this->remove_iframe($adunitid,16);
				exit(0);
			}
		}
		
		
		
		$date_condition='';
		$date_flag=0;
		if($time_targeting_enabled ==1)
		{
			$datetime=time();
			if(Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)
			{
	
	
			
			$date_condition.=' AND ((a.date_filter IS NULL AND a.time_filter IS NULL AND a.day_filter IS NULL) OR (';
				
				
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
			
			
			
			$date_condition.='))';
			
			}
		}
			
		
		$same_account_str="";
		
		if(Configuration::get_instance()->read('allow_publishers_to_display_their_own_ads') ==0)
		{
			if(($debugmode_status == 1 && $debugmode_values['same-user'] == 1) || $debugmode_status != 1)
			{
				if($pid >0)
				$same_account_str=" AND a.uid <> '".$pid."' ";
			}
		}
		
		

		
		$prid=0;
		$site_restriction_str="";
		if($pid >0)
		{	
			if($mem_obj !=false)
				$mem_pub_details=$mem_obj->get('pub_details_'.$pid);
			
			if($mem_obj !=false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
			{
						                 
				if($mem_pub_details['pub_status'] !=1)
				{
					echo $this->remove_iframe($adunitid,17);
					exit(0);
				}
				
				if($display_type ==9 && $popdirect ==1 && ($mem_pub_details['get_pop_link'] ==0 || intval(Configuration::get_instance()->read('pop_direct_link_enabled')) ==0))
				exit(0);
				
				if($cpm_enabled ==1)
				$this->set_variable('specific_profit',$mem_pub_details['cpm_profit_percentage']);
				
				if($video_enabled ==1)
				$this->set_variable('specific_profit_cpv',$mem_pub_details['cpv_profit_percentage']);
				
				if($referral_enabled ==1 && $pub_ref_enabled ==1 && $mem_pub_details['refferal_status']==1)
				$prid=intval($mem_pub_details['rid']); 
			}
			else 
			{
				$pquery_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));
				$query_result=$pquery_res->fetch_assoc();
			
				if($mem_obj != false)
				$mem_obj->set('pub_details_'.$pid,$query_result,MEMCACHE_EXPIRY);
			
			if($query_result['pub_status'] !=1)
			{
				echo $this->remove_iframe($adunitid,17);
				exit(0);
			}

			if($display_type ==9 && $popdirect ==1 && ($query_result['get_pop_link'] ==0 || intval(Configuration::get_instance()->read('pop_direct_link_enabled')) ==0))
			exit(0);			

			if($cpm_enabled ==1)
			$this->set_variable('specific_profit',$query_result['cpm_profit_percentage']);
			
			if($video_enabled ==1)
			$this->set_variable('specific_profit_cpv',$query_result['cpv_profit_percentage']);			
			
			
				if($referral_enabled ==1 && $pub_ref_enabled ==1 && $query_result['refferal_status']==1)
				$prid=intval($query_result['rid']);
			
			}
			$res_site_array=array();
			if($mem_obj !=false)
			$mem_obj->get('pub_restricted_sites_'.$pid);
			if($mem_obj !=false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
			{ 
				$res_site_array = json_decode($mem_obj->get('pub_restricted_sites_'.$pid),1);
				
			}
			else 
			{  
				$site_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array ($pid));
				if($mem_obj !=false)
				$mem_obj->set('pub_restricted_sites_'.$pid,$site_json,MEMCACHE_EXPIRY);
				if($site_json != '')
					$res_site_array = json_decode($site_json);
			}
			
			foreach($res_site_array as $value)
			{
				if($value != "")
				$site_restriction_str .= " AND a.click_url not like '%" . $value . "%' ";
			}
		}
		
	
		$this->set_variable('prid',$prid);
		
		if($mem_obj == false || ADUNIT_MEMCACHE_ENABLED == 0)
		{
			$res->set_result_index(0);
			$this->set_result("res",$res);
		}
		else 
		$this->set_array('res',$result,array(),1);
		
		$bannersize=0;
		
		if($display_type !=9)
		{
			if($native ==1)
			$bannersize=$result['nativeimg_dimension'];
			else 
			{
				if($ad_container_type ==4)
				$bannersize=$result['textimage_size'];
				else if($ad_container_type ==14)
				$bannersize=$result['adblockid'];
				else
				$bannersize=$result['bannersize'];
			}
		}	

		$bannerid=$bannersize;
		
	    
	    
		$refstring="";
		if($referral_enabled ==1 && $adv_ref_enabled ==1)
		$refstring=",a.refferal_id as rid,a.refferal_status ";		
		
	

		
		$imagesupport=0;
		$ecommercesupport=0;
		
		$bannerwidth=0;
		$bannerheight=0;
		
		
		
		if($display_type !=9 && $display_type !=13 && $ad_container_type !=14)
		{
			if($bannersize >0 && $ad_container_type ==4)
			{
				$blockdata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($bannersize));
				$blockdatarow=$blockdata->fetch_assoc();
			
				$bannerwidth=$blockdatarow['width'];
				$bannerheight=$blockdatarow['height'];
		
				$this->set_variable('banner_width',$bannerwidth);
				$this->set_variable('banner_height',$bannerheight);
			
				if($native_enabled ==1)
				{
					$top_aligned_width=$blockdatarow['top_aligned_width'];
					$left_aligned_width=$blockdatarow['left_aligned_width'];
					$top_aligned_height=$blockdatarow['top_aligned_height'];
					$left_aligned_height=$blockdatarow['left_aligned_height'];
					
					$this->set_variable('top_aligned_width',$top_aligned_width);
					$this->set_variable('top_aligned_height',$top_aligned_height);
				
					$this->set_variable('left_aligned_width',$left_aligned_width);
					$this->set_variable('left_aligned_height',$left_aligned_height);
				}	
			}			
			else
			{
				if($bannerid >0)
				{
					$bannerdata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($bannerid));
					$bannerdatarow=$bannerdata->fetch_assoc();
					
					$imagesupport=$bannerdatarow['image_support'];
					$ecommercesupport=$bannerdatarow['ecommerce_support'];
					
					$expandablesupport		= $bannerdatarow['expandable_support'];
					$expandable_width		= $bannerdatarow['expandable_width'];
					$expandable_height		= $bannerdatarow['expandable_height'];				
				}
			}		
			
			if($ecommercesupport ==0)
			$ecommerce_enabled=0;		
		}
		
			
		if($expandablesupport ==0)
		$expandable_enabled=0;			
		
		
		$expandable_string="";
		
		if($expandable_enabled ==1)
		$expandable_string=" ,a.expandable,a.expandable_banner ";
		
		$refstring=$refstring.$expandable_string;
		
		
		
					
			
		if($rotation_variable ==1)
		{
			$ad_changing		= "ORDER BY a.default_rate DESC";
			
			if(time() % 5 == 0)
			$ad_changing.=",a.last_display ASC";
			
			
			$cpa_ad_changing	= "ORDER BY a.default_rate DESC,a.tracking_last_checked DESC";
			
			if(time() % 5 == 0)
			$cpa_ad_changing.=",a.last_display ASC";				
		}
		else
		{
			$ad_changing		= "ORDER BY a.last_display ASC";	
			
			$cpa_ad_changing	= "ORDER BY a.last_display ASC";			
		}

		
		/********************** PPC Variables *************************/
		
		$ppc_budget_str="";

		if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
		$ppc_budget_str=' AND a.pricing_status=1 AND (a.daily_budget =0 OR a.daily_budget > a.daily_budget_used) AND  (a.daily_budget =0 OR (a.daily_budget - a.daily_budget_used) >= a.default_rate) AND (a.total_ad_budget >= (a.total_budget_used + a.default_rate)) ';

		

		/********************** PPC Variables *************************/
		
		/********************** CPA Variables *************************/

		
		
		$cpa_budget_str = "";

		if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
		$cpa_budget_str=' AND a.pricing_status=1 AND a.total_ad_budget > a.total_budget_used AND ((a.total_ad_budget - a.total_budget_used) >= a.default_rate) ';
		

		
		/********************** CPA Variables *************************/
		

		$hc_budget_str='';
		$hc_get_string='';
		
		

		if($html_enabled ==1)
		{
			
			if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
			$hc_budget_str=' AND a.html_default=0 AND a.pricing_status=1 AND (((a.daily_budget >0 AND (a.daily_budget - a.daily_budget_used) >= (a.default_rate/1000)) OR a.daily_budget =0) AND (a.total_ad_budget >= (a.total_budget_used + (a.default_rate/1000)))) ';
			
			
			
			$hc_get_string=' ,a.id as aid,a.default_rate,a.total_ad_budget ';
			
		}

		
		$cpm_budget_str='';
		$cpm_get_string='';
		
		if($cpm_enabled ==1 || ($pop_enabled ==1 && $display_type ==9))
		{
			
			if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
			$cpm_budget_str=' AND a.pricing_status=1 AND (((a.daily_budget >0 AND (a.daily_budget - a.daily_budget_used) >= (a.default_rate/1000)) OR a.daily_budget =0) AND (a.total_ad_budget >= (a.total_budget_used + (a.default_rate/1000)))) ';
			
			
		
			$cpm_get_string=' ,a.id as aid,a.default_rate ';
		}
		
		
		
		
		$cpv_budget_str='';
		$cpv_get_string='';
		if($video_enabled ==1 && $display_type ==13)
		{
			
			if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
			$cpv_budget_str=' AND a.pricing_status=1 AND (((a.daily_budget >0 AND (a.daily_budget - a.daily_budget_used) >= a.default_rate) OR a.daily_budget =0) AND (a.total_ad_budget >= (a.total_budget_used + a.default_rate))) ';
			
		
			$cpv_get_string=' ,a.id as aid,a.default_rate ';
		}		
		
		
		

		/********************************** Keyword Query **********************************/


		if($debugmode_status == 1 && $debugmode_values['keywords'] == 1)
		$keyword_based_display = 1;
		
		elseif($debugmode_status == 1 && $debugmode_values['keywords'] != 1)
		$keyword_based_display = 0;
		
		elseif($debugmode_status != 1)
		$keyword_based_display = Configuration::get_instance()->read('keyword_based_ad_display');

		
		
		$keyword_string  = "";
		
		if($display_type !=3 && $keyword_based_display ==1)
		{
		
			$keywords=explode(",",$key_query_string);
			$ads_title=explode(" ",$ads_title);
			$ads_descs=explode(" ",$ads_descs);
			
			$array1 = array_merge ($keywords, $ads_title,$ads_descs);
			
			$keyword = array_unique($array1);
			
			
			$keyword_string=" (";
			
			foreach($keyword as $k=>$v)
			{
				if(trim($v) !="")
				{
					$key=$db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=? and status=1",array(trim($v)));
			
					if($key >0)
					{
						if($keyword_string !=" (")
						$keyword_string.=" OR ";
			
						$keyword_string.="km.kid = ".$key." ";
					}
				}
			}

			if($keyword_string !=" (")
			$keyword_string.=" OR ";
			
			$keyword_string.="km.kid = 0 ";
			$keyword_string.=" ) ";
			
			
			$keyword_string = " INNER JOIN ".TABLE_PREFIX."ad_keyword_mapping k ON k.id = (SELECT km.id FROM ".TABLE_PREFIX."ad_keyword_mapping km WHERE km.aid = a.id AND ".$keyword_string." LIMIT 0,1) ";
			

			$key_select = " ,k.kid as keyid,COALESCE(k.id,0) as kid ";
		}
		else
		$key_select  = " ,0 as keyid,0 as kid ";
		
		$key_select1 = " ,0 as keyid,0 as kid ";
		
		/********************************** Keyword Query **********************************/

		
		
		/********************************** Geographic Query *******************************/
		
		$country_display_condition = "";

		if($city_enabled ==1 && $cityid >0)
		{
		
			$country_display_condition =" AND (";
			
			$country_display_condition.=" ((g.country_code='".$country."') AND (g.state_code='".$state."') AND (g.city='".$cityid."')) or ";
			
			$country_display_condition.=" ((g.country_code='".$country."') AND (g.state_code='".$state."')) or ";
			
			$country_display_condition.=" ((g.country_code='".$country."') AND (g.state_code='00') AND (g.city='".$cityid."')) or ";
			
			$country_display_condition.=" ((g.country_code='".$country."')) or ";
			
			$country_display_condition.=" ((g.country_code='0') AND (g.state_code='0') AND (g.city='0')) ";
			
			$country_display_condition.=" )";
		
		}
		else if(($debugmode_status == 1 && $debugmode_values['country'] == 1) || $debugmode_status != 1)
		{
			$country_display_condition=" AND (";
			
			if($country!="")
			$country_display_condition.=" (g.country_code='".$country."') or ";
			
			$country_display_condition.=" (g.country_code='0')) ";
			
		}
		
		
		
		
		
		/********************************** Geographic Query *******************************/

		
		/********************************** Category Targeting ********************************/
		$category_string='';
		$category_string1='';
		if($catid > 0 && in_array($display_type,$stringarray))
		{
			$category_string=UtilityHelper::get_category_last_childs_display($catid);
		
			if($category_string !='')
			$category_string=' AND ( cam.catid IN ('.$category_string.') OR cam.catid IS NULL )';
			else 
			$category_string=' AND cam.catid IS NULL ';
			
			$category_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_category_mapping cam ON a.id = cam.aid ';
		}
		/********************************** Category Targeting ********************************/
		
	/********************************** ISP & Connection Type Targeting ********************************/
		$isp_string='';
		$isp_string1='';
		if($isp_enabled ==1 || $connection_enabled ==1)
		{
			if($isp !='' && $isp_enabled ==1)
		   	$ispid=$db->read_single_column("select id from ".TABLE_PREFIX."isp where name=? and country=?",array($isp,$country));
			
			if($connection !='' && $connection_enabled ==1)
			$conn_id=$db->read_single_column("select id from ".TABLE_PREFIX."connection where name=? ",array($connection));
		
			$isp_string.=' AND (';
			
			if($isp_enabled==1)
			{
				$isp_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_isp_mapping im ON a.id = im.aid ';
				
				if($ispid > 0)
				$isp_string.=' ( im.ispid ='.$ispid.' OR im.ispid IS NULL OR im.ispid=0) ';
				else 
				$isp_string.=' ( im.ispid IS NULL OR  im.ispid=0)';
			}
			if($connection_enabled==1)
			{
				$isp_string1.=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_connection_mapping cn ON a.id = cn.aid ';
				
				if($isp_enabled ==1)
				$isp_string.=' OR ';
		 		if($conn_id >0)
				$isp_string.='  ( cn.conn_id='.$conn_id.' OR cn.conn_id IS NULL OR cn.conn_id=0)';
				else 	
				$isp_string.='  ( cn.conn_id IS NULL OR cn.conn_id=0)';
			}
			
			$isp_string.=' )';
		}
		/********************************** ISP & Connection Type Targeting  ********************************/
		
		/********************************** Language Targeting **********************************************/

		
		$language_string='';
		$language_string1='';
		$language_select ='';		
		
		if($language_targeting_enabled==1)
		{


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
						$language_select=' ,COALESCE(brl.language_id,0) as brl_id, a.direction as dir ' ;
						
						$language_string=' AND ( brl.language_id in ( '.implode(',', $brlang_id_arr).') OR brl.language_id IS NULL OR brl.language_id=0)';
					
						$language_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_language_mapping brl ON a.id = brl.aid ';
					}
				}
			}
		}

		/**********************************Language Targeting *********************************/
		
		
		
		
		$typestring="";
		$typestring1="";
		$special_condition=0;
		
		if($ecommerce_enabled ==1 || $imagesupport ==1)
		$special_condition=1;
		
		
		if($ecommerce_enabled ==1 && $imagesupport ==1)
		{
			$typestring=' AND (a.type =2 OR (a.type =7 AND ecommerce_parent <>0)) ';
			$typestring1=' (a.type =2 OR (a.type =7 AND ecommerce_parent <>0)) ';
		}
		else if($ecommerce_enabled ==1)
		{
			$typestring=' AND a.type =7 AND ecommerce_parent <>0 ';
			$typestring1=' a.type =7 AND ecommerce_parent <>0 ';
		}
		else if($imagesupport ==1)
		{
			$typestring=' AND a.type =2 ';
			$typestring1=' a.type =2 ';
		}
			
		
		
		$query_type_string=$typestring;
		

		
		$html_ad_getting_query='';
		
		$cpv_ad_getting_query='';
		
		$cpm_text_ad_getting_query='';
		$cpm_image_ad_getting_query='';
		$cpm_textimage_ad_getting_query='';
		$cpm_interstitial_ad_getting_query='';
		$cpm_skin_ad_getting_query='';
		
		$ppc_text_ad_getting_query='';
		$ppc_image_ad_getting_query='';
		$ppc_textimage_ad_getting_query='';
		$ppc_interstitial_ad_getting_query='';
		$ppc_skin_ad_getting_query='';
		
		$cpa_text_ad_getting_query='';
		$cpa_image_ad_getting_query='';
		$cpa_textimage_ad_getting_query='';
		$cpa_interstitial_ad_getting_query='';		
		$cpa_skin_ad_getting_query='';
		
		$sponsored_ad_getting_query="";
				
		
		if($sponsored_enabled ==1 && $display_type ==3)
		{
			$spquery=$db->execute_query("SELECT id,aid FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE publisher=? AND position=? AND site=? AND status=2 AND start_time <=? AND end_time >=? LIMIT 0,1",array($pid,$adunitid,$sid,time(),time()));
			$spquerydata=$spquery->fetch_assoc();
			$currentmapid=$spquerydata['id'];
			$currentaid=$spquerydata['aid'];
			
			$ad_container_type=intval($this->get_adtype_from_id($currentaid));
			
			if($ad_container_type ==7)
			$ad_container_type=2;			
			
			
			$this->set_variable('currentmapid',$currentmapid);
			
			$sponsored_ad_getting_query="SELECT a.id as aid,a.click_url,a.banner,a.title, a.description,a.display_url,a.uid as userid,a.display_type as dsp,a.type,0 AS retargetid,0 AS retarget,0 as rid ".$expandable_string." FROM ".TABLE_PREFIX."ads a WHERE a.id='".$currentaid."' AND a.display_type=3 ".$ad_status_check_string." ".$ad_pause_status_check_string." LIMIT 0,1";
		}
		

		
		
		if($html_enabled ==1 && ($display_type ==1 || $display_type ==4))
		{
			$html_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.description ".$key_select.",a.uid as userid,a.display_type as dsp,a.type,0 as rid
			".$hc_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=2
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =2
			".$category_string."
			".$devicestring."
			".$os_string."
			".$browser_string."
			AND a.banner_id ='".$result['bannersize']."'
			".$hc_budget_str."
			".$ad_changing."
			LIMIT 0,1";
		}
		
		
		if($video_enabled ==1 && $display_type ==13)
		{
			$cpv_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type,a.mime_type,a.duration_seconds,a.display_url".$refstring."
			".$cpv_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."			
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=13
			AND a.type =13 
			AND (a.mime_type ='video/mp4' OR a.mime_type ='video/webm' OR a.mime_type ='video/ogg')
			".$aspect_ratio_string."
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."			
			".$user_status_check_string."
			".$same_account_str."
			".$site_restriction_str."
			".$cpv_budget_str."
			".$ad_changing."
			LIMIT 0,1";
		}

		

		
		
		if($cpm_enabled ==1 && ($display_type ==1 || $display_type ==4))
		{
			$cpm_text_ad_getting_query="SELECT 0 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
			".$cpm_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=1
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =1
			{RETARGETCONDITION}
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			".$same_account_str."
			".$site_restriction_str."
			".$cpm_budget_str."
			".$ad_changing."
			LIMIT 0,{TEXTADLIMIT}";
			
			
			
			
			$cpm_image_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.", a.uid as userid,a.display_type as dsp,a.type".$refstring."
			".$cpm_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=1
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			".$query_type_string."
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$cpm_budget_str."
			".$ad_changing."
			LIMIT 0,1";
			
			

			
			
			$cpm_textimage_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid, a.click_url, a.banner, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
			".$cpm_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE  a.id = g.aid
			".$country_display_condition."
			AND a.display_type=1
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =11
			{RETARGETCONDITION}
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'		
			".$same_account_str."
			".$site_restriction_str."
			".$cpm_budget_str."
			".$ad_changing."
			LIMIT 0,{TEXTADLIMIT}";
			
			
					
			
			$cpm_interstitial_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			".$cpm_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=1
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =5
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$cpm_budget_str."
			".$ad_changing."
			LIMIT 0,1";			
			
			

			
			$cpm_skin_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			".$cpm_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=1
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =14
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$cpm_budget_str."
			".$ad_changing."
			LIMIT 0,1";					
		}
		
		
		
		
		if($cpc_enabled ==1 && ($display_type ==0 || $display_type ==4))
		{
			$ppc_text_ad_getting_query="SELECT 0 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=0
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =1
			{RETARGETCONDITION}
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			".$same_account_str."
			".$site_restriction_str."
			".$ppc_budget_str."
			".$ad_changing."
			LIMIT 0,{TEXTADLIMIT}";
			
			$ppc_image_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=0
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			".$query_type_string."
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$ppc_budget_str."
			".$ad_changing."
			LIMIT 0,1";
			
			
			
			
			$ppc_textimage_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid, a.click_url, a.banner, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=0
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =11
			{RETARGETCONDITION}
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'		
			".$same_account_str."
			".$site_restriction_str."
			".$ppc_budget_str."
			".$ad_changing."
			LIMIT 0,{TEXTADLIMIT}";

			
			$ppc_interstitial_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=0
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =5
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$ppc_budget_str."
			".$ad_changing."
			LIMIT 0,1";			
			
			$ppc_skin_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=0
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =14
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$ppc_budget_str."
			".$ad_changing."
			LIMIT 0,1";				
		}
		
				
		
		if($cpa_enabled ==1 && ($display_type ==6 || $display_type ==4))
		{
			$cpa_text_ad_getting_query="SELECT 0 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=6
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =1
			{RETARGETCONDITION}
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			".$same_account_str."
			".$site_restriction_str."
			".$cpa_budget_str."
			".$cpa_ad_changing."
			LIMIT 0,{TEXTADLIMIT}";
			
			
			$cpa_image_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=6
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			".$query_type_string."
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$cpa_budget_str."
			".$cpa_ad_changing."
			LIMIT 0,1";
			
			
			$cpa_textimage_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid, a.click_url, a.banner, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=6
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =11
			{RETARGETCONDITION}
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'		
			".$same_account_str."
			".$site_restriction_str."
			".$cpa_budget_str."
			".$cpa_ad_changing."
			LIMIT 0,{TEXTADLIMIT}";
										
			
			$cpa_interstitial_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=6
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =5
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$cpa_budget_str."
			".$cpa_ad_changing."
			LIMIT 0,1";
						
			
			$cpa_skin_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=6
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =14
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			AND a.banner_id ='".$bannersize."'
			".$same_account_str."
			".$site_restriction_str."
			".$cpa_budget_str."
			".$cpa_ad_changing."
			LIMIT 0,1";
		}
		/************************ Retargeting Ad Display *************************/

		$ppc_retarget_text_ad_getting_query='';
		$ppc_retarget_image_ad_getting_query='';
		$ppc_retarget_textimage_ad_getting_query='';
		$ppc_retarget_interstitial_ad_getting_query='';
		$ppc_retarget_skin_ad_getting_query='';
		
		
		$cpm_retarget_text_ad_getting_query='';
		$cpm_retarget_image_ad_getting_query='';
		$cpm_retarget_textimage_ad_getting_query='';
		$cpm_retarget_interstitial_ad_getting_query='';
		$cpm_retarget_skin_ad_getting_query='';

		$cpa_retarget_text_ad_getting_query='';
		$cpa_retarget_image_ad_getting_query='';
		$cpa_retarget_textimage_ad_getting_query='';
		$cpa_retarget_interstitial_ad_getting_query='';
		$cpa_retarget_skin_ad_getting_query='';


		if($retarget_display ==1)
		{
			if($cpc_enabled ==1 && ($display_type ==0 || $display_type ==4))
			{
				$ad_changing_retarget=" ORDER BY a.default_rate DESC ";
				
				
				$ppc_retarget_text_ad_getting_query="SELECT 1 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select1.", a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=0
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.type =1
				AND a.retargeting =1
				".$user_status_check_string."
				".$same_account_str."
				".$site_restriction_str."
				".$ppc_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,".$total_text_ads;
				
				
				
				$ppc_retarget_image_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid,a.click_url, a.banner ".$key_select1.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=0
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.retargeting =1
				".$query_type_string."
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'
				".$same_account_str."
				".$site_restriction_str."
				".$ppc_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,1";
				
			
				
				$ppc_retarget_textimage_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid, a.click_url, a.banner, a.title, a.description ".$key_select1.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=0
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.type =11
				AND a.retargeting =1
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'		
				".$same_account_str."
				".$site_restriction_str."
				".$ppc_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,".$total_text_ads;
	
				
				$ppc_retarget_interstitial_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid,a.click_url, a.banner ".$key_select1.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=0
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.type =5
				AND a.retargeting =1
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'
				".$same_account_str."
				".$site_restriction_str."
				".$ppc_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,1";			
				
				
				$ppc_retarget_skin_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid,a.click_url, a.banner ".$key_select1.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=0
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.type =14
				AND a.retargeting =1
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'
				".$same_account_str."
				".$site_restriction_str."
				".$ppc_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,1";						
			}
			
						
	
			
			if($cpa_enabled ==1 && ($display_type ==6 || $display_type ==4))
			{
				$cpa_retarget_text_ad_getting_query="SELECT 1 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select1.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=6
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.type =1
				AND a.retargeting =1
				".$user_status_check_string."
				".$same_account_str."
				".$site_restriction_str."
				".$cpa_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,".$total_text_ads;
				
				
				
				
				$cpa_retarget_image_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid,a.click_url, a.banner ".$key_select1.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=6
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.retargeting =1
				".$query_type_string."
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'
				".$same_account_str."
				".$site_restriction_str."
				".$cpa_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,1";
				
				
				
				
				
				
				
				$cpa_retarget_textimage_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid, a.click_url, a.banner, a.title, a.description ".$key_select1.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=6
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.retargeting =1
				AND a.type =11
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'		
				".$same_account_str."
				".$site_restriction_str."
				".$cpa_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,".$total_text_ads;
											
				
				$cpa_retarget_interstitial_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid,a.click_url, a.banner ".$key_select1.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=6
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.retargeting =1
				AND a.type =5
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'
				".$same_account_str."
				".$site_restriction_str."
				".$cpa_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,1";
							
				
				$cpa_retarget_skin_ad_getting_query="SELECT rm.rid AS retargetid,1 as retarget,a.id as aid,a.click_url, a.banner ".$key_select1.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$retarget_mapping_join."
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=6
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.retargeting =1
				AND a.type =14
				".$user_status_check_string."
				AND a.banner_id ='".$bannersize."'
				".$same_account_str."
				".$site_restriction_str."
				".$cpa_budget_str."
				".$retarget_string."
				".$retarget_exclude."			
				".$ad_changing_retarget."
				LIMIT 0,1";				
			}


		}


		/************************ Retargeting Ad Display *************************/
		
		
		
		$pop_ad_strings="";
		$originalad=0;
		$popaid=0;
		
		
		if($display_type ==9 && $pop_enabled ==1)
		{
			$pop_type_string="";
			
			$poptype=0;
			$number=0;
			
			
			
			$day_limit=intval(Configuration::get_instance()->read('pop_display_count_day'));
			
			$this->set_variable('day_limit',$day_limit);
			$this->set_variable('aduid',$adunitid);
			
		
			
			$pop_ads_support=Configuration::get_instance()->read('pop_ads_support');
			$pop_array=explode('-',$pop_ads_support);
			
			
			if($result['pop_up_support'] ==1 && $pop_array[0] ==1)
			$pop_type_string.=' pop_type=1 OR pop_type=4 OR pop_type=6 OR pop_type=7 ';
			
			
			if($result['pop_under_support'] ==1 && $pop_array[1] ==1)
			{
				if($pop_type_string !="")
				$pop_type_string.=' OR ';
				
				$pop_type_string.=' pop_type=2 OR pop_type=4 OR pop_type=5 OR pop_type=7 ';
			}
			
			if($result['pop_tab_support'] ==1 && $pop_array[2] ==1)
			{
				if($pop_type_string !="")
				$pop_type_string.=' OR ';
				
				$pop_type_string.=' pop_type=3 OR pop_type=5 OR pop_type=6 OR pop_type=7 ';
			}

			
			if($pop_type_string =="")
			exit;
			else
			$pop_type_string=' AND ('.$pop_type_string.') ';			
			
			
	
			
		   $pop_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url ".$key_select.",a.uid as userid,a.display_type as dsp,a.pop_type,a.type,a.pop_window_height,a.pop_window_width ".$refstring."
			".$cpm_get_string."
			FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
			".$keyword_string."
			".$category_string1."
			".$os_string1."
			".$browser_string1."
			".$language_string1."
			".$isp_string1."
			WHERE a.id = g.aid
			".$country_display_condition."
			AND a.display_type=9
			".$ad_status_check_string."
			".$ad_pause_status_check_string."
			AND a.type =0
			".$pop_type_string."
			".$category_string."
			".$devicestring."
			".$date_condition."
			".$os_string."
			".$browser_string."
			".$language_string."
			".$isp_string."
			".$user_status_check_string."
			".$same_account_str."
			".$site_restriction_str."
			".$cpm_budget_str."
			".$ad_changing."
			LIMIT 0,1";
			
			
			
			$resquery=$db->execute_query($pop_ad_getting_query);
			$number=$resquery->get_num_records();
					
			$rid=0;
			
			if($number >0)
			{
				$addata=$resquery->fetch_assoc();
				
				$originalad=1;
				
				
				$poptype=$addata['pop_type'];
				
				$popwidth=$addata['pop_window_width'];
				$popheight=$addata['pop_window_height'];
				if($referral_enabled ==1 && $adv_ref_enabled ==1)
				$rid=intval($addata['rid']);
		
				$profit=0;
				$singleimprate=$addata['default_rate']/1000;
		
				if($pid >0)
				{
					if($query_result['pop_profit_percentage'] >0)
					$pop_specific_profit=$query_result['pop_profit_percentage'];
					else
					$pop_specific_profit=Configuration::get_instance()->read('pop_profit_percentage');
					
					$profit=$singleimprate*$pop_specific_profit/100;
				}
				else
			 	$profit=$singleimprate;
				
				$pop_ad_strings=$addata['userid'].'|'.$addata['aid'].'|'.$addata['kid'].'|'.$pid.'|'.$result['auid'].'|1|'.$sid.'|9|'.$addata['keyid'].'|'.$addata['aid'].'|'.$profit.'|'.$singleimprate.'|'.$rid.'|'.$prid;
			
			}
			else
			{
					$default_pop_ad_result="SELECT a.id as aid,a.click_url,a.display_type as dsp,a.type,a.pop_type
					FROM ".TABLE_PREFIX."ads a
					WHERE a.uid = 0
					".$ad_status_check_string."
					".$ad_pause_status_check_string."
					AND a.display_type =9
					AND a.type =0
					ORDER BY a.last_display ASC 
					LIMIT 0,1";
					
				
					$resquery=$db->execute_query($default_pop_ad_result);
					$number=$resquery->get_num_records();
				
					if($number >0)
					{
						$addata=$resquery->fetch_assoc();
						
						$poptype=$addata['pop_type'];
						$popwidth=Configuration::get_instance()->read('pop_window_width');
						$popheight=Configuration::get_instance()->read('pop_window_height');
					}
					else
					exit;
			}
			
			
			$this->pop_ad_string_data=$pop_ad_strings;
			$this->originalpopad=$originalad;
			$this->popaid=$addata['aid'];
			
			
			if($number >0)
			{
				$this->display_type_value=$display_type;
				
				
				$this->set_variable('originalad',$originalad);
				$this->set_variable('pop_ad_strings',$pop_ad_strings);
				$this->set_variable('pop_aid',$addata['aid']);
				$this->set_variable('poptype',$poptype);
				$this->set_variable('click_url',$addata['click_url'],0);
				
				
				$this->set_variable('pop_window_width',$popwidth);
				$this->set_variable('pop_window_height',$popheight);
			}
			else
			exit;
			
		}
		
		
		
		
		
		if($display_type !=9)
		{
			$priority=Configuration::get_instance()->read('ad_display_priority');
			
			if(($ad_container_type !=2 && $ad_container_type !=3) || ($display_type !=1 && $display_type !=4))
			{
				$priority=str_replace('html_','',$priority);
				$priority=str_replace('_html','',$priority);
				$priority=str_replace('html','',$priority);
			}
			
			
			$queryarray=array();
			
			
			if($cpc_enabled ==1)
			{
				$queryarray['ppc'][0]=$ppc_text_ad_getting_query;
				$queryarray['ppc'][1]=$ppc_image_ad_getting_query;
				$queryarray['ppc'][2]="";
				$queryarray['ppc'][3]=$ppc_textimage_ad_getting_query;
				$queryarray['ppc'][4]=$ppc_interstitial_ad_getting_query;
				$queryarray['ppc'][10]=$ppc_skin_ad_getting_query;
				
				
				$queryarray['ppc'][5]=$ppc_retarget_text_ad_getting_query;
				$queryarray['ppc'][6]=$ppc_retarget_image_ad_getting_query;
				$queryarray['ppc'][7]="";
				$queryarray['ppc'][8]=$ppc_retarget_textimage_ad_getting_query;				
				$queryarray['ppc'][9]=$ppc_retarget_interstitial_ad_getting_query;
				$queryarray['ppc'][11]=$ppc_retarget_skin_ad_getting_query;
			}
			
			if($cpm_enabled ==1)
			{
				$queryarray['cpm'][0]=$cpm_text_ad_getting_query;
				$queryarray['cpm'][1]=$cpm_image_ad_getting_query;
				$queryarray['cpm'][2]="";
				$queryarray['cpm'][3]=$cpm_textimage_ad_getting_query;
				$queryarray['cpm'][4]=$cpm_interstitial_ad_getting_query;
				$queryarray['cpm'][10]=$cpm_skin_ad_getting_query;
				
				$queryarray['cpm'][5]=$cpm_retarget_text_ad_getting_query;
				$queryarray['cpm'][6]=$cpm_retarget_image_ad_getting_query;
				$queryarray['cpm'][7]="";
				$queryarray['cpm'][8]=$cpm_retarget_textimage_ad_getting_query;				
				$queryarray['cpm'][9]=$cpm_retarget_interstitial_ad_getting_query;
				$queryarray['cpm'][11]=$cpm_retarget_skin_ad_getting_query;
			}
			
			if($html_enabled ==1)
			{
				$queryarray['html'][0]=$html_ad_getting_query;
				$queryarray['html'][1]='';
				$queryarray['html'][2]='';
				$queryarray['html'][3]='';
				$queryarray['html'][4]='';
				$queryarray['html'][10]='';
				
				$queryarray['html'][5]='';
				$queryarray['html'][6]='';
				$queryarray['html'][7]='';
				$queryarray['html'][8]='';	
				$queryarray['html'][9]='';
				$queryarray['html'][11]='';
			}
			
			if($sponsored_enabled ==1 && $display_type ==3)
			{
				$queryarray['sponsored'][0]=$sponsored_ad_getting_query;
				$queryarray['sponsored'][1]='';
				$queryarray['sponsored'][2]='';
				$queryarray['sponsored'][3]='';
				$queryarray['sponsored'][4]='';
				$queryarray['sponsored'][10]='';
				
				$queryarray['sponsored'][5]='';
				$queryarray['sponsored'][6]='';
				$queryarray['sponsored'][7]='';
				$queryarray['sponsored'][8]='';	
				$queryarray['sponsored'][9]='';		
				$queryarray['sponsored'][11]='';		
			}
			
			
			if($cpa_enabled ==1)
			{
				$queryarray['cpa'][0]=$cpa_text_ad_getting_query;
				$queryarray['cpa'][1]=$cpa_image_ad_getting_query;
				$queryarray['cpa'][2]="";
				$queryarray['cpa'][3]=$cpa_textimage_ad_getting_query;
				$queryarray['cpa'][4]=$cpa_interstitial_ad_getting_query;
				$queryarray['cpa'][10]=$cpa_skin_ad_getting_query;
				
				$queryarray['cpa'][5]=$cpa_retarget_text_ad_getting_query;
				$queryarray['cpa'][6]=$cpa_retarget_image_ad_getting_query;
				$queryarray['cpa'][7]="";
				$queryarray['cpa'][8]=$cpa_retarget_textimage_ad_getting_query;				
				$queryarray['cpa'][9]=$cpa_retarget_interstitial_ad_getting_query;			
				$queryarray['cpa'][11]=$cpa_retarget_skin_ad_getting_query;					
			}
			
			
			if($video_enabled ==1)
			{
				$queryarray['cpv'][0]=$cpv_ad_getting_query;
				$queryarray['cpv'][1]='';
				$queryarray['cpv'][2]='';
				$queryarray['cpv'][3]='';
				$queryarray['cpv'][4]='';
				$queryarray['cpv'][10]='';
				
				
				$queryarray['cpv'][5]='';
				$queryarray['cpv'][6]='';
				$queryarray['cpv'][7]='';
				$queryarray['cpv'][8]='';	
				$queryarray['cpv'][9]='';
				$queryarray['cpv'][11]='';
			}			
			
			
			
			
			
			
			$adget_flag=0;
			$number=0;
			$html_get=0;
			
			
			

			
				if($display_type ==1 || $display_type ==4)
				{
					$ad_display_random=Configuration::get_instance()->read('ad_display_random');
					
					if($ad_display_random ==1)
					{
						$countlist = 0;

						if(isset($queryarray['ppc']))
						$countlist = $countlist+1;

						if(isset($queryarray['cpm']))
						$countlist = $countlist+1;

						if(isset($queryarray['cpa']))
						$countlist = $countlist+1;

						if(isset($queryarray['html']))
						$countlist = $countlist+1;
						
						
						
						
						if($countlist >1)
						{
							$currentsystime=time();
							$currentrandom=$currentsystime % $countlist;
							
							$priority_array=explode('_',$priority);
							
							if($countlist ==2)
							{
								if($currentrandom ==0)
								$priority=$priority_array[0].'_'.$priority_array[1];	
								else 
								$priority=$priority_array[1].'_'.$priority_array[0];
							}
							else if($countlist ==3)
							{
								if($currentrandom ==0)
								$priority=$priority_array[0].'_'.$priority_array[2].'_'.$priority_array[1];
								else if($currentrandom ==1)
								$priority=$priority_array[1].'_'.$priority_array[0].'_'.$priority_array[2];
								else if($currentrandom ==2)
								$priority=$priority_array[2].'_'.$priority_array[1].'_'.$priority_array[0];
							}
							else if($countlist ==4)
							{
								if($currentrandom ==0)
								$priority=$priority_array[0].'_'.$priority_array[2].'_'.$priority_array[1].'_'.$priority_array[3];
								else if($currentrandom ==1)
								$priority=$priority_array[1].'_'.$priority_array[0].'_'.$priority_array[2].'_'.$priority_array[3];
								else if($currentrandom ==2)
								$priority=$priority_array[2].'_'.$priority_array[3].'_'.$priority_array[1].'_'.$priority_array[0];
								else if($currentrandom ==3)
								$priority=$priority_array[3].'_'.$priority_array[1].'_'.$priority_array[2].'_'.$priority_array[0];
							}					
						}
					}
					
					$priority_array=explode('_',$priority);
				}
				else if($display_type ==0)
				{
					$priority_array=array();
					
					$priority_array[]='ppc';
				}
				else if($display_type ==6)
				{
					$priority_array=array();
					
					$priority_array[]='cpa';
				}				
				

				
				
				if($retarget_display ==1)
				{
					foreach($priority_array as $pkey=>$pvalue)
					{
						if($pvalue !='html' && $pvalue !='cpm' && $pvalue !='cpv')
						{
							if($ad_container_type==1)
							{
								$resquery=$db->execute_query($queryarray[$pvalue][5]);
								$number=$resquery->get_num_records();
							}
							else if($ad_container_type==2 && $special_condition ==1)
							{
								$resquery=$db->execute_query($queryarray[$pvalue][6]);
								$number=$resquery->get_num_records();
							}
							else if($ad_container_type==3)
							{
								$currentsystime=time();
								$currentrandom=$currentsystime % 2;								
								
								
								if($currentrandom == 0 && $special_condition ==1)          // First time banner check
								{
									$resquery=$db->execute_query($queryarray[$pvalue][6]);
									$number=$resquery->get_num_records();
									
									if($number > 0)
									$ad_container_type=2;											
								}
								
								
								if($currentrandom == 1 || $number == 0)						// First time text check or no banner exists
								{
									$resquery=$db->execute_query($queryarray[$pvalue][5]);
									$number=$resquery->get_num_records();
									
									if($number > 0)
									$ad_container_type=1;									
								}
								
								
								if($currentrandom == 1 && $number == 0 && $special_condition ==1)  // First time text check and no text exists then banner check again
								{
									$resquery=$db->execute_query($queryarray[$pvalue][6]);
									$number=$resquery->get_num_records();
									
									if($number > 0)
									$ad_container_type=2;											
								}								
							}
							else if($ad_container_type ==5)
							{
								$resquery=$db->execute_query($queryarray[$pvalue][9]);
								$number=$resquery->get_num_records();
							}
							else if($ad_container_type ==4 && $textimage_enabled ==1)
							{
								$resquery=$db->execute_query($queryarray[$pvalue][8]);
								$number=$resquery->get_num_records();
							}
							else if($ad_container_type ==14 && $skin_enabled ==1)
							{
								$resquery=$db->execute_query($queryarray[$pvalue][11]);
								$number=$resquery->get_num_records();
							}
							

							if($number >0)
							{
								$this->cfile_name=$cache_two;		

								if($pvalue =='ppc')
								$pricing=0;
								else if($pvalue =='cpa')
								$pricing=6;

								$adget_flag=1;
								break;
							}
						}
					}
				}				
				

				
				
				if($number ==0)
				{
					if($retarget_display ==1)
					{
						if($display_type !=3)
						{
							if(($debugmode_status == 1 && $debugmode_values['cache'] == 1) || $debugmode_status != 1)
							{
								
								include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_three);
									
								if($cached_flag ==1)
								die;
								else if($cached_flag ==2)
								unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_three);									
							}
						}
						else
						$this->cfile_name=$cache_one;	
					}
					
					
				
					$old_container=$ad_container_type;
					
					
					if($display_type ==0 || $display_type ==1 || $display_type ==4 || $display_type ==6)
					{
						foreach($priority_array as $pkey=>$pvalue)
						{
							if($pvalue =='html')
							{
								$resquery=$db->execute_query($queryarray[$pvalue][0]);
								$number=$resquery->get_num_records();
								if($number >0)
								{
									$html_get=1;
									$ad_container_type=2;
									$display_type=1;
									$adget_flag=1;
									break;
								}
							}
							else 
							{
								if(($cpc_enabled ==1 && ($display_type ==0 || $display_type ==4) && $pvalue =='ppc') || ($cpm_enabled ==1 && ($display_type ==1 || $display_type ==4) && $pvalue =='cpm') || ($cpa_enabled ==1 && ($display_type ==6 || $display_type ==4) && $pvalue =='cpa'))
								{
									if($ad_container_type==1)
									{
										$replacevalue=str_replace("{RETARGETCONDITION}","",$queryarray[$pvalue][0]);
										$replacevalue=str_replace("{TEXTADLIMIT}",$total_text_ads,$replacevalue);
		
										$resquery=$db->execute_query($replacevalue);
										$number=$resquery->get_num_records();								
									}
									else if($ad_container_type==2 && $special_condition ==1)
									{
										$resquery=$db->execute_query($queryarray[$pvalue][1]);								
										$number=$resquery->get_num_records();
									}
									else if($ad_container_type==4 && $textimage_enabled ==1)
									{
										$replacevalue=str_replace("{RETARGETCONDITION}","",$queryarray[$pvalue][3]);
										$replacevalue=str_replace("{TEXTADLIMIT}",$total_text_ads,$replacevalue);
		
										$resquery=$db->execute_query($replacevalue);									
										$number=$resquery->get_num_records();
									}						
									else if($ad_container_type==5 && $interstitial_enabled ==1)
									{
										$resquery=$db->execute_query($queryarray[$pvalue][4]);
										$number=$resquery->get_num_records();
									}
									else if($ad_container_type==14 && $skin_enabled ==1)
									{
										$resquery=$db->execute_query($queryarray[$pvalue][10]);
										$number=$resquery->get_num_records();
									}						
									else if($ad_container_type==3)
									{
										$currentsystime=time();
										$currentrandom=$currentsystime % 2;								
										
										
										if($currentrandom == 0 && $special_condition ==1)          // First time banner check
										{
											$resquery=$db->execute_query($queryarray[$pvalue][1]);
											$number=$resquery->get_num_records();
											
											if($number > 0)
											$ad_container_type=2;											
										}
										
										
										if($currentrandom == 1 || $number == 0)						// First time text check or no banner exists
										{
											$replacevalue=str_replace("{RETARGETCONDITION}","",$queryarray[$pvalue][0]);
											$replacevalue=str_replace("{TEXTADLIMIT}",$total_text_ads,$replacevalue);
		
											$resquery=$db->execute_query($replacevalue);	
											$number=$resquery->get_num_records();
											
											if($number > 0)
											$ad_container_type=1;									
										}
										
										if($currentrandom == 1 && $number == 0 && $special_condition ==1)  // First time text check and no text exists then banner check again
										{
											$resquery=$db->execute_query($queryarray[$pvalue][1]);
											$number=$resquery->get_num_records();
											
											if($number > 0)
											$ad_container_type=2;											
										}																		
									}
									
									if($number >0)
									{
										if($pvalue =='cpm')
										$display_type=1;
										else if($pvalue =='ppc')
										$display_type=0;
										else if($pvalue =='cpa')
										$display_type=6;								
										
										$adget_flag=1;
										break;
									}
								}
							}
						}
					}
					else if($sponsored_enabled ==1 && $display_type ==3)
					{
						$resquery=$db->execute_query($queryarray['sponsored'][0]);
						
						$number=$resquery->get_num_records();
						if($number >0)
						$adget_flag=1;
					}	
					else if($video_enabled ==1 && $display_type ==13)
					{
						$resquery=$db->execute_query($queryarray['cpv'][0]);
						
						$number=$resquery->get_num_records();
						if($number >0)
						$adget_flag=1;
					}										
				}
				else
				{
					if(($ad_container_type ==1 || ($ad_container_type ==4 && $textimage_enabled ==1)) && $number != $total_text_ads)
					$balanceads=$total_text_ads-$number;
					else
					$balanceads=0;


					if($balanceads >0)
					{
						if($pricing ==0)
						$pvalue='cpc';
						else if($pricing ==6)
						$pvalue='cpa';

						if($ad_container_type ==1)
						$replacevalue=str_replace("{TEXTADLIMIT}",$balanceads,$queryarray[$pvalue][0]);
						else if($ad_container_type ==4)
						$replacevalue=str_replace("{TEXTADLIMIT}",$balanceads,$queryarray[$pvalue][6]);

						$replacevalue=str_replace("{RETARGETCONDITION}"," AND a.retargeting=0 ",$replacevalue);
						

						$resquery_balance=$db->execute_query($replacevalue);
						$number_balance=$resquery_balance->get_num_records();

						$number=$number+$number_balance;
					}					
				}
				
		

				if($adget_flag ==1 && $html_get ==1)
				$this->set_result("res1",$resquery,array('description'));
				else if($adget_flag ==1)
				{
					$this->set_result("res1",$resquery,array('click_url'));
					
					if($number_balance >0)
					$this->set_result("res1balance",$resquery_balance,array('click_url'));
				}
		
		
				$default_ad_count=0;
				$default_ad_result_query="";
		
				
				
				
				
		if($display_type !=3)
		{
			if($old_container ==3 || $ad_container_type==1 || $ad_container_type==2)
			{	
				if($number == 0 || ($ad_container_type ==1 && $number >0 && $number < $total_text_ads))
				{
					$default_ad_result_text="";
					$default_ad_result_html="";
					$default_ad_result_image="";
					

					if(($number == 0 && ($ad_container_type ==1 || $old_container ==3)) || ($number >0 && $number < $total_text_ads && $ad_container_type ==1))					
					{
						$default_ad_limit=$total_text_ads-$number;
								
						$default_ad_result_text="SELECT a.id as aid, a.click_url, a.title, a.description, a.display_url,a.type
						FROM ".TABLE_PREFIX."ads a
						WHERE a.uid = 0
						".$ad_status_check_string."
						".$ad_pause_status_check_string."
						AND a.display_type =0
						AND a.type =1
						ORDER BY a.last_display ASC 
						LIMIT 0,".$default_ad_limit;
					}		
					
					if($number == 0 && ($ad_container_type ==2 || $old_container ==3))
					{
						if($html_enabled ==1 && ($display_type ==1 || $display_type ==4))
						{
							$default_ad_result_html="SELECT a.id as aid,a.description,a.display_type as dsp,a.type
							FROM ".TABLE_PREFIX."ads a 
							WHERE a.uid = 0
							".$ad_status_check_string."
							".$ad_pause_status_check_string."
							AND a.display_type=2
							AND a.type =2
							AND a.banner_id ='".$bannersize."'
							AND a.html_default=1 
							ORDER BY a.last_display ASC 
							LIMIT 0,1";
						}
								
						$default_ad_result_image="SELECT a.id as aid,a.click_url,a.banner,a.display_type as dsp,a.type
						FROM ".TABLE_PREFIX."ads a
						WHERE a.uid = 0
						".$ad_status_check_string."
						".$ad_pause_status_check_string."
						AND a.display_type =0
						AND a.type =2
						AND a.banner_id ='".$bannersize."'
						ORDER BY a.last_display ASC 
						LIMIT 0,1";
					}
					
			
					
					$priority_array_dummy=array();
					
					if($number ==0 && $html_enabled ==1 && ($display_type ==1 || $display_type ==4) && ($old_container ==3 || $ad_container_type ==2))
					{
						$currenttime=time();
						$time_random=$currenttime%2;
			
						if($time_random ==1)
						{
						      $priority_array_dummy[]='html';
						      $priority_array_dummy[]='ppc';
						}
						else
						{
						      $priority_array_dummy[]='ppc';
						      $priority_array_dummy[]='html';
						}
					}
					else
					$priority_array_dummy[]='ppc';
					
					
					$default_html_get=0;
					
					
					if($number ==0 && $old_container ==3)
					{
						$currenttime=time();
						$time_random=$currenttime%2;
			
						if($time_random ==1)
						$ad_container_type=2;
						else
						$ad_container_type=1;
					}		
					
					
			
					foreach($priority_array_dummy as $pkey=>$pvalue)
					{
						if($pvalue =='html')
						{
							$default_ad_result_query=$db->execute_query($default_ad_result_html);
							$default_ad_count=$default_ad_result_query->get_num_records();
							
							if($default_ad_count >0)
							{
								$ad_container_type=2;
								$default_html_get=1;
								$display_type=1;
								$adget_flag=1;
								break;
							}
						}
						else
						{
							if($ad_container_type ==1)
							{
								$default_ad_result_query=$db->execute_query($default_ad_result_text);
								$default_ad_count=$default_ad_result_query->get_num_records();
								
								if($default_ad_count >0)
								{
									if($number == 0)
									$display_type=0;
									
									$adget_flag=1;
									break;
								}
							}
							
						
							
							if($ad_container_type ==2)
							{
								$default_ad_result_query=$db->execute_query($default_ad_result_image);
								$default_ad_count=$default_ad_result_query->get_num_records();
								
								if($default_ad_count >0)
								{
									if($number == 0)
									$display_type=0;
									
									$adget_flag=1;
									break;
								}
							}				
							
							if($ad_container_type ==1 && $old_container ==3 && $adget_flag ==0)
							{
								$default_ad_result_query=$db->execute_query($default_ad_result_image);
								$default_ad_count=$default_ad_result_query->get_num_records();
								
								if($default_ad_count >0)
								{
									$ad_container_type=2;
									
									if($number == 0)
									$display_type=0;
									
									$adget_flag=1;
									break;
								}					
							}
							
							if($ad_container_type ==2 && $old_container ==3 && $adget_flag ==0)
							{
								$default_ad_result_query=$db->execute_query($default_ad_result_text);
								$default_ad_count=$default_ad_result_query->get_num_records();
								
								if($default_ad_count >0)
								{
									$ad_container_type=1;
									
									if($number == 0)
									$display_type=0;
									
									$adget_flag=1;
									break;
								}					
							}				
						}
					}		
			
					
					if($adget_flag ==1 && $default_html_get ==1)
					$this->set_result("pubad",$default_ad_result_query,array('description'));
					else if($adget_flag ==1)
					$this->set_result("pubad",$default_ad_result_query,array('click_url'));
				}	
			}
			else if($ad_container_type==4 && $textimage_enabled ==1)
			{
				
				if($number < $total_text_ads)
				{
					$default_ad_limit=$total_text_ads-$number;
					
					$default_ad_result="SELECT a.id as aid, a.click_url,a.banner, a.title, a.description, a.display_url,a.type
					FROM ".TABLE_PREFIX."ads a
					WHERE a.uid = 0
					".$ad_status_check_string."
					".$ad_pause_status_check_string."
					AND a.display_type =0
					AND a.type =11
					AND a.banner_id ='".$bannersize."'		
					ORDER BY a.last_display ASC 
					LIMIT 0,".$default_ad_limit;
					
					$default_ad_result_query=$db->execute_query($default_ad_result);
					$default_ad_count=$default_ad_result_query->get_num_records();
					
					
					if($default_ad_count >0)
					$adget_flag=1;
					
					if($adget_flag ==1)
					$this->set_result("pubad",$default_ad_result_query,array('click_url'));
				}
			}
			else if($ad_container_type ==5 && $interstitial_enabled ==1)
			{
				if($number != 1)
				{
					$default_ad_result="SELECT a.id as aid,a.click_url,a.banner,a.display_type as dsp,a.type 
					FROM ".TABLE_PREFIX."ads a
					WHERE a.uid = 0
					".$ad_status_check_string."
					".$ad_pause_status_check_string."
					AND a.display_type =0
					AND a.type =5
					AND a.banner_id ='".$bannersize."'
					ORDER BY a.last_display ASC 
					LIMIT 0,1";
					
					
					
                    if($display_type ==0 || $display_type ==1 || $display_type ==6)
					{
						$default_ad_result_query=$db->execute_query($default_ad_result);
						$default_ad_count=$default_ad_result_query->get_num_records();
						
						if($default_ad_count >0)
						$adget_flag=1;
					}
					
					if($adget_flag ==1)
					$this->set_result("pubad",$default_ad_result_query,array('click_url'));
				}	
			}
			else if($ad_container_type ==13 && $video_enabled ==1 && $display_type ==13)
			{
				if($number != 1)
				{
					$default_ad_result="SELECT a.id as aid,a.banner,a.display_type as dsp,a.type,a.mime_type,a.duration_seconds,a.display_url,a.click_url 
					FROM ".TABLE_PREFIX."ads a
					WHERE a.uid = 0
					".$ad_status_check_string."
					".$ad_pause_status_check_string."
					AND a.display_type =13
					AND a.type =13
					AND (a.mime_type ='video/mp4' OR a.mime_type ='video/webm' OR a.mime_type ='video/ogg')
					".$aspect_ratio_string."
					ORDER BY a.last_display ASC 
					LIMIT 0,1";
					
					
					$default_ad_result_query=$db->execute_query($default_ad_result);
					$default_ad_count=$default_ad_result_query->get_num_records();
						
					if($default_ad_count >0)
					$adget_flag=1;
					
					if($adget_flag ==1)
					$this->set_result("pubad",$default_ad_result_query,array('click_url'));
				}	
			}			
			else if($ad_container_type ==14 && $skin_enabled ==1)
			{
				if($number != 1)
				{
					$default_ad_result="SELECT a.id as aid,a.click_url ,a.banner,a.display_type as dsp,a.type 
					FROM ".TABLE_PREFIX."ads a
					WHERE a.uid = 0
					".$ad_status_check_string."
					".$ad_pause_status_check_string."
					AND a.display_type =0
					AND a.type =14
					AND a.banner_id ='".$bannersize."'
					ORDER BY a.last_display ASC 
					LIMIT 0,1";
					
					
                    if($display_type ==0 || $display_type ==1 || $display_type ==6)
					{
						$default_ad_result_query=$db->execute_query($default_ad_result);
						$default_ad_count=$default_ad_result_query->get_num_records();
						
						if($default_ad_count >0)
						$adget_flag=1;
					}
					
					if($adget_flag ==1)
					$this->set_result("pubad",$default_ad_result_query,array('click_url'));
				}	
			}
			
			if($number ==0 && $adget_flag ==0)
			$display_type=0;
		}
		
		
		
		
		
		
			$this->set_variable("adunittype",$ad_container_type);
			$this->set_variable("originalcnts",$number);
			$this->set_variable("puboriginalcnts",$default_ad_count);
					
			$number=$number+$default_ad_count;
			$this->set_variable("number",$number);
			
			$this->set_variable('number_balance',$number_balance);
			$this->set_variable('sid',$sid);
			$this->set_variable('category_enabled',$category_targeting_enabled);
			$this->set_variable('sponsored_enabled',$sponsored_enabled);
			$this->set_variable('interstitial_enabled',$interstitial_enabled);
			$this->set_variable('skin_enabled',$skin_enabled);
			$this->set_variable('textimage_enabled',$textimage_enabled);
			$this->set_variable('ecommerce_enabled',$ecommerce_enabled);
			$this->set_variable('cpa_enabled',$cpa_enabled);
			$this->set_variable('cpc_enabled',$cpc_enabled);
			$this->set_variable('sticky_enabled',$sticky_enabled);
			
			$this->set_variable('referral_enabled',$referral_enabled);
			$this->set_variable("adv_ref_enabled",$adv_ref_enabled);
			$this->set_variable("pub_ref_enabled",$pub_ref_enabled);
			$this->set_variable('ipagent',$ipagent);
			$this->set_variable('video_enabled',$video_enabled);
			
			
			$this->set_variable('expandable_width',$expandable_width);
			$this->set_variable('expandable_height',$expandable_height);			
			$this->set_variable('expandable_type',$expandable_type);
			$this->set_variable('expandable_style',$expandable_style);			
			$this->set_variable('expandable_enabled',$expandable_enabled);

			
			
			
			$this->display_type_value=$display_type;

		}
		
		$this->set_variable('aduid',$adunitid);
		$this->set_variable('display_type',$display_type);
		$this->set_variable('pop_enabled',$pop_enabled);
		$this->set_variable('popdirect',$popdirect);
		
		
	}
	
	function before_render()
	{
		ob_clean();

		if($this->get_action() =='video')
		ob_start(array(&$this,"create_xml_cache"));
		else
		ob_start(array(&$this,"create_cache"));		
	}
	
	

	
		
			
	

	function load_action()
	{
		$param1=$this->mybase64_decode($this->read_page_param(1));
		$param2=$this->mybase64_decode($this->read_page_param(2));
		
		?>
		<script data-cfasync="false" type="text/javascript">
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

		<?php if($param2 !=""){?>
		var script=document.createElement('script');
			script.setAttribute("data-cfasync","false");
			script.type = "text/javascript";
		    script.async=1;
			script.src="<?php echo $param2;?>";
			document.getElementsByTagName('head')[0].appendChild(script);
		<?php }?>	

		setTimeout(function(){ window.location.href="<?php echo $param1;?>"; }, 1000);

		</script>
		<?php 
		exit;
	}
	
	
	function video_action()
	{
		
		$client_ip=UtilityHelper::get_user_ip();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$adunitid=intval($this->read_page_param(1));

		$debugmode_status = Configuration::get_instance()->read('debugmode_enable');

		$debugmode_values = array();

		$debug_adunitid = "";

		if($debugmode_status == 1)
		{
			$debugmode_values = json_decode(Configuration::get_instance()->read('debugmode_values'),true);
			$debug_adunitid = $debugmode_values['adcode-id'];
		}

		if($adunitid != $debug_adunitid)
		{
			$debugmode_status = 0;
		}
		
		$ipagent=$client_ip.$user_agent;	
		
        $this->cache_ip=$client_ip;
        $this->cache_user_agent=$user_agent;
        $this->cache_date=date('d',time()).date('H',time());		
		
		
		$this->set_variable("client_ip",$client_ip);
		$encip=md5($client_ip);
		
		$this->cache_encip=$encip;
		
		if(Configuration::get_instance()->read('proxy_detection_for_ad_display') ==1)
		{
			if(UtilityHelper::proxyDetection())	
			die;	
		}
		
		
		
		
			
		
		
		
		$device_targeting_addon_code = "XYZADMDEV";
		$city_targeting_addon_code = "XYZADMCTY";
		$time_targeting_addon_code = "XYZADMTME";
		$language_targeting_addon_code = "XYZADMLNG";
		$isp_targeting_addon_code = "XYZADMISP";
		$category_targeting_addon_code = "XYZADMCAT";
		
		
		

		$user_status_check_string = "";

		if(($debugmode_status == 1 && $debugmode_values['user-status-check'] == 1) || $debugmode_status != 1)
		{
			$user_status_check_string = " AND a.user_status =1 ";
		}

		$ad_status_check_string = "";

		if(($debugmode_status == 1 && $debugmode_values['ad-status-check'] == 1) || $debugmode_status != 1)
		{
			$ad_status_check_string = " AND a.status =1 ";
		}

		$ad_pause_status_check_string = "";

		if(($debugmode_status == 1 && $debugmode_values['pause-status-check'] == 1) || $debugmode_status != 1)
		{
			$ad_pause_status_check_string = " AND a.pause_status =0 ";
		}

		
		if($debugmode_status == 1 && $debugmode_values[$city_targeting_addon_code] == 1)
		{
			$city_enabled = 1;
		}
		elseif($debugmode_status == 1 && $debugmode_values[$city_targeting_addon_code] != 1)
		{
			$city_enabled = 0;
		}
		elseif($debugmode_status != 1)
		{	
			$city_enabled=$this->get_addon_status('city-targeting_enabled');
		}

		
		if($debugmode_status == 1 && $debugmode_values[$device_targeting_addon_code] == 1)
		{
			$device_targeting_enabled = 1;
		}
		elseif($debugmode_status == 1 && $debugmode_values[$device_targeting_addon_code] != 1)
		{
			$device_targeting_enabled = 0;
		}
		elseif($debugmode_status != 1)
		{	
			$device_targeting_enabled=$this->get_addon_status('device-targeting_enabled');
			
		}

		
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		
		if($debugmode_status == 1 && $debugmode_values[$time_targeting_addon_code] == 1)
		{
			$time_targeting_enabled = 1;
		}
		elseif($debugmode_status == 1 && $debugmode_values[$time_targeting_addon_code] != 1)
		{
			$time_targeting_enabled = 0;
		}
		elseif($debugmode_status != 1)
		{
			$time_targeting_enabled=$this->get_addon_status('time-targeting_enabled');
		}

		$video_enabled=$this->get_addon_status('video-ads_enabled');
		
		if($debugmode_status == 1 && $debugmode_values[$language_targeting_addon_code] == 1)
		{
			$language_targeting_enabled = 1;
		}
		elseif($debugmode_status == 1 && $debugmode_values[$language_targeting_addon_code] != 1)
		{
			$language_targeting_enabled = 0;
		}
		elseif($debugmode_status != 1)
		{
			$language_targeting_enabled=$this->get_addon_status('language-targeting_enabled');
		}

		$referral_enabled=$this->get_addon_status('referral_enabled');
		
		if($debugmode_status == 1 && $debugmode_values[$isp_targeting_addon_code] == 1)
		{
			$isp_connection_enabled = 1;
		}
		elseif($debugmode_status == 1 && $debugmode_values[$isp_targeting_addon_code] != 1)
		{
			$isp_connection_enabled = 0;
		}
		elseif($debugmode_status != 1)
		{
			$isp_connection_enabled=$this->get_addon_status('isp-connection-targeting_enabled');
		}

		if($isp_connection_enabled==1)
		{
			if($debugmode_status == 1 && $debugmode_values['isp-targeting'] == 1 )
			{
				$isp_enabled = 1;
			}
			elseif($debugmode_status == 1 && $debugmode_values['isp-targeting'] != 1)
			{
				$isp_enabled = 0;
			}
			elseif($debugmode_status != 1)
			{
				$isp_enabled=$this->get_addon_status('isp-targeting_enabled');
			}

			if($debugmode_status == 1 && $debugmode_values['connection-targeting'] == 1 )
			{
				$connection_enabled = 1;
			}
			elseif($debugmode_status == 1 && $debugmode_values['connection-targeting'] != 1)
			{
				$connection_enabled = 0;
			}
			elseif($debugmode_status != 1)
			{
				$connection_enabled=$this->get_addon_status('connectiontype-targeting_enabled');
			}
			
		}
		else
		{
			$isp_enabled=0;
			$connection_enabled=0;
		}

		
		if($debugmode_status == 1 && $debugmode_values[$category_targeting_addon_code] == 1)
		$category_targeting_enabled = 1;
		else if($debugmode_status == 1 && $debugmode_values[$category_targeting_addon_code] != 1)
		$category_targeting_enabled = 0;
		else if($debugmode_status != 1)
		{
			$category_targeting_enabled=$this->get_addon_status('category-targeting_enabled');
		}		
		
		$specialcache='';
		if($city_enabled ==1)
		{
			$record =UtilityHelper::get_geo_details_from_ip($client_ip);
			$country=$record->country_code;
			$state=$record->region;
			$cityname=$record->city;
			$latitude=$record->latitude;
			$longitude=$record->longitude;
			
			$specialcache=$state.$cityname;
		}
		else
		{
			$country =UtilityHelper::get_country_from_ip($client_ip);
		}
		
		
		$this->geo_country=$country;
		
		$number_balance=0;
	
		
			
		$sid=0;
		$catid=0;
		$sitename='';
		
		$isp='';
		$connection='';
		$ispid=0;
		$conn_id=0;
			
		$cacheDevice='';
		$osid=0;
		$osname='';
		$browserid=0;
		$browsername='';
		
		
		
		if($isp_enabled ==1 || $connection_enabled ==1)
		{ 
			try
			{
			    $db1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);
				
				$dbip = new DBIP_MySQLI($db1);
				
				$inf = $dbip->Lookup($client_ip,TABLE_PREFIX.'dbip_lookup');
				$isp= $inf->isp_name ;
				$connection=$inf->connection_type ;
				$cacheDevice=$cacheDevice.$isp.$connection;
				
				
			} 
			catch (DBIP_Exception $e) 
			{ 
				
			}
		}		

			
			
		if($device_targeting_enabled ==1)
		{
			DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
			
			$dd = new DeviceDetector($user_agent);
			
			$dd->parse();
			$deviceType = ($dd->isMobile() ? ($dd->isTablet() ? 'tablet' : 'phone') : 'computer');
			
			if($deviceType =='computer')
			$cacheDevice='c';
			else if($deviceType =='tablet' || $deviceType =='phone')
			$cacheDevice='m';
			
		
			$os_targeting_enabled=Configuration::get_instance()->read('os-targeting_enabled');
			$browser_targeting_enabled=Configuration::get_instance()->read('browser-targeting_enabled');
			
			
			if($os_targeting_enabled ==1)
			{
				if($dd->isBot()) 
				$botInfo = $dd->getBot();
				else 
				{
					$osInfo = $dd->getOs();
					$osname=$osInfo['name'];
					$cacheDevice=$cacheDevice.$osname;
				}
			}
			
			if($browser_targeting_enabled ==1)
			{
				
				if($dd->isBot()) 
				$botInfo = $dd->getBot();
				else 
				{
					$clientInfo = $dd->getClient();
					
				 	$browsername=$clientInfo['name'];
				 	$cacheDevice=$cacheDevice.$browsername;
				}
			}
		}		
		

	
		$language=array();
		$language1='';
		if($language_targeting_enabled==1)
		{
			if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			{
				$http_accept=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
				$x = explode(",",$http_accept);
				foreach ($x as $val) 
				{
					$language[] ="'". substr($val, 0, 2)."'";
				}
				$language_arr=array_unique($language);
				$language1= implode('', $language_arr);
			}
		}		



		$cache_three=$adunitid.$country.$specialcache.$cacheDevice.'1'; // RON Ads
		$cache_three=$adunitid."-".md5($cache_three).".php";		
		

		$this->cfile_name=$cache_three;		
		
		$cached_flag=0;

		if(($debugmode_status == 1 && $debugmode_values['cache'] == 1) || $debugmode_status != 1)
		{
			include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_three);
					
			if($cached_flag ==1)
			die;
			else if($cached_flag ==2)
			unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cache_three);				
		}
		
		
		

		$db= DAL::get_instance();
		$mem_obj=$this->memcache_connect();
		if($mem_obj != false && ADUNIT_MEMCACHE_ENABLED == 1)
		{    
			$result=$mem_obj->get('adunit_res_'.$adunitid); 
			$number=count($result);
		}
		
	


			$res=$db->execute_query("select au.*,au.id as auid,au.name as auname from ".TABLE_PREFIX."adunit au where au.id=?",array($adunitid));
			$number=intval($res->get_num_records());
		
			$result=$res->fetch_assoc();
		
			if($mem_obj != false && ADUNIT_MEMCACHE_ENABLED == 1)
			{
				$mem_adunit_array=array(
						"display_type"=>$result["display_type"],
						"pubid"=>$result["pubid"],
						"linear"=>$result["linear"],
						"non_linear_text"=>$result["non_linear_text"],
						"player_size"=>$result["player_size"],
						"video_type"=>$result["video_type"],
						"bannersize"=>$result["bannersize"]
				);
				
				$mem_obj->set("adunit_res_".$adunitid,$mem_adunit_array,MEMCACHE_EXPIRY);
		
			}
		
		
		
		$display_type=$result['display_type'];
		$pid=intval($result['pubid']);
		
		
		$linear=intval($result['linear']);
		$non_linear_text=intval($result['non_linear_text']);
		$non_linear_banner=intval($result['non_linear_banner']);
		
		$nonlinear_support=intval(Configuration::get_instance()->read('vast_player_nonlinear_support'));
		
		if($nonlinear_support ==0)
		{
			$non_linear_text=0;
			$non_linear_banner=0;
		}		
		
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		

		if($linear ==0)
		exit;


		if($non_linear_text ==1 && $text_ads_enabled ==0)
		$non_linear_text=0;
		
		
		if($number ==0)
		exit(0);		

		
		
		
		if($display_type != 13)
		exit(0);
		
		if($video_enabled ==0 && $display_type ==13)
		exit;	
		
		if($video_enabled ==1 && $display_type ==13 && $result['video_type'] !=1)
		exit;			
		
		
		$video_banner_width=0;
		$video_banner_height=0;
		
		
		if($non_linear_banner ==1)	
		{
			$imagesupport=intval($this->get_banner_dimension_support($result['player_size'],13));
				
			if($imagesupport ==0)
			$non_linear_banner=0;
			
			if($non_linear_banner ==1)	
			{
				$player_size=$this->get_banner_dimension($result['player_size']);
				$player_size_array=explode('-',$player_size);			
				
				if(isset($player_size_array[0]))
				{
					$video_banner_width=$player_size_array[0];
					$video_banner_height=$player_size_array[1];	
				}
			}
		}
			
		

		
		$this->cpc_enabled_value=$cpc_enabled;		
		$this->cpm_enabled_value=$cpm_enabled;
		$this->cpa_enabled_value=$cpa_enabled;
		$this->device_enabled_value=$device_targeting_enabled;
		$this->referral_enabled_value=$referral_enabled;		
		$this->video_enabled_value=$video_enabled;		
		
		$this->language_targeting_enabled_value=$language_targeting_enabled;
		$this->isp_targeting_enabled_value=$isp_enabled;
		$this->connectiontype_targeting_enabled_value=$connection_enabled;
		
		$adv_ref_enabled=0;
		$pub_ref_enabled=0;

		if($referral_enabled ==1)
		{
			$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
			$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
		}
		
		
		$this->geo_tracking_enabled=intval(Configuration::get_instance()->read('countrywise_data_tracking'));

		
		$ppctrack=0;
		$cpmtrack=0;
		$cpatrack=0;
		$videotrack=0;
		
		
		if($non_linear_text ==1 || $non_linear_banner ==1)
		{
			if($cpc_enabled ==1)
			$ppctrack=intval(Configuration::get_instance()->read('ppc_impression_tracking_interval'));
			
			if($cpa_enabled ==1)
			$cpatrack=intval(Configuration::get_instance()->read('cpa_impression_tracking_interval'));
			
			if($cpm_enabled ==1)
			$cpmtrack=intval(Configuration::get_instance()->read('cpm_impression_tracking_interval'));
		}

		$videotrack=intval(Configuration::get_instance()->read('vast_player_impression_tracking_interval'));
		
		
		$this->ppc_tracking_interval=$ppctrack;
		$this->cpa_tracking_interval=$cpatrack;
		$this->cpm_tracking_interval=$cpmtrack;
		$this->video_tracking_interval=$videotrack;
		
		
		if($videotrack ==0)
		$track_string="start";
		else if($videotrack ==25)
		$track_string="firstQuartile";
		else if($videotrack ==50)
		$track_string="midpoint";		
		else if($videotrack ==75)
		$track_string="thirdQuartile";		
		else 
		$track_string="complete";			
		
		
		$cityid=0;

		if($city_enabled ==1 && $country !="" && $state !="" && $latitude !="" && $longitude !="")
		$cityid=UtilityHelper::get_cityid_from_latlng_display($country,$state,$latitude,$longitude);
		
		
		$rotation_variable=Configuration::get_instance()->read('ad_rotation');
		
		
		
		
		
		/*************** For Site Restriction **************/
		if($category_targeting_enabled ==1)
		{
			$sid=$result['sid'];
			
			if($sid >0)
			{
				if($mem_obj != false)
				$sitedata=$mem_obj->get('site_'.$sid);
				
				if($mem_obj !=false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
				{  
					$catid=$sitedata['catid'];
					$sitename=strtolower($sitedata['url']);
				}
				else 
				{
					$siterow=$db->execute_query("SELECT catid,url FROM ".TABLE_PREFIX."sites WHERE id=? AND status=1",array($sid));
					$sitedata=$siterow->fetch_assoc();
				
					$catid=$sitedata['catid'];
					$sitename=strtolower($sitedata['url']);
					
					if($mem_obj != false)
					$mem_obj->set('site_'.$sid,$sitedata,MEMCACHE_EXPIRY);
				}
			}
		}
		/*************** For Site Restriction **************/		
		/*************** For device Restriction **************/
		$devicestring='';
		$devicestring1='';
		$os_string='';
		$os_string1='';
		$browser_string='';
		$browser_string1='';
		if($device_targeting_enabled ==1)
		{
			$devicestring.=' AND (a.device IS NULL OR  a.device=2 ';
		
			if($deviceType =='computer')
			{
				if($devicestring !='')
				$devicestring.=' OR ';
		
				$devicestring.=' a.device=0  ';
			}
		
			if($deviceType =='tablet' || $deviceType =='phone')
			{
				if($devicestring !='')
				$devicestring.=' OR ';
				$devicestring1.='  a.device=1 ';
				
			}
			$devicestring.=$devicestring1.' ) ';
		
		
			/********************************** OS Targeting ********************************/
			if($os_targeting_enabled==1)
			{
				if($mem_obj != false && $mem_obj->get($osname)!='')
				$osid=$mem_obj->get($osname);                  /****getting os id from memcache ********/
				else
				{
					$osid=$db->read_single_column("select id from ".TABLE_PREFIX."os where name=?",array($osname));
					
					if($mem_obj != false)
					$mem_obj->set($osname,$osid,MEMCACHE_EXPIRY);
				}
				if($osid > 0)
				{
					$os_string=' AND ( os.osid = '.$osid.' OR os.osid IS NULL OR os.osid=0)';
					
					$os_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_os_mapping os ON a.id = os.aid ';
				}
				$this->set_variable('osid', intval($osid));
			}
			/********************************** OS Targeting ********************************/
			
			
			/********************************** Browser Targeting ********************************/
			if($browser_targeting_enabled==1)
			{
				if($mem_obj != false && $mem_obj->get($browsername)!='')
				$browserid=$mem_obj->get($browsername);          /****getting browser id from memcache ********/
				else
				{
					$browserid=$db->read_single_column("select id from ".TABLE_PREFIX."browser where name=?",array($browsername));
					
					if($mem_obj != false)
					$mem_obj->set($browsername,$browserid,MEMCACHE_EXPIRY);     //// store browser id in memcache
				}
				
				if($browserid > 0)
				{
					$browser_string=' AND ( br.browserid = '.$browserid.' OR br.browserid IS NULL OR br.browserid=0)';
				
					$browser_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_browser_mapping br ON a.id = br.aid ';
				} 
				$this->set_variable('browserid', intval($browserid));
			}
			/********************************** Browser Targeting ********************************/
		}
		/*************** For device Restriction **************/		
		
	
		
		
		$date_condition='';
		$date_flag=0;
		if($time_targeting_enabled ==1)
		{
			$datetime=time();
			if(Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)
			{
			
			$date_condition.=' AND ((a.date_filter IS NULL AND a.time_filter IS NULL AND a.day_filter IS NULL) OR (';
				
				
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
			
			$date_condition.='))';
			
			}
		}
			
		
		$same_account_str="";
		
		if(Configuration::get_instance()->read('allow_publishers_to_display_their_own_ads') ==0)
		{
			if(($debugmode_status == 1 && $debugmode_values['same-user'] == 1) || $debugmode_status != 1)
			{
				if($pid >0)
				$same_account_str=" AND a.uid <> '".$pid."' ";
			}
		}
		
		

		
		$prid=0;
		$cpm_profit_percentage = 0;
		$cpv_profit_percentage = 0;
		$site_restriction_str="";
		if($pid > 0)
		{
			if($mem_obj != false)
				$mem_pub_details = $mem_obj->get('pub_details_' . $pid);
			if($mem_obj != false && $mem_obj->getResultCode() == RES_SUCCESS)
			{
				if($mem_pub_details ['pub_status'] != 1)
					exit(0);
				
				if($cpm_enabled == 1)
				{
					$cpm_profit_percentage = $mem_pub_details['cpm_profit_percentage'];
							
					if($cpm_profit_percentage == 0)
						$cpm_profit_percentage = Configuration::get_instance()->read('cpm_profit_percentage');
				}
				
				$cpv_profit_percentage = $mem_pub_details['cpv_profit_percentage'];
				
				if($cpv_profit_percentage == 0)
					$cpv_profit_percentage = Configuration::get_instance()->read('cpv_profit_percentage');
				
				if($referral_enabled == 1 && $pub_ref_enabled == 1)
					$prid = intval($mem_pub_details['rid']);
				
				$site_json=$mem_pub_details['restricted_sites'];
			}
			else
			{
					$pquery_res = $db->execute_query("SELECT * FROM " . TABLE_PREFIX . "users WHERE id=?",array ($pid));
					$query_result = $pquery_res->fetch_assoc();
					if($mem_obj != false)
						$mem_obj->set('pub_details_' . $pid,$query_result,MEMCACHE_EXPIRY);
				
					if($query_result ['pub_status'] != 1)
					exit;
					
					if($cpm_enabled == 1)
					{
						$cpm_profit_percentage = $query_result ['cpm_profit_percentage'];
					
						if($cpm_profit_percentage == 0)
						$cpm_profit_percentage = Configuration::get_instance()->read('cpm_profit_percentage');
					}
				
					$cpv_profit_percentage = $query_result ['cpv_profit_percentage'];
				
					if($cpv_profit_percentage == 0)
					$cpv_profit_percentage = Configuration::get_instance()->read('cpv_profit_percentage');
				
					if($referral_enabled == 1 && $pub_ref_enabled == 1)
					$prid = intval($query_result ['rid']);
					$site_json=$query_result['restricted_sites'];
					$vast_adcode_enabled=intval($query_result['vast_adcode_enabled']);			
			
					if($vast_adcode_enabled ==0)
					exit;
			}
			
			
			
			$res_site_array=array();
			
			if($site_json != '')
			$res_site_array = json_decode($site_json);
			foreach ($res_site_array as $value)
			{
				if($value != "")
				$site_restriction_str .= " AND a.click_url not like '%" . $value . "%' ";
			}
		}
		

		//$res->set_result_index(0);
		//$this->set_result("res",$res);
		
		
		$refstring="";
		if($referral_enabled ==1 && $adv_ref_enabled ==1)
		$refstring=",a.refferal_id as rid,a.refferal_status ";		
		
	

		
		
		
		
		
		/********************** PPC Variables *************************/
		if($rotation_variable ==1)
		{
			$ad_changing		= "ORDER BY a.default_rate DESC";
			
			if(time() % 5 == 0)
			$ad_changing.=",a.last_display ASC";
			
			
			$cpa_ad_changing	= "ORDER BY a.default_rate DESC,a.tracking_last_checked DESC";
			
			if(time() % 5 == 0)
			$cpa_ad_changing.=",a.last_display ASC";				
		}
		else
		{
			$ad_changing		= "ORDER BY a.last_display ASC";	
			
			$cpa_ad_changing	= "ORDER BY a.last_display ASC";			
		}		
		
		
		
		$ppc_budget_str="";

		if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
		$ppc_budget_str=' AND a.pricing_status=1 AND (a.daily_budget =0 OR a.daily_budget > a.daily_budget_used) AND  (a.daily_budget =0 OR (a.daily_budget - a.daily_budget_used) >= a.default_rate) AND (a.total_ad_budget >= (a.total_budget_used + a.default_rate)) ';


		

		/********************** PPC Variables *************************/
		

		
		
		
		/********************** CPA Variables *************************/
		
		
		
		$cpa_budget_str = "";

		if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
		$cpa_budget_str=' AND a.pricing_status=1 AND a.total_ad_budget > a.total_budget_used AND (((a.total_ad_budget - a.total_budget_used) >= a.default_rate)) ';

		

		/********************** CPA Variables *************************/
		

		
		$cpm_budget_str='';
		$cpm_get_string='';
		
		if($cpm_enabled ==1)
		{
			if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
			$cpm_budget_str=' AND a.pricing_status=1 AND (((a.daily_budget >0 AND (a.daily_budget - a.daily_budget_used) >= (a.default_rate/1000)) OR a.daily_budget =0) AND (a.total_ad_budget >= (a.total_budget_used + (a.default_rate/1000)))) ';
			
		
			$cpm_get_string=' ,a.default_rate ';
		}
		
		
		
		
		$cpv_budget_str='';
		

		
		if(($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1) || $debugmode_status != 1)
		$cpv_budget_str=' AND a.pricing_status=1 AND (((a.daily_budget >0 AND (a.daily_budget - a.daily_budget_used) >= a.default_rate) OR a.daily_budget =0) AND (a.total_ad_budget >= (a.total_budget_used + a.default_rate))) ';
		
	
		$cpv_get_string=' ,a.id as aid,a.default_rate ';
			
	
	
		
		/********************************** Geographic Query *******************************/
		
		$country_display_condition = "";

		
		if($city_enabled ==1 && $cityid >0)
		{
		
			$country_display_condition =" AND (";
			
			$country_display_condition.=" ((g.country_code='".$country."') AND (g.state_code='".$state."') AND (g.city='".$cityid."')) or ";
			
			$country_display_condition.=" ((g.country_code='".$country."') AND (g.state_code='".$state."')) or ";
			
			$country_display_condition.=" ((g.country_code='".$country."') AND (g.state_code='00') AND (g.city='".$cityid."')) or ";
			
			$country_display_condition.=" ((g.country_code='".$country."')) or ";
			
			$country_display_condition.=" ((g.country_code='0') AND (g.state_code='0') AND (g.city='0')) ";
			
			$country_display_condition.=" )";
		
		}
		else if(($debugmode_status == 1 && $debugmode_values['country'] == 1) || $debugmode_status != 1)
		{
			$country_display_condition=" AND (";
			
			if($country!="")
			$country_display_condition.=" (g.country_code='".$country."') or ";
			
			$country_display_condition.=" (g.country_code='0')) ";
			
		}
	
		
		/********************************** Geographic Query *******************************/

		
		/********************************** ISP & Connection Type Targeting ********************************/
		$isp_string='';
		$isp_string1='';
		
		if($isp_enabled ==1 || $connection_enabled ==1)
		{
			if($isp !='' && $isp_enabled ==1)
		   	$ispid=$db->read_single_column("select id from ".TABLE_PREFIX."isp where name=? and country=?",array($isp,$country));
			
			if($connection !='' && $connection_enabled ==1)
			$conn_id=$db->read_single_column("select id from ".TABLE_PREFIX."connection where name=? ",array($connection));
		
			$isp_string.=' AND (';
			if($isp_enabled ==1)
			{
				$isp_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_isp_mapping im ON a.id = im.aid ';
				
				if($ispid > 0)
				$isp_string.='( im.ispid ='.$ispid.' OR im.ispid IS NULL OR im.ispid=0)';
				else 
				$isp_string.='( im.ispid IS NULL OR  im.ispid=0)';
			}
			
			if($connection_enabled ==1)
			{
				$isp_string1.=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_connection_mapping cn ON a.id = cn.aid ';
				
				if($isp_enabled ==1)
				$isp_string.=' OR ';
		 		
				if($conn_id >0)
				$isp_string.='  ( cn.conn_id='.$conn_id.' OR cn.conn_id IS NULL OR cn.conn_id=0)';
				else 	
				$isp_string.='  ( cn.conn_id IS NULL OR cn.conn_id=0)';
			}
			
			$isp_string.=' )';
		}
		/********************************** ISP & Connection Type Targeting  ********************************/
		
		/********************************** Language Targeting **********************************************/

		if($language_targeting_enabled==1)
		{
			$language_string='';
			$language_string1='';
			$language_select ='';

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
						$language_select=' ,COALESCE(brl.language_id,0) as brl_id, a.direction as dir ' ;
						
						$language_string=' AND ( brl.language_id in ( '.implode(',', $brlang_id_arr).') OR brl.language_id IS NULL OR brl.language_id=0)';
					
						$language_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_language_mapping brl ON a.id = brl.aid ';
					}
				}
			}
		}

		/**********************************Language Targeting *********************************/
		
		
		/********************************** Category Targeting ********************************/
		$category_string='';
		$category_string1='';
		if($catid > 0)
		{
			$category_string=UtilityHelper::get_category_last_childs_display($catid);
		
			if($category_string !='')
			$category_string=' AND ( cam.catid IN ('.$category_string.') OR cam.catid IS NULL )';
			else 
			$category_string=' AND cam.catid IS NULL ';
			
			$category_string1=' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_category_mapping cam ON a.id = cam.aid ';
		}
		/********************************** Category Targeting ********************************/		
		

		$cpv_ad_getting_query='';


		$cpm_text_ad_getting_query='';
		$cpm_image_ad_getting_query='';
		
		$ppc_text_ad_getting_query='';
		$ppc_image_ad_getting_query='';
		
		$cpa_text_ad_getting_query='';
		$cpa_image_ad_getting_query='';
		
		$key_select = " ,0 as keyid,0 as kid ";
		

		$cpv_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type,a.mime_type,video_width,video_height,bitrate,duration,a.duration_seconds,a.display_url".$refstring."  
		".$cpv_get_string."
		FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
		".$category_string1."
		".$os_string1."
		".$browser_string1."
		".$language_string1."
		".$isp_string1."
		WHERE a.id = g.aid
		".$country_display_condition."
		AND a.display_type=13
		AND a.type =13 
		AND a.mime_type !='video/webm' AND a.mime_type !='video/ogg'
		".$ad_status_check_string."
		".$ad_pause_status_check_string."
		".$category_string."
		".$devicestring."
		".$date_condition."
		".$os_string."
		".$browser_string."
		".$language_string."
		".$isp_string."		
		".$user_status_check_string."
		".$same_account_str."
		".$site_restriction_str."
		".$cpv_budget_str."
		".$ad_changing."
		LIMIT 0,1";
	


		
		if($cpm_enabled ==1)
		{
			if($non_linear_text ==1)
			{
				$cpm_text_ad_getting_query="SELECT 0 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
				".$cpm_get_string."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
				".$category_string1."
				".$os_string1."
				".$browser_string1."
				".$language_string1."
				".$isp_string1."	
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=1
				AND a.type =1
				AND a.video_player_support =1
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				".$category_string."
				".$devicestring."
				".$date_condition."
				".$os_string."
				".$browser_string."
				".$language_string."
				".$isp_string."		
				".$user_status_check_string."
				".$same_account_str."
				".$site_restriction_str."
				".$cpm_budget_str."
				".$ad_changing."
				LIMIT 0,1";
			}
			
			
			if($non_linear_banner ==1)
			{
				$cpm_image_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				".$cpm_get_string."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$category_string1."
				".$os_string1."
				".$browser_string1."
				".$language_string1."
				".$isp_string1."	
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=1
				AND a.type =2 
				AND a.video_player_support =1				
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				".$category_string."
				".$devicestring."
				".$date_condition."
				".$os_string."
				".$browser_string."
				".$language_string."
				".$isp_string."		
				".$user_status_check_string."
				AND a.banner_id ='".$result['player_size']."'
				".$same_account_str."
				".$site_restriction_str."
				".$cpm_budget_str."
				".$ad_changing."
				LIMIT 0,1";
			}
		}
		
		
		
		
		if($cpc_enabled ==1)
		{
			if($non_linear_text ==1)
			{
				$ppc_text_ad_getting_query="SELECT 0 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select.",a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
				".$category_string1."
				".$os_string1."
				".$browser_string1."
				".$language_string1."
				".$isp_string1."	
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=0
				AND a.type =1
				AND a.video_player_support =1					
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				".$category_string."
				".$devicestring."
				".$date_condition."
				".$os_string."
				".$browser_string."
				".$language_string."
				".$isp_string."		
				".$user_status_check_string."
				".$same_account_str."
				".$site_restriction_str."
				".$ppc_budget_str."
				".$ad_changing."
				LIMIT 0,1";
			}
			
			
			if($non_linear_banner ==1)
			{
				$ppc_image_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$category_string1."
				".$os_string1."
				".$browser_string1."
				".$language_string1."
				".$isp_string1."	
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=0
				AND a.type =2 
				AND a.video_player_support =1					
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				".$category_string."
				".$devicestring."
				".$date_condition."
				".$os_string."
				".$browser_string."
				".$language_string."
				".$isp_string."		
				".$user_status_check_string."
				AND a.banner_id ='".$result['player_size']."'
				".$same_account_str."
				".$site_restriction_str."
				".$ppc_budget_str."
				".$ad_changing."
				LIMIT 0,1";
			}
		}
		
		
		
		if($cpa_enabled ==1)
		{
			if($non_linear_text ==1)
			{
				$cpa_text_ad_getting_query="SELECT 0 as retarget,a.id as aid, a.click_url, a.title, a.description ".$key_select.", a.display_url, a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g, ".TABLE_PREFIX."ads a 
				".$category_string1."
				".$os_string1."
				".$browser_string1."
				".$language_string1."
				".$isp_string1."	
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=6
				AND a.type =1
				AND a.video_player_support =1					
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				".$category_string."
				".$devicestring."
				".$date_condition."
				".$os_string."
				".$browser_string."
				".$language_string."
				".$isp_string."		
				".$user_status_check_string."
				".$same_account_str."
				".$site_restriction_str."
				".$cpa_budget_str."
				".$cpa_ad_changing."
				LIMIT 0,1";
			}
			
			if($non_linear_banner ==1)
			{			
				$cpa_image_ad_getting_query="SELECT 0 AS retargetid,0 as retarget,a.id as aid,a.click_url, a.banner ".$key_select.",a.uid as userid,a.display_type as dsp,a.type".$refstring."
				FROM ".TABLE_PREFIX."ad_geographic_mapping g,".TABLE_PREFIX."ads a 
				".$category_string1."
				".$os_string1."
				".$browser_string1."
				".$language_string1."
				".$isp_string1."	
				WHERE a.id = g.aid
				".$country_display_condition."
				AND a.display_type=6
				AND a.type =2 
				AND a.video_player_support =1					
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				".$category_string."
				".$devicestring."
				".$date_condition."
				".$os_string."
				".$browser_string."
				".$language_string."
				".$isp_string."		
				".$user_status_check_string."
				AND a.banner_id ='".$result['player_size']."'
				".$same_account_str."
				".$site_restriction_str."
				".$cpa_budget_str."
				".$cpa_ad_changing."
				LIMIT 0,1";
			}
		}
		


		
			$priority=Configuration::get_instance()->read('ad_display_priority');
			
			$queryarray=array();
			
			
			if($cpc_enabled ==1)
			{
				$queryarray['ppc'][0]=$ppc_text_ad_getting_query;
				$queryarray['ppc'][1]=$ppc_image_ad_getting_query;
			}
			
			if($cpm_enabled ==1)
			{
				$queryarray['cpm'][0]=$cpm_text_ad_getting_query;
				$queryarray['cpm'][1]=$cpm_image_ad_getting_query;
			}
			
			if($cpa_enabled ==1)
			{
				$queryarray['cpa'][0]=$cpa_text_ad_getting_query;
				$queryarray['cpa'][1]=$cpa_image_ad_getting_query;
			}
			

		

			$ad_display_random=Configuration::get_instance()->read('ad_display_random');
			
			
			$priority_array=explode('_',$priority);
			
			
			$pstring="";
			
			foreach($priority_array as $pkey1=>$pvalue1)
			{
				if($pvalue1 != "html")
				{
					if($pstring != "")
					$pstring.="_";
					
					$pstring.=$pvalue1;
				}
			}
			
			if($pstring != "")
			$priority=$pstring;
			
			
				
		   if($ad_display_random ==1)
		   {
				$countlist = 0;

				if(isset($queryarray['ppc']))
				$countlist = $countlist+1;

				if(isset($queryarray['cpm']))
				$countlist = $countlist+1;

				if(isset($queryarray['cpa']))
				$countlist = $countlist+1;

				
				
				if($countlist >1)
				{
					$currentsystime=time();
					$currentrandom=$currentsystime % $countlist;
					
					$priority_array=explode('_',$priority);
					
					if($countlist ==2)
					{
						if($currentrandom ==0)
						$priority=$priority_array[0].'_'.$priority_array[1];	
						else 
						$priority=$priority_array[1].'_'.$priority_array[0];
					}
					else if($countlist ==3)
					{
						if($currentrandom ==0)
						$priority=$priority_array[0].'_'.$priority_array[2].'_'.$priority_array[1];
						else if($currentrandom ==1)
						$priority=$priority_array[1].'_'.$priority_array[0].'_'.$priority_array[2];
						else if($currentrandom ==2)
						$priority=$priority_array[2].'_'.$priority_array[1].'_'.$priority_array[0];
					}
				}
			}
				
			$priority_array=explode('_',$priority);
				
				
			
			
			$adget_flag=0;
			$default_ad_get_flag=0;
			$number=0;			
			
			


			$resquery=$db->execute_query($cpv_ad_getting_query);
			
			$number=$resquery->get_num_records();
			if($number >0)
			{
				$adget_flag=1;		
				$display_type=13;
			}
			

			if($number ==0 && ($non_linear_text ==1 || $non_linear_banner ==1))
			{
				foreach($priority_array as $pkey=>$pvalue)
				{
					if(($cpc_enabled ==1 && $pvalue =='ppc') || ($cpm_enabled ==1 && $pvalue =='cpm') || ($cpa_enabled ==1 && $pvalue =='cpa'))
					{
						if($non_linear_text ==1 && $non_linear_banner ==1)
						{
							$currenttime=time();
							$time_random=$currenttime%2;
						}
						else if($non_linear_banner ==1)
						$time_random=1;
						else
						$time_random=0;
						
							
						if($time_random ==1)
						{
							$ad_container_type=2;
							$resquery=$db->execute_query($queryarray[$pvalue][1]);
							$number=$resquery->get_num_records();
						}
						else
						{
							$ad_container_type=1;
							$resquery=$db->execute_query($queryarray[$pvalue][0]);								
							$number=$resquery->get_num_records();
						}
									
						if($number ==0 && $ad_container_type ==2 && $non_linear_text ==1)
						{
							$ad_container_type=1;
							$resquery=$db->execute_query($queryarray[$pvalue][0]);								
							$number=$resquery->get_num_records();
						}
						else if($number ==0 && $ad_container_type ==1 && $non_linear_banner ==1)
						{
							$ad_container_type=2;
							$resquery=$db->execute_query($queryarray[$pvalue][1]);
							$number=$resquery->get_num_records();
						}
					
								
						if($number >0)
						{
							if($pvalue =='cpm')
							$display_type=1;
							else if($pvalue =='ppc')
							$display_type=0;
							else if($pvalue =='cpa')
							$display_type=6;								
							
							$adget_flag=1;
							break;
						}
					}
				}
			}

		
	
			if($adget_flag == 0)
			{
				$default_ad_result="SELECT a.id as aid,a.banner,a.click_url,a.display_type as dsp,a.type,a.mime_type,video_width,video_height,bitrate,duration,a.duration_seconds,a.display_url 
				FROM ".TABLE_PREFIX."ads a
				WHERE a.uid = 0
				".$ad_status_check_string."
				".$ad_pause_status_check_string."
				AND a.display_type =13
				AND a.type =13
				AND a.mime_type !='video/webm' AND a.mime_type !='video/ogg'
				ORDER BY a.last_display ASC 
				LIMIT 0,1";

				
				$default_ad_result_query=$db->execute_query($default_ad_result);
				$default_ad_count=$default_ad_result_query->get_num_records();
					
				if($default_ad_count >0)
				$default_ad_get_flag=1;
				
				
				if($default_ad_get_flag ==0)
				{
					if($non_linear_banner ==1)
					{
						$default_ad_result="SELECT a.id as aid,a.click_url,a.banner,a.display_type as dsp,a.type
						FROM ".TABLE_PREFIX."ads a
						WHERE a.uid = 0
						".$ad_status_check_string."
						".$ad_pause_status_check_string."
						AND a.display_type =0
						AND a.type =2
						AND a.banner_id ='".$result['bannersize']."'
						ORDER BY a.last_display ASC 
						LIMIT 0,1";
						
						
						$default_ad_result_query=$db->execute_query($default_ad_result);
						$default_ad_count=$default_ad_result_query->get_num_records();
						
						if($default_ad_count >0)
						$default_ad_get_flag=1;					
					}
					
					if($default_ad_get_flag ==0 && $non_linear_text ==1)
					{	
						$default_ad_result="SELECT a.id as aid,a.click_url,a.title, a.description, a.display_url,a.type
						FROM ".TABLE_PREFIX."ads a
						WHERE a.uid = 0
						".$ad_status_check_string."
						".$ad_pause_status_check_string."
						AND a.display_type =0
						AND a.type =1
						ORDER BY a.last_display ASC 
						LIMIT 0,1";
						
						$default_ad_result_query=$db->execute_query($default_ad_result);
						$default_ad_count=$default_ad_result_query->get_num_records();
								
						if($default_ad_count >0)
						$default_ad_get_flag=1;									
					}
				}
			}					
			
				



			$admarket_name=Configuration::get_instance()->read('admarket_name');
				
			
			$basepath=str_replace('https://','',TRACK_BASE);
			$basepath=str_replace('http://','',$basepath);
			
			
			
			
			$rid=0;
			$profit=0;
			$cpm_ad_strings="";
			
			
				
			if($adget_flag ==1 || $default_ad_get_flag ==1)
			{
				if($adget_flag ==1)
				$resdata=$resquery->fetch_assoc();
				else if($default_ad_get_flag ==1)
				$resdata=$default_ad_result_query->fetch_assoc();
				
				
				if($adget_flag ==1)
				{
	                $kid_data=$resdata['kid'];
	                $key_id=$resdata['keyid'];
	                
					$bs=md5($resdata['aid'].$kid_data.$adunitid.'0'.$resdata['retarget']);
					 	


					$clksurl='//'.$basepath.TRACK_DIR.'/index.php?page=click/validate/'.$resdata['aid'].'/'.$kid_data.'/'.$adunitid.'/0/{ENCIP}/'.$bs.'/0/'.$resdata['retarget'];
	                
	                
					if($display_type ==1 || $display_type ==13)
					{
					 	if($referral_enabled ==1 && $adv_ref_enabled ==1)
						$rid=intval($resdata['rid']);
					}                
                

				
				    $cpm_ad_strings=$resdata['userid'].'|'.$resdata['aid'].'|'.$kid_data.'|'.$pid.'|'.$adunitid.'|1|0|'.$display_type.'|'.$key_id;
					
				   				    
				 
				    $mapid=$resdata['aid'];
				    
					if($display_type ==1 || $display_type ==13)
					{
					 	if($display_type ==1)
					 	{
							$singleimprate=$resdata['default_rate']/1000;
					 		
					 		if($pid >0)
		 					$profit=$singleimprate*$cpm_profit_percentage/100;
					 	}
					 	else if($display_type ==13)
					 	{
							$singleimprate=$resdata['default_rate'];
					 		
					 		if($pid >0)
		 					$profit=$singleimprate*$cpv_profit_percentage/100;
					 	}			 	
						
						$cpm_ad_strings.='|'.$mapid.'|'.$profit.'|'.$singleimprate.'|'.$rid.'|'.$prid;
					}
				
	
	                $this->cache_cpm_string=$cpm_ad_strings;
		
				}   
 				
			
                
				
				
				$this->vast_aid=$resdata['aid'];
				$this->vast_ad_get=$adget_flag;
				
				
				$this->vast_ad_type=$resdata['type'];
				$this->vast_ad_tracking_time=$videotrack;
				

				
				if($resdata['type'] !=1)
				{
					$sample_xml='<VAST xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vast.xsd" version="4.0" >';
				    
					
					$sample_xml.='<Ad id="'.$resdata['aid'].'">';
					$sample_xml.='<InLine>';
					$sample_xml.='<AdSystem>'.$admarket_name.'</AdSystem>';
					
					if($resdata['type'] ==13)
	            	$sample_xml.='<AdTitle>'.$admarket_name.' Vast Linear Ad</AdTitle>';
					else
					$sample_xml.='<AdTitle>'.$admarket_name.' Vast NonLinear Banner Ad</AdTitle>';
            	
            	
                
					if($resdata['type'] ==13)
	                {
	                	$this->vast_ad_duration=$resdata['duration_seconds'];
	                	
		                $sample_xml.='<Creatives>';
		                $sample_xml.='<Creative AdID="'.$resdata['aid'].'">';
		                
		                $sample_xml.='<Linear>';
		                $sample_xml.='<Duration>'.$resdata['duration'].'</Duration>';
	            
	                	$sample_xml.='<TrackingEvents>';
	                
	                	//Impression tracking
		                $sample_xml.='<Tracking event="'.$track_string.'"><![CDATA[{IMPRESSIONTRACKING}]]></Tracking>';
		                
		                $sample_xml.='</TrackingEvents>';
	                
	                
		                $sample_xml.='<VideoClicks>';
		                $sample_xml.='<ClickThrough><![CDATA['.$resdata['click_url'].']]></ClickThrough>';
		                
		                if($adget_flag ==1)
		                $sample_xml.='<ClickTracking><![CDATA['.$clksurl.']]></ClickTracking>';
		                
		                
			            $sample_xml.='</VideoClicks>';
	
	                
		                $sample_xml.='<MediaFiles>';
		                $sample_xml.='<MediaFile delivery="progressive" bitrate="'.$resdata['bitrate'].'" width="'.$resdata['video_width'].'" height="'.$resdata['video_height'].'" type="'.$resdata['mime_type'].'">';
		                
						$sample_xml.='<![CDATA['.BASE.DATA_DIR.'/video/'.$resdata['aid'].'/'.$resdata['banner'].']]>';
		                
		                
		                $sample_xml.='</MediaFile>';
			            $sample_xml.='</MediaFiles>';
	                
						$sample_xml.='</Linear>';
						$sample_xml.='</Creative>';
						$sample_xml.='</Creatives>';
	                }
	                else
	                {
					 	$sample_xml.='<Creatives>';
		                $sample_xml.='<Creative>';
		                $sample_xml.='<NonLinearAds>';
                
                
		                $sample_xml.='<TrackingEvents>';
		                
			        $sample_xml.='<Tracking event="start"><![CDATA[{IMPRESSIONTRACKING}]]></Tracking>';
		
		                $sample_xml.='</TrackingEvents>';
                
		                if($resdata['type'] ==2)
		                {
		                    $sample_xml.='<NonLinear id="overlay" minSuggestedDuration="00:00:10" width="'.$video_banner_width.'" height="'.$video_banner_height.'" scalable="true" maintainAspectRatio="true">';
		                	
			                $sample_xml.='<StaticResource creativeType="'.$resdata['mime_type'].'">';
			                
							$sample_xml.='<![CDATA['.BASE.DATA_DIR.'/'.$resdata['aid'].'_'.$resdata['banner'].']]>';	                
			                
				            $sample_xml.='</StaticResource>';
		                }
	           
	            
		                if($adget_flag ==1)
			            $sample_xml.='<NonLinearClickThrough><![CDATA['.$clksurl.']]></NonLinearClickThrough>';
			            
			            
		                $sample_xml.='</NonLinear>';
						$sample_xml.='</NonLinearAds>';
						$sample_xml.='</Creative>';
						$sample_xml.='</Creatives>';
	                }
				
					$sample_xml.='</InLine>';
					$sample_xml.='</Ad>';
					$sample_xml.='</VAST>';
				}
				else
				{
					 $sample_xml='<VideoAdServingTemplate xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vast.xsd" version="4.0">';
					 $sample_xml.='<Ad id="'.$resdata['aid'].'">';
					 $sample_xml.='<InLine>';
					 $sample_xml.='<AdSystem>'.$admarket_name.'</AdSystem>';
						
					 $sample_xml.='<AdTitle>'.$admarket_name.' Vast NonLinear Text Ad</AdTitle>';
		
					 $sample_xml.='<Impression><Url><![CDATA[{IMPRESSIONTRACKING}]]></Url></Impression>';
					 
					 $sample_xml.='<NonLinearAds>';
		             $sample_xml.='<NonLinear id="overlay" resourceType="HTML">';
		             $sample_xml.='<Code>';
			         $sample_xml.='<![CDATA[';
		
					 $sample_xml.='<p>'.$resdata['title'].'</p>';
					 $sample_xml.='<p>'.$resdata['description'].'</p>';
					 $sample_xml.='<p>'.$resdata['display_url'].'</p>';
		
					 $sample_xml.=']]>';
				     $sample_xml.='</Code>';
			            	
				     
				     if($adget_flag ==1)
		             $sample_xml.='<NonLinearClickThrough><Url><![CDATA['.$clksurl.']]></Url></NonLinearClickThrough>';
		
					 $sample_xml.='</NonLinear>';
					 $sample_xml.='</NonLinearAds>';
					 $sample_xml.='</InLine>';
					 $sample_xml.='</Ad>';
					 $sample_xml.='</VideoAdServingTemplate>';
				}
				
				$this->set_variable('xmldata',$sample_xml,0);
			}	
	}
	
	
	
	function banner_action()
	{
		
		$admarket_name=Configuration::get_instance()->read('admarket_name');
		$aid=intval($this->read_page_param(1));
		$bannersize=intval($this->read_page_param(2));
		$aduid=intval($this->read_page_param(3));
		$currentmd5=$this->read_page_param(4);
		
		$md5=md5($admarket_name.$aid.$bannersize.$aduid);
		
		$success=0;
		
		if($md5 == $currentmd5)
		{
			if($this->get_ad_validation_user($aid,12))
			{
				if($this->get_adunit_exists($aduid,12))
				{
					$db= DAL::get_instance();	
					
					$image=$db->read_single_column("SELECT image FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=? AND banner_size=?",array($aid,$bannersize));
					
					if($image !="")
					{
						$array=array("jpeg"=>"image/jpg","jpg"=>"image/jpg","pjpeg"=>"image/jpg","png"=>"image/png","gif"=>"image/gif");
						
						$extension=explode(".",$image);
						$extensionname=strtolower($extension[count($extension)-1]);
						
						$filepath='../'.DATA_DIR.'/banners/'.$aid.'/'.$image;
						
						if(file_exists($filepath))
						{
							ob_clean();
							
						    	header('content-type:'.$array[$extensionname]);
							header('content-disposition:inline;filename="'.$image.'";');
							readfile($filepath);
							
							$success=1;
						}
						
					}
				}
			}
		}
		
		if($success ==0)
		{
			$filepath='../images/one.png';
						
			if(file_exists($filepath))
			{
				ob_clean();
				
			    	header('content-type:image/png');
				header('content-disposition:inline;filename="one.png";');
				readfile($filepath);
			}
		}
		
		exit;
	}
		
	
	
	
	
	function create_xml_cache($ad_buffer_data)
	{
		if(Configuration::get_instance()->read('ad_display_cache_time') >0 && trim($ad_buffer_data) !="")
		{
		   $cache_file_db_operation='<?php
			
		   $created_time='.time().';
			
		   if((Configuration::get_instance()->read("ad_display_cache_time")*60) < (time()-$created_time))
		   $cached_flag=2;
		   else
		   {
		        $cached_flag=1;							
			
				ob_start();

				ob_clean();
				
				header("Content-type: text/xml; charset=utf-8");
				header("Access-Control-Allow-Origin: *");

				?>'.$ad_buffer_data.'<?php
				$ad_cache_buffer_data = ob_get_contents();
                
                
                $cache_cpm_string="'.$this->cache_cpm_string.'";
                
				  
                $vast_aid='.$this->vast_aid.';
                $vast_ad_get='.$this->vast_ad_get.';
                
                
                $vast_ad_type='.$this->vast_ad_type.';
                $vast_ad_tracking_time='.$this->vast_ad_tracking_time.';
                $vast_ad_duration='.$this->vast_ad_duration.';
                
				$specialtime=0;
		
				if($vast_ad_type ==13)
				{
					$vast_ad_duration='.$this->vast_ad_duration.';
				
					if($vast_ad_tracking_time ==0)
					$specialtime=time()+120;
					{
						$percentage_time=($vast_ad_duration*$vast_ad_tracking_time)/100;
					
						$specialtime=time()+$percentage_time+120;
					}
				}
				else
				$specialtime=time()+120;               
                
				
				$urlsrc="";
				if($vast_ad_get ==1)
				{
					$impression_data_string=$this->get_impression_data($cache_cpm_string,$vast_ad_type);
					
					if($impression_data_string !="")
					$urlsrc=TRACK_BASE.TRACK_DIR."/index.php?page=click/data/".$impression_data_string."/".md5($impression_data_string.Configuration::get_instance()->read("admarket_name").$this->cache_ip.$this->cache_user_agent.$this->cache_date.$specialtime.$this->geo_country)."/".$specialtime."/".$this->geo_country."/1";
				}
				else
				$urlsrc=TRACK_BASE.TRACK_DIR."/index.php?page=click/default_update/".$vast_aid;
		
				
				$urlsrc=str_replace("https:","",$urlsrc);
				$urlsrc=str_replace("http:","",$urlsrc);
				
				$ad_cache_buffer_data = str_replace("{IMPRESSIONTRACKING}",$urlsrc, $ad_cache_buffer_data);
                		$ad_cache_buffer_data = str_replace("{ENCIP}","'.$this->cache_encip.'", $ad_cache_buffer_data);
                
                
			
				
				ob_clean();
				echo $ad_cache_buffer_data;
			}
			?>';
		   
			file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/".$this->cfile_name,$cache_file_db_operation);
		}
		
		
		$specialtime=0;

		if($this->vast_ad_type ==13)
		{
			if($this->vast_ad_tracking_time ==0)
			$specialtime=time()+120;
			{
				$percentage_time=($this->vast_ad_duration*$this->vast_ad_tracking_time)/100;
			
				$specialtime=time()+$percentage_time+120;
			}
		}
		else
		$specialtime=time()+120;
		
		

	
		$urlsrc="";
		if($this->vast_ad_get ==1)
		{
			$impression_data_string=$this->get_impression_data($this->cache_cpm_string,$this->vast_ad_type);
			
			if($impression_data_string !="")
			$urlsrc=TRACK_BASE.TRACK_DIR.'/index.php?page=click/data/'.$impression_data_string.'/'.md5($impression_data_string.Configuration::get_instance()->read('admarket_name').$this->cache_ip.$this->cache_user_agent.$this->cache_date.$specialtime.$this->geo_country).'/'.$specialtime.'/'.$this->geo_country.'/1';
		}
		else
		$urlsrc=TRACK_BASE.TRACK_DIR.'/index.php?page=click/default_update/'.$this->vast_aid;

		
		$urlsrc=str_replace("https:","",$urlsrc);
		$urlsrc=str_replace("http:","",$urlsrc);
		
		$ad_buffer_data = str_replace("{IMPRESSIONTRACKING}",$urlsrc, $ad_buffer_data);
		$ad_buffer_data = str_replace("{ENCIP}",$this->cache_encip, $ad_buffer_data);
		
		
		
		return $ad_buffer_data;
	}	
	
	
	
	function create_cache($ad_buffer_data)
	{
			if(Configuration::get_instance()->read('ad_display_cache_time') >0 && trim($ad_buffer_data) !="")
			{
					   $cache_file_db_operation='
					   <?php
						
					   $created_time='.time().';
						
					   if((Configuration::get_instance()->read("ad_display_cache_time")*60) < (time()-$created_time))
					   $cached_flag=2;
					   else
					   {
					       $cached_flag=1;						
						
						   ob_start();
			
			               $display_type='.$this->display_type_value.';
			               
			               $cpc_enabled="'.$this->cpc_enabled_value.'";
			               $cpm_enabled="'.$this->cpm_enabled_value.'";
			               $html_enabled="'.$this->html_enabled_value.'";
			               $sponsored_enabled="'.$this->sponsored_enabled_value.'";
			               $cpa_enabled="'.$this->cpa_enabled_value.'";
			               $pop_enabled="'.$this->pop_enabled_value.'";
				       	   
			               
			               $ppc_tracking_interval="'.$this->ppc_tracking_interval.'";
			               $cpa_tracking_interval="'.$this->cpa_tracking_interval.'";
			               $cpm_tracking_interval="'.$this->cpm_tracking_interval.'";
			               $sponsored_tracking_interval="'.$this->sponsored_tracking_interval.'";
			               $html_tracking_interval="'.$this->html_tracking_interval.'";
			               $interstitial_tracking_interval="'.$this->interstitial_tracking_interval.'";
			               $pop_tracking_interval="'.$this->pop_tracking_interval.'";
						   
			               $pop_ad_string_data="'.$this->pop_ad_string_data.'";
			               $originalpopad='.$this->originalpopad.';
			               $popaid='.$this->popaid.';
			               
			               
						   //if($display_type ==9)                       //Need it for some server requests
						   //header("Content-Type: text/javascript");	 //Need it for some server requests		               
			               
			          
							ob_clean();
			
							?>'.$ad_buffer_data.'<?php
	
							
							$ad_cache_buffer_data = ob_get_contents();
							
						
						
							if($display_type ==9 && $pop_enabled ==1)
							{
								if($originalpopad ==1)
								{
									if($this->popdirect ==1)
									$specialtime=time()+$pop_tracking_interval+20;
									else
									$specialtime=time()+900;			

									$impression_data_string=$this->get_impression_data($pop_ad_string_data,$display_type);
									
						    if($impression_data_string !="")
						    $ad_cache_buffer_data = str_replace("{POPPARAM}",$this->mybase64_encode(TRACK_BASE.TRACK_DIR."/index.php?page=click/data/".$pop_ad_string_data."/".md5($cache_pop_string.$this->cache_ip.$this->cache_user_agent.$this->cache_date."0".$this->geo_country)."/0/".$this->geo_country), $ad_cache_buffer_data);					
						    else
						    $ad_cache_buffer_data = str_replace("{POPPARAM}","", $ad_cache_buffer_data);
						}
						else
						$ad_cache_buffer_data = str_replace("{POPPARAM}",$this->mybase64_encode(TRACK_BASE.TRACK_DIR."/index.php?page=click/default_update/".$popaid), $ad_cache_buffer_data);
					}
					else
					{	
				                		$ad_cache_buffer_data = str_replace("{ENCIP}",$this->cache_encip, $ad_cache_buffer_data);
				                
				                		$cache_cpm_string="'.$this->cache_cpm_string.'";
				                
		
								$specialtime=0;
					
								if($display_type ==0)
								$specialtime=time()+$ppc_tracking_interval+10;
								else if($display_type ==1)
								$specialtime=time()+$cpm_tracking_interval+10;
								else if($display_type ==2)
								$specialtime=time()+$html_tracking_interval+10;
								else if($display_type ==3)
								$specialtime=time()+$sponsored_tracking_interval+10;	
								else if($display_type ==6)
								$specialtime=time()+$cpa_tracking_interval+10;             
								else if($display_type ==13)
								$specialtime=time()+900;  		

								
								$impression_data_string=$this->get_impression_data($cache_cpm_string,$display_type);
								
								
								$ad_cache_buffer_data = str_replace("{STRINGDATA}",$impression_data_string, $ad_cache_buffer_data);
							    	$ad_cache_buffer_data = str_replace("{MDSTRINGDATA}",md5($impression_data_string.Configuration::get_instance()->read("admarket_name").$this->cache_ip.$this->cache_user_agent.$this->cache_date.$specialtime.$this->geo_country),$ad_cache_buffer_data);
							    	$ad_cache_buffer_data = str_replace("{MDSTRINGTIME}",$specialtime, $ad_cache_buffer_data);
								$ad_cache_buffer_data = str_replace("{MDSTRINGCOUNTRY}",$this->geo_country, $ad_cache_buffer_data);

								
								if($impression_data_string !="")
								$ad_cache_buffer_data = str_replace("{DATAHEADAPPEND}","document.getElementsByTagName(\"head\")[0].appendChild(script);", $ad_cache_buffer_data);
								else
								$ad_cache_buffer_data = str_replace("{DATAHEADAPPEND}","script.src=\"\";", $ad_cache_buffer_data);								
								
	  		                			}
			                
			
							ob_clean();
							echo $ad_cache_buffer_data;
						}
						?>';
		
						file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/".$this->cfile_name,$cache_file_db_operation);
					}
					
					
					

		if($this->display_type_value ==9 && $this->pop_enabled_value ==1)
		{
			if($this->originalpopad ==1)
			{
				if($this->popdirect ==1)
				$specialtime=time()+$this->pop_tracking_interval+20;
				else
				$specialtime=time()+900;
				
				$impression_data_string=$this->get_impression_data($this->pop_ad_string_data,$this->display_type_value);
				
				
				if($impression_data_string !="")
				$ad_buffer_data = str_replace("{POPPARAM}",$this->mybase64_encode(TRACK_BASE.TRACK_DIR."/index.php?page=click/data/".$impression_data_string."/".md5($impression_data_string.Configuration::get_instance()->read("admarket_name").$this->cache_ip.$this->cache_user_agent.$this->cache_date.$specialtime.$this->geo_country)."/".$specialtime."/".$this->geo_country), $ad_buffer_data);
				else
				$ad_buffer_data = str_replace("{POPPARAM}","", $ad_buffer_data);
				
			}
			else
			$ad_buffer_data = str_replace("{POPPARAM}",$this->mybase64_encode(TRACK_BASE.TRACK_DIR."/index.php?page=click/default_update/".$this->popaid), $ad_buffer_data);
		}
		else
		{			
					
			$ad_buffer_data = str_replace("{ENCIP}",$this->cache_encip, $ad_buffer_data);
		
			$specialtime=0;

			if($this->display_type_value ==0)
			$specialtime=time()+$this->ppc_tracking_interval+10;
			else if($this->display_type_value ==1)
			$specialtime=time()+$this->cpm_tracking_interval+10;
			else if($this->display_type_value ==2)
			$specialtime=time()+$this->html_tracking_interval+10;			
			else if($this->display_type_value ==3)
			$specialtime=time()+$this->sponsored_tracking_interval+10;			
			else if($this->display_type_value ==6)
			$specialtime=time()+$this->cpa_tracking_interval+10;
			else if($this->display_type_value ==13)
			$specialtime=time()+900;
			

			$impression_data_string=$this->get_impression_data($this->cache_cpm_string,$this->display_type_value);
			
			
			$ad_buffer_data = str_replace("{STRINGDATA}",$impression_data_string, $ad_buffer_data);
		    $ad_buffer_data = str_replace("{MDSTRINGDATA}",md5($impression_data_string.Configuration::get_instance()->read('admarket_name').$this->cache_ip.$this->cache_user_agent.$this->cache_date.$specialtime.$this->geo_country),$ad_buffer_data);
		    $ad_buffer_data = str_replace("{MDSTRINGTIME}",$specialtime, $ad_buffer_data);
			$ad_buffer_data = str_replace("{MDSTRINGCOUNTRY}",$this->geo_country, $ad_buffer_data);
			
			
			if($impression_data_string !="")
			$ad_buffer_data = str_replace("{DATAHEADAPPEND}",'document.getElementsByTagName("head")[0].appendChild(script);', $ad_buffer_data);
			else
			$ad_buffer_data = str_replace("{DATAHEADAPPEND}",'script.src="";', $ad_buffer_data);
		}
			
		
		return $ad_buffer_data;
	}
	
	
	function get_impression_data($data_get_string,$adtype)
	{
		$dbadlimit=0;
		
		if($adtype ==1) //CPM
		$dbadlimit=intval(Configuration::get_instance()->read('cpm_ad_impression_limit_hour'));
		else if($adtype ==2)  //HTML
		$dbadlimit=intval(Configuration::get_instance()->read('html_ad_impression_limit_hour'));
		else if($adtype ==0 || $adtype ==3 || $adtype ==6)  //CPC/CPD/CPA
		$dbadlimit=1;
		else if($adtype ==9)  //POP
		$dbadlimit=intval(Configuration::get_instance()->read('pop_ad_impression_limit_hour'));
		else if($adtype ==13)  //CPV
		$dbadlimit=intval(Configuration::get_instance()->read('cpv_ad_impression_limit_hour'));
		

		if($dbadlimit >0)
		{
			$data_get_array=explode('.data.',$data_get_string);
			$data_get_array_count=count($data_get_array);
				
			$cookiecp='';
				
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
					$cookiecparray=explode('_',$cookiecp);

					foreach($cookiecparray as $ckey => $cvalue)
					{
						$cvalueexp=explode('-',$cvalue);

						if($cvalueexp[0] == $aid && $cvalueexp[1] >= $dbadlimit)
						unset($data_get_array[$i]);
					}
				}
			}
				
			//if(count($data_get_array) >0)
			//$data_get_string=implode('.data.',$data_get_array);
			//else
			//$data_get_string="";
			
			
			
			
			
			if(count($data_get_array) > 1)
			$data_get_string=implode('.data.',$data_get_array);
			else if(count($data_get_array) == 1)
			{
				$data_get_array = array_values($data_get_array);
				
				$data_get_string=$data_get_array[0];
			}
			else
			$data_get_string="";			
			
			
			
		}
			
		return $data_get_string;
	}
	

	
	function remove_iframe($adcodeid,$message=0)
	{
		$show_iframe_space=intval(Configuration::get_instance()->read('show_iframe_space'));
	
		$string_data="";
		
		if($show_iframe_space ==0)
		{
			$noadscontent=array('operation'=>'noads','auid'=>$adcodeid);
			$noadscontent=json_encode($noadscontent);		
			
			$string_data="<script data-cfasync='false' type='text/javascript'>window.parent.postMessage('".$noadscontent."','*');</script>";
		}		
		else
		{
			$message_data="";
			
			
			if($message ==1)
			$message_data="Display request from proxy site.";
			else if($message ==2)
			$message_data="Suspicious ad query requests.";
			else if($message ==3)
			$message_data="Native addon disabled.But try to display native ads.";
			else if($message ==4)
			$message_data="Interstitial addon disabled.But try to display interstitial ads.";
			else if($message ==5)
			$message_data="Skin addon disabled.But try to display skin ads.";
			else if($message ==6)
			$message_data="Video addon disabled.But try to display video ads.";
			else if($message ==7)
			$message_data="Video addon enabled.But html5 player support disabled.";
			else if($message ==8)
			$message_data="Video addon enabled.But video type is vast.";
			else if($message ==9)
			$message_data="Aspect ratio height mismatch.";
			else if($message ==10)
			$message_data="Aspect ratio width mismatch.";
			else if($message ==11)
			$message_data="Aspect ratio mismatch.";
			else if($message ==12)
			$message_data="Text ads disabled.But try to display text ads.";
			else if($message ==13)
			$message_data="Text+image ads disabled.But try to display text+image ads.";			
			else if($message ==14)
			$message_data="Invalid adcode id.";
			else if($message ==15)
			$message_data="Unauthorized website.";
			else if($message ==16)
			$message_data="Adcode size mismatch.";
			else if($message ==17)
			$message_data="Publisher status not active.";
			
			
			$string_data="<div style='display:none;'>".$message_data."</div>";
		}
		
		return $string_data;
	}
	
	
};
?>