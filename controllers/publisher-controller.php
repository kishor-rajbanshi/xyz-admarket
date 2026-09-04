<?php 
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

if(file_exists(ADDON_DIR_PATH."withdrawal".DS."common".DS."helpers".DS."withdrawal-helper.php"))
include_once ADDON_DIR_PATH."withdrawal".DS."common".DS."helpers".DS."withdrawal-helper.php";

class PublisherController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="statistics" || $this->get_action()=="add_site_filter" || $this->get_action()=="view_site_filters" || $this->get_action()=="delete_site_filter" || $this->get_action()=="update_site_filter" || $this->get_action()=="country")
		{
			if(!(LoginHelper::validate_user_login()))
			{
				$this->flash($this->get_message('login failed'), BASE,0);
			}
		}
	}
	
  
   
	function statistics_action()
	{
		$this->set_title($this->get_label('pub overall statistics'));
		
	
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		$db= DAL::get_instance();
		
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$tab=$this->read_post_param("tab");
		}
		else
		{
			$duration=1;
			$tab=1;
		}
		if($_POST)
		{
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$from_date='';
			$to_date='';
		}
		
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		if($duration=="" || $duration==0)
			$duration=1;
		
		
		if($tab=="" || $tab==0)
			$tab=1;
		
	
		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
		$this->set_variable("tab",$tab);
		
	
	}
	
	function add_site_filter_action()
	{
		$db = DAL::get_instance();
		$site = $this->read_post_param('site');
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		$site1 = substr($site,0,7);
		$error=0;
		if($site1 == "http://")
		{
			$site = str_replace($site1,"",$site);
		}
		$site2 = substr($site,0,8);
		if($site2 == "https://")
		{
			$site = str_replace($site2,"",$site);
		}
		
		$site3 = substr($site,0,4);
		
		if($site3 == "www.")
		{
			$site = str_replace($site3,"",$site);
		}
		
		if($site == "")
		{
			$error=1;
			$error_code=1;	
			
		}
		else if(! UtilityHelper::is_valid_domain($site))
		{
			$error=1;
			$error_code=2;
		}
		else
		{
			$html_content='';
			$site_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array ($uid));
			if($site_json != '')
				$site_array = json_decode($site_json);
			else
				$site_array = array ();
			
			$check = in_array($site,$site_array);
			if($check != 1)
			{
				array_push($site_array,$site); 
				$value = $db->execute_query("UPDATE " . TABLE_PREFIX . "users SET restricted_sites=? WHERE id=?",array (json_encode($site_array),$uid));
				if($value->error == "")
				{
					$mem_obj=$this->memcache_connect();
					if($mem_obj != false)
						$mem_obj->get('pub_restricted_sites_'.$uid);
					if($mem_obj != false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
					{
						$mem_obj->set('pub_restricted_sites_'.$uid,json_encode($site_array),MEMCACHE_EXPIRY);
					}
				}
				else if($value->error != "")
				{  
					$error=1;
					$error_code=3;
				}
			}
			else
			{
				$error=1;
				$error_code=4;
			}
		}
		
		if($error == 1)
		{
			$res_array=array("error"=>$error,"error_code"=>$error_code);
			echo json_encode($res_array);exit;
		}
		else 
		{
			$this->set_array("sites", $site_array,array(),1);
		}	
		
	}
	
	
	function view_site_filters_action()
	{
		$this->set_title($this->get_label('manage restricted sites'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();
		$sites_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array($uid));
		$sites_array=json_decode($sites_json);
		if(count($sites_array) > 0)
		$this->set_array("sites", $sites_array,array(),1);
	
	}
	

	function country_action()
	{
		$db= DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		$duration=$this->read_page_param(1);
		$from_date=$this->read_page_param(2);
		$to_date=$this->read_page_param(3);
		
		$from_date=str_replace('-','/',$from_date);
 		$to_date=str_replace('-','/',$to_date);
		
		
		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);
		
		
		if($from_date !='')  // for custom date range
		$results=$this->get_range_top_country_publishers($from_date,$to_date,$uid);
		else
		$results=$this->get_top_country_publishers($duration,$uid);
			
		$this->set_array("results",$results,array(0,1));
		
		
	}
	
	function update_site_filter_action()
	{
		$db= DAL::get_instance();
		$mem_obj=$this->memcache_connect();
		$sid=$this->read_post_param('index');
		$site=$this->read_post_param('new_value');
		$site_old=$this->read_post_param('old_value');
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		if($mem_obj!= false)
		{      
			$pub_det_array = $mem_obj->get('pub_restricted_sites_'.$uid);	
		
		}
		$sites_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array($uid));
		$sites_array=json_decode($sites_json);
		
		$check = in_array($site,$sites_array);
		if(!UtilityHelper::is_valid_domain($site))
		{      
			$res_array=array("success"=>0,"error_code"=>1);
		}
		else if($check == 1)
		{     
			$res_array=array("success"=>0,"error_code"=>3);
		}
		else if($sites_array[$sid]==$site_old && $check!=1)
		{      
			$sites_array[$sid]=$site;
			$res=$db->execute_query("UPDATE " . TABLE_PREFIX . "users SET  restricted_sites=? WHERE id=?",array(json_encode($sites_array),$uid));
			if($res->error=='')
			{ 
				if($mem_obj != false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
				{
					$mem_obj->set('pub_restricted_sites_'.$uid,json_encode($sites_array),MEMCACHE_EXPIRY);
				}
				$res_array=array("success"=>1);
			}
			
		}
		else 
		{
			$res_array=array("success"=>0,"error_code"=>2);
		}
		echo json_encode($res_array);
		exit;
		
	}
	
	function delete_site_filter_action()
	{      
		$db= DAL::get_instance();
		$sid=$this->read_post_param('index');
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$sites_json = $db->read_single_column("select restricted_sites from " . TABLE_PREFIX . "users where id=?",array($uid));
		$sites_array=json_decode($sites_json);  
		unset($sites_array[$sid]);
		$site_new=array();
		
		foreach($sites_array as $value)
		{     
			$site_new[]=$value;
		}
		
		$del=$db->execute_query("UPDATE " . TABLE_PREFIX . "users SET  restricted_sites=? WHERE id=?",array(json_encode($site_new),$uid));
		if($del->get_error()=='')
		{
			$mem_obj=$this->memcache_connect();
			if($mem_obj != false)
				$mem_obj->get('pub_restricted_sites_'.$uid);
			if($mem_obj != false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
			{
				$mem_obj->set('pub_restricted_sites_'.$uid,json_encode($site_new),MEMCACHE_EXPIRY);
			}
			$success=1;
		}
		else 
			$success=0;
		$res_array=array("success"=>$success,"id"=>$sid);
		echo json_encode($res_array);
		exit;
	}
	

	
	
};