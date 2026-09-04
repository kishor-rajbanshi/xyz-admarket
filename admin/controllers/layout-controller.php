<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";

class LayoutController extends ApplicationController
{
	function header_action()
	{
		$pageid=$this->read_page_param(1);
		$subid=$this->read_page_param(2);
		
		if($pageid=="" || $pageid <=0)
		$pageid=0;
		
		
		if(!LoginHelper::validate_admin_login())
		{
			header("Location: ".$this->make_url("index/index"));
			exit;
		}
		
		$this->set_variable("pageid",$pageid);
		$this->set_variable("subid",$subid);
		
		$pluginarray=array();
		if(is_dir(ADDON_DIR_PATH))
		{
					$folders=array_diff(scandir(ADDON_DIR_PATH), array('..', '.'));
			
			foreach($folders as $folder)
			{
				if(is_dir(ADDON_DIR_PATH.$folder))
				$pluginarray[0][]=$folder;
			}
			$this->set_variable('addoncount',count($pluginarray[0]));
		}
		
		$this->set_array('pluginarray',$pluginarray);
		
		
	}
	function footer_action()
	{
		
	}
	
};
?>