<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class AddonController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		
		$pagecontent=$this->get_page_params();
		$pcontent='';
		$pvalue=0;
		
		if(isset($pagecontent[0]))
		$pcontent=$pagecontent[0];

		if(isset($pagecontent[1]))
		$pvalue=$pagecontent[1];
		
		
		//if(($pcontent == 'sponsored' && ($pvalue !=24 && $pvalue !=25)) || ($pcontent == 'affiliate-ads' && $pvalue !=1))  //24=>marketplace		//25=>sitedetails //1=>Affiliate marketplace
		
		
		
		if(!($pcontent == 'sponsored' && $pvalue ==24) && !($pcontent == 'sponsored' && $pvalue ==25) && !($pcontent == 'retargeting' && $pvalue ==2) && !($pcontent == 'referral' && $pvalue ==5) && !($pcontent == 'affiliate-ads' && $pvalue ==1))  //24=>marketplace //25=>sitedetails //1=>Affiliate marketplace
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}
	}
	
	
	
	
	function dispatch_action()
	{
		$page=$this->get_page();
		
		$pagearray = explode("/",$page);
		$pagedata =array_slice($pagearray, 3);
		
		$pagedata =implode("/",$pagedata);
		
		if($this->get_addon_status($pagearray[2].'_enabled') !=1)
		$this->flash($this->get_message('invalid'), $this->make_url('index/index'),0);
		
		include(ADDON_DIR_PATH.$pagearray[2].'/dispatcher.php');
	}
	
};
?>