<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

if(!class_exists('CategoryHelper'))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";

class CategoryController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		
		if($this->get_action()=="category_targeting")
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}
		else
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}
		
		if($this->get_addon_status('category-targeting_enabled') !=1)
		$this->flash($this->get_message('invalid'),BASE,0);
	}
	
		
		
	function add_action()
	{
		$db= DAL::get_instance();
	
		if($_POST)
		$pid=intval($this->read_post_param('pid'));
		else
		$pid=intval($this->read_page_param(1));
	
	
		if($pid >0 && !CategoryHelper::get_category_exists($pid))
		{
			$this->flash($this->get_message('category invalid'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}
		
	
		$parent_level=CategoryHelper::get_category_level($pid);
	
		$category="";
		$keyword='';
		$description='';		
		
		
		if($_POST)
		{
			$category=$this->read_post_param('category');
			$keyword=$this->read_post_param('keyword');
			$description=$this->read_post_param('description');			
			
			
			if($category =="")
			$this->set_notice("mandatory");
			else
			{
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=? and name=?",array($pid,$category));
				if($count >0)
				$this->set_notice("category exists");
				else
				{
					if($pid ==0)
					$parent_level1=0;
					else
					$parent_level1=$parent_level+1;
	
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."categories (name,pid,level,keyword,description) values (?,?,?,?,?)",array($category,$pid,$parent_level1,$keyword,$description));
	
					$this->flash($this->get_message('category added'), $this->make_url('dispatch/category_targeting/2/'.$pid,ADMIN_DIR));
					exit;
				}
			}
		}
		
		$this->set_variable("pid",$pid);
		$this->set_variable("category",$category);
		$this->set_variable("keyword",$keyword);
		$this->set_variable("description",$description);
	}
	
	function manage_action()
	{
		$pid=intval($this->read_page_param(1));
		$db= DAL::get_instance();
		
		if($pid >0 && !CategoryHelper::get_category_exists($pid))
		{
			$this->flash($this->get_message('category invalid'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}
	
		
		$res=$db->execute_query("select * from ".TABLE_PREFIX."categories where pid=? order by name",array($pid));
		$this->set_result("res",$res);
	
		$this->set_variable("categorypath",CategoryHelper::get_category_path($pid,"dispatch/category_targeting/2/"),0);
	}
	function edit_action()
	{
		$db= DAL::get_instance();
	
		if($_POST)
		$id=$this->read_post_param('id');
		else
		$id=$this->read_page_param(1);
	
		if(!CategoryHelper::get_category_exists($id))
		{
			$this->flash($this->get_message('category invalid'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}
	
	
		$pid=DAL::get_instance()->read_single_column("select pid from ".TABLE_PREFIX."categories where id=?",array($id));
		if($_POST)
		{
			$category=$this->read_post_param('category');
			$keyword=$this->read_post_param('keyword');
			$description=$this->read_post_param('description');			
	
			$this->set_variable("id",$id);
			$this->set_variable("category",$category);
			$this->set_variable("keyword",$keyword);
			$this->set_variable("description",$description);			
	
			if($category =="")
			{
				$this->set_notice("mandatory");
			}
			else
			{
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=? and name=? and id <> ?",array($pid,$category,$id));
				if($count >0)
				$this->set_notice("category exists");
				else
				{
					$res=$db->execute_query("update ".TABLE_PREFIX."categories set name=?,keyword=?,description=? where id=?",array($category,$keyword,$description,$id));
												
					$this->flash($this->get_message('category edited'), $this->make_url('dispatch/category_targeting/2/'.$pid,ADMIN_DIR));
					exit;
				}
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."categories where id=?",array($id));
			$row=$res->fetch_assoc();
	
			$this->set_variable("id",$row['id']);
			$this->set_variable("category",$row['name']);
			$this->set_variable("keyword",$row['keyword']);
			$this->set_variable("description",$row['description']);			
		}
		$this->set_variable("pid",$pid);
	}
	
	
	function delete_action()
	{
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);
		$this->set_variable('id',$id);

		if(!CategoryHelper::get_category_exists($id))
		{
			$this->flash($this->get_message('category invalid'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}
	
		$pid=CategoryHelper::get_category_pid($id);												
	
		$childcount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=?", array($id));
		$sitecount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where catid=?", array($id));

		if($childcount >0)
		{
			$this->flash($this->get_message('category child exists'),$this->make_url('dispatch/category_targeting/2/'.$pid,ADMIN_DIR),0);
			exit;
		}
		else if($sitecount >0)
		{
			$this->flash($this->get_message('category site exists'),$this->make_url('dispatch/category_targeting/2/'.$pid,ADMIN_DIR),0);
			exit;
		}
		else
		{
			$res=$db->execute_query("delete from ".TABLE_PREFIX."categories where id=?",array($id));
			
			$this->flash($this->get_message('category deleted'), $this->make_url('dispatch/category_targeting/2/'.$pid,ADMIN_DIR));
			exit;
		}
	}
	
	
	
	
	function category_targeting_html_action()
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
	
	
		$alert_msg='';
		if($_POST)
		{
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));
	
			$data1=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."categories");
			$count=0;
			while($data1row=$data1->fetch_assoc())
			{
				if(isset($_POST['ca'.$data1row['id']]) && $_POST['ca'.$data1row['id']]==1)
				{
					$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_category_mapping (uid,aid,catid) VALUES (?,?,?)",array(0,$aid,$data1row['id']));
					$count=1;
				}
			}
	
			$alert_msg=$this->get_message('successfully updated the category targeting');
		}
	
	
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=0 ORDER BY name");
		$this->set_result('row',$row);
	
		$oldcat_list='';
		$data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? AND catid <>0",array($aid));
		while($datarow=$data->fetch_assoc())
		{
			if($oldcat_list !='')
			$oldcat_list.='_';
	
			$oldcat_list.=$datarow['catid'];
		}
	
		$this->set_variable('oldcat_list',$oldcat_list);
		$this->set_variable('alert_msg',$alert_msg);
		$this->set_variable('aid',$aid);
	}
	
	
	
	
	
	function category_targeting_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
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
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));
			
			$data1=$db->execute_query("SELECT id FROM ".TABLE_PREFIX."categories");
			$count=0;
			while($data1row=$data1->fetch_assoc())
			{
				if(isset($_POST['ca'.$data1row['id']]) && $_POST['ca'.$data1row['id']]==1)
				{
					$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_category_mapping (uid,aid,catid) VALUES (?,?,?)",array($uid,$aid,$data1row['id']));
					$count=1;
				}
			}
			
			$alert_msg=$this->get_message('successfully updated the category targeting');
		}
		
				
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=0 ORDER BY name");
		$this->set_result('row',$row);
		
		$oldcat_list='';
		$data=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? AND catid <>0",array($aid));
		while($datarow=$data->fetch_assoc())
		{
			if($oldcat_list !='')
			$oldcat_list.='_';
			
			$oldcat_list.=$datarow['catid'];
		}
		
		$this->set_variable('oldcat_list',$oldcat_list);
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
	
	
	function category_targeting_admin_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
		$aid=$this->read_page_param(1);
		
		if(!$this->get_ad_validation_user($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_base_url('index/control_panel',ADMIN_DIR),0);
		}
		
		
		$catquery=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? AND catid <>0",array($aid));
		$this->set_result("catquery",$catquery);
		
		$catnumbers=$catquery->get_num_records();
		$this->set_variable("catnumbers",$catnumbers);
		
		
		
		$adname=$this->get_ad_name($aid);
		$this->set_variable('adname',$adname);
		
		
	}
	
	
	
	
	
	
	
	
	
};