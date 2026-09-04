<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class KeywordController extends ApplicationController
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
	
	
	function list_action()
	{
		$this->set_title($this->get_label('manage keywords'));
		$db= DAL::get_instance();
	
	
		if($_POST)
		$status=$this->read_post_param('status');
		else
		{
		    $status=$this->read_page_param(1);
			
			$exp=explode("-",$status);
			if($exp[0]=="page")
			$status=2;
		}
	
		if($status =="")
		$status=2;
	
	
	
		if($status ==2)
			$status_str="";
		else
			$status_str=" where status='".$status."' ";
	

		$check1= "select id,keyword,status from ".TABLE_PREFIX."keywords ".$status_str." ORDER BY id desc";
		$pagination = new Pagination($check1);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);
	
		$this->set_variable("status", $status);
	
	
	}
	
	function change_status_action()
	{
	
		$kid=$this->read_page_param(1);
		
		if(DEMO_MODE && $kid <=10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('keyword/list'),0);
			exit;
		}
		
		
		
		$action=$this->read_page_param(2);
		$status=$this->read_page_param(3);
		$pg=$this->read_page_param(4);
		
		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
		$pg="";
		
		if($pg=="")
		$pg="page-1";
		
		
	
		$db= DAL::get_instance();
	
		$res=$db->execute_query("update ".TABLE_PREFIX."keywords set status=? where id=?",array($action,$kid));
	
	
		$this->flash($this->get_message('keyword status updated'), $this->make_url('keyword/list/'.$status.'/'.$pg));
		exit;
	
	}
	
	function delete_action()
	{
		$kid=$this->read_page_param(1);
		
		
		if(DEMO_MODE && $kid <=10)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('keyword/list'),0);
			exit;
		}
		
		
		
		
		$status=$this->read_page_param(2);
		$pg=$this->read_page_param(3);
		
		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
			$pg="";
		
		if($pg=="")
			$pg="page-1";
		
		$db= DAL::get_instance();
		$sql1="delete from ".TABLE_PREFIX."keywords where id=?";
		$res=$db->execute_query($sql1,array($kid));
		$sql="delete from ".TABLE_PREFIX."ad_keyword_mapping where kid=?";
		$res1=$db->execute_query($sql,array($kid));
		$this->flash($this->get_message('keyword deleted'), $this->make_url('keyword/list/'.$status.'/'.$pg));
		exit;
	}	
	
};