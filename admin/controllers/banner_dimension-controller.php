<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";

class Banner_dimensionController extends ApplicationController
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
		$this->set_title($this->get_label('manage banner dimensions'));
		$db= DAL::get_instance();
	
		if($_POST)
		$status=intval($this->read_post_param("status"));
		else
		{
			$status=$this->read_page_param(1);
			
			$exp=explode("-",$status);
			if($exp[0] == "page")
			$status = 0;
		}
	
	
		if($status === "")
		$status=0;
	
		$adpricing_str="";
		

		
		if($this->get_addon_status('text-image-ads_enabled') !=1)
		{
			if($adpricing_str =="")
			$adpricing_str.=' WHERE ';
			else
			$adpricing_str.=' AND ';
			
			$adpricing_str.=' banner_type <>3 ';
		}		
		
		if($this->get_addon_status('skin-ads_enabled') !=1)
		{
			if($adpricing_str =="")
			$adpricing_str.=' WHERE ';
			else
			$adpricing_str.=' AND ';
			
			$adpricing_str.=' banner_type <>4 ';
		}		
		
		
		
		if($this->get_addon_status('interstitial_enabled') !=1)
		{
			if($adpricing_str =="")
			$adpricing_str.=' WHERE ';
			else
			$adpricing_str.=' AND ';
			
			$adpricing_str.=' banner_type <>1 ';
		}		

		if($status >0)
		{
			if($adpricing_str =="")
			$adpricing_str.=' WHERE ';
			else
			$adpricing_str.=' AND ';
			
		
			$adpricing_str.=' status='.intval($status).' ';
			
		}		

	
		$pagination = new Pagination("SELECT * FROM ".TABLE_PREFIX."banner_dimensions ".$adpricing_str."ORDER BY id DESC");
		$res=$pagination->get_result();
		$this->set_result("res",$res);
		$this->set_variable("pagination",$pagination->links(),0);
	
		$pg=$pagination->get_page_number();
		$this->set_variable("pg","page-".$pg);
	
		$this->set_variable("status",$status);
	}
	
	function delete_action()
	{
		$bid=$this->read_page_param(1);
		$status=$this->read_page_param(2);
		$pg=$this->read_page_param(3);
		
		
		if(DEMO_MODE && $bid <= 50)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('banner_dimension/list'),0);
			exit;
		}
		
		
		if(!$this->get_dimension_exists($bid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('banner_dimension/list'));
			exit;
		}
		
		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
		$pg="";
		
		if($pg =="")
		$pg="page-1";
		
		$db= DAL::get_instance();
	

		$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."ads where banner_id=?",array($bid));
		
		$count11=$db->read_single_column("select count(id) from ".TABLE_PREFIX."adblock where bannersize=? OR textimage_size=?",array($bid,$bid));
		
				
		if($count >0)
		$this->flash($this->get_message('cannot delete dimension'), $this->make_url('banner_dimension/list/'.$status.'/'.$pg),0);
		else if($count11 >0)
		$this->flash($this->get_message('adblocks using this dimension'), $this->make_url('banner_dimension/list/'.$status.'/'.$pg),0);
		else
		{
			if($this->get_addon_status('ecommerce-ads_enabled') ==1 || $this->get_addon_status('ecommerce-ads_enabled') ==0)
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."display_layout WHERE layout_banner=?",array($bid));
			
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."banner_dimensions WHERE id=?",array($bid));

			$this->flash($this->get_message('dimension deleted'), $this->make_url('banner_dimension/list/'.$status.'/'.$pg));
			exit;
		}		
	
	}
	
	function change_status_action()
	{
		$bid=$this->read_page_param(1);
		
		if(DEMO_MODE && $bid <= 50)
		{
			$this->flash($this->get_message('demo mode'), $this->make_url('banner_dimension/list'),0);
			exit;
		}
		
		
		if(!$this->get_dimension_exists($bid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('banner_dimension/list'));
			exit;
		}
		
		
		$status=$this->read_page_param(2);
		
		$oldstatus=$this->read_page_param(3);
		$pg=$this->read_page_param(4);
		
		$pgnew=explode("-",$pg);
		if($pgnew[0] !="page")
			$pg="";
		
		if($pg=="")
			$pg="page-1";
	
		$db= DAL::get_instance();
	
	
		if($status==1 || $status==2)
		$res=$db->execute_query("update ".TABLE_PREFIX."banner_dimensions set status=? where id=?",array($status,$bid));
			

		$this->flash($this->get_message('banner status updated'), $this->make_url('banner_dimension/list/'.$oldstatus.'/'.$pg));
	
	}
	function edit_action()
	{
		$this->set_title($this->get_label('edit banner dimension'));
	
	
		$db= DAL::get_instance();
	
		if($_POST)
		{
			$bid=$this->read_post_param('bid');
			$st=$this->read_post_param('st');
			$pg=$this->read_post_param('pg');
		}
		else
		{
			$bid=$this->read_page_param(1);
			$st=$this->read_page_param(2);
			$pg=$this->read_page_param(3);
		}
	
		
		if(!$this->get_dimension_exists($bid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('banner_dimension/list'));
			exit;
		}
		
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
		$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
		$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$html5_enabled=$this->get_addon_status('html5-ads_enabled');
		
		
		$this->set_variable('video_enabled',$video_enabled);
		$this->set_variable('ecommerce_enabled',$ecommerce_enabled);
		$this->set_variable('textimage_enabled',$textimage_enabled);
		$this->set_variable('expandable_enabled',$expandable_enabled);
		$this->set_variable('skin_enabled',$skin_enabled);
		$this->set_variable('html5_enabled',$html5_enabled);
		

		$alreadyads=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ads WHERE banner_id=? AND type=2",array($bid));
		
		
		$alreadyads_expandable = 0;
		
		if($expandable_enabled ==1)
		$alreadyads_expandable=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ads WHERE banner_id=? AND type=2 AND expandable=1",array($bid));
		
		$alreadyads_ecommerce=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ads WHERE banner_id=? AND type=7",array($bid));
				
		
		$alreadyads_interstitial=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ads WHERE banner_id=? AND type=5",array($bid));
		$alreadyads_textimage=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ads WHERE banner_id=? AND type=11",array($bid));
		$alreadyads_skin=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."ads WHERE banner_id=? AND type=14",array($bid));
		
		
		$this->set_variable('alreadyads',intval($alreadyads));	
		$this->set_variable('alreadyads_expandable',intval($alreadyads_expandable));	
		$this->set_variable('alreadyads_ecommerce',intval($alreadyads_ecommerce));	
		$this->set_variable('alreadyads_interstitial',intval($alreadyads_interstitial));			
		$this->set_variable('alreadyads_textimage',intval($alreadyads_textimage));	
		$this->set_variable('alreadyads_skin',intval($alreadyads_skin));		
		
		if($_POST)
		{
			
			if(DEMO_MODE && $bid <= 50)
			{
				$this->flash($this->get_message('demo mode'), $this->make_url('banner_dimension/list'),0);
				exit;
			}
			
			
			
			$height=$this->read_post_param('height');
			$width=$this->read_post_param('width');
			$filesize=$this->read_post_param('filesize');
			$banner_type=$this->read_post_param('banner_type');
			
	
			$this->set_variable('height',$height);
			$this->set_variable('width',$width);
			$this->set_variable('filesize',$filesize);
			$this->set_variable('banner_type',$banner_type);
			
			
			$imagead=1;
			$ecommercead=0;
			$videoad=0;
			$expandablead=0;
			$expandable_width=0;
			$expandable_height=0;	
			$html5_filesize=0;
			
			
			
			if($banner_type ==0 || $banner_type ==1 || $banner_type ==2)
			{
				$imagead=intval($this->read_post_param('imagead'));
				$ecommercead=intval($this->read_post_param('ecommercead'));
				$expandablead=intval($this->read_post_param('expandablead'));
				$expandable_width=intval($this->read_post_param('expandable_width'));
				$expandable_height=intval($this->read_post_param('expandable_height'));				
				$html5_filesize=intval($this->read_post_param('html5_filesize'));
							
				
				if($banner_type ==0 && $imagead ==1)
				$videoad=intval($this->read_post_param('videoad'));
			}

			
			if($banner_type ==1 || $banner_type ==2)
			$imagead=1;
						
			
	
	
			if($height=="" || $width=="" || $filesize =="")
			$this->set_notice("mandatory");
			else if($height <=0 || $width <=0 || $filesize <=0)
			$this->set_notice("invalid data");
			else if(!is_numeric($height) || !is_numeric($width) || !is_numeric($filesize))
			$this->set_notice("invalid data");
			else if($banner_type ==0 &&  $imagead ==1 && $expandable_enabled ==1 && $expandablead ==1 && ($expandable_width ==0 || $expandable_height ==0 || !is_numeric($expandable_width) || !is_numeric($expandable_height)))
			$this->set_notice("please enter expandable banner dimension");
			else if($db->read_single_column("select count(id) from ".TABLE_PREFIX."banner_dimensions where height=? and width=? AND banner_type=? and id<>?",array($height,$width,$banner_type,$bid))>0)
			$this->set_notice("dimension is exists");
			else if(($banner_type ==0 || $banner_type ==1 || $banner_type ==2) && $html5_enabled ==1 && ($html5_filesize ==0 || !is_numeric($html5_filesize)))
			$this->set_notice("please enter html5 filesize");			
			else if(($banner_type ==0 || $banner_type ==1 || $banner_type ==2) && $imagead ==0 && $ecommercead ==0)
			$this->set_notice("please select a supported ad type");			
			else
			{
				if($imagead ==0)
				{
					$expandable_width=0;
					$expandable_height=0;
					$expandablead=0;
				}				
				
				$res=$db->execute_query("UPDATE ".TABLE_PREFIX."banner_dimensions set height=?,width=?,filesize=?,image_support=?,ecommerce_support=?,vast_video_support=?,expandable_support=?,expandable_width=?,expandable_height=? where id=?",array($height,$width,$filesize,$imagead,$ecommercead,$videoad,$expandablead,$expandable_width,$expandable_height,$bid));
				
				if($html5_enabled ==1)
				$db->execute_query("UPDATE ".TABLE_PREFIX."banner_dimensions SET html5_filesize=? WHERE id=?",array($html5_filesize,$bid));
				
				
				if($res->error =="")
				{
					$this->flash($this->get_message('banner dimension edited'), $this->make_url('banner_dimension/list/'.$st.'/'.$pg));
					exit;
				}
				else
					$this->set_notice("error occured");
			}
			
			$this->set_variable('imagead',$imagead);
			$this->set_variable('ecommercead',$ecommercead);
			$this->set_variable('expandablead',$expandablead);
			$this->set_variable('expandable_width',$expandable_width);
			$this->set_variable('expandable_height',$expandable_height);
			$this->set_variable('html5_filesize',$html5_filesize);
			
		}
		else
		{
			$res=$db->execute_query("select * from ".TABLE_PREFIX."banner_dimensions where id=?",array($bid));
			$this->set_result("res",$res);
		}
	
		$this->set_variable('bid',$bid);
		$this->set_variable('st',$st);
		$this->set_variable('pg',$pg);
		
	}
	
	function create_action()
	{
		$this->set_title($this->get_label('new banner dimension'));
	
		$ecommerce_enabled=$this->get_addon_status('ecommerce-ads_enabled');
		$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
		$video_enabled=$this->get_addon_status('video-ads_enabled');
		$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');
		$skin_enabled=$this->get_addon_status('skin-ads_enabled');
		$html5_enabled=$this->get_addon_status('html5-ads_enabled');
		
		
		$this->set_variable('ecommerce_enabled',$ecommerce_enabled);
		$this->set_variable('textimage_enabled',$textimage_enabled);
		$this->set_variable('expandable_enabled',$expandable_enabled);
		$this->set_variable('video_enabled',$video_enabled);
		$this->set_variable('skin_enabled',$skin_enabled);
		$this->set_variable('html5_enabled',$html5_enabled);
		
		$imagead=1;		
		$ecommercead=0;
		$videoad=0;
		
		$expandablead=0;
		$expandable_width=0;
		$expandable_height=0;		
		
		$html5_filesize=0;
		
		
		$db= DAL::get_instance();
		if($_POST)
		{
			$height=$this->read_post_param('height');
			$width=$this->read_post_param('width');
			$filesize=$this->read_post_param('filesize');
			$banner_type=$this->read_post_param('banner_type');
			
			
			if($banner_type ==0 || $banner_type ==1 || $banner_type ==2)
			{
				$imagead=intval($this->read_post_param('imagead'));
				$ecommercead=intval($this->read_post_param('ecommercead'));
				$html5_filesize=intval($this->read_post_param('html5_filesize'));
				
				
				$expandablead=intval($this->read_post_param('expandablead'));
				$expandable_width=intval($this->read_post_param('expandable_width'));
				$expandable_height=intval($this->read_post_param('expandable_height'));				
				
				if($banner_type ==1 || $banner_type ==2)
				$imagead=1;
				
				if($banner_type ==0 && $imagead ==1)
				$videoad=intval($this->read_post_param('videoad'));
				
				if($imagead ==0)
				{
					$expandable_width=0;
					$expandable_height=0;
					$expandablead=0;
				}				
				
			}

	
			if($height =="" || $width =="" || $filesize =="")
			$this->set_notice("mandatory");
			else if($height <= 0 || $width <= 0 || $filesize <=0)
			$this->set_notice("invalid data");
			else if(!is_numeric($height) || !is_numeric($width) || !is_numeric($filesize))
			$this->set_notice("invalid data");
			else if(($banner_type ==0 || $banner_type ==1 || $banner_type ==2) && $imagead ==0 && $ecommercead ==0)
			$this->set_notice("please select a supported ad type");		

			else if(($banner_type ==0 || $banner_type ==1 || $banner_type ==2) && $html5_enabled ==1 && ($html5_filesize ==0 || !is_numeric($html5_filesize)))
			$this->set_notice("please enter html5 filesize");			
			
			else if($banner_type ==0 && $imagead ==1 && $expandable_enabled ==1 && $expandablead ==1 && ($expandable_width ==0 || $expandable_height ==0 || !is_numeric($expandable_width) || !is_numeric($expandable_height)))
			$this->set_notice("please enter expandable banner dimension");			
			
			
			
			else if($db->read_single_column("select count(id) from ".TABLE_PREFIX."banner_dimensions where height=? and width=? AND banner_type=?",array($height,$width,$banner_type))>0)
			$this->set_notice("dimension is exists");
			else
			{
				$res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."banner_dimensions (height,width,filesize,status,image_support,ecommerce_support,banner_type,vast_video_support,expandable_support,expandable_width,expandable_height) values (?,?,?,?,?,?,?,?,?,?,?)",array($height,$width,$filesize,1,$imagead,$ecommercead,$banner_type,$videoad,$expandablead,$expandable_width,$expandable_height));
				
				$lastid=$res->get_last_id();

				if($html5_enabled ==1)
				$db->execute_query("UPDATE ".TABLE_PREFIX."banner_dimensions SET html5_filesize=? WHERE id=?",array($html5_filesize,$lastid));

				if($res->error =="")
				{
					$this->flash($this->get_message('banner dimension created'), $this->make_url('banner_dimension/list'));
					exit;
				}
				else
					$this->set_notice("error occured");
			}
			
			
			
			if($width ==0)
			$width="";
			
			if($height ==0)
			$height="";
			
			if($filesize ==0)
			$filesize="";
			
			if($expandable_width ==0)
			$expandable_width="";
			
			if($expandable_height ==0)
			$expandable_height="";			
			
			if($html5_filesize ==0)
			$html5_filesize="";			
			
			$this->set_variable('height',$height);
			$this->set_variable('width',$width);
			$this->set_variable('filesize',$filesize);
			$this->set_variable('html5_filesize',$html5_filesize);
			
			$this->set_variable('banner_type',$banner_type);
			
			$this->set_variable('expandablead',$expandablead);
			$this->set_variable('expandable_width',$expandable_width);
			$this->set_variable('expandable_height',$expandable_height);			
			
		}
		
		
		
		$this->set_variable('ecommercead',$ecommercead);
		$this->set_variable('videoad',$videoad);
		$this->set_variable('imagead',$imagead);
	}
};	
?>
