<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class TimeController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="time_targeting")
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}
		
		
		if($this->get_action()=="time_targeting_admin")
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}
		
		
		
		
		
		if($this->get_addon_status('time-targeting_enabled') !=1)
		$this->flash($this->get_message('invalid'),BASE,0);
	}
	
		
	
	function time_targeting_admin_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		
		$aid=$this->read_page_param(1);
		
		if(!$this->get_ad_validation_user($aid))
		{
			$this->flash($this->get_message('invalid'), $this->make_base_url('index/control_panel',ADMIN_DIR),0);
		}
		
		
				
			$result=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_time_mapping WHERE aid=?",array($aid));
			$dbcount=$result->get_num_records();
			
			$data=$result->fetch_assoc();
			
			
			
			if($dbcount >0)
			{
				
			$this->set_variable('dbcount',$dbcount);
			
			$datefilter=$data['date_filter'];
			$date_period=$data['date_period'];
			$timefilter=$data['time_filter'];
			$starttime=$data['start_time'];
			$endtime=$data['end_time'];
			$dayfilter=$data['day_filter'];
			$startday=$data['start_day'];
			$endday=$data['end_day'];
			$time_period1=$data['time_period1'];
			$time_period2=$data['time_period2'];
			$time_period3=$data['time_period3'];
			$time_period4=$data['time_period4'];
			$time_period5=$data['time_period5'];
			$day_period1=$data['day_period1'];
			$day_period2=$data['day_period2'];
			$day_period3=$data['day_period3'];
			$day_period4=$data['day_period4'];
			$day_period5=$data['day_period5'];
			$day_period6=$data['day_period6'];
			$day_period7=$data['day_period7'];
			
			$startdatedb=$data['start_date'];
			$enddatedb=$data['end_date'];
			
			if(($datefilter ==1 || $datefilter ==2) && $startdatedb >0 && $enddatedb >0)
			{
				$startdate=date("d",$startdatedb).'/'.date("m",$startdatedb).'/'.date("Y",$startdatedb);
				$enddate=date("d",$enddatedb).'/'.date("m",$enddatedb).'/'.date("Y",$enddatedb);
			}
			else
			{
				$startdate='';
				$enddate='';
			}
			
	
			
			$dbadstatus=$db->read_single_column("SELECT status FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));
			
			$this->set_variable('dbadstatus',$dbadstatus);
			
			$this->set_variable('datefilter',$datefilter);
			$this->set_variable('startdate',$startdate);
			$this->set_variable('enddate',$enddate);
			$this->set_variable('date_period',$date_period);
			
			
			
			$this->set_variable('timefilter',$timefilter);
			$this->set_variable('starttime',$starttime);
			$this->set_variable('endtime',$endtime);
			$this->set_variable('time_period1',$time_period1);
			$this->set_variable('time_period2',$time_period2);
			$this->set_variable('time_period3',$time_period3);
			$this->set_variable('time_period4',$time_period4);
			$this->set_variable('time_period5',$time_period5);
			
			
			$this->set_variable('dayfilter',$dayfilter);
			$this->set_variable('startday',$startday);
			$this->set_variable('endday',$endday);
			$this->set_variable('day_period1',$day_period1);
			$this->set_variable('day_period2',$day_period2);
			$this->set_variable('day_period3',$day_period3);
			$this->set_variable('day_period4',$day_period4);
			$this->set_variable('day_period5',$day_period5);
			$this->set_variable('day_period6',$day_period6);
			$this->set_variable('day_period7',$day_period7);
			
			
			}
			else
				$this->set_variable('dbcount',$dbcount);
			
				
		
		$adname=$this->get_ad_name($aid);
		$this->set_variable('adname',$adname);
		
	}
	
	
	
	
	
	function time_targeting_action()
	{
		$this->disable_notice_area();
		$db= DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		
		if($_POST)
		$aid=$this->read_post_param('aid');
		else
		$aid=$this->read_page_param(1);
	
	
		if(!$this->get_your_ad($aid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_base_url('ad/list'),0);
		}
		
		$id=0;
		$message="";
		$messageflag=0;
		if($_POST)
		{
			
			 $id=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."ad_time_mapping WHERE uid=? AND aid=?",array($uid,$aid));
			
			 if(Configuration::get_instance()->read('date_filter_enabled') ==1)
			 {
			 	$datefilter=$this->read_post_param('datefilter');
			 	$startdate=$this->read_post_param('startdate');
			 	$enddate=$this->read_post_param('enddate');
			 	$date_period=$this->read_post_param('date_period');
			 	

			 	$newstart='';
			 	$newend='';
			 	
			 	if($datefilter ==0)
			 	{
			 		$startdate='';
			 		$enddate='';
			 		$date_period=0;
			 		
			 	}
			 	
			 	
			 	if($datefilter ==1)
			 	{
			 		$date_period=0;
			 		
			 		
			 		$startdatearray=explode('/',$startdate); // ********* day/month/year Format ***//
			 		$enddatearray=explode('/',$enddate); // ********* day/month/year Format ***//
			 		
			 		$newstart=mktime(0,0,0,$startdatearray[1],$startdatearray[0],$startdatearray[2]);
			 		$newend=mktime(0,0,0,$enddatearray[1],$enddatearray[0]+1,$enddatearray[2]);
			 		
			 		$currentdate=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
			 		
			 		if($newstart < $currentdate || $newend < $currentdate || $newend <= $newstart)
			 		{
			 			$messageflag=1;
			 			$message=$this->get_message('invalid start and end date');
			 		}
			 		
			 		
			 		
			 		
			 		
			 		
			 	}
			 	
			 	if($datefilter ==2)
			 	{
			 		$startdate='';
			 		$enddate='';
			 		
			 		$newvalue=(7*$datefilter)+1;
			 		
			 		$newstart=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
			 		$newend=mktime(0,0,0,date("m",time()),date("d",time())+$newvalue,date("Y",time()));
			 		
			 	}
			 	
			 	
			 	if($messageflag ==0)
			 	{
				 	if($id =='')
				 	{
				 		$insert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_time_mapping (uid,aid,date_filter,date_period,start_date,end_date) VALUES (?,?,?,?,?,?)",array($uid,$aid,$datefilter,$date_period,$newstart,$newend));
				 		$id=$insert->get_last_id();
				 	}
				 	else 
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."ad_time_mapping SET date_filter=?,date_period=?,start_date=?,end_date=? WHERE uid=? AND aid=?",array($datefilter,$date_period,$newstart,$newend,$uid,$aid));
			 	}
			 	
			 }
			
			
			
			 if(Configuration::get_instance()->read('time_filter_enabled') ==1)
			 {
			 	$timefilter=$this->read_post_param('timefilter');
			 	
			 	
			 	if($timefilter ==0)
			 	{
			 		$starttime=0;
			 		$endtime=0;
			 		
			 		$time_period1=0;
			 		$time_period2=0;
			 		$time_period3=0;
			 		$time_period4=0;
			 		$time_period5=0;
			 	}
			 	
			 	if($timefilter ==1)
			 	{
			 		$starttime=$this->read_post_param('starttime');
			 		$endtime=$this->read_post_param('endtime');
			 		
			 		$time_period1=0;
			 		$time_period2=0;
			 		$time_period3=0;
			 		$time_period4=0;
			 		$time_period5=0;
			 	}
			 	
			 	if($timefilter ==2)
			 	{
			 		$starttime=0;
			 		$endtime=0;
			 		
			 		$time_period1=intval($this->read_post_param('time_period1'));
			 		$time_period2=intval($this->read_post_param('time_period2'));
			 		$time_period3=intval($this->read_post_param('time_period3'));
			 		$time_period4=intval($this->read_post_param('time_period4'));
			 		$time_period5=intval($this->read_post_param('time_period5'));
			 	}
			 	

			 	if($messageflag ==0)
			 	{
				 	if($id =='')
				 	{
				 		$insert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_time_mapping (uid,aid,time_filter,time_period1,time_period2,time_period3,time_period4,time_period5,start_time,end_time) VALUES (?,?,?,?,?,?,?,?,?,?)",array($uid,$aid,$timefilter,$time_period1,$time_period2,$time_period3,$time_period4,$time_period5,$starttime,$endtime));
				 		$id=$insert->get_last_id();
				 	}
				 	else
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."ad_time_mapping SET time_filter=?,time_period1=?,time_period2=?,time_period3=?,time_period4=?,time_period5=?,start_time=?,end_time=? WHERE uid=? AND aid=?",array($timefilter,$time_period1,$time_period2,$time_period3,$time_period4,$time_period5,$starttime,$endtime,$uid,$aid));
			 	}
			 	
			 }
			
			
			
			 if(Configuration::get_instance()->read('day_filter_enabled') ==1)
			 {
			 
			 	$dayfilter=$this->read_post_param('dayfilter');
			 	
			 	if($dayfilter ==0)
			 	{
			 		$startday=0;
			 		$endday=0;
			 	
			 		$day_period1=0;
				 	$day_period2=0;
				 	$day_period3=0;
				 	$day_period4=0;
				 	$day_period5=0;
				 	$day_period6=0;
				 	$day_period7=0;
			 	}
			 	
			 	if($dayfilter ==1)
			 	{
			 		$startday=$this->read_post_param('startday');
			 		$endday=$this->read_post_param('endday');
			 		
			 		$day_period1=0;
				 	$day_period2=0;
				 	$day_period3=0;
				 	$day_period4=0;
				 	$day_period5=0;
				 	$day_period6=0;
				 	$day_period7=0;
			 	}
			 	
			 	if($dayfilter ==2)
			 	{
			 		$startday=0;
			 		$endday=0;
			 		
			 		$day_period1=intval($this->read_post_param('day_period1'));
			 		$day_period2=intval($this->read_post_param('day_period2'));
			 		$day_period3=intval($this->read_post_param('day_period3'));
			 		$day_period4=intval($this->read_post_param('day_period4'));
			 		$day_period5=intval($this->read_post_param('day_period5'));
			 		$day_period6=intval($this->read_post_param('day_period6'));
			 		$day_period7=intval($this->read_post_param('day_period7'));
			 	}
			 	
			 	
			 	
			 	if($messageflag ==0)
			 	{
				 	if($id =='')
				 	{
				 		$insert=$db->execute_query("INSERT INTO ".TABLE_PREFIX."ad_time_mapping (uid,aid,day_filter,day_period1,day_period2,day_period3,day_period4,day_period5,day_period6,day_period7,start_day,end_day) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",array($uid,$aid,$dayfilter,$day_period1,$day_period2,$day_period3,$day_period4,$day_period5,$day_period6,$day_period7,$startday,$endday));
				 		$id=$insert->get_last_id();
				 	}
				 	else
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."ad_time_mapping SET day_filter=?,day_period1=?,day_period2=?,day_period3=?,day_period4=?,day_period5=?,day_period6=?,day_period7=?,start_day=?,end_day=? WHERE uid=? AND aid=?",array($dayfilter,$day_period1,$day_period2,$day_period3,$day_period4,$day_period5,$day_period6,$day_period7,$startday,$endday,$uid,$aid));
			 	}
			 	
			 
			 }
			
			if($messageflag ==0) 
			$message=$this->get_message('time target update success');
		}
		else 
		{
			
			$result=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ad_time_mapping WHERE uid=? AND aid=?",array($uid,$aid));
			$data=$result->fetch_assoc();
			
			
			$datefilter=$data['date_filter'];
			$date_period=$data['date_period'];
			$timefilter=$data['time_filter'];
			$starttime=$data['start_time'];
			$endtime=$data['end_time'];
			$dayfilter=$data['day_filter'];
			$startday=$data['start_day'];
			$endday=$data['end_day'];
			$time_period1=$data['time_period1'];
			$time_period2=$data['time_period2'];
			$time_period3=$data['time_period3'];
			$time_period4=$data['time_period4'];
			$time_period5=$data['time_period5'];
			$day_period1=$data['day_period1'];
			$day_period2=$data['day_period2'];
			$day_period3=$data['day_period3'];
			$day_period4=$data['day_period4'];
			$day_period5=$data['day_period5'];
			$day_period6=$data['day_period6'];
			$day_period7=$data['day_period7'];
			
			$startdatedb=$data['start_date'];
			$enddatedb=$data['end_date'];
			
			if($datefilter ==1 && $startdatedb >0 && $enddatedb >0)
			{
				$startdate=date("d",$startdatedb).'/'.date("m",$startdatedb).'/'.date("Y",$startdatedb);
				$enddate=date("d",$enddatedb).'/'.date("m",$enddatedb).'/'.date("Y",$enddatedb);
			}
			else
			{
				$startdate='';
				$enddate='';
			}
		
		}
		
		
		
		
		
		$this->set_variable('datefilter',$datefilter);
		$this->set_variable('startdate',$startdate);
		$this->set_variable('enddate',$enddate);
		$this->set_variable('date_period',$date_period);
		
		
		
		$this->set_variable('timefilter',$timefilter);
		$this->set_variable('starttime',$starttime);
		$this->set_variable('endtime',$endtime);
		$this->set_variable('time_period1',$time_period1);
		$this->set_variable('time_period2',$time_period2);
		$this->set_variable('time_period3',$time_period3);
		$this->set_variable('time_period4',$time_period4);
		$this->set_variable('time_period5',$time_period5);
		
		
		$this->set_variable('dayfilter',$dayfilter);
		$this->set_variable('startday',$startday);
		$this->set_variable('endday',$endday);
		$this->set_variable('day_period1',$day_period1);
		$this->set_variable('day_period2',$day_period2);
		$this->set_variable('day_period3',$day_period3);
		$this->set_variable('day_period4',$day_period4);
		$this->set_variable('day_period5',$day_period5);
		$this->set_variable('day_period6',$day_period6);
		$this->set_variable('day_period7',$day_period7);
		
		
		
		
		
		
		
		$this->set_variable('aid',$aid);
		$this->set_variable("message", $message);
		
		
		
		
		if(isset($_COOKIE['my_locale']))
		$localname=$_COOKIE['my_locale'];
		else
		$localname=DEFAULT_LOCALE;
		
		
		$direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
		$direction=intval($direction);
		
		$this->set_variable('direction',$direction);
		
		
		
		
		
		
		
		
		
	}
};