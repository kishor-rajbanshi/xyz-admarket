<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class AutocronController extends ApplicationController
{
	function data_backup_action()
	{
		$this->disable_notice_area();
		
		if(file_exists("../".CACHE_DIR."/cron/lock-auto-data.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-auto-data.txt");
			
			if(date('d',$file_creation_time) != date('d',time()))
			unlink("../".CACHE_DIR."/cron/lock-auto-data.txt");
		}			
		
		
		
		$db= DAL::get_instance();
		
		
        if(!is_dir("../".CACHE_DIR."/cron"))
       	mkdir("../".CACHE_DIR."/cron",0777);		
		
		
		$fp = fopen("../".CACHE_DIR."/cron/lock-auto-data.txt", "a+"); 
		
		if(flock($fp, LOCK_EX | LOCK_NB)) 
		{ 	
			$cron_running_time=date('Y-m-d-H:i:s',time());
			
			$appendstring="Locked At : ".$cron_running_time." - Data Backup Cron == ";		
		
			
			set_time_limit(0);
				
			$cpm_addon_enabled=$this->get_addon_status('cpm_enabled');
			$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
			$pop_addon_enabled=$this->get_addon_status('pop-ads_enabled');
			$affiliate_addon_enabled=$this->get_addon_status('affiliate-ads_enabled');
			$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
			$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
			$category_enabled=$this->get_addon_status('category-targeting_enabled');
			$device_enabled=$this->get_addon_status('device-targeting_enabled');
			$isp_enabled=$this->get_addon_status("isp-connection-targeting_enabled");
			$language_enabled=$this->get_addon_status('language-targeting_enabled');
			
			
			$time_data=time()-(7*86400);  // 7 Days
			

			$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE status=-2 AND updation_time <?",array($time_data));

			while($rowdata=$row->fetch_assoc())
			{
				$aid=$rowdata['id'];
				$adtype=$rowdata['type'];
				$display_type=$rowdata['display_type'];
				
				$banner_name=$rowdata['banner'];
				$ecommercelogo=$rowdata['logo'];
				$ad_parent=intval($rowdata['ecommerce_parent']);				
				$bannertype=intval($rowdata['html5']);

				if(($affiliate_addon_enabled ==1 || $affiliate_addon_enabled ==0) && $adtype ==12)
				{
					$imagelist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($aid));
					
					while($imagerow=$imagelist->fetch_assoc())
					{
						unlink('../'.DATA_DIR.'/banners/'.$aid.'/'.$imagerow['image']);
					}
					
					rmdir('../'.DATA_DIR.'/banners/'.$aid);
					
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_image_mapping WHERE aid=?",array($aid));
				}
	

				if($adtype ==2 || $adtype ==5 || $adtype ==10 || $adtype ==11)
				{
				
					if($bannertype ==0)
					{				
				
						unlink('../'.DATA_DIR.'/'.$aid.'_'.$banner_name);
					
						if($adtype ==2 && isset($rowdata['expandable']) && $rowdata['expandable'] ==1)
						{
							$expandable_banner=$rowdata['expandable_banner'];
							
							unlink("../".DATA_DIR.'/'.$aid.'_exp_'.$expandable_banner);
						}
					}
					else
					{
						if(is_dir("../".DATA_DIR.'/html5/'.$aid.'/'))
						$this->remove_files("../".DATA_DIR.'/html5/'.$aid.'/');
									
						if($adtype ==2 && isset($rowdata['expandable']) && $rowdata['expandable'] ==1)
						{
						       if(is_dir("../".DATA_DIR.'/html5/'.$aid.'-exp/'))
						       $this->remove_files("../".DATA_DIR.'/html5/'.$aid.'-exp/');						
						}									
					}
					
					
				}	
				else if($adtype ==13)
				{
					unlink("../".DATA_DIR."/video/".$aid."/".$banner_name);
					
					rmdir("../".DATA_DIR.'/video/'.$aid.'/');
				}
				else if($adtype ==14)	
				{
					$filename_array=json_decode($banner_name,true);							
													
					foreach($filename_array as $rkey=>$rvalue)
					{
						unlink('../'.DATA_DIR.'/'.$aid.'/'.$rvalue);
					}
						
					rmdir('../'.DATA_DIR.'/'.$aid.'/');							
				}
				
			
				if(($ecommerce_enabled ==1 || $ecommerce_enabled ==0) && $adtype ==7)
				{
					unlink('../'.DATA_DIR."/ecommerce/logo/".$ecommercelogo);
	
					$row123=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($aid));
					while($row1234=$row123->fetch_assoc())
					{
						unlink('../'.DATA_DIR.'/ecommerce/'.$aid.'/'.$row1234['ad_image']);
					}
	
					rmdir('../'.DATA_DIR.'/ecommerce/'.$aid.'/');
					
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_list WHERE aid=?",array($aid));
				}
				
		
				$db->execute_query("delete from ".TABLE_PREFIX."ads where id=?",array($aid));
				$db->execute_query("delete from ".TABLE_PREFIX."ads_cache where id=?",array($aid));
				$db->execute_query("delete from ".TABLE_PREFIX."ad_geographic_mapping where aid=?",array($aid));
				$db->execute_query("delete from ".TABLE_PREFIX."ad_keyword_mapping where aid=?",array($aid));

				
				if($retargeting_enabled ==1 || $retargeting_enabled ==0)
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_retargeting_mapping WHERE aid=?",array($aid));			
				
			
				if($category_enabled ==1 || $category_enabled ==0)
				$db->execute_query("delete from ".TABLE_PREFIX."ad_category_mapping where aid=?",array($aid));
				
				
				if($language_enabled ==1 || $language_enabled ==0)
				$db->execute_query("delete from ".TABLE_PREFIX."ad_language_mapping where  uid=?",array($uid));
				
		
				if($device_enabled ==1 || $device_enabled ==0)
				{
					$db->execute_query("delete from ".TABLE_PREFIX."ad_os_mapping where  uid=?",array($uid));
					$db->execute_query("delete from ".TABLE_PREFIX."ad_browser_mapping where uid=?",array($uid));
					
				}
				
				if($isp_enabled ==1 || $isp_enabled ==0)
				{
					$db->execute_query("delete from ".TABLE_PREFIX."ad_isp_mapping where uid=?",array($uid));
					$db->execute_query("delete from ".TABLE_PREFIX."ad_connection_mapping where uid=?",array($uid));
					
				}

			
				if(($ecommerce_enabled ==1 || $ecommerce_enabled ==0) && $adtype ==7 && $ad_parent >0)
				{
					$ecommerce_id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ads WHERE ecommerce_parent=?",array($ad_parent));
		
					if(intval($ecommerce_id) ==0)
					{
						$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads WHERE id=?",array($ad_parent));
						$db->execute_query("DELETE FROM ".TABLE_PREFIX."ads_cache WHERE id=?",array($ad_parent));
					}
				}		
			}

			
			echo "Successfully deleted the draft ads";

		
			$cron_running_time=date('Y-m-d-H:i:s',time());
			
			fwrite($fp, $appendstring."UnLocked At : ".$cron_running_time."\n");
				
			fflush($fp); 	
			flock($fp, LOCK_UN);    // release the lock	
		}
		else
		{
			echo "<br/>Another cron is already running<br/>";
		}
		fclose($fp);	
	}
};
?>