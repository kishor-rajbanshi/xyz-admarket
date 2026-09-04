<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class OptimizecronController extends ApplicationController
{
	function optimize_action()
	{
		$this->disable_notice_area();
		
		$db= DAL::get_instance();
		if(Configuration::get_instance()->read("product_version") !== PRODUCT_VERSION)
		{
			die();
		}
	
	
		if(file_exists("../".CACHE_DIR."/cron/lock-optimize.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-optimize.txt");
			

			if(date('d',$file_creation_time) != date('d',time()))
			unlink("../".CACHE_DIR."/cron/lock-optimize.txt");
		}

		
        if(!is_dir("../".CACHE_DIR."/cron"))
       	mkdir("../".CACHE_DIR."/cron",0777);		
		
		$current_time =date("Y",time());
		$current_time.=date("m",time());
		$current_time.=date("d",time());
		$current_time.=date("H",time());			
		


		$db= DAL::get_instance();
		$success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status =? WHERE task=?",array(0,'optimizecron_success_time'));
		$fp = fopen("../".CACHE_DIR."/cron/lock-optimize.txt", "a+"); 

		if(flock($fp, LOCK_EX | LOCK_NB))
		{
			$cron_running_time=date('Y-m-d-H:i:s',time());

			$appendstring="Locked At : ".$cron_running_time." - Data Backup Cron == ";
			/************************** TABLE OPTIMIZATION START****************************/

			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."ads");
			
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."ad_device_mapping");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."ad_geographic_mapping");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."ad_keyword_mapping");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."cpm_ad_mapping");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."geo_statistics_adv_daily");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."geo_statistics_pub_daily");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."dailyclicks");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."dailyclicks_backup");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."adv_impression_hourly_backup");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."pub_impression_hourly_backup");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."statistics_pub_daily");
			$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."adunit");
			
			if($this->get_addon_status('referral_enabled'))
			{
				$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."referral_hourly_backup");
				$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."referral_statistics_daily");
				$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."referral_visits_daily");
			}
			
			if($this->get_addon_status('category-targeting_enabled'))
			{
				$db->execute_query("OPTIMIZE TABLE ".TABLE_PREFIX."ad_category_mapping");
			}



			/************************** TABLE OPTIMIZATION END*****************************/
			$success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status =? WHERE task=?",array($current_time,2,'optimizecron_success_time'));
			
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
		
		echo "<br/>".$appendstring .$cron_running_time."</br>";
	}
};