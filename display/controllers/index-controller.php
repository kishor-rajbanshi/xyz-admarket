<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
class IndexController extends ApplicationController
{
	function index_action()
	{
		
		
	}
	
	function clear_cache_action()
	{
		$server=$this->read_page_param(1);
		if(md5($_SERVER['HTTP_HOST']) == $server)
		{
			$cachedir = CACHE_DIR.DS."cache/";
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
		}
		exit;
	}
	
	function update_config_action()
	{
		$server=$this->read_page_param(1);
	
		if(md5($_SERVER['HTTP_HOST']) == $server)
		{
			$db= DAL::get_instance();
			$configarraypath=PATH_TO_ROOT.CACHE_DIR.'/configuration/configuration.php';
			
			if(!is_dir(PATH_TO_ROOT.CACHE_DIR.'/configuration/'))
				mkdir(PATH_TO_ROOT.CACHE_DIR.'/configuration/',0777);
			
				unlink($configarraypath);
			
				$configstring=array();
					
					
				$mem_obj=$this->memcache_connect();
				$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."config");
				while($row=$res->fetch_assoc())
				{
					$configstring[$row['name']]=$row['value'];
			
					if($mem_obj !=false)
						$mem_obj->set($row['name'],$row['value'],MEMCACHE_EXPIRY);
				}
			
				$configstring=json_encode($configstring);
			
				$pluginarray=array();
				if(is_dir(ADDON_DIR_PATH))
				{
					$folders=array_diff(scandir(ADDON_DIR_PATH), array('..', '.'));
						
					foreach($folders as $folder)
					{
						if(is_dir(ADDON_DIR_PATH.$folder))
							$pluginarray[0][]=$folder;
					}
				}
			
				$addonstring='';
				foreach($pluginarray[0] as $pkey =>$pvalue)
				{
					if($addonstring !='')
						$addonstring.=',';
			
						if($this->get_addon_status($pvalue.'_enabled') ==1)
						{
							$addonstring.='"'.$pvalue."_enabled".'"=>1';
							if($mem_obj != false)
								$mem_obj->set($pvalue.'_enabled',1,MEMCACHE_EXPIRY);
									
						}
						else
						{
							$addonstring.='"'.$pvalue."_enabled".'"=>0';
							if($mem_obj != false)
								$mem_obj->set($pvalue.'_enabled',0,MEMCACHE_EXPIRY);
						}
				}
			
			
				$filecontent = fopen($configarraypath,'w+');
				flock($filecontent, LOCK_EX);
				fwrite($filecontent,"<?php \n \$configurationarray='");
				fwrite($filecontent,str_replace("'","\'",$configstring));
				fwrite($filecontent,"'; \n?>\n\n");
				flock($filecontent, LOCK_UN);
				fclose($filecontent);
		}
	
		exit;
	}
	
};
?>