<?php
class GeodatacronController extends ApplicationController
{
	function before_execute()
	{
	     parent::before_execute();
	     setlocale(LC_ALL,'en_US'); //In case of some languages,decimal places replace with ','.
	}	
	
	function data_backup_action()
	{
		$this->disable_notice_area();
		
		
		if(file_exists("../".CACHE_DIR."/cron/lock-geo.txt"))
		{
			$file_creation_time=filemtime("../".CACHE_DIR."/cron/lock-geo.txt");
			
			if(date('d',$file_creation_time) != date('d',time()))
			unlink("../".CACHE_DIR."/cron/lock-geo.txt");
		}			
		
			
        if(!is_dir("../".CACHE_DIR."/cron"))
       	mkdir("../".CACHE_DIR."/cron",0777);		
		
		$fp = fopen("../".CACHE_DIR."/cron/lock-geo.txt", "a+"); 
		
		if(flock($fp, LOCK_EX | LOCK_NB)) 
		{ 	
			$cron_running_time=date('Y-m-d-H:i:s',time());
			
			$appendstring="Locked At : ".$cron_running_time." - Data Backup Cron == ";				
			
		
		
		
		
		$db= DAL::get_instance();
		
		set_time_limit(0);
		$success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status =? WHERE task=?",array(0,'geo_cron_success_time'));
		
		
		$query_limit=Configuration::get_instance()->read('db_query_execution_limit');
		
		$cpc_addon_enabled=$this->get_addon_status('cpc_enabled');
		$cpp_addon_enabled=$this->get_addon_status('cpp_enabled');	
		$cpa_addon_enabled=$this->get_addon_status('cpa_enabled');
		$cpm_addon_enabled=$this->get_addon_status('cpm_enabled');
		$html_addon_enabled=$this->get_addon_status('html_enabled');
		$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
		$category_targeting_enabled=$this->get_addon_status('category-targeting_enabled');
		$referral_enabled=$this->get_addon_status('referral_enabled');
		
		
		$pop_addon_enabled=$this->get_addon_status('pop-ads_enabled');
		$affiliate_addon_enabled=$this->get_addon_status('affiliate-ads_enabled');
		$video_addon_enabled=$this->get_addon_status('video-ads_enabled');
		
		
		$totimpression='impression';
		
		if($cpm_addon_enabled ==1)
		{
			$totimpression=$totimpression.'+cpm_impression';
			
			$cpm_select=" ,sum(cpm_impression) as cpm_impression,sum(cpm_spend) as cpm_spend,sum(cpm_profit) as cpm_profit ";
			
			$cpm_select1=" ,sum(cpm_click) as cpm_click ";
			
			$cpm_insert1=" ,`cpm_impression`,`cpm_spend`,`cpm_profit`,`cpm_click` ";
			
			$cpm_insert2=" ,?,?,?,? ";
		}
		else 
		{
			$cpm_select="";
			$cpm_select1="";
			
			$cpm_insert1="";
			$cpm_insert2="";
		}
		
		
		
		if($pop_addon_enabled ==1)
		{
			$totimpression=$totimpression.'+pop_impression';
			
			$pop_select=" ,sum(pop_impression) as pop_impression,sum(pop_spend) as pop_spend,sum(pop_profit) as pop_profit ";
			
			$pop_insert1=" ,`pop_impression`,`pop_spend`,`pop_profit` ";
			
			$pop_insert2=" ,?,?,? ";
		}
		else 
		{
			$pop_select="";
			$pop_insert1="";
			$pop_insert2="";
		}
		
		
		
		if($html_addon_enabled ==1)
		{
			$totimpression=$totimpression.'+html_impression';
			
			$html_select=" ,sum(html_impression) as html_impression,sum(html_profit) as html_profit ";
			$html_select1=" ,`html_impression`,`html_profit` ";
			$html_select2=" ,?,? ";
		}
		else
		{
			$html_select="";
			$html_select1="";
			$html_select2="";
		}
		

		
		$siddata1='';
		$siddata2='';
		$siddata3='';
		if($category_targeting_enabled ==1)
		{
			$siddata1=',`sid`';
			$siddata2=',?';
			$siddata3=',sid';
		}
		
		
		if($cpa_addon_enabled ==1)
		{
			$totimpression=$totimpression.'+cpa_impression';
			
			$cpa_select =" ,sum(cpa_impression) as cpa_impression ";
			$cpa_select3=" ,sum(cpa_impression) as cpa_impression,sum(cpa_click) as cpa_click,sum(cpa_conversion) as cpa_conversion,sum(cpa_spend) as cpa_spend,sum(cpa_profit) as cpa_profit ";
			
			$cpa_insert1=" ,`cpa_impression`,`cpa_click`,`cpa_conversion`,`cpa_spend`,`cpa_profit` ";
			$cpa_insert2=" ,?,?,?,?,? ";
		}
		else 
		{
			$cpa_select="";
			$cpa_select3="";
			$cpa_insert1="";
			$cpa_insert2="";
		}
		
				
		
		if($affiliate_addon_enabled ==1)
		{
			$affiliate_select3=" ,sum(affiliate_click) as affiliate_click,sum(affiliate_conversion) as affiliate_conversion,sum(affiliate_spend) as affiliate_spend,sum(affiliate_profit) as affiliate_profit ";
			
			$affiliate_insert1=" ,`affiliate_click`,`affiliate_conversion`,`affiliate_spend`,`affiliate_profit` ";
			$affiliate_insert2=" ,?,?,?,? ";
		}
		else 
		{
			$affiliate_select3="";
			$affiliate_insert1="";
			$affiliate_insert2="";
		}
		
		
			
		if($video_addon_enabled ==1)
		{
			$totimpression=$totimpression.'+cpv_impression';
			
			
			$cpv_select=" ,sum(cpv_impression) as cpv_impression,sum(cpv_spend) as cpv_spend,sum(cpv_profit) as cpv_profit ";
			
			
			$cpv_select1=" ,sum(cpv_click) as cpv_click ";
			
			
			$cpv_insert1=" ,`cpv_impression`,`cpv_spend`,`cpv_profit`,`cpv_click` ";
			
			$cpv_insert2=" ,?,?,?,? ";
		}
		else 
		{
			$cpv_select="";
			$cpv_select1="";
			
			$cpv_insert1="";
			$cpv_insert2="";
		}			

		
		$cpd_select  = "";	
		$cpdString   = "";
		
		if($sponsored_enabled ==1)
		{
			$totimpression=$totimpression.'+sponsored_impression';
			
			$cpv_select.=" ,sum(sponsored_impression) as sponsored_impression ";
			
			$cpv_select1.=" ,sum(sponsored_click) as sponsored_click,sum(sponsored_spend) as sponsored_spend,sum(sponsored_profit) as sponsored_profit ";
			
			$cpd_select  =" ,sponsored_spend,sponsored_profit ";
			
			$cpv_insert1.=" ,`sponsored_impression`,`sponsored_click`,`sponsored_spend` ,`sponsored_profit` ";
				
			$cpv_insert2.=" ,?,?,?,? ";

			$cpdString   = ",COALESCE(clickvalue,0) as cpd_spend,COALESCE(profit,0) as cpd_profit";
		}



		if($cpp_addon_enabled ==1)
		{
			$totimpression=$totimpression.'+cpp_impression';
			
			$cpv_select.=" ,sum(cpp_impression) as cpp_impression,sum(cpp_spend) as cpp_spend,sum(cpp_profit) as cpp_profit ";
			
			$cpv_select1.=" ,sum(cpp_click) as cpp_click ";
			
			$cpv_insert1.=" ,`cpp_impression`,`cpp_spend`,`cpp_profit`,`cpp_click` ";
			
			$cpv_insert2.=" ,?,?,?,? ";
		}

		
		
		$impressionstr=' COALESCE(sum('.$totimpression.'),0) as impression ';
		
		
		
			$current_time =date("Y",time());
			$current_time.=date("m",time());
			$current_time.=date("d",time());
			$current_time.=date("H",time());		
		
		
		
		
		
		
		
		
echo "<br><br><strong>Countrywise Advertiser Daily</strong><br>";
		
do
{    
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_daily'));
	
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
		$mintm_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."adv_impression_hourly_backup");
		if($mintm_res->error !="")
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."adv_impression_hourly_backup table<br>".$mintm_res->get_sql();
			break;
		}
		
		$mintm_row=$mintm_res->fetch_assoc();
		$temp=intval($mintm_row['tm']);
		
		$mintm1_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."dailyclicks_backup");
		if($mintm1_res->error !="")
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."dailyclicks_backup table<br>".$mintm1_res->get_sql();
			break;
		}
		
		$mintm1_row=$mintm1_res->fetch_assoc();
		$temp1=intval($mintm1_row['tm']);
		
		
		$temp2=0;
		if($cpa_addon_enabled ==1)
		{
			$mintm2_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."dailyconversions_backup");
			if($mintm2_res->error !="")
			{
				echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."dailyconversions_backup table<br>".$mintm2_res->get_sql();
				break;
			}
		
			$mintm2_row=$mintm2_res->fetch_assoc();
			$temp2=intval($mintm2_row['tm']);
		}
		

		if($temp ==0)
		echo "<br>Min time - imp = No Impressions.";
		else
		echo "<br>Min time - imp = ".$temp;
		
		if($temp1 ==0)
		echo "<br>Min time - clk = No Clicks.";
		else	
		echo "<br>Min time - clk = ".$temp1;
		
		
		if($cpa_addon_enabled ==1)
		{
			if($temp2 ==0)
			echo "<br>Min time - CPA conversion = No Conversions.";
			else	
			echo "<br>Min time - CPA conversion = ".$temp2;
		}	
			
	
		
		///////////////////////////////////////////////////////////////////////////
		
		if($cpa_addon_enabled ==1)
		{
			if($temp ==0 && $temp1 ==0 && $temp2 ==0)
			{
				echo "<br>No impressions,clicks and conversions";
			    break;
		    }
		}
		else
		{
			if($temp ==0 && $temp1 ==0)
			{
				echo "<br>No impressions and clicks";
			    break;
		    }
		}
		
		
		if($temp1 > $temp2 && $temp2 >0)
		$temp1=$temp2;
		
		
		if($temp > $temp1 && $temp1 >0)
		$temp=$temp1;
		
		if($temp ==0 && $temp1 >0)
		$temp=$temp1;
		else if($temp ==0 && $temp2 >0)
		$temp=$temp2;		
		
		if($temp >0)
		$day=substr($temp,0,-2);
		else
		{
			echo "<br>No data found";
			break;
		}		
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

	$hr_qry_res=$db->execute_query("select time,status from ".TABLE_PREFIX."statistics_updation where task=?",array('impression_updation_minute'));    
	    
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
			echo "<br>BREAKING - HOURLY ADVERTISER TASK INCOMPLETE";
			break;
		}		
	}



$hr_qry_res1=$db->execute_query("select time,status from ".TABLE_PREFIX."statistics_updation where task=?",array('dailyclick_backup'));

if($hr_qry_res1->error !="")
{
	echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$hr_qry_res1->get_sql();
	break;
}
else
{
	$stat_row_hourly1=$hr_qry_res1->fetch_assoc();
	$one_hour_less    = $this->get_previous_hour($current_hour);
	if($stat_row_hourly1['time'] < $one_hour_less) 
	{
		echo "<br>BREAKING - DAILY CLICK BACKUP TASK INCOMPLETE";
		break;
	}
}

$del_hdata_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_adv_daily where time >=?",array($day));
if($del_hdata_res->error !="")
{
	echo "<br><br>Data clearing from ".TABLE_PREFIX."geo_statistics_adv_daily failed<br>".$del_hdata_res->get_sql();
	break;
}

$qry_res=$db->execute_query("SELECT uid,aid,kid,sum(cpc_impression) as cpc_impression,country,source ".$cpm_select." ".$html_select." ".$cpa_select." ".$pop_select." ".$cpv_select.$cpd_select." FROM ".TABLE_PREFIX."adv_impression_hourly_backup WHERE (time>=? and time <=?) group by country,uid,aid,kid",array($start,$end));


if($qry_res->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."adv_impression_hourly_backup table<br>".$qry_res->get_sql();
	break;
}


$qry1_res=$db->execute_query("SELECT uid,aid,kid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit,click_type,country,source".$cpdString." FROM ".TABLE_PREFIX."dailyclicks_backup WHERE (time>=? and time <=?) group by country,click_type,uid,aid,kid",array($start,$end));
if($qry1_res->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."dailyclicks_backup table<br>".$qry1_res->get_sql();
	break;	
}

    if($cpa_addon_enabled ==1)
	{
		$qry2_res=$db->execute_query("SELECT uid,aid,kid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit,country,conversion_type FROM ".TABLE_PREFIX."dailyconversions_backup WHERE (time>=? and time <=?) group by country,conversion_type,uid,aid,kid",array($start,$end));
		if($qry2_res->error !="")
		{
			echo "<br>Data retrieval failed from ".TABLE_PREFIX."dailyconversions_backup table<br>".$qry2_res->get_sql();
			break;	
		}
	}


	$result_arr=array();
	while($row=$qry_res->fetch_assoc())
	{
		$key=$row['country'].':'.$row['uid'].':'.$row['aid'].':'.$row['kid'];
		
		$result_arr[$key][0]=$row['uid'];
		$result_arr[$key][1]=$row['aid'];
		$result_arr[$key][2]=$row['kid'];
		$result_arr[$key][3]=$row['cpc_impression'];
		$result_arr[$key][4]=0;
		$result_arr[$key][5]=0;
		$result_arr[$key][6]=0;
		$result_arr[$key][34]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$result_arr[$key][7]=$row['cpm_impression'];
			$result_arr[$key][8]=$row['cpm_spend'];
			$result_arr[$key][9]=$row['cpm_profit'];
		}
		
		if($html_addon_enabled ==1)
		{
			$result_arr[$key][10]=$row['html_impression'];
			$result_arr[$key][11]=$row['html_profit'];
		}
		
		
		$result_arr[$key][12]=0;
		$result_arr[$key][13]=$row['country'];
		
		
		if($cpa_addon_enabled ==1)
		$result_arr[$key][14]=$row['cpa_impression'];
		else
		$result_arr[$key][14]=0;
		
		
		$result_arr[$key][15]=0;
		$result_arr[$key][16]=0;
		$result_arr[$key][17]=0;
		if($pop_addon_enabled ==1)
		{
			$result_arr[$key][18]=$row['pop_impression'];
			$result_arr[$key][19]=$row['pop_spend'];
			$result_arr[$key][20]=$row['pop_profit'];
		}
		
		
		
		
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][21]=0;
			$result_arr[$key][22]=0;
			$result_arr[$key][23]=0;
		}
		
		if($video_addon_enabled ==1)
		{
			$result_arr[$key][24]=$row['cpv_impression'];
			$result_arr[$key][25]=$row['cpv_spend'];
			$result_arr[$key][26]=$row['cpv_profit'];
		}		
		
		
		if($sponsored_enabled ==1)
		{
			$result_arr[$key][28]=$row['sponsored_impression'];		
			
			
			$result_arr[$key][29]=$row['sponsored_spend'];
			$result_arr[$key][30]=$row['sponsored_profit'];
			
		}


		if($cpp_addon_enabled ==1)
		{
			$result_arr[$key][31]=$row['cpp_impression'];
			$result_arr[$key][32]=$row['cpp_spend'];
			$result_arr[$key][33]=$row['cpp_profit'];
		}



	}
	
	$qry_res->free_result();
	while($row=$qry1_res->fetch_assoc())
	{
		$key=$row['country'].':'.$row['uid'].':'.$row['aid'].':'.$row['kid'];
	
		$result_arr[$key][0]=$row['uid'];
		$result_arr[$key][1]=$row['aid'];
		$result_arr[$key][2]=$row['kid'];
		
		
		
		if(!isset($result_arr[$key][3]))
		$result_arr[$key][3]=0;



		$result_arr[$key][4]=$row['ids'];


		if($row['click_type'] != 3)
		{		
			$result_arr[$key][5]=$row['cvalue'];
			$result_arr[$key][6]=$row['profit'];
		}
		else
		{
			$result_arr[$key][5]=0;
			$result_arr[$key][6]=0;			
		}		
		
		if(!isset($result_arr[$key][34]))
		$result_arr[$key][34]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][7]))
			$result_arr[$key][7]=0;
			
			if(!isset($result_arr[$key][8]))
			$result_arr[$key][8]=0;
			
			if(!isset($result_arr[$key][9]))
			$result_arr[$key][9]=0;
		}
		
		if($html_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][10]))
			$result_arr[$key][10]=0;
			
			if(!isset($result_arr[$key][11]))
			$result_arr[$key][11]=0;
		}
		
		
		$result_arr[$key][12]=$row['click_type'];
		$result_arr[$key][13]=$row['country'];
		
		
		if(!isset($result_arr[$key][14]))
		$result_arr[$key][14]=0;
		
		
		$result_arr[$key][15]=0;
		$result_arr[$key][16]=0;
		$result_arr[$key][17]=0;
		if($pop_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][18]))
			$result_arr[$key][18]=0;
			
			if(!isset($result_arr[$key][19]))
			$result_arr[$key][19]=0;
			
			if(!isset($result_arr[$key][20]))
			$result_arr[$key][20]=0;
		}
		
		
		
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][21]=0;
			$result_arr[$key][22]=0;
			$result_arr[$key][23]=0;
		}
		
		if($video_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][24]))
			$result_arr[$key][24]=0;
			
			if(!isset($result_arr[$key][25]))
			$result_arr[$key][25]=0;
			
			if(!isset($result_arr[$key][26]))
			$result_arr[$key][26]=0;			
		}
		
		
		if($sponsored_enabled ==1)
		{
			if(!isset($result_arr[$key][28]))
			$result_arr[$key][28]=0;
			
			if($row['click_type'] != 3)
			{			
				if(!isset($result_arr[$key][29]))
				$result_arr[$key][29]=0;	

				if(!isset($result_arr[$key][30]))
				$result_arr[$key][30]=0;
			}
			else
			{
				$result_arr[$key][29]=$row['cpd_spend'];
				$result_arr[$key][30]=$row['cpd_profit'];
			}						
		}		



		if($cpp_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][31]))
			$result_arr[$key][31]=0;
			
			if(!isset($result_arr[$key][32]))
			$result_arr[$key][32]=0;
			
			if(!isset($result_arr[$key][33]))
			$result_arr[$key][33]=0;			
		}

	}
	
	$qry1_res->free_result();
	
	if($cpa_addon_enabled ==1)
	{
	
		while($row=$qry2_res->fetch_assoc())
		{
			$key=$row['country'].':'.$row['uid'].':'.$row['aid'].':'.$row['kid'];
		
			$result_arr[$key][0]=$row['uid'];
			$result_arr[$key][1]=$row['aid'];
			$result_arr[$key][2]=$row['kid'];
			
			if(!isset($result_arr[$key][3]))
			$result_arr[$key][3]=0;
			
			if(!isset($result_arr[$key][4]))
			$result_arr[$key][4]=0;
			
			if(!isset($result_arr[$key][5]))
			$result_arr[$key][5]=0;
			
			if(!isset($result_arr[$key][6]))
			$result_arr[$key][6]=0;
			
			if(!isset($result_arr[$key][34]))
			$result_arr[$key][34]=0;
			if($cpm_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][7]))
				$result_arr[$key][7]=0;
				
				if(!isset($result_arr[$key][8]))
				$result_arr[$key][8]=0;
				
				if(!isset($result_arr[$key][9]))
				$result_arr[$key][9]=0;
			}
			
			if($html_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][10]))
				$result_arr[$key][10]=0;
				
				if(!isset($result_arr[$key][11]))
				$result_arr[$key][11]=0;
			}
			
			if(!isset($result_arr[$key][12]))
			$result_arr[$key][12]=0;
			
			$result_arr[$key][13]=$row['country'];
			
			
			if(!isset($result_arr[$key][14]))
			$result_arr[$key][14]=0;
			
			
			if($row['conversion_type'] == 12) //Affiliate
			{
				$result_arr[$key][15]=0;
				$result_arr[$key][16]=0;
				$result_arr[$key][17]=0;

				if($affiliate_addon_enabled ==1)
				{
					$result_arr[$key][21]=$row['ids'];
					$result_arr[$key][22]=$row['cvalue'];
					$result_arr[$key][23]=$row['profit'];
				}
			}
			else //CPA
			{
				$result_arr[$key][15]=$row['ids'];
				$result_arr[$key][16]=$row['cvalue'];
				$result_arr[$key][17]=$row['profit'];

				if($affiliate_addon_enabled ==1)
				{
					$result_arr[$key][21]=0;
					$result_arr[$key][22]=0;
					$result_arr[$key][23]=0;
				}
			}

				
			if($pop_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][18]))
				$result_arr[$key][18]=0;
				
				if(!isset($result_arr[$key][19]))
				$result_arr[$key][19]=0;
				
				if(!isset($result_arr[$key][20]))
				$result_arr[$key][20]=0;
			}
			
			
			if($video_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][24]))
				$result_arr[$key][24]=0;
				
				if(!isset($result_arr[$key][25]))
				$result_arr[$key][25]=0;
				
				if(!isset($result_arr[$key][26]))
				$result_arr[$key][26]=0;			
			}			
			
		
			
			if($sponsored_enabled ==1)
			{
				if(!isset($result_arr[$key][28]))
				$result_arr[$key][28]=0;
				
				if(!isset($result_arr[$key][29]))
				$result_arr[$key][29]=0;	
	
				if(!isset($result_arr[$key][30]))
				$result_arr[$key][30]=0;				
			}	


			if($cpp_addon_enabled ==1)
			{
				if(!isset($result_arr[$key][31]))
				$result_arr[$key][31]=0;
				
				if(!isset($result_arr[$key][32]))
				$result_arr[$key][32]=0;
				
				if(!isset($result_arr[$key][33]))
				$result_arr[$key][33]=0;			
			}					
			
		}
		$qry2_res->free_result();
	}
	

	$num_row_inserted=0;
	$result_count=count($result_arr);



	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion



	foreach ( $result_arr as $k => $v)
	{
		
		if($valuestring !="")
		$valuestring.=',';
		$valuestring.="(?,?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		
		
		

		$clickcount=$v[4];
		$clickcount1=0;
		$clickcount2=0;
		$clickcount3=0;
		$clickcount4=0;
		$clickcount5=0;
		$clickcount6=0;
		$clickcount7=0;
		
		if($v[12] ==0)              //CPC
		$clickcount1=$clickcount;
		else if($v[12] ==1)         //CPM
		$clickcount2=$clickcount;
		else if($v[12] ==6)         //CPA
		$clickcount3=$clickcount;
		else if($v[12] ==12)        //Affiliate
		$clickcount4=$clickcount;
		else if($v[12] ==13)        //CPV
		$clickcount5=$clickcount;		
		else if($v[12] ==3)		    //CPD
		$clickcount6=$clickcount;			
		else if($v[12] ==18)        //CPP
		$clickcount7=$clickcount;	
		

		$insert_data[]='';
		$insert_data[]=$v[0];
		$insert_data[]=$v[1];
		$insert_data[]=$v[2];
		$insert_data[]=$v[3];
		$insert_data[]=$clickcount1;
		$insert_data[]=$day;
		$insert_data[]=$v[5];
		$insert_data[]=$v[6];
		$insert_data[]=$v[13];
		$insert_data[]=$v[34];
		
		
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$v[7];
			$insert_data[]=$v[8];
			$insert_data[]=$v[9];
			$insert_data[]=$clickcount2;
		}
			
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$v[10];
			$insert_data[]=$v[11];
		}
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$v[14];
			$insert_data[]=$clickcount3;
			$insert_data[]=$v[15];
			$insert_data[]=$v[16];
			$insert_data[]=$v[17];	
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$v[18];
			$insert_data[]=$v[19];
			$insert_data[]=$v[20];	
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$clickcount4;
			$insert_data[]=$v[21];
			$insert_data[]=$v[22];
			$insert_data[]=$v[23];	
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$v[24];
			$insert_data[]=$v[25];
			$insert_data[]=$v[26];
			$insert_data[]=$clickcount5;
		}		
		
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$v[28];
			$insert_data[]=$clickcount6;
			$insert_data[]=$v[29];
			$insert_data[]=$v[30];
		}		
		
		
		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$v[31];
			$insert_data[]=$v[32];
			$insert_data[]=$v[33];
			$insert_data[]=$clickcount7;
		}


		
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
		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_adv_daily (`id`,`uid`, `aid`, `kid`, `impression`,`click`,`time`,`money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
				
		if($ins_qry_res->error =="")		
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
		else 
		{
			echo "<br>Data insertion to ".TABLE_PREFIX."geo_statistics_adv_daily failed<br>".$ins_qry_res->get_sql();
			break;
		}			
	}
    }

	if($num_row_inserted == $result_count)
	{
		if($day == $current_day)
		{		
			/*
			if($day."23"!=$current_hour)
			{
			//partial completion
				$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'geo_statistics_adv_daily'));	
				echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT DAY STATISTICS";
				break;
			}
			else
			{*/
				//partial completion
				$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'geo_statistics_adv_daily'));
				
				if($up_st_res->get_error() =='')
				echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT DAY STATISTICS";
				else 
				echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
				
				
				break;
			//}
		}
	else if($day < $current_day)	
	{
		//full completion
		$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($day."23",2,'geo_statistics_adv_daily'));
	
		if($up_st_res->get_error() !='')
		echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
	}
}

flush();
//echo mysql_error();
}while(1);


////////////////////////////////////////////////////////////////////		

echo "<br><br><strong>Countrywise Publisher Daily</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_daily'));

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
		$mintm_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."pub_impression_hourly_backup");
		if($mintm_res->error !="")
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."pub_impression_hourly_backup table<br>".$mintm_res->get_sql();
			break;
		}

		$mintm_row=$mintm_res->fetch_assoc();
		$temp=intval($mintm_row['tm']);

		$mintm1_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."dailyclicks_backup");
		if($mintm1_res->error !="")
		{
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."dailyclicks_backup table<br>".$mintm1_res->get_sql();
			break;
		}

		$mintm1_row=$mintm1_res->fetch_assoc();
		$temp1=intval($mintm1_row['tm']);
		
		
				$temp2=0;
		if($cpa_addon_enabled ==1)
		{
			$mintm2_res=$db->execute_query("select min(time) as tm from ".TABLE_PREFIX."dailyconversions_backup");
			if($mintm2_res->error !="")
			{
				echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."dailyconversions_backup table<br>".$mintm2_res->get_sql();
				break;
			}
		
			$mintm2_row=$mintm2_res->fetch_assoc();
			$temp2=intval($mintm2_row['tm']);
		}
		
				
		if($temp ==0)
		echo "<br>Min time - imp = No Impressions.";
		else
		echo "<br>Min time - imp = ".$temp;
		
		if($temp1 ==0)
		echo "<br>Min time - clk = No Clicks.";
		else	
		echo "<br>Min time - clk = ".$temp1;
		
		
		
		if($cpa_addon_enabled ==1)
		{
			if($temp2 ==0)
			echo "<br>Min time - CPA conversion = No Conversions.";
			else	
			echo "<br>Min time - CPA conversion = ".$temp2;
		}	

		///////////////////////////////////////////////////////////////////////////
		
		if($cpa_addon_enabled ==1)
		{
			if($temp ==0 && $temp1 ==0 && $temp2 ==0)
			{
				echo "<br>No impressions,clicks and conversions";
			    break;
		    }
		}
		else
		{
			if($temp ==0 && $temp1 ==0)
			{
				echo "<br>No impressions and clicks";
			    break;
		    }
		}
		
		
		if($temp1 > $temp2 && $temp2 >0)
		$temp1=$temp2;
		
		
		if($temp > $temp1 && $temp1 >0)
		$temp=$temp1;
		
		if($temp ==0 && $temp1 >0)
		$temp=$temp1;
		else if($temp ==0 && $temp2 >0)
		$temp=$temp2;		
		
		if($temp >0)
		$day=substr($temp,0,-2);
		else
		{
			echo "<br>No data found";
			break;
		}		
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

	$hr_qry_res=$db->execute_query("select time,status from ".TABLE_PREFIX."statistics_updation where task=?",array('impression_updation_minute'));
	if($hr_qry_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$hr_qry_res->get_sql();
		break;
	}
	else
	{
		$stat_row_hourly=$hr_qry_res->fetch_assoc();

		$updationhour=substr($stat_row_hourly['time'],0,-2);

		if($updationhour  < $end)  
		{
			echo "<br>BREAKING - HOURLY PUBLISHER TASK INCOMPLETE";
			break;
		}		
	}
	
	$hr_qry_res1=$db->execute_query("select time,status from ".TABLE_PREFIX."statistics_updation where task=?",array('dailyclick_backup'));
	if($hr_qry_res1->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$hr_qry_res1->get_sql();
		break;
	}
	else
	{
		$stat_row_hourly1=$hr_qry_res1->fetch_assoc();
		$one_hour_less    = $this->get_previous_hour($current_hour);
		if( $stat_row_hourly1['time'] < $one_hour_less)  
		{
			echo "<br>BREAKING - DAILY CLICK BACKUP TASK INCOMPLETE";
			break;
		}
	}

	$del_hdata_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_pub_daily where time >=?",array($day));
	if($del_hdata_res->error !="")
	{
		echo "<br><br>Data clearing from ".TABLE_PREFIX."geo_statistics_pub_daily failed<br>".$del_hdata_res->get_sql();
		break;
	}

	$qry_res=$db->execute_query("SELECT pid,bid,cpd_target_id,sum(cpc_impression) as cpc_impression,country,source ".$cpm_select." ".$html_select." ".$siddata3." ".$cpa_select." ".$pop_select." ".$cpv_select.$cpd_select." FROM ".TABLE_PREFIX."pub_impression_hourly_backup WHERE (time>=? and time <=?) group by country,pid".$siddata3.",bid,cpd_target_id",array($start,$end));
	if($qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."pub_impression_hourly_backup table<br>".$qry_res->get_sql();
		break;
	}


	$qry1_res=$db->execute_query("SELECT pid,bid,cpd_target_id,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit,click_type,country,source".$cpdString." ".$siddata3." FROM ".TABLE_PREFIX."dailyclicks_backup WHERE (time>=? and time <=?)  group by country,click_type,pid".$siddata3.",bid,cpd_target_id",array($start,$end));
	if($qry1_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."dailyclicks_backup table<br>".$qry1_res->get_sql();
		break;
	}
	
	
	if($cpa_addon_enabled ==1)
	{
		
		$qry2_res=$db->execute_query("SELECT pid,bid,count(id) as ids,COALESCE(sum(clickvalue),0) as cvalue,COALESCE(sum(profit),0) as profit,country,conversion_type ".$siddata3." FROM ".TABLE_PREFIX."dailyconversions_backup WHERE (time>=? and time <=?)  group by country,conversion_type,pid".$siddata3.",bid",array($start,$end));
		if($qry2_res->error !="")
		{
			echo "<br>Data retrieval failed from ".TABLE_PREFIX."dailyconversions_backup table<br>".$qry2_res->get_sql();
			break;
		}
	}
	
	$result_arr=array();
	while($row=$qry_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['country'].':'.$row['pid'].':'.$row['sid'].':'.$row['bid'].':'.$row['cpd_target_id'];
		else 
		$key=$row['country'].':'.$row['pid'].':'.$row['bid'].':'.$row['cpd_target_id'];
			

		$result_arr[$key][0]=$row['pid'];
		$result_arr[$key][1]=$row['bid'];
		$result_arr[$key][2]=$row['cpc_impression'];
		$result_arr[$key][3]=0;
		$result_arr[$key][4]=0;
		$result_arr[$key][5]=0;
		$result_arr[$key][40]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$result_arr[$key][6]=$row['cpm_impression'];
			$result_arr[$key][7]=$row['cpm_spend'];
			$result_arr[$key][8]=$row['cpm_profit'];
		}
		
		if($html_addon_enabled ==1)
		{
			$result_arr[$key][10]=$row['html_impression'];
			$result_arr[$key][11]=$row['html_profit'];
		}
		
		
		if($category_targeting_enabled ==1)
		$result_arr[$key][12]=$row['sid'];
		
		
		$result_arr[$key][13]=0;
		$result_arr[$key][14]=$row['country'];
		
		if($cpa_addon_enabled ==1)
		$result_arr[$key][15]=$row['cpa_impression'];
		else
		$result_arr[$key][15]=0;
		
		
		$result_arr[$key][16]=0;
		$result_arr[$key][17]=0;
		$result_arr[$key][18]=0;
		
		
		if($pop_addon_enabled ==1)
		{
			$result_arr[$key][19]=$row['pop_impression'];
			$result_arr[$key][20]=$row['pop_spend'];
			$result_arr[$key][21]=$row['pop_profit'];
		}
		
		
		
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][23]=0;
			$result_arr[$key][24]=0;
			$result_arr[$key][25]=0;
		}
		
		
		$result_arr[$key][22]=0;
		$result_arr[$key][26]=0;
		$result_arr[$key][27]=0;
		
		if($video_addon_enabled ==1)
		{
			$result_arr[$key][28]=$row['cpv_impression'];
			$result_arr[$key][29]=$row['cpv_spend'];
			$result_arr[$key][30]=$row['cpv_profit'];
			$result_arr[$key][31]=0;
		}
		
		if($sponsored_enabled ==1)
		{
			$result_arr[$key][32]=$row['sponsored_impression'];		
			$result_arr[$key][33]=0;
			$result_arr[$key][34]=$row['sponsored_spend'];	
			$result_arr[$key][35]=$row['sponsored_profit'];				
		}		
		
		if($cpp_addon_enabled ==1)
		{
			$result_arr[$key][36]=$row['cpp_impression'];
			$result_arr[$key][37]=$row['cpp_spend'];
			$result_arr[$key][38]=$row['cpp_profit'];
			$result_arr[$key][39]=0;
		}

	}
	$qry_res->free_result();

	while($row=$qry1_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['country'].':'.$row['pid'].':'.$row['sid'].':'.$row['bid'].':'.$row['cpd_target_id'];
		else
		$key=$row['country'].':'.$row['pid'].':'.$row['bid'].':'.$row['cpd_target_id'];

		$result_arr[$key][0]=$row['pid'];
		$result_arr[$key][1]=$row['bid'];
		if(!isset($result_arr[$key][2]))
		$result_arr[$key][2]=0;
		
		
		
		
		
	    if($row['click_type'] ==0)
		{
			$result_arr[$key][3]=$row['ids'];
			$result_arr[$key][4]=$row['cvalue'];
			$result_arr[$key][5]=$row['profit'];
		}
		else if($row['click_type'] ==1)
		$result_arr[$key][26]=$row['ids'];
		else if($row['click_type'] ==6)
		$result_arr[$key][27]=$row['ids'];
		else if($row['click_type'] ==12)
		$result_arr[$key][22]=$row['ids'];
		else if($row['click_type'] ==13)
		$result_arr[$key][31]=$row['ids'];		
		else if($row['click_type'] ==3)
		$result_arr[$key][33]=$row['ids'];			
		else if($row['click_type'] ==18)
		$result_arr[$key][39]=$row['ids'];			
		
		if(!isset($result_arr[$key][40]))
    		$result_arr[$key][40]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][6]))
			$result_arr[$key][6]=0;
			
			if(!isset($result_arr[$key][7]))
			$result_arr[$key][7]=0;
			
			if(!isset($result_arr[$key][8]))
			$result_arr[$key][8]=0;
		}
		
		if($html_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][10]))
			$result_arr[$key][10]=0;
			
			if(!isset($result_arr[$key][11]))
			$result_arr[$key][11]=0;
		}
		
		
		
		
		
		if($category_targeting_enabled ==1)
		{
			if(!isset($result_arr[$key][12]))
			$result_arr[$key][12]=$row['sid'];
		}
		
		
		
		$result_arr[$key][13]=$row['click_type'];
		$result_arr[$key][14]=$row['country'];
		
		
		if(!isset($result_arr[$key][15]))
		$result_arr[$key][15]=0;
		
		
		$result_arr[$key][16]=0;
		$result_arr[$key][17]=0;
		$result_arr[$key][18]=0;
		if($pop_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][19]))
			$result_arr[$key][19]=0;
			
			if(!isset($result_arr[$key][20]))
			$result_arr[$key][20]=0;
			
			if(!isset($result_arr[$key][21]))
			$result_arr[$key][21]=0;
		}
		
		
		if($affiliate_addon_enabled ==1)
		{
			$result_arr[$key][23]=0;
			$result_arr[$key][24]=0;
			$result_arr[$key][25]=0;
		}
		
		if($video_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][28]))
			$result_arr[$key][28]=0;
			
			if(!isset($result_arr[$key][29]))
			$result_arr[$key][29]=0;
			
			if(!isset($result_arr[$key][30]))
			$result_arr[$key][30]=0;			
		}		
		
		if($sponsored_enabled ==1)
		{
			if(!isset($result_arr[$key][32]))
			$result_arr[$key][32]=0;
			
			if($row['click_type'] != 3)
			{
				if(!isset($result_arr[$key][34]))
				$result_arr[$key][34]=0;			
				
				if(!isset($result_arr[$key][35]))
				$result_arr[$key][35]=0;
			}	
			else
			{
				$result_arr[$key][34]=$row['cpd_spend'];
				$result_arr[$key][35]=$row['cpd_profit'];				
			}
		}	

		if($cpp_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][36]))
			$result_arr[$key][36]=0;
			
			if(!isset($result_arr[$key][37]))
			$result_arr[$key][37]=0;
			
			if(!isset($result_arr[$key][38]))
			$result_arr[$key][38]=0;			
		}



	}
	
	$qry1_res->free_result();
	

	
	if($cpa_addon_enabled ==1)
	{

	while($row=$qry2_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['country'].':'.$row['pid'].':'.$row['sid'].':'.$row['bid'].':0';
		else
		$key=$row['country'].':'.$row['pid'].':'.$row['bid'].':0';

		$result_arr[$key][0]=$row['pid'];
		$result_arr[$key][1]=$row['bid'];
		
		if(!isset($result_arr[$key][2]))
		$result_arr[$key][2]=0;
		
			if(!isset($result_arr[$key][3]))
			$result_arr[$key][3]=0;
			
			if(!isset($result_arr[$key][4]))
			$result_arr[$key][4]=0;
			
			if(!isset($result_arr[$key][5]))
			$result_arr[$key][5]=0;
		
			if(!isset($result_arr[$key][40]))
			$result_arr[$key][40]=0;
		
		
		if($cpm_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][6]))
			$result_arr[$key][6]=0;
			
			if(!isset($result_arr[$key][7]))
			$result_arr[$key][7]=0;
			
			if(!isset($result_arr[$key][8]))
			$result_arr[$key][8]=0;
		}
		
		if($html_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][10]))
			$result_arr[$key][10]=0;
			
			if(!isset($result_arr[$key][11]))
			$result_arr[$key][11]=0;
		}
		
				
		
		if($category_targeting_enabled ==1)
		{
			if(!isset($result_arr[$key][12]))
			$result_arr[$key][12]=$row['sid'];
		}
		

		if(!isset($result_arr[$key][13]))
		$result_arr[$key][13]=0;
		
		
		$result_arr[$key][14]=$row['country'];
		
		if(!isset($result_arr[$key][15]))
		$result_arr[$key][15]=0;
			





		
		if($row['conversion_type'] == 12) //Affiliate
		{
			$result_arr[$key][16]=0;
			$result_arr[$key][17]=0;
			$result_arr[$key][18]=0;

			if($affiliate_addon_enabled ==1)
			{
				$result_arr[$key][23]=$row['ids'];
				$result_arr[$key][24]=$row['cvalue'];
				$result_arr[$key][25]=$row['profit'];
			}
		}
		else //CPA
		{
			$result_arr[$key][16]=$row['ids'];
			$result_arr[$key][17]=$row['cvalue'];
			$result_arr[$key][18]=$row['profit'];

			if($affiliate_addon_enabled ==1)
			{
				$result_arr[$key][23]=0;
				$result_arr[$key][24]=0;
				$result_arr[$key][25]=0;
			}
		}

		if($pop_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][19]))
			$result_arr[$key][19]=0;
			
			if(!isset($result_arr[$key][20]))
			$result_arr[$key][20]=0;
			
			if(!isset($result_arr[$key][21]))
			$result_arr[$key][21]=0;
		}
		
	    if(!isset($result_arr[$key][22]))
	    $result_arr[$key][22]=0;
		
		if(!isset($result_arr[$key][26]))
		$result_arr[$key][26]=0;
		
		if(!isset($result_arr[$key][27]))
		$result_arr[$key][27]=0;
		
				
		if($video_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][28]))
			$result_arr[$key][28]=0;
			
			if(!isset($result_arr[$key][29]))
			$result_arr[$key][29]=0;
			
			if(!isset($result_arr[$key][30]))
			$result_arr[$key][30]=0;
			
			if(!isset($result_arr[$key][31]))
			$result_arr[$key][31]=0;						
		}		
						
		
		if($sponsored_enabled ==1)
		{
			if(!isset($result_arr[$key][32]))
			$result_arr[$key][32]=0;
			
			if(!isset($result_arr[$key][33]))
			$result_arr[$key][33]=0;

			if(!isset($result_arr[$key][34]))
			$result_arr[$key][34]=0;			
			
			if(!isset($result_arr[$key][35]))
			$result_arr[$key][35]=0;				
		}			
		
		if($cpp_addon_enabled ==1)
		{
			if(!isset($result_arr[$key][36]))
			$result_arr[$key][36]=0;
			
			if(!isset($result_arr[$key][37]))
			$result_arr[$key][37]=0;
			
			if(!isset($result_arr[$key][38]))
			$result_arr[$key][38]=0;	

			if(!isset($result_arr[$key][39]))
			$result_arr[$key][39]=0;						
		}

		
	}
		
	$qry2_res->free_result();
		
	}


	$num_row_inserted=0;
	$result_count=count($result_arr);

	$insert_data=array();
	$valuestring="";


	$advarraybatch=array();   // For Batch Insertion
	$iii=0;                   // For Batch Insertion
	$iiii=0;                  // For Batch Insertion



	foreach ( $result_arr as $k => $v)
	{
		
		if($valuestring !="")
		$valuestring.=',';
		$valuestring.="(?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		
		
		$clickcount1=0;
		$clickcount2=0;
		$clickcount3=0;
		$clickcount4=0;
		$clickcount5=0;		
		$clickcount6=0;		
		$clickcount7=0;

		if(isset($v[3]))
		$clickcount1=$v[3];

		if(isset($v[26]))
		$clickcount2=$v[26];

		if(isset($v[27]))
		$clickcount3=$v[27];

		if(isset($v[22]))
		$clickcount4=$v[22];

		if(isset($v[31]))
		$clickcount5=$v[31];

		if(isset($v[33]))		
		$clickcount6=$v[33];
		
		if(isset($v[39]))		
		$clickcount7=$v[39];	
		
		$insert_data[]='';
		$insert_data[]=$v[0];
		$insert_data[]=$v[1];
		$insert_data[]=$v[2];
		$insert_data[]=$clickcount1;
		$insert_data[]=$day;

		if(isset($v[4]))
		$insert_data[]=$v[4];
		else
		$insert_data[]=0;	

		if(isset($v[5]))
		$insert_data[]=$v[5];
		else
		$insert_data[]=0;

		if(isset($v[14]))
		$insert_data[]=$v[14];
		else
		$insert_data[]=0;

		$insert_data[]=$v[40];
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$v[6];
			$insert_data[]=$v[7];
			$insert_data[]=$v[8];
			$insert_data[]=$clickcount2;
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$v[10];
			$insert_data[]=$v[11];
		}
		
		if($category_targeting_enabled ==1)
		$insert_data[]=$v[12];
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$v[15];
			$insert_data[]=$clickcount3;
			$insert_data[]=$v[16];
			$insert_data[]=$v[17];
			$insert_data[]=$v[18];	
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$v[19];
			$insert_data[]=$v[20];
			$insert_data[]=$v[21];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$clickcount4;
			$insert_data[]=$v[23];
			$insert_data[]=$v[24];
			$insert_data[]=$v[25];
		}	
		
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$v[28];
			$insert_data[]=$v[29];
			$insert_data[]=$v[30];
			$insert_data[]=$clickcount5;	
		}
		

		if($sponsored_enabled ==1)
		{
			$insert_data[]=$v[32];
			$insert_data[]=$clickcount6;
			$insert_data[]=$v[34];
			$insert_data[]=$v[35];			
		}			
				

		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$v[36];
			$insert_data[]=$v[37];
			$insert_data[]=$v[38];
			$insert_data[]=$clickcount7;
		}

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

		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_pub_daily (`id`,`uid`, `bid`, `impression`,`click`,`time`,`money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
		if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
		else
		{
			echo "<br>Data insertion to ".TABLE_PREFIX."geo_statistics_pub_daily failed<br>".$ins_qry_res->get_sql();
			break;
			}
		}
	}

	if($num_row_inserted == $result_count)
	{
		if($day == $current_day)
		{
			/*
			if($day."23"!=$current_hour)
		{
			//partial completion
			$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'geo_statistics_pub_daily'));
				echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT DAY STATISTICS";
			break;
		}
		else
			{*/
				//partial completion
				$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($previous_hour,1,'geo_statistics_pub_daily'));
			
				if($up_st_res->get_error() =='')
				echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT DAY STATISTICS";
				else
				echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
			
				break;
			//}
		
		}
		else if($day < $current_day)
		{
			$up_st_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($day."23",2,'geo_statistics_pub_daily'));
		
			if($up_st_res->get_error() !='')
			echo "<br>Statistics updation failed in ".TABLE_PREFIX."statistics_updation table<br>".$up_st_res->get_sql();
		}
	}
	flush();
	//echo mysql_error();
}while(1);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Countrywise Advertiser Monthly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_monthly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."geo_statistics_adv_daily");
		if($min_qry_res->error =="")
		{
			$min_time_daily_row=$min_qry_res->fetch_assoc();
			if($min_time_daily_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."geo_statistics_adv_daily table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."geo_statistics_adv_daily table<br>".$min_qry_res->get_sql();
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
		echo "<br>BREAKING - LAST MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_month_substract($month);
		$delete_time=$delete_time."31";
		
	
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_adv_daily WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."geo_statistics_adv_daily<br>".$del_qry_res->get_sql();
		else 
		echo "<br>Old data deletion from ".TABLE_PREFIX."geo_statistics_adv_daily failed<br>".$del_qry_res->get_sql();
    }

	$start=	$month."01";
	$end=$month."31";

echo "<br>Currently building data for ".$month;

$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_daily'));
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



	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_adv_monthly WHERE time=?",array($month));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_adv_monthly failed<br>".$st_del_res->get_sql();
		break;
	}
	
	
	
	
	
	$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_adv_daily WHERE (time>=? and time<=?)  group by country,uid,aid,kid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_adv_daily table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['aid'];
		$insert_data[]=$row['kid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		$insert_data[]=$row['country'];
		$insert_data[]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$row['cpm_impression'];
			$insert_data[]=$row['cpm_spend'];
			$insert_data[]=$row['cpm_profit'];
			$insert_data[]=$row['cpm_click'];
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$row['html_impression'];
			$insert_data[]=$row['html_profit'];
		}
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$row['cpa_impression'];
			$insert_data[]=$row['cpa_click'];
			$insert_data[]=$row['cpa_conversion'];
			$insert_data[]=$row['cpa_spend'];
			$insert_data[]=$row['cpa_profit'];
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$row['pop_impression'];
			$insert_data[]=$row['pop_spend'];
			$insert_data[]=$row['pop_profit'];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$row['affiliate_click'];
			$insert_data[]=$row['affiliate_conversion'];
			$insert_data[]=$row['affiliate_spend'];
			$insert_data[]=$row['affiliate_profit'];
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];
		}

		
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$row['sponsored_impression'];
			$insert_data[]=$row['sponsored_click'];
			$insert_data[]=$row['sponsored_spend'];
			$insert_data[]=$row['sponsored_profit'];			
		}
		

		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$row['cpp_impression'];
			$insert_data[]=$row['cpp_spend'];
			$insert_data[]=$row['cpp_profit'];
			$insert_data[]=$row['cpp_click'];
		}	


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

	
	$sel_qry_res->free_result();	
	
	
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
			$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_adv_monthly (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
				echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_adv_monthly failed<br>".$ins_qry_res->get_sql();
				break;
			}	
		}
    }


	if($num_row_inserted == $result_count)
	{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$month,'geo_statistics_adv_monthly'));
	if($month==$this->get_previous_month($current_month))
	{
		echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
		break;
	}
}
else 
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$month,'geo_statistics_adv_monthly'));
	echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST MONTH STATISTICS";
	break;
}

	//echo mysql_error();
	flush();
}while (1);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Countrywise Publisher Monthly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_monthly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."geo_statistics_pub_daily");
		if($min_qry_res->error =="")
		{
			$min_time_daily_row=$min_qry_res->fetch_assoc();
			if($min_time_daily_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."geo_statistics_pub_daily table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."geo_statistics_pub_daily table<br>".$min_qry_res->get_sql();
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
		echo "<br>BREAKING - LAST MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_month_substract($month);
		$delete_time=$delete_time."31";
		

		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_pub_daily WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."geo_statistics_pub_daily<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."geo_statistics_pub_daily failed<br>".$del_qry_res->get_sql();
	}

	$start=	$month."01";
	$end=$month."31";
	echo "<br>Currently building data for ".$month;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_daily'));
	if($st_up_qry_res->error !="")
	{
		echo "<br>Data not got from ".TABLE_PREFIX."statistics_updation table<br>".$st_up_qry_res->get_sql();
		break;
	}
	$stat_row_daily=$st_up_qry_res->fetch_assoc();
	if( $stat_row_daily['time']<= $month."31"."23")
	{
		echo "<br>BREAKING - DAILY TABLE INCOMPLETE";
		break; //cannot build as the daily table data is not complete.
	}


	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_pub_monthly WHERE time=?",array($month));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_pub_monthly failed<br>".$st_del_res->get_sql();
		break;
	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_pub_daily WHERE (time>=? and time<=?)  group by country,uid".$siddata3.",bid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_pub_daily table<br>".$sel_qry_res->get_sql();
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

		$valuestring.="(?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		
		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['bid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		$insert_data[]=$row['country'];
		$insert_data[]=$row['source'];
		
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$row['cpm_impression'];
			$insert_data[]=$row['cpm_spend'];
			$insert_data[]=$row['cpm_profit'];
			$insert_data[]=$row['cpm_click'];
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$row['html_impression'];
			$insert_data[]=$row['html_profit'];
		}
		
		if($category_targeting_enabled ==1)
		$insert_data[]=$row['sid'];
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$row['cpa_impression'];
			$insert_data[]=$row['cpa_click'];
			$insert_data[]=$row['cpa_conversion'];
			$insert_data[]=$row['cpa_spend'];
			$insert_data[]=$row['cpa_profit'];
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$row['pop_impression'];
			$insert_data[]=$row['pop_spend'];
			$insert_data[]=$row['pop_profit'];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$row['affiliate_click'];
			$insert_data[]=$row['affiliate_conversion'];
			$insert_data[]=$row['affiliate_spend'];
			$insert_data[]=$row['affiliate_profit'];
		}
		
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];	
		}	
		
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$row['sponsored_impression'];
			$insert_data[]=$row['sponsored_click'];
			$insert_data[]=$row['sponsored_spend'];
			$insert_data[]=$row['sponsored_profit'];			
		}		
		

		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$row['cpp_impression'];
			$insert_data[]=$row['cpp_spend'];
			$insert_data[]=$row['cpp_profit'];
			$insert_data[]=$row['cpp_click'];
		}	

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
	
	
	$sel_qry_res->free_result();
	
	
	
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
		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_pub_monthly (`id`,`uid`, `bid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
			{
			echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_pub_monthly failed<br>".$ins_qry_res->get_sql();
			break;
		}
	}
    }


	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$month,'geo_statistics_pub_monthly'));
		if($month==$this->get_previous_month($current_month))
		{
			echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$month,'geo_statistics_pub_monthly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST MONTH STATISTICS";
		break;
	}
	//echo mysql_error();
	flush();
}while (1);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Countrywise Advertiser Yearly</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_yearly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."geo_statistics_adv_monthly");
		if($min_qry_res->error =="")
		{
			$min_time_monthly_row=$min_qry_res->fetch_assoc();
			if($min_time_monthly_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."geo_statistics_adv_monthly table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."geo_statistics_adv_monthly table<br>".$min_qry_res->get_sql();
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
		echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_previous_year($year)."12";
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_adv_monthly WHERE time<=?",array($delete_time));
	
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."geo_statistics_adv_monthly<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."geo_statistics_adv_monthly failed<br>".$del_qry_res->get_sql();
	}

$start=	$year."01";
$end=$year."12";

echo "<br>Currently building data for ".$year;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_monthly'));
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




	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_adv_yearly WHERE time=?",array($year));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_adv_yearly failed<br>".$st_del_res->get_sql();
		break;

	}

	$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_adv_monthly WHERE (time>=? and time<=?)  group by country,uid,aid,kid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_adv_monthly table<br>".$sel_qry_res->get_sql();
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
		$valuestring.="(?,?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		
		

		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['aid'];
		$insert_data[]=$row['kid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$year;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		$insert_data[]=$row['country'];
		$insert_data[]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$row['cpm_impression'];
			$insert_data[]=$row['cpm_spend'];
			$insert_data[]=$row['cpm_profit'];
			$insert_data[]=$row['cpm_click'];
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$row['html_impression'];
			$insert_data[]=$row['html_profit'];
		}
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$row['cpa_impression'];
			$insert_data[]=$row['cpa_click'];
			$insert_data[]=$row['cpa_conversion'];
			$insert_data[]=$row['cpa_spend'];
			$insert_data[]=$row['cpa_profit'];
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$row['pop_impression'];
			$insert_data[]=$row['pop_spend'];
			$insert_data[]=$row['pop_profit'];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$row['affiliate_click'];
			$insert_data[]=$row['affiliate_conversion'];
			$insert_data[]=$row['affiliate_spend'];
			$insert_data[]=$row['affiliate_profit'];
		}
		
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];
		}

		
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$row['sponsored_impression'];
			$insert_data[]=$row['sponsored_click'];
			$insert_data[]=$row['sponsored_spend'];
			$insert_data[]=$row['sponsored_profit'];			
		}		
		

		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$row['cpp_impression'];
			$insert_data[]=$row['cpp_spend'];
			$insert_data[]=$row['cpp_profit'];
			$insert_data[]=$row['cpp_click'];
		}	



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
	
	
	$sel_qry_res->free_result();
	
	
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
		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_adv_yearly (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
		{
			echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_adv_yearly failed<br>".$ins_qry_res->get_sql();
			break;
		}
	}
    }
	

   	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$year,'geo_statistics_adv_yearly'));
		if($year==$this->get_previous_year($current_year))
		{
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$year,'geo_statistics_adv_yearly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Countrywise Publisher Yearly</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_yearly'));
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
		$min_qry_res=$db->execute_query("SELECT min(time) as mt FROM ".TABLE_PREFIX."geo_statistics_pub_monthly");
		if($min_qry_res->error =="")
		{
			$min_time_monthly_row=$min_qry_res->fetch_assoc();
			if($min_time_monthly_row['mt']=='')
			{
				echo "<br>No data in the ".TABLE_PREFIX."geo_statistics_pub_monthly table";
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
			echo "<br>Query failed while getting minimum time from ".TABLE_PREFIX."geo_statistics_pub_monthly table<br>".$min_qry_res->get_sql();
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
		echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
		break;
	}

	if($update_status==2)
	{
		$delete_time=$this->get_previous_year($year)."12";
		$del_qry_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_pub_monthly WHERE time<=?",array($delete_time));
		if($del_qry_res->error =="")
		echo "<br>Old data deleted from ".TABLE_PREFIX."geo_statistics_pub_monthly<br>".$del_qry_res->get_sql();
		else
		echo "<br>Old data deletion from ".TABLE_PREFIX."geo_statistics_pub_monthly failed<br>".$del_qry_res->get_sql();
	}

	$start=	$year."01";
	$end=$year."12";
	echo "<br>Currently building data for ".$year;

	$st_up_qry_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_monthly'));
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



	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_pub_yearly WHERE time=?",array($year));
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_pub_yearly failed<br>".$st_del_res->get_sql();
		break;

	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_pub_monthly WHERE (time>=? and time<=?)  group by country,uid".$siddata3.",bid",array($start,$end));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_pub_monthly table<br>".$sel_qry_res->get_sql();
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
		$valuestring.="(?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		
		

		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['bid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$year;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		$insert_data[]=$row['country'];
		$insert_data[]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$row['cpm_impression'];
			$insert_data[]=$row['cpm_spend'];
			$insert_data[]=$row['cpm_profit'];
			$insert_data[]=$row['cpm_click'];
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$row['html_impression'];
			$insert_data[]=$row['html_profit'];
		}
		
		if($category_targeting_enabled ==1)
		$insert_data[]=$row['sid'];
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$row['cpa_impression'];
			$insert_data[]=$row['cpa_click'];
			$insert_data[]=$row['cpa_conversion'];
			$insert_data[]=$row['cpa_spend'];
			$insert_data[]=$row['cpa_profit'];
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$row['pop_impression'];
			$insert_data[]=$row['pop_spend'];
			$insert_data[]=$row['pop_profit'];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$row['affiliate_click'];
			$insert_data[]=$row['affiliate_conversion'];
			$insert_data[]=$row['affiliate_spend'];
			$insert_data[]=$row['affiliate_profit'];
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];	
		}
		
		
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$row['sponsored_impression'];
			$insert_data[]=$row['sponsored_click'];
			$insert_data[]=$row['sponsored_spend'];
			$insert_data[]=$row['sponsored_profit'];			
		}		
		

		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$row['cpp_impression'];
			$insert_data[]=$row['cpp_spend'];
			$insert_data[]=$row['cpp_profit'];
			$insert_data[]=$row['cpp_click'];
		}			
		
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
	
	
	$sel_qry_res->free_result();
	
	
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
		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_pub_yearly (`id`,`uid`,`bid`,`impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
		{
			echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_pub_yearly failed<br>".$ins_qry_res->get_sql();
			break;
		}
	}
   }
			
	
   	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$year,'geo_statistics_pub_yearly'));
		if($year==$this->get_previous_year($current_year))
		{
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			break;
		}
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$year,'geo_statistics_pub_yearly'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;

	}
	//echo mysql_error();
	flush();
}while (1);

////////*/////////*/////////*/////////*//////////*//////////*//////////*///////////*//////////*//////////*//////////

echo "<br><br><strong>Countrywise Advertiser Monthly Temp</strong><br>";

do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_monthly_temp'));
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
			echo "<br>BREAKING - CURRENT MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
	*/
	
	//$start=	$current_month."01"."00";
	
	$start=	$current_month."01";
	$end=$current_hour;

	echo "<br>Currently building data for ".$start."00"." - ".$end;


	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_adv_monthly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_adv_monthly_temp failed<br>".$st_del_res->get_sql();
		break;
	}

	$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_adv_daily WHERE (time>=?)  group by country,uid,aid,kid",array($start));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_adv_daily table<br>".$sel_qry_res->get_sql();
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
		
		$valuestring.="(?,?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		

		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['aid'];
		$insert_data[]=$row['kid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$current_month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		$insert_data[]=$row['country'];
		$insert_data[]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$row['cpm_impression'];
			$insert_data[]=$row['cpm_spend'];
			$insert_data[]=$row['cpm_profit'];
			$insert_data[]=$row['cpm_click'];
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$row['html_impression'];
			$insert_data[]=$row['html_profit'];
		}
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$row['cpa_impression'];
			$insert_data[]=$row['cpa_click'];
			$insert_data[]=$row['cpa_conversion'];
			$insert_data[]=$row['cpa_spend'];
			$insert_data[]=$row['cpa_profit'];
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$row['pop_impression'];
			$insert_data[]=$row['pop_spend'];
			$insert_data[]=$row['pop_profit'];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$row['affiliate_click'];
			$insert_data[]=$row['affiliate_conversion'];
			$insert_data[]=$row['affiliate_spend'];
			$insert_data[]=$row['affiliate_profit'];
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];	
		}	
		
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$row['sponsored_impression'];
			$insert_data[]=$row['sponsored_click'];
			$insert_data[]=$row['sponsored_spend'];
			$insert_data[]=$row['sponsored_profit'];			
		}		
		

		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$row['cpp_impression'];
			$insert_data[]=$row['cpp_spend'];
			$insert_data[]=$row['cpp_profit'];
			$insert_data[]=$row['cpp_click'];
		}	


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
	
	
	$sel_qry_res->free_result();
	
	
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
		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_adv_monthly_temp (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
		{
			echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_adv_monthly_temp failed<br>".$ins_qry_res->get_sql();
			break;
		}
	}
   }
	


	if($result_count == $num_row_inserted)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'geo_statistics_adv_monthly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'geo_statistics_adv_monthly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT MONTH STATISTICS";
		break;
	}

//echo mysql_error();
flush();
}while (1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Countrywise Publisher Monthly Temp</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_monthly_temp'));

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
			echo "<br>BREAKING - CURRENT MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
	*/
	//$start=	$current_month."01"."00";
	
	$start=	$current_month."01";
	$end=$current_hour;

	echo "<br>Currently building data for ".$start."00"." - ".$end;

	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_pub_monthly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_pub_monthly_temp failed<br>".$st_del_res->get_sql();
		break;
	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_pub_daily WHERE (time>=?)  group by country,uid".$siddata3.",bid",array($start));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_pub_daily table<br>".$sel_qry_res->get_sql();
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
		$valuestring.="(?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		

		
		$insert_data[]='';
		$insert_data[]=$row['uid'];
		$insert_data[]=$row['bid'];
		$insert_data[]=$row['imp'];
		$insert_data[]=$row['clk'];
		$insert_data[]=$current_month;
		$insert_data[]=$row['spent'];
		$insert_data[]=$row['profit'];
		$insert_data[]=$row['country'];
		$insert_data[]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$row['cpm_impression'];
			$insert_data[]=$row['cpm_spend'];
			$insert_data[]=$row['cpm_profit'];
			$insert_data[]=$row['cpm_click'];
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$row['html_impression'];
			$insert_data[]=$row['html_profit'];
		}
		
		if($category_targeting_enabled ==1)
		$insert_data[]=$row['sid'];
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$row['cpa_impression'];
			$insert_data[]=$row['cpa_click'];
			$insert_data[]=$row['cpa_conversion'];
			$insert_data[]=$row['cpa_spend'];
			$insert_data[]=$row['cpa_profit'];
		}
		
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$row['pop_impression'];
			$insert_data[]=$row['pop_spend'];
			$insert_data[]=$row['pop_profit'];
		}
		
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$row['affiliate_click'];
			$insert_data[]=$row['affiliate_conversion'];
			$insert_data[]=$row['affiliate_spend'];
			$insert_data[]=$row['affiliate_profit'];
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$row['cpv_impression'];
			$insert_data[]=$row['cpv_spend'];
			$insert_data[]=$row['cpv_profit'];
			$insert_data[]=$row['cpv_click'];
		}
	
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$row['sponsored_impression'];
			$insert_data[]=$row['sponsored_click'];
			$insert_data[]=$row['sponsored_spend'];
			$insert_data[]=$row['sponsored_profit'];			
		}		
		
		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$row['cpp_impression'];
			$insert_data[]=$row['cpp_spend'];
			$insert_data[]=$row['cpp_profit'];
			$insert_data[]=$row['cpp_click'];
		}			
		
		
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
	
	
	$sel_qry_res->free_result();
	
	
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
		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_pub_monthly_temp (`id`,`uid`, `bid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
			if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
			else
		{
			echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_pub_monthly_temp failed<br>".$ins_qry_res->get_sql();
			break;
		}
	}
    }
	
	if($result_count == $num_row_inserted)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'geo_statistics_pub_monthly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'geo_statistics_pub_monthly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING CURRENT MONTH STATISTICS";
		break;
	}
	//echo mysql_error();
	flush();
}while (1);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Countrywise Advertiser Yearly Temp</strong><br>";
do
{

$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_adv_yearly_temp'));
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
			echo "<br>BREAKING - CURRENT YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
	*/
//$start=	$current_year."01"."01"."00";

$start=	$current_year."01";
$end=$current_hour;

$year_begin=$current_year."01";
$month_begin=$current_year.date("m",time());

echo "<br>Currently building data for ".$start."01"."00"." - ".$end;

$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_adv_yearly_temp");
if($st_del_res->error !="")
{
	echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_adv_yearly_temp failed<br>".$st_del_res->get_sql();
	break;

}

$sel_qry_res=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_adv_monthly WHERE (time>=?)  group by country,uid,aid,kid",array($year_begin));
if($sel_qry_res->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_adv_monthly table<br>".$sel_qry_res->get_sql();
	break;

}

$sel_qry_res1=$db->execute_query("SELECT uid,aid,kid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_adv_monthly_temp WHERE (time>=?)  group by country,uid,aid,kid",array($month_begin));
if($sel_qry_res1->error !="")
{
	echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_adv_monthly_temp table<br>".$sel_qry_res->get_sql();
	break;

}

$array=array();
while($row=$sel_qry_res->fetch_assoc())
{
	$key=$row['country'].':'.$row['uid'].':'.$row['aid'].':'.$row['kid'];

	$array[$key][0]=$row['uid'];
	$array[$key][1]=$row['aid'];
	$array[$key][2]=$row['kid'];
	$array[$key][3]=$row['imp'];
	$array[$key][4]=$row['clk'];
	$array[$key][5]=$row['spent'];
	$array[$key][6]=$row['profit'];
	$array[$key][39]=$row['source'];
	
	if($cpm_addon_enabled ==1)
	{
		$array[$key][7]=$row['cpm_impression'];
		$array[$key][8]=$row['cpm_spend'];
		$array[$key][9]=$row['cpm_profit'];
		$array[$key][12]=$row['cpm_click'];
	}
	
	
	if($html_addon_enabled ==1)
	{
		$array[$key][10]=$row['html_impression'];
		$array[$key][11]=$row['html_profit'];
	}
	
	$array[$key][13]=$row['country'];
	
	
	if($cpa_addon_enabled ==1)
	{
		$array[$key][14]=$row['cpa_impression'];
		$array[$key][15]=$row['cpa_click'];
		$array[$key][16]=$row['cpa_conversion'];
		$array[$key][17]=$row['cpa_spend'];
		$array[$key][18]=$row['cpa_profit'];
	}
	
	if($pop_addon_enabled ==1)
	{
		$array[$key][19]=$row['pop_impression'];
		$array[$key][20]=$row['pop_spend'];
		$array[$key][21]=$row['pop_profit'];
	}
	
	
	if($affiliate_addon_enabled ==1)
	{
		$array[$key][22]=$row['affiliate_click'];
		$array[$key][23]=$row['affiliate_conversion'];
		$array[$key][24]=$row['affiliate_spend'];
		$array[$key][25]=$row['affiliate_profit'];
	}
	
	
	if($video_addon_enabled ==1)
	{
		$array[$key][26]=$row['cpv_impression'];
		$array[$key][27]=$row['cpv_spend'];
		$array[$key][28]=$row['cpv_profit'];
		$array[$key][29]=$row['cpv_click'];		
	}

	if($sponsored_enabled ==1)
	{
		$array[$key][31]=$row['sponsored_impression'];
		$array[$key][32]=$row['sponsored_click'];
		$array[$key][33]=$row['sponsored_spend'];
		$array[$key][34]=$row['sponsored_profit'];			
		
		
	}	


	if($cpp_addon_enabled ==1)
	{
		$array[$key][35]=$row['cpp_impression'];
		$array[$key][36]=$row['cpp_spend'];
		$array[$key][37]=$row['cpp_profit'];
		$array[$key][38]=$row['cpp_click'];
	}

	
	
}


$sel_qry_res->free_result();



while($row=$sel_qry_res1->fetch_assoc())
{
	$key=$row['country'].':'.$row['uid'].':'.$row['aid'].':'.$row['kid'];

	if(!isset($array[$key][0]))
	$array[$key][0]=$row['uid'];

	if(!isset($array[$key][1]))
	$array[$key][1]=$row['aid'];

	if(!isset($array[$key][2]))
	$array[$key][2]=$row['kid'];


	if(!isset($array[$key][3]))
	$array[$key][3]=$row['imp'];
	else
	$array[$key][3]=$array[$key][3]+$row['imp'];

	if(!isset($array[$key][4]))
	$array[$key][4]=$row['clk'];
	else
	$array[$key][4]=$array[$key][4]+$row['clk'];

	if(!isset($array[$key][5]))
	$array[$key][5]=$row['spent'];
	else
	$array[$key][5]=$array[$key][5]+$row['spent'];

	if(!isset($array[$key][6]))
	$array[$key][6]=$row['profit'];
	else
	$array[$key][6]=$array[$key][6]+$row['profit'];
	
	if(!isset($array[$key][39]))
  	$array[$key][39]=$row['source'];
	if($cpm_addon_enabled ==1)
	{
		if(!isset($array[$key][7]))
		$array[$key][7]=$row['cpm_impression'];
		else
		$array[$key][7]=$array[$key][7]+$row['cpm_impression'];
		
		
		if(!isset($array[$key][8]))
		$array[$key][8]=$row['cpm_spend'];
		else
		$array[$key][8]=$array[$key][8]+$row['cpm_spend'];
		
		if(!isset($array[$key][9]))
		$array[$key][9]=$row['cpm_profit'];
		else
		$array[$key][9]=$array[$key][9]+$row['cpm_profit'];
		
		if(!isset($array[$key][12]))
		$array[$key][12]=$row['cpm_click'];
		else
		$array[$key][12]=$array[$key][12]+$row['cpm_click'];
	}
	
	
	
	if($html_addon_enabled ==1)
	{
		if(!isset($array[$key][10]))
		$array[$key][10]=$row['html_impression'];
		else
		$array[$key][10]=$array[$key][10]+$row['html_impression'];
		
		if(!isset($array[$key][11]))
		$array[$key][11]=$row['html_profit'];
		else
		$array[$key][11]=$array[$key][11]+$row['html_profit'];
	}
	
	if(!isset($array[$key][13]))
	$array[$key][13]=$row['country'];
	
	
	if($cpa_addon_enabled ==1)
	{
		if(!isset($array[$key][14]))
		$array[$key][14]=$row['cpa_impression'];
		else
		$array[$key][14]=$array[$key][14]+$row['cpa_impression'];
		
		if(!isset($array[$key][15]))
		$array[$key][15]=$row['cpa_click'];
		else
		$array[$key][15]=$array[$key][15]+$row['cpa_click'];
		
		if(!isset($array[$key][16]))
		$array[$key][16]=$row['cpa_conversion'];
		else
		$array[$key][16]=$array[$key][16]+$row['cpa_conversion'];
		
		if(!isset($array[$key][17]))
		$array[$key][17]=$row['cpa_spend'];
		else
		$array[$key][17]=$array[$key][17]+$row['cpa_spend'];
		
		if(!isset($array[$key][18]))
		$array[$key][18]=$row['cpa_profit'];
		else
		$array[$key][18]=$array[$key][18]+$row['cpa_profit'];
	}
	
	
	if($pop_addon_enabled ==1)
	{
		if(!isset($array[$key][19]))
		$array[$key][19]=$row['pop_impression'];
		else
		$array[$key][19]=$array[$key][19]+$row['pop_impression'];
		
		
		if(!isset($array[$key][20]))
		$array[$key][20]=$row['pop_spend'];
		else
		$array[$key][20]=$array[$key][20]+$row['pop_spend'];
		
		if(!isset($array[$key][21]))
		$array[$key][21]=$row['pop_profit'];
		else
		$array[$key][21]=$array[$key][21]+$row['pop_profit'];
	}
	
	
	if($affiliate_addon_enabled ==1)
	{
		if(!isset($array[$key][22]))
		$array[$key][22]=$row['affiliate_click'];
		else
		$array[$key][22]=$array[$key][22]+$row['affiliate_click'];
		
		
		if(!isset($array[$key][23]))
		$array[$key][23]=$row['affiliate_conversion'];
		else
		$array[$key][23]=$array[$key][23]+$row['affiliate_conversion'];
		
		if(!isset($array[$key][24]))
		$array[$key][24]=$row['affiliate_spend'];
		else
		$array[$key][24]=$array[$key][24]+$row['affiliate_spend'];
		
		
		if(!isset($array[$key][25]))
		$array[$key][25]=$row['affiliate_profit'];
		else
		$array[$key][25]=$array[$key][25]+$row['affiliate_profit'];		
		

	}
	
	
	if($video_addon_enabled ==1)
	{
		if(!isset($array[$key][26]))
		$array[$key][26]=$row['cpv_impression'];
		else
		$array[$key][26]=$array[$key][26]+$row['cpv_impression'];
		
		
		if(!isset($array[$key][27]))
		$array[$key][27]=$row['cpv_spend'];
		else
		$array[$key][27]=$array[$key][27]+$row['cpv_spend'];
		
		if(!isset($array[$key][28]))
		$array[$key][28]=$row['cpv_profit'];
		else
		$array[$key][28]=$array[$key][28]+$row['cpv_profit'];
		
		if(!isset($array[$key][29]))
		$array[$key][29]=$row['cpv_click'];
		else
		$array[$key][29]=$array[$key][29]+$row['cpv_click'];		
	}
	
	
	
	if($sponsored_enabled ==1)
	{
		if(!isset($array[$key][31]))
		$array[$key][31]=$row['sponsored_impression'];
		else
		$array[$key][31]=$array[$key][31]+$row['sponsored_impression'];		
		
		if(!isset($array[$key][32]))
		$array[$key][32]=$row['sponsored_click'];
		else
		$array[$key][32]=$array[$key][32]+$row['sponsored_click'];		
		
		if(!isset($array[$key][33]))
		$array[$key][33]=$row['sponsored_spend'];
		else
		$array[$key][33]=$array[$key][33]+$row['sponsored_spend'];			

		if(!isset($array[$key][34]))
		$array[$key][34]=$row['sponsored_profit'];
		else
		$array[$key][34]=$array[$key][34]+$row['sponsored_profit'];				
		
		
		
	}	


	if($cpp_addon_enabled ==1)
	{
		if(!isset($array[$key][35]))
		$array[$key][35]=$row['cpp_impression'];
		else
		$array[$key][35]=$array[$key][35]+$row['cpp_impression'];
		
		
		if(!isset($array[$key][36]))
		$array[$key][36]=$row['cpp_spend'];
		else
		$array[$key][36]=$array[$key][36]+$row['cpp_spend'];
		
		if(!isset($array[$key][37]))
		$array[$key][37]=$row['cpp_profit'];
		else
		$array[$key][37]=$array[$key][37]+$row['cpp_profit'];
		
		if(!isset($array[$key][38]))
		$array[$key][38]=$row['cpp_click'];
		else
		$array[$key][38]=$array[$key][38]+$row['cpp_click'];
	}

}


$sel_qry_res1->free_result();


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

	$valuestring.="(?,?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2." )";
	
	
	$insert_data[]='';
	$insert_data[]=$v[0];
	$insert_data[]=$v[1];
	$insert_data[]=$v[2];
	$insert_data[]=$v[3];
	$insert_data[]=$v[4];
	$insert_data[]=$current_year;
	$insert_data[]=$v[5];
	$insert_data[]=$v[6];
	$insert_data[]=$v[13];
  	$insert_data[]=$v[39];
	
	
	
	if($cpm_addon_enabled ==1)
	{
		$insert_data[]=$v[7];
		$insert_data[]=$v[8];
		$insert_data[]=$v[9];
		$insert_data[]=$v[12];
	}
	
	if($html_addon_enabled ==1)
	{
		$insert_data[]=$v[10];
		$insert_data[]=$v[11];
	}
	
	if($cpa_addon_enabled ==1)
	{
		$insert_data[]=$v[14];
		$insert_data[]=$v[15];
		$insert_data[]=$v[16];
		$insert_data[]=$v[17];
		$insert_data[]=$v[18];
	}
	
	if($pop_addon_enabled ==1)
	{
		$insert_data[]=$v[19];
		$insert_data[]=$v[20];
		$insert_data[]=$v[21];
	}
	
	if($affiliate_addon_enabled ==1)
	{
		$insert_data[]=$v[22];
		$insert_data[]=$v[23];
		$insert_data[]=$v[24];
		$insert_data[]=$v[25];
	}
	
	if($video_addon_enabled ==1)
	{
		$insert_data[]=$v[26];
		$insert_data[]=$v[27];
		$insert_data[]=$v[28];
		$insert_data[]=$v[29];	
	}
	
	if($sponsored_enabled ==1)
	{
		$insert_data[]=$v[31];
		$insert_data[]=$v[32];		
		$insert_data[]=$v[33];	
		$insert_data[]=$v[34];			
	}	
	

	if($cpp_addon_enabled ==1)
	{
		$insert_data[]=$v[35];
		$insert_data[]=$v[36];
		$insert_data[]=$v[37];
		$insert_data[]=$v[38];	
	}

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
	$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_adv_yearly_temp (`id`,`uid`, `aid`, `kid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
	if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
	else
	{
		echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_adv_yearly_temp failed<br>".$ins_qry_res->get_sql();
		break;
	}
}
    }

if($num_row_inserted == $result_count)
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'geo_statistics_adv_yearly_temp'));
	echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST YEAR STATISTICS";
	break;
}
else
{
	$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'geo_statistics_adv_yearly_temp'));
	echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
    break;
}
//echo mysql_error();
flush();
}while (1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br><strong>Countrywise Publisher Yearly Temp</strong><br>";
do
{
	$supdation_res=$db->execute_query("SELECT time,status FROM ".TABLE_PREFIX."statistics_updation WHERE task=?",array('geo_statistics_pub_yearly_temp'));
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
			echo "<br>BREAKING - CURRENT YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!";
			break;
		}
	}
	*/
	//$start=	$current_year."01"."01"."00";
	
	$start=	$current_year."01";
	$end=$current_hour;
	
	$year_begin=$current_year."01";
	$month_begin=$current_year.date("m",time());


	echo "<br>Currently building data for ".$start."01"."00"." - ".$end;
	$st_del_res=$db->execute_query("DELETE FROM ".TABLE_PREFIX."geo_statistics_pub_yearly_temp");
	if($st_del_res->error !="")
	{
		echo "<br>Data clearing from ".TABLE_PREFIX."geo_statistics_pub_yearly_temp failed<br>".$st_del_res->get_sql();
		break;
	}

	$sel_qry_res=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_pub_monthly WHERE (time>=?)  group by country,uid".$siddata3.",bid",array($year_begin));
	if($sel_qry_res->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_pub_monthly table<br>".$sel_qry_res->get_sql();
		break;
	}

	$sel_qry_res1=$db->execute_query("SELECT uid,bid,sum(impression) as imp,sum(click) as clk,sum(money_spent) as spent,sum(pub_profit) as profit,country,source ".$cpm_select.$cpm_select1." ".$html_select." ".$siddata3." ".$cpa_select3." ".$pop_select." ".$affiliate_select3." ".$cpv_select.$cpv_select1." FROM ".TABLE_PREFIX."geo_statistics_pub_monthly_temp WHERE (time>=?)  group by country,uid".$siddata3.",bid",array($month_begin));
	if($sel_qry_res1->error !="")
	{
		echo "<br>Data retrieval failed from ".TABLE_PREFIX."geo_statistics_pub_monthly_temp table<br>".$sel_qry_res->get_sql();
		break;
	}

	$array=array();
	while($row=$sel_qry_res->fetch_assoc())
	{
		if($category_targeting_enabled ==1)
		$key=$row['country'].':'.$row['uid'].':'.$row['sid'].':'.$row['bid'];
		else
		$key=$row['country'].':'.$row['uid'].':'.$row['bid'];

		$array[$key][0]=$row['uid'];
		$array[$key][1]=$row['bid'];
		$array[$key][2]=$row['imp'];
		$array[$key][3]=$row['clk'];
		$array[$key][4]=$row['spent'];
		$array[$key][5]=$row['profit'];
		$array[$key][39]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			$array[$key][6]=$row['cpm_impression'];
			$array[$key][7]=$row['cpm_spend'];
			$array[$key][8]=$row['cpm_profit'];
			$array[$key][13]=$row['cpm_click'];
		}
		
		if($html_addon_enabled ==1)
		{
			$array[$key][10]=$row['html_impression'];
			$array[$key][11]=$row['html_profit'];
		}

		if($category_targeting_enabled ==1)
		$array[$key][12]=$row['sid'];
		
		$array[$key][14]=$row['country'];
		
		
		if($cpa_addon_enabled ==1)
		{
			$array[$key][15]=$row['cpa_impression'];
			$array[$key][16]=$row['cpa_click'];
			$array[$key][17]=$row['cpa_conversion'];
			$array[$key][18]=$row['cpa_spend'];
			$array[$key][19]=$row['cpa_profit'];
		}
	
		if($pop_addon_enabled ==1)
		{
			$array[$key][20]=$row['pop_impression'];
			$array[$key][21]=$row['pop_spend'];
			$array[$key][22]=$row['pop_profit'];
		}
	
		
		
		if($affiliate_addon_enabled ==1)
		{
			$array[$key][23]=$row['affiliate_click'];
			$array[$key][24]=$row['affiliate_conversion'];
			$array[$key][25]=$row['affiliate_spend'];
			$array[$key][26]=$row['affiliate_profit'];
		}
	
		
		
		if($video_addon_enabled ==1)
		{
			$array[$key][27]=$row['cpv_impression'];
			$array[$key][28]=$row['cpv_spend'];
			$array[$key][29]=$row['cpv_profit'];
			$array[$key][30]=$row['cpv_click'];		
		}		
		
		if($sponsored_enabled ==1)
		{
			$array[$key][31]=$row['sponsored_impression'];
			$array[$key][32]=$row['sponsored_click'];
			$array[$key][33]=$row['sponsored_spend'];
			$array[$key][34]=$row['sponsored_profit'];			
		}	
			

		if($cpp_addon_enabled ==1)
		{
			$array[$key][35]=$row['cpp_impression'];
			$array[$key][36]=$row['cpp_spend'];
			$array[$key][37]=$row['cpp_profit'];
			$array[$key][38]=$row['cpp_click'];
		}	
		
	}
	
	$sel_qry_res->free_result();	
	
	
	

	while($row=$sel_qry_res1->fetch_assoc())
	{
		
		if($category_targeting_enabled ==1)
		$key=$row['country'].':'.$row['uid'].':'.$row['sid'].':'.$row['bid'];
		else
		$key=$row['country'].':'.$row['uid'].':'.$row['bid'];

		if(!isset($array[$key][0]))
		$array[$key][0]=$row['uid'];


		if(!isset($array[$key][1]))
		$array[$key][1]=$row['bid'];

		if(!isset($array[$key][2]))
		$array[$key][2]=$row['imp'];
		else
		$array[$key][2]=$array[$key][2]+$row['imp'];

		if(!isset($array[$key][3]))
		$array[$key][3]=$row['clk'];
		else
		$array[$key][3]=$array[$key][3]+$row['clk'];

		if(!isset($array[$key][4]))
		$array[$key][4]=$row['spent'];
		else
		$array[$key][4]=$array[$key][4]+$row['spent'];

		if(!isset($array[$key][5]))
		$array[$key][5]=$row['profit'];
		else
		$array[$key][5]=$array[$key][5]+$row['profit'];
		
		if(!isset($array[$key][39]))
		$array[$key][39]=$row['source'];
		
		if($cpm_addon_enabled ==1)
		{
			if(!isset($array[$key][6]))
			$array[$key][6]=$row['cpm_impression'];
			else
			$array[$key][6]=$array[$key][6]+$row['cpm_impression'];
		
		
			if(!isset($array[$key][7]))
			$array[$key][7]=$row['cpm_spend'];
			else
			$array[$key][7]=$array[$key][7]+$row['cpm_spend'];
			
			
			if(!isset($array[$key][8]))
			$array[$key][8]=$row['cpm_profit'];
			else
			$array[$key][8]=$array[$key][8]+$row['cpm_profit'];
			
			
			if(!isset($array[$key][13]))
			$array[$key][13]=$row['cpm_click'];
			else
			$array[$key][13]=$array[$key][13]+$row['cpm_click'];
			
		}
		
		
		
		if($html_addon_enabled ==1)
		{
			if(!isset($array[$key][10]))
			$array[$key][10]=$row['html_impression'];
			else
			$array[$key][10]=$array[$key][10]+$row['html_impression'];
		
			if(!isset($array[$key][11]))
			$array[$key][11]=$row['html_profit'];
			else
			$array[$key][11]=$array[$key][11]+$row['html_profit'];
		}
		
		if($category_targeting_enabled ==1)
		{
			if(!isset($array[$key][12]))
			$array[$key][12]=$row['sid'];
		}
		
		if(!isset($array[$key][14]))
		$array[$key][14]=$row['country'];
		
		
		
		if($cpa_addon_enabled ==1)
		{
			if(!isset($array[$key][15]))
			$array[$key][15]=$row['cpa_impression'];
			else
			$array[$key][15]=$array[$key][15]+$row['cpa_impression'];
			
			if(!isset($array[$key][16]))
			$array[$key][16]=$row['cpa_click'];
			else
			$array[$key][16]=$array[$key][16]+$row['cpa_click'];
			
			if(!isset($array[$key][17]))
			$array[$key][17]=$row['cpa_conversion'];
			else
			$array[$key][17]=$array[$key][17]+$row['cpa_conversion'];
			
			if(!isset($array[$key][18]))
			$array[$key][18]=$row['cpa_spend'];
			else
			$array[$key][18]=$array[$key][18]+$row['cpa_spend'];
			
			if(!isset($array[$key][19]))
			$array[$key][19]=$row['cpa_profit'];
			else
			$array[$key][19]=$array[$key][19]+$row['cpa_profit'];
		}
		
		
		if($pop_addon_enabled ==1)
		{
			if(!isset($array[$key][20]))
			$array[$key][20]=$row['pop_impression'];
			else
			$array[$key][20]=$array[$key][20]+$row['pop_impression'];
			
			
			if(!isset($array[$key][21]))
			$array[$key][21]=$row['pop_spend'];
			else
			$array[$key][21]=$array[$key][21]+$row['pop_spend'];
			
			if(!isset($array[$key][22]))
			$array[$key][22]=$row['pop_profit'];
			else
			$array[$key][22]=$array[$key][22]+$row['pop_profit'];
		}
	
		if($affiliate_addon_enabled ==1)
		{
			if(!isset($array[$key][23]))
			$array[$key][23]=$row['affiliate_click'];
			else
			$array[$key][23]=$array[$key][23]+$row['affiliate_click'];
			
			
			if(!isset($array[$key][24]))
			$array[$key][24]=$row['affiliate_conversion'];
			else
			$array[$key][24]=$array[$key][24]+$row['affiliate_conversion'];
			
			if(!isset($array[$key][25]))
			$array[$key][25]=$row['affiliate_spend'];
			else
			$array[$key][25]=$array[$key][25]+$row['affiliate_spend'];
			
			
			if(!isset($array[$key][26]))
			$array[$key][26]=$row['affiliate_profit'];
			else
			$array[$key][26]=$array[$key][26]+$row['affiliate_profit'];		
			
	
		}
		
		if($video_addon_enabled ==1)
		{
			if(!isset($array[$key][27]))
			$array[$key][27]=$row['cpv_impression'];
			else
			$array[$key][27]=$array[$key][27]+$row['cpv_impression'];
			
			
			if(!isset($array[$key][28]))
			$array[$key][28]=$row['cpv_spend'];
			else
			$array[$key][28]=$array[$key][28]+$row['cpv_spend'];
			
			if(!isset($array[$key][29]))
			$array[$key][29]=$row['cpv_profit'];
			else
			$array[$key][29]=$array[$key][29]+$row['cpv_profit'];
			
			if(!isset($array[$key][30]))
			$array[$key][30]=$row['cpv_click'];
			else
			$array[$key][30]=$array[$key][30]+$row['cpv_click'];		
		}

		
		if($sponsored_enabled ==1)
		{
			if(!isset($array[$key][31]))
			$array[$key][31]=$row['sponsored_impression'];
			else
			$array[$key][31]=$array[$key][31]+$row['sponsored_impression'];		
			
			if(!isset($array[$key][32]))
			$array[$key][32]=$row['sponsored_click'];
			else
			$array[$key][32]=$array[$key][32]+$row['sponsored_click'];		
			
			if(!isset($array[$key][33]))
			$array[$key][33]=$row['sponsored_spend'];
			else
			$array[$key][33]=$array[$key][33]+$row['sponsored_spend'];			
	
			if(!isset($array[$key][34]))
			$array[$key][34]=$row['sponsored_profit'];
			else
			$array[$key][34]=$array[$key][34]+$row['sponsored_profit'];						
		}	


		if($cpp_addon_enabled ==1)
		{
			if(!isset($array[$key][35]))
			$array[$key][35]=$row['cpp_impression'];
			else
			$array[$key][35]=$array[$key][35]+$row['cpp_impression'];
			
			
			if(!isset($array[$key][36]))
			$array[$key][36]=$row['cpp_spend'];
			else
			$array[$key][36]=$array[$key][36]+$row['cpp_spend'];
			
			if(!isset($array[$key][37]))
			$array[$key][37]=$row['cpp_profit'];
			else
			$array[$key][37]=$array[$key][37]+$row['cpp_profit'];
			
			if(!isset($array[$key][38]))
			$array[$key][38]=$row['cpp_click'];
			else
			$array[$key][38]=$array[$key][38]+$row['cpp_click'];
		}




	}
	
	$sel_qry_res1->free_result();	
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

		$valuestring.="(?,?,?,?,?,?,?,?,?,?".$cpm_insert2." ".$html_select2." ".$siddata2." ".$cpa_insert2." ".$pop_insert2." ".$affiliate_insert2." ".$cpv_insert2.")";
		

		$insert_data[]='';
		$insert_data[]=$v[0];
		$insert_data[]=$v[1];
		$insert_data[]=$v[2];
		$insert_data[]=$v[3];
		$insert_data[]=$current_year;
		$insert_data[]=$v[4];
		$insert_data[]=$v[5];
		$insert_data[]=$v[14];
		$insert_data[]=$v[39];
		
		
		
		
		if($cpm_addon_enabled ==1)
		{
			$insert_data[]=$v[6];
			$insert_data[]=$v[7];
			$insert_data[]=$v[8];
			$insert_data[]=$v[13];
		}
		
		if($html_addon_enabled ==1)
		{
			$insert_data[]=$v[10];
			$insert_data[]=$v[11];
		}
		
		if($category_targeting_enabled ==1)
		$insert_data[]=$v[12];
		
		if($cpa_addon_enabled ==1)
		{
			$insert_data[]=$v[15];
			$insert_data[]=$v[16];
			$insert_data[]=$v[17];
			$insert_data[]=$v[18];
			$insert_data[]=$v[19];
		}
	
		if($pop_addon_enabled ==1)
		{
			$insert_data[]=$v[20];
			$insert_data[]=$v[21];
			$insert_data[]=$v[22];
		}
		
		if($affiliate_addon_enabled ==1)
		{
			$insert_data[]=$v[23];
			$insert_data[]=$v[24];
			$insert_data[]=$v[25];
			$insert_data[]=$v[26];
		}
		
		if($video_addon_enabled ==1)
		{
			$insert_data[]=$v[27];
			$insert_data[]=$v[28];
			$insert_data[]=$v[29];
			$insert_data[]=$v[30];		
		}
			
		
		if($sponsored_enabled ==1)
		{
			$insert_data[]=$v[31];
			$insert_data[]=$v[32];		
			$insert_data[]=$v[33];		
			$insert_data[]=$v[34];				
		}	
			

		if($cpp_addon_enabled ==1)
		{
			$insert_data[]=$v[35];
			$insert_data[]=$v[36];
			$insert_data[]=$v[37];
			$insert_data[]=$v[38];	
		}				
		
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
		$ins_qry_res=$db->execute_query("INSERT INTO ".TABLE_PREFIX."geo_statistics_pub_yearly_temp (`id`,`uid`, `bid`, `impression`,`click`, `time`, `money_spent`,`pub_profit`,`country`,`source`".$cpm_insert1." ".$html_select1." ".$siddata1." ".$cpa_insert1." ".$pop_insert1." ".$affiliate_insert1." ".$cpv_insert1.") VALUES ".$advvalue[1]." ",$advvalue[0]);
		if($ins_qry_res->error =="")
			$num_row_inserted=$num_row_inserted+$ins_qry_res->get_num_records();
		else
		{
			echo"<br>Data insertion to ".TABLE_PREFIX."geo_statistics_pub_yearly_temp failed<br>".$ins_qry_res->get_sql();
			break;
			}	
		}
	}
	if($num_row_inserted == $result_count)
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(2,$previous_hour,'geo_statistics_pub_yearly_temp'));
		echo "<br>BREAKING AFTER COMPLETELY UPDATING LAST YEAR STATISTICS";
		break;
	}
	else
	{
		$up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=?,time=? WHERE task=?",array(1,$previous_hour,'geo_statistics_pub_yearly_temp'));
		echo "<br>BREAKING AFTER PARTIALLY UPDATING LAST YEAR STATISTICS";
		break;
	}
	//echo mysql_error();
	flush();
}while (1);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
	$success_time_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status =? WHERE task=?",array($current_time,2,'geo_cron_success_time'));
		
	
	
	
	
	
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