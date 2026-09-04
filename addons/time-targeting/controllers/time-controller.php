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



			$result=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE id=?",array($aid));

			$dbcount=$result->get_num_records();

			$data=$result->fetch_assoc();



			if($dbcount >0)
			{

			$this->set_variable('dbcount',$dbcount);

			$datefilter=$data['date_filter'];
			$date_period=$data['date_period'];
			$timefilter=$data['time_filter'];
			$starttime=$data['tmt_start_time'];
			$endtime=$data['tmt_end_time'];
			$dayfilter=$data['day_filter'];
			$startday=$data['tmt_start_day'];
			$endday=$data['tmt_end_day'];
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

			$startdatedb=$data['tmt_start_date'];
			$enddatedb=$data['tmt_end_date'];

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

		$id          = 0;
		$message     = "";
		$messageflag = 0;

		if($_POST)
		{
			if(DEMO_MODE && $aid <= 100)
			$message = $this->get_message('demo mode');
			else
			{

			 if(Configuration::get_instance()->read('date_filter_enabled') ==1)
			 {
			 	$datefilter=$this->read_post_param('datefilter');
			 	$startdate=$this->read_post_param('startdate');
			 	$enddate=$this->read_post_param('enddate');
			 	$date_period=$this->read_post_param('date_period');


			 	$newstart='';
			 	$newend='';
			 	$ads_string="";

			 	if($datefilter ==0)
			 	{
			 		$startdate='';
			 		$enddate='';
			 		$date_period=0;

			 		$ads_string="date_filter=b'?',tmt_start_date=?,tmt_end_date=?";
			 		$ads_arr[]=0;
			 		$ads_arr[]=0;
			 		$ads_arr[]=0;
			 		$ads_arr[]=$uid;
			 		$ads_arr[]=$aid;

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

			 		$ads_string="date_filter=b'?',tmt_start_date=?,tmt_end_date=?";

			 		$ads_arr[]=1;
			 		$ads_arr[]=$newstart;
			 		$ads_arr[]=$newend;
			 		$ads_arr[]=$uid;
			 		$ads_arr[]=$aid;

			 	}

			 	if($datefilter ==2)
			 	{
			 		$startdate='';
			 		$enddate='';

			 		$newvalue=(7*$datefilter)+1;

			 		$newstart=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
			 		$newend=mktime(0,0,0,date("m",time()),date("d",time())+$newvalue,date("Y",time()));


			 		$ads_string="date_filter=b'?',tmt_start_date=?,tmt_end_date=?";

			 		$ads_arr[]=1;
			 		$ads_arr[]=$newstart;
			 		$ads_arr[]=$newend;
			 		$ads_arr[]=$uid;
			 		$ads_arr[]=$aid;

			 	}


			 	if($messageflag ==0)
			 	{

				 	$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET date_filter=?,date_period=?,tmt_start_date=?,tmt_end_date=? WHERE uid=? AND id=?",array($datefilter,$date_period,$newstart,$newend,$uid,$aid));

				 	$abc=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ".$ads_string." WHERE uid=? AND id=?",$ads_arr);

			 	}

			 }



			 if(Configuration::get_instance()->read('time_filter_enabled') ==1)
			 {
			 	$timefilter=$this->read_post_param('timefilter');

			 	$ads_time_arr=array();
			 	if($timefilter ==0)
			 	{

			 		$starttime=0;
			 		$endtime=0;

			 		$time_period1=0;
			 		$time_period2=0;
			 		$time_period3=0;
			 		$time_period4=0;
			 		$time_period5=0;

			 		$ads_string_time="time_filter=b'?'";
			 		$ads_time_arr[]=0;
			 		for($i=0;$i<=23;$i++)
			 		{
			 		    $ads_string_time.=",".$i."_hour=b'?'";
			 		    $ads_time_arr[]=0;
			 		}

			 		$ads_time_arr[]=$uid;
			 		$ads_time_arr[]=$aid;
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

			 		$ads_string_time="time_filter=b'?'";
			 		$ads_time_arr[]=1;


			 		for($i=0;$i<=23;$i++)
			 		{

			 		    $ads_string_time.=",".$i."_hour=b'?'";
			 		    if($starttime<=$endtime)
			 		    {
			 		        if($i>=$starttime && $i<=$endtime)
			 		        $ads_time_arr[]=1;
			 		        else
			 		        $ads_time_arr[]=0;
			 		    }

			 		    else if($starttime>$endtime)
			 		    {
			 		        if($starttime<=$i || $i<=$endtime)
			 		        $ads_time_arr[]=1;
			 		        else
			 		        $ads_time_arr[]=0;
			 		    }

			 		}


			 		$ads_time_arr[]=$uid;
			 		$ads_time_arr[]=$aid;



			 	}



			 	if($timefilter ==2)
			 	{
			 		$starttime=0;
			 		$endtime=0;
			 		$flag=0;
			 		$time_period1=intval($this->read_post_param('time_period1'));
			 		$time_period2=intval($this->read_post_param('time_period2'));
			 		$time_period3=intval($this->read_post_param('time_period3'));
			 		$time_period4=intval($this->read_post_param('time_period4'));
			 		$time_period5=intval($this->read_post_param('time_period5'));


			 		if($time_period1 ==1)
			 		{
			 		    $flag=1;
			 		    $ads_string_time=",6_hour=1,7_hour=1,8_hour=1";
			 		}
			 		else
			 		{
			 		    $ads_string_time=",6_hour=0,7_hour=0,8_hour=0";
			 		}
			 		if($time_period2 ==1)
			 		{
			 		    $flag=1;
			 		    $ads_string_time.=",9_hour=1,10_hour=1,11_hour=1";
			 		}
			 		else
			 		{
			 		    $ads_string_time.=",9_hour=0,10_hour=0,11_hour=0";
			 		}
			 		if($time_period3 ==1)
			 		{
			 		    $flag=1;
			 		    $ads_string_time.=",12_hour=1,13_hour=1,14_hour=1,15_hour=1";
			 		}
			 		else
			 		{
			 		    $ads_string_time.=",12_hour=0,13_hour=0,14_hour=0,15_hour=0";
			 		}
			 		if($time_period4 ==1)
			 		{
			 		    $flag=1;
			 		    $ads_string_time.=",16_hour=1,17_hour=1,18_hour=1,19_hour=1";
			 		}
			 		else
			 		{
			 		    $ads_string_time.=",16_hour=0,17_hour=0,18_hour=0,19_hour=0";

			 		}
			 		if($time_period5 ==1)
			 		{
			 		    $flag=1;
			 		    $ads_string_time.=",20_hour=1,21_hour=1,22_hour=1,23_hour=1,0_hour=1,1_hour=1,2_hour=1,3_hour=1,4_hour=1,5_hour=1";
			 		}
			 		else
			 		{
			 		    $ads_string_time.=",20_hour=0,21_hour=0,22_hour=0,23_hour=0,0_hour=0,1_hour=0,2_hour=0,3_hour=0,4_hour=0,5_hour=0";

			 		}

			 		if($flag==0)
			 		    $ads_string_time="time_filter=0".$ads_string_time;
			 		else
			 		    $ads_string_time="time_filter=1".$ads_string_time;

			 		$ads_time_arr[]=$uid;
			 		$ads_time_arr[]=$aid;


			 	}

			 	if($messageflag ==0)
			 	{

				 	$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET time_filter=?,time_period1=?,time_period2=?,time_period3=?,time_period4=?,time_period5=?,tmt_start_time=?,tmt_end_time=? WHERE uid=? AND id=?",array($timefilter,$time_period1,$time_period2,$time_period3,$time_period4,$time_period5,$starttime,$endtime,$uid,$aid));

				 	$bc=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache set ".$ads_string_time." WHERE uid=? AND id=?",$ads_time_arr);

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

				 	$ads_string_day="day_filter=0,1_day=0,2_day=0,3_day=0,4_day=0,5_day=0,6_day=0,7_day=0";
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


				 	$ads_string_day="day_filter=b'?'";
				 	$ads_day_arr[]=1;
				 	for($i=1;$i<=7;$i++)
				 	{

				 	    $ads_string_day.=",".$i."_day=b'?'";
				 	    if($startday<=$endday)
				 	    {
				 	        if($i>=$startday && $i<=$endday)
				 	        $ads_day_arr[]=1;
				 	        else
				 	        $ads_day_arr[]=0;
				 	    }

				 	    else if($startday>$endday)
				 	    {
				 	        if($startday<=$i || $i<=$endday)
				 	        $ads_day_arr[]=1;
				 	        else
				 	        $ads_day_arr[]=0;
				 	    }

				 	}

			 	}

			 	if($dayfilter ==2)
			 	{
			 		$startday=0;
			 		$endday=0;
			 		$flag1=0;

			 		$day_period1=intval($this->read_post_param('day_period1'));
			 		$day_period2=intval($this->read_post_param('day_period2'));
			 		$day_period3=intval($this->read_post_param('day_period3'));
			 		$day_period4=intval($this->read_post_param('day_period4'));
			 		$day_period5=intval($this->read_post_param('day_period5'));
			 		$day_period6=intval($this->read_post_param('day_period6'));
			 		$day_period7=intval($this->read_post_param('day_period7'));


			 		if($day_period1 ==1)
			 		{
			 		    $flag1=1;
			 		    $ads_string_day.="1_day=1,";
			 		}
			 		else
			 		    $ads_string_day.="1_day=0,";

		 		    if($day_period2 ==1)
		 		    {
		 		        $flag1=1;
		 		        $ads_string_day.="2_day=1,";
		 		    }
		 		    else
		 		        $ads_string_day.="2_day=0,";

	 		        if($day_period3 ==1)
	 		        {
	 		             $flag1=1;
		 		         $ads_string_day.="3_day=1,";
	 		        }
		 		    else
		 		         $ads_string_day.="3_day=0,";
		 		    if($day_period4 ==1)
		 		    {
		 		         $flag1=1;
		 		         $ads_string_day.="4_day=1,";
		 		    }
		 		     else
		 		         $ads_string_day.="4_day=0,";

		 		    if($day_period5 ==1)
		 		    {
		 		         $flag1=1;
		 		         $ads_string_day.="5_day=1,";
		 		    }
		 		     else
		 		         $ads_string_day.="5_day=0,";

		 		     if($day_period6 ==1)
		 		     {
		 		         $flag1=1;
		 		         $ads_string_day.="6_day=1,";
		 		     }
		 		     else
		 		         $ads_string_day.="6_day=0,";

		 		     if($day_period7 ==1)
		 		     {
		 		         $flag1=1;
		 		         $ads_string_day.="7_day=1";
		 		     }
		 		     else
		 		         $ads_string_day.="7_day=0";

		 		     if($flag1==0)
		 		         $ads_string_day="day_filter=0,".$ads_string_day;
		 		     else
		 		         $ads_string_day="day_filter=1,".$ads_string_day;

			 	}
			 	$ads_day_arr[]=$uid;
			 	$ads_day_arr[]=$aid;


			 	if($messageflag ==0)
			 	{
				 		$db->execute_query("UPDATE ".TABLE_PREFIX."ads SET day_filter=?,day_period1=?,day_period2=?,day_period3=?,day_period4=?,day_period5=?,day_period6=?,day_period7=?,tmt_start_day=?,tmt_end_day=? WHERE uid=? AND id=?",array($dayfilter,$day_period1,$day_period2,$day_period3,$day_period4,$day_period5,$day_period6,$day_period7,$startday,$endday,$uid,$aid));

				 		$abc=$db->execute_query("UPDATE ".TABLE_PREFIX."ads_cache SET ".$ads_string_day." WHERE uid=? AND id=?",$ads_day_arr);
			 	}


			 }

			if($messageflag ==0)
			$message=$this->get_message('time target update success');

			}
		}
		else
		{

			$result=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."ads WHERE uid=? AND id=?",array($uid,$aid));
			$data=$result->fetch_assoc();


			$datefilter=$data['date_filter'];
			$date_period=$data['date_period'];
			$timefilter=$data['time_filter'];
			$starttime=$data['tmt_start_time'];
			$endtime=$data['tmt_end_time'];
			$dayfilter=$data['day_filter'];
			$startday=$data['tmt_start_day'];
			$endday=$data['tmt_end_day'];
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

			$startdatedb=$data['tmt_start_date'];
			$enddatedb=$data['tmt_end_date'];

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
	}
};
?>
