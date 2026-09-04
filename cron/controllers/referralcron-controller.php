<?php
class ReferralcronController extends ApplicationController
{
	
	function before_execute()
	{
	     parent::before_execute();
	     setlocale(LC_ALL,'en_US'); //In case of some languages,decimal places replace with ','.
	}
		
	function data_backup_action()
	{
		$this->disable_notice_area();
		
		if(file_exists("../".CACHE_DIR."/cron/lock-referral.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-referral.txt");
			
			if(date('d',$file_creation_time) != date('d',time()))
			unlink("../".CACHE_DIR."/cron/lock-referral.txt");
		}			
		
			
        if(!is_dir("../".CACHE_DIR."/cron"))
       	mkdir("../".CACHE_DIR."/cron",0777);		
		
		$fp = fopen("../".CACHE_DIR."/cron/lock-referral.txt", "a+"); 
		
		if(flock($fp, LOCK_EX | LOCK_NB)) 
		{ 	
			$cron_running_time=date('Y-m-d-H:i:s',time());
			
			$appendstring="Locked At : ".$cron_running_time." - Data Backup Cron == ";				
			

		
		
		$db= DAL::get_instance();
		$success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=? WHERE task=?",array(0,'referral_cron_success_time'));
		
		set_time_limit(0);
		
		
		$query_limit=Configuration::get_instance()->read('db_query_execution_limit');
		
		
		$current_time =date("Y",time());
		$current_time.=date("m",time());
		$current_time.=date("d",time());
		$current_time.=date("H",time());			
		
		
		
		
echo "<br><br><strong>Referral Daily</strong><br>";
		
do
{    
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_statistics_daily'));
	
	if($supdation_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		break;
	}
	
	$stat_row=$supdation_res->fetch_assoc();
	$update_status=$stat_row['status'];
	$start_time=$stat_row['time'];
	

	$current_day=date("Y",time());
	$current_day.=date("m",time());
	$current_day.=date("d",time());
	
	$current_hour  = $current_day.date("H",time());
	$previous_hour = $this->get_previous_hour($current_hour);

	
	if($start_time > $current_hour)
	{
		echo "<br>BREAKING - LAST DAY STATISTICS IS COMPLETE";
		break;
	}
	
	if($start_time==0)
	{
		$mintm_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."referral_hourly_backup");
		if($mintm_res->error !="")
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."referral_hourly_backup table<br>".$mintm_res->get_sql();
			break;
		}
		
		$mintm_row=$mintm_res->fetch_assoc();
		$temp=intval($mintm_row['tm']);
		
			
		if($temp ==0)
		{
			echo "<br>Min time = No Referral Data.";
			break;
		}
		else
		echo "<br>Min time = ".$temp;
		
		if($temp !=0)
		$day=substr($temp,0,-2);
	}
	else
	{
		if($update_status==2)
		$day=$this->get_nextday($start_time);
		else
		$day=substr($start_time,0,-2);
	}
		
	if($day < $current_day)	
	{
		$start=	$day."00";
		$end=$day."23";	
	}	
	else 
	{	
		$start=	$day."00";
		$end=$current_hour;
	}		

echo "<br>Currently building data for ".$day;

	$hr_qry_res=$db->execute_query("select time,status from ".TABLE_PREFIX."statistics_updation where task=?",array('referral_data_minute'));    
	    
	if($hr_qry_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$hr_qry_res->get_sql();
		break;
	}
	else 
	{
		$stat_row_hourly=$hr_qry_res->fetch_assoc();
		
		$updationhour=substr($stat_row_hourly['time'],0,-2);

		if($updationhour < $end)  	
		{
			echo "<br>BREAKING - HOURLY REFERRAL TASK INCOMPLETE";
			break;
		}
	}



$del_hdata_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_statistics_daily where time >=?",array($day));
if($del_hdata_res->error !="")
{
	echo "<br><br>Data clearing from ".TABLE_PREFIX."referral_statistics_daily failed<br>".$del_hdata_res->get_sql();
	break;
}


$qry_res=$db->execute_query("SELECT uid,sum(adv_earning) as adv_earning,sum(pub_earning) as pub_earning FROM ".TABLE_PREFIX."referral_hourly_backup WHERE (time >=? and time <=?) group by uid",array($start,$end));
if($qry_res->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_hourly_backup table<br>".$qry_res->get_sql();
	break;
}

	
	$num_row_inserted=0;
	$result_count=$qry_res->get_num_records();
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion
	
		
	
	
	while($row=$qry_res->fetch_assoc())
	{
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?)";		
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['adv_earning'];
		$insert_data[]=$row['pub_earning'];
		$insert_data[]=$day;
		
		
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/				
	}
	
	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/	
	
	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{	
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_statistics_daily (`id`,`uid`,`adv_earning`,`pub_earning`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_statistics_daily failed<br>".$ins_qry_res->get_sql();
				break;
			}			
		}
    }
	
	
	if($num_row_inserted == $result_count)	
	{
		if($day == $current_day)
		{		
			/*
			if($day."23" != $current_hour)//partial completion
			{
				$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'referral_statistics_daily'));	
				echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT DAY STATISTICS";
				break;
			}
			else  //partial completion
			{*/
				$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'referral_statistics_daily'));
				if($up_st_res->get_error() =='')
				{
					$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_hourly_backup where time <=?",array($previous_hour));
					echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT DAY STATISTICS";
				}
				else 
					echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
					
					break;
			//}
		}
		else if($day < $current_day) //full completion
		{
			$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($day."23",2,'referral_statistics_daily'));
		
			if($up_st_res->get_error() =='')
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_hourly_backup where time <=?",array($day."23"));
			else
			echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
		}
	}

flush();
//echo mysql_error();
}while(1);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br><strong>Referral Monthly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_statistics_monthly'));
	if($supdation_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		break;
	}
	
	$stat_row=$supdation_res->fetch_assoc();
	$update_status=$stat_row['status'];
	$start_time=$stat_row['time'];
	
	$current_month=date("Y",time());
	$current_month.=date("m",time());
	
	if($start_time==0)
	{
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."referral_statistics_daily");
		if($min_qry_res->error =="")
		{
			$min_time_daily_row=$min_qry_res->fetch_assoc();
			if($min_time_daily_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."referral_statistics_daily table";
				break;
			}
			else
			{
	            echo "<br>Min time = ".$min_time_daily_row['mt'];
				$month=$min_time_daily_row['mt'];
				$month=substr($month,0,-2);
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."referral_statistics_daily table<br>".$min_qry_res->get_sql();
			break;
		}
	
	}
	else
	{
		if($update_status==2)
		$month=$this->get_nextmonth($start_time);
		else
		$month=$start_time;
	}

	if($month>=$current_month)// && $update_status==2
	{
		echo "<br>BREAKING - LAST MONTH STATISTICS IS COMPLETE";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_month_substract($month);
		$delete_time=$delete_time."31";
		
	
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_statistics_daily WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."referral_statistics_daily<br>".$del_qry_res->get_sql();
		else 
		echo "<br>Old data deletion from ".TABLE_PREFIX."referral_statistics_daily failed<br>".$del_qry_res->get_sql();
    }

	$start=	$month."01";
	$end=$month."31";

echo "<br>Currently building data for ".$month;

$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_statistics_daily'));
if($st_up_qry_res->error !="")
{
	echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$st_up_qry_res->get_sql();
	break;
	
}

$stat_row_daily=$st_up_qry_res->fetch_assoc();
if($stat_row_daily['time'] <= $month."31"."23") 
{
	echo "<br>BREAKING - DAILY TABLE INCOMPLETE";
	break; //cannot build as the daily table data is not complete.
}

	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_statistics_monthly WHERE time=?",array($month));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."referral_statistics_monthly failed<br>".$st_del_res->get_sql();
		break;
	}
	
	
	
	$sel_qry_res=$db->execute_query("SELECT uid,sum(adv_earning) as adv_earning,sum(pub_earning) as pub_earning FROM ".TABLE_PREFIX."referral_statistics_daily WHERE (time>=? and time<=?)  group by uid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_statistics_daily table<br>".$sel_qry_res->get_sql();
		break;
	}

	
	$num_row_inserted=0;
	$result_count=$sel_qry_res->get_num_records();
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion
	
	
	while($row=$sel_qry_res->fetch_assoc())
	{
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?)";		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['adv_earning'];
		$insert_data[]=$row['pub_earning'];
		$insert_data[]=$month;
		
		
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/		
	}
	
	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/			
	
	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{	
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_statistics_monthly (`id`,`uid`,`adv_earning`,`pub_earning`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_statistics_monthly failed<br>".$ins_qry_res->get_sql();
				break;
			}			
		}
    }
	
if($num_row_inserted == $result_count)		
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$month,'referral_statistics_monthly'));
	if($month==$this->get_previous_month($current_month))
	{
		echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
		break;
	}
}
else 
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$month,'referral_statistics_monthly'));
	echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST MONTH STATISTICS";
	break;
}

	//echo mysql_error();
	flush();
}while (1);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br><strong>Referral Yearly</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_statistics_yearly'));
	if($supdation_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		break;
	}
	
	$stat_row=$supdation_res->fetch_assoc();
	$update_status=$stat_row['status'];
	$start_time=$stat_row['time'];

	$current_year=date("Y",time());
	if($start_time==0)
	{
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."referral_statistics_monthly");
		if($min_qry_res->error =="")
		{
			$min_time_monthly_row=$min_qry_res->fetch_assoc();
			if($min_time_monthly_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."referral_statistics_monthly table";
				break;
			}
			else
			{
	            echo "<br>Min time = ".$min_time_monthly_row['mt'];
				$year=$min_time_monthly_row['mt'];
				$year=substr($year,0,-2);
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."referral_statistics_monthly table<br>".$min_qry_res->get_sql();
			break;
		}
	}
	else
	{
		if($update_status==2)
		$year=$this->get_nextyear($start_time);
		else
		$year=$start_time;
	}
	if($year >= $current_year) //&& $update_status==2
	{
		echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_previous_year($year)."12";
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_statistics_monthly WHERE time<=?",array($delete_time));
	
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."referral_statistics_monthly<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."referral_statistics_monthly failed<br>".$del_qry_res->get_sql();
	}

$start=	$year."01";
$end=$year."12";

echo "<br>Currently building data for ".$year;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_statistics_monthly'));
	if($st_up_qry_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$st_up_qry_res->get_sql();
		break;
	
	}

	$stat_row_monthly=$st_up_qry_res->fetch_assoc();

	if(($stat_row_monthly['time']==$year."12" && $stat_row_monthly['status']!=2) || ($stat_row_monthly['time']<$year."12") )
	{
		echo "<br>BREAKING - MONTHLY TABLE INCOMPLETE";
		break; //cannot build as the monthly table data is not complete.
	}


	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_statistics_yearly WHERE time=?",array($year));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."referral_statistics_yearly failed<br>".$st_del_res->get_sql();
		break;

	}

	$sel_qry_res=$db->execute_query("SELECT uid,sum(adv_earning) as adv_earning,sum(pub_earning) as pub_earning FROM ".TABLE_PREFIX."referral_statistics_monthly WHERE (time>=? and time<=?)  group by uid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_statistics_monthly table<br>".$sel_qry_res->get_sql();
		break;
	
	}

	
	$num_row_inserted=0;
	$result_count=$sel_qry_res->get_num_records();
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion
	
		
	
	
	while($row=$sel_qry_res->fetch_assoc())
	{
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?)";		
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['adv_earning'];
		$insert_data[]=$row['pub_earning'];
		$insert_data[]=$year;
		
		
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/		
	}
	
	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/	

	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{	
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_statistics_yearly (`id`,`uid`,`adv_earning`,`pub_earning`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_statistics_yearly failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }
	
	
	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$year,'referral_statistics_yearly'));
		if($year==$this->get_previous_year($current_year))
		{
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$year,'referral_statistics_yearly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


echo "<br><br><strong>Referral Monthly Temp</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_statistics_monthly_temp'));
	if($supdation_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		break;
	}

	$stat_row=$supdation_res->fetch_assoc();
	$update_status=$stat_row['status'];
	$start_time=$stat_row['time'];

    $current_month=date("Y",time());
	$current_month.=date("m",time());
	
	$current_day=$current_month.date("d",time());
    $current_hour=$current_day.date("H",time());
	$previous_hour=$this->get_previous_hour($current_hour);

	/*
	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT MONTH STATISTICS IS COMPLETE";
			break;
		}
	}
	*/
	
	
	$start=	$current_month."01";
	$end=$current_hour;

	echo "<br>Currently building data for ".$start."00"." - ".$end;

	

	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_statistics_monthly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."referral_statistics_monthly_temp failed<br>".$st_del_res->get_sql();
		break;
	}

	
	
	
	$sel_qry_res=$db->execute_query("SELECT uid,sum(adv_earning) as adv_earning,sum(pub_earning) as pub_earning FROM ".TABLE_PREFIX."referral_statistics_daily WHERE (time>=?)  group by uid",array($start));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_statistics_daily table<br>".$sel_qry_res->get_sql();
		break;
	}

	
	$num_row_inserted=0;
	$result_count=$sel_qry_res->get_num_records();
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion
	
		
	
	
	while($row=$sel_qry_res->fetch_assoc())
	{
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?)";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['adv_earning'];
		$insert_data[]=$row['pub_earning'];
		$insert_data[]=$current_month;
		
		
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/		
	}

	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/		

	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{	
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_statistics_monthly_temp (`id`,`uid`,`adv_earning`,`pub_earning`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_statistics_monthly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }
	
	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'referral_statistics_monthly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'referral_statistics_monthly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT MONTH STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);



/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Referral Yearly Temp</strong><br>";
do
{

$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_statistics_yearly_temp'));
if($supdation_res->error !="")
{
	echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
	break;
}

$stat_row=$supdation_res->fetch_assoc();
$update_status=$stat_row['status'];
$start_time=$stat_row['time'];
$current_year=date("Y",time());
$current_day=$current_year.date("m",time());
$current_day.=date("d",time());

$current_hour  = $current_day.date("H",time());
$previous_hour = $this->get_previous_hour($current_hour);

/*
	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT YEAR STATISTICS IS COMPLETE";
			break;
		}
	}
*/

$start=	$current_year."01";
$end=$current_hour;

$year_begin=$current_year."01";
$month_begin=$current_year.date("m",time());

echo "<br>Currently building data for ".$start."01"."00"." - ".$end;

$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_statistics_yearly_temp");
if($st_del_res->error !="")
{
	echo "<br>Data clearing from ".TABLE_PREFIX."referral_statistics_yearly_temp failed<br>".$st_del_res->get_sql();
	break;

}

$sel_qry_res=$db->execute_query("SELECT uid,sum(adv_earning) as adv_earning,sum(pub_earning) as pub_earning FROM ".TABLE_PREFIX."referral_statistics_monthly WHERE (time>=?)  group by uid",array($year_begin));
if($sel_qry_res->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_statistics_monthly table<br>".$sel_qry_res->get_sql();
	break;

}

$sel_qry_res1=$db->execute_query("SELECT uid,sum(adv_earning) as adv_earning,sum(pub_earning) as pub_earning FROM ".TABLE_PREFIX."referral_statistics_monthly_temp WHERE (time>=?)  group by uid",array($month_begin));
if($sel_qry_res1->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_statistics_monthly_temp table<br>".$sel_qry_res->get_sql();
	break;

}


$array=array();
while($row=$sel_qry_res->fetch_assoc())
{
	$key=$row['uid'];

	$array[$key][0]=$row['uid'];
	$array[$key][1]=$row['adv_earning'];
	$array[$key][2]=$row['pub_earning'];
}


while($row=$sel_qry_res1->fetch_assoc())
{
	$key=$row['uid'];

	if(!isset($array[$key][0]))
	$array[$key][0]=$row['uid'];

	if(!isset($array[$key][1]))
	$array[$key][1]=$row['adv_earning'];
	else
	$array[$key][1]=$array[$key][1]+$row['adv_earning'];
	

	if(!isset($array[$key][2]))
	$array[$key][2]=$row['pub_earning'];
	else
	$array[$key][2]=$array[$key][2]+$row['pub_earning'];
	
}



	$num_row_inserted=0;
	$result_count=count($array);
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion
	
	

foreach ($array as $k => $v)
{
	if($valuestring !="")
	$valuestring.=',';

	$valuestring.="(?,?,?,?,?)";	
	
	$insert_data[]='';
	$insert_data[]=$v[0];
	$insert_data[]=$v[1];
	$insert_data[]=$v[2];
	$insert_data[]=$current_year;
	

	/*********   For Batch Insertion   ********/
	
	$iiii++;
	
	if($iiii % $query_limit ==0)
	{
		$advarraybatch[$iii][0]=$insert_data;

		$advarraybatch[$iii][1]=$valuestring;

		
		$insert_data=array();

		$valuestring="";
		
		$iii++;
		$iiii=0;
	}
	
	/*********   For Batch Insertion   ********/	
}

	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/		
	
	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{		
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_statistics_yearly_temp (`id`,`uid`,`adv_earning`,`pub_earning`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_statistics_yearly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}
		}
    }	
			

if($num_row_inserted == $result_count)
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'referral_statistics_yearly_temp'));
	echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST YEAR STATISTICS";
	break;
}
else
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'referral_statistics_yearly_temp'));
	echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
    break;
}
//echo mysql_error();
flush();
}while (1);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br><strong>Referral Visits Monthly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_visits_monthly'));
	if($supdation_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		break;
	}
	
	$stat_row=$supdation_res->fetch_assoc();
	$update_status=$stat_row['status'];
	$start_time=$stat_row['time'];
	
	$current_month=date("Y",time());
	$current_month.=date("m",time());
	
	if($start_time==0)
	{
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."referral_visits_daily");
		if($min_qry_res->error =="")
		{
			$min_time_daily_row=$min_qry_res->fetch_assoc();
			if($min_time_daily_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."referral_visits_daily table";
				break;
			}
			else
			{
	            echo "<br>Min time = ".$min_time_daily_row['mt'];
				$month=$min_time_daily_row['mt'];
				$month=substr($month,0,-2);
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."referral_visits_daily table<br>".$min_qry_res->get_sql();
			break;
		}
	
	}
	else
	{
		if($update_status==2)
		$month=$this->get_nextmonth($start_time);
		else
		$month=$start_time;
	}

	if($month >= $current_month)// && $update_status==2
	{
		echo "<br>BREAKING - LAST MONTH STATISTICS IS COMPLETE";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_month_substract($month);
		$delete_time=$delete_time."31";
		
	
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_visits_daily WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."referral_visits_daily<br>".$del_qry_res->get_sql();
		else 
		echo "<br>Old data deletion from ".TABLE_PREFIX."referral_visits_daily failed<br>".$del_qry_res->get_sql();
    }

	$start=	$month."01";
	$end=$month."31";

echo "<br>Currently building data for ".$month;






	$stat_row_daily =date("Y",time());
	$stat_row_daily.=date("m",time());
	$stat_row_daily.=date("d",time());
	$stat_row_daily.=date("H",time());
	


if($stat_row_daily <= $month."31"."23") 
{
	echo "<br>BREAKING - DAILY TABLE INCOMPLETE";
	break; //cannot build as the daily table data is not complete.
}

	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_visits_monthly WHERE time=?",array($month));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."referral_visits_monthly failed<br>".$st_del_res->get_sql();
		break;
	}
	
	
	
	$sel_qry_res=$db->execute_query("SELECT uid,url,sum(visits) as visits,sum(signup) as signup FROM ".TABLE_PREFIX."referral_visits_daily WHERE (time>=? and time<=?)  group by uid,url",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_visits_daily table<br>".$sel_qry_res->get_sql();
		break;
	}

	
	
	$num_row_inserted=0;
	$result_count=$sel_qry_res->get_num_records();
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion
	
	
	
	while($row=$sel_qry_res->fetch_assoc())
	{
		
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?,?)";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['url'];
		$insert_data[]=$row['visits'];
		$insert_data[]=$row['signup'];
		$insert_data[]=$month;
		
	
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/		
	}
	
	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/		
	
	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{		
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_visits_monthly (`id`,`uid`,`url`,`visits`,`signup`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_visits_monthly failed<br>".$ins_qry_res->get_sql();
				break;
			}
		}
	}

		
if($num_row_inserted == $result_count)
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$month,'referral_visits_monthly'));
	if($month==$this->get_previous_month($current_month))
	{
		echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
		break;
	}
}
else 
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$month,'referral_visits_monthly'));
	echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST MONTH STATISTICS";
	break;
}

	//echo mysql_error();
	flush();
}while (1);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br><strong>Referral Visits Yearly</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_visits_yearly'));
	if($supdation_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		break;
	}
	
	$stat_row=$supdation_res->fetch_assoc();
	$update_status=$stat_row['status'];
	$start_time=$stat_row['time'];

	$current_year=date("Y",time());
	if($start_time==0)
	{
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."referral_visits_monthly");
		if($min_qry_res->error =="")
		{
			$min_time_monthly_row=$min_qry_res->fetch_assoc();
			if($min_time_monthly_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."referral_visits_monthly table";
				break;
			}
			else
			{
	            echo "<br>Min time = ".$min_time_monthly_row['mt'];
				$year=$min_time_monthly_row['mt'];
				$year=substr($year,0,-2);
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."referral_visits_monthly table<br>".$min_qry_res->get_sql();
			break;
		}
	}
	else
	{
		if($update_status==2)
		$year=$this->get_nextyear($start_time);
		else
		$year=$start_time;
	}
	if($year >= $current_year) //&& $update_status==2
	{
		echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_previous_year($year)."12";
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_visits_monthly WHERE time<=?",array($delete_time));
	
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."referral_visits_monthly<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."referral_visits_monthly failed<br>".$del_qry_res->get_sql();
	}

$start=	$year."01";
$end=$year."12";

echo "<br>Currently building data for ".$year;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_visits_monthly'));
	if($st_up_qry_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$st_up_qry_res->get_sql();
		break;
	
	}

	$stat_row_monthly=$st_up_qry_res->fetch_assoc();

	if(($stat_row_monthly['time']==$year."12" && $stat_row_monthly['status']!=2) || ($stat_row_monthly['time']< $year."12") )
	{
		echo "<br>BREAKING - MONTHLY TABLE INCOMPLETE";
		break; //cannot build as the monthly table data is not complete.
	}


	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_visits_yearly WHERE time=?",array($year));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."referral_visits_yearly failed<br>".$st_del_res->get_sql();
		break;

	}

	$sel_qry_res=$db->execute_query("SELECT uid,url,sum(visits) as visits,sum(signup) as signup FROM ".TABLE_PREFIX."referral_visits_monthly WHERE (time>=? and time<=?)  group by uid,url",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_visits_monthly table<br>".$sel_qry_res->get_sql();
		break;
	
	}


	
	$num_row_inserted=0;
	$result_count=$sel_qry_res->get_num_records();
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion	
	
	
	
	
	
	
	while($row=$sel_qry_res->fetch_assoc())
	{
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?,?)";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['url'];
		$insert_data[]=$row['visits'];
		$insert_data[]=$row['signup'];
		$insert_data[]=$year;

		
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/		
	}
	
	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/		
	
	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{			
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_visits_yearly (`id`,`uid`,`url`,`visits`,`signup`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_visits_yearly failed<br>".$ins_qry_res->get_sql();
				break;
			}
		}
	}
	
	
	
	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$year,'referral_visits_yearly'));
		if($year==$this->get_previous_year($current_year))
		{
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$year,'referral_visits_yearly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


echo "<br><br><strong>Referral Visits Monthly Temp</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_visits_monthly_temp'));
	if($supdation_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
		break;
	}

	$stat_row=$supdation_res->fetch_assoc();
	$update_status=$stat_row['status'];
	$start_time=$stat_row['time'];

    $current_month=date("Y",time());
	$current_month.=date("m",time());
	
	$current_day=$current_month.date("d",time());
	$current_hour  = $current_day.date("H",time());
	$previous_hour = $this->get_previous_hour($current_hour);

	/*
	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT MONTH STATISTICS IS COMPLETE";
			break;
		}
	}
	*/
	
	$start=	$current_month."01";
	$end=$current_hour;

	echo "<br>Currently building data for ".$start."00"." - ".$end;

	

	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_visits_monthly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."referral_visits_monthly_temp failed<br>".$st_del_res->get_sql();
		break;
	}

	
	
	
	$sel_qry_res=$db->execute_query("SELECT uid,url,sum(visits) as visits,sum(signup) as signup FROM ".TABLE_PREFIX."referral_visits_daily WHERE (time>=?)  group by uid,url",array($start));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_visits_daily table<br>".$sel_qry_res->get_sql();
		break;
	}


	
	
	$num_row_inserted=0;
	$result_count=$sel_qry_res->get_num_records();
	
	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion	
	
	
	
	
	while($row=$sel_qry_res->fetch_assoc())
	{
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?,?)";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['url'];
		$insert_data[]=$row['visits'];
		$insert_data[]=$row['signup'];
		$insert_data[]=$current_month;
		

		
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/		
	}
	
	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/		
	
	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{					
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_visits_monthly_temp (`id`,`uid`,`url`,`visits`,`signup`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_visits_monthly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}
		}
	}


	
	
	
	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'referral_visits_monthly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'referral_visits_monthly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT MONTH STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);



/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Referral Visits Yearly Temp</strong><br>";
do
{

$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('referral_visits_yearly_temp'));
if($supdation_res->error !="")
{
	echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$supdation_res->get_sql();
	break;
}

$stat_row=$supdation_res->fetch_assoc();
$update_status=$stat_row['status'];
$start_time=$stat_row['time'];
$current_year=date("Y",time());
$current_day=$current_year.date("m",time());
$current_day.=date("d",time());

$current_hour  = $current_day.date("H",time());
$previous_hour = $this->get_previous_hour($current_hour);

/*
	if($start_time!=0)
	{
		$next_hour=$this->get_nexthour($start_time);
		if($next_hour >$previous_hour)
		{
			echo "<br>BREAKING - CURRENT YEAR STATISTICS IS COMPLETE";
			break;
		}
	}
		*/

$start=	$current_year."01";
$end=$current_hour;

$year_begin=$current_year."01";
$month_begin=$current_year.date("m",time());

echo "<br>Currently building data for ".$start."01"."00"." - ".$end;

$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."referral_visits_yearly_temp");
if($st_del_res->error !="")
{
	echo "<br>Data clearing from ".TABLE_PREFIX."referral_visits_yearly_temp failed<br>".$st_del_res->get_sql();
	break;

}

$sel_qry_res=$db->execute_query("SELECT uid,url,sum(visits) as visits,sum(signup) as signup FROM ".TABLE_PREFIX."referral_visits_monthly WHERE (time>=?)  group by uid,url",array($year_begin));
if($sel_qry_res->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_visits_monthly table<br>".$sel_qry_res->get_sql();
	break;

}

$sel_qry_res1=$db->execute_query("SELECT uid,url,sum(visits) as visits,sum(signup) as signup FROM ".TABLE_PREFIX."referral_visits_monthly_temp WHERE (time>=?)  group by uid,url",array($month_begin));
if($sel_qry_res1->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."referral_visits_monthly_temp table<br>".$sel_qry_res->get_sql();
	break;

}


$array=array();
while($row=$sel_qry_res->fetch_assoc())
{
	$key=$row['uid'].":".$row['url'];

	$array[$key][0]=$row['uid'];
	$array[$key][1]=$row['url'];
	$array[$key][2]=$row['visits'];
	$array[$key][3]=$row['signup'];
}


while($row=$sel_qry_res1->fetch_assoc())
{
	$key=$row['uid'].":".$row['url'];

	if(!isset($array[$key][0]))
	$array[$key][0]=$row['uid'];

	if(!isset($array[$key][1]))
	$array[$key][1]=$row['url'];	
	
	if(!isset($array[$key][2]))
	$array[$key][2]=$row['visits'];

	if(!isset($array[$key][3]))
	$array[$key][3]=$row['signup'];
}



	$num_row_inserted=0;
	$result_count=count($array);


	$insert_data=array();
	$valuestring="";	
	

	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion




foreach ($array as $k => $v)
{
		if($valuestring !="")
		$valuestring.=',';

		$valuestring.="(?,?,?,?,?,?)";
		
		
		$insert_data[]='';
		$insert_data[]=$v[0];
		$insert_data[]=$v[1];
		$insert_data[]=$v[2];
		$insert_data[]=$v[3];
		$insert_data[]=$current_year;
		
		
		/*********   For Batch Insertion   ********/
		
		$iiii++;
		
		if($iiii % $query_limit ==0)
		{
			$advarraybatch[$iii][0]=$insert_data;

			$advarraybatch[$iii][1]=$valuestring;

			
			$insert_data=array();

			$valuestring="";
			
			$iii++;
			$iiii=0;
		}
		
		/*********   For Batch Insertion   ********/		
	}
	
	/*********   For Batch Insertion   ********/
	if($iiii >0)
	{
		$advarraybatch[$iii][0]=$insert_data;
		$advarraybatch[$iii][1]=$valuestring;
	}
	/*********   For Batch Insertion   ********/		
	
	if($result_count >0)
    {
		foreach($advarraybatch as $advkey=>$advvalue)
		{		
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."referral_visits_yearly_temp (`id`,`uid`,`url`,`visits`,`signup`,`time`) VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();	
			else 	
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."referral_visits_yearly_temp failed<br>".$ins_qry_res->get_sql();
				break;
			}
		}
	}




if($num_row_inserted == $result_count)
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'referral_visits_yearly_temp'));
	echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST YEAR STATISTICS";
	break;
}
else
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'referral_visits_yearly_temp'));
	echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
    break;
}
//echo mysql_error();
flush();
}while (1);



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_time,2,'referral_cron_success_time'));
		

		$cron_running_time=date('Y-m-d-H:i:s',time());
			
		fwrite($fp, $appendstring."UnLocked At : ".$cron_running_time."\n");
				
			fflush($fp); 	
			flock($fp, LOCK_UN);    // release the lock	
		}
		else
		{
			echo "<br/>Another cron is already running<br/>";
		}
		fclose($fp);
	}	
};
?>