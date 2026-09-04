<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class CpmController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();

			if(!(LoginHelper::validate_user_login()))
				$this->flash($this->get_message('login failed'), BASE,0);
		
		if($this->get_addon_status('cpm_enabled') !=1)
		$this->flash($this->get_message('invalid'),BASE,0);
	}
	
	
	function top_list_action()
	{
		$this->disable_notice_area();
		$duration=intval($this->read_page_param(1));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		if($duration ==0)
		$duration=3;
		
		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
	}
		
};