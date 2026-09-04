<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

if(file_exists(ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php"))
include_once ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php";

if(file_exists(ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php"))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";

if(file_exists(LIB_DIR_PATH."getID3/getid3.php"))
include_once(LIB_DIR_PATH."getID3/getid3.php");

class AdController extends ApplicationController
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


			if(!isset($privilege['aa_1']) && !isset($privilege['aa_2']) && !isset($privilege['aa_3']))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if($this->get_action() !="list" && $this->get_action() !="view" && $this->get_action() !="change_status" && $this->get_action() !="status_mail" && $this->get_action() !="preview")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['aa_1']) && $this->get_action() =="list")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['aa_2']) && $this->get_action() =="view")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['aa_3']) && $this->get_action() =="change_status" || $this->get_action() =="status_mail")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
		}
	}


	function load_ad_preview_action()
	{
		$this->disable_notice_area();
		$db = DAL::get_instance();

		$adLayoutID  		 = intval($this->read_post_param("adLayoutID"));
		$adcodeID  		     = intval($this->read_post_param("adcodeID"));
		$adType 			 = intval($this->read_post_param("adType")); //1 => Text only, 11 => Text + image only
		$titleEnabled      	 = intval($this->read_post_param("titleEnabled"));
		$descriptionEnabled  = intval($this->read_post_param("descriptionEnabled"));
		$urlEnabled      	 = intval($this->read_post_param("urlEnabled"));

		$titleText      	 = $this->read_post_param("titleText");
		$descriptionText     = $this->read_post_param("descriptionText");
		$displayurlText      = $this->read_post_param("displayurlText");
		$buttonText      	 = $this->read_post_param("buttonText");
		$bannerName      	 = $this->read_post_param("bannerName");


		$creditTextID  			= 0;
		$creditAlignment  		= 0;
		$creditPositioning  	= 0;
		$stickySupport  		= 0;
		$stickyClosePosition  	= 0;
		$responsiveSupport		= 0;
		$windowWidth  			= 0;
		$windowHeight  			= 0;
		$stickyPosition  		= "";

		$buttonSectionWidth     = 0;
		$buttonSectionHeight    = 50;
		$buttonEnabled      	= 1;
		$titleBorder      		= 0;
		$descriptionBorder      = 0;
		$displayurlBorder      	= 0;
		$contentSlide      		= 0;
		$slideDirection      	= 0;
		$slideDuration      	= 0;
		$CTAPosition      		= 2;
		$CTABorderRadius      	= 5;
		$CTAPaddingHorizontal   = 10;
		$CTAPaddingVertical     = 5;

		if($adLayoutID > 0) //Preview of cpd ad position
		{
			$result      = $db->execute_query("select ab.*,au.* from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au where ab.id = au.blockid and au.id = ?",array($adcodeID));


			if($result->get_num_records() == 0)
			{
				echo ""; //No adcode or adblock exists
				die;
			}

			$resultData = $result->fetch_assoc();


			$bannerSize  			= $resultData['bannersize'];
			$textimageSize  		= $resultData['textimage_size'];
			$imagePosition  		= $resultData['image_position'];
			$adblockWidth  			= $resultData['width'];
			$adblockHeight  		= $resultData['height'];
			$layoutTheme  	        = $resultData['theme'];
			$layoutFont             = $resultData['font'];
			$borderType  			= $resultData['abr_type'];


			$responseResult = $this->get_adlayout_preview(4,1,$adType,$adType,$adLayoutID,$borderType,$bannerSize,$textimageSize,$imagePosition,$adblockWidth,$adblockHeight,$creditTextID,$creditAlignment,$creditPositioning,$layoutTheme,$layoutFont,$adcodeID,$layoutTheme,$layoutFont,0,0,0,0,"",$buttonSectionWidth,$buttonSectionHeight,$titleEnabled,$descriptionEnabled,$urlEnabled,$buttonEnabled,$titleBorder,$descriptionBorder,$displayurlBorder,$contentSlide,$slideDirection,$slideDuration,$CTAPosition,$CTABorderRadius,$CTAPaddingHorizontal,$CTAPaddingVertical,$titleText,$descriptionText,$displayurlText,$buttonText,$bannerName);

		}
		else
		{
			$bannerSize  		    = 0;
			$textimageSize  	    = intval($this->read_post_param("textimageSize"));
			$imagePosition  		= 0;
			$adblockWidth  			= 300;
			$adblockHeight  		= 250;
			$layoutTheme      		= 1;
			$layoutFont       		= 1;
			$borderType  			= 1;


			$responseResult = $this->get_adlayout_preview(3,1,$adType,$adType,$adLayoutID,$borderType,$bannerSize,$textimageSize,$imagePosition,$adblockWidth,$adblockHeight,$creditTextID,$creditAlignment,$creditPositioning,$layoutTheme,$layoutFont,0,0,0,0,0,0,0,"",          $buttonSectionWidth,$buttonSectionHeight,$titleEnabled,$descriptionEnabled,$urlEnabled,$buttonEnabled,$titleBorder,$descriptionBorder,$displayurlBorder,$contentSlide,$slideDirection,$slideDuration,$CTAPosition,$CTABorderRadius,$CTAPaddingHorizontal,$CTAPaddingVertical,$titleText,$descriptionText,$displayurlText,$buttonText,$bannerName);
		}


		echo $responseResult;

		die;
	}







	function delete_banner_action()
	{
		//$this->disable_notice_area();
		$db   = DAL::get_instance();

		$bannerID   = $this->read_post_param("bannerID");
		$aid        = $this->read_post_param("aid");
		$fromData   = intval($this->read_post_param("fromData")); //0=>affiliate banner 1=>other ads banners


		if($fromData == 0)
		{
			$bannerID = intval($db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ad_image_mapping WHERE id=? AND aid=?",array($bannerID,$aid)));

			if($bannerID == 0)
			{
				echo 1;
				exit;
			}

			$db->execute_query("UPDATE ".TABLE_PREFIX."ad_image_mapping SET status=0 WHERE id=?",array($bannerID));

			echo 2;
			exit;
		}
		else
		{
			$bannerList = $db->read_single_column("SELECT banner_list FROM ".TABLE_PREFIX."ads WHERE id = ? ",array($aid));

			if($bannerList == "")
			{
				echo 1;
				exit;
			}

			$bannerListArray = json_decode($bannerList,1);
			$tempArray       = array();
			$deleteBanner    = "";

			foreach($bannerListArray as $bKey=>$bValue)
			{
				if($bValue != "")
				{
					if($bKey == $bannerID)
					$deleteBanner = $bValue;
					else
					$tempArray[$bKey] = $bValue;
				}
			}

			$tempArrayJson = "";

			if(count($tempArray) > 0)
			$tempArrayJson = json_encode($tempArray);

			$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET banner_list = ? WHERE id = ? ",array($tempArrayJson,$aid));

			if($deleteBanner != "" && file_exists('../'.DATA_DIR.'/'.$aid.'/'.$deleteBanner))
			unlink('../'.DATA_DIR.'/'.$aid.'/'.$deleteBanner);

			echo 2;
			exit;
		}
	}


	function list_action()
	{
		$db= DAL::get_instance();
		$manager_str='';
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=intval($this->read_cookie_param(COOKIE_ADMIN_LOGINID));
		$adsid='';
		$adsname='';
		$username='';
		$search_text='';

		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==2)
			$manager_str=" and adv_managerid=".$adminid;
		}

		if($_POST)
		{
			$status=intval($this->read_post_param('status'));
			$adtype=intval($this->read_post_param('type'));
			$adpricing=intval($this->read_post_param("adpricing"));
			$search_by=$this->read_post_param('search_by');
			if($search_by==1)
			{
			    $search_text=$this->read_post_param('adsid');
			}
			else if($search_by==2)
			{
			    $search_text=$this->read_post_param('name');
			}
			else if($search_by==3)
			{
			    $search_text=$this->read_post_param('username');
			}

			$premiumStatus = intval($this->read_post_param('premiumStatus'));
		}
		else
		{
			$status=$this->read_page_param(1);
			$adtype=$this->read_page_param(2);
			$adpricing=$this->read_page_param(3);
			$premiumStatus=trim($this->read_page_param(4));
			$search_by=$this->read_page_param(5);
			$search_text=$this->read_page_param(6);


			if($premiumStatus === "")
			$premiumStatus = 2;

			$premiumStatus = intval($premiumStatus);

			$exp=explode("-",$status);
			if($exp[0]=="page")
				$status=2;

			$exp=explode("-",$adtype);
			if($exp[0]=="page")
				$adtype=0;

			$exp=explode("-",$adpricing);
			if($exp[0]=="page")
			$adpricing=-1;

			$exp=explode("-",$premiumStatus);
			if($exp[0]=="page")
			$premiumStatus=2;

			$exp=explode("-",$search_by);
			if($exp[0]=="page")
			    $search_by=0;

			$exp=explode("-",$search_text);
			if($exp[0]=="page")
			    $search_text=0;
		}

		$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

		if($premium_ad_enabled == 0)
		$premiumStatus=2;

		$search_text = str_replace("%","\%",$search_text);
		$search_text = str_replace("_","\_",$search_text);

		if($search_by==1)
		$adsid=intval($search_text);
		else if($search_by==2)
		$adsname=$db->sanitize($search_text);
		else if($search_by==3)
	        $username=$db->sanitize($search_text);




		$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
		$this->set_variable('ecommerce_enabled',$ecommerce_enabled);


		if($adpricing === "")
		$adpricing=-1;

		if($adtype === "")
		$adtype=0;

		if($status === "")
		$status=2;


		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

		$this->set_variable('text_ads_enabled',$text_ads_enabled);

		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		$cpp_enabled=$this->get_addon_status('cpp_enabled');


		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" and a.display_type=".intval($adpricing)." ";

		if($this->get_addon_status('cpc_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>0 ';

		if($this->get_addon_status('cpm_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>1 ';

		if($this->get_addon_status('cpa_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>6 ';

		if($this->get_addon_status('sponsored_enabled') !=1)
		$adpricing_str.=' AND a.display_type <>3 ';

		if($cpp_enabled !=1)
		$adpricing_str.=' AND a.display_type <>18 ';

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

		if($cpp_enabled !=1)
		$adtype_str.=' AND a.type <>18 ';

		if($status ==2)
		$status_str=" AND a.status <> -2 ";
		else
		$status_str=" and a.status=".intval($status)." ";

		$search_string="";

		if($search_by == 1)
		$search_string = " and a.id=".$adsid." ";

		else if($search_by == 2)
		$search_string = " and a.name LIKE '".$adsname."%' ";

		else if($search_by == 3)
		$search_string = " and u.username LIKE '".$username."%' ";

		$premiumString = "";

		if($premiumStatus == 1 || $premiumStatus == 0)
		$premiumString = " AND a.premium_ad = ".$premiumStatus." ";

		$this->set_variable("type",$adtype);
		$this->set_variable("status",$status);
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("premiumStatus",$premiumStatus);


		$this->set_title($this->get_label('view ads'));

		$pagination = new Pagination("SELECT a.*,u.username FROM ".TABLE_PREFIX."ads a INNER JOIN ".TABLE_PREFIX."users u  ON a.uid = u.id where a.uid<>0 AND ecommerce_parent=0 ".$adtype_str.$status_str.$adpricing_str." ".$manager_str.$search_string.$premiumString." ORDER BY id desc");
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);

		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);

		$search_text = str_replace("\%","%",$search_text);
		$search_text = str_replace("\_","_",$search_text);
		$this->set_variable('search_by',$search_by);
		if($search_text!='')
		{

		$this->set_variable('adsid',$search_text);
		$this->set_variable('adsname',$search_text);
		$this->set_variable('username',$search_text);
		}
		else
		{
		    $this->set_variable("adsid", $adsid);
		    $this->set_variable("adsname", $adsname);
		    $this->set_variable("username", $username);
		}
	}


	function view_action()
	{
		$this->set_title($this->get_label('view ads'));
		$ad_id=$this->read_page_param(1);


		if(!$this->get_ad_validation_user($ad_id))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
		}


		$adp=$this->get_ad_pricing_value($ad_id);


		if($adp ==0 && $this->get_addon_status('cpc_enabled') !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);

		if($adp ==1 && $this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);

		if($adp ==3 && $this->get_addon_status('sponsored_enabled') !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);

		if($adp ==6 && $this->get_addon_status('cpa_enabled') !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);

		if($adp ==18 && $this->get_addon_status('cpp_enabled') !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);


		$db= DAL::get_instance();
		$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($ad_id));
		$this->set_result("res",$res);

		$rowdata=$res->fetch_assoc();
		$uid=$rowdata['uid'];
		$pricing=$rowdata['display_type'];


		if($rowdata['type'] ==7 && $rowdata['ecommerce_parent'] ==0)
		$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);

		if($_POST)
		{
			$duration  = intval($this->read_post_param("duration"));
			$tab       = $this->read_post_param("tab");
			$from_date = $this->read_post_param("from_date");
			$to_date   = $this->read_post_param("to_date");
		}
		else
		{
			$duration  = 1;
			$tab       = 6;
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

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);

		if($tab=="" || $tab==0)
		$tab=6;

		if($duration == 0)
		$duration = 1;


		$timePeriod[] = $duration;

		if($duration == 7)
		{
			$timePeriod[] = $from_date;
			$timePeriod[] = $to_date;
		}


		$dbResult = $this->get_advertiser_statistics($timePeriod, 1, $pricing, $uid, $ad_id);
		$this->set_array("reportResult",$dbResult);

		$dbResultTimeperiod = $this->get_advertiser_timeperiod_statistics($timePeriod, 1, $pricing, $uid, $ad_id);
		$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);


		if($adp == 3)
		{
			$keywords_data = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE aid = ? AND (status = 2 OR status = 3 OR status = 4) ORDER BY id DESC",array($ad_id));
		}
		else
		{
			$keywords_data = $db->execute_query("SELECT k.id,k.keyword FROM ".TABLE_PREFIX."keywords k INNER JOIN ".TABLE_PREFIX."ad_keyword_mapping m ON k.id=m.kid and m.aid=? AND m.kid <>0",array($ad_id));
		}

		$adResult = array();


		if($adp == 0)
		$pricingValue = "cpc";
		else if($adp == 1)
		$pricingValue = "cpm";
		else if($adp == 3)
		$pricingValue = "cpd";
		else if($adp == 6)
		$pricingValue = "cpa";		
		else if($adp == 18)
		$pricingValue = "cpp";

		if($keywords_data->get_num_records() > 0)
		{
			while($resultData = $keywords_data->fetch_assoc())
			{
				$kid         = $resultData['id'];
				$dbResult    = $this->get_advertiser_statistics($timePeriod, 1, $pricing, $uid, $ad_id, $kid);

				if($adp == 3)
				{
					$siteName      = CategoryHelper::get_site_name($resultData['site']);
			      	$positionName  = SponsoredHelper::get_position_data($resultData['position'],0);

			      	$sectionString = "<div>";

			      	if($siteName == "")
			      	$sectionString.= $this->get_label("site deleted");
			      	else
			      	$sectionString.= $siteName;

			      	$sectionString.= "</div>";

			      	$sectionString.= "<div style='font-weight: bold;'>";

			      	if($positionName == "")
			      	$sectionString.= $this->get_label("position deleted");
			      	else
			      	$sectionString.= "<a href='".$this->make_url("dispatch/sponsored/13/".$resultData['position'])."'>".$positionName."</a>";

			      	$sectionString.= "</div>";


			      	$sectionString.= "<div><bdi>".$this->get_label("package days rate label",array("x"=>$resultData['position_days'],"y"=>$this->get_money_format($resultData['cpd_rate_total'])))."</bdi></div>";

					$sectionString.= "<div><bdi>".$this->get_label('status')." : ".SponsoredHelper::get_ad_mapping_status($resultData['status'])."</bdi></div>";

					$sectionString.= "<div>".$this->get_label('publisher')." : ";

					if($resultData['publisher'] == 0)
					$sectionString.= $this->get_label("admin");
					else
					$sectionString.= "<a href='".$this->make_url("user/profile/".$resultData['publisher']."/0")."'>".$this->get_user_name($resultData['publisher'])."</a>";

					$sectionString.= "</div>";

					$firstValue['keyword']      = $sectionString;
				}
				else
				$firstValue['keyword']      = $resultData['keyword'];

				$arrayMerge                 = array_merge($firstValue,$dbResult[$pricingValue]);
				$adResult[$kid]             = $arrayMerge;
			}
		}


		if($adp != 3)
		{
			$dbResult = $this->get_advertiser_statistics($timePeriod, 1, $pricing, $uid, $ad_id, 0);

			$firstValue['keyword']      = $this->get_label("global targeted");
			$arrayMerge                 = array_merge($firstValue,$dbResult[$pricingValue]);
			$adResult['globalTargeted'] = $arrayMerge;
		}

		if($adp == 3)
		$firstHeadingArray['firstHeading'] 	= $this->get_label('position details'); //Set ad array
	    else
	    $firstHeadingArray['firstHeading'] 	= $this->get_label('keyword');

		$dbHeading    		=  $dbResult['heading'];

		$dbHeadingMerge 	=  array_merge($firstHeadingArray,$dbHeading);

		$adResult['heading'] = $dbHeadingMerge;

		if($adp == 3)
		$this->set_array("adResult",$adResult,array('keyword'));
	    else
	    $this->set_array("adResult",$adResult);

		$this->set_variable("adid",$ad_id);
		$this->set_variable("duration",$duration);

		$this->set_variable("tab",$tab);

		$query_key=$db->execute_query("SELECT k.keyword FROM ".TABLE_PREFIX."keywords k INNER JOIN ".TABLE_PREFIX."ad_keyword_mapping m ON k.id=m.kid and m.aid=? AND m.kid <>0",array($ad_id));
		$this->set_result("query_res",$query_key);

		$numbers=$query_key->get_num_records();
		$this->set_variable("numbers",$numbers);

		$adpriceing=$this->get_ad_pricing_value($ad_id);
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');


		if($this->get_addon_status('city-targeting_enabled') ==1)
		{


		}
		else
		{
			$locquery_key=$db->execute_query("select country_code from ".TABLE_PREFIX."ad_geographic_mapping where aid=? and country_code<>'0'",array($ad_id));
			$this->set_result("locquery_res",$locquery_key);

			$locnumbers=$locquery_key->get_num_records();
			$this->set_variable("locnumbers",$locnumbers);
		}
	}

	function premium_action()
	{
		$db = DAL::get_instance();

		$aid             = $this->read_page_param(1);
		$premium_type    = intval($this->read_page_param(2));
		$frompg          = $this->read_page_param(3);
		$duration        = $this->read_page_param(4);
		$at              = $this->read_page_param(5);
		$st              = $this->read_page_param(6);
		$adpricing       = $this->read_page_param(7);
		$premiumStatus   = $this->read_page_param(8);

		if($frompg == 2)
		{
			$search_by     	 = $this->read_page_param(9);
			$pg              = $this->read_page_param(10);
		}
		else
		{
			$search_by     	 = "";
			$pg              = $this->read_page_param(9);
		}

		if(!$this->get_check_advertiser_ad($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);
		}

		if($premium_type != 0 && $premium_type != 1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);


		$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET premium_ad = ? WHERE id = ?",array($premium_type,$aid));
		$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET premium_ad = ? WHERE id = ?",array($premium_type,$aid));


		$uid      = $db->read_single_column("select uid from ".TABLE_PREFIX."ads where id=?",array($aid));
		$username = $db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));


		$search_by_value = "";

		if($search_by == 1)
		$search_by_value = $aid;
		else if($search_by == 2)
		$search_by_value = $db->read_single_column("select name from ".TABLE_PREFIX."ads where id=?",array($aid));
		else if($search_by == 3)
		$search_by_value = $username;




		if($frompg == 1)
		$this->flash($this->get_message('ad premium status updated successfully'), $this->make_url('ad/view/'.$aid));
		else if($frompg == 3)
		{
				$uid = $db->read_single_column("SELECT uid from ".TABLE_PREFIX."ads WHERE id = ?",array($aid));

				$this->flash($this->get_message('ad premium status updated successfully'), $this->make_url('user/profile/').$uid.'/2/a/'.$duration.'/'.$at.'/'.$st.'/'.$adpricing.'/'.$premiumStatus.'/'.$pg);
		}
		else
		$this->flash($this->get_message('ad premium status updated successfully'), $this->make_url('ad/list/'.$st.'/'.$at.'/'.$adpricing.'/'.$premiumStatus."/".$search_by."/".$search_by_value.'/'.$pg));

	}


	function change_status_action()
	{
		$this->set_title($this->get_label('manage ad'));

		$aid=$this->read_page_param(1);
		$frompg=$this->read_page_param(2);

		$duration=$this->read_page_param(3);
		$at=$this->read_page_param(4);
		$st=$this->read_page_param(5);
		$adpricing=$this->read_page_param(6);
		$premiumStatus   = $this->read_page_param(7);

		if($frompg == 2)
		{
			$search_by     	 = $this->read_page_param(8);
			$pg              = $this->read_page_param(9);
		}
		else
		{
			$search_by     	 = "";
			$pg              = $this->read_page_param(8);
		}



		if($adpricing =="")
		$adpricing=-1;

		if(!$this->get_check_advertiser_ad($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);
		}

		$db= DAL::get_instance();


		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
		$rowdata=$row->fetch_assoc();

		$uid=$rowdata['uid'];
		$adtype=$rowdata['type'];
		$status=$rowdata['status'];
		$ecommerce_parent=$rowdata['ecommerce_parent'];



		$this->set_variable('uid',$uid);
		$this->set_variable('aid', $aid);
		$this->set_variable('frompg', $frompg);
		$this->set_variable('status', $status);
		$this->set_variable('adtype',$adtype);
		$this->set_variable('ecommerce_parent',$ecommerce_parent);

		$this->set_variable('duration', $duration);
		$this->set_variable('at', $at);
		$this->set_variable('st', $st);
		$this->set_variable('pg', $pg);
		$this->set_variable('adpricing',$adpricing);
		$this->set_variable('search_by',$search_by);
		$this->set_variable('premiumStatus',$premiumStatus);


		$actmapping=0;

		$adp=$this->get_ad_pricing_value($aid);

		if($adp ==1 && $this->get_addon_status('cpm_enabled') ==1)
		{
			$pricing_status=$db->read_single_column("SELECT pricing_status FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
			if($pricing_status ==1)
			$actmapping=1;
		}

		$this->set_variable('actmapping',$actmapping);



		if($_POST)
		{
			if(DEMO_MODE && $aid <= 100)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('ad/list'),0);
				exit;
			}




			$status=$this->read_post_param('status');
			$search_by=$this->read_post_param('search_by');
			$adpricing=$this->read_post_param('adpricing');
			$premiumStatus   = $this->read_post_param('premiumStatus');

			header("Location: ".$this->make_url("ad/status_mail/".$aid."/".$status."/".$frompg."/".$duration."/".$at."/".$st."/".$adpricing."/".$premiumStatus."/".$search_by."/".$pg));
		}
	}


	function status_mail_action()
	{

		$this->set_title($this->get_label('send mail to adv'));


		$aid=$this->read_page_param(1);


		if(DEMO_MODE && $aid <= 100)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('ad/list'),0);
			exit;
		}




		$status=$this->read_page_param(2);
		$frompg=$this->read_page_param(3);

		$duration=$this->read_page_param(4);
		$at=$this->read_page_param(5);
		$st=$this->read_page_param(6);

		$adpricing=$this->read_page_param(7);
		$premiumStatus=intval($this->read_page_param(8));
		$search_by=$this->read_page_param(9);
		$pg=$this->read_page_param(10);

		$db= DAL::get_instance();

		if($status==1 || $status==0)
		$id=5;
		else if($status==2)
		$id=7;

		$yes_no=0;
		if($_POST)
		{
			$subject=$this->read_post_param('subject');
			$message=$this->read_post_param('message');


			$aid=$this->read_post_param('aid');

			if(DEMO_MODE && $aid <= 100)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('ad/list'),0);
				exit;
			}


			$status=$this->read_post_param('status');
			$frompg=$this->read_post_param('frompg');


			$duration=$this->read_post_param("duration");
			$at=$this->read_post_param("at");
			$st=$this->read_post_param("st");
			$pg=$this->read_post_param("pg");
			$adpricing=$this->read_post_param("adpricing");
			$search_by=$this->read_post_param("search_by");
			$premiumStatus=intval($this->read_post_param("premiumStatus"));



			if(isset($_POST['yes_no']))
			$yes_no=1;


			if(!$this->get_check_advertiser_ad($aid))
			{
				$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);
			}

			if(($subject=="" || $message=="") && $yes_no==0)
			{
				$this->set_notice('mandatory');
			}
			else
			{
				$row0=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
				$rowdata=$row0->fetch_assoc();

				$uid=$rowdata['uid'];
				$gtype=$rowdata['type'];
				$ecommerce_parent=$rowdata['ecommerce_parent'];

				$listarray=array();

				if($gtype ==7 && $ecommerce_parent ==0)
				{
					$row00=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE ecommerce_parent=?",array($aid));
					while($rowdata0=$row00->fetch_assoc())
					{
						$listarray[]=$rowdata0['id'];
					}

					$listarray[]=$aid;
				}
				else if($gtype ==7 && $ecommerce_parent >0)
				{
					$listarray[]=$aid;

					if($status ==2)
					{
						$idcount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where ecommerce_parent=?",array($ecommerce_parent));

						if($idcount ==1)
						$listarray[]=$ecommerce_parent;
					}
				}
				else
				$listarray[]=$aid;

				$mapcountflag=0;
				$actmapflag=0;
				$cpvactmapflag=0;


				if($status==2)
				{
					$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

					$mapcount=0;
					if($sponsored_enabled ==1 || $sponsored_enabled ==0)
					$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE aid=? AND status = 2",array($aid));

					if($mapcount >0 && ($gtype !=7 || ($gtype ==7 && $ecommerce_parent >0)))
					$this->flash($this->get_message('sponsored mappings ad exists'), $this->make_url('ad/list'),0);
				}

				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

				$baseaid=$aid;

				$adp=$this->get_ad_pricing_value($aid);
				$time_enabled=$this->get_addon_status('time-targeting_enabled');

			foreach($listarray as $keylist=>$aid)
			{

				if($status==1)
				{
					if($time_enabled ==1 || $time_enabled ==0)
					{
						$oldstatus123=$db->read_single_column("SELECT status FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));

						if($oldstatus123 ==-1)
						{
							$timerow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
							$timedata=$timerow->fetch_assoc();

							$date_filter=$timedata['date_filter'];
							$start_date=$timedata['tmt_start_date'];
							$end_date=$timedata['tmt_end_date'];

							if(($date_filter ==1 || $date_filter ==2) && $start_date >0 && $end_date >0)
							{
								$currentdate=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
								if($start_date < $currentdate)
								{
									$timedifference=$currentdate-$start_date;
									$newstart=$currentdate;
									$newend=$end_date+$timedifference;

									$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET tmt_start_date=?,tmt_end_date=? WHERE id=?",array($newstart,$newend,$aid));
									$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET tmt_start_date=?,tmt_end_date=? WHERE id=?",array($newstart,$newend,$aid));
								}
							}
						}
					}

					$res=$db->execute_query("update ".TABLE_PREFIX."ads set status=1,start_date=? where id=?",array(time(),$aid));
					$res=$db->execute_query("update ".TABLE_PREFIX."ads_cache set status=b'1' where id=?",array($aid));
				}
				else if($status==0)
				{
				    $res=$db->execute_query("update ".TABLE_PREFIX."ads set status=0 where id=?",array($aid));
				    $res=$db->execute_query("update ".TABLE_PREFIX."ads_cache set status=b'0' where id=?",array($aid));
				}
				else if($status==2)
				{
					$cpm_enabled=$this->get_addon_status('cpm_enabled');
					$pop_enabled=$this->get_addon_status('pop-ads_enabled');
					$directlink_enabled = $this->get_addon_status('direct-link-ads_enabled');
					$video_enabled=$this->get_addon_status('video-ads_enabled');


					if(($mapcountflag ==1 || $actmapflag ==1 || $cpvactmapflag ==1) && $aid == $baseaid)
					continue;

					$actmapping=0;

					if($adp !=3)
					$actmapping=$db->read_single_column("SELECT pricing_status FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));

					$mapcount=0;
					if($sponsored_enabled ==1 || $sponsored_enabled ==0)
					$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE aid=? AND status=2",array($aid));

					if($mapcount >0)
					{
						$mapcountflag=1;
						continue;
					}
					else if($actmapping==1)
					{
						$actmapflag=1;
						continue;
					}

				    $old_data=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($aid));
					$old_data_row=$old_data->fetch_assoc();

					$adtype=$old_data_row['type'];
					$imagename=$old_data_row['banner'];
					$ecommercelogo=$old_data_row['logo'];
					$bannertype=intval($old_data_row['html5']);

						$bannerList = $old_data_row['banner_list'];


						$bannerListArray = array();

						if($bannerList != "")
						$bannerListArray = json_decode($bannerList,1);



						$total_ad_budget= $old_data_row['total_ad_budget'];
						$total_budget_used= $old_data_row['total_budget_used'];
						$remain_budget= $total_ad_budget - $total_budget_used;

						if($remain_budget != 0)
						$this->flash($this->get_message('active pricing exists'), $this->make_url('ad/list'),0);

						if($adtype ==12)
						{
							$imagelist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($aid));

							while($imagerow=$imagelist->fetch_assoc())
							{
								unlink("../".DATA_DIR.'/banners/'.$aid.'/'.$imagerow['image']);
							}

							rmdir("../".DATA_DIR.'/banners/'.$aid);

							$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($aid));
						}
						else
						{
							if($adtype ==2 || $adtype ==11)
							{
								if($bannertype ==0)
								{
									unlink("../".DATA_DIR.'/'.$aid.'_'.$imagename);

									if($adtype ==2 && isset($old_data_row['expandable']) && $old_data_row['expandable'] ==1)
									{
										$expandable_banner=$old_data_row['expandable_banner'];

										unlink("../".DATA_DIR.'/'.$aid.'_exp_'.$expandable_banner);
									}
								}
								else
								{
									if(is_dir("../".DATA_DIR.'/html5/'.$aid.'/'))
									$this->remove_files("../".DATA_DIR.'/html5/'.$aid.'/');


									if($adtype ==2 && isset($old_data_row['expandable']) && $old_data_row['expandable'] ==1)
									{
										if(is_dir("../".DATA_DIR.'/html5/'.$aid.'-exp/'))
										$this->remove_files("../".DATA_DIR.'/html5/'.$aid.'-exp/');
									}
								}
							}
							else if($adtype ==13)
							{
								unlink("../".DATA_DIR.'/video/'.$aid.'/'.$imagename);

								rmdir("../".DATA_DIR.'/video/'.$aid.'/');
							}
							else if($adtype ==14)
							{
								$filename_array=json_decode($imagename,true);

								foreach($filename_array as $rkey=>$rvalue)
								{
									unlink('../'.DATA_DIR.'/'.$aid.'/'.$rvalue);
								}

								rmdir('../'.DATA_DIR.'/'.$aid.'/');
							}

							if($adtype ==2 || $adtype ==11)
							{
								foreach($bannerListArray as $bKey=>$bValue)
								{
									if(file_exists('../'.DATA_DIR.'/'.$aid.'/'.$bValue))
									unlink('../'.DATA_DIR.'/'.$aid.'/'.$bValue);
								}

								rmdir('../'.DATA_DIR.'/'.$aid.'/');
							}
						}


						if($adtype ==7)
						{
							unlink("../".DATA_DIR."/ecommerce/logo/".$ecommercelogo);

							$row123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($aid));
							while($row1234=$row123->fetch_assoc())
							{
								unlink("../".DATA_DIR.'/ecommerce/'.$aid.'/'.$row1234['ad_image']);
							}

							rmdir("../".DATA_DIR.'/ecommerce/'.$aid.'/');


							$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($aid));
						}



						$db->execute_query("delete from ".TABLE_PREFIX."ads where id=?",array($aid));
						$db->execute_query("delete from ".TABLE_PREFIX."ads_cache where id=?",array($aid));
						$db->execute_query("delete from ".TABLE_PREFIX."ad_geographic_mapping where aid=?",array($aid));
						$db->execute_query("delete from ".TABLE_PREFIX."ad_keyword_mapping where aid=?",array($aid));


						$retargeting_enabled=$this->get_addon_status('retargeting_enabled');

						if($retargeting_enabled ==1 || $retargeting_enabled ==0)
						$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE aid=?",array($aid));

						$category_enabled=$this->get_addon_status('category-targeting_enabled');
						$device_enabled=$this->get_addon_status('device-targeting_enabled');
						$isp_enabled=$this->get_addon_status("isp-connection-targeting_enabled");
						$language_enabled=$this->get_addon_status('language-targeting_enabled');

						if($device_enabled ==1 || $device_enabled ==0)
						{
							$db->execute_query("delete from ".TABLE_PREFIX."ad_os_mapping where aid=?",array($aid));
							$db->execute_query("delete from ".TABLE_PREFIX."ad_browser_mapping where aid=?",array($aid));

						}

						if($isp_enabled ==1 || $isp_enabled ==0)
						{
							$db->execute_query("delete from ".TABLE_PREFIX."ad_isp_mapping where aid=?",array($aid));
							$db->execute_query("delete from ".TABLE_PREFIX."ad_connection_mapping where aid=?",array($aid));

						}
						if($language_enabled ==1 || $language_enabled ==0)
						$db->execute_query("delete from ".TABLE_PREFIX."ad_language_mapping where aid=?",array($aid));

						if($category_enabled ==1 || $category_enabled ==0)
						$db->execute_query("delete from ".TABLE_PREFIX."ad_category_mapping where aid=?",array($aid));

						if($sponsored_enabled ==1 || $sponsored_enabled ==0)
						$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE aid=? AND (status=-1 OR status=0 OR status=1)",array($aid));

				}
			}


			if($mapcountflag ==1 && $gtype ==7)
			$this->flash($this->get_message('sponsored mappings ad exists'), $this->make_url('ad/list'),0);


			if($actmapflag ==1 && $gtype ==7)
			$this->flash($this->get_message('active pricing exists'), $this->make_url('ad/list'),0);

			if($yes_no==0)
			UtilityHelper::send_mail($email,$subject,$message);

			$uid=$db->read_single_column("select uid from ".TABLE_PREFIX."ads where id=?",array($aid));
			$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));

            if($search_by==1)
            $value=$aid;
            else if($search_by==2)
            $value=$db->read_single_column("select name from ".TABLE_PREFIX."ads where id=?",array($aid));
            else if($search_by==3)
            $value=$username;


				if($status ==2)
				{
					if($actmapping ==0)
					{
						if($frompg==1 || $frompg==2)
						    $this->flash($this->get_message('successfully deleted'), $this->make_url('ad/list/'.$st.'/'.$at.'/'.$adpricing.'/'.$premiumStatus."/".$search_by."/".$value.'/'.$pg));
						else if($frompg==3)
						$this->flash($this->get_message('successfully deleted'), $this->make_url('user/profile/').$uid.'/2/a/'.$duration.'/'.$at.'/'.$st.'/'.$adpricing.'/'.$premiumStatus.'/'.$pg);
					}
					else if($actmapping >0)
					{
						if($frompg==1 || $frompg==2)
						    $this->flash($this->get_message('active pricing exists'), $this->make_url('ad/list/'.$st.'/'.$at.'/'.$adpricing.'/'.$premiumStatus."/".$search_by."/".$value.'/'.$pg));
						else if($frompg==3)
						$this->flash($this->get_message('active pricing exists'), $this->make_url('user/profile/').$uid.'/2/a/'.$duration.'/'.$at.'/'.$st.'/'.$adpricing.'/'.$premiumStatus.'/'.$pg);
					}
				}
				else
				{
					if($frompg==1 && $status==1)
					$this->flash($this->get_message('successfully activated'), $this->make_url('ad/view/'.$aid));
					if($frompg==1 && $status==0)
					$this->flash($this->get_message('successfully blocked'), $this->make_url('ad/view/'.$aid));


					if($frompg==2 && $status==1)
					    $this->flash($this->get_message('successfully activated'), $this->make_url('ad/list/'.$st.'/'.$at.'/'.$adpricing.'/'.$premiumStatus."/".$search_by."/".$value.'/'.$pg));
					if($frompg==2 && $status==0)
					    $this->flash($this->get_message('successfully blocked'), $this->make_url('ad/list/'.$st.'/'.$at.'/'.$adpricing.'/'.$premiumStatus."/".$search_by."/".$value.'/'.$pg));



					if($frompg==3 && $status==1)
					$this->flash($this->get_message('successfully activated'), $this->make_url('user/profile/').$uid.'/2/a/'.$duration.'/'.$at.'/'.$st.'/'.$adpricing.'/'.$premiumStatus.'/'.$pg);
					if($frompg==3 && $status==0)
					$this->flash($this->get_message('successfully blocked'), $this->make_url('user/profile/').$uid.'/2/a/'.$duration.'/'.$at.'/'.$st.'/'.$adpricing.'/'.$premiumStatus.'/'.$pg);
				}
			}
		}
		else
		{
			if(!$this->get_check_advertiser_ad($aid))
			{
				$this->flash($this->get_message('invalid'), $this->make_url('ad/list'),0);
			}

			$db= DAL::get_instance();

			$language_enabled=Configuration::get_instance()->read('language_enabled');

			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
			$detail=$res->fetch_assoc();

			$uid=$db->read_single_column("select uid from ".TABLE_PREFIX."ads where id=?",array($aid));
			$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));


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
			$message=str_replace("{AID}",$aid,$message);


			$subject=str_replace("{PRICING}",$this->get_ad_pricing($aid),$subject);
			$message=str_replace("{PRICING}",$this->get_ad_pricing($aid),$message);

			if($status==1)
			{
				$subject=str_replace("{NEWSTATUS}",$this->get_label('activated'),$subject);
				$message=str_replace("{NEWSTATUS}",$this->get_label('activated'),$message);
			}
			if($status==0)
			{
				$subject=str_replace("{NEWSTATUS}",$this->get_label('blocked'),$subject);
				$message=str_replace("{NEWSTATUS}",$this->get_label('blocked'),$message);
			}
		}

		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);


		$this->set_variable('status', $status);
		$this->set_variable("aid",$aid);
		$this->set_variable('frompg', $frompg);

		$this->set_variable('yes_no', $yes_no);

		$this->set_variable('duration', $duration);
		$this->set_variable('at', $at);
		$this->set_variable('st', $st);
		$this->set_variable('pg', $pg);
		$this->set_variable('search_by', $search_by);
		$this->set_variable('adpricing', $adpricing);
		$this->set_variable('premiumStatus', $premiumStatus);
	}

	function preview_action()
	{
		$this->disable_notice_area();
		$aid=$this->read_page_param(1);
		$frompg=$this->read_page_param(2);

		$db= DAL::get_instance();

		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);

		if(!$this->get_ad_validation_user($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
			exit;
		}

		$res1=$db->execute_query("select b.* from ".TABLE_PREFIX."ads a INNER JOIN ".TABLE_PREFIX."banner_dimensions b where a.banner_id=b.id and a.id=?",array($aid));
		$this->set_result("res1",$res1);


		$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=? ORDER BY id desc",array($aid));
		$this->set_result("res",$res,array('banner_list'));


		$usid_row=$res->fetch_array();
		$usid=$usid_row['uid'];
		$adtype=$usid_row['type'];

		if($adtype ==14)
		{
			$json_banners=$usid_row['banner'];

			$json_array=json_decode($json_banners,true);

			$json_array_result[]=$json_array;

			$this->set_array("json_array",$json_array_result);
		}

		$usname=$this->get_user_name($usid);
		$this->set_variable('usname', $usname);

		if($adtype ==7)
		{
			$gres=$db->execute_query("select * from ".TABLE_PREFIX."ads_list where aid=? ORDER BY id ASC",array($aid));
			$this->set_result("gres",$gres);
		}


		$this->set_variable("aid",$aid);
		$this->set_variable("frompg",$frompg);

		$row=$db->execute_query("SELECT total_budget_used,daily_budget_used,daily_budget,total_ad_budget FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
		$rowdata=$row->fetch_assoc();


		$amount_spend=$rowdata['total_budget_used'];
		$amount_spend_today=$rowdata['daily_budget_used'];
		$daily_budget=$rowdata['daily_budget'];
		$total_ad_budget=$rowdata['total_ad_budget'];

		$this->set_variable('amount_spend',$amount_spend);
		$this->set_variable('amount_spend_today',$amount_spend_today);
		$this->set_variable('daily_budget',$daily_budget);
		$this->set_variable('total_budget',$total_ad_budget);

		if($adtype ==12)
		{
			$imagerow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=? AND status=1",array($aid));

			$this->set_result('imagerow',$imagerow);
		}
	}



	function preview_admin_action()
	{
		//$this->disable_notice_area();
		$aid      = intval($this->read_page_param(1));
		$adcodeID = intval($this->read_page_param(2));

		$db= DAL::get_instance();

		if(!$this->get_ad_validation_user($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
			exit;
		}

		$this->set_variable("aid",$aid);
		$this->set_variable("adcodeID",$adcodeID);
	}

	function preview_frame_action()
	{
		//$this->disable_notice_area();
		$aid      = intval($this->read_page_param(1));
		$adcodeID = intval($this->read_page_param(2));

		$db= DAL::get_instance();

		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);

		$res1=$db->execute_query("select b.* from ".TABLE_PREFIX."ads a INNER JOIN ".TABLE_PREFIX."banner_dimensions b where a.banner_id=b.id and a.id=?",array($aid));
		$this->set_result("res1",$res1);


		$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=? ORDER BY id desc",array($aid));
		$this->set_result("res",$res,array('banner_list'));

		$usid_row=$res->fetch_array();
		$adtype=$usid_row['type'];

		if($adtype ==14)
		{
			$json_banners=$usid_row['banner'];

			$json_array=json_decode($json_banners,true);

			$json_array_result[]=$json_array;

			$this->set_array("json_array",$json_array_result);
		}


		if($adtype ==7)
		{
			$gres=$db->execute_query("select * from ".TABLE_PREFIX."ads_list where aid=? ORDER BY id ASC",array($aid));
			$this->set_result("gres",$gres);
		}


		$this->set_variable("aid",$aid);

		$adLayoutID = 0;

		if($adtype == 1 || $adtype == 11)
		{
			$blockID        = $db->read_single_column("SELECT blockid FROM ".TABLE_PREFIX."adunit WHERE id = ?",array($adcodeID));

			if($blockID > 0)
			{
				$layoutList   = $db->read_single_column("SELECT ad_layout_list FROM ".TABLE_PREFIX."adblock WHERE id = ?",array($blockID));

				$adLayoutList = json_decode($layoutList);

				if(count($adLayoutList) > 0)
				{
					$randomKey  = array_rand($adLayoutList);

					$adLayoutID = $adLayoutList[$randomKey];
				}
			}
		}

		$this->set_variable("adLayoutID",$adLayoutID);
		$this->set_variable("adcodeID",$adcodeID);
	}

	function create_default_action()
	{
		$db= DAL::get_instance();


		$this->set_title($this->get_label('create default ad'));

		$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$directlink_enabled 		= $this->get_addon_status('direct-link-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');

		$this->set_variable('textimage_enabled',$textimage_enabled);
		$this->set_variable('pop_enabled',$pop_enabled);
		$this->set_variable('directlink_enabled',$directlink_enabled);
		$this->set_variable('cpv_enabled',$cpv_enabled);
		$this->set_variable('skin_enabled',$skin_enabled);


		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);


		$res1=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND (banner_type=0 OR banner_type=1)");
		$this->set_result("res1",$res1);

		$res4=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =3");
		$this->set_result("res4",$res4);

		if($skin_enabled ==1)
		{
			$res14_banner=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =4");
			$this->set_result("res14_banner",$res14_banner);

			$res14_adblock=$db->execute_query("select * from ".TABLE_PREFIX."adblock where status=1 AND type =2 AND banner_type =4");
			$this->set_result("res14_adblock",$res14_adblock);
		}

		if($_POST)
		{
			$name=$this->read_post_param('name');
			$type=$this->read_post_param('type1');
			$banner_type=$this->read_post_param('banner_type');

			if($banner_type ==2 || $banner_type ==5)
			$type=$banner_type;


			$title=$this->read_post_param('title');
			$desc=$this->read_post_param('desc');


			$displayurl      = $this->read_post_param('displayurl');
			$cta_button_text = $this->read_post_param('cta_button_text');


			$clickurl=$this->read_post_param('clickurl');

			$clickurl=filter_var($clickurl,FILTER_SANITIZE_URL);

			$bannersize=0;

			if($type ==14)
			$bannersize=$this->read_post_param('bannersize_14');
			else
			$bannersize=$this->read_post_param('bannersize');



			if($clickurl !="")
			{
				if(substr($clickurl,0,7) !="http://")
				{
					if(substr($clickurl,0,8) !="https://")
					{
						$clickurl="http://".$clickurl;
					}
				}
			}

			$this->set_variable('name',$name);
			$this->set_variable('type',$type);
			$this->set_variable('title',$title);
			$this->set_variable('desc',$desc);
			$this->set_variable('displayurl',$displayurl);
			$this->set_variable("cta_button_text",$cta_button_text);
			$this->set_variable('clickurl',$clickurl);
			$this->set_variable('bannersize',$bannersize);


			$maxtitle           = Configuration::get_instance()->read('max_ad_title_length');
			$maxdesc            = Configuration::get_instance()->read('max_ad_desc_length');
			$maxdispurl         = Configuration::get_instance()->read('max_display_url_length');
			$ctaButtonLength 	= Configuration::get_instance()->read('cta_button_text_length');
			$adstatus 			= Configuration::get_instance()->read('default_ad_status');


			if($type==1)
			{
				if($name=="" || $title=="" || $desc=="" || $displayurl=="" || $clickurl=="")
				{
					$this->set_notice("mandatory");
				}
				else if(mb_strlen($title,'utf8') > $maxtitle)
				{
					$this->set_notice($this->get_message("check ad length",array('x'=>$maxtitle)));
				}
				else if (mb_strlen($desc,'utf8') > $maxdesc)
				{
					$this->set_notice($this->get_message("check desc length",array('x'=>$maxdesc)));
				}
				else if (mb_strlen($displayurl,'utf8') > $maxdispurl)
				{
					$this->set_notice($this->get_message("check display url length",array('x'=>$maxdispurl)));
				}
				else if($cta_button_text != "" && mb_strlen($cta_button_text,'utf8') > $ctaButtonLength)
				$this->set_notice($this->get_message("check cta button length",array('x'=>$ctaButtonLength)));
				else
				{
					$sql1="INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,title,display_url,click_url,status,description,user_status,cta_button_text) values
					(?,?,?,?,?,?,?,?,?,?)";
					$res=$db->execute_query($sql1,array(0,$name,$type,$title,$displayurl,$clickurl,$adstatus,$desc,1,$cta_button_text));

					if($res->error =="")
					$this->flash($this->get_message('def text ad created'), $this->make_url('ad/list_default'));
					else
					{
						$this->set_notice("error occurred");
					}
				}
			}
			else if($type==2 || $type ==11)
			{
				$res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bannersize));
				$result11=$res11->fetch_assoc();

				$height1=$result11['height'];
				$width1=$result11['width'];
				$filesize=$result11['filesize'];


				if($name=="" || $clickurl=="" || $_FILES["banner"]["name"]=="")
				{
					$this->set_notice("mandatory");
				}
				else if($type == 11 && ($title=="" || $desc=="" || $displayurl==""))
				{
					$this->set_notice("mandatory");
				}
				else if($type == 11 && mb_strlen($title,'utf8') > $maxtitle)
				{
					$this->set_notice($this->get_message("check ad length",array('x'=>$maxtitle)));
				}
				else if ($type == 11 && mb_strlen($desc,'utf8') > $maxdesc)
				{
					$this->set_notice($this->get_message("check desc length",array('x'=>$maxdesc)));
				}
				else if ($type == 11 && mb_strlen($displayurl,'utf8') > $maxdispurl)
				{
					$this->set_notice($this->get_message("check display url length",array('x'=>$maxdispurl)));
				}
				else if($type == 11 && $cta_button_text != "" && mb_strlen($cta_button_text,'utf8') > $ctaButtonLength)
				$this->set_notice($this->get_message("check cta button length",array('x'=>$ctaButtonLength)));
				else
				{

					$bannerclass		= new getID3;
					$bannerUploadedData	= $bannerclass->analyze($_FILES["banner"]["tmp_name"]);
					$mimetype			= $bannerUploadedData['mime_type'];


					$extension=explode(".",$_FILES["banner"]['name']);
					$extensionname=strtolower($extension[count($extension)-1]);


					$filename=time().'.'.$extensionname;

					if(($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg") && ($_FILES["banner"]["size"]/1024 <= $filesize))
					{
						if($_FILES["banner"]["error"] > 0)
						{
							$this->set_notice("error occurred");
						}
						else
						{
							if(!is_dir("../".DATA_DIR))
							mkdir("../".DATA_DIR,0777);


							$sql1="INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,banner,status,banner_id,click_url,title,description,display_url,user_status,mime_type,cta_button_text) values
							(?,?,?,?,?,?,?,?,?,?,?,?,?)";
							$res=$db->execute_query($sql1,array(0,$name,$type,$filename,$adstatus,$bannersize,$clickurl,$title,$desc,$displayurl,1,$mimetype,$cta_button_text));

							if($res->error=="")
							{
								$id=$res->get_last_id();

								if(move_uploaded_file($_FILES["banner"]["tmp_name"],"../".DATA_DIR."/".$id."_".$filename))
								{
									$image=new ImageHelper("../".DATA_DIR."/".$id."_".$filename);
									$image->resize($width1,$height1,"../".DATA_DIR."/".$id."_".$filename);

									$this->flash($this->get_message('def banner ad created'), $this->make_url('ad/list_default'));
									exit;
								}
								else
								{
									$this->flash($this->get_message('image upload failed'), $this->make_url('ad/edit_default/').$id,0);
									exit;
								}
							}
							else
							{
								$this->set_notice("error occurred");
							}
						}
					}
					else
					{
						$this->set_notice("image not supported");
					}
				}
			}
			else if($type==9)
			{
				if($name=="" || $clickurl=="")
				$this->set_notice("mandatory");
				else
				{
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,click_url,status,display_type,user_status) values (?,?,?,?,?,?,?)",array(0,$name,9,$clickurl,$adstatus,1,1));

					if($res->error =="")
					$this->flash($this->get_message('pop ad create success'), $this->make_url('ad/list_default'));
					else
					{
						$this->set_notice("error occurred");
					}
				}
			}
			else if($type==21)
			{
				if($name=="" || $clickurl=="")
				$this->set_notice("mandatory");
				else
				{
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,click_url,status,display_type,user_status) values (?,?,?,?,?,?,?)",array(0,$name,21,$clickurl,$adstatus,0,1));

					if($res->error =="")
					$this->flash($this->get_message('directlink ad create success'), $this->make_url('ad/list_default'));
					else
					{
						$this->set_notice("error occurred");
					}
				}
			}
			else if($type ==13)
			{
				if($name=="" || $displayurl=="" || $clickurl=="" || $_FILES["videofile"]["name"] =="")
				{
					$this->set_notice("mandatory");
				}
				else
				{

					$bannersize=0;
					$videoextensionname="";
					$videomaxsize=0;
					$videomaxduration=0;

					$duration=0;
					$duration_string="";
					$bitrate=0;
					$videowidth=0;
					$videoheight=0;
					$videosize=0;

					$aspect_ratio_value=0;
					$aspect_ratio_array=array();

					$videofile=$_FILES['videofile']['name'];
					$videofile_temp_name=$_FILES['videofile']["tmp_name"];

					if($videofile !="" && $videofile_temp_name !="")
					{
						$videoextension=explode(".",$videofile);
						$videoextensionname=strtolower($videoextension[count($videoextension)-1]);

						$videofile=time().'.'.$videoextensionname;

						$videomaxsize=Configuration::get_instance()->read('video_file_max_size');
						$videomaxduration=Configuration::get_instance()->read('video_file_max_duration');

						$videoclass= new getID3;
						$videodata=$videoclass->analyze($videofile_temp_name);

						$duration=ceil($videodata['playtime_seconds']);
						$duration_string=gmdate("H:i:s",$duration);
						$bitrate=$videodata['bitrate'];

						//$mimetype=$videodata['mime_type'];


						if($videoextensionname =='mp4')
						$mimetype='video/mp4';
						else if($videoextensionname =='flv')
						$mimetype='video/x-flv';
						else if($videoextensionname =='wmv')
						$mimetype='video/x-ms-wmv';
						else if($videoextensionname =='mpg')
						$mimetype='video/mpeg';
						else if($videoextensionname =='avi')
						$mimetype='video/x-msvideo';
						else if($videoextensionname =='webm')
						$mimetype='video/webm';
						else if($videoextensionname =='ogv' || $videoextensionname =='ogg')
						$mimetype='video/ogg';


						$videowidth=$videodata['video']['resolution_x'];
						$videoheight=$videodata['video']['resolution_y'];
						$videosize=(($videodata['filesize']/1024)/1024);

						$aspect_ratio_array=$this->get_aspect_ratio_list(1);
						$aspect_ratio_value=round($videowidth/$videoheight,3);
					}


					if($videoextensionname != "mp4" && $videoextensionname != "webm" && $videoextensionname != "ogg")
					$this->set_notice("video file not supported");
					else if($videofile_temp_name =="" || $videosize > $videomaxsize)
					$this->set_notice("video size not supported");
					else if($duration > $videomaxduration)
					$this->set_notice("video duration high");

					else if(!in_array($aspect_ratio_value,$aspect_ratio_array))
					$this->set_notice("video aspect ratio not supported");

					else
					{
						$aspect_ratio_id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."aspect_ratio WHERE aspect_float_value=?",array($aspect_ratio_value));

						$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,banner,status,click_url,display_url,video_width,video_height,mime_type,bitrate,duration,duration_seconds,display_type,aspect_ratio,user_status) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array(0,$name,$type,$videofile,$adstatus,$clickurl,$displayurl,$videowidth,$videoheight,$mimetype,$bitrate,$duration_string,$duration,1,$aspect_ratio_id,1));

						if($res->error=="")
						{
							$id=$res->get_last_id();

							if(!is_dir('../'.DATA_DIR))
							mkdir('../'.DATA_DIR,0777);

							if(!is_dir('../'.DATA_DIR.'/video/'))
							mkdir('../'.DATA_DIR.'/video/',0777);

							if(!is_dir('../'.DATA_DIR.'/video/'.$id.'/'))
							mkdir('../'.DATA_DIR.'/video/'.$id.'/',0777);


							if(move_uploaded_file($videofile_temp_name,'../'.DATA_DIR.'/video/'.$id.'/'.$videofile))
							{
								$this->flash($this->get_message('video ad create success'), $this->make_url('ad/list_default'));
								exit;
							}
							else
							{
								$this->flash($this->get_message('video upload failed'), $this->make_url('ad/edit_default/'.$id),0);
								exit;
							}
						}
						else
						{
							$this->set_notice("error occurred");
						}
					}
				}
			}
			else if($type == 18)
			{

				if($name=="" || $title=="" || $desc==""  || $clickurl=="")
				{
					$this->set_notice("mandatory");
				}
				else if(mb_strlen($title,'utf8')>Configuration::get_instance()->read('max_ad_title_length'))
				{
					$this->set_notice($this->get_message("check ad length",array('x'=>Configuration::get_instance()->read('max_ad_title_length'))));
				}
				else if (mb_strlen($desc,'utf8')>Configuration::get_instance()->read('max_ad_desc_length'))
				{
					$this->set_notice($this->get_message("check desc length",array('x'=>Configuration::get_instance()->read('max_ad_desc_length'))));
				}
				else
				{
					if($_FILES['notify_icon_image']['name'] != "")
					{
						$bannerclass		= new getID3;
						$bannerUploadedData	= $bannerclass->analyze($_FILES['notify_icon_image']["tmp_name"]);
						$mimetype			= $bannerUploadedData['mime_type'];
						$filename=$_FILES['notify_icon_image']['name'];
						$extension=explode(".",$filename);
						$extensionname=$extension[1];
						if($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg"){
							if($_FILES["notify_icon_image"]["error"] > 0)
							{
								$this->set_notice("error occurred");
							}
							else
							{
								if(!is_dir("../".DATA_DIR."/notification_icons/"))
									mkdir("../".DATA_DIR."/notification_icons/",0777);


									$sql1="INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,banner,status,click_url,title,description,display_url,user_status,mime_type) values
							(?,?,?,?,?,?,?,?,?,?,?)";
									$res=$db->execute_query($sql1,array(0,$name,$type,$filename,$adstatus,$clickurl,$title,$desc,$displayurl,1,$mimetype));

									if($res->error=="")
									{
										$id=$res->get_last_id();
										$notification_icon_width = Configuration::get_instance()->read('push_notification_icon_width');
										$notification_icon_height = Configuration::get_instance()->read('push_notification_icon_height');
										if(move_uploaded_file($_FILES["notify_icon_image"]["tmp_name"],"../".DATA_DIR."/notification_icons/".$id."_".$filename))
										{
											$image=new ImageHelper("../".DATA_DIR."/notification_icons/".$id."_".$filename);
											$image->resize($notification_icon_width,$notification_icon_height,"../".DATA_DIR."/notification_icons/".$id."_".$filename);

											$this->flash($this->get_message('def push notification ad created'), $this->make_url('ad/list_default'));
											exit;
										}
										else
										{
											$this->flash($this->get_message('icon image upload failed'), $this->make_url('ad/edit_default/').$id,0);
											exit;
										}
									}
									else
									{
										$this->set_notice("error occurred");
									}
							}



						}
						else
							$this->set_notice("icon image format not supported");

					}
					else {
						$sql1="INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,status,click_url,title,description,display_url,user_status,mime_type) values
							(?,?,?,?,?,?,?,?,?,?)";
						$res=$db->execute_query($sql1,array(0,$name,$type,$adstatus,$clickurl,$title,$desc,$displayurl,1,$mimetype));
						if($res->error == ''){
							$this->flash($this->get_message('def push notification ad created'), $this->make_url('ad/list_default'));
							exit;
						}
						else
						{
							$this->set_notice("error occurred");
						}

					}
				}
			}
			else if($type ==14)
			{
				if($name=="" || $clickurl=="")
				$this->set_notice("mandatory");
				else
				{
					$image_upload_flag=0;
					$noimage_flag=0;
					$existing_dimensions=explode(',',$this->read_post_param('existing_dimensions'));

					foreach($existing_dimensions as $key => $value)
					{
						if($_FILES["skin_banner_".$value]["name"] == "")
						{
							$noimage_flag=1;
							break;
						}
					}


					if($noimage_flag ==1)
					$this->set_notice("please upload skin banners for all dimensions");
					else
					{
	                    $res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 And banner_type =4 order by width ASC");

	                    $time =time();

	                    $filename ='';
                        $filenames=array();
                        $skin_erro_flag=0;

                        while($result11=$res11->fetch_assoc())
                        {
                            $height1=$result11['height'];
                            $width1=$result11['width'];
                            $id_banner=$result11['id'];
                            $filesize=$result11['filesize'];


                            $extension=explode(".",$_FILES["skin_banner_".$id_banner]["name"]);
                            $extensionname=strtolower($extension[count($extension)-1]);

                            $filenames[$id_banner]=$width1.'_'.$height1.'_skin_'.$time.'.'.$extensionname;

                            $bannerinfo = getimagesize($_FILES["skin_banner_".$id_banner]["tmp_name"]);
                            $bannerwidth = $bannerinfo[0];
                            $bannerheight = $bannerinfo[1];

                            if($extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg")
                            {
                            	$skin_erro_flag=1;
                            	break;
                            }
                            else if($_FILES["skin_banner_".$id_banner]["size"]/1024 > $filesize)
                            {
                                $skin_erro_flag=2;
                                break;
                            }
                            else if($_FILES["skin_banner_".$id_banner]["error"] > 0)
                            {
                                $skin_erro_flag=3;
								break;
                            }
                        }

                        $filename= json_encode($filenames);


					    if($skin_erro_flag ==1)
						$this->set_notice("image not supported");
                        else if($skin_erro_flag ==2)
                        $this->set_notice("image size not supported");
					    else if($skin_erro_flag==3)
                        $this->set_notice("image file corrupted");
			            else
			            {

							$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,banner,status,banner_id,click_url,user_status) values (?,?,?,?,?,?,?,?)",array(0,$name,$type,$filename,$adstatus,$bannersize,$clickurl,1));

							if($res->error=="")
							{
								$insert_id=$res->get_last_id();

								if(!is_dir("../".DATA_DIR))
								mkdir("../".DATA_DIR,0777);

								if(!is_dir("../".DATA_DIR."/".$insert_id))
								mkdir("../".DATA_DIR."/".$insert_id,0777);


								foreach($filenames as $key => $filename)
								{
									if(move_uploaded_file($_FILES["skin_banner_".$key]["tmp_name"],"../".DATA_DIR."/".$insert_id."/".$filename))
									{
										$image=new ImageHelper("../".DATA_DIR."/".$insert_id."/".$filename);
										$image->resize($width1,$height1,"../".DATA_DIR."/".$insert_id."/".$filename);
									}
									else
									{
										$image_upload_flag=1;
										break;
									}
								}

								if($image_upload_flag ==0)
								$this->flash($this->get_message('default skin ad created'), $this->make_url('ad/list_default'));
								else
								$this->flash($this->get_message('image upload failed'), $this->make_url('ad/edit_default/'.$insert_id),0);
							}
							else
							{
								$this->set_notice("error occurred");
							}
						}
					}
				}
			}
		}
	}

	function list_default_action()
	{
		  	$db= DAL::get_instance();
			$this->set_title($this->get_label('manage default ads'));
			$adstatus='';

			if($_POST)
			{
				$adtype=intval($this->read_post_param('type'));
				$status=intval($this->read_post_param('status'));
			}
			else
			{
				$adtype=$this->read_page_param(1);
				$status=$this->read_page_param(2);

				$exp=explode("-",$adtype);
				if($exp[0]=="page")
				$adtype=0;

				$exp=explode("-",$status);
				if($exp[0]=="page")
				$status=2;


			}

			if($adtype === "")
			$adtype=0;

			if($status === "")
			$status=2;


			$pop_enabled=$this->get_addon_status('pop-ads_enabled');
			$directlink_enabled = $this->get_addon_status('direct-link-ads_enabled');
			$cpv_enabled=$this->get_addon_status('video-ads_enabled');

			$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');

			$this->set_variable('text_ads_enabled',$text_ads_enabled);



			$this->set_variable('pop_enabled',$pop_enabled);
			$this->set_variable('cpv_enabled',$cpv_enabled);
			$this->set_variable('directlink_enabled',$directlink_enabled);



			
			$adtype_str="";
			
			if($adtype > 0)
			$adtype_str=" AND a.type=".$adtype." ";


			
			if($this->get_addon_status('text-image-ads_enabled') !=1)
			$adtype_str.=' AND a.type <>11 ';

			if($text_ads_enabled !=1)
			$adtype_str.=' AND a.type <>1 ';

			if($pop_enabled !=1)
			$adtype_str.=' AND a.type <>9 ';
			
			if($directlink_enabled !=1)
			$adtype_str.=' AND a.type <>21 ';

			if($cpv_enabled !=1)
			$adtype_str.=' AND a.type <>13 ';

			if($this->get_addon_status('skin-ads_enabled') !=1)
			$adtype_str.=' AND a.type <>14 ';

			$adtype_str.=' AND a.type <>17 ';	//For exclude feed ads
			$adtype_str.=' AND a.type <>20 ';	//For exclude DSP ads

			if($this->get_addon_status('cpp_enabled') !=1)
			$adtype_str.=' AND a.type <>18 ';


			if($status ==2)
			$status_str="";
			else
			{
				$adstatus=intval($status);
				$status_str=" and a.status='".$adstatus."' ";
			}

			$this->set_variable("type",$adtype);
			$this->set_variable("status",$status);


			$query = "SELECT * FROM ".TABLE_PREFIX."ads a where uid=0 ".$adtype_str.$status_str." ORDER BY id desc";
			$pagination = new Pagination($query);
			$res=$pagination->get_result();
			$this->set_result("res",$res);
			$this->set_variable("pagination",$pagination->links(),0);

			$pg=$pagination->get_page_number();
			$this->set_variable("pg","page-".$pg);
	}


	function edit_default_action()
	{
		$this->set_title($this->get_label('edit default ad'));
		$aid=$this->read_page_param(1);
		$db= DAL::get_instance();



		$frompg=$this->read_page_param(2);
		$tp=$this->read_page_param(3);
		$st=$this->read_page_param(4);
		$pg=$this->read_page_param(5);


		if(!$this->get_ad_validation_admin($aid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('ad/list_default'),0);
		}


		$adstatus=Configuration::get_instance()->read('default_ad_status');

		$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$directlink_enabled= $this->get_addon_status('direct-link-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');

		$this->set_variable('cpv_enabled',$cpv_enabled);
		$this->set_variable('pop_enabled',$pop_enabled);
		$this->set_variable('directlink_enabled',$directlink_enabled);
		$this->set_variable('skin_enabled',$skin_enabled);
		$this->set_variable('textimage_enabled',$textimage_enabled);


		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);


		$adtype=$this->get_adtype_from_id($aid);

		if($adtype ==2)
		{
			$res1=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND (banner_type =0 OR banner_type=1)");
			$this->set_result("res1",$res1);
		}
		else if($adtype ==11)
		{
			$res1=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =3");
			$this->set_result("res1",$res1);
		}
		else if($adtype ==14)
		{
			$res14_banner=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =4");
			$this->set_result("res14_banner",$res14_banner);

			$res14_adblock=$db->execute_query("select * from ".TABLE_PREFIX."adblock where status=1 AND type =2 AND banner_type =4");
			$this->set_result("res14_adblock",$res14_adblock);
		}


		if($_POST)
		{
			if(DEMO_MODE && $aid <= 100)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('ad/list_default'),0);
				exit;
			}

			$name=$this->read_post_param('name');
			$type=$this->read_post_param('type');
			$title=$this->read_post_param('title');
			$desc=$this->read_post_param('desc');
			$displayurl=$this->read_post_param('displayurl');
			$cta_button_text=$this->read_post_param('cta_button_text');

			$clickurl=$this->read_post_param('clickurl');

			$clickurl=filter_var($clickurl,FILTER_SANITIZE_URL);

			$bannerid=intval($this->read_post_param('bannersize'));


			$frompg=$this->read_post_param("frompg");
			$tp=$this->read_post_param("tp");
			$st=$this->read_post_param("st");
			$pg=$this->read_post_param("pg");

			$banner="";

			if($type==2 || $type==11)
			$banner=$_FILES["banner"]["name"];

			$bannerid=$this->read_post_param('bannersize');

			if($clickurl !="")
			{
				if(substr($clickurl,0,7) !="http://")
				{
					if(substr($clickurl,0,8) !="https://")
					{
						$clickurl="http://".$clickurl;
					}
				}
			}



			$this->set_variable("name",$name);
			$this->set_variable("type",$type);
			$this->set_variable("title",$title);
			$this->set_variable("desc",$desc);
			$this->set_variable("displayurl",$displayurl);
			$this->set_variable("cta_button_text",$cta_button_text);
			$this->set_variable("clickurl",$clickurl);
			$this->set_variable("bannersize",$bannerid);


			$maxtitle           = Configuration::get_instance()->read('max_ad_title_length');
			$maxdesc            = Configuration::get_instance()->read('max_ad_desc_length');
			$maxdispurl         = Configuration::get_instance()->read('max_display_url_length');
			$ctaButtonLength 	= Configuration::get_instance()->read('cta_button_text_length');

			if($type==9)
			{
				if($name=="" || $clickurl=="")
				{
					$this->set_notice("mandatory");
				}
				else
				{
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,click_url=?,status=? where id=?",array($name,$clickurl,$adstatus,$aid));

					if($res->error=="")
					{
						if($frompg ==1)
						$this->flash($this->get_message('pop ad edit success'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
						else
						$this->flash($this->get_message('pop ad edit success'), $this->make_url('ad/view_default/'.$aid));
					}
					else
					$this->set_notice("error occurred");
				}
			}
			else if($type==21)
			{
				if($name=="" || $clickurl=="")
				{
					$this->set_notice("mandatory");
				}
				else
				{
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,click_url=?,status=? where id=?",array($name,$clickurl,$adstatus,$aid));

					if($res->error=="")
					{
						if($frompg ==1)
						$this->flash($this->get_message('directlink ad edit success'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
						else
						$this->flash($this->get_message('directlink ad edit success'), $this->make_url('ad/view_default/'.$aid));
					}
					else
					$this->set_notice("error occurred");
				}
			}
			else if($type==1)
			{
				if($name=="" || $title=="" || $desc=="" ||$displayurl=="" || $clickurl=="")
				{
					$this->set_notice("mandatory");
				}
				else if(mb_strlen($title,'utf8') > $maxtitle)
				{
					$this->set_notice($this->get_message("check ad length",array('x'=>$maxtitle)));
				}
				else if (mb_strlen($desc,'utf8') > $maxdesc)
				{
					$this->set_notice($this->get_message("check desc length",array('x'=>$maxdesc)));
				}
				else if (mb_strlen($displayurl,'utf8') > $maxdispurl)
				{
					$this->set_notice($this->get_message("check display url length",array('x'=>$maxdispurl)));
				}
				else if($cta_button_text != "" && mb_strlen($cta_button_text,'utf8') > $ctaButtonLength)
				$this->set_notice($this->get_message("check cta button length",array('x'=>$ctaButtonLength)));
				else
				{
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,title=?,description=?,display_url=?,click_url=?,status=?,cta_button_text = ? where id=?",array($name,$title,$desc,$displayurl,$clickurl,$adstatus,$cta_button_text,$aid));

					if($res->error=="")
					{
						if($frompg ==1)
						$this->flash($this->get_message('def text ad edited'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
						else
						$this->flash($this->get_message('def text ad edited'), $this->make_url('ad/view_default/'.$aid));
					}
					else
					$this->set_notice("error occurred");
				}
			}
			else if($type==2 || $type==11)
			{
				$res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bannerid));
				$result11=$res11->fetch_assoc();

				$height1=$result11['height'];
				$width1=$result11['width'];
				$filesize=$result11['filesize'];

				$oldbaid=$db->read_single_column("select banner_id from ".TABLE_PREFIX."ads where id=?",array($aid));

				if($name=="" || $clickurl=="" || (($oldbaid !=$bannerid) && $banner==""))
				{
					$this->set_notice("mandatory");
				}
				else if($type == 11 && ($title=="" || $desc=="" || $displayurl==""))
				{
					$this->set_notice("mandatory");
				}
				else if($type == 11 && mb_strlen($title,'utf8') > $maxtitle)
				{
					$this->set_notice($this->get_message("check ad length",array('x'=>$maxtitle)));
				}
				else if ($type == 11 && mb_strlen($desc,'utf8') > $maxdesc)
				{
					$this->set_notice($this->get_message("check desc length",array('x'=>$maxdesc)));
				}
				else if ($type == 11 && mb_strlen($displayurl,'utf8') > $maxdispurl)
				{
					$this->set_notice($this->get_message("check display url length",array('x'=>$maxdispurl)));
				}
				else if($type == 11 && $cta_button_text != "" && mb_strlen($cta_button_text,'utf8') > $ctaButtonLength)
				$this->set_notice($this->get_message("check cta button length",array('x'=>$ctaButtonLength)));
				else
				{
					if($banner !="")
					{
						$bannerclass		= new getID3;
						$bannerUploadedData	= $bannerclass->analyze($_FILES["banner"]["tmp_name"]);
						$mimetype			= $bannerUploadedData['mime_type'];


						$extension=explode(".",$_FILES["banner"]['name']);
						$extensionname=strtolower($extension[count($extension)-1]);

						$fname=time().'.'.$extensionname;


						if(($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg") && ($_FILES["banner"]["size"]/1024 <= $filesize))
						{
							if($_FILES["banner"]["error"] > 0)
							{
								$this->set_notice("error occurred");
							}
							else
							{
								if(!is_dir("../".DATA_DIR))
								mkdir("../".DATA_DIR,0777);


								$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."ads where id=?",array($aid));


								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,banner_id=?,banner=?,status=?,click_url=?,title=?,description=?,display_url=?,mime_type=?,cta_button_text=? where id=?",array($name,$bannerid,$fname,$adstatus,$clickurl,$title,$desc,$displayurl,$mimetype,$cta_button_text,$aid));


								if($res->error=="")
								{
									if(move_uploaded_file($_FILES["banner"]["tmp_name"],"../".DATA_DIR."/".$aid."_".$fname))
									{
										if($old_name != $fname)
										unlink("../".DATA_DIR."/".$aid."_".$old_name);

										$image=new ImageHelper("../".DATA_DIR."/".$aid."_".$fname);
										$image->resize($width1,$height1,"../".DATA_DIR."/".$aid."_".$fname);

										if($frompg ==1)
										$this->flash($this->get_message('def banner ad edited'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
										else
										$this->flash($this->get_message('def banner ad edited'), $this->make_url('ad/view_default/'.$aid));
									}
									else
									{
										$this->flash($this->get_message('image upload failed'), $this->make_url('ad/edit_default/').$aid,0);
										exit;
									}
								}
								else
								{
									$this->set_notice("error occurred");
								}
							}
						}
						else
						{
							$this->set_notice("image not supported");
						}
					}
					else
					{
						$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,status=?,click_url=?,title=?,description=?,display_url=?,cta_button_text=? where id=?",array($name,$adstatus,$clickurl,$title,$desc,$displayurl,$cta_button_text,$aid));

						if($res->error=="")
						{
							if($frompg ==1)
							$this->flash($this->get_message('def banner ad edited'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
							else
							$this->flash($this->get_message('def banner ad edited'), $this->make_url('ad/view_default/'.$aid));
						}
						else
						{
							$this->set_notice("error occurred");
						}
					}
				}
			}
			else if($type ==13)
			{
				$videofile=$_FILES['videofile']['name'];
				$videofile_temp_name=$_FILES['videofile']["tmp_name"];

				$videoextensionname="";
				$videomaxsize=0;
				$videomaxduration=0;

				$duration=0;
				$duration_string="";
				$bitrate=0;
				$videowidth=0;
				$videoheight=0;
				$videosize=0;

				$aspect_ratio_value=0;
				$aspect_ratio_array=array();

				if($videofile !="" && $videofile_temp_name !="")
				{
					$videoextension=explode(".",$videofile);
					$videoextensionname=strtolower($videoextension[count($videoextension)-1]);

					$videofile=time().'.'.$videoextensionname;

					$videomaxsize=Configuration::get_instance()->read('video_file_max_size');
					$videomaxduration=Configuration::get_instance()->read('video_file_max_duration');

					$videoclass= new getID3;
					$videodata=$videoclass->analyze($videofile_temp_name);

					$duration=ceil($videodata['playtime_seconds']);
					$duration_string=gmdate("H:i:s",$duration);
					$bitrate=$videodata['bitrate'];

					//$mimetype=$videodata['mime_type'];


					if($videoextensionname =='mp4')
					$mimetype='video/mp4';
					else if($videoextensionname =='flv')
					$mimetype='video/x-flv';
					else if($videoextensionname =='wmv')
					$mimetype='video/x-ms-wmv';
					else if($videoextensionname =='mpg')
					$mimetype='video/mpeg';
					else if($videoextensionname =='avi')
					$mimetype='video/x-msvideo';
					else if($videoextensionname =='webm')
					$mimetype='video/webm';
					else if($videoextensionname =='ogv' || $videoextensionname =='ogg')
					$mimetype='video/ogg';


					$videowidth=$videodata['video']['resolution_x'];
					$videoheight=$videodata['video']['resolution_y'];
					$videosize=(($videodata['filesize']/1024)/1024);

					$aspect_ratio_array=$this->get_aspect_ratio_list(1);
					$aspect_ratio_value=round($videowidth/$videoheight,3);
				}



				if($name =="" || $displayurl =="" || $clickurl =="")
				$this->set_notice("mandatory");
				else if($videofile !="" && $videoextensionname != "mp4" && $videoextensionname != "webm" && $videoextensionname != "ogg")
				$this->set_notice("video file not supported");
				else if($videofile !="" && ($videofile_temp_name =="" || $videosize > $videomaxsize))
				$this->set_notice("video size not supported");
				else if($videofile !="" && $duration > $videomaxduration)
				$this->set_notice("video duration high");

				else if($videofile !="" && !in_array($aspect_ratio_value,$aspect_ratio_array))
				$this->set_notice("video aspect ratio not supported");

				else
				{

					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,status=?,click_url=?,display_url=? where id=?",array($name,$adstatus,$clickurl,$displayurl,$aid));

					if($res->error =="")
					{
						if($videofile !="")
						{
							if(!is_dir('../'.DATA_DIR))
							mkdir('../'.DATA_DIR,0777);

							if(!is_dir('../'.DATA_DIR.'/video/'))
							mkdir('../'.DATA_DIR.'/video/',0777);

							if(!is_dir('../'.DATA_DIR.'/video/'.$aid.'/'))
							mkdir('../'.DATA_DIR.'/video/'.$aid.'/',0777);


							$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."ads where id=?",array($aid));


							$aspect_ratio_id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."aspect_ratio WHERE aspect_float_value=?",array($aspect_ratio_value));


							$res1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set banner=?,video_width=?,video_height=?,mime_type=?,bitrate=?,duration=?,duration_seconds=?,aspect_ratio=? where id=?",array($videofile,$videowidth,$videoheight,$mimetype,$bitrate,$duration_string,$duration,$aspect_ratio_id,$aid));

							if($res1->error =="")
							{
								if(move_uploaded_file($videofile_temp_name,'../'.DATA_DIR.'/video/'.$aid.'/'.$videofile))
								{
									if($old_name != $videofile)
									unlink("../".DATA_DIR."/video/".$aid."/".$old_name);

									if($frompg ==1)
									$this->flash($this->get_message('video ad edit success'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
									else
									$this->flash($this->get_message('video ad edit success'), $this->make_url('ad/view_default/'.$aid));

									exit;
								}
								else
								{
									$this->flash($this->get_message('video upload failed'), $this->make_url('ad/edit_default/'.$aid),0);
									exit;
								}
							}
							else
							{
								$this->set_notice("error occurred");
							}
						}
						else
						{
							if($frompg ==1)
							$this->flash($this->get_message('video ad edit success'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
							else
							$this->flash($this->get_message('video ad edit success'), $this->make_url('ad/view_default/'.$aid));
						}
					}
					else
					{
						$this->set_notice("error occurred");
					}
				}
			}
			else if($type == 18)
			{
				if($name=="" || $title=="" || $desc=="" || $clickurl=="")
				{
					$this->set_notice("mandatory");
				}
				else if(mb_strlen($title,'utf8')>Configuration::get_instance()->read('max_ad_title_length'))
				{
					$this->set_notice($this->get_message("check ad length",array('x'=>Configuration::get_instance()->read('max_ad_title_length'))));
				}
				else if (mb_strlen($desc,'utf8')>Configuration::get_instance()->read('max_ad_desc_length'))
				{
					$this->set_notice($this->get_message("check desc length",array('x'=>Configuration::get_instance()->read('max_ad_desc_length'))));
				}
				
				else
				{
					if($_FILES['notify_icon_image']['name'] != "")
					{
						$bannerclass		= new getID3;
						$bannerUploadedData	= $bannerclass->analyze($_FILES['notify_icon_image']["tmp_name"]);
						$mimetype			= $bannerUploadedData['mime_type'];
						$filename=$_FILES['notify_icon_image']['name'];
						$extension=explode(".",$filename);
						$extensionname=$extension[1];
						if($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg"){
							if($_FILES["notify_icon_image"]["error"] > 0)
							{
								$this->set_notice("error occurred");
							}
							else
							{
								if(!is_dir("../".DATA_DIR."/notification_icons/"))
									mkdir("../".DATA_DIR."/notification_icons/",0777);


									$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."ads where id=?",array($aid));


									$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,banner_id=?,banner=?,status=?,click_url=?,title=?,description=?,display_url=?,mime_type=? where id=?",array($name,$bannerid,$filename,$adstatus,$clickurl,$title,$desc,$displayurl,$mimetype,$aid));


									if($res->error=="")
									{
										$id=$res->get_last_id();
										$notification_icon_width = Configuration::get_instance()->read('push_notification_icon_width');
										$notification_icon_height = Configuration::get_instance()->read('push_notification_icon_height');
										if(move_uploaded_file($_FILES["notify_icon_image"]["tmp_name"],"../".DATA_DIR."/notification_icons/".$aid."_".$filename))
										{

											if($old_name != $filename)
												unlink("../".DATA_DIR."/notification_icons/".$aid."_".$old_name);
												$image=new ImageHelper("../".DATA_DIR."/notification_icons/".$aid."_".$filename);
												$image->resize($notification_icon_width,$notification_icon_height,"../".DATA_DIR."/notification_icons/".$aid."_".$filename);

												if($frompg ==1)
													$this->flash($this->get_message('def banner ad edited'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
													else
														$this->flash($this->get_message('def banner ad edited'), $this->make_url('ad/view_default/'.$aid));
														exit;
										}
										else
										{
											$this->flash($this->get_message('icon image upload failed'), $this->make_url('ad/edit_default/').$aid,0);
											exit;
										}
									}
									else
									{
										$this->set_notice("error occurred");
									}
							}



						}
						else
							$this->set_notice("icon image format not supported");

					}
					else {
						$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,status=?,click_url=?,title=?,description=?,display_url=? where id=?",array($name,$adstatus,$clickurl,$title,$desc,$displayurl,$aid));

						if($res->error=="")
						{
							if($frompg ==1)
								$this->flash($this->get_message('default push notification ad edited'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
								else
									$this->flash($this->get_message('default push notification ad edited'), $this->make_url('ad/view_default/'.$aid));
						}
						else
						{
							$this->set_notice("error occurred");
						}
					}
				}
			}
			else if($type ==14)
			{
				if($name =="" || $clickurl =="")
				{
					$this->set_notice("mandatory");
				}
				else
				{
					$image_upload_flag=0;
					$noimage_flag=0;
					$existing_dimensions=explode(',',$this->read_post_param('existing_dimensions'));

					$result=$db->execute_query("select banner_id,banner from ".TABLE_PREFIX."ads where id=?",array($aid));
					$result_data=$result->fetch_assoc();

					$oldbaid=$result_data['banner_id'];
					$old_name=$result_data['banner'];


					if($oldbaid != $bannerid)
					{
						foreach($existing_dimensions as $key => $value)
						{
							if($_FILES["skin_banner_".$value]["name"] == "")
							{
								$noimage_flag=1;
								break;
							}
						}
					}

					if($noimage_flag ==1)
					$this->set_notice("please upload skin banners for all dimensions");
					else
					{
	                    $res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 And banner_type =4 order by width ASC");

	                    $time =time();

	                    $filename ='';
                        $filenames=array();
                        $skin_erro_flag=0;
                        $success_flag=0;
                        $image_upload_flag=0;
						$updation_string="";

                        while($result11=$res11->fetch_assoc())
                        {
                            $height1=$result11['height'];
                            $width1=$result11['width'];
                            $id_banner=$result11['id'];
                            $filesize=$result11['filesize'];

                            if($_FILES["skin_banner_".$id_banner]["name"] !="")
                            {
	                            $extension=explode(".",$_FILES["skin_banner_".$id_banner]["name"]);
	                            $extensionname=strtolower($extension[count($extension)-1]);

	                            $filenames[$id_banner]=$width1.'_'.$height1.'_skin_'.$time.'.'.$extensionname;

	                            $bannerinfo = getimagesize($_FILES["skin_banner_".$id_banner]["tmp_name"]);
	                            $bannerwidth = $bannerinfo[0];
	                            $bannerheight = $bannerinfo[1];

	                            if($extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg")
	                            {
	                            	$skin_erro_flag=1;
	                            	break;
	                            }
	                            else if($_FILES["skin_banner_".$id_banner]["size"]/1024 > $filesize)
	                            {
	                                $skin_erro_flag=2;
	                                break;
	                            }
	                            else if($_FILES["skin_banner_".$id_banner]["error"] > 0)
	                            {
	                                $skin_erro_flag=3;
									break;
	                            }
                            }
                        }


					    if($skin_erro_flag ==1)
						$this->set_notice("image not supported");
                        else if($skin_erro_flag ==2)
                        $this->set_notice("image size not supported");
					    else if($skin_erro_flag==3)
                        $this->set_notice("image file corrupted");
			            else
			            {
							$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,click_url=?,status=?,updation_time=? where id=?",array($name,$clickurl,$adstatus,time(),$aid));

							$filename_array=array();
							$fileremove_array=array();

							$filename_array=json_decode($old_name,true);



							if($res->error =="")
							{
								$success_flag=1;

								if(count($filenames) >0)
								{
									if(!is_dir("../".DATA_DIR))
									mkdir("../".DATA_DIR,0777);

									if(!is_dir("../".DATA_DIR."/".$aid))
									mkdir("../".DATA_DIR."/".$aid,0777);


									foreach($filenames as $key => $filename)
									{
										if(move_uploaded_file($_FILES["skin_banner_".$key]["tmp_name"],"../".DATA_DIR."/".$aid."/".$filename))
										{
											$image=new ImageHelper("../".DATA_DIR."/".$aid."/".$filename);
											$image->resize($width1,$height1,"../".DATA_DIR."/".$aid."/".$filename);

											if(isset($filename_array[$key]))
											$fileremove_array[]=$filename_array[$key];

											$filename_array[$key]=$filename; //New file name
										}
										else
										{
											$image_upload_flag=1;
											break;
										}
									}

									if($image_upload_flag ==1)
									$this->flash($this->get_message('image upload failed'), $this->make_url('ad/edit_default/'.$aid),0);
									else
									{
										if(count($filename_array) >0)
										{
											$filename_update=json_encode($filename_array);

											$res_updation=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET banner_id=?,banner=? WHERE id=?",array($bannerid,$filename_update,$aid));

											if($res_updation->error =="")
											{
												$success_flag=1;

												foreach($fileremove_array as $rkey=>$rvalue)
												{
													unlink("../".DATA_DIR.'/'.$aid.'/'.$rvalue);
												}
											}
											else
											$this->set_notice("error occurred");
										}
									}
								}
							}
							else
							$this->set_notice("error occurred");

							if($success_flag ==1)
							{
								if($frompg ==1)
								$this->flash($this->get_message('default skin ad edited'), $this->make_url('ad/list_default/'.$tp.'/'.$st.'/'.$pg));
								else
								$this->flash($this->get_message('default skin ad edited'), $this->make_url('ad/view_default/'.$aid));
							}
			        	}
					}
				}
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($aid));
			$this->set_result("res",$res);
		}


		$this->set_variable("aid",$aid);

		$this->set_variable("frompg",$frompg);
		$this->set_variable("tp",$tp);
		$this->set_variable("st",$st);
		$this->set_variable("pg",$pg);
	}
	function change_status_default_action()
	{
		$db= DAL::get_instance();

		$aid=$this->read_page_param(1);


		if(DEMO_MODE && $aid <= 100)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('ad/list_default'),0);
			exit;
		}

		if(!$this->get_ad_validation_admin($aid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('ad/list_default'),0);
		}

		$status=$this->read_page_param(2);
		$from=$this->read_page_param(3);

		$type=$this->read_page_param(4);
		$oldstatus=$this->read_page_param(5);
		$pg=$this->read_page_param(6);



		if($status==1)
		{
			$res=$db->execute_query("update ".TABLE_PREFIX."ads set status=1 where id=? AND uid = 0",array($aid));

			if($from==1)
			$this->flash($this->get_message('ad activated'), $this->make_url('ad/list_default/'.$type.'/'.$oldstatus.'/'.$pg));
			else
			$this->flash($this->get_message('ad activated'), $this->make_url('ad/view_default/'.$aid));

			exit;
		}
		else if($status==0)
		{
			$res=$db->execute_query("update ".TABLE_PREFIX."ads set status=0 where id=? AND uid = 0",array($aid));

			if($from==1)
			$this->flash($this->get_message('ad blocked'), $this->make_url('ad/list_default/'.$type.'/'.$oldstatus.'/'.$pg));
			else
			$this->flash($this->get_message('ad blocked'), $this->make_url('ad/view_default/'.$aid));

			exit;
		}
	}

	function view_default_action()
	{
		$this->set_title($this->get_label('view default ads'));
		$ad_id=$this->read_page_param(1);

		if(!$this->get_ad_validation_admin($ad_id))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
		}

		$db= DAL::get_instance();
		$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($ad_id));
		$this->set_result("res",$res);
		$this->set_variable('aid',$ad_id);
	}
	function preview_default_action()
	{
		$this->disable_notice_area();
		$aid=$this->read_page_param(1);
		$frompg=$this->read_page_param(2);

		$db= DAL::get_instance();

		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);


		if(!$this->get_ad_validation_admin($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_url('index/control_panel'),0);
			exit;
		}

		$res1=$db->execute_query("select b.height,b.width,b.filesize from ".TABLE_PREFIX."ads a INNER JOIN ".TABLE_PREFIX."banner_dimensions b where a.banner_id=b.id and a.id=?",array($aid));
		$this->set_result("res1",$res1);


		$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=? ORDER BY id desc",array($aid));
		$this->set_result("res",$res);

		$usid_row=$res->fetch_assoc();
		$adtype=$usid_row['type'];

		if($adtype ==14)
		{
			$json_banners=$usid_row['banner'];

			$json_array=json_decode($json_banners,true);

			$json_array_result[]=$json_array;

			$this->set_array("json_array",$json_array_result);
		}

		$this->set_variable("aid",$aid);
		$this->set_variable("frompg",$frompg);
	}
	function delete_default_action()
	{
		$db= DAL::get_instance();

		$aid=$this->read_page_param(1);

		if(DEMO_MODE && $aid <= 100)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('ad/list_default'),0);
			exit;
		}

		if(!$this->get_ad_validation_admin($aid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('ad/list_default'),0);
		}



		$type=$this->read_page_param(2);
		$oldstatus=$this->read_page_param(3);
		$pg=$this->read_page_param(4);


		$old_type=$db->read_single_column("select type from ".TABLE_PREFIX."ads where id=?",array($aid));

		if($old_type ==2 || $old_type ==11)
		{
			$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."ads where id=?",array($aid));

			unlink("../".DATA_DIR."/".$aid."_".$old_name);
		}
		else if($old_type ==13)
		{
			$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."ads where id=?",array($aid));

			unlink("../".DATA_DIR.'/video/'.$aid.'/'.$old_name);

			rmdir("../".DATA_DIR.'/video/'.$aid.'/');
		}
		else if($old_type ==14)
		{
			$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."ads where id=?",array($aid));

			$filename_array=json_decode($old_name,true);

			foreach($filename_array as $rkey=>$rvalue)
			{
				unlink('../'.DATA_DIR.'/'.$aid.'/'.$rvalue);
			}

			rmdir('../'.DATA_DIR.'/'.$aid.'/');
		}


		$res=$db->execute_query("delete from ".TABLE_PREFIX."ads where id=? AND uid = 0",array($aid));

		$this->flash($this->get_message('ad deleted'), $this->make_url('ad/list_default/'.$type.'/'.$oldstatus.'/'.$pg));
		exit;
	}

	function pricing_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();

		$aid=$this->read_page_param(1);

		if(!$this->get_ad_validation_user($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_base_url('index/control_panel',ADMIN_DIR),0);
		}

		$this->set_variable('aid',$aid);

		$adrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
		$this->set_result('adrow',$adrow);


		$expiry=$db->execute_query("SELECT status,budget,budget_used,start_date,end_date FROM ".TABLE_PREFIX."ads_budget_history WHERE aid=? ORDER BY id DESC",array($aid));
		$this->set_result('expiry',$expiry);
	}
};
?>
