<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";

class CreditController extends ApplicationController
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

	function create_action()
	{
		$this->set_title($this->get_label('create new credit text'));
		$db= DAL::get_instance();

		if($_POST)
		{
			$ctext="";
			$cimage="";

			$icontext="";
			$iconimage="";


			$ctype=$this->read_post_param('ctype');
			$icontype=$this->read_post_param('icontype');

			if($ctype ==0)
			{
				$ctext=$this->read_post_param('ctext');
				$ctext=strip_tags($ctext);
				$count=$db->read_single_column("select count(id) as totalcount from ".TABLE_PREFIX."credittext where credittext=?",array($ctext));
			}
			else if($ctype ==1)
			{
				$cimage=$_FILES["cimage"]["name"];

				$extension=explode(".",$_FILES['cimage']['name']);
				$extensionname=strtolower($extension[count($extension)-1]);

				$cimage=time().'.'.$extensionname;
			}



			if($icontype ==0)
			{
				$icontext=$this->read_post_param('icontext');
				$icontext=strip_tags($icontext);
			}
			else if($icontype ==1)
			{
				$iconimage=$_FILES["iconimage"]["name"];

				if($iconimage !="")
				{
					$extension1=explode(".",$_FILES['iconimage']['name']);
					$extensionname1=strtolower($extension1[count($extension1)-1]);

					$iconimage=time().'.'.$extensionname1;
				}
			}


			if($icontype ==1 && $iconimage =="")
			$icontype=0;



			if($ctype ==0 && $ctext=="")
			$this->set_notice("mandatory");
			else if($ctype ==0 && $count >0)
			$this->set_notice("credit text already created");
			else if($ctype ==1 && $cimage=="")
			$this->set_notice("mandatory");
			else if($ctype ==1 && ($extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg"))
			$this->set_notice("image not supported");
			else if($icontype ==1 && $iconimage !="" && ($extensionname1 != "gif" && $extensionname1 != "jpeg" && $extensionname1 != "pjpeg" && $extensionname1 != "png" && $extensionname1 != "svg" && $extensionname1 != "jpg"))
			$this->set_notice("image not supported");
			else if($ctype ==1 && $_FILES["cimage"]["error"] > 0)
			$this->set_notice("image file upload failed");
			else if($icontype ==1 && $iconimage !="" && $_FILES["iconimage"]["error"] > 0)
			$this->set_notice("image file upload failed");
			else
			{
					$flag=0;


					if($icontype ==0)
					$icon_content=$icontext;
					else
					$icon_content=$iconimage;



					if($ctype ==0)
					{
						$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."credittext (id,credittext,type,icon_type,icon_content) values (?,?,?,?,?)",array('',$ctext,0,$icontype,$icon_content));
						$id=$res->get_last_id();
					}
					else if($ctype ==1)
					{
						$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."credittext (id,image,type,icon_type,icon_content) values (?,?,?,?,?)",array('',$cimage,1,$icontype,$icon_content));
						$id=$res->get_last_id();

						$cimage=$id.'-'.$cimage;

						if(!is_dir("../".DATA_DIR."/credit"))
	    				mkdir("../".DATA_DIR."/credit",0777);

	    				$credit_width=80;
        				$credit_height=15;

						if(move_uploaded_file($_FILES["cimage"]["tmp_name"],"../".DATA_DIR."/credit/".$cimage))
						{
							$image=new ImageHelper("../".DATA_DIR."/credit/".$cimage);
        					$image->resize($credit_width,$credit_height,"../".DATA_DIR."/credit/".$cimage);

							$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET image=? WHERE id=?",array($cimage,$id));
						}
						else
						{
							$flag=1;

							$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET image=? WHERE id=?",array('',$id));
						}
					}


					 if($icontype ==1 && $iconimage !="")
					 {
					 	$iconimage=$id.'-icon-'.$iconimage;

						if(!is_dir("../".DATA_DIR."/credit"))
	    				mkdir("../".DATA_DIR."/credit",0777);

	    				$credit_width=15;
        				$credit_height=15;

						if(move_uploaded_file($_FILES["iconimage"]["tmp_name"],"../".DATA_DIR."/credit/".$iconimage))
						{
							$image=new ImageHelper("../".DATA_DIR."/credit/".$iconimage);
        					$image->resize($credit_width,$credit_height,"../".DATA_DIR."/credit/".$iconimage);

							$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET icon_content=? WHERE id=?",array($iconimage,$id));
						}
						else
						{
							$flag=1;

							$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET icon_content=? WHERE id=?",array('',$id));
						}
					 }


					if($flag ==0)
					$this->flash($this->get_message('credit success'), $this->make_url('credit/manage'));
					else if($flag ==1)
					$this->flash($this->get_message('image file upload failed'), $this->make_url('credit/manage'),0);

					exit;
			}

			$this->set_variable('ctext',$ctext);
			$this->set_variable('ctype',$ctype);
			$this->set_variable('icontext',$icontext);
			$this->set_variable('icontype',$icontype);
		}
	}
	function manage_action()
	{
		$this->set_title($this->get_label('manage credit text'));

		$db= DAL::get_instance();

		$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."credittext  ORDER BY id desc");
		$this->set_result("res",$res);
	}
	function edit_action()
	{
		$this->set_title($this->get_label('edit credit text'));
		$db= DAL::get_instance();

		if($_POST)
		$id=$this->read_post_param('id');
		else
		$id=$this->read_page_param(1);

		$exists=$db->read_single_column("select count(id) as totalcount from ".TABLE_PREFIX."credittext where id=?",array($id));

		if($exists ==0)
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('credit/manage'),0);
			exit;
		}


		if($_POST)
		{
			if(DEMO_MODE && $id <=2)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('credit/manage'),0);
				exit;
			}


			$ctext="";
			$cimage="";

			$icontext="";
			$iconimage="";

			$ctype=$this->read_post_param('ctype');
			$icontype=$this->read_post_param('icontype');

			if($ctype ==0)
			{
				$ctext=$this->read_post_param('ctext');
				$ctext=strip_tags($ctext);
				$count=$db->read_single_column("select count(id) as totalcount from ".TABLE_PREFIX."credittext where credittext=? and id<>?",array($ctext,$id));
			}
			else if($ctype ==1)
			{
				$cimage=$_FILES["cimage"]["name"];

				if($cimage !='')
				{
					$extension=explode(".",$_FILES['cimage']['name']);
					$extensionname=strtolower($extension[count($extension)-1]);

					$cimage=time().'.'.$extensionname;
				}
			}


			if($icontype ==0)
			{
				$icontext=$this->read_post_param('icontext');
				$icontext=strip_tags($icontext);
			}
			else if($icontype ==1)
			{
				$iconimage=$_FILES["iconimage"]["name"];

				if($iconimage !="")
				{
					$extension1=explode(".",$_FILES['iconimage']['name']);
					$extensionname1=strtolower($extension1[count($extension1)-1]);

					$iconimage=time().'.'.$extensionname1;
				}
			}


			$res2=$db->execute_query("select * from ".TABLE_PREFIX."credittext where id=?",array($id));
			$row=$res2->fetch_assoc();

			$oldimage			= $row['image'];
			$oldicon_type		= $row['icon_type'];
			$oldicon_content	= $row['icon_content'];


			if($icontype ==1 && $oldicon_type ==0 && $iconimage =="")
			$icontype=0;


			if($ctype ==0 && $ctext =="")
			$this->set_notice("mandatory");
			else if($ctype ==0 && $count >0)
			$this->set_notice("credit text already created");
			else if($ctype ==1 && $cimage !='' && ($extensionname != "gif" && $extensionname != "jpeg" && $extensionname != "pjpeg" && $extensionname != "png" && $extensionname != "svg" && $extensionname != "jpg"))
			$this->set_notice("image not supported");
			else if($icontype ==1 && $iconimage !="" && ($extensionname1 != "gif" && $extensionname1 != "jpeg" && $extensionname1 != "pjpeg" && $extensionname1 != "png" && $extensionname1 != "svg" && $extensionname1 != "jpg"))
			$this->set_notice("image not supported");
			else if($ctype ==1 && $cimage !='' && $_FILES["cimage"]["error"] > 0)
			$this->set_notice("image file upload failed");
			else if($icontype ==1 && $iconimage !="" && $_FILES["iconimage"]["error"] > 0)
			$this->set_notice("image file upload failed");
			else
			{
				$dataflag=0;

				if($icontype ==0)
				$icon_content=$icontext;
				else
				$icon_content=$iconimage;



				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET credittext=? WHERE id=?",array($ctext,$id));


				if($ctype ==1)
				{
					$credit_width=80;
        			$credit_height=15;

					if($cimage !='')
        			{
						$cimage=$id.'-'.$cimage;

						if(!is_dir("../".DATA_DIR."/credit"))
						mkdir("../".DATA_DIR."/credit",0777);

						if(move_uploaded_file($_FILES["cimage"]["tmp_name"],"../".DATA_DIR."/credit/".$cimage))
						{
							$image=new ImageHelper("../".DATA_DIR."/credit/".$cimage);
	        				$image->resize($credit_width,$credit_height,"../".DATA_DIR."/credit/".$cimage);

							$res=$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET image=? WHERE id=?",array($cimage,$id));

							unlink("../".DATA_DIR."/credit/".$oldimage);
						}
						else
						$dataflag=1;
        			}
				}


				if($icontype ==1 && $icon_content !="")
				{
				 	$iconimage=$id.'-icon-'.$iconimage;

					if(!is_dir("../".DATA_DIR."/credit"))
    				mkdir("../".DATA_DIR."/credit",0777);

    				$credit_width=15;
        			$credit_height=15;

					if(move_uploaded_file($_FILES["iconimage"]["tmp_name"],"../".DATA_DIR."/credit/".$iconimage))
					{
						$image=new ImageHelper("../".DATA_DIR."/credit/".$iconimage);
        				$image->resize($credit_width,$credit_height,"../".DATA_DIR."/credit/".$iconimage);

						$res=$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET icon_type=?,icon_content=? WHERE id=?",array($icontype,$iconimage,$id));

						if($res->error =='')
						{
							if($oldicon_type ==1 && $oldicon_content !="")
							unlink("../".DATA_DIR."/credit/".$oldicon_content);
						}
					}
					else
					{
						$dataflag=1;
						$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET icon_type=?,icon_content=? WHERE id=?",array($oldicon_type,$oldicon_content,$id));
					}
				}
				else if($icon_content !="")
				{
					$res=$db->execute_query("UPDATE ".TABLE_PREFIX."credittext SET icon_type=?,icon_content=? WHERE id=?",array($icontype,$icon_content,$id));

					if($res->error =='')
					{
						if($oldicon_type ==1 && $oldicon_content !="")
						unlink("../".DATA_DIR."/credit/".$oldicon_content);
					}
				}

        		if($dataflag ==0)
				$this->flash($this->get_message('credit edit success'), $this->make_url('credit/manage'));
				else
				$this->flash($this->get_message('image file upload failed'), $this->make_url('credit/manage'),0);
			}
		}


		$res=$db->execute_query("select * from ".TABLE_PREFIX."credittext where id=?",array($id));
		$row=$res->fetch_assoc();

		$ctext			= $row['credittext'];
		$ctype			= $row['type'];
		$icon_type		= $row['icon_type'];
		$oldimage		= $row['image'];
		$icon_content	= $row['icon_content'];


		$this->set_variable('oldimage',$oldimage);
		$this->set_variable("id",$id);
		$this->set_variable('ctext',$ctext);
		$this->set_variable('ctype',$ctype);
		$this->set_variable('iconcontent',$icon_content);
		$this->set_variable('icontype',$icon_type);
	}

	function delete_action()
	{
		$id=$this->read_page_param(1);
		$db= DAL::get_instance();


		if(DEMO_MODE && $id <=2)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('credit/manage'),0);
			exit;
		}

		$row=$db->execute_query("SELECT id,image,icon_type,icon_content FROM ".TABLE_PREFIX."credittext WHERE id=?",array($id));

		if($row->get_num_records() ==0)
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('credit/manage'),0);
			exit;
		}

		$rowdata=$row->fetch_assoc();

		$oldimage		= $rowdata['image'];
		$icon_type		= $rowdata['icon_type'];
		$icon_content	= $rowdata['icon_content'];


		if($oldimage !='')
		unlink("../".DATA_DIR."/credit/".$oldimage);

		if($icon_type ==1 && $icon_content !="")
		unlink("../".DATA_DIR."/credit/".$icon_content);


		$res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."credittext where id=?",array($id));

		$db->execute_query("UPDATE ".TABLE_PREFIX."adblock SET credit_text=0 WHERE credit_text=?",array($id));

		$native_enabled          = $this->get_addon_status('native-ad-display_enabled');

		if($native_enabled == 1 || $native_enabled == 0)
		{
			$db->execute_query("UPDATE ".TABLE_PREFIX."config SET value = 0 WHERE name = 'native_credit_text' AND value = ?",array($id));

			if(method_exists($this, 'get_config_updation'))
			$this->get_config_updation();
		}


		$this->flash($this->get_message('delete credit'), $this->make_url('credit/manage'));
		exit;
	}
};
?>
