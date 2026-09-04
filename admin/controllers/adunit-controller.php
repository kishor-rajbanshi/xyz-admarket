<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";


if(file_exists(ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php"))
include_once ADDON_DIR_PATH."sponsored".DS."common".DS."helpers".DS."sponsored-helper.php";

if(file_exists(ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php"))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";


class AdunitController extends ApplicationController
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



			if(!isset($privilege['ac_1']) && !isset($privilege['ac_2']) && !isset($privilege['ac_3']) && !isset($privilege['ac_4']) && !isset($privilege['ac_5']))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if($this->get_action() !="manage_user_adcode" && $this->get_action() !="manage" && $this->get_action() !="create" && $this->get_action() !="edit" && $this->get_action() !="delete")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ac_1']) && $this->get_action() =="manage_user_adcode")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ac_2']) && $this->get_action() =="manage")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ac_3']) && $this->get_action() =="create")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ac_4']) && $this->get_action() =="edit")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ac_5']) && $this->get_action() =="delete")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
		}
	}


	function load_adcode_preview_action()
	{
		$this->disable_notice_area();
		$db = DAL::get_instance();

		$adcodeID 			    = intval($this->read_post_param('adcodeID'));
		$adcodeType 			= intval($this->read_post_param('adcodeType'));
		$adcodeTheme			= intval($this->read_post_param('adcodeTheme'));
		$borderType  			= intval($this->read_post_param('borderType'));
		$adLayoutID  			= intval($this->read_post_param('adLayoutID'));
		$stickySupport  		= intval($this->read_post_param('stickySupport'));
		$stickyClosePosition  	= intval($this->read_post_param('stickyClosePosition'));
		$responsiveSupport		= intval($this->read_post_param('responsiveSupport'));
		$adBlockID              = intval($this->read_post_param('adBlockID'));
		$stickyPosition  		= $this->read_post_param('stickyPosition');
		$windowWidth  			= $this->read_post_param('windowWidth');
		$windowHeight  			= $this->read_post_param('windowHeight');
		$deviceType  			= $this->read_post_param('deviceType');

		$nativeAdcode           	= intval($this->read_post_param('nativeAdcode'));
		$textimageDimension     	= intval($this->read_post_param('textimageDimension'));
		$textimagePosition      	= intval($this->read_post_param('textimagePosition'));
		$nativeAdsCount         	= intval($this->read_post_param('nativeAdsCount'));
		$nativeRows             	= intval($this->read_post_param('nativeRows'));
		$nativeColumns          	= intval($this->read_post_param('nativeColumns'));
		$previewSectionOuterWidth 	= intval($this->read_post_param('previewSectionOuterWidth'));
		$nativeHeading            	= $this->read_post_param('nativeHeading');




		$adblockSizeChanged     = 0;

		$heading_color 	         = "";
		$title_color 			 = "";
		$description_color 		 = "";
		$url_color 				 = "";
		$credit_text_color 		 = "";
		$cta_text_color 		 = "";
		$cta_button_color 		 = "";
		$border_color 			 = "";

		$title_hover_color 		 = "";
		$description_hover_color = "";
		$url_hover_color 		 = "";
		$heading_hover_color 	 = "";
		$cta_hover_color 		 = "";

		$adcode_background 		 = "";
		$title_background 		 = "";
		$description_background  = "";
		$url_background 		 = "";
		$heading_background 	 = "";
		$cta_background 		 = "";
		$image_background 		 = "";

		if($adcodeTheme == -1)
		{
			$heading_color  		    = $this->read_post_param('heading_color');
			$title_color  				= $this->read_post_param('title_color');
			$description_color  		= $this->read_post_param('description_color');
			$url_color  				= $this->read_post_param('url_color');
			$credit_text_color  		= $this->read_post_param('credit_text_color');
			$cta_text_color  			= $this->read_post_param('cta_text_color');
			$cta_button_color  			= $this->read_post_param('cta_button_color');
			$border_color  				= $this->read_post_param('border_color');
			$title_hover_color  		= $this->read_post_param('title_hover_color');
			$description_hover_color  	= $this->read_post_param('description_hover_color');
			$url_hover_color  			= $this->read_post_param('url_hover_color');
			$heading_hover_color  		= $this->read_post_param('heading_hover_color');
			$cta_hover_color  			= $this->read_post_param('cta_hover_color');
			$adcode_background  		= $this->read_post_param('adcode_background');
			$title_background  			= $this->read_post_param('title_background');
			$description_background  	= $this->read_post_param('description_background');
			$url_background  			= $this->read_post_param('url_background');
			$heading_background  		= $this->read_post_param('heading_background');
			$cta_background  			= $this->read_post_param('cta_background');
			$image_background  			= $this->read_post_param('image_background');
		}


		if($nativeAdcode == 0 && $responsiveSupport == 1)
		{
			$filePath    		= ROOT_DIR_PATH.CACHE_DIR."/configuration/responsive_adblock.php";
			$datacontent 		= file_get_contents($filePath);
			$datacontent 		= str_replace('<?php ','',$datacontent);
			$datacontent 		= str_replace(' ?>','',$datacontent);
			$datacontent_array  = json_decode($datacontent,1);

			if(isset($datacontent_array[$adBlockID][$deviceType]) && intval($datacontent_array[$adBlockID][$deviceType]) > 0)
			{
				$responsiveAdBlockID 	= intval($datacontent_array[$adBlockID][$deviceType]);

				if($responsiveAdBlockID > 0 && $responsiveAdBlockID != $adBlockID)
				$adblockSizeChanged  = 1;
			}
		}

		if($nativeAdcode == 0)
		{
			if($adblockSizeChanged == 0)
			$result     = $db->execute_query("select ab.*,au.* from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au where ab.id = au.blockid and au.id = ?",array($adcodeID));
			else
			$result     = $db->execute_query("select ab.*,au.* from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au where ab.id=? and au.id = ?",array($responsiveAdBlockID,$adcodeID));
		}
		else
		$result     = $db->execute_query("select au.* from ".TABLE_PREFIX."adunit au where au.id = ?",array($adcodeID));


		if($result->get_num_records() == 0)
		{
			echo ""; //No adcode or adblock exists
			die;
		}

		$resultData = $result->fetch_assoc();

		if($nativeAdcode == 1)
		{
			if($adLayoutID == 0)
			$adLayoutID  		    = $resultData['native_layout_id'];

			$adLayoutTypeDB  		= $adcodeType;
			$bannerSize  			= 0;
			$textimageSize  		= $textimageDimension;
			$imagePosition  		= $textimagePosition;
			$adblockWidth  			= 0;
			$adblockHeight  		= 0;
			$creditTextID  			= Configuration::get_instance()->read('native_credit_text');
			$creditAlignment  		= Configuration::get_instance()->read('native_creditalignment');
			$creditPositioning  	= Configuration::get_instance()->read('native_creditposition');
			$layoutThemeDB  	    = 1;
			$layoutFontDB           = 1;
		}
		else
		{
			if($adcodeType == 1 || $adcodeType == 3 || $adcodeType ==11)
			{
				$adLayoutList = json_decode($resultData['ad_layout_list']);

			if(count($adLayoutList) > 0)
			{
				$randomKey  = array_rand($adLayoutList);

				$adLayoutID = $adLayoutList[$randomKey];
			}
		}


			$adLayoutTypeDB  		= $resultData['type'];
			$bannerSize  			= $resultData['bannersize'];
			$textimageSize  		= $resultData['textimage_size'];
			$imagePosition  		= $resultData['image_position'];
			$adblockWidth  			= $resultData['width'];
			$adblockHeight  		= $resultData['height'];
			$creditTextID  			= $resultData['credit_text'];
			$creditAlignment  		= $resultData['creditalignment'];
			$creditPositioning  	= $resultData['creditposition'];
			$layoutThemeDB  	    = $resultData['theme'];
			$layoutFontDB           = $resultData['font'];
		}


		if($nativeAdcode == 1)
		{
			$responseResult = $this->get_adlayout_preview(6,1,$adcodeType,$adLayoutTypeDB,$adLayoutID,$borderType,$bannerSize,$textimageSize,$imagePosition,$adblockWidth,$adblockHeight,$creditTextID,$creditAlignment,$creditPositioning,$adcodeTheme,$layoutFontDB,$adcodeID,$layoutThemeDB,$layoutFontDB,$windowWidth,$windowHeight,$stickySupport,$stickyClosePosition,$stickyPosition,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,"","","","","",$heading_color,$title_color,$description_color,$url_color,$credit_text_color,$cta_text_color,$cta_button_color,$border_color,$title_hover_color,$description_hover_color,$url_hover_color,$heading_hover_color,$cta_hover_color,$adcode_background,$title_background,$description_background,$url_background,$heading_background,$cta_background,$image_background,$nativeAdcode,$nativeAdsCount,$nativeRows,$nativeColumns,$nativeHeading,$responsiveSupport,$previewSectionOuterWidth);

		}
		else
		{
		$responseResult = $this->get_adlayout_preview(1,1,$adcodeType,$adLayoutTypeDB,$adLayoutID,$borderType,$bannerSize,$textimageSize,$imagePosition,$adblockWidth,$adblockHeight,$creditTextID,$creditAlignment,$creditPositioning,$adcodeTheme,$layoutFontDB,$adcodeID,$layoutThemeDB,$layoutFontDB,$windowWidth,$windowHeight,$stickySupport,$stickyClosePosition,$stickyPosition,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,"","","","","",$heading_color,$title_color,$description_color,$url_color,$credit_text_color,$cta_text_color,$cta_button_color,$border_color,$title_hover_color,$description_hover_color,$url_hover_color,$heading_hover_color,$cta_hover_color,$adcode_background,$title_background,$description_background,$url_background,$heading_background,$cta_background,$image_background);
		}

		echo $responseResult;

		die;
	}




	function create_action()
	{
		$this->set_title($this->get_label('create new adunit'));

		$db= DAL::get_instance();

		$responsive_enabled  = $this->get_addon_status('responsive-ads_enabled');
		$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');

		$responsive_string = "";

		if($responsive_enabled == 1)
		$responsive_string = ",xsmall_dev_adblock,small_dev_adblock,medium_dev_adblock,large_dev_adblock";

		//Text
		$res1=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=1");
		$this->set_result("res1",$res1);

		//Text
		$resInterstitial1=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=1 AND type=1");
		$this->set_result("resInterstitial1",$resInterstitial1);

		//Text
		if($inpage_push_enabled == 1)
		{
			$resInpagePush1=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=1 AND allow_inpage_push_ads = 1");
			$this->set_result("resInpagePush1",$resInpagePush1);
		}

		//Banner
		$res2=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=2");
		$this->set_result("res2",$res2);

		//Banner
		$resInterstitial2=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=1 AND type=2");
		$this->set_result("resInterstitial2",$resInterstitial2);

		//Text/Banner/Text+Image
		$res3=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=3");
		$this->set_result("res3",$res3);

		//Text/Banner/Text+Image
		$resInterstitial3=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=1 AND type=3");
		$this->set_result("resInterstitial3",$resInterstitial3);

		//Text/Banner/Text+Image
		if($inpage_push_enabled == 1)
		{
			$resInpagePush3=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=3 AND allow_inpage_push_ads = 1");
			$this->set_result("resInpagePush3",$resInpagePush3);
		}

		//Text+Image
		$res11=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=4");
		$this->set_result("res11",$res11);

		//Text+Image
		$resInterstitial11=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=1 AND type=4");
		$this->set_result("resInterstitial11",$resInterstitial11);

		//Text+Image
		if($inpage_push_enabled == 1)
		{
			$resInpagePush11=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=4 AND allow_inpage_push_ads = 1");
			$this->set_result("resInpagePush11",$resInpagePush11);
		}
		
		//Video Ads
        	$res13=$db->execute_query("select id,height,width,type,name".$responsive_string." from ".TABLE_PREFIX."adblock where status=1 AND banner_type=5 AND type=5");
		$this->set_result("res13",$res13);

		$res13_linear=$db->execute_query("select id,height,width from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type=0 AND vast_video_support =1");
		$this->set_result("res13_linear",$res13_linear);
		
			
		//Skin Ads
		$res14=$db->execute_query("select * from ".TABLE_PREFIX."adblock where status=1 AND banner_type=4 AND type=2");
		$this->set_result("res14",$res14);

		$category_enabled=$this->get_addon_status('category-targeting_enabled');
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$sticky_enabled=$this->get_addon_status('sticky-ad-display_enabled');
		$directlink_enabled =$this->get_addon_status('direct-link-ads_enabled');
		
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

		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;

		if($cpc_enabled !=1 && $cpm_enabled !=1 && $cpa_enabled !=1 && $sponsored_enabled !=1)
		{
			$this->flash($this->get_message('no active adcode type exists'), $this->make_url('adunit/manage'),0);
			exit;
		}


		$adcode_for=0;
		$player_size=0;
		$linear=0;
		$nonlinearbanner=0;
		$nonlineartext=0;
		$nonlinear_size=0;

		$popup_support  = 0;
		$poptab_support = 0;
		$pop_type = 0; // 0 => New tab, 1 => POP Up

		$sticky_support=0;
		$sticky_position="";
		$sticky_close_position=0;

				

		$responsive_support = 0;  // For non native ads

		$sid=0;
		$stringarray=array();
		if($category_enabled ==1)
		{
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

			if($category_enabled_ads !='')
			$stringarray=explode('_',$category_enabled_ads);
		}

		$adpricing=0;
		$skin_array =array();

		if($_POST)
		{
			$name               = $this->read_post_param('name');
			$adpricing          = $this->read_post_param('adpricing');
			$responsive_support = intval($this->read_post_param('responsive_support'));

			if($category_enabled == 1)
		 	{
		 		if($adpricing == 3)
	 			$sid=intval($this->read_post_param('sid_select_01'));
	 			else
	 			$sid=intval($this->read_post_param('sid_select_00'));
		 	}


			$adblocktype = intval($this->read_post_param('adblocktype'));


			if($adblocktype != 9 && $adblocktype != 13 && $adblocktype != 14 && $adblocktype != 21)
			$adblockFormatType = intval($this->read_post_param('adblockFormatType'));
			else 
			$adblockFormatType = 0;



			if($adblocktype == 21) //Direct Link 
			$sid = 0;
			        
			$blockid=0;

			//Exclude Video/POP/Direct Link
			if($adblocktype != 9 && $adblocktype != 13 && $adblocktype != 21)
			{
				if($adblockFormatType == 1)
				$blockid=intval($this->read_post_param('blockid_interstitial_'.$adblocktype));
				else if($adblockFormatType == 2)
				$blockid=intval($this->read_post_param('blockid_inpagepush_'.$adblocktype));
				else 
				$blockid=intval($this->read_post_param('blockid_'.$adblocktype));
				
				
				
			
			
			

			}


			if($adblocktype ==14)
			$container_id=$this->read_post_param('container_id');

			if($adblocktype ==9)
			{
				$pop_type = intval($this->read_post_param('pop_type'));

				if($pop_type == 1)
				$popup_support  = 1;
				else
				$poptab_support = 1;
			}

			if($adblocktype == 13)
			{
				$adcode_for=intval($this->read_post_param('adcode_for'));

				if($adcode_for ==2)
				$blockid=intval($this->read_post_param('player_size'));

				if($adcode_for ==1)
				{
					if($linear_support ==1)
					$linear=1;

					$nonlinearbanner=intval($this->read_post_param('nonlinearbanner'));
					$nonlineartext=intval($this->read_post_param('nonlineartext'));
				}

				if($nonlinearbanner ==1)
				$nonlinear_size=intval($this->read_post_param('nonlinear_size'));
			}

			if($adcode_for ==1)
			$player_data=$nonlinear_size;
			else if($adcode_for ==2)
			$player_data=$blockid;

			$existing='';
			if($sponsored_enabled ==1 && $adpricing ==3)
			$existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND sid=? AND name=? AND blockid=?",array(0,$sid,$name,$blockid));
			
			if($sticky_enabled ==1 && $adblockFormatType == 0 && ($adblocktype == 1 || $adblocktype == 2 || $adblocktype == 3 || $adblocktype == 11 || ($adblocktype == 13 && $adcode_for == 2)))
			$sticky_support=$this->read_post_param('sticky_support');

			if($sticky_support ==1)
			{
				$sticky_position       = $this->read_post_param('sticky_position');
				$sticky_close_position = $this->read_post_param('sticky_close_position');
			}

			$siteCheckingPricing = $adpricing;

			if($adblocktype == 9)
			$siteCheckingPricing = $adblocktype;

			if(($adblocktype == 1 || $adblocktype == 2 || $adblocktype == 3 || $adblocktype == 9 || $adblocktype == 11 || $adblocktype ==13 || $adblocktype == 14) && $category_enabled ==1 && $sid ==0 && in_array($siteCheckingPricing, $stringarray))
			$this->set_notice("please select a targeting site");

			else if($adblocktype ==13 && $adcode_for ==2 && $blockid ==0)
			$this->set_notice("please select html5 player dimension");
			else if($adblocktype !=9 && $adblocktype !=13 && $adblocktype !=21 && $blockid ==0)
			$this->set_notice("please select a adblock");

			else if($adblocktype ==13 && $adcode_for ==0)
			$this->set_notice("please select a player type");
			else if($adblocktype ==13 && $adcode_for ==1 && $nonlinearbanner ==1 && $nonlinear_size ==0)
			$this->set_notice("please select nonlinear banner dimension");
			else if($adblocktype ==13 && $adcode_for ==1 && $linear ==0 && $nonlinearbanner ==0 && $nonlineartext ==0)
			$this->set_notice("you have no privilege for creating CPV adcodes");


			else if($sponsored_enabled ==1 && $adpricing ==3 && $name =='')
			$this->set_notice("mandatory");
			else if($sponsored_enabled ==1 && $adpricing ==3 && $existing !='')
			$this->set_notice("same adcode already exists");
			else if($category_enabled ==1 && $sid >0 && !CategoryHelper::get_site_exists($sid,0))
			$this->set_notice("site invalid");
			else
			{
				if(mb_strlen($name) >25)
				$name=substr($name,0,25);

				if($adblocktype != 9 && $adblocktype != 21)
				{
					$res=$db->execute_query("select * from ".TABLE_PREFIX."adblock where id=?",array($blockid));
					$result=$res->fetch_assoc();

                    			$displayAdblocktype = $adblocktype;

					if($displayAdblocktype == 2 || $displayAdblocktype == 13)
					$adcodeTheme = $result['theme'];
					else
					$adcodeTheme = 0;

					if($adblockFormatType == 1)
					{
						$adcodeType = 5;
						$adType     = $displayAdblocktype;
					}
					else if($adblockFormatType == 2)
					{
						$adcodeType = 19;
						$adType     = $displayAdblocktype;
					}
					else 
					{
						$adcodeType = $displayAdblocktype;
						$adType     = $displayAdblocktype;
					}

					$qrs=$db->execute_query("insert into ".TABLE_PREFIX."adunit (`id`,`pubid`,`blockid`,`name`,`abr_type`,`status`,`display_type`,`adcode_type`,`ad_type`,`adcode_theme`) values (?,?,?,?,?,?,?,?,?,?)",array('',0,$blockid,$name,$result['bordertype'],1,$adpricing,$adcodeType,$adType,$adcodeTheme));
				}
				else if($adblocktype == 9)
				$qrs=$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (`id`,`pubid`,`name`,`status`,`display_type`,`adcode_type`,`ad_type`,`pop_up_support`,`pop_tab_support`) values (?,?,?,?,?,?,?,?,?)",array('',0,$name,1,$adpricing,$adblocktype,$adblocktype,$popup_support,$poptab_support));
				else if($adblocktype == 21)
				$qrs=$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (`id`,`pubid`,`name`,`status`,`display_type`,`adcode_type`,`ad_type`) values (?,?,?,?,?,?,?)",array('',0,$name,1,$adpricing,$adblocktype,$adblocktype));

				if($qrs->error == "")
				{
					$last_id=$qrs->get_last_id();

					if($name == '')
					{
						$name='AdCode-'.$last_id;
						$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET name=? WHERE id=?",array($name,$last_id));
					}

					if($sponsored_enabled == 1 && $adpricing == 3)
					{
						$maximum_ads = intval(Configuration::get_instance()->read('max_cpd_ads_count_per_slot'));
						$cpd_rate    = Configuration::get_instance()->read('cpd_package_rate');


						$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET maximum_ads = ?,cpd_rate = ? WHERE id = ?",array($maximum_ads,$cpd_rate,$last_id));
					}


					if($responsive_enabled == 1 && $adblockFormatType != 2 && $adpricing != 3 && $adblocktype != 9 && $adblocktype != 21 && ($adblocktype != 13 || ($adblocktype == 13 && $adcode_for == 2)) && $adblocktype !=14)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `responsive_support`=? WHERE id=?",array($responsive_support,$last_id));

					if($category_enabled ==1)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET sid=? WHERE id=?",array($sid,$last_id));

					if($video_enabled == 1 && $adblocktype == 13)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `player_size`=?,`linear`=?,`non_linear_text`=?,`non_linear_banner`=?,`video_type`=? WHERE id=?",array($player_data,$linear,$nonlineartext,$nonlinearbanner,$adcode_for,$last_id));
					
					if($adblocktype == 14)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `container_id`=? WHERE id=?",array($container_id,$last_id));

					if($sticky_support == 1)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET sticky_support=?,sticky_position=?,sticky_close_position=? WHERE id=?",array($sticky_support,$sticky_position,$sticky_close_position,$last_id));

					$this->flash($this->get_message('adunit create'), $this->make_url('adunit/edit/'.$last_id));
				}
				else
				$this->set_notice("error occurred");
			}

			$this->set_variable("name",$name);
			$this->set_variable("blockid",$blockid);
			$this->set_variable("adblocktype",$adblocktype);
			$this->set_variable("adblockFormatType",$adblockFormatType);
		}

		$this->set_variable('responsive_support',$responsive_support);
		$this->set_variable('responsive_enabled',$responsive_enabled);

		$this->set_variable('sticky_support',$sticky_support);
		$this->set_variable('sticky_position',$sticky_position);
		$this->set_variable('sticky_close_position',$sticky_close_position);

		$this->set_variable("sid",$sid);
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable('pop_type',$pop_type);
		$this->set_variable('pop_enabled',$pop_enabled);

		$this->set_variable('adcode_for',$adcode_for);
		$this->set_variable('linear',$linear);
		$this->set_variable('nonlinearbanner',$nonlinearbanner);
		$this->set_variable('nonlineartext',$nonlineartext);
		$this->set_variable('nonlinear_size',$nonlinear_size);
	}
	function manage_action()
	{		
		$this->set_title($this->get_label('manage adunits'));
		$db= DAL::get_instance();

		if($_POST)
		{
			$adpricing=intval($this->read_post_param("adpricing"));
			$sid=intval($this->read_post_param("sid"));
		}
		else
		{
			$adpricing=$this->read_page_param(1);
			$sid=$this->read_page_param(2);

			$exp=explode("-",$adpricing);
			if($exp[0]=="page")
			$adpricing=-1;

			$exp=explode("-",$sid);
			if($exp[0]=="page")
			$sid=0;
		}

		if($adpricing === '')
		$adpricing=-1;

		if($sid === '')
		$sid=0;

		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("sid",$sid);

		$text_ads_enabled   = Configuration::get_instance()->read('text-ads_enabled');
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$cpp_enabled=$this->get_addon_status('cpp_enabled');
		$interstitial_enabled = $this->get_addon_status('interstitial_enabled');
		$textimage_enabled    = $this->get_addon_status('text-image-ads_enabled');
		$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');

		$directlink_enabled = $this->get_addon_status('direct-link-ads_enabled');

		$directlink_availability = 0;

		if($directlink_enabled == 1)
		$directlink_availability = 1;

		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;

		$adpricing_str = "";
		$adtype_str    = "";

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


		if($cpp_enabled !=1)
		$adpricing_str.=' AND display_type <>18 ';


		if($interstitial_enabled !=1)
		$adtype_str.=' a.adcode_type <> 5 ';

		if($text_ads_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>1 ';
		}

		if($textimage_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <> 11 ';
		}
		
		if($cpv_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';
			$adtype_str.=' a.adcode_type <> 13 ';
		}
		if($inpage_push_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>19 ';
		}

		if($skin_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <> 14 ';
		}

		if($pop_enabled != 1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>9 ';			
		}

		if($directlink_availability != 1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>21 ';			
		}

		if($adtype_str !="")
		$adtype_str = ' AND (('.$adtype_str.') OR ab.banner_type IS NULL) ';

		$sidquery='';
		if($this->get_addon_status('category-targeting_enabled') ==1)
		{
			if($sid >0)
			$sidquery=' AND sid='.intval($sid).' ';
		}

		$pagination = new Pagination("select a.*,ab.name as abname,ab.type,ab.banner_type,ab.width,ab.height,ab.textimage_size from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE pubid=0 AND a.adcode_type <>22 ".$adpricing_str." ".$sidquery." ".$adtype_str." ORDER BY a.id desc");
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	}

	function manage_user_adcode_action()
	{
		$db= DAL::get_instance();
		$mngr_str='';
		$usr_str1='';

		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
			$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
			if($admintype ==3)
			{
				$mngr_str=" and pub_managerid=".$adminid;

				$usr_str=$this->get_users_under_manager($admintype,$adminid);
				if($usr_str!='')
				$usr_str1=" and pubid in (".$usr_str.")";
				else 
				$usr_str1=" and pubid =-2 ";
			}
		}
		$this->set_title($this->get_label('manage user adunits'));
		$search_by="";
		$search_text="";
		$pub=-1;
		if($_POST)
		{
			$adpricing=intval($this->read_post_param("adpricing"));
			$search_by=$this->read_post_param("search_by");

			if($search_by==1)
            $search_text=$this->read_post_param("uid");
			else if($search_by==2)
		    $search_text=$this->read_post_param("username");
			else if($search_by==3)
			$search_text=$this->read_post_param("name");
			else if($search_by==4)
			$search_text=$this->read_post_param("url");
			else
			$search_text="";
		}
		else
		{
			$pub=$this->read_page_param(1);
			$adpricing=$this->read_page_param(2);

			$exp=explode("-",$pub);
			if($exp[0]=="page")
			$pub=-1;

			$exp=explode("-",$adpricing);
			if($exp[0]=="page")
			$adpricing=-1;
		}

		$search_text = str_replace("%","\%",$search_text);
		$search_text = str_replace("_","\_",$search_text);

		$search_string='';
		if($pub === '')
		$pub=-1;

		if($adpricing === '')
		$adpricing=-1;

		$sitejoin='';

		if($pub==-1)
		$pub_string="";
		else
		$pub_string=" and pubid=".intval($pub)." ";


		if($search_by == 1)
		$search_string = " and a.id=".intval($search_text)." ";
		else if($search_by == 2)
		$search_string = " and u.username LIKE '%".$db->sanitize($search_text)."%' ";

		else if($search_by == 3)
		$search_string = " and a.name LIKE '%".$db->sanitize($search_text)."%' ";

		else if($search_by == 4)
		{
			$search_string = " and s.url LIKE '%".$db->sanitize($search_text)."%' ";
			$sitejoin="JOIN ".TABLE_PREFIX."sites s ON a.sid=s.id";
		}
		else
		$search_string =" ";

		$this->set_variable("adpricing",$adpricing);
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$cpp_enabled=$this->get_addon_status('cpp_enabled');
		$directlink_enabled   = $this->get_addon_status('direct-link-ads_enabled');
		$feedads_enabled      = $this->get_addon_status('feed-ads_enabled');
		$textimage_enabled    = $this->get_addon_status('text-image-ads_enabled');
		$interstitial_enabled = $this->get_addon_status('interstitial_enabled');
		$native_enabled       = $this->get_addon_status('native-ad-display_enabled');
		$inpage_push_enabled = $this->get_addon_status('inpage-push-ads_enabled');

		$text_ads_enabled     = Configuration::get_instance()->read('text-ads_enabled');

		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;

		$directlink_availability = 0;

		if($directlink_enabled == 1)
		$directlink_availability = 1;

		if($feedads_enabled == 1)
		$feedads_enabled    = intval(Configuration::get_instance()->read('supply_feed_ads'));

		$this->set_variable("feedads_enabled",$feedads_enabled);

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


		if($cpp_enabled !=1)
		$adpricing_str.=' AND display_type <>18 ';

		if($interstitial_enabled !=1)
		$adtype_str.=' a.adcode_type <> 5 ';

		if($text_ads_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>1 ';
		}

		if($textimage_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <> 11 ';
		}
		
		if($cpv_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';
			$adtype_str.=' a.adcode_type <> 13 ';
		}
		if($inpage_push_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>19 ';
		}

		if($skin_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <> 14 ';
		}

		if($pop_enabled != 1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>9 ';			
		}

		if($affiliate_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>12 ';			
		}

		if($feedads_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';

			$adtype_str.=' a.adcode_type <>17 ';
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
		$adtype_str=' AND (('.$adtype_str.') OR ab.banner_type IS NULL) ';

		$this->set_variable("pub",$pub);

		$pagination = new Pagination("select a.*,ab.name as abname,ab.type,ab.banner_type,ab.width,ab.height,ab.textimage_size from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid  JOIN ".TABLE_PREFIX."users u ON a.pubid=u.id ".$sitejoin." WHERE pubid<>0 AND a.adcode_type <> 22 ".$pub_string.$adpricing_str." ".$adtype_str." ".$usr_str1. " ".$search_string." ".$native_str." ORDER BY a.id desc");
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);

		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);

		$search_text = str_replace("\%","%",$search_text);
		$search_text = str_replace("\_","_",$search_text);

		$this->set_variable("search_by",$search_by);
		if($search_by==1)
		$this->set_variable("uid",$search_text);
		else if($search_by==2)
		$this->set_variable("username",$search_text);
		else if($search_by==3)
		$this->set_variable("adcodename",$search_text);
		else if($search_by==4)
		$this->set_variable("url",$search_text);
	    else
	    $search_text="";
	}

	function edit_action()
	{
		$this->set_title($this->get_label('edit adunit'));
		$db= DAL::get_instance();

		if($_POST)
		$id=$this->read_post_param('aduid');
		else
		$id=$this->read_page_param(1);

		$overrideTheme 	= Configuration::get_instance()->read('allow_publishers_to_override_theme');

		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$category_enabled=$this->get_addon_status('category-targeting_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$responsive_enabled=$this->get_addon_status('responsive-ads_enabled');
		$sticky_enabled=$this->get_addon_status('sticky-ad-display_enabled');
		$native_enabled=$this->get_addon_status('native-ad-display_enabled');


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


        $res14=$db->execute_query("select id,height,width from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type=0 AND vast_video_support =1");
		$this->set_result("res14",$res14);


		$adcode_for=0;
		$linear=0;
		$nonlinearbanner=0;
		$nonlineartext=0;
		$nonlinear_size=0;
		$responsive_support = 0;

		$sticky_support=0;
		$sticky_position='';
		$sticky_close_position=0;


		$res = $db->execute_query("SELECT au.*,au.id as auid,au.name as auname FROM ".TABLE_PREFIX."adunit au WHERE id = ?",array($id));

		if($res->get_num_records() == 0)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/manage'),0);
		
		$adcodeRowData = $res->fetch_assoc();

		$uid          = $adcodeRowData['pubid'];
		$blockid      = $adcodeRowData['blockid'];
		$adcodeType   = $adcodeRowData['ad_type']; 
		$adcodeFormat = $adcodeRowData['adcode_type']; 
		$nativeAdcode = $adcodeRowData['native']; 
		$pricing      = $adcodeRowData['display_type']; 
		

		if($adcodeType == 18)
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('index/control_panel'),0);
			exit;
		}

		$feed_auth_key      = "";

		if($adcodeFormat == 17) //Feed Type
		{
			$feedads_enabled    = intval($this->get_addon_status('feed-ads_enabled'));

			if($feedads_enabled == 1)
			$feedads_enabled    = intval(Configuration::get_instance()->read('supply_feed_ads'));

			if($feedads_enabled == 1)
			{
				$userRow  = $db->execute_query("SELECT supply_feed_ads,feed_auth_key FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));

				$userData = $userRow->fetch_assoc();

				$feedads_enabled = intval($userData['supply_feed_ads']);
				$feed_auth_key   = $userData['feed_auth_key'];
			}
		}

		$this->set_variable("feed_auth_key",$feed_auth_key);
		$this->set_variable("adcodeType",$adcodeType);
		$this->set_variable("adcodeFormat",$adcodeFormat);

		

		$sid=0;
		$stringarray=array();
		if($category_enabled ==1)
		{
			if($_POST)
			$sid=intval($this->read_post_param('sid'));

			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

			if($category_enabled_ads !='')
			$stringarray=explode('_',$category_enabled_ads);

			//if(CategoryHelper::get_site_count_user(0) ==0)
			//{
			//	$this->flash($this->get_message('no active sites'), $this->make_url('adunit/manage'),0);
			//}
		}


		if(($adcodeFormat !=9 && $adcodeFormat != 13 && $adcodeFormat != 17 && $adcodeFormat != 21) || ($adcodeFormat == 13 && $blockid > 0))
		$res = $db->execute_query("select ab.*,au.*,au.id as auid,au.name as auname,abr_type from ".TABLE_PREFIX."adunit au LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=au.blockid WHERE au.id=?", array($id));

		$this->set_result("res",$res);
	
		$resFontExternal = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."external_fonts ORDER BY id DESC");
		$this->set_result("resFontExternal",$resFontExternal);
	
		$resTheme   = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."adblock_theme");
		$this->set_result("resTheme",$resTheme);
	
		$layoutID    = 0;
		$layoutTheme = 1;
	
		if($adcodeType == 1 || $adcodeType == 3 || $adcodeType == 11)
		{
			$resAdblock        = $res->fetch_assoc();
	
			$adLayoutListArray = json_decode($resAdblock['ad_layout_list']);
	
			if($nativeAdcode == 1)
			$layoutID          = intval($resAdblock['native_layout_id']);
			else
			{
				if(count($adLayoutListArray) > 0)
				{
					$randomKey         = array_rand($adLayoutListArray,1);

					$layoutID          = intval($adLayoutListArray[$randomKey]);
				}
			}

			if($layoutID > 0)
			{				
				if($nativeAdcode == 1)
				$layoutType = 2;
				else
				$layoutType = 1;


				$resLayout  = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_layout WHERE layout_type = ? AND id = ?",array($layoutType,$layoutID));

				if($resLayout->get_num_records() > 0)
				{
					$resLayoutData = $resLayout->fetch_assoc();

					$layoutID      = $resLayoutData['id'];

					if($resAdblock['adcode_theme'] > 0)
					$layoutTheme   = $resAdblock['adcode_theme'];
					else
					{
						if(($overrideTheme == 0 && $resAdblock['adcode_theme'] == -1) || $resAdblock['adcode_theme'] == 0)
						$layoutTheme   = $resLayoutData['layout_theme'];
						else if($overrideTheme == 1 && $resAdblock['adcode_theme'] == -1)
						$layoutTheme   = -1;
					}
				}
				else
				$layoutID = 0;
			}
		}	
		else if($adcodeType == 2 || ($adcodeType == 13 && $blockid > 0))
		{
			$resAdblock        = $res->fetch_assoc();
	
			if($resAdblock['adcode_theme'] > 0)
			$layoutTheme   = $resAdblock['adcode_theme'];
			else
			{
				if(($overrideTheme == 0 && $resAdblock['adcode_theme'] == -1) || $resAdblock['adcode_theme'] == 0)
				$layoutTheme   = $resAdblock['theme'];
				else if($overrideTheme == 1 && $resAdblock['adcode_theme'] == -1)
				$layoutTheme   = -1;
			}
		}	

		if($sponsored_enabled == 1 && $pricing == 3)
		{
			$maximum_ads_db = intval(Configuration::get_instance()->read('max_cpd_ads_count_per_slot'));
			$cpd_rate_db    = Configuration::get_instance()->read('cpd_package_rate');

			$this->set_variable("maximum_ads_db",$maximum_ads_db);
			$this->set_variable("cpd_rate_db",$cpd_rate_db);
		}


		$heading_color 	         = "";
		$title_color 			 = "";
		$description_color 		 = "";
		$url_color 				 = "";
		$credit_text_color 		 = "";
		$cta_text_color 		 = "";
		$cta_button_color 		 = "";
		$border_color 			 = "";

		$title_hover_color 		 = "";
		$description_hover_color = "";
		$url_hover_color 		 = "";
		$heading_hover_color 	 = "";
		$cta_hover_color 		 = "";

		$adcode_background 		 = "";
		$title_background 		 = "";
		$description_background  = "";
		$url_background 		 = "";
		$heading_background 	 = "";
		$cta_background 		 = "";
		$image_background 		 = "";

		$popup_support  = 0;
		$poptab_support = 0;
		$pop_type = 0; // 0 => New tab, 1 => POP Up
		

		if($_POST)
		{
			if(DEMO_MODE && $id <= 50)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('adunit/edit/'.$id),0);
				exit;
			}

			$id=$this->read_post_param('aduid');
			$name=$this->read_post_param('aduname');
			$adpricing=$this->read_post_param('adpricing');
			$borderType  = $this->read_post_param('borderType');
			$adcodeTheme = intval($this->read_post_param('adcode_theme'));


				if($overrideTheme == 0 && $adcodeTheme == -1 && ($adcodeType == 2 || ($adcodeType == 13 && $blockid > 0)))
				$adcodeTheme = 1;
				else if($overrideTheme == 0 && $adcodeTheme == -1)
				$adcodeTheme = 0;


				if($adcodeTheme == -1 && $adcodeType != 1 && $adcodeType != 2 && $adcodeType != 3 && $adcodeType != 11 && !($adcodeType == 13 && $blockid > 0))
				$adcodeTheme = 0;

			if($adcodeType ==9)
			{
				$pop_type = intval($this->read_post_param('pop_type'));

				if($pop_type == 1)
				$popup_support  = 1;
				else
				$poptab_support = 1;

				$this->set_variable('pop_type',$pop_type);
			}

			if($adcodeType ==13)
			{
				$adcode_for=intval($this->read_post_param('adcode_for'));

				if($adcode_for ==1)
				{
					if($linear_support ==1)
					$linear=1;

					$nonlinearbanner=intval($this->read_post_param('nonlinearbanner'));
					$nonlineartext=intval($this->read_post_param('nonlineartext'));
				}

				if($nonlinearbanner ==1)
				$nonlinear_size=intval($this->read_post_param('nonlinear_size'));
			}

			if($adcode_for ==1)
			$player_data=$nonlinear_size;
			else if($adcode_for ==2)
			$player_data=$blockid;


			

			if($sticky_enabled ==1 && $adcodeFormat != 5 && $adcodeFormat != 17 && $adcodeFormat != 19 && $adcodeFormat != 21 && ($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 3 || $adcodeType == 11 || ($adcodeType == 13 && $adcode_for == 2)))
			$sticky_support=intval($this->read_post_param('sticky_support'));

			if($sticky_support ==1)
			{
				$sticky_position=$this->read_post_param('sticky_position');
				$sticky_close_position=$this->read_post_param('sticky_close_position');
			}

			if($adpricing != 3 && $adcodeType != 9 && $adcodeFormat != 17 && $adcodeFormat != 21 && ($adcodeType != 13 || ($adcodeType == 13 && $adcode_for == 2)))
			$responsive_support = intval($this->read_post_param('responsive_support'));

			$existing='';
			if($sponsored_enabled ==1 && $adpricing ==3)
			$existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND sid=? AND name=? AND id<>?",array(0,$sid,$name,$id));


			if($sponsored_enabled == 1 && $adpricing == 3)
			{
				$maximum_ads = intval($this->read_post_param('maximum_ads'));
				$cpd_rate    = $this->read_post_param('cpd_rate');

				if($maximum_ads == 0 || $maximum_ads > $maximum_ads_db)
				$maximum_ads = $maximum_ads_db;

				if($cpd_rate < $cpd_rate_db)
				$cpd_rate = $cpd_rate_db;

				$this->set_variable("maximum_ads",$maximum_ads);
				$this->set_variable("cpd_rate",$cpd_rate);
			}

				if($adcodeTheme == -1)
				{
					$heading_color 	         = $this->read_post_param('heading_color');
					$title_color 			 = $this->read_post_param('title_color');
					$description_color 		 = $this->read_post_param('description_color');
					$url_color 				 = $this->read_post_param('url_color');
					$credit_text_color 		 = $this->read_post_param('credit_text_color');
					$cta_text_color 		 = $this->read_post_param('cta_text_color');
					$cta_button_color 		 = $this->read_post_param('cta_button_color');
					$border_color 			 = $this->read_post_param('border_color');

					$title_hover_color 		 = $this->read_post_param('title_hover_color');
					$description_hover_color = $this->read_post_param('description_hover_color');
					$url_hover_color 		 = $this->read_post_param('url_hover_color');
					$heading_hover_color 	 = $this->read_post_param('heading_hover_color');
					$cta_hover_color 		 = $this->read_post_param('cta_hover_color');

					$adcode_background 		 = $this->read_post_param('adcode_background');
					$title_background 		 = $this->read_post_param('title_background');
					$description_background  = $this->read_post_param('description_background');
					$url_background 		 = $this->read_post_param('url_background');
					$heading_background 	 = $this->read_post_param('heading_background');
					$cta_background 		 = $this->read_post_param('cta_background');
					$image_background 		 = $this->read_post_param('image_background');



					$defaultThemeResult      = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."adblock_theme WHERE id = 1");

					$defaultThemeData        = $defaultThemeResult->fetch_assoc();

					if($heading_color == "")
					$heading_color 	         = $defaultThemeData['heading_color'];

					if($title_color == "")
					$title_color 			 = $defaultThemeData['title'];

					if($description_color == "")
					$description_color 		 = $defaultThemeData['description'];

					if($url_color == "")
					$url_color 				 = $defaultThemeData['url'];

					if($credit_text_color == "")
					$credit_text_color 		 = $defaultThemeData['credit'];

					if($cta_text_color == "")
					$cta_text_color 		 = $defaultThemeData['button'];

					if($cta_button_color == "")
					$cta_button_color 		 = $defaultThemeData['button_background'];

					if($border_color == "")
					$border_color 			 = $defaultThemeData['border'];

					if($title_hover_color == "")
					$title_hover_color 		 = $defaultThemeData['title_hover_color'];

					if($description_hover_color == "")
					$description_hover_color = $defaultThemeData['description_hover_color'];

					if($url_hover_color == "")
					$url_hover_color 		 = $defaultThemeData['url_hover_color'];

					if($heading_hover_color == "")
					$heading_hover_color 	 = $defaultThemeData['heading_hover_color'];

					if($cta_hover_color == "")
					$cta_hover_color 		 = $defaultThemeData['button_hover'];

					if($adcode_background == "")
					$adcode_background 		 = $defaultThemeData['background'];

					if($title_background == "")
					$title_background 		 = $defaultThemeData['title_background'];

					if($description_background == "")
					$description_background  = $defaultThemeData['description_background'];

					if($url_background == "")
					$url_background 		 = $defaultThemeData['url_background'];

					if($heading_background == "")
					$heading_background 	 = $defaultThemeData['heading_background'];

					if($cta_background == "")
					$cta_background 		 = $defaultThemeData['cta_background'];

					if($image_background == "")
					$image_background 		 = $defaultThemeData['image_background'];
				}

				$siteCheckingPricing = $adpricing;

				if($adcodeType == 9)
				$siteCheckingPricing = $adcodeType;

				if($adcodeFormat != 17 && ($adcodeType == 1 || $adcodeType == 2 || $adcodeType == 3 || $adcodeType == 5 || $adcodeType == 9 || $adcodeType == 11 || $adcodeType == 13 || $adcodeType == 14) && $category_enabled ==1 && $sid ==0 && in_array($siteCheckingPricing, $stringarray))
				$this->set_notice("please select a targeting site");

				else if($adcodeType ==13 && $adcode_for ==0)
				$this->set_notice("please select a player type");
				else if($adcodeType ==13 && $adcode_for ==1 && $nonlinearbanner ==1 && $nonlinear_size ==0)
				$this->set_notice("please select nonlinear banner dimension");
				else if($adcodeType ==13 && $adcode_for ==1 && $linear ==0 && $nonlinearbanner ==0 && $nonlineartext ==0)
				$this->set_notice("you have no privilege for creating CPV adcodes");

				else if($sponsored_enabled ==1 && $adpricing ==3 && $name =='')
				$this->set_notice("mandatory");
				else if($sponsored_enabled ==1 && $adpricing ==3 && $existing !='')
				$this->set_notice("same adcode already exists");
				else if($category_enabled ==1 && $sid >0 && !CategoryHelper::get_site_exists($sid,0))
				$this->set_notice("site invalid");				
				else
				{
					if($name =="")
					$name="AdCode-".$id;

					if(mb_strlen($name) >25)
					$name=substr($name,0,25);

					$query_string='';
					$param_array=array();


						if($adcodeType == 2 || $adcodeType == 13 || $adcodeType == 14)
						$borderType = 1;

					$query_string.=' name = ?,adcode_theme = ?,abr_type = ?,display_type = ?,heading_color = ?,title_color = ?,description_color = ?,url_color = ?,credit_text_color = ?,cta_text_color = ?,cta_button_color = ?,border_color = ?,title_hover_color = ?,description_hover_color = ?,url_hover_color = ?,heading_hover_color = ?,cta_hover_color = ?,adcode_background = ?,title_background = ?,description_background = ?,url_background = ?,heading_background = ?,cta_background = ?,image_background = ?';
					$param_array=array_merge($param_array,array($name,$adcodeTheme,$borderType,$adpricing,$heading_color,$title_color,$description_color,$url_color,$credit_text_color,$cta_text_color,$cta_button_color,$border_color,$title_hover_color,$description_hover_color,$url_hover_color,$heading_hover_color,$cta_hover_color,$adcode_background,$title_background,$description_background,$url_background,$heading_background,$cta_background,$image_background));

					if($pop_enabled ==1 && $adcodeType ==9)
					{
						$query_string.=',pop_up_support =?, pop_tab_support =?';
						$param_array=array_merge($param_array,array($popup_support, $poptab_support));
					}

					if($category_enabled == 1)
					{
						$query_string.=' ,sid =?';
						$param_array=array_merge($param_array,array($sid));
					}


					if($video_enabled == 1 && $adcodeType == 13)
					{
						$query_string.=',`player_size` =?,`linear` =?,`non_linear_text` =?,`non_linear_banner` =?,`video_type` =?';
						$param_array=array_merge($param_array,array($player_data,$linear,$nonlineartext,$nonlinearbanner,$adcode_for));
					}

					if($skin_enabled ==1 && $adcodeType == 14)
					{
						$container_id=$this->read_post_param('container_id');

						$this->set_variable("container_id",$container_id);

						$query_string.=',container_id =?';
						$param_array=array_merge($param_array,array($container_id));
					}


					$param_array=array_merge($param_array,array($id));
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set ".$query_string." where id=?",$param_array);

					if($res->error == '')
					{
						if($sponsored_enabled == 1 && $adpricing == 3)
						{
							$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET maximum_ads = ?,cpd_rate = ? WHERE id = ?",array($maximum_ads,$cpd_rate,$id));
						}

						if($sticky_enabled == 1 && $adcodeFormat != 5 && $adcodeType != 9 && $adcodeType != 14 && $adcodeType != 21)
						$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET sticky_support=?,sticky_position=?,sticky_close_position=? WHERE id=?",array($sticky_support,$sticky_position,$sticky_close_position,$id));

						if($responsive_enabled == 1 && $nativeAdcode == 0 && $adpricing != 3 && $adcodeFormat != 9 && $adcodeFormat != 21 && ($adcodeFormat != 13 || ($adcodeFormat == 13 && $adcode_for == 2)) && $adcodeFormat !=14)
						$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `responsive_support`=? WHERE id=?",array($responsive_support,$id));

						$this->flash($this->get_message('adunit edit success'), $this->make_url('adunit/edit/'.$id));
						exit;
					}
					else
					{
						$this->set_notice("error occurred");
					}
			}
			$this->set_variable("aduname",$name);


			$this->set_variable("heading_color",$heading_color);
			$this->set_variable("title_color",$title_color);
			$this->set_variable("description_color",$description_color);
			$this->set_variable("url_color",$url_color);
			$this->set_variable("credit_text_color",$credit_text_color);
			$this->set_variable("cta_text_color",$cta_text_color);
			$this->set_variable("cta_button_color",$cta_button_color);
			$this->set_variable("border_color",$border_color);
			$this->set_variable("title_hover_color",$title_hover_color);
			$this->set_variable("description_hover_color",$description_hover_color);
			$this->set_variable("url_hover_color",$url_hover_color);
			$this->set_variable("heading_hover_color",$heading_hover_color);
			$this->set_variable("cta_hover_color",$cta_hover_color);
			$this->set_variable("adcode_background",$adcode_background);
			$this->set_variable("title_background",$title_background);
			$this->set_variable("description_background",$description_background);
			$this->set_variable("url_background",$url_background);
			$this->set_variable("heading_background",$heading_background);
			$this->set_variable("cta_background",$cta_background);
			$this->set_variable("image_background",$image_background);
		}

		$this->set_variable("aduid",$id);
		$this->set_variable("sid",$sid);


		$this->set_variable("layoutID",$layoutID);


		if($_POST)
		$this->set_variable("layoutTheme",$adcodeTheme);
		else
		$this->set_variable("layoutTheme",$layoutTheme);

		$this->set_variable('sticky_support',$sticky_support);
		$this->set_variable('sticky_position',$sticky_position);
		$this->set_variable('sticky_close_position',$sticky_close_position);


		$this->set_variable('pop_enabled',$pop_enabled);
		$this->set_variable('pricing',$pricing);

		$this->set_variable('adcode_for',$adcode_for);
		$this->set_variable('linear',$linear);
		$this->set_variable('nonlinearbanner',$nonlinearbanner);
		$this->set_variable('nonlineartext',$nonlineartext);
		$this->set_variable('nonlinear_size',$nonlinear_size);

		$this->set_variable('responsive_support',$responsive_support);
		$this->set_variable('responsive_enabled',$responsive_enabled);
		$this->set_variable("native_enabled",$native_enabled);

		$resLayout  = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_layout WHERE layout_type = 2");
		$this->set_result("resLayout",$resLayout);

		$resDimension = $db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type = 3");
		$this->set_result("resDimension",$resDimension);
	}


	function delete_action()
	{

		$id=$this->read_page_param(1);
		$adpricing=$this->read_page_param(2);
		$sid=intval($this->read_page_param(3));

		$db= DAL::get_instance();


		if(DEMO_MODE && $id <= 50)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('adunit/manage/'.$adpricing),0);
			exit;
		}


		if($this->get_adunit_available_check($id))
		{
			$sponsored_enabled=$this->get_addon_status('sponsored_enabled');

			$mapcount=0;
			if($sponsored_enabled ==1 || $sponsored_enabled ==0)
			$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE position=? AND (status=2 OR status=3)",array($id));


			if($mapcount ==0)
			{
				$res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."adunit where id=? and pubid=0",array($id));


				if($sponsored_enabled ==1 || $sponsored_enabled ==0)
				{
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE position=? AND (status=-1 OR status=0 OR status=1)",array($id));

				}

				$this->flash($this->get_message('adunit delete'), $this->make_url('adunit/manage/'.$adpricing.'/'.$sid));
			}
			else
			{
				$this->flash($this->get_message('sponsored mappings exists'), $this->make_url('adunit/manage/'.$adpricing.'/'.$sid),0);
			}
		}
		else
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('adunit/manage/'.$adpricing.'/'.$sid),0);
		}

	}
};
?>