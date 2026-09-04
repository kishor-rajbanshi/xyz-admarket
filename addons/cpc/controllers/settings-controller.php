<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class SettingsController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if(!(LoginHelper::validate_admin_login()))
		{
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}
		
	}
	
	
	function index_action()
	{
		
		$this->set_title($this->get_label('cpc activation'));
		$db= DAL::get_instance();
		
		$operation=intval($this->read_page_param(1));
		$currentstatus=$this->get_addon_status('cpc_enabled');
		if($currentstatus ==-1 && $operation ==1)
		{
			$db->execute_query('INSERT INTO '.TABLE_PREFIX.'config (`name`,`value`) VALUES ("cpc_enabled",1)');
			
			$priority=Configuration::get_instance()->read('ad_display_priority');

			if($priority =="")
			Configuration::get_instance()->update('ad_display_priority','ppc');
			else
			Configuration::get_instance()->update('ad_display_priority',Configuration::get_instance()->read('ad_display_priority').'_ppc');
		}
		else if($currentstatus ==0 && $operation ==1)
		{
			Configuration::get_instance()->update('cpc_enabled',1);
			
			$priority=Configuration::get_instance()->read('ad_display_priority');

			if($priority =="")
			Configuration::get_instance()->update('ad_display_priority','ppc');
			else
			Configuration::get_instance()->update('ad_display_priority',Configuration::get_instance()->read('ad_display_priority').'_ppc');
		}
		else 
		{
			Configuration::get_instance()->update('cpc_enabled',0);
			$priority_array=explode('_',Configuration::get_instance()->read('ad_display_priority'));
			
			foreach($priority_array as $k=>$v)
			{
				if($v == 'ppc')
				{
					unset($priority_array[$k]);
				}
			}
			
			Configuration::get_instance()->update('ad_display_priority',implode('_',$priority_array));
		}
			
		
		if($operation ==1)
		{
		 	$ppc_profit_percentage_flag=0;
		 	$ppc_minrate_flag=0;
		 	$colums = $db->execute_query("SHOW COLUMNS FROM ".TABLE_PREFIX."users");
		 	
		 	while($row = $colums->fetch_array())
		 	{
		 		if($row['Field']=="ppc_profit_percentage")
		 		$ppc_profit_percentage_flag=1;
		 		
		 		if($row['Field']=="ppc_minrate")
		 		$ppc_minrate_flag=1;
		 	}
		 		
	 		if($ppc_profit_percentage_flag ==0)
		 	$db->execute_query("ALTER TABLE ".TABLE_PREFIX."users ADD `ppc_profit_percentage` float NOT NULL default '0'");			
			
		 	if($ppc_minrate_flag ==0)
		 	$db->execute_query("ALTER TABLE ".TABLE_PREFIX."users ADD `ppc_minrate` float NOT NULL default '0'");
		 	    
		 	$tmp=$db->read_single_column("select id from ".TABLE_PREFIX."config where name=?", array('ppc_impression_tracking_interval'));
		 	if($tmp == "")
		 	$db->execute_query("INSERT INTO `".TABLE_PREFIX."config` (`id`, `name`, `value`) VALUES ('', 'ppc_impression_tracking_interval',1)");

		 	
		 	$tmp=$db->read_single_column("select id from ".TABLE_PREFIX."config where name=?", array('min_click_value'));
		 	if($tmp == "")
		 	$db->execute_query("INSERT INTO `".TABLE_PREFIX."config` (`id`, `name`, `value`) VALUES ('', 'min_click_value','0.5')");		 	
		 	
		 	
		 	$tmp=$db->read_single_column("select id from ".TABLE_PREFIX."config where name=?", array('min_budget'));
		 	if($tmp == "")
		 	$db->execute_query("INSERT INTO `".TABLE_PREFIX."config` (`id`, `name`, `value`) VALUES ('', 'min_budget','10')");			 	
		 	
		 	
		 	$tmp=$db->read_single_column("select id from ".TABLE_PREFIX."config where name=?", array('profit_percentage'));
		 	if($tmp == "")
		 	$db->execute_query("INSERT INTO `".TABLE_PREFIX."config` (`id`, `name`, `value`) VALUES ('', 'profit_percentage','50')");		
		 	
		 	$tmp=$db->read_single_column("select id from ".TABLE_PREFIX."config where name=?", array('min_cpc_total_budget'));
		 	if($tmp == "")
		 	$db->execute_query("INSERT INTO `".TABLE_PREFIX."config` (`id`, `name`, `value`) VALUES ('', 'min_cpc_total_budget','25')");
		 	
		 	$tmp=$db->read_single_column("select id from ".TABLE_PREFIX."config where name=?", array('min_cpc_daily_budget'));
		 	if($tmp == "")
		 	$db->execute_query("INSERT INTO `".TABLE_PREFIX."config` (`id`, `name`, `value`) VALUES ('', 'min_cpc_daily_budget','5')");
		 		
		}
		
		
		
		$this->update_site_targeting();
		

		
		if(method_exists($this, 'get_config_updation'))
		$this->get_config_updation();
		

		
		
		if($operation ==1)
		$this->flash($this->get_message('cpc addon status successfully updated'), $this->make_base_url('addon/settings/cpc',ADMIN_DIR));
		else 
		$this->flash($this->get_message('cpc addon status successfully updated'), $this->make_base_url('addon/manage',ADMIN_DIR));
			
		exit;
	}
	
	function configure_action()
	{
		$this->set_title($this->get_label('addon settings type',array('x'=>strtoupper('cpc'))));
		
        $profit_percentage=Configuration::get_instance()->read('profit_percentage');
        $click_value=Configuration::get_instance()->read('min_click_value');
        $min_cpc_total_budget=Configuration::get_instance()->read('min_cpc_total_budget');	
        $ppc_impression_tracking_interval=Configuration::get_instance()->read('ppc_impression_tracking_interval');
        $min_cpc_daily_budget=Configuration::get_instance()->read('min_cpc_daily_budget');
		if($_POST)
		{
			if(DEMO_MODE)
			{
				$this->flash($this->get_message('demo mode'), $this->make_base_url('addon/settings/cpc',ADMIN_DIR),0);
				exit;
			}
		
       		$profit_percentage=$this->read_post_param('profit_percentage');
        	$click_value=$this->read_post_param('click_value');
        	$min_cpc_total_budget=$this->read_post_param('budget');	
        	$ppc_impression_tracking_interval=$this->read_post_param('ppc_impression_tracking_interval');
        	$min_cpc_daily_budget=$this->read_post_param('min_cpc_daily_budget');                         // CPC min daily ad budget
        	
        	if($profit_percentage=="" || $click_value=="" || $min_cpc_total_budget=="" || $min_cpc_daily_budget=="")
        	{
        		$this->set_notice("mandatory");
        	}			
	        else if(!is_numeric($profit_percentage) || !is_numeric($click_value) || !is_numeric($min_cpc_total_budget))
        	{
        		$this->set_notice("positive value");
        	}
        	else if(!UtilityHelper::isPositive($profit_percentage) || !UtilityHelper::isPositive($click_value) || !UtilityHelper::isPositive($min_cpc_total_budget))
        	{
        		$this->set_notice("positive value");
        	}
        	else if($min_cpc_total_budget < $click_value)
        	{  
        		$this->set_notice("min cpc budget not less than click value");
        	}
        	else if($min_cpc_daily_budget < $click_value)
        	{
        		$this->set_notice("min cpc daily budget not less than click value");
        	}
			else
			{
        		Configuration::get_instance()->update('profit_percentage',$profit_percentage);
        		Configuration::get_instance()->update('min_click_value',$click_value);
        		Configuration::get_instance()->update('min_cpc_total_budget',$min_cpc_total_budget);		
        		Configuration::get_instance()->update('min_cpc_daily_budget',$min_cpc_daily_budget);
        		Configuration::get_instance()->update('ppc_impression_tracking_interval',$ppc_impression_tracking_interval);
        		
			
				if(method_exists($this, 'get_config_updation'))
				$this->get_config_updation();
					
				$this->flash($this->get_message('cpc settings update success'), $this->make_base_url('addon/settings/cpc',ADMIN_DIR));
				exit;
			}
		}
		
        $this->set_variable("profit_percentage",$profit_percentage);
        $this->set_variable("click_value",$click_value);
        $this->set_variable("budget",$min_cpc_total_budget);		
        $this->set_variable("ppc_impression_tracking_interval",$ppc_impression_tracking_interval);
        $this->set_variable("min_cpc_daily_budget",$min_cpc_daily_budget);
	}
};
?>
