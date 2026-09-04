<?php 
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";

class Email_templateController extends ApplicationController
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
	function manage_action()
	{
		$this->set_title($this->get_label('manage email templates'));
		$db= DAL::get_instance();
		
		$query = "SELECT id,subject,message FROM ".TABLE_PREFIX."email_templates order by id";
		$res=$db->execute_query($query);
		$this->set_result("res",$res,array('message'));
	}
	
	function edit_action()
	{
		$this->set_title($this->get_label('edit email templates'));
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);
	
		
		$co=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."email_templates WHERE id=?",array($id));
		if($co =="")
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('email_template/manage'),0);
			exit;
		}
		
		$language_enabled=Configuration::get_instance()->read('language_enabled');
		$this->set_variable('language_enabled',$language_enabled);
		
		if($language_enabled ==1)
		{
			$localedata=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."locale WHERE status=1 OR name='".DEFAULT_LOCALE."'");
			$this->set_result('localedata', $localedata);
		}
		
		
		if($_POST)
		{
			if(DEMO_MODE)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('email_template/manage'),0);
				exit;
			}
			
			$subject=$this->read_post_param('subject');
			$message=$this->read_post_param('message');
			
			
			$this->set_variable("subject",$subject);
			$this->set_variable("message",$message,0);
			$this->set_variable("id",$id);
			
			if($subject =="" || $message =="")
			$this->set_notice("mandatory");
			else
			{
				if($language_enabled ==1)
				{
					$locale1='';
					$locale2=array();
					
					while($localerow=$localedata->fetch_assoc())
					{
						$submitvalue=trim($this->read_post_param('subject_'.$localerow['id']));
						$submitvalue1=trim($this->read_post_param('message_'.$localerow['id']));
							
						$this->set_variable($localerow['id'].'_subject',$submitvalue);
						$this->set_variable($localerow['id'].'_message',$submitvalue1,0);
							
														
						
							if($locale1 !='')
							$locale1.=',';
						
							$locale1.=intval($localerow['id']).'_subject=?';
					
							$locale2[]=$submitvalue;
						
						
						
						
							if($locale1 !='')
							$locale1.=',';
						
							$locale1.=intval($localerow['id']).'_message=?';
					
							$locale2[]=$submitvalue1;
						
						
					}
					$localedata->set_result_index();
				}
				
				
				
				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."email_templates set subject=?,message=? where id=?",array($subject,$message,$id));
				
				if($language_enabled ==1)
				{
					if($locale1 !='' && count($locale2) >0)
					{
						$locale2[]=$id;
						
						$db->execute_query("UPDATE ".TABLE_PREFIX."email_templates SET ".$locale1." WHERE id=?",$locale2);
					}
				} 
				
				
				$this->flash($this->get_message('email template edited'), $this->make_url('email_template/edit/'.$id));
				exit;
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
			$row=$res->fetch_assoc();
			$this->set_variable("id",$row['id']);
			$this->set_variable("subject",$row['subject']);
			$this->set_variable("message",$row['message'],0);
			
			if($language_enabled ==1)
			{
				while($localerow=$localedata->fetch_assoc())
				{
					$this->set_variable($localerow['id'].'_subject',$row[$localerow['id'].'_subject']);
					$this->set_variable($localerow['id'].'_message',$row[$localerow['id'].'_message'],0);
				}
			}
		}
	}
	
	
	
	
};