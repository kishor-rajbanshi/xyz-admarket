<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class DeviceController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="device_targeting")
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}
		
		
		if($this->get_action()=="device_targeting_html" || $this->get_action()=="device_targeting_admin")
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}
		
		if($this->get_addon_status('device-targeting_enabled') !=1)
		$this->flash($this->get_message('invalid'),BASE,0);
	}
	
	
	function device_targeting_admin_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
		
		$aid=$this->read_page_param(1);
	
		if(!$this->get_ad_validation_user($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_base_url('index/control_panel',ADMIN_DIR),0);
		}
		
		$adname=$this->get_ad_name($aid);
		
		$this->set_variable('adname',$adname);
		$osenabled=Configuration::get_instance()->read('os-targeting_enabled');
		$browsrenabled=Configuration::get_instance()->read('browser-targeting_enabled');
		$devicetype=$db->read_single_column("SELECT device from ".TABLE_PREFIX."ads WHERE id=?",array($aid));
	
		if($browsrenabled ==1)
		{
			$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_browser_mapping WHERE aid=? AND browserid > 0",array($aid));
		
			$this->set_result("res_browser",$res);
			
		    $numbers=$res->get_num_records();
		    $this->set_variable("brnumbers",$numbers);
	    
		}
			
		if($osenabled ==1 )
		{
			$res1=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_os_mapping WHERE aid=? AND osid > 0",array($aid));
		
			$this->set_result("res_os",$res1);
			
		    $numbers1=$res1->get_num_records();
			$this->set_variable("osnumbers",$numbers1);
		}
		$this->set_variable('osenabled',$osenabled);
		$this->set_variable('browserenabled',$browsrenabled);
		
		
		$this->set_variable('device',intval($devicetype));
		
		
		
		
		
	}
	

	function device_targeting_marketplace_action()
	{
	    $this->disable_notice_area();
	    $db= DAL::get_instance();
	    
	    
	    $aid=$this->read_page_param(1);
	    
	    
	    $adname=$this->get_ad_name($aid);
	    
	    $this->set_variable('adname',$adname);
	    $osenabled=Configuration::get_instance()->read('os-targeting_enabled');
	    $browsrenabled=Configuration::get_instance()->read('browser-targeting_enabled');
	    $devicetype=$db->read_single_column("SELECT device from ".TABLE_PREFIX."ads WHERE id=?",array($aid));
	    
	    if($browsrenabled ==1 )
	    {
	        $res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_browser_mapping WHERE aid=? AND browserid > 0",array($aid));
	        
	        $this->set_result("res_browser",$res);
	        
	        $numbers=$res->get_num_records();
	        $this->set_variable("brnumbers",$numbers);
	        
	    }
	    
	    if($osenabled ==1)
	    {
	        $res1=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_os_mapping WHERE aid=? AND osid > 0",array($aid));
	        
	        $this->set_result("res_os",$res1);
	        
	        $numbers1=$res1->get_num_records();
	        $this->set_variable("osnumbers",$numbers1);
	    }
	    $this->set_variable('osenabled',$osenabled);
	    $this->set_variable('browserenabled',$browsrenabled);
	    
	    
	    $this->set_variable('device',intval($devicetype));
	    
	    
	    
	    
	    
	}
	
	
	function device_targeting_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$os_ids=array();
		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		$aid=$this->read_page_param(1);
	
		
		
		
		
		$os_enabled			= $this->get_addon_status('os-targeting_enabled');
		$browser_enabled	= $this->get_addon_status('browser-targeting_enabled');
		
		
		
		
		
		$this->set_variable('os_enabled', $os_enabled);
		$this->set_variable('browser_enabled', $browser_enabled);
		
		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_base_url('ad/list'),0);
		}
		
		$message="";
		$osarray=array();
		$os_desids=array();
		$os_mobids=array();
		$br_ids=array();
		$selected_br=array();
		$res_os1=$db->execute_query("select * from ".TABLE_PREFIX."os");
		while ($ros=$res_os1->fetch_assoc())
		{
			$res_browser1=$db->execute_query("select * from ".TABLE_PREFIX."browser where os".$ros['id']."=?",array(1));
			
			while ($rbr=$res_browser1->fetch_assoc())
			{
				$osarray['os_'.$ros['id']][]=$rbr['id'];
			}
			
		}
		if($_POST)
		{
			$newdevice=intval($this->read_post_param('device_type'));
			
			if($this->read_post_param('os_des_ids')!='')
			$os_desids=$this->read_post_param('os_des_ids');
			if($this->read_post_param('os_mob_ids')!='')
			$os_mobids=$this->read_post_param('os_mob_ids');
			if($this->read_post_param('br_ids')!='')
			$br_ids=$this->read_post_param('br_ids');
			
			//$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_device_mapping WHERE aid=?",array($aid));
			$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET device=? WHERE id=?",array($newdevice,$aid));
			
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_os_mapping WHERE aid=?",array($aid));
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_browser_mapping WHERE aid=?",array($aid));
			
			
			if($newdevice==0)
			{
				$os_ids=$os_desids;
				
			}
			else if($newdevice==1){
				$os_ids=$os_mobids;
			}
			else {
				$os_ids=array_merge($os_desids,$os_mobids);
			}
			
			
			$oscount=count($os_ids);
			
			  
			if($oscount >0 )
			{ 
				for ($i=0;$i<$oscount;$i++)
				{
					if(isset($osarray['os_'.$os_ids[$i]]))
					$selected_br=array_merge($selected_br,$osarray['os_'.$os_ids[$i]]);
					if($os_enabled == 1)
					$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_os_mapping (uid,aid,osid) VALUES (?,?,?)",array($uid,$aid,$os_ids[$i]));
					
				}
			}
			else 
			{
				if($newdevice==0)
					$os_res=$db->execute_query("select id from ".TABLE_PREFIX."os where desktop=1");
				elseif ($newdevice==1)
					$os_res=$db->execute_query("select id from ".TABLE_PREFIX."os where mobile=1");
				else 
					$os_res=$db->execute_query("select id from ".TABLE_PREFIX."os");
				
				while ($ros1=$os_res->fetch_assoc())
				{
					$res_br=$db->execute_query("select id from ".TABLE_PREFIX."browser where os".$ros1['id']."=?",array(1));
					
					while ($br=$res_br->fetch_assoc())
					{
						
						$selected_br[]=$br['id'];
					}
					
				}
			}
	
			if($browser_enabled ==1)
			{
				$brcount=count($br_ids);
				if(count($brcount))
				{
					for ($i=0;$i<$brcount;$i++)
					{
						if($os_enabled ==1)
						{
							if(!in_array($br_ids[$i], $selected_br))
								continue;
						}
					
						$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_browser_mapping (uid,aid,browserid) VALUES (?,?,?)",array($uid,$aid,$br_ids[$i]));
						
					}
				}
			}
			
			$message=$this->get_message('device target update success');
	
			$this->set_variable('olddevice',$newdevice);
			
		}
		else 
		{
			$olddevice=$db->read_single_column("SELECT device FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
			
 			
 			$this->set_variable('olddevice',$olddevice);
			
		}
		
		$res_osmap=$db->execute_query("select osid from ".TABLE_PREFIX."ad_os_mapping where aid=?",array($aid));
		if($res_osmap->get_num_records())
		{
			while ($rosmap=$res_osmap->fetch_assoc())
			{
				$osdb[]=$rosmap['osid'];
			}
		}
		else $osdb=array();
		$res_brmap=$db->execute_query("select browserid from ".TABLE_PREFIX."ad_browser_mapping where aid=?",array($aid));
		if ($res_brmap->get_num_records())
		{
		while ($rosmap=$res_brmap->fetch_assoc())
		{
			$brdb[]=$rosmap['browserid'];
		}
		}
		else $brdb=array();
		
				$this->set_variable('osdb',json_encode($osdb),0);
				$this->set_variable('brdb',json_encode($brdb),0);
				
		$osarrayjson=json_encode($osarray);
		$this->set_variable('osarrayjson',$osarrayjson,0);
		
		$res_os=$db->execute_query("select * from ".TABLE_PREFIX."os ORDER BY name");
		$res_browser=$db->execute_query("select * from ".TABLE_PREFIX."browser ORDER BY name");
		
		$this->set_result('res_os', $res_os);
		$this->set_result('res_browser', $res_browser);
		
		
		$this->set_variable('aid',$aid);
		$this->set_variable("message", $message);
		
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
		
		
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
		
		$this->set_variable('direction',$direction);
		
	}
	
	
	
	function device_targeting_html_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		$aid=$this->read_page_param(1);
		
		
		if(!$this->get_ad_validation_admin($aid,2))
		{
			$this->flash($this->get_message('invalid id'),$this->make_base_url('dispatch/html/manage',ADMIN_DIR),0);
			exit;
		}
		
		
		
		$message="";
		$os_ids=array();
		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		$aid=$this->read_page_param(1);
	
		$os_enabled=Configuration::get_instance()->read('os-targeting_enabled');
		$browser_enabled=Configuration::get_instance()->read('browser-targeting_enabled');
		$this->set_variable('os_enabled', $os_enabled);
		$this->set_variable('browser_enabled', $browser_enabled);
		
		
		
		$message="";
		$osarray=array();
		$os_desids=array();
		$os_mobids=array();
		$br_ids=array();
		$res_os1=$db->execute_query("select * from ".TABLE_PREFIX."os");
		while ($ros=$res_os1->fetch_assoc())
		{
			$res_browser1=$db->execute_query("select * from ".TABLE_PREFIX."browser where os".$ros['id']."=?",array(1));
			
			while ($rbr=$res_browser1->fetch_assoc())
			{
			$osarray['os_'.$ros['id']][]=$rbr['id'];
			}
			
		}
		if($_POST)
		{
			$newdevice=intval($this->read_post_param('device_type'));
			
			if($this->read_post_param('os_des_ids')!='')
			$os_desids=$this->read_post_param('os_des_ids');
			if($this->read_post_param('os_mob_ids')!='')
			$os_mobids=$this->read_post_param('os_mob_ids');
			if($this->read_post_param('br_ids')!='')
			$br_ids=$this->read_post_param('br_ids');
			
			//$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_device_mapping WHERE aid=?",array($aid));
			$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET device=? WHERE id=?",array($newdevice,$aid));
			
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_os_mapping WHERE aid=?",array($aid));
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_browser_mapping WHERE aid=?",array($aid));
			
			
			if($newdevice==0)
			{
				$os_ids=$os_desids;
				
			}
			else if($newdevice==1){
				$os_ids=$os_mobids;
			}
			else {
				$os_ids=array_merge($os_desids,$os_mobids);
			}
			
			$selected_br=array();
			$oscount=count($os_ids);
			if($oscount)
			{
				for ($i=0;$i<$oscount;$i++)
				{
					if(isset($osarray['os_'.$os_ids[$i]]))
					$selected_br=array_merge($selected_br,$osarray['os_'.$os_ids[$i]]);
					if($os_enabled ==1)
					$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_os_mapping (uid,aid,osid) VALUES (?,?,?)",array($uid,$aid,$os_ids[$i]));
					
				}
			}
			else 
			{
				if($newdevice==0)
					$os_res=$db->execute_query("select id from ".TABLE_PREFIX."os where desktop=1");
				elseif ($newdevice==1)
					$os_res=$db->execute_query("select id from ".TABLE_PREFIX."os where mobile=1");
				else 
					$os_res=$db->execute_query("select id from ".TABLE_PREFIX."os");
				
				while ($ros1=$os_res->fetch_assoc())
				{
					$res_br=$db->execute_query("select id from ".TABLE_PREFIX."browser where os".$ros1['id']."=?",array(1));
					
					while ($br=$res_br->fetch_assoc())
					{
						
						$selected_br[]=$br['id'];
					}
					
				}
			}
		
			if($browser_enabled ==1 )
			{
				$brcount=count($br_ids);
				if(count($brcount))
				{
					for ($i=0;$i<$brcount;$i++)
					{
						if($os_enabled==1)
						{
							if(!in_array($br_ids[$i], $selected_br))
								continue;
						}
					
						$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_browser_mapping (uid,aid,browserid) VALUES (?,?,?)",array($uid,$aid,$br_ids[$i]));
						
					}
				}
			}
			
			$message=$this->get_message('device target update success');
	
			$this->set_variable('olddevice',$newdevice);
			
		}
		else 
		{
			$olddevice=$db->read_single_column("SELECT device FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
			
 			
 			$this->set_variable('olddevice',$olddevice);
			
		}
		
		$res_osmap=$db->execute_query("select osid from ".TABLE_PREFIX."ad_os_mapping where aid=?",array($aid));
		if($res_osmap->get_num_records())
		{
			while ($rosmap=$res_osmap->fetch_assoc())
			{
				$osdb[]=$rosmap['osid'];
			}
		}
		else $osdb=array();
		$res_brmap=$db->execute_query("select browserid from ".TABLE_PREFIX."ad_browser_mapping where aid=?",array($aid));
		if ($res_brmap->get_num_records())
		{
		while ($rosmap=$res_brmap->fetch_assoc())
		{
			$brdb[]=$rosmap['browserid'];
		}
		}
		else $brdb=array();
		
				$this->set_variable('osdb',json_encode($osdb),0);
				$this->set_variable('brdb',json_encode($brdb),0);
				
		$osarrayjson=json_encode($osarray);
		$this->set_variable('osarrayjson',$osarrayjson,0);
		
		$res_os=$db->execute_query("select * from ".TABLE_PREFIX."os");
		$res_browser=$db->execute_query("select * from ".TABLE_PREFIX."browser");
		
		$this->set_result('res_os', $res_os);
		$this->set_result('res_browser', $res_browser);
		
		
		$this->set_variable('aid',$aid);
		$this->set_variable("message", $message);
		
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
		
		
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
		
		$this->set_variable('direction',$direction);
		$this->set_variable('aid',$aid);
		$this->set_variable("message", $message);
		
	}
	
};
