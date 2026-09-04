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
	
	function create_action()
	{
		$this->set_title($this->get_label('create new adunit'));
		
		$db= DAL::get_instance();
		
		
		$res1=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=1");
		$this->set_result("res1",$res1);
			
		$res2=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=2");
		$this->set_result("res2",$res2);
						
		$res3=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 AND banner_type=0 AND type=3");
		$this->set_result("res3",$res3);
			
		$res7=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 AND banner_type=1 AND type=2");
		$this->set_result("res7",$res7);
						
		$res9=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 AND banner_type=3 AND type=4");
		$this->set_result("res9",$res9);
		
        $res13=$db->execute_query("select id,height,width,type,name from ".TABLE_PREFIX."adblock where status=1 AND banner_type=5 AND type=5");
		$this->set_result("res13",$res13);			
		
        $res14=$db->execute_query("select id,height,width from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type=0 AND vast_video_support =1");
		$this->set_result("res14",$res14);	
		
		$res15=$db->execute_query("select * from ".TABLE_PREFIX."adblock where status=1 AND banner_type=4 AND type=2");
		$this->set_result("res15",$res15);		
		
		
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
			$this->flash($this->get_message('no active adcode type exists'), $this->make_url('adunit/manage'),0);
			exit;
		}		
		
	
		$adcode_for=0;
		$player_size=0;
		$linear=0;
		$nonlinearbanner=0;
		$nonlineartext=0;
		$nonlinear_size=0;		
		
		$popup_support=0;
		$popunder_support=0;
		$poptab_support=0;
		
		
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
			$name=$this->read_post_param('name');
			$adpricing=$this->read_post_param('adpricing');
			
			
			
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
			$existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND sid=? AND name=? AND blockid=?",array(0,$sid,$name,$blockid));
			
			
			if($adpricing !=13 && $category_enabled ==1 && $sid ==0 && in_array($adpricing,$stringarray))
			$this->set_notice("please select a targeting site");
			else if($adpricing ==13 && $category_enabled ==1 && $sid ==0 && (in_array($adpricing,$stringarray) || $adcode_for ==1))
			$this->set_notice("please select a targeting site");			

			
			else if($adpricing ==13 && $adcode_for ==2 && $blockid ==0)
			$this->set_notice("please select html5 player dimension");			
			else if($adpricing !=9 && $adpricing !=13 && $blockid ==0)
			$this->set_notice("please select a adblock");			
			
			else if($adpricing ==13 && $adcode_for ==0)
			$this->set_notice("please select a player type");
			else if($adpricing ==13 && $adcode_for ==1 && $nonlinearbanner ==1 && $nonlinear_size ==0)
			$this->set_notice("please select nonlinear banner dimension");	
			else if($adpricing ==13 && $adcode_for ==1 && $linear ==0 && $nonlinearbanner ==0 && $nonlineartext ==0)
			$this->set_notice("you have no privilege for creating CPV adcodes");
			
			
			
			else if($pop_enabled ==1 && $adpricing ==9 && $popup_support ==0 && $popunder_support ==0 && $poptab_support ==0)
			$this->set_notice("please choose a supported pop type");
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
				
				
				if($adpricing !=9)
				{				
					$res=$db->execute_query("select * from ".TABLE_PREFIX."adblock where id=?",array($blockid));
					$result=$res->fetch_assoc();
	
					$qrs=$db->execute_query("insert into ".TABLE_PREFIX."adunit (`id`,`pubid`,`blockid`,`name`,`at_color`,`ad_color`,`au_color`,`ab_color`,`ac_color`,`abr_color`,`abr_type`,`status`,`credittext`,`display_type`) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array('',0,$blockid,$name,$result['tcolor'],$result['dcolor'],$result['ucolor'],$result['bcolor'],$result['ccolor'],$result['br_color'],$result['bordertype'],1,$result['credit_text'],$adpricing));
				}
				else
				$qrs=$db->execute_query("INSERT INTO ".TABLE_PREFIX."adunit (`id`,`pubid`,`name`,`status`,`display_type`) values(?,?,?,?,?)",array('',$uid,$name,1,$adpricing));
				
					
					
					
					
				if($qrs->error =="")
				{
					$last_id=$qrs->get_last_id();
					
					
					if($name =='')
					{
						$name='AdCode-'.$last_id;
						$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET name=? WHERE id=?",array($name,$last_id));
					}
					
					if($pop_enabled ==1 && $adpricing ==9)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET pop_up_support=?,pop_under_support=?,pop_tab_support=? WHERE id=?",array($popup_support,$popunder_support,$poptab_support,$last_id));
					
					if($category_enabled ==1)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET sid=? WHERE id=?",array($sid,$last_id));
					
					if($video_enabled ==1 && $adpricing ==13)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `player_size`=?,`linear`=?,`non_linear_text`=?,`non_linear_banner`=?,`video_type`=? WHERE id=?",array($player_data,$linear,$nonlineartext,$nonlinearbanner,$adcode_for,$last_id));
					
					if($adblocktype ==14)
					$db->execute_query("UPDATE ".TABLE_PREFIX."adunit SET `container_id`=? WHERE id=?",array($container_id,$last_id));					
					
					$this->flash($this->get_message('adunit create'), $this->make_url('adunit/edit/'.$last_id));
				}
				else
				$this->set_notice("error occurred");
			}
			
			$this->set_variable("name",$name);
			$this->set_variable("blockid",$blockid);
			$this->set_variable("adblocktype",$adblocktype);
			
		}
			
	
		
		$this->set_variable("sid",$sid);
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable('popup_support',$popup_support);
		$this->set_variable('popunder_support',$popunder_support);
		$this->set_variable('poptab_support',$poptab_support);
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
			$adpricing=$this->read_post_param("adpricing");
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
		
		
		if($adpricing =='')
		$adpricing=-1;		
		
		if($sid =='')
		$sid=0;
		
		$this->set_variable("adpricing",$adpricing);
		$this->set_variable("sid",$sid);
		
		
		
		$cpc_enabled=$this->get_addon_status('cpc_enabled');
		$cpm_enabled=$this->get_addon_status('cpm_enabled');
		$html_enabled=$this->get_addon_status('html_enabled');
		$cpa_enabled=$this->get_addon_status('cpa_enabled');		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$cpv_enabled=$this->get_addon_status('video-ads_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		
		
		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;		
		
		
		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" and display_type='".$adpricing."' ";
		
		
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
		
		if($cpv_enabled !=1)
		$adpricing_str.=' AND display_type <>13 ';		
		
		
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
		
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
				

		

		$sidquery='';
		if($this->get_addon_status('category-targeting_enabled') ==1)
		{
			if($sid >0)
			$sidquery=' AND sid='.$sid.' ';
		}
		
		
		
		$query="select a.*,ab.name as abname,ab.type,ab.banner_type,ab.width,ab.height from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid WHERE pubid=0 ".$adpricing_str." ".$sidquery." ".$adtype_str." ORDER BY a.id desc";
		
		
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	}
	function manage_user_adcode_action()
	{
		$mngr_str='';$usr_str1='';
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
			$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
			if($admintype ==3)
			{
				$mngr_str=" and pub_managerid=".$adminid;
			
				$usr_str=$this->get_users_under_mngr($admintype,$adminid);
				if($usr_str!='')
					$usr_str1=" and pubid in (".$usr_str.")";
				else $usr_str1=" and pubid =-2 ";
			}
		}
		$this->set_title($this->get_label('manage user adunits'));
		$search_by="";
		$search_text="";
		$pub=-1;
		if($_POST)
		{
			$pub=$this->read_post_param("pub");
			$adpricing=$this->read_post_param("adpricing");
			$search_by=$this->read_post_param("search_by");
			$search_text=$this->read_post_param("search_text");
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
		if($pub =='')
		$pub=-1;
		
		if($adpricing =='')
		$adpricing=-1;
		
		$sitejoin='';
		
		if($pub==-1)
		$pub_string="";
		else
		$pub_string=" and pubid='$pub' ";
		
		if($search_by == 1){
			$search_string = " and a.id=".intval($search_text)." ";
		}
		else if($search_by == 2){
			$search_string = " and u.username LIKE '%".$search_text."%' ";
		}
		else if($search_by == 3){
			$search_string = " and a.name LIKE '%".$search_text."%' ";
		}
		else if($search_by == 4){
			$search_string = " and s.url LIKE '%".$search_text."%' ";
			$sitejoin="JOIN ".TABLE_PREFIX."sites s ON a.sid=s.id";
		}
		
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
		
		
		if($cpm_enabled ==1 || $html_enabled ==1)
		$cpm_enabled=1;		
		
		
		if($adpricing ==-1)
		$adpricing_str="";
		else
		$adpricing_str=" and display_type='".$adpricing."' ";
		
		
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
		
		$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
		
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
		
		
		
		$this->set_variable("pub",$pub);
		
		$db= DAL::get_instance();
		
		
		
 		$query="select a.*,ab.name as abname,ab.type,ab.banner_type,ab.width,ab.height from ".TABLE_PREFIX."adunit a LEFT OUTER JOIN ".TABLE_PREFIX."adblock ab ON ab.id=a.blockid  JOIN ".TABLE_PREFIX."users u ON a.pubid=u.id ".$sitejoin." WHERE pubid<>0 ".$pub_string.$adpricing_str." ".$adtype_str." ".$usr_str1. " ".$search_string." ORDER BY a.id desc";
				
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);		
		
		$search_text = str_replace("\%","%",$search_text);
		$search_text = str_replace("\_","_",$search_text);
		$this->set_variable("search_by",$search_by);
		$this->set_variable("search_text",$search_text);
	
		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where pub_status=1 ".$mngr_str." order by id ASC");
		$this->set_result("res1",$res1);
	}
	function edit_action()
	{
		$this->set_title($this->get_label('edit adunit'));
		$db= DAL::get_instance();
		$mem_obj=$this->memcache_connect();
		
		if($_POST)
		$id=$this->read_post_param('aduid');
		else
		$id=$this->read_page_param(1);
		
		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$category_enabled=$this->get_addon_status('category-targeting_enabled');
		$pop_enabled=$this->get_addon_status('pop-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
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
			
		
        $res14=$db->execute_query("select id,height,width from ".TABLE_PREFIX."banner_dimensions where status=1 AND banner_type=0 AND vast_video_support =1");
		$this->set_result("res14",$res14);			
		
		
		$adcode_for=0;
		$linear=0;
		$nonlinearbanner=0;
		$nonlineartext=0;
		$nonlinear_size=0;		
		
		$blockid=$this->get_adblock_id($id);
		
		
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
		
		
		
		if(!$this->get_adunit_available_check($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('adunit/manage'),0);
		}
		else
		{
			
			$pricing=$this->get_adunit_preference_value($id);
			
			
				if($_POST)
				{
					if(DEMO_MODE && $id <=75)
					{
						$this->flash($this->get_message('demo mode'), $this->make_url('adunit/edit/'.$id),0);
						exit;
					}
									
					
					
					$id=$this->read_post_param('aduid');
					$name=$this->read_post_param('aduname');
					$adtype=$this->read_post_param('adtype');
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
					$existing=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."adunit WHERE pubid=? AND sid=? AND name=? AND id<>?",array(0,$sid,$name,$id));
			
					
					
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
							
					else if($sponsored_enabled ==1 && $adpricing ==3 && $name =='')
					$this->set_notice("mandatory");
					else if($sponsored_enabled ==1 && $adpricing ==3 && $existing !='')
					$this->set_notice("same adcode already exists");
					else if($category_enabled ==1 && $sid >0 && !CategoryHelper::get_site_exists($sid,0))
					$this->set_notice("site invalid");
					else if($pop_enabled ==1 && $adpricing ==9 && $popup_support ==0 && $popunder_support ==0 && $poptab_support ==0)
					$this->set_notice("please choose a supported pop type");
					else
					{
						if($name =="")
						$name="AdCode-".$id;
						
						
						if(mb_strlen($name) >25)
						$name=substr($name,0,25);
						
						
						
				$query_string='';
				$param_array=array();
				
				if($adpricing !=9 && $adpricing !=13)
				{
				
					$credittext=$this->read_post_param('credittext');
					$color5=$this->read_post_param('color5');
					$color6=$this->read_post_param('color6');
				
				
					if($color5=="")
						$color5="#FFFFFF";
						if($color6=="")
							$color6="#888888";
								
								
								
								
							if($adtype==1 || $adtype==3)
							{
								$border=$this->read_post_param('border');
								$color1=$this->read_post_param('color1');
								$color2=$this->read_post_param('color2');
								$color3=$this->read_post_param('color3');
								$color4=$this->read_post_param('color4');
									
								if($color1=="")
									$color1="#b50818";
									if($color2=="")
										$color2="#1437d7";
										if($color3=="")
											$color3="#079707";
											if($color4=="")
												$color4="#FFFFFF";
							}
								
							if($adtype==1 || $adtype==3)
							{
								$query_string.=' name =?,credittext =?,abr_type =?,at_color =?,ad_color =?,au_color =?,ab_color =?,ac_color =?,abr_color =?,display_type =?';
								$param_array=array_merge($param_array,array($name,$credittext,$border,$color1,$color2,$color3,$color4,$color5,$color6,$adpricing));
								if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
								{
									$mem_adunit_array['name']=$name;
									$mem_adunit_array['credittext']=$credittext;
									$mem_adunit_array['abr_type']=$border;
									$mem_adunit_array['at_color']=$color1;
									$mem_adunit_array['ad_color']=$color2;
									$mem_adunit_array['au_color']=$color3;
									$mem_adunit_array['ab_color']=$color4;
									$mem_adunit_array['ac_color']=$color5;
									$mem_adunit_array['abr_color']=$color6;
									$mem_adunit_array['display_type']=$adpricing;
								}
								
								if($category_enabled ==1)
								{
									$query_string.=',sid =?';
									$param_array=array_merge($param_array,array($sid));
								}
								
							}
							else if($adtype==2)
							{
								$query_string.=' name =?,credittext =?,ac_color =?,abr_color =?,display_type =?';
								$param_array=array_merge($param_array,array($name,$credittext,$color5,$color6,$adpricing));
								if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
								{
									$mem_adunit_array['name']=$name;
									$mem_adunit_array['credittext']=$credittext;
									$mem_adunit_array['ac_color']=$color5;
									$mem_adunit_array['abr_color']=$color6;
									$mem_adunit_array['display_type']=$adpricing;
								}
								
								
									if($category_enabled ==1)
									{
										$query_string.=',sid =?';
										$param_array=array_merge($param_array,array($sid));
										if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
										{
											$mem_adunit_array['sid']=$sid;   
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
							}
				}
				else if($pop_enabled ==1 && $adpricing ==9)
				{
					$query_string.=' name =?,pop_up_support=?,pop_under_support=?,pop_tab_support=?';
					$param_array=array_merge($param_array,array($name,$popup_support,$popunder_support,$poptab_support));
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
					{
						$mem_adunit_array['name']=$name;
						$mem_adunit_array['pop_up_support']=$popup_support;
						$mem_adunit_array['pop_under_support']=$popunder_support;
						$mem_adunit_array['pop_tab_support']=$poptab_support;
					}
				
				
						if($category_enabled ==1)
						{  
							$query_string.=',sid =?';
							$param_array=array_merge($param_array,array($sid));
							if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array['sid']=$sid;
							}
						}
				}
				else if($video_enabled ==1 && $adpricing ==13)
				{
					if($blockid > 0)
					{
						$credittext=$this->read_post_param('credittext');
						$color4=$this->read_post_param('color4');
						$color5=$this->read_post_param('color5');
						$color6=$this->read_post_param('color6');
				
						if($color4=="")
							$color4="#FFFFFF";
							if($color5=="")
								$color5="#FFFFFF";
								if($color6=="")
									$color6="#888888";
					}
				
						
					$query_string.=' name =?,`player_size` =?,`linear` =?,`non_linear_text` =?,`non_linear_banner` =?,`video_type` =?';
					$param_array=array_merge($param_array,array($player_data,$linear,$nonlineartext,$nonlinearbanner,$adcode_for));
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
					{
						$mem_adunit_array['name']=$name;
						$mem_adunit_array['player_size']=$player_data;
						$mem_adunit_array['linear']=$linear;
						$mem_adunit_array['non_linear_text']=$nonlineartext;
						$mem_adunit_array['non_linear_banner']=$nonlinearbanner;
						$mem_adunit_array['video_type']=$adcode_for;
					}
						
					if($blockid > 0)
					{ 
						$query_string.=',ab_color =?,ac_color =?,abr_color =?,credittext =?';
						$param_array=array_merge($param_array,array($color4,$color5,$color6,$credittext));
						if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
						{
							$mem_adunit_array['ab_color']=$color4;
							$mem_adunit_array['ac_color']=$color5;
							$mem_adunit_array['abr_color']=$color6;
							$mem_adunit_array['credittext']=$credittext;
						}
					}
				
					if($category_enabled == 1)
						{
							$query_string .= ',sid =?';
							$param_array = array_merge($param_array,array ($sid));
							if($mem_obj != false && ADUNIT_MEMCACHE_ENABLED == 1)
							{
								$mem_adunit_array ['sid'] = $sid;
							}
						}
				}
				
				
				
				$param_array=array_merge($param_array,array($id));
				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."adunit set ".$query_string." where id=?",$param_array);
				if($res->error == '')
				{
					if($mem_obj!=false && ADUNIT_MEMCACHE_ENABLED == 1)
						$mem_obj->set("adunit_res_".$aduid,$mem_adunit_array,MEMCACHE_EXPIRY);
							
						$this->flash($this->get_message('adunit edit success'), $this->make_url('adunit/edit/'.$id));
						exit;
				}
				else
				{
					$this->set_notice("error occurred");
				}
			 }
			$this->set_variable("name",$name);
			}
			
			$this->set_variable("aduid",$id);
			$this->set_variable("sid",$sid);
			
			
			
			if($pricing ==9 || ($pricing ==13 && $blockid ==0))
			$query = "select au.*,au.id as auid,au.name as auname from ".TABLE_PREFIX."adunit au where au.id=?";
			else
			$query = "select ab.*,au.*,au.id as auid,au.name as auname,credittext,at_color,ad_color,au_color,ab_color,ac_color,abr_color,abr_type from ".TABLE_PREFIX."adblock ab,".TABLE_PREFIX."adunit au  where ab.id=au.blockid and au.id=?";
			
			
			$res=$db->execute_query($query,array($id));
			$this->set_result("res",$res);
			
			
			$this->set_variable('pop_enabled',$pop_enabled);
			$this->set_variable('pricing',$pricing);
			
			$this->set_variable('adcode_for',$adcode_for);
			$this->set_variable('linear',$linear);
			$this->set_variable('nonlinearbanner',$nonlinearbanner);
			$this->set_variable('nonlineartext',$nonlineartext);
			$this->set_variable('nonlinear_size',$nonlinear_size);	
				
			
		}	
	}
	function delete_action()
	{
	
		$id=$this->read_page_param(1);
		$adpricing=$this->read_page_param(2);
		$sid=intval($this->read_page_param(3));
		
		$db= DAL::get_instance();
		
		
		if(DEMO_MODE && $id <=75)
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
				
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."packages WHERE posid=?",array($id));
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
		
		
		$credit=$db->execute_query("select * from ".TABLE_PREFIX."adunit where id=?",array($id));
		$credit=$credit->fetch_assoc();
		$this->set_variable("ctext",$credit['credittext']);
		
		
		$direction=0;
		$this->set_variable("direction",intval($direction));
		
		
		$credit_text_array=$this->get_credittext($credit['credittext'],1,1,1); //Last parameter 1 is for data return from DB
			
		$credit_text=$credit_text_array[0];
		$credit_type=$credit_text_array[1];
		$credit_icon=$credit_text_array[2];
		$credit_icon_type=$credit_text_array[3];

		
		$this->set_variable("credits_texts",$credit_text,0);
		$this->set_variable("credit_icon",$credit_icon,0);
		$this->set_variable("credittype",$credittype);
		$this->set_variable("credit_icon_type",$credit_icon_type);		
	}
};	