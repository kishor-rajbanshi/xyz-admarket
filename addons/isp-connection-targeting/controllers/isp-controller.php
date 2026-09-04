<?php

include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class IspController extends ApplicationController
{

	function before_execute()
	{
		parent::before_execute();
		
		if($this->get_action()=="isp_display" || $this->get_action()=="delete" || $this->get_action()=="isp_targeting" || $this->get_action()=="advertiser_isp" || $this->get_action()=="ad_isp_report" )
		{ 
			if(!(LoginHelper::validate_user_login()))
			{
				$this->flash($this->get_message('login failed'), BASE,0);
				die;
			}
		}
		else if($this->get_action()=="targeting_view")
		{ 
			if(!(LoginHelper::validate_admin_login()))
			{
				$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
				die;
			}
		}
		if($this->get_addon_status('isp-targeting_enabled') !=1)
		{
			$this->flash($this->get_message('invalid'),BASE,0);
			die;
		}
	}
	
	
function isp_targeting_action()
	{
		set_time_limit(0);
		$query_limit=Configuration::get_instance()->read('db_query_execution_limit');
		
		//$this->disable_notice_area();
		$db= DAL::get_instance();
		$country='';
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$isp_arr=array();
		if($_POST)
		{
			
			 $aid=$this->read_post_param('aid');
			 $select_all=$this->read_post_param('select_all');
			 $country=$this->read_post_param('country');
			 $isp_ids=$this->read_post_param('isp_ids');
			
		}
		else
		{
			$aid=$this->read_page_param(1);
			$country=$this->read_page_param(2);
		}
		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_base_url('ad/list'),0);
		}
		
		$alert_msg='';
		if($_POST)
		{
			
			$insert_data=array();
			$valuestring="";


			$arraybatch=array();   // For Batch Insertion
			$iii=0;                   // For Batch Insertion
			$iiii=0;                  // For Batch Insertion
			
			
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_isp_mapping WHERE aid=? and country=?",array($aid,$country));
			$count=0;
			if($isp_ids!='')
					$isp_arr=explode(',', rtrim($isp_ids,','));
			 	$countisp=count($isp_arr);
				
				for($i=0;$i<$countisp;$i++)
				{
					
					if($valuestring !="")
						$valuestring.=',';
					$valuestring.="(?,?,?,?)";
						array_push($insert_data,$uid,$aid,$country,$isp_arr[$i]);
					
					
					/*********   For Batch Insertion   ********/
			
					$iiii++;
			
					if($iiii % $query_limit ==0 ||$countisp == $i+1)
					{
						$arraybatch[$iii][0]=$insert_data;
			
						$arraybatch[$iii][1]=$valuestring;
			
						$insert_data=array();
			
						$valuestring="";
			
						$iii++;
						$iiii=0;
					}
			
				}	/*********   For Batch Insertion   ********/		
	
				//print_r($arraybatch);die;
				foreach($arraybatch as $key=>$value)
				{	
					$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_isp_mapping (uid,aid,country,ispid) VALUES ".$value[1]." ",$value[0]);
					$count=1;
				}
			
		
			$alert_msg=$this->get_message('successfully updated the isp targeting');$this->set_variable('alert_msg',$alert_msg);
		}
	
		$data=$db->execute_query("SELECT m.*,s.name FROM ".TABLE_PREFIX."ad_isp_mapping m join  ".TABLE_PREFIX."isp s on m.ispid=s.id WHERE aid=? AND ispid <>0 order by m.id desc",array($aid));
		$num=$data->get_num_records();//die;
		
		$this->set_variable('alert_msg',$alert_msg);
		$this->set_variable('aid',$aid);
		$this->set_variable('country',$country);
		$this->set_variable('num',$num);
		if($num > 0)
		$this->set_result("res",$data);
		if(isset($_COOKIE['my_locale']))
			$localname=$_COOKIE['my_locale'];
		else
			$localname=DEFAULT_LOCALE;
		
		
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
		$cc=0;
		$cc=$db->read_single_column("SELECT  `country_code` FROM  `".TABLE_PREFIX."ad_geographic_mapping` WHERE  `aid` =? LIMIT 0 ,1",array($aid));
		if($cc!='' && $cc!=='0' )
		{
			$res_country=$db->execute_query("select code,name from ".TABLE_PREFIX."countries c left join ".TABLE_PREFIX."ad_geographic_mapping g  on c.code=g.country_code  where code!='A1' and code!='A2' and code!='AP' and code!='EU' and g.aid=? ORDER BY name ASC",array($aid));
			$this->set_result('res_country', $res_country);
			
		}
		else 
		{
        	$res_country=$db->execute_query("select code,name from ".TABLE_PREFIX."countries  where code!='A1' and code!='A2' and code!='AP' and code!='EU'  ORDER BY name ASC");
        	$this->set_result('res_country', $res_country);
        	
		}
		
		$this->set_result('res_country', $res_country);
	
		$this->set_variable('direction',$direction);
		
	}
	
	function  isp_display_action()
	{
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		$this->disable_notice_area();
		$db= DAL::get_instance();
		$cid=$this->read_page_param(1);
		$aid=$this->read_page_param(2);
	 	 $count=$db->read_single_column("SELECT count(*) FROM ".TABLE_PREFIX."isp  s WHERE country=? AND NOT EXISTS(SELECT * FROM ".TABLE_PREFIX."ad_isp_mapping m WHERE  m.ispid=s.id and m.aid=?) ORDER BY ct DESC",array($cid,$aid));
		
		if($count>1000)
			$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."isp  s WHERE country=? AND ct>10 AND NOT EXISTS(SELECT * FROM ".TABLE_PREFIX."ad_isp_mapping m WHERE  m.ispid=s.id and m.aid=?) ORDER BY ct DESC",array($cid,$aid));
		else 
			$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."isp  s WHERE country=? AND NOT EXISTS(SELECT * FROM ".TABLE_PREFIX."ad_isp_mapping m WHERE  m.ispid=s.id and m.aid=?) ORDER BY ct DESC",array($cid,$aid));
			
		//echo $row->get_sql();die;
		$str='';
		if($row->get_num_records())
		{
			
			$i=1;
			$str.='<div class="col-md-3 col-sm-6 col-xs-12 " style="padding: 3px;">
			<input type="checkbox" name="select_all" id="select_all" value="1">'.$this->get_label('select all').'
			</div><div style="clear:both;"></div>';
			$i=1;
		while($row_data=$row->fetch_assoc())
		{
			if($i==1){
			
			
			$str.='<div class="col-md-12 col-sm-12 col-xs-12 isp_div_outer padding-side">';
			
			 }
			
			$str.='<div style="float:left;padding: 3px;" class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><input type="checkbox"   class="ispchk"  id="isp'.$row_data['id'].'" value="'.$row_data['id'].'" ';
		
			
					$str.='/>&nbsp;&nbsp;'.$row_data['name']."</div>";
					if($i==20){
					
					$str.='<span id="morespan" style="float: right;cursor: pointer;font-size: 12px;position: relative;    top: 15px;    right: 30px;    font-weight: 600;" onclick="LoadMore();">'.$this->get_label('more'). ' &#9660;</span>
					</div><div id="morediv" style="display: none;" class="item_list">';
				 }
					
					$i=$i+1;
					
		}
		if($row->get_num_records()>20)
		{
		$str.='<span id="hidespan" style="float: right;cursor: pointer;font-size: 12px;position: relative;    top: 15px;    right: 30px;    font-weight: 600;" onclick="HideMore();">'.$this->get_label('hide').' &#9650;</span>
		</div>';
		}
		}
		else $str.='<div>'.$this->get_label("no records found").'</div>';
		echo $str;
		//die;
	}
	
	function delete_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		$id=$this->read_page_param(1);	
		$aid=$this->read_page_param(2);
		$country=$this->read_page_param(3);
		$db->execute_query("DELETE FROM ".TABLE_PREFIX."ad_isp_mapping WHERE id=?",array($id));
		
		$this->flash($this->get_message('isp targeting delete success'), $this->make_url('isp/isp_targeting/'.$aid.'/'.$country,BASE));
		exit;
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
		
		
		$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_isp_mapping WHERE aid=? AND ispid > 0",array($aid));
	
		$this->set_result("res",$res);
		
	    $numbers=$res->get_num_records();
		$this->set_variable("numbers",$numbers);
		
		
		
		$adname=$this->get_ad_name($aid);
		$this->set_variable('adname',$adname);
		
		
	}

	function targeting_view_marketplace_action()
	{
		
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
		$aid=$this->read_page_param(1);
		
		$res=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_isp_mapping WHERE aid=? AND ispid > 0",array($aid));
	
		$this->set_result("res",$res);
	
	    $numbers=$res->get_num_records();
		$this->set_variable("numbers",$numbers);
		
		$adname=$this->get_ad_name($aid);
		$this->set_variable('adname',$adname);
		
	}
	
};