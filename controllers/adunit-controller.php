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
		if($this->get_action()=="create" || $this->get_action()=="view" || $this->get_action()=="edit" || $this->get_action()=="delete" || $this->get_action()=="preview" || $this->get_action()=="statistics" || $this->get_action()=="detail_statistics" || $this->get_action()=="edit_native" || $this->get_action()=="preview_native")
		{
			if(!(LoginHelper::validate_user_login()))
			{
				$this->flash($this->get_message('login failed'), BASE,0);
			}
			
			$uid=$this->read_cookie_param(COOKIE_LOGINID);
			$db= DAL::get_instance();
			
			$status=$db->read_single_column("select pub_status from ".TABLE_PREFIX."users where id=?",array($uid));
			
			if($status !=1)
			$this->flash($this->get_message('your publisher account in inactive'), $this->make_url('user/advertiser_home'),0);			
		}
	}
	
	function create_action()
	{
		$this->set_title($this->get_label('create new adunit'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		
		$category_enabled=$this->get_addon_status('category-targeting_enabled');
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		$native_enabled=$this->get_addon_status('native-ad-display_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		
		
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
		

		
		if($cpc_enabled !=1 && $cpm_enabled !=1 && $cpa_enabled !=1 && $sponsored_enabled !=1 && $pop_enabled !=1 && $video_enabled !=1)
		{
			$this->flash($this->get_message('no active adcode type exists'), $this->make_url('adunit/view'),0);
			exit;
		}
		
		
		$sid=0;
		$stringarray=array();
		if($category_enabled ==1)
		{
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

			if($category_enabled_ads !='')
			$stringarray=explode('_',$category_enabled_ads);
		}
		
		
		
		$db= DAL::get_instance();
		
		
		
		$res1=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 and allowpublisher=1 AND banner_type=0 AND type=1");
		$this->set_result("res1",$res1);
			
		$res2=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 and allowpublisher=1 AND banner_type=0 AND type=2");
		$this->set_result("res2",$res2);
						
		$res3=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 and allowpublisher=1 AND banner_type=0 AND type=3");
		$this->set_result("res3",$res3);
			
		$res7=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 and allowpublisher=1 AND banner_type=1 AND type=2");
		$this->set_result("res7",$res7);
						
        $res9=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 AND banner_type=3 AND type=4");
		$this->set_result("res9",$res9);
		
        $res13=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 and allowpublisher=1 AND banner_type=5 AND type=5");
		$this->set_result("res13",$res13);	

        $res14=$db->execute_query("select id,height,width from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type=0 AND vast_video_support =1");
		$this->set_result("res14",$res14);	

		$res15=$db->execute_query("select * from ".TABLE_PREFIX."adblock where status=1 and allowpublisher=1 AND banner_type=4 AND type=2");
		$this->set_result("res15",$res15);
		
		
		
		
		
		if($video_enabled ==1)
		{
			$user_data=$db->execute_query("SELECT vast_adcode_enabled FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			$user_data_row=$user_data->fetch_assoc();
			
			if($user_data->get_num_records() >0)
			{
				$vast_adcode_enabled=$user_data_row['vast_adcode_enabled'];
				
				$this->set_variable('vast_adcode_enabled',$vast_adcode_enabled);
			}	
		}		
			
		
		

		$adcode_for=0;
		$player_size=0;
		$linear=0;
		$nonlinearbanner=0;
		$nonlineartext=0;
		$nonlinear_size=0;


		
		
		$adpricing=0;
		
		$popup_support=0;
		$popunder_support=0;
		$poptab_support=0;
		$layout=0;
		$native=0;
		$responsive=0;
		$customcode='';
		$img_dim='';
		$name='';
		$skin_array =array();
		
		if($_POST)
		{
			
			$native=$this->read_post_param('native');
			
			if($native_enabled ==1 && $native==1)
			{
				$adpricing=$this->read_post_param('adpricing1');
				$name=$this->read_post_param('name1');
				
				$layout=$this->read_post_param('layout');
				
				
			 	$responsive=$this->read_post_param('responsive');
			 	$customcode=$this->read_post_param('customcode');
			 	$img_dim=$this->read_post_param('img_dim');
			 	
			 	if($category_enabled ==1)
			 	{
			 		if($adpricing == 3)
		 			$sid=intval($this->read_post_param('sid_select_11'));
		 			else
		 			$sid=intval($this->read_post_param('sid_select_10'));
			 	}
			 	
				$adblocktype=$this->read_post_param('adblocktype1');
			}
			else 
			{
				$adpricing=$this->read_post_param('adpricing');
				$name=$this->read_post_param('name');
					
				
				if($category_enabled ==1)
			 	{
			 		if($adpricing == 3)
		 			$sid=intval($this->read_post_param('sid_select_01'));
		 			else
		 			$sid=intval($this->read_post_param('sid_select_00'));
			 	}				
				
				
				if($pop_enabled ==1 && $adpricing ==9)
				{
					$popup_support=intval($this->read_post_param('popup_support'));
					$popunder_support=intval($this->read_post_param('popunder_support'));
					$poptab_support=intval($this->read_post_param('poptab_support'));
				}
				
			
				if($adpricing !=9 && $adpricing !=13)
				$adblocktype=$this->read_post_param('adblocktype');
				else
				$adblocktype=0;
			
						
			$blockid=0;
			if($adpricing !=9 && $adpricing !=13)
			{
				if($adblocktype ==1)
				$blockid=intval($this->read_post_param('blockid_1'));
				else if($adblocktype ==2)
				$blockid=intval($this->read_post_param('blockid_2'));
				else if($adblocktype ==3)
				$blockid=intval($this->read_post_param('blockid_3'));
				else if($adblocktype ==5)
				$blockid=intval($this->read_post_param('blockid_7'));
				else if($adblocktype ==11)
				$blockid=intval($this->read_post_param('blockid_9'));
				else if($adblocktype ==14)
				$blockid=intval($this->read_post_param('blockid_14'));
			}

			if($adblocktype ==14)
			$container_id=$this->read_post_param('container_id');
	
			
			if($adpricing ==13)
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
				$existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND sid=? AND name=? AND blockid=?",array($uid,$sid,$name,$blockid));
				
			}
				
				
			if($native==1 && $name =='')
			$this->set_notice("mandatory");
			else if($adpricing !=13 && $category_enabled ==1 && $sid ==0 && in_array($adpricing,$stringarray))
			$this->set_notice("please select a targeting site");
			else if($adpricing ==13 && $category_enabled ==1 && $sid ==0 && (in_array($adpricing,$stringarray) || $adcode_for ==1))
			$this->set_notice("please select a targeting site");
			
			
			
			else if($adpricing ==13 && $adcode_for ==2 && $blockid ==0)
			$this->set_notice("please select html5 player dimension");			
			else if($native==0 && $adpricing !=9 && $adpricing !=13 && $blockid ==0)
			$this->set_notice("please select a adblock");
			
			
			else if($adpricing ==13 && $adcode_for ==0)
			$this->set_notice("please select a player type");
			else if($adpricing ==13 && $adcode_for ==1 && $nonlinearbanner ==1 && $nonlinear_size ==0)
			$this->set_notice("please select nonlinear banner dimension");	
			else if($adpricing ==13 && $adcode_for ==1 && $linear ==0 && $nonlinearbanner ==0 && $nonlineartext ==0)
			$this->set_notice("you have no privilege for creating CPV adcodes");
			else if($native==1 && $layout==0)
			$this->set_notice("please select a layout");

			
			
			else if($pop_enabled ==1 && $adpricing ==9 && $popup_support ==0 && $popunder_support ==0 && $poptab_support ==0)
			$this->set_notice("please choose a supported pop type");
			else if($sponsored_enabled ==1 && $adpricing ==3 && $name =='' && $layout==0)
			$this->set_notice("mandatory");
			else if($sponsored_enabled ==1 && $adpricing ==3 && $existing !='')
			$this->set_notice("same adcode already exists");
			else if($category_enabled ==1 && $sid >0 && !CategoryHelper::get_site_exists($sid,$uid))
			$this->set_notice("site invalid");
			else 
			{
				
				
				if(mb_strlen($name) >25)
				$name=substr($name,0,25);
	
		
				if($native ==1)
				{
					$tcolor=Configuration::get_instance()->read('native_tcolor');
					$dcolor=Configuration::get_instance()->read('native_dcolor');
					$ucolor=Configuration::get_instance()->read('native_ucolor');
					$ccolor=Configuration::get_instance()->read('native_ccolor');
					
					$bcolor=Configuration::get_instance()->read('native_bcolor');
					$br_color=Configuration::get_instance()->read('native_br_color');
					$bordertype=Configuration::get_instance()->read('native_bordertype');
					$img_postion =Configuration::get_instance()->read ("native_image_position");
					
					
					$hcolor=Configuration::get_instance()->read('nativead_header_color');
					$h_bgcolor=Configuration::get_instance()->read('nativead_header_bgcolor');
					$credittext=Configuration::get_instance()->read('native_credit_text');
								
					$query=$db->execute_query("insert into ".TABLE_PREFIX."adunit (`id`,`pubid`,`blockid`,`name`,`at_color`,`ad_color`,`au_color`,`ab_color`,`ac_color`,`abr_color`,`abr_type`,`status`,`credittext`,`display_type`,`nativeimg_position`,`htxt_color`,`htxt_bgcolor`) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array('',$uid,$blockid,$name,$tcolor,$dcolor,$ucolor,$bcolor,$ccolor,$br_color,$bordertype,1,$credit_text,$adpricing,$img_postion,$hcolor,$h_bgcolor));
				}
				else	
				{
					if($adpricing !=9)
					{
						$res=$db->execute_query("select * from ".TABLE_PREFIX."adblock where id=?",array($blockid));	
						$result=$res->fetch_assoc();
			
						$query=$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (`id`,`pubid`,`blockid`,`name`,`at_color`,`ad_color`,`au_color`,`ab_color`,`ac_color`,`abr_color`,`abr_type`,`status`,`credittext`,`display_type`) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array('',$uid,$blockid,$name,$result['tcolor'],$result['dcolor'],$result['ucolor'],$result['bcolor'],$result['ccolor'],$result['br_color'],$result['bordertype'],1,$result['credit_text'],$adpricing));
					}
					else
					$query=$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (`id`,`pubid`,`name`,`status`,`display_type`) values(?,?,?,?,?)",array('',$uid,$name,1,$adpricing));
					
					
				}
				if($query->error =="")
				{
					$aduid=$query->get_last_id();
					
					if($name =='')
					{
						$name='AdCode-'.$aduid;
						$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET name=? WHERE id=?",array($name,$aduid));
					}
					
					if($pop_enabled ==1 && $adpricing ==9)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET pop_up_support=?,pop_under_support=?,pop_tab_support=? WHERE id=?",array($popup_support,$popunder_support,$poptab_support,$aduid));
					
					
					
					if($native_enabled ==1 && $native==1)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `layout`=?,`native`=?,`responsive`=?,`custom_code`=?,`nativeimg_dimension`=? WHERE id=?",array($layout,$native,$responsive,$customcode,$img_dim,$aduid));
					
					if($category_enabled ==1)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET sid=? WHERE id=?",array($sid,$aduid));

					
					if($video_enabled ==1 && $adpricing ==13)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `player_size`=?,`linear`=?,`non_linear_text`=?,`non_linear_banner`=?,`video_type`=? WHERE id=?",array($player_data,$linear,$nonlineartext,$nonlinearbanner,$adcode_for,$aduid));
					
					if($adblocktype ==14)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `container_id`=? WHERE id=?",array($container_id,$aduid));
					
					if($native==1)
					$this->flash($this->get_message('adunit created'), $this->make_url('adunit/edit_native/'.$aduid));
					else	
					$this->flash($this->get_message('adunit created'), $this->make_url('adunit/edit/'.$aduid));
				}
				else
				{
						$this->set_notice("error occurred");
				}
			}
				
			$this->set_variable("name",$name);
			
			if($native_enabled==1 && $native==1)
			{
				$this->set_variable("layout",$layout);
				
				$this->set_variable("native",$native);
			}
			else
			{
				$this->set_variable("blockid",$blockid);
				$this->set_variable("adblocktype",$adblocktype);
			}
			
	}
		
	$this->set_variable('responsive',$responsive);
	
	$this->set_variable('layout', $layout);
	$this->set_variable('type', $native);
	$this->set_variable('customcode', $customcode);
	$this->set_variable('img_dim', $img_dim);
	$this->set_variable("name",$name);
	
	$this->set_variable('popup_support',$popup_support);
	$this->set_variable('popunder_support',$popunder_support);
	$this->set_variable('poptab_support',$poptab_support);
	$this->set_variable('pop_enabled',$pop_enabled);
	
	
	$this->set_variable("uid",$uid);
	$this->set_variable("sid",$sid);
	$this->set_variable("adpricing",$adpricing);
	
	$this->set_variable('adcode_for',$adcode_for);
	$this->set_variable('linear',$linear);
	$this->set_variable('nonlinearbanner',$nonlinearbanner);
	$this->set_variable('nonlineartext',$nonlineartext);
	$this->set_variable('nonlinear_size',$nonlinear_size);	
	
	if($native_enabled ==1)
	{
		$layouttxt=$db->execute_query("select * from ".TABLE_PREFIX."nativeads_layout where type=1 and status=1");
		$layoutimg=$db->execute_query("select * from ".TABLE_PREFIX."nativeads_layout where type=2 and status=1"); 
		$this->set_result('layouttxt', $layouttxt);
		$this->set_result('layoutimg', $layoutimg);
		
	
		$resdim=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =3");
		$this->set_result("res_dim",$resdim);  
	}
}

    function view_action()
	{
		$this->set_title($this->get_label('manage adunits'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		if($_POST)
		{
			$adpricing=$this->read_post_param("adpricing");
			$sid=intval($this->read_post_param("sid"));
		}
		else
		{
			$adpricing=$this->read_page_param(1);
			$sid=intval($this->read_page_param(2));
			
			$exp=explode("-",$adpricing);
			if($exp[0]=="page")
			$adpricing='';			
		}
		
		
		
		
		
		
		if($adpricing =='')
		$adpricing=-1;
		
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("uid",$uid);
		$this->set_variable("sid",$sid);
		
		
		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" and a.display_type='".$adpricing."' ";
		
		
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$category_enabled=$this->get_addon_status('category-targeting_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');		
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		
		
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
		
		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;		
				
		
		if($cpc_enabled !=1)
		$adpricing_str.=' AND a.display_type <>0 ';				
		
		if($cpm_enabled !=1)
		$adpricing_str.=' AND a.display_type <>1 ';
		
		if($sponsored_enabled !=1)
		$adpricing_str.=' AND a.display_type <>3 ';
		
		if($cpc_enabled !=1 && $cpm_enabled !=1 && $cpa_enabled !=1)
		$adpricing_str.=' AND a.display_type <>4 ';		
		
		if($cpa_enabled !=1)
		$adpricing_str.=' AND a.display_type <>6 ';
		
		if($pop_enabled !=1)
		$adpricing_str.=' AND a.display_type <>9 ';
		
		if($affiliate_enabled !=1)
		$adpricing_str.=' AND a.display_type <>12 ';
		
		if($video_enabled !=1)
		$adpricing_str.=' AND a.display_type <>13 ';		
		
		
		$adtype_str="";
		
		
		if($this->get_addon_status('interstitial_enabled') !=1)
		$adtype_str.=' ab.banner_type <>1 ';		
		
		if($this->get_addon_status('text-image-ads_enabled') !=1)
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
		
		
		if($video_enabled !=1)
		{
			if($adtype_str !="")
			$adtype_str.=' AND ';
			
			$adtype_str.=' ab.type <>5 ';	
		}		
		
		if($text_ads_enabled !=1)
		{		
			if($adtype_str !="")
			$adtype_str.=' AND ';			
			
			$adtype_str.=' ab.type <>1 ';		
		}
				

		if($adtype_str !="")
		$adtype_str=' AND (('.$adtype_str.') OR ab.banner_type IS NULL) ';
		

		
			
		$db= DAL::get_instance();
		
		$sidstring='';
		$sidquery='';
		if($category_enabled ==1)
		{
			if($sid >0)
			$sidquery=' AND a.sid='.$sid.' ';
			
			$sidstring=',a.sid ';
		}
		
		
		$query="select a.*,ab.name as abname,ab.type,ab.banner_type,ab.width,ab.height from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE a.pubid=? ".$adpricing_str." ".$sidquery." ".$adtype_str." ORDER BY a.id desc";
		
		$pagination = new Pagination($query,array($uid));
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	}
		
	function edit_action()
	{
		$db= DAL::get_instance();
		$mem_obj=$this->memcache_connect();
	
		$this->set_title($this->get_label('edit adunit'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		
		if($_POST)
		$aduid=$this->read_post_param('aduid');
		else
		$aduid=$this->read_page_param(1);
		
		if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
		{     
			$mem_adunit_array=$mem_obj->get("adunit_res_".$aduid);
		}
	
		$this->set_variable("uid",$uid);
		$this->set_variable("aduid",$aduid);
		
		$category_enabled=$this->get_addon_status('category-targeting_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');		
		
		
		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;
		
		
		
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
		
		
		
		$sid=0;
		$stringarray=array();
		if($category_enabled ==1)
		{
			if($_POST)
			$sid=intval($this->read_post_param('sid'));
				
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

			if($category_enabled_ads !='')
			$stringarray=explode('_',$category_enabled_ads);
		
			//if(CategoryHelper::get_site_count_user($uid) ==0)
			//{
			//	$this->flash($this->get_message('no active sites'), $this->make_url('user/publisher_home'),0);
			//}
		}
		
		
		if(!$this->get_adunit_available_check($aduid,$uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('adunit/view'),0);
			exit;
		}
		
		
		
		
		
		$adp=$this->get_adunit_preference_value($aduid);
		
		
		if($adp ==0 && $cpc_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);		
		
		if($adp ==1 && $cpm_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==3 && $sponsored_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==4 && $cpc_enabled !=1 && $cpm_enabled !=1 && $cpa_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);		
		
		if($adp ==6 && $cpa_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==9 && $pop_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
	
		if($adp ==13 && $video_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		
		
		$blockid=$this->get_adblock_id($aduid);
		
		
		if($adp ==9 || ($adp ==13 && $blockid ==0))
		$query="select au.*,au.id as auid,au.name as auname from ".TABLE_PREFIX."adunit au where au.id=?";
		else
		$query="select ab.*,au.*,au.id as auid,au.name as auname,credittext,at_color,ad_color,au_color,ab_color,ac_color,abr_color,abr_type from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au  where ab.id=au.blockid and au.id=?";
		
		
		
		$res=$db->execute_query($query,array($aduid));
		$this->set_result("res",$res);
		
		
		if(Configuration::get_instance()->read('allow_tcolor')==0 && Configuration::get_instance()->read('allow_dcolor')==0 && 
		   Configuration::get_instance()->read('allow_ucolor')==0 && Configuration::get_instance()->read('allow_bcolor')==0 && 
		   Configuration::get_instance()->read('allow_ccolor')==0 && Configuration::get_instance()->read('allow_brcolor')==0)
		{
			$flag=0;
			$this->set_variable('flag',$flag);
		}
		else 
		{
			$flag=1;
			$this->set_variable('flag',$flag);
		}
		
		if($_POST)
		{
			if(DEMO_MODE && $aduid <=75)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('adunit/view'),0);
				exit;
			}
						
			
			$aduname=$this->read_post_param('aduname');
			$border=$this->read_post_param('border');
			$adpricing=$this->read_post_param('adpricing');
			
			
			if($pop_enabled ==1 && $adpricing ==9)
			{
				$popup_support=intval($this->read_post_param('popup_support'));
				$popunder_support=intval($this->read_post_param('popunder_support'));
				$poptab_support=intval($this->read_post_param('poptab_support'));
				
				
				$this->set_variable('popup_support',$popup_support);
				$this->set_variable('popunder_support',$popunder_support);
				$this->set_variable('poptab_support',$poptab_support);
	
				
			}
			
			
			
			
			if($adpricing !=9)
			{
				if($adpricing !=13)
				{
					$color1=$this->read_post_param('color1');
					$color2=$this->read_post_param('color2');
					$color3=$this->read_post_param('color3');
				}
				
				if($adpricing !=13 || ($adpricing ==13 && $blockid >0))
				{
					$color4=$this->read_post_param('color4');
					$color5=$this->read_post_param('color5');
					$color6=$this->read_post_param('color6');
				}
			}
			
			
			
			if($adpricing ==13)
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
			
			
			$existing='';
			if($sponsored_enabled ==1 && $adpricing ==3)
			$existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND sid=? AND name=? AND id<>?",array($uid,$sid,$aduname,$aduid));
			
	
			if($adpricing !=13 && $category_enabled ==1 && $sid ==0 && in_array($adpricing,$stringarray))
			$this->set_notice("please select a targeting site");
			else if($adpricing ==13 && $category_enabled ==1 && $sid ==0 && (in_array($adpricing,$stringarray) || $adcode_for ==1))
			$this->set_notice("please select a targeting site");			
			
			
			
			else if($adpricing ==13 && $adcode_for ==0)
			$this->set_notice("please select a player type");
			else if($adpricing ==13 && $adcode_for ==1 && $nonlinearbanner ==1 && $nonlinear_size ==0)
			$this->set_notice("please select nonlinear banner dimension");		
			else if($adpricing ==13 && $adcode_for ==1 && $linear ==0 && $nonlinearbanner ==0 && $nonlineartext ==0)
			$this->set_notice("you have no privilege for creating CPV adcodes");
			

			else if($sponsored_enabled ==1 && $adpricing ==3 && $aduname =='')
			$this->set_notice("mandatory");
			else if($sponsored_enabled ==1 && $adpricing ==3 && $existing !='')
			$this->set_notice("same adcode already exists");
			else if($category_enabled ==1 && $sid >0 && !CategoryHelper::get_site_exists($sid,$uid))
			$this->set_notice("site invalid");
			else if($pop_enabled ==1 && $adpricing ==9 && $popup_support ==0 && $popunder_support ==0 && $poptab_support ==0)
			$this->set_notice("please choose a supported pop type");
			else
			{
				if($aduname =="")
				$aduname="AdCode-".$aduid;
				
				if(mb_strlen($aduname) >25)
				$aduname=substr($aduname,0,25);
				
				$query_string='';
				$param_array=array();
				
				$query_string.=' name =?,abr_type =?,display_type =?';
				$param_array=array_merge($param_array,array($aduname,$border,$adpricing));
				if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
				{
					$mem_adunit_array['name']=$aduname;
					$mem_adunit_array['abr_type']=$border;
					$mem_adunit_array['display_type']=$adpricing;
				}
				if($adpricing !=9 && $adpricing !=13)
				{
				
					if($adpricing !=13)
					{
						if($color1 !="")
						{
							$query_string.=',at_color =?';
							$param_array=array_merge($param_array,array($color1));
							if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array['at_color']=$color1;
							}
						}
						if($color2 !="")
						{
							$query_string.=',ad_color =?';
							$param_array=array_merge($param_array,array($color2));
							if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array['ad_color']=$color2;
							}
						}
						if($color3 !="")
						{
							$query_string.=',au_color =?';
							$param_array=array_merge($param_array,array($color3));
							if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array['au_color']=$color3;
							}
						}
					}
					
					
					if($adpricing !=13 || ($adpricing ==13 && $blockid >0))
					{
						if($color4 !="")
						{   
							$query_string.=',ab_color =?';
							$param_array=array_merge($param_array,array($color4));
							if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array['ab_color']=$color4;
							}
						}
					
						if($color5 !="")
						{
							$query_string.=',ac_color =?';
							$param_array=array_merge($param_array,array($color5));
							if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array['ac_color']=$color5;
							}
						}
					
						if($color6 !="")
						{
							$query_string.=',abr_color =?';
							$param_array=array_merge($param_array,array($color6));
							if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array['abr_color']=$color6;
							}
						}
					}
				}
				
				
				
				if($pop_enabled ==1 && $adpricing ==9)
				{
					$query_string.=',pop_up_support =?,pop_under_support =?,pop_tab_support =?';
					$param_array=array_merge($param_array,array($popup_support,$popunder_support,$poptab_support));
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
					{
						$mem_adunit_array['pop_up_support']=$popup_support;
						$mem_adunit_array['pop_under_support']=$popunder_support;
						$mem_adunit_array['pop_tab_support']=$poptab_support;
					}
				}
					
				if($category_enabled == 1)
				{
					$query_string.=' ,sid =?';
					$param_array=array_merge($param_array,array($sid));
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
					{
						$mem_adunit_array['sid']=$sid;
					}
				}
					
				if($video_enabled == 1 && $adpricing == 13)
				{
					$query_string.=',`player_size` =?,`linear` =?,`non_linear_text` =?,`non_linear_banner` =?,`video_type` =?';
					$param_array=array_merge($param_array,array($player_data,$linear,$nonlineartext,$nonlinearbanner,$adcode_for));
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
					{
						$mem_adunit_array['player_size']=$player_data;
						$mem_adunit_array['linear']=$linear;
						$mem_adunit_array['non_linear_text']=$nonlineartext;
						$mem_adunit_array['non_linear_banner']=$nonlinearbanner;
						$mem_adunit_array['video_type']=$adcode_for;
					}
				}
				
				if($skin_enabled ==1)
				{
					$container_id=$this->read_post_param('container_id');
				
					$this->set_variable("container_id",$container_id);
					
					$query_string.=',container_id =?';
					$param_array=array_merge($param_array,array($container_id));
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
					{
						$mem_adunit_array['container_id']=$container_id;
					}
				}
				
				$param_array=array_merge($param_array,array($aduid));
				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set ".$query_string." where id=?",$param_array);
				if($res->error == '')
				{ 
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
						$mem_obj->set("adunit_res_".$aduid,$mem_adunit_array,MEMCACHE_EXPIRY);
					
					$this->flash($this->get_message('adunit edit success'), $this->make_url('adunit/edit/'.$aduid));
					exit;
				}
				else 
				{ 
					$this->set_notice("error occurred");
				}
				
			
		    }
			
			$this->set_variable("aduname",$aduname);
			$this->set_variable("abrt",$border);
			$this->set_variable("sid",$sid);
		}
		
		
		$this->set_variable('pop_enabled',$pop_enabled);
		$this->set_variable('pricing',$adp);
		
		
		$user_data=$db->execute_query("SELECT get_pop_link FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
		$user_data_row=$user_data->fetch_assoc();
		
		if($user_data->get_num_records() >0)
		{
			$get_direct_link=$user_data_row['get_pop_link'];
			
			$this->set_variable('get_direct_link',$get_direct_link);
		}
		
				
		$this->set_variable('adcode_for',$adcode_for);
		$this->set_variable('linear',$linear);
		$this->set_variable('nonlinearbanner',$nonlinearbanner);
		$this->set_variable('nonlineartext',$nonlineartext);
		$this->set_variable('nonlinear_size',$nonlinear_size);			
		
	}
	
	function delete_action()
	{
		$id=$this->read_page_param(1);
		$adpricing=$this->read_page_param(2);
		$sid=$this->read_page_param(3);
		
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		if($this->get_adunit_available_check($id,$uid))
		{
			if(DEMO_MODE && $id <=5)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('adunit/view'),0);
				exit;
			}
		
			$db= DAL::get_instance();
			
			
			
			
			$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
			
			$mapcount=0;
			if($sponsored_enabled ==1 || $sponsored_enabled ==0)
			$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE position=? AND (status=2 OR status=3)",array($id));
			
			
			if($mapcount ==0)
			{
				$res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."adunit where id=? and pubid=?",array($id,$uid));
				
				if($sponsored_enabled ==1 || $sponsored_enabled ==0)
				{
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE position=? AND (status=-1 OR status=0 OR status=1)",array($id));
				
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."packages WHERE posid=?",array($id));
				}
				
				$this->flash($this->get_message('adunit deleted'), $this->make_url('adunit/view/'.$adpricing.'/'.$sid));
			}
			else
			{
				$this->flash($this->get_message('sponsored mappings exists'), $this->make_url('adunit/view/'.$adpricing.'/'.$sid),0);
			}
			

			
		}
		else 
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('adunit/view'),0);
			exit;
		}
	}
	
	function preview_action()
	{
		$this->disable_notice_area();
		$id=$this->read_page_param(1);
		$prtype=$this->read_page_param(2);
		$this->set_variable("aduid",$id);
		$this->set_variable("prtype",$prtype);
	
		$db= DAL::get_instance();

		$res=$db->execute_query("select ab.*,au.*,au.id as auid,au.name as auname,credittext,at_color,ad_color,au_color,ab_color,ac_color,abr_color,abr_type from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au  where ab.id=au.blockid and au.id=?",array($id));
		$this->set_result("res",$res);
	
		$resdata=$res->fetch_assoc();
				
		$this->set_variable("ctext",$resdata['credittext']);
		
		$direction=0;
		$this->set_variable("direction",intval($direction));
		
		
		$credit_text_array=$this->get_credittext($resdata['credittext'],1,1,1); //Last parameter 1 is for data return from DB
			
		$credit_text=$credit_text_array[0];
		$credit_type=$credit_text_array[1];
		$credit_icon=$credit_text_array[2];
		$credit_icon_type=$credit_text_array[3];

		
		$this->set_variable("credits_texts",$credit_text,0);
		$this->set_variable("credit_icon",$credit_icon,0);
		$this->set_variable("credittype",$credittype);
		$this->set_variable("credit_icon_type",$credit_icon_type);			
		
		
	}

	
	function statistics_action()
	{
	
		$this->set_title($this->get_label('adunit statistics'));
	
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
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
		
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		
		if($adpricing =='')
		$adpricing=-1;
				
		$this->set_variable("adpricing",$adpricing);
		
		$sidstring='';
		
		
		if($this->get_addon_status('category-targeting_enabled') ==1)
		{
			if($sid >0)
			$sidstring=' AND sid='.$sid.' ';
		}
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
		
		
		
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
		$adpricing_str.=' AND display_type <>9 ';
		
		if($affiliate_enabled !=1)
		$adpricing_str.=' AND display_type <>12 ';
		
		if($cpv_enabled !=1)
		$adpricing_str.=' AND display_type <>13 ';		
		
		
		
		
		$adtype_str="";
		
		
		if($this->get_addon_status('interstitial_enabled') !=1)
		$adtype_str.=' ab.banner_type <>1 ';		
		
		if($this->get_addon_status('text-image-ads_enabled') !=1)
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
		
		
		
		if($text_ads_enabled !=1)
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
		
		
		$query="select a.*,ab.name as abname,ab.type,ab.banner_type from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE a.pubid=? ".$adpricing_str." ".$sidstring." ".$adtype_str." ORDER BY a.id desc";
		
		$pagination = new Pagination($query,array($uid));
		
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		
		if($duration=="" || $duration==0)
		$duration=1;
	
		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
		$this->set_variable("sid",$sid);
	}
	
	function detail_statistics_action()
	{
		$this->set_title($this->get_label('adunit statistics'));
	
		$aduid=$this->read_page_param(1);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
	
	
	
		$db= DAL::get_instance();
	
	
	
		if(!$this->get_adunit_available_check($aduid,$uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('adunit/statistics'),0);
			exit;
		}
	
		
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
				
		
		
		
		$adp=$this->get_adunit_preference_value($aduid);
		
		
		if($adp ==0 && $cpc_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);	
		
		if($adp ==1 && $cpm_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==3 && $sponsored_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==4 && $cpc_enabled !=1 && $cpm_enabled !=1 && $cpa_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);			
		
		if($adp ==6 && $cpa_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==9 && $pop_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==12 && $affiliate_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);
		
		if($adp ==13 && $cpv_enabled !=1)
		$this->flash($this->get_message('invalid operation'), $this->make_url('adunit/view'),0);		
		
		
		
		
		$name=$db->read_single_column("select name from ".TABLE_PREFIX."adunit where id=?",array($aduid));
	
	
	
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
	
	
	
	}
	
	
	function edit_native_action()
	{
		
		
		$db= DAL::get_instance();
		$this->set_title($this->get_label('edit adunit'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		
		if($_POST)
			$aduid=$this->read_post_param('aduid');
		else
			$aduid=$this->read_page_param(1);
		
		$this->set_variable("uid",$uid);
		$this->set_variable("aduid",$aduid);
		
		$category_enabled=$this->get_addon_status('category-targeting_enabled');
	
		$native_enabled=$this->get_addon_status('native-ad-display_enabled');
		
		$sid=0;
		$stringarray=array();
		if($category_enabled ==1)
		{
			if($_POST)
				$sid=intval($this->read_post_param('sid'));
		
			$category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');
		
			if($category_enabled_ads !='')
				$stringarray=explode('_',$category_enabled_ads);
		
				if(CategoryHelper::get_site_count_user($uid) ==0)
				{
					$this->flash($this->get_message('no active sites'), $this->make_url('user/publisher_home'),0);
				}
		}
				$native=$db->read_single_column("select native from ".TABLE_PREFIX."adunit where id=? and pubid=?",array($aduid,$uid));
				
				
				if($native ==0 || $native_enabled !=1)
				$this->flash($this->get_message('invalid id'), $this->make_url('adunit/view'),0);
					
				if(!$this->get_adunit_available_check($aduid,$uid))
				{
					$this->flash($this->get_message('invalid id'), $this->make_url('adunit/view'),0);
					exit;
				}
		
				$adp=$this->get_adunit_preference_value($aduid);
		
				$res=$db->execute_query("select au.*,au.id as auid,au.name as auname from ".TABLE_PREFIX."adunit au where au.id=?",array($aduid));
				$this->set_result("res",$res);
				
				$resdata=$res->fetch_assoc();
				
				$layoutdata=$resdata['layout'];
				$img_postion=$resdata['nativeimg_position'];
				
				

				$lres=$db->execute_query("select * from ".TABLE_PREFIX."nativeads_layout where id=?",array($layoutdata));
				$lrow=$lres->fetch_assoc();
				$rows=$lrow['rows'];
				$columns=$lrow['columns'];
				$typedata=$lrow['type'];

				if($typedata ==2)
				$typedata=11;
				
				
				$this->set_variable('rows',$rows);
				$this->set_variable('columns',$columns);
				$this->set_variable('img_postion',$img_postion);
				$this->set_variable('typedata',$typedata);
				
				
				
				$datadimension=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($resdata['nativeimg_dimension']));
				$datadimension_row=$datadimension->fetch_assoc();
				
				
				$this->set_variable('nativead_minwidth_left_aligned',$datadimension_row['left_aligned_width']);				
				$this->set_variable('nativead_minwidth_top_aligned',$datadimension_row['top_aligned_width']);				
				$this->set_variable('nativead_minheight_left_aligned',$datadimension_row['left_aligned_height']);				
				$this->set_variable('nativead_minheight_top_aligned',$datadimension_row['top_aligned_height']);

				
				
				$flag=0;
	 			if(Configuration::get_instance()->read('allow_htbgcolor')==1||Configuration::get_instance()->read('allow_htcolor')==1||Configuration::get_instance()->read('allow_brcolor')==1||Configuration::get_instance()->read('allow_bcolor')==1||Configuration::get_instance()->read('allow_ucolor')==1||Configuration::get_instance()->read('allow_ccolor')=="1" ||Configuration::get_instance()->read('allow_tcolor')=="1"||Configuration::get_instance()->read('allow_dcolor')=="1")
				{  
					$flag=1;
				}
				$this->set_variable('flag',$flag);
				
				$responsive=0;
				if($_POST)
				{
					if(DEMO_MODE && $aduid <=5)
					{
						$this->flash($this->get_message('demo mode'), $this->make_url('adunit/view'),0);
						exit;
					}
		
		
					$aduname=$this->read_post_param('aduname');
					$border=$this->read_post_param('border');
					$adpricing=$this->read_post_param('adpricing');
					
					$layout=$this->read_post_param('layout');
					$native=1;
					$responsive=$this->read_post_param('responsive');
					$customcode=$this->read_post_param('customcode');
					$img_dim=$this->read_post_param('img_dim');
					$img_pos=$this->read_post_param('img_pos');
					
					$color1=$this->read_post_param('color1');
					$color2=$this->read_post_param('color2');
					$color3=$this->read_post_param('color3');
					$color4=$this->read_post_param('color4');
					$color5=$this->read_post_param('color5');
					$color6=$this->read_post_param('color6');
					$color7=$this->read_post_param('color7');
					$color8=$this->read_post_param('color8');
		
		/*
					if($category_enabled ==1)
						$existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND sid=? AND name=? AND id<>?",array($uid,$sid,$aduname,$aduid));
					else $existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND name=? AND id<>?",array($uid,$aduname,$aduid));
					
		
		
		*/
		
		
					if($category_enabled ==1 && $sid ==0 && in_array($adpricing,$stringarray))
						$this->set_notice("please select a targeting site");
					else if($sponsored_enabled ==1 && $adpricing ==3 && $aduname =='')
						$this->set_notice("mandatory");
					else if( $existing !='')
						$this->set_notice("same adcode already exists");
					else if($category_enabled ==1 && $sid >0 && !CategoryHelper::get_site_exists($sid,$uid))
						$this->set_notice("site invalid");
					
					else
					{
						
						
		
						if(mb_strlen($aduname) >25)
							$aduname=substr($aduname,0,25);
		
		
						if($adpricing !=9)
						{
		
							if($color1 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set at_color=? where id=?",array($color1,$aduid));
		
							if($color2 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set ad_color=? where id=?",array($color2,$aduid));
		
							if($color3 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set au_color=? where id=?",array($color3,$aduid));
		
							if($color4 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set ab_color=? where id=?",array($color4,$aduid));
		
							if($color5 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set ac_color=? where id=?",array($color5,$aduid));
		
							if($color6 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set htxt_color=? where id=?",array($color6,$aduid));
							if($color7 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set htxt_bgcolor=? where id=?",array($color7,$aduid));
							if($color8 !="")
								$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set abr_color=? where id=?",array($color8,$aduid));
							
						}
		
		
						$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set name=?,abr_type=?,`display_type`=?,`layout`=?,`native`=?,`responsive`=?,`custom_code`=?,`nativeimg_dimension`=?,`nativeimg_position`=? where id=?",array($aduname,$border,$adpricing,$layout,$native,$responsive,$customcode,$img_dim,$img_pos,$aduid));
						
						if($res->error =="")
						{
							if($category_enabled ==1)
								$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET sid=? WHERE id=?",array($sid,$aduid));
		
							$this->flash($this->get_message('adunit edit success'), $this->make_url('adunit/edit_native/'.$aduid));
							exit;
						}
						else
							$this->set_notice("error occurred");
					}
		
					$this->set_variable("aduname",$aduname);
					$this->set_variable("abrt",$border);
					$this->set_variable("sid",$sid);
				}
		
				$this->set_variable("responsive",$responsive);
				
				$this->set_variable('pricing',$adp);
		
				$layouttxt=$db->execute_query("select * from ".TABLE_PREFIX."nativeads_layout where type=1 and status=1");
				$layoutimg=$db->execute_query("select * from ".TABLE_PREFIX."nativeads_layout where type=2 and status=1");
				$this->set_result('layouttxt', $layouttxt);
				$this->set_result('layoutimg', $layoutimg);
				
				$resdim=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type =3");
				$this->set_result("res_dim",$resdim);
	
	}
	function preview_native_action()
	{
		$this->disable_notice_area();
		$id=$this->read_page_param(1);
		$prtype=$this->read_page_param(2);
		
		$this->set_variable("aduid",$id);
		$this->set_variable("prtype",$prtype);
		
		$db= DAL::get_instance();
		
		$res=$db->execute_query("select au.*,au.id as auid,au.name as auname,credittext,at_color,ad_color,au_color,ab_color,ac_color,abr_color,abr_type from ".TABLE_PREFIX."adunit au where au.id=?",array($id));
		$this->set_result("res",$res);
		
		$resdata=$res->fetch_assoc();
				
		$this->set_variable("ctext",$resdata['credittext']);
		
		$direction=0;
		$this->set_variable("direction",intval($direction));		
		
		
		$credit_text_array=$this->get_credittext($resdata['credittext'],1,1,1); //Last parameter 1 is for data return from DB
			
		$credit_text=$credit_text_array[0];
		$credit_type=$credit_text_array[1];
		$credit_icon=$credit_text_array[2];
		$credit_icon_type=$credit_text_array[3];

		
		$this->set_variable("credits_texts",$credit_text,0);
		$this->set_variable("credit_icon",$credit_icon,0);
		$this->set_variable("credittype",$credittype);
		$this->set_variable("credit_icon_type",$credit_icon_type);			
		
		
		$layoutdata=$resdata['layout'];
				
		$lres=$db->execute_query("select * from ".TABLE_PREFIX."nativeads_layout where id=?",array($layoutdata));
		$lrow=$lres->fetch_assoc();
		$rows=$lrow['rows'];
		$columns=$lrow['columns'];
		$typedata=$lrow['type'];

		if($typedata ==2)
		$typedata=11;
				
				
		$this->set_variable('rows',$rows);
		$this->set_variable('columns',$columns);
		$this->set_variable('typedata',$typedata);
				
				
		$datadimension=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($resdata['nativeimg_dimension']));
		$datadimension_row=$datadimension->fetch_assoc();
				
				
		$this->set_variable('nativead_minwidth_left_aligned',$datadimension_row['left_aligned_width']);				
		$this->set_variable('nativead_minwidth_top_aligned',$datadimension_row['top_aligned_width']);				
		$this->set_variable('nativead_minheight_left_aligned',$datadimension_row['left_aligned_height']);				
		$this->set_variable('nativead_minheight_top_aligned',$datadimension_row['top_aligned_height']);
	}
};	