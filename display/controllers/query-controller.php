<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."device-helper.php";

/********* Move this section to action section*************/

include_once LIB_DIR_PATH.DS."dbipclass/dbip.class.php";
include_once LIB_DIR_PATH.DS."DeviceDetect/BrowserDetection.php";

/********* Move this section to action section*************/

class QueryController extends ApplicationController
{
	function before_execute()
	{
	     parent::before_execute();
	     setlocale(LC_ALL,'en_US'); //In case of some languages,decimal places replace with ','.
	}

	var $geo_country 				= "";

	var $cpc_impression  			= "";
	var $cpm_impression  			= "";
	var $cpa_impression  			= "";
	var $cpd_impression  			= "";
	var $cpv_impression  			= "";
	var $html_impression 			= "";
 	var $pop_impression             = "";

	var $originalAdImpressionString = "";

	var $adPricingValue    			= 0;
	var $cpc_enabled_value 			= 0;
	var $cpm_enabled_value 			= 0;
	var $cpa_enabled_value 			= 0;
	var $cpd_enabled_value 			= 0;
	var $video_enabled_value 		= 0;
	var $html_enabled_value 		= 0;
	var $pop_enabled_value 			= 0;

	var $textimage_enabled_value 	= 0;
	var $interstitial_enabled_value = 0;
	var $category_enabled_value 	= 0;
	var $device_enabled_value 		= 0;
	var $language_targeting_enabled = 0;
	var $isp_targeting_enabled 		= 0;
	var $referral_enabled_value 	= 0;





	var $ppc_tracking_interval 		= 0;
	var $cpa_tracking_interval 		= 0;
	var $cpm_tracking_interval 		= 0;
	var $html_tracking_interval 	= 0;
	var $cpd_tracking_interval 		= 0;
	var $pop_tracking_interval 		= 0;
	var $video_tracking_interval 	= 0;

	var $geo_tracking_enabled 		= 0;

	var $totalAdGet         		= 0;

	var $vast_aid 					= 0;
	var $vast_paid_ad_get 				= 0;
	var $vast_ad_type 				= 0;
	var $vast_ad_duration 			= 0;
	var $vast_ad_tracking_time 		= 0;
	var $vastWinNoticeURL                   = "";

	var $ecommerceFlag				= 0;
	var $displayAdCount  			= 0;
	var $adcodeID		 			= 0;
	var $ad_container_type			= 0;

	var $feedAd  					= 0;
	var $feedAdID  					= 0;

	var $feedCacheable      		= 1;
	var $adRotationEnabled          = 0;
	var $adRotationInterval         = 0;

	var $adListArray     			= array();
	var $indexAppend                = "";

	var $trackDomain          = "";

	var $exchangeAd  					= 0;
  	var $exchangeAdID  				= 0;
	var $html_ad_get_flag            = 0;
	
	function items_action()
	{
		if(!MOD_REWRITE)
		$this->indexAppend = "index.php?page=";

		$time    				  		= time();
		$displayArray			  		= array();
		$containerArray 		  		= array();
		$restrictedSiteArray            = array();
		$language                 		= array();
		$languageArray                  = array();
		$categorySupportedPricing 		= array();
		$adDisplayStep			   		= array();
		$queryReplacementArray         	= array();
		$queryRetargetReplacementArray 	= array();
		$queryDefaultReplacementArray  	= array();

		$cachedFlag             = 0;
		$invalidSiteFlag 		= 0;
		$native                 = 0;
		$expandablesupport		= 0;
		$expandable_width		= 0;
		$expandable_height		= 0;
		$isp_enabled            = 0;
		$connection_enabled     = 0;
		$balanceAdsForFilling   = 0;

		$cityid                 = 0;
		$sid                    = 0;
		$catid                  = 0;
		$osid                   = 0;
		$browserid              = 0;
		$ispid                  = 0;
		$conn_id                = 0;
		$publisherRid           = 0;

		

		$retarget_display       = 0;
		$responsiveAdBlockID    = 0;
		$adblockSizeChanged     = 0;

		$adcodeAllowedAds        = 1;
		$nativeRows        		 = 0;
		$nativeColumns     		 = 0;
		$nativeAdsAllowed        = 1;
		$nativeImagePosition     = 0;
		$nativeResponsiveSupport = 0;


		$cpmHtmlEnabled         = 0;
		$ad_container_type      = 0;
		$videoAspectRatioID     = 0;


		$html5_player_support   = 0;
		$adv_ref_enabled        = 0;
		$pub_ref_enabled        = 0;
		$ppctrack               = 0;
		$cpmtrack               = 0;
		$htmltrack              = 0;
		$cpatrack               = 0;
		$poptrack               = 0;
		$videotrack             = 0;
		$sponsoredtrack         = 0;
		$trackInterval          = 0;

		$dateFlag               = 0;
		$imageSupport 			= 0;
		$ecommerceSupport 		= 0;
		$bannerAdsSupport   	= 0;

		$bannerWidth 			= 0;
		$bannerHeight 			= 0;
		$feedQueryLimit 	    = 1;
		$exchangeQueryLimit 	= 5; //Need to taken from settings
	
		
		$feedGetSuccess        = 0;
		$exchangeGetSuccess    = 0;

		$keywordJoin  			   = "";
		$countryJoin               = "";
		$ispJoin            	   = "";
		$retargetJoin     		   = "";
		$countryJoinCondition      = "";

		$bannerCondition           = "";
		$cpdBannerCondition        = "";
		$countryCondition          = "";
		$categoryCondition         = "";
		$languageCondition         = "";
		$connectionCondition       = "";
		$ispCondition     		   = "";
		$dateCondition             = "";
		$deviceCondition           = "";
		$osCondition               = "";
		$browserCondition          = "";
		$html5Condition            = "";
		$videoAspectRatioCondition = "";
		$videoAspectRatioConditionDefault = "";

		$budgetCondition 	            = "";
		$dailyBudgetCondition           = "";
		$siteRestrictionCondition       = "";
		$sameAccountAdDisplayCondition  = "";
		$retargetAdsIncludeCondition    = "";
		$retargetAdsExcludeCondition    = "";

		$html5Columns           = "";
		$html5DefaultColumns    = "";
		$referralColumns        = "";
		$expandableColumns      = "";
		$feedColumns      		= "";
		$dspColumns             = "";
		$cpvColumns             = "";
		$cpvDefaultColumns      = "";
		$html5ColumnsCPD        = "";
		$expandableColumnsCPD   = "";

		$cacheDevice            = "";
		$cacheCity              = "";
		$retargetCache          = "";

		$tableColumnsAppend     = "";
		$retargetString         = "";

		$isp                    = "";
		$connection             = "";
		$osname                 = "";
		$browsername            = "";
		$deviceType             = "";

		$country                = "";
		$countryAlpha3          = "";
		$subdivision1           = "";
		$subdivision2           = "";
		$cityname               = "";
		$latitude               = "";
		$longitude              = "";

		$siteTitle 				= "";
		$siteDescription        = "";

		$adDisplaySite          = "";
		$nativeCustomCode       = "";

		$impTime =date("Y",time());
		$impTime.=date("m",time());
		$impTime.=date("d",time());
		$impTime.=date("H",time());

		$impTimeMinute  = $impTime.date("i",time());

		$requestIP      = UtilityHelper::get_user_ip();

		$requestUserAgent       = $_SERVER['HTTP_USER_AGENT'];
		$encryptedIP    		= md5($requestIP);
		$ipUserAgent       		= $requestIP.$requestUserAgent;

		$native_enabled      	 = $this->get_addon_status('native-ad-display_enabled');
		$responsive_enabled  	 = $this->get_addon_status('responsive-ads_enabled');
		$max_adcodes_in_one_page = intval(Configuration::get_instance()->read('max_adcodes_in_one_page'));
		$systemCurrency          = Configuration::get_instance()->read('system_currency');

		$adunitid 				= intval($this->read_get_param("aduid"));
		$publisher				= intval($this->read_get_param("pid"));
		$pricingRequest         = intval($this->read_get_param("displaytype"));
		$block_id   			= intval($this->read_get_param("block_id"));
		$responsive				= intval($this->read_get_param("responsive"));
		$directlink             = intval($this->read_get_param("directlink"));
		$native                 = intval($this->read_get_param("native"));
		$interstitial           = intval($this->read_get_param("interstitial"));
		$skin                   = intval($this->read_get_param("skin"));
		$inpagepush             = intval($this->read_get_param("inpagepush"));
		$pop                    = intval($this->read_get_param("pop"));
		$video                  = intval($this->read_get_param("video"));

		$adcode_max_count		= intval($this->read_get_param("adcode_count"));
		$key_time     			= intval($this->read_get_param("time"))+40;
		$ad_container_width     = intval($this->read_get_param("width"));
		$ad_container_height    = intval($this->read_get_param("height"));
		$adSectionWidth			= intval($this->read_get_param("adSectionWidth"));
		$device_type	        = $this->read_get_param("device_type");

		$page_data    			= trim($this->read_get_param("page_data"));
		$cpc_impression 		= trim($this->read_get_param("cpc_impression"));
		$cpm_impression 		= trim($this->read_get_param("cpm_impression"));
		$cpa_impression 		= trim($this->read_get_param("cpa_impression"));
		$cpd_impression 		= trim($this->read_get_param("cpd_impression"));
		$cpv_impression 		= trim($this->read_get_param("cpv_impression"));
		$html_impression 		= trim($this->read_get_param("html_impression"));
		$pop_impression 		= trim($this->read_get_param("pop_impression"));


		$siteKeywords          = urldecode(trim($this->read_get_param("search_keywords")));
		$siteTitle             = urldecode(trim($this->read_get_param("page_title")));
		$siteDescription       = urldecode(trim($this->read_get_param("meta_description")));

		$display_site          = strtolower(urldecode(trim($this->read_get_param("deliver"))));
		$page_referrer         = $this->mybase64_decode(trim($this->read_get_param("page_referrer")));

		$retarget              = $this->read_cookie_param('ads_retarget');
		$retarget_already      = $this->read_cookie_param('ads_retarget_exclude');


		/************ For set track domain *************/
		$trackDomain       = $this->get_track_domain();

		$this->trackDomain = $trackDomain;
		$this->set_variable("trackDomain",$trackDomain);
		/************ For set track domain *************/

		if($responsive_enabled != 1)
		$responsive 			= 0;

		if($native_enabled != 1)
		$native                 = 0;

		$validator    		   = md5($requestIP."ADM-DATA-ADM".$requestUserAgent.$key_time);

		if(Configuration::get_instance()->read('drop_suspicious_ad_query_requests') ==1)
		{
			if($directlink == 0)
			{
				if($page_data != $validator)
				{
					echo $this->remove_iframe($adunitid,2,$pricingRequest);
					exit;
				}
			}
		}

		if(Configuration::get_instance()->read('proxy_detection_for_ad_display') ==1)
		{
			if(UtilityHelper::proxyDetection())
			{
				echo $this->remove_iframe($adunitid,1,$pricingRequest);
				die;
			}
		}
		
		if($max_adcodes_in_one_page > 0 && $adcode_max_count > $max_adcodes_in_one_page)
		exit;

		//if($pop == 1)           //Need it for some server requests
		//header("Content-Type: application/javascript");	 //Need it for some server requests

		$cpc_enabled                = $this->get_addon_status('cpc_enabled');
		$cpm_enabled                = $this->get_addon_status('cpm_enabled');
		$cpa_enabled                = $this->get_addon_status('cpa_enabled');
		$cpd_enabled                = $this->get_addon_status('sponsored_enabled');
		$pop_enabled                = $this->get_addon_status('pop-ads_enabled');
		$html_enabled               = $this->get_addon_status('html_enabled');
		$video_enabled              = $this->get_addon_status('video-ads_enabled');

		$city_enabled               = $this->get_addon_status('city-targeting_enabled');
		$device_targeting_enabled   = $this->get_addon_status('device-targeting_enabled');
		$time_targeting_enabled     = $this->get_addon_status('time-targeting_enabled');
		$retargeting_enabled        = $this->get_addon_status('retargeting_enabled');
		$language_targeting_enabled = $this->get_addon_status('language-targeting_enabled');
		$isp_connection_enabled 	= $this->get_addon_status('isp-connection-targeting_enabled');
		$category_targeting_enabled = $this->get_addon_status('category-targeting_enabled');

		$textimage_enabled          = $this->get_addon_status('text-image-ads_enabled');
		$sticky_enabled             = $this->get_addon_status('sticky-ad-display_enabled');
		$interstitial_enabled       = $this->get_addon_status('interstitial_enabled');
		$ecommerce_enabled          = $this->get_addon_status('ecommerce-ads_enabled');
		$expandable_enabled         = $this->get_addon_status('expandable-banners_enabled');
		$feedads_enabled            = $this->get_addon_status('feed-ads_enabled');
		$skin_enabled               = $this->get_addon_status('skin-ads_enabled');
		$html5_enabled              = $this->get_addon_status('html5-ads_enabled');
		$directlink_enabled         = $this->get_addon_status('direct-link-ads_enabled');
		$referral_enabled 			= $this->get_addon_status('referral_enabled');
		$inpage_push_enabled        = $this->get_addon_status('inpage-push-ads_enabled');
		$dspconnector_enabled       = $this->get_addon_status('dsp-connector_enabled');

		if($dspconnector_enabled == 1)
			$exchangeQueryLimit = Configuration::get_instance()->read('dsp_exchange_query_limit');

		$automaticRotation 			= intval(Configuration::get_instance()->read('automatic_ad_rotation'));
		$automaticRotationInterval 	= intval(Configuration::get_instance()->read('automatic_ad_rotation_interval'));

		$this->adRotationInterval   = $automaticRotationInterval;

		$this->set_variable("automaticRotation",$automaticRotation);
		$this->set_variable("automaticRotationInterval",$automaticRotationInterval);

		if($isp_connection_enabled == 1)
		{
			$isp_enabled 			= $this->get_addon_status('isp-targeting_enabled');
			$connection_enabled 	= $this->get_addon_status('connectiontype-targeting_enabled');
		}

		if($feedads_enabled == 1)
		$feedads_enabled 			= intval(Configuration::get_instance()->read('demand_feed_ads'));

		if($city_enabled ==1)
		{
			$record =UtilityHelper::get_geo_details_from_ip($requestIP,1);

			$country=$record->country_code;
			$subdivision1=$record->subdivision1;
			$subdivision2=$record->subdivision2;
			$cityname=$record->city;
			$latitude=$record->latitude;
			$longitude=$record->longitude;
			$cityid=$record->cityid;

			$cacheCity = $subdivision1.$subdivision2.$cityid;
		}
		else
		{
			$country = UtilityHelper::get_country_from_ip($requestIP);
		}
		
	
		$countryResultArray = json_decode(COUNTRY_RESULT_JSON, 1);
		
		if(isset($countryResultArray[$country]))
		$countryAlpha3 = $countryResultArray[$country];
		

		$this->geo_country      = $country;
		$this->cacheIP          = $requestIP;
		$this->cacheUserAgent   = $requestUserAgent;
		$this->cacheDate        = date('d',time()).date('H',time());
		$this->cacheEncryptedIP = $encryptedIP;
		$this->adcodeID	        = $adunitid;
		$this->cpc_impression	= $cpc_impression;
		$this->cpm_impression	= $cpm_impression;
		$this->cpa_impression	= $cpa_impression;
		$this->cpd_impression	= $cpd_impression;
		$this->cpv_impression	= $cpv_impression;
		$this->html_impression	= $html_impression;
		$this->pop_impression	= $pop_impression;

		$excludeArray           = array();
		$capTimeCurrent         = time();

		//************* For excluding cap completed ads ***************/
		if(!DEMO_MODE && $directlink == 0)
		{
				if(($pricingRequest == 0 || $pricingRequest == 4) && $cpc_impression != "") //CPC or CPC/CPM/CPA
				{
						$capCookieArray     = explode("_",$this->mybase64_decode($cpc_impression));
						$maxImpressionLimit = 5;

						$notINString    = "";
						foreach($capCookieArray as $capKey => $capValue)
						{
								if(trim($capValue) != "")
								{
										$capValueArray   = explode("-",$capValue);

										if($capValueArray[2] > $capTimeCurrent && $capValueArray[1] >= $maxImpressionLimit)
										{
												if($notINString != "")
												$notINString.= ",";

												$notINString.= $capValueArray[0];
										}
								}
						}

						if($notINString != "")
						$excludeArray["ppc"] = " AND ac.id NOT IN (".$notINString.") ";
				}

				if(($pricingRequest == 1 || $pricingRequest == 4) && $cpm_impression != "" && $pop == 0 && $video == 0) //CPM or CPC/CPM/CPA
				{
						$capCookieArray     = explode("_",$this->mybase64_decode($cpm_impression));
						$maxImpressionLimit = intval(Configuration::get_instance()->read('cpm_ad_impression_limit_hour'));


						$notINString    = "";
						foreach($capCookieArray as $capKey => $capValue)
						{
								if(trim($capValue) != "")
								{
										$capValueArray   = explode("-",$capValue);

										if($capValueArray[2] > $capTimeCurrent && $capValueArray[1] >= $maxImpressionLimit)
										{
												if($notINString != "")
												$notINString.= ",";

												$notINString.= $capValueArray[0];
										}
								}
						}

						if($notINString != "")
						$excludeArray["cpm"] = " AND ac.id NOT IN (".$notINString.") ";


						if($html_impression != "") //HTML
						{
								$capCookieArray     = explode("_",$this->mybase64_decode($html_impression));
								$maxImpressionLimit = intval(Configuration::get_instance()->read('html_ad_impression_limit_hour'));

								$notINString    = "";
								foreach($capCookieArray as $capKey => $capValue)
								{
										if(trim($capValue) != "")
										{
												$capValueArray   = explode("-",$capValue);

												if($capValueArray[2] > $capTimeCurrent && $capValueArray[1] >= $maxImpressionLimit)
												{
														if($notINString != "")
														$notINString.= ",";

														$notINString.= $capValueArray[0];
												}
										}
								}

								if($notINString != "")
								$excludeArray["html"] = " AND ac.id NOT IN (".$notINString.") ";
						}
				}

				if(($pricingRequest == 6 || $pricingRequest == 4) && $cpa_impression != "") //CPA or CPC/CPM/CPA
				{
						$capCookieArray     = explode("_",$this->mybase64_decode($cpa_impression));
						$maxImpressionLimit = 5;

						$notINString    = "";
						foreach($capCookieArray as $capKey => $capValue)
						{
								if(trim($capValue) != "")
								{
										$capValueArray   = explode("-",$capValue);

										if($capValueArray[2] > $capTimeCurrent && $capValueArray[1] >= $maxImpressionLimit)
										{
												if($notINString != "")
												$notINString.= ",";

												$notINString.= $capValueArray[0];
										}
								}
						}

						if($notINString != "")
						$excludeArray["cpa"] = " AND ac.id NOT IN (".$notINString.") ";
				}

				if($pricingRequest == 1 && $video == 1 && $cpv_impression != "") //CPV
				{
						$capCookieArray     = explode("_",$this->mybase64_decode($cpv_impression));
						$maxImpressionLimit = intval(Configuration::get_instance()->read('cpv_ad_impression_limit_hour'));

						$notINString    = "";
						foreach($capCookieArray as $capKey => $capValue)
						{
								if(trim($capValue) != "")
								{
										$capValueArray   = explode("-",$capValue);

										if($capValueArray[2] > $capTimeCurrent && $capValueArray[1] >= $maxImpressionLimit)
										{
												if($notINString != "")
												$notINString.= ",";

												$notINString.= $capValueArray[0];
										}
								}
						}

						if($notINString != "")
						$excludeArray["cpv"] = " AND ac.id NOT IN (".$notINString.") ";
				}

				if($pricingRequest == 1 && $pop == 1 && $pop_impression != "") //POP
				{
						$capCookieArray     = explode("_",$this->mybase64_decode($pop_impression));
						$maxImpressionLimit = intval(Configuration::get_instance()->read('pop_ad_impression_limit_hour'));

						$notINString    = "";
						foreach($capCookieArray as $capKey => $capValue)
						{
								if(trim($capValue) != "")
								{
										$capValueArray   = explode("-",$capValue);

										if($capValueArray[2] > $capTimeCurrent && $capValueArray[1] >= $maxImpressionLimit)
										{
												if($notINString != "")
												$notINString.= ",";

												$notINString.= $capValueArray[0];
										}
								}
						}

						if($notINString != "")
						$excludeArray["pop"] = " AND ac.id NOT IN (".$notINString.") ";
				}
		}
		//************* For excluding cap completed ads ***************/


		if($device_targeting_enabled == 1)
		{
			$browserClass   = new foroco\BrowserDetection();
			$browserResult  = $browserClass->getAll($requestUserAgent);


			$deviceType = $browserResult['device_type'];

			if($deviceType =='desktop')
			$cacheDevice='c';
			else if($deviceType =='mobile')
			$cacheDevice='m';

			$os_targeting_enabled      = Configuration::get_instance()->read('os-targeting_enabled');
			$browser_targeting_enabled = Configuration::get_instance()->read('browser-targeting_enabled');

			if($os_targeting_enabled == 1)
			{
				$osname=$browserResult['os_name'];
				$cacheDevice=$cacheDevice.$osname;
			}

			if($browser_targeting_enabled == 1)
			{
				$browsername=$browserResult['browser_name'];
				$cacheDevice=$cacheDevice.$browsername;
			}
		}

		/***************************** Ad Retargeting **************************/
		if($retargeting_enabled ==1 && $retarget != "" && $directlink == 0)
		{
			$retargetarray=explode(',',$retarget);

			foreach($retargetarray as $rkey=>$rvalue)
			{
				if($rvalue !="")
				{
					$rvaluearray=explode('-',$rvalue);

					if($rvaluearray[2] > time())
					{
						if($retargetString !="")
						$retargetString.=",";

						$retargetString.=$rvaluearray[1];
					}
				}
			}


			if($retargetString !="")
			{
				$retargetCache    = $retargetString;

				$retarget_display=1;

				$retargetAdsIncludeCondition = " AND rm.rid IN (".$retargetString.") ";

				$retargetJoin = ' INNER JOIN '.TABLE_PREFIX.'ad_retargeting_mapping rm ON a.id = rm.aid ';

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
					$retargetAdsExcludeCondition = ' AND a.id NOT IN ('.$mapstring.') ';
				}
			}
		}
		/***************************** Ad Retargeting ***************************/
		if($responsive == 1 && $pop == 0 && $directlink == 0)
		{
			$data_file = ROOT_DIR_PATH.CACHE_DIR."/configuration/responsive_adblock.php";

			$datacontent=file_get_contents($data_file);
			$datacontent=str_replace('<?php ','',$datacontent);
			$datacontent=str_replace(' ?>','',$datacontent);
			$datacontent_array=json_decode($datacontent,1);


			if(isset($datacontent_array[$block_id][$device_type]) && intval($datacontent_array[$block_id][$device_type]) > 0)
			{
				$responsiveAdBlockID 	= intval($datacontent_array[$block_id][$device_type]);
				if($responsiveAdBlockID > 0 && $responsiveAdBlockID != $block_id)
				$adblockSizeChanged  = 1;
			}
			else
			$responsive 	= 0;
		}

		$this->set_variable('adblockSizeChanged',$adblockSizeChanged);

		if($isp_enabled ==1 || $connection_enabled ==1)
		{
			try
			{
			    $db1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);

				$dbip = new DBIP_MySQLI($db1);
				$ipLookUpInfo = $dbip->Lookup($requestIP,TABLE_PREFIX.'dbip_lookup');
				$isp= $ipLookUpInfo->isp_name ;
				$connection=$ipLookUpInfo->connection_type ;
				$cacheDevice=$cacheDevice.$isp.$connection;

			} catch (DBIP_Exception $e) {
				//echo "error: {$e->getMessage()}\n";
			}
		}

		if($language_targeting_enabled == 1)
		{
			if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			{
				$http_accept=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
				$x = explode(",",$http_accept);
				foreach ($x as $val) {

					$language[] ="'". substr($val, 0, 2)."'";

				}
				$languageArray = array_unique($language);
			}
		}

		$cpdAdsCache = $adunitid.$display_site.$country.$cacheCity.$cacheDevice.'0'.$responsiveAdBlockID;
		$cpdAdsCache = $adunitid."-".md5($cpdAdsCache).".php";

		$retargetAdsCache = $adunitid.$display_site.$country.$cacheCity.$cacheDevice.'1'.$ipUserAgent.$retargetCache.$responsiveAdBlockID;
		$retargetAdsCache = $adunitid."-".md5($retargetAdsCache).".php";

		$normalAdsCache   = $adunitid.$display_site.$country.$cacheCity.$cacheDevice.'1'.$responsiveAdBlockID; // RON Ads
		$normalAdsCache   = $adunitid."-".md5($normalAdsCache).".php";

		$this->cacheFileName = $normalAdsCache;

		if($cpd_enabled == 1 && $pricingRequest == 3)
		{
			include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cpdAdsCache);

			if($cachedFlag == 1)
			die;
			else if($cachedFlag == 2)
			unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$cpdAdsCache);
		}

		if($retarget_display == 1)
		{
			include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$retargetAdsCache);

			if($cachedFlag == 1)
			die;
			else if($cachedFlag == 2)
			unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$retargetAdsCache);
		}

		if($retarget_display == 0)
		{
			include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);

			if($cachedFlag == 1)
			die;
			else if($cachedFlag == 2)
			unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);
		}

		$db= DAL::get_instance();

		if($pop == 1 || $native == 1 || $directlink == 1)
		$res=$db->execute_query("select au.*,au.id as auid,abr_type as border_type from ".TABLE_PREFIX."adunit au where au.id=?",array($adunitid));
		else
		{
			//Direct join responsive adblock with adcode. There is no ON condition
			if($responsive == 1)
			$res=$db->execute_query("select ab.*,au.*,au.id as auid,abr_type as border_type from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au  where ab.id=? and au.id=?",array($responsiveAdBlockID,$adunitid));
			else
			$res=$db->execute_query("select ab.*,au.*,ab.id as adblockid,au.id as auid,abr_type as border_type from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au  where ab.id=au.blockid and au.id=?",array($adunitid));
		}

		$adcode_count = $res->get_num_records();
		$result       = $res->fetch_assoc();

		if($pricingRequest != 3 && $responsive_enabled == 1 && $responsive == 1 && $directlink == 0 && $pop == 0 && $video == 0)
		{
			if(!isset($result['responsive_support']) || $result['responsive_support'] == 0)  //Early adcode is responsive, but publisher change responsive support settings
			{
				$res=$db->execute_query("select ab.*,au.*,au.id as auid,abr_type as border_type from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au  where ab.id=au.blockid and au.id=?",array($adunitid));

				$adcode_count = $res->get_num_records();
				$result       = $res->fetch_assoc();
			}
		}

		$display_type = intval($result['display_type']);
		$pid          = intval($result['pubid']);
		$adcodeType   = intval($result['adcode_type']);
		$adcodeTypeDB = $adcodeType;

		if($pid != $publisher)
		die;

		if($native == 1)
		$native = $result['native'];

		$this->set_variable("pid",$pid);
		$this->set_variable("native",$native);
		$this->set_variable("native_enabled",$native_enabled);

		$queryTimes	 = intval(Configuration::get_instance()->read('count_of_ads_query_per_request'));

		if($queryTimes == 0)
		$queryTimes  = 1;

		if($native_enabled ==0 && $native ==1)
		{
			echo $this->remove_iframe($adunitid,3);
			exit;
		}

		if($native_enabled==1 && $native == 1)
		{
			$nativeResponsiveSupport  = $result['responsive_support'];

			if($nativeResponsiveSupport == 1)
			$adcodeAllowedAds         = $result['native_ads_count'];
			else
			{
				$nativeRows        	  = $result['native_ads_rows_count'];
				$nativeColumns     	  = $result['native_ads_column_count'];

				$adcodeAllowedAds     = $nativeRows * $nativeColumns;
			}

			$nativeAdsAllowed        = $adcodeAllowedAds;
			$nativeCustomCode        = $result['custom_code'];  //Need to check in view
			$nativeImagePosition     = $result['nativeimg_position'];

			$ad_container_type	     = intval($result['adcode_type']);
		}

		$this->set_variable('nativeRows', $nativeRows);
		$this->set_variable('nativeColumns', $nativeColumns);
		$this->set_variable('nativeAdsAllowed', $nativeAdsAllowed);


		if($pricingRequest !=5 && $pricingRequest !=14 && $pricingRequest != $display_type)  
		exit;

		if($pop_enabled != 1 && $pop == 1)
		{
			echo $this->remove_iframe($adunitid, 19, 9);
			exit;
		}

		if($directlink_enabled != 1 && ($directlink == 1 || $adcodeType == 21))
		{
			echo $this->remove_iframe($adunitid, 20, $adcodeType);
			exit;
		}
		
		if($skin_enabled != 1 && $skin == 1)
		{
			echo $this->remove_iframe($adunitid,5);
			exit;
		}

		if($video_enabled != 1 && $video == 1)
		{
			echo $this->remove_iframe($adunitid,6);
			exit;
		}

		if($video_enabled ==1)
		$html5_player_support=intval(Configuration::get_instance()->read('html5_player_support'));

		if($video_enabled ==1 && $html5_player_support ==0 && $adcodeType ==13)
		{
			echo $this->remove_iframe($adunitid,7);
			exit;
		}

		if($video_enabled ==1 && $adcodeType ==13 && $result['video_type'] ==1)
		{
			echo $this->remove_iframe($adunitid,8);
			exit;
		}

		if($video_enabled ==1 && $adcodeType ==13)
		{
			$this->set_variable('player_width',$result['width']);
			$this->set_variable('player_height',$result['height']);

			$aspect_ratio_value = round($result['width']/$result['height'],3);


			if($aspect_ratio_value > 0)
			{
				$aspect_row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."aspect_ratio WHERE aspect_float_value=?",array($aspect_ratio_value));

				$aspect_count=$aspect_row->get_num_records();

				if($aspect_count >0)
				{
					$aspect_data=$aspect_row->fetch_assoc();

					$videoAspectRatioID = $aspect_data['id'];

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

			if($videoAspectRatioID >0)
			{
					$videoAspectRatioCondition        = " AND ac.aspect_ratio=".$videoAspectRatioID." ";
					$videoAspectRatioConditionDefault = " AND a.aspect_ratio=".$videoAspectRatioID." ";
			}
			else
			{
				echo $this->remove_iframe($adunitid,11);
				exit;
			}
		}

		if($html_enabled ==1 || $cpm_enabled ==1)
		$cpmHtmlEnabled=1;

		$adcodeDisplayType = 0;

		if($adcodeType != 9 && $adcodeType != 13 && $adcodeType != 21)
		{
			if($native == 0)
			$adcodeAllowedAds = 1;

			if($display_type !=3)
			{
				if((($cpc_enabled ==1 && $cpmHtmlEnabled ==1) || ($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpmHtmlEnabled ==1 && $cpa_enabled ==1)) && Configuration::get_instance()->read('ad_preference') ==0 && $pid >0)
				$display_type=4;
			}

			if($native == 0)
			{
				if($result['banner_type'] == 1)      //For Interstitial
				$adcodeDisplayType = 1;

				$ad_container_type = $result['ad_type'];
			}

			$adcodeType         = $ad_container_type;
		}
		else 
		$ad_container_type	= $adcodeType;
		
		if($inpagepush == 1 && ($inpage_push_enabled != 1 || $result['adcode_type'] != 19))
		{
			echo $this->remove_iframe($adunitid,21);
			exit;
		}
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

		if($text_ads_enabled ==0 && $ad_container_type ==1)
		{
			echo $this->remove_iframe($adunitid,12);
			exit(0);
		}

		if($text_ads_enabled == 0 && $textimage_enabled != 1 && $ad_container_type ==3)
		$ad_container_type=2;

		if($textimage_enabled ==0 && $ad_container_type == 11)
		{
			echo $this->remove_iframe($adunitid,13);
			exit(0);
		}

		if($adcode_count <=0 || $adcode_count =="")
		{
			echo $this->remove_iframe($adunitid,14);
			exit(0);
		}

		if(($interstitial_enabled == 0 && $interstitial == 1) || ($interstitial_enabled == 0 && $adcodeDisplayType == 1) || ($interstitial == 0 && $adcodeDisplayType == 1) || ($interstitial == 1 && $adcodeDisplayType == 0))
		{
			echo $this->remove_iframe($adunitid,4);
			exit;
		}


		$this->cpc_enabled_value=$cpc_enabled;
		$this->cpm_enabled_value=$cpm_enabled;
		$this->html_enabled_value=$html_enabled;
		$this->interstitial_enabled_value=$interstitial_enabled;
		$this->category_enabled_value=$category_targeting_enabled;
		$this->cpd_enabled_value=$cpd_enabled;
		$this->device_enabled_value=$device_targeting_enabled;
		$this->cpa_enabled_value=$cpa_enabled;
		$this->pop_enabled_value=$pop_enabled;
		$this->textimage_enabled_value=$textimage_enabled;
		$this->referral_enabled_value=$referral_enabled;
		$this->video_enabled_value=$video_enabled;

		$this->language_targeting_enabled_value=$language_targeting_enabled;
		$this->isp_targeting_enabled_value=$isp_enabled;
		$this->connectiontype_targeting_enabled_value=$connection_enabled;

		if($referral_enabled ==1)
		{
			$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
			$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
		}


		$this->geo_tracking_enabled=intval(Configuration::get_instance()->read('countrywise_data_tracking'));

		if($adcodeType !=9 && $adcodeType !=13 && $adcodeType !=21)
		{
			if($cpc_enabled ==1)
			$ppctrack=intval(Configuration::get_instance()->read('ppc_impression_tracking_interval'));

			if($cpa_enabled ==1)
			$cpatrack=intval(Configuration::get_instance()->read('cpa_impression_tracking_interval'));


			if($cpm_enabled ==1)
			$cpmtrack=intval(Configuration::get_instance()->read('cpm_impression_tracking_interval'));

			if($html_enabled ==1)
			$htmltrack=intval(Configuration::get_instance()->read('html_impression_tracking_interval'));

			if($cpd_enabled ==1)
			$sponsoredtrack=intval(Configuration::get_instance()->read('sponsored_impression_tracking_interval'));
		}
		else if($adcodeType ==9)
		{
			if($pop_enabled ==1)
			{
				$poptrack=intval(Configuration::get_instance()->read('pop_impression_tracking_interval'));

				if($poptrack ==0)
				$poptrack=1;
			}
		}
		else if($adcodeType ==13)
		{
			if($video_enabled ==1)
			$videotrack=intval(Configuration::get_instance()->read('html5_player_impression_tracking_interval'));
		}

		//Impression tracking interval should be less than or equal to automatic rotation interval
		if($automaticRotation == 1 && $automaticRotationInterval > 0 && ($native == 0 || ($native == 1 && $adcodeAllowedAds == 1)) && ($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 3 || $adcodeType == 5 || $adcodeType == 11 || $adcodeType == 14) && ($display_type == 0 || $display_type == 1 || $display_type == 3 || $display_type == 4 || $display_type == 6))
		{
			if($cpc_enabled == 1 && $ppctrack >= $automaticRotationInterval)
			$ppctrack 		 = $automaticRotationInterval;

			if($cpm_enabled == 1 && $cpmtrack >= $automaticRotationInterval)
			$cpmtrack   	 = $automaticRotationInterval;

			if($cpa_enabled == 1 && $cpatrack >= $automaticRotationInterval)
			$cpatrack   	 = $automaticRotationInterval;

			if($cpd_enabled == 1 && $sponsoredtrack >= $automaticRotationInterval)
			$sponsoredtrack  = $automaticRotationInterval;
		}

		

		$this->pop_tracking_interval   = $poptrack;
		$this->ppc_tracking_interval   = $ppctrack;
		$this->cpa_tracking_interval   = $cpatrack;
		$this->cpm_tracking_interval   = $cpmtrack;
		$this->html_tracking_interval  = $htmltrack;
		$this->cpd_tracking_interval   = $sponsoredtrack;
		$this->video_tracking_interval = $videotrack;

		if($category_targeting_enabled ==1)
		{
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

			if($category_enabled_ads !='')
			$categorySupportedPricing = explode('_',$category_enabled_ads);
		}

		$rotation_variable = Configuration::get_instance()->read('ad_rotation');


		/*************** For Site Restriction **************/

		$siteCheckingPricing = $display_type;

		if($adcodeType == 9)
		$siteCheckingPricing = $adcodeType;
		
		$IABCategoryArray  = array();
		$IABCategoryString = "";

		if($category_targeting_enabled ==1 && $adcodeType != 21)
		{
			$sid = intval($result['sid']);

			if($sid >0)
			{
				  if($dspconnector_enabled == 1)
          			  $IABCategoryString = ",iab_category_id";
					$siterow=$db->execute_query("SELECT catid,url,protocol".$IABCategoryString." FROM ".TABLE_PREFIX."sites WHERE id=? AND status=1",array($sid));
					$sitedata=$siterow->fetch_assoc();

					$catid=$sitedata['catid'];
					$protocol=$sitedata['protocol'];
					$sitename=strtolower($sitedata['url']);

					if(isset($sitedata['iab_category_id']) && $sitedata['iab_category_id'] != "")
					$IABCategoryArray = explode(",", $sitedata['iab_category_id']);
					$adDisplaySite = $protocol.$sitename;



				if(in_array($siteCheckingPricing, $categorySupportedPricing))
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
								$invalidSiteFlag	= 1;

								//echo $this->remove_iframe($adunitid,15);
								//exit(0);
							}
							else
							$site_checked=1;
						}
					}
					/************ Modification For Support blogspot.com/blogspot.in etc ***********/


					if($display_site != $sitename && $site_checked ==0 && $invalidSiteFlag == 0)
					{
						$sitelength=strlen($sitename)+1;
						$displaysub=substr($display_site,-$sitelength);

						if($displaysub != '.'.$sitename)
						{
							$invalidSiteFlag	= 1;

							//echo $this->remove_iframe($adunitid,15);
							//exit(0);
						}
					}
				}
			}
		}
		/*************** For Site Restriction **************/

		/*************** For device Restriction **************/

		if($device_targeting_enabled ==1 && $display_type !=3)
		{
			$deviceCondition.=' AND ( ac.device = 2 ';

			if($deviceType == 'desktop')
			{
				if($deviceCondition != '')
				$deviceCondition.=' OR ';

				$deviceCondition.=' ac.device = 0  ';
			}

			if($deviceType == 'mobile')
			{
				if($deviceCondition != '')
				$deviceCondition.=' OR ';

				$deviceCondition.=' ac.device = 1 ';
			}

			$deviceCondition.=' ) ';


			if($os_targeting_enabled==1)
			{
				$osid = intval($db->read_single_column("select id from ".TABLE_PREFIX."os where name=?",array($osname)));

				$osCondition = ' AND (';

				if($osid > 0)
				$osCondition.=' ac.'.$osid.'_os = 1 OR ';

				$osCondition.=' ac.all_os = 1 )';
			}


			if($browser_targeting_enabled == 1)
			{
				$browserid = intval($db->read_single_column("select id from ".TABLE_PREFIX."browser where name=?",array($browsername)));

				$browserCondition.=' AND (';

				if($browserid > 0)
			    $browserCondition.=' ac.'.$browserid.'_browser = 1 OR ';

				$browserCondition.=' ac.all_browser = 1 )';
			}
		}
		/*************** For device Restriction **************/


		if($ad_container_type != 21 && $ad_container_type != 14 && $ad_container_type !=9 && $native ==0 && $responsive == 0)
		{
			if($ad_container_width != $result['width'] || $ad_container_height != $result['height'])
			{
				echo $this->remove_iframe($adunitid,16);
				exit(0);
			}
		}

		if($time_targeting_enabled ==1)
		{
		    $datetime  = time();

		    $date_filter_enabled = Configuration::get_instance()->read('date_filter_enabled');
		    $time_filter_enabled = Configuration::get_instance()->read('time_filter_enabled');
		    $day_filter_enabled  = Configuration::get_instance()->read('day_filter_enabled');


		    if($date_filter_enabled == 1 || $time_filter_enabled == 1 || $day_filter_enabled == 1)
		    {
		        $dateCondition.=' AND (';

		        if($date_filter_enabled == 1)
		        {
		            $dateCondition.=' (ac.date_filter =0 OR (ac.date_filter =1 AND ac.tmt_start_date <='.$datetime.' AND ac.tmt_end_date >='.$datetime.')) ';
		            $dateFlag = 1;
		        }

		        if($time_filter_enabled == 1)
		        {
		            $datehour=date('G',time());

		            if($dateFlag == 1)
		            $dateCondition.=' AND ';

		            $dateCondition.=' (ac.time_filter = 0 OR (ac.time_filter = 1 AND ac.'.$datehour.'_hour = 1)) ';

		            $dateFlag = 1;
		        }

		        if($day_filter_enabled == 1)
		        {
		            $dateday=date('w',time());

		            if($dateday == 0)
		            $dateday = 7;

		            if($dateFlag == 1)
		            $dateCondition.=' AND ';

		            $dateCondition.=' (ac.day_filter = 0 OR (ac.day_filter = 1 AND ac.'.$dateday.'_day = 1)) ';
		        }

		        $dateCondition.=')';
		    }
		}

		if($pid >0 && Configuration::get_instance()->read('allow_publishers_to_display_their_own_ads') ==0)
		{
		     $sameAccountAdDisplayCondition = " AND ac.uid <> ".$pid." ";
		}

		$directlinkCondition        = " AND ac.type = 21 ";
		$directlinkConditionDefault = " AND a.type = 21 ";
		if($pid >0)
		{
			$pquery_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));
			$query_result=$pquery_res->fetch_assoc();

			if($query_result['pub_status'] !=1)
			{
				echo $this->remove_iframe($adunitid,17,$display_type);
				exit(0);
			}

			if($directlink_enabled == 1 && $adcodeType == 21 && $query_result['directlink_availability'] == 0)
			{
				echo $this->remove_iframe($adunitid, 20, $adcodeType);
				exit(0);
			}

			if($cpm_enabled ==1)
			$this->set_variable('specific_profit',$query_result['cpm_profit_percentage']);

			if($video_enabled ==1)
			$this->set_variable('specific_profit_cpv',$query_result['cpv_profit_percentage']);

			if($referral_enabled ==1 && $pub_ref_enabled ==1 && $query_result['refferal_status']==1)
			$publisherRid = intval($query_result['rid']);

			$site_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array ($pid));

			if($site_json != '')
			$restrictedSiteArray = json_decode($site_json);

			foreach($restrictedSiteArray as $value)
			{
				if($value != "")
				$siteRestrictionCondition.= " AND a.click_url not like '%" . $db->sanitize($value) . "%' ";
			}
		}

		$this->set_variable('publisherRid',$publisherRid);

		$res->set_result_index(0);
		$this->set_result("adcodeResult",$res);

		$bannersize     = 0;
		$textimage_size = 0;

		if($adcodeType != 9 && $adcodeType != 21)
		{
			if($native == 1)
			$textimage_size = $result['nativeimg_dimension'];
			else
			{
				if($ad_container_type == 3) //Text/Banner/Text+Image
				{
					$bannersize=$result['bannersize'];
					$textimage_size=$result['textimage_size'];
				}
				else if($ad_container_type == 11)  //Text+Image
				$textimage_size=$result['textimage_size'];
				else if($ad_container_type == 14)  //Skin
				$bannersize=$result['adblockid'];
				else
				$bannersize=$result['bannersize'];
			}
		}

		if($textimage_enabled == 1 && $textimage_size == 0)
		{
			$textimage_enabled = 0;

			if($textimage_enabled == 0 && $ad_container_type == 11)
			{
					echo $this->remove_iframe($adunitid,13);
					exit(0);
			}
		}

		if($referral_enabled == 1 && $adv_ref_enabled == 1)
	  	$referralColumns = ",ac.refferal_id as rid,ac.refferal_status";

		if($ad_container_type !=9 && $ad_container_type !=13 && $ad_container_type !=14 && $ad_container_type !=21)
		{
			if($textimage_size > 0 && ($ad_container_type == 3 || $ad_container_type == 11))
			{
				$blockdata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($textimage_size));
				$blockdatarow=$blockdata->fetch_assoc();

				$textimageWidth   = $blockdatarow['width'];
				$textimageHeight  = $blockdatarow['height'];

				$this->set_variable('textimageWidth',$textimageWidth);
				$this->set_variable('textimageHeight',$textimageHeight);
			}

			if($bannersize > 0 && $ad_container_type != 11)
			{
				$bannerdata 			= $db->execute_query("SELECT * FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($bannersize));
				$bannerdatarow 			= $bannerdata->fetch_assoc();

				$bannerWidth   			= $bannerdatarow['width'];
				$bannerHeight  			= $bannerdatarow['height'];

				$imageSupport      		= $bannerdatarow['image_support'];
				$ecommerceSupport  		= $bannerdatarow['ecommerce_support'];

				$expandablesupport		= $bannerdatarow['expandable_support'];
				$expandable_width		= $bannerdatarow['expandable_width'];
				$expandable_height		= $bannerdatarow['expandable_height'];
			}

			if($ecommerceSupport == 0)
			$ecommerce_enabled = 0;
		}

		if($expandablesupport ==0)
		$expandable_enabled=0;

		if($expandable_enabled ==1)
		{
			$expandableColumns      = " ,ac.expandable,ac.cta_expandable_support,a.expandable_banner ";
	    	$expandableColumnsCPD   = " ,a.expandable,a.cta_expandable_support,a.expandable_banner ";
		}

		if($html5_enabled == 1)
		{
			$html5Columns        = " ,ac.cta_support,ac.html5";
			$html5ColumnsCPD     = " ,a.cta_support,a.html5";
			$html5DefaultColumns = " ,a.cta_support,a.html5";
		}

		$banner_list  = " ,a.banner_list ";

		if($video_enabled ==1 && $adcodeType == 13)
		{
			$cpvColumns        = ",a.mime_type,ac.duration_seconds";
			$cpvDefaultColumns = ",a.mime_type,a.duration_seconds";
		}

		$pop_up_support    = 0;
		$pop_tab_support   = 0;
		$pop_window_width  = 0;
		$pop_window_height = 0;

		if($pop_enabled ==1 && $adcodeType == 9)
		{
			$pop_ads_support   = Configuration::get_instance()->read('pop_ads_support');
			$pop_array         = explode('-',$pop_ads_support);

			$pop_up_support    = $result['pop_up_support'];
			$pop_tab_support   = $result['pop_tab_support'];
			$pop_window_width  = Configuration::get_instance()->read('pop_window_width');
			$pop_window_height = Configuration::get_instance()->read('pop_window_height');

			if(!($result['pop_up_support'] ==1 && $pop_array[0] ==1) && !($result['pop_tab_support'] ==1 && $pop_array[1] ==1))
			exit;
		}

		$countrywise_pricing_enabled = Configuration::get_instance()->read('countrywise_pricing_enabled');
		$countryFieldSelect          = "";


		if($countrywise_pricing_enabled == 1)
		$decoded_array = json_decode(Configuration::get_instance()->read('countrywise_minimum_price'),1);
		else
		$decoded_array = array();



		if($feedads_enabled == 1 && ($ad_container_type == 1 || $ad_container_type == 2 || $ad_container_type == 3 || $ad_container_type == 9 || $ad_container_type == 11 || $ad_container_type == 21))
		$feedColumns  = ",feed_url,ad_section_path,bid_tag,pixel_tag,ac.result_cacheable,ac.response_type";

		if($dspconnector_enabled == 1)
		$dspColumns  = ",a.dsp_endpoint, a.dsp_request_headers,a.dsp_auction_type, ac.dsp_rate_from";
		$tableColumnsAppend = $referralColumns.$expandableColumns.$html5Columns.$cpvColumns.$feedColumns.$banner_list.$dspColumns;

		if($html5_enabled == 1)
		$html5Condition = " AND (ac.html5 = 0 OR ac.html5 = 1) ";
		else
		$html5Condition = " AND ac.html5 = 0 ";

		if($rotation_variable ==1)
		{
			if(time() % 5 == 0)
			$rotation_variable = 0;
		}

		$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

		$premiumString = "";
		if($premium_ad_enabled == 1)
		$premiumString = " ac.premium_ad DESC,";

		if($rotation_variable ==1)
		{
		    if($countrywise_pricing_enabled == 1)
		    {
		   		$normalAdRotation = " ORDER BY ".$premiumString."g.price DESC,ac.default_rate DESC,random ASC ";
		   		$cpaAdRotation	  = " ORDER BY ".$premiumString."ac.tracking_last_checked DESC,g.price DESC,ac.default_rate DESC,random ASC";
		    }
		    else 
		    {
		   		$normalAdRotation = " ORDER BY ".$premiumString."ac.default_rate DESC,random ASC ";
		   		$cpaAdRotation	  = " ORDER BY ".$premiumString."ac.tracking_last_checked DESC,ac.default_rate DESC,random ASC";
		    }
		}
		else
		{
            $normalAdRotation  = " ORDER BY ".$premiumString."random ASC ";
            $cpaAdRotation     = " ORDER BY ".$premiumString."random ASC ";
		}

		$adStatusCondition            = " AND ac.status = 1 ";
		$userStatusCondition          = " AND ac.user_status = 1 ";
		$adPauseStatusCondition       = " AND ac.pause_status = 0 ";


		$defaultAdStatusCheck         = " AND a.status = 1 ";
		$defaultAdPauseStatusCheck    = " AND a.pause_status = 0 ";


		$CPCBudgetCondition 	= "";
		$CPMBudgetCondition 	= "";
		$CPABudgetCondition 	= "";
		$CPVBudgetCondition 	= "";
		$POPBudgetCondition 	= "";
		$HTMLBudgetCondition 	= "";

		$CPCCountryPrice  = 0;
		$CPMCountryPrice  = 0;
		$CPACountryPrice  = 0;
		$CPVCountryPrice  = 0;
		$POPCountryPrice  = 0;
		$HTMLCountryPrice = 0;


		if($countrywise_pricing_enabled == 1)
		{
				if($country != "")
				{
						if(isset($decoded_array[$country]['cpc']) && $decoded_array[$country]['cpc'] > 0)
						$CPCCountryPrice = $decoded_array[$country]['cpc'];

						if(isset($decoded_array[$country]['cpm']) && $decoded_array[$country]['cpm'] > 0)
						$CPMCountryPrice = $decoded_array[$country]['cpm'];

						if(isset($decoded_array[$country]['cpa']) && $decoded_array[$country]['cpa'] > 0)
						$CPACountryPrice = $decoded_array[$country]['cpa'];

						if(isset($decoded_array[$country]['cpv']) && $decoded_array[$country]['cpv'] > 0)
						$CPVCountryPrice = $decoded_array[$country]['cpv'];

						if(isset($decoded_array[$country]['pop']) && $decoded_array[$country]['pop'] > 0)
						$POPCountryPrice = $decoded_array[$country]['pop'];

						if(isset($decoded_array[$country]['html']) && $decoded_array[$country]['html'] > 0)
						$HTMLCountryPrice = $decoded_array[$country]['html'];
				}



				if($CPCCountryPrice > 0)
				{
						$CPCBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= g.price)) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + g.price)
						))

						OR
						(g.price = 0 AND (".$CPCCountryPrice." >= ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ".$CPCCountryPrice.")) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ".$CPCCountryPrice.")
						))

						OR
						(g.price = 0 AND (".$CPCCountryPrice." < ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ac.default_rate)) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate)
						))
						) ";
				}
				else
				{
						$CPCBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= g.price)) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + g.price)
						))
						OR
						(g.price = 0 AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ac.default_rate)) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate)
						))
						) ";
				}


				if($CPVCountryPrice > 0)
				{
						$CPVBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= g.price)) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + g.price)
						))

						OR
						(g.price = 0 AND (".$CPVCountryPrice." >= ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ".$CPVCountryPrice.")) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ".$CPVCountryPrice.")
						))

						OR
						(g.price = 0 AND (".$CPVCountryPrice." < ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ac.default_rate)) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate)
						))
						) ";
				}
				else
				{
						$CPVBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= g.price)) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + g.price)
						))
						OR
						(g.price = 0 AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ac.default_rate)) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate)
						))
						) ";
				}


				if($CPMCountryPrice > 0)
				{
						$CPMBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= (g.price / 1000))) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + (g.price / 1000))
						))

						OR
						(g.price = 0 AND (".$CPMCountryPrice." >= ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (".$CPMCountryPrice." / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (".$CPMCountryPrice." / 1000))
						))

						OR
						(g.price = 0 AND (".$CPMCountryPrice." < ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (ac.default_rate / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (ac.default_rate / 1000))
						))
						) ";
				}
				else
				{
						$CPMBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= (g.price / 1000))) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + (g.price / 1000))
						))
						OR
						(g.price = 0 AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (ac.default_rate / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (ac.default_rate / 1000))
						))
						) ";
				}


				if($POPCountryPrice > 0)
				{
						$POPBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= (g.price / 1000))) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + (g.price / 1000))
						))

						OR
						(g.price = 0 AND (".$POPCountryPrice." >= ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (".$POPCountryPrice." / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (".$POPCountryPrice." / 1000))
						))

						OR
						(g.price = 0 AND (".$POPCountryPrice." < ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (ac.default_rate / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (ac.default_rate / 1000))
						))
						) ";
				}
				else
				{
						$POPBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= (g.price / 1000))) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + (g.price / 1000))
						))
						OR
						(g.price = 0 AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (ac.default_rate / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (ac.default_rate / 1000))
						))
						) ";
				}


				if($HTMLCountryPrice > 0)
				{
						$HTMLBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= (g.price / 1000))) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + (g.price / 1000))
						))

						OR
						(g.price = 0 AND (".$HTMLCountryPrice." >= ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (".$HTMLCountryPrice." / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (".$HTMLCountryPrice." / 1000))
						))

						OR
						(g.price = 0 AND (".$HTMLCountryPrice." < ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (ac.default_rate / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (ac.default_rate / 1000))
						))
						) ";
				}
				else
				{
						$HTMLBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= (g.price / 1000))) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + (g.price / 1000))
						))
						OR
						(g.price = 0 AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= (ac.default_rate / 1000))) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + (ac.default_rate / 1000))
						))
						) ";
				}


				if($CPACountryPrice > 0)
				{
						$CPABudgetCondition = " AND ((g.price > 0 AND (ac.total_ad_budget >= ac.total_budget_used + g.price))
						OR
						(g.price = 0 AND (".$CPACountryPrice." >= ac.default_rate) AND (ac.total_ad_budget >= ac.total_budget_used + ".$CPACountryPrice."))
						OR
						(g.price = 0 AND (".$CPACountryPrice." < ac.default_rate) AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate))
						) ";
				}
				else
				{
						$CPABudgetCondition = " AND ((g.price > 0 AND (ac.total_ad_budget >= ac.total_budget_used + g.price))
						OR
						(g.price = 0 AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate))
						) ";
				}
		}



		$budgetCondition      = " AND ac.budget_available = 1 ";
		$dailyBudgetCondition = " AND ac.daily_budget_available = 1 ";

		/********************************** Keyword Query **********************************/

		$keyword_based_display = Configuration::get_instance()->read('keyword_based_ad_display');

		if($display_type !=3 && $adcodeType != 21 && $keyword_based_display ==1)
		{
			$siteKeywords  		= explode(",",$siteKeywords);
			$siteTitle  		= explode(" ",$siteTitle);
			$siteDescription    = explode(" ",$siteDescription);
			$mergeArray 		= array_merge ($siteKeywords, $siteTitle,$siteDescription);
			$keyword 			= array_unique($mergeArray);


			$keywordCondition = " (";

			foreach($keyword as $k=>$v)
			{
				if(trim($v) !="")
				{
					$key=intval($db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=? and status=1",array(trim($v))));

					if($key >0)
					{
						if($keywordCondition !=" (")
						$keywordCondition.=" OR ";

						$keywordCondition.="km.kid = ".$key." ";
					}
				}
			}

			if($keywordCondition !=" (")
			$keywordCondition.=" OR ";

			$keywordCondition.="km.kid = 0 ";
			$keywordCondition.=" ) ";


			$keywordJoin = " INNER JOIN ".TABLE_PREFIX."ad_keyword_mapping k ON k.id = (SELECT km.id FROM ".TABLE_PREFIX."ad_keyword_mapping km WHERE km.aid = a.id AND ".$keywordCondition." LIMIT 0,1) ";


			$keywordColumns = " ,k.kid as keyid,COALESCE(k.id,0) as kid ";
		}
		else
		$keywordColumns  		 = " ,0 as keyid,0 as kid ";

		$keywordColumnsRetarget  = " ,0 as keyid,0 as kid ";
		/********************************** Keyword Query **********************************/

		/********************************** Geographic Query *******************************/

		$countryFieldSelect   = " ,ac.all_countries ";

		if($city_enabled ==1)
		{
			$countryJoin = TABLE_PREFIX."ad_geographic_mapping g,";

			$countryJoinCondition = " a.id = g.aid ";

			$countryFieldSelect.= " ,g.price ";

			$countryCondition =" AND (";

			$countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='".$subdivision2."') AND (g.city=".$cityid.")) OR ";

			$countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='".$subdivision2."') AND (g.city=0)) OR ";

			$countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='0') AND (g.city=".$cityid.")) OR ";

			$countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='0') AND (g.city=0)) OR ";

			$countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=".$cityid.")) OR ";

			$countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=0)) OR ";

			$countryCondition.=" ((g.country_code='0') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=0)) ";

			$countryCondition.=" )";

		}
		else
		{
			if($countrywise_pricing_enabled == 1)
			{
				$countryJoin = TABLE_PREFIX."ad_geographic_mapping g,";

				$countryJoinCondition = " a.id = g.aid ";

				$countryFieldSelect.= " ,g.price ";


				$countryCondition=" AND (";

				if($country != "")
				$countryCondition.=" (g.country_code='".$country."') OR ";

				$countryCondition.=" (g.country_code = '0')) ";
			}
			else
			{
				$countryCondition=" ( ";

				if($country != "")
				$countryCondition.=" ac.".$country."_country = 1 OR ";
				
				$countryCondition.=" ac.all_countries = 1 ) ";
			}
		}
		/********************************** Geographic Query *******************************/

		/********************************** Category Targeting ********************************/
		if($catid != "" && $catid != 0 && in_array($siteCheckingPricing, $categorySupportedPricing) && $adcodeType != 21)
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

			$categoryCondition = ' AND ( ';

			if($category_string != '')
			{
					$categoryArray = explode(",",$category_string);

					foreach($categoryArray as $key => $value)
					{
						$categoryCondition.=' ac.'.intval($value).'_category = 1 OR ';
					}
			}

			$categoryCondition.=' ac.all_categories = 1 )';
		}
		/********************************** Category Targeting ********************************/

		/****************************** ISP & Connection Type Targeting ********************************/
		if($isp_enabled ==1 || $connection_enabled ==1)
		{
			if($isp !='' && $isp_enabled ==1)
		   	$ispid=intval($db->read_single_column("select id from ".TABLE_PREFIX."isp where name=? and country=?",array($isp,$country)));

			if($connection !='' && $connection_enabled ==1)
			$conn_id=intval($db->read_single_column("select id from ".TABLE_PREFIX."connection where name=? ",array($connection)));

			if($isp_enabled == 1)
			{
				$ispJoin = ' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_isp_mapping im ON a.id = im.aid ';

				$ispCondition.=' AND (';

				if($ispid > 0)
				$ispCondition.=' ( im.ispid ='.$ispid.' OR im.ispid IS NULL OR im.ispid=0) ';
				else
				$ispCondition.=' ( im.ispid IS NULL OR  im.ispid=0)';

			    $ispCondition.=' )';
			}

			if($connection_enabled == 1)
			{
				$connectionCondition.=' AND (';

		 		if($conn_id > 0)
				$connectionCondition.='ac.'.$conn_id.'_connection = 1 OR';

				$connectionCondition.=' ac.all_connections = 1';

				$connectionCondition.=' )';
			}
		}
		/********************************** ISP & Connection Type Targeting  ********************************/

		
		/********************************** Language Targeting **********************************************/
		if($language_targeting_enabled==1)
		{
			if(count($languageArray)>0)
			{
		  		$languageValueString = '';

				foreach($languageArray as $lkey => $lvalue)
				{
					$languageArray[$lkey] = str_replace("'","", $lvalue);

				if($languageValueString !='')
					$languageValueString.=',';

					$languageValueString.='?';
				}

				$language_data=$db->execute_query("select id from ".TABLE_PREFIX."language where code in (".$languageValueString.")",$languageArray);

				if($language_data->get_num_records()>0)
				{
					$languageCondition.= ' AND ( ';

					while ($brdata=$language_data->fetch_assoc())
					{
						$languageCondition.= ' ac.'.intval($brdata['id']).'_language = 1 OR ';
					}
					$languageCondition.='ac.all_languages = 1 )';

				}
			}
		}
		/**********************************Language Targeting *********************************/

		if($ecommerce_enabled ==1 || $imageSupport ==1)
		$bannerAdsSupport = 1;

		if($ecommerce_enabled ==1 && $imageSupport ==1)
		{
			$bannerCondition    = ' AND (ac.type =2 OR (ac.type =7 AND ac.ecommerce_parent <>0)) ';
			$cpdBannerCondition = ' AND (a.type =2 OR (a.type =7 AND a.ecommerce_parent <>0)) ';
		}
		else if($ecommerce_enabled ==1)
		{
			$bannerCondition    = ' AND ac.type =7 AND ac.ecommerce_parent <>0 ';
			$cpdBannerCondition = ' AND a.type =7 AND a.ecommerce_parent <>0 ';
		}
		else if($imageSupport ==1)
		{
			$bannerCondition    = ' AND ac.type =2 ';
			$cpdBannerCondition = ' AND a.type =2 ';
		}

		//For development only
		if(DEMO_MODE)
		$invalidSiteFlag = 0; //jesus

		if($cpd_enabled ==1 && $display_type ==3 && $invalidSiteFlag == 0)
		{
			$ad_container_type = $result['ad_type'];

			//INNER JOIN ".TABLE_PREFIX."ads_cache ac ON ac.id = a.id

			$cpdAdQueryString = "SELECT m.id as currentmapid,m.day_cpd_rate_total,m.day_cpd_rate_publisher,a.id as aid,a.click_url,a.banner,a.title, a.description,a.cta_button_text,a.display_url,a.uid as userid,a.display_type,a.type,0 AS retargetid,0 AS retarget,0 as rid,a.banner_list
			".$expandableColumnsCPD.$html5ColumnsCPD.",rand() as random
			FROM
			".TABLE_PREFIX."ads a
			INNER JOIN ".TABLE_PREFIX."sponsored_ad_mapping m
			ON a.id = m.aid
			WHERE
			a.display_type = 3
			AND a.status = 1
			AND a.user_status = 1
			AND a.pause_status = 0
			AND publisher=".$pid."
			AND position=".$adunitid."
			AND site=".$sid."
			AND m.status=2
			AND start_time <=".time()."
			AND end_time >=".time()."
			{COMMON-CONDITION}
			ORDER BY random ASC
			LIMIT 0,{LIMIT-CONDITION}";
		}


		$adQueryString = "SELECT 0 AS retargetid,0 as retarget,ac.id as aid,ac.default_rate,
			ac.uid as userid, a.title,a.description,a.cta_button_text,a.display_url,a.click_url, a.banner,ac.display_type,ac.type".$tableColumnsAppend.$keywordColumns.$countryFieldSelect.",rand() as random
			FROM ".$countryJoin." ".TABLE_PREFIX."ads a
			".$keywordJoin."
			".$ispJoin."
			INNER JOIN ".TABLE_PREFIX."ads_cache ac ON ac.id = a.id
			WHERE
		      ".$countryJoinCondition."
		      ".$countryCondition."
		      ".$categoryCondition."
		      ".$deviceCondition."
		      ".$dateCondition."
		      ".$osCondition."
		      ".$browserCondition."
		      ".$languageCondition."
		      ".$ispCondition."
		      ".$connectionCondition."
		      AND ac.pricing_status = 1
		      {PRICING-CONDITION}
		      {COMMON-CONDITION}
		      {RETARGET-CONDITION}
			  {ADS-EXCLUDE}
		      ".$adStatusCondition."
			  ".$adPauseStatusCondition."
			  ".$userStatusCondition."
			  ".$sameAccountAdDisplayCondition."
			  ".$siteRestrictionCondition."
			  ".$budgetCondition."
			  {DAILY-BUDGET-CONDITION}
			  {AD-ROTATION-CONDITION}
			  LIMIT 0,{LIMIT-CONDITION}";

		$retargetAdQueryString = "SELECT rm.rid AS retargetid,1 as retarget, ac.id as aid,ac.default_rate, ac.uid as userid, a.title, a.description,a.cta_button_text, a.display_url, a.click_url, a.banner,ac.display_type,ac.type ".$tableColumnsAppend.$keywordColumnsRetarget.$countryFieldSelect.",rand() as random
			FROM ".$countryJoin." ".TABLE_PREFIX."ads a
			".$retargetJoin."
			INNER JOIN ".TABLE_PREFIX."ads_cache ac ON ac.id = a.id
			WHERE
			      ".$countryJoinCondition."
			      ".$countryCondition."
				  AND ac.pricing_status = 1
				  {PRICING-CONDITION}
				  {COMMON-CONDITION}
				  {ADS-EXCLUDE}
			      ".$adStatusCondition."
				  ".$adPauseStatusCondition."
				  ".$userStatusCondition."
				  ".$sameAccountAdDisplayCondition."
				  ".$siteRestrictionCondition."
				  ".$retargetAdsIncludeCondition."
				  ".$retargetAdsExcludeCondition."
				  ".$budgetCondition."
				  {DAILY-BUDGET-CONDITION}
				  {AD-ROTATION-CONDITION}
				  LIMIT 0,{LIMIT-CONDITION}";

		$defaultAdQueryString = "SELECT a.id as aid, a.title, a.description,a.cta_button_text, a.display_url, a.click_url,a.banner,a.type,a.display_type".$cpvDefaultColumns.$html5DefaultColumns.",rand() as random
				FROM ".TABLE_PREFIX."ads a
				WHERE a.uid = 0
				".$defaultAdStatusCheck."
				".$defaultAdPauseStatusCheck."
				{PRICING-CONDITION}
      			{COMMON-CONDITION}
				ORDER BY random ASC
				LIMIT 0,{LIMIT-CONDITION}";

		$currentDisplayTime  	  			= time();
		$default_ad_display_random 			= intval(Configuration::get_instance()->read('default_ad_display_random'));
		$default_ad_display_gap	   			= intval(Configuration::get_instance()->read('default_ad_display_gap'));
		$default_ad_existing_recheck 	    = intval(Configuration::get_instance()->read('default_ad_existing_recheck'));

		if($default_ad_display_random == 1)
		{
			$defaultAdLastDisplay	  		= intval($result['default_ad_display_time']);
			$default_ad_display_interval    = 60 * $default_ad_display_gap;

			if(($currentDisplayTime - $defaultAdLastDisplay) < $default_ad_display_interval)
			$default_ad_display_random = 0;
		}

		$delayTime			 	   = 60 * $default_ad_existing_recheck;

		if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
		{
			if(($currentDisplayTime - $result['default_text_ads']) > $delayTime)
			{
				$queryDefaultReplacementArray["pricing-condition"] = 0;
				$queryDefaultReplacementArray["common-condition"]  = " AND a.type =1 ";
				$queryDefaultReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

				$displayArray['default'][1][0] 					   = $queryDefaultReplacementArray;
			}
		}

		if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerAdsSupport == 1 && $inpagepush == 0)
		{
			if(($currentDisplayTime - $result['default_banner_ads']) > $delayTime)
			{
				$queryDefaultReplacementArray["pricing-condition"] = 0;
				$queryDefaultReplacementArray["common-condition"]  = " AND a.type =2 AND a.banner_id =".$bannersize;
				$queryDefaultReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

				$displayArray['default'][2][0] 					   = $queryDefaultReplacementArray;
			}
		}

		if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1)
		{
			if(($currentDisplayTime - $result['default_textimage_ads']) > $delayTime)
			{
				$queryDefaultReplacementArray["pricing-condition"] = 0;
				$queryDefaultReplacementArray["common-condition"]  = " AND a.type =11 AND a.banner_id =".$textimage_size;
				$queryDefaultReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

				$displayArray['default'][11][0] 				   = $queryDefaultReplacementArray;
			}
		}



		if($ad_container_type == 14)
		{
			if(($currentDisplayTime - $result['default_skin_ads']) > $delayTime)
			{
				$queryDefaultReplacementArray["pricing-condition"] = 0;
				$queryDefaultReplacementArray["common-condition"]  = " AND a.type =14 AND a.banner_id =".$bannersize;
				$queryDefaultReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

				$displayArray['default'][14][0] 				   = $queryDefaultReplacementArray;
			}
		}

		if($ad_container_type == 21)//Directlink
		{
			$queryDefaultReplacementArray["pricing-condition"] = 0;
			$queryDefaultReplacementArray["common-condition"]  = $directlinkConditionDefault;
			$queryDefaultReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

			$displayArray['default'][21][0] 				   = $queryDefaultReplacementArray;
		}


		if($cpc_enabled == 1 && ($display_type == 0 || $display_type == 4))
		{
			$queryReplacementArray["pricing-condition"]    = 0;
			$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
			$queryReplacementArray["adrotation-condition"] = $normalAdRotation;

			$queryRetargetReplacementArray["pricing-condition"]    = 0;
			$queryRetargetReplacementArray["budget-condition"]     = $dailyBudgetCondition;
			$queryRetargetReplacementArray["adrotation-condition"] = $normalAdRotation;

			if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =1 ";
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][1][0] 					 = $queryReplacementArray;

				$queryRetargetReplacementArray["common-condition"]   = " AND ac.type =1 AND ac.retargeting =1 ";
				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][1][2] 					 = $queryRetargetReplacementArray;

			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerAdsSupport == 1 && $inpagepush == 0)
			{
				$queryReplacementArray["common-condition"]   = $bannerCondition." AND ac.banner_id =".$bannersize.$html5Condition;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][2][0] 					 = $queryReplacementArray;



				$queryRetargetReplacementArray["common-condition"]   = $bannerCondition." AND ac.retargeting =1 AND ac.banner_id =".$bannersize;

				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][2][2] 					 = $queryRetargetReplacementArray;

			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =11 AND ac.banner_id =".$textimage_size;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][11][0] 					 = $queryReplacementArray;



				$queryRetargetReplacementArray["common-condition"]   = " AND ac.type =11 AND ac.retargeting =1 AND ac.banner_id =".$textimage_size;

				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][11][2] 					 = $queryRetargetReplacementArray;





			}

			if($ad_container_type == 14)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =14 AND ac.banner_id =".$bannersize;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][14][0]					 = $queryReplacementArray;

				$queryRetargetReplacementArray["common-condition"]   = " AND ac.type =14 AND ac.retargeting =1 AND ac.banner_id =".$bannersize;
				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][14][2] 					 = $queryRetargetReplacementArray;
			}

			if(($ad_container_type == 1 || $ad_container_type == 3) && $feedads_enabled == 1 && $text_ads_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_text = 1 ";
				$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

				$displayArray['ppc'][1][3]					 = $queryReplacementArray;
			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $feedads_enabled == 1 && $bannerAdsSupport == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_banner = 1 ";
				$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

				$displayArray['ppc'][2][3]					 = $queryReplacementArray;
			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $feedads_enabled == 1 && $textimage_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_textimage = 1 ";
				$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

				$displayArray['ppc'][11][3]					 = $queryReplacementArray;
			}

			if($ad_container_type == 21)//Directlink
			{
				$queryReplacementArray["common-condition"]   = $directlinkCondition;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['ppc'][21][0]					 = $queryReplacementArray;

				if($feedads_enabled == 1)
				{
					$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_directlink = 1 ";
					$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

					$displayArray['ppc'][21][3]					 = $queryReplacementArray;
				}
			}
		}

		if($cpm_enabled == 1 && ($display_type == 1 || $display_type == 4) && $adcodeType != 9 && $adcodeType != 13)
		{
			$queryReplacementArray["pricing-condition"]    = 1;
			$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
			$queryReplacementArray["adrotation-condition"] = $normalAdRotation;

			if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =1 ";
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpm'][1][0] 					 = $queryReplacementArray;
			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerAdsSupport == 1 && $inpagepush == 0)
			{
				$queryReplacementArray["common-condition"]   = $bannerCondition." AND ac.banner_id =".$bannersize.$html5Condition;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpm'][2][0] 					 = $queryReplacementArray;
			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =11 AND ac.banner_id =".$textimage_size;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpm'][11][0] 				 = $queryReplacementArray;


			}

			if($ad_container_type == 14)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =14 AND ac.banner_id =".$bannersize;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpm'][14][0] 				 = $queryReplacementArray;
			}

			if(($ad_container_type == 1 || $ad_container_type == 3) && $feedads_enabled == 1 && $text_ads_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_text = 1 ";
				$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

				$displayArray['cpm'][1][3] 					 = $queryReplacementArray;
			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $feedads_enabled == 1 && $bannerAdsSupport == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_banner = 1 ";
				$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

				$displayArray['cpm'][2][3] 					 = $queryReplacementArray;
			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $feedads_enabled == 1 && $textimage_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_textimage = 1 ";
				$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

				$displayArray['cpm'][11][3]					 = $queryReplacementArray;
			}

			if($ad_container_type == 21)//Directlink
			{
				$queryReplacementArray["common-condition"]   = $directlinkCondition;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpm'][21][0]					 = $queryReplacementArray;

				if($feedads_enabled == 1)
				{
					$queryReplacementArray["common-condition"]   = " AND ac.type = 17 AND ac.feed_directlink = 1 ";
					$queryReplacementArray["limit-condition"]    = $feedQueryLimit;

					$displayArray['cpm'][21][3]					 = $queryReplacementArray;
				}
			}
		

			//For exchange
if(($ad_container_type == 1 || $ad_container_type == 3) && $dspconnector_enabled == 1 && $text_ads_enabled == 1)
{
	$queryReplacementArray["common-condition"]   = " AND ac.type = 20 AND ac.dsp_native = 1 ";
	$queryReplacementArray["limit-condition"]    = $exchangeQueryLimit;
	$displayArray['cpm'][1][4] 					         = $queryReplacementArray;
}
if(($ad_container_type == 2 || $ad_container_type == 3) && $dspconnector_enabled == 1 && $bannerAdsSupport == 1)
{
	$queryReplacementArray["common-condition"]   = " AND ac.type = 20 AND ac.dsp_banner = 1 ";
	$queryReplacementArray["limit-condition"]    = $exchangeQueryLimit;
	$displayArray['cpm'][2][4] 					         = $queryReplacementArray;
}
if(($ad_container_type == 11 || $ad_container_type == 3) && $dspconnector_enabled == 1 && $textimage_enabled == 1)
{
	$queryReplacementArray["common-condition"]   = " AND ac.type = 20 AND ac.dsp_native = 1 ";
	$queryReplacementArray["limit-condition"]    = $exchangeQueryLimit;
	$displayArray['cpm'][11][4]					         = $queryReplacementArray;
}
//For exchange
		}
		if($html_enabled ==1 && ($display_type ==1 || $display_type ==4) && $inpagepush == 0 && $adcodeType != 9)
		{
			$queryReplacementArray["pricing-condition"]    = 2;
			$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
			$queryReplacementArray["adrotation-condition"] = $normalAdRotation;


			if($ad_container_type == 2 || $ad_container_type == 3)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =2 AND ac.html_default = 0 AND ac.banner_id =".$bannersize;
				$queryReplacementArray["limit-condition"]    = 1;

				$displayArray['cpm'][2][1] 					 = $queryReplacementArray;


				if(($currentDisplayTime - $result['default_html_ads']) > $delayTime)
				{
					$queryDefaultReplacementArray["pricing-condition"] = 2;
					$queryDefaultReplacementArray["common-condition"]  = " AND a.type =2 AND a.html_default=1 AND a.banner_id =".$bannersize;
					$queryDefaultReplacementArray["limit-condition"]  = 1;

					$displayArray['default'][2][1] 				      = $queryDefaultReplacementArray;
				}
			}
		}

		if($cpa_enabled == 1 && ($display_type == 6 || $display_type == 4))
		{
			$queryReplacementArray["pricing-condition"]    = 6;
			$queryReplacementArray["budget-condition"]     = "";
			$queryReplacementArray["adrotation-condition"] = $cpaAdRotation;


			$queryRetargetReplacementArray["pricing-condition"]    = 6;
			$queryRetargetReplacementArray["budget-condition"]     = "";
			$queryRetargetReplacementArray["adrotation-condition"] = $cpaAdRotation;


			if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =1 ";
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][1][0] 					 = $queryReplacementArray;


				$queryRetargetReplacementArray["common-condition"]   = " AND ac.type =1 AND ac.retargeting =1 ";
				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][1][2] 					 = $queryRetargetReplacementArray;
			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerAdsSupport == 1 && $inpagepush == 0)
			{
				$queryReplacementArray["common-condition"]   = $bannerCondition." AND ac.banner_id =".$bannersize.$html5Condition;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][2][0] 					 = $queryReplacementArray;

				$queryRetargetReplacementArray["common-condition"]   = $bannerCondition." AND ac.retargeting =1 AND ac.banner_id =".$bannersize;
				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][2][2] 					 = $queryRetargetReplacementArray;
			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =11 AND ac.banner_id =".$textimage_size;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][11][0] 				 = $queryReplacementArray;

				$queryRetargetReplacementArray["common-condition"]   = " AND ac.type =11 AND ac.retargeting =1 AND ac.banner_id =".$textimage_size;
				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][11][2] 					 = $queryRetargetReplacementArray;
			}






			if($ad_container_type == 14)
			{
				$queryReplacementArray["common-condition"]   = " AND ac.type =14 AND ac.banner_id =".$bannersize;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][14][0] 				 = $queryReplacementArray;

				$queryRetargetReplacementArray["common-condition"]   = " AND ac.type =14 AND ac.retargeting =1 AND ac.banner_id =".$bannersize;
				$queryRetargetReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;
				$displayArray['cpa'][14][2] 					 = $queryRetargetReplacementArray;
			}

			if($ad_container_type == 21)//Directlink
			{
				$queryReplacementArray["common-condition"]   = $directlinkCondition;
				$queryReplacementArray["limit-condition"]    = $adcodeAllowedAds * $queryTimes;

				$displayArray['cpa'][21][0]					 = $queryReplacementArray;				
			}
		}

		if($cpd_enabled == 1 && $display_type == 3)
		{
			$queryReplacementArray["pricing-condition"]    = 3;
			$queryReplacementArray["budget-condition"]     = "";
			$queryReplacementArray["adrotation-condition"] = "";
			$queryReplacementArray["limit-condition"]      = $adcodeAllowedAds * $queryTimes;


			if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND a.type =1 ";
				$displayArray['cpd'][1][0] 				     = $queryReplacementArray;
			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerAdsSupport == 1 && $inpagepush == 0)
			{
				$queryReplacementArray["common-condition"]   = $cpdBannerCondition;
				$displayArray['cpd'][2][0] 				     = $queryReplacementArray;
			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1)
			{
				$queryReplacementArray["common-condition"]   = " AND a.type =11 ";
				$displayArray['cpd'][11][0] 				     = $queryReplacementArray;
			}


			if($ad_container_type == 14)
			{
				$queryReplacementArray["common-condition"]   = " AND a.type =14 ";
				$displayArray['cpd'][14][0] 				 = $queryReplacementArray;
			}
		}

		if($video_enabled == 1 && $adcodeType == 13)
		{
			$queryReplacementArray["pricing-condition"]    = 1;
			$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
			$queryReplacementArray["adrotation-condition"] = $normalAdRotation;

			$queryReplacementArray["common-condition"]  = " AND ac.type =13 AND (a.mime_type ='video/mp4' OR a.mime_type ='video/webm' OR a.mime_type ='video/ogg') ".$videoAspectRatioCondition;

			$queryReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

			$displayArray['cpv'][13][0] 				= $queryReplacementArray;

			if(($currentDisplayTime - $result['default_cpv_ads']) > $delayTime)
			{
				$queryDefaultReplacementArray["pricing-condition"] = 1;
				$queryDefaultReplacementArray["common-condition"]  = " AND a.type =13 AND (a.mime_type ='video/mp4' OR a.mime_type ='video/webm' OR a.mime_type ='video/ogg')	".$videoAspectRatioConditionDefault;
				$queryDefaultReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

				$displayArray['default'][13][1] 				   = $queryDefaultReplacementArray;
			}
		}

		if($pop_enabled == 1 && $adcodeType == 9)
		{
			$queryReplacementArray["pricing-condition"]    = 1;
			$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
			$queryReplacementArray["adrotation-condition"] = $normalAdRotation;
			$queryReplacementArray["common-condition"]     = " AND ac.type = 9 ";
			$queryReplacementArray["limit-condition"]      = $adcodeAllowedAds * $queryTimes;

			$displayArray['pop'][9][0] 				       = $queryReplacementArray;

			if($feedads_enabled == 1)
			{
				$queryReplacementArray["pricing-condition"]    = 1;
				$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
				$queryReplacementArray["adrotation-condition"] = $normalAdRotation;
				$queryReplacementArray["common-condition"]     = " AND ac.type = 17 AND ac.feed_pop = 1 ";
				$queryReplacementArray["limit-condition"]      = $feedQueryLimit;

				$displayArray['pop'][9][3] 				       = $queryReplacementArray;
			}

			//For exchange
			if($dspconnector_enabled == 1)
			{
				$queryReplacementArray["pricing-condition"]    = 1;
				$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
				$queryReplacementArray["adrotation-condition"] = $normalAdRotation;
				$queryReplacementArray["common-condition"]     = " AND ac.type = 20 AND ac.dsp_pop = 1 ";
				$queryReplacementArray["limit-condition"]      = $exchangeQueryLimit;
				$displayArray['pop'][9][4] 				             = $queryReplacementArray;
			}
			//For exchange
			if(($currentDisplayTime - $result['default_pop_ads']) > $delayTime)
			{
				$queryDefaultReplacementArray["pricing-condition"] = 1;
				$queryDefaultReplacementArray["common-condition"]  = " AND a.type = 9 ";
				$queryDefaultReplacementArray["limit-condition"]   = $adcodeAllowedAds * $queryTimes;

				$displayArray['default'][9][1] 				   = $queryDefaultReplacementArray;
			}
		}

		$sourcepriority_array = array();

		$sourcepriority		= Configuration::get_instance()->read('ad_source_priority');
		$ad_source_random	= Configuration::get_instance()->read('ad_source_random');

		$sourcepriority_array	= explode('_',$sourcepriority);
		$sourcepstring			= "";

		foreach($sourcepriority_array as $sourcepkey1=>$sourcepvalue1)
		{
			if(
				$sourcepvalue1 == 'system' || 
				($sourcepvalue1 == 'html' && $html_enabled == 1 && ($display_type == 1 || $display_type == 4)) || 
				($sourcepvalue1 == 'feed' && $feedads_enabled == 1 && ($display_type == 0 || $display_type == 1 || $display_type == 4)) || 
				($sourcepvalue1 == 'dsp-connector' && $dspconnector_enabled == 1 && ($display_type == 1 || $display_type == 4))
			)
			{
				if($sourcepstring != "")
				$sourcepstring.="_";

				$sourcepstring.=$sourcepvalue1;
			}
		}

		if($sourcepstring != "")
		$sourcepriority	= $sourcepstring;

		if($ad_source_random ==1)
		{
			$sourcepriority_array	= explode('_',$sourcepriority);
			$sourcecountlist 		= count($sourcepriority_array);

			if($sourcecountlist >1)
			{
				if($sourcecountlist ==2)
				{
				    $sourcecurrentrandom  = array(0,1);
				    shuffle($sourcecurrentrandom);

					if($sourcecurrentrandom[0] == 0)
					$sourcepriority=$sourcepriority_array[0].'_'.$sourcepriority_array[1];
					else
					$sourcepriority=$sourcepriority_array[1].'_'.$sourcepriority_array[0];
				}
				else if($sourcecountlist == 3)
				{
				    $sourcecurrentrandom  = array(0,1,2);
				    shuffle($sourcecurrentrandom);

					if($sourcecurrentrandom[0] ==0)
					$sourcepriority=$sourcepriority_array[0].'_'.$sourcepriority_array[2].'_'.$sourcepriority_array[1];
					else if($sourcecurrentrandom[0] ==1)
					$sourcepriority=$sourcepriority_array[1].'_'.$sourcepriority_array[0].'_'.$sourcepriority_array[2];
					else if($sourcecurrentrandom[0] ==2)
					$sourcepriority=$sourcepriority_array[2].'_'.$sourcepriority_array[1].'_'.$sourcepriority_array[0];
				}
				else if($sourcecountlist == 4)
				{
				    $sourcecurrentrandom  = array(0,1,2,3);
				    shuffle($sourcecurrentrandom);
						if($sourcecurrentrandom[0] == 0)
						$sourcepriority=$sourcepriority_array[0].'_'.$sourcepriority_array[3].'_'.$sourcepriority_array[1].'_'.$sourcepriority_array[2];
						else if($sourcecurrentrandom[0] == 1)
						$sourcepriority=$sourcepriority_array[1].'_'.$sourcepriority_array[0].'_'.$sourcepriority_array[2].'_'.$sourcepriority_array[3];
						else if($sourcecurrentrandom[0] == 2)
						$sourcepriority=$sourcepriority_array[2].'_'.$sourcepriority_array[1].'_'.$sourcepriority_array[3].'_'.$sourcepriority_array[0];
						else if($sourcecurrentrandom[0] == 3)
						$sourcepriority=$sourcepriority_array[3].'_'.$sourcepriority_array[2].'_'.$sourcepriority_array[0].'_'.$sourcepriority_array[1];
				}
			}
		}

		$sourcepriority_array=explode('_',$sourcepriority);

		$priority_array = array();

		if($display_type == 4)
		{
			$priority			= Configuration::get_instance()->read('ad_display_priority');
			$ad_display_random	= Configuration::get_instance()->read('ad_display_random');

			$priority_array		= explode('_',$priority);
			$pstring			= "";

			foreach($priority_array as $pkey1=>$pvalue1)
			{
				if(($cpc_enabled == 1 && $pvalue1 == 'ppc') || ($cpm_enabled ==1 && $pvalue1 == 'cpm') || ($cpa_enabled == 1 && $pvalue1 == 'cpa'))
				{
					if($pstring != "")
					$pstring.="_";

					$pstring.=$pvalue1;
				}
			}

			if($pstring != "")
			$priority	= $pstring;


			if($ad_display_random ==1)
			{
				$priority_array	= explode('_',$priority);
				$countlist 		= count($priority_array);

				if($countlist >1)
				{
					if($countlist ==2)
					{
					    $currentrandom  = array(0,1);
				        shuffle($currentrandom);

						if($currentrandom[0] == 0)
						$priority=$priority_array[0].'_'.$priority_array[1];
						else
						$priority=$priority_array[1].'_'.$priority_array[0];
					}
					else if($countlist == 3)
					{
					    $currentrandom  = array(0,1,2);
				        shuffle($currentrandom);

						if($currentrandom[0] ==0)
						$priority=$priority_array[0].'_'.$priority_array[2].'_'.$priority_array[1];
						else if($currentrandom[0] ==1)
						$priority=$priority_array[1].'_'.$priority_array[0].'_'.$priority_array[2];
						else if($currentrandom[0] ==2)
						$priority=$priority_array[2].'_'.$priority_array[1].'_'.$priority_array[0];
					}
				}
			}

			$priority_array=explode('_',$priority);
		}
		else if($cpc_enabled ==1 && $display_type == 0)
		{
			$priority			= 'ppc';
			$priority_array[] 	= 'ppc';
		}
		else if($cpm_enabled ==1 && $display_type == 1 && $adcodeType != 9 && $adcodeType != 13)
		{
			$priority			= 'cpm';
			$priority_array[] 	= 'cpm';
		}
		else if($pop_enabled ==1 && $display_type == 1 && $adcodeType == 9)
		{
			$priority			= 'pop';
			$priority_array[] 	= 'pop';
		}
		else if($video_enabled ==1 && $display_type == 1 && $adcodeType == 13)
		{
			$priority			= 'cpv';
			$priority_array[] 	= 'cpv';
		}
		else if($cpd_enabled ==1 && $display_type == 3)
		{
			$priority			= 'cpd';
			$priority_array[] 	= 'cpd';
		}
		else if($cpa_enabled ==1 && $display_type == 6)
		{
			$priority			= 'cpa';
			$priority_array[] 	= 'cpa';
		}		

		$sourceArray = array();
		foreach($sourcepriority_array as $skey => $svalue)
		{
			if($svalue == 'system')
			$sourceArray['system'] = $priority_array;

			if($svalue == 'html')
			$sourceArray['html']   = array('cpm');

			if($svalue == 'feed')
			{
				$priority_array_feed = $priority_array;

				foreach($priority_array_feed as $keyfeed => $keyvalue)
				{
					if($keyvalue == 'cpa')
					{
						unset($priority_array_feed[$keyfeed]);
						break;
					}
				}

				$sourceArray['feed'] = $priority_array_feed;
			}
			if($svalue == 'dsp-connector')
			{
				$priority_array_dspconnector = $priority_array;
				foreach($priority_array_dspconnector as $keydspconnector => $keyvalue)
				{
						if($keyvalue == 'ppc' || $keyvalue == 'cpa')
						{
								unset($priority_array_dspconnector[$keydspconnector]);
								break;
						}
				}
				$sourceArray['dsp-connector'] = $priority_array_dspconnector;
			}
		}

		if($ad_container_type == 3)
		{
			if($inpagepush == 0)
			{
			if($textimage_enabled == 1 && $text_ads_enabled == 1)
			{
				$currentrandom  = array(0,1,2);
				shuffle($currentrandom);

				if($currentrandom[0] == 1)
				{
					$containerArray[1]	= 1;


				    $currentrandom1  = array(0,1);
				    shuffle($currentrandom1);


					if($currentrandom1[0] == 1)
					{
						$containerArray[2]	= 2;
						$containerArray[11]	= 11;
					}
					else
					{
						$containerArray[11]	= 11;
						$containerArray[2]	= 2;
					}
				}
				else if($currentrandom[0] == 2)
				{
					$containerArray[2]	= 2;


				    $currentrandom1  = array(0,1);
				    shuffle($currentrandom1);

					if($currentrandom1[0] == 1)
					{
						$containerArray[1]	= 1;
						$containerArray[11]	= 11;
					}
					else
					{
						$containerArray[11]	= 11;
						$containerArray[1]	= 1;
					}
				}
				else
				{
					$containerArray[11]	= 11;


				    $currentrandom1  = array(0,1);
				    shuffle($currentrandom1);

					if($currentrandom1[0] == 1)
					{
						$containerArray[1]	= 1;
						$containerArray[2]	= 2;
					}
					else
					{
						$containerArray[2]	= 2;
						$containerArray[1]	= 1;
					}
				}
			}
			else if($textimage_enabled == 1)
			{
				$currentrandom  = array(0,1);
				shuffle($currentrandom);

				if($currentrandom[0] == 1)
				{
					$containerArray[11]	= 11;
					$containerArray[2]	= 2;
				}
				else
				{
					$containerArray[2]	= 2;
					$containerArray[11]	= 11;
				}
			}
			else
			{
				$currentrandom  = array(0,1);
				shuffle($currentrandom);

				if($currentrandom[0] == 1)
				{
					$containerArray[1]	= 1;
					$containerArray[2]	= 2;
				}
				else
				{
					$containerArray[2]	= 2;
					$containerArray[1]	= 1;
				}
			}
		}
			else if($inpagepush == 1)
			{
				if($textimage_enabled == 1 && $text_ads_enabled == 1)
				{
					$currentrandom  = array(0,1);
					shuffle($currentrandom);
					if($currentrandom[0] == 1)
					{
						$containerArray[1]	= 1;
						$containerArray[11]	= 11;
					}
					else
					{
						$containerArray[11]	= 11;
						$containerArray[1]	= 1;
					}
				}
				else if($textimage_enabled == 1)
				$containerArray[11]	= 11;
				else if($text_ads_enabled == 1)
				$containerArray[1]	= 1;
			}
		}
		else
		$containerArray[$ad_container_type]	= $ad_container_type;

		$pricing				= 0;
		$adget_flag				= 0;
		$html_get				= 0;
		$adsResultCount         = 0;
		$defaultAdsResultCount  = 0;
		$default_html_get		= 0;
		$originalpopad			= 0;
		$popaid					= 0;
		$stepTwoPass			= 0;
		$pop_ad_strings			= "";


		if($retarget_display == 1 && $adcodeType != 21 && ($display_type == 0 || $display_type == 4 || $display_type == 6) && $invalidSiteFlag == 0)
		{
			foreach($priority_array as $pkey=>$pvalue)
			{
				if($pvalue == 'ppc' || $pvalue =='cpa')
				{
					foreach($containerArray as $ckey => $cvalue)
					{
						if(isset($displayArray[$pvalue][$ckey][2]))
						{
							$retargetAdQueryStringReplace = $retargetAdQueryString;

							$pricingCondition     = $displayArray[$pvalue][$ckey][2]["pricing-condition"];

							$budgetCondition      = $displayArray[$pvalue][$ckey][2]["budget-condition"];

							$rotationCondition    = $displayArray[$pvalue][$ckey][2]["adrotation-condition"];

							$commonCondition      = $displayArray[$pvalue][$ckey][2]["common-condition"];

							$limitCondition       = $displayArray[$pvalue][$ckey][2]["limit-condition"];


							if(isset($excludeArray[$pvalue]))
							$retargetAdQueryStringReplace		= str_replace("{ADS-EXCLUDE}",$excludeArray[$pvalue],$retargetAdQueryStringReplace);
							else
							$retargetAdQueryStringReplace		= str_replace("{ADS-EXCLUDE}","",$retargetAdQueryStringReplace);




							if($pvalue == 'ppc')
							$retargetAdQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPCBudgetCondition,$retargetAdQueryStringReplace);
							else if($pvalue == 'cpa')
							$retargetAdQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPABudgetCondition,$retargetAdQueryStringReplace);






							$retargetAdQueryStringReplace		= str_replace("{COMMON-CONDITION}",$commonCondition,$retargetAdQueryStringReplace);

							$retargetAdQueryStringReplace		= str_replace("{DAILY-BUDGET-CONDITION}",$budgetCondition,$retargetAdQueryStringReplace);

							$retargetAdQueryStringReplace		= str_replace("{AD-ROTATION-CONDITION}",$rotationCondition,$retargetAdQueryStringReplace);

							$retargetAdQueryStringReplace		= str_replace("{LIMIT-CONDITION}",$limitCondition,$retargetAdQueryStringReplace);


							$resquery		   = $db->execute_query($retargetAdQueryStringReplace);
							$adsResultCount	   = $resquery->get_num_records();

							if($adsResultCount > 0)
							{
								$ad_container_type	= $ckey;

								$this->cacheFileName	= $retargetAdsCache;

								if($pvalue == 'ppc')
								{
									$pricing 		= 0;
									$display_type   = 0;
								}
								else if($pvalue == 'cpa')
								{
									$pricing 		= 6;
									$display_type	= 6;
								}

								$adget_flag = 1;
								break;
							}
						}
					}
				}

				if($adget_flag == 1)
				break;
			}
		}

		if($ad_container_type == 1 || $ad_container_type == 11)
		$totalAdCount	= $adcodeAllowedAds;
		else
		$totalAdCount	= 1;

		$this->displayAdCount = $totalAdCount;
		
		if($adsResultCount == 0 || $adsResultCount < $totalAdCount)
		{
			if($invalidSiteFlag == 1)
			$adDisplayStep[]	= 2;   //Default ad
			else
			{
				if($default_ad_display_random == 0 || $adsResultCount > 0)
				{
					$adDisplayStep[]	= 1;   //Advertiser/Admin ad
					$adDisplayStep[]	= 2;   //Default ad
				}
				else
				{
					$adDisplayStep[]	= 2;	//Default ad
					$adDisplayStep[]	= 1;	//Advertiser/Admin ad
				}
			}

			/************ Loop through advertiser/admin ads and default ads start ************/
			foreach($adDisplayStep as $stepKey => $stepValue)
			{				
				if($stepValue == 1) //Advertiser/Admin ad
				{			
					if($adsResultCount == 0) //No ads getting from retargeting ads fetch
					{
						if($display_type == 3)
						$this->cacheFileName	= $cpdAdsCache;
						else if($adcodeType != 9 && $adcodeType != 21)
						{
							if($retarget_display ==1)
							{
								include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);

								if($cachedFlag ==1)
								die;
								else if($cachedFlag ==2)
								unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);
							}
						}

						/************ Loop through different ad source start **********/
						foreach($sourceArray as $soKey => $soValue)
						{
							if($soKey == 'feed')
							$checkSourceKey = 3;
							else if($soKey == 'dsp-connector')
							$checkSourceKey = 4;
						    	else if($soKey == 'html')
							$checkSourceKey = 1;
						    	else
						    	$checkSourceKey = 0;
							
							/************ Loop through ad source based pricing start **********/
							foreach($soValue as $pkey=>$pvalue)
							{							
								/************ Loop through ad type (text/banner/text+banner/video etc) start **********/
								foreach($containerArray as $ckey => $cvalue)
								{			
									/************ Execution of different execute queries according to source/pricing/ad type etc start **********/
									if(isset($displayArray[$pvalue][$ckey][$checkSourceKey]))
									{			
										if($pvalue == 'cpd')
										$adQueryStringReplace = $cpdAdQueryString;
										else
										$adQueryStringReplace = $adQueryString;

										$pricingCondition     = $displayArray[$pvalue][$ckey][$checkSourceKey]["pricing-condition"];
										$budgetCondition      = $displayArray[$pvalue][$ckey][$checkSourceKey]["budget-condition"];
										$rotationCondition    = $displayArray[$pvalue][$ckey][$checkSourceKey]["adrotation-condition"];
										$commonCondition      = $displayArray[$pvalue][$ckey][$checkSourceKey]["common-condition"];
										$limitCondition       = $displayArray[$pvalue][$ckey][$checkSourceKey]["limit-condition"];

										if($soKey == 'html' && isset($excludeArray['html']))
										$adQueryStringReplace		= str_replace("{ADS-EXCLUDE}",$excludeArray['html'],$adQueryStringReplace);
										else if($soKey != 'dsp-connector' && isset($excludeArray[$pvalue]))
										$adQueryStringReplace		= str_replace("{ADS-EXCLUDE}",$excludeArray[$pvalue],$adQueryStringReplace);
										else
										$adQueryStringReplace		= str_replace("{ADS-EXCLUDE}","",$adQueryStringReplace);

										$adQueryStringReplace		= str_replace("{COMMON-CONDITION}",$commonCondition,$adQueryStringReplace);
										$adQueryStringReplace		= str_replace("{LIMIT-CONDITION}",$limitCondition,$adQueryStringReplace);
										
										if($pvalue != 'cpd')
										{
											$adQueryStringReplace		= str_replace("{RETARGET-CONDITION}","",$adQueryStringReplace);
											
											
											if(($checkSourceKey == 0 || $checkSourceKey == 3) && $pvalue == 'ppc')
											$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPCBudgetCondition,$adQueryStringReplace);
											else if($checkSourceKey == 0 && $pvalue == 'cpv')
											$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPVBudgetCondition,$adQueryStringReplace);
											else if($checkSourceKey == 0 && $pvalue == 'cpa')
											$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPABudgetCondition,$adQueryStringReplace);
											else if(($checkSourceKey == 0 || $checkSourceKey == 3) && $pvalue == 'cpm')
											$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPMBudgetCondition,$adQueryStringReplace);
											else if(($checkSourceKey == 0 || $checkSourceKey == 3) && $pvalue == 'pop')
											$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$POPBudgetCondition,$adQueryStringReplace);
											else if($checkSourceKey == 1) //HTML
											$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$HTMLBudgetCondition,$adQueryStringReplace);
											else
											$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition,$adQueryStringReplace);

								
											
											
											
											$adQueryStringReplace		= str_replace("{DAILY-BUDGET-CONDITION}",$budgetCondition,$adQueryStringReplace);
											$adQueryStringReplace		= str_replace("{AD-ROTATION-CONDITION}",$rotationCondition,$adQueryStringReplace);
										}

										$resquery		 = $db->execute_query($adQueryStringReplace);
										$adsResultCount	 = $resquery->get_num_records();
																					
					/************* Checking of ads getting for display start ***********/
										if($adsResultCount > 0)
										{
											$ad_container_type	= $ckey;

if($soKey != 'feed' && $soKey != 'dsp-connector' && ($pvalue == 'pop' || $adcodeType == 21)) //For POP / Directlink
											{						
												if($pvalue == 'pop')
												{
													$originalpopad	= 1;
													$adget_flag 	= 1;

	$pop_specific_profit = 100;
													if($pid > 0)
													{
														if($query_result['pop_profit_percentage'] >0)
														$pop_specific_profit=$query_result['pop_profit_percentage'];
														else
														$pop_specific_profit=Configuration::get_instance()->read('pop_profit_percentage');
													}

$this->adListArray = $this->get_processed_pop_ads_result(
								$pid, 
								$resquery, 
								$result['auid'],
								$pop_up_support,
								$pop_window_width,
								$pop_window_height,
								$originalpopad,
								$sid,
								$deviceType,
								$osname,
								$browsername,
								$catid,
								$this->geo_country,
								$pop_specific_profit, 																												
								$referral_enabled, 
								$adv_ref_enabled, 
								$countrywise_pricing_enabled,
								$publisherRid,
								$decoded_array
							);








													}
else if($adcodeType == 21) //Directlink
												{
													$originaldirectlinkad  = 1;
													$adget_flag 	       = 1;

	$cpm_specific_profit   = 100;

	if($display_type == 1 && $pid > 0)
														{

																if($query_result['cpm_profit_percentage'] >0)
																$cpm_specific_profit = $query_result['cpm_profit_percentage'];
																else
																$cpm_specific_profit = Configuration::get_instance()->read('cpm_profit_percentage');
															}

	$this->adListArray = $this->get_processed_directlink_ads_result(
									$pid, 
									$resquery, 																														
									$result['auid'],



									$originaldirectlinkad,
									$trackDomain,
									$indexAppend,
									$this->geo_country,
									$cpm_specific_profit, 																														
									$referral_enabled, 
									$adv_ref_enabled, 

									$countrywise_pricing_enabled, 																														
									$publisherRid,																														
									$display_type,																														
									$sid,
									$deviceType,
									$osname,
									$browsername,
									$catid,
									$decoded_array																														
														
									);	
														
														
												}

												break;
											}
											else
											{
												$feedGetSuccess = 0;

												if($soKey == 'html')
												{
												$html_get	= 1;
													$this->html_ad_get_flag = 1;
												}
												else if($soKey == 'feed')
												{
	/********* Loop through different feed ads getting from DB start ***********/
													while($addata = $resquery->fetch_assoc())
													{
														$userid    		  = 0;
														$rid    		  = 0;
														$retargetid 	  = 0;
														$retarget   	  = 0;

														$feedAid          = $addata['aid'];

														$titleTag         = $addata['title'];
														$descriptionTag   = $addata['description'];
														$displayUrlTag    = $addata['display_url'];
														$clickUrlTag      = $this->replace_macro($addata['click_url'],$addata['aid'],$result['auid'],$sid,$country,$deviceType,$osname,$browsername,$catid,time());
														$imageUrlTag      = $addata['banner'];
														$bidTag           = $addata['bid_tag'];
														$pixelTag         = $addata['pixel_tag'];

														$keyid            = $addata['keyid'];
														$kid              = $addata['kid'];
														$type    		  = $addata['type'];

														$feedUrl    	  = $addata['feed_url'];

														$default_rate 	   	 = $addata['default_rate'];

														$response_type       = $addata['response_type']; //0 => xml,1 =>json
														$ad_section_path     = $addata['ad_section_path']; //<result>,<ads>
														$result_cacheable    = $addata['result_cacheable'];

														$feedData = "";
		$uniqueID = md5($adunitid.'-'.uniqid().'-'.rand(0,100000));

														$feedUrl  = str_replace('{subid}', $feedAid.".".$adunitid, $feedUrl);
														
														
													
														
														
														$feedUrl  = str_replace('{source_id}', $adunitid, $feedUrl);
														$feedUrl  = str_replace('{user_ip}', $requestIP, $feedUrl);
														$feedUrl  = str_replace('{country}', $country, $feedUrl);
														
														$feedUrl  = str_replace('{ua}', urlencode(trim($requestUserAgent)), $feedUrl);
														
														
														
														
														
													
														
														
														$feedUrl  = str_replace('{url}', urlencode(trim($adDisplaySite)), $feedUrl);

														$feedUrl  = str_replace('{user_id}', $uniqueID, $feedUrl);
														$feedUrl  = str_replace('{bid_request_id}', $uniqueID, $feedUrl);


														if($language_targeting_enabled == 1 && isset($languageArray[0]))
															$feedUrl  = str_replace('{lang}', $languageArray[0], $feedUrl);
														else
															$feedUrl  = str_replace('{lang}', '', $feedUrl);


														if($ad_container_type == 1 || $ad_container_type == 11)
														$feedUrl  = str_replace('{count}', $adcodeAllowedAds, $feedUrl);
														else
														$feedUrl  = str_replace('{count}', 1, $feedUrl);


														if($ad_container_type == 2)
														{
																$feedUrl  = str_replace('{image_required}', 1, $feedUrl);
																$feedUrl  = str_replace('{image_size}', $bannerWidth.'x'.$bannerHeight, $feedUrl);


														}
														else if($ad_container_type == 11)
														{
																$feedUrl  = str_replace('{image_required}', 1, $feedUrl);
																$feedUrl  = str_replace('{image_size}', $textimageWidth.'x'.$textimageHeight, $feedUrl);
														}
														else
														{
																$feedUrl  = str_replace('{image_required}', 0, $feedUrl);
																$feedUrl  = str_replace('{image_size}', '', $feedUrl);
														}
														
														
														

														if(function_exists('curl_init'))
														{
															$ch = curl_init();
															curl_setopt($ch, CURLOPT_URL, $feedUrl);
															curl_setopt($ch, CURLOPT_HEADER, 0);
															curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
															//curl_setopt($ch, CURLOPT_USERAGENT, urlencode(trim($requestUserAgent)));
															$feedContent = curl_exec($ch);
															curl_close($ch);
															$feedData=$feedContent;
														}
														else if(ini_get('allow_url_fopen') == 1 && $feedContent = fopen($feedUrl,"r"))
														{
															while(!feof($feedContent))
															$feedData.=fgetc($feedContent);
															fclose($feedContent);
														}

														$responseGet   = 0;
														$responseArray = array();

														if($response_type == 0 && $feedData != "")
														{
															//$feedXML = simplexml_load_string($feedData);
															$feedXML = simplexml_load_string($feedData, null, LIBXML_NOCDATA);
															//For removing <![CDATA[]]>
															
															
															if($feedXML === false)
															{
																//No response from 3rd party
															}
															else
															{
																$responseGet   = 1;
																$responseArray = json_decode(json_encode($feedXML), true);
															}
														}
														else if($response_type == 1 && $feedData != "")	
														{
															$responseGet   = 1;
															$responseArray = json_decode($feedData, true);
														}


														if($responseGet == 1)
														{
															$feedAdArray  = array();
															$successFlag  = 0;

															if($ad_section_path != '')
															{
																$adSectionArray = explode(',',$ad_section_path);

																$tempArray      = $responseArray;


																foreach($adSectionArray as $arrayKey => $arrayValue)
																{
																	if(isset($tempArray[$arrayValue]) && is_array($tempArray[$arrayValue]))
																	{
																		$tempArray   = $tempArray[$arrayValue];
																		$successFlag = 1;
																	}
																}
															}
															else 
															{
																$tempArray   = $responseArray;
																$successFlag = 1;
															}

															if($successFlag == 1)
															{
																if(isset($tempArray[0]))
																{
																	$randomKeyArray = array_rand($tempArray);
																	$randomItem     = $tempArray[$randomKeyArray];
																	$feedAdArray[0] = $randomItem;
																}
																else 
																$feedAdArray[0] = $tempArray;
															}
															
															if($successFlag == 1 && count($feedAdArray) > 0 && !isset($feedAdArray[0]))
															{
																$dummyArray    = $feedAdArray;
																$feedAdArray   = array();
																$feedAdArray[] = $dummyArray;
															}

			/*********** Feed ads response processing start **************/
														    if(count($feedAdArray) > 0)
														    {
				if(
					$ad_container_type == 1 || 
					$ad_container_type == 2 || 


					$ad_container_type == 11																
				)

														    		{
	$adStringArray = $this->get_processed_feed_ads_result(
								$ad_container_type, 
								$feedAdArray
							 );



		    		if(count($adStringArray) > 0)

															    		{
						$this->set_variable('feedJSON',json_encode($adStringArray),0);



															    			$this->feedAdID     = $feedAid;









															    			$this->feedCacheable = $result_cacheable;
															    			$feedGetSuccess      = 1;

					
															    		}

																		else
																		$this->set_variable('feedJSON','');

															    		break;
															    	}













															else if($adcodeType == 9) //POP
														    	{





																			$originalpopad		 = 1;
					$pop_specific_profit = 100;



															    		    

																			if($pid > 0)
																			{
																				if($query_result['pop_profit_percentage'] >0)
																				$pop_specific_profit=$query_result['pop_profit_percentage'];
																				else
																				$pop_specific_profit=Configuration::get_instance()->read('pop_profit_percentage');
																		    }


					$this->adListArray = $this->get_processed_feed_pop_ads_result(
												$pid, 
												$pop_specific_profit, 
												$addata, 
												$feedAdArray,
												$countrywise_pricing_enabled, 
												$this->geo_country,
												$decoded_array,
												$publisherRid,
												$result['auid'],
												$pop_up_support,
												$pop_window_width,
												$pop_window_height,
												$originalpopad,
												$sid																									
											);

					if(is_array($this->adListArray) && count($this->adListArray) > 0)
																								{
						$adget_flag 		 = 1;
			    		$feedGetSuccess      = 1;
			    		$this->feedCacheable = $result_cacheable;


			    		$this->feedAdID      = $feedAid;





																		}

																		break;
																	}
															else if($ad_container_type == 21) //Directlink
														    	{
																			$originaldirectlinkad = 1;
					$cpm_specific_profit  = 100;

					if($display_type == 1 && $pid > 0)
																			{																			
																					if($query_result['cpm_profit_percentage'] >0)
																					$cpm_specific_profit = $query_result['cpm_profit_percentage'];
																					else
																					$cpm_specific_profit = Configuration::get_instance()->read('cpm_profit_percentage');
																				}

						$this->adListArray = $this->get_processed_feed_directlink_ads_result(
									$pid, 
									$cpm_specific_profit, 
									$addata, 
									$feedAdArray, 
									$countrywise_pricing_enabled, 
									$this->geo_country,
									$decoded_array,
									$publisherRid,
									$result['auid'],
									$display_type,
									$originaldirectlinkad,																																																																	
									$trackDomain,
									$indexAppend
								);

					if(is_array($this->adListArray) && count($this->adListArray) > 0)
																			{
						$adget_flag 		  = 1;
						$feedGetSuccess       = 1;
						$this->feedCacheable  = $result_cacheable;
						$this->feedAdID       = $feedAid;
						
																			}
					
														    			break;
														    		}
														    	}
			/*********** Feed ads response processing end **************/
														    }
														}
	/********* Loop through different feed ads getting from DB end ***********/
	
	if($feedGetSuccess == 0)												
	$adsResultCount = 0;
												}
															
														   
												else if($soKey == 'dsp-connector')
{
	//jesus
	while($addata = $resquery->fetch_assoc())
	{
		$userid    		  = 0;
		$rid    		    = 0;
		$retargetid 	  = 0;
		$retarget   	  = 0;
		$exchangeAid      = $addata['aid'];
		$keyid            = $addata['keyid'];
		$kid              = $addata['kid'];
		$type    		      = $addata['type'];
		$default_rate 	  = $addata['default_rate'];
		$dspEndPoint    	 = $addata['dsp_endpoint'];
		$dspRequestHeaders = $addata['dsp_request_headers'];
		$dspRateFrom    	 = $addata['dsp_rate_from'];//0=>Exchange,1=>DSP-Connector Settings
		$dspAuctionType    = intval($addata['dsp_auction_type']);
		if($dspAuctionType == 0)
		$dspAuctionType    = 2;
$dspRequestHeaderArray = [];
if (!empty($dspRequestHeaders)) {
    $lines = explode("\n", $dspRequestHeaders);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed !== '') {
            $dspRequestHeaderArray[] = $trimmed;
		}
		}
		}

	
	//Jesus
	$requestBidPayLoad = $this->get_bid_request_payload(
							$pid, $sid, $sitename, $result['auid'], $adcodeType, $ad_container_type,
							$dspAuctionType, $adcodeAllowedAds, $IABCategoryArray, $keyword,
							$requestIP, $requestUserAgent, $inpagepush, $native, 
							$language_targeting_enabled, $languageArray,
							$device_targeting_enabled, $deviceType, $page_referrer,
							$osname, $isp_connection_enabled, $isp_enabled, $isp, 
							$connection_enabled, $connection,
							$countryAlpha3, $subdivision1, $city_enabled, $cityid, $latitude, $longitude,  
							$bannerHeight, $bannerWidth, $text_ads_enabled, 
							$textimage_enabled, $textimageWidth, $textimageHeight
						);



			$exchangeCURL = curl_init($dspEndPoint);
			curl_setopt($exchangeCURL, CURLOPT_HEADER, false);
			curl_setopt($exchangeCURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($exchangeCURL, CURLOPT_HTTPHEADER, $dspRequestHeaderArray);
			curl_setopt($exchangeCURL, CURLOPT_POST, true);
	curl_setopt($exchangeCURL, CURLOPT_POSTFIELDS, $requestBidPayLoad);
			curl_setopt($exchangeCURL, CURLOPT_SSL_VERIFYPEER, false);
			$resultFromExchange        = curl_exec($exchangeCURL);
	$httpCode            = curl_getinfo($exchangeCURL, CURLINFO_HTTP_CODE);
			curl_close($exchangeCURL);
	


	
			$exchangeAdStringArray = array();
	if($httpCode === 200 && $resultFromExchange != "")
			{
		$RTBResponse = $this->get_processed_rtb_response($resultFromExchange, $adcodeType, $ad_container_type);


																				
				if($adcodeType == 9)
							{
				
					$originalpopad		 = 1;
					$pop_specific_profit = 100;

					if($pid > 0)
											{
						if($query_result['pop_profit_percentage'] >0)
						$pop_specific_profit = $query_result['pop_profit_percentage'];
						else
						$pop_specific_profit = Configuration::get_instance()->read('pop_profit_percentage');
					}

					foreach($RTBResponse as $key => $value)
															{
						
						//Need to add this code $impressionPrice <= 1 && for restricting unwanted price from response
						if(
							$value["bidRequestID"] != "" &&
							$value["impressionID"] != "" &&
							$value["impressionPrice"] > 0 &&	
							$value["impressionUrl"] != "" &&
							$value["impressionClickUrl"] != ""					
						) //$value["impressionPrice"] <= 1 && 
																	 {


					$this->adListArray = $this->get_processed_exchange_pop_ads_result(
													$pid, 
													$pop_specific_profit, 
													$addata, 
													$value["impressionClickUrl"],
													$value["impressionUrl"],
													$value["impressionPrice"],
													$countrywise_pricing_enabled, 
													$this->geo_country,
													$decoded_array,
													$publisherRid,
													$result['auid'],
													$pop_up_support,
													$pop_window_width,
													$pop_window_height,
													$originalpopad,
													$sid																									
												);
							break;
							//POP ads case loop execute only one time					
																	 }
															}
					if(is_array($this->adListArray) && count($this->adListArray) > 0)
					{																		
						$adget_flag 		 = 1;
						$exchangeGetSuccess  = 1;
						$this->exchangeAdID     = $exchangeAid;
						$this->exchangeAd       = 1;						
											}
					break; 

							}
			else 
			{
			$exchangeAdStringArray = array();
			foreach($RTBResponse as $key => $value)
			{
				
				//Need to add this code $impressionPrice <= 1 && for restricting unwanted price from response
			if(
					$value["bidRequestID"] != "" &&
					$value["impressionID"] != "" &&
					$value["impressionPrice"] > 0 &&	
					$value["impressionUrl"] != "" &&					
					(
						(($ad_container_type == 1 || $ad_container_type == 11) && $value["impressionClickUrl"] != "" &&  ($value["impressionTitle"] != "" || $value["impressionImageUrl"] != "")) || 
						($ad_container_type == 2 && ($value["impressionRenderContent"] != "" || ($value["impressionClickUrl"] != "" && $value["impressionImageUrl"] != "")))
				)
				) //$value["impressionPrice"] <= 1 && 
				{
					$exchangeAdStringArray[] = array(
										'title'          => $value["impressionTitle"],
										'description'    => $value["impressionDescription"],
										'display_url'    => $value["impressionDisplayUrl"],
										'click_url'      => $value["impressionClickUrl"],
										'default_rate'   => $value["impressionPrice"],
										'impression_url' => $value["impressionUrl"],										
										'banner'         => $value["impressionImageUrl"],		
										'render_content' => $this->mybase64_encode($value["impressionRenderContent"]),																				
										'bid_request_id' => $value["bidRequestID"],
										'impression_id'  => $value["impressionID"]
						);
				}		
			}			
				if(count($exchangeAdStringArray) > 0)
				{
					$adget_flag 		 = 1;
						$exchangeGetSuccess  = 1;
						$this->exchangeAdID     = $exchangeAid;
						$this->exchangeAd       = 1;
				
						$this->set_variable('exchangeJSON',json_encode($exchangeAdStringArray),0);
				}
						else
						$this->set_variable('exchangeJSON','');
				break; 
				}
}

}
if($exchangeGetSuccess == 0)
				$adsResultCount = 0;

}

												if(
												($soKey != 'feed' && $soKey != 'dsp-connector') || 
												($soKey == 'feed' && $feedGetSuccess == 1) || 
												($soKey == 'dsp-connector' && $exchangeGetSuccess == 1)
												)
												{
													if($pvalue == 'ppc')
													$display_type = 0;
													else if($pvalue == 'cpm')
													$display_type = 1;
													else if($pvalue == 'cpa')
													$display_type = 6;

													$adget_flag = 1;
													break;
												}
											}
										}
									/************* Checking of ads getting for display end ***********/
																				
										if($adget_flag == 1)
										break;
									}
								/************ Execution of different execute queries according to source/pricing/ad type etc end **********/
								}
							/************ Loop through ad type (text/banner/text+banner/video etc) end **********/

								if($adget_flag == 1)
								break;
							}
						/************ Loop through ad source based pricing end **********/

							if($adget_flag == 1)
							break;
						}
					/************ Loop through different ad source end **********/
					}
				else  //Ads getting from retargeting ads fetch
					{
						if($native == 1 && ($ad_container_type == 1 || $ad_container_type == 11) && $adcodeAllowedAds > $adsResultCount)
						$balanceads	= $adcodeAllowedAds-$adsResultCount;
						else
						$balanceads	= 0;

						if($balanceads >0)
						{
							if($pricing == 0)
							$pvalue='ppc';
							else if($pricing == 6)
							$pvalue='cpa';

							if(isset($displayArray[$pvalue][$ad_container_type][0]))
							{
								$adQueryStringReplace = $adQueryString;

								$pricingCondition     = $displayArray[$pvalue][$ad_container_type][0]["pricing-condition"];

								$budgetCondition      = $displayArray[$pvalue][$ad_container_type][0]["budget-condition"];

								$rotationCondition    = $displayArray[$pvalue][$ad_container_type][0]["adrotation-condition"];

								$commonCondition      = $displayArray[$pvalue][$ad_container_type][0]["common-condition"];

								$limitCondition       = $displayArray[$pvalue][$ad_container_type][0]["limit-condition"];

								$adQueryStringReplace		= str_replace("{RETARGET-CONDITION}"," AND ac.retargeting = 0 ",$adQueryStringReplace);
								$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition,$adQueryStringReplace);
								$adQueryStringReplace		= str_replace("{COMMON-CONDITION}",$commonCondition,$adQueryStringReplace);
								$adQueryStringReplace		= str_replace("{DAILY-BUDGET-CONDITION}",$budgetCondition,$adQueryStringReplace);
								$adQueryStringReplace		= str_replace("{AD-ROTATION-CONDITION}",$rotationCondition,$adQueryStringReplace);
								$adQueryStringReplace		= str_replace("{LIMIT-CONDITION}",$balanceads,$adQueryStringReplace);

								$resquery_balance	   = $db->execute_query($adQueryStringReplace);
								$balanceAdsForFilling  = $resquery_balance->get_num_records();

								$adsResultCount		   = $adsResultCount+$balanceAdsForFilling;
							}
						}
					}
					

					if($adcodeType != 9 && $adcodeType != 21)//POP & Directlink
					{
						if($adget_flag == 1 && $html_get == 1)
						$this->set_result("normalAdResult",$resquery,array('description'));
						else if($adget_flag ==1)
						{
							$this->set_result("normalAdResult",$resquery,array('click_url','feedJSON','exchangeJSON','banner_list'));

							if($balanceAdsForFilling >0)
							$this->set_result("normalAdResultBalance",$resquery_balance,array('click_url'));
						}
					}
				}

			if($stepValue == 2 || $adsResultCount > 0) //Default ad
				{
					$stepTwoPass	= 1;

					if($display_type != 3 && ($adsResultCount == 0 || ($native == 1 && $adsResultCount > 0 && ($ad_container_type == 1 || $ad_container_type == 11) && $adcodeAllowedAds > $adsResultCount && $feedGetSuccess == 0 && $exchangeGetSuccess == 0)))
					{
						if($native == 1 && $adsResultCount > 0 && ($ad_container_type == 1 || $ad_container_type == 11) && $adcodeAllowedAds > $adsResultCount)
						{
							$balanceads	= $adcodeAllowedAds-$adsResultCount;

							if(isset($displayArray['default'][$ad_container_type][0]) && $balanceads > 0)
							{
								$defaultAdQueryStringReplace = $defaultAdQueryString;

								$pricingCondition     = $displayArray['default'][$ad_container_type][0]["pricing-condition"];
								$commonCondition      = $displayArray['default'][$ad_container_type][0]["common-condition"];
								$limitCondition       = $displayArray['default'][$ad_container_type][0]["limit-condition"];


								$defaultAdQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND a.display_type = ".$pricingCondition,$defaultAdQueryStringReplace);
								$defaultAdQueryStringReplace		= str_replace("{COMMON-CONDITION}",$commonCondition,$defaultAdQueryStringReplace);
								$defaultAdQueryStringReplace		= str_replace("{LIMIT-CONDITION}",$balanceads,$defaultAdQueryStringReplace);

								$resquery_default   = $db->execute_query($defaultAdQueryStringReplace);

								$defaultAdsResultCount = $resquery_default->get_num_records();
							}
						}
						else if($adsResultCount == 0)
						{
							foreach($containerArray as $ckey => $cvalue)
							{
								$arrayDefault = array();

							if((($display_type == 1 && $adcodeType != 9 && $adcodeType != 13 && $adcodeType != 21) || $display_type == 4) && $ckey == 2 && isset($displayArray['default'][$ckey][1])) //Banner adcode
								{
									$time_random  = array(0,1);
									shuffle($time_random);

									if($time_random[0] == 1)
									{
										$arrayDefault[] = 1;
										$arrayDefault[] = 0;
									}
									else
									{
										$arrayDefault[] = 0;
										$arrayDefault[] = 1;
									}
								}
							else if($adcodeType == 9 || $adcodeType == 13)
								$arrayDefault[] = 1;
								else
								$arrayDefault[] = 0;
								
								foreach($arrayDefault as $akey => $avalue)
								{
									if(isset($displayArray['default'][$ckey][$avalue]))
									{
										$defaultAdQueryStringReplace = $defaultAdQueryString;

										$pricingCondition     = $displayArray['default'][$ckey][$avalue]["pricing-condition"];
										$commonCondition      = $displayArray['default'][$ckey][$avalue]["common-condition"];
										$limitCondition       = $displayArray['default'][$ckey][$avalue]["limit-condition"];

										$defaultAdQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND a.display_type = ".$pricingCondition,$defaultAdQueryStringReplace);
										$defaultAdQueryStringReplace		= str_replace("{COMMON-CONDITION}",$commonCondition,$defaultAdQueryStringReplace);
										$defaultAdQueryStringReplace		= str_replace("{LIMIT-CONDITION}",$limitCondition,$defaultAdQueryStringReplace);


										$resquery_default	   = $db->execute_query($defaultAdQueryStringReplace);
										$defaultAdsResultCount = $resquery_default->get_num_records();




										if($defaultAdsResultCount > 0)
										{
											$ad_container_type	= $ckey;

											if($adcodeType == 9)
											{
											$originalpopad = 0;
$this->adListArray = $this->get_processed_pop_ads_result(
							$pid, 
							$resquery_default, 
							$result['auid'],
							$pop_up_support,
							$pop_window_width,
							$pop_window_height,
							$originalpopad,
							$sid,
							$deviceType,
							$osname,
							$browsername,
							$catid,
							$this->geo_country	
						);
											}
											else if($adcodeType == 21)
											{
$originaldirectlinkad = 0;
$this->adListArray = $this->get_processed_directlink_ads_result(
								$pid, 
								$resquery_default, 
								$result['auid'],
								$originaldirectlinkad,
								$trackDomain,
								$indexAppend																												
							);			
											}
											else
											{
												if($ckey == 2)
												{
													if($avalue == 1)
													{
														$default_html_get	= 1;
														$display_type		= 1;
													}
													else
													$display_type   = 0;
												}
											}

											$adget_flag = 1;

											break;
										}
										else
										{
											/*
											$ckeyText = "";

											if($ckey == 1)
											$ckeyText = 'text';
											else if($ckey == 2 && $avalue == 0)
											$ckeyText = 'banner';
											else if($ckey == 2 && $avalue == 1)
											$ckeyText = 'html';
											else if($ckey == 4)
											$ckeyText = 'textimage';
											else if($ckey == 9)
											$ckeyText = 'pop';
											else if($ckey == 13)
											$ckeyText = 'cpv';
											else if($ckey == 14)
											$ckeyText = 'skin';

											if($ckeyText != "")
											$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET default_".$ckeyText."_ads = ? WHERE id = ?",array(time(),$adunitid));
											*/
										}
									}

									if($adget_flag == 1)
									break;
								}

								if($adget_flag == 1)
								break;
							}
						}

						if($adcodeType != 9 && $adcodeType != 21)
						{
							if($adget_flag == 1 && $default_html_get == 1)
							$this->set_result("defaultAdResult",$resquery_default,array('description'));
							else if($adget_flag == 1)
							$this->set_result("defaultAdResult",$resquery_default,array('click_url'));
						}
					}
				}

				//Need to set $totalAdCount again
				if($ad_container_type == 1 || $ad_container_type == 11)
				$totalAdCount	= $adcodeAllowedAds;
				else
				$totalAdCount	= 1;

				$this->displayAdCount = $totalAdCount;

				if($adget_flag == 1 && ($stepTwoPass == 1 || ($adsResultCount+$defaultAdsResultCount) >= $totalAdCount))
				break;
			}
		/************ Loop through advertiser/admin ads and default ads end ************/
		}
		else
		{
			if($adget_flag == 1)
			$this->set_result("normalAdResult",$resquery,array('click_url'));
		}

	if($adsResultCount == 0 && $adget_flag == 0 && $display_type != 3 && $adcodeType != 9 && $adcodeType != 13)
		$display_type = 0;

		if($adsResultCount == 0 && $defaultAdsResultCount == 0 && $display_type != 3)
		{
			if($adcodeType == 9)
			echo $this->remove_iframe($adunitid,18,$adcodeType);
			else if($adcodeType == 21)
			header("location:".BASE);
			else
			echo $this->remove_iframe($adunitid,18,$display_type);
			
			die;
		}
		
		$this->ad_container_type = $ad_container_type;

		if($adcodeType != 9 && $adcodeType != 21)
		{						
			$this->set_variable("normalAdsResultCount",$adsResultCount);
			$this->set_variable("defaultAdsResultCount",$defaultAdsResultCount);

			$this->set_variable("totalAdsResultCount",$adsResultCount+$defaultAdsResultCount);

			$this->set_variable('balanceAdsForFilling',$balanceAdsForFilling);
			$this->set_variable('sid',$sid);
			$this->set_variable('category_enabled',$category_targeting_enabled);
			$this->set_variable('cpd_enabled',$cpd_enabled);
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
			$this->set_variable('video_enabled',$video_enabled);

			$this->set_variable('expandable_width',$expandable_width);
			$this->set_variable('expandable_height',$expandable_height);
			$this->set_variable('expandable_enabled',$expandable_enabled);
		}
		else if(($adcodeType == 9 || $adcodeType == 21) && $adget_flag == 1)
		{
			$this->adListArray = json_encode($this->adListArray);
		}
		else if($adcodeType == 9 && $adget_flag == 0)
		{
			echo $this->remove_iframe($adunitid,18,$adcodeType);
			die;
		}
		else if($adcodeType == 21 && $adget_flag == 0)
		{
			header("location:".BASE);
			die;
		}
		
		if($display_type == 0)
		$trackInterval = $ppctrack;
		else if($display_type == 1 && $adcodeType != 9 && $adcodeType != 13 && $adcodeType != 21)
		$trackInterval = $cpmtrack;
		else if($display_type == 3)
		$trackInterval = $sponsoredtrack;
		else if($display_type == 6)
		$trackInterval = $cpatrack;

		$this->set_variable('trackInterval',$trackInterval);

		if($adcodeType == 9 || $adcodeType == 13)
		$this->adPricingValue	= $adcodeType;
		else
		$this->adPricingValue	= $display_type;

		$this->set_variable('aduid',$adunitid);
		$this->set_variable('display_type',$display_type);
		$this->set_variable('pop_enabled',$pop_enabled);
		$this->set_variable('feedGetSuccess',intval($feedGetSuccess));
		$this->set_variable('exchangeGetSuccess',intval($exchangeGetSuccess));
		$this->set_variable('totalAdCount',$totalAdCount);

		$adcodeFont     = 0;
		$adcodeTheme    = 0;
		$adLayoutID     = 0;
		$overrideTheme  = Configuration::get_instance()->read('allow_publishers_to_override_theme');


		if($ad_container_type == 1 || $ad_container_type == 2 || $ad_container_type == 11 || $ad_container_type == 13)
		{
			$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
			$this->set_result("resFontExternal",$resFontExternal);


			if($ad_container_type == 1 || $ad_container_type == 11)
			{
				if($native == 1)
				$adLayoutID   = intval($result['native_layout_id']);
				else
				{
					$adLayoutList = json_decode($result['ad_layout_list']);

					if(count($adLayoutList) > 0)
					{
						$randomKey  = array_rand($adLayoutList);

						$adLayoutID = $adLayoutList[$randomKey];
					}
				}

				if($adLayoutID > 0)
				{
					$layoutResult   = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_layout WHERE id = ?",array($adLayoutID));

					if($layoutResult->get_num_records() > 0)
					{
						$layoutResultData = $layoutResult->fetch_assoc();

						if($result['adcode_theme'] > 0)
						$adcodeTheme   = $result['adcode_theme'];
						else
						{
							if(($overrideTheme == 0 && $result['adcode_theme'] == -1) || $result['adcode_theme'] == 0)
							$adcodeTheme   = $layoutResultData['layout_theme'];
							else if($overrideTheme == 1 && $result['adcode_theme'] == -1)
							$adcodeTheme   = -1;
						}

						if($adcodeTheme == 0)
						$adcodeTheme = 1;

						$adcodeFont  = intval($layoutResultData['layout_font']);

						if($adcodeFont == 0)
						$adcodeFont = 1;

						$this->set_result("resAdLayout",$layoutResult);
					}
				}
			}
			else
			{
				if($result['adcode_theme'] > 0)
				$adcodeTheme   = $result['adcode_theme'];
				else
				{
					if(($overrideTheme == 0 && $result['adcode_theme'] == -1) || $result['adcode_theme'] == 0)
					$adcodeTheme   = $result['theme'];
					else if($overrideTheme == 1 && $result['adcode_theme'] == -1)
					$adcodeTheme   = -1;
				}

				if($adcodeTheme == 0)
				$adcodeTheme = 1;

				$adcodeFont  = intval($result['font']);

				if($adcodeFont == 0)
				$adcodeFont = 1;
			}


			if($adcodeTheme > 0)
			{
				$themeResult    = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."adblock_theme WHERE id =?",array($adcodeTheme));
				$this->set_result("themeResult",$themeResult);
			}

			$fontResult     = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."adblock_font WHERE id =?",array($adcodeFont));
			$this->set_result("fontResult",$fontResult);
		}

		$this->set_variable('adcodeFont',$adcodeFont);
		$this->set_variable('adcodeTheme',$adcodeTheme);
		$this->set_variable('adLayoutID',$adLayoutID);
		$this->set_variable('adSectionWidth',$adSectionWidth);
		$this->set_variable("adcodeType",$ad_container_type);
		$this->set_variable('adcodeDisplayType',$adcodeDisplayType);
		$this->set_variable('interstitial',$interstitial);
		$this->set_variable('inpagepush',$inpagepush);	
	}

	function feed_action()
	{
	  	if(!MOD_REWRITE)
		$this->indexAppend = "index.php?page=";


		$admarket_name 	        = Configuration::get_instance()->read('admarket_name');

		$displayArray					= array();
		$containerArray 				= array();
		$restrictedSiteArray 			= array();
		$language     					= array();
		$languageArray 					= array();
		$categorySupportedPricing 		= array();
		$adDisplayStep			   		= array();
    $queryReplacementArray         	= array();
    $queryDefaultReplacementArray 	= array();



		$trackDomain       = $this->get_track_domain();
		$this->trackDomain = $trackDomain;

		$cacheDevice  		 = "";
		$cacheCity    		 = "";
		$country       		 = "";
		$subdivision1  		 = "";
		$subdivision2  		 = "";
		$cityname      		 = "";
		$latitude      		 = "";
		$longitude     		 = "";

		$osname       		 = "";
		$browsername  		 = "";
		$isp        		 = "";
		$connection 		 = "";

		$deviceCondition 	 = "";
		$osCondition 		 = "";
		$browserCondition 	 = "";
		$dateCondition 		 = "";
		$categoryCondition 	 = "";
		$ispCondition        = "";
		$connectionCondition = "";
		$languageCondition   = "";

		$adResultXML 		 = "";
		$ispJoin             = "";
	    $keywordJoin   		 = "";


		$siteRestrictionCondition 		= "";
		$sameAccountAdDisplayCondition 	= "";


		$cachedFlag   	= 0;
		$cityid       	= 0;
		$sid          	= 0;
		$catid       	= 0;
		$osid         	= 0;
		$browserid    	= 0;
		$ispid        	= 0;
		$conn_id      	= 0;
		$dateFlag     	= 0;
		$bannerWidth  	= 0;
		$bannerHeight 	= 0;
		$iconWidth    	= 0;
		$iconHeight   	= 0;
		$bannerOK     	= 0;
		$iconOK       	= 0;
		$bannerID 		= 0;
		$iconID   		= 0;

		$isp_enabled            = 0;
		$connection_enabled     = 0;




		$feedID 		  = intval($this->read_get_param("feed"));
		$authKey          = $this->read_get_param("auth");
		$publisherID	  = intval($this->read_get_param("pid"));
		$pricingRequest   = intval($this->read_get_param("pricing"));
		$adcodeType       = intval($this->read_get_param("type")); //text,banner,text+image etc
		$displaySite      = strtolower(urldecode(trim($this->read_get_param("site"))));
		$bannerSize       = urldecode(trim($this->read_get_param("banner_size"))); //Width x Height
		$iconSize         = urldecode(trim($this->read_get_param("icon_size"))); //Width x Height
		$keywordString    = urldecode(trim($this->read_get_param("keywords"))); //Comma seperated
		$requestIP        = urldecode(trim($this->read_get_param("user_ip")));
		$requestUserAgent = urldecode(trim($this->read_get_param("ua")));
		$adsLimit         = intval($this->read_get_param("count"));
		$languageGet      = urldecode(trim($this->read_get_param("language"))); //Comma seperated en,ar






		if($adsLimit == 0)
		$adsLimit = 5;

		if($adcodeType == 0)
		$adcodeType = 3;



		if($feedID == 0 || $authKey == "" || $publisherID == 0 || ($pricingRequest != 0 && $pricingRequest != 4 && $pricingRequest != 6) || $displaySite == "" || $requestIP == "" || ($adcodeType == 2 && $bannerSize == "") || ($adcodeType == 11 && $iconSize == ""))
		{
			$this->feedNotification($feedID,$admarket_name,"Required parameters are missing!");
			die;
		}


        $this->cacheIP 		= $requestIP;
        $this->cacheDate    = date('d',time()).date('H',time());

		$encryptedIP                  = md5($requestIP);
		$this->cacheEncryptedIP       = $encryptedIP;

		if(Configuration::get_instance()->read('proxy_detection_for_ad_display') == 1 && UtilityHelper::proxyDetection())
		{
			$this->feedNotification($feedID,$admarket_name,"Proxy request!");
			die;
		}


		$feedads_enabled = $this->get_addon_status('feed-ads_enabled');

		if($feedads_enabled == 1)
		$feedads_enabled = intval(Configuration::get_instance()->read('supply_feed_ads'));

		if($feedads_enabled == 0)
		{
			$this->feedNotification($feedID,$admarket_name,"Feed ad request not supported");
			die;
		}



		$cpc_enabled                = $this->get_addon_status('cpc_enabled');
		$cpa_enabled                = $this->get_addon_status('cpa_enabled');
		$textimage_enabled          = $this->get_addon_status('text-image-ads_enabled');
		$city_enabled               = $this->get_addon_status('city-targeting_enabled');
		$category_targeting_enabled = $this->get_addon_status('category-targeting_enabled');
		$time_targeting_enabled     = $this->get_addon_status('time-targeting_enabled');
		$device_targeting_enabled   = $this->get_addon_status('device-targeting_enabled');
		$isp_connection_enabled     = $this->get_addon_status('isp-connection-targeting_enabled');
		$language_targeting_enabled = $this->get_addon_status('language-targeting_enabled');


		if($isp_connection_enabled == 1)
		{
			$isp_enabled            = $this->get_addon_status('isp-targeting_enabled');
			$connection_enabled     = $this->get_addon_status('connectiontype-targeting_enabled');
		}

		if($city_enabled == 1)
		{
			$record       = UtilityHelper::get_geo_details_from_ip($requestIP,1);
			$country      = $record->country_code;
			$subdivision1 = $record->subdivision1;
			$subdivision2 = $record->subdivision2;
			$cityname     = $record->city;
			$latitude     = $record->latitude;
			$longitude    = $record->longitude;
			$cityid       = $record->cityid;
			$cacheCity    = $subdivision1.$subdivision2.$cityid;
		}
		else
		$country      = UtilityHelper::get_country_from_ip($requestIP);


		$this->geo_country = $country;


		if($device_targeting_enabled == 1)
		{

			$browserClass   = new foroco\BrowserDetection();
			$browserResult  = $browserClass->getAll($requestUserAgent);

			$deviceType = $browserResult['device_type'];
			if($deviceType =='desktop')
			$cacheDevice='c';
			else if($deviceType =='mobile' )
			$cacheDevice='m';

			$os_targeting_enabled      = Configuration::get_instance()->read('os-targeting_enabled');
			$browser_targeting_enabled = Configuration::get_instance()->read('browser-targeting_enabled');

			if($os_targeting_enabled ==1)
			{
				$osname=$browserResult['os_name'];
				$cacheDevice=$cacheDevice.$osname;
			}

			if($browser_targeting_enabled ==1)
			{
				$browsername=$browserResult['browser_name'];
				$cacheDevice=$cacheDevice.$browsername;
			}
		}



		if($isp_enabled ==1 || $connection_enabled ==1)
		{
			try
			{

			    $db1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);

				$dbip = new DBIP_MySQLI($db1);
				$ipLookUpInfo = $dbip->Lookup($requestIP,TABLE_PREFIX.'dbip_lookup');
				$isp= $ipLookUpInfo->isp_name ;
				$connection=$ipLookUpInfo->connection_type ;
				$cacheDevice=$cacheDevice.$isp.$connection;


			} catch (DBIP_Exception $e) {
				//echo "error: {$e->getMessage()}\n";
			}
		}



		if($language_targeting_enabled==1)
		{
			$x = explode(",",$languageGet);

			foreach($x as $val)
			{
			    $language[] = "'".$val."'";
			}

			$languageArray = array_unique($language);
		}


		$normalAdsCache = $feedID.$displaySite.$country.$cacheCity.$cacheDevice.'1'.$authKey.$publisherID.$pricingRequest.$adcodeType.$bannerSize.$iconSize.$languageGet;
		$normalAdsCache = $feedID."-".md5($normalAdsCache).".php";


		$this->feedCacheName = $normalAdsCache;


		include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);

		if($cachedFlag == 1)
		die;
		else if($cachedFlag == 2)
		unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);


		$db  = DAL::get_instance();

		$res          = $db->execute_query("select * from ".TABLE_PREFIX."adunit where id=?",array($feedID));

		$adcodeExists = intval($res->get_num_records());

		if($adcodeExists == 0)
		{
			$this->feedNotification($feedID,$admarket_name,"No adcode exists");
			die;
		}


		$result = $res->fetch_assoc();

		$display_type = $result['display_type'];
		$pid          = $result['pubid'];

		if($pid != $publisherID)
		{
			$this->feedNotification($feedID,$admarket_name,"Url parameters changed");
			die;
		}

	    if($pricingRequest != $display_type)
	    {
			$this->feedNotification($feedID,$admarket_name,"Url parameters changed");
			die;
		}


		$queryTimes	 = intval(Configuration::get_instance()->read('count_of_ads_query_per_request'));

		if($queryTimes == 0)
		$queryTimes  = 1;




		if($cpc_enabled ==1 && $cpa_enabled ==1 && Configuration::get_instance()->read('ad_preference') ==0 && $pid >0)
		$display_type = 4;

	    $adcodeAllowedAds    = $adsLimit;
		$ad_container_type   = $adcodeType;

		if($bannerSize != "")
		{
			$bannerSizeArray = explode("x",$bannerSize);

			if(isset($bannerSizeArray[0]))
			$bannerWidth     = floatval($bannerSizeArray[0]);

			if(isset($bannerSizeArray[1]))
			$bannerHeight    = floatval($bannerSizeArray[1]);

			if($bannerWidth > 0  && $bannerHeight > 0)
			$bannerOK     = 1;
		}

		if($iconSize != "")
		{
			$iconSizeArray   = explode("x",$iconSize);

			if(isset($iconSizeArray[0]))
			$iconWidth     = floatval($iconSizeArray[0]);

			if(isset($iconSizeArray[1]))
			$iconHeight    = floatval($iconSizeArray[1]);

			if($iconWidth > 0  && $iconHeight > 0)
			$iconOK        = 1;
		}


		if($ad_container_type == 2 && $bannerOK == 0)
		{
			$this->feedNotification($feedID,$admarket_name,"Banner size parameter missing");
			die;
		}

		if($ad_container_type == 11 && $iconOK == 0)
		{
			$this->feedNotification($feedID,$admarket_name,"Icon size parameter missing");
			die;
		}


		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

		if($text_ads_enabled ==0 && $ad_container_type ==1)
		{
			$this->feedNotification($feedID,$admarket_name,"Text ads not supported");
			die;
		}


		if($text_ads_enabled == 0 && $textimage_enabled != 1 && $ad_container_type ==3)
		$ad_container_type=2;


		if($textimage_enabled == 0 && $ad_container_type == 11)
		{
			$this->feedNotification($feedID,$admarket_name,"Text+image ads not supported");
			die;
		}

		if($category_targeting_enabled ==1)
		{
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

			if($category_enabled_ads !='')
			$categorySupportedPricing = explode('_',$category_enabled_ads);
		}


		/*************** For device Restriction **************/

		if($device_targeting_enabled ==1)
		{
		    $deviceCondition.=' AND ( ac.device = 2 ';

		    if($deviceType == 'desktop')
		    {
		        if($deviceCondition != '')
		        $deviceCondition.=' OR ';

		        $deviceCondition.=' ac.device = 0  ';
		    }

		    if($deviceType == 'mobile' )
		    {
		        if($deviceCondition != '')
		        $deviceCondition.=' OR ';

		        $deviceCondition.=' ac.device = 1 ';
		    }

		    $deviceCondition.=' ) ';


		    if($os_targeting_enabled==1)
		    {
		        $osid = $db->read_single_column("select id from ".TABLE_PREFIX."os where name=?",array($osname));

		        $osCondition = ' AND (';

		        if($osid > 0)
		        $osCondition.=' ac.'.$osid.'_os = 1 OR ';

		        $osCondition.=' ac.all_os = 1 )';
		    }


		    if($browser_targeting_enabled == 1)
		    {
		        $browserid = $db->read_single_column("select id from ".TABLE_PREFIX."browser where name=?",array($browsername));

		        $browserCondition.=' AND (';

		        if($browserid > 0)
		        $browserCondition.=' ac.'.$browserid.'_browser = 1 OR ';

		        $browserCondition.=' ac.all_browser = 1 )';
		    }
		}
		/*************** For device Restriction **************/


	    if($time_targeting_enabled ==1)
	    {
	        $datetime  = time();

	        $date_filter_enabled = Configuration::get_instance()->read('date_filter_enabled');
	        $time_filter_enabled = Configuration::get_instance()->read('time_filter_enabled');
	        $day_filter_enabled  = Configuration::get_instance()->read('day_filter_enabled');


	        if($date_filter_enabled == 1 || $time_filter_enabled == 1 || $day_filter_enabled == 1)
	        {
	            $dateCondition.=' AND (';

	            if($date_filter_enabled == 1)
	            {
	                $dateCondition.=' (ac.date_filter =0 OR (ac.date_filter =1 AND ac.tmt_start_date <='.$datetime.' AND ac.tmt_end_date >='.$datetime.')) ';
	                $dateFlag = 1;
	            }

	            if($time_filter_enabled == 1)
	            {
	                $datehour=date('G',time());

	                if($dateFlag == 1)
	                $dateCondition.=' AND ';

	                $dateCondition.=' (ac.time_filter = 0 OR (ac.time_filter = 1 AND ac.'.$datehour.'_hour = 1)) ';

	                $dateFlag = 1;
	            }

	            if($day_filter_enabled == 1)
	            {
	                $dateday=date('w',time());

	                if($dateday == 0)
	                $dateday = 7;

	                if($dateFlag == 1)
	                $dateCondition.=' AND ';

	                $dateCondition.=' (ac.day_filter = 0 OR (ac.day_filter = 1 AND ac.'.$dateday.'_day = 1)) ';
	            }

	            $dateCondition.=')';
	        }
	    }


		if($pid >0 && Configuration::get_instance()->read('allow_publishers_to_display_their_own_ads') ==0)
		{
				$sameAccountAdDisplayCondition = " AND ac.uid <> ".$pid." ";
		}

		if($pid >0)
		{
			$pquery_res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."users WHERE id=?",array($pid));
			$query_result=$pquery_res->fetch_assoc();


			if($query_result['pub_status'] !=1)
			{
				$this->feedNotification($feedID,$admarket_name,"User status not active");
				die;
			}

			if($authKey != $query_result['feed_auth_key'])
			{
				$this->feedNotification($feedID,$admarket_name,"Feed auth key invalid");
				die;
			}

			if(intval($query_result['supply_feed_ads']) == 0)
			{
				$this->feedNotification($feedID,$admarket_name,"Feed ad request not supported");
				die;
			}




			$site_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array ($pid));

			if($site_json != '')
			$restrictedSiteArray = json_decode($site_json);


			foreach($restrictedSiteArray as $value)
			{
				if($value != "")


				$siteRestrictionCondition.= " AND a.click_url not like '%" . $db->sanitize($value) . "%' ";

			}
		}


		if($bannerOK == 1)
		{
			$bannerID  = intval($db->read_single_column("SELECT id FROM ".TABLE_PREFIX."banner_dimensions WHERE width = ? AND height = ? AND (banner_type = 0 OR banner_type = 1)",array($bannerWidth,$bannerHeight)));

			if($bannerID == 0)
			$bannerOK = 0;
		}

		if($iconOK == 1)
		{
			$iconID    = intval($db->read_single_column("SELECT id FROM ".TABLE_PREFIX."banner_dimensions WHERE width = ? AND height = ? AND banner_type = 3",array($iconWidth,$iconHeight)));

			if($iconID == 0)
			$iconOK = 0;
		}

		$rotation_variable=Configuration::get_instance()->read('ad_rotation');

		if($rotation_variable ==1)
		{
			if(time() % 5 == 0)
			$rotation_variable = 0;
		}

		$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

		$premiumString = "";
		if($premium_ad_enabled == 1)
		$premiumString = " ac.premium_ad DESC,";

	    if($rotation_variable ==1)
	    {
	      $normalAdRotation = " ORDER BY ".$premiumString."ac.default_rate DESC,random ASC ";
	      $cpaAdRotation    = " ORDER BY ".$premiumString."ac.tracking_last_checked DESC,ac.default_rate DESC,random ASC";
	    }
	    else
	    {
            $normalAdRotation = " ORDER BY ".$premiumString."random ASC ";
            $cpaAdRotation    = " ORDER BY ".$premiumString."random ASC ";
	    }

	    $adStatusCondition       = " AND ac.status = 1 ";
	    $userStatusCondition     = " AND ac.user_status = 1 ";
	    $adPauseStatusCondition  = " AND ac.pause_status = 0 ";

	    $defaultAdStatusCheck         = " AND a.status = 1 ";
	    $defaultAdPauseStatusCheck    = " AND a.pause_status = 0 ";

		$budgetCondition      = " AND ac.budget_available = 1 ";
		$dailyBudgetCondition = " AND ac.daily_budget_available = 1 ";


		/********************************** Keyword Query **********************************/

		$keyword_based_display  = Configuration::get_instance()->read('keyword_based_ad_display');

		if($keyword_based_display ==1)
		{
			$keywords = explode(",",$keywordString);
			$keyword  = array_unique($keywords);

			$keywordCondition = " (";

			foreach($keyword as $k=>$v)
			{
				if(trim($v) !="")
				{
					$key=intval($db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=? and status=1",array(trim($v))));

					if($key >0)
					{
						if($keywordCondition !=" (")
						$keywordCondition.=" OR ";

						$keywordCondition.="km.kid = ".$key." ";
					}
				}
			}

			if($keywordCondition !=" (")
			$keywordCondition.=" OR ";

			$keywordCondition.="km.kid = 0 ";
			$keywordCondition.=" ) ";


			$keywordJoin = " INNER JOIN ".TABLE_PREFIX."ad_keyword_mapping k ON k.id = (SELECT km.id FROM ".TABLE_PREFIX."ad_keyword_mapping km WHERE km.aid = a.id AND ".$keywordCondition." LIMIT 0,1) ";


			$keywordColumns = " ,k.kid as keyid,COALESCE(k.id,0) as kid ";
		}
		else
		$keywordColumns  = " ,0 as keyid,0 as kid ";

		/********************************** Keyword Query **********************************/



		/********************************** Geographic Query *******************************/


	    if($city_enabled ==1)
	    {
		      $countryJoin = TABLE_PREFIX."ad_geographic_mapping g,";

		      $countryJoinCondition = " a.id = g.aid ";

		      $countryCondition =" AND (";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='".$subdivision2."') AND (g.city=".$cityid.")) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='".$subdivision2."') AND (g.city=0)) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='0') AND (g.city=".$cityid.")) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='0') AND (g.city=0)) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=".$cityid.")) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=0)) OR ";

		      $countryCondition.=" ((g.country_code='0') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=0)) ";

		      $countryCondition.=" )";

	    }
	    else
	    {
	           $countryCondition=" ( ";

	           if($country != "")
	           $countryCondition.=" ac.".$country."_country = 1 OR ";

	           $countryCondition.=" ac.all_countries = 1 ) ";
	    }


		/********************************** Geographic Query *******************************/


		/********************************** Category Targeting ********************************/
	    if($catid != "" && in_array($display_type,$categorySupportedPricing))
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


		      $categoryCondition = ' AND ( ';

		      if($category_string != '')
		      {
			        $categoryArray = explode(",",$category_string);

			        foreach($categoryArray as $key => $value)
			        {
			          	$categoryCondition.=' ac.'.intval($value).'_category = 1 OR ';
			        }
		      }
		      $categoryCondition.=' ac.all_categories = 1 )';

	    }
		/********************************** Category Targeting ********************************/

		/********************* ISP & Connection Type Targeting **********************/

	    if($isp_enabled ==1 || $connection_enabled ==1)
	    {
		      if($isp !='' && $isp_enabled ==1)
		      $ispid = intval($db->read_single_column("select id from ".TABLE_PREFIX."isp where name=? and country=?",array($isp,$country)));

		      if($connection !='' && $connection_enabled ==1)
		      $conn_id = intval($db->read_single_column("select id from ".TABLE_PREFIX."connection where name=? ",array($connection)));


		      if($isp_enabled == 1)
		      {
		        $ispJoin = ' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_isp_mapping im ON a.id = im.aid ';

		        $ispCondition.=' AND (';

		        if($ispid > 0)
		        $ispCondition.=' ( im.ispid ='.$ispid.' OR im.ispid IS NULL OR im.ispid=0) ';
		        else
		        $ispCondition.=' ( im.ispid IS NULL OR  im.ispid=0)';

		        $ispCondition.=' )';
		      }



		      if($connection_enabled == 1)
		      {
		        $connectionCondition.=' AND (';

		        if($conn_id > 0)
		        $connectionCondition.='ac.'.$conn_id.'_connection = 1 OR';

		        $connectionCondition.=' ac.all_connections = 1';

		        $connectionCondition.=' )';
		      }

	    }
		/****************** ISP & Connection Type Targeting  *********************/

		/****************** Language Targeting *****************************/

   	    if($language_targeting_enabled==1)
	    {
		      if(count($languageArray) > 0)
		      {


		  		$languageValueString = '';

		               	foreach($languageArray as $lkey => $lvalue)
		               	{
		               	    $languageArray[$lkey] = str_replace("'","", $lvalue);

				    if($languageValueString !='')
		                    $languageValueString.=',';

		               	    $languageValueString.='?';
		                }

				$language_data=$db->execute_query("select id from ".TABLE_PREFIX."language where code in (".$languageValueString.")",$languageArray);

			        if($language_data->get_num_records() > 0)
			        {
				          $languageCondition.= ' AND ( ';

				          while ($brdata=$language_data->fetch_assoc())
				          {
				            $languageCondition.= ' ac.'.intval($brdata['id']).'_language = 1 OR ';
				          }
				          $languageCondition.='ac.all_languages = 1 )';

			        }
		      }
	    }

		/**********************************Language Targeting *********************************/



	    $adQueryString = "SELECT ac.id as aid,ac.default_rate,
	      ac.uid as userid, a.title,a.description,a.cta_button_text,a.display_url,a.click_url, a.banner,ac.display_type,ac.type".$keywordColumns.",rand() as random

	      FROM ".$countryJoin." ".TABLE_PREFIX."ads a
	      ".$keywordJoin."
	      ".$ispJoin."
	      INNER JOIN ".TABLE_PREFIX."ads_cache ac ON ac.id = a.id
	      WHERE
	          ".$countryJoinCondition."
	          ".$countryCondition."
	          ".$categoryCondition."
	          ".$deviceCondition."
	          ".$dateCondition."
	          ".$osCondition."
	          ".$browserCondition."
	          ".$languageCondition."
	          ".$ispCondition."
	          ".$connectionCondition."
	          AND ac.pricing_status = 1

	          {PRICING-CONDITION}
	          {COMMON-CONDITION}

	        ".$adStatusCondition."
	        ".$adPauseStatusCondition."
	        ".$userStatusCondition."
	        ".$sameAccountAdDisplayCondition."
	        ".$siteRestrictionCondition."
	        ".$budgetCondition."

	        {DAILY-BUDGET-CONDITION}
	        {AD-ROTATION-CONDITION}

	        LIMIT 0,{LIMIT-CONDITION}";


	    $defaultAdQueryString = "SELECT a.id as aid, a.title, a.description,a.cta_button_text, a.display_url, a.click_url,a.banner,a.type,a.display_type,rand() as random
	        FROM ".TABLE_PREFIX."ads a
	        WHERE a.uid = 0
	        ".$defaultAdStatusCheck."
	        ".$defaultAdPauseStatusCheck."

	        {PRICING-CONDITION}
	        {COMMON-CONDITION}

	        ORDER BY random ASC
	        LIMIT 0,{LIMIT-CONDITION}";



		$currentDisplayTime  	  			= time();

		$default_ad_display_random 			= intval(Configuration::get_instance()->read('default_ad_display_random'));
		$default_ad_display_gap	   			= intval(Configuration::get_instance()->read('default_ad_display_gap'));
		$default_ad_existing_recheck 	    = intval(Configuration::get_instance()->read('default_ad_existing_recheck'));


		if($default_ad_display_random == 1)
		{
			$defaultAdLastDisplay	  		= intval($result['default_ad_display_time']);
			$default_ad_display_interval    = 60 * $default_ad_display_gap;

			if(($currentDisplayTime - $defaultAdLastDisplay) < $default_ad_display_interval)
			$default_ad_display_random = 0;
		}


		$delayTime			 	   = 60 * $default_ad_existing_recheck;



		if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
		{
			if(($currentDisplayTime - $result['default_text_ads']) > $delayTime)
			{
			    $queryDefaultReplacementArray["pricing-condition"] = 0;
		        $queryDefaultReplacementArray["common-condition"]  = " AND a.type =1 ";
		        $queryDefaultReplacementArray["limit-condition"]   = $queryTimes * $adsLimit;

		        $displayArray['default'][1][0]                     = $queryDefaultReplacementArray;
			}
		}

		if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerOK == 1)
		{
			if(($currentDisplayTime - $result['default_banner_ads']) > $delayTime)
	      	{
		        $queryDefaultReplacementArray["pricing-condition"] = 0;
		        $queryDefaultReplacementArray["common-condition"]  = " AND a.type =2 AND a.html5 = 0 AND a.banner_id =".$bannerID;
		        $queryDefaultReplacementArray["limit-condition"]   = $queryTimes * $adsLimit;

		        $displayArray['default'][2][0]             = $queryDefaultReplacementArray;
	      	}
		}


		if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1 && $iconOK == 1)
		{
			if(($currentDisplayTime - $result['default_textimage_ads']) > $delayTime)
	      	{
		        $queryDefaultReplacementArray["pricing-condition"] = 0;
		        $queryDefaultReplacementArray["common-condition"]  = " AND a.type =11 AND a.banner_id =".$iconID;
		        $queryDefaultReplacementArray["limit-condition"]   = $queryTimes * $adsLimit;

		        $displayArray['default'][11][0]             = $queryDefaultReplacementArray;
	      	}
		}




		if($cpc_enabled == 1 && ($display_type == 0 || $display_type == 4))
		{
	      	$queryReplacementArray["pricing-condition"]    = 0;
	      	$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
	      	$queryReplacementArray["adrotation-condition"] = $normalAdRotation;


			if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =1 ";
		        $queryReplacementArray["limit-condition"]    = $queryTimes * $adsLimit;

		        $displayArray['ppc'][1][0]           = $queryReplacementArray;
			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerOK == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =2 AND ac.html5 = 0 AND ac.banner_id =".$bannerID;
		        $queryReplacementArray["limit-condition"]    = $queryTimes * $adsLimit;

		        $displayArray['ppc'][2][0]           = $queryReplacementArray;
			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1 && $iconOK == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =11 AND ac.banner_id =".$iconID;
		        $queryReplacementArray["limit-condition"]    = $queryTimes * $adsLimit;

		        $displayArray['ppc'][11][0]           = $queryReplacementArray;


			}
		}



		if($cpa_enabled == 1 && ($display_type == 6 || $display_type == 4))
		{
		    $queryReplacementArray["pricing-condition"]    = 6;
		    $queryReplacementArray["budget-condition"]     = "";
		    $queryReplacementArray["adrotation-condition"] = $cpaAdRotation;



			if(($ad_container_type == 1 || $ad_container_type == 3) && $text_ads_enabled == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =1 ";
		        $queryReplacementArray["limit-condition"]    = $queryTimes * $adsLimit;

		        $displayArray['cpa'][1][0]           = $queryReplacementArray;
			}

			if(($ad_container_type == 2 || $ad_container_type == 3) && $bannerOK == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =2 AND ac.html5 = 0 AND ac.banner_id =".$bannerID;
		        $queryReplacementArray["limit-condition"]    = $queryTimes * $adsLimit;

		        $displayArray['cpa'][2][0]           = $queryReplacementArray;
			}

			if(($ad_container_type == 11 || $ad_container_type == 3) && $textimage_enabled == 1 && $iconOK == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =11 AND ac.banner_id =".$iconID;
		        $queryReplacementArray["limit-condition"]    = $queryTimes * $adsLimit;

		        $displayArray['cpa'][11][0]           = $queryReplacementArray;


			}
		}


		$priority_array       = array();

		if($display_type == 4)
		{
			$priority			= Configuration::get_instance()->read('ad_display_priority');
			$ad_display_random	= Configuration::get_instance()->read('ad_display_random');

			$priority_array		= explode('_',$priority);
			$pstring			= "";

			foreach($priority_array as $pkey1=>$pvalue1)
			{
				if(($cpc_enabled == 1 && $pvalue1 == 'ppc') || ($cpa_enabled == 1 && $pvalue1 == 'cpa'))
				{
					if($pstring != "")
					$pstring.="_";

					$pstring.=$pvalue1;
				}
			}

			if($pstring != "")
			$priority	= $pstring;


			if($ad_display_random ==1)
			{
				$priority_array	= explode('_',$priority);

				$countlist 		= count($priority_array);


				if($countlist >1)
				{

					if($countlist ==2)
					{

        				$currentrandom  = array(0,1);
        				shuffle($currentrandom);

						if($currentrandom[0] == 0)
						$priority=$priority_array[0].'_'.$priority_array[1];
						else
						$priority=$priority_array[1].'_'.$priority_array[0];
					}
				}
			}

			$priority_array=explode('_',$priority);
		}
		else if($cpc_enabled ==1 && $display_type == 0)
		{
			$priority			= 'ppc';
			$priority_array[] 	= 'ppc';
		}
		else if($cpa_enabled ==1 && $display_type == 6)
		{
			$priority			= 'cpa';
			$priority_array[] 	= 'cpa';
		}


		$containerArrayTemp    = array();
		$sourceArray           = array();
		$sourceArray['system'] = $priority_array;


		if($ad_container_type == 3)
		{
			if($text_ads_enabled == 1)
			$containerArray[1]	= 1;

			if($bannerOK == 1)
			$containerArray[2]	= 2;

			if($textimage_enabled == 1 && $iconOK == 1)
			$containerArray[11]	= 11;

			$containerArrayKeys = array_keys($containerArray);

			shuffle($containerArrayKeys);

			foreach($containerArrayKeys as $caKey)
			{
			    $containerArrayTemp[$caKey] = $containerArray[$caKey];
			}

			$containerArray = $containerArrayTemp;
		}
		else
		$containerArray[$ad_container_type]	= $ad_container_type;




		$pricing				= 0;
		$adget_flag				= 0;
		$adsResultCount			= 0;
		$defaultAdsResultCount	= 0;
		$stepTwoPass			= 0;



		$this->displayAdCount	= $adsLimit;


		if($default_ad_display_random == 0)
		{
			$adDisplayStep[]	= 1;   //Advertiser ad
			$adDisplayStep[]	= 2;   //Default ad
		}
		else
		{
			$adDisplayStep[]	= 2;	//Default ad
			$adDisplayStep[]	= 1;	//Advertiser ad
		}


		$base_url       = BASE;

			foreach($adDisplayStep as $stepKey => $stepValue)
			{
				if($stepValue == 1)
				{
					foreach($sourceArray as $soKey => $soValue)
					{
						$checkSourceKey = 0;

						foreach($soValue as $pkey=>$pvalue)
						{
							foreach($containerArray as $ckey => $cvalue)
							{
								if(isset($displayArray[$pvalue][$ckey][$checkSourceKey]))
								{
									$adQueryStringReplace = $adQueryString;

				                    $pricingCondition     = $displayArray[$pvalue][$ckey][$checkSourceKey]["pricing-condition"];

				                    $budgetCondition      = $displayArray[$pvalue][$ckey][$checkSourceKey]["budget-condition"];

				                    $rotationCondition    = $displayArray[$pvalue][$ckey][$checkSourceKey]["adrotation-condition"];

				                    $commonCondition      = $displayArray[$pvalue][$ckey][$checkSourceKey]["common-condition"];

				                    $limitCondition       = $displayArray[$pvalue][$ckey][$checkSourceKey]["limit-condition"];



				                    $adQueryStringReplace   = str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition,$adQueryStringReplace);

				                    $adQueryStringReplace   = str_replace("{DAILY-BUDGET-CONDITION}",$budgetCondition,$adQueryStringReplace);

				                    $adQueryStringReplace   = str_replace("{AD-ROTATION-CONDITION}",$rotationCondition,$adQueryStringReplace);

				                    $adQueryStringReplace   = str_replace("{LIMIT-CONDITION}",$limitCondition,$adQueryStringReplace);

				                    $adQueryStringReplace   = str_replace("{COMMON-CONDITION}",$commonCondition,$adQueryStringReplace);


									$resquery			= $db->execute_query($adQueryStringReplace);

									$adsResultCount		= $resquery->get_num_records();

									if($adsResultCount > 0)
									{
										$ad_container_type	= $ckey;

										if($pvalue == 'ppc')
										{
											$display_type   = 0;

											if($pid > 0)
											{
												if($query_result['ppc_profit_percentage'] >0)
												$profit_percentage=$query_result['ppc_profit_percentage'];
												else
												$profit_percentage=Configuration::get_instance()->read('profit_percentage');
											}
										}
										else if($pvalue == 'cpa')
										{
											$display_type=6;

											if($pid > 0)
											{
												if($query_result['cpa_profit_percentage'] >0)
												$profit_percentage=$query_result['cpa_profit_percentage'];
												else
												$profit_percentage=Configuration::get_instance()->read('cpa_profit_percentage');
											}
										}
		$publisherRevenue	= 0;
		while($addata = $resquery->fetch_assoc())
		{
			$revenueSpend	    = $addata['default_rate'];

			if($pid > 0)
			$publisherRevenue = $revenueSpend*$profit_percentage/100;
			else
		 	$publisherRevenue = $revenueSpend;

			$impressionString = $addata['userid'].'|'.$addata['aid'].'|'.$addata['kid'].'|'.$pid.'|'.$feedID.'|1|'.$sid.'|'.$display_type.'|'.$addata['keyid'];

			$banner_url = '';
			$icon_url   = '';

			if($ad_container_type == 2)
			$banner_url = $base_url.DATA_DIR.'/'.$addata['aid'].'_'.$addata['banner'];
			else if($ad_container_type == 11)
			$icon_url   = $base_url.DATA_DIR.'/'.$addata['aid'].'_'.$addata['banner'];


			$bs         = md5($addata['aid'].$addata['kid'].$feedID.$sid.$pid.'0');
			$click_url  = $trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/validate/'.$addata['aid'].'/'.$addata['kid'].'/'.$feedID.'/'.$sid.'/'.$pid.'/{ENCIP}/'.$bs.'/0/0';


			$pixel_url  = "{PIXEL-URL-".$addata['aid']."}";

    		$this->adListArray[0][$addata['aid']] 				   = $impressionString;
    		$this->adListArray[2][$addata['aid']]['count'] 		   = $adsResultCount;
    		$this->adListArray[2][$addata['aid']]['default_rate']  = $publisherRevenue;
    		$this->adListArray[2][$addata['aid']]['title'] 		   = $addata['title'];
    		$this->adListArray[2][$addata['aid']]['description']   = $addata['description'];
    		$this->adListArray[2][$addata['aid']]['display_url']   = $addata['display_url'];
    		$this->adListArray[2][$addata['aid']]['click_url'] 	   = $click_url;
    		$this->adListArray[2][$addata['aid']]['pixel_url'] 	   = $pixel_url;
			$this->adListArray[2][$addata['aid']]['banner_url']    = $banner_url;
			$this->adListArray[2][$addata['aid']]['icon_url'] 	   = $icon_url;
		}


										$adget_flag = 1;
										break;
									}

									if($adget_flag == 1)
									break;
								}
							}

							if($adget_flag == 1)
							break;

						}

						if($adget_flag == 1)
						break;
					}
				}


				if($stepValue == 2 || $adsResultCount > 0)
				{
					$stepTwoPass	= 1;

					if($adsResultCount == 0 || ($adsResultCount > 0 && $adsLimit > $adsResultCount))
					{
						if($adsResultCount > 0 && $adsLimit > $adsResultCount)
						{
							$balanceads	= $adsLimit - $adsResultCount;

							if(isset($displayArray['default'][$ad_container_type][0]) && $balanceads > 0)
							{

				               $defaultAdQueryStringReplace = $defaultAdQueryString;

				                $pricingCondition     = $displayArray['default'][$ad_container_type][0]["pricing-condition"];

				                $commonCondition      = $displayArray['default'][$ad_container_type][0]["common-condition"];

				                $limitCondition       = $displayArray['default'][$ad_container_type][0]["limit-condition"];


				                $defaultAdQueryStringReplace    = str_replace("{PRICING-CONDITION}"," AND a.display_type = ".$pricingCondition,$defaultAdQueryStringReplace);

				                $defaultAdQueryStringReplace    = str_replace("{COMMON-CONDITION}",$commonCondition,$defaultAdQueryStringReplace);

				                $defaultAdQueryStringReplace    = str_replace("{LIMIT-CONDITION}",$balanceads,$defaultAdQueryStringReplace);



								$resquery_default	= $db->execute_query($defaultAdQueryStringReplace);

								$defaultAdsResultCount	= $resquery_default->get_num_records();
							}
						}
						else if($adsResultCount == 0)
						{
							foreach($containerArray as $ckey => $cvalue)
							{
								$arrayDefault   = array();
								$arrayDefault[] = 0;

								foreach($arrayDefault as $akey => $avalue)
								{
									if(isset($displayArray['default'][$ckey][$avalue]))
									{
					                   $defaultAdQueryStringReplace = $defaultAdQueryString;

					                    $pricingCondition     = $displayArray['default'][$ckey][$avalue]["pricing-condition"];

					                    $commonCondition      = $displayArray['default'][$ckey][$avalue]["common-condition"];

					                    $limitCondition       = $displayArray['default'][$ckey][$avalue]["limit-condition"];


					                    $defaultAdQueryStringReplace    = str_replace("{PRICING-CONDITION}"," AND a.display_type = ".$pricingCondition,$defaultAdQueryStringReplace);

					                    $defaultAdQueryStringReplace    = str_replace("{COMMON-CONDITION}",$commonCondition,$defaultAdQueryStringReplace);

					                    $defaultAdQueryStringReplace    = str_replace("{LIMIT-CONDITION}",$limitCondition,$defaultAdQueryStringReplace);

										$resquery_default	 = $db->execute_query($defaultAdQueryStringReplace);
										$defaultAdsResultCount  = $resquery_default->get_num_records();

										if($defaultAdsResultCount > 0)
										{
											$ad_container_type	= $ckey;
											$display_type       = 0;
											$adget_flag         = 1;

											break;
										}
										else
										{
											/*
											$ckeyText = "";

											if($ckey == 1)
											$ckeyText = 'text';
											else if($ckey == 2)
											$ckeyText = 'banner';
											else if($ckey == 4)
											$ckeyText = 'textimage';
											


											if($ckeyText != "")
											$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET default_".$ckeyText."_ads = ? WHERE id = ?",array(time(),$feedID));
											*/
										}
									}

									if($adget_flag == 1)
									break;
								}

								if($adget_flag == 1)
								break;
							}
						}


						if($defaultAdsResultCount > 0)
						{
							$publisherRevenue	= 0;
							$defaultIncrement   = 0;
							while($addata = $resquery_default->fetch_assoc())
							{
								$banner_url = '';
								$icon_url   = '';

								if($ad_container_type == 2)
								$banner_url = $base_url.DATA_DIR.'/'.$addata['aid'].'_'.$addata['banner'];
								else if($ad_container_type == 11)
								$icon_url   = $base_url.DATA_DIR.'/'.$addata['aid'].'_'.$addata['banner'];

								$click_url  = $trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/click_default/'.$addata['aid'].'/'.$feedID;

								$pixel_url  = $trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression_default/'.$addata['aid'].'/'.$feedID;


					    		$this->adListArray[0][$addata['aid']] 				   = $addata['aid'];
					    		$this->adListArray[2][$addata['aid']]['count'] 		   = $defaultAdsResultCount;
					    		$this->adListArray[2][$addata['aid']]['default_rate']  = $publisherRevenue;
					    		$this->adListArray[2][$addata['aid']]['title'] 		   = $addata['title'];
					    		$this->adListArray[2][$addata['aid']]['description']   = $addata['description'];
					    		$this->adListArray[2][$addata['aid']]['display_url']   = $addata['display_url'];
					    		$this->adListArray[2][$addata['aid']]['click_url'] 	   = $click_url;
					    		$this->adListArray[2][$addata['aid']]['pixel_url'] 	   = $pixel_url;
								$this->adListArray[2][$addata['aid']]['banner_url']    = $banner_url;
								$this->adListArray[2][$addata['aid']]['icon_url'] 	   = $icon_url;


								$defaultIncrement++;

								if(($adsResultCount + $defaultIncrement) >= $adsLimit)
								break;

							}
						}
					}
				}


				if($adget_flag == 1 && ($stepTwoPass == 1 || ($adsResultCount+$defaultAdsResultCount) >= $adsLimit))
				break;
		}


		$this->totalAdGet = $adsResultCount + $defaultAdsResultCount;

		if($this->totalAdGet > $adsLimit)
		$this->totalAdGet = $adsLimit;


		if($this->totalAdGet > 0)
		{
			$adResultXML ='<?xml version="1.0" encoding="UTF-8"?>';
			$adResultXML.='<feed id ="'.$feedID.'">';
			$adResultXML.='<adnetwork>'.$admarket_name.'</adnetwork>';
			$adResultXML.='<results>';

			if($display_type == 0)
			$adResultXML.='<pricing>cpc</pricing>';
		    else if($display_type == 6)
			$adResultXML.='<pricing>cpa</pricing>';

			$adResultXML.='<count>'.intval($this->totalAdGet).'</count>';
			$adResultXML.='{LISTINGREPLACE}';
			$adResultXML.='</results>';
			$adResultXML.='</feed>';
		}
		else
		{
			$adResultXML ='<?xml version="1.0" encoding="UTF-8"?>';
			$adResultXML.='<feed id ="'.$feedID.'">';
			$adResultXML.='<adnetwork>'.$admarket_name.'</adnetwork>';
			$adResultXML.='<results>';
			$adResultXML.='No result found';
			$adResultXML.='</results>';
			$adResultXML.='</feed>';
		}

		$this->set_variable('xmldata',$adResultXML,0);

		$this->adListArray = json_encode($this->adListArray);

	}



	function before_render()
	{
		ob_clean();

		if($this->get_action() =='video')
		ob_start(array(&$this,"create_xml_cache"));
		else if($this->get_action() =='feed')
		ob_start(array(&$this,"create_feed_cache"));
		else
		ob_start(array(&$this,"create_cache"));
	}


	function pop_render_action()
	{
	    if(!MOD_REWRITE)
		$this->indexAppend = "index.php?page=";

		$adsLandingPage			= $this->mybase64_decode($this->read_page_param(1));//Ads landing page
		$admarketTrackParameter	= $this->mybase64_decode($this->read_page_param(2));//Admarket impression tracking
		$adsFrom3rdParty		= intval($this->read_page_param(3)); //If 1 => feed ads, 2 => exchange ads, 0 => normal pop
		$popCookieData	        = $this->mybase64_decode($this->read_page_param(4)); //POP cookie data
		$pixelURL3rdParty       = $this->mybase64_decode($this->read_page_param(5));
		//If any issue in img pixel tracking, need to receive pixel url as 5th param from ad display
		//Need to do $this->mybase64_decode($pixelURL3rdParty)

		$popImpressionTracking 	= intval(Configuration::get_instance()->read('pop_impression_tracking_using_js'));

		$trackDomain       = $this->get_track_domain();

		if($adsLandingPage != "" && $admarketTrackParameter == "")
		{
			if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
			file_get_contents($pixelURL3rdParty);

			header("Location:".$adsLandingPage);
		}
		else if($adsLandingPage == "" && $admarketTrackParameter == "")
		{
			if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
			file_get_contents($pixelURL3rdParty);

			header("Location:".BASE);
		}
		else if($admarketTrackParameter != "")
		{
			$originalAd 	= -1;
			$adcodeid 		= 0;
			$cookieDataGet 	= "";

			if($popCookieData != "")
			$cookieDataGet    = $popCookieData;

			$impressionStringUrl 		 = str_replace($trackDomain.TRACK_DIR."/".$this->indexAppend."action/","",$admarketTrackParameter);

			$impression_string_array = explode('/',$impressionStringUrl);

			if(isset($impression_string_array[0]) && $impression_string_array[0] == 'impression')
			{
				$originalAd = 1;

				$impression_string 		 = $impression_string_array[1];

				if($cookieDataGet != "")
				{
					$data_get_array_data	= explode('|',$impression_string);
					$adcodeid				= intval($data_get_array_data[4]);

					if($adcodeid == 0)
					{
						$admarketTrackParameter = "";

						if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
						file_get_contents($pixelURL3rdParty);
					}
					else
					{

						if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
						$admarketTrackParameter = $admarketTrackParameter."/".$this->mybase64_encode($pixelURL3rdParty);
					}
				}
			}
			else if(isset($impression_string_array[0]) && $impression_string_array[0] == 'impression_default')
			$originalAd = 0;

			if($popImpressionTracking == 1){?>
			<script data-cfasync="false" type="text/javascript">
			
			var script=document.createElement('script');
				script.setAttribute("data-cfasync","false");
				script.type = "text/javascript";
			    script.async=1;
				script.src="<?php echo $admarketTrackParameter;?>";
				document.getElementsByTagName('head')[0].appendChild(script);

				setTimeout(function(){ window.location.href="<?php echo $adsLandingPage;?>"; }, 1000);
				</script>
			<?php 
				if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
				file_get_contents($pixelURL3rdParty);							
			}else{
				
					if($originalAd == 1)
					{
						$data_get_string = trim($impression_string_array[1]);
						$data_md5		 = $impression_string_array[2];
						$come_time		 = $impression_string_array[3];
						$country		 = $impression_string_array[4];

						if($cookieDataGet !== 0 && $cookieDataGet != "")
						$cookieDataGet 	 = $this->mybase64_decode($cookieDataGet);
						else
						$cookieDataGet	 = "";

						if($data_get_string == "")
						{
							//if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $adsFrom3rdParty != "")
							//file_get_contents($adsFrom3rdParty);

							if($adsLandingPage != "")
							header("Location:".$adsLandingPage);
							else 
							header("Location:".BASE);
							
							exit;
						}

						$data_get_array=explode('.data.',$data_get_string);
						$data_get_array_count=count($data_get_array);

						$adcodeid = 0;
						$adtype	  = 0;
						for($i=0;$i < $data_get_array_count;$i++)
						{
							$data_get_array_data = explode('|',$data_get_array[$i]);
							$adcodeid            = $data_get_array_data[4];
							$adtype              = $data_get_array_data[7];
							break;
						}

						$maxImpressionLimit = intval(Configuration::get_instance()->read('pop_ad_impression_limit_hour'));

						$requestIP		    = UtilityHelper::get_user_ip();
						$requestUserAgent 	= $_SERVER['HTTP_USER_AGENT'];

						$md5_data_string    = $requestIP.$requestUserAgent.date('d',time()).date('H',time());
						$current_md5        = md5($data_get_string.Configuration::get_instance()->read('admarket_name').$md5_data_string.$come_time.$country);

					if($data_md5 == $current_md5 && time() < $come_time)
					{
							$impression_updation = 0;
							if($maxImpressionLimit > 0)
							{
								$data_get_array       = explode('.data.',$data_get_string);
								$data_get_array_count = count($data_get_array);

								for($i=0;$i < $data_get_array_count;$i++)
								{
									$data_single_array = explode('|',$data_get_array[$i]);
									$aid               = $data_single_array[1];

									if($cookieDataGet != '')
									{
										$cookieFlag      = 0;
										$capCookieArray  = explode('_',$cookieDataGet);

										$capTimeCurrent = time();

										foreach($capCookieArray as $capKey => $capValue)
										{
												if(trim($capValue) != "")
												{
														$capValueArray = explode('-',$capValue);

														if($capValueArray[2] > $capTimeCurrent)
														{
																if($capValueArray[0] == $aid && $capValueArray[1] < $maxImpressionLimit)
																{
																		$impression_updation = 1;
																		$cookieFlag          = 1;
																}
																else
																{
																		if($capValueArray[0] == $aid)
																		$cookieFlag      = 1;
																}
														}

														if($capValueArray[0] == $aid && $capValueArray[1] >= $maxImpressionLimit && $capValueArray[2] >= $capTimeCurrent)
														unset($data_get_array[$i]);
												}
										}

										if($cookieFlag == 0)
										$impression_updation = 1;
									}
									else
									$impression_updation   = 1;
								}

								if(count($data_get_array) > 0)
								$data_get_string         = implode('.data.',$data_get_array);
								else
								{
									$data_get_string     = "";
									$impression_updation = 0;
								}
						}
						else
						$impression_updation       = 1;

						if($impression_updation ==1)
							$this->ImpressionUpdate($data_get_string,$requestIP,$country,$maxImpressionLimit, $adsFrom3rdParty);
					}

					if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
					file_get_contents($pixelURL3rdParty);

				}
				else if($originalAd == 0)
				{
					$db		  = DAL::get_instance();
					$adID	  = intval($impression_string_array[1]);
					$adcodeID = intval($impression_string_array[2]);

						$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET default_ad_display_time = ? WHERE id = ?",array(time(),$adcodeID));
					}
				

				if($adsLandingPage != "")
				header("Location:".$adsLandingPage);
				else 
				header("Location:".BASE);
			}
		}
		exit;
	}


	function track_directlink_action()
	{
		if(!MOD_REWRITE)
		$this->indexAppend = "index.php?page=";
		
		$adsLandingPage			= $this->mybase64_decode($this->read_page_param(1)); //Ads landing page
		$admarketTrackParameter	= $this->mybase64_decode($this->read_page_param(2)); //Admarket impression tracking
		$adsFrom3rdParty		= intval($this->read_page_param(3)); //If 1 => feed ads, 2 => exchange ads, 0 => normal ads
		$pixelURL3rdParty       = $this->mybase64_decode($this->read_page_param(4));//If any issue in img pixel tracking, need to receive pixel url as 4th param from ad display
		//Need to do $this->mybase64_decode($pixelURL3rdParty)

		$trackDomain            = $this->get_track_domain();
		
		if($adsLandingPage != "" && $admarketTrackParameter == "")
		{
			//If img pixel tracking failed, need to pass pixel url and use below code.
			if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
			file_get_contents($pixelURL3rdParty);
		
			header("Location:".$adsLandingPage);
		}
		else if($adsLandingPage == "" && $admarketTrackParameter == "")
		{
			if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
			file_get_contents($pixelURL3rdParty);
		
			header("Location:".BASE);
		}
		else if($admarketTrackParameter != "")
		{
			
				$originalAd 	= -1;
			
				$impressionStringUrl     = str_replace($trackDomain.TRACK_DIR."/".$this->indexAppend."action/","",$admarketTrackParameter);
				$impression_string_array = explode('/',$impressionStringUrl);
			
				if(isset($impression_string_array[0]) && $impression_string_array[0] == 'impression')
				{
					$originalAd        = 1;
					$impression_string = $impression_string_array[1];
				}
				else if(isset($impression_string_array[0]) && $impression_string_array[0] == 'impression_default')
				$originalAd = 0;
			
				if($originalAd == 1)
				{
					$data_get_string = trim($impression_string_array[1]);
					$data_md5		 = $impression_string_array[2];
					$come_time		 = $impression_string_array[3];
					$country		 = $impression_string_array[4];
			
					if($data_get_string == "")
					{
						if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
						file_get_contents($pixelURL3rdParty);
			
						if($adsLandingPage != "")
						header("Location:".$adsLandingPage);
						else 
						header("Location:".BASE);
			
						exit;
					}
			
					$data_get_array       = explode('.data.',$data_get_string);
					$data_get_array_count = count($data_get_array);
			
					$aid      = 0;
					$adtype	  = 0;
					for($i=0; $i < $data_get_array_count; $i++)
					{
						$data_get_array_data = explode('|',$data_get_array[$i]);
						$aid               = $data_get_array_data[1];
						$adtype            = $data_get_array_data[7];
						break;
					}
			
					$requestIP		    = UtilityHelper::get_user_ip();
					$requestUserAgent 	= $_SERVER['HTTP_USER_AGENT'];
					$maxImpressionLimit = 0;
			
					if($adtype == 1) //CPM
					{
						$cookie_name        = "_data_cpm";
						$maxImpressionLimit = intval(Configuration::get_instance()->read('cpm_ad_impression_limit_hour'));
						$capInterval        = intval(Configuration::get_instance()->read('cpm_interval'));
					}					

					$md5_data_string    = $requestIP.$requestUserAgent.date('d',time()).date('H',time());
					$current_md5        = md5($data_get_string.Configuration::get_instance()->read('admarket_name').$md5_data_string.$come_time.$country);
			
					$capTimeCurrent       = time();
					$newElementExpiry     = $capTimeCurrent + ($capInterval * 60 * 60);

					if($data_md5 == $current_md5 && time() < $come_time)
					{
						$impression_updation = 0;
						if($maxImpressionLimit > 0)
						{
							$capCookieTemp 		= array();
							$capCookieContent   = "";
							$cookieDataGet      = $_COOKIE[$cookie_name];	

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

							if(count($capCookieTemp) > 0)
							$capCookieContent    = implode("_",$capCookieTemp);
							
							setcookie($cookie_name, $capCookieContent, 0, "/"); 

							if(count($data_get_array) == 0)
							$impression_updation = 0;
						}
						else
						$impression_updation = 1;
			
						if($impression_updation == 1)		
						$this->ImpressionUpdate($data_get_string, $requestIP, $country, $maxImpressionLimit, $adsFrom3rdParty);
					}
			
					if(($adsFrom3rdParty == 1 || $adsFrom3rdParty == 2) && $pixelURL3rdParty != "")
					file_get_contents($pixelURL3rdParty);
				}
				else if($originalAd == 0)
				{
					$db		  = DAL::get_instance();
					$adID	  = intval($impression_string_array[1]);
					$adcodeID = intval($impression_string_array[2]);
			
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET default_ad_display_time = ? WHERE id = ?",array(time(),$adcodeID));
				}
			
			
			if($adsLandingPage != "")
			header("Location:".$adsLandingPage);
			else 
			header("Location:".BASE);			
		}
		exit;
	}

	

	function ImpressionUpdate($data_get_string, $currentipaddress, $country, $maxImpressionLimit, $sourceType)
	{
		$time=time();

		$impTime =date("Y",time());
		$impTime.=date("m",time());
		$impTime.=date("d",time());
		$impTime.=date("H",time());

		$impTimeMinute = $impTime.date("i",time());
		$impTimeSecond = $impTimeMinute.date("s",time());

		$db = DAL::get_instance();

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
			$publisherRid=0;
			$arefperc=0;
			$prefperc=0;
			
			if($adtype ==1)
			{
				$cpm_impression=1;
				$cpm_profit=$data_get_array_data[10];
				$cpm_spend=$data_get_array_data[11];
			}

			if($adtype ==9)
			{
				$pop_impression=1;
				$pop_profit=$data_get_array_data[10];
				$pop_spend=$data_get_array_data[11];
			}

			if($adtype ==1 || $adtype == 9)
			{
				$rid=intval($data_get_array_data[12]);
				$publisherRid=intval($data_get_array_data[13]);
		    
				$impfile=$aid.'_'.$impTime.'.php';

				$ip_string="";
				$cpm_ip_array=array();
				$cpm_ip_array_string="";
				$impression_updation=0;

				if($maxImpressionLimit > 0)
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
				$insert_data[]=$publisherRid;
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

	function video_action()
	{
		$vast_request_origin = "*";
		$display_site        = "";
		if(!empty($_SERVER['HTTP_ORIGIN']))
		{
			$vast_request_origin = $_SERVER['HTTP_ORIGIN'];
			$display_site = preg_replace('/^www\./i', '', $_SERVER['HTTP_ORIGIN']);  
			$display_site = preg_replace('/^https:\/\//i', '', $display_site);  
			$display_site = preg_replace('/^http:\/\//i', '', $display_site);  
		}
		header("Content-type: text/xml; charset=utf-8");
		header("Access-Control-Allow-Origin: ".$vast_request_origin);
		if($vast_request_origin != "*")
		header("Access-Control-Allow-Credentials: true");
	    if(!MOD_REWRITE)
	    $this->indexAppend = "index.php?page=";


    	$queryReplacementArray          = array();
 		$queryDefaultReplacementArray   = array();
		$restrictedSiteArray            = array();
	    $displayArray					= array();
		$containerArray 		        = array();
		$language                       = array();
		$languageArray                  = array();
		$categorySupportedPricing 		= array();
		$adDisplayStep			   		= array();


    	$adResultXML 			= "";
		$cacheCity 				= "";
		$cacheDevice 			= "";
		$sitename 				= "";
		$isp 					= "";
		$connection 			= "";
		$osname 				= "";
		$browsername 			= "";

		$siteRestrictionCondition      = "";
		$sameAccountAdDisplayCondition = "";

		$ispJoin 	  			= "";
        $countryJoinCondition	= "";

	    $deviceCondition   		= "";
	    $osCondition 	   		= "";
	    $browserCondition  		= "";
		$dateCondition 			= "";
		$countryCondition 		= "";
		$referralColumns 		= "";
		$ispCondition 			= "";
		$categoryCondition   	= "";
		$connectionCondition 	= "";
		$languageCondition   	= "";

		$cityid    				= 0;
		$sid       				= 0;
		$catid     				= 0;
		$ispid     				= 0;
		$conn_id   				= 0;
		$osid      				= 0;
		$browserid 				= 0;
		$publisherRid           = 0;
		$cpm_profit_percentage  = 0;
		$cpv_profit_percentage  = 0;
		$isp_enabled        	= 0;
		$connection_enabled 	= 0;
		$cachedFlag          	= 0;
		$video_banner_width  	= 0;
		$video_banner_height 	= 0;
		$adv_ref_enabled 		= 0;
		$pub_ref_enabled 		= 0;
		$ppctrack     			= 0;
		$cpmtrack     			= 0;
		$cpatrack     			= 0;
		$videotrack   			= 0;
		$dateFlag     			= 0;
		$invalidSiteFlag = 0; //jesus

		$exchangeQueryLimit 	= 5; 
		$exchangeGetSuccess    = 0;
		$trackDomain       = $this->get_track_domain();
		$this->trackDomain = $trackDomain;


		$requestIP         = UtilityHelper::get_user_ip();
		$requestUserAgent  = $_SERVER['HTTP_USER_AGENT'];

		$adunitid    = intval($this->read_page_param(1));
		$publisher   = intval($this->read_page_param(2));


		$this->adcodeID	= $adunitid;


        $this->cacheIP 			= $requestIP;
        $this->cacheUserAgent   = $requestUserAgent;
        $this->cacheDate 		= date('d',time()).date('H',time());

		$encryptedIP 			= md5($requestIP);

		$this->cacheEncryptedIP=$encryptedIP;

		if(Configuration::get_instance()->read('proxy_detection_for_ad_display') ==1)
		{
			if(UtilityHelper::proxyDetection())
			die;
		}


		$cpc_enabled 				= $this->get_addon_status('cpc_enabled');
		$cpm_enabled 				= $this->get_addon_status('cpm_enabled');
		$cpa_enabled 				= $this->get_addon_status('cpa_enabled');
		$video_enabled 				= $this->get_addon_status('video-ads_enabled');
		$category_targeting_enabled = $this->get_addon_status('category-targeting_enabled');
		$device_targeting_enabled 	= $this->get_addon_status('device-targeting_enabled');
		$city_enabled 				= $this->get_addon_status('city-targeting_enabled');
		$time_targeting_enabled 	= $this->get_addon_status('time-targeting_enabled');
		$language_targeting_enabled = $this->get_addon_status('language-targeting_enabled');
		$isp_connection_enabled 	= $this->get_addon_status('isp-connection-targeting_enabled');
		$referral_enabled 			= $this->get_addon_status('referral_enabled');
		$dspconnector_enabled       = $this->get_addon_status('dsp-connector_enabled');

		if($dspconnector_enabled == 1)
		$exchangeQueryLimit = Configuration::get_instance()->read('dsp_exchange_query_limit');
		if($isp_connection_enabled == 1)
		{
			$isp_enabled        	= $this->get_addon_status('isp-targeting_enabled');
			$connection_enabled 	= $this->get_addon_status('connectiontype-targeting_enabled');
		}


		if($city_enabled == 1)
		{
			$record =UtilityHelper::get_geo_details_from_ip($requestIP,1);
			$country=$record->country_code;
			$subdivision1=$record->subdivision1;
			$subdivision2=$record->subdivision2;
			$cityname=$record->city;
			$latitude=$record->latitude;
			$longitude=$record->longitude;
			$cityid=$record->cityid;

			$cacheCity = $subdivision1.$subdivision2.$cityid;
		}
		else
		{
			$country = UtilityHelper::get_country_from_ip($requestIP);
		}

		$countryResultArray = json_decode(COUNTRY_RESULT_JSON, 1);
        	if(isset($countryResultArray[$country]))
		$countryAlpha3 = $countryResultArray[$country];

		$this->geo_country = $country;


		if($isp_enabled ==1 || $connection_enabled ==1)
		{
			try
			{
			    $db1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);

				$dbip = new DBIP_MySQLI($db1);

				$ipLookUpInfo = $dbip->Lookup($requestIP,TABLE_PREFIX.'dbip_lookup');
				$isp= $ipLookUpInfo->isp_name ;
				$connection=$ipLookUpInfo->connection_type ;
				$cacheDevice=$cacheDevice.$isp.$connection;
			}
			catch (DBIP_Exception $e)
			{

			}
		}


		if($device_targeting_enabled ==1)
		{

			$browserClass   = new foroco\BrowserDetection();
			$browserResult  = $browserClass->getAll($requestUserAgent);
			$deviceType = $browserResult['device_type'];

			if($deviceType =='desktop')
			$cacheDevice='c';
			else if($deviceType =='mobile')
			$cacheDevice='m';


			$os_targeting_enabled       = Configuration::get_instance()->read('os-targeting_enabled');
		    $browser_targeting_enabled  = Configuration::get_instance()->read('browser-targeting_enabled');


			if($os_targeting_enabled == 1)
			{
				$osname=$browserResult['os_name'];
				$cacheDevice=$cacheDevice.$osname;
			}

			if($browser_targeting_enabled == 1)
			{
				$browsername=$browserResult['browser_name'];
				$cacheDevice=$cacheDevice.$browsername;
			}
		}


		if($language_targeting_enabled == 1)
		{
			if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			{
				$http_accept=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
				$x = explode(",",$http_accept);

				foreach ($x as $val)
				{
					$language[] ="'". substr($val, 0, 2)."'";
				}

				$languageArray=array_unique($language);
			}
		}


		$normalAdsCache = $adunitid.$country.$cacheCity.$cacheDevice.'1'; // RON Ads
		$normalAdsCache = $adunitid."-".md5($normalAdsCache).".php";


		$this->cacheFileName = $normalAdsCache;


		include(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);

		if($cachedFlag ==1)
		die;
		else if($cachedFlag ==2)
		unlink(ROOT_DIR_PATH.CACHE_DIR."/cache/".$normalAdsCache);


		$db= DAL::get_instance();


		$res=$db->execute_query("select au.*,au.id as auid from ".TABLE_PREFIX."adunit au where au.id=?",array($adunitid));
		$adcodeCount  = intval($res->get_num_records());

		$result       = $res->fetch_assoc();

		$display_type = intval($result['display_type']);
		$pid          = intval($result['pubid']);
		$adcodeType   = intval($result['adcode_type']);
		$vastSkipInterval = 0;


		if($pid != $publisher)
		die;



		$linear            = intval($result['linear']);
		$non_linear_text   = intval($result['non_linear_text']);
		$non_linear_banner = intval($result['non_linear_banner']);

		$nonlinear_support = intval(Configuration::get_instance()->read('vast_player_nonlinear_support'));

		if($nonlinear_support == 0)
		{
			$non_linear_text=0;
			$non_linear_banner=0;
		}

		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');


		if($linear == 0)
		exit;

		if($non_linear_text == 1 && $text_ads_enabled == 0)
		$non_linear_text = 0;

		if($adcodeCount == 0)
		exit(0);

		if(
			($cpm_enabled != 1 && $adcodeType == 13) || 

			($video_enabled != 1 && $adcodeType == 13) || 
			($adcodeType != 13) || 
			($result['video_type'] != 1)
		)
		die;


		if($non_linear_banner ==1)
		{
			$imageSupport = intval($this->get_banner_dimension_support($result['player_size'],13));

			if($imageSupport == 0)
			$non_linear_banner=0;

			if($non_linear_banner ==1)
			{
				$player_size       = $this->get_banner_dimension($result['player_size']);
				$player_size_array = explode('-',$player_size);

				if(isset($player_size_array[0]))
				{
					$video_banner_width  = $player_size_array[0];
					$video_banner_height = $player_size_array[1];
				}
			}
		}


		$this->cpc_enabled_value                      = $cpc_enabled;
		$this->cpm_enabled_value                      = $cpm_enabled;
		$this->cpa_enabled_value                      = $cpa_enabled;
		$this->device_enabled_value                   = $device_targeting_enabled;
		$this->referral_enabled_value  				  = $referral_enabled;
		$this->video_enabled_value                    = $video_enabled;
		$this->language_targeting_enabled_value       = $language_targeting_enabled;
		$this->isp_targeting_enabled_value            = $isp_enabled;
		$this->connectiontype_targeting_enabled_value = $connection_enabled;


		if($referral_enabled ==1)
		{
			$adv_ref_enabled=Configuration::get_instance()->read('advertiser_referral_enabled');
			$pub_ref_enabled=Configuration::get_instance()->read('publisher_referral_enabled');
		}


		$this->geo_tracking_enabled=intval(Configuration::get_instance()->read('countrywise_data_tracking'));


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


		$this->ppc_tracking_interval   = $ppctrack;
		$this->cpa_tracking_interval   = $cpatrack;
		$this->cpm_tracking_interval   = $cpmtrack;
		$this->video_tracking_interval = $videotrack;


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

		if($category_targeting_enabled ==1)
		{
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

			if($category_enabled_ads !='')
			$categorySupportedPricing = explode('_',$category_enabled_ads);
		}

		/*************** For Site Restriction **************/
		$siteCheckingPricing = $display_type;
		$IABCategoryArray    = array();
		$IABCategoryString   = "";
		if($category_targeting_enabled == 1)
		{
			$sid = intval($result['sid']);

			if($sid >0)
			{
				if($dspconnector_enabled == 1)
          		$IABCategoryString = ",iab_category_id";
				$siterow=$db->execute_query("SELECT catid,url,protocol".$IABCategoryString." FROM ".TABLE_PREFIX."sites WHERE id=? AND status=1",array($sid));
				$sitedata=$siterow->fetch_assoc();

				$catid    = $sitedata['catid'];
				$sitename = strtolower($sitedata['url']);
				if(isset($sitedata['iab_category_id']) && $sitedata['iab_category_id'] != "")
				$IABCategoryArray = explode(",", $sitedata['iab_category_id']);
				if(in_array($siteCheckingPricing, $categorySupportedPricing))
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
								$invalidSiteFlag	= 1;
							}
							else
							$site_checked=1;
						}
					}
					/************ Modification For Support blogspot.com/blogspot.in etc ***********/
					if($display_site != $sitename && $site_checked ==0 && $invalidSiteFlag == 0)
					{
						$sitelength=strlen($sitename)+1;
						$displaysub=substr($display_site,-$sitelength);
						if($displaysub != '.'.$sitename)
						{
							$invalidSiteFlag	= 1;
						}
					}
				}
			}
		}
		/*************** For Site Restriction **************/


		if(DEMO_MODE)
		$invalidSiteFlag = 0; //jesus
		
		if($invalidSiteFlag == 1)
		die;
    /*************** For device Restriction **************/

    if($device_targeting_enabled ==1)
    {
      $deviceCondition.=' AND ( ac.device = 2 ';

      if($deviceType == 'desktop')
      {
        if($deviceCondition != '')
        $deviceCondition.=' OR ';

        $deviceCondition.=' ac.device = 0  ';
      }

      if($deviceType == 'mobile')
      {
        if($deviceCondition != '')
        $deviceCondition.=' OR ';

        $deviceCondition.=' ac.device = 1 ';
      }

      $deviceCondition.=' ) ';


      if($os_targeting_enabled==1)
      {
        $osid = intval($db->read_single_column("select id from ".TABLE_PREFIX."os where name=?",array($osname)));

        $osCondition = ' AND (';

        if($osid > 0)
        $osCondition.=' ac.'.$osid.'_os = 1 OR ';

        $osCondition.=' ac.all_os = 1 )';
      }


      if($browser_targeting_enabled == 1)
      {
        $browserid = intval($db->read_single_column("select id from ".TABLE_PREFIX."browser where name=?",array($browsername)));

        $browserCondition.=' AND (';

        if($browserid > 0)
        $browserCondition.=' ac.'.$browserid.'_browser = 1 OR ';

        $browserCondition.=' ac.all_browser = 1 )';
      }
    }
    /*************** For device Restriction **************/


    if($time_targeting_enabled == 1)
    {
        $datetime  = time();

        $date_filter_enabled = Configuration::get_instance()->read('date_filter_enabled');
        $time_filter_enabled = Configuration::get_instance()->read('time_filter_enabled');
        $day_filter_enabled  = Configuration::get_instance()->read('day_filter_enabled');


        if($date_filter_enabled == 1 || $time_filter_enabled == 1 || $day_filter_enabled == 1)
        {
            $dateCondition.=' AND (';

            if($date_filter_enabled == 1)
            {
                $dateCondition.=' (ac.date_filter =0 OR (ac.date_filter =1 AND ac.tmt_start_date <='.$datetime.' AND ac.tmt_end_date >='.$datetime.')) ';
                $dateFlag=1;
            }

            if($time_filter_enabled == 1)
            {
                $datehour=date('G',time());

                if($dateFlag == 1)
                $dateCondition.=' AND ';

                $dateCondition.=' (ac.time_filter = 0 OR (ac.time_filter = 1 AND ac.'.$datehour.'_hour = 1)) ';

                $dateFlag = 1;
            }

            if($day_filter_enabled == 1)
            {
                $dateday=date('w',time());

                if($dateday == 0)
                $dateday = 7;

                if($dateFlag == 1)
                $dateCondition.=' AND ';

                $dateCondition.=' (ac.day_filter = 0 OR (ac.day_filter = 1 AND ac.'.$dateday.'_day = 1)) ';
            }

            $dateCondition.=')';
        }
    }



	if($pid >0 && Configuration::get_instance()->read('allow_publishers_to_display_their_own_ads') ==0)
	{
		$sameAccountAdDisplayCondition = " AND ac.uid <> ".$pid." ";
	}



		if($pid > 0)
		{
			$pquery_res = $db->execute_query("SELECT * FROM " . TABLE_PREFIX . "users WHERE id=?",array ($pid));
			$query_result = $pquery_res->fetch_assoc();


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
			$publisherRid = intval($query_result ['rid']);
			$site_json=$query_result['restricted_sites'];
			$vast_adcode_enabled=intval($query_result['vast_adcode_enabled']);

			if($vast_adcode_enabled ==0)
			exit;

			if($site_json != '')
			$restrictedSiteArray = json_decode($site_json);
			foreach ($restrictedSiteArray as $value)
			{
				if($value != "")
				$siteRestrictionCondition.= " AND a.click_url not like '%" . $db->sanitize($value) . "%' ";

			}
		}




		if($referral_enabled ==1 && $adv_ref_enabled ==1)
		$referralColumns = ",ac.refferal_id as rid,ac.refferal_status ";

		$keywordColumns  = ",0 as keyid,0 as kid ";

		$rotation_variable = Configuration::get_instance()->read('ad_rotation');
		$countrywise_pricing_enabled = Configuration::get_instance()->read('countrywise_pricing_enabled');
        $countryFieldSelect          = "";
        if($countrywise_pricing_enabled == 1)
		$decoded_array = json_decode(Configuration::get_instance()->read('countrywise_minimum_price'),1);
		else
		$decoded_array = array();

		if($dspconnector_enabled == 1)
		$dspColumns  = ",a.dsp_endpoint, a.dsp_request_headers,a.dsp_auction_type, ac.dsp_rate_from";
		$cpvColumns        = ",a.mime_type,ac.video_width,ac.video_height,ac.bitrate,a.duration,ac.duration_seconds";
		$cpvDefaultColumns = ",a.mime_type,a.video_width,a.video_height,a.bitrate,a.duration,a.duration_seconds";
 		$tableColumnsAppend = $referralColumns.$cpvColumns.$dspColumns;
	    if($rotation_variable ==1)
	    {
	      if(time() % 5 == 0)
	      $rotation_variable = 0;
	    }


			$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

			$premiumString = "";
			if($premium_ad_enabled == 1)
			$premiumString = " ac.premium_ad DESC,";

	    if($rotation_variable ==1)
	    {
		    if($countrywise_pricing_enabled == 1)
		    {
		   		$normalAdRotation = " ORDER BY ".$premiumString."g.price DESC,ac.default_rate DESC,random ASC ";
		   		$cpaAdRotation	  = " ORDER BY ".$premiumString."ac.tracking_last_checked DESC,g.price DESC,ac.default_rate DESC,random ASC";
		    }
		    else 
		    {
	      $normalAdRotation = " ORDER BY ".$premiumString."ac.default_rate DESC,random ASC ";
	      $cpaAdRotation    = " ORDER BY ".$premiumString."ac.tracking_last_checked DESC,ac.default_rate DESC,random ASC";
	    }
	    }
	    else
	    {
            $normalAdRotation = " ORDER BY ".$premiumString."random ASC ";
            $cpaAdRotation    = " ORDER BY ".$premiumString."random ASC ";
	    }


	    $adStatusCondition       	= " AND ac.status = 1 ";
	    $userStatusCondition     	= " AND ac.user_status = 1 ";
	    $adPauseStatusCondition  	= " AND ac.pause_status = 0 ";
	    $defaultAdStatusCheck       = " AND a.status = 1 ";
	    $defaultAdPauseStatusCheck  = " AND a.pause_status = 0 ";
        $CPVBudgetCondition = "";
		$CPVCountryPrice    = 0;
		if($countrywise_pricing_enabled == 1)
		{
            if($country != "")
            {
                if(isset($decoded_array[$country]['cpv']) && $decoded_array[$country]['cpv'] > 0)
                $CPVCountryPrice = $decoded_array[$country]['cpv'];
            }
            if($CPVCountryPrice > 0)
            {
                $CPVBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= g.price)) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + g.price)
                ))
                OR
                (g.price = 0 AND (".$CPVCountryPrice." >= ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ".$CPVCountryPrice.")) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ".$CPVCountryPrice.")
                ))
                OR
                (g.price = 0 AND (".$CPVCountryPrice." < ac.default_rate) AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ac.default_rate)) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate)
                ))
                ) ";
            }
            else
            {
                $CPVBudgetCondition = " AND ((g.price > 0 AND (((ac.daily_budget >0 AND (ac.daily_budget - ac.daily_budget_used >= g.price)) OR ac.daily_budget =0) AND (ac.total_ad_budget >= ac.total_budget_used + g.price)
                ))
                OR
                (g.price = 0 AND (((ac.daily_budget > 0 AND (ac.daily_budget - ac.daily_budget_used >= ac.default_rate)) OR ac.daily_budget = 0) AND (ac.total_ad_budget >= ac.total_budget_used + ac.default_rate)
                ))
                ) ";
            }
		}
	    $budgetCondition      		= " AND ac.budget_available = 1 ";
	    $dailyBudgetCondition 		= " AND ac.daily_budget_available = 1 ";

		/********************************** Geographic Query *******************************/

		$countryFieldSelect   = " ,ac.all_countries ";

	    if($city_enabled ==1)
	    {
		      $countryJoin = TABLE_PREFIX."ad_geographic_mapping g,";

		      $countryJoinCondition = " a.id = g.aid ";
            $countryFieldSelect.= " ,g.price ";

		      $countryCondition =" AND (";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='".$subdivision2."') AND (g.city=".$cityid.")) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='".$subdivision2."') AND (g.city=0)) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='0') AND (g.city=".$cityid.")) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='".$subdivision1."') AND (g.subdivision2='0') AND (g.city=0)) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=".$cityid.")) OR ";

		      $countryCondition.=" ((g.country_code='".$country."') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=0)) OR ";

		      $countryCondition.=" ((g.country_code='0') AND (g.subdivision1='0') AND (g.subdivision2='0') AND (g.city=0)) ";

		      $countryCondition.=" )";
	    }
	    else
	    {
			if($countrywise_pricing_enabled == 1)
			{
				$countryJoin          = TABLE_PREFIX."ad_geographic_mapping g,";
				$countryJoinCondition = " a.id = g.aid ";
				$countryFieldSelect.= " ,g.price ";
                $countryCondition=" AND (";
                if($country != "")
				$countryCondition.=" (g.country_code='".$country."') OR ";

				$countryCondition.=" (g.country_code = '0')) ";
	    }
	    else
	    {
	          $countryCondition=" ( ";

	          if($country != "")
	          $countryCondition.=" ac.".$country."_country = 1 OR ";
	                $countryCondition.=" ac.all_countries = 1 ) ";
	    }
		}
		/********************************** Geographic Query *******************************/


		/********************************** Category Targeting ********************************/
		if($catid != "" && $catid != 0 && in_array($siteCheckingPricing, $categorySupportedPricing))
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


			    $categoryCondition = ' AND ( ';

			    if($category_string != '')
			    {
			        $categoryArray = explode(",",$category_string);

			        foreach($categoryArray as $key => $value)
			        {
			          	$categoryCondition.=' ac.'.intval($value).'_category = 1 OR ';
			        }
			    }
			    $categoryCondition.=' ac.all_categories = 1 )';
    	}
		/********************************** Category Targeting ********************************/

		/************************ ISP & Connection Type Targeting *********************/

	    if($isp_enabled ==1 || $connection_enabled ==1)
	    {
		      if($isp !='' && $isp_enabled ==1)
		      $ispid = intval($db->read_single_column("select id from ".TABLE_PREFIX."isp where name=? and country=?",array($isp,$country)));

		      if($connection !='' && $connection_enabled ==1)
		      $conn_id = intval($db->read_single_column("select id from ".TABLE_PREFIX."connection where name=? ",array($connection)));


		      if($isp_enabled == 1)
		      {
			        $ispJoin = ' LEFT OUTER JOIN '.TABLE_PREFIX.'ad_isp_mapping im ON a.id = im.aid ';

			        $ispCondition.=' AND (';

			        if($ispid > 0)
			        $ispCondition.=' ( im.ispid ='.$ispid.' OR im.ispid IS NULL OR im.ispid=0) ';
			        else
			        $ispCondition.=' ( im.ispid IS NULL OR  im.ispid=0)';

			          $ispCondition.=' )';
		      }



		      if($connection_enabled == 1)
		      {
			        $connectionCondition.=' AND (';

			        if($conn_id > 0)
			        $connectionCondition.='ac.'.$conn_id.'_connection = 1 OR';

			        $connectionCondition.=' ac.all_connections = 1';

			        $connectionCondition.=' )';
		      }
	    }
		/********************* ISP & Connection Type Targeting  ***********************/

		/****************** Language Targeting *****************************/


	    if($language_targeting_enabled==1)
	    {
		    if(count($languageArray)>0)
		    {
			$languageValueString = '';

	               	foreach($languageArray as $lkey => $lvalue)
	               	{
	               	    $languageArray[$lkey] = str_replace("'","", $lvalue);

			    if($languageValueString !='')
	                    $languageValueString.=',';

	               	    $languageValueString.='?';
	                }

			$language_data=$db->execute_query("select id from ".TABLE_PREFIX."language where code in (".$languageValueString.")",$languageArray);


		        if($language_data->get_num_records() > 0)
		        {
		          $languageCondition.= ' AND ( ';

		          while ($brdata=$language_data->fetch_assoc())
		          {
                        $languageCondition.= ' ac.'.intval($brdata['id']).'_language = 1 OR ';
		          }
		          $languageCondition.='ac.all_languages = 1 )';

		        }
		    }
	    }

		/***********************Language Targeting **************************/


		$adQueryString = "SELECT 0 AS retargetid,0 as retarget,ac.id as aid,ac.default_rate,ac.uid as userid, a.title,a.description,a.cta_button_text,a.display_url,a.click_url, a.banner,ac.display_type,ac.type".$tableColumnsAppend.$keywordColumns.",rand() as random
		FROM ".$countryJoin." ".TABLE_PREFIX."ads a
		".$ispJoin."
		INNER JOIN ".TABLE_PREFIX."ads_cache ac ON ac.id = a.id
		WHERE
		      ".$countryJoinCondition."
		      ".$countryCondition."
		      ".$categoryCondition."
		      ".$deviceCondition."
		      ".$dateCondition."
		      ".$osCondition."
		      ".$browserCondition."
		      ".$languageCondition."
		      ".$ispCondition."
		      ".$connectionCondition."
		      AND ac.pricing_status = 1

		      {PRICING-CONDITION}
		      {COMMON-CONDITION}

		    ".$adStatusCondition."
		    ".$adPauseStatusCondition."
		    ".$userStatusCondition."
		    ".$sameAccountAdDisplayCondition."
		    ".$siteRestrictionCondition."
		    ".$budgetCondition."

		    {DAILY-BUDGET-CONDITION}
		    {AD-ROTATION-CONDITION}

		    LIMIT 0,{LIMIT-CONDITION}";



        $defaultAdQueryString = "SELECT a.id as aid, a.title, a.description,a.cta_button_text, a.display_url, a.click_url,a.banner,a.type,a.display_type".$cpvDefaultColumns.",rand() as random
        FROM ".TABLE_PREFIX."ads a
        WHERE a.uid = 0
        ".$defaultAdStatusCheck."
        ".$defaultAdPauseStatusCheck."

        {PRICING-CONDITION}
        {COMMON-CONDITION}

        ORDER BY random ASC
        LIMIT 0,{LIMIT-CONDITION}";



		$currentDisplayTime  	   			= time();

		$default_ad_display_random 			= intval(Configuration::get_instance()->read('default_ad_display_random'));
		$default_ad_display_gap	   			= intval(Configuration::get_instance()->read('default_ad_display_gap'));
		$default_ad_existing_recheck 	    = intval(Configuration::get_instance()->read('default_ad_existing_recheck'));


		if($default_ad_display_random == 1)
		{
			$defaultAdLastDisplay	  		= intval($result['default_ad_display_time']);
			$default_ad_display_interval    = 60 * $default_ad_display_gap;

			if(($currentDisplayTime - $defaultAdLastDisplay) < $default_ad_display_interval)
			$default_ad_display_random = 0;
		}


		$delayTime			 	   		= 60 * $default_ad_existing_recheck;


		if(($currentDisplayTime - $result['default_cpv_ads']) > $delayTime)
	    {
	        $queryDefaultReplacementArray["pricing-condition"] = 1;
	        $queryDefaultReplacementArray["common-condition"]  = " AND a.type =13 AND a.mime_type !='video/webm' AND a.mime_type !='video/ogg' ";
	        $queryDefaultReplacementArray["limit-condition"]   = 1;

	        $displayArray['default'][13][0]                    = $queryDefaultReplacementArray;
	    }


		if($non_linear_text == 1)
		{
			if(($currentDisplayTime - $result['default_text_ads']) > $delayTime)
	      	{
		        $queryDefaultReplacementArray["pricing-condition"] = 0;
		        $queryDefaultReplacementArray["common-condition"]  = " AND a.type =1 ";
		        $queryDefaultReplacementArray["limit-condition"]   = 1;

		        $displayArray['default'][1][0]             = $queryDefaultReplacementArray;
	      	}
		}

		if($non_linear_banner == 1)
		{
			if(($currentDisplayTime - $result['default_banner_ads']) > $delayTime)
		    {
		        $queryDefaultReplacementArray["pricing-condition"] = 0;
		        $queryDefaultReplacementArray["common-condition"]  = " AND a.type =2 AND a.banner_id =".$result['player_size'];
		        $queryDefaultReplacementArray["limit-condition"]   = 1;

		        $displayArray['default'][2][0]             = $queryDefaultReplacementArray;
		    }
		}


     	$queryReplacementArray["pricing-condition"]    = 1;
      	$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
      	$queryReplacementArray["adrotation-condition"] = $normalAdRotation;

        $queryReplacementArray["common-condition"]   = " AND ac.type =13 AND a.mime_type !='video/webm' AND a.mime_type !='video/ogg' ";
        $queryReplacementArray["limit-condition"]    = 1;
        $displayArray['cpv'][13][0]                   = $queryReplacementArray;

        if($dspconnector_enabled == 1)
        {
            $queryReplacementArray["pricing-condition"] = 1;
            $queryReplacementArray["common-condition"]  = " AND ac.type = 20 AND ac.dsp_video_linear = 1 ";
            $queryReplacementArray["limit-condition"]   = $exchangeQueryLimit;
            $displayArray['cpv'][13][4] 		= $queryReplacementArray;
        }

		if($cpc_enabled == 1)
		{
	     	$queryReplacementArray["pricing-condition"]    = 0;
	      	$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
	      	$queryReplacementArray["adrotation-condition"] = $normalAdRotation;


			if($non_linear_text == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =1 AND ac.video_player_support =1 ";
		        $queryReplacementArray["limit-condition"]    = 1;

		        $displayArray['ppc'][1][0]           = $queryReplacementArray;
			}


			if($non_linear_banner == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =2 AND ac.html5 = 0 AND ac.video_player_support =1 AND ac.banner_id =".$result['player_size'];
		        $queryReplacementArray["limit-condition"]    = 1;

		        $displayArray['ppc'][2][0]           = $queryReplacementArray;
			}
		}


		if($cpm_enabled == 1)
		{
	     	$queryReplacementArray["pricing-condition"]    = 1;
	      	$queryReplacementArray["budget-condition"]     = $dailyBudgetCondition;
	      	$queryReplacementArray["adrotation-condition"] = $normalAdRotation;



			if($non_linear_text == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =1 AND ac.video_player_support =1 ";
		        $queryReplacementArray["limit-condition"]    = 1;

		        $displayArray['cpm'][1][0]           = $queryReplacementArray;
			}

			if($non_linear_banner == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =2 AND ac.html5 = 0 AND ac.video_player_support =1 AND ac.banner_id =".$result['player_size'];
		        $queryReplacementArray["limit-condition"]    = 1;

		        $displayArray['cpm'][2][0]           = $queryReplacementArray;
			}
		}


		if($cpa_enabled == 1)
		{
	     	$queryReplacementArray["pricing-condition"]    = 6;
	      	$queryReplacementArray["budget-condition"]     = "";
	      	$queryReplacementArray["adrotation-condition"] = $cpaAdRotation;

			if($non_linear_text == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =1 AND ac.video_player_support =1 ";
		        $queryReplacementArray["limit-condition"]    = 1;

		        $displayArray['cpa'][1][0]           = $queryReplacementArray;
			}

			if($non_linear_banner == 1)
			{
		        $queryReplacementArray["common-condition"]   = " AND ac.type =2 AND ac.html5 = 0 AND ac.video_player_support =1 AND ac.banner_id =".$result['player_size'];
		        $queryReplacementArray["limit-condition"]    = 1;

		        $displayArray['cpa'][2][0]           = $queryReplacementArray;
			}
		}
		$sourcepriority_array = array();
		$sourcepriority		= Configuration::get_instance()->read('ad_source_priority');
		$ad_source_random	= Configuration::get_instance()->read('ad_source_random');

		$sourcepriority_array = explode('_',$sourcepriority);
		$sourcepstring		  = "";

		foreach($sourcepriority_array as $sourcepkey1=>$sourcepvalue1)
		{
			if(
				$sourcepvalue1 == 'system' || 				 
				($sourcepvalue1 == 'dsp-connector' && $dspconnector_enabled == 1)
			  )
			{
				if($sourcepstring != "")
				$sourcepstring.="_";
				$sourcepstring.=$sourcepvalue1;
			}
		}
		if($sourcepstring != "")
		$sourcepriority	= $sourcepstring;
		if($ad_source_random == 1)
		{
			$sourcepriority_array	= explode('_',$sourcepriority);
			$sourcecountlist 		= count($sourcepriority_array);
			if($sourcecountlist > 1)
			{
				if($sourcecountlist == 2)
				{
				    $sourcecurrentrandom  = array(0,1);
				    shuffle($sourcecurrentrandom);
					if($sourcecurrentrandom[0] == 0)
					$sourcepriority=$sourcepriority_array[0].'_'.$sourcepriority_array[1];
					else
					$sourcepriority=$sourcepriority_array[1].'_'.$sourcepriority_array[0];
				}		
			}
		}
		$sourcepriority_array=explode('_',$sourcepriority);
		$priority_array    = array();
		$ad_display_random	= Configuration::get_instance()->read('ad_display_random');
		$priority			= Configuration::get_instance()->read('ad_display_priority');

		$priority_array		= explode('_',$priority);

		$pstring			= "";

		foreach($priority_array as $pkey1=>$pvalue1)
		{
			if($pvalue1 != "html")
			{
				if(($cpc_enabled == 1 && $pvalue1 == 'ppc') || ($cpm_enabled == 1 && $pvalue1 == 'cpm') || ($cpa_enabled == 1 && $pvalue1 == 'cpa'))
				{
					if($pstring != "")
					$pstring.="_";

					$pstring.=$pvalue1;
				}
			}
		}

		if($pstring != "")
		$priority=$pstring;



		   if($ad_display_random ==1)
		   {
		   		$priority_array	= explode('_',$priority);

		   		$countlist 		= count($priority_array);


				if($countlist >1)
				{

					if($countlist ==2)
					{
        				$currentrandom  = array(0,1);
        				shuffle($currentrandom);


						if($currentrandom[0] ==0)
						$priority=$priority_array[0].'_'.$priority_array[1];
						else
						$priority=$priority_array[1].'_'.$priority_array[0];
					}
					else if($countlist ==3)
					{
        				$currentrandom  = array(0,1,2);
        				shuffle($currentrandom);

						if($currentrandom[0] ==0)
						$priority=$priority_array[0].'_'.$priority_array[2].'_'.$priority_array[1];
						else if($currentrandom[0] ==1)
						$priority=$priority_array[1].'_'.$priority_array[0].'_'.$priority_array[2];
						else if($currentrandom[0] ==2)
						$priority=$priority_array[2].'_'.$priority_array[1].'_'.$priority_array[0];
					}
				}
			}


			if($priority != "")
			$priority 		= 'cpv_'.$priority;
			else
			$priority 		= 'cpv';


			$priority_array = explode('_',$priority);

			$sourceArray = array();
			foreach($sourcepriority_array as $skey => $svalue)
			{
				if($svalue == 'system')
				$sourceArray['system'] = $priority_array;
				if($svalue == 'dsp-connector')
				$sourceArray['dsp-connector'] = array("cpv");
			}

			$containerArray[13]	= 13;

			if($non_linear_text == 1 && $non_linear_banner == 1)
			{

				$currentrandom1  = array(0,1);
				shuffle($currentrandom1);


				if($currentrandom1[0] == 1)
				{
					$containerArray[1]	= 1;
					$containerArray[2]	= 2;
				}
				else
				{
					$containerArray[2]	= 2;
					$containerArray[1]	= 1;
				}
			}
			else if($non_linear_text == 1)
			$containerArray[1]	= 1;
			else if($non_linear_banner == 1)
			$containerArray[2]	= 2;


			$adget_flag				= 0;
			$default_ad_get_flag	= 0;
			$adsResultCount			= 0;
		$defaultAdsResultCount  = 0;

			if($default_ad_display_random == 0)
			{
            $adDisplayStep[]	= 1;   //Advertiser/Admin ad
				$adDisplayStep[]	= 2;   //Default ad
			}
			else
			{
				$adDisplayStep[]	= 2;	//Default ad
            $adDisplayStep[]	= 1;	//Advertiser/Admin ad
			}


        /************ Loop through advertiser/admin ads and default ads start ************/
			foreach($adDisplayStep as $stepKey => $stepValue)
			{
            if($stepValue == 1) //Advertiser/Admin ad
				{
                /************ Loop through different ad source start **********/
                foreach($sourceArray as $soKey => $soValue)
                {
                    if($soKey == 'dsp-connector')
                    $checkSourceKey = 4;						    
                    else
                    $checkSourceKey = 0;
                    /************ Loop through ad source based pricing start **********/
                    foreach($soValue as $pkey=>$pvalue)
					{
						/************ Loop through ad type (text/banner/video etc) start **********/
						foreach($containerArray as $ckey => $cvalue)
						{
							/************ Execution of different execute queries according to source/pricing/ad type etc start **********/
							if(isset($displayArray[$pvalue][$ckey][$checkSourceKey]))
							{
                   				$adQueryStringReplace = $adQueryString;


                                $pricingCondition     = $displayArray[$pvalue][$ckey][$checkSourceKey]["pricing-condition"];

                                $budgetCondition      = $displayArray[$pvalue][$ckey][$checkSourceKey]["budget-condition"];

                                $rotationCondition    = $displayArray[$pvalue][$ckey][$checkSourceKey]["adrotation-condition"];

                                $commonCondition      = $displayArray[$pvalue][$ckey][$checkSourceKey]["common-condition"];

                                $limitCondition       = $displayArray[$pvalue][$ckey][$checkSourceKey]["limit-condition"];

if($checkSourceKey == 0 && $pvalue == 'ppc')
$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPCBudgetCondition,$adQueryStringReplace);
else if($checkSourceKey == 0 && $pvalue == 'cpv')
$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPVBudgetCondition,$adQueryStringReplace);
else if($checkSourceKey == 0 && $pvalue == 'cpa')
$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPABudgetCondition,$adQueryStringReplace);
else if($checkSourceKey == 0 && $pvalue == 'cpm')
$adQueryStringReplace		= str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition.$CPMBudgetCondition,$adQueryStringReplace);
else
		                      	$adQueryStringReplace   = str_replace("{PRICING-CONDITION}"," AND ac.display_type = ".$pricingCondition,$adQueryStringReplace);

		                      	$adQueryStringReplace   = str_replace("{DAILY-BUDGET-CONDITION}",$budgetCondition,$adQueryStringReplace);

		                      	$adQueryStringReplace   = str_replace("{AD-ROTATION-CONDITION}",$rotationCondition,$adQueryStringReplace);

		                      	$adQueryStringReplace   = str_replace("{COMMON-CONDITION}",$commonCondition,$adQueryStringReplace);

		                      	$adQueryStringReplace   = str_replace("{LIMIT-CONDITION}",$limitCondition,$adQueryStringReplace);


								$resquery			= $db->execute_query($adQueryStringReplace);
								$adsResultCount		= $resquery->get_num_records();

								/************* Checking of ads getting for display start ***********/
								if($adsResultCount > 0)
								{
									$ad_container_type	= $ckey;

	if($soKey == 'dsp-connector')
	{
		//jesus
		while($addata = $resquery->fetch_assoc())
		{
			$retargetid 	   = 0;
			$retarget   	   = 0;
			$userid    	   = $addata['userid'];
			$adID          = $addata['aid'];
			$mapid             = $addata['aid'];	
			$keyid             = $addata['keyid'];
			$kid               = $addata['kid'];
			$adType    	   = $addata['type'];
			$default_rate 	   = $addata['default_rate'];
			$country_price     = 0;
	        	if(isset($addata['price']))
	        	$country_price     = $addata['price'];
			$rid    	   = 0;	
			if($referral_enabled ==1 && $adv_ref_enabled ==1)
			$rid=intval($addata['rid']);
			$dspEndPoint       = $addata['dsp_endpoint'];
			$dspRequestHeaders = $addata['dsp_request_headers'];
			$dspRateFrom       = $addata['dsp_rate_from'];//0=>Exchange,1=>DSP-Connector Settings
			$dspAuctionType    = intval($addata['dsp_auction_type']);
			if($dspAuctionType == 0)
			$dspAuctionType    = 2;
$dspRequestHeaderArray = [];
if (!empty($dspRequestHeaders)) {
$lines = explode("\n", $dspRequestHeaders);
foreach ($lines as $line) {
$trimmed = trim($line);
if ($trimmed !== '') {
$dspRequestHeaderArray[] = $trimmed;
}
}
}
			//Jesus
			$requestBidPayLoad = $this->get_bid_request_payload(
							$pid, $sid, $sitename, $result['auid'], $adcodeType, $ad_container_type,
							$dspAuctionType, $adcodeAllowedAds, $IABCategoryArray, $keyword,
							$requestIP, $requestUserAgent, $inpagepush, $native, 
							$language_targeting_enabled, $languageArray,
							$device_targeting_enabled, $deviceType, $page_referrer,
							$osname, $isp_connection_enabled, $isp_enabled, $isp, 
							$connection_enabled, $connection,
							$countryAlpha3, $subdivision1, $city_enabled, $cityid, $latitude, $longitude
						);
			$exchangeCURL = curl_init($dspEndPoint);
			curl_setopt($exchangeCURL, CURLOPT_HEADER, false);
			curl_setopt($exchangeCURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($exchangeCURL, CURLOPT_HTTPHEADER, $dspRequestHeaderArray);
			curl_setopt($exchangeCURL, CURLOPT_POST, true);
			curl_setopt($exchangeCURL, CURLOPT_POSTFIELDS, $requestBidPayLoad);
			curl_setopt($exchangeCURL, CURLOPT_SSL_VERIFYPEER, false);
			$resultFromExchange  = curl_exec($exchangeCURL);
			$httpCode            = curl_getinfo($exchangeCURL, CURLINFO_HTTP_CODE);
			curl_close($exchangeCURL);
			$exchangeAdStringArray = array();
			if($httpCode === 200 && $resultFromExchange != "")
			{
				$bs	      = md5($adID.$kid.$result['auid'].$sid.$pid.$retarget);
				$vastClickURL = $trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/validate/'.$adID.'/'.$kid.'/'.$result['auid'].'/'.$sid.'/'.$pid.'/{ENCIP}/'.$bs.'/0/'.$retarget;
				$RTBResponse = $this->get_processed_rtb_response($resultFromExchange, $adcodeType, $ad_container_type, $vastSkipInterval, $track_string, $vastClickURL);
$impressionPrice = 0;
$exchangeAdStringArray = array();
foreach($RTBResponse as $key => $value)
{
	//Need to add this code $impressionPrice <= 1 && for restricting unwanted price from response
	if(
	$value["bidRequestID"] != "" &&
	$value["impressionID"] != "" &&
	$value["impressionPrice"] > 0 &&	
	$value["impressionUrl"] != "" &&
	$value["impressionRenderContent"] != ""
	) //$value["impressionPrice"] <= 1 && 
	{
		$this->vastWinNoticeURL = $value["impressionUrl"];
		$impressionPrice = $value["impressionPrice"];
		$this->set_variable('xmldata',$value["impressionRenderContent"],0);
		$display_type	     = 1;
		$exchangeGetSuccess  = 1;
		$this->exchangeAd    = 1;	
		$adget_flag	    = 1;
		break;
	}		
}			
			}	
			if($exchangeGetSuccess == 1)
			break;
		}
		if($exchangeGetSuccess == 0)
		$adsResultCount = 0;
	}
	else 
	{
		$addata		= $resquery->fetch_assoc();
		$retargetid 	   = 0;
		$retarget   	   = 0;
		$userid    	   = $addata['userid'];
		$adID          = $addata['aid'];
		$keyid             = $addata['keyid'];
		$kid               = $addata['kid'];
		$adType    	   = $addata['type'];
		$default_rate 	   = $addata['default_rate'];
	        $mapid             = $addata['aid'];	
	        $country_price     = 0;
	        if(isset($addata['price']))
	        $country_price     = $addata['price'];
		$rid    	   = 0;	
		if($referral_enabled ==1 && $adv_ref_enabled ==1)
		$rid=intval($addata['rid']);
		$duration_seconds = $addata['duration_seconds'];   		
		$duration = $addata['duration'];
		$click_url = $addata['click_url'];
		$bitrate = $addata['bitrate'];
		$mime_type = $addata['mime_type'];
		$video_height = $addata['video_height'];
		$video_width = $addata['video_width'];
		$banner = $addata['banner'];
		$title = $addata['title'];
		$description = $addata['description'];
		$display_url = $addata['display_url'];



									if($pvalue == 'ppc')
									$display_type	= 0;
									else if($pvalue == 'cpm' || $pvalue == 'cpv')
									$display_type	= 1;
									else if($pvalue == 'cpa')
									$display_type	= 6;


									$adget_flag		= 1;

									break;
	}
								}
                                /************* Checking of ads getting for display end ***********/
							}
                            /************ Execution of different execute queries according to source/pricing/ad type etc end **********/
						}
                        /************ Loop through ad type (text/banner/video etc) end **********/

						if($adget_flag == 1)
						break;
					}
                    /************ Loop through ad source based pricing end **********/

					if($adget_flag == 1)
					break;
				}
                /************ Loop through different ad source end **********/

                if($adget_flag == 1)
				break;
            }
				if($stepValue == 2 || $adget_flag == 0)
				{
					foreach($containerArray as $ckey => $cvalue)
					{
						if(isset($displayArray['default'][$ckey][0]))
						{
		                    $defaultAdQueryStringReplace = $defaultAdQueryString;

		                    $pricingCondition     = $displayArray['default'][$ckey][0]["pricing-condition"];

		                    $commonCondition      = $displayArray['default'][$ckey][0]["common-condition"];

		                    $limitCondition       = $displayArray['default'][$ckey][0]["limit-condition"];


		                    $defaultAdQueryStringReplace    = str_replace("{PRICING-CONDITION}"," AND a.display_type = ".$pricingCondition,$defaultAdQueryStringReplace);

		                    $defaultAdQueryStringReplace    = str_replace("{COMMON-CONDITION}",$commonCondition,$defaultAdQueryStringReplace);

		                    $defaultAdQueryStringReplace    = str_replace("{LIMIT-CONDITION}",$limitCondition,$defaultAdQueryStringReplace);


							$resquery_default	= $db->execute_query($defaultAdQueryStringReplace);
							$defaultAdsResultCount	= $resquery_default->get_num_records();

							if($defaultAdsResultCount > 0)
							{
								$ad_container_type		= $ckey;
								$default_ad_get_flag	= 1;
						$addata				= $resquery_default->fetch_assoc();

		$adID       = $addata['aid'];
		$adType    	   = $addata['type'];
		$duration_seconds = $addata['duration_seconds'];   		
		$duration = $addata['duration'];
		$click_url = $addata['click_url'];
		$bitrate = $addata['bitrate'];
		$mime_type = $addata['mime_type'];
		$video_height = $addata['video_height'];
		$video_width = $addata['video_width'];
		$banner = $addata['banner'];
		$title = $addata['title'];
		$description = $addata['description'];
		$display_url = $addata['display_url'];
								break;
							}

							}
						}

					if($default_ad_get_flag == 1)
					break;
				}
			}

			$this->vast_aid				 = $adID;
			$this->vast_paid_ad_get			 = $adget_flag;
			$this->vast_ad_type 		 = $adType;
			$this->vast_ad_tracking_time = $videotrack;

			$admarket_name=Configuration::get_instance()->read('admarket_name');





				if($adget_flag ==1)
				{
				if($adcodeType == 13)
				$impressionTrackPricing = 13;
				else 
				$impressionTrackPricing = $display_type;

			    $impressionUpdationParameters = $userid.'|'.$adID.'|'.$kid.'|'.$pid.'|'.$adunitid.'|1|'.$sid.'|'.$impressionTrackPricing.'|'.$keyid;


				if($display_type == 1)
					{
						$profit		= 0;
						$singleimprate	= 0;
								
					if($adcodeType == 13)
						$pricingKey = "cpv";
					else 
					$pricingKey = "cpm";
						if($default_rate > 0)
						{
							if($countrywise_pricing_enabled == 1)
							{
								if($country_price > 0)
									$singleimprate = $country_price/1000;
									else 		
								{
					if(isset($decoded_array[$country][$pricingKey]) && $decoded_array[$country][$pricingKey] > 0)
					{
						$countryPrice  = $decoded_array[$country][$pricingKey];
						$singleimprate = $countryPrice/1000;
					}
					else
						$singleimprate = $default_rate/1000;
					}
								}
							else
								$singleimprate = $default_rate/1000;
							}
						else if($impressionPrice > 0)
						$singleimprate	= $impressionPrice/1000;
					if($adcodeType == 13)
					 	{

					 		if($pid > 0 && $singleimprate > 0)
						$profit=$singleimprate*$cpv_profit_percentage/100;
		 					else
					 		$profit=$singleimprate;
					 	}
					else 
					 	{

					 		if($pid > 0 && $singleimprate > 0)
						$profit=$singleimprate*$cpm_profit_percentage/100;
		 					else
					 		$profit=$singleimprate;
					 	}

						$impressionUpdationParameters.='|'.$mapid.'|'.$profit.'|'.$singleimprate.'|'.$rid.'|'.$publisherRid;
					}

	                $this->originalAdImpressionString=$impressionUpdationParameters;
				}


			if($exchangeGetSuccess == 0 && ($adget_flag == 1 || $default_ad_get_flag == 1))
			{
				if($adget_flag ==1)
				{
					$bs	 = md5($adID.$kid.$adunitid.$sid.$pid.$retarget);
					$clksurl = $trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/validate/'.$adID.'/'.$kid.'/'.$adunitid.'/'.$sid.'/'.$pid.'/{ENCIP}/'.$bs.'/0/'.$retarget;
				}


				if($adType == 13 || $adType == 2)
				{
					$adResultXML='<VAST xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vast.xsd" version="4.0" >';


					$adResultXML.='<Ad id="'.$adID.'">';
					$adResultXML.='<InLine>';
					$adResultXML.='<AdSystem>'.$admarket_name.'</AdSystem>';


					//For v 4.0
					$adResultXML.='<Impression id="Impression-ID"></Impression>';





					if($adType ==13)
	            	$adResultXML.='<AdTitle>'.$admarket_name.' Vast Linear Ad</AdTitle>';
					else
					$adResultXML.='<AdTitle>'.$admarket_name.' Vast NonLinear Banner Ad</AdTitle>';



					if($adType ==13)
	                {
	                	$this->vast_ad_duration=$duration_seconds;

		                $adResultXML.='<Creatives>';
		                $adResultXML.='<Creative adId="'.$adID.'">';



		                //For v 4.0
		                $adResultXML.='<UniversalAdId idRegistry="Ad-ID" idValue="'.$adID.'">'.$adID.'</UniversalAdId>';

						if($vastSkipInterval > 0)
						$adResultXML.='<Linear skipoffset = "'.gmdate('H:i:s', (int)$vastSkipInterval).'">';
						else
						$adResultXML.='<Linear skipoffset = "00:00:00">';
		                $adResultXML.='<Duration>'.$duration.'</Duration>';

	                	$adResultXML.='<TrackingEvents>';

	                	//Impression tracking
		                $adResultXML.='<Tracking event="'.$track_string.'"><![CDATA[{IMPRESSIONTRACKING}]]></Tracking>';

		                $adResultXML.='</TrackingEvents>';


		                $adResultXML.='<VideoClicks>';
		                $adResultXML.='<ClickThrough><![CDATA['.$click_url.']]></ClickThrough>';

		                if($adget_flag ==1)
		                $adResultXML.='<ClickTracking><![CDATA['.$clksurl.']]></ClickTracking>';


			            $adResultXML.='</VideoClicks>';


		                $adResultXML.='<MediaFiles>';
		                $adResultXML.='<MediaFile delivery="progressive" bitrate="'.$bitrate.'" width="'.$video_width.'" height="'.$video_height.'" type="'.$mime_type.'">';

						$adResultXML.='<![CDATA['.BASE.DATA_DIR.'/video/'.$adID.'/'.$banner.']]>';


		                $adResultXML.='</MediaFile>';
			            $adResultXML.='</MediaFiles>';

						$adResultXML.='</Linear>';
						$adResultXML.='</Creative>';
						$adResultXML.='</Creatives>';
	                }
	                else if($adType == 2)
	                {
					 	$adResultXML.='<Creatives>';
		                $adResultXML.='<Creative>';

		                //For v 4.0
		                $adResultXML.='<UniversalAdId idRegistry="Ad-ID" idValue="'.$adID.'">'.$adID.'</UniversalAdId>';


		                $adResultXML.='<NonLinearAds>';


		                $adResultXML.='<TrackingEvents>';

			        	$adResultXML.='<Tracking event="start"><![CDATA[{IMPRESSIONTRACKING}]]></Tracking>';

		                $adResultXML.='</TrackingEvents>';

		                
		                    //$adResultXML.='<NonLinear id="overlay" minSuggestedDuration="00:00:10" width="'.$video_banner_width.'" height="'.$video_banner_height.'" scalable="true" maintainAspectRatio="true">';

		                    //For v 4.0
		                    $adResultXML.='<NonLinear>';


			                $adResultXML.='<StaticResource creativeType="'.$mime_type.'">';

							$adResultXML.='<![CDATA['.BASE.DATA_DIR.'/'.$adID.'_'.$banner.']]>';

				            $adResultXML.='</StaticResource>';



		                if($adget_flag ==1)
			            $adResultXML.='<NonLinearClickThrough><![CDATA['.$clksurl.']]></NonLinearClickThrough>';

				
		                $adResultXML.='</NonLinear>';
						$adResultXML.='</NonLinearAds>';
						$adResultXML.='</Creative>';
						$adResultXML.='</Creatives>';
	                }

					$adResultXML.='</InLine>';
					$adResultXML.='</Ad>';
					$adResultXML.='</VAST>';


				}
				else
				{
					 $adResultXML='<VideoAdServingTemplate xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="vast.xsd" version="4.0">';
					 $adResultXML.='<Ad id="'.$adID.'">';
					 $adResultXML.='<InLine>';
					 $adResultXML.='<AdSystem>'.$admarket_name.'</AdSystem>';

					 $adResultXML.='<AdTitle>'.$admarket_name.' Vast NonLinear Text Ad</AdTitle>';

					 $adResultXML.='<Impression><Url><![CDATA[{IMPRESSIONTRACKING}]]></Url></Impression>';

					 $adResultXML.='<NonLinearAds>';
		             $adResultXML.='<NonLinear id="overlay" resourceType="HTML">';
		             $adResultXML.='<Code>';
			         $adResultXML.='<![CDATA[';

					 $adResultXML.='<p>'.$title.'</p>';
					 $adResultXML.='<p>'.$description.'</p>';
					 $adResultXML.='<p>'.$display_url.'</p>';

					 $adResultXML.=']]>';
				     $adResultXML.='</Code>';


				     if($adget_flag ==1)
		             $adResultXML.='<NonLinearClickThrough><Url><![CDATA['.$clksurl.']]></Url></NonLinearClickThrough>';

					 $adResultXML.='</NonLinear>';
					 $adResultXML.='</NonLinearAds>';
					 $adResultXML.='</InLine>';
					 $adResultXML.='</Ad>';
					 $adResultXML.='</VideoAdServingTemplate>';
				}

				$this->set_variable('xmldata',$adResultXML,0);
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
			if($this->get_ad_validation_user($aid, 6, 12))
			{
				if($this->get_adunit_exists($aduid, 6, 12))
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

		   $admarket_name 	= "'.Configuration::get_instance()->read("admarket_name").'";


		   $ad_display_cache_time = '.Configuration::get_instance()->read("ad_display_cache_time").';



		   if(($ad_display_cache_time * 60) < (time()-$created_time))
		   $cachedFlag=2;
		   else
		   {
		        $cachedFlag=1;

				ob_start();

				ob_clean();


				echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				?>'.$ad_buffer_data.'<?php
				$ad_cache_buffer_data = ob_get_contents();


                $originalAdImpressionString="'.$this->originalAdImpressionString.'";


                $vast_aid='.$this->vast_aid.';
                $vast_paid_ad_get='.$this->vast_paid_ad_get.';
                $adcodeID='.$this->adcodeID.';


		$vastWinNoticeURL="'.$this->vastWinNoticeURL.'";
                $vast_ad_type='.$this->vast_ad_type.';
                $vast_ad_tracking_time='.$this->vast_ad_tracking_time.';
                $vast_ad_duration='.$this->vast_ad_duration.';

								$trackDomain     ="'.$this->trackDomain.'";
				$exchangeAd                 = '.$this->exchangeAd.';

				$sourceType  = 0;
				$specialtime=0;

				if($exchangeAd == 1)
				$sourceType = 2;
				if($vast_ad_type ==13)
				{

					if($vast_ad_tracking_time ==0)
					$specialtime=time()+120;
					else
					{
						$percentage_time=($vast_ad_duration*$vast_ad_tracking_time)/100;

						$specialtime=time()+$percentage_time+120;
					}
				}
				else
				$specialtime=time()+120;


				$urlsrc="";
				if($vast_paid_ad_get ==1)
				{
					$impression_data_string=$this->get_impression_data($originalAdImpressionString,$vast_ad_type,"",1);

					if($impression_data_string !="")
					$urlsrc = $trackDomain.TRACK_DIR."/".$this->indexAppend."action/impression/".$impression_data_string."/".md5($impression_data_string.$admarket_name.$this->cacheIP.$this->cacheUserAgent.$this->cacheDate.$specialtime.$this->geo_country)."/".$specialtime."/".$this->geo_country."/".$sourceType;
				}
				else
				$urlsrc=$trackDomain.TRACK_DIR."/".$this->indexAppend."action/impression_default/".$vast_aid."/".$adcodeID;


				$urlsrc=str_replace("https:","",$urlsrc);
				$urlsrc=str_replace("http:","",$urlsrc);

				$ad_cache_buffer_data = str_replace("{IMPRESSIONTRACKING}",$urlsrc, $ad_cache_buffer_data);
                		$ad_cache_buffer_data = str_replace("{ENCIP}","'.$this->cacheEncryptedIP.'", $ad_cache_buffer_data);

				ob_clean();
				echo $ad_cache_buffer_data;
				if($vastWinNoticeURL != "")
				file_get_contents($vastWinNoticeURL);
			}
			?>';

			file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/".$this->cacheFileName,$cache_file_db_operation);
		}

		$sourceType  = 0;
		$specialtime=0;

		if($this->exchangeAd == 1)
		$sourceType = 2;
		if($this->vast_ad_type ==13)
		{
			if($this->vast_ad_tracking_time ==0)
			$specialtime=time()+120;
			else 
			{
				$percentage_time=($this->vast_ad_duration*$this->vast_ad_tracking_time)/100;

				$specialtime=time()+$percentage_time+120;
			}
		}
		else
		$specialtime=time()+120;




		$urlsrc="";
		if($this->vast_paid_ad_get ==1)
		{
			$impression_data_string=$this->get_impression_data($this->originalAdImpressionString,$this->vast_ad_type,"",1);

			if($impression_data_string !="")
			$urlsrc = $this->trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression/'.$impression_data_string.'/'.md5($impression_data_string.Configuration::get_instance()->read('admarket_name').$this->cacheIP.$this->cacheUserAgent.$this->cacheDate.$specialtime.$this->geo_country).'/'.$specialtime.'/'.$this->geo_country.'/'.$sourceType;
		}
		else
		$urlsrc=$this->trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression_default/'.$this->vast_aid.'/'.$this->adcodeID;


		$urlsrc=str_replace("https:","",$urlsrc);
		$urlsrc=str_replace("http:","",$urlsrc);

		$ad_buffer_data = str_replace("{IMPRESSIONTRACKING}",$urlsrc, $ad_buffer_data);
		$ad_buffer_data = str_replace("{ENCIP}",$this->cacheEncryptedIP, $ad_buffer_data);


		if($this->vastWinNoticeURL != "")
		file_get_contents($this->vastWinNoticeURL);

		return $ad_buffer_data;
	}

	function create_feed_cache($ad_buffer_data)
	{
		if(Configuration::get_instance()->read('ad_display_cache_time') >0 && trim($ad_buffer_data) !="")
		{
		   $cache_file_db_operation='<?php

		   $adListArray		      = json_decode(\''.$this->adListArray.'\',1);
		   $created_time          = '.time().';
		   $admarket_name 	      = "'.Configuration::get_instance()->read("admarket_name").'";
		   $ad_display_cache_time = '.Configuration::get_instance()->read("ad_display_cache_time").';

		   $trackDomain           = "'.$this->trackDomain.'";

		   if(($ad_display_cache_time * 60) < (time()-$created_time))
		   $cachedFlag=2;
		   else
		   {
		        $cachedFlag=1;

				ob_start();
				ob_clean();

				header("Content-type: text/xml; charset=utf-8");
				header("Access-Control-Allow-Origin: *");
				?>'.$ad_buffer_data.'<?php
				$ad_cache_buffer_data = ob_get_contents();

                $displayAdCount ='.$this->displayAdCount.';

				$listingReplace = "";

				$specialtime = time()+120;    //2 minutes expiry for one impression url

				$returnArray = $this->getFeedAdsFromlist($displayAdCount,$adListArray);

				if(isset($returnArray))
                {
                	foreach($returnArray as $rKey => $rValue)
                	{

                		if($rValue["paid_ad"] == 1)
				        {
	                		$ImpressionString    = $rValue["impression_string"];
							$md5StringData		 = md5($ImpressionString.$admarket_name.$this->cacheIP.$this->cacheDate.$specialtime.$this->geo_country."4");//For supply ads as xml feed

	                		$impressionUrl  	 = $trackDomain.TRACK_DIR."/".$this->indexAppend."action/impression/".$ImpressionString."/".$md5StringData."/".$specialtime."/".$this->geo_country."/4";

	                		$clickUrl            = str_replace("{ENCIP}",$this->cacheEncryptedIP, $rValue["click_url"]);


	                		$pixelUrl 	         = str_replace("{PIXEL-URL-".$rKey."}",$impressionUrl, $rValue["pixel_url"]);
						}
						else
						{
							$clickUrl = $rValue["click_url"];
							$pixelUrl = $rValue["pixel_url"];
						}

						$title        = $rValue["title"];
						$description  = $rValue["description"];
						$display_url  = $rValue["display_url"];
						$banner_url   = $rValue["banner_url"];
						$icon_url     = $rValue["icon_url"];
						$default_rate = $rValue["default_rate"];



						$listingReplace.= "<listing>";
						$listingReplace.= "<bid>$default_rate</bid>";

						if($title != "")
						$listingReplace.= "<title>$title</title>";

						if($description != "")
						$listingReplace.= "<description>$description</description>";

						if($display_url != "")
						$listingReplace.= "<displayurl>$display_url</displayurl>";

						if($banner_url != "")
						$listingReplace.= "<bannerurl>$banner_url</bannerurl>";

						if($icon_url != "")
						$listingReplace.= "<iconurl>$icon_url</iconurl>";

						if($clickUrl != "")
						$listingReplace.= "<clickurl>$clickUrl</clickurl>";

						if($pixelUrl != "")
						$listingReplace.= "<pixelurl>$pixelUrl</pixelurl>";

						$listingReplace.= "</listing>";
					}
                }

                $ad_cache_buffer_data = str_replace("{LISTINGREPLACE}",$listingReplace, $ad_cache_buffer_data);


				ob_clean();
				echo $ad_cache_buffer_data;
			}
			?>';

			file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/".$this->feedCacheName,$cache_file_db_operation);
		}


		$admarket_name 	= Configuration::get_instance()->read('admarket_name');
		$specialtime    = time()+120;
		$listingReplace = "";

		$adListArray		= json_decode($this->adListArray,1);
		$returnArray 		= $this->getFeedAdsFromlist($this->displayAdCount,$adListArray);

		if(isset($returnArray))
        {
            foreach($returnArray as $rKey => $rValue)
            {
               	if($rValue["paid_ad"] == 1)
				{
	                $ImpressionString   = $rValue["impression_string"];
					$md5StringData		= md5($ImpressionString.$admarket_name.$this->cacheIP.$this->cacheDate.$specialtime.$this->geo_country."4");//For supply ads as xml feed

	                $impressionUrl  	= $this->trackDomain.TRACK_DIR."/".$this->indexAppend."action/impression/".$ImpressionString."/".$md5StringData."/".$specialtime."/".$this->geo_country."/4";

	                $clickUrl           = str_replace("{ENCIP}",$this->cacheEncryptedIP, $rValue["click_url"]);


	                $pixelUrl 	        = str_replace("{PIXEL-URL-".$rKey."}",$impressionUrl, $rValue["pixel_url"]);
				}
				else
				{
					$clickUrl = $rValue["click_url"];
					$pixelUrl = $rValue["pixel_url"];
				}

				$title        = $rValue["title"];
				$description  = $rValue["description"];
				$display_url  = $rValue["display_url"];
				$banner_url   = $rValue["banner_url"];
				$icon_url     = $rValue["icon_url"];
				$default_rate = $rValue["default_rate"];



				$listingReplace.= "<listing>";
				$listingReplace.= "<bid>".$default_rate."</bid>";

				if($title != "")
				$listingReplace.= "<title>".$title."</title>";

				if($description != "")
				$listingReplace.= "<description>".$description."</description>";

				if($display_url != "")
				$listingReplace.= "<displayurl>".$display_url."</displayurl>";

				if($banner_url != "")
				$listingReplace.= "<bannerurl>".$banner_url."</bannerurl>";

				if($icon_url != "")
				$listingReplace.= "<iconurl>".$icon_url."</iconurl>";

				if($clickUrl != "")
				$listingReplace.= "<clickurl>".$clickUrl."</clickurl>";

				if($pixelUrl != "")
				$listingReplace.= "<pixelurl>".$pixelUrl."</pixelurl>";

				$listingReplace.= "</listing>";
			}
        }

        $ad_buffer_data = str_replace("{LISTINGREPLACE}",$listingReplace, $ad_buffer_data);

		return $ad_buffer_data;
	}


	function create_cache($ad_buffer_data)
	{
			if($this->feedCacheable == 1 && Configuration::get_instance()->read('ad_display_cache_time') >0 && trim($ad_buffer_data) !="")
			{
					   $cache_file_db_operation='
					   <?php

					   $adListArray		      = json_decode(\''.$this->adListArray.'\',1);

					   $ad_display_cache_time = '.Configuration::get_instance()->read("ad_display_cache_time").';
					   $trackDomain           = "'.$this->trackDomain.'";

					   $created_time='.time().';

					   if(($ad_display_cache_time*60) < (time()-$created_time))
					   $cachedFlag=2;
					   else
					   {
					       $cachedFlag=1;

						   ob_start();

			               $display_type='.$this->adPricingValue.';
				       $html_ad_get_flag='.$this->html_ad_get_flag.';

			               $cpc_enabled="'.$this->cpc_enabled_value.'";
			               $cpm_enabled="'.$this->cpm_enabled_value.'";
			               $html_enabled="'.$this->html_enabled_value.'";
			               $cpd_enabled="'.$this->cpd_enabled_value.'";
			               $cpa_enabled="'.$this->cpa_enabled_value.'";
			               $pop_enabled="'.$this->pop_enabled_value.'";

			               $ppc_tracking_interval="'.$this->ppc_tracking_interval.'";
			               $cpa_tracking_interval="'.$this->cpa_tracking_interval.'";
			               $cpm_tracking_interval="'.$this->cpm_tracking_interval.'";
			               $cpd_tracking_interval="'.$this->cpd_tracking_interval.'";
			               $html_tracking_interval="'.$this->html_tracking_interval.'";
			               $pop_tracking_interval="'.$this->pop_tracking_interval.'";

			               $adcodeID='.$this->adcodeID.';
			               $displayAdCount='.$this->displayAdCount.';
			               $ad_container_type='.$this->ad_container_type.';
			               $ecommerceFlag='.$this->ecommerceFlag.';
				$exchangeAd        = '.$this->exchangeAd.';

			               $adRotationEnabled='.$this->adRotationEnabled.';
			               $adRotationInterval='.$this->adRotationInterval.';

						   //if($display_type == 9)       //Need it for some server requests
						   //header("Content-Type: application/javascript");	 //Need it for some server requests

							ob_clean();

							?>'.$ad_buffer_data.'<?php

							$ad_cache_buffer_data   = ob_get_contents();




				$ad_cache_buffer_data = $this->get_ad_display_cache(
					$ad_cache_buffer_data, $adListArray, $this->cacheEncryptedIP, $this->cacheIP, 
					$this->cacheUserAgent, $this->cacheDate, 












					$trackDomain, $display_type, $exchangeAd, $html_ad_get_flag, $ad_container_type,










					$adcodeID, $ecommerceFlag, $adRotationEnabled, $adRotationInterval, $this->indexAppend, 

					$this->geo_country, $displayAdCount, $ppc_tracking_interval, $cpm_tracking_interval, 
					$html_tracking_interval, $cpd_tracking_interval, $cpa_tracking_interval, $pop_tracking_interval, 











					$this->cpc_impression, $this->cpm_impression, $this->html_impression, $this->cpd_impression, 
					$this->cpa_impression, $this->pop_impression, $this->cpv_impression
				);











									  	  




															

										
										

									





									

								












							ob_clean();
							echo $ad_cache_buffer_data;
						}
						?>';

						file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/".$this->cacheFileName,$cache_file_db_operation);
					}























			$adListArray		= json_decode($this->adListArray,1);























				





















					  	  

							
							

								

		$ad_buffer_data = $this->get_ad_display_cache(







						
					$ad_buffer_data, $adListArray, $this->cacheEncryptedIP, $this->cacheIP, 
					$this->cacheUserAgent, $this->cacheDate, 
					$this->trackDomain, $this->adPricingValue, $this->exchangeAd, 
						
					
					$this->html_ad_get_flag, $this->ad_container_type, $this->adcodeID, $this->ecommerceFlag,


					$this->adRotationEnabled, $this->adRotationInterval, $this->indexAppend, $this->geo_country,

					$this->displayAdCount, $this->ppc_tracking_interval, $this->cpm_tracking_interval, 

					$this->html_tracking_interval, $this->cpd_tracking_interval, $this->cpa_tracking_interval, 
					$this->pop_tracking_interval, 
					$this->cpc_impression, $this->cpm_impression, $this->html_impression, $this->cpd_impression, 
					$this->cpa_impression, $this->pop_impression, $this->cpv_impression
				);






			//file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/2.php",$ad_buffer_data);

			return $ad_buffer_data;
	}

	function get_js_data_replace($ad_container_type,$adcodeID,$dataContent0 = "",$ecommerceFlag = 0,$expandableContent = "")
	{
		$jsDisplayString = "";

		if($ad_container_type == 14 && $dataContent0 > 0) //For skin ads
		{
			$jsDisplayString = '<script data-cfasync="false" type="text/javascript">

								$(document).ready(function()
								{
									adID = '.intval($dataContent0).';

									if($("#skin-content-"+adID).length > 0)
									{
										skinContentValue = document.getElementById("skin-content-"+adID).value;

										if(skinContentValue != "")
										window.parent.postMessage(skinContentValue,"*");
									}
								});
			    				</script>';
		}


		if($ad_container_type != 14 && $dataContent0 != "")	//For normal ads
		{
			$jsDisplayString = '<script data-cfasync="false" type="text/javascript">
						adcodeID				= '.$adcodeID.';
						displayIDs      		= "'.$dataContent0.'";
						displayIDsArray 		= displayIDs.split("-");
						displayIDsArrayLength 	= displayIDsArray.length;
						adid					= 0;

						if(displayIDsArrayLength > 0)
						{
							tempData = 0;

							for(ii = 0;ii < displayIDsArrayLength;ii++)
							{
								tempData = ii;
								adid	 = displayIDsArray[ii];

								if(document.getElementById("content-box-section-"+displayIDsArray[ii]))
								document.getElementById("content-box-section-"+displayIDsArray[ii]).style.display = "";
							}';

						$jsDisplayString.= '}';

						$jsDisplayString.= '</script>';
		}

		return $jsDisplayString;
	}

   function get_impression_js($type,$impression_data_string,$md5StringData = "",$specialtime = 0,$geo_country = "",$impression_cookie = "",$adcodeID = 0,$tracking_interval = 1000,$displayType = 0,$ad_container_type = 0,$sourceType = 0)
   {
		$trackDomain       = $this->get_track_domain();

   		$impressionJSString = "";

   		if($ad_container_type != 13 && $impression_data_string == "")
   		return $impressionJSString;

   		if($ad_container_type == 13)
   		{
			$impressionJSString = '<script data-cfasync="false" type="text/javascript">
			var video_timer=0;
			var video_tracked=0;
			var interval;
			function TrackData(operation,duration,trackingtime,original,adid)
			{
				//original  => 1 Normal Ads
				//original  => 0 Default Ads

				//operation => 1 Play
				//operation => 2 Pause

				if(operation ==1)
				{
					if(document.getElementById("video-outer-layer-"+adid))
					document.getElementById("video-outer-layer-"+adid).style.display = "";
				}
				else
				{
					if(document.getElementById("video-outer-layer-"+adid))
					document.getElementById("video-outer-layer-"+adid).style.display = "none";
				}';


			if($impression_data_string != "")
			{
				$impressionJSString.= '
				if(video_tracked ==1)
				return;

				if(operation ==1)
				{
					    interval=window.setInterval(function(){

						video_timer=video_timer+1;

						if((original == 1 && (video_timer >= trackingtime || video_timer >= duration)) || original == 0)
						{
							window.clearInterval(interval);

							video_timer=0;
							video_tracked=1;

							var script=document.createElement("script");
							script.setAttribute("data-cfasync","false");
						 	script.type = "text/javascript";
						    script.async=1;';


			   				if($type == 1)
					 		$impressionJSString.= 'script.src="'.$trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression/'.$impression_data_string.'/'.$md5StringData.'/'.$specialtime.'/'.$geo_country.'/'.$sourceType.'/'.$impression_cookie.'";';
				 	 		else
				 	 		$impressionJSString.= 'script.src="'.$trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression_default/'.$impression_data_string.'/'.$adcodeID.'";';

							$impressionJSString.= 'document.getElementsByTagName("head")[0].appendChild(script);
						}

					}, 1000);

				}
				else if(operation ==2)
				{
					video_timer=video_timer+1;
					window.clearInterval(interval);
				}';
			}

			$impressionJSString.= '
			}
			</script>';
   		}
   		else if($ad_container_type == 14)
   		{
			    $impressionJSString =  '<script data-cfasync="false" type="text/javascript">
										$(document).ready(function()
										{
				    						var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
											var eventer = window[eventMethod];
											var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

											eventer(messageEvent, function (e)
										    {
										        var response=e.data;
										        try
										        {
										            responsedata=JSON.parse(response);

										            if(responsedata != "" && responsedata.adcodeID == '.$adcodeID.' && responsedata.operation == "skinAdRendered")
										            {
										                if(rotationIndexTemp == 0 && fullAdsRendered == 0) //First rendering ads impression tracked from here
														{
															window.setTimeout(function()
															{
																var script=document.createElement("script");
																script.setAttribute("data-cfasync","false");
																script.type = "text/javascript";
																script.async=1;';

									   							if($type == 1)
														 		$impressionJSString.= 'script.src="'.$trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression/'.$impression_data_string.'/'.$md5StringData.'/'.$specialtime.'/'.$geo_country.'/'.$sourceType.'/'.$impression_cookie.'";';
													 	 		else
													 	 		$impressionJSString.= 'script.src="'.$trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression_default/'.$impression_data_string.'/'.$adcodeID.'";';

														 		$impressionJSString.= 'document.getElementsByTagName("head")[0].appendChild(script);
															},'.$tracking_interval.');
														}
											        }
										        } catch (e) {}
										    }, false);
										});
					     				</script>';
   			}
   			else
   			{
	   			$impressionJSString = '<script data-cfasync="false" type="text/javascript">
											 		window.setTimeout(function(){
											 		var script=document.createElement("script");
											 		script.setAttribute("data-cfasync","false");
											 		script.type = "text/javascript";
											    		script.async=1;';

	   												if($type == 1)
												 	$impressionJSString.= 'script.src="'.$trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression/'.$impression_data_string.'/'.$md5StringData.'/'.$specialtime.'/'.$geo_country.'/'.$sourceType.'/'.$impression_cookie.'";';
													else
													$impressionJSString.= 'script.src="'.$trackDomain.TRACK_DIR.'/'.$this->indexAppend.'action/impression_default/'.$impression_data_string.'/'.$adcodeID.'";';

													$impressionJSString.= 'document.getElementsByTagName("head")[0].appendChild(script);
											 	 	},'.$tracking_interval.');
										  			</script>';
   			}

   			return $impressionJSString;
   }

   /*
   function get_ads_from_ads_list($displayAdCount, $adListArray, $adcodeType)
   {
       $originalAdImpressionString	= "";
       $defaultAdImpressionString 	= "";
       $adIDList				 	= "";
       $originalAdExists   	 		= 0;
       $defaultAdAllowed		 	= $displayAdCount;
       $skinAdContent			    = "";
       $expandableContent1          = "";

       $popAD						= 0;
       $poptype 					= 0;
       $pop_window_width			= 0;
       $pop_window_height			= 0;
       $originalpopad				= 0;
	   $pop_click_url				= "";
       $pop_pixel_url               = "";

	   $directlinkAD                = 0;
	   $originaldirectlinkad		= 0;

       $directlink_click_url		= "";
       $directlink_pixel_url        = "";
       $feed_ad                     = 0;
	   $exchange_ad                 = 0;

       if(isset($adListArray[0])) //Paid Ads
       {
           	$originalAdArray = $adListArray[0];
           	$originalAdCount = count($originalAdArray);

		    if($originalAdCount > 0)
		    {
		         $originalAdExists   = 1;

		         if($originalAdCount < $displayAdCount)
		         {
		             $defaultAdAllowed = $displayAdCount - $originalAdCount;

					//In case of native ads, loop may execute multiple time. Other ads case loop execute only one time
		             foreach($originalAdArray as $k1 => $v1)
		             {
		               		if($originalAdImpressionString !="")
							$originalAdImpressionString.= ".data.";

		               		$originalAdImpressionString.= $v1;


							if($adIDList != "")
							$adIDList.= "-";

							$adIDList.= $k1;

							if(isset($adListArray[2][$k1]))
							$skinAdContent = $adListArray[2][$k1];

							if(isset($adListArray[3][$k1]))
							$expandableContent1 = $adListArray[3][$k1][0];

						//POP / Direct Link ads case
		             				if(isset($adListArray[4][$k1]))
							{
								   if($adcodeType == 9)
								   {
										$popAD						= 1;
										$poptype 					= $adListArray[4][$k1]['poptype'];
										$pop_window_width			= $adListArray[4][$k1]['pop_window_width'];
										$pop_window_height			= $adListArray[4][$k1]['pop_window_height'];
										$originalpopad				= $adListArray[4][$k1]['originalpopad'];
								$pop_click_url				= $adListArray[4][$k1]['click_url'];
								$pop_pixel_url				= $adListArray[4][$k1]['pixel_url'];
								   }	
								   else if($adcodeType == 21)
								   {
										$directlinkAD               = 1;
										$originaldirectlinkad		= $adListArray[4][$k1]['originaldirectlinkad'];
								$directlink_click_url		= $adListArray[4][$k1]['click_url'];
								$directlink_pixel_url		= $adListArray[4][$k1]['pixel_url'];
								   }							 
							 
						       		$feed_ad					    = $adListArray[4][$k1]['feed_ad'];
							$exchange_ad                = $adListArray[4][$k1]['exchange_ad'];
							}
		            }
		        }
               	else //Number of paid ads >= allowed ads to display
               	{
               		$defaultAdAllowed = 0;

				    $premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

					if($premium_ad_enabled == 1)
					{
						$dIndex         = 0;
						$randomKeysData = array();

						foreach($originalAdArray as $dKey => $dValue)
						{
							if($dKey > 0)
							$randomKeysData[] = $dKey;

							$dIndex++;

							if($dIndex >= $displayAdCount)
							break;
						}
					}
					else
					$randomKeysData 	  = array_rand($originalAdArray,$displayAdCount);

               		if(!is_array($randomKeysData))
               		{
               			$randomKeys[] 	= $randomKeysData;
               		}
               		else
               		$randomKeys = $randomKeysData;

					//In case of native ads, loop may execute multiple time. Other ads case loop execute only one time
               		foreach($randomKeys as $k1 => $v1)
               		{
               			if($originalAdImpressionString !="")
						$originalAdImpressionString.= ".data.";

						$originalAdImpressionString.= $originalAdArray[$v1];

						if($adIDList != "")
						$adIDList.= "-";

						$adIDList.= $v1;

						if(isset($adListArray[2][$v1]))
						$skinAdContent = $adListArray[2][$v1];

               			if(isset($adListArray[3][$v1]))
						$expandableContent1 = $adListArray[3][$v1][0];

						//POP / Direct Link ads case
               			if(isset($adListArray[4][$v1]))
						{
							if($adcodeType == 9)
							{
								$popAD						= 1;
								$poptype 					= $adListArray[4][$v1]['poptype'];
								$pop_window_width			= $adListArray[4][$v1]['pop_window_width'];
								$pop_window_height			= $adListArray[4][$v1]['pop_window_height'];
								$originalpopad				= $adListArray[4][$v1]['originalpopad'];
								$pop_click_url				= $adListArray[4][$v1]['click_url'];
								$pop_pixel_url				= $adListArray[4][$v1]['pixel_url'];
							}						 
						    else if($adcodeType == 21)
						    {
								$directlinkAD               = 1;
								$originaldirectlinkad		= $adListArray[4][$v1]['originaldirectlinkad'];
						    	$directlink_click_url		= $adListArray[4][$v1]['click_url'];
								$directlink_pixel_url		= $adListArray[4][$v1]['pixel_url'];
						    }							 
						 
					        $feed_ad						= $adListArray[4][$v1]['feed_ad'];
							$exchange_ad                    = $adListArray[4][$v1]['exchange_ad'];
						}
               		}
               	}
            }
       }

       if(isset($adListArray[1]) && $defaultAdAllowed > 0) //Default Ads
       {
           	$defaultAdArray = $adListArray[1];
           	$defaultAdCount = count($defaultAdArray);

            if($defaultAdCount < $defaultAdAllowed)
            {
				//In case of native ads, loop may execute multiple time. Other ads case loop execute only one time
               	foreach($defaultAdArray as $k1 => $v1)
               	{
               			if($defaultAdImpressionString !="")
						$defaultAdImpressionString.= "_";

               			$defaultAdImpressionString.= $v1;

						if($adIDList != "")
						$adIDList.= "-";

						$adIDList.= $k1;

						if(isset($adListArray[2][$k1]))
						$skinAdContent = $adListArray[2][$k1];

               	       	if(isset($adListArray[3][$k1]))
						$expandableContent1 = $adListArray[3][$k1][0];

					//POP / Direct Link ads case
	             		if(isset($adListArray[4][$k1]))
						{
							if($adcodeType == 9)
							{
						   		$popAD						= 1;
						   		$poptype 					= $adListArray[4][$k1]['poptype'];
					       		$pop_window_width			= $adListArray[4][$k1]['pop_window_width'];
					       		$pop_window_height			= $adListArray[4][$k1]['pop_window_height'];
					       		$originalpopad				= $adListArray[4][$k1]['originalpopad'];
							$pop_click_url				= $adListArray[4][$k1]['click_url'];
							$pop_pixel_url				= $adListArray[4][$k1]['pixel_url'];
							}	
						    else if($adcodeType == 21)
						    {
							    $directlinkAD               = 1;
							    $originaldirectlinkad		= $adListArray[4][$k1]['originaldirectlinkad'];
							$directlink_click_url		= $adListArray[4][$k1]['click_url'];
							$directlink_pixel_url		= $adListArray[4][$k1]['pixel_url'];
						    }		
						 						 
					        $feed_ad					= $adListArray[4][$k1]['feed_ad'];
						$exchange_ad                = $adListArray[4][$k1]['exchange_ad'];
						}
               	}
            }
            else
            {
               	    $randomKeysData = array_rand($defaultAdArray,$defaultAdAllowed);

               		if(!is_array($randomKeysData))
               		{
               			$randomKeys[] 	= $randomKeysData;
               		}
               		else
               		$randomKeys = $randomKeysData;

				//In case of native ads, loop may execute multiple time. Other ads case loop execute only one time
               	    foreach($randomKeys as $k1 => $v1)
              	    {
               			if($defaultAdImpressionString !="")
						$defaultAdImpressionString.= "_";

               			$defaultAdImpressionString.= $defaultAdArray[$v1];

						if($adIDList != "")
						$adIDList.= "-";

						$adIDList.= $v1;

						if(isset($adListArray[2][$v1]))
						$skinAdContent = $adListArray[2][$v1];

              	       	if(isset($adListArray[3][$v1]))
						$expandableContent1 = $adListArray[3][$v1][0];

					//POP / Direct Link ads case
               			if(isset($adListArray[4][$v1]))
						{
							if($adcodeType == 9)
							{
						   		$popAD						= 1;
						   		$poptype 					= $adListArray[4][$v1]['poptype'];
					       		$pop_window_width			= $adListArray[4][$v1]['pop_window_width'];
					       		$pop_window_height			= $adListArray[4][$v1]['pop_window_height'];
					       		$originalpopad				= $adListArray[4][$v1]['originalpopad'];
							$pop_click_url				= $adListArray[4][$v1]['click_url'];
							$pop_pixel_url				= $adListArray[4][$v1]['pixel_url'];
							}
						    else if($adcodeType == 21)
						    {
							    $directlinkAD               = 1;
							    $originaldirectlinkad		= $adListArray[4][$v1]['originaldirectlinkad'];
							$directlink_click_url		= $adListArray[4][$v1]['click_url'];
							$directlink_pixel_url		= $adListArray[4][$v1]['pixel_url'];
						    }		
						 
					        $feed_ad					= $adListArray[4][$v1]['feed_ad'];
						$exchange_ad                = $adListArray[4][$v1]['exchange_ad'];
						}
               	   }
             }
        }

		
		$returnArray["paid_ads"] 	         = $originalAdImpressionString;
        $returnArray["default_ads"] 	     = $defaultAdImpressionString;
        $returnArray["ad_id_list"] 	         = $adIDList;
        $returnArray["skin_ad_content"] 	 = $skinAdContent;
        $returnArray["expandable_content"] = $expandableContent1;
        $returnArray["pop"]["pop_ad"] 	         = $popAD;
        $returnArray["pop"]["click_url"] 	     = $pop_click_url;
        $returnArray["pop"]["pop_type"] 	     = $poptype;
        $returnArray["pop"]["pop_window_width"]  = $pop_window_width;
        $returnArray["pop"]["pop_window_height"] = $pop_window_height;
        $returnArray["pop"]["original_pop_ad"]   = $originalpopad; 
        $returnArray["pop"]["pixel_url"] 	     = $pop_pixel_url;
		if($adcodeType == 9 && $feed_ad == 1 && $popAD == 1)
        $returnArray["pop"]["source"] = 1;
		else if($adcodeType == 9 && $exchange_ad == 1 && $popAD == 1)
        $returnArray["pop"]["source"] = 2;
		else 
		$returnArray["pop"]["source"] = 0;
		$returnArray["directlink"]["directlink_ad"] 	        = $directlinkAD;
        $returnArray["directlink"]["click_url"] 	            = $directlink_click_url;
        $returnArray["directlink"]["original_directlink_ad"] 	= $originaldirectlinkad;
        $returnArray["directlink"]["pixel_url"] 	            = $directlink_pixel_url;
		if($adcodeType == 21 && $feed_ad == 1 && $directlinkAD == 1)
        $returnArray["directlink"]["source"] 	    = 1;
		else if($adcodeType == 21 && $exchange_ad == 1 && $directlinkAD == 1)
		$returnArray["directlink"]["source"] 	    = 2;
		else 
		$returnArray["directlink"]["source"] 	    = 0;

		//Jesus
        //file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/2.php",print_r($returnArray,true));

        return $returnArray;
	}
	*/

	function get_ads_from_ads_list($displayAdCount, $adListArray, $adcodeType)
	{
		$result = [
			"paid_ads" => "",
			"default_ads" => "",
			"ad_id_list" => "",
			"skin_ad_content" => "",
			"expandable_content" => "",
			"pop" => [
				"pop_ad" => 0,
				"click_url" => "",
				"pop_type" => 0,
				"pop_window_width" => 0,
				"pop_window_height" => 0,
				"original_pop_ad" => 0,
				"pixel_url" => "",
				"source" => 0
			],
			"directlink" => [
				"directlink_ad" => 0,
				"click_url" => "",
				"original_directlink_ad" => 0,
				"pixel_url" => "",
				"source" => 0
			]
		];
		$originalAdExists = 0;
		$defaultAdAllowed = $displayAdCount;
		$feed_ad = 0;
		$exchange_ad = 0;
		if(!empty($adListArray[0])) //Paid Ads Case
		{
			$originalAdArray = $adListArray[0];
			$originalAdCount = count($originalAdArray);
			$originalAdExists = 1;
			$selectedOriginalKeys = [];
			if ($originalAdCount >= $displayAdCount) 
			{
				$premiumEnabled = Configuration::get_instance()->read('allow_premium_ads');
				if ($premiumEnabled == 1) {
					$selectedOriginalKeys = array_slice(array_keys($originalAdArray), 0, $displayAdCount);
				} else {
					$selectedOriginalKeys = (array) array_rand($originalAdArray, $displayAdCount);
				}
				$defaultAdAllowed = 0;
			} else {
				$selectedOriginalKeys = array_keys($originalAdArray);
				$defaultAdAllowed = $displayAdCount - $originalAdCount;
			}
			foreach ($selectedOriginalKeys as $key) {
				$result["paid_ads"] .= ($result["paid_ads"] !== "" ? ".data." : "") . $originalAdArray[$key];
				$result["ad_id_list"] .= ($result["ad_id_list"] !== "" ? "-" : "") . $key;
				$result["skin_ad_content"] = $adListArray[2][$key] ?? $result["skin_ad_content"];
				$result["expandable_content"] = $adListArray[3][$key][0] ?? $result["expandable_content"];
				if (!empty($adListArray[4][$key])) {
					$adData = $adListArray[4][$key];
					if ($adcodeType == 9) {
						$result["pop"] = array_merge($result["pop"], [
							"pop_ad" => 1,
							"pop_type" => $adData['poptype'],
							"pop_window_width" => $adData['pop_window_width'],
							"pop_window_height" => $adData['pop_window_height'],
							"original_pop_ad" => $adData['originalpopad'],
							"click_url" => $adData['click_url'],
							"pixel_url" => $adData['pixel_url']
						]);
					} elseif ($adcodeType == 21) {
						$result["directlink"] = array_merge($result["directlink"], [
							"directlink_ad" => 1,
							"original_directlink_ad" => $adData['originaldirectlinkad'],
							"click_url" => $adData['click_url'],
							"pixel_url" => $adData['pixel_url']
						]);
					}
					$feed_ad = $adData['feed_ad'];
					$exchange_ad = $adData['exchange_ad'];
				}
			}
		}
		// Default Ads Case
		if (!empty($adListArray[1]) && $defaultAdAllowed > 0) {
			$defaultAdArray = $adListArray[1];
			$defaultAdCount = count($defaultAdArray);
			$selectedDefaultKeys = $defaultAdCount > $defaultAdAllowed ? (array) array_rand($defaultAdArray, $defaultAdAllowed) : array_keys($defaultAdArray);
			foreach ($selectedDefaultKeys as $key) {
				$result["default_ads"] .= ($result["default_ads"] !== "" ? "_" : "") . $defaultAdArray[$key];
				$result["ad_id_list"] .= ($result["ad_id_list"] !== "" ? "-" : "") . $key;
				$result["skin_ad_content"] = $adListArray[2][$key] ?? $result["skin_ad_content"];
				$result["expandable_content"] = $adListArray[3][$key][0] ?? $result["expandable_content"];
				if (!empty($adListArray[4][$key])) {
					$adData = $adListArray[4][$key];
					if ($adcodeType == 9) {
						$result["pop"] = array_merge($result["pop"], [
							"pop_ad" => 1,
							"pop_type" => $adData['poptype'],
							"pop_window_width" => $adData['pop_window_width'],
							"pop_window_height" => $adData['pop_window_height'],
							"original_pop_ad" => $adData['originalpopad'],
							"click_url" => $adData['click_url'],
							"pixel_url" => $adData['pixel_url']
						]);
					} elseif ($adcodeType == 21) {
						$result["directlink"] = array_merge($result["directlink"], [
							"directlink_ad" => 1,
							"original_directlink_ad" => $adData['originaldirectlinkad'],
							"click_url" => $adData['click_url'],
							"pixel_url" => $adData['pixel_url']
						]);
					}
					$feed_ad = $adData['feed_ad'];
					$exchange_ad = $adData['exchange_ad'];
				}
			}
		}
		if ($adcodeType == 9 && $result["pop"]["pop_ad"] == 1) {
			$result["pop"]["source"] = $feed_ad ? 1 : ($exchange_ad ? 2 : 0);
		}
		if ($adcodeType == 21 && $result["directlink"]["directlink_ad"] == 1) {
			$result["directlink"]["source"] = $feed_ad ? 1 : ($exchange_ad ? 2 : 0);
		}
		//Jesus
		//file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/2.php",print_r($result,true));
		return $result;
	}
	function get_impression_data($data_get_string,$adtype,$cookieDataGet = "",$vast = 0)
	{
		$maxImpressionLimit = 0;

		if($adtype ==1) //CPM
		$maxImpressionLimit = intval(Configuration::get_instance()->read('cpm_ad_impression_limit_hour'));
		else if($adtype ==2)  //HTML
		$maxImpressionLimit = intval(Configuration::get_instance()->read('html_ad_impression_limit_hour'));
		else if($adtype ==0 || $adtype ==3 || $adtype ==6)  //CPC/CPD/CPA
		$maxImpressionLimit = 5;
		else if($adtype ==9)  //POP
		$maxImpressionLimit = intval(Configuration::get_instance()->read('pop_ad_impression_limit_hour'));
		else if($adtype ==13)  //CPV
		$maxImpressionLimit = intval(Configuration::get_instance()->read('cpv_ad_impression_limit_hour'));

		if($cookieDataGet != "")
		$cookieDataGet = $this->mybase64_decode($cookieDataGet);
		else
		{
		    if($adtype == 13 && $vast == 1)
			{
				if(isset($_COOKIE['_data_cpv']))
				$cookieDataGet = $_COOKIE['_data_cpv'];
			}
		}

		if($maxImpressionLimit > 0)
		{
			$data_get_array       = explode('.data.',$data_get_string);
			$data_get_array_count = count($data_get_array);

			for($i=0;$i < $data_get_array_count;$i++)
			{
				$data_single_array  = explode('|',$data_get_array[$i]);
				$aid                = $data_single_array[1];

				if($cookieDataGet != '')
				{
					$capTimeCurrent  = time();
					$capCookieArray  = explode('_',$cookieDataGet);

					foreach($capCookieArray as $capKey => $capValue)
					{
						if(trim($capValue) != "")
						{
								$capValueArray = explode('-',$capValue);

								if($capValueArray[0] == $aid && $capValueArray[1] >= $maxImpressionLimit && $capValueArray[2] >= $capTimeCurrent)
								unset($data_get_array[$i]);
						}
					}
				}
			}

			//if(count($data_get_array) >0)
			//$data_get_string=implode('.data.',$data_get_array);
			//else
			//$data_get_string="";

			if(count($data_get_array) > 1)
			$data_get_string     = implode('.data.',$data_get_array);
			else if(count($data_get_array) == 1)
			{
					$data_get_array  = array_values($data_get_array);
					$data_get_string = $data_get_array[0];
			}
			else
			$data_get_string     = "";
		}

		return $data_get_string;
	}

	function remove_iframe($adcodeid, $message = 0, $displayType = "")
	{
		$show_iframe_space = intval(Configuration::get_instance()->read('show_iframe_space'));
		$string_data       = "";

		//file_put_contents(ROOT_DIR_PATH.CACHE_DIR."/cache/3.php",$show_iframe_space);

		if($show_iframe_space == 0 && $displayType != 9)
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
			else if($message ==18)
			$message_data="No ads available.";
			else if($message ==19)
			$message_data="POP ads addon disabled.But try to display pop ads.";
			else if($message ==20)
			$message_data="Directlink ads addon disabled.But try to display directlink ads.";
			else if($message ==21)
			$message_data="In-page push addon disabled.But try to display In-page push ads.";

			if($displayType == 9)
			$string_data='{"adResponse => '.$message_data.'"}';
			else
			$string_data="<div style='display:none;'>".$message_data."</div>";

		}

		return $string_data;
	}

	function feedNotification($feedID,$admarket_name,$message = "")
	{
		header("Content-type: text/xml; charset=utf-8");
		header('Access-Control-Allow-Origin: *');

		$xmldata ='<?xml version="1.0" encoding="UTF-8"?>';
		$xmldata.='<feed id ="'.$feedID.'">';
		$xmldata.='<adnetwork>'.$admarket_name.'</adnetwork>';
		$xmldata.='<error>';
		$xmldata.=$message;
		$xmldata.='</error>';
		$xmldata.='</feed>';

		echo $xmldata;
		die;
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

   function getFeedAdsFromlist($displayAdCount,$adListArray)
   {
       $originalAdExists   	 		= 0;
       $defaultAdAllowed		 	= $displayAdCount;
       $returnArray                 = array();

       if(isset($adListArray[0]))
       {
           	$originalAdArray = $adListArray[0];
           	$originalAdCount = count($originalAdArray);

		    if($originalAdCount > 0)
		    {
		         $originalAdExists   = 1;

		         if($originalAdCount < $displayAdCount)
		         {
		             $defaultAdAllowed = $displayAdCount - $originalAdCount;

		             foreach($originalAdArray as $k1 => $v1)
		             {
		               	$returnArray[$k1]['impression_string'] = $v1;

	             		if(isset($adListArray[2][$k1]))
						{
							$returnArray[$k1]['title']        = $adListArray[2][$k1]['title'];
							$returnArray[$k1]['description']  = $adListArray[2][$k1]['description'];
							$returnArray[$k1]['display_url']  = $adListArray[2][$k1]['display_url'];
							$returnArray[$k1]['click_url']    = $adListArray[2][$k1]['click_url'];
							$returnArray[$k1]['pixel_url']    = $adListArray[2][$k1]['pixel_url'];
							$returnArray[$k1]['banner_url']   = $adListArray[2][$k1]['banner_url'];
							$returnArray[$k1]['icon_url']     = $adListArray[2][$k1]['icon_url'];
							$returnArray[$k1]['default_rate'] = $adListArray[2][$k1]['default_rate'];
							$returnArray[$k1]['paid_ad']      = 1;
						}
		            }
		        }
               	else
               	{
               		$defaultAdAllowed = 0;

					$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

					if($premium_ad_enabled == 1)
					{
						$dIndex         = 0;
						$randomKeysData = array();

						foreach($originalAdArray as $dKey => $dValue)
						{
							if($dKey > 0)
							$randomKeysData[] = $dKey;

							$dIndex++;

							if($dIndex >= $displayAdCount)
							break;
						}
					}
					else
					$randomKeysData 	  = array_rand($originalAdArray,$displayAdCount);

               		if(!is_array($randomKeysData))
               		{
               			$randomKeys[] 	= $randomKeysData;
               		}
               		else
               		$randomKeys = $randomKeysData;

               		foreach($randomKeys as $k1 => $v1)
               		{
		               	$returnArray[$v1]['impression_string'] = $originalAdArray[$v1];

						if(isset($adListArray[2][$v1]))
						{
							$returnArray[$v1]['title']        = $adListArray[2][$v1]['title'];
							$returnArray[$v1]['description']  = $adListArray[2][$v1]['description'];
							$returnArray[$v1]['display_url']  = $adListArray[2][$v1]['display_url'];
							$returnArray[$v1]['click_url']    = $adListArray[2][$v1]['click_url'];
							$returnArray[$v1]['pixel_url']    = $adListArray[2][$v1]['pixel_url'];
							$returnArray[$v1]['banner_url']   = $adListArray[2][$v1]['banner_url'];
							$returnArray[$v1]['icon_url']     = $adListArray[2][$v1]['icon_url'];
							$returnArray[$v1]['default_rate'] = $adListArray[2][$v1]['default_rate'];
							$returnArray[$v1]['paid_ad']      = 1;
						}
               		}
               	}
            }
       }

       if(isset($adListArray[1]) && $defaultAdAllowed > 0)
       {
           	$defaultAdArray = $adListArray[1];
           	$defaultAdCount = count($defaultAdArray);

            if($defaultAdCount < $defaultAdAllowed)
            {
               	foreach($defaultAdArray as $k1 => $v1)
               	{
	               	$returnArray[$k1]['impression_string'] = $v1;

             		if(isset($adListArray[2][$k1]))
					{
						$returnArray[$k1]['title']        = $adListArray[2][$k1]['title'];
						$returnArray[$k1]['description']  = $adListArray[2][$k1]['description'];
						$returnArray[$k1]['display_url']  = $adListArray[2][$k1]['display_url'];
						$returnArray[$k1]['click_url']    = $adListArray[2][$k1]['click_url'];
						$returnArray[$k1]['pixel_url']    = $adListArray[2][$k1]['pixel_url'];
						$returnArray[$k1]['banner_url']   = $adListArray[2][$k1]['banner_url'];
						$returnArray[$k1]['icon_url']     = $adListArray[2][$k1]['icon_url'];
						$returnArray[$k1]['default_rate'] = $adListArray[2][$k1]['default_rate'];
						$returnArray[$k1]['paid_ad']      = 0;
					}
               	}
            }
            else
            {
               	$randomKeysData = array_rand($defaultAdArray,$defaultAdAllowed);

				if(!is_array($randomKeysData))
				{
					$randomKeys[] 	= $randomKeysData;
				}
				else
				$randomKeys = $randomKeysData;

				foreach($randomKeys as $k1 => $v1)
				{
					$returnArray[$v1]['impression_string'] = $defaultAdArray[$v1];

					if(isset($adListArray[2][$v1]))
					{
						$returnArray[$v1]['title']        = $adListArray[2][$v1]['title'];
						$returnArray[$v1]['description']  = $adListArray[2][$v1]['description'];
						$returnArray[$v1]['display_url']  = $adListArray[2][$v1]['display_url'];
						$returnArray[$v1]['click_url']    = $adListArray[2][$v1]['click_url'];
						$returnArray[$v1]['pixel_url']    = $adListArray[2][$v1]['pixel_url'];
						$returnArray[$v1]['banner_url']   = $adListArray[2][$v1]['banner_url'];
						$returnArray[$v1]['icon_url']     = $adListArray[2][$v1]['icon_url'];
						$returnArray[$v1]['default_rate'] = $adListArray[2][$v1]['default_rate'];
						$returnArray[$v1]['paid_ad']      = 0;
					}
				}
            }
        }

        return $returnArray;
	}
};
?>
