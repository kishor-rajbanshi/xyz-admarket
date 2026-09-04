<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";


class ThumbcronController extends ApplicationController
{
	function thumb_action()
	{
		$this->disable_notice_area();

		if(file_exists("../".CACHE_DIR."/cron/lock-thumb-data.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-thumb-data.txt");

			if(date('d',$file_creation_time) != date('d',time()))
			unlink("../".CACHE_DIR."/cron/lock-thumb-data.txt");
		}

		$db= DAL::get_instance();

        if(!is_dir("../".CACHE_DIR."/cron"))
       	mkdir("../".CACHE_DIR."/cron",0777);


		$fp = fopen("../".CACHE_DIR."/cron/lock-thumb-data.txt", "a+");

		if(flock($fp, LOCK_EX | LOCK_NB))
		{
			$cron_running_time=date('Y-m-d-H:i:s',time());

			$appendstring="Locked At : ".$cron_running_time." - Data Backup Cron == ";


			set_time_limit(0);

			$category_targeting_enabled = $this->get_addon_status('category-targeting_enabled');

			$wkhtmlpath	  	    = Configuration::get_instance()->read('wkhtmltoimage_path');

			if($category_targeting_enabled == 1 && $wkhtmlpath != '')
			{
				$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."sites WHERE thumbshot = 0 ORDER BY id DESC LIMIT 0,10");

				while($rowdata = $row->fetch_assoc())
				{
					$siteid   = $rowdata['id'];
					$protocol = $rowdata['protocol'];
					$url	  = $rowdata['url'];
					$siteurl  = $protocol.$url;



				    if(!is_callable('shell_exec') || stripos(ini_get('disable_functions'),'shell_exec'))
				    break;


				    if(!is_dir(PATH_TO_ROOT.DATA_DIR.'/site_logo/'))
	        		mkdir(PATH_TO_ROOT.DATA_DIR.'/site_logo/',0777);

				    if(!is_dir(PATH_TO_ROOT.DATA_DIR.'/site_logo/'.$siteid.'/'))
	        		mkdir(PATH_TO_ROOT.DATA_DIR.'/site_logo/'.$siteid.'/',0777);


			        $filename		= PATH_TO_ROOT.DATA_DIR.'/site_logo/'.$siteid.'/thumbshot.png';
			        $shellfilename	= getcwd().'/'.DATA_DIR.'/site_logo/'.$siteid.'/thumbshot.png';

			        $shellfilename	= str_replace("/".CRON_DIR,"",$shellfilename);


	                $filecontent	= $wkhtmlpath.' --height 1000 '.$siteurl.' '.$shellfilename;
	                shell_exec($filecontent.' 2>&1');

					$image=new ImageHelper($filename);
					$image->resize(240,200,$filename);


	                if(file_exists($filename))
	                $db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 1,thumbshot_image = ? WHERE id = ?",array('thumbshot.png',$siteid));
			else
	                $db->execute_query("UPDATE ".TABLE_PREFIX."sites SET thumbshot = 1 WHERE id = ?",array($siteid));
				}
			}


			echo "<br/>Cron run successfully<br/>";


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
