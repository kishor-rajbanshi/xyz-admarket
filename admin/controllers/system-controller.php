<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once(LIB_DIR_PATH."FCKeditor/fckeditor.php") ;

class SystemController extends ApplicationController
{
	
	function before_execute()
	{		
		parent::before_execute();
		if(!(LoginHelper::validate_admin_login()))
		{
			$this->flash($this->get_message('login failed'), $this->make_url('index/index'),0);
		}
		
		
		if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==1 && $this->get_action() != "todo")
		$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
		
		
	}

	
	
	function clear_cloudflare_cache_action()
	{
		$cloudflare_array=$this->remove_cloudflare_cache(2);

		$cloud_flag=0;
		
        if($cloudflare_array['success'] !=1)
        $cloud_flag=1;
        
        if($cloud_flag ==1)
        $this->flash($this->get_message('cloudflare cache clear failed'), $this->make_url('system/todo'),0);
		else
		$this->flash($this->get_message('cloudflare cache clear success'), $this->make_url('system/todo'));
		
		exit;
	}
	
	
	function delete_backup_action()
	{
		$this->disable_notice_area();
		$file=$this->read_page_param(1);
		
		if(file_exists('../backup/'.$file))
		{
			unlink('../backup/'.$file);
			echo 1;
			exit;
		}
		else
		{
			echo 0;
			exit;
		}
	}

	function backup_action()
	{
		$this->set_title($this->get_label('data backup'));
		$db= DAL::get_instance();
		
		
		if(DEMO_MODE)
       	{
       		$this->flash($this->get_message('demo mode'), $this->make_url('index/control_panel'),0);
       		exit;
       	}
		
		
	
		if(!is_callable('shell_exec') || stripos(ini_get('disable_functions'),'shell_exec'))
		$this->flash($this->get_message('shell execute function enable'), $this->make_url('index/control_panel'),0);
		
		
		
		
		
		$data=$db->execute_query("SHOW TABLES");
		$datanew=$data;
		$this->set_result('row',$data);
	
		$path=str_replace(ADMIN_DIR,'',getcwd());
	
		if($_POST)
		{
			$checkflag=0;
			$tablestring='';
			while($data1=$data->fetch_array())
			{
				if(isset($_POST[$data1[0]]) && $_POST[$data1[0]] ==1)
				{
					$checkflag=1;
					
					if($tablestring !='')
					$tablestring.=' ';
					
					$tablestring.=$data1[0];
				}
			}
			
			
			
			if($checkflag ==0)
			$this->set_notice('please select tables for backup');
			else
			{
				$newdataname=DB_NAME.'-'.date("d",time()).'-'.date("m",time()).'-'.date("Y",time()).'-'.date("H",time());
				$newdataname1=DB_NAME.'-Tables-'.date("d",time()).'-'.date("m",time()).'-'.date("Y",time()).'-'.date("H",time());
				
				if(!is_dir($path.'backup/'))
				mkdir($path.'backup/');
				
				
				if(isset($_POST['all_tables']) && $_POST['all_tables'] ==1)
				{
					$command = 'mysqldump -u '.DB_USER.' -p'.DB_PASSWORD.' '.DB_NAME.' | gzip > '.$path.'backup/'.$newdataname.'.sql.gz';
					$result = shell_exec($command);
				}
				else
				{
					$command = 'mysqldump -u '.DB_USER.' -p'.DB_PASSWORD.' '.DB_NAME.' '.$tablestring.' | gzip > '.$path.'backup/'.$newdataname1.'.sql.gz';
					$result = shell_exec($command);
				}
				$this->set_notice('you have successfully backup the data',1);
			}
			
			
		}
		
		
		$this->set_variable('path',$path.'backup/');
		
	
		$array=array();
		$totalcount=0;
		if(is_dir('../backup/'))
		{
			$folders=array_diff(scandir('../backup/'), array('..', '.'));
			
			foreach($folders as $folder)
			{
				$array[filemtime('../backup/'.$folder)]=$folder;
			}
			$totalcount=count($array);
		}
		
		$this->set_variable('totalcount',$totalcount);
		
		krsort($array);
		
		$array1[]=$array;
		
		$this->set_array('array',$array1);
		
	}


function todo_action()
{
	
	

	$this->set_title($this->get_label('admin todo'));
	$db= DAL::get_instance();

	$mngr_str='';
	$usr_str1='';
	$usr_str2='';
	
	$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
	$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
		
	$manager_id=$adminid;
	if($this->get_addon_status('subadmin_enabled') ==1)
	{	
		if($admintype==2 || $admintype==3)
		{
			$usr_str=$this->get_users_under_mngr($admintype,$adminid);
				if($usr_str!='')
					$usr_str1=" and uid in (".$usr_str.")";
				else $usr_str1=" and uid =-2 ";
			
			if($admintype ==3)
			{
				$mngr_str=" and pub_managerid=".$adminid;
			
				if($usr_str!='')
					$usr_str2=" and pid in (".$usr_str.")";
				else $usr_str2=" and pid =-2 ";
				
				
			}
			elseif ($admintype ==2)
			{
				$mngr_str=" and adv_managerid=".$adminid;
					
				if($usr_str!='')
					$usr_str2=" and pid in (".$usr_str.")";
				else $usr_str2=" and pid =-2 ";
			}
		
		}
	}
	
	
	$cron_running_time_orignal=$db->read_single_column("select time from ".TABLE_PREFIX."statistics_updation where task='cron_success_time'");


	$minute_cron_running_time=$db->read_single_column("select time  from ".TABLE_PREFIX."statistics_updation where task='impression_updation_minute'");
	
	$this->set_variable('minute_cron_running_time_original',$minute_cron_running_time);
	
	
	if($minute_cron_running_time >0)
	$minute_cron_running_time=substr($minute_cron_running_time,0,-2);
	
	$this->set_variable('minute_cron_running_time',$minute_cron_running_time);
	
	$this->set_variable("cron_running_time",$cron_running_time_orignal);
	

	$wcron_running_time_orignal=$db->read_single_column("select time from ".TABLE_PREFIX."statistics_updation where task='withdrawalcron_success_time'");
	$wcron_running_month=$db->read_single_column("select status from ".TABLE_PREFIX."statistics_updation where task='withdrawalcron_success_time'");
	
	
	
	
	
		$this->set_variable("wtime",$wcron_running_time_orignal);
	
		$this->set_variable("wmonth",$wcron_running_month);
	$cachepath="../".CACHE_DIR.DS."cache/";
	
	
	$cachecount = 0;
	if(count(glob($cachepath."*.php*")) >0)
	{
		foreach (glob($cachepath."*.php*") as $cachefiles)
		{
			$cachecount++;
		}
	}
	

	$city_enabled=$this->get_addon_status('city-targeting_enabled');
	
	if($city_enabled ==1 || Configuration::get_instance()->read('countrywise_data_tracking') ==1)
	{
		$geo_modify_time_city=$this->get_date_format(2,filemtime(LIB_DIR_PATH."geo/GeoLiteCity.dat"));
		$this->set_variable("geo_modify_time_city",$geo_modify_time_city);
	}
	
	$geo_modify_time_ip=$this->get_date_format(2,filemtime(LIB_DIR_PATH."geo/GeoLite2-Country.mmdb"));
	$this->set_variable("geo_modify_time_ip",$geo_modify_time_ip);

	
	$this->set_variable("city_enabled",$city_enabled);


	$pendingadv=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where adv_status='-1' ".$mngr_str." ");
	$pendingpub=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where pub_status='-1' ".$mngr_str." ");
	
	
	$text_ads_enabled=Configuration::get_instance()->read('text-ads_enabled');
		
	$cpv_enabled=$this->get_addon_status('video-ads_enabled');	
	

	$adtype_str="";
	$adpricing_str="";
	
	if($this->get_addon_status('cpc_enabled') !=1)
	$adpricing_str.=' AND display_type <>0 ';	
	
	if($this->get_addon_status('cpm_enabled') !=1)
	$adpricing_str.=' AND display_type <>1 ';
	
	if($this->get_addon_status('cpa_enabled') !=1)
	$adpricing_str.=' AND display_type <>6 ';
	
	if($this->get_addon_status('sponsored_enabled') !=1)
	$adpricing_str.=' AND display_type <>3 ';
	
	if($this->get_addon_status('pop-ads_enabled') !=1)
	$adpricing_str.=' AND display_type <>9 ';
	
	if($this->get_addon_status('affiliate-ads_enabled') !=1)
	$adpricing_str.=' AND display_type <>12 ';	
	
	if($cpv_enabled !=1)
	$adpricing_str.=' AND display_type <>13 ';	

	
	if($text_ads_enabled !=1)		
	$adtype_str.=' AND type <>1 ';		
	
	if($this->get_addon_status('interstitial_enabled') !=1)
	$adtype_str.=' AND type <>5 ';		
	
	if($this->get_addon_status('ecommerce-ads_enabled') !=1)
	$adtype_str.=' AND type <>7 ';		
	
	if($this->get_addon_status('text-image-ads_enabled') !=1)
	$adtype_str.=' AND type <>11 ';			
	
	if($cpv_enabled !=1)
	$adtype_str.=' AND type <>13 ';		
	
	if($this->get_addon_status('skin-ads_enabled') !=1)
	$adtype_str.=' AND type <>14 ';		

	
	$pendingads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where status='-1' AND uid >0 ".$adtype_str.$adpricing_str." ".$usr_str1." ");
	
	
	
	$rowresult=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."payment_gateway WHERE status=1 AND id <>3 AND id <>4 AND id <>5");
	$this->set_result('rowresult',$rowresult);
	
	
	$rowresult123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."withdrawal_gateway WHERE status=1");
	$this->set_result('rowresult123',$rowresult123);
	
	

	if($this->get_addon_status('category-targeting_enabled') ==1)
	{
		$pendingsites=$db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where status='-1' ".$usr_str2." ");
		$this->set_variable("pendingsites",$pendingsites);
	}
	
	
	
	$newsletter_addon_folder=$this->xyz_get_addon_folder_name("XYZADMNLR");
	
	$news_cron_running_time_orignal=0;
	
	if($this->get_addon_status($newsletter_addon_folder.'_enabled') ==1)
	$news_cron_running_time_orignal=Configuration::get_instance()->read('cron_end_time');
$news_cron_running_time_orignal=$db->read_single_column("select time from ".TABLE_PREFIX."statistics_updation where task='newsletter_cron_success_time'");
	

	$this->set_variable("news_cron_running_time",$news_cron_running_time_orignal);		

	
	
	$this->set_variable("pendingadv",$pendingadv);
	$this->set_variable("pendingpub",$pendingpub);
	$this->set_variable("pendingads",$pendingads);
	$this->set_variable("cachecount",$cachecount);
	
	
	$requestcount=0;
	
	if($this->get_addon_status('sponsored_enabled') ==1)
	$requestcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE status=2 AND cancel_request=1");
	
	
	$this->set_variable("requestcount",$requestcount);
	
	
	
	
	
	
        if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==2)
		{
			
			$activeadv=0;
			$totalads=0;
			$activeads=0;
			
			$activetextads=0;
			$activebannerads=0;
			$activehtmlads=0;
			$htmlads=0;
			/* custom work */
			
		
		$activeadv=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where adv_status=? and adv_managerid=?",array(1,$manager_id));
		
		
		$totalusers=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where adv_status=1 OR pub_status=1 and adv_managerid=?",array($manager_id));
		
		$this->set_variable("totalusers",$totalusers);
		
		/*$usr_str=$this->get_users_under_mngr($admintype,$manager_id);
			if($usr_str!='')
				$usr_str1=" and uid in (".$usr_str.")";
		     else $usr_str1=" and uid=-2 ";*/
		$this->set_variable("activeadv",$activeadv);
		
		
		
		$totalads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=0 ".$usr_str1." ");
		$activeads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads  where uid <>0 AND display_type=0 AND status=? ".$usr_str1." ",array(1));
			
		

		
		
		
		
		
		
		$activeadsall=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where uid <>0 AND status=1 ".$usr_str1." ");
		$this->set_variable("activeadsall",$activeadsall);
		

		$this->set_variable("totalads",$totalads);
		$this->set_variable("activeads",$activeads);
		
		
		$totaladscpm=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=1 ".$usr_str1." ");
		$activeadscpm=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads  WHERE uid <>0 AND display_type=1 AND status=? ".$usr_str1." ",array(1));
			
		$this->set_variable("totaladscpm",$totaladscpm);
		$this->set_variable("activeadscpm",$activeadscpm);
		
		
		
		$totaladscpa=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=6 ".$usr_str1." ");
		$activeadscpa=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads  WHERE uid <>0 AND display_type=6 AND status=? ".$usr_str1." ",array(1));
		
		$this->set_variable("totaladscpa",$totaladscpa);
		$this->set_variable("activeadscpa",$activeadscpa);
		
		

		$htmlads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid =0 AND display_type=2 ".$usr_str1." ");
		$activehtmlads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid =0 AND display_type=2 AND status=1 ".$usr_str1." ");
		
		$this->set_variable("htmlads",$htmlads);
		$this->set_variable("activehtmlads",$activehtmlads);
		
		
		$sponsoredads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=3 ".$usr_str1." ");
		$activesponsoredads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=3 AND status=1 ".$usr_str1." ");
		
		$this->set_variable("sponsoredads",$sponsoredads);
		$this->set_variable("activesponsoredads",$activesponsoredads);
		
		
		
		
		$interstitialads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=5 ".$usr_str1." ");
		$activeinterstitialads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=5 AND status=1 ".$usr_str1." ");
		
		$this->set_variable("interstitialads",$interstitialads);
		$this->set_variable("activeinterstitialads",$activeinterstitialads);
		
		
		
		$popads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=9 ".$usr_str1." ");
		$activepopads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=9 AND status=1 ".$usr_str1." ");
		
		$this->set_variable("popads",$popads);
		$this->set_variable("activepopads",$activepopads);
		
	
		
		
		
		
		$totaladsaffiliate=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND display_type=12 ".$usr_str1." ");
		$activeadsaffiliate=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads  WHERE uid <>0 AND display_type=12 AND status=1 ".$usr_str1." " );
		
		
		$this->set_variable("totaladsaffiliate",$totaladsaffiliate);
		$this->set_variable("activeadsaffiliate",$activeadsaffiliate);
		
		$totalactiveads=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads WHERE uid <>0 AND status=1 ".$usr_str1." ");
				$this->set_variable("totalactiveads",$totalactiveads);
		
		
		
		

				
		
		
		
	
	  }
      else if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==3)
      {
	      	$activepub=0;
	      	$activepub=$db->read_single_column("select count(id) from ".TABLE_PREFIX."users where pub_status=? and pub_managerid=?",array(1,$manager_id));
	      	$this->set_variable("activepub",$activepub);
	      	$usr_str=$this->get_users_under_mngr($admintype,$manager_id);
			
	      	if($usr_str!='')
			$usr_str1=" and pubid in (".$usr_str.")";
			else 
			$usr_str1=" and pubid=-2 ";
			    
			$query = "SELECT count(id) FROM ".TABLE_PREFIX."adunit where pubid<>0  ".$usr_str1." ORDER BY id desc";
			     
			$totaladcodes=$db->read_single_column($query);
			$this->set_variable('totaladcodes', $totaladcodes);
      }
	
      
}

function clear_cache_action()
{
	$db= DAL::get_instance();
	$cachedir = "../".CACHE_DIR.DS."cache/";
	$cd = dir($cachedir);
	while($cache_name = $cd->read())
	{
		$cache_create=filectime($cachedir.$cache_name);
		if($cache_create<(time()-3600))
		{
			unlink($cachedir.$cache_name);
		}
	}
	$cd->close();
	
	
	
	
	$cachedir = "../".CACHE_DIR.DS;
	$files = scandir($cachedir);
	foreach($files as $file)
	{
		if(is_file($cachedir.$file))
		{
			if($file != "index.php" && $file != ".." && $file != ".")
			unlink($cachedir.$file);
		}
	}

	
	$impressiondir = PATH_TO_ROOT.DATA_DIR.'/impression/';
	
	if(is_dir($impressiondir))
	{
		$impd = dir($impressiondir);
		while($impression_name = $impd->read())
		{
			unlink($impressiondir.$impression_name);
		}
		$impd->close();
		
		rmdir($impressiondir);
	}	
	

	
	$display_server_domains="";
	$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."config");
	while($row=$res->fetch_assoc())
	{
		if($row['name'] == 'display_server_domains')
			$display_server_domains=trim($row['value']);
	}
	
	if($display_server_domains !="")
	{
		$server_array=explode(',',$display_server_domains);
	
		$current_server_address=$_SERVER['SERVER_ADDR'];
	
		foreach($server_array as $akey=>$avalue)
		{
			if($avalue != $current_server_address)
			{
				$param1=$avalue;
				$param1=str_replace("https://",'',$param1);
				$param1=str_replace("http://",'',$param1);
					
				$urldata=$avalue.'/'.DISPLAY_DIR.'/index.php?page=index/clear_cache/'.md5($param1);
					
	
				$this->fetch_file_contents($urldata);
			}
		}
	}
	

	$this->flash($this->get_message('cache delete success'), $this->make_url('system/todo'));
	exit;
}
function update_geo_action()
{
	 $param=intval($this->read_page_param(1));
	 $page=intval($this->read_page_param(2));
	
	 if($param >1)
	 $param=0;
     
     if($param ==1)
     {
     	$downloadpath='https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz';
     	$downloadfile='GeoLiteCity.dat.tar.gz';
     	$tempfilepath='GeoIP/GeoLite2-City.mmdb';
     	$filecopypath=LIB_DIR_PATH.'geo/GeoLite2-City.mmdb';
     }
	 else 
	 {
	 	$downloadpath='https://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.tar.gz';
	 	$downloadfile='GeoIP.dat.tar.gz';
	 	$tempfilepath='GeoIP/GeoLite2-Country.mmdb';
	 	$filecopypath=LIB_DIR_PATH.'geo/GeoLite2-Country.mmdb';
	 }

	 
	 $this->remove_files('../'.DATA_DIR.'/tempgeo/');
	 
	 
	$geo_data="";
	if($geo_files=fopen($downloadpath,"r"))
	{
		while(!feof($geo_files))
		$geo_data.=fgetc($geo_files);

		fclose($geo_files);
	}
	elseif (function_exists('curl_init'))
	{
		$curl_obj = curl_init();
		curl_setopt($curl_obj, CURLOPT_URL,$downloadpath);
		curl_setopt($curl_obj, CURLOPT_HEADER, 0);
		curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);
		$geo_content = curl_exec($curl_obj);
		curl_close($curl_obj);
		$geo_data=$geo_content;
	}


	if(!is_dir("../".DATA_DIR."/tempgeo"))
	mkdir("../".DATA_DIR."/tempgeo",0777);


	if($geo_data !="")
	file_put_contents(ROOT_DIR_PATH.DATA_DIR."/tempgeo/".$downloadfile,$geo_data);


	$zip_file = '../'.DATA_DIR.'/tempgeo/'.$downloadfile;
	
	
	$phar = new PharData($zip_file);
    $phar->extractTo('../'.DATA_DIR.'/tempgeo/'); 
    
	$folders_inside_zip=array_diff(scandir('../'.DATA_DIR.'/tempgeo/'), array('..', '.'));
											
	foreach($folders_inside_zip as $each_folder)
	{
		if(is_dir('../'.DATA_DIR.'/tempgeo/'.$each_folder))
		rename('../'.DATA_DIR.'/tempgeo/'.$each_folder,'../'.DATA_DIR.'/tempgeo/GeoIP');
	}    
    

	if(copy("../".DATA_DIR."/tempgeo/".$tempfilepath,$filecopypath))
	{
		unlink($zip_file);
		$this->remove_files('../'.DATA_DIR.'/tempgeo/GeoIP');			
		
		
		if($page ==1)
		$this->flash($this->get_message('geo update success'), $this->make_base_url('addon/settings/city-targeting',ADMIN_DIR));
		else
		$this->flash($this->get_message('geo update success'), $this->make_url('system/todo'));
		
		exit;
	}
	else
	{
		unlink($zip_file);
		$this->remove_files('../'.DATA_DIR.'/tempgeo/GeoIP');	
		
		
		if($page ==1)
		$this->flash($this->get_message('geo update failed'), $this->make_base_url('addon/settings/city-targeting',ADMIN_DIR),0);
		else
		$this->flash($this->get_message('geo update failed'), $this->make_url('system/todo'),0);
		
		
		exit;
	}
	
}



	function add_action()
	{
		$this->set_title($this->get_label('create new page'));
		
		$db= DAL::get_instance();
	
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		
		if($_POST)
		{
			$menu_name=$this->read_post_param('menu_name');
			$seo_name=$this->read_post_param('seo_name');
			$page_title=$this->read_post_param('page_title');
			$content=$this->read_post_param('content');
			$keyword=$this->read_post_param('keyword');
			$description=$this->read_post_param('description');
			
			if($menu_name =="" || $content =="" || $seo_name =="")
			$this->set_notice("mandatory");
			else
			{
				if($language_enabled ==1)
				{
					$locale1='';
					$locale2=array();
					
					while($localerow=$localedata->fetch_assoc())
					{
						$submitvalue=trim($this->read_post_param('meta_description_'.$localerow['id']));
						$submitvalue1=trim($this->read_post_param('meta_keyword_'.$localerow['id']));
						$submitvalue2=trim($this->read_post_param('title_'.$localerow['id']));
						$submitvalue3=trim($this->read_post_param('content_'.$localerow['id']));
						$submitvalue4=trim($this->read_post_param('name_'.$localerow['id']));
							
						$this->set_variable('meta_description_'.$localerow['id'],$submitvalue);
						$this->set_variable('meta_keyword_'.$localerow['id'],$submitvalue1);
						$this->set_variable('title_'.$localerow['id'],$submitvalue2);
						$this->set_variable('content_'.$localerow['id'],$submitvalue3,0);
						$this->set_variable('name_'.$localerow['id'],$submitvalue4);
							
														
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_meta_description=?';
					
								$locale2[]=$submitvalue;
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_meta_keyword=?';
					
								$locale2[]=$submitvalue1;
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_title=?';
					
								$locale2[]=$submitvalue2;
							
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_content=?';
					
								$locale2[]=$submitvalue3;
							
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_name=?';
					
								$locale2[]=$submitvalue4;
							
						}
						$localedata->set_result_index();
					}
				
					
				
				
					$priority=$db->read_single_column("select max(priority) from ".TABLE_PREFIX."custom_pages");
						
					if($priority >=1)
					$newpriority=$priority+1;
					else
					$newpriority=1;
							
				
				$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."custom_pages (name,content,title,meta_keyword,meta_description,status,priority) values (?,?,?,?,?,?,?)",array($menu_name,$content,$page_title,$keyword,$description,0,$newpriority));
				
				
				if($res->error =="")
				{
					$lid=$res->get_last_id();
					
					if($language_enabled ==1)
					{
						if($locale1 !='' && count($locale2) >0)
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."custom_pages SET ".$locale1." WHERE id=".$lid." ",$locale2);
					} 
					
					
					$db->execute_query("INSERT INTO ".TABLE_PREFIX."seo_url (name,filename,seoname,pageid) VALUES (?,?,?,?)",array($menu_name,'Custom Page',$seo_name,$lid));
					
					$this->flash($this->get_message('page creation success'), $this->make_url('system/manage'));
				}
				else
				$this->set_notice("error occurred");
			}
		}
		else
		{
			$menu_name='';
			$page_title='';
			$content='';
			$keyword='';
			$description='';
			$seo_name='';
		}
		
		$this->set_variable('menu_name',$menu_name);
		$this->set_variable('seo_name',$seo_name);
		$this->set_variable('page_title',$page_title);
		$this->set_variable('content',$content);
		$this->set_variable('keyword',$keyword);
		$this->set_variable('description',$description);
	
	}
	function edit_action()
	{
		$this->set_title($this->get_label('edit page'));
	
		$db= DAL::get_instance();
	
		if($_POST)
		$id=$this->read_post_param("id");
		else
		$id=$this->read_page_param(1);

	
		
		if(!$this->get_page_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/manage'),0);
			exit;
		}
	
	
		if(DEMO_MODE && $id <=5)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('system/manage'),0);
			exit;
		}
	
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		
	
		if($_POST)
		{
			$menu_name=$this->read_post_param('menu_name');
			$seo_name=$this->read_post_param('seo_name');
			$page_title=$this->read_post_param('page_title');
			$content=$this->read_post_param('content');
			$keyword=$this->read_post_param('keyword');
			$description=$this->read_post_param('description');
	
			if($menu_name =="" || $content ==""  || $seo_name =="")
			$this->set_notice("mandatory");
			else
			{
				
				if($language_enabled ==1)
				{
					$locale1='';
					$locale2=array();
					
					while($localerow=$localedata->fetch_assoc())
					{
						$submitvalue=trim($this->read_post_param('meta_description_'.$localerow['id']));
						$submitvalue1=trim($this->read_post_param('meta_keyword_'.$localerow['id']));
						$submitvalue2=trim($this->read_post_param('title_'.$localerow['id']));
						$submitvalue3=trim($this->read_post_param('content_'.$localerow['id']));
						$submitvalue4=trim($this->read_post_param('name_'.$localerow['id']));
						
						
						$this->set_variable('meta_description_'.$localerow['id'],$submitvalue);
						$this->set_variable('meta_keyword_'.$localerow['id'],$submitvalue1);
						$this->set_variable('title_'.$localerow['id'],$submitvalue2);
						$this->set_variable('content_'.$localerow['id'],$submitvalue3,0);
						$this->set_variable('name_'.$localerow['id'],$submitvalue4);
						
						
						
														
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_meta_description=?';
					
								$locale2[]=$submitvalue;
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_meta_keyword=?';
					
								$locale2[]=$submitvalue1;
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_title=?';
					
								$locale2[]=$submitvalue2;
							
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_content=?';
					
								$locale2[]=$submitvalue3;
							
							
							
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_name=?';
					
								$locale2[]=$submitvalue4;
							
						}
						$localedata->set_result_index();
					}
				
				
				
				
				
				
				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."custom_pages SET name=?,content=?,title=?,meta_keyword=?,meta_description=? WHERE id=?",array($menu_name,$content,$page_title,$keyword,$description,$id));
	
	
				if($res->error =="")
				{
					
					if($language_enabled ==1)
					{
						if($locale1 !='' && count($locale2) >0)
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."custom_pages SET ".$locale1." WHERE id=".$id." ",$locale2);
					} 
					
					
					
					
					$db->execute_query("UPDATE ".TABLE_PREFIX."seo_url SET seoname=? WHERE pageid=?",array($seo_name,$id));
					
					$this->flash($this->get_message('page edit success'), $this->make_url('system/manage'));
				}
				else
				$this->set_notice("error occurred");
			}
		}
		else
		{
			$res_test=$db->execute_query("select * from ".TABLE_PREFIX."custom_pages where id=?",array($id));
			$res_test_data=$res_test->fetch_assoc();
	
			$menu_name=$res_test_data['name'];
			$page_title=$res_test_data['title'];
			$content=$res_test_data['content'];
			$keyword=$res_test_data['meta_keyword'];
			$description=$res_test_data['meta_description'];
			
			
			
			if($language_enabled ==1)
			{
				while($localerow=$localedata->fetch_assoc())
				{
					$this->set_variable('meta_description_'.$localerow['id'],$res_test_data[$localerow['id'].'_meta_description']);
					$this->set_variable('meta_keyword_'.$localerow['id'],$res_test_data[$localerow['id'].'_meta_keyword']);
					$this->set_variable('title_'.$localerow['id'],$res_test_data[$localerow['id'].'_title']);
					$this->set_variable('content_'.$localerow['id'],$res_test_data[$localerow['id'].'_content'],0);
					$this->set_variable('name_'.$localerow['id'],$res_test_data[$localerow['id'].'_name']);
					
				}
			}
			
			
			$seo_name=$db->read_single_column("SELECT seoname FROM ".TABLE_PREFIX."seo_url WHERE pageid=?",array($id));
		}
		
		$this->set_variable('id',$id);
		$this->set_variable('menu_name',$menu_name);
		$this->set_variable('seo_name',$seo_name);
		$this->set_variable('page_title',$page_title);
		$this->set_variable('content',$content,0);
		$this->set_variable('keyword',$keyword);
		$this->set_variable('description',$description);
	
	}
	function manage_action()
	{
		$this->set_title($this->get_label('manage pages'));
		$db= DAL::get_instance();
	
		$query = "SELECT * FROM ".TABLE_PREFIX."custom_pages ORDER BY priority ASC";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	}
	function delete_action()
	{
		$db= DAL::get_instance();
	
		$id=$this->read_page_param(1);

		if(!$this->get_page_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/manage'),0);
			exit;
		}
		
		if(DEMO_MODE && $id <=5)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('system/manage'),0);
			exit;
		}
	
				
		$priority=$db->read_single_column("select priority from ".TABLE_PREFIX."custom_pages where id=?",array($id));
		
		$res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."custom_pages WHERE id=?",array($id));
		$res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."seo_url WHERE pageid=?",array($id));
		
		$db->execute_query("update ".TABLE_PREFIX."custom_pages set priority=(priority-1) where priority >? ",array($priority));
		
	
		$this->flash($this->get_message('page delete success'), $this->make_url('system/manage'));
		exit;
	}
	
	
	
	function change_page_status_action()
	{
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);
		$operation=intval($this->read_page_param(2));
		
		if(!$this->get_page_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/manage'),0);
			exit;
		}
		
		if(DEMO_MODE && $id <=5)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('system/manage'),0);
			exit;
		}
	
		
		$db->execute_query("update ".TABLE_PREFIX."custom_pages set status=? where id=?",array($operation,$id));
		
		
		$this->flash($this->get_message('page status update success'), $this->make_url('system/manage'));
		exit;
	}
	
	function change_page_priority_action()
	{
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);
		$operation=$this->read_page_param(2);

		
		if(!$this->get_page_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/manage'),0);
			exit;
		}
		
		
	    $priority=$db->read_single_column("select priority from ".TABLE_PREFIX."custom_pages where id=?",array($id));

		if($operation==1)
		$newpriority=$priority-1;
		else
		$newpriority=$priority+1;

		
	    $newpriorityid=$db->read_single_column("select id from ".TABLE_PREFIX."custom_pages where priority=? limit 0,1",array($newpriority));
	
		$db->execute_query("update ".TABLE_PREFIX."custom_pages set priority=? where id=?",array($priority,$newpriorityid));
		$db->execute_query("update ".TABLE_PREFIX."custom_pages set priority=? where id=?",array($newpriority,$id));
	
		$this->flash($this->get_message('page priority update success'), $this->make_url('system/manage'));
		exit;
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	function add_banner_action()
	{
		$this->set_title($this->get_label('create custom banner'));
		
		$db= DAL::get_instance();
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		$title='';
		$content='';
		$type=0;
		if($_POST)
		{
			$title=$this->read_post_param('title');
			$content=$this->read_post_param('content');
			$type=$this->read_post_param('type');
			
			$clogo=$_FILES["clogo"]["name"];
			
			
			
			if($language_enabled ==1)
			{
				$locale1='';
				$locale2=array();
		
				while($localerow=$localedata->fetch_assoc())
				{
					$submitvalue=trim($this->read_post_param($localerow['id'].'_title'));
					$submitvalue1=trim($this->read_post_param($localerow['id'].'_content'));
					
					$this->set_variable($localerow['id'].'_title',$submitvalue);
					$this->set_variable($localerow['id'].'_content',$submitvalue1);
													
					if($locale1 !='')
					$locale1.=',';
			
					$locale1.=$localerow['id'].'_title=?';
					$locale2[]=$submitvalue;
			
			
					if($locale1 !='')
					$locale1.=',';
					
					$locale1.=$localerow['id'].'_content=?';
					
					$locale2[]=$submitvalue1;
				}
				$localedata->set_result_index();
			}
			
			
						
			if($title =="" || $content =="" || $clogo =="")
			$this->set_notice("mandatory");
			else
			{
					$extension=explode(".",$_FILES['clogo']['name']);
					$extensionname=strtolower($extension[count($extension)-1]);
					
					
					$clogo=time().'.'.$extensionname;
					
					
					if($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "jpg")
					{
						if($_FILES["clogo"]["error"] > 0)
						$this->set_notice("image file upload failed");
						else
						{
							$priority=$db->read_single_column("select max(priority) from ".TABLE_PREFIX."custom_banners");
						
							if($priority >=1)
							$newpriority=$priority+1;
							else
							$newpriority=1;
							
							
							$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."custom_banners (title,content,priority,status,type) values (?,?,?,?,?)",array($title,$content,$newpriority,0,$type));
							$id=$res->get_last_id();
							
							
							if(!is_dir('../'.DATA_DIR.'/logo'))
							mkdir('../'.DATA_DIR.'/logo',0777);

							if($res->error=="")
							{
								if($language_enabled ==1)
								{
									if($locale1 !='' && count($locale2) >0)
				 					$db->execute_query("UPDATE ".TABLE_PREFIX."custom_banners SET ".$locale1." WHERE id=".$id." ",$locale2);
								} 
								
								if(move_uploaded_file($_FILES["clogo"]["tmp_name"],'../'.DATA_DIR."/logo/".$id."-".$clogo))
								{
									$width1=480;
									$height1=420;
								
									$image=new ImageHelper('../'.DATA_DIR."/logo/".$id."-".$clogo);
									$image->resize_custom_limit($width1,$height1,'../'.DATA_DIR."/logo/".$id."-".$clogo);
									$image->setImage('../'.DATA_DIR."/logo/".$id."-".$clogo);
								
									$db->execute_query("UPDATE ".TABLE_PREFIX."custom_banners SET banner=? WHERE id=?",array($id.'-'.$clogo,$id));
									
									$this->flash($this->get_message('custom banner create success'), $this->make_url('system/custom_banners'));
									exit;
								}
								else
								{
									$this->flash($this->get_message('image file upload failed'), $this->make_url('system/edit_banner/'.$id),0);
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

		$this->set_variable('title',$title);
		$this->set_variable('content',$content);
		$this->set_variable('type',$type);
		
		
	}
		
	function custom_banners_action()
	{
		$this->set_title($this->get_label('manage custom banner'));
		$db= DAL::get_instance();
		
		$res =$db->execute_query("SELECT * FROM ".TABLE_PREFIX."custom_banners ORDER BY priority ASC");
		$this->set_result("res",$res);
	}	
	
	function change_status_action()
	{
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);
		$operation=intval($this->read_page_param(2));
		
		if(!$this->get_banner_exists($id))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('system/custom_banners'),0);
			exit;
		}
		
		$db->execute_query("update ".TABLE_PREFIX."custom_banners set status=? where id=?",array($operation,$id));
		
		
		$this->flash($this->get_message('banner status update success'), $this->make_url('system/custom_banners'));
		exit;
		
		
	}
	
	function change_priority_action()
	{
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);
		$operation=$this->read_page_param(2);

		
		if(!$this->get_banner_exists($id))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('system/custom_banners'),0);
			exit;
		}
		
		
	    $priority=$db->read_single_column("select priority from ".TABLE_PREFIX."custom_banners where id=?",array($id));

		if($operation==1)
		$newpriority=$priority-1;
		else
		$newpriority=$priority+1;

		
	    $newpriorityid=$db->read_single_column("select id from ".TABLE_PREFIX."custom_banners where priority=? limit 0,1",array($newpriority));
	
		$db->execute_query("update ".TABLE_PREFIX."custom_banners set priority=? where id=?",array($priority,$newpriorityid));
		$db->execute_query("update ".TABLE_PREFIX."custom_banners set priority=? where id=?",array($newpriority,$id));
	
		$this->flash($this->get_message('priority update success'), $this->make_url('system/custom_banners'));
		exit;
	
}
	
	
		
	function edit_banner_action()
	{
		$this->set_title($this->get_label('edit custom banner'));
		$db= DAL::get_instance();
		
		if($_POST)
		$id=$this->read_post_param("id");
		else
		$id=$this->read_page_param(1);
				
		if(!$this->get_banner_exists($id))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('system/custom_banners'),0);
			exit;
		}
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		$title='';
		$content='';
		if($_POST)
		{
			$title=$this->read_post_param('title');
			$content=$this->read_post_param('content');
			$type=$this->read_post_param('type');
			$clogo=$_FILES["clogo"]["name"];
			
			$this->set_variable('title',$title);
			$this->set_variable('content',$content);
			$this->set_variable('type',$type);
			
			if($language_enabled ==1)
			{
				$locale1='';
				$locale2=array();
		
				while($localerow=$localedata->fetch_assoc())
				{
					$submitvalue=trim($this->read_post_param($localerow['id'].'_title'));
					$submitvalue1=trim($this->read_post_param($localerow['id'].'_content'));
					
					$this->set_variable($localerow['id'].'_title',$submitvalue);
					$this->set_variable($localerow['id'].'_content',$submitvalue1);
													
					if($locale1 !='')
					$locale1.=',';
			
					$locale1.=$localerow['id'].'_title=?';
					$locale2[]=$submitvalue;
			
			
					if($locale1 !='')
					$locale1.=',';
					
					$locale1.=$localerow['id'].'_content=?';
					
					$locale2[]=$submitvalue1;
				}
				$localedata->set_result_index();
			}
			
			if($title =="" || $content =="")
			$this->set_notice("mandatory");
			else
			{
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."custom_banners SET title=?,content=?,type=? WHERE id=?",array($title,$content,$type,$id));
					if($res->error =="")
					{
						if($language_enabled ==1)
						{
							if($locale1 !='' && count($locale2) >0)
				 			$db->execute_query("UPDATE ".TABLE_PREFIX."custom_banners SET ".$locale1." WHERE id=".$id." ",$locale2);
						} 
					
						if($clogo !="")
						{
							$extension=explode(".",$_FILES['clogo']['name']);
							$extensionname=strtolower($extension[count($extension)-1]);
							
							$clogo=time().'.'.$extensionname;
							
							if($extensionname == "gif" || $extensionname == "jpeg" || $extensionname == "pjpeg" || $extensionname == "png" || $extensionname == "jpg")
							{
								if($_FILES["clogo"]["error"] > 0)
								{
									$this->flash($this->get_message('image file upload failed'), $this->make_url('system/edit_banner/'.$id),0);
									exit;
								}
								else
								{
									if(!is_dir('../'.DATA_DIR.'/logo'))
									mkdir('../'.DATA_DIR.'/logo',0777);
		
									if(move_uploaded_file($_FILES["clogo"]["tmp_name"],'../'.DATA_DIR."/logo/".$id."-".$clogo))
									{
											$width1=480;
											$height1=420;
										
											$image=new ImageHelper('../'.DATA_DIR."/logo/".$id."-".$clogo);
											$image->resize_custom_limit($width1,$height1,'../'.DATA_DIR."/logo/".$id."-".$clogo);
											$image->setImage('../'.DATA_DIR."/logo/".$id."-".$clogo);
										
											$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."custom_banners where id=?",array($id));
											
											$db->execute_query("UPDATE ".TABLE_PREFIX."custom_banners SET banner=? WHERE id=?",array($id.'-'.$clogo,$id));
											
											unlink('../'.DATA_DIR."/logo/".$old_name);
											
											$this->flash($this->get_message('custom banner edit success'), $this->make_url('system/custom_banners'));
											exit;
										}
										else
										{
											$this->flash($this->get_message('image file upload failed'), $this->make_url('system/edit_banner/'.$id),0);
											exit;
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
							$this->flash($this->get_message('custom banner edit success'), $this->make_url('system/custom_banners'));
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
			$res_test=$db->execute_query("select * from ".TABLE_PREFIX."custom_banners where id=?",array($id));
			$res_test_data=$res_test->fetch_assoc();
			
			$this->set_variable('title',$res_test_data['title']);
			$this->set_variable('content',$res_test_data['content']);
			$this->set_variable('clogo',$res_test_data['banner']);
			$this->set_variable('type',$res_test_data['type']);
		
			
			if($language_enabled ==1)
			{
				while($localerow=$localedata->fetch_assoc())
				{
					$this->set_variable($localerow['id'].'_title',$res_test_data[$localerow['id'].'_title']);
					$this->set_variable($localerow['id'].'_content',$res_test_data[$localerow['id'].'_content'],0);
				}
			}
		}
		
		$this->set_variable('id',$id);
	}	
		
	function delete_banner_action()
	{
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);

		if(!$this->get_banner_exists($id))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('system/custom_banners'),0);
			exit;
		}
		
		$old_name=$db->read_single_column("select banner from ".TABLE_PREFIX."custom_banners where id=?",array($id));
		unlink('../'.DATA_DIR."/logo/".$old_name);

		$priority=$db->read_single_column("select priority from ".TABLE_PREFIX."custom_banners where id=?",array($id));
		
		$db->execute_query("DELETE FROM ".TABLE_PREFIX."custom_banners WHERE id=?",array($id));
		
		$db->execute_query("update ".TABLE_PREFIX."custom_banners set priority=(priority-1) where priority >? ",array($priority));
		
		$this->flash($this->get_message('custom banner delete success'), $this->make_url('system/custom_banners'));
		exit;
	}
	



	function notifications_action()
	{
		$this->set_title($this->get_label('manage notifications'));
		$db= DAL::get_instance();
		
		if($_POST)
		{
			$type=$this->read_post_param('type');
			$status=$this->read_post_param('status');
		}
		else
		{
			$type=$this->read_page_param(1);
			$status=$this->read_page_param(2);
			
			$exp=explode("-",$type);
			if($exp[0]=="page")
			{
				$type=$this->read_page_param(2);
				$status=$this->read_page_param(3);
			}
		}
		
		
		if($type =="")
		$type=-1;
		
		if($status =="")
		$status=-1;
		
		
		$type_str="";
		$status_str="";
		$string="";
		
		if($type >-1)
		$type_str=' type='.$type.' ';
		
		if($status >-1)
		$status_str=' status='.$status.' ';
		
		
		if($type_str !="" && $status_str !="")
		$string=' WHERE '.$type_str.' AND '.$status_str;
		else if($type_str !="")
		$string=' WHERE '.$type_str;
		else if($status_str !="")
		$string=' WHERE '.$status_str;
		
		$query = "SELECT * FROM ".TABLE_PREFIX."notifications ".$string." ORDER BY id desc";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);
		$this->set_variable('type',$type);
		$this->set_variable('status',$status);
	}
	
	
	function add_notifications_action()
	{
		$this->set_title($this->get_label('new notification'));
		$db= DAL::get_instance();
		
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		$type=0;
		$message="";
		$time="";
		if($_POST)
		{
			$type=$this->read_post_param('type');
			$message=$this->read_post_param('message');
			$time=$this->read_post_param('time');
			
			
			$this->set_variable("time",$time);
			
			if($language_enabled ==1)
			{
				$locale1='';
				$locale2=array();
					
				while($localerow=$localedata->fetch_assoc())
				{
					$submitvalue=trim($this->read_post_param($localerow['id'].'_message'));
								
					$this->set_variable($localerow['id'].'_message',$submitvalue);

					if($locale1 !='')
					$locale1.=',';
					
					$locale1.=$localerow['id'].'_message=?';
					
					$locale2[]=$submitvalue;
				}
				$localedata->set_result_index();
			}
				
			
			if($message =="")
			$this->set_notice("mandatory");
			else 
			{
				if($time !="")
				{
					$timearray=explode('/',$time); // ********* day/month/year Format ***//
					$time=mktime(23,59,59,$timearray[1],$timearray[0],$timearray[2]);
				}
				else
				$time=time();
				
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."notifications (id,type,message,time) values (?,?,?,?)",array('',$type,$message,$time));
					$id=$res->get_last_id();
						
					if($language_enabled ==1)
					{
						if($locale1 !='' && count($locale2) >0)
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."notifications SET ".$locale1." WHERE id=".$id." ",$locale2);
					} 
						
					$this->flash($this->get_message('notifications create success'), $this->make_url('system/notifications'));
					exit;
			}	
		}

		$this->set_variable('type',$type);
		$this->set_variable('message',$message);
	}
	
	
	function edit_notifications_action()
	{
		$this->set_title($this->get_label('edit notification'));
		$db= DAL::get_instance();
		
		if($_POST)
		{
			$id=$this->read_post_param('id');
			$dbtype=$this->read_post_param('dbtype');
			$status=$this->read_post_param('status');
			$pg=$this->read_post_param('pg');
		}
		else
		{
			$id=$this->read_page_param(1);
			$dbtype=$this->read_page_param(2);
			$status=$this->read_page_param(3);
			$pg=$this->read_page_param(4);
		}
		
		
		if(!$this->get_notification_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/notifications'),0);
			exit;
		}
		
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		
		
		
		if($_POST)
		{
			$type=0;
			$message="";
			
			$type=$this->read_post_param('type');
			$message=$this->read_post_param('message');
			$time=$this->read_post_param('time');
			
			$this->set_variable("time",$time);

				if($language_enabled ==1)
				{
							$locale1='';
							$locale2=array();
					
							while($localerow=$localedata->fetch_assoc())
							{
								$submitvalue=trim($this->read_post_param($localerow['id'].'_message'));
								
								
								$this->set_variable($localerow['id'].'_message',$submitvalue);

								if($locale1 !='')
								$locale1.=',';
					
								$locale1.=$localerow['id'].'_message=?';
					
								$locale2[]=$submitvalue;
					
							}
							$localedata->set_result_index();
				}
			
			
			
			if(DEMO_MODE)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('system/notifications'),0);
				exit;
			}
			
			
			
			if($message =="")
			$this->set_notice("mandatory");
			else
			{
				if($time !="")
				{
					$timearray=explode('/',$time); // ********* day/month/year Format ***//
					$time=mktime(23,59,59,$timearray[1],$timearray[0],$timearray[2]);
				}
				else
				$time=time();
				
				
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."notifications SET type=?,message=?,time=? WHERE id=?",array($type,$message,$time,$id));
					
					if($language_enabled ==1)
					{
						if($locale1 !='' && count($locale2) >0)
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."notifications SET ".$locale1." WHERE id=".$id." ",$locale2);
					} 
					
					$this->flash($this->get_message('notification edit success'), $this->make_url('system/notifications/'.$dbtype.'/'.$status.'/'.$pg));
					exit;
			}
		}
		else 
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."notifications where id=?",array($id));
			$row=$res->fetch_assoc();
			
			$message=$row['message'];
			$type=$row['type'];			
			$time=date("d",$row['time']).'/'.date("m",$row['time']).'/'.date("Y",$row['time']);
			
			$this->set_variable("time",$time);
			
			
			if($language_enabled ==1)
			{
				while($localerow=$localedata->fetch_assoc())
				{
					$this->set_variable($localerow['id'].'_message',$row[$localerow['id'].'_message']);
				}
			}	
			
			
		}
		
		$this->set_variable("id",$id);
		$this->set_variable('message',$message);
		$this->set_variable('type',$type);
		
		
		
		$this->set_variable('dbtype',$dbtype);
		$this->set_variable('status',$status);
		$this->set_variable('pg',$pg);
		
		
	}
	
	
	function delete_notifications_action()
	{
		$id=$this->read_page_param(1);
		$dbtype=$this->read_page_param(2);
		$status=$this->read_page_param(3);
		$pg=$this->read_page_param(4);
			
		$db= DAL::get_instance();
		
		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('system/notifications'),0);
			exit;
		}
		
		if(!$this->get_notification_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/notifications'),0);
			exit;
		}
		

		$res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."notifications where id=?",array($id));
			
						
		$this->flash($this->get_message('notifications delete success'), $this->make_url('system/notifications/'.$dbtype.'/'.$status.'/'.$pg));
		exit;
		
	}


	function notification_status_action()
	{
		$id=$this->read_page_param(1);
		$operation=intval($this->read_page_param(2));
		$dbtype=$this->read_page_param(3);
		$status=$this->read_page_param(4);
		$pg=$this->read_page_param(5);
		
		$db= DAL::get_instance();
		
		if(!$this->get_notification_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/notifications'),0);
			exit;
		}
		
		
		$db->execute_query("UPDATE ".TABLE_PREFIX."notifications SET status=? where id=?",array($operation,$id));
		
		$this->flash($this->get_message('notifications status update success'), $this->make_url('system/notifications/'.$dbtype.'/'.$status.'/'.$pg));
		exit;
		
	}
	

	function meta_data_action()
	{
		$this->set_title($this->get_label('manage meta'));
		$db= DAL::get_instance();
		
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."meta");
		$this->set_result('row',$row);
		
		
		
		if($_POST)
		{
			if(DEMO_MODE)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('system/meta_data'),0);
				exit;
			}
			
			while($rowdata123=$row->fetch_assoc())
			{
				$title=$this->read_post_param('title_'.$rowdata123['id']);
				$keyword=$this->read_post_param('keyword_'.$rowdata123['id']);
				$description=$this->read_post_param('description_'.$rowdata123['id']);
				
				
				
				
				if($language_enabled ==1)
				{
					$locale1='';
					$locale2=array();
					
					while($localerow=$localedata->fetch_assoc())
					{
						$submitvalue=trim($this->read_post_param('keyword_'.$localerow['id'].'_'.$rowdata123['id']));
						$submitvalue1=trim($this->read_post_param('description_'.$localerow['id'].'_'.$rowdata123['id']));
						$submitvalue2=trim($this->read_post_param('title_'.$localerow['id'].'_'.$rowdata123['id']));
						
						
							if($locale1 !='')
							$locale1.=',';
					
							$locale1.=$localerow['id'].'_keyword=?';
					
							$locale2[]=$submitvalue;
						
						
						
							if($locale1 !='')
							$locale1.=',';
							
							$locale1.=$localerow['id'].'_description=?';
							
							$locale2[]=$submitvalue1;
						
						
						
							if($locale1 !='')
							$locale1.=',';
							
							$locale1.=$localerow['id'].'_title=?';
							
							$locale2[]=$submitvalue2;
						
					}
					$localedata->set_result_index();
				}
			
				
				
				$sql=$db->execute_query("update ".TABLE_PREFIX."meta set title=?,keyword=?,description=? where id=?",array($title,$keyword,$description,$rowdata123['id']));
			
				if($language_enabled ==1)
				{
					if($locale1 !='' && count($locale2) >0)
				 	$db->execute_query("UPDATE ".TABLE_PREFIX."meta SET ".$locale1." WHERE id=".$rowdata123['id']." ",$locale2);
				} 
			}
			
			$this->flash($this->get_message('meta update success'), $this->make_url('system/meta_data'));
			exit;
		}
	
		
	}
	function terms_action()
	{
		$this->set_title($this->get_label('manage terms'));
		$db= DAL::get_instance();
		
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		
		$this->set_variable("advterm",1);
		
		$data=$db->execute_query("select * from ".TABLE_PREFIX."terms where id=1");
		$data1=$db->execute_query("select * from ".TABLE_PREFIX."terms where id=2");
		
		$this->set_result('data',$data);
		$this->set_result('data1',$data1);
		
		if($_POST)
		{
			if(DEMO_MODE)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('system/terms'),0);
				exit;
			}
	
			$advterm=$this->read_post_param('advterm');
			$description=$this->read_post_param('description');
			$description1=$this->read_post_param('description1');
			
			if($advterm==1 && $description =="")
			$this->set_notice("mandatory");
			else if($advterm==2 && $description1 =="")
			$this->set_notice("mandatory");
			else
			{
				if($language_enabled ==1)
				{
					$locale1='';
					$locale2=array();
					
					while($localerow=$localedata->fetch_assoc())
					{
						if($advterm ==1)
						$submitvalue=trim($this->read_post_param('description_'.$localerow['id']));
						else
						$submitvalue=trim($this->read_post_param('description1_'.$localerow['id']));
												
						
						
						
							if($locale1 !='')
							$locale1.=',';
					
							$locale1.=$localerow['id'].'_description=?';
					
							$locale2[]=$submitvalue;
						
					}
					$localedata->set_result_index();
				}
				
				if($advterm ==1)
				$sql=$db->execute_query("update ".TABLE_PREFIX."terms set description=? where id=1",array($description));
				else if($advterm ==2)
				$sql=$db->execute_query("update ".TABLE_PREFIX."terms set description=? where id=2",array($description1));
					
				if($language_enabled ==1)
				{
					if($locale1 !='' && count($locale2) >0)
				 	$db->execute_query("UPDATE ".TABLE_PREFIX."terms SET ".$locale1." WHERE id=".$advterm." ",$locale2);
				} 
	
				$this->flash($this->get_message('terms update success'), $this->make_url('system/terms'));
				exit;
			}
		}
	}
	function about_us_action()
	{
		$this->set_title($this->get_label('about us'));
		$db= DAL::get_instance();
	
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		$data=$db->execute_query("select * from ".TABLE_PREFIX."aboutus where id=1");
		$this->set_result('data',$data);
		
		if($_POST)
		{
			if(DEMO_MODE)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('system/about_us'),0);
				exit;
			}
	
			$description=$this->read_post_param('description');
			
			if($description =="")
			$this->set_notice("mandatory");
			else
			{
				if($language_enabled ==1)
				{
					$locale1='';
					$locale2=array();
					
					while($localerow=$localedata->fetch_assoc())
					{
						$submitvalue=trim($this->read_post_param('description_'.$localerow['id']));

							if($locale1 !='')
							$locale1.=',';
					
							$locale1.=$localerow['id'].'_description=?';
					
							$locale2[]=$submitvalue;
					
					}
					$localedata->set_result_index();
				}
				
				$sql=$db->execute_query("UPDATE ".TABLE_PREFIX."aboutus SET description=? WHERE id=1",array($description));
				
				if($language_enabled ==1)
				{
					if($locale1 !='' && count($locale2) >0)
				 	$db->execute_query("UPDATE ".TABLE_PREFIX."aboutus SET ".$locale1." WHERE id=1",$locale2);
				} 
				
				$this->flash($this->get_message('aboutus update success'), $this->make_url('system/about_us'));
				exit;
			}
		}
	}	

	
	function testimonial_create_action()
	{
		$db= DAL::get_instance();
		$this->set_title($this->get_label('create testimonial'));
		
		$res1=$db->execute_query("select id,username from ".TABLE_PREFIX."users where adv_status=1 or pub_status=1  order by id ASC");
		$this->set_result("res1",$res1);
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		
		if($_POST)
		{
			$usr=intval($this->read_post_param('usr'));
			$testimonial=$this->read_post_param('testimonial');
		
			$this->set_variable('usr',$usr);
			$this->set_variable('testimonial',$testimonial);
			
				if($usr ==0 || $testimonial =="")
				$this->set_notice("mandatory");
				else
				{
					
					/*
					if($language_enabled ==1)
					{
						$locale1='';
						$locale2=array();
					
						while($localerow=$localedata->fetch_assoc())
						{
							$submitvalue=trim($this->read_post_param('description_'.$localerow['id']));
							
							$this->set_variable('description_'.$localerow['id'],$submitvalue);
							
														
							
								if($locale1 !='')
								$locale1.=',';
						
								$locale1.=$localerow['id'].'_description=?';
					
								$locale2[]=$submitvalue;
							
						}
						$localedata->set_result_index();
					}
				*/
					
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."testimonial (uid,description,time) values (?,?,?)",array($usr,$testimonial,time()));
					$iddata=$res->get_last_id();
					
				/*	if($language_enabled ==1)
					{
						if($locale1 !='' && count($locale2) >0)
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."testimonial SET ".$locale1." WHERE id=".$iddata." ",$locale2);
					} 
					*/
					
					if($res->error =="")
					$this->flash($this->get_message('testimonial creation success'), $this->make_url('system/testimonial'));
					else
					$this->set_notice("error occurred");
				}
		}
	}
	function testimonial_edit_action()
	{
		
		$db= DAL::get_instance();
		$this->set_title($this->get_label('edit testimonial'));
		
				
		if($_POST)
		{
			$id=$this->read_post_param("id");
			$usr=$this->read_post_param("usr");
			$pg=$this->read_post_param("pg");
		}
		else
		{
			$id=$this->read_page_param(1);
			$usr=$this->read_page_param(2);
			$pg=$this->read_page_param(3);
		}
		
		if($usr=="")
		$usr=0;
		
		if($pg=="")
		$pg="page-1";
		
		
		if(!$this->get_testimonial_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/testimonial'),0);
			exit;
		}
		
		
		if(DEMO_MODE && $id <=5)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('system/testimonial'),0);
			exit;
		}
		
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
				
		if($_POST)
		{
			$testimonial=$this->read_post_param('testimonial');
			$uid=$this->read_post_param('uid');
			
			$this->set_variable('uid',$uid);
			$this->set_variable('testimonial',$testimonial);
		
			if($testimonial =="")
			$this->set_notice("mandatory");
			else
			{
				/*if($language_enabled ==1)
				{
					$locale1='';
					$locale2=array();
					
					while($localerow=$localedata->fetch_assoc())
					{
						$submitvalue=trim($this->read_post_param('description_'.$localerow['id']));
							
						$this->set_variable('description_'.$localerow['id'],$submitvalue);
							
														
						
							if($locale1 !='')
							$locale1.=',';
						
							$locale1.=$localerow['id'].'_description=?';
					
							$locale2[]=$submitvalue;
						
					}
					$localedata->set_result_index();
				}*/
				
				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."testimonial SET description=?,time=? WHERE id=?",array($testimonial,time(),$id));
			
				
				/*if($language_enabled ==1)
				{
					if($locale1 !='' && count($locale2) >0)
					$db->execute_query("UPDATE ".TABLE_PREFIX."testimonial SET ".$locale1." WHERE id=".$id." ",$locale2);
				} */
				
				if($res->error =="")
				$this->flash($this->get_message('testimonial edit success'), $this->make_url('system/testimonial/'.$usr.'/'.$pg));
				else
				$this->set_notice("error occurred");
			}
		}
		else
		{
			$res_test=$db->execute_query("select * from ".TABLE_PREFIX."testimonial where id=?",array($id));
			$res_test_data=$res_test->fetch_assoc();
			
			$this->set_variable('uid',$res_test_data['uid']);
			$this->set_variable('testimonial',$res_test_data['description']);
			
		/*	while($localerow=$localedata->fetch_assoc())
			{
				$this->set_variable('description_'.$localerow['id'],$res_test_data[$localerow['id'].'_description']);
			}*/
		}
		
		
		$this->set_variable('id',$id);
		$this->set_variable('pg',$pg);
		$this->set_variable('usr',$usr);
		
	}
	function testimonial_action()
	{
		$this->set_title($this->get_label('manage testimonials'));
		
		if($_POST)
		{
			$usr=$this->read_post_param('usr');
		}
		else
		{
			$usr=$this->read_page_param(1);
		
			$exp=explode("-",$usr);
			if($exp[0]=="page")
			$usr=0;
		
		
		}
		
		if($usr =="")
		$usr=0;
		
		if($usr ==0)
		$usr_str="";
		else
		$usr_str=" where uid='".$usr."' ";
		
		$this->set_variable("usr",$usr);
			
		$db= DAL::get_instance();
		
		$query = "SELECT * FROM ".TABLE_PREFIX."testimonial ".$usr_str." ORDER BY id desc";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);
		
		
		$sql1="select id,username from ".TABLE_PREFIX."users where adv_status=1 or pub_status=1  order by id ASC";
		$res1=$db->execute_query($sql1);
		$this->set_result("res1",$res1);
	}
	function testimonial_delete_action()
	{
		$db= DAL::get_instance();
		
		$id=$this->read_page_param(1);
		$usr=$this->read_page_param(2);
		$pg=$this->read_page_param(3);

		
		if($usr=="")
		$usr=0;
		
		if($pg=="")
		$pg="page-1";
		
		
		if(!$this->get_testimonial_exists($id))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('system/testimonial'),0);
			exit;
		}
		
		if(DEMO_MODE && $id <=5)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('system/testimonial'),0);
			exit;
		}
		
		$res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."testimonial WHERE id=?",array($id));
		
		$this->flash($this->get_message('testimonial delete success'), $this->make_url('system/testimonial/'.$usr.'/'.$pg));
		exit;
	}
	
	
	function seo_url_action()
	{
		$this->set_title($this->get_label('manage seo url'));
		$db= DAL::get_instance();
		
		if($_POST)
		{


		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('system/seo_url'),0);
			exit;
		}



			$row123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."seo_url");
			while($row1234=$row123->fetch_assoc())
			{
				if(isset($_POST['seoname_'.$row1234['id']]))
				{
					$seoname=$this->read_post_param('seoname_'.$row1234['id']);
					
					if($row1234['pageid'] ==0 || ($row1234['pageid'] >0 && $seoname !=''))
					$db->execute_query("UPDATE ".TABLE_PREFIX."seo_url SET seoname=? WHERE id=?",array($seoname,$row1234['id']));
				}
			}
			
			$this->flash($this->get_message('seo url update success'), $this->make_url('system/seo_url'));
			exit;
		}
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."seo_url");
		$this->set_result('row',$row);
	}

	
	function tax_rules_action()
	{
	    $this->set_title($this->get_label('manage tax rules'));
	    $db= DAL::get_instance();
	    
	    $res = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."tax_rules");
	    $this->set_result("res",$res,array('country'));
	}
	function create_rule_action()
	{
	    $this->set_title($this->get_label('create tax rule'));
	    
	    $user_type			= 0;
	    $name				= "";
	    $tax				= "";
	    $json_string		= "";
        $selectedlocations	= "";
		$selarray			= array();	    
	    
	    
	    $db= DAL::get_instance();
	    
	    if($_POST)
	    {
	        $name=$this->read_post_param('name');
	        $tax=$this->read_post_param('tax');
	        $user_type=intval($this->read_post_param('user_type'));
	        
	        if($user_type ==0)
	        $user_type =3;
	        
	        
	        if($tax < 0)
	        $tax=0;
	        
	        
        	$selectedlocations=$this->read_post_param('a_loc');
			
			$selectedlocationsnew=substr($selectedlocations,0,-1);
			$selectedlocations_array=explode(",",$selectedlocationsnew);
				
			foreach($selectedlocations_array as $k1=>$v1)
			{
				if($v1 !='')
				$selarray[$v1]=$this->get_country_name($v1);
			}
			asort($selarray);	

			
			if(count($selarray) >0)
			$json_string=json_encode($selarray);
	        
	            
	        if($name =="" || $tax =="")
	        $this->set_notice("mandatory");
	        else if(!is_numeric($tax))
	        $this->set_notice("positive value");
            else if($db->read_single_column("select count(id) from ".TABLE_PREFIX."tax_rules WHERE name=?",array($name)) >0)
	        $this->set_notice("tax rule already exists");
	        else
	        {
	             $res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."tax_rules (name,tax,country,status,user_type) values (?,?,?,?,?)",array($name,$tax,$json_string,-1,$user_type));
	                            
	             if($res->error =="")
	             {
	                  $this->flash($this->get_message('tax rule created'), $this->make_url('system/tax_rules'));
	                   exit;
	             }
	             else
	             $this->set_notice("error occured");
	         }
	                        
	    }
	    
	    $this->set_variable('name',$name);
	    $this->set_variable('tax',$tax);
	    $this->set_variable('user_type',$user_type);
	    
	    
	    
	    
      	$qstring='';
		$ststr='';
		if($selectedlocations !='')
		{
			$selected_array=explode(',',$selectedlocations);
			foreach($selected_array as $key=>$val)
			{
				if($ststr !='')
				$ststr.=',';
		
				$ststr.="'".$val."'";
			}
		
			if($ststr !='')
			$qstring=" code NOT IN(".$ststr.") and ";
		}
		
		
		
		$this->set_array('selarray',$selarray,array(),1);
		$this->set_variable('selectedlocations',$selectedlocations);
		
		
		$result=$db->execute_query("select code,name from ".TABLE_PREFIX."countries where ".$qstring." code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
		$this->set_result("result",$result);
	}
	
	function delete_rule_action()
	{
	    $db= DAL::get_instance();
	    $id=$this->read_page_param(1);
	    
	    if(!$this->get_rule_exists($id))
	    {
	        $this->flash($this->get_message('invalid id'), $this->make_url('system/tax_rules'));
	        exit;
	    }
	    
	    $db->execute_query("DELETE FROM ".TABLE_PREFIX."tax_rules WHERE id=?",array($id));
	    
	    $this->flash($this->get_message('tax rule deleted'), $this->make_url('system/tax_rules'));
	    exit;
	}
	
	function change_rule_status_action()
	{
	    $id=intval($this->read_page_param(1));
	    $status=intval($this->read_page_param(2));
	    
	    if(!$this->get_rule_exists($id))
	    {
	        $this->flash($this->get_message('invalid id'), $this->make_url('system/tax_rules'));
	        exit;
	    }
	    
	    $db= DAL::get_instance();
	    
	    $db->execute_query("UPDATE ".TABLE_PREFIX."tax_rules SET status=? WHERE id=?",array($status,$id));
	    
	    $this->flash($this->get_message('tax rule status updated'), $this->make_url('system/tax_rules'));
	}
	
	function edit_rule_action()
	{
	    $this->set_title($this->get_label('edit tax rule'));
	    
	    $db= DAL::get_instance();
	    
	    if($_POST)
	    $id=$this->read_post_param('id');
	    else
	    $id=$this->read_page_param(1);
	            
	            
	    if(!$this->get_rule_exists($id))
	    {
	         $this->flash($this->get_message('invalid id'), $this->make_url('system/tax_rules'));
	         exit;
	    }
	            
	    $json_string		= "";
        $selectedlocations	= "";
		$selarray			= array();		    
	    
	            
        if($_POST)
        {
           $name=$this->read_post_param('name');
           $tax=$this->read_post_param('tax');
           $user_type=$this->read_post_param('user_type');
                
           if($tax < 0)
           $tax=0;
                    
           $this->set_variable('name',$name);
           $this->set_variable('tax',$tax);
           $this->set_variable('user_type',$user_type);
           
           
       		$selectedlocations=$this->read_post_param('a_loc');
       		
			
			$selectedlocationsnew=substr($selectedlocations,0,-1);
			$selectedlocations_array=explode(",",$selectedlocationsnew);
				
			foreach($selectedlocations_array as $k1=>$v1)
			{
				if($v1 !='')
				$selarray[$v1]=$this->get_country_name($v1);
			}
			asort($selarray);	


			
			if(count($selarray) >0)
			$json_string=json_encode($selarray);           
           
            if($name =="" || $tax =="")
            $this->set_notice("mandatory");
            else if(!is_numeric($tax))
            $this->set_notice("positive value");
            else if($db->read_single_column("select count(id) from ".TABLE_PREFIX."tax_rules WHERE name=? AND id<>?",array($name,$id)) >0)
            $this->set_notice("tax rule already exists");
            else
            {
                $res=$db->execute_query("UPDATE ".TABLE_PREFIX."tax_rules SET name=?,tax=?,country=? WHERE id=?",array($name,$tax,$json_string,$id));
                                    
                if($res->error =="")
                {
                    $this->flash($this->get_message('tax rule edited'), $this->make_url('system/tax_rules'));
                    exit;
                }
                else
                $this->set_notice("error occured");
            }
       }
       else
       {
            $res=$db->execute_query("select * from ".TABLE_PREFIX."tax_rules where id=?",array($id));
            $this->set_result("res",$res);
            
            $fetch_data=$res->fetch_assoc();
            
            $country=$fetch_data['country'];
            
            $json		= array();
            $string		= "";
            
            if($country !="")
            {
            	$json=json_decode($country,1);
            	
            	foreach($json as $jkey=>$jvalue)
            	{
					$string.=$jkey;
					
	 				if($string !='')
					$string.=',';  					
            	}
            }
            
            
            $selarray=$json;
            
 			$selectedlocations=$string;
			asort($selarray);           
       }
            
       $this->set_variable('id',$id);
       
       
      	$qstring='';
		$ststr='';
		if($selectedlocations !='')
		{
			$selected_array=explode(',',$selectedlocations);
			foreach($selected_array as $key=>$val)
			{
				if($ststr !='')
				$ststr.=',';
		
				$ststr.="'".$val."'";
			}
		
			if($ststr !='')
			$qstring=" code NOT IN(".$ststr.") and ";
		}
		
		
		
		$this->set_array('selarray',$selarray,array(),1);
		$this->set_variable('selectedlocations',$selectedlocations);
		
		
		$result=$db->execute_query("select code,name from ".TABLE_PREFIX."countries where ".$qstring." code!='A1' and code!='A2' and code!='AP' and code!='EU' ORDER BY name");
		$this->set_result("result",$result);       
	}
	




















};
?>
