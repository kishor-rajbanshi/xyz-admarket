<?php

include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

if(file_exists(ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php"))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";

if(file_exists(ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php"))
include_once ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php";

if(file_exists(LIB_DIR_PATH."getID3/getid3.php"))
include_once(LIB_DIR_PATH."getID3/getid3.php");


class AdController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="create" || $this->get_action()=="view" || $this->get_action()=="edit" || $this->get_action()=="list" || $this->get_action()=="delete" || $this->get_action()=="keywords" || $this->get_action()=="edit_keyword" || $this->get_action()=="delete_keyword" || $this->get_action()=="locations" || $this->get_action()=="preview" || $this->get_action()=="update_pause_status" || $this->get_action()=="statistics" || $this->get_action()=="detailed_statistics" || $this->get_action()=="pricing" || $this->get_action()=="update_pricing" || $this->get_action()=="pricing_cancel" || $this->get_action()=="run_history")
		{
			if(!(LoginHelper::validate_user_login()))
			{
				$this->flash($this->get_message('login failed'), BASE,0);
			}


			$uid=$this->read_cookie_param(COOKIE_LOGINID);
			$db= DAL::get_instance();

			$status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));

			if($status !=1)
			$this->flash($this->get_message('your advertiser account in inactive'), $this->make_url('dashboard/publisher_home'),0);
		}
	}

	function load_ad_preview_action()
	{
		$this->disable_notice_area();
		$db = DAL::get_instance();

		if(!(LoginHelper::validate_user_login()))
		{
			echo ""; //User not login
			die;
		}

		$adLayoutID  		 = intval($this->read_post_param("adLayoutID"));
		$adcodeID  		     = intval($this->read_post_param("adcodeID"));
		$adType 			 = intval($this->read_post_param("adType")); //1 => Text only, 11 => Text + image only,18 =>notification ad
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


			$responseResult = $this->get_adlayout_preview(4,0,$adType,$adType,$adLayoutID,$borderType,$bannerSize,$textimageSize,$imagePosition,$adblockWidth,$adblockHeight,$creditTextID,$creditAlignment,$creditPositioning,$layoutTheme,$layoutFont,$adcodeID,$layoutTheme,$layoutFont,0,0,0,0,"",$buttonSectionWidth,$buttonSectionHeight,$titleEnabled,$descriptionEnabled,$urlEnabled,$buttonEnabled,$titleBorder,$descriptionBorder,$displayurlBorder,$contentSlide,$slideDirection,$slideDuration,$CTAPosition,$CTABorderRadius,$CTAPaddingHorizontal,$CTAPaddingVertical,$titleText,$descriptionText,$displayurlText,$buttonText,$bannerName);

		}
		else
		{
			$bannerSize  		    = 0;
			$textimageSize  	    = intval($this->read_post_param("textimageSize"));
			$imagePosition  		= 0;
			$adblockWidth  			= 300;

			if($adType == 18)
			{
				$adblockHeight  		= 100;
				$imagePosition  		= 2;
			}
			else
			{
				$adblockHeight  		= 250;
			}

			$layoutTheme      		= 1;
			$layoutFont       		= 1;
			$borderType  			= 1;


			$responseResult = $this->get_adlayout_preview(3,0,$adType,$adType,$adLayoutID,$borderType,$bannerSize,$textimageSize,$imagePosition,$adblockWidth,$adblockHeight,$creditTextID,$creditAlignment,$creditPositioning,$layoutTheme,$layoutFont,0,0,0,0,0,0,0,"",          $buttonSectionWidth,$buttonSectionHeight,$titleEnabled,$descriptionEnabled,$urlEnabled,$buttonEnabled,$titleBorder,$descriptionBorder,$displayurlBorder,$contentSlide,$slideDirection,$slideDuration,$CTAPosition,$CTABorderRadius,$CTAPaddingHorizontal,$CTAPaddingVertical,$titleText,$descriptionText,$displayurlText,$buttonText,$bannerName);
		}

		echo $responseResult;

		die;
	}




	function delete_banner_action()
	{
		//$this->disable_notice_area();
		$db   = DAL::get_instance();
		$uid  = $this->read_cookie_param(COOKIE_LOGINID);

		$bannerID   = $this->read_post_param("bannerID");
		$aid        = $this->read_post_param("aid");
		$fromData   = intval($this->read_post_param("fromData")); //0=>affiliate banner 1=>other ads banners

		if(!(LoginHelper::validate_user_login()))
		{
			echo 1;
			exit;
		}


		if($fromData == 0)
		{
			$bannerID = intval($db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ad_image_mapping WHERE id=? AND uid=? AND aid=?",array($bannerID,$uid,$aid)));

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
			$bannerList = $db->read_single_column("SELECT banner_list FROM ".TABLE_PREFIX."ads WHERE id = ? AND uid = ?",array($aid,$uid));

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

			$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET banner_list = ? WHERE id = ? AND uid = ?",array($tempArrayJson,$aid,$uid));

			if($deleteBanner != "" && file_exists(DATA_DIR.'/'.$aid.'/'.$deleteBanner))
			unlink(DATA_DIR.'/'.$aid.'/'.$deleteBanner);

			echo 2;
			exit;
		}
	}



	function get_responsive_size_action()
	{
		$db         = DAL::get_instance();
		$bannerSize = intval($this->read_post_param("bannerSize"));
		$bannerType = intval($this->read_post_param("bannerType"));//2=>Normal Banner 11=>Text+Image Banner

		$bannerSizeArray   = array();
		$bannerSizeArray[] = $bannerSize;

		$responsive_enabled = $this->get_addon_status('responsive-ads_enabled');

		if($responsive_enabled != 1)
		{
			$bannerSizeJSON = json_encode($bannerSizeArray);
			echo $bannerSizeJSON;
			die;
		}

		if($bannerType == 11)
		$columnString = " textimage_size ";
		else
		$columnString = " bannersize ";



		$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."adblock WHERE ".$columnString." = ?",array($bannerSize));

		while($rowData = $row->fetch_assoc())
		{
			$xsmall_dev_adblock = $rowData['xsmall_dev_adblock'];
			$small_dev_adblock  = $rowData['small_dev_adblock'];
			$medium_dev_adblock = $rowData['medium_dev_adblock'];
			$large_dev_adblock  = $rowData['large_dev_adblock'];

			if($xsmall_dev_adblock > 0)
			{
				$Size = $db->read_single_column("SELECT ".$columnString." FROM ".TABLE_PREFIX."adblock WHERE id = ? ",array($xsmall_dev_adblock));

				if($Size > 0 && !in_array($Size, $bannerSizeArray))
				$bannerSizeArray[] = $Size;
			}

			if($small_dev_adblock > 0)
			{
				$Size = $db->read_single_column("SELECT ".$columnString." FROM ".TABLE_PREFIX."adblock WHERE id = ? ",array($small_dev_adblock));

				if($Size > 0 && !in_array($Size, $bannerSizeArray))
				$bannerSizeArray[] = $Size;
			}

			if($medium_dev_adblock > 0)
			{
				$Size = $db->read_single_column("SELECT ".$columnString." FROM ".TABLE_PREFIX."adblock WHERE id = ? ",array($medium_dev_adblock));

				if($Size > 0 && !in_array($Size, $bannerSizeArray))
				$bannerSizeArray[] = $Size;
			}

			if($large_dev_adblock > 0)
			{
				$Size = $db->read_single_column("SELECT ".$columnString." FROM ".TABLE_PREFIX."adblock WHERE id = ? ",array($large_dev_adblock));

				if($Size > 0 && !in_array($Size, $bannerSizeArray))
				$bannerSizeArray[] = $Size;
			}
		}


		$bannerSizeJSON = json_encode($bannerSizeArray);
		echo $bannerSizeJSON;
		die;
	}



	function default_mapping_action()
	{
		$db= DAL::get_instance();
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE uid >0");
		while($rowdata=$row->fetch_assoc())
		{
			$row123=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE aid=? LIMIT 0,1",array($rowdata['id']));
			if($row123 =='')
			{
				$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) VALUES (?,?,?)",array($rowdata['uid'],$rowdata['id'],0));
			}
		}
		exit;
	}

	function suggestion_action()
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");



		$this->disable_notice_area();
		$type=$this->read_page_param(1);
		$pricing=$this->read_page_param(2);
		$device=$this->read_page_param(3);
		$bannerid=$this->read_page_param(4);
		$aid=intval($this->read_page_param(5));

		echo $this->get_suggested_value_ad($type,$aid,$bannerid,$pricing,$device);

		exit;
	}


	function create_action()
	{
		$this->set_title($this->get_label('create ad'));
		$db   = DAL::get_instance();
		$time = time();

		$uid  = $this->read_cookie_param(COOKIE_LOGINID);
		$this->set_variable('uid', $uid);


		if($_POST)
		$firstParameter = intval($this->read_post_param('firstParameter'));
		else
		$firstParameter = intval($this->read_page_param(1));

		$cpdPackage     = 0;
		$positionID     = 0;
		$cloneFlag      = 0;
		$parentAdID     = 0;

		if($firstParameter == -1) // Ad clone case
		{
				if($_POST)
				$parentAdID = intval($this->read_post_param('parentAdID'));
				else
				$parentAdID = intval($this->read_page_param(2));

				if(!$this->get_your_ad($parentAdID,$uid))
 			 	{
 				 		$this->flash($this->get_message('invalid id'), $this->make_url('ad/list'),0);
 				 		exit;
 			 	}

				$parentAdType = intval($this->get_ad_type_value($parentAdID));

				if($parentAdType == 7)
				{
 				 		$this->flash($this->get_message('invalid id'), $this->make_url('ad/list'),0);
 				 		exit;
 			 	}

				$cloneFlag      = 1;
		}
		else if($firstParameter > 0)//For manage CPD ad creation from marketplace
		{
				if($_POST)
				$positionID  = intval($this->read_post_param('positionID'));
				else
				$positionID  = intval($this->read_page_param(1));

				if($_POST)
				{
						if($positionID > 0)
						$cpdPackage  = intval($this->read_post_param('package_'.$positionID));
				}
				else
				{
						if($positionID > 0)
						$cpdPackage  = intval($this->read_page_param(2));
				}
		}

		$adCloneEnabled = Configuration::get_instance()->read('enable_advertisers_ad_clone_option');

		if($adCloneEnabled == 0 && $cloneFlag == 1)
		{
				$cloneFlag      = 0;
				$firstParameter = 0;
				$parentAdID     = 0;
		}

		$this->set_variable("firstParameter",$firstParameter);
		$this->set_variable("cloneFlag",$cloneFlag);
		$this->set_variable("parentAdID",$parentAdID);


    	$text_ads_enabled      = Configuration::get_instance()->read('text-ads_enabled');
		$textimage_enabled     = $this->get_addon_status('text-image-ads_enabled');
		$pop_enabled           = $this->get_addon_status('pop-ads_enabled');
		$directlink_enabled 	 = $this->get_addon_status('direct-link-ads_enabled');
		$affiliate_enabled     = $this->get_addon_status('affiliate-ads_enabled');
		$ecommerce_enabled     = $this->get_addon_status('ecommerce-ads_enabled');
		$video_enabled         = $this->get_addon_status('video-ads_enabled');
		$expandable_enabled    = $this->get_addon_status('expandable-banners_enabled');
		$skin_enabled          = $this->get_addon_status('skin-ads_enabled');
		$html5_enabled         = $this->get_addon_status('html5-ads_enabled');
		$retargeting_enabled   = $this->get_addon_status('retargeting_enabled');

		$cpd_enabled = $this->get_addon_status('sponsored_enabled');

		if($cpd_enabled != 1)
		$positionID  = 0;

		$positionAdType        = 0;
		$positionAdcodeType    = 0;
		$positionBannerSize    = 0;
		$positionTextImageSize = 0;
		$adblockID             = 0;


		if($positionID > 0)
		{
			if(!SponsoredHelper::get_position_exists($positionID))
			$positionID  = 0;

			$this->set_variable("positionID",$positionID);


			$cpdseo=$this->get_seo_name('dispatch/sponsored/24');

	  		if($cpdseo !='')
	 		$cpdurl=BASE.$cpdseo;
	 		else
	 		$cpdurl=$this->make_url('dispatch/sponsored/24');


			$adtype_str="";

			if($text_ads_enabled == 0)
			$adtype_str.=" p.ad_type <>1 ";

			if($textimage_enabled !=1)
			{
				if($adtype_str !="")
				$adtype_str.=' AND ';

				$adtype_str.=' p.ad_type <> 11 ';
			}

			if($skin_enabled !=1)
			{
				if($adtype_str !="")
				$adtype_str.=' AND ';

				$adtype_str.=' p.ad_type <> 14 ';
			}

			if($adtype_str !="")
			$adtype_str=' AND ('.$adtype_str.') ';

			$positionRow = $db->execute_query("SELECT p.*,ab.bannersize,ab.textimage_size
				FROM ".TABLE_PREFIX."adunit p  INNER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=p.blockid
				INNER JOIN ".TABLE_PREFIX."sites s ON s.id = p.sid
				LEFT OUTER JOIN ".TABLE_PREFIX."users u ON u.id = p.pubid
				WHERE (u.pub_status = 1 OR (p.pubid = 0 AND u.pub_status is NULL)) AND s.status = 1 AND s.marketplace_display = 1 AND p.id = ? ".$adtype_str." AND p.cpd_rate > 0 AND p.display_type = 3 ORDER BY p.id DESC",array($positionID));

			if($positionRow->get_num_records() == 0)
			{
				$this->flash($this->get_message('invalid operation'), $cpdurl,0);
			}

			$positionData          = $positionRow->fetch_assoc();

			$positionID            = $positionData['id'];
			$positionAdcodeType    = $positionData['adcode_type'];
			$positionAdType        = $positionData['ad_type'];
			$positionBannerSize    = $positionData['bannersize'];
			$positionTextImageSize = $positionData['textimage_size'];
			$adblockID             = $positionData['blockid'];
			$cpdRate               = $positionData['cpd_rate'];
			$sitePid               = $positionData['pubid'];
			$siteID                = $positionData['sid'];

			$cpdProfitPercentage   = 0;

	        if($sitePid > 0)
			$cpdProfitPercentage = $db->read_single_column("SELECT sponsored_profit_percentage FROM ".TABLE_PREFIX."users WHERE id = ?",array($sitePid));

			if($cpdProfitPercentage == 0)
			$cpdProfitPercentage = Configuration::get_instance()->read('sponsored_profit_percentage');

			$this->set_variable("cpdProfitPercentage",$cpdProfitPercentage);


			$bannerAdSupport       = 0;
			$textimageAdSupport    = 0;
            $ecommerceAdSupport    = 0;


			if($positionBannerSize > 0)
			{
				if($positionAdType == 2 || $positionAdType == 3)
				{
					$bannerSizeID = $db->read_single_column("select id from ".TABLE_PREFIX."banner_dimensions where status=1 AND (banner_type =0 OR banner_type =1) AND image_support =1 AND id = ?",array($positionBannerSize));

					if(intval($bannerSizeID) > 0)
					$bannerAdSupport = 1;


					if($ecommerce_enabled ==1)
					{
						$ecomBannerSizeID = $db->read_single_column("select b.id from ".TABLE_PREFIX."banner_dimensions b INNER JOIN ".TABLE_PREFIX."display_layout d ON b.id=d.layout_banner where status=1 AND banner_type =0 AND ecommerce_support =1 AND b.id = ?",array($positionBannerSize));

						if(intval($ecomBannerSizeID) > 0)
						$ecommerceAdSupport = 1;
					}
			   }
			}

			if($positionTextImageSize > 0)
			{
				if($positionAdType == 3 || $positionAdType == 11)
				{
					$bannerSizeID = $db->read_single_column("select id from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =3 AND id = ?",array($positionTextImageSize));

					if(intval($bannerSizeID) > 0)
					$textimageAdSupport = 1;
			    }
			}

			$this->set_variable("bannerAdSupport",$bannerAdSupport);
			$this->set_variable("textimageAdSupport",$textimageAdSupport);
			$this->set_variable("ecommerceAdSupport",$ecommerceAdSupport);
			$this->set_variable("positionBannerSize",$positionBannerSize);
			$this->set_variable("positionTextImageSize",$positionTextImageSize);
			$this->set_variable("adblockID",$adblockID);
			$this->set_variable("cpdRate",$cpdRate);
			$this->set_variable("sitePid",$sitePid);

			$pop_enabled           = 0;
			$directlink_enabled		 = 0;
			$affiliate_enabled     = 0;
			$video_enabled         = 0;
			$retargeting_enabled   = 0;

			if($positionID > 0 && (($positionAdType == 1 && $text_ads_enabled != 1) || ($positionAdType == 2 && ($bannerAdSupport == 0 && $ecommerceAdSupport == 0)) || ($positionAdType == 3 && ($text_ads_enabled == 0 && $bannerAdSupport == 0 && $ecommerceAdSupport == 0 && $textimageAdSupport == 0)) || ($positionAdType == 11 && ($textimage_enabled != 1 || $textimageAdSupport == 0)) || ($positionAdType == 14 && $skin_enabled != 1)))
			{
				$this->flash($this->get_message('ad type not supported'), $cpdurl,0);
			}
		}


		$this->set_variable("positionID",$positionID);
		$this->set_variable("positionAdType",$positionAdType);
		$this->set_variable("positionAdcodeType",$positionAdcodeType);


		if($html5_enabled == 1)
		{
			$html5_ads_for_all_advertisers=intval(Configuration::get_instance()->read('html5_ads_for_all_advertisers'));

			if($html5_ads_for_all_advertisers ==0)
			{
				$advertiser_html5_support=$this->get_advertiser_html5_support($uid);

				if($advertiser_html5_support ==0)
				$html5_enabled = 0;
			}
		}


		$linear_support=0;
		$nonlinear_support=0;
		$html5_player_support=0;


		if($video_enabled ==1)
		{
			$linear_support=intval(Configuration::get_instance()->read('vast_player_linear_support'));
			$nonlinear_support=intval(Configuration::get_instance()->read('vast_player_nonlinear_support'));
			$html5_player_support=intval(Configuration::get_instance()->read('html5_player_support'));
		}


		$this->set_variable('expandable_enabled',$expandable_enabled);
		$this->set_variable('video_enabled',$video_enabled);
		$this->set_variable('linear_support',$linear_support);
		$this->set_variable('nonlinear_support',$nonlinear_support);
		$this->set_variable('html5_player_support',$html5_player_support);
		$this->set_variable('skin_enabled',$skin_enabled);
		$this->set_variable('html5_enabled',$html5_enabled);

		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);

		$retargeting=0;
		$vast_support=0;

		$res1=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND (banner_type =0 OR banner_type =1) AND image_support =1 ORDER BY width DESC");
		$this->set_result("res1",$res1);

		if($ecommerce_enabled ==1)
		{
			$res3=$db->execute_query("select b.* from ".TABLE_PREFIX."banner_dimensions b INNER JOIN ".TABLE_PREFIX."display_layout d ON b.id=d.layout_banner where status=1 AND banner_type =0 AND ecommerce_support =1 GROUP BY b.id");
			$this->set_result("res3",$res3);
		}

		if($textimage_enabled ==1)
		{
			$res6=$db->execute_query("select b.* from ".TABLE_PREFIX."banner_dimensions b where b.status=1 AND b.banner_type =3 GROUP BY b.id ORDER BY b.width DESC");
			$this->set_result("res6",$res6);
		}

		if($skin_enabled ==1)
		{
			$res14_banner=$db->execute_query("select b.* from ".TABLE_PREFIX."banner_dimensions b  where b.status=1 AND b.banner_type =4");
			$this->set_result("res14_banner",$res14_banner);

			$res14_adblock=$db->execute_query("select ab.* from ".TABLE_PREFIX."adblock ab  where ab.status=1 AND ab.type =2 AND ab.banner_type =4");
			$this->set_result("res14_adblock",$res14_adblock);
		}


		$this->set_variable('ecommerce_enabled',$ecommerce_enabled);

		$layoutid=0;
		$htype=0;
		$hdata="";
		$hlink="";
		$callactiontext="";


		$themedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adblock_theme WHERE theme_type=1");
		$themedata1=$themedata->fetch_assoc();



		$color1=$themedata1['title'];
		$color2=$themedata1['description'];
		$color3=$themedata1['url'];
		$color4=$themedata1['border'];
		$color5=$themedata1['background'];
		$color6=$themedata1['ad_background'];
		$color7=$themedata1['price'];
		$color8=$themedata1['offer_price'];
		$color9=$themedata1['button'];
		$color10=$themedata1['button_background'];
		$color11=$themedata1['button_hover'];
		$color12=$themedata1['heading'];
		$color13=$themedata1['heading_button_background'];
		$color14=$themedata1['heading_button'];
		$color15=$themedata1['heading_button_hover'];
		$color16=$themedata1['ad_selection_border'];


		$maxsize="";
		$rowid=1;
		$file_type=0;

		$expandable=0;
		$expand_width=0;
		$expand_height=0;
		$expand_support=0;


		$banner_type	= 0;


		$additional_banners = "";

		$cta_button_text = "";

		if($_POST)
		{
			$additional_banners = $this->read_post_param('additional_banners'); //For CPD Banners


			$name=$this->read_post_param('name');
			$type=$this->read_post_param('type1');
			$title=$this->read_post_param('title');
			$desc=$this->read_post_param('desc');
			$displayurl=$this->read_post_param('displayurl');
			$cta_button_text=$this->read_post_param('cta_button_text');
			$clickurl=$this->read_post_param('clickurl');

			$clickurl=filter_var($clickurl,FILTER_SANITIZE_URL);

			if($html5_enabled ==1 && $type ==2)
			$banner_type=intval($this->read_post_param('banner_type_value'));


			$adpricing=$this->read_post_param('adpricing');




			$referral_enabled=$this->get_addon_status('referral_enabled');

			$ref_id=0;
			$refferal_status=0;
			$ref_string="";

			if($referral_enabled ==1)
			$ref_string=",rid";


			$user_data=$db->execute_query("SELECT adv_status,adv_account_balance".$ref_string." FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			$user_data_row=$user_data->fetch_assoc();

			$adv_balance = $user_data_row['adv_account_balance'];
			$adv_status  = $user_data_row['adv_status'];



			if($referral_enabled ==1)
			{
				$ref_id=intval($user_data_row['rid']);

				if($ref_id > 0)
				{
					$ref_array=$this->get_referral_user_active($ref_id);

					if($ref_array[0] >0)
					$refferal_status=1;
				}
			}



			if($adpricing !=3)
			$adstatus      = -2;
			else
			$adstatus=Configuration::get_instance()->read('default_ad_status');

			$ad_start_time = 0;


			if($type ==12)
			$desc=$this->read_post_param('description');
			else
			$desc=$this->read_post_param('desc');


			$maxtitle           = Configuration::get_instance()->read('max_ad_title_length');
			$maxdesc            = Configuration::get_instance()->read('max_ad_desc_length');
			$maxdispurl         = Configuration::get_instance()->read('max_display_url_length');
			$ctaButtonLength 	= Configuration::get_instance()->read('cta_button_text_length');


			$rowid=$this->read_post_param('rowid');
			$file_type=intval($this->read_post_param('file_type_hidden'));

			$ecommerce_flag=0;

			if($file_type ==1)
			{
				$rowarray=explode('-',$rowid);
				$csvarray=array();

				foreach($rowarray as $gkey => $gvalue)
				{
					$csvarray1=array();

					$gtitle=trim($this->read_post_param('ad_title_'.$gvalue));
					$gdescription=trim($this->read_post_param('ad_description_'.$gvalue));
					$gdispurl=trim($this->read_post_param('ad_display_url_'.$gvalue));
					$gclickurl=trim($this->read_post_param('ad_click_url_'.$gvalue));
					$gimageurl=trim($this->read_post_param('image_url_'.$gvalue));
					$gretargetingurl=trim($this->read_post_param('ad_retargeting_url_'.$gvalue));
					$gadprice=trim($this->read_post_param('ad_price_'.$gvalue));
					$gadofferprice=trim($this->read_post_param('ad_offer_price_'.$gvalue));


					$this->set_variable('ad_title_'.$gvalue,$gtitle);
					$this->set_variable('ad_description_'.$gvalue,$gdescription);
					$this->set_variable('ad_display_url_'.$gvalue,$gdispurl);
					$this->set_variable('ad_click_url_'.$gvalue,$gclickurl);
					$this->set_variable('image_url_'.$gvalue,$gimageurl);
					$this->set_variable('ad_retargeting_url_'.$gvalue,$gretargetingurl);
					$this->set_variable('ad_price_'.$gvalue,$gadprice);
					$this->set_variable('ad_offer_price_'.$gvalue,$gadofferprice);


					if($gtitle !="" && $gdescription !="" && $gdispurl !="" && $gclickurl !="" && $gimageurl !="")
					{
						$ecommerce_flag=1;

						$csvarray1[]=substr($gtitle,0,$maxtitle);
						$csvarray1[]=substr($gdescription,0,$maxdesc);
						$csvarray1[]=substr($gdispurl,0,$maxdispurl);
						$csvarray1[]=$gclickurl;
						$csvarray1[]=$gimageurl;
						$csvarray1[]=$gadprice;
						$csvarray1[]=$gadofferprice;
						$csvarray1[]=$gretargetingurl;


						$csvarray[]=$csvarray1;
					}
				}
			}

			if(($adpricing ==0 || $adpricing ==6) && $type != 12)
			$retargeting=intval($this->read_post_param('retargeting'));

			if(($adpricing ==0 || $adpricing ==1 || $adpricing ==6) && $type != 12)
			{
				if($video_enabled ==1 && $nonlinear_support ==1 && ($type ==1 || $type ==2))
				$vast_support=intval($this->read_post_param('vast_support'));
			}

			$bannersize=0;

			if($type ==2)
			$bannersize=$this->read_post_param('bannersize_02');
			else if($type ==7)
			$bannersize=$this->read_post_param('bannersize_07');
			else if($type ==11)
			$bannersize=$this->read_post_param('bannersize_11');
			else if($type ==14)
			$bannersize=$this->read_post_param('bannersize_14');

			if($type == 2 && $banner_type == 0)
			$expandable=intval($this->read_post_param('expandable_'.$bannersize));

			$layout_flag=0;
			$checked_size="";

			if($ecommerce_enabled ==1 && $type ==7)
			{
				$size_checked=$this->read_post_param('size_checked');

				if($size_checked !="")
				{
					$size_array=explode('-',$size_checked);

					foreach($size_array as $k123=>$v123)
					{
						if(trim($v123) !="")
						{
							$size_checkbox=intval($this->read_post_param('chk_diamension_'.$v123));

							$this->set_variable('chk_diamension_'.$v123,$size_checkbox);

							if($size_checkbox ==1)
							{
								$layout_value=intval($this->read_post_param('layoutid_'.$v123));

								if($layout_value >0)
								{
									$layout_flag=1;

									if($checked_size !="")
									$checked_size.="-";

									$checked_size.=$v123.'_'.$layout_value;

								}

								$this->set_variable('layoutid_'.$v123,$layout_value);
							}
						}
					}
				}

				$this->set_variable('size_checked',$size_checked);


				$htype=intval($this->read_post_param('htype'));
				$hdata=$this->read_post_param('hdata');
				$hlink=$this->read_post_param('hlink');
				$callactiontext=$this->read_post_param('callactiontext');
				$ecommercelogo=$_FILES["ecommercelogo"]["name"];


				$csvfile="";
				//$csvfile=$_FILES["csvfile"]["name"];

				$color1=$this->read_post_param('color1');
				$color2=$this->read_post_param('color2');
				$color3=$this->read_post_param('color3');
				$color4=$this->read_post_param('color4');
				$color5=$this->read_post_param('color5');
				$color6=$this->read_post_param('color6');
				$color7=$this->read_post_param('color7');
				$color8=$this->read_post_param('color8');
				$color9=$this->read_post_param('color9');
				$color10=$this->read_post_param('color10');
				$color11=$this->read_post_param('color11');
				$color12=$this->read_post_param('color12');
				$color13=$this->read_post_param('color13');
				$color14=$this->read_post_param('color14');
				$color15=$this->read_post_param('color15');
				$color16=$this->read_post_param('color16');

				if($color1 =="")
				$color1=$themedata1['title'];
				if($color2 =="")
				$color2=$themedata1['description'];
				if($color3 =="")
				$color3=$themedata1['url'];
				if($color4 =="")
				$color4=$themedata1['border'];
				if($color5 =="")
				$color5=$themedata1['background'];
				if($color6 =="")
				$color6=$themedata1['ad_background'];
				if($color7 =="")
				$color7=$themedata1['price'];
				if($color8 =="")
				$color8=$themedata1['offer_price'];
				if($color9 =="")
				$color9=$themedata1['button'];
				if($color10 =="")
				$color10=$themedata1['button_background'];
				if($color11 =="")
				$color11=$themedata1['button_hover'];
				if($color12 =="")
				$color12=$themedata1['heading'];
				if($color13 =="")
				$color13=$themedata1['heading_button_background'];
				if($color14 =="")
				$color14=$themedata1['heading_button'];
				if($color15 =="")
				$color15=$themedata1['heading_button_hover'];
				if($color16 =="")
				$color16=$themedata1['ad_selection_border'];
			}

			if($type ==7)
			{
				$title="";
				$desc="";
				$displayurl="";
				$clickurl="";
			}
			else if($type ==2 || $type ==9 || $type ==13 || $type ==14 || $type ==21)
			{
				$title="";
				$desc="";

				if($type !=13)
				$displayurl="";
			}


			if(mb_strlen($name,'utf8') >25)
			$name=substr($name,0,25);

			$this->set_variable('name', $name);
			$this->set_variable('type', $type);
			$this->set_variable('title', $title);
			$this->set_variable('desc', $desc);
			$this->set_variable('displayurl', $displayurl);
			$this->set_variable('cta_button_text', $cta_button_text);
			$this->set_variable('clickurl', $clickurl);
			$this->set_variable('bannersize', $bannersize);
			$this->set_variable('adpricing', $adpricing);

		if($name == "")
		$this->set_notice("please fill ad name");
		else if($type !=7 && $clickurl == "")
		$this->set_notice("please fill click url");
		else if(($type ==1 || $type ==11 || $type ==12 || $type == 18) && $title =="")
		$this->set_notice("please fill ad title");
		else if(($type ==1 || $type ==11 || $type ==12 || $type == 18) && $desc =="")
		$this->set_notice("please fill ad description");
		else if(($type ==1 || $type ==11 || $type ==13) && $displayurl =="")
		$this->set_notice("please fill display url");
		else if($type !=7 && !UtilityHelper::is_valid_url($clickurl))
		$this->set_notice("click url is not valid");

		else if(($type ==1 || $type ==11) && mb_strlen($title,'utf8') > $maxtitle)
		$this->set_notice($this->get_message("check ad length",array('x'=>$maxtitle)));

		else if(($type ==1 || $type ==11) && mb_strlen($desc,'utf8') > $maxdesc)
		$this->set_notice($this->get_message("check desc length",array('x'=>$maxdesc)));

		else if(($type ==1 || $type ==11 || $type ==13 ||  $type == 18) && mb_strlen($displayurl,'utf8') > $maxdispurl)
		$this->set_notice($this->get_message("check display url length",array('x'=>$maxdispurl)));

		else if(($type ==1 || $type ==11) && $cta_button_text != "" && mb_strlen($cta_button_text,'utf8') > $ctaButtonLength)
		$this->set_notice($this->get_message("check cta button length",array('x'=>$ctaButtonLength)));

		else if(($type==2 || $type ==11) && $_FILES["banner"]["name"] =="")
		$this->set_notice("please upload ad banner");

		else if($type == 2 && $expandable ==1 && $_FILES["expandable_banner_".$bannersize]["name"] == "")
		$this->set_notice("please upload expandable ad banner");

		else if($type ==13 && $_FILES["videofile"]["name"] =="")
		$this->set_notice("please upload ad video");

		else if($type ==7 && ($size_checked =="" || $layout_flag ==0))
		$this->set_notice("please choose a display layout");
		else if($type ==7 && ($hdata =="" || $hlink ==""))
		$this->set_notice("please enter headline data");
		else if($type ==7 && $ecommercelogo =="")
		$this->set_notice("please upload logo image");
		//else if($type ==7 && $file_type ==0 && $csvfile =="")
		//$this->set_notice("please upload csv file");
		else if($type==7 && mb_strlen($hdata,'utf8') > Configuration::get_instance()->read('max_headline_length'))
		$this->set_notice($this->get_message("check headline length",array('x'=>Configuration::get_instance()->read('max_headline_length'))));
		else if($type==7 && mb_strlen($callactiontext,'utf8') > Configuration::get_instance()->read('max_call_action_length'))
		$this->set_notice($this->get_message("check call action length",array('x'=>Configuration::get_instance()->read('max_call_action_length'))));
		else
		{
			$mimetype = "";
			$filename = "";

			$expandable_filename="";
			$expbannerwidth = 0;
			$expbannerheight = 0;

			$videoextensionname="";
			$videomaxsize=0;
			$videomaxduration=0;

			$duration=0;
			$duration_string="";
			$bitrate=0;
			$videowidth=0;
			$videoheight=0;
			$videosize=0;

			$skin_erro_flag=0;
			$image_upload_flag=0;
			$noimage_flag=0;

			$aspect_ratio_id=0;
			$aspect_ratio_value=0;
			$aspect_ratio_array=array();

			if($type ==2 || $type ==7 || $type ==11)
			{
				if($type==2 || $type==11)
				{
					$res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bannersize));
					$result11=$res11->fetch_assoc();
					$height1=$result11['height'];
					$width1=$result11['width'];
					$filesize=$result11['filesize'];
					$html5_filesize=$result11['html5_filesize'];

					if($type ==2)
					{
						$vast_video_support=intval($result11['vast_video_support']);
						$expandable_support=intval($result11['expandable_support']);
						$expandable_height=$result11['expandable_height'];
						$expandable_width=$result11['expandable_width'];

						if($expandable_enabled ==1 && $expandable_support ==1 && $expandable ==1 && $expandable_width >0 && $expandable_height >0)
						$expandable=1;
						else
						$expandable=0;

						if($vast_video_support ==0)
						$vast_support=0;
					}


					if($_FILES['banner']['name'] != "")
					{
						$bannerclass		= new getID3;
						$bannerUploadedData	= $bannerclass->analyze($_FILES['banner']["tmp_name"]);
						$mimetype			= $bannerUploadedData['mime_type'];
					}



					$extension=explode(".",$_FILES['banner']['name']);
					$extensionname=strtolower($extension[count($extension)-1]);

					$filename=$time.'.'.$extensionname;

					$bannerinfo = getimagesize($_FILES["banner"]["tmp_name"]);
					$bannerwidth = $bannerinfo[0];
					$bannerheight = $bannerinfo[1];


					if($type ==2 && $expandable ==1)
					{
						$expandable_filename=$_FILES["expandable_banner_".$bannersize]["name"];


						if($expandable_filename !="")
						{
							$expandable_extension=explode(".",$expandable_filename);
							$expandable_extensionname=strtolower($expandable_extension[count($expandable_extension)-1]);

							$expandable_filename=$time.'.'.$expandable_extensionname;

							//////////
							$expbannerinfo = getimagesize($_FILES["expandable_banner_".$bannersize]["tmp_name"]);
							$expbannerwidth = $expbannerinfo[0];
							$expbannerheight = $expbannerinfo[1];
							//////////
						}
					}
				}


				if($type ==7)
				{
					$res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bannersize));
					$result11=$res11->fetch_assoc();
					$filesize=$result11['filesize'];


					$mimetype="";
					$filename="";
					$csvextensionname="";

					$logoextension=explode(".",$_FILES['ecommercelogo']['name']);
					$logoextensionname=strtolower($logoextension[count($logoextension)-1]);

					//if($file_type ==0)
					//{
					//	$csvextension=explode(".",$_FILES['csvfile']['name']);
					//	$csvextensionname=strtolower($csvextension[count($csvextension)-1]);
					//}

					if($ecommercelogo !="")
					$ecommercelogo=$time.'.'.$logoextensionname;
				}
			}
			else if($type ==13)
			{
				$videofile=$_FILES['videofile']['name'];
				$videofile_temp_name=$_FILES['videofile']["tmp_name"];


				if($videofile !="" && $videofile_temp_name !="")
				{
					$videoextension=explode(".",$videofile);
					$videoextensionname=strtolower($videoextension[count($videoextension)-1]);

					$videofile=$time.'.'.$videoextensionname;

					$videomaxsize=Configuration::get_instance()->read('video_file_max_size');
					$videomaxduration=Configuration::get_instance()->read('video_file_max_duration');

					$videoclass= new getID3;
					$videodata=$videoclass->analyze($videofile_temp_name);


					$duration=ceil($videodata['playtime_seconds']);
					$duration_string=gmdate("H:i:s",$duration);
					$bitrate=$videodata['bitrate'];

					//$mimetype=$videodata['mime_type'];

					$mimetype="";

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

					$aspect_ratio_id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."aspect_ratio WHERE aspect_float_value=?",array($aspect_ratio_value));
				}
			}
			else if($type ==14)
			{
				$existing_dimensions=explode(',',$this->read_post_param('existing_dimensions'));

				foreach($existing_dimensions as $key => $value)
				{
					if($_FILES["skin_banner_".$value]["name"] == "")
					{
						$noimage_flag=1;
						break;
					}
				}

				if($noimage_flag ==0)
				{
	                  $res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =4 order by width ASC");

	                  $filename ='';
                      $filenames=array();

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
				}
			}
			else if($type == 18)
			{
				if($_FILES['notify_icon_image']['name'] != "")
					{
						$bannerclass		= new getID3;
						$bannerUploadedData	= $bannerclass->analyze($_FILES['notify_icon_image']["tmp_name"]);
						$mimetype			= $bannerUploadedData['mime_type'];
						$filename=$_FILES['notify_icon_image']['name'];
						 $extension=explode(".",$filename);

                            $extensionname=$extension[1];


					}
		    }

			$additionalBannerUploadArray  = array();
			$additionalBannerIssueFlag    = 0;
			$additionalBannerIssueMessage = 0;
			$responsive_enabled           = $this->get_addon_status('responsive-ads_enabled');


			if($responsive_enabled == 1 && $adpricing == 3 && ($type==2 || $type==11) && $additional_banners != "")
			{
				$additionalBannerArray = explode(',',$additional_banners);

				foreach($additionalBannerArray as $adKey=>$adValue)
				{

					if($_FILES['banner_responsive_'.$adValue]['name'] != "")
					{
						$responsiveExtension     = explode(".",$_FILES['banner_responsive_'.$adValue]['name']);
						$responsiveExtensionName = strtolower($responsiveExtension[count($responsiveExtension)-1]);

						if($responsiveExtensionName != "gif" && $responsiveExtensionName != "jpeg" && $responsiveExtensionName != "pjpeg" && $responsiveExtensionName != "png" && $responsiveExtensionName != "svg" && $responsiveExtensionName != "jpg")
						{
							$dimensionValue = str_replace("-", " x ",$this->get_banner_dimension($adValue));

							$additionalBannerIssueFlag    = 1;
							$additionalBannerIssueMessage = $this->get_message("uploaded banner extension not supported in responsive banner",array("x"=>$dimensionValue));
						}
						else if($_FILES['banner_responsive_'.$adValue]["size"]/1024 > $filesize)
						{
							$dimensionValue = str_replace("-", " x ",$this->get_banner_dimension($adValue));

							$additionalBannerIssueFlag    = 2;
							$additionalBannerIssueMessage = $this->get_message("uploaded banner size is high in responsive banner",array("x"=>$dimensionValue));
						}
						else if($_FILES['banner_responsive_'.$adValue]["error"] > 0)
						{
							$dimensionValue = str_replace("-", " x ",$this->get_banner_dimension($adValue));

							$additionalBannerIssueFlag    = 3;
							$additionalBannerIssueMessage = $this->get_message("uploaded banner is corrupted in responsive banner",array("x"=>$dimensionValue));
						}

						if($additionalBannerIssueFlag > 0)
						break;
						else
						{
							$dimensionValue  = $this->get_banner_dimension($adValue);
							$dimensionArray  = explode("-",$dimensionValue);

							$dimensionWidth  = 0;
							$dimensionHeight = 0;

							if(isset($dimensionArray[0]))
							$dimensionWidth  = $dimensionArray[0];

							if(isset($dimensionArray[1]))
							$dimensionHeight = $dimensionArray[1];


							$additionalBannerUploadArray[$adValue][0] = $adValue.'-'.$time.'.'.$responsiveExtensionName;  //File name
							$additionalBannerUploadArray[$adValue][1] = 'banner_responsive_'.$adValue;//File upload field name

							$additionalBannerUploadArray[$adValue][2] = $dimensionWidth;
							$additionalBannerUploadArray[$adValue][3] = $dimensionHeight;
						}
					}
				}

				if($additionalBannerIssueFlag == 0) //For default banner
				{
					$additionalBannerUploadArray[$bannersize][0] = $bannersize.'-'.$time.'.'.$extensionname;  //File name
					$additionalBannerUploadArray[$bannersize][1] = 'banner';//File upload field name

					$additionalBannerUploadArray[$bannersize][2] = $width1;
					$additionalBannerUploadArray[$bannersize][3] = $height1;
				}
			}


			if($additionalBannerIssueFlag > 0)
			$this->set_notice($additionalBannerIssueMessage);
			else if(($type==2 || $type==11) && $banner_type == 0 && ($extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg"))
			$this->set_notice("uploaded banner extension not supported");
			else if($type == 18 && ($extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg"))
			$this->set_notice("notification icon image extension not supported");
			else if(($type==2 || $type==11) && $banner_type == 0 && $_FILES["banner"]["size"]/1024 > $filesize)
			$this->set_notice("uploaded banner size is high");
			else if(($type==2 || $type==11) && $banner_type == 0 && $_FILES["banner"]["error"] > 0)
			$this->set_notice("uploaded banner is corrupted");

			else if($type==2 && $banner_type == 0 && $expandable ==1 && $expandable_extensionname != "gif" && $expandable_extensionname != "jpeg" && $expandable_extensionname != "pjpeg" && $expandable_extensionname != "png" && $expandable_extensionname != "svg" && $expandable_extensionname != "jpg")
			$this->set_notice("uploaded expandable banner extension not supported");
			else if($type==2 && $banner_type == 0 && $expandable ==1 && $_FILES["expandable_banner_".$bannersize]["size"]/1024 > $filesize)
			$this->set_notice("uploaded expandable banner size is high");
			else if($type==2 && $banner_type == 0 && $expandable ==1 && $_FILES["expandable_banner_".$bannersize]["error"] > 0)
			$this->set_notice("uploaded expandable banner is corrupted");

			else if($type==2 && $banner_type == 1 && $extensionname != "zip")
			$this->set_notice("html5 file format not supported");
			else if($type==2 && $banner_type == 1 && $_FILES["banner"]["size"]/1024 > $html5_filesize)
			$this->set_notice("html5 file size not supported");
			else if($type==2 && $banner_type == 1 && $_FILES["banner"]["error"] > 0)
			$this->set_notice("html5 file upload failed");

			else if($type==2 && $banner_type == 1 && $expandable ==1 && $expandable_extensionname != "zip")
			$this->set_notice("expandable html5 file format not supported");
			else if($type==2 && $banner_type == 1 && $expandable ==1 && $_FILES["expandable_banner_".$bannersize]["size"]/1024 > $html5_filesize)
			$this->set_notice("expandable html5 file size not supported");
			else if($type==2 && $banner_type == 1 && $expandable ==1 && $_FILES["expandable_banner_".$bannersize]["error"] > 0)
			$this->set_notice("expandable html5 file upload failed");

			else if($type == 7 && $logoextensionname != "gif" && $logoextensionname != "jpeg" && $logoextensionname != "pjpeg" && $logoextensionname != "png" && $logoextensionname != "svg" && $logoextensionname != "jpg")
			$this->set_notice("uploaded logo image extension not supported");
			else if($type == 7 && $_FILES["ecommercelogo"]["size"]/1024 > $filesize)
			$this->set_notice("uploaded logo image size is high");
			else if($type == 7 && $_FILES["ecommercelogo"]["error"] > 0)
			$this->set_notice("uploaded logo image is corrupted");
			//else if($type == 7 && $file_type ==0 && $csvextensionname != "csv")
			//$this->set_notice("uploaded file format not supported");
			//else if($type == 7 && $file_type ==0 && $_FILES["csvfile"]["error"] > 0)
			//$this->set_notice("csv file upload failed");
			else if($type ==7 && $file_type ==1 && $ecommerce_flag ==0)
			$this->set_notice("please fill ecommerce popup");

			else if($type ==13 && $linear_support ==1 && $html5_player_support ==1 && $videoextensionname != "mp4" && $videoextensionname != "flv" && $videoextensionname != "wmv" && $videoextensionname != "mpg" && $videoextensionname != "avi" && $videoextensionname != "webm" && $videoextensionname != "ogg")
			$this->set_notice("uploaded video file extension not supported");
			else if($type ==13 && $linear_support ==1 && $videoextensionname != "mp4" && $videoextensionname != "flv" && $videoextensionname != "wmv" && $videoextensionname != "mpg" && $videoextensionname != "avi")
			$this->set_notice("uploaded video file extension not supported");
			else if($type ==13 && $html5_player_support ==1 && $videoextensionname != "mp4" && $videoextensionname != "webm" && $videoextensionname != "ogg")
			$this->set_notice("uploaded video file extension not supported");

			else if($type ==13 && $videofile_temp_name =="" || $videosize > $videomaxsize)
			$this->set_notice("uploaded video file size is high");
			else if($type ==13 && !in_array($aspect_ratio_value,$aspect_ratio_array))
			$this->set_notice("video aspect ratio not supported");
			else if($type ==13 && $duration > $videomaxduration)
			$this->set_notice("video duration high");

			else if($type ==14 && $noimage_flag ==1)
			$this->set_notice("please upload skin banners for all dimensions");
			else if($type ==14 && $skin_erro_flag ==1)
			$this->set_notice("uploaded banner extension not supported");
            else if($type ==14 && $skin_erro_flag ==2)
            $this->set_notice("uploaded banner size is high");
			else if($type ==14 && $skin_erro_flag==3)
            $this->set_notice("uploaded banner is corrupted");
			else
			{
				$append_string0="";
				$append_string1="";
				$append_string2=array();

				$ads_string0="";
				$ads_string1="";
				$ads_string2=array();


				$append_string2[]=$uid;
				$append_string2[]=$name;
				$append_string2[]=$type;
				$append_string2[]=$title;
				$append_string2[]=$desc;
				$append_string2[]=$displayurl;
				$append_string2[]=$cta_button_text;
				$append_string2[]=$clickurl;

				if($type ==13)
				$append_string2[]=$videofile;
				else
				$append_string2[]=$filename;

				$append_string2[]=$bannersize;
				$append_string2[]=$adpricing;
				$append_string2[]=$adstatus;
				$append_string2[]=0;
				$append_string2[]=$time;
				$append_string2[]=$adv_status;
				$append_string2[]=-1;
				$append_string2[]=$ad_start_time;

				$ads_string2[] 	= $uid;
				$ads_string2[] 	= $type;
				$ads_string2[] 	= $bannersize;
				$ads_string2[] 	= $adpricing;
				$ads_string2[] 	= $adv_status;

				if($refferal_status ==1)
				{
					$append_string0.=",refferal_id,refferal_status";
					$ads_string0.=",refferal_id,refferal_status";

					$append_string1.=",?,?";

					$ads_string1.=",?,b'?'";

					$append_string2[]=$ref_id;
					$append_string2[]=$refferal_status;

					$ads_string2[] = $ref_id;
				    $ads_string2[] = $refferal_status;
				}

				if($type ==1 || $type ==2)
				{
					$append_string0.=",video_player_support,mime_type";
					$ads_string0.=",video_player_support";

					$append_string1.=",?,?";
					$ads_string1.=",b'?'";

					$append_string2[]=$vast_support;
					$append_string2[]=$mimetype;

					$ads_string2[] = $vast_support;

				}
				if($type ==13)
				{
					$append_string0.=",video_width,video_height,mime_type,bitrate,duration,duration_seconds,aspect_ratio";
					$ads_string0.=",video_width,video_height,bitrate,duration_seconds,aspect_ratio";

					$append_string1.=",?,?,?,?,?,?,?";
					$ads_string1.=",?,?,?,?,?";

					$append_string2[]=$videowidth;
					$append_string2[]=$videoheight;
					$append_string2[]=$mimetype;
					$append_string2[]=$bitrate;
					$append_string2[]=$duration_string;
					$append_string2[]=$duration;
					$append_string2[]=$aspect_ratio_id;

					$ads_string2[]=$videowidth;
					$ads_string2[]=$videoheight;
                    $ads_string2[]=$bitrate;
                    $ads_string2[]=$duration;
					$ads_string2[]=$aspect_ratio_id;
				}

				if($adpricing == 6 || $type ==12)
				{
					$append_string0.=",tracking_last_checked";
					$ads_string0.=",tracking_last_checked";

					$append_string1.=",?";
					$ads_string1.=",?";

					$append_string2[]=mktime(0,0,0,date("m",$time),date("d",$time)-1,date("y",$time));
					$ads_string2[]=mktime(0,0,0,date("m",$time),date("d",$time)-1,date("y",$time));
				}

				$res = $db->execute_query("INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,title,description,display_url,cta_button_text,click_url,banner,banner_id,display_type,status,pause_status,updation_time,user_status,pricing_status,start_date".$append_string0.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$append_string1.")",$append_string2);

				if($res->error == "")
				{
					$aid        = $res->get_last_id();
					array_unshift($ads_string2,$aid);

					$device_enabled      = $this->get_addon_status('device-targeting_enabled');
					$language_enabled    = $this->get_addon_status('language-targeting_enabled');
					$category_enabled    = $this->get_addon_status('category-targeting_enabled');
					$connection_enabled  = $this->get_addon_status('connectiontype-targeting_enabled');

				  if($cloneFlag == 1)
				  {
							$parentAdData = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id = ?",array($parentAdID));
							$parentAdRow  = $parentAdData->fetch_assoc();

						  	$timeTargetEnabled      = $this->get_addon_status('time-targeting_enabled');
							$cityTargetingEnabled   = $this->get_addon_status('city-targeting_enabled');
							$ispTargetingEnabled    = $this->get_addon_status('isp-connection-targeting_enabled');

							//Keyword Targeting
							$keywordResult = $db->execute_query("select * from ".TABLE_PREFIX."ad_keyword_mapping where aid = ?",array($parentAdID));

							while($keywordData = $keywordResult->fetch_assoc())
							{
									$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid, aid, kid, click_value, time) VALUES (?, ?, ?, ?, ?)",array($uid,$aid,$keywordData["kid"],$keywordData["click_value"],time()));
							}
							//Keyword Targeting

							//Geo Targeting
							$geoResult = $db->execute_query("select * from ".TABLE_PREFIX."ad_geographic_mapping where aid=?",array($parentAdID));

							while($geoData = $geoResult->fetch_assoc())
							{
									if($cityTargetingEnabled == 1 || $cityTargetingEnabled == 0)
									$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_geographic_mapping (uid,aid,country_code,subdivision1,subdivision2,city) VALUES (?,?,?,?,?,?)",array($uid,$aid,$geoData["country_code"],$geoData["subdivision1"],$geoData["subdivision2"],$geoData["city"]));
									else
							 		$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_geographic_mapping (uid,aid,country_code) VALUES (?,?,?)",array($uid,$aid,$geoData["country_code"]));
							}
							//Geo Targeting

							//Category Targeting
							if($category_enabled == 1)
							{
									$categoryResult = $db->execute_query("select * from ".TABLE_PREFIX."ad_category_mapping where aid = ?",array($parentAdID));

									while($categoryData = $categoryResult->fetch_assoc())
									{
											$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_category_mapping (uid,aid,catid,click_value,time) VALUES (?,?,?,?,?)",array($uid,$aid,$categoryData["catid"],$categoryData["click_value"],time()));
									}
							}
							//Category Targeting

							//Language Targeting
							if($language_enabled == 1)
							{
									$languageResult = $db->execute_query("select * from ".TABLE_PREFIX."ad_language_mapping where aid = ?",array($parentAdID));

									while($languageData = $languageResult->fetch_assoc())
									{
											$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_language_mapping (uid,aid,language_id) VALUES (?,?,?)",array($uid,$aid,$languageData["language_id"]));
									}
							}
							//Language Targeting

							//Device Targeting
							if($device_enabled == 1 || $device_enabled == 0)
							{
									$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET device = ? WHERE uid = ? AND id = ?", array($parentAdRow['device'],$uid,$aid));

									//OS Targeting
									$osResult = $db->execute_query("select * from ".TABLE_PREFIX."ad_os_mapping where aid = ?",array($parentAdID));

									while($osData = $osResult->fetch_assoc())
									{
											$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_os_mapping (uid, aid, osid) VALUES (?, ?, ?)",array($uid,$aid,$osData["osid"]));
									}
								  //OS Targeting

									//Browser Targeting
									$browserResult    = $db->execute_query("select * from ".TABLE_PREFIX."ad_browser_mapping where aid = ?",array($parentAdID));

									while($browserData = $browserResult->fetch_assoc())
									{
											$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_browser_mapping (uid, aid, browserid) VALUES (?, ?, ?)",array($uid,$aid,$browserData["browserid"]));
									}
								  //Browser Targeting

						  }
							//Device Targeting

							//ISP & Connection Targeting
							if($ispTargetingEnabled == 1 || $ispTargetingEnabled == 0)
							{
									//ISP Targeting
									$ispResult = $db->execute_query("select * from ".TABLE_PREFIX."ad_isp_mapping where aid = ?",array($parentAdID));

									while($ispData = $ispResult->fetch_assoc())
									{
											$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_isp_mapping (uid, aid, country, ispid) VALUES (?, ?, ?, ?)",array($uid,$aid,$ispData["country"],$ispData["ispid"]));
									}
									//ISP Targeting


									//Connection Targeting
									$connectionResult = $db->execute_query("select * from ".TABLE_PREFIX."ad_connection_mapping where aid = ?",array($parentAdID));

									while($connectionData = $connectionResult->fetch_assoc())
									{
											$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_connection_mapping (uid, aid, conn_id) VALUES (?, ?, ?)",array($uid,$aid,$connectionData["conn_id"]));
									}
									//Connection Targeting
							}
							//ISP & Connection Targeting

							//Time Targeting
							if($timeTargetEnabled == 1 || $timeTargetEnabled == 0)
							{
									$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET
											date_filter = ?, time_filter = ?, day_filter = ?,
											date_period = ?,
											time_period1 = ?, time_period2 = ?,	time_period3 = ?,	time_period4 = ?,	time_period5 = ?,
											day_period1 = ?, day_period2 = ?,	day_period3 = ?, day_period4 = ?, day_period5 = ?, day_period6 = ?, day_period7 = ?,
											tmt_start_date = ?,	tmt_end_date = ?,
											tmt_start_time = ?,	tmt_end_time = ?,
											tmt_start_day = ?, tmt_end_day = ?
											WHERE uid = ? AND id = ?",
											array(
												$parentAdRow['date_filter'],$parentAdRow['time_filter'],$parentAdRow['day_filter'],
												$parentAdRow['date_period'],
												$parentAdRow['time_period1'],$parentAdRow['time_period2'],$parentAdRow['time_period3'],$parentAdRow['time_period4'],$parentAdRow['time_period5'],
												$parentAdRow['day_period1'],$parentAdRow['day_period2'],$parentAdRow['day_period3'],$parentAdRow['day_period4'],$parentAdRow['day_period5'],$parentAdRow['day_period6'],$parentAdRow['day_period7'],
												$parentAdRow['tmt_start_date'],$parentAdRow['tmt_end_date'],
												$parentAdRow['tmt_start_time'],$parentAdRow['tmt_end_time'],
												$parentAdRow['tmt_start_day'],$parentAdRow['tmt_end_day'],
												$uid,$aid));
							}
							//Time Targeting

							//Ads cache data updation

							$parentAdCacheData = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads_cache WHERE id = ?",array($parentAdID));
							$parentAdRow  = $parentAdCacheData->fetch_assoc();

							$columnNameString  = "";

							foreach($parentAdRow as $parentKey => $parentValue)
							{
								if($parentKey != "id" && $parentKey != "banner_id" && $parentKey != "default_rate" && $parentKey != "status" && $parentKey != "pause_status" && $parentKey != "pricing_status" && $parentKey != "budget_available" && $parentKey != "daily_budget_available" &&
								$parentKey != "video_player_support" && $parentKey != "video_width" && $parentKey != "video_height" && $parentKey != "aspect_ratio" && $parentKey != "bitrate" &&
								$parentKey != "duration_seconds" && $parentKey != "expandable" &&
								$parentKey != "html_default" && $parentKey != "retargeting" && $parentKey != "html5" && $parentKey != "cta_support" &&
								$parentKey != "cta_expandable_support" && $parentKey != "upload_success" && $parentKey != "exp_upload_success" && 
								$parentKey != "feed_text" && $parentKey != "feed_banner" && $parentKey != "feed_textimage" && $parentKey != "feed_pop" && $parentKey != "feed_directlink" && $parentKey != "result_cacheable" && $parentKey != "response_type" && 
								$parentKey != "dsp_rate_from" && $parentKey != "dsp_auction_type" && $parentKey != "dsp_interstitial" && $parentKey != "dsp_banner" && $parentKey != "dsp_native" && $parentKey != "dsp_pop" && $parentKey != "dsp_directlink" && $parentKey != "dsp_video_linear" && $parentKey != "dsp_inpage_push" &&  $parentKey != "dsp_exchange_id" &&   
								$parentKey != "daily_budget" && $parentKey != "daily_budget_used" && $parentKey != "total_ad_budget" &&
								$parentKey != "total_budget_used")
								{
										if($columnNameString != "")
										$columnNameString.= ",";

										$columnNameString.= $parentKey;
								}
							}

							if($columnNameString != "")
							$columnNameString = ",".$columnNameString;

							$firstColumn = "id,banner_id";

							$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_cache (".$firstColumn.$columnNameString.") SELECT ".$aid.",".$bannersize.$columnNameString." FROM ".TABLE_PREFIX."ads_cache WHERE id = ?",array($parentAdID));

							if($type == 1 || $type == 2)
							$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET video_player_support = b'?' WHERE id = ?",array($vast_support,$aid));

							if($type == 13)
							$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET video_width = ?,video_height = ?,bitrate = ?,duration_seconds = ?,aspect_ratio = ? WHERE id = ?",array($videowidth,$videoheight,$bitrate,$duration,$aspect_ratio_id,$aid));


							/************* For Pricing Updation ***************/
							if($adpricing != 3)
							{
									$adCloneErrorFlag = 0;
									$rDefaultRate   	= $parentAdRow['default_rate'];
									$rBudget        	= $parentAdRow['total_ad_budget'];
									$rDailyBudget   	= $parentAdRow['daily_budget'];
									$rPricingStatus  	= $parentAdRow['pricing_status'];

									$advAccountBalance  = $db->read_single_column("SELECT adv_account_balance FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));

									if($advAccountBalance >= $rBudget && $rDefaultRate > 0 && $rPricingStatus == 1)
									{
											 $db->execute_query("BEGIN");

											 $accountBalanceUpdate = $db->execute_query("UPDATE ".TABLE_PREFIX."users SET adv_account_balance = adv_account_balance - ? WHERE id=?",array($rBudget,$uid));

											 if($accountBalanceUpdate->error == "")
											 {
														$adUpdateString  = "";
														$adUpdateArray   = array();
														$adDefaultStatus = Configuration::get_instance()->read('default_ad_status');

														$adUpdateString.= " default_rate = ?, total_ad_budget = ?, daily_budget = ?, status = ?, pricing_status = ?, start_date = ? ";

														$adUpdateArray[] = $rDefaultRate;
														$adUpdateArray[] = $rBudget;
														$adUpdateArray[] = $rDailyBudget;
														$adUpdateArray[] = $adDefaultStatus;
														$adUpdateArray[] = 1;
														$adUpdateArray[] = time();
														$adUpdateArray[] = $aid;
														$adUpdateArray[] = $uid;

														$budgetUpdate    = $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET ".$adUpdateString." WHERE id = ? AND uid = ?",$adUpdateArray);

														if($budgetUpdate->error == "")
														{
																if($adDefaultStatus <= 0)
																$adDefaultStatusCache = 0;
																else
																$adDefaultStatusCache = 1;

																$budgetAdCacheUpdate = $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET default_rate = ?, daily_budget = ?, total_ad_budget = ?, status = b'?', pricing_status = b'?', budget_available = b'?', daily_budget_available = b'?' WHERE id = ?",array($rDefaultRate, $rDailyBudget, $rBudget, $adDefaultStatusCache, 1, 1, 1, $aid));

																if($budgetAdCacheUpdate->error != "")
																$adCloneErrorFlag = 1;
														}
														else
														$adCloneErrorFlag = 1;
											 }
											 else
											 $adCloneErrorFlag = 1;


											 if($adCloneErrorFlag == 0)
											 $db->execute_query("COMMIT");
											 else
											 $db->execute_query("ROLLBACK");
									}
							}
							/************* For Pricing Updation ***************/
				}
				else
				{
					    $res_ads = $db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_cache (id,uid,type,banner_id,display_type,user_status".$ads_string0.") values (?,?,?,?,?,?".$ads_string1.")",$ads_string2);

					    if($device_enabled ==1 || $device_enabled ==0)
			            $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_os=1,all_browser=1 WHERE id=?",array($aid));

					    if($language_enabled ==1 || $language_enabled ==0)
					    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_languages=1 WHERE id=?",array($aid));

					    if($category_enabled ==1 || $category_enabled ==0)
					    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=1 WHERE id=?",array($aid));

					    if($connection_enabled ==1 || $connection_enabled ==0)
					    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_connections=1 WHERE id=?",array($aid));

						if($adpricing != 3)
						{
							$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_geographic_mapping (uid,aid,country_code) values (?,?,?)",array($uid,$aid,0));
							$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_countries=b'?' WHERE id=? AND uid=?",array(1,$aid,$uid));

							if($type != 12)
							$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) VALUES (?,?,?)",array($uid,$aid,0));
						}
					}

					if($adpricing != 3)
					{
							if($retargeting_enabled ==1 && ($adpricing == 0 || $adpricing == 6) && $type != 12)
							{
									$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET retargeting = ? WHERE id = ?",array($retargeting,$aid));
									$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET retargeting = b'?' WHERE id = ?",array($retargeting,$aid));

									if($cloneFlag == 1 && $retargeting == 1)
									{
											$rootAdRetarget = intval($db->read_single_column("SELECT retargeting FROM ".TABLE_PREFIX."ads WHERE id = ?",array($parentAdID)));

											if($rootAdRetarget == 1)
											{
													$rClickurl       = str_ireplace("http://","",$clickurl);
													$rClickurl       = str_ireplace("https://","",$rClickurl);
													$rClickurl       = str_ireplace("www.","",$rClickurl);
													$rClickurlLength = strlen($rClickurl);

													$rString         = "";
													for($i=0;$i < $rClickurlLength;$i++)
													{
															if($rClickurl[$i] == '.' || ctype_alnum($rClickurl[$i]))
															$rString.= $rClickurl[$i];
															else
															break;
													}

													$rClickurl    = $rString;

													$retargetList = $db->execute_query("SELECT l.* FROM ".TABLE_PREFIX."retargeting_sites s INNER JOIN ".TABLE_PREFIX."retargeting_lists l ON s.id = l.sid WHERE s.uid = ? AND s.url = ? AND s.status = 1 AND l.type = 0",array($uid,$rClickurl));

													while($retargetListRow = $retargetList->fetch_assoc())
													{
															$parentRetargeted = intval($db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE uid = ? AND aid = ? AND rid = ? AND rsid = ?",array($uid,$parentAdID,$retargetListRow['id'],$retargetListRow['sid'])));

															if($parentRetargeted > 0)
															$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_retargeting_mapping (uid,aid,rid,rsid) VALUES (?,?,?,?)",array($uid,$aid,$retargetListRow['id'],$retargetListRow['sid']));
													}
											}
									}
							}
					}

					if($type ==2 || $type ==7 || $type ==11 || $type ==12 ||  $type ==13 || $type ==14)
					{
						if(!is_dir(DATA_DIR))
						mkdir(DATA_DIR,0777);
					}

					if($adpricing == 3 && $positionID > 0)
					{
						if($cpdPackage == 0)
						{
							$cpd_package_days = Configuration::get_instance()->read('cpd_package_days');
    						$cpd_package_days_array  = explode(",",$cpd_package_days);

							$cpdPackage              = intval($cpd_package_days_array[0]);
						}



					    $cpd_rate_including_admin_profit = Configuration::get_instance()->read('cpd_rate_including_admin_profit');


					    if($sitePid == 0 || $cpd_rate_including_admin_profit == 1)
					    {
					      $calculatedRate          = $cpdRate;
					      $day_cpd_rate_publisher  = ($cpdRate * $cpdProfitPercentage)/100;
					    }
					    else
					    {
					      $calculatedRate          = ($cpdRate * 100)/$cpdProfitPercentage;
					      $day_cpd_rate_publisher  = $cpdRate;

					    }


						$day_cpd_rate_total      = $calculatedRate;
						$cpd_rate_total          = $cpdPackage * $calculatedRate;
						$cpd_rate_publisher      = $cpdPackage * $day_cpd_rate_publisher;


						$db->execute_query("INSERT INTO ".TABLE_PREFIX."sponsored_ad_mapping (uid,aid,publisher,site,position,status,position_days,create_time,update_time,day_cpd_rate_total,day_cpd_rate_publisher,cpd_rate_total,cpd_rate_publisher) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",array($uid,$aid,$sitePid,$siteID,$positionID,-1,$cpdPackage,$time,$time,$day_cpd_rate_total,$day_cpd_rate_publisher,$cpd_rate_total,$cpd_rate_publisher));



			$sitename     			= CategoryHelper::get_site_name($siteID);
			$positionname 			= SponsoredHelper::get_position_data($positionID,1);
			$packagename  			= $this->get_label("package days rate label",array("x"=>$cpdPackage,"y"=>$this->get_money_format($cpd_rate_total)));
			$packagenamePublisher   = $this->get_label("package days rate label",array("x"=>$cpdPackage,"y"=>$this->get_money_format($cpd_rate_publisher)));
			$advsite      			= $clickurl;
			$adname       			= $this->get_ad_name($aid);

			$advsiteadmin 			= '<a href="'.$clickurl.'">'.$clickurl.'</a>';
			$advadadmin   			= '<a href="'.$this->make_base_url("ad/view/".$aid,ADMIN_DIR).'">'.$adname.'</a>';


						if($sitePid >0)
						{
							$managemappings = $this->make_url("dispatch/sponsored/17/".$positionID,BASE);

							$userdata       = $db->execute_query("select username,email,locale from ".TABLE_PREFIX."users where id=?",array($sitePid));
							$userdata1      = $userdata->fetch_assoc();
							$email          = $userdata1['email'];


							$res1    = $db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=19");
							$result1 = $res1->fetch_assoc();

							$language_enabled = Configuration::get_instance()->read('language_enabled');
							$admarketName     = Configuration::get_instance()->read('admarket_name');


							if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_message']) && $result1[$userdata1['locale'].'_message'] !='')
							$message=$result1[$userdata1['locale'].'_message'];
							else
							$message=$result1['message'];

							if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_subject']) && $result1[$userdata1['locale'].'_subject'] !='')
							$subject=$result1[$userdata1['locale'].'_subject'];
							else
							$subject=$result1['subject'];



							$subject=str_replace("{ADMARKETNAME}",$admarketName,$subject);
							$message=str_replace("{ADMARKETNAME}",$admarketName,$message);
							$message=str_replace("{USERNAME}",$userdata1['username'],$message);



							$message=str_replace("{SITE}",$sitename,$message);
							$message=str_replace("{POSITION}",$positionname,$message);
							$message=str_replace("{PACKAGE}",$packagenamePublisher,$message);
							$message=str_replace("{MANAGEMAPPINGS}",$managemappings,$message);
							$message=str_replace("{ADURL}",$advsite,$message);
							$message=str_replace("{ADNAME}",$adname,$message);

							UtilityHelper::send_mail($email,$subject,nl2br($message));
					}
					else
					{

						$managemappings = '<a target="_blank" href="'.$this->make_base_url("dispatch/sponsored/13/".$positionID,ADMIN_DIR).'">'.$this->get_label('here').'</a>';

					$message ='
                    Hello,

You have a new CPD ad targeting request for below position

Advertiser Name	 : <a target="_blank" href="'.$this->make_base_url("user/profile/".$uid,ADMIN_DIR).'">'.$this->get_user_name($uid).'</a>
Site	    	 : '.$sitename.'
Position	     : '.$positionname.'
Package          : '.$packagename.'
Ad Name          : '.$advadadmin.'
Advertiser Site  : '.$advsiteadmin.'

You can manage the request '.$managemappings.'

Thanks again for using '.Configuration::get_instance()->read('admarket_name').'

Best Regards,
'.$admarketName.'
';

                    $admin_email=Configuration::get_instance()->read('admin_notification_email');

                   	UtilityHelper::send_mail($admin_email,$admarketName." - CPD Ad Targeting Request",nl2br($message));
					}
				}

					if($type ==12 && $cloneFlag == 0)
					{
				        $ivalue=0;
						$failedcount=0;

						/************** For Top 10 Keyword Insertion ************/

						$contentdata=$title.' '.$desc;

						$toparray=$this->get_top_keywords($contentdata);

						$okstatus=0;
						$key_status=Configuration::get_instance()->read('keyword_default_status');

						foreach($toparray as $k1=>$v1)
						{
							$key=trim($k1);


							$keyid=$db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=?",array($key));

							if($keyid =="" && $key !="")
							{
								$res1234=$db->execute_query("INSERT INTO ".TABLE_PREFIX."keywords (keyword,create_time,status) values (?,?,?)",array($key,$time,$key_status));

								$kid=$res1234->get_last_id();
								$okstatus=$key_status;
							}
							else if($keyid >0)
							{
								$row=$db->execute_query("select * from ".TABLE_PREFIX."keywords where keyword=?",array($key));
								$rowdata=$row->fetch_assoc();


								$kid=$rowdata['id'];
								$okstatus=$rowdata['status'];
							}

							$id1=$db->read_single_column("select id from ".TABLE_PREFIX."ad_keyword_mapping where kid=? and aid=?",array($kid,$aid));


							if($id1 =="" && $okstatus !=0)
							$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) values (?,?,?)",array($uid,$aid,$kid));
						}
						/************** For Top 10 Keyword Insertion ************/

						while($dimdata=$res1->fetch_assoc())
						{
							$filesize=$dimdata['filesize'];
							$height1=$dimdata['height'];
							$width1=$dimdata['width'];
							$banid=$dimdata['id'];


							if($_FILES["banner_".$banid]['name'] !='')
							{
								$extension=explode(".",$_FILES["banner_".$banid]['name']);
								$extensionname=strtolower($extension[count($extension)-1]);

								if(($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg") && ($_FILES["banner_".$banid]["size"]/1024 <= $filesize))
								{
									if($_FILES["banner_".$banid]["error"] == 0)
									{
										if($ivalue ==0)
										{
											if(!is_dir(DATA_DIR.'/banners/'))
											mkdir(DATA_DIR.'/banners/',0777);

											if(!is_dir(DATA_DIR.'/banners/'.$aid))
											mkdir(DATA_DIR.'/banners/'.$aid,0777);
										}


										$filename=$banid.'-banner.'.$extensionname;

										$res3=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_image_mapping (uid,aid,banner_size,width,height,image) values (?,?,?,?,?,?)",array($uid,$aid,$banid,$width1,$height1,$filename));

										if($res3->error =="")
										{
											if(move_uploaded_file($_FILES["banner_".$banid]["tmp_name"],DATA_DIR.'/banners/'.$aid.'/'.$banid.'-banner.'.$extensionname))
											{
												$image=new ImageHelper(DATA_DIR.'/banners/'.$aid.'/'.$banid.'-banner.'.$extensionname);
												$image->resize($width1,$height1,DATA_DIR.'/banners/'.$aid.'/'.$banid.'-banner.'.$extensionname);
											}
											else
											$failedcount=$failedcount+1;
										}
										else
										$failedcount=$failedcount+1;


										$ivalue=$ivalue+1;
									}
									else
									$failedcount=$failedcount+1;
								}
								else
								$failedcount=$failedcount+1;
							}
						}


						if($failedcount >0)
						$this->flash($this->get_message('some image upload failed',array('x'=>$failedcount)), $this->make_url('ad/view/'.$aid.'/1'),0);
						else
						$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
					}
					else if($type ==9 || $type ==21)
					$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
					else if($type ==13)
					{
						if(!is_dir(DATA_DIR.'/video/'))
						mkdir(DATA_DIR.'/video/',0777);

						if(!is_dir(DATA_DIR.'/video/'.$aid.'/'))
						mkdir(DATA_DIR.'/video/'.$aid.'/',0777);

						if(move_uploaded_file($videofile_temp_name,DATA_DIR.'/video/'.$aid.'/'.$videofile))
						$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
						else
						$this->flash($this->get_message('video upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
					}
					else if($type ==1)
					{
						if($adpricing == 3 && $positionID > 0)
						$this->flash($this->get_message('cpd ad targeting success'), $this->make_url('ad/view/'.$aid.'/2'));
						else
						$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
					}
					else if($type == 18 && $_FILES['notify_icon_image']['name'] !='')
					{
						if(!is_dir(DATA_DIR."/notification_icons"))
						mkdir(DATA_DIR."/notification_icons",0777);

						$notification_icon_width = Configuration::get_instance()->read('push_notification_icon_width');
						$notification_icon_height = Configuration::get_instance()->read('push_notification_icon_height');
						if(move_uploaded_file($_FILES["notify_icon_image"]["tmp_name"],DATA_DIR."/notification_icons/".$aid."_".$filename))
							{
								$image=new ImageHelper(DATA_DIR."/notification_icons/".$aid."_".$filename);
								$image->resize($notification_icon_width,$notification_icon_height,DATA_DIR."/notification_icons/".$aid."_".$filename);
								$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
							 }
							 else
							 {
							 	$this->flash($this->get_message('image upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
							 }

					}
					else if($type ==2 || $type ==11)
					{
						if($banner_type == 0)
						{
							if($adpricing == 3 && count($additionalBannerUploadArray) > 0)
							{
								if(move_uploaded_file($_FILES["banner"]["tmp_name"],DATA_DIR."/".$aid."_".$filename))
								{
									$image=new ImageHelper(DATA_DIR."/".$aid."_".$filename);
									$image->resize($width1,$height1,DATA_DIR."/".$aid."_".$filename);

									if(!is_dir(DATA_DIR."/".$aid))
									mkdir(DATA_DIR."/".$aid,0777);

									$image_upload_flag = 0;
									$bannerDataArray   = array();

									foreach($additionalBannerUploadArray as $keyData => $valueData)
									{
										$fileNameData = $valueData[0];
										$fieldName    = $valueData[1];
										$fileWidth    = $valueData[2];
										$fileHeight   = $valueData[3];

										if($fieldName == "banner")
										{
											if(copy(DATA_DIR."/".$aid."_".$filename,DATA_DIR."/".$aid."/".$fileNameData))
											{
												$bannerDataArray[$keyData] = $fileNameData;
											}
											else
											{
												$image_upload_flag=1;
											    break;
											}
										}
										else
										{
											if(move_uploaded_file($_FILES[$fieldName]["tmp_name"],DATA_DIR."/".$aid."/".$fileNameData))
											{
												$image=new ImageHelper(DATA_DIR."/".$aid."/".$fileNameData);
												$image->resize($fileWidth,$fileHeight,DATA_DIR."/".$aid."/".$fileNameData);

												$bannerDataArray[$keyData] = $fileNameData;
											}
											else
											{
												$image_upload_flag=1;
												break;
											}
										}
									}

									if($image_upload_flag == 0)
									{
										$bannerDataJSON = json_encode($bannerDataArray);

										$bannerUpdate = $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET banner_list = ? WHERE id = ?",array($bannerDataJSON,$aid));

										if($bannerUpdate->error == "")
										{
											if($adpricing == 3 && $positionID > 0)
											$this->flash($this->get_message('cpd ad targeting success'), $this->make_url('ad/view/'.$aid.'/2'));
											else
											$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
										}
									    else
										$this->flash($this->get_message('image upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);

									}
									else
									$this->flash($this->get_message('image upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
								}
								else
								$this->flash($this->get_message('image upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
							}
							else
							{
								if(move_uploaded_file($_FILES["banner"]["tmp_name"],DATA_DIR."/".$aid."_".$filename))
								{
									$image=new ImageHelper(DATA_DIR."/".$aid."_".$filename);
									$image->resize($width1,$height1,DATA_DIR."/".$aid."_".$filename);

									if($type==2 && $expandable ==1 && $expandable_enabled ==1)
									{
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET expandable=?,expandable_banner=? WHERE id=?",array($expandable,$expandable_filename,$aid));
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET expandable=b'?' WHERE id=?",array($expandable,$aid));

										if(move_uploaded_file($_FILES["expandable_banner_".$bannersize]["tmp_name"],DATA_DIR."/".$aid."_exp_".$expandable_filename))
										{
											$image_exp=new ImageHelper(DATA_DIR."/".$aid."_exp_".$expandable_filename);
											$image_exp->resize($expandable_width,$expandable_height,DATA_DIR."/".$aid."_exp_".$expandable_filename);
										}
										else
										$this->flash($this->get_message('expandable image file upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
									}


									if($adpricing == 3 && $positionID > 0)
									$this->flash($this->get_message('cpd ad targeting success'), $this->make_url('ad/view/'.$aid.'/2'));
									else
									$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
								}
								else
								$this->flash($this->get_message('image upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
							}
						}
						else    // HTML5 Banner
						{
							$filename='html5.zip';
							$expandable_filename='exp-html5.zip';

							$otherfile=0;
							$ctaflag=0;
							$ctainclude=1;
							$ctaflag_expandable=0;


							////////////////////////////////
							$supported_folders=Configuration::get_instance()->read('allowed_folders');
							$supported_formats=Configuration::get_instance()->read('allowed_file_formats');
							$allowed_image_file_extensions=Configuration::get_instance()->read('allowed_image_file_extensions');
							$allowed_video_file_extensions=Configuration::get_instance()->read('allowed_video_file_extensions');
							$allowed_font_file_extensions=Configuration::get_instance()->read('allowed_font_file_extensions');




							$validate_zip_folder_structure=Configuration::get_instance()->read('validate_zip_folder_structure');
							$support_remote_urls=Configuration::get_instance()->read('support_remote_urls');




							$supported_array			= explode(',',$supported_formats);
							$supported_folder_array		= explode(',',$supported_folders);
							$supported_image_array		= explode(',',$allowed_image_file_extensions);
							$supported_video_array		= explode(',',$allowed_video_file_extensions);
							$supported_font_array		= explode(',',$allowed_font_file_extensions);


							$supported_array=array_merge($supported_array,$supported_image_array);
							$supported_array=array_merge($supported_array,$supported_video_array);
							$supported_array=array_merge($supported_array,$supported_font_array);
							////////////////////////////////



							if(!is_dir(DATA_DIR))
							mkdir(DATA_DIR,0777);


							if(!is_dir(DATA_DIR.'/tmp-html-data/'))
							mkdir(DATA_DIR.'/tmp-html-data/',0777);

							if(!is_dir(DATA_DIR.'/tmp-html-data/'.$aid.'/'))
							mkdir(DATA_DIR.'/tmp-html-data/'.$aid.'/',0777);


							$before_extraction=DATA_DIR.'/tmp-html-data/'.$aid.'/'.$filename;
							$after_extraction=DATA_DIR.'/tmp-html-data/'.$aid.'/';


							/*************================****************/
							if(is_dir($after_extraction."html5/"))
							$this->remove_files($after_extraction.'html5/');
							/*************================****************/



							if(move_uploaded_file($_FILES["banner"]["tmp_name"],$before_extraction))
							{
								$zip = new ZipArchive;

								if($zip->open($before_extraction))
								{
									$zip->extractTo($after_extraction);
									$zip->close();



									$folders_inside_zip=array_diff(scandir($after_extraction), array('..', '.','html5.zip'));   /*************================****************/


									$enter_flag = 0;		/*************================****************/


									foreach($folders_inside_zip as $each_folder)
									{
										if(count($folders_inside_zip) == 1 && is_dir($after_extraction.$each_folder))   /*************================****************/
										{
											rename($after_extraction.$each_folder,$after_extraction."html5");

											$enter_flag = 1;		/*************================****************/
										}
									}


 									/*************================****************/
									if($enter_flag == 0)
									{
										if(!is_dir($after_extraction."html5/"))
										mkdir($after_extraction."html5/",0777);

										foreach($folders_inside_zip as $each_folder)
										{
											rename($after_extraction.$each_folder,$after_extraction."html5/".$each_folder);
										}
									}
									/*************================****************/




									if(is_dir($after_extraction))
									{
										if($validate_zip_folder_structure == 1)
										{
											$folder_lists=$this->get_folders($after_extraction.'html5/');

											$folder_difference=array_diff($folder_lists,$supported_folder_array);

											if(count($folder_difference) >0)
											$otherfile=21;                //Unwanted folders
										}


										if($otherfile ==0)
										{
											$extensionarray=array();
											$folder_files=$this->get_files($after_extraction.'html5/');


											foreach($folder_files as $fkey=>$fvalue)
											{
												$fvaluearray=explode('.',$fvalue);

												$extension_name=strtolower($fvaluearray[count($fvaluearray)-1]);

												if(!in_array($extension_name,$supported_array))
												{
													if(unlink($fvalue))
													{

													}
													else
													$extensionarray[]=$extension_name;
												}
												else
												$extensionarray[]=$extension_name;
											}

											$difference=array_diff($extensionarray,$supported_array);

											if(count($difference) >0)
											$otherfile=1;                //Unwanted files in folder
										}




										if($otherfile ==0)
										{
											if(!file_exists($after_extraction.'html5/index.html'))
											$otherfile=2;                //Index.html file missing

											if($otherfile ==0)
											{
												foreach($folder_files as $fkey1=>$fvalue1)
												{
													$scriptarray=array();
													$linkarray=array();

													$extension=explode('.',$fvalue1);
													$extension1=strtolower($extension[count($extension)-1]);


													if($extension1 == 'html' || $extension1 == 'js')   //for change
													{
														$filedata=$this->fetch_file_contents($fvalue1);


														if($support_remote_urls == 0)
														{
															$otherfile=$this->validate_file_contents($filedata);

															if($otherfile >0)
															break;
														}



														if($otherfile ==0) // For check if cta.js included
														{
															$scriptarray=$this->get_page_script_tag($filedata);

															$str_position=strpos($filedata,'javascript:xyz_cta.trigger()');

															if($str_position === false)
															{}
															else
															$ctaflag=1;

															if($fvalue1 == $after_extraction.'html5/index.html')
															{
																if(!in_array(BASE.DISPLAY_DIR."/js/cta.js",$scriptarray))
																$ctainclude=0;
															}
														}
												   }
												}

												if($otherfile ==0 && $ctaflag ==1 && $ctainclude ==0)
												$otherfile=3;                //Please include cta.js file for click tracking in index.html file
											}
										}
									}

									if($otherfile >0)
									$this->remove_files($after_extraction);

									if($otherfile ==0)
									{
										if(!is_dir(DATA_DIR.'/html5/'))
										mkdir(DATA_DIR.'/html5/',0777);

										if(!is_dir(DATA_DIR.'/html5/'.$aid.'/'))
										mkdir(DATA_DIR.'/html5/'.$aid.'/',0777);

										$this->recurse_copy($after_extraction,DATA_DIR.'/html5/'.$aid.'/');

										unlink(DATA_DIR.'/html5/'.$aid.'/html5.zip');

										$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET html5=1,cta_support=?,upload_success=1 WHERE id=?",array($ctaflag,$aid));
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET html5=b'1',cta_support=b'?',upload_success=b'1' WHERE id=?",array($ctaflag,$aid));
									}
								}
								else
								$error_flag=4;
							}
							else
							$error_flag=4;

							$this->remove_files($after_extraction);




									/*
										if($type==2 && $expandable ==1 && $expandable_enabled ==1)
										{
											$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET expandable=?,expandable_banner=? WHERE id=?",array($expandable,$expandable_filename,$aid));

											if(move_uploaded_file($_FILES["expandable_banner_".$bannersize]["tmp_name"],DATA_DIR."/".$aid."_exp_".$expandable_filename))
											{
												$image_exp=new ImageHelper(DATA_DIR."/".$aid."_exp_".$expandable_filename);
												$image_exp->resize($expandable_width,$expandable_height,DATA_DIR."/".$aid."_exp_".$expandable_filename);
											}
											else
											{
												$this->flash($this->get_message('expandable image file upload failed'), $this->make_url('ad/edit/'.$aid),0);
											}
										}
										*/









								if($otherfile ==1)
								$this->flash($this->get_message('unwanted files in folder'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==2)
								$this->flash($this->get_message('index.html file missing'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==3)
								$this->flash($this->get_message('please include cta.js file in index.html'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==4)
								$this->flash($this->get_message('unwanted scripts / urls'), $this->make_url('ad/view/'.$aid.'/1'),0);



								else if($otherfile ==5)
								$this->flash($this->get_message('unwanted <link> href'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==6)
								$this->flash($this->get_message('unwanted <base> tag'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==7)
								$this->flash($this->get_message('unwanted <meta> refresh'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==8)
								$this->flash($this->get_message('iframe not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);


								else if($otherfile ==9)
								$this->flash($this->get_message('unwanted image urls'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==10)
								$this->flash($this->get_message('unwanted href url'), $this->make_url('ad/view/'.$aid.'/1'),0);


								else if($otherfile ==13)
								$this->flash($this->get_message('window / document open not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);

								else if($otherfile ==14)
								$this->flash($this->get_message('document.write not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==15)
								$this->flash($this->get_message('ajax() not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==16)
								$this->flash($this->get_message('send() not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==17)
								$this->flash($this->get_message('XMLHttpRequest() not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);


								else if($otherfile ==18)
								$this->flash($this->get_message('alert() not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==19)
								$this->flash($this->get_message('eval() not supported'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==20)
								$this->flash($this->get_message('unwanted third party urls'), $this->make_url('ad/view/'.$aid.'/1'),0);
								else if($otherfile ==21)
								$this->flash($this->get_message('unwanted folders'), $this->make_url('ad/view/'.$aid.'/1'),0);

								else
								{
									if($adpricing == 3 && $positionID > 0)
									$this->flash($this->get_message('cpd ad targeting success'), $this->make_url('ad/view/'.$aid.'/2'));
									else
									$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
								}
						}
					}
					else if($type ==14)
					{
						if(!is_dir(DATA_DIR."/".$aid))
						mkdir(DATA_DIR."/".$aid,0777);

						foreach($filenames as $key => $filename)
						{
							if(move_uploaded_file($_FILES["skin_banner_".$key]["tmp_name"],DATA_DIR."/".$aid."/".$filename))
							{
								$image=new ImageHelper(DATA_DIR."/".$aid."/".$filename);
								$image->resize($width1,$height1,DATA_DIR."/".$aid."/".$filename);
							}
							else
							{
								$image_upload_flag=1;
								break;
							}
						}


						if($image_upload_flag ==0)
						{
							if($adpricing == 3 && $positionID > 0)
							$this->flash($this->get_message('cpd ad targeting success'), $this->make_url('ad/view/'.$aid.'/2'));
							else
							$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
						}
						else
						$this->flash($this->get_message('image upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
					}
					else if($type ==7)
					{
						$error_flag=0;

						$ecommerce_failed=0;
						$ecommerce_failed1=0;

						$csvempty=1;
						$iii=0;
						$gidfirst=0;


						$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET headline_type=?,headline_text=?,headline_link=?,action_text=?,title_color=?,desc_color=?,url_color=?,border_color=?,background_color=?,ad_background_color=?,price_color=?,offer_price_color=?,ab_text_color=?,ab_background_color=?,ab_bhover_color=?,ah_text_color=?,abh_text_color=?,abh_background_color=?,abh_bhover_color=?,ad_selection_color=? WHERE id=?",array($htype,$hdata,$hlink,$callactiontext,$color1,$color2,$color3,$color4,$color5,$color6,$color7,$color8,$color9,$color10,$color11,$color12,$color13,$color14,$color15,$color16,$aid));


						$old_logo_path="";
						$old_csv_path="";

						$checked_size_array=explode('-',$checked_size);



						foreach($checked_size_array as $kdata => $vdata)
						{
							$vdata_array=explode('_',$vdata);

							if(intval($vdata_array[0]) >0 && intval($vdata_array[1]) >0)
							{
								$gbannerid=intval($vdata_array[0]);
								$glayout=intval($vdata_array[1]);


								$append_string0="";
								$append_string1="";
								$append_string2=array();

								$ads_string0="";
								$ads_string1="";
								$ads_string2=array();


								$append_string2[]=$uid;
								$append_string2[]=$name;
								$append_string2[]=$type;
								$append_string2[]=$filename;
								$append_string2[]=$adstatus;
								$append_string2[]=0;
								$append_string2[]=$gbannerid;
								$append_string2[]=$clickurl;
								$append_string2[]=$adpricing;
								$append_string2[]=$time;
								$append_string2[]=$aid;
								$append_string2[]=$glayout;
								$append_string2[]=$htype;
								$append_string2[]=$hdata;
								$append_string2[]=$hlink;
								$append_string2[]=$callactiontext;
								$append_string2[]=$color1;
								$append_string2[]=$color2;
								$append_string2[]=$color3;
								$append_string2[]=$color4;
								$append_string2[]=$color5;
								$append_string2[]=$color6;
								$append_string2[]=$color7;
								$append_string2[]=$color8;
								$append_string2[]=$color9;
								$append_string2[]=$color10;
								$append_string2[]=$color11;
								$append_string2[]=$color12;
								$append_string2[]=$color13;
								$append_string2[]=$color14;
								$append_string2[]=$color15;
								$append_string2[]=$color16;
								$append_string2[]=$adv_status;
								$append_string2[]=-1;
								$append_string2[]=$ad_start_time;


								$ads_string2[]=$uid;
								$ads_string2[]=$type;
								$ads_string2[]=$gbannerid;
								$ads_string2[]=$adpricing;
								$ads_string2[]=$aid;
								$ads_string2[]=$glayout;
								$ads_string2[]=$adv_status;


								if($refferal_status ==1)
								{
									$append_string0.=",refferal_id,refferal_status";
									$ads_string0.=",refferal_id,refferal_status";

									$append_string1.=",?,?";
									$ads_string1.=",?,b'?'";

									$append_string2[]=$ref_id;
									$append_string2[]=$refferal_status;

									$ads_string2[]=$ref_id;
									$ads_string2[]=$refferal_status;
								}



								$gres=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,banner,status,pause_status,banner_id,click_url,display_type,updation_time,ecommerce_parent,display_layout,headline_type,headline_text,headline_link,action_text,title_color,desc_color,url_color,border_color,background_color,ad_background_color,price_color,offer_price_color,ab_text_color,ab_background_color,ab_bhover_color,ah_text_color,abh_text_color,abh_background_color,abh_bhover_color,ad_selection_color,user_status,pricing_status,start_date".$append_string0.") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?".$append_string1.")",$append_string2);


								if($gres->error =="")
								{
									$gid=$gres->get_last_id();

									if($iii ==0)
									$gidfirst=$gid;



									array_unshift($ads_string2,$gid);
									$gres1=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_cache (id,uid,type,banner_id,display_type,ecommerce_parent,display_layout,user_status".$ads_string0.") VALUES (?,?,?,?,?,?,?,?".$ads_string1.")",$ads_string2);

                  					$device_targetting_enabled=$this->get_addon_status('device-targeting_enabled');
									$languge_targetting_enabled=$this->get_addon_status('language-targeting_enabled');
									$category_targetting_enabled=$this->get_addon_status('category-targeting_enabled');
									$connection_targetting_enabled=$this->get_addon_status('connectiontype-targeting_enabled');
									if($device_targetting_enabled ==1 || $device_targetting_enabled ==0)
									{
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_os=1 WHERE id=?",array($gid));
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_browser=1 WHERE id=?",array($gid));
									}
									if($languge_targetting_enabled ==1 || $languge_targetting_enabled ==0)
									{
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_languages=1 WHERE id=?",array($gid));
									}
									if($category_targetting_enabled ==1 || $category_targetting_enabled ==0)
									{
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=1 WHERE id=?",array($gid));
									}
									if($connection_targetting_enabled ==1 || $connection_targetting_enabled ==0)
									{
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_connections=1 WHERE id=?",array($gid));
									}

									$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET logo=? WHERE id=?",array($gid.'-'.$ecommercelogo,$gid));

									if($adpricing !=3)
									{
										if($retargeting_enabled ==1 && ($adpricing ==0 || $adpricing ==6))
										{
										    $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET retargeting=? WHERE id=?",array($retargeting,$gid));
										    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET retargeting=b'?' WHERE id=?",array($retargeting,$gid));

										}

										$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_geographic_mapping (uid,aid,country_code) values (?,?,?)",array($uid,$gid,0));
										$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_countries=b'?' WHERE id=? AND uid=?",array(1,$gid,$uid));

										$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) VALUES (?,?,?)",array($uid,$gid,0));
									}

									if($iii ==0)
									{
										if(!is_dir(DATA_DIR.'/ecommerce'))
										mkdir(DATA_DIR.'/ecommerce',0777);

										if(!is_dir(DATA_DIR.'/ecommerce/logo'))
										mkdir(DATA_DIR.'/ecommerce/logo',0777);

										if(!is_dir(DATA_DIR.'/ecommerce/csv'))
										mkdir(DATA_DIR.'/ecommerce/csv',0777);
									}

									if(!is_dir(DATA_DIR.'/ecommerce/'.$gid))
									mkdir(DATA_DIR.'/ecommerce/'.$gid,0777);

									$logopath=DATA_DIR."/ecommerce/logo/".$gid."-".$ecommercelogo;
									$csvpath=DATA_DIR."/ecommerce/csv/".$gid."-".$csvfile;

									if($iii ==0)
									{
										$old_logo_path=$logopath;

										if(move_uploaded_file($_FILES["ecommercelogo"]["tmp_name"],$logopath))
										{

										}
										else
										$error_flag=1;
									}
									else
									{
										if(copy($old_logo_path,$logopath))
										{

										}
										else
										$error_flag=1;
									}

									/*if($file_type ==0 && $error_flag ==0)
									{
										if($iii ==0)
										{
											$old_csv_path=$csvpath;

											if(move_uploaded_file($_FILES["csvfile"]["tmp_name"],$csvpath))
											{
												$csvarray=array();
												$i=0;
												$csv=fopen($csvpath,"r");
												while(!feof($csv))
												{
													$csvdata=fgetcsv($csv);

													if($i >0 && isset($csvdata) && $csvdata[0] !="")
													$csvarray[]=$csvdata;

													$i=$i+1;
												}
												fclose($csv);
											}
											else
											$error_flag=2;
										}
									}*/

									if($error_flag ==0)
									{
										if(count($csvarray) ==0)
										$csvempty=0;

										//0=>Title
										//1=>Description
										//2=>Display Url
										//3=>Click Url
										//4=>Image Path
										//5=>Sale Price
										//6=>Offer Price
										//7=>Retargeting Url

										$ii=1;
										$imageinsert=0;
										$timedata=$time;


										foreach($csvarray as $key=>$value)
										{
											$ecommerce_failed1=$ecommerce_failed1+$ecommerce_failed;

											$ecommerce_failed=0;


											$ad_retargeting_url='';

											$ad_title=substr(trim($value[0]),0,$maxtitle);
											$ad_description=substr(trim($value[1]),0,$maxdesc);
											$ad_display_url=substr(trim($value[2]),0,$maxdispurl);
											$ad_click_url=trim($value[3]);
											$ad_image_path=trim($value[4]);
											$ad_sale_price=trim($value[5]);
											$ad_offer_price=trim($value[6]);

											if(isset($value[7]))
											$ad_retargeting_url=trim($value[7]);


											if($ad_title =="" || $ad_description =="" || $ad_display_url =="" || $ad_click_url =="" || $ad_image_path =="")
											{
												$ecommerce_failed=$ecommerce_failed+1;
												continue;
											}
											else
											{
												$adimageextension=explode(".",$ad_image_path);
												$adimageextensionname=strtolower($adimageextension[count($adimageextension)-1]);


												if($adimageextensionname != "gif" && $adimageextensionname != "jpeg" && $adimageextensionname != "pjpeg" && $adimageextensionname != "png" && $adimageextensionname != "svg" && $adimageextensionname != "jpg")
												{
													$ecommerce_failed=$ecommerce_failed+1;
													continue;
												}
												else
												{
													$ad_image_name=$gid.'-'.$ii.'-'.$timedata.'.'.$adimageextensionname;

													$res123=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_list (uid,aid,ad_title,ad_description,ad_display_url,ad_click_url,ad_retargeting_url,ad_image,ad_image_path,ad_price,ad_offer_price) VALUES (?,?,?,?,?,?,?,?,?,?,?)",array($uid,$gid,$ad_title,$ad_description,$ad_display_url,$ad_click_url,$ad_retargeting_url,$ad_image_name,$ad_image_path,$ad_sale_price,$ad_offer_price));

													$lastrow=$res123->get_last_id();


													$filecontent=$this->fetch_file_contents($ad_image_path);

													if($filecontent !="")
													{
														$ecommercepath=DATA_DIR.'/ecommerce/'.$gid.'/'.$ad_image_name;

														if(file_put_contents($ecommercepath,$filecontent))
														{
															$currentsize=filesize($ecommercepath);
															$currentsize=$currentsize/1024;


															if($currentsize > $filesize)
															{
																$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($lastrow));

																unlink($ecommercepath);

																$ecommerce_failed=$ecommerce_failed+1;
															}
															else
															{
																$imageinsert=1;


																if($retargeting_enabled ==1 && $retargeting ==1 && $ad_retargeting_url !="")
																{
																	$rmsite=str_ireplace("http://","",$ad_retargeting_url);
																	$rmsite=str_ireplace("https://","",$rmsite);
																	$rmsite=str_ireplace("www.","",$rmsite);

																	$rmurl=$rmsite;


																	$stringlength=strlen($rmsite);

																	$string="";
																	for($i=0;$i < $stringlength;$i++)
																	{
																		if($rmsite[$i] =='.' || ctype_alnum($rmsite[$i]))
																		$string.=$rmsite[$i];
																		else
																		break;
																	}

																	$rmsite=$string;



																	if(UtilityHelper::is_valid_domain($rmsite))
																	{
																		$rsid=$db->read_single_column("select id from ".TABLE_PREFIX."retargeting_sites where url=?",array($rmsite));

																		if(intval($rsid) ==0)
																		{
																			$query=$db->execute_query("INSERT INTO ".TABLE_PREFIX."retargeting_sites (uid,url) VALUES (?,?)",array($uid,$rmsite));

																			$rsid=$query->get_last_id();
																		}

																		if($rsid >0)
																		{
																			$rmcondition=str_ireplace($rmsite,"",$rmurl);

																			$rmlistid=$db->read_single_column("select id from ".TABLE_PREFIX."retargeting_lists WHERE sid=? AND uid=? AND conditions=? AND type=1",array($rsid,$uid,$rmcondition));

																			if(intval($rmlistid) ==0)
																			{
																				$rlinsert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."retargeting_lists (uid,sid,conditions,type) VALUES (?,?,?,?)",array($uid,$rsid,$rmcondition,1));

																				$rmlistid=$rlinsert->get_last_id();

																				$rmname='Ecommerce Ad Retargeting List - '.$rmlistid;

																				$db->execute_query("UPDATE ".TABLE_PREFIX."retargeting_lists SET name=? WHERE id=?",array($rmname,$rmlistid));
																			}

																			if($rmlistid >0)
																			$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_retargeting_mapping (uid,aid,alid,rid,rsid) VALUES (?,?,?,?,?)",array($uid,$gid,$lastrow,$rmlistid,$rsid));
																		}
																	}
																}
															}
														}
														else
														{
															$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($lastrow));

															$ecommerce_failed=$ecommerce_failed+1;
														}
													}
													else
													{
														$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($lastrow));

														$ecommerce_failed=$ecommerce_failed+1;
													}

													$ii=$ii+1;
												}
											}
										}

										$ecommerce_failed1=$ecommerce_failed1+$ecommerce_failed;
									}

									$iii=$iii+1;
								}
								else
								$error_flag=3;
							}
						}


						//if($file_type ==0 && $old_csv_path !="" && file_exists($old_csv_path))
						//unlink($old_csv_path);


						if($error_flag !=3)
						{
							if($error_flag ==0 && $csvempty ==1)
							{
								if($dbadtype ==7)
								$dbadtype=2;

								if($ecommerce_failed1 ==0)
								{
									if($adpricing == 3 && $positionID > 0)
									$this->flash($this->get_message('cpd ad targeting success'), $this->make_url('ad/view/'.$aid.'/2'));
									else
									$this->flash($this->get_message(''), $this->make_url('ad/view/'.$aid.'/2'));
								}
								else
								$this->flash($this->get_message('ecommerce file upload failed',array('x'=>$ecommerce_failed1)), $this->make_url('ad/view/'.$aid.'/1'),0);
							}
							else if($csvempty ==0)
							$this->flash($this->get_message('csv file empty'), $this->make_url('ad/view/'.$aid.'/1'),0);
							else if($error_flag ==1)
							$this->flash($this->get_message('image file upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
							else if($error_flag ==2)
							$this->flash($this->get_message('csv file upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
							else
							$this->flash($this->get_message('file upload failed'), $this->make_url('ad/view/'.$aid.'/1'),0);
						}
						else
						$this->set_notice("error occurred");
					}
				}
				else
				$this->set_notice("error occurred");
			}
		}
	}
	else
	{
			if($cloneFlag == 1)
			{
					 $clone_data = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id = ?",array($parentAdID));
					 $clone_row  = $clone_data->fetch_assoc();

					 $this->set_variable("name",$clone_row['name']);
					 $this->set_variable("title",$clone_row['title']);
					 $this->set_variable("desc",$clone_row['description']);
					 $this->set_variable("displayurl",$clone_row['display_url']);
					 $this->set_variable("clickurl",$clone_row['click_url']);
					 $this->set_variable("cta_button_text",$clone_row['cta_button_text']);


					 $expandable   = intval($clone_row['expandable']);
					 $retargeting  = intval($clone_row['retargeting']);
					 $vast_support = intval($clone_row['video_player_support']);
					 $banner_type  = intval($clone_row['html5']);

					 //$this->set_variable("html5",$clone_row['html5']);

					 $this->set_variable("type",$clone_row['type']);
					 $this->set_variable("bannersize",$clone_row['banner_id']);
					 $this->set_variable("adpricing",$clone_row['display_type']);
	 	  }
	}


		$this->set_variable("additional_banners",$additional_banners);
		$this->set_variable('cpdPackage',$cpdPackage);
		$this->set_variable('rowid',$rowid);
		$this->set_variable('file_type',$file_type);
		$this->set_variable('htype',$htype);
		$this->set_variable('hdata',$hdata);
		$this->set_variable('hlink',$hlink);
		$this->set_variable('callactiontext',$callactiontext);


		$this->set_variable('expandable',$expandable);
		$this->set_variable('vast_support',$vast_support);
		$this->set_variable('retargeting',$retargeting);

		$this->set_variable('color1',$color1);
		$this->set_variable('color2',$color2);
		$this->set_variable('color3',$color3);
		$this->set_variable('color4',$color4);
		$this->set_variable('color5',$color5);
		$this->set_variable('color6',$color6);
		$this->set_variable('color7',$color7);
		$this->set_variable('color8',$color8);
		$this->set_variable('color9',$color9);
		$this->set_variable('color10',$color10);
		$this->set_variable('color11',$color11);
		$this->set_variable('color12',$color12);
		$this->set_variable('color13',$color13);
		$this->set_variable('color14',$color14);
		$this->set_variable('color15',$color15);
		$this->set_variable('color16',$color16);
		$this->set_variable('banner_type',$banner_type);
	}


	function edit_action()
	{
		$uname=$this->read_cookie_param(COOKIE_USERNAME);
		$pass=$this->read_cookie_param(COOKIE_PASSWORD);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$db= DAL::get_instance();

		$currentTime = time();

		$referral_enabled=$this->get_addon_status('referral_enabled');

		$ref_id=0;
		$refferal_status=0;
		$ref_string="";

		if($referral_enabled ==1)
		$ref_string=",rid";

		$user_data=$db->execute_query("SELECT adv_status,adv_account_balance".$ref_string." FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
		$user_data_row=$user_data->fetch_assoc();

		$adv_balance = $user_data_row['adv_account_balance'];
		$adv_status  = $user_data_row['adv_status'];

		if($referral_enabled ==1)
		{
			$ref_id=intval($user_data_row['rid']);

			if($ref_id > 0)
			{
				$ref_array=$this->get_referral_user_active($ref_id);

				if($ref_array[0] >0)
				$refferal_status=1;
			}
		}

		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		{
			$aid=$this->read_page_param(1);
			$from=$this->read_page_param(2);
		}

		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('ad/list'),0);
			exit;
		}

		$expandable = 0;

		$adrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
		$addata=$adrow->fetch_assoc();

        $adtype            = $addata['type'];
		$ecommerce_parent  = $addata['ecommerce_parent'];
		$current_banner    = $addata['banner_id'];
		$pricing           = $addata['display_type'];

		if($this->get_addon_status('device-targeting_enabled') ==1)
		$device=$addata['device'];
		else
		$device=2;

		$this->set_variable('device',$device);


		$expandable_enabled   = $this->get_addon_status('expandable-banners_enabled');
		$retargeting_enabled  = $this->get_addon_status('retargeting_enabled');
		$ecommerce_enabled    = $this->get_addon_status('ecommerce-ads_enabled');
		$textimage_enabled    = $this->get_addon_status('text-image-ads_enabled');
		$video_enabled        = $this->get_addon_status('video-ads_enabled');
		$skin_enabled         = $this->get_addon_status('skin-ads_enabled');
		$html5_enabled        = $this->get_addon_status('html5-ads_enabled');


		if($html5_enabled == 1)
		{
			$html5_ads_for_all_advertisers=intval(Configuration::get_instance()->read('html5_ads_for_all_advertisers'));

			if($html5_ads_for_all_advertisers ==0)
			{
				$advertiser_html5_support=$this->get_advertiser_html5_support($uid);

				if($advertiser_html5_support ==0)
				$html5_enabled = 0;
			}
		}

		$vast_support=0;
		$linear_support=0;
		$nonlinear_support=0;
		$html5_player_support=0;

		if($video_enabled ==1)
		{
			$linear_support=intval(Configuration::get_instance()->read('vast_player_linear_support'));
			$nonlinear_support=intval(Configuration::get_instance()->read('vast_player_nonlinear_support'));
			$html5_player_support=intval(Configuration::get_instance()->read('html5_player_support'));
		}

		$this->set_variable('video_enabled',$video_enabled);
		$this->set_variable('linear_support',$linear_support);
		$this->set_variable('nonlinear_support',$nonlinear_support);
		$this->set_variable('html5_player_support',$html5_player_support);
		$this->set_variable('html5_enabled',$html5_enabled);


		$this->set_variable('expandable_enabled',$expandable_enabled);
		$this->set_variable('ecommerce_enabled',$ecommerce_enabled);
		$this->set_variable('textimage_enabled',$textimage_enabled);
		$this->set_variable('ecommerce_parent',$ecommerce_parent);
		$this->set_variable('retargeting_enabled',$retargeting_enabled);
		$this->set_variable('skin_enabled',$skin_enabled);


		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);

		if($pricing ==1 && $this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($pricing ==6 && $this->get_addon_status('cpa_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($pricing ==13 && $this->get_addon_status('video-ads_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adtype ==2 || $adtype ==12)
		{
			$res1=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND (banner_type =0 OR banner_type =1) AND image_support =1");
			$this->set_result("res1",$res1);
		}
		else if($adtype ==7)
		{
			$res1=$db->execute_query("select b.* from ".TABLE_PREFIX."banner_dimensions b INNER JOIN ".TABLE_PREFIX."display_layout d ON b.id=d.layout_banner where status=1 AND banner_type =0 AND ecommerce_support =1 GROUP BY b.id");
			$this->set_result("res1",$res1);
		}
		else if($adtype ==11)
		{
			$res1=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =3");
			$this->set_result("res1",$res1);
		}
		else if($adtype ==14)
		{
			$res14_banner=$db->execute_query("select b.* from ".TABLE_PREFIX."banner_dimensions b where b.status=1 AND b.banner_type =4");
			$this->set_result("res14_banner",$res14_banner);

			$res14_adblock=$db->execute_query("select ab.* from ".TABLE_PREFIX."adblock ab where ab.status=1 AND ab.type =2 And ab.banner_type =4");
			$this->set_result("res14_adblock",$res14_adblock);
		}

		$adstatus=Configuration::get_instance()->read('default_ad_status');

		$htype=0;
		$hdata="";
		$hlink="";
		$callactiontext="";

		$themedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adblock_theme WHERE theme_type=1");
		$themedata1=$themedata->fetch_assoc();

		$retargeting=0;
		$rowid=1;
		$file_type=1;
		$banner="";
		$videofile="";

		$banner_type = 0;
		$additional_banners = "";

		if($_POST)
		{
			if(DEMO_MODE && $aid <= 100)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('ad/list'),0);
				exit;
			}

			$additional_banners = $this->read_post_param('additional_banners'); //For CPD Banners


			$from=$this->read_post_param("from");

			$name=$this->read_post_param('name');
			$type=$this->read_post_param('type');
			$title=$this->read_post_param('title');
			$desc=$this->read_post_param('desc');
			$displayurl=$this->read_post_param('displayurl');
			$cta_button_text=$this->read_post_param('cta_button_text');
			$clickurl=$this->read_post_param('clickurl');

			$clickurl=filter_var($clickurl,FILTER_SANITIZE_URL);

			if($html5_enabled ==1 && $type ==2)
			$banner_type=intval($this->read_post_param('banner_type_value'));

			$bannerid=$this->read_post_param('bannersize_0');

			if($type == 2 && $banner_type == 0)
			$expandable=intval($this->read_post_param('expandable_'.$bannerid));

			if(mb_strlen($name,'utf8') >25)
			$name=substr($name,0,25);


			$this->set_variable("name",$name);

			if($type ==2 || $type ==7 || $type ==11 || $type ==14)
			$this->set_variable("bannerid",$bannerid);
			else
			$this->set_variable("bannerid",0);

			$this->set_variable("type",$type);
			$this->set_variable("aid",$aid);
			$this->set_variable("expandable",$expandable);



			if($type==2 || $type ==11)
			$banner=$_FILES["banner"]["name"];


			$old_data=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($aid));
			$old_data_row=$old_data->fetch_assoc();
			$old_name=$old_data_row['banner'];
			$oldbaid=$old_data_row['banner_id'];
			$oldbanner_type=$old_data_row['html5'];
			$upload_success=intval($old_data_row['upload_success']);


			$oldBannerListArray = json_decode($old_data_row['banner_list'],1);

			$old_exp_name="";
			$old_expandable=0;

			if($expandable_enabled ==1)
			{
				$old_exp_name   = $old_data_row['expandable_banner'];
				$old_expandable = $old_data_row['expandable'];
			}

			$flag=0;
			if($old_data_row['title'] != $title)
			$flag = 1;
			else if($old_data_row['description'] != $desc)
			$flag = 1;
			else if($old_data_row['click_url'] != $clickurl)
			$flag = 1;
			else if($old_data_row['display_url'] != $displayurl)
			$flag = 1;
			else if($banner !='' && $old_name != $banner)
			$flag = 1;


		    if($flag ==0 || $old_data_row['status'] ==-2)
		    $adstatus=$old_data_row['status'];


			$maxtitle 			= Configuration::get_instance()->read('max_ad_title_length');
			$maxdesc 			= Configuration::get_instance()->read('max_ad_desc_length');
			$maxdispurl 		= Configuration::get_instance()->read('max_display_url_length');
			$ctaButtonLength 	= Configuration::get_instance()->read('cta_button_text_length');


			$rowid=$this->read_post_param('rowid');
			$file_type=intval($this->read_post_param('file_type_hidden'));

			$ecommerce_flag=0;

			$csvarray=array();

			if($file_type ==1 && $ecommerce_parent ==0)
			{
				$rowarray=explode('-',$rowid);

				foreach($rowarray as $gkey => $gvalue)
				{
					$csvarray1=array();

					$gtitle=trim($this->read_post_param('ad_title_'.$gvalue));
					$gdescription=trim($this->read_post_param('ad_description_'.$gvalue));
					$gdispurl=trim($this->read_post_param('ad_display_url_'.$gvalue));
					$gclickurl=trim($this->read_post_param('ad_click_url_'.$gvalue));
					$gimageurl=trim($this->read_post_param('image_url_'.$gvalue));
					$gretargetingurl=trim($this->read_post_param('ad_retargeting_url_'.$gvalue));
					$gadprice=trim($this->read_post_param('ad_price_'.$gvalue));
					$gadofferprice=trim($this->read_post_param('ad_offer_price_'.$gvalue));
					$gaimagepathdir=trim($this->read_post_param('ecommerce_ad_image_'.$gvalue));

					$this->set_variable('ad_title_'.$gvalue,$gtitle);
					$this->set_variable('ad_description_'.$gvalue,$gdescription);
					$this->set_variable('ad_display_url_'.$gvalue,$gdispurl);
					$this->set_variable('ad_click_url_'.$gvalue,$gclickurl);
					$this->set_variable('image_url_'.$gvalue,$gimageurl);
					$this->set_variable('ad_retargeting_url_'.$gvalue,$gretargetingurl);
					$this->set_variable('ad_price_'.$gvalue,$gadprice);
					$this->set_variable('ad_offer_price_'.$gvalue,$gadofferprice);
					$this->set_variable('ad_image_'.$gvalue,$gaimagepathdir);


					if($gtitle !="" && $gdescription !="" && $gdispurl !="" && $gclickurl !="" && $gimageurl !="")
					{
						$ecommerce_flag=1;

						$csvarray1[]=substr($gtitle,0,$maxtitle);
						$csvarray1[]=substr($gdescription,0,$maxdesc);
						$csvarray1[]=substr($gdispurl,0,$maxdispurl);
						$csvarray1[]=$gclickurl;
						$csvarray1[]=$gimageurl;
						$csvarray1[]=$gadprice;
						$csvarray1[]=$gadofferprice;
						$csvarray1[]=$gretargetingurl;


						$csvarray[]=$csvarray1;
					}
				}
			}

			$layout_flag=0;
			$checked_size="";
			$size_array=array();

			if($ecommerce_enabled ==1 && $type ==7)
			{
				$size_checked=$this->read_post_param('size_checked');

				if($size_checked !="")
				{
					$size_array=explode('-',$size_checked);

					foreach($size_array as $k123=>$v123)
					{
						if(trim($v123) !="")
						{
							$size_checkbox=intval($this->read_post_param('chk_diamension_'.$v123));

							$this->set_variable('chk_diamension_'.$v123,$size_checkbox);

							if($size_checkbox ==1)
							{
								$layout_value=intval($this->read_post_param('layoutid_'.$v123));

								if($layout_value >0)
								{
									$layout_flag=1;

									if($checked_size !="")
									$checked_size.="-";

									$checked_size.=$v123.'_'.$layout_value;

								}

								$this->set_variable('layoutid_'.$v123,$layout_value);
							}
						}
					}
				}

				$this->set_variable('size_checked',$size_checked);



				$htype=intval($this->read_post_param('htype'));
				$hdata=$this->read_post_param('hdata');
				$hlink=$this->read_post_param('hlink');
				$callactiontext=$this->read_post_param('callactiontext');



				$ecommercelogo="";
				$csvfile="";

				if($ecommerce_parent ==0)
				{
					$ecommercelogo=$_FILES["ecommercelogo"]["name"];
					//$csvfile=$_FILES["csvfile"]["name"];
				}

				$color1=$this->read_post_param('color1');
				$color2=$this->read_post_param('color2');
				$color3=$this->read_post_param('color3');
				$color4=$this->read_post_param('color4');
				$color5=$this->read_post_param('color5');
				$color6=$this->read_post_param('color6');
				$color7=$this->read_post_param('color7');
				$color8=$this->read_post_param('color8');
				$color9=$this->read_post_param('color9');
				$color10=$this->read_post_param('color10');
				$color11=$this->read_post_param('color11');
				$color12=$this->read_post_param('color12');
				$color13=$this->read_post_param('color13');
				$color14=$this->read_post_param('color14');
				$color15=$this->read_post_param('color15');
				$color16=$this->read_post_param('color16');



				if($color1 =="")
				$color1=$themedata1['title'];
				if($color2 =="")
				$color2=$themedata1['description'];
				if($color3 =="")
				$color3=$themedata1['url'];
				if($color4 =="")
				$color4=$themedata1['border'];
				if($color5 =="")
				$color5=$themedata1['background'];
				if($color6 =="")
				$color6=$themedata1['ad_background'];
				if($color7 =="")
				$color7=$themedata1['price'];
				if($color8 =="")
				$color8=$themedata1['offer_price'];
				if($color9 =="")
				$color9=$themedata1['button'];
				if($color10 =="")
				$color10=$themedata1['button_background'];
				if($color11 =="")
				$color11=$themedata1['button_hover'];
				if($color12 =="")
				$color12=$themedata1['heading'];
				if($color13 =="")
				$color13=$themedata1['heading_button_background'];
				if($color14 =="")
				$color14=$themedata1['heading_button'];
				if($color15 =="")
				$color15=$themedata1['heading_button_hover'];
				if($color16 =="")
				$color16=$themedata1['ad_selection_border'];



				$this->set_variable('color1',$color1);
				$this->set_variable('color2',$color2);
				$this->set_variable('color3',$color3);
				$this->set_variable('color4',$color4);
				$this->set_variable('color5',$color5);
				$this->set_variable('color6',$color6);
				$this->set_variable('color7',$color7);
				$this->set_variable('color8',$color8);
				$this->set_variable('color9',$color9);
				$this->set_variable('color10',$color10);
				$this->set_variable('color11',$color11);
				$this->set_variable('color12',$color12);
				$this->set_variable('color13',$color13);
				$this->set_variable('color14',$color14);
				$this->set_variable('color15',$color15);
				$this->set_variable('color16',$color16);
			}


			if($pricing ==0 || ($pricing ==6 && $type != 12))
			$retargeting=intval($this->read_post_param('retargeting'));

			$this->set_variable("retargeting",$retargeting);


			if($pricing ==0 || $pricing ==1 || ($pricing ==6 && $type != 12))
			{
				if($video_enabled ==1 && $nonlinear_support ==1 && ($type ==1 || $type ==2))
				$vast_support=intval($this->read_post_param('vast_support'));
			}

			if($name == "")
			$this->set_notice("please fill ad name");
			else if($type !=7 && $clickurl == "")
			$this->set_notice("please fill click url");
			else if(($type ==1 || $type ==11 || $type ==12 || $type == 18) && $title =="")
			$this->set_notice("please fill ad title");
			else if(($type ==1 || $type ==11 || $type ==12 || $type == 18) && $desc =="")
			$this->set_notice("please fill ad description");
			else if(($type ==1 || $type ==11  || $type ==13) && $displayurl =="")
			$this->set_notice("please fill display url");
			else if($type !=7 && !UtilityHelper::is_valid_url($clickurl))
			$this->set_notice("click url is not valid");

			else if(($type ==1 || $type ==11 || $type == 18) && mb_strlen($title,'utf8') > $maxtitle)
			$this->set_notice($this->get_message("check ad length",array('x'=>$maxtitle)));

			else if(($type ==1 || $type ==11 || $type == 18) && mb_strlen($desc,'utf8') > $maxdesc)
			$this->set_notice($this->get_message("check desc length",array('x'=>$maxdesc)));

			else if(($type ==1 || $type ==11 || $type ==13 || $type == 18) && mb_strlen($displayurl,'utf8') > $maxdispurl)
			$this->set_notice($this->get_message("check display url length",array('x'=>$maxdispurl)));

			else if(($type ==1 || $type ==11) && $cta_button_text != "" && mb_strlen($cta_button_text,'utf8') > $ctaButtonLength)
			$this->set_notice($this->get_message("check cta button length",array('x'=>$ctaButtonLength)));


			else if(($type==2 || $type ==11) && ($oldbaid != $bannerid && $banner =="") || (($oldbanner_type != $banner_type) && $banner==""))
			$this->set_notice("please upload ad banner");


			else if($type == 2 && $expandable ==1 && ($oldbaid != $bannerid || $oldbanner_type != $banner_type || $old_expandable ==0) && $_FILES["expandable_banner_".$bannerid]["name"] == "")
			$this->set_notice("please upload expandable ad banner");



			else if($type ==7 && ($size_checked =="" || $layout_flag ==0))
			$this->set_notice("please choose a display layout");
			else if($type ==7 && ($hdata =="" || $hlink ==""))
			$this->set_notice("please enter headline data");
			//else if($type ==7 && $ecommerce_parent ==0 && $file_type ==0 && $csvfile =="")
			//$this->set_notice("please upload csv file");
			else if($type==7 && mb_strlen($hdata,'utf8') > Configuration::get_instance()->read('max_headline_length'))
			$this->set_notice($this->get_message("check headline length",array('x'=>Configuration::get_instance()->read('max_headline_length'))));
			else if($type==7 && mb_strlen($callactiontext,'utf8') > Configuration::get_instance()->read('max_call_action_length'))
			$this->set_notice($this->get_message("check call action length",array('x'=>Configuration::get_instance()->read('max_call_action_length'))));



			else
			{
				$mimetype = "";
				$filename = "";

				$expandable_filename="";
				$expbannerwidth = 0;
				$expbannerheight = 0;

				$videoextensionname="";
				$videomaxsize=0;
				$videomaxduration=0;

				$duration=0;
				$duration_string="";
				$bitrate=0;
				$videowidth=0;
				$videoheight=0;
				$videosize=0;

				$skin_erro_flag=0;
				$image_upload_flag=0;
				$noimage_flag=0;

				$aspect_ratio_id=0;
				$aspect_ratio_value=0;
				$aspect_ratio_array=array();


				$html5_flag = 0;


			if($type ==2 || $type ==7 || $type ==11)
			{
				if($type==2 || $type==11)
				{
					$res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bannerid));
					$result11=$res11->fetch_assoc();
					$height1=$result11['height'];
					$width1=$result11['width'];
					$filesize=$result11['filesize'];
					$html5_filesize=$result11['html5_filesize'];


					if($type ==2)
					{
						$vast_video_support=intval($result11['vast_video_support']);
						$expandable_support=intval($result11['expandable_support']);
						$expandable_height=$result11['expandable_height'];
						$expandable_width=$result11['expandable_width'];

						if($expandable_enabled ==1 && $expandable_support ==1 && $expandable ==1 && $expandable_width >0 && $expandable_height >0)
						$expandable=1;
						else
						$expandable=0;

						if($vast_video_support ==0)
						$vast_support=0;
					}



					if($_FILES['banner']['name'] != "")
					{
						$bannerclass		= new getID3;
						$bannerUploadedData	= $bannerclass->analyze($_FILES['banner']["tmp_name"]);
						$mimetype			= $bannerUploadedData['mime_type'];
					}



					$extension=explode(".",$_FILES['banner']['name']);
					$extensionname=strtolower($extension[count($extension)-1]);

					if($_FILES['banner']['name'] !="")
					{
						$filename=$currentTime.'.'.$extensionname;

						$bannerinfo = getimagesize($_FILES["banner"]["tmp_name"]);
						$bannerwidth = $bannerinfo[0];
						$bannerheight = $bannerinfo[1];
					}

					if($type ==2 && $expandable ==1)
					{
						$expandable_filename=$_FILES["expandable_banner_".$bannerid]["name"];


						if($expandable_filename !="")
						{
							$expandable_extension=explode(".",$expandable_filename);
							$expandable_extensionname=strtolower($expandable_extension[count($expandable_extension)-1]);

							$expandable_filename=$currentTime.'.'.$expandable_extensionname;

							//////////
							$expbannerinfo = getimagesize($_FILES["expandable_banner_".$bannerid]["tmp_name"]);
							$expbannerwidth = $expbannerinfo[0];
							$expbannerheight = $expbannerinfo[1];
							//////////
						}
					}
				}

				if($type ==7)
				{
					$res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bannerid));
					$result11=$res11->fetch_assoc();
					$filesize=$result11['filesize'];


					$mimetype="";
					$filename="";
					$csvextensionname="";

					$logoextension=explode(".",$_FILES['ecommercelogo']['name']);
					$logoextensionname=strtolower($logoextension[count($logoextension)-1]);

					//if($file_type ==0 && $ecommerce_parent ==0)
					//{
					//	$csvextension=explode(".",$_FILES['csvfile']['name']);
					//	$csvextensionname=strtolower($csvextension[count($csvextension)-1]);
					//}

					if($ecommercelogo !="")
					$ecommercelogo=$currentTime.'.'.$logoextensionname;
				}
			}
			else if($type == 18)
			{

				if($_FILES['notify_icon_image']['name'] != "")
					{
						$bannerclass		= new getID3;
						$bannerUploadedData	= $bannerclass->analyze($_FILES['notify_icon_image']["tmp_name"]);
						$mimetype			= $bannerUploadedData['mime_type'];
						$filename=$_FILES['notify_icon_image']['name'];
						 $extension=explode(".",$filename);

                            $extensionname=$extension[1];


					}

		    }
			else if($type ==13)
			{
				$videofile=$_FILES['videofile']['name'];
				$videofile_temp_name=$_FILES['videofile']["tmp_name"];

				if($videofile !="" && $videofile_temp_name !="")
				{
					$videoextension=explode(".",$videofile);
					$videoextensionname=strtolower($videoextension[count($videoextension)-1]);

					$videofile=$currentTime.'.'.$videoextensionname;

					$videomaxsize=Configuration::get_instance()->read('video_file_max_size');
					$videomaxduration=Configuration::get_instance()->read('video_file_max_duration');

					$videoclass= new getID3;
					$videodata=$videoclass->analyze($videofile_temp_name);


					$duration=ceil($videodata['playtime_seconds']);
					$duration_string=gmdate("H:i:s",$duration);
					$bitrate=$videodata['bitrate'];

					//$mimetype=$videodata['mime_type'];

					$mimetype="";

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

					$aspect_ratio_id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."aspect_ratio WHERE aspect_float_value=?",array($aspect_ratio_value));
				}
			}
			else if($type ==14)
			{
				$existing_dimensions=explode(',',$this->read_post_param('existing_dimensions'));

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

				if($noimage_flag ==0)
				{
	                  $res11=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =4 order by width ASC");

	                  $time =$currentTime;

	                  $filename ='';
                      $filenames=array();

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

                        $filename= json_encode($filenames);
					}
				}

			$additionalBannerUploadArray  = array();
			$additionalBannerIssueFlag    = 0;
			$additionalBannerIssueMessage = 0;
			$responsive_enabled           = $this->get_addon_status('responsive-ads_enabled');


			if($responsive_enabled == 1 && $pricing == 3 && ($type==2 || $type==11) && $additional_banners != "")
			{
				$additionalBannerArray = explode(',',$additional_banners);

				foreach($additionalBannerArray as $adKey=>$adValue)
				{

					if($_FILES['banner_responsive_'.$adValue]['name'] != "")
					{
						$responsiveExtension     = explode(".",$_FILES['banner_responsive_'.$adValue]['name']);
						$responsiveExtensionName = strtolower($responsiveExtension[count($responsiveExtension)-1]);

						if($responsiveExtensionName != "gif" && $responsiveExtensionName != "jpeg" && $responsiveExtensionName != "pjpeg" && $responsiveExtensionName != "png" && $responsiveExtensionName != "svg" && $responsiveExtensionName != "jpg")
						{
							$dimensionValue = str_replace("-", " x ",$this->get_banner_dimension($adValue));

							$additionalBannerIssueFlag    = 1;
							$additionalBannerIssueMessage = $this->get_message("uploaded banner extension not supported in responsive banner",array("x"=>$dimensionValue));
						}
						else if($_FILES['banner_responsive_'.$adValue]["size"]/1024 > $filesize)
						{
							$dimensionValue = str_replace("-", " x ",$this->get_banner_dimension($adValue));

							$additionalBannerIssueFlag    = 2;
							$additionalBannerIssueMessage = $this->get_message("uploaded banner size is high in responsive banner",array("x"=>$dimensionValue));
						}
						else if($_FILES['banner_responsive_'.$adValue]["error"] > 0)
						{
							$dimensionValue = str_replace("-", " x ",$this->get_banner_dimension($adValue));

							$additionalBannerIssueFlag    = 3;
							$additionalBannerIssueMessage = $this->get_message("uploaded banner is corrupted in responsive banner",array("x"=>$dimensionValue));
						}

						if($additionalBannerIssueFlag > 0)
						break;
						else
						{
							$dimensionValue  = $this->get_banner_dimension($adValue);
							$dimensionArray  = explode("-",$dimensionValue);

							$dimensionWidth  = 0;
							$dimensionHeight = 0;

							if(isset($dimensionArray[0]))
							$dimensionWidth  = $dimensionArray[0];

							if(isset($dimensionArray[1]))
							$dimensionHeight = $dimensionArray[1];


							$additionalBannerUploadArray[$adValue][0] = $adValue.'-'.$currentTime.'.'.$responsiveExtensionName;  //File name
							$additionalBannerUploadArray[$adValue][1] = 'banner_responsive_'.$adValue;//File upload field name

							$additionalBannerUploadArray[$adValue][2] = $dimensionWidth;
							$additionalBannerUploadArray[$adValue][3] = $dimensionHeight;
						}
					}
				}

				if($additionalBannerIssueFlag == 0 && $_FILES['banner']['name'] !="") //For default banner
				{
					$additionalBannerUploadArray[$bannerid][0] = $bannerid.'-'.$currentTime.'.'.$extensionname;  //File name
					$additionalBannerUploadArray[$bannerid][1] = 'banner';//File upload field name

					$additionalBannerUploadArray[$bannerid][2] = $width1;
					$additionalBannerUploadArray[$bannerid][3] = $height1;
				}
			}


				if($additionalBannerIssueFlag > 0)
				$this->set_notice($additionalBannerIssueMessage);
				else if(($type==2 || $type==11) && $filename !="" && $banner_type == 0 && ($extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg"))
				$this->set_notice("uploaded banner extension not supported");
				else if(($type==2 || $type==11) && $filename !="" && $banner_type == 0 && $_FILES["banner"]["size"]/1024 > $filesize)
				$this->set_notice("uploaded banner size is high");
				else if(($type==2 || $type==11) && $filename !="" && $banner_type == 0 && $_FILES["banner"]["error"] > 0)
				$this->set_notice("uploaded banner is corrupted");

				elseif($type==2 && $expandable ==1 && $expandable_filename !="" && $banner_type == 0 && $expandable_extensionname != "gif" && $expandable_extensionname != "jpeg" && $expandable_extensionname != "pjpeg" && $expandable_extensionname != "png" && $expandable_extensionname != "svg" && $expandable_extensionname != "jpg")
				$this->set_notice("uploaded expandable banner extension not supported");
				else if($type==2 && $expandable ==1 && $expandable_filename !="" && $banner_type == 0 && $_FILES["expandable_banner_".$bannerid]["size"]/1024 > $filesize)
				$this->set_notice("uploaded expandable banner size is high");
				else if($type==2 && $expandable ==1 && $expandable_filename !="" && $banner_type == 0 && $_FILES["expandable_banner_".$bannerid]["error"] > 0)
				$this->set_notice("uploaded expandable banner is corrupted");



				else if($type==2 && $filename !="" && $banner_type == 1 && $extensionname != "zip")
				$this->set_notice("html5 file format not supported");
				else if($type==2 && $filename !="" && $banner_type == 1 && $_FILES["banner"]["size"]/1024 > $html5_filesize)
				$this->set_notice("html5 file size not supported");
				else if($type==2 && $filename !="" && $banner_type == 1 && $_FILES["banner"]["error"] > 0)
				$this->set_notice("html5 file upload failed");



				else if($type==2 && $expandable_filename !="" && $banner_type == 1 && $expandable ==1 && $expandable_extensionname != "zip")
				$this->set_notice("expandable html5 file format not supported");
				else if($type==2 && $expandable_filename !="" && $banner_type == 1 && $expandable ==1 && $_FILES["expandable_banner_".$bannersize]["size"]/1024 > $html5_filesize)
				$this->set_notice("expandable html5 file size not supported");
				else if($type==2 && $expandable_filename !="" && $banner_type == 1 && $expandable ==1 && $_FILES["expandable_banner_".$bannersize]["error"] > 0)
				$this->set_notice("expandable html5 file upload failed");





				else if($type==7 && $result11['ecommerce_support'] ==0)
				$this->set_notice("ad format not supported in this dimension");
				else if($type == 7 && $ecommercelogo !="" && $logoextensionname != "gif" && $logoextensionname != "jpeg" && $logoextensionname != "pjpeg" && $logoextensionname != "png" && $logoextensionname != "svg" && $logoextensionname != "jpg")
				$this->set_notice("uploaded logo image extension not supported");
				else if($type == 7 && $ecommercelogo !="" && $_FILES["ecommercelogo"]["size"]/1024 > $filesize)
				$this->set_notice("uploaded logo image size is high");
				else if($type == 7 && $ecommercelogo !="" && $_FILES["ecommercelogo"]["error"] > 0)
				$this->set_notice("uploaded logo image is corrupted");
				//else if($type == 7 && $ecommerce_parent ==0 && $file_type ==0 && $csvextensionname != "csv")
				//$this->set_notice("uploaded file format not supported");
				//else if($type == 7 && $ecommerce_parent ==0 && $file_type ==0 && $_FILES["csvfile"]["error"] > 0)
				//$this->set_notice("csv file upload failed");
				else if($type ==7 && $ecommerce_parent ==0 && $file_type ==1 && $ecommerce_flag ==0)
				$this->set_notice("please fill ecommerce popup");



				else if($type ==13 && $videofile !="" && $linear_support ==1 && $html5_player_support ==1 && $videoextensionname != "mp4" && $videoextensionname != "flv" && $videoextensionname != "wmv" && $videoextensionname != "mpg" && $videoextensionname != "avi" && $videoextensionname != "webm" && $videoextensionname != "ogg")
				$this->set_notice("uploaded video file extension not supported");
				else if($type ==13 && $videofile !="" && $linear_support ==1 && $videoextensionname != "mp4" && $videoextensionname != "flv" && $videoextensionname != "wmv" && $videoextensionname != "mpg" && $videoextensionname != "avi")
				$this->set_notice("uploaded video file extension not supported");
				else if($type ==13 && $videofile !="" && $html5_player_support ==1 && $videoextensionname != "mp4" && $videoextensionname != "webm" && $videoextensionname != "ogg")
				$this->set_notice("uploaded video file extension not supported");

				else if($type ==13 && $videofile !="" && $videofile_temp_name =="" || $videosize > $videomaxsize)
				$this->set_notice("uploaded video file size is high");
				else if($type ==13 && $videofile !="" && !in_array($aspect_ratio_value,$aspect_ratio_array))
				$this->set_notice("video aspect ratio not supported");
				else if($type ==13 && $videofile !="" && $duration > $videomaxduration)
				$this->set_notice("video duration high");

				else if($type ==14 && $noimage_flag ==1)
				$this->set_notice("please upload skin banners for all dimensions");
				else if($type ==14 && $skin_erro_flag ==1)
				$this->set_notice("uploaded banner extension not supported");
	            else if($type ==14 && $skin_erro_flag ==2)
	            $this->set_notice("uploaded banner size is high");
				else if($type ==14 && $skin_erro_flag==3)
	            $this->set_notice("uploaded banner is corrupted");
				else
				{

					$zip_uploaded = 0;
					$zip_exp_uploaded = 0;



					$append_string0="";
					$append_string2=array();


                    $ads_string0="";
					$ads_string2=array();


					$append_string2[]=$name;
					$append_string2[]=$adstatus;
					$append_string2[]=$title;
					$append_string2[]=$desc;
					$append_string2[]=$displayurl;
					$append_string2[]=$cta_button_text;
					$append_string2[]=$clickurl;
					$append_string2[]=$currentTime;

					if($adstatus <= 0)
					$ads_string2[]=0;
					else
					$ads_string2[]=1;

					if($type ==1 || $type ==2)
					{
						$append_string0.=",video_player_support=?";

						$ads_string0.=",video_player_support=b'?'";

						$append_string2[]=$vast_support;

	                    $ads_string2[]=$vast_support;
					}


					if($filename !="" && ($type ==2 || $type ==11 || $type ==14 || $type == 18))
					{
						$zip_uploaded = 1;

						if($banner_type == 1)
						$filename = "index.html";

						$append_string0.=",banner_id=?,banner=?,html5=? ";
						$ads_string0.=",banner_id=?,html5=b'?' ";

						$append_string2[]=$bannerid;
						$append_string2[]=$filename;
						$append_string2[]=$banner_type;

						$ads_string2[]=$bannerid;
						$ads_string2[]=$banner_type;

						if($type ==2 || $type ==11 || $type == 18)
						{
							$append_string0.=",mime_type=? ";
							$append_string2[]=$mimetype;
						}
					}


					if($type==2 && $expandable_enabled ==1)
					{
						$append_string0.=",expandable=?,html5=? ";

	                    $ads_string0.=",expandable=b'?',html5=b'?' ";

	                    $append_string2[]=$expandable;
						$append_string2[]=$banner_type;

	                    $ads_string2[]=$expandable;
	                    $ads_string2[]=$banner_type;

						if(($old_expandable ==1 && $expandable ==0) || $expandable_filename !="")
						{
							$zip_exp_uploaded = 1;

							if($banner_type == 1)
							$expandable_filename = "index.html";

							$append_string0.=",expandable_banner=?";

							$append_string2[]=$expandable_filename;
						}
					}



					if($videofile !="" && $type ==13)
					{
						$append_string0.=",banner=?,video_width=?,video_height=?,mime_type=?,bitrate=?,duration=?,duration_seconds=?,aspect_ratio=?";

						$ads_string0.=",video_width=?,video_height=?,bitrate=?,duration_seconds=?,aspect_ratio=?";

						$append_string2[]=$videofile;
						$append_string2[]=$videowidth;
						$append_string2[]=$videoheight;
						$append_string2[]=$mimetype;
						$append_string2[]=$bitrate;
						$append_string2[]=$duration_string;
						$append_string2[]=$duration;
						$append_string2[]=$aspect_ratio_id;


						$ads_string2[]=$videowidth;
						$ads_string2[]=$videoheight;
						$ads_string2[]=$bitrate;
						$ads_string2[]=$duration;
						$ads_string2[]=$aspect_ratio_id;
					}

					$append_string2[]=$aid;
					$ads_string2[]=$aid;

					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,status=?,title=?,description=?,display_url=?,cta_button_text=?,click_url=?,updation_time=?".$append_string0." WHERE id=?",$append_string2);


					if($res->error =="")
					{
	                    $ads_upd=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache set status=b'?'".$ads_string0." WHERE id=?",$ads_string2);

						if($type ==2 || $type ==7 || $type ==11 || $type ==12 ||  $type ==13 || $type ==14 || $type == 18)
						{
							if(!is_dir(DATA_DIR))
							mkdir(DATA_DIR,0777);
						}


						if($pricing !=3)
						{
							if($retargeting_enabled ==1 && ($pricing ==0 || ($pricing ==6 && $type != 12)))
							{
								$retarget_upd=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET retargeting=? WHERE id=?",array($retargeting,$aid));
								if($retarget_upd->error =="")
								{
                                   $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET retargeting=b'?' WHERE id=?",array($retargeting,$aid));

	                            }

	                            if($retargeting ==0)
	                            $db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE aid=?",array($aid));
							}
						}


						if($type ==9 || $type ==21)
						{
							if($from ==1){?>
								<script type="text/javascript">
								window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
								</script>
							<?php }
							else
							$this->set_notice("successfully updated the ad content",1);
						}
						else if($type ==12)
						{
							/************** For Top 10 Keyword Insertion ************/
							$contentdata=$title.' '.$desc;
							$toparray=$this->get_top_keywords($contentdata);

							$okstatus=0;
							$key_status=Configuration::get_instance()->read('keyword_default_status');


							$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE aid=?",array($aid));

							foreach($toparray as $k1=>$v1)
							{
								$key=trim($k1);
								$keyid=$db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=?",array($key));

								if($keyid =="" && $key !="")
								{
									$res1234=$db->execute_query("INSERT INTO ".TABLE_PREFIX."keywords (keyword,create_time,status) values (?,?,?)",array($key,$currentTime,$key_status));

									$kid=$res1234->get_last_id();
									$okstatus=$key_status;
								}
								else if($keyid >0)
								{
									$row=$db->execute_query("select * from ".TABLE_PREFIX."keywords where keyword=?",array($key));
									$rowdata=$row->fetch_assoc();

									$kid=$rowdata['id'];
									$okstatus=$rowdata['status'];
								}

								$id1=$db->read_single_column("select id from ".TABLE_PREFIX."ad_keyword_mapping where kid=? and aid=?",array($kid,$aid));

								if($id1 =="" && $okstatus !=0)
								$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) values (?,?,?)",array($uid,$aid,$kid));
							}

							/************** For Top 10 Keyword Insertion ************/

							$failedcount=0;

							while($dimdata=$res1->fetch_assoc())
							{
								$filesize=$dimdata['filesize'];
								$height1=$dimdata['height'];
								$width1=$dimdata['width'];
								$banid=$dimdata['id'];


								if($_FILES["banner_".$banid]['name'] !='')
								{
									$extension=explode(".",$_FILES["banner_".$banid]['name']);
									$extensionname=strtolower($extension[count($extension)-1]);

									if(($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "svg" || $extensionname == "jpg") && ($_FILES["banner_".$banid]["size"]/1024 <= $filesize))
									{
										if($_FILES["banner_".$banid]["error"] == 0)
										{
											if(!is_dir(DATA_DIR.'/banners/'))
											mkdir(DATA_DIR.'/banners/',0777);

											if(!is_dir(DATA_DIR.'/banners/'.$aid))
											mkdir(DATA_DIR.'/banners/'.$aid,0777);


											$filename=$banid.'-banner.'.$extensionname;


											$map_row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=? AND banner_size=?",array($aid,$banid));
											$map_data=$map_row->fetch_assoc();


											$already=0;
											$old_image="";

											if(isset($map_data) && count($map_data) >0)
											{
												$already=intval($map_data['id']);
												$old_image=$map_data['image'];
											}


											if($already >0)
											$res3=$db->execute_query("UPDATE ".TABLE_PREFIX."ad_image_mapping SET status=1,image=? WHERE id=?",array($filename,$already));
											else
											$res3=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_image_mapping (uid,aid,banner_size,width,height,image) values (?,?,?,?,?,?)",array($uid,$aid,$banid,$width1,$height1,$filename));



											if($res3->error =="")
											{
												if(move_uploaded_file($_FILES["banner_".$banid]["tmp_name"],DATA_DIR.'/banners/'.$aid.'/'.$banid.'-banner.'.$extensionname))
												{
													if($already >0)
													{
														if($old_image != $banid.'-banner.'.$extensionname)
														unlink(DATA_DIR.'/banners/'.$aid.'/'.$old_image);
													}

													$image=new ImageHelper(DATA_DIR.'/banners/'.$aid.'/'.$banid.'-banner.'.$extensionname);
													$image->resize($width1,$height1,DATA_DIR.'/banners/'.$aid.'/'.$banid.'-banner.'.$extensionname);
												}
												else
												$failedcount=$failedcount+1;
											}
										}
										else
										$failedcount=$failedcount+1;
									}
									else
									$failedcount=$failedcount+1;
								}
							}

							if($from ==1){?>
								<script type="text/javascript">
								window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
								</script>
							<?php }
							else
							{
								if($failedcount >0)
								$this->set_notice($this->get_message('some image upload failed',array('x'=>$failedcount)));
								else
								$this->set_notice("successfully updated the ad content",1);
							}
						}
						else if($type ==1)
						{
							if($from ==1){?>
								<script type="text/javascript">
								window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
								</script>
							<?php }
							else
							$this->set_notice("successfully updated the ad content",1);
						}
						else if($type ==2 || $type ==11)
						{
							$success_flag=0;
							$otherfile=0;
							$ctaflag=0;
							$ctainclude=1;
							$ctaflag_expandable=0;
							$failed_flag=0;

							if($banner_type == 0)
							{
								if($pricing == 3 && count($additionalBannerUploadArray) > 0)
								{
									if($type ==2 && $old_expandable ==1 && $expandable ==0)
									unlink(DATA_DIR."/".$aid."_exp_".$old_exp_name);

									$success_flag=0;
									$failed_flag=0;

									if($filename =="" && $expandable_filename =="")
									$success_flag=1;

									if($filename !="")
									{
										if(move_uploaded_file($_FILES["banner"]["tmp_name"],DATA_DIR."/".$aid."_".$filename))
										{
											if($old_name != $filename)
											unlink(DATA_DIR."/".$aid."_".$old_name);

											$image=new ImageHelper(DATA_DIR."/".$aid."_".$filename);
											$image->resize($width1,$height1,DATA_DIR."/".$aid."_".$filename);

											$success_flag=1;
										}
										else
										$failed_flag=1;
									}

									$image_upload_flag = 0;
									$bannerDataArray   = array();

									if($success_flag == 1)
									{
										if(!is_dir(DATA_DIR."/".$aid))
										mkdir(DATA_DIR."/".$aid,0777);

										foreach($additionalBannerUploadArray as $keyData => $valueData)
										{
											$fileNameData = $valueData[0];
											$fieldName    = $valueData[1];
											$fileWidth    = $valueData[2];
											$fileHeight   = $valueData[3];

											if($fieldName == "banner")
											{
												if(copy(DATA_DIR."/".$aid."_".$filename,DATA_DIR."/".$aid."/".$fileNameData))
												{
													$bannerDataArray[$keyData] = $fileNameData;
												}
												else
												{
													$image_upload_flag=1;
												    break;
												}
											}
											else
											{
												if(move_uploaded_file($_FILES[$fieldName]["tmp_name"],DATA_DIR."/".$aid."/".$fileNameData))
												{
													$image=new ImageHelper(DATA_DIR."/".$aid."/".$fileNameData);
													$image->resize($fileWidth,$fileHeight,DATA_DIR."/".$aid."/".$fileNameData);

													$bannerDataArray[$keyData] = $fileNameData;
												}
												else
												{
													$image_upload_flag=1;
													break;
												}
											}
										}


										if(count($bannerDataArray) > 0)
										{
											$tempNewArray     = array();
											$tempRemoveArray  = array();

											if(count($oldBannerListArray) > 0)
											{
												foreach($oldBannerListArray as $oKey=>$oValue)
												{
													if(!in_array($oKey, $bannerDataArray))
													$tempNewArray[$oKey]        = $oValue;
												    else
												    {
												    	$tempRemoveArray[$oKey] = $oValue;
												    	$tempNewArray[$oKey]    = $bannerDataArray[$oKey];
												    }


												    $arrayDifference = array_diff_key($bannerDataArray, $tempNewArray);

												    if(count($arrayDifference) > 0)
												    {
												    	foreach($arrayDifference as $dKey=>$dValue)
														{
															$tempNewArray[$dKey]        = $dValue;
														}
												    }
												}
											}
											else
											$tempNewArray = $bannerDataArray;


											$bannerDataJSON = json_encode($tempNewArray);

											$bannerUpdate = $db->execute_query("UPDATE ".TABLE_PREFIX."ads SET banner_list = ? WHERE id = ?",array($bannerDataJSON,$aid));

											if($bannerUpdate->error != "")
											$image_upload_flag = 1;
										    else
										    {
										    	foreach($tempRemoveArray as $trKey=>$trValue)
										    	{
										    		unlink(DATA_DIR."/".$aid."/".$trValue);
										    	}
										    }
										}
									}

									if($image_upload_flag == 0)
									$success_flag     = 1;
									else
									{
										$success_flag = 0;
										$failed_flag  = 1;
									}


									if($failed_flag == 1)
									$this->set_notice($this->get_message('image upload failed'));


								}
								else
								{
									if($type ==2 && $old_expandable ==1 && $expandable ==0)
									unlink(DATA_DIR."/".$aid."_exp_".$old_exp_name);

									$success_flag=0;
									$failed_flag=0;

									if($filename =="" && $expandable_filename =="")
									$success_flag=1;

									if($filename !="")
									{
										if(move_uploaded_file($_FILES["banner"]["tmp_name"],DATA_DIR."/".$aid."_".$filename))
										{
											if($old_name != $filename)
											unlink(DATA_DIR."/".$aid."_".$old_name);

											$image=new ImageHelper(DATA_DIR."/".$aid."_".$filename);
											$image->resize($width1,$height1,DATA_DIR."/".$aid."_".$filename);

											$success_flag=1;
										}
										else
										{
											$failed_flag=1;

											$this->set_notice($this->get_message('image upload failed'));
										}
									}

									if($type ==2 && $expandable_filename !="" && $failed_flag ==0)
									{
										if(move_uploaded_file($_FILES["expandable_banner_".$bannerid]["tmp_name"],DATA_DIR."/".$aid."_exp_".$expandable_filename))
										{
											if($old_exp_name != $expandable_filename && $old_expandable == 1)
											unlink(DATA_DIR."/".$aid."_exp_".$old_exp_name);

											$image=new ImageHelper(DATA_DIR."/".$aid."_exp_".$expandable_filename);
											$image->resize($expandable_width,$expandable_height,DATA_DIR."/".$aid."_exp_".$expandable_filename);

											$success_flag=1;
										}
										else
										{
											$failed_flag=1;

											$this->set_notice($this->get_message('expandable image file upload failed'));
										}
									}
								}
							}
							else
							{
										if($type ==2 && $old_expandable ==1 && $expandable ==0)
										{
											if($oldbanner_type == 1)
											$this->remove_files(DATA_DIR.'/html5/'.$aid.'-exp/');
											else
											unlink(DATA_DIR."/".$aid."_exp_".$old_exp_name);
										}


										if($zip_uploaded == 1 || $zip_exp_uploaded == 1)
										{
											$filename='html5.zip';
											$expandable_filename='exp-html5.zip';

											$supported_folders=Configuration::get_instance()->read('allowed_folders');
											$supported_formats=Configuration::get_instance()->read('allowed_file_formats');
											$allowed_image_file_extensions=Configuration::get_instance()->read('allowed_image_file_extensions');
											$allowed_video_file_extensions=Configuration::get_instance()->read('allowed_video_file_extensions');
											$allowed_font_file_extensions=Configuration::get_instance()->read('allowed_font_file_extensions');




											$validate_zip_folder_structure=Configuration::get_instance()->read('validate_zip_folder_structure');
											$support_remote_urls=Configuration::get_instance()->read('support_remote_urls');




											$supported_array			= explode(',',$supported_formats);
											$supported_folder_array		= explode(',',$supported_folders);
											$supported_image_array		= explode(',',$allowed_image_file_extensions);
											$supported_video_array		= explode(',',$allowed_video_file_extensions);
											$supported_font_array		= explode(',',$allowed_font_file_extensions);


											$supported_array=array_merge($supported_array,$supported_image_array);
											$supported_array=array_merge($supported_array,$supported_video_array);
											$supported_array=array_merge($supported_array,$supported_font_array);


											if(!is_dir(DATA_DIR))
											mkdir(DATA_DIR,0777);


											if(!is_dir(DATA_DIR.'/tmp-html-data/'))
											mkdir(DATA_DIR.'/tmp-html-data/',0777);


											if($zip_uploaded == 1)
											{
												if(!is_dir(DATA_DIR.'/tmp-html-data/'.$aid.'/'))
												mkdir(DATA_DIR.'/tmp-html-data/'.$aid.'/',0777);


												$before_extraction=DATA_DIR.'/tmp-html-data/'.$aid.'/'.$filename;
												$after_extraction=DATA_DIR.'/tmp-html-data/'.$aid.'/';



												/*************================****************/
												if(is_dir($after_extraction."html5/"))
												$this->remove_files($after_extraction.'html5/');
												/*************================****************/


												if(move_uploaded_file($_FILES["banner"]["tmp_name"],$before_extraction))
												{
													$zip = new ZipArchive;

													if($zip->open($before_extraction))
													{
														$zip->extractTo($after_extraction);
														$zip->close();


														$folders_inside_zip=array_diff(scandir($after_extraction), array('..', '.','html5.zip'));   /*************================****************/

														$enter_flag = 0;		/*************================****************/


														foreach($folders_inside_zip as $each_folder)
														{
															if(count($folders_inside_zip) == 1 && is_dir($after_extraction.$each_folder))   /*************================****************/
															{
																rename($after_extraction.$each_folder,$after_extraction."html5");

																$enter_flag = 1;		/*************================****************/
															}
														}


														/*************================****************/
														if($enter_flag == 0)
														{
															if(!is_dir($after_extraction."html5/"))
															mkdir($after_extraction."html5/",0777);

															foreach($folders_inside_zip as $each_folder)
															{
																rename($after_extraction.$each_folder,$after_extraction."html5/".$each_folder);
															}
														}
														/*************================****************/




														if(is_dir($after_extraction))
														{
															if($validate_zip_folder_structure == 1)
															{
																$folder_lists=$this->get_folders($after_extraction.'html5/');

																$folder_difference=array_diff($folder_lists,$supported_folder_array);

																if(count($folder_difference) >0)
																$otherfile=21;                //Unwanted folders
															}


															if($otherfile ==0)
															{
																$extensionarray=array();
																$folder_files=$this->get_files($after_extraction.'html5/');


																foreach($folder_files as $fkey=>$fvalue)
																{
																	$fvaluearray=explode('.',$fvalue);

																	$extension_name=strtolower($fvaluearray[count($fvaluearray)-1]);

																	if(!in_array($extension_name,$supported_array))
																	{
																		if(unlink($fvalue))
																		{

																		}
																		else
																		$extensionarray[]=$extension_name;
																	}
																	else
																	$extensionarray[]=$extension_name;
																}

																$difference=array_diff($extensionarray,$supported_array);

																if(count($difference) >0)
																$otherfile=1;                //Unwanted files in folder
															}




															if($otherfile ==0)
															{
																if(!file_exists($after_extraction.'html5/index.html'))
																$otherfile=2;                //Index.html file missing

																if($otherfile ==0)
																{
																	foreach($folder_files as $fkey1=>$fvalue1)
																	{
																		$scriptarray=array();
																		$linkarray=array();

																		$extension=explode('.',$fvalue1);
																		$extension1=strtolower($extension[count($extension)-1]);


																		if($extension1 == 'html' || $extension1 == 'js')   //for change
																		{
																			$filedata=$this->fetch_file_contents($fvalue1);


																			if($support_remote_urls == 0)
																			{
																				$otherfile=$this->validate_file_contents($filedata);

																				if($otherfile >0)
																				break;
																			}



																			if($otherfile ==0) // For check if cta.js included
																			{
																				$scriptarray=$this->get_page_script_tag($filedata);

																				$str_position=strpos($filedata,'javascript:xyz_cta.trigger()');

																				if($str_position === false)
																				{}
																				else
																				$ctaflag=1;

																				if($fvalue1 == $after_extraction.'html5/index.html')
																				{
																					if(!in_array(BASE.DISPLAY_DIR."/js/cta.js",$scriptarray))
																					$ctainclude=0;
																				}
																			}
																	   }
																	}

																	if($otherfile ==0 && $ctaflag ==1 && $ctainclude ==0)
																	$otherfile=3;                //Please include cta.js file for click tracking in index.html file
																}
															}
														}

														if($otherfile >0)
														$this->remove_files($after_extraction);

														if($otherfile ==0)
														{
															if(!is_dir(DATA_DIR.'/html5/'))
															mkdir(DATA_DIR.'/html5/',0777);

															if(!is_dir(DATA_DIR.'/html5/'.$aid.'/'))
															mkdir(DATA_DIR.'/html5/'.$aid.'/',0777);

															$success_flag=1;


															if($oldbanner_type == 0)
															unlink(DATA_DIR."/".$aid."_".$old_name);

															if($type ==2 && $old_expandable ==1 && $oldbanner_type == 0)
															unlink(DATA_DIR."/".$aid."_exp_".$old_exp_name);

															if($oldbanner_type == 1)
															$this->remove_files(DATA_DIR.'/html5/'.$aid.'/');





															$this->recurse_copy($after_extraction,DATA_DIR.'/html5/'.$aid.'/');

															if(file_exists(DATA_DIR.'/html5/'.$aid.'/html5.zip'))
															unlink(DATA_DIR.'/html5/'.$aid.'/html5.zip');

															$upd_qry=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET html5=1,cta_support=?,upload_success=1 WHERE id=?",array($ctaflag,$aid));
															if($upd_qry->error =="")
															{
 																$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET html5=1,cta_support=b'?',upload_success=1 WHERE id=?",array($ctaflag,$aid));
															}
														}
													}
													else
													$error_flag=4;
												}
												else
												$error_flag=4;

												$this->remove_files($after_extraction);

											}




											if($zip_exp_uploaded == 1)
											{
												//if($old_name != $fname)
												//unlink(DATA_DIR."/".$aid."_".$old_name);


												//if($type ==2 && $old_expandable ==1 && $expandable ==0)
												//unlink(DATA_DIR."/".$aid."_exp_".$old_exp_name);


												//if($old_exp_name != $expandable_filename)
												//unlink(DATA_DIR."/".$aid."_exp_".$old_exp_name);

											}



										}
										else
										$success_flag=1;
							}

							if($otherfile ==1)
							$this->set_notice($this->get_message('unwanted files in folder'));
							else if($otherfile ==2)
							$this->set_notice($this->get_message('index.html file missing'));
							else if($otherfile ==3)
							$this->set_notice($this->get_message('please include cta.js file in index.html'));
							else if($otherfile ==4)
							$this->set_notice($this->get_message('unwanted scripts / urls'));


							else if($otherfile ==5)
							$this->set_notice($this->get_message('unwanted <link> href'));
							else if($otherfile ==6)
							$this->set_notice($this->get_message('unwanted <base> tag'));
							else if($otherfile ==7)
							$this->set_notice($this->get_message('unwanted <meta> refresh'));
							else if($otherfile ==8)
							$this->set_notice($this->get_message('iframe not supported'));


							else if($otherfile ==9)
							$this->set_notice($this->get_message('unwanted image urls'));
							else if($otherfile ==10)
							$this->set_notice($this->get_message('unwanted href url'));

							else if($otherfile ==13)
							$this->set_notice($this->get_message('window / document open not supported'));


							else if($otherfile ==14)
							$this->set_notice($this->get_message('document.write not supported'));

							else if($otherfile ==15)
							$this->set_notice($this->get_message('ajax() not supported'));
							else if($otherfile ==16)
							$this->set_notice($this->get_message('send() not supported'));
							else if($otherfile ==17)
							$this->set_notice($this->get_message('XMLHttpRequest() not supported'));

							else if($otherfile ==18)
							$this->set_notice($this->get_message('alert() not supported'));
							else if($otherfile ==19)
							$this->set_notice($this->get_message('eval() not supported'));
							else if($otherfile ==20)
							$this->set_notice($this->get_message('unwanted third party urls'));
							else if($otherfile ==21)
							$this->set_notice($this->get_message('unwanted folders'));
							else
							{
								if($success_flag ==1 && $failed_flag ==0)
								{
									if($from ==1){?>
										<script type="text/javascript">
										window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
										</script>
									<?php }
									else
									$this->set_notice("successfully updated the ad content",1);
								}
							}
					}
					else if($type ==13)
					{
						$success_flag=0;

						if($videofile =="")
						$success_flag=1;
						else
						{
							if(!is_dir(DATA_DIR.'/video/'))
							mkdir(DATA_DIR.'/video/',0777);

							if(!is_dir(DATA_DIR.'/video/'.$aid.'/'))
							mkdir(DATA_DIR.'/video/'.$aid.'/',0777);

							if(move_uploaded_file($videofile_temp_name,DATA_DIR.'/video/'.$aid.'/'.$videofile))
							{
								if($old_name != $videofile)
								unlink(DATA_DIR."/video/".$aid."/".$old_name);

								$success_flag=1;
							}
							else
							$this->set_notice($this->get_message('video upload failed'));
						}


						if($success_flag ==1)
						{
							if($from ==1){?>
								<script type="text/javascript">
								window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
								</script>
							<?php }
							else
							$this->set_notice("successfully updated the ad content",1);
						}
					}
					else if($type == 18)
					{
						if($filename =="")
						$success_flag=1;
						else
						{
						if(!is_dir(DATA_DIR.'/notification_icons/'))
							mkdir(DATA_DIR.'/notification_icons/',0777);


						$notification_icon_width = Configuration::get_instance()->read('push_notification_icon_width');
						$notification_icon_height = Configuration::get_instance()->read('push_notification_icon_height');
						if(move_uploaded_file($_FILES["notify_icon_image"]["tmp_name"],DATA_DIR."/notification_icons/".$aid."_".$filename))
							{
								if($old_name != $filename)
								unlink(DATA_DIR."/notification_icons/".$aid."_".$old_name);

								$success_flag=1;
								$image=new ImageHelper(DATA_DIR."/notification_icons/".$aid."_".$filename);
								$image->resize($notification_icon_width,$notification_icon_height,DATA_DIR."/notification_icons/".$aid."_".$filename);

							 }
							 else
							$this->set_notice($this->get_message('notification icon upload failed'));
						 }

						 if($success_flag ==1)
						{
							if($from ==1){?>
								<script type="text/javascript">
								window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
								</script>
							<?php }
							else
							$this->set_notice("successfully updated the ad content",1);
						}

				    }
					else if($type ==14)
					{
    					$success_flag=0;

						$filename_array=array();
						$fileremove_array=array();

						$filename_array=json_decode($old_name,true);

						if(count($filenames) >0)
						{
							if(!is_dir(DATA_DIR."/".$aid))
							mkdir(DATA_DIR."/".$aid,0777);


							foreach($filenames as $key => $filename)
							{
								if(move_uploaded_file($_FILES["skin_banner_".$key]["tmp_name"],DATA_DIR."/".$aid."/".$filename))
								{
									$image=new ImageHelper(DATA_DIR."/".$aid."/".$filename);
									$image->resize($width1,$height1,DATA_DIR."/".$aid."/".$filename);

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
							$this->set_notice($this->get_message('image upload failed'));
							else
							{
								foreach($fileremove_array as $rkey=>$rvalue)
								{
									unlink(DATA_DIR.'/'.$aid.'/'.$rvalue);
								}

								$success_flag=1;
							}
						}


						if($success_flag ==1)
						{
							if($from ==1){?>
								<script type="text/javascript">
								window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
								</script>
							<?php }
							else
							$this->set_notice("successfully updated the ad content",1);
						}
					}
					else if($type ==7)
					{
						$error_flag=0;

						$ecommerce_failed=0;
						$ecommerce_failed1=0;

						$csvempty=1;

						if($ecommerce_enabled ==1)
						{
							$old_array=array();
							$old_aid_array=array();

							$newarray=array();
							$removearray=array();
							$currentarray=array();
							$currentaidarray=array();
							$arraytemp=array();

							$baseaid=0;
							$layout_data=0;

							if($ecommerce_parent ==0)
							{
								$baseaid=$aid;

								$current_logo="";
								$logo_balance="";
								$logo_balance_old="";

								$rowdata123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE ecommerce_parent=?",array($aid));
								while($rowdata1234=$rowdata123->fetch_assoc())
								{
									$old_array[]=$rowdata1234['banner_id'];

									$old_aid_array[$rowdata1234['banner_id']]=$rowdata1234['id'];

									if($current_logo =="")
									{
										$current_logo=$rowdata1234['logo'];

										$length=strlen($rowdata1234['id'].'-');

										$logo_balance=substr($current_logo,$length);
										$logo_balance_old=$logo_balance;
									}
								}

								$newarray=array_diff($size_array,$old_array);

								$removearray=array_diff($old_array,$size_array);

								$currentarray=array_diff($old_array,$removearray);


								foreach($currentarray as $ckey2=>$cvalue2)
								{
									$layout_data=intval($this->read_post_param('layoutid_'.$cvalue2));

									if($layout_data >0)
									{
										$currentaidarray[]=$old_aid_array[$cvalue2];

										$ecommerce_string="";
										$ecommerce_array=array();
										$ecommerce_cache_string="";
										$ecommerce_cache_array=array();


										$ecommerce_array[]=$name;
										$ecommerce_array[]=$adstatus;
										$ecommerce_array[]=$layout_data;

                                        if($adstatus <=0)
                                     	$ecommerce_cache_array[]=0;
										else
										$ecommerce_cache_array[]=1;

										$ecommerce_cache_array[]=$layout_data;

										if($ecommercelogo !="")
										{
											$ecommerce_string=",logo=?";

											$ecommerce_array[]=$old_aid_array[$cvalue2].'-'.$ecommercelogo;
										}

										$ecommerce_array[]=$old_aid_array[$cvalue2];
										$ecommerce_cache_array[]=$old_aid_array[$cvalue2];

										$qry=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET name=?,status=?,display_layout=?".$ecommerce_string." WHERE id=?",$ecommerce_array);
										if($qry->error =="")
										{
    											$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET status=b'?',display_layout=? WHERE id=?",$ecommerce_cache_array);

										}
									}
								}


								foreach($newarray as $nkey=>$nvalue)
								{
									$layout_data=intval($this->read_post_param('layoutid_'.$nvalue));

									if($layout_data >0)
									{
										$ad_start_time = 0;


										$append_string0="";
										$append_string1="";
										$append_string2=array();

										$ads_string0="";
										$ads_string1="";
										$ads_string2=array();

										$append_string2[]=$uid;
										$append_string2[]=$name;
										$append_string2[]=$type;
										$append_string2[]=$adstatus;
										$append_string2[]=0;
										$append_string2[]=$nvalue;
										$append_string2[]=$layout_data;
										$append_string2[]=$aid;
										$append_string2[]=$adv_status;
										$append_string2[]=$pricing;
										$append_string2[]=-1;
										$append_string2[]=$ad_start_time;


                                        $ads_string2[]=$uid;
										$ads_string2[]=$type;
										if($adstatus <=0)
                                     		$ads_string2[]=0;
										else
											$ads_string2[]=1;
										$ads_string2[]=0;
										$ads_string2[]=$nvalue;
										$ads_string2[]=$layout_data;
										$ads_string2[]=$aid;
										$ads_string2[]=$adv_status;
										$ads_string2[]=$pricing;
										$ads_string2[]=0;

										if($refferal_status ==1)
										{
											$ads_string0.=",refferal_id,refferal_status";

   											$ads_string1.=",?,b'?'";


											$append_string0.=",refferal_id,refferal_status";
											$append_string1.=",?,?";

											$append_string2[]=$ref_id;
											$append_string2[]=$refferal_status;
								   			$ads_string2[]=$ref_id;
											$ads_string2[]=$refferal_status;
										}



										$resinsert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads (uid,name,type,status,pause_status,banner_id,display_layout,ecommerce_parent,user_status,display_type,pricing_status,start_date".$append_string0.") values (?,?,?,?,?,?,?,?,?,?,?,?".$append_string1.")",$append_string2);

										if($resinsert->error =="")
										{


											$resinsert_param="";
											$lastid=$resinsert->get_last_id();
											array_unshift($ads_string2,$lastid);
											$resinsert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_cache (id,uid,type,status,pause_status,banner_id,display_layout,ecommerce_parent,user_status,display_type,pricing_status".$ads_string0.") values (?,?,?,b'?',b'?',?,?,?,b'?',?,b'?'".$ads_string1.")",$ads_string2);
						          				$device_targetting_enabled     =$this->get_addon_status('device-targeting_enabled');
											$languge_targetting_enabled    =$this->get_addon_status('language-targeting_enabled');
											$category_targetting_enabled   =$this->get_addon_status('category-targeting_enabled');
											$connection_targetting_enabled =$this->get_addon_status('connectiontype-targeting_enabled');
											if($device_targetting_enabled ==1 || $device_targetting_enabled ==0)
											{
												$q=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_os=1 WHERE id=?",array($lastid));
												$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_browser=1 WHERE id=?",array($lastid));

											}
											if($languge_targetting_enabled ==1 || $languge_targetting_enabled ==0)
											{
												$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_languages=1 WHERE id=?",array($lastid));
											}
											if($category_targetting_enabled ==1 || $category_targetting_enabled ==0)
											{
												$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=1 WHERE id=?",array($lastid));
											}
											if($connection_targetting_enabled ==1 || $connection_targetting_enabled ==0)
											{
												$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_connections=1 WHERE id=?",array($lastid));
											}
											if($ecommercelogo !="")
											$logo_balance=$ecommercelogo;

											$db->execute_query("UPDATE ".TABLE_PREFIX."ads set logo=? where id=?",array($lastid.'-'.$logo_balance,$lastid));

											$currentaidarray[]=$lastid;

											$arraytemp[]=$lastid;
										}
									}
								}


								$qry1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,status=?,display_layout=?,banner_id=? where id=?",array($name,$adstatus,$layout_data,$bannerid,$baseaid));
				                if($qry1->error =="")
				                        {

                                        if($adstatus <=0)
                                     		$ad_status=0;
										else
											$ad_status=1;
								        $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache set status=b'?',display_layout=?,banner_id=? where id=?",array($ad_status,$layout_data,$bannerid,$baseaid));
                                		}
								$currentaidarray[]=$baseaid;
							}
							else
							{
								$layout_data=intval($this->read_post_param('layoutid_'.$current_banner));

								if($layout_data >0)
								{
									$currentaidarray[]=$aid;


									$qry2=$db->execute_query("UPDATE ".TABLE_PREFIX."ads set name=?,status=?,display_layout=? where id=?",array($name,$adstatus,$layout_data,$aid));
									if($qry2->error =="")
									{

										if($adstatus <= 0)
                                     		$ad_status=0;
										else
											$ad_status=1;

                                            $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache set status=b'?',display_layout=? where id=?",array($ad_status,$layout_data,$aid));
									}
								}
							}


							$iii=0;
							$aidfirst=0;

							foreach($currentaidarray as $ckey1=>$adid)
							{
								if($iii ==0)
								$aidfirst=$adid;

								if(in_array($adid,$arraytemp))
								{
								    $db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_geographic_mapping (uid,aid,country_code) values (?,?,?)",array($uid,$adid,0));
									$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_countries=b'?' WHERE id=? AND uid=?",array(1,$adid,$uid));


								}

								if($pricing !=3)
								{
									if($retargeting_enabled ==1 && ($pricing ==0 || $pricing ==6))
									{
										$qry3=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET retargeting=? WHERE id=?",array($retargeting,$adid));

										if($qry3->error =="")
										{
   						                                $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET retargeting=b'?' WHERE id=?",array($retargeting,$adid));
										}
										if($retargeting ==0)
										$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE aid=?",array($adid));
									}

									if(in_array($adid,$arraytemp))
									$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) VALUES (?,?,?)",array($uid,$adid,0));
								}

								$qry4=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET updation_time=?,headline_type=?,headline_text=?,headline_link=?,action_text=?,title_color=?,desc_color=?,url_color=?,border_color=?,background_color=?,ad_background_color=?,price_color=?,offer_price_color=?,ab_text_color=?,ab_background_color=?,ab_bhover_color=?,ah_text_color=?,abh_text_color=?,abh_background_color=?,abh_bhover_color=?,ad_selection_color=? WHERE id=?",array($currentTime,$htype,$hdata,$hlink,$callactiontext,$color1,$color2,$color3,$color4,$color5,$color6,$color7,$color8,$color9,$color10,$color11,$color12,$color13,$color14,$color15,$color16,$adid));


								if($iii ==0)
								{
									if(!is_dir(DATA_DIR.'/ecommerce'))
									mkdir(DATA_DIR.'/ecommerce',0777);

									if(!is_dir(DATA_DIR.'/ecommerce/logo'))
									mkdir(DATA_DIR.'/ecommerce/logo',0777);

									if(!is_dir(DATA_DIR.'/ecommerce/csv'))
									mkdir(DATA_DIR.'/ecommerce/csv',0777);
								}


								$logopath=DATA_DIR."/ecommerce/logo/".$adid."-".$ecommercelogo;
								$csvpath=DATA_DIR."/ecommerce/csv/".$adid."-".$csvfile;


								if($ecommerce_parent ==0 && $baseaid != $adid && ($ecommercelogo !="" || in_array($adid,$arraytemp)))
								{
									if($ecommercelogo !="")
									{
											if($iii ==0)
											{
												$old_logo_path=$logopath;

												if(move_uploaded_file($_FILES["ecommercelogo"]["tmp_name"],$logopath))
												{
													if($ecommercelogo != $logo_balance_old)
													unlink(DATA_DIR."/ecommerce/logo/".$adid."-".$logo_balance_old);
												}
												else
												{
													$error_flag=1;

													if($ecommercelogo != $logo_balance_old)
													unlink(DATA_DIR."/ecommerce/logo/".$adid."-".$logo_balance_old);
												}
											}
											else
											{
												if(copy($old_logo_path,$logopath))
												{
													if($ecommercelogo != $logo_balance_old)
													unlink(DATA_DIR."/ecommerce/logo/".$adid."-".$logo_balance_old);
												}
												else
												{
													$error_flag=1;

													if($ecommercelogo != $logo_balance_old)
													unlink(DATA_DIR."/ecommerce/logo/".$adid."-".$logo_balance_old);
												}
											}

									}
									else if($ecommercelogo =="")
									{
											$oldid=0;

											if(count($currentarray) >0)
											$oldid=$currentarray[0];
											else if(count($removearray) >0)
											$oldid=$removearray[0];



											$old_logo_path=DATA_DIR."/ecommerce/logo/".$old_aid_array[$oldid]."-".$logo_balance_old;

											$logopath=DATA_DIR."/ecommerce/logo/".$adid."-".$logo_balance_old;

											if(copy($old_logo_path,$logopath))
											{

											}
											else
											$error_flag=1;
									}
								}

								/*if($file_type ==0 && $ecommerce_parent ==0 && $error_flag ==0)
								{
									if($iii ==0)
									{
										$old_csv_path=$csvpath;

										if(move_uploaded_file($_FILES["csvfile"]["tmp_name"],$csvpath))
										{
											$csvarray=array();
											$i=0;
											$csv=fopen($csvpath,"r");
											while(!feof($csv))
											{
												$csvdata=fgetcsv($csv);

												if($i >0 && isset($csvdata) && $csvdata[0] !="")
												$csvarray[]=$csvdata;

												$i=$i+1;
											}
											fclose($csv);
										}
										else
										$error_flag=2;
									}
								}*/


								if($error_flag ==0 && $ecommerce_parent ==0 && $baseaid != $adid)
								{
									if(count($csvarray) ==0)
									$csvempty=0;

									//0=>Title
									//1=>Description
									//2=>Display Url
									//3=>Click Url
									//4=>Image Path
									//5=>Sale Price
									//6=>Offer Price
									//7=>Retargeting Url

									/********************** Remove Old Data *********************/
									$row123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($adid));
									while($row1234=$row123->fetch_assoc())
									{
										unlink(DATA_DIR.'/ecommerce/'.$adid.'/'.$row1234['ad_image']);
									}


									if(is_dir(DATA_DIR.'/ecommerce/'.$adid))
									rmdir(DATA_DIR.'/ecommerce/'.$adid.'/');

									$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($adid));

									if($retargeting_enabled ==1 && ($pricing ==0 || $pricing ==6))
									$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE aid=? AND uid=?",array($adid,$uid));


									/********************** Remove Old Data *********************/



									if(!is_dir(DATA_DIR.'/ecommerce/'.$adid))
									mkdir(DATA_DIR.'/ecommerce/'.$adid,0777);


									$ii=1;
									$imageinsert=0;
									$timedata=$currentTime;

									foreach($csvarray as $key=>$value)
									{

										$ecommerce_failed1=$ecommerce_failed1+$ecommerce_failed;

										$ecommerce_failed=0;

										$ad_retargeting_url='';

										$ad_title=substr(trim($value[0]),0,$maxtitle);
										$ad_description=substr(trim($value[1]),0,$maxdesc);
										$ad_display_url=substr(trim($value[2]),0,$maxdispurl);
										$ad_click_url=trim($value[3]);
										$ad_image_path=trim($value[4]);
										$ad_sale_price=trim($value[5]);
										$ad_offer_price=trim($value[6]);

										if(isset($value[7]))
										$ad_retargeting_url=trim($value[7]);




										if($ad_title =="" || $ad_description =="" || $ad_display_url =="" || $ad_click_url =="" || $ad_image_path =="")
										{
											$ecommerce_failed=$ecommerce_failed+1;
											continue;
										}
										else
										{

											$adimageextension=explode(".",$ad_image_path);
											$adimageextensionname=strtolower($adimageextension[count($adimageextension)-1]);


											if($adimageextensionname != "gif" && $adimageextensionname != "jpeg" && $adimageextensionname != "pjpeg" && $adimageextensionname != "png" && $adimageextensionname != "svg" && $adimageextensionname != "jpg")
											{
												$ecommerce_failed=$ecommerce_failed+1;
												continue;
											}
											else
											{
												$ad_image_name=$adid.'-'.$ii.'-'.$timedata.'.'.$adimageextensionname;

												$res123=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_list (uid,aid,ad_title,ad_description,ad_display_url,ad_click_url,ad_retargeting_url,ad_image,ad_image_path,ad_price,ad_offer_price) VALUES (?,?,?,?,?,?,?,?,?,?,?)",array($uid,$adid,$ad_title,$ad_description,$ad_display_url,$ad_click_url,$ad_retargeting_url,$ad_image_name,$ad_image_path,$ad_sale_price,$ad_offer_price));

												$lastrow=$res123->get_last_id();


												$filecontent=$this->fetch_file_contents($ad_image_path);

												if($filecontent !="")
												{
													$ecommercepath=DATA_DIR.'/ecommerce/'.$adid.'/'.$ad_image_name;

													if(file_put_contents($ecommercepath,$filecontent))
													{
														$currentsize=filesize($ecommercepath);
														$currentsize=$currentsize/1024;

														if($currentsize > $filesize)
														{
															$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($lastrow));

															unlink($ecommercepath);

															$ecommerce_failed=$ecommerce_failed+1;
														}
														else
														{

															if($retargeting_enabled ==1 && $retargeting ==1 && $ad_retargeting_url !="")
															{
																$rmsite=str_ireplace("http://","",$ad_retargeting_url);
																$rmsite=str_ireplace("https://","",$rmsite);
																$rmsite=str_ireplace("www.","",$rmsite);

																$rmurl=$rmsite;


																$stringlength=strlen($rmsite);

																$string="";
																for($i=0;$i < $stringlength;$i++)
																{
																	if($rmsite[$i] =='.' || ctype_alnum($rmsite[$i]))
																	$string.=$rmsite[$i];
																	else
																	break;
																}

																$rmsite=$string;



																if(UtilityHelper::is_valid_domain($rmsite))
																{
																	$rsid=$db->read_single_column("select id from ".TABLE_PREFIX."retargeting_sites where url=?",array($rmsite));

																	if(intval($rsid) ==0)
																	{
																		$query=$db->execute_query("INSERT INTO ".TABLE_PREFIX."retargeting_sites (uid,url) VALUES (?,?)",array($uid,$rmsite));

																		$rsid=$query->get_last_id();
																	}

																	if($rsid >0)
																	{
																		$rmcondition=str_ireplace($rmsite,"",$rmurl);

																		$rmlistid=$db->read_single_column("select id from ".TABLE_PREFIX."retargeting_lists WHERE sid=? AND uid=? AND conditions=? AND type=1",array($rsid,$uid,$rmcondition));

																		if(intval($rmlistid) ==0)
																		{
																			$rlinsert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."retargeting_lists (uid,sid,conditions,type) VALUES (?,?,?,?)",array($uid,$rsid,$rmcondition,1));

																			$rmlistid=$rlinsert->get_last_id();

																			$rmname='Ecommerce Ad Retargeting List - '.$rmlistid;

																			$db->execute_query("UPDATE ".TABLE_PREFIX."retargeting_lists SET name=? WHERE id=?",array($rmname,$rmlistid));
																		}

																		if($rmlistid >0)
																		$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_retargeting_mapping (uid,aid,alid,rid,rsid) VALUES (?,?,?,?,?)",array($uid,$adid,$lastrow,$rmlistid,$rsid));
																	}
																}
															}
														}
													}
													else
													{
														$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($lastrow));

														$ecommerce_failed=$ecommerce_failed+1;
													}
												}
												else
												{
													$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE id=?",array($lastrow));

													$ecommerce_failed=$ecommerce_failed+1;
												}
												$ii=$ii+1;
											}
										}
									}

									$ecommerce_failed1=$ecommerce_failed1+$ecommerce_failed;

								}
								$iii=$iii+1;
							}

							$removearraydata=array();

							if($ecommerce_parent ==0 && count($removearray) >0)
							{
								foreach($removearray as $r1=>$r2)
								{
									$removearraydata[]=$old_aid_array[$r2];
								}
							}

							//if($file_type ==0 && $ecommerce_parent ==0 && $old_csv_path !="" && file_exists($old_csv_path))
							//unlink($old_csv_path);

							if($ecommerce_parent ==0 && count($removearraydata) >0)
							$this->delete_ecommerce_ads($removearraydata);
						}

						if($error_flag ==0 && $csvempty ==1)
						{
							if($ecommerce_failed1 ==0)
							{
								if($from ==1){?>
									<script type="text/javascript">
									window.parent.location.href="<?php echo $this->make_url('ad/view/'.$aid.'/2')?>";
									</script>
								<?php }
								else
								{
									if($ecommerce_parent ==0 && $type ==7)
									$this->set_notice("successfully updated the ad content",1);
									else
									$this->set_notice("successfully updated the ad content",1);
								}
							}
							else
							{
								$this->set_notice($this->get_message('ecommerce file upload failed',array('x'=>$ecommerce_failed1)));
							}
						}
						else if($csvempty ==0)
						$this->set_notice($this->get_message('csv file empty'));
						else if($error_flag ==1)
						$this->set_notice($this->get_message('logo image upload failed'));
						else if($error_flag ==2)
						$this->set_notice($this->get_message('csv file upload failed'));
						else
						$this->set_notice($this->get_message('file upload failed'));
					}
				}
				else
				$this->set_notice("error occurred");
			}
		}
	}
	else
	{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($aid));
			$this->set_result("res",$res);


			$bannerdata=$res->fetch_assoc();
			$adtype=$bannerdata['type'];
			$bannerid=$bannerdata['banner_id'];
			$ecommerce_parent=$bannerdata['ecommerce_parent'];
			$banner_type=$bannerdata['html5'];
			$upload_success=$bannerdata['upload_success'];


			if($html5_enabled == 0 && $banner_type == 1)
			$banner_type = 0;


			if($adtype ==7)
			{
				$stringdata="";

				$listaid=$aid;
				if($ecommerce_parent ==0)
				{
					$rowdata123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE ecommerce_parent=?",array($aid));

					$iiii=0;
					while($rowdata1234=$rowdata123->fetch_assoc())
					{
						if($iiii ==0)
						$listaid=$rowdata1234['id'];

						if($stringdata !="")
						$stringdata.="-";

						$stringdata.=$rowdata1234['banner_id'];

						$this->set_variable('chk_diamension_'.$rowdata1234['banner_id'],1);
						$this->set_variable('layoutid_'.$rowdata1234['banner_id'],$rowdata1234['display_layout']);

						$iiii=$iiii+1;
					}
				}
				else
				{
						$stringdata=$bannerid;

						$this->set_variable('chk_diamension_'.$bannerid,1);
						$this->set_variable('layoutid_'.$bannerid,$bannerdata['display_layout']);
				}


				$this->set_variable('size_checked',$stringdata);



				$gres=$db->execute_query("select * from ".TABLE_PREFIX."ads_list where aid=? ORDER BY id ASC",array($listaid));

				$iii=1;
				$istring='';
				while($gres1=$gres->fetch_assoc())
				{
					$this->set_variable('ad_title_'.$iii,$gres1['ad_title']);
					$this->set_variable('ad_description_'.$iii,$gres1['ad_description']);
					$this->set_variable('ad_display_url_'.$iii,$gres1['ad_display_url']);
					$this->set_variable('ad_click_url_'.$iii,$gres1['ad_click_url']);
					$this->set_variable('image_url_'.$iii,$gres1['ad_image_path']);
					$this->set_variable('ad_retargeting_url_'.$iii,$gres1['ad_retargeting_url']);
					$this->set_variable('ad_price_'.$iii,$gres1['ad_price']);
					$this->set_variable('ad_offer_price_'.$iii,$gres1['ad_offer_price']);

					$imagepathdir=DATA_DIR.'/ecommerce/'.$listaid.'/'.$gres1['ad_image'];

					$this->set_variable('ad_image_'.$iii,$imagepathdir);


					if($istring !='')
					$istring.='-';

					$istring.=$iii;

					$iii=$iii+1;
				}


				$file_type=1;

				if($istring !='')
				$rowid=$istring;
			}
		}


		$currentBannerName    = $db->read_single_column("SELECT banner FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
		$this->set_variable('currentBannerName',$currentBannerName);

		if($bannerid >0)
		{
			$sizedata=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bannerid));
			$sizedatarow=$sizedata->fetch_assoc();

			$filesize=$sizedatarow['filesize'];

			$this->set_variable("maxsize",$filesize);
		}



		$this->set_variable('rowid',$rowid);
		$this->set_variable('file_type',$file_type);



		$this->set_variable("from",$from);

		$this->set_variable('pricing',$pricing);
		$this->set_variable('htype',$htype);
		$this->set_variable('hdata',$hdata);
		$this->set_variable('hlink',$hlink);
		$this->set_variable('callactiontext',$callactiontext);


		$this->set_variable('banner_type',$banner_type);
		$this->set_variable("upload_success",$upload_success);

		$this->set_variable('additional_banners',$additional_banners);
	}

	function delete_action()
	{
		$aid=$this->read_page_param(1);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$tp=$this->read_page_param(2);
		$st=$this->read_page_param(3);
		$adpricing=$this->read_page_param(4);
		$pg=$this->read_page_param(5);


		if(DEMO_MODE && $aid <= 100)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('ad/list'),0);
			exit;
		}


		$db= DAL::get_instance();

		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('ad/list'),0);
			exit;
		}

		$adp=$this->get_ad_pricing_value($aid);


		if($adp ==1 && $this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);



		$baseaid=$aid;
		$flagdata=0;
		$ad_parent=0;
		$adlist_array=array();

		$adtype=$this->get_ad_type_value($baseaid);

		$addata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE ecommerce_parent=?",array($aid));

		while($addatarow=$addata->fetch_assoc())
		{
			$adlist_array[]=$addatarow['id'];
		}

		$adlist_array[]=$aid;


		foreach($adlist_array as $key123=>$aid)
		{
			if($adtype ==7 && $aid == $baseaid && ($flagdata ==1 || $flagdata ==2))
			continue;


			if($adp !=3)
			{
				$data=$db->execute_query("select total_ad_budget,total_budget_used,pricing_status from ".TABLE_PREFIX."ads where id=?",array($aid));
				$data_row=$data->fetch_assoc();
				$status=$data_row['pricing_status'];

				if($status ==1)
				{
					if($adtype !=7)
					{
						$this->flash($this->get_message('active pricing exists'), $this->make_url('ad/list'),0);
						exit;
					}
					else
					{
						$flagdata=1;
						continue;
					}
				}
				else
				{
					$total_ad_budget = $data_row['total_ad_budget'];
					$total_budget_used = $data_row['total_budget_used'];
				}
			}





				$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

				$mapcount=0;
				if($sponsored_enabled ==1 || $sponsored_enabled ==0)
				$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE aid=? AND status = 2",array($aid));

				if($mapcount >0)
				{
					if($adtype !=7)
					{
						$this->flash($this->get_message('sponsored mappings ad exists'), $this->make_url('ad/list'),0);
						exit;
					}
					else
					{
						$flagdata=2;
						continue;
					}
				}



			if($adtype ==12)
			{
				$imagelist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($aid));

				while($imagerow=$imagelist->fetch_assoc())
				{
					unlink(DATA_DIR.'/banners/'.$aid.'/'.$imagerow['image']);
				}

				rmdir(DATA_DIR.'/banners/'.$aid);

				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($aid));
			}

			$old_data=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($aid));
			$old_data_row=$old_data->fetch_assoc();

			$old_type=$old_data_row['type'];
			$old_name=$old_data_row['banner'];
			$ecommercelogo=$old_data_row['logo'];
			$ad_parent=intval($old_data_row['ecommerce_parent']);
			$bannertype=intval($old_data_row['html5']);
			$bannerList = $old_data_row['banner_list'];


			$bannerListArray = array();

			if($bannerList != "")
			$bannerListArray = json_decode($bannerList,1);



			if($old_type ==2 || $old_type ==11)
			{
				if($bannertype ==0)
				{
					unlink(DATA_DIR.'/'.$aid.'_'.$old_name);

					if($old_type ==2 && isset($old_data_row['expandable']) && $old_data_row['expandable'] ==1)
					{
						$expandable_banner=$old_data_row['expandable_banner'];

						unlink(DATA_DIR.'/'.$aid.'_exp_'.$expandable_banner);
					}
				}
				else
				{
					if(is_dir(DATA_DIR.'/html5/'.$aid.'/'))
					$this->remove_files(DATA_DIR.'/html5/'.$aid.'/');

					if($old_type ==2 && isset($old_data_row['expandable']) && $old_data_row['expandable'] ==1)
					{
						if(is_dir(DATA_DIR.'/html5/'.$aid.'-exp/'))
						$this->remove_files(DATA_DIR.'/html5/'.$aid.'-exp/');
					}
				}



			}
			else if($old_type ==13)
			{
				unlink(DATA_DIR.'/video/'.$aid.'/'.$old_name);

				rmdir(DATA_DIR.'/video/'.$aid.'/');
			}
			else if($old_type ==14)
			{
				$filename_array=json_decode($old_name,true);

				foreach($filename_array as $rkey=>$rvalue)
				{
					unlink(DATA_DIR.'/'.$aid.'/'.$rvalue);
				}

				rmdir(DATA_DIR.'/'.$aid.'/');
			}
			else if($adtype ==18)
			{
				unlink(DATA_DIR.'/notification_icons/'.$aid.'_'.$old_name);

			}


			if($old_type ==2 || $old_type ==11)
			{
				foreach($bannerListArray as $bKey=>$bValue)
				{
					if(file_exists(DATA_DIR.'/'.$aid.'/'.$bValue))
					unlink(DATA_DIR.'/'.$aid.'/'.$bValue);
				}

				rmdir(DATA_DIR.'/'.$aid.'/');
			}



			if($old_type ==7)
			{
				unlink(DATA_DIR."/ecommerce/logo/".$ecommercelogo);

				$row123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($aid));
				while($row1234=$row123->fetch_assoc())
				{
					unlink(DATA_DIR.'/ecommerce/'.$aid.'/'.$row1234['ad_image']);
				}

				rmdir(DATA_DIR.'/ecommerce/'.$aid.'/');

				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($aid));
			}



			$db->execute_query("delete from ".TABLE_PREFIX."ads where id=? and uid=?",array($aid,$uid));
			$db->execute_query("delete from ".TABLE_PREFIX."ads_cache where id=? and uid=?",array($aid,$uid));
			$db->execute_query("delete from ".TABLE_PREFIX."ad_geographic_mapping where aid=? and uid=?",array($aid,$uid));
			$db->execute_query("delete from ".TABLE_PREFIX."ad_keyword_mapping where aid=? and uid=?",array($aid,$uid));

			$retargeting_enabled=$this->get_addon_status('retargeting_enabled');

			if($retargeting_enabled ==1 || $retargeting_enabled ==0)
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE aid=? and uid=?",array($aid,$uid));



			$category_enabled=$this->get_addon_status('category-targeting_enabled');
			$device_enabled=$this->get_addon_status('device-targeting_enabled');
		    $isp_enabled=$this->get_addon_status("isp-connection-targeting_enabled");
		    $language_enabled=$this->get_addon_status('language-targeting_enabled');

			if($category_enabled ==1 || $category_enabled ==0)
			$db->execute_query("delete from ".TABLE_PREFIX."ad_category_mapping where aid=? and uid=?",array($aid,$uid));

			if($language_enabled ==1 || $language_enabled ==0)
			$db->execute_query("delete from ".TABLE_PREFIX."ad_language_mapping where aid=? and uid=?",array($aid,$uid));

			if($device_enabled ==1 || $device_enabled ==0)
			{
				$db->execute_query("delete from ".TABLE_PREFIX."ad_os_mapping where aid=? and uid=?",array($aid,$uid));
				$db->execute_query("delete from ".TABLE_PREFIX."ad_browser_mapping where aid=? and uid=?",array($aid,$uid));

			}

			if($isp_enabled ==1 || $isp_enabled ==0)
			{
				$db->execute_query("delete from ".TABLE_PREFIX."ad_isp_mapping where aid=? and uid=?",array($aid,$uid));
				$db->execute_query("delete from ".TABLE_PREFIX."ad_connection_mapping where aid=? and uid=?",array($aid,$uid));

			}

			if($sponsored_enabled ==1 || $sponsored_enabled ==0)
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE aid=? AND (status=-1 OR status=0 OR status=1)",array($aid));

		}

		if($adtype ==7 && $flagdata ==0 && $ad_parent >0)
		{
			$ecommerce_id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ads WHERE ecommerce_parent=?",array($ad_parent));

			if(intval($ecommerce_id) ==0)
			{
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads WHERE id=? AND uid=?",array($ad_parent,$uid));
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_cache WHERE id=? AND uid=?",array($ad_parent,$uid));
			}
		}


		if($flagdata ==1)
		{
			$message=$this->get_message('active pricing exists');
			$color=0;
		}
		else if($flagdata ==2)
		{
			$message=$this->get_message('sponsored mappings ad exists');
			$color=0;
		}
		else
		{
			$message=$this->get_message('ad delete success');
			$color=1;
		}

		if($tp!="" && $st!="" && $pg!="" && $adpricing !="")
		$this->flash($message, $this->make_url('ad/list/'.$tp.'/'.$st.'/'.$adpricing.'/'.$pg),$color);
	    else
		$this->flash($message, $this->make_url('ad/list'),$color);


		exit;
	}


	function update_pause_status_action()
	{
		$aid=$this->read_page_param(1);
		$pause=$this->read_page_param(2);
		$frompg=$this->read_page_param(3);

		if(DEMO_MODE && $aid <= 100)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('ad/list'),0);
			exit;
		}


		$tp=$this->read_page_param(4);
		$st=$this->read_page_param(5);
		$adpricing=$this->read_page_param(6);
		$pg=$this->read_page_param(7);

		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$db= DAL::get_instance();

		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);
		}

		$adp=$this->get_ad_pricing_value($aid);

		if($adp ==1 && $this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==3 && $this->get_addon_status('sponsored_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==6 && $this->get_addon_status('cpa_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		$query="update ".TABLE_PREFIX."ads set pause_status=? where id=?";
    	        $query1="update ".TABLE_PREFIX."ads_cache set pause_status=b'?' where id=?";

		if($pause==1)
		{
			$db->execute_query($query,array(1,$aid));
			$db->execute_query($query1,array(1,$aid));
		}
		else
		{
			$db->execute_query($query,array(0,$aid));
			$db->execute_query($query1,array(0,$aid));
		}

		if($frompg==1)
		$this->flash($this->get_message('ad status updated'), $this->make_url("ad/detailed_statistics/".$aid));
		else if($frompg==2)
		$this->flash($this->get_message('ad status updated'), $this->make_url("ad/list/".$tp."/".$st."/".$adpricing."/".$pg));
		else if($frompg==3)
		$this->flash($this->get_message('ad status updated'), $this->make_url("ad/view/".$aid));
	}

	function list_action()
	{
		$this->set_title($this->get_label('manage your ads'));

		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$db= DAL::get_instance();
		if($_POST)
		{
			$adtype=intval($this->read_post_param('type'));
			$status=intval($this->read_post_param('status'));
			$adpricing=intval($this->read_post_param('adpricing'));
		}
		else
		{
			$adtype=$this->read_page_param(1);
			$status=$this->read_page_param(2);
			$adpricing=$this->read_page_param(3);

			$exp=explode("-",$adtype);
			if($exp[0]=="page")
			$adtype=0;

			$exp=explode("-",$status);
			if($exp[0]=="page")
			$status=2;

			$exp=explode("-",$adpricing);
			if($exp[0]=="page")
			$adpricing=-1;
		}

		$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
		$this->set_variable('ecommerce_enabled',$ecommerce_enabled);


		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		$this->set_variable('text_ads_enabled',$text_ads_enabled);


		if($adpricing === "")
		$adpricing=-1;

		if($adtype === "")
		$adtype=0;

		if($status === "")
		$status=2;

		if($adtype ==0)
		$adtype_str="";
		else
		$adtype_str=" AND a.type=".intval($adtype)." ";

		if($text_ads_enabled !=1)
		$adtype_str.=' AND a.type <>1 ';


		if($ecommerce_enabled !=1)
		$adtype_str.=' AND a.type <>7 ';

		if($this->get_addon_status('pop-ads_enabled') !=1)
		$adtype_str.=' AND a.type <>9 ';

		if($this->get_addon_status('text-image-ads_enabled') !=1)
		$adtype_str.=' AND a.type <>11 ';

		if($this->get_addon_status('affiliate-ads_enabled') !=1)
		$adtype_str.=' AND a.type <>12 ';

		if($this->get_addon_status('video-ads_enabled') !=1)
		$adtype_str.=' AND a.type <>13 ';
		if($this->get_addon_status('skin-ads_enabled') !=1)
		$adtype_str.=' AND a.type <>14 ';

		if($this->get_addon_status('cpp_enabled') !=1)
		$adtype_str.=' AND a.type <>18 ';

		if($this->get_addon_status('direct-link-ads_enabled') !=1)
		$adtype_str.=' AND a.type <>21 ';

		if($status ==2)
		$status_str="";
		else
		$status_str=" and a.status=".intval($status)." ";



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
		


		$this->set_variable("type",$adtype);
		$this->set_variable("status",$status);
		$this->set_variable("adpricing",$adpricing);


		$uid=$this->read_cookie_param(COOKIE_LOGINID);


		$pagination = new Pagination("SELECT * FROM ".TABLE_PREFIX."ads a where uid=? AND ecommerce_parent=0 ".$adtype_str.$status_str.$adpricing_str." ORDER BY id desc",array($uid));
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);

		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);


		$draftid=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ads WHERE uid=? AND status=-2 LIMIT 0,1",array($uid));

		$this->set_variable("draftid",$draftid);
	}

	function view_action()
	{
		$this->set_title($this->get_label('manage keywords locations'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		if($_POST)
		{
			$aid=$this->read_post_param("aid");
			$from=intval($this->read_post_param("from"));
		}
		else
		{
			$aid=$this->read_page_param(1);
			$from=intval($this->read_page_param(2));
		}

		$db= DAL::get_instance();


		if(!$this->get_your_ad($aid,$uid))
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);


		$adrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
		$this->set_result("adrow",$adrow);

		$adrowdata=$adrow->fetch_assoc();

		$adstatus=$adrowdata['status'];
		$adp=$adrowdata['display_type'];
		$adtype=$adrowdata['type'];

		$parent_ad=0;

		if($this->get_addon_status('ecommerce-ads_enabled') ==1)
		$parent_ad=$adrowdata['ecommerce_parent'];

		if($this->get_addon_status('device-targeting_enabled') ==1)
		$device=$adrowdata['device'];
		else
		$device=0;

		if($adp ==0 && $this->get_addon_status('cpc_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==1 && $this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==3 && $this->get_addon_status('sponsored_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==6 && $this->get_addon_status('cpa_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);


		$this->set_variable("aid",$aid);
		$this->set_variable("from",$from);

		if($this->get_addon_status('isp-connection-targeting_enabled') ==1)
		$isp_success=$db->read_single_column("SELECT value FROM ".TABLE_PREFIX."config WHERE name=?",array('isp_dataimport_success'));
		else
		$isp_success=0;

		$this->set_variable('isp_success',$isp_success);
		$this->set_variable('device',$device);
		$this->set_variable('adstatus',$adstatus);
		$this->set_variable('adtype',$adtype);
		$this->set_variable('parent_ad',$parent_ad);
	}
	function preview_action()
	{
		//$this->disable_notice_area();
		$aid=$this->read_page_param(1);
		$frompg=$this->read_page_param(2);


		$adp=$this->get_ad_pricing_value($aid);

		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');

		if($adp ==0 && $this->get_addon_status('cpc_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==1 && $cpm_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==3 && $this->get_addon_status('sponsored_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==6 && $this->get_addon_status('cpa_enabled') !=1)

		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);


		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$db= DAL::get_instance();


		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('ad/statistics'),0);
		}


		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);


		$res1=$db->execute_query("select b.height,b.width,b.filesize from ".TABLE_PREFIX."ads a INNER JOIN ".TABLE_PREFIX."banner_dimensions b where a.banner_id=b.id and a.id=?",array($aid));
		$this->set_result("res1",$res1);


		$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=? ORDER BY id desc",array($aid));
		$this->set_result("res",$res,array('banner_list'));

		$adres=$res->fetch_assoc();
		$adtype=$adres['type'];


		if($adtype ==14)
		{
			$json_banners=$adres['banner'];

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
		$this->set_variable("frompg",$frompg);

		if($adtype == 12)
		{
				$imagerow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=? AND status=1",array($aid));

				$this->set_result('imagerow',$imagerow);
		}
	}


	function preview_user_action()
	{
		//$this->disable_notice_area();
		$aid      = intval($this->read_page_param(1));
		$adcodeID = intval($this->read_page_param(2));

		$this->set_variable("aid",$aid);
		$this->set_variable("adcodeID",$adcodeID);
	}


	function preview_frame_action()
	{
		$this->disable_notice_area();
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

	function keywords_action()
	{
			header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");

			$this->disable_notice_area();
			$db= DAL::get_instance();
			$uid=$this->read_cookie_param(COOKIE_LOGINID);
			$aid=$this->read_page_param(1);
			$action=$this->read_page_param(2);

			$inscount=0;

			if(!$this->get_your_ad($aid,$uid))
			{
				$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);
			}

			$res=$db->execute_query("select * from ".TABLE_PREFIX."ads where id=?",array($aid));
			$this->set_result("res",$res);

			$operation_message = "";
			if($_POST)
			{
				$key_status=Configuration::get_instance()->read('keyword_default_status');

				$clickvalue=$this->read_post_param('clickvalue');
				$uid=$this->read_post_param('uid');
				$aid=$this->read_post_param('aid');
				$keywords=$this->read_post_param('keywords');






					if($keywords =="")
					$operation_message = $this->get_message("mandatory");
					else
					{
						$search= array("\r\n", "\n", "\r");
						$replace=",";
						$keywords=str_replace($search, $replace, $keywords);

						$time1=time();

						$single=explode(",",$keywords);
						$count=count($single);

						$okstatus=1;
						for($i=0;$i<$count;$i++)
						{
							$key=trim($single[$i]);
							$id=$db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=?",array($key));

							if($id=="" && $key !="")
							{
								$res1=$db->execute_query("INSERT INTO ".TABLE_PREFIX."keywords (keyword,create_time,status) values (?,?,?)",array($key,$time1,$key_status));

								$okstatus=1;
							}
							else if($id >0)
							{
								$okstatus=$db->read_single_column("select status from ".TABLE_PREFIX."keywords where keyword=?",array($key));
							}


							$kid=$db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=?",array($key));
							$id1=$db->read_single_column("select id from ".TABLE_PREFIX."ad_keyword_mapping where kid=? and aid=?",array($kid,$aid));




							if($id1=="" && $okstatus !=0)
							{
									$result=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) values (?,?,?)",array($uid,$aid,$kid));

									if($result->error=="")
									$inscount=$inscount+1;
							}

						}

						if($inscount==0)
						{
							if($id1 > 0)
							$operation_message = $this->get_message("you already added");
							else if($okstatus == 0)
							$operation_message = $this->get_message("ajax keyword blocked");
						}
						else
						{
							$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE aid=? AND kid=0",array($aid));
							$operation_message = $this->get_message('added count',array('x'=>$inscount));
						}
			}
		}

		$this->set_variable("operation_message", $operation_message);
		$this->set_variable("action", $action);

		$check="select k.id as keyid,k.keyword,m.id as mid from ".TABLE_PREFIX."keywords k INNER JOIN ".TABLE_PREFIX."ad_keyword_mapping m ON k.id=m.kid and m.aid=? AND m.kid <>0 ORDER BY m.kid DESC";
		$pagination = new Pagination($check,array($aid));
		$result=$pagination->get_result();

		$this->set_result("result",$result);
		$this->set_variable("pagination",$pagination->links(false),0);


		$pg=$pagination->get_page_number();
		$this->set_variable("pg",$pg);

		$this->set_variable("uid",$uid);
		$this->set_variable('aid', $aid);
	}


	function edit_keyword_action()
	{
			header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");



					$db= DAL::get_instance();

					$ajax_msg="";

					$kid=$this->read_post_param('kid');
					$aid=$this->read_post_param('aid');
					$keyword=$this->read_post_param('keyword');







								if(DEMO_MODE && $aid <= 100)
								{
									$ajax_msg="1_".$this->get_message('demo mode');
								}

								else if($keyword=="")
								{
									$ajax_msg="1_".$this->get_message('mandatory');
								}
								else
								{
									$key_status=Configuration::get_instance()->read('keyword_default_status');
									$time1=time();


									$id=$db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=?",array($keyword));
									if($id =="")
									{
											$res1=$db->execute_query("INSERT INTO ".TABLE_PREFIX."keywords (keyword,create_time,status) values (?,?,?)",array($keyword,$time1,$key_status));

											$okstatus=1;
									}
									else if($id >0)
									{
										$okstatus=$db->read_single_column("select status from ".TABLE_PREFIX."keywords where keyword=?",array($keyword));
									}


									$kwid=$db->read_single_column("select id from ".TABLE_PREFIX."keywords where keyword=?",array($keyword));
									$cnts=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_keyword_mapping where kid=? and aid=? and id<>?",array($kwid,$aid,$kid));



									if($okstatus ==0)
									$ajax_msg="1_".$this->get_message('ajax keyword blocked');
									else
									{
										if($cnts ==0)
										{
											$res1=$db->execute_query("update ".TABLE_PREFIX."ad_keyword_mapping set kid=? where id=?",array($kwid,$kid));

											$ajax_msg=$this->get_message('ajax success edit');
										}
										else
										$ajax_msg="1_".$this->get_message('ajax already added');
									}

								}
								echo $ajax_msg;
								exit;
	}


	function delete_keyword_action()
	{
			$kid=$this->read_page_param(1);
			$ad_id=$this->read_page_param(2);
			$pg=$this->read_page_param(3);
			$db= DAL::get_instance();

			if(DEMO_MODE && $ad_id <= 100)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('ad/keywords/'.$ad_id),0);
				exit;
			}


			if($pg >0)
			$pg="/page-".$pg;


			$res1=$db->execute_query("delete from ".TABLE_PREFIX."ad_keyword_mapping where id=? AND aid=?",array($kid,$ad_id));


			$kcnt=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_keyword_mapping WHERE aid=?",array($ad_id));
			if($kcnt ==0)
			{
				$uid=$this->read_cookie_param(COOKIE_LOGINID);
				$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_keyword_mapping (uid,aid,kid) VALUES (?,?,?)",array($uid,$ad_id,0));
			}


			header("Location: ".$this->make_url('ad/keywords/'.$ad_id.'/1'.$pg));
			exit;
	}

	function locations_action()
	{
		$this->disable_notice_area();

		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$aid=$this->read_page_param(1);

		$db= DAL::get_instance();


		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('ad/locations/'.$aid),0);
		}

		$adp=$this->get_ad_pricing_value($aid);

		if($adp ==0 && $this->get_addon_status('cpc_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/locations/'.$aid),0);

		if($adp ==1 && $this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/locations/'.$aid),0);

		if($adp ==3 && $this->get_addon_status('sponsored_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/locations/'.$aid),0);

		if($adp ==6 && $this->get_addon_status('cpa_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/locations/'.$aid),0);


		$message='';

		if($_POST)
		{
			$aid=$this->read_post_param('aid');


			if(DEMO_MODE && $aid <= 100)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('ad/locations/'.$aid),0);
				exit;
			}




			$old_locatn=$this->read_post_param('a_loc');

			$result3=$db->execute_query("select country_code from ".TABLE_PREFIX."ad_geographic_mapping where aid=? and country_code <>'0' GROUP BY country_code",array($aid));

			$country_code=array();
			while($val=$result3->fetch_assoc())
			{
				$country_code[]=$val['country_code'];
			}

			$location=explode(",",$old_locatn);
			$diff_loc=array_diff($country_code, $location);





			foreach ($diff_loc as $key=>$value)
			{
				$res11=$db->execute_query("delete from ".TABLE_PREFIX."ad_geographic_mapping where country_code=? and aid=?",array($value,$aid));

				$res_updation1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_country=0  WHERE id=?",array($value,$aid));

			}

			$new_loc=array_diff($location,$country_code);

	                $flag=0;
			foreach ($new_loc as $key=>$value)
			{
				if($value !="")
				{
				$flag=1;
				$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_geographic_mapping (uid,aid,country_code) values (?,?,?)",array($uid,$aid,$value));

				$res_updation2=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_country=1  WHERE id=?",array($value,$aid));

				}

			}
			if($flag ==1)
			   $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_countries=0  WHERE id=?",array($aid));



			$country_counts=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_geographic_mapping where country_code<>'0' and aid=?",array($aid));
			if($country_counts >0)
			{
				$del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_geographic_mapping WHERE country_code=? and aid=?",array(0,$aid));
				$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_countries=0  WHERE id=?",array($aid));

			}
			else
			{
				$zero_count=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ad_geographic_mapping where country_code='0' and aid=?",array($aid));

				if($zero_count <=0 || $zero_count=="")
				{
				$country_ins_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_geographic_mapping (uid,aid,country_code) values (?,?,?)",array($uid,$aid,0));

				$res_updation=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_countries=1  WHERE id=?",array($aid));
				}
				else
				$res_updation=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_countries=1  WHERE id=?",array($aid));


			}

			$message=$this->get_message('location updated');

		}


		$query="select code,name from ".TABLE_PREFIX."countries where
		code NOT IN(select country_code from ".TABLE_PREFIX."ad_geographic_mapping where aid=? and country_code<>'0') and code!='A1' and code!='A2' and code!='AP'
		and code!='EU' ORDER BY name";


		$result=$db->execute_query($query,array($aid));
		$this->set_result("result",$result);

		$result3=$db->execute_query("select country_code from ".TABLE_PREFIX."ad_geographic_mapping where aid=? and country_code <>'0' GROUP BY country_code",array($aid));
		$this->set_result("result3",$result3);

		$tar_count=$result3->get_num_records();

		$this->set_variable('aid', $aid);
		$this->set_variable('tar_count', $tar_count);
		$this->set_variable('message', $message);
	}


	function statistics_action()
	{
		$this->set_title($this->get_label('ad statistics'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		if($_POST)
		{
			$adtype=intval($this->read_post_param('type'));
			$duration=intval($this->read_post_param("duration"));
			$adpricing=intval($this->read_post_param('adpricing'));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
			$sortBy     = $this->read_post_param("sortBy");
			$orderBy    = $this->read_post_param("orderBy");
		}
		else
		{
			$adtype=0;
			$duration=1;
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
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("sortBy",$sortBy);
		$this->set_variable("orderBy",$orderBy);
		$this->set_variable("uid",$uid);

		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $sorting = 0, $forMap = 0, $resultLimit = 0, $country = '', $ordering = 0, $advertiser = '', $adtype = 0, $isAdvertiserAds = -1
		$reportResult  = $this->get_advertiser_statistics($timePeriod, 0, $adpricing, $uid, 0, 0, 4, $sorting, 0, 0, "", $ordering, "", $adtype, 1);
		$this->set_array("reportResult",$reportResult);
	}

	function detailed_statistics_action()
	{
		$this->set_title($this->get_label('ad statistics'));

		$db= DAL::get_instance();

		$aid=$this->read_page_param(1);

		$uid=$this->read_cookie_param(COOKIE_LOGINID);



		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('ad/statistics'),0);
		}


		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
		$this->set_result("rowdata",$row);

		$rowdata         = $row->fetch_assoc();

		$adtype				   = $rowdata['type'];
		$def_click_value = $rowdata['default_rate'];
		$adp				     = $rowdata['display_type'];

		$amount_spend       = 0;
		$amount_spend_today = 0;
		$daily_budget       = 0;
		$total_ad_budget    = 0;

		if($adp != 3)
		{
				$amount_spend       = $rowdata['total_budget_used'];
				$amount_spend_today = $rowdata['daily_budget_used'];
				$daily_budget       = $rowdata['daily_budget'];
				$total_ad_budget    = $rowdata['total_ad_budget'];
		}

		$this->set_variable('amount_spend',$amount_spend);
		$this->set_variable('amount_spend_today',$amount_spend_today);
		$this->set_variable('daily_budget',$daily_budget);
		$this->set_variable('total_ad_budget',$total_ad_budget);


		if($adp ==0 && $this->get_addon_status('cpc_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==1 && $this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==3 && $this->get_addon_status('sponsored_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==6 && $this->get_addon_status('cpa_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($adp ==13 && $this->get_addon_status('video-ads_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);
		
		if($adp ==18 && $this->get_addon_status('cpp_enabled') !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);



		if($adtype ==7 && $rowdata['ecommerce_parent'] ==0)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($this->get_addon_status('text-image-ads_enabled') !=1 && $adtype ==11)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($this->get_addon_status('skin-ads_enabled') !=1 && $adtype ==14)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($this->get_addon_status('pop-ads_enabled') !=1 && $adtype ==9)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($this->get_addon_status('affiliate-ads_enabled') !=1 && $adtype == 12)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($this->get_addon_status('video-ads_enabled') !=1 && $adtype == 13)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);
		if($this->get_addon_status('direct-link-ads_enabled') !=1 && $adtype == 21)
		$this->flash($this->get_message('invalid operation'), $this->make_url('ad/list'),0);

		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$tab=intval($this->read_post_param("tab"));
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$duration=1;
			$tab=0;
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




		//$timePeriod = array(1), $fromAdmin = 1, $pricing = -1 $uid=-1, $aid=0, $kid=0,  $report_type=0, $country = ''
		$dbResult = $this->get_advertiser_statistics($timePeriod, 0, $adp, $uid, $aid);
		$this->set_array("reportResult",$dbResult);


		$dbResultTimeperiod = $this->get_advertiser_timeperiod_statistics($timePeriod, 0, $adp, $uid, $aid);
		$this->set_array("reportResultTimeperiod",$dbResultTimeperiod);


	


		if($adp == 3)
		$keywords_data = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE aid = ? AND (status = 2 OR status = 3 OR status = 4) ORDER BY id DESC",array($aid));
		else
		$keywords_data = $db->execute_query("SELECT k.id,k.keyword FROM ".TABLE_PREFIX."keywords k INNER JOIN ".TABLE_PREFIX."ad_keyword_mapping m ON k.id=m.kid and m.aid=? AND m.kid <>0",array($aid));


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
				$dbResult    = $this->get_advertiser_statistics($timePeriod, 0, $adp, $uid, $aid, $kid);


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
			      	$sectionString.= $positionName;

			      	$sectionString.= "</div>";

			      	$sectionString.= "<div><bdi>".$this->get_label("package days rate label",array("x"=>$resultData['position_days'],"y"=>$this->get_money_format($resultData['cpd_rate_total'])))."</bdi></div>";

					$sectionString.= "<div><bdi>".$this->get_label('status')." : ".SponsoredHelper::get_ad_mapping_status($resultData['status'])."</bdi></div>";

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
			$dbResult = $this->get_advertiser_statistics($timePeriod, 0, $adp, $uid, $aid, 0);

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



		$this->set_variable("duration",$duration);
		$this->set_variable("aid",$aid);
		$this->set_variable("tab",$tab);
		$this->set_variable("uid",$uid);
	}

	function pricing_action()
	{
		$db= DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);


		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		$aid=$this->read_page_param(1);


		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_base_url('ad/list'),0);
		}

		$ac_balance = $db->read_single_column("select adv_account_balance from " . TABLE_PREFIX . "users where id=?",array (
				$uid
		));
		$check=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE uid=? AND id=?",array($uid,$aid));
		$checkrow=$check->fetch_assoc();

		$pricing_status=intval($checkrow['pricing_status']);
		$ad_pricing=$checkrow['display_type'];

		$adtype=$checkrow['type'];
		$adstatus=$checkrow['status'];

		$parent_ad=0;

		if($this->get_addon_status('ecommerce-ads_enabled') ==1)
		$parent_ad=$checkrow['ecommerce_parent'];

		$this->set_variable('ac_balance',$ac_balance);
		$this->set_variable('pricing',$checkrow['display_type']);
		$this->set_variable('pricing_status',$pricing_status);
		$this->set_variable('total_ad_budget',$checkrow['total_ad_budget']);
		$this->set_variable('total_budget_used',$checkrow['total_budget_used']);
		$this->set_variable('daily_budget',$checkrow['daily_budget']);
		$this->set_variable('daily_budget_used',$checkrow['daily_budget_used']);
		$this->set_variable('default_rate',$checkrow['default_rate']);
		$this->set_variable('start_date',$checkrow['start_date']);
		$this->set_variable('end_date',$checkrow['end_date']);
		$this->set_variable('adtype',$adtype);
		$this->set_variable('adstatus',$adstatus);
		$this->set_variable('parent_ad',$parent_ad);


		$suggest_value=$this->get_suggested_value_ad($checkrow['type'],$aid,$checkrow['banner_id'],$ad_pricing);
		$this->set_variable('suggest_value',$suggest_value);


		$this->set_variable("uid",$uid);
		$this->set_variable('aid', $aid);

		if($adtype == 9) //POP
		$adDisplayType = 9;
		else if($adtype == 12) //Affiliate
		$adDisplayType = 12;
		else if($adtype == 13) //Video
		$adDisplayType = 13;
		else 
		$adDisplayType = $ad_pricing;


		$min_default_rate=$this->get_minrate_by_uid($uid,$adDisplayType);

		if($ad_pricing == 0)
		{
			$min_ad_budget=Configuration::get_instance()->read('min_cpc_total_budget');
			$min_daily_budget=Configuration::get_instance()->read('min_cpc_daily_budget');
		}
		else if($ad_pricing == 1 && $adtype != 9 && $adtype != 13)
		{
			$min_ad_budget=Configuration::get_instance()->read('min_cpm_total_budget');
			$min_daily_budget=Configuration::get_instance()->read('min_cpm_daily_budget');
		}
		else if($ad_pricing == 1 && $adtype == 9)
		{
			$min_ad_budget=Configuration::get_instance()->read('min_pop_total_budget');
			$min_daily_budget=Configuration::get_instance()->read('min_pop_daily_budget');
		}
		else if($ad_pricing == 1 && $adtype == 13)
		{
			$min_ad_budget=Configuration::get_instance()->read('min_cpv_total_budget');
			$min_daily_budget=Configuration::get_instance()->read('min_cpv_daily_budget');
		}
		else if($ad_pricing == 6 && $adtype != 12)
		{
			$min_ad_budget=Configuration::get_instance()->read('min_cpa_total_budget');
		}				
		else if($ad_pricing == 6 && $adtype == 12)
		{
			$min_ad_budget=Configuration::get_instance()->read('min_affiliate_total_budget');
		}
		else if($ad_pricing == 18)
		{
			$min_ad_budget=Configuration::get_instance()->read('min_cpp_total_budget');
			$min_daily_budget=Configuration::get_instance()->read('min_cpp_daily_budget');
		}

				if($ad_pricing != 6)
				$this->set_variable('min_daily_budget',$min_daily_budget);

				$this->set_variable('min_default_rate',$min_default_rate);
				$this->set_variable('min_ad_budget',$min_ad_budget);
	}




	function update_pricing_action()
	{
			$db= DAL::get_instance();

			$aid				= $this->read_post_param('aid');
			$uid				= $this->read_cookie_param(COOKIE_LOGINID);

			$ad_budget			= $this->read_post_param('ad_budget');
			$default_rate		= $this->read_post_param('default_rate');
			$daily_budget		= $this->read_post_param('daily_budget');

			if(DEMO_MODE && $aid <= 100)
			{
				echo "error-12";
				die;
			}


			$ds_places			= Configuration::get_instance()->read('decimal_place');

			$ad_budget_array	= explode('.',$ad_budget);

			if(isset($ad_budget_array[1]) && strlen($ad_budget_array[1]) > $ds_places)
			{
				$ad_budget_array[1] = substr($ad_budget_array[1],0,$ds_places);

				$ad_budget			= implode('.',$ad_budget_array);
			}


			$default_rate_array	= explode('.',$default_rate);

			if(isset($default_rate_array[1]) && strlen($default_rate_array[1]) > $ds_places)
			{
				$default_rate_array[1] = substr($default_rate_array[1],0,$ds_places);

				$default_rate		= implode('.',$default_rate_array);
			}


			$daily_budget_array	= explode('.',$daily_budget);

			if(isset($daily_budget_array[1]) && strlen($daily_budget_array[1]) > $ds_places)
			{
				$daily_budget_array[1] = substr($daily_budget_array[1],0,$ds_places);

				$daily_budget		= implode('.',$daily_budget_array);
			}

			$min_default_rate   	= 0;
			$min_ad_budget			= 0;
			$min_daily_budget		= 0;
			$error_flag				= 0;
			$subcount				= 0;
			$increment_budget_sum 	= 0;
			$adv_balance_string 	= "";
			$pricing_variable 		= "";

			$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=? AND uid=?",array($aid,$uid));

			if($row->get_num_records() >0)
			{
					$rowparent 		  = $row->fetch_assoc();

					$adtype			  = $rowparent['type'];
					$adstatus		  = $rowparent['status'];
					$pricing		  = $rowparent['display_type'];
					$pricing_status   = $rowparent['pricing_status'];
					$parentad  		  = $rowparent['ecommerce_parent'];
					$prev_budget 	  = $rowparent['total_ad_budget'];


					if($adstatus ==-2)
					$default_ad_status = Configuration::get_instance()->read('default_ad_status');
					else
					$default_ad_status = $adstatus;

					$row->set_result_index();

					if($adtype ==7 && $parentad ==0 && $adstatus ==-2)
					{
						$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE ecommerce_parent=? AND uid=? AND status =-2 AND type=7",array($aid,$uid));

						$subcount=$row->get_num_records();
					}
					else
					$subcount =1;

					if($adtype == 9) //POP
					$adDisplayType = 9;
					else if($adtype == 12) //Affiliate
					$adDisplayType = 12;
					else if($adtype == 13) //Video
					$adDisplayType = 13;
					else 
					$adDisplayType = $pricing;

					$min_default_rate=$this->get_minrate_by_uid($uid,$adDisplayType);

					$adv_ac_balance=$db->read_single_column("SELECT adv_account_balance FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));

					if($pricing == 0)
					$pricing_variable="cpc_";
					else if($pricing == 1 && $adtype != 9 && $adtype != 13)
					$pricing_variable="cpm_";
					else if($pricing == 1 && $adtype == 9)
					$pricing_variable="pop_";
					else if($pricing == 1 && $adtype == 13)
					$pricing_variable="video_";
					else if($pricing == 6 && $adtype != 12)
					$pricing_variable="cpa_";					
					else if($pricing == 6 && $adtype == 12)
					$pricing_variable="affiliate_";


					if($pricing_variable !="")
					{
						$min_ad_budget=Configuration::get_instance()->read('min_'.$pricing_variable.'total_budget');

						if($pricing !=6)
						$min_daily_budget=Configuration::get_instance()->read('min_'.$pricing_variable.'daily_budget');
					}

					$additionalBudget      = $ad_budget - $prev_budget;
					$totalAdditionalBudget = $additionalBudget * $subcount;

					if($default_rate ==0 || $ad_budget ==0)
					{
						echo "error-1";
						exit;
					}
					else if($adv_ac_balance < $totalAdditionalBudget)
					{
						echo "error-2";
						exit;
					}
					else if($default_rate < $min_default_rate)
					{
						echo "error-3";
						exit;
					}
					else if($default_rate > $ad_budget)
					{
						echo "error-4";
						exit;
					}
					else if($ad_budget < $min_ad_budget)
					{
						echo "error-5";
						exit;
					}
					else if($ad_budget < $prev_budget && $pricing_status ==1)
					{
						echo "error-6";
						exit;
					}
					else if($pricing !=6 && $daily_budget > $ad_budget)
					{
						echo "error-7";
						exit;
					}
					else if($pricing !=6 && $daily_budget >0 && $daily_budget < $min_daily_budget)
					{
						echo "error-8";
						exit;
					}
					else if($pricing !=6 && $daily_budget >0 && $daily_budget < $default_rate)
					{
						echo "error-9";
						exit;
					}
			}


			if($row->get_num_records() >0)
			{
				$db->execute_query("BEGIN");

				while($rowdata = $row->fetch_assoc())
				{
					$aid                  = $rowdata['id'];
					$adstatus		      = $rowdata['status'];
					$ecommerce_parent 	  = $rowdata['ecommerce_parent'];
					$budget_used 		  = $rowdata['total_budget_used'];
					$daily_budget_used 	  = $rowdata['daily_budget_used'];


					$increment_budget 	  = $ad_budget - $prev_budget;

					$increment_budget_sum = $increment_budget_sum + $increment_budget;


					$start_time			= 0;
					$search_data		= array();
					$search_ads_data	= array();

					$update_string 		= "default_rate=?,total_ad_budget=?,pricing_status=?";


					$update_ads_string 	= "default_rate=?,total_ad_budget=?,pricing_status=b'?'";

					$search_data[]		= $default_rate;
					$search_data[]		= $ad_budget;
					$search_data[]		= 1;

					$search_ads_data[]		= $default_rate;
					$search_ads_data[]		= $ad_budget;
					$search_ads_data[]		= 1;

					if($adstatus ==-2)
					{
						$update_string.=",status=?";
						$update_ads_string.=",status=b'?'";

						$search_data[]		= $default_ad_status;

						if($default_ad_status == -1)
						$search_ads_data[]		= 0;
						else
						$search_ads_data[]		= $default_ad_status;
					}


					if($pricing_status == 0 || $pricing_status ==-1 || $pricing_status == 2)
					{
						$update_string.=",start_date=?";

						$start_time			= time();
						$search_data[]		= $start_time;
					}


					if($pricing != 6)
					{
						$update_string.=",daily_budget=?";
						$search_data[]		= $daily_budget;

						$update_ads_string.=",daily_budget=?";
						$search_ads_data[]	= $daily_budget;
					}

					$defaultRateOneRequest = 0;

					if($pricing == 1 || $pricing == 2)
					$defaultRateOneRequest = $default_rate / 1000;
					else if($pricing == 0 || $pricing == 6)
					$defaultRateOneRequest = $default_rate;

					if($adstatus == -2)
					{
						if($ad_budget >= $defaultRateOneRequest)
						{
							$update_ads_string.= ",budget_available=b'?'";
							$search_ads_data[]	= 1;
						}

						if($pricing != 6)
						{
							if($daily_budget == 0 || $daily_budget >= $defaultRateOneRequest)
							{
								$update_ads_string.= ",daily_budget_available=b'?'";
								$search_ads_data[]	= 1;
							}
						}
					}
					else
					{
						if(($ad_budget - $budget_used) >= $defaultRateOneRequest)
						{
							$update_ads_string.= ",budget_available=b'?'";
							$search_ads_data[]	= 1;
						}


						if($pricing != 6)
						{
							if($daily_budget == 0 || ($daily_budget - $daily_budget_used) >= $defaultRateOneRequest)
							{
								$update_ads_string.= ",daily_budget_available=b'?'";
								$search_ads_data[]	= 1;
							}
							else if($daily_budget <= $daily_budget_used)
							{
								$update_ads_string.= ",daily_budget_available=b'?'";
								$search_ads_data[]	= 0;
							}
						}
					}



					$search_data[]			= $aid;
					$search_data[]			= $uid;

					$search_ads_data[]		= $aid;
					$search_ads_data[]		= $uid;

					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET ".$update_string." WHERE id=? AND uid=?",$search_data);
					$res1=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ".$update_ads_string." WHERE id=? AND uid=?",$search_ads_data);


					if($res->get_error() =="" && $res1->get_error() =="")
					{
						if($increment_budget >0)
						{
							$res11=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET adv_account_balance = adv_account_balance-? WHERE id=?",array($increment_budget,$uid));

							if($res11->error !="")
							$error_flag=1;
						}
					}
					else
					$error_flag=1;

					if($error_flag == 0 && $adtype ==7 && $ecommerce_parent >0 && $adstatus ==-2)  // Parent ad status change
					{
						$res111=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET status=? WHERE id=? AND uid=?",array($default_ad_status,$ecommerce_parent,$uid));

						if($default_ad_status <= 0)
						    $ads_status=0;
						else
						    $ads_status=1;
						$res112=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET status=b'?' WHERE id=? AND uid=?",array($ads_status,$ecommerce_parent,$uid));

						if($res111->error !="")
						    $error_flag=1;

						if($res112->error !="")
						    $error_flag=1;
					}
				}

				if($error_flag == 0)
				{
						$db->execute_query("COMMIT");

						$balance_decrement = 0;

						if($subcount ==1)
						$balance_decrement	= $increment_budget;
						else if($subcount >1)
						$balance_decrement	= $increment_budget_sum;

						$adv_balance_string.='<bdi>'.$this->get_label('adv account balance').':'.$this->get_money_format($adv_ac_balance-$balance_decrement).'</bdi>';

						if($start_time > 0)
						$time_converted=$this->get_date_format(2,$start_time);
						else
						$time_converted=0;

						$array = array('success' => 1, 'adv_balance_string' => $adv_balance_string,'ad_budget'=>$ad_budget,'pricing_status'=>1,'start_time'=>$time_converted,'ad_status'=>$default_ad_status);

						echo json_encode($array);
						die;
				}
				else
				{
						$db->execute_query("ROLLBACK");

						echo "error-10";
						exit;
				}
			}
			else
			{
					echo "error-11";
					exit;
			}
	}

	function pricing_cancel_action() // For pricing cancellation
	{
		$db= DAL::get_instance();
		$aid=$this->read_post_param('aid');
		$uid=$this->read_cookie_param(COOKIE_LOGINID);


		if(DEMO_MODE && $aid <= 100)
		{
				echo json_encode(array('success'=>2));
				die;
		}


		$row=$db->execute_query("SELECT total_ad_budget,total_budget_used,display_type,start_date,end_date FROM ".TABLE_PREFIX."ads WHERE id=? AND uid=? AND pricing_status=1",array($aid,$uid));

		if($row->get_num_records() >0)
		{
			$rowdata=$row->fetch_assoc();

			$budget_balance=$rowdata['total_ad_budget'] - $rowdata['total_budget_used'];

			$error_flag=0;
			$db->execute_query("BEGIN");

			$res=$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET pricing_status=?,default_rate=?,total_ad_budget=?,total_budget_used=?,daily_budget=?,daily_budget_used=? WHERE id=? AND uid=? AND pricing_status=1",array(0,0,0,0,0,0,$aid,$uid));

			if($res->error == '')
			{
				$resCache = $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET pricing_status=b'?',default_rate=?,total_ad_budget=?,total_budget_used=?,daily_budget=?,daily_budget_used=?,budget_available=b'?',daily_budget_available=b'?' WHERE id=? AND uid=? AND pricing_status=1",array(0,0,0,0,0,0,0,0,$aid,$uid));

				if($resCache->error == '')
				{
					if($budget_balance >0)
					{
						$res1=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET adv_account_balance=adv_account_balance+? WHERE id=?",array($budget_balance,$uid));

						if($res1->error !='')
						$error_flag=1;
					}

					if($error_flag == 0 && $rowdata['total_budget_used'] > 0)
					{
						$res2=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ads_budget_history (uid,aid,display_type,budget,budget_used,start_date,end_date) VALUES (?,?,?,?,?,?,?)",array($uid,$aid,$rowdata['display_type'],$rowdata['total_ad_budget'],$rowdata['total_budget_used'],$rowdata['start_date'],time()));

						if($res2->error !='')
						$error_flag=1;
					}
				}
				else
				$error_flag=1;
			}
			else
			$error_flag=1;


			if($error_flag ==0)
			{
				$db->execute_query("COMMIT");

				$adv_balance=$db->read_single_column("SELECT adv_account_balance FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));

				$ac_balance_string='<bdi>'.$this->get_label('account balance').':'.$this->get_money_format($adv_balance).'</bdi>';

				echo json_encode(array('success' => 1,'adv_balance_string' => $ac_balance_string));
				die;
			}
			else
			{
					$db->execute_query("ROLLBACK");

					echo json_encode(array('success'=>0));
					die;
			}
		}
		else
		{
				echo json_encode(array('success'=>-1));
				die;
		}
	}

	function run_history_action()
	{
		$db= DAL::get_instance();
		$aid=$this->read_post_param('aid');
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$this->disable_notice_area();

		if(!(LoginHelper::validate_user_login()))
		{
				echo $this->get_message('login failed');
				exit;
		}
		else
		{
				$expiry = $db->execute_query("SELECT budget,budget_used,start_date,end_date,status FROM ".TABLE_PREFIX."ads_budget_history WHERE uid=? AND aid=? ORDER BY id DESC",array($uid,$aid));
				$this->set_result('expiry',$expiry);
		}
	}


	function validate_js_file($jsdata)
	{
		$otherfile=0;

		preg_match('/\.(.*?)open(.*?)\(/i',$jsdata,$matching); //window.open()/document.open()

		if(isset($matching[0]) && $matching[0] !="")
		$otherfile=2;


		if($otherfile ==0)
		{
			preg_match('/\.(.*?)write(.*?)\(/i',$jsdata,$matching); //document.write()

			if(isset($matching[0]) && $matching[0] !="")
			$otherfile=2;
		}


		if($otherfile ==0)
		{
			preg_match('/\.(.*?)ajax(.*?)\(/i',$jsdata,$matching); //ajax()

			if(isset($matching[0]) && $matching[0] !="")
			$otherfile=2;
		}


		if($otherfile ==0)
		{
			preg_match('/\.(.*?)send(.*?)\(/i',$jsdata,$matching); //send()

			if(isset($matching[0]) && $matching[0] !="")
			$otherfile=2;
		}


		if($otherfile ==0)
		{
			preg_match('/XMLHttpRequest/i',$jsdata,$matching); //XMLHttpRequest()

			if(isset($matching[0]) && $matching[0] !="")
			$otherfile=2;
		}


		if($otherfile ==0)
		{
			preg_match('/alert(.*?)\(/i',$jsdata,$matching); //alert()

			if(isset($matching[0]) && $matching[0] !="")
			$otherfile=2;
		}


		/**************** For jQuery *********************/

		if($otherfile ==0)
		{
			preg_match('/\.(.*?)html(.*?)\(/i',$jsdata,$matching); //.html()

			if(isset($matching[0]) && $matching[0] !="")
			$otherfile=2;
			else
			{
				preg_match('/\.(.*?)append(.*?)\(/i',$jsdata,$matching); //.append()

				if(isset($matching[0]) && $matching[0] !="")
				$otherfile=2;
				else
				{
					preg_match('/\.(.*?)appendTo(.*?)\(/i',$jsdata,$matching); //.appendTo()

					if(isset($matching[0]) && $matching[0] !="")
					$otherfile=2;
					else
					{

						preg_match('/\.(.*?)prepend(.*?)\(/i',$jsdata,$matching); //.prepend()

						if(isset($matching[0]) && $matching[0] !="")
						$otherfile=2;
						else
						{
							preg_match('/\.(.*?)prependTo(.*?)\(/i',$jsdata,$matching); //.prependTo()

							if(isset($matching[0]) && $matching[0] !="")
							$otherfile=2;
							else
							{
								preg_match('/\.(.*?)createElement(.*?)\(/i',$jsdata,$matching); //.createElement()

								if(isset($matching[0]) && $matching[0] !="")
								$otherfile=2;
								else
								{
									preg_match('/href/i',$jsdata,$matching); //href

									if(isset($matching[0]) && $matching[0] !="")
									{
										preg_match_all('/\.(.*?)attr(.*?)\((.*?)["\']href["\'](.*?)\)/i',$jsdata,$matchingdata);

										if(count($matchingdata[4]) ==0)
										$otherfile=2;
										else
										{
											foreach($matchingdata[4] as $k=>$v)
											{
												$href=trim($v);

												$href=str_replace(',','',$href);
												$href=str_replace('"','',$href);
												$href=str_replace("'",'',$href);
												$href=str_replace(";",'',$href);

												if($href != 'javascript:xyz_cta.trigger()')
												{
													$otherfile=2;
													break;
												}
											}
										}
									}


									if($otherfile ==0)
									{
										preg_match('/src/i',$jsdata,$matching); //src

										if(isset($matching[0]) && $matching[0] !="")
										$otherfile=2;
									}
								}
							}
						}
					}
				}
			}
		}

		/**************** For jQuery *********************/

		/**************** For javascript *********************/

		if($otherfile ==0)
		{
			preg_match('/\.(.*?)innerHTML/i',$jsdata,$matching); //.innerHTML

			if(isset($matching[0]) && $matching[0] !="")
			$otherfile=2;
			else
			{
				preg_match('/\.(.*?)cloneNode(.*?)\(/i',$jsdata,$matching); //.cloneNode()

				if(isset($matching[0]) && $matching[0] !="")
				$otherfile=2;
				else
				{
					preg_match('/\.(.*?)insertAdjacentHTML(.*?)\(/i',$jsdata,$matching); //.insertAdjacentHTML()

					if(isset($matching[0]) && $matching[0] !="")
					$otherfile=2;
					else
					{
						preg_match('/\.(.*?)createDocumentFragment(.*?)\(/i',$jsdata,$matching); //.createDocumentFragment()

						if(isset($matching[0]) && $matching[0] !="")
						$otherfile=2;
						else
						{

							preg_match('/\.(.*?)createElement(.*?)\(/i',$jsdata,$matching); //.createElement()

							if(isset($matching[0]) && $matching[0] !="")
							$otherfile=2;
							else
							{
								preg_match('/\.(.*?)appendChild(.*?)\(/i',$jsdata,$matching); //.appendChild()

								if(isset($matching[0]) && $matching[0] !="")
								$otherfile=2;
								else
								{
									preg_match('/\.(.*?)insertBefore(.*?)\(/i',$jsdata,$matching); //.insertBefore()

									if(isset($matching[0]) && $matching[0] !="")
									$otherfile=2;
									else
									{
										preg_match('/href/i',$jsdata,$matching); //href

										if(isset($matching[0]) && $matching[0] !="")
										{
											preg_match_all('/\.(.*?)href(.*?)=(.*?);/i',$jsdata,$matchingdata);

											if(count($matchingdata[3]) ==0)
											$otherfile=2;
											else
											{
												foreach($matchingdata[3] as $k=>$v)
												{
													$href=trim($v);

													if($href != 'javascript:xyz_cta.trigger()')
													{
														$otherfile=2;
														break;
													}
												}
											}
										}


										if($otherfile ==0)
										{
											preg_match('/src/i',$jsdata,$matching); //src

											if(isset($matching[0]) && $matching[0] !="")
											$otherfile=2;
										}
									}
								}
							}
						}
					}
				}
			}

		}


		/**************** For javascript *********************/

		return $otherfile;
	}


	function validate_file_contents($filedata)
	{
		$otherfile=0;

		$scriptarray=$this->get_page_src($filedata);

		$allowed_js_urls="";

		if($html5_enabled ==1)
		$allowed_js_urls=trim(Configuration::get_instance()->read('allowed_js_urls'));

		if($allowed_js_urls !="")
		{
			$jsarray1=explode(',',$allowed_js_urls);

			foreach($jsarray1 as $k1=>$v1)
			{
				if(trim($v1) !="")
				$jsarray[]=strtolower(trim($v1));
			}
		}

		$jsarray[]=strtolower(BASE.DISPLAY_DIR."/js/cta.js");

		foreach($scriptarray as $k=>$v)
		{
			$datapath=strtolower(trim($v));

			if(!in_array($datapath,$jsarray))
			{

				$str_position=stripos($datapath,'http://');

				if($str_position === false)
				{
					$str_position=stripos($datapath,'https://');

					if($str_position === false)
					{
						$str_position=stripos($datapath,'//');

						if($str_position != false)
						{
							$otherfile=4;      //Unwanted 3rd party scripts / urls




							break;
						}
					}
					else
					{
						$otherfile=4;      //Unwanted 3rd party scripts / urls



						break;
					}
				}
				else
				{
					$otherfile=4;      //Unwanted 3rd party scripts / urls




					break;
				}
			}
		}







		if($otherfile ==0)
		{

			$scriptarray=$this->get_page_src_jquery($filedata);



			foreach($scriptarray as $k=>$v)
			{
				$datapath=strtolower(trim($v));

				$str_position=stripos($datapath,'http://');

				if($str_position === false)
				{
					$str_position=stripos($datapath,'https://');

					if($str_position === false)
					{
						$str_position=stripos($datapath,'//');

						if($str_position != false)
						{
							$otherfile=4;      //Unwanted 3rd party scripts / urls
							break;
						}
					}
					else
					{
						$otherfile=4;      //Unwanted 3rd party scripts / urls
						break;
					}
				}
				else
				{
					$otherfile=4;      //Unwanted 3rd party scripts / urls
					break;
				}
			}
		}




		if($otherfile ==0)
		{
			$scriptarray=$this->get_page_video_sources($filedata);

			foreach($scriptarray as $k=>$v)
			{
				$datapath1=strtolower(trim($v));

				$datapatharray=explode(',',$datapath1);

				foreach($datapatharray as $k1=>$v1)
				{
					$datapath=strtolower(trim($v1));

					if($datapath !="")
					{
						$str_position=stripos($datapath,'http://');

						if($str_position === false)
						{
							$str_position=stripos($datapath,'https://');

							if($str_position === false)
							{
								$str_position=stripos($datapath,'//');

								if($str_position != false)
								{
									$otherfile=20;      //Unwanted 3rd party video source
									break;
								}
							}
							else
							{
								$otherfile=20;      //Unwanted 3rd party video source
								break;
							}
						}
						else
						{
							$otherfile=20;      //Unwanted 3rd party video source
							break;
						}
					}
				}

				if($otherfile ==20)
				break;
			}
		}





			/**************** For jQuery *********************/

			if($otherfile ==0)
			{
				preg_match('/sources/i',$filedata,$matching); //sources video

				if(isset($matching[0]) && $matching[0] !="")
				{

				 	preg_match_all('/\.((\s+?)attr|attr)((\s+?)\(|\()((\s+?)["\']|["\'])((\s+?)sources|sources)((\s+?)["\']|["\'])((\s+?),|,)((\s+?)["\']|["\'])(.*?)((\s+?)["\']|["\'])((\s+?)\)|\))/i',$filedata,$matchingdata);

				 	preg_match_all('/\.((\s+?)setAttribute|setAttribute)((\s+?)\(|\()((\s+?)["\']|["\'])((\s+?)sources|sources)((\s+?)["\']|["\'])((\s+?),|,)((\s+?)["\']|["\'])(.*?)((\s+?)["\']|["\'])((\s+?)\)|\))/i',$filedata,$matchingdata1);


					if(count($matchingdata[15]) >0)
					{
						foreach($matchingdata[15] as $k=>$v)
						{
							$datapath=strtolower(trim($v));

							if($datapath !="")
							{
								$str_position=stripos($datapath,'http://');

								if($str_position === false)
								{
									$str_position=stripos($datapath,'https://');

									if($str_position === false)
									{
										$str_position=stripos($datapath,'//');

										if($str_position != false)
										{
											$otherfile=20;      //Unwanted 3rd party video source
											break;
										}
									}
									else
									{
										$otherfile=20;      //Unwanted 3rd party video source
										break;
									}
								}
								else
								{
									$otherfile=20;      //Unwanted 3rd party video source
									break;
								}
							}
						}
					}

					if($otherfile ==0)
					{
						if(count($matchingdata1[15]) >0)
						{
							foreach($matchingdata1[15] as $k=>$v)
							{
								$datapath=strtolower(trim($v));

								if($datapath !="")
								{
									$str_position=stripos($datapath,'http://');

									if($str_position === false)
									{
										$str_position=stripos($datapath,'https://');

										if($str_position === false)
										{
											$str_position=stripos($datapath,'//');

											if($str_position != false)
											{
												$otherfile=20;      //Unwanted 3rd party video source
												break;
											}
										}
										else
										{
											$otherfile=20;      //Unwanted 3rd party video source
											break;
										}
									}
									else
									{
										$otherfile=20;      //Unwanted 3rd party video source
										break;
									}
								}
							}
						}
					}


					if($otherfile ==0)
					{
						preg_match_all('/\.((\s+?)sources|sources)((\s+?)=|=)(.*?);/i',$filedata,$matchingdata);

						if(count($matchingdata[5]) >0)
						{
							foreach($matchingdata[5] as $k=>$v)
							{
								$datapath=strtolower(trim($v));

								if($datapath !="")
								{
									$str_position=stripos($datapath,'http://');

									if($str_position === false)
									{
										$str_position=stripos($datapath,'https://');

										if($str_position === false)
										{
											$str_position=stripos($datapath,'//');

											if($str_position != false)
											{
												$otherfile=20;      //Unwanted 3rd party video source
												break;
											}
										}
										else
										{
											$otherfile=20;      //Unwanted 3rd party video source
											break;
										}
									}
									else
									{
										$otherfile=20;      //Unwanted 3rd party video source
										break;
									}
								}
							}
						}
					}
				}
			}


			/**************** For jQuery *********************/


		if($otherfile ==0)    // For remove unwanted <link> tag
		{
			$linkarray=$this->get_page_link_tag($filedata);

			$allowed_font_urls=trim(Configuration::get_instance()->read('allowed_font_urls'));

			$fontarray=array();

			if($allowed_font_urls !="")
			{
				$fontarray1=explode(',',$allowed_font_urls);

				foreach($fontarray1 as $k1=>$v1)
				{
					if(trim($v1) !="")
					$fontarray[]=strtolower(trim($v1));
				}
			}


			foreach($linkarray as $k=>$v)
			{
				$datapath=strtolower(trim($v));

				if(!in_array($datapath,$fontarray))
				{
					$str_position=stripos($datapath,'http://');

					if($str_position === false)
					{
						$str_position=stripos($datapath,'https://');

						if($str_position === false)
						{
							$str_position=stripos($datapath,'//');

							if($str_position != false)
							{
								$otherfile=5;    //Unwanted 3rd <link> href
								break;
							}
						}
						else
						{
							$otherfile=5;    //Unwanted 3rd <link> href
							break;
						}
					}
					else
					{
						$otherfile=5;    //Unwanted 3rd <link> href
						break;
					}
				}
			}
		}




		if($otherfile ==0)    // For remove unwanted <base> tag
		{
			$basearray=$this->get_page_base_tag($filedata);

			if(count($basearray) >0)
			$otherfile=6;    //Unwanted <base> tag
		}


		if($otherfile ==0)   // For remove meta refresh
		{
			$metarefresh=$this->get_page_meta_tag($filedata);

			if($metarefresh ==1)
			$otherfile=7;   //Unwanted <meta> refresh
		}



		/*
		if($otherfile ==0) // For remove <iframe>
		{
			$str_position=strpos($filedata,'<iframe');

			if($str_position === false)
			{

			}
			else
			$otherfile=8;    //Iframe not supported
		}

		if($otherfile ==0) // For remove unwanted image paths
		{
			$imagearray=$this->get_page_img_tag($filedata);

			foreach($imagearray as $k=>$v)
			{
				$datapath=strtolower(trim($v));

				$str_position=strpos($datapath,'http://');

				if($str_position === false)
				{
					$str_position=strpos($datapath,'https://');

					if($str_position === false)
					{
						$str_position=strpos($datapath,'//');

						if($str_position != false)
						{
							$otherfile=9;    //Unwanted 3rd party urls
							break;
						}
					}
					else
					{
						$otherfile=9;    //Unwanted 3rd party urls
						break;
					}
				}
				else
				{
					$otherfile=9;    //Unwanted 3rd party urls
					break;
				}
			}
		}
		*/


		if($otherfile ==0) // For remove unwanted <a> href
		{
			$anchorarray=$this->get_page_anchor_tag($filedata);

			foreach($anchorarray as $k=>$v)
			{
				if($v !="")
				{
					$str_position=stripos($v,'javascript:xyz_cta.trigger()');

					if($str_position === 0)
					{

					}
					else
					{
						$otherfile=10;    //Unwanted href url
						break;
					}
				}
			}
		}



		/*
		if($otherfile ==0) // For remove unwanted document.location,window.location,window.open
		{
			$str_position=strpos($filedata,'document.location');

			if($str_position === false)
			{}
			else
			$otherfile=11;         //Document location not supported

			if($otherfile ==0)
			{
				$str_position=strpos($filedata,'window.location');

				if($str_position === false)
				{}
				else
				$otherfile=12;     //Window location not supported
			}
		}
		*/





		if($otherfile ==0)
		{

			//preg_match('/\.((\s+?)open|open)((\s+?)\(|\()/i',$filedata,$matching); //window.open()/document.open()

			//if(isset($matching[0]) && $matching[0] !="")
			//$otherfile=13;  //Window / Document open not supported


			if($otherfile ==0)
			{
				preg_match('/\.((\s+?)write|write)((\s+?)\(|\()/i',$filedata,$matching); //document.write()

				if(isset($matching[0]) && $matching[0] !="")
				$otherfile=14;  //Document.Write not supported
			}


			if($otherfile ==0)
			{
				preg_match('/\.((\s+?)ajax|ajax)((\s+?)\(|\()/i',$filedata,$matching); //ajax()

				if(isset($matching[0]) && $matching[0] !="")
				$otherfile=15;  //ajax() not supported
			}


			if($otherfile ==0)
			{
				preg_match('/\.((\s+?)send|send)((\s+?)\(|\()/i',$filedata,$matching); //send()

				if(isset($matching[0]) && $matching[0] !="")
				$otherfile=16;  //send() not supported
			}


			if($otherfile ==0)
			{
				preg_match('/XMLHttpRequest/i',$filedata,$matching); //XMLHttpRequest()

				if(isset($matching[0]) && $matching[0] !="")
				$otherfile=17;  //XMLHttpRequest() not supported
			}


			if($otherfile ==0)
			{
				preg_match('/alert((\s+?)\(|\()/i',$filedata,$matching); //alert()

				if(isset($matching[0]) && $matching[0] !="")
				$otherfile=18;  //alert() not supported
			}



			if($otherfile ==0) // For remove eval()
			{
				$str_position=stripos($filedata,'eval(');

				if($str_position === false)
				{

				}
				else
				$otherfile=19;    //eval() not supported
			}

			if($otherfile ==0) // For remove eval()
			{
				$str_position=stripos($filedata,'eval.');

				if($str_position === false)
				{

				}
				else
				$otherfile=19;    //eval() not supported
			}




			/**************** For jQuery *********************/

			if($otherfile ==0)
			{
				preg_match('/href/i',$filedata,$matching); //href

				if(isset($matching[0]) && $matching[0] !="")
				{

				 	preg_match_all('/\.((\s+?)attr|attr)((\s+?)\(|\()((\s+?)["\']|["\'])((\s+?)href|href)((\s+?)["\']|["\'])((\s+?),|,)((\s+?)["\']|["\'])(.*?)((\s+?)["\']|["\'])((\s+?)\)|\))/i',$filedata,$matchingdata);

				 	preg_match_all('/\.((\s+?)setAttribute|setAttribute)((\s+?)\(|\()((\s+?)["\']|["\'])((\s+?)href|href)((\s+?)["\']|["\'])((\s+?),|,)((\s+?)["\']|["\'])(.*?)((\s+?)["\']|["\'])((\s+?)\)|\))/i',$filedata,$matchingdata1);


					if(count($matchingdata[15]) >0)
					{
						foreach($matchingdata[15] as $k=>$v)
						{
							$href=trim($v);

							$href=str_replace(',','',$href);
							$href=str_replace('"','',$href);
							$href=str_replace("'",'',$href);
							$href=str_replace(";",'',$href);

							if($href != 'javascript:xyz_cta.trigger()')
							{
								$otherfile=10;   //Unwanted href url
								break;
							}
						}
					}

					if($otherfile ==0)
					{
						if(count($matchingdata1[15]) >0)
						{
							foreach($matchingdata1[15] as $k=>$v)
							{
								$href=trim($v);

								$href=str_replace(',','',$href);
								$href=str_replace('"','',$href);
								$href=str_replace("'",'',$href);
								$href=str_replace(";",'',$href);

								if($href != 'javascript:xyz_cta.trigger()')
								{
									$otherfile=10;   //Unwanted href url
									break;
								}
							}
						}
					}

				}
			}

			/**************** For jQuery *********************/




			/**************** For javascript *********************/

			if($otherfile ==0)
			{




				preg_match('/href/i',$filedata,$matching); //href

				if(isset($matching[0]) && $matching[0] !="")
				{
				 	preg_match_all('/\.((\s+?)href|href)((\s+?)=|=)(.*?);/i',$filedata,$matchingdata);

					if(count($matchingdata[5]) >0)
					{
						foreach($matchingdata[5] as $k=>$v)
						{
							$href=trim($v);

							if($href != 'javascript:xyz_cta.trigger()')
							{
								$otherfile=10;     //Unwanted href url
								break;
							}
						}
					}
				}
			}
			/**************** For javascript *********************/
		}

		return $otherfile;
	}
};
?>
