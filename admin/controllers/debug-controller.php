<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class DebugController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if(!(LoginHelper::validate_admin_login()))
		{
			$this->flash($this->get_message('login failed'), $this->make_url('index/index'),0);
		}
		
		if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==1)
		$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
	}

	function index_action()
	{
		$this->set_title($this->get_label('debug mode'));
		$db= DAL::get_instance();

		$addonarraypath=PATH_TO_ROOT.CACHE_DIR.'/configuration/configuration.php';
		$addonarray=array();
		$debugmode_values=array();
		$debug_config_val=Configuration::get_instance()->read('debugmode_values');
		if($debug_config_val!='')
		    $debugmode_values = json_decode($debug_config_val,true);
		$this->set_array('debugmode_values',$debugmode_values,array(),1);

		$all_options = 0;

		if(file_exists($addonarraypath))
			include($addonarraypath);

		$this->xyz_admarket_addon_include ( "addon.php", "" );


		$this->set_array('addonarray',$addonarray,array(),1);

		if($_POST){
			$debugmode_values = array();
			$debugmode_enable_exists = $db->read_single_column('SELECT name FROM '.TABLE_PREFIX.'config WHERE name="debugmode_enable"');

			if($debugmode_enable_exists == "")
				Configuration::get_instance()->insert('debugmode_enable',$this->read_post_param('debugmode_enable'));
			else
				Configuration::get_instance()->update('debugmode_enable',$this->read_post_param('debugmode_enable'));

			if($this->read_post_param('debugmode_enable') == 1){

				foreach($_POST as $key => $value){
					if($key != 'debugmode_enable' && $key != 'all_options' && $key != 'submit'){
						$debugmode_values[$key] = $value;
					}
				}

				$this->set_array('debugmode_values',$debugmode_values,array(),1);

				$debugmode_values = json_encode($debugmode_values);

				$debugmode_values_exists = $db->read_single_column('SELECT name FROM '.TABLE_PREFIX.'config WHERE name="debugmode_values"');

				if($debugmode_values_exists == "")
					Configuration::get_instance()->insert('debugmode_values',$debugmode_values);
				else
					Configuration::get_instance()->update('debugmode_values',$debugmode_values);
			}
			else{
				Configuration::get_instance()->update('debugmode_values',"");
				$this->set_array('debugmode_values',$debugmode_values,array(),1);
			}

			if(method_exists($this, 'get_config_updation'))
				$this->get_config_updation();

			$all_options = $this->read_post_param('all_options');

			$this->set_variable('all_options',$all_options);

		}
	}

	function xyz_admarket_addon_include($filename,$folder_name)
	{
		$xyz_addons_dir_path=ADDON_DIR_PATH;
		if(is_dir($xyz_addons_dir_path))
		{
	
			$d = dir($xyz_addons_dir_path);
				
			if($d)
			{
				while($entry = $d->read())
				{
					if($entry=="." || $entry=="..")
						continue;
						if($folder_name=="" || $folder_name==$entry)
						{
							if (file_exists($xyz_addons_dir_path.$entry."/".$filename)) {
								if($filename=="addon.php")
								{
									require_once($xyz_addons_dir_path.$entry."/".$filename);
								}
									
							}
						}
				}
				$d->close();
			}
		}
		
	}
}