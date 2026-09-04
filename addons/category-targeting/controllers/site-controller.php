<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once(LIB_DIR_PATH."FCKeditor/fckeditor.php") ;

if(!class_exists('CategoryHelper'))
include_once ADDON_DIR_PATH."category-targeting".DS."common".DS."helpers".DS."category-helper.php";

class SiteController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();    
		
		if($this->get_action()=="add_admin_site" || $this->get_action()=="edit_admin_site" || $this->get_action()=="manage_admin_site" || $this->get_action()=="delete_logo_admin" || $this->get_action()=="delete_admin_site" || $this->get_action()=="activate_site" || $this->get_action()=="block_site" || $this->get_action()=="statistics_admin" || $this->get_action()=="statistics_publisher"  || $this->get_action()=="detail_statistics_admin" || $this->get_action()=="statistics_publisher_profile")
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}
		
		 
		if($this->get_action()=="add_site" || $this->get_action()=="edit_site" || $this->get_action()=="manage_site" || $this->get_action()=="delete_site" || $this->get_action()=="detail_statistics" || $this->get_action()=="statistics" || $this->get_action()=="delete_logo")
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}
		
		if($this->get_addon_status('category-targeting_enabled') !=1)
		$this->flash($this->get_message('invalid'),BASE,0);
	}
	

	function add_admin_site_action()
	{
		$db= DAL::get_instance();
		$pid=0;
	
		$category=0;
		$url="";
		$protocol="http://";
		$title='';
		$description='';
		$twitter_url='';
		$facebook_url='';
		if($_POST)
		{
			$category=$this->read_post_param('category');
			$url=$this->read_post_param('url');
			$protocol=$this->read_post_param('protocol');
			$twitter_url=$this->read_post_param('twitter_url');
			$facebook_url=$this->read_post_param('facebook_url');
			
			
			$title=$this->read_post_param('title');
			$description=$this->read_post_param('description');
			$logo=$_FILES["logo"]["name"];
			
			$extensionname='';
			if($logo !='')
			{
				$extension=explode(".",$logo);
				$extensionname=strtolower($extension[count($extension)-1]);
				
				$logo=time().'.'.$extensionname;
			}
			
			
			
	
			$url=str_ireplace("http://","",$url);
			$url=str_ireplace("https://","",$url);
			$url=str_ireplace("www.","",$url);
	
	
			$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=?",array($url));
	
			if(!CategoryHelper::get_category_exists($category))
			$this->set_notice("please select a category");
			else if(!UtilityHelper::is_valid_domain($url))
			$this->set_notice("invalid url");
			else if($count >0)
			$this->set_notice("site name already exists");
			else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg")
			$this->set_notice("image not supported");
			else if($logo !='' && $_FILES["logo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else
			{
				$alexa_rank=intval($this->get_alexa_rank($url));
				$google_rank=0;
								
				$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."sites (pid,catid,url,status,title,description,time,twitter_url,facebook_url,alexa_rank,google_rank,protocol) values (?,?,?,?,?,?,?,?,?,?,?,?)",array($pid,$category,$url,1,$title,$description,time(),$twitter_url,$facebook_url,$alexa_rank,$google_rank,$protocol));
				
				if($logo !='')
				{
					$lsid=$res->get_last_id();
					
					if(!is_dir('../'.DATA_DIR.'/site_logo'))
					mkdir('../'.DATA_DIR.'/site_logo',0777);
								
					mkdir('../'.DATA_DIR.'/site_logo/'.$lsid,0777);
								
					if($res->error =="")
					{
						if(move_uploaded_file($_FILES["logo"]["tmp_name"],'../'.DATA_DIR."/site_logo/".$lsid."/".$logo))
						{
							$height=70;
							$width=70;
										
							$image=new ImageHelper('../'.DATA_DIR."/site_logo/".$lsid."/".$logo);
							$image->resize($width,$height,'../'.DATA_DIR."/site_logo/".$lsid."/".$logo);
							
							$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$lsid));
						}
					}		
				}					
				
				$this->flash($this->get_message('site add success'), $this->make_url('dispatch/category_targeting/6/0/'.$category,ADMIN_DIR));
				exit;
			}
		}
		$this->set_variable("url",$url);
		$this->set_variable("protocol",$protocol);
		$this->set_variable("category",$category);
		$this->set_variable("title",$title);
		$this->set_variable("description",$description);
		$this->set_variable("twitter_url",$twitter_url);
		$this->set_variable("facebook_url",$facebook_url);
		

		
	}
	function manage_admin_site_action()
	{
		$db= DAL::get_instance();
		$owner="";
		$site_id="";
		$site_name="";
		
		if($_POST)
		{
			$owner=$this->read_post_param('owner');
	        $search_by=$this->read_post_param('search_by');
			$search_text=$this->read_post_param('search_text');
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');
		}
		else
		{
			$owner=$this->read_page_param(1);
			$category=intval($this->read_page_param(2));
			$status=$this->read_page_param(3);
			$search_by=$this->read_page_param(5);
			$search_text=$this->read_page_param(6);
		
			$exp=explode("-",$category);
			if($exp[0]=="page")
			$category=0;
			
			$exp=explode("-",$status);
			if($exp[0]=="page")
			$status=-2;
			
			$exp=explode("-",$owner);
			if($exp[0]=="page")
			$owner=-1;
			
		}

		$search_text = str_replace("%","\%",$search_text);
		$search_text = str_replace("_","\_",$search_text);

		if($search_by == 1){
				$site_id = $search_text;
			}
			else if($search_by == 2){
				$site_name = $search_text;
			}
			else if($search_by == 3){
				$ownername = $search_text;
			}
		
		if($owner =="")
		$owner=-1;
		
		
		if($status =="")
		$status=-2;
		
		if($owner != -1)
		$string=' WHERE pid='.$owner.' ';
		else 
		$string=' WHERE ( s.pid=0 OR s.pid >0 ) ';

		if($site_id != "")
			$string.=" AND s.id='".$site_id."' ";

		if($site_name != "")
			$string.=" AND s.url LIKE '%".$site_name."%' ";
		
		if($ownername != "")
			    $string.=" AND u.username LIKE '%".$ownername."%' ";
			    
			    
		if($category >0)
		$string.=' AND s.catid='.$category.' ';
		
		if($status != -2)
		$string.=' AND s.status='.$status.' ';

		
		$this->set_variable("owner",$owner);
		$this->set_variable("category",$category);
		$this->set_variable("status",$status);
		
		
		
		$query = "SELECT s.*,u.username FROM ".TABLE_PREFIX."sites s LEFT JOIN ".TABLE_PREFIX."users u ON s.pid=u.id ".$string." ORDER BY s.id DESC";
		
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
		
		$search_text = str_replace("\%","%",$search_text);
		$search_text = str_replace("\_","_",$search_text);
		$this->set_variable("search_by",$search_by);
		$this->set_variable("search_text",$search_text);
		 
		 
		
	}
	
	function edit_admin_site_action()
	{
		$db= DAL::get_instance();
		
		if($_POST)
		$sid=$this->read_post_param('sid');
		else
		{
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$urlcategory=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);
			$page=$this->read_page_param(5);
		}
		
		
		
		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}
		
		$logo='';
		if($_POST)
		{
                        $urlcategory=$this->read_post_param('urlcategory');
			$owner=$this->read_post_param('owner');
			$category=$this->read_post_param('category');
			$url=$this->read_post_param('url');
			$protocol=$this->read_post_param('protocol');
			$status=$this->read_post_param('status');
			$page=$this->read_post_param('page');
			
			
			$title=$this->read_post_param('title');
			$description=$this->read_post_param('description');
			$twitter_url=$this->read_post_param('twitter_url');
			$facebook_url=$this->read_post_param('facebook_url');
			$logo=$_FILES["logo"]["name"];
			
			$extensionname='';
			if($logo !='')
			{
				$extension=explode(".",$logo);
				$extensionname=strtolower($extension[count($extension)-1]);
				
				$logo=time().'.'.$extensionname;
			}
			
			$url=str_ireplace("http://","",$url);
			$url=str_ireplace("https://","",$url);
			$url=str_ireplace("www.","",$url);
			
			
			$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=? AND id <> ?",array($url,$sid));
			
			if(!CategoryHelper::get_category_exists($category))
			$this->set_notice("please select a category");
			else if(!UtilityHelper::is_valid_domain($url))
			$this->set_notice("invalid url");
			else if($count >0)
			$this->set_notice("site name already exists");
			else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg")
			$this->set_notice("image not supported");
			else if($logo !='' && $_FILES["logo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else
			{
				$alexa_rank=intval($this->get_alexa_rank($url));
				
				$google_rank=0;
				
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET url=?,catid=?,title=?,description=?,twitter_url=?,facebook_url=?,alexa_rank=?,google_rank=?,protocol=? WHERE id=?",array($url,$category,$title,$description,$twitter_url,$facebook_url,$alexa_rank,$google_rank,$protocol,$sid));
					
					
					if($logo !='')
					{
						if(!is_dir('../'.DATA_DIR.'/site_logo'))
						mkdir('../'.DATA_DIR.'/site_logo',0777);
									
						if(!is_dir('../'.DATA_DIR.'/site_logo/'.$sid))
						mkdir('../'.DATA_DIR.'/site_logo/'.$sid,0777);
									
						if($res->error =="")
						{
							if(move_uploaded_file($_FILES["logo"]["tmp_name"],'../'.DATA_DIR."/site_logo/".$sid."/".$logo))
							{
								$height=70;
								$width=70;
											
								$image=new ImageHelper('../'.DATA_DIR."/site_logo/".$sid."/".$logo);
								$image->resize($width,$height,'../'.DATA_DIR."/site_logo/".$sid."/".$logo);
								
								$oldlogo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));
							
								$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$sid));
								
								unlink('../'.DATA_DIR."/site_logo/".$sid."/".$oldlogo);
							}
						}		
					}			
					
					
					
					$this->flash($this->get_message('site edit success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$page,ADMIN_DIR));
					exit;
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
			$row=$res->fetch_assoc();
		
			$sid=$row['id'];
			$category=$row['catid'];
			$url=$row['url'];
			$protocol=$row['protocol'];
			$title=$row['title'];
			$description=$row['description'];
			$logo=$row['logo'];
			$twitter_url=$row['twitter_url'];
			$facebook_url=$row['facebook_url'];
			
		}
		
		$logo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));
		
		$this->set_variable("title",$title);
		$this->set_variable("description",$description);
		$this->set_variable("logo",$logo);	
		$this->set_variable("sid",$sid);
		$this->set_variable("url",$url);
		$this->set_variable("protocol",$protocol);
		$this->set_variable("category",$category);
                $this->set_variable("urlcategory",$urlcategory);
		$this->set_variable("twitter_url",$twitter_url);
		$this->set_variable("facebook_url",$facebook_url);
		
		$this->set_variable("owner",$owner);
		$this->set_variable("status",$status);
		$this->set_variable("page",$page);
		
	}
	
	function activate_site_action()
	{
		$db= DAL::get_instance();
	
		$yesno=0;
		$subject='';
		$message='';
		if($_POST)
		{
			$sid=intval($this->read_post_param('sid'));
			$owner=$this->read_post_param('owner');
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');
			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else 
		{
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$category=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);
			$page=$this->read_page_param(5);
		}
		
	
		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}
		
		$siteownerid=$db->read_single_column("SELECT pid FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$sitename=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		
		if($_POST || $siteownerid ==0)
		{
			$res=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET status=1 WHERE id=?",array($sid));
			
			
			if($yesno ==0 && $siteownerid >0)
			{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');
				
				if(DEMO_MODE)
				{
					$this->flash($this->get_message('demo mode'),$this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
					exit;
				}
				
				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($siteownerid));
				
				if($subject =="" || $message =="")
				$this->set_notice('mandatory');
				else
				UtilityHelper::send_mail($email,$subject,$message);
			}
			
			$this->flash($this->get_message('site status success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$page,ADMIN_DIR));
			exit;
		}
		else if($siteownerid >0)
		{
			
				
			$userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($siteownerid));
			$userdata1=$userdata->fetch_assoc();
			$username=$userdata1['username'];
			
			$language_enabled=Configuration::get_instance()->read('language_enabled');
			
			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=17");
			$result1=$res->fetch_assoc();
			
			
			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_message']) && $result1[$userdata1['locale'].'_message'] !='')
			$message=$result1[$userdata1['locale'].'_message'];
			else
			$message=$result1['message'];
			
			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_subject']) && $result1[$userdata1['locale'].'_subject'] !='')
			$subject=$result1[$userdata1['locale'].'_subject'];
			else
			$subject=$result1['subject'];
			
			
			
			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
			$message=str_replace("{USERNAME}",$username,$message);
			$message=str_replace("{NEWSTATUS}",$this->get_label('activated'),$message);
			$subject=str_replace("{NEWSTATUS}",$this->get_label('activated'),$subject);
			$message=str_replace("{SITENAME}",$sitename,$message);
		}
		
		$this->set_variable('sid', $sid);
		$this->set_variable('owner', $owner);
		$this->set_variable('category', $category);
		$this->set_variable('status', $status);
		$this->set_variable('page', $page);
		$this->set_variable('yesno', $yesno);
		
		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);
	}
	
	function block_site_action()
	{
		$db= DAL::get_instance();
	
		$yesno=0;
		$subject='';
		$message='';
		if($_POST)
		{
			$sid=intval($this->read_post_param('sid'));
			$owner=$this->read_post_param('owner');
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');
			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else 
		{
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$category=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);
			$page=$this->read_page_param(5);
		}
		
	
		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}
		
		$siteownerid=$db->read_single_column("SELECT pid FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$sitename=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		
		if($_POST || $siteownerid ==0)
		{	
			$res=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET status=0 WHERE id=?",array($sid));
	
			if($yesno ==0 && $siteownerid >0)
			{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');
				
				if(DEMO_MODE)
				{
					$this->flash($this->get_message('demo mode'),$this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
					exit;
				}
				
				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($siteownerid));
				
				if($subject =="" || $message =="")
				$this->set_notice('mandatory');
				else
				UtilityHelper::send_mail($email,$subject,$message);
			}
			
			$this->flash($this->get_message('site status success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$page,ADMIN_DIR));
			exit;
		}
		else if($siteownerid >0)
		{
			
							
			$userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($siteownerid));
			$userdata1=$userdata->fetch_assoc();
			$username=$userdata1['username'];
			
			$language_enabled=Configuration::get_instance()->read('language_enabled');
			
			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=17");
			$result1=$res->fetch_assoc();
			
			
			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_message']) && $result1[$userdata1['locale'].'_message'] !='')
			$message=$result1[$userdata1['locale'].'_message'];
			else
			$message=$result1['message'];
			
			if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_subject']) && $result1[$userdata1['locale'].'_subject'] !='')
			$subject=$result1[$userdata1['locale'].'_subject'];
			else
			$subject=$result1['subject'];
			
			
			
			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
			$message=str_replace("{USERNAME}",$username,$message);
			$message=str_replace("{NEWSTATUS}",$this->get_label('blocked'),$message);
			$subject=str_replace("{NEWSTATUS}",$this->get_label('blocked'),$subject);
			$message=str_replace("{SITENAME}",$sitename,$message);
			
		}
		
		$this->set_variable('sid', $sid);
		$this->set_variable('owner', $owner);
		$this->set_variable('category', $category);
		$this->set_variable('status', $status);
		$this->set_variable('page', $page);
		$this->set_variable('yesno', $yesno);
		
		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);
		
	}
	
	
	
	function delete_logo_admin_action()
	{
		$db= DAL::get_instance();
		$sid=intval($this->read_page_param(1));
		
		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}
		
		$old_logo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
			
		$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo='' WHERE id=?",array($sid));
			
		unlink('../'.DATA_DIR."/site_logo/".$sid."/".$old_logo);
		rmdir('../'.DATA_DIR."/site_logo/".$sid);
		
		$this->flash($this->get_message('site logo deleted'), $this->make_url('dispatch/category_targeting/7/'.$sid,ADMIN_DIR));
		exit;
	}
	
	
	
	function delete_admin_site_action()
	{
		$db= DAL::get_instance();
		
		$yesno=0;
		$subject='';
		$message='';
		if(isset($_POST['confirm']))
		{
			$confirmed=1;
			$sid=intval($this->read_post_param('sid'));
			$owner=$this->read_post_param('owner');
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');
			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else 
		{
			$confirmed=0;
			$sid=intval($this->read_page_param(1));
			$owner=$this->read_page_param(2);
			$category=intval($this->read_page_param(3));
			$status=$this->read_page_param(4);
			$page=$this->read_page_param(5);
		}
		
		
		
		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}
		
		$siteownerid=$db->read_single_column("SELECT pid FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$sitename=$db->read_single_column("SELECT url FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
		$adunitcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));
		
		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
			
		$mapcount=0;
		if($sponsored_enabled ==1 || $sponsored_enabled ==0)
		$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=2 OR status=3)",array($sid));
		
		
		if($mapcount >0)
		$this->flash($this->get_message('sponsored mappings site exists'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);

		
		
		
		if(($adunitcount ==0 && $siteownerid ==0) || $confirmed ==1)
		{
			$oldlogo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));
			
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
			
			
			if($oldlogo !='')
			unlink("../".DATA_DIR."/site_logo/".$sid."/".$oldlogo);
			
			rmdir("../".DATA_DIR."/site_logo/".$sid."/");
			
			
			
			
			if($adunitcount >0)
			{
				$slist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));
				
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));
				
			}
			
			if($sponsored_enabled ==1 || $sponsored_enabled ==0)
			{
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=-1 OR status=0 OR status=1)",array($sid));
			
				if($adunitcount >0)
				{
					while($slistrow=$slist->fetch_assoc())
					{
						$db->execute_query("DELETE FROM ".TABLE_PREFIX."packages WHERE posid=?",array($slistrow['id']));
					}
				}
			}
			
			if($yesno ==0 && $siteownerid >0)
			{
				$subject=$this->read_post_param('subject');
				$message=$this->read_post_param('message');
			
				if(DEMO_MODE)
				{
					$this->flash($this->get_message('demo mode'),$this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
					exit;
				}
			
				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($siteownerid));
			
				if($subject =="" || $message =="")
				$this->set_notice('mandatory');
				else
				UtilityHelper::send_mail($email,$subject,$message);
			}
			
			$this->flash($this->get_message('site delete success'), $this->make_url('dispatch/category_targeting/6/'.$owner.'/'.$category.'/'.$status.'/'.$page,ADMIN_DIR));
			exit;
			
		}
		else 
		{
			if($siteownerid >0)
			{
				$userdata=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($siteownerid));
				$userdata1=$userdata->fetch_assoc();
				$username=$userdata1['username'];
				
				$language_enabled=Configuration::get_instance()->read('language_enabled');
				
				
				$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=18");
				$result1=$res->fetch_assoc();
			
			
				if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_message']) && $result1[$userdata1['locale'].'_message'] !='')
				$message=$result1[$userdata1['locale'].'_message'];
				else
				$message=$result1['message'];
				
				if($language_enabled ==1 && $userdata1['locale'] >0 && isset($result1[$userdata1['locale'].'_subject']) && $result1[$userdata1['locale'].'_subject'] !='')
				$subject=$result1[$userdata1['locale'].'_subject'];
				else
				$subject=$result1['subject'];
			
			
				$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
				$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
				$message=str_replace("{USERNAME}",$username,$message);
				$message=str_replace("{SITENAME}",$sitename,$message);
			
			}
		}
		
		$this->set_variable("sid",$sid);
		$this->set_variable("owner",$owner);
		$this->set_variable("category",$category);
		$this->set_variable("status",$status);
		$this->set_variable("page",$page);
		
		$this->set_variable("siteownerid",$siteownerid);
		$this->set_variable('yesno', $yesno);
		$this->set_variable('subject', $subject);
		$this->set_variable('message', $message,0);
		
	}
	
	
	
	function statistics_admin_action()
	{
		$db= DAL::get_instance();
	
		if($_POST)
		$duration=$this->read_post_param("duration");
		else
		$duration=1;
	
		if($duration=="" || $duration==0)
		$duration=1;
	
		$this->set_variable("duration",$duration);
	
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
	
		$query = "SELECT * FROM ".TABLE_PREFIX."sites WHERE pid=0 AND (status=1 OR status=0) ORDER BY id";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	
		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
	}
	
	
	
	function statistics_publisher_action()
	{
		$db= DAL::get_instance();
	
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$pub=$this->read_post_param("pub");
		}
		else
		{
			$duration=1;
			$pub=-1;
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
		
		if($pub=="")
		$pub=-1;
		
		if($pub ==-1)
		$pub_string=" and pid <>0 ";
		else
		$pub_string=" and pid='$pub' ";
		
			
		$this->set_variable("duration",$duration);
		$this->set_variable("pub",$pub);
	
		$query = "SELECT * FROM ".TABLE_PREFIX."sites WHERE (status=1 OR status=0) ".$pub_string." ORDER BY id";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	
		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
		
		$sql1="select id,username from ".TABLE_PREFIX."users where pub_status=1 order by id ASC";
		$res1=$db->execute_query($sql1);
		$this->set_result("res1",$res1);
	}
	
	
	function statistics_publisher_profile_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$pub=$this->read_post_param("pub");
		}
		else
		{
			$duration=1;
			$pub=$this->read_page_param(1);
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
		
		$pub_string=" and pid='$pub' ";
		
		
		$this->set_variable("duration",$duration);
		$this->set_variable("pub",$pub);
		
		$query = "SELECT * FROM ".TABLE_PREFIX."sites WHERE (status=1 OR status=0) ".$pub_string." ORDER BY id";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
		
		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
	}
	
	
	
	
	
	
	function detail_statistics_admin_action()
	{
		$db= DAL::get_instance();
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$sid=intval($this->read_post_param("sid"));
			$tab=$this->read_post_param("tab");
		}
		else
		{
			$sid=intval($this->read_page_param(1));
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
	
		if(!CategoryHelper::get_site_exists($sid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',ADMIN_DIR),0);
			exit;
		}
	
		
		$row_data=$db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
		$row_content=$row_data->fetch_assoc();
		
		$uid=$row_content['pid'];
		$sitename=$row_content['url'];
		
		
		$uid=intval($uid);
		
		if($uid >0)
		$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		else
		$username="Admin";
		
		
		
	
		if($duration=="" || $duration==0)
		$duration=1;
	
		if($tab=="" || $tab==0)
		$tab=1;
	
		$this->set_variable("username",$username);
		$this->set_variable("duration",$duration);
		$this->set_variable("sitename",$sitename);
		$this->set_variable("tab",$tab);
		$this->set_variable("sid",$sid);
		$this->set_variable("uid",$uid);
	}
	
	
	
	
	///////////////////////////////////////////////
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	function add_site_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);

	
		$category=0;
		$url="";
		$protocol="http://";
		$title='';
		$description='';
		$twitter_url='';
		$facebook_url='';
		if($_POST)
		{
			$category=$this->read_post_param('category');
			$url=$this->read_post_param('url');
			$protocol=$this->read_post_param('protocol');
			
			$title=$this->read_post_param('title');
			$description=$this->read_post_param('description');
			$twitter_url=$this->read_post_param('twitter_url');
			$facebook_url=$this->read_post_param('facebook_url');
			$logo=$_FILES["logo"]["name"];
			
			$extensionname='';
			if($logo !='')
			{
				$extension=explode(".",$logo);
				$extensionname=strtolower($extension[count($extension)-1]);
				
				$logo=time().'.'.$extensionname;
			}
			
			
			
	
			$url=str_ireplace("http://","",$url);
			$url=str_ireplace("https://","",$url);
			$url=str_ireplace("www.","",$url);
			
			
			
			
			$site_default_status=Configuration::get_instance()->read('site_default_status');
			
			
	
	
			$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=?",array($url));
	
			if(!CategoryHelper::get_category_exists($category))
			$this->set_notice("please select a category");
			else if(!UtilityHelper::is_valid_domain($url))
			$this->set_notice("invalid url");
			else if($count >0)
			$this->set_notice("site name already exists");
			else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg")
			$this->set_notice("image not supported");
			else if($logo !='' && $_FILES["logo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else
			{
				$alexa_rank=intval($this->get_alexa_rank($url));
				
				$google_rank=0;
				
				$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."sites (pid,catid,url,status,title,description,time,twitter_url,facebook_url,alexa_rank,google_rank,protocol) values (?,?,?,?,?,?,?,?,?,?,?,?)",array($pid,$category,$url,$site_default_status,$title,$description,time(),$twitter_url,$facebook_url,$alexa_rank,$google_rank,$protocol));
				
				if($logo !='')
				{
					$lsid=$res->get_last_id();
					
					if(!is_dir(DATA_DIR.'/site_logo'))
					mkdir(DATA_DIR.'/site_logo',0777);
								
					mkdir(DATA_DIR.'/site_logo/'.$lsid,0777);
								
					if($res->error =="")
					{
						if(move_uploaded_file($_FILES["logo"]["tmp_name"],DATA_DIR."/site_logo/".$lsid."/".$logo))
						{
							$height=70;
							$width=70;
										
							$image=new ImageHelper(DATA_DIR."/site_logo/".$lsid."/".$logo);
							$image->resize($width,$height,DATA_DIR."/site_logo/".$lsid."/".$logo);
							
							$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$lsid));
						}
					}		
				}					
				
				$this->flash($this->get_message('site add success'), $this->make_url('dispatch/category_targeting/6/'.$category,BASE));
				exit;
			}
		}
		$this->set_variable("url",$url);
		$this->set_variable("protocol",$protocol);
		$this->set_variable("category",$category);
		$this->set_variable("title",$title);
		$this->set_variable("description",$description);
		$this->set_variable("twitter_url",$twitter_url);
		$this->set_variable("facebook_url",$facebook_url);
		

	}
	function manage_site_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
	
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			$mngr_str='';
			$usr_str1='';
			$usr_str2='';
			
			$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
			$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
			
			if($admintype==2 || $admintype==3)
			{
				$usr_str=$this->get_users_under_mngr($admintype,$adminid);
				if($usr_str!='')
				$usr_str1=" and uid in (".$usr_str.")";
				else 
				$usr_str1=" and uid =-2 ";
				
				
		
				if($admintype ==3)
				{
					$mngr_str=" and pub_managerid=".$adminid;
				
					if($usr_str!='')
					$usr_str2=" and pid in (".$usr_str.")";
					else 
					$usr_str2=" and pid =-2 ";
				}
				
				elseif ($admintype ==2)
				{
					$mngr_str=" and adv_managerid=".$adminid;
					
					if($usr_str!='')
					$usr_str2=" and pid in (".$usr_str.")";
					else 
					$usr_str2=" and pid =-2 ";
				}
		
			}
		}
		
		if($_POST)
		{
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');
		}
		else
		{
			$category=intval($this->read_page_param(1));
			$status=$this->read_page_param(2);
	
			$exp=explode("-",$category);
			if($exp[0]=="page")
			$category=0;
	
			$exp=explode("-",$status);
			if($exp[0]=="page")
			$status=-2;
		}
	
	
		if($status =="")
		$status=-2;
	
		$string=' WHERE pid='.$pid.' ';
	
		if($category >0)
		$string.=' AND catid='.$category.' ';
	
		if($status != -2)
		$string.=' AND status='.$status.' ';
	
	
		$this->set_variable("category",$category);
		$this->set_variable("status",$status);
	
	
		$query = "SELECT * FROM ".TABLE_PREFIX."sites ".$string." ".$usr_str2." ORDER BY id DESC";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	
		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
	}
	
	function delete_logo_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
		$sid=intval($this->read_page_param(1));
		
		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}
		
		$old_logo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));
			
		$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo='' WHERE id=?",array($sid));
			
		unlink(DATA_DIR."/site_logo/".$sid."/".$old_logo);
		rmdir(DATA_DIR."/site_logo/".$sid);
		
		$this->flash($this->get_message('site logo deleted'), $this->make_url('dispatch/category_targeting/7/'.$sid,BASE));
		exit;
	}
	
	
	
	function edit_site_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
		$mem_obj=$this->memcache_connect();
		
		if($_POST)
		$sid=$this->read_post_param('sid');
		else
		{
			$sid=intval($this->read_page_param(1));
			$urlcategory=intval($this->read_page_param(2));
			$status=$this->read_page_param(3);
			$page=$this->read_page_param(4);
		}
		
		if($mem_obj!=false)
		{   
			$site_array=$mem_obj->get('site_'.$sid);
		}
		
	
		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}
	
	
		$logo='';
		if($_POST)
		{
            $urlcategory=$this->read_post_param('urlcategory');
			$category=$this->read_post_param('category');
			$url=$this->read_post_param('url');
			$protocol=$this->read_post_param('protocol');
			$status=$this->read_post_param('status');
			$page=$this->read_post_param('page');
			
			
			$title=$this->read_post_param('title');
			$description=$this->read_post_param('description');
			$twitter_url=$this->read_post_param('twitter_url');
			$facebook_url=$this->read_post_param('facebook_url');
			$logo=$_FILES["logo"]["name"];
			
			$extensionname='';
			if($logo !='')
			{
				$extension=explode(".",$logo);
				$extensionname=strtolower($extension[count($extension)-1]);
				
				$logo=time().'.'.$extensionname;
			}
			

			
			$site_default_status=Configuration::get_instance()->read('site_default_status');
			
			$url=str_ireplace("http://","",$url);
			$url=str_ireplace("https://","",$url);
			$url=str_ireplace("www.","",$url);
	
	
			$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."sites where url=? AND id <> ?",array($url,$sid));
	
			if(!CategoryHelper::get_category_exists($category))
			$this->set_notice("please select a category");
			else if(!UtilityHelper::is_valid_domain($url))
			$this->set_notice("invalid url");
			else if($count >0)
			$this->set_notice("site name already exists");
			else if($logo !='' && $extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "jpg")
			$this->set_notice("image not supported");
			else if($logo !='' && $_FILES["logo"]["error"] > 0)
			$this->set_notice("error occurred in image uploading");
			else
			{
				$alexa_rank=intval($this->get_alexa_rank($url));
				
				$google_rank=0;
				
				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET url=?,catid=?,status=?,title=?,description=?,twitter_url=?,facebook_url=?,alexa_rank=?,google_rank=?,protocol=? WHERE id=?",array($url,$category,$site_default_status,$title,$description,$twitter_url,$facebook_url,$alexa_rank,$google_rank,$protocol,$sid));
				
				if($res->error=='' && $mem_obj!=false && $mem_obj->getResultCode() == Memcached::RES_SUCCESS)
				{
						$site_array['catid']=$category;
						$site_array['url']=$url;
						$mem_obj->set('site_'.$sid,$site_array,MEMCACHE_EXPIRY);
				}
				if($logo !='')
				{
					if(!is_dir(DATA_DIR.'/site_logo'))
					mkdir(DATA_DIR.'/site_logo',0777);
								
					if(!is_dir(DATA_DIR.'/site_logo/'.$sid))
					mkdir(DATA_DIR.'/site_logo/'.$sid,0777);
								
					if($res->error =="")
					{
						if($mem_obj!=false && count($site_array) > 0)
						{
							$site_array['catid']=$category;
							$site_array['url']=$url;
							$mem_obj->set('site_'.$sid,$site_array,MEMCACHE_EXPIRY);
						}
						
						if(move_uploaded_file($_FILES["logo"]["tmp_name"],DATA_DIR."/site_logo/".$sid."/".$logo))
						{
							$height=70;
							$width=70;
										
							$image=new ImageHelper(DATA_DIR."/site_logo/".$sid."/".$logo);
							$image->resize($width,$height,DATA_DIR."/site_logo/".$sid."/".$logo);
							
							$oldlogo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));
						
							$db->execute_query("UPDATE ".TABLE_PREFIX."sites SET logo=? WHERE id=?",array($logo,$sid));
							
							unlink(DATA_DIR."/site_logo/".$sid."/".$oldlogo);
						}
					}		
				}			
				
	
				$this->flash($this->get_message('site edit success'), $this->make_url('dispatch/category_targeting/6/'.$urlcategory.'/'.$status.'/'.$page,BASE));
				exit;
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."sites where id=?",array($sid));
			$row=$res->fetch_assoc();
	
			$sid=$row['id'];
			$category=$row['catid'];
			$url=$row['url'];
			$protocol=$row['protocol'];
			$title=$row['title'];
			$description=$row['description'];
			$logo=$row['logo'];
			$twitter_url=$row['twitter_url'];
			$facebook_url=$row['facebook_url'];
		}
	
		
		
		
		$logo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));
		
			
		$this->set_variable("title",$title);
		$this->set_variable("description",$description);
		$this->set_variable("logo",$logo);	
		$this->set_variable("sid",$sid);
		$this->set_variable("url",$url);
		$this->set_variable("protocol",$protocol);
		$this->set_variable("category",$category);
                $this->set_variable("urlcategory",$urlcategory);
		$this->set_variable("twitter_url",$twitter_url);
		$this->set_variable("facebook_url",$facebook_url);
	
		$this->set_variable("status",$status);
		$this->set_variable("page",$page);
	}
	
	function delete_site_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
	
		if(isset($_POST['confirm']))
		{
			$confirmed=1;
			$sid=intval($this->read_post_param('sid'));
			$category=intval($this->read_post_param('category'));
			$status=$this->read_post_param('status');
			$page=$this->read_post_param('page');
			$yesno=intval($this->read_post_param('yesno'));
		}
		else
		{
			$confirmed=0;
			$sid=intval($this->read_page_param(1));
			$category=intval($this->read_page_param(2));
			$status=$this->read_page_param(3);
			$page=$this->read_page_param(4);
		}
	
	
	
		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}
	
		
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
			
		$mapcount=0;
		if($sponsored_enabled ==1 || $sponsored_enabled ==0)
		$mapcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=2 OR status=3)",array($sid));
		
		
		if($mapcount >0)
		$this->flash($this->get_message('sponsored mappings site exists'), $this->make_url('dispatch/category_targeting/6',BASE),0);
		
	
		
		
		$adunitcount=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."adunit WHERE sid=?",array($sid));
	
		if($adunitcount ==0 || $confirmed ==1)
		{
			$oldlogo=$db->read_single_column("SELECT logo FROM ".TABLE_PREFIX."sites where id=?",array($sid));
			
			
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."sites WHERE id=? AND pid=?",array($sid,$pid));
			
			
			
			if($oldlogo !='')
			unlink(DATA_DIR."/site_logo/".$sid."/".$oldlogo);
			
			rmdir(DATA_DIR."/site_logo/".$sid."/");
			
			
			
			if($adunitcount >0)
			{
				$slist=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."adunit WHERE sid=? AND pubid=?",array($sid,$pid));
				
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."adunit WHERE sid=? AND pubid=?",array($sid,$pid));
				
			}
			
			
			if($sponsored_enabled ==1 || $sponsored_enabled ==0)
			{
				$db->execute_query("DELETE FROM ".TABLE_PREFIX."sponsored_ad_mapping WHERE site=? AND (status=-1 OR status=0 OR status=1)",array($sid));
			
				if($adunitcount >0)
				{
					while($slistrow=$slist->fetch_assoc())
					{
						$db->execute_query("DELETE FROM ".TABLE_PREFIX."packages WHERE posid=?",array($slistrow['id']));
					}
				}
			}
	
			$this->flash($this->get_message('site delete success'), $this->make_url('dispatch/category_targeting/6/'.$category.'/'.$status.'/'.$page,BASE));
			exit;
		}

	
		$this->set_variable("sid",$sid);
		$this->set_variable("category",$category);
		$this->set_variable("status",$status);
		$this->set_variable("page",$page);
	}
	
	
	function statistics_action()
	{
		$db= DAL::get_instance();
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
	
		if($_POST)
		$duration=$this->read_post_param("duration");
		else
		$duration=1;
	
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
	
		$this->set_variable("duration",$duration);
		$this->set_variable("pid",$pid);
	
	
		$query = "SELECT * FROM ".TABLE_PREFIX."sites WHERE pid=".$pid." AND (status=1 OR status=0) ORDER BY id";
		$pagination = new Pagination($query);
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	
		$pg=$pagination->get_page_number();
		$this->set_variable("page","page-".$pg);
	}
	
	
	function detail_statistics_action()
	{
		$pid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();
		
		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$sid=intval($this->read_post_param("sid"));
			$tab=$this->read_post_param("tab");
		}
		else
		{
			$sid=intval($this->read_page_param(1));
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
	
		if(!CategoryHelper::get_site_exists($sid,$pid))
		{
			$this->flash($this->get_message('site invalid'), $this->make_url('dispatch/category_targeting/6',BASE),0);
			exit;
		}
	
		$sitename=$db->read_single_column("select url from ".TABLE_PREFIX."sites where id=?",array($sid));
	
		if($duration=="" || $duration==0)
		$duration=1;
	
		if($tab=="" || $tab==0)
		$tab=1;
	
		
		$this->set_variable("duration",$duration);
		$this->set_variable("sitename",$sitename);
		$this->set_variable("pid",$pid);
		$this->set_variable("tab",$tab);
		$this->set_variable("sid",$sid);
	}
	
	
	
};