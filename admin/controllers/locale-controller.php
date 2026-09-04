<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class LocaleController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if(!(LoginHelper::validate_admin_login()))
		{
			$this->flash($this->get_message('login failed'), $this->make_url('index/index'),0);
		}		
	}
	
	function status_action()
	{
		$db= DAL::get_instance();
		$id=intval($this->read_page_param(1));
		$status=intval($this->read_page_param(2));
		

		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}


		if(!$this->get_locale_exists($id))
		{
			$this->flash($this->get_message('invalid id'),$this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}
		
		
		if($status >1)
		$status=0;
		
		$db->execute_query("UPDATE ".TABLE_PREFIX."locale SET status=? WHERE id=?",array($status,$id));
		
		
		if($status ==0)
		{
			$default_local_id=$db->read_single_column("select id from ".TABLE_PREFIX."locale where name=?",array(DEFAULT_LOCALE));
			$db->execute_query("update ".TABLE_PREFIX."users set locale=? where locale=?",array($default_local_id,$id));
		}
		
		$this->flash($this->get_message('locale status updated'),$this->make_base_url('locale/manage',ADMIN_DIR));
		exit;
	}
	
	
	function add_action()
	{
		$this->set_title($this->get_label('add locale'));
		
		$name="";
		$desc="";
		$direction=0;
		
		if($_POST)
		{


		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}





			$name=$this->read_post_param('name');
			$desc=$this->read_post_param('desc');
			$direction=$this->read_post_param('direction');
	
			if($name=="" || $desc=="")
			{
				$this->set_notice("mandatory");
			}
			else
			{
				$db= DAL::get_instance();
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."locale where name=?",array($name));
				if($count >0)
				{
					$this->set_notice("locale exists");
				}
				else
				{
					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."locale (`name`,`description`,`direction`) values (?,?,?)",array($name,$desc,$direction));
					$languageid=$res->get_last_id();
					
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."meta ADD (`".$languageid."_title` VARCHAR(255) NOT NULL default '',`".$languageid."_keyword` LONGTEXT NOT NULL default '',`".$languageid."_description` LONGTEXT NOT NULL default '')");
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."email_templates ADD (`".$languageid."_subject` VARCHAR(255) NOT NULL default '',`".$languageid."_message` LONGTEXT NOT NULL default '')");
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."aboutus ADD (`".$languageid."_description` LONGTEXT NOT NULL default '')");
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."terms ADD (`".$languageid."_description` LONGTEXT NOT NULL default '')");
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."testimonial ADD (`".$languageid."_description` LONGTEXT NOT NULL default '')");
					
					
					
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."custom_pages ADD (`".$languageid."_name` VARCHAR(255) NOT NULL default '',`".$languageid."_content` LONGTEXT NOT NULL default '',`".$languageid."_title` VARCHAR(255) NOT NULL default '',`".$languageid."_meta_keyword` LONGTEXT NOT NULL default '',`".$languageid."_meta_description` LONGTEXT NOT NULL default '')");
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."custom_banners ADD (`".$languageid."_title` VARCHAR(255) NOT NULL default '',`".$languageid."_content` LONGTEXT NOT NULL default '')");
					$db->execute_query("ALTER TABLE ".TABLE_PREFIX."notifications ADD (`".$languageid."_message` VARCHAR(255) NOT NULL default '')");
					
										
					$this->flash($this->get_message('locale added'),$this->make_base_url('locale/manage',ADMIN_DIR));
					exit;
				}
			}
		}
		$this->set_variable("name",$name);
		$this->set_variable("desc",$desc);
		$this->set_variable("direction",$direction);
		
		
	}
	
	function manage_action()
	{
		$this->set_title($this->get_label('manage locale'));
	
		$db= DAL::get_instance();
	
	
		$res=$db->execute_query("select * from ".TABLE_PREFIX."locale order by id desc");
		$this->set_result("res",$res);
	}
	
	
	function edit_action()
	{
		$this->set_title($this->get_label('edit locale'));
	
		$db= DAL::get_instance();


		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}




	
		if($_POST)
		$id=intval($this->read_post_param('id'));
		else
		$id=intval($this->read_page_param(1));
	
	
		
	
		if(!$this->get_locale_exists($id))
		{
			$this->flash($this->get_message('invalid id'),$this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}
	
	
		$name=$db->read_single_column("select name from ".TABLE_PREFIX."locale where id=?", array($id));
		if($name == DEFAULT_LOCALE)
		{
			$this->flash($this->get_message('locale default manipulation'),$this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}
	
	
		if($_POST)
		{
			$name=$this->read_post_param('name');
			$desc=$this->read_post_param('desc');
			$direction=$this->read_post_param('direction');
	
			if($id==0 || $name=="" || $desc=="")
			{
				$this->set_notice("mandatory");
			}
			else
			{
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."locale where name=? and id <> ?",array($name,$id));
				if($count >0)
				{
					$this->set_notice("locale exists");
				}
				else
				{
					$res=$db->execute_query("update ".TABLE_PREFIX."locale set name=?,description=?,direction=? where id=?",array($name,$desc,$direction,$id));
	
					$this->flash($this->get_message('locale edited'),$this->make_base_url('locale/manage',ADMIN_DIR));
					exit;
				}
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."locale where id=?",array($id));
			$row=$res->fetch_assoc();
	
			$id=$row['id'];
			$name=$row['name'];
			$desc=$row['description'];
			$direction=$row['direction'];
		}
	
		$this->set_variable("id",$id);
		$this->set_variable("name",$name);
		$this->set_variable("desc",$desc);
		$this->set_variable("direction",$direction);
		
	}
	
	function delete_action()
	{
		$db= DAL::get_instance();
	
		$id=intval($this->read_page_param(1));



		if(DEMO_MODE)
		{
			$this->flash($this->get_message('demo mode'), $this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}


	
		if(!$this->get_locale_exists($id))
		{
			$this->flash($this->get_message('invalid id'),$this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}
	
		$name=$db->read_single_column("select name from ".TABLE_PREFIX."locale where id=?",array($id));
		if($name == DEFAULT_LOCALE)
		{
			$this->flash($this->get_message('locale default manipulation'),$this->make_base_url('locale/manage',ADMIN_DIR),0);
			exit;
		}
		else
		{
			$res=$db->execute_query("delete from ".TABLE_PREFIX."locale where id=?",array($id));
			$default_local_id=$db->read_single_column("select id from ".TABLE_PREFIX."locale where name=?",array(DEFAULT_LOCALE));
			
			$db->execute_query("update ".TABLE_PREFIX."users set locale=? where locale=?",array($default_local_id,$id));
		
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."meta DROP `".$id."_description`,DROP `".$id."_keyword`,DROP `".$id."_title`");
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."email_templates DROP `".$id."_subject`,DROP `".$id."_message`");
			
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."aboutus DROP `".$id."_description`");
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."terms DROP `".$id."_description`");
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."testimonial DROP `".$id."_description`");
			
				
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."custom_pages DROP `".$id."_name`,DROP `".$id."_content`,DROP `".$id."_title`,DROP `".$id."_meta_keyword`,DROP `".$id."_meta_description`");
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."custom_banners DROP `".$id."_title`,DROP `".$id."_content`");
			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."notifications DROP `".$id."_message`");
				
			$this->flash($this->get_message('locale deleted'),$this->make_base_url('locale/manage',ADMIN_DIR));
			exit;
		}
	}
};
?>