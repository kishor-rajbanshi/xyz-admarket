<?php

include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class LanguageController extends ApplicationController
{

	function before_execute()
	{
		parent::before_execute();
		
		if($this->get_action()=="language_targeting" )
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}
		else
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}
		
		if($this->get_addon_status('language-targeting_enabled') !=1)
		$this->flash($this->get_message('invalid'),BASE,0);
	}
	
	
	function language_targeting_action()
	{
		
		
		$this->disable_notice_area();
		$db= DAL::get_instance();
		$selectall=0;
		if($_POST)
			$aid=$this->read_post_param('aid');
		else
			$aid=$this->read_page_param(1);
	
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
	
		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_base_url('ad/list'),0);
		}
	
		$alert_msg='';
		if($_POST)
		{
			$time=time();
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_language_mapping WHERE aid=?",array($aid));
	
			$data1=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."language");
			$count=0;
			$selectall=$this->read_post_param('select_all');
			if($selectall==1)
				$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_language_mapping (uid,aid,language_id) VALUES (?,?,?)",array($uid,$aid,0));
			else
			{
			while($data1row=$data1->fetch_assoc())
			{
				
				if(isset($_POST['language'.$data1row['id']]) && $_POST['language'.$data1row['id']]==1)
				{
					$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_language_mapping (uid,aid,language_id) VALUES (?,?,?)",array($uid,$aid,$data1row['id']));
				
					$count=1;
				}
			}
			}
			$alert_msg=$this->get_message('successfully updated the language targeting');
		}
	
	
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."language  ORDER BY name");
		$this->set_result('row',$row);
	
		$oldlanguage_list='';
		$data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_language_mapping WHERE aid=? AND language_id <>0",array($aid));
		while($datarow=$data->fetch_assoc())
		{
			if($oldlanguage_list !='')
				$oldlanguage_list.='_';
	
			$oldlanguage_list.=$datarow['language_id'];
		}
		$selectcount=$db->read_single_column("select count(*) from ".TABLE_PREFIX."ad_language_mapping where aid=? and language_id=0",array($aid));
		if($selectcount)
			$selectall=1;
		$this->set_variable('select_all',$selectall);
		$this->set_variable('oldlanguage_list',$oldlanguage_list);
		$this->set_variable('alert_msg',$alert_msg);
		$this->set_variable('aid',$aid);
	
	
	
	
		if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
		else
			$localname=DEFAULT_LOCALE;
	
	
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
	
		$this->set_variable('direction',$direction);
	}
	
	

	function targeting_view_action()
	{
		
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
		$aid=$this->read_page_param(1);
		
		if(!$this->get_ad_validation_user($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_base_url('index/control_panel',ADMIN_DIR),0);
		}
		
		
		$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_language_mapping WHERE aid=? AND language_id > 0",array($aid));
	
		$this->set_result("res",$res);
		
	    $numbers=$res->get_num_records();
		$this->set_variable("numbers",$numbers);
		
		
		
		$adname=$this->get_ad_name($aid);
		$this->set_variable('adname',$adname);
		
		
		
	}

	function get_language_name($id)
	{
		$db= DAL::get_instance();
		if($id)
		{
			$name=$db->read_single_column("SELECT name FROM ".TABLE_PREFIX."language WHERE id=?",array($id));
		    if($name=='')
		    $name=$this->get_label('na');
		}
		else $name=$this->get_label('na');
		return $name;
	}
	
};