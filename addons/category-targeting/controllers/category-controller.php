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

		$built_in_category=$db->read_single_column("select built_in_category from ".TABLE_PREFIX."categories where id=?",array($pid));

		if($built_in_category==1)
		{
			$this->flash($this->get_message('category add permission'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}
		$parent_level=CategoryHelper::get_category_level($pid);

		$category            = "";
		$keyword             = '';
		$description         = '';
		$iab_id='';
		$iab_id_category="";
		$exclusive_targeting = 0;

		if($_POST)
		{
			$category            = $this->read_post_param('category');
			$iab_id_category=$this->read_post_param('iab_id_category');
			$keyword             = $this->read_post_param('keyword');
			$description         = $this->read_post_param('description');
			$exclusive_targeting = intval($this->read_post_param('exclusive_targeting'));

			if($iab_id_category=="")
			$iab_id="";
			else
			$iab_id=$db->read_single_column("select iab_id from ".TABLE_PREFIX."categories where id=?",array($iab_id_category));

			if($category =="")
			$this->set_notice("mandatory");
			else
			{
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=? and name=? and built_in_category=?",array($pid,$category,0));
				if($count >0)
				$this->set_notice("category exists");
				else
				{
					if($pid ==0)
					$parent_level1=0;
					else
					$parent_level1=$parent_level+1;

					$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."categories (name,pid,level,keyword,description,iab_id,exclusive_targeting) values (?,?,?,?,?,?,?)",array($category,$pid,$parent_level1,$keyword,$description,$iab_id,$exclusive_targeting));
	                                $last_d=$res->get_last_id();

	                                $db->execute_query("ALTER TABLE " . TABLE_PREFIX . "ads_cache ADD (?_category int NOT NULL DEFAULT 0)",array($last_d));

					$this->flash($this->get_message('category added'), $this->make_url('dispatch/category_targeting/2/'.$pid.'/2',ADMIN_DIR));
					exit;
				}
			}
		}

		$this->set_variable("pid",$pid);
		$this->set_variable("category",$category);
		$this->set_variable("iab_id_category",$iab_id_category);
		$this->set_variable("keyword",$keyword);
		$this->set_variable("description",$description);
		$this->set_variable("exclusive_targeting",$exclusive_targeting);


	}

	function manage_action()
	{
		$db= DAL::get_instance();
		$pid_1=0;
		$pid_2=0;
		$pid = intval($this->read_page_param(1));
		$tab = intval($this->read_page_param(2));
		$linked = 0;
		if($tab == 0)
		$tab = 1;
		else
		$linked=$tab;
		if($tab == 1)
		$pid_1 = $pid;
		else if($tab == 2)
		$pid_2 = $pid;

		$this->set_variable("tab",$tab);
		if($pid > 0 && !CategoryHelper::get_category_exists($pid))
		{
			$this->flash($this->get_message('category invalid'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}

		if($linked == 0 || $linked == 1)
		$pid_iab_cat = $pid_1;
		else
		$pid_iab_cat = 0;

		if($linked == 0 || $linked == 2)
		$pid_custom_cat = $pid_2;
		else
		$pid_custom_cat = 0;
		$iab_categories = $db->execute_query("select * from ".TABLE_PREFIX."categories where pid=? and built_in_category=? order by name",array($pid_iab_cat,1));
		$this->set_result("iab_categories",$iab_categories);

		$custom_categories=$db->execute_query("select * from ".TABLE_PREFIX."categories where pid=? and built_in_category=? order by name",array($pid_custom_cat,0));
		$this->set_result("custom_categories",$custom_categories);
		$this->set_variable("categorypath_1",CategoryHelper::get_category_path($pid_1,"dispatch/category_targeting/2/","","",1),0);
		$this->set_variable("categorypath_2",CategoryHelper::get_category_path($pid_2,"dispatch/category_targeting/2/","","",2),0);

	}
	function status_change_action()
	{
		$db = DAL::get_instance();
		$this->disable_notice_area();

		$cid = intval($this->read_post_param('catID'));
		$parent_category_status = 1;
		$res=$db->execute_query("select * from ".TABLE_PREFIX."categories where id=?",array($cid));
		if($res->get_num_records() == 0)
		{
			echo 1; //category invalid
			exit;
		}
		else
		$row=$res->fetch_assoc();
		$built_in_category = $row['built_in_category'];
		if($row['pid'] != 0)
		$parent_category_status=$db->read_single_column("select category_status from ".TABLE_PREFIX."categories where id=?",array($row['pid']));
		if($parent_category_status==0)
		 {
		         echo 2; //parent is blocked
			 exit;
		 }
		if($row['category_status']==1)
		$category_status = 0;
		else if($row['category_status']==0)
		$category_status = 1;
		$cat_string = "";
		if($built_in_category == 1)
		{
			$iab_list   = CategoryHelper::get_built_in_iab_child_list($cid);
			if($iab_list != "")
			$cat_string = " AND iab_id IN ".$iab_list;
			$custom_categories_linked = $db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where built_in_category=? and category_status=? ".$cat_string,array(0,1));
			if($custom_categories_linked > 0)
			{
			        echo 3; //category child exists
				exit;
			}
			$res=$db->execute_query("update ".TABLE_PREFIX."categories set category_status=? where built_in_category=?".$cat_string,array($category_status,1));
		 }
		 else if($built_in_category == 0)
		 {
			 $child_cat_list   = CategoryHelper::get_custom_category_child_list($cid);
			 $child_cat_string = "";
			 if($child_cat_list != "")
			 $child_cat_string = " AND id IN (".$cid.",".$child_cat_list.")";
			 else
			 $child_cat_string = " AND id IN (".$cid.")";
			 $res = $db->execute_query("update ".TABLE_PREFIX."categories set category_status=? where built_in_category=?".$child_cat_string,array($category_status,0));
		 }
		 die;
	}
	function edit_action()
	{
		$db= DAL::get_instance();

		if($_POST)
		$id = $this->read_post_param('id');
		else
		$id = $this->read_page_param(1);

		if(!CategoryHelper::get_category_exists($id))
		{
			$this->flash($this->get_message('category invalid'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}

		$built_in_category=$db->read_single_column("select built_in_category from ".TABLE_PREFIX."categories where id=?",array($id));
		if($built_in_category==1)
		{
			$this->flash($this->get_message('category edit permission'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}

		$pid=DAL::get_instance()->read_single_column("select pid from ".TABLE_PREFIX."categories where id=?",array($id));
		if($_POST)
		{
			if(DEMO_MODE && $id <= 115)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
				exit;
			}

			$category            = $this->read_post_param('category');
			$iab_id_category=$this->read_post_param('iab_id_category');
			$keyword             = $this->read_post_param('keyword');
			$description         = $this->read_post_param('description');
			$exclusive_targeting = intval($this->read_post_param('exclusive_targeting'));


			$this->set_variable("id",$id);
			$this->set_variable("category",$category);
			$this->set_variable("iab_id_category",$iab_id_category);
			$this->set_variable("keyword",$keyword);
			$this->set_variable("description",$description);
			$this->set_variable("exclusive_targeting",$exclusive_targeting);

			if($iab_id_category=="")
			$iab_id="";
			else
			$iab_id=$db->read_single_column("select iab_id from ".TABLE_PREFIX."categories where id=?",array($iab_id_category));

			if($category =="")
				$this->set_notice("mandatory");
			else
			{
				$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=? and name=? and built_in_category=? and id <> ?",array($pid,$category,0,$id));
				if($count >0)
				$this->set_notice("category exists");
				else
				{
					$res=$db->execute_query("update ".TABLE_PREFIX."categories set name=?,iab_id=?,keyword=?,description=?,exclusive_targeting = ? where id=?",array($category,$iab_id,$keyword,$description,$exclusive_targeting,$id));

					$this->flash($this->get_message('category edited'), $this->make_url('dispatch/category_targeting/2/'.$pid.'/2',ADMIN_DIR));
					exit;
				}
			}
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."categories where id=?",array($id));
			$row=$res->fetch_assoc();

			if($row['iab_id']=="")
			$linked_category_id="";
			else
			$linked_category_id=$db->read_single_column("select id from ".TABLE_PREFIX."categories where iab_id=? and built_in_category=?",array($row['iab_id'],1));
			$this->set_variable("id",$row['id']);
			$this->set_variable("category",$row['name']);
			$this->set_variable("iab_id_category",$linked_category_id);
			$this->set_variable("keyword",$row['keyword']);
			$this->set_variable("description",$row['description']);
			$this->set_variable("exclusive_targeting",$row['exclusive_targeting']);


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

		$built_in_category=$db->read_single_column("select built_in_category from ".TABLE_PREFIX."categories where id=?",array($id));
		if($built_in_category==1)
		{
			$this->flash($this->get_message('category delete permission'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
			exit;
		}

		if(DEMO_MODE && $id <= 115)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('dispatch/category_targeting/2/0',ADMIN_DIR),0);
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

			$aid=$db->execute_query("select aid from ".TABLE_PREFIX."ad_category_mapping where catid=?", array($id));

			$res=$db->execute_query("delete from ".TABLE_PREFIX."ad_category_mapping where catid=?",array($id));

			while($data1row=$aid->fetch_assoc())
			{
				$adcount=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ad_category_mapping where aid=?", array($data1row['aid']));

				if(intval($adcount) == 0)
                    		$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=1 WHERE id=?",array($data1row['aid']));

			}



			$db->execute_query("ALTER TABLE ".TABLE_PREFIX."ads_cache DROP COLUMN ?_category",array($id));

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
			if(DEMO_MODE && $aid <= 100)
			$alert_msg=$this->get_message('demo mode');
			else
			{
					$data1   = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE category_status = 1");

					$exclusive_targeting  = 0;
					$categoryParentID     = 0;
					$categoryParentIDTemp = 0;
					$noUpdationFlag       = 0;

					while($data11row = $data1->fetch_assoc())
					{
							if(isset($_POST['ca'.$data11row['id']]) && $_POST['ca'.$data11row['id']]==1)
							{
									$parentID = $data11row['pid'];

									if($parentID == 0)
									$exclusive_targeting = intval($data11row['exclusive_targeting']);
									else
									$exclusive_targeting = CategoryHelper::get_parent_category_exclusive($parentID);

									if($exclusive_targeting == 1)
									break;
							}
					}

					$data1->set_result_index();


					if($exclusive_targeting == 1)
					{
							while($data111row = $data1->fetch_assoc())
							{
									if(isset($_POST['ca'.$data111row['id']]) && $_POST['ca'.$data111row['id']]==1)
									{
											$parentID = $data111row['pid'];

											if($parentID == 0)
											$categoryParentID = intval($data111row['id']);
											else
											$categoryParentID = CategoryHelper::get_last_parent_category_id($parentID);


											if($categoryParentIDTemp > 0 && $categoryParentIDTemp != $categoryParentID)
											{
													$alert_msg      = $this->get_message('system does not support multiple categories');

													$noUpdationFlag = 1;
											}

											$categoryParentIDTemp = $categoryParentID;
									}
							}
					}

					$data1->set_result_index();


					if($noUpdationFlag == 0)
					{
				$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? AND catid <>0",array($aid));

		        while($result=$res->fetch_assoc())
		        {
		        	$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($result['catid'],0,$aid));
		        }

			    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=1 WHERE id=?",array($aid));

				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));


				$count=0;
				while($data1row=$data1->fetch_assoc())
				{
					if(isset($_POST['ca'.$data1row['id']]) && $_POST['ca'.$data1row['id']]==1)
					{
						$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_category_mapping (uid,aid,catid) VALUES (?,?,?)",array(0,$aid,$data1row['id']));
						$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($data1row['id'],1,$aid));
						$count=1;
					}
				}
		        	if($count == 1)
				$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=0 WHERE id=?",array($aid));

				$alert_msg=$this->get_message('successfully updated the category targeting');
			}
		    }
		}


		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=0 AND category_status = 1 ORDER BY name");
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


	function category_targeting_dsp_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();

		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		$aid=$this->read_page_param(1);

		if(!$this->get_ad_validation_admin($aid))
		{
			$this->flash($this->get_message('invalid id'),$this->make_base_url('dispatch/dsp-connector/manage',ADMIN_DIR),0);
			exit;
		}


		$alert_msg='';
		if($_POST)
		{
			if(DEMO_MODE && $aid <= 100)
			$alert_msg=$this->get_message('demo mode');
			else
			{
					$data1   = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE category_status = 1");

					$exclusive_targeting  = 0;
					$categoryParentID     = 0;
					$categoryParentIDTemp = 0;
					$noUpdationFlag       = 0;

					while($data11row = $data1->fetch_assoc())
					{
							if(isset($_POST['ca'.$data11row['id']]) && $_POST['ca'.$data11row['id']]==1)
							{
									$parentID = $data11row['pid'];

									if($parentID == 0)
									$exclusive_targeting = intval($data11row['exclusive_targeting']);
									else
									$exclusive_targeting = CategoryHelper::get_parent_category_exclusive($parentID);

									if($exclusive_targeting == 1)
									break;
							}
					}

					$data1->set_result_index();


					if($exclusive_targeting == 1)
					{
							while($data111row = $data1->fetch_assoc())
							{
									if(isset($_POST['ca'.$data111row['id']]) && $_POST['ca'.$data111row['id']]==1)
									{
											$parentID = $data111row['pid'];

											if($parentID == 0)
											$categoryParentID = intval($data111row['id']);
											else
											$categoryParentID = CategoryHelper::get_last_parent_category_id($parentID);


											if($categoryParentIDTemp > 0 && $categoryParentIDTemp != $categoryParentID)
											{
													$alert_msg      = $this->get_message('system does not support multiple categories');

													$noUpdationFlag = 1;
											}

											$categoryParentIDTemp = $categoryParentID;
									}
							}
					}

					$data1->set_result_index();


					if($noUpdationFlag == 0)
					{
				$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? AND catid <>0",array($aid));

		        while($result=$res->fetch_assoc())
		        {
		        	$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($result['catid'],0,$aid));
		        }

			    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=1 WHERE id=?",array($aid));

				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));


				$count=0;
				while($data1row=$data1->fetch_assoc())
				{
					if(isset($_POST['ca'.$data1row['id']]) && $_POST['ca'.$data1row['id']]==1)
					{
						$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_category_mapping (uid,aid,catid) VALUES (?,?,?)",array(0,$aid,$data1row['id']));
						$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($data1row['id'],1,$aid));
						$count=1;
					}
				}
		        if($count == 1)
				$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=0 WHERE id=?",array($aid));

				$alert_msg=$this->get_message('successfully updated the category targeting');
			}
		    }
		}


		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=0 AND category_status = 1 ORDER BY name");
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


	function category_targeting_feed_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();

		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		$aid=$this->read_page_param(1);

		if(!$this->get_ad_validation_admin($aid))
		{
			$this->flash($this->get_message('invalid id'),$this->make_base_url('dispatch/feed-ads/manage',ADMIN_DIR),0);
			exit;
		}


		$alert_msg='';
		if($_POST)
		{
			if(DEMO_MODE && $aid <= 100)
			$alert_msg=$this->get_message('demo mode');
			else
			{
					$data1   = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE category_status = 1");

					$exclusive_targeting  = 0;
					$categoryParentID     = 0;
					$categoryParentIDTemp = 0;
					$noUpdationFlag       = 0;

					while($data11row = $data1->fetch_assoc())
					{
							if(isset($_POST['ca'.$data11row['id']]) && $_POST['ca'.$data11row['id']]==1)
							{
									$parentID = $data11row['pid'];

									if($parentID == 0)
									$exclusive_targeting = intval($data11row['exclusive_targeting']);
									else
									$exclusive_targeting = CategoryHelper::get_parent_category_exclusive($parentID);

									if($exclusive_targeting == 1)
									break;
							}
					}

					$data1->set_result_index();


					if($exclusive_targeting == 1)
					{
							while($data111row = $data1->fetch_assoc())
							{
									if(isset($_POST['ca'.$data111row['id']]) && $_POST['ca'.$data111row['id']]==1)
									{
											$parentID = $data111row['pid'];

											if($parentID == 0)
											$categoryParentID = intval($data111row['id']);
											else
											$categoryParentID = CategoryHelper::get_last_parent_category_id($parentID);


											if($categoryParentIDTemp > 0 && $categoryParentIDTemp != $categoryParentID)
											{
													$alert_msg      = $this->get_message('system does not support multiple categories');

													$noUpdationFlag = 1;
											}

											$categoryParentIDTemp = $categoryParentID;
									}
							}
					}

					$data1->set_result_index();


					if($noUpdationFlag == 0)
					{
				$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? AND catid <>0",array($aid));

		        while($result=$res->fetch_assoc())
		        {
		        	$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($result['catid'],0,$aid));
		        }

			    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=1 WHERE id=?",array($aid));

				$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));


				$count=0;
				while($data1row=$data1->fetch_assoc())
				{
					if(isset($_POST['ca'.$data1row['id']]) && $_POST['ca'.$data1row['id']]==1)
					{
						$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_category_mapping (uid,aid,catid) VALUES (?,?,?)",array(0,$aid,$data1row['id']));
						$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($data1row['id'],1,$aid));
						$count=1;
					}
				}
		        if($count == 1)
				$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=0 WHERE id=?",array($aid));

				$alert_msg=$this->get_message('successfully updated the category targeting');
			}
		    }
		}


		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=0 AND category_status = 1 ORDER BY name");
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

		$operation_message = "";
		if($_POST)
		{
			if(DEMO_MODE && $aid <= 100)
			$operation_message = $this->get_message('demo mode');
			else
			{
					$data1   = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE category_status = 1");

					$exclusive_targeting  = 0;
					$categoryParentID     = 0;
					$categoryParentIDTemp = 0;
					$noUpdationFlag       = 0;

					while($data11row = $data1->fetch_assoc())
					{
							if(isset($_POST['ca'.$data11row['id']]) && $_POST['ca'.$data11row['id']]==1)
							{
									$parentID = $data11row['pid'];

									if($parentID == 0)
									$exclusive_targeting = intval($data11row['exclusive_targeting']);
									else
									$exclusive_targeting = CategoryHelper::get_parent_category_exclusive($parentID);

									if($exclusive_targeting == 1)
									break;
							}
					}

					$data1->set_result_index();


					if($exclusive_targeting == 1)
					{
							while($data111row = $data1->fetch_assoc())
							{
									if(isset($_POST['ca'.$data111row['id']]) && $_POST['ca'.$data111row['id']]==1)
									{
											$parentID = $data111row['pid'];

											if($parentID == 0)
											$categoryParentID = intval($data111row['id']);
											else
											$categoryParentID = CategoryHelper::get_last_parent_category_id($parentID);


											if($categoryParentIDTemp > 0 && $categoryParentIDTemp != $categoryParentID)
											{
													$operation_message = $this->get_message('system does not support multiple categories');

													$noUpdationFlag = 1;
											}

											$categoryParentIDTemp = $categoryParentID;
									}
							}
					}

					$data1->set_result_index();


					if($noUpdationFlag == 0)
					{
						    $res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=? AND catid <>0",array($aid));
						    while($result=$res->fetch_assoc())
						    {
						        $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($result['catid'],0,$aid));
						    }

					      $db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_category_mapping WHERE aid=?",array($aid));
						    $db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=b'?' WHERE id=?",array(0,$aid));


								$count=0;
								while($data1row = $data1->fetch_assoc())
								{
										if(isset($_POST['ca'.$data1row['id']]) && $_POST['ca'.$data1row['id']]==1)
										{
											$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_category_mapping (uid,aid,catid) VALUES (?,?,?)",array($uid,$aid,$data1row['id']));
											$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ?_category=b'?' WHERE id=?",array($data1row['id'],1,$aid));
											$count=1;
										}
								}

								if($count==0)
								$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET all_categories=b'?' WHERE id=?",array(1,$aid));

								$operation_message = $this->get_message('successfully updated the category targeting');
						}
				}
		}


		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=0 AND category_status = 1 ORDER BY name");
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
		$this->set_variable('operation_message',$operation_message);
		$this->set_variable('aid',$aid);
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
?>
